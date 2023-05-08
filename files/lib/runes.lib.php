<?php
/**
* runes.lib.php: Funktionen fürs Runen-addon
* @author Alucard <diablo3-clan[AT]web.de>
* @version DS-E V/2
*/

/**
 * tpl_id der Unbekannten Rune
 */
define('RUNE_DUMMY_TPL',getsetting('runes_dummytpl','r_dummy'));

/**
 * class_id der Runen
 */
define('RUNE_CLASS_ID',getsetting('runes_classid',0));

/**
 * Name der Runen-ExtraInfo-Tabelle
 */
define('RUNE_EI_TABLE','runes_extrainfo');

/**
 * Indentifizieren-Zahlungsart: gar nix bezahlen
 */
define('RUNE_IDENTPAY_NONE', 0);

/**
 * Indentifizieren-Zahlungsart: mit Gold bezahlen
 */
define('RUNE_IDENTPAY_GOLD', 1);

/**
 * Indentifizieren-Zahlungsart: in Edelsteinen bezahlen
 */
define('RUNE_IDENTPAY_GEMS', 2);

/**
 * Indentifizieren-Zahlungsart: mit einer Rune bezahlen
 */
define('RUNE_IDENTPAY_RUNE', 3);

/**
 * Indentifizieren Edelstein-Kostenge
 */
define('RUNE_IDENTPAY_GEMS_VALUE', 2);

/**
 * Indentifizieren Gold-Kosten
 */
define('RUNE_IDENTPAY_GOLD_VALUE', 7500);

/**
 * Magiegegenstände in der form '"tpl_id1","tpl_id2",...,"tpl_idN"'
 */
define('RUNE_MAGIC_STUFF', '"r_fdr_hgn","r_fdr_mnn"');

define('R_FEHU', 		1);
define('R_URUZ', 		2);
define('R_THURISAZ', 	3);
define('R_ANSUZ', 		4);
define('R_RAIDHO', 		5);
define('R_KENAZ', 		6);
define('R_GEBO', 		7);
define('R_WUNJO', 		8);
define('R_HAGALAZ', 	9);
define('R_NAUDIZ', 		10);
define('R_ISA', 		11);
define('R_JERA', 		12);
define('R_EIWAZ', 		13);
define('R_PETHRO', 		14);
define('R_ALGIZ', 		15);
define('R_SOWILO', 		16);
define('R_TEIWAZ', 		17);
define('R_BERKANA', 	18);
define('R_EHWAZ', 		19);
define('R_MANNAZ', 		20);
define('R_LAGUZ', 		21);
define('R_INGWAZ', 		22);
define('R_DAGAZ', 		23);
define('R_OTHALA', 		24);



/**
* @desc Array der germanischen Götter
* @global array $rune_gods
* @name $rune_gods
*/
$rune_gods = array('Thor', 'Wotan', 'Tyr', 'Freyr', 'Idun', 'Loki', 'Njörd', 'Freyja');

//temporäre runenliste zurücksetzen
unset($session['rune_known_list']);

/**
* @author Alucard
* @desc Identifiziert die Rune vom typ x
* @param value1 der rune
* @return anzahl der identifizierten runen
*/
function runes_identify($runeid, $same=true, $set_uei=true){
	global $session;

	if( $runeid > 24 ){
		return -1;
	}

	$res = item_list_get (	'i.owner='.$session['user']['acctid'].' AND i.tpl_id="'.RUNE_DUMMY_TPL.'" AND i.value2='.$runeid,
							'', true, 'i.id, it.tpl_class');
	$count 	= db_num_rows( $res );


	while( ($rune = db_fetch_assoc( $res )) ){
		if( !isset($tpl) ){
			$tpl	= item_get_tpl('tpl_class = '.$rune['tpl_class'].' AND tpl_value2='.$runeid);
			$tpl['name'] 		= $tpl['tpl_name'];
			$tpl['description'] = $tpl['tpl_description'];
			$tpl['gold'] 		= $tpl['tpl_gold'];
			$tpl['gems'] 		= $tpl['tpl_gems'];
			$tpl['value1'] 		= $tpl['tpl_value1'];
			$tpl['value2'] 		= $tpl['tpl_value2'];
			$tpl['hvalue'] 		= $tpl['tpl_hvalue'];
			$tpl['hvalue2'] 	= $tpl['tpl_hvalue2'];
		}

		item_set('id='.$rune['id'],$tpl);

	}
	if( $set_uei ){
		$arr_tmp = user_get_aei('runes_ident');
		$a_runes_ident = utf8_unserialize($arr_tmp['runes_ident']);
		$a_runes_ident[''.$runeid] = true;
		ksort($a_runes_ident);
		db_query('UPDATE account_extra_info SET runes_ident="'.db_real_escape_string(utf8_serialize($a_runes_ident)).'" WHERE acctid='.$session['user']['acctid'].' LIMIT 1');
	}

	return $count;
}


