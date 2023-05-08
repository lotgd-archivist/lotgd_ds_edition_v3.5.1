<?php
/**
 * nav.lib.php: Navigationsfunktionen
 * @author LOGD-Core / Drachenserver-Team
 * @version DS-E V/2
*/

// Einstellungsarrays für Navs
/**
 * Erlaube den Aufruf dieser Seiten auch im ausgeloggten Zustand (also ohne $session Variable)
 */
$allowanonymous = array(
    'about.php'			=> true,
    'about_server.php'			=> true,
	'create.php'		=> true,
	'demouser.php'		=> true,
	'index.php'			=> true,
	'logdnet.php'		=> true,
	'login.php'			=> true,
	'list.php'			=> true,
	'petition.php'		=> true,
	'motd.php'			=> true,
	'motdrss.php'		=> true,
	'motd-coding.php'	=> true,
	'namegenerator.php'	=> true,
	'news.php'			=> true,
	'httpreq_autocomplete_name.php' => true,
	'motcrss.php'		=> true,
);

/**
 * Erlaube den Aufruf dieser Seiten, auch wenn sie nicht im $allownav Array stehen (z.B. für Popups)
 */
$allownonnav = array(
	'badnav.php'		=> true,
    'bio.php'			=> true,
    'picture.php'			=> true,
    'steckbrief.php'	=> true,
	'comment2mail.php'	=> true,
	'mail.php'			=> true,
	'motd.php'			=> true,
	'motdrss.php'		=> true,
	'motd-coding.php'	=> true,
	'multi_prefs.php'	=> true,
	'namegenerator.php'	=> true,
	'petition.php'		=> true,
    'pict.php'			=> true,
    'picv.php'			=> true,
    'prefs.php'			=> true,
    'prefs_bio.php'			=> true,
    'prefs_steckbrief.php'			=> true,
	'usernotes.php'		=> true,
	'httpreq_autocomplete_name.php' => true,
	'motcrss.php'		=> true,
	'bathorys_popups.php'=> true,
    'httpreq_chat.php' => true,
);

/**
 * Folgende Seiten werden nicht in die Restorepage geschrieben. Beim Wiedereinloggen startet man somit nicht in ihnen.
 */
$nokeeprestore = array(
	'badnav.php'		=> true,
	'bio.php'			=> true,
    'picture.php'			=> true,
    'steckbrief.php'	=> true,
	'comment2mail.php'	=> true,
	'mail.php'			=> true,
	'motd.php'			=> true,
	'motd-coding.php'	=> true,
	'multi_prefs.php'	=> true,
	'namegenerator.php'	=> true,
	'newday.php'		=> true,
	'petition.php'		=> true,
	'pict.php'			=> true,
    'picv.php'			=> true,
    'prefs.php'			=> true,
    'prefs_bio.php'			=> true,
    'prefs_steckbrief.php'			=> true,
	'usernotes.php'		=> true,
	'bathorys_popups.php'=> true,
    'httpreq_chat.php' => true,
    'httpreq.php' => true,
    'httpreq_usermenu.php' => true,
    'httpreq_autocomplete_name.php' => true,
);

/**
 * Enthält zu bestimmten Dateien eine Beschreibung die ein 
 * onMouseOver Div anzeigen lassen wenn die Maus darüber weilt.
 */
