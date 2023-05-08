<?php
// idea of gargamel @ www.rabenthal.de
/* changes by Salator:
* Bankraub bei Sieg für Spieler mit mehr als 10DK
* Hilfe rufen (für Neulinge) wieder aktiviert,
* Erfolg bei (DK+1)*2 Spielern gleicher Rasse die sich in den letzten (900) Sekunden in die Felder ausgeloggt haben
*
* Asgarath 06/11/08
* Event auf Stadtwache angepasst
*/
if (!isset($session))
{
	exit();
}

$session['user']['specialinc']='moocher.php';
/** @noinspection PhpUndefinedVariableInspection */
$mcat = $playermount['mountcategory'];

switch ($_GET['op'])
{
	case '':
	output('Du folgst dem Waldweg und bist besonders wachsam, weil du in einem düsteren Abschnitt des Waldes gelandet bist. Dann passiert es:
	`nHinter einer Wegbiegung wirst du plötzlich von Dieben umringt!
	`n`n`%Die Typen sehen wirklich furchterregend aus und fordern mit gezogenen Waffen dein Gold.`0
	`nBleibt dir eine andere Wahl?`0');
	//abschluss intro
	addnav('Gold herausgeben','forest.php?op=give');
	addnav('Kämpfen','forest.php?op=fight');
	// Prüfe, ob User ein Tier und dieses noch Runden hat und lasse nur geeignete Tiere zu
	if($session['user']['hashorse'] && $session['bufflist']['mount']['rounds']>0 && $mcat != 'Haustiere' && $mcat != 'Mod-Biester' && $mcat !='Nutztiere')
	{
			addnav('Nutze dein Tier','forest.php?op=mount');
	}
	if ($session['user']['dragonkills']<6)
	{
		addnav('Hilfe rufen','forest.php?op=help');
	}
	break;

	case 'give':
	// Gold geben
	$gold = $session['user']['gold'];
	if ($session['user']['gold'] > 0 )
	{
		output("Angesichts der Übermacht der Diebe entschließt du dich, dein Gold herauszugeben.`nDich schmerzt der `QVerlust von $gold Gold`0, aber noch schlimmer wäre der Verlust deines Lebens gewesen.`0");
		$session['user']['gold']=0;
	}
	else
	{
		// aber nix dabei
		output("Du erklärst dem Anführer, dass du zahlen willst.
		`nAls du ihm jedoch deine leere Geldbörse hinhältst, findet er das gar nicht komisch.
		`n`QEr gibt seiner wilden Truppe ein Zeichen.... `0Die ganze Meute prügelt nun auf dich ein und sie lassen erst von dir ab, als du schon tot scheinst.
		`n`n`9Du bist aber gerade noch mit dem Leben davon gekommen und verlierst einen permanenten Lebenspunkt.`0");
		$session['user']['maxhitpoints']-=1;
		$session['user']['hitpoints']=1;
	}
	$session['user']['specialinc'] = "";
	break;

	case "fight": // kämpfen
	output("`%Du entschließt dich zu kämpfen und ziehst blitzschnell deine Waffe.`n`n`0");
	$hp = round($session['user']['hitpoints'] * 2.5);
	$dam = e_rand(1,$hp);
			
	if ($session['user']['hitpoints'] > $dam )
	{
		//sieg
		output("In einem unübersichtlichen Getümmel wirst du hart getroffen, aber du führst deine Waffe auch erfolgreich. Nach einer ganzen Weile steht fest:
		`n`n`@Du hast gewonnen!
		`n`nSchwer gezeichnet feierst du deinen letzten Sieg des heutigen Tages. Wenigstens hast du einiges an Erfahrung gewonnen.`0");
		$session['user']['hitpoints']-= $dam;
		$session['user']['turns']=0;
		$session['user']['experience']+= round($session['user']['experience']*0.08);
		if ($session['user']['dragonkills'] >10)// && e_rand(1,3) == 1)
		{
			output('`n`n`%Beim Durchsuchen des Bandenführers findest du einen Zettel. Es scheint ein Plan für einen Raub zu sein. Du bemerktst die Notiz `&'.getsetting('townname','Atrahor').' Bank.`n`n`@Du könntest die überlebenden Räuber zwingen, etwas dazu zu sagen.');
			addnav('R?zum Reden zwingen','forest.php?op=ask');
			addnav('Zurück in den Wald','forest.php?op=leave');
		}
		else
		{
			$session['user']['specialinc'] = "";
		}
	}
	else
	{
		//niederlage
		output("Die Entscheidung, gegen die Übermacht der Diebe zu kämpfen, war sicher nicht deine beste! `QDu hast einfach keine Chance!
		`n`n`6Nach einem kurzen, heftigen Kampf verabschiedest du dich vom Leben.
		`n`nFür deinen Mut wird dich jedoch Ramius belohnen.`0");
		killplayer(100,0,0,'news.php','Tägliche News');
		$session['user']['gravefights']+=2;
		addnews("`^".$session['user']['name']."`# hatte keine Chance im Kampf gegen die Diebesbande. Auf Wiedersehen!");
	}
	break;

	case "ask": // ask for bankrobbery
	$moochers = e_rand(1,4);
	//Anzahl Überlebende der Räuberbande; Erfolgs-Chance
	output('`@Als du dir das Gemetzel betrachtest stellst du fest dass '.$moochers.' Räuber überlebt haben, welche du zu dem geplanten Raub befragen kannst. 
			Also hältst du einem die Klinge an den Hals.`n
			Schon bald beginnt der Räuber um sein Leben zu wimmern und gibt dir die Informationen, die du haben willst...`nDu kannst nun ');
	output('`n- '.create_lnk('einen gemeinsamen Raub planen und die Beute teilen','forest.php?op=rob&anz='.$moochers), true);
	output('`n- '.create_lnk('die Sache alleine durchziehen','forest.php?op=rob'), true);
		
	if ($session['user']['reputation']>10
    || $session['user']['profession'] == PROF_GUARD 
    || $session['user']['profession'] == PROF_GUARD_HEAD)
    // Abfrage ob User zur Stadtwache gehört sicherheitshalber eingebaut
	{
		output('`n`@- '.create_lnk('die Bande in den Kerker bringen','forest.php?op=inform'), true);
	}
	else
	{
		output('`n`@- '.create_lnk('schnell von hier verschwinden','forest.php?op=leave'), true);
	}
	break;

	case "rob": // bankrobbery
	$session['user']['specialinc']='';
	if ($session['user']['profession'] == PROF_TEMPLE_SERVANT 
		|| $session['user']['profession'] == PROF_JUDGE 
		|| $session['user']['profession'] == PROF_JUDGE_HEAD
		|| $session['user']['profession'] == PROF_GUARD
		|| $session['user']['profession'] == PROF_GUARD_HEAD)
	{
        /** @noinspection PhpUndefinedVariableInspection */
        $str_prof = $profs[$session['user']['profession']][$session['user']['sex']];
		output('`6Gerade noch rechtzeitig fällt dir ein, dass du ja '.$str_prof.' bist. Also vergisst du die Sache schnell wieder.');
	}
	elseif ($session['user']['login'] == strip_appoencode(getsetting('fuerst',''),3)) {
		output('`6Gerade noch rechtzeitig fällt dir ein, dass du ja den Fürstentitel trägst und eine solche Aktion deinem Amt... eher abträglich wäre. Also vergisst du die Sache schnell wieder.');
	}
	else
	{
		$moochers = $_GET['anz'];
		output("`6Vermummt ".($moochers?"und mit ".$moochers." Räubern im Gefolge ":"")."stürmst du in die Bank!`n");
		output("`6Die Gelegenheit scheint günstig. Du schreist `$`n`c`bDas ist ein Banküberfall!`b`c`n`n");
		$moochers++;
		//Spieler zählt mit dazu
		switch (e_rand($moochers,6))
		{
		case 1 :
			//Alleingang kann übel werden
			output("`6Tja, leider bist du heute nicht der Erste, der auf diese Idee gekommen ist. Du rennst der Stadtwache direkt in die Arme. An Ort und Stelle wirt dir in einem Schnellverfahren der Prozeß gemacht. Noch ewig wirst du den Richterspruch in deinen Gedanken hören:
			`n`qIm Namen der Gesetze verdonnern wir die Krätze wegen wiederholten `QEierdiebstahls`q zu 500 Jahren schweren Kerkers mit einem Fasttag täglich!
			`n`n<a href='prison.php'>Küss die Hand Herr Kerkermeister, ich bin wieder da.</a>");
			addnav('','prison.php');
			addnews("`b`@".$session['user']['name']."`b`& lief in der Bank der Stadtwache direkt in die Arme.");
			systemmail($session['user']['acctid'],'`$Du wurdest verurteilt!`0','Das in der Bank an Ort und Stelle einberufene Gericht hat dich zu 1 Tag Kerker verurteilt.
			`nDiese Strafe wird zu eventuell anderen Strafen hinzugerechnet, jedoch kann deine Haft dadurch nicht länger als '.getsetting('maxsentence',5).' Tage werden.');
			if ($session['user']['imprisoned'] < getsetting('maxsentence',5))
			{
				$session['user']['imprisoned']++;
			}
			addnav("In den Kerker!","prison.php");
			
			break;
			
		case 2 :
			output("`6Eine alte Frau dreht sich um und sagt `QJunger Mann, stelln Sie sich gefälligst hinten an!`6
			`nDu hast Respekt vor dem Krückstock der alten Dame und stellst dich wie geheißen ans Ende der Schlange.
			`n`@Seit einer halben Stund´ stehst du ewig in der Reih´, du `4verlierst einen Waldkampf`@ durch die blöde Warterei.");
			//is ja schon 0 $session['user']['turns']--;
			break;
			
		case 3 :
			output("`6Der Kassierer schaut dich an und meint `3Das kann ja jeder sagen.`6
			`nDu sagst `4Das kann eben nicht jeder sagen.`6
			`nDer Kassierer sagt `3Das ist ein Banküberfall. Seht Ihr, das kann jeder sagen.`6
			`nEinige Anwesende sagen nun ebenfalls `qDas ist ein Banküberfall.`6
			`n`nDie Sache wird dir peinlich und du verläßt die Bank.");
			if (e_rand(1,2)==1 && $session['user']['reputation']>0)
			{
				output("`n`&Jemand hat dich trotz der Maskierung erkannt, dein Ansehen sinkt.");
				$session['user']['reputation']--;
			}
			break;
			
		case 4 :
			output("`6Der Kassierer sagt `@Nein! Was fällt Euch ein?
			`n`qNa gut,`6 sagst du,`q dann zahl´ ich halt was ein.`n`n");
			addnav('Etwas Einzahlen','bank.php?op=deposit');
			break;
			
		case 5 :
			output("`6Du stürzt auf den Schalter zu, hältst dem Kassier dein ".$session['user']['weapon']."`6 an den Hals und brüllst ihn an `\$Geld oder Leber!`&
			`n`6 Der Kassierer überreicht dir eine Schweineleber, frisch aus der Dark Horse Taverne, mit den Worten `@Ihr habt.. Leber gesagt?
			`n`6Wenigstens hast du jetzt was zu essen.");
			if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
			{
				$session['user']['hitpoints']++;
			}
			break;
			
		case 6 :
			$row = db_fetch_assoc(db_query("SELECT name,sex FROM accounts WHERE loggedin=0 ORDER BY rand(".e_rand().") LIMIT 1"));
			output("`6Du greifst dir `^".$row['name']."`6 als Geisel und hältst ".($row['sex']?"ihr":"ihm")." dein ".$session['user']['weapon']."`6 an den Hals, während du den Kassierer anbrüllst `\$Geld oder Leben!`&`n");
			$foundgold = e_rand(200,600) * $session['user']['level'];
			output("`n`6Du erbeutest `Q".$foundgold." Gold");
			if ($moochers >1)
			{
				$foundgold=round($foundgold/$moochers);
				output("`6, wovon nach Aufteilung der Beute noch `Q".$foundgold." Gold`6 für dich bleiben,");
			}
			$session['user']['gold'] += $foundgold;
			
			switch (e_rand(1,3))
			{
			case 1 :
				//Raub mit späterer Verurteilung
			case 2 :
				output("`n`6Wenig später hängt dein Steckbrief überall in der Stadt. Es wird nicht lange dauern bis man dich fasst. Dein Ansehen leidet unter dieser Aktion.");
				$session['user']['reputation']-=2;
				addnews("`b`@".$session['user']['name']."`b`& erbeutete`^ ".$foundgold."`& Gold bei einem Banküberfall.");
				addcrimes("`b`@".$session['user']['name']."`b`& erbeutete`^ ".$foundgold."`& Gold bei einem Banküberfall.");
				break;
				
			case 3 :
				//Raub ohne Folgen
				output("`6 und entkommst unerkannt.");
				$meldung = '`$Gerade wurde die Bank von '.getsetting('townname','Atrahor').' ausgeraubt! Zeugen gesucht!';
				$sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('".db_real_escape_string($meldung)."',NOW(),0)";
				db_query($sql);
				break;
			default:
				output('`n`&Justitia meint es gut mit dir, im Zweifel für den Angeklagten.');
			}
			
			break;
			
		default : //Fehlerfall
			output('`&Irgendetwas stimmt hier nicht. Du zählst nocheinmal alle Räuber durch, mit dir sind es '.$moochers.'. Da hat sich doch ein Spion dazugeschummelt? Es ist besser, du lässt die Bank sein.');
		}
	}
	break;

	case "inform": // petzen
	 
   if ($session['user']['profession'] == PROF_GUARD || $session['user']['profession'] == PROF_GUARD_HEAD)
	 {
	   output('`@Als Stadtwache ist es für dich natürlich selbstverständlich dieses Diebespack in den Kerker zu bringen. Du erhälst allerdings keine Belohnung, denn du wirst ja dafür bezahlt. Als kleiner Trost steigt jedoch dein Ansehen bei den Bürgern!');
	   $session['user']['reputation']=50;
	   addnews('`#Stadtwache `^'.$session['user']['name'].'`# hat eine Diebesbande gefasst und sich maximales Ansehen verdient!');
   }
   else
   {
      output('`@Als ehrenhafter Bürger übergibst du die Schurken der Stadtwache. Du erhältst `^1000 Gold und 3 Edelsteine`@ als Belohnung und hast dir `6maximales Ansehen`@ verdient!');
	   $session['user']['gold']+=1000;
	   $session['user']['gems']+=3;
	   $session['user']['reputation']=50;
	   addnews('`^'.$session['user']['name'].'`# hat eine Diebesbande gefasst und sich maximales Ansehen verdient!');
	 }
   $session['user']['specialinc']='';
	 break;

case "leave": // win fight and exit
	output('`@Die Sache kommt dir merkwürdig vor und du beschließt, schnell zu verschwinden.');
	$session['user']['specialinc']="";
	break;

	case"help": // hilfe
	//    $needed = 6;  // im wald benötigte helfer
	$needed = ($session['user']['dragonkills']+1)*2;
	output("Du rufst um Hilfe. Ganz laut.`n
	Die Diebensbande ist erstaunt, greift dich aber nicht an. Offensichtlich wollen sie sich einwenig an deiner Angst weiden. Und sie sind sicher, dass dir eh niemand hilft.`n`n`0");
	
	//    $sql = "SELECT name,level,title,location FROM accounts WHERE locked=0 AND location=5 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY level DESC";
	$sql = "SELECT name,level,alive,race,location FROM accounts WHERE locked=0 AND location=0 AND alive=1 AND race='".$session['user']['race']."' AND loggedin=0 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY level DESC";
	$result = db_query($sql) or die(sql_error($sql));
	$max = db_num_rows($result);
	$anz = db_num_rows($result);
	
	if ($anz >= $needed )
	{
		// genug helfer da
		output("`6Aber die Diebe haben nicht mit deinen Freunden aus der Stadt gerechnet!
		Die folgenden Bewohner von ".getsetting('townname','Atrahor')." sind nämlich gerade in der Nähe und eilen dir zu Hilfe:`n`0");
		for ($i=0; $i<$needed; $i++)
		{
			//$max zu $needed geändert
			$row = db_fetch_assoc($result);
			if ($row['name'] != $session['user']['name'] )
			{
				output("".$row['tile']." ".$row['name']."n`0");
			}
		}
		output("`n`2Gemeinsam besiegt ihr die Diebesbande. `0Du bedankst dich bei allen Helfern und versprichst, in der Taverne eine Runde zu schmeissen.`n
		Du verlierst zwar einen Waldkampf, ziehst aber trotzdem glücklich weiter.`0");
		$session['user']['turns']-= 1;
	}
	else if ($anz == 0 )
	{
		output("`3Leider behalten die Diebe recht, denn im Moment sind keine anderen Bewohner in der Nähe. Sie schauen noch einen Moment zu, wie du verzweifelt auf Hilfe wartest und greifen dich dann an.`n`n
		`QNach einem kurzen, heftigen Kampf verabschiedest du dich vom Leben");
		$session['user']['alive']=false;
		$session['user']['gold']=0;
		if($session['user']['gems'] > 50) {
			$int_losegems = min(round($session['user']['gems'] * 0.3),100);
			if($int_losegems > 0) {
				output(', sowie '.$int_losegems.' deiner Edelsteine. Schade drum..');
				$session['user']['gems'] -= $int_losegems;
				debuglog('Verlor '.$int_losegems.' Gems bei den Dieben im Wald.');
			}
		}
		
		$session['user']['hitpoints']=0;
		addnews("`^".$session['user']['name']."`# hatte keine Chance im Kampf gegen die Diebesbande. Auf Wiedersehen!");
						
		addnav("Tägliche News","news.php");
	}
	else
	{
		// nicht genug bewohner
		if ($anz == 1 )
		{
			output("`#Zwar werden Deine Hilferufe gehört. Aber der einzige Bewohner, der zur Zeit auch noch in der Nähe ist, erreicht dich nicht rechtzeitig.`n`0");
		}
		else
		{
			output("`#Deine Hilferufe werden zwar von $anz Bewohnern gehört, die auch gerade in der Nähe sind, aber unglücklicherweise können die den Ort des Überfalls nicht rechtzeitig erreichen. Du bleibst auf dich allein gestellt.`n`0");
		}
		output("Damit behalten die Diebe leider recht. Sie schauen noch einen Moment zu, wie du verzweifelt auf Hilfe wartest und greifen dich dann an.`n`n
		`QNach einem kurzen, heftigen Kampf verabschiedest du dich vom Leben");
		$session['user']['alive']=false;
		$session['user']['gold']=0;
		if($session['user']['gems'] > 50) {
			$int_losegems = min(round($session['user']['gems'] * 0.3),100);
			if($int_losegems > 0) {
				output(', sowie '.$int_losegems.' deiner Edelsteine. Schade drum..');
				$session['user']['gems'] -= $int_losegems;
				debuglog('Verlor '.$int_losegems.' Gems bei den Dieben im Wald.');
			}
		}
		$session['user']['hitpoints']=0;
		addnews("`^".$session['user']['name']."`# hatte keine Chance im Kampf gegen die Diebesbande. Auf Wiedersehen!");
		addnav("Tägliche News","news.php");
	}
	$session['user']['specialinc']="";
	break;
	
	case 'mount':
	  $mname=$playermount['mountname'];
	  $mid=$playermount['mountid'];
	  output('`0Du entscheidest dich, erst einmal dein Tier in den Kampf zu schicken, bevor du selbst in den Kampf ziehst. Sofort attackiert dein/e '.$mname.'`0 die Gegner');
		switch($mcat)
		{
		case 'Drachlinge':
		  // Drachlinge haben sehr hohe Chance, aber Wyvern bekommt andere Angriffsart
            if($mid==14)
            {
                output(' mit seinem Giftstachel');
                $rand = e_rand(2,6);
            }
            else  if($mid==6)
            {
                output(' mit seinem Giftstachel');
                $rand = e_rand(1,6);
            }
			else
			{
                output(' mit seinen langen messerscharfen Zähnen');
		  	    $rand = e_rand(1,6);
			}
			break;
		case 'Reittiere':
		  output(' und tritt mit den schweren Hufen nach ihnen');
			// Nur Cerapter, Einhorn und Nachtmahr bekommen hohe Gewinnchancen
			if($mid==8 || $mid==20 || $mid==30)
		  {
				$rand = e_rand(2,6);
			}
			else
			{
				$rand = e_rand(3,6);
			}
			break;
		case 'Geister und Dämonen':
			// Geister und Dämonen bekommen mittlere Gewinnchancen
			output(' mit schnellen und gefährlichen Hieben');
			$rand = e_rand(3,6);
			break;
		case 'Magische Wesen':
		  // Mittlere Gewinnchance, kein Text für Angriffsart
			$rand = e_rand(3,6);
			break;
		case 'Wildtiere':
		  // Niedrigere Gewinnchance, Alternativtext für die Flugtiere
		  if($mid==15 || $mid==25 || $mid==56)
		  {
				output(' und hackt mit den Schnabel auf sie ein');
				$rand = e_rand(5,6);
			}
			else
			{
			  output(' und beisst kräftig zu');
				$rand = e_rand(4,6);
	 		}
			break;
		case 'Besondere':
		  // 100% Gewinnchance
		  output(' mit sehr schlauen Tricks und Fallen');
			$rand = 5;
		  break;
		}
		switch($rand)
		{
			case 1: // Totalschaden, nur für Drachlinge möglich
			  $session['user']['specialinc']='';
				output(', der einen so gewaltigen Schaden anrichtet, dass kaum Überreste bleiben und du sogar verletzt wirst!`n`n
								`$Du solltest dringend einen Heiler aufsuchen!');
				$session['user']['hitpoints']=1;
				addnav('Zurück in den Wald','forest.php');
				break;
			case 2:
			case 3:
			case 4:
			case 5: // Außer Gefecht
			  output(', so dass diese stark geschwächt sind und für dich keine Bedrohung mehr darstellen. Nach einem schnellen Kampf ist alles vorbei und du gehst natürlich als Sieger hervor.`n`n
								Leider hat dich der Kampf deine letzten Kräfte gekostet, doch du erhälst Erfahrungspunkte!');
				$session['user']['turns']=0;
				$session['user']['experience']+= round($session['user']['experience']*0.08);
				if ($session['user']['dragonkills'] >10 && e_rand(1,3) == 1)
				{
					output('`n`n`%Beim Durchsuchen des Bandenführers findest du einen Zettel. Es scheint ein Plan für einen Raub zu sein. Du bemerktst die Notiz `&'.getsetting('townname','Atrahor').' Bank.`n`n`@Du könntest die überlebenden Räuber zwingen, etwas dazu zu sagen.');
					addnav('R?zum Reden zwingen','forest.php?op=ask');
				}
				addnav('Zurück in den Wald','forest.php?op=leave');
				break;
			case 6: // Fehlschlag
			  output('. Doch leider geht der Angriff ins Leere und dein Tier zieht sich erschöpft zurück. Jetzt hast du keine andere Wahl, du musst kämpfen!');
			  $session['bufflist']['mount']['rounds']=0;
			  addnav('Kämpfe!','forest.php?op=fight');
				break;
		}
		break;
	
	default: //Fehlerfall
	output('`&Ein gewaltiger Blitz trifft dich. Du verlierst die Besinnung und träumst davon, Waldmonster zu erschlagen.');
	$session['user']['turns']++;
	$session['user']['specialinc']="";
	break;
}
?>
