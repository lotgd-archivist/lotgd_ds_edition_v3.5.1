<?php

define('AVATAR_UPLOAD_DIR2', mb_substr($_SERVER['SCRIPT_FILENAME'],0,mb_strrpos($_SERVER['SCRIPT_FILENAME'],'/')).'/images/avatar/');
define('AVATAR_SECURE_DIR2', mb_substr($_SERVER['SCRIPT_FILENAME'],0,mb_strrpos($_SERVER['SCRIPT_FILENAME'],'/')).'/images/avatar/confirmed/');

/**
 * Klasse rund um bilder
 * @oldauthor Alucard, Dragonslayer, Jenutan
 * @author Saris, Bathory
 */
class CPicture
{	
	/**
	* Verzeichnis, in das Bilder von Usern hochgeladen werden
	*/
	const AVATAR_UPLOAD_DIR = AVATAR_UPLOAD_DIR2;
	const MAX_QOUTA = 26214400*2;

	/**
	* Sicheres Verzeichnis, in das Bilder von Usern nach ihrer Bestätigung verschoben werden
	*/
	const AVATAR_SECURE_DIR = AVATAR_SECURE_DIR2;
	
	/**
	* Gleiches als relative Pfadangabe
	*/
	const AVATAR_UPLOAD_WEBDIR = './images/avatar/';
	
	/**
	* Gleiches als relative Pfadangabe
	*/
	const AVATAR_SECURE_WEBDIR = './images/avatar/confirmed/';
	
	/**
	* Bilder-DB-Tabelle
	*/
	const PICTURE_TABLE = 'user_uploads_pictures';
	
	/**
	* Beihaltet die Datei-Endung
	* 
	* @var string
	*/
	public $ext;
	
	/**
	* Der temporäre Dateiname, 
	* unter dem die hochgeladene Datei auf dem Server gespeichert wurde.
	* 
	*/
	public $tmp;
	
	/**
	* Ist die Datei überhaupt ein (erlaubtes) Bild?
	* Wird "true", wenn alles soweit ok ist.
	* 
	* @var boolean
	*/
	private $valide;
	
	/**
	* Aktuelle Bild-Breite
	* 
	* @var int
	*/
	public $width;
	
	/**
	* Aktuelle Bild-Höhe
	* 
	* @var int
	*/
	public $height;
	
	/**
	* Original Bild-Breite
	* 
	* @var int
	*/
	public $width_orig;
	
	/**
	* Original Bild-Höhe
	* 
	* @var int
	*/
	public $height_orig;
	
	/**
	* Konstruktor!
	* Überprüft schonmal auf zugelassene Endungen,
	* und speichert den temporären Dateiname, unter dem die hochgeladene Datei auf dem Server gespeichert wurde.
	*
	* @param array $file PHP-File-Array
	*/
	public function __construct( $file )
	{
		if(is_array($file)){
			switch($file['type'])
			{
				case 'image/jpg' :
				case 'image/jpeg' :
				case 'image/pjpeg' :
					$this->ext = 'jpg';
					break;
				case 'image/gif' :
					$this->ext = 'gif';
					break;
				case 'image/png' :
					$this->ext = 'png';
					break;
				default:
					$this->ext = '';
					break;
			}

			$this->tmp = $file['tmp_name'];
			$this->valide = utf8_preg_match('/^(gif|jpg|jpeg|png)$/', $this->ext) && is_uploaded_file($this->tmp);
		}else if(filter_var($file, FILTER_VALIDATE_URL)){
			$end = mb_substr($file,-4);
			if(in_array($end,array('.jpg','.gif','.png')))
			{
				$this->ext  = mb_substr($end,1);
				$this->tmp = '/tmp/atrahor_'.md5($file).'.tmp';
				$this->valide = true;
				switch( $end ){
					case '.gif':
						$image = imagecreatefromgif( $file );
						if($image){
							imagegif($image, $this->tmp);
							imagedestroy($image);
						}else{
							$this->tmp = '';
							$this->valide = false;
						}
						break;
					case '.jpg':
						$image = imagecreatefromjpeg( $this->tmp );
						if($image){
							imagejpeg($image, $this->tmp);
							imagedestroy($image);
						}else{
							$this->tmp = '';
							$this->valide = false;
						}
						break;
					case '.png':
						$image = imagecreatefrompng($this->tmp);
						if($image){
							imagepng($image, $this->tmp);
							imagedestroy($image);
						}else{
							$this->tmp = '';
							$this->valide = false;
						}
						break;
				}
			}
		}
		if( $this->valide ){
			list($this->width, $this->height) = getimagesize($this->tmp);
			$this->width_orig  = $this->width;
			$this->height_orig = $this->height;
		}
	}
	
