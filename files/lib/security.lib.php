<?php
/**
* security.lib.php: Funktionsbibliothek für Methoden, die diversen Sicherheitszwecken dienen
* @author LOGD-Core / Drachenserver-Team
* @version DS-E V/2
*/

/**
* @author talion
* @desc Massakriert Cheater und "Hacker" ; )
*/
function kill_cheater () {

	global $access_control,$Char;

	//Echte Superuser und Superuserchars (zu erkennen am ID_Switch Feld) werden nicht massakriert!
	if($access_control->su_lvl_check(1) == false && $Char->superuser_id_switch == 0) 
	{
		clearnav();
		$Char->output='';
		
		systemlog(' - HACKVERSUCH -`n
				Seite: '.$_SERVER['REQUEST_URI'].'`n
				Referer: '.$_SERVER['HTTP_REFERER'],$Char->acctid);
	
	
		echo('Für den Versuch, die Götter zu betrügen und dich in Adminseiten einzuschleichen, wurdest du niedergeschmettert!<br /><br />');
		echo('Ramius, der Gott der Toten, erscheint dir in einer Vision. Er erklärt dir, dass du 25% deiner Erfahrung und alle deine Gefallen verloren hast.<br /><br />');
	
		$Char->hitpoints=0;
		$Char->alive=0;
		$Char->soulpoints=0;
		$Char->gravefights=0;
		$Char->deathpower=0;
		$Char->experience*=0.75;	
		
		saveuser();

		// Talion: Mail an alle Superuser mal sowas von unnötig, da eh Log geschrieben wird.
		/*
		$sql = 'SELECT acctid FROM accounts WHERE superuser>0';
		$result = db_query($sql);
		while ($row = db_fetch_assoc($result)) {
			systemmail($row['acctid'],'`#'.$Char->login.'`# bei Hackversuch ertappt','Böse(r), böse(r), böse(r) '.$Char->name.', du bist ein Hacker!');
		}
		*/
		exit();
	}
	else 
	{
		systemlog('Ein Grottenölmchen hat sich verirrt nach: '.calcreturnpath());
		redirect('superuser.php');
	}

}

/**
 * Atrahor-Colortags-sensitive utf8_htmlspecialchars:
 * Textformatierungstags werden nicht in utf8_htmlentities transformiert
 * (Bzw. diese Transformationen reversiert)
 * 
 * @see http://de2.php.net/manual/en/function.utf8_htmlspecialchars.php
 */
function ctag_htmlspecialchars($string, $quote_style = ENT_COMPAT) {
	
	// color code => info array
	$arr_tags = array_keys(get_appoencode());
	
	$arr_ents = get_html_translation_table(HTML_SPECIALCHARS);
	
	$arr_search = array_intersect(array_keys($arr_ents),$arr_tags);
			
	$string = utf8_htmlspecialchars($string,$quote_style);
	
	$size = sizeof($arr_search);
	if(0 == $size) {
		return $string;
	}
	
	$arr_replace = array();
	
	foreach($arr_search as $key=>$tag) {
		// replace e.g.: `&amp; -> `&
		$arr_replace[$key] 	= '`' . $tag;
		$arr_search[$key] 	= '`' . $arr_ents[$tag];
	}
	
	return str_replace($arr_search,$arr_replace,$string);
			
}


/**
* @desc return a given parameter which has been checked and altered in order not
* to be dangerous for SQL Queries
* @param string the parameter
* @param bool remove html tags
* @param bool remove sql commands
* @param bool remove html special chars
* @return returns the corrected parameter or false if the parameter was empty, else true
*/
function mixed_check_parameter($str_parameter, $bool_remove_tags = true, $bool_remove_sql = true,
$bool_no_html_special_chars = true)
{
	if($str_parameter == null)
	{
		return false;
	}
	if($str_parameter == '')
	{
		return true;
	}

	if (get_magic_quotes_gpc())
	{
		$str_parameter = stripslashes($str_parameter);
	}

	$str_parameter = addslashes($str_parameter);
	if($bool_remove_tags == true)
	{
		$str_parameter = strip_tags($str_parameter);
	}
	if($bool_no_html_special_chars == true)
	{
		$str_parameter = utf8_htmlentities($str_parameter);
	}
	//Not fully functional right now, dos not do anything
	//Is planned to remove SQL statements by a regular expression
	if($bool_remove_sql == true)
	{
		$str_regex = '#((select.*from.*(where)?.*)|(insert.*into.*values.*)|'.
		'(delete.*from.*|create.*(table|database)))#iu';

		//Remove what was defined in the regular expression above
		$str_parameter = utf8_preg_replace($str_regex, '',$str_parameter);
	}
	//Return the cleaned parameter
	return $str_parameter;
}

