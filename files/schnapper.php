<?php
/* *******************
TMSIDR Schnapper
Written by Romulus von Grauhaar
Visit http://www.scheibenwelt-logd.de.vu

Schnapper ist ein kleines Zusatzskript für das Dorf. Wer die Scheibenwelt-Romane kennt,
wird auch mit dieser Figur etwas anfangen können. Schnapper bietet die unterschiedlichsten Waren feil,
allerdings weiß man nie so genau, ob sie etwas positives oder negatives bewirken. Außerdem hat er
zufällig gewählte Waren und sein Sortiment schwankt (das einzige, was man immer
bei ihm bekommt, sind seine Würstchen und Fleischpasteten).
Man kann Schnapper als 'normales' Geschäft im Dorf (oder an beliebiger anderer Stelle) einbauen,
oder aber man fügt in der village.php eine Zufallsaktion hinzu, dass einem Schnapper über den Weg läuft.
Dazu ergänzt man im Skript der village.php einfach folgenden Code:

_____finde:_____________________
if ($session['user']['alive']){ }else{
redirect('shades.php');
}
_____füge danach ein:_____________

// Schnapper Mod by Romulus
if ($_GET['op']!='schnapper') {
switch(e_rand(1,10))
{
case 1:
redirect('schnapper.php');
break;
case 2:
case 3:
case 4:
case 5:
case 6:
case 7:
case 8:
case 9:
case 10:
break;
}
}

Die Preise der einzelnen Waren lassen sich natürlich am Skriptanfang anpassen.

******************* */

$sausagecost='70';
$piecost='100';
$dragoncost='500';
$detectorcost='150';
$polishcost='1000';
$cremecost='500';
$stonecost='1000';
$potioncost='50';
$surprisecost='1'; //Edelstein

require_once 'common.php';
page_header('T.M.S.I.D.R. Schnapper');
output('`c`b`^T.M.S.I.D.R. Schnapper`0`b`c`n`6');