$arr_nav_desc = array(
	'abandoncastle.php' => 'Ein unheimlicher, gefährlicher Ort, an dem auf den wagemutigen Abenteurer große Schätze warten können.',
	'academy.php' => 'Hier kann man seine speziellen Fähigkeiten trainieren und Zauber und Zutaten kaufen.',
	'armor.php'   => 'Der Rüstungshändler. Hier kann man Rüstungen bis zur maximalen Stärke von 15 kaufen.',
	'bank.php'    => 'Auf der Bank können Gold und Edelsteine vor Ereignissen sicher untergebracht werden.',
	'beggar.php'  => 'Alle, die wirklich garnichts haben, können sich hier wenigstens etwas Gold zum Leben holen.',
	'coffechouse.php' => 'Ein Treffpunkt für die Betuchteren, wo man viele allerlei Leckereien kaufen kann.',
	'dg_main.php' => 'Sobald man ein paar Heldentaten vorweisen kann, kann man sich hier nach einer passenden Gemeinschaft umsehen.',
	'dorfamt.php' => 'Hier werden die Steuern gezahlt, haben Fürst, Richter und Wachen ihr Büro und findet man die OoC-Bereiche.',
	'dorffest.php' => 'Die Festwiese, auf der ab und an das große Stadtfest stattfindet.',
	'dorftor.php' => 'Verlässt man das die Stadt durch dieses Tor findet man die Felder, den Richtplatz und die Portalebene.',
	'forest.php'  => 'Heimat von vielen Monstern und damit der Ort, an dem man kämpft um Erfahrung zu sammeln.',
	'friedhof.php' => 'Ein Ort an dem man der Toten gedenken und ihnen Blumen auf ihr Grab legen kann.',
	'gardens.php' => 'Idyllischer Treffpunkt nicht nur für Verliebte. Von hier gelangt man auch zum Tempel und zum Baum des Lebens.',
	'goldpartner.php' => 'Auf der Suche nach RPG-Bekanntschaften? Hier kann man sich selbst kurz vorstellen und auf Zuschriften warten.',
	'graveyard.php' => 'So wie man im Wald kämpft kann man hier Seelen quälen, um vielleicht genug Gefallen zu sammeln, an die Oberwelt zurückzukehren.',
	'gypsy.php'   => 'Die Zigeunerin handelt mit Edelsteinen und steht im Kontakt zu den Toten.',
	'halle_der_geister.php' => 'Eine große, von einem Abgrund durchzogene Halle mit einem alten Geist.',
	'healer.php'  => 'Der Heiler füllt die Lebenspunkte gegen eine kleine Gebühr wieder auf.',
	'hof.php'     => 'Heldentaten, Reichtum oder Schönheit - wer ist worin der Beste und wo steht man selbst? Hier kann man es herausfinden.',
	'houses.php'  => 'Das Viertel, in dem die Häuser der Charaktere, der Dorfbrunnen und die dunkle Gasse zu finden sind.',
	'inn.php'     => 'In der Schenke kann man sich mit Freunden treffen, die Zeit mit Spielen totschlagen und sein tägliches Freiale bekommen.',
	'library.php' => 'Die große Bibliothek Atrahors, in der man zahlreiche Informationen und Hilfen zum Spiel findet. Vor allem für neuere Spieler sehr zu empfehlen.',
	'list.php'    => 'Eine Liste aller auf diesem Server angemeldeten Spieler inkl. Onlinestatus.',
	'lodge.php'   => 'An diesem Ort erfährst du alles über Donationpoints und kannst sie gegen Preise eintauschen.',
	'login.php?op=logout' => 'In den Feldern Ausloggen. Hier ist man leicht angreifbar.',
	'market.php'	=> 'Hier findet man fast alle Händler Atrahors, die Bank, die Ställe und vieles mehr.',
	'news.php'    => 'Die letzten Leistungen aller Spieler. Hier findet man auch Informationen über Atrahor.',
	'ooc.php?op=brett' => 'Wer jemanden zum Spielen sucht, kann hier sein Zettelchen hinterlassen.',
	'ooc.php?op=disku' => 'Fragen, Vorschläge, Fehler und ähnliches finden hier ihren Platz und werden von der Administration gelesen.',
	'ooc.php?op=ooc' => 'Der Raum, in dem der Wahnsinn zu Hause ist. OoC Bereich ohne Haftung für geistige Schäden.',
	'outhouse.php'=> 'Für das tägliche Geschäft. Hände waschen nicht vergessen!',
	'pool.php'    => 'Der Waldsee, in dem man angeln kann, sobald man etwas erfahrener ist. Im Hintergrund erhebt sich das Nebelgebirge.',
	'prison.php'  => 'Hier landen Mörder, Diebe, Steuersünder und alle anderen, die gegen das Gesetz von Atrahor verstoßen haben.',
	'pvparena.php' => 'Der Ort um sich auf legalem Wege mit anderen Spielern zu messen.',
	'racesspecial.php' => 'Ein Raum, der nur Angehörigen deiner Rasse zugänglich ist.',
	'rock.php'    => 'Hier gibt es diverse Informationen für den etwas erfahreneren Spieler.',
	'stables.php' => 'In den Ställen kann man Tiere kaufen, die im Wald beim Kämpfen hilfreich sind und diese einmal am Tag füttern.',
	'superuser.php' => 'Ganz, ganz schlecht. Wirklich, du willst hier nicht draufklicken, ganz bestimmt nicht. TUS NICHT!!!',
	'tempel.php'  => 'Hier sind die Priester zu finden, die für Eheschließungen und Scheidungen zuständig sind.',
	'train.php'   => 'Der Ort, an dem man ein Level aufsteigen kann, indem man seinen Meister besiegt.',
	'trivia.php'  => 'Einmal nach jeder Heldentat kann der Held sich hier 8 Fragen über Atrahor stellen und Punkte kassieren.',
	'vendor.php'  => 'Der Wanderhändler, der die meisten gefundenen Beutestücke kauft und selbst vor allem Möbel im Angebot hat.',
	'village.php'	=> 'Der zentrale Ausgangsort des Spiels.',
	'weapons.php' => 'Der Waffenhändler. Hier kann man Waffen bis zur maximalen Stärke von 15 kaufen.',
);

$accesskeys = array();
$quickkeys = array();

/**
 * Fügt regulären Ausdruck zu Liste erlaubter Navs hinzu, anhand dessen die aufzurufende Seite geprüft wird
 *  - Vorsicht ist geboten, damit der RegExp nicht zu gierig wird und User Zugriff auf falsche Navs erhält!
 *
 * @param string $url regulärer Ausdruck (mit Delimitern; Bsp: '/xy.php?op=del&id=\d{1,}/'
 */
function addpregnav( $url )
{
	global $session;
	$pos = mb_strpos($url, "^");
	if( $pos===false || $pos >1 ){
		$url = mb_substr($url,0,1).'^'.mb_substr($url,1);
	}
	if( !isset($session['allowednavs']['preg'] ) ){
		$session['allowednavs']['preg'] = array();
	}
	$session['allowednavs']['preg'][] = $url;
} 


$g_arr_addnav_menu = array();

/**
 * Fügt Navibutton mit Popmenu hinzu
 *
 * @param string $str_text Beschriftung
 * @param array $arr_menu_conf Array mit popmenu (JSLIB) - Menüobjektdefinitionen
 * @param bool $bool_hotkey Hotkey ja / nein
 */
function addnav_menu ($str_text,$arr_menu_conf,$bool_hotkey=true) {

	global $g_arr_addnav_menu;

	$int_counter = sizeof($g_arr_addnav_menu);

	// Menu-Data hinzufügen
	$str_js = 'a_men('.$int_counter.');';

	if(sizeof($arr_menu_conf)) {
		$g_arr_addnav_menu[$int_counter] = 'new LOTGD.MenuItem('.implode('),new LOTGD.MenuItem(',$arr_menu_conf).')';
	}

	addnav($str_text,' ',true,$str_js,false,$bool_hotkey);


}

/**
 * Fügt Navipunkt zu Liste erlaubter Navs + evtl. Navigation dazu
 * Bugfixes by salator & talion @ atrahor
 *
 * @param string Text für Menüpunkt; Leer, um keinen Menüpunkt zu erzeugen
 * @param string Link, false um Überschrift einzubauen
 * @param bool HTML erlauben?
 * @param mixed Art der Aktion: false -> normal, true -> Popup, String -> JS-Befehl
 * @param bool In neuem Fenster öffnen?
 * @param bool Hotkey aktivieren?
 * @param string $str_sure Fragt vor dem Besuch des Links mit dem angegebenen Text
 * @param bool $bool_help_txt Soll beim onmouseover ein Hilfetext angezeigt werden?
 * @return Link mit c-Info
 */
