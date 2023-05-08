<?php
/**
* user.lib.php: Funktionsbibliothek für Methoden, die zur Modifizierung / Anzeige von Accountdaten benötigt werden
* @author LOGD-Core / Drachenserver-Team
* @version DS-E V/2
*/
require_once(LIB_PATH.'disciples.lib.php');

// Konstantendefs für die activated-Spalte
define('USER_ACTIVATED_STEALTH',42);
define('USER_ACTIVATED_MUTE',200);
define('USER_ACTIVATED_MUTE_AUTO',201);
define('USER_ACTIVATED_VACATION',100);
define('USER_ACTIVATED_SENTNOTICE',11);
define('USER_ACTIVATED_FIRSTINFO',2);
define('USER_ACTIVATED_DEFAULT',1);

// Konstantendefs für die Orte
define('USER_LOC_FIELDS',0);
define('USER_LOC_INN',1);
define('USER_LOC_HOUSE',2);
define('USER_LOC_PRISON',3);

define('USER_LOC_VACATION',99);

// Konstantendefs für restatlocation
define('USER_RESTATLOC_TIMEOUT',16777215);

//Konstantendefs für bits
define('UBIT_SWITCH', -1); //bit umschalten
define('UBIT_ALL', ~0);//maxBit
//Spalte 'conf_bits'
define('UBIT_DISABLE_CACHE',		1); //user umgeht das caching-system
define('UBIT_DISABLE_SYMPVOTE', 	2); //user darf keine sympathiepunkte mehr bekommen
define('UBIT_DISABLE_PVP',			4); //PVP für Userverbieten
define('UBIT_DISABLE_DISCREM',		8); //User darf keinen Knappen mehr abgeben (discremover.php)

//Spalte 'newday_bits' (werden nach nem newday zurückgesetzt)
define('UBIT_NEWDAY_RESET', 		UBIT_ALL);	//wird regulären newday zurückgesetzt
define('UBIT_NEWDAY_RESET_RESURRECTION', 0); //wird bei ner wiedergeburt zurückgesetzt
define('UBIT_WACHE_SPIEL',			1); // Das Spiel mit der Wache am Dorftor
define('UBIT_OLD_SPIRIT_SOOTHE_SOUL',2); //Seele wiederherstellen lassen
define('UBIT_WISDOM_ALCHEMY',		4); // Tempel der Weisen, alchemistische Experimente


define('CHAT_STATUS_INVISIBLE', 	0);
// Flag der biotime-Spalte für gesperrte Bio
define('BIO_LOCKED','9999-12-31 00:00:00');

// Wert der spirits-Spalte für RP-Wiedererweckung
define('RP_RESURRECTION',			-42);

// Wert der pvpflag-Spalte für Immu
define('PVP_IMMU','5013-10-06 00:42:00');

// Erstmal eine Konstante für Wertelimits
define('USER_VAL_LIMT','1000');


define('UL_SHOW_SEARCH',1);
			define('UL_SHOW_RPG',2);
			define('UL_SHOW_DND',4);
			define('UL_SHOW_WAITING',8);
			define('UL_SHOW_INVISIBLE',16);
			define('UL_SHOW_AVAILABLE',32);
			define('UL_SHOW_NOTIME',64);

/**
*@desc Ruft einzelnen Datensatz aus Account-Extra-Info-Tabelle ab
*@param string SQL-konforme (durch Kommata getrennte) Angabe der abzurufenden Felder. Optional.
*@param int Accountid, wenn 0, acctid des Users. Optional.
*@param string Zusätzliche SQL-WHERE Bedingungen. Optional
*@return array Assoziativen Array mit Datensatz
*@author talion
*/
function user_get_aei ($fields='*',$acctid=0,$where='') {

	global $session;

	$acctid = ($acctid == 0 ? $session['user']['acctid'] : $acctid);

	$sql = 'SELECT '.$fields.' FROM account_extra_info WHERE '.($acctid > 0 ? ' acctid='.$acctid : '').($where != '' ? $where : '').' ORDER BY acctid ASC LIMIT 1';

	$res = db_query($sql);

	return( db_fetch_assoc($res) );

}

/**
*@desc Prüft Änderungen der Spielerwerte auf Zulässigkeit bezüglich gegebener Grenzen und Maxima
*@desc Mnemonic: User-change-value ;)
*@param string Feldname des zu ändernden Wertes
*@param int Änderung als Ganzzahl
*@param bool Keinerlei Textausgabe durch die Fkt. Optional
*@param bool Wenn die Gesamtsumme nicht addiert/subtrahiert werden kann wird zumindest bis zu Grenze geändert
*@return Tatsächliche Änderung des Wertes als Ganzzahl; 0 = Fehler
*@author maris
*/
function ucval ($field,$value,$silent=false,$split=true) {

	global $session;

	$acctid = $session['user']['acctid'];

	switch ($field){

		case 'defence':
			$limit = USER_VAL_LIMT;
			$desc_1 = 'Verteidigungspunkte';
			$desc_2 = 'Verteidigungspunkten';
			break;

		case 'attack':
			$limit = USER_VAL_LIMT;
			$desc_1 = 'Angriffspunkte';
			$desc_2 = 'Angriffspunkten';
			break;

		case 'maxhitpoints':
			$limit = USER_VAL_LIMT;
			$desc_1 = 'maximale Lebenspunkte';
			$desc_2 = 'Maximum-Lebenspunkten';
			break;

		case 'gems':
			$limit = USER_VAL_LIMT;
			$desc_1 = 'Edelsteine';
			$desc_2 = 'Edelsteinen';
			break;

		case 'turns':
			$limit = USER_VAL_LIMT;
			$desc_1 = 'Runden';
			$desc_2 = 'Runden';
			break;

		case 'charm':
			$limit = USER_VAL_LIMT;
			$desc_1 = 'Charmepunkte';
			$desc_2 = 'Charmepunkten';
			break;

		case 'castleturns':
			$limit = USER_VAL_LIMT;
			$desc_1 = 'Schlossrunden';
			$desc_2 = 'Schlossrunden';
			break;

		default:
			$limit = USER_VAL_LIMT;
			break;

	}

	if ($limit==0)
	{
		if (!$silent ) output("`4Fehler in der Wertzuweisung!`nSage bitte einem Programmierer bescheid.`0");
		return('0');
        /** @noinspection PhpUnreachableStatementInspection */
        exit;
	}

	if ($value>0)
	{

		$affected = $limit-$session['user'][$field];
		if ($affected >= $value)
		//Alles klar
		{
			$changed_val = $value;
			if (!$silent ) output("`^Du bekommst `@{$changed_val} {$desc_1}`^ dazu!`0");
		}
		elseif ($affected > 0)
		// Grenze nach Addition erreicht
		{
			if ($split)
			// Sollte gesplittet werden?
			{
				$changed_val = $affected;
				if (!$silent ) output("`^Du hast soeben den für dich höchtmöglichen Wert an `@{$desc_2}`^ erreicht und `@{$changed_val}`^ dazu bekommen!`0");
			}
			else
			// Oder nicht?
			{
				if (!$silent ) output("`^Dein Charakter kann derzeit nicht diese Menge an `4{$desc_2}`^ dazu bekommen.`0");
				return('0');
                /** @noinspection PhpUnreachableStatementInspection */
                exit;
			}
		}
		else
		// Da können wir nix mehr tun!
		{
			if (!$silent ) output("`^Dein Charakter kann derzeit nichts mehr an `4{$desc_2}`^ dazu bekommen.`0");
			return('0');
            /** @noinspection PhpUnreachableStatementInspection */
            exit;
		}
	}
	elseif ($value==0)
	{
		return('0');
        /** @noinspection PhpUnreachableStatementInspection */
        exit;
	}
	// Hier später einfügen: Minimum-Limit
	else
	{
		$changed_val=$value;
		if (!$silent ) output("`^Du verlierst `@{$changed_val} {$desc_1}`^!`0");
	}
	$session['user'][$field]+=$changed_val;

	debuglog($desc_1.' um '.$changed_val.' geändert.');
	return($changed_val);
}

