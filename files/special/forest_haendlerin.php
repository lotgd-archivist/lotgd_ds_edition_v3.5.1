<?php
/**
 * @desc Triff und hilf der verzweifelten Händlerin
 * Gewidmet Agriel (Jasmin Hempel)
 * Überarbeitet von Dragonslayer für Atrahor.de
 * @author Innos (Martin Bitzer)
 * @copyright Innos (Martin Bitzer) http://www.talamar.de/logd
 */
if (! isset($session))
{
	exit();
}
page_header("Die verzweifelte Händlerin");
$session['user']['specialinc'] = basename(__FILE__);

$str_output = '';

switch ($_GET['op'])
{
	case "":
		{
			$str_output .= "`tDu wanderst durch den Wald und summst vor dich hin. Plötzlich siehst du eine junge hübsche Frau auf einem umgefallenen Baum sitzen.`n
			Sie weint bitterlich und du fragst dich was los ist.`n
			Was willst du tun?";
			addnav("Hingehen", "forest.php?op=go");
			addnav("Sie in Ruhe lassen", "forest.php?op=leave");
			break;
		}
	case "go":
		{
			$str_output .= "`yWas ist mit Euch?`t, fragst du sie höflich.`n`yIch bin Händlerin Sheila und war auf dem Weg nach " . getsetting('townname', 'Atrahor') . ". Doch kam der Grossbauer Harkon zu mir und nahm sich all meine Waren, weil ich seinen Wald widerrechtlich betreten habe. Nun habe ich nichts mehr.`n`t
			Du wunderst dich im ersten Moment zwar, da du dachtest, dass der Wald dem Reich gehört und nicht einem Grossbauern. Doch dann wird dir klar, dass der Grossbauer nichts anderes als ein mieser Scharlatan sein kann.`n`n
			Willst du der Händlerin helfen?";
			addnav("Ja", "forest.php?op=ja");
			addnav("Nein", "forest.php?op=nein");
			break;
		}
	case "leave":
		{
			$str_output .= "`tDu denkst dir deinen Teil und gehst weiter deines Weges. Jedoch plagt dich dein schlechtes Gewissen und du überlegst lang, ob dein Handeln richtig war.";
			$session['user']['charm'] = max(0, $session['user']['charm'] - 2);
			$session['user']['specialinc'] = "";
			break;
		}
	//Entscheidung helfen oder nicht
	case "nein":
		{
			$str_output .= "`tDu zuckst kurz mit den Schultern und gehst einfach wieder zurück in den Wald. Soll sie doch selbst sehen wie sie zurecht kommt.";
			$session['user']['charm'] = max(0, $session['user']['charm'] - 2);
			$session['user']['reputation'] = max(- 50, $session['user']['reputation'] - 2);
			$session['user']['specialinc'] = "";
			break;
		}
	case "ja":
		{
			$str_output .= "`tNatürlich kannst du dieses Unrecht nicht ungesühnt lassen und tröstet die junge Händlerin. Dann ziehst du los um den Grossbauern zu stellen`n`n";
			switch (e_rand(1, 12))
			{
				case '1':
					$str_output .= "Bereits nach kurzer Zeit findest Du den vermeintlichen Grossbauern auf einem Baumstamm sitzend und grinsend sein Gold zählen. Heroisch baust du dich vor ihm auf, um ihm die Leviten zu lesen. Das sich jedoch auch hinter dir jemand aufbaut bemerkst du zu spät. Wer es jedoch sofort bemerkt ist Ramius...";
					killplayer();
					addnews($session['user']['name'] . " wurde vom Söldner des Grossbauern Harkon hinterrücks ermordet.");
					break;
				case '2':
				case '3':
				case '4':
				case '5':
					$str_output .= "Es kostet dich viel Zeit bis du endlich den vermeintlichen Großbauern ausfündig machst. Als dieser dich dann jedoch endlich erblickt und einen Blick auf deine Waffe wirft, wird er plötzlich ziemlich umgänglich und händigt dir umgehend das Hab und Gut der Händlerin aus. Na, das war doch erstaunlich einfach.";
					$session['user']['turns'] = max(0, $session['user']['turns'] - 2);
					addnav("Zurück zur Händlerin", "forest.php?op=back");
					break;
				case '6':
				case '7':
				case '8':
				case '9':
					$str_output .= "Als du den vermeintlichen Bauern endlich stellst, ist dieser bei weitem nicht so kooperativ wie du es dir gewünscht hättest, denn er zückt seine Waffe...";
					output($str_output);
					$str_output = '';
					
					$crexp = e_rand(200, 500);
					$badguy = array(
					"creaturename" => "Grossbauer Harkon" , 
					"creaturelevel" => 0 , 
					"creatureweapon" => "Goldene Sichel" , 
					"creatureattack" => 1 , 
					"creaturedefense" => 1 , 
					"creaturehealth" => 2 , 
					"creaturegold" => 0 , 
					"creatureexp" => $crexp , 
					"diddamage" => 0);
					
					//Pimp my Bauer
					$userlevel = $session['user']['level'];
					$userattack = e_rand($session['user']['attack'] - 1, $session['user']['attack'] + 2);
					$userhealth = $session['user']['hitpoints'];
					$userdefense = e_rand($session['user']['defence'] - 1, $session['user']['defence'] + 2);
					$badguy['creaturelevel'] += $userlevel;
					$badguy['creatureattack'] += $userattack;
					$badguy['creaturehealth'] = $userhealth;
					$badguy['creaturedefense'] += $userdefense;
					$badguy['creaturegold'] = 0;
					$session['user']['badguy'] = createstring($badguy);
					
					$_GET['op'] = "fight";
					$battle = true;
					break;
				case '10':
				case '11':
				case '12':
					$str_output .= "Zwar findest du den Bauern und kannst ihn stellen, jedoch zeigt er sich als geschickter Verhandlungspartner. `yPass auf`t meint er zu dir. `yIch habe die Waren bereits verkauft und hänge an meinem Leben. Ich bin mir sicher, dass wir uns einig werden könnten.`t Er bietet dir 500 Goldstücke an, wenn Du ihm sein kleines 'Geschäft' vergessen lässt. Bist du damit einverstanden?";
					addnav("Ich nehme dein Gold", "forest.php?op=trade&act=gold");
					addnav("Ich bin nicht bestechlich", "forest.php?op=trade&act=nogold");
					break;
			}
			break;
		}
	case 'trade':
		{
			if ($_GET['act'] == 'gold')
			{
				$str_output .= '`ySiehst du, ich wusste doch wir werden uns einig. `tMit diesen Worten händigt er dir 500 Goldstücke aus und verschwindet dann ganz rasch im Unterholz. Dabei schaut er sich immer mal wieder um, um sicher zu gehen, dass du es dir nicht anders überlegst.';
				$session['user']['specialinc'] = "";
			}
			else
			{
				$str_output .= '`tDu schüttelst betrübt den Kopf und beutelst den Händler ein wenig, aber bestimmt und siehe da...mit der bitteren Realtität konfrontiert wird der Bauer plötzlich ganz umgänglich und reicht dir mit einem Male einen Beutel zu, der das Hab und Gut der Händlerin enthält. Leider nicht alles, einiges davon hat der Betrüger wahrscheinlich schon an einen Hehler verkauft.';
				addnav('Zurück zur Händlerin', 'forest.php?op=back2');
			}
			break;
		}
	// Zurück zur Händlerin
	case "back":
		{
			$str_output .= "`tNach einer Weile kommst du zurück und übergibst der Händlerin all ihre Sachen wieder. `yIch danke euch! Lasst mich euch entlohnen. Was verlangt ihr für eure Tat?`t fragt sie dich schüchtern?";
			addnav("1000 Gold", "forest.php?op=gold");
			addnav("Nichts", "forest.php?op=nichts");
			break;
		}
	case "back2":
		{
			$str_output .= "`t Nach einer Weile kommst du zurück und gibst der Händlerin den Rest ihrer Sachen wieder. Zwar ist sie enttäuscht weil sie nicht alles wieder hat, fragt aber dennoch schüchtern wie sie dich entlohnen könnte. `yWas verlangt ihr nun für eure Tat als Belohnung?";
			addnav("500 Gold", "forest.php?op=gold2");
			addnav("Nichts", "forest.php?op=nichts");
			break;
		}
	// Entscheidung Belohnung oder nicht
	case "gold":
		{
			$str_output .= "`tDu verlangst 1000 Gold für deine Tat. Die Händlerin gibt sie dir natürlich.";
			$session['user']['gold'] += 1000;
			$session['user']['reputation'] = max(0, $session['user']['reputation'] - 5);
			$session['user']['specialinc'] = "";
			break;
		}
	case "gold2":
		{
			$str_output .= "`tDu verlangst 500 Gold für deine Tat. Die Händlerin gibt sie dir natürlich.";
			$session['user']['gold'] += 500;
			$session['user']['reputation'] = max(0, $session['user']['reputation'] - 5);
			$session['user']['specialinc'] = "";
			break;
		}
	case "nichts":
		{
			$str_output .= "`tDu winkst ab und erklärst, dass das doch selbstverständlich für dich gewesen sei. Die Händlerin fällt dir glücklich um den Hals und küsst dich innig.`n
			Für deine noble Tat steigt dein Ansehen in der Bevölkerung.";
			$session['user']['reputation'] += 5;
			$session['user']['specialinc'] = '';
			break;
		}
	// der Kampf
	case "fight":
		{
			$battle = true;
			break;
		}
}
if ($battle)
{
	include ("battle.php");
	if ($victory)
	{
		$expwin = $badguy['creatureexp'];
		$session['user']['experience'] += $expwin;
		$str_output .= "`tDu hast `^" . $badguy['creaturename'] . "`t geschlagen.`n `tDu bekommst $expwin Erfahrungspunkte.";
		addnav("Zurück zur Händlerin", "forest.php?op=back");
		$badguy = array();
		$session['user']['badguy'] = "";
	}
	elseif ($defeat)
	{
		addnews($session['user']['name'] . "starb durch den Grossbauern Harkon");
		$str_output .= "Du liegst auf dem Boden und der Grossbauer rammt sein goldenen Sichel in deinen Hals";
		$str_output .= "`n`yDu bist tot.`n";
		killplayer();
	}
	else
	{
		fightnav(true, false);
	}
}
output($str_output);
?>