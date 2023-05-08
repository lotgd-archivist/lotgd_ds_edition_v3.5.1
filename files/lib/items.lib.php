<?php
/**
* items.lib.php: LIB-Datei des neuen Drachenserver-Itemsystems. Enthält Basisfunktionen und Konstantendefs
* @author talion <t@ssilo.de>
* @version DS-E V/2
*/

// Konstantendefs: Bitte als Zahlencodes nur Werte > 1234567!

define('ITEM_BUFF_NEWDAY',1);
define('ITEM_BUFF_FIGHT',2);
define('ITEM_BUFF_USE',4);
define('ITEM_BUFF_PET',8);

define('ITEM_COMBO_NEWDAY',1);
define('ITEM_COMBO_ALCHEMY',2);
define('ITEM_COMBO_RUNES',4);
define('ITEM_COMBO_COOKING',8);

define('ITEM_OWNER_VENDOR',1234567);
define('ITEM_OWNER_SPELLSHOP',1234568);
define('ITEM_OWNER_GUILD',1234569);
define('ITEM_OWNER_PETERSEN',1234571);

define('ITEM_EQUIP_WEAPON',1);
define('ITEM_EQUIP_ARMOR',2);

// deposit1
define('ITEM_LOC_EQUIPPED',9999999);
// deposit2
define('ITEM_LOC_GUILDHALL',1234567);
define('ITEM_LOC_GUILDEXT',1234568);

define('ITEM_MOD_PATH','./item_modules/');

define('ITEMS_TABLE','items');
define('ITEMS_TPL_TABLE','items_tpl');

// Array: Enthält Informationen für einen evtl. Hook
$item_hook_info = array();
// Array: Enthält Referenz auf Item für einen evtl. Code-Hook
$hook_item = array();

/**
* @author talion
* @desc Lädt Moduldatei und führt Hook-Funktion aus bzw. führt Codehook aus
*			Funktioniert auch für Kombo-Hooks
* @param string Name des Itemmoduls (_codehook_ bei Codehooks)
* @param string Der Hook-Case welcher ausgeführt wird
* @param array Assoziativer Array mit Daten der an den Hook übergeben wird, also entweder Item oder Kombo
* @return mixed Rückgabe der jeweiligen Hook-Funktion bzw. false
*/
function item_load_hook ( $hook , $type , &$item ) {

	// Wenn Codehook
	if($hook == '_codehook_') {

		$GLOBALS['hook_item'] = &$item;
		$GLOBALS['hook_type'] = $type;

        $GLOBALS['item'] = &$item;

		$str_code = 'global $item_hook_info,$hook_item,$item,$hook_type,$Char,$session; '.$item['hookcode'];

		if(mb_strlen($str_code) > 3 && mb_strpos($str_code,';')) {
			$bool_correct = true;
			$bool_correct = eval(utf8_eval( $str_code ));

			if(false === $bool_correct) {

				output('`n`n`b`$FEHLER in Hook-Ausführung: '.$hook.' '.$type.'`0`n`n');

			}

			return($bool_correct);

		}
		else {
			return(false);
		}

        /** @noinspection PhpUnreachableStatementInspection */
        return(true);

	}

	$func = $hook.'_hook_process';

	if( !function_exists($func) ) {

		$path = ITEM_MOD_PATH . $hook . '.php';

		if( !is_file($path) ) { return(false); }

		require_once( $path );

	}

	$result = $func( $type , $item );

	return ( $result );

}

/**
* @author talion
* @desc Ermittelt einzelne Itemschablone
* @param string SQL-WHERE String
* @param string SQL-String, Welche Felder sollen selektiert werden (Optional, Standard auf alle)
* @return mixed Assoz. Array mit Schablone bzw. false, wenn keine Schablone gefunden
*/
function item_get_tpl ($where,$what='*') {

	$sql = 'SELECT '.$what.' FROM items_tpl WHERE '.$where;

	$sql .= ' LIMIT 1';

	$res = db_query($sql);

	if(db_num_rows($res)) {
		return(db_fetch_assoc($res));
	}
	else {
		return(false);
	}

}

/**
* @author talion
* @desc Ermittelt Itemkombo, in der angegebene Items enthalten sind. Reihenfolge ist wichtig!
* @param string ItemTPLid 1
* @param string ItemTPLid 2
* @param string ItemTPLid 3
* @param int Der Kombo-Typ (Alchemie, Newday..) bzw. 0 wenn Typ keine Rolle spielt
* @return mixed Assoz. Array mit Kombo bzw. false, wenn keine Kombo gefunden
*/
function item_get_combo ($id1,$id2,$id3,$type) {

	global $session;

	// Wenn Reihenfolge für diese Kombo egal ist
	$str_no_order = ' OR
						(no_order=1 AND ';
	// Übergebene IDs alphabetisch sortieren
	$arr_ids = array();
	if(!empty($id1)) {
		$arr_ids[] = stripslashes($id1);
	}
	if(!empty($id2)) {
		$arr_ids[] = stripslashes($id2);
	}
	if(!empty($id3)) {
		$arr_ids[] = stripslashes($id3);
	}
	sort($arr_ids,SORT_STRING);
	// Sortiert sind sie, nun den SQL-Query aktualisieren
	$str_no_order .= '	 	(id1 = "'.(isset($arr_ids[0]) ? db_real_escape_string($arr_ids[0]) : '').'")
						AND (id2 = "'.(isset($arr_ids[1]) ? db_real_escape_string($arr_ids[1]) : '').'")
						AND (id3 = "'.(isset($arr_ids[2]) ? db_real_escape_string($arr_ids[2]) : '').'")
					)';
	// END Reihenfolge egal

	$sql = 'SELECT * FROM items_combos WHERE '.($type > 0 ? 'type='.$type.' AND ': '').'
				(
					( id1 = "'.db_real_escape_string(stripslashes($id1)).'"
						'.(!empty($id1) ? 'OR id1="*"' : '').' )
				AND ( id2 = "'.db_real_escape_string(stripslashes($id2)).'"
						'.(!empty($id2) ? 'OR id2="*"' : '').' )
				AND ( id3 = "'.db_real_escape_string(stripslashes($id3)).'"
						'.(!empty($id3) ? 'OR id3="*"' : '').' )
				) '.$str_no_order;
	$res = db_query($sql);

	if(db_num_rows($res)) {
		return(db_fetch_assoc($res));
	}
	else {
		return(false);
	}

}

/**
 * Liefert einer bestimmten Zufallszahlenspanne zwischen 1 und 100 zugeordnete Häufigkeiten
 *
 * @return int Häufigkeit von 1 (selten) - 7 (häufig)
 * @author talion
 */
function item_get_chance () {

	$int_percent = e_rand(1,100);

	if($int_percent >= 73) {			// extrem häufig
		$int_chance = 7;
	}
	else if($int_percent >= 51) {		// sehr häufig
		$int_chance = 6;
	}
	else if($int_percent >= 33) {		// häufig
		$int_chance = 5;
	}
	else if($int_percent >= 19) {		// gelegentlich
		$int_chance = 4;
	}
	else if($int_percent >= 9) {		// selten
		$int_chance = 3;
	}
	else if($int_percent >= 3) {		// sehr selten
		$int_chance = 2;
	}
	else if($int_percent >= 1) {		// extrem selten
		$int_chance = 1;
	}

	return ($int_chance);
}

/**
* @author talion
* @desc Ermittelt ein einzelnes Item (sowie dazugehörige Schablone)
* @param string SQL-WHERE-String: Vorsicht bei Spalten, die sowohl in items_tpl als auch items vorhanden sind!
*				Für items kann Alias 'i', für items_tpl 'it' verwendet werden.
* @param bool Schablone mit abrufen ja / nein. Optional, Standard auf true. Muss mit abgerufen werden, wenn nach
				Schablonenwerten im 1. Param gesucht wird!
* @param string SQL-String, welche Felder sollen abgerufen werden. (Optional, Standard auf alle)
* @return mixed Assoz. Array mit Item bzw. false, wenn kein Item gefunden
*/
function item_get ($where, $tpl=true, $what='*')
{
	if(is_numeric($where))
	{
		$where = 'i.id ='.$where;
	}

	$sql = 'SELECT '.$what.' FROM '.ITEMS_TABLE.' i'
			.($tpl ? ' LEFT JOIN items_tpl it USING( tpl_id ) ' : '').'
			WHERE '.$where;

	$sql .= ' LIMIT 1 ';

	$res = db_query($sql);

	if(db_num_rows($res)) {
		return(db_fetch_assoc($res));
	}
	else {
		return(false);
	}

}

/**
* @author talion
* @desc Ermittelt eine Liste von Items (sowie dazugehörige Schablonen)
* @param string SQL-WHERE-String: Vorsicht bei Spalten, die sowohl in items_tpl als auch items vorhanden sind!
*					Für items kann Alias 'i', für items_tpl 'it' verwendet werden.
* @param string Zusätzliche SQL-Bedingungen / Anweisungen (Limit o.ä.) (Optional, Standard keine)
* @param bool Schablone mit abrufen ja / nein. Optional, Standard auf true. Muss mit abgerufen werden, wenn nach
				Schablonenwerten im 1. Param gesucht wird!
* @param string SQL-String, welche Felder sollen abgerufen werden. (Optional, Standard auf alle)
* @param bool Ergebnis als assoziatives Array zurückgeben
* @return mixed SQL-Result
*/
function item_list_get ($where, $extra='', $tpl=true, $what='*',$bool_return_array = false)
{
	if(is_numeric($where))
	{
		$where = 'i.id ='.$where;
	}

	$sql = 'SELECT '.$what.' FROM '.ITEMS_TABLE.' i '
			.($tpl ? ' LEFT JOIN items_tpl it USING( tpl_id ) ' : '').'
			WHERE ';

	$sql .= $where.' ';

	$sql .= $extra;

	if($bool_return_array == false)
	{
		$res = db_query($sql);

		return( $res );
	}
	else
	{
		return db_get_all($sql);
	}

}


/**
* @author talion
* @desc Ermittelt eine Liste von Itemschablonen
* @param string SQL-WHERE-String
* @param string Zusätzliche SQL-Bedingungen / Anweisungen (Limit o.ä.) (Optional, Standard keine)
* @param string SQL-String, welche Felder sollen abgerufen werden. (Optional, Standard auf alle)
* @param bool Ergebnis als assoziatives Array zurückgeben
* @return mixed SQL-Result
*/
function item_tpl_list_get ($where, $extra='', $what='*',$bool_return_array = false) {

	$sql = 'SELECT '.$what.' FROM items_tpl it
			WHERE ';

	$sql .= $where.' ';

	$sql .= $extra;

	if($bool_return_array == false)
	{
		$res = db_query($sql);

		return( $res );
	}
	else
	{
		return db_get_all($sql);
	}

}

