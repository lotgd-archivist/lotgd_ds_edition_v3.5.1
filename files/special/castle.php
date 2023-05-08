<?php

// 25082004

// originally found at www.lotgd.com
// changes & translation by anpera
// additional changes by nTE

checkday();
page_header("Die Burg");
$session['user']['specialinc']='castle.php';
$runden=$session['user']['turns'];
$castleoptions = utf8_unserialize($session['user']['specialmisc']);
if (!is_array($castleoptions) || $castleoptions['castleinit'] != 1)
{
	$castleoptions = array();
	$castleoptions['beautyshop_uses'] = 0;
	$castleoptions['well_uses'] = 0;
	$castleoptions['castleinit'] = 1;
	$castleoptions['gems_onenter']=$session['user']['gems'];
	$castleoptions['navigatorhead']=item_count('tpl_id="navikopf" AND owner='.$session['user']['acctid']); //User hat ein Item "Kopf des Navigators" vom beleidgterpirat-Special
}
$session['user']['specialmisc'] = utf8_serialize($castleoptions);

function castlenav($what='', $runden=0)
{
	global $session;
	switch ($what)
	{
	case 'main':
		addnav('Burghof');
		addnav('Wunschbrunnen (1 Edelstein)','forest.php?op=well');
		addnav('Glücksspieler','stonesgame.php');
		//addnav('Schießbude','forest.php?op=bar');
		if ($runden>0)
		{
			addnav('Übungsraum','forest.php?op=train');
		}
		addnav('Shops');
		addnav('Waffenschmied','forest.php?op=blacksmith');
		addnav('Rüstungsschmied','forest.php?op=armourer');
		addnav('Kalas Beautyshop','forest.php?op=medicine');
		addnav('Sonstige');
		if ($runden>0)
		{
			addnav('Katakomben betreten...','forest.php?op=catacombs');
		}
		addnav('v?Burg verlassen','forest.php?op=leavecastle');
		break;
        /** @noinspection PhpMissingBreakStatementInspection */
        case 'kala'://ACHTUNG: $runden = kalavisits
		if($runden == -1 ) //Chilimaske
		{
			castlenav();
			return;
		}
		if($runden==-2) //Tageslimit erreicht
		{ 
			addnav('G?Noch eine Gurkenmaske!', 'forest.php?op=mask2');
		}
		//fehlendes break; beabsichtigt!
	case 'return':
		addnav('Nach draußen','forest.php?op=return');
		break;
	default:
		$session['user']['specialinc']='';
		$session['user']['specialmisc']='';
		forest();
		break;
	}
}

function catacombs()
{
	global $session,$castleoptions;
	$where=false;
	if($castleoptions['navigatorhead'])
	{
		output('`n`%Der Kopf des Navigators weist dir den Weg:');
	}
	else
	{
		output('`n`%Du kannst in folgende Richtungen gehen:');
		switch (e_rand(1,2))
		{
		case 1:
			output('`n<a href="forest.php?op=north">Norden</a>',true);
			addnav('Norden','forest.php?op=north');
			addnav('','forest.php?op=north');
			$where=true;
			break;
		case 2:
			break;
		}
		switch (e_rand(1,2))
		{
		case 1:
			output('`n<a href="forest.php?op=east">Osten</a>',true);
			addnav('Osten','forest.php?op=east');
			addnav('','forest.php?op=east');
			$where=true;
			break;
		case 2:
			break;
		}
		switch (e_rand(1,2))
		{
		case 1:
			output('`n<a href="forest.php?op=south">Süden</a>',true);
			addnav('Süden','forest.php?op=south');
			addnav('','forest.php?op=south');
			$where=true;
			break;
		case 2:
			break;
		}
		switch (e_rand(1,2))
		{
		case 1:
			output('`n<a href="forest.php?op=west">Westen</a>',true);
			addnav('Westen','forest.php?op=west');
			addnav('','forest.php?op=west');
			$where=true;
			break;
		case 2:
			break;
		}
	}
	if ($where == false)
	{
		switch (e_rand(1,5))
		{
		case 1:
			output('`n<a href="forest.php?op=north">Norden</a>',true);
			addnav('Norden','forest.php?op=north');
			addnav('','forest.php?op=north');
			break;
		case 2:
			output('`n<a href="forest.php?op=east">Osten</a>',true);
			addnav('Osten','forest.php?op=east');
			addnav('','forest.php?op=east');
			break;
		case 3:
			output('`n<a href="forest.php?op=south">Süden</a>',true);
			addnav('Süden','forest.php?op=south');
			addnav('','forest.php?op=south');
			break;
		case 4:
			output('`n<a href="forest.php?op=west">Westen</a>',true);
			addnav('Westen','forest.php?op=west');
			addnav('','forest.php?op=west');
			break;
		case 5:
			switch (e_rand(1,5))
			{
			case 1:
				addnews($session['user']['name'].' hat große Reichtümer in den Katakomben gefunden!');
				$gems = e_rand(1,3);
				$gold = e_rand($session['user']['level']*11,$session['user']['level']*100);
				output('`^ Vorwärts!`n`n`tDu erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du Berge von Reichtümern und du stopfst dir die Taschen voll!`n');
				output('`n`^Du hast '.$gems.' Edelsteine und '.$gold.' Gold mitgenommen!');
				$session['user']['gems']+=$gems;
				$session['user']['gold']+=$gold;
				break;
			case 2:
				output('`^ Vorwärts!`n`n`tDu erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du Berge von Gold und du stopfst dir die Taschen voll!`n');
				$gold = e_rand($session['user']['level']*11,$session['user']['level']*100);
				output('`n`^Du hast '.$gold.' Gold mitnehmen können!');
				$session['user']['gold']+=$gold;
				break;
			case 3:
				output('`^ Vorwärts!`n`n`tDu erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du ... `bnichts`b! Ein anderer war wohl schneller als du.`n');
				output('`nSchwer enttäuscht suchst du einen Ausgang aus den Katakomben.');
				break;
			case 4:
				output('`^ Vorwärts!`n`n`tDu erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du ... `bnichts`b! Ein anderer war wohl schneller als du.`n');
				output('Schwer enttäuscht suchst du einen Ausgang aus den Katakomben.`n`nWenigstens hast du durch das Herumirren etwas an `^Erfahrung`% gewonnen.');
				$session['user']['experience']+=$session['user']['experience']*0.02;
				break;
			case 5:
				output('`^ Vorwärts!`n`n`tDu erreichst eine verschlossene Tür und drückst sie auf. Dahinter findest du einen besonders schönen Edelstein und steckst ihn ein!`n');
				output('`n`^Du hast 1 Edelstein gefunden!');
				$session['user']['gems']+=1;
				break;
			}
			output('`n`n`n<a href="forest.php?op=exitlab">Katakomben verlassen</a>',true);
			addnav('Katakomben verlassen','forest.php?op=exitlab');
			addnav('','forest.php?op=exitlab');
			$castleoptions['navigatorhead']=false;
			$session['user']['specialmisc'] = utf8_serialize($castleoptions);
			item_delete('tpl_id="navikopf" AND owner='.$session['user']['acctid'],1);
			break;
		}
	}
}

