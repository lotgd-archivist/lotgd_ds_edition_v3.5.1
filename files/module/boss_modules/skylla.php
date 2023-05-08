<?php
/**
 * Bossgegner Skylla
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3
 * @author dragonslayer
 */
/*

'id' => 'skylla', //ID des Bossgegners
'enabled' => true, //Eingeschaltet
'name' => '`BSkylla', //Name mit Farbcode
'inc' => 'skylla.php', //Includedatei mit dem nötigen Code
'min_dk' => 40, //Wieviele DKs sind nötig damit der Boss erscheint
'dk_delta' => 1, //Alle wieviel DK erscheint der Gegner
'min_exp' => 0, //Wieviele Erfahrungspunkte muss der User mindestens haben
'min_lvl' => 15, //Welches Level muss der User mindestens besitzen
'additional_nav_preconditions' => true, //Soll Funktion additional_nav_preconditions aufgerufen werden
//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
'multiple_challenge'=>false, //Darf der Drache mehrfach am Tag herausgefordert werden
'nav_name' => 'O?Dem Meer ein Opfer darbringen', //Wie lautet der Name der Navigation
'sex' => 1, //Geschlecht 0=männlich, 1=weiblich
'img' => null, //Pfad zu einem Bild dass den Boss zeigt (optional)
'level' => 18, //Level des Drachen
'weapon' => 'Schnappende Köpfe', //Waffenname
'attack' => 60, //Attacklevel (wird noch abhängig vom User gebufft)
'defense' => 60, //Defenselevel (wird noch abhängig vom User gebufft)
'hitpoints' => 300, //Mindesthitpoints
'gain_reputation'=> 3, //Wieviele Ansehenpunkte werden gewonnen beim Sieg
'defeat_reputation'=> 3, //Wieviele Ansehenpunkte werden verloren beim Tod
'gain_charm'=>6, //Wieviele Charmepunkte werden gewonnen beim Sieg
'defeat_charm'=>6, //Wieviele Charmepunkte werden verloren beim Tod
*/

/**
 * Die Nav darf nur angezeigt werden, wenn der User Muschelketten bei sich hat,
 * die er als Köder nehmen kann
 */
function boss_check_additional_nav_preconditions()
{
	global $session,$Char;
	
	if((item_count('owner = "'.$session['user']['acctid'].'" AND tpl_id = "muschelkette"') >0 )&&(($Char->dragonkills-6)%10==0))
	{
		return true;
	}
	else 
	{
		return false;
	}
}

