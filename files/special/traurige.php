<?php

/*
* BE, Die Traurige
*
*@author: Jenutan for atrahor.de
*/

require_once 'common.php';

if (!isset($session))
{
	exit();
}

//Eine Überschrift
$str_output ='
`c`T<big>Die Traurig<sub>e</sub></big>`c`n
`n
';

//BE-Filename (ggf. Umbenennen!)
$specialinc = "traurige.php";

//Bekommt man einen Schlüssel?
$getkey = false;

//Verliert man seinen Schlüssel?
$losekey = false;

//Kampf?
$battle = false;

//Fluch - schwach
$buffweak = array(
	"name"          =>"`TFluch der Göttin Artio",
	"rounds"        => 50,
	"wearoff"       => "`TDer Fluch der Göttin Artio verlässt dich, du hast genug gelitten.`7",
	"defmod"        => 0.95,
	"atkmod"        => 0.95,
	"roundmsg"      => "Der Gedanke an deine bösartige Tat lähmt dich!",
	"activate"      => "offense,defense",
	"survivenewday" => "1"
);

//Fluch - böse
$buffdeadly = array(
	"name"          => "`4Böser `TFluch der Göttin Artio",
	"rounds"        => 100,
	"wearoff"       => "`TDer `4böse `TFluch der Göttin Artio verlässt dich, du hast genug gelitten.`7",
	"defmod"        => 0.55,
	"atkmod"        => 0.55,
	"roundmsg"      => "Der Gedanke an deine bösartige Tat lähmt dich sehr!",
	"activate"      => "offense,defense",
	"survivenewday" => "1"
);

//Troll
$badguyTroll = array(
	"creaturename"      => "`Tbrauner `2Troll`0",
	"creaturelevel"     => $session['user']['level'],
	"creatureweapon"    => "Große Faust",
	"creatureattack"    => (int)($session['user']['attack'] * 1.1),
	"creaturedefense"   => (int)($session['user']['defence'] * 0.9),
	"creaturehealth"    => (int)($session['user']['maxhitpoints'] * e_rand(25,200) / 100),
	"diddamage"         => 0
);

//Hat man denn schon einen Schlüssel? - START
$int_count = item_count("tpl_id = 'rosekey' AND owner = ".$session['user']['acctid']);
//hat man denn schon einen (Schlüssel)? - ENDE

if ($int_count>0)
{
	$str_output .='
	`@Du hörst wieder so ein Jammern, doch als du nachsehen willst,`n
	ist keiner mehr da!`n
	`n
	';

	switch (e_rand(1,3))
	{
	case 1:
		$str_output .='
		`3Aber dafür findest du ein `^wenig Gold `3auf dem Boden herumliegen...
		';

		//Goldausgabe (abhängig vom Level + Zufall)
		$session['user']['gold'] += $session['user']['level'] * e_rand(50,100);
		break;
	case 2:
		$str_output .='
		`4Diese Aktion kostete dich einen Waldkampf!
		';

		//Runden senken (ggf. wieder auf 0 hoch)
		$session['user']['turns']--;
		if ($session['user']['turns'] < 0)
		{
			$session['user']['turns'] = 0;
		}
		break;
	case 3:
		//Schlüssel löschen
		$losekey = true;
		$str_output .='
		`3Aber dir fällt eine kleine Luke am Boden auf,`n
		`tWer hat die denn bitte dahingebaut?`3, fragst du dich ernsthaft als du bereits den Rosenschlüssel probierst...`n
		Und er passt und bricht ab!`n
		`n
		';

		switch (e_rand(1,4))
		{
			default:
			$str_output .='
			`4Doch leider ist nichts darin!

			Traurig machst DU dich davon...
			';
						//Nichtssagender Newseintrag
			addnews("`3".$session['user']['name']." `3öffnete eine Luke, die leer war...");

			//Trostpreis ;)
			$session['user']['donation'] ++;
			break;
		case 1:
			$str_output .='
			`3Du findest eine komisch riechende Creme, die deinen `@Angriff stark steigert`3!

			Schnell machst du dich glücklich davon...
			';

			//Angriff erhöhen
			$session['user']['attack']  += 2;

			//Nichtssagender Newseintrag
			addnews("`3".$session['user']['name']." `3öffnete eine Luke und wurde dabei stärker...");
			break;
		case 2:
			$str_output .='
			`3Du findest eine komisch riechende Creme, die deine `@Verteidigung stark steigert`3!

			Schnell machst du dich glücklich davon...
			';

			//Verteidigung erhöhen
			$session['user']['defence'] += 2;

			//Nichtssagender Newseintrag
			addnews("`3".$session['user']['name']." `3öffnete eine Luke und wurde dabei stärker...");
			break;
		}
		//end switch
		break;
		default: //sollte nicht auftreten
		$str_output .='
		Unter der Luke ist nichts als Erde. Eigentlich war dir ja klar, dass eine Luke im Wald nirgendwo hinführen kann.';
		$session['user']['specialinc']='';
	}
	//end switch
}
else
{
	//Hat man keinen Schlüssel
	switch ($_GET['op'])
	{
		//Für Fehler im Code...
		default:
		$str_output .='
		Na das war ja wohl überhauptnichts! Was auch immer du getan hast um hier hinzukommen, es hat nicht funktioniert.';
		$session['user']['specialinc']='';
		break;
	case "":
		$str_output .='
		`@Als du mal wieder auf der Suche nach etwas zum Jagen und gerade auf dem Weg von der Stadt zur Lichtung im Wald bist,
		ertönt von irgendwoher ein Jammern.`n
		`n
		Lieber '.create_lnk("nachsehen","forest.php?op=nachsehen",true,true,'',false,'Nachsehen',CREATE_LINK_LEFT_NAV_HOTKEY).'?`n
		Oder doch besser '.create_lnk("weiter gehen","forest.php?op=weitergehen",true,true,'',false,'Weiter gehen',CREATE_LINK_LEFT_NAV_HOTKEY).'?
		';

		//Den User beim BE halten
		$session['user']['specialinc'] = $specialinc;
		break;
	case "weitergehen":
		$str_output .='
		`@Du gehst schnell weiter, bis das Jammern nicht mehr zu hören ist.`n
		Doch dich beschleicht das Gefühl, dass du jemandem in Not nicht geholfen hast...`n
		`n
		`%Du verlierst etwas Charme!
		';

		//5 Charmpunkte verlieren, ggf. wieder auf 0 zurücksetzen...
		$session['user']['charm'] -= 5;
		if ($session['user']['charm'] < 0)
		{
			$session['user']['charm'] = 0;
		}
		break;
	case "nachsehen":
		$str_output .='
		`@Vorsichtig gehst du den Geräuschen nach `n
		und nach kurzer Zeit findest du ein kleines zusammengekauertes (Menschen-)Mädchen.`n
		In ihren dreckigen Sachen sitzt sie da.`n
		`n
		Was fühlst du?`n'.
		create_lnk("Mitleid","forest.php?op=mitleid",true,true,'',false,false,CREATE_LINK_LEFT_NAV_HOTKEY).',`n'.
		create_lnk("Wut","forest.php?op=wut",true,true,'',false,false,CREATE_LINK_LEFT_NAV_HOTKEY).',`n'.
		create_lnk("Gleichgültigkeit","forest.php?op=gleichgueltigkeit",true,true,'',false,false,CREATE_LINK_LEFT_NAV_HOTKEY).'`n
		oder '.create_lnk("Verachtung","forest.php?op=verachtung",true,true,'',false,false,CREATE_LINK_LEFT_NAV_HOTKEY);
		//Den User beim BE halten
		$session['user']['specialinc'] = $specialinc;

		break;
	case "mitleid":
		$str_output .='
		`@Du willst auf das arme Mädchen zugehen und ihr helfen...`n
		`n
		';

		//Wird die Geschichte weitererzählt?
		$endstory = false;

		if ($session['user']['sex'])
		{
			//Weibliche Spieler werden hier bevorzugt ;)
			$endstory = true;
		}
		else
		{
			//Männliche Spieler
			switch (e_rand(1,3))
			{
			case 1:
				$endstory = true;
				break;
				default:
				$str_output .='
				`3Das Mädchen hat `TAngs<sub>t</sub> vor dir und rennt davon`3!`n
				Ehe du dich besinnst, ist sie schon verschwunden...`n
				`n
				Ein wenig mürrisch kehrst du um...`n
				Doch die Tatsache, dass du nur helfen wolltest bringt dir `%etwas Charme`3!`n
				';

				//Charme vergeben
				$session['user']['charm'] += 5;
				break;
			}
		}

		//Das Ende der Geschichte (?)
		if ($endstory)
		{
			$str_output .='
			`@Vorsichtig nimmst du sie in den Arm und tröstest sie, bis sie aufhört zu weinen.`n
			Als du sie hochheben willst, findest du `Teinen alten, rostigen Schlüssel`@...`n
			`n
			Zurück in der Stadt übergibst du sie an ein paar Priesterinnen,`n
			die sich liebevoll um sie kümmern.`n
			`n
			Aber wofür hatte sie einen Schlüssel bei sich?`n
			Als du ihn näher betrachtest, ist dort eine Rose eingeritzt.`n
			Ob sie etwas zu bedeuten hat?`n
			`n
			Etwas verwirrt machst du dich auf,`n
			zurück in den Wald...`n
			';

			//Schlüssel geben
			$getkey = true;
		}
		break;
	case "wut":
		$str_output .='
		`@Bei diesem Anblick packt dich die `$blanke Wut`@, und du stürmst auf das kleine Mädchen zu!`n
		`n
		Doch plötzlich erscheint ein riesiger, `Tbrauner `2Troll `@wie aus dem Nichts vor ihr und greift dich an...`n
		';

		//Troll als Gegner schreiben und Kampf aktivieren
		$session['user']['badguy'] = createstring($badguyTroll);

		$battle = true;

		break;
	case "gleichgueltigkeit":
		$str_output .='
		`@Dir ist das Mädchen egal.`n
		Und noch ehe du darüber genauer nachgedacht hast,`n
		erscheint Göttin Artio vor dir, und nimmt das Mädchen in ihren Schutz.`n
		`n
		"`$Du elender Narr`@", brüllt sie dich an. "`$Dieses Kind hätte sterben können!`n
		Für deine Feigheit wirst du bestraft!`@".`n
		`n
		`tMir doch egal`@, denkst du...`n
		So sicher bist du dir da allerdings nicht...`n
		';

		//Schwachen Fluch aktivieren
		buff_add($buffweak);
		break;
	case "verachtung":
		$str_output .='
		`@Mit einem verachtenden Blick läufst du hochnäsig an dem kleinen Mädchen vorbei,`n
		solch einem Gassenkind schenkst du doch keine Aufmerksamkeit!`n
		`n
		Und noch ehe du darüber genauer nachgedacht hast, was du eben getan hast,`n
		erscheint Göttin Artio hinter dir, und nimmt das Mädchen in ihren Schutz.`n
		`n
		"`$Du elender Narr`@", brüllt sie dich an. "`$Dieses Kind hätte sterben können!`n
		Für deine Hochnäsigkeit wirst du bestraft!`@".`n
		`n
		Ein Fluch trifft dich schmerzhaft in den Rücken,`n
		das wird dir noch lange im Gedächtnis bleiben!
		';

		//Bösen Fluch aktivieren
		buff_add($buffdeadly);
		break;
	case "run":
		$str_output .='
		"`c`b`4Du kannst jetzt nicht fliehen!`0`b`c`n`n
		';
		$battle = true;
		break;
	case "fight":
		$battle = true;
		break;
	}
	//end switch (op)
}
//end else (ob man einen Schlüssel hat)

output($str_output,true);

//KampfScript
if ($battle)
{
	//Battle-Script includen
	include("battle.php");

	//Special-Include setzen
	$session['user']['specialinc'] = $specialinc;

	//Falls man gewinnt
	if ($victory)
	{
		//Gegner + BE löschen
		$session['user']['badguy']      = array();
		$session['user']['specialinc']  = "";

		//Gegner besiegt
		output('`n`n
		`@Der Troll geht vor dir in die Knie. Du hast den Kampf gewonnen!`n
		`n
		Aber das Mädchen ist genauso plötzlich verschwunden, wie der Troll erschienen ist.`n
		War sie der Troll?`n
		`n
		Beim Durchsuchen des Trolls findest du einen Edelstein!`n
		Und einen komischen `Talten, rostigen Schlüssel`@ auf dem eine Rose eingeritzt ist...`n
		`n
		`tWofür der wohl gut ist?`@, fragst du dich.`n
		Natürlich steckst du ihn sofort mit ein.
		');

		//Edelstein + Schlüssel ausbezahlen
		$session['user']['gems'] ++;
		$getkey = true;

	}
	else if ($defeat)
	{
		//Gegner + BE löschen
		$session['user']['badguy']      = array();
		$session['user']['specialinc']  = "";

		output('`n`n
		`@Der Troll holt aus und schlägt dich nieder!`n
		`n
		`4Als du wieder aufwachst,`n
		befindest du dich im Totenreich.`n
		`n
		Allerdings war dir das eine Lehre, dein ganzes Gold ist weg!`n
		Aber deine Erfahrung steigt ein wenig!
		');
		//Erfahrung kriegen, Gold verlieren und dann sterben
		$session['user']['experience'] *= 1.02;
		addnews('`%'.$session['user']['name'].'`5 weiß nun, dass man sich besser nicht mit Trollen anlegt.');
		killplayer(100, 0, false, 'shades.php', 'Zu den Schatten');
	}
	else
	{
		fightnav(true,true);
	}
}
//End of Battle

//Bekommt man einen Schlüssel?
if ($getkey)
{

	item_add($session['user']['acctid'],'rosekey');
}

//Muss der Schlüssel gelöscht werden?
if ($losekey)
{
	item_delete("tpl_id = 'rosekey' AND owner = ".$session['user']['acctid'],1);
}
?>