function addnav($text,$link=false,$priv=false,$pop=false,$newwin=false,$hotkey=true,$str_sure = '',$nav_desc='')
{
	global $nav, $session, $accesskeys, $REQUEST_URI, $quickkeys, $arr_nav_desc, $nestedtags,$lastcolor;;

	$bool_span = false;
	if (isset($nestedtags['color']))
	{
		unset($nestedtags['color']);
		$bool_span = true;
        $lastcolor = null;
	}
	if ($link===false)
	{
		$nav.=templatereplace('navhead',array('title'=>appoencode($text,$priv)));
	}
	elseif (empty($link))
	{
		$nav.=templatereplace('navhelp',array('text'=>appoencode($text,$priv)));
	}
	else
	{		
		if (!empty($text))
		{
			$extra='';
			if ($newwin===false)
			{
				if (mb_strpos($link,'?'))
				{
					$extra='&c='.$session['counter'];
				}
				else
				{
					$extra='?c='.$session['counter'];
				}

				//$extra.='-'.date('His'); //das nützt über dem if garnix. nach unten verschoben by sheed-ma. da es aber offenbar nicht gebraucht wird bleibt es auskommentiert.
			}

			//Setting: User don't want hotkeys
			if(!isset($session['user']['prefs']['nohotkeys'])) $session['user']['prefs']['nohotkeys'] = false;
			if ($session['user']['prefs']['nohotkeys'] || mb_strpos($text,'³') !== false)
			{
				$hotkey=false;
				if (mb_substr($text,1,1)=='?')
				{
					$text = mb_substr($text,2);
				}
			}

			//$link = str_replace(" ","%20",$link);
			//hotkey for the link.
			if($hotkey) {
				$key='';
				if (mb_substr($text,1,1)=='?')
				{
					$key = mb_substr($text,0,1);
					$text = mb_substr($text,2);

					// check to see if a key was specified up front.
					//Formerly known as $accesskeys[mb_strtolower($key)] == 1
					//Afterwards known as isset($accesskeys[mb_strtolower($key)])
					//if (!empty($accesskeys[mb_strtolower($key)]))
					if(isset($accesskeys[mb_strtolower($key)]) && $accesskeys[mb_strtolower($key)] == 1)
					{
						// output ("key ".mb_substr($text,0,1)." already taken`n");
						$key = '';
					}
					else
					{
                        if(!isset($ignoreuntil))$ignoreuntil=null;
						//output("key set to $key`n");
						$found=false;
						$int_strlen = mb_strlen($text);
						for ($i=0;$i<$int_strlen; $i++)
						{
							$char = mb_substr($text,$i,1);
							if ($ignoreuntil == $char)
							{
								$ignoreuntil='';
							}
							else
							{

								if ($char=='<') {
									$ignoreuntil='>';
								}
								if ($char=='&') {
									$ignoreuntil=';';
								}
								if ($char=='`') {
									$ignoreuntil=mb_substr($text,$i+1,1);
								}

								if (empty($ignoreuntil))
								{
									if ($char==$key)
									{
										$found=true;
										break;
									}
								}

							}
						}
						if ($found==false)
						{
							if (mb_strpos($text, '__') !== false)
							{
								$text=str_replace('__', '('.$key.') ', $text);
							}
							else
							{
								$key = mb_strtoupper($key);
								$text='('.$key.') '.$text;
							}
							$i=mb_strpos($text, $key);
							// output("Not found`n");
						}
					}
				}
				if (empty($key))
				{
					$int_strlen = mb_strlen($text);
					for ($i=0;$i<$int_strlen; $i++)
					{
                        if(!isset($ignoreuntil))$ignoreuntil=null;

						$char = mb_substr($text,$i,1);
						if ($ignoreuntil == $char)
						{
							$ignoreuntil='';
						}
						else
						{
							if ($char=='<') {
								$ignoreuntil='>';
							}
							if ($char=='&') {
								$ignoreuntil=';';
							}
							if ($char=='`') {
								$ignoreuntil=mb_substr($text,$i+1,1);
							}

							//Formerly known as $accesskeys[mb_strtolower($char)]==1
							if (isset($accesskeys[mb_strtolower($char)]) || (mb_strpos('abcdefghijklmnopqrstuvwxyz0123456789', mb_strtolower($char)) === false) || $ignoreuntil<>'')
							{

							}
							else
							{
								break;
							}
						}
					}
				}
				if ($i<mb_strlen($text))
				{
					$key=mb_substr($text,$i,1);
					$accesskeys[mb_strtolower($key)]=1;
					$keyrep=' accesskey="'.$key.'" ';
				}
				else
				{
					$key='';
					$keyrep='';
				}
				//output("Key is $key for $text`n");

				if ($key!='')
				{
					$text=mb_substr($text,0,$i).'`H'.$key.'`H'.mb_substr($text,$i+1);
					if ($pop)
					{
						$quickkeys[$key]=(is_string($pop) ? $pop :  popup($link.$extra));
					}
					else
					{
						$quickkeys[$key]="window.location='$link$extra'";
					}
				}
			}

			if(is_string($pop))
			{
                if(JS::$defer){
                    $id = 'nav_id_'.time().'_'.e_rand(0,50000);
                    $str_popup = ' id="'.$id.'"';
                    JS::event('#'.$id,'click',''.$pop.'; return false;');
                }else{
                    $str_popup = 'onClick="'.$pop.'; return false;"';
                }
			}
			else 
			{
				$str_popup = ($pop==true ? " onClick=\"".popup($link.$extra)."; return false;\"" : ($newwin==true?"target='_blank'":""));
			}

			if(!empty($str_sure))
			{
				$str_sure = ' onClick="return confirm(\''.$str_sure.'\');" ';
			}
			
			if(!isset($session['user']['prefs']['nav_help_enabled'])) $session['user']['prefs']['nav_help_enabled'] = false;
			
			if(getsetting('nav_help_enabled',1)==1 && $session['user']['prefs']['nav_help_enabled']==1 && ($nav_desc != '' || array_key_exists($link,$arr_nav_desc)))
			{
                if($nav_desc != ''){
                    $str_mouse_over_help = "title = '".$nav_desc."'";
                }else{
                    $str_mouse_over_help = "title = '".$arr_nav_desc[$link]."'";
                }
			}
			
			if(!isset($keyrep) || $keyrep == null) $keyrep = '';
			if(!isset($str_mouse_over_help ) || $str_mouse_over_help  == null) $str_mouse_over_help  = '';
			
			$nav.=templatereplace('navitem',
				array
				(
					"text"		=>appoencode($text,$priv),
					"link"		=>(($pop == true) ? '#' : utf8_htmlentities($link.$extra)),
					"accesskey"	=>$keyrep,
					"popup"		=>$str_popup.$str_sure,
					"script"	=> $str_mouse_over_help
				)
			);
		}
		if(!isset($extra) || $extra == null)$extra='';
		allownav($link.$extra);

		if ($bool_span)
		{
			$nestedtags['color'] = true;
			$bool_span = false;
		}

		return($link.$extra);
	}
	if ($bool_span)
	{
		$nestedtags['color'] = true;
	}
}