/**
* @author Alucard
* @desc Liste der unidentifizierten Runen des Nutzers
* @return mixed SQL-Result
*/
function runes_get_unidentified(){
	global $session;
	return item_list_get (	'i.owner='.$session['user']['acctid'].' AND tpl_id=\''.RUNE_DUMMY_TPL.'\'',
							'', false, 'i.id, i.name, i.value2');
}

/**
* @author Alucard
* @desc Gibt dem Nutzer eine Rune (zufällig)
* @param bool $bool_moreInfo nur name, oder mehr Infos zurückgeben
* @return mixed string Runenname oder array('name'=>string, 'id'=>int)
*/
function runes_give( $bool_moreInfo=false ){
	global $session;
	$sql 	= 'SELECT id FROM '.RUNE_EI_TABLE.' WHERE seltenheit<='.e_rand(1,255).' ORDER BY RAND() LIMIT 1';
	$res 	= db_query( $sql );
	$rune 	= db_fetch_assoc($res);
	$known 	= runes_get_known(false);
	if( $known[$rune['id']] ){
		$tpl	= item_get_tpl('tpl_class = '.RUNE_CLASS_ID.' AND tpl_value2='.$rune['id'], 'tpl_id, tpl_name');
		item_add($session['user']['acctid'], $tpl['tpl_id']);
		$str_ret = $tpl['tpl_name'];
	}
	else{
		item_add($session['user']['acctid'], RUNE_DUMMY_TPL, array('tpl_value2'=>$rune['id']));
		$str_ret = 'Unbekannte Rune';
	}
	
	if( $bool_moreInfo ){
		return array('name'=>$str_ret, 'id'=>$rune['id']);
	}
	return $str_ret;
}

/**
* @author Alucard
* @desc Zählt unidentifizierte Runen des Nutzers
* @return int Anzahl
*/
function runes_get_unidentified_count($rid=0){
	global $session;
	return item_count (	'i.owner='.$session['user']['acctid'].
						' AND tpl_id=\''.RUNE_DUMMY_TPL.'\''.
						($rid ? ' AND value2='.$rid : ''));
}


/**
* @author Alucard
* @desc liefert alle Runen zurück
* @return mixed SQL-Result
*/
function runes_get($unidef=false, $stuff=false, $order='i.special_info, i.value2'){
	global $session;
	
	$sql = 'SELECT i.* FROM '.ITEMS_TABLE.' i'
		 .' JOIN items_tpl it ON it.tpl_id = i.tpl_id'
		// .' JOIN items_classes ic ON ic.id = it.tpl_class'
		 .' WHERE owner='.$session['user']['acctid']
		 .' AND (it.tpl_class='.RUNE_CLASS_ID
		 .($unidef ? '' : ' AND it.tpl_id <> \''.RUNE_DUMMY_TPL.'\'')
		 .($stuff ? ' OR it.tpl_id' : ' AND it.tpl_id NOT').' IN ('.RUNE_MAGIC_STUFF.')'
		 .') ORDER BY '.$order;

	return db_query($sql);
}


/**
* @author Alucard
* @desc liefert extrainfos der rune zurück
* @return mixed SQL-Result
*/
function runes_get_ei( $id ){
	//global $session;
	$where = '';
	if( is_array($id) ){
		$where = 'id IN ('.implode(',',$id).')';
	}
	else{
		$where = 'id='.((int)$id);
	}
	
	$sql = 'SELECT * FROM '.RUNE_EI_TABLE.' WHERE '.$where;

	return db_query($sql);
}