// Konstantendefs für check_blacklist
define('BLACKLIST_LOGIN',1);
define('BLACKLIST_TITLE',2);
define('BLACKLIST_EMAIL',4);

/**
* @desc Prüft, ob ein bestimmter Wert in der Blacklist vorhanden ist
* @param int Blacklist-Typ; Angabe mit obigen Flags und bitweiser ODER-Verknüpfung
* @param string Wert, auf den geprüft werden soll
* @return true, wenn Eintrag auf Blacklist besteht, sonst false
* @author talion
*/
function check_blacklist ($int_type, $str_val)
{

	$str_where = '';

	// Accents ersetzen
	$arr_srch = array( 'è','é','ê','à','á','â','ì','í','î','ò','ó','ô','ù','ú','û' );
	$arr_repl = array( 'e','e','e','a','a','a','i','i','i','o','o','o','u','u','u' );
	$str_val = str_replace($arr_srch, $arr_repl, $str_val);

	// Wortbestandteile ermitteln
	$arr_words = utf8_preg_split('/[_\W]|([A-Z](?:[a-z]+))/',$str_val,-1,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

	if(sizeof($arr_words) > 1) {
		foreach($arr_words as $w) {
			$str_where .= ' OR (LOWER(value) = "'.db_real_escape_string(mb_strtolower($w)).'") ';
		}
	}

	$sql = 'SELECT id FROM blacklist WHERE type & '.$int_type.'
			AND (
					(LOWER(value)="'.db_real_escape_string(mb_strtolower($str_val)).'")
					'.$str_where.'
				)
				 LIMIT 1';

	$res = db_query($sql);

	if(db_num_rows($res)) {

		return(true);

	}

	return(false);

}

/**
* @desc Prüft, ob ein Ban auf Account / PC gesetzt ist und Account diesen nicht übergeht.
*		Dazu können entweder die zu checkenden Daten direkt übergeben werden
*		Oder, falls keine davon gegeben ist: Wenn Acctid gegeben, verwendet Func
*		den damit adressierten Datensatz. Sonst: Infos aus Session / Server-Vars
* @param string Login-Filter
* @param string IP-Filter
* @param string ID-Filter
* @param string EMail-Filter
* @param int AccountID (Optional; Standard 0)
* @param bool Auf Index-Seite mit Fehlermeldung weiterleiten (Optional, Standard true)
* @return true, wenn Ban besteht, sonst false
* @author talion using LOGD-CORE code
*/
function checkban ($str_login = false, $str_ip = false, $str_id = false, $str_mail = false, $int_acctid = 0, $bool_errormsg = true)
{
	global $session,$SCRIPT_NAME;

	$bool_banoverride = $session['banoverride'];

	// Daten zusammenstellen
	// Wenn Account gegeben
	if($int_acctid > 0) {
		$sql = 'SELECT
						'.($str_ip===false ? 'lastip AS str_ip,':'').'
						'.($str_id===false ? 'uniqueid AS str_id,':'').'
						'.($str_mail===false ? 'emailaddress AS str_mail,':'').'
						'.($str_login===false ? 'login AS str_login,':'').'
						banoverride AS bool_banoverride
				 FROM accounts WHERE acctid='.$int_acctid;
		$res = db_query($sql);

		$arr_data = db_fetch_assoc($res);
		db_free_result($res);
		extract($arr_data);
	}
	// Wenn keine Daten unmittelbar übergeben, session-Daten übernehmen
	if($str_login === false && $str_ip === false && $str_id === false && $str_mail === false) {
		$str_login = (!empty($session['user']['login']) ? $session['user']['login'] : false);
		$str_ip = (!empty($session['user']['lastip']) ? $session['user']['lastip'] : $_SERVER['REMOTE_ADDR']);
		$str_id = (!empty($session['user']['uniqueid']) ? $session['user']['uniqueid'] : $_COOKIE['lgi']);
		$str_mail = (!empty($session['user']['emailaddress']) ? $session['user']['emailaddress'] : false);
	}

	if ($bool_banoverride) {
		return false;
	}

	// Wenn effektiv keine zu prüfende Bedingung vorhanden
	if(empty($str_ip) && empty($str_id) && empty($str_mail) && empty($str_login)) {
		return(false);
	}

	// Auf Ban prüfen
	$sql = 'SELECT * FROM bans WHERE
			(	 0
				'.($str_ip !== false ? 'OR ("'.$str_ip.'"=ipfilter AND ipfilter<>"") ' : '').'
				'.($str_id !== false ? 'OR (uniqueid="'.$str_id.'" AND uniqueid<>"") ' : '').'
				'.($str_mail !== false ? 'OR (mailfilter="'.db_real_escape_string($str_mail).'" AND mailfilter != "") ' : '').'
				'.($str_login !== false ? 'OR (LOWER(loginfilter)="'.db_real_escape_string(mb_strtolower($str_login)).'" AND loginfilter != "") ' : '').'
			)
			AND (banexpire="0000-00-00" OR banexpire>"'.date('Y-m-d').'")
			LIMIT 1';
	$result = db_query($sql);

	if (db_num_rows($result)>0)
	{

		if($bool_errormsg) {

			$row = db_fetch_assoc($result);

			$sql = 'UPDATE bans SET last_try = NOW() WHERE id='.$row['id'];
			db_query($sql);

			Atrahor::clearSession();
			$session['message'].='`n`4Du bist einer Verbannung zum Opfer gefallen:`n';

			$session['user']['lastip'] = $_SERVER['REMOTE_ADDR'];
			$session['user']['uniqueid'] = $id;

			$session['message'].=$row['banreason'];
			if ($row['banexpire']=='0000-00-00') {
				$session['message'].='`n  `$Die Verbannung ist permanent!`0';
			}
			if ($row['banexpire']!='0000-00-00') {
				$session['message'].='`n  `^Der Bann wird am '.strftime('%e. %B %Y',strtotime($row['banexpire'])).' aufgehoben `0';
			}
			$session['message'].='`n';

			$session['message'].='`n`4Wenn dir die Gründe unklar sind, kannst du mit einer Anfrage in einem höflichen Ton nach dem Grund fragen, aber gib deinen Charakternamen und eine Emailadresse an, sonst können wir keine Auskunft geben.';

			if($SCRIPT_NAME != 'index.php' && $SCRIPT_NAME != 'petition.php')
			{
				header('Location: index.php');
				exit();
			}
		}

		db_free_result($result);

		return(true);
	}

	return(false);
}

/**
* @author talion
* @desc Trägt einen Ban in die Datenbank ein und loggt davon betroffene User automatisch aus.
* @param int Accountid des Accounts, dessen Daten gebannt werden sollen. Überschreibt evtl.
	andere Parameter. Falls 0, müssen die anderen Parameter gegeben sein.
* @param string Bangrund.
* @param date Ablaufzeit.
* @param mixed Zu bannende IP (String). Wenn auf false, wird dieser Wert beim Ban nicht verwendet.
* @param mixed Zu bannende ID (String). Wenn auf false, wird dieser Wert beim Ban nicht verwendet.
* @param mixed Zu bannende Mailadresse (String). Wenn auf false, wird dieser Wert beim Ban nicht verwendet.
* @param mixed Zu bannender Login (String). Wenn auf false, wird dieser Wert beim Ban nicht verwendet.
* @return array Liste mit AccountIDs, die vom Ban betroffen sind. Bei Fehler: bool false. Setzt zusätzlich
*			session['error'] auf Grund für Abbruch.
*/
function setban($int_acctid,$str_reason,$date_expire,$str_ip=false,$str_id=false,$str_mail=false,$str_login=false)
{
	global $session;
	
	//Ist schon drin, muss nicht nochmal gebannt werden
	if(checkban($str_login,$str_ip,$str_id,$str_mail,$str_login,$int_acctid))
	{
		return false;
	}

	$int_acctid = (int)$int_acctid;
	$arr_data = array();
	$arr_users = array();
	$str_ids = '';
	$str_where = '';

	// Ist Ban zeitlich überhaupt konsistent?
	if(strtotime($date_expire) <= time() && $date_expire != '0000-00-00') {

		$session['error'] = 'setban_expire_invalid';
		return(false);

	}

	// einzutragende Daten ermitteln
	if($int_acctid > 0) {

		$sql = 'SELECT
						'.($str_ip!==false ? 'lastip AS str_ip,':'').'
						'.($str_id!==false ? 'uniqueid AS str_id,':'').'
						'.($str_mail!==false ? 'emailaddress AS str_mail,':'').'
						'.($str_login!==false ? 'login AS str_login,':'').'
						acctid AS int_acctid
				 FROM accounts WHERE acctid='.$int_acctid;
		$res = db_query($sql);

		// Gegebener Account existiert nicht
		if(!db_num_rows($res)) {

			$session['error'] = 'setban_account_notfound';
			return(false);

		}

		$arr_data = db_fetch_assoc($res);
		extract($arr_data);

	}

	if(empty($str_ip) && empty($str_id) && empty($str_mail) && empty($str_login)) {
		$session['error'] = 'setban_noconditions';
		return(false);
	}

	// User ermitteln, die der Ban betreffen könnte
	$sql = 'SELECT a.acctid,a.name FROM accounts a LEFT JOIN account_extra_info USING(acctid) WHERE
				banoverride = 0 AND (
				'.(!empty($str_ip) ? 'lastip = "'.db_real_escape_string($str_ip).'" OR ':'').'
				'.(!empty($str_id) ? 'uniqueid = "'.db_real_escape_string($str_id).'" OR ':'').'
				'.(!empty($str_mail) ? 'emailaddress = "'.db_real_escape_string($str_mail).'" OR ':'').'
				'.(!empty($str_login) ? 'login = "'.db_real_escape_string($str_login).'" OR ':'').'
				0)';
	$res = db_query($sql);

	while($a = db_fetch_assoc($res)) {
		$arr_users[$a['acctid']] = $a;
		$str_ids .= ','.$a['acctid'];
	}

	if(mb_strlen($str_ids) > 1) {

		// betroffene User ausloggen
		$bool_result = user_update(
			array
			(
				'loggedin'=>0,
				'where'=>'acctid IN ( -1'.$str_ids.' )'
			)
		);
		if( !$bool_result ) {

			$session['error'] = 'setban_account_logout_failed';
			return(false);

		}
		if( db_error(LINK) ) {

			$session['error'] = 'setban_account_logout_failed';
			return(false);

		}

	}
/* Funktioniert nicht oder nur wenn alle Bannfelder gesetzt sind. Beim Urlaubsmodus z.B. ist das nicht der Fall
	$sql = 'SELECT id FROM bans WHERE
		ipfilter="'.db_real_escape_string($str_ip).'" OR
		uniqueid="'.db_real_escape_string($str_id).'" OR
		loginfilter="'.db_real_escape_string(mb_strtolower($str_login)).'" OR
		mailfilter="'.db_real_escape_string($str_mail).'"';
	
	if(db_num_rows(db_query($sql)))
	{
		$session['error'] = 'Ein solcher Bann existiert bereits';
		return array();
	}
*/	
	$sql = 'INSERT INTO bans SET '
				.'banreason="'.$str_reason.'",'
				.'banexpire="'.$date_expire.'",'
				.'ipfilter="'.db_real_escape_string($str_ip).'",'
				.'uniqueid="'.db_real_escape_string($str_id).'",'
				.'loginfilter="'.db_real_escape_string(mb_strtolower($str_login)).'",'
				.'mailfilter="'.db_real_escape_string($str_mail).'"';
	if( !db_query($sql) || db_error(LINK) ) {
		$session['error'] = 'setban_insert_failed';
		return(false);
	}

	return($arr_users);

}

/**
* @author talion
* @desc Entfernt einen Ban aus der Datenbank.
* @param int BanID des Bans, der entfernt werden soll.
* @return array Liste mit AccountIDs, die von Entfernung des Bans betroffen sind. Bei Fehler: bool false. Setzt zusätzlich
*			session['error'] auf Grund für Abbruch.
*/
function delban($int_banid)
{
	global $session;

	$int_banid = (int)$int_banid;
	$arr_users = array();

	if($int_banid == 0) {

		$session['error'] = 'delban_ban_notfound';
		return(false);

	}

	// Ban abrufen
	$sql = 'SELECT ipfilter AS str_ip, uniqueid AS str_id, mailfilter AS str_mail, loginfilter AS str_login FROM bans WHERE id='.$int_banid;
	$res = db_query($sql);

	// Gegebener Ban existiert nicht
	if(!db_num_rows($res)) {

		$session['error'] = 'delban_ban_notfound';
		return(false);

	}

	$arr_data = db_fetch_assoc($res);
	extract($arr_data);

	// User ermitteln, die der Ban betreffen könnte
	$sql = 'SELECT a.acctid,a.login FROM accounts a LEFT JOIN account_extra_info USING(acctid) WHERE
				'.(!empty($str_ip) ? 'lastip = "'.db_real_escape_string($str_ip).'" OR ':'').'
				'.(!empty($str_id) ? 'uniqueid = "'.db_real_escape_string($str_id).'" OR ':'').'
				'.(!empty($str_mail) ? 'emailaddress = "'.db_real_escape_string($str_mail).'" OR ':'').'
				'.(!empty($str_login) ? 'login = "'.db_real_escape_string($str_login).'" OR ':'').'
				0';
	$res = db_query($sql);

	while($a = db_fetch_assoc($res)) {
		$arr_users[$a['acctid']] = $a;
	}

	$sql = 'DELETE FROM bans WHERE id='.$int_banid;
	if( !db_query($sql) || db_error(LINK) ) {
		$session['error'] = 'delban_delete_failed';
		return(false);
	}

	return($arr_users);

}


/**
*@desc 	Schreibt Eintrag ins Debuglog
*@param string Nachricht
*@param int Acctid des Ziels (Optional, Standard 0 = Kein Ziel)
*@param bool Wenn true, wird aktuelle IP und ID des Accounts mitgeloggt (Optional, Standard false)
*@author LOGD-Core
*/
function debuglog($message,$target=0,$bool_log_all=false)
{
	global $Char;

	$message = stripslashes($message);
	$message = db_real_escape_string($message);

	$sql = "
		INSERT INTO
			`debuglog`
		SET
			`date`		= NOW(),
			`actor`		= '".$Char->acctid."',
			`target`	= '".$target."',
			`message`	= '".$message."',
	";

	if($bool_log_all) {
		$sql .= "
			`ip`		= '".db_real_escape_string($Char->lastip)."',
			`uid`		= '".db_real_escape_string($Char->uniqueid)."'
		";
	}
	else
	{
		$sql .= "
			`ip`		= '',
			`uid`		= ''
		";
	}

	db_query($sql);
}

/**
*@desc 	Schreibt Eintrag ins Systemlog
*@param string Nachricht
*@param int Acctid des Urhebers (Optional, Standard 0 = System)
*@param int Acctid des Ziels (Optional, Standard 0 = Kein Ziel)
*@author talion
*/
function systemlog($message,$actor=0,$target=0)
{

	$message	= db_real_escape_string($message);
	$actor		= db_real_escape_string($actor);
	$target		= db_real_escape_string( is_null_or_empty($target)? 0 : $target);

	$sql = "
		INSERT INTO
			`syslog`
		SET
			`date` 		= NOW(),
			`actor`		= '".$actor."',
			`target`	= '".$target."',
			`message`	= '".$message."'
	";
	db_query($sql);

}

/**
*@desc Erzeugt aus LOGD-Adresse einen Link ohne für Rückkehrfunktionen störende Params
*@param string Zu bearbeitender Link (Optional; Wenn nicht gegeben: Aktuelle Seite)
*@author Original from the LOTGD.NET/MightyE, modded by Dasher for the Guilds/Clans Code, modded by talion for Drachenserver
*/
function calcreturnpath($ret='')
{
	//
	//  Work out the return url
	//  Allows functions to be called from different source URL's and promotes reuse
	//  Original from the LOTGD.NET/MightyE, modded by Dasher for the Guilds/Clans Code
	//

	$return = ($ret!='') ? $ret : $_SERVER['REQUEST_URI'];
	$return = utf8_preg_replace("'([?&]c=[[:digit:]-]*)|([?&]vital=[[:digit:]-]*)'",'',$return);
	$pos = mb_strrpos($return,'/');
	if($pos !== false) 
	{
		$return = mb_substr($return,$pos+1);
	}

	return($return);
}

/**
 * Registriert alle Variablen die als Parameter übergeben wurden als superglobale Variable
 *
 * @param array $arr_var Array die als superglobale Variable gesetzt werden soll
 */
function register_global(&$var)
{
	if(is_array($var))
	{
		foreach ($var as $key => $val)
		{
			global ${$key};
			${$key} = $val;
		}
	}
}


/**
 * Das Anti cheat Modul erkennt cheatende Spieler (hoffentlich)
 * Die Metode gibt true zurück, wenn der User versucht zu cheaten wird true zurückgegeben,
 * andernfalls false
 *
 * @param arr/int row Enthält einen Usereintrag oder acctid
 * @param bool debgmode Umgeht den Check für Debugzwecke (Optional, Standard false)
 * @return bool true wenn gecheated wurde, ansonsten false
 */
function ac_check($row,$debugmode=false)
{
	global $session;

	if($debugmode==true && $access_control->su_check(access_control::SU_RIGHT_DEBUG))
	{
		return(false);
	}

	if(!is_array($row))
	{ //falls kein Array sondern eine AccountID übergeben wurde
		$row=array('acctid'=>intval($row));
	}

	if (isset($row['acctid']))
	{
		if (!isset($row['uniqueid']))
		{
			$sql = 'SELECT uniqueid FROM accounts WHERE acctid = '.$row['acctid'];
			$result = db_query($sql);
			if (db_num_rows($result)>0)
			{
				$row = db_fetch_assoc($result);
			}
			else
			{
				return false;
			}
		}
		if ($session['user']['uniqueid'] == $row['uniqueid'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
?>