<?php
/**
 * @author Alucard <alucard@atrahor.de>
 */

/**
 * Klasse für den Charakter
 * @author Alucard, changed by Bathory
 */
class CCharacter {
	
	const 	TABLE_NAME						= 'accounts';
	
	
	const 	 NEW_USER						= -23;
	
	/**
	 * Stealthmodus aktiviert
	 *
	 */
	const 	 USER_ACTIVATED_STEALTH			= 42;
	/**
	 * geknebelter Spieler
	 *
	 */
	const 	 USER_ACTIVATED_MUTE			= 200;
	/**
	 * automatisch geknebelter Spieler
	 *
	 */
	const 	 USER_ACTIVATED_MUTE_AUTO		= 201;
	const 	 USER_ACTIVATED_VACATION		= 100;
	/**
	 * "Wir vermissen dich"- Nachricht geschickt
	 *
	 */
	const 	 USER_ACTIVATED_SENTNOTICE		= 11;
	const 	 USER_ACTIVATED_FIRSTINFO		= 2;
	/**
	 * Defaultvalue
	 *
	 */
	const 	 USER_ACTIVATED_DEFAULT			= 1;
	
	/**
	 * Der angegebene Parameter $int_losegold ist ein absoluter Wert
	 * @see CCharacter::kill
	 *
	 */
	const 	LOSEGOLD_VALUE					= 1;
	/**
	 * Der angegebene Parameter $int_loseexp ist ein absoluter Wert
	 * @see CCharacter::kill
	 *
	 */
	const 	LOSEEXP_VALUE					= 2;
	/**
	 * Geschlecht männlich
	 * @see CCharacter::sex
	 *
	 */
	const 	SEX_MALE						= 0;
	/**
	 * Geschlecht weiblich
	 * @see CCharacter::sex
	 *
	 */
	const  	SEX_FEMALE						= 1;
	
	/**
	 * Geschlecht undefiniert oder leer
	 * @see CCharacter::sex
	 * 
	 */
	const 	SEX_UNDEF						= -1;
	
	/**
	 * Suchmodus zur Benutzer es wird eine "=" Abfrage durchgeführt
	 */
	const	SEARCH_EXACT					= 1;
	/**
	 * Suchmodus zur Benutzer es wird eine "LIKE" Abfrage durchgeführt
	 */
	const	SEARCH_LIKE						= 2;
	/**
	 * Suchmodus zur Benutzer es wird eine "%L%I%K%E%" Abfrage durchgeführt
	 */
	const	SEARCH_LIKE_EXT					= 4;
	/**
	 * Suchmodus zur Benutzer es wird eine SOUNDEX Abfrage durchgeführt
	 */
	const	SEARCH_SOUNDEX					= 8;
	
	/**
	 * Erweitert die SOUNDEX Suche um eine Levenshtein Abfrage
	 */
	const	SEARCH_FUZZY					= 16;

	/**
	 * Referenz auf geladenen Daten (beim Spieler selbst -> $session['user'])
	 *
	 * @var array
	 */
	protected $arr_data 			= array();
	
	/**
	 * Kopie der Daten, die später zum Abgleich benutzt wird
	 * 
	 * @var array
	 */
	protected $arr_dataCopy			= array();
	
	/**
	 * Aliasse für bestimmte Werte
	 * Form: 'alias' => 'name'
	 *
	 * @var array
	 */
	private static $arr_aliases 	= array(
		// Bitte alphabetisch sortieren
		'id'				=> 'acctid'	
	);
	
	/**
	 * Diese Werte können negativ sein
	 *
	 * @var array
	 */
	private static $arr_negativeValues = array(
		// Bitte alphabetisch sortieren
		'balance_dragon'	=> true,
		'balance_forest'	=> true,
		'chat_status'		=> true,
		'conf_bits'			=> true,
		'gemsinbank'		=> true,
		'goldinbank'		=> true,
		'imprisoned'		=> true,
		'newday_bits'		=> true,
		'reputation'		=> true,
		'spirits'			=> true,
		'race'				=> true,
		'sex'				=> true		
	);
	
	/**
	 * Diese Werte dürfen nicht überschrieben werden
	 *
	 * @var array
	 */
	private static $arr_noOverwrite	= array(
		'acctid'			=> true
	);
	
	/**
	 * Liste von Arraykeys, die serialisiert gespeichert sind
	 *
	 * @var array
	 */
	private $arr_serialized	= array(
		//Bitte alphabetisch sortieren
		'allowednavs'		=> true,
		'bufflist'			=> true,
		'dragonpoints'		=> true,
		'prefs'				=> true,
		'plu_mi'			=> true,
        'quests_temp'   	=> true,
		'specialtyuses'		=> true,
		'surights'			=> true	
	);
	
	
	private $int_maxReputation		= -1337;

	/**
	 * Rechte
	 *
	 * @var array
	 */
	public $rights					= null;
	
