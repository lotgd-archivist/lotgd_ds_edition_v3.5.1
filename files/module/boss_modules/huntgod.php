<?php
/**
 * Bossgegner Waldgott
 * @version DS-E V/3
 * @author Salator
 */


/**
 * Die Nav darf nur angezeigt werden wenn der User auf Jägerlevel 6 ist
 */
function boss_check_additional_nav_preconditions()
{
	$rowe=user_get_aei('hunterlevel');
	return ($rowe['hunterlevel']==6);
}

function boss_do_intro()
{
	global $g_arr_current_boss,$session,$battle,$badguy,$g_str_base_file;

	switch($_GET['act'])
	{
		case '':
		{
			if($session['user']['gems']>=10)
			{
				addnav('A?Betritt die Arena',$g_str_base_file.'&act=enter');
			}
			addnav('Lieber ein andermal','lodge.php');
			output(get_title('`QDie letzte Prüfung').'
			`qSeit einiger Zeit hast dir nun schon beachtlichen Ruhm als Jäger verdient und eine schöne Sammlung an Trophäen erbeutet. Nun bist du in den Kreis derer aufgestiegen, die das Recht haben, die höchste aller Jägerprüfungen -das Duell mit dem Waldgott-Champion- abzulegen.
			`nDieses Duell ist ein Kampf auf Leben und Tod.
			`nEs ist Brauch, dass dem Sieger der Kopf des Unterlegenen gehört, wenn der tödliche Schlag mit der geweihten Jagdwaffe ausgeführt wurde. Gewinnst du das Duell auf diese Art, so gilt die Prüfung als bestanden.
			`n`nDesweiteren ist es Brauch, dass der Prüfling ein rauschhaftes Fest für alle anwesenden Jäger gibt. Dies wird dich `^all dein Gold und 10 Edelsteine`q kosten.
			`n`nBist du bereit, die höchste aller Jagdprüfungen abzulegen?');
			break;
		}
		
		case 'enter':
		{
			$badguy = boss_get_badguy_array($g_arr_current_boss);
			$session['user']['badguy']=utf8_serialize($badguy);
			$battle=true;
			$session['user']['seendragon']=1;
			output(get_title('`QDie Arena').'
			
			`qEtliche deiner Jagdgenossen, welche diese Prüfung schon bestanden haben, haben sich als Zuschauer in der Arena versammelt. Die Prüfung, die einen Hüter des Waldes zum Beherrscher des Waldes macht, ist ein seltenes und außergewöhnliches Schauspiel. Spannung liegt in der Luft, du spürst, dass die prüfenden Blicke der Zuschauenden auf dich gerichtet sind. Aber du fühlst dich selbstsicher. 
			`nGerade überlegst du noch, ob du diese Prüfung nur vor einem weltlichen Gremium ablegen wirst, oder ob der Waldgott auch zusieht, da ertönt ein gewaltiger Donner aus den Wolken. Dies ist das Zeichen des Waldgottes, dass der Kampf beginnen möge.
			`nDu verneigst dich vor dem `^Großen Hirsch`q und dieser tut es ebenso vor dir. Dann beginnt das Duell.
			`n`n');
			break;
		}
	}
}

function boss_do_autochallenge()
{
	global $g_str_base_file;
	output(get_title('Der Drache holt dich!').'`$Auf dem Weg zum Stadtzentrum hörst du ein seltsames Geräusch aus Richtung Wald und spürst ein ebenso seltsames Verlangen, der Ursache für das Geräusch nachzugehen.
	Die Leute auf dem Platz scheinen in ihrer Unterhaltung nichts davon mitbekommen zu haben, also machst du dich alleine auf den Weg. Kaum im Wald hörst du das Geräusch erneut, diesmal schon wesentlich näher.
	`nIn der Ferne siehst du ihn: Den `@grünen Drachen`$! Gerade dabei, eine Höhle zu betreten. Er scheint müde zu sein. Das ist `bDIE`b Gelegenheit! Nie hast du dich stärker gefühlt...');

	addnav('Weiter...',$g_str_base_file.'&op=intro');
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
				$flawless = 0;
				if ($_GET['flawless'])
				{
					$flawless = 1;
					$str_output = get_title('~~ Ein Perfekter Kampf! ~~');
				}
				else
				{
					$str_output = get_title('Sieg!');
				}
				$rowe=user_get_aei('hunterlevel');
				$str_output .= '`2Vor dir liegt regungslos der große Hirsch. '.($rowe['hunterlevel']==7?'Unter tosendem Beifall deiner Jägergenossen und mit vor Stolz geschwollener Brust schwenkst du das Mystische Geweih. Du hast die Prüfung bestanden.':'Zu schade, dass du diese Prüfung vergeigt hast. Jedoch erwarten die anwesenden Jäger trotzdem ein Fest von dir.').' Also gibst du eine gewaltige Feier in der Jägerhütte.
				`n`nIm Vereinszimmer hat Petersen eine festliche Tafel mit den erlesensten Wild-Speisen gedeckt. Das Bier und der Wein fließt in Strömen. Dazu spielt der Wanderbarde eine beschwingte Melodei.
				`nBis in die frühen Morgenstunden wird gefressen, gesoffen, getanzt und geh... ups, das ist nicht jugendfrei, also wird gelacht.
				`nWährend die Reste deiner Wahrnehmung völlig schwinden, schlüpft weit entfernt in der Drachenhöhle ein junger grüner Drache aus seinem Ei. Alltag in Atrahor.
				`n`nAls du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern, was nach dem Betreten der Arena geschehen ist.
				';
				$session['user']['gems']-=10;
				addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				
				break;
			}
		case 'wakeup':
			{
				$str_output .= get_title('Erwache!');
				$str_output .= 'Du erwachst umgeben von Bäumen. In der Nähe hörst du die Geräusche einer Stadt.
				Dunkel erinnerst du dich daran, dass du ein neuer Jäger bist, und an irgendwas von einem gefährlichen grünen Drachen, der die Gegend heimsucht.
				`n`n`^Du bist von nun an bekannt als `&'.$session['user']['name'].'`^!!
				`n`n`&Weil dies deine '.$session['user']['dragonkills'].' Heldentat war startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du verdient oder gekauft hast.
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über den Hirsch!`n';

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
	output("Du musst diese Prüfung zu Ende führen, egal wie sie ausgeht!");
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
	addnews('`#'.$session['user']['login'].'`# hat sich den Titel `&'.$session['user']['title'].'`# für die `^'.$session['user']['dragonkills'].'`#te Heldentat verdient!');

	output('`&Mit einem letzten mächtigen Knall lässt `@der Große Hirsch`& ein furchtbares Brüllen los und fällt dir vor die Füße, endlich tot.');
	addnav('Weiter',$g_str_base_file.'&op=epilogue&flawless='.$flawless);
}

function boss_do_flawless_victory()
{
	boss_calc_flawless_victory_bonus();
}

function boss_do_defeat()
{
	global $g_arr_current_boss;
	output('`b`%'.$g_arr_current_boss['name'].'`& hat dich gefressen!!!`n
			`4Du hast dein ganzes Gold verloren!`n
			Du kannst morgen wieder kämpfen.`0');

	boss_calc_defeat();

	addnav('Tägliche News','news.php');

}

function boss_get_victory_news_text()
{
	global $session;

	$str_news = '`&'.$session['user']['name'].'`& hat den `@Champion des Waldgottes`& in einem Jägerduell besiegt.';

	return $str_news;
}
function boss_get_defeat_news_text()
{
	global $session;

	$str_news = '`%'.$session['user']['name'].'`5 wurde vom `&Champion des Waldgottes`5 in einem Jägerduell besiegt. '.
	($session['user']['sex']?'Ihr':'Sein').' Kopf ziert nun als Trophäe den Tempel, genau wie die Köpfe der Unwürdigen, die vorher kamen.';

	return $str_news;
}

?>