function boss_do_intro()
{
	global $g_arr_current_boss,$session,$battle,$badguy,$g_str_base_file,$battle;
    if(!isset($str_output))$str_output='';
	switch($_GET['act'])
	{
		case '':
			{
				if(empty($_GET['item_id']) || intval($_GET['item_id']) == 0)
				{
					$str_output .= get_title('`BSkylla').'`BDu machst einen Spaziergang am Strand um genießt den Seewind, der dir durchs Haar pfeift. Deine Augen sind auf den Sand vor deinen Füßen gerichtet, denn vielleicht spült eine Welle ja die eine oder andere Kostbarkeit an den Strand. Nur Meeresvögel leisten dir Gesellschaft, während du hin und wieder einen Stein umdrehst oder eine Muschel näher betrachtest. `n
Schließlich kommt dir der Gedanke, dass wenn du der See etwas opferst, sie dich ja dafür eventuell belohnt?`n`n';
					$sql_res = item_list_get('owner = "'.$session['user']['acctid'].'" AND tpl_id = "muschelkette"');

					if(db_num_rows($sql_res)<1)
					{
						$str_output .= "`BDu findest jedoch nichts angebrachtes und wirfst enttäuscht ein Stück angeschwemmtes Holz zurück ins Meer und wartest, doch leider passiert rein gar nichts. Nun ja, das wäre aber auch zu schön gewesen.";
					}
					else
					{
						$str_output .= "`BWillst du vielleicht eine Muschelkette ins Meer werfen?";
						while ($arr_item = db_fetch_assoc($sql_res))
						{
							$str_output .= '`n`n'.create_lnk($arr_item['name'],$g_str_base_file.'&item_id='.$arr_item['id']);
						}
					}
					addnav('S?Zurück zum Strand','hafen.php?op=strand');
				}
				else
				{
					$arr_item = item_get('id='.$_GET['item_id']);
					
						$str_output .= get_title('`BSkylla').'`BSchließlich entdeckst du tatsächlich eine Kette aus Muscheln, die du an diesem Strand gefunden hast, weshalb es dir richtig erscheint, sie wieder dorthin zu bringen, wo sie ursprünglich hingehörte.`0`n`n';
						item_delete('id='.$_GET['item_id']);
						addnav('Die Kette werfen',$g_str_base_file.'&act=intro');
					
				}
				output($str_output);
				break;
			}
		case 'intro':
			{
				addnav('Stelle dich dem Feind',$g_str_base_file.'&act=fight');
				addnav('Verschwinde lieber',$g_str_base_file.'&act=end');
				
				$str_output .= get_title('`BSkylla').'`BDu wirfst die Kette ins Meer und weckst damit scheinbar schlafende Monster, denn anders kann das Wesen, das sich langsam aus den Wogen des Meeres erhebt, kaum genannt werden.`n
Die sechs Köpfe, welche auf dem geschuppten Leib sitzen, drehen sich langsam in deine Richtung. Dies muss Skylla sein, von der Seefahrer hinter vorgehaltener Hand abends in den Tavernen erzählen. Diesem Seemannsgarn hast du immer mit einem verächtlichen Lächeln gelauscht, doch jetzt wünscht du dir fast, besser zugehört zu haben, denn es stellt sich die Frage, bleibst du um diesen Albtraum zu bekämpfen oder versuchst du zu fliehen?`n`n';
				$session['user']['seendragon']=1;
				output($str_output);
				break;
			}
		case 'end':
			{
				$str_output .= get_title('`BSyklla').'`BSchnell lässt du das Meer und all seine Bewohner hinter sich, sollen doch andere Helden dieses Monster erschlagen, du suchst dir lieber eine Herausforderung, die unter vier Augen ausgetragen wird.`n';
				addnav('S?Der Strand','hafen.php?op=strand');
				output($str_output);
				break;
			}
		case 'fight':
			{
				$str_output .= get_title('`BSkylla').'
				`BSelbstbewusst greifst du nach deinem Schwert und stellst dich diesem Gegner. Ein leichter Kampf wird es nicht, dies ist dir bewusst. Nicht nur, das mehrere Köpfe nach dir schnappen, auch das Meer, in das du gewatet bist um in Reichweite des Wesens zu kommen, behindert sich. Nur gut, das die Brandung an diesem Teilabschnitt des Strandes nicht allzu stark ist. Immer wieder täuschst du Scheinangriffe vor, um Skylla weiter aus ihrem natürlichen Element zu locken und so einen Vorteil zu erringen.`n`n';
				
				output($str_output);

				$session['user']['seendragon']=1;
				$badguy = boss_get_badguy_array($g_arr_current_boss);
				$session['user']['badguy']=utf8_serialize($badguy);
				$battle=true;
				break;
			}
	}
}

function boss_do_autochallenge()
{
	return true;
}

function boss_do_epilogue()
{
	global $g_str_base_file, $g_arr_current_boss, $session;

	music_set('drachenkill',0);
    if(!isset($str_output))$str_output='';
	switch ($_GET['act'])
	{
		case '':
			{
				$str_output = get_title('`BVom Untergang des Monsters!');
				$str_output .= '`BMit einem vernichtenden Schlag trennst du Skylla den letzten Kopf vom Körper und siehst, wie das Wesen langsam im Wasser versinkt. Scheinbar hast du wirklich gesiegt!`n`n';
				
								if ($_GET['flawless'])
									{
				  $str_output .= 'Mit einem Lächeln auf den Lippen wäschst du das Blut von deiner Klinge und entdeckst so eine bläulich schimmernde `fPerle`B, die du sogleich in deinen Beutel fallen lässt um sie später in Ruhe zu untersuchen.`n
									Noch in Gedanken bei deinem heldenhaften Sieg, welchen sicherlich einige Barden besingen werden, entgeht dir eine hohe Welle, die dich nun unvorbereitet trifft. Das Schwert entgleitet deiner Hand, als du unter Wasser gedrückt und gegen einen spitzen Felsen geschleudert wirst.`n
									Kurz bevor du das Bewusstsein verlierst, streift dich etwas Schuppiges auf dem Weg zurück ins tiefe Meer...';
				  //item vergeben
				  item_add($session['user']['acctid'],'perl');
				  }                
				
								$str_output .= '`nBei einem letzten Blick vermeinst du einen von Skyllas Köpfen kurz auftauchen zu sehen. Dann wird es endgültig schwarz um dich und als du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern, was geschehen ist.`n`n';
				addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				break;
			}
		case 'wakeup':
			{
				$str_output .= get_title('Erwache!');
				$str_output .= 'Du erwachst umgeben von Bäumen. In der Nähe hörst du die Geräusche eines Dorfs.
				Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von gefährlichen Kreaturen, die die Gegend heimsuchen.
				Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages diesen abscheulichen Wesen stellst.
				`n`n`^Du bist von nun an bekannt als `&'.$session['user']['name'].'`^!!
				`n`n`&Weil du '.$session['user']['dragonkills'].' Heldentaten vollbracht hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du dir verdient oder erkauft hast.
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über Skylla!`n';

				addnav('Es ist ein neuer Tag','news.php');

				// Knappe laden und steigern
				$rowk = get_disciple();
				if ($rowk['state']>0)
				{
					$str_output .= disciple_levelup($rowk);
					$session['bufflist'] = array();
				}
				break;
			}
	}
	output($str_output);
}

function boss_do_run()
{
	global $battle;
	$battle = true;
	output('Die Köpfe der Kreatur verhindert deine Flucht! Dir bleibt nichts anderes übrig, als mit aller Macht zu versuchen, dir den Weg freizukämpfen.`n');
}

function boss_do_fight()
{
	global $battle;
	$battle = true;
}

function boss_do_victory()
{
	global $g_str_base_file,$badguy,$flawless,$session;

	boss_calc_victory_bonus();
	

	music_set('drachenkill',0);

	$flawless = 0;
	if ($badguy['diddamage'] != 1)
	{
		$flawless = 1;
	}
	addnews('`#'.$session['user']['login'].'`# hat sich den Titel `&'.$session['user']['title'].'`# für die `^'.$session['user']['dragonkills'].'`#te erfolgreiche Heldentat verdient!');

	headoutput(get_title('`@Sieg!').'`BMit einem vernichtenden Schlag trennst du Skylla den letzten Kopf vom Körper und siehst, wie das Wesen langsam im Wasser versinkt. Scheinbar hast du sie wirklich gesiegt!
	`n`n<hr>`n');
	addnav('Weiter',$g_str_base_file.'&op=epilogue&flawless='.$flawless);
}

function boss_do_flawless_victory()
{
	boss_calc_flawless_victory_bonus();
}

function boss_do_defeat()
{
	global $g_arr_current_boss;
	headoutput(get_title('`$Niederlage!').'`%'.$g_arr_current_boss['name'].'`& hat dich verschlungen! Ob Ramius dich hier drin wohl überhaupt finden wird?`n
	Du kannst morgen wieder kämpfen.`0
	`n`n<hr>`n');

	boss_calc_defeat();

	addnav('Tägliche News','news.php');

}

function boss_get_victory_news_text()
{
	global $session;

	$str_news = '`&'.$session['user']['name'].'`& hat das, als `BSkylla `&bekannte Meeresungeheuer besiegt. Über alle Länder freuen sich die Völker!';

	return $str_news;
}
function boss_get_defeat_news_text()
{
	global $session;

	$str_news = '`%'.$session['user']['name'].'`5 wurde vom Meeresungeheuer `BSkylla `5gefressen.';

	return $str_news;
}

?>