/**
* @author talion
* @desc Ruft Liste mit ItemBuffs ab OHNE sie anzuwenden
* @param int Typenwert des Buffs (Newday, fight..) bzw. 0 wenn egal
* @param string CSV-Liste der Buff-IDs
* @return array Buffliste
*/
function item_get_buffs ($type , $buff_ids) {

	global $session;

	$buffs = array();

	// Komma vorne dranklemmen, falls nicht vorhanden
	$buff_ids = (mb_substr($buff_ids,0,1) != ',' && mb_strlen($buff_ids > 0) ? ',' : '') . $buff_ids;

	// BUFF-Liste abrufen
	if(sizeof($buff_ids > 1)) {
		$sql = 'SELECT * FROM items_buffs WHERE '.($type > 0 ? 'type='.$type.' AND ' : '').' id IN (-1'.$buff_ids.')';
		$res = db_query($sql);
	}

	if(db_num_rows($res)) {

		while($b = db_fetch_assoc($res)) {

			$buffs[] = $b;

		}

	}

	return($buffs);

}

/**
* @author talion
* @desc Ruft Liste mit ItemBuffs ab und wendet sie auf Spieler an
* @param int Typenwert des Buffs (Newday, fight..) bzw. 0 wenn egal
* @param mixed CSV-Liste der Buff-IDs ODER Array mit Buffliste
* @param int Optionale Accountid, ansonsten session_user
* @return -
*/
function item_set_buffs ($type , $buff_ids , $acctid=0) {

	global $session;

	$acctid = ( $acctid == 0 ? $session['user']['acctid'] : $acctid );

	// BUFF-Liste abrufen
	if(is_string($buff_ids)) {
		$buffs = item_get_buffs($type,$buff_ids);
	}
	else {
		$buffs = $buff_ids;
	}

	if(is_array($buffs)) {
		foreach($buffs as $b) {

			unset($b['buff_name']);
			unset($b['id']);
			unset($b['type']);

			buff_add($b);

		}
	}

}

/**
* @author talion
* @desc Ermittelt die aktuell in Umlauf befindliche Anzahl von Items
* @param string SQL-WHERE-String: Vorsicht bei Spalten, die sowohl in items_tpl als auch items vorhanden sind!
*					Für items kann Alias 'i', für items_tpl 'it' verwendet werden.
* @param bool Schablone mit abrufen ja / nein. Wenn nach TPL-Werten gesucht werden soll, muss Param true sein!
*				(Optional, Standard false)
* @param bool Sollen alle item_count Werte in die Anzahl-Berechnung mit einbezogen werden?
* @return int Anzahl
*/
function item_count ( $where , $tpl=false, $count_internal = false ) {
	global $session;

	$str_count_extra = $count_internal == true? '+(SELECT SUM(`item_count`)FROM '.ITEMS_TABLE.' WHERE '.$where.') AS a':'';

	$sql = 'SELECT COUNT(*)'.$str_count_extra.' FROM '.ITEMS_TABLE.' i '.($tpl ? ' LEFT JOIN items_tpl it USING(tpl_id) ' : '').'
			 WHERE '.$where;
	$res = db_query($sql);
	$count = db_fetch_row($res);

	return($count[0]);
}

/**
* @author talion
* @desc Fügt ein Item zu Inventar eines Users hinzu, prüft auch auf evtl. Begrenzungen
* @param int Accountid des neuen Besitzers
* @param string ID der Schablone. Wenn leer oder 0, sollte zusätzl. Info über den 3. Param. gegeben werden.
* @param array Assoz. Array (Feldname => Inhalt)
*				Dient zum Überschreiben der Schablonenwerte. Namenskonvention: tpl_...., (tpl_value1 etc.) bis auf deposit
* 				Überschreiben findet mittels array_merge statt, d.h. alle in diesem Array gegebenen Werte überschreiben ihre Pendants
* 				in der Schabloneninfo. Wenn keine gegeben, wird auch nichts überschrieben.
* 				Wenn nur dieser Param gesetzt und Tpl-ID (2. Param) nicht gegeben, wird diese Info allein zum Erstellen des Items genutzt.
* @param bool Wenn true, wird auf versch. Voraussetzungen geprüft (z.B. max. Anzahl)
* @return ID des neu eingefügten Items bzw. false bei Fehler
*/
function item_add ( $acctid, $item, $item_info=array(), $check = true, $check_error_output = true) {
	global $session,$Char;

	if(!$acctid) {
		systemlog('`^Code-Fehler: item_add(): AcctID nicht vorhanden!<br>Adresse: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'],0,$session['user']['acctid']);
		return(false);
	}

	// SchablonenID gegeben
	if( !empty($item) )
	{

		// Schablone abrufen
		$arr_item_tpl = item_get_tpl( ' tpl_id="'.$item.'" ',' tpl_id,tpl_name,tpl_description,tpl_value1,tpl_value2,tpl_hvalue,tpl_hvalue2,tpl_gold,tpl_gems,tpl_special_info,tpl_content as content' );

		// Keine solche Schablone vorhanden
		if(!is_array($arr_item_tpl)) {
			systemlog('`^Code-Fehler: item_add(): Schablone ID '.$item.' nicht vorhanden!<br>Adresse: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'],0,$session['user']['acctid']);
			return (false);
		}

		if(!is_array($item_info)) {
			$item_info = array();
		}

		$item_info = adv_array_merge( $arr_item_tpl, $item_info );
	}

	// Wenn tpl_id in item_info gegeben ist
	if( !empty($item_info['tpl_id']) )
	{

		$count = null;

		// Prüfungen
		if ( $check )
		{
			// Wenn max. Anzahl gegeben, darauf prüfen
			if( $item_info ['maxcount'] > 0 )
			{
				//($count === null) ? item_count( 'tpl_id="'.$item_info['tpl_id'].'"' ) : $count;
				$count = item_count( 'tpl_id="'.$item_info['tpl_id'].'"' );

				if( $item_info ['maxcount'] <= $count ) {
					return(false);
				}

			}
			// Wenn max. Anzahl pro User gegeben, darauf prüfen
			if( $item_info ['maxcount_per_user'] > 0 )
			{
				$count = item_count( 'tpl_id="'.$item_info['tpl_id'].'" AND owner="'.$acctid.'"');
				if( $item_info ['maxcount_per_user'] <= $count ) {
					if ($check_error_output){
						output(jslib_messagebox('Systemmeldung','Du kannst kein(e) weitere(s) '.strip_appoencode($item_info['tpl_name']).' in dein Inventar aufnehmen!'),true);
					}
					return(false);
				}
			}

		}

		//Soll ein Item mit einer alten ID wieder hergestellt werden?
		$int_old_item_id = 0;
		if(isset($item_info['original_id']) && is_numeric($item_info['original_id']))
		{
			//Soll ein Item mit einer alten ID wieder hergestellt werden?
			//Wenn ja darf es natürlich noch nicht existieren.
			if(item_count('id='.$item_info['original_id']) == 0)
			{
				$int_old_item_id = $item_info['original_id'];
			}
		}

		$sql = 'INSERT INTO '.ITEMS_TABLE.'
					(
						'.($int_old_item_id > 0? 'id, ' : '').
						'name, tpl_id, owner, description, value1, value2, hvalue,
						hvalue2, gold, gems, weight, item_count, deposit1, deposit2, special_info, content, hide
					)
					VALUES
					(
						'.($int_old_item_id > 0? $int_old_item_id.', ' : '').'
						"'.addstripslashes($item_info['tpl_name']).'",
						"'.$item_info['tpl_id'].'",
						"'.$acctid.'",
						"'.addstripslashes($item_info['tpl_description']).'",
						"'.$item_info['tpl_value1'].'",
						"'.$item_info['tpl_value2'].'",
						"'.$item_info['tpl_hvalue'].'",
						"'.$item_info['tpl_hvalue2'].'",
						"'.(empty($item_info['tpl_gold'])?0:$item_info['tpl_gold']).'",
						"'.(empty($item_info['tpl_gems'])?0:$item_info['tpl_gems']).'",
						"'.(empty($item_info['tpl_weight'])?0:$item_info['tpl_weight']).'",
						"'.(empty($item_info['item_count'])?0:$item_info['item_count']).'",
						"'.(empty($item_info['deposit1'])?0:$item_info['deposit1']).'",
						"'.(empty($item_info['deposit2'])?0:$item_info['deposit2']).'",
						"'.addstripslashes($item_info['tpl_special_info']).'",
						"'.addstripslashes(is_array($item_info['content'])?utf8_serialize($item_info['content']):$item_info['content']).'",
						"'.(empty($item_info['hide'])?0:$item_info['hide']).'"
					)';


		db_query($sql);
		$db_insert_id = false;
		if(db_affected_rows()) {
			$db_insert_id = db_insert_id();
		}

		return($db_insert_id);
	}

	systemlog('`^Code-Fehler: item_add(): Keine tpl_id in item_info gegeben!<br>Adresse: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'],0,$session['user']['acctid']);
	return(false);

}

/**
* @author salator
* @desc Überschreibt bestehendes Item mit angegebenem Schablonenwert und legt es dem User in den Beutel
* @param string Austruck für Suchbedingung
* @param string ID der Schablone
* @param array optionaler Parameter zum Überschreiben der Schablonenwerte wie bei item_add
* @return false bei Fehler
*/
function item_overwrite ( $sql_where, $tpl_id, $arr_data=array() )
{
	global $session;

	$res = item_tpl_list_get( 'tpl_id="'.$tpl_id.'" LIMIT 1' );
	if( db_num_rows($res) )
	{
		$item_tpl = db_fetch_assoc($res);
		$item_new=array('name' => $item_tpl['tpl_name'],
		'owner' => $session['user']['acctid'],
		'value1' => $item_tpl['tpl_value1'],
		'value2' => $item_tpl['tpl_value2'],
		'gold' => $item_tpl['tpl_gold'],
		'gems' => $item_tpl['tpl_gems'],
		'description' => $item_tpl['tpl_description'],
		'hvalue' => $item_tpl['tpl_hvalue'],
		'hvalue2' => $item_tpl['tpl_hvalue2'],
		'deposit1' => 0,
		'deposit2' => 0,
		'tpl_id' => $item_tpl['tpl_id'],
		'special_info' => $item_tpl['tpl_special_info'],
		'weight' => $item_tpl['tpl_weight'],
		'item_count' => 0,
		);
		if (!empty($arr_data))
		{
			$item_new=adv_array_merge($item_new, $arr_data);
		}
		if(item_set($sql_where,$item_new)) return true;
		return false;
	}
	systemlog('`^Code-Fehler: item_overwrite(): Keine passende tpl_id gefunden! ('.$tpl_id.')<br>Adresse: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'],0,$session['user']['acctid']);
	return false;
}

