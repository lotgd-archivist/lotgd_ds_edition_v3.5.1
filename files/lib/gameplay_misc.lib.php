<?php
/**
 * Umschalten eines Bits
 *
 */
define('BIT_SWITCH', -1);
/**
 * MaxBit
 *
 */
define('BIT_ALL', ~0);
/**
 * Grenzt einen Wert ein gibt bei Überschreitung die obere oder die untere Grenze zurück
 *
 * @author Alucard
 * @param int $int_min
 * @param int $int_val
 * @param int $int_max
 * @return int
 */
function between( $int_min, $int_val, $int_max ){
	return min( $int_max, max($int_min, $int_val) );
}

/**
 * Überprüft ob ein numerischer Wert zwischen zwei anderen Werten liegt
 *
 * @author Dragonslayer
 * @param int $int_min
 * @param int $int_val
 * @param int $int_max
 * @return bool
 */
function isBetween( $int_min, $int_val, $int_max )
{
	$boolReturn = ($int_val >= $int_min && $int_val <= $int_max);
	return $boolReturn;
}

function getBit( $int_bit, $int_flag ){
	return $int_bit & $int_flag;
}

function setBit( $int_bit, &$int_flag, $int_val = BIT_SWITCH ){
	$r = (int)$int_flag;
	if( $int_val == BIT_SWITCH ){
		$int_flag = $r ^ $int_bit;
	}
	else{
		$int_flag = (int)$int_val ? $r|$int_bit : $r&(BIT_ALL^$int_bit);
	}
	return $int_flag;
}

function getBitBool($int_bit, $int_flag)
{
	return (getBit($int_bit, $int_flag)==$int_flag);
}

function getMAC($sIP = false)
{
	$sIP = ($sIP!=false)?$sIP:getenv("REMOTE_ADDR");

	exec("arp -a ".$sIP, $ret);
	foreach ( $ret as $line) {
		if (mb_strpos($line,$sIP) !== false)
		{
			$cols = explode(" ",$line);
			return trim($cols[3]);
		}
	}
}

/**
 * Fügt einen Array immer assoziativ-keys zusammen
 * (array_merge macht das bei numerischen Keys nicht!)
 *
 * @return array
 */
function array_merge_assoc(){
	$int_args 	= func_num_args();
	$arr_return = func_get_arg(0);
	for($int_i = 1;$int_i<$int_args;$int_i++)
	{
		$arr_arg = (array)func_get_arg($int_i);
		foreach ($arr_arg as $key => $val) {
			$arr_return[ $key ] = $val;
		}
	}
	return is_array($arr_return) ? $arr_return : array();
}

/**
 * Nimmt beliebig viele Parameter entgegen und fügt diese in einem Array zusammen
 * WICHTIG: Die Reihenfolge ist wichtig. Wenn in Param-n der gleiche Arrayschlüssel vorhanden ist wie
 * in Paramn+1, so wird der Wert des Schlüssels von Param-n mit dem Wert des Schlüssels aus Param-n+1
 * überschrieben.
 * @return Array ein Array der aus allen übergebenen Elementen besteht
 */
function adv_array_merge()
{
	$int_args = func_num_args();
	$arr_return = array();
	for($int_i = 0;$int_i<$int_args;$int_i++)
	{
		$arr_arg_x = func_get_arg($int_i);
		if(	is_bool($arr_arg_x) == true ||
			is_null($arr_arg_x) == true)
		{
			continue;
		}
		else
		{
			$arr_return = array_merge($arr_return,(array)$arr_arg_x);
		}
	}
	return $arr_return;
}

/**
 * Deserialisiert eine Menge an übergebenen Werten in einen Array.
 * Wenn die Werte nicht deserialisierbar sind wird ein leerer Array zurückgegeben.
 * Wenn ein Array übergeben wird, wird er zurückgegeben.
 * @return array
 */