	private $bool_isPlayer			= false;
	private $bool_noSave			= true;

    private $multis = null;
	
	/**
	 * Konstruktor
	 *
	 * @param int $int_acctid
	 * @param bool $bool_player ist der Spieler der aktuell spielende ($session) oder ein anderer
	 */
	public function __construct( $int_acctid=0, $bool_player=false ){
		//global $session;
		
		$int_acctid 			= (int)$int_acctid;
		$this->bool_isPlayer 	= $bool_player;
		$bool_found 			= false;
		
		if( !$int_acctid ){
			return;
		}
		
		// User aus dem Speicher laden falls vorhanden
		$arr_data = false; //Cache::get(Cache::CACHE_TYPE_MEMORY, 'user_data_'.$int_acctid );
	
		$old_time = strtotime( $arr_data['laston'] );
		$new_time = time() - getsetting( 'LOGINTIMEOUT', 900 );
				
		if( $arr_data === false || !is_array($arr_data) || $old_time < $new_time ){
			// User stattdessen aus der DB laden
			$sql 		= 'SELECT * FROM '.self::TABLE_NAME.' WHERE acctid = '.$int_acctid;
			$result 	= db_query( $sql, false );
			
			if( db_num_rows( $result ) > 0 ){
				$bool_found 	= true;
				$arr_data 		= db_fetch_assoc( $result );
			}
			
			db_free_result( $result );
		}
		else{
			$bool_found = true;
		}
		
		// Account vorhanden
		if( $bool_found == true ){
			$this->initFrom( $arr_data, false );
		}
		// Account nicht gefunden
		else{
			if( $this->bool_isPlayer ){
				Atrahor::clearSession();
				Atrahor::$Session['message'] = '`4Fehler! Dein Login war falsch.`0';
				redirect('index.php', 'Account verschwunden!');
			}
			throw new Exception("Spieler $int_acctid konnte nicht geladen werden!");
		}
	}
	
	
	public function initFrom( &$arr_data, $bool_noSave=true ){
		
		$this->bool_noSave   = $bool_noSave;
		
		// Daten übernehmen
		$this->arr_data = $arr_data;
		
		if( !$bool_noSave ){
			// Array mit Kopie der Vars anlegen
			$this->createDataCopy();
			
		}
		else{
			$this->bool_isPlayer = false;
		}
	

		if( $this->bool_isPlayer ){
			// Reinladen
			Atrahor::$Session['user'] =& $this->arr_data;
		
			// serialisierte Daten laden
			$this->loadSerialized('dragonpoints');
			$this->loadSerialized('prefs');
			$this->loadSerialized('specialtyuses');
			$this->loadSerialized('allowednavs');
			$this->loadSerialized('plu_mi');
			$this->loadSerialized('bufflist');
		}

		// Spezialrechte laden
		$this->loadRights();
		
		// ICH
		if( $this->bool_isPlayer ){
			// lebendig oder tot
			$this->alive = (bool)($this->hitpoints > 0);
			
			// Wenn Benachrichtigung wegen Accountverfall an User geschickt wurde, Status zurücksetzen
			/**
			 * @todo Auslagern in login.php
			 */
			if ( $this->activated == CCharacter::USER_ACTIVATED_SENTNOTICE ){
				$this->activated = CCharacter::USER_ACTIVATED_DEFAULT;
				systemlog('`@Login nach langer Abwesenheit', $this->acctid);
			}
			
			// DDL-location bei jedem Klick resetten
			$this->ddl_location = 0;
			
			// Buffs
			Atrahor::$Session['bufflist'] 	 = $this->bufflist;

			Atrahor::$Session['allowednavs'] = $this->allowednavs;
		
			// RP-Wiedererweckung
			if( $this->spirits == RP_RESURRECTION ){
				$this->turns 			= 0;
				$this->castleturns 		= 0;
				$this->playerfights 	= 0;
				$this->fedmount 		= 1;
				$this->seenmaster 		= 2;
				$this->seendragon 		= 1;
				$this->seenlover 		= 1;
				Atrahor::$Session['bufflist'] 	= array();
				$this->hitpoints 		= min($this->hitpoints, 1);
			}
			$this->uniqueid 	= Atrahor::$Session['uniqueid'];
			$this->lastip		= Atrahor::$Session['lastip'];
			$this->reputation 	= min($this->reputation, $this->getMaxReputation());
			$this->charm 		= max($this->charm, 0);
			$this->attack 		= max($this->attack, 1);
			$this->defence 		= max($this->defence, 1);
			// Tier laden
			getmount( $this->hashorse );
		}
	}

    public function __isset($str_name) {
        return array_key_exists($str_name,$this->arr_data);
    }

