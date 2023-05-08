<?php
/**
 * Bossgegner Fenriswolf
 */

/**
 * Die Nav darf nur angezeigt werden wenn der User ???
 */
function boss_check_additional_nav_preconditions()
{
	global $Char;
	$bool_items = (item_count('owner = "'.$Char->acctid.'" AND tpl_id = "r_fdr_hgn"') >0) && (item_count('owner = "'.$Char->acctid.'" AND tpl_id = "r_fdr_mnn"') >0);
	$bool_user = ($Char->dragonkills-5)%10==0;
	if($bool_items && $bool_user)
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
	global $g_arr_current_boss,$Char,$battle,$badguy,$g_str_base_file,$battle;
    if(!isset($str_output))$str_output='';
	switch($_GET['act'])
	{
		case '': //Erster Text
		{
			$str_output .= get_title('Odins Auftrag').words_by_sex('`tDu betrachtest die beiden Federn der mystischen Raben noch ein letztes mal, ehe du sie in den magischen Käfig legst und die Tür schließt. In dem Moment beginnt die Drahtkonstruktion zu leuchten, sodass du den Blick abwenden musst. Als du erneut hinsiehst, ist der Käfig leer. `nHinter dir jedoch hörst du Flügelschlagen. Du drehst dich um und dir blicken Hugin und Munin entgegen. Die beiden Raben ergreifen deine Arme und ehe du dich versiehst, stehst du auf einer weiten Ebene, auf der ein einzelner, enormer Felsen steht. Niemand geringeres als Odin, der oberste Gott Asgards steht daneben. Er winkt dich zu sich - wer würde es wagen sich einem Gott zu verweigern - und deutet auf eine harmlos aussehende Schnur, die sich vom Felsen bis zu einem tobenden Wolf zieht.`n`^"Wir haben von deinen Heldentaten gehört, [junger Krieger|junge Kriegerin] und dich für eine besondere Aufgabe auserkoren. Dieser Wolf, Fenris, stellt eine Gefahr für uns dar, doch ist es uns unmöglich, ihn zu töten, da er das Blut eines der unsrigen trägt. Du jedoch bist bekannt dafür, gegen als unsterblich bekannte Kreaturen in den Kampf zu ziehen - wirst du dein Glück versuchen? Solltest du Erfolg haben, werden wir dich reich belohnen und auch dein Tod würde dir einen Platz in Walhall sichern."`t`nDu blickst von Odin zu Fenris, der ohne Unterlass mit seiner Fessel ringt und dir keine Beachtung schenkt. Wirst du dich dem Kampf stellen?');
			addnav('Die Herausforderung annehmen',$g_str_base_file.'&act=intro');
			addnav('Feige ablehnen','houses.php');
			output($str_output);
			break;
		}
		case 'intro': //Text wenn man die Herausforderung annimmt
		{
			$str_output .= get_title('Der Fenriswolf').words_by_sex('`tNatürlich schreitest du in den Kampf, immerhin geht es um deine Ehre als [Krieger|Kriegerin] und wie gefährlich kann schon ein Wesen sein, dass an einer Leine hängt? Als du dich dem Wolf jedoch näherst wird dir klar, dass Kampf keineswegs so einfach wird, denn sogleich scheint die Fessel vergessen und ein weiter Sprung bringt dich in seine Reichweite. Groß ragt er vor dir auf, der Wahnsinn, der durch seine Gefangenschaft seine Gedanken getrübt hat, steht in seinen Augen. Fast könnte man Mitleid mit dem Tier bekommen, wäre da nicht das bedrohliche Knurren, dass seine Kehle verlässt und die scharfen Reißzähne, die nur darauf warten dich zu erfassen. Solltest du ihn nicht besiegt ist klar, was dein Schicksal sein wird.');
			output($str_output);
			$badguy = boss_get_badguy_array($g_arr_current_boss);
			$Char->badguy=utf8_serialize($badguy);
			$battle=true;
			$Char->seendragon=1;
			
			item_delete('owner = "'.$Char->acctid.'" AND tpl_id = "r_fdr_hgn"',1);
			item_delete('owner = "'.$Char->acctid.'" AND tpl_id = "r_fdr_mnn"',1);
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
	global $g_str_base_file, $g_arr_current_boss, $Char;

	music_set('drachenkill',0);
    if(!isset($str_output))$str_output='';
	switch ($_GET['act'])
	{
		case '':
			{
				$str_output .= get_title('Sieg!').words_by_sex('`tDu willst den Leiden des Fenris endlich ein Ende bereiten, doch sein Herz kommt nicht zum Stillstand, was auch immer du tust. Schließlich tritt Odin hinter dich und hält dich von einem weiteren Schwertstreich ab.`n `^"Lasst es sein, [mein Junge|meine Liebe], er ist wahrlich unsterblich, doch deine Mühe war nicht vergebens. Es wird lange dauern, bis er sich von diesem Kampf erholt hat, kostbare Zeit, in denen er nicht an seiner Flucht arbeiten kann. Nimm zum Dank ein Ebenbild Draupnirs, er soll dir gute Dienste erweisen." `n`tDu nimmst den Ring mit einer Verneigung entgegen und kannst gerade noch einen Blick auf den geschundenen Fenris werfen, ehe deine Sicht verschwimmt.');
				item_add($Char->acctid,'draupnir');
				addnav('Aufwachen',$g_str_base_file.'&op=epilogue&act=wakeup');
				break;
			}
		case 'wakeup':
			{
				$str_output .= get_title('Erwache!').'Du erwachst umgeben von Bäumen. In der Nähe hörst du die Geräusche einer Stadt. Dunkel erinnerst du dich daran, dass du ein neuer Krieger bist, und an irgendwas von gefährlichen Kreaturen, die die Gegend heimsuchen. Du beschließt, dass du dir einen Namen verdienen könntest, wenn du dich vielleicht eines Tages diesen abscheulichen Wesen stellst. `n`n`^Du bist von nun an bekannt als `&'.$Char->name.'`^!!`n`n`&Weil du '.$Char->dragonkills.' Heldentaten vollbracht hast, startest du mit einigen Extras. Außerdem behältst du alle zusätzlichen Lebenspunkte, die du dir verdient oder erkauft hast. `n`n`^Du bekommst '.$g_arr_current_boss['gain_charm'].' Charmepunkte für deinen Sieg über den Fenriswolf. ';

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
	output('Du merkst, dass deine Entscheidung, dich dem Kampf zu stellen, dein Leben kosten könnte, doch sobald du dich auch nur nach einer Gelegenheit umsiehst, vor deinem knurrenden Gegner zu fliehen, stellt dieser sich dir in den Weg. Anders als gedacht stellt Gleipnir kein Hindernis für ihn dar, die Fessel ist schlicht zu lang, um dir zur Flucht zu verhelfen.');
}

function boss_do_fight()
{
	global $battle;
	$battle = true;
}

function boss_do_victory()
{
	global $g_str_base_file,$badguy,$flawless,$Char;

	boss_calc_victory_bonus();

	music_set('drachenkill',0);

	$flawless = 0;
	if ($badguy['diddamage'] != 1)
	{
		$flawless = 1;
	}
	addnews('`#'.$Char->login.'`# hat sich den Titel `&'.$Char->title.'`# für die `^'.$Char->dragonkills.'`#te erfolgreiche Heldentat verdient!');
	//Dieser Text ist noch fertig anzupassen!
	headoutput(get_title('Sieg!').'`&Nach einem letzten Angriff geht der Fenriswolf in die Knie, hechelnd sieht er zu dir auf, gequält von dem Gedanken daran, von einem Sterblichen besiegt worden zu sein.`n`n<hr>`n');
	addnav('Weiter',$g_str_base_file.'&op=epilogue&flawless='.$flawless);
}

function boss_do_flawless_victory()
{
	boss_calc_flawless_victory_bonus();
}

function boss_do_defeat()
{
	global $g_arr_current_boss;
	headoutput(get_title('Niederlage').$g_arr_current_boss['name'].'`&hat dich verschlungen, doch allein dafür den Kampf gewagt zu haben, ist dir ein Aufenthalt in Walhall gewiss.`n`4Du hast dein ganzes Gold verloren!`n
	Du kannst morgen wieder kämpfen.`0`n`n<hr>`n');
	boss_calc_defeat();
	addnav('Tägliche News','news.php');
}

function boss_get_victory_news_text()
{
	global $Char;
	$str_news = '`&'.$Char->name.'`& hat den Fenriswolf gebändigt!';
	return $str_news;
}
function boss_get_defeat_news_text()
{
	global $Char;
	$str_news = '`%'.$Char->name.'`5 diente dem Fenriswolf als Mahlzeit.';
	return $str_news;
}
?>