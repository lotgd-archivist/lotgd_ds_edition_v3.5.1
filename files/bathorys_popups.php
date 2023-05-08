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
    case 'abo':
        require_once(BPATH.'module_abo.php');
        new MAbo($mdo,true);
        break;

    case 'ignore':
        require_once(BPATH.'module_ignore.php');
        new MIgnore($mdo,true);
        break;

    case 'quest':
        require_once(BPATH.'module_quest.php');
        new MQuest($mdo,true);
        break;

    case 'calendar':
        require_once(BPATH.'module_calendar.php');
        new MCalendar($mdo,true);
        break;

	default:
		die(' More comming soon =)! ');
	break;
}

?>
