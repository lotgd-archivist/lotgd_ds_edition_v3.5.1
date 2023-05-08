<?php

$pagestarttime = microtime(true);
define('TIME_INT', time());
require_once('utf8.php');
require('dbconnect.php');
require('config.inc.php');

//Auto Class Loader
require_once(CLASS_PATH.'class.CClassLoader.php');

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    JS::$defer = false;
}else{
    JS::$defer = true;
}

require_once(LIB_PATH.'gametime.lib.php');
require_once(LIB_PATH.'db.lib.php');
require_once(LIB_PATH.'settings.lib.php');
require_once(LIB_PATH.'news.lib.php');
require_once(LIB_PATH.'commentary.lib.php');
require_once(LIB_PATH.'mail.lib.php');
require_once(LIB_PATH.'security.lib.php');
require_once(LIB_PATH.'rand.lib.php');
require_once(LIB_PATH.'nav.lib.php');
require_once(LIB_PATH.'buffs.lib.php');
require_once(LIB_PATH.'user.lib.php');
require_once(LIB_PATH.'gameplay_misc.lib.php');
require_once(LIB_PATH.'mount.lib.php');
require_once(LIB_PATH.'profession.lib.php');
require_once(LIB_PATH.'specialty.lib.php');
require_once(LIB_PATH.'chosen.lib.php');
require_once(LIB_PATH.'items.lib.php');
require_once(LIB_PATH.'output.lib.php');
require_once(LIB_PATH.'jslib.lib.php');

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

define('LINK','');
$mysqli->query("SET names 'utf8';");
$mysqli->query("SET time_zone = '".DEFAULT_TIMEZONE."';");

//do some cleanup here to make sure magic_quotes_gpc is ON, and magic_quotes_runtime is OFF, and error reporting is all but notice.
set_magic_quotes($_GET);
set_magic_quotes($_POST);
set_magic_quotes($_SESSION);
set_magic_quotes($_COOKIE);

ini_set('session.hash_function', 'sha512');
session_name('DS35SESSIONID_'.getsetting('townname','Atrahor'));
if((isset($_POST['eingeloggtbleiben']) && $_POST['eingeloggtbleiben']) || isset($_COOKIE['eingeloggtbleiben']) && $_COOKIE['eingeloggtbleiben'])
{
    ini_set('session.gc_probability', 1);
    $session_lifetime =  60 * 60 * 24 * 15;
    ini_set('session.gc_maxlifetime', $session_lifetime + 777);
    ini_set('session.save_path', 'cache/sessions/long');
    utf8_setcookie('eingeloggtbleiben', 1, time() + $session_lifetime);
    session_set_cookie_params($session_lifetime,'/',$_SERVER['HTTP_HOST'],isset($_SERVER['HTTPS']),true);
}
else
{
    ini_set('session.gc_probability', 1);
    $session_lifetime =  60 * 60 * 24;
    ini_set('session.gc_maxlifetime', $session_lifetime + 777);
    ini_set('session.save_path', 'cache/sessions');
    session_set_cookie_params(0,'/',$_SERVER['HTTP_HOST'],isset($_SERVER['HTTPS']),true);
}
session_start();
if((isset($_POST['eingeloggtbleiben']) && $_POST['eingeloggtbleiben']) || isset($_COOKIE['eingeloggtbleiben']) && $_COOKIE['eingeloggtbleiben'])
{
    if("" != session_id())utf8_setcookie(session_name(),session_id(),TIME_INT + $session_lifetime);
}

$session =& $_SESSION['session'];
//MemoryCache und AccessControl initialisieren
Atrahor::init();

// Locale-Einstellungen+Timezone
$str_locale = getsetting('locale','de_DE');
if(!empty($str_locale)) {
	setlocale(LC_TIME, $str_locale);
	setlocale(LC_CTYPE, $str_locale);
}
//Zeiteinstellung laden (verhindert PHP strict Warnmeldungen)
date_default_timezone_set(DEFAULT_TIMEZONE);
// END Locale-Einstellungen


register_global($_SERVER);

$session['lasthit'] = time();

$req_uri_ori = $_SERVER['REQUEST_URI'];

if (isset($PATH_INFO) && !empty($PATH_INFO))
{
	$SCRIPT_NAME=$PATH_INFO;
	$REQUEST_URI='';
}

//Necessary for some IIS installations (CGI in particular)
if (empty($REQUEST_URI))
{
	if (is_array($_GET) && count($_GET)>0)
	{
		$REQUEST_URI=$SCRIPT_NAME.'?';
		$i=0;
		foreach($_GET as $key=>$val)
		{
			if ($i>0)
			{
				$REQUEST_URI.='&';
			}
			$REQUEST_URI.=$key.'='.URLEncode($val);
			$i++;
		}
	}
	else
	{
		$REQUEST_URI=$SCRIPT_NAME;
	}
	$_SERVER['REQUEST_URI'] = $REQUEST_URI;

}

