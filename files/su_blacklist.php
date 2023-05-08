<?php
/**
* su_blacklist.php: Werkzeug zum Verwalten der Negativlisten
* @author talion <t@ssilo.de>
* @version DS-E V/2
*/

$str_filename = basename(__FILE__);
require_once('common.php');
$access_control->su_check(access_control::SU_RIGHT_EDITORUSER,true);

page_header('Blacklist - Editor');

output('`c`b`&Blacklist bearbeiten`b`c`n');

// Grundnavi erstellen
addnav('Zurück');
grotto_nav();

addnav('Aktionen');
addnav('Neuer Eintrag',$str_filename.'?op=edit_bl&page='.$_GET['page']);
addnav('Liste',$str_filename.'?page='.$_GET['page']);
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
	
	// Eingabemaske
	case 'edit_bl':			
		
		output('Groß / Kleinschreibung wird nicht berücksichtigt!`n
				Es wird nach folgendem Schema durchsucht:`n
				Zu prüfender Ausdruck wird anhand von Großschreibung, Bindestrichen, @-Zeichen, Punkten und Leerzeichen in einzelne Bestandteile aufgetrennt.`n
				Jeder Bestandteil wird einzeln überprüft, ob er einem gesperrten Ausdruck entspricht.`n
				Z.B. sperrt der Ausdruck "der" alle Werte, in denen "der" als ein Wort vorhanden ist.`n
				Zum Schluß unterzieht die Routine auch noch den gesamten Prüfwert einem Check.
				`n`n');
		
		$arr_form = array();
		$arr_data = array();
		
		$int_id = (int)$_REQUEST['id'];
						
		// Fehlgeschlagener Eintrag
		if(!empty($session['bl_post'])) {
			$arr_data = $session['bl_post'];
			unset($session['bl_post']);
		}
		else if($int_id > 0) {
			$sql = 'SELECT * FROM blacklist WHERE id='.$int_id;
			$arr_data = db_fetch_assoc(db_query($sql));			
			
			$int_type = $arr_data['type'];
			unset($arr_data['type']);
			
			// Typen zuweisen
			$arr_data['type['.BLACKLIST_LOGIN.']'] = ($int_type & BLACKLIST_LOGIN ? 1 : 0);
			$arr_data['type['.BLACKLIST_TITLE.']'] = ($int_type & BLACKLIST_TITLE ? 1 : 0);
			$arr_data['type['.BLACKLIST_EMAIL.']'] = ($int_type & BLACKLIST_EMAIL ? 1 : 0);
		}
		// Keine Daten gegeben
		else {
			
		}
			
		$arr_form = array(
							'type['.BLACKLIST_LOGIN.']'=>'TYP : Login,checkbox,1',
							'type['.BLACKLIST_TITLE.']'=>'TYP : Titel,checkbox,1',
							'type['.BLACKLIST_EMAIL.']'=>'TYP : EMail,checkbox,1',
							'value'=>'Eintrag:',
							'remarks'=>'Anmerkungen (optional):,text,255',
							'id'=>'EintragID,hidden'
						);
		
		$str_lnk = $str_filename.'?op=insert_bl&page='.$_GET['page'];
		addnav('',$str_lnk);
					
		$str_out = '`c<form method="POST" action="'.$str_lnk.'">';
					
		$str_out .= generateform($arr_form,$arr_data,false,'Speichern!',0);
		
		$str_out .= '</form>`c';
		
		output($str_out, true);
					
	break;
	
	// Eintrag einfügen
	case 'insert_bl':
						
		$int_type = 0;
		$int_id = (int)$_REQUEST['id'];
		
		if(sizeof($_POST['type']) == 0) {
			$session['message'] = '`$Bitte einen Typ auswählen!`0';
			$session['bl_post'] = $_POST;
			redirect($str_filename.'?op=edit_bl&page='.$_GET['page']);	
		}
		
		foreach($_POST['type'] as $tid => $t) {
			$int_type = $int_type ^ $tid;
		}
				
		$str_value = mb_strtolower($_POST['value']);
		$str_remarks = $_POST['remarks'];
		
		// Auf doppelte Einträge checken
		if(!$int_id) {
			$sql = 'SELECT id FROM blacklist WHERE type='.$int_type.' AND LOWER(value)="'.$str_value.'"';
			if(db_num_rows(db_query($sql))) {
				$session['message'] = '`$Ein identischer Eintrag besteht bereits!`0';
				$session['bl_post'] = $_POST;
				redirect($str_filename.'?op=edit_bl&page='.$_GET['page']);	
			}
		}
						
		// Eintrag
		if($int_id == 0) {
			$sql = 'INSERT INTO ';
		}
		else {
			$sql = 'UPDATE ';
		}
		$sql .= 'blacklist SET type='.$int_type.',value="'.$str_value.'",remarks="'.$str_remarks.'"';
		$sql .= ($int_id ? ' WHERE id='.$int_id : '');
		
		db_query($sql);
		
		$int_id =  (!$int_id ? db_insert_id() : $int_id);
						
		if(db_error(LINK) || db_affected_rows() < 0) {	
			$session['message'] = '`$Fehler bei Bearbeiten des Eintrags ID '.$int_id.': '.db_error(LINK).'`n';
		}	
		else {
			$session['message'] = '`@Eintrag ID '.$int_id.' erfolgreich getätigt!`0'; 		
		}
		redirect($str_filename.'?page='.$_GET['page']);
		
	break;
	
	// Eintrag löschen
	case 'del_bl':
		$int_id = (int)$_GET['id'];
		
		$sql = 'DELETE FROM blacklist WHERE id='.$int_id;
		db_query($sql);
		
		if( db_error(LINK) ) {
			$session['message'] = '`$Fehler bei Löschen des Eintrags ID '.$int_id.':`n';
		}	
		else {
			$session['message'] = '`@Eintrag ID '.$int_id.' erfolgreich gelöscht!`0'; 		
		}
		redirect($str_filename.'?type='.$_GET['type'].'&page='.$_GET['page']);
		
	break;
	
	// Export-Maske
	case 'export_form':
		
		$arr_form = array();
		$arr_data = array();
		
		$sql = 'SELECT * FROM blacklist';
		$res = db_query($sql);
		
		$str_out = 'Bitte Einträge auswählen, die exportiert werden sollen:`n`n';
		
		if(db_num_rows($res) > 0) {		
				
			$str_lnk = $str_filename.'?op=export&page='.$_GET['page'];
			addnav('',$str_lnk);
			
			while($b = db_fetch_assoc($res)) {
				$arr_form['ids['.$b['id'].']'] = $b['value'].':,checkbox,1';
			}
						
			$str_out .= '`c<form method="POST" action="'.$str_lnk.'">';
						
			$str_out .= generateform($arr_form,$arr_data,false,'Exportieren!');
			
			$str_out .= '</form>`c';
			
			output($str_out, true);
			
		}
		
		addnav('Alle exportieren!',$str_filename.'?op=export&all=1');
		
	break;
	
	// Exportieren
	case 'export':
		
		if(empty($_POST['ids']) && !$_GET['all']) {
			$session['message'] = '`$Nichts zum Exportieren ausgewählt!';
			redirect($str_filename.'?op=export_form');
		}
		
		if($_POST['ids']) {
			$str_ids = implode(',',array_keys($_POST['ids']));
			$sql = 'SELECT value,type FROM blacklist WHERE id IN(-1,'.$str_ids.')';
		}
		else {
			$sql = 'SELECT value,type FROM blacklist WHERE 1';
		}
		
		
		$res = db_query($sql);
		
		$arr_export = array();
		
		if(db_num_rows($res) > 0) {		
									
			while($b = db_fetch_assoc($res)) {
				$arr_export[] = $b;
			}
			
			$str_export = utf8_serialize($arr_export);
			
		}
		
		output('`c`@Export: `n`n<textarea class="input" rows="20" cols="40">'.$str_export.'</textarea>`c`n',true);
				
	break;
	
	// Importieren-Maske
	case 'import_form':
		
		$arr_form = array();
		$arr_data = array();
					
		$arr_form = array(
							'import'=>'Zu importierende Daten,textarea,40,20'
						);
		
		$str_lnk = $str_filename.'?op=import&page='.$_GET['page'];
		addnav('',$str_lnk);
					
		$str_out = '`c<form method="POST" action="'.$str_lnk.'">';
					
		$str_out .= generateform($arr_form,$arr_data,false,'Importieren!');
		
		$str_out .= '</form>`c';
		
		output($str_out, true);
		
	break;
	
	// Importieren
	case 'import':
		
		$arr_import = utf8_unserialize(($_POST['import']));
		
		if(is_array($arr_import)) {
			
			foreach($arr_import as $i) {
				
				if(!db_num_rows(db_query('SELECT id FROM blacklist WHERE type='.$i['type'].' AND value="'.db_real_escape_string($i['value']).'"'))) {
					
					$sql = 'INSERT INTO blacklist SET type='.$i['type'].', value="'.db_real_escape_string($i['value']).'"';
					db_query($sql);
					if(!db_error(LINK)) {
						output('`@'.$i['type'].': '.$i['value'].' eingefügt..`n');
					}
					else {
						output('`$Fehler bei Einfügen von '.$i['type'].': '.$i['value'].'!`n');
					}
				}
				else {
					output('`^'.$i['type'].': '.$i['value'].' bereits vorhanden, überspringen..`n');
				}
				
			}
			
		}
		else {		
			output('`$Keine Daten gefunden!`n');
		}
		
		addnav('Noch mehr importieren',$str_filename.'?op=import_form');
		
	break;
		
	// Black'list'
	default:
		
		addnav('Import',$str_filename.'?op=import_form');
		addnav('Export',$str_filename.'?op=export_form');
		
		$int_type = (int)$_REQUEST['type'];
		
		$str_out = '';
		$str_trclass = '';
		$str_out .= '<a name="TOP">&nbsp;</a>';
		
		$str_where = (!empty($int_type) ? ' WHERE (type & '.$int_type.')':'');
		
		$count_sql = 'SELECT COUNT(*) AS c FROM blacklist '.$str_where.' ORDER BY value';
		$arr_res = page_nav($str_filename.'?type='.$int_type.'&', $count_sql, 100);
		
		$sql = 'SELECT * FROM blacklist '.$str_where.' ORDER BY value LIMIT '.$arr_res['limit'];
		$res = db_query($sql);
		
		if(!empty($_POST['testname'])) {
			$_POST['testname'] = stripslashes($_POST['testname']);
			$bool_res = check_blacklist(BLACKLIST_LOGIN ^ BLACKLIST_TITLE ^ BLACKLIST_EMAIL,$_POST['testname']);
			if($bool_res) {
				$str_out .= '`c`i`$Test: Dieser Wert wäre ungültig!`0`c`i`n`n';
			}
			else {
				$str_out .= '`c`i`@Test: Dieser Wert wäre gültig!`0`c`i`n`n';
			}
		}
		
		addnav('',$str_filename);
						
		$str_out .= '`c<table cellspacing="3" cellpadding="3">
							<tr>
								<td colspan="5" align="center">
									<form method="POST" action="'.$str_filename.'">
										<input type="text" name="testname" size="30" maxlength="50" value="'.$_POST['testname'].'">
										<input type="submit" value="Testen!">
									</form>`n
									<form method="POST" action="'.$str_filename.'">
										Anzeigen: 
										<select name="type" onchange="this.form.submit();">
											<option value="0">Alle</option>
											<option value="'.BLACKLIST_LOGIN.'" '.($int_type == BLACKLIST_LOGIN ? 'selected="selected"':'').'>Login</option>
											<option value="'.BLACKLIST_TITLE.'" '.($int_type == BLACKLIST_TITLE ? 'selected="selected"':'').'>Titel</option>
											<option value="'.BLACKLIST_EMAIL.'" '.($int_type == BLACKLIST_EMAIL ? 'selected="selected"':'').'>EMail</option>
										</select>
										<input type="submit" value="Anzeigen!">
									</form>
								</td>
							</tr>
						</table>';

		$int_found = db_num_rows($res);
			
		if($int_found == 0) {
			$str_out .= '<table><tr><td align="center">`iKeine Einträge vorhanden!`i</td></tr>';
		}
		else{
			$str_out .= '<table><tr><td align="center">`i'.$arr_res['count'].' Einträge gesamt vorhanden, '.$int_found.' angezeigt!`i</td></tr>';
						
			$i_end = ord('Z');
			$str_out.='<tr><td align="center">`b';
			for($i=ord('A');$i<=$i_end;++$i){
				$str_out .= '<a href="#LETTER_'.chr($i).'">'.chr($i).'</a>&nbsp;';
			}
			$str_out .= '`b<br>Buchstabensuche funktioniert nur auf der aktuellen Seite.</td></tr></table>';
		}

		$str_out.='
						<table cellspacing="3" cellpadding="3">
							<tr class="trhead">
								<th>Aktionen</th>
								<th>ID</th>
								<th>Typ(en)</th>
								<th>Wert</th>
								<th>Anmerkungen</th>
							</tr>';
		
		$c_first = 0;
		
		while($b = db_fetch_assoc($res)) {
			
			if( $c_first < ord($b['value']) ){
				$c_first  = ord($b['value']);
				$c_letter = mb_strtoupper(chr($c_first));
				$str_out .= '<tr class="trhead"><td colspan="5">
								<a href="#TOP"><img border="0" src="./images/up.gif"></a>
								<a name="LETTER_'.$c_letter.'">`&'.$c_letter.'</a>
							</td></tr>';
			}
			
			$str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');
			
			$str_out .= '<tr class="'.$str_trclass.'">';
			$str_out .= '<td>
							['.create_lnk('Edit',$str_filename.'?op=edit_bl&id='.$b['id'].'&page='.$_GET['page']).']&nbsp;['.create_lnk('Del',$str_filename.'?op=del_bl&type='.$int_type.'&id='.$b['id'].'&page='.$_GET['page'],true,false,'Diesen Blacklisteintrag wirklich aufheben?').']
						</td>';
			$str_out .= '<td>`S'.$b['id'].'`0</td>';
			$str_out .= '<td>'
								.($b['type'] & BLACKLIST_LOGIN ? ' Login; ' : '')
								.($b['type'] & BLACKLIST_TITLE ? ' Titel; ' : '')
								.($b['type'] & BLACKLIST_EMAIL ? ' EMail; ' : '')
						.'</td>';
			$str_out .= '<td>`b`$'.$b['value'].'`0`b</td>';
			$str_out .= '<td>'.$b['remarks'].'</td>';
			$str_out .= '</tr>';
										
		}
				
		$str_out .= '</table>`c';
		
		output($str_out, true);
		
	break;
}


page_footer();
?>
