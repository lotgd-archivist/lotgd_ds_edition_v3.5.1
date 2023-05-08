<?php
/**
 * Bossgegner Killerkaninchen. Wer nicht weiß wo diese Bestie ihre Inspiration fand sollte sich was schämen.
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3.42
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 */


/**
 * Folgende Bedingungen müssen zusätzlich erfüllt werden
 */
function boss_check_additional_nav_preconditions()
{	
	return true;
}

function boss_do_intro()
{
	global $g_arr_current_boss,$session,$Char,$battle,$badguy,$g_str_base_file,$battle;
	
	$str_act = $_GET['act'];
	
	if(Atrahor::$Session['boss_killer_cony_visited'] == true)
	{
		$str_act = 'holy_granade';
	}
    if(!isset($str_output))$str_output='';
	switch($str_act)
	{
		case '':
			{
				$str_output .= get_title('`xKa`%in`=ch`5en').words_by_sex('`jAls du die Wiese betrachtest hüpft dein Herz im Leibe. Die kleinen Löffler die hier grasen und sich tollen sind einfach zu putzig. Weiße, braune, schwarze und gefleckte Kaninchen. Schlappohren und kleine Rammler, sie hüpfen munter umher und ziehen dich magisch an. Dort hinten scheint ihr Bau zu sein. Eine kleine Höhle vor der putzige Knochenreste liegen, ein paar niedliche halb zerfressene Kadaver und noch viel mehr dieser süßen, kleinen flauschigen - `a"`bHALT!`b WAS tut ihr da?"`j ruft dich mit einem Male eine kratzige Stimme zurück in die Wirklichkeit. `a"In Deckung du Narr!" Ehe du recht begreifst was gemeint sein könnte, reisst dich eine unbekannte Gestalt von den Beinen und hinter einen großen Findling in Deckung. Du starrst ihn verdutzt an und erkennst einen seltsam gekleideten, alten Mann. Er trägt eine gedrehte Widderhorn-Kappe, mehrere verschiedenfarbige Lagen von Gewändern und bückt sich gerade nach einem knorrigen Wanderstab, als du ihn ansprichst: `y"Entschuldigt mal, was sollte das denn? Und wer seid ihr überhaupt?" `j Der Alte zupft sich seinen langen weißen Bart zurecht, schaut kopfschüttelnd auf dich hernieder und antwortet. `a"Ich bin ein großer Zauberer und Nekromant! Man nennt mich...Tim! Und ihr [mein Herr|meine Dame] seid anscheinend lebensmüde! Habt ihr die Bestie denn nicht gesehen?" - `y"Wovon sprecht ihr, welche Bestie denn?" - `a"Jetzt behauptet bloß ihr habt die ganzen Gebeine nicht gesehen, die stammen von...IHR! Der Bestie! So viele hat sie schon ins Verderben gerissen." - `y"Verzeiht...Tim...aber kann es sein dass ihr ...zu lang in der Sonne gesessen habt? Das waren Kaninchen!" - `a"Oh [armer Narr|arme Närrin] es IST eines der Kaninchen! Mit hässlichen vergilbten gebogenen Hauern pellt sie das Fleisch von ihren Opfern! Wo das Vieh hinbeisst wächst kein Gras mehr" `jSo langsam keimen in dir doch deine berechtigten Zweifel über den gemütszustand des "mächtigen Tim" auf, wie der Alte da vor dir steht und mit seinen Händen die Hauer eines Kaninchens nachahmt und dich von der Brutalität des gemeinen Löfflers überzeugen will. Und so erklärst du dem wimmernden Kerl mal ganz klipp und klar mit wem er es a) zu tun hat und b) was du so schon alles getötet hast. Schnell hast du dich in Rage geredet und die "ach so finsteren Kaninchen" dazu auserkoren deine Wut zu stillen. Gibts halt heute Abend Kaninchenbraten!');
				
				addnav('Dem Zeig ichs!',$g_str_base_file.'&act=intro');
				addnav('Iih Kaninchen, weg hier','forest.php');
				output($str_output);
				break;
			}
		case 'holy_granade':
			{
				$str_output .= get_title('`5Ki`=ll`%er`xka`%in`=ch`5en');
				if(item_count('i.tpl_id="holy_granade" AND i.owner='.$Char->acctid) > 0)
				{
					addnav('Die heilige Handgranate',$g_str_base_file.'&act=use_granade');
				}
				else 
				{
					addnav('Dem Zeig ichs!',$g_str_base_file.'&act=intro');
					addnav('Iih Kaninchen, weg hier','forest.php');
				}
				output($str_output);
				break;
			}
		case 'intro':
			{	
				$str_output .= get_title('`5Ki`=ll`%er`xka`%in`=ch`5en').'Du zurrst deine Waffen fest, richtest dich auf und lässt deinen Kampfschrei ertönen! Insgeheim hoffst du nur, dass dich niemand beobachtet hat wie du auf eines der Kaninchen zurennst, um es dem Erdboden gleich zu machen und dein eigenes Ego zu bestätigen.
				
				`b`$Wappne dich für den "Kampf"`0`b';
				output($str_output);
				$badguy = boss_get_badguy_array();

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
	 * Kein Autochallenge durch die Bunnies
	 */
	return true;
}

function boss_do_epilogue()
{
	global $g_str_base_file, $g_arr_current_boss, $session, $Char;
    if(!isset($str_output))$str_output='';
	music_set('drachenkill',0);

	switch ($_GET['act'])
	{
		case '':
			{
				$str_output = get_title('`QDer Tod des Jack ``O!');
				$str_output .= '';
				addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				break;
			}
		case 'wakeup':
			{
				$str_output .= get_title('Erwache!');
				$str_output .= 'Du erwachst klitschnass am Rande eines kleinen Bachs, umgeben von Bäumen. Du hast keine ahnung wie du hierher gekommen bis noch was du hier sollst. In der Nähe hörst du die Geräusche einer Stadt.
				Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von gefährlichen Kreaturen, die die Gegend heimsuchen.
				Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages diesen abscheulichen Wesen stellst.
				`n`n`^Du bist von nun an bekannt als `&'.$session['user']['name'].'`^!!
				`n`n`&Weil du '.$session['user']['dragonkills'].' Heldentaten vollbracht hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du dir verdient oder erkauft hast.
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über Jack ``O!`n`n';

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
	output('Das verfluchte Pelzknäuel ist einfach viel zu schnell. In welche Richtung du dich auch wendest, das Kaninchen versperrt dir den Weg. Du musst bis zum bitteren Ende Kämpfen!');
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
//Dieser Text ist noch fertig anzupassen!
	headoutput(get_title('`@Sieg!').'`&Der unheilige Leib des Jack ``O fällt in sich zusammen und zurück bleibt nur ein alter, verschrumpelter `QKürbiskopf`& aus dem du einige Kürbissamen entnehmen kannst.`n`n<hr>`n');
	item_add($Char->acctid,'pumpkin_seed');
	addnav('Weiter',$g_str_base_file.'&op=epilogue&flawless='.$flawless);
}

function boss_do_flawless_victory()
{
	boss_calc_flawless_victory_bonus();
}

function boss_do_defeat()
{
	global $g_arr_current_boss;
	headoutput(get_title('Niederlage').'`& Du wurdest von '.$g_arr_current_boss['name'].'  gerichtet! Ramius empfängt dich, beinahe hämisch grinsend.`n
			`4Du hast dein ganzes Gold verloren!`n
			Du schwörst dir so schnell wie möglich dieses Monster zu erledigen. Vielleicht hat ja jemand einen Hinweis wie du vorgehen solltest.`0
	`n`n<hr>`n');

	Atrahor::$Session['boss_killer_cony_visited'] = true;
	
	boss_calc_defeat();

	addnav('Tägliche News','news.php');

}

function boss_get_victory_news_text()
{
	global $Char;

	$str_news = '`&'.$Char->name.'`& hat ein `5Ki`=ll`%er`xka`%in`=ch`5en `&besiegt!';

	return $str_news;
}

function boss_get_defeat_news_text()
{
	global $Char;

	$str_news = '`%'.$Char->name.'`5 wurde von einem `xKa`%in`=ch`5en`5. Wie Lächerlich!';

	return $str_news;
}

?>