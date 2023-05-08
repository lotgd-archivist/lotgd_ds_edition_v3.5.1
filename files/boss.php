<?php
/**
 * Diese Datei ist die zentrale Schaltstelle um einen Bosskampf zu initieren
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 * @version DS/V3.23
 */

include('./common.php');
include(LIB_PATH.'boss.lib.php');

if(isset($_GET['boss']))
{
	$g_str_boss = $_GET['boss'];
}
elseif (empty($session['user']['specialmisc'])==false)
{
	$g_str_boss= $session['user']['specialmisc'];
}
else
{
	$g_str_boss = 'green_dragon';
}

$session['user']['specialmisc'] = $g_str_boss;

$g_arr_current_boss = boss_load_boss($g_str_boss);
$g_str_base_file = basename(__FILE__).'?boss='.$g_str_boss;

page_header(strip_appoencode($g_arr_current_boss['name'],3));

/**
 * Aufruf der Seite mit Parameter r speichert die Returnseite
 */
if(isset($_GET['r']) && $_GET['r']) {
	set_restorepage_history($g_ret_page);
}
$g_str_ret = get_restorepage_history();

switch($_GET['op'])
{
	case '':
	case 'intro':
		{
			if($session['user']['armordef']==0 && $session['user']['armor']!='Straßenkleidung') 
			{ //User hat Luxusgewand an
				//output('`c`bUnpassende Ausrüstung`b`c`n`^Du hast dein '.$session['user']['armor'].'`^ noch an. Hoffst du etwa, dein Gegner wird sich totlachen wenn du ihm so gegenübertrittst?!`0`n`n');
				jslib_mb('Du hast dein '.strip_appoencode($session['user']['armor']).' noch an. Hoffst du etwa, dein Gegner wird sich totlachen wenn du ihm so gegenübertrittst?!','Unpassende Ausrüstung');
				$show_invent=true;
			}
			boss_do_intro();
			break;
		}
	case 'autochallenge':
		{
			boss_do_autochallenge();
			break;
		}
	case 'run':
		{
			boss_do_run();
			break;
		}
	case 'fight':
		{
			boss_do_fight();
			break;
		}
	case 'epilogue':
		{
			boss_do_epilogue();
			break;
		}
	default:
		{
			clear_data(true,false,false,true);
			page_header('Error:'.$g_str_boss);

			output($g_arr_boss[$g_str_boss]['inc'].' existiert nicht in '.BOSS_PATH. ' oder '.
			$g_arr_boss[$g_str_boss]['name'].' existiert nicht in der Library Datei oder '.
			$_GET['op'].' ist nicht in '.$g_str_base_file.' definiert');
			addnav('Zurück',$g_str_base_file);
			page_footer();
			break;
		}
}

if ($battle)
{
	include('battle.php');
	if ($victory)
	{
		boss_do_victory();
		if($flawless)
		{
			boss_do_flawless_victory();
		}
		boss_write_victory_news();
		boss_clean_up();
	}
	elseif ($defeat)
	{
		boss_do_defeat();
		boss_write_defeat_news();
		boss_clean_up();
	}
	else
	{
		fightnav(true,false);
	}
}
page_footer();
?>