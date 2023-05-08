<?php
/**
* board.lib.php: Funktionsbibliothek für Methoden, die zur Modifizierung / Anzeige von Schwarzen Brettern dienen
* @author talion <t@ssilo.de>
* @version DS-E V/2
*/

/**
* Zeigt ein Nachrichtenbrett an
*@param string Interne Kategorie, deren Einträge angezeigt werden sollen
*@param int Löschen von Einträgen erlauben für: 0 = Keinen, 1 = Autor, 2 = Alle (Optional, Standard 0)
*@param string Überschrift (Optional)
*@param string Nachricht bei leerer Kategorie (Optional)
*@param bool YeOlde-Maillink anzeigen (abhängig von Name anzeigen = true) (Optional, Standard true)
*@param bool Datum anzeigen (Optional, Standard false)
*@param bool Aktualisieren-Button anbieten (Optional, Standard false)
*@param bool Namen bei Einträgen zeigen (Optional, Standard true)
*@param int Limit der Ausgaben (Optional, Standard 200)
*@param bool Editieren der Beiträge erlauben?
*@author talion
*/
function board_view ($section,$del=0,
					$header='Am schwarzen Brett sind folgende Nachrichten zu lesen:',
					$nomsg='Es sind keine Nachrichten vorhanden!',
					$showmail=true,$showdate=false,$showrefresh=false,$shownames=true,
					$limit=200,
					$bool_edit_allowed = false) {
	global $session,$Char;
	$link = calcreturnpath();
	
	if($_GET['board_action'] == 'del') 
	{
		debuglog('löschte board #'.((int)$_GET['msgid']).' bei '.$section);
		$sql = 'DELETE FROM boards WHERE id='.(int)$_GET['msgid'];
		db_query($sql);
		redirect( utf8_preg_replace('/(\?|&)(board_action|msgid)=(\w)*/','',$link) );
	}	
	else 
	{
		$sql = 'SELECT a.acctid,a.name,a.login,b.*
		FROM boards b
		LEFT JOIN accounts a ON a.acctid=b.author
		WHERE b.section="'.$section.'"

		AND a.acctid NOT IN ('.CIgnore::ignore_sql(CIgnore::IGNO_BOARDS).')

		ORDER BY b.postdate DESC, b.expire DESC
		LIMIT '.$limit;
		$res = db_query($sql);
		$out = (db_num_rows($res)) ? $header : $nomsg;
		
		while($msg = db_fetch_assoc($res)) 
		{
			$link_del = $link.(( mb_strpos($link,'&') || mb_strpos($link,'?') ) ? '&' : '?').'board_action=del&msgid='.$msg['id'];
			$link_edit = $link.(( mb_strpos($link,'&') || mb_strpos($link,'?') ) ? '&' : '?').'board_action=edit&msgid='.$msg['id'];
			$date = ($showdate) ? '`&(`i '.date('d.m.y',strtotime($msg['postdate'])).' `i)`n' : '';					
			
			$out .= '`n`n
			<div id="bb_'.$msg['id'].'">';
			if($shownames) {
				
				$str_extra_init = ($msg['author'] == $Char->acctid) ? '{m_msgid: '.($bool_edit_allowed ? $msg['id'] : 0).', m_msgauthor:'.$msg['author'].' }' : ' {} ';
				$out .= ($showmail ? '`&<img src="./images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0">'
                        .CRPChat::menulink($msg,(($bool_edit_allowed && ($msg['author'] == $Char->acctid)) ? 'board_edit,'.$link_edit : ''))
                        : $msg['name']).'`n';
	
			}
			$out .= $date;
			
			$str_edit = '';
			$bool_edit = false;
			//Einträge editieren			
			if($bool_edit_allowed == true && $msg['author'] == $Char->acctid)
			{				
				addnav('',$link_edit);
				$str_edit = '<a href="'.$link_edit.'"><img src="./images/icons/profil.gif" alt="Editieren" border=""/></a>';
			}

			if($bool_edit_allowed == true && $_GET['board_action'] == 'edit' && !is_null_or_empty($_POST['bb_message']) && (int)$_GET['msgid'] == $msg['id'])
			{
				$msg['message'] = str_replace('`0','',$_POST['bb_message']);
				$msg['message'] .= '`0';
				$sql = 'UPDATE boards SET message="'.db_real_escape_string($msg['message']).'" WHERE id='.(int)$_POST['msgid'];
				db_query($sql);
			}
			elseif($bool_edit_allowed == true && $_GET['board_action'] == 'edit' && (int)$_GET['msgid'] == $msg['id'])
			{
				$bool_edit = true;
				$msg['message'] = str_replace('`0','',$msg['message']);
				$msg['message'] = str_replace(array('`','³','²'),array('``','³³','²²'),$msg['message']);
				$out .= form_header($link_edit);
				$out .= '<input type="text" name="bb_message" value="'.$msg['message'].'" size="60" />
							 <input type="hidden" name="msgid" value="'.$msg['id'].'" />
							 <input type="submit" value="Ändern" />';
				$out .= form_footer();
			}

			
			if($bool_edit == false)
			{
				$out .= '`0'.$str_edit.' '.strip_tags(closetags($msg['message'],'`b`c`i'));
				if( ($del == 1 && $session['user']['acctid'] == $msg['author']) || $del >= 2)
				{
					$out .= '`n'.create_lnk('[ Entfernen ]',$link_del,true,false,'Wirklich löschen?');
				}
			}

			
			$out .= '</div>';
		}
		
		if($showrefresh) 
		{
			$out .= '`n`n'.create_lnk('[ Aktualisieren ]',$link);
		}
		output($out,true);
	}
}

