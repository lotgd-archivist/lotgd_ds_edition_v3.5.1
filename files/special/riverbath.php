<?php
/****************************************/
/* river for bathing
/* Version 0.1
/* Type: Forest Event
/* Web: http://www.pqcomp.com/logd
/* E-mail: logd@pqcomp.com
/* Author: Lonny Luberts
/*
/* Modification by SkyPhy, July 04
/* Translation by SkyPhy, July 04
/**************************************/
if(!isset($session))
{
	exit();
}
require_once 'common.php';
checkday();
$session['user']['specialinc']='riverbath.php';
if ($_GET['op'] == '')
{
output('Du kommst bei einem `wkleinen Bach`0 vorbei. Das Wasser ist einladend sauber. Was für ein schöner Platz zum baden!');
addnav('Baden gehen','forest.php?op=bathe');
addnav('Weitergehen','forest.php?op=continue');
}
elseif ($_GET['op'] == 'continue')
{
	$session['user']['specialinc']='';
	output('Du denkst dir, &laquo;Man muss ja nicht gleich in jeden Bach springen...&raquo; und ziehst weiter.');
}
elseif ($_GET['op'] == 'bathe')
{
	output('Schnell ziehst du dich aus und springst in den Bach. Als du wieder heraussteigst fühlst du dich viel frischer und sauberer! ');
	if (e_rand(0,4)==2)
	{
		output('Als du dich wieder anziehst findest du unter deinen Kleidern `6einen Edelstein`0.');
		$session['user']['gems']++;
	}
	$session['user']['specialinc']='';
	//addnav('Weiter','forest.php');
}
?>