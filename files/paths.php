<?php

// 21072004

// includes specials for normal use
// idea by Durandil

require_once("common.php");
$pfad="./special/".($_GET['ziel']).".php";

if (!utf8_preg_match('/^[a-z_]*$/',$_GET['ziel']) || !is_readable($pfad)) 
{
	systemlog($pfad.' nicht lesbar');
	clearstatcache();
	redirect("village.php");
}

checkday();
page_header("Etwas Besonderes");
addcommentary();
include($pfad);

if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0)
{
	addnav('d?Zum Stadtzentrum','village.php');
}

page_footer();
?>