/**
* @author talion
* @desc Verändert die Werte eines bestehenden Items
* @param string SQL-WHERE Konditionen (Achtung: nur Felder der ITEMS-Table verfügbar!) oder int für Item ID
* @param array Assoz. Array (Feldname => Inhalt)
*				 Enthält zu verändernde Werte.
* @param bool Wenn true, wird auf versch. Voraussetzungen geprüft (z.B. max. Anzahl)
* @param int  LIMIT der Änderungen std: 100
* @return true bei Erfolg, sonst false
*/
function item_set ( $item, $item_info, $check=true, $limit=100 ) {
	global $session;

	if( $item == '' )
	{
		return(false);
	}

	if(is_numeric($item))
	{
		$item = 'id='.$item;
	}

	if( $check )
	{
		// Wenn max. Anzahl gegeben, darauf prüfen
		/*if( $item_info ['maxcount'] > 0 && $item_info ['tpl_id'] )
		{

			$count = item_count( ' tpl_id="'.$item_info['tpl_id'].'"' );

			if( $item_info ['maxcount'] <= $count ) { return(false); }

		}*/

	}

	$sql = 'UPDATE '.ITEMS_TABLE.' SET '.
				( isset($item_info['name']) ? 'name="'.addstripslashes($item_info['name']).'",' : '').
				( isset($item_info['tpl_id']) ? 'tpl_id="'.$item_info['tpl_id'].'",' : '').
				( isset($item_info['owner']) ? 'owner="'.$item_info['owner'].'",' : '').
				( isset($item_info['description']) ? 'description="'.addstripslashes($item_info['description']).'",' : '').
				( isset($item_info['value1']) ? 'value1="'.$item_info['value1'].'",' : '').
				( isset($item_info['value2']) ? 'value2="'.$item_info['value2'].'",' : '').
				( isset($item_info['hvalue']) ? 'hvalue="'.$item_info['hvalue'].'",' : '').
				( isset($item_info['hvalue2']) ? 'hvalue2="'.$item_info['hvalue2'].'",' : '').
				( isset($item_info['gold']) ? 'gold="'.$item_info['gold'].'",' : '').
				( isset($item_info['gems']) ? 'gems="'.$item_info['gems'].'",' : '').
				( isset($item_info['weight']) ? 'weight="'.$item_info['weight'].'",' : '').
				( isset($item_info['item_count']) ? 'item_count="'.$item_info['item_count'].'",' : '').
				( isset($item_info['deposit1']) ? 'deposit1="'.$item_info['deposit1'].'",' : '').
				( isset($item_info['deposit2']) ? 'deposit2="'.$item_info['deposit2'].'",' : '').
				( isset($item_info['sort_order']) ? 'sort_order="'.$item_info['sort_order'].'",' : '').
				( isset($item_info['hide']) ? 'hide="'.$item_info['hide'].'",' : '').
				( isset($item_info['content']) ? 'content="'.addstripslashes(is_array($item_info['content'])?utf8_serialize($item_info['content']):$item_info['content']).'",' : '').
				( isset($item_info['special_info']) ? 'special_info="'.addstripslashes($item_info['special_info']).'",' : '').
			' id=id WHERE ' . $item . ($limit ? ' LIMIT '.$limit : '');

	db_query($sql);

	$affected_rows=db_affected_rows();

	if($affected_rows>=50) {
		systemlog('`4Warnung: `&item_set(): '.$affected_rows.' betroffene Items!<br>`^'.$sql.'<br>Adresse: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'],0,$session['user']['acctid']);
	}

	if($affected_rows) {
		return(true);
	}

	return(false);

}

/**
* @author talion
* @desc Erhöht oder verringert Die Anzahl eines Items (item_count Wert)
* @param string SQL-WHERE Konditionen (Achtung: nur Felder der ITEMS-Table verfügbar!) oder int für Item ID
* @param int  Wert der hinzuaddiert oder abgezogen werden muss
* @return true bei Erfolg, sonst false
*/
function item_set_amount ( $item, $amount )
{
	global $Char;

	if( $item == '' )
	{
		return(false);
	}

	if(is_numeric($item))
	{
		$item = 'id='.$item;
	}

	$sql = 'UPDATE '.ITEMS_TABLE.' SET
				item_count= item_count+"'.$amount.'",
				id=id WHERE ' . $item . ' LIMIT 1';

	db_query($sql);

	$affected_rows=db_affected_rows();


	if($affected_rows)
	{
		return(true);
	}

	return(false);

}

/**
* @author salator
* @desc gibt Userinterface zur manuellen Sortierung von Items aus
* @param string SQL-WHERE Konditionen (Achtung: nur Felder der ITEMS-Table verfügbar!)
* @param string weitere SQL-Bedingungen (optional, Standard: Sortier-Vorgabe)
* @param string Feldname in dem die Sortierung gespeichert wird (optional, Standard: sort_order)
* @return fertiges html-Formular (ohne Zurück-Link!)
*/
function item_set_sort_order ($sql_where, $sql_extra=false, $str_fieldname='sort_order', $ausblendbar=false)
{
	global $session, $_POST;
	if($sql_extra===false)
	{
		$sql_extra='ORDER BY sort_order DESC, name DESC, id ASC';
	}
	$filename=$_SERVER['REQUEST_URI'];
	$filename=mb_substr($filename,mb_strrpos($filename,'/')+1);

	if(is_array($_POST['sortorder']))
	{
		foreach($_POST['sortorder'] as $item_id => $sort_order)
		{
			
			$ausblen = isset($_POST['ausblenden'][$item_id]) ? 1 : 0;
			
			if($_POST['sortorig'][$item_id]!=$sort_order || ( $ausblendbar && $_POST['ausblendenorig'][$item_id] != $ausblen) )
			{
				
				$sort_order=min(intval($sort_order),255);
				$sort_order=max(0,$sort_order);
				
				$arr_it = array();
				$arr_it[$str_fieldname] = $sort_order;
				$arr_it['hide'] = $ausblen;

				if(item_set('id='.(int)$item_id,$arr_it,true,1))
				{
					$counter++;
				}

			}
		}
		if($counter>0)
		{
			$str_out.='`n`@'.$counter.' Item(s) wurde verändert.`0';
		}
	}

	$arr_items=item_list_get($sql_where,$sql_extra,true,'id,name,description,hide,'.$str_fieldname.' AS sort_order',true);
	if(count($arr_items)>0)
	{
		$str_out.='`0`n<form action="'.$filename.'" method="post">
		<table border="0">
		<tr class="trhead">
		'.( $ausblendbar ? '<th>Ausblenden</th>' : '').'
		<th>Level</th>
		<th>Name</th>
		<th>Beschreibung (gekürzt)</th>
		</tr>';
		foreach ($arr_items as $number => $item)
		{
			$i++;
			$trclass=($i%2?'trdark':'trlight');
			$str_out.='<tr class="'.$trclass.'">
			
			'.( $ausblendbar ? '
			<td>
			<input name="ausblenden['.$item['id'].']" type="checkbox" value="" '.( ($item['hide'] == 1) ? 'checked' : '').' />
			<input type="hidden" name="ausblendenorig['.$item['id'].']" value="'.$item['hide'].'">
			</td>' : '').'
			
			<td>
			<input type="text" name="sortorder['.$item['id'].']" value="'.$item['sort_order'].'" size="3" maxlength="3">
			<input type="hidden" name="sortorig['.$item['id'].']" value="'.$item['sort_order'].'">
			</td>
			<td>'.$item['name'].'</td>
			<td>'.mb_substr(strip_appoencode($item['description'],3),0,100).'</td>
			</tr>';
		}
		$str_out.='</table>
		<input type="submit" value="Speichern" class="button">
		</form>
		(Zulässige Werte sind 0-255, höhere Werte stehen oben)`n`n';
		addnav('',$filename);
	}
	else
	{
		$str_out.='Diese Auswahl enthält keine Items`n`n';
	}
	return ($str_out);
}



/**
 * Konstanten der Inventaransicht
 */
define('ITEM_INVENT_HEAD_CATS',1);
define('ITEM_INVENT_HEAD_ORDER',2);
define('ITEM_INVENT_HEAD_LOC_PLAYER',4);
define('ITEM_INVENT_HEAD_LOC_OTHER',8);
define('ITEM_INVENT_HEAD_SHOP_SELL',16);
define('ITEM_INVENT_HEAD_SHOP_BUY',32);
define('ITEM_INVENT_HEAD_MULTI',64);
/**
 * Zeige eine Suchbox an
 */
define('ITEM_INVENT_HEAD_SEARCH',128);

/**
 * Zeige an, ob das Item beim DK verfällt
 */
define('ITEM_INVENT_HEAD_EXPIRES',256);

/**
 * Dürfen Items hier gestacked werden
 */
define('ITEM_INVENT_HEAD_NOT_STACKABLE',512);

/**
 * Soll der generierte Output zurückgegeben werden anstatt output direkt zu verwenden?
 */
define('ITEM_INVENT_HEAD_RETURN_OUTPUT',1024);

/**
 * Globale Umgebungsvariablen der Inventaransicht
 */
/*$g_int_header_ops = 0;
$g_str_base_link = '';
$g_str_ret_link = '';
$g_float_gold_r = 1;
$g_float_gems_r = 1;
$g_bool_tpl_only = false;

$g_int_cat = 0;
$g_str_orderby_in = '';
$g_str_order = '';
$g_str_loc = '';*/

/**
 * Setzt globale Variablen für Inventarumgebung. Ermittelt auch durch GPC übergebene Informationen
 * (kategorie usw.)
 *
 * @param int $int_header_ops Per bitweisem ODER verknüpfte Konstanten, die das AUssehen des Inventars bestimmen (s.o.)
 * @param float $float_gold_r Wert, mit dem der Goldwert eines Items im Verkaufmodus multipliziert wird
 * @param float $float_gems_r Wert, mit dem der Gemwert eines Items im Verkaufmodus multipliziert wird
 * @param bool $bool_tpl_only Nur Schablonen abrufen
 */
