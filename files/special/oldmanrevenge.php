<?php
// Rache am alten Mann
// by Maris (Maraxxus@gmx.de)

if (!isset($session)) exit();
$session['user']['specialinc']='oldmanrevenge.php';

switch ($_GET['op'])
{

case 'push':
	output("`^Du nimmst etwas Anlauf und ");
	if (e_rand(1,3)==2)
	{
		output(" musst feststellen, dass sich der alte Kerl in einer plötzlichen Bewegung wegdreht und du selbst das Gleichgewicht verlierst.
		`n`n`4Du stürzt einen steilen Hang hinab und kommst in einem Strauch zum liegen. Du brauchst `\$3 weitere Runden `4um wieder zurück zum Weg zu finden.");
		$session['user']['turns']=max(0,$session['user']['turns']-3);
		addnav("Verdammt!","forest.php?op=leave");
	}
	else
	{
		output(" gibst dem Alten einen kräftigen Schubser, welcher nun munter den steilen Hang hinab purzelt.`nSeinem lauten Schimpfen und Fluchen lauschst du noch ein Weile vergnügt und setzt dann deinen Weg fort.`n`nHach... böse sein mach einfach nur Spass!`nDu erhältst `@2 Runden`^ dazu!`n");
		$session['user']['turns']+=2;
		addnav("Schön!","forest.php?op=leave");
	}
break;

case 'beat':
	output("`^Du ballst die Hände zu Fäusten und stürzt dich auf den gemeingefährlichen... alten Mann.`n");
	if (e_rand(1,3)==2)
	{
		output("`4Nur leider scheint es, als hast du hier einen dieser alten chinesischen Meister erwischt, welcher dich nun nach allen Regeln der Kunst auseinander nimmt.
		`nHalb tot schleppst du dich davon und kassierst sogar noch einen Schlag mit dem hässlichen Stock.
		`n`nDu verlierst `\$einen Charmepunkt`4 und hast nur noch `\$einen Lebenspunkt`4 übrig.");
		$session['user']['charm']=max(0,$session['user']['charm']-1);
		$session['user']['hitpoints']=1;
		addnav("Autsch!","forest.php?op=leave");
	}
	else
	{
		output("So viel Spaß hattest du schon lange nicht mehr! Es ist dir ein leichtes, den armen alten Kerl windelweich zu prügeln.`nEin schlechtes Gewissen hast du dabei auch nicht, denn immerhin ist er ja der böse alte Mann mit dem hässlichen Stock...`n`nAls Beweis für deine \"Heldentat\" nimmst du dir eine kleine Trophähe von ihm mit.");
		$trophy = item_get_tpl(' tpl_id="trph" ' );
		$roll = e_rand(1,4);
		
		switch ($roll)
		{
			case 1:
			$trophy['tpl_name']='Die `5Pantoffeln`& des alten Mannes';
			$trophy['tpl_description']='Die `5Pantoffeln`& des alten Mannes. Erworben in einem ... in einem Kampf.';
			$trophy['tpl_hvalue2']=1;
			break;
			
			case 2:
			$trophy['tpl_name']='Der `5Hörtrichter`& des alten Mannes';
			$trophy['tpl_description']='Der `5Hörtrichter`& des alten Mannes. Erworben in einem ... in einem Kampf.';
			$trophy['tpl_hvalue2']=2;
			break;

			case 3:
			$trophy['tpl_name']='Das `5Gebiss`& des alten Mannes';
			$trophy['tpl_description']='Das `5Gebiss`& des alten Mannes. Erworben in einem ... in einem Kampf.';
			$trophy['tpl_hvalue2']=3;
			break;
		
			case 4:
			$trophy['tpl_name']='Der `5Skalp`& des alten Mannes';
			$trophy['tpl_description']='Der `5Skalp`& des alten Mannes. Erworben in einem ... in einem Kampf.';
			$trophy['tpl_hvalue2']=4;
			break;
		}
		$trophy['tpl_gold']=100*$roll;
		item_add($session['user']['acctid'],0, $trophy);
		addnav("Muhaha!","forest.php?op=leave");
	}
break;

case 'stick':
	output("`^Du näherst dich langsam und arglistig dem nichtsahnenden alten Mann, um ihm diesen hässlichen Stock ein für allemal abzunehmen.
	`nEs gelingt dir sogar nach einer kurzen Rangelei, den hässlichen Stock zu zerbrechen.`n`n");
	if (e_rand(1,3)==2)
	{
		output("`4Leider stellt es sich heraus, dass du gerade irgendeinem unschuldigen alten Mann seine Gehhilfe zerstört hast!
		`nSchäm dich! Du verlierst dafür etwas Ansehen!`n");
		addnews('`5Gemein! '.$session['user']['name'].' `5nimmt einem alten Mann den Gehstock weg!');
		$session['user']['reputation']-=10;
		addnav("Oh je!","forest.php?op=leave");
	}
	else
	{
		output("Der Alte murrt dich an und kündigt an, sich sofort einen neuen, noch hässlicheren Stock zu suchen und dir damit im Wald aufzulauern!
		`nImmerhin erhöht die Zerstörung des Stockes deinen Charme um 1 und dein Ansehen in der Stadt steigt auch!`n");
		$session['user']['reputation']+=10;
		$session['user']['charm']++;
		addnav("Ausgezeichnet!","forest.php?op=leave");
	}
break;

case 'greet':
	output("`^Du schreitest an dem alten Mann vorbei und grüßt ihn freundlich.
	`nAlsdann beginnt der Alte, dich ohne Chance auf ein Entkommen in ein Gespräch zu verwickeln.`n`n");
	if (e_rand(1,3)==2)
	{
		output("`42 Runden später und als dir bereits Blut aus den Ohren läuft ist das Gespräch vorüber und der Alte lässt dich ziehen.");
		$session['user']['turns']=max(0,$session['user']['turns']-2);
		addnav("Weg hier!","forest.php?op=leave");
	}
	else
	{
		$gain = round($session['user']['experience']*0.15);
		output("Du kannst vom reichhaltigen Erfahrungsschatz des Alten schöpfen.
		`nSeine Geschichten bringen dir $gain Erfahrungspunkte!`n");
		$session['user']['experience']+=$gain;
		addnav("Weiter","forest.php?op=leave");
	}
break;

case 'leave':
	$session['user']['specialinc']='';
	redirect("forest.php");
break;

default:
	output("`^Du siehst einen alten Mann mit einem hässlichen Stock einsam an einer Felsenklippe stehen und die Ferne schauen.
	`nIrgendwie hast du das Gefühl, dass du ihn schonmal irgendwo gesehen hast.
	`n`nWie willst du ihm begegnen?`n");
	addnav("Gewaltsam");
	addnav("Schubsen","forest.php?op=push");
	addnav("Verprügeln","forest.php?op=beat");
	addnav("Stock wegnehmen","forest.php?op=stick");
	addnav("Freundlich");
	addnav("Grüßen","forest.php?op=greet");
	addnav("Gar nicht");
	addnav("Weitergehen","forest.php?op=leave");
break;
}
?>