if ($_GET['op']=='enter')
{
	$show_invent = true;

	output('`uD`}i`Ie `tWache tritt beiseite und du läufst durch das Tor in die Burg. Die Mitte des Burghofs ist ein großer, mit Gras bewachsener Platz, um den herum viele interessante Stände und Läden sind. Einige davon klingen wirklich verlockend! Du weißt gar nicht, wo du zuerst hingehen sollst, aber auf dem Platz stehen einige Leute, so beschließt du, sie einfach zu fra`Ig`}e`un.`n');
	viewcommentary('Courtyard','Rede mit Anderen');
	castlenav('main', $runden);
}
	/******Leave Castle******/
else if ($_GET['op']=='leave')
{
	$session['user']['specialinc']='';
	$session['user']['specialmisc'] = '';
	output('`tDu beschließt, dass du keine Zeit für die Burg hast und kehrst um. Du nimmst den selben Pfad zurück in den Wald, den du gekommen bist...');
}
else if ($_GET['op']=='leavecastle')
{
	$gems_spent=$castleoptions['gems_onenter']-$session['user']['gems'];
	if($gems_spent>0)
	{
		debuglog($gems_spent.' Edelsteine in der Orkburg gelassen');
	}
	$session['user']['specialinc']='';
	$session['user']['specialmisc'] = '';
	output('`tDu gehst durch das Tor und über den Pfad zurück in den Wald.
	`n`n`^Du vertrödelst einen Waldkampf!');
	if ($session['user']['turns']>0)
	{
		$session['user']['turns']--;
	}
}
	/********Return to Courtyard*******/
else if ($_GET['op']=='return')
{
	$show_invent = true;

	output('`uD`}u `Ig`tehst nach draußen. Die Mitte des Burghofs ist ein großer, mit Gras bewachsener Platz, um den herum viele interessante Stände und Läden sind. Einige davon klingen wirklich verlockend! Du weißt gar nicht, wo du zuerst hingehen sollst, aber auf dem Platz stehen einige Leute, so beschließt du, sie einfach zu fra`Ig`}e`un.`n');
	viewcommentary('Courtyard','Rede mit Anderen');
	castlenav('main', $runden);
}
	/*********catacombs*******/
else if ($_GET['op']=='catacombs')
{
	output('`uD`}u `Ib`tetrittst die Katakomben.  Ein Schild am Eingang warnt: `u"Große Reichtümer warten im Inneren, aber ebenso großes Leid! Der Weg nach draußen liegt im `yOsten`u... Merk dir das!!"`n');
	catacombs();
}
else if ($_GET['op']=='north')
{
	output('`tIm Inneren der Katakomben gehst du auf der Suche nach Reichtum nach `yNorden`t...`n');
	catacombs();
}
else if ($_GET['op']=='east')
{
	output('`tIm Inneren der Katakomben gehst du auf der Suche nach Reichtum nach `yOsten`t...`n');
	switch (e_rand(1,5))
	{
	case 1:
	case 2:
	case 3:
	case 4:
		catacombs();
		break;
	case 5:
		catacombs();
		output('`n`n`^Du findest einen Ausgang...
		`n<a href="forest.php?op=exitlab">Katakomben verlassen</a>');
		addnav('Ausgang');
		addnav('Katakomben verlassen','forest.php?op=exitlab');
		addnav('','forest.php?op=exitlab');
		break;
	}
}
else if ($_GET['op']=='south')
{
	output('`tIm Inneren der Katakomben gehst du auf der Suche nach Reichtum nach `ySüden`t...`n');
	catacombs();
}
else if ($_GET['op']=='west')
{
	output('`tIm Inneren der Katakomben gehst du auf der Suche nach Reichtum nach `yWesten`t...`n');
	catacombs();
}
else if ($_GET['op']=='exitlab')
{
	$session['user']['specialmisc'] = 0;
	$session['user']['specialinc']='';
	$ff = e_rand(1,4);
	if($ff>$session['user']['turns'])
	{
		$ff=$session['user']['turns'];
	}
	output('`tDu hast es geschafft, einen Ausgang aus den Katakomben zu finden. Allerdings musst du feststellen, dass du wieder im Wald gelandet bist. Dein Abenteuer in den Katakomben hatte seinen Preis...`n
	`n`^Du verlierst '.$ff.' Waldkämpfe!');
		$session['user']['turns']-=$ff;
}
	/*********Bar, wird zur Schießbude umgestaltet und zum Dorffest gesteckt*******/
else if ($_GET['op']=='bar')
{
	$orkhits=e_rand(0,10);
	if($_GET['act']=='stone')
	{
		output('`#Du entscheidest dich für die Steinschleuder und stellst dein Können mit dieser Waffe unter Beweis: Du triffst '.$orkhits.' Orks, die jedoch keinen größeren Schaden nehmen.');
		$session['user']['gold']-=5; //Scheißegal ob man das auch ohne Gold ewig machen kann
	}
	elseif($_GET['act']=='bow')
	{
		output('`#Du entscheidest dich für Pfeil und Bogen und stellst dein Können mit dieser Waffe unter Beweis: '.$orkhits.' Orks fallen um.');
		$session['user']['gold']-=5; //Scheißegal ob man das auch ohne Gold ewig machen kann
	}
	elseif($_GET['act']=='catapult')
	{
		output('`#Du entscheidest dich für das Katapult und stellst dein Können mit dieser Waffe unter Beweis: Du triffst '.$orkhits.' Orks, die durch die Wucht des Aufpralls regelrecht zersplittern.');
		$session['user']['gold']-=5; //Scheißegal ob man das auch ohne Gold ewig machen kann
	}
	elseif($_GET['act']=='machinegun')
	{
		if($orkhits==5)
		{
			output('`#Du entscheidest dich für das RPG2000 und grübelst, wie man damit Orks trifft. Dann findest du den Abzug und schießt dir selbst ins Bein. `$AUTSCH!`# So ein gefährliches Ding! Da bist du froh, dass sowas erst in ein paar hundert Jahren für ein Computerspiel erfunden wird. Was auch immer ein Computer sein mag...');
			$session['user']['hitpoints']*=0.6;
		}
		else
		{
			output('`#Du entscheidest dich für das RPG2000 und grübelst, wie man damit Orks trifft. Vielleicht solltest du in ein paar hundert Jahren wiederkommen, wenn Computerspiele erfunden sind. Was auch immer Computerspiele sein mögen...');
		}
		$session['user']['gold']-=5; //Scheißegal ob man das auch ohne Gold ewig machen kann
	}
	else
	{
		output("`#Du gehst durch die Tür in die `^'Schießbude'`# und das Erste, was dir auffällt, ist eine endlose Kette von Holz-Orks, welche durch den Raum gezogen werden. Du hast von diesem Ort gehört, die unbegrenzten Möglichkeiten, auf Orks zu schießen, machen ihn zu einem guten Ort zum Entspannen oder Dampf ablassen.`nAuf einer Holztafel steht die Preisliste: 10 Schuss 5 Gold.");

	}
	output('`n`n');
	viewcommentary('orcfield','Auch Orks killen',30,'ruft');
	addnav('Steinschleuder','forest.php?op=bar&act=stone');
	addnav('Pfeil und Bogen','forest.php?op=bar&act=bow');
	addnav('Katapult','forest.php?op=bar&act=catapult');
	addnav('RPG2000','forest.php?op=bar&act=machinegun');
	castlenav('return', $runden);
}
	/********Armourer********/
