<?php
/**
 * Bossgegner Lichkönig
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3.42
 * @author Dragonslayer
 * @copyright Dragonslayerr for Atrahor
 */


/**
 * Die Nav darf nur angezeigt werden wenn der User das Tauschquest überwunden hat oder mit einem 1:5 Zufall
 */
function boss_check_additional_nav_preconditions()
{
	global $session;
	
	return item_count('owner='.$session['user']['acctid'].' AND tpl_id="analloni_s"  AND deposit1=0')>0;
}

function boss_do_intro()
{
	global $g_arr_current_boss,$session,$battle,$badguy,$g_str_base_file,$battle;
    if(!isset($str_output))$str_output='';
	switch($_GET['act'])
	{
		case '':
			{
				$str_output .= get_title('`)Der Lichkönig').'`e
				Du beginnst zu frösteln. Mit jedem Schritt, den du dem Dachboden näher kommst, weicht die Wärme aus dem Gemäuer einer trockenen, unnatürlichen Kälte. `7Dein Atem, sichtbar als grauer Dampf vor deinem Mund, geht schnell, denn die vielen Stufen fordern ihren Tribut. Doch stetig gehst du deinen Weg, denn das Ziel ist klar. Es ist der Lichkönig, der dich erwartet. `)Hoch oben im Turm bestreitet er sein unheiliges Dasein, irgendwo zwischen Leben und Tod.`n`n
				`)Die Tür zum Dachboden ist mit einer dünnen, weißen Schicht Eis überzogen. Noch kälter ist jedoch das Gefühl, welches dich überkommt als du die Tür aufschieben möchtest. `(Beinahe alles um dich herum sagt dir es nicht zu tun, doch das kleine Anallôni-Amulett in deiner Tasche vibriert aufmunternd und gibt dir die nötige Kraft.
				
				';
				addnav('Ja, die Tür öffnen!',$g_str_base_file.'&act=intro');
				addnav('Nein, schnell wieder runter','forest_rpg_places.php?op=inside_abbey');
				output($str_output);
				break;
			}
		case 'intro':
			{	
				$str_output .= get_title('`)Der Lichkönig').'`(
				Du betrittst eine schmucklose, leere Kammer unter dem Dach des Schlossturms. Ein einziges verstaubtes Fenster spendet klägliches Licht und beleuchtet den staubigen Fussboden, auf dem schon seit Jahren niemand mehr wandelte. Kleine Staubflöckchen tanzen im hereinfallenden Licht und für einen Moment könnte man die Szene tatsächlich genießen, wäre da nicht eine stockfinstere Ecke am entfernten Ende des Raumes, so als würde dieser versuchen dir ihren Inhalt um jeden Preis vorzuenthalten. Leider weißt du nur zu gut was sich dort wohl befinden wird und trittst einen Schritt in den Raum und seine unnatürliche Stille hinein.`n`n
				`)Das Klacken deines Absatzes auf den uralten Dielen durchbricht die Ruhe schlagartig, Staub wirbelt unter deinem Hacken auf und aus der dunklen Ecke starren dich zwei glitzernd schwarze Augen an, die zu einem unendlich alten Körper gehören. Dein Herz wird bang, doch Dein Körper zieht aus dem noch immer sanft vibrierenden Anallôni-Amulett eine wärmende Kraft. Du ziehst das Kleinod aus deiner Tasche hervor und lässt es auf deiner offenen Hand liegen. `7Beinahe augenblicklich beginnt es den Raum um dich herum zu erwärmen und zu erhellen, so als fechte es einen unsichtbaren Kampf gegen die Kälte und Dunkelheit des Lichkönigs.`n`n
				`eImmer weiter zurück drängt das warme Licht die Dunkelheit und die Schatten aus jedem Winkel des Dachbodens, bis diese schließlich komplett verschwinden. Doch als auch die letzte Dunkelheit weicht, siehst du nicht, was du erwartet hast. Die Ecke ist leer! Mit grausiger Erkenntnis bemerkst du deinen Fehler als hinter dir die Tür zuschlägt, eine schneidende Kälte dir das Anallôni-Amulett aus der Hand stösst, so dass dieses an der entfernten Wand in tausend Splitter zerschlägt!`n`n
				
				`b`$Wappne dich für den Kampf`0`b';
				output($str_output);
				$badguy = boss_get_badguy_array($g_arr_current_boss);
				//Ein Anallôni-Amulett löschen
				item_delete('owner='.$session['user']['acctid'].' AND tpl_id="analloni_s" AND deposit1=0',1);
				$session['user']['badguy']=utf8_serialize($badguy);
				$battle=true;
				$session['user']['seendragon']=1;
				break;
			}
	}
}

function boss_do_autochallenge()
{
	/**
	 * Kein Autochallenge durch den Lichkönig 
	 */
	return true;
}

function boss_do_epilogue()
{
	global $g_str_base_file, $g_arr_current_boss, $session;

	music_set('drachenkill',0);

	switch ($_GET['act'])
	{
		case '':
			{
				$str_output = get_title('`(Tod und Leben eines Königs!');
				$str_output .= '`)Als deine Waffe ein letztes Mal auf das unheilige Wesen hernieder fährt, hörst du einen befreiten Seufzer. Der Körper fällt in sich zusammen und zurück bleiben nur die Lumpen, die das Wesen am Leibe trug.`n
				Doch als Du dich gerade zum gehen wenden willst, spürst du mit einem Male eine Präsenz im Raum, die dich inne halten lässt.`n
				`e"Anallôni-Steine, sehr einfallsreich, '.($session['user']['sex'] == 0?'mein Lieber':'meine Liebe').'."`) Es handelt sich um eine Stimme, die dir nur allzusehr vertraut ist und noch immer Angst und Ehrfurcht zugleich lehrt. Ramius, der Gott der Toten persönlich, steht hier in diesem Raum und betrachtet die Überreste deines Machwerkes. `e"Leider darf ich dies nicht zulassen, denn der gute König Wenceslas hat einen gar vorzüglichen Vertrag mit mir abgeschlossen... und dieser wird nicht gebrochen!"`) `n`n
				Mit offenem Mund musst du erkennen, wie das noch wenige Sekunden zuvor in sich zusammengefallene Gebilde beginnt sich wieder zusammenzusetzen. Langsam bauen sich Arme, Beine und Kopf auf, der Rücken biegt sich gerade und die Kleidung hebt sich erneut an den unheiligen Leib. Schlussendlich öffnet der Lichkönig seine Augen. Sie sind schwarz und tief wie eh und je und sofort bricht auch die Kälte wieder über dich herein, die bereits zuvor auf seiner Umgebung lastete.`n`n
				`e"Du verstehst gewiss, dass ich keinem lebenden Wesen gestatten darf zu erfahren was sich hier zugetragen hat, nicht wahr?" `)Dass dies leider keine Frage wahr, merkst du recht schnell, denn als du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern, was geschehen ist.`n';
				addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				break;
			}
		case 'wakeup':
			{
				$str_output = get_title('Erwache!');
				$str_output .= 'Du erwachst umgeben von Bäumen. In der Nähe hörst du die Geräusche einer Stadt.
				Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von gefährlichen Kreaturen, die die Gegend heimsuchen.
				Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages diesen abscheulichen Wesen stellst.
				`n`n`^Du bist von nun an bekannt als `&'.$session['user']['name'].'`^!!
				`n`n`&Weil du '.$session['user']['dragonkills'].' Heldentaten vollbracht hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du dir verdient oder erkauft hast.
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über den Lichkönig!`n`n';

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
	output('So sehr du auch gehetzt um dich blickst, du vermagst den Ausgang aus der Dunkelheit nicht mehr auszumachen. Dir bleibt nichts anderes übrig, als mit aller Macht zu versuchen, den Lichkönig zu bekämpfen.');
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
//Dieser Text ist noch fertig anzupassen!
	headoutput(get_title('`@Sieg!').'`&Der unheilige Leib des Lichkönigs fällt in sich zusammen und zurück bleiben nur die Lumpen, die seinen einst königlichen Körper umhüllten. Du hast es tatsächlich geschafft.
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
	headoutput(get_title('Niederlage').$g_arr_current_boss['name'].'`& hat sich deiner Seele bemächtigt! Ramius empfängt dich, beinahe hämisch grinsend.`n
			`4Du hast dein ganzes Gold verloren!`n
			Du kannst morgen wieder kämpfen.`0
	`n`n<hr>`n');

	boss_calc_defeat();

	addnav('Tägliche News','news.php');

}

function boss_get_victory_news_text()
{
	global $session;

	$str_news = '`&'.$session['user']['name'].'`& hat den `(Lichkönig `&befreit!';

	return $str_news;
}

function boss_get_defeat_news_text()
{
	global $session;

	$str_news = '`%'.$session['user']['name'].'`5 überließ dem `(Lichkönig `5seine Seele.';

	return $str_news;
}

?>