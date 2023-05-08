<?php
/**
* @desc Halle der Geister von Fingolfin
* @date 14.4.2007
* @version 1.2
* @author Fingolfin
* @copyright Fingolfin für Atrahor.de
*/

/*
---------------------------------------------------------------
SQL Befehle:

ALTER TABLE `account_extra_info` ADD `abyss` TINYINT( 1 ) UNSIGNED NOT null DEFAULT '0';
ALTER TABLE `account_extra_info` ADD `oldspirit` TINYINT( 1 ) UNSIGNED NOT null DEFAULT '0';
---------------------------------------------------------------

In "newday.php" beim 2. "$changes_new" in der Auflistung ", 'abyss'=>0, 'oldspirit'=>0 " einfügen

*/

//=============================================================

$str_filename = basename(__FILE__);
$str_backtext = 'Zu den Schatten';
$str_backlink = 'shades.php';
//Max. Gefallen die ein User abgeben kann
$max_give = 10;
//Max. Gefallen des Geistes
$max_oldspirit = 200;

//=============================================================

require_once 'common.php';

if ($session['user']['alive'])
{
redirect('village.php');
}

page_header('Halle der Geister');
addcommentary();
checkday();

$str_output = '';

switch($_GET['op'])
{
//Standartanzeige der Halle der Geister
case '':

	$str_output .= '`c`b`)H`ea`sl`&le der Geis`st`ee`)r`0`b`c`n
	`eW`)ä`(h`Nrend du durch das Schattenreich wanderst, gelangst du in eine Gegend, in der das schon spärliche Licht noch schwächer zu werden scheint. Neugierig gehst du weiter und entdeckst in der Ferne ein schwach schimmerndes bläuliches Licht. Als du näher kommst, werden die Umrisse einer gewaltigen Halle immer klarer. Ehrfürchtig bleibst du vor dem Portal stehen, dessen Türen bereits lange aus den Angeln gefallen sind. Du gehst um die riesigen Türflügel herum und betrittst die Halle. Staunend schaust du dich im Inner`(n `)u`em.`n`n
	`)An den Seiten der Halle sind Nischen eingelassen, in denen sich andere Seelen leise flüsternd unterhalten.
	Das blaue Schimmern scheint aus den Fugen zwischen den massiven Bodensteinplatten zu kommen, als würde es die gesammte Halle als einziges noch zusammenhalten.
	Als du die Mitte erreicht hast hörst du ein leises Summen. Das muss von der gegenüberliegenden Seite kommen.
	Du näherst dich dem immer intensiver werdenden Summen und stellst fest, dass es aus einem tiefen pechschwarzen Abgrund kommt, der alles Licht in seinem Umkreis zu verschlingen scheint.`n`n
	`eU`)n`(e`Nntschlossen drehst du dich um und gehst in Richtung der Nischen von denen dir einige Gesprächsfetzen zu Ohren kom`(m`)e`en...`0`n`n';
	output($str_output);

	

	addnav('Halle der Geister');
	addnav('An den Abgrund',$str_filename.'?op=abyss');
	addnav('Der alte Geist',$str_filename.'?op=oldspirit');
	//addnav('S?Geister-Schach','chess.php');
	if($session['user']['exchangequest']==22)
	{
		addnav('s?`%Der seltsame Geist`0','exchangequest.php');
	}
	if($session['user']['gravefights']<2)
	{
		addnav('x?Hexengeist','shades_bonestacker.php');
	}
	addnav('Zurück');
	addnav($str_backtext,$str_backlink);
	break;

