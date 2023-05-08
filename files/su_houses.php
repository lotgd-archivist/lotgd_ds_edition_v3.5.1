<?php
/**
* su_houses.php: Kontrollwerkzeug fürs Wohnviertel
* @author talion <t@ssilo.de>
* @version DS-E V/3
*/

$str_filename = basename(__FILE__);
require_once('common.php');
require_once(LIB_PATH.'house.lib.php');

$access_control->su_check(access_control::SU_RIGHT_GROTTO,true);

page_header('Hausmeister');

$str_out = get_title('`&Hausmeister');

// Grundnavi erstellen
addnav('Zurück');

grotto_nav();

addnav('Aktionen');
addnav('Hauptmenü',$str_filename);
// END Grundnavi erstellen


// Evtl. Fehler / Erfolgsmeldungen anzeigen
if($session['message'] != '') {
	$str_out .= '`n`b'.$session['message'].'`b`n`n';
	$session['message'] = '';
}
// END Evtl. Fehler / Erfolgsmeldungen anzeigen

// MAIN SWITCH
$str_op = ($_REQUEST['op'] ? $_REQUEST['op'] : '');

switch($str_op) {

	// Editmode
	case 'edit':

		$str_act = (isset($_GET['act']) ? $_GET['act'] : '');
		$int_id = (isset($_GET['id']) ? (int)$_GET['id'] : 0);

		if(!$int_id) {
			$session['message'] = '`$Keine ID für Edit gegeben!`0';
			redirect($str_filename);
		}

		// Haus abrufen
		$res = db_query('SELECT h.*,a.name,a.login FROM houses h LEFT JOIN accounts a ON a.acctid = h.owner WHERE h.houseid='.$int_id);
		if(!db_num_rows($res)) {
			$session['message'] = '`$Haus mit ID '.$int_id.' nicht gefunden!`0';
			redirect($str_filename);
		}

		$arr_house = db_fetch_assoc($res);
		$str_baselnk = $str_filename.'?op=edit&id='.$int_id;

		$str_out .= '`c`bEdit des Hauses Nr. '.$arr_house['houseid'].'`b`c`n';

		// Navis
		addnav('Inspizieren');
		addnav('Haus betreten','inside_houses.php?id='.$int_id);
		addnav('Adminkeule');
		addnav('Haus checken',$str_baselnk.'&act=check_house',false,false,false,false,'Bist du dir sicher?');
		addnav('Haus verkaufen',$str_baselnk.'&act=sell_house',false,false,false,false,'Bist du dir sicher?');
		addnav('Haus auf Verlassen setzen',$str_baselnk.'&act=abandon_house',false,false,false,false,'Bist du dir sicher?');
		addnav('Haus standardisieren',$str_baselnk.'&act=reset_house',false,false,false,false,'Bist du dir sicher?');
		addnav('Haus abreißen',$str_baselnk.'&act=kill_house',false,false,false,false,'Willst du das Haus wirklich abreißen?');
		addnav('house_extension-Cache löschen',$str_baselnk.'&act=reset_extension_cache');

		$arr_house['dmg_info'] = utf8_unserialize($arr_house['dmg_info']);
		$arr_house['tricks'] = utf8_unserialize($arr_house['tricks']);

		switch($str_act) {

			// Schlüssel
			case 'key':

				$arr_form = array('id'=>'Schlüssel-ID,viewonly',
									'value2'=>'Wert 2',
									'value3'=>'Wert 3',
									'value4'=>'Wert 4',
									'hvalue'=>'Zusatzwert',
									'owner'=>'Besitzer AcctID',
									'gold'=>'Gold erhalten,int',
									'gems'=>'Gems erhalten,int',
									'chestlock'=>'Schatztruhensperre aktiv,bool');

				if(isset($_GET['keyid']) && !empty($_GET['keyid'])) {
					$int_keyid = (int)$_GET['keyid'];
					$str_out .= 'Schlüssel '.$int_keyid.' bearbeiten:';
					$res = db_query('SELECT * FROM keylist WHERE id='.$int_keyid);
					if(!db_num_rows($res)) {
						$session['message'] = '`$Schlüssel nicht gefunden!`0';
						redirect($str_baselnk);
					}
					$arr_key = db_fetch_assoc($res);
				}
				else {
					$int_keyid = 0;
					$str_out .= 'Schlüssel neu anlegen:';
				}

				if(isset($_GET['save'])) {

					$str_sql = ($int_keyid ? 'UPDATE' : 'INSERT INTO').' keylist SET value1='.$int_id.',type='.HOUSES_KEY_DEFAULT;

					foreach ($_POST as $str_k => $val) {

						if(isset($arr_form[$str_k])) {
							$str_sql .= ','.$str_k.' = "'.db_real_escape_string(stripslashes($val)).'"';
						}

					}

					if($int_keyid) {
						$str_sql .= ' WHERE id='.$int_keyid;
					}

					db_query($str_sql);

					$session['message'] = '`@Schlüssel gespeichert!`0';

					redirect($str_baselnk);

				}

				if(isset($_GET['del'])) {

					$str_sql = 'DELETE FROM keylist WHERE id='.$int_keyid;
					db_query($str_sql);

					$session['message'] = '`@Schlüssel entfernt!`0';

					redirect($str_baselnk);

				}

				if(isset($_GET['reset'])) {

					$str_sql = 'UPDATE keylist SET owner='.$arr_house['owner'].',gold=0,gems=0,chestlock=0 WHERE id='.$int_keyid;
					db_query($str_sql);

					$session['message'] = '`@Schlüssel an Eigentümer zurückgegeben!`0';

					redirect($str_baselnk);

				}

				if(isset($_GET['reset_all'])) {

					$str_sql = 'UPDATE keylist SET owner='.$arr_house['owner'].',gold=0,gems=0,chestlock=0 WHERE owner=0 AND value1='.$int_id;
					db_query($str_sql);

					$session['message'] = '`@Alle verlorenen Schlüssel an Eigentümer zurückgegeben!`0';

					redirect($str_baselnk);

				}

				$str_out .= form_header($str_baselnk.'&act=key&save=1&keyid='.$int_keyid);
				output($str_out);
				$str_out = '';
				showform($arr_form,$arr_key);
				$str_out .= '</form>`n`n<hr />`n';

			break;

			// Gemach
			case 'room':

				$str_type_enum = '';
				foreach ($g_arr_house_extensions as $str_k=>$arr_e) {
					if(isset($arr_e['room']) && true === $arr_e['room']) {
						$str_type_enum .= ','.$str_k.','.$arr_e['name'];
					}
				}
				$arr_form = array('id'=>'Raum-ID,viewonly',
									'name_prev'=>'Name Vorschau,preview,name',
									'name'=>'Name des Raums',
									'desc'=>'Beschreibung,textarea,30,10',
									'val'=>'Nur mit Einladung zugänglich?,bool',
									'owner'=>'AcctID des Besitzers,int',
									'loc'=>'Standort,enum,'.HOUSES_ROOM_BASEMENT.',Keller,'.HOUSES_ROOM_GROUND.',Erdgeschoß,'.HOUSES_ROOM_1ST.',1.Stock,'.HOUSES_ROOM_2ND.',2.Stock,'.HOUSES_ROOM_ROOF.',Dachgeschoß,'.HOUSES_ROOM_TOWER.',Turmgeschoß',
									'type'=>'Typ des Gemachs,enum'.$str_type_enum);

				if(isset($_GET['roomid']) && !empty($_GET['roomid'])) {
					$int_roomid = (int)$_GET['roomid'];
					$str_out .= 'Gemach '.$int_roomid.' bearbeiten:';
					$res = db_query('SELECT * FROM house_extensions WHERE id='.$int_roomid.' AND loc IS NOT null');
					if(!db_num_rows($res)) {
						$session['message'] = '`$Gemach nicht gefunden!`0';
						redirect($str_baselnk);
					}
					$arr_room = db_fetch_assoc($res);

					$arr_room['content'] = utf8_unserialize($arr_room['content']);
					$arr_room = array_merge($arr_room,(array)$arr_room['content']);
				}
				else {
					$int_roomid = 0;
					$str_out .= 'Gemach neu anlegen:';
				}

				if(isset($_GET['save'])) {

					// Gemach (korrekt) wegnehmen
					if($_POST['owner'] != $arr_room['owner'] && $arr_room['owner'] > 0) {
						house_take_room($arr_room,$arr_house,false);
					}

					$str_sql = ($int_roomid ? 'UPDATE' : 'INSERT INTO').' house_extensions SET houseid='.$int_id.',level=1';

					$arr_room['content']['desc'] = stripslashes($_POST['desc']);
					unset($_POST['desc']);
					$arr_room['content'] = utf8_serialize($arr_room['content']);

					$str_sql .= ',content="'.db_real_escape_string($arr_room['content']).'"';

					foreach ($_POST as $str_k => $val) {

						if(isset($arr_form[$str_k])) {
							$str_sql .= ','.$str_k.' = "'.db_real_escape_string(stripslashes($val)).'"';
						}

					}

					if($int_roomid) {
						$str_sql .= ' WHERE id='.$int_roomid;
					}

					db_query($str_sql);

					$session['message'] = '`@Gemach gespeichert!`0';

					redirect($str_baselnk);

				}

				if(isset($_GET['del'])) {

					house_take_room($arr_room,$arr_house,true);

					$session['message'] = '`@Gemach entfernt!`0';

					redirect($str_baselnk);

				}

				if(isset($_GET['reset'])) {

					house_take_room($arr_room,$arr_house,false);

					$session['message'] = '`@Gemach enteignet!`0';

					redirect($str_baselnk);

				}

				$str_out .= form_header($str_baselnk.'&act=room&save=1&roomid='.$int_roomid);
				output($str_out);
				$str_out = '';
				showform($arr_form,$arr_room);
				$str_out .= '</form>`n`n';

				// Schlüssel
				if($int_roomid)
				{
					$sql = 'SELECT k.*,a.name FROM keylist k
							LEFT JOIN accounts a ON a.acctid = k.owner
							WHERE value2='.$int_roomid.' AND type='.HOUSES_KEY_PRIVATE;
					$res = db_query($sql);

					$str_out .= plu_mi('kedit_priv',0,false).' Schlüssel: '.db_num_rows($res).' Schlüssel vorhanden.';
					$str_out .= '<div id="'.plu_mi_unique_id('kedit_priv').'" style="display:none;">
									<!--`n[ '.create_lnk('Schlüssel hinzufügen',$str_baselnk.'&act=key').' ]`n-->
									<table width="600">';

					while($arr_k = db_fetch_assoc($res)) {
						$str_out .= '<tr>';
						//$str_out .= '<td>[ '.create_lnk('`^Edit`0',$str_baselnk.'&act=key&keyid='.$arr_k['id']).' ]&nbsp;</td>';
						$str_out .= '<td>[ '.create_lnk('`$X`0',$str_baselnk.'&act=key&del=1&keyid='.$arr_k['id'],true,false,'Schlüssel wirklich löschen?').' ] </td>';
						$str_out .= '<td>';
						if(!empty($arr_k['name'])) {
							$str_out .= $arr_k['name'];
						}
						$str_out .= '</td>
									<td>';
						if(!empty($arr_k['name'])) {
							$str_out .= ' [ '.create_lnk('Alle Schlüssel d. Spielers',$str_filename.'?op=user_keys&id='.$arr_k['owner']).' ] ';
						}
						$str_out .= '</td>';
					}

					$str_out .= '</table></div>`n`n';
				}

				$str_out .= '<hr />`n';

			break;

			// Anbau
			case 'ext':

				$str_type_enum = 'enum';
				foreach ($g_arr_house_extensions as $str_k=>$arr_e) {
					if(!isset($arr_e['room']) || false === $arr_e['room']) {
						$str_type_enum .= ','.$str_k.','.$arr_e['name'];
					}
				}

				$arr_form = array('id'=>'Anbau-ID,viewonly',
									'type'=>'TypID,'.(empty($_GET['extid']) ? $str_type_enum : 'viewonly'),
									'type_txt'=>'Typ,viewonly',
									'level'=>'Level des Anbaus,enum_order,1,10',
									'val'=>'Wert 1 (Beliebige Variable),int',
									'content'=>'Inhalts-Speicher,viewonly');

				if(isset($_GET['extid']) && !empty($_GET['extid'])) {
					$int_extid = (int)$_GET['extid'];
					$str_out .= 'Anbau '.$int_extid.' bearbeiten:';
					$res = db_query('SELECT * FROM house_extensions WHERE id='.$int_extid.' AND loc IS null');
					if(!db_num_rows($res)) {
						$session['message'] = '`$Anbau nicht gefunden!`0';
						redirect($str_baselnk);
					}
					$arr_ext = db_fetch_assoc($res);

					$arr_ext['content'] = utf8_unserialize($arr_ext['content']);

					$arr_ext['type_txt'] = $g_arr_house_extensions[$arr_ext['type']]['name'];
				}
				else {
					$int_extid = 0;
					$str_out .= 'Anbau neu anlegen:';
				}

				if(isset($_GET['save'])) {

					$str_sql = ($int_extid ? 'UPDATE' : 'INSERT INTO').' house_extensions SET houseid='.$int_id;

					$arr_ext['content'] = utf8_serialize($arr_ext['content']);

					$str_sql .= ',content="'.db_real_escape_string($arr_ext['content']).'"';

					foreach ($_POST as $str_k => $val) {

						if(isset($arr_form[$str_k])) {
							$str_sql .= ','.$str_k.' = "'.db_real_escape_string(stripslashes($val)).'"';
						}

					}

					if($int_extid) {
						$str_sql .= ' WHERE id='.$int_extid;
					}

					db_query($str_sql);

					$session['message'] = '`@Anbau gespeichert!`0';

					redirect($str_baselnk);

				}

				if(isset($_GET['del'])) {

					house_extension_run('rip_auto',$arr_ext,$arr_house);
					
					$str_sql = 'DELETE FROM house_extensions WHERE id='.$int_extid;
					db_query($str_sql);
					// Anbauten-Cache zurücksetzen
					if(!Cache::delete(Cache::CACHE_TYPE_HDD,'houserooms'.$arr_house['houseid']))
					{
						admin_output('Cachereset (houserooms'.$arr_house['houseid'].') funzt nicht',true);
					}
										
					$session['message'] = '`@Anbau entfernt!`0';

					redirect($str_baselnk);

				}

				$str_out .= form_header($str_baselnk.'&act=ext&save=1&extid='.$int_extid);
				output($str_out);
				$str_out = '';
				showform($arr_form,$arr_ext);
				$str_out .= '</form>`n`n<hr />`n';

			break;

			// Haus an sich
			case 'save_house':

				// Status geändert?
				if($_POST['status'] != $arr_house['status']) {
					// Konsequenzen daraus ziehen ; )
					house_check($arr_house,$_POST['status']);
				}

				// Eigentümer geändert?
				if($_POST['owner'] != $arr_house['owner'] && $_POST['owner'] > 0 && $arr_house['owner'] > 0) {
					// Schlüssel auf neuen EIgentümer überschreiben
					house_keys_set(' value1='.$int_id.' AND owner='.$arr_house['owner'].' AND type='.HOUSES_KEY_DEFAULT,array('owner'=>$_POST['owner']),1000);
					// Altem Eigentümer sämtliche Gemächer abnehmen
					$sql = 'SELECT * FROM house_extensions WHERE houseid='.$int_id.' AND owner='.$arr_house['owner'];
					$res = db_query($sql);
					while($arr_e = db_fetch_assoc($res)) {
						house_take_room($arr_e,$arr_house);
					}
					// Möbel aus dem Haus packen
					item_set('deposit1='.$int_id.' AND owner='.$arr_house['owner'],array('deposit1'=>0,'deposit2'=>0),false,1000);
					// Account ändern
					user_update(
						array
						(
							'house'=>0,
							'where'=>'house='.$arr_house['houseid']
						)
					);
					user_update(
						array
						(
							'house'=>$arr_house['houseid'],
						),
						(int)$_POST['owner']
					);
					debuglog('Haus '.$arr_house['houseid'].' auf neuen Eigentümer ID '.$_POST['owner'].' übertragen!');
				}

				$str_sql = 'UPDATE houses SET houseid=houseid';
				
				$str_changes = '';
				
				foreach ($arr_house as $str_k => $val) {

					if(isset($_POST[$str_k]) && $_POST[$str_k] != $val) {
						$str_sql .= ','.$str_k.' = "'.db_real_escape_string(stripslashes($_POST[$str_k])).'"';
						$str_changes .= $str_k . ' von '.$val.' auf '.$_POST[$str_k].'; ';
					}

				}

				$str_sql .= ' WHERE houseid='.$int_id;
				db_query($str_sql);
				
				debuglog('Editierte Haus '.$arr_house['houseid'].', Änderungen: '.$str_changes);

				$session['message'] = '`@Haus aktualisiert!`0';

				redirect($str_baselnk);

			break;

			// Haus auf Schlüssel-/Gemächergrenzen kontrollieren
			case 'check_house':

				house_check($arr_house,$arr_house['status'],true);

				$session['message'] = '`@Haus erfolgreich kontrolliert!`0';

				redirect($str_baselnk);

			break;

			// Haus auf Wohnhaus zurücksetzen
			case 'reset_house':

				// Zur Sicherheit mal abreißen
				house_deconstruct($arr_house);

				// Dann auf Wohnhaus setzen
				db_query('UPDATE houses SET status=0,build_state=0,housename="Whose house? Run\s House!" WHERE houseid='.$int_id);

				// Schlüsselsatz korrigieren
				house_check($arr_house,0);
				
				debuglog('Setzte Haus '.$arr_house['houseid'].' auf Wohnhaus zurück');
				$session['message'] = '`@Haus auf Wohnhaus zurückgesetzt (Vergiss nicht, den Namen zu ändern)!`0';

				redirect($str_baselnk);

			break;

			// Haus abreißen
			case 'kill_house':
								
				house_deconstruct($arr_house);
				
				debuglog('Entfernte Haus '.$arr_house['houseid']);
				$session['message'] = '`@Haus entfernt und in leeres Grundstück umgewandelt!`0';

				redirect($str_baselnk);

			break;

			// Haus verkaufen
			case 'sell_house':

				house_sell($arr_house,0,0,0);
				
				debuglog('Verkaufte Haus '.$arr_house['houseid']);
				$session['message'] = '`@Haus verkauft!`0';

				redirect($str_baselnk);

			break;

			// Haus auf verlassen setzen
			case 'abandon_house':

				db_query('	UPDATE houses SET build_state="'.HOUSES_BUILD_STATE_ABANDONED.'",lastchange=NOW(),owner=0
							WHERE houseid='.$int_id);

				// Accounts zurücksetzen
				
				user_update(
						array
						(
							'house'=>0,
							'where'=>'house='.$int_id
						)
					);

				debuglog('Setzte Haus '.$arr_house['houseid'].' auf Verlassen');	
				$session['message'] = '`@Haus auf "Verlassen" gesetzt!`0';

				redirect($str_baselnk);

			break;

			// house_extension-Cache löschen
			case 'reset_extension_cache':

				$housecache = Cache::get(Cache::CACHE_TYPE_HDD,'houserooms'.$arr_house['houseid']);
				
				if($housecache == false)
				{
					$session['message'] = 'Es sind überhaupt keine Daten im Cache vorhanden (houserooms'.$arr_house['houseid'].') !';
				}
				else
				{
					if(!Cache::delete(Cache::CACHE_TYPE_HDD,'houserooms'.$arr_house['houseid']))
					{
						$session['message'] = 'Cachereset (houserooms'.$arr_house['houseid'].') funzt nicht :(';
					}
					else
					{
						$session['message'] = 'Cachereset erfolgreich (houserooms'.$arr_house['houseid'].') !';
					}
					debuglog('Löschte house_extension-Cache: houserooms'.$arr_house['houseid']);
				}

				redirect($str_baselnk);

			break;
		}

		// Hausedit
		$str_out .= plu_mi('hedit',1,false).' Hausdaten:';
		$str_out .= '<div id="'.plu_mi_unique_id('hedit').'">`n`n'.
					'`0';
		$str_housetypes = ',Alle';
		foreach ($g_arr_house_builds as $str_k => $arr_b) {
			$str_housetypes .= ','.$str_k.','.$arr_b['name'];
		}

		$arr_house['build_state_view'] = get_house_state($arr_house['status'],$arr_house['build_state'],false);
		$arr_form = array('houseid'=>'Hausnr.,viewonly',
							'housename_prev'=>'Hausname Vorschau,preview,housename',
							'housename'=>'Hausname',
							'description'=>'Beschreibung,textarea,30,10',
							'name'=>'Besitzer,viewonly',
							'owner'=>'Besitzer AcctID,int',
							'gold'=>'Gold in Hausschatz,int',
							'gems'=>'Gems in Hausschatz,int',
							'pvpflag_houses'=>'PvP-Flag (Zeitpkt. des letzten Angriffs)',
							'lastchange'=>'Wann wurde Haus verlassen (nur bei entsprechendem Status)?',
							'dmg'=>'Schadenslevel,int',
							'dmg_info'=>'Schaden,viewonly',
							'trick'=>'Schabernack,viewonly',
							'extension'=>'Falls das Haus ausgebaut wird: Woran wird gebaut?',
							'status'=>'Haustyp,enum,'.$str_housetypes,
							'build_state_view'=>'Aktueller Hausstatus,viewonly',
							'build_state'=>'Hausstatus,bitflag,Ausbau im Bau,Erweiterung im Bau,Haus im Bau,Haus zum Verkauf,Verlassen,Leeres Grundstück,Bauruine|?Nur maximal ein Feld auswählen und nur wenn es absolut notwendig ist!');

		$str_out .= form_header($str_baselnk.'&act=save_house');
		output($str_out);
		$str_out = '';
		showform($arr_form,$arr_house);
		$str_out .= '</form></div>`n`n';

		// Schlüssel
		$sql = 'SELECT k.*,a.name FROM keylist k
				LEFT JOIN accounts a ON a.acctid = k.owner
				WHERE value1='.$int_id.' AND type='.HOUSES_KEY_DEFAULT;
		$res = db_query($sql);

		$str_out .= plu_mi('kedit',($str_act == 'key' ? 1 : 0),false).' Schlüssel: '.db_num_rows($res).' Schlüssel vorhanden.';
		$str_out .= '<div id="'.plu_mi_unique_id('kedit').'" '.($str_act == 'key' ? '' : 'style="display:none;"').'>
						`n[ '.create_lnk('Schlüssel hinzufügen',$str_baselnk.'&act=key').' ] [ '.create_lnk('Verlorene Schlüssel resetten',$str_baselnk.'&act=key&reset_all=1').' ]`n
						<table width="600">';

		while($arr_k = db_fetch_assoc($res)) {
			$str_out .= '<tr>';
			$str_out .= '<td>[ '.create_lnk('`^Edit`0',$str_baselnk.'&act=key&keyid='.$arr_k['id']).' ]&nbsp;</td>';
			$str_out .= '<td>[ '.create_lnk('`$X`0',$str_baselnk.'&act=key&del=1&keyid='.$arr_k['id'],true,false,'Schlüssel wirklich löschen?').' ] </td>';
			$str_out .= '<td>';
			if(!empty($arr_k['name'])) {
				$str_out .= $arr_k['name'];
			}
			else {
				$str_out .= '`$verloren`0';
			}
			$str_out .= '</td>
						<td>';
			if(!empty($arr_k['name'])) {
				$str_out .= ' [ '.create_lnk('Alle Schlüssel d. Spielers',$str_filename.'?op=user_keys&id='.$arr_k['owner']).' ] ';
			}
			else {
				$str_out .= ' [ '.create_lnk('`@Reset`0',$str_baselnk.'&act=key&reset=1&keyid='.$arr_k['id']).' ] ';
			}
			$str_out .= '</td>';
		}

		$str_out .= '</table></div>`n`n';

		// Gemächer
		$sql = 'SELECT r.*,a.name AS oname FROM house_extensions r
				LEFT JOIN accounts a ON a.acctid = r.owner
				WHERE r.houseid='.$int_id.' AND r.LOC IS NOT null';
		$res = db_query($sql);

		$str_out .= plu_mi('redit',($str_act == 'room' ? 1 : 0),false).' Gemächer: '.db_num_rows($res).' Gemächer vorhanden.';
		$str_out .= '<div id="'.plu_mi_unique_id('redit').'" '.($str_act == 'room' ? '' : 'style="display:none;"').'>
						`n[ '.create_lnk('Gemach hinzufügen',$str_baselnk.'&act=room').' ]`n';

		while($arr_r = db_fetch_assoc($res)) {
			$str_out .= '[ '.create_lnk('`^Edit`0',$str_baselnk.'&act=room&roomid='.$arr_r['id']).' ]&nbsp;';
			$str_out .= ' ';
			$str_out .= '[ '.create_lnk('`$X`0',$str_baselnk.'&act=room&del=1&roomid='.$arr_r['id'],true,false,'Gemach wirklich löschen?').' ] ';
			if(!empty($arr_r['name'])) {
				$str_out .= $arr_r['name'].'`0 ('.$g_arr_house_extensions[$arr_r['type']]['name'].')';
			}
			else {
				$str_out .= $g_arr_house_extensions[$arr_r['type']]['name'];
			}
			$str_out .= ' im '.house_get_floor($arr_r['loc']);

			if(!empty($arr_r['oname'])) {
				$str_out .= ' von '.$arr_r['oname'];
				$str_out .= ' [ '.create_lnk('`@Reset`0',$str_baselnk.'&act=room&reset=1&roomid='.$arr_r['id']).' ] ';
			}
			else {
				$str_out .= ' - ohne Besitzer';
			}
			$str_out .= '`n';
		}

		$str_out .= '</div>`n`n';

		// Anbauten
		$sql = 'SELECT he.* FROM house_extensions he
				WHERE he.houseid='.$int_id.' AND loc IS null';
		$res = db_query($sql);

		$str_out .= plu_mi('eedit',($str_act == 'ext' ? 1 : 0),false).' Anbauten: '.db_num_rows($res).' Anbauten vorhanden.';
		$str_out .= '<div id="'.plu_mi_unique_id('eedit').'" '.($str_act == 'ext' ? '' : 'style="display:none;"').'>
					`n[ '.create_lnk('Anbau hinzufügen',$str_baselnk.'&act=ext').' ]`n';

		while($arr_e = db_fetch_assoc($res)) {
			$garden_link = "";
			if ($arr_e['type'] == 'garden')
			{
				$garden_link = " <a href='su_garden.php?garden=".$arr_e['id']."'>[Betreten]</a>";
				addnav("","su_garden.php?garden=".$arr_e['id']);
			}
			$str_out .= '[ '.create_lnk('`^Edit`0',$str_baselnk.'&act=ext&extid='.$arr_e['id']).' ]&nbsp;';
			$str_out .= '[ '.create_lnk('`$X`0',$str_baselnk.'&act=ext&del=1&extid='.$arr_e['id'],true,false,'Anbau wirklich löschen?').' ] ';
			$str_out .= $g_arr_house_extensions[$arr_e['type']]['name'].' (Level '.$arr_e['level'].') '.$garden_link.'`n';
		}

		$str_out .= '</div>`n`n';


	break;

	// Zeigt Schlüssel einzelnen Users an
	case 'user_keys':

		//addnav('Zurück',$str_filename);

		$str_out .= '`c`bSchlüssel einzelner Spieler`b`c`n`n';

		$int_id = (isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0);
		$int_type = (isset($_REQUEST['type']) ? (int)$_REQUEST['type'] : -1);
		$bool_own_house = (isset($_REQUEST['own_house']) ? (bool)$_REQUEST['own_house'] : false);
		$str_raw_name = (isset($_REQUEST['name']) ? stripslashes($_REQUEST['name']) : '');
		$str_search_name = str_create_search_string($str_raw_name);

		// User abrufen
		if($int_id > 0 || !empty($str_raw_name))
		{
			$res = db_query('SELECT a.name,a.login,a.acctid,a.house
							FROM accounts a
							WHERE 1'.
							($int_id ? ' AND a.acctid='.$int_id : '').
							(!empty($str_raw_name) ? ' AND a.name LIKE "'.$str_search_name.'"' : '').
							' ORDER BY (a.login="'.db_real_escape_string($str_raw_name).'") DESC
							LIMIT 1');
			if(db_num_rows($res)) {
				$arr_user = db_fetch_assoc($res);
				$int_id = $arr_user['acctid'];
				db_free_result($res);
			}
		}

		$arr_form = array	(
							 'name'=>'Spielername'
							,'id'=>'Spieler-ID'
							,'type'=>'Schlüsseltyp,enum,-1,Alle,'.HOUSES_KEY_DEFAULT.',Häuser,'.HOUSES_KEY_PRIVATE.',Privatgemächer'
							,'own_house'=>'Schlüssel zu eigenem Haus anzeigen?,checkbox,1'
							);
		$str_out .= form_header($str_filename.'?op=user_keys');
		$str_out .= generateform($arr_form,array('name'=>$str_raw_name,'id'=>$int_id,'type'=>$int_type,'own_house'=>$bool_own_house),false,'Suchen!');
		$str_out .= '</form><hr /><br />';

		if(!isset($arr_user))
		{
			$str_out .= '`$User nicht gefunden bzw. kein User ausgewählt!`0`n';
		}
		else
		{
			$str_out .= '`c`bSchlüssel des Spielers '.$arr_user['name'].'`0:`b`c`n`n';

			if($int_type == -1 || $int_type == HOUSES_KEY_DEFAULT)
			{
				$str_out .= '`n`bHausschlüssel:`b`n';

				// Zunächst Schlüssel zu Häusern
				$sql = 'SELECT k.owner, k.value1, k.value2, k.value3,
						k.value4, k.type, k.gold, k.gems, k.chestlock, k.description,
						k.hvalue, h.housename, a.name AS oname
						FROM keylist k
						LEFT JOIN houses h ON (h.houseid = k.value1)
						LEFT JOIN accounts a ON (a.acctid = h.owner)
						WHERE k.owner='.$int_id.' AND k.type='.HOUSES_KEY_DEFAULT;
				if($bool_own_house)
				{
					$sql .= ' ORDER BY (k.value1 = '.$arr_user['house'].') ASC';
				}
				else 
				{
					$sql .= ' AND k.value1 != '.$arr_user['house'];
				}

				$res = db_query($sql);

				if(!($int_count = db_num_rows($res)))
				{
					$str_out .= 'Spieler verfügt über keine Hausschlüssel!';
				}
				else
				{
					$str_out .= 'Spieler verfügt über '.$int_count.' Hausschlüssel:
								<table width="600">
									<tr class="trhead">
										<td>`bSchlüsselID`b</td>
										<td>`bHaus`b</td>
										<td>`bEigentümer`b</td>
										<td>`bGold-/Gembilanz`b</td>
										<td>`bGesperrt?`b</td>
										<!--<td>`bAktionen`b</td>-->
									</tr>';
					$str_trclass = 'trdark';
					while($arr_k = db_fetch_assoc($res)) {
						$str_out .= '<tr class="'.$str_trclass.'">';
						$str_out .= '<td>'.$arr_k['id'].'</td>';
						$str_out .= '<td>'.$arr_k['housename'].(!empty($arr_k['housename']) ? '`n'.create_lnk('Hausedit',$str_filename.'?op=edit&id='.$arr_k['value1']) : '').'</td>';
						$str_out .= '<td>'.$arr_k['oname'].'</td>';
						$str_out .= '<td>'.$arr_k['gold'].' / '.$arr_k['gems'].'</td>';
						$str_out .= '<td>'.($arr_k['chestlock'] ? 'ja' : 'nein').'</td>';
						$str_out .= '<!--<td>';
						//$str_out .= ;
						$str_out .= '</td>-->
									</tr>';

						$str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');
					}
					$str_out .= '</table>';

					db_free_result($res);
				}
			}

			if($int_type == -1 || $int_type == HOUSES_KEY_PRIVATE)
			{
				$str_out .= '`n`bGemachschlüssel:`b`n';

				$sql = 'SELECT k.*, he.name AS rname, he.type AS rtype, h.housename, a.name AS oname FROM keylist k
						LEFT JOIN house_extensions he ON (he.id = k.value2)
						LEFT JOIN houses h ON (h.houseid = he.houseid)
						LEFT JOIN accounts a ON (a.acctid = he.owner)
						WHERE k.owner='.$int_id.' AND k.type='.HOUSES_KEY_PRIVATE;
				$res = db_query($sql);

				if(!($int_count = db_num_rows($res)))
				{
					$str_out .= 'Spieler verfügt über keine Gemachschlüssel!';
				}
				else
				{
					$str_out .= 'Spieler verfügt über '.$int_count.' Gemachschlüssel:
								<table width="600">
									<tr class="trhead">
										<td>`bSchlüsselID`b</td>
										<td>`bGemach`b</td>
										<td>`bEigentümer`b</td>
										<td>`bHaus`b</td>
									</tr>';
					$str_trclass = 'trdark';
					while($arr_k = db_fetch_assoc($res)) {
						$arr_k['rname'] = (!empty($arr_k['rname']) ? $arr_k['rname'] : $g_arr_house_extensions[$arr_k['rtype']]['name']);
						$str_out .= '<tr class="'.$str_trclass.'">';
						$str_out .= '<td>'.$arr_k['id'].'</td>';
						$str_out .= '<td>'.$arr_k['rname'].'</td>';
						$str_out .= '<td>'.$arr_k['oname'].'</td>';
						$str_out .= '<td>'.$arr_k['housename'].(!empty($arr_k['housename']) ? '`n'.create_lnk('Hausedit',$str_filename.'?op=edit&id='.$arr_k['value1']) : '').'</td>';
						$str_out .= '<!--<td>';
						//$str_out .= ;
						$str_out .= '</td>-->
									</tr>';

						$str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');
					}
					$str_out .= '</table>';

					db_free_result($res);
				}
			}

		}

	break;

	// Suchergebnisse, Hauptmenü
	default:

		addnav('Spielerschlüssel',$str_filename.'?op=user_keys');

		// Suchparameter ermitteln
		// Wenn Suchfeld abgeschickt:
		if(isset($_POST['houseid'])) {

			$session['su_houses'] = array();
			$session['su_houses_data'] = array();
			if(!empty($_POST['houseid'])) {
				$session['su_houses_data']['houseid'] = $_POST['houseid'];
				$session['su_houses']['houseid'] = (int)$session['su_houses_data']['houseid'];
			}
			if(!empty($_POST['housename'])) {
				$session['su_houses_data']['housename'] = $_POST['housename'];
				$session['su_houses']['housename'] = str_create_search_string(stripslashes($session['su_houses_data']['housename']));
			}
			if(!empty($_POST['name'])) {
				$session['su_houses_data']['name'] = $_POST['name'];
				$session['su_houses']['name'] = str_create_search_string(stripslashes($session['su_houses_data']['name']));
			}
			if(trim($_POST['owner']) != '') {
				$session['su_houses_data']['owner'] = $_POST['owner'];
				$session['su_houses']['owner'] = (int)$_POST['owner'];
			}
			if(!empty($_POST['status'])) {
				$session['su_houses_data']['status'] = $_POST['status'];
				$session['su_houses']['status'] = (int)$session['su_houses_data']['status'];
			}
			if(!empty($_POST['build_state'])) {
				$session['su_houses_data']['build_state'] = $_POST['build_state'];
				$session['su_houses']['build_state'] = (int)$session['su_houses_data']['build_state'];
			}
			if(sizeof($session['su_houses']) == 0) {
				unset($session['su_houses']);
				unset($session['su_houses_data']);
			}

		}

		// Suchparams löschen
		if(isset($_REQUEST['show_all'])) {
			unset($session['su_houses']);
			unset($session['su_houses_data']);
		}

		// Suchparams vorhanden?
		// Eigentlich sollten diese beim Verlassen des Editors resettet werden
		// Ich experimentiere jedoch mal damit, dass diese verbleiben
		if(isset($session['su_houses']) && sizeof($session['su_houses'])) {

			$str_where .= ' WHERE 1 ';

			foreach ($session['su_houses'] as $key=>$val) {
				if($key == 'name' || $key == 'housename') {
					$str_where .= ' AND '.$key.' LIKE "'.db_real_escape_string($val).'" ';
				}
				else {
					$str_where .= ' AND '.$key.' = "'.db_real_escape_string($val).'" ';
				}
			}

		}

		$str_baselnk = $str_filename;

		$str_count_sql = '	SELECT 		COUNT( * ) AS c
							FROM		houses h
							LEFT JOIN	accounts a ON h.owner=a.acctid '
							.$str_where;

		$str_data_sql = '	SELECT 		h.*, a.name AS owner_name, a.login
							FROM		houses h
							LEFT JOIN	accounts a ON h.owner=a.acctid '
							.$str_where.'
							ORDER BY 	h.houseid';

		$arr_res = page_nav($str_baselnk,$str_count_sql,20);

		$str_data_sql .= ' LIMIT '.$arr_res['limit'];

		$str_housetypes = ',Alle';
		foreach ($g_arr_house_builds as $str_k => $arr_b) {
			$str_housetypes .= ','.$str_k.','.$arr_b['name'];
		}

		$str_out .= '`c'.plu_mi('hsearch',(!sizeof($session['su_houses_data']) ? 0 : 1),false).' Suche:
					<div id="'.plu_mi_unique_id('hsearch').'" style="'.(!sizeof($session['su_houses_data']) ? 'display:none;' : '').'">'.form_header($str_baselnk);
		$arr_form = array('houseid'=>'Hausnr.,int',
							'housename'=>'Hausname',
							'name'=>'Besitzer (Login)',
							'owner'=>'Besitzer (AcctID); 0 für ohne Besitzer,int',
							'status'=>'Haustyp,enum,'.$str_housetypes,
							'build_state'=>'Hausstatus,enum,,Alle
															,'.HOUSES_BUILD_STATE_SELL.',im Verkauf
															,'.HOUSES_BUILD_STATE_EMPTY.',Leeres Grundstück
															,'.HOUSES_BUILD_STATE_ABANDONED.',verlassen
															,'.HOUSES_BUILD_STATE_IP.',im Ausbau
															,'.HOUSES_BUILD_STATE_INIT.',Baustelle
															,'.HOUSES_BUILD_STATE_EXT.',in Erweiterung');
		$str_out .= generateform($arr_form,(isset($session['su_houses_data']) ? $session['su_houses_data'] : array()),false,'Suchen!');
		$str_out .= (sizeof($session['su_houses_data']) ? create_lnk('`^Alle anzeigen`0',$str_baselnk.'?show_all=1').'`n' : '').'
					</form></div>`c
					`iUm folgendes habe ich Charliemagne gebeten: '.$str_data_sql.'`i`n
					Ergeben hat dies '.$arr_res['count'].' Ergebnis(se) total, verteilt auf '.$arr_res['maxpage'].' Seite(n).';

		$str_out .= '<hr />
						`n`c<table cellpadding="2" cellspacing="1">
						';

		$res = db_query($str_data_sql);

		if(db_num_rows($res) == 0) {

			$str_out .= '<tr><td>`iKeine Ergebnisse gefunden!`i</td></tr>';

		}
		else {
			$str_out .= '<tr class="trhead" style="font-weight:bold;"><td width="100">Hausnr. / ID</td><td width="200">Hausname</td><td width="200">Eigentümer</td></tr>';
		}

		$str_edit_select = '<select name="act" onchange="this.form.submit()">
							<option value="">Haus</option>
							<option value="extensions">Anbauten</option>
							<option value="rooms">Gemächer</option>
							</select>';

		// Ergebnisse zeigen
		while($h = db_fetch_assoc($res)) {

			$h['owner_name'] = (empty($h['owner_name']) ? 'Ohne Besitzer' : $h['owner_name']);

			$str_out .= '<tr class="trlight">
							<td>'.$h['houseid'].'</td>
							<td>'.$h['housename'].'</td>
							<td>'.$h['owner_name'].'</td>
							</tr>
							<tr class="trdark">
							<td>
								'.get_house_state($h['status'],$h['build_state'],false,false).'
							</td>
							<td colspan="2" align="right">
								'.create_lnk('Haus editieren',$str_filename.'?op=edit&id='.$h['houseid']).'
							</td>
							</tr>';

		}

		$str_out .= '</table>`c';
		// END Ergebnisse zeigen

	break;
	// END Hauptmenü
}

output($str_out, true);

page_footer();
?>
