<?php
/**
 * Die große alte Eiche. Hier sitzen einige komische Kauze aus Atrahor
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor.de
 */

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();
page_header('Die alte Eiche');

$str_filename = basename(__FILE__);
$str_out = '';

switch ($_GET['op'])
{
	default:
	case '':
		{
			addnav('Wege');
			addnav('W?Wald','forest.php');
			addnav('d?Stadtzentrum','village.php');
			addnav('o?Wohnviertel','houses.php');
			addnav('M?Marktplatz','market.php');			
			if (($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ENTER)) || ($Char->expedition>0))
			{
				addnav('Expedition','expedition.php');
			}
			else
			{
				addnav('Expedition','expedition_guest.php');
			}
			
			if (($Char->dragonkills>0))
			{
				addnav('G?Gildenviertel','dg_main.php');
			}
			
			$str_out .= get_title('`SD`Ti`;e `Yg`Gr`2o`Jß`2e `GE`Yi`;c`Th`Se');
			$str_out .= '`JI`2m `GZ`pe`Yn`;t`Tr`Sum des Stadtplatzes steht eine mächtige Eiche. So hoch, dass sie den Himmel zu berühren scheint, so breit, dass es 20 Männer braucht um sie zu umfassen und so tief, dass ihre Wurzeln tiefer in die Erde vordringen als die Zwerge es sich trauen. Im Sommer spendet sie Schatten und ihre Blätter rascheln beruhigend im Wind, im Herbst ist sie Spielplatz vieler Kinder die auf Ihren Ästen herumtollen und selbst im Winter schützt sie mit ihrem breiten Geäst die Bänke, die um sie herum aufgebaut wurden und auf denen sich die alten und weisen Herrschaften '.getsetting('townname','Atrahor').'s niedergelassen haben um zu diskutieren oder Jung und Alt mit ihren Geschichten zu beglücken. Du saugst die gemütliche Stadtatmosphäre in dich auf.';
			
			$str_out .= Weather::get_weather_text('Große Eiche');			
			
			//Ausgabe wegen viewcommentary schon hier
			output($str_out);
			$str_out = '';
			
			viewcommentary('greatoaktree');
			
			addnav('Die große Eiche');
			addnav('Runenmeister','runemaster.php?op=master');
            addnav('Der Questinator','bathiderquestinator.php');

			break;
		}
}
output($str_out);

page_footer();
?>