/**
*@desc Verändert einzelnen Datensatz aus Account-Extra-Info-Tablle
*@param array Assoziativer Array (feld => Wert) der zu verändernden Daten
*@param int Accountid, wenn 0, acctid des Users. Optional.
*@param where (string) Zusätzliche SQL-WHERE Bedingungen. Optional
*@return int Anzahl der betroffenen Datensätze
*@author talion
*/
function user_set_aei ($changes,$acctid=0,$where='') {

	global $session;

	if(!sizeof($changes)) {return(false);}

	$acctid = ($acctid == 0 ? $session['user']['acctid'] : $acctid);

	$sql = 'UPDATE account_extra_info SET acctid=acctid';

	foreach($changes as $field => $val) {
		
		//Array muss serialisiert werden
		if(is_array($val))
		{
			$val = db_real_escape_string(utf8_serialize($val));
		}
		$sql .= ','.$field.' = "'.$val.'"';

	}

	$sql .= ' WHERE '.($acctid > 0 ? ' acctid='.$acctid : '').($where != '' ? $where : '').' LIMIT 1';

	$res = db_query($sql);

	return( db_affected_rows() );

}

/**
*@desc Ruft einzelnen Datensatz aus Spieler-Statistik ab
*@param string SQL-konforme (durch Kommata getrennte) Angabe der abzurufenden Felder. Optional.
*@param int Accountid, wenn 0 verwende acctid des aktuellen Users. Optional.
*@param string Zusätzliche SQL-WHERE Bedingungen. Optional
*@return array Assoziativen Array mit Datensatz
*@author talion
*/
function user_get_stats ($fields='*',$acctid=0,$where='') {

	global $session;

	$acctid = ($acctid == 0 ? $session['user']['acctid'] : $acctid);

	$sql = 'SELECT '.$fields.' FROM account_stats WHERE '.($acctid > 0 ? ' acctid='.$acctid : '').($where != '' ? $where : '').' ORDER BY acctid ASC LIMIT 1';

	$res = db_query($sql);

	return( db_fetch_assoc($res) );

}

/**
*@desc Verändert Wert in Spieler-Statistik
*@param array Assoziativer Array (feld => Wert) der zu verändernden Daten
*@param int Accountid, wenn 0 verwende acctid des aktuellen Users. Optional.
*@param where (string) Zusätzliche SQL-WHERE Bedingungen. Optional
*@return int Anzahl der betroffenen Datensätze
*@author talion
*/
function user_set_stats ($changes,$acctid=0,$where='') {

	global $session;

	if(!sizeof($changes)) {return(false);}

	$acctid = ($acctid == 0 ? $session['user']['acctid'] : $acctid);

	$sql = 'UPDATE account_stats SET acctid=acctid';

	foreach($changes as $field => $val) {

		$sql .= ','.$field.' = '.$val.'';

	}

	$sql .= ' WHERE '.($acctid > 0 ? ' acctid='.$acctid : '').($where != '' ? $where : '').' LIMIT 1';

	$res = db_query($sql);

	return( db_affected_rows() );

}

/**
 * Lädt Superusergruppen oder einzelne Gruppe; Konvertiert gleichzeitig Rechte in Array-Format
 *
 * @param int Gruppenid (Optional, Standard -1 = alle Gruppen)
 * @return array Assoziativer Array mit Gruppen, Gruppenid als Schlüssel; Einzelne Gruppe, falls ID gegeben; false, falls nicht gefunden
 */
function user_get_sugroups ($int_id=-1) {
	return CCharacter::getSUGroups( $int_id );	
}

/**
 * Validiert Array mit Superuser-Rechten (Achtet auf Abhängigkeiten)
 *
 * @param array Assoz. Array mit Rechten ( RechteID access_control::SU_RIGHT_... => 1 / 0 )
 * @param array Assoz. Array mit zusätzl. Rechten ( RechteID access_control::SU_RIGHT_... => 1 / 0 ), z.B. Rechte der übergeordneten Gruppe. Optional!
 * @return array Assoz. Array mit Rechten, wobei Abhängigkeiten bereinigt wurden
 * @author talion
*/
function user_set_surights ($arr_rights, $arr_add_rights=null) {
	global $session,$access_control;

	$int_dep_right = 0;

	if(!is_array($arr_rights)) {
		return(array());
	}

	foreach ($arr_rights as $int_rid => $int_right) {

		if(!empty($access_control[$int_rid]['dependent'])) {

			$int_dep_right = $access_control[$int_rid]['dependent'];

			if(!$arr_rights[$int_dep_right] && (!is_null($arr_add_rights) && !$arr_add_rights[$int_dep_right])) {

				$arr_rights[$int_rid] = 0;

			}

		}

	}

	return ($arr_rights);
}

/**
*@desc Speichert Accountdaten des aktuellen Users
*@author LOGD-Core, modified by Drachenserver-Team
*/
function saveuser()
{
	global $Char;
	if ( $Char instanceof CCharacter )
	{
		$Char->save();
	}
}

function user_update($arr_values,$int_acctid = -1 )
{
	global $Char;
	$str_sql = 'UPDATE `accounts` SET ';
	
	$int_acctid = $int_acctid == -1 ? $Char->acctid : $int_acctid;

    $fields = db_get("SELECT * FROM accounts WHERE acctid = '".intval($int_acctid)."' LIMIT 1");

	$str_data = '';
	$str_tupel = '';
	$str_where = 'acctid='.$int_acctid;
	$int_count_fields = 0;

	foreach ($arr_values as $str_field => $data)
	{

		if ($str_field == 'where')
		{
			$str_where = $data;
			unset($arr_values[$str_field]);
			continue;
		}
		elseif ( is_array($fields) && !array_key_exists($str_field,$fields))
		{
			//Sonst SQL Fehler!
			continue;
		}
		elseif(is_array($data))
		{
			// SQL-Code
			if(isset($data['sql']) && $data['sql'] === true)
			{
				$str_data = $data['value'];
			}		
			else 
			{
				$data = db_real_escape_string(utf8_serialize($data));
				$str_data = '"'.$data.'"';
			}
		}
		elseif(is_bool($data))
		{
			$data = (int)$data;
			$str_data = '"'.$data.'"';
		}
		elseif(is_string($data)) 
		{
			$data = addstripslashes($data);
			$str_data = '"'.$data.'"';
		}
		else
		{
			$str_data = '"'.$data.'"';
		}
		
		$str_tupel .= '`'.$str_field.'`='.$str_data.',';
		$int_count_fields++;
		
	}
	
	if($int_count_fields==0)
	{
		return false;
	}

	// Komma weg
	$str_tupel = mb_substr($str_tupel,0,mb_strlen($str_tupel)-1);

	// Query zusammensetzen und an die Datenbank senden
	$str_sql .= $str_tupel."\n WHERE ".$str_where;
	
	if($int_acctid>-1)
	{
	//Cache::delete(Cache::CACHE_TYPE_MEMORY,'user_data_'.$int_acctid );
	}
	
	db_query('BEGIN');
	db_query('SELECT `'.implode('`,`',array_keys($arr_values)).'` FROM `accounts` WHERE '.$str_where.' FOR UPDATE');
	db_query($str_sql);	
	db_query('COMMIT');

	if(!db_errno(LINK)) 
	{
		return(true);
	}
	else 
	{
		return(false);
	}
}

/**
*@desc Prüft übergebenen String auf Übereinstimmung mit nicht zu ändernden Titeln
*@param string Titel
*@author talion
*@return bool true, wenn Übereinstimmung, sonst false
*/
function user_check_title_nochange ($str_in) {

	// Titel, deren Änderung nicht erlaubt ist
	// Case insensitive, ohne Farbcodes!
	$arr_titles_nochange = array(
	'flauschihase','kröte','frosch','ramius sklave','ramius sklavin','feigling','tempeldiener',
	'fürst von '.mb_strtolower(getsetting('townname','Atrahor')),'fürstin von '.mb_strtolower(getsetting('townname','Atrahor'))
	);

	if(!sizeof($arr_titles_nochange)) {
		return(false);
	}

	$str_in = stripslashes( $str_in );
	$str_in = mb_strtolower( $str_in );
	$str_in = strip_appoencode( $str_in, 3 );

	if( in_array( $str_in, $arr_titles_nochange ) ) {
		return(true);
	}
	else {
		return(false);
	}
}


