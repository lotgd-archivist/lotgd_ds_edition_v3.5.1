<?php

/**
* Drei Monster, drei Waffen, man sollte aufpassen, was man wählt..
* Inspired by Cordi :D
* @author Laulajatar für atrahor.de
*/

if (!isset($session))
{
	echo ('$session not set in basilisk.php');
	exit();
}

$session['user']['specialinc']=basename(__FILE__);
$str_output = '';

switch ($_GET['op'])
{ 
	case 'run': 
	{
		$str_output .= '`IDas ist dir alles nicht geheuer und eigentlich hattest du ja vor, heute mal lebend nach Hause zu kommen. Also lässt du den Toten samt seiner Sachen liegen, verdrückst dich still und leise wieder und bist dir nach einer ganzen Weile wenigstens sicher, dass dir Niemand und vor allem Nichts gefolgt ist.`n`n';
		$session['user']['specialinc']='';
		$session['user']['specialmisc']='';
		break;
	}

	case 'stay':
	{
		switch ($_GET['weapon'])
		{ 
			case 'mirror':
				//$name = 'Spiegel';
				$named = 'den Spiegel';
				break;
			case 'ball':
				//$name = 'Kristallkugel';
				$named = 'die Kristallkugel';
				break;
			case 'sword':
				//$name = 'Schwert';
				$named = 'das Schwert';    
				break;
		}
		
		$str_output .= '`IEntschlossen und mutig bückst du dich und hebst '.$named.' auf. Das wäre doch gelacht, wenn du den Verantwortlichen nicht finden und zur Rechenschaft ziehen würdest! Stück für Stück wagst du dich in das dichter werdende Unterholz hinein und stehst schließlich vor einer Felswand, in der ein dunkles, unheilvoll scheinendes Loch klafft.`n';
		if ($session['user']['specialmisc']!='Poltergeist')
		$str_output .= 'Wie es der Zufall so will, enden genau hier die Spuren, denen du gefolgt bist.`n';
		$str_output .= '\'Jetzt gibt es kein Zurück mehr\', denkst du dir, als du '.$named.' fester packst und dich langsam in den Höhleneingang hineinwagst. Doch weit kommst du nicht, schon nach ein paar Schritten stellst du fest, dass du wohl gefunden hast, was du gesucht hast.`n`n';  

		switch ($session['user']['specialmisc'])
		{
			case 'Basilisk':
			{
				$str_output .= '`IVor dir kannst du im Halbdunkel der Höhle ein Wesen erkennen, das den Körper einer Schlange hat und den Kopf eines Hahns. \'Ein Basilisk\', schießt es dir durch den Kopf, nur Augenblicke, ehe er auf dich aufmerksam werden und dein Schicksal besiegelt sein wird.`n';
				
				if ($_GET['weapon']=='mirror')
				{
					$expplus=round($session['user']['experience']*0.05);
					$str_output .= 'Geistesgegenwärtig reißt du '.$named.' hoch, den du mitgenommen hast, und hältst ihn der Bestie entgegen, gerade als sie dich mit ihrem versteinernden Blick ansehen will. Von der Wirkung seines eigenen Zaubers getroffen erstarrt der Basilisk augenblicklich und als du es schließlich wieder wagst, deine Augen zu öffnen und '.$named.' sinken zu lassen, ist die Gefahr gebannt. Wenn das mal nicht knapp war... doch immerhin hast du dabei etwas gelernt.`nDu erhältst `j '.$expplus.' `IErfahrungspunkte, hast es jedoch so eilig, diesen schauderhaften Ort zu verlassen, dass du '.$named.' glatt hier vergisst.`n`n';
					$session['user']['experience']+=$expplus;
				}
				else
				{
					$str_output .= 'Dummerweise kann dir in dieser Situation weder '.$named.' noch dein/e '.$session['user']['weapon'].' `Ihelfen. Du kommst nur noch dazu, den Angriff anzudeuten, als der Blick des Basilisken auf dich fällt und du mitten in der Bewegung erstarrst. Sieht so aus, als würdest du noch eine ganze Weile hier stehen bleiben...`n`n`4Du bist tot! Du verlierst all dein Gold und 5% deiner Erfahrung.`n`n';
					addnews($session['user']['name'].' `Imacht sich gut als Statue...');
					killplayer();
				}
				break;
			}
			
			case 'Golem':
			{
				$str_output .= '`IVor dir kannst du im Halbdunkel der Höhle ein riesiges Wesen erkennen, das aus massivem Stein zu bestehen scheint. \'Ein Golem\', schießt es dir durch den Kopf, nur Augenblicke, ehe er auf dich aufmerksam werden wird und dein Schicksal besiegelt ist.`n';

				if ($_GET['weapon']=='sword')
				{
					$expplus=round($session['user']['experience']*0.05);
					$str_output .= 'Noch während du überlegst, wie du hier noch heil herauskommen sollst, beginnt '.$named.' in deiner Hand leicht zu glühen. Viel hast du ja nicht zu verlieren, weswegen du es fester packst und mit einem Kampfschrei auf den Golem losstürmst! Und wirklich, wo eine normale Waffe wohl vollkommen versagt hätte, zerschneidet dieses Schwert den steinernen Körper wie Butter. Die beiden Hälften des Golems fallen zu Boden, doch leider ist auch die Klinge des Schwertes erloschen und zerbrochen. So wird es dir nichts mehr nützen, weswegen du es schweren Herzens hier zurücklässt. Doch immerhin hast du dabei etwas gelernt.`nDu erhältst `j '.$expplus.' `IErfahrungspunkte!`n`n';
					$session['user']['experience']+=$expplus;
				}
				else
				{
					$str_output .= 'Dummerweise kann dir in dieser Situation weder '.$named.' noch dein/e '.$session['user']['weapon'].' `Ihelfen. Der Golem kommt mit donnernden Schritten auf dich zu, während deine Schläge nur von ihm abprallen und das letzte, an das du dich erinnern kannst, ist eine riesiege, steinerne Faust, die auf deinen Kopf niedersaust. Na aua...`n`n`4Du bist tot! Du verlierst all dein Gold und 5% deiner Erfahrung.`n`n';
					addnews($session['user']['name'].'`Is Schädel wurde von einem Golem zu Matsch verarbeitet.');
					killplayer();
				}
				break;
			}
			
			case 'Poltergeist':
			{
				$str_output .= '`IVor dir kannst du im Halbdunkel der Höhle schemenhafte Umrisse ausmachen, die jedoch nicht viel deutlicher werden, als du sie anstarrst. Im Gegenteil, als sich das.. Ding langsam auf dich zubewegt musst du feststellen, dass du direkt hindurchsehen kannst! \'Das muss ein Geist sein!\' Doch ob dir diese Erkenntnis noch so viel nützen wird? Du erinnerst dich, einmal gehört zu haben, dass manche Geister, vor allem Poltergeister, gerne mit Dingen werfen und bist dir garnicht so sicher, wer schneller wäre.`n`n';

				if ($_GET['weapon']=='ball')
				{
					$expplus=round($session['user']['experience']*0.05);
					$str_output .= 'Dir ist klar, dass deine Waffen nichts gegen ein Wesen ausrichten können, das keinen Körper hat. Schon willst du über eine Flucht nachdenken, als '.$named.' in deiner Hand zu leuchten beginnt und der Poltergeist in seiner Bewegung innehält. Ein unwirkliches Heulen erfüllt die Höhle, während das blasse Leuchten stärker wird und der Geist nun, scheinbar gegen seinen Willen, angezogen wird. Seine Umrisse werden verzogen, auf die Kugel zu und mit einem Mal wird er direkt hineingesogen! Nachdem du den ersten Schreck verdaut hast, lässt du die Kugel fallen, nicht sicher, ob sie nun noch gefährlich ist oder du sie besser zerschlagen solltest. Also machst du das einzig vernünftige: Du nimmst die Beine in die Hand und verschwindest!`nDoch immerhin hast du dabei etwas gelernt.`nDu erhältst `j '.$expplus.' `IErfahrungspunkte!`n`n';
					$session['user']['experience']+=$expplus;
				}
				else
				{
					$str_output .= 'Dir ist klar, dass dir in dieser Situation weder '.$named.' noch dein/e '.$session['user']['weapon'].' `Ihelfen kann, weswegen du dich umdrehst um wegzulaufen. Leider stimmen die Geschichten wohl doch und Geister werfen gerne mit Dingen. Als dir das klar wird steckt das Messer jedoch schon tief in deinem Rücken und du sinkst langsam auf die Knie, während dir schwarz vor Augen wird.`n`n`4Du bist tot! Du verlierst all dein Gold und 5% deiner Erfahrung.`n`n';
					addnews($session['user']['name'].' `Iwurde von einem Geist förmlich zu Tode erschreckt.');
					killplayer();
				}
				break;
			}
			default: 
			{
				$str_output = 'Na ups, dein Gegner sollte ein "'.$session['user']['specialmisc'].'" sein! Irgendwas ist hier schief gelaufen, schreibe bitte eine Anfrage..';
				break;
			}
		} // switch misc
		
		$session['user']['specialinc']='';
		$session['user']['specialmisc']='';
		
		break;
	}

	default:
	{
		$str_output .= '`IWährend du durch den Wald gehst bemerkst du mit einem Mal, wie deine Umgebung sich ein wenig verändert. Die Pflanzen wirken krank und gelblich, manche sogar schon tot, kein Laut ist mehr zu hören und unter einem Busch kannst du ein totes Kaninchen entdecken. Gerade als du dir überlegst, ob es nicht sicherer wäre, einen anderen Weg einzuschlagen, stolperst du fast über eine Leiche.`nVor dir liegt ein Abenteurer, beziehungsweise das, was noch von ihm übrig ist. Was der Grund für sein Ableben war, kannst du auf den ersten Blick nicht feststellen, dafür siehst du seinen Beutel der, halb geöffnet, neben ihm liegt. Es scheinen sich zwei Gegenstände darin zu befinden: Ein `&Spiegel `Iund eine `&Kristallkugel`I. Außerdem liegt sein `&Schwert `Inoch neben ihm, als hätte er versucht, sich damit gegen irgendetwas zu verteidigen.`nDu suchst nach verdächtigen Spuren auf dem Boden und ';
		$what = e_rand(1,3);
		switch ($what)
		{
		case '1':
			$str_output .= 'bemerkst eine Art `iSchleifspur`i, die sich von dem Körper entfernt und im Unterholz verschwindet.';
			$session['user']['specialmisc'] = 'Basilisk';
			break;
		case '2':
			$str_output .= 'bemerkst `iFußspuren`i, die sich von dem Körper entfernen und im Unterholz verschwinden.';
			$session['user']['specialmisc'] = 'Golem';
			break; 
		default:
			$str_output .= 'bemerkst `igar keine`i verdächtigen Spuren... sehr verdächtig.';
			$session['user']['specialmisc'] = 'Poltergeist';
			break; 
		}
		
		$str_output .= '`nWas willst du nun tun? Du kannst einen der Gegenstände mitnehmen und versuchen, herauszufinden, was den Abenteurer getötet hat oder du verschwindest so schnell du kannst und hoffst, dass, was immer es war, DICH nicht auch noch bekommt.`n`n';
		
		addnav('Was tust du?');
		addnav('Den `&Spiegel `0mitnehmen','forest.php?op=stay&weapon=mirror');
		addnav('Die `&Kristallkugel `0mitnehmen','forest.php?op=stay&weapon=ball');
		addnav('Das `&Schwert `0mitnehmen','forest.php?op=stay&weapon=sword');
		addnav('Verschwinden','forest.php?op=run');
		
		
		break;
	}
} // Ende von groooßer switch

output ($str_output);
?>