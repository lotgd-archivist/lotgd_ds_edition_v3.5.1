<?php
/**
 * Badnav Datei zeigt die letzte vom User legal besuchte Seite an und dient somit als Auffangseite für alle Betrugsversuche
 *
 * @author LOTGD Core
 */

require_once 'common.php';

$session['debug'].='Badnav';


function check_navs($navs)
{
	global $allownonnav;
	
	$noreturnnavs = array_merge($allownonnav,array(
        'httpreq.php' => true,
        'news.php' => true,
        'badnav.php' => true,
        'httpreq_usermenu.php' => true,
        'newday.php' => true
    ));
	
	if(is_array($navs))
	{
		foreach($navs as $key => $val)
		{
			if(array_key_exists("".( mb_strpos($key,'?') ? mb_substr($key,0,mb_strpos($key,'?')) : $key )."",$noreturnnavs))
			{
				unset($navs[$key]);
			}
		}
		
		return ( (count($navs) > 0) ? true : false );
	}
	else
	{
		return false;
	}
}

if ($session['user']['loggedin'] && $session['loggedin'])
{
	// checkday() muss absolut konservativ aufgerufen werden!
	// (= nur dann, wenn die letzte Seite ohnehin auf newday weiterleiten wollte oder es wirklich keine andere Navmöglichkeit gibt)
	//if (mb_strpos($session['user']['output'],'<!--CheckNewDay()-->'))
	//{
		//checkday();
	//}


	if(is_array($session['allowednavs']))
	{
		foreach($session['allowednavs'] as $key => $val)
		{
			if 	(
					trim($key)=='' ||
					$key===0 ||
					mb_substr($key,0,8)=='motd.php' ||
					mb_substr($key,0,8)=='mail.php'
				)
			{
				unset($session['allowednavs'][$key]);
			}
		}
	}
	
	// sinnlose badnavs sowie den ND-bug??? beheben by bathory
	if(!check_navs($session['allowednavs']))
	{	
		$session['debug'].=' Keine sinnvole allowed Nav gefunden => reset der Navs ';
		debuglog(' Keine sinnvole allowed Nav gefunden => reset der Navs ');
		$session['allowednavs']=array();
		addnav('','village.php');
		$session['user']['output'] = '<a href="village.php">Deine erlaubten Navs waren beschädigt. Zurück in die Stadt.</a>';
	}
	if(count($session['allowednavs'])==1 && isset($session['allowednavs']['badnav.php']))
	{
		$session['allowednavs']=array();
		addnav('','village.php');
		$session['user']['output'] = '<a href="village.php">Deine erlaubten Navs waren beschädigt. Zurück in die Stadt.</a>';
	}
	if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0 || empty($session['user']['output']))
	{
		$session['allowednavs']=array();
		addnav('','village.php');
		$session['user']['output'] = '<a href="village.php">Deine erlaubten Navs waren beschädigt. Zurück in die Stadt.</a>';
	}
	// Aus dem Output wird Javascript und iFrames herausgefiltert, um automatisches Seitenreloading
	// durch Badnavs und Javascript zu vermeiden
	echo strip_selected_tags($session['user']['output'],array('iframe'),true,false);

	// Sehr wichtig für Verfolgung renitenter Bugs.
	$session['debug'].='Badnav ohne ND';
	$session['user']['allowednavs']=$session['allowednavs'];
	saveuser();
}
else
{
	Atrahor::clearSession();
	redirect('index.php');
}
?>