<?php

$DONT_OVERWRITE_NAV 	= true;
$BOOL_JS_HTTP_REQUEST 	= true;
require_once('common.php');

$do = mb_strtolower(urldecode(stripslashes($_GET['do'])));
$q = mb_strtolower(urldecode(stripslashes($_GET['term'])));

if (!$q)
{
	exit;
}

$arr_users = CCharacter::getChars($q,'`name`,`login`,`superuser`,`loggedin`,`laston`,`activated`',array(),'','`loggedin` DESC,`laston` DESC,`acctid` ASC',20);

$ans = array();
$ans_su = array();
$array = array();

// IDs aller Gruppen abrufen, die explizit als Superuser markiert sind
$sugroupKeys = $access_control->get_superuser_sugroups();

foreach($arr_users as $row)
{
    $array['value'] = ($do == 'login') ? $row['login'] : strip_appoencode($row['name'],3);
    $array['id'] =$row['login'];
	
	if (in_array($row['superuser'],$sugroupKeys))
	{
		$ans_su[] = $array;
	}
	else
	{
		$ans[] = $array;
	}
}

sort($ans);
sort($ans_su);

echo json_encode(array_merge($ans_su, $ans));

?>