    public function getMulties()
    {
        if($this->multis == null)
        {
            $this->multis = db_get_all("SELECT DISTINCT a.acctid, a.login
						                      FROM account_multi am
						                      JOIN accounts a
						                      ON a.acctid<>".intval($this->acctid)." AND (a.acctid=am.master OR a.acctid=am.slave) AND a.imprisoned=0 AND activated<>".USER_ACTIVATED_MUTE."
						                  WHERE am.master=".intval($this->acctid)." OR am.slave=".intval($this->acctid).""
            );
        }
        return $this->multis;
    }

    public function getMultiesIDs()
    {
        $this->getMulties();
        if($this->multisIDS == null){
            $this->multisIDS[] = $this->acctid;
            foreach($this->multis as $m){
                $this->multisIDS[] = $m['acctid'];
            }
            $this->multisIDS = implode(',',$this->multisIDS);
        }
        return $this->multisIDS;
    }

    public function isDemoUser()
    {
        return $this->acctid == getsetting('demouser_acctid',0);
    }

    /**
     * @param $acctid
     * @return bool
     */
    public function isSelf ($acctid)
    {
        return ($acctid == 0 || $acctid == $this->acctid);
    }

    /**
     * @param $acctid
     * @return bool
     */
    public function isMulti ($acctid)
    {
        $this->getMulties();
        foreach($this->multis as $m){
            if($acctid == $m['acctid']){
                return $m;
            }
        }
        return false;
    }

    public function giveSympVote($id)
    {
        global $session;

        $rowsy = user_get_aei('symp_given,symp_votes');
        $to_id = $id;

        $maxsymp=getsetting('max_symp','10');

        if ( ($rowsy['symp_given']==0) &&
            ($rowsy['symp_votes']<$maxsymp) &&
            (	($session['user']['dragonkills']>0) &&
                (getsetting('symp_dk_lock','1')==1)
            )
        )
        {
            $failed = false;
            //Prüfen, ob er SP bekommen darf
            $res = db_query('SELECT acctid FROM accounts WHERE acctid='.$to_id.' AND 0=(conf_bits & '.UBIT_DISABLE_SYMPVOTE.')');
            if( db_num_rows($res) == 0 ){
                $str_back = 'Dieser Charakter darf keine Sympathiepunkte bekommen.';
                $failed = true;
            }
            else if(getsetting('symp_per_acc',10) < $maxsymp) {
                // Wenn max. Anzahl an Symp.punkten auf diesen Account noch nicht überschritten
                $sql = 'SELECT COUNT(*) AS c FROM sympathy_votes WHERE from_user='.$session['user']['acctid'].' AND to_user='.$to_id;
                $count = db_fetch_assoc(db_query($sql));

                if($count['c'] >= getsetting('symp_per_acc',10)) {
                    $str_back = 'Du hast diesem Charakter bereits genug Sympathiepunkte gegeben. So gerne kannst du ihn ja gar nicht haben.`0';
                    $failed = true;
                }

            }
            if( !$failed ){
                $sql = 'UPDATE account_extra_info SET sympathy=sympathy+1 WHERE acctid = '.$to_id;
                db_query($sql);
                $sql = 'UPDATE account_extra_info SET symp_given=1, symp_votes=symp_votes+1 WHERE acctid = '.$session['user']['acctid'];
                db_query($sql);

                $sql='INSERT INTO sympathy_votes (timestamp,from_user,to_user) VALUES (now(),'.$session['user']['acctid'].','.$to_id.')';
                db_query($sql);

                debuglog('Vergibt einen Sympathiepunkt an ',$to_id);
                $str_back = 'Sympathiepunkt vergeben!';
            }
        }
        else{
            $str_back = 'Du kannst keinen Sympathiepunkt vergeben!';
        }

        return $str_back;
    }

	/**
	 * Die Behandlungsroutine für das Lesen der Eigenschaften
	 *
	 * @param string $str_name Name der Eigenschaft
	 * @return mixed Referenz auf diese Eigenschaft
	 */
	public function &__get( $str_name ){
		//Alias?
		$str_name = $this->getNameFromAlias($str_name);

		$this->loadSerialized( $str_name );
			
		return $this->arr_data[$str_name];
	}
	
	/**
	 * Die Behandlungsroutine für das Schreiben der Eigenschaften
	 *
	 * @param string $name Name der Eigenschaft
	 * @param mixed $value neuer Wert
	 * @return mixed Referenz auf neuen Wert
	 */
	public function &__set( $name, $value ){
		//Alias?
		$name = $this->getNameFromAlias($name);
		
		//admin_output('__set: '.$name.' = '.$value.'<br>',false);
		//gibt es diesen wert überhaupt?
		if ( $this->isWritableValue($name) /*&& array_key_exists($name, $this->arr_data)*/){
			//Korrigieren und setzen
			$this->arr_data[$name] 		= $this->correctValue($name, $value);

			return $this->arr_data[$name];
		}
		
		return null;
	}
	
