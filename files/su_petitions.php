<?php
/**
* su_petitions.php: Anfragenmanager
* @author 	partly LOGD-Core, modded and rewritten by talion <t@ssilo.de>
* @version DS-E V/2
*/

// modded by talion (t@ssilo.de) für den Drachenserver (lotgd.drachenserver.de):
// kategorien, webmail, floskeln, Priorität, Permanente Kommentare, versch. Kleinigkeiten

//Dieser Part wird nur durch einen asynchronen HTTP REQUEST aufgerufen
//und gibt alle User zurück die mit der übergebenen IP/ID assoziiert sind
if (isset($_REQUEST['show_closed_petitions']))
{
	$DONT_OVERWRITE_NAV 	= true;
	$BOOL_JS_HTTP_REQUEST 	= true;
	require_once('common.php');

	$str_output = render_closed_petitions();

	//Führt implizit ein die() aus und gibt den Text zurück der ausgegeben wird
	jslib_http_text_output(appoencode($str_output));
}

$str_filename = basename(__FILE__);
require_once('common.php');
$access_control->su_check(access_control::SU_RIGHT_PETITION,true);

page_header('Petition Viewer');

// Standard-Navs
addnav('Zurück');
grotto_nav(array('mundane'=>true,'grotto'=>true));

// END Standard-Navs

output('`c`b`&'.getsetting('townname','Atrahor').'-Callcenter`0`b`c`n');

// Kommentare dauerhaft in Anfrage einfügen
if ( !empty($_POST['comment']) ) {
	$comment = str_replace('`n','',$_POST['comment']);
	$comment = '`n`b`#'.$session['user']['login'].' : `0`b`3'.$comment.'`0';
	$sql = 'UPDATE petitions SET lastact=NOW(),commentcount=commentcount+1,comments=CONCAT(comments,"'.db_real_escape_string($comment).'") WHERE petitionid="'.(int)$_GET['id'].'"';
	db_query($sql);
}

// Zustandsbezeichnungen
$statuses=array(0=>"`bUngel.`b","Gel.","Geschl.");
$str_op = (!empty($_REQUEST['op']) ? $_REQUEST['op'] : '');

switch($str_op) {
		// Zu bearbeiten von setzen
	case 'setfor':
	{
		$pid = (int)$_GET['pid'];
		$str_val = '';
		$arr_grp = null;

		if(isset($_POST['p_for'])) {
			
			if((int)$_POST['p_for'] == 0)
			{
				$str_val = $_POST['p_for'];
			}
			else 
			{
				// Rausfinden, welche Gruppe das ist
				if(($arr_grp = user_get_sugroups((int)$_POST['p_for'])) !== false)
				{	
					// Hat Gruppe überhaupt Anfragen-Recht?
					if($arr_grp[2][access_control::SU_RIGHT_PETITION])
					{			
						$str_val = strip_appoencode($arr_grp[1],3);
					}
					else
					{
						$arr_grp = null;	
					}
				}
			}
			
			$sql = 'UPDATE petitions SET p_for="'.addstripslashes($str_val).'" WHERE petitionid='.$pid;
			db_query($sql);
			if(getsetting('petition_mail_assignment_message',1) == 1)
			{
				
				// Alte Mails löschen
				$sql = 'DELETE FROM mail WHERE body LIKE "%Anfrage (Nummer '.$pid.')%" AND msgfrom=0';
				$res = db_query($sql);
				
				// Wenn an User
				if(is_null($arr_grp))
				{
					$db_result = db_query('SELECT acctid FROM accounts WHERE login="'.addstripslashes($_POST['p_for']).'"');
					$arr_result = db_fetch_assoc($db_result);
					if(count($arr_result)>0)
					{
						$str_body = '
						`tHallo!
						Dir wurde eine Anfrage (Nummer '.$pid.') in der Grotte zugeteilt. Es scheint, als wärest Du als einzige(r)
						in der Lage, diese verfahrene Situation zu lösen, die sich dort den Augen der versammelten Grottenolme präsentiert.
	
						Beehre uns mit Deiner Weisheit und Güte und der Dank einer ganzen Welt sei Dir gewiss...
						oder lies den Scheiss einfach kurz durch und schick den Mist an jemand anders weiter ;-)
						';
						systemmail($arr_result['acctid'],'`$Eine Anfrage wurde Dir zugeteilt`0',$str_body,0,true);
					}
				}
				// Wenn an Gruppe
				else 
				{
					$db_result = db_query('SELECT acctid FROM accounts WHERE superuser="'.(int)$_POST['p_for'].'" AND superuser > 0');
					while($arr_result = db_fetch_assoc($db_result))
					{
						$str_body = '
						`tHallo!
						Deiner Superusergruppe ('.$arr_grp[1].'`t) wurde eine Anfrage (Nummer '.$pid.') in der Grotte zugeteilt. Es scheint, als wäret Ihr als einzige
						in der Lage, diese verfahrene Situation zu lösen, die sich dort den Augen der versammelten Grottenolme präsentiert.
	
						Beehre uns mit Deiner Weisheit und Güte und der Dank einer ganzen Welt sei Dir gewiss...
						oder lies den Scheiss einfach kurz durch und schick den Mist an jemand anders weiter ;-)
						';
						systemmail($arr_result['acctid'],'`$Eine Anfrage wurde '.$arr_grp[1].'`$ zugeteilt`0',$str_body,0,true);	
					}
					
				}
				
				unset($db_result,$arr_result);
			}
			
		}

		redirect($str_filename.'?op=view&id='.$pid);
		break;
	}

		// Kurzbeschreibung setzen
	case 'setdesc':
	{
		$pid = (int)$_GET['pid'];
		$str_val=utf8_htmlentities(strip_appoencode($_POST['short_desc'],3));
		$sql = 'UPDATE petitions SET short_desc="'.addstripslashes($str_val).'" WHERE petitionid='.$pid;
		db_query($sql);
		redirect($str_filename.'?op=view&id='.$pid);
		break;
	}
		// AntwortYeOlde versenden
	case 'sendmessage':
	{

		$pid = (int)$_GET['id'];

		$sql = 'SELECT author,body FROM petitions WHERE petitionid='.$pid;
		$row = db_fetch_assoc(db_query($sql));

		$_POST['subject'] = 'RE: Hilfeanfrage';
		$_POST['body']=str_replace("`n","\n",$_POST['body']);
		$_POST['body']=str_replace("\r\n","\n",$_POST['body']);
		$_POST['body']=str_replace("\r","\n",$_POST['body']);
		$_POST['body']=mb_substr($_POST['body'],0,(int)getsetting("mailsizelimit",1024));
		$_POST['body'] = closetags($_POST['body'],'`c`i`b');

        $ret_id = systemmail($row['author'],($_POST['subject']),($_POST['body']));

		petitionmail(($_POST['subject']),($_POST['body']),$pid,$session['user']['acctid'],1,$row['author'],$ret_id);
		redirect($str_filename.'?op=view&id='.$pid);

		break;
	}

		// AntwortEMail versenden
	case 'sendmail':
	{

		$pid = (int)$_GET['id'];

		$sql = 'SELECT body FROM petitions WHERE petitionid='.$pid;
		$row = db_fetch_assoc(db_query($sql));
		$subject = 'RE: Hilfeanfrage';
		$body=str_replace("`n","\n",$_POST['body']);
		$body=str_replace("\r\n","\n",$body);
		$body=str_replace("\r","\n",$body);
		$body=mb_substr($body,0,(int)getsetting("mailsizelimit",1024));
		$body = closetags($body,'`c`i`b');

		petitionmail($subject,$body,$pid,$session['user']['acctid'],1,0,0);

		$body = "\n( ACHTUNG : Evtl. Antworten auf diese Mail bitte wieder per Anfrage! )\n\n
				".$body."\n\n
				( ACHTUNG : Evtl. Antworten auf diese Mail bitte wieder per Anfrage! )";

		$mail = urldecode($_POST['mail']);

		$from_mail = getsetting('petitionemail','postmaster@localhost');

        savesetting('petitionemailsent',getsetting('petitionemailsent',0)+1);

		$headers = 'From: '.$from_mail;

		send_mail($mail,$subject,$body,$headers);

		redirect($str_filename.'?op=view&id='.$pid);

		break;
		// END Antwort-EMail
	}

		// Alle Anfragen als gelesen markieren
	case 'mark_read':
	{
		$sql = 'SELECT petitionid, lastact FROM petitions';
		$res = db_query($sql,false,true);

		while($p = db_fetch_assoc($res)) {
			$session['petitions'][$p['petitionid']] = date('Y-m-d H:i:s');
		}

		user_set_aei(array('seenpetitions'=>db_real_escape_string(utf8_serialize($session['petitions']))));
		redirect($str_filename);

		break;
	}
	
		// Einzelne Anfrage
	case 'view':
	{
		require_once(LIB_PATH.'browser.lib.php');
		
		// Farben in Anfrage zeigen?
		$bool_show_colors = false;
		if(isset($_GET['toggle_colors']))
		{
			if(!isset($session['su_petitions_show_colors']))
			{
				$session['su_petitions_show_colors'] = null;
			}
			$session['su_petitions_show_colors'] = ($session['su_petitions_show_colors'] === true ? false : true);
		}
		if(isset($session['su_petitions_show_colors']) && $session['su_petitions_show_colors'] == true)
		{
			$bool_show_colors = true;
		}
				
		// Gruß
		$str_greets = str_replace('`n',"\n",get_extended_text('petition_greetings','*',false,false));

		$int_pid = (int)$_GET['id'];

		$sql = 'SELECT 		a.name,a.login,a.acctid,a.loggedin,a.laston,a.activated,a.emailvalidation,
							p.*, a_s.browser, a_s.browser_version
				FROM 		petitions p
				LEFT JOIN 	accounts a
					ON 		a.acctid = p.author
				LEFT JOIN	account_stats a_s
					ON		a_s.acctid = p.author
				WHERE 		p.petitionid='.$int_pid;
		$result = db_query($sql);
		$row = db_fetch_assoc($result);

        addnav('Anfragen');
        if(!isset($_GET['kat']))$_GET['kat']='';
        addnav('Anfragen anzeigen',$str_filename.'?kat='.$_GET['kat']);

        addnav('Aktionen');
		addnav('Navdebug',$str_filename.'?op=navdebug&pid='.$row['petitionid']);

		// In Session markieren, dass angeschaut
		if($session['petitions'][$int_pid]<$row['lastact'])
		{
			$session['petitions'][$int_pid] = date('Y-m-d H:i:s');
			user_set_aei(array('seenpetitions'=>db_real_escape_string(utf8_serialize($session['petitions']))));
		}

		$str_out = '';

		// Navi erstellen
		if (isset($_GET['viewpageinfo']) && $_GET['viewpageinfo']==1){
			addnav('Details ausblenden',$str_filename.'?op=view&id='.$int_pid);
		}
		else{
			addnav('D?Details einblenden',$str_filename.'?op=view&id='.$int_pid.'&viewpageinfo=1');
		}


		addnav('Operationen');
		addnav('Anfrage schließen',$str_filename.'?setstat=2&id='.$int_pid,false,false,false,false,'Möchtest Du die Anfrage wirklich schließen?');
		addnav('U?Als Ungelesen markieren',$str_filename.'?setstat=0&id='.$int_pid);

		if($row['prio'] == 0) {
			addnav('P?Hohe Prio',$str_filename.'?setprio=1&id='.$int_pid);
		}
		else {
			addnav('N?Normale Prio',$str_filename.'?setprio=0&id='.$int_pid);
		}

    if(!isset($session['su_petitions_bookmark_id']))$session['su_petitions_bookmark_id']='';

        $loggedin = false;

		if ($row['acctid']>0){
			$loggedin = user_get_online(0,$row);
		}

		$row['body'] = stripslashes($row['body']);
		// HTML-Tags encoden, um Kuddlmuddl zu vermeiden
		$row['body'] = utf8_htmlspecialchars($row['body']);
		
		// Nur wenn aktiviert: Formatierungscodes in Anfrage anzeigen
		if(!$bool_show_colors)
		{
			$row['body'] = str_replace('`','&#0096;',$row['body']);
		}
		else 
		{
			// &amp; vermeiden
			$row['body'] = str_replace('&amp;','&',$row['body']);
		}

		$str_out .= '<div align="center">
					<table style="width:95%; text-align:left;">
						<tr class="trdark">
							<td width="20%">`b`@Von:`0`b</td>
							<td>';

		if (!empty($row['login'])) {
			$str_maillnk = 'mail.php?op=write&to='.rawurlencode($row['login']).'&body='.URLEncode("\n\n----- Deine Anfrage -----\n".$row['body']).'&subject=RE:+Hilfeanfrage';
			$str_out .= '<a href="#" onClick="'.popup($str_maillnk).';return false;">
					<img src="./images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0">
					</a> ';
			$str_out .= '`b`^'. CRPChat::menulink($row).'`0`b'.($loggedin ? ' `@(online)`0 ' : '');
		}
		else {
			$str_out .= '`^`i[nicht eingeloggt]`i `b'.$row['charname'].'`b'.($loggedin ? ' `@(online)`0 ' : '').'';
		}

		$str_out .= '		`0</td>
						</tr>
						';

							$str_out .= '	<tr class="trdark">
							<td>`b`@Datum:`0`b</td><td>`^`b'.date('d.m.Y H:i:s',strtotime($row['date'])).'`b`0</td>
						</tr>';

							$str_out .= '
						<tr class="trdark">
							<td>`b`@Browser:`0`b</td>
							<td>`^`b'.browser_longname($row['browser']).' V'.$row['browser_version'].'`b`0</td>
						</tr>
						<tr class="trdark"><td>`b`@Inhalt:`0`b</td>
											<td>[ '.create_lnk('Formatierungstags in Anfrage '.($bool_show_colors ? 'aus':'an').'schalten',$str_filename.'?op=view&id='.$int_pid.'&toggle_colors=1').' ]</td>'.
						'</tr>
						<tr class="trlight"><td>&nbsp;</td><td>';
		$str_out .= ''.nl2br($row['body']).'</td></tr>
					</table></div>';

		// Zu bearbeiten von Start
		// Gruppen
		$arr_grps = CCharacter::getSUGroups();
		//Usern
		$arr_users = CCharacter::getSUChars('login,('.user_get_online().') AS online');
		// Superuser
		$res = db_query('SELECT login, ('.user_get_online().') AS online FROM accounts WHERE superuser>0 ORDER BY login ASC');

		$str_lnk = $str_filename.'?op=setfor&pid='.$int_pid;
		addnav('',$str_lnk);
		$str_out .= '`n<form method="POST" action="'.utf8_htmlentities($str_lnk).'">
					Zu bearbeiten von: <select name="p_for" onchange="this.form.submit();">
					<option value="">Allen</option>
					<option> ~~~ </option>';
		foreach ($arr_grps as $id=>$g) 
		{
			if(!$g[2][access_control::SU_RIGHT_PETITION])
			{
				continue;
			}
			$n = strip_appoencode($g[1],3);
			$str_out .= '<option value="'.$id.'" '.($row['p_for'] == $n ? 'selected="selected"' : '').'>'.$n.'</option>';
		}
		$str_out .= '<option> ~~~ </option>';
		foreach ($arr_users as $a) 
		{
			$str_out .= '<option value="'.$a['login'].'" '.($row['p_for'] == $a['login'] ? 'selected="selected"' : '').'>'.$a['login'].' '.($a['online'] ? '(online)':'').'</option>';
		}
		$str_out .= '</select>
					 <input type="submit" value="Los!"></form>';
		// ENDE Zu bearbeiten von

		// Kurzbeschreibung
		$str_lnk = $str_filename.'?op=setdesc&pid='.$int_pid;
		addnav('',$str_lnk);
		$str_out .= '`n<form method="POST" action="'.utf8_htmlentities($str_lnk).'">
					Kurzbeschreibung: <input type="text" name="short_desc" value="'.$row['short_desc'].'" size=25 maxlength=25>
					 <input type="submit" value="Setzen"></form>';
		// ENDE Kurzbeschreibung

		$str_out .= '`n`@Kommentare:`n`0';
		$str_lnk = $str_filename.'?op=view&id='.$int_pid;
		addnav('',$str_lnk);
		$str_out .= $row['comments'].'`n`0
					<hr>
						`&Vorschau: `0'.js_preview('comment').'
						<form method="POST" action="'.utf8_htmlentities($str_lnk).'">
							<input type="text" size="50" maxlength="2000" value="" name="comment" id="comment" />&nbsp;<input type="submit" value="Hinzufügen!">
						</form>[ <a href="'.utf8_htmlentities($str_lnk).'">Aktualisieren</a> ]';

		$str_lnk = 'httpreq_usermenu.php?op=petition_mail_check&pid='.$int_pid.'&time_from='.time();
		$str_refr_lnk = 'su_petitions.php?op=view&id='.$int_pid.'&refresh=1';
		addnav('',$str_lnk);
		addnav('',$str_refr_lnk);

		require_once(LIB_PATH.'jslib.lib.php');
		$str_out .= JS::encapsulate(jslib_httpreq_init().'

						function petanswer_del (pid,mid) {

							if(!confirm("Antwort wirklich löschen?")) {
								return;
							}

							g_req.send( "httpreq_usermenu.php?op=petition_mail_del&mid="+mid+"&pid="+pid,
															function (req) {
																document.getElementById("a_"+mid).style.display = "none";
																LOTGD.parseCommand(LOTGD.getCommandFromRequest(req));
															},
															function () {alert("Fehler bei Ausführung des Befehls!");},
															null,
															null
													);
						}

						function petanswer_check (form) {
							g_req.form = form;
							var pet_post = new LOTGD.HTTPPostVars();
							pet_post.addVar("body",document.getElementById("body").value);
							g_req.send( "'.$str_lnk.'",
															function (req) {
																var cmd = LOTGD.getCommandFromRequest(req);

																if(LOTGD_NO_CMD != cmd) {
																	document.getElementById("mailform").innerHTML
																		= "<span style=\"color:red;\">ACHTUNG! Es wurde bereits eine Antwort auf diese Anfrage verfasst, oder aber du wurdest mittlerweile ausgelogged. Aktualisieren? (Deine Eingabe wird gesichert)<\/span><p><a href=\"'.$str_refr_lnk.'\">Ja!<\/a><\/p>"
																			+ document.getElementById("mailform").innerHTML;
																	return false;
																}
																g_req.form.submit();
															},
															function () {alert("fehler");},
															pet_post,
															null
													);


						}
					');

		// Antworten per Ye Olde
		if (!empty($row['login'])) {

			$answerbody = "\n\n".'----- Deine Anfrage -----'."\n".$row['body'];
			$answersubject = 'RE: Hilfeanfrage';
			$str_out .= '`n`n`@`bMailverkehr:`b`n`0<table><tr><td>';
			$sql = 'SELECT p.*, a.login,m.seen AS is_seen FROM petitionmail p
					LEFT JOIN accounts a ON p.msgfrom=a.acctid
					LEFT JOIN mail m ON m.messageid=p.messageid
					WHERE petitionid="'.$int_pid.'" ORDER BY sent ASC';
			$result = db_query($sql);

			// Löschung ungelesener Antworten
			addpregnav('/httpreq_usermenu.php\?op=petition_mail_del&mid=\d{1,}&pid=\d{1,}/');

			$int_answer_amount = db_num_rows($result);
			$bool_last_user = true;

			if(!$int_answer_amount) {
				$str_out .= '`iBisher keiner!`i`n';
			}
                if(!isset($int_counter))$int_counter=0;
			while ($row2 = db_fetch_assoc($result)) {

				$int_counter++;

				$bool_last_user = true;

				// Wenn Messageid gegeben -> Antwortmail von Admins
				if($row2['messageid']) {
					$bool_last_user = false;
					if(!isset($row2['is_seen'])) { $row2['is_seen'] = 1; }
				}

				$row2['body'] = stripslashes($row2['body']);
				// HTML-Tags encoden, um Kuddlmuddl zu vermeiden
				$row2['body'] = utf8_htmlspecialchars($row2['body']);

				// Nur wenn aktiviert: Formatierungscodes in Anfrage anzeigen
				if(!$bool_show_colors)
				{
					$row2['body'] = str_replace('`','&#0096;',$row2['body']);
				}
				else
				{
					// &amp; vermeiden
					$row2['body'] = str_replace('&amp;','&',$row2['body']);
				}

				$str_out .= '<table class="input" width="100%" id="a_'.$row2['messageid'].'"><tr><td>';
				$str_out .= '`4Datum:`& '.$row2['sent'].' '.($row2['messageid'] ? '(Gelesen: '.$row2['is_seen'].')' : '').'`n`4Von:`& '.$row2['login'].'`n`4Betreff:`& '.$row2['subject'].'`n`4Text:`& ';
				$str_out .= str_replace("\n","`n",$row2['body']);

				// Löschung von ungelesenen Admin-Antworten erlauben
				if(!$row2['is_seen'] && !$bool_last_user) {

					$str_out .= '`n`n[ <a href="javascript:void(0);" id="petanswer_del_'.$row2['petitionid'].'_'.$row2['messageid'].'">Löschen</a> ]
'.JS::event('#petanswer_del_'.$row2['petitionid'].'_'.$row2['messageid'].'','click','petanswer_del('.$row2['petitionid'].','.$row2['messageid'].');').'
					';

				}

				$str_out .= '`0</td></tr></table>`n';
				// Antworten nicht anhängen, wenn von Admin und ungelesen
				if($bool_last_user) {
					$answerbody = "\n\n----- Deine Anfrage -----\n".$row2['body'];
				}
				else {
					if($row2['is_seen']) {
						$answerbody = "\n\n----- Unsere Antwort -----\n".$row2['body'];
					}
				}
				// sinnlos:
				//$answersubject = 'RE: '.$row2['subject'];
			}
			$str_out .= '</td></tr></table>';

			if($int_answer_amount == 0) {

				$answerbody = "\n\n".$str_greets.$answerbody;

			}

			$str_lnk = $str_filename.'?op=sendmessage&id='.$int_pid;

			if(isset($session['pet_refresh_'.$int_pid])) {
				$answerbody = $session['pet_refresh_'.$int_pid];
				unset($session['pet_refresh_'.$int_pid]);
			}

			$str_out .= '<div id="mailform" align="center">
					<form action="'.utf8_htmlentities($str_lnk).'" method="post" id="form32341">
					`@`n`bIngame-Mail schreiben`b`n`n`0'.
								//Betreff: <input type="text" name="subject" value="'.$answersubject.'">`n
								'<textarea name="body" id="body" class="input" cols="120" rows="20">'.$answerbody.'</textarea>`n`n
					<input type="submit" class="button" value="Senden"></form>

                        '.JS::event('#form32341','submit','petanswer_check(this);return false;').'

					</div>`n';
			addnav('',$str_lnk);

			$sql = 'UPDATE petitionmail SET seen=1 WHERE petitionid="'.$int_pid.'"';
			db_query($sql);
		}
		// Antworten per EMail
		else {

			if(!empty($row['email'])) {

				$answerbody = "\n\n".'----- Deine Anfrage -----'."\n\n".$row['body'];
				$answersubject = 'RE: Hilfeanfrage';

				$str_out .= '`n`n`@`bBisherige Antworten an '.$row['email'].':`b`n<table><tr><td>';
				$sql = 'SELECT petitionmail.*, accounts.login FROM petitionmail LEFT JOIN accounts ON petitionmail.msgfrom=accounts.acctid WHERE petitionid="'.$_GET['id'].'" ORDER BY sent ASC';
				$result = db_query($sql);

				$int_answer_amount = db_num_rows($result);

				if(!$int_answer_amount) {
					$str_out .= '`iBisher keine!`i`n';
				}

				while ($row2 = db_fetch_assoc($result)) {
					
					$row2['body'] = stripslashes($row2['body']);
					// HTML-Tags encoden, um Kuddlmuddl zu vermeiden
					$row2['body'] = utf8_htmlspecialchars($row2['body']);
					
					// Nur wenn aktiviert: Formatierungscodes in Anfrage anzeigen
					if(!$bool_show_colors)
					{
						$row2['body'] = str_replace('`','&#0096;',$row2['body']);
					}
					else 
					{
						// &amp; vermeiden
						$row2['body'] = str_replace('&amp;','&',$row2['body']);
					}
					
					$str_out .= '<table class="input" width="100%"><tr><td>';
					$str_out .= '`4Datum:`& '.$row2['sent'].'`n`4Von:`& '.$row2['login'].'`n`4Betreff:`& '.$row2['subject'].'`n`4Text:`& ';
					$str_out .= str_replace("\n","`n",$row2['body']);
					$str_out .= '</td></tr></table>`n';

					$answerbody = "\n\n----- Deine Anfrage -----\n".$row2['body'];

					//$answersubject = 'RE: '.$row2['subject'];
				}
				$str_out .= '</td></tr></table>';

				if($int_answer_amount == 0) {

					$answerbody = "\n\n".$str_greets.$answerbody;

				}

				$str_lnk = $str_filename.'?op=sendmail&id='.$int_pid;

				if(isset($session['pet_refresh_'.$int_pid])) {
					$answerbody = $session['pet_refresh_'.$int_pid];
					unset($session['pet_refresh_'.$int_pid]);
				}

				$str_out .= '<div id="mailform" align="center">
							<form action="'.$str_lnk.'" method="post" id="form72837">';
				$str_out .= '`n`b`@E-Mail an '.$row['email'].' von '.getsetting('petitionemail','').' schreiben:`b`n`n';
				$str_out .=
									//'Betreff: <input type="text" name="subject" value="'.$answersubject.'">
									'<textarea name="body" id="body" class="input" cols="100" rows="15">'.$answerbody.'</textarea>`n
						<input type="hidden" name="mail" value="'.urlencode($row['email']).'"><input type="submit" class="button" value="Senden"></form>

						'.JS::event('#form72837','submit','petanswer_check(this);return false;').'

						</div>`n';
				addnav('',$str_lnk);
				$sql = 'UPDATE petitionmail SET seen=1 WHERE petitionid='.$int_pid;
				db_query($sql);

			}
		}

		output($str_out,true);

		if (isset($_GET['viewpageinfo'])){
			$output .= appoencode('`n`n`b`@Sessiondaten:`b`&`n');
			$row['pageinfo']=stripslashes($row['pageinfo']);
			$body = utf8_htmlentities($row['pageinfo']);
			$output .= '<div style="font-family: fixed-width;width:500px">'.nl2br($body).'</div>';
		}
		if ($row['status']==0) {
			$sql = 'UPDATE petitions SET status=1 WHERE petitionid='.$int_pid;
			$result = db_query($sql);
		}

		break;
		// END einzelne Anfrage anzeigen
	}
	
	// Versch. Debuginfos zu Navi anzeigen
	case 'navdebug':
	{
		
		$pid = (int)$_GET['pid'];
		
		$petition = db_get_all('SELECT * FROM petitions WHERE petitionid='.$pid);
		if(sizeof($petition) == 0) {
			throw new Exception('No petition record found with id '.$pid);
		}
		$petition = $petition[0];
		
		$uid = $petition['author'];
		
		
		// convert print_r output (stored in pagedata - a little bit quirky since it'd be much better 
		// and versatile to save session data as a serialized string) back to array
		// piece of code taken from php.net (print_r comment page)
		$printr = $petition['pageinfo'];
		$newarray = array();      
        $a[0] = &$newarray;       
        if (utf8_preg_match_all('/^\s+\[(\w+).*\] => (.*)\n/m', $printr, $match)) {                       
            foreach ($match[0] as $key => $value) {   
                $tabs = mb_substr_count(mb_substr($value, 0, mb_strpos($value, "[")), "        ");
                if ($match[2][$key] == 'Array' || mb_substr($match[2][$key], -6) == 'Object') {                   
                    $a[$tabs+1] = &$a[$tabs][$match[1][$key]];
                }                           
                else {
                    $a[$tabs][$match[1][$key]] = $match[2][$key];                   
                }
            }
        }   
        // end conversion
        			
		// Debuglog
		$debuglog = db_get_all('SELECT date,message FROM debuglog WHERE actor='.$uid.' AND date <= "'.$petition['date'].'" ORDER BY id DESC LIMIT 10');
						
		// Aktuelle allowednavs
		$allowednavsUser = $newarray['user']['allowednavs'];
		$allowednavsSession = $newarray['allowednavs'];
		
		// Restorepage
		$rp = $newarray['user']['restorepage'];
		
		// Req-Debug
		$reqDebug = $newarray['req_debug'];
				
		// Rp-Debug
		$rpDebug = $newarray['rp_debug'];
		
		// lastnewday
		$lastnewday = $newarray['user']['lasthit'];
		
		// print!
		
		$debuglogOut = '<table>';
		foreach ($debuglog as $d) {
			$debuglogOut .= '<tr><td>'.$d['date'].'</td><td>'.$d['message'].'</td></tr>';
		}
		$debuglogOut .= '</table>';
		
		$str_out = '`bNavi-Debug für UID '.$uid.'`b`n`n
		
					<table>
						<tr>
							<td width="50%">`bDebuglog`b</td>
							<td width="50">&nbsp;</td>
							<td width="50%"></td>
						</tr>
						<tr>
							<td colspan="3">'.$debuglogOut.'</td>
						</tr>
						<tr>
							<td>`bAllowednavs Session`b</td>
							<td width="50">&nbsp;</td>
							<td>`bAllowednavs User`b</td>
						</tr>
						<tr>
							<td><pre>'.print_r($allowednavsSession,true).'</pre></td>
							<td width="50">&nbsp;</td>
							<td><pre>'.print_r($allowednavsUser,true).'</pre></td>
						</tr>
						<tr>
							<td>`bRequestdebug`b</td>
							<td width="50">&nbsp;</td>
							<td>`bRestorepagedebug`b</td>
						</tr>
						<tr>
							<td><pre>'.print_r($reqDebug,true).'</pre></td>
							<td width="50">&nbsp;</td>
							<td>Current rp: '.$rp.'<br><pre>'.print_r($rpDebug,true).'</pre></td>
						</tr>
						<tr>
							<td>`bLast newday`b</td>
							<td width="50">&nbsp;</td>
							<td>`bcheckday annotation in output?`b</td>
						</tr>
						<tr>
							<td>'.$lastnewday.'</td>
							<td width="50">&nbsp;</td>
							<td>'.(mb_strpos($newarray['user']['output'],'<!--CheckNewDay()-->') > -1 ? 'ja':'nein').'</td>
						</tr>
					</table>';
		output($str_out);		
		
		addnav('Zurück zur Anfrage','su_petitions.php?op=view&id='.$pid);
		
		break;
	}

		// Anfragenliste
	default:
	{

		// Veraltete Anfragen löschen
		if(!$session['daily']['delpetitions']) {
			//das macht jeder Superuser nur noch einmal pro Ingame-Tag
			$session['daily']['delpetitions']=1;
			$sql = 'SELECT petitionid FROM petitions WHERE status=2 AND datediff(now(),lastact)>14';
			$result = db_query($sql,false,true);

			$arr_ids = array();
			while ($row = db_fetch_assoc($result)) {
				$arr_ids[] = $row['petitionid'];
			}
			if(count($arr_ids)>0)
			{
				db_query('DELETE FROM petitionmail WHERE petitionid IN ('.implode(',',$arr_ids).')');
			}

			$sql = 'DELETE FROM petitions WHERE status=2 AND  datediff(now(),lastact)>14';
			db_query($sql);
		}
		// END veraltete Anfragen löschen

		// Anfragenstatus "bereits gesehen"
		if(!isset($session['petitions']))
		{
			$arr_petids=db_get_all('SELECT petitionid FROM petitions','petitionid'); //nur vorhandene Anfragen sind interessant
			$rowe=user_get_aei('seenpetitions');
			$session['petitions']=utf8_unserialize($rowe['seenpetitions']);
			if(!is_array($session['petitions']))
			{
				$session['petitions']=array();
			}
			foreach($session['petitions'] as $key=>$val)
			{ //gelöschte Anfragen auch aus dem Gesehen-Status löschen
				if(!array_key_exists($key,$arr_petids))
				{
					unset($session['petitions'][$key]);
				}
			}
		}
		// END Anfragenstatus "bereits gesehen"

		// Anfragen-Statusänderungen
		if ( isset($_GET['setstat']) ) {
			$sql = 'UPDATE petitions SET status="'.(int)$_GET['setstat'].'" '.($_GET['setstat'] == 2 ? ',prio=0':'').' WHERE petitionid='.(int)$_GET['id'];
			db_query($sql);
			// Wenn geschlossen:
			if($_GET['setstat'] == 2)
			{
				//Letzte-Bearbeitung-Datum aktualisieren
				$sql = 'UPDATE petitions SET lastact=NOW() WHERE petitionid='.(int)$_GET['id'];
				db_query($sql);
				// Alte Mails löschen
				$sql = 'DELETE FROM mail WHERE body LIKE "%Anfrage (Nummer '.$_GET['id'].')%" AND msgfrom=0';
				$res = db_query($sql);
			}
		}

		if ( isset($_GET['setprio']) ){
			$sql = 'UPDATE petitions SET prio="'.(int)$_GET['setprio'].'" WHERE petitionid='.(int)$_GET['id'];
			db_query($sql);
		}
		// END Anfragen-Statusänderungen

		//Geschlossene auslassen
		$sql_where .= (!empty($sql_where)?' AND p.status != 2 ':' p.status != 2 ');

		if(!empty($sql_where))
		{
			$sql_where = 'WHERE '.$sql_where;
		}

		$sql = 'SELECT 		p.petitionid,p.prio,p.charname,p.date, p.status,p.lastact,p.kat,p.p_for,p.commentcount,p.short_desc,
							a.name,
							IF(petitionmail.petitionid > 0,COUNT(*),0) AS petmails
				FROM 		petitions p
				LEFT JOIN 	petitionmail
					USING	(petitionid)
				LEFT JOIN 	accounts a
					ON 		a.acctid = p.author
				'.$sql_where.'
				GROUP BY 	p.petitionid
				ORDER BY 	p.status ASC, p.prio DESC, p.lastact DESC, p.date DESC';

		$result = db_query($sql, false, true);
		$kat_recent = ( !empty($_GET['kat']) ) ? $_GET['kat'] : -1;

    addnav('Aktionen');
		addnav('Aktualisieren',$str_filename.'?kat='.$_GET['kat']);
		addnav('Alle Anfragen abhaken',$str_filename.'?op=mark_read');

		$str_trclass = 'trdark';

		$str_out = '<table border="0" cellspacing="3" cellpadding="3" style="text-align:left;">
						'.$str_search.'
						<tr class="trhead">
							<td>Ops</td><td>Num</td><td>Von</td><td>Datum</td><td>Status</td><td>Komm.</td><td>Kat</td>
						</tr>';
		
		$arr_user_sugrp = user_get_sugroups($session['user']['superuser']);
		$arr_user_sugrp[1] = strip_appoencode($arr_user_sugrp[1],2);
		
		addpregnav('/'.$str_filename.'\?op=(view|del)&id=\d+?&kat=(-?\d*)?/');
		addpregnav('/'.$str_filename.'\?setstat=[012]&id=\d+?&kat=(-?\d*)?/');
		
		while( $row = db_fetch_assoc($result)){

			$str_trclass = ($str_trclass == 'trdark' ? 'trlight' : 'trdark');
			$str_trplumi = ($row['status']==2?' id="'.plu_mi_unique_id('petition_status_closed').'" '.(plu_mi_get_val('petition_status_closed')? '':'style="display:none;"'):'');

			$str_out .= '<tr class="'.$str_trclass.'"'.$str_trplumi.'>
							<td nowrap="nowrap">
								<a href="'.$str_filename.'?op=view&amp;id='.$row['petitionid'].'&amp;kat='.$kat_recent.'"><img src="./images/icons/petition_view.png" border="0" title="Ansehen"></a>&nbsp;

								<a href="'.$str_filename.'?setstat=0&amp;id='.$row['petitionid'].'&amp;kat='.$kat_recent.'"><img src="./images/icons/petition_unread.png" border="0" title="Ungelesen"></a>&nbsp;
								<a href="'.$str_filename.'?setstat=1&amp;id='.$row['petitionid'].'&amp;kat='.$kat_recent.'"><img src="./images/icons/petition_read.png" border="0" title="Gelesen"></a>&nbsp;
								<a href="'.$str_filename.'?setstat=2&amp;id='.$row['petitionid'].'&amp;kat='.$kat_recent.'"><img src="./images/icons/petition_closed.png" border="0" title="Schließen"></a>&nbsp;
							</td>
							<td>'.($row['prio'] ? '`^' : '').$row['petitionid'].'`0</td>
							<td>';

			if (empty($row['name']))
			{
				$str_out .= '`i[nicht eingeloggt]`i '.$row['charname'];
			}
			else
			{
				$str_out .=  $row['name'];
			}

			$str_out .= '	</td>
							<td>'.date('d.m.y<\b\r>H:i:s',strtotime($row['date'])).'</td>
							<td>'.$statuses[$row['status']].($row['lastact']>max($session['lastlogoff'],$session['petitions'][$row['petitionid']])?'`4*`0':'').'</td>
							<td>'.$row['commentcount']
								.(
									!$row['petmails'] && strtotime($row['date']) < strtotime('-7 days')
									?'<img src="./images/icons/attention_red_16x16.gif" width="16" height="16" alt="Mehr als 7 Tage unbeantwortet!" title="Mehr als 7 Tage unbeantwortet!" />'
									:
								(
									!$row['petmails'] && strtotime($row['date']) < strtotime('-2 days')
									?'<img src="./images/icons/attention_yellow_16x16.gif" width="16" height="16" alt="Mehr als 2 Tage unbeantwortet!" title="Mehr als 2 Tage unbeantwortet!" />'
									:''
								)).' </td>
							<td>'.(!empty($row['short_desc'])?$row['short_desc']:$ARR_PETITION_KATS[$row['kat']]).(!empty($row['p_for']) ? '`nFür: '.($arr_user_sugrp[1] == $row['p_for'] ? '`$' : '').($session['user']['login'] == $row['p_for'] ? '`j' : '') .$row['p_for'].'`0' : '').'</td>
						</tr>';
		}
		// END Listenschleife
		//Plumi für die geschlossenen Anfragen
		$str_out .= '
					<tr class="trhead">
						<td colspan="7">
							'.plu_mi('petition_status_closed',false).'Geschlossene Anfragen:
						</td>
					</tr>';
					
		$str_out .= '
					<tr>
						<td colspan="7">
						'.jslib_dyn_content('petition_status_closed', $str_filename.'?show_closed_petitions=1', DYN_CONNECT_TO_PLUMI| DYN_PLUMI_HIDE | DYN_DIV_EXISTS).'
						</td>
					</tr>';

		$str_out .= '</table>
						`i(Geschlossene Anfragen werden nach 14 Tagen automatisch gelöscht)`i`n
						`n`bLegende:`b`n`&Ungelesen:`0 Frisch reingekommen. Niemand arbeitet bisher an diesem Problem.
						`n`&Gelesen:`0 Die Anfrage wurde gelesen und (hoffentlich) zugeteilt.
						`n`&Geschlossen:`0 Diese Anfrage wurde bearbeitet. Es sollte keine weitere Arbeit mehr nötig sein.`n`n
						`tWenn eine Anfrage gelesen wird`0, wird sie automatisch als gelesen markiert, wenn sie nicht schon als geschlossen markiert war.`n
						`tWenn du ein Problem nicht lösen kannst`0 und das Problem neu ist, markiere die Anfrage wieder als ungelesen, damit ein anderer dem Spieler helfen kann. Falls du weißt wer dafür zuständig ist, teile die Anfrage demjenigen zu, der Ungelesen-Status braucht in dem Fall nicht erneut gesetzt werden.`n
						`tWenn du eine Anfrage übernimmst`0, mache das irgendwie kenntlich, z.B. indem du sie dir selbst zuteilst.`n
						Wenn du eine `tzugeteilte Aufgabe nicht lösen`0 kannst, reiche sie möglichst zielgenau weiter.`n
						`tWenn eine Anfrage erfolgreich bearbeitet wurde`0, markiere sie als geschlossen. Sie wird dann nach 14 Tagen (nach Schließungsdatum) automatisch gelöscht. `tFalls der User nochmal antwortet`0 wird eine geschlossene Anfrage automatisch wieder geöffnet.';

		output($str_out);

		break;
		// END Anfragenliste
	}
}

page_footer();

function render_closed_petitions()
{
	global $ARR_PETITION_KATS, $session,$statuses,$str_filename,$kat_recent;
	$sql = 'SELECT 		p.petitionid,p.prio,p.charname,p.date, p.status,p.lastact,p.kat,p.p_for,p.commentcount,p.short_desc,
							a.name,
							IF(petitionmail.petitionid > 0,COUNT(*),0) AS petmails
				FROM 		petitions p
				LEFT JOIN 	petitionmail
					USING	(petitionid)
				LEFT JOIN 	accounts a
					ON 		a.acctid = p.author
				WHERE		p.status = 2
				GROUP BY 	p.petitionid
				ORDER BY 	p.status ASC, p.prio DESC, p.lastact DESC, p.date DESC';

		$result = db_query($sql, false, true);

		$str_trclass = 'trdark';

		$str_out = '<table border="0" cellspacing="3" cellpadding="3" style="text-align:left;">
						<tr class="trhead">
							<td>Ops</td><td>Num</td><td>Von</td><td>Datum</td><td>Status</td><td>Komm.</td><td>Kat</td>
						</tr>';
		
		$arr_user_sugrp = user_get_sugroups($session['user']['superuser']);
		$arr_user_sugrp[1] = strip_appoencode($arr_user_sugrp[1],2);
		$int_count = db_num_rows($result);

		while( $row = db_fetch_assoc($result)){

			$str_trclass = ($str_trclass == 'trdark' ? 'trlight' : 'trdark');

			$str_out .= '<tr class="'.$str_trclass.'">
								<td nowrap="nowrap">
									<a href="'.$str_filename.'?op=view&amp;id='.$row['petitionid'].'&amp;kat='.$kat_recent.'"><img src="./images/icons/petition_view.png" border="0" alt="Ansehen"></a>&nbsp;
									<a href="'.$str_filename.'?op=del&amp;id='.$row['petitionid'].'&amp;kat='.$kat_recent.'" onClick="return confirm(\'Wirklich löschen?\');"><img src="./images/icons/petition_delete.png" border="0" alt="Löschen"></a>&nbsp;
									<a href="'.$str_filename.'?setstat=0&amp;id='.$row['petitionid'].'&amp;kat='.$kat_recent.'"><img src="./images/icons/petition_unread.png" border="0" alt="Ungelesen"></a>&nbsp;
									<a href="'.$str_filename.'?setstat=1&amp;id='.$row['petitionid'].'&amp;kat='.$kat_recent.'"><img src="./images/icons/petition_read.png" border="0" alt="Gelesen"></a>&nbsp;
									<a href="'.$str_filename.'?setstat=2&amp;id='.$row['petitionid'].'&amp;kat='.$kat_recent.'"><img src="./images/icons/petition_closed.png" border="0" alt="Schließen"></a>&nbsp;
								</td>
								<td>'.($row['prio'] ? '`^' : '').$row['petitionid'].'`0</td>
								<td>';

			if (empty($row['name'])){
				$str_out .= '`i[nicht eingeloggt]`i '.$row['charname'];
			}
			else{
				$str_out .=  $row['name'];
			}

			$str_out .= '	</td>
								<td>'.date('d.m.y<\b\r>H:i:s',strtotime($row['date'])).'</td>
								<td>Geschl'.($row['lastact']>max($session['lastlogoff'],$session['petitions'][$row['petitionid']])?'`4*`0':'').'</td>
								<td>'.$row['commentcount'].'</td>
								<td>'.(!empty($row['short_desc'])?$row['short_desc']:$ARR_PETITION_KATS[$row['kat']]).(!empty($row['p_for']) ? '`nFür: '.($session['user']['login'] == $row['p_for'] || $arr_user_sugrp[1] == $row['p_for'] ? '`$':'').$row['p_for'].'`0' : '').'</td>
							</tr>';
		}
		
		$str_out .= '</table>';
		// END Listenschleife
		return $str_out;
}
?>