// Konstanten für rename- und retitle-Optionen
define('USER_NAME_BLACKLIST',			0x001);
define('USER_NAME_BAN',					0x002);
define('USER_NAME_BADWORD',				0x004);
define('USER_NAME_OFFICIALTITLE',		0x008);
define('USER_NAME_EXCLUSIVE_TITLE',		0x010);
define('USER_NAME_NOCHANGE',			0x020);
define('USER_NAME_SOUNDS_LIKE_ADMIN',	0x040);
define('USER_NAME_SOUNDS_LIKE_EXISTING',0x080);
define('USER_NAME_TOO_SHORT',			0x100);
define('USER_NAME_TOO_LONG',			0x200);
define('USER_NAME_DUPE',				0x400);
define('USER_NAME_SPACE_IN_NAME',		0x800);
define('USER_NAME_SPECIALCHAR_IN_NAME',	0x1000);
define('USER_NAME_CRITICAL_CHAR_IN_NAME',0x2000);
define('USER_NAME_TOO_MANY_COLORS',		0x4000);
define('USER_NAME_ALL_UPPER',			0x8000);
define('USER_NAME_FIRST_LOWER',			0x10000);

/**
*@desc Benennt einen User um, validiert Namen, kümmert sich auch um Umbenennung in Forum
*@param int AccountID
*@param string Loginname / Farbiger Name
*@param bool Speicherung vornehmen oder nur validieren (optional, Standard true)
*@param bool Änderung des Forenlogins vornehmen (optional, Standard true)
*@param int Legt fest, worauf die Änderung überprüft wird. Bitweise ODER-Verknüpfung der Konstanten USER_NAME_...
*@author talion
*@return string Fehlercode bzw. neuen Namen
*/
function user_rename ($int_acctid, $str_name, $bool_save = true, $bool_boardlogin = true, $int_options = 0, $bool_softeval = false) {
	global $session, $access_control;

	if($int_options == 0) {
		$int_options = USER_NAME_BADWORD | USER_NAME_BAN | USER_NAME_BLACKLIST | USER_NAME_OFFICIALTITLE | USER_NAME_ALL_UPPER | USER_NAME_FIRST_LOWER | USER_NAME_TOO_LONG | USER_NAME_TOO_SHORT;
	}

	$bool_player = false;
	$int_return = 0;

	// Wichtige Infos abrufen
	if($int_acctid == $session['user']['acctid'] || $int_acctid == 0) {
		$bool_player = true;
		$int_acctid = $session['user']['acctid'];
		$arr_info['login'] = $session['user']['login'];
	}
	else {
		$arr_info = db_get( 'SELECT login FROM accounts WHERE acctid='.$int_acctid );
	}

	// Feststellen, ob es sich bei Param um farbigen Namen handelt
	$str_name = stripslashes($str_name);
	$str_login = trim(strip_appoencode($str_name,3));
	$str_cname = false;
	if($str_login != $str_name) {
		$str_cname = trim($str_name);
	}
	$int_login_len = mb_strlen($str_login);
	
	if(getsetting('name_casechange',1) && ($int_options & USER_NAME_NOCHANGE))
	{
		if ( mb_strtolower($str_login) != mb_strtolower($arr_info['login'])){
				$int_return = $int_return | USER_NAME_NOCHANGE;
		}
				
		if(!getsetting('allletter_up_allow',1) && ($int_options & USER_NAME_ALL_UPPER) && ctype_upper($str_login))
		{
			$int_return = $int_return | USER_NAME_ALL_UPPER;
		}
		
		if(getsetting('firstletter_up',1) && ($int_options & USER_NAME_FIRST_LOWER) && ctype_lower(mb_substr($str_login,0,1)))
		{
			$int_return = $int_return | USER_NAME_FIRST_LOWER;
		}
	}

	// Wenn Login leer ist
	if(empty($str_login)) {
		$int_return = $int_return | USER_NAME_TOO_SHORT;
	}	

	// Login unabhängig von Groß / Kleinschreibung prüfen
	$str_checklogin = mb_strtolower($str_login);

	// Unterschied bei Login?
	if( mb_strtolower($arr_info['login']) != $str_checklogin ) {

		// Check auf Ban
		if( ($int_options & USER_NAME_BAN) && checkban($str_checklogin,false,false,false,0,false) ) {
			$int_return = $int_return | USER_NAME_BAN;
		}

		// Check auf Eintrag in BlackList: Darf nicht in Kleinschrift erfolgen!
		if( ($int_options & USER_NAME_BLACKLIST) && check_blacklist(BLACKLIST_LOGIN, $str_login) ) {
			$int_return = $int_return | USER_NAME_BLACKLIST;
		}
		
		// Klingt der User wie ein Admin ist ds nicht erlaubt
		if( $int_options & USER_NAME_SOUNDS_LIKE_ADMIN) {
			
			$str_su_groups = implode(',',$access_control->get_superuser_sugroups());
			$str_sql_only_su = " (accounts.superuser IN ($str_su_groups)) ";
			$arr_result = db_get('SELECT count(*) as `count` FROM `accounts` WHERE '.$str_sql_only_su.' AND `login` SOUNDS LIKE "'.$str_login.'"');
			if($arr_result['count'] > 0) 
			{
				$int_return = $int_return | USER_NAME_SOUNDS_LIKE_ADMIN;
			}
		}
		
		// Klingt der User wie ein bereits existierender User?
		if($int_options & USER_NAME_SOUNDS_LIKE_EXISTING) {
			
			$str_su_groups = implode(',',$access_control->get_superuser_sugroups());
			$str_sql_only_su = " (accounts.superuser NOT IN ($str_su_groups)) ";
			$arr_result = db_get('SELECT count(*) as `count` FROM `accounts` WHERE '.$str_sql_only_su.' AND `login` SOUNDS LIKE "'.$str_login.'"');
			if($arr_result['count'] > 0) 
			{
				$int_return = $int_return | USER_NAME_SOUNDS_LIKE_EXISTING;
			}
		}

		// Check auf Duplikat
		if( db_num_rows(db_query('SELECT acctid FROM accounts WHERE LOWER(login)="'.db_real_escape_string($str_checklogin).'"')) ) {
			$int_return = $int_return | USER_NAME_DUPE;
		}

		// Login validieren

		// Zunächst: Wenn gar keine Sonderzeichen in Namen erlaubt
		if(getsetting("specialkeys",0) == 0) {
			if( utf8_preg_match("/([^[:alpha:]\s-])/",$str_checklogin) ) {
				$int_return = $int_return | USER_NAME_SPECIALCHAR_IN_NAME;
			}
		}

		// Wenn keine Leerzeichen erlaubt
		if(getsetting("spaceinname",0) == 0) {
			if( utf8_preg_match("/([\s])/",$str_checklogin) ) {
				$int_return = $int_return | USER_NAME_SPACE_IN_NAME;
			}
		}

		// Generell Zeichen, die nichts in einem Namen zu suchen haben
		$str_criticalchars = getsetting("criticalchars",'');
		if(!$bool_softeval && !empty($str_criticalchars)) {
			//fix by bathi
			//$str_criticalchars = str_replace(array('/','[',']'),array('\/','\[','\]'),$str_criticalchars);
			//end fix by bathi
			if( utf8_preg_match("/[".utf8_preg_quote($str_criticalchars,"/")."]/i",$str_checklogin) ) {
				$int_return = $int_return | USER_NAME_CRITICAL_CHAR_IN_NAME;
			}
		}

		// Prüfen, ob wir einen offiziellen Titel im Namen haben
		if(($int_options & USER_NAME_OFFICIALTITLE)) {
			$titles = utf8_unserialize((getsetting('title_array',null)) );
			if(is_array($titles)) {

				foreach($titles as $t) {

					if(mb_strtolower($t[0]) == $str_checklogin || mb_strtolower($t[1]) == $str_checklogin) {
						$int_return = $int_return | USER_NAME_OFFICIALTITLE;
					}
				}
			}
		}

		// Länge checken
		$int_min_len = getsetting('nameminlen',3);
		$int_max_len = getsetting('namemaxlen',25);

		if (($int_options & USER_NAME_TOO_SHORT) && $int_login_len < $int_min_len){
			$int_return = $int_return | USER_NAME_TOO_SHORT;
		}
		if (($int_options & USER_NAME_TOO_LONG) && $int_login_len > $int_max_len){
			$int_return = $int_return | USER_NAME_TOO_LONG;
		}

		// Böse Sachen im Namen
		if ( ($int_options & USER_NAME_BADWORD) && soap($str_checklogin)!=$str_checklogin ){
			$int_return = $int_return | USER_NAME_BADWORD;
		}
		if($int_return > 0)
		{
			return $int_return;
		}

		if($bool_save) {
			// Login: Passt!
			// Login Speichern
			if($bool_player) {
				$session['user']['login'] = $str_login;
			}
			else {
				user_update(
					array
					(
						'login'=>db_real_escape_string($str_login)
					),
					$int_acctid
				);
			}
		}
		// END LOGIN

		// Name ist unterschiedlich, farbigen Namen löschen
		$str_cname = '';
	}


	// TODO: Validierung des farbigen Namens
	if(is_string($str_cname)) {

        /*
		// Max. Anzahl der Farbcodes (*2: ` + Farbcode)
		$int_colorcount = getsetting("name_maxcolors",7);
		if( (mb_strlen($str_cname) - $int_login_len) > $int_colorcount * 2 ) {
			$int_return = $int_return | USER_NAME_TOO_MANY_COLORS;
		}
		 */
		if($int_return > 0)
		{
			return $int_return;
		}

		// Speichern
		if($bool_save) {
			$str_cname = db_real_escape_string($str_cname);
			user_set_aei( array('cname'=>$str_cname), $int_acctid );
		}
	}
	else {	// Kein farbiger Name mehr
		// Speichern
		if($bool_save) {
			user_set_aei( array('cname'=>''), $int_acctid );
		}
	}

	return($int_return);
}

