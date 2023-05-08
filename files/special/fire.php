<?php

/**
 * Dumme Gedanken auf einem doofen Stadtfest.
 *
 * @author laulajatar für atrahor.de
 */

if (!isset($session))
{
	exit();
}

$session['user']['specialinc']='fire.php';

$str_output = '';

switch ($_GET['op'])
{
	// Gehen ohne was zu machen
	case 'leave':
		$str_output .= '`tDu beschließt, den Rauch Rauch sein zu lassen und setzt deinen Weg fort.`n`n';
		$session['user']['specialinc']='';
		//addnav('Weiter','forest.php');
		break;

	case 'leave2':
		$str_output .= '`tDu kehrst dem Feuer den Rücken zu und lenkst deine Schritte wieder in den Wald zurück. Der nächste Regen wird es schon löschen, ganz sicher.`n`n';
		$session['user']['specialinc']='';
		//addnav('Weiter','forest.php');
		break;

	case 'leave3':
		$str_output .= '`tDir sind die bunten Gestalten nicht geheuer und du setzt deinen Weg lieber in entgegengesetzter Richtung fort.`n`n';
		$session['user']['specialinc']='';
		//addnav('Weiter','forest.php');
		break;

		// Feuer löschen
	case 'loeschen':
		$str_output .= '`tDa du nicht mit der Gewissheit leben willst, für einen eventuellen Waldbrand verantwortlich zu sein, schaufelst du mit beiden Händen Sand auf das Feuer, bis auch nicht mehr der kleinste Funken zu entdecken ist.`n `&Die Mühe kostet dich jedoch einen Waldkampf!`n`n';
		$session['user']['turns']--;
		$gem=e_rand(1,5);
		switch ($gem)
		{
			case 1:
			case 2:
			case 3:
			case 4:
				$str_output .= 'Mit dem guten Gefühl, das Richtige getan zu haben, kehrst du in den Wald zurück.`n`n';
				$session['user']['specialinc']='';
				//addnav('Weiter','forest.php');
				break;

			case 5:
				$str_output .= 'Bei der Arbeit hast du sogar `#einen Edelstein `tin der Erde gefunden, den du natürlich einsteckst. Fröhlich pfeifend gehst du in den Wald zurück.`n`n';
				$session['user']['gems']++;
				$session['user']['specialinc']='';
				//addnav('Weiter','forest.php');
				break;
		}
		// Ende von switch gem

		break;

		// zu den Abenteurern setzen
	case 'abenteurer':
		$str_output .= '`tDu begrüßt die Abenteurer fröhlich und sie nehmen dich bereitwillig in ihren Kreis auf, drücken dir einen Schlauch Wein in die Hand und fahren dann mit ihren Erzählungen fort. Du lauschst ';
		$story=e_rand(1,5);
		switch ($story)
		{
			// Geschichten sind spannend, Gewinn von EP
			case 1:
			case 2:
			case 3:
				$expplus=round($session['user']['experience']*0.1);
				$str_output .= 'ihren spannenden Geschichten über Monster und Drachen und wie viele diese tapferen Helden schon getötet haben (oder zumindest behaupten, getötet zu haben). Dabei lernst du noch den ein oder anderen nützliches Trick und als du dich schließlich nach einer ganzen Weile verabschiedest, um weiterzuziehen, fühlst du dich allen Gefahren gewachsen.`n`&Du erhälst '.$expplus.' Erfahrungspunkte, verlierst aber drei Waldkämpfe!`n`n';
				$session['user']['experience']+=$expplus;
				$session['user']['turns']=max(0,$session['user']['turns']-3);
				$session['user']['specialinc']='';
				//addnav('Zurück in den Wald','forest.php');
				break;

				// Geschichten sind langweilig, nur Waldkampfverlust
			case 4:
			case 5:
				$str_output .= 'ihren todlangweiligen Geschichten über Verwandte und die Verwandten dieser Verwandten (und deren Angehörigen!) und musst schwer darum kämpfen, dass dein Kopf dir nicht auf die Brust sackt. Als du dich schließlich, nach Stunden wie es dir scheint, endlich losreißen kannst und unter vielen Entschuldigungen im Wald verschwindest, fühlst du dich nur noch müde.`n`&Du verlierst 5 Waldkämpfe!`n`n';
				$session['user']['turns']=max(0,$session['user']['turns']-5);
				$session['user']['specialinc']='';
				//addnav('Zurück in den Wald','forest.php');
				break;

		}
		// Ende von switch Geschichten
		break;

		// Begegnung mit den Räubern, Kampf
	case 'angriff':
		$kampf=e_rand(1,5);
		switch ($kampf)
		{
			case 1:
			case 2:
			case 3:
				$gemtext='';
				if ($session['user']['gems']>100)
				{
					$gemtext=', 2 Edelsteine';
					$session['user']['gems']-=2;
				}
				$str_output .= '`tMit gezogenem Schwert und lautem Kampfgeschrei stürzt du dich auf die Räuber, in festem Glauben an deine Stärke und deine Fähigkeiten. Erst als du das Schwert bemerkst, dass auf einmal in deinem Bauch steckt, beginnt der Glaube zu schwinden und als dir schwarz vor Augen wird und du zu Boden fällst, bist du dir sicher: Du hast dich überschätzt!`n`4Du bist tot!`n`&Du verlierst all dein Gold'.$gemtext.' und 5% deiner Erfahrung. Du kannst morgen wieder spielen!`n`n';
				addnews('`t'.$session['user']['name'].' `t fiel im Wald einer Räuberbande zum Opfer.');
				killplayer();
				break;

			case 4:
			case 5:
				$goldgewinn=e_rand(1000,3000);
				$esgewinn=e_rand(2,4);
				$str_output .= '`tMit einem lauten Kampfschrei stürzt du dich auf die Räuber und erledigst zwei von ihnen, ehe sie überhaupt wissen, wie ihnen geschieht. Nachdem sich auch noch ein dritter zu seinen Kameraden gesellt hat, suchen die übrigen ihr Heil in der Flucht und du machst dich in aller Seelenruhe daran, ihre Taschen zu durchwühlen, die sie dagelassen haben.`nDu findest `^'.$goldgewinn.' Goldstücke `tund `#'.$esgewinn.' Edelsteine`t!`n`n';
				$session['user']['specialinc']='';
				$session['user']['gold']=$session['user']['gold']+$goldgewinn;
				$session['user']['gems']=$session['user']['gems']+$esgewinn;
				// vielleicht sollt ich mir noch n addnews ausdenken ^^
				//addnav('Zurück in den Wald','forest.php');
				break;
		}
		// Ende von switch Angriff

		break;

		// Versuchen, zu fliehen
	case 'flucht':
		$fliehen=e_rand(3,3);
		switch ($fliehen)
		{
			case 1:
			case 2:
				$str_output .= '`tDu drehst dich um und schleichst ein ganzes Stück zurück, ehe du zu rennen anfängst. Nach etlichen hundert Metern traust du dich erst, wieder langsamer zu werden und zu Atem zu kommen. Wie es aussieht hast du es geschafft, verlierst jedoch durch deine Flucht `&einen Waldkampf`t.`n`n';
				$session['user']['specialinc']='';
				$session['user']['turns']--;
				//addnav('Glück gehabt','forest.php');
				break;

			case 3:
				$gemtext='';
				if ($session['user']['gems']>100)
				{
					$gemtext='und ein paar Edelsteine ';
					$gemloss=e_rand(1,3);
					$session['user']['gems']-=$gemloss;
				}
				$str_output .= '`tDu drehst dich um, um die Flucht zu ergreifen, als dich etwas am Kopf trifft und dir schwarz vor Augen wird. Anscheinend warst du doch nicht ganz so unbemerkt, wie du gedacht hast! Als du wieder zu dir kommst, stellst du fest, dass die Räuber dir die Hälfte deines Goldes '.$gemtext.'gestohlen haben. Mit höllischen Kopfschmerzen, aber immerhin am Leben, schleichst du in den Wald zurück.`n`&Du hast fast alle deine Lebenspunkte verloren!`n`n';
				$session['user']['specialinc']='';
				$session['user']['hitpoints']=1;
				$session['user']['gold']*=0.5;
				//addnav('Zurück in den Wald','forest.php');
				break;

		}
		//Ende von switch Flucht

		break;

		// Zum Feuer gehen
	case 'go':
		$what=e_rand(1,4);
		switch ($what)
		{
			// Niemand da
			case 1:
			case 2:
				$str_output .= '`tDu näherst dich der Rauchsäule, doch nichts deutet auf die Anwesenheit anderer Wesen hin. Schließlich kannst du durch einige Zweige hindurch das Feuer erspähen, das augenscheinlich verlassen ist. Was für eine Unachtsamkeit, so könnte ja der ganze Wald niederbrennen!`n Du überlegst, ob du das Feuer löschen willst, oder ob dir deine kostbare Zeit zu schade dafür ist und du lieber in den Wald zurückgehen willst.`n`n';
				addnav('Was tust du?');
				addnav('Feuer löschen','forest.php?op=loeschen');
				addnav('Zurück in den Wald','forest.php?op=leave2');
				break;

				// Abenteurer da
			case 3:
				$str_output .= '`tAls du dich der Rauchsäule näherst, kannst du leise Stimmen hören, die deutlicher werden, je näher du kommst. Schließlich entdeckst du das Feuer samt einem Haufen buntgekleideter Abenteurer, die auf dem Boden um das Feuer herumsitzen und sich Geschichten erzählen. Eigentlich sehen sie recht freundlich aus und es wäre vielleicht interessant zu hören, was sie so zu erzählen haben.`n Willst du dich zu ihnen gesellen oder ziehst du dich lieber in den Wald zurück, solange sie dich noch nicht bemerkt haben?`n`n';
				addnav('Zu ihnen gehen','forest.php?op=abenteurer');
				addnav('Zurück in den Wald','forest.php?op=leave3');
				break;

				// Räuber da
			case 4:
				$str_output .= '`tDu gehst durch den Wald auf den Rauch zu, bis dich auf einmal nur noch ein schmaler Baumstreifen von dem Lagerfeuer trennt. Um das Feuer herum sitzen auf der Lichtung gut ein halbes dutzend sehr unfreundlich aussehen Räuber. Noch scheinen sie dich nicht bemerkt zu haben, du könntest also versuchen, dich unbemerkt zurückzuziehen. Oder glaubst du etwa, du kannst es mit allen auf einmal aufnehmen um zu sehen, ob sie nicht irgendwelche Reichtümer bei sich haben?`n`n';
				addnav('Angreifen','forest.php?op=angriff');
				addnav('Flüchten','forest.php?op=flucht');
				break;

		}
		// Ende von switch what

		break;

	default:
		$str_output .= '`tAls du so nichtsahnend durch den Wald gehst, bemerkst du auf einmal nicht weit von dir eine Rauchsäule über den Baumwipfeln. Es könnte ein Lagerfeuer sein, oder eine Hütte. Du überlegst, hinzugehen und nachzusehen, wen es noch so tief in den Wald verschlagen hat. Oder du vergisst das Ganze und gehst weiter deines Weges.`n`&Was willst du tun?`n`n';
		addnav('Was willst du tun?');
		addnav('R?Zu dem Rauch gehen','forest.php?op=go');
		addnav('Weitergehen','forest.php?op=leave');

}
// Ende von grosser,ganz grosser switch Oo
output ($str_output);
?>