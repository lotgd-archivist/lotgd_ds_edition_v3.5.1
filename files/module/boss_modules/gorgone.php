<?php
/**
 * Bossgegner Medusa die Gorgone, entstanden 2009 auf einer Dienstreise in Athen...wo sonst kann man so tolle Ideen bekommen
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor.de
 */


/**
 * Die Nav darf nur angezeigt werden, wenn...
 */
function boss_check_additional_nav_preconditions()
{
	global $session;
	
	
}

function boss_do_intro()
{
	global $g_arr_current_boss,$session,$battle,$badguy,$g_str_base_file,$battle;
    if(!isset($str_output))$str_output='';
	switch($_GET['act'])
	{
		case '':
			{
				output($str_output);
				break;
			}
		case 'intro':
			{
				addnav('Ziehe den Fang an Land',$g_str_base_file.'&act=fight');
				addnav('Lass ihn in Ruhe',$g_str_base_file.'&act=end');
				
				
				$session['user']['seendragon']=1;
				output($str_output);
				break;
			}
		case 'end':
			{
				
				addnav('S?Das Seeufer','fish.php');
				output($str_output);
				break;
			}
		case 'fight':
			{
				
				
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
	/*
	Mir fiel kein gescheiter Anfang zu einer Autochallenge durch Jormungandr ein, 
	da der Witz darin besteht mit einem Stierkopf zu fischen. Falls einer eine Idee hat, bitte gern!
	*/
	return true;
}

function boss_do_epilogue()
{
	global $g_str_base_file, $g_arr_current_boss, $session;
    if(!isset($str_output))$str_output='';
	music_set('drachenkill',0);

	switch ($_GET['act'])
	{
		case '':
			{
				$str_output = get_title('Sieg!');
				
				addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				break;
			}
		case 'wakeup':
			{
				$str_output .= get_title('Erwache!');
				$str_output .= 'Du erwachst umgeben von Bäumen. In der Nähe hörst du die Geräusche einer Stadt.
				Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von gefährlichen Kreaturen, die die Gegend heimsuchen.
				Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages diesen abscheulichen Wesen stellst.
				`n`n`^Du bist von nun an bekannt als `&'.$session['user']['name'].'`^!!
				`n`n`&Weil du '.$session['user']['dragonkills'].' Heldentaten vollbracht hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du dir verdient oder erkauft hast.
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über Jörmungandr!`n';

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
	output('Der schleimige Leib der Kreatur verhindert deine Flucht! Dir bleibt nichts anderes übrig, als mit aller Macht zu versuchen, den riesigen Leib zu bekämpfen.');
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

	headoutput(get_title('`@Sieg!').'`&Mit einem gewaltigen Tosen verschwindet der Leib Jörmungandrs in den Fluten des Sees. Mit zitternden Gliedern stehst du an der Reling des kleinen Fischerbootes und spähst ungläubig auf den wieder ruhigen See hinaus. Du hast es tatsächlich geschafft. Wenn du es könntest, würdest du vor Freude Donner und Blitze werfen.
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

	$str_news = '`&'.$session['user']['name'].'`& hat Jörmungandr, den Weltumschlingenden, zurückgetrieben!';

	return $str_news;
}
function boss_get_defeat_news_text()
{
	global $session;

	$str_news = '`%'.$session['user']['name'].'`5 wurde von Jörmungandr, dem Weltumschlingenden, gefressen.';

	return $str_news;
}

?>