if ($_GET['op']=='')
{
	checkday();
	output('`@ Auf deinem Weg durch die Straßen läuft dir der berühmt-berüchtigte Händler
	Treibe-mich-selbst-in-den-Ruin Schnapper über den Weg und beginnt sofort, seine Waren anzupreisen:
	`5"Hallo '.($session['user']['sex']?'meine Teuerste':'mein Freund').'! Ich habe heute einige besonders ausgefallene Waren im Angebot.
	Oder willst du vielleicht den Klassiker, ein echtes Schnapper-Würstchen zum Preis von nur '.$sausagecost.' Goldmünzen? Und damit treibe ich mich selbst in den Ruin!"`@');
	addnav('Kaufen');
	addnav('W? Schnappers Würstchen ('.$sausagecost.' Gold)','schnapper.php?op=w');
	addnav('F? Schnappers Fleischpastete ('.$piecost.' Gold)','schnapper.php?op=f');
	$shop1=e_rand(1,3);
	$shop2=e_rand(1,3);
	if ($shop1==1)
	{
		addnav('D? Schnappers Drachentöterelixier ('.$dragoncost.' Gold)','schnapper.php?op=d');
	}
	if ($shop1==2)
	{
		addnav('H? Schnappers extragünstiger Heiltrank ('.$potioncost.' Gold)','schnapper.php?op=h');
	}
	if ($shop1==3)
	{
		addnav('D? Schnappers Drachendetektor ('.$detectorcost.' Gold)','schnapper.php?op=a');
	}
	if ($shop2==1)
	{
		addnav('R? Schnappers Rüstungspolitur ('.$polishcost.' Gold)','schnapper.php?op=r');
	}
	if ($shop2==2)
	{
		addnav('C? Schnappers Schönheits-Creme ('.$cremecost.' Gold)','schnapper.php?op=c');
	}
	if ($shop2==3)
	{
		addnav('S? Schnappers Schleifstein ('.$stonecost.' Gold)','schnapper.php?op=s');
	}
	addnav('K? Die Katze im Sack kaufen ('.$surprisecost.' Edelstein)','schnapper.php?op=k');
}
elseif ($_GET['op']=='w') //Würstchen
{
	if ($session['user']['gold']<$sausagecost)
	{
		output('`@Schnapper schaut dich böse an. `5"Da bietet man dir das Geschäft deines Lebens an und du hast noch nichtmal genug Gold dabei!"`@');
	}
	else
	{
		$session['user']['gold']-=$sausagecost;
		output('`@Schnapper gibt dir eines seiner typischen Würstchen aus seinem Bauchladen und schmiert etwas Senf darauf.`n
		`5"Ich wusste doch, dass ich einen Kenner vor mir habe. Lass es dir gut schmecken!"`@`n
		Misstrauisch beäugst du das Würstchen und beisst mutig hinein.`n');
		switch (e_rand(1,10))
		{
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
			output('`@Du würgst und spuckst das Würstchen wieder aus. Es hat dermassen ekelhaft geschmeckt, dass du nur noch Übelkeit verspürst. Als du dich wütend nach Schnapper umdrehst, ist er schon längst in einer Menschenmenge verschwunden.`n`n
			`&Du verlierst einige Trefferpunkte!');
			$session['user']['hitpoints']=max(1,$session['user']['hitpoints']-5);
			break;
		case 7:
		case 8:
		case 9:
			output('`@Du würgst vor Übelkeit, aber das Würstchen bleibt in deinem Magen. Es hat zwar furchtbar ekelhaft geschmeckt, aber du fühlst dich trotzdem gestärkt.`n`n
			`&Du erhältst einige Trefferpunkte!');
			$session['user']['hitpoints']+=5;
			break;
		case 10:
			output('`@Als du das Würstchen herunterwürgst, überkommt dich ein derartig großer Schwall Übelkeit, dass du umkippst und keine Luft mehr bekommst.
			Schnapper sieht derweil zu, dass er Land gewinnt, während du dein Leben aushauchst. Und das an einem Würstchen! `n`n
			`&Das war\'s wohl! Du bist tot und verlierst 5% deiner Erfahrung und all dein mitgeführtes Gold!');
			addnews('`%'.$session['user']['name'].'`7 starb an einem von Schnappers berüchtigten Würstchen.');
			killplayer(100,5,0,'news.php','Ankh-Morpork Times');
		}
		//switch
	}
	//else (genug Gold)
}
// End Würstchen
elseif ($_GET['op']=='f') //Fleischpastete
{
	if ($session['user']['gold']<$piecost)
	{
		output('`@Schnapper schaut dich böse an. `5"Da bietet man dir das Geschäft deines Lebens an und du hast noch nichtmal genug Gold dabei!"`@');
	}
	else
	{
		$session['user']['gold']-=$piecost;
		output('`@Schnapper gibt dir eine seiner typischen Fleischpasteten aus seinem Bauchladen.`n
		`5"Ich wusste doch, dass ich einen Kenner vor mir habe. Lass sie dir gut schmecken!"`@`n
		Misstrauisch beäugst du die Pastete und beisst mutig hinein.`n');
		switch (e_rand(1,10))
		{
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
			output('`@Du würgst und spuckst die Pastete wieder aus. Sie hat dermassen ekelhaft geschmeckt, dass du nur noch Übelkeit verspürst. Als du dich wütend nach Schnapper umdrehst, ist er schon längst in einer Menschenmenge verschwunden.`n`n
			`&Du verlierst einige Trefferpunkte!');
			$session['user']['hitpoints']=max(1,$session['user']['hitpoints']-10);
			break;
		case 7:
		case 8:
		case 9:
			output('`@Du würgst vor Übelkeit, aber die Pastete bleibt in deinem Magen. Sie hat zwar furchtbar ekelhaft geschmeckt, aber du fühlst dich trotzdem gestärkt.`n`n
			`&Du erhältst einige Trefferpunkte!');
			$session['user']['hitpoints']+=10;
			break;
		case 10:
			output('`@Als du die Pastete herunterwürgst, überkommt dich ein derartig großer Schwall Übelkeit, dass du umkippst und keine Luft mehr bekommst.
			Schnapper sieht derweil zu, dass er Land gewinnt, während du dein leben aushauchst. Und das an einer Fleischpastete! `n`n
			`&Das war\'s wohl! DU bist tot und verlierst 5% deiner Erfahrung und all dein mitgeführtes Gold!');
			addnews('`%'.$session['user']['name'].'`7 starb an einer von Schnappers berüchtigten Fleischpasteten.');
			killplayer(100,5,0,'news.php','Ankh-Morpork Times');
		}
		//switch
	}
	//else (genug Gold)
}
// End Fleischpastete
elseif ($_GET['op']=='d') //Drachentöterelixier
{
	if ($session['user']['gold']<$dragoncost)
	{
		output('`@Schnapper schaut dich böse an. `5"Da bietet man dir das Geschäft deines Lebens an und du hast noch nichtmal genug Gold dabei!"`@');
	}
	else
	{
		$session['user']['gold']-=$dragoncost;
		output('`@Schnapper öffnet seinen Bauchladen und holt eine kleine Flasche hervor. Geheimnisvoll blickt er dich an`n
		`5"Das ist die geheime Medizin der Drachentöter. Trinkst du sie, wirst du stark wie ein Drache!"`@`n
		Misstrauisch beäugst du die Flasche und nimmst mutig einen kräftigen Schluck.`n');
		switch (e_rand(1,3))
		{
		case 1:
			output('`@Das Zeug schmeckt widerlich. Von dem ekelhaften Gesöff fühlst du dich eher schwach als stark.
			Als du dich wütend nach Schnapper umdrehst, ist er schon längst in einer Menschenmenge verschwunden.`n`n
			`&Du verlierst eine Kampfrunde!');
			$session['user']['turns']=max(0,$session['user']['turns']-1);
			break;
		case 2:
			output('`@Das Zeug schmeckt widerlich, aber es scheint dir trotzdem nichts schlimmes passiert zu sein. Allerdings auch nichts gutes.
			Als du dich wütend nach Schnapper umdrehst, ist er schon längst in einer Menschenmenge verschwunden.`n`n
			`&Das Drachentöterelixier hatte keine Wirkung!');
			break;
		case 3:
			output('`@Das Zeug schmeckt widerlich. Trotzdem merkst du, wie du dich wieder stärker und kampfbereit fühlst.`n`n
			`&Du erhältst eine zusätzliche Kampfrunde!');
			$session['user']['turns']+=1;
		}
		//switch
	}
	//else (genug Gold)
}
// End Drachentöterelixir
elseif ($_GET['op']=='h') //Heiltrank
{
	if ($session['user']['gold']<$potioncost)
	{
		output('`@Schnapper schaut dich böse an. `5"Da bietet man dir das Geschäft deines Lebens an und du hast noch nichtmal genug Gold dabei!"`@');
	}
	else
	{
		$session['user']['gold']-=$potioncost;
		output('`@Schnapper öffnet seinen Bauchladen und holt eine Flasche mit einem roten Kreuz darauf hervor.`n
		`5"Das ist die extragünstige Schnapper-Spezialmedizin. Sie wird dich von deinen Wunden heilen!"`@`n
		Misstrauisch beäugst du die Flasche und nimmst mutig einen kräftigen Schluck.`n');
		switch (e_rand(1,3))
		{
		case 1:
			output('`@Der Trank schmeckt nach gar nichts. Allerdings macht sich trotzdem schon bald eine Wirkung breit: Du fühlst dich irgendwie nicht mehr so gut wie vor der Medizin.
			Als du dich wütend nach Schnapper umdrehst, ist er schon längst in einer Menschenmenge verschwunden.`n`n
			`&Du verlierst einige Trefferpunkte!');
			$session['user']['hitpoints']=max(1,$session['user']['hitpoints']-3);
			break;
		case 2:
			output('`@Der Trank schmeckt nach gar nichts und offenbar ist es auch fast gar nichts, nämlich nur gefärbtes Wasser.
			Als du dich wütend nach Schnapper umdrehst, ist er schon längst in einer Menschenmenge verschwunden.`n`n
			`&Der Heiltrank hatte keine Wirkung!');
			break;
		case 3:
			output('`@Der Trank schmeckt nach gar nichts. Allerdings macht sich trotzdem schon bald eine Wirkung breit: Du fühlst dich gesünder als zuvor.
			Wer hätte gedacht, dass der supergünstige Schnappertrank wirkt.`n`n
			`&Du erhältst einige Trefferpunkte!');
			$session['user']['hitpoints']+=8;
		}
		//switch
	}
	//else (genug Gold)
}
// End Heiltrank
elseif ($_GET['op']=='a') //Drachentetektor
{
	if ($session['user']['gold']<$detectorcost)
	{
		output('`@Schnapper schaut dich böse an. `5"Da bietet man dir das Geschäft deines Lebens an und du hast noch nichtmal genug Gold dabei!"`@');
	}
	else
	{
		$session['user']['gold']-=$detectorcost;
		output('`@Schnapper öffnet seinen Bauchladen und holt eine Art Wünschelrute mit seltsamen metallenen Verzierungen hervor.`n
		`5"Mit diesem Gerät kannst du ganz einfach Drachen in der Umgebung aufspüren!"`@`n
		Mit diesen Worten verschwindet er in einer Menschenmenge.`n Irgendwie hast du das Gefühl, dass dich Schnapper reingelegt hat, denn
		du hast gar keine Ahnung was du mit diesem Stück Holz anfangen sollst.');
	}
	//else (genug Gold)
}
// End Detektor
elseif ($_GET['op']=='r') //Rüstungspolitur
{
	if ($session['user']['gold']<$polishcost)
	{
		output('`@Schnapper schaut dich böse an. `5"Da bietet man dir das Geschäft deines Lebens an und du hast noch nichtmal genug Gold dabei!"`@');
	}
	else
	{
		$session['user']['gold']-=$polishcost;
		output('`@Schnapper öffnet seinen Bauchladen und holt eine kleine Flasche mit einer milchigen Tinktur hervor.`n
			`5"Poliere deine Rüstung mit diesem Mittel und du wirst ein überwältigendes Ergebnis haben! Und dieses Poliertuch gibt\'s gratis dazu. Und damit treibe ich mich selbst in den Ruin!"`@`n
			Misstrauisch beäugst du die Tinktur, fängst dann aber trotzdem an, deine Rüstung zu polieren.`n');
		$rand=($session['user']['armordef']>0 ? e_rand(1,3) : 2);
		switch ($rand)
		{
		case 1:
			output('`@Nachdem du die Politur gründlich mit dem Tuch verrieben hast, bekommt dein `4'.$session['user']['armor'].'`@ schwarze Flecken und scheint nicht mehr so stabil wie vorher zu sein.
			Während du noch am polieren warst, hat sich Schnapper mucksmäuschenstill davongeschlichen.`n`n
`&Deine Rüstung wird schwächer!');
			$armordef=$session['user']['armordef']-1;
			$armorvalue=round($session['user']['armorvalue']*0.95);
			item_set_armor($session['user']['armor'], $armordef, $armorvalue, 0, 0, 1);
			break;
		case 2:
			output('`@Nachdem du die Politur gründlich mit dem Tuch verrieben hast, sieht dein `4'.$session['user']['armor'].'`@ noch genauso aus wie vorher.
			Während du noch am polieren warst, hat sich Schnapper mucksmäuschenstill davongeschlichen.`n`n
			`&Die Rüstungspolitur zeigt keine Wirkung!');
			break;
		case 3:
			output('`@Nachdem du die Politur gründlich mit dem Tuch verrieben hast, erstrahlt dein `4'.$session['user']['armor'].'`@ in neuem Glanz.
			Du willst Schnapper für die hervorragende Qualität seiner Politur danken, doch er ist schon weitergegangen und verkauft seine Würstchen auf der anderen Seite des Platzes.`n`n
			`&Deine Rüstung wird stärker!');
			$armordef=$session['user']['armordef']+1;
			$armorvalue=round($session['user']['armorvalue']*1.05);
			item_set_armor($session['user']['armor'], $armordef, $armorvalue, 0, 0, 1);
		}
		//switch
	}
	//else (genug Gold)
}
// End Politur
elseif ($_GET['op']=='c') //Schönheitscreme
{
	if ($session['user']['gold']<$cremecost)
	{
		output('`@Schnapper schaut dich böse an. `5"Da bietet man dir das Geschäft deines Lebens an und du hast noch nichtmal genug Gold dabei!"`@');
	}
	else
	{
		$session['user']['gold']-=$cremecost;
		output('`@Schnapper öffnet seinen Bauchladen und holt eine kleine Tube hervor, die eine blassrosafarbene Creme enthält.`n
		`5"Das Rezept dieser Creme habe ich '.($session['user']['sex']?'vom Liebesgott Phallis':'von der Liebesgöttin Aphrodante').' persönlich erhalten!"`@`n
		Misstrauisch beginnst du, dein Gesicht mit der Creme einzureiben.`n');
		switch (e_rand(1,3))
		{
		case 1:
			output('`@Die Creme klebt furchtbar und als dein Blick in eine Wasserpfütze fällt, erschrickst du über dein entstelltes Gesicht.
			Als du dich wütend nach Schnapper umdrehst, ist er schon längst in einer Menschenmenge verschwunden.`n`n
			`&Du verlierst drei Charmepunkte!');
			$session['user']['charm']=max(0,$session['user']['charm']-3);
			break;
		case 2:
			output('`@Die Creme klebt furchtbar und als dein Blick in eine Wasserpfütze fällt, merkst du, dass sich überhaupt nichts getan hat.
			Als du dich wütend nach Schnapper umdrehst, ist er schon längst in einer Menschenmenge verschwunden.`n`n
			`&Die Schönheitscreme hatte keine Wirkung!');
			break;
		case 3:
			output('`@Die Creme klebt furchtbar und als dein Blick in eine Wasserpfütze fällt, bist du erstaunt, wie gut du plötzlich aussieht.`n`n
			`&Du erhältst drei Charmepunkte!');
			$session['user']['charm']+=3;
		}
		//switch
	}
	//else (genug Gold)
}
// End Schönheitscreme
elseif ($_GET['op']=='s') //Schleifstein
{
	if ($session['user']['gold']<$stonecost)
	{
		output('`@Schnapper schaut dich böse an. `5"Da bietet man dir das Geschäft deines Lebens an und du hast noch nichtmal genug Gold dabei!"`@');
	}
	else
	{
		$session['user']['gold']-=$stonecost;
		output('`@Schnapper öffnet seinen Bauchladen und holt einen mit magischen Symbolen verzeirten, rauhen Stein hervor.`n
		`5"Schleife damit deine Waffe und du wirst ein überwältigendes Ergebnis feststellen!"`@`n
		Misstrauisch beäugst du den Schleifstein, fängst dann aber trotzdem an, deine Waffe zu schleifen.`n');
		$rand=($session['user']['weapondmg']>0 ? e_rand(1,3) : 2);
		switch ($rand)
		{
		case 1:
			output('`@Nachdem du einige Minuten an deiner `4'.$session['user']['weapon'].'`@ geschliffen hast, bricht davon ein kleines Stück ab und sie scheint nicht mehr so scharf wie vorher zu sein.
			Während du noch am schleifen warst, hat sich Schnapper mucksmäuschenstill davongeschlichen.`n`n
			`&Deine Waffe wird schwächer!');
			$weapondmg=$session['user']['weapondmg']-1;
			$weaponvalue=round($session['user']['weaponvalue']*0.95);
			item_set_weapon($session['user']['weapon'], $weapondmg, $weaponvalue, 0, 0, 1);
			break;
		case 2:
			output('`@Nachdem du etliche Minuten an deinem `4'.$session['user']['weapon'].'`@ geschliffen hast, scheint sich seltsamerweise überhaupt nichts getan zu haben.
			Während du noch am schleifen warst, hat sich Schnapper mucksmäuschenstill davongeschlichen.`n`n
			`&Der Schleifstein zeigt keine Wirkung!');
			break;
		case 3:
			output('`@Nachdem du einige Minuten an deiner `4'.$session['user']['weapon'].'`@ geschliffen hast, merkst du deutlich eine Zunahme der Angriffskraft.
			Du willst Schnapper für die hervorragende Qualität des Schleifsteins danken, doch er ist schon weitergegangen und verkauft seine Würstchen auf der anderen Seite des Platzes.`n`n
			`&Deine Waffe wird stärker!');
			$weapondmg=$session['user']['weapondmg']+1;
			$weaponvalue=round($session['user']['weaponvalue']*1.05);
			item_set_weapon($session['user']['weapon'], $weapondmg, $weaponvalue, 0, 0, 1);
		}
		//switch
	}
	//else (genug Gold)
}
// End Schleifstein
elseif ($_GET['op']=='k') //Katze im Sack
{
	if ($session['user']['gems']<$surprisecost)
	{
		output('`@Schnapper schaut dich böse an. `5"Da bietet man dir das Geschäft deines Lebens an und du hast noch nichtmal genug Edelsteine dabei!"`@');
	}
	else
	{
		$session['user']['gems']-=$surprisecost;
		output('`@Schnapper öffnet seinen Bauchladen und holt einen kleinen Leinenbeutel hervor.`n
		`5"Öffne diesen Beutel bei Vollmond und du wirst reich belohnt werden."`@`n
		Misstrauisch beäugst du den Beutel, packst ihn aber dennoch ein und wartest auf den Vollmond.`n');
		item_add($session['user']['acctid'],'piginapoke');
	}
}
// End Katze im Sack
if ($session['user']['hitpoints']>0)
{
	addnav('Zurück zum Marktplatz','market.php?op=schnapper');
}
page_footer();
?>

