<?php

class Atrahor{
	
	/**
	 * Security
	 *
	 * @var access_control
	 */
	public static $AccessControl 	= null;
	
	public static $titles			= array();
	
	public static $Session			= array();
	
	public static function init(){
		//nur wegen Kompatibilität
		global $access_control, $titles, $session;
		
		//Erzeugen des Security Objekts
		$access_control = Cache::get(Cache::CACHE_TYPE_MEMORY, 'obj_access_control');
		if( !$access_control instanceof access_control )
		{
			$access_control = new access_control();
			Cache::set(Cache::CACHE_TYPE_MEMORY,'obj_access_control',$access_control);
		}
		
		// Titelliste
		if( !Optimizer::optimizing(Optimizer::NO_TITLES) ){
			$titles = utf8_unserialize(getsetting('title_array',null));
		}
		else{
			$titles = array();
		}
		
		
		//Kompatibilität
		self::$Session			=& $session;
		self::$AccessControl 	=& $access_control;
		self::$titles			=& $titles;	
	}	
	
	public static function clearSession(){
		global $Char;
		
		/**
		 * $b = array("hihaha");
		 * $a = array('user'=>&$b);
		 * $a = array();
		 * var_dump( $b ); //shows array(1) { [0]=> string(6) "hihaha" }
		 * ==>Das Sessionarray muss explizit gelöscht werden!
		 */

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"],
                $params["domain"], $params["secure"], $params["httponly"]
            );
        }

		utf8_setcookie('eingeloggtbleiben', 0, time() - 42000);

        session_destroy();

        self::$Session = array();

        //Char darf nicht gespeichert werden
        if ($Char instanceof CCharacter) {
            $Char->bool_noSave = true;
        }
		
	}
}


?>