function adv_unserialize()
{
	$int_args = func_num_args();
	$arr_return = array();
	for($int_i = 0;$int_i<$int_args;$int_i++)
	{
		//Argument holen
		$arg_x = func_get_arg($int_i);

		//Wenn schon deserialisiert
		if (is_array($arg_x)) {
			//Arrays zusammenführen
			$arr_return = array_merge($arr_return,(array)$arg_x);
			continue;
		}

		// deserialisieren
		$arr_arg_x = utf8_unserialize($arg_x);

		//War das Argument nicht deserialisierbar wird false zurückgegeben
		if(	$arr_arg_x === false )
		{
			continue;
		}
		else
		{
			//Arrays zusammenführen
			$arr_return = array_merge($arr_return,(array)$arr_arg_x);
		}
	}
	return $arr_return;
}

function adv_empty($var)
{
	if(is_array($var))
	{
     	if(count($var) == 0)
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
		$string = trim($var);
		if(!is_numeric($var))
		{
			return empty($var);
		}
		return false;
	}
}

/**
 * überprüft ob eine variable gesetzt, null oder leer ist
 * (aus C# übernommen)
 * @param mixed $var zu überprüfender Wert
 * @return bool
 */
function is_null_or_empty($var)
{
	return
		(
		isset($var) 	== false ||
		is_null($var)	== true ||
		adv_empty($var) 	== true ||
		$var 				== ''
		) &&
		(is_numeric($var) == false );
}

/**
 * Setzt den den Standardspeicherort für Messages auf den vorgegebenen Stringwert
 * @param String $str_message Ausgabenachricht
 */
function setStatusMessage($str_message)
{
	Atrahor::$Session['message'][] = $str_message;
}

/**
 * Gibt die aktuell vorhandene Statusnachricht aus und löscht diese danach!
 * @return string
 */
function getStatusMessage()
{
	if(is_null_or_empty(Atrahor::$Session['message']) == false)
	{
		if(is_array(Atrahor::$Session['message']) && count(Atrahor::$Session['message']) > 0)
		{
			$str_msg = join('`n',Atrahor::$Session['message']);
		}
		else
		{
			$str_msg = Atrahor::$Session['message'];
		}
		unset(Atrahor::$Session['message']);
		return '`c`b`$'.$str_msg.'`0`b`c`n';

	}
	return '';
}

/**
 * Wrapper für die Levenshtein Funktion. Hebt die Limitierung von PHP auf nur Strings bis 255 zu vergleichen
 *
 * @param string $str_left
 * @param string $str_right
 * @param int $int_cost_ins
 * @param int $int_cost_rep
 * @param int $int_cost_del
 * @return int
 */
function adv_levenshtein($str_left, $str_right, $int_cost_ins = null, $int_cost_rep = null, $int_cost_del = null)
{
	//Stringlänge von beiden liegt unter der PHP Limitierung
	if(mb_strlen($str_left) <= 255 && mb_strlen($str_right) <= 255)
	{
		return utf8_levenshtein($str_left, $str_right, $int_cost_ins, $int_cost_rep, $int_cost_del);
	}

	$arr_left = utf8_str_split($str_left,255);
	$arr_right = utf8_str_split($str_right,255);

	$int_desc_cost = 0;
	for ($i = 0; $i < max(count($arr_left),count($arr_right)) ; $i++)
	{
		if(!isset($arr_right[$i]))
		{
			$int_desc_cost += mb_strlen($arr_left[$i])*$int_cost_del;
		}
		elseif(!isset($arr_left[$i]))
		{
			$int_desc_cost += mb_strlen($arr_right[$i])*$int_cost_ins;
		}
		else
		{
			$int_desc_cost += utf8_levenshtein($arr_left[$i],$arr_right[$i]);
		}
	}
	
	//bathi wär hat return vergessen??? ;)
	
	return $int_desc_cost;
}

/**
 * Erstellt bei Bedarf eine Semaphore. Wenn diese schon existiert, dann blockiert
 * der Thread bis diese wieder freigegeben wurde.
 * Muss Vor einem Codeblock ausgeführt werden, der exklusiv ausgeführt werden muss
 *
 * @param int $sem_key Semaphoren Key
 * @param int $int_max_connections Maximale Anzahl an gleichzeitigen Verbindungen
 * @return mixed Prozesshandle oder false bei Fehler
 */
