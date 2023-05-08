<?php
/**
 * Bossgegner grüner Drache
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3
 * @author dragonslayer
 */


function boss_do_intro()
{
	global $g_arr_current_boss,$session,$battle,$badguy,$g_str_base_file;

	switch($_GET['act'])
	{
		case '':
			{
				addnav('Betritt die Höhle',$g_str_base_file.'&act=enter');
				addnav('Renne weg wie ein Baby','inn.php');
				output(get_title('Der Höhleneingang').'`$Du läufst auf den dunklen Eingang einer Höhle in den Tiefen des Waldes zu.
				Im Umkreis von mehreren hundert Metern sind die Bäume bis zu den Stümpfen niedergebrannt.
				Rauchschwaden steigen an der Decke des Höhleneinganges empor und werden plötzlich
				von einer kalten Windböe verweht. Der Eingang der Höhle liegt an der Seite eines Felsens,
				einige Dutzend Meter über dem Boden des Waldes, wobei Geröll eine kegelförmige
				Rampe zum Eingang bildet. Stalaktiten und Stalagmiten nahe des Eingangs
				erwecken in dir den Eindruck, dass der Höhleneingang in Wirklichkeit
				das Maul einer riesigen Bestie ist.
				`n`nAls du vorsichtig den Eingang der Höhle betrittst, hörst - oder besser fühlst du,
				ein lautes Rumpeln, das etwa dreißig Sekunden andauert, bevor es wieder verstummt.
				Du bemerkst, dass dir ein schwefliger Geruch entgegen schlägt. Das Poltern ertönt erneut, und das in einem regelmäßigen Rhythmus.
				`n`nVorsichtig kletterst du den Geröllhaufen hinauf, der dich über die Überreste vergangener Helden zum Eingang der Höhle führt.
				`n`nJeder Instinkt in deinem Körper will fliehen und so schnell wie möglich zurück nach Hause...in Sicherheit!');
				$session['user']['seendragon']=1;
				break;
			}
		case 'enter':
			{
				output(get_title('Der Höhleneingang').'`$Du erstickst jeden Drang zu fliehen und betrittst vorsichtig die Höhle. Du spekulierst
				darauf, den großen Drachen im Schlaf zu überraschen, um ihn mit einem Minimum an eigenem Schmerz
				zu erlegen. Leider ist das nicht der Fall. Du biegst in der Höhle um eine Ecke
				und entdeckst das Riesenbiest, das mit den Hinterbeinen auf einem gewaltigen Haufen Gold sitzt und
				seine Zähne mit etwas reinigt, das wie eine Rippe aussieht.');

				$badguy = boss_get_badguy_array($g_arr_current_boss);
				$session['user']['badguy']=utf8_serialize($badguy);
				$battle=true;
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

					$str_output .= '`2Vor dir liegt regungslos der große Drache und du bist dir seines Todes mehr als nur sicher.
					Du bist vom Kopf bis zu den Zehen mit dem dicken schwarzen Blut dieser stinkenden Kreatur bedeckt,
					als dir plötzlich voller Entsetzen auffällt, dass hinter dem riesigen Biest dessen Eier zu liegen scheinen.
					Verdammt, diese Wesen werden sicherlich bald schlüpfen. Was sollst du nur tun?
					';
					addnav('Die Eier vernichten',$g_str_base_file.'&op=epilogue&act=destroy_eggs');
					addnav('Ein Ei stehlen',$g_str_base_file.'&op=epilogue&act=get_egg');
					addnav('Weglaufen',$g_str_base_file.'&op=epilogue&act=run');
				}
				else
				{
					$str_output = get_title('Sieg!');
					$str_output .= '`2Vor dir liegt regungslos der große Drache. Sein schwerer Atem ist wie Säure für deine Lungen.
					Du bist vom Kopf bis zu den Zehen mit dem dicken schwarzen Blut dieser stinkenden Kreatur bedeckt.
					Das Riesenbiest fängt plötzlich an, den Mund zu bewegen. Verärgert über dich selbst, dass du dich von dem vorgetäuschten Tod
					der Kreatur hast reinlegen lassen, springst du zurück und erwartest, dass der riesige Schwanz auf dich zugeschossen kommt. Doch das passiert
					nicht. Stattdessen beginnt der Drache zu sprechen.`n`n
					`^"Warum bist du hierher gekommen, Sterblicher? Was habe ich dir getan?"`2 sagt er mit sichtlicher Anstrengung.
					`^"Meinesgleichen wurde schon immer gesucht, um vernichtet zu werden. Warum? Wegen Geschichten aus fernen Ländern,
					die von Drachen erzählen, die Jagd auf die Schwachen machen? Ich sage dir, dass diese Märchen nur durch Missverständnisse
					über uns entstehen und nicht, weil wir eure Kinder fressen."`2 Das Biest macht eine Pause um schwer zu atmen, dann fährt es fort:
					`^"Ich werde dir jetzt ein Geheimnis verraten. Hinter mir liegen meine Eier. Meine Jungen werden schlüpfen und sich gegenseitig
					auffressen. Nur eines wird überleben, aber das wird das Stärkste sein. Es wird sehr schnell wachsen und
					genauso stark werden wie ich."`2 Der Atem des Drachens wird kürzer und flacher.`n`n
					Du fragst: `#"Warum erzählst du mir das? Kannst du dir nicht denken, dass ich deine Eier jetzt auch vernichten werde?"`2
					`^"Nein, das wirst du nicht. Ich kenne noch ein weiteres Geheimnis, von dem du offensichtlich nichts weißt."`n`n
					`#"Bitte erzähle, oh mächtiges Wesen!"`n`n
					`2Das große Biest macht eine Pause, um seine letzten Kräfte zu sammeln. `^"Eure Art verträgt das Blut Meinesgleichen nicht.
					Bald wirst du nur noch ein kleines schwaches Wesen sein, kaum in der Lage, eine Waffe zu halten. Jegliche Fähigkeiten, welche du erlernt hast,
					werden deinem Gedächtnis entschwinden und auch die Stärke, die du erlangt hast, um mich zu vernichten, wird wieder jene sein, welche du zu
					Beginn deines Trainings besessen hast. Du kannst von Glück reden, dass zumindest dein Gedächtnis erhalten bleibt und mein Blut lediglich dafür sorgt,
					dass du sämtliche Begegnungen mit Kreaturen meiner Art vergessen wirst. Schon bald wirst du dein Bewusstsein verlieren und bereits beim Erwachen alles
					vergessen haben, was ich dir so eben berichtet habe. Leider wird dein Unwissen dich erneut dazu veranlassen zu trainieren, um Meinesgleichen
					abermals aufzusuchen und zu vernichten. Wahrhaft ein Jammer, dass niemand der Welt berichten kann, was für Kreaturen wir in Wirklichkeit,
					jenseits der Legenden sind..."`n`n
					`2Du bemerkst, dass deine Wahrnehmung tatsächlich bereits zu schwinden beginnt und fliehst Hals über Kopf aus der Höhle, nur darauf fixiert,
					die Hütte des Heilers zu erreichen, bevor es zu spät ist. Irgendwo unterwegs verlierst du deine Waffe und schließlich
					stolperst du über einen Stein in einem schmalen Bach. Deine Sicht ist inzwischen auf einen kleinen Kreis beschränkt,
					der in deinem Kopf herumzuwandern scheint. Während du so da liegst und in die Bäume starrst, glaubst du die Geräusche der Stadt
					in der Nähe zu hören. Dein letzter ironischer Gedanke ist, dass, obwohl du den Drachen besiegt hast, er doch
					dich besiegt hat.`n`n
					Während die Reste deiner Wahrnehmung völlig schwinden, fällt in der Drachenhöhle weit entfernt ein Ei auf die Seite und ein kleiner Riss
					erscheint in der dicken, lederartigen Schale. Als du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern,
					was nach dem Betreten der Höhle geschehen ist.`n';
					addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				}
				break;
			}
		case 'destroy_eggs':
			{
				$int_rand = e_rand(1,100);
				$str_output = get_title('Zerstöre die Brut!');
				if($int_rand<75)
				{
					$str_output .= '`2Stürmisch rennst du mit deiner Waffe auf die Eier zu und beginnst wie ein Irrer auf ihnen herumzuschlagen. Zwar ist die Schale äußerst hart, doch schaffst du es letztendlich, eines nach dem anderen zu zerstören. Als Beweis deiner Heldentat, steckst du ein Stück Eierschale ein, als dir plötzlich auffällt, dass du eines der Eier übersehen hast, da es etwas abseits liegt. Gerade als du darauf zurennen willst, dringt der schwere Atem des grünen Drachens in dein Gehör. Verdammt! Das Vieh scheint noch am Leben zu sein. So geschwächt wie du bist, wirst du es wohl kaum schaffen einen weiteren Kampf zu gewinnen, was dich dazu veranlasst schnellstens zu verschwinden. Du nimmst die Beine in die Hand und rennst schleunigst aus der Höhle. Irgendwo unterwegs verlierst du deine Waffe und stolperst schließlich über einen Stein in einem schmalen Bach. Du weißt nicht warum, aber dein Wahrnehmungssinn scheint nach und nach zu verschwinden. Während du so da liegst und in die Bäume starrst, glaubst du die Geräusche der Stadt in der Nähe zu hören. Dein letzter ironischer Gedanke ist, dass, obwohl du den Drachen besiegt hast, er doch dich besiegt hat.`n`n
							Während die Reste deiner Wahrnehmung völlig schwinden, fällt in der Drachenhöhle weit entfernt ein Ei auf die Seite und ein kleiner Riss
							erscheint in der dicken, lederartigen Schale. Als du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern, was nach dem Betreten der Höhle geschehen ist und wunderst dich beim Kontrollieren deines Rucksacks, über das auf mysteriöse Art und Weise aufgetauchte Stück der Eierschale.`n
							';
					addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
					item_add($session['user']['acctid'],'b_gd_shell');
				}
				else
				{

					$str_output .= '`2Stürmisch rennst du mit deiner Waffe auf die Eier zu und beginnst wie ein Irrer auf ihnen herumzuschlagen. Doch so sehr du dich auch bemühst, `@sie sind einfach nicht klein zu kriegen`2. Völlig erschöpft sackst du zusammen, als du plötzlich den schweren Atem des grünen Drachens im Nacken spürst. Schockiert drehst du dich um, als du dem riesigen Geschöpf auch schon direkt in die Augen starrst. Verdammt! Das Vieh scheint noch am Leben zu sein. So überrumpelt wie du bist, wirst du es wohl kaum schaffen einen weiteren Kampf zu gewinnen, was dich dazu veranlasst schnellstens zu verschwinden. Du nimmst die Beine in die Hand und rennst aus der Höhle. Irgendwo unterwegs verlierst du deine Waffe und stolperst schließlich über einen Stein in einen schmalen Bach. Du weißt nicht warum, aber dein Wahrnehmungssinn scheint nach und nach zu verschwinden. Während du so da liegst und in die Bäume starrst, glaubst du die Geräusche der Stadt in der Nähe zu hören. Dein letzter ironischer Gedanke ist, dass, obwohl du den Drachen besiegt hast, er doch dich besiegt hat.`n`n';
					addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				}
				break;
			}
		case 'get_egg':
			{
				$str_output = get_title('Stehle ein Ei!');
				$int_rand = e_rand(1,100);
				if($int_rand<50)
				{
					$str_output .= '`2Du beschließt, ein wenig in der Stadt zu prahlen und eines der Eier an dich zu reißen. Stürmisch rennst du darauf los und schaffst es irgendwie, eines der schweren Klötze in deinen Rucksack zu packen. Dies getan, dringt der schwere Atem des grünen Drachens in dein Gehör. Verdammt! Das Vieh scheint noch am Leben zu sein. So geschwächt wie du bist, wirst du es wohl kaum schaffen einen weiteren Kampf zu gewinnen, was dich dazu veranlasst schnellstens zu verschwinden. Du nimmst die Beine in die Hand und rennst schleunigst aus der Höhle. Irgendwo unterwegs verlierst du deine Waffe und stolperst schließlich über einen Stein in einem schmalen Bach. Du weißt nicht warum, aber dein Wahrnehmungssinn scheint nach und nach zu verschwinden. Während du so da liegst und in die Bäume starrst, glaubst du die Geräusche der Stadt in der Nähe zu hören. Dein letzter ironischer Gedanke ist, dass, obwohl du den Drachen besiegt hast, er doch dich besiegt hat.`n`n
							Während die Reste deiner Wahrnehmung völlig schwinden, fällt in der Drachenhöhle weit entfernt ein Ei auf die Seite und ein kleiner Riss
							erscheint in der dicken, lederartigen Schale. Als du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern, was nach dem Betreten der Höhle geschehen ist und wunderst dich beim Kontrollieren deines Rucksacks, über das auf mysteriöse Art und Weise aufgetauchte Ei des Drachens.
							';
					addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
					item_add($session['user']['acctid'],'b_gd_egg');
				}
				else
				{
					$str_output .= '`2Du beschließt, ein wenig in der Stadt zu prahlen und eines der Eier an dich zu reißen. Stürmisch rennst du darauf los, `@doch schaffst es beim besten Willen nicht`2, eines der schweren Dracheneier in deinen Rucksack zu schaffen. Völlig erschöpft sackst du zusammen, als du plötzlich den schweren Atem des grünen Drachens im Nacken spürst. Schockiert drehst du dich um, als du dem riesigen Geschöpf auch schon direkt in die Augen starrst. Verdammt! Das Vieh scheint noch am Leben zu sein. So überrumpelt wie du bist, wirst du es wohl kaum schaffen einen weiteren Kampf zu gewinnen, was dich dazu veranlasst schnellstens zu verschwinden. Du nimmst die Beine in die Hand und rennst aus der Höhle. Irgendwo unterwegs verlierst du deine Waffe und stolperst schließlich über einen Stein in einen schmalen Bach. Du weißt nicht warum, aber dein Wahrnehmungssinn scheint nach und nach zu verschwinden. Während du so da liegst und in die Bäume starrst, glaubst du die Geräusche der Stadt in der Nähe zu hören. Dein letzter ironischer Gedanke ist, dass, obwohl du den Drachen besiegt hast, er doch dich besiegt hat.`n`n';
					addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				}
				break;
			}
		case 'run':
			{
				$str_output = get_title('Flieh so schnell es geht!');
				$str_output .='`2Noch ehe du dich den Eiern nähern kannst, dringt der schwere Atem des grünen Drachens in dein Gehör. Verdammt! Das Vieh scheint noch am Leben zu sein. So geschwächt wie du bist, wirst du es wohl kaum schaffen einen weiteren Kampf zu gewinnen, was dich dazu veranlasst, schnellstens zu verschwinden. Du nimmst die Beine in die Hand und rennst schleunigst aus der Höhle. Irgendwo unterwegs verlierst du deine Waffe und stolperst schließlich über einen Stein in einem schmalen Bach. Du weißt nicht warum, aber dein Wahrnehmungssinn scheint nach und nach zu verschwinden. Während du so da liegst und in die Bäume starrst, glaubst du die Geräusche der Stadt in der Nähe zu hören. Dein letzter ironischer Gedanke ist, dass, obwohl du den Drachen besiegt hast, er doch dich besiegt hat.`n`n
				Während die Reste deiner Wahrnehmung völlig schwinden, fällt in der Drachenhöhle weit entfernt ein Ei auf die Seite und ein kleiner Riss
				erscheint in der dicken, lederartigen Schale. Als du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern, was nach dem Betreten der Höhle geschehen ist.
				';
				addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				break;
			}
		case 'wakeup':
			{
				$str_output .= get_title('Erwache!');
				$str_output .= 'Du erwachst umgeben von Bäumen. In der Nähe hörst du die Geräusche einer Stadt.
				Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von einem gefährlichen grünen Drachen, der die Gegend heimsucht.
				Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages dieser abscheulichen Kreatur stellst.
				`n`n`^Du bist von nun an bekannt als `&'.$session['user']['name'].'`^!!
				`n`n`&Weil du den Drachen '.$session['user']['dragonkills'].' mal besiegt hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du verdient oder gekauft hast.
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über den Drachen!`n';

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
	output("Der Schwanz der Kreatur versperrt den einzigen Ausgang aus der Höhle!");
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
	addnews('`#'.$session['user']['login'].'`# hat sich den Titel `&'.$session['user']['title'].'`# für den `^'.$session['user']['dragonkills'].'`#ten erfolgreichen Kampf gegen den `@Grünen Drachen`# verdient!');

	output('`&Mit einem letzten mächtigen Knall lässt `@der Grüne Drache`& ein furchtbares Brüllen los und fällt dir vor die Füße, endlich tot.');
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

	$str_news = '`&'.$session['user']['name'].'`& hat die abscheuliche,
	als `@Grüner Drache`& bekannte Kreatur besiegt. Über alle Länder freuen sich die Völker!';

	return $str_news;
}
function boss_get_defeat_news_text()
{
	global $session;

	$str_news = '`%'.$session['user']['name'].'`5 wurde gefressen, als '.
	($session['user']['sex']?'sie':'er').' dem `@Grünen Drachen`5 begegnete!!!  '.
	($session['user']['sex']?'Ihre':'Seine').' Knochen liegen nun am Eingang der Höhle,
	genau wie die der Krieger, die vorher kamen.';

	return $str_news;
}

?>