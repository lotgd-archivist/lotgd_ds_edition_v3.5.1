<?php
/*****************************************/
/* Waterfall */
/* --------- */
/* Written by Kevin Kilgore */
/* (with some creative help by Jake Taft)*/
/* german translation by nTE */
/*****************************************/

$session['user']['specialinc']='waterfall.php';
switch ($_GET['op'])
{
case 'search':
case '':
	output('`2Du siehst einen kleinen Pfad, der vom Hauptweg abgeht. Der Pfad ist zugewachsen und du hättest ihn beim Vorbeischleichen fast nicht gesehen.
	`n`nWährend du dich hinunterkniest, um den Pfad näher zu betrachten, bemerkst du Fußabdrücke, die den Pfad entlang führen, aber merkwürdigerweise keine, die wieder zurück führen.
	`nWährend du den Pfad untersuchst, hörst du etwas, dass sich wie fließendes Wasser anhört.');
	addnav('Folge dem Pfad','forest.php?op=trail');
	addnav('Zurück in den Wald','forest.php?op=leave');
	$session['user']['specialinc']='waterfall.php';
	break;

case 'trail':
	output('`2Du entschließt dich, dem Pfad zu folgen und fängst an, die Gegend näher zu untersuchen...`n`n');
	$rand = e_rand(1,12);
	switch ($rand)
	{
	case 1:
	case 2:
	case 3:
	case 4:
	case 5:
		output("`n`2Nach ein paar Stunden des Suchens verläufst du dich.
		`n`n`7Du verlierst einen Waldkampf dabei, den Weg zurück zu finden.");
		$session['user']['turns']=max(0,$session['user']['turns']-1);
		$session['user']['specialinc']='';
		break;
	case 6:
	case 7:
	case 8:
		output('`^Nach ein paar Minuten des Erforschens findest du einen Wasserfall!
		`n`n`2Du bemerkst auch einen kleinen Vorsprung entlang der Steinoberfläche des Wasserfalls.
		`nOb du zum Vorsprung gehen solltest?');
		addnav('Gehe zum Vorsprung','forest.php?op=ledge');
		addnav('Zurück in den Wald','forest.php?op=leaveleave');
		break;
	case 9:
	case 10:
	case 11:
	case 12:
		output('`^Nach ein paar Minuten des Erforschens des Gebiets findest du einen Wasserfall!
		`n`2Durstig vom Herumlaufen überlegst du, ob du vielleicht einen Schluck Wasser trinken solltest.');
		addnav('Trinke einen Schluck Wasser','forest.php?op=drink');
		addnav('Zurück in den Wald','forest.php?op=leaveleave');
		break;
	default:
		output('Als die Kirchturmuhr dreizehn schlägt denkst du dir, hier ist irgendwas falsch, und gehst in die Stadt zurück.');
		$session['user']['specialinc']='';
		addnav('Zum Stadtzentrum','village.php');
		break;
	}
	break;

case 'ledge':
	$fall = e_rand(1,9);
	$session['user']['specialinc']='';
	switch ($fall)
	{
	case 1:
	case 2:
	case 3:
	case 4:
		$gems = e_rand(1,2);
		output('`7Du bewegst dich vorsichtig über die Steine, um hinter den Wasserfall zu gelangen und findest dort... `^'.($gems==1?'einen Edelstein!':$gems.' Edelsteine!'));
		$session['user']['gems'] += $gems;
		break;
	case 5:
	case 6:
	case 7:
	case 8:
		$lhps = round($session['user']['hitpoints']*0.25);
		$session['user']['hitpoints'] = round($session['user']['hitpoints']*0.75);
		output('`7Du gehst vorsichtig über die Steine, um hinter den Wasserfall zu gelangen, aber nicht vorsichtig genug!
		`nDu rutschst ab, fällst hinunter und verletzt dich.
		`n`n`4Du hast '.$lhps.' Lebenspunkte dabei verloren.');
		if ($session['user']['gold']>0)
		{
			$gold = round($session['user']['gold']*0.15);
			output('`n`4Du stellst außerdem fest, dass du '.$gold.' Gold während deines Sturzes verloren hast.');
			$session['user']['gold'] -= $gold;
		}
		break;
	case 9:
		output('`7Während du den Vorsprung entlanggehst, rutschst du aus und fällst hinab, prallst auf einige Steine unter dir auf und landest schlussendlich im Wasser!
		`n`n
		`n`4Du bist gestorben! Du kannst morgen wieder spielen.');
		killplayer(100,0,0,'news.php','Tägliche News');
		addnews($session['user']['name'].'s `%zerschundener Körper wurde, teils von Steinen begraben, unter einem Wasserfall gefunden.');
		break;
	default:
		output('Als vor dir zehn Steine herunterfallen denkst du dir, hier ist irgendwas falsch, und gehst in die Stadt zurück.');
		$session['user']['specialinc']='';
		addnav('Zum Stadtzentrum','village.php');
		break;
	}
	break;

case 'drink':
	$session['user']['specialinc']='';
	$cnt = e_rand(1,6);
	switch ($cnt)
	{
	case 1:
	case 2:
	case 3:
		output('`2Du trinkst vom Wasser und fühlst dich erfrischt!
		`n`n`^Deine Lebenspunkte wurden vollständig aufgefüllt!');
		if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
		{
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
		}
		break;
	case 4:
		output('`2Du gehst zum Fuße des Wasserfalls und nimmst einen tiefen Schluck des klaren Wassers.
		`nWährend du trinkst, spürst du ein kribbelndes Gefühl das sich in deinem ganzen Körper ausbreitet...
		`nDu fühlst dich erfrischt und gesünder als je zuvor!
		`n`n`^Deine Lebenspunkte wurden vollständig aufgefüllt und deine maximalen Lebenspunkte wurden `bpermanent`b um `71 `^erhöht!');
		$session['user']['maxhitpoints']++;
		$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
		break;
	case 5:
	case 6:
		output('`2Du trinkst von dem Wasser und beginnst dich seltsam zu fühlen. Du setzt dich und wirst krank.
		`n`4Du verlierst einen Waldkampf während du dich langsam wieder erholst!');
		if ($session['user']['turns']>0)
		{
			$session['user']['turns']--;
		}
		break;
	default:
		output('Als du den Wasserfall komplett leer getrunken hast denkst du dir, hier ist irgendwas falsch, und gehst in die Stadt zurück.');
		$session['user']['specialinc']='';
		addnav('Zum Stadtzentrum','village.php');
		break;
	}
	break;

case 'leave':
	output('`2Du starrst für einen Moment auf den Pfad, um den Mut zu bekommen, ihn zu erforschen.
	Ein kalter Schauer läuft dir den Rücken runter und du musst unwillkürlich zittern.
	Du entscheidest dich, auf dem Hauptweg zu bleiben und siehst zu, dass du zügig Abstand zu dem mysteriösen Pfad gewinnst.');
	$session['user']['specialinc']='';
	break;

case 'leaveleave':
	output('`2Du entscheidest, dass Vorsicht der bessere Teil des Heldenmuts ist, oder zumindest des Überlebens und kehrst zum Wald zurück.');
	$session['user']['specialinc']='';
	break;

default:
	output('Du kannst dir nicht erklären, wie du an diese Stelle gekommen bist und denkst dir, hier ist irgendwas falsch. Also gehst du in die Stadt zurück.');
	$session['user']['specialinc']='';
	addnav('Zum Stadtzentrum','village.php');
	break;
}
output('`0`n');
?>
