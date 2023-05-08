<?php
$nestedtags=array();
$lastcolor=null;
$output='';
$BOOL_POPUP = false;

define('APPOENCODE_MODE_COLORS',1);
define('APPOENCODE_MODE_OTHER',2);
define('APPOENCODE_MODE_ALL',3);

define('PLU_MI_AUTO_GET', -1337);

/**
 * Erzeugt JS-Livepreview für Formatierungstags
 *
 * @param string Name des Eingabefeldes, für das Vorschau erzeugt werden soll
 * @return string Vorschaucode
 * @author bathi
 */
function js_preview ($str_fieldname) {
	$str_out = appoencode('`0').'<span id="'.$str_fieldname.'_prev"></span>
				'.JS::encapsulate('
				atrajQ(document).ready(function () {atrajQ("#'.$str_fieldname.'").keyup(function() {
                    atrajQ("#'.$str_fieldname.'_prev").html(parse(atrajQ(this).val()));
                });});
				');
	return($str_out);

}

/**
 * Entfernt HTML Tags aus einem Text. Arbeitet invers zur PHP Funktion strip_tags bei der man
 * alle tags angeben muss die erhalten bleiben sollen
 *
 * @author dragonslayer für Atrahor.de
 * @copyright dragonslayer für Atrahor.de
 * @example strip_selected_tags($str_text,array('b','br'),false,true);
 * @param string $str Text der bearbeitet werden soll
 * @param array $tags Array der die tags enthält die entfernt werden sollen.
 * 				Es dürfen keine spitzen Klammern verwendet werden
 * 				Syntax: array('b','a')
 * @param bool $bool_strip_content Soll der Inhalt der Tags auch entfernt werden?
 * @param bool $bool_strip_comment Sollen Kommentare entfernt werden?
 * @return String bereinigter String
 */
function strip_selected_tags($str, $tags = array(), $bool_strip_content = false, $bool_strip_comment = false)
{
	foreach ($tags as $tag)
	{
		$str = utf8_preg_replace('%(<\s*'.$tag.'[^>]*?>)(.*?)(<\s*\/\s*'.$tag.'[^>]*?>)%is',($bool_strip_content?'':'$2'),$str);
		$str = utf8_preg_replace('%(<\s*'.$tag.'[^>]*?>)%is','',$str);
	}
	//Strip comments but remember: This might destroy javascript!
	if($bool_strip_comment == true)
	{
		$str = utf8_preg_replace("%(<!--)(.*?)(-->)%is",'',$str);
	}
	return $str;
}

/**
 * internal function used by strip_selected_attributes
 *
 * @param string $str The string from which to strip the attributes
 * @return string The cleaned string
 */
function __strip_selected_attributes($treffer){
	global $G_remove_attributes_arr_attr;

    $str = $treffer[2];
	$str = stripslashes($str);
	$rem = implode('|',$G_remove_attributes_arr_attr);
	$str = utf8_preg_replace('/((('.$rem.')(\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+)/i','',$str);
	return '<'.$treffer[1].$str.$treffer[4].'>';
}

/**
 * Strip selected html attributes from any kind of tag
 *
 * @param string $str The string from which to strip the attributes
 * @param array $attributes contains all attributes which have to be stripped
 * Format "attribute_1","attribute_2",...,"attribute_n"
 * @return string The cleaned string
 */
function strip_selected_attributes($str, $arr_attr=array())
{
	global $G_remove_attributes_arr_attr;

	if(count($arr_attr)>0)
	{
		$G_remove_attributes_arr_attr = $arr_attr;
        return utf8_preg_replace_callback('#<(\/?\w+)((?:\s+\w+(\s*=\s*(?:".*?"|\'.*?\'|[^\'">\s]+))?)+\s*|\s*)(\/?)>#i','__strip_selected_attributes', $str);
	}
	else
	{
		return $str;
	}
}

/**
 * Säubert einen String von illegalen HTML Tags und Attributen
 *
 * @param string $str_html der zu reinigende String
 */
function clean_html($str_html = '',$bool_strip_tags = true, $bool_strip_attributes = true, $bool_addslashes = true, $bool_strip_images = true, $bool_allow_img_back=false, $xss=false)
{
	$str_html_copy = $str_html;
	$str_html_clean = '';
	$int_count = 0;
	while($str_html_clean != $str_html_copy)
	{
        $str_html_clean = $str_html_copy;

        if($xss)
        {
            $str_html_clean = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x19])/', '', $str_html_clean);
            $searchHexEncodings = '/&#[xX]0{0,8}(21|22|23|24|25|26|27|28|29|2a|2b|2d|2f|30|31|32|33|34|35|36|37|38|39|3a|3b|3d|3f|40|41|42|43|44|45|46|47|48|49|4a|4b|4c|4d|4e|4f|50|51|52|53|54|55|56|57|58|59|5a|5b|5c|5d|5e|5f|60|61|62|63|64|65|66|67|68|69|6a|6b|6c|6d|6e|6f|70|71|72|73|74|75|76|77|78|79|7a|7b|7c|7d|7e);?/ie';
            $searchUnicodeEncodings = '/&#0{0,8}(33|34|35|36|37|38|39|40|41|42|43|45|47|48|49|50|51|52|53|54|55|56|57|58|59|61|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|106|107|108|109|110|111|112|113|114|115|116|117|118|119|120|121|122|123|124|125|126);?/ie';
            while (preg_match($searchHexEncodings, $str_html_clean) || preg_match($searchUnicodeEncodings, $str_html_clean)) {
                $str_html_clean = utf8_preg_replace_callback($searchHexEncodings,function($m) { return chr(hexdec($m[1])); }, $str_html_clean);
                $str_html_clean = utf8_preg_replace_callback($searchUnicodeEncodings,function($m) { return chr($m[1]); }, $str_html_clean);
            }
        }

		//a...-tags
		$str_html_clean = utf8_preg_replace_callback('/<\s*(a[^>\s]*)([^>]*?)>(.*?)<\s*\/\s*(a[^>\s]*?)\s*>/isS',function ($match) {
			if('a' == $match[1]){
				$match[0] = utf8_preg_replace_callback('/href=["\']*([^"\'>]*)["\']*/isS',function ($match2) {
					if('#' == mb_substr($match2[1],0,1)){
						return 'href="'.str_ireplace(array('http','href','/','\\','.','javascript','"','\'','<','>',',',';'),'',$match2[1]).'"';
					}else{
                        $url = str_ireplace(array('https://www.atrahor.de/','http://www.atrahor.de/'),'',$match2[1]);
                        $url = str_ireplace(array('http','href','/','\\','javascript','"','\'','<','>',',',';'),'',$url);

                        if('steckbrief.php?id=' == mb_substr($url,0,18)){
                            return 'href="'.$url.'"';
                        }else if('bio.php?id=' == mb_substr($url,0,11)){
                            return 'href="'.$url.'"';
                        }
                    }
					return '';
				},$match[0]);
				return $match[0];
			}else{
				return str_ireplace(array('http','href'),'',$match[0]);
			}
		},$str_html_clean);

		if($bool_strip_tags)
        {
            $str_html_clean = strip_selected_tags(
                $str_html_clean,
                array_merge(
                    array(
                        'layer',
                        'script',
                        'noscript',
                        'object',
                        'embed',
                        'frame',
                        'iframe',
                        'applet',
                        'noframes',
                        'button',
                        'form',
                        'select',
                        'xml',
                        'head',
                        'body',
                        'html',
                        'pre',
                        'code',
                        'meta',
                        'blink',
                        'frameset',
                        'ilayer',
                        'bgsound',
                        'title',
						'base',

                        'audio',
                        'canvas',
                        'command',
                        'datalist',
                        'keygen',
                        'output',
                        'source',
                        'track',
                        'video',

                        'link'
                    ),
                    ($bool_strip_images?array('img'):array())
                )
            );
        }


		if($bool_strip_attributes)
        {
            $str_html_clean = strip_selected_attributes(
                $str_html_clean,
                array_merge(
                    array(
                        'accesskey',
                        'onabort',
                        'onactivate',
                        'onafterprint',
                        'onafterupdate',
                        'onbeforeactivate',
                        'onbeforecopy',
                        'onbeforecut',
                        'onbeforedeactivate',
                        'onbeforeeditfocus',
                        'onbeforepaste',
                        'onbeforeprint',
                        'onbeforeunload',
                        'onbeforeupdate',
                        'onblur',
                        'onbounce',
                        'oncellchange',
                        'onchange',
                        'onclick',
                        'oncontextmenu',
                        'oncontrolselect',
                        'oncopy',
                        'oncut',
                        'ondataavailable',
                        'ondatasetchanged',
                        'ondatasetcomplete',
                        'ondblclick',
                        'ondeactivate',
                        'ondrag',
                        'ondragend',
                        'ondragenter',
                        'ondragleave',
                        'ondragover',
                        'ondragstart',
                        'ondrop',
                        'onerror',
                        'onerrorupdate',
                        'onfilterchange',
                        'onfinish',
                        'onfocus',
                        'onfocusin',
                        'onfocusout',
                        'onhelp',
                        'onkeydown',
                        'onkeypress',
                        'onkeyup',
                        'onlayoutcomplete',
                        'onload',
                        'onlosecapture',
                        'onmousedown',
                        'onmouseenter',
                        'onmouseleave',
                        'onmousemove',
                        'onmouseout',
                        'onmouseover',
                        'onmouseup',
                        'onmousewheel',
                        'onmove',
                        'onmoveend',
                        'onmovestart',
                        'onpaste',
                        'onpropertychange',
                        'onreadystatechange',
                        'onreset',
                        'onresize',
                        'onresizeend',
                        'onresizestart',
                        'onrowenter',
                        'onrowexit',
                        'onrowsdelete',
                        'onrowsinserted',
                        'onscroll',
                        'onselect',
                        'onselectionchange',
                        'onselectstart',
                        'onstart',
                        'onstop',
                        'onsubmit',
                        'onunload'
                    ),
                    ( ($bool_strip_images  && !$bool_allow_img_back) ? array('background'):array())
                )
            );
        }




		if($bool_addslashes)
        {
            $str_html_clean = addstripslashes($str_html_clean);
        }
		//Holzhammermethode, wenn jemandem was besseres einfällt: Ändern! :)
		if($bool_strip_images && !$bool_allow_img_back)
        {
            $str_html_clean = str_ireplace('background','',$str_html_clean);
        }

		if($str_html_clean == $str_html_copy || $int_count > 10)
		{
			break;
		}
		else
		{
			$str_html_copy = $str_html_clean;
			$str_html_clean = '';
			$int_count++;
		}
	}

	return $str_html_clean;
}

/**
 * Remove a value from an array
 * @param string $val Value to remove
 * @param array $arr Array that contains the values
 * @return array $array_remval Array reduced by the specified element
 * @author Dragonslayer
 */
function array_remove_val($val, &$arr)
{
	$array_remval = $arr;
	$int_count = count($array_remval);
	for($x=0;$x<$int_count;$x++)
	{
		$i=array_search($val,$array_remval);
		if (is_numeric($i))
		{
			$array_temp  = array_slice($array_remval, 0, $i );
			$array_temp2 = array_slice($array_remval, $i+1, count($array_remval)-1 );
			$array_remval = array_merge($array_temp, $array_temp2);
		}
	}
	return $array_remval;
}

/**
 * Zeigt Text auf schriftrollenartigen Hintergrund mitsamt Scrollbalken und passender Formatierung
 * Inhalts-DIV ist im DOM über ID 'book_content' ansprechbar, Schriftrollen-DIV über ID 'book'
 * LOGD-Formatierungen sollten davor entfernt werden
 *
 * @param string Anzuzeigender Text
 * @return string Ausgabefertiges HTML
 * @author talion
 */
function show_scroll ($str_txt)
{
	return(print_frame($str_txt,'',1,true));
}

/**
 * Gibt Warnung vor Verlust der PvP-Immu aus
 *
 * @param bool $dokill Angriff bereits begangen? Optional, Standard false
 */
function pvpwarning($dokill=false)
{
	global $session;
	$days = getsetting('pvpimmunity', 5);
	$exp = getsetting('pvpminexp', 1500);
	if ($session['user']['age'] <= $days &&
	$session['user']['dragonkills'] == 0 &&
	$session['user']['user']['pk'] == 0 &&
	$session['user']['experience'] <= $exp)
	{
		if ($dokill)
		{
			output('`$Warnung!`^ Da du selbst noch vor PvP geschützt warst, aber jetzt einen anderen Spieler angreifst, hast du deine Immunität verloren!!`n`n');
			$session['user']['pk'] = 1;
		}
		else
		{
			output('`$Warnung!`^ Innerhalb der ersten '.$days.'  Tage in dieser Welt, oder bis sie '.$exp.' Erfahrungspunkte gesammelt haben, sind alle Spieler vor PvP-Angriffen geschützt. Wenn du einen anderen Spieler angreifst, verfällt diese Immunität für dich!`n`n');
		}
	}
}

/**
 * Fügt Daten zu Output hinzu, ohne diese durch appoencode zu transformieren
 *
 * @param string $indata Auszugebender Text
 */
function rawoutput($indata)
{
	global $output;
	$output .= $indata;
}

/**
 * @desc Macht eine Ausgabe auf dem Bildschirm wenn der User ein Superuser ist
 *
 * @param string $str_output Auszugebender String
 * @param bool Wenn true: Ausgabe per echo, sonst per output. Standard true
 */
function admin_output($str_output,$bool_echo=true)
{
	if(access_control::is_superuser())
	{
		if($bool_echo) {
			echo($str_output);
		}
		else {
			output($str_output,true);
		}
	}
}

function disp_array($array,$bool_raw=true,$str_prefix="[",$str_suffix="]")
{
	if (!is_array($array))
	{
		if ($bool_raw)
		{
			$array=str_replace("`","``",$array);
		}
		return $array;
	}
	global $int_counts,$bool_isarray;
    $str_table='';
	if ($int_counts==0)
	{
		$str_table.="<table>";
	}
	foreach($array as $key => $val)
	{
		if (!$bool_isarray)
		{
			$str_table.="<tr><td>";
			if ($int_counts>0)
			{
				for($i=0;$i<$int_counts;$i++)
				{
					$str_table.="</td><td>";
				}
			}
		}
		$bool_isarray = false;
		if ($bool_raw)
		{
			$key=str_replace("`","``",$key);
		}
		$str_table.=$str_prefix."$key".$str_suffix." </td><td>";
		if (is_array($val)){
			if ($val==array())
			{
				$str_table.="</td></tr>";
			}
			else
			{
				$bool_isarray = 1;
				$int_counts += 1;
				$str_table .= disp_array($val,$bool_raw,$str_prefix,$str_suffix);
			}
		}
		else
		{
			if ($bool_raw)
			{
				$val = str_replace("`","``",$val);
			}
			$str_table.=$val."</td></tr>";
		}
	}
	if ($int_counts>0)
	{
		$int_counts-=1;
	}
	else
	{
		$str_table.="</table>";
	}
	return $str_table;
}

/**
 * Fügt Text zur Ausgabe hinzu, transformiert diesen zunächst mittels appoencode
 *
 * @param string $indata Auszugebender Text
 * @param bool $priv HTML-Sonderzeichen umwandeln?
 */
function output($indata,$priv=true){
	global $output;

    $output.=appoencode($indata,$priv);
	//$output = str_replace('&amp;','&',$output);
}

/**
 * Fügt Text vorne an Ausgabe an, transformiert diesen zunächst mittels appoencode
 *
 * @param string $indata Auszugebender Text
 * @param bool $priv HTML-Sonderzeichen umwandeln?
 */
function headoutput($indata,$priv=true){
	global $output;
	$output=appoencode($indata,$priv).$output;
}

