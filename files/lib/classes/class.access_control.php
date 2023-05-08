<?php

/**
 * Klassendefinition für das Atrahor Zugangsrechtesystem
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 */
class access_control implements Countable , Iterator, ArrayAccess
{
	// Konstantendefs für SU-Rechte
	const SU_RIGHT_PETITION 			= 1;
	const SU_RIGHT_CASTLECHOOSE 		= 2;
	const SU_RIGHT_NEWDAY 				= 3;
	const SU_RIGHT_FORESTSPECIAL 		= 4;
	const SU_RIGHT_STEALTH 				= 5;
	const SU_RIGHT_EDITORUSER 			= 6;
	const SU_RIGHT_EDITORGUILDS 		= 7;
	const SU_RIGHT_DEBUG 				= 8;
	const SU_RIGHT_COMMENT				= 9;
	const SU_RIGHT_RIGHTS 				= 10;
	const SU_RIGHT_FAILLOG 				= 11;
	const SU_RIGHT_EDITORHOUSES 		= 12;
	const SU_RIGHT_EDITORITEMS 			= 13;
	const SU_RIGHT_EDITORCASTLES 		= 14;
	const SU_RIGHT_EDITORLIBRARY 		= 15;
	const SU_RIGHT_GAMEOPTIONS 			= 16;
	const SU_RIGHT_DONATIONS 			= 17;
	const SU_RIGHT_LOGOUTALL 			= 18;
	const SU_RIGHT_CHECKBOARDS 			= 19;
	const SU_RIGHT_MAILBOX 				= 20;
	const SU_RIGHT_EDITORTITLES 		= 21;
	const SU_RIGHT_EDITORCOLORS 		= 22;
	const SU_RIGHT_EDITOREXTTXT 		= 23;
	const SU_RIGHT_EDITORSPECIALTIES 	= 24;
	const SU_RIGHT_EDITORWORLD 			= 25;
	const SU_RIGHT_EDITORMOUNTS 		= 26;
	const SU_RIGHT_MOTD 				= 27;
	const SU_RIGHT_RETITLE 				= 28;
	const SU_RIGHT_WARTUNG 				= 29;
	const SU_RIGHT_WATCHSU 				= 30;
	const SU_RIGHT_EXPEDITION 			= 31;
	const SU_RIGHT_MUTE 				= 32;
	const SU_RIGHT_PRISON 				= 33;
	const SU_RIGHT_LOCKHTML 			= 34;
	const SU_RIGHT_NEWS 				= 35;
	const SU_RIGHT_COMMENTPRIV 			= 36;
	const SU_RIGHT_SULVL 				= 37;
	const SU_RIGHT_GODMODE 				= 38;
	const SU_RIGHT_EDITORRANDOMCOM 		= 39;
	const SU_RIGHT_EDITORSPECIAL 		= 40;
	const SU_RIGHT_CASTLEMAP 			= 41;
	const SU_RIGHT_DEV 					= 42;
	const SU_RIGHT_EXPEDITION_ADMIN 	= 43;
	const SU_RIGHT_REGISTRATUR 			= 44;
	const SU_RIGHT_STATS 				= 45;
	const SU_RIGHT_DEBUGLOG 			= 46;
	const SU_RIGHT_SYSLOG 				= 47;
	const SU_RIGHT_RP 					= 48;
	const SU_RIGHT_MULTI 				= 49;
	const SU_RIGHT_EDITOREQUIPMENT 		= 50;
	const SU_RIGHT_BIOS 				= 51;
	const SU_RIGHT_LOCKBIOS 			= 52;
	const SU_RIGHT_EDITORRACES 			= 53;
	const SU_RIGHT_SHOWSTEALTH 			= 54;
	const SU_RIGHT_GROTTO 				= 55;
	const SU_RIGHT_UPLOADCONTROL 		= 56;
	const SU_RIGHT_RPRATING 			= 57;
	const SU_RIGHT_SOURCEVIEW 			= 58;
	const SU_RIGHT_SOURCEEDIT 			= 59;
	const SU_RIGHT_EDITORRENAME 		= 60;
	const SU_RIGHT_PRANGERMOD 			= 61;
	const SU_RIGHT_PORTALADMIN 			= 62;
	const SU_RIGHT_EDITOR_CISLOGANS 	= 63;
	const SU_RIGHT_MASSMAIL 			= 64;
	const SU_RIGHT_ISATALION 			= 423;
	const SU_RIGHT_EDITOR_BARDE 		= 65;
	const SU_RIGHT_USERDISCU 			= 66;
	const SU_RIGHT_WRITE_YOM_TO_MAIL 	= 67;
	const SU_RIGHT_LIVE_DIE 			= 68;
	const SU_RIGHT_PORTALCODE 			= 69;
	const SU_RIGHT_FORWARD_YOM_TO_SUPERUSER = 70;
	const SU_RIGHT_EDIT_BOTD 			= 71;
	const SU_RIGHT_SEARCH_NEWS 			= 72;
	const SU_RIGHT_QUICKNAV 			= 73;
	const SU_RIGHT_REGISTRATUR_EDIT_USER= 74;
	const SU_RIGHT_VIEW_SYMPATHY_VOTES	= 75;
	const SU_RIGHT_ANONYMIZE_USER		= 76;
	const SU_RIGHT_BAN_USER				= 77;
	const SU_RIGHT_EDIT_RIGHTS			= 78;
	const SU_RIGHT_EXPEDITION_ENTER		= 79;
	const SU_RIGHT_VENDOR_ENTER			= 80;
	const SU_RIGHT_SEE_PAGEGEN			= 81;
	const SU_RIGHT_SEE_INTERNA			= 82;
	const SU_RIGHT_EDITUSERSHOPS 		= 83;
	const SU_RIGHT_RESET_DRAGONPOINTS	= 84;
	const SU_RIGHT_EDITOR_HINTS			= 85;
	const SU_RIGHT_EDITOR_WEATHER_TEXTS	= 86;
	const SU_RIGHT_EDITOR_TROPHIES		= 87;
    const SU_RIGHT_EDITOR_ELEMENTS		= 88;
    const SU_RIGHT_FIXNAVS		        = 89;
    const SU_RIGHT_LOCKIMG		        = 90;

