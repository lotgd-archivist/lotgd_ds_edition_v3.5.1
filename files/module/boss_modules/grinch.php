<?php
/**
 * Bossgegner Der Grinch
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3.42
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 */


/**
 * Die Nav darf nur angezeigt werden wenn wir Weihnachten haben und der user ein Geschenk dabei hat
 */
function boss_check_additional_nav_preconditions()
{
	global $Char;
	return (isBetween(1,date('j'),26) && date('n') == 12 && item_count('tpl_id = "giftforagrinch" AND owner='.$Char->acctid) > 0);
}

function boss_do_intro()
{
	global $g_arr_current_boss,$session,$Char,$battle,$badguy,$g_str_base_file,$battle;
    if(!isset($str_output))$str_output='';
	switch($_GET['act'])
	{
		case '':
			{
				$str_output .= get_title('`@Der Grinch').'`FDu atmest die kühle, frische Luft ein und all deine Sorgen aus. In einer Welt, nicht unweit der deinigen feiert man gerade ein Fest welches sich Weihnachten nennt. Und auch wenn du nicht wirklich viel damit am Hut haben solltest, so stellst du doch fest, dass man sich dem Charme einer idyllischen Winterlandschaft nicht widersetzen kann. Der frische Schnee knirscht unter deinen Schritten und weit und breit hinterlässt du die ersten und einzigen Spuren. Dein Spaziergang führt dich immer weiter den Berg hinauf, denn von dort oben hat man zur Dämmerung einen malerischen Blick über das Tal und deine Stadt. Irgendwann erreichst du einen kleinen windgeschützten Vorsprung, der von den letzten Strahlen der Sonne erhellt wird und entschliesst dich zu bleiben. Du betrachtest die in der Ferne rauchenden Schornsteine, den Schnee auf den Dächern und Bäumen und den weich von rot nach lila verfärbten Himmel. Als die Töne einiger Glocken von drunten zur dir herauf Schellen kannst du es nicht lassen und beginnst ein weihnachtliches Lied zu summen und deine Beine im Takt baumeln zu lassen. Doch leider wirst du jäh in deinem Tun gestört, als du ein lautes Krakeelen von über dir vernimmst. `n`n
				"`@WAS IST DENN DAS FÜR EIN GRÄSSLICHER GESANG?`F"`n`n
				Verdutzt blickst du weiter den Berg hinauf und siehst etwa 100 Meter über dir eine Einbuchtung die wie der Zugang zu einer Höhle aussieht.';
				
				addnav('Das schaue ich mir an!',$g_str_base_file.'&act=intro');
				addnav('Pöh, dann gehe ich eben!','nebelgebirge.php');
				output($str_output);
				break;
			}
		case 'intro':
			{	
				$str_output .= get_title('`@Der Grinch').'`FInteressiert rappelst du dich auf und läufst zu der Einbuchtung empor, die sich bei näherer Betrachtung tatsächlich als eine Höhle entpuppt. Du rufst kurz ein "Huhu" hinein und hoffst auf ein Echo. Doch stattdessen ertönt nur ein wenig freundliches "`@VERPISS DICH`F". Der plötzlich in dir geborene Sittenwächter kann das natürlich nicht auf sich sitzen lassen und anstatt einfach die Klappe zu halten antwortest du schnippisch. "`*Aber aber, solch schlechte Stimmung zur Weihnachtszeit?`F" - "`@Weihnachtszeit? WEIHNACHTSZEIT?!? Ich geb dir gleich Weihnachtszeit!F" ist die wenig überraschende Antwort und schon fliegt dir ein grünes Fellbündel entgegen und versucht dir jegliche Weihnachtsfreude aus dem Gesicht zu prügeln. Herzlichen Glückwunsch, du hast den Grinch gefunden und genau dieser versucht dich auf die unweihnachtliche Seite der Macht zu bekehren!`n`n
				
				`$`bWappne dich für den Kampf`b`0`n`n';
				output($str_output);
				$badguy = boss_get_badguy_array($g_arr_current_boss);

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
	 * Kein Autochallenge durch den Grinch
	 */
	return true;
}

function boss_do_epilogue()
{
	global $g_str_base_file, $g_arr_current_boss, $session, $Char;

	music_set('drachenkill',0);
    if(!isset($str_output))$str_output='';
	switch ($_GET['act'])
	{
		case '':
			{
				$str_output = get_title('`@Der Christmasgrinch');
				$str_output .= '`FMit aller Inbrunst und der Kraft der zwei Fäuste verteidigst du das Weihnachtsfest gegen den fiesen grünen Kobold und erklärst mit jedem Schlag sehr eindringlich, worum es eigentlich geht. Um Familie `$*Peng*`F, Geselligkeit und Freundschaft `4*Puff*`F. Freude zu schenken und vor allem GESCHENKE `A*Ouuch*`F Und plötzlich...hast du eine Eingebung und lässt von ihm ab. Wenn du ihm schon nicht die wahre Bedeutung des Weihnachtsfestes einprügeln kannst, eventuell hilft ja dann ein wenig guter alter Kommerz? Ein Geschenk für den Grinch! Du hast doch da noch den Tinnef vom alten Zausel von der Eiche.';
				
				addnav('Der Tinnef vom Alten',$g_str_base_file.'&op=epilogue&act=wakeup&subact=giftforagrinch');	
				addnav('Der kriegt NIX',$g_str_base_file.'&op=epilogue&act=wakeup&subact=nogiftforagrinch');	
				
				break;
			}
		case 'wakeup':
			{
				$str_output .= get_title('Erwache!');
				
				if($_GET['subact'] == 'nogiftforagrinch')
				{
					$str_output .= '`FPfff, soweit kommts noch, auch wenn das Geschenk noch so hässlich ist, DER kriegt es gewiss nicht, der hasst weihnachten und ist hässlich und grün und überhaupt. Nä, der soll hier oben mal schön sein eigenes Ding machen. Angewidert drehst du dich um und lässt das grüne Fellding allein zurück. Sauer über soviel Feiertagsmuffelei machst du dich auf deinen Weg zurück ins Tal. Du stapfst schnellen Schrittes voran und im Dämmerlicht des schwindenden Tages kommt es natürlich wie es kommen muss, du stolperst über eine Wurzel und fällst. Zwar landest du weich im Schnee, nur leider wird dein Sturz nicht gerade gebremst. Und so polterst du fröhlich Salto um Salto als kleine Lawine den Berg hinunter.`n`n';
				}
				else
				{
					$str_output .= '`FHmm, und was quatschte der doch gleich vom Geist der Weihnacht, der einem ins Gesicht springt? Könnte es vielleicht sein, dass dieses grüne Fellbündel hier zu deinen Füßen...Du greifst kurz entschlossen in deinen Beutel, holst das kleine Präsent heraus und hälst es dem Grinch entgegen. "`*Ich weiß zwar nicht genau wieso ich es tue, aber jeder sollte sich an Weihnachten über irgendetwas freuen können, auch du. Hier, das schenke ich dir!`F" Der Grinch schnuppert kurz misstrauisch, reißt dann aber flott das Geschenkpapier herunter und quiekt plötzlich in einer Tonhöhe, die eher an einen Boygroup-Fan auf einem Abschiedskonzert der Lieblingsband erinnert als an das grummelige, aggressive Ding als das du ihn kennengelernt hast. "`@Ohhhhhh, das sind ja ein Paar Kuhschwanzlaminellenwärmer, die hab ich mir ja schon ewig gewünscht!`F"...`n`n
					Und somit brach das Eis. Sowohl zwischen dem Grinch und dem Weihnachtsfest, als auch zwischen dir und dem Grinch. Leider ist das wortwörtlich zu nehmen und so rutschst du, begleitet von heftigen Dankesworten des Grinches, den Berg hinunter.`n';

					item_delete('tpl_id = "giftforagrinch" AND owner='.$Char->acctid,1);
				}				
				
				$str_output .= 'Du erwachst völlig durchgefroren Rande eines kleinen Bachs, umgeben von winterlich verschneiten Bäumen. Du hast keine Ahnung, wie du hierher gekommen bist, noch was du hier sollst. In der Nähe hörst du die Geräusche einer Stadt und einige Töne, die weihnachtliche Stimmung verbreiten.
				Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von gefährlichen Kreaturen, die die Gegend heimsuchen.
				Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages diesen abscheulichen Wesen stellst.
				`n`n`^Du bist von nun an bekannt als `&'.$session['user']['name'].'`^!!
				`n`n`&Weil du '.$session['user']['dragonkills'].' Heldentaten vollbracht hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du dir verdient oder erkauft hast.
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über den Grinch!`n`n';

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
	output('Sobald du auch nur versuchst dich aus dem Staub zu machen zieht dich der Grinch grunzend in seine Höhle zurück und garniert dich mit einigen Tritten!');
}

function boss_do_fight()
{
	global $battle;
	$battle = true;
}

function boss_do_victory()
{
	global $g_str_base_file,$badguy,$flawless,$session,$Char;

	boss_calc_victory_bonus();
	

	music_set('drachenkill',0);

	$flawless = 0;
	if ($badguy['diddamage'] != 1)
	{
		$flawless = 1;
	}
	addnews('`#'.$session['user']['login'].'`# hat sich den Titel `&'.$session['user']['title'].'`# für die `^'.$session['user']['dragonkills'].'`#te erfolgreiche Heldentat verdient!');

	headoutput(get_title('`@Sieg!').'`FDu hast dem Grinch eine ordentliche Lektion erteilt`n`n<hr>`n');
	addnav('Weiter',$g_str_base_file.'&op=epilogue&flawless='.$flawless);
}

function boss_do_flawless_victory()
{
	boss_calc_flawless_victory_bonus();
}

function boss_do_defeat()
{
	global $g_arr_current_boss;
	headoutput(get_title('Niederlage').$g_arr_current_boss['name'].'`@ hat dich windelweich geprügelt und dich dazu gezwungen zuzugeben, dass es den Weihnachtsmann gar nicht gibt, bevor er dich gelben Schnee hat fressen lassen. Selbst Ramius muss über dich grinsen.`n
			`4Du hast dein ganzes Gold verloren!`n
			Du kannst morgen wieder kämpfen.`0
	`n`n<hr>`n');

	boss_calc_defeat();

	addnav('Tägliche News','news.php');

}

function boss_get_victory_news_text()
{
	global $session;

	$str_news = '`&'.$session['user']['name'].'`F hat den `@Grinch`F besiegt! Frohohohe Weihnachten Atrahor!';

	return $str_news;
}

function boss_get_defeat_news_text()
{
	global $session;

	$str_news = '`%'.$session['user']['name'].'`@ musste schwören dass es den Weihnachtsmann gar nicht gibt. Der Grinch hat zugeschlagen!';

	return $str_news;
}

?>