//Der Abgrund am Ende der Halle der Geister
case 'abyss':

	switch($_GET['act'])
	{
		case '':

			$str_output .= 'Langsam bewegst du dich auf den pechschwarzen Abgrund zu und bleibst kurz vor der Kante stehen.
			Ohne es unterdrücken zu können stellen sich deine Nackenhaare auf und du bekommst eine Gänsehaut.
			Zwar kannst du nicht erkennen, wie tief es hier hinunter geht, aber du hast die Vermutung, dass es hier gar keinen Boden gibt...`n`n
			`$Was wirst du tun?';
			output($str_output);

			addnav('Springen!',$str_filename.'?op=abyss&act=jump');
			addnav('Zurück');
			addnav('In die Halle',$str_filename);
			break;

		case 'jump':

			//Der Attributwert von 'abyss' wird aus der DB gelesen
			$arr_jump = user_get_aei('abyss',$session['user']['acctid']);

			//Wenn der User noch nicht in den Abgrund gesprungen ist, dann...
			if($arr_jump['abyss']<1)
			{
			$str_output .= 'Du fasst all deinen Mut zusammen und kurz bevor du über den Rand trittst kneifst du die Augen zusammen.
			Du fühlst, wie du nach unten gezogen wirst und der Fall kommt dir wie eine Ewigkeit vor, doch ...`n`n`)';

			//Verschiedene Fälle, was passieren kann
			switch(e_rand(1,8))
			{
				case 1:
				case 2:
				case 3:

					$str_output .= '... mit einem Mal spürst du, wie du hart auf etwas aufprallst.
					Als du deine Augen öffnest siehst du, dass du dich wieder in der Mitte der Halle befindest.`n
					Gerade willst du enttäuscht fortgehen, als neben dir aus dem Nichts ein paar Goldstücke herabfallen.';

					$session['user']['gold'] += 10;
					break;

				case 4:
				case 5:

					$str_output .= '... plötzlich spürst du wie dein Körper gebremst wird und du auf etwas zu sitzen kommst.
					Vorsichtig schaust du zwischen deinen Augenlidern hervor und stellst fest, dass du in einer der Nischen in der Halle sitzt.
					Erleichtert stehst du auf und gehst weiter.';
					break;

				case 6:

					$str_output .= '... ohne jede Vorwarnung wirst mit dem Rücken auf einen glatten Boden gepresst.
					Da du bereits tot bist macht dir das auch nicht mehr viel aus, nur etwas stört dich - ein stechender Schmerz an deinem Schulterblatt.
					Du drehst dich zur Seite und entdeckst einen Edelstein, der unter dir gelegen hat. Freudig sammelst du ihn auf und gehst weiter.';

					$session['user']['gems'] += 1;
					break;

				case 7:
				case 8:

					$str_output .= '... plötzlich ist das Gefühl des freien Falls verschwunden.
					Du spürst festen Boden unter deinen Füßen und vorsichtig öffnest du die Augen. Um dich herum ist ein schwarzes Nichts.
					Panisch schaust du dich um und bemerkst etwas verwirrt, dass du immer noch direkt vor dem Abgrund stehst.
					Gerade als du dich fragst ob du nun schon halluzinierst, hörst du aus der Ferne ein tönendes Gelächter:`n`n
					`$Du hast mich zum Lachen gebracht. Dafür gewähre ich Dir meine Gunst!';

					$session['user']['deathpower'] += 1;
					break;
			}

			//Der Attributwert 'abyss' wird auf 1 gesetzt
			user_set_aei(array('abyss'=>1),$session['user']['acctid']);
			}
			//Wenn der User schon einmal gesprungen ist, dann...
			else
			{
			$str_output .= 'Du stehst vor dem Abgrund und überlegst ob du springen solltest.
			Nach einer Weile kommst du zu dem Schluss, dass du heute schon genug durchgemacht hast und es nicht mehr ertragen würdest, erneut über die Kante zu treten...';
			}
			output($str_output);

			addnav('Weiter',$str_filename);
			break;

	}
	break;

