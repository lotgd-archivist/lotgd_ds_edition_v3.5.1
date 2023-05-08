<?php
/**
 * @author Ysandre (email:ysandre[-[at]-]gmx.net)
 * @copyright Ysandre for atrahor.de
 */

if (!isset($Char))
{
	exit();
}

page_header('Das Moor');

$Char->specialinc = 'moor.php';

switch($_GET['op'])
{
	//special event occurred
	case '':
		
		output('`TDer Tag war bisher anstrengend gewesen und langsam geht dir die Puste
				aus. Eine Rast wäre angebracht und du lässt den Blick umher schweifen.
				Die Gegend, in welche es dich verschlagen hat, ist dir zwar unbekannt, aber im Wald gleicht sich
				schließlich ein Strauch dem anderen und so kommt dir die Szenerie erst
				einmal nicht unbedingt seltsam vor. Allein der Boden ist ein wenig morastig, vielleicht
				vom Regen, der noch nicht wieder verdunstet ist. `n`n Gerade als du dich
				abwenden willst, um dir einen schöneren Platz für eine Pause zu suchen, fällt
				dir ein Glitzern zwischen den Bäumen auf. Angestrengt kneifst du die Augen
				zusammen, um festzustellen, ob du dich nicht getäuscht haben könntest. Tatsächlich
				funkelt dort nochmals etwas auf. `n`n Möchtest du der Sache auf den Grund
				gehen oder doch lieber weiter deiner Wege ziehen?');

		addnav('Weitergehen', 'forest.php?op=follow');
		addnav('Zurück in den Wald', 'forest.php?op=leave');
		break;

	//the fool choose to follow
	case 'follow':

		output('`TEs wird immer dunkler, das dichte Nadelkleid der vielen Tannen sperrt
				jedes Sonnenlicht aus und du kannst kaum noch die Hand vor deiner Nase
				erkennen. Dir fällt jeder Schritt schwer, versinkst du doch mittlerweile
				schon bis zu den Knöcheln im Morast und fragst dich, wie du je wieder deine
				Stiefel sauber bekommen sollst. Das Glitzern hast du mittlerweile aus den
				Augen verloren und allmählich verfluchst du dich dafür deiner Neugier
				nachgegeben zu haben. Am liebsten würdest du ja wieder den Rückweg
				einschlagen, aber eigentlich weißt du so gar nicht mehr aus welcher Richtung
				du gekommen bist. Verflucht aber auch, du hast dich verirrt! `n`nAls 
				erfahrener Abenteuer verfällst du natürlich nicht so einfach in Panik. Du blickst
				dich erst einmal um, ob dir vielleicht nicht doch etwas auffällt, dass
				dich zurück auf sichere Pfade geleiten könnte. Während du aufmerksam
				die Umgebung betrachtest, fallen dir ein leichtes Glimmen zwischen den
				Sträuchern, ein stetiges Flackern und in der Ferne ein helles Leuchten auf.
				Nun ist guter Rat teuer. Welchem Licht willst du folgen? ');

		addnav('Glimmen', 'forest.php?op=glow');
		addnav('Flackern', 'forest.php?op=flicker');
		addnav('Leuchten', 'forest.php?op=shiners');
		break;

	//follow the glow
	case 'glow':
		switch (e_rand(1,20))
		{
			//find purse #1 (chance of 25%) -> +500 gold
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				output('`TDu stapfst noch eine Weile durch das Moor, immer das Glimmen
						im Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Schließlich	scheinst du den Ursprung des seltsamen Lichtes
						gefunden zu haben. `n`n	Es ist ein Goldbeutel, aus dem einige
						Münzen herausgefallen sind und das Sonnenlicht reflektieren. 
						Schnell zählst du die Goldstücke und kommst auf die stolze
						Summe von `^500 Gold.`TVerstohlen blickst du dich um, ob nicht irgendwo
						der Eigentümer dieses kleinen Vermögens hier irgendwo zu entdecken
						ist. Du steckst das Gold rasch ein und freust dich über die
						Aufbesserung der Haushaltskasse, ehe du endlich einen Weg zurück
						in den Wald findest.');

				$Char->gold += 500;

				$Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
				break;

			//get lost in the forest #1 (chance of 25%) -> loss of 5 turns
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
				output ('`TDu stapfst noch eine Weile durch das Moor, immer das Glimmen
						im Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Doch plötzlich wirst du abgelenkt, als ein Ast irgendwo
						lautstark unter einem schweren Gewicht zusammenbricht. Als du dich schließlich wieder
						umblickst, um das Glimmen zu suchen, musst du feststellen, dass du
						dich wohl nun endgültig verlaufen hast! `n`n
						Du brauchst eine Weile um wieder aus dem Moor zu finden und `$ verlierst `Tdaher 5
						Waldkämpfe.');

				$Char->turns -= 5;			    

			    $Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
				break;

			//nothing special happens #1 (chance of 50%) -> loss of 1 turn
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
				output('`TDu stapfst noch eine Weile durch das Moor, immer das Glimmen im
						Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Doch plötzlich wirst du abgelenkt, als ein Ast irgendwo
						lautstark unter einem schweren Gewicht zusammenbricht. Als du dich schließlich wieder
						umblickst, um das Glimmen zu suchen, stellst du fest, dass du
						wieder dort bist, von wo aus du gekommen warst. `i`YWenigstens
						etwas!`T`i, denkst du dir und machst dich weiter deiner Wege. `n
						Dennoch hat dich deine Neugier einen Waldkampf gekostet!');

				$Char->turns -= 1;	

				$Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
				break;
		}//endswitch glow
		break;

	//follow the flicker
	case 'flicker':
		switch (e_rand(1,20))
		{
			//bandits beat player (chance of 15%) -> loss of all gold + LP = 1
			case 1:
			case 2:
			case 3:
				output('`TDu stapfst noch eine Weile durch das Moor, immer das Flackern im
						Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Schließlich scheinst du den Ursprung des seltsamen Lichtes gefunden
						zu haben. `n Es ist ein Lagerfeuer, umrundet von zerrissenen und
						heruntergekommenen Gestalten, die dir ganz und gar nicht geheuer
						vorkommen. Noch ehe du dich unbemerkt wieder umdrehen und zurück
						in den Wald verschwinden kannst, spürst du einen Schlag auf den
						Hinterkopf und wirst ohnmächtig. `n`n Als du wieder erwachst, ist
						das Feuer herabgebrannt und die Räuber verschwunden. Dein ganzes
						Gold haben sie mitgehen lassen. Du brauchst dringend einen Trank
						gegen die Schmerzen und du fragst dich, wie du nun den Heiler
						bezahlen sollst. Mist aber auch!');

				$Char->hitpoints = 1;
				$Char->gold = 0;

				$Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
				break;

			//get lost in the forest #2 (chance of 25%) -> loss of 5 turns + negative buff
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
				output('`TDu stapfst noch eine Weile durch das Moor, immer das Flackern im
						Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Doch plötzlich scheinst du abgelenkt, ein Ast bricht irgendwo
						lautstark unter einem schweren Gewicht zusammen und deine
						Aufmerksamkeit geht flöten. Als du dich schließlich wieder
						umblickst, um das Flackern zu suchen, musst du feststellen, dass du
						dich wohl nun endgültig verlaufen hast! `n`n Du brauchst eine Weile
						um wieder aus dem Moor zu finden und `$ verlierst `Tdaher
						5 Waldkämpfe. Außerdem fühlst du dich wie gerädert und spürst schon
						jetzt alle Knochen vom vielen Waten. Deine Verteidigung `$ sinkt!');

				$Char->turns -= 5;					

			    $bones = array(
								"name" => "`$ schmerzende Knochen",
								"rounds" => 20,
			    					"startmsg"=>"`0Deine Bewegungen sind reichlich steif.`n",
								"wearoff" => "`0Du fühlst dich langsam entspannter.`n",
								"defmod" => 0.95,
								"activate"=>"roundstart"
							   );
			    buff_add($bones);

			    $Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
				break;

			//find purse #2 (chance of 45%) -> +1000 gold
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
				output('`TDu stapfst noch eine Weile durch das Moor, immer das Flackern im
						Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Schließlich scheinst du den Ursprung des seltsamen Lichtes gefunden
						zu haben. `n`n Es ist ein Goldbeutel, aus dem einige Münzen
						herausgefallen sind und nun vom Sonnenlicht bestrahlt werden.
						Schnell zählst du die Goldstücke und kommst auf die stolze Summe
						von `^ 1000 Gold.`T Verstohlen blickst du dich um, ob nicht der
						Eigentümer dieses halben Vermögens hier irgendwo zu entdecken ist.
						Du steckst das Gold rasch ein und freust dich über die Aufbesserung
						der Haushaltskasse, ehe du endlich einen Weg zurück in den Wald
						findest.');

				$Char->gold += 1000;

				$Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
				break;

			//meet some gypsies (chance of 15%) -> some positive buff
			case 18:
			case 19:
			case 20:
				output('`TDu stapfst noch eine Weile durch das Moor, immer das Flackern im
						Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Schließlich scheinst du den Ursprung des seltsamen Lichtes gefunden
						zu haben. `nEs ist ein Lagerfeuer, umrundet von einigen Planwagen
						und Pferden, die es doch tatsächlich geschafft haben, in dem
						weitläufigen Moor ein trockenes Fleckchen  zu finden. Ein
						Zigeunervölkchen scheint hier Rast zu machen und als man dich
						entdeckt, wirst du recht herzlich dazu eingeladen am Fest, das
						anscheinend gerade voll im Gange ist, teilzunehmen. Lange Zeit
						bleibt dir leider nicht bei dem lustigen Lage, aber als du
						schließlich den Rückweg antrittst, begleitet dich die `Qfröhliche
						Musik`T noch einige Zeit. Dein Angriff steigt!');

				$music = array(
							"name" => "`QMusik in den Ohren",
							"rounds" => 20,
			    			"startmsg"=>"`0Der Ohrwurm lässt dich nicht mehr los.`n",
							"wearoff" => "`0Das liebliche Lied verschwindet wieder.`n",
							"atkmod" => 1.04,
							"activate"=>"roundstart"
							  );
				buff_add($music);

				$Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
				break;
		}//endswitch flicker
		break;

	//follow the shiners
	case 'shiners':
		switch (e_rand(1,20))
		{
			//find purse #3 (chance of 35%) -> +1500 gold
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
				output ('`TDu stapfst noch eine Weile durch das Moor, immer das Leuchten
						im Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Schließlich scheinst du den Ursprung des seltsamen Lichtes gefunden
						zu haben. `n`n Es ist ein Goldbeutel, aus dem einige Münzen
						herausgefallen sind und nun vom Sonnenlicht bestrahlt werden.
						Schnell zählst du die Goldstücke und kommst auf die stolze Summe
						von `^ 1500 Gold.`T Verstohlen blickst du dich um, ob nicht der
						Eigentümer dieses halben Vermögens hier irgendwo zu entdecken ist.
						Du steckst das Gold rasch ein und freust dich über die Aufbesserung
						der Haushaltskasse, ehe du endlich einen Weg zurück in den Wald
						findest.');

				$Char->gold += 1500;

				$Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
    			break;

    		//get lost in the forest #3 (chance of 15%) -> death for the fools
			case 8:
			case 9:
			case 10:
				output('`TDu stapfst noch eine Weile durch das Moor, immer das Leuchten
						im Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Irgendwie bekommst du aber allmählich nicht mehr deine Füße aus dem
						widerlichen Morast. Es fällt dir immer schwerer einen Schritt zu
						machen, bis du plötzlich bis zu Hüfte einsackst. Mist aber auch!
						Du merkst wie du langsam, aber stetig immer weiter sinkst und
						beginnst panisch mit den Armen zu rudern, in der Hoffnung dich
						irgendwie frei zu bekommen. Aber das Schicksal scheint dir nicht
						hold zu sein und schließlich bist auch du ein Opfer des Moores
						geworden. `n`n `$ Du bist gestorben und kannst morgen
						weiterkämpfen!');

				addnews($Char->name.'`T war so leichtsinnig das Moor herauszufordern.');
      			killplayer();

      			$Char->specialinc = '';
				break;

			//find way back to the forest (chance of 20%) -> gain 3% exp
			case 11:
			case 12:
			case 13:
			case 14:
				output('`TDu stapfst noch eine Weile durch das Moor, immer das Leuchten im
						Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Doch plötzlich scheinst du abgelenkt, ein Ast bricht irgendwo
						lautstark unter einem schweren Gewicht zusammen und deine
						Aufmerksamkeit geht flöten. Als du dich schließlich wieder
						umblickst, um das Leuchten zu suchen, musst du feststellen, dass
						du das tückische Moor auf deiner Suche nach dem seltsamen Licht
						komplett durchquert hast. Dir ist nichts geschehen und auch sonst
						scheint noch alles dran zu sein. `n`n Mit ein wenig `2mehr Erfahrung
						`Tim Gepäck trittst du deinen weiteren Weg durch den Wald an. ');

				$Char->experience *= 1.03;

				$Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
				break;

			//nothing special happens #2 (chance of 30%) -> loss of 1 turn
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
				output('`TDu stapfst noch eine Weile durch das Moor, immer das Glimmen im
						Blick, damit du es ja nicht mehr aus den Augen verlieren kannst.
						Doch plötzlich scheinst du abgelenkt, ein Ast bricht irgendwo
						lautstark unter einem schweren Gewicht zusammen und deine
						Aufmerksamkeit geht flöten. Als du dich schließlich wieder
						umblickst, um das Glimmen zu suchen, stellst du fest, dass du
						wieder dort bist, von wo aus du gekommen warst. `i`YWenigstens
						etwas!`T`i, denkst du dir und machst dich weiter deiner Wege. `n
						Dennoch hat dich deine Neugier einen Waldkampf gekostet!');

				$Char->turns -= 1;	

				$Char->specialinc = '';
    			//addnav('Zurück in den Wald','forest.php');
    			break;
		}//endswitch shiners
		break;

	case 'leave':
		output('Nein, du weißt es besser. Lieber bleibst du auf dem Weg als dass du dich in
				unbekannte Gefilde begibst. Du lässt das Glitzern Glitzern sein und begibst
				dich zurück in den Wald.');
		$Char->specialinc = '';
		//forest();
		break;

}//endswitch
?>
