<?php
/**
* nerwen.php: A village addition by Spider
* inspiration from the original fortune teller
* author unkown
* Nerwen will tell your fortune, and depending
* on how much money you offer her, you have a
* chance at a "better" fortune.
*
* @author Spider, translation and modification by Salator and talion
* @version DS-E V/2
*/

require_once 'common.php';
page_header('Nerwen\'s Zelt');
output('`c`b`zNerwen\'s Zelt`b`c`n');

if ($_GET['op']=='')
{
	output('`ZVessa deutet mit einem knappen Nicken in einen hinteren Teil des Zeltes, der durch mehrere Tücher vor unerwünschten Blicken schützt. Dort wartet bereits Nerwen Lossëhelin auf dich, als du eintrittst. Elfen sind schließlich nicht nur für ihre umwerfende Schönheit, sondern auch für ihr überaus gutes Gehör bekannt.
	Ihre tiefblauen Augen blicken geradewegs in deine und du hast das Gefühl, sie könnte direkt in deine Seele und deine Gedanken sehen.`n`n
	`["Ah, liebe'.($session['user']['sex']?' ':'r ').$session['user']['name'].'`[, wir haben uns ja lange nicht gesehen. Gut schaut Ihr aus! Doch sagt, habt Ihr etwas von meinem Bruder Golradir gehört? Die Bauarbeiten an seinem Haus sind fast fertig und seine kleine Zuflucht wird öffnen, sobald er zurück ist."
	`n`n`ZNerwen hält einen Moment inne, noch immer in deine Augen blickend. `["Nein, ich sehe Ihr wisst auch nichts über ihn. Aber ich fühle seine baldige Rückkehr."
	`n`n`ZEinen kurzen Moment wirkt sie gedankenverloren und weit weg, doch sogleich fährt sie auch schon wieder fort. `["Nun gut, genug Worte über meinen verirrten Bruder. Ich nehme an, Ihr seid zu mir gekommen, um etwas mehr über Euch zu erfahren? Ich kann Euch ein wenig aus Eurer Zukunft enthüllen, wenn Ihr mir ein wenig von eurem Gold gebt."');
	addnav('Z?Frage nach deiner Zukunft','nerwen.php?op=future');
}
else if ($_GET['op']=='future')
{
	output('`ZBegierig, etwas über deine Zukunft zu erfahren, fragst du Nerwen, wieviel "ein wenig" ist.`n`["Mein Kind, ein wenig ist so viel, wie Ihr mir geben wollt. Die Frage ist, wie freigiebig seid Ihr heute?"`n`n');
	output('`ZWieviel Gold willst du Nerwen geben?`n');
	output("<form action='nerwen.php?op=future2' method='POST'><input id='input' name='amount' width=5 accesskey='h'> <input type='submit' class='button' value='weggeben'></form>",true);
	JS::Focus('input');
	addnav('','nerwen.php?op=future2');
}
else if ($_GET['op']=='future2')
{
	$offer=abs(intval($_POST['amount']));
	if ($offer==0)
	{
		output('`ZNerwen schaut dich streng an.`n`["Ihr wollt mich zum Narren halten, oder vielleicht denkt Ihr, mit mir kann man Scherze machen. Verschwindet aus meinem Zelt und kommt wieder, wenn Ihr Manieren gelernt habt!"');
	}
	else if ($offer<100)
	{
		output('`ZNerwen schaut auf den kläglichen Goldhaufen, den du ihr anbietest und schüttelt den Kopf.`n`["Tut mir leid mein Kind, aber das ist einfach nicht genug. Dies ist meine Art, den Lebensunterhalt zu verdienen und für so wenig kann ich das nicht machen."');
	}
	else if ($offer>$session['user']['gold'])
	{
		output('`ZNerwen schaut dich streng an.`n`["Ich denke, Ihr braucht etwas Nachhilfe in der Zahlenlehre, bevor Ihr euch mit der Zukunft beschäftigt. Wie könnt Ihr mir etwas geben, was ihr nicht habt?"');
		addnav('nochmal versuchen','nerwen.php?op=future');
	}
	else
	{
		$max=min(ceil($offer/100),15);
		$session['user']['gold']-=$offer;
		output('`ZNerwen nimmt dein Gold, lächelt und schaut dir tief in die Augen.`n`n');
		$fortune = e_rand(1,$max);
		$debugtext=array('0','sehr schlecht','-2WK','1 Charm','-500 Bank-Gold','-1ES','Niete','1000 Bank-Gold','2WK','1ES','200LP','1 Charm','-50%LP','Trunkenheit','50LP','sehr gut');
		debuglog('gab '.$offer.' an Nerwen, bekam Ereignis '.$fortune.' von '.$max.' ('.$debugtext[$fortune].')');
		switch ($fortune)
		{
		case 1:
			output('`["Heute sieht es gar nicht gut aus für Euch, tut mir schrecklich leid."');
			$session['user']['hitpoints']=1;
			$session['user']['gold']-=100;
			$session['user']['charm']-=1;
			$session['user']['gems']-=1;
			if ($session['user']['gold'] < 0)
			{
				$session['user']['gold'] = 0;
			}
			if ($session['user']['gems'] < 0)
			{
				$session['user']['gems'] = 0;
			}
			break;
		case 2:
			output('`["Mein Kind, Ihr werdet heute zeitig schlafen gehen."');
			$session['user']['turns']-=2;
			if ($session['user']['turns'] < 0)
			{
				$session['user']['turns'] = 0;
			}
			break;
		case 3:
		case 11:
			output('`["Euer Tag wird sich in Liebesdingen großartig entwickeln."');
			$session['user']['charm']++;
			break;
		case 4:
			output('`["Ich fürchte, Ihr werdet heute etwas verlieren. Ich kann aber nicht sehen, was es ist."');
			$session['user']['goldinbank']-=500;
			break;
		case 5:
			$sql='SELECT houseid, gems FROM houses WHERE owner='.$session['user']['acctid'];
			$result=db_query($sql);
			$row=db_fetch_assoc($result);
			if ($row['gems']>0)
			{
				$row['gems']--;
				$sql = 'UPDATE houses SET gems='.$row['gems'].' WHERE houseid='.$row['houseid'];
				db_query($sql);

				insertcommentary(1,'/msg Eine Elster landet am offenen Fenster, fliegt zur Schatztruhe und schnappt sich einen Edelstein.','house-'.$row['houseid']);

				output('`["Ich fürchte, Euer Haus wird heute bestohlen."');
			}
			else
			{
				output('`["Ich fürchte, man wird Euch heute auf hinterhältige Weise umbringen."');
			}
			break;
		case 7:
			output('`["Ihr werdet im Laufe des Tages eine freudige Überraschung erleben."');
			$session['user']['goldinbank']+=1000;
			break;
		case 8:
		case 20:
			output('`["Frische Kraft durchströmt Euch, Euer Tag wird lang und produktiv."');
			$session['user']['turns']+=2;
			break;
		case 9:
			$sql='SELECT houseid, gems FROM houses WHERE owner='.$session['user']['acctid'];
			$result=db_query($sql);
			$row=db_fetch_assoc($result);
			if ($row['houseid'])
			{
				$row['gems']++;
				$sql = 'UPDATE houses SET gems='.$row['gems'].' WHERE houseid='.$row['houseid'];
				db_query($sql);

				insertcommentary(1,'/msg Ein Edelstein fällt vom Himmel und kullert direkt vor die Schatztruhe.','house-'.$row['houseid']);

				output('`["Ihr werdet zuhause eine freudige Überraschung erleben."');
			}
			else
			{
				output('`["Ich sehe, Ihr werdet heute etwas Wertvolles finden"');
				item_add($session['user']['acctid'],'glasfigur');
			}
			break;
		case 10:
			output('`["Ich sehe heute neue Kräfte in Euch erwachen."');
			$session['user']['hitpoints']+=200;
			break;
		case 12:
			output('`["Ich sehe, Ihr fühlt Euch heute nicht so gut. Hoffentlich geht es Euch bald besser."');
			$session['user']['hitpoints']*=0.5;
			if ($session['user']['hitpoints'] < 0)
			{
				$session['user']['hitpoints'] = 1;
			}
			break;
		case 13:
			output('`["Ich empfehle Euch, sich heute von der Schenke fernzuhalten. In Eurem Zustand seid Ihr dort nicht sehr willkommen."');
			$session['user']['drunkenness']=80;
			break;
		case 14:
			output('`["Ihr seid heute gesegnet, genießt es, solange es anhält."');
			$session['user']['hitpoints']+=50;
			break;
		case 15:
			output('`["Euer Tag sieht sehr vielversprechend aus. Nun geht und macht das Beste daraus."');
			$session['user']['hitpoints']+=10;
			$session['user']['gold']+=100;
			$session['user']['charm']+=1;
			$session['user']['gems']+=1;
			break;
			default:
			output('`["Ich fürchte, Eure Zukunft ist heute etwas vernebelt! Mehr kann ich nicht sehen."');
		}
	}
}
addnav('M?Zurück zum Markt','market.php');

page_footer();
?>