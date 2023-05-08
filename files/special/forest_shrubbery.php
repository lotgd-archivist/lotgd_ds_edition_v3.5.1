<?php

/**
 * Special im Wald: Der Gebüschverkäufer. Wer errät wo das her stammt darf den wald die größte und mächtigste Eiche fällen. Mittels eines Herings!
 * @copyright Dragonslayer for Atrahor
 * @author Dragonslayer
 */

page_header('Der Gebüschhändler');

/** @noinspection PhpUndefinedVariableInspection */
$Char->specialinc = 'forest_shrubbery.php';
$str_backlink = 'forest.php';

$str_out = get_title('Der Gebüschhändler');

switch($_GET['sop'])
{
	default:
	case '':
		{
			$str_out .= '`JDu lustwandelst gerade ein wenig durch einen Teil des Waldes, den du noch `pNIE`J zuvor gesehen hast und pfeifst das wundervolle Liedchen "Wir wollen `pNIE`Jmals auseinander gehn" von Violet und Seth im Duett, als dich plötzlich ein `pNIE`J gekanntes Geräuscht aufhorchen lässt. Ist das eventuell ein Drache? Hier draussen? Nein, `pNIE`J und nimmer. Vielmehr ist es ein kleines, von Ochsen gezogenes Wägelchen, welches dir da entgegengerumpelt kommt. Darauf sitzt ein hoch gewachsener und schlacksig wirkender Mann mit spitzem Bart, schwarzen Haaren und braunem Umhang. Er scheint ein Händler zu sein. Der Wagen kommt neben dir zum stehen und er blickt zu dir herunter.`n`a"[Junger Mann|Junge Frau] leider war ich in diesen Gefilden bisher noch `pNIE`a unterwegs und Frage mich bereits, ob es für mich hier etwas zu holen gibt. Hättet ihr Interesse an meinen hochqualitativen Gebüschen?" -`G"Ihr meint ihr seid ein fahrender Gebüschhändler" - `a"Nun, genau das meinte ich damit. Wie sieht es aus? Nur `b100 Edelsteine`b und dieses schöne Stück Holz wird euch `pNIE`a enttäuschen." - `G"`pNIE`G?" - `a"Nein, `pNIE`a! Dafür garantiere ich mit meinem Namen. `pNIE`asbert Nevernoh, zu Euren Diensten!"`n`n`J...`n`nDu starrst den Mann an wie eine Kuh das neue Tor und das weißt du auch. Allerdings ist dir das auch gerade ziemlich egal.';
			if($Char->gems >= 100)
			{
				$str_out .= 'Das nötige Barklimper hättest du dabei. Also? Wie siehts aus?';
				addnav('So eine Gelegenheit kommt `pNIE`0 wieder!','forest.php?sop=buy');
				addnav('Nein danke, bin ich denn deppert?','forest.php?sop=leave');	
			}
			else 
			{
				$str_out .= 'Schließlich kannst du dir das eh nicht leisten und so gehst du kopfschüttelnd einfach weiter. So ein komischer Kauz.';
				addnav('Pffft, 100 ES...','forest.php?sop=leave');
			}			
			break;
		}
	case 'buy':
		{
			$Char->specialinc = '';
			$str_out .= '`a"Habt Dank, [edler Herr|edle Dame], diesen Kauf werdet ihr nicht bereuen. Stellt euch nur die Möglichkeiten vor die sich euch mit diesem erstklassigen Gebüsch eröffnen werden."`J Und mit diesen Worten rattert er von dannen und seine worte werden immer leiser, je weiter er sich entfernt: `a"Immer ein schattiges Plätzchen, Schutz vor Regen und Schnee, der Knüller auf jeder Party, ein Heim für Vögel und Ungeziefer jeder Art..." `JNa herzlichen Glückwunsch du [frischgebackener Gebüschbesitzer|frischgebackene Gebüschbesitzerin] du.';
			$Char->gems -= 100;
			item_add($Char->acctid,'shrubbery');
			break;
		}
	case 'leave':
		{
			$Char->specialinc = '';
			redirect('forest.php');
			break;
		}
}
output(words_by_sex($str_out));
?>