<?php
/**
 * Der Geisterpirat LeChuck entstand während des BBWF in Paris 2010
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3.42
 * @author Dragonslayer
 */

function boss_do_intro()
{
	global $g_arr_current_boss,$Char,$battle,$badguy,$g_str_base_file;

	switch($_GET['act'])
	{
		case '':
			{
				
				addnav('`(Tiefer in den Nebel',$g_str_base_file.'&op=intro&act=enter');
				addnav('`eRaus aus dem Nebel','hafen.php');
				output(get_title('`)Nebel auf dem Wasser').'`eAm Strand wird es mit einem Male sehr still. Das Rauschen des Meeres rückt in unendliche Ferne, kein Lüftchen regt sich, keine Möwe kreischt und keine Menschenseele ist zu sehen. Unnatürlich laut klingen deine Schritte auf dem Sand in deinen Ohren. Nebel zieht auf. In mehreren Lagen streicht er zart über den Boden und das Wasser. Schon nach kurzer Zeit ist er so dicht, dass du weder erkennen kannst was vor dir liegt, noch wo du jetzt genau bist.');
				$Char->seendragon=1;
				break;
			}
		case 'enter':
			{
				output(get_title('`)Nebel auf dem Wasser').'`eImmer tiefer treibst du in den unnatürlichen Nebel hinein. Das dumpfe Licht spielt deinen Ängsten und malt Schemen von Fratzen in dein Unterbewusstsein, die dich zu beobachten scheinen, dich umwinden und mit dir spielen. Als du es beinahe nicht mehr aushälst, taucht plötzlich in einiger Entfernung ein dunkler Schemen auf. Mit einem festen Ziel vor Augen bist du beinahe erleichtert. Du kannst dich auf etwas konzentrieren, ohne in dem undurchdringlichen Grau auf Grau verrückt zu werden. Du beschleunigst deine Schritte und hälst auf dein Ziel zu. Doch als du dich weiter näherst fragst du dich unwillkürlich, ob es wirklich so eine gute Idee war in diesem Nebel etwas finden zu wollen.`n`n
				Der dunkle Schemen hat sich mittlerweile zu einer kauernden humanoiden Gestalt in Lumpen entwickelt. Noch immer gehst du darauf zu, doch erst als du vorsichtig "`&Entschuldigt bitte?`e" rufst, bemerkst du deinen Fehler.`n`n
				Auf dem Sand hockt eine Gestalt mit wild zerzausten Haaren, einem Bart, der trotz Windstille sich zu bewegen scheint, zerschlissenem roten Kapitänsmantel und einem weiten dunklen Hut. Da durchfährt es dich. Die Person die sich dort vor dir aufrichtet ist '.$g_arr_current_boss['name'].'`e der unheilvolle Geisterpirat und einzig der abgetrennte Arm seines vorherigen Opfers, an dem er bisher nagte, hat ihn davon abgehalten dich zu bemerken. Mit bösem Grinsen, funkelnden Augen und rasiermesserscharfen Klauen will er sich seinen nächsten Snack gönnen.`0');

				$badguy = boss_get_badguy_array($g_arr_current_boss);
				$badguy['linkwin'] = $g_str_base_file.'&op=intro&act=victory';
				$badguy['linkdefeat'] = $g_str_base_file.'&op=intro&act=defeat';

				addnav('Kämpfe','battlewrapper.php?op=fight');
				$Char->badguy=utf8_serialize($badguy);
				
				break;
			}
		case 'repeat':
			{
				output(get_title('`)Nebel auf dem Wasser').'`eIrgendetwas stimmt nicht. Du fühlst nicht die Erleichterung, die du sonst spürst, wenn du einen erfolgreichen Kampf bestritten hast. Außerdem umhüllt dich noch immer dieser dichte Nebel. Du drehst dich um und schaust noch einmal auf '.$g_arr_current_boss['name'].'`e doch dieser liegt nicht mehr dort wo er noch Sekunden zuvor lag. Panisch spähst du in den grauen Schleier, um das schlimmste zu vermeiden, doch er ist schneller. Der Untote springt dich aus dem Hinterhalt an und fügt dir eine klaffende Wunde zu, ehe du dich wehren kannst.`0');
				$badguy = boss_get_badguy_array($g_arr_current_boss);
				$badguy['linkwin'] = $g_str_base_file.'&op=intro&act=victory';
				$badguy['linkdefeat'] = $g_str_base_file.'&op=intro&op=fight';

				$Char->badguy=utf8_serialize($badguy);
				$Char->hitpoints = max(0,$Char->hitpoints-10);
				addnav('Kämpfe','battlewrapper.php?op=fight');
				if(item_count('i.tpl_id="common_rootbeer" AND i.owner='.$Char->acctid)>0)
				{
					addnav('Benutze das Malzbier',$g_str_base_file.'&op=intro&act=rootbeer');
				}
				addnav('Flieh so schnell du kannst',$g_str_base_file.'&op=intro&act=flee');
				break;
			}
		case 'rootbeer':
			{
				output(get_title('`)Nebel auf dem Wasser').'`ePanisch reisst du irgendetwas aus deinem Inventar. Es ist eine Flasche Malzbier. Unwillkürlich besprühst du den Geisterpiraten damit. Doch mit der Reaktion hast du nicht gerechnet. Wort- und tonlos reisst '.$g_arr_current_boss['name'].'`e den Mund auf, hält sich mit den Händen das Gesicht und taumelt einige Schritte zurück. Anscheinend hast du seine Schwachstelle gefunden, ihn allerdings auch sehr wütend gemacht. Jetzt geht es ans Eingemachte!`0');
				
				$badguy = boss_get_badguy_array($g_arr_current_boss);
				$badguy['creaturelevel'] = 30;
				$badguy['creatureattack'] = $Char->attack+30;
				$badguy['creaturedefense'] = $Char->defense+30;
				$badguy['creaturehealth'] = $Char->maxhitpoints+1000;
				
				$Char->badguy=utf8_serialize($badguy);
				
				//rootbeer löschen
				item_delete('tpl_id="common_rootbeer" AND owner='.$Char->acctid,1);
				
				addnav('Kämpfe',$g_str_base_file.'&op=fight');
				break;
			}
		case 'flee':
			{
				$Char->reputation -= $g_arr_current_boss['defeat_reputation'];
				$Char->charm=max(0,$Char->charm - $g_arr_current_boss['defeat_charm']);
				
				redirect('hafen.php');
				break;
			}
		case 'victory':
			{
				if(!isset(Atrahor::$Session['daily']['boss_lechuck_count']))
				{
					Atrahor::$Session['daily']['boss_lechuck_count'] = 1;
				}
				else
				{
					Atrahor::$Session['daily']['boss_lechuck_count'] += 1;
				}
				
				output(get_title('`)Nebel auf dem Wasser').$g_arr_current_boss['name'].'`e fällt vor dir zu Boden. Seine Extremitäten stehen in teilweise grotesken Winkeln von seinem Körper ab und das Funkeln in seinen Augen scheint langsam zu erlöschen. Ermattet wendest du dich ab.`0');
				addnav('Zurück in den Nebel',$g_str_base_file.'&op=intro&act=repeat');
				
				break;
			}
		case 'defeat':
			{
				output(get_title('`)Nebel auf dem Wasser').'`eDu fällst vor deinem Gegner zu Boden. '.((Atrahor::$Session['daily']['boss_lechuck_count'] > 0)?'Wie konnte dies nur geschehen? Du hast ihn '.(Atrahor::$Session['daily']['boss_lechuck_count']).' mal besiegt und er stand dennoch immer wieder auf...':'').'Glücklicherweise ist Ramius bei dir und nimmt dich mit sich, bevor du erleben musst, wie sich '.$g_arr_current_boss['name'].' `eüber deine sterblichen Überreste her macht.`0');
				
				boss_calc_defeat();

				addnav('Tägliche News','news.php');
				boss_write_defeat_news();
				boss_clean_up();
				
				break;
			}
	}
}

function boss_do_autochallenge()
{
	
	return true;

	/**
	*global $g_str_base_file;
	*output(get_title('Nebel auf dem Wasser!').'`(Es ist absolut windstill und menschenleer um dich herum und denoch wabert am Strand und auf dem Meer unnatürlicher Nebel in dicken Bahnen an dir vorbei. Ein eisiges Gefühl kriecht dir den Nacken empor, doch deine Neugier ist größer. Du schiebst dich förmlich durch die Nebelbahnen und immer tiefer in das Grau.`0');

	*addnav('Weiter...',$g_str_base_file.'&op=enter');
	*/
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
				$str_output = get_title('`)Lichtender Nebel`0');
				$str_output .= '`)Ein letztes Mal lässt du deine Waffe auf den besiegten Gegner hernieder fahren. Endlich soll seine Seele ruhen und dich aus diesem Nebel lassen. Mit jedem Schritt, den du dich von den Überresten des Geisterpiraten entfernst, lichtet sich der nebel ein wenig mehr und die Geräusche kehren in deine Welt zurück. Als du zum ersten mal den Boden erkennen kannst macht dein Herz vor Freude einen kleinen Sprung, nur um dir danach sofort in die Hose zu rutschen. "`&Dieses boshafte Ungeheuer`)", schimpfst du und blickst dich ungläubig um. Durch den Nebel völlig unbemerkt hast du dich weit, weit vom Strand weg begeben. Du befindest dich auf einer kleinen Sandbank...Und die Flut steigt schnell...
				';
				addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				break;
			}
		case 'wakeup':
			{
				$str_output .= get_title('Erwache!');
				$str_output .= 'Stunden später erwachst du klitschnass am Rande eines kleinen Bachs, umgeben von Bäumen. Du hast keine Ahnung wie du hierher gekommen bis noch was du hier sollst. In der Nähe hörst du die Geräusche einer Stadt.
				Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von gefährlichen Kreaturen, die die Gegend heimsuchen.
				Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages diesen abscheulichen Wesen stellst.
				`n`n`^Du bist von nun an bekannt als `&'.$Char->name.'`^!!
				`n`n`&Weil du '.$Char->dragonkills.' Heldentaten vollbracht hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du dir verdient oder erkauft hast.
				`n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg!`n`n';

				addnav('Es ist ein neuer Tag','news.php');

				// Knappe laden und steigern
				$rowk = get_disciple();
				if ($rowk['state']>0)
				{
					$str_output .= disciple_levelup($rowk);
					Atrahor::$Session['bufflist'] = array();
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
	output("Der Nebel versperrt dir den weg. Wohin solltest du auch fliehen wenn du nicht weißt wo du bist!");
}

function boss_do_fight()
{
	global $battle;
	$battle = true;
}

function boss_do_victory()
{
	global $g_str_base_file,$badguy,$flawless,$Char,$g_arr_current_boss;

	boss_calc_victory_bonus();
	

	music_set('drachenkill',0);

	$flawless = 0;
	if ($badguy['diddamage'] != 1)
	{
		$flawless = 1;
	}
	addnews('`#'.$Char->login.'`# hat sich den Titel `&'.$Char->title.'`# für den `^'.$Char->dragonkills.'`#ten erfolgreichen Kampf gegen '.$g_arr_current_boss['name'].' `# verdient!');

	output('`&Der letzte Schlag fegt '.$g_arr_current_boss['name'].'`& von seinen untoten Beinen!`0');
	addnav('Weiter',$g_str_base_file.'&op=epilogue&flawless='.$flawless);
}

function boss_do_flawless_victory()
{
	boss_calc_flawless_victory_bonus();
}

function boss_do_defeat()
{
	global $g_arr_current_boss;
	output('`b`%'.$g_arr_current_boss['name'].'`& hat dich getötet!!!`n
			`4Du hast dein ganzes Gold verloren!`n
			Du kannst morgen wieder kämpfen.`0');

	boss_calc_defeat();

	addnav('Tägliche News','news.php');

}

function boss_get_victory_news_text()
{
	global $Char,$g_arr_current_boss;

	$str_news = '`&'.$Char->name.'`& hat die abscheuliche,
	als '.$g_arr_current_boss['name'].'`& bekannte Kreatur besiegt.';

	return $str_news;
}
function boss_get_defeat_news_text()
{
	global $Char,$g_arr_current_boss;

	$str_news = '`%'.$Char->name.'`5 wurde getötet, als '.
	($Char->sex?'sie':'er').' '.$g_arr_current_boss['name'].'`5 begegnete!!!';

	return $str_news;
}

?>