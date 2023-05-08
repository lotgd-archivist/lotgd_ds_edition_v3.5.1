<?php
/**
* su_item.php: Superuser-Editor / Verwaltungswerkzeug des neuen Drachenserver-Itemsystems
* @author talion <t@ssilo.de>
* @version DS-E V/2
*/

$str_filename = basename(__FILE__);
$page=(isset($_GET['page'])?$_GET['page']:0);
require_once('common.php');
$access_control->su_check(access_control::SU_RIGHT_EDITORITEMS,true);

function get_tpl_select ( $val, $enum=false, $str_namefilter='' ) {

	$tpl_list = '';

	$sql = 'SELECT tpl_name,tpl_id FROM items_tpl ';

	if(!empty($str_namefilter)) {
		$sql .= ' WHERE tpl_name LIKE "'.$str_namefilter.'" ';
	}

	$sql .= 'ORDER BY tpl_name ASC';

	$res = db_query($sql);

	while( $c = db_fetch_assoc($res) ) {

		if($enum) {
			$tpl_list .= ','.$c['tpl_id'].','.utf8_htmlspecialchars(str_replace(',','',strip_appoencode($c['tpl_name'],3)));
		}
		else {
			$tpl_list .= '<option value="'.$c['tpl_id'].'" '.($val == $c['tpl_id'] ? 'selected="selected"': '').'>'.utf8_htmlspecialchars(strip_appoencode($c['tpl_name'],3)).'</option>';
		}

	}

	return($tpl_list);

}

function get_cat_select ( $val , $enum=false ) {

	$cats = '';

	$sql = 'SELECT class_name,id FROM items_classes ORDER BY class_name ASC';
	$res = db_query($sql);

	while( $c = db_fetch_assoc($res) ) {

		if($enum) {
			$cats .= ','.$c['id'].','.utf8_htmlspecialchars(str_replace(',','',strip_appoencode($c['class_name'],3)));
		}
		else {
			$cats .= '<option value="'.$c['id'].'" '.($val == $c['id'] ? 'selected="selected"': '').'>'.utf8_htmlspecialchars(strip_appoencode($c['class_name'],3)).'</option>';
		}

	}
	return($cats);

}

function get_buff_select ( $val , $enum=false ) {

	$buffs = '';

	$sql = 'SELECT buff_name,id FROM items_buffs ORDER BY buff_name ASC';
	$res = db_query($sql);

	while( $c = db_fetch_assoc($res) ) {

		if($enum) {
			$buffs .= ','.$c['id'].','.utf8_htmlspecialchars(str_replace(',','',strip_appoencode($c['buff_name'],3)));
		}
		else {
			$buffs .= '<option value="'.$c['id'].'" '.($val == $c['id'] ? 'selected="selected"': '').'>'.utf8_htmlspecialchars(strip_appoencode($c['buff_name'],3)).'</option>';
		}

	}
	return($buffs);

}

function get_type_select ( $val , $enum=false ) {

	$types = '';

	if($enum) {
		$types .= ','.ITEM_BUFF_NEWDAY.',Newday';
		$types .= ','.ITEM_BUFF_FIGHT.',Kampf';
		$types .= ','.ITEM_BUFF_USE.',Benutzen';
		$types .= ','.ITEM_BUFF_PET.',Haustier';
	}
	else {
		$types .= '<option value="'.ITEM_BUFF_NEWDAY.'" '.(ITEM_BUFF_NEWDAY == $val ? 'selected="selected"': '').'>Newday</option>';
		$types .= '<option value="'.ITEM_BUFF_FIGHT.'" '.(ITEM_BUFF_FIGHT == $val ? 'selected="selected"': '').'>Kampf</option>';
		$types .= '<option value="'.ITEM_BUFF_USE.'" '.(ITEM_BUFF_USE == $val ? 'selected="selected"': '').'>Benutzen</option>';
		$types .= '<option value="'.ITEM_BUFF_PET.'" '.(ITEM_BUFF_PET == $val ? 'selected="selected"': '').'>Haustier</option>';
	}

	return($types);

}

function get_combo_type_select ( $val , $enum=false ) {

	$types = '';

	if($enum) {
		$types .= ','.ITEM_COMBO_NEWDAY.',Newday';
		$types .= ','.ITEM_COMBO_ALCHEMY.',Alchemie';
		$types .= ','.ITEM_COMBO_RUNES.',Runen';
		$types .= ','.ITEM_COMBO_COOKING.',Küche';
	}
	else {
		$types .= '<option value="'.ITEM_COMBO_NEWDAY.'" '.(ITEM_COMBO_NEWDAY == $val ? 'selected="selected"': '').'>Newday</option>';
		$types .= '<option value="'.ITEM_COMBO_ALCHEMY.'" '.(ITEM_COMBO_ALCHEMY == $val ? 'selected="selected"': '').'>Alchemie</option>';
		$types .= '<option value="'.ITEM_COMBO_RUNES.'" '.(ITEM_COMBO_RUNES == $val ? 'selected="selected"': '').'>Runen</option>';
		$types .= '<option value="'.ITEM_COMBO_COOKING.'" '.(ITEM_COMBO_COOKING == $val ? 'selected="selected"': '').'>Küche</option>';
	}

	return($types);

}

function get_hook_select ( $val , $enum=false ) {

	$types = '';

	$arr_hooks = array();

	if ( $handle = dir('item_modules') )
	{
		$filename = array();
		while (false !== ($file = $handle->read()))
		{

			if(mb_strpos($file,'.php')) {

				$file = str_replace('.php','',$file);

				$arr_hooks[] = $file;

			}

		}
		$handle->close();
	}

	if(sizeof($arr_hooks))
	{
		sort($arr_hooks);

		foreach ($arr_hooks as $file) {
			if($enum) {
				$types .= ','.$file.','.$file;
			}
			else {
				$types .= '<option value="'.$file.'" '.($file == $val ? 'selected="selected"': '').'>'.$file.'</option>';
			}
		}

	}

	return($types);

}

page_header('Itemeditor');

output('`c`b`&Itemeditor`0`b`c`n');

// Grundnavi erstellen
addnav('Zurück');
grotto_nav();

addnav('Aktionen');
// END Grundnavi erstellen

// Evtl. Fehler / Erfolgsmeldungen anzeigen
if($session['message'] != '') {
	output('`n`b'.$session['message'].'`b`n`n');
	$session['message'] = '';
}
// END Evtl. Fehler / Erfolgsmeldungen anzeigen

// MAIN SWITCH
$op = ($_REQUEST['op'] ? $_REQUEST['op'] : '');

