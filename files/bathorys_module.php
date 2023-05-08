<?php
/*
* @author Báthory
*/

define('BPATH','bathorys_module/');

require_once('common.php');
$module = isset($_GET['mod']) ? $_GET['mod'] : '';
$mdo = isset($_GET['mdo']) ? $_GET['mdo'] : '';

switch($module)
{
    case 'quest':
        require_once(BPATH.'module_quest.php');
        new MQuest($mdo);
        break;
	default:
		die(' More comming soon =)! ');
	break;
}

?>
