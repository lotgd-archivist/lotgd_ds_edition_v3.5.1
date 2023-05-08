<?php
/**
 * Bossgegner Nidhöggr
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3
 * @author dragonslayer
 */


/**
 * Die Nav darf nur angezeigt werden wenn der User das Tauschquest überwunden hat oder mit einem 1:5 Zufall
 */
function boss_check_additional_nav_preconditions()
{
	global $session;
	
	return $session['user']['exchangequest'] > 28 || e_rand(0,5) == 5;
}

function boss_do_intro()
{
	global $g_arr_current_boss,$session,$battle,$badguy,$g_str_base_file,$battle;
    if(!isset($str_output))$str_output='';
	switch($_GET['act'])
	{
		case '':
			{
				$str_output .= get_title('Die Qual der Nornen').'`y
				"Nun kommt der dunkle Drache geflogen, die Natter herauf aus den Nidafelsen. Das Feld überfliegend trägt er auf den Flügeln, Nidhöggr, Leichen - und nieder senkt er sich."`n`n
				`tBesorgt schauen die drei Nornen drein, als der neiderfüllte Schrei des Todbringers erklingt. Nidhöggr\'s Streit mit dem Adler, der in der Krone Yggdrasils lebt, geschürt durch die Ränkespiele Ratatöskrs des Eichhörnchens, hat ein neues trauriges Hoch gefunden. So nagt Nidhöggr unablässlich an den Wurzeln der Weltenesche, mit dem Drang diese zu zerstören, es dem Adler und den Menschen heimzuzahlen.
				Die Nornen jedoch können dies nicht zulassen, denn sie wissen über Vergangenheit, Gegenwart und Zukunft und sehen, dass es heute noch nicht soweit ist. Ragnarök wird heute nicht das Ende der Welt besiegeln. Ein Sterblicher, Träger einer `FBrosche der alten Völker`t wird das Unrecht tilgen und an Gottes statt Nidhöggr in die Flucht schlagen.`n`n
				Die Nornen schauen dich eindringlich an. `y"Bist Du der Sterbliche der uns erretten kann." `t `
				Ein wenig verwundert es dich, dass diese Frage so gar nicht nach einer Frage klingt, aber über dein Schicksal entscheidet noch immer nur eine einzige Person. Und das bist du selbst... oder?	
				';
				addnav('J?Ja, ich bin es!',$g_str_base_file.'&act=intro');
				addnav('N?Nein, das bin ich nicht','forest.php');
				output($str_output);
				break;
			}
		case 'intro':
			{	
				$str_output .= get_title('Der Neiddrache').'`y"Da sog Nidhöggr den Leiblosen ihre Seelen unablässlich, nagt die Wurzeln der Yggdrasil."`t`n`n
				Du weißt zwar nicht genau, was dir die Nornen sagen wollen, aber mit Drachen kennst du dich aus und weißt mit ihnen "umzugehen"! Du zückst deine Waffe, rückst deine Rüstung zurecht und lässt dir von den Nornen den Weg weisen.`n
				Dieser führt dich kurz, aber bestimmt durch den Nebel und direkt zu den Wurzeln der gigantischen Esche, aus der die Quelle Hvergelmir entspringt, der Ursprung aller kalten Flüsse und Heimat Nidhöggrs. Du betrachtest einen Moment lang ungläubig die monumentalen Holzwindungen, die sich aus dem Boden heraus in den mächtigen Stamm hinein vereinen. Wenn du es nicht genauer wüsstest, könntest du meinen, hier einen Übergang zwischen den Welten der Lebenden und der Toten zu erspähen. Kurz außerhalb deines Sichtbereiches scheinen fortwährend die Fratzen der Leiblosen auf dich hernieder zu schauen. Doch als du dich wendest siehst du nichts weiter als gewundenes Holz, welches deine Fantasie Lügen straft. Mit einem Male bemerkst du durch die Nebelschwaden eine Bewegung. Es ist ein Eichhörnchen welches die Wurzeln empor rennt. Es ist Ratatöskr und spätestens jetzt bist du dir ganz sicher, hier richtig zu sein. `n
				Bereits kurze Zeit später erkennst du den Eingang, der in die Wurzeln hineingefressen scheint. Eine unwirkliche Stille umfasst dich und als du die Höhle betrittst wispert es wie aus tausend Stimmen und dennoch lautlos direkt in deinen Ohren `y"Niflheim - Land der Schatten, Heimat der Seelenlosen, Thronsaal des Nidhöggr"`t`n
				Und dann siehst du ihn. Ein großer schwarzer Drachenleib. Nein, es ist viel mehr ein Fleisch gewordener Schatten, mit rot leuchtenden Augen der lautlos den Eindringling betrachtet - Dich!`n
				`bWappne dich für den Kampf`b';
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
	/*
	Mir fiel kein gescheiter Anfang zu einer Autochallenge durch Nifhöggr ein.
	Außerdem 
	*/
	return true;
}
//Hvergelmir
function boss_do_epilogue()
{
	global $g_str_base_file, $g_arr_current_boss, $session;

	music_set('drachenkill',0);

	switch ($_GET['act'])
	{
		case '':
			{
				$str_output = get_title('Sieg in Hvergelmir!');
				$str_output .= '`tAls dein Schwert ein letztes Mal in die schwarze, neblige Masse eintaucht, verblassen die rotglühenden Augen und die Form des drachigen Leibes verfällt. Kein Aufschrei, kein stürzender Leib, nur eine stumme Nebelwand ergiesst sich minutenlang kalt über dich. Obwohl dir die Sicht genommen wurde weißt du, dass du es geschafft hat, denn die latente Angst, die seit dem Moment auf dir lastete, als du Nidhöggrs Thronsaal betreten hast, ist nun verschwunden und einer wohltuenden Stille gewichen.`n
				Als deine Augen endlich wieder etwas wahrnehmen wollen, erblickst du die drei Nornen. Sie begießen die schwarzen Wurzeln Yggdrasils mit dem Wasser aus ihrer Quelle. Dort wo das reinigende Naß das verdorrte Holz benetzt, beginnt neues Leben zu entstehen. Die Wunden der Welt, entstanden durch Nidhöggrs Wut, schließen sich langsam und du erkennst Hirsche, Biber und einen Adler, die dich aufmerksam betrachten.`n
				Du horchst aufmerksam auf, als die Nornen zu sprechen beginnen:`n
				Urd:`y"'.($session['user']['sex']?'Seht die mächtige Heldin, durch sie ':'Seht den mächtigen Helden, durch ihn ').' wurde Nidhöggr besänftigt und der Kreis kann erneut von vorn beginnen."`n`t
				Verdanti:`y"'.($session['user']['sex']?'Sie':'Er').' erweist Ramius einen großen Gefallen, da '.($session['user']['sex']?'sie':'er').' dem dunklen Herrscher die Seelen gibt, die Nidhöggr ihm gestohlen hat."`t`n
				Skuld:`y"So werden auch wir '.($session['user']['sex']?'ihre':'seine').' Seele reinigen"`t`n
				Die Nornen benetzen dich mit dem kühlen Wasser der Quelle Urd. Du spürst, dass auch du Teil des Kreislaufes der Welt, von Entstehen und Vergehen bist. Da du den Drachen Nidhöggr gesehen hast ist die Zeit des Vergehens für dich unlängst angebrochen, doch der Dank der Nornen erlaubt dir einen Neubeginn. Du schließt langsam die Augen und merkst wie dein Bewusstsein schwindet.`n
				Als du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern, was geschehen ist.`n';
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
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über Nidhöggr!
				`n`nDein Ansehen bei Ramius steigt gewaltig, da du ihm viele Seelen gebracht hast, die sonst Nidhöggr anheim gefallen wären.`n';
				
				$session['user']['deathpower'] += 100;

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
	output('So sehr du auch gehetzt um dich blickst, du vermagst den Ausgang nicht mehr auszumachen. Dir bleibt nichts anderes übrig, als mit aller Macht zu versuchen, den riesigen Todbringer zu bekämpfen.');
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
	headoutput('`c`b`@Sieg!`0`b`c`n`&Mit einem gewaltigen Tosen verschwindet der Leib Nidhöggrs. Mit zitternden Gliedern steht du unter der Esche und spähst ungläubig auf den wieder ruhigen Nebel. Du hast es tatsächlich geschafft. Wenn du es könntest würdest du vor Freude Donner und Blitze werfen.
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

	$str_news = '`&'.$session['user']['name'].'`& hat Nidhöggr, den Todbringer, zurückgetrieben!';

	return $str_news;
}
function boss_get_defeat_news_text()
{
	global $session;

	$str_news = '`%'.$session['user']['name'].'`5 überließ Nidhöggr, dem Todbringer, seine Seele.';

	return $str_news;
}

?>