function evaluate_user_rename($int_result)
{
	if(is_bool($int_result))
	{		
		return $int_result;
	}
	if($int_result === 0)
	{
		return true;
	}
	
	$str_return = '';

	if($int_result & USER_NAME_BAN)
	{
		$str_return .= 'Dieser Name ist gebannt!`n';
	}
	if($int_result & USER_NAME_BLACKLIST)
	{
		$str_return .= 'Dieser Name ist verboten!`n';
	}
	if($int_result & USER_NAME_DUPE)
	{
		$str_return .= 'Diesen Namen gibt es leider schon!`n';
	}
	if($int_result & USER_NAME_TOO_SHORT)
	{
		$str_return .= 'Dein gewählter Name ist zu kurz (Min. '.getsetting('nameminlen',3).' Zeichen)!`n';
	}
	if($int_result & USER_NAME_TOO_LONG)
	{
		$str_return .= 'Dein gewählter Name ist zu lang (Max. '.getsetting('namemaxlen',3).' Zeichen)!`n';
	}
	if($int_result & USER_NAME_BADWORD)
	{
		$str_return .= 'Dein gewählter Name enthält unzulässige Begriffe!`n';
	}
	if($int_result & USER_NAME_SPACE_IN_NAME)
	{
		$str_return .= 'Dein gewählter Name enthält Leerzeichen, was leider nicht erlaubt ist!`n';
	}
	if($int_result & USER_NAME_SPECIALCHAR_IN_NAME)
	{
		$str_return .= 'Dein gewählter Name enthält Sonderzeichen, was leider nicht erlaubt ist!`n';
	}
	if($int_result & USER_NAME_CRITICAL_CHAR_IN_NAME)
	{
		$str_return .= 'Dein gewählter Name enthält eines der folgenden Zeichen, die für einen Namen nicht geeignet sind:`n
								'.str_replace('\\','',getsetting('criticalchars','')).'`n';
	}
	if($int_result & USER_NAME_OFFICIALTITLE)
	{
		$str_return .= 'Dein gewählter Name enthält einen Titel, der ein Teil des Spiels ist!`n';
	}
	if($int_result & USER_NAME_SOUNDS_LIKE_ADMIN)
	{
		//Username anzeigen der ähnelt
	}
	if($int_result & USER_NAME_SOUNDS_LIKE_EXISTING)
	{
		//Username anzeigen der ähnelt
	}
	if($int_result & USER_NAME_NOCHANGE)
	{
		$str_return .= 'Dein neuer Name muss genauso bleiben wie dein alter Name. Du kannst
					'.(getsetting('name_casechange',1) ? 'die Groß-/Kleinschreibung ändern, ' : '').'
					 Farbcodes entfernen oder hinzufügen, aber ansonsten muss alles gleichbleiben.`n';
	}
	if($int_result & USER_NAME_TOO_MANY_COLORS)
	{
		$str_return .= 'Du hast leider zu viele Farbwechsel in deinem Namen verwendet. Du darfst nur '.getsetting("name_maxcolors",7).'x die Farbe wechseln.';
	}	
	if($int_result & USER_NAME_ALL_UPPER)
	{
		$str_return .= 'Namen, die nur in Großschreibung gehalten sind, sind verboten. Bitte ändere dies.`n';
	}
	if($int_result & USER_NAME_FIRST_LOWER)
	{
		$str_return .= 'Namen, deren erster Buchstabe in Kleinschreibung gehalten ist, sind verboten. Bitte ändere dies und verwende als erstes Zeichen einen Großbuchstaben.`n';
	}
	
	//Catch all
	if($int_result > 0 && empty($str_return))
	{
		$str_return .= 'Irgendetwas stimmt mit deinem Namen nicht. Wir haben keine Ahnung, aber wurden benachrichtigt :-)`n';
		systemlog('`$Ungültiger Username ohne definierten Grund`0');
	}
	
	return '`^'.$str_return.'`0';
}


/**
*@desc Ändert Titel eines Users, validiert diesen
*@param int AccountID
*@param string Regulärer Titel
*@param string Eigener Titel
*@param bool Speichern? (Optional, Standard true)
*@param int Legt fest, worauf die Änderung überprüft wird. Bitweise ODER-Verknüpfung der Konstanten USER_NAME_...
*@author talion
*@return string Fehlercode bzw. true
*/
function user_retitle ($int_acctid, $str_title, $str_ctitle, $bool_save = true, $int_options = 0) {
	global $session,$arr_titles_nochange;

	if($int_options == 0) {
		$int_options = USER_NAME_BADWORD | USER_NAME_BLACKLIST | USER_NAME_OFFICIALTITLE;
	}

	$bool_player = false;

	// Wichtige Infos abrufen
	if($int_acctid == $session['user']['acctid'] || $int_acctid == 0) {
		$bool_player = true;
		$int_acctid = $session['user']['acctid'];
		$arr_info = user_get_aei('ctitle');
		$arr_info['title'] = $session['user']['title'];
	}
	else {
		$arr_info = db_fetch_assoc( db_query( 'SELECT title FROM accounts WHERE acctid='.$int_acctid ) );
		$arr_info = array_merge( $arr_info, user_get_aei('ctitle',$int_acctid) );
	}

	$str_title = stripslashes(trim($str_title));
	$str_ctitle = stripslashes(trim($str_ctitle));

	// Eigenen Titel validieren
	if(is_string($str_ctitle)) {

		// Prüfen, ob aktueller ctitle ein nicht zu ändernder
		if( ($int_options & USER_NAME_NOCHANGE) && user_check_title_nochange($arr_info['ctitle'])) {
			return('ctitle_changeforbidden');
		}

		if(!empty($str_ctitle)) {
			$str_checktitle = strip_appoencode(mb_strtolower($str_ctitle),3);

			// Prüfen, ob neuer Titel ein exklusiver
			if( ($int_options & USER_NAME_EXCLUSIVE_TITLE) && user_check_title_nochange($str_checktitle)) {
				return('ctitle_exclusive');
			}

			// Länge checken
			$int_min_len = getsetting('titleminlen',3);
			$int_max_len = getsetting('titlemaxlen',25);
			$int_checktitle_len = mb_strlen($str_checktitle);
			$int_ctitle_len = mb_strlen($str_ctitle);
			
			//Titel OHNE Farben darf nicht kürzer als getsetting('titleminlen',3) sein
			if ($int_checktitle_len < $int_min_len){
				return('ctitle_tooshort');
			}
			//Titel OHNE Farben darf nicht länger als getsetting('titlemaxlen',25) sein
			if ($int_checktitle_len > $int_max_len){
				return('ctitle_toolong');
			}

			// Sonderzeichen als ersten Buchstaben des Titels dürfen nur Superuser
			// Evtl.: Als gesondertes Recht vergeben
			if( utf8_preg_match('/[^\w]/',mb_substr($str_checktitle,0,1)) && $session['user']['superuser'] == 0 ) {
				return('ctitle_blacklist');
			}

			// Check auf Eintrag in BlackList
			if( ($int_options & USER_NAME_BLACKLIST) && check_blacklist(BLACKLIST_TITLE, $str_checktitle) ) {
				return('ctitle_blacklist');
			}

			// Böse Sachen im Titel
			if ( ($int_options & USER_NAME_BADWORD) && soap($str_ctitle)!=$str_ctitle){
				return('ctitle_badword');
			}

			// Prüfen, ob wir einen offiziellen Titel verwenden
			if($int_options & USER_NAME_OFFICIALTITLE) {
				$titles = utf8_unserialize((getsetting('title_array',null)) );
				if(is_array($titles)) {
					foreach($titles as $t) {

						if(mb_strtolower($t[0]) == $str_checktitle || mb_strtolower($t[1]) == $str_checktitle) {
							return('ctitle_officialtitle');
						}
					}
				}
			}

            /*
			// Max. Anzahl der Farbcodes (*2: ` + Farbcode)
			$int_colorcount = getsetting('title_maxcolors',7);
			if( (mb_strlen($str_ctitle) - $int_checktitle_len) > $int_colorcount * 2 ) {
				return('ctitle_toomuchcolors');
			}
            */

		}

		if($bool_save) {
			// ctitle: Passt!

			$str_ctitle = str_replace('[quot]','&quot;',$str_ctitle);
			user_set_aei(array('ctitle'=>db_real_escape_string($str_ctitle)),$int_acctid);
		}
	}
	// END CTITLE

	// regulärer Titel
	if(is_string($str_title) && !empty($str_title) && $bool_save) {

		// Prüfen, ob aktueller title ein nicht zu ändernder
		if( ($int_options & USER_NAME_NOCHANGE) && user_check_title_nochange($arr_info['title'])) {
			return('title_changeforbidden');
		}

		// Speichern
		if($bool_player) {
			$session['user']['title'] = $str_title;
		}
		else {
			user_update(
				array
				(
					'title'=>db_real_escape_string($str_title)
				),
				$int_acctid
			);
		}
	}


	return(true);
}