/**
 * Fügt den Link der Liste der erlaubten Navs hinzu
 *
 * @param string $str_link Der zu erlaubende Link
 */
function allownav($str_link)
{
	Atrahor::$Session['allowednavs'][$str_link] = true;
	Atrahor::$Session['allowednavs'][str_replace(' ', '%20', $str_link)] = true;
	Atrahor::$Session['allowednavs'][str_replace(' ', '+', $str_link)] = true;
}


/**
 * Setzt die Liste der erlaubten Navs zurück.
 *
 */
function clearnav()
{
	global $session,$accesskeys,$nav;
	$session['allowednavs']=array();
	$session['user']['allowednavs'] = array();
	$accesskeys = array();
	$nav='';
}

/**
 * Überprüft ob eine Variable per Formular/URL übergeben wurde. 
 * Falls ja gibt sie diesen Wert zurück. Falls nein wird in der 
 * Session nachgesehen, ob die Variable dort existiert.
 * @author Dragonslayer
 * @param array $arr_vars Liste der Variablennamen nach denen gesucht werden soll
 * @param bool $bool_clear Falls true wird jeder übergebene Variablenname aus der Session gelöscht
 * @return array Assoziativer Array mit "Name_der_variablen" => "Wert"
 */
function persistent_nav_vars($arr_vars, $bool_clear = false)
{
	global $session;
	
	$arr_result = array();
	if(!is_array($arr_vars))
	{
		return $arr_result;
	}
	
	//Alle übergebenen Wunschvariablen durchiterieren
	foreach ($arr_vars as $mixed_var)
	{
		$str_var = $mixed_var;
		$bool_delete = false;
		$bool_delete_if_not_set = false;
		if(is_array($mixed_var))
		{
			$str_var = $mixed_var['name'];
			$bool_delete = $mixed_var['delete']?true:false;
			$bool_delete_if_not_set = $mixed_var['delete_if_not_set']?true:false;
		}
		
		//Löschen falls gewünscht
		if($bool_clear || $bool_delete || ($bool_delete_if_not_set && is_null_or_empty($_REQUEST[$str_var])))
		{
			unset ($session['nav_vars'][$str_var]);
			$arr_result[$str_var] = null;
			continue;
		}
		
		//Wenn die Variable per Formular/URL übergeben wurde
		if(!is_null_or_empty($_REQUEST[$str_var]))
		{
			//Diesen Wert bevorzugt zurückgeben
			$arr_result[$str_var] = $_REQUEST[$str_var];
			//Variable für nächste Verwendung speichern
			$session['nav_vars'][$str_var] = $_REQUEST[$str_var];
		}
		//Wenn die variable in der Session vorhanden ist
		elseif (!is_null_or_empty($session['nav_vars'][$str_var]))
		{
			//Diesen Wert bevorzugt zurückgeben
			$arr_result[$str_var] = $session['nav_vars'][$str_var];
		}
		
	}
	//Array mit allen gefundenen Werten zurückgeben
	return $arr_result;
}

/**
 * Leitet Spieler auf angegebene Seite weiter, nimmt Speicherung der Userdaten vor.
 *
 * @param string Link, auf den weitergeleitet wird
 * @param string Grund für Weiterleitung (optional)
 */
function redirect($location,$reason=false,$save_user=true)
{
	global $session,$REQUEST_URI;

	if (mb_strpos($location,'badnav.php')===false)
	{
		//$session['allowednavs']=array();
		addnav('',$location);
		$session['user']['output']="<a href=\"".utf8_htmlentities($location)."\">Hier klicken</a>";
	}
	else 
	{
		if($session['allowednavs']['badnav.php']==true && $location == 'badnav.php' && count($session['allowednavs']) == 1)
		{
			$location='village.php';			
			$session['user']['output']="<a href=\"".utf8_htmlentities($location)."\">Hier klicken</a>";		
		}
		//$session['allowednavs']=array();
		addnav('',$location);
		
	}
	$session['debug'].="Redirected to $location from $REQUEST_URI.  $reason\n";
	if( $save_user ){
		saveuser();
	}
	header("Location: $location");
	exit();
}

/**
 * Sind output und nav leer, müssten wir den User normalerweise retten. 
 * Hiermit kann sich der User selbst wieder zum Dorfplatz schicken
 */
function prevent_getting_stuck()
{
	global $nav, $output, $Char, $access_control, $session;
		
	if(is_null_or_empty($nav) && is_null_or_empty($output))
	{
		addnav('Zurück');
		page_header('Gnomoporter');
		output(
			get_title('Gnomoporter!').
			'Du bist einem Fehler zum Opfer gefallen. Damit du hier nicht stecken bleibst, haben wir überall kleine Gnome aufgestellt, die dich ins Stadtzentrum teleportieren. Aber bitte nicht füttern!`n`n'
			.create_lnk('Gnomoportiere mich zum Stadtzentrum, aber pronto!','village.php',true,true,'',false,'Zum Stadtzentrum')
			.($access_control->su_lvl_check($Char->superuser)?create_lnk('`nNeu Laden',calcreturnpath(),true,true,'',false,'Neu laden'):'')
		);
		
		//Logeintrag schreiben mit den letzten 10 besuchten Seiten. Ich finde dich du Scheiss Fehler!
		$str_history = '';
		for($int_i = 0 ; $int_i<min(10,count($session['req_debug'])) ; $int_i++)
		{
			$str_history .= '`n'.$int_i.') '.$session['req_debug'][$int_i];
		}
		systemlog('$nav und $output leer in: '.calcreturnpath().$str_history, $Char->acctid);
	}
}

