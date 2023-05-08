<?php
/**
 * Bossgegner Jack-O-Lantern
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3.42
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 */


/**
 * Die Nav darf nur angezeigt werden wenn wir Halloween haben
 */
function boss_check_additional_nav_preconditions()
{	
	global $Char;
	return (isBetween(25,date('j'),31) && date('n') == 10);
}

function boss_do_intro()
{
	global $g_arr_current_boss,$session,$Char,$battle,$badguy,$g_str_base_file,$battle;
    if(!isset($str_output))$str_output='';
	switch($_GET['act'])
	{
		case '':
			{
				$str_output .= get_title('`QJack-O-Lantern').'`)Über einen kleinen Fluss spannt sich eine alte überdachte Holzbrücke. Das Ufer auf beiden Seiten des Flusses wabert im Nebel und auf dem Wasser gluckert es leise und beunruhigend. In gleichmäßigen, aber viel zu großen Abständen erhellen Fackeln den Weg über den Fluss. Das schwarze Holz knarrt unter deinen Sohlen als du die Brücke betrittst und irgendwo kräht ein Rabe, den du in seinem Festmahl gestört hast. Mit jedem Schritt entfernst du dich vom einen Ufer, scheinst dem anderen jedoch keinen Schritt näher zu kommen. Schatten huschen die Pfosten entlang, erschaffen von den Flammen der Fackeln und deiner eigenen Vorstellungskraft. Nach kurzer Zeit siehst du in einigen Schritt Entfernung etwas auf dem Boden liegen. Es sieht aus wie ein großer `QKürbis`). Ausgehöhlt und mit einer Kerze darin. Die Grimasse die er schneidet ist bedrückend. Mit einem Male hörst du eine Stimme: `Q"Oh, armes Menschlein. Hast du dich verirrt?"`) rauh und kratzig, mit einem Nachhall in deinem Kopf wie du ihn sonst nur von Ramius kennst und doch anders zugleich...`Q"Hast du eigentlich eine Ahnung wie spät es ist? Tick-Tack-Tick-Tack...Hast du eigentlich eine Ahnung wo du bist? Tick-Tack-Tick-Tack. Hast du eigentlich eine AHNUNG WANN JETZT GERADE IST `bTICK-TACK-TICK-TACK?!?`b."`) Du schaust dich um. Der Horror ist in dein Gesicht geschrieben, ein kalter Schauer läuft deinen Rücken herunter.';
				
				addnav('Waffe ziehen',$g_str_base_file.'&act=intro');
				addnav('Schnell weg von hier','forest.php');
				output($str_output);
				break;
			}
		case 'intro':
			{	
				$str_output .= get_title('`QJack-O-Lantern').'				
				`Q"Oh Menschlein, das hättest du nicht tun sollen. Es ist Samhain! Die Zeit des Jahres in der ich die Tore in die Totenwelt aufstosse und sie offen halte. Alle meine Freunde sind hier. Willst du sie kennenlernen, ja möchtest du? Es sind hunderte, nein tausende, so viele verlorene Seelen die ich über die Jahrhunderte bereits gefangen habe. Ich bin mir sicher du kennst einige von Ihnen? Vielleicht habe ich ja auch die Seele deiner Großeltern oder sogar deiner Eltern...willst du ihnen begegnen? Willst du dass sie über dich herfallen und dir das Fleisch von den Knochen schaben?!? Naaaaainn, ich habe eine viel bessere Idee! Ich werde mich deiner persönlich annehmen. Hihi, das wird ein Spass!"`)`n`n
				`c'.print_frame('<img src="./images/jack-o-lantern.jpg" />','`QJack ``O Lantern',0,true).'`c`n`n
				Vom Dach der Brücke aus hörst du Schritte auf dem Holz klacken. Und mit einer behenden Leichtigkeit schwingt sich ein langes, spindeldürres Wesen die Balken hinunter, um einige Schritt vor dir stehen zu bleiben. Mit seinen langen, messerscharfen Fingern greift es nach dem auf dem Boden liegenden `QKürbis`) und setzt sich diesen auf die Schultern.`n`n				
				`b`$Wappne dich für den Kampf`0`b';
				output($str_output);
				$badguy = boss_get_badguy_array($g_arr_current_boss);

				$session['user']['badguy']=utf8_serialize($badguy);
				$battle=true;
				$session['user']['seendragon']=1;
				break;
			}
	}
}

function boss_do_autochallenge()
{
	/**
	 * Kein Autochallenge durch Jack O 
	 */
	return true;
}

function boss_do_epilogue()
{
	global $g_str_base_file, $g_arr_current_boss, $session, $Char;

	music_set('drachenkill',0);
    if(!isset($str_output))$str_output='';
	switch ($_GET['act'])
	{
		case '':
			{
				$str_output = get_title('`QDer Tod des Jack ``O!');
				$str_output .= '`)Als deine Waffe ein letztes Mal auf das unheilige Wesen hernieder fährt, zerplatzt dessen `QKürbiskopf`) und du hast das Gefühl als ob sogleich eine große Last von deinem Geist abfallen würde. Mit einem Male ist die Brücke auf der du stehst wieder was sie schon immer war, der Nebel und das unheimliche Gemurmel des Wassers weichen den Geräuschen eines normalen herbstlichen Tages - düster, aber keineswegs bedrohlich.`n
				`7Doch als Du dich gerade zum gehen wenden willst, spürst du mit einem Male eine Präsenz, die dich inne halten lässt.`n
				`("Hmhmmm, '.$Char->name.'`( du bereitest mir Sorgen".`7 Ramius, der Gott der Toten, der alte Seelentrenner persönlich, steht hier neben den Überresten des Kürbiskopfes und schüttelt seinen unter einer kapuze verborgenen Kopf `("Jack ``O war ein böser Mann. Aber auch sehr gerissen. In den Himmel wird er nicht auffahren und in meine Hölle lasse ich ihn erst recht nicht. Meine Strafe war sein ewiges Wandeln auf Erden.... und diese werde ich nicht aufheben!"`7 `n`n
				Mit offenem Mund musst du erkennen, wie das noch wenige Sekunden zuvor in sich zusammengefallene Gebilde sich wieder zusammenzusetzen beginnt. Langsam bauen sich die spindeldürren Arme, Beine und Klingenhände auf. Der Rumpf richtet sich auf steht kopflos vor dir. Zuletzt beginnt sich der `QKürbis`7 wieder zu sammeln und zusammenzusetzen und mit einem leisen Puff entfacht sich zuguterletzt die Kerze im Innern des Unheiligen Gebildes.
				`("Du verstehst gewiss, dass ich keinem lebenden Wesen gestatten darf zu erfahren was sich hier zugetragen hat, nicht wahr?" `sDass dies leider keine Frage war, merkst du recht schnell. Mit lautem Krachen bricht unter dir eine der Brückenplanken entzwei und du fällst in die Fluten, die dich mit tödlicher Macht hinab ziehen, wo du mit Sicherheit den Tod erleiden wirst. `&Als du jedoch nach einigen Stunden am Flussufer wieder zu dir kommst, kannst du dich an nichts erinnern, was geschehen ist.';
				addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				break;
			}
		case 'wakeup':
			{
				$str_output .= get_title('Erwache!');
				$str_output .= 'Du erwachst klitschnass am Rande eines kleinen Bachs, umgeben von Bäumen. Du hast keine ahnung wie du hierher gekommen bis noch was du hier sollst. In der Nähe hörst du die Geräusche einer Stadt.
				Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von gefährlichen Kreaturen, die die Gegend heimsuchen.
				Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages diesen abscheulichen Wesen stellst.
				`n`n`^Du bist von nun an bekannt als `&'.$session['user']['name'].'`^!!
				`n`n`&Weil du '.$session['user']['dragonkills'].' Heldentaten vollbracht hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du dir verdient oder erkauft hast.
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über Jack ``O!`n`n';

				addnav('Es ist ein neuer Tag','news.php');

				// Knappe laden und steigern
				$rowk = get_disciple();
				if ($rowk['state']>0)
				{
					$str_output .= disciple_levelup($rowk);
					$session['bufflist'] = array();
				}
				break;
			}
	}
	output($str_output);
}

function boss_do_run()
{
	global $battle;
	$battle = true;
	output('So sehr du auch gehetzt um dich blickst, du vermagst den Rückweg nicht mehr auszumachen. Dir bleibt nichts anderes übrig, als es mit Jack-O-Lantern aufzunehmen.');
}

function boss_do_fight()
{
	global $battle;
	$battle = true;
}

function boss_do_victory()
{
	global $g_str_base_file,$badguy,$flawless,$session,$Char;

	boss_calc_victory_bonus();
	

	music_set('drachenkill',0);

	$flawless = 0;
	if ($badguy['diddamage'] != 1)
	{
		$flawless = 1;
	}
	addnews('`#'.$session['user']['login'].'`# hat sich den Titel `&'.$session['user']['title'].'`# für die `^'.$session['user']['dragonkills'].'`#te erfolgreiche Heldentat verdient!');
//Dieser Text ist noch fertig anzupassen!
	headoutput(get_title('`@Sieg!').'`&Der unheilige Leib des Jack ``O fällt in sich zusammen und zurück bleibt nur ein alter, verschrumpelter `QKürbiskopf`& aus dem du einige Kürbissamen entnehmen kannst.`n`n<hr>`n');
	item_add($Char->acctid,'pumpkin_seed');
	addnav('Weiter',$g_str_base_file.'&op=epilogue&flawless='.$flawless);
}

function boss_do_flawless_victory()
{
	boss_calc_flawless_victory_bonus();
}

function boss_do_defeat()
{
	global $g_arr_current_boss;
	headoutput(get_title('Niederlage').$g_arr_current_boss['name'].'`& hat sich deiner Seele bemächtigt! Ramius empfängt dich, beinahe hämisch grinsend.`n
			`4Du hast dein ganzes Gold verloren!`n
			Du kannst morgen wieder kämpfen.`0
	`n`n<hr>`n');

	boss_calc_defeat();

	addnav('Tägliche News','news.php');

}

function boss_get_victory_news_text()
{
	global $session;

	$str_news = '`&'.$session['user']['name'].'`& hat den `QKürbiskönig `&besiegt!';

	return $str_news;
}

function boss_get_defeat_news_text()
{
	global $session;

	$str_news = '`%'.$session['user']['name'].'`5 überließ dem `QKürbiskönig `5seine Seele.';

	return $str_news;
}

?>