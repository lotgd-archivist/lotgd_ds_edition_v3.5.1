<?php
/**
 * @desc Sonntag nacht, halb 3 - Charlie  ist gerettet und ich glaub im Sushi war irgendwo Kugelfisch *burps*
 * @longdesc Tu was gutes für die Nase, zieh Wasabi durch die Nase - Prost!
 * @author Maris, Dragonslayer + Caffeine Rush!!!
 * @copyright Atrahor, DS V2.5
 */


page_header('Kneipenschlägerei');
$session['user']['specialinc'] = basename(__FILE__);

$str_output = '';


switch ($_GET['sop'])
{
	case 'brawl':
	{
		$str_output .= 'Und schon ergibt sich die schönste Keilerei. Du und dein dickbäuchiger "Fausttanzpartner" lassen es so richtig krachen.`n'; 
		switch (e_rand(0,5))
		{
			case 0:
			{
				$str_output .= 'Dabei gehen selbstverständlich ein paar Stühle und auch Gläser zu Bruch... zu dumm, 
				dass das leider nicht mehr durch deine private Hausratversicherung gedeckt wird. Jedefalls nicht
				 mehr seit diesem ... `iVorfall`i... Cedrick schreibt dir 100 Goldmünzen auf deinen Bierdeckel an!';
				$session['user']['gold'] -= 100;
				if($session['user']['gold']<0)
				{
					$session['user']['gold'] = 0;
				}
				$session['user']['specialinc'] = '';
				addnav('Yeah!','inn.php');
			}
			break;
			
			case 1:
			{
				$str_output .= 'Der Hühne gerät ins Wanken und fällt schließlich rücklinks auf den Boden... ob der Tritt in die Weichteile wohl damit zu tun hatte?';
				$session['user']['specialinc'] = '';
				addnav('Yeah!','inn.php');
			}
			break;
			
			case 2:
			{
				$str_output .= 'Ihr prügelt so eine Weile vor euch hin...aber da kein anderer Lust zu haben scheint,
				sich einzumischen und auch die Wettquoten schlecht sind, beschließt ihr lieber, euch sinnlos zu besaufen.';
				$session['user']['drunkenness'] += 30;
				$session['user']['specialinc'] = '';
				addnav('Prost!','inn.php');
			}
			break;
			
			case 3:
			{
				$str_output .= 'Völlig wider erwarten und abseits jeglicher guten Manieren, die bei einer ordentlichen Schlägerei gelten, 
				reicht dir der Hüne die Hand, entschuldigt sich brav und gibt dir 100 Goldmünzen für die Reinigung!'; 
				$session['user']['gold'] += 100;
				$session['user']['specialinc'] = '';
				addnav('Was für ein Weichei','inn.php');
			}
			break;
			
			case 4:
			{
				$str_output .= 'Ein Dampfhammer schickt dich ins Land der Träume. Als du wieder aufwachst liegen du und ein paar abgebrochene Stuhlbeine auf der Straße,
				nur deine Hosen haben es nicht geschafft, die liegen in der Regenrinne und dienen jetzt einem Vogel aus Nest.';
				addnews('`@'.$session['user']['name'].'`@ ist gut zu Vögeln',$session['user']['acctid']);
				$session['user']['specialinc'] = '';
				addnav('Aufstehen','village.php');
			}
			break;
			
			case 5:
			{
				if ($session['user']['profession']==21 || $session['user']['profession']==22)
				{
					$str_output = 'Eine schöne Kneipenschlägerei macht umso mehr Spass je mehr Leute daran teilnehmen.
					Das dachte sich auch der freundliche Stadtgardist, der dir zuerst einen Stuhl über die Rübe zieht und dich in den Kerker stecken will, doch dann schnell das Weite sucht als er merkt dass er einen Richter vor sich hat.`n
					Wenn man es bedenkt war es ein erfolgreicher Tag. Erst ordentlich prügeln dürfen und dann noch die Stadtwache erschrecken...';
					$session['user']['specialinc'] = '';
					addnav('Raus hier','village.php');
				}
				else
				{
					$str_output = 'Eine schöne Kneipenschlägerei macht umso mehr Spass je mehr Leute daran teilnehmen.
					Das dachte sich auch der freundliche Stadtgardist, der dir zuerst einen Stuhl über die Rübe zieht und dich dann an den Ohren in den Kerker schleift.`n
					Wenn man es bedenkt war es ein erfolgreicher Tag. Erst ordentlich prügeln dürfen und dann noch den Abend in der gemütlichen Zelle ausklingen lassen.`n
					War heute nicht Sumpfsuppentag?';
					$session['user']['specialinc'] = '';
					$session['user']['imprisoned'] = 1;
					addnav('Lecker','prison.php');
					addnews('`5'.$session['user']['name'].'`3 wurde mitsamt einigen Rüpeln aus der Kneipe verhaftet.');
				}
			}
			break;
		}
	}
	break;
	
	case 'nothing':
	{
		$str_output .= 'Da nun mehr als nur ein paar Finger auf dich zeigen und das Gegacker immer lauter wird, verlässt du beschämt die Kneipe';
		$session['user']['specialinc'] = '';
		addnews('`@'.$session['user']['name'].'`@ wurde in der Schenke ausgegackert',$session['user']['acctid']);
		addnav('Beschämt rausgehen','village.php');
	}
	break;
	
	default:
	{
		$str_output .= 'Unweit von dir gerät ein grobschlächtiger Met-bevorzugender Hüne mit dichtem 
		Bart und dicken Oberarmen ins stolpern und giesst den Inhalt seines Füllhornes über dein '.$session['user']['armor'].'.
		`nDer offensichtlich betrunkene und schwer streitsüchtige Herr grinst dich hämisch an und spuckt den Inhalt seiner vollen Backen direkt hinterher.`n
		Du wischst dir die streng riechende Flüssigkeit aus den Augen und triffst eine Entscheidung...';
		
		addnav('Prügeln & Kneifen');
		addnav('Zuschlagen','inn.php?sop=brawl');
		addnav('Blind drauflos prügeln','inn.php?sop=brawl');
		addnav('Energisch wegschubsen','inn.php?sop=brawl');
		addnav('Mit Stuhlbein streicheln','inn.php?sop=brawl');
		addnav('Ruppige Weichteilmassage','inn.php?sop=brawl');
		addnav('Fingernägel fliegen lassen','inn.php?sop=brawl');
		addnav('Üblen Tittytwister ausüben','inn.php?sop=brawl');
		
		addnav('Pazifismus zelebrieren');
		addnav('An die Decke gucken','inn.php?sop=nothing');
		addnav('Weggehen','inn.php?sop=nothing');
		addnav('Ruhig bis 10 zählen','inn.php?sop=nothing');
		addnav('Dich entschuldigen','inn.php?sop=nothing');
		addnav('Dich bedanken','inn.php?sop=brawl');
		addnav('Treudoof lächeln','inn.php?sop=nothing');
		if($session['user']['exchangequest']==13)
		{
			addnav('`%Met nachfüllen`0','exchangequest.php');
		}
	}
}
output($str_output);
?>