function item_invent_set_env ($int_header_ops = 0, $float_gold_r = 1, $float_gems_r = 1, $bool_tpl_only = false) {

	global $g_int_header_ops, $g_str_base_link, $g_str_ret_link, $g_float_gold_r, $g_float_gems_r, $g_bool_tpl_only;
	global $g_int_cat,$g_str_orderby_in,$g_str_order,$g_str_loc, $g_str_search, $g_bool_search_in_description,$g_str_search_text;

	$g_int_header_ops = $int_header_ops;

	$g_float_gems_r = $float_gems_r;
	$g_float_gold_r = $float_gold_r;

	$g_bool_tpl_only = $bool_tpl_only;

	// Aktueller Pfad
	$g_str_ret_link = calcreturnpath();

	// Aktueller Pfad ohne Filterinformationen
	$g_str_base_link = utf8_preg_replace('/([?&]cat=[0-9]*)|([?&]orderby=[a-z]*)|([?&]loc=[a-z]*)|([?&]order=[a-z]*)|([?&]page=[0-9]*)/i','',$g_str_ret_link);
	$g_str_base_link = str_replace('?&','',$g_str_base_link);
	$g_str_base_link .= (mb_strpos($g_str_base_link,'?') ? '&' : '?');

	/**
	 * Folgende Informationen sind nur von Bedeutung, wenn auch durch Inventhead-Kontrolle gegeben.
	 * Ansonsten werden hier Default-Werte verwendet.
	 */

	if(($g_int_header_ops & ITEM_INVENT_HEAD_SEARCH) && str_replace('?','',str_replace('&','',$g_str_base_link)) == 'invent.php' )
	{
		$arr_nav_vars = persistent_nav_vars(
			array(
				'orderby',
				'order',
				'loc',
				'inventory_search',
				'search_description'
			),
			!is_null_or_empty($_REQUEST['delete_search'])
		);
	}
	else
	{
		$arr_nav_vars = $_REQUEST;
	}
	// Filterkategorie
	$g_int_cat = (int)$_REQUEST['cat'];
	// Ordnungskriterium
	$g_str_orderby_in = $arr_nav_vars['orderby'];
	// Ordnungsreihenfolge: 0 = ASC, 1 = DESC
	$g_str_order = ($arr_nav_vars['order'] == 'DESC' ? 'DESC' : 'ASC');
	// Ort
	$g_str_loc = $arr_nav_vars['loc'];
	//Suche abgesendet

	//unset($_SESSION['inventory_search']);
	if(isset($arr_nav_vars['inventory_search']))
	{
		$g_str_search_text = mb_strtolower(addstripslashes(trim($arr_nav_vars['inventory_search'])));
		$g_bool_search_in_description = isset($arr_nav_vars['search_description'])?true:false;
		if($g_bool_tpl_only)
		{
			$g_str_search = ' AND (LOWER(it.tpl_name) LIKE "'.str_create_search_string($g_str_search_text).'" '.($g_bool_search_in_description?'OR LOWER(it.tpl_description) LIKE "%'.$g_str_search_text.'%"':'').') ';
		}
		else
		{
			$g_str_search = ' AND (LOWER(i.name) LIKE "'.str_create_search_string($g_str_search_text).'" '.($g_bool_search_in_description?'OR LOWER(i.description) LIKE "%'.$g_str_search_text.'%"':'').') ';
		}
	}
	else
	{
		$g_str_search = '';
	}
}

/**
 * Ruft wichtige Daten für Inventaransicht ab (Count, Items, Kategorien)
 *
 * @param string $str_where SQL-WHERE-String
 * @param int $int_pagecount Anzahl Items / Seite. Wenn 0 (=Standard): Keine Seitenaufteilung
 * @return array Feld 'data': SQL-Result der Items, 'pages': Rückgabearray der page_nav()-Funktion
 */
