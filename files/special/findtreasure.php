<?php

// gefunden auf http://www.lotgd.de
//
// Modifikationen von warchild & anpera
// mit einer Idee von Manwe

if (!isset($session)) exit();

$specialinc_file = 'findtreasure.php';

if ($_GET['op'] == 'baum')
{
	output('`2Als du den Baum näher untersuchst, merkst du, dass der Baum innen hohl ist. Du fasst in den hohlen Baum und ');
	$baumsuche = e_rand(1,12);
	$session['user']['specialinc'] = ''; 
	switch($baumsuche){
		case 1:
		case 2: 
		output('holst ein zusammengerolltes Pergament heraus.
		`n`n`2Du kannst es lesen oder es wegwerfen und deinen Weg weitergehen`0');
		addnav('Pergament lesen','forest.php?op=lesen');
		addnav('Zurück in den Wald','forest.php?op=back');
		$session['user']['specialinc'] = $specialinc_file; 
		break;
		case 3:
		output('du streifst dabei einen der `2weißen Pilze`7, der daraufhin mit einem leisen \'Plopp!\' eine riesige Menge Sporen auspustet, die du unglücklicherweise einatmest!
		Ausgestreckt auf dem Baum liegend siehst du grelle Farben und hörst leise Musik während die Zaubersporen deine Erinnerungen vernichten und du dich beim Aufwachen fragst, wie du hier hergekommen bist.
		`n`n`^Du verlierst einen Waldkampf und 5% Deiner Erfahrung!');
		$session['user']['experience'] = round($session['user']['experience'] * 0.95);
		$session['user']['turns']--;
		break;
		case 4:
		case 5: 
		output('traust deinen Augen nicht. Du ziehst die Hand heraus und es liegt `#ein Edelstein`2 darin!');
		$session['user']['gems']++;
		break;
		case 6:
		case 7:
		case 8:
		$amt=e_rand(10,90)*$session['user']['level'];
		output('findest nichts. Du gehst rechts um den Baumstamm herum und findest in einem kleinen Versteck `^'.$amt.' `2Gold.');
		$session['user']['gold']+=$amt;
		break;
		case 9:
		case 10:
		case 11:
		output('spürst einen brennenden Schmerz in deiner Hand! Eine Schlange bewohnt diesen Baumstamm und hat dir in die Hand gebissen. Dir wird schwarz vor Augen. Das Gift wirkt.
		`n`nAls du wieder zu dir kommst, fühlst du dich schwach. Aber du hast überlebt.`n');
		if ($session['user']['hitpoints']>2)
		{
			output('`4Du hast die meisten deiner Lebenspunkte verloren.');
			$session['user']['hitpoints']=2;
		}
		break;
		case 12:
		output('du fasst direkt mitten in ein `4Wespennest! `2Zornig aufgeweckt stürzen sich die Wespen wutentbrannt auf dich und zerstechen deine Arme, dein Gesicht und deinen Hals!
		`n`n`^Du verlierst einen Charmepunkt und einige Lebenspunkte!');
		$session['user']['charm']=max(0,$session['user']['charm']-1);
		$session['user']['hitpoints'] = round($session['user']['hitpoints']* 0.6);
		break;
	}
	// addnav('Weg weitergehen','forest.php');
}

else if ( $_GET['op'] == 'lesen')
{
	output('`2Auf dem Pergament siehst du eine Karte dieser Gegend. Auf der Karte ist ein \'`4X`2\' markiert. Es scheint eine Schatzkarte zu sein!
	`n`nAm markierten Weg erkennst du, dass dich die Schatzsuche fast den gesamten restlichen Tag kosten würde.');
	$session['user']['specialinc'] = $specialinc_file; 
	addnav('Schatz suchen','forest.php?op=schatzsuche');
	addnav('Weg weitergehen','forest.php?op=back');
}

else if ( $_GET['op'] == 'schatzsuche')
{
	if ($session['user']['turns']<1)
	{
		output('`2Leider hast du heute nicht mehr genug Zeit übrig, die du zum Suchen nach dem Schatz verwenden könntest.');
	}
	else
	{
		$runden=e_rand(1,$session['user']['turns']);
		$foundgold = 100 * e_rand(1,$runden) * $session['user']['level'];
		$foundgold = round(e_rand(($foundgold >> 1) , $foundgold));
		if ( e_rand(1,4) == 1 ) $foundgold *= 10;
		
		$arr_items=array //Salator hat heute nen Kasper gefrühstückt
		(
			array('tpl_name' => '`TZ`Qo`Tnk`0'
				,'text' => 'einen `TZ`Qo`Tnk'
				,'tpl_gold' => e_rand(1000,2000)
				,'tpl_description' => 'Ein schwarzpelziges Plüschtier mit übermäßig langer Nase. Es grinst dich hämisch an.')
			,array('tpl_name' => '`TVoodoo-Puppe`0'
				,'text' => 'eine dir ähnlich sehende `TVoodoo-Puppe'
				,'tpl_gold' => e_rand(10,200)
				,'tpl_description' => 'Ein Püppchen, wie es oft bei finsteren Ritualen verwendet wird. Es sieht beinahe aus wie '.strip_appoencode($session['user']['name']))
			,array('tpl_name' => '`qAlte Latschen`0'
				,'text' => 'ein paar `qalte Latschen'
				,'tpl_gold' => e_rand(2,20)
				,'tpl_description' => '`3der Größe '.e_rand(15,50))
			,array('tpl_name' => '`&Melkfett`0'
				,'text' => 'einen Napf voll `&Melkfett'
				,'tpl_gold' => e_rand(20,100)
				,'tpl_description' => 'Eine Substanz, welche der Viehbauer benutzt, damit die Kuh beim Melken nicht quietscht.')
			,array('tpl_name' => '`7Fußball`0'
				,'text' => 'einen `7Fußball'
				,'tpl_gold' => 211
				,'tpl_description' => 'Ein rundes Ding, welches sich hervorragend als Ball verwenden lässt. Hergestellt aus echten Füßen.')
			,array('tpl_name' => '`yCompute`0'
				,'text' => 'eine `yCompute'
				,'tpl_gold' => 42
				,'tpl_description' => 'Eine sehr seltene Vogelart. Wenn du jetzt noch den zugehörigen Computer hättest, könntest du eine Computenzucht aufmachen.')
			,array('tpl_name' => '`/Wachskerze`0'
				,'text' => 'eine magische `/Wachskerze'
				,'tpl_gold' => 15
				,'tpl_description' => 'Diese Kerze wächst auf magische Weise, wenn man sie gelegentlich in flüssiges Bienenwachs tunkt.')
		);
		if($foundgold<=$session['user']['level']*51) //Zonk
		{
			$foundgold=0;
			$itemselect=0;
		}
		else
		{
			$itemselect=e_rand(1,(count($arr_items)-1));
		}
		
		output('`2Auf der Suche nach dem `4X`2 auf der Karte verbrauchst du `@'.$runden.' `2Waldkämpfe. Schliesslich findest du die Stelle und fängst auch sofort an, mit '.$session['user']['weapon'].'`2 nach dem hier vermuteten Schatz zu graben. Schon nach kurzer Zeit stößt du auf eine große, beschlagene Holzkiste. Als du die Kiste öffnest, lächelt dich ein kleines Vermögen an:
		`n`nIn der Schatztruhe findest du `^'.$foundgold.' Goldstücke`2 und '.$arr_items[$itemselect]['text'].'`2. Nach einem kleinen Freudentanz machst du dich zurück auf deinen Weg.');
		addnews('`0In einer stürmischen Nacht findet '.$session['user']['name'].' einen riesigen Schatz von `^'.$foundgold.'`0 Goldstücken.');
		$session['user']['gold'] += $foundgold;
		$session['user']['turns']-=$runden;
		$session['user']['reputation']++;
		item_add($session['user']['acctid'],'beutdummy',$arr_items[$itemselect]);
	}
	$session['user']['specialinc'] = ''; 
	//addnav('Zurück in den Wald','forest.php?op=back');
}

else if ( $_GET['op'] == 'back')
{
	output('`2Du begibst dich wieder auf den Weg, von dem du gekommen bist und gehst weiter auf die Suche nach Abenteuern.');
	$seesion['user']['specialinc'] = '';
	forest(true);
	// addnav('Zurück in den Wald','forest.php');
}

else
{
	output('`2Am Rande des Weges fällt dir ein umgefallener hohler Baum auf, der irgendwie nach einem Abenteuer riecht.
	`n`nDu kannst den Baum näher untersuchen oder weiter deines Weges gehen.`0');

	$session['user']['specialinc'] = $specialinc_file;
	addnav('u?Baum untersuchen','forest.php?op=baum');
	addnav('Weg weitergehen','forest.php?op=back');
	//addnav('debug','forest.php?op=schatzsuche');
}

?>