/**
 *@desc gibt für einen numerischen location-Wert den zugehörigen Ortsnamen aus
 *@param int Location (optional, Standard eigene location)
 *@return string Location-Name
 *@author Salator
*/
function get_location_name($location=0)
{
	global $arr_ports, $Char;
	$location=intval($location);
	if($location==0)
	{
		$location=$Char->location;
	}
	if($arr_ports[$location]['townname']>'')
	{
		return($arr_ports[$location]['townname']);
	}
	elseif($location== USER_LOC_INN)
	{
		return ('Zimmer in Kneipe');
	}
	elseif($location== USER_LOC_HOUSE)
	{
		return ('Im Haus');
	}
	elseif($location== USER_LOC_PRISON)
	{
		return ('Im Kerker');
	}
	elseif($location== USER_LOC_VACATION)
	{
		return ('In Sibirien');
	}
	else
	{
		return('Weiß der Geier...');
	}
}


/**
 * Zeigt Waldnavigation an.
 *
 * @param bool Beschreibungskopf für den Wald zeigen (optional, Standard false)
 */
function forest($noshowmessage=false)
{
	global $session,$playermount, $access_control;
	$str_output = '';
	$conf = utf8_unserialize($session['user']['donationconfig']);
	if ($conf['healer'] || $session['user']['acctid']==getsetting('hasegg',0) || ($session['user']['marks']>=31))
	{
		addnav('H?Golindas Hütte','healer.php');
	}
	else
	{
		addnav('H?Hütte des Heilers','healer.php');
	}
	addnav('Kampf');
	addnav('B?Etwas zum Bekämpfen suchen','forest.php?op=search');
	if ($session['user']['level']>1)
	{
		addnav('e?Herumziehen','forest.php?op=search&type=slum');
	}
	addnav('N?Nervenkitzel suchen','forest.php?op=search&type=thrill');

	if($session['user']['dragonkills'] >= 50 && $session['user']['level'] <= 14) {
		addnav('l?Hölle suchen','forest.php?op=search&type=extreme');
	}

	addnav('Sonderbare Orte');
	addnav('W?Die Waldlichtung','tempel.php?op=witches');
	if ($session['user']['exchangequest']>0) //Hilfeseite für Tauschquest
	{
		addnav('o?Die Nornen','well_of_urd.php');
	}
	addnav('Frühstücks-Wiese','forest_rpg_places.php?op=grassyfield');
	addnav('K?Die Kreuzung','forest_rpg_places.php');
	addnav('#?Das verlassene Schloss','abandoncastle.php');
	
	$admin = $access_control->su_check(access_control::SU_RIGHT_COMMENT);

	// Rassenräume
	$arr_race = race_get($session['user']['race']);

	// Wenn Rassenraum im Wald
	if($arr_race['raceroom'] == 1) {
		addnav($arr_race['raceroom_nav'],'racesspecial.php?race='.$arr_race['id']);
	}

	// Wenn Spieler alle Rassenräume betreten kann
	if($arr_race['raceroom_all'] || $admin) {

  	addnav('');
		$sql = 'SELECT id,raceroom_nav,raceroom FROM races WHERE raceroom=1 AND id != "'.$session['user']['race'].'"';
		$res = db_query($sql);

		while($r = db_fetch_assoc($res)) {
			addnav($r['raceroom_nav'],'racesspecial.php?race='.$r['id'],false,false,false,false);
		}
	}
	addnav('');
	if (($playermount['tavern']>0) || ($conf['darkhorsetavern']))
	{
		addnav('T?Zur Dark Horse Taverne','forest.php?op=darkhorse');
	}
	if ($conf['castle'])
	{
		addnav('r?Zur Burg','forest.php?op=castle');
	}
	$arr_aei = user_get_aei('job');
	if ($arr_aei['job']==JOB_MINER){
		addnav('i?Goldmine','forest.php?specialinc=goldmine.php&callmethod=legal');
	} elseif ($conf['goldmine']>0) 
	{
		addnav('i?Goldmine ('.$conf['goldmine'].'x)','paths.php?ziel=goldmine&pass=conf');
	}

	addnav('','forest.php');


	include_once(LIB_PATH.'boss.lib.php');
	boss_get_nav('green_dragon');
	boss_get_nav('jackolantern');

    if($session['user']['gold']>0){
        addnav('');
        addnav('g?`&Gold zur Bank schicken','forest.php?op=sendgnome');
    }
	addnav('Sonstiges');

	//Knappe bringt Gold zur Bank
	$arr_disc = $session['bufflist']['decbuff'];
	if (isset($arr_disc) && $arr_disc['state'] == 15 && $arr_disc['rounds'] > 1 && $session['user']['gold']>0)
	{
        addnav('s?'.$arr_disc['realname'].' `&zur Bank schicken','forest.php?op=senddisciple');
	}

	addnav('d?Zurück zum Stadtzentrum','village.php');
	addnav('M?Zurück zum Marktplatz','market.php');
	addnav('P?Plumpsklo','outhouse.php');
	if ($session['user']['turns']<=1 )
	{
		addnav('x?Hexenhaus','hexe.php');
	}
	if ($noshowmessage!=true){
		if ($session['user']['prefs']['noimg']==1)
		{
			$str_output .= '`c`b`jD`2e`Jr Wa`2l`jd`b`0`c`n';
		}
		if(($session['user']['exchangequest']>0 && $session['user']['exchangequest']<30) || e_rand(0,40)==20)
		{
			$str_well_of_urd = '<a style="text-decoration:none; color:#C0C0C0;" href="well_of_urd.php">o</a>';
		}
		else
		{
			$str_well_of_urd = '<a style="text-decoration:none; color:#1DD000;" href="well_of_urd.php">o</a>';
		}
		$str_output .= '`jD`2e`Jr Wald, Heimat von bösartigen Kreaturen und Übeltätern aller Art.`n`n
		Die dichten Blätter des Waldes erlauben an den meisten Stellen nur wenige Meter Sicht.
		Die Wege würden dir verb'.$str_well_of_urd.'rgen bleiben, hättest du nicht ein so gut geschultes Auge. Du bewegst dich so leise wie
		eine milde Brise über den dicken Humus, der den Boden bedeckt. Dabei versuchst du es zu vermeiden
		auf dünne Zweige oder irgendwelche der ausgebleichten Knochenstücke zu treten, welche den Waldboden spicken.
		Du verbirgst deine Gegenwart vor den abscheulichen Monstern, die den Wald durchwande`2r`jn.';
		addnav('','well_of_urd.php');

		if ($session['user']['turns']<=1)
		{
			$str_output .= ' `jI`2n`J der Nähe siehst du wieder den Rauch aus dem Kamin eines windschiefen Hexenhäuschens aufsteigen, von dem du schwören könntest, es war eben noch nicht `2d`Ja. ';
		}
		$str_output.='</span>';
	}

	// Imagemap by Maris
	if ($session['user']['prefs']['noimg']==0)
	{
		$str_head = '`b`7Der Wald`0`b';
		$gen_output='<div><map name="derwald" id="derwald">';
		if ($session['user']['level']>1)
		{
			$gen_output.='<area shape="rect" coords="30,150,100,40" href="forest.php?op=search&amp;type=slum" title="Herumziehen">';
			addnav('','forest.php?op=search&type=slum');
		}
		$gen_output.='<area shape="rect" coords="170,160,260,50" href="forest.php?op=search" title="Etwas zum Bekämpfen suchen">
			 <area shape="rect" coords="310,190,380,60" href="forest.php?op=search&amp;type=thrill" title="Nervenkitzel">';
		addnav('','forest.php?op=search');
		addnav('','forest.php?op=search&type=thrill');
		addnav('','well_of_urd.php');
		if($session['user']['dragonkills'] >= 50 && $session['user']['level'] <= 14)
		{
			$gen_output.='<area shape="rect" coords="470,190,520,100" href="forest.php?op=search&amp;type=extreme" title="Hölle suchen">';
			addnav('','forest.php?op=search&type=extreme');
		}
		if($session['user']['exchangequest'] > 0)
		{
			$gen_output.='<area shape="rect" coords="426,37,436,74" href="well_of_urd.php" title="Zu den Nornen">';
		}
		$gen_output.='</map></div><img border="0" src="./images/forest.jpg" usemap="#derwald">';

		headoutput('`n`c'.print_frame($gen_output,$str_head,0,true).'`c`n');
	}
	// Ende Imagemap

	//Changed to adapt the walspecialeditor needs
	if ($access_control->su_check(access_control::SU_RIGHT_FORESTSPECIAL))
	{
		if($_GET['su_op']=='forestspecials')
		{
			if(isset($session['su_forestspecialnav']))
			{
				unset($session['su_forestspecialnav']);
			}
			else
			{
				$session['su_forestspecialnav']=1;
			}
		}
		if($session['su_forestspecialnav'])
		{
			$str_output .= '`n`nSUPERUSER Specials: '.create_lnk('ausblenden','forest.php?su_op=forestspecials').'`n';
			$query_result = db_query('SELECT filename FROM special_events ORDER BY filename ASC');
			$count = db_num_rows($query_result);
			$arr_forest_specials = array();
			for ($i=0;$i<$count;$i++)
			{
				$row = db_fetch_assoc($query_result);
				$str_output .= '<a href="forest.php?specialinc='.$row['filename'].'">'.$row['filename'].'</a>`n';
				$arr_forest_specials[] = $row['filename'];
			}
			addpregnav('/forest.php\?specialinc='.join('|',$arr_forest_specials).'/');
			unset($arr_forest_specials);
		}
		else
		{
			$str_output.='`n`nSUPERUSER Specials: '.create_lnk('einblenden','forest.php?su_op=forestspecials').'`n';
		}
	}
	output($str_output,true);
}

