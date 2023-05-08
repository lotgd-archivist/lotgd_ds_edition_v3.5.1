<?php
/**
 * index.php: Startseite für Atrahor
 * @author LOGD-Core / Drachenserver-Team (Überarbeitung, Performance, Liverestzeit)
 * @version DS-E V/2
*/

require_once 'common.php';

if (isset($session['loggedin']) && $session['loggedin'])
{
	redirect('badnav.php');
}
else
{
	utf8_setcookie('eingeloggtbleiben', 0, time() - 42000);
    define_template(getsetting('defaultskin','dragonslayer_1'));
}


if(!isset($_SERVER['HTTPS']))$_SERVER['HTTPS']=false;
$arr_server = parse_url(getsetting('server_address','localhost'));
$str_referer = ($_SERVER['HTTPS']?'https://':'http://').$_SERVER['HTTP_HOST'];
if(!LOCAL_TESTSERVER && !utf8_preg_match('/'.utf8_preg_quote($arr_server['host']).'/i',$str_referer))
{
	//utf8_setcookie('lasthit',0,strtotime(date('r').'+365 days'));
	redirect(getsetting('server_address','localhost'));
}

page_header();

$str_out = '';

$str_townname = getsetting('townname','Atrahor');
$int_maxonline = getsetting('maxonline',10);

$str_out .= '`c`IWillkommen bei Legend of the Green Dragon in der Dragonslayer-Edition, schamlos abgekupfert von Seth Able\'s Legend of the Red Dragon.`n`n';

if (getsetting('activategamedate','0')==1)
{
	$str_out .='`IWir schreiben den `y'.getgamedate().'`I.`0`n';
}
$str_out .='`IDie gegenwärtige Zeit in '.$str_townname.' ist `y'.getgametime(true).'`I.`0`n';

//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));

$calctime = strtotime('1980-01-01 00:00:00 + '.$realsecstotomorrow.' seconds');

$nextdattime = date('G \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n, s \\S\\e\\k\\u\\n\\d\\e\\n\\ \\(\\E\\c\\h\\t\\z\\e\\i\\t\\)',$calctime);
$str_out .='`INächster neuer Tagesabschnitt in: `0<div id="index_time">`y'.$nextdattime.'`0</div>`n`n';

$newdk=stripslashes(getsetting('newdragonkill',''));
if ($newdk!='')
{
	$str_out .='`IDie letzte Heldentat vollbrachte: `y'.$newdk.'`I!`0`n';
}

$guild=stripslashes(getsetting('dgtopguild',''));
if ($guild!='')
{
	$str_out .='`IDie angesehenste Gilde '.getsetting('townname','Atrahor').'s zur Zeit ist: `y'.$guild.'`I!`0`n`n';
}

$dkcounter = number_format( (int)getsetting('dkcounterges',0) , 0 , ' ', ' ' );
if ($dkcounter>0)
{
	$str_out .='`IInsgesamt haben unsere Helden bereits `y'.$dkcounter.'`I Heldentaten vollbracht!`0`n`n';
}

$fuerst=stripslashes(getsetting('fuerst',''));
if ($fuerst!='')
{
	$str_out .='`IDen Fürstentitel '.$str_townname.'s trägt zur Zeit: `0`b`y'.$fuerst.'`0`b`I!`0`n`n';
}

if(getsetting('wartung',0) > 0) {
	$str_out .='`b`^Der Server befindet sich im Moment im Wartungsmodus, um Änderungen am Spiel oder dem Server störungsfrei vornehmen zu können.`0`b`^`nBitte warte, bis sich dies ändert.`n`n`0';
}

$result = db_fetch_assoc(db_query("SELECT COUNT(*) AS onlinecount FROM accounts WHERE locked=0 AND ".user_get_online() ));
$onlinecount = $result['onlinecount'];

if (( $onlinecount >= $int_maxonline && $int_maxonline!=0) || getsetting('wartung',0) > 0 )
{
	$id=$_COOKIE['lgi'];
	$sql = "SELECT superuser,uniqueid FROM accounts WHERE uniqueid='$id' AND superuser>0";
	$result = db_query($sql);
	if (db_num_rows($result)>0)
	{
		$row = db_fetch_assoc($result);
		$is_superuser=$row['superuser'];
	}
	else
	{
		$is_superuser=0;
	}
}
else
{
	$is_superuser = 0;
}

if ( ($onlinecount<$int_maxonline || $int_maxonline==0 || $is_superuser) )
{
	$str_out .='`tGib deinen Namen und dein Passwort ein, um '.$str_townname.' zu betreten.`n`n`0';
	if (isset($_GET['op']) && $_GET['op']=='timeout' )
	{
		$session['message'].='`nDeine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n';
		if (!isset($_COOKIE[session_name()]))
		{
			$session['message'].=' Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n';
		}
	}
	if ($session['message']!='')
	{
		$str_out .= '`b`$'.$session['message'].'`0`b`n';
	}
	$str_out .= "<div id='LOGIN_FORM'><form action='login.php' name='loginform' method='POST'>"
	.templatereplace("login",array("username"=>"<span style='text-decoration: underline;'>N</span>ame","password"=>"<span style='text-decoration: underline;'>P</span>asswort","button"=>"Einloggen"))
	.'</form></div>`c';
	addnav('','login.php');
}
else
{
	$str_out .='`b`^Der Server ist im Moment ausgelastet, die maximale Anzahl an Usern ist bereits online.`0`b`^`nBitte warte, bis wieder ein Platz frei ist.`n`n`0';
	if ($_GET['op']=='timeout')
	{
		$session['message'].='`nDeine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n';
		if (!isset($_COOKIE[session_name()]))
		{
			$session['message'].=' Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n';
		}
	}
	if ($session['message']!='')
	{
		$str_out .='`b`$'.$session['message'].'`b`n';
	}
	$str_out .=templatereplace('full').'`c';
}

$str_out .='`n`c`b`&'.getsetting('loginbanner','').'`0`b`c`n';
$session['message']='';
$str_out .='`c`t'.$str_townname.' läuft unter: `y'.GAME_VERSION.'`0`c`n';

// Ausgabe
output($str_out);

// Hotkeys auf Startseite?
$bool_hotkeys = true;
if(!isset( $_GET['r'] ) || $_GET['r']  == null) $_GET['r']  = '';
$int_ref=intval($_GET['r']);
$str_ref=($int_ref>0?'?r='.$int_ref:'');
$str_ref2=($int_ref>0?'&r='.$int_ref:'');
clearnav();
addnav('Neu hier?');
addnav('`yCharakter erstellen`0','create.php'.$str_ref,false,false,false,$bool_hotkeys);
if(getsetting('demouser_public',0)>0)
{
	addnav('Schnupperzugang','demouser.php'.$str_ref,false,false,false,$bool_hotkeys);
}
addnav('Über '.getsetting('townname','Atrahor'),'about_server.php'.$str_ref,false,false,false,$bool_hotkeys);
addnav('Das Spiel');
addnav('Liste der Einwohner','list.php'.$str_ref,false,false,false,$bool_hotkeys);
addnav('Passwort vergessen?','create.php?op=forgot'.$str_ref2,false,false,false,$bool_hotkeys);
addnav('Die LoGD-Welt');
addnav('About LoGD','about.php'.$str_ref,false,false,false,$bool_hotkeys);
addnav('LoGD Net','logdnet.php?op=list'.$str_ref2,false,false,false,$bool_hotkeys);

page_footer();
?>