else if ($_GET['op']=='armourer')
{
	if (mb_strstr($session['user']['armor'],'High-Grade'))
	{
		output('`uD`}u `Ib`tetrittst Thorics Rüstungsladen. Du siehst Thoric in ein Buch vertieft in einer Ecke sitzen. Er schaut auf und wirft sofort einen Blick auf dein `^'.$session['user']['armor'].'`t. `u"Schön zu sehen, dass du meine Handwerkskunst trägst."`t murmelt er, bevor er sich wieder seinem Buch zuwendet.');
	}
	else
	{
		$newdefence = $session['user']['armordef'] + 2;
		$cost = $session['user']['armordef'] * 250;
		output('`uD`}u `Ib`tetrittst Thorics Rüstungsladen. Du siehst Thoric in ein Buch vertieft in einer Ecke sitzen. Er schaut auf und wirft sofort einen Blick auf dein `^'.$session['user']['armor'].'`u. ');
		if ($cost == 0)
		{
			output('`u"Sieht nicht so aus, als ob ich aus damit irgendetwas machen könnte."`t, murmelt er, bevor er sich wieder seinem Buch zuwendet.
			`n`n`^Niedergeschlagen machst du dich daran, den Laden zu verlassen...');
		}
		else if ($cost > $session['user']['gold'])
		{
			output('`u"Ich könnte das zu einem `^High-Grade '.$session['user']['armor'].'`u mit `^'.$newdefence.'`u Rüstungsschutz machen, wenn du willst. Und das kostet dich nur `^'.$cost.'`u Gold!"`t, murmelt er, bevor er sich wieder seinem Buch zuwendet.
			`n`n`^Da du aber nicht so viel Gold dabei hast, beschließt du den Laden zu verlassen...');
		}
		else
		{
			output('`u"Ich könnte das zu einem `^High-Grade '.$session['user']['armor'].'`u mit `^'.$newdefence.'`u Rüstungsschutz machen, wenn du willst. Und das kostet dich nur `^'.$cost.'`u Gold!"`t, murmelt er, bevor er sich wieder seinem Buch zuwendet.
			`n`n<a href="forest.php?op=upgradearmour">Rüstung verbessern</a>');
			addnav("","forest.php?op=upgradearmour");
			addnav("Rüstung verbessern","forest.php?op=upgradearmour");
		}
	}
	castlenav('return', $runden);
}
else if ($_GET['op']=='upgradearmour')
{
	output('`tThoric nimmt dein `^'.$session['user']['armor'].'`t und arbeitet eine Weile daran. Bald steht er auf, passt dir die Rüstung an und macht noch ein paar abschließende Änderungen. Die Rüstung fühlt sich jetzt etwas schwerer an, scheint aber tatsächlich von viel höherer Qualität zu sein als vorher. Zufrieden verlässt du den Laden.');
	$newarmor = 'High-Grade '.$session['user']['armor'];
	$cost = $session['user']['armordef'] * 250;
	$session['user']['gold']-=$cost;

	item_set_armor($newarmor,$session['user']['armordef']+2,$session['user']['armorvalue']+$cost,0,0,1);

	castlenav("return", $runden);
}
	/********Blacksmith********/
else if ($_GET['op']=='blacksmith')
{
	if (mb_strstr($session['user']['weapon'],'High-Grade'))
	{
		output('`uD`}u `Ib`tetrittst die Schmiede. Der Waffenschmied beugt sich über einen Schmelztiegel mit geschmolzenem Metall und betrachtet dein `^'.$session['user']['weapon'].'`t. `u"Das war `ne tolle Arbeit, die ich da für dich gemacht hab, also warum bist du hier?"`t, gibt er an. Etwas enttäuscht verlässt du die Schmi`Ie`}d`ue.');
	}
	else
	{
		$newattack = $session['user']['weapondmg'] + 2;
		$cost = $session['user']['weapondmg'] * 250;
		output('`uD`}u `Ib`tetrittst die Schmiede. Der Waffenschmied beugt sich über einen Schmelztiegel mit geschmolzenem Metall und betrachtet dein `^'.$session['user']['weapon'].'`t. ');
		if ($cost == 0)
		{
			output('`u"Du erwartest doch nicht, dass ich sowas bearbeite? Komm wieder, wenn du eine ordentliche Waffe hast."
			`n`n`^Niedergeschlagen machst du dich daran, den Laden zu verlassen...');
		}
		else if ($cost > $session['user']['gold'])
		{
			output('`u"Daraus kann ich ein `^High-Grade '.$session['user']['weapon'].'`u mit `^'.$newattack.'`u Schaden machen! Aber das wird dich `^'.$cost.'`u Gold kosten..."
			`n`n`^Da du nicht genug Gold hast, beschließt du den Laden zu verlassen...');
		}
		else
		{
			output('`u"Daraus kann ich ein `^High-Grade '.$session['user']['weapon'].'`% mit `5'.$newattack.'`u Schaden machen! Aber das wird dich `^'.$cost.'`u Gold kosten..."
			`n`n<a href="forest.php?op=upgradeweapon">Waffe verbessern</a>');
			addnav('','forest.php?op=upgradeweapon');
			addnav('Waffenschmied');
			addnav('Waffe verbessern','forest.php?op=upgradeweapon');
		}
	}
	castlenav('return', $runden);
}
else if ($_GET['op']=='upgradeweapon')
{
	output('`uD`}e`Ir `tWaffenschmied nimmt `^'.$session['user']['weapon'].'`t und arbeitet eine Weile daran. Bald steht er auf und gibt dir deine Waffe zurück. Sie wirkt etwas schwerer, aber die Qualität scheint wesentlich besser als vorher zu sein. Zufrieden verlässt du den La`Id`}e`un. ');
	$newweapon = 'High-Grade '.$session['user']['weapon'];
	$cost = $session['user']['weapondmg'] * 250;
	$session['user']['gold']-=$cost;

	item_set_weapon($newweapon,$session['user']['weapondmg']+2,$session['user']['weaponvalue']+$cost,0,0,1);

	castlenav('return', $runden);
}
	/********Training Room********/
else if ($_GET['op']=='train')
{
	output('`uD`}u `Ib`tetrittst den Trainingsraum und schaust dich um. Du siehst diverse Schwerter, Dummies und Trainer. Hier kannst du Zeit zum Trainieren verbringen und gefahrlos deine Erfahrung steigern.`n');
	if ($session['user']['turns'] < 1)
	{
		output('`n`n`tDu hast leider keine Waldkämpfe zum Trainieren üb`Ir`}i`ug!');
	}
	else
	{
		output('`tWieviele Runden willst du trainie`Ir`}e`un?`0`n
		<form action="forest.php?op=train2" method="POST">
		<input name="trai" id="trai"><input type="submit" class="button" value="Trainieren">
		</form>
		'.focus_form_element('trai'));
		addnav('','forest.php?op=train2');
	}
	castlenav('return', $runden);
}
else if ($_GET['op']=="train2")
{
	$trai = abs((int)$_GET['trai'] + (int)$_POST['trai']);
	if ($session['user']['turns'] <= $trai)
	{
		$trai = $session['user']['turns'];
	}
	if ($session['user']['turns']<=0)
	{
		output('`^Du fällst erschöpft um und landest sehr unsanft auf dem rauen Boden, bevor du trainieren konntest. Du verlierst einen Teil deiner Lebensenergie.');
		$session['user']['hitpoints']=round($session['user']['hitpoints']*0.8);
		if ($session['user']['hitpoints']<=0)
		{
			$session['user']['hitpoints']=1;
		}
	}
	else
	{
		$session['user']['turns']-=$trai;
		$exp = $session['user']['level']*e_rand(5,12)+e_rand(0,9);
		$totalexp = $exp*$trai;
		$session['user']['experience']+=$totalexp;
		output('`^Du trainierst '.$trai.' Runden und bekommst '.$totalexp.' Erfahrungspunkte!`n');
	}
	castlenav('return', $runden);
}
	/********Well********/
else if ($_GET['op']=='well')
{
	output('`uA`}u`If `teiner Seite des Burgplatzes befindet sich ein Wunschbrunnen. Du läufst hin und schaust hinunter. Ein Schild davor behauptet: `^"Wirf einen Edelstein hinein und wünsch dir was..."');
	if ($session['user']['gems'] < 1 && (e_rand(1,10) != 7 || $castleoptions['well_uses']>=1))
	{
		output('`n`n`uDa du keinen Edelstein hast, hat sich die Sache für dich erledigt...');
	}
	else
	{
		output('`n`n`tDu wirfst einen '.($session['user']['gems']>0?'Edelstein':'glänzenden Kieselstein').' hinein und wünschst dir ');
		if($session['user']['gems']>0)
		{
			$session['user']['gems']--;
		}
		else
		{
			debuglog('schummelte beim Wunschbrunnen');
		}
		$castleoptions['well_uses']+=1;
		$rand1 = e_rand(1,6);
		switch ($rand1)
		{
		case 1:
			output('`^Erfahrung...');
			break;
		case 2:
			output('`^Mehr Gold...');
			break;
		case 3:
			output('`^Mehr Lebenskraft...');
			break;
		case 4:
			output('`^Den Edelstein zurück...');
			break;
		case 5:
			output('`^Einen längeren Tag...');
			break;
		case 6:
			output('`^Mehr Charme...');
			break;
		}
		$rand2 = e_rand(1,4);
		switch ($rand2)
		{
		case 1:
		case 2:
		case 3:
			output('`n`n`uLeider gewähren dir die Götter diesen Wunsch nicht.');
			break;
		case 4:
			output('`n`n`uDie Götter gewähren dir diesen Wunsch! ');
			switch ($rand1)
			{
			case 1:
				$reward = round(e_rand($session['user']['experience'] * 0.05,$session['user']['experience'] * 0.1));
				$session['user']['experience'] += $reward;
				output('`^'.$reward.'`^ Erfahrungspunkte...');
				break;
			case 2:
				$gold = e_rand($session['user']['level']*10,$session['user']['level']*100);
				$session['user']['gold'] += $gold;
				output('`^'.$gold.' `^mehr Gold...');
				break;
			case 3:
				$reward = 1;
				$session['user']['maxhitpoints'] += $reward;
				output('`^'.$reward.' `^zusätzlichen Lebenspunkt...');
				break;
			case 4:
				$gems = e_rand(2,4);
				$session['user']['gems'] += $gems;
				output('`^'.$gems.' `^Edelsteine...');
				break;
			case 5:
				$ff = e_rand(1,4);
				$session['user']['turns'] += $ff;
				output('`^'.$ff.' `^mehr Waldkämpfe...');
				break;
			case 6:
				$charm = e_rand(1,5);
				$session['user']['charm'] += $charm;
				output('`^'.$charm.' `^mehr Charme...');
				break;
			}
			break;
		}
		$session['user']['specialmisc'] = utf8_serialize($castleoptions);
	}
	castlenav('return', $runden);
}
	/********Healer********/
else if ($_GET['op']=='medicine')
{
	$loglev = log($session['user']['level']);
	$cost = ($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10);
	$cost=$cost*0.9;
	$cost = round($cost,0);
	output('`c`b`3K`§a`Blas Beautysh`§o`3p`0`b`c`n
	`3D`§i`Be wunderschöne Kala begrüßt dich in ihrem Beautyshop. `3"Ah.. hallo, '.$session['user']['name'].'`3.
	Brauchst du Heilung? Willst du schöner werden? Oder soll sich deine Schönheit endlich bezahlt machen? Dann bist du hier genau richtig!`3", sagt sie.`n
	Du fragst sie, was genau sie damit meint. `3"Also: Heilung dürfte dir klar sein. Mit einer Gesichtsmaske - vorzugsweise aus Gurken von Violet - kann ich dich attraktiver machen. Und wenn du willst, kannst du mir etwas von deiner Schönheit ... überlassen und dafür etwas Erfahrung gewinnen.`3"');
	$indate = getsetting('gamedate','0005-01-01');
	$date = explode('-',$indate);
	$monat = $date[1];
	$tag = $date[2];
	if ($session['user']['exchangequest']==20 && $monat==11 && $tag<5) 
	{
		output('`n`n`%Beim Stichwort "Gurkenmaske" fängt eine zufällig anwesende Dame mittleren Alters an zu erzählen: "`7Die Gurkenmasken sind sehr empfehlenswert. Ich komme jeden Tag hier her und lasse mir Gurkenmasken machen. Aber irgendetwas fehlt mir noch.`%"
		`nDie Dame ist ohne Frage hübsch, sie sieht reich aus und ist bestimmt sogar adlig. Und irgendwie hat sie exakt die selbe Frisur wie die Nixe neulich an Poseidons See.
		`nOb sie vielleicht mit der Perlenkette...?');
		addnav('`%Die Dame ansprechen`0','exchangequest.php');
	}
	if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
	{
		addnav('Komplette Heilung (`^'.$cost.' Gold`0)','forest.php?op=buy1');
	}
	addnav('Gurkenmaske (`#1 Edelstein`0)','forest.php?op=maske');
	if ($session['user']['charm']>0)
	{
		addnav('Charme opfern (`^100 Gold`0)','forest.php?op=copfer');
	}
	castlenav('return', $runden);
}
else if ($_GET['op']=='buy1')
{
	$loglev = log($session['user']['level']);
	$cost = ($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10);
	$cost=$cost*0.9;
	$cost = round($cost,0);
	if ($session['user']['gold']>=$cost)
	{
		$session['user']['gold']-=$cost;
		//debuglog('spent $cost gold on healing');
		$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
		output('`3Kala gibt dir einen großen, wohlschmeckenden Heiltrank. Du bist angenehm überrascht, da du eigentlich etwas Ähnliches wie das Zeug vom Heiler im Wald erwartet hättest. Kalas Trank entfaltet sofort seine Wirkung.`n`n`^Du bist vollständig geheilt.');
	}
	else
	{
		output('`3"Also, ohne Gold bekommst du hier gar nichts! Verschwinde lieber!`3", raunzt Kala dich an, als sie merkt, dass du keine '.$cost.' Gold dabei hast.');
	}
	castlenav('return', $runden);
}
else if ($_GET['op']=='maske')
{
	$k_vists = user_get_aei('kala_visits');
	$k_vists = (int)$k_vists['kala_visits'];
	if ($session['user']['gems']>=1 && $k_vists<3)
	{
		$session['user']['gems']-=1;
		//debuglog('spent 1 gem for charm in castle');
		$session['user']['charm']+= 1;
		//$castleoptions['beautyshop_uses']+=1;
		user_set_aei(array('kala_visits'=>++$k_vists));
		output('`3Du gibst ihr einen Edelstein und Kala packt dich mit einer Kraft, die du ihr nicht zugetraut hättest, auf einen Stuhl und fängt sofort an, dein Gesicht mir irgendwelchen mehr oder weniger schleimigen Dingen zu bedecken. Dabei scheint sie hin und wieder von den Zutaten zu naschen, aber sicher bist du dir nicht, denn deine Augen waren das Erste, was unter Gurkenscheiben verschwunden ist. Du kommst dir ziemlich albern vor, aber nach einiger Zeit, als du das Ergebnis präsentiert bekommst, bist du der Meinung, dass es sich doch gelohnt hat..
		`n`n`^Du erhältst einen Charmepunkt!');
	}
	else if ($k_vists>=3)
	{
		output('`3"Ja, ich könnte dir noch eine Gurkenmaske machen, aber helfen wird sie dir heute nicht mehr.`3"');
		if($k_vists==3){
			output('`nSie schaut dich erwartungsvoll an und wartet deine Reaktion ab.`n');
			$k_vists = -2;
		}
	}
	else
	{
		output('`3"Also, ohne Edelstein bekommst du hier gar nichts! Verschwinde lieber!`3", raunzt Kala dich an, als sie merkt, dass du keinen Edelstein hast.');
	}
	$session['user']['specialmisc'] = utf8_serialize($castleoptions);
	castlenav('kala', $k_vists);
}
else if ($_GET['op']=='mask2')
{
	$k_option = 0;
	if ($session['user']['gems']>=1)
	{
		$k_vists = user_get_aei('kala_visits');
		$k_vists = (int)$k_vists['kala_visits'];
		$session['user']['gems']-=1;
		user_set_aei(array('kala_visits'=>++$k_vists));
		//debuglog('spent 1 gem for charm in castle');
		$int_rnd = e_rand(0,100);
		output('`3Du gibst ihr einen Edelstein und setzt dich auf den Stuhl auf den dich Kala heute schon 3 mal gesetzt hat, lehnst dich an und schließt in freudiger Erwartung auf die erneute Wohltat deine Augen.`n
				Nach kurzer Zeit trägt Kala die Maske auf dein Gesicht auf.`n');
		if($int_rnd<25){
			output('`4Doch plötzlich brennt dein Gesicht wie Feuer! Kala hat dir ausversehen eine `bChilimaske`b aufgetragen.`nSchnell springst du auf und rennst unter lautem Gelächter der Orkburgbewohner davon!`n
					Als du an einem Bach vorbeikommst, wäschst du dir die brennende Paste ab und musst mit Erschrecken feststellen, dass dein Gesicht furchtbar aussieht.`n`n
					`3Du verlierst 3 Charmepunkte!');
			$k_option = -1;
			$session['user']['charm']-= 3;
		}
		elseif($int_rnd<50){
			output('`3Du genießt die wohltuende Gurkenmaske eine Weile und verlässt dann Kalas Shop.`n`n`^Du erhältst einen Charmepunkt!');	
			$session['user']['charm']+= 1;
		}
		elseif($int_rnd<75){
			output('`3Du genießt die wohltuende Gurkenmaske eine Weile und verlässt dann Kalas Shop. Doch als du an die frische Luft kommst, fühlst du dich schwach!`n`n
					`4Du hast eine Gurkenallergie bekommen!');
			$buff = array("name"=>"`JGurkenallergie`!","rounds"=>30,"wearoff"=>"`JDeine Gurkenallergie schwindet!`0","atkmod"=>0.5,"roundmsg"=>"`JDeine Gurkenallergie lässt dich nur mit halber Kraft zuschlagen!`!","activate"=>"offense");
			$session['bufflist']['gurkenallergie']=$buff;
		}
		elseif($int_rnd<100){
			output('`3Du genießt die wohltuende Gurkenmaske eine Weile und verlässt dann Kalas Shop.`n`n`^Aus irgendeinem Grund hatte die Maske diesmal keine Wirkung!');
		}
		elseif($int_rnd==100){
			output('`3Doch es fühlt sich anders an, als die 3 Masken zuvor! Es ist, als würden kleine Feen über dein Gesicht tänzeln und deine Haut liebkosen.`n
			`3"Oh nein!`3", schreit Kala, `3"Ich habe ausversehen eine Aloha Vera Maske gemacht! Sie hat die fünffache Wirkung.`3"`n`n
			`tDu erhältst 5 Charmepunkte!');
			$session['user']['charm']+= 5;
		}
	}
	else
	{
		output('`3`3"Also, ohne Edelstein bekommst du hier gar nichts! Verschwinde lieber!`3", raunzt Kala dich an, als sie merkt, dass du keinen Edelstein hast.');
	}
	$session['user']['specialmisc'] = utf8_serialize($castleoptions);
	castlenav('kala', $k_option);
}
else if ($_GET['op']=='copfer')
{
	if ($session['user']['gold']>=100)
	{
		$session['user']['gold']-=100;
		//debuglog("spent 100 gold on turning charm into experience");
		$amt=e_rand(1,5);
		$exp=20*($session['user']['level']+2*$amt);
		$session['user']['charm']-=$amt;
		if ($session['user']['charm']<0)
		{
			$session['user']['charm']=0;
		}
		$session['user']['experience']+=$exp;
		$castleoptions = utf8_unserialize($session['user']['specialmisc']);
		if ($castleoptions['beautyshop_uses']>0)
		{
			$castleoptions['beautyshop_uses']-=1;
		}
		$session['user']['specialmisc'] = utf8_serialize($castleoptions);
		output("`3Kala nimmt dein Gold und reibt dein Gesicht mit einer übel riechenden Pampe ein. Nach einer Weile wäscht sie dir das Zeug mit Wasser ab - und gibt dir das Wasser mit der Pampe zu trinken!");
		output(" Noch etwas benommen von dem furchtbaren Anblick im Spiegel, leistest du kaum Widerstand und trinkst.`n`n`^Du VERLIERST ".$amt." Charmepunkte!`nDu bekommst ".$exp." Erfahrungspunkte dafür.");
	}
	else
	{
		output("`3\"Also, ohne Gold bekommst du hier gar nichts! Verschwinde lieber!`3\", raunzt Kala dich an, als sie merkt, dass du keine 100 Gold dabei hast.");
	}
	castlenav("return", $runden);
}
	/********Guard Fight********/
else if ($_GET['op']=='guardfight' || $_GET['op'] == 'fight' || $_GET['op'] == 'run')
{
	if ($_GET['op']=='guardfight')
	{
		$badguy = array('creaturename'=>'Greifenwache','creaturelevel'=>$session['user']['level'],'creatureweapon'=>'Scharfe Krallen und Schnabel','creatureattack'=>$session['user']['attack'],'creaturedefense'=>$session['user']['defence'],'creaturehealth'=>$session['user']['maxhitpoints'], 'diddamage'=>0);
		$session['user']['badguy']=createstring($badguy);
		$fight=true;
	}
	else if ($_GET['op'] == 'fight')
	{
		$fight=true;
	}
	else if ($_GET['op'] == 'run')
	{
		output('`%Dein Stolz verbietet es dir, vor diesem Kampf davonzulaufen!`n');
		$fight=true;
	}
	if ($fight)
	{
		if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!='')
		{
			$_GET['skill']='';
			if ($_GET['skill']=='')
			{
				$session['user']['buffbackup']=utf8_serialize($session['bufflist']);
			}
			$session['bufflist']=array();
			output('`&Dein Stolz verbietet es dir, deine besonderen Fähigkeiten einzusetzen!`0');
		}
		include 'battle.php';
		if ($victory)
		{
			$session['user']['reputation']++;
			output('`n`tDu hast die Greifenwache besiegt und dir wird der Eintritt zur Burg gewährt!`n
			`n`uD`}i`Ie `tWache tritt beiseite und du läufst durch das Tor in die Burg. Die Mitte des Burghofs ist ein großer, mit Gras bewachsener Platz, um den herum viele interessante Stände und Läden sind. Einige davon klingen wirklich verlockend!`n');
			castlenav('main', $runden);
		}
		else if ($defeat)
		{
			output('`n`^Kurz vor dem endgültigen Todesstoß fliegt die Greifenwache zurück auf ihren Platz und bewacht wieder das Tor. Du hast nur noch 1 Lebenspunkt und verlierst 3 Waldkämpfe, aber du hast Glück, noch am Leben zu sein !');
			$session['user']['hitpoints']=1;
			$session['user']['turns']=max(0,$session['user']['turns']-2);
			$session['user']['specialinc']="";
		}
		else
		{
			fightnav(false,true);
		}
	}
}
else
{
	if (e_rand(1,100) <95)
	{
		if ($session['user']['turns']<1)
		{
			$session['user']['specialinc']='';
			output('`tEigentlich hast du dich auf das Waldschloss gefreut, aber deine Füße und dein lahmes Pferd freuen sich bereits auf den Feierabend. Du entscheidest, heute doch nicht zum Schloss zu reiten!');
			addnav('W?Weiter','forest.php');
			page_footer();
		}
		output('`uD`}u `If`tolgst einem unbefestigten Pfad und siehst dabei in der Ferne gelegentlich eine große Burg... Könnte `bdas`b die legendäre `yOrkburg`t sein?`n
		Du kommst näher und bist dir plötzlich gar nicht mehr so sicher, ob du dich der Burg wirklich weiter nähern oder lieber umkehren solltest.`n`n
		Aber du gibst dir einen Ruck, lässt deine Ängste hinter dir und läufst weiter auf die Burg zu. Als du näher kommst, bemerkst du, dass ein Greif vor dem Tor Wache hält. Du kommst dort an und die mystische Kreatur spricht dich an. `/"Willkommen in der Orkburg! Wenn du hier rein willst, musst du deine Tapferkeit entweder schon mit einer Heldentat bewiesen haben oder du musst mich in einem fairen Kampf besiegen!"');
		if ($session['user']['dragonkills']>0)
		{
			output('`n`n`^Da du bereits eine Heldentat vollbracht hast, darfst du passieren.
			`n`n<a href="forest.php?op=enter">Die Burg betreten</a>`n<a href="forest.php?op=leave">Umkehren</a>');
			addnav('Die Burg betreten','forest.php?op=enter');
			addnav('','forest.php?op=enter');
		}
		else
		{
			output('`n`n<a href="forest.php?op=guardfight">Bekämpfe die Wache</a>`n<a href="forest.php?op=leave">Kehre um</a>');
			addnav('Wache bekämpfen','forest.php?op=guardfight');
			addnav('','forest.php?op=guardfight');
		}
		addnav('','forest.php?op=leave');
		addnav('Umkehren','forest.php?op=leave');
	}
	else
	{
		$session['user']['specialinc']='';
		$session['user']['specialmisc'] = '';
		output('`tDu folgst einem unbefestigten Pfad und verirrst dich total!');
		output('`n`n`^Beim Versuch, einen Weg zurück zu finden, verlierst du 2 Waldkämpfe!`n`n');
		$session['user']['turns']=max(0,$session['user']['turns']-2);
		addnav('Zurück in den Wald','forest.php');
		//forest();
	}
}
?>
