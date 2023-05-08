<?php

// Knappen können nützlich sein und eine Stufe aufsteigen... oder auch sterben
// By Maris (Maraxxus@gmx.de)

if (!isset($session))
{
	exit();
}

$specialinc_file = "wolves.php";
require_once(LIB_PATH.'disciples.lib.php');
$rowk = get_disciple ();

if ($_GET['op'] == "askforhelp")
{
	output("`#Du rufst nach deinem treuen Knappen und ".$rowk['name']."`# zieht sein kleines Schwertchen und stellt sich damit schützend vor dich.`n ");
	output("`#Dann kommen die Wölfe... und der Größte von ihnen stürzt sich auf deinen Knappen.`n");
	$session['user']['specialinc'] = "";
	switch (e_rand(1,6))
	{
	case 1 :
	case 2 :
	case 3 :
	case 4 :
	case 5 :
		output('`#Irgendwie gelingt es '.$rowk['name'].'`#, dem Leitwolf im Sprung sein kleines Schwert in die Kehle zu rammen.
		`nDas Tier wälzt sich eine Weile zuckend und jaulend auf dem Boden, dann bleibt es regungslos liegen.
		`nDie anderen Wölfe entfernen sich rasch.
		`nGemeinsam schafft ihr es, dein Bein aus der Falle zu befreien und ihr seht zu, dass ihr das Weite sucht bevor die Bestien wiederkommen.
		`n`n`4Du hast fast alle deine Lebenspunkte verloren.
		`n`@Für deinen Knappen war dies eine besondere Erfahrung im Kampf, die ihn voranbringt!`0`n');
		$session['user']['hitpoints']=1;
		output(disciple_levelup());
		
		addnav("Zurück zum Wald","forest.php");
		break;
	case 6 :
		if($session['user']['exchangequest']!=29 && $session['user']['specialtyuses']['wisdomuses']>0 && $rowk['level']%2!=0)
		{
			output('Fieberhaft überlegst du, was du in dieser Situation tun könntest. Im letzten Moment kommt dir der rettende Gedanke: Du stößt '.$rowk['name'].'`# beiseite, greifst dein Stiefelmesser und hältst es dem Wolf entgegen. Der Wolf kann nicht mehr reagieren und springt genau in die Klinge deines Stiefelmessers.
			`nIm Todeskampf gräbt er noch seine Zähne tief in deinen Arm und auch '.$rowk['name'].'`# kommt nicht ungeschoren davon.
			`n`4Diese Aktion kostet dich eine Anwendung in `&Weisheit`4. Du hast fast alle deine Lebenspunkte und 3 Waldkämpfe verloren, dein Knappe verliert ein Level. Aber wenigstens habt ihr überlebt.');
			$session['user']['hitpoints']=1;
			$session['user']['specialtyuses']['wisdomuses']--;
			$session['user']['turns']-=3;
			if ($session['user']['turns']<0)
			{
				$session['user']['turns']=0;
			}
			unset($session['bufflist']['decbuff']);
			if($rowk['level']>0)
			{
				$sql='UPDATE disciples SET level=level-1 WHERE master='.$session['user']['acctid'];
			}
		}
		else
		{
			output('`#Dein kleiner Begleiter hat keine Chance und du kannst nichts für ihn tun.
			`nDie Wölfe zerreissen '.$rowk['name'].'`# und fressen sich vor deinen Augen an ihm satt.
			`nDoch dann lassen sie dich links liegen und trotten zurück in den tieferen Wald.
			`nDu brauchst eine halbe Ewigkeit dich zu befreien.
			`n`4Du hast fast alle deine Lebenspunkte und 5 Waldkämpfe verloren. Dein Knappe ist tot!`0
			`n');
			$session['user']['hitpoints']=1;
			$session['user']['turns']-=5;
			if ($session['user']['turns']<0)
			{
				$session['user']['turns']=0;
			}
			
			disciple_remove(true);
			
			debuglog("Verlor einen Knappen beim Wolf-Event im Wald.");
		}
		break;
		
	}
}
else if ($_GET['op'] == "sendaway")
{
	output("`#Du rufst laut : \"`5".$rowk['name']."`5, lauf! Hol Hilfe!`#\"`n");
	output("Dein Knappe läuft so schnell er kann fort.`n`n");
	switch (e_rand(1,3))
	{
	case 1 :
	case 2 :
		output("`#Die Zeit vergeht, es kommt dir vor wie Stunden.
		`nDie Wölfe kommen immer näher, du kannst schon ihre stechenden Augen im Unterholz erkennen.
		`nDann plötzlich schrecken die Tiere auf und rennen davon als `^".$rowk['name']."`# mit einer kleinen Gruppe Feldarbeiter erscheint.
		`nDie kräftigen Männer helfen dir, dich von der Falle zu befreien und stützen dich auf deinem Weg fort von hier.
		`n`4Du hast fast alle deine Lebenspunkte und 3 Waldkämpfe verloren!`0
		`n");
		$session['user']['hitpoints']=1;
		$session['user']['turns']-=3;
		if ($session['user']['turns']<0)
		{
			$session['user']['turns']=0;
		}
		$session['user']['specialinc'] = "";
		addnav("Zurück zum Wald","forest.php");
		break;
	case 3 :
		output("`#Doch als er mit Hilfe wiederkehrt findet er nur noch deine gründlich abgenagten Knochen und deine zerrissene Ausrüstung bei der Falle wieder.
		`n`4Du bist tot!`0`n");
		$session['user']['hitpoints']=0;
		$session['user']['specialinc'] = "";
        CQuest::died();
		addnews($session['user']['name']."`@ wurde im Wald von Wölfen gefressen!`n");
		addnav("Weiter","shades.php");
		break;
	}
}
else
{
	output('`#Du gehst ahnungslos deines Weges, als du plötzlich ein lautes, peitschenähnliches Geräusch direkt unter dir vernimmst. Sofort steigt ein brennender Schmerz dein Bein hinauf und deine Knie knicken weg. Halb bewusstlos vor Schmerz erkennst du die scharf gezackten Bügel einer unter Blättern verborgenen großen Wildfalle, in die du gerade gelaufen bist.`0`n`n');
	
	if ($rowk['state']>0 && $rowk['state']!=22)
	{
		output("`#Zu allem Übel hörst du noch das Heulen mehrerer Wölfe, die wohl die Witterung deines Blutes aufgenommen haben und bald bei dir sein werden.
		`nAber zum Glück ist ja dein Knappe `^".$rowk['name']."`# in deiner Nähe.
		`nObwohl er fast noch ein Kind ist, könnte er dich aus dieser Notlage befreien, allerdings könnte er auch genauso gut sein Leben hierbei verlieren...
		`n`nWas tust du?`0");
		
		$session['user']['specialinc'] = $specialinc_file;
		addnav('r?'.$rowk['name'].' rufen','forest.php?op=askforhelp');
		addnav('f?'.$rowk['name'].' fortschicken','forest.php?op=sendaway');
	}
	else
	{
		output("`#Es dauert eine halbe Ewigkeit bis du dich aus der Falle befreit hast.
		`nDu greifst dir einen langen Stock als Stütze und humpelst davon.
		`n`4Du hast fast alle deine Lebenspunkte und 5 Waldkämpfe verloren!`0`n");
		$session['user']['hitpoints']=1;
		$session['user']['turns']-=5;
		if ($session['user']['turns']<0)
		{
			$session['user']['turns']=0;
		}
		addnav("Zurück in den Wald","forest.php");
	}
}
?>