function enter_mutex_code($sem_key = 0,$int_max_connections = 1)
{
	if(!function_exists('sem_get'));
	{
		return false;
	}
    /** @noinspection PhpUnreachableStatementInspection */
    $sem_key = (int)$sem_key;
	$sem_id = sem_get($sem_key,$int_max_connections,null,1);

	//Blockierung bis Semaphore freigegeben ist
	if(sem_acquire($sem_id) == true)
	{
		return $sem_id;
	}
	else
	{
		return false;
	}
}

/**
 * Freigabe einer Semaphore nach dem kritischen Codeabschnitt
 *
 * @param handle $sem_id Prozesshandle NICHT der Semaphore Key
 * @return bool true bei Erfolg, false bei Misserfolg
 */
function exit_mutex_code($sem_id)
{
	if(!function_exists('sem_get'));
	{
		return false;
	}
    /** @noinspection PhpUnreachableStatementInspection */
    if($sem_id != false)
	{
		return sem_release($sem_id);
	}
	else
	{
		return true;
	}
}

/**
 * Liefert die max. LP-Zahl zurück, die ein Spieler bei gegebenem Stand haben dürfte
 *
 * @param array Assoz. Array mit Spielerinfos (dragonkills,level,dragonpoints); optional, standard session['user']
 * @return int Max. LP
 */
function get_max_hp ($arr_data=array())
{
	global $session;

	if(empty($arr_data)) {
		$arr_data = $session['user'];
	}

	if(!is_array($arr_data['dragonpoints'])) {
		$arr_data['dragonpoints'] = array();
		if(!empty($arr_data['dragonpoints'])) {
			$arr_data['dragonpoints'] = utf8_unserialize($arr_data['dragonpoints']);
		}
	}

	$int_dkhp=0;
	foreach($session['user']['dragonpoints'] as $val)
	{
		if ($val=='hp') {
			$int_dkhp+=5;
		}
	}

	$int_maxhp = getsetting('limithp',0) * $arr_data['dragonkills'] + 12 * $arr_data['level'] + $int_dkhp;

	return($int_maxhp);
}

/**
 * Gibt die für den jeweils nächsten Level benötigte Erfahrung zurück
 *
 * @param int $lvl Aktueller Level
 * @param int $dks Anzahl DKs
 * @param bool $autochallenge Auf Auto-Herausforderungsmerkmal prüfen?
 * @return int Ben. Erfahrungspunkte
 */
