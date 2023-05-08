<?php
/* *******************
by Fly
on 09/18/2004

little modyfications by Hadriel
******************* */
if (!isset($session)) exit();
if ($_GET['op']=='')
{
	output('`3Während deiner Suche siehst du einen alten `q ledernen Stiefel `3 unter einer Wurzel.
	`nWillst du ihn untersuchen?`n');
	addnav('Untersuchen','forest.php?op=try');
	addnav('Weitergehen','forest.php?op=back');
	$session['user']['specialinc']='stiefel.php';
}
else if  ($_GET['op']=='back')
{
	output('`3Du gehst zurück in den Wald.');
//	addnav('Zurück in den Wald','forest.php');
	$session['user']['specialinc']='';
}
else  if ($_GET['op']=='try')
{
	switch (e_rand(1,5))
	{
		case 1:
		case 2:
		case 3:
		output('`3Im Stiefel befindet sich eine alte, stinkende Socke.`n
		Der Gestank treibt dir Tränen in die Augen. Trotzdem gibst du die Hoffnung nicht auf, noch was zu finden.`n`n');
		$session['bufflist']['augen'] = array('name'=>'`4tränende Augen',
				'rounds'=>20,
				'wearoff'=>'Du kannst wieder klar sehen!',
				'defmod'=>0.96,
				'atkmod'=>0.92,
				'roundmsg'=>'Deine tränenden Augen behindern dich',
				'activate'=>'defense');
		break;
		case 4:
		case 5:
		output('`3 Du greifst in den Stiefel`n`n');
		break;
	}
	switch (e_rand(1,7))
	{
		case 1:
		case 2:
		case 3:
			$win = e_rand(1,2)*$session['user']['level']*10;
			output('`3und du findest `^'.$win.' Gold!`3
			`n`nDu nimmst den Stiefel mit und gehst zurück in den Wald.');
			$session['user']['gold']+= $win;
			//addnav('Zurück in den Wald','forest.php');
			$session['user']['specialinc']='';
			$item['tpl_name'] = '`qAlter Stiefel`0';
			$item['tpl_gold'] = e_rand(1,10)*5;
			$item['tpl_description'] = '`3der Größe '.e_rand(15,50).'`0';
			item_add($session['user']['acctid'],'beutdummy',$item);
		break;
		case 4:
		case 5:
			output('`3und du findest `^einen Edelstein!`3.
			`n`nDu nimmst den Stiefel mit und gehst zurück in den Wald.');
			$session['user']['gems']++;
			//addnav('Zurück in den Wald','forest.php');
			$session['user']['specialinc']='';
			$item['tpl_name'] = '`qAlter Stiefel`0';
			$item['tpl_gold'] = e_rand(1,10)*5;
			$item['tpl_description'] = '`3der Größe '.e_rand(15,50).'`0';
			item_add($session['user']['acctid'],'beutdummy',$item);
		break;
		case 6:
			output('`3und du findest nix!
			`n`nDu nimmst den Stiefel mit und gehst zurück in den Wald.');
			//addnav('Zurück in den Wald','forest.php');
			$session['user']['specialinc']='';
			$item['tpl_name'] = '`qAlter Stiefel`0';
			$item['tpl_gold'] = e_rand(1,10)*5;
			$item['tpl_description'] = '`3der Größe '.e_rand(15,50).'`0';
			item_add($session['user']['acctid'],'beutdummy',$item);
		break;
		case 7:
			output('`3Du findest ein Stück Gold! Als du die Reinheit mit einem Biss in das Stück feststellen willst, `4vergiftet dich der lautlose Pfeil eines Räubers!
			`n`3Der Räuber beugt sich über dich, eiskalt hat er dich erwischt. Nachdem er deinen toten Körper eine Weile durchsucht hat, hat er auch das beste Versteck für Goldmünzen gefunden und jede einzelne an sich genommen.
			`n`n`4Du verlierst all dein Gold...');
			killplayer(100,0,0,'news.php','Tägliche News');
			addnews('`3'.$session['user']['name'].'`0 wurde mit einem Pfeil im Rücken aufgefunden.');
		break;
	}
}
?>