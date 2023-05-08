<?php

require_once("common.php");
page_header('Das '.getsetting('townname','Atrahor').' Trivia');

$op=$_GET['op'];
$number_of_rounds=8;

$str_backlink = 'market.php';
$str_backtext = 'Zum Markt';
$str_file = basename(__FILE__);

switch($op)
{

	case 'play':
		// Übertragene Daten holen
		if ($_GET['round'])
		{
			$round=$_GET['round'];
		}
		else
		{
			$round=1;
			$sql = "SELECT id FROM trivia ORDER BY rand(".e_rand().") LIMIT $number_of_rounds";
			$result = db_query($sql);

			// Wenn noch keine Fragen hinterlegt wurden: Abbruch!
			if(!db_num_rows($result)) {
				output('Leider kennt Gunther Lauch noch keine einzige Frage - komm später wieder!');
				addnav($str_backtext,$str_backlink);
				page_footer();
			}

			for ($j=1;$j<=$number_of_rounds;$j++)
			{
				$order=db_fetch_assoc($result);
				$quiz_order[$j]=$order['id'];
			}
			$session['user']['quiz_order']=utf8_serialize($quiz_order);
			if($session['user']['acctid']==getsetting('demouser_acctid',0))
			{ //für Demo-Zugang immer die gleichen Fragen
				$session['user']['quiz_order']='a:8:{i:1;s:2:"70";i:2;s:1:"3";i:3;s:2:"23";i:4;s:2:"71";i:5;s:2:"33";i:6;s:2:"63";i:7;s:2:"95";i:8;s:2:"57";}';
			}
		}
		$given_answer=$_GET['given_answer'];
		if ($_GET['points'])
		{
			$points=$_GET['points'];
		}
		else
		// Reihenfolge der Fragen Initialisieren
		{
			$points=0;
		}

		// ID der aktuellen Frage ermitteln
		$quiz_order=utf8_unserialize($session['user']['quiz_order']);
		$q_id=$quiz_order[$round];

		// Erstmal hard gecodet
		if ($round<=$number_of_rounds)
		{
			output('`&`c`bWillkommen beim Legend of Atrahor Quiz`c`b`n`n'.$round.'. Frage:`n`n');

			// Jetzt laden wir das Quiz aus der DB
			$sql = "SELECT * FROM trivia WHERE id=".$q_id;
			$result = db_query($sql);
			$row = db_fetch_assoc($result);

			$question=$row['question'];
			$answer=utf8_unserialize($row['answer']);
			$correct=$row['correct'];

			output("`n".$question." `n`n`&");

			$i=1;
			foreach ($answer as $key => $val )
			{
				if ($given_answer)
				{
					if ($given_answer==$i)
					{
						if ($given_answer==$correct)
						{
							output("`^");
							$points++;
						}
						else
						{
							output("`4");
						}
					}
					elseif ($correct==$i)
					{
						output("`@");
					}
					output("`n".$i.") ".$val."`&`n");
				}
				else
				{
					output("`n$i) ".create_lnk("$val","trivia.php?op=play&round=$round&given_answer=$i&points=$points",true,true,'',false,"$i?Antwort $i",1)."`n");
				}
				$i++;
			}
			output("`n`n");

			if ($given_answer)
			{
				output("Deine Antwort war ");
				If ($given_answer==$correct)
				{
					output("`@richtig!`n`n`&");
				}
				else
				{
					output("`4leider falsch!`n`n`&");
				}
				$round++;
				addnav("Nächste Runde");
				addnav("Weiter",$str_file."?op=play&round=$round&points=$points");
				$solution=utf8_unserialize($row['solution']);

				/**$i=1;
				foreach ($solution as $key => $val )
				{
				output("`n".$i.": ".$val."`&");
				$i++;
				}**/
				output("Gunther Lauchs Kommentar zu deiner Antwort:`5 ".$solution[$given_answer-1]."`&`n`n");
			}

			output("`n`nDeine Punkte: $points`n`n");
			addnav('Zurück');
			addnav($str_backtext,$str_backlink, false, false, false, true,'Willst Du wirklich zurück zum Marktplatz? Dadurch verlierst Du Deine Quizpunkte die Du heute gewonnen hättest!');
		}
		else
		{
			output("`&Du hast das Quiz beendet und $points Punkte gemacht!`n");
			if ($points==$number_of_rounds)
			{
				output("`^Da du alle Fragen richtig beantwortet hast bekommst du 2 Bonuspunkte!`n");
				$points+=2;
			}
			output("`&Deine Gesamtpunktzahl: `^$points`n");
			$sql = "UPDATE account_extra_info SET quizpoints=quizpoints+$points WHERE acctid = ".$session['user']['acctid'];
			db_query($sql);

			addnav('Punkte einlösen');
			addnav('Preis holen','trivia.php?op=prices');
			addnav('Zurück');
			addnav($str_backtext,$str_backlink);
		}
		break;

	case 'ready':
		if (count(utf8_unserialize($session['user']['quiz_order']))>0)
		{
			output("`4Du hast bereits gespielt!`nVollbringe erst eine Heldentat, bevor du wieder kommst!`n`n`&");
		}
		else
		{
			output("`^Da du noch nicht gespielt hast kannst du dies nun tun.`nWillst du das wirklich?`n`n`&");
			addnav("Ja, spielen!",$str_file."?op=play");
		}
		addnav($str_backtext,$str_backlink);
		break;

	case 'prices':
		$p = user_get_aei('quizpoints,quizpoints_spent');
		$points_total=$p['quizpoints'];
		$points=$points_total-$p['quizpoints_spent'];

		if ($points_total==0)
		{
			output("`&Gunther Lauch hat es nicht gerne, hereingelegt zu werden.`nMit ärgerlichem Blick zückt er wortlos seine Nagelkeule...");
		}
		else
		{
			if( $points >= 100){
				require_once(LIB_PATH.'runes.lib.php');
				$know_runes = runes_get_known();
			}

			output("`&Gunther Lauch nimmt dich beiseite und führt dich zu einem abgelegenen, gut vor fremden Blicken geschützten Zelt. Er spricht zu dir:`n`n\"`5Du hast beim Quiz `#$points_total`5 Punkte gemacht!`n`n`^Gratulation!`5`n`nDavon kannst du noch `#$points `5Punkte gegen tolle Preise eintauschen!`n`nWas darf's denn sein?`&\"");

			addnav("Ab 1 Punkt");
			if ($points>=1)
			{
				addnav("Trostbonbon (1P)",$str_file."?op=getprice&id=bon&cost=1");
			}
			else
			{
				addnav("??? (1P)");
			}
			if ($points>=2)
			{
				addnav("Drehkreisel (2P)",$str_file."?op=getprice&id=kreisel&cost=2");
			}
			else
			{
				addnav("??? (2P)");
			}
			if ($points>=3)
			{
				addnav("Trostpflaster (3P)",$str_file."?op=getprice&id=pflaster&cost=3");
			}
			else
			{
				addnav("??? (3P)");
			}
			addnav("Ab 5 Punkten");

			if ($points>=5)
			{
				addnav("1000 Gold (5P)",$str_file."?op=getprice&id=gold&cost=5&amount=1000");
			}
			else
			{
				addnav("??? (5P)");
			}
			if ($points>=6)
			{
				addnav("Ale von Cedrick (6P)",$str_file."?op=getprice&id=ale&cost=6");
			}
			else
			{
				addnav("??? (6P)");
			}
			if ($points>=7)
			{
				addnav("1 Edelstein (7P)",$str_file."?op=getprice&id=gems&cost=7&amount=1");
			}
			else
			{
				addnav("??? (7P)");
			}
			if ($points>=8)
			{
				addnav("10x 1 Waldkampf (8P)",$str_file."?op=getprice&id=wk&cost=8&amount=10");
			}
			else
			{
				addnav("??? (8P)");
			}
			addnav("Ab 10 Punkten");
			if ($points>=10)
			{
				addnav("2 Extra-Leben (10P)",$str_file."?op=getprice&id=revive&cost=10");
			}
			else
			{
				addnav("??? (10P)");
			}
			if ($points>=12)
			{
				addnav("Zaubertafel (12P)",$str_file."?op=getprice&id=tafel&cost=12");
			}
			else
			{
				addnav("??? (12P)");
			}
			if ($points>=15)
			{
				addnav("20 DP (15P)",$str_file."?op=getprice&id=dp&cost=15&amount=20");
			}
			else
			{
				addnav("??? (15P)");
			}
			if ($points>=20)
			{
				addnav("3 Charmepunkte (20P)",$str_file."?op=getprice&id=charm&cost=20&amount=3");
			}
			else
			{
				addnav("??? (20P)");
			}
			addnav("Ab 25 Punkten");

			if ($points>=25)
			{
				addnav("Gratis Tod (25P)",$str_file."?op=getprice&id=ramius&cost=25");
			}
			else
			{
				addnav("??? (25P)");
			}
			if ($points>=30)
			{
				addnav("3 perm. LP (30P)",$str_file."?op=getprice&id=lp&cost=30&amount=3");
			}
			else
			{
				addnav("??? (30P)");
			}
			if ($points>=35)
			{
				addnav("Ansehen (35P)",$str_file."?op=getprice&id=reputation&cost=35");
			}
			else
			{
				addnav("??? (35P)");
			}
			addnav("Ab 50 Punkten");
			if ($points>=50)
			{
				addnav("10 Edelsteine (50P)",$str_file."?op=getprice&id=gems&cost=50&amount=10");
			}
			else
			{
				addnav("??? (50P)");
			}

			if ($points>=65)
			{
				addnav("Amnestie (65P)",$str_file."?op=getprice&id=amnesty&cost=65&amount=10");
			}
			else
			{
				addnav("??? (65P)");
			}
			if ($points>=75)
			{
				addnav("Superwaffe (75P)",$str_file."?op=getprice&id=weapon&cost=75");
			}
			else
			{
				addnav("??? (75P)");
			}
			if ($points>=80)
			{
				addnav("Superrüstung (80P)",$str_file."?op=getprice&id=armor&cost=80");
			}
			else
			{
				addnav("??? (80P)");
			}
			if ($points>=85)
			{
				addnav("25000 Gold (85P)",$str_file."?op=getprice&id=gold&cost=85&amount=25000");
			}
			else
			{
				addnav("??? (85P)");
			}
			addnav("Ab 100 Punkten");
			if ($points>=100)
			{
				addnav("25 Edelsteine (100P)",$str_file."?op=getprice&id=gems&cost=100&amount=25");
			}
			else
			{
				addnav("??? (100P)");
			}
			if ($points>=100 && $know_runes['15'])
			{
				addnav("Algiz-Rune (100P)",$str_file."?op=getprice&id=algiz&cost=100");
			}
			elseif ($points>=100 && !$know_runes['15'])
			{
				addnav("unbekannte Rune (100P)");
			}
						else
			{
				addnav("??? (100P)");
			}
      if ($points>=200 && $know_runes['23'])
			{
				addnav("Dagaz-Rune (200P)",$str_file."?op=getprice&id=dagaz&cost=200");
			}
			elseif ($points>=200 && !$know_runes['23'])
			{
				addnav("unbekannte Rune (200P)");
			}
			else
			{
				addnav("??? (200P)");
			}
			addnav("Ab 300 Punkten");
			if ($points>=300)
			{
				addnav("100 Edelsteine (300P)",$str_file."?op=getprice&id=gems&cost=300&amount=100");
			}
			else
			{
				addnav("??? (300P)");
			}
			if ($points>=400 && $know_runes['24'])
			{
				addnav("Othala-Rune (400P)",$str_file."?op=getprice&id=othala&cost=400");
			}
			elseif ($points>=400 && !$know_runes['24'])
			{
				addnav("unbekannte Rune (400P)");
			}
			else
			{
				addnav("??? (400P)");
			}
			addnav("Ab 500 Punkten");
			if ($points>=500)
			{
				addnav("Besonderes Tier (500P)",$str_file."?op=getprice&id=fuchs&cost=500");
			}
			else
			{
				addnav("??? (500P)");
			}
			addnav("Hauptpreis");
			if ($points>=750)
			{
				addnav("Das Mal der Luft (750P)",$str_file."?op=getprice&id=luft&cost=750");
			}
			else
			{
				addnav("Das Mal der Luft (750P)");
			}

		}
		addnav("Ich überleg's mir nochmal");
		addnav($str_backtext,$str_backlink);
		break;

	case 'getprice':

		$id=$_GET['id'];
		$cost=$_GET['cost'];
		$confirm=$_GET['confirm'];
		$amount=$_GET['amount'];

		if (!$confirm)
		{
			output("`&Gunther Lauch erklärt dir diesen Preis:`n`&`n\"");
		}
		switch ($id){

			case 'bon':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="trostbon" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Bonbon für $cost QP erkauft.");
				}
				else
				{
					output("`5Nicht traurig sein - ein kleines Trostbonbon ist sogar für jene drin, die nicht im Quiz geglänzt haben!`&\"");
				}
				break;

			case 'kreisel':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="kreisel" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Kreisel für $cost QP erkauft.");
				}
				else
				{
					output("`5Ein kleines, nettes Spielzeug für jeden, der gern Dinge dreht!`&\"");
				}
				break;

			case 'pflaster':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="pflaster" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Pflaster für $cost QP erkauft.");
				}
				else
				{
					output("`5Nimm's nicht so schwer und tröste dich mit diesem Pflaster!`nUnd wenn dir was weh tut, dann kleb es einfach drauf und lass es dir besser gehen!`&\"");
				}
				break;

			case 'wk':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$config = utf8_unserialize($session['user']['donationconfig']);
					$config['forestfights'][] = array('left'=>$amount,'bought'=>date('M d'));
					//array_push($config['forestfights'],array('left'=>$amount,'bought'=>date('M d')));
					$session['user']['donationconfig'] = utf8_serialize($config);
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("$amount WKs für $cost QP erkauft.");
				}
				else
				{
					output("`5Müde und ausgelaugt? Das kann keine Ausrede sein. Denn dieser Waldkampf, den du von nun an die nächsten $amount Tage hast, wird dich stets vor Kraft nur so strotzen lassen!`nWaldkämpfe - einen besseren Ausdruck für Elan und Ausdauer gibt es wohl nicht!`&\"");
				}
				break;

			case 'lp':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$session['user']['maxhitpoints']+=$amount;
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("$amount LP für $cost QP erkauft.");
				}
				else
				{
					output("`5Mache dich unempfindlicher gegen Hiebe mit ".($amount==1 ? 'diesem Lebenspunkt' : 'diesen '.$amount.' Lebenspunkten')."!`nLebenspunkte helfen in jeder Situation, in der du Schläge kassierst!`&\"");
				}
				break;

			case 'charm':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$session['user']['charm']+=$amount;
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("$amount Charme für $cost QP erkauft.");
				}
				else
				{
					output("`5Tu was für dein Äußeres mit ".($amount==1 ? 'diesem Charmepunkt' : 'diesen '.$amount.' Charmepunkten')."!`nDu kannst ein Schwächling sein, du kannst dumm sein, doch wenn du ein hübsches Gesicht hast, ist das Glück stets auf deiner Seite!`&\"");
				}
				break;

			case 'gold':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$session['user']['gold']+=$amount;
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("$amount Gold für $cost QP erkauft.");
				}
				else
				{
					output("`5Mache dich beliebter durch Reichtum! Diese ".$amount." Goldstücke tragen dazu bei, dass man in dir mehr als einen armen Schlucker sieht!`&\"");
				}
				break;

			case 'dp':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$session['user']['donation']+=$amount;
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("$amount DP für $cost QP erkauft.");
				}
				else
				{
					output("`5Zu geizig zu spenden? Zu faul, um Heldentaten zu vollbringen? Keine Lust auf RPG? Dann greif dir diese ".$amount." Donation Points ab und gönne dir ein paar Besonderheiten, wie sie nur die Großen geniessen!`&\"");
				}
				break;

			case 'gems':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$session['user']['gems']+=$amount;
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("$amount Edelsteine für $cost QP erkauft.");
				}
				else
				{
					output("`5Juwelen glitzern und sind hübsch anzusehen. Gönne dir doch einfach ".($amount==1 ? 'diesen Edelstein' : 'diese '.$amount.' Edelsteine')."!`nDu wirst begeistert sein!`&\"");
				}
				break;

			case 'ale':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="klfale" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("1 Fass Ale für $cost QP erkauft.");
				}
				else
				{
					output("`5Na, keinen Mumm Cedrick herauszufordern? Klar doch, du willst es dir nicht mit ihm verscherzen! Hier bekommst du ein Fass seines besten Hausales, ohne dich schlagen zu müssen!`nCedricks Ale - keins dröhnt mehr!");
				}
				break;

			case 'revive':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$session['user']['deathpower']+=200;
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("2 Leben für $cost QP erkauft.");
				}
				else
				{
					output("`5Der Tod kann eine unüberwindliche Barriere zwischen zwei Wesen sein!`nDiese 200 Gefallen erlauben es dir, dich selbst zweimal auferstehen zu lassen!`&\"");
				}
				break;

			case 'ramius':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="quizram" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Ramius-Tour für $cost QP erkauft.");
				}
				else
				{
					output("`5Einmal Ramius und zurück!`nDiese Sightseeing-Tour durch das Totenreich zeigt dir einmal alle Sehenwürdigkeiten des Jenseits. Ob Mausoleum, Seelenquälen oder Tot-o-Lotto, sei dabei! Mit 5 extra-Grabkämpfen!`nFür die Rückreise bekommst du 100 Gefallen - aber pass auf, dass dein Ansehen nicht zu stark sinkt.`nDenn sonst musst du dennoch unten bleiben!`&\"");
				}
				break;

			case 'reputation':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$session['user']['reputation']=50;
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Ansehen für $cost QP erkauft.");
				}
				else
				{
					output("`5Niemand mag dich?`nDas können wir ändern! Nimm diesen Preis und dein Ansehen wird so hoch sein, wie es in deinem Fall nur möglich ist!`&\"");
				}
				break;

			case 'weapon':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="quizweap" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Waffe für $cost QP erkauft.");
				}
				else
				{
					output("`5Gelangweilt von Thorims Waffen für Hinz und Kunz?`nWillst du etwas besonderes sein? Dann greif zu - diese Waffe ist genau so außergewöhnlich wie du selbst!`&\"");
				}
				break;

			case 'armor':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="quizarm" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Rüstung für $cost QP erkauft.");
				}
				else
				{
					output("`5Bist du es Leid, die billigen Rüstungen von Pegasus aufzutragen?`nWillst du dich vom Pöbel absetzen? Dann greif zu - diese Rüstung gibt dir was du brauchst!`&\"");
				}
				break;

			case 'fuchs':
				if ($confirm)
				{
					if ($session['user']['hashorse']>0)
					{
						output("`&Du hast bereit ein Tier.`nWenn du diesen Preis abholen möchstest, musst du es zuerst irgendwo loswerden!`n");
					}
					else
					{
						output("`&Alles klar! Der Preis gehört dir!`n");
						$session['user']['hashorse']=73;
						getmount($session['user']['hashorse'],true);
						$session['bufflist']['mount']=utf8_unserialize($playermount['mountbuff']);
						$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
						db_query($sql);
						debuglog("Schlaufuchs für $cost QP erkauft.");
					}
				}
				else
				{
					output("`5Willst du ein ganz besonderes Tier, das du nirgendwo anders finden wirst und das vielseitig verwendbar ist?`nEinen kleinen, schlauen Fuchs, um den dich jeder beneiden wird? Dann greif zu!`&\"");
				}
				break;

			case 'luft':
				if ($confirm)
				{
					$mark=$session['user']['marks'];
					if ($mark>=16)
					{
						$mark-=16;
					}
					if ($mark>=8)
					{
						$mark-=8;
					}
					if ($mark>=4)
					{
						$mark-=4;
					}

					if ($mark<2)
					{
						output("`&Alles klar! Der Preis gehört dir!`n");
						$session['user']['marks']+=2;
						$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
						db_query($sql);
						debuglog("Luftmal für $cost QP erkauft.");
						addnews("`%".$session['user']['name']."`& Hat das `!Mal der Luft`& gegen Wissen erlangt!");
					}
					else
					{
						output("`&Du hast das Mal doch bereits!`nWarum also willst du es erneut?`n");
					}
				}
				else
				{
					output("`5Keine Lust, eine halbe Ewigkeit durchs Schloss zu rennen und tausend Tode zu sterben?`nOder einfach nur verzweifelt wegen deiner Unwürdigkeit? Hier hast du die Chance das Mal zu bekommen!`&\"");
				}
				break;

			case 'tafel':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="zbrtafel" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Zaubertafel für $cost QP erkauft.");
				}
				else
				{
					output("`5Du brauchst eine Zaubertafel, findest aber keine?`nKein Problem, hier bekommst du eine funkelnagelneue!`&\"");
				}
				break;

			case 'othala':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="r_othala" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Othala-Rune für $cost QP erkauft.");
				}
				else
				{
					output("`5Wer hätte sie nicht gern, die Othala-Rune?`nTja... deine Chance sie zu bekommen ist hier und jetzt!`&\"");
				}
				break;

			case 'dagaz':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="r_dagaz" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Dagaz-Rune für $cost QP erkauft.");
				}
				else
				{
					output("`5Wer hätte sie nicht gern, die Dagaz-Rune?`nTja... deine Chance sie zu bekommen ist hier und jetzt!`&\"");
				}
				break;
				
				case 'algiz':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$itemnew = item_get_tpl(' tpl_id="r_algiz" ' );
					if ($itemnew)
					{
						item_add($session['user']['acctid'],0,$itemnew);
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Algiz-Rune für $cost QP erkauft.");
				}
				else
				{
					output("`5Wer hätte sie nicht gern, die Algiz-Rune?`nTja... deine Chance sie zu bekommen ist hier und jetzt!`&\"");
				}
				break;

			case 'amnesty':
				if ($confirm)
				{
					output("`&Alles klar! Der Preis gehört dir!`n");
					$new_days=$session['user']['daysinjail']-10;
					If ($new_days>0)
					{
						$session['user']['daysinjail']=$new_days;
					}
					else
					{
						$session['user']['daysinjail']=0;
					}
					$sql = "UPDATE account_extra_info SET quizpoints_spent=quizpoints_spent+$cost WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					debuglog("Amnestie für $cost QP erkauft.");
				}
				else
				{
					output("`5Ist die Gesellschaft dran Schuld, dass du in deiner Jugend oft im Kerker warst? Verbaut es dir heute dein Leben?`nNatürlich kannst du nichts dafür und hiermit wird deine Weste wieder so weiß wie... ein Ogerzahn... Naja, ich kann auch keine Wunder wirken, aber $amount Hafttage kann ich schon verschwinden lassen!`&\"");
				}
				break;

		}
		if (!$confirm)
		{
			addnav("Diesen Preis nehmen?");
			addnav("Ja",$str_file."?op=getprice&id=$id&cost=$cost&amount=$amount&confirm=1");
			addnav("Andere Preise");
			addnav("Nochmal schaun",$str_file."?op=prices");
		}
		addnav("Verschwinden");
		addnav($str_backtext,$str_backlink);
		break;

	case '':
		output("`&Willkommen beim großen ".getsetting('townname','Atrahor')." Quiz!`n`n
    	Jeder der mächtigen Krieger und Helden hat das Recht, einmal an diesem Spiel teilzunehmen, bevor er eine Heldentat vollbringt. Jawohl, einmal und nicht öfter! Es kann nicht gespart werden und einen Vorgriff gibt es auch nicht!`n`nDie Regeln sind einfach: Gunther Lauch stellt dir $number_of_rounds teils leichte, teils knifflige, teils lustige Fragen rund um ".getsetting('townname','Atrahor')." und gibt mehrere Antwortmöglichkeiten zur Auswahl.`nFür jede richtige Antwort gibt es einen Punkt. Wenn du alle Fragen richtig beantwortest, erhälst du 2 weitere Punkte als Bonus!`n`n");
		addnav("Teilnehmen");
		addnav("Spielen",$str_file."?op=ready");
		addnav("Punkte einlösen");
		addnav("Preis holen",$str_file."?op=prices");
		addnav("Weggehen");
		addnav($str_backtext,$str_backlink);
		break;
}
page_footer();
?>