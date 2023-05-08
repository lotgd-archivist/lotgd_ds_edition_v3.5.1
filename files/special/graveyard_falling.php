<?php
/**
 * @desc Alles Gute kommt von oben
 * @author Gloin
 * @copyright Atrahor, DS V2.5
 */


page_header('Alles Gute kommt von oben');
$session['user']['specialinc'] = basename(__FILE__);
$str_file = basename($_SERVER['SCRIPT_FILENAME']);

$str_output = '';

$str_output .= get_title('`)Alles Gute kommt von oben.');

switch ($_GET['sop'])
{
	case 'help':
		{
			$session['user']['specialinc'] = '';
			$str_output .= 'Du hilfst der armen Seele auf die Beine und bleibst bei ihr bis es ihr wieder einigermaßen gut geht.`n';
			switch (e_rand(0,1))
			{
				case 0:
					{
						$str_output .= 'Dies kostet dich zwar eine Runde aber du fühlst dich gut. Die Toten müssen schließlich zusammenhalten!';
						$session['user']['gravefights']--;
						if($session['user']['gravefights']<0)
						{
							$session['user']['gravefights']=0;
						}
					}
					break;
				case 1:
					{
						$str_output .= 'Die Seele beginnt laut zu fluchen und dich fürchterlich zu beleidigen.
						Du fühlst dich zwar schlecht aber du hast nun wirklich Lust auf einen Kampf';
						$session['user']['gravefights']++;
					}
					break;
			}
		}
		break;
	case 'loot':
		{
			$session['user']['specialinc'] = '';
			$str_output .= 'Du nutzt den günstigen Moment aus und durchsucht dein Gegenüber!`n`n';
			switch (e_rand(0,1))
			{
				case 0:
					{
						$str_output .= 'Dabei findest du 3 Gold und 1 Edelstein. Jetzt weißt Du auch warum diese arme Seele hier so unorthodox ankam...sie hat den Fährmann geprellt!';
						$session['user']['gold']+=3;
						$session['user']['gems']+=1;
					}
					break;
				case 1:
					{
						$str_output .= 'Gerade als du versuchst die Habseligkeiten der Seele zu plündern wacht diese auf. Noch ehe du reagieren kannst
						verpasst sie dir eine sodass du rücklings umfällst und selbst einige Zeit brauchst um wieder aufzustehen zu können';
						$session['user']['gravefights']-=2;
						if($session['user']['gravefights']<0)
						{
							$session['user']['gravefights']=0;
						}
					}
					break;
			}
		}
		break;
	case 'leave':
		{
			$session['user']['specialinc'] = '';
			$str_output .= 'Du drehst dem Häufchen Elend den Rücken zu und gehst ein paar Schritte, als du dich nochmal umsiehst, ist sie auch schon verschwunden.';
		}
		break;
	default:
		{
			$str_output .= 'Gerade als du dich aufgemacht hast um eine Seele zu suchen, die durch ihre Qualen Ramius Gefallen wecken würde,
			hörst du einen immer lauter werdenden Schrei. Als du den Kopf hebst, siehst du gerade noch eine Gestalt, anscheinend ein Leidensgenosse im Reich der Toten,
			an dir vorbeisausen. Mit einem matten "`bFUMPH`b" schlägt er neben Dir auf und bleibt bewusstlos liegen.`n`n
			Nachdem du den kurzen Schock überwunden hast fasst du deinen Entschluss';
			addnav('H?Hilf ihm auf',$str_file.'?sop=help');
			addnav('D?Durchsuche ihn',$str_file.'?sop=loot');
			addnav('z?Den Rücken zukehren',$str_file.'?sop=leave');
		}
		break;
}
output($str_output);
?>