$SCRIPT_NAME=mb_substr($SCRIPT_NAME,mb_strrpos($SCRIPT_NAME,'/')+1);
if(!isset($PATH_INFO))$PATH_INFO='';
// Notfall-Fix gegen Cheaterei
if (mb_substr($SCRIPT_NAME,mb_strrpos($SCRIPT_NAME,".php"))!=".php" || mb_strpos($PATH_INFO,".php")){
    echo('<p>Schutzverletzung!</p>
    		<p>Verarbeitung abgebrochen. Ein Log mit einer Problembeschreibung wurde angefertigt!</p>');
    $int_acctid = (int)$session['user']['acctid'];
    systemlog('Ungültiger Scriptaufruf: SCRIPT_NAME -> '.$SCRIPT_NAME.'; PATH_INFO -> '.$PATH_INFO.'; REQUEST_URI -> '.$req_uri_ori.'; -> Verdacht auf Cheatversuch?',$int_acctid);
    exit();
}
unset($req_uri_ori);
if (mb_strpos($REQUEST_URI,'?'))
{
	$REQUEST_URI=$SCRIPT_NAME.mb_substr($REQUEST_URI,mb_strpos($REQUEST_URI,'?'));
}
else
{
	$REQUEST_URI=$SCRIPT_NAME;
}
$session['lastip']=$REMOTE_ADDR;

if(!isset($session['loggedin']) || !$session['loggedin'] || intval($session['user']['acctid']) == getsetting('demouser_acctid',0))
{
    if (mb_strlen($_COOKIE['lgi'])<32)
    {
        $u=md5('t_'.microtime());
        $_COOKIE['lgi']=$u;
        $session['uniqueid']=$u;
    }
    else
    {
        $session['uniqueid']=$_COOKIE['lgi'];
    }
}
else
{
    if (mb_strlen($_COOKIE['lgi'])<32)
    {
        if (mb_strlen($session['uniqueid'])<32)
        {
            $u=md5(microtime());
			utf8_setcookie('lgi',$u,strtotime(date('r').'+365 days'));
            $_COOKIE['lgi']=$u;
            $session['uniqueid']=$u;
        }
        else
        {
			utf8_setcookie('lgi',$session['uniqueid'],strtotime(date('r').'+365 days'));
        }
    }
    else
    {
        $session['uniqueid']=$_COOKIE['lgi'];
    }
}
$revertsession=$session;
if(!isset($session['req_debug']))
{
	$session['req_debug'] = array();
}
if(count($session['req_debug']) > 50)
{
	array_shift($session['req_debug']);
}
array_push($session['req_debug'],$REQUEST_URI);

// NAVS checken, User laden.
// Einstellungsarrays nun in nav.lib!
if (isset($session['loggedin']) && $session['loggedin'] && isset($session['user']['acctid']) && (int)$session['user']['acctid'])
{
	utf8_setcookie('lasthit', 0, time() - 42000);
	// ACCOUNT laden
	//user_load($session['user']['acctid']);
	$Char = new CCharacter( $session['user']['acctid'], true );
    if(!isset($DONT_OVERWRITE_NAV))$DONT_OVERWRITE_NAV=false;
    if(!isset($allownonnav[$SCRIPT_NAME]))$allownonnav[$SCRIPT_NAME]=false;
	// Aktualisiere Laston
	$session['user']['laston'] = date('Y-m-d H:i:s');
	if (isset($session['allowednavs'][$REQUEST_URI]) && $session['allowednavs'][$REQUEST_URI] && !$allownonnav[$SCRIPT_NAME])
	{
		if( !$DONT_OVERWRITE_NAV ){
			$session['allowednavs']=array();
		}
	}
	else
	{
		$badnav = true;
		if( isset($session['allowednavs']['preg']) ){
			foreach( $session['allowednavs']['preg'] as $navi ){
				if(utf8_preg_match($navi,$REQUEST_URI)){
					$badnav = false;
					if( !$DONT_OVERWRITE_NAV ){
						$session['allowednavs']=array();
					}
					break;
				}
			}
		}

		if ($badnav && !$allownonnav[$SCRIPT_NAME])
		{
			if( $BOOL_JS_HTTP_REQUEST ){
				if( count($session['allowednavs'])==1 && $session['allowednavs']['newday.php'] ){
					jslib_http_command('newday');
				}
				else{
					//jslib_http_command('badnav');
					// Anfrage einfach verwerfen?
					exit;
				}
			}
			else{
				redirect('badnav.php','Navigation auf '.$REQUEST_URI.' nicht erlaubt');
			}
		}
	}
	//section des user zurücksetzen
	if( !$BOOL_JS_HTTP_REQUEST && $SCRIPT_NAME!='badnav.php' && !$allownonnav[$SCRIPT_NAME]){
		$session['user']['chat_section'] = '';
	}

	if ($session['user']['imprisoned']==-5 && $session['user']['alive'])	// Stadtwachen RPG-Einkerkerung
	{
		$session['user']['imprisoned']=1;
		if( $BOOL_JS_HTTP_REQUEST ){
			addnav('','prison.php');
			saveuser();
			jslib_http_command('prison');
		}
		else{
			redirect('prison.php');
		}
	}

	// Auf unbestimmte Zeit im Kerker
	if ($session['user']['imprisoned']==-2)
	{
		$session['user']['imprisoned']=-1;
		if( $BOOL_JS_HTTP_REQUEST ){
			addnav('','prison.php');
			saveuser();
			jslib_http_command('prison');
		}
		else{
			redirect('prison.php');
		}
	}

	// Mails abrufen, wenn HTTP_REQUEST und portal checken
	$g_str_execute = '';
	if($BOOL_JS_HTTP_REQUEST) {
		if($session['user']['httpreq_flag'] & HTTPREQ_FLAG_NEW_MAIL) {

            if ($session['user']['prefs']['minimail'])
            {
            	$g_str_execute = '/exec document.getElementById("MAILBOXLINK").innerHTML = "'.addslashes(maillink(true)).'"; document.getElementById("MINILINK").innerHTML = "'.addslashes(minimail(true)).'";';
            }
            else
            {
            	$g_str_execute = '/exec document.getElementById("MAILBOXLINK").innerHTML = "'.addslashes(maillink(true)).'";';
            }
			$session['user']['httpreq_flag'] = $session['user']['httpreq_flag'] ^ HTTPREQ_FLAG_NEW_MAIL;

			user_update(array('httpreq_flag'=>$session['user']['httpreq_flag']),$session['user']['acctid']);

		}
	}

}
else
{
	if (!$allowanonymous[$SCRIPT_NAME])
	{
		if( $BOOL_JS_HTTP_REQUEST ){
			jslib_http_command('timeout');
		}
		else{
			redirect('index.php?op=timeout','Not logged in: '.$REQUEST_URI);
		}
	}
}
if(!isset($session['user']['loggedin'])) $session['user']['loggedin'] = false;
if ($session['user']['loggedin']!=true && (!$allowanonymous[$SCRIPT_NAME] && !access_control::is_superuser()))
{
	if( $BOOL_JS_HTTP_REQUEST ){
		jslib_http_command('timeout');
	}
	else{
		redirect('login.php?op=logout');
	}
}

$session['counter']++;

if(!isset($nokeeprestore[$SCRIPT_NAME])) $nokeeprestore[$SCRIPT_NAME] = false;

// Wenn wir Seite in Restore setzen dürfen
if (!$nokeeprestore[$SCRIPT_NAME] && !$BOOL_JS_HTTP_REQUEST)
{
	$g_ret_page = calcreturnpath($session['user']['restorepage']);
	$session['user']['restorepage']=$REQUEST_URI;
	// DEBUG
	if(!isset($session['rp_debug']))
{
	$session['rp_debug'] = array();
}
	if(count($session['rp_debug']) > 25)
	{
		array_shift($session['rp_debug']);
	}
	array_push($session['rp_debug'],$session['user']['restorepage']);
}

//Falls ein Special innerhalb eines if($_GET['xyz']...) läuft wird hier versucht der Pfad zum Special wieder herzustellen
//indem alle Variablen wiederhergestellt werden, die eine Abarbeitung des Skripts bis zum Aufruf des Specials ermöglicht haben.
spc_restore_navs_for_special_execution();

// Inventar standardmäßig auf aus
$show_invent = false;

define_template();

require_once(LIB_PATH.'house.lib.php');

if(!isset($str_output))$str_output = '';
if(!isset($output))$output = '';
if(!isset($text))$text = '';
if(!isset($str_out))$str_out='';
if(!isset($template['bodyscript']))$template['bodyscript'] = '';
if(!isset($template['headscript']))$template['headscript']='';
if(!isset($template['script']))$template['script']='';
if(!isset($template['twitter']))$template['twitter'] = '';
if(!isset($template['facebook_like']))$template['facebook_like'] = '';
if(!isset($template['facebook_bookmark']))$template['facebook_bookmark'] = '';
if(!isset($template['JS_LIB']))$template['JS_LIB'] = '';

$JSLIB_MENUINIT = false;
$HEADER_LOADED = false;
$global_title='';

?>