	/**
	 * Alias in richtigen Namen umwandeln
	 *
	 * @param string $alias
	 * @return string
	 */
	private function getNameFromAlias( $str_alias ){
		if( isset(self::$arr_aliases[$str_alias]) ){
			return self::$arr_aliases[$str_alias];
		}
		return $str_alias;
	}
	
	/**
	 * Korrigieren eines Wertes
	 * float -> int
	 * bool -> int
	 *
	 * @param string $str_name
	 * @param mixed $value
	 * @return mixed neuer Wert
	 */
	public function correctValue( $str_name, $value ){
		
		switch( $str_name )
		{
			case 'reputation':
				$value=min(max($value,-50),50);
			break;
			default: break;
		}
		
		// keine Floats
		if( is_float($value) ){
			$value = intval( $value );
		}
		// keine Bools
		else if( is_bool($value) ){
			$value = (int)$value;
		}
		// Wenn Integer < 0 ist, aber nicht sein darf
		if( is_int($value) && $value < 0 && !$this->isNegativeValue($str_name) ){	
			$value = 0;
		}
		
		return $value;
	}
	
	/**
	 * Speichert den User
	 *
	 */
	public function save(){
		//global $session;
		
		if( $this->bool_noSave ){
			return;
		}

		// Wird in der foreach Schleife abgehandelt
		if( $this->bool_isPlayer ){
			$this->allowednavs	= Atrahor::$Session['allowednavs'];
			$this->bufflist		= Atrahor::$Session['bufflist'];
		}
		

		// Schwuler serialized hin und herschubs workaround
		$arr_serialized_arrays 	= array();
		
		$str_sql				= '';
		$bool_changed 			= false;
		
		foreach( $this->arr_data as $key => $val ){
			
			// Nicht überschreiben aber warum weiß keiner :-D
			/**
			 * TODO ich auch nicht
			 */
			if( $key == 'httpreq_flag' ){
				continue;
			}

			// Arrays serialisieren
			elseif(is_array($val))
			{
				//unserialisierten Wert puffern
				$arr_serialized_arrays[$key] = $val;
				
				$val = utf8_serialize($val);
				$this->arr_data[ $key ] = $val;				
			}
			//Boolean Werte in Intwerte umwandeln
			elseif( is_bool($val) ){
				$this->arr_data[ $key ] = (int)$val;
			}
			
			//Floats werden in ints umgewandelt, wir nutzen keine Floats
			elseif( is_float($val) ){
				$this->arr_data[ $key ] = intval($val);
			}
			//Negative Werte für Felder die logisch nicht negativ sein dürfen
			
			if( is_numeric($val) && $val < 0 && !$this->isNegativeValue($key) ){	
				//if( !in_array($key, $arr_ltz) ) {
					systemlog('Ungültiger negativer Wert '.$val.' für key '.$key.' in '.$_SERVER['REQUEST_URI'].', ref: '.$_SERVER["HTTP_REFERER"], $this->acctid);
					// Diese Zeile hier setzt radikal alle anderen Werte zurück auf null
					$this->arr_data[ $key ] = 0;
				//}	
			}	

			// Überprüfen, ob sich Wert geändert hat
			if( array_key_exists($key, $this->arr_dataCopy) ){
				if( $this->arr_dataCopy[$key] !== $val ){
					$str_sql .= ', '.$key.'="'.db_real_escape_string($val).'"';
					$bool_changed = true;
				}
			}
			else {
				// Unerlaubte Keys löschen
				unset($this->arr_dataCopy[$key]);
			}
		}
		
		// eigentliche Speicherung
		if( $bool_changed ){
			// TODO und das sowieso weg
			$str_sql  = mb_substr($str_sql,2);
			$str_sql  = 'UPDATE '.self::TABLE_NAME.' SET '
						.$str_sql
						.' WHERE acctid = '.$this->acctid; 

			db_query($str_sql, false);

		}

		// Fehlerbereinigung
		// Manchmal findet ein saveuser nicht am Ende eines Skripts statt sondern mittendrin
		// Daraus folgt: einige Werte die zuvor als array vorlagen sind nun keine mehr
		// Das wird hier ggf wieder rückgängig gemacht
		$this->rewriteSerializeBuffer( $arr_serialized_arrays );
		
		unset( $this->output );
	}
	
	/**
	 * Laden von serialisierten Eigenschaften
	 *
	 * @param Name der Eigenschaft
	 */
	private function loadSerialized( $str_name ){
		if( array_key_exists($str_name,$this->arr_serialized) ){
			$var = $this->arr_data[$str_name];
			if( is_string($var) ){
				$var = utf8_unserialize($var);
				if( !is_array($var) ){
					$var = array();
				}
				$this->arr_serialized[$str_name] 	= false;
				$this->arr_data[$str_name] 			= $var;
			}
		}
	}
	
	
	private function rewriteSerializeBuffer( &$arr_buffer ){
		if( is_array($arr_buffer) ){
			
			foreach( $arr_buffer as $key => $val ){
				$this->arr_data[$key] = $val;
			}
			
			unset( $arr_buffer );
		}
	}