//Der alte Geist
case 'oldspirit':

	//Mögliche Aktionen des alten Geistes
	switch($_GET['act'])
	{
		//Standardanzeige beim alten Geist
		case '':

			output('`vAls du die Nischen genauer begutachtest, fällt dir etwas abgelegen ein sehr alt aussehender Geist auf, der ohne jegliche Anteilnahme am Geschehen in der Halle regungslos verharrt.
			Du hast das komische Gefühl, dass du der einzige bist, der dem Geist seine Aufmerksamkeit schenkt.`n`n
			`0Was wirst du tun?');

			addnav('Der alte Geist');
			addnav('Sprich mit ihm',$str_filename.'?op=oldspirit&act=ask');
			addnav('Habe Mitleid',$str_filename.'?op=oldspirit&act=give');
			addnav('Seele besänftigen',$str_filename.'?op=oldspirit&act=soothe');
			addnav('Zurück');
			addnav('In die Halle',$str_filename);
			break;
		case 'soothe':
			{
				if($Char->getNewdayBit(UBIT_OLD_SPIRIT_SOOTHE_SOUL) == UBIT_OLD_SPIRIT_SOOTHE_SOUL)
				{
					$str_output .= 'Der alte Geist ist noch immer sehr müde von deiner letzten Seelenbesänftigung. Gönne ihm etwas Ruhe.`n
					Außerdem, mehr als einmal am Tag solltest du dein Glück hier wirklich nicht auf die Probe stellen!';
					addnav('Dann eben morgen',$str_filename.'?op=oldspirit');
				}
				else
				{
					switch ($_GET['subact'])
					{
						//Mehr Grabkämpfe für Seelenkraft
						case 1:
							{
								$Char->setNewdayBit(UBIT_OLD_SPIRIT_SOOTHE_SOUL,1);
								$str_output .= 'Der alte Geist beginnt mit einem leisen, beruhigenden Singsang und legt seine kühle Hand auf deine Stirn.`n
								Du merkst, wie deine Leiden gelindert werden und erhältst '.$_GET['bonus'].' Runden hinzu!`n`n';

								$session['user']['gravefights'] += $_GET['bonus'];
								$session['user']['soulpoints'] -= $_GET['points'];


								//Ramius bemerkt den Schwindel
								if (e_rand(0,4) == 0)
								{
									$str_output .= 'Leider erkennt Ramius deinen Betrugversuch und ist schwer enttäuscht von dir. Du wirst ihn lange Zeit um keinen Gefallen mehr bitten können.';
									$session['user']['deathpower']=0;
									addnav('Verdammt',$str_filename.'?op=oldspirit');
								}
								else
								{
									addnav('Vielen Dank',$str_filename.'?op=oldspirit');
								}
							}
							break 1;
						default:
							{
								$int_points = max(round($session['user']['soulpoints']*0.9),20);
								$int_bonus = e_rand(2,3);
								$str_output .= 'In meiner langen Zeit in dieser tristen Unterwelt habe ich gelernt, die Qualen einer Seele zu erlassen...`n
								Für nur '.$int_points.' deiner Seelenpunkte kann dein Körper '.$int_bonus.' Qualen mehr ertragen.`n`n';
								if($int_points <= $session['user']['soulpoints'])
								{
									$str_output .= 'Für dich klingt es nach einem fairen Angebot. Hoffentlich merkt Ramius nichts! Es würde ihm bestimmt nicht gefallen.';
									addnav('Ja, das klingt fair',$str_filename.'?op=oldspirit&act=soothe&subact=1&points='.$int_points.'&bonus='.$int_bonus);
									addnav('Nee, lieber nicht.',$str_filename.'?op=oldspirit');
								}
								else
								{
									$str_output .= 'Das klingt zwar ganz nett, aber leider kannst du dir sowas momentan bei Ramius nicht leisten.';
									addnav('Tja schade auch',$str_filename.'?op=oldspirit');
								}
							}
					}
				}
			}
			output($str_output);
			break;

		//Den alten Geist ansprechen
		case 'ask':

			//Der Attributwert von 'oldspirit' wird aus der DB gelesen
			$arr_ask = user_get_aei('oldspirit',$session['user']['acctid']);

			//Wenn 'oldspirit' Null ist UND der User keine Folterrunden mehr hat UND weniger als 100 Gefallen, dann...
			if($arr_ask['oldspirit']<1 && $session['user']['gravefights']<1 && $session['user']['deathpower']<100)
			{
			$str_output .= 'Du näherst dich dem alten Geist und willst ihn gerade ansprechen, als dieser sich dir zuwendet und flüstert:`n`n
			`vDu siehst so aus, als würdest Du in Ramius Gunst nicht gerade gut stehen. ';

			//Wenn der alte Geist mindestens 5 Gefallen besitzt, dann...
			if(getsetting('oldspiritamount','0')>4)
			{
				$str_output .= 'Ich werde einmal mit Ramius reden. Leider kann ich dir nicht versprechen, dass Ramius mich anhört ...`n`n
				`0Mit diesen Worten dreht er sich wieder von dir weg, um erneut in seiner Starre zu versinken.
				Verwundert lässt du von ihm ab um dich wieder in die Halle zubegeben, nicht ohne den Hintergedanken einmal bei Ramius vorstellig zu werden.';

				//Der Geist verliert 5 Gefallen
				savesetting('oldspiritamount', (getsetting('oldspiritamount','0')-5));
				//Der User erhält 5 Gefallen
				$session['user']['deathpower'] += 5;
			}
			//Wenn der Geist nicht genug Gefallen hat, dann...
			else
			{
				$str_output .= 'Ich würde ja zu gerne etwas für Dich tun, doch ich habe gerade selber keinen guten Draht zu Ramius.`n`n
				`0Traurig schaut er dich noch einmal an um sich kurz darauf wieder von dir wegzudrehen um erneut in seiner Starre zu versinken.';
			}

			//Der Attributwert von 'oldspirit' wird auf 1 gesetzt
			user_set_aei(array('oldspirit'=>1),$session['user']['acctid']);
			}
			//Wenn der User noch Folterrunden, zu viel Gefallen hat, oder schon 5 Gefallen bekommen hat, dann..
			else
			{
			$str_output .= 'Du näherst dich dem alten Geist und willst ihn gerade ansprechen, als dieser sich dir zuwendet und dich energisch anspricht:`n`n`v';

			//Wenn er noch Folterrunden hat, dann...
			if($session['user']['gravefights']>0)
			{
				$str_output .= 'Verschwinde, du kannst noch genug Seelen quälen um selbst in Ramius Gunst zu steigen.`n`n';

			}
			//Wenn er zu viel Gefallen hat, dann...
			else if($session['user']['deathpower']>99)
			{
				$str_output .= 'Verschwinde, du stehst selbst hoch genug in Ramius Gunst, um wieder zu den Lebenden zu kommen.`n`n';
			}
			//Wenn er schon 5 Gefallen bekommen hat, dann...
			else
			{
				$str_output .= 'Dein Gesicht kenn ich! Verschwinde, bevor ich auf die Idee komme mich bei Ramius über dich zu beschweren.`n`n';
			}

			$str_output .= '`0Erschrocken weichst du ein paar Schritte zurück und lässt den alten Geist in Ruhe.';

			}
			output($str_output);

			addnav('Weiter',$str_filename);
			break;

		//Wenn der User Gefallen abgeben will
		case 'give':

			if($session['user']['deathpower']>$max_give)
			{
			$form_gefallen = $max_give;
			}
			else
			{
				$form_gefallen = $session['user']['deathpower'];
			}

			//Abfrage wie viel Gefallen an den Geist gehen sollen
			$str_output .= 'Du betrachtest den alten Geist und bekommst Mitleid mit ihm.
			Nach kurzem Überlegen kommt dir eine Idee, wie du ihm vielleicht etwas Gutes tun könntest.
			Du nimmst dir vor, mit Ramius zu reden und dich für den alten Geist einzusetzen...`n`n
			<form action="'.$str_filename.'?op=oldspirit&amp;act=given" method="POST">`)Du möchtest <input name="gefallenspende" id="gefallenspende" size="5" value="'.$form_gefallen.'"> Gefallen für den alten Geist geben. Du hoffst, dass dir Ramius gut gesonnen ist.`n`n`0
			<input type="submit" value="Abgeben"></form>
			'.focus_form_element('gefallenspende');
			output($str_output);

			addnav('',$str_filename.'?op=oldspirit&act=given');
			addnav('Zurück');
			addnav('In die Halle',$str_filename);
			break;

		//Wenn der User dem alten Geist gefallen gibt!
		case 'given':

			//Der Attributwert von 'oldspirit' wird aus der DB gelesen
			$arr_given = user_get_aei('oldspirit',$session['user']['acctid']);

			//Der Wert (Gefallen) aus dem Form wird in eine Variable übernommen
			$gefallen = $_POST['gefallenspende'];

			//Wenn der User bereits Gefallen bekommen oder gegeben hat, dann...
			if($arr_given['oldspirit']>0)
			{
			$str_output .= 'Du solltest heute jeglichen Versuch mit Ramius zu Reden unterlassen.
			Du hast dein Glück bereits mehr herausgefordert als gut für dich ist.
			Versuche es später wieder.';
			}
			//Wenn der User mehr gefallen geben will als er hat, dann...
			else if($gefallen > $session['user']['deathpower'])
			{
			switch(e_rand(1,6))
			{
				case 1:
				case 2:
				case 3:

					//Der User verliert 20 Gefallen
					$str_output .= 'Du bringst Ramius gerade deine Bitte vor, als dieser dich andonnert:`n`n
					`$Unwürdiger! Soviel ist Deine Seele nicht wert! Für diesen Betrug sollst du hart bestraft werden. Tritt mir aus den Augen!`n`n
					`#Du sinkst ein ganzes Stück in Ramius Gunst.';

					$session['user']['deathpower'] -= 20;
					if($session['user']['deathpower']< 0)
					{
						$session['user']['deathpower'] = 0;
					}
					break;

				case 4:
				case 5:

					//Der User verliert wenn vorhanden eine Grabrunde...
					$str_output .= 'Du bringst Ramius gerade deine Bitte vor, als dieser dich andonnert:`n`n
					`$Unwürdiger! Soviel ist Deine Seele nicht wert! Für diesen Betrug sollst du hart bestraft werden. Tritt mir aus den Augen!`n`n';

					if($session['user']['gravefights']>0)
					{
						$str_output .= '`#Du verlierst eine Folterrunde.';

						$session['user']['gravefights'] -= 1;
					}
					//sonst 25 Gefallen
					else
					{
						$str_output .= '`#Du verlierst 25 Gefallen.';

						$session['user']['deathpower'] -= 25;
						if($session['user']['deathpower']< 0)
						{
						$session['user']['deathpower'] = 0;
						}
					}
					break;

				case 6:

					//Der User verliert einen Charmepunkt
					$str_output .= 'Du bringst Ramius gerade deine Bitte vor, als dieser dich andonnert:`n`n
					`$Unwürdiger! Soviel ist Deine Seele nicht wert! Für diesen Betrug sollst du hart bestraft werden. Tritt mir aus den Augen!`n`n
					`#Du verlierst einen Charmepunkt.';

					$session['user']['charm'] -= 1;
					if($session['user']['charm']< 0)
					{
						$session['user']['charm'] = 0;
					}
					break;
			}
			}
			//Wenn der User mehr Gefallen als die zulässigen 10 geben will, dann...
			elseif($gefallen > 10)//UMGA
			{
				$str_output .= 'Ramius schaut dich lachend an.`n`n
				`$Du Wicht, so hoch wird dieser alte Geist in meiner Gunst nie steigen dürfen. Verschwinde!`n`n
				`0Als du gehst siehst du hinter einem Felsen eine Seele die dir zuwinkt. Du näherst dich ihr vorsichtig.`n
				Probiere es mit '.$max_give.' Gefallen, das hat er von dem letzten der das Gleiche versucht hat angenommen.';
			}
			//Wenn der User 0 Gefallen geben will, dann...
			elseif($gefallen == 0)
			{
			$str_output .= 'Als du zu Ramius gehst hörst du bereits eine Stimme aus der Ferne die zu dir schallt:`n`n
			`$Deine Seele ist nichts wert, kehre um...`n`n
			`0Enttäuscht ziehst du wieder ab.';
			}
			//Wenn der User eine negative Zahl eingegeben hat, dann...
			elseif($gefallen < 0)
			{
			$str_output .= 'Du bringst Ramius deine komische Bitte vor. Dieser überlegt einen Moment und es scheint als wolle er dich jeden Moment zerdrücken, doch dann spricht er mit donnernder Stimme:`n`n
			`$Du willst den alten Geist also Betrügen. Du sollst wissen, dass ich über jeden Deiner Schritte bescheid weiß. Für diesen Frevel sollst Du büßen!`n`n
			`0Schnell rennst du ohne ein Wort zu sagen davon, nicht dass es sich Ramius doch noch anders überlegt und dich auch noch zerdrückt...';

			$session['user']['deathpower'] += $gefallen;
			if($session['user']['deathpower']< 0)
			{
				$session['user']['deathpower'] = 0;
			}
			}
			//Wenn die Gefallen des Geistes + die Gefallen des Users die Grenze überschreiten,dann...
			elseif(($gefallen + getsetting('oldspiritamount','0')) > $max_oldspirit)
			{
				$str_output .= 'Ramius schaut dich durchdringend an und sagt dann mit donnernder Stimme:`n`n
				`$Der alte Geist braucht keinen Zuspruch durch DICH!`n`n
				`0Ängstlich ziehst du den Kopf ein und verdrückst dich ganz schnell um Ramius nicht noch herauszufordern.';
			}
			//Wenn alles in Ordnung ist dann werden die Gefallen auf den Geist übertragen
			else
			{
			$str_output .= 'Ramius schaut dich prüfend an und nickt dann.`n`n
			`$Ich denke ich habe den alten Geist falsch eingeschätzt. Er ist durch Dich in meiner Gunst gestiegen. Und jetzt verschwinde.`n`n
			`0Du bist dankbar und fühlst dich guter Dinge. Schnell und ohne zurückzublicken gehst du weiter.';

			savesetting('oldspiritamount',getsetting('oldspiritamount','0')+$gefallen);
			$session['user']['deathpower'] -= $gefallen;
			}
			output($str_output);

			addnav('Weiter',$str_filename);

			//Das Abgeben der Gefallen geht nur einmal, auch wenn eine falsche Eingabe (bewusst oder unbewusst) gemacht wurde
			user_set_aei(array('oldspirit'=>1),$session['user']['acctid']);
			break;
	}
	break;
}

page_footer();
?>