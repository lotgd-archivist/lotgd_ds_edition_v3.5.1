<?php

// 21072004

/*
* Old Drawl
* Figur erfunden von LordRaven
*
* Old Drawl ist geschaffen worden, um den Spielern in der Kneipe Specials zu ermöglichen, die Ihnen das
* Spiel ein wenig erleichtern. Allerdings soll das Ansprechen von Old Drawl sowei das Benutzen seiner
* Fähigkeiten auch ein Risko enthalten. Es kann sein das er den abgesprochenen Preis nicht einhält,
* ausflippt und den Fragenden verletzt, so daß dieser einen Charmepunkt verliert etc.
* Außerdem kann er schon mal das eine oder andere Spezial verwechseln und der Benutzer bekommt für den Preis
* eventuell weniger oder aber auch ein besseres Special
* Die Risikoidee ist in dieser Version 1.0 noch nicht enthalten.
*
* Version:    1.0 vom 24.04.2004
* Version:    1.1 Debuglog hinzugefügt - 25.04.2004 LordRaven
* Version:    1.2 Zufallsfunktion für böse Attacken eingefügt - 26.04.2004 LordRaven
* Version:    1.3 Old Drawl das Erschlagen des Fragenden auf Zufallsbasis wegen Balancing eingebaut
*					mod by talion: Ganz gemeine Erfahrungsverluste
* Author:     LordRaven
* Email:      logd@lordraven.de
*
* Leichtes Balancing, debuglog entschlackt (anp)
*/
require_once "common.php";
page_header("Old Drawls Tisch");

$config = utf8_unserialize($session['user']['donationconfig']);

if ($_GET['op']=='speak')
{
	$str_title = ($session['user']['sex'] ? 'Meein Töchterleeein':'Meein Sooohn');
	$zufall = e_rand(1,8);
	output('`c`b`]Old Drawls Stammtisch`0`b`c
	`n`(D`)u `7h`_a`[st es gewagt und Old Drawl angesprochen.
	Langsam dreht der alte Mann seinen Kopf zu dir herum und schaut dich durchdringend aus seinen alten Augen an.
	Dir kommt es so vor, als wären sie gelb.
	Als er zu sprechen beginnt, wird dir klar, woher sein Name kommt.
	Schleppend setzt `_e`7r `)a`(n:`n`n');
	if ($zufall!=7)
	{
		output('`8"'.$str_title.', was stööörst Du meiiiineee Ruuuuuheeee?
		Saaag was Duuu voooon mirrrr willlst unnnd daaann laaass miiiich innn Ruuuuheeee. 
		Fooollgeendee Aaktiooneennn kann iiich Diir anbiiieteeenn.
		Abeeerrrr giiiib acht - irrgeendwiiieee haaabbeee iiicchhhh maanchmaaal meeiinnee Kräääftteee niiicht meeeehr iimmeeerr uunterrr Kooontroolleee."');
		addnav('Old Drawl Aktionen');
		addnav('3x Goldmine','olddrawl.php?op=do&action=goldmine');
		addnav('Lotterie spielen','lottery.php');
	}
	else
	{
		output('`8"'.$str_title.', was stööörst Du meiiiineee Ruuuuuheeee?
		Haabeenn Diir dieee Waarnungennn niiicht gerreicht?
		Muussssteeest Duu uuunbeeeddinngt meeiiiinee Ruuheee stööörenn?
		Icchhh haabee voon solcheeen Abstauuuubernn wiiee Diiir diee Naseee volll!!"
		`n`n`[Old Drawl macht eine Faust, holt aus und ');
		switch(e_rand(1,5))
		{
			case 1:
				output('`[trifft dich mitten im Gesicht, so dass eine hässliche Beule enteht. Die Wucht schleudert dich bis an den Tresen zurück.');
				output('`n`n`&Du hast 2 Charmepunkte verloren.');
				$session['user']['charm']=max(0,$session['user']['charm']-2);
				break;
			case 2:
				output('`[trifft dich am Körper. Die Wucht schleudert dich bis an den Tresen zurück.');
				/*
				//Viiiiiiiiieeeeeeeel zu gefährlich! Bekomm das als Bauernjunge Level 1 5x und du bist dauertot!!
				// Naja, gibt ja ne Sperre (6 LP minimum in newday.php), aber das muss man ja nicht ausreizen
				output("`n`n`@Du hast `42 Lebenspunkte`@ verloren.");
				$session['user']['maxhitpoints']-=2;
				*/
				output('`n`n`[Du hast fast alle deine Lebenspunkte verloren.');
				if ($session['user']['hitpoints']>1) $session['user']['hitpoints']=2;
				break;
			case 3:
				output('`[greift dir in die Tasche und klaut dir deinen Geldbeutel mit '.$session['user']['gold'].' Gold.');
				$session['user']['gold']=0;
				break;
			case 4:
				output('`[trifft dich so hart, dass du tot umfällst und noch dazu 8% deiner Erfahrung verlierst.
				`nDu kannst morgen wieder spielen.');
				killplayer(100,8,0);
				debuglog('hat '.$session['user']['gold'].' Gold und 2 Edelsteine bei Old Drawl verloren');
				$session['user']['gems']=max(0,$session['user']['gems']-2);
				addnews('`%'.$session['user']['name'].' `0wurde von Old Drawl erschlagen, als '.($session['user']['sex']?'sie':'er').' ihn angesprochen hat.');
				break;
			case 5:
				output('`[haut voll daneben und fällt dabei unsanft auf den Boden. Er hatte wohl schon das eine oder andere Ale zuviel. \'Puh\', denkst du, \'Glück gehabt...\'');
				break;
		}
	}
}

else if ($_GET['op']=='do')
{
	if ($_GET['action']=='goldmine')
	{
		output('`c`b`]Old Drawls Stammtisch`0`b`c
		`n`[Für die Aktion `^3 mal Goldmine im Wald `[verlangt Old Drawl `^2 `[Edelsteine.
		`[Aber achte darauf, dass sie nach wie vor einstürzen kann und es keine Garantie für eine erfolgreiche Suche gibt.
		Außerdem verlierst du jeweils mindestens eine Runde und musst 3 Runden übrig haben, um die Mine betreten zu können.
		`n`nWillst du ihm die 2 Edelsteine geben?');
		addnav('E?Zwei Edelsteine geben','olddrawl.php?op=do&action=goldmine2');
		addnav('A?Zurück zur Auswahl','olddrawl.php?op=speak');
	}
	else if ($_GET['action']=='goldmine2')
	{
		output('`c`b`]Old Drawls Stammtisch`0`b`c`n');
		if ($session['user']['gems'] >= 2)
		{
			if ($session['user']['gems'] >= 2 && $config['goldmine']==0 && $config['goldmineday']==0)
			{
				$config['goldmine'] += 3;
				$config['goldmineday']=1;
				$session['user']['gems'] -= 2;
				output('`[Old Drawl gibt dir eine halb zerfallene Karte zur Goldmine.
				Du wirst sie wohl tatsächlich nur 3 mal verwenden können.`n`n`n');
				debuglog('Old Drawl macht Zugang zur Goldmine auf');
			}
			elseif ($config['goldmineday']==1)
			{
				output('`n`n`[Old Drawl ist heute zu müde, um dir helfen zu können - komm morgen wieder!');
			}
			else
			{
				output('`[Du hast noch '.$config['goldmine'].' freie Zugänge zur Goldmine zur Verfügung, komme wieder wenn diese verbraucht sind.');
			}
		}
		else
		{
				output("`n`n`[Du hast nicht genügend Edelsteine zur Verfügung.");
		}
	}
}

else
{
	output('`c`b`]Old Drawls Stammtisch`0`b`c
	`n`(D`)u`7 s`_i`[ehst, wie die Leute in der Kneipe immer wieder misstrauisch auf einen Tisch in der Ecke der Kneipe blicken und sich leise über einen alten Mann unterhalten.
	Im Lärm der Kneipe verstehst du immer nur Wortfetzen aus den Gesprächen, aber daraus geht für dich hervor, dass die Leute früher großen Nutzen durch diesem alten Mann hatten, dieser aber mittlerweile wohl verrückt geworden ist und ihn die Leute deswegen lieber meiden, bevor ihnen Schlimmes passiert, sie gar `bErfahrung verlieren`b.
	`n`nDie Neugier siegt in dir und du trittst vorsichtig an den Tisch, wo immer der alte Kauz, den alle Old Drawl nennen, sitzt und schweigsam sein Ale trinkt.
	Du weißt nicht wieso, aber irgendwie scheint dieser alte Mann ein Geheimnis zu verbergen und dein Gefühl sagt dir, dass es dir irgendwie nütztlich sein kann Old Drawl anzusprechen.
	`n`nDu bist verunsichert, was du tun sollst. Sprichst du ihn an oder gehst du lieber wieder zurück an die T`_h`7e`)k`(e?');
	addnav('Old Drawl ansprechen','olddrawl.php?op=speak');
}

if ($session['user']['alive']==true) 
{
	addnav('Zurück an die Theke','inn.php');
}
$session['user']['donationconfig'] = utf8_serialize($config);
page_footer();
?>