	private function createDataCopy(){
		global $session_copy;
		
		$this->arr_dataCopy = $this->arr_data;
		
		if( $this->bool_isPlayer ){
			$session_copy = &$this->arr_dataCopy;
		}
		$this->arr_dataCopy['output'] = '';
	}

	/**
	 * Laden der Benutzerrechte
	 * 
	 *
	 */
	public function loadRights(){
	
		// spezielle Rechte übernehmen 
		if( is_array($this->surights) ){
			$this->rights = $this->surights;
		}
		else{
			$this->rights = array();
		}
		
		// Wenn Benutzer einer Gruppe angehört
		if( $this->superuser > -1 ){
			// Gruppe abrufen
			$arr_usergroup = self::getSUGroups( $this->superuser );
		
			if( false !== $arr_usergroup ){
		
				$arr_grprights = $arr_usergroup[2];
		
				// Einzelrechte überschreiben Gruppenrechte
				$this->rights = array_merge_assoc( $arr_grprights, $this->rights );
				//$this->rights = array_merge( $arr_grprights, $this->rights );
			}
		}
	}
	
	/**
	 * Lädt Superusergruppen oder einzelne Gruppe; Konvertiert gleichzeitig Rechte in Array-Format
	 *
	 * @param int Gruppenid (Optional, Standard -1 = alle Gruppen)
	 * @return array Assoziativer Array mit Gruppen, Gruppenid als Schlüssel; Einzelne Gruppe, falls ID gegeben; false, falls nicht gefunden
	 */
	public static function getSUGroups( $int_id = -1 ){
	
		$arr_grps = utf8_unserialize((getsetting('sugroups','')) );
	
		if( is_array($arr_grps[$int_id]) ){
			return $arr_grps[ $int_id ];
		}
		elseif( $int_id == -1 ) {
			return $arr_grps;
		}

		return false;
	}
	
	/**
	 * Überprüft ob der aktuelle User ein Superusercharakter ist
	 *
	 * @uses access_control::is_superuser
	 * @return boolean
	 */
	public function isSuperuser()
	{
		return access_control::is_superuser();
	}
	
	/**
	 * Gib eine Liste aller Charaktere
	 *
	 * @param unknown_type $str_what
	 * @return unknown
	 */
	public static function getSUChars ($str_what = '`acctid`,`login`,`name`')
	{
		global $access_control;
		
		$str_su_groups = implode(',',$access_control->get_superuser_sugroups());
		$str_sql = "SELECT $str_what FROM accounts WHERE (accounts.superuser IN ($str_su_groups)) ";	
		$arr_users = db_get_all($str_sql);
		return $arr_users;
	}	
	