function music_set ($str_cat, $int_secs2wait = 20) {
    //tut nix mehr
}

/**
* Lädt Farben aus DB in Session, falls nötig (sprich: noch nicht in Session existent).
* @param bool Wenn true, wird Array in Session auf jeden Fall überschrieben (Optional, Standard false)
* @param bool Nur erlaubte Tags zurückgeben
* @return array Array mit Farben / Tags
* @author talion, Verwendet Eliwoods appoencode Erweiterung
*/
function get_appoencode ($bool_forcereload=false, $bool_get_allowed_only = false) {

	global $session;

	if($bool_get_allowed_only)
	{
		$arr_appoencode = Cache::get(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY, 'appoencode_allowed');
	}
	else
	{
		$arr_appoencode = Cache::get(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY, 'appoencode');
	}

	if(false === $arr_appoencode || $bool_forcereload) {

		//Cached Query
		$str_sql = 'SELECT * FROM appoencode WHERE active="1"'.($bool_get_allowed_only == true?' AND allowed="1"':'');
		$arr_appoencode = db_get_all($str_sql,'code');

		if(is_array($arr_appoencode) && count($arr_appoencode)>0)
		{
			//In der session speichern
			Cache::set(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY,'appoencode'.($bool_get_allowed_only == true?'_allowed':''),$arr_appoencode );
		}
		else
		{
			return array();
		}

	}

	return($arr_appoencode);

}

/**
* Entfernt Formatierungstags aus gegebenem String
* @param string String, aus dem Tags entfernt werden sollen.
* @param int 1: Nur Farbtags, 2: Nur Sonstige, 3: Alle (Optional, Standard 1)
* @return string Ergebnisstring.
* @author talion
*/
function strip_appoencode ($str_input,$int_mode=1, $skip=array()) {
	if($int_mode==1||$int_mode==3) {
        $str_input = str_replace('³','',$str_input);
        $str_input = str_replace('²','',$str_input);
        $str_input = utf8_preg_replace('/(#[abcdef0-9]{6};|#[abcdef0-9]{3};)/i','',$str_input);  //(?<!&)
	}
    $str_regex = str_replace($skip,'',regex_appoencode($int_mode));
	$str_input = utf8_preg_replace('/[`]['.$str_regex.']/','',$str_input);

	return($str_input);

}

/**
* Erstellt einen regulären Ausdruck zur Entfernung/Modifizierung der Formatierungstags
* @param int 1: Nur Farbtags, 2: Nur Sonstige, 3: Alle (Optional, Standard 1)
* @param bool Unerlaubte Codes in Regex aufnehmen (Optional, Standard true)
* @return string Regex
* @author talion
*/
function regex_appoencode ($int_mode=1,$bool_forbidden=true) {

	$arr_tags = get_appoencode();
	$str_regex = '';

	foreach($arr_tags as $tag => $c) {

		if( (	($int_mode == 1 && $c['color'] != '') ||
		($int_mode == 2 && $c['color'] == '') ||
		($int_mode == 3)
		) && ($bool_forbidden || $c['allowed'])
		) {

			$str_regex .= $tag;

		}

	}

	if($int_mode == 3 || $int_mode == 2) {
		$str_regex .= '0';
	}

	return( utf8_preg_quote($str_regex,'/') );

}

/**
 * Am Ende eines Strings ein `0 anhängen falls es gebraucht wird
 *
 * @param string $str_input
 */
function add_0_to_string($str_input)
{
	if(is_numeric(mb_strpos($str_input,'`')))
	{
		$int_offset = mb_strlen($str_input)-2;
		$bool_found = mb_strpos($str_input,'`0',$int_offset);
		if($bool_found === false)
		{
			$str_input .= '`0';
		}
	}
	return $str_input;
}

/**
 * Schaltet die Darstellung eines Hotkeys an wenn das gewünscht ist
 */
define ('CREATE_LINK_LEFT_NAV_HOTKEY',1);
//define ('CREATE_LINK_HIDE_STATUS_BAR',2);
/**
* Erzeugt einen HTML-Link und fügt auf Wunsch Navimöglichkeit hinzu.
* @param string $str_text Text, der verlinkt werden soll.
* @param string $str_lnk Linkpfad
* @param bool $bool_allownav Zu allowednavs hinzufügen? (Standard true)
* @param bool $bool_leftnav Zur Seitennavi links hinzufügen? (Standard false)
* @param string	$str_sure Sicherheitsabfrage; wenn nicht gegeben, keine
* @param bool $bool_popup Wenn true wird Link als Popup aufgerufen
* @param string $str_leftnavtext Text der Leftnav, wenn leer wird $str_text verwendet
* @param int $int_flags Übergabe von Flags zur steuerung einiger optionaler Parameter s. CREATE_LINK...Konstanten Verwendung: CONST_1 | CONST_2
* @return string HTML-Text mit fertigem Link
* @author talion
*/
function create_lnk ($str_txt, $str_lnk, $bool_allownav=true, $bool_leftnav=false, $str_sure='', $bool_popup = false, $str_leftnavtext=false, $int_flags = 0) {

	global $session;

	if(!empty($str_sure) && $bool_popup === false)
	{
		$str_sure = ' onClick="return confirm(\''.$str_sure.'\');" ';
	}
	elseif($bool_popup === true && !empty($str_sure))
	{
        $str_sure = ' onClick="if(confirm(\''.$str_sure.'\') == false){return false;}else{'.popup($str_lnk).';return false; }" ';
	}
	elseif($bool_popup === true && empty($str_sure))
	{
        $str_sure = ' onClick="'.popup($str_lnk).';return false;" ';
	}

	$str_out = '<a href="'.utf8_htmlentities($str_lnk).'" '.$str_sure.' >'.$str_txt.'</a>';

	if($bool_allownav) {
		if($bool_leftnav)
		{
			addnav( ($str_leftnavtext ? $str_leftnavtext : $str_txt) , $str_lnk , false, $bool_popup, false, ($int_flags & CREATE_LINK_LEFT_NAV_HOTKEY)?true:false, $str_sure);
		}
		addnav( '' , $str_lnk );
	}

	return($str_out);

}

/**
 * Gibt das Starttag eines Formulars an, erledigt auf Wunsch AddNav-Formalitäten
 *
 * @param string $str_action Link, zu dem Formular gesendet werden soll; entspricht addnav-Link
 * @param string $str_method HTTP-Requestmethode (GET oder POST, optional, Standard POST)
 * @param bool $bool_addnav Soll Link zu allowednavs hinzugefügt werden? (optional, Standard true)
 * @param string $str_formname Name + ID des Formulars. Wenn leer: Keine Zuweisung (Optional, Standard leer)
 * @param string $str_onsubmit OnSubmit-Inhalt für das Formular. Wenn leer: keiner (optional, Standard leer)
 * @return string HTML-Code des Formular-Tags
 */
function form_header ($str_action, $str_method='POST', $bool_addnav=true, $str_formname='', $str_onsubmit='') {

	if($bool_addnav) {
		addnav('',$str_action);
	}
	$str_ret = '<form accept-charset="utf-8" method="'.$str_method.'" action="'.$str_action.'"';
	if(!empty($str_formname)) {
        $id = $str_formname;
        $str_ret .= ' name="'.$str_formname.'" id="'.$str_formname.'"';
	}else{
        $id = 'form_'.time().e_rand(0,10000);
        $str_ret .= ' id="'.$id.'"';
    }
	$str_ret .= '>';

    if(!empty($str_onsubmit)) {
        $str_ret .= JS::event('#'.$id,'submit',$str_onsubmit);
    }

	return($str_ret);

}

/**
 * Gibt ein schließendes Form Tag aus
 * Ist eigentlich überflüssig, aber der Vollständigkeit halber enthalten
 *
 * @return string Schließendes Form Tag
 */
function form_footer () {
	return '</form>';
}


/**
 * Gibt auf Basis eines Inhalts-Arrays <option>-Tags für <select>-Tag zurück;
 * Erledigt Selektion des aktuellen Eintrags
 *
 * @param array $arr_content Assoz. Array 'Wert' => 'Text'
 * @param mixed $mixed_selected Aktuell selektierter Wert; optional, Standard false
 * @param bool $bool_strip_appo Appoencode-Tags entfernen; optional, Standard false
 * @return string HTML: <option>-Tags
 */
function form_sel_options ($arr_content, $mixed_selected=false, $bool_strip_appo=false) {

	$str_ret = '';

	foreach ($arr_content as $mixed_val => $mixed_txt) {
		$str_ret .= '<option value="'.$mixed_val.'"';
		// Wenn selektierter Wert gleich diesem
		if($mixed_selected === $mixed_val) {
			$str_ret .= ' selected="selected"';
		}
		$str_ret .= '>'.$mixed_txt.'</option>';
	}

	// Wenn Formatierungstags raus:
	if($bool_strip_appo) {
		$str_ret = strip_appoencode($str_ret,3);
	}

	return($str_ret);

}

/**
 * Fügt die zwei Funktionen addslashes und stripslashes zu einer Methode zusammen.
 * Erst Slashes entfernen und dann hinzufügen
 *
 * @param string $str_text Der zu escapende text
 * @return string Escaped String
 */
function addstripslashes($item, $key = '')
{
	return addslashes(stripslashes($item));
}

function own_addslashes($item, $key)
{
	return addslashes($item);
}

/**
 * Hilfsfunktion für uasort in fightnav()
 * sortiert eigenes Spezialgebiet als erstes
 * darunter nach Kategorie sortiert
*/
function specialtysort($a, $b)
{
	global $session;
	if ($a['specid'] == $session['user']['specialty']) return -1;
	if ($b['specid'] == $session['user']['specialty']) return 1;
	if ($a['category'] == $b['category']) return 0;
	return ($a['category'] > $b['category']) ? 1 : -1;
    /** @noinspection PhpUnreachableStatementInspection */
    return 0;
}
/**
 * Gibt Kampfnavigation aus.
 *
 * @param bool $allowspecial Spezialfähigkeiten zulassen
 * @param bool $allowflee Flucht zulassen
 */
