<?php

/**
 * Special im Wald: Die Götterklinge
 * @copyright Dyan for Atrahor
 * @author Dragonslayer
 */

page_header('Die Götterklinge');

/** @noinspection PhpUndefinedVariableInspection */
$Char->specialinc = 'forest_godsblade.php';
$str_filename = basename($_SERVER['SCRIPT_FILENAME']);
$str_backlink = 'forest.php';

$str_out = get_title('Die Götterklinge');

switch($_GET['op'])
{
	default:
	case '':
		{
			$str_out .= 'Auf deinen Streifzügen fällt dir plötzlich eine alte, verwitterte Ruine auf. Das Mauerwerk ist an vielen Stellen eingefallen und mit Pflanzen überwuchert. Früher muss dieser Ort mal ein Tempel oder ähnliches gewesen sein, es lässt sich immernoch ein Hauch von Heiligkeit und Reinheit spüren. Inmitten der zerfallenen Ruinen bemerkst du plötzlich einen Schrein und in dessen Mitte eine Klinge mit einer seltsamen Aura. Mit einem Glänzen in den Augen trittst du langsam näher an das Schwert heran und beäugst es ganz genau. Es scheint aus besonders edlen Metallen hergestellt worden zu sein und auf ihm befinden sich seltsame, unbekannte Zeichen. Das Schwert scheint bereits seit einer Ewigkeit hier im Altar zu stecken und doch sieht die Schneide aus, als sei sie erst heute fertig geschmiedet worden. Nach langem Bewundern und Mustern der Klinge kommst du zu einem Entschluss.';
			addnav('Die Klinge berühren','forest.php?op=touch');
			addnav('Den Altar verlassen','forest.php?op=leave');
			break;
		}
	case 'touch':
		{
			$Char->specialinc = '';
			$int_rand = mt_rand(1,7);
			switch ($int_rand)
			{
				case 1:
					{
						$str_out .= 'Du berührst die Klinge und eine unglaubliche Kraft durchflutet deinen Körper. Deine Angriffskraft steigt um eins und du erhälst einen zusätzlichen Lebenspunkt.`n`n
						Du verlässt den Altar und die Ruine mit dem Wissen, dass die Götter auf deiner Seite sind und machst dich schnellstmöglich auf den Weg in den Wald. Die Erlebnisse von gerade lassen dich vor Tatendrang und Mut nur so strotzen.';
						$Char->maxhitpoints += 1;
						$Char->attack += 1;
						break;
					}
				case 2:
					{
						$str_out .='Du berührst die Klinge und wirst von einer einmaligen Weisheit durchflutet. Du erhälst in deinen Künsten eine Anwendung mehr.';
						increment_specialty();
						break;
					}
				case 3:
					{
						$str_out .='Du berührst die Klinge und fühlst die Weisheit der Götter. Deine Erfahrung steigt.';
						$Char->experience += 1000;
						break;
					}
				case 4:
					{
						$str_out .='Du berührst die Klinge und fühlst wie dein Körper von Klarheit ergriffen wird. Deine Sinne sind geschärft.';
						break;
					}
				case 5:
					{
						$str_out .='Du berührst die Klinge, chneidest dich an deren Schneide und spürst einen kurzen, aber heftigen Schmerz. Du verlierst ein paar Lebenspunkte und wirst kurz ohnmächtig.`n`n
						Du wachst wieder auf und verlässt diesen Ort rasch. Es hat ohne Zweifel seine Gründe, dass diese Unheilsklinge hier im Stein steckt und das sollte lieber so bleiben. Verletzt und auch etwas verängstigt machst du dich auf in den Wald und lässt die Ruine hinter dir.';
						$Char->turns -= 2;
						$Char->hitpoints *= 0.75;
						break;
					}
				case 6:
					{
						$str_out .='Du berührst die Klinge und ein Blitz schleudert dich fort. Du bist verletzt und bewusstlos. `n`n
						Du wachst wieder auf und verlässt diesen Ort rasch. Es hat ohne Zweifel seine Gründe, dass diese Unheilsklinge hier im Stein steckt und das sollte lieber so bleiben. Verletzt und auch etwas verängstigt machst du dich auf in den Wald und lässt die Ruine hinter dir.';
						$Char->turns -= 4;
						$Char->hitpoints *= 0.25;
						break;
					}
				case 7:
					{
						$str_out .='Du berührst die Klinge und erbost die Götter damit diesen heiligen Ort entweiht zu haben. Du fühlst wie eine unbekannte Kraft dir das Leben aus dem Körper zieht. Du stirbst einen langsamen Tod.`n`n
						Deine Neugier wurde dir heute zum Verhängnis. Die Götter zu erzürnen und in ihre Heiligtümer einzudringen war ein Fehler, den du mit dem Leben bezahlt hast. Du bist tot. Jedoch lehrt dich das Erlebte, dass die Götter wankelmütig sind und mit Vorsicht behandelt werden wollen.';
						$Char->experience *= 1.05;
						$Char->kill(100,0);
						break;
					}
			}
			break;
		}
	case 'leave':
		{
			$Char->specialinc = '';
			$str_out .= 'Die ganze Sache ist dir nicht geheuer. Schwerter die in Steinen stecken bringen nur Ärger und sollten lieber in Ruhe gelassen werden. Du verlässt die Ruinen und gehst zurück in den Wald.';
			break;
		}
}
output($str_out);
?>