/**
*@desc 	Erstellt aus bereits vorher gegebenen Daten vollwertigen Spielernamen
*		Nimmt selbst keinerlei Validierung vor!
*@param int AccountID
*@param bool Speichern? (Optional, Standard true)
*@param array Daten, falls gegeben (ctitle,csign,cname,title,login); macht DB-Abfrage unnötig (optional, Standard leer)
*@author talion
*@return string Fehlercode bzw. neuen Namen
*/
function user_set_name ($int_acctid, $bool_save = true, $arr_data = array()) {

	global $session,$arr_titles_nochange;

	$bool_player = false;
	
	// Wichtige Infos abrufen
	if($int_acctid == $session['user']['acctid'] || $int_acctid == 0) {
		$bool_player = true;
		$int_acctid = $session['user']['acctid'];
		$arr_info['login'] = $session['user']['login'];
		$arr_info['title'] = $session['user']['title'];
	}
	else {
		if(!sizeof($arr_data)) {
			$arr_info = db_fetch_assoc( db_query( 'SELECT login, title FROM accounts WHERE acctid='.$int_acctid ) );
		}
		else {
			$arr_info = $arr_data;
		}
	}
	if(!sizeof($arr_data)) {
		$arr_info_e = user_get_aei('ctitle,cname,csign,title_postorder,title_hide',$int_acctid);
	}
	else {
		$arr_info_e = $arr_data;
	}
	// END infos abrufene
	
	$bool_owntitle = !empty($arr_info_e['ctitle']);
	
	//advanced_title_options
	$arr_info_title_adv = user_get_aei('advanced_title_options',$int_acctid);
	$has_title_adv = $arr_info_title_adv['advanced_title_options'];
	
	$str_realtitle 	= $bool_owntitle ? str_replace('`0','',$arr_info_e['ctitle']) : $arr_info['title'];
	// Wenn normaler Titel ein nicht zu ändernder ist
	if( user_check_title_nochange($arr_info['title']) ) {
		$str_realtitle 	= $arr_info['title'];
		$bool_owntitle = false;
	}
	$str_csign      = !empty($arr_info_e['csign']) 	? $arr_info_e['csign'].'`&' : '';
	$str_realname 	= !empty($arr_info_e['cname']) 	? str_replace('`0','',$arr_info_e['cname'])	: $arr_info['login'];
	$str_name 		= trim($str_csign);
	
	$bool_replace_name = ($str_realtitle != str_replace('%s%','',$str_realtitle)) ? true : false;
	
	// Titel verbergen bzw. hinter dem Namen (Nur wenn eigener Titel eingetragen)
	if($arr_info_e['title_hide']) {	
		$str_name .= trim($str_realname).'`0';
	}
	else if($bool_owntitle && $has_title_adv && $bool_replace_name){
		$str_name .= str_replace('%s%',' '.trim($str_realname).' ',trim($str_realtitle)).'`0';
	}
	else if($bool_owntitle && $arr_info_e['title_postorder']) {	
		$str_name .= trim($str_realname).' '.trim($str_realtitle).'`0';
	}
	else {
		$str_name .= trim($str_realtitle).' '.trim($str_realname).'`0';
	}

	// Speichern
	if($bool_save) {
		if($bool_player) {
			$session['user']['name'] = $str_name;
		}
		else {
			user_update(
				array
				(
					'name'=>db_real_escape_string($str_name)
				),
				$int_acctid
			);
		}
	}

	return($str_name);

}

/**
*@desc Löscht einen User komplett aus der Datenbank.
*@param int Accountid des zu löschenden Users, muss gegeben sein.
*@return bool true / false
*@author Drachenserver-Team
*/
function user_delete ($uid) {
	require_once(LIB_PATH.'house.lib.php');

	$uid = (int)$uid;

	if(!$uid) {
		return(false);
	}

	$sql = 'SELECT guildid,dragonkills,cname,login FROM accounts
			LEFT JOIN account_extra_info USING(acctid) WHERE accounts.acctid='.$uid;
	$acc = db_fetch_assoc(db_query($sql));

	$acc['tmpname'] = ($acc['cname'] ? $acc['cname'] : $acc['login']);

	//User in Valhalla speichern
	if($acc['dragonkills'] >= getsetting('famous_deleted_chars_min_DKs',30))
	{
		$sql='SELECT a.acctid,name,race,dragonkills,sex,birthday AS birth,aei.char_birthdate FROM accounts a LEFT JOIN account_extra_info aei USING(acctid) WHERE a.acctid='.$uid.' AND locked=0';
		$result=db_query($sql);
		if(db_num_rows($result))
		{
			$row=db_fetch_assoc($result);
			$row['death'] = getsetting('gamedate','0005-01-01');
			$row['bio']='';
			$row['name_clean'] = str_replace('`0', '', strip_appoencode( $row['name'] ) );
			db_insert('valhalla',$row);
		}
	}

	// Fürstentitel vakant setzen
	$fuerst = stripslashes(getsetting('fuerst',''));
	if($fuerst == $acc['tmpname']) {
		savesetting('fuerst','');
	}

	// inventar und haus löschen und partner und ei freigeben
	if ($uid==getsetting('hasegg',0)) {
		savesetting('hasegg',stripslashes(0));
		$sql = 'UPDATE items SET owner=0 WHERE tpl_id="goldenegg"';
		db_query($sql);
	}

	// Hausschlüssel auf Verloren setzen
	$sql = 'UPDATE keylist SET owner=0 WHERE owner='.$uid.' AND type='.HOUSES_KEY_DEFAULT;
	db_query($sql);

	// Wenn Haus noch im Bau, auf leeres Grundstück zurücksetzen, sonst auf verlassen
	$sql = 'UPDATE houses SET owner=0,build_state=IF(
							build_state = '.HOUSES_BUILD_STATE_INIT.',
								'.HOUSES_BUILD_STATE_EMPTY.',
								'.HOUSES_BUILD_STATE_ABANDONED.'
							),lastchange=NOW()
			WHERE owner='.$uid.'';
	db_query($sql);

	// Gemächer auf 0 setzen
	$sql = 'UPDATE house_extensions SET owner=0 WHERE owner='.$uid;
	db_query($sql);

	// Einladungen in Gemächer löschen (des Gelöschten und im Besitz des Gelöschten)
	$sql = 'DELETE FROM keylist WHERE type='.HOUSES_KEY_PRIVATE.' AND (value3='.$uid.' OR owner='.$uid.')';
	db_query($sql);

	user_update(
		array
		(
			'charisma'=>0,
			'marriedto'=>0,
			'where'=>'marriedto='.$uid
		)
	);

	// Adressbuch und Mails löschen
	$sql = 'DELETE FROM yom_adressbuch WHERE player='.$uid.' OR acctid='.$uid;
	db_query($sql);
	$sql = 'DELETE FROM mail WHERE msgto='.$uid;
	db_query($sql);


	$sql = 'DELETE FROM boards WHERE author='.$uid;
	db_query($sql);


	$sql = 'DELETE FROM disciples WHERE master='.$uid;
	db_query($sql);

	// Items löschen
	$sql = 'DELETE FROM items WHERE owner='.$uid;
	db_query($sql);

	// Gartenpflanzen löschen
	$sql = 'DELETE FROM crops WHERE owner_id='.$uid;
	db_query($sql);


	$sql = 'DELETE FROM pvp WHERE acctid2='.$uid.' OR acctid1='.$uid;
	db_query($sql);


	$sql = 'DELETE FROM accounts WHERE acctid='.$uid;
	db_query($sql);

	$sql = 'DELETE FROM account_extra_info WHERE acctid='.$uid;
	db_query($sql);

	$sql = 'DELETE FROM goldpartner WHERE acctid='.$uid;
	db_query($sql);

	// Statistiken löschen
	$sql = 'DELETE FROM account_stats WHERE acctid='.$uid;

	db_query($sql);

	// Abstimmungsergebnisse löschen
	$sql = 'DELETE FROM pollresults WHERE account='.$uid.' AND motditem=0';
	db_query($sql);

	// Aus Flirtliste löschen
	flirt_set(0,$uid,0,-1,0);

	$sql = 'DELETE FROM history WHERE acctid='.$uid;
	db_query($sql);

	// Einträge im Strafregister löschen
	db_query('DELETE FROM cases WHERE accountid='.$uid);
	db_query('DELETE FROM crimes WHERE accountid='.$uid);

	// Kommentare auf gelöscht setzen
	db_query('UPDATE commentary SET deleted_by = 16777215 WHERE author='.$uid);

	//Aus Multi-Table löschen
	db_squeryf('DELETE FROM account_multi WHERE master="%d" OR slave="%d"', $uid, $uid);
	
	//Userbilder löschen
	CPicture::delete($uid);

	return(true);

}

/**
*@desc Ermittelt Online-Status eines Spielers
*		Entweder ruft Funktion die dazu benötigten Accountdaten per acctid ab oder verwendet die per Param
*		übergebenen. Falls keines davon gegeben: Gibt sie den Queryteil zurück, der zu einem Check benötigt wird
*@param int Accountid des Users. Optional.
*@param array Accountdaten des Users. Optional. Enthalten muss sein: loggedin, laston, activated
*@param bool User im Stealthmode anzeigen ja / nein. Für User mit entsprechendem Recht sind diese immer sichtbar.
*@param bool Userrechte Ignorieren
*@return mixed Entweder bool (User online / offline) oder string (SQL-String)
*@author talion
*/
function user_get_online ($acctid=0,$acctinfo=false,$show_stealth=false,$ignore_rights=false) {

	global $session, $access_control;

	$acctid = (int)$acctid;
	$timeout = getsetting('LOGINTIMEOUT',900);
	$timeout_date = date( 'Y-m-d H:i:s' , time() - $timeout );

	if(!$ignore_rights && $show_stealth === false) {
		// Wenn entsprechendes Recht: Doch anzeigen
		$show_stealth = $access_control->su_check(access_control::SU_RIGHT_SHOWSTEALTH);
	}

	if($acctid) {

		$sql = 'SELECT loggedin,laston,activated FROM accounts WHERE acctid='.$acctid;
		$res = db_query($sql);
		$acctinfo = db_fetch_assoc($res);

	}

	if(is_array($acctinfo)) {
		$online = ($acctinfo['loggedin'] == 1 && $acctinfo['laston'] > $timeout_date && ($show_stealth || $acctinfo['activated'] != USER_ACTIVATED_STEALTH || $session['user']['acctid'] == $acctinfo['acctid']) ? true : false);
		return($online);

	}

	return( ' loggedin=1 AND laston>"'.$timeout_date.'" '.(!$show_stealth ? ' AND activated!='.USER_ACTIVATED_STEALTH : '') );

}