/**
 * Gibt Grottennav (Zurück zur Grotte / zum Weltlichen) aus; checkt davor auf access_control::SU_RIGHT_GROTTO
 *
 * @param array $arr_conf 		Asspz. Konfig-Array, der bestimmt, welche Links gezeigt werden. (String => boolean) mit Keys: grotto, mundane, petition
 */
function grotto_nav ($arr_conf = array('grotto'=>true,'mundane'=>true,'petition'=>true))
{
	global $access_control,$session;
	if(!$access_control->su_check(access_control::SU_RIGHT_GROTTO))
	{
		systemlog('`$`bAchtung: Aufruf von grotto_nav() ohne Grottenrechte!`b`0',$session['user']['acctid']);
		return;
	}

	if(!empty($session['su_petitions_bookmark_id']) && $arr_conf['petition'] && $access_control->su_check(access_control::SU_RIGHT_PETITION))
	{
		addnav('A?Zurück zur Anfrage','su_petitions.php?op=view&id='.$session['su_petitions_bookmark_id']);
	}
	if($arr_conf['grotto'])
	{
		addnav('G?Zurück zur Grotte','superuser.php');
	}
	if($arr_conf['mundane'])
	{
		addnav('W?Zurück zum Weltlichen','superuser.php?op=superuser_ret');
	}

}