switch($op) {

	case '':	// Standardansicht, Auswahlmenü, Suchmaske

		$out = '`&Willkommen im Itemeditor, dem Universalwerkzeug, wenn es um materielle Pfründe geht!`n
				Du als `bGott`b hast nun folgende Möglichkeiten:`n`n
				- Itembuffs bearbeiten. Jeder Schablone kannst du bis zu zwei Buffs verschiedenen Typs zuweisen.`n
				- Itemkategorien bearbeiten. Alle Items müssen einer Kategorie zugewiesen werden, die z.B. Benennungen für die Spalten vorgibt.`n
				- Itemschablonen bearbeiten. Viele Items stammen von einer Schablone ab. In dieser sind bestimmte Eigenschaften, Werte, Kategorie etc. zentral festgelegt, was gleichzeitig die Bearbeitung aller Items einer Schablone ermöglicht.`n
				- Items bearbeiten. Alle Items müssen einer Kategorie zugewiesen werden, die z.B. Benennungen für die Spalten vorgibt.`n
				`nHier kannst du suchen:`n`n`0
				';

		$acctid = (isset($_REQUEST['acctid']) && $_REQUEST['acctid'] != '' ? $_REQUEST['acctid'] : -1 );

		// Wenn Login gegeben, durch Acctid ersetzen
		if( (int)$acctid == 0 && $acctid != '0' ) {
			$arr_tmp = db_fetch_assoc(db_query('SELECT acctid FROM accounts WHERE login="'.$acctid.'" LIMIT 1'));
			if($arr_tmp['acctid'] > 0) {
				$acctid = $arr_tmp['acctid'];
			}
			else {
				$acctid = -1;
			}
		}

		$ipp = (int)$_REQUEST['ipp'];
		$cat = ( isset($_REQUEST['cat']) ? (int)$_REQUEST['cat'] : -1 );
		$depo1 = ( isset($_REQUEST['depo1']) && $_REQUEST['depo1'] != '' ? (int)$_REQUEST['depo1'] : -8000000 );
		$depo2 = ( isset($_REQUEST['depo2']) && $_REQUEST['depo2'] != '' ? (int)$_REQUEST['depo2'] : -8000000 );
		$tpl = ( isset($_REQUEST['tpl']) && $_REQUEST['tpl'] != '' ? $_REQUEST['tpl'] : -1);
		$what = $_REQUEST['what'];
		// Wofür das ganze Theater? Um Umlaute korrekt im Return-Link mitschleifen zu können, ohne dass es zu bösen Navs kommt
		$name_in = urldecode(stripslashes($_REQUEST['name']));
		$name_in = utf8_html_entity_decode(str_replace('|','&',$name_in));
		$name = str_create_search_string($name_in);

		$code = stripslashes($_POST['code']);

		$ipp = ($ipp == 0 ? 50 : $ipp);

		// Basislink
		$base_link = $str_filename . '?ipp='.$ipp.'&cat='.$cat.'&acctid='.$acctid.'&what='.$what.'&name='.urlencode(str_replace('&','|',utf8_htmlentities($name_in))).'&tpl='.$tpl.'&depo1='.$depo1.'&depo2='.$depo2.'&page=';

		// Suchmaske
		$link = $str_filename . '';
		addnav('',$link);

		$cats = '-1,Alle,';
		$cats .= '0,Unkategorisiert';
		$cats .= get_cat_select($cat,true);

		$tpls = '-1,Alle,';
		$tpls .= ' ,Ohne Schablone';
		$tpls .= get_tpl_select($tpl,true);

		// History setzen
		set_restorepage_history($base_link.$page);
		// END History setzen

		$out .= '`c<form action="'.$link.'" method="POST">
						Was anzeigen?
						<select name="what" onchange="this.form.submit()">
							<option value=""> ------ </option>
							
							'.( ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) ? '<option value="items" '.($what == 'items' ? 'selected="selected"' : '').'>Items</option>' : '').'
							
							<option value="items_tpl" '.($what == 'items_tpl' ? 'selected="selected"' : '').'>Item-Schablonen</option>
							
							'.( ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) ? '<option value="classes" '.($what == 'classes' ? 'selected="selected"' : '').'>Kategorien</option>' : '').'
							
							'.( ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) ? '<option value="buffs" '.($what == 'buffs' ? 'selected="selected"' : '').'>Buffs</option>' : '').'
							
							'.( ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) ? '<option value="combos" '.($what == 'combos' ? 'selected="selected"' : '').'>Kombos</option>' : '').'
							
							</select> <input type="submit" value="Zeig her!">`n`n';

		
		if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD))addnav('Neues Item',$str_filename . '?op=edit_item');
		
		
		addnav('Neue Schablone',$str_filename . '?op=edit_itemtpl');
		
		if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD))
		{
			addnav('Neue Kategorie',$str_filename . '?op=edit_cat');
			addnav('Neuer Buff',$str_filename . '?op=edit_buff');
			addnav('Neue Kombo',$str_filename . '?op=edit_combo');
			addnav('Stats');
			addnav('Waldfund-Häufigkeit',$str_filename . '?op=stats_find_forest');
			addnav('ID-Liste erstellen',$str_filename . '?op=id_list');
		}
		if($acctid > 0 && $access_control->su_check(access_control::SU_RIGHT_EDITORUSER)) {
			addnav('Zum:');
			addnav('Usereditor','user.php?op=edit&userid='.$acctid);
		}

		if($what != '') {

			switch($what) {
				case 'items':
					$out .= '
						Bei Items:`n';
					$arr_form = array	(
										'acctid'=>'Besitzer? (Accountid ODER Login),int',
										'cat'=>'Kategorie?,enum,'.$cats,
										'tpl'=>'Schablone?,enum,'.$tpls,
										'name'=>'Name?,text,128',
										'depo1'=>'Deposit-Wert 1,int',
										'depo2'=>'Deposit-Wert 2,int'
										);
					$arr_data = array('acctid'=>($acctid==-1?'':$acctid),'cat'=>$cat,'tpl'=>$tpl,'name'=>$name_in,'depo1'=>($depo1 != -8000000 ? $depo1 : ''),'depo2'=>($depo2 != -8000000 ? $depo2 : ''));

					$head = '<td>`bID`b</td>
							<td>`bKategorie`b</td>
							<td>`bSchablone`b</td>
							<td>`bName`b</td>
							<td>`bBesitzer`b</td>
							';

					$tpl = ($tpl == ' ' ? '' : $tpl);

					$where = ' WHERE 1 '.
					($cat>-1 ? ' AND it.tpl_class="'.$cat.'" ' : '').
					($acctid>-1 ? ' AND i.owner="'.$acctid.'" ' : '').
					($name!='' ? ' AND i.name LIKE "'.$name.'" ' : '').
					($tpl!=-1 ? ' AND i.tpl_id="'.$tpl.'" ' : '').
					($depo1!=-8000000 ? ' AND i.deposit1="'.$depo1.'" ' : '').
					($depo2!=-8000000 ? ' AND i.deposit2="'.$depo2.'" ' : '');
					if($where==' WHERE 1 ') $where.='=0'; //unnötiges Komplettdurchsuchen der Tabelle
					$count_sql = 'SELECT COUNT(*) AS c FROM items i LEFT JOIN items_tpl it USING(tpl_id) '.$where;
					$sql = 'SELECT i.*,it.tpl_name,ic.class_name,a.name AS ownername FROM items i
						LEFT JOIN items_tpl it ON it.tpl_id = i.tpl_id
						LEFT JOIN accounts a ON a.acctid=i.owner
						LEFT JOIN items_classes ic ON ic.id = it.tpl_class
						'.$where.' ORDER BY name ASC, id ASC ';

					break;

				case 'items_tpl':

					$out .= '
						Bei Items-TPL: `n';
					
					if(!($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)))
					{
						$cats = '31,31 : Atramon-Items';
						$cat=31;
					}
					
					$arr_form = array	(
										'cat'=>'Kategorie?,enum,'.$cats,
										'tpl'=>'Schablonen-ID?,text,10',
										'name'=>'Name?,text,128',
										'code'=>'PHP-Code in Code-Hook?,text'
										);
					$arr_data = array('cat'=>$cat,'tpl'=>($tpl != -1?$tpl:''),'name'=>$name_in,'code'=>$code);

					$head = '<td>`bID`b</td>
						<td>`bKategorie`b</td>
						<td>`bName`b</td>';

					$where = ' WHERE 1 '.
								($cat>-1 ? ' AND it.tpl_class="'.$cat.'" ' : '').
								($name ? ' AND it.tpl_name LIKE "'.$name.'" ' : '').
								($tpl!=-1 ? ' AND it.tpl_id LIKE "%'.$tpl.'%" ' : '').
								($code ? ' AND it.hookcode LIKE "%'.db_real_escape_string($code).'%" ' : '');
					$count_sql = 'SELECT COUNT(*) AS c FROM items_tpl it '.$where;
					$sql = 'SELECT it.*,ic.class_name FROM items_tpl it
						LEFT JOIN items_classes ic ON ic.id = it.tpl_class
						'.$where.' ORDER BY tpl_name ASC, tpl_id ASC ';

					break;

				case 'classes':

					$head = '<td>`bID`b</td>
						<td>`bName`b</td>
						<td>`bSortierung (von hoch nach tief)`b</td>';

					$where = ' WHERE 1 ';
					$count_sql = 'SELECT COUNT(*) AS c FROM items_classes '.$where;
					$sql = 'SELECT * FROM items_classes ic
						'.$where.' ORDER BY class_order DESC, class_name ASC, id ASC ';

					break;

				case 'buffs':

					$head = '<td>`bID`b</td>
						<td>`bName`b</td>
						<td>`bTyp`b</td>';

					$where = ' WHERE 1 ';
					$count_sql = 'SELECT COUNT(*) AS c FROM items_buffs '.$where;
					$sql = 'SELECT * FROM items_buffs
						'.$where.' ORDER BY buff_name ASC, id ASC ';

					break;

				case 'combos':

					$head = '<td>`bID`b</td>
						<td>`bName`b</td>
						<td>`bTyp`b</td>
						<td>`bItem 1`b</td>
						<td>`bItem 2`b</td>
						<td>`bItem 3`b</td>
						<td>`bErgebnis`b</td>
						<td>`bBuff`b</td>
						<td>`bChance`b</td>
						<td>`bReihenfolge?`b</td>
						';

					$where = ' WHERE 1 ';
					$count_sql = 'SELECT COUNT(*) AS c FROM items_combos '.$where;
					$sql = 'SELECT * FROM items_combos
						'.$where.' ORDER BY combo_name ASC, combo_id ASC ';

					break;

			}

			// Suchformular anzeigen
			if(!empty($arr_form)) {
				$out .= generateform($arr_form,$arr_data,false,'Suchen!');
			}

			$out .= '</form>`c';

			// Navi erzeugen
			$arr_page_res = page_nav($base_link,$count_sql,$ipp);
			$sql .= ' LIMIT '.$arr_page_res['limit'];
			// END Navi erzeugen

			$style = 'trdark';

			$out .= '`c<table cellspacing="2" cellpadding="2"><tr class="trhead">
					'.$head.'
					<td>`bAktionen`b</td>
					</tr>';

			$res = db_query($sql);

			$out .= '`7Suchanfrage an die Datenbank: '.$sql.'`n`n';

			if(db_num_rows($res) == 0) {
				$out .= '`iKeine Ergebnisse gefunden!`i';
			}
			else {

				$out .= '`i`b`^'.$arr_page_res['count'].'`7`b Einträge gefunden!`i`n`n';

				while($i = db_fetch_assoc($res)) {

					$out .= '<tr class="'.$style.'">';

					switch($what) {
						case 'items':

							$i['class_name'] = ($i['class_name'] == '' ? 'Keine' : $i['class_name']);
							$i['tpl_name'] = ($i['tpl_name'] == '' ? 'Keine' : $i['tpl_name']);

							$i['ownername'] = ($i['owner'] == ITEM_OWNER_GUILD ? 'GILDE Nr. '.$i['deposit1'] : $i['ownername']);

							$out .= '
								<td>'.$i['id'].'</td>
								<td>'.$i['class_name'].'`&</td>
								<td>'.$i['tpl_name'].'`&</td>
								<td>'.$i['name'].'`&</td>
								<td>'.$i['ownername'].'`&</td>
								';

							$editlink = $str_filename . '?op=edit_item&id='.$i['id'];
							$dellink = $str_filename . '?op=del_item&id='.$i['id'];

							break;

						case 'items_tpl':

							$i['class_name'] = ($i['class_name'] == '' ? 'Keine' : $i['class_name']);

							$out .= '
								<td>'.$i['tpl_id'].'</td>
								<td>'.$i['class_name'].'`&</td>
								<td>'.$i['tpl_name'].'`&</td>
								';

							$editlink = $str_filename . '?op=edit_itemtpl&tpl_id_old='.$i['tpl_id'];
							$dellink = $str_filename . '?op=del_itemtpl&tpl_id='.$i['tpl_id'];

							break;

						case 'classes':

							$out .= '
								<td>'.$i['id'].'</td>
								<td>'.$i['class_name'].'`&</td>
								<td> - '.$i['class_order'].' - </td>
								';

							$editlink = $str_filename . '?op=edit_cat&id='.$i['id'];
							$dellink = $str_filename . '?op=del_cat&id='.$i['id'];

							break;

						case 'buffs':

							$out .= '
								<td>'.$i['id'].'</td>
								<td>'.$i['buff_name'].'`&</td>
								<td>'.$i['type'].'`&</td>
								';

							$editlink = $str_filename . '?op=edit_buff&id='.$i['id'];
							$dellink = $str_filename . '?op=del_buff&id='.$i['id'];

							break;

						case 'combos':

							$str_combo_lnk = $str_filename.'?op=edit_itemtpl&tpl_id_old=';
							$str_combo_buff_lnk = $str_filename.'?op=edit_buff&id=';

							$out .= '
								<td>'.$i['combo_id'].'</td>
								<td>'.$i['combo_name'].'`&</td>
								<td>'.$i['type'].'`&</td>
								<td>'.$i['id1'].(!empty($i['id1']) && $i['id1'] != '*' ? create_lnk(' * ',$str_combo_lnk.$i['id1']) : '').'`&</td>
								<td>'.$i['id2'].(!empty($i['id2']) && $i['id2'] != '*' ? create_lnk(' * ',$str_combo_lnk.$i['id2']) : '').'`&</td>
								<td>'.$i['id3'].(!empty($i['id3']) && $i['id3'] != '*' ? create_lnk(' * ',$str_combo_lnk.$i['id3']) : '').'`&</td>
								<td>'.$i['result'].(!empty($i['result']) ? create_lnk(' * ',$str_combo_lnk.$i['result']) : '').'`&</td>
								<td>'.$i['buff'].(!empty($i['buff']) ? create_lnk(' * ',$str_combo_buff_lnk.$i['buff']) : '').'`&</td>
								<td>'.$i['chance'].'`&</td>
								<td>'.($i['no_order'] ? 'Nein' : 'Ja').'`&</td>
								';

							$editlink = $str_filename . '?op=edit_combo&combo_id='.$i['combo_id'];
							$dellink = $str_filename . '?op=del_combo&combo_id='.$i['combo_id'];

							break;
					}


					$out .= '<td nowrap="nowrap">
									[ <a href="'.$editlink.'">Edit</a> ]
									[ `$<a href="'.$dellink.'" onClick="return confirm(\'Willst du diesen Eintrag wirklich löschen?\');">Del</a>`& ]
								</td>
								</tr>';

					$style = ($style == 'trlight' ? 'trdark' : 'trlight');

					addnav('',$editlink);

					addnav('',$dellink);

				}
			}

			$out .= '</table>`c';
		}

		$out .= '</form>';

		output($out,true);

	break;

	// Editformular für ein Item
	case 'edit_item':

		output('`bItem bearbeiten:`b`n`n');

		$id = (int)$_REQUEST['id'];

		$ready = false;

		$data = array(
							'name'=>$item_tpl['tpl_name'],
							'tpl_id'=>$item_tpl['tpl_id'],
							'description'=>$item_tpl['tpl_description'],
							'special_info'=>$item_tpl['tpl_special_info'],
							'value1'=>$item_tpl['tpl_value1'],
							'value2'=>$item_tpl['tpl_value2'],
							'hvalue'=>$item_tpl['tpl_hvalue'],
							'hvalue2'=>$item_tpl['tpl_hvalue2'],
							'gold'=>$item_tpl['tpl_gold'],
							'gems'=>$item_tpl['tpl_gems'],
							'weight'=>$item_tpl['tpl_weight'],
							'item_count' => 0,
							'deposit1'=>'0',
							'deposit2'=>'0',
							'owner'=>'0',
							'id_view'=>$id
							);

		$form = array(
						'id' => 'ID,hidden',
						'id_view' => 'ID,viewonly',

						'name_prev' => 'Namens-Vorschau:,preview,name',
						'name' => 'Name,text,255',
						'desc_prev' => 'Beschreibungs-Vorschau:,preview,description',
						'description' => 'Beschreibung,textarea,30,10',
						'gold' => 'Goldpreis,int',
						'gems' => 'Gempreis,int',
						'value1' => 'Wert 1,int',
						'value2' => 'Wert 2,int',
						'hvalue' => 'HWert 1,int',
						'hvalue2' => 'HWert 2,int',
						'special_info' => 'Zusatzinfo,text,80',
						'weight' => 'Gewicht,int',						
						'item_count' => 'Anzahl der Items, int',
						'deposit1' => 'Ablagewert 1 ('.ITEM_LOC_EQUIPPED.' für angelegt),int',
						'deposit2' => 'Ablagewert 2,int',
						'owner' => 'Besitzer,int',
						'tpl_id' => 'Schablone,enum'.get_tpl_select(0,true),
						'Schablonendaten,title',
						'tpl' => 'Daten,viewonly',

						'Content,title',
						'content'=>'Daten,viewonly'
						);

		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		if( $_GET['act'] == 'save' ) {

			$sql = ($id ? 'UPDATE ' : 'INSERT INTO ');

			$sql .= ' items SET ';

			foreach($data as $key => $v) {
				if(isset($_POST[$key])) {
					$sql .= $key.' = "'.$_POST[$key].'",';
				}
			}

			$sql = mb_substr($sql,0,mb_strlen($sql)-1);

			$sql .= ($id ? ' WHERE id='.$id : '');

			db_query($sql);

			$session['message'] = '`@Erfolgreich gespeichert!';

			if(!$id) {$id = db_insert_id();}

			if(!isset($_POST['back'])) {
				redirect($str_filename . '?op=edit_item&id='.$id);
			}
			else {
				redirect($ret);
			}

		}
		// END SPEICHERN


		addnav('Abbruch',$ret);

		// Keine ID gegeben
		if(!$id) {

			$tpl_id = $_REQUEST['tpl_id'];
			$tpl = item_get_tpl(' tpl_id="'.$tpl_id.'" ');

			// Zunächst Auswahlformular für Schablone anzeigen, wenn Schablonenid nicht gegeben
			if($tpl['tpl_id'] == '') {

				$link = $str_filename . '?op=edit_item';
				addnav('',$link);

				output('<form action="'.$link.'" method="POST">',true);

				// Wenn Eingrenzung gegeben:
				$str_search = '';
				if(isset($_POST['search'])) {
					$str_search = $_POST['search'];
					if(!empty($str_search)) {
						$str_search = str_create_search_string($str_search);
					}
				}

				output('Item-Schablone: <select name="tpl_id"><option value="" selected="selected">Bitte auswählen:</option>
															'.get_tpl_select('',false,$str_search).'
																</select>`n`n
						Oder nach Schablonenname filtern: <input type="text" name="search" value="'.$_POST['search'].'">`n`n
						<input type="submit" value="Weiter">',true);

			}
			else { // Wenn gegeben, Schablonendaten abrufen, mit $data mergen

				$tpl_merge = array();

				foreach($data as $key=>$d) {
					if($key != 'tpl_id') {
						$tpl_merge[$key] = $tpl['tpl_'.$key];
					}
					else {
						$tpl_merge[$key] = $tpl[$key];
					}
				}

				$data = array_merge($data,$tpl_merge);

				$ready = true;

			}

		}
		else {	// ID gegeben

			// Itemdata abrufen und mit $data mergen
			$data = array_merge($data,item_get('id='.$id,false));

			// Schablone abrufen
			$tpl = item_get_tpl(' tpl_id="'.$data['tpl_id'].'" ');

			$ready = true;

			addnav('Schablone bearbeiten',$str_filename . '?op=edit_itemtpl&tpl_id_old='.$data['tpl_id']);
			addnav('Kopie anlegen',$str_filename . '?op=edit_item&copy=1&id='.$id);
		}

		// Formular anzeigen
		if($ready) {

			if($_GET['copy']) {
				$data['id'] = 0;
			}

			$data['tpl'] = $tpl;

			$link = $str_filename . '?op=edit_item&act=save';
			addnav('',$link);

			output('<form action="'.$link.'" method="POST">',true);

			showform($form,$data);

			output('`c<input type="submit" class="button" name="back" value="Speichern + Zurück">`c</form>',true);
		}

	break;

	// Editformular für eine Itemschablone
	case 'edit_itemtpl':

		$data = array('tpl_name'=>'',
						'tpl_id'=>'',
						'tpl_class'=>'',
						'tpl_description'=>'',
						'tpl_value1'=>0,
						'tpl_value2'=>0,
						'tpl_hvalue'=>0,
						'tpl_hvalue2'=>0,
						'tpl_gold'=>1,
						'tpl_gems'=>0,
						'tpl_special_info'=>'',
						'tpl_weight'=>1,
						'tpl_stackable' => 0,
						'buff1'=>0,
						'buff2'=>0,
						'use_hook'=>null,
						'newday_hook'=>null,
						'newday_furniture_hook'=>null,
						'battle_hook'=>null,
						'furniture_hook'=>null,
						'furniture_private_hook'=>null,
						'furniture_privateinvited_hook'=>null,
						'furniture_guild_hook'=>null,
						'find_forest_hook'=>null,
						'forest_death_hook'=>null,
						'hookcode'=>'',
						'gift_hook'=>null,
						'send_hook'=>null,
						'pvp_defeat_hook'=>null,
						'pvp_victory_hook'=>null,
						'equip_hook'=>null,
						'trade_hook'=>null,
						'find_forest'=>0,
						'guildinvent'=>0,
						'throw'=>0,
						'deposit'=>0,
						'deposit_private'=>0,
						'deposit_guild'=>0,
						'vendor'=>0,
						'vendor_new'=>0,
						'spellshop'=>0,
						'giftshop'=>0,
						'alchemy'=>0,
						'cooking'=>0,
						'curse'=>0,
						'showinvent'=>0,
						'equip'=>0,
						'battle_mode'=>0,
						'stables_pet'=>0,
						'prisonescloose'=>0,
						'loose_dragon'=>0,
						'loose_dragon_death'=>0,
						'loose_forest_death'=>0,
						'distributor'=>0,
						'usershops'=>0,
						'newday_del'=>1,
						'maxcount'=>0,
						'deposit_show'=>0,
						'hot_item' =>  0
						);

		// ID gegeben
		$id = $_REQUEST['tpl_id_old'];

		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		// Wenn ID gegeben, Schablone abrufen und leere Formdata überschreiben
		if($id) {
			$sql = 'SELECT it.* FROM items_tpl it WHERE tpl_id="'.$id.'"';
			$res = db_query($sql);
			$data = db_fetch_assoc($res);
		}

		// SPEICHERN
		if( $_GET['act'] == 'save' ) {

			$ready = false;

			// Auf korrekte ID checken
			if($_POST['tpl_id'] == '') {
				output('`4Es muss eine TPL-ID gesetzt werden!`0');
			}
			else {
				$_POST['tpl_id'] = mb_substr($_POST['tpl_id'],0,30);

				$item = item_get_tpl(' tpl_id="'.$_POST['tpl_id'].'" ');
				if($item['tpl_id'] != '' && $id != $item['tpl_id']) {
					output('`4Es muss eine einzigartige TPL-ID gesetzt werden!`0');
				}
				else {
					$ready = true;
				}
			}

			// Alles klar
			if($ready) {

				$sql = ($id ? 'UPDATE ' : 'INSERT INTO ');

				$sql .= ' items_tpl SET ';

				if(isset($_POST['content']))
				{
					$_POST['content'] = utf8_serialize($_POST['content']);
				}

				// Abzuspeichernde Daten durchgehen und SQL-String erstellen
				foreach($data as $key => $v) {
					if(isset($_POST[$key])) {
						if(empty($_POST[$key]) && is_null($v)) {
							$sql .= $key.' = null,';
						}
						else {
							$sql .= $key.' = "'.db_real_escape_string($_POST[$key]).'",';
						}
					}
				}

				$sql = mb_substr($sql,0,mb_strlen($sql)-1);

				$sql .= ($id ? ' WHERE tpl_id="'.$id.'"' : '');

				db_query($sql);

				// Wenn ID gegeben (Edit) und Unterschied zu neuer ID
				if(!empty($id) && ($data['tpl_id'] != $_POST['tpl_id']) ) {
					// Items mit alter ID auf neue bringen
					item_set(' tpl_id="'.$data['tpl_id'].'"',array('tpl_id'=>$_POST['tpl_id']),true,'' );
					$session['message'] = '`&'.db_affected_rows().'`@ Items auf neue Schablone gesetzt!`n';
				}

				// ID aktualisieren
				$id = $_POST['tpl_id'];

				$session['message'] .= '`@Erfolgreich gespeichert!';

				if(!$id)
				{
					$id = $data['tpl_id'];
				}

				redirect($str_filename . '?op=edit_itemtpl&tpl_id_old='.$id);
			}
			else
			{	// Fehler beim Speichern
				// Bereits eingegebene Daten übernehmen
				$data = array_merge($data,$_POST);
			}

		}
		// END SPEICHERN

		$hook_list = 'enum,,N / A'.get_hook_select(0,true).',_codehook_,PHPCode-Hook';

		$cat_list = 'enum';

		$cat_list .= get_cat_select(0,true);

		$buff_list = 'enum,0,Keiner'.get_buff_select(0,true);

		$trade_list = 'enum,0,Gar nicht,1,Kauf,2,Verkauf,3,Beides';

		$chance_list = 'enum,0,Gar nicht,1,Extrem selten,2,Sehr selten,3,Selten,4,Gelegentlich,5,Häufig,6,Sehr häufig,7,Extrem häufig';

		$equip_list = 'enum,0,Gar nicht,'.ITEM_EQUIP_WEAPON.',Als Waffe,'.ITEM_EQUIP_ARMOR.',Als Rüstung';



if(!($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)))
					{
						$cats = '31,31 : Atramon-Items';
						$cat_list = 'enum,31,31 : Atramon-Items';
						$cat=31;
					}


		$form = array(
					'tpl_id' => 'ID (max. 30 Zeichen; keine Sonderzeichen; eindeutig),text,30',
					'name_prev' => 'Namens-Vorschau:,preview,tpl_name',
					'tpl_name' => 'Name,text,255',
					'tpl_class' => 'Kategorie,'.$cat_list,
					'desc_prev' => 'Beschreibungs-Vorschau:,preview,tpl_description',
					'tpl_description' => 'Beschreibung,textarea,30,10',
					'tpl_gold' => 'Goldpreis,int',
					'tpl_gems' => 'Gempreis,int',
					'tpl_value1' => 'Wert 1,int',
					'tpl_value2' => 'Wert 2,int',
					'tpl_hvalue' => 'HWert 1,int',
					'tpl_hvalue2' => 'HWert 2,int',
					'tpl_special_info' => 'Zusatzinfo,text,80',
					'Buffs,title',
					'buff1' => '1. Buff,'.$buff_list,
					'buff2' => '2. Buff,'.$buff_list,
					'Hooks,title',
					'newday_hook' => 'Newday-Hook für Items im Inventar,'.$hook_list,
					'newday_furniture_hook' => 'Newday-Hook für eingelagerte Items,'.$hook_list,
					'battle_hook' => 'Kampf-Hook,'.$hook_list,
					'furniture_hook' => 'Einrichtungs-Hook,'.$hook_list,
					'furniture_private_hook' => 'Privatraum-Einrichtungs-Hook,'.$hook_list,
					'furniture_privateinvited_hook' => 'Privatraum-Einrichtungs-Hook für Nicht-Hausbewohner,'.$hook_list,
					'furniture_guild_hook' => 'Gilden-Einrichtungs-Hook,'.$hook_list,
					'use_hook' => 'Benutzen-Hook,'.$hook_list,
					'find_forest_hook' => 'Waldbeute-Hook,'.$hook_list,
					'forest_death_hook' => 'Waldtod-Hook,'.$hook_list,
					'gift_hook' => 'Geschenk-Hook,'.$hook_list,
					'send_hook' => 'Handelshaus-Hook,'.$hook_list,
					'pvp_victory_hook' => 'PvP-Sieg-Hook,'.$hook_list,
					'pvp_defeat_hook' => 'PvP-Verlust-Hook,'.$hook_list,
					'equip_hook' => 'Anlegen/Ablegen-Hook,'.$hook_list,
					'trade_hook' => 'Handels-Hook (wird beim Wanderhändler-Kauf/Verkauf eines Items ausgeführt),'.$hook_list,
					'hookcode' => 'PHP-Code der als Hook ausgeführt wird,textarea,60,30',
					'Einstellungen,title',
					'hot_item' => 'Setzt PvP-Immu tempor. außer Kraft / Versenden an Immu-Leute möglich?,enum,0,Nein,1,Ja; Versenden mögl.,2,Ja; Kein Versenden',
					'find_forest' => 'Wahrscheinl. für Beute in Wald?,'.$chance_list,
					'prisonescloose' => 'Wahrscheinl. für Verlust bei Kerkerflucht?,'.$chance_list,
					'loose_dragon_death' => 'Wahrscheinl. für Verlust bei DK-Niederl.?,'.$chance_list,
					'loose_forest_death' => 'Wahrscheinl. für Verlust bei Wald-Niederl.?,'.$chance_list,
					'maxcount' => 'Max. Anzahl gesamt vorhanden (0 = unbegrenzt)?,int',
					'maxcount_per_user' => 'Max. Anzahl pro User erlaubt (0 = unbegrenzt)?,int',
					'tpl_stackable' => 'Ist das Item stapelbar?,bool|?Werden mehrere Exemplare des Typs im Inventar als (int x) angezeigt',
					'deposit' => 'Max. Anzahl davon im Haus?,int',
					'deposit_private' => 'Max. Anzahl davon im Privatraum?,int',
					'deposit_guild' => 'Max. Anzahl davon als Möbel in der Gilde?,int',
					'deposit_show' => 'Von außen sichtbar?,bool|?Bei Hausbetrachtung.',
					'loose_dragon' => 'Verlust bei DK?,enum,0,Gar nicht,1,Nur Ort: Inventar,2,Alle',
					'throw' => 'Kann weggeworfen werden?,bool',
					'distributor' => 'Über Handelshaus versendbar?,bool',
					'usershops' => 'Auf dem Markt (usershops) anbietbar?,bool',
					'showinvent' => 'Im Inventar sichtbar?,bool',
					'battle_mode' => 'Während Kampf nutzbar in.. (in Verbindung mit Kampfhook),enum,0,Gar nicht,1,Wald,2,Arena,3,Beidem',
					'newday_del' => 'Besitzerlose Exemplare bei Newday löschen?,bool',
					'guildinvent' => 'Darf in Gildeninventar gelegt werden?,bool',
					'stables_pet' => 'Als Haustier in Ställen zu erwerben?,bool',
					'vendor_new' => 'Als Neuware bei Wanderhändler?,bool',
					'giftshop' => 'Im Geschenkeladen verfügbar?,bool',
					'alchemy' => 'Im Schmelztigel verwendbar?,bool',
					'cooking' => 'In der Küche verwendbar?,bool',
					'vendor' => 'Beim Wanderhändler als Gebrauchtware handelbar?,'.$trade_list,
					'spellshop' => 'Im Zauberladen handelbar?,'.$trade_list,
					'auction' => 'Im Auktionshaus handelbar?,bool',
					'curse' => 'Bei der Hexe abnehm-/annehmbar?,'.$trade_list,
					'equip' => 'Anlegbar?,'.$equip_list
					);



		addnav('Abbruch',$ret);

		$link = $str_filename . '?op=edit_itemtpl&act=save';
		addnav('',$link);

		output('<form action="'.$link.'" method="POST">',true);

		// Wenn keine Kopie einer Schablone angelegt werden soll
		if(!$_GET['copy']) {
			// .. merken wir uns die alte ID falls vorhanden
			output('<input type="hidden" name="tpl_id_old" value="'.$data['tpl_id'].'">',true);
		}
		else {	// sonst vergessen wir sie
			$data['tpl_id'] = 'Kopie von '.$data['tpl_id'];
		}

		showform($form,$data);

		output('</form>',true);

		if($data['tpl_id'] != '') {
			if(($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD))) addnav('Items mit dieser Schablone',$str_filename . '?what=items&tpl='.$data['tpl_id']);
			if(($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD))) addnav('Item dieser Schablone anlegen',$str_filename . '?op=edit_item&tpl_id='.$data['tpl_id']);
			addnav('Kopie dieser Schablone anlegen',$str_filename . '?op=edit_itemtpl&copy=1&tpl_id_old='.$data['tpl_id']);
			if(($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD))) addnav('Items aktualisieren',$str_filename . '?op=itemtpl_setchildren&tpl_id='.$data['tpl_id']);
		}

		break;

	// Aktualisieren der von einer Schablone abstammenden Items:
	// VORSICHT!
	case 'itemtpl_setchildren':

		$tpl_id = $_REQUEST['tpl_id'];

		if($_GET['act'] == 'ok' && $tpl_id != '') {

			$data = item_get_tpl(' tpl_id="'.$tpl_id.'" ');

			$what = $_GET['what'];

			item_set(' tpl_id="'.$tpl_id.'" ',array( $what => $data['tpl_'.$what] ),true,'' );

			$session['message'] = '`&'.db_affected_rows().'`@ Items aktualisiert!';

			redirect($str_filename . '?op=itemtpl_setchildren&tpl_id='.$tpl_id );

		}

		$doit = false;
		$count = 0;

		output('`&Diese Funktion bietet die Möglichkeit, alle von einer Itemschablone abstammenden Items
				automatisch an neue Werte der Schablone anzupassen.`n
				`$Hierbei ist extreme Vorsicht geboten!`n
				`&Wenn Items in einer bestimmten Spalte individuelle Werte enthalten können, sollte von
				einer automatischen Aktualisierung abgesehen werden, da dadurch sämtliche Daten überschrieben
				werden!');

		if($tpl_id == '') {
			output('`n`nKeine Schablone gegeben!');
		}
		else {
			$data = item_get_tpl(' tpl_id="'.$tpl_id.'" ');

			output('`n`n');
			output_array($data,'Schablone: ');

			$count = item_count(' tpl_id="'.$tpl_id.'" ');
		}

		if($count == 0) {
			output('`n`nKeine Items mit dieser Schablone vorhanden!');
		}
		else {
			output('`n`n'.$count.' Items mit dieser Schablone vorhanden!');
			$doit = true;
		}

		if($doit) {

			output('`n`nWas willst du also überschreiben?`n`n');

			$base_link = $str_filename . '?op=itemtpl_setchildren&tpl_id='.$tpl_id.'&act=ok'.'&what=';

			$links = array(
						'Name'=>'name',
						'Beschreibung'=>'description',
						'Wert 1'=>'value1',
						'Wert 2'=>'value2',
						'HWert 1'=>'hvalue',
						'HWert 2'=>'hvalue2',
						'Zusatzinfo'=>'special_info',
						'Gold'=>'gold',
						'Gems'=>'gems'
						);

			foreach($links as $name=>$what) {

				$link = $base_link.$what;

				$tpl_what = 'tpl_'.$what;

				addnav('',$link);

				output('`&<a href="'.$link.'" onClick="return confirm(\'Willst du '.$name.' wirklich überschreiben?\');">`b'.$name.'`b</a> ( `^'.$tpl_what.' : `&'.$data[$tpl_what].' )`n`n');

			}

		}

		addnav('Zurück zur Schablone',$str_filename . '?op=edit_itemtpl&tpl_id_old='.$tpl_id );

		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		addnav('Abbruch',$ret);

		break;

	case 'edit_cat':	// Editformular für eine Kategorie

		$data = array('class_name'=>'',
						'id'=>'0',
						'class_description'=>'',
						'class_value1'=>'',
						'class_value2'=>'',
						'class_hvalue'=>'',
						'class_hvalue2'=>'',
						'class_gold'=>'Gold',
						'class_gems'=>'Edelsteine',
						'class_special_info'=>'',
						'class_order'=>0
						);

		$id = (int)$_REQUEST['id'];
		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		if($id) {
			$sql = 'SELECT * FROM items_classes ic WHERE id='.$id;
			$res = db_query($sql);
			$data = db_fetch_assoc($res);
		}

		if( $_GET['act'] == 'save' ) {

			$sql = ($id ? 'UPDATE ' : 'INSERT INTO ');

			$sql .= ' items_classes SET ';

			foreach($data as $key => $v) {
				if(isset($_POST[$key])) {
					$sql .= $key.' = "'.$_POST[$key].'",';
				}
			}

			$sql = mb_substr($sql,0,mb_strlen($sql)-1);

			$sql .= ($id ? ' WHERE id='.$id : '');

			db_query($sql);

			$session['message'] = '`@Erfolgreich gespeichert!';

			if(!$id) {$id = db_insert_id();}

			redirect($str_filename . '?op=edit_cat&id='.$id);

		}

		$str_orderenum = 'enum';
		for($i=0; $i<=50; $i++) {
			$str_orderenum .= $i.', - '.$i.' - ';
		}

		$form = array(
					'idv' => 'ID,viewonly',
					'id' => 'id,hidden',
					'class_order' => 'Sortierungsnr.,'.$str_orderenum.'|?Je höher, desto weiter vorne erscheinen Items dieser Kategorie in der Itemansicht',
					'class_name' => 'Name',
					'class_description' => 'Beschreibung',
					'class_gold' => 'Goldspalte Name',
					'class_gems' => 'Gemspalte Name',
					'class_value1' => 'Wert 1 Name',
					'class_value2' => 'Wert 2 Name',
					'class_hvalue' => 'HWert 1 Name',
					'class_hvalue2' => 'HWert 2 Name',
					'class_special_info' => 'Zusatzinfo'
					);

		addnav('Abbruch',$ret);

		$link = $str_filename . '?op=edit_cat&act=save';
		addnav('',$link);

		output('Damit ein Wert angezeigt wird, muss die Spalte benannt sein. Bei leerer Benennung wird im Inventar auch der Wert ausgeblendet.
				Ausnahme: Zusatzinfo.`n
				<form action="'.$link.'" method="POST">',true);

		showform($form,$data);

		output('</form>',true);

		break;

	case 'edit_combo':	// Editformular für eine Kombo

		$data = array('combo_name'=>'',
						'combo_id'=>'0',
						'id1'=>'',
						'id2'=>'',
						'id3'=>'',
						'type'=>'',
						'result'=>'',
						'buff'=>'',
						'hook'=>'',
						'hookcode'=>'',
						'chance'=>'',
						'no_order'=>'0',
						'no_entry'=>'0'
						);

		$id = (int)$_REQUEST['combo_id'];
		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		if($id) {
			$sql = 'SELECT * FROM items_combos ic WHERE combo_id='.$id;
			$res = db_query($sql);
			$data = db_fetch_assoc($res);
		}

		if( $_GET['act'] == 'save' ) {

			$bool_ok = true;

			$sql = ($id ? 'UPDATE ' : 'INSERT INTO ');

			$sql .= ' items_combos SET ';

			// Wenn Reihenfolge egal ist, müssen wir die SchablonenIDs alphabetisch sortieren
			if(!empty($_POST['no_order'])) {

				// Wenn Wildcard verwendet, aussteigen
				if($_POST['id1'] == '*' || $_POST['id2'] == '*' || $_POST['id3'] == '*') {
					output('`n`$`bBei Kombos ohne spezielle Reihenfolge sind Wildcards nicht erlaubt!`0`b`n`n');
					$bool_ok = false;
				}

				$arr_ids = array();
				if(!empty($_POST['id1'])) {
					$arr_ids[] = stripslashes($_POST['id1']);
					unset($_POST['id1']);
				}
				if(!empty($_POST['id2'])) {
					$arr_ids[] = stripslashes($_POST['id2']);
					unset($_POST['id2']);
				}
				if(!empty($_POST['id3'])) {
					$arr_ids[] = stripslashes($_POST['id3']);
					unset($_POST['id3']);
				}
				sort($arr_ids,SORT_STRING);
				// Sortiert sind sie, nun den SQL-Query aktualisieren
				$sql .= (!empty($arr_ids[0]) ? ' id1 = "'.db_real_escape_string($arr_ids[0]).'", ' : '');
				$sql .= (!empty($arr_ids[1]) ? ' id2 = "'.db_real_escape_string($arr_ids[1]).'", ' : '');
				$sql .= (!empty($arr_ids[2]) ? ' id3 = "'.db_real_escape_string($arr_ids[2]).'", ' : '');
			}
			// END Reihenfolge egal

			foreach($data as $key => $v) {
				if(isset($_POST[$key]) && $key != 'combo_id') {
					$sql .= $key.' = "'.db_real_escape_string(stripslashes($_POST[$key])).'",';
				}
			}

			$sql = mb_substr($sql,0,mb_strlen($sql)-1);

			$sql .= ($id ? ' WHERE combo_id='.$id : '');

			if($bool_ok) {
				db_query($sql);

				$session['message'] = '`@Erfolgreich gespeichert!';

				if(!$id) {$id = db_insert_id();}

				redirect($str_filename . '?op=edit_combo&combo_id='.$id);
			}
			else {
				$data = array_merge($data,$_POST);
			}

		}

		$tpl_list = get_tpl_select( 0 ,true);
		$tpl_list_n = ',enum,,Keines,*,WILDCARD'.$tpl_list;
		$tpl_list_p = ',enum,,Keines'.$tpl_list;
		$tpl_list = ',enum,*,WILDCARD'.$tpl_list;

		$type_list = ',enum'.get_combo_type_select(0,true);

		$buff_list = ',enum, ,Keiner'.get_buff_select(0,true);

		$hook_list = 'enum, ,N / A'.get_hook_select(0,true).',_codehook_,PHPCode-Hook';

		$form = array(
					'combo_idv' => 'ID,viewonly',
					'combo_id' => 'id,hidden',
					'combo_name' => 'Name',
					'id1' => 'Item 1'.$tpl_list,
					'id2' => 'Item 2'.$tpl_list,
					'id3' => 'Item 3'.$tpl_list_n,
					'result' => 'Item-Ergebnis (bei Alchemie Crafting etc.)'.$tpl_list_p,
					'buff' => 'Item-Ergebnisbuff (bei Newday etc.)'.$buff_list,
					'chance'=>'Wahrscheinlichkeit (0-255),int',
					'no_order'=>'Keine bestimmte Reihenfolge (Bei Alchemie etc.),bool',
					'no_entry'=>'Kein Eintrag ins Rezeptbuch,bool',
					'type' => 'Typ'.$type_list,
					'hook'=>'Hook,'.$hook_list,
					'hookcode' => 'PHP-Code der als Hook ausgeführt wird,textarea,40,20'
					);

		addnav('Abbruch',$ret);

		if($data['combo_id'] != '') {
			addnav('Kopie dieser Kombo anlegen',$str_filename . '?op=edit_combo&copy=1&combo_id='.$data['combo_id']);
		}

		$link = $str_filename . '?op=edit_combo&act=save';
		addnav('',$link);

		output('Um Kombinationen ohne Berücksichtigung der Reihenfolge zu erstellen, bitte das entsprechende Häkchen setzen. Die Schablonen
				werden dann automatisch nach Alphabet sortiert und so abgespeichert.`n
				Wildcards ( = Jedes Item erlaubt ) können nur bei Kombinationen mit Reihenfolge verwendet werden und funktionieren derzeit auch nicht in Newday-Combos!`n
				<form action="'.$link.'" method="POST">',true);

		// Wenn keine Kopie einer Kombo angelegt werden soll
		if(!$_GET['copy']) {

		}
		else {	// sonst vergessen wir die ID
			$data['combo_id'] = 'Kopie von ID '.$data['combo_id'];
		}

		showform($form,$data);

		output('</form>',true);

		break;

	case 'edit_buff':	// Editformular für einen Buff

		$data = array('buff_name'=>'',
						'id'=>'0',
						'buff'=>'',
						'type'=>0,
						'name'=>'',
						'roundmsg'=>'',
						'wearoff'=>'',
						'effectmsg'=>'',
						'effectnodmgmsg'=>'',
						'effectfailmsg'=>'',
						'rounds'=>'',
						'atkmod'=>1,
						'defmod'=>1,
						'regen'=>'',
						'minioncount'=>'',
						'minbadguydamage'=>'',
						'maxbadguydamage'=>'',
						'lifetap'=>'',
						'damageshield'=>1,
						'badguydmgmod'=>1,
						'badguyatkmod'=>1,
						'badguydefmod'=>1,
						'plus_charm'=>'',
						'activate'=>'',
						'survive_death' => 0
						);

		$id = (int)$_REQUEST['id'];
		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		if($id) {
			$sql = 'SELECT * FROM items_buffs WHERE id='.$id;
			$res = db_query($sql);
			$data = db_fetch_assoc($res);
			$data['buff'] = utf8_unserialize($data['buff']);
		}

		if( $_GET['act'] == 'save' ) {

			$sql = ($id ? 'UPDATE ' : 'INSERT INTO ');

			$sql .= ' items_buffs SET ';

			foreach($data as $key => $v) {
				if(isset($_POST[$key])) {
					$sql .= $key.' = "'.$_POST[$key].'",';
				}
			}

			$sql = mb_substr($sql,0,mb_strlen($sql)-1);

			$sql .= ($id ? ' WHERE id='.$id : '');

			db_query($sql);

			$session['message'] = '`@Erfolgreich gespeichert!';

			if(!$id) {$id = db_insert_id();}

			redirect($str_filename . '?op=edit_buff&id='.$id);

		}

		addnav('Abbruch',$ret);

		$link = $str_filename . '?op=edit_buff&act=save';
		addnav('',$link);

		$type_enum = 'enum'.get_type_select(0,true);

		$form = array(
					'id' => 'ID,viewonly',
					'buff_name' => 'Buff-Name',
					'type' => 'Typ,'.$type_enum,
					'Buff-Meldungen,title',
					'name' => 'Name',
					'roundmsg' => 'Meldung jede Runde',
					'wearoff' => 'Ablaufmeldung',
					'effectmsg' => 'Effektmeldung',
					'effectnodmgmsg'=>'Kein Schaden Meldung',
					'effectfailmsg'=>'Fehlschlag Meldung',
					'Effekte,title',
					'rounds'=>'Hält Runden (nach Aktivierung),int',
					'atkmod'=>'Multiplier. Angriffsmulti Spieler,int',
					'defmod'=>'Multiplier. Verteidigungsmulti Spieler,int',
					'regen'=>'Feste LP-Regeneration,int',
					'minioncount'=>'Anzahl der "Buffelemente",int',
					'minbadguydamage'=>'Deren min. Schaden',
					'maxbadguydamage'=>'Deren max. Schaden',
					'lifetap'=>'Multiplier. Gegnerschaden->Leben,int',
					'damageshield'=>'Multiplier. Gegnerschaden->Gegnerabzug,int',
					'badguydmgmod'=>'Multiplier für Gegnerschaden,int',
					'badguyatkmod'=>'Multiplier für Gegnerangriff,int',
					'badguydefmod'=>'Multiplier für Gegnerdef,int',
					'activate'=>'Aktivieren bei `n(Mögl.: roundstart offense defense durch Kommata getrennt)',
					'Nicht-Kampf / Permanenzeffekte,title',
					'plus_charm'=>'Plus / Minus für Charme,int',
					'survive_death'=>'Bleibt beim Tod bestehen,bool|?Sinnvoll z.B. bei bestimmten Knappen'
					);

		output('<form action="'.$link.'" method="POST">',true);

		if(!$_GET['copy']) {
			output('<input type="hidden" name="id" value="'.$id.'">');
		}
		else {
			$data['buff_name'] = 'Kopie von '.$data['buff_name'];
		}

		showform($form,$data);

		output('</form>',true);

		if($id) {
			addnav('Kopie anlegen',$str_filename . '?op=edit_buff&copy=1&id='.$id);
		}

		break;


	case 'del_item':	// Item löschen

		$sql = 'DELETE FROM items WHERE id='.(int)$_GET['id'];
		if(db_query($sql)) {$session['message'] = '`@Item Erfolgreich gelöscht!';}

		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		redirect($ret);

		break;

	case 'del_itemtpl':	// Itemschablone löschen

		$tpl_id = $_REQUEST['tpl_id'];

		if($_GET['act'] == 'ok') {
			if($_GET['del'] == 1) {
				$sql = 'DELETE FROM items WHERE tpl_id="'.$tpl_id.'"';
			}
			else {
				$sql = 'UPDATE items SET tpl_id=0 WHERE tpl_id="'.$tpl_id.'"';
			}

			if ( !db_query ( $sql ) ) { $session['message'] = '`4Items konnten nicht umgestellt werden! NICHTS gelöscht.'; }
			else {

				$session['message'] .= db_affected_rows().'`@ Items verändert / gelöscht, `n';

				$sql = 'DELETE FROM items_tpl WHERE tpl_id="'.$tpl_id.'" LIMIT 1';
				if ( !db_query ( $sql ) ) { $session['message'] .= '`4Item-TPL konnte nicht gelöscht werden!'; }
				else { $session['message'] .= '`@Item-TPL erfolgreich gelöscht!'; }

			}

			// Restorepage-History
			$ret = get_restorepage_history();
			if(empty($ret)) {
				$ret = $str_filename;
			}

			redirect($ret);

		}

		output('VORSICHT: Sollen die Items ohne Schablone auf 0 gesetzt oder aber GELÖSCHT werden?');

		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		addnav('Abbruch!',$ret);
		addnav('Auf Null setzen',$str_filename . '?op=del_itemtpl&tpl_id='.$tpl_id.'&act=ok' );
		addnav('VORSICHT:');
		addnav('Alle Löschen!',$str_filename . '?op=del_itemtpl&tpl_id='.$tpl_id.'&act=ok&del=1' );

		break;

	case 'del_cat':	// Kat löschen

		$sql = 'UPDATE items_tpl SET tpl_class=0 WHERE tpl_class='.(int)$_GET['id'];
		db_query($sql);

		$session['message'] = db_affected_rows().'`@ Item-Schablonen entkategorisiert,`n';

		$sql = 'DELETE FROM items_classes WHERE id='.(int)$_GET['id'];
		if(db_query($sql)) {$session['message'] .= '`@Kategorie Erfolgreich gelöscht!';}
		else {
			$session['message'] = '`4Fehler bei Löschen der Kategorie!';
		}

		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		redirect($ret);

		break;

	case 'del_buff':	// Buff löschen

		$sql = 'UPDATE items_tpl SET buff1=0 WHERE buff1='.(int)$_GET['id'];
		db_query($sql);

		$count += db_affected_rows();

		$sql = 'UPDATE items_tpl SET buff2=0 WHERE buff2='.(int)$_GET['id'];
		db_query($sql);

		$count += db_affected_rows();

		$session['message'] = $count.'`@ Item-Schablonen entbufft,`n';

		$sql = 'DELETE FROM items_buffs WHERE id='.(int)$_GET['id'];
		if(db_query($sql)) {$session['message'] .= '`@Buff Erfolgreich gelöscht!';}
		else {
			$session['message'] = '`4Fehler bei Löschen des Buffs!';
		}

		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		redirect($ret);

		break;

	case 'del_combo':	// Kombo löschen

		$sql = 'DELETE FROM items_combos WHERE combo_id='.(int)$_GET['combo_id'];
		if(db_query($sql)) {$session['message'] .= '`@Kombo Erfolgreich gelöscht!';}
		else {
			$session['message'] = '`4Fehler bei Löschen der Kombo!';
		}

		// Restorepage-History
		$ret = get_restorepage_history();
		if(empty($ret)) {
			$ret = $str_filename;
		}

		redirect($ret);

		break;

	case 'stats_find_forest':	//Statistik: Waldfund-Häufigkeit

		$chance_list = array('Gar nicht','Extrem selten','Sehr selten','Selten','Gelegentlich','Häufig','Sehr häufig','Extrem häufig');
		$row = db_get_all('SELECT find_forest, count(find_forest) AS c FROM `items_tpl` GROUP BY find_forest','find_forest');
		$str_out.='Häufigkeitsverteilung der Schablonen für "Im Wald findbar"
		`n<table>';
		for($i=0; $i<=7; $i++)
		{
			$str_out.='<tr>
			<td>'.$chance_list[$i].':</td>
			<td>'.(int)$row[$i]['c'].' Schablonen</td></tr>';
		}
		output($str_out.'</table>
		`nZur Erklärung: Erst wird die Häufigkeitsstufe ausgewürfelt, wobei die Wahrscheinlichkeiten nicht gleichmäßig verteilt sind. Dann ein zufälliges Item dieser Stufe gewählt.
		`nEs ist also nicht sinnvoll, die seltene Stufe mit Müll zu füllen der nicht oft auftreten soll. Seltene Items werden dadurch noch seltener. Besser wäre eine ausgewogene Verteilung in obiger Liste.');
		addnav('Zum Editor',$str_filename);
		break;

	case 'id_list':
		if(isset($_POST['owner']) || isset($_POST['where']))
		{
			$sql='SELECT id FROM items WHERE 1 ';
			$arr_ids=array();
			if(intval($_POST['owner']>0))
			{
				$sql.='AND owner='.intval($_POST['owner']).' ';
			}
			elseif($_POST['owner']>'')
			{
				$row=db_fetch_assoc(db_query('SELECT acctid FROM accounts WHERE login="'.db_real_escape_string($_POST['owner']).'"'));
				$sql.='AND owner='.intval($row['acctid']).' ';
			}
			$sql.=$_POST['where'];
			$sql.=' ORDER BY id';
			output($sql);
			$result=db_query($sql);
			while($row=db_fetch_assoc($result))
			{
				array_push($arr_ids,$row['id']);
			}
			$str_ids=implode(', ',$arr_ids);
			output('`n<form action="#">
			<textarea cols=80 rows=15>'.$str_ids.'</textarea></form>`n`n');
		}
		output('<form action="'.$str_filename.'?op=id_list" method="post">
		SELECT id
		`nFROM items
		`nWHERE owner=<input type="text" name="owner"> (Login oder ID)
		`n<input type="text" name="where"> (zusätzliche Bedingungen. Vorsicht!)
		`nORDER BY id
		`n<input type="submit" class="button" value="Suchen">
		</form>');
		addnav('',$str_filename.'?op=id_list');
		addnav('Zurück zum Editor',$str_filename);
		break;

	default:
		output('Was hast du denn HIER verloren?! Op: '.$op);
		addnav('Zurück',$str_filename . '');
}


page_footer();
?>
