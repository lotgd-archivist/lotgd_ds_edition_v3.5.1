<?php
/**
* su_donation.php: Werkzeug zum Verwalten von Donationpoints + Spenden.
* @author talion <t@ssilo.de>
* @version DS-E V/2
*/

$str_filename = basename(__FILE__);
require_once('common.php');
$access_control->su_check(access_control::SU_RIGHT_DONATIONS,true);

page_header('Donationpunkte');

output('`c`b`&Donationpunkte verwalten`&`b`c`n`n');

// Grundnavi erstellen
addnav('Zurück');
grotto_nav();
addnav('Gehe zu');
addnav('Donationpoints vergeben',$str_filename.'?op=new_dp&what=add');
addnav('Donationpoints abziehen',$str_filename.'?op=new_dp&what=min');
addnav('Liste',$str_filename.'?op=list');
addnav('History',$str_filename.'?op=history');

$str_ret = urldecode($_REQUEST['ret']);
if(!empty($str_ret)) {
	addnav('Zurück', $str_ret);
}
// END Grundnavi erstellen

// Evtl. Fehler / Erfolgsmeldungen anzeigen
if($session['message'] != '') {
	output('`n`b'.$session['message'].'`0`b`n`n');
	$session['message'] = '';
}
// END Evtl. Fehler / Erfolgsmeldungen anzeigen

// MAIN SWITCH
$str_op = ($_REQUEST['op'] ? $_REQUEST['op'] : '');

