<?php
/**
* inn.php: Schenke zum Eberkopf
* @author LOGD-Core, modded by Drachenserver-Team @ atrahor.de
* @version DS-E V/2
*/

// 15082004

// MOD by tcb, 14.5.05: Neue Berechnungsformel für LP

require_once('common.php');
require_once(LIB_PATH.'board.lib.php');

function get_lp_gems()
{
	global $Char;

	$lp_max = get_max_hp();

	$val = 2 + min(max($Char->dragonkills - 9, 0 ) , 1 )
	//+ ( ceil(min($Char->dragonkills - 9 , 40 ) * 0.05 ) )
	+ ceil(max($Char->maxhitpoints - $lp_max, 0 ) * 0.003 );

	return(min((int)$val , 15 ) );
}

function get_ale_stats()
{
	// Lade die Menge der Fässer und schreibe in Array
	$totalkeg = getsetting('totalkeg',50);
	$keg_info['total'] = $totalkeg;
	// Berechnung Goldkosten, max. 12000 Gold
	if($totalkeg < 40)
	{
		$keg_info['gold']=12000;
	}
	else
	{
		$value = (50 - $totalkeg)/10 * 6000;
		$keg_info['gold']=6000 + $value;
	}
	// Berechnung Edelsteinkosten, max. 15 Gems
	if($totalkeg < 35)
	{
		$keg_info['gems']=15;
	}
	else
	{
		$keg_info['gems']=50 - $totalkeg;
	}
	return $keg_info;
}

music_set('schenke');



addcommentary();
checkday();

if ($Char->imprisoned>0)
{
	redirect('prison.php');
}

$buff = array('name'=>'`!Schutz der Liebe','rounds'=>60,'wearoff'=>'`!Du vermisst '.($Char->sex?'Seth':'`5Violet`').'!.`0','defmod'=>1.2,'roundmsg'=>'Deine große Liebe lässt dich an deine Sicherheit denken!','activate'=>'defense');

