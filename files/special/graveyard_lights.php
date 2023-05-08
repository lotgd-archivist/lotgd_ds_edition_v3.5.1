<?php
/**
 * @desc Ein kleines Licht auf dem Friedhof
 * @author Jake
 * @copyright Atrahor, DS V2.5
 */


page_header('Ein kleines Licht');
$session['user']['specialinc'] = basename(__FILE__);
$str_file = basename($_SERVER['SCRIPT_FILENAME']);

$str_output = '';

$str_output .= '`)`c`bEin kleines Licht`b`c`n`n';

switch ($_GET['sop'])
{
	case 'touch':
		{
			$session['user']['specialinc'] = '';
			switch (e_rand(0,1))
			{
				case 0:
					{
						$str_output .= 'Ein brennender Schmerz fährt dir durch deine Hand! Das Licht ist verschwunden noch bevor du überhaupt weisst was es war. Du verlierst 10% deiner Seelenkraft!';
						$session['user']['soulpoints'] *= 0.9;
					}
					break;
				case 1:
					{
						$str_output .= 'Eine sanfte Wärme geht von dem glühenden Licht aus, doch ist es so hell, dass man es nicht direkt anschauen kann. Die Wärme breitet sich in deinem Geiste aus und du fühlst dich gestärkt. Deine Seele fühlt sich gestärkt.';
						//Berechnung aus der graveyard.php
						$session['user']['soulpoints'] = $session['user']['level'] * 5 + 50;
					}
					break;
			}

		}
		break;
	case 'fight':
		{
			$session['user']['specialinc'] = '';
			switch (e_rand(0,1))
			{
				case 0:
					{
						$str_output .= 'Du schlägst  mit deiner Waffe auf das Licht! Ein heller roter Blitz blendet dich und dir werden 25 Gefallen geraubt. Ramius verzeiht den Frevel an seinen Irrlichtern nicht!';
						$session['user']['deathpower'] -= 25;
						if($session['user']['deathpower']<0)
						{
							$session['user']['deathpower'] = 0;
						}
					}
					break;
				case 1:
					{
						$str_output .= 'Du zerschlägst das Licht. Ein Knall ertönt und als du wieder bei Sinnen bist merkst du, dass ein kleiner Zettel vor dir liegt, worauf 20 Gefallen von Ramius niedergeschrieben sind. Freudig nimmst du den Zettel und steckst ihn ein.';
						$session['user']['deathpower'] += 20;
					}
					break;
			}

		}
		break;
	case 'leave':
		{
			$session['user']['specialinc'] = '';
			$str_output .= 'Du drehst dem Licht den Rücken zu und gehst ein paar Schritte, als du dich nochmal umsiehst, ist das Licht verschwunden.';
		}
		break;
	default:
		{
			$str_output .= 'Als du über den Friedhof läufst und um dich schaust, erblickst du ein seltsames Leuchten.
			Neugierig gehst darauf zu, woraufhin das kleine, rote Licht anfängt sich zu bewegen.
			Es zieht ein paar Kreise um deinen Körper und kommt dann unvermittelt etwa eine Armeslänge entfernt von dir zum Stehen.`n`n
			Was willst Du nun tun?';
			addnav('B?Berühre das Licht',$str_file.'?sop=touch');
			addnav('a?Greife das Licht an',$str_file.'?sop=fight');
			addnav('z?Den Rücken zukehren',$str_file.'?sop=leave');
		}
		break;
}
output($str_output);
?>