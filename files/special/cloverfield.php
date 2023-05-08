<?php

if (!isset($session)) exit();

$session['user']['specialinc'] = 'cloverfield.php';
$done = false;

switch($_GET['op'])
{
	case "leave" :
		$str_out = "Du beschließt, dich doch lieber wieder auf den Weg zu machen. Irgendwo wartet schließlich ein harmloses, vom Aussterben bedrohtes Monster auf dich und deine Waffe.";
		$done = true;
		break;
	case "gather" :
		$done = true;
		$session['user']['turns']--;
		$chance = e_rand(1,10);
		$str_out = "Natürlich ist es sicher, du sitzt hier immerhin schon eine ganze Weile.`n";
		$str_out .= "Du gehst los und suchst nach einem Kleeblatt mit vier Blättern, schließlich muss sich auf so einer großen Wiese ja mindestens eines befinden, oder etwa nicht ?`n`n";
		switch($chance)
		{
			case 10 :
			case 9 :
				$str_out .= "Nach einer Weile findest du tatsächlich eines mit mehr als drei Blättern. Verwirrt starrst du es eine Weile lang an, denn es besitzt noch ein paar mehr als die erwarteten vier. Naja, sicher die Urstrahlung. Du pflückst es und betrachtest dein fröhliches Wiesenhüpfen vorerst als beendet. Vorerst.";
				item_add($session['user']['acctid'],"cloversix");
				break;
			case 8 :
			case 7 :
			case 6 :
				$str_out .= "Nach einer Weile findest du tatsächlich eines und pflückst es natürlich sofort. Die Suche hat dich hungrig gemacht, so ein kleines Picknick, ja, das wäre jetzt was Feines...";
				item_add($session['user']['acctid'],"cloverfour");
				break;
			case 5 :
			case 4 :
				$str_out .= "Aber auch nach langer Suche findest du kein Kleeblatt, das mehr als drei Blätter hat. Einen Moment lang überlegst du dir, ein Blatt von einem abzureißen und an einem anderen zu befestigen, aber das würde vermutlich nichts bringen.`n";
				$str_out .= "Enttäuscht ziehst du von dannen.";
				break;
			case 3 :
			case 2 :
				$str_out .= "Aber auch nach langer Suche findest du kein Kleeblatt, das mehr als drei Blätter hat. Diese Zeitverschwendung macht dich ein wenig ärgerlich, und so trittst du nach einer unschuldigen Blume.`n";
				$str_out .= "Der Ärger weicht ziemlichem Erstaunen, als diese unschuldige Pflanze nach deinem Bein schnappt. Geschickt weichst du dem Biss der Pflanze aus, hast aber nicht bemerkt, dass sich von hinten eine hinterhältige Schlammpfütze angeschlichen hat und dir nun zeigt, dass der Horizont garantiert zum Himmel gehört, indem sie deine Blickrichtung geschickt verändert.`n";
				$str_out .= "Nach einer Weile richtest du dich wieder auf und reibst dein schmerzendes Gesäß. Zum Glück hat niemand diese Vorstellung gesehen. Hoffst du jedenfalls. Du schämst dich ordentlich und fliehst Richtung Wald.";
				$session['user']['charm']--;
				break;
			case 1 :
				$str_out .= "Du beginnst plötzlich zu schweben und entfernst dich immer weiter vom Boden. Einen Moment lang rätselst du, was hier vor sich geht, bevor du nach oben blickst.`nSchockiert fasst du deinen ersten Gedanken : \"Was ist DAS ?\"`n`n";
				$str_out .= "Nach genauerem hinsehen erkennst du, dass dich irgendwelche kleinen, grauen Figuren durch Glasscheiben am Rande dieser seltsamen Apparatur betrachten. Ab hier verschwimmt Alles vor deinen Augen.`n`n`n";
				$str_out .= "Plötzlich fährst du erschrocken hoch und siehst dich zitternd um. Du sitzt noch immer auf der Wiese, und nichts hat sich verändert. Du stehst auf und machst dich auf den Weg zurück in den Wald.`n`n";
				$str_out .= "Aber obwohl du weißt, dass das nur ein seltsamer Tagtraum war, könntest du trotzdem schwören, dass sich dein Unterleib ein wenig unangenehm anfühlt. Vermutlich bist du auf einer Distel gesessen.`n... ja... ja, das muss es sein...";
				$session['suer']['turns']--;
		}
		break;
	default :
		$str_out = "Du betrittst eine saftige, `2grüne`0 Wiese, die zum Sitzen einlädt. Du lässt dich nieder und erholst dich eine Weile, bis dir ein kleines Stück von dir entfernt ein Kleeblatt auffältt. Und noch eins. Und noch ein paar. Wo kommen die denn plötzlich alle her ?`nIrgendwie gerätst du plötzlich in Sammel-Laune, aber... ob das hier, auf einer dir völlig unbekannten Wiese, auch sicher ist ?";
		addnav("Sammeln","forest.php?op=gather");
		addnav("l?Bloß nicht","forest.php?op=leave");
}

if ($done) $session['user']['specialinc'] = "";

output($str_out);

?>