/**
* @author talion
* @desc Unterstützt Erstellung seitenübergreifender Inhalte durch Bereitstellung einer
*		Seiten-Navi (Verwendet dazu GET['page'])
* @param string Basisadresse (mitsamt aller Params bis auf page, ohne Bindezeichen! [? oder &])
* @param mixed Wenn String: Count-SQL (Für COUNT Alias c verwenden!), wenn int: Gesamtanzahl der Tupel
* @param int Ergebnisse pro Seite (Optional, Standard 50)
* @param string Überschrift für Seitenliste in Navi, Leer für inaktiv (Optional, Standard 'Seiten')
* @param string Seitenbezeichnung in Navi (Optional, Standard 'Seite'). Wenn leer: Keine addnav-Ausgabe, sondern HTML-String
* 					mit Seitennavi (s.u.)!
* @param bool Von-Bis hinter Navipunkt anzeigen? (Optional, Standard true)
* @return array Max. Seitenzahl (maxpage), Gesamtzahl (count), aktuelle Seite (page),
				LIMIT-String für den Daten-Query ohne LIMIT-Keyword (limit), Ergebnis von (from), Ergebnis bis (to),
				page_nav mit HTML-String, der Seitennavi enthält, falls entsprechender Param gesetzt
*/
function page_nav ($str_baselnk, $count, $int_rpp=50, $str_caption='Seiten', $str_site='Seite', $bool_range=true) {

	// Navi-Link ermitteln
	$str_baselnk = utf8_preg_replace('/([?&]page=[0-9]*)/','',$str_baselnk);
	$str_last_sign = mb_substr($str_baselnk,mb_strlen($str_baselnk)-1);
	if($str_last_sign != '&' && $str_last_sign != '?') {
		$str_baselnk .= (mb_strpos($str_baselnk,'?') ? '&' : '?');
	}
	$str_baselnk .= 'page=';

	// Gesamtanzahl ermitteln
	$int_count = 0;
	if(is_numeric($count))
	{
		$arr_data['count'] = (int)$count;
	}
	elseif (is_string($count)) {
		$arr_count = db_fetch_assoc(db_query($count));
		$arr_data['count'] = $arr_count['c'];
	}
	else {
		$arr_data['count'] = (int)$count;
	}

	//if(!$arr_data['count']) {return(false);}

	$arr_data['page_nav'] = '';

	// Aktuelle Seite ermitteln
	$arr_data['page'] = (int)$_REQUEST['page'];
	$arr_data['page'] = ($arr_data['page'] <= 0 ? 1 : $arr_data['page']);

	// Max. Seite ermitteln
	$arr_data['maxpage'] = ceil($arr_data['count'] / $int_rpp);

	// LIMIT-String erstellen
	$arr_data['from'] = ($arr_data['page'] - 1) * $int_rpp;
	$arr_data['to'] = min($arr_data['page'] * $int_rpp,$arr_data['count']);
	$arr_data['limit'] = $arr_data['from'].','.$int_rpp;

	if($arr_data['maxpage']) {

		// Übermaß an Navis vermeiden (wenn Navis überhaupt erstellt werden sollen)
		if($arr_data['maxpage'] > 60 && !empty($str_site)) {
			addnav('Seitengruppen');

			for($i=1; $i<=$arr_data['maxpage']; $i+=60) {

				$int_page_to = min($i + 59, $arr_data['maxpage']);

				$int_from = ($i-1) * $int_rpp + 1;
				$int_to = min($int_page_to * $int_rpp, $arr_data['count']);

				addnav( $str_site.' '.$i.' - '.$int_page_to.' '.($bool_range ? ' ('.$int_from.' - '.$int_to.')' : ''), $str_baselnk.$i);

			}
			$int_page_from = floor( ($arr_data['page']-1) / 60) * 60 + 1;
			$int_page_to = min($arr_data['maxpage'], ceil($arr_data['page'] / 60) * 60 );
		}
		else {
			$int_page_to = $arr_data['maxpage'];
			$int_page_from = 1;
		}

		if(!empty($str_caption) && !empty($str_site)) {addnav($str_caption);}

		// Seitennavi erstellen
		for($i=$int_page_from; $i<=$int_page_to; $i++) {

			$int_from = ($i-1) * $int_rpp + 1;
			$int_to = min($i * $int_rpp, $arr_data['count']);

			if(!empty($str_site)) {
				addnav( ($i == $arr_data['page'] ? '`^': '').$str_site.' '.$i.($bool_range ? ' ('.$int_from.' - '.$int_to.')`0' : ''), $str_baselnk.$i);
			}
			else {
				$arr_data['page_nav'] .= $str_caption.' <a href="'.$str_baselnk.$i.'">'.($i == $arr_data['page'] ? '`^': '').$i.'`0</a>';
			}

		}
	}

	return($arr_data);

}

/**
 * Speichert Link auf Seite, um Zurücklink o.ä. anzubieten.
 *
 * @param string Link (wird mit calcreturnpath behandelt)
 */
function set_restorepage_history ($str_val) {

	global $session;

	$session['user']['prefs']['restore_history'] = calcreturnpath($str_val);

}

/**
 * Gibt gespeicherten Zurücklink zurück
 *
 * @return string Link
 */
function get_restorepage_history () {

	global $session;

	if(isset($session['user']['prefs']['restore_history'])) {
		return ($session['user']['prefs']['restore_history']);
	}
	else {
		return ('');
	}

}

/**
 * Start processing a special-file on any location in the script
 *
 * @param String 	$str_category 		The category in which the special file can be found
 * @param int 		$int_probability 	The probability by which a special will be called - Should be between 0 and 1000, so the value is in promille
 * @param String 	$str_explicit_file 	If given this will include the given file (if it can be found)
 * @param array		$bool_save_navs_for_special_execution	Array of variable names which have to be saved in order to get to the same location where
 *  														the special has to be included (default: array('op') => Saves the $_GET['op'] parameter)
 * @param String 	$str_header 		Defines the page_header for the special
 * @author Dragonslayer for Atrahor.de
 * @example spc_get_special('forest',70,'',array('op','subop'),true,'','A different header');
 */
