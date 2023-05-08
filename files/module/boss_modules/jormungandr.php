<?php
/**
 * Bossgegner Jormungandr
 * Alle in dieser Datei vorliegenden Funktionen müssen für andere Bossgegner
 * implementiert werden.
 * @version DS-E V/3
 * @author dragonslayer
 */


/**
 * Die Nav darf nur angezeigt werden, wenn der User Trophäen bei sich hat,
 * die er als Köder nehmen kann
 */
function boss_check_additional_nav_preconditions()
{
	global $session;
	
	if(item_count('owner = "'.$session['user']['acctid'].'" AND tpl_id = "trph"') >0 )
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
					$str_output .= get_title('Der Riese Hymir').'`tGut gelaunt gehst du den Steg hinunter zum Wasser. Auf dessen rechter Seite sitzt heute wieder der Riese Hymir in seinem kleinen Fischerboot und knüpft seine Netze. Ihr begrüßt euch freundlich und du fragst dich einmal mehr warum er eigentlich der "Riese" genannt wird. Er ist zwar groß gewachsen, aber ein Riese...nunja.`n
					Nach ein wenig Smalltalk über Wetter, Land und Leute gelangt ihr rasch zur Sache und so fragt dich Hymir:`n
					`y"Tragt ihr denn heute zufällig einen `bbesonderen Köder`b bei Euch? Ihr wisst schon? Eine wahre Prachttrophäe?"`t`n
					Ein Blick in deinen Beutel werfend antwortest du:`n`n';
					$sql_res = item_list_get('owner = "'.$session['user']['acctid'].'" AND tpl_id = "trph" AND value2="7"');

					if(db_num_rows($sql_res)<1)
					{
						$str_output .= "`yLeider nicht, werter Hymir. Aber sobald ich einen habe werde ich gewiss wieder zu Euch kommen!`n
						`tIhr verabschiedet euch höflich und du wendest dich ab und gehst den Steg zurück an Land";
					}
					else
					{
						$str_output .= "`yIch denke schon, werter Hymir.`n
						`tDu greifst in deinen Beutel und ziehst eine deiner Trophäen hervor:";
						while ($arr_item = db_fetch_assoc($sql_res))
						{
							$str_output .= '`n'.create_lnk($arr_item['name'],$g_str_base_file.'&item_id='.$arr_item['id']);
						}
					}
					addnav('S?Zurück zum Seeufer','fish.php');
				}
				else
				{
					$arr_item = item_get('id='.$_GET['item_id']);
					if($arr_item === false || mb_strpos($arr_item['name'],'Der Kopf von Stier')===false)
					{
						$str_output .= get_title('Der Riese Hymir').'`tHymir schüttelt den Kopf und betrachtet "'.$arr_item['name'].'" von allen Seiten. `n`y "Nein, dies ist nicht was ich suche."`t`n
						Er gibt dir die Trophäe wieder."';
						addnav('Eine weitere Trophäe anbieten',$g_str_base_file);
						addnav('S?Zurück zum Seeufer','fish.php');
					}
					else 
					{
						$str_output .= get_title('Der Riese Hymir').'`tHymir betrachtet die Trophäe mit interessiertem Blick und murmelt leise "`y'.$arr_item['name'].'. Das gäbe doch einen vorzüglichen...LASST UNS ANGELN GEHEN!"`t`n
						Mit diesen Worten springt er unvermittelt auf und rudert los. Ehe du dich versiehst, bist du auch schon weit auf dem See.`n
						`y"Gut"`t, denkst Du Dir, `y"mache ich eben einen Angelausflug mit dem Riesen Hymir und nutze einen Stierkopf als Köder..."`0';
						item_delete('id='.$_GET['item_id']);
						addnav('Stierkopfangeln',$g_str_base_file.'&act=intro');
					}
					
				}
				output($str_output);
				break;
			}
		case 'intro':
			{
				addnav('Ziehe den Fang an Land',$g_str_base_file.'&act=fight');
				addnav('Lass ihn in Ruhe',$g_str_base_file.'&act=end');
				
				$str_output .= get_title('Der Angelausflug').'`FDas Hymirlied summend, fahrt ihr beide in die Mitte des Sees und ankert schließlich. Hymir erklärt dir unterdessen, dass es einer alten Legende zu Folge möglich sei, einen besonders dicken Fang an Land zu ziehen, wenn man mit Stierköpfen fischt. Und solch eine Möglichkeit dürfe man sich angesichts deiner Trophäe doch nicht entgehen lassen, oder?`n
				Du befestigst deinen ungewöhnlichen Köder am Haken und wirfst die Angel weit aus.`n
				Der kleine Schwimmer landet platschend an einer besonders tiefen Stelle des Sees und tanzt nun eine Weile beruhigend auf den kleinen 
				Wellen, die dein Boot auf dem See erzeugt. 
				Du lässt deine Gedanken eine Weile lang treiben, geniesst die entspannende Wirkung des plätschern des Wassers und denkst über dieses und jenes nach, als du unterbewusst eine Bewegung wahrnimmst. Da hat sich doch tatsächlich ein Fischlein an deinen Köder heran gewagt.
				';
				$session['user']['seendragon']=1;
				output($str_output);
				break;
			}
		case 'end':
			{
				$str_output .= get_title('Der Angelausflug').'`FAch, denkst du dir. Heute ist so ein schöner Tag, lassen wir dem Fisch doch auch mal seine Freude und ihn in Ruhe den Köder abknabbern. Schließlich soll man ja jeden Tag eine gute Tat vollbringen. Nur ob das für einen Mitmenschen oder einen Fisch gelten soll hat keiner gesagt.`n
				Du summst ein wenig weiter das Hymirlied.`n`n
				Der Riese Hymir schaut dich zwar ein wenig verdutzt an, genießt dann aber auch einfach den schönen Angelausflug. Als ihr später wieder an das Ufer rudert, bedankst du dich für den schönen Tag und gehst gut gelaunt über die kleine Botsanlegestelle zurück zum Seeufer. Du willst dich noch einmal umdrehen und Hymir zuwinken, als dieser schon längst wieder auf das Wasser hinaus gerudert ist.';
				addnav('S?Das Seeufer','fish.php');
				output($str_output);
				break;
			}
		case 'fight':
			{
				$str_output .= get_title('Jörmungandr, der Weltumschlingende').'
				Mit einem kurzen aber kräftigen Ruck ziehst du die Rute an, damit der Angelhaken seine grausame Funktion erfüllen kann und holst dann vorsichtig die Angelschnur ein.`n
				Geschafft, du spürst den Widerstand deines Fangs. Erst schwach, doch dann immer stärker werdend, als dieser offensichtlich zu fliehen versucht und dabei erst kleine Wellen an der Wasseroberfläche, dann den ganzen See in Unruhe versetzt. Den ganzen See?!? Es dämmert dir schnell, dass du hier keinen normalen Fang an der Angel hast, doch zu spät realisierst du, dass du wohl besser einfach die Angel fallen gelassen und um dein Leben gerudert wärst.`n`n
				Mit lautem Getöse hebt sich aus den Tiefen des Sees ein schwarz-grünlich schillernder Leib hervor und mehrere Meter in die Höhe. Es ist Jörmungandr, der Weltumschlingende. Seine eisigen Augen starren von oben auf dich herab und aus den tiefen seines schwarzen Rachens haucht er dich hasserfüllt an.`n
				`bBekämpfe die Midgardschlange!`b';
				
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

	music_set('drachenkill',0);
    if(!isset($str_output))$str_output='';
	switch ($_GET['act'])
	{
		case '':
			{
				$str_output = get_title('Sieg!');
				$str_output .= '`FNach diesem glorreichen Kampf scheinst du völlig der Welt entrückt zu sein. Deine Muskeln zittern wie Espenlaub und deine Finger krampfen noch immer um deine Waffe. Es scheint dir wie eine Ewigkeit, die du auf das trügerisch ruhige Wasser starrst. Würde Hymir dich nicht sanft an der Schulter berühren, würdest du wohl auch noch am Ende aller Zeiten hier stehen und darüber sinnieren, welches göttliche Gefüge dich diesen Kampf hat bestehen lassen.`n
				Du versuchst den Kopf zu Hymir zu drehen und siehst dessen selbstgefälliges Grinsen auf einem Gesicht, welches gar nicht mehr zu ihm passen mag. Vor deinem inneren Auge siehst du Hymir wachsen und als den Riesen erscheinen, der er immer war. Mit erstaunlich sanfter Stimme spricht er zu dir.`n
				`y"Ich bin von Dir beeindruckt. Nur wenige außer Dir sind in der Lage, dem gewaltigen Jörmungandr die Stirn zu bieten. Der Sohn des Loki wird einige Zeit schlafen, ehe er wagen wird, sich erneut zu regen und Ragnarök, das Ende aller Zeiten heraufzubeschwören. Damit es Dir jedoch nicht so ergeht wie es einst dem Hammerschwinger Thor ergehen wird, werde ich mich Deiner annehmen müssen."`n
				`FDer Sinn Hymirs Worte trifft dich wie ein Schlag! Nur Mjölnir, dem Hammer Thors ist es vergönnt die Midgardschlange zu vernichten. Doch wenn selbst des Göttervaters Sohn durch den Biss Jörmungandrs sterben wird, wie soll es erst dir ergehen? Bereits jetzt spürst du, wie du jeglichen Halt zu dieser Welt und deinem Körper zu verlieren drohst. Doch so weit lässt es Hymir nicht kommen. Er legt seine Hand sanft auf deine Schläfe und du spürst wie deine Lider schwer werden und du langsam in einen tiefen und gesundenden Schlaf hinweg gleitest.
				`nWährend die Reste deiner Wahrnehmung völlig schwinden, blickst du noch einmal auf den See und vermeinst eine von Jörmungandrs gigantischen Finnen unter der Wasseroberfläche aufblitzen zu sehen. Als du nach einigen Stunden wieder zu dir kommst, kannst du dich an nichts erinnern, was geschehen ist.`n';
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