	/**
	* Holt alle bilderbezogene Texte zu einem User aus der DB und packt diese in das globale Array $pictures_info
	*
	* @param int $user AccountID des zu ladenden Users
	* @author 2007 Jenutan for Atrahor.de
	*/
	public static function picture_fill_info($user)
	{
		global $pictures_info;
	
		if (is_array($pictures_info[$user])) return;
		
		$sql = "
			SELECT
				*
			FROM
				`" . self::PICTURE_TABLE . "`
			WHERE
				`userid`		= '" . $user ."'
		";
		$res = db_query($sql);
		$max = db_num_rows($res);
		$pictures_info[$user] = array();
		for ($i = 0; $i < $max; $i++)
		{
			$row = db_fetch_assoc($res);
			$pictures_info[$user][$row['small_letter']] = $row;
		}
	}
	
	/**
		* Speichert den Künstler zu einem Bild in die DB sowie in der globalen $pictures_info
		*
		* @param string $text Künstler-Text
		* @param int/string $small_letter Der "Small-Letter" des Bildes.  (int bei Jägerhüttenbilder)
		* @param int/bool $different_user AccountID des Accounts, dessen Bilder ersetzt werden sollen (falls != von $session['user']['acctid'])
		* @return bool Ob's geklappt hat.
		* @author 2007 Jenutan for Atrahor.de
	*/
	public static function picture_save_author($text, $small_letter, $different_user = false)
	{
		global $session, $pictures_info;

		$user = $session['user']['acctid'];
		if ($different_user !== false) $user = $different_user;

		$text = stripslashes($text);
		$text = db_real_escape_string($text);

		if (!is_array($pictures_info[$user][$small_letter]))
		{
			self::picture_fill_info($user);
		}

		if (is_array($pictures_info[$user][$small_letter]))
		{
			$start = 'UPDATE ';
			$end = " WHERE 
				`userid`		= '" . $user . "' AND 
				`small_letter`	= '" . $small_letter . "'
			";
		}
		else
		{
			$start = 'INSERT INTO ';
			$end = '';
		}
		$sql = $start . "
				`" . self::PICTURE_TABLE . "`
			SET
				`userid`		= '" . $user . "',
				`small_letter`	= '" . $small_letter . "',
				`author`		= '" . $text . "',
				`time`			= NOW()
		" . $end;

		$pictures_info[$user][$small_letter]['author'] = stripslashes($text);

		self::picture_save_checker(0, $small_letter, $user);

		return db_query($sql);
	}

	public static function picture_save_ext_url($text, $small_letter, $different_user = false)
	{
		global $session, $pictures_info;

		$user = $session['user']['acctid'];
		if ($different_user !== false) $user = $different_user;

		$text = stripslashes($text);
		$text = db_real_escape_string($text);

		if (!is_array($pictures_info[$user][$small_letter]))
		{
			self::picture_fill_info($user);
		}

		if (is_array($pictures_info[$user][$small_letter]))
		{
			$start = 'UPDATE ';
			$end = " WHERE
				`userid`		= '" . $user . "' AND
				`small_letter`	= '" . $small_letter . "'
			";
		}
		else
		{
			$start = 'INSERT INTO ';
			$end = '';
		}
		$sql = $start . "
				`" . self::PICTURE_TABLE . "`
			SET
				`userid`		= '" . $user . "',
				`small_letter`	= '" . $small_letter . "',
				`ext_url`		= '" . $text . "',
				`time`			= NOW()
		" . $end;

		$pictures_info[$user][$small_letter]['ext_url'] = stripslashes($text);

		return db_query($sql);
	}
	
