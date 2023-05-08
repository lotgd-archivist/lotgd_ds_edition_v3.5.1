<?php
/**
 * @desc Ein Hehler in der Kneipe, und sonstige Auswüchse *g*
 * @author Jenutan
 * @copyright Jenutan 2007, for Atrahor
 * @edit 09/08 Asgarath (asgarath@hotmail.de) - Anpassung für Stadtwachen
 */

if(!isset($session))
{
	die('');
}

$fehler = false;
$session['user']['specialinc'] = basename(__FILE__);
$str_output = '';
//Name unseres "Helden" xD
$name = "`4Buttler `TArtios";

switch($_GET['sop'])
{
	default:
		$str_output .= '
			<big><big><big>
			`$FEHLER! Hier solltest du nicht sein dürfen, bitte melden!
			</big></big></big>
		';
		//Absichtlich KEIN break;  ;)
	case '':
		page_header('Der Fremde');
		addnav('Fremde Gestalt');
		// Im String Abfrage ob User zur Stadtwache gehört
		$str_output .= '`n
			`n`7
			Du betrittst die Schenke wie gewohnt.
			Doch heute fühlst du dich etwas komisch,`n
			setzt dich gleich am Eingang hin und beobachtest die Wesen,
			die `&- wie immer - `7ein und ausgehen.`n
			`n
			Doch plötzlich bemerkst du eine dunkle Gestalt,
			die in einer noch dunkleren Ecke der Schenke sitzt.`n
			Keiner bisher scheint sie bemerkt zu haben,
			auch dir kommt diese Person unbekannt - gar befremdend - vor.`n
			`n
			`@Was willst du tun?`0`n
			'.($session['user']['profession']==PROF_GUARD || $session['user']['profession']==PROF_GUARD_HEAD ? create_lnk('Als Stadtwache handeln?','inn.php?sop=wachesein',true,true,'',false,'Amt nutzen',CREATE_LINK_LEFT_NAV_HOTKEY) : create_lnk('Eine Stadtwache holen?','inn.php?sop=wacheholen',true,true,'',false,'Stadtwache holen',CREATE_LINK_LEFT_NAV_HOTKEY)).'`n
			'.create_lnk('Nicht beachten?','inn.php?sop=nichtbeachten',true,true,'',false,'Nicht beachten',CREATE_LINK_LEFT_NAV_HOTKEY).'`n
			`7Oder etwa`0 '.create_lnk('ansprechen?','inn.php?sop=ansprechen',true,true,'',false,'Ansprechen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n
		';
	break;
	case 'nichtbeachten':
		page_header('Nicht beachten!');
		$session['user']['specialinc'] = '';
		$session['user']['gold'] = (int)($session['user']['gold'] /2);
		$str_output .= '
			`7Du stehst auf, gehst an der dunkeln, unheimlichen Gestalt vorbei und
			beachtest sie nicht weiter...`n
			`n
			Irgendwie hast du das Gefühl,
			einen Fehler begangen zu haben...`n
			`n
			Später, als du die Schenke wieder verlassen hast,
			merkst du, dass `4die Hälfte deines Goldes fehlt!`7`n
			Du rennst in die Schenke zurück,
			doch die Gestalt `&- die du verdächtigst - `7ist längst verschwunden...
			`n
			`&Vielleicht hättest du ja doch die Stadtwachen rufen sollen?
			`7 denkst du dir.
		';
		addnav('Weiter','inn.php');
	break;
	// Nur für Stadtwachen
	case 'wachesein':
		page_header('Stadtwache');
		addnav('Wie willst du Handeln?');
		$str_output .= '
			`7Da dir diese Person zwielichtig vorkommt, entschließt du dich sie vorsichtig aus der Ferne zu beobachten. Lange Zeit geschieht nichts, doch dann gesellt sich eine zweite Gestalt dazu. Deine Erfahrung als `b`4Stadtwache`b `7sagt dir, dass hier etwas nicht mit rechten Dingen zugeht.`n
			`n
			`@Wie willst du Handeln?`0`n
			'.create_lnk('Die beiden festnehmen?','inn.php?sop=festnehmen',true,true,'',false,'Festnehmen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n
			'.create_lnk('Es doch lieber sein lassen?','inn.php?sop=abwenden',true,true,'',false,'Abwenden',CREATE_LINK_LEFT_NAV_HOTKEY).'`n
			';
	break;
	case 'festnehmen':
		$session['user']['specialinc']='';
		output("`7Ohne weiter Zeit zu verschwenden, gehst du auf die beiden Gestalten zu und rufst`n`n`$ Halt! Im Namen der Stadtwache!`7`n`n");
		$rand=e_rand(1,3);
		switch($rand)
		{
			case 1:
				$str_output .= '
				Kaum bist du bei ihnen angekommen, haben sie auch schon die Tische umgeworfen und ihre Schwerter gezückt.
				Allerdings bist du ihnen im Kampf überlegen und so dauert es nicht lange, bis du sie überwältigt und
				in den Kerker geschleppt hast. Dort erfährst du, dass es sich um zwei gesuchte Verbecher handelt!`n
				`n
				`^Du bekommst eine Belohnung in Höhe von 3000 Goldstücken!`n
				';
				$session['user']['gold'] += 3000;
				addnav('Erfolg!');
				addnav('Juhuu!','prison.php');
			break;
			case 2:
				$str_output .= '
				Als du näher kommst, spürst du, dass etwas nicht stimmt. Sofort greifen dich die beiden Fremden an.
				Leider hast du keine Chance zu reagieren und gehst schon nach dem ersten Treffer ohnmächtig zu Boden.
				Sofort eilt dir Cedrik zu Hilfe und deinen Angreifern bleibt nichts anderes übrig als die Flucht zu ergreifen.
				Als du wieder zu dir kommst, merkst du, dass sie deinen Goldbeutel mitgenommen haben.`n
				`n
				`$ Du verlierst dein ganzes Gold!`n
				`$ Du solltest dringend einen Heiler aufsuchen!`n
				';
				$session['user']['gold'] = 0;
				$session['user']['hitpoints']= 1;
				addnav('Fehlschlag');
				addnav('Verdammt...','inn.php');
			break;
			case 3:
				$str_output .= '
				Sofort heben sie die Arme und beteuern dir ihre Unschuld, doch davon lässt du dich nicht beirren!
				Du schleppst sie in den Kerker und hoffst auf eine dicke Belohung.
				Leider stellt sich heraus, dass es sich um gute Bürger handelt, welche nur ein Pläuschchen halten wollten.
				Unschuldige zu verhaften gehört sich für eine Stadtwache nicht!`n
				`n
				`$Du verlierst Ansehen!`n
				`n
				';
				$session['user']['reputation'] -= 5;
				addnews($session['user']['name']."`2 hat heute große Schande über die `b`4Stadtwache`b `2gebracht, als er Unschuldige verhaftete!");
				addnav('Schäm dich!');
				addnav('Schämen gehen','prison.php');
			break;
			default:
				$fehler=true;
		}
	break;
	case 'abwenden':
		$session['user']['specialinc']='';
		page_header('Einbildung');
		addnav('Einbildung');
		$str_output .= '
			`7Du beobachtest die beiden noch etwas, 
			doch da dir an dem Verhalten nichts weiteres auffällt,
			denkst du dir, dass es wohl Einbildung gewesen sein muss.`n
			Also wendest du dich ab um dich wieder "wichtigen" Dingen zu widmen.`n
			';
		addnav('Zahlen bitte...!','inn.php');
	break;
	case 'wacheholen':
		page_header('Schreck!');
		addnav('Schreck!');
		$str_output .= '
			`7Da dir diese Person sehr unheimlich vorkommt,
			entschließt du dich lieber eine Stadtwache zu rufen.`n
			Kaum bist du aus der Schenke drausen und willst den Mund aufmachen, um eine Wache zu rufen,`n
			legt sich plötzlich eine kalte, feuchte Hand auf deine linke Schulter...`n
			`n
			`^Du bist im ersten Moment wie vor Schreck erstarrt!`n
			`n
			`@Was tust du?`0`n
			'.create_lnk('Dich vorsichtig umdrehen!','inn.php?sop=umdrehen&how=slow',true,true,'',false,'Vorsichtig umdrehen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n
			'.create_lnk('Dich blitzartig umdrehen!','inn.php?sop=umdrehen&how=fast',true,true,'',false,'Schnell umdrehen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n
			'.create_lnk('Weiter rennen!','inn.php?sop=weiterrennen',true,true,'',false,'Weiter rennen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n
		';
	break;
	case 'umdrehen':
		//Seiten- und eine Navigations-Überschift
		page_header('Umdrehen');
		addnav('Umdrehen');

		switch($_GET['how'])
		{
			//Wenn man sich langsam umgedreht hat
			case 'slow':
				//Standard-Textausgabe
				$str_output .= '
					`7Langsam drehst du dich um.`n
					`n
				';
				switch(e_rand(1,3))
				{
					case 1:
						//Cedrik steht hinter einem, und verlangt die Zeche

						//Besonderes Ereignis löschen
						$session['user']['specialinc'] = '';

						//Zeche berechnen
						$costs = (int)(e_rand(50,100)/100 * $session['user']['gold']);
						if($session['user']['gold'] >= $costs)
						{
							$session['user']['gold'] -= $costs;
						}
						else
						{
							$session['user']['gold'] = 0;
						}
						if($session['user']['gems'])
						{
							$session['user']['gems'] --;
							$loseEdel = true;
						}

						//Text ausgeben
						$str_output .= '
							`7Hinter dir steht </span><span style="color: #9900FF;">Cedrik`7!`n
							`n
							`&Puhhh`7, denkst du und bist erleichtert,
							ganz im Gegensatz zu ihm.`n
							`n
							Mit `$hochroten Gesicht`7 knallt er dir die Rechnung in die Hand,
							die du nicht bezahlt hattest.
							Da hilft es dir auch nichts die Wachen zu rufen.`n
							`n
							`4"Macht dann `^'.$costs.' Goldstücke`4'.
							( $loseEdel ? ' und `^einen Edelstein`4' : '' ).'."`7,
							kläfft er dich an und du bezahlst was du ihm schuldest.
							`n
							Etwas verwirrt stehst du nun da.`n
							Als du wieder in die Schenke gehst, um nach der seltsamen Gestalt zu sehen,`n
							ist diese nicht aufzufinden...
						';

						//Link setzen
						addnav('Weiter', 'inn.php');
					break;
					case 2:
						//Dragonslayer steht hinter einem *g*

						//Special beenden
						$session['user']['specialinc'] = '';

						//Slayers Name holen^^
						$sql = "
							SELECT
								`name`
							FROM
								`accounts`
							WHERE
								`acctid` = '1'
						";
						$result = db_query($sql);
						$god = db_fetch_object($result);
						$god = $god->name;

						//ggf. einen fiktiven Namen einfügen
						if(!$god) $god = "Gott";

						//Textausgabe
						$str_output .= '
							'.$god.' `7steht hinter dir, lächelt und reicht dir einen Edelstein!`n
							`n
							Du bedankst dich auch ganz höflich und rennst schnell zurück in die Schenke...
						';

						//Eine kleine blöde News ;)
						addnews('
							`3'.$session['user']['name'].' `3bekam von `~'.$god.' `3 ein kleines Geschenk.
							Da könnte man richtig neidisch werden.
						');

						//Den Edelstein nicht vergessen ^^
						$session['user']['gems'] ++;

						//Irgendwie muss man ja auch hier wegkommen *g*
						addnav('Zur Schenke','inn.php');
					break;
					case 3:
						//Der Hehler steht hinter einem und spricht einen an...

						$str_output .= '
							Tatsächlich steht die unbekannte Gestalt vor dir!
						';
						addnav('Ansprechen','inn.php?sop=ansprechen');
					break;
					default:
						//darf's nicht geben -> die-Fehler produzieren
						$fehler = true;
				}
			break;
			case 'fast':
				$str_output .= '
					`7Schnell drehst du dich um.`n
					`n
				';

				switch(e_rand(1,2))
				{
					case 1:
						//Direkter (standard) Tod
						$session['user']['specialinc'] = '';
						$str_output .= '
							Leider `$`bzu`b`7 schnell!`n
							Du bist schon tot, bevor du erkennen kannst,`n
							wer hinter dir stand...`n
							`n
							`4Du wirst all deines Goldes beraubt und verlierst Erfahrung!

						';
						addnews('`3'.$session['user']['name'].'
							`3 drehte sich in einer gefahrvollen Situation
							"nur" zu Schnell um und starb deswegen...
						');
						killplayer();
					break;
					case 2:
						//Edelsteine für Charm *g* - mein persönlicher Favourit!
						$session['user']['specialinc'] = '';
						$gems = e_rand(3,5);
						$str_output .= '
							Ein `jB`@e`Jt`2runke`Jn`@e`jr `7steht hinter dir!`n
							`n
							Er schwankt vo`ir`i, er schwankt zurüc`ik`i.`n
							Doch plötzlich muss er sich übergeben, ausgerechnet direkt über dich.`n
							`n
							Doch in seinem Erbrochenen findest du eine handvoll Klunker,`n
							du zählst insgesamt `^'.$gems.' Edelsteine`7,
							die du natürlich sofort behälst.`n
							`n
							Du setzt ihn noch auf eine Bank ab,`n
							nun solltest du dich aber wieder etwas zurecht machen,`n
							dein Aussehen hat doch ein wenig gelitten.`n
							`n
							Als du zurück kommst,`n
							ist die seltsame Gestalt jedoch nicht mehr in der Schenke aufzufinden...
						';

						addnews('`3'.$session['user']['name'].'
							`3 wurde von einem `jB`@e`Jt`2runke`Jn`@e`jn `3"beschenkt" ... igitt!
						');

						$session['user']['gems'] += $gems;
						$session['user']['charm'] -= 5;
						if ($session['user']['charm'] < 0)
						{
							$session['user']['charm'] = 0;
						}
						addnav('Zurück zur Schenke', 'inn.php');
					break;
					default:
						$fehler = true;
				}
			break;
			default:
				//darf's nicht geben -> die-Fehler produzieren
				$fehler = true;
		}
	break;
	case 'weiterrennen':
		addnav('Weiter rennen');
		$str_output .= '
			`7Du reißt dich los und rennst davon.`n
			`n
		';

		//Noch keine wirkliche Idee eingefallen...
		switch("1")
		{
			case 1:
				$session['user']['specialinc'] = '';
				$str_output .= '
					`7Doch nach ein paar Metern stolperst du ganz ungeschickt und
					machst dich dabei dreckig
				';
				if($session['user']['gems'] >= 3)
				{
					$session['user']['gems'] -= 3;
					$str_output .= '
						und `4verlierst dabei 3 Edelsteine`7!
					';
				}
				else
				{
					$str_output .= '.';
				}
				$str_output .= '
					`n
					`n
					`7Nichtsdestotrotz stehst du wieder auf. Die Person hinter dir scheint verschwunden zu sein...`n
					`n
					Du klopfst den Dreck von deiner Rüstung und machst dich auf, zurück zur Schenke.
				';
				addnews('`3'.$session['user']['name'].'
					`3 fiel in den Dreck vor der Schenke!
					`7Du Saubär`3 rufen die Kinder '.($session['user']['sex']?'ihr':'ihm').' nach...
				');
				addnav('Zur Schenke', 'inn.php');
			break;
			default:
				$fehler = true;
		}
	break;
	case 'ansprechen':
		page_header('Hehler');
		addnav('Antworten');
		$str_output .= '
			`7Du sprichst die Gestalt einfach mutig an.`n
			Sie ist dir nicht böse gesinnt und stellt sich dir als '.$name.' `7vor.`n
			`n
			`3"Seid gegrüßt '.$session['user']['name'].'`3,`n
			ich bin Händler und interessiere mich für Dinge, die keinen interessieren.`n
			Zur Zeit auf der Suche nach einer `%Rose`3,`n
			einer `Tharten `%Rose`3.`n
			`n
			Hast du eine `isolche`i `%Rose`3?"`7, fragt er dich.`n
			`n
			`@Was tust du?`0`n
			'.create_lnk('"Ja" antworten!','inn.php?sop=ja',true,true,'',false,'Ja',CREATE_LINK_LEFT_NAV_HOTKEY).'`n
			'.create_lnk('"Nein" antworten!','inn.php?sop=nein',true,true,'',false,'Nein',CREATE_LINK_LEFT_NAV_HOTKEY).'`n
		';
	break;
	case 'ja':
		page_header('Hehler');
		addnav('"Ja!"');
		$str_output .= '
			`7Du antwortest selbstsicher:`n
			`&Aber Ja doch!`n
			`n
			`7Du leerst der Gestalt deine Taschen aus, sie sieht mit einem Blick darüber`n
			und sagt:`n
			`n
		';
		//Hat man denn schon eine Rose (in Schlüsselform ;)?
		$int_count = item_count(" `name` = '`TRosenschlüssel`0' AND `owner` = '".$session['user']['acctid']."'");

		if($int_count)
		{
			$str_output .= '
				`3"Da ist ja genau das, was ich gesucht habe!"`n
				`n
				`7Sie packt sich den `TRosenschlüssel `7und gibt dir dafür `^10 Edelsteine`7!`n
				`n
				Nachdem ihr die Waren getauscht habt,
				verabschiedet sich die Gestalt `n
				und verschwindet so schnell sie kann durch einen Hinterausgang...
			';
			//Ware austauschen
			$session['user']['gems'] += 10;
			item_delete("`name` = '`TRosenschlüssel`0' AND `owner` = ".$session['user']['acctid']);

			$session['user']['specialinc'] = '';
			addnav('Verdutzt Aussehen', 'inn.php');
		}
		else
		{
			$str_output .= '
				`3"Nein, nicht dabei, was ich suche. Ich muss los, man darf mich hier nicht sehen!`n
				Cya"`n
				`n
				`7Und ehe sie ihren Satz beendet hat, ist sie schon am Davonrauschen.`n
				`n
				Etwas verdutzt schaust du ihr hinterher.`n
				Als du bemerkst, dass dir `4etwas Gold fehlt`7...
			';
			$rand = e_rand(25,75);
			$session['user']['gold'] = (int)($rand/100 * $session['user']['gold']);
			addnav('Pech gehabt!', 'inn.php');
			$session['user']['specialinc'] = '';
		}

	break;
	case 'nein':
		page_header('Hehler');
		addnav('"Nein!"');
		$str_output .= '
			`7Du antwortest vorsichtshalber mit `&Nein, habe ich nicht!`n
			`n
			`7Die Gestalt - von der du weder Rasse, noch Geschlecht sicher kennst, du erahnst nur, dass sie männlich ist - antwortet dir darauf:`n
			`3"Dann haben wir uns nie getroffen, lebe wohl."`7 und schon steht die Gestalt auf,`n
			begibt sich rasch zum Ausgang und ward nicht mehr gesehen.`n
			`n
			Du rennst der Gestalt zwar noch hinterher,`n
			doch draußen findest du sie nicht mehr!`n
			`n
			Etwas verwirrt begibst du dich zurück zu Schenke...
		';
		$session['user']['specialinc'] = '';
		addnav('Zurück zur Schenke', 'inn.php');
	break;
}

//Falls ein >default< eintritt, das nicht soll...
if($fehler)
{
	die('
		FEHLER! ach ja?!?^^<br />
		Bitte melden!<br />
		<br />
		<big>DANKE!</big><br />
		Ein Klick auf "zurück" hilft meistens...
	');
}

output($str_output, true);
unset($str_output);
?>