function item_invent_head ($str_where, $int_pagecount = 0) {
	
	global $g_str_base_link,$g_str_ret_link,$g_int_header_ops,$g_float_gold_r, $g_float_gems_r, $g_bool_tpl_only, $session;
	global $g_int_cat,$g_str_orderby_in,$g_str_order,$g_str_loc,$g_str_search,$g_str_search_text,$g_str_where;
	
	$str_out = '';
	$arr_data = array();
	
	if($session['user']['prefs']['itemsperpage'] > 0) $int_pagecount = $session['user']['prefs']['itemsperpage'];
	
	if($g_bool_tpl_only) {

		switch($g_str_orderby_in) {
			case 'name':
				$str_orderby = ' it.tpl_name '.$g_str_order;
			break;
			case 'gold':
				$str_orderby = ' it.tpl_gold '.$g_str_order;
			break;
			case 'gems':
				$str_orderby = ' it.tpl_gems '.$g_str_order;
			break;
			case 'loosedk':
				$str_orderby = ' it.loose_dragon '.$g_str_order;
			break;
			default:
				$str_orderby = ' ic.class_order DESC, ic.class_name ASC, it.tpl_name ASC';
			break;
		}

		$str_cat_sql = 'SELECT ic.id,ic.class_name
		FROM items_tpl it
		LEFT JOIN items_classes ic ON it.tpl_class=ic.id';
		$str_count_sql = 'SELECT COUNT(*) AS c FROM items_tpl it WHERE '.$str_where.($g_int_cat > 0 ? ' AND it.tpl_class='.$g_int_cat : '');

		$str_data_sql = 'SELECT it.tpl_id, it.tpl_class, it.deposit, it.deposit_private, it.use_hook,
							it.tpl_value1 AS value1, it.tpl_value2 AS value2,
							it.tpl_hvalue AS hvalue, it.tpl_hvalue2 AS hvalue2,
							it.tpl_gold AS gold, it.tpl_gems AS gems,
							it.tpl_name AS name, it.tpl_description AS description,
							it.loose_dragon,
							ic.class_name,ic.class_value1,ic.class_value2,ic.class_hvalue,ic.class_hvalue2,
							ic.class_gold,ic.class_gems,ic.class_special_info
					FROM items_tpl it
					LEFT JOIN items_classes ic ON it.tpl_class=ic.id
					WHERE ';
		$str_data_sql .= ' '.$str_where.$g_str_search.($g_int_cat > 0 ? ' AND it.tpl_class='.$g_int_cat : '');
		$str_data_sql .= ' ORDER BY '.$str_orderby;
	}
	else {

		$str_loc_sql = ' ';
		switch ($g_str_loc) {
			case 'beutel':
				$str_loc_sql = ' AND (deposit1=0 AND deposit2=0) ';
			break;
			case 'haus':
				$str_loc_sql = ' AND (deposit1>0 AND deposit1<1234567 AND deposit2=0) ';
			break;
			case 'privat':
				$str_loc_sql = ' AND (deposit1>0 AND deposit1<1234567 AND deposit2>0) ';
			break;
			case 'gilde':
				$str_loc_sql = ' AND (deposit1=0 AND deposit2=0) ';
			break;
			default:
				$str_loc_sql = '  ';
			break;

		}

		$str_where .= $str_loc_sql;
		//Where Klausel speichern für das stacking!
		$g_str_where = $str_where;

		switch($g_str_orderby_in) {
			case 'name':
				$str_orderby = ' i.name '.$g_str_order;
			break;
			case 'gold':
				$str_orderby = ' i.gold '.$g_str_order;
			break;
			case 'gems':
				$str_orderby = ' i.gems '.$g_str_order;
			break;
			case 'loosedk':
				$str_orderby = ' it.loose_dragon '.$g_str_order;
			break;
			case 'id':
				$str_orderby = ' i.id '.$g_str_order;
			break;
			default:
				$str_orderby = ' ic.class_order DESC, ic.class_name ASC, it.tpl_name ASC';
			break;
		}

		$str_cat_sql = 'SELECT ic.id,ic.class_name
			FROM '.ITEMS_TABLE.' i
			INNER JOIN items_tpl it ON i.tpl_id = it.tpl_id
			LEFT JOIN items_classes ic ON it.tpl_class=ic.id';
		$str_count_sql = 'SELECT COUNT(*) AS c FROM '.ITEMS_TABLE.' i LEFT JOIN items_tpl it USING(tpl_id) WHERE '.$str_where.$g_str_search.' AND i.tpl_id != "" '.($g_int_cat > 0 ? ' AND it.tpl_class='.$g_int_cat : '');

		$str_data_sql = 'SELECT i.*, it.tpl_name, it.tpl_id, it.tpl_class, it.deposit, it.deposit_private, it.use_hook, it.equip, it.throw, it.loose_dragon,
					ic.class_name,ic.class_value1,ic.class_value2,ic.class_hvalue,ic.class_hvalue2,
					ic.class_gold,ic.class_gems,ic.class_special_info
				FROM '.ITEMS_TABLE.' i
				LEFT JOIN items_tpl it ON i.tpl_id = it.tpl_id
				LEFT JOIN items_classes ic ON it.tpl_class=ic.id
				WHERE i.tpl_id!="" AND ';
		$str_data_sql .= ' '.$str_where.$g_str_search.($g_int_cat > 0 ? ' AND it.tpl_class='.$g_int_cat : '');
		$str_data_sql .= ' ORDER BY '.$str_orderby;

	}

	// Ortauflistung
	if(!$g_bool_tpl_only && ($g_int_header_ops & ITEM_INVENT_HEAD_LOC_OTHER || $g_int_header_ops & ITEM_INVENT_HEAD_LOC_PLAYER)) {

		addnav('Orte');
		addnav(( (empty($g_str_loc) || $g_str_loc == 'alle') ? '`^':'').'Alle',$g_str_base_link.'loc=alle');
		if($g_int_header_ops & ITEM_INVENT_HEAD_LOC_PLAYER) {
			addnav(($g_str_loc == 'beutel' ? '`^':'').'Beutel',$g_str_base_link.'loc=beutel');
			addnav(($g_str_loc == 'haus' ? '`^':'').'Häuser',$g_str_base_link.'loc=haus');
			addnav(($g_str_loc == 'privat' ? '`^':'').'Gemächer',$g_str_base_link.'loc=privat');
		}
		if($g_int_header_ops & ITEM_INVENT_HEAD_LOC_OTHER) {
			addnav(($g_str_loc == 'gilde' ? '`^':'').'Gilde',$g_str_base_link.'loc=gilde');
		}

	}

	// Kategorieauflistung
	if($g_int_header_ops & ITEM_INVENT_HEAD_CATS) {

		$str_cat_sql .= ' WHERE '.$str_where;
		$str_cat_sql .= ' GROUP BY it.tpl_class ORDER BY ic.class_order DESC, ic.class_name ASC';
		$res = db_query($str_cat_sql);

		if($g_int_cat != 0 || db_num_rows($res) > 0) {

			addnav('Kategorien');
			addnav( ($g_int_cat==0 ? '`^' : '').'Alle',$g_str_base_link.'cat=0&loc='.$g_str_loc);

			while($c = db_fetch_assoc($res)) {

				if($c['id'] == $int_cat) {
					$str_out .= '`c`&Kategorie: '.$c['class_name'].'`c`n';
				}

				addnav(($c['id'] == $g_int_cat ? '`^' : '').$c['class_name'],$g_str_base_link.'cat='.$c['id'].'&page=1&loc='.$g_str_loc);

			}
		}
	}
	// END Kategorieauflistung

	// Seiten-Navi
	if($int_pagecount > 0)
	{
		$arr_page_res = page_nav($g_str_base_link.'orderby='.$g_str_orderby_in.'&order='.$g_str_order.'&cat='.$g_int_cat.'&loc='.$g_str_loc,$str_count_sql,$int_pagecount,'Seiten','Seite',false);

		// Seitendaten speichern
		$arr_data['pages'] = $arr_page_res;
	}

	// Daten abrufen + speichern
	$arr_data['data'] = db_query($str_data_sql .( ($int_pagecount > 0) ? ' LIMIT '.$arr_page_res['limit'] : '' ).'');

	// Ordnen nach
	if($g_int_header_ops & ITEM_INVENT_HEAD_ORDER) {
		//$g_str_order = 'DESC'; //das macht hier kein sinn und ist ein bug
		$str_out .= '`c`n`&Ordnen nach:
			<select name="orderby" id="items_order_by" onchange="window.location.href=getElementById(\'items_order_by\')[getElementById(\'items_order_by\').selectedIndex].value">
				<option value="'.$g_str_base_link.'orderby=katg&order='.$g_str_order.'&cat='.$g_int_cat.'&loc='.$g_str_loc.'" '.((empty($g_str_orderby_in)|| $g_str_orderby_in=='katg') ? 'selected' : '').'>Kategorien</option>
				<option value="'.$g_str_base_link.'orderby=name&order='.$g_str_order.'&cat='.$g_int_cat.'&loc='.$g_str_loc.'" '.($g_str_orderby_in == 'name' ? 'selected' : '').'>Name</option>
				<option value="'.$g_str_base_link.'orderby=gold&order='.$g_str_order.'&cat='.$g_int_cat.'&loc='.$g_str_loc.'" '.($g_str_orderby_in == 'gold' ? 'selected' : '').'>Goldwert</option>
				<option value="'.$g_str_base_link.'orderby=gems&order='.$g_str_order.'&cat='.$g_int_cat.'&loc='.$g_str_loc.'" '.($g_str_orderby_in == 'gems' ? 'selected' : '').'>Edelsteinwert</option>
				<option value="'.$g_str_base_link.'orderby=loosedk&order='.$g_str_order.'&cat='.$g_int_cat.'&loc='.$g_str_loc.'" '.($g_str_orderby_in == 'loosedk' ? 'selected' : '').'>Verlust bei DK</option>
				<option value="'.$g_str_base_link.'orderby=id&order='.$g_str_order.'&cat='.$g_int_cat.'&loc='.$g_str_loc.'" '.($g_str_orderby_in == 'id' ? 'selected' : '').'>Erhalt (Datum)</option>
			</select>
			Sortiere :
			<select name="order" id="items_order" onchange="window.location.href=getElementById(\'items_order\')[getElementById(\'items_order\').selectedIndex].value">
				<option value="'.$g_str_base_link.'orderby='.$g_str_orderby_in.'&order=ASC&cat='.$g_int_cat.'&loc='.$g_str_loc.'" '.($g_str_order == 'ASC' ? 'selected' : '').'>Aufsteigend</option>
				<option value="'.$g_str_base_link.'orderby='.$g_str_orderby_in.'&order=DESC&cat='.$g_int_cat.'&loc='.$g_str_loc.'" '.($g_str_order == 'DESC' ? 'selected' : '').'>Absteigend</option>
			</select>
			`0`n`c';
		addpregnav('/'.str_replace('?','\?',$g_str_base_link).'orderby=(katg|loosedk|name|gold|gems|id)?(&order=(ASC|DESC)?)?&cat='.$g_int_cat.'&loc='.$g_str_loc.'/');
	}
	// END Ordnen nach

	// Wenn Ausgabe vorhanden: Raus damit!
	// Soll die Ausgabe aber zurückgeliefert werden wird diese im Datenarray zwischengespeichert
	if(!empty($str_out) && !($g_int_header_ops & ITEM_INVENT_HEAD_RETURN_OUTPUT)) {
		output($str_out,true);
	}
	else
	{
		$arr_data['output'] = $str_out;
	}

	// Datenarray zurückgeben
	return ($arr_data);

}

/**
 * @TODO: Kommentare ausführlicher, Doku
 */

/**
 * Zeigt Inventardaten an.
 *
 * @param array Der von der Funktion item_invent_head zurückgegebene Array.
 * @param string Meldung, wenn keine Items vorhanden
 * @param array Aktionslinks, die herkömmliche ersetzen
 * @param string Callback-Funktion, die bei jedem Item aufgerufen wird
 */
function item_invent_show_data ($arr_data, $str_nothing_msg = '', $arr_options = array(), $str_callback = '', $dontstack = false) {

	global $g_str_base_link,$g_int_header_ops,$g_str_ret_link,$g_float_gold_r, $g_float_gems_r, $g_bool_tpl_only;
	global $g_int_cat,$g_str_orderby_in,$g_str_order, $g_str_search,$g_bool_search_in_description,$g_str_search_text, $g_str_where;
	global $session,$SCRIPT_NAME, $Char;

	$player = user_get_aei('job');
	$p_job = $player['job'];

	// Inhalte
	$res = $arr_data['data'];

	$int_count = db_num_rows($res);
	$int_last_cat = 0;
	$str_style = 'trlight';
	$str_extra = '';

	if($g_int_header_ops & ITEM_INVENT_HEAD_SEARCH)
	{
		$str_search_form = 	form_header($g_str_ret_link).'
			<input type="text" name="inventory_search" size="50" value="'.mb_strtolower(addstripslashes(trim($g_str_search_text))).'" />
			<input type="checkbox" name="search_description" '.($g_bool_search_in_description?'checked':'').' />&nbsp;'.jslib_hint('`b[?]`b','Beschreibung eines Items auch durchsuchen').'
			<input type="submit" name="search" value="Suchen!">
			'.( (str_replace('?','',str_replace('&','',$g_str_base_link)) != 'invent.php') ? '' : '<input type="submit" name="delete_search" value="Suchvorgabe löschen!">').'
			</form>';
	}
	else
	{
		$str_search_form = '';
	}

	if($int_count == 0) {
		output('`c'.$str_search_form.'`n'.$str_nothing_msg.'`c');
		return;
	}

	$str_out = '`c<table width="70%">';

	if(sizeof($arr_options) > 0) {

		$str_options_link = $g_str_ret_link;
		$str_options_link .= (mb_strpos($str_options_link,'?') ? '&' : '?');

	}

	if($g_int_header_ops & ITEM_INVENT_HEAD_SEARCH)
	{
		$str_out .= '
		<tr>
			<td colspan="2" align="center">
			'.$str_search_form.'
			</td>
		</tr>';
	}

	// Multi-Selects
	if($g_int_header_ops & ITEM_INVENT_HEAD_MULTI) {

		// Alle markieren
		$str_out .= '<tr>
					<td align="left" >
					<input type="button" value="Alle markieren" onclick="for(i=0;i<document.getElementsByName(\'ids[]\').length;i++){ document.getElementsByName(\'ids[]\')[i].checked=true;}">
					<input type="button" value="Auswahl aufheben" onclick="for(i=0;i<document.getElementsByName(\'ids[]\').length;i++){ document.getElementsByName(\'ids[]\')[i].checked=false;}">
					</td>
					<td align="right" nowrap="nowrap">
					 ';

		// Im Handelsmodus..
		if($g_int_header_ops & ITEM_INVENT_HEAD_SHOP_BUY || $g_int_header_ops & ITEM_INVENT_HEAD_SHOP_SELL) {

			if($g_int_header_ops & ITEM_INVENT_HEAD_SHOP_BUY) {
				$str_formlnk = $SCRIPT_NAME.'?op=buy_do&gold_r='.$g_float_gold_r.'&gems_r='.$g_float_gems_r;
				$str_out .= '<form method="POST" action="'.$str_formlnk.'">';
				$str_out .= '<select name="op" onchange="this.form.submit()">
							<option value="">Bitte wählen:</option>
							<option value="sell_do">Kaufen!</option>
							</select>';
			}
			else {
				$str_formlnk = $SCRIPT_NAME.'?op=sell_do&gold_r='.$g_float_gold_r.'&gems_r='.$g_float_gems_r;
				$str_out .= '<form method="POST" action="'.$str_formlnk.'">';
				$str_out .= '<select name="op" onchange="this.form.submit()">
							<option value="">Bitte wählen:</option>
							<option value="sell_do">Verkaufen!</option>
							</select>';
			}
			addnav('',$str_formlnk);
		}
		// Sonst:
		else {

			// Wenn eigene Ops
			if(sizeof($arr_options) > 0) {
				$str_formlnk = $str_options_link;
				$str_out .= '<form method="POST" action="'.$str_formlnk.'">';
				$str_out .= 'Ausgewählte:&nbsp;<select name="op" onchange="this.form.submit()">
								<option value="">Bitte wählen:</option>';

				foreach ($arr_options as $str_txt => $str_op) {
					$str_out .= '<option value="'.$str_op.'">'.$str_txt.'</option>';
				}
				$str_out .= '
								</select>
							';
			}
			else {
				$str_formlnk = 'invhandler.php?ret='.urlencode($g_str_ret_link).'';
				$str_out .= '<form method="POST" action="'.$str_formlnk.'">';
				$str_out .= 'Ausgewählte:&nbsp;<select name="op" onchange="this.form.submit()">
								<option value="">Bitte wählen:</option>
								<option value="wegw">Wegwerfen!</option>
								<option value="einl">Einlagern!</option>
								<option value="ausl">Auslagern!</option>
								</select>
							';
			}

			addnav('',$str_formlnk);

		}

		$str_out .= '</td></tr>';

	}
	// END Multi-Selects

	$int_counter = 0;
	$bool_tr_open = false;

	//Speichert den Array of items
	$arr_items = array();
	//Workaround für den Sortierungsfehler
	$int_count = 0;
	while ($i = db_fetch_assoc($res))
	{
		$str_key = utf8_preg_replace('/\s/','',(mb_strtolower(strip_appoencode($i['name'],3))));
		$i['name_sort'] = $str_key;

		$arr_items[$str_key.$int_count] = $i;
		$int_count++;
	}

	//Sortieren nach Name wenn gewünscht.
	if($g_str_orderby_in == 'name')
	{
		ksort($arr_items);
	}

	//Liste der Items laden die dem user gehören und die gestacked werden dürfen
	if($g_int_header_ops & ITEM_INVENT_HEAD_NOT_STACKABLE)
	{
		$arr_stackable_items_keys = array();
	}
	else
	{
		if(is_null_or_empty($g_str_where))
		{
			$g_str_where = 'owner='.$Char->acctid.' AND i.tpl_id!="" AND showinvent=1';
		}
		$arr_stackable_items = db_get_all('

				SELECT COUNT(*) AS item_count, it.tpl_name, it.tpl_id, it.tpl_stackable
				FROM items i USE INDEX (owner)
				LEFT JOIN items_tpl it USING (tpl_id)
				WHERE i.tpl_id!="" AND '.$g_str_where.'
				GROUP BY i.tpl_id HAVING COUNT(*)>1 AND it.tpl_stackable = 1;

			','tpl_id');
		$arr_stackable_items_keys = array_keys($arr_stackable_items);
	}

	$str_tpl_id = '';
	//$bool_firsttrank = false;
	$itemcounter = 0;
	$itemmax = count($arr_items);
	
	//Items darstellen
	foreach($arr_items as $i) {
		$itemcounter++;
		$str_handler_link = 'invhandler.php?ret='.urlencode($g_str_ret_link).'&';

		$str_handler_link .= 'id='.$i['id'].'&';

		if($int_last_cat != $i['tpl_class']) {

			//addnav($i['class_name'],$base_link.'cat='.$i['tpl_class']);

			$int_last_cat = $i['tpl_class'];

			$str_out .= '<tr><td colspan="2">&nbsp;</td></tr>
						<tr class="trhead"><td colspan="2">`b'.$i['class_name'].'`b</td></tr>';

		}

		$int_counter++;

		// Callback
		if(!empty($str_callback)) {
			$str_out .= $str_callback($i);
			continue;
		}


		//Stackable Eigenschaften ausloten
		$bool_open_item_plumi = false;
		$bool_add_to_item_plumi = false;
		
		$dd = $session['user']['prefs']['dontstack'] ? true : false;
		
		if(!$dontstack && !$dd){
			//Wenn das aktuelle item eine andere tpl id hat als das alte
			if( ($str_tpl_id != $i['tpl_id'] ) ) //&& $i['name'] != 'Unbekannter Trank') || ( $i['name'] == 'Unbekannter Trank' && !$bool_firsttrank) )
			{
				if(in_array($i['tpl_id'],$arr_stackable_items_keys) ) //|| $i['name'] == 'Unbekannter Trank')
				{
					$bool_open_item_plumi = true;
					$bool_add_to_item_plumi = true;
					//if($i['name'] == 'Unbekannter Trank')$bool_firsttrank = true;
				}
			}
			//Das aktuelle item hat die gleiche ID wie das vorhergehende und darf gestackt werden und ein plumi ist schon offen
			elseif ( ($str_tpl_id == $i['tpl_id'] && in_array($i['tpl_id'],$arr_stackable_items_keys) ) ) // || ( $i['name'] == 'Unbekannter Trank' && $bool_firsttrank ) )
			{
				$bool_add_to_item_plumi = true;
			}
		}
		$str_tpl_id = $i['tpl_id'];

		if($g_int_header_ops & ITEM_INVENT_HEAD_MULTI) {
            $temp_id = JS::cleanID($str_tpl_id);
			$str_plumi_checkbox = '<input id="plumi_'.$temp_id.'" type="checkbox">&nbsp;'
                .JS::event('#plumi_'.$temp_id,'mouseup','if(document.getElementById(\'plumi_'.$temp_id.'\').checked == false)
			{ for(i=0;i<document.getElementsByName(\'ids[]\').length;i++){if(document.getElementsByName(\'ids[]\')[i].id==\''.$temp_id.'\')
			{document.getElementsByName(\'ids[]\')[i].checked=true;}}} if(document.getElementById(\'plumi_'.$temp_id.'\').checked == true)
			{ for(i=0;i<document.getElementsByName(\'ids[]\').length;i++){if(document.getElementsByName(\'ids[]\')[i].id==\''.$temp_id.'\')
			{document.getElementsByName(\'ids[]\')[i].checked=false;}}}');
		}
		else
		{
			$str_plumi_checkbox = '';
		}

		if($bool_open_item_plumi)
		{
			//if($i['name'] == 'Unbekannter Trank'){
			//	$arr_stackable_items[$str_tpl_id]['item_count'] = item_count(" owner = '".(int)$session['user']['acctid']."' AND name='Unbekannter Trank' ");	
			//}
			$str_out .= '<tr class="trhead"><td colspan=2>'.$str_plumi_checkbox.plu_mi($str_tpl_id,0,false).' '.strip_appoencode($i['tpl_name']).' ('.$arr_stackable_items[$str_tpl_id]['item_count'].' Stück insgesamt auf allen Seiten)</td></tr>';
		}

		$str_style = ($str_style == 'trlight' ? 'trdark' : 'trlight');
		$str_trplumi = ($bool_add_to_item_plumi?' id="'.plu_mi_unique_id($str_tpl_id).'" style="display:none; "':'');
		$str_css_plumi = ($bool_add_to_item_plumi?' style="padding-left:20px;"':'');


		$str_out .= '<tr class="'.$str_style.'" '.$str_trplumi.'>';
		$bool_tr_open = true;

		if($g_int_header_ops & ITEM_INVENT_HEAD_MULTI) {
			$str_extra = '<input type="checkbox" name="ids[]" id="'.$i['tpl_id'].'" value="'.($g_bool_tpl_only ? $i['tpl_id'] : $i['id']).'"> ';
		}

		$str_out .= '<td valign="top" align="left" width="50%" '.$str_css_plumi.'>`&'.$str_extra.'`b'.$i['name'].'`b'.(($i['item_count'] > 1 ) ? ' `&('.$i['item_count'].'x)' : '');
		$str_out .= '<td align="right">`q';

		if($g_int_header_ops & ITEM_INVENT_HEAD_EXPIRES)
		{
			if($i['deposit1'] == 0 && $i['deposit2'] == 0)
			{
				$str_out .= $i['loose_dragon'] > 0 ?'`i(Verlust beim DK)`i - ':'';
			}
			else
			{
				$str_out .= $i['loose_dragon'] == 2 ?'`i(Verlust beim DK)`i - ':'';
			}
		}

		if( ($g_int_header_ops & ITEM_INVENT_HEAD_SHOP_BUY) && $i['owner'] != $session['user']['acctid'] ) {
			$str_out .= 'Vor deinen Augen';
		}
		else {
			if($i['deposit1'] == ITEM_LOC_EQUIPPED) {
				$str_out .= 'Angelegt';
			}
			else if($i['owner'] == ITEM_OWNER_GUILD) {
				if($i['deposit2'] == ITEM_LOC_GUILDHALL) {
					$str_out .= 'Gildenhalle';
				}
				else if($i['deposit2'] == ITEM_LOC_GUILDEXT) {
					$str_out .= 'Gildenräume';
				}
				else {
					$str_out .= 'Gewölbe der Gilde';
				}
			}
            else if($i['deposit1'] == 23422342) {
                $ort = db_get("SELECT * FROM rp_worlds_places WHERE id='".intval($i['deposit2'])."' LIMIT 1");
                $name = isset($ort['name']) ?  $ort['name'] : '`iverlassen`i';
                $str_out .= 'RP-Ort: '.$name;
            }
            else if($i['deposit1'] > 0) {
                $str_out .= 'Haus Nr. '.$i['deposit1'];
                if($i['deposit2'] > 0) {
                    $str_out .= ', Privatgemach';
                }
            }
			else {
				$str_out .= 'Im Inventar';
			}
		}

		$str_out .= '</td>';

		$str_trplumi = ($bool_add_to_item_plumi?' id="'.plu_mi_unique_id($str_tpl_id).'" style="display:none;"':'');

		$str_out .= '</tr><tr class="'.$str_style.'" '.$str_trplumi.'><td colspan="2" width="100%" '.$str_css_plumi.'>`&';

		$str_out .= ''.($i['description'] != '' ? ''.$i['description'].'`n`&' : '');
		$str_out .= (!empty($i['special_info'])
					? '`n'.(!empty($i['class_special_info']) ? $i['class_special_info'].': ':'').$i['special_info'].'`0'
					: '');

		if($i['class_value1'] != '') {$str_out .= ' [ '.$i['class_value1'].' : '.$i['value1'].' ] ';}
		if($i['class_value2'] != '') {$str_out .= ' [ '.$i['class_value2'].' : '.$i['value2'].' ] ';}
		if($i['class_hvalue'] != '') {$str_out .= ' [ '.$i['class_hvalue'].' : '.$i['hvalue'].' ] ';}
		if($i['class_hvalue2'] != '') {$str_out .= ' [ '.$i['class_hvalue2'].' : '.$i['hvalue2'].' ]';}
		$str_out .= '`n';

		$int_new_gold = round($i['gold'] * $g_float_gold_r);
		$int_new_gems = round($i['gems'] * $g_float_gems_r);

		// 10%iger Händlerbonus auf den Preis (in der Liste anzeigen)
		if(!empty($i['class_gold'])) {
			if ($p_job==6 && ($g_int_header_ops & ITEM_INVENT_HEAD_SHOP_BUY))
			{
				$int_new_gold*=0.9;
			}
			elseif ($p_job==6 && ($g_int_header_ops & ITEM_INVENT_HEAD_SHOP_SELL))
			{
				$int_new_gold*=1.1;
			}

			$str_out .= $i['class_gold'].' : `^'.number_format($int_new_gold,0,'','.').'`0 '.
				($int_new_gold != $i['gold'] ? '(`6'.number_format($i['gold'],0,'','.').'`0) ' : '');
		}
		if(!empty($i['class_gems'])) {
			$str_out .= $i['class_gems'].' : `7'.number_format($int_new_gems,0,'','.').'`0 '.
				($int_new_gems != $i['gems'] ? '(`e'.number_format($i['gems'],0,'','.').'`0) ' : '');
		}
		if(!empty($i['weight'])) {
			$str_out .= '&nbsp;&nbsp;&nbsp;&nbsp;(`7'.number_format($i['weight']*0.1,1,',','.').' kg`0) ';
		}
		$str_out .= '`n';
		//$out .= '</tr><tr class="style"><td colspan="2">';
		// Mögliche Aktionen

		if(sizeof($arr_options) > 0) {

			foreach($arr_options as $str_txt=>$str_op) {
				$str_link = $str_options_link;

				$str_link .= ($str_op != '' ? 'op='.$str_op.'&' : '');

				$str_link .= ($g_bool_tpl_only ? 'tpl_id='.$i['tpl_id'] : 'id='.$i['id']);

				$str_out .= ' [ '.create_lnk($str_txt,$str_link).' ] ';

			}

		}
		else if( !($g_int_header_ops & ITEM_INVENT_HEAD_SHOP_BUY || $g_int_header_ops & ITEM_INVENT_HEAD_SHOP_SELL) ) {

			if($i['deposit'] > 0 || $i['deposit_private'] > 0) {

				if( ($i['deposit1'] > 0 && $i['deposit1'] != ITEM_LOC_EQUIPPED && $i['deposit1'] != ITEM_LOC_GUILDEXT && $i['deposit1'] != ITEM_LOC_GUILDHALL)
					|| $i['deposit2'] > 0) {

					$str_link = $str_handler_link.'op=ausl';

					$str_out .= ' [ '.create_lnk('Auslagern',$str_link).' ] ';
				}
				elseif($i['deposit1'] == 0) {
					$str_link = $str_handler_link.'op=einl';

					$str_out .= ' [ '.create_lnk('Einlagern',$str_link).' ] ';
				}

			}

			if($i['throw'] && $i['deposit1'] != ITEM_LOC_EQUIPPED) {
				$str_link = $str_handler_link.'op=wegw';

				$str_out .= ' [ '.create_lnk('Wegwerfen',$str_link).' ] ';
			}

			if($i['equip']) {

				if($i['deposit1'] != ITEM_LOC_EQUIPPED) {

					$str_link = $str_handler_link.'op=ausr';

					$str_out .= ' [ '.create_lnk('Ausrüsten',$str_link).' ] ';

				}
				else {

					$str_link = $str_handler_link.'op=abl';

					$str_out .= ' [ '.create_lnk('Ablegen',$str_link).' ] ';

				}
			}

			if($i['use_hook'] != '' && $i['deposit1'] == 0) {
				$str_link = $str_handler_link.'op=use';

				$str_out .= ' [ '.create_lnk('Benutzen',$str_link).' ] ';
			}

		}
		else {	// shop

			if( $i['owner'] != $session['user']['acctid'] &&
				($g_int_header_ops & ITEM_INVENT_HEAD_SHOP_BUY) &&
				$i['deposit1'] != ITEM_LOC_EQUIPPED ) {

				if($session['user']['gold'] >= $int_new_gold && $session['user']['gems'] >= $int_new_gems) {
					$str_link = $SCRIPT_NAME.'?op=buy_do&gold_r='.$g_float_gold_r.'&gems_r='.$g_float_gems_r;

					if($g_bool_tpl_only) {$str_link .= '&tpl_id='.$i['tpl_id'];}
					else {$str_link .= '&id='.$i['id'];}

					$str_out .= ' [ '.create_lnk('Kaufen',$str_link).' ] ';
				}
			}

			if( $i['owner'] == $session['user']['acctid'] &&
				($g_int_header_ops & ITEM_INVENT_HEAD_SHOP_SELL) &&
				$i['deposit1'] != ITEM_LOC_EQUIPPED ) {

				$str_link = $SCRIPT_NAME.'?op=sell_do&gold_r='.$g_float_gold_r.'&gems_r='.$g_float_gems_r;

				$str_link .= '&id='.$i['id'];

				$str_out .= ' [ '.create_lnk('Verkaufen',$str_link).' ] ';
			}

		}	// END shop

		$str_out .= '</td>';

		$str_out .= '</tr>';


	}	// END while

	if($g_int_header_ops & ITEM_INVENT_HEAD_MULTI) {
		$str_out .= '</form>';
	}

	$str_out .= '</table>`c';
	if($g_int_header_ops & ITEM_INVENT_HEAD_RETURN_OUTPUT)
	{
		return $arr_data['output'].$str_out;
	}
	else
	{
		output($str_out,true);
	}


}


/**
* @author talion
* @desc Löscht ein oder mehrere Items.
* @param string SQL-where-String (Nur mit ITEMS-Spalten!)
* @param int LIMIT der Löschung std: 100
* @return true o. false
*/
function item_delete ( $where, $limit=100, $bool_delete_all = false ) {
	global $session;

	if(is_numeric($where))
	{
		$where = 'id='.$where;
	}

	$sql = 'DELETE FROM '.ITEMS_TABLE.' WHERE '.$where.($limit ? ' LIMIT '.$limit : '');

	db_query($sql);

	$rows=db_affected_rows();
	if($rows>=100)
	{
		systemlog('item_delete: '.$rows.' betroffene Items!',0,$session['user']['acctid']);
	}
	if($rows>0)
	{
		return(true);
	}

	return(false);

}

/**
* @author talion
* @desc Nimmt Änderungen an Werten der Spieler-Ausrüstung (z.Zeit Waffe + Rüstung) vor.
*		Wird von item_set_weapon bzw. item_set_armor gewrappt. Bitte diese Funktionen nutzen!
* @param string 'weapon' für Waffe, 'armor' für Rüstung
* @param string Name des Ausrüstungsgegenstands
* @param int 'Fähigkeit' des Gegenstands (Bei Waffen: Angriffswert)
* @param int Goldwert des Gegenstands
* @param int AccountID. Wenn 0, wird Spielerid verwendet
* @return array Altes Item.
*/
function item_change_equipment ($type,$item_name,$item_skill,$item_value,$acctid) {

	global $session;

	$item_old = array();

	$user = false;

	// Spalten / Wertebezeichnungen setzen
	if($type == 'weapon') {
		$e_name = 'weapon';
		$e_skill = 'weapondmg';
		$e_user = 'attack';
		$e_val = 'weaponvalue';
		$e_tpl = 'waffedummy';
	}
	else if($type == 'armor') {
		$e_name = 'armor';
		$e_skill = 'armordef';
		$e_user = 'defence';
		$e_val = 'armorvalue';
		$e_tpl = 'rstdummy';
	}

	// Wenn aktueller User betroffen
	if($acctid == 0 || $acctid == $session['user']['acctid']) {

		$acctid = $session['user']['acctid'];
		$user = true;
		$item_old['name'] = $session['user'][$e_name];
		//$item_old['gold'] = $session['user'][$e_val];
		//$item_old['value1'] = $session['user'][$e_skill];

	}
	else {	// Wenn and. User betroffen

		$sql = 'SELECT '.$e_name.','.$e_skill.','.$e_user.','.$e_val.' FROM accounts WHERE acctid='.$acctid;
		$it = db_fetch_assoc(db_query($sql));
		$item_old['name'] = $it[$e_name];
		$item_old['gold'] = $it[$e_val];
		$item_old['value1'] = $it[$e_skill];
	}

	// Altes Item abrufen
	$item_old = adv_array_merge($item_old,item_get(' name="'.db_real_escape_string($item_old['name']).'" AND owner='.$acctid.' AND deposit1='.ITEM_LOC_EQUIPPED));

	// Wenn gegeben: Verändern
	if($item_old['id'] > 0) {

		$arr_changes = array('deposit1'=>ITEM_LOC_EQUIPPED,'deposit2'=>0,
			'value1'=>($item_skill>-1?$item_skill:$item_old['value1']),
			'gold'=>($item_value>-1?$item_value:$item_old['gold']),
			'name'=>($item_name!=''?$item_name:$item_old['name']),
			);

		item_set(' id='.$item_old['id'], $arr_changes);

	}
	else {	// Sonst: Neu erstellen

		if($item_old['value1'] > 0 && $item_old['gold'] > 0) {

			if($user) {	// Wenn akt. User
				$item_old['name'] = $session['user'][$e_name];
				$item_old['gold'] = $session['user'][$e_value];
				$item_old['value1'] = $session['user'][$e_skill];
			}
			else {	// Sonst: Aus DB holen
				$sql = 'SELECT '.$e_name.' AS name,'.$e_skill.' AS value1,'.$e_value.' AS gold FROM accounts WHERE acctid='.(int)$acctid;
				$res = db_query($sql);
				$item_old = db_fetch_assoc($res);
			}

			$arr_changes = array('deposit'=>ITEM_LOC_EQUIPPED,'deposit2'=>0,
			'tpl_value1'=>($item_skill>-1?$item_skill:$item_old['value1']),
			'tpl_gold'=>($item_value>-1?$item_value:$item_old['gold']),
			'tpl_name'=>($item_name!=''?$item_name:$item_old['name']),
			);

			// ... und hinzufügen
			item_add($session['user']['acctid'],$e_tpl,$arr_changes);
		}

	}

	// Werte bei Spieler setzen
	if($user) {

		if($item_name != '') {$session['user'][$e_name] = $item_name;}
		if($item_value > -1) {$session['user'][$e_val] = $item_value;}
		if($item_skill > -1) {
			$session['user'][$e_user] += $item_skill - $session['user'][$e_skill];
			$session['user'][$e_skill] = $item_skill;
		}

	}
	else {	// Bei Fremdaccount

		$sql = 'UPDATE accounts SET ';

		if($item_name != '') {$sql .= $e_name.' = "'.$item_name.'" ';}
		if($item_value > -1) {$sql .= $e_val.' = "'.$item_value.'" ';}
		if($item_skill > -1) {
			$sql .= $e_user.' = '.$item_skill.' - '.$e_skill.', '.$e_skill.' = '.$item_skill;
		}

		$sql .= ' WHERE acctid='.$acctid;

		db_query($sql);
	}

	return($item_old);

}

/**
* @author talion
* @desc Setzt Spieler-Ausrüstung (z.Zeit Waffe + Rüstung) neu.
*		Wird von item_set_weapon bzw. item_set_armor gewrappt. Bitte diese Funktionen nutzen!
* @param string 'weapon' für Waffe, 'armor' für Rüstung
* @param string Name des Ausrüstungsgegenstands
* @param int 'Fähigkeit' des Gegenstands (Bei Waffen: Angriffswert)
* @param int Goldwert des Gegenstands
* @param int ItemID des zu verwendenden Gegenstands (falls gegeben)
* @param int AccountID. Wenn 0, wird Spielerid verwendet
* @param int Veränderungsmodus: 0 = Standard, 2 = Aktuelle Ausrüstung ersetzen
* @return array Altes Item.
*/
function item_set_equipment ($type,$item_name,$item_skill,$item_value,$item_id,$acctid,$change) {

	global $session;

	// Wenn Equipment nicht nur editiert werden soll
	if($item_name == '' || $item_skill == -1 || $item_value == -1) {return(false);}

	$item_old = array();

	$user = false;

	// Spalten / Wertebezeichnungen setzen
	if($type == 'weapon') {
		$e_name = 'weapon';
		$e_skill = 'weapondmg';
		$e_user = 'attack';
		$e_val = 'weaponvalue';
		$e_tpl = 'waffedummy';
	}
	else if($type == 'armor') {
		$e_name = 'armor';
		$e_skill = 'armordef';
		$e_user = 'defence';
		$e_val = 'armorvalue';
		$e_tpl = 'rstdummy';
	}

	// Wenn aktueller User betroffen
	if($acctid == 0 || $acctid == $session['user']['acctid']) {

		$acctid = $session['user']['acctid'];
		$user = true;
		$item_old['name'] = $session['user'][$e_name];
		$item_old['gold'] = $session['user'][$e_val];
		$item_old['value1'] = $session['user'][$e_skill];

	}
	else {	// Wenn and. User betroffen

		$sql = 'SELECT '.$e_name.','.$e_skill.','.$e_user.','.$e_val.' FROM accounts WHERE acctid='.$acctid;
		$it = db_fetch_assoc(db_query($sql));
		$item_old['name'] = $it[$e_name];
		$item_old['gold'] = $it[$e_val];
		$item_old['value1'] = $it[$e_skill];
	}


	if($change == 2) {	// Komplett ersetzen

		item_delete(' name="'.db_real_escape_string($item_old['name']).'" AND owner='.$acctid.' AND deposit1='.ITEM_LOC_EQUIPPED);

	}
	else {

		// Altes Item abrufen
		$item_result = item_get(' name="'.db_real_escape_string($item_old['name']).'" AND owner='.$acctid.' AND deposit1='.ITEM_LOC_EQUIPPED);
		if (is_array($item_result)) $item_old = adv_array_merge($item_old,$item_result);

		// Wenn gegeben: Verändern
		if($item_old['id'] > 0) {

			$deposit = ($change==0 ? 0 : ITEM_LOC_EQUIPPED);

			item_set(' id='.$item_old['id'], array('deposit1'=>$deposit,'deposit2'=>0,
										'value1'=>$item_old['value1'],'gold'=>$item_old['gold'],
										'name'=>$item_old['name'] ) );

		}
		else {	// Sonst: Neu erstellen

			if($item_old['value1'] > 0 && $item_old['gold'] > 0) {

				if($user) {	// Wenn akt. User
					$item_old['tpl_name'] = $session['user'][$e_name];
					$item_old['tpl_gold'] = $session['user'][$e_value];
					$item_old['tpl_value1'] = $session['user'][$e_skill];
				}
				else {	// Sonst: Aus DB holen
					$sql = 'SELECT '.$e_name.' AS tpl_name,'.$e_skill.' AS tpl_value1,'.$e_value.' AS tpl_gold FROM accounts WHERE acctid='.(int)$acctid;
					$res = db_query($sql);
					$item_old = db_fetch_assoc($res);
				}

				//$item_old['tpl_name'] = $item_old['tpl_name'];

				// ... und hinzufügen
				item_add($session['user']['acctid'],$e_tpl,$item_old);
			}

		}

	}

	// Neues Item setzen, wenn dafür ID gegeben
	if($item_id > 0) {

		item_set(' id='.$item_id, array('deposit1'=>ITEM_LOC_EQUIPPED,'deposit2'=>0,
											'value1'=>$item_skill,'gold'=>$item_value,
											'name'=>$item_name ) );


	}
	// END Items setzen

	// Werte bei Spieler setzen
	if($user) {

		if($item_name != '') {$session['user'][$e_name] = $item_name;}
		if($item_value > -1) {$session['user'][$e_val] = $item_value;}
		if($item_skill > -1) {
			$session['user'][$e_user] += $item_skill - $session['user'][$e_skill];
			$session['user'][$e_skill] = $item_skill;
		}

	}
	else {	// Bei Fremdaccount

		$sql = 'UPDATE accounts SET ';

		if($item_name != '') {$sql .= $e_name.' = "'.$item_name.'" ';}
		if($item_value > -1) {$sql .= $e_val.' = "'.$item_value.'" ';}
		if($item_skill > -1) {
			$sql .= $e_user.' = '.$item_skill.' - '.$e_skill.', '.$e_skill.' = '.$item_skill;
		}

		$sql .= ' WHERE acctid='.$acctid;

		db_query($sql);
	}

	return($item_old);

}

/**
* @author talion
* @desc Setzt Waffe des Spielers und dazugehörige Werte. Wrapper-Funktion für item_set_ + change_equipment
* @param string Name der Waffe
* @param int Angriffswert der Waffe
* @param int Goldwert der Waffe
* @param int ID des Items, das zur Waffe wird (optional)
* @param int AccountID. Wenn 0, wird Spielerid verwendet (Optional)
* @param int Änderungsmodus - 0: Standard, Aktuelle wird ersetzt und in Inventar eingelagert
*								 1: aktuelle wird verändert, 2: aktuelle wird komplett durch neue ersetzt
* @return array Altes Item.
*/
function item_set_weapon ( $item_name='Fäuste' , $item_attack=0 , $item_value=0 , $item_id=0 , $acctid=0 , $change=0 ) {

	$arr_result = array();

	if($change == 1) {
		$arr_result = item_change_equipment('weapon',$item_name,$item_attack,$item_value,$acctid);
	}
	else {
		$arr_result = item_set_equipment('weapon',$item_name,$item_attack,$item_value,$item_id,$acctid,$change);
	}

	return( $arr_result );

}

/**
* @author talion
* @desc Setzt Rüstung des Spielers und dazugehörige Werte. Wrapper-Funktion für item_set_ + change_equipment
* @param string Name der Rüstung
* @param int Defwert der Rüstung
* @param int Goldwert der Rüstung
* @param int ID des Items, das zur Rüstung wird (optional)
* @param int AccountID. Wenn 0, wird Spielerid verwendet (Optional)
* @param int Änderungsmodus - 0: Standard, Aktuelle wird ersetzt und in Inventar eingelagert
*								 1: aktuelle wird verändert, 2: aktuelle wird komplett durch neue ersetzt
* @return array Altes Item.
*/
function item_set_armor ( $item_name='Straßenkleidung' , $item_defence=0 , $item_value=1 , $item_id=0 , $acctid=0 , $change=0 ) {

	$arr_result = array();

	if($change == 1) {
		$arr_result = item_change_equipment('armor',$item_name,$item_defence,$item_value,$acctid);
	}
	else {
		$arr_result = item_set_equipment('armor',$item_name,$item_defence,$item_value,$item_id,$acctid,$change);
	}

	return( $arr_result );

}

/**
 * Ruft Liste bekannter Item-Kombinationen eines bestimmten Users ab
 *
 * @param int $int_acctid AccountID; Optional, wenn 0 = Standard: Aktueller Spieler
 * @param int $int_combotype ITEM_COMBO_X-Konstante; Typ der Kombos, deren Liste abgerufen werden soll. Wenn null = Standard: Ganze Liste
 * @param array $arr_row Eine von drei Varianten:
 * 							- Userdaten: 'combo' => Serialisierte Liste
 * 							- Datenarray: 'combo' => Liste als Array
 * 							- Datenarray: $arr_row == der Liste
 * 						Wenn null = Standard, werden Daten aus AEI-Table abgerufen
 * @return array Assoziat. Liste bekannter Item-Kombinationen nach Muster Kombo-ID => Kenntnisstand
 */
function item_get_combolist ($int_acctid = 0, $int_combotype = null, $arr_row = null)
{
	if(0 == (int)$int_acctid)
	{
		global $session;
		$int_acctid = $session['user']['acctid'];
	}

	// Wenn Daten nicht mit übergeben: Aus AEI abrufen
	if(is_null($arr_row) || !is_array($arr_row))
	{
		$arr_tmp = user_get_aei('combos',$int_acctid);
		$arr_tmp = utf8_unserialize($arr_tmp['combos']);
	}
	else
	{
		// Wenn Kombos in angeg. Array enthalten:
		if(isset($arr_row['combos']))
		{
			// Wenn noch nicht als Array vorliegend
			if(!is_array($arr_row['combos']))
			{
				$arr_tmp = utf8_unserialize($arr_row['combos']);
			}
			else
			{
				$arr_tmp = $arr_row['combos'];
			}
		}
		// Sonst: Annahme: Angeg. Array = Komboliste
		else
		{
			$arr_tmp = $arr_row;
		}
	}

	// Gesamte Liste zurückgeben
	if(is_null($int_combotype))
	{
		return $arr_tmp;
	}

	// Nur Liste angeg. Kombos zurückgeben
	if(isset($arr_tmp[$int_combotype]))
	{
		return $arr_tmp[$int_combotype];
	}

	return array();
}

/**
 * Speichert Liste bekannter Item-Kombinationen eines bestimmten Users wieder in Datenbank
 *
 * @param array $arr_list COMBOS-Array in Form (ITEM_COMBO_X => (Liste bekannter Kombinationen des Typs im Format Combo-ID => Status))
 * @param int $int_acctid AccountID; Optional, wenn 0 = Standard: Aktueller Spieler
 */
function item_set_combolist ($arr_list, $int_acctid = 0)
{
	if(0 == (int)$int_acctid)
	{
		global $session;
		$int_acctid = $session['user']['acctid'];
	}
	$arr_list = array('combos'=>db_real_escape_string(utf8_serialize($arr_list)));
	user_set_aei($arr_list,$int_acctid);
}

?>