/**
* @author Alucard
* @desc Erstellen aller möglichen Runenrezepte
* @return array Liste der Runenrezepte
*/
function runes_get_recipelist(){
	$res = db_query('SELECT ic.combo_id AS id,ic.combo_name AS name FROM items_combos ic
					  LEFT JOIN items_tpl it ON ic.result = it.tpl_id
					  WHERE ic.type='.ITEM_COMBO_RUNES.' AND it.tpl_special_info <> "RZWE"');
	$ret = array();
	while( ($r=db_fetch_assoc($res)) ){
		$r['name'] = strip_appoencode(str_replace('r_mix_', '', $r['name']));
		$ret[] = $r;
	}
	return $ret;
}


/**
* @author Alucard
* @desc Speichert ein Rezept in den Array der bekannten Rezepte
* @return array bekannte Runenrezepte (array(id1,id2,...,idN))
*/
function runes_get_known_recipes(){
	return item_get_combolist(0,ITEM_COMBO_RUNES);
}

/**
* @author Alucard
* @desc Speichert den Rezeptarray
* @param array $arr bekannte Runenrezepte
*/
function runes_set_knwon_recipes( $arr ){
	$arr_tmp = item_get_combolist();
	$arr_tmp[ITEM_COMBO_RUNES] = $arr;
	item_set_combolist($arr_tmp);
}


/**
* @author Alucard
* @desc Speichert ein Rezept in den Array der bekannten Rezepte
* @param int $id ID des Rezepts in items_combos
* @return bool erfolgreich ausgeführt?
*/
function runes_add_known_recipe( $id ){
	$id = intval($id);
	$arr_tmp = user_get_aei('combos');
	$arr = utf8_unserialize($arr_tmp['combos']);

	if( !is_array($arr[ITEM_COMBO_RUNES]) ){
		$arr[ITEM_COMBO_RUNES] = array();
	}
	if( !is_numeric(array_search($id, $arr[ITEM_COMBO_RUNES])) ){
		array_push($arr[ITEM_COMBO_RUNES], $id);
		user_set_aei(array('combos'=>db_real_escape_string(utf8_serialize($arr))));
		return true;
	}
	return false;

}

/**
* @author Alucard
* @desc Gibt den HTML-Code für das Bild eines Items Zurück
* @param array $arr Array des Items
* @return string HTML-Code des Bildes
*/
function runes_get_recipe_image( $arr ){
	$ret = '';
	$known = runes_get_known();
	if( $arr['is_rune'] ){
		$ret = '<div><center><img src="./images/runes/'.
				($known[$arr['r_id']]?
					str_replace('r_','',$arr['tpl_id']).'.png" /><br>`~'.$arr['name'].'`0' :
					'unknown.png" /><br>`~Rune<br>unbekannt`0'
				).'</center></div>';
	}
	else{
		if( $arr['tpl_id']=='waffedummy' ){
			$ret = '<div><center><img src="./images/runes/runen-waffen.png" /><br>`~beliebige<br>Waffe`0</center></div>';
		}
		else if( $arr['tpl_id']=='rstdummy' ){
			$ret = '<div><center><img src="./images/runes/runen-ruestung.png" /><br>`~beliebige<br>Rüstung`0</center></div>';
		}
		else{
			$r = explode('_',str_replace('r_','',$arr['tpl_id']));
			$add_v = true;
			switch( $r[0] ){
				case 'cmup':
					$arr['name'] = 'Charmsteigerung um '.$r[1];
					$add_v = false;
				break;

				case 'lpup':
				break;

				case 'wpnup':
					$r[0] = 'runen-waffen';
				break;

				case 'amrup':
					$r[0] = 'runen-ruestung';
				break;

				default:
					$add_v = false;
				break;
			}

			if( $add_v ){
				$ret = '<div style="z-index: 3;position: relative; font-size: 20px; font-weight: bold; text-align: right;top: 70px;width: 64px;">+'.$r[1].'</div>
						<div>
							<img src="./images/runes/'.$r[0].'.png" />
						</div>';
			}
			else{
				$ret = '<div>'.$arr['name'].'</div>';
			}

		}
	}
	return $ret;
}


/**
* @author Alucard
* @desc Holt ein bestimmtes Runenrezept
* @param int $id ID des Rezepts in items_combos
* @param bool $bool_only_data Rezept ohne weitere bearbeitung der Daten
* @return array Rezept
*/
function runes_get_recipe( $id, $bool_only_data=false ){
	$id  =  intval($id);
	$sql = 	'SELECT ic.combo_id, ic.combo_name AS name, ic.id1, ic.id2, ic.id3, ic.result FROM items_combos ic '
			.($id ?
				'WHERE combo_id='.$id
			:
				'LEFT JOIN items_tpl it ON ic.result = it.tpl_id
				WHERE ic.type='.ITEM_COMBO_RUNES.' AND it.tpl_special_info <> "RZWE" ORDER BY RAND() LIMIT 1'
			);
	$res = db_query($sql);
	$arr = false;
	if( $res && db_num_rows($res) ){
		$arr = db_fetch_assoc($res);
		if( $bool_only_data ){
			return $arr;
		}
		$arr['name'] = strip_appoencode(str_replace('r_mix_', '', $arr['name']));
		$r = item_get_tpl(' tpl_id="'.$arr['id1'].'" ', 'tpl_special_info');
		$arr['tpl_special_info'] = $r['tpl_special_info'];
		if( $r['tpl_special_info']=='RZWE' ){
			$res = db_query('SELECT id1, id2, id3 FROM items_combos WHERE result="'.$arr['id1'].'"');
			if( $res && ($res = db_fetch_assoc($res)) ){
				$arr['id4'] = $arr['id2'];
				$arr['id3'] = $res['id3'];
				$arr['id2'] = $res['id2'];
				$arr['id1'] = $res['id1'];
			}
			else{
				$arr = false;
			}
		}
		if( $arr!== false ){
			for($i=1;$i<5&&!empty($arr['id'.$i]);++$i){
				$arr['id'.$i] = item_get_tpl(' tpl_id="'.$arr['id'.$i].'" ', 'tpl_name AS name, tpl_class='.RUNE_CLASS_ID.' AS is_rune, tpl_id, tpl_value2 AS r_id');
			}
			$arr['result'] = item_get_tpl(' tpl_id="'.$arr['result'].'" ', 'tpl_name AS name, tpl_class='.RUNE_CLASS_ID.' AS is_rune, tpl_id, tpl_value2 AS r_id');
		}
	}
	return $arr;
}

/**
* @author Alucard
* @desc filterfunktion für runes_only_known. Nicht zur expliziten nutzung gedacht
* @return bool bekannt?
*/
function runes_filter_known( $var ){
	return $var;
}

/**
* @author Alucard
* @desc Filtert die Runen, die im runes_ident array stehen und den wert 'false' haben heraus
* @param int $session_usage zur entlastung der Datenbank wird das ergebnis nach dem ersten Aufruf in die session gespeichert
* @return int Anzahl der bekannten Runen
*/
function runes_only_known( $arr ){
	if( is_array($arr) ){
		return array_filter($arr, 'runes_filter_known');
	}
	return array();
}


/**
* @author Alucard
* @desc Ermittelt die Anzahl der bekannten Runen eines Spielers
* @param int $session_usage zur entlastung der Datenbank wird das ergebnis nach dem ersten Aufruf in die session gespeichert
* @return int Anzahl der bekannten Runen
*/
function runes_get_known( $session_usage=true , $int_acctid = 0){
	global $session;
	//fix by bathi
	if($int_acctid > 0)$session_usage=false;
	
	if( !$session_usage || !isset($session['rune_known_list']) )
	{
		//fix by bathi
		$ident = ( ($int_acctid > 0) ? user_get_aei('runes_ident',$int_acctid) : user_get_aei('runes_ident') );
		$ident = utf8_unserialize($ident['runes_ident']);
		
		if( !is_array($ident) ){
			$ident = array();
		}
		//fix by bathi
		if($int_acctid > 0) return runes_only_known($ident);
		
		$session['rune_known_list'] = runes_only_known($ident);
	}

	return $session['rune_known_list'];
}


/**
* @author Alucard
* @desc Gibt einen zufälligen Gottername aus $rune_gods zurück
* @return string Göttername
*/
function runes_rand_god(){
	$a = $rune_gods = array('Thor', 'Wotan', 'Tyr', 'Freyr', 'Idun', 'Loki', 'Njörd', 'Freyja');
    shuffle($a);
	return $a[0];
}


/**
* @author Alucard
* @desc Gibt den Seltenheitsnamen in Bezug auf prameter $val zurück
* @param int $val 0-255 Seltenheit der Rune
* @return string Seltenheitsname
*/
function runes_get_rarity( $val ){
	if( $val > 230){
		return '`4sehr selten';
	}

	if( $val > 170){
		return '`qselten';
	}

	if( $val > 100){
		return '`^durchschnittlich';
	}

	if( $val > 50){
		return '`@oft';
	}

	return '`2sehr oft';
}


/**
* @author Alucard
* @desc Ermittelt den Rang des Spielers in Bezug auf seine Identifizierten Runen
* @param int $val 0-24 Anzahl der identifizierten Runen
* @param int Geschlecht ($session['user']['sex'])
* @return string Rang
*/
function runes_get_rank( $knownrunes, $sex ){
	$rank = '';
	if( !$knownrunes ){
		$rank = 'Unwissende';
		if( !$sex ){
			$rank .= 'r';
		}
	}
	else if( $knownrunes < 5 ){
		$rank = 'Lehrling';
	}
	else if( $knownrunes < 10 ){
		$rank = 'Forscher';
		if( $sex ){
			$rank .= 'in';
		}
	}
	else if( $knownrunes < 15 ){
		$rank = 'Wissende';
		if( !$sex ){
			$rank .= 'r';
		}
	}
	else if( $knownrunes < 20 ){
		$rank = 'Eingeweihte';
		if( !$sex ){
			$rank .= 'r';
		}
	}
	else if( $knownrunes < 24 ){
		$rank = 'Seneschall';
	}
	else if( $knownrunes == 24 ){
		$rank = $sex ? 'Matriarchin' : 'Patriarch';
	}
	return $rank;
}

?>