	/**
		* Holt den Künstler zu einem Bild aus der globalen $pictures_info
		*
		* @param int/string $small_letter Der "Small-Letter" des Bildes.  (int bei Jägerhüttenbilder)
		* @param int/bool $different_user AccountID des Accounts, dessen Bilder ersetzt werden sollen (falls != von $session['user']['acctid'])
		* @return string Den Künstler zum Bild
		* @author 2007 Jenutan for Atrahor.de
	*/
	public static function picture_get_author($small_letter, $different_user = false)
	{
		global $session, $pictures_info;
		
		$user = $session['user']['acctid'];
		if ($different_user !== false) $user = $different_user;
	
		$small_letter = addslashes($small_letter);
		$ersetze = array('[', ']');
		$small_letter = str_replace($ersetze, '', $small_letter);
		
		if (!is_array($pictures_info[$user][$small_letter]))
		{
			self::picture_fill_info($user);
		}
		
		if (is_array($pictures_info[$user][$small_letter]))
		{
			$ret = $pictures_info[$user][$small_letter]['author'];
		}
		else
		{
			$ret = '';
		}
		return $ret;
	}
	
	/**
		* Speichert den Kommentar-Text zu einem Bild in der DB sowie in der globalen $pictures_info
		*
		* @param string $text Der zu speichernde Text
		* @param int/string $small_letter Der "Small-Letter" des Bildes.  (int bei Jägerhüttenbilder)
		* @param int/bool $different_user AccountID des Accounts, dessen Bilder ersetzt werden sollen (falls != von $session['user']['acctid'])
		* @return bool Ob's geklappt hat...
		* @author 2007 Jenutan for Atrahor.de
	*/
	public static function picture_save_text($text, $small_letter, $different_user = false)
	{
		global $session, $pictures_info;
		
		$user = $session['user']['acctid'];
		if ($different_user !== false) $user = $different_user;
		
		$text = stripslashes($text);
		$text = db_real_escape_string($text);
		if (!is_array($pictures_info[$user][$small_letter]))
		{
			self::picture_fill_info($user);
		}
		
		if (is_array($pictures_info[$user][$small_letter]))
		{
			$start = 'UPDATE ';
			$end = " WHERE 
				`userid`		= '" . $user . "' AND 
				`small_letter`	= '" . $small_letter . "'
			";
		}
		else
		{
			$start = 'INSERT INTO ';
			$end = '';
		}
		$sql = $start . "
				`" . self::PICTURE_TABLE . "`
			SET
				`userid`		= '" . $user . "',
				`small_letter`	= '" . $small_letter . "',
				`text`			= '" . $text . "',
				`time`			= NOW()
		" . $end;
		
		$pictures_info[$user][$small_letter]['text'] = stripslashes($text);
		return db_query($sql);
	}
	
	/**
		* Holt den Kommentar-Text zu einem Bild aus der globalen $pictures_info
		*
		* @param int/string $small_letter Der "Small-Letter" des Bildes.  (int bei Jägerhüttenbilder)
		* @param int/bool $different_user AccountID des Accounts, dessen Bilder ersetzt werden sollen (falls != von $session['user']['acctid'])
		* @return string Den Text zum Bild
		* @author 2007 Jenutan for Atrahor.de
	*/
	public static function picture_get_text($small_letter, $different_user = false)
	{
		global $session, $pictures_info;
		
		$user = $session['user']['acctid'];
		if ($different_user !== false) $user = $different_user;
	
		$small_letter = addslashes($small_letter);
		$ersetze = array('[', ']');
		$small_letter = str_replace($ersetze, '', $small_letter);
		
		if (!is_array($pictures_info[$user][$small_letter]))
		{
			self::picture_fill_info($user);
		}
		
		if (is_array($pictures_info[$user][$small_letter]))
		{
			$ret = $pictures_info[$user][$small_letter]['text'];
		}
		else
		{
			$ret = '';
		}
		return $ret;
	}
	
	public static function picture_get_checker($small_letter, $different_user = false)
	{
		global $session, $pictures_info;
		
		if ($different_user !== false)
		{
			$user = $different_user;
		}
		else
		{
			$user = $session['user']['acctid'];
		}
		
		$small_letter = str_replace(array('[', ']'), '', $small_letter);
		
		self::picture_fill_info($user);
		
		if ((int)$pictures_info[$user][$small_letter]['checkedby']  === 0) return '`iUnkontrolliert!`i';
		
		$checker = $session['cache']['accounts'][$pictures_info[$user][$small_letter]['checkedby']]['name'];
		
		if ($checker)
		{
			$ret = $checker . '`0';
		}
		else
		{
			$ret = '`iUnbekannt!`i';
		}
		return $ret;
	}

