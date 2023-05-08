<?php
/**
 * Eine Wrapperdatei die dazu dient Kämpfe auszutragen, so dass diese nicht mehr in den eigentlichen Contentdateien umständlich eingepflegt werden müssen.
 * Der badguy Array muss hierzu lediglich um zwei Schlüssel erweitert werden
 * [linkwin] = Link zu einer Datei die die Gewinnbedingungen und Texte dann ausgibt.
 * [linkdefeat] = Link zu einer Datei die die Verlustbedingungen und Texte dann ausgibt.
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 */

require_once('common.php');

$str_filename = basename(__FILE__);

page_header('Ein Kampf');

switch ($_GET['op'])
{
	default:
	case 'battle':
	case 'fight':
	case 'run':
		{
			$battle = true;
			$fight = true;
			$str_out = '';
			include_once ('battle.php');
		
			if ($victory == true)
			{
				clearnav();
				addnav('Gewonnen!',$badguy['linkwin']);
				Atrahor::$Session['battlewrapper_badguy'] = $badguy;
			}
			elseif ($defeat == true)
			{
				addnav('Verloren',$badguy['linkdefeat']);
				Atrahor::$Session['battlewrapper_badguy'] = $badguy;
			}
			else
			{
				if ($fight == true)
				{
					unset(Atrahor::$Session['battlewrapper_badguy']);
					fightnav(true,false);
				}
			}
			break;
		}
}

output($str_out);

page_footer();
?>