function fightnav($allowspecial=true, $allowflee=true){
	global $PHP_SELF,$session, $access_control;
	//$script = str_replace("/","",$PHP_SELF);
	$script = mb_substr($PHP_SELF,mb_strrpos($PHP_SELF,'/')+1);
	addnav('Kämpfen',$script.'?op=fight');
	if ($allowflee) {
		addnav('Wegrennen',$script.'?op=run');
	}
	if (getsetting('autofight',0)){
		addnav('AutoFight');
		addnav('5 Runden kämpfen',$script.'?op=fight&auto=5');
		if($session['user']['dragonkills']>1)
		{ //und sollte sich nochmal jemand darüber aufregen dass das nicht bis zum Ende geht werden ihm die Hälfte der LP abgezogen
			addnav('Bis zum bitteren Ende',$script.'?op=fight&auto=200');
		}
		//elseif($session['user']['dragonkills']==2) addnav('B?12 Runden kämpfen',$script.'?op=fight&auto=12');
		elseif($session['user']['dragonkills']==1)
		{
			addnav('Bis 7 Runden kämpfen',$script.'?op=fight&auto=7');
		}
		else
		{
			addnav('3 Runden kämpfen',$script.'?op=fight&auto=3');
		}
	}
	if ($allowspecial) {
		addnav('`bBesondere Fähigkeiten`b');

		$arr_specialties = Cache::get(Cache::CACHE_TYPE_MEMORY,'specialties' );

		if(false === $arr_specialties) {

			$sql = "SELECT * FROM specialty WHERE active='1';";
			$arr_specialties = db_get_all($sql);

			Cache::set(Cache::CACHE_TYPE_MEMORY,'specialties',$arr_specialties);
		}

		uasort($arr_specialties,"specialtysort");
		foreach($arr_specialties as $row) {

			if($session['user']['specialtyuses'][$row['usename'].'uses']>0)
			{
				require_once './module/specialty_modules/'.$row['filename'].'.php';
				$f2 = $row['filename'].'_run';
				$f2('fightnav',$row['specid'],$script.'?op=fight');
			}

		}

		if ($access_control->su_check(access_control::SU_RIGHT_GODMODE)) {
			addnav('`&Superuser`0','');
			addnav('!?`&&bull; __GOD MODE`0',$script.'?op=fight&skill=godmode',true);
		}
		// spells by anpera, modded by talion
		$result = item_list_get( ' owner='.$session['user']['acctid'].' AND value1>0 AND (deposit1=0 OR deposit1='.ITEM_LOC_EQUIPPED.')
				AND (battle_mode = 1 OR battle_mode = 3) ', ' GROUP BY name ORDER BY tpl_class DESC, name ASC, value1 ASC, id ASC', true , ' SUM(value1) AS anzahl, name, id ' );

		$int_count = db_num_rows($result);

		if ($int_count>0) addnav(' ~ Dein Beutel ~ ');

		for ($i=1;$i<=$int_count;$i++)
		{
			$row = db_fetch_assoc($result);

			addnav($row['name'].' `0('.$row['anzahl'].'x)',$script.'?op=fight&skill=zauber&itemid='.$row['id'],false,false,false,false);
		}
		// end spells
	}
}

/**
* Erstellt CSS-Code für Farbformatierungen
* @return string CSS
* @author talion
*/
function write_appoencode_css () {

	$sql = "SELECT * FROM appoencode WHERE color!=''";
	$result = db_query($sql);

	$str_out = '';
	$int_code = 0;

	while($c = db_fetch_assoc($result)) {

		// Numerischer ASCII-Code für den Tag, um gültige CSS-Class zu erhalten
		$int_code = utf8_ord($c['code']);

		$str_out .= '.c'.$int_code;
		$str_out .= '{color:#'.$c['color'].';}';

	}

	return($str_out);
}

################################## //by bathi

function appoEncodeCallback($str)
{
    global $nestedtags,$lastcolor;
    $out = "";
    switch($str[1]){
        case '0':{
            if (isset($nestedtags['color']))$out = '</span>';
            unset($nestedtags['color']);
            $lastcolor = null;
        }break;
        case '`': $out = "`";break;
        case '>': $out = ">";break;
        case '<': $out = "<";break;
        case ' ': $out = " ";break;
        default:
        {
            $tag = $str[1];
            $a = get_appoencode();
            if(isset($str[3]) || isset($str[2]) || (isset($a[$tag]) && isset($a[$tag]['color'])) ){
                if (isset($nestedtags['color']))$out.='</span>';
                else $nestedtags['color']=true;
                if(isset($str[3])) $tout = '<span style="color:'.$str[3].'">';
                else if(isset($str[2])) $tout = '<span style="color:'.$str[2].'">';
                else $tout = '<span style="color:#'.$a[$tag]['color'].'">';
                $out .= $tout;
                $lastcolor = $tout;
            }else{
                $tagrow = $a[$tag];
                if($lastcolor != null){
                    $out.='</span>';
                }
                if (isset($nestedtags[$tagrow['tag']]) && strstr($tagrow['tag'],' /')==false) {
                    $out.='</'.$tagrow['tag'].'>';
                    unset($nestedtags[$tagrow['tag']]);
                } elseif (strstr($tagrow['tag'],' /')==true) {
                    $out.='<'.$tagrow['tag'].">\n";
                } else {
                    $out.='<'.$tagrow['tag'].' '.$tagrow['style'].'>';
                    $nestedtags[$tagrow['tag']] = true;
                }
                if($lastcolor != null){
                    $out.=$lastcolor;
                }
            }
        } break;
    }
    return $out;
}

/**
 * Wandelt Formatierungstags in einem String zu HTML/CSS-Äquivalenten um
 * @author by bathi
 * @param string Input, Text der bearbeitet werden soll
 * @param bool Wenn true, werden HTML-Codes nicht escaped
 * @return string Bearbeiteter Text
 */
function appoencode($data,$priv=true)
{
    global $nestedtags,$session,$Char;
    if(!$priv){
        $data = utf8_htmlspecialsimple($data);
    }
    $data = str_replace(array('&sup3;','&sup2;','&quot;'),array('³','²','"'),$data);
    if($session['user']['prefs']['nocolors']){
        $data = strip_appoencode($data,1);
    }
    else{
        $data = parse_name_color($data);
        if(mb_strpos($data,'³')!==false){
            $data = str_replace('³³','&sup3;',$data);
            $data = do_verlauf($data);
        }
        $data = str_replace('²²','&sup2;',$data);
    }
    $data = utf8_preg_replace_callback('/\`(.{1})|²(#[a-fA-F0-9]{6};)|²(#[a-fA-F0-9]{3};)/sU', 'appoEncodeCallback',$data);
    return $data;
}

function parse_name_color($data)
{
    $data = utf8_preg_replace_callback('/\(([^\|\)\(]*)\|([^\)]*)\)/sU', 'parse_name_color_callback',$data);
    return $data;
}

function parse_name_color_callback($data)
{
    $txt = $data[1];
    $name = parse_name_color_callback_get_name($data[2]);
    return empty($name) ? $data[0] : color_from_name($txt,$name);
}

function parse_name_color_callback_get_name($name)
{
    if($name!='')$user = db_get("SELECT name FROM accounts WHERE login LIKE '".db_real_escape_string($name)."' LIMIT 1");
    return !empty($user['name']) ? $user['name'] : false;
}

################################## //by bathi end

/**
 * Angegebene Tags am Ende des Strings schließen
 * (macht keinen Sinn bei Farben, da die nicht geschlossen werden)
 *
 * @param string $string Zu bearbeitender String
 * @param array $tags Liste mit zu schließenden Formatierungstags
 * @return string Bearbeiteter String
 */
function closetags($string, $tags)
{
	$tags = explode('`',$tags);
	$tag_count = count($tags);
	
	$clean_string = utf8_preg_replace("/³[^<>³]+³/imsS",'',$string);
	$clean_string = str_replace('``','',$clean_string);

	for($i = 0; $i<$tag_count; $i++)
	{
		$tags[$i] = trim($tags[$i]);
		if ($tags[$i]=='')
		{
			continue;
		}

		if (mb_substr_count($clean_string,'`'.$tags[$i])%2)
		{
			$string .= '`'.$tags[$i];
		}
	}
	return $string;
}

/**
 * Ersetzt Elemente in Templates
 *
 * @param string $itemname
 * @param array $vals
 * @return string
 */
function templatereplace($itemname,$vals=false)
{
	global $template, $backuptemplate, $output;

	if (!isset($template[$itemname]))
	{
		$output.=('<b>Warnung:</b> Das <i>'.$itemname.'</i> Template wurde nicht gefunden!<br>');
		//ganz primitive Fallback-Option
		$backuptemplate=loadtemplate('yarbrough');
		$template[$itemname]=$backuptemplate[$itemname];
	}
	$out = $template[$itemname];

	if(is_array($vals))
	{
		foreach ($vals as $key => $val)
		{
			$out = str_replace('{'.$key.'}',$val,$out);
		}
	}
	return $out;
}

/**
 * Gibt den Wert einer PLU-MI-ID zurück, wenn diese ID nicht gesetzt ist, wird der wert von $std zurückgegeben
 * @author Alucard
 * @param string $id ID des PLUMIS
 * @param int $std Standardwert falls ID nicht gefunden
 * @return int
 */
function plu_mi_get_val( $id, $std=1){
	global $session;
	if( !is_array($session['user']['plu_mi']) ){
		$session['user']['plu_mi'] = array();
		return $std;
	}
	return (int)(array_key_exists($id, $session['user']['plu_mi']) ? $session['user']['plu_mi'][$id] : $std);
}


/**
 * Erstellt ein Plumi Dokumentation -> http://forum.atrahor.de/index.php?topic=265.msg11396#msg11396
 * @author Alucard
 * @param string $id ID des PLUMIS
 * @param int $val Wert [0|1]
 * @param bool $request Soll ein request ausgeführt werden?
 * @param bool $only_on_click Soll nur der OnClick-Code zurückgegeben werden?
 * @return string
 */
function plu_mi( $id, $val=PLU_MI_AUTO_GET, $request=true, $only_on_click=false ){
	global $session;
	global $BOOL_JSLIB_PLU_MI;

	$id = JS::cleanID($id);
	$str = '';
	if( $val == PLU_MI_AUTO_GET ){
		$val = plu_mi_get_val($id);
	}
	if( !$BOOL_JSLIB_PLU_MI ){
		$BOOL_JSLIB_PLU_MI = true;
		addnav('','httpreq.php?op=switch_plu_mi');
	}
	if( $only_on_click ){

        if(JS::$defer){
            $str .= ' ';
            JS::event('#'.$id,'click','if(!isSet(PLU_MI_VALUES.'.$id.')){PLU_MI_VALUES.'.$id.'='.($val?'true':'false').';}PLU_MI(\''.$id.'\', (PLU_MI_VALUES.'.$id.' = !PLU_MI_VALUES.'.$id.'), '.($request?'true':'false').');');
        }else{
            $str .= 'onClick="if(!isSet(PLU_MI_VALUES.'.$id.')){PLU_MI_VALUES.'.$id.'='.($val?'true':'false').';}PLU_MI(\''.$id.'\', (PLU_MI_VALUES.'.$id.' = !PLU_MI_VALUES.'.$id.'), '.($request?'true':'false').');"';
        }
	}
	else{
		$str .= '
		<img style="display: '.($val?'inline':'none').'" id="plu_mi_mi_'.$id.'" src="./images/icons/stat_minus.gif" alt="einklappen" title="einklappen">
		'.JS::event('#plu_mi_mi_'.$id.'','click','PLU_MI(\''.$id.'\', false, '.($request?'true':'false').');').'
		<img  style="display: '.($val?'none':'inline').'" id="plu_mi_plu_'.$id.'" src="./images/icons/stat_plus.gif"  alt="ausklappen" title="ausklappen">
        '.JS::event('#plu_mi_plu_'.$id.'','click','PLU_MI(\''.$id.'\', true, '.($request?'true':'false').');').'
		';
	}
	return $str;
}

/**
 * Erstellt eine eindeutige ID für ein PLUMI-Objekt
 * @author Alucard
 * @param string $id ID des PLUMIS
 * @return string
 */
function plu_mi_unique_id( $id ){
	global $A_STR_PLUMI_UNIQUE_ID;
	if( !is_array($A_STR_PLUMI_UNIQUE_ID) ){
		$A_STR_PLUMI_UNIQUE_ID = array();
	}
    if(!isset($A_STR_PLUMI_UNIQUE_ID[$id]))$A_STR_PLUMI_UNIQUE_ID[$id]=0;
	return $id.((int)$A_STR_PLUMI_UNIQUE_ID[$id]++);
}

/**
 * Gibt Vitalinfo für Useroberfläche zurück (bzw. in ausgeloggtem Zustand die Einwohnerliste)
 *
 * @return string Ausgabe
 */
function charstats()
{
	global $session,$show_invent,$BOOL_COMMENTAREA, $access_control;
	$u =& $session['user'];
	if (isset($session['loggedin']) && $session['loggedin'])
	{
		{
			$CCharStats = new CCharStats();
	
			$invent = ' - ';
			if($show_invent)
			{
				$link = 'invent.php?r=1';
				$invent = '<a href="'.$link.'"><img src="./images/icons/beutel.gif" style="vertical-align:middle" title="Inventar" border="0" alt="Beutel">&nbsp;`IInventar`0</a>`n';
				addnav('',$link);

			}
            $ownbio = '<a href="javasctipt:void(0);" target="_blank" onClick="'.popup('bio.php?id='.$session['user']['acctid'], array('width'=>800,'height'=>600)).';return false;"><img src="./images/icons/bio.gif" style="vertical-align:middle" title="Eigene Biographie" border="0" alt="Bio"> `IBio`0</a>`n';
            //Bioverwaltung
            $ownbio .= '<a href="javasctipt:void(0);" target="_blank" onClick="'.popup('prefs_bio.php', array('width'=>800,'height'=>600)).';return false;"><img src="./images/icons/profil.gif" style="vertical-align:middle" title="Bioverwaltung" border="0" alt="Bioverwaltung"> `IBioverwaltung`0</a>`n';

            $profile = '<a href="javasctipt:void(0);" target="_blank" onClick="'.popup('prefs.php', array('width'=>800,'height'=>600)).';return false;"><img src="./images/icons/profil.gif" style="vertical-align:middle" title="Profil" border="0" alt="Profil"> `IProfil`0</a>`n';
            // Usernotes
            $usernotes = '<a href="javasctipt:void(0);" target="_blank" onClick="'.popup('usernotes.php').';return false;"><img src="./images/icons/notes.gif" style="vertical-align:middle" width="15" height="15" title="Notizen" border="0" alt="Notizen"> `INotizen`0</a>`n';

            $player = CQuest::nav();

			$u['hitpoints']=round($u['hitpoints'],0);
			$u['experience']=round($u['experience'],0);
			$u['maxhitpoints']=round($u['maxhitpoints'],0);
			$spirits=array(RP_RESURRECTION=>'Halbtot','-6'=>'Wiedererweckt','-2'=>'Sehr schlecht','-1'=>'Schlecht','0'=>'Normal','1'=>'Gut','2'=>'Sehr gut');
			if ($u['alive']==0)
			{
				$spirits[$u['spirits']] = 'TOT';
			}
			$atk=$u['attack'];
			$def=$u['defence'];
			if (!is_array($session['bufflist']))
			{
				$session['bufflist']=array();
			}
            $buffs='';
			foreach($session['bufflist'] as $val2)
			{
				$buffs.=appoencode('`#'.$val2['name'].' `7('.$val2['rounds'].' Runden übrig)`n`0',true);
				if (isset($val2['atkmod'])) {
					$atk *= $val2['atkmod'];
				}
				if (isset($val2['defmod'])) {
					$def *= $val2['defmod'];
				}
			}
			$atk = round($atk, 2);
			$def = round($def, 2);
			$atk = ($atk == $u['attack'] ? '`^' : ($atk > $u['attack'] ? '`@' : '`$')) . '`b'.$atk.'`b`0';
			$def = ($def == $u['defence'] ? '`^' : ($def > $u['defence'] ? '`@' : '`$')) . '`b'.$def.'`b`0';
			if (count($session['bufflist'])==0)
			{
				$buffs.=appoencode('`^Keine`0',true);
			}
			if ($u['petid']>0) {
				$pettime = max(0,strtotime($u['petfeed'])-time());
				// by Maris for Ibga ;)
				$days = ceil($pettime / (3600*24 / getsetting("daysperday",4)));
				if ($days<0) $days=0;
			}
			if($access_control->su_check(access_control::SU_RIGHT_DEBUG) && $u['prefs']['charinfo_debugfield']!='')
			{ //Debug-Ausgabe eines Feldes aus $session['user'] oder aei oder frei definierbar
				if(!array_key_exists($u['prefs']['charinfo_debugfield'],$u))
				{//wenn nicht gefunden, in aei suchen
					$rowe=user_get_aei();
					if(!array_key_exists($u['prefs']['charinfo_debugfield'],$rowe))
					$rowe[$u['prefs']['charinfo_debugfield']]='kein Suchfeld';
				}
				$content=(array_key_exists($u['prefs']['charinfo_debugfield'],$u)
				? $u[$u['prefs']['charinfo_debugfield']]
				: $rowe[$u['prefs']['charinfo_debugfield']]);
				if(mb_substr($content,0,2)=='a:')
				{
					$content=utf8_unserialize($content);
				}
				if(is_array($content))
				{
					$content=var_export($content,true);
				}
				else
				{
					$content=utf8_wordwrap(strip_tags($content),55,"\n",true);
				}
			}
			if (getsetting('dispnextday',0))
			{
				$time = gametime();
				$tomorrow = strtotime(date('Y-m-d H:i:s',$time).' + 1 day');
				$tomorrow = strtotime(date('Y-m-d 00:00:00',$tomorrow));
				$secstotomorrow = $tomorrow-$time;
				$realsecstotomorrow = round($secstotomorrow / (int)getsetting('daysperday',4));
			}
	
			$data['name'] = $u['name'];
			$data['hitpoints'] = $u['hitpoints'].'/'.$u['maxhitpoints'].grafbar($u['maxhitpoints'],$u['hitpoints']);
			$data['soulpoints'] = $u['soulpoints'].grafbar((5*$u['level']+50),$u['soulpoints']);
			$data['turns'] = $u['turns'];
			$data['deathpower'] = $u['deathpower'];
			$data['castleturns'] = $u['castleturns'];
			$data['gravefights'] = $u['gravefights'];
			$data['spirits'] = $spirits[(string)$u['spirits']].'`0';
			$data['level'] = $u['level'].'`0';
			$data['exp'] = expbar();
			$data['atk'] = $atk;
			$data['def'] = $def;
			$data['repu'] = '<img src="./images/trans.gif" width=1 height=4 alt="">'.grafbar(100,($u['reputation']+50));
			$data['psy'] = (10 + round(($u['level']-1)*1.5)).'`0';
			$data['geist'] = (10 + round(($u['level']-1)*1.5)).'`0';
	
			$data['gold'] = '<img src="./images/icons/gold.gif" style="vertical-align:middle" title="'.$u['gold'].' Gold dabei" alt="dabei:"> '.$u['gold'].'<br><img src="./images/icons/bank.gif" style="vertical-align:middle" title="'.$u['goldinbank'].' Gold auf der Bank" alt="Bank:"> '.$u['goldinbank'].'`0';
			$data['gems'] = '<img src="./images/icons/gem.gif" style="vertical-align:middle" title="'.$u['gems'].' Edelsteine dabei" alt="dabei:"> '.$u['gems'].'<br><img src="./images/icons/bank.gif" style="vertical-align:middle" title="'.$u['gemsinbank'].' Edelsteine auf der Bank" alt="Bank:"> '.$u['gemsinbank'].'`0';
	
		//Waffen- und Rüstungsname kürzen, damit diese nicht das Design verhauen
			$mb_strlen = 15;
			$subweapon = '';
			$subarmor = '';
	
			if (mb_strlen($u['weapon'])>$mb_strlen+1 && mb_strpos($u['weapon'],'³') === false) {
				$subweapon = mb_substr($u['weapon'], 0, $mb_strlen).'...';
			}
			if (mb_strlen($u['armor'])>$mb_strlen+1 && mb_strpos($u['armor'],'³') === false) {
				$subarmor = mb_substr($u['armor'], 0, $mb_strlen).'...';
			}
	
			$data['weapon'] = '<img src="./images/icons/waffe.gif" style="vertical-align:middle" alt=""> `7Angr: '.$u['weapondmg'].'`^`n'.$u['weapon'].'`0';
			$data['armour'] = '<img src="./images/icons/ruestung.gif" style="vertical-align:middle" alt=""> `7Vert: '.$u['armordef'].'`^`n'.$u['armor'].'`0';
			if (mb_strlen($subweapon)>0){
				$data['weapon'] = '<img src="./images/icons/waffe.gif" style="vertical-align:middle" alt=""> `7Angr: '.$u['weapondmg'].'`^`n
				<div onmouseover="javascript:document.getElementById(\'weapon\').style.display = \'block\';" onmouseout="javascript:document.getElementById(\'weapon\').style.display = \'none\';">`^'.$subweapon.'`0<div>
				<div style="display:none;position:absolute;border:solid 1px #C0C0C0;background:#000000;" id="weapon">`^'.$u['weapon'].'`0</div>';
			}
			if (mb_strlen($subarmor)>0){
				$data['armour'] = '<img src="./images/icons/ruestung.gif" style="vertical-align:middle" alt=""> `7Vert: '.$u['armordef'].'`^`n
				<div onmouseover="javascript:document.getElementById(\'armor\').style.display = \'block\';" onmouseout="javascript:document.getElementById(\'armor\').style.display = \'none\';">`^'.$subarmor.'`0<div>
				<div style="display:none;position:absolute;border:solid 1px #C0C0C0;background:#000000;" id="armor">`^'.$u['armor'].'`0</div>';
			}

			$data['animal'] = '';
			if($u['petid']>0)
			{
				$data['animal'] = '<font face="verdana" size=1>'.$days.'<br>'.grafbar(24*3600,$pettime).'</font>';
			}
			$data['debug'] = '';
			if($access_control->su_check(access_control::SU_RIGHT_DEBUG) && $u['prefs']['charinfo_debugfield']!='')
			{
				$data['debug'] = $content.'&nbsp;`0';
			}
			$data['quicknav'] = '';
			if($session['user']['prefs']['quicknav_enabled'] == 1)
			{
				$data['quicknav'] = jslib_quicknav();
			}

			$data['profil'] = '`0'.($session['user']['alive'] && $show_invent ? $invent:'').$profile.$ownbio.$usernotes.(!empty($player) ? $player:'').($session['user']['alive'] && $show_invent && $u['dragonkills']>=5 ? '':'');
	
			$data['nextd'] = '<span id="time"  style="display:none;">'.($realsecstotomorrow-2).'</span>`0';
			$data['buffs'] = $buffs;
	
			//"Wer ist hier"-liste
			if( $BOOL_COMMENTAREA && getsetting('chat_who_is_here',0))
			{
				$ool_id= "show_online_on_location";
	                if(!isset($data['whoishere']))$data['whoishere']='';

				$data['whoishere'] .='<tr><td><div id="show_online_on_location">'.CRPChat::getOOL().'</div></td></tr>
					';
	
			}
			else
			{
				$data['whoishere'] ='<tr><td>
						Ob sich hier jemand aufhält, wissen nur die Götter.
					</td></tr>';
			}
            $fo = $CCharStats->arr_default;

            $fo[4] = array('Wer ist hier? '.CRPChat::getStatusOOL(0,false,true,true),
                array
                (
                    array('title'=>'Wer ist hier','type'=>3,'su'=>false,'free'=>true,'value'=>'whoishere')
                )
            );

			$CCharStats->initialize_data($fo,$data);
			return $CCharStats->get_char_stats();
		}
	}
	else
	{
		return charstats_offline();
	}
}

function charstats_offline()
{
		//Administrator Addon by Hadriel @ anaras.ch
		//modded by Talion: nur noch ein Query
		$sql='SELECT name,superuser FROM accounts WHERE '.user_get_online().' ORDER BY superuser ASC, level AND dragonkills DESC';
		$result = db_query($sql);
        if(!isset($ret)) $ret = '';
		$onlinecount = db_num_rows($result);

		if($onlinecount == 0) {
			$ret .= '`bEs ist niemand online:`b`n
							`^Niemand';
		}
		else {

			$ret .= '`b`sMomentan spiel'.($onlinecount>1?'en':'t').' '.$onlinecount.' Charakter'.($onlinecount>1?'e':'').' in '.getsetting('townname','Atrahor').'`0`b`n`n';

			$arr_usergroups = utf8_unserialize((getsetting('sugroups','')) );

			$count = array(0=>0,1=>0,2=>0,3=>0);
			$out = array(0=>'',1=>'',2=>'',3=>'');

			while($row = db_fetch_assoc($result))
			{
				$type = 0;

				// In Liste gesondert zeigen?
				if($arr_usergroups[$row['superuser']][3]) {
					$type = $row['superuser'];
				}

				$out[$type] .= '`^'.$row['name'].'`n';
				$count[$type]++;
			}

			$str_what = '';

			//Gesamte Userliste fett anzeigen
			$ret .= '`b';
			foreach($out as $lvl => $lst) {

				if($lst) {
					$str_what = ($count[$lvl]>1 ? $arr_usergroups[$lvl][1] : $arr_usergroups[$lvl][0]);
					$ret.='`&'.$count[$lvl].' '.$str_what.':`n';
					$ret.=$lst.'`n';
				}

			}
			$ret .= '`b';
		}

		$ret.=grafbar(getsetting('maxonline',10),(getsetting('maxonline',10)-$onlinecount),'100%',5,'onlinecount_grafbar');
		$ret = appoencode($ret);

		if($onlinecount > getsetting('onlinetop',0)) {
			savesetting('onlinetop',$onlinecount);
			savesetting('onlinetoptime',time());
		}

		db_free_result($result);
		return $ret;
}

/**
 * Soll für die Formularausgabe ein multidimensionaler Array ausgegeben werden,
 * so erzeugt diese Funktion für generateform passende $data Arrays der Form
 * data[array_level_1][array_level_2][...]
 *
 * @param array $arr_data Der Eingabearray
 * @param string $str_prefix Der Präfix mit dem der Array im generateform
 * Dataarray angegeben wird z.B. $data[präfix]
 * @return array
 */
function generate_form_data($arr_data,$str_prefix = 'ext')
{
	$_arr_data = array();
	if(is_array($arr_data))
	{
		foreach	($arr_data as $key=>$val)
		{
			if(is_array($val))
			{
				$_arr_data = adv_array_merge
				(
					$_arr_data,
					generate_form_data
					(
						$val,
						$str_prefix.'['.$key.']'
					)
				);
			}
			$_arr_data[$str_prefix.'['.$key.']'] = $val;
		}
	}
	else
	{
		return $arr_data;
	}
	return $_arr_data;
}

/**
 * Params siehe bei showform - Erzeugt Formular. showform() dient als Wrapper
 *
 * @return string HTML mit fertigem Formular
 */
function generateform($layout,$row,$nosave = false,$savebutton='Speichern',$tabs=4,$reiteraslist=false)
{
	global $output,$session,$template,$load_ace;

	//Wieviele Formulare wurden bereits auf der aktuellen Seite erzeugt
	(isset($GLOBALS['generate_form_count']))?$GLOBALS['generate_form_count']++:$GLOBALS['generate_form_count']=0;
	$int_count_f = $GLOBALS['generate_form_count'];

	$js = '';

	if($tabs > 0) {
		$js .= '
			'.JS::encapsulate('
			var setting = 0;

			var count = atrajQ("#thecounter").data("count");
			function show_'.$int_count_f.'(setting)
			{
			  if (document.getElementById
			  && document.getElementById(setting).style.visibility == "hidden")
			  {
				document.getElementById(setting).style.visibility = "visible";
				document.getElementById(setting).style.display = "inline";

				document.getElementById("link_"+setting).className = "form_title_selected";




				var viewportWidth = atrajQ(window).width(),
            viewportHeight = atrajQ(window).height(),
            $foo = atrajQ("#"+setting),
            elWidth = $foo.width(),
            elHeight = $foo.height(),
            elOffset = $foo.offset();
             atrajQ(window)

            .scrollLeft(elOffset.left + (elWidth/2) - (viewportWidth/2));



			  }
			  else
			  {
				document.getElementById(setting).style.visibility = "hidden";
				document.getElementById(setting).style.display = "none";

				document.getElementById("link_"+setting).className = "form_title";
			  }


			}

			function hidden_'.$int_count_f.'()
			{

			  for(var x = 1; x < count; x++)
			  {
				var elements = document.getElementById('.$int_count_f.'+"_"+x);
				elements.style.visibility = "hidden";
				elements.style.display = "none";
				document.getElementById("link_'.$int_count_f.'_"+x).className = "form_title";
			  }
			}

			function set_setting_'.$int_count_f.'(setting)
			{
			  hidden_'.$int_count_f.'();
			  show_'.$int_count_f.'(setting);
			}
			');
	}

	$countt = 1;

	//Definition von Variablen
	$str_auto_focus = '';
	$int_reiterlen = 0;
	$int_count_normal_fields = 0;
	$bool_html_editor_loaded = false;
	$arr_html_editor_elements = array();
	$table='';

	foreach($layout as $key=>$val)
	{
		$extra_info = explode('|?',$val);
		$info = explode(",",$extra_info[0]);

		// Wenn wir Tooltips für dieses Element benötigen
		// Prüfen, ob wir das JS nicht bereits eingebunden haben
		$str_tooltip = '';
		if($extra_info[1]) {
			$str_tooltip = jslib_hint('&nbsp;<b>[?]</b>','`d<b>'.utf8_preg_replace('/\n|\r\n|\r/','',$info[0]).'</b>`0<br />'.utf8_preg_replace('/\n|\r\n|\r/','',$extra_info[1]),'lotgdHintSweet');
		}
		// END tooltips

		//Soll dieses Element den Fokus erlangen?
		if(in_array('focus',$info))
		{
			$str_auto_focus = $key;
			array_remove_val('focus',$info);
		}

		if ($info[1]=="title" || $countt == 1)	// Bei jedem Titelfeld ODER wenn noch kein Titelfeld geöffnet wurde
		{

			// Wenn neues 'Kapitel' eröffnet werden soll..
			if($info[2] != '2' || $countt == 1) {
				$str_txt = ($info[1] != 'title' ? '' : $info[0]);

				if($countt > 1) {	// Letztgeöffneten Container samt table schließen
					$table.="</table></div>";
				}

				// Neuen Container erstellen
				$table.="<div id='".$int_count_f."_".$countt."' style='"
				.($countt>1?"visibility: hidden; display: none;":"visibility: visible; display: block;")
				."'>";

				// Tabelle für Formular erstellen
				$table .= '<table cellspacing="'.$tabs.'" style="width:100%;"><tr><td colspan="2">';

				//$table.="<div style=' background: #666666;'>";
				if($info[2] != '1') {
					$table.=appoencode("`b`^$str_txt`0`b");
				}

				// Reiter
                if(!isset($reiter))$reiter='';
				if($tabs == 0 || ($countt>1 && ($countt-1) % $tabs == 0))
				{	// neue Zeile jeden $tabs. Reiter, aber nicht beim ersten
					if($reiteraslist)$reiter .= '';
                    else $reiter .= '</tr><tr>';
				}

				// Länge der Zeile bestimmen
				$int_len = mb_strlen($str_txt);
				if($int_len > 25) {
					$str_txt = mb_substr($str_txt,0,23).'..';
				}
                if($reiteraslist)
                {
                    $reiter.="<li class='form_title".($countt==1? '_selected':'')."' id='link_".$int_count_f."_".$countt."' >";
                    $reiter.=''.JS::event("#link_".$int_count_f."_".$countt,'click',"set_setting_".$int_count_f."('".$int_count_f."_".$countt."');").'<span>&nbsp;'.$str_txt.'&nbsp;</span>';
                    $reiter.='</li>';
                }
                else
                {
                    $reiter.="<td class='form_title".($countt==1? '_selected':'')."' id='link_".$int_count_f."_".$countt."' >";
                    $reiter.=''.JS::event('#link_'.$int_count_f.'_'.$countt,'click','set_setting_'.$int_count_f.'("'.$int_count_f.'_'.$countt.'");').'<span>&nbsp;'.$str_txt.'&nbsp;</span>';
                    $reiter.='</td>';
                }

				$countt++;
			}
			else {
				$table .= '<tr><td colspan="2">'.appoencode("`b`^".$info[0]."`0`b").'</td></tr>';
			}

		}
        if(!isset($submit_buttons))$submit_buttons='';
		//Einen logischen Divider ausgeben
		if($info[1]=="divider")
		{
			$table .= ($int_count_normal_fields>0?'</td></tr>':'').'
			<tr><td colspan="2"><div style="border-bottom:1px solid #FFCC00;font-weight:bolder;">'.$info[0].'</div>
			';
		}
		elseif ($info[1]=='submit_button')
		{
			$str_confirm = '';
			if(isset($info[3]))
			{
				$str_confirm = 'onClick="return confirm(\''.addslashes($info[3]).'\');"';
			}
			$submit_buttons .= "<input class='button' name='$key' id='$key' type='".(isset($info[2])?$info[2]:'button')."' ".$str_confirm." value=\"".utf8_htmlentities($info[0])."\" />";
		}
		elseif ($info[1]=="hidden")
		{
			$table.="<input type='hidden' name='$key' id='$key' value=\"".utf8_htmlentities($row[$key])."\">";
		}
		//Nur html ausgeben!
		elseif($info[1]=="html")
		{
			$table .= ($int_count_normal_fields>0?'</td></tr>':'').'
			<tr><td colspan="2">'.$row[$key];
		}
		// Bei Nicht-Titelfeldern
		elseif ($info[1]!="title")
		{
			if($info[1]!='hidden' && $info[1] != 'preview' && $info[1]!='html' && $info[1]!='title' && $info[1]!='viewonly' )
			{
				$str_no_line_style = 'border-bottom: 1px dotted gray;';
			}
			else
			{
				$str_no_line_style = '';
			}

			// Neue Zeile
			$table .= '	<tr><td valign="top" style="'.$str_no_line_style.'" '.
						(mb_strlen($info[0]) <= 55 ? 'nowrap' : '').
						'>';
						// Labeltext
						$table.=appoencode($info[0]);

						if($str_tooltip != '') {
							$table .= $str_tooltip;
						}

						// Zelle für Formularfeld öffnen
						$table .= '</td><td>';

		}

		$int_count_normal_fields++;

		// Prüfen, welchen Typ von Formularfeld
		switch($info[1])
		{
			case "title":	// wird oben erledigt
			case 'divider': // wird oben erledigt
			case 'html':	// wird oben erledigt
			case 'submit_button':
				break;
			case 'hidden':	// wird oben erledigt
				continue 2;
				break;
            case "textarea":
                // Restzeichenanzeige
                $str_rv = '';
                if(!isset($bool_leftchars_js))$bool_leftchars_js=false;
                if(($info[4] > 0 && $info[5] != true) ||
                    ($info[4] > 0 && (getsetting('htmleditor_enabled',1) == 0) ) ) {
                    $table.="Noch <span id='".$key."_jscounter'>".($info[4]-mb_strlen($row[$key]))."</span> Zeichen übrig.<br />";
                    $str_rv = 'onchange="CountMax('.$info[4].',\''.$key.'\');" onfocus="CountMax('.$info[4].',\''.$key.'\');" onkeydown="CountMax('.$info[4].',\''.$key.'\');" onkeyup="CountMax('.$info[4].',\''.$key.'\');"';
                    // Restzeichenanzeige, einbinden falls noch nicht vorhanden
                    if(!$bool_leftchars_js && (defined("FORM_LEFTCHARS") == false)) {
                        define('FORM_LEFTCHARS',true);
                        $bool_leftchars_js = true;
                        //Javascript für die Restzeichenanzeige der nachrichten, entnommen aus mail.php
                        $table =
                            JS::encapsulate('
						function CountMax(wert,el)
						{
							var max = wert;
							var handler_counter = document.getElementById(el+"_jscounter");
							var handler = document.getElementById(el);
							var str = handler.value;
							wert = max - str.length;

							if (wert < 0)
							{
								handler.value = str.substring(0,max);
								wert = max-str.length;
								handler_counter.innerHTML = wert;
							}
							else
							{
								handler_counter.innerHTML = max - str.length;
							}
						}
						') . $table;

                    }
                }
                //Stelle den HTML Editor dar
                if($info[5]==true && getsetting('htmleditor_enabled',1) == 1 )
                {
                    if(!$bool_html_editor_loaded)
                    {
                        $bool_html_editor_loaded = true;
                        $arr_html_editor_elements[] = $key;
                    }
                    $bool_toggle_link = true;
                }
                else
                {
                    $bool_toggle_link = false;
                }

                $table.="<textarea name='$key' id='$key' class='input' cols='$info[2]' rows='$info[3]'".$str_rv.">".$row[$key]."</textarea>
							<br>".($bool_toggle_link?"<a href='javascript:toggleEditor(\"$key\");'>Schalte den Editor an/aus</a>":'');



                // END Stelle den HTML Editor dar

                break;
            case "csseditor":

                $row[$key] = utf8_htmlspecialchars($row[$key]);

                $table.="
                <textarea name='$key'>".$row[$key]."</textarea>
                <div name='csseditor_$key' id='csseditor_$key' class='csseditor'>".$row[$key]."</div>
                ".JS::encapsulate("
                        var csseditor = ace.edit('csseditor_$key');
                        var ct = atrajQ('textarea[name=\"$key\"]').hide();
                        csseditor.setTheme('ace/theme/monokai');
                        csseditor.getSession().setMode('ace/mode/css');
                        csseditor.getSession().on('change', function(){
                          ct.val(csseditor.getSession().getValue());
                        });
                ");

                break;
            case "rawhtmleditor":

                $row[$key] = str_replace('`&amp;','`&',utf8_htmlspecialchars($row[$key]));

                $table.="
                <textarea name='$key'>".$row[$key]."</textarea>
                <div name='rawhtmleditor_$key' id='rawhtmleditor_$key' class='rawhtmleditor'>".$row[$key]."</div>
                ".JS::encapsulate("
                        var ".$key."_heditor = ace.edit('rawhtmleditor_$key');
                        var ".$key."_ht = atrajQ('textarea[name=\"$key\"]').hide();
                        ".$key."_heditor.setTheme('ace/theme/monokai');
                        ".$key."_heditor.getSession().setMode('ace/mode/html');
                        ".$key."_heditor.getSession().on('change', function(){
                          ".$key."_ht.val(".$key."_heditor.getSession().getValue());
                        });
                ");

                break;
			case 'usersearch':
				$table .= JS::Autocomplete($key,false,false,$row[$key],$info[2]);
				break;
			case 'file':
				$table .= '<input name="'.$key.'" id="'.$key.'" type="file">';
				break;
			case "enum":
				$table.='<select id="'.$key.'" name="'.$key.'">';
				$max = count($info);
				for($i = 2; $i < $max; $i += 2)
				{
					$optval = $info[$i];
					$optdis = $info[$i+1];
					$table.='<option value="'.$optval.'"'.($row[$key]==$optval?' selected':'').'>'.utf8_htmlentities($optval.' : '.$optdis).'</option>';
				}
				$table.='</select>';
				break;
				// Aufsteigende Liste von Zahlen by talion
				// info[2]: Von-Wert, info[3]: Bis-Wert
			case "enum_order":
				$bool_order = true;

				// Absteigend
				if($info[2] > $info[3]) {
					$bool_order = false;
				}

				$table.="<select id='$key' name='$key'>";

				for($i = $info[2]; $i <= $info[3]; $i+=($bool_order ? 1 : -1) ) {

					$table.='<option value="'.$i.'" '.($row[$key]==$i?' selected':'').'> '.$i.' </option>';

				}

				$table.="</select>";
				break;
			case "select":
				$table.='<select id="'.$key.'" name="'.$key.'">';
				$max = count($info);
				for($i = 2; $i < $max; $i += 2)
				{
					$optval = $info[$i];
					$optdis = $info[$i+1];
					$table.='<option value="'.$optval.'"'.($row[$key]==$optval?' selected':'').'>'.utf8_htmlentities($optdis).'</option>';
				}
				$table.='</select>';
				break;
				// added by talion
			//Description,select,#Items to show,[value,display],...
			case "select_multiple":
				$table.='<select name="'.$key.'[]" multiple="multiple" size="'.$info[2].'">';
				$max = count($info);
				for($i = 3; $i < $max; $i += 2)
				{
					$optval = $info[$i];
					$optdis = $info[$i+1];
					$selected = (is_array($row[$key]) && in_array( $optval, $row[$key] )?' selected':($row[$key]==$optval?' selected':''));
					$table.='<option value="'.$optval.'"'.$selected.'>'.utf8_htmlentities($optdis).'</option>';
				}
				$table.='</select>';
				break;
			case 'radio':
				$max = count($info);
				for($i = 2; $i < $max; $i += 2)
				{
					$optval = $info[$i];
					$optdis = $info[$i+1];
					$table.='<input type="radio" name="'.$key.'" value="'.$optval.'"'.($row[$key]==$optval?' checked':'').'> '.$optdis.'<br>';
				}
				break;
				// added by talion
			case 'checkbox':
				$table.='<input class="input" type="checkbox" name="'.$key.'" value="'.utf8_htmlentities($info[2]).'" '.($row[$key]==$info[2] ? ' checked':'').'>';
				break;
			case "password":
				$table.="<input type='password' name='$key' id='$key' value=\"".utf8_htmlentities($row[$key])."\">";
				break;
			case "bool":
				$table.="<select name='$key' id='$key'>";
				$table.="<option value='0'".($row[$key]==0?" selected":"").">Nein</option>";
				$table.="<option value='1'".($row[$key]==1?" selected":"").">Ja</option>";
				$table.="</select>";
				break;
			case "viewonly":
				$table.= appoencode(dump_item($row[$key]));
				break;
			case "int":
				$table.="<input name='$key' id='$key' value=\"".utf8_htmlentities($row[$key])."\" size='5'>";
				break;
			case 'text':
				$table.="<input name='$key' id='$key' value=\"".utf8_htmlentities($row[$key])."\" size='30' maxlength='".($info[2]!=''?$info[2]:'30')."'>";
				break;
				// added by talion
			case 'preview':
				$table.=js_preview($info[2]);
				break;

			case 'bitflag':

				array_splice($info, 0, 2);
				$int_cnt = count($info);
				if( !defined('FORM_BITFIELD') ){
					define('FORM_BITFIELD',true);
					$table .= JS::encapsulate('
								function form_setBit(id, bit, obj){
									var dest = document.getElementById(id);
									var val	 = dest.value;
									if( bit & val ){
										val ^= bit;
									}
									else{
										val |= bit;
									}
									dest.value = val;
								}

							   ');
				}
				$table .= '<table>';
                $tzu = 0;
				for($i=0,$b=1,$val=($row[$key]&$b);$i<$int_cnt;++$i,$b<<=1,$val=($row[$key]&$b)){
                    $tzu++;
					$table .= '<tr>';
					if($info[$i]!='0'){
						$table .= '<td>'.$info[$i].'</td><td><input id="form_setBit_'.$key.'_'.$tzu.'" type="checkbox" '.($val?'checked ':'').'value="'.$b.'">
						'.JS::event('#form_setBit_'.$key.'_'.$tzu.'','click','form_setBit(\''.$key.'\','.$b.',this)').'
						</td>';
					}
					else{
						$table .= '<td colspan="2"><i>nicht belegt</i></td>';
					}
					$table .= '<td>(2^'.$i.')</td></tr>';
				}
				$table .= '<tr><td></td><td colspan="2"><input size="10" type="text" style="text-align:right" readonly name="'.$key.'" id="'.$key.'" value="'.$row[$key].'"></td></tr>';
				$table .= '</table>';
				break;

            case 'hex_pick':
                $table.='<input type="text" id="'.$key.'" name="'.$key.'" class="hex_pick" value="'.utf8_htmlentities(get_hex_color_classiv($row[$key])).'">';
                break;

            case 'hex_pick_top':
                $table.='<input type="text" id="'.$key.'" name="'.$key.'" class="hex_pick_top" value="'.utf8_htmlentities(get_hex_color_classiv($row[$key])).'">';
                break;

            case 'datetime':
                $table.='<input type="text" id="'.$key.'" name="'.$key.'" value="'.utf8_htmlentities(get_hex_color_classiv($row[$key])).'">';
                $t = array();
                for($h=0;$h<24;$h++)
                {
                    for($m=0;$m<60;$m+=5)
                    {
                        $t[] = "'".str_pad($h, 2, '0', STR_PAD_LEFT).":".str_pad($m, 2, '0', STR_PAD_LEFT)."'";
                    }
                }
                JS::encapsulate("
                atrajQ('#".$key."').datetimepicker({
                    format:'Y-m-d H:i',
                    allowTimes:[".implode(',',$t)."],
                    theme:'dark'
                });
                ");
                break;

			case 'color_pick':
				array_splice($info, 0, 2);
				if( !defined('FORM_COLORPICK') ){
					$G_FORM_COLORPICK_ID = 0;
					define('FORM_COLORPICK',true);
					$table .= JS::encapsulate('
									var form_color_pick_act_picker = 0;
									function form_color_pick( id, color, code, key, add ){
										var obj = document.getElementById("form_colorpick_"+ id +"_picked");
										obj.style.background = "#"+color;
										obj = document.getElementById(key);
										obj.value = (add ? "`" : "") + code;
										obj = document.getElementById("form_colorpick_" + id + "_picker_char");
										obj.innerHTML = "&#0096;"+ code;
									}

									function form_color_pick_show( id ){
										var obj;
										if( form_color_pick_act_picker ){
											obj = document.getElementById("form_colorpick_"+ form_color_pick_act_picker +"_picker");
											obj.style.display = "none";
										}
										obj = document.getElementById("form_colorpick_"+ id +"_picker");
										obj.style.display = "block";
										form_color_pick_act_picker = id;
									}
							   ').'
							   <style type="text/css">
								   div.cp{
									   width: 14px;
									   height: 14px;
									   display: block;
									   cursor: pointer;
								   }
							   </style>';
				}
				$G_FORM_COLORPICK_ID++;
				$cp_color 	= (empty($row[$key]) ? $info[1] : $row[$key]);
				$cp_clear 	= str_replace('`','',$cp_color);
				$cp_appoen 	= get_appoencode(false,true); //Nur erlaubte Farben holen
				$cp_i		= 0;
				$cp_str		= '';
				$table 	   .= '<input type="hidden" name="'.$key.'" id="'.$key.'" value="'.$cp_color.'">';
                $tzu = 0;
				foreach( $cp_appoen as $cp ){
                    $tzu++;
					if( empty($cp['tag']) ){
						$cp_str .= '<td>
						<div id="form_color_pick_id'.$G_FORM_COLORPICK_ID.'_'.$tzu.'" class="cp" style="background: #'.$cp['color'].'"></div>
                        '.JS::event('#form_color_pick_id'.$G_FORM_COLORPICK_ID.'_'.$tzu.'','click','form_color_pick('.$G_FORM_COLORPICK_ID.',\''.$cp['color'].'\',\''.$cp['code'].'\',\''.$key.'\','.(int)$info[0].');').'
						</td>';

						++$cp_i;
						if( $cp_i > 9 ){
							$cp_str .= '</tr><tr>';
							$cp_i = 0;
						}
						if( $cp['code'] == $cp_clear ){
							$cp_color = $cp['color'];
						}
					}
				}

				$table 	   .= '	<div>
									<table>
										<tr>
											<td width="75">aktuell:</td>
											<td width="20" align="left">
											<div class="cp" style="display: block;background: #'.$cp_color.'" id="form_colorpick_'.$G_FORM_COLORPICK_ID.'_picked"></div>
                                              '.JS::event('#form_colorpick_'.$G_FORM_COLORPICK_ID.'_picked','click','form_color_pick_show('.$G_FORM_COLORPICK_ID.');').'
											</td>
											<td align="left"><div id="form_colorpick_'.$G_FORM_COLORPICK_ID.'_picker_char">&#0096;'.$cp_clear.'</div></td>
										</tr>
										<tr><td colspan="3"><div style="display: none;" id="form_colorpick_'.$G_FORM_COLORPICK_ID.'_picker"><table><tr>'.$cp_str.'</tr></table></div></td></tr>
									</table>
								</div>';
			break;
			default:
				$table.="<input size='30' name='$key' id='$key' value=\"".utf8_htmlentities($row[$key])."\">";
		}	// END formfeld-typ

		// Zelle für Formfeld und Zeile schließen
		$table .= '</td></tr>';
	}	// END Layout durchgehen

	//Wenn der HTML Editor geladen wurde, dann werden nur die Textareas übersetzt, die explizit angegeben wurden.
	if($bool_html_editor_loaded && getsetting('htmleditor_enabled',1) == 1 && (defined("FORM_HTMLEDITOR") == false))
	{
		define('FORM_HTMLEDITOR',true);

		$str_html_editor = '


						'.JS::encapsulate('jquery/ckeditor/ckeditor.js',true).'
                        '.JS::encapsulate('jquery/ckeditor/adapters/jquery.js',true).'

						'.JS::encapsulate('

                        var ckshow = false;
                        var ckk = null;

						function toggleEditor(id) {
							var elm = atrajQ("#"+id);

                            if(ckshow)
                            {
                                ckk.destroy();
                                ckshow = false;
                                undo_encoding(document.getElementById(id));
                            }
                            else
                            {
                                elm.ckeditor();
                                ckk = elm.ckeditorGet();
                                ckshow = true;
                            }




						}

						function undo_encoding (elm)
						{
							elm.value = elm.value.replace(/&amp;/g, \'&\');
							elm.value = elm.value.replace(/&lt;/g, \'<\');
							elm.value = elm.value.replace(/&gt;/g, \'>\');
							elm.value = elm.value.replace(/&quot;/g, \'"\');
						}
						');
		//$str_html_editor = str_replace('%htmleditor_elements%',join(',',$arr_html_editor_elements),$str_html_editor);

		//Produzierte IE Fehler...
		//add_to_header($str_html_editor);
		$table .= $str_html_editor;
	}

	// Letztgeöffneten Container schließen
	$table .= '</table></div>';

	$table = '<div id="thecounter" data-count="'.$countt.'"></div>'.$js.$table;
    if(!$reiteraslist)$reiter = str_replace("><",">\n<",$reiter);

	$table .= '<div align="center">'.($nosave?'':'<input type="submit" name="form_submit" id="form_submit" class="button" value="'.$savebutton.'">').$submit_buttons.'</div>';

	// Wenn nur ein Bereich vorhanden
	if($countt <= 2)
	{
		$reiter = '';
	}

	if($reiter>'')
	{
        if($reiteraslist)
        {
            $reiter = '
		<div id="lemenu"><ul id="ulmenu">'.$reiter.'</ul></div>';

        }
        else

        {
            $reiter = '
		<div id="lemenu"><center><table><tr>'.$reiter.'</tr></table></center></div>';
        }

	}

	if(!empty($str_auto_focus))
	{
		$str_autofocus_html = focus_form_element($str_auto_focus);
	}

	return $reiter.$table.$str_autofocus_html;
}


/**
 * Erzeugt aus Formularschablone und Formulardatensatz fertigen Formularcode UND gibt diesen aus
 * Wrapper für generateform()
 *
 * @param array Formular-Layout im Format: 'Feldname'=>'Feldtyp,Parameter|?Hilfetext/Tooltip'
 * @param array Daten als assoziativer Array: 'Feldname'=>'Feldinhalt'
 * @param bool Keinen Speicherbutton anbieten (optional, Standard false)
 * @param string Text des Speicherbuttons (optional, Standard 'Speichern')
 * @author eliwood, talion: tabellen statt divs für Formdarstellung, Alucard: showform prints return of generateform
 */
function showform($layout,$row,$nosave = false,$savebutton='Speichern',$tabs=4)
{
	rawoutput(generateform($layout, $row, $nosave, $savebutton, $tabs));
}

/**
 * @desc Lädt ein vorgegebenes Template
 *
 * @param string $templatename Enthält den Namen des zu ladenden Templates (Dateiname+Erweiterung)
 * @return array enthält das Template
 */
function loadtemplate($templatename)
{
	global $template;
	if (empty($templatename) || !file_exists(TEMPLATE_PATH.$templatename.'/tpl.php'))
	{
		$templatename=getsetting('defaultskin','dragonslayer_1');
	}

	//Title merken, sonst überschrieben beim Includen...
	$title = $template['title'];
	include_once(TEMPLATE_PATH.$templatename.'/tpl_data.php');
	$template['title'] = $title;
	return $template;
}

/**
 * Gibt Minibutton für IG-Mails zurück
 * @param bool $rtcheck Wenn true, gibt Funktion nur Inhalt des Buttons (=Link + Text) zurück; sonst + Rahmen. optional, standard false
 * @return string Knöpfchen
 */
function minimail( $rtcheck=false )
{
	global $session;
	$sql = 'SELECT sum(if(seen=0,1,0)) AS notseen FROM mail WHERE msgto=\''.$session['user']['acctid'].'\'';
	$result2 = db_query($sql);
	$mails = db_fetch_assoc($result2);
	db_free_result($result2);
	$mails['notseen']=(int)$mails['notseen'];

	if ($mails['notseen']>0)
	{
        $return = '<img src="./images/mail-message-new.png" border="0" onClick="'.popup('mail.php').'" style="cursor:pointer;">';
	}

	if( !$rtcheck ){
		$return = '<span id="MINILINK">'.$return.'</span>';
	}

	return($return);
}


/**
 * Setzt den Fokus auf ein HTML Form Element
 * Das Form Element muss durch die ID $str_element gültig definiert sein
 *
 * @param String $str_element Form Element auf das der Fokus gelegt werden soll
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor.de
 */
function focus_form_element($str_element,$bool_return = true)
{
	$str_output = JS::Focus($str_element,false);
	if($bool_return == true)
	{
		return $str_output;
	}
	else
	{
		global $output;
		$output .= $str_output;
	}
}

/**
 * Gibt Linkbutton für IG-Mails zurück
 *
 * @param bool $rtcheck Wenn true, gibt Funktion nur Inhalt des Buttons (=Link + Text) zurück; sonst + Rahmen. optional, standard false
 * @return string Fertiger Button
 */
function maillink( $rtcheck=false )
{
	global $session;

	$sql = 'SELECT sum(if(seen>=1,1,0)) AS seencount, sum(if(seen=0,1,0)) AS notseen FROM mail WHERE msgto=\''.$session['user']['acctid'].'\' AND archived=0';
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	db_free_result($result);
	$row['seencount']=(int)$row['seencount'];
	$row['notseen']=(int)$row['notseen'];

	$return = '';
	if($row['seencount']>=getsetting('inboxlimit',50) && !access_control::is_superuser())
	{
		$return .= '
		<div style="z-index:5; float:left; padding-bottom:10px; padding-top:10px; background-color:black; vertical-align:middle; width:100%; height:auto; font-size:20px; top:2px; left:0px; position:relative; border:2px solid red; background-image: url(/templates/dragonslayer_1/bg_tile_stressedmetal.jpg);">

			Zu viele Mails!<br>
			Du hast zu viele Briefe in Deiner Schatulle. Bitte entferne einige!<br>';
        $return .= '<a href="#" target="_blank" onClick="'.popup('mail.php').';return false;" >Brief-Schatulle aufräumen</a>';

		$return .= '</div>';
	}

		$return .= '<a href="mail.php" target="_blank" onClick="'.popup('mail.php').';return false;" class="'.($row['notseen']>0?'hotmotd':'motd').'">Brieftauben: '.$row['notseen'].'&nbsp;neu,&nbsp;'.$row['seencount'].'&nbsp;alt</a>';

	if( !$rtcheck ){
		$return = '<span id="MAILBOXLINK">'.$return.'</span>';
	}
	return $return;
}



function motclink()
{
    global $session;
    $return = '';
    if ($session['user']['lastmotc'] == '0000-00-00 00:00:00')
    {
        $return .=  '<a href="motd-coding.php?check=all" target="_blank" onClick="'.popup('motd-coding.php?check=all').';return false;" class="hotmotd"><b>MoTC</b></a>';
    }
    else
    {
        $return .= '<a href="motd-coding.php?check=all" target="_blank" onClick="'.popup('motd-coding.php?check=all').';return false;" class="motd"><b>MoTC</b></a>';
    }
    return $return;
}



/**
 * Gibt Linkbutton für MoTD zurück
 *
 * @return string Fertiger Button
 */
function motdlink()
{
	global $session;

    $return = '';
    if ($session['needtoviewmotd'])
    {
        $return .= '<a  href="motd.php" target="_blank" onClick="'.popup('motd.php').';return false;" class="hotmotd"><b>MoTD</b></a>';
    }
    else
    {
        $return .= '<a  href="motd.php" target="_blank" onClick="'.popup('motd.php').';return false;" class="motd"><b>MoTD</b></a>';
    }
    return $return;
}

/**
 * Fügt beliebigen Code in den Seiten Kopf ein
 * Arbeitet mit momentan mit str_replace, ist also mehr als verbesserungswürdig,
 * aber es funktioniert ;-)
 *
 * @param string $str_source Einzufügender Text
 * @author dragonslayer
 */
function add_to_header($str_source = '')
{
	global $header;
	if($str_source != '')
	{
		$header = str_replace('</head>',$str_source."\n</head>\n",$header);
	}
}

/**
 * Erzeugt Seitenkopf
 *
 * @param string $title Seitentitel
 */
function page_header($title=GAME_VERSION,$header_info=false,$bool_ace=false)
{
	global $HEADER_LOADED,$SCRIPT_NAME,$session,$template,$global_title;

    $global_title = $title;

    if(!$HEADER_LOADED)
    {
        if(!isset($session['user']['prefs']['nocolors'])) $session['user']['prefs']['nocolors'] = false;
        $str_colors_css = '';
        if ($session['user']['loggedin'] && $session['user']['prefs']['nocolors']==false)
        {
            $hotkey_hexcode = Cache::get(Cache::CACHE_TYPE_SESSION,'hotkey_hexcode' );
            if($hotkey_hexcode == false)
            {
                $arr_aei = user_get_aei('hotkey_hexcode');
                $hotkey_hexcode = $arr_aei['hotkey_hexcode'];
                Cache::set(Cache::CACHE_TYPE_SESSION , 'hotkey_hexcode', $hotkey_hexcode);
            }
            if ($hotkey_hexcode != 'default')
            {
                $str_colors_css .= '<style type="text/css">.navhi { color: #'.$hotkey_hexcode.' !important; }</style>';
            }
        }
        $str_rss ='';
        //Insert RSS Feed
        if(getsetting('rss_enable_motd_feed',1) == 1)
        {
            $str_rss_address = is_null_or_empty(getsetting('rss_motd_feed_address','')) ? getsetting('server_address','').'motdrss.php' : getsetting('rss_motd_feed_address','');
            $str_rss = '<link rel="alternate" type="application/rss+xml"
					title="RSS" href="'.$str_rss_address.'" >';
        }
        //Insert Metadata
        $str_metadata = '
        <meta name="keywords" content="'.getsetting('server_meta_keywords','Atrahor,lotgd').'" >
        <meta name="description" content="'.getsetting('server_meta_description','Atrahor.de').'" >
        <meta http-equiv="Content-Script-Type" content="text/javascript">
        <meta NAME="language" CONTENT="de">
        <meta NAME="robots" CONTENT="index,follow">
        ';
        if(!isset($session['user']['lastmotd'])) $session['user']['lastmotd'] =  null;
        if(!isset($session['user']['lastmotc'])) $session['user']['lastmotc'] = null;
        $hs = '';
        $template['headscript'] .= $str_metadata.$str_colors_css.$str_rss.$hs;
        JS::SetHeader(false,($session['user']['lastmotd'] == '0000-00-00 00:00:00'),($session['user']['lastmotc'] == '0000-00-00 00:00:00'),$bool_ace,true);
    }
    $template['headscript'] .= $header_info;
	$template['title'] = $title;
    $HEADER_LOADED = true;
}

/**
 * Gibt JS-Link (onclick="<link>") für Popup zurück
 *
 * @param string $page URL der Seite, die in popup geöffnet werden soll
 * @param array $conf Assoz. Array mit Konfiguration für Popup-Fenster (width, height, scrollbars [yes|no], resizable [yes|no])
 * @return string JS-Befehl zum Öffnen
 */
function popup($page, $conf = array())
{
	$conf['scrollbars'] = isset( $conf['scrollbars'] ) 	? $conf['scrollbars'] 	: 'yes';
	$conf['resizable'] 	= isset( $conf['resizable'] ) 	? $conf['resizable'] 	: 'yes';
	$conf['width'] 		= isset( $conf['width'] ) 		? $conf['width'] 		: '800';
	$conf['height'] 	= isset( $conf['height'] ) 		? $conf['height'] 		: '700';
	$ret = "window.open('".utf8_htmlentities($page)."','".utf8_preg_replace('([^[:alnum:]])','',$page)."','";
	$a   = '';
	foreach( $conf as $k => $v ){
		$a .= ( !empty($a)?',':'' ).$k.'='.$v;
	}
	return $ret.$a."')";
}

/**
 * Erzeugt Seitenende (Navis usw.) und beendet Ausgabe. Speichert Userdaten (-> saveuser())
 *
 * @uses saveuser
 */
function page_footer()
{
	global 	$output,$nestedtags,$lastcolor,$nav,$session,$REMOTE_ADDR,$REQUEST_URI,
	$pagestarttime,$quickkeys,$template,
	$BOOL_COMMENTAREA,$g_arr_addnav_menu, $access_control, $Char, $JSLIB_MENUINIT;

	//Quick and dirty fix gegen ND Bug.
	prevent_getting_stuck();

	$bool_vitalout = false;
	// Vitalinfo im Dragonslayer-Tpl ausblenden, wenn gewünscht by talion
	if($session['user']['prefs']['template'] == 'dragonslayer_1' && $BOOL_COMMENTAREA && $session['disablevital']) {
		$bool_vitalout = true;
	}

	if (isset($nestedtags['color']))
	{
		$output.='</span>';
		unset($nestedtags['color']);
        $lastcolor = null;
	}
	foreach($nestedtags as $key=>$val)
	{
		$output.='</'.$key.'>';

		unset($nestedtags[$key]);
	}

	///
	/// Werbung an/abschalten
	///
	if(getsetting('ad_enabled',1) == 1)
	{
		$template['ad'] = getsetting('ad_html','');
	}
	else
	{
		$template['ad'] = '';
	}

	if(getsetting('paypal_enabled','1') == 1)
	{
		if(!isset( $session['user']['name']))  $session['user']['name'] = '';
		if(!isset( $session['user']['login']))  $session['user']['login'] = '';

		//NOTICE
		//NOTICE Although I will not deny you the ability to remove the below paypal link, I do request, as the author of this software
		//NOTICE that you leave it in.
		//NOTICE
		$paypalstr = '<table align="center"><tr>';
		if(getsetting('paypal_author_enabled','1') == 1)
		{
			$paypalstr .= '
			<td><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="nahdude81@hotmail.com">
				<input type="hidden" name="item_name" value="Legend of the Green Dragon Author Donation from '.strip_appoencode($session['user']['name'],3).'">
				<input type="hidden" name="item_number" value="'.utf8_htmlentities($session['user']['login']).":".$_SERVER['HTTP_HOST']."/".utf8_htmlentities($_SERVER['REQUEST_URI']).'">
				<input type="hidden" name="no_shipping" value="1">
				<input type="hidden" name="cn" value="Your Character Name">
				<input type="hidden" name="cs" value="1">
				<input type="hidden" name="currency_code" value="EUR">
				<input type="hidden" name="tax" value="0">
				<input type="image" src="./images/paypal1.gif" style="border:none;" name="submit" alt="Donate!">
			</form></td>';
		}

		if(getsetting('paypal_server_enabled','1') == 1)
		{
			$paysite = getsetting('paypal_email', '');
			if ($paysite != '')
			{
				$paypalstr .= '<td>';
				$paypalstr .= '
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="business" value="'.$paysite.'">
					<input type="hidden" name="item_name" value="Spende aus '.getsetting('townname','Atrahor').' von '.strip_appoencode($session['user']['name'],3).'">
					<input type="hidden" name="item_number" value="'.utf8_htmlentities($session['user']['login']).":".getsetting('server_address','').'">
					<input type="hidden" name="no_shipping" value="1">
					<input type="hidden" name="cn" value="Your Character Name">
					<input type="hidden" name="cs" value="1">
					<input type="hidden" name="currency_code" value="EUR">
					<input type="hidden" name="tax" value="0">
					<input type="image" src="./images/paypal2.gif" style="border:none;" name="submit" alt="Spenden!">
				</form></td>';
			}
			else
			{
				$paypalstr .= '<td>Du hast keine E-Mail Adresse für deinen Paypal Account hinterlegt!</td>';
			}
		}
		$paypalstr .= '</tr></table><br>';
		$template['paypal'] =& $paypalstr;
	}

	$template['nav'] =& $nav;

    $template['motd'] = motdlink();
    $template['motc'] = motclink();

    $template['faq'] = '<a href="petition.php?op=faq" target="_blank" class="motd" onClick="'.popup('petition.php?op=faq').';return false;">FAQ</a>';

	//Wenn der User eingelogged ist
	if (isset($session['user']['acctid']) && $session['user']['acctid']>0)
	{
		$template['mail'] = maillink();
        $template['referral'] = '';
        $template['ignore'] = '<a href="bathorys_popups.php?mod=ignore" target="_blank" class="motd" onClick="'.popup('bathorys_popups.php?mod=ignore').';return false;">Ignore</a>';

    }
	//Für alle anderen
	else
	{
		$template['mail'] = '';
		$template['referral'] = '';
        $template['ignore'] = '';
	}

    $template['petition'] = '<a href="petition.php" onClick="'.popup('petition.php').';return false;" target="_blank" class="motd">Hilfe anfordern</a>';


    $str_append = "<table border='0' cellpadding='5' cellspacing='0' align='right'>";

	if ($access_control->su_check(access_control::SU_RIGHT_PETITION))
	{
		$sql = 'SELECT max(lastact) AS lastact, count(petitionid) AS c,status FROM petitions GROUP BY status';
		$result = db_query($sql);
		$petitions=array(0=>0,1=>0,2=>0);
		$petitions['star'] = '';
		$petitions['unread'] = false;
		$int_count = db_num_rows($result);
        if(!isset($session['lastlogoff']))$session['lastlogoff']=0;
		for ($i=0;$i<$int_count;$i++)
		{
			$row = db_fetch_assoc($result);
			$petitions[(int)$row['status']] = $row['c'];
			if ($row['lastact']>$session['lastlogoff'] && $row['status']<2)
			{
				$petitions['unread'] = true;
			}
		}
		db_free_result($result);
		// Neue Petitionen; schauen, ob Sternchen nötig ist
		if ($petitions['unread'])
		{
			$sql = 'SELECT petitionid, lastact FROM petitions WHERE lastact > "'.$session['lastlogoff'].'" AND status < 2';
			$result = db_query($sql);
			while ($row = db_fetch_assoc($result))
			{
				if (!isset($session['petitions'][$row['petitionid']]) || !$session['petitions'][$row['petitionid']])
				{
					$petitions['star'] = appoencode('`$*`0');
					break;
				}
				elseif($session['petitions'][$row['petitionid']]<$row['lastact'])
				{ //neue Aktivität in bekannter Anfrage
					$petitions['star'] = appoencode('`J*`0');
				}
			}
			db_free_result($result);
		}

		$pet_link = 'superuser.php?op=intro_pet';
		$template['su_petitionlist'] = '<tr><td align="right"><b>'.create_lnk('Anfragen',$pet_link).' '.$petitions['star'].':</b> '.$petitions[0].' Ungelesen, '.$petitions[1].' Gelesen, '.$petitions[2].' Geschlossen.</td></tr>';
		//$str_append .= $template['su_petitionlist'];
	}

    if($Char->acctid > 0)
    {
        CQuest::checkplace($REQUEST_URI);
    }

    if($Char->acctid > 0)
    {
        $abo_link = 'bathorys_popups.php?mod=abo';
        $linkabo = '<a href="'.$abo_link.'" target="_blank" onClick="'.popup($abo_link).';return false;"><b>Abonnements</b></a>';
        if(!isset($template['su_petitionlist']))$template['su_petitionlist'] = '';
        $newc = CBookmarks::newcount();
        $template['su_petitionlist'] .= appoencode('<tr><td align="left"><b>'.$linkabo.' :</b> '.( ($newc>0) ? '`@' : ''  ).$newc.' Orte mit neuen Beiträgen.</td></tr>');

        $cal_link = 'bathorys_popups.php?mod=calendar';
        $cal_link_new = 'bathorys_popups.php?mod=calendar&mdo=verwa';
        $newc = CCalendar::newcount();
        $linkcal = '<a href="'.( ($newc > 0) ? $cal_link_new : $cal_link ).'" target="_blank" onClick="'.popup(( ($newc > 0) ? $cal_link_new : $cal_link )).';return false;"><b>Kalender</b></a> ';
        $template['su_petitionlist'] .= appoencode('<tr><td align="left"><b>'.$linkcal.':</b> '.( ($newc>0) ? '`@' : ''  ).$newc.' neue Termine.</td></tr>');
        $str_append .= $template['su_petitionlist'];
    }

	//Superuser Switch ID
	if($Char != null && $Char->superuser_id_switch != 0)
	{
		$grotte_link = 'superuser.php?op=superuser_id_switch';
		$str_lnk_full = addnav('',$grotte_link);

		if (!isset($quickkeys['>']))
		{
			$quickkeys['>']="window.location='$str_lnk_full'";
		}
	}

	if($access_control->su_check(access_control::SU_RIGHT_GROTTO)) {

		$grotte_link = 'superuser.php?op=intro_grotte';
		$str_lnk_full = addnav('',$grotte_link);
		$str_append .= '<tr><td align="right"><b>'.create_lnk('Admingrotte',$grotte_link).'</b></td></tr>';
		if (!isset($quickkeys['<']))
		{
			$quickkeys['<']="window.location='$str_lnk_full'";
		}

	}

	if($access_control->su_check(access_control::SU_RIGHT_STEALTH)) {

		$template['su_stealth'] = '<tr><td align="right">'.jslib_stealth_switch().'</td></tr>';
		$str_append .= $template['su_stealth'];

	}


	$str_append .= "</table>";
	if(!isset($template['petitions']))$template['petitions']='';
	$template['petitions'] .= $str_append; //ist das eine verwaiste Variable? Spiel-weit keine weitere Fundstelle

	if($bool_vitalout) {

		$template['headscript'] .=
					''.JS::encapsulate('
						document.getElementById("border_right").style.display = "none";
						document.getElementById("border_left").style.display = "none";
						document.getElementsByTagName("body")[0].style.width = "98%";
					');
	}

	$template['stats'] = charstats();

	// Menu-Navs
	$int_size = sizeof($g_arr_addnav_menu);
	if($int_size) {
		$js_out = 'var a_m = Array();
						var a_m_fo = -1;
						var a_m_vis = -1;

						function a_men (id) {
							if(a_m_fo > -1) {
								id = a_m_fo;
								a_m_fo = -1;
							}

							if(!isSet(LOTGD.popMenu) || !isSet(LOTGD.Hint)) {
								a_m_fo = id;
								LOTGD.loadLibrary("popmenu");
								new libLoadWaiter("popmenu",a_men,true);
								return;
							}

							if(a_m_vis == -1) {
								LOTGD.addEvent(document, "click", function (){if(a_m_vis==true) {a_men(-1);}});
								a_m_vis = false;
							}

							for(i=0;i < a_m.length;i++) {
								if(isSet(a_m[i])) {
									a_m[i].setVisibility(false);
								}
							}

							a_m_vis = false;

							if(id > -1) {

								if(!isSet(a_m[id])) {
									a_m[id] = new LOTGD.popMenu();
									a_m[id].m_container = document.getElementById("main_content");
									switch(id) {';

		for($i=0;$i<$int_size;$i++) {
			$js_out .= 'case '.$i.': a_m[id].addItem('.$g_arr_addnav_menu[$i].');break;';
		}
		$js_out .= '
									}
									a_m[id].showAt();
								}

								a_m[id].show();
								window.setTimeout("a_m_vis=true;",100);
							}
						}';

        $template['headscript'] .= JS::encapsulate($js_out);
	}

    $template['source'] = '';
	$template['copyright'] = COPYRIGHT;
	$template['version'] = 'Version: '.GAME_VERSION;

	$gentime = microtime(true)-$pagestarttime;
	$str_pagegen = 'Seitengenerierung: '.round($gentime,2).'s';

    //if($gentime > 0.5){
        //systemlog('slow gen '.$REQUEST_URI.' time: '.$gentime,$Char->acctid);
    //}

	$template['pagegen'] =& $str_pagegen;

    //$template['JS_LIB']  bodyend
    if(0 != $Char->acctid)$template['JS_LIB'] = jslib_init();//.$template['bodyend'];

    $template['bodyend'] = '
<div class="legallinks">
    <a href="./static/nutzungsbestimmungen.html" target="_blank" onClick="'.popup('./static/nutzungsbestimmungen.html',array('width' => '1000')).';return false;">Nutzungsbestimmungen</a> -
    <a href="./static/spielregeln.html" target="_blank" onClick="'.popup('./static/spielregeln.html',array('width' => '1000')).';return false;">Spielregeln</a> -
    <a href="./static/netiquette.html" target="_blank" onClick="'.popup('./static/netiquette.html',array('width' => '1000')).';return false;">Netiquette</a> -
    <a href="./static/datenschutzrichtlinien.html" target="_blank" onClick="'.popup('./static/datenschutzrichtlinien.html',array('width' => '1000')).';return false;">Datenschutzrichtlinien</a> -
    <a href="./static/haftungsausschluss.html" target="_blank" onClick="'.popup('./static/haftungsausschluss.html',array('width' => '1000')).';return false;">Haftungsausschluss</a> -
    <a href="./static/impressum.html" target="_blank">Impressum</a>
    </div><div id="ajax_temp"></div>
                                    <div class="hidden">
                                        <div id="dialog-message" title="Info"></div>
                                      </div>'.$template['bodyend'];

	$template['output'] =& $output;

	$output .= $str_append;

	$template = utf8_preg_replace('/(<span class="c)(\d+)(">)(\s*)(<\/span>)/','$4',$template);

    ob_start();

	include(TEMPLATE_PATH.$session['user']['prefs']['template'].'/tpl.php');
	$session['user']['output'] = ob_get_flush();

	saveuser(true);

	session_write_close();

	exit();
}

/**
 * Erzeugt Popup-Header
 *
 * @param string $title Titel für Seite
 * @param bool $bool_jslib Soll Jslib eingebunden werden? (optional, standard false)
 */
function popup_header($title=GAME_VERSION,$bool_jslib=false,$bool_ace=false)
{
	global $BOOL_POPUP,$template;

	$str_metadata = '
	<meta name="keywords" content="'.getsetting('server_meta_keywords','Atrahor,lotgd').'" >
	<meta name="description" content="'.getsetting('server_meta_description','Atrahor.de').'" >
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta NAME="language" CONTENT="de">
	<meta NAME="robots" CONTENT="index,follow">
	';

	$template['headscript'] .= $str_metadata;
	$template['title'] = $title;
	$BOOL_POPUP = true;
    JS::SetHeader($bool_jslib,false,false,$bool_ace,false);
}

/**
 * Erzeugt Popup-Seitenende
 *
 * @param bool $bool_saveuser Userdaten speichern (optional, standard true)
 * @uses saveuser
 */
function popup_footer($bool_saveuser = true)
{
	global $output, $nestedtags, $session, $template, $Char;

	//Offene Tags am Ende des Standardoutput schließen
	foreach($nestedtags as $key=>$val)
	{
		$output.='</'.$key.'>';
		unset($nestedtags[$key]);
	}

	//Referenz auf Ausgabedaten
    $template['output'] =& $output;

    $template['output'] .='<div id="ajax_temp"></div>
                                    <div class="hidden">
                                        <div id="dialog-message" title="Info"></div>
                                      </div>';

	///
	/// Werbung an/abschalten
	///
	if(getsetting('ad_enabled',1) == 1)
	{
		$template['ad'] = getsetting('ad_html','');
	}
	else
	{
		$template['ad'] = '';
	}

	//Benutze das Usertemplate und falls keine entsprechende Popup_tpl Datei vorhanden ist inkludiere das Standardtemplate
	if (file_exists(TEMPLATE_PATH.$session['user']['prefs']['template_pop'].'/tpl_popup.php'))
	{
		$str_template_to_include = TEMPLATE_PATH.$session['user']['prefs']['template_pop'].'/tpl_popup.php';
	}
	else
	{
		$str_template_to_include = TEMPLATE_PATH.'/tpl_popup_default.php';
	}

	if($bool_saveuser) {
		saveuser(true);
	}

	//if(!LOCAL_TESTSERVER && getsetting('output_compression',0)==1 || $session['user']['prefs']['output_compression'] !== false)

    //habs mal über prefs drin gelassen, aber da slayer mod_pagespeed benutzt ist  ob_gzhandler doppeltgemoppelt und führt nur zu lahmeren seitenaufbau...? //bathi
   //if($session['user']['prefs']['slowinet'] == 1)
   // {
	//	//start the gzip compression
	//	ob_start('ob_gzhandler');
	//}
	//else
	//{
		ob_start();
	//}

	include($str_template_to_include);

	$output = ob_get_flush();

	//Aprilscherz hier isser

	//Aprilscherz da wahrer

	exit();
}

/**
 * Löscht den kompletten bis hier vorgenommenen output
 *
 * @uses clearnav
 */
function clearoutput()
{
	global $output,$nestedtags,$header;
	clearnav();
	$output=null;
	unset($nestedtags);
	$header=null;
}


/**
 * Löscht ausgewählte Daten die der Kern ausgibt
 *
 * @param bool $bool_output Output löschen
 * @param bool $bool_header Header löschen
 * @param bool $bool_nav Navs löschen
 * @param bool $bool_nestedtags Nestedtags löschen
 */
function clear_data($bool_output = true, $bool_header = true, $bool_nav = true, $bool_nestedtags = true)
{
	global $output,$nestedtags,$header;

	if($bool_output) $output = null;
	if($bool_header) $header = null;
	if($bool_nestedtags) unset($nestedtags);
	if($bool_nav) clearnav();
}

/**
 * Badwords filtern
 *
 * @param string $input
 * @return string gefilterter String
 */
function soap($input)
{
	if (getsetting('soap',1))
	{
        $sql = 'SELECT * FROM nastywords';
        $result = db_query($sql);
        $arr_nastywords = db_fetch_assoc($result);
		$search = $arr_nastywords['words'];
		$search = str_replace('a','[a4@]',$search);
		$search = str_replace('l','[l1!]',$search);
		$search = str_replace('i','[li1!]',$search);
		$search = str_replace('e','[e3]',$search);
		$search = str_replace('t','[t7+]',$search);
		$search = str_replace('o','[o0]',$search);
		$search = str_replace('s','[sz$]',$search);
		$search = str_replace('k','c',$search);
		$search = str_replace('c','[c(k]',$search);
		$start = "#(\s|\A)";
		$end = "(\s|\Z)#iU";
		$search = str_replace('*','([[:alnum:]]*)',$search);
		$search = str_replace(' ',$end.' '.$start, $search);
		$search = $start.$search.$end;
		$search = explode(' ',$search);
		return utf8_preg_replace($search,'\1`i$@#%`i\2',$input);
	}
	else
	{
		return $input;
	}
}

function bytesToSize($bytes, $precision = 2)
{
	$kilobyte = 1024;
	$megabyte = $kilobyte * 1024;
	$gigabyte = $megabyte * 1024;
	$terabyte = $gigabyte * 1024;

	if (($bytes >= 0) && ($bytes < $kilobyte)) {
		return $bytes . ' B';

	} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
		return round($bytes / $kilobyte, $precision) . ' KB';

	} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
		return round($bytes / $megabyte, $precision) . ' MB';

	} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
		return round($bytes / $gigabyte, $precision) . ' GB';

	} elseif ($bytes >= $terabyte) {
		return round($bytes / $terabyte, $precision) . ' TB';
	} else {
		return $bytes . ' B';
	}
}

/**
 * Fügt dem Template weitere CSS Definitionen hinzu die im Headbereich eingefügt werden
 * Der übergebene Parameter sollte entweder die Form
 * <link href="file.css" rel="stylesheet" type="text/css">
 * besitzen oder aber  eine vollständige CSS definition aufweisen
 * <style type="text/css">
 *	@import url(templates/colors.css);
 * </style>
 *
 * @param string $str_css CSS Definition
 * @version DS V3
 */
function tpl_add_css($str_css)
{
	global $template;
	if(!empty ($str_css ))
	{
		$template['css'] .= $str_css;
	}
}

/**
 * Erzeugt String aus Array (eindimensional). Veraltet, utf8_serialize() wird empfohlen!
 * Die Methode ist nur noch aus Kompatibilitätsgründen vorhanden, verwendet intern jedoch utf8_serialize()
 *
 * @param array $array Eingabe-Array
 * @return string Ausgabe
 */
function createstring($array)
{
	if (is_array($array))
	{
		return utf8_serialize($array);
	}
	return $array;
}

/**
 * Erzeugt Array (eindimensional) aus String. Veraltet, utf8_unserialize() wird empfohlen!
 * Die Methode ist nur noch aus Kompatibilitätsgründen vorhanden, verwendet intern jedoch utf8_unserialize()
 *
 * @param string Eingabe-String
 * @return array $array Ausgabe
 */
function createarray($string)
{
	return utf8_unserialize($string);
}

/**
 * Gibt Inhalt eines Array aus (rekursiv)
 * Verwendet intern jetzt die PHP Funktion print_r
 *
 * @param array $array auszugebender Array
 * @param string $prefix Vor Array-Element anzuhängende Formatierung o.ä.
 * @return string Ausgabe
 */
function output_array($array,$prefix='')
{
	return print_r($array,true);
}

/**
 * = output_array?
 *
 * @param array $item
 * @return string
 */
function dump_item($item)
{
	$output = '';
	$temp = $item;
//	if (is_array($item)) $temp = $item;
//	else $temp = adv_unserialize($item);
	if (is_array($temp))
	{
		$output .= "array(" . count($temp) . ") {<blockquote>";
		foreach ($temp as $key => $val)
		{
			$output .= "'$key' = '" . dump_item($val) . "'`n";
		}
		$output .= "</blockquote>}";
	}
	else
	{
		$output .= $item;
	}
	return $output;
}

/**
 * Gibt Ordinal der übergebenen Zahl zurück
 *
 * @param int $val Eingabewert
 * @return string Ordinal
 */
function ordinal($val){
	$exceptions = array(1=>'ten',2=>'ten',3=>'ten',11=>'ten',12=>'ten',13=>'ten');
	$x = ($val % 100);
	if (isset($exceptions[$x]))
	{
		return $val.$exceptions[$x];
	}
	else
	{
		$x = ($val % 10);
		if (isset($exceptions[$x]))
		{
			return $val.$exceptions[$x];
		}
		else
		{
			return $val.'ten';
		}
	}
}

/**
 * Wandelt in einem Text alle [male|female] oder [male|female|neuter] Ausdrücke geschlechtsspezifisch um
 *
 * @param string $str_text umzuwandelnder Text
 * @param mixed $sex Geschlechtsangabe (0=männlich, 1=weiblich, 2=sächlich, false=Userdaten)
 * @return string geschlechtsspezifischer Text
 */
function words_by_sex($str_text,$sex=false)
{
	global $Char;

	if($sex===false)
	{
		$sex=$Char->sex;
	}


	$str_text = utf8_preg_replace_callback(
		'/(.?)\[(.*?)\|(.*?)(?:\|(.*?))?(?!`)\]/sim',
		create_function('$arr_matches','return ($arr_matches[1]=="`" ? $arr_matches[0] : $arr_matches[1].$arr_matches['.($sex+2).']);'), $str_text);
	return $str_text;
}

/**
 * Ersetzt in einem Zufallsspott oder übergebenem Text die Standard-Platzhalter
 * @param mixed $taunt umzuwandelnder Text, oder false wenn Gegnerspruch ignoriert werden soll(Optional)
 * @return string fertiger Spott
 */
function get_taunt($taunt='')
{
	global $session,$badguy;

	if($taunt=='')
	{
		if($badguy['creaturewin']!='' && $taunt!==false)
		{
			$taunt = $badguy['creaturewin'];
		}
		else
		{
			$sql = 'SELECT taunt FROM taunts ORDER BY rand('.e_rand().') LIMIT 1';
			$result = db_query($sql);
			$taunt = db_fetch_assoc($result);
			$taunt = $taunt['taunt'];
		}
	}

	$taunt = str_replace('%s',($session['user']['sex']?'sie':'ihn'),$taunt);
	$taunt = str_replace('%o',($session['user']['sex']?'sie':'er'),$taunt);
	$taunt = str_replace('%p',($session['user']['sex']?'ihr':'sein'),$taunt);
	$taunt = str_replace('%x',($session['user']['weapon']),$taunt);
	$taunt = str_replace('%X',$badguy['creatureweapon'],$taunt);
	$taunt = str_replace('%W',$badguy['creaturename'],$taunt);
	$taunt = str_replace('%w',$session['user']['name'],$taunt);
	$taunt = words_by_sex($taunt);

	$taunt='`5'.$taunt.'`0';

	return $taunt;
}

/**
 * Fortschrittsbalken für Erfahrung
 * exp bar mod coded by: dvd871 with modifications by: anpera
 *
 * @return string HTML
 */
function expbar($baronly=false) {
	global $session,$exparray;

	$last_exp = get_exp_required($session['user']['level']-1,$session['user']['dragonkills']);
	$exp_req = get_exp_required($session['user']['level'],$session['user']['dragonkills']);
	$left = $session['user']['experience'] - $last_exp;
	$full = $exp_req - $last_exp;

	$req=$exparray[$session['user']['level']]-$exparray[$session['user']['level']-1];
	$u='<font face="verdana" size=1>'.$session['user']['experience'].' / '.($exp_req).'<br></font>'.grafbar($full,$left);

    if($baronly)
    {
        return grafbar($full,$left);
    }

    return($u);
}

/**
 * Gibt HTML für Fortschrittsbalken zurück
 *
 * @param float $full Max.wert
 * @param float $left Aktueller Wert
 * @param int $width Gesamtbreite
 * @param int $height Gesamthöhe
 * @param string $str_style Gibt eine ID für den Grafbar an, die per CSS weiter gestyled werden kann
 * @param bool $newversion Verwendung der neuen Grafbar mit Grafiken
 * @return string HTML
 */
function grafbar($full,$left,$width=70,$height=5,$str_id = '',$newversion = true,$owncol='')
{
    $owncol = str_replace('#','',$owncol);
    if($owncol == 'transparent')$owncol='FFFFFF';

	$col2='#000000';
	if ($left<=0)
	{
		$left=0;
		$col='#000000';
		$grafbarfile = 'grafbar_red.png';
		$transcolor = '#820000';
	}
	else if ($left<$full/4)
	{
		$col='#FF0000';
		$grafbarfile = 'grafbar_red.png';
		$transcolor = '#820000';
	}
	else if ($left<$full/2)
	{
		$col='#FFFF00';
		$grafbarfile = 'grafbar_yellow.png';
		$transcolor = '#825c00';
	}
	else if ($left>=$full)
	{
		$left=$full;
		$col='#007F00';
		$col2='#007F00';
		$grafbarfile = 'grafbar_darkgreen.png';
		$transcolor = '#004100';
	}
	else
	{
		$col='#00FF00';
		$grafbarfile = 'grafbar_green.png';
		$transcolor = '#2f9100';
	}
	if ($full==0) $full=1;
	if($str_id=='') $str_id='grafbar'.$full.$left; //damit irgendwas für korrektes HTML dasteht
	if ($newversion)
	{
		$u = '`0<table cellspacing="0" id="'.$str_id.'" style="border: solid 1px #000000; height: '.$height.'px;" width="'.$width.'"><tr>'.($left==0?'':'<td width="' . round($left / $full * 100) . '%" style="background:url(./images/grafbar/'.$grafbarfile.') repeat-x '.$transcolor.'" height="3"></td>').($left==$full?'':'<td height="3" width="'. round(100-($left / $full * 100)) .'%" style="background:url(./images/grafbar/grafbar_bg.png) repeat-x #393939"></td>').'</tr></table>';
	}
	else if($owncol!='')
	{
		$u = '`0<table cellspacing="0" id="'.$str_id.'" style="border: solid 1px #000000; height: '.$height.'px;" width="'.$width.'"><tr><td width="' . round($left / $full * 100) . '%" style="background-color:#'.$owncol.'" height="3"></td><td height="3" width="'. round(100-($left / $full * 100)) .'%" style="background-color:#111;"></td></tr></table>';
	}
	else
	{
		$u = '`0<table cellspacing="0" id="'.$str_id.'" style="border: solid 1px #000000; height: '.$height.'px;" width="'.$width.'"><tr><td width="' . round($left / $full * 100) . '%" style="background-color:'.$col.'" height="3"></td><td height="3" width="'. round(100-($left / $full * 100)) .'%" style="background-color:'.$col2.'"></td></tr></table>';
	}
	return($u);
}

/**
 * Gibt Überschrift zurück, gibt implizit eine Statusmessage aus Atrahor::Session['message'] aus, falls vorhanden
 *
 * @param string $str_title Überschrift
 * @return string Formatierte Überschrift
 */
function get_title ($str_title)
{
	return('`c`b'.add_0_to_string($str_title).'`b`c`n'.getStatusMessage());
}

/**
 * Platziere etwas HTML rund um die übergebenen Paraeter
 *
 * @param string $str_content Inhalt der umrahmt werden soll
 * @param string $str_header Kopfzeile (Falls vorhanden)
 * @param int $int_design Wähle ein Design aus
 * @return string Frame+Content
 */
function print_frame($str_content, $str_header = '', $int_design = 0, $bool_return = false)
{
	switch ($int_design)
	{
		//Dunkler Rahmen
		default:
			{
				$str_output = '
				<table class="frame" cellspacing="0" cellpadding="0" border="0">
					<tbody><tr>
						<td>
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tbody><tr class="frame_label">
									<td width="46" class="frame_label_l"></td>
									<td height="23" class="frame_label">'.$str_header.'</td>
									<td width="46" class="frame_label_r"></td></tr>
								</tbody>
							</table>
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tbody>
								<tr>
									<td class="frame_border_l"></td>
									<td valign="top" style="text-align: left;" class="frame_main">

										'.$str_content.'

									</td>
									<td class="frame_border_r"></td>
								</tr>
								</tbody>
							</table>
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tbody>
								<tr class="frame_label_b">
									<td width="46" class="frame_label_lb"></td>
									<td height="24" class="frame_label"><img src="./images/frame/zier_b2.png"></td>
									<td width="46" class="frame_label_rb"></td>
								</tr>
								</tbody>
							</table>
							</td>
						</tr>
						</tbody>
					</table>';
				break;
			}
		//Scroll
		case 1:
			{
				$str_output = '
							<div style="background: URL(./images/papier.gif) no-repeat;font-family:Helvetica;font-size:10pt;padding:35px;color:black;width:360px;height:360px;" id="book">
								<div style="width:335px;height:340px;overflow:auto;text-align:left;" id="book_content">
								 '.$str_content.'
								</div>
							</div>';

				break;
			}
	}

	if($bool_return == true)
	{
		return $str_output;
	}
	else
	{
		output($str_output);
	}
}

function generate_botd($str_category = 'std', $str_bannertext)
{
	global $session;
}

////////////// VERLAUF by bathi

function do_verlauf ($str) {
	return utf8_preg_replace_callback("/³[^<>³]+³/imsS","do_verlauf_parse",$str);
}

function do_verlauf_parse($treffer)
{
    $str = $treffer[0];
    $str = utf8_html_entity_decode($str);
    $str = str_replace(array('²','³'),'',$str);
    $str = utf8_preg_replace('/`[^'.regex_appoencode(1,false).']{1}/','',$str);
    utf8_preg_match_all("/(`([".regex_appoencode(1,false)."]{1})|#([a-fA-F0-9]{3,6});)([^`#]*)/imsS", $str, $matches);

    if(isset($matches[4]))
    {
        $appoencode = get_appoencode();
        $str = '';
        $i = 0;
		$end = count($matches)-1;
        foreach($matches[4] as $match){
            if($match == '' && $i == $end) continue;
            if(isset($matches[2][$i]) && $matches[2][$i] != ''){
                $color1 = $appoencode[$matches[2][$i]]['color'];
            }
            else{
                $color1 = $matches[3][$i];
            }

            if(isset($matches[2][$i+1]) && $matches[2][$i+1] != ''){
                $color2 = $appoencode[$matches[2][$i+1]]['color'];
            }
            else if(isset($matches[3][$i+1]) && $matches[3][$i+1] != ''){
                $color2 = $matches[3][$i+1];
            }
            else if(isset($matches[2][$i]) && $matches[2][$i] != ''){
                $color2 = $matches[2][$i];
            }
            else{
                $color2 = $matches[3][$i];
            }
            $str .= calc_verlauf($match,$color1,$color2,false,( ($i == 0) ? 'first' : ( ($i == (count($matches[4])-2)) ? 'last' : '')));
            $i++;
        }
    }
    return $str;
}

function calc_verlauf ($text,$color1,$color2, $offset=false, $type = '')
{
	if('' == $text) return '';
    $len1 = strlen($color1);
    $len2 = strlen($color2);

    $steps=mb_strlen($text);

    if($len1==3){
        $r1=hexdec(str_repeat(substr($color1,0,1),2));
        $g1=hexdec(str_repeat(substr($color1,1,1),2));
        $b1=hexdec(str_repeat(substr($color1,2,1),2));
    }else{
        $r1=hexdec(substr($color1,0,2));
        $g1=hexdec(substr($color1,2,2));
        $b1=hexdec(substr($color1,4,2));
    }

    if($len2==3){
        $r2=hexdec(str_repeat(substr($color2,0,1),2));
        $g2=hexdec(str_repeat(substr($color2,1,1),2));
        $b2=hexdec(str_repeat(substr($color2,2,1),2));
    }else{
        $r2=hexdec(substr($color2,0,2));
        $g2=hexdec(substr($color2,2,2));
        $b2=hexdec(substr($color2,4,2));
    }

    $diff_r=$r2-$r1;
    $diff_g=$g2-$g1;
    $diff_b=$b2-$b1;
    $str = '';
    $ct = ($type=='first' || $type=='last') ? $steps : $steps+1;
    if($ct==0)$ct=1;
    for ($i=0; $i<$steps; $i++){
        $factor=  ( ($type=='first') ? ($i) : ($i+1) ) / $ct;
        $r=round($r1 + $diff_r * $factor);
        $g=round($g1 + $diff_g * $factor);
        $b=round($b1 + $diff_b * $factor);
        $color="#".sprintf("%02X",$r).sprintf("%02X",$g).sprintf("%02X",$b);
        $str .= '<span style="color:'.$color.';">'.mb_substr($text,$i,1)."</span>";
    }
    return $str;
}

function get_color_pos_clasic($str){
    $col = array();
    $appoencode = get_appoencode();
    utf8_preg_match_all("/`[".regex_appoencode(1)."]{1}/i",$str, $matches, PREG_OFFSET_CAPTURE);
    foreach($matches[0] as $k => $v){
        $col[$v[1]] = array('pos' => $v[1], 'len' => mb_strlen($v[0]), 'color' => '#'.$appoencode[mb_substr($v[0],1)]['color'] );
    }
    return $col;
}

function get_hex_color_classiv($str){
    if(mb_strlen($str) > 1) return $str;
    $appoencode = get_appoencode();
    return $appoencode[$str]['color'];
}

function get_color_pos_hex($str){
    $col = array();
    utf8_preg_match_all("/(#[a-f0-9]{6};|#[a-f0-9]{3};)/i",$str, $matches, PREG_OFFSET_CAPTURE);
    foreach($matches[0] as $k => $v){
        $col[$v[1]] = array('pos' => $v[1], 'len' => mb_strlen($v[0]), 'color' => mb_substr($v[0],0,-1) );
    }
    return $col;
}

////////////// VERLAUF ENDE

/**
* Färbt einen String wie den Usernamen
* @param string String, der gefärbt werden soll
* @param string Username (optional, Standard '' = aktueller User)
* @param int Zeichengruppierung (optional, Standard 0 = gleichmäßige Verteilung)
* return string Gefärbter String
*/
function color_from_name ($str_input, $username='', $chargroup=0)
{
    if($username=='')
    {
        global $session;
        $username=$session['user']['name'];
    }
    $str_back = '';
    $str_front = '';
    $username=str_replace('`0','',$username);
    $chargroup=intval($chargroup);

        $arr_col_get = get_color_pos_clasic($username) + get_color_pos_hex($username);
        ksort($arr_col_get);
        $arr_colorcodes = array();
        foreach($arr_col_get as $h){
            $arr_colorcodes[] = '²'.$h['color'];
        }

        if(mb_strpos($username,'*`') || mb_strpos($username,'¬`')) //bei Superusern mit Signum die ersten beiden Farbcodes löschen
        {
            array_shift($arr_colorcodes);
            array_shift($arr_colorcodes);
        }

        $int_allcolors=count($arr_colorcodes);
        $int_frontcolors=ceil($int_allcolors/2);

        if($int_allcolors>0) //Name ist gefärbt
        {
            if($chargroup<=0) //falls nicht angegeben, Aufteilung der Farben
            {
                $chargroup=max(1,floor(mb_strlen($str_input)/$int_allcolors));
            }

            for($i=0; $i<$int_frontcolors; $i++) //vorderen Teil färben
            {
                $str_front.=$arr_colorcodes[$i].';'.mb_substr($str_input,0,$chargroup);
                $str_input=mb_substr($str_input,$chargroup);
            }
            for($i=$int_allcolors-1; $i>=$int_frontcolors; $i--) //hinteren Teil färben
            {
                $str_back=mb_substr($str_input,-$chargroup).$arr_colorcodes[$i].';'.$str_back;
                $str_input=mb_substr($str_input,0,-$chargroup);
            }

            $str_colored=$str_front.$str_input.$str_back;

            //if((mb_strpos($username,'³') === false)) return $str_colored.'`0';
            //else
                return '³'.$str_colored.'³`0';
        }
        else //kein farbiger Name
        {
            return($str_input);
        }
}

?>