	public static function get_quota($different_user = false)
	{
		global $session;
		if ($different_user !== false) {$user = $different_user;} else {$user = $session['user']['acctid'];}
		$user = intval($user);
		$quota = 0;
		foreach(glob(self::AVATAR_SECURE_DIR.'/'.$user.'.*.*') as $file) {
			$quota += filesize($file);
		}
		return $quota;
	}
	
	public static function picture_save_checker($checker_id, $small_letter, $different_user = false, $mir_zuweisen = false)
	{
		global $session, $pictures_info;
		
		if (!is_numeric($checker_id)) die('Hoppla, falsche checkerid!');
		
		if ($different_user !== false)
		{
			$user = $different_user;
		}
		else
		{
			$user = $session['user']['acctid'];
		}
		
		$small_letter = str_replace(array('[', ']'), '', $small_letter);
		
		self::picture_fill_info($user);
	
		if (is_array($pictures_info[$user][$small_letter]))
		{
			$start = 'UPDATE ';
			$end = " WHERE 
				`userid`		= '" . $user . "' AND 
				`small_letter`	= '" . $small_letter . "'
			";
		}
		else
		{
			$start = 'INSERT INTO ';
			$end = '';
		}
		$sql = $start . "
				`" . self::PICTURE_TABLE . "`
			SET
				`userid`		= '" . $user . "',
				`small_letter`	= '" . $small_letter . "',
				`checkedby`		= '" . $checker_id . "',
				" . ($mir_zuweisen?
					"`status`			= '" . $checker_id . "',"
					:
					"`status`			= 0,"
				) . "
				`time`			= NOW()
		" . $end;
		$pictures_info[$user][$small_letter]['checkedby'] = $checker_id;
		if ($mir_zuweisen)
		{
			$pictures_info[$user][$small_letter]['status'] = $checker_id;
		}
		return db_query($sql);
	}
	
	/**
	 * Beseitige alle Spuren (sowohl im temp. Verz. als auch im sicheren) eines bestimmten Bildes.
	 * Statische Methode
	 *
	 * @param int bild owner
	 * @param char bild typ
	 * @param boolean ob aus der db gelöscht werden soll
	 * @return Anzahl der gelöschten Bilder oder -1 bei Fehler
	 */
    public static function clear_old ($int_uid,$str_type,$bol_deletedb=true) {
        if((empty($int_uid) or empty($str_type)) and $str_type != 0) {
            return(-1);
        }
        $user_id = $int_uid;
        $small_letter = $str_type;

        $int_counter = 0;

        $str_old = CPicture::get_image_path($user_id,$small_letter,3,true);
        if(($str_old)) {
            $int_counter++;
            unlink($str_old);
        }
        $str_old = CPicture::get_image_path($user_id,$small_letter,2,true);
        if(($str_old)) {
            $int_counter++;
            unlink($str_old);
        }

        $sql = "
			DELETE FROM
				`" . self::PICTURE_TABLE . "`
			WHERE
				`userid`		= '" . $user_id . "'			AND
				`small_letter`	= '" . $small_letter . "'
			
		";
        $bol_deletedb ? db_query($sql) : false;

        return($int_counter);
    }

    public static function clear_all ($user_id) {
        $sql = "
			SELECT small_letter FROM
				" . self::PICTURE_TABLE . "
			WHERE
				`userid`		= '" . intval($user_id) . "'

		";

        $all = db_get_all($sql);

        foreach($all as $p)
        {
            self::clear_old($user_id,$p['small_letter'],true);
        }
    }

	/**
	 * Ersetzt Bildtags im gegebenen Text mit <img>-HTML-Code
	 * Statische Methode
	 *
	 * @param string $str_txt Referenz auf Text, der geparst werden soll
	 * @param int $int_acctid AccountID des Accounts, dessen Bilder ersetzt werden sollen
	 * @param int $int_lastedit Timestamp mit letzter Bioänderung (um Caching bei Änderungen der Bilder zu verhindern)
	 * 							Optional, sonst aktueller Timestamp (= wird vom Browser jedes Mal geladen)
	 * @param array $arr_types Bildtypen, die ersetzt werden sollen. Im Momment möglich:
	 * 							biopic (durchnummerierte Bilder), h (Hausavatar), p (Spielerava), m (Tierava), d (Knappenava)
	 * 							Wenn nichts angegeben bzw. Param = null, werden alle ersetzt.
	 * @param bool $bool_sulnk Bei Vorhandensein des nötigen Rechts Link zu Bilderverwaltung legen? (Optional, Standard false)
	 * 							Funktioniert noch nicht!
	 */
	public static function replace_pic_tags (&$str_txt, $int_acctid, $int_lastedit = null, $arr_types = null, $bool_sulnk = false, $width=null)
	{
		//$int_tmp = -1; //Wird nicht mehr gebraucht?
		//$str_sulnk = $str_sulnk_rec = '';
		
		global $access_control;

		if(is_null($arr_types))
		{
			$arr_types = array('m','p','d','h','biopic');
		}
		if(is_null($int_lastedit))
		{
			$int_lastedit = time();
		}
		
		//Für den SUPER-DAU x)
		$str_txt = str_replace('[PIC=]', '&#91;PIC=&#93;', $str_txt);
		
		
		
		//by bathi
		
		$aei = user_get_aei('msg_chars',$int_acctid);
		$msgChars = adv_unserialize($aei['msg_chars']);
		$has = count($msgChars);
		for($h=0; $h<$has;$h++){

			$small_letter = 'mc'.$h;
			
			//Den Autor zum Bild holen...
			$author = self::picture_get_author($small_letter, $int_acctid);
			if (!$author) $author = 'Nicht eingetragen!';
			
			//Bilder ersetzen, erst den Suchstring festlegen...
			$suche = '[PIC=' . $small_letter . ']';
			
			//Bastel den Pfad...
			$path = CPicture::get_image_path($int_acctid,$small_letter,1);
			
			//Wenn das Bild verfügbar ist, mach den ersetze-String zum HTML-Bildercode
			if (($path))
			{
				$ersetze = '<img src="' . $path . '" alt="Kein Bild gefunden!" '.( ($width != null) ? ' width="'.$width.'" ' : '').' title="&copy; ' . utf8_htmlentities($author) . '" />';
			}
			else
			{
				$ersetze = 'Kein Bild hochgeladen!';
			}
			
			//Ersetze die Bildertags
			$count = 0;
			$str_txt = str_replace($suche, $ersetze, $str_txt, $count);
			
			//Falls nix umgewandelt wurde:
			if (!$count)
			{
				$suche = array('[', ']');
				$small_letter = str_replace($suche, '', $small_letter);
				$str_txt = str_replace('[PIC=' . $small_letter, '&#91;PIC=' . $small_letter, $str_txt);
			}
		}
		
		//bathi end
		
		
		
		//Solange es noch "[PIC=" gibt...
		while (($pos = mb_strpos($str_txt, '[PIC=')) !== false)
		{
			//Den "Small-Letter" extrahieren x)
			$small_letter = mb_substr($str_txt, $pos + 5, 6);
            $small_letter = substr($small_letter,0,mb_strpos( $small_letter, ']'));
			$suche = array('[', ']');
			$small_letter = str_replace($suche, '', $small_letter);
			
			//Den Autor zum Bild holen...
			$author = self::picture_get_author($small_letter, $int_acctid);
			if (!$author) $author = 'Nicht eingetragen!';
			
			//Bilder ersetzen, erst den Suchstring festlegen...
			$suche = '[PIC=' . $small_letter . ']';
			
			//Bastel den Pfad...
			$path = CPicture::get_image_path($int_acctid,$small_letter,1);
			
			//Wenn das Bild verfügbar ist, mach den ersetze-String zum HTML-Bildercode
			if (($path))
			{
				$ersetze = '<img src="' . $path .  '" alt="Kein Bild gefunden!" '.( ($width != null) ? ' width="'.$width.'" ' : '').' title="&copy; ' . utf8_htmlentities($author) . '" />';
			}
			else
			{
				$ersetze = 'Kein Bild hochgeladen!';
			}
			
			//Ersetze die Bildertags
			$count = 0;
			$str_txt = str_replace($suche, $ersetze, $str_txt, $count);
			
			//Falls nix umgewandelt wurde:
			if (!$count)
			{
				$suche = array('[', ']');
				$small_letter = str_replace($suche, '', $small_letter);
				$str_txt = str_replace('[PIC=' . $small_letter, '&#91;PIC=' . $small_letter, $str_txt);
			}
		}
	}

	/**
	* Gibt die private Variable "valide" aus
	*
	* @return boolean Variable $this->valide;
	*/
	public function is_valide(){
		return $this->valide;
	}


	/**
	* Ändert die Bildgröße, wenn nötig!
	*
	* @param int $w Maximale Breite
	* @param int $h Maximale Höhe
	* @return boolean "True", wenn was geändert werden musste! "False", wenn alles schon >im Rahmen< war xD
	*/
	public function resize( $w = 200, $h = 200){
		$res = false;

		if( $this->width > $w ){
			$res = true;
			$fac = $w / $this->width;
			$this->width  = $w;
			$this->height = floor($fac*$this->height);
		}

		if( $this->height > $h ){
			$res = true;
			$fac = $h / $this->height;
			$this->height = $h;
			$this->width  = floor($fac*$this->width);
		}

		return $res;
	}

	/**
	* Speichert das Bild auf den Server, nachdem es überprüft wurde
	*
	* @param int $int_uid user_id
	* @param string $str_type type
	* @param int $w Maximale Breite
	* @param int $h Maximale Höhe
	* @param string $path Dorthin kommen die Bilder.
	* @return boolean "True", wenn alles glatt ging! "False", wenn is_valide() was zu meckern hat...
	*/
	public function save( $int_uid,$str_type, $w = 200, $h = 200, $path = self::AVATAR_UPLOAD_WEBDIR ){
		
		if( !$this->is_valide() ){
			return false;
		}
		$ret 		= true;
		$destname 	= $path.$int_uid.'.'.( is_numeric($str_type) ? '['.$str_type.']' : $str_type ).'.'.$this->ext;
		$res 		= ($w!=-1 ? $this->resize( $w, $h ) : false ); // bei $w = -1 wird nich resized
		//Die Variable $res wird anscheinend garnicht mehr gebraucht...
		
		switch( $this->ext ){
			case 'gif':
				$image = imagecreatefromgif( $this->tmp );
				$img = imagecreatetruecolor($this->width, $this->height);
				$black = imagecolorallocate($img, 0, 0, 0);
				imagecolortransparent($img, $black);
				imagecopyresized($img, $image, 0,0, 0,0, $this->width, $this->height, $this->width_orig, $this->height_orig);
				imagegif($img, $destname);
				imagedestroy($img);
				imagedestroy($image);
			break;
			case 'jpg':
				$img = imagecreatetruecolor( $this->width, $this->height );
				$image = imagecreatefromjpeg( $this->tmp );
				imagecopyresized($img, $image, 0, 0, 0, 0, $this->width, $this->height, $this->width_orig, $this->height_orig);
				imagejpeg($img, $destname, 100);
				imagedestroy( $image );
				imagedestroy( $img );
			break;
			case 'png':
				$small = imagecreatefrompng($this->tmp);
				imagesavealpha($small,true);
				$img = imagecreatetruecolor($this->width, $this->height);
    			imagesavealpha($img, true);
    			$trans_colour = imagecolorallocatealpha($img, 0, 0, 0, 127);
    			imagefill($img, 0, 0, $trans_colour);
				imagecopyresized($img, $small, 0,0, 0,0, $this->width, $this->height, $this->width_orig, $this->height_orig);
				imagesavealpha($img,true);
				imagesavealpha($small,true);
				imagepng($img, $destname);
				imagedestroy($small);
				imagedestroy($img); 
			break;
		}
		if('' != $this->tmp)unlink($this->tmp);
		if ($ret)
		{
			self::picture_save_checker(0,$str_type,$int_uid);
		}
		db_query("UPDATE " . CPicture::PICTURE_TABLE . " SET comments = '',ext='".$this->ext."' WHERE userid = '" . $int_uid . "' AND small_letter = '".$str_type."'");
		return $ret;
	}

	/**
	 * Statische Methode! Gibt Pfad zu einem Bild zurück.
	 * @author Saris
	 * @param int $int_uid Userid des Owners
	 * @param string $str_type Type des Bildes
	 * @param int $int_secure Ob Secure Ordner odner Upload Ordner
	 */
	 public static function get_image_path($int_uid,$str_type,$int_secure,$bool_serverpath=false)
	 {

	 	$sql = "
			SELECT 
				`ext`
			FROM
				`" . self::PICTURE_TABLE . "`
			WHERE
					`userid`		= '" . $int_uid . "'
				AND	`small_letter`	= '". $str_type ."'
		";
		
	 	$row = db_fetch_assoc(db_query($sql));
	 	$str_return_path = false;
		switch($int_secure)
		{
			case 0:
				$str_return_path = self::AVATAR_UPLOAD_WEBDIR.$int_uid.'.'.( is_numeric($str_type) ? '['.$str_type.']' : $str_type ).'.'.$row['ext'];
			break;
			case 1:
				$str_return_path = self::AVATAR_SECURE_WEBDIR.$int_uid.'.'.( is_numeric($str_type) ? '['.$str_type.']' : $str_type ).'.'.$row['ext'];
			break;
			case 2:
				$str_return_path = self::AVATAR_UPLOAD_DIR.$int_uid.'.'.( is_numeric($str_type) ? '['.$str_type.']' : $str_type ).'.'.$row['ext'];
			break;
			case 3:
				$str_return_path = self::AVATAR_SECURE_DIR.$int_uid.'.'.( is_numeric($str_type) ? '['.$str_type.']' : $str_type ).'.'.$row['ext'];
			break;
		}
		
		if(file_exists($str_return_path) == false)
		{
			$str_return_path = str_replace(array('[',']'),'',$str_return_path);
			if(file_exists($str_return_path) == false)
			{
				$str_return_path = false;
			}
		}

         if(!$bool_serverpath && $str_return_path)
         {
             $rowex = user_get_aei('imgtime',intval($int_uid));
             if(isset($rowex['imgtime']) && $rowex['imgtime'] == BIO_LOCKED) {
                 $str_return_path = false;
             }else{
                 $d=SECRET_IMG_KEY;
                 $key = hash('sha256',$d.$d.$d.$str_return_path.filemtime($str_return_path).$d.$d.$d);

                 $_SESSION['img'][$key] = $str_return_path;
                 $str_return_path = './picture.php?k='.$key;
             }
         }

		return $str_return_path;
	 }
	 
	 	/**
	 * Statische Methode! Gibt ext zu einem Bild zurück.
	 * @author Saris
	 * @param int $int_uid Userid des Owners
	 * @param string $str_type Type des Bildes
	 */
	 public static function get_ext($int_uid,$str_type)
	 {
	 	$sql = "
			SELECT 
				`ext`
			FROM
				`" . self::PICTURE_TABLE . "`
			WHERE
					`userid`		= '" . $int_uid . "'
				AND	`small_letter`	= '". $str_type ."'
		";
		
	 	$row = db_fetch_assoc(db_query($sql));
		return $row['ext'];
	 }
	 
	 /**
	 * Statische Methode! Lösche alle Bilder die zu einem Benutzer mit einer bestimmten ID gehören
	 * @author Dragonslayer changed by saris
	 * @copyright Dragonslayer for Atrahor.de
	 * @param int $int_uid Userid des zu löschenden Users
	 * @param string $str_small_letter Optionaler small letter, so dass nur manche Bilder gelöscht werden
	 */
	public static function delete($int_uid,$str_small_letter = '')
	{
		if($str_small_letter != '')
		{
			$str_sql_and = ' AND `small_letter` = "'.addstripslashes($str_small_letter).'"';
		}
		else 
		{
			$str_sql_and = '';
		}
		
		$sql = "
			SELECT 
				`small_letter`
			FROM
				`" . self::PICTURE_TABLE . "`
			WHERE
				`userid`	= '" . $int_uid . "'
			".$str_sql_and."
		";
		$db_result = db_query($sql);
		
		while ($arr_result = db_fetch_assoc($db_result)) 
		{		
			CPicture::clear_old($int_uid,(is_int($arr_result['small_letter'])?'['.$arr_result['small_letter'].']':$arr_result['small_letter']));	
		}
	}
}
?>