	// Namen / Beschreibungen für SU-Rechte
	// Array eines einzelnen Rechts jeweils: 'desc' = Beschreibung, 'dependent' = Abhängigkeit von best. and. Recht
	// 																(Gilt nur, wenn dieses ebenfalls positiv)
	static $ARR_SURIGHTS = array(   'support' => 'Support / PR'
							,self::SU_RIGHT_PETITION => array('desc'=>'Darf Anfragen bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_FIXNAVS=> array('desc'=>'Darf defekte Navs reparieren?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_MOTD => array('desc'=>'Darf MOTD bearbeiten?')
								,self::SU_RIGHT_WRITE_YOM_TO_MAIL => array('desc'=>'Darf Brieftauben verfassen und sie an E-Mails verschicken')
							,self::SU_RIGHT_FORWARD_YOM_TO_SUPERUSER => array('desc'=>'Darf seine Brieftauben automatisch an einen anderen Superuser weiterleiten')
							,'editors' => 'Grotte & Editoren'
							,self::SU_RIGHT_GROTTO => array('desc'=>'Darf Grotte betreten?')
							,self::SU_RIGHT_SEE_INTERNA => array('desc'=>'Sieht grotteninterne Dinge?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORUSER => array('desc'=>'Darf User bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORGUILDS => array('desc'=>'Darf Gilden bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORHOUSES => array('desc'=>'Darf Häuser bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORITEMS => array('desc'=>'Darf Items bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORCASTLES => array('desc'=>'Darf Schlösser bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORLIBRARY => array('desc'=>'Darf Bibliothek bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORTITLES => array('desc'=>'Darf Titel bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORCOLORS => array('desc'=>'Darf Farbtags bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITOREXTTXT => array('desc'=>'Darf Ext. Texte bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORSPECIALTIES => array('desc'=>'Darf Fähigkeiten bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORWORLD => array('desc'=>'Darf Welt-Editoren benutzen (Monster;Spott;Rätsel;Runen;)?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITOREQUIPMENT => array('desc'=>'Darf Waffen + Rüstungen bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORMOUNTS => array('desc'=>'Darf Stalltiere bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORRACES => array('desc'=>'Darf Rassen bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORRANDOMCOM => array('desc'=>'Darf Zufallskommentare bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITORSPECIAL => array('desc'=>'Darf Waldereignisse bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_DONATIONS => array('desc'=>'Darf DP vergeben / abnehmen?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_EDITOR_WEATHER_TEXTS => array('desc' => 'Darf Wettertexte verwalten')
							,'surveillance' => 'Überwachung'
							,self::SU_RIGHT_COMMENT => array('desc'=>'Darf Kommentarsektionen überwachen?')
							,self::SU_RIGHT_BIOS => array('desc'=>'Darf Bios überwachen?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_STEALTH => array('desc'=>'Darf Stealthmodus nutzen?')
							,self::SU_RIGHT_NEWS => array('desc'=>'Darf News + Aufzeichnungen eintragen / löschen?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_SHOWSTEALTH => array('desc'=>'Sieht User im Stealthmodus?')
							,'deep' => 'Gefährliches'
							,self::SU_RIGHT_EDIT_RIGHTS => array('desc'=>'Darf die Gruppenrichtlinien verändern','dependent'=>self::SU_RIGHT_RIGHTS)
							,self::SU_RIGHT_RIGHTS => array('desc'=>'Darf Superuser-Rechte einstellen?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_GAMEOPTIONS => array('desc'=>'Darf Spieleinstellungen bearbeiten?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_LOGOUTALL => array('desc'=>'Darf alle Spieler ausloggen?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_DEV => array('desc'=>'Darf versch. Entwicklerrechte ausüben?')
							,self::SU_RIGHT_ISATALION => array('desc'=>'Hat das Recht ein Talion zu sein?','dependent'=>self::SU_RIGHT_DEV)
							,self::SU_RIGHT_ANONYMIZE_USER => array('desc'=>'Darf einen User vollständig anonymisieren')
							,self::SU_RIGHT_RESET_DRAGONPOINTS => array('desc'=>'Darf Drachenpunkte resetten')
							,'cheats' => 'Cheating / SU-Buttons'
							,self::SU_RIGHT_CASTLECHOOSE => array('desc'=>'Darf Schloss wählen?')
							,self::SU_RIGHT_CASTLEMAP => array('desc'=>'Sieht Superusermap im Schloß?')
							,self::SU_RIGHT_NEWDAY => array('desc'=>'Darf Neuen Tag auslösen?')
							,self::SU_RIGHT_FORESTSPECIAL => array('desc'=>'Waldspecialauswahl?')
							,self::SU_RIGHT_SULVL => array('desc'=>'Darf SU-Levelbutton nutzen?')
							,self::SU_RIGHT_GODMODE => array('desc'=>'Darf GODMODE nutzen?')
							,self::SU_RIGHT_LIVE_DIE => array('desc'=>'Darf Super-Lemming spielen?')
							,self::SU_RIGHT_VENDOR_ENTER => array('desc'=>'Darf Wanderhändler nutzen auch wenn nicht anwesend?')
							,'Logs' => 'Logs'
							,self::SU_RIGHT_FAILLOG => array('desc'=>'Darf Faillog ansehen?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_SYSLOG => array('desc'=>'Darf Systemlog einsehen?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_DEBUGLOG => array('desc'=>'Darf Debuglog einsehen?','dependent'=>self::SU_RIGHT_GROTTO)
							,self::SU_RIGHT_SEARCH_NEWS => array('desc'=>'Darf die News durchsuchen?')
							,self::SU_RIGHT_VIEW_SYMPATHY_VOTES => array('desc'=>'Darf die Wahlanalyse sehen')
							,'Rollenspiel' => 'Rollenspiel'
							,self::SU_RIGHT_RP => array('desc'=>'Spielleiterfunktionen (/msg)?')
							,self::SU_RIGHT_EXPEDITION => array('desc'=>'Ein- / Ausladungen der Expedition?')
							,self::SU_RIGHT_EXPEDITION_ENTER => array('desc'=>'Darf Expedition betreten auch wenn nicht freigeschaltet?')
							,self::SU_RIGHT_EXPEDITION_ADMIN => array('desc'=>'Administration der Expedition?')
							,'punish' => 'Strafen'
							,self::SU_RIGHT_MUTE => array('desc'=>'Knebelfunktion?')
							,self::SU_RIGHT_PRISON => array('desc'=>'Kerkerfunktion?')
							,self::SU_RIGHT_LOCKBIOS => array('desc'=>'Darf Bios sperren?')
							,self::SU_RIGHT_LOCKIMG => array('desc'=>'Darf Bilder sperren?')
                            ,self::SU_RIGHT_BAN_USER => array('desc'=>'Darf Verbannungen aussprechen', 'dependent'=>self::SU_RIGHT_GROTTO)
							,'Sonstiges'=>'Sonstiges'
							,self::SU_RIGHT_WARTUNG => array('desc'=>'Übergeht Wartungsmodus?')
							,self::SU_RIGHT_DEBUG => array('desc'=>'Debug-Funktionen (Male testen Gericht / Stadtwache / Tempel betreten etc.)?')
							,self::SU_RIGHT_QUICKNAV => array('desc'=>'Darf Quicknav benutzen')
							);

	/**
	 * Enthält die deserialisierten Superusergruppen sobald sie einmal aus der DB geladen wurden
	 * @var array
	 */
	static $arr_su_groups;

	/**
	* @author talion
	* @desc Überprüft, ob der aktuelle User angegebenes Superuser-Recht besitzt
	* @param int ID des Rechts (durch Konstanten gegeben)
	* @param bool Wenn true, wird Anticheat-Maßnahme durchgeführt. (Optional, Standard false)
	* @return bool True oder False.
	*/
	public function su_check($int_rid, $bool_becruel = false) {

		global $Char;
		if($Char == null) return false;
		
		if( !is_array($Char->rights) && $Char->superuser ) {
			$Char->loadRights();
		}

		if(!empty(self::$ARR_SURIGHTS[$int_rid]['dependent'])) {
			if( !$Char->rights[ self::$ARR_SURIGHTS[$int_rid]['dependent'] ] ){
				// Eigentlich wird auf Abhängigkeit bereits beim Speichern der Rechte geprüft, aber doppelt hält besser
				$Char->rights[$int_rid] = 0;
			}
		}
		$r = $Char->rights;
		if( $Char->rights[$int_rid] ){
			return true;
		}
		else{

			if( false === $bool_becruel ){
				return false;
			}
			else{

				kill_cheater();

				return(false);
			}
		}
	}

	/**
	* @author talion
	* @desc Überprüft, ob der aktuelle User (min) angegebenen Superuser-Lvl besitzt
	* @param int Level
	* @param bool Wenn true, wird 1. Param als Min.Lvl gesehen (Optional, Standard true)
	* @param bool Wenn true, wird Anticheat-Maßnahme durchgeführt. (Optional, Standard false)
	* @return bool True oder False.
	*/
    public function su_lvl_check ($int_su, $bool_min = true, $bool_becruel = false, $bool_admin_check = false) {

		global $session;

		//Lade alle Gruppen die explizit als Superusergruppen gekennzeichnet sind
		$arr_sugroups = self::get_superuser_sugroups();

		if($bool_min) {
			//User hat mindestens das gewünschte Superuserlevel. Das Level muss aber in der Liste der
			//Superusergruppen sein
			if($session['user']['superuser'] >= $int_su && in_array($session['user']['superuser'],$arr_sugroups)) {
				return(true);
			}
		}
		else {
			if($session['user']['superuser'] == $int_su) {
				return(true);
			}
		}

		// Ab hier steht fest: Bedingung nicht gegeben.

		if(false === $bool_becruel) {
			return(false);
		}
		else {

			kill_cheater();

			return(false);
		}

	}

	/**
	 * Überprüft ob der aktuelle User ein superuser ist
	 *
	 * @return boolean rue wenn es ein superuser ist, sonst false
	 */
	public static function is_superuser()
	{
		global $Char;

		//Lade alle Gruppen die explizit als Superusergruppen gekennzeichnet sind
		$arr_sugroups = self::get_superuser_sugroups();

		return in_array($Char->superuser,$arr_sugroups);

	}

	/**
	 * Liefert SQL-Schnipsel um zu überprüfen, ob Datensätze in Account-Table bestimmtes Recht haben
	 *
	 * @param int ID des Rechts (durch Konstanten gegeben)
	 * @return string SQL-WHERE-Conditions
	 */
	public function su_check_other ($int_right) {

		$str_sql = ' 0 ';

		// Gruppe
		$arr_grps = user_get_sugroups();
		foreach ($arr_grps as $gid=>$g) {
			$arr_rights = $g[2];
			if($arr_rights[$int_right]) {
				$str_sql .= ' OR accounts.superuser = '.$gid;
			}
		}

		// Sonderrechte
		$str_sql .= ' OR accounts.surights LIKE \'%i:'.$int_right.';s:1:"1"\' ';

		return ($str_sql);

	}

	/**
	 * Lädt Superusergruppen oder einzelne Gruppe; Konvertiert gleichzeitig Rechte in Array-Format
	 *
	 * @param int Gruppenid (Optional, Standard -1 = alle Gruppen)
	 * @return array Assoziativer Array mit Gruppen, Gruppenid als Schlüssel; Einzelne Gruppe, falls ID gegeben; false, falls nicht gefunden
	 */
	public static function user_get_sugroups ($int_id=-1)
	{
		//Superusergruppen müssen im Objekt selbst gecached werden wenn sie dort noch nicht liegen!
		if(!is_array(self::$arr_su_groups) || count(self::$arr_su_groups) == 0)
		{
			$arr_grps = utf8_unserialize((getsetting('sugroups','')) );
		}
		else
		{
			$arr_grps &= self::$arr_su_groups;
		}

		if(is_array($arr_grps[$int_id])) {
			return($arr_grps[$int_id]);
		}
		elseif($int_id == -1) {
			return($arr_grps);
		}
		else {
			return(false);
		}
	}

	/**
	 * Gibt einen Array zurück der nur die Gruppen enthält die Superuser sind
	 */
	public static function get_superuser_sugroups($bool_get_groups = false)
	{
		$arr_grps = self::user_get_sugroups();
		$arr_return = array();
		foreach ($arr_grps as $gid=>$g)
		{
			if($bool_get_groups == true && $g[4] == true)
			{
				$arr_return[$gid] = $g;
				continue;
			}

			if($g[4] == true)
			{
				$arr_return[] = $gid;
			}
		}
		return $arr_return;
	}

	///Iterator Interface

	public function rewind() {
		reset(self::$ARR_SURIGHTS);
	}

	public function current() {
		$var = current(self::$ARR_SURIGHTS);
		return $var;
	}

	public function key() {
		$var = key(self::$ARR_SURIGHTS);
		return $var;
	}

	public function next() {
		$var = next(self::$ARR_SURIGHTS);
		return $var;
	}

	public function valid() {
		$var = $this->current() !== false;
		return $var;
	}

	///END Iterator Interface

	///Countable Interface
	public function count()
	{
		$var = count(self::$ARR_SURIGHTS);
		return $var;
	}
	///END Countable Interface

	///ArrayAccess Interface
	public function offsetExists ($offset)
	{
		return isset(self::$ARR_SURIGHTS[$offset]);
	}
 	public function offsetGet ($offset)
 	{
 		return self::$ARR_SURIGHTS[$offset];
 	}
 	public function offsetSet ($offset, $value)
 	{
 		self::$ARR_SURIGHTS[$offset] = $value;
 	}
 	public function offsetUnset ($offset)
 	{
 		unset(self::$ARR_SURIGHTS[$offset]);
 	}
 	///END ArrayAccess Interface

}
?>