function spc_get_special(
$str_category = 'forest',
$int_probability = 70,
$str_explicit_file = '',
$arr_save_navs_for_special_execution = array('op'),
$str_header = ''
)
{
	global $session,$nav,$output,$accesskeys,$header,$access_control,$Char;

	//Wurde bereits ein Special geladen? Dann wird das gleiche Special jetzt wieder geladen
	if (!empty($session['user']['specialinc']))
	{
		$str_explicit_file = $session['user']['specialinc'];
		$session['user']['specialinc'] = '';


		//Falls ein Special innerhalb eines if($_GET['xyz']...) läuft wird hier versucht der Pfad zum Special wieder herzustellen
		//indem alle Variablen wiederhergestellt werden, die eine Abarbeitung des Skripts bis zum Aufruf des Specials ermöglicht haben.
		if(count($arr_save_navs_for_special_execution)>0)
		{
			spc_save_navs_for_special_execution($arr_save_navs_for_special_execution);
		}

		spc_save_navs();
		$spc_str_output_backup = $output;
		$spc_str_header_backup = $header;
		clear_data(true,true,true,true);

		include('./special/'.$str_explicit_file);

		$spc_str_output = $output;

		if(!empty($spc_str_output))
		{
			$output = null;
			$output .= $spc_str_output;
		}
		else
		{
			$output = $spc_str_output_backup;
		}

		if(empty($header))
		{
			page_header(strip_appoencode($str_header,3));
		}

		if($str_header != '')
		{
			$output = appoencode($str_header).$output;
		}
		else
		{
			$output = appoencode('`c`b`yEtwas Besonderes!`0`b`c`n`n').$output;
		}

		//Damit der User nicht hängen bleibt:
		//Hat das Special navs geschrieben dann führ ein page_footer aus
		//Ansonsten schreibe das nav_backup_zurück
		if (is_array($session['allowednavs']) && count($session['allowednavs'])!=0)
		{
			page_footer();
		}
		else
		{
			//Die Variablen sind nicht mehr nötig
			spc_delete_navs_for_special_inclusion();
			spc_restore_navs();
			return;
		}
	}

	//Wurde eine spezielle Datei angegeben? Dann ist die wahrscheinlichkeit dass es auftritt = 100
	if($str_explicit_file != '')
	{
		$int_probability = 1000;
	}

	$int_random = e_rand(1,1000);

	/*Testing the random generator
	if($int_random<=$int_probability)
	$output .= '<font color="red">'.$int_random.'('.$int_probability.') - </font>';
	else
	$output .= $int_random.'('.$int_probability.') - ';
	//return;*/

	if ($int_random<=$int_probability)
	{
		if(!empty($str_explicit_file))
		{
			$str_sql = 'SELECT filename FROM special_events WHERE filename="'.$str_explicit_file.'" AND released=1 AND dk <='.$session['user']['dragonkills'];
		}
		else
		{
			$str_sql = 'SELECT filename FROM special_events LEFT JOIN special_category USING (category_id) WHERE category_name="'.$str_category.'" AND prio <= '.e_rand(0,3).' AND dk <='.$session['user']['dragonkills'].' AND released=1 ORDER BY RAND() LIMIT 1';
		}

		$db_result = db_query($str_sql);
		if(db_num_rows($db_result)==0)
		{
			return;
		}

		$str_special = db_result($db_result,0,'filename');
		unset($db_result);

		if ($str_special !== false)
		{
			//Falls ein Special innerhalb eines if($_GET['xyz']...) läuft wird hier versucht der Pfad zum Special wieder herzustellen
			//indem alle Variablen wiederhergestellt werden, die eine Abarbeitung des Skripts bis zum Aufruf des Specials ermöglicht haben.
			if(count($arr_save_navs_for_special_execution)>0)
			{
				spc_save_navs_for_special_execution($arr_save_navs_for_special_execution);
			}

			$y = $HTTP_GET_VARS['op'];
			$HTTP_GET_VARS['op']='';
			$yy = $_GET['op'];
			$_GET['op']='';

			spc_save_navs();
			$spc_str_output_backup = $output;
			clear_data(true,false,true,true);

			include('special/'.$str_special);

			$spc_str_output = $output;

			if(!empty($spc_str_output))
			{
				$output = null;
				$output .= $spc_str_output;
			}
			else
			{
				$output = $spc_str_output_backup;
			}

			if(empty($header))
			{
				page_header(strip_appoencode($str_header,3));
			}

			if($str_header != '')
			{
				$output = appoencode($str_header).$output;
			}
			else
			{
				$output = appoencode('`c`b`yEtwas Besonderes!`0`b`c`n`n').$output;
			}

			$session['specialinc_debug'] = $str_special;

			$HTTP_GET_VARS['op']=$y;
			$_GET['op']=$yy;

			//Damit der User nicht hängen bleibt:
			//Hat das Special navs geschrieben dann führ ein page_footer aus
			//Ansonsten schreibe das nav_backup_zurück
			if (is_array($session['allowednavs']) && count($session['allowednavs'])!=0 )
			{
				page_footer();
			}
			else
			{
				//Die Variablen sind nicht mehr nötig
				spc_delete_navs_for_special_inclusion();
				spc_restore_navs();
				return;
			}
		}
		else
		{
			admin_output('Das gesuchte Special '.$str_special.' existiert nicht');
		}
	}
}

/**
 * @desc Wrapper für die spc_save_restore Methode, speichert die Navs ab!
 * @author Dragonslayer
 */
function spc_save_navs()
{
	global $session,$accesskeys,$nav;
	$session['navbackup']['allowednavs'] = $session['allowednavs'];
	$session['navbackup']['user']['allowednavs'] = $session['user']['allowednavs'];
	$session['navbackup']['accesskeys'] = $accesskeys;
	$session['navbackup']['nav'] = $nav;
}

/**
 * @desc Wrapper für die spc_save_restore Methode, stellt die Navs wieder her!
 * @author Dragonslayer
 */
function spc_restore_navs()
{
	global $session,$accesskeys,$nav;
	$arr_1 = is_array($session['navbackup']['user']['allowednavs'])?$session['navbackup']['user']['allowednavs']:array();
	$arr_2 = is_array($session['user']['allowednavs'])?$session['user']['allowednavs']:array();

	$arr_3 = is_array($session['navbackup']['allowednavs'])?$session['navbackup']['allowednavs']:array();
	$arr_4 = is_array($session['allowednavs'])?$session['allowednavs']:array();

	$arr_5 = is_array($_SESSION['navbackup']['accesskeys'])?$_SESSION['navbackup']['accesskeys']:array();

	$session['user']['allowednavs'] = array_merge($arr_1,$arr_2);
	$session['allowednavs'] = array_merge($arr_3,$arr_4);
	$accesskeys = array_merge($arr_5,$accesskeys);
	$nav = $nav.$session['navbackup']['nav'];
	unset ($session['navbackup']);
}

/**
 * @desc Saves all Parameters which are needed to get through the script down
 * to the point where the special execution starts
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 */
function spc_save_navs_for_special_execution($arr_variables)
{
	global $session;
	foreach($arr_variables as $mixed_variable)
	{
		if(isset($_GET[$mixed_variable]))
		{
			$session['spc_navs']['get'][$mixed_variable] = $_GET[$mixed_variable];
		}
		if(isset($_POST[$mixed_variable]))
		{
			$session['spc_navs']['post'][$mixed_variable] = $_POST[$mixed_variable];
		}
	}
	if(isset($session['SPC_OP']))
	{
		$_GET['op'] = $session['SPC_OP'];
	}
}

/**
 * @desc Restores all Parameters which are needed to get through the script down
 * to the point where the special execution starts
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 */
function spc_restore_navs_for_special_execution()
{
	global $session, $BOOL_JS_HTTP_REQUEST;
	if(isset($session['spc_navs']) && $BOOL_JS_HTTP_REQUEST == false)
	{
		$session['SPC_OP'] = $_GET['op'];

		if(is_array($session['spc_navs']['get']))
		{
			$_GET  = array_merge($_GET,$session['spc_navs']['get']);
		}
		if(is_array($session['spc_navs']['post']))
		{
			$_POST = array_merge($_POST,$session['spc_navs']['post']);
		}

		spc_delete_navs_for_special_inclusion();
	}
}

function spc_delete_navs_for_special_inclusion()
{
	global $session;
	unset ($session['spc_navs']);
}
?>
