<?php
/*
************************************************************************
* upgraded to 1.1 .. randomised event outcomes and added a few extra's *
* upgraded to 1.2 .. added Dragon King                                 *
* upgraded to 1.3 .. added graphics                                    *
* Original version for 1.0 by Kala                                     *
* 0.9.7 Conversion by Excalibur (www.ogsi.it/logd)                     *
* You may want to add DEBUGLOG where needed                            *
* Just place the script into 'specials' folder into root dir           *
************************************************************************
Komplettüberarbeitung von Dragonslayer für Atrahor.de Dezember 2007

*/
//settings
$maxhp=e_rand(2,5);
//max HP gained
$favor=50;
//% of favors lost
$session['user']['specialinc'] = basename(__FILE__);
page_header("Drachenmutter");

$str_output = '`t';

switch($_GET['op'])
{
	case '':
	case 'search':
		{
			addnav("Die Höhle");
			$str_output .= "Du kommst an einem Efeu beranktem Felsvorsprung vorbei. Es sieht so aus als läge dahinter eine Höhle.`n
			Was gedenkst Du zu tun?`n`n"
			.create_lnk('Hebe den Efeu beiseite und betrete die Höhle.','forest.php?op=enter',true,true,'',false,'Betrete die Höhle',CREATE_LINK_LEFT_NAV_HOTKEY).'`n'
			.create_lnk('Nimm die Beine in die Hände und lauf weg.','forest.php?op=run',true,true,'',false,'Renne weg',CREATE_LINK_LEFT_NAV_HOTKEY).'`n'
			.create_lnk('Geh leise weg.','forest.php?op=back',true,true,'',false,'Schleiche von dannen',CREATE_LINK_LEFT_NAV_HOTKEY);
			
			break;
		}
	case "enter":
		{
			$str_output .= "Du ziehst den Efeu beiseite und findest dich in einer klammen Höhle wieder, deren Boden mit Knochenresten nur so übersäht ist.`n`n
			Du blickst noch einmal schaudernd über die schulter zurück in die Höhle und überlegst, was wohl besser für dich wäre. Die Höhle zu erkunden oder einfach zu gehen?";
			addnav("Die Höhle");
			addnav("Tiefer in die Höhle vordringen", "forest.php?op=continue");
			break;
		}
	case "run":
		{
			$str_output .= "Voller Angst um dein Leben rennst Du Hals über Kopf zurück in einen sichereren Teil des Waldes";
			$session['user']['specialinc'] = "";
			break;
		}
	case "back":
		{
			$str_output .= "Vorsichtig entfernst du dich wieder vom Eingang, als plötzlich unter dir der Waldboden nachgibt und du wild rudernd die Balance verlierst.";
			$str_output .= "`n`n";
			$str_output .= "Mit einem lauten Rumms schlägst du auf den Boden auf. Nachdem du Dich wieder aufgerappelt hast stellst du überglücklich fest, dass dir nichts passiert zu sein scheint. Vorsichtig schaust du dich um.`n`n";
			addnav("Die Höhle");

			$str_output .= create_lnk('Erkunde die Höhle','forest.php?op=continue',true,true,'',false,'Die Höhle erkunden',CREATE_LINK_LEFT_NAV_HOTKEY).'`n';
			$str_output .= create_lnk('Schließe die Augen und hoffe auf das Beste','forest.php?op=hope',true,true,'',false,'Hoffe auf das Beste',CREATE_LINK_LEFT_NAV_HOTKEY);
			break;
		}
	case "hope":
		{
			$str_output .= "Du schließt fest deine Augen und redest Dir ein, dass das alles nur ein schlechter Traum sein kann. Irgendjemand wird dir doch wohl helfen können...";
			$str_output .= "`n`n";
			$str_output .= "Mit einem Male spürst du einen schnell stärker werdenden Luftzug in deinem Nacken, der innerhalb kürzester Zeit tosend jede Ritze in deiner Rüstung gnadenlos ausnutzt. Elendig frierend wirst du schließlich von deinen Füßen gerissen und an die entfernte Wand gepresst. Doch genauso plötzlich wie der Wind aufkam ist er auch wieder vorbei und alles was verbleibt ist das Summen in deinen Ohren. Als Du die Augen endlich wieder öffnen kannst, findest Du Dich im Wald wieder.";
			$str_output .= "`n`n";
			$str_output .= "Du verlierst 4 Waldkämpfe.";

			$session['user']['turns'] = max(0,$session['user']['turns']-4);

			$session['user']['specialinc'] = '';
			break;
		}
	case "continue":
		{
			$str_output .= "Du siehst Dich in der Höhle um und entdeckst mehrere Tunnel, die von hier aus sternförmig tiefer in das Innere führen. Außerdem siehst du in einer Ecke das verdörrte Skelett eines Kriegers liegen, der eine Nachricht in seiner knochigen Hand zu halten scheint.`n`n
		    Vorsichtig entfernst du den Zettel aus dem Griff des Toten und liest ihn:`n`n
		    `yDer, der du hier eintrittst sei gewarnt. Diese Höhle ist die Heimat der Drachenmutter. Man sagt dass es hier viele Fallen gibt, aber die Belohnung ist groß, denn...`n`n
		    `tHier endet die Nachricht abrupt und du fragst dich was den Schreiberling wohl davon abgehalten hat seinen Satz zu vollenden. Die Blutspritzer auf dem Boden lassen es dich jedoch zumindest erahnen.`n
		    Die bleibt wohl nichts anderes übrig, als es herauszufinden. Welchen Tunnel möchtest Du nehmen?";

			addnav("Das Gewölbe");
			addnav("1. Tunnel", "forest.php?op=tunnel&op1=1");
			addnav("2. Tunnel", "forest.php?op=tunnel&op1=2");
			addnav("3. Tunnel", "forest.php?op=tunnel&op1=3");
			addnav("4. Tunnel", "forest.php?op=tunnel&op1=4");
			addnav("5. Tunnel", "forest.php?op=tunnel&op1=5");
			break;
		}
	case "tunnel":
		{
			//switch (e_rand(1, 5))
			switch ($_GET['op1'])
			{
				case 1:
					$str_output .= "Als du auf halbem Wege durch den Tunnel gelaufen bist erreichst du eine Einsturzstelle. Da du keine Möglichkeit siehst darum herum zu laufen und auch keine Lust hast Dich jetzt hier als Minenarbeiter verdient zu machen, drehst du schlichtweg um und läufst zurück in Richtung Haupthalle. Als Du dort ankommst entscheidest du dich erneut für einen der Gänge.";

					addnav("Das Gewölbe");
					addnav("Weiter", "forest.php?op=continue");
					break;
				case 2:
					$str_output .= "Nach einigen hundert Metern durch den  klammen Tunnel erspähst du in der Dunkelheit das leichte Flackern einer Lichtquelle. Als du weiter darauf zu hältst, findest du dich nach kurzer Zeit an einem Ausgang der Höhle wieder, der dich in den Wald führt.`n
	        		Zu deiner Linken glimmert etwas im Sonnenlicht. Es sind zwei `bEdelsteine`b, die du dankbar einsteckst.";
					$session['user']['gems']+=2;
					$session['user']['specialinc'] = "";
					break;
				case 3:
					$str_output .="Auf deinem Weg durch den Tunnel trittst du unverhofft auf einen rutschigen Stein und verdrehst dir schmerzhaft deinen Fuss, bis ein hässliches Knacken das Brechen eines Knochens zu erkennen gibt. Du humpelst zurück in die Haupthalle. Mit einem gebrochenen Fuss hast du wohl keine Chance jemals wieder aus dieser Höhle zu entkommen. Du wirst hier Wohl oder Übel sterben. Mit einem Zettel in der hand lässt Du dich in eine Ecke fallen. Auf dem Zettel steht geschrieben:`n
			        `yDer, der du hier eintrittst sei gewarnt. Diese Höhle ist die Heimat der Drachenmutter. Man sagt dass es hier viele Fallen gibt, aber die Belohnung ist groß, denn...`n`n
			        `tDu bist nun tot und darfst erst morgen wieder spielen.";

					killplayer();

					addnews("Der Körper von ".$session['user']['name']." wurde in einer dunklen Höhle gefunden.");
					$session['user']['specialinc'] = "";
					break;
				case 4:
					$str_output .="Du umrundest eine kleine Steinformation in deinem Weg und findest dich mit einem Male am Ufer eines kleinen unterirdischen Sees wieder. Über den kleinen Vorsprung unter dem du gerade stehst fällt ein kleiner Wasserfall und als du diesen passierst und das Wasser dein Haupt benetzt, fühlst du dich frisch und gestärkt.`n`n
			        Dem Flussverlauf folgend ist es für dich nun ein Leichtes den Ausgang zu finden.
			        ";

					$session['user']['hitpoints'] = $session['user']['maxhitpoints']*1.05;

					$session['user']['specialinc'] = "";
					break;
				case 5:
					$str_output .="Schon kurz nachdem du in den Tunnel eingebogen bist hörst du mit einem Male ein rostiges Quietschen. Als du vorsichtig vorwärts gehst erkennst du, dass es sich um eine Fee handelt, die in einem Käfig gefangen ist, der an der Tunneldecke hängt.`n
			        Sie ruft dir zu: '`yBitte hilf mir! Ein böser Troll hat mich gefangen und hier aufgehangen, damit ich den Tunnel erhelle. Wenn Du mich befreist werde ich Dich belohnen!`t'`n
			        Wirst Du sie befreien?";
					addnav("Die Fee");
					addnav("Befreie sie", "forest.php?op=free");
					addnav("Ignoriere sie", "forest.php?op=onway");
					break;
			}
			break;
		}
	case "free":
		{
			$str_output .= "Du greifst in die kleine Ausbuchtung in welcher der Käfig hängt und öffnest vorsichtig die Tür, so dass die glimmende Fee hinausflattern kann.";
			switch (e_rand(1, 3))
			{
				case 1:
					$str_output .= "`n`n Als sie dich einmal umschwirrt geht ein glimmernder Staub auf dich hernieder und du verlierst das Bewusstsein. Als du wieder erwachst bist du tiefer in der Höhle als zuvor. Die Fee hat dir auf deinem Weg geholfen! Vor dir siehst du eine Biegung des Tunnels";

					addnav("Die Hilfe der Fee");
					addnav("Folge der Abzweigung","forest.php?op=turn");
					break;
				case 2:

					$str_output .= "`n`nDunkelheit umhüllt dich und als due wieder bei Sinnen bist, findest Du Dich in der Haupthöhle wieder. Die Fee hat dich vera...albert";
					$session['user']['turns'] = max(0,$session['user']['turns']);
					addnav("Weiter", "forest.php?op=continue");
					break;
				case 3:
					$str_output .= "`n`nEin goldener Schein umhüllt dich. Als er verklingt fühlst du dich stark und gewappnet einige weitere Zeit im Wald zu verbringen.";
					$session['user']['turns']+=2;
					addnav("Folge deinem Weg", "forest.php?op=onway");
					break;
			}
			break;
		}
	case "turn":
		{
			$str_output .= "Du folgst der Abzweigung und kommst nach kurzer Zeit an eine Gabelung. Welchen weg möchtest Du nehmen?";
			addnav("Nimm den Linken Weg","forest.php?op=choice&op1=1");
			addnav("Gehe geradeaus weiter","forest.php?op=choice&op1=2");
			addnav("Nimm den rechten W eg","forest.php?op=choice&op1=3");
			break;
		}
	case "onway":
		{
			$str_output .= "Du bist nun schon eine ganze Weile unterwegs und wunderst Dich, dass du bisher weder umgekommen, noch belohnt worden bist. Wie auf ein Zeichen hin trittst du plötzlich auf einen kleinen Geldbeutel. Du nimmst ihn an dich, zählst 83 Goldstücke und bedankst dich artig...bei wem auch immer.";
			$session ['user']['gold']+=83;

			$str_output .= "`n`n Bereits kurz danach gelangst Du an einen unterirdischen Fluss. Zu Deiner Linken siehst Du ein kleines Boot, zu deiner Rechten führt dein Pfad wieder vom Wasser fort.`n`n
	    	Was gedenkst du zu tun?";
			addnav("Der Fluss");
			addnav("Folge dem Pfad", "forest.php?op=path");
			addnav("Steig in das Boot", "forest.php?op=boat");
			addnav("Schwimm durch den Fluss", "forest.php?op=swim");
			break;
		}
	case "swim":
		{
			$str_output .="Du stürzt Dich in die Fluten und beginnst mit großen Zügen in Richtung anderes Ufer zu schwimmen. Als du zu deiner Linken plötzlich große Luftblasen aufsteigen siehst, packt dich die Panik und du schwimmst so schnell du kannst von den Blasen weg. Doch Ohne Erfolg. Das Unbekannte kommt dir immer näher und mit einem Male schiesst ein großer Tentakel aus der Wasseroberfläche empor und in deine Richtung. Panisch holst du noch einmal tief Luft, doch bevor es überhaupt soweit ist, hat dich das Ding auch schon umschlungen und du siehst in das einzige Auge eines riesigen Oktopus bevor du unter Wasser verschwindest.`n
	    	Sekunden werden zu Minuten als du zusammen mit dem großen Ungetüm immer weiter Richtung Grund sinkst, doch mit einem Mal gibt er dich wieder frei und so schnell er kam ist er auch wieder fort. Mit allerletzter Kraft rettest Du dich an das Ufer und ziehst dich an Land wo du erschöpft liegen bleibst.";

			$session['user']['turns'] = max(0,$session['user']['turns']-5);

			addnav("Der Fluss");
			addnav("Folge dem Pfad", "forest.php?op=path");
			addnav("Steig in das Boot", "forest.php?op=boat");
			break;
		}
	case "path":
		{
			$str_output .="Du entscheidest dich dafür doch lieber auf dem Weg zu bleiben und dieser führt dich schnurstracks zu einem kleinen Haus, dass in die Seite des Tunnels eingelassen wurde und den Weg mit einer Schranke versperrt. Du klopfst ein wenig irritiert an die Tür und wunderst dich wenig, als ein großer Troll die Tür öffnet und dich anstarrt.`n
		    Was willst du hier? fragt er dich und du hast gerade das Gefühl besonders ehrlich sein zu müssen und antwortest deswegen:`n
		    Ich will hier raus`n
		    Der Troll überlegt nicht lange und bietet dir an, dich für 3 Edelsteine auf den richtigen Weg zu weisen";
			addnav("Der Troll");
			addnav("Zahle ihm Edelsteine", "forest.php?op=pay&what=gem");
			addnav("Gib ihm einen Hering", "forest.php?op=pay&what=hering");
			addnav("Nichts zahlen!", "forest.php?op=don");
			break;
		}
	case "don":
		{
			$str_output .= "Ne, vergiss es! Ich zahl doch keine 3 Edelsteine dafür. Den Weg find ich schon allein raus. Du machst auf dem Absatz kehrt und läufst erneut einige Zeit planlos durch das Gewölbe, bis du endlich irgendwann mit schmerzenden Füßen draußen ankommst.";
			$session ['user']['turns']=max(0,$session ['user']['turns']-3);
			$session['user']['specialinc'] = "";
			break;
		}
	case "pay":
		{
			if($_GET['what'] == 'gem')
			{
				if($session['user']['gems']==0)
				{
					$str_output .= "Du überlegst kurz und nickst dann akzeptierend. Da du allerdings keine 3 Edelsteine zur Hand hast gibst du dem Troll einfach nur einen roten Hering. Wo der jetzt grad herkommt weißt Du zwar selbst nicht so genau, aber dem Troll scheints zu gefallen. Er lächelt, hebt die Schranke hoch und brubbelt etwas wie - Einfach dem Weg weiter folgen.";
				}
				elseif($session['user']['gems']<3)
				{
					$str_output .= "Du überlegst kurz und nickst dann akzeptierend. Da du allerdings keine 3 Edelsteine zur Hand hast gibst du dem Troll einfach nur ".$session['user']['gems'].". Dieser schaut kurz in seine Hand und zählt ausgiebig. Dann lächelt er, hebt die Schranke hoch und brubbelt etwas wie - Einfach dem Weg weiter folgen.";
					$session['user']['gems']=0;
				}
				else
				{
					$str_output .= "Du überlegst kurz und nickst dann akzeptierend. Der Troll hält freudig seine riesige Hand auf und zählt ausgiebig. Dann lächelt er, hebt die Schranke hoch und brubbelt etwas wie - Einfach dem Weg weiter folgen.";
					$session['user']['gems']-=3;
				}
			}
			else 
			{
				$str_output .= "Du überlegst kurz und nickst dann überheblich. Mit einem geschickten griff in die tasche förderst du einen roten hering zu Tage. Wo der jetzt grad herkommt weißt Du zwar selbst nicht so genau, aber dem Troll scheints zu gefallen. Er lächelt, hebt die Schranke hoch und brubbelt etwas wie - Einfach dem Weg weiter folgen.";
			}

			$str_output .= "inige Windungen später stehst du wieder im Wald";
			$session['user']['specialinc'] = "";
			break;
		}
	case "boat":
		{
			$str_output .="Geschwind enttäust du das kleine Boot und springst darauf, bevor es von der Strömung erfasst wird. Dank der kleinen Ruder die darin liegen ist es beinahe ein Leichtes den Strom zu überqueren. Als du plötzlich aus deinem Augenwinkel bemerkst wie das Wasser hinter brodelt beginnst du hastig schneller zu rudern. Gerade noch rechtzeitig erreichst Du das Ufer und springst von Bord, als ein riesiger Tentakel aus dem Wasser schiesst und das Boot mit sich in die Tiefe reißt.`n`n
	    	Puh, da hast Du ja gerade nochmal Glück gehabt. Doch was nun?";
			addnav("Eine weitere Kreuzung");
			addnav("In den linken Gang", "forest.php?op=choice&op1=1");
			addnav("In den rechten Gang", "forest.php?op=choice&op1=2");
			addnav("Geradeaus weiter", "forest.php?op=choice&op1=3");
			break;
		}
	case "choice":
		{
			switch (e_rand(1, 3))
			{
				case 1:
					$str_output .="Als du dem Gang eine Weile gefolgt bist erreichst du eine unscheinbare Höhle, die in ein angenehmes Licht getaucht ist. An ihrem Ende steht eine weibliche Gestalt. Es ist die Drachenmutter und doch verspürst du keine Furcht als du dich ihr näherst.`n
			        `n'Meinen Dank an Dich, Fremde".($session['user']['sex']?'':'r').". Zwar bist du unwissend in meine Heimat gestolpert, aber daraus mache ich dir keinen Vorwurf. Viel eher gefällt es mir jedoch, dass du keines meiner Nester gefährdet hast. Deswegen lasse ich dich ziehen und gebe dir außerdem eine kleine Belohnung.' Sie reicht dir einen Edelstein und deutet mit dem Finger auf den Ausgang zu ihrer Linken.`n`n
			        Als du endlich die Dunkelheit der Höhlen hinter dir gelassen hast atmest du einmal tief durch und gehst dann in den Wald zurück";

					$session['user']['specialinc'] = "";
					break;
				case 2:
					$str_output .= "Mit einem Male stolperst Du in eine kleine, sehr warme Höhle voller Eier. Direkt auf der anderen Seite scheint ein Ausgang zu liegen. Als du versuchst diesen zu erreichen, trittst du unwillkürlich auf einige der Eier und zerbrichst ihre Schalen. Beinahe augenblicklich ertönt ein hasserfülltes Brüllen und du gestehst dir ein, wohl einen Fehler begangen zu haben, denn die Drachenmutter greift dich an!'";
					addnav("Wappne dich zum Kampf","forest.php?op=attack");
					break;
				case 3:
					$str_output .= "Och nöö, du bist komplett im Kreis gelaufen!'";
					addnav("","forest.php?op=continue");
					break;
			}
			break;
		}
	case "attack":
		{
			$badguy = array("creaturename"=>"Drachenmutter",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"Wut und Empörung",
			"creatureattack"=>round($session['user']['attack'])+1,
			"creaturedefense"=>round($session['user']['defense'])+1,
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.76),
			"diddamage"=>0);
			$session['user']['badguy']=createstring($badguy);
			$_GET['op']= "fight";
			break;
		}
}
if ($_GET['op'] == "fight")
{
	$battle=true;
}
if ($battle)
{
	include("battle.php");
	if ($victory)
	{
		$str_output .= "`nDie Drachenmutter liegt tot zu deinen Füßen.";
		$expbonus=$session['user']['dragonkills']*4;
		$expgain =($session['user']['level']*e_rand(18,26)+$expbonus);
		$session['user']['experience']+=$expgain;
		$str_output .= "Du erhälst ".$expgain." Erfahrungspunkte.`n`n";
		$str_output .= "Müde und abgekämpft kehrst du in den Wald zurück.";
		$session['user']['specialinc'] = "";
	}
	else if ($defeat)
	{
		$session['user']['specialinc'] = "";
		killplayer();
		$str_output .= "`nDu hörst noch entfernt das triumphierende Brüllen der Drachenmutter als einer der letzten Knochen deines Leibes splittert. `bDu bist tot`b";
	}
	else
	{
		fightnav(true,false);
	}
}

output($str_output);
?>