function get_exp_required
 ($lvl,$dks,$autochallenge=false)
{
	$exparray=array(0=>0,1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
	if($autochallenge)
	{
		$exparray[$lvl]= round($exparray[$lvl+1] + ($dks * min(0.18+$lvl*0.015,0.3) ) * $lvl * 100,0);
	}
	else
	{
		$exparray[$lvl]= round($exparray[$lvl] + ($dks * min(0.18+$lvl*0.015,0.3) ) * $lvl * 100,0);
	}
	return($exparray[$lvl]);
}
/* Originalcode, berechnet das komplette Array. Totaler Overhead weil für jeden Seitenaufruf schon für die Vitalinfo 2 Funktionsaufrufe nötig sind!!
function get_exp_required ($lvl,$dks,$autochallenge=false)
{
	$exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
	foreach ($exparray as $key => $val)
	{
		$exparray[$key]= round($val + ($dks * min(0.18+$lvl*0.015,0.3) ) * $lvl * 100,0);
	}
	if($autochallenge)
	{
		$lvl++;
	}
	return($exparray[$lvl]);
}
*/

/**
* @author talion
* @desc Steigert das Level des Spielers. Erledigt alle damit verbundenen Änderungen.
* @return int Neuen Level.
*/
function increment_level ()
{
	global $session;
	$str_output = '';

	$session['user']['level']++;
	$session['user']['maxhitpoints']+=10;
	$session['user']['soulpoints']+=5;
	$session['user']['attack']++;
	$session['user']['defence']++;
	$session['user']['seenmaster']=0;
	$session['user']['reputation']+=3;

	if($session['user']['balance_forest'] > 0) {	// Derzeit schwer
		// Balance nur erleichtern, wenn Spieler nicht ohnehin sehr schnell
		if( ($session['user']['age'] / $session['user']['level']) > 1) {
			// halbieren
			$session['user']['balance_forest'] = round($session['user']['balance_forest']*0.5);
		}
	}
	else {	// derzeit leicht
		// auf jeden Fall halbieren
		$session['user']['balance_forest'] = round($session['user']['balance_forest']*0.5);
	}

	$str_output .= "`n`#Du steigst auf zu Level `^".$session['user']['level']."`#!`n";
	$str_output .= "Deine maximalen Lebenspunkte sind jetzt `^".$session['user']['maxhitpoints']."`#!`n";
	$str_output .= "Du bekommst einen Angriffspunkt dazu!`n";
	$str_output .= "Du bekommst einen Verteidigungspunkt dazu!`n";
	if ($session['user']['level']<15)
	{
		$str_output .= "Du hast jetzt einen neuen Meister.`n";
	}
	else
	{
		$str_output .= "Keiner im Land ist mächtiger als du!`n";
	}

	$rowe = user_get_aei('referer,refererawarded');

	if ($rowe['referer']>0 && $session['user']['level']>=getsetting('refererminlvl',5) && $session['user']['dragonkills']>=getsetting('referermindk','0') && $rowe['refererawarded']<1){
		$dp = getsetting('refererdp',50);

		user_update(
			array
			(
				'donation'=>array('sql'=>true,'value'=>'donation+'.$dp)
			),
			$rowe['referer']
		);

		user_set_aei(array('refererawarded'=>1));

		systemmail($rowe['referer'],"`%Eine deiner Anwerbungen hat's geschafft!`0","`%{$session['user']['name']}`# ist auf Level `^{$session['user']['level']}`# aufgestiegen und du hast deine `^".$dp."`# Punkte bekommen!");
	}
	if ($session['user']['level']==10){
		$session['user']['donation']+=1;
	}

	output($str_output);

	increment_specialty();

	return($session['user']['level']);

}

/**
* @author talion
* @desc Holt Flirtstatus zwischen zwei Spielern aus Datenbank.
* @param int AccountID des einen Partners
* @param int AccountID des zweiten Partners
* @return mixed SQL-Result (bzw. false wenns schiefgeht)
*/
function flirt_get ($int_acctid1,$int_acctid2) {

	$int_acctid1 = (int)$int_acctid1;
	$int_acctid2 = (int)$int_acctid2;

	$str_ids = $int_acctid1.','.$int_acctid2;
	$str_where = 'acctid1 IN ('.$str_ids.')'. ( $int_acctid2 > 0 ? ' AND acctid2 IN ('.$str_ids.') AND acctid1!=acctid2' : '');

	$sql = 'SELECT * FROM flirts WHERE
				'.$str_where;
	$res = db_query($sql);

	if(db_error(LINK) || !db_num_rows($res)) {
		return(false);
	}

	return( $res );

}

/**
* @author talion
* @desc Verändert Flirtstatus zwischen zwei Spielern bzw. entfernt Beziehung.
*		FlirtID o. AccountIDs müssen gegeben sein!
* @param int FlirtID
* @param int AccountID des einen Partners
* @param int AccountID des zweiten Partners
* @param int Beziehungsstatus: -1 um Eintrag zu entfernen, 0 für unverändert
* @param int Flirtcount
* @return mixed false wenn's schiefgeht, true wenn's entfernt wurde, sonst FlirtID
*/
function flirt_set ($int_flirtid,$int_acctid1,$int_acctid2,$int_state,$int_count) {

	$int_flirtid = (int)$int_flirtid;
	$int_acctid1 = (int)$int_acctid1;
	$int_acctid2 = (int)$int_acctid2;
	$int_state = (int)$int_state;
	$int_count = (int)$int_count;

	if($int_flirtid) {
		$str_where = ' flirtid='.$int_flirtid;
	}
	else {

		if($int_acctid1 == 0 && $int_acctid2 == 0) {
			return(false);
		}
		$str_ids = $int_acctid1.','.$int_acctid2;
		$str_where = 'acctid1 IN ('.$str_ids.')'. ( $int_acctid2 > 0 ? ' AND acctid2 IN ('.$str_ids.') AND acctid1!=acctid2' : '');

	}

	// Entfernen
	if($int_state == -1) {
		$sql = 'DELETE FROM flirts WHERE '.$str_where;
		db_query($sql);
		if(!db_affected_rows()) {
			return(false);
		}
		return(true);
	}

	// Schauen, ob schon ein solcher Eintrag existiert
	// (Wenn keine Flirtid gegeben, sonst ist es logisch)
	if($int_flirtid == 0) {
		$arr_flirt = flirt_get($int_acctid1,$int_acctid2);
	}
	else {
		$arr_flirt = array('flirtid'=>$int_flirtid);
	}

	if($arr_flirt['flirtid'] > 0) {

		// Keine Änderung nötig?
		if($arr_flirt['flirtstate'] == $int_state) {
			return($arr_flirt['flirtid']);
		}

		$sql = 'UPDATE ';
	}
	else {
		$sql = 'INSERT INTO ';
	}
	$sql .= ' flirts SET flirtid=flirtid,'.($int_state > 0 ? 'flirtstate='.$int_state.'flirtcount='.$int_count : '');
	if($arr_flirt['flirtid'] > 0) {
		$sql .= ' WHERE flirtid='.$arr_flirt['flirtid'];
	}
	else {
		$sql .= ',acctid1='.$int_acctid1. ($int_acctid2 > 0 ? ',acctid2='.$int_acctid2 : '');
	}

	db_query($sql);

	if(db_error(LINK)) {
		return(false);
	}

	$int_result = ($arr_flirt['flirtid'] > 0 ? $arr_flirt['flirtid'] : db_insert_id());

	return( $int_result );

}

// Konstantendefs für Verhalten der flirt-Logik
define('FLIRT_AFFIANCE',1);			// Verlobung auslösen?
define('FLIRT_WEDDING',2);			// Heirat auslösen?
define('FLIRT_ENGAGED_DIVORCE',2);	// Bei Fremdflirt in verlobtem Status:

/**
* @author talion
* @desc Erledigt Flirtlogik: Heirat etc.
* @param int AccountID des Ziels
* @param int Verhalten,
* @return mixed false wenn's schiefgeht, true wenn's entfernt wurde, sonst FlirtID
*/


/**
* @author talion
* @desc Lädt Rasse aus DB in Session, falls nötig (sprich: noch nicht in Session existent).
* @param string ID der Rasse. Wenn == Spielerrasse, wird Session verwendet und Spielerrasse gesetzt.
* @param bool Wenn true, wird Array in Session auf jeden Fall überschrieben (Optional, Standard false)
* @return array Array mit Rasse
*/
function race_get ($str_race,$bool_forcereload=false) {

	global $session;

	$str_race = stripslashes($str_race);

	if(empty($str_race)) { return(array()); }

	$bool_playerrace = ($str_race == $session['user']['race'] ? true : false);
	$arr_race = array();
	$mixed_cache = Cache::get(Cache::CACHE_TYPE_SESSION, 'playerrace');

	if(false === $mixed_cache || !$bool_playerrace || $bool_forcereload) {
		$sql = 'SELECT * FROM races WHERE id="'.db_real_escape_string($str_race).'"';
		$result = db_query($sql);

		if (db_num_rows($result)>0){
			$arr_race = db_fetch_assoc($result);
			$arr_race['boni'] = utf8_unserialize($arr_race['boni']);
			$arr_race['specboni'] = utf8_unserialize($arr_race['specboni']);
		}

		if($bool_playerrace) {

			$mixed_cache = $arr_race;
			Cache::set(Cache::CACHE_TYPE_SESSION, 'playerrace', $mixed_cache);

		}
		else {
			return($arr_race);
		}

	}

	// Hier steht fest, dass wir Playerrace haben
	return($mixed_cache);

}

/**
* @author talion
* @desc Setzt Boni aktueller Rasse auf Spieler bzw. entfernt diese
* @param bool Wenn true, werden permanente Boni gesetzt, sonst temp. (Newday)
* @param bool Wenn true, werden die Boni abgenommen
* @param array Array mit Userdaten, auf die Boni angewendet werden.
*/
function race_set_boni ($bool_perm,$bool_off,&$arr_data) {

	if(sizeof($arr_data) == 0) {
		return;
	}

	if(empty($arr_data['race'])) {
		return;
	}

	$arr_race = race_get($arr_data['race']);

	$int_sign = $bool_off ? -1 : 1;

	if($bool_perm) {

		$arr_data['attack'] = max($arr_data['attack']+($arr_race['boni']['attack']*$int_sign),0);
		$arr_data['defence'] = max($arr_data['defence']+($arr_race['boni']['defence']*$int_sign),0);
		$arr_data['maxhitpoints'] = max($arr_data['maxhitpoints']+($arr_race['boni']['maxhitpoints']*$int_sign),9);

	}
	else {

		$arr_data['turns'] = max($arr_data['turns']+($arr_race['boni']['turns']*$int_sign),1);
		$arr_data['castleturns'] = max($arr_data['castleturns']+($arr_race['boni']['castleturns']*$int_sign),0);
		if($arr_data['spirits'] >= -2) {
			$arr_data['spirits'] = max($arr_data['spirits']+($arr_race['boni']['spirits']*$int_sign),-2);
			$arr_data['spirits'] = min($arr_data['spirits'],2);
		}

		if(is_array($arr_race['specboni']) && sizeof($arr_race['specboni']) > 0) {

			foreach ($arr_race['specboni'] as $k=>$v) {
				$arr_data['specialtyuses'][$k.'uses'] = max($arr_data['specialtyuses'][$k.'uses']+($v*$int_sign),0);

			}
		}
	}
}

/**
 * Setzt das Template für einen User.
 *
 * @param string $str_overwrite_template Templatename (Ordner im Template Verzeichnis)
 * @todo TMP Fix entfernen wenn alle user auf das neue Template System umgestellt wurden
 */
function define_template($str_overwrite_template = '')
{
	//globale $template-Variable Wichtig für den loadtemplate())-Aufruf unten -____-'
	global $BOOL_JS_HTTP_REQUEST, $session, $template;

	// Templatekram nur ausführen, wenn kein HTTP-Request
	if(!$BOOL_JS_HTTP_REQUEST) {

		// TEMPLATE setzen
		if(!empty($str_overwrite_template)) {
			$session['user']['prefs']['template'] = $str_overwrite_template;
		}
		if (!empty($session['user']['prefs']['template']))
		{
			//Temporärer fix bis alle user auf das neue Templatesystem umgestellt wurden
			$session['user']['prefs']['template'] = basename($session['user']['prefs']['template'],'.htm');
			utf8_setcookie("template",0,time()-42000);
			$_COOKIE['template']=$session['user']['prefs']['template'];
		}
		if (!empty($_COOKIE['template']))
		{
			$templatename=$_COOKIE['template'];
		}
		if (empty($templatename) || !file_exists(TEMPLATE_PATH.$templatename.'/tpl.php'))
		{
			$templatename=getsetting('defaultskin','dragonslayer_1');
		}
		$session['user']['prefs']['template'] = $templatename;

        //todo mobile erkennung + deak cookie
        if($_SERVER['REMOTE_ADDR']=='......')
        {
            $session['user']['prefs']['template'] = 'mobil';
            $templatename = 'mobil';
        }

		// TEMPLATE laden
		loadtemplate($templatename);
	}
}
?>