	public static function getChars($str_search_txt,$str_what = '`a`.`acctid`,`login`,`name`',$arr_search_in = array(), $str_where_addition = '', $str_order_by = '', $str_limit = '100')
	{
		global $access_control;
		
		$str_search_txt = trim(addstripslashes($str_search_txt));
		
		if(count($arr_search_in) == 0)
		{
			$arr_search_in = array(
				'a.acctid'	=> array('type'=>self::SEARCH_EXACT , 'mode'=> null, 'open_bracket' => false, 'close_bracket' => false),
				'login' 	=> array('type'=>self::SEARCH_SOUNDEX  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
				'name' 		=> array('type'=>self::SEARCH_LIKE_EXT  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false)
			);
		}
				
		$str_sql_prepend = '';
		$str_sql = "SELECT $str_what FROM `accounts` `a` LEFT JOIN `account_extra_info` `aei` USING (`acctid`) WHERE ";
		$str_sql_append = '';
		
		$str_where_addition = $str_where_addition == '' ? '' : ' '.$str_where_addition;
		
		$arr_where = array();
		$bool_first_search = true;

        if (!isset($str_where)) {
            $str_where = '';
        }

		foreach ($arr_search_in as $key => $arr_search)
		{
			if($key == 'acctid')
			{
				$key = '`a`.`acctid`';
			}
			
			$str_where .= $arr_search['mode'] == null ? '':' '.$arr_search['mode'].' ';
			
			$str_where .= $arr_search['open_bracket'] == true ? '(':'';			
			
			if($arr_search['type'] & self::SEARCH_EXACT)
			{
				$str_where .= $key.' = "'.$str_search_txt.'" ';
			}
			if($arr_search['type'] & self::SEARCH_LIKE )
			{
				$str_where .= $key.' LIKE "'.$str_search_txt.'" ';
			}
			if($arr_search['type'] & self::SEARCH_LIKE_EXT )
			{
				$str_where .= $key.' LIKE "'.str_create_search_string($str_search_txt).'" ';
			}
			if($arr_search['type'] & self::SEARCH_SOUNDEX )
			{
				$str_where .= $key.' SOUNDS LIKE "'.$str_search_txt.'"';
			}
			if($arr_search['type'] & self::SEARCH_FUZZY )
			{
				$str_sql_prepend = 'SELECT ab.* FROM (';
				
				$str_where .= $key.' SOUNDS LIKE "'.$str_search_txt.'"';
				
				$str_sql_append = ') AS ab ORDER BY ab.'.$key.' DESC';
			}			
			
			$str_where .= $arr_search['close_bracket'] == true ? ')':'';
		}
		
		$str_sql_order_by = $str_order_by == '' ? '' : ' ORDER BY '.$str_order_by;
		$str_where .= $str_sql_order_by;
		
		$str_sql_limit = $str_limit != '' ? ' LIMIT '.$str_limit : ''; 
		
		$str_sql_query = $str_sql_prepend.$str_sql.$str_where.$str_where_addition.$str_sql_limit.$str_sql_append;
			
		$arr_users = db_get_all($str_sql_query);
		return $arr_users;
	}
	
	/**
	* Berechnet maximales Ansehen
	* 
	* @return int Maximales Ansehen
	* @author maris
	* @author Alucard
	*/
	public function getMaxReputation(){
		if( $this->int_maxReputation == -1337 ){
			// Bestrafung		
			$int_penalty = $this->daysinjail - $this->dragonkills;
			
			// Eingrenzen
			$this->int_maxReputation = 50 - between(0, $int_penalty, 50);//min(50, max(0, $penalty));
		}
		return $this->int_maxReputation;
	}

	public function handleDrunkenness()
	{
		global $Char;
		
		$arrReturn = array();
		$arrReturn['died'] = false;
		$arrReturn['output'] = '';
		
		if ($this->drunkenness > 99)
		{
			page_header('Du hast zu viel gesoffen');
			//Elfen müssen aufpassen
			if ($this->race == 'elf')
			{
				$arrReturn['output'] .= 'Du hast zu viel gesoffen und bist an einer Alkoholvergiftung gestorben.`n`n
				Du verlierst 5% deiner Erfahrungspunkte und die Hälfte deines Goldes!`n`n
				Du kannst morgen wieder spielen.';
				$Char->kill(50,5);
				$arrReturn['died'] = true;
				addnews($this->name.' hat '.($this->sex?'ihren':'seinen').' zarten Elfenkörper mit zuviel Ale zugrunde gerichtet.');				
			}
			//Zwerge vertragen mehr
			elseif ($this->race == 'zwg')
			{
				switch(mt_rand(1,2))
				{
					case 1:
						$arrReturn['output'] .= 'Du hast zwar zuviel gesoffen, aber da ein Zwerg einiges vertragen kann, hast du es gerade noch überlebt.`n
						Du verlierst den Großteil deiner Lebenspunkte!';
						$this->hitpoints = 1;
						$this->drunkenness = 90;
						addnews($this->name.' entging nur knapp den Folgen einer Alkoholvergiftung, weil '.($this->sex?'sie eine Zwergin':'er ein Zwerg').' ist.');
						break;
					case 2:
						$arrReturn['output'] .= 'Du hast zu viel gesoffen und bist an einer Alkoholvergiftung gestorben.`n`n 
						Du verlierst 5% deiner Erfahrungspunkte und die Hälfte deines Goldes!`n`n
						Du kannst morgen wieder spielen.';
						$Char->kill(50,5);
						$arrReturn['died'] = true;
						addnews($this->name.' starb an einer Überdosis Ale.');				
						break;
				}
			}
			else 
			{
				//Alle anderen bekommen ne Chance
				switch(e_rand(1,10))
				{
					case 1:
					case 2:
					case 3:
						$arrReturn['output'] .= 'Du hast zwar zu viel gesoffen, es aber gerade noch überlebt.`n
						Du verlierst den Großteil deiner Lebenspunkte!';
						$this->hitpoints=1;
						$this->drunkenness=90;
						addnews($this->name.' entging nur knapp den Folgen einer Alkoholvergiftung.');
						break;
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
						$arrReturn['output'] .= 'Du hast zuviel gesoffen und bist an einer Alkoholvergiftung gestorben.`n`n 
						Du verlierst 5% deiner Erfahrungspunkte und die Hälfte deines Goldes!`n`n
						Du kannst morgen wieder spielen.';
						$Char->kill(50,5);
						$arrReturn['died'] = true;
						addnews($this->name.' starb an einer Überdosis Ale. ');
						break;
				}
			}
		}
		return $arrReturn;
	}


/**
 * BITS
 */

	/**
	 * Gibt den Wert eines bestimmten FLAG-Bits zurück
	 *
	 * @param int $int_bit
	 * @param string $str_row Welches Flag soll geprüft werden
	 * @return int Wert des Bits
	 */
	public function getBit( $int_bit, $str_row ){
		$r = $this->$str_row;
		if( $r !== null ){
			return getBit($int_bit, $r);
		}
		return 0;
	}
	
	/**
	 * Setzt den Wert eines bestimmten FLAG-Bits zurück
	 *
	 * @param int $int_bit Konstante des Bits (Bit-Name)
	 * @param string $str_row Welches Flag soll geprüft werden
	 * @param int $int_val BIT_SWITCH -> Wert wird geschaltet. 1->0, 0->1
	 * @return int Wert des Flags
	 */
	public function setBit( $int_bit, $str_row, $int_val = BIT_SWITCH ){
		$r = $this->$str_row;
		if( $r !== null ){
			return 	$this->$str_row = setBit($int_bit, $r, $int_val);
		}
		return 0;
	}


	/**
	 * Gibt den Wert des Config-FLAG-Bits zurück
	 *
	 * @param int $int_bit
	 * @return int Wert des Bits
	 */
	public function getConfBit( $int_bit ){
		return $this->getBit( $int_bit, 'conf_bits' );
	}
	
	/**
	 * Setzt den Wert des Config-FLAG-Bits zurück
	 *
	 * @param int $int_bit Konstante des Bits (Bit-Name)
	 * @param int $int_val BIT_SWITCH -> Wert wird geschaltet. 1->0, 0->1
	 * @return int Wert des Flags
	 */
	public function setConfBit( $int_bit, $int_val = BIT_SWITCH ){
		return $this->setBit( $int_bit, 'conf_bits', $int_val);
	}
	
	/**
	 * Gibt den Wert des Newday-FLAG-Bits zurück
	 *
	 * @param int $int_bit
	 * @return int Wert des Bits
	 */
	public function getNewdayBit( $int_bit ){
		return $this->getBit( $int_bit, 'newday_bits' );
	}
	
	/**
	 * Setzt den Wert des Config-FLAG-Bits zurück
	 *
	 * @param int $int_bit Konstante des Bits (Bit-Name)
	 * @param int $int_val BIT_SWITCH -> Wert wird geschaltet. 1->0, 0->1
	 * @return int Wert des Flags
	 */
	public function setNewdayBit( $int_bit, $int_val = BIT_SWITCH ){
		return $this->setBit( $int_bit, 'newday_bits', $int_val);
	}
	
/**
 * END Bits
 */
	
	/**
	 * ist diese Variable überschreibbar?
	 *
	 * @param string $str_name
	 * @return bool
	 */
	public function isWritableValue( $str_name ){
		if(array_key_exists($str_name,self::$arr_noOverwrite))
		{
			return !(bool)self::$arr_noOverwrite[ $str_name ];
		}
		else 
		{
			return true;	
		}
	}
	
	/**
	 * kann diese Variable negative werden?
	 *
	 * @param string $str_name
	 * @return bool
	 */
	public function isNegativeValue( $str_name ){
		return (bool)self::$arr_negativeValues[ $str_name ];
	}
	
	/**
	 * Anonymisiert den Benutzer
	 * @param bool $bool_save Benutzer speichern
	 * @todo Prefs
	 */
	public function anonymize( $bool_save = false ){
		$hash					= md5('DragonslayerAlucardTalionMaris'); 
		$this->emailadress  	= utf8_str_pad($hash, 128, $hash, STR_PAD_BOTH);
		$this->emailvalidation	= utf8_str_pad($hash, 32,  $hash, STR_PAD_BOTH);
		$this->password			= utf8_str_pad($hash, 32,  $hash, STR_PAD_BOTH);
		$this->lastip			= utf8_str_pad($hash, 40,  $hash, STR_PAD_BOTH);
		$this->login			= utf8_str_pad($hash, 50,  $hash, STR_PAD_BOTH);
		$this->uniqueid			= utf8_str_pad($hash, 32,  $hash, STR_PAD_BOTH);
		$this->prefs			= array();
		$this->loggedin			= 0;
		$this->output			= "";
		$this->laston			= date('Y-m-d H:i:s', 0);
		$this->lasthit			= date('Y-m-d H:i:s', 0);
		$this->lastmod			= date('Y-m-d H:i:s', 0);
		$this->recentcomments	= date('Y-m-d H:i:s', 0);
		$this->petfeed			= date('Y-m-d H:i:s', 0);
		$this->pvpflag			= date('Y-m-d H:i:s', 0);
		
		// Speichern
		if( $bool_save ){
			$this->save();
		}
	}
	
	/**
	 * Holt den user aus dem verbannungsmodus zurück
	 */
	public function removeVacationmode()
	{
		db_query('DELETE FROM bans WHERE loginfilter="'.$this->login.'" AND banreason LIKE "%Urlaubsmodus%"');
		$this->laston = date('Y-m-d H:i:s',time());
		$this->location = USER_LOC_VACATION;
		$this->save();
	}
	
	/**
	 * Setzt den user für $intDays Tage in den Urlaubsmodus
	 *
	 * @param int $intDays Anzahl der Tage
	 */
	public function setVacationmode($intDays = false)
	{
		if($intDays === false)
		{
			$intDays = getsetting('vacation_ban_time',7);
		}
			
		//Bestimmte Items löschen
		if ($this->acctid == getsetting('hasegg',0))
		{

			savesetting('hasegg',0);
			$sql = 'UPDATE items SET owner=0 WHERE tpl_id="goldenegg"';
			db_query($sql);
		}						
		$res=item_list_get('tpl_id IN("idolrnds", "idolgnie", "idolfish", "idolkmpf", "idoldead", "mapt") AND owner='.$this->acctid);
		if(db_num_rows($res)>0)
		{
            if (!isset($strItems)) {
                $strItems = '';
            }

			while ($row=db_fetch_assoc($res))
			{
				$strItems.=','.$row['id'];
			}
		}
		unset($res);			
		item_delete('id IN (0'.$strItems.')');
		setban(0,'Automatischer Systembann: User hat sich in den Urlaubsmodus versetzt.',date('Y-m-d H:i:s',time()+($intDays*86400)),false,false,false,$this->login);
		$this->save();
	}
	
	/**
	 * Lässt den Spieler sterben und speichert automatisch
	 *
	 * @param int $int_losegold wert vom goldverlust (standardmäßig 100%)
	 * @param int $int_loseexp wert vom expverlust (standardmäßig 5%)
	 * @param bool $bool_killdisciple Knappe sterben lassen
	 * @param string $str_redirect was kommt nach dem tod
	 * @param string $str_linkname Beschriftung für den Todeslink
	 * @param int $int_killflags flags, die das verhalten bestimmen (ODER-VERKNÜPFT)
	 * @return array Verluste des Spielers, nach dem Muster: 'gold'=>Wert, 'disciple'=>Knappen-Datensatz...
	 * @see CCharacter::LOSEGOLD_VALUE
	 * @see CCharacter::LOSEEXP_VALUE
	 */
	public function kill( 	$int_losegold		= 100,
							$int_loseexp		= 5,
							$bool_killdisciple	= false,
							$str_redirect		= 'shades.php',
							$str_linkname		= 'Zu den Schatten',
							$int_killflags		= 0)
	{
		//global $session;
		
		/**
		 * man kann nur den aktuellen user killen
		 */
		if( !$this->bool_isPlayer ){
			return array();
		}

		$arr_results = array(); 

        CQuest::died();

        $this->specialinc = '';

		// Goldverlust
		if( $this->gold && $int_losegold > 0 ){
			if( $int_killflags & CCharacter::LOSEGOLD_VALUE ){	
				$int_losegold 		= min($int_losegold, $this->gold);
				$this->gold		   -= $int_losegold;
				$arr_results['gold'] 	= $int_losegold;
			}
			else{
				$int_losegold		= round( $this->gold * ($int_losegold/100), 0);
				$this->gold 	   -= $int_losegold;
				$arr_results['gold'] 	= $int_losegold;
			}
		}
	
	
		// Erfahrungsverlust
		if( $this->experience && $int_loseexp > 0 ){
			if( $int_killflags & CCharacter::LOSEEXP_VALUE ){
				$int_loseexp 			= min($int_loseexp, $this->experience);
				$this->experience 	   -= $int_loseexp;
				$arr_results['experience'] 	= $int_loseexp;
			}
			else{	
				$int_loseexp 			= round( $this->experience * ($int_loseexp/100), 0);
				$this->experience  	   -= $int_loseexp;
				$arr_results['experience'] 	= $int_loseexp;
			}
		}
	
		// Knappenzeug nur berechnen wenn auch gewünscht.
		if( $bool_killdisciple )
		{
			$arrDisciple = CDisciple::get($this->acctid);
			$disciple_buff = array();
			// wenn Knappe vorhanden
			if( count($arrDisciple) > 0 ) {
		
				// Wenn kein untoter Knappe und Knappe auch nicht verschleppt oder tot
				if($arrDisciple['buff']['survive_death'] == 0 && $arrDisciple['buff']['state'] > 0)
				{				
					CDisciple::remove();
					$arr_results['disciple'] = $arrDisciple;
				}
			}
		}

		// sonstige weltliche Werte zurücksetzen :>
		
		/**
		 * @DS alle Buffs werden abgearbeitet und ggf gelöscht.
		 */
		buff_process_death();
		$this->maze_visited 				= '';
		$this->badguy						= '';
		$this->specialinc					= '';
		$this->alive						= 0;
		$this->hitpoints					= 0;
	
		if( !empty($str_redirect) ){
			clearnav();
			addnav($str_linkname, $str_redirect);
		}
		
		// Speichern
		$this->save();
		
		return $arr_results;
	}
}
?>
