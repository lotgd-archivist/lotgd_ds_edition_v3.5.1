<?php

// 22062004

/*************************
Vampire's Lair
Special Event/Add-on
for LoGD
by Mike Counts (genmac)
- Dec. 2003

Install:

-Special event: copy vampire.php into /special directory.

Add-on: copy vampire.php into main LoGD directory, add
link from village.php or wherever you wish.

***

modifications and translation by anpera
special event ONLY!!!

This event can regulate the max hp a player can have to prevent
powergamers from becoming overpowered

in configuration.php somewhere after:
$setup = array(
add:
	"limithp"=>"max maxhitpoints a character can keep (Level*12+HPfromDP+x*DK (0=no limit)),int",
*************************/

if (!isset($session)) exit();
$session['user']['specialinc']="vampire.php";

$dkhp = 0;
foreach ($session['user']['dragonpoints'] AS $key => $val)
{
    if ($val=="hp") $dkhp++;
}

$maxhp = get_max_hp();
$minhp=10*$session['user']['level']+5*$dkhp;

$lifecost = 5;
$gemgain = round($lifecost/2);
$goldgain = $lifecost*100;

if($_GET['op']=='continue')
{
	output('`c`b`ZDas Lager des Vampirs`b`c`n`TEin bösartiges Wesen manifestiert sich aus dem Nichts vor dir. Du erzitterst aus Furcht vor dieser uralten Macht, die von dieser Kreatur ausgeht. Dir bleibt kaum Zeit, das Geschehene richtig zu verkraften, als du bereits die dunkle, Unheil verheißende Stimme der Kreatur vernimmst: `4"Sterblicher, ich spüre viel Lebenskraft in dir. Da ich alt werde, schwindet mein Verlangen zu jagen. Im Austausch für ein kleines bisschen deiner permanenten Lebenskraft, gewähre ich dir Kräfte außerhalb deiner Vorstellungskraft." `TErst jetzt erkennst du, dass du einem uralten Vampir gegenüberstehst, der auf deine Entscheidung wartet. Kannst du seinen Worten Glauben schenken und wirst du es wirklich wagen, diesen Schritt zu gehen?');
	if($session['user']['maxhitpoints']>$lifecost){
		addnav('A?Biete '.$lifecost.' Lebenspunkte für Angriff','forest.php?op=str');
		addnav('V?Biete '.$lifecost.' Lebenspunkte für Verteidigung','forest.php?op=def');
		addnav('R?Biete '.$lifecost.' Lebenspunkte für Reichtum','forest.php?op=wealth');
		addnav('~');
	} else{
		addnav('Nicht genug Lebenskraft');
	}
	addnav('Flüchte in Furcht','forest.php?op=leave');
}
else if ($_GET['op']=='leave')
{
	if ($session['user']['marks']<32)
	{
	// addnav("Zurück in den Wald","forest.php");
	if (getsetting("limithp",0)>0 && $session['user']['maxhitpoints']>$maxhp)
		{
			$losthp=$session['user']['maxhitpoints'];
			$session['user']['maxhitpoints']=max(round($session['user']['maxhitpoints']*0.9),$maxhp);
			$losthp-=$session['user']['maxhitpoints'];
			$exp=$losthp*10;
			$session['user']['experience']+=$exp;
			output('`TAusgehungert und vom Geruch deiner enormen Lebenskraft fast wahnsinnig überwältigt dich ein Vampir auf deiner Flucht und saugt dir das Blut aus den Adern. Als er endlich satt ist, verschwindet er so lautlos und schnell wie er kam im Wald.`n`n`TDu hast `$'.$losthp.'`T Lebenspunkte `bpermanent`b verloren.');
			debuglog('verlor '.$losthp.' permanente Lebenspunkte auf der Flucht vor dem Vampir im Wald.');
			output('`n`TDu hast deine Lektion gelernt und bekommst `$'.$exp.'`T Erfahrungspunkte.');
			if ($session['user']['turns']>0)
			{
				output('`n`TDu fühlst dich schlapp und verlierst einen Waldkampf.');
				$session['user']['turns']--;
			}
			addnews('`%'.$session['user']['name'].'`7 hatte im Wald eine folgenschwere Begegnung mit einem Vampir.');
		}
		else
		{
			output('`n`TDu verlässt diesen verfluchten Ort so schnell du kannst.');
			if (getsetting("limithp",0)>0 && $session['user']['charm']>250){
				$save=$session['user']['charm'];
				$session['user']['charm']=max(round($session['user']['charm']*0.9),250);
				$save-=$session['user']['charm'];
				output(' `TDennoch sitzt dir der Schreck noch tief in den Knochen, schon bald wirst du merken, dass deine Haare '.($save>10?'deutlich':'etwas').' grauer geworden sind.');
				debuglog('verlor '.$save.' Charmepunkte auf der Flucht vor dem Vampir im Wald.');
			}
		}
		$session['user']['specialinc']='';
	}
	else
	{
		output('`TAusgehungert und vom Geruch deiner enormen Lebenskraft fast wahnsinnig überwältigt dich ein Vampir auf deiner Flucht und setzt seine Zähne an deinen Hals an.`nDoch dann erkennt er das Zeichen des Blutgottes an deinem Hals und weicht in Ehrfurcht zurück.`n`nFür sein grobes Auftreten entschädigt er dich mit `^1000 `TGoldstücken`n');
		$session['user']['gold']+=1000;
		$session['user']['specialinc']='';
		addnav('Weiter','forest.php');
	}
}
else if($_GET['op']=='str' || $_GET['op']=='def' || $_GET['op']=='wealth')
{
	output('`c`b`ZDas Lager des Vampirs`b`c`n');
	if (($session['user']['maxhitpoints']-$lifecost)<$minhp){
		output('`TDer Vampir betrachtet dich ausführlich und für deinen Geschmack etwas zu gründlich. Doch schliesslich meint er nur, dass deine Lebenskraft nicht ausreicht, um ihn zu sättigen. Er lässt dich unangetastet und ohne Belohnung ziehen.');
	}
	else
	{
		$session['user']['maxhitpoints'] -= $lifecost;
		if($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
		output('`n`n`TDu erschauderst, als der Vampir seine Zähne in deinem Hals versenkt. Du fühlst deine Lebenskraft durch die Wunde aus deinem Körper strömen und wie deine Sinne langsam schwächer werden. Im Gegenzug dafür, dass der alte Vampir sich stärken durfte, spricht er einen fluchähnlichen Zauber über dich.`n`n');
		if($_GET['op']=='str')
		{
			$session['user']['attack']++;
			output('Dein Angriffwert erhöht sich vorübergehend um `)1`T und du verlierst `$'.$lifecost.' `Tpermanente Lebenspunkte.');
		}
		else if($_GET['op']=='def')
		{
			$session['user']['defence']++;
			output('Deine Verteidigung erhöht sich vorübergehend um `)1`T und du verlierst`$'.$lifecost.' `Tpermanente Lebenspunkte.');
		}
		else if($_GET['op']=='wealth')
		{
			$session['user']['gold'] += $goldgain;
			$session['user']['gems'] += $gemgain;
			output('Für deine geopferten `$'.$lifecost.'`T permanenten Lebenspunkte gibt dir der Vampir `^'.$goldgain.' `TGold und `#'.$gemgain.' `TEdelsteine.');
		}
	}
	$session['user']['specialinc']="";
	// addnav("Verlasse diesen Ort","forest.php");
} else {
	output('`c`b`ZEin dunkler Weg`b`c`n`TDu stehst vor einem verschlungenen Pfad, der immer tiefer in den dicht verwachsenen Abschnitt des Waldes führt. Ein dunkler Nebel umgibt dich, sodass du kaum die Hand vor Augen sehen kannst und du spürst plötzlich ein kaltes Grausen, das sich in der Luft um dich herum erhebt.');
	if (getsetting("limithp",0)>0 && ($session['user']['maxhitpoints']>$maxhp || $session['user']['charm']>250) && $session['user']['marks']<32)
	{
		output(' `TInstinktiv weisst du, dass du jetzt keine Furcht zeigen darfst.');
	}
	output(' `TAber wirst du es wirklich wagen, dich dem zu stellen, was vor dir liegt?');
	addnav('Gehe tapfer weiter','forest.php?op=continue');
	addnav('Flüchte in Furcht','forest.php?op=leave');
}
?>