switch($str_op) {

	// DP vergeben
	case 'new_dp':

		$str_desc = '';
		$str_submitbutton = '';
		$int_id = (int)$_REQUEST['id'];

		// Abziehen oder Hinzufügen?
		$str_what = $_REQUEST['what'];

		$str_lnk = $str_filename.'?op=new_dp&what='.$str_what.'&id='.$int_id;
		addnav('',$str_lnk);

		$str_out = '`c`&`bDonationpoints und Spenden '.($str_what == 'add' ? 'hinzufügen' : 'entfernen').'`b`c`n
					<form method="POST" action="'.$str_lnk.'">';

		// AccountID gegeben: Formular zur Vergabe anzeigen
		if($int_id > 0) 
		{

			$res = db_query('SELECT login FROM accounts WHERE acctid='.$int_id);
			if(!db_num_rows($res)) {
				$session['message'] = '`$Angegebener Account konnte nicht gefunden werden!`0';
				redirect($str_filename);
			}
			$arr_user = db_fetch_assoc($res);

			// Gleich negative Beträge verhindern
			$int_dp = max((int)$_POST['dp'],0);
			$float_donation = max((float)$_POST['donation'],0);

			// Daten gegeben
			if(!empty($int_dp) || !empty($float_donation)) {

				// Gesamtzahl der Spenden ermitteln
				$float_donations_ges = (float)getsetting('donations_ges','0');

				if(!empty($_POST['custom_reason'])) {
					$str_reason = stripslashes($_POST['custom_reason']);
				}
				else {
					$str_reason = stripslashes($_POST['reason']);
				}
				
				$str_optional_mail = trim($_POST['optional_mail']);

				if($str_what != 'add') {
					$str_sysmsg = '`#'.$int_dp.' Donationpoints abgezogen, Grund: "'.$str_reason.'", von:';
					$str_subj = '`$Donationpoints verloren!`0';
					$str_body = '`#Die Administration hat dir soeben `3'.$int_dp.'`# Donationpoints abgezogen!';
					$str_msg = '`@'.$int_dp.' Donationpoints und '.$float_donation.' Euro Spenden wurden erfolgreich abgezogen von '.$arr_user['login'].'!`0';
					$int_dp *= -1;
					$float_donation *= -1;
				}
				else {
					$str_sysmsg = '`#'.$int_dp.' Donationpoints vergeben, Grund: "'.$str_reason.'", an:';
					$str_subj = '`@Donationpoints erhalten!`0';
					$str_body = '`#Die Administration hat dir soeben `3'.$int_dp.'`# Donationpoints gutgeschrieben!';
					$str_msg = '`@'.$int_dp.' Donationpoints und '.$float_donation.' Euro Spenden wurden erfolgreich eingetragen für '.$arr_user['login'].'!`0';
				}

				// Wenn Spende, in Account vermerken
				if(!empty($float_donation)) {
					$sql = 'UPDATE account_extra_info SET donations=GREATEST(donations+'.$float_donation.',0) WHERE acctid='.$int_id;
					db_query($sql);
					

				}

				// Donationpoints gutschreiben, Systemlog, Systemmail
				if($int_id == $session['user']['acctid']) {
					$session['user']['donation'] = max($session['user']['donation']+$int_dp,0);
				}
				else {
					user_update(
						array
						(
							'donation'=>array('sql'=>true,'value'=>'GREATEST(donation+'.$int_dp.',0)')
						),
						$int_id
					);
				}

				if($int_dp != 0) {
					systemlog($str_sysmsg,$session['user']['acctid'],$int_id);
					systemmail($int_id,$str_subj,$str_body.'`nAls Grund dafür wurde angegeben: `n`3'.$str_reason.'`0');
					if(mb_strlen($str_optional_mail) > 0)
					{
						systemmail($int_id,'Bezüglich der Donationpoints',$str_optional_mail);
					}
					
					$arr_insert = array('receiver'=>$int_id,'dp_amount' => $int_dp, 'donation' => $float_donation, 'reason'=>$str_reason);
					
					db_insert('donationhistory',$arr_insert);
				}

				if($float_donation != 0) {
					$float_donations_ges += $float_donation;
					savesetting('donations_ges',$float_donations_ges);
				}

				$session['message'] = $str_msg;

				redirect($str_filename);

			}
			else {	// Sonst: Vergabeformular anzeigen

				if($str_what == 'add') {
					$str_desc = 'Werte `@vergeben`& an `b'.$arr_user['login'].'`b:';
					$str_submitbutton = 'Vergeben!';
					$str_enum_reasons = 'Spende,Spende,
										Bugmeldung,Bugmeldung,
										Werbung,Werbung';
				}
				else {
					$str_desc = 'Werte `$abziehen`& von `b'.$arr_user['login'].'`b:';
					$str_submitbutton = 'Abziehen!';
					$str_enum_reasons = 'Versehen,Versehen,
										Doof,Doof';
				}

				$str_desc .= '`nBei Veränderungen an der Anzahl der Donationpunkte wird automatisch eine Systemmail
								an den Betroffenen versendet, die Dimension und Grund der Veränderung nennt. Sollten nur die Spenden
								verändert werden, so unterbleibt diese Mail.';

				$arr_form = array(
										'dp'=>'Anzahl an Donationpoints,int,focus',
										'donation'=>'Empfangene Spende in Euro (Punkt als Dezimalzeichen; optional),int',
										'custom_reason'=>'Grund (Hier eingeben oder aus untenstehender Liste wählen):',
										'reason'=>'Grund:,enum,'.$str_enum_reasons,
										'optional_mail' => 'Eine zusätzliche Nachricht an den Charakter senden,textarea,80,20'
									);
			}

		}
		else 
		{	// Sonst: Suchformular für User anzeigen

			$str_lnk = $str_filename.'?op=new_dp&ret='.urlencode($_GET['ret']);
			addnav('',$str_lnk);
		
			$str_out .= '`c<form method="POST" action="'.$str_lnk.'">';

			// Name eingegeben?
			if(!empty($_POST['search']) && mb_strlen($_POST['search']) > 2) {
				
				//public static function getChars($str_search_txt,$str_what = '`acctid`,`login`,`name`',$arr_search_in = array(), $str_where_addition = '', $int_limit = 0)
				$arr_users = CCharacter::getChars($_POST['search'],'`acctid`,`login`',
					array(
						'acctid'	=> array('type'=>CCharacter::SEARCH_EXACT , 'mode'=> null, 'open_bracket' => false, 'close_bracket' => false),
						'name' 		=> array('type'=>CCharacter::SEARCH_LIKE_EXT  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
						'login' 	=> array('type'=>CCharacter::SEARCH_FUZZY, 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false)
					)
				);

				$int_result_count = count($arr_users);

				if(!$int_result_count) {
					$session['message'] = '`$Es wurden keine Accounts gefunden, die auf deine Eingabe '.$str_search_in.' passen!`0';
					redirect($str_filename.'?op=new_dp');
				}
				elseif($int_result_count > 500) {
					$session['message'] = '`$Es wurden zu viele Accounts gefunden, die auf deine Eingabe '.$str_search_in.' passen. Schränke den Suchbegriff etwas ein!`0';
					redirect($str_filename.'?op=new_dp');
				}
				else {
					foreach($arr_users as $a ) {
						$str_names .= ','.$a['acctid'].','.$a['login'];
					}
				}

				$arr_form = array('id'=>'Gefundene Accounts:,enum'.$str_names);

				$str_submitbutton = 'Übernehmen!';

			}
			else {	// Sonst: Eingabefeld
				$arr_form = array('search'=>'Suche in Login:,text,80,focus');
				$str_submitbutton = 'Suche!';
			}

		}

		// Zusammenfügen und ausgeben
		$str_out .= $str_desc.'`n'.generateform($arr_form,$_POST,false,$str_submitbutton);
		$str_out .= '</form>`c';

		output($str_out,true);

	break;
	
	case 'history':
		{
			$str_out = get_title('Liste der vorangegangenen Transaktionen');
			$str_sql = 'SELECT IFNULL(`a`.`name`,"Nicht mehr vorhanden") as `name` , `d`.* FROM `donationhistory` `d` LEFT JOIN `accounts` `a` ON (`a`.`acctid`=`d`.`receiver`)';
			$db_res = db_query($str_sql);
			$str_out .= '<table><tr class="trhead"><th>Datum</th><th>Name</th><th>Donationpoints</th><th>Spende</th><th>Grund</th><tr>';
			
			while($arr_result = db_fetch_assoc($db_res))
			{
				$str_style = $str_style == 'trlight'?'trdark':'trlight';
				$str_out .= '<tr class="'.$str_style.'"><td>'.$arr_result['timestamp'].'</td><td>'.$arr_result['name'].'</td><td>'.$arr_result['dp_amount'].'</td><td>'.$arr_result['donation'].'</td><td>'.$arr_result['reason'].'</td><tr>';
			}
			$str_out .= '</table>';
			output($str_out);
			break;
		}

	// Liste der DP
	default:

		$str_out = '';
		$str_where = ' WHERE 1 ';
		$str_order = ($_REQUEST['order'] == 'asc' ? 'ASC' : 'DESC');
		$str_orderby = '';
		switch ($_REQUEST['orderby']) {
			case 'name': $str_orderby = ' login '; break;
			case 'dp_netto': $str_orderby = ' dp_netto '; break;
			case 'dp_spent': $str_orderby = ' donationspent '; break;
			case 'dp': $str_orderby = ' donation '; break;
			case 'donations': $str_orderby = ' donations '; break;
			default: $str_orderby = ' dp_netto '; break;
		}

		// Suche nach Login
		if(!empty($_POST['search'])) {
			$str_search_in = mb_substr($_POST['search'],0,80);

			$str_search = str_create_search_string($str_search_in);
			$str_where .= ' AND (login LIKE "'.$str_search.'" OR name LIKE "'.$str_search.'"  OR login SOUNDS LIKE "'.$str_search_in.'") ';
		}

		$count_sql = 'SELECT COUNT(*) AS c FROM accounts '.$str_where;
		$arr_res = page_nav($str_filename, $count_sql, 100);

		$sql = 'SELECT GREATEST((donation-donationspent),0) AS dp_netto, donation, donationspent, name, a.acctid, aei.donations
				FROM accounts a
				LEFT JOIN account_extra_info aei USING (acctid)
				'.$str_where.'
				ORDER BY '.$str_orderby.' '.$str_order.'
				LIMIT '.$arr_res['limit'];
		$res = db_query($sql);

		addnav('',$str_filename);

		$arr_form = array	(
								'search'=>'Suche in Login:,text,80,focus',
								'orderby'=>'Ordnen nach:,enum,dp_netto,DP netto,name,Name,dp_spent,DP ausgegeben,dp,DP gesamt,donations,Spenden',
								'order'=>'Reihenfolge:,enum,desc,Absteigend,asc,Aufsteigend'
							);

		
		// Gesamtzahl der Spenden ermitteln
		$float_donations_ges = (float)getsetting('donations_ges','0');

		$str_out .= '`c<table cellspacing="3" cellpadding="3">
							<tr>
								<td colspan="7">
									<form method="POST" action="'.$str_filename.'">
										'.generateform($arr_form,$_POST,false,'Suche!').'
									</form>
								</td>
							</tr>
							<tr class="trhead">
								<td>`bPlatz`b</td>
								<td>`bName`b</td>
								<td>`bDP netto`b</td>
								<td>`bDP gesamt`b</td>
								<td>`bDP ausgegeben`b</td>
								<td>`bSpenden`b</td>
								<td>`bAktionen`b</td>
							</tr>';

		if(db_num_rows($res) == 0) {
			$str_out .= '<tr><td colspan="7">`iKeine Accounts vorhanden! (Was auch immer du dann hier treibst.. Freak!)`i</td></tr>';
		}
		else{
			$str_out .= '<tr><td colspan="7">`i'.$arr_res['count'].' Einträge vorhanden!`i`n
											Spenden gesamt: '.number_format($float_donations_ges,2,null,' ').'</td></tr>';
		}

		$int_counter = 0;

		while($a = db_fetch_assoc($res)) {

			$int_counter++;

			$str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');

			$str_out .= '<tr class="'.$str_trclass.'">';
			$str_out .= '<td>'.$int_counter.'</td>';
			$str_out .= '<td>'.$a['name'].'</td>';
			$str_out .= '<td>`@'.number_format($a['dp_netto'],null,null,' ').'`0</td>';
			$str_out .= '<td>`7'.number_format($a['donation'],null,null,' ').'</td>';
			$str_out .= '<td>`$'.number_format($a['donationspent'],null,null,' ').'</td>';
			$str_out .= '<td>`^'.number_format($a['donations'],2,null,' ').'</td>';
			$str_out .= '<td align="center">
							'.create_lnk('<img src="./images/icons/plus.gif" border="0" />',$str_filename.'?op=new_dp&id='.$a['acctid'].'&what=add&ret='.urlencode($str_ret)).'
							'.create_lnk('<img src="./images/icons/minus.gif" border="0" />',$str_filename.'?op=new_dp&id='.$a['acctid'].'&ret='.urlencode($str_ret)).'
						</td>';
			$str_out .= '</tr>';

		}

		$str_out .= '</table>`c';

		output($str_out, true);

	break;
}

page_footer();
?>
