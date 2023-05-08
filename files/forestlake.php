<?php

// 30052004

// Datei:  forestlake.php
// Datum:  01.05.2004 ff
// Author: Durandil
// Inhalt: Flirt mit Aurelia / allgemein Flirt mit Partner im Wald.

/* Installation: 
Datei ist kein Waldspecial mehr, einfach verlinken
addnav("Trampelpfad","forestlake.php");
*/
require_once("common.php");

if (!isset($session))
{
	exit();
}
checkday();
page_header('Der Waldsee');
addcommentary();

// Einstellungen ...............................................................
$accountallowed = false;
// Muß auf false stehen... wird später automatisch berechnet.


//$forestlakedebugoutput = false;
// .............................................................................

// Daten des Partners aus Datenbank suchen......................................
$lover = "";
$loverID = $session['user']['marriedto'];
$loverHorse = 'Pferd';
$loverHorseID = 0;
$loved = $session['user']['login'];
$lovedHorse = 'Pferd';
$lovedID = $session['user']['acctid'];
$lovedHorseID = $session['user']['hashorse'];
if ($session['user']['marriedto']>0)
{
	$sql = 'SELECT acctid,login,marriedto,charisma,hashorse FROM accounts WHERE acctid='.$loverID.' ORDER BY acctid DESC';
	$result = db_query($sql);
	if (db_num_rows($result)>0)
	{
		$row = db_fetch_assoc($result);
		$lover = $row['login'];
		$loverHorseID = $row['hashorse'];
	}
}
if ($loverID==4294967295)
{
	$lover = $session['user']['sex']?'Seth':'Violet';
}
if ($row['charisma']>=999 && $row['marriedto']!=$session['user']['acctid'])
{
	$loverID=0;
}

$chat = '_'.min($session['user']['acctid'],$session['user']['marriedto']).'_'.max($session['user']['acctid'],$session['user']['marriedto']);
$chat_tower='Tower'.$chat;
$chat='Clearing'.$chat;

/*if ($forestlakedebugoutput==true)
{
	output('`nlogin: ('.$session['user']['acctid'].') '.$session['user']['login']);
	output('`nhorse: '.$lovedHorseID);
	output('`nlover: ('.$loverID.') '.$lover);
	output('`nhorse: '.$loverHorseID);
	output('`nchat: '.$chat.'`n');
}*/

function lakenav()
{
	global $session,$loverHorseID,$lovedHorseID;
	if (!mb_strpos($session['user']['specialmisc'],'_swim'))
	{
		addnav('Im See schwimmen','forestlake.php?op=swim');
	}
	if (!mb_strpos($session['user']['specialmisc'],'_music'))
	{
		addnav('Musizieren','forestlake.php?op=music');
	}
	if (!mb_strpos($session['user']['specialmisc'],'_picknick'))
	{
		addnav('Picknicken','forestlake.php?op=picknick');
	}
	//if (!mb_strpos($session['user']['specialmisc'],'_flirt'))
	{
		addnav('Plaudern','forestlake.php?op=flirt');
	}
	if ($loverHorseID+$lovedHorseID>0 && !mb_strpos($session['user']['specialmisc'],'_ride') )
	{
		addnav('Ausreiten','forestlake.php?op=ride');
	}
	addnav('Verabschieden','forestlake.php?op=return');
}

switch ($_GET['op'])
{
// Zutritt ...................................................................
case '':
	{
		output('`@Während du durch den Garten läufst, fällt dir ein Baum auf, in den etwas geritzt ist. Aus der Nähe siehst du, dass es ein Herz ist, in das ');
		if ($loverID==0)
		{
			output('`$ Aurelia & Durandil forever');
		}
		else
		{
			output('`$'.$loved.'`$ & '.$lover.'`$ forever');
		}
		output(' `@ geritzt ist.
`@Außerdem fallen dir kleine Zweige auf, die alle so geflochten scheinen, als würden sie in eine bestimmte Richtung zeigen. ');
		if ($loverID>0)
		{
			if ($loverID==4294967295)
			{
				output('Du überlegst, den Pfeilen zu folgen, aber denkst dir dann, dass diese Zeichen bestimmt nicht für dich bestimmt sind, weil dein Schatz ja noch in der Kneipe arbeitet und gar nicht hier im Garten sein kann.`n`n');
				addnav('W?Zum Wohnviertel','houses.php?op=enter');
			}
			else
			{
				output('Du überlegst, den Pfeilen zu folgen, denn irgendwie hast du ein gutes Gefühl dabei und mußt an deinen Schatz denken.`n`n');
				addnav('Folge den Pfeilen','forestlake.php?op=clearing');
			}
		}
		else
		{
			output('Aber da du eh\' Single bist, kann dieses Zeichen gar nicht für dich bestimmt sein, und du ziehst dich diskret zurück.`n`n');
			addnav('W?Zum Wohnviertel','houses.php?op=enter');
		}
		output('`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		addnav('Zurück zum Stadtzentrum','village.php');
		break;
	}

// Auf der Lichtung ..........................................................
case 'clearing':
	{
		$session['user']['specialinc']=''; //falls der User vom Waldspecial kommt
		if (!mb_strpos($session['user']['specialmisc'],'done:'))
		{
			$session['user']['specialmisc']='done:';
		}
		output('`@Nach wenigen hundert Metern kommst du an eine kleine Lichtung, die an einem klaren Waldsee liegt.`n
		Auf den ersten Blick siehst du, dass erst vor kurzem noch jemand hier war. Aber bevor du dir groß Gedanken darüber machen kannst, spürst du wie sich plötzlich von hinten zwei Arme um dich schlingen...`n`n
	`^'.$lover.'`@ küsst dich sanft in den Nacken und flüstert dir ins Ohr: `3"`#Hallo Liebling, schön dass Du hierher gefunden hast.`n
		Was hältst Du davon, wenn wir uns einfach mal einen schönen Tag machen? Auf was hättest Du Lust?`3"`n`n
		`@Auf der Lichtung vor dir bemerkst du nun eine von Rosen umkranzte Decke, neben der auch ein Picknickkorb zu stehen scheint.
		Etwas weiter links lädt der See mit seinem klaren Wasser zum Schwimmen ein.
		Und ganz hinten scheinen die Sonnenstrahlen auf einen umgefallenen Baum, der jedoch noch sehr stabil aussieht und der ideale Platz wäre, sich zu einem kleinen Plausch zu setzen.`n');
		if ($loverHorseID+$lovedHorseID>0)
		{
			output('`@In nicht allzu weiter Ferne über den Baumwipfeln seht ihr zudem eine Turmruine. Wenn ihr wollt, könnt ihr auch gemeinsam einen Ausritt dorthin unternehmen.`n');
		}
		else
		{
			output('`@Wenn einer von euch ein Reittier besitzen würde, könntet ihr auch einen kleinen Ausritt unternehmen - Aber ihr habt ja genug netter Möglichkeiten.`n');
		}
		output('`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		lakenav();
		break;
	}

// Ausreiten .................................................................
case "ride":
	{
		$session['user']['specialmisc'].='_ride';
		// Besorge Namen der Tiere
		$sql = 'SELECT mountname FROM mounts WHERE mountid='.$loverHorseID;
		$result = db_query($sql);
		if (db_num_rows($result)>0)
		{
			$row = db_fetch_assoc($result);
			$loverHorse = $row['mountname'];
		}
		if (($loverHorseID==0) && ($lovedHorseID>0))
		{
			// Du hast ein Pferd
			output('`@Da '.$lover.'`@ kein eigenes Reittier besitzt, stellst du deinen '.$playermount['mountname'].'`@ zur Verfügung. Ihr reitet gemeinsam den See entlang.`n');
		}
		else if(($loverHorseID>0) && ($lovedHorseID==0)) // Der andere hat ein Pferd
		{
			output('`@Du hast zwar kein Reittier, aber '.$lover.'`@ hilft dir und ihr reitet gemeinsam auf '.$loverHorse.'`@ den See entlang.`n');
		}
		else // Beide besitzen Tiere
		{
			output('`@Ihr schwingt euch auf eure Tiere, du auf dein '.$playermount['mountname'].'`@ und '.$lover.'`@ auf einem '.$loverHorse.'`@, und reitet den See entlang.`n');
		}
		output('`@Am hinteren Ende des Sees angekommen seht ihr, dass der Zufluss sich in Richtung der Turmruine durch den Wald windet, und dass es euch ein schmaler Streifen Wiese erlaubt, dorthin zu reiten.`n
		`n`@`^'.$lover.'`@ ruft dir fröhlich zu:`n
		`3"`#Komm schon '.$loved.', wer zuerst da ist gewinnt und darf sich etwas wünschen!`3"`n`n
		`@Kopf an Kopf wetteifernd eilt ihr den Fluss entlang, und schon bald bemerkt ihr, wie der Boden sich hebt und ihr einen Hügel hinaufreitend dem Turm näher kommt, und innerhalb einer knappen Viertelstunde seid ihr an der Ruine angekommen.`n`n
		Der Turm scheint ein alter Signalturm gewesen sein, wirkt allerdings schon ziemlich mitgenommen; die Zinnen über dem dritten Stockwerk sind bereits weggebröckelt, und auch die Decken sind teilweise eingestürzt.`n
		Trotzdem macht die Ruine irgendwie einen romantischen Eindruck, und du kannst dir vorstellen, dass man von oben - sofern die Treppen noch vorhanden sein sollten - bestimmt einen großartigen Ausblick hat. Wollt ihr euch den Turm nichtmal näher anschauen?`n
`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		addnav('Turm untersuchen','forestlake.php?op=towerruin');
		addnav('Zurück zur Lichtung','forestlake.php?op=rideback');
		break;
	}

// Im Turm ...................................................................
case 'towerruin':
	{
		output('`@Ihr betretet Hand in Hand das Erdgeschoß des Turms. Das Glück ist euch hold, denn die Holzdecken oberhalb des ersten Obergeschosses sind zwar eingestürzt, die an den Wänden entlanglaufende Treppe ist jedoch aus Stein gemauert und hält euch, so dass ihr gemeinsam nach oben gehen und die Aussicht genießen könnt.`n
		Und diese ist noch weit schöner als ihr euch vorgestellt habt - soweit das Auge reicht seht ihr grüne Mischwälder, gelegentlich unterbrochen von riesigen erhabenen Sequoias, die noch aus einer früheren Zeit zu stammen scheinen.`n
		Ihr beobachtet, wie der Fluss sich fröhlich in das Tal schlängelt, in den See fliesst, und sich mit kleinen Seitenläufen vereint in der Ferne verliert...`n`n
		Doch während ihr so verschlungen da oben steht, merkt ihr, wie der Wind immer heftiger an euch rüttelt, und als ihr euch umdreht, seht ihr eine dunkle Wetterfront auf euch zuziehen.`n
		Ihr bleibt trotzdem noch eine Weile oben, lasst den Wind durch eure Haare streifen und beobachtet, wie es in der Ferne blitzt und donnert.`n
		Erst als es ungemütlich nass wird, eilt ihr die Treppen wieder herunter, macht es euch in einer trockenen Ecke des Untergeschosses mit ein paar Decken gemütlich und verbringt die Zeit bis zum Ende des Unwetters damit, den Gewalten der Natur zu lauschen und miteinander zu flüstern.`n`n
`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		viewcommentary($chat_tower,'Flüstern',10,'flüstert zärtlich',false,true,false,true,false,true,2);
		addnav('Zurück zur Lichtung','forestlake.php?op=rideback');
		break;
	}
	// Zurück vom Ausritt ........................................................
case 'rideback':
	{
		output('`@Ihr beeilt euch, zurück zur Waldlichtung zu kommen, schließlich wartet da immer noch der Picknickkorb auf euch, und eine Runde im See zu schwimmen wäre auch eine gute Idee. Oder wollt ihr einfach nur ein gemütliches Pläuschchen halten?`n
`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		lakenav();
		break;
	}
	// Zusammen Musik machen .....................................................
case 'music':
	{
		$session['user']['specialmisc'].='_music';
		output('`3"`#Wollen wir nicht zusammen etwas singen?`3"`@ fragt '.$lover.'`@, packt eine Laute aus und fängt an, die Saiten zu stimmen.`n`n
	`@Als '.$lover.'`@ damit fertig ist, stimmst du leise ein Lied an, und '.$lover.'`@ begleitet dich mit sanften Klängen...`n`n`n`%');
		
		$rand = e_rand(0,1);
		// Zufälliger Song.
		switch ($rand)
		{
		case 0:

			output('Oceans apart day after day`n
			And I slowly go insane`n
			I hear your voice on the line`n
			But it doesn\'t stop the pain`n
			`n
			If I see you next to never`n
			How can we say forever`n
			`n
			Wherever you go`n
			Whatever you do`n
			I will be right here waiting for you`n
			Whatever it takes`n
			Or how my heart breaks`n
			I will be right here waiting for you`n
			`n
			I took for granted, all the times`n
			That I though would last somehow`n
			I hear the laughter, I taste the tears`n
			But I can\'t get near you now`n
			`n
			Oh, can\'t you see it baby`n
			You\'ve got me goin\' crazy`n
			`n
			Wherever you go`n
			Whatever you do`n
			I will be right here waiting for you`n
			Whatever it takes`n
			Or how my heart breaks`n
			I will be right here waiting for you`n
			`n
			I wonder how we can survive`n
			This romance`n
			But in the end if I\'m with you`n
			I\'ll take the chance`n
			`n
			Oh, can\'t you see it baby`n
			You\'ve got me goin\' crazy`n
			`n
			Wherever you go`n
			Whatever you do`n
			I will be right here waiting for you`n
			Whatever it takes`n
			Or how my heart breaks`n
			I will be right here waiting for you`n
			`n');
			break;
		case 1:
			output('Take me now baby here as I am`n
			Pull me close try an understand`n
			I work all day out in the hot sun`n
			Stay with me now till the mornin\' comes`n
			Come on now try and understand`n
			The way I feel when I\'m in your hands`n
			Take me now as the sun descends`n
			They can\'t hurt you now`n
			They can\'t hurt you now`n
			They can\'t hurt you now`n
			`n
			Because the night belongs to lovers`n
			Because the night belongs to us`n
			Because the night belongs to lovers`n
			Because the night belongs to us`n
			`n
			What I got I have earned`n
			What I\'m not I have learned`n
			Desire and hunger is the fire I breathe`n
			Just stay in my bed till the morning comes`n
			Come on now try and understand`n
			The way I feel when I\'m in your hands`n
			Take me now as the sun descends`n
			They can\'t hurt you now`n
			They can\'t hurt you now`n
			They can\'t hurt you now`n
			`n
			Because the night...`n
			`n
			Your love is here and now`n
			The vicious circle turns and burns without`n
			Though I cannot live forgive me now`n
			The time has come to take this moment and`n
			They can\'t hurt you now`n
			`n
			Because the night...`n
			`n');
			break;
		}
		output('`@Leise lässt du deinen Gesang zu den letzten Akkorden ausklingen, und ihr sitzt noch minutenlang da und schaut euch verträumt in die Augen...`n
`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		lakenav();
		break;
	}
	// Im See Schwimmen ..........................................................
case 'swim':
	{
		$session['user']['specialmisc'].='_swim';
		output('`@Noch etwas schüchtern entkleidest du dich, genießt aber doch, wie `^'.$lover.'`@ deinen nackten Körper bewundert und sich ebenfalls zu entkleiden beginnt.
		Nackt wie von Gott geschaffen schwimmt ihr gemeinsam an das andere Ufer und zurück, wo ihr im seichteren Wasser herumzutollt und euch gegenseitig unter Wasser zu drücken versucht.');
		$rand = e_rand(1,50);
		// Zufällige Ereignisse während des Schwimmens.
		switch ($rand)
		{
		case 1:
			output('`n`n`^Während du untertauchst, siehst du etwas am Boden des Sees schimmern. Bei näherem Hinsehen erkennst du einen Ring. Von einem unguten Gefühl erfasst beschließt du, den Ring ganz schnell wieder zu vergessen, und widmest dich schnell wieder ganz und gar '.$lover.'.');
			$exp = e_rand(20,200);
			output('`n`n`^Du erhältst '.$exp.' Erfahrungspunkte für diese weise Entscheidung.');
			$session['user']['experience']+=$exp;
			break;
		case 2:
			output('`n`n`^Während du untertauchst, siehst du etwas am Boden des Sees schimmern. Bei näherem Hinsehen erkennst du einen Ring. Du willst ihn bergen, doch bevor du wirklich herankommst, merkst du auf einmal, daß dein Bein in einigen Schlingpflanzen am Grund verheddert ist. Du versuchst dich loszureissen, doch es will dir nicht gelingen. Nach einigen Minuten geht dir der Atem aus und deine Sinne schwinden...');
			$exp = e_rand(20,200);
			if ($exp>$session['user']['experience'])
			{
				output('`n`n`^Während du langsam wieder zu Bewußtsein kommst, merkst du, daß du dich nicht mehr daran erinnern kannst, was du in den letzten Tagen getan hast.');
				$session['user']['experience']=0;
			}
			else
			{
				output('`n`n`^Während du langsam wieder zu Bewußtsein kommst, wird dir bewußt, was für ein Trottel du doch bist - durch diese dumme Aktion hast du '.$exp.' Erfahrungspunkte verloren!');
				$session['user']['experience']-=$exp;
			}
			break;
		case 3:
			output('`n`n`^Während du untertauchst, siehst du etwas am Boden des Sees schimmern. Du tauchst hinab und greifst danach. Als du wieder zurück an die Oberfläche kommst, siehst du, dass du zwei Ringe gefunden hast, von denen du einen '.$lover.' gibst und den anderen selber ansteckst.');
			$mhp = e_rand(1,round($session['user']['level']/3));
			output('`n`n`^Der Ring verleiht Dir '.$mhp.' zusätzliche Lebenspunkte.');
			$session['user']['maxhitpoints']+=$mhp;
			//$db_query("INSERT INTO items (name,owner,class,value1,gold,gems,description,hvalue) VALUES ('Ring des Lebens',".$session['user']['acctid'].",'Schmuck',$mhp,100,$mhp,'Dieser schöne Ring aus dem See im Wald gibt dir $mhp Lebenspunkte')");
			//$db_query("INSERT INTO items (name,owner,class,value1,gold,gems,description,hvalue) VALUES ('Ring des Lebens',$loverID,'Schmuck',0,100,0,'Diesen schönen Ring hat dir ".$session['user'][login]."`0 am See im Wald geschenkt.')");
			break;
		case 4:
			output('`n`n`^Während du untertauchst, siehst du etwas am Boden des Sees schimmern. Du tauchst hinab und greifst danach. Dabei rutscht der Ring auf deinen Finger, und du fühlst dich plötzlich schwächer. Zurück an der Oberfläche merkst du zudem, daß du den Ring nicht wieder abbekommst!');
			$mhp = e_rand(1,round($session['user']['level']/3));
			if ($session['user']['maxhitpoints']<10)
			{
				$mhp = 0;
			}
			if (($mhp>0) && ((($session['user']['maxhitpoints']-$mhp)<10)))
			{
				$mhp = $session['user']['maxhitpoints']-10;
			}
			output('`n`n`^Der Ring raubt Dir '.$mhp.' Lebenspunkte!');
			//$db_query('INSERT INTO items (name,owner,class,value1,gold,gems,description,hvalue) VALUES ('Der Ring','.$session['user']['acctid'].','Fluch',$mhp,500,$mhp,'Dieser Ring saugt dir $mhp Lebenspunkte ab.')');
			$session['user']['maxhitpoints']-=$mhp;
			break;
		}
		if ($rand<10)
		{
			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
			{
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
				output('`n`n`^Das herrlich klare und saubere Wasser hat deine Lebenspunkte vollständig aufgefüllt.');
			}
		}
		output('`n`n`n`@Nach einer guten Stunde gibt `^'.$lover.'`@ erschöpft auf, nimmt dich in die Arme und spricht zärtlich:`n
		`3"`#Hat das einen Spaß gemacht. '.$loved.'`#, Du bist wundervoll und ich liebe Dich.`3"`n`n
		`@Gemeinsam steigt ihr aus dem Wasser, trocknet euch ein wenig länger als notwendig gegenseitig ab, und überlegt, was ihr wohl als nächstes tun werdet.`n
`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		lakenav();
		break;
	}
	// Gemütlich picknicken ......................................................
case 'picknick':
	{
		$session['user']['specialmisc'].='_picknick';
		output('`@Gemeinsam setzt ihr euch auf die Decke. `^'.$lover.'`@ steckt dir eine der Rosen ins Haar, küsst dich zärtlich, und holt eine Weinflasche und zwei Gläser aus dem Picknickkorb.`n
		Nachdem `^'.$lover.'`@ dir ein Glas eingeschenkt hat, stoßt ihr auf eure gemeinsame Zukunft an, und beginnt über Gott und die Welt und vor allem über euch zu reden.`n`n
		Es dauert nicht lange, dann ist die erste Weinflasche leer, und bald darauf eine zweite. Um dem Alkohol eine Grundlage zu geben beschließt ihr, etwas zu essen, und füttert euch gegenseitig mit Konfekt und kandiertem Obst aus dem scheinbar bodenlosen Korb.`n`n
		Als es irgendwann dunkel wird, seid ihr beide sehr satt und leicht beschwippst, und kuschelt euch gemeinsam unter eine Decke. Auf dem Rücken liegend beobachtet ihr, wie die Sterne langsam herauskommen. ');
		// Rausch hinzufügen?
		$session['user']['drunkenness']+=3;
		//if (($lover=='Durandil') && ($loved=='Aurelia')) output('Bald merkst Du, dass es der Abendstern Durandil besonders angetan hat. ');
		output('`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		lakenav();
		break;
	}
	// Auf Baumstamm setzen und plaudern .........................................
case 'flirt':
	{
		$session['user']['specialmisc'].='_flirt';
		output('`@Zusammen mit `^'.$lover.'`@ setzt du dich auf den Baumstamm, um ein wenig zu plaudern. Instinktiv wisst ihr, dass euch hier niemand belauschen kann, und ihr euren Gefühlen freien Lauf lassen könnt.`n`n');
		$rand = e_rand(1,50);
		// schenk ihr etwas, z.B. einen Edelstein! // Wert wegen Chat-Autoaktualisierung von 100 auf 50 reduziert
		
		if ($session['forestlake_event'] || !$bool_comment_written)
		{
			$rand = 100;
		}
		
		switch ($rand)
		{
		case 1:
			output('`&Während des Gespräches steckt '.$lover.'`& dir eine weitere Rose ins Haar und flüstert:`n`3"`#Du bist wunderschön...`3"`n`n');
			break;
            /** @noinspection PhpUnreachableStatementInspection */
            $session['user']['charm']++;
			$session['forestlake_event'] = true;
			break;
		case 2:
			output('`&Während des Gespräches bemerkst du immer wieder, wie toll '.$lover.'`& doch aussiehst, und schämst dich, daß du dich heute morgen nicht sorgfältiger frisch gemacht hast.`n`n');
			break;
            /** @noinspection PhpUnreachableStatementInspection */
            $session['user']['charm']--;
			$session['forestlake_event'] = true;
			break;
		case 3:
			$gems = e_rand(1, $session['user']['level']/7+1);
			output('`&'.$lover.'`& schenkt dir während des Gespräches '.$gems.' Edelsteine:`n`3"`#Sie sind zwar nicht so schön wie Du es bist, aber ich hoffe sie gefallen Dir trotzdem...`3"`n`n');
			$session['user']['gems'] += $gems;
			$session['forestlake_event'] = true;
			break;
		case 4:
			$gems = e_rand(1, $session['user']['level']/7+1);
			if ($gems>$session['user']['gems'])
			{
				$session['user']['gems'] = 0;
				output('`&Du bist so in das Gespräch vertieft, daß du gar nicht merkst, wie dir alle deine Edelsteine aus der Tasche purzeln und im tiefen Gras verschwinden!`n`n');
				break;
			}
			else
			{
				$session['user']['gems'] -= $gems;
				output('`&Du bist so in das Gespräch vertieft, daß du gar nicht merkst, wie dir '.$gems.' Edelsteine aus der Tasche purzeln und im tiefen Gras verschwinden!`n`n');
				break;
			}
            /** @noinspection PhpUnreachableStatementInspection */
            $session['forestlake_event'] = true;
			break;
		}
		viewcommentary($chat,'Flüstern',10,'flüstert zärtlich',false,true,false,true,false,true,2);
		output('`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		lakenav();
		break;
	}

case 'return':
	{
		output('`@Nachdem ihr euch eine verdiente Auszeit genommen habt, wird es Zeit, sich wieder den Gefahren des Alltags zu stellen.
Ihr verabschiedet euch sehr herzlich und versprecht, euch so bald wie möglich wieder hier zu treffen.
`n`n`n`n`n`n`n`n`n`n`$@`@}-,-´-');
		//output('`n`n`n`n`n`n`n`n`n`7Mein Dank geht an Kaiserin siwi dafür, dass sie diese Waldbegegnung eingebunden hat, and natürlich und vor allem an Aurelia, die mich dazu inspiriert hat.');
		$session['user']['specialmisc']='';
		addnav('Wege');
		addnav('G?Zum Garten','gardens.php');
		addnav('Zum Stadtzentrum','village.php');
		break;
	}

default:
	output('Das wäre dein Preis gewesen: eine romantische Stunde am Waldsee, zusammen mit deinem Partner. Aber die Götter meinen es heute nicht gut mit dir und schicken dich in die Stadt zurück.');
	$session['user']['specialmisc']='';
	addnav('Zurück zum Stadtzentrum','village.php');
}
page_footer();
?>