/**
 *Zeigt Formular zum Einstellen eines Boardeintrags an
 *@param string Text des Buttons (optional)
 *@param string Handlungsanweisung vor Eingabefeld (optional)
 *@param int maximale Länge der Message (optional, Standard 500 Zeichen) (Parameter am 18.11.2008 ohne konkretes Einbauziel hinzugefügt und generelles Limit auf 400 erhöht)(09.02.2010 auf 500 aufgestockt ;)) || 03.10.10 800 Zeichen^^
 *@author talion
 */
function board_view_form ($buttontext='Aufgeben',$msg='Hier kannst du deine Nachricht eingeben:',$msg_maxlen=800)
{
	$link = calcreturnpath();
	$link .= ( mb_strpos($link,'&') || mb_strpos($link,'?') ) ? '&' : '?';
	
	output($addmsg.'`n`&Vorschau:`^ ');
	rawoutput(js_preview('msg'));
	$output.='`0`n`n<form action="'.utf8_htmlentities($link).'board_action=add" method="POST">';
	$output.=$msg.'`0 <input type="text" name="msg" id="msg" size="60" maxlength="'.$msg_maxlen.'">';
	$output.='<input type="submit" name="ok" value="'.$buttontext.'">`n';
	$output.='</form>';
	
	output($output,true);
	addnav('',$link.'board_action=add');
	
}

/**
 *Schreibt einen Boardeintrag in die DB
 *@param string Board-ID
 *@param int Anzeigedauer in Realtagen (optional, Standard 100)
 *@param int maximale Anzahl Messages pro Spieler (optional, Standard 0 -> unbegrenzt)
 *@param string Message (optional, als Standard wird $_POST['msg'] genommen)
 *@param int maximale Länge der Message (optional, Standard 500 Zeichen)
 *@author talion
 */
function board_add ($section,$days=180,$max_posts=0,$msg='',$msg_maxlen=800) {
	global $session;
	
	if($max_posts > 0) 
	{
		$sql = 'SELECT id FROM boards WHERE author='.$session['user']['acctid'].' AND section="'.$section.'"';
		$res = db_query($sql);
		if(db_num_rows($res) >= $max_posts) 
		{
			return(-1);
		}
	}
	if(mb_strlen($msg) == 0) {
		$msg = mb_substr($_POST['msg'],0,$msg_maxlen);
	}
	
	$msg.='`0';
	$sql = 'INSERT INTO boards SET section="'.$section.'",author='.$session['user']['acctid'].',message="'.db_real_escape_string($msg).'",postdate=NOW(),expire="'.date("Y-m-d H:i:s",strtotime(date("r")."+".$days." days")).'"';
	db_query($sql);
}


/**
 * Zeigt eine Umfrage an, dient als Handler für Stimmabgabe, Schließen + Löschen einer Umfrage
 * GET['poll_action']: del = Löschen, close = Schließen, open = Öffnen, vote = Abstimmen
 * 
 * @param string Interne ID der anzuzeigenden Umfrage
 * @param int Löschen der Umfrage erlauben für: 0 = Keinen, 1 = Autor, 2 = Alle (Optional, Standard 0)
 * @param int Schließen der Umfrage erlauben für: 0 = Keinen, 1 = Autor, 2 = Alle (Optional, Standard 0)
 * @param string Beschriftung für Abstimmenbutton (optional)
 * @author talion
 * @return bool false bei einem Fehler oder wenn Umfrage nicht vorhanden ist
*/
function poll_view ($str_section, $int_del=0, $int_close=0, $str_button='Abstimmen') {
	
	global $session;
	
	$str_link = calcreturnpath();
	
	if($_GET['poll_action'] == 'del') {
		
		$int_pid = (int)$_GET['poll_id'];

		if(!$int_pid) {return(false);}	
		
		poll_delete($int_pid);
		
		redirect( utf8_preg_replace('/(\?|&)(poll_action|poll_id)=(\w)*/','',$str_link) );
		return (true);
		
	}
	else if($_GET['poll_action'] == 'close') {
		
		$int_pid = (int)$_GET['poll_id'];

		if(!$int_pid) {return(false);}	
		
		// Poll deaktivieren
		$sql = 'UPDATE polls SET closed=1 WHERE id='.$int_pid;
		db_query($sql);
		
		redirect( utf8_preg_replace('/(\?|&)(poll_action|poll_id)=(\w)*/','',$str_link) );
		return (true);
		
	}
	else if($_GET['poll_action'] == 'open') {
		
		$int_pid = (int)$_GET['poll_id'];

		if(!$int_pid) {return(false);}
		
		// Poll aktivieren
		$sql = 'UPDATE polls SET closed=0 WHERE id='.$int_pid;
		db_query($sql);
		
		redirect( utf8_preg_replace('/(\?|&)(poll_action|poll_id)=(\w)*/','',$str_link) );
		return (true);
		
	}
	else if($_GET['poll_action'] == 'vote') {
		
		// Veraltete Polls löschen
		$sql = 'SELECT id FROM polls WHERE expire < "'.date('Y-m-d H:i:s').'"';
		$res = db_query($sql);
		if(db_num_rows($res)) {
            $str_delids = db_create_list($res);
            $str_delids = implode(',',$str_delids[0]);
			$sql = 'DELETE FROM polls WHERE id IN ('.$str_delids.')';
			db_query($sql);
			$sql = 'DELETE FROM pollresults WHERE pollid IN ('.$str_delids.')';
			db_query($sql);
		}
		// END Veraltete Polls löschen
		
		$int_pid = (int)$_GET['poll_id'];
		$int_vote = (int)$_POST['vote'];

		if(!$int_pid || !$int_vote) {return(false);}
		
		// Bisherige Stimmen löschen
		$sql = 'DELETE FROM pollresults WHERE account='.$session['user']['acctid'].' AND pollid='.$int_pid;
		db_query($sql);
		
		// Stimme abgeben
		db_insert('pollresults',array('pollid'=>$int_pid,'choice'=>$int_vote,'account'=>$session['user']['acctid']));
		
		redirect( utf8_preg_replace('/(\?|&)(poll_action|poll_id)=(\w)*/','',$str_link) );
		return (true);
		
	}
	else {	// Poll anzeigen

		// Poll abrufen
		$sql = 'SELECT * FROM polls WHERE section="'.db_real_escape_string($str_section).'" LIMIT 1';
		$arr_poll = db_fetch_assoc(db_query($sql));
		
		if(!$arr_poll['id']) {return(false);}
		
		// Votes abrufen
		$int_count = 0;
		$arr_votes = array();
		$sql = 'SELECT COUNT(*) AS c,choice FROM pollresults WHERE pollid='.$arr_poll['id'].' GROUP BY choice';
		$res = db_query($sql);
		while($v = db_fetch_assoc($res)) {
			$int_count += $v['c'];
			$arr_votes[$v['choice']] = $v['c'];
		}
		
		// Wofür haben wir gestimmt?
		$sql = 'SELECT choice FROM pollresults WHERE pollid='.$arr_poll['id'].' AND account='.$session['user']['acctid'];
		$arr_our = db_fetch_assoc(db_query($sql));
		
		$int_count = array_sum($arr_votes);

		$str_link .= (( mb_strpos($str_link,'&') || mb_strpos($str_link,'?') ) ? '&' : '?');
		
		$str_out = '`b`&Umfrage: '.$arr_poll['question'].'`b`n`n';
		
		if(!$arr_poll['closed']) {
			$str_votelnk = $str_link.'poll_action=vote&poll_id='.$arr_poll['id'];
			$str_out .= '<form method="POST" action="'.$str_votelnk.'">`n';
			addnav('',$str_votelnk);
		}
		else {
			$str_out .= '`b`$Umfrage geschlossen.`0`b`n`n';
		}
		
		for($i=1; $i<7; $i++) {
			if(!empty($arr_poll['option'.$i])) {
				$int_votes = (!empty($arr_votes[$i]) ? $arr_votes[$i] : 0);
				$float_percent = round( ($int_votes / max($int_count,1))*100,1);
				$str_votebutton = (!$arr_poll['closed'] ? '<input type="radio" name="vote" value="'.$i.'" '.($arr_our['choice'] == $i ? 'checked="checked"': '').' />' : '');
				$str_out .= $str_votebutton.' `&'.$arr_poll['option'.$i].' `&('.$int_votes.' - '.$float_percent.' %)`n`n';
			}
		}
		
		$str_out .= '`&`iInsgesamt '.$int_count.' Stimme(n).`i`n`n';
		
		if(!$arr_poll['closed']) {
			$str_out .= '<input type="submit" value="'.$str_button.'"></form>`n';
		}
		
		if(!empty($arr_poll['description'])) {
			$str_out .= $arr_poll['description'].'`n`n';
		}
		
		if($int_close == 2 || ($int_close == 1 && $session['user']['acctid'] == $arr_poll['author'])) {
			if($arr_poll['closed']) {
				$str_out .= '[ '.create_lnk('Umfrage öffnen',$str_link.'poll_action=open&poll_id='.$arr_poll['id']).' ]';
			}
			else {
				$str_out .= '[ '.create_lnk('Umfrage schließen',$str_link.'poll_action=close&poll_id='.$arr_poll['id']).' ]';
			}
		}

		if($int_del == 2 || ($int_del == 1 && $session['user']['acctid'] == $arr_poll['author'])) {
			$str_out .= '[`0 '.create_lnk('`$Umfrage entfernen`0',$str_link.'poll_action=del&poll_id='.$arr_poll['id'],true,false,'Umfrage wirklich entfernen?').' `&]';		
		}
		
		output($str_out,true);
		return (true);
		
	}
	
}

/**
 * Fügt Umfrage hinzu, nimmt dafür Inhalt in POST
 * Zum Hinzufügen: Auf GET['poll_action'] prüfen, muss 'add' sein, wenn Form abgeschickt wurde
 * @param string Interne Kategorie, unter der Eintrag gespeichert wird
 * @param int Max. RL-Zeit in Tagen bis Eintrag gelöscht wird (Optional, Standard 100)
 * @param int Max. Anzahl an Posts vom gleichen Autor (Optional, Standard 0)
 * @return bool false, wenn Fehler auftritt. session['polladderror'] ist dann mit einem Fehlercode gesetzt
 */
function poll_add ($str_section,$int_days=100,$int_maxpolls=0) {
	global $session;
	
	unset($session['polladderror_data']);
	unset($session['polladderror']);
	
	if($int_maxpolls > 0) {
		$res = db_query('SELECT id FROM polls WHERE section="'.db_real_escape_string($str_section).'"');
		if(db_num_rows($res) >= $int_maxpolls) {
			$session['polladderror'] = 'maxpolls';
			return(false);
		}
	}
	
	// Neuen Poll einfügen
	$arr_poll = array(
						'section'=>$str_section,
						'question'=>utf8_htmlentities($_POST['question']),
						'description'=>utf8_htmlentities($_POST['description']),
						'expire'=>date('Y-m-d H:i:s',time()+86400*$int_days),
						'postdate'=>array('sql'=>true,'value'=>'NOW()'),
						'author'=>$session['user']['acctid']
						);
	$int_optioncount = 0;
	for ($i=1; $i<7; $i++) {
		if(!empty($_POST['option'.$i])) {
			$arr_poll['option'.$i] = strip_appoencode(utf8_htmlentities($_POST['option'.$i]),2);
			$int_optioncount++;
		}
		else {
			$arr_poll['option'.$i] = '';
		}
	}
	
	if($int_optioncount < 2 || empty($arr_poll['question'])) {
		$session['polladderror_data'] = $_POST;
		$session['polladderror'] = 'mindata';
		return (false);
	}
	
	if(db_insert('polls',$arr_poll)) {
		return(true);
	}
	$session['polladderror_data'] = $_POST;
	$session['polladderror'] = 'db_error';
	return (false);
	
}

/**
 * Zeigt Formular zum Erstellen einer Umfrage an
 * @param string Text des Buttons
 * @author talion
 */
function poll_show_addform ($str_button = 'Übernehmen') {
	global $session;
	
	$str_link = calcreturnpath();
	$str_link .= ( mb_strpos($str_link,'&') || mb_strpos($str_link,'?') ) ? '&' : '?';
	$str_link .= 'poll_action=add';
	
	$arr_form = array('question'=>'Frage:,text,255',
						'description'=>'Optionale Beschreibung unter der Umfrage:,text,255');
						
	for ($i=1; $i<7; $i++) {
		$arr_form['option'.$i] = 'Antwort '.$i.',text,120';
	}
	
	$arr_data = array();
	
	if(!empty($session['polladderror'])) {
			
		if(!empty($session['polladderror_data'])) {
			$arr_data = $session['polladderror_data'];
		}
		
	}
	addnav('',$str_link);
	output('`n`&Es müssen die Frage und mindestens zwei Antwortmöglichkeiten angegeben werden, die restlichen Felder sind optional:`n
			<form method="POST" action="'.$str_link.'">',true);
	showform($arr_form,$arr_data,false,$str_button);
	output('</form>',true);
	
}

/**
 * Löscht eine Umfrage samt Umfragergebnissen (oder nur diese, falls gewünscht) 
 *
 * @param int ID der Umfrage
 * @param string Section der Umfrage
 * @param int AcctID Autor der Umfrage
 * @param bool Nur Votes löschen
 * @return bool true / false
 */
function poll_delete ($int_id, $str_section = '', $int_acctid = 0, $bool_only_votes = false) {
	
	global $session;
		
	$str_ids = '-1';
	
	if(empty($int_id))
	{
		if(empty($str_section) && empty($int_acctid)) {
			return (false);
		}
		
		$sql = 'SELECT id FROM polls WHERE '
				.(!empty($int_acctid) ? 'author='.$int_acctid.' AND ' : '').' '
				.(!empty($str_section) ? 'section="'.db_real_escape_string($str_section).'" AND ' : '').' 1';
		$res = db_query($sql);
		
		if(!db_num_rows($res)) {
			return (false);
		}
		
		while($p = db_fetch_assoc($res)) {
			$str_ids .= ','.$p['id'];
		}
		
	}
	else
	{
		$str_ids .= ','.$int_id;
	}
	
	if(!$bool_only_votes) {
		// Poll löschen
		$sql = 'DELETE FROM polls WHERE id IN('.$str_ids.')';
		db_query($sql);
		
		if(db_errno(LINK)) {return (false);}
		
	}
	
	// Votes löschen
	$sql = 'DELETE FROM pollresults WHERE pollid IN('.$str_ids.') AND motditem=0';
	db_query($sql);
	
	if(!db_errno(LINK)) {
		return (true);
	}
	else {
		return (false);
	}
	
}
?>