/**
*@desc Zeigt eine Userliste in Tabellenform an
*@param int Spieler pro Seite (Optional, Standard 50)
*@param string SQL-WHERE-Konditionen (Optional, Standard keine)
*@param string SQL-ORDER BY-Anweisungen (Optional, Standard level, dks etc.)
*@param bool Suchmaske anzeigen (Optional, Standard false)
* @param bool Suchmaske anzeigen (Optional, Standard false)
*@param int Maximale Anzahl an Spielern, die angezeigt werden sollen
*@author talion unter Verwendung von Core-Code
*/
function user_show_list (
	$playersperpage=50,
	$where='',
	$orderby=' level DESC, dragonkills DESC, name ASC',
	$show_search=false,
	$arr_columns = '*',
	$max_show = 100
) {
	require_once(LIB_PATH.'jslib.lib.php');

	global $session, $access_control, $Char;

	$link = calcreturnpath();

    $where = $where . (($where != '') ? ' AND ' : ' ') . ' a.acctid NOT IN ('.CIgnore::ignore_sql(CIgnore::IGNO_LIST).') ' ;

	$where = ($where != '') ? $where : '1';
	$search = '';

	$sql = 'SELECT count(*) AS c FROM accounts a WHERE locked=0 AND '.$where;

	$arr_res = page_nav($link,$sql,$playersperpage);
	//$link = $link .= (mb_strstr($link,'?')?'&':'?');

	$totalplayers = $arr_res['count'];

	if ($_GET['op']=='search' && mb_strlen($_POST['name']) > 2 )
	{
		$str_name = str_create_search_string($_POST['name']);

		$search=' AND (a.name LIKE "'.$str_name.'") ';
		$orderby = ' IF( a.login = "'.$_POST['name'].'", 1, 0 ) DESC , '.$orderby;
		$limit = ' LIMIT 0,'.($max_show+1);

	}
	else{
		$limit=' LIMIT '.$arr_res['limit'];
	}

	$bool_lockhtml = $access_control->su_check(access_control::SU_RIGHT_LOCKHTML);
	$sql = 'SELECT 	a.superuser,
					a.acctid,
					a.name,
					a.login,
					a.alive,
					a.expedition,
					a.imprisoned,
					a.location,
					a.sex,
					a.level,
					a.laston,
					a.loggedin,
					a.lastip,
					a.uniqueid,
					a.race,
					a.prefs,
					a.chat_status,
					'.($bool_lockhtml ? 'aei.html_locked,' : '').'
					aei.charclass,
					aei.ext_profile,
					g.name AS guildname,
					restorepage
			FROM accounts a
			LEFT JOIN dg_guilds g ON g.guildid=a.guildid AND a.guildfunc!=1
			INNER JOIN account_extra_info aei ON a.acctid=aei.acctid
			WHERE locked=0 AND '.$where.' '.$search.'
			ORDER BY '.$orderby.' '.$limit;
	if ($session['user']['loggedin'] && $show_search)
	{
		$searchlink = $link.(mb_strpos($link,'?') !== false ?'&':'?');
		$str_output .= '
			`n`c
				' . form_header($searchlink . 'op=search') . '
						Nach Name suchen:  ' . JS::Autocomplete('name',true, true) . '
				</form>
			`c`n
		';
	}
	elseif ($show_search)
	{
		$ip=$_SERVER['REMOTE_ADDR'];
		$searchfield=false;

		db_query('DELETE FROM ipsperre WHERE timelimit<='.date('U'));

		if (db_num_rows(db_query("SELECT * FROM ipsperre WHERE ip='".$ip."'"))<=0)
		{
			$ex_search['searchlimit']=getsetting('exsearch_limit', 10);
			$ex_search['timelimit']=date('U')+(getsetting('exsearch_time', 30)*60);

			db_query("INSERT INTO ipsperre(search, timelimit, ip) VALUES (".$ex_search['searchlimit'].", ".$ex_search['timelimit'].", '".$ip."')");

			$searchfield=true;
		}

		else
		{
			$ex_result = db_query("SELECT * FROM ipsperre WHERE ip='".$ip."'");
			$ex_row = db_fetch_object($ex_result);
			$ex_search['searchlimit'] = $ex_row->search;
			$ex_search['timelimit'] = $ex_row->timelimit;
		}

		if ($_POST['limit'])
		{
			$newtime=date('U')+(getsetting('exsearch_time', 30)*60);

			db_query("UPDATE ipsperre SET search=search-1, timelimit=".$newtime." WHERE ip='".$ip."'");

			$ex_search['searchlimit']--;
			$ex_search['timelimit']=$newtime;
		}

		if ($ex_search['searchlimit']>0)
		{
			$searchfield = true;
		}

		if ($searchfield == true)
		{

			$searchlink = $link.(mb_strpos($link,'?') !== false ? '&':'?');
			$str_output .= '
				`n`c
				' . form_header($searchlink . 'op=search') . '
					Nach Name suchen: ' . JS::Autocomplete('name',true, true) . '
					<input type="hidden" name="limit" value="true">
				</form>
				`c
			';
		}

		elseif (db_num_rows(db_query("SELECT * FROM ipsperre WHERE ip='".$ip."'"))>0)
		{
			$resttime=round(($ex_search['timelimit']-date('U'))/60);
			$str_output .= '`n`c`iNoch `b'.$resttime.'`b '.($resttime==1?'Minute':'Minuten').' bis zur nächsten Suche.`i`c`n';
		}
	}
	
	$arr_all_columns = array(
		'level'	=> 'Level',
		'race'	=> 'Rasse',
		'place' => 'Ort',		
		'rpg'	=> 'RPG',
		'status'=> 'Status',
		'guild'	=> 'Gilde'
	);
	$arr_show_columns = array();
	
	//Default Werte setzen aus dem Parameter
	foreach ($arr_all_columns as $key => $val)
	{
		//Bei Stern immer setzen
		if($arr_columns == '*')
		{
			$arr_show_columns[$key] = true;
		}
		elseif(isset($arr_columns[ $key ]))
		{
			$arr_show_columns[$key] = ($arr_columns[ $key ] == true?true:false);
		}
	}
	
	//Jetzt die Werte aus der Session holen und default werte überschreiben
	if(is_array(Atrahor::$Session['user_list_columns']))
	{
		foreach(Atrahor::$Session['user_list_columns'] as $key => $val)
		{
			if(!is_null_or_empty($arr_all_columns[ $key ]))
			{
				$arr_show_columns[$key] = $val == true?true:false;
			}
		}
	}
	
	//Mit den Userwerten aus dem Formular finalisieren, weil höchste Priorität
	if(isset($_REQUEST['list_columns_submit']))
	{
		foreach ($arr_all_columns as $key => $val)
		{
			$arr_show_columns[$key] = ($_REQUEST['list_columns'][ $key ] == 'on' ? true : false);
			Atrahor::$Session['user_list_columns'][$key] = $arr_show_columns[$key];
		}
	}
	
	//Html Fragment erstellen
	foreach ($arr_all_columns as $key => $val)
	{
		$str_list_columns .= '<input type="checkbox" name="list_columns['.$key.']" id="list_'.$key.'" '.($arr_show_columns[$key] == true?'checked="checked"':'').' />'.$val.'&nbsp;';
	}
	
	if($Char->loggedin)
	{
		$str_output .= '
			`n`c
				'.form_header($link).$str_list_columns.'<input type="submit" name="list_columns_submit" value="Spalten ein/ausblenden" />'.form_footer().'
			`c`n
		';
	}

	$result = db_query($sql);
	$max = db_num_rows($result);
	if ($max>$max_show) 
	{
		$str_output .= "`$ Es treffen zu viele Namen auf diese Suche zu. Nur die ersten 100 werden angezeigt.`0`n";
		$max = $max_show;
	}

	if($arr_res['count'] > $playersperpage) 
	{
		$str_output .= '`bSeite '.$arr_res['page'].': '.($arr_res['from']+1).'-'.$arr_res['to'].' von '.$arr_res['count'].'`b`n';
	}

	$arr_groups = utf8_unserialize((getsetting('sugroups','')) );

	$str_output .= '<table style="margin-left:auto; margin-right:auto" border=0 cellpadding=2 cellspacing=1 bgcolor="#999999">
	<tr class="trhead">
		'.($arr_show_columns['level'] == true ? '<th>Level</th>':'').'
		<th>Name</th>
		'.($arr_show_columns['race'] == true ? '<th>Rasse</th>':'').'
		<th><img src="./images/female.gif">/<img src="./images/male.gif"></th>
		'.($arr_show_columns['place'] == true ? '<th>Ort</th>':'').'
		'.($arr_show_columns['status'] == true ? '<th>Status</th>':'').'
		'.($arr_show_columns['rpg'] == true ? '<th>RPG</th>':'').'
		<th>Zuletzt&nbsp;da</th>
		'.($arr_show_columns['guild'] == true ? '<th>Gilde</th>':'').'
	</tr>';


	// Rassen abrufen
	$arr_races = db_create_list(db_query('SELECT colname,id FROM races'),'id');
	
	$arr_superuser_groups = $access_control->get_superuser_sugroups();

	for($i=0;$i<$max;$i++)
	{
		$row = db_fetch_assoc($result);

		$row['guildname'] = ($row['guildname']) ? $row['guildname'] : 'Keine';

		$str_output .= '<tr class="'.($i%2?'trdark':'trlight').'">';
		
		//Level
		$str_output .= ($arr_show_columns['level'] == true ? '<td>`^'.$row['level'].'`0</td>' : '');

		//Userzeile
		if ($session['user']['loggedin']) 
		{
			$str_output .= '<td>'.CRPChat::menulink($row);
		}
		else
		{
			$str_output .= '<td>`'.($row['acctid']==getsetting('hasegg',0)?'^':'&').$row['name'].'`0';
		}

		if(in_array($row['superuser'],$arr_superuser_groups)) 
		{
			$str_output .= ' `n`7'.$arr_groups[$row['superuser']][0].'`0';
		}

		$str_output .= '</td>';

        $row['prefs'] = utf8_unserialize($row['prefs']);

        $row['ext_profile'] = utf8_unserialize($row['ext_profile']);
        $vc = CRPChat::make_color($row['ext_profile']['colors']['value'],'@');

        $rprace = trim($row['prefs']['rprace']);
        $row['charclass'] = strip_appoencode(strip_tags(utf8_html_entity_decode(trim($row['charclass']))),2);

        //Rasse
        if($session['user']['prefs']['norprace'] == 1) $str_output .= ($arr_show_columns['race'] == true ? '<td>'.$arr_races[$row['race']]['colname'].'`0</td>' : '');
        else if($rprace!='') $str_output .= ($arr_show_columns['race'] == true ? '<td>'.$vc.strip_appoencode(strip_tags(utf8_html_entity_decode($rprace)),2).( $row['charclass'] ? '`0 `n<div class="tsm">'.$vc.$row['charclass'].'`0</div>' : '' ).'`0</td>' : '');
        else $str_output .= ($arr_show_columns['race'] == true ? '<td>'.$vc.$arr_races[$row['race']]['colname'].( $row['charclass'] ? '`0 `n<div class="tsm">'.$vc.$row['charclass'].'`0</div>' : '' ).'`0</td>' : '');


		//Geschlecht
		$str_output .= '<td align="center">';
		$str_output .= $row['sex']?'<img src="./images/female.gif">':'<img src="./images/male.gif">';
		$str_output .= '</td>';

		//Ort		
		if($arr_show_columns['place'] == true)
		{
			$str_output .= '<td>';
			$loggedin=user_get_online(0,$row);
			switch($row['location'])
			{
				case USER_LOC_FIELDS:
					$str_output .= $loggedin?'`#Online`0':'`3Die Felder`0';
					break;
				case USER_LOC_INN:
					$str_output .= '`3Zimmer in Kneipe`0';
					break;
				case USER_LOC_HOUSE:
					$str_output .= '`3Im Haus`0';
					break;
				case USER_LOC_PRISON:
					$str_output .= '`3Im Kerker`0';
					break;
				case USER_LOC_VACATION:
					$str_output .= '`3In Sibirien`0';
					break;
				default:
					$str_output .= '`3'.get_location_name($row['location']).'`0';
			}
	
			if($bool_suwatch && !$row['superuser']) {
				$str_output .='`n`0'.utf8_htmlentities(mb_substr($row['restorepage'],0,30));
			}
			$str_output .= '</td>';
		}

		//Status tot/lebendig
		$str_output .= ($arr_show_columns['status'] == true ? '<td>'.($row['alive']?'`1Lebt`0':'`4Tot`0').'</td>' : '');
		
		//RPG
		if($arr_show_columns['rpg'] == true)
		{
			$str_output .= '<td align="center">';
			//Welcher Status dargestellt wird steht in den Settings
			$int_user_list_chat_status = getsetting('user_list_chat_status',0);
	
			
	
			if(getBit(UL_SHOW_SEARCH,$int_user_list_chat_status) && $row['chat_status']==3)
			{
				$str_output .='<img src="./images/icons/suche.gif" alt="Suche" title="Sucht einen RPG-Partner!">';
			}
			elseif(getBit(UL_SHOW_RPG,$int_user_list_chat_status) && $row['chat_status']==4)
			{
				$str_output .='<img src="./images/icons/rpg.gif" alt="Suche+Play" title="Das RPG kann gerne erweitert werden!">';
			}
			elseif(getBit(UL_SHOW_DND,$int_user_list_chat_status) && $row['chat_status']==5)
			{
				$str_output .='<img src="./images/icons/rpgdnd.gif" alt="nicht stören" title="Möchte beim Spielen nicht gestört werden!">';
			}
			elseif(getBit(UL_SHOW_WAITING,$int_user_list_chat_status) && $row['chat_status']==2)
			{
				$str_output .='<img src="./images/icons/warte.gif" alt="Wartet" title="Wartet auf eine RPG Verabredung!">';
			}
			elseif(getBit(UL_SHOW_INVISIBLE,$int_user_list_chat_status) && $row['chat_status']==0)
			{
				$str_output .='<img src="./images/icons/invisible.gif" alt="Unsichtbar" title="Ist nicht sichtbar im Spiel!">';
			}
			elseif(getBit(UL_SHOW_AVAILABLE,$int_user_list_chat_status) && $row['chat_status']==1)
			{
				$str_output .='<img src="./images/icons/visible.gif" alt="Online" title="Ist Online!">';
			}
			elseif(getBit(UL_SHOW_NOTIME,$int_user_list_chat_status) && $row['chat_status']==6)
			{
				$str_output .='<img src="./images/icons/stop.png" alt="keine Zeit" title="Ist Online, hat aber keine Zeit!">';
			}
			else
			{
				$str_output .='`&-`0';
			}
			$str_output .= '</td>';
		}

		//Zuletzt da
		$str_output .= '<td align="center">';

		$laston=round((strtotime(date('r'))-strtotime($row['laston'])) / 86400,0).' Tage';
		if (mb_substr($laston,0,2)=='1 ')
		{
			$laston='1 Tag';
		}
		if (date('Y-m-d',strtotime($row['laston'])) == date('Y-m-d'))
		{
			$laston='Heute';
		}
		if (date('Y-m-d',strtotime($row['laston'])) == date('Y-m-d',strtotime(date('r').'-1 day')))
		{
			$laston='Gestern';
		}
		if ($loggedin)
		{
			$laston='Jetzt';
		}

		$str_output .= $laston;
		$str_output .= '</td>';
		
		//Gilde
		$str_output .= ($arr_show_columns['guild'] == true ? '<td align="center">'.$row['guildname'].'`0</td>' : '');
		//Zeilenende
		$str_output .= '</tr>';
	}
	$str_output .= '</table>';
	output($str_output);
}

/**
 * Vergibt einmaligen ctitle an einen User, setzt bisherige Träger des Titels zurück;
 * Speichert bisherigen Titel auf Wunsch in Backup
 *
 * @param int AccountID des neuen Trägers. Wenn 0, wird nur zurückgesetzt
 * @param string Der Titel
 * @param bool Backup nutzen (optional, Standard true)
 */
function user_unique_ctitle ($int_acctid, $str_title, $bool_restore = true) {

	$str_title = stripslashes($str_title);

	// Ermitteln, wer diesen ctitle bisher trägt
	$arr_old = user_get_aei('acctid,ctitle_backup',-1,'ctitle="'.db_real_escape_string($str_title).'"');

	// Diesen zurücksetzen
	if($arr_old['acctid']) {
		if($bool_restore) {
			user_set_aei(array('ctitle'=>$arr_old['ctitle_backup'],'ctitle_backup'=>''),$arr_old['acctid']);
		}
		else {
			user_set_aei(array('ctitle'=>''),$arr_old['acctid']);
		}
		// Namen neu setzen
		user_set_name($arr_old['acctid']);
	}

	if($int_acctid > 0) {

		// Neuem User den Titel geben
		$sql = 'UPDATE account_extra_info SET '.($bool_restore ? 'ctitle_backup = ctitle' : '').',ctitle="'.db_real_escape_string($str_title).'" WHERE acctid='.$int_acctid;
		db_query($sql);

		user_set_name($int_acctid);
	}

}


define('LOSEGOLD_VALUE',   	1);  //anzahl an gold verlieren
define('LOSEEXP_VALUE', 	2);	//bestimmten wert an exp verlieren

/**
*@desc Killt nen User
* Prozente in form: [0-100]
*@param int $losegold wert vom goldverlust (standardmäßig 100%)
*@param int $loseexp wert vom expverlust (standardmäßig %)
*@param int $killdisciple wird der knppe sterben? 0 = nein; !=0 = jupp
*@param string $redirect was kommt nach dem tod?
*@param string $linkname eschriftung für den Todeslink
*@param int $killflags flags, die das verhalten bestimmen (ODER-VERKNÜPFT)
*@return array Verluste des Spielers, nach dem Muster: 'gold'=>Wert, 'disciple'=>Knappen-Datensatz...
*@author Alucard, modded by talion
*/

function killplayer( $losegold		= 100,
$loseexp		= 5,
$killdisciple	= false,
$redirect		= 'shades.php',
$linkname		= 'Zu den Schatten',
$killflags		= 0)
{
	global $Char;

	$arr_return = $Char->kill($losegold, $loseexp, $killdisciple, $redirect, $linkname, $killflags);
	return $arr_return;
}

/**
* @desc Errechnet einen Prozentsatz der Erfahrungspunkte; falls 2. Param true: DK- und Levelabhängig
* @param float_percent (float) wieviel Prozent der benötigten Erfahrung? (optional, Standard 10%)
* @param bool_nextlevel (bool) abhängig von nötiger Erfahrung für nächstes Level? (optional, Standard true)
* @author Talion, Salator
*/
function user_percent_level_exp ($float_percent=10, $bool_nextlevel=true)
{
	global $session;
	$float_percent/=100;

	if($bool_nextlevel===true)
	{ //Berechnung x% der Erfahrung die für Levelaufstieg nötig ist
		$int_rec = get_exp_required($session['user']['level']-1,$session['user']['dragonkills']);
		$int_req = get_exp_required($session['user']['level'],$session['user']['dragonkills']);
		$expplus = round(max($int_req - $int_rec,0) * $float_percent);
	}

	else
	{ //einfache Berechnung x% der vorhandenen Erfahrung
		$expplus=round($session['user']['experience'] * $float_percent);
	}

	return($expplus);
}
?>