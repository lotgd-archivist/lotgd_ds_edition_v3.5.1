<?php
/**
 * @author Ysandre (email:ysandre[at]gmx.net)
 * @copyright Ysandre for atrahor.de
 */

if (!isset($Char))
{
	exit();
}

page_header('Der Kürbis');

$Char->specialinc = 'halloween.php';

switch($_GET['op'])
{
	//special event occurred
	case '':
		output(get_title('`QDer Riesenkürbis').'`dAus dem dichten Unterholz 
		leuchtet dir ein unheimliches Licht entgegen. Da du'.
		($Char->sex?'eine tapfere Heldin':'ein tapferer Held').'
		bist und deine Neugierde dich packt, gehst du weiter und kommst auf eine 
		kleine Lichtung. Vor dir steht ein dicker, riesiger Kürbis, der fast 
		so groß ist wie du. In den Kürbis wurde eine Fratze hineingeschnitten, 
		die dich finster anzublicken erscheint. Der Kürbis wird von mehreren 
		Kerzen, die im Kreis angeordnet in seinem Inneren stehen, beleuchtet. 
		In deren Mitte liegen ein paar glitzernde Edelsteine, die dich wie 
		magisch anziehen. Bist du mutig genug zwischen die Zähne des 
		Kürbismundes zu fassen und ihn hinaus zu holen?');

		addnav('In den Kürbis fassen', 'forest.php?op=try');
		addnav('Zurück in den Wald', 'forest.php?op=leave');
		break;

	case 'leave':
		output('`A Du traust dem Bild, das sich dir zeigt nicht. Ein einsamer
		Kürbis auf einer Lichtung, die du noch nie gesehen hast, ist dir nicht 
		geheuer. Schnell trittst du den Rückzug an und lässt den Kürbis hinter 
		dir.');
		$Char->specialinc = '';
		break;

	case 'try':
		$rand = e_rand(1,25);
		switch ($rand)
		{
			case ($rand < 5):
				output('`qDie Hitze der Kerzen, die du beim Versuch, den
				Edelstein heraus zu fischen, zu spüren bekommst, lässt dich 
				unberührt und du kannst die begehrten Steine herausfischen. `n
				Du `2erhälst `q2 Edelsteine.');
				$Char->gems += 2;
				$Char->turns--;
				$Char->specialinc = '';
				break;
			case ($rand < 10):
				output('`A Du verbrennst dich an den Kerzen und zuckst leise
				schreiend zurück. Von deinem Schrei erweckt, bewegt sich der 
				Kürbis und sein Mund schnappt auf und zu. Voller Angst lässt du 
				die Edelsteine liegen und rennst davon.`n
				Du `$verlierst `Aan Ansehen, weil du feige davon gelaufen bist.');
				$Char->reputation *= 0.95;
				$Char->specialinc = '';
				break;
			default:
				output('`qAls du gerade nach den vermeintlichen Edelsteinen greifen
				willst, bemerkst du plötzlich Geister um dich herum, sie 
				beobachten dein Handeln. Ihre Blicke kannst du spüren, 
				sie scheinen dich regelrecht zu durchbohren und die 
				bedrohliche Stille um dich herum macht dich nervös. Du fühlst 
				genau dass es nun von deiner nächsten Aktion abhängt, ob diese 
				Sache noch gut für dich ausgeht.');
				addnav('Was wirst du tun?');
				addnav('Mutig sein', 'forest.php?op=brave');
				addnav('Gewitzt sein', 'forest.php?op=smartly');
				addnav('Vorsichtig sein','forest.php?op=careful');
				break;
		}//endswitch
		break;

	case 'brave':
		output('`ADu legst blitzschnell deine Hand auf die Kerze und
		erlöschst somit die Flamme, in der Hoffnung dadurch die Geister 
		zu vertreiben oder diese wenigstens dazu zu bewegen sich einen 
		anderen Dummen zu suchen. Leider war die Idee alles andere als 
		gut und du hast mit deinem unbedachten Handeln die Geister 
		erzürnt. Das ist wohl ganz dumm gelaufen. Die Geister fallen 
		über dich her und entziehen dir fast deine gesamte Kraft.');
		$Char->hitpoints = 1;
		$Char->specialinc = '';
		break;
	case 'smartly':
		output('`qDu überlegst einen Moment, wie du die Geister besänftigen 
		könntest und kommst auf die Idee, ihnen einen deiner wertvollen 
		Edelsteine zu opfern.');
		$Char->gems--;
		$rand = e_rand(1,3);
		switch ($rand)
		{
			case 1:
				output('`n`nDie Geister scheinen zufrieden zu sein und bilden 
				eine Gasse um dir den Weg freizugeben. Während du an ihnen 
				vorbeigehst, streifen einige sanft deine Arme und Schultern. 
				Der kühle Hauch erfrischt dich und du spürst eine ungewohnte 
				Kraft in dir.');
				$ghost = array(
					"name" => "`2 Kraft der Ahnen",
					"rounds" => 20,
    				"startmsg"=>"`0Du spürst die Kraft deiner Ahnen in dir.`n",
					"wearoff" => "`0Die Kraft deiner Ahnen hat dich verlassen.`n",
					"atkmod" => 1.04,
					"defmod" => 1.1,
					"activate"=>"roundstart"
					);
				buff_add($ghost);
				break;
			default:
				output('`n`nDie Geister scheinen zufrieden und lassen dich weiter
				deiner Wege ziehen.');
		}//endswitch
		$Char->specialinc = '';
		break;
	case 'careful':
		output('`qDu ziehst deine Hand vorsichtig zurück und siehst diese wenig 
				später verängstigt an. Kaum darauf ertönt von den Geistern ein 
				Kichern. Erst jetzt bemerkst du, dass es nur die Stadtkinder 
				sind die sich als Geister verkleidet haben. Du hörst noch lange 
				ihr Lachen und kannst es so schnell auch nicht aus deinen 
				Gedanken vertreiben. Es stört eine Weile deine Konzentration.');
		$giggle = array(
					"name" => "`$ nachhallendes Kichern",
					"rounds" => 20,
    					"startmsg"=>"`0Voller Scham bist du noch nicht bereit 
    					dich angemessen zu verteidigen.`n",
					"wearoff" => "`0Du fühlst dich langsam wieder selbstsicherer.`n",
					"defmod" => 0.95,
					"activate"=>"roundstart"
					);
		buff_add($giggle);
		$Char->specialinc = '';
		break;

}//endswitch
?>