//Der Säufertod
//Elfen müssen aufpassen
if ($Char->race=='elf')
{
	if ($Char->drunkenness>=99)
	{
		page_header("Du hast soviel gesoffen");
		output("`[Du hast zuviel gesoffen und bist an einer Alkoholvergiftung gestorben.
		`n`nDu verlierst 5% deiner Erfahrungspunkte und die Hälfte deine Goldes!
		`n`nDu kannst morgen wieder spielen.");
		killplayer(50,5,0,'news.php','Tägliche News');
		addnews($Char->name." hat ".($Char->sex?"ihren":"seinen")." zarten Elfenkörper in der Kneipe mit zuviel Ale zugrunde gerichtet.");
		page_footer();
	}
}
else if ($Char->drunkenness>=99)
{
		//Zwerge vertragen mehr
		if ($Char->race=='zwg')
		{
			switch (e_rand(1,10))
			{
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
					if (($Char->profession!=PROF_JUDGE) && ($Char->profession!=PROF_JUDGE_HEAD) && ($Char->profession!=PROF_GUARD) && ($Char->profession!=PROF_GUARD_HEAD))
					{
						$str_output .= '`[Du hast zwar zuviel gesoffen, aber da ein Zwerg einiges vertragen kann, hast du es gerade noch überlebt. Du erwachst in der Ausnüchterungszelle.`n
						Du verlierst den Großteil deiner Lebenspunkte!';
						$Char->imprisoned=1;
						addnews($Char->name.' entging nur knapp den Folgen einer Alkoholvergiftung, weil '.($Char->sex?'sie eine Zwergin':'er ein Zwerg').' ist und verbringt die Nacht in der Ausnüchterungszelle.');
						addnav('Weiter','prison.php');
					}
					elseif ($Char->profession!=PROF_GUARD || $Char->profession!=PROF_GUARD_HEAD)
					{
						$str_output .= '`$Du hast zwar zuviel gesoffen, aber da ein Zwerg einiges vertragen kann, hast du es gerade noch überlebt. Als Stadtwache bleibt dir die Ausnüchterungszelle erspart.`n
						Du verlierst den Großteil deiner Lebenspunkte!`n`n';
						addnews('`@Stadtwache '.$Char->name.' `@entging nur knapp den Folgen einer Alkoholvergiftung, weil '.($Char->sex?'sie eine Zwergin':'er ein Zwerg').' ist und muss dank Immunität für die Stadtwachen nicht in die Ausnüchterungszelle.');
						$Char->drunkenness=50;
						//addnav('Weiter','village.php');
					}
					else
					{
						$str_output .= '`$Du hast zwar zuviel gesoffen, aber da ein Zwerg einiges vertragen kann, hast du es gerade noch überlebt. Als Richter bleibt dir die Ausnüchterungszelle erspart.`n
						Du verlierst den Großteil deiner Lebenspunkte!`n`n';
						addnews('`@Richter '.$Char->name.' `@entging nur knapp den Folgen einer Alkoholvergiftung, weil '.($Char->sex?'sie eine Zwergin':'er ein Zwerg').' ist und muss dank richterlicher Immunität nicht in die Ausnüchterungszelle.');
						$Char->drunkenness=50;
						//addnav('Weiter','village.php');
					}
					$Char->hitpoints=1;
					output($str_output);
					break;
				case 6:
				case 7:
				case 8:
				case 9:
				case 10:
					page_header("Du hast soviel gesoffen");
					output("`[Du hast zuviel gesoffen und bist an einer Alkoholvergiftung gestorben.
					`n`nDu verlierst 5% deiner Erfahrungspunkte und die Hälfte deine Goldes!
					`n`nDu kannst morgen wieder spielen.");
					killplayer(50,5,0,'news.php','Tägliche News');
					addnews($Char->name." starb in der Kneipe an einer Überdosis Ale ");
					page_footer();
					break;
			}
		}
		else 
		{
			//Alle anderen bekommen ne Chance
			switch (e_rand(1,10))
			{
				case 1:
				case 2:
				case 3:
				case 4:
					if (($Char->profession!=21) && ($Char->profession!=22) && ($Char->profession!=PROF_GUARD) && ($Char->profession!=PROF_GUARD_HEAD))
					{
						$str_output .= '`[Du hast zwar zuviel gesoffen, es aber gerade noch überlebt. Du erwachst in der Ausnüchterungszelle.`n
						Du verlierst den Großteil deiner Lebenspunkte!';
						$Char->imprisoned=1;
						addnews($Char->name.' entging nur knapp den Folgen einer Alkoholvergiftung und verbringt die Nacht in der Ausnüchterungszelle.');
						addnav('Weiter','prison.php');
					}
					else if ($Char->profession!=PROF_GUARD || $Char->profession!=PROF_GUARD_HEAD)
					{
						$str_output .= '`$Du hast zwar zuviel gesoffen, es aber gerade noch überlebt. Als Stadtwache bleibt dir die Ausnüchterungszelle erspart.`n
						Du verlierst den Großteil deiner Lebenspunkte!`n`n';
						addnews('`@Stadtwache '.$Char->name.' `@entging nur knapp den Folgen einer Alkoholvergiftung und muss dank Immunität für die Stadtwachen nicht in die Ausnüchterungszelle.');
						$Char->drunkenness=50;
						//addnav('Weiter','village.php');
					}
					else
					{
						$str_output .= '`$Du hast zwar zuviel gesoffen, es aber gerade noch überlebt. Wegen deiner richterlichen Immunität musst du nicht in die Ausnüchterungszelle.`n
						Du verlierst den Großteil deiner Lebenspunkte!`n`n';
						$Char->drunkenness=50;
						addnews('`@Richter '.$Char->name.' `@entging nur knapp den Folgen einer Alkoholvergiftung und muss dank richterlicher Immunität nicht in den Kerker.');
						//addnav('Weiter','village.php');
					}
					$Char->hitpoints=1;
					output($str_output);
					break;
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:
				case 10:
					page_header("Du hast soviel gesoffen");
					output("`[Du hast zuviel gesoffen und bist an einer Alkoholvergiftung gestorben.
					`n`nDu verlierst 5% deiner Erfahrungspunkte und die Hälfte deine Goldes!
					`n`nDu kannst morgen wieder spielen.");
					killplayer(50,5,0,'news.php','Tägliche News');
					addnews($Char->name." starb in der Kneipe an einer Überdosis Ale ");
					page_footer();
					break;
		}
	}
}

page_header('Schenke zum Eberkopf');
output('`c`b`(S`)c`7h`ee`_n`[ke zum Ebe`_r`ek`7o`)p`(f`0`b`c`n');
if ($_GET['op']=='strolldown')
{
	if($Char->exchangequest==13) //Jre rf jrvß fbyyf unyg znpura
	{
		$Char->specialinc='inn_brawl.php';
	}
}
if ($_GET['op']=='' || $_GET['op']=='strolldown')
{
	//specials nicht bei Cedrik-verprügeln eintreten lassen. Sind sicher noch andere Stellen wo es unpassend ist, die aber weniger genutzt werden und nicht auffallen. (Salator)
	spc_get_special('inn',7,'',array('op'));

	output('`(P`)fe`7if`ee`_nt`[abak, Bierdünste und der eindeutige Geruch von Schweiß liegen hier schwer in der Luft und umhüllen jeden neuen Besucher der Schenke.
	Hier, wo sich die Stadtbewohner einfinden, wenn schlechtes Wetter über die Stadt fegt, sie sich den Anstrengungen des Tages entledigen oder sich ein Ale gönnen, um dem Alltag im eigenen Hause zu entgehen.
	'.($Char->sex?'Ein anderer Grund könnte auch Seth sein, der sich grade an seiner Laute zu schaffen macht und sich um wohlklingende Töne bemüht.':'Ein anderer Grund könnte auch `5Violet`[ sein, die ihr aufreizendes Kleid zurechtzupft, um sich dann besonders um die Sorgen der männlichen Besucher zu kümmern.').'
	`nDas flackernde Licht von Kamin und Kerzen verleiht der Schankstube eine gemütliche Atmosphäre, in der genug Bänke, Tische und auch Barhocker aufgestellt sind, um jedem Besucher einen Sitzplatz zu bieten.
	Cedrik, der Wirt, steht hinter der Theke und hat dabei immer ein Auge für den Schankraum, sowohl um seine Gäste zu bedienen, aber auch um für Ordnung zu sorgen.
	Nebenbei unterhält er sich aber freundlich mit einem weiteren Gast, der scheinbar voller Euphorie auf ihn einredet und irgendetwas über ');

	switch (e_rand(1,14))
	{
		case 1:
			output('`oDrachen `_er`ez`7ä`)hl`(t.');
			break;
		case 2:
			output('`oSeth `_er`ez`7ä`)hl`(t.');
			break;
		case 3:
			output('`oViolet `_er`ez`7ä`)hl`(t.');
			break;
		case 4:
			output('`oThorim `_er`ez`7ä`)hl`(t.');
			break;
		case 5:
			output('`oleckeres Ale `_er`ez`7ä`)hl`(t.');
			break;
		case 6:
			output('`oKala `_er`ez`7ä`)hl`(t.');
			break;
		case 7:
			output('`oZwergenweitwurf `_er`ez`7ä`)hl`(t.');
			break;
		case 8:
			output('`odas elementare Zerwürfnis des Seins `_er`ez`7ä`)hl`(t.');
			break;
		case 9:
			output('`ohäufig gestellte Fragen `_er`ez`7ä`)hl`(t.');
			break;
		case 10:
			output('`oManwe und Bibir `_er`ez`7ä`)hl`(t.');
			break;
		default:
			$row = db_fetch_assoc(db_query('SELECT name FROM accounts WHERE locked=0 ORDER BY rand('.e_rand().') LIMIT 1'));
			output('`o'.$row['name'].' `_er`ez`7ä`)hl`(t.');
			break;
	}
	if (getsetting('pvp',1))
	{
		output(' `n`[Dag Durnick sitzt übel gelaunt mit seiner Pfeife  im Mund in einer der schlecht beleuchteten Ecken des Raumes. ');
	}
	output('`n`n`[Die Uhr am Kamin zeigt `u'.getgametime(true).'`[.
	`n`n`4Cedrik überwacht immer sehr genau, welche Anschläge an sein Brett gehängt werden.
	Da er sich allerdings um einen guten Ruf bemüht, verbietet er hier Nachrichten, die gegen das Gesetzbuch von Atrahor verstoßen.
	Hinter vorgehaltener Hand flüstert er jedem, der eine solche Nachricht zu verkünden hat, dass diese in der Taverne in der dunklen Gasse willkommen sind!
	`n`n');
	board_view('inn" OR b.section="immo" OR b.section="sell',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,
	'`[Die neueste Nachricht am schwarzen Brett ist:',
	'`[Am schwarzen Brett neben der Tür ist nicht eine einzige Nachricht zu sehen.',
	true,false,false,true,1);

	if ($Char->imprisoned==0)
	{

		$show_invent = true;

		addnav('Was machst du?');
		if ($Char->sex==0)
		{
			addnav('V?Flirte mit Violet','inn.php?op=violet');
		}
		else
		{
			addnav('V?Quatsche mit Violet','inn.php?op=violet');
		}
		addnav('S?Rede mit dem Barden Seth','inn.php?op=seth');
		addnav('F?Mit Freunden unterhalten','inn.php?op=converse');
		if (item_count(' tpl_id="dineinl" AND owner='.$Char->acctid) )
		{
			addnav('C?Zum Candlelight Dinner','dinner.php');
		}
		addnav('Schwarzes Brett');
		addnav('Immobilienmarkt','inn.php?op=viewboard&board=immo');
		addnav('Kauf/Verkauf','inn.php?op=viewboard&board=sell');
		addnav('t?Sonstiges','inn.php?op=viewboard&board=inn');
		addnav('Hinzufügen','inn.php?op=msgboard');
		addnav('Personen');
		addnav('W?Sprich mit dem Wirt Cedrik','inn.php?op=bartender');
		if (getsetting('pvp',1))
		{
			addnav('D?Mit Dag Durnick sprechen','dag.php');
		}
		addnav('O?Mit Old Drawl sprechen','olddrawl.php');

		addnav('Sonstiges');
		addnav('n?Zimmer nehmen (Log out)','inn.php?op=room');
		addnav('Zurück zum Stadtzentrum','village.php');
	}
}
else
{
	switch ($_GET['op'])
	{
		case "msgboard":
		{
			$boards=array(
			'immo' => 'Haus/Schlüssel/Ausbau',
			'sell' => 'Kauf/Verkauf',
			'inn' => 'Sonstiges'
			);

			if ($_GET['act']=="add1")
			{
				$msgprice=$Char->level*6*(int)$_GET['amt'];
				if ($_GET['board_action'] == "")
				{
					output("`[Cedrik kramt ein Pergament und ein Feder unter der Theke hervor und erwartet, dass du ihm diktierst, was er für dich notieren soll. Da viele Kunden des Schreibens nicht mächtig sind, musste er selbst lernen, zu schreiben. `s\"Das macht dann `^$msgprice`s Gold. Wie soll die Nachricht lauten?\"`n`n");

					board_view_form('Ans schwarze Brett',
					'`[Gib deine Nachricht ein:');
				}
				else
				{
					if ($Char->gold<$msgprice)
					{
						output('`[Als Cedrik bemerkt, dass du offensichtlich nicht genug Gold hast, schnauzt er dich an: `s"So kommen wir nicht ins Geschäft, Kleine'.($Char->sex?'':'r').'. Sieh zu, dass du Land gewinnst. Oder im Lotto."');
					}
					else
					{
						if (board_add($_GET['board'],(int)$_GET['amt'],1) == -1)
						{
							output('`[Cedrik verdreht die Augen und fordert von dir: `s"Du hast schon einen Zettel da hängen. Reiß den erst ab."');
						}
						else
						{
							output('`[Mürrisch nimmt Cedrik dein Gold, schreibt deinen Text auf den Zettel und ohne ihn nochmal durchzulesen, heftet er ihn zu den anderen an das schwarze Brett neben der Eingangstür.');
							$Char->gold-=$msgprice;
						}
					}
				}
			}
			elseif ($_GET['board'])
			{
				$msgprice=$Char->level*6;
				$msgdays=(int)getsetting("daysperday",4);
				output('`s"In die Rubrik '.$boards[$_GET['board']].' also. Wie lang soll die Nachricht denn dort zu sehen sein?" `[fragt dich Cedrik fordernd und nennt dir seine Preise.');
				addnav($msgdays.' Tage (`^'.$msgprice.'`0 Gold)','inn.php?op=msgboard&act=add1&board='.$_GET['board'].'&amt=1');
				addnav(($msgdays*3).' Tage (`^'.($msgprice*3).'`0 Gold)','inn.php?op=msgboard&act=add1&board='.$_GET['board'].'&amt=3');
				addnav(($msgdays*10).' Tage (`^'.($msgprice*10).'`0 Gold)','inn.php?op=msgboard&act=add1&board='.$_GET['board'].'&amt=10');
				addnav('Lieber woanders hin','inn.php?op=msgboard');
				if ($Char->message>"")
				{
					output('`nEr macht dich noch darauf aufmerksam, dass er deine alte Nachricht entfernen wird, wenn du jetzt eine neue anbringen willst.');
				}
			}
			else
			{
				output('`(D`)u`7g`ei`_b`[st Cedrik zu verstehen, dass du auch eine Nachricht am Schenkenbrett aufhängen möchtest, um mit anderen Bürgern zu kommunizieren. Cedrik mustert dich erst prüfend und nickt dann `s"Du möchtest eine Nachricht am schwarzen Brett hinterlassen, ja? Welcher Art soll denn die Nachricht sein? Und denk dran, ich will hier keine zwielichten Machenschaften `[h`_a`7b`)e`(n!"');
				addnav('Haus/Schlüssel/Ausbau','immo_board.php');
				addnav('Kauf/Verkauf','inn.php?op=msgboard&board=sell');
				addnav('Sonstiges','inn.php?op=msgboard&board=inn');
			}
			break;
		}

		case 'viewboard':
		{
			board_view($_GET['board'],($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,
			'`[An der Tafel hängen diese Pergamente:',
			'`[An dieser Tafel hängt kein Angebot oder Gesuch aus.');
			break;
		}

		case "violet":
		{
			/*
			Wink
			Kiss her hand
			Peck her on the lips
			Sit her on your lap
			Grab her backside
			Carry her upstairs
			Marry her
			*/
			if ($Char->sex==1)
			{
				if ($_GET['act']=="")
				{
					addnav("Tratsch","inn.php?op=violet&act=gossip");
					addnav("Frage, ob dich dein ".$Char->armor." dick aussehen lässt","inn.php?op=violet&act=fat");
					output("`(B`)e`7v`_o`[r du dich entscheidest, zu `5Violet`[ zu schlenderst, wartest du noch etwas ab, damit sie erst noch ihr \"Gespräch\" mit dem Herren beenden kann.
					Plötzlich lacht sie mit glockenheller Stimme über einen wahrscheinlich eher anzüglichen Witz und schüttelt dabei ihr langes, schwarzes Haar. Beinahe jeder männliche Gast folgt ihr spätestens jetzt mit seinen Blicken.
					Noch ein letztes bezauberndes Lächeln und sie nimmt das Tablett mit vielen leeren Gläsern wieder auf, um zur Theke zurückzukehen. Du nutzt diesen Moment, um ihr durch einen Wink zu verdeutlichen, dass du gern mit ihr reden würdest.
					Zwar ist ihr exzentrischer Lebensstil in der ganzen Stadt kein Geheimnis, doch in der Männerwelt kennt sie sich dadurch am best`[e`_n `7a`)u`(s!");

				}
				else if ($_GET['act']=="gossip")
					{
						output("`(F`)ü`7r`_ e`[in paar Minuten tratschst du mit `5Violet`[ über alles und nichts, besonders aber über die Männer in der Stadt. Sie gibt dir ein paar wertvolle Tipps und empfiehlt dir
						bei dieser Gelegenheit auch Kalas Beautyshop, die eine ganz besondere Gurkenmaske anwendet, um die Haut noch geschmeidiger zu machen. Bei ihr selbst würde das immer noch wunderbar funktionieren, erklärt Violet lächelnd. Nach ein paar Minuten
						bemerkt ihr allerdings beide, dass Cedrik euch bereits tadelnde Blicke zuwirft und du beschließt, dass es besser ist, Violet wieder ihre Arbeit nachkommen zu l`[a`_s`7s`)e`(n. ");
					}
					else if ($_GET['act']=="fat")
						{
							$charm = $Char->charm+e_rand(-1,1);
							output("`5Violet`[ mustert dich ernst von oben bis unten an und bildet sich dabei ein genaues Bild von deinem Äußeren.
							Wenn jemand in der Stadt dir wirklich sagen kann, wie du dein Aussehen verbessern kannst, dann wird es wohl nur Violet sein. Schließlich schmunzelt sie leicht und antwortet dir ehrlich auf deine Frage: ");
							switch ($charm)
							{
								case -3:
								case -2:
								case -1:
								case 0:
									output("`R\"Dein Outfit lässt nicht viel Spielraum für Fantasie, aber über manche Dinge sollte man auch wirklich nicht nachdenken. Du solltest etwas weniger freizügige Kleidung in der Öffentlichkeit tragen!\"");
									break;
								case 1:
								case 2:
								case 3:
									output("`R\"Ich habe schon einige reizvolle Damen gesehen, aber ich fürchte du bist keine davon.\"");
									break;
								case 4:
								case 5:
								case 6:

									output("`R\"Ich habe schon schlimmeres gesehen, aber nur beim Verfolgen eines Pferdes.\"");
									break;
								case 7:
								case 8:
								case 9:
									output("`R\"Du bist ziemlicher Durchschnitt, meine Gute.\"");
									break;
								case 10:
								case 11:
								case 12:
									output("`R\"Du bist schon etwas zum Anschauen, aber lass dir das nicht zu sehr zu Kopfe steigen, ja?\"");
									break;
								case 13:
								case 14:
								case 15:
									output("`R\"Du siehst schon ein bisschen besser aus als der Durchschnitt!\"");
									break;
								case 16:
								case 17:
								case 18:
									output("`R\"Nur wenige Frauen können von sich behaupten, mit dir mithalten zu können!\"");
									break;
								case 19:
								case 20:
								case 21:
								case 22:
									output("`R\"Willst du mich mit dieser Frage neidisch machen? Oder mich einfach nur ärgern?\"");
									break;
								case 23:
								case 24:
								case 25:
									output("`R\"Ich bin von deiner Schönheit geblendet.\"");
									break;
								case 26:
								case 27:
								case 28:
								case 29:
								case 30:
									output("`R\"Ich hasse dich. Warum? Weil du einfach die schönste Frau aller Zeiten bist!\"");
									break;
								default:
									output("`R\"Vielleicht solltest du langsam etwas gegen deine überirdische Schönheit tun. Du bist unerreichbar!\"");
							}
						}
			}
			if ($Char->sex==0)
			{
				if ($Char->seenlover==0)
				{
					if ($Char->marriedto==4294967295)
					{
						if (e_rand(1, 4)==1)
						{
							output("`[Als `5Violet`[ sich endlich von einem anderen Gast verabschiedest, beeilst du dich, ihr den Weg abzuschneiden. Ihre Schönheit ist auch dir zu Ohren gekommen und du kannst dieses Gerücht nur bestärken. Etwas unbeherrscht und übereifrig greifst du ihr ins Haar und willst sie im Gesicht und am Hals mit Küssen verwöhnen, aber sie brummelt nur ablehnend etwas ");
							switch (e_rand(1,4))
							{
								case 1:
									output("`[davon, dass sie zu beschäftigt damit ist, diese Schweine zu bedienen.");
									break;
								case 2:
									output("`[wie \"diese Zeit des Monats\".");
									break;
								case 3:
									output("`[wie \"eine   leichte   Erkältung...\"  *hust hust* .. .");
									break;
								case 4:
									output("`[darüber, dass alle Männer Schweine sind.");
									break;
							}
							output(" Dass sie allerdings so zickig ist, hatte man wohl leider vergessen, dir zu sagen. Nach so einer eiskalten Absage, machst du lieber, dass du davon kommst.");
							$Char->charm = max(0,$Char->charm-1);
							output("`n`n`&Du VERLIERST einen Charmepunkt!");
						}
						else
						{
							output("`[Als `5Violet`[ sich endlich von einem anderen Gast verabschiedest, beeilst du dich, ihr den Weg abzuschneiden. Ihre Schönheit ist auch dir zu Ohren gekommen und du kannst dieses Aussage nur bestärken. Etwas unbeherrscht und übereifrig greifst du ihr ins Haar und willst sie im Gesicht und am Hals mit Küssen verwöhnen. Sie stellt vorher noch schnell ihr
							Tablett ab und schmust ein bisschen mit dir. Schließlich flüstert sie dir nur zu, dass sie wieder arbeiten muss und lässt dich mit einem letzten Lächeln stehen.");
							$session['bufflist']['lover']=$buff;
							$Char->charm++;
							output("`n`n`&Du erhältst einen Charmepunkt!");
						}
						$Char->seenlover=1;
					}
					else if ($_GET['flirt']=="")
						{
							output("`[Du starrst verträumt durch den Raum auf `5Violet`[, die sich über einen Tisch beugt, ");
							output("`[um einem Gast einen Drink zu servieren. Dabei zeigt sie vielleicht etwas mehr Haut als ");
							output("`[nötig, aber du fühlst absolut keinen Drang danach, ihr das vorzuhalten. Ihr langes Haar fällt ihr dabei nach vorn ums Gesicht und unterstreicht ihre wunderschönen Gesichtszüge.");
							output("`[Auch ihre eleganten Bewegungen lassen deine Gedanken zum Träumen abschweifen.");
							addnav("Flirt");
							addnav("Zwinkern","inn.php?op=violet&flirt=1");
							addnav("Handkuss","inn.php?op=violet&flirt=2");
							addnav("Küsschen auf die Lippen","inn.php?op=violet&flirt=3");
							addnav("Setze sie auf deinen Schoß","inn.php?op=violet&flirt=4");
							addnav("Greif ihr an den Hintern","inn.php?op=violet&flirt=5");
							addnav("Trag sie nach oben","inn.php?op=violet&flirt=6",false,false,false,false,$Char->charisma>=999?'Du weißt, dass dich deine Partnerin sehen könnte?':'');
							if ($Char->charisma!=4294967295)
							{
								addnav("Heirate sie","inn.php?op=violet&flirt=7");
							}
					}
					else
					{
						$c = $Char->charm;
						$Char->seenlover=1;
						switch ($_GET['flirt'])
						{
							case 1:
								if (e_rand($c,2)>=2)
								{
									output("`[Du zwinkerst `5Violet`[ zu und sie schenkt dir dafür ein warmes Lächeln, während sie beginnt, einen Tisch abzuwischen.");
									if ($c<4)
									{
										$c++;
									}
								}
								else
								{
									output("`[Du zwinkerst `5Violet`[ zu, doch diese zieht nur kurz eine Augenbraue hoch und scheint dich dann völlig zu übergehen. Anscheinend ist sie nicht an dir interessiert.");
								}
								break;
							case 2:
								if (e_rand($c,4)>=4)
								{
									output("`[Selbstsicher schlenderst du Richtung `5Violet`[ durch den Raum. Du nimmst ihre Hand, küsst sie sanft und hältst so für einige Sekunden inne. Violet errötet leicht und streift ihre schwarze Haarsträhne hinters Ohr. Während du dich zurückziehst, presst sie die Rückseite ihrer Hand sehnsüchtig an ihre Wange.");
									if ($c<7)
									{
										$c++;
									}
								}
								else
								{
									output("`[Selbstsicher schlenderst du Richtung `5Violet`[ durch den Raum und greifst nach ihrer Hand.
									`n`nAber Violet entzieht dir diese sofort und fragt dich nur sachlich, ob du vielleicht ein Ale bestellen möchtest.");
								}
								break;
							case 3:
								if (e_rand($c,7)>=7)
								{
									output("`[Du lehnst lässig mit deinem Rücken an einer hölzernen Säule und wartest, bis `5Violet`[ in deine Richtung schlendert. Dann rufst du sie mit einem Lächeln auf den Lippen zu dir und betrachtest dabei ihren ganzen Körper. Sie nähert sich dir mit der Andeutung eines Lächelns im Gesicht. Du umfasst ihr Kinn, hebst es etwas an und presst ihr einen schnellen Kuss auf ihre prallen Lippen.");
									if ($Char->charisma==4294967295)
									{
										output("`[Deine Frau wird gar nicht begeistert sein, wenn sie davon erfährt!");
										$c--;
									}
									else
									{
										if ($c<11)
										{
											$c++;
										}
									}
								}
								else
								{
									output("`[Du lehnst mit deinem Rücken an einer hölzernen Säule und wartest, bis `5Violet`[ in deine Richtung läuft. Dann rufst du sie zu dir. Sie lächelt und bedauert, dass sie mit ihrer Arbeit einfach zu beschäftigt ist, um sich einen Moment für dich Zeit zu nehmen.");
								}
								break;
							case 4:
								if (e_rand($c,11)>=11)
								{

									output("`[Du sitzt an einem Tisch und lauerst auf deine Gelegenheit. Als `5Violet`[ bei dir vorbei kommt, umarmst du sie an der Hüfte und ziehst sie auf deinen Schoss. Sie lacht und wirft dir ihre Arme in einer warmen Umarmung um den Hals. Schließlich klopft sie dir auf die Brust und besteht darauf, dass sie wirklich wieder an die Arbeit gehen sollte.");
									if ($Char->charisma==4294967295)
									{
										output(" `[Deine Frau wird gar nicht begeistert sein, wenn sie davon erfährt!");
										$c--;
									}
									else
									{
										if ($c<14)
										{
											$c++;
										}
									}
								}
								else
								{
									output("`[Du sitzt an einem Tisch und lauerst auf deine Gelegenheit. Als `5Violet`[ bei dir vorbei kommt, grapschst du nach ihrer Hüfte, aber sie weicht geschickt aus, ohne auch nur einen Tropfen von dem Ale zu verschütten, das sie trägt.");
									if ($c>0 && $c<10)
									{
										$c--;
									}
								}
								break;
							case 5:
								if (e_rand($c,14)>=14)
								{
									output("`[Du wartest, bis `5Violet`[ an dir vorbeirauscht und gibst ihr einen Klaps auf den Hintern. Sie dreht sich um und schenkt dir ein warmes, wissendes Lächeln.");
									if ($Char->charisma==4294967295)
									{
										$c--;
									}
									else
									{
										if ($c<18)
										{
											$c++;
										}
									}
								}
								else
								{
									output("`[Du wartest, bis `5Violet`[ an dir vorbeirauscht und gibst ihr einen Klaps auf den Hintern. Sie dreht sich um und verpasst dir eine Ohrfeige. Eine gepfefferte! Vielleicht solltest du es etwas langsamer angehen.");
									//$Char->hitpoints=1;
									if ($c>0 && $c<13)
									{
										$c--;
									}
								}
								if ($Char->charisma==4294967295)
								{
									output(" `[Deine Frau wird gar nicht begeistert sein, wenn sie davon erfährt!");
								}
								break;
							case 6:
								if (e_rand($c,18)>=18)
								{
									output('`[Wie ein Wirbelwind braust du durch die Schenke, schnappst dir `5Violet`[, die dir ihre Arme um den Hals wirft und trägst sie in ihren Raum nach oben. Kaum zehn Minuten später stolzierst du, eine Pfeife rauchend und bis zu den Ohren grinsend, die Treppe wieder runter. ');
									if ($Char->turns>0)
									{
										output('`[Du fühlst dich ausgelaugt! ');
										$Char->turns = max(0,$Char->turns-1);
									}
									//addnews("`@Es wurde beobachtet, wie ".$Char->name."`@ und `5Violet`@ gemeinsam die Treppen in der Schenke nach oben gingen.");
									if ($Char->charisma==4294967295 && e_rand(1,3)==2)
									{
										$sql = 'SELECT acctid,name FROM accounts WHERE acctid='.$Char->marriedto;
										$result = db_query($sql);
										$row = db_fetch_assoc($result);
										$partner=$row['name'];
										addnews('`$'.$partner.'`$ hat '.$Char->name.'`$ wegen eines Seitensprungs mit `5Violet`$ verlassen!');
										output('`n`$Das war zu viel für '.$partner.'`$! Sie reicht die Scheidung ein. Die Hälfte deines Goldes auf der Bank wird ihr zugesprochen. Ab sofort bist du wieder solo!');
										$Char->charisma=0;
										$Char->marriedto=0;
										if ($Char->goldinbank>1)
										{
											$getgold=round($Char->goldinbank/2);
											$Char->goldinbank-=$getgold;
										}
										else
										{
											$getgold=0;
										}
										user_update(
										array
										(
										'charisma'=>0,
										'marriedto'=>0,
										'goldinbank'=>array('sql'=>true,'value'=>'goldinbank+'.$getgold)
										),
										$row['acctid']
										);

										systemmail($row['acctid'],'`$Seitensprung!`0','`&'.$Char->name.'`6 geht mit Violet fremd!`nDas ist Grund genug für dich, die Scheidung einzureichen. Ab sofort bist du wieder solo.`nDu bekommst `^'.$getgold.' Gold`6 von seinem Vermögen auf dein Bankkonto.');
									}
									else if ($Char->charisma==4294967295)
										{
											output('`[ Deine Frau wird gar nicht begeistert sein, wenn sie davon erfährt!');
											$c--;
										}
										else
										{
											if ($c<25)
											{
												$c++;
											}
									}
								}
								else
								{
									output("`[Wie ein Wirbelwind fegst du durch die Schenke und schnappst nach `5Violet`[. Sie dreht sich um und schlägt dir ins Gesicht! `R\"Für was hältst du mich eigentlich?\" `[brüllt sie dich an! ");
									if ($c>0)
									{
										$c--;
									}
								}
								break;
							case 7:
								output("`5Violet`[ arbeitet fieberhaft, um einige Gäste der Schenke zu bedienen. Du schlenderst zu ihr rüber, nimmst ihr die Becher aus der Hand und stellst sie auf den nächsten Tisch. Unter ihrem Protest kniest du dich auf einem Knie vor sie hin und nimmst ihre Hand. Sie verstummt plötzlich. Du starrst zu ihr hoch und äußerst die Frage, von der du nie für möglich gehalten hast, dass du sie einmal stellen wirst. Sie starrt dich an und du liest sofort die Antwort aus ihrem Gesicht. ");
								if ($c>=22)
								{
									output("`n`nEs ist ein Ausdruck überschäumender Freude. `R\"Ja! Ja, ja, ja!\" `[sagt sie. Ihre letzten Bestätigungen gehen dabei in einem Sturm von Küssen auf dein Gesicht und deinen Hals unter.`n`[Violet und du heiraten in der Kirche am Ende der Strasse in einer prachtvollen Feier mit vielen rausgeputzten Mädels.");
									addnews("`&".$Char->name." und `%Violet`& haben heute feierlich den Bund der Ehe geschlossen!!!");
									if ($Char->marriedto>0 && $Char->marriedto<4294967295) //Flirtstatus des Partners löschen
									{
										$sql = 'SELECT acctid,name FROM accounts WHERE acctid='.$Char->marriedto.' AND marriedto='.$Char->acctid;
										$result = db_query($sql);
										if(db_num_rows($result)>0)
										{
											$row = db_fetch_assoc($result);
											$partner=$row['name'];

											user_update(
											array
											(
											'charisma'=>0,
											'marriedto'=>0,
											),
											$row['acctid']
											);

											if($Char->charisma>=999)
											{
												systemmail($row['acctid'],'`$Verlassen!`0','`&'.$Char->name.'`6 hat dich verlassen und gerade Violet geheiratet!`nAb sofort bist du wieder solo.');
											}
										}
									}
									$Char->marriedto=4294967295;


									$session['bufflist']['lover']=$buff;
									$Char->donation+=1;
								}
								else
								{
									output("`n`n`[Es ist ein sehr trauriger Blick. Sie sagt: `R\"Nein, ich bin noch nicht bereit für eine feste Bindung.\"`[`n`nEntmutigt und enttäuscht hast du heute keine Lust mehr auf irgendwelche Abenteuer im Wald.");
									$Char->turns=0;
								}
						}
						if ($c > $Char->charm)
						{
							output("`n`n`&Du erhältst einen Charmepunkt!");
						}
						if ($c < $Char->charm)
						{
							output('`n`n`$Du VERLIERST einen Charmepunkt!');
						}
						$Char->charm=max(0,$c);
					}
				}
				else
				{
					output("`[Du denkst, es ist besser, dein Glück mit `5Violet`[ heute nicht mehr herauszufordern.");
				}
			}
			else
			{
				//sorry, no lezbo action here.
			}

			break;
		}

		case "seth":
		{
			/*
			Wink
			Flutter Eyelashes
			Drop Hankey
			Ask the bard to buy you a drink
			Kiss the bard soundly
			Completely seduce the bard
			Marry him
			*/
			if ($_GET['subop']=="" && $_GET['flirt']=="")
			{
				output("`[Seth schaut dich erwartungsvoll an.");
				addnav("Fordere Seth auf, dich zu unterhalten","inn.php?op=seth&subop=hear");
				if ($Char->sex==1)
				{
					if ($Char->marriedto==4294967295)
					{
						addnav("Flirte mit Seth", "inn.php?op=seth&flirt=1");
					}
					else
					{
						addnav("Flirt");
						addnav("Zwinkern","inn.php?op=seth&flirt=1");
						addnav("Mit den Wimpern klimpern","inn.php?op=seth&flirt=2");
						addnav("Taschentuch fallenlassen","inn.php?op=seth&flirt=3");
						addnav("Frage ihn nach einem Drink","inn.php?op=seth&flirt=4");
						addnav("Küsse ihn geräuschvoll","inn.php?op=seth&flirt=5");
						addnav("Den Barden komplett verführen","inn.php?op=seth&flirt=6",false,false,false,false,$Char->charisma>=999?'Du weißt, dass dich dein Partner sehen könnte?':'');
						if ($Char->charisma!=4294967295)
						{
							addnav("Heirate ihn","inn.php?op=seth&flirt=7");
						}
					}
				}
				else
				{
					addnav("Frage Seth nach seiner Meinung über dein(e/n) ".$Char->armor,"inn.php?op=seth&act=armor");
				}
			}
			if ($_GET['act']=="armor")
			{
				$charm = $Char->charm+e_rand(-1,1);
				output(" `[Seth schaut dich ernst von oben bis unten an und macht sich ein Bild von deinem Äußeren. So viele Gäste wie hier täglich ein- und ausgehen, muss er sich am besten mit der männlichen Konkurrenz auskennen.");
				output("Deshalb kann auch nur er dir eine ehrliche Einschätzung geben. Schließlich kommt er zu einem Schluss und sagt:");
				switch ($charm)
				{
					case -3:
					case -2:
					case -1:
					case 0:
						output("`F\"Du machst mich glücklich, dass ich nicht schwul bin!\"");
						break;
					case 1:
					case 2:
					case 3:
						output("`F\"Ich habe einige hübsche Männer in meinem Leben gesehen, aber ich fürchte du bist keiner von ihnen.\"");
						break;
					case 4:
					case 5:
					case 6:
						output("`F\"Ich habe schon schlimmeres gesehen, aber nur beim Verfolgen eines Pferdes.\"");
						break;
					case 7:
					case 8:
					case 9:
						output("`F\"Du bist ziemlicher Durchschnitt, mein Freund.\"");
						break;
					case 10:
					case 11:
					case 12:
						output("`F\"Du bist schon etwas zum Anschauen, aber lass dir das nicht zu sehr zu Kopfe steigen, ja?\"");
						break;
					case 13:
					case 14:
					case 15:
						output("`F\"Du siehst schon ein bisschen besser aus als der Durchschnitt!\"");
						break;
					case 16:
					case 17:
					case 18:
						output("`F\"Nur wenige Frauen könnten dir widerstehen!\"");
						break;
					case 19:
					case 20:
					case 21:
					case 22:
						output("`F\"Willst du mich mit dieser Frage neidisch machen? Oder mich einfach nur ärgern?\"");
						break;
					case 23:
					case 24:
					case 25:
						output("`F\"Ich bin von deiner Schönheit geblendet.\"");
						break;
					case 26:
					case 27:
					case 28:
					case 29:
					case 30:
						output("`F\"Ich hasse dich. Warum? Weil du einfach der schönste Mann aller Zeiten bist!\"");
						break;
					default:
						output("`F\"Vielleicht solltest du langsam etwas gegen deine überirdische Schönheit tun. Du bist unerreichbar!\"");
				}
			}
			if ($_GET['subop']=="hear")
			{

				$rowe = user_get_aei('seenbard');

				if ($rowe['seenbard'])
				{
					output("`[Seth`0 räuspert sich und trinkt einen Schluck Wasser. `F\"Tut mir Leid, mein Hals ist einfach zu trocken.\"`[");
					// addnav("Return to the inn","inn.php");
				}
				else
				{
					user_set_aei(array('seenbard'=>1));

					$rnd = e_rand(0,18);
					output("`[Seth räuspert sich und fängt an:`n`n");

					switch ($rnd)
					{
						case 0:
							output("\"`@Grüner Drache`F ist grün.`n`@Grüner Drache`F ist wild.`n`@Grünen Drachen`F wünsch ich mir gekillt.\" ");
							output("`n`n`[Du erhältst ZWEI zusätzliche Waldkämpfe für heute!");
							$Char->turns+=2;
							break;
						case 1:
							output("`F\"Mireraband, ich spotte euch und spuck auf euren Fuß.`nDenn er verströmt fauligen Gestank mehr als er muss!\" ");
							output("`n`n`[Du fühlst dich erheitert und bekommst einen extra Waldkampf.");
							$Char->turns++;
							break;
						case 2:

							output("`F\"Membrain Mann Membrain Mann.`nMembrain Mann hasst ".$Char->name."`F Mann.`nSie haben einen Kampf, Mambrain gewinnt.`nMembrain Mann.\" ");
							output("`n`n`[Du bist dir nicht ganz sicher, was du davon halten sollst... du gehst lieber wieder weg und denkst, es ist besser, Seth wieder zu besuchen, wenn er sich besser fühlt. ");
							output("`[Nach einer kurzen Verschnaufpause könntest du wieder ein paar böse Jungs verprügeln.");
							$Char->turns++;
							break;
						case 3:
							output("`F\"Für eine Geschichte versammelt euch hier`neine Geschichte so schrecklich und hart`nüber Cedrik und sein gepanschtes Bier`nund wie sehr er ihn hasst, mich, den Bard'!\" ");
							output("`n`n`[Du stellst fest, dass er Recht hat, Cedriks Bier ist wirklich eklig. Das dürfte der Grund dafür sein, warum die meisten Gäste sein Ale bevorzugen. Du kannst der Geschichte von Seth nicht wirklich etwas abgewinnnen, aber du findest dafür etwas Gold auf dem Boden!");
							$gain = e_rand(10,50);
							$Char->gold+=$gain;
							//debuglog("found $gain gold near Seth");
							break;
						case 4:
							output("`F\"Der große grüne Drache hatte eine Gruppe Zwerge entdeckt und sie *schlurps* einfach aufgefuttert. Sein Kommentar später war: 'Die schmecken ja toll... aber... kleiner sollten sie wirklich nicht sein!'\" ");
							if ($Char->race=='zwg')
							{
								output("`[Als Zwerg kannst du darüber nicht lachen. Mit grimmigem Gesichtsausdruck, der auch Seths Lachen zu ersticken scheint, schlägst du ihn zu Boden. Du bist so wütend, dass dich heute wohl nichts mehr erschrecken kann.");
							}
							else
							{
								output("`n`n`[Mit einem guten, herzlichen Kichern in deiner Seele rückst du wieder aus, bereit für was auch immer da kommen mag!");
							}
							$Char->hitpoints=round($Char->maxhitpoints*1.2,0);
							break;
						case 5:
							output("`F\"Hört gut zu und nehmt es euch zu Herzen: Mit jeder Sekunde rücken wir dem Tod etwas näher. *zwinker*\" ");
							output("`n`n`[Deprimiert wendest du dich ab... und verlierst einen Waldkampf!");
							$Char->turns = max(0,$Char->turns-1);
							break;
						case 6:

							output("`F\"Ich liebe Thorim, die Waffen von Thorim, ich liebe Thorim, die Waffen von Thorim, ich liebe Thorim, die Waffen von Thorim, nichts tötet so gut wie die WAFFEN von ... Thorim!\" ");
							output("`n`n`[Du denkst, Seth ist ganz in Ordnung... jetzt willst du los, um irgendwas zu töten. Aus irgendeinem Grund denkst du an Bienen und Fisch.");
							$Char->turns++;
							break;
						case 7:

							output("`[Seth richtet sich auf und scheint sich auf etwas eindrucksvolles vorzubereiten. Dann rülpst er dir laut ins Gesicht. `F\"War das unterhaltsam genug?\"");
							output("`n`n`[Der Gestank nach angedautem Ale ist überwältigend. Dir wird etwas übel und du verlierst ein paar Lebenspunkte.");
							$Char->hitpoints -= round($Char->maxhitpoints * 0.1,0);
							if ($Char->hitpoints<=0)
							{
								$Char->hitpoints=1;
							}
							//$Char->donation+=1;
							break;
						case 8:
							if ($Char->gold >= 5)
							{
								output("`F\"Welches Geräusch macht es, wenn man mit einer Hand klatscht?\"`[, fragt Seth. Während du über diese Scherzfrage nachgrübelst, \"befreit\" Seth eine kleine Unterhaltungsgebühr aus deinem Goldsäckchen.");
								output("`n`n`[Du verlierst 5 Gold!");
								$Char->gold-=5;
								//debuglog("lost 5 gold to Seth");
							}
							else
							{
								output("`F\"Welches Geräusch macht es, wenn man mit einer Hand klatscht?\"`[, fragt Seth. Während du über diese Scherzfrage nachgrübelst, versucht Seth eine kleine Unterhaltungsgebühr aus deinem Goldsäckchen zu befreien, findet aber nicht, was er sich erhofft hat.");
								//$Char->donation+=1;
							}
							break;
						case 9:
							output("`F\"Welcher Fuss muss immer zittern?`n`nDer Hasenfuss.\" ");
							output("`n`n`[Du gröhlst und Seth lacht herzlich. Kopfschüttelnd bemerkst du einen Edelstein im Staub.");
							$Char->gems++;
							//debuglog("got 1 gem from Seth");
							break;
						case 10:
							output("`[Seth spielt eine sanfte, aber mitreißende Melodie.");

							output("`n`n`[Du fühlst dich entspannt und erholt und deine Wunden scheinen sich zu schließen.");
							if ($Char->hitpoints<$Char->maxhitpoints)
							{
								$Char->hitpoints=$Char->maxhitpoints;
							}
							break;
						case 11:
							output("`[Seth spielt dir ein düsteres Klagelied vor.");

							output("`n`n`[Deine Stimmung fällt und du wirst heute nicht mehr so viele Bösewichte erschlagen.");
							$Char->turns = max(0,$Char->turns-1);
							break;
						case 12:

							output("`F\"Die Ameisen marschieren in Einerreihen, Hurra, Hurra!`nDie Ameisen marschieren in Einerreihen, Hurra, Hurra!`nDie Ameisen marschieren in Einerreihen, Hurra, Hurra, und die kleinste stoppt und nuckelt am Daumen.`nUnd sie alle marschieren in den Bau um vorm Regen abzuhaun.`nBumm, bumm, bumm.`nDie Ameisen marschieren in Zweierreihen, Hurra, Hurra! ....\" ");
							output("`n`n`[Seth singt immer weiter, aber du hast nicht den Wunsch herauszufinden, wie weit Seth zählen kann, deswegen verschwindest du leise. Nach dieser kurzen Rast fühlst du dich erholt.");
							$Char->hitpoints=$Char->maxhitpoints;
							break;
						case 13:
							output("`F\"Es war ein mal eine Dame von der Venus, ihr Körper war geformt wie ein ...\" ");
							if ($Char->sex==1)
							{
								output("`n`n`[Seth wird durch einen barschen Schlag ins Gesicht unterbrochen. Du fühlst dich rauflustig und gewinnst einen Waldkampf dazu.");
							}
							else
							{
								output("`n`n`[Seth wird durch dein plötzliches lautes Gelächter unterbrochen, das du ausstößt, ohne seinen Reim vollständig gehört haben zu müssen. So angespornt erhältst du einen zusätzlichen Waldkampf.");
							}
							$Char->turns++;
							break;
						case 14:

							output("`[Seth spielt einen stürmischen Schlachtruf für dich, der den Kriegergeist in dir erweckt.");
							output("`n`n`[Du bekommst einen zusätzlichen Waldkampf!");
							$Char->turns++;
							break;
						case 15:
							output("`[Seth scheint in Gedanken völlig woanders zu sein ... bei deinen ... Augen.");
							if ($Char->sex==1)
							{
								output("`n`n`[Du erhältst einen Charmepunkt!");
								$Char->charm++;
							}
							else
							{
								output("`n`n`[Aufgebracht stürmst du aus der Bar! In deiner Wut bekommst du einen Waldkampf dazu.");
								$Char->turns++;
							}
							break;
						case 16:

							output("`[Seth fängt an zu spielen, aber eine Saite seiner Laute reißt plötzlich und schlägt dir flach ins Auge.`n`n`F\"Uuuups! Vorsicht, du wirst dir noch deine Augen ausschießen, Mensch!\"");
							output("`n`n`[Du verlierst einige Lebenspunkte!");
							$Char->hitpoints-=round($Char->maxhitpoints*0.1,0);
							if ($Char->hitpoints<1)
							{
								$Char->hitpoints=1;
							}
							break;
						case 17:
							output("`[Er fängt an zu spielen, als ein rauflustiger Gast vorbeistolpert und Bier auf dich verschüttet. Du verpasst die ganze Vorstellung, während du das Gesöff von deine(r/m) ".$Char->armor."`[ putzt.");
							//$Char->donation+=1;
							break;
						case 18:
							output("`[Seth starrt dich gedankenvoll an. Offensichtlich komponiert er gerade ein episches Gedicht...`n`n`F\"H-Ä-S-S-L-I-C-H, du kannst dich nicht verstecken -- Du bist hässlich, yeah, yeah, so hässlich!\" ");
							$Char->charm = max(0,$Char->charm-1);
							if ($Char->charm<=0)
							{
								output("`n`n`[Wenn du einen Charmepunkt hättest, hättest du ihn jetzt verloren. Aber so reißt Seth nur eine Saite seiner Laute.");
							}
							else
							{
								output("`n`n`[Deprimiert verlierst du einen Charmepunkt.");
							}
							break;
					}
				}
			}
			if ($Char->sex==1 && $_GET['flirt']!="")
			{
				//$Char->seenlover=0;
				if ($Char->seenlover==0)
				{
					if ($Char->marriedto==4294967295)
					{
						if (e_rand(1,4)==1)
						{
							output("`[Du gehst rüber zu Seth, um ihn zu knuddeln und mit Küssen zu überhäufen, aber er brummelt nur etwas ");
							switch (e_rand(1,4))
							{
								case 1:
									output("`[darüber, dass er damit beschäftigt ist, seine Laute zu stimmen. ");
									break;
								case 2:
									output("`[wie \"um diese Zeit?\" ");
									break;
								case 3:
									output("`[wie \"leicht erkältet...  *hust hust* ... ");
									break;
								case 4:
									output("`[darüber, dass er sich ein Bier holen will. ");
									break;
							}
							output("`[Nach so einem Kommentar lässt du ihn stehen und haust ab!");
							$Char->charm = max(0,$Char->charm-1);
							output("`n`n`&Du VERLIERST einen Charmepunkt!");
						}
						else
						{
							output("`[Du und Seth nehmt euch etwas Zeit füreinander und du verlässt die Schenke mit einem zuversichtlichen Strahlen!");
							$session['bufflist']['lover']=$buff;
							$Char->charm++;
							output("`n`n`&Du erhältst einen Charmepunkt!");
						}
						$Char->seenlover=1;
					}
					else if ($_GET['flirt']=="")
						{
						}
						else
						{
							$c = $Char->charm;
							$Char->seenlover=1;
							switch ($_GET['flirt'])
							{
								case 1:
									if (e_rand($c,2)>=2)
									{
										output("`[Seth grinst ein breites Grinsen. Hach, ist dieses Grübchen in seinem Kinn nicht süß??");
										if ($c<4)
										{
											$c++;
										}
									}
									else
									{
										output("`[Seth hebt eine Augenbraue und fragt dich, ob du etwas im Auge hast.");
									}
									break;
								case 2:
									if (e_rand($c,4)>=4)
									{
										output("`[Seth lächelt dich an und sagt: `F\"Du hast wunderschöne Augen\"");
										if ($c<7)
										{
											$c++;
										}
								}
								else
								{
									output("S`[eth lächelt und winkt ... zu der Person hinter dir.");
								}
								break;
							case 3:
								if (e_rand($c,7)>=7)
								{
									output("`[Während Seth sich bückt, um dir dein Taschentuch zurückzugeben, bewunderst du seinen knackigen Hintern.");
									if ($Char->charisma==4294967295)
									{
										output(" `[Dein Mann wird gar nicht begeistert sein, wenn er davon erfährt!");
										$c--;
									}
									else
									{
										if ($c<11)
										{
											$c++;
										}
									}
								}
								else
								{
									output("`[Seth hebt das Taschentuch auf, putzt sich damit die Nase und gibt es dir zurück.");
								}
								break;
							case 4:
								if (e_rand($c,11)>=11)
								{
									output("`[Seth platziert seinen Arm um deine Hüfte, geleitet dich an die Bar und kauft dir eines der köstlichsten Getränke, die es in der Schenke gibt.");
									if ($Char->charisma==4294967295)
									{
										output(" `[Dein Mann wird gar nicht begeistert sein, wenn er davon erfährt!");
										$c--;
									}
									else
									{
										if ($c<14)
										{
											$c++;
										}
									}
								}
								else
								{
									output("`[Seth bedauert: `F\"Tut mir Leid, meine Dame, ich habe kein Geld zu verschenken.\"`[ Dabei stülpt er seine mottenzerfressenen Taschen nach außen.");
									if ($c>0 && $c<10)
									{
										$c--;
									}
								}
								break;
							case 5:
								if (e_rand($c,14)>=14)
								{
									output("`[Du läufst auf Seth zu, packst ihn am Hemd, stellst ihn auf die Beine und drückst ihm einen kräftigen, langen Kuss direkt auf seine attraktiven Lippen. Seth bricht fast zusammen - mit zerzausten Haaren und ziemlich atemlos.");
									if ($Char->charisma==4294967295)
									{
										$c--;
									}
									else
									{
										if ($c<18)
										{
											$c++;
										}
									}
								}
								else
								{
									output("`[Du bückst dich zu Seth herunter, um ihn auf die Lippen zu küssen, doch als sich eure Lippen gerade berühren wollen, bückt sich Seth, um sich den Schuh zuzubinden.");
									// $Char->hitpoints=1;
									//why the heck was this here???
									if ($c>0 && $c<13)
									{
										$c--;
									}
								}
								if ($Char->charisma==4294967295)
								{
									output(" `[Dein Mann wird gar nicht begeistert sein, wenn er davon erfährt!");
								}
								break;
							case 6:
								if (e_rand($c,18)>=18)
								{
									output('`[Du stehst auf der ersten Treppenstufe und gibst Seth ein \'komm hierher\' Zeichen. Er folgt dir wie ein Schoßhündchen. ');
									if ($Char->turns>0)
									{
										output('`[Du fühlst dich ausgelaugt! ');
										$Char->turns = max(0,$Char->turns-2);
									}
									//addnews("`@Es wurde beobachtet, wie ".$Char->name."`@ und `^Seth`@ gemeinsam die Treppen in der Schenke nach oben gingen.");
									if ($Char->charisma==4294967295 && e_rand(1,3)==2)
									{
										$sql = 'SELECT acctid,name FROM accounts WHERE acctid='.$Char->marriedto;
										$result = db_query($sql);
										$row = db_fetch_assoc($result);
										$partner=$row['name'];
										addnews('`$'.$partner.'`$ hat '.$Char->name.'`$ wegen eines Seitensprungs mit `^Seth`$ verlassen!');
										output('`n`$Das war zu viel für '.$partner.'`$! Er reicht die Scheidung ein. Die Hälfte deines Goldes auf der Bank wird ihm zugesprochen. Ab sofort bist du wieder solo!');
										$Char->charisma=0;
										$Char->marriedto=0;
										if ($Char->goldinbank>1)
										{
											$getgold=round($Char->goldinbank/2);
											$Char->goldinbank-=$getgold;
										}
										else
										{
											$getgold=0;
										}

										user_update(
										array
										(
										'charisma'=>0,
										'marriedto'=>0,
										'goldinbank'=>array('sql'=>true,'value'=>'goldinbank+'.$getgold)
										),
										$row['acctid']
										);

										systemmail($row['acctid'],'`$Seitensprung!`0','`&'.$Char->name.'
										`6 geht mit Seth fremd!`nDas ist Grund genug für dich, die Scheidung einzureichen. Ab sofort bist du wieder solo.`nDu bekommst `^'.$getgold.' Gold`6 von ihrem Vermögen auf dein Bankkonto.');
									}
									else if ($Char->charisma==4294967295)
										{
											output(' `[Dein Mann wird gar nicht begeistert sein, wenn er davon erfährt!');
											$c--;
										}
										else
										{
											if ($c<25)
											{
												$c++;
											}
									}
								}
								else
								{
									output("`F\"Tut mir Leid meine Dame, aber ich habe in 5 Minuten einen Auftritt.\"");
									if ($c>0)
									{
										$c--;
									}
								}
								break;
							case 7:
								output("`[Du gehst zu Seth und verlangst ohne Umschweife von ihm, daß er dich heiratet.`n`nEr schaut dich ein paar Sekunden lang an.`n`n");
								if ($c>=22)
								{
									output("`F\"Natürlich, meine Liebe!\"`[, sagt er. Die nächsten Wochen bist du damit beschäftigt, eine gigantische Hochzeit vorzubereiten, die natürlich Seth zahlt und danach geht es in den dunklen Wald in die Flitterwochen.");
									addnews("`&".$Char->name." und `^Seth`& haben heute feierlich den Bund der Ehe geschlossen!!!");
									if ($Char->marriedto>0 && $Char->marriedto<4294967295) //Flirtstatus des Partners löschen
									{
										$sql = 'SELECT acctid,name FROM accounts WHERE acctid='.$Char->marriedto.' AND marriedto='.$Char->acctid;
										$result = db_query($sql);
										if(db_num_rows($result)>0)
										{
											$row = db_fetch_assoc($result);
											$partner=$row['name'];

											user_update(
											array
											(
											'charisma'=>0,
											'marriedto'=>0,
											),
											$row['acctid']
											);

											if($Char->charisma>=999)
											{
												systemmail($row['acctid'],'`$Verlassen!`0','`&'.$Char->name.'`6 hat dich verlassen und gerade Seth geheiratet!`nAb sofort bist du wieder solo.');
											}
										}
									}
									$Char->marriedto=4294967295;


									//int max.
									$session['bufflist']['lover']=$buff;
									$Char->donation+=1;
								}
								else
								{
									output("`[Seth sagt: `F\"Es tut mir Leid, offensichtlich habe ich einen falschen Eindruck erweckt. Ich denke, wir sollten einfach nur Freunde sein.\"`[ Deprimiert hast du heute kein Verlangen mehr danach, nochmal im Wald kämpfen zu gehen.");
									$Char->turns=0;
								}
								break;
						}
						if ($c > $Char->charm)
						{
							output("`n`n`&Du bekommst einen Charmepunkt!");
						}
						if ($c < $Char->charm)
						{
							output('`n`n`&Du VERLIERST einen Charmepunkt!');
						}
						$Char->charm=$c;
					}
				}
				else
				{
					output("`[Du denkst, es ist besser, dein Glück mit `9Seth`0 heute nicht mehr herauszufordern.");
				}
			}
			else
			{
				//sorry, no lezbo action here.
			}
			break;
		}

		case 'converse':
		{
			output('`(D`)i`7e`_ z`[ahlreichen Tische sind weiträumig im Inneren des Schankraumes verteilt und doch ist keiner zu weit entfernt, um Cedrik oder Violet jeder Zeit ein Zeichen geben zu können, dass sie etwas bringen sollen.
			Die einzelnen Plätze sind unterschiedlich beleuchtet, ganz nach den Vorzügen der Gäste wählbar und auch etwas abseits der anderen Sitzgelegenheiten finden sich noch einige vereinzelte.
			Die Stühle sind aus massivem Holz, kaum beschmückt und auch nicht mit Sitzkissen gepolstert.
			Hier kann man sich also über Gott und die Welt unterhalten, einfach nur einen Krug Bier trinken oder Geheimnisse tauschen.
			Denn eines ist sicher - weder Schankmaid, noch Barde oder Wirt sind derartig indiskret, den Themen der Gäste zu lauschen, die diese hier bespre`_c`7h`)e`(n.`n`n');
			viewcommentary('inn','`[Zur Unterhaltung beitragen:',20,'sagt',false,true,false,false,true);
			break;
		}

		case 'bartender':
		{
			$sqla = 'SELECT gotfreeale,beerspent FROM account_extra_info WHERE acctid='.$Char->acctid;
			$resa = db_query($sqla);
			$rowa = db_fetch_assoc($resa);
			$paidales=intval(getsetting('paidales',0));

			if (($paidales<=1 || $rowa['gotfreeale']>=2) && ($Char->marks<31))
			{
				$alecost = $Char->level*10;
			}
			else
			{
				$alecost = 0;
			}

			if ($_GET['act']=='') //Mit dem Wirt sprechen Startbild
			{
				output('`(C`)e`7d`_r`[ik, der Wirt, beobachtet dich genau, als du dich der Theke näherst und doch wirkt er sehr neutral auf dich - nicht freundlich aber auch nicht ablehnend.
				Er weiß genau, was für finstere Gestalten sich in dieser Stadt herumtreiben und so muss er selbst immer auf der Hut sein und sich ein Bild von seinen Gästen machen, bevor er mit ihnen spricht.
				Nebenbei poliert er ein Glas und hält es prüfend ins Licht, das durch die Tür hereinscheint, als ein Gast die Schenke verlässt. `s"Wie kann ich helfen?"`[, fragt er dich to`_n`7l`)o`(s.');

				if ($Char->marks>=31)
				{
					output('`n`s"Achja, Auserwählte trinken hier aufs Haus."`[, fügt er kurz an.');
				}
				addnav('Schwarzes Brett','inn.php?op=msgboard');
				addnav('Bestechen','inn.php?op=bartender&act=bribe');
				if ($Char->profession > 0 && $Char->profession <= 2)
				{
					addnav('Razzia','inn.php?op=bartender&act=listupstairs');
				}
				addnav('Tränke','inn.php?op=bartender&act=gems');
				if ($paidales<=1)
				{
					addnav('Ale (`^'.$alecost.'`0 Gold)','inn.php?op=bartender&act=ale');
					addnav('Runde schmeißen','inn.php?op=bartender&act=schmeiss');
				}
				else
				{
					addnav('Ale (`^'.($rowa['gotfreeale']>=2?$alecost.'`0 Gold':'schon bezahlt`0').')','inn.php?op=bartender&act=ale');
					output('`n`[Es stehen noch '.($paidales-1).' frisch gefüllte, von '.getsetting('paidale_by','Seth').'`[ bezahlte Krüge mit Ale vor Cedrik.');
					if (($rowa['gotfreeale']>=2) && ($Char->marks<31))
					{
						output(' `[Leider hattest du dein Frei-Ale für heute schon und du wirst selbst bezahlen müssen.');
					}
				}
				addnav('Kaufe ein kleines Bierfass','inn.php?op=bartender&act=buyale');
				addnav('Spucke Cedrik ins Ale','inn_cedrick_brawl.php?op=boxing');
				if(item_count('i.tpl_id="common_root" AND i.owner='.$Char->acctid)>0) addnav('Nach Malzbier befragen','inn.php?op=bartender&act=rootbeer');
				$drunkenness = array(-1=>'absolut nüchtern',
				0=>'ziemlich nüchtern',
				1=>'kaum berauscht',
				2=>'leicht berauscht',
				3=>'angetrunken',
				4=>'leicht betrunken',
				5=>'betrunken',
				6=>'ordentlich betrunken',
				7=>'besoffen',
				8=>'richtig zugedröhnt',
				9=>'fast bewusstlos'
				);
				$drunk = round($Char->drunkenness/10-.5,0);
				if ($drunkenness[$drunk])
				{
					output('`n`n`}Du fühlst dich '.$drunkenness[$drunk].'`n`n');
				}
				else
				{
					output('`n`n`{Du fühlst dich nicht mehr.`n`n');
				}
			}
			else if ($_GET['act']=='rootbeer') //Tränke
			{
				switch($_GET['subact'])
				{
					default:
					case '':
					{
						$str_output .= '`[Du hast eine deiner Wurzeln in der Hand und fragst Cedrick, ob er in der Lage wäre daraus etwas herzustellen. Cedrick überlegt kurz, schaut erst dich an, dann die Wurzel, dann zurück zu dir und antwortet "`sDa sollte sich was machen lassen. Wenn ich diese Wurzel in warmer Zuckermaische einlege, dann aufkoche und mit Speisestärke bedecke...Joa, ich denke daraus kann man was machen. Könnte vielleicht sogar schmecken. Das macht für dich dann...sagen wir 1000 Goldmünzen.`[" Du schluckst kurz bei diesem Preis, aber was Cedrick macht hat Hand und Fuß, das weißt du. Also? Gibst du ihm eine Wurzel?`0';
						addnav('Nein, doch nicht','inn.php?op=bartender');
						if($Char->gold < 1000)
						{
							$str_output .= '`n`n`[Oh, ich vergaß! Du hast ja gar nicht genug Geld bei dir. Wie schade.`0';
						}
						else
						{
							$arr_roots = item_list_get('i.tpl_id="common_root" AND i.owner='.$Char->acctid,'',false,'i.name,i.id',true);
							foreach($arr_roots as $arr_root)
							{
								addnav($arr_root['name'].' geben','inn.php?op=bartender&act=rootbeer&subact=make&item='.$arr_root['id']);
							}
						}
						break;
					}
					case 'make':
					{
						$arr_root = item_get((int)$_GET['item']);
						$str_output .= '`["`sAh, das sieht nach '.$arr_root['name'].' aus,`[", sagt Cedrick und macht sich sofort an die Arbeit deine Wurzel zu veredeln. Nach kurzer Zeit ist er fertig und du hälst eine Flasche feinsten Wurzelgebräus in der Hand. Lecker! Zum Trinken beinahe zu schade.`0';
						$Char->gold -= 1000;
						item_overwrite($arr_root['id'],'common_rootbeer');
						addnav('Danke, das wars','inn.php?op=bartender');
						if(item_count('i.tpl_id="common_root" AND i.owner='.$Char->acctid)>0)
						{
							addnav('Noch eines erstellen?','inn.php?op=bartender&act=rootbeer');
						}
						break;
					}
				}
			}
			else if ($_GET['act']=='gems') //Tränke
			{
					if ($_POST['gemcount']=='' || !isset($_POST['wish']) )
					{
						output('`s"Du hast Edelsteine, oder?"`[, fragt dich Cedrik. `s"Nun, für `^zwei Edelsteine`s werd ich dir nen magischen Trank machen!"
						`n`n`[Wieviele Edelsteine gibst du ihm?
						`0<form action="inn.php?op=bartender&amp;act=gems" method="POST">
						<input name="gemcount" value="0" size="3" maxlength="4">
						<input type="submit" class="button" value="Weggeben">
						`n`[Und was willst du dafür?`0
						`n<input type="radio" name="wish" value="1"> Charme
						`n<input type="radio" name="wish" value="2"> Lebenskraft(`^'.get_lp_gems().' `0Edelsteine)
						`n<input type="radio" name="wish" value="3"> Gesundheit
						`n<input type="radio" name="wish" value="4"> Vergessen
						'.(getsetting('race_change_allowed',0)?'`n<input type="radio" name="wish" value="5"> Transmutation':'').'
						</form>',true);
						addnav('','inn.php?op=bartender&act=gems'); // "<input type="radio" name="wish" value="6"> Gegengift(`^1`0 Edelstein)" entfernt
					}
					else
					{
						$gemcount = abs((int)$_POST['gemcount']);
						if ($gemcount>$Char->gems)
						{
							output('`[Cedrik starrt dich an und sagt: `s"Du hast nich so viele Edelsteine, `bzieh los und besorg Dir noch welche!`b"');
						}
						else
						{
							switch ($_POST['wish'])
							{
								case 1:
									$cost = 2;
									if ($cost <= $gemcount)
									{
										$amount = floor($gemcount/$cost);
										$Char->charm+=$amount;
										$msg .= '`&Du fühlst dich charmant! `^(Du erhältst '.$amount.' Charmepunkte)';
									}
									break;

								case 2:
									$cost = get_lp_gems();
									if ($cost <= $gemcount)
									{
										$amount = floor($gemcount/$cost);
										$Char->maxhitpoints+= $amount;
										$Char->hitpoints+= $amount;
										$msg .= '`&Du fühlst dich lebhaft! `^(Deine maximale Lebensenergie erhöht sich permanent um '.$amount.')';
									}
									break;

								case 3:
									$cost = 2;
									if ($cost <= $gemcount)
									{
										$amount = floor($gemcount/$cost) * 10;
										if ($Char->hitpoints<$Char->maxhitpoints)
										{
											$Char->hitpoints=$Char->maxhitpoints;
										}
										$Char->hitpoints+=$amount;
									$msg .= '`&Du fühlst dich gesund! `^(Du erhältst vorübergehend '.$amount.' Lebenspunkte dazu)';
								}
								break;

							case 4:
								$cost = 2;
								if ($cost <= $gemcount)
								{
									$Char->specialty=0;
									$msg .= '`&Du fühlst dich völlig ziellos in deinem Leben. Du solltest eine Pause machen und einige wichtige Entscheidungen über dein Leben treffen! `^(Dein Spezialgebiet wurde zurückgesetzt)';
								}
								break;

							case 5:
								if(!getsetting('race_change_allowed',0)) {
									systemlog('Cheatversuch: Inputform manipuliert, um an Transmutationstrank zu kommen',$Char->acctid);
									exit;
								}
								$cost = 2;
								if ($cost <= $gemcount)
								{
									// Rassenboni abnehmen
									race_set_boni(true,true,$session['user']);
									$Char->race='';
									$msg .= '`&Deine Knochen werden zu Gelatine und du musst vom Effekt des Tranks ordentlich würgen! `^(Deine Rasse wurde zurückgesetzt. Du kannst morgen eine neue wählen.)';
									if (isset($session['bufflist']['transmute']))
									{
										$session['bufflist']['transmute']['rounds'] += 10;
									}
									else
									{
										$session['bufflist']['transmute']=array("name"=>"`6Transmutationskrankheit",
										"rounds"=>10,
										"wearoff"=>"Du hörst auf, deine Därme auszukotzen. Im wahrsten Sinne des Wortes.",
										"atkmod"=>0.75,
										"defmod"=>0.75,
										"roundmsg"=>"Teile deiner Haut und deiner Knochen verformen sich wie Wachs.",
										"survivenewday"=>1,
										"newdaymessage"=>"`6Durch die Auswirkungen des Transmutationstranks fühlst du dich immer noch `2krank`6.",
										"activate"=>"offense,defense"
										);
									}
								}
								break;

								/*case 6:
								// Gegengift für Giftfalle im Haus - oder eben auch nicht -> entfernt
								$cost = 1;
								$gemcount = ($gemcount >= 1 ? 1 : 0);
								if ($cost <= $gemcount)
								{
								$msg .= '`&...und fühlst dich gegen jedes Gift gewappnet.';
								$session['bufflist']['poison_potion']=array('name' => 'Gegengift',
								'rounds' => 1
								);
								}
								break;*/
						}
						// END wish

						if ($cost > $gemcount)
						{
							output("`n`n`[Er will mehr Edelsteine sehen.");
						}
						else
						{
							$msg = '`[Du platzierst '.$gemcount.' Edelsteine auf der Theke. Du trinkst den Trank, den Cedrik dir im Austausch für deine Edelsteine gegeben hat und.....`n`n'.$msg;
							output($msg);
							$rest = $gemcount % $cost;
							if ($rest)
							{
								output('`n`n`[Cedrik, der über deine absolute mathematische Unfähigkeit Bescheid weiß, gibt dir die überzähligen Edelsteine zurück.');
								$gemcount -= $rest;
							}
							$Char->gems-=$gemcount;
							if ($gemcount>10)
							{
								debuglog("Gab $gemcount Edelsteine für Tränke in der Schenke.");
							}
						}
					}
					// END Genug Edels
				}
				// END Aktion
			}
			// END act gems

			else if ($_GET['act']=='schmeiss') //Runde schmeißen Eingabeformular
			{
					$alecost = $Char->level*10;
					$maxale=min(floor($Char->gold/$alecost),getsetting('maxales',50));

					output('`[Du bist guter Laune und überlegst dir, ob du für deine Kumpels hier in der Schenke ne Runde Ale spendieren solltest.
					`n`n1 Ale kostet dich `^'.$alecost.'`[ Gold.
					`n`0<form action="inn.php?op=bartender&amp;act=schmeiss2" method="POST">
					`[Die nächsten <input name="runden" id="runden" size="3" maxlength="2" value="'.$maxale.'"> Ale gehen auf deine Rechnung.`0
					`n<input type="submit" class="button" value="Ausgeben">
					</form>
					'.focus_form_element('runden'));
					addnav('','inn.php?op=bartender&act=schmeiss2');
				}

			else if ($_GET['act']=='schmeiss2') //Runde schmeißen fertig
			{
						$alecost = $Char->level*10;
						// auch bei Auserwählten, evtl. noch andere Lösung

						$amt = abs((int)$_POST['runden']);
						$jamjam=$amt*$alecost;
						$schussel=$Char->name;
						if ($Char->gold<$jamjam)
						{
							output('`[Du stellst gerade noch rechtzeitig vor einer Blamage fest, dass du nicht genug Gold dabei hast.');
						}
						else if ($paidales>1 || $alecost==0)
							{
								output('`[Tja, der gute Wille war da, doch ein anderer war schneller als du! Enttäuscht bewegst du dich Richtung Freiale und schwörst dir, in Zukunft schneller zu sein.');
							}
							else if (abs($rowa['gotfreeale']-2)==1)
								{
									output('`[Cedrik schüttelt nur kurz den Kopf und meint nur `s"Du hast heute schonmal eine Runde spendiert. In meiner Schenke machst du niemanden zum Säufer. Alles klar?"');
								}
								else if ($amt>getsetting("maxales",50))
									{
										output('`s"Hast du sie noch alle, hier so mit deinem Gold anzugeben? Schau dich doch mal um, wieviele überhaupt da sind!"`[ Mit diesen Worten zeigt dir Cedrik einen Vogel und dreht sich gelangweilt weg. ');
									}
									else
									{
										output('`[Du sprichst mit Cedrik, dem Wirt, und schiebst ihm `^'.$jamjam.'`[ Gold rüber. Dieser nickt mit dem Kopf und grölt in die Runde `s"Die nächsten '.$amt.' Ale gehen auf '.$schussel.' `s!!".
										`n`[Ein allgemeiner Freudenschrei ist die Antwort und du bist der Held der Stunde.`n`n');

										$sql = "UPDATE account_extra_info SET beerspent=beerspent+".$amt.",gotfreeale=gotfreeale+1 WHERE acctid=".$Char->acctid."";
										db_query($sql);

										if ($amt>5)
										{
											output("`&Du erhältst einen Charmepunkt!`0");
											$Char->charm+=1;
										}
										//if ($amt>10)
										{
											$Char->donation+=1;
										}
										savesetting("paidales",$amt+1);
										savesetting('paidale_by',$Char->login);
										$Char->gold-=$jamjam;

										if ($amt > 10)
										{
											$sql = "INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'inn',".$Char->acctid.",': spendiert die nächsten `^$amt`& Ale!')";
											db_query($sql);
										}
				}
			}

			else if ($_GET['act']=='bribe') //Bestechen
			{
					$g1 = $Char->level*10;
					$g2 = $Char->level*50;
					$g3 = $Char->level*100;
					$Char->reputation--;
					if ($_GET['type']=="")
					{
						output("`[Wieviel willst du ihm anbieten?");
						addnav("1 Edelstein","inn.php?op=bartender&act=bribe&type=gem&amt=1");
						addnav("2 Edelsteine","inn.php?op=bartender&act=bribe&type=gem&amt=2");
						addnav("3 Edelsteine","inn.php?op=bartender&act=bribe&type=gem&amt=3");
						addnav("$g1 Gold","inn.php?op=bartender&act=bribe&type=gold&amt=$g1");
						addnav("$g2 Gold","inn.php?op=bartender&act=bribe&type=gold&amt=$g2");
						addnav("$g3 Gold","inn.php?op=bartender&act=bribe&type=gold&amt=$g3");
					}
					else
					{
						if ($_GET['type']=="gem")
						{
							if ($Char->gems<$_GET['amt'])
							{
								$try=false;
								output('`&Du hast keine '.$_GET['amt'].' Edelsteine!');
							}
							else
							{
								$chance = $_GET['amt']/4;
								$Char->gems-=$_GET['amt'];

								$try=true;
							}
						}
						else
						{
							if ($Char->gold<$_GET['amt'])
							{
								output('`&Du hast keine '.$_GET['amt'].' Gold!');
								$try=false;
							}
							else
							{
								$try=true;
								$chance = $_GET['amt']/($Char->level*110);
								$Char->gold-=$_GET['amt'];

							}
						}
						$chance*=100;
						if ($try)
						{
							if (e_rand(0,100)<$chance)
							{
								output('`[Cedrik lehnt sich zu dir über die Theke und fragt: `s"Was kann ich für dich tun, Kleine'.($Char->sex?'':'r').'?"');
								if (getsetting('pvp',1))
								{
									addnav('Wer schläft oben?','inn.php?op=bartender&act=listupstairs');
								}
								addnav('Farbenlehre','inn.php?op=bartender&act=colors');
								addnav('Spezialgebiet wechseln','inn.php?op=bartender&act=specialty');
							}
							else
							{
								output('`[Cedrik fängt an, die Oberfläche der Theke zu wischen, was eigentlich schon vor langer Zeit wieder einmal nötig gewesen wäre.
								Als er damit fertig ist, '.($_GET['type']=='gem'?($_GET['amt']>0?'sind deine Edelsteine':'ist dein Edelstein'):'ist dein Gold').' verschwunden.
								Du fragst wegen deinem Verlust nach, aber Cedrik starrt dich nur mit leerem Blick an.');
								addnav('Farbenlehre','inn.php?op=bartender&act=colors');
							}
						}
						else
						{
							output('`n`n`[Cedrik steht nur da und schaut dich ausdruckslos an.');
						}
				}
			}

			else if ($_GET['act']=='ale') //Ale
			{
					output('`[Du schlägst mit der Faust auf die Bar und verlangst ein Ale');
					if ($Char->drunkenness>66)
					{
						output('`[, aber Cedrik fährt unbekümmert damit fort, das Glas weiter zu polieren, an dem er gerade arbeitet. `s"Du hast genug gehabt '.($Char->sex?'Mädl':'Bursche').'." ');
					}
					elseif ($Char->gold>=$alecost)
					{
						$Char->drunkenness+=33;
						$Char->gold-=$alecost;
						if ($paidales>1 && $rowa['gotfreeale']<2)
						{
							savesetting('paidales',$paidales-1);
							$sql = 'UPDATE account_extra_info SET gotfreeale=gotfreeale+2 WHERE acctid='.$Char->acctid;
							db_query($sql);

						}
						output('`[. Cedrik nimmt ein Glas und schenkt schäumendes Ale aus einem angezapften Fass hinter ihm ein. Er gibt dem Glas Schwung und es rutscht über die Theke, wo du es mit deinen Kriegerreflexen fängst.
					`n`nDu drehst dich um, trinkst dieses herzhafte Gesöff auf ex und gibst '.($Char->sex?'Seth':'Violet').' ein Lächeln mit deinem Ale-Schaum-Oberlippenbart.`n`n');
					switch (e_rand(1,3))
					{
						case 1:
						case 2:
							output('`&Du fühlst dich gesund!');
							$Char->hitpoints+=round($Char->maxhitpoints*0.1,0);
							break;
						case 3:
							output('`&Du fühlst dich lebhaft!');
							$Char->turns++;
					}
					if ($Char->drunkenness>33)
					{
						$Char->reputation--;
					}
					$session['bufflist']['101'] = array('name'=>'`#Rausch','rounds'=>10,'wearoff'=>'Dein Rausch verschwindet.','atkmod'=>1.25,'roundmsg'=>'Du hast einen ordentlichen Rausch am laufen.','activate'=>'offense');
				}
				else
				{
					output('`[. Du hast aber nicht genug Gold bei dir. Wie kannst du ein Ale haben wollen, wenn du das Gold dafür nicht hast!?!');
				}
			}

			else if ($_GET['act']=='listupstairs') //Wer schläft oben
			{

					require_once(LIB_PATH.'dg_funcs.lib.php');

					addnav('Liste aktualisieren','inn.php?op=bartender&act=listupstairs');
					$str_out='`[Cedrik legt einen Satz Schlüssel vor dich auf die Theke und sagt dir, welcher Schlüssel wessen Zimmer öffnet. Du hast die Wahl. Du könntest bei jedem reinschlüpfen und angreifen.`0';
					if ($Char->profession == PROF_TEMPLE_SERVANT )
					{
						$str_out.='`n`[Als Tempeldiener kehrst du jedoch besser gleich wieder um..';
					}
					else
					{
						$pvptime = getsetting("pvptimeout",600);
						$pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
						pvpwarning();
						if ($Char->pvpflag==PVP_IMMU)
						{
							$str_out.='`n`&(Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!)`n`n';
						}
						$days = getsetting('pvpimmunity', 5);
						$exp = getsetting('pvpminexp', 1500);
						if (($Char->profession==0) || ($Char->profession>2)) //keine Stadtwache
						{

							// Hot Items: Immu spielt bei Stadtwachen sowieso keine Rolle
							$res = item_list_get(' hot_item>0 AND owner>0 AND deposit1=0 ','',true,'owner');
							if(db_num_rows($res)) {
								$arr_hot_owners = db_create_list($res,'owner');
							}
							else {
								$arr_hot_owners = array();
							}

							$sql = "SELECT
							a.name,
							alive,
							location,
							sex,
							level,
							laston,
							loggedin,
							login,
							pvpflag,
							acctid,
							g.name AS guildname,
							a.guildid,
							a.guildfunc
							FROM accounts a
							LEFT JOIN dg_guilds g ON (g.guildid=a.guildid AND guildfunc!=".DG_FUNC_APPLICANT.")
							WHERE
							(locked=0) AND
							(level >= ".($Char->level-1)." AND level <= ".($Char->level+2).") AND
							(alive=1 AND location=".USER_LOC_INN.") AND
							(age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
							!(".user_get_online(0,0,true).") AND
							(acctid <> ".$Char->acctid.") AND
							(dragonkills > ".($Char->dragonkills-5).")
							ORDER BY level DESC";
						}
						else //Stadtwache
						{

							$sql = "SELECT
							a.name,
							alive,
							location,
							sex,
							level,
							laston,
							loggedin,
							login,
							pvpflag,
							a.acctid,
							g.name AS guildname,
							a.guildid,
							a.guildfunc,
							aei.sentence
							FROM accounts a
							LEFT JOIN dg_guilds g ON (g.guildid=a.guildid AND guildfunc!=".DG_FUNC_APPLICANT.")
							LEFT JOIN account_extra_info aei ON (a.acctid=aei.acctid)
							WHERE
							(locked=0) AND
							(level >= ".($Char->level-1)." AND level <= ".($Char->level+2).") AND
							(alive=1 AND location=".USER_LOC_INN.") AND
							(age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
							!(".user_get_online(0,0,true).") AND
							(a.acctid <> ".$Char->acctid.")
							ORDER BY level DESC";
						}

						$result = db_query($sql);
						if ($Char->guildid)
						{
							$guild = &dg_load_guild($Char->guildid,array('treaties'));
						}

						$str_out.='`n`n`c<table bgcolor="#999999" border="0" cellpadding="3" cellspacing="0">
					<tr class="trhead">
					<th>Name</th>
					<th>Level</th>
					<th>Gilde</th>
					</tr>';

					$count = db_num_rows($result);

					if ($count == 0)
					{
						$str_out.='<tr><td colspan="4" class="trlight">`i`[Leider erblickst du niemanden, der für dich in Frage käme!`0`i</td></tr>';
					}

					for ($i=0; $i<$count; $i++)
					{
						$row = db_fetch_assoc($result);

						$row['guildname'] = ($row['guildname']) ? $row['guildname'] : ' - ';

						$state_str = '';
						if ($row['guildid'] && $Char->guildid)
						{
							$state = dg_get_treaty($guild['treaties'][$row['guildid']]);
							if ($state==1)
							{
								$state_str = ' `@(befreundet)';
							}
							else if ($state==-1)
								{
									$state_str = ' `4(Feind)';
								}
						}
						$immu = ((($row['pvpflag']>$pvptimeout && ($Char->profession==0 || $Char->profession>2) && !isset($arr_hot_owners[$row['acctid']]))) || ($Char->guildid>0 && $Char->guildid == $row['guildid']));
						if( !$immu ){
							addnav('','pvp.php?act=attack&id='.$row['acctid']);
						}
						$str_out.='<tr class="'.($i%2?'trlight':'trdark').'">
						<td>'.($row['sentence']>0?'<img src="./images/oldscroll.GIF" width=16 height=16 alt=""> ':'')
                            .CRPChat::menulink($row,($immu ? '' : 'pvp')).($immu ? ' `i(immun)`i' : '')
                            .'`0</td>
						<td align="center">'.$row['level'].'</td>
						<td>'.$row['guildname'].$state_str.'`0</td>
						</tr>';

					}
					$str_out.='</table>`c';
					$Char->reputation--;
					output($str_out,true);
				}
				// END if erlaubt
			}

			else if ($_GET['act']=='colors') //Farbenlehre
			{
					output('`(C`)e`7d`_r`[ik lehnt sich weiter über die Bar. `s"Du willst also was über Farben wissen, hmm?"
					`[Du willst gerade antworten, als du feststellst, dass das eine rhetorische Frage war.
					Cedrik fährt fort: `s"Um Farbe in deine Texte zu bringen, musst du folgendes tun: Zuerst machst du ein &#0096; Zeichen `s(Shift und die Taste links neben Backspace), gefolgt von den Kodierungen, die du auch in deinem Profil sehen kannst. Jedes dieser Zeichen entspricht einer Farbe. Kapiert?"
					`n`[ Hier kannst du testen:
					`0<form action="'.utf8_htmlentities($REQUEST_URI).'" method="POST">
					`[Deine Eingabe: '.str_replace('`','&#0096;',utf8_htmlentities($_POST['testtext'])).'
					`n`[Sieht so aus: '.$_POST['testtext'].'
					`n<input name="testtext" id="input"><input type="submit" class="button" value="Testen">
					`0</form>
					'.focus_form_element("input").'
					`[`n`nDu kannst diese Farben in jedem Text verwenden, den du eing`_i`7b`)s`(t.');
					addnav('',$REQUEST_URI);
				}

			else if ($_GET['act']=='specialty') //Spezialgebiet wechseln
			{
						$c=CRPChat::make_color($Char->prefs['commentemotecolor'],'&');
						if ($_GET['specialty']=='')
						{
							output($c.'"Ich will mein Spezialgebiet wechseln`0"`[, verkündest du Cedrik.
							`n`n`(O`)h`7n`_e`[ ein Wort packt Cedrik dich am Hemd, zieht dich über die Theke und zerrt dich hinter die Fässer hinter ihm.
							Dann dreht er am Hahn eines kleinen Fässchens mit der Aufschrift "Feines Gesöff XXX"
							`n`n`[Du schaust dich um und erwartest, dass irgendwo eine Geheimtür aufgeht, aber nichts passiert.
							Stattdessen dreht Cedrik den Hahn wieder zurück und hebt einen frisch mit seinem vermutlich besten Gebräu gefüllten Krug.
							Das Zeug schäumt und ist von blau-grünlicher Farbe.
							`n`n`s"Was? Du hast einen geheimen Raum erwartet?"`[, fragt er dich. `s"Also dann solltest du noch besser aufpassen, wie laut du sagst, dass du deine Fähigkeiten ändern willst. Nicht jeder sieht mit Wohlwollen auf diese Art von Dingen.
							`n`nWelches neue Spezialgebiet hast du dir denn ged`_a`7c`)h`(t?"');
							$str_where=' WHERE active="1" ';
							if($Char->exchangequest<29)
							{
								$str_where.=' AND usename!="wisdom" ';
							}
							$sql = 'SELECT * FROM specialty '.$str_where.' ORDER BY category,specid';
						$result = db_query($sql);
						while ($row = db_fetch_assoc($result))
						{
							addnav($row['specname'],utf8_preg_replace("/[&?]c=[[:digit:]-]*/",'',$REQUEST_URI."&specialty=".$row['specid']));
						}

					}
					else
					{
						output('`s"Ok, du hast es."
						`n`n'.$c.'"Das war schon alles?`0", fragst du ihn.
						`n`[`nCedrik fängt laut an zu lachen: `s"Jup. Was hasten erwartet? Irgendne Art fantastisches und geheimnisvolles Ritual???
						Du bist in Ordnung, Kleiner... spiel nur niemals Poker, ok?"
						`n`n`s"Ach, nochwas. Obwohl du dein Können in deiner alten Fertigkeit jetzt nicht mehr einsetzen kannst, hast du es immer noch.
						Deine neue Fertigkeit wirst du trainieren müssen, um wirklich gut darin zu `_s`7e`)i`(n."');
						$Char->specialtyuses['old_specialty']= $Char->specialty;
						$Char->specialty=$_GET['specialty'];
					}
				}

			else if ($_GET['act']=='buyale') //Alefass kaufen
			{
						// Lade das Array aus der Funktion
						$keg_info = get_ale_stats();
						$str_output .= '`0Du deutest Cedrik an, dass du ein kleines Bierfass kaufen möchtest, also dreht sich dieser um und öffnet eine kleine Luke im Boden. ';
						// Lade die Menge der bereits gekauften Fässer aus der Datenbank
						$sql = 'SELECT gotalekegs FROM account_extra_info WHERE acctid='.$Char->acctid;
						$res = db_query($sql);
						$row = db_fetch_assoc($res);

						// Prüfe ob noch Bierfässer vorhanden sind
						if($keg_info['total'] < 1)
						{
							$str_output .= 'Doch von dort ist nur gähnende Leere zu erkennen. Es sieht so aus, als hätte Cedrik keine Fässer mehr übrig und deshalb lässt er die Luke zurück in den Boden fallen.';
							if($row['gotalekegs'] >= 5)
							{
								$str_output .= ' Du hattest aber sowieso schon genug Bierfässer gekauft und musst deshalb auf die nächste Lieferung warten.';
							}
							else
							{
								// Zähle die (unten) angegebenen Items
								$hefe = item_count(" `tpl_id` = 'hefe' AND `owner` = '".$Char->acctid."'");
								$hopfen = item_count(" `tpl_id` = 'hopfen' AND `owner` = '".$Char->acctid."'");
								$alkohol = item_count(" `tpl_id` = 'alkohol' AND `owner` = '".$Char->acctid."'");
								$destwasser = item_count(" `tpl_id` = 'wasser' AND `owner` = '".$Char->acctid."'");
								$gewuerze = item_count(" `tpl_id` = 'gewuerze' AND `owner` = '".$Char->acctid."'");

								$str_output .= ' Er schlägt dir aber vor, dass er dir ein Fass brauen könnte, wenn du ihm folgende Zutaten bringst:`n`n
								- 2x Hefe (`@'.$hefe.'x im Inventar`0)`n
								- 2x Hopfen (`@'.$hopfen.'x im Inventar`0)`n
								- 1x Alkohol (`@'.$alkohol.'x im Inventar`0)`n
								- 1x Destilliertes Wasser (`@'.$destwasser.'x im Inventar`0)`n
								- 3x Gewürze (`@'.$gewuerze.'x im Inventar`0)`n`n
								Als Lohn für seine Umstände verlangt er außerdem `^5`0 Edelsteine von dir!';

								// Prüfe, ob der User die nötigen Zutaten und genug Edelsteine hat
								if($hefe >= 2 && $hopfen >= 2 && $alkohol >= 1 && $destwasser >= 1 && $gewuerze >= 3 && $Char->gems >= 5)
								{
									$str_output .= '`n`n`@Du hast alle nötigen Zutaten zusammen und kannst dir nun das Ale brauen lassen.';
									addnav('Ale brauen','inn.php?op=bartender&act=brew');
								}
								else
								{
									$str_output .= '`n`n`$Du hast noch nicht alle nötigen Zutaten!';
									if($Char->gems < 5)
									{
										$str_output .= '`nDu hast nicht genügend Edelsteine dabei!';
									}
								}
						}
					}
					else
					{
						$str_output .= 'Dort kannst du `^'.$keg_info['total'].' `0'.($keg_info['total']==1?'Fass':'Fässer').'  voll Ale erkennen. Cedrik würde dir ein Fass für `^'.$keg_info['gold'].'`0 Gold '.($keg_info['gems']>0?'und `^'.$keg_info['gems'].'`0 Edelsteine':'').' verkaufen.';
						if($row['gotalekegs'] >= 5)
						{
							$str_output .= ' Doch leider hast du schon zu viele Fässer gekauft und Cedrik möchte schließlich auch den anderen Kunden etwas verkaufen. Komm doch einfach nach der nächsten Lieferung wieder!';
						}
						else
						{
							addnav('Kaufe ein kleines Fass Ale','inn.php?op=bartender&act=buyale2');
						}
					}
				}

			else if ($_GET['act']=='buyale2')
			{
						// Array aus Funktion laden
						$keg_info = get_ale_stats();
						// Prüfe ob User überhaupt bezahlen kann
						if($Char->gold >= $keg_info['gold'] && $Char->gems >= $keg_info['gems'])
						{
							$str_output .= 'Du legst Cedrik die `^'.$keg_info['gold'].'`0 Gold '.($keg_info['gems']>0?'und `^'.$keg_info['gems'].'`0 Edelsteine':'').' auf die Theke und verlangst ein kleines Fass Ale von ihm. Er nimmt deine Bezahlung an sich und überreicht dir dafür dein kleines Fass Ale.';
							$Char->gold -= $keg_info['gold'];
							$Char->gems -= $keg_info['gems'];
							item_add($Char->acctid,'klfale');
							$keg_info['total'] -= 1;
							savesetting('totalkeg',$keg_info['total']);
							// Erhöhe die gekaufte Anzahl in der DB um 1
							$sql = 'UPDATE account_extra_info SET gotalekegs=gotalekegs+1 WHERE acctid='.$Char->acctid;
							db_query($sql);
						}
						else
						{
							$str_output .= 'Leider kannst du Cedrik nicht bezahlen';
							// Ein paar kleine Strafen, wenn man Cedriks Zeit verschwendet ;-)
							if($Char->hitpoints<=10)
							{
								$rand = e_rand(2,4);
							}
							else
							{
								$rand = e_rand(1,4);
							}
							switch($rand)
							{
								case 1:
									$str_output .= ' und hast damit seine kostbare Zeit gestohlen. Er hält dir eine Standpauke bis du einschläfst und dir an der Theke den Kopf anschlägst!`n`n
									`$Du verlierst 10 Lebenspunkte!';
									$Char->hitpoints -= 10;
									break;

								case 2:
									$str_output .= ', weshalb er dich wütend ansieht und mit einem dreckigen Lappen nach dir schmeisst. Na toll! Jetzt stinkst du nach Ale und siehst aus wie ein Schwein!`n`n
									Du verlierst einen Charmepunkt!';
									$Char->charm -= 1;
									break;

								case 3:
									$str_output .= ' weshalb du dich schnell aus dem Staub machst. Leider etwas zu schnell! Du rutscht auf einer Alepfütze aus und brichst dir durch eine unschöne Landung das Genick.`n`n
									`$Du bist tot und das Gelächter über dein jämmerliches Ableben ist in der ganzen Stadt zu hören!';
									$Char->hitpoints = 0;
									$Char->alive = false;
									addnews('`0Heute gab großes Gelächter als '.$Char->name.'`0 in der Schenke unglücklich stürzte und so den Löffel abgab.');
									addnav('Zu den News','news.php');
									break;

								case 4:
									$str_output .= ' weshalb du enttäuscht zurück in die Schenke gehst.';
									break;
							}
						}
					}

			else if ($_GET['act']=='brew')
			{
							$str_output .= 'Du gibst Cedrik die Zutaten und dieser verschwindet damit im Hinterzimmer. Nach einer Weile kommt er mit einem frisch gebrauten Fass zu dir zurück und überreicht es dir im Austausch gegen die `@5 Edelsteine.';
							$Char->gems -= 5;
							// Lösche die Anzahl der Zutaten aus dem Inventar des Users
							item_delete(" `tpl_id` = 'hefe' AND `owner` = '".$Char->acctid."'","2");
							item_delete(" `tpl_id` = 'hopfen' AND `owner` = '".$Char->acctid."'","2");
							item_delete(" `tpl_id` = 'alkohol' AND `owner` = '".$Char->acctid."'","1");
							item_delete(" `tpl_id` = 'wasser' AND `owner` = '".$Char->acctid."'","1");
							item_delete(" `tpl_id` = 'gewuerze' AND `owner` = '".$Char->acctid."'","3");
							item_add($Char->acctid,'klfale');
							// Erhöhe die gekaufte Anzahl in der DB um 1
							$sql = 'UPDATE account_extra_info SET gotalekegs=gotalekegs+1 WHERE acctid='.$Char->acctid;
							db_query($sql);
						}

			break;
		}

		case 'room': 
		{
			$aei = db_fetch_assoc(db_query('SELECT boughtroomtoday FROM account_extra_info WHERE acctid='.$Char->acctid));

			$config = utf8_unserialize($Char->donationconfig);
			$expense = round(($Char->level*(10+log($Char->level))),0);
			if ($_GET['pay'])
			{
				if ($_GET['coupon']==1)
				{
					$config['innstays']--;
					debuglog("logged out in the inn");
					$Char->donationconfig=utf8_serialize($config);
					$Char->loggedin=0;
					$Char->location=USER_LOC_INN;

					db_query('UPDATE account_extra_info SET boughtroomtoday=1 WHERE acctid='.$Char->acctid);

					saveuser();
					Atrahor::clearSession();
					redirect("index.php");
				}
				else
				{
					if ($_GET['pay'] == 2 || $Char->gold>=$expense || $aei['boughtroomtoday'])
					{
						if ($Char->loggedin)
						{
							if ($aei['boughtroomtoday'])
							{
							}
							else
							{
								if ($_GET['pay'] == 2)
								{
									$fee = getsetting("innfee", "5%");
									if (mb_strpos($fee, "%"))
									{
										$expense += round($expense * $fee / 100,0);
									}
									else
									{
										$expense += $fee;
									}
									$Char->goldinbank -= $expense;
								}
								else
								{
									$Char->gold -= $expense;
								}
								db_query('UPDATE account_extra_info SET boughtroomtoday=1 WHERE acctid='.$Char->acctid);
							}
						}
						redirect('login.php?op=logout&loc='.USER_LOC_INN.'&restatloc=0');
					}
					else
					{
						output('`s"Aah, so ist das also."`[, sagt Cedrik und hängt den Schlüssel, den er gerade geholt hat, wieder an seinen Haken hinter der Theke. Vielleicht solltest du erstmal für das nötige Kleingeld sorgen, bevor du dich am örtlichen Handel beteiligst.');
					}
				}
			}
			else
			{
				if ($aei['boughtroomtoday'])
				{
					output('`[Du hast heute schon für ein Zimmer bezahlt.');
					addnav('Gehe ins Zimmer','inn.php?op=room&pay=1');
				}
				else
				{
					if ($config['innstays']>0)
					{
						addnav('`[Zeige ihm den Gutschein für '.$config['innstays'].' Übernachtungen','inn.php?op=room&pay=1&coupon=1');
					}
					output('`[Du trottest zum Wirt und fragst nach einem Zimmer. Er betrachtet dich und sagt: `s"Das kostet `^'.$expense.'`s Gold für die Nacht." ');
					$fee = getsetting("innfee", "5%");
					if (mb_strpos($fee, "%"))
					{
						$bankexpense = $expense + round($expense * $fee / 100,0);
					}
					else
					{
						$bankexpense = $expense + $fee;
					}
					if ($Char->goldinbank >= $bankexpense && $bankexpense != $expense)
					{
						output('`[Weil du so eine nette Person bist, bietet er dir zum Preis von `^'.$bankexpense.'`[ Gold auch an, direkt von der Bank zu bezahlen. Der Preis beinhaltet ' . (mb_strpos($fee, '%') ? $fee : $fee.' Gold') . ' Überweisungsgebühr.');
					}

					output('`n`n`[Du willst dich nicht von deinem Gold trennen und fängst an darüber zu debattieren, dass man in den Feldern auch kostenlos schlafen könnte. Schließlich siehst du aber ein, dass ein Zimmer in der Schenke vielleicht der sicherere Platz zum Schlafen ist, da es schwieriger für Herumstreicher sein dürfte, in einen verschlossenen Raum einzudringen.');
					addnav('Gib ihm '.$expense.' Gold','inn.php?op=room&pay=1');
					if ($Char->goldinbank >= $bankexpense)
					{
						addnav('B?Zahle '.$bankexpense.' Gold von der Bank','inn.php?op=room&pay=2');
					}
				}
			}
			break;
		}
	}
	if($Char->alive==true)
	{
		addnav('Zurück zur Schenke','inn.php');
	}
	output($str_output);
}
page_footer();
?>