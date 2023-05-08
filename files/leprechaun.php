<?php

require_once("common.php");

if (!isset($session)) exit();

$file = basename(__FILE__);

page_header("Leprechaun");

switch($_GET['op'])
{
	case "give" :
		if ($_GET['what'] == "six")
		{
			$str_out .= "Du überreichst dem kleinen Mann dein etwas ungewöhnliches Fundstück und wartest seine Reaktion ab.`n";
			$str_out .= "Er betrachtet deinen Fund eine Weile lang verwundert, nimmt ihn dann aber an und reicht dir zum Dank einen ";
			if (e_rand(1,2) == 1)
			{
				item_add($session['user']['acctid'],"grsshltrnk");
				$str_out .= "Heiltrank.";
			}
			else
			{
				$session['user']['gems']++;
				$str_out .= "Edelstein.";
			}
			item_delete("owner=".$session['user']['acctid']." AND tpl_id='cloversix'",1);
		}
		else
		{
			$gold = e_rand(700,2300);
			$session['user']['gold'] += $gold;
			item_delete("owner=".$session['user']['acctid']." AND tpl_id='cloverfour'",1);
			$str_out .= "Du gibst ihm ein vierblättriges Kleeblatt, er lächelt und reicht dir $gold Goldstücke.";
		}
		$str_out .= "`nAußerdem wirkt er einen Zauber auf dich, warnt dich aber zugleich, dass er nicht garantieren kann, dass dieser Zauber auch angenehme Folgen hat.`n`n";
		$spell = e_rand(1,4);
		switch($spell)
		{
			case 4:
				$str_out .= "Du ziehst von dannen und freust dich so sehr über deinen Gewinn, dass du einen hinterhältigen Kuhfladen nicht bemerkst, der erst geschickt deine Bewegungsrichtung ändert und anschließend deine Kleidung und dein Gesicht ziert.`nDu verlierst einen Charmepunkt.";
				$session['user']['charm']--;
				break;
			case 3:
				$str_out .= "Du lächelst fröhlich in die Welt und gehst Richtung Wald, als du plötzlich über einen Stein stolperst und mit dem Gesicht auf etwas Hartem landest. Das hat zwar ein wenig geschmerzt, aber die Erfahrung, dass es sich um einen kleinen Beutel mit 200 Goldstücken darin handelte, lindert den Schmerz ein wenig.";
				$session['user']['hitpoints'] = max(1,0.9*$session['user']['hitpoints']);
				$session['user']['gold'] += 200;
				break;
			case 2:
				$str_out .= "Du schließt die Augen und genießt erfreut die frische Luft und das Zwitschern der Vögel... das... seltsamerweise immer lauter wird. Deine Augen öffnen sich, nur um einen Vogel mit einem etwas sehr spitzen Schnabel direkt auf dein Gesicht zurasen zu sehen. Das ist eine der Erfahrungen, die man lieber nicht machen will.`n";
				$str_out .= "Nach einem kurzen (und für dich leider nicht siegreichen) Kampf mit dem leicht wahnsinnigen Vogel hältst du mit einer Hand die schmerzenden Stellen in deinem Gesicht und beobachtest, wie dich der kleine Leprechaun mit einem entschuldigenden \"Ich-habs-dir-ja-gesagt\"-Blick ansieht. Leicht genervt entfernst du dich.";
				$session['user']['hitpoints'] = max(1,0.5*$session['user']['hitpoints']);
				break;
			case 1:
				$str_out .= "Du fühlst dich auf einmal deutlich besser als gut, kannst es aber nicht erklären. Du erhältst einen zusätzlichen Lebenspunkt.`nWar vermutlich die Urstrahlung.";
				$session['user']['maxhitpoints']++;
				break;
		}
		break;
	default :
		$str_out .= "Du gehst auf das kleine grüne Männlein zu, das du hier noch nie gesehen hast. Neugierig und freundlich fragst du ihn, wer er denn sei und wieso er sich hier aufhält. Das Männlein grinst und eröffnet dir mit feiner Fistelstimme, dass er ein Leprechaun sei und hier nach vierblättrigen Kleeblättern Ausschau hält (was dir ein wenig seltsam vorkommt, da du auf dieser Wiese bisher keine Kleeblätter entdecken konntest). Er bittet dich darum, ihm in dem Fall, dass du zufällig über welche stolperst, auch welche mitzubringen und er verspricht dir, dass er sich auch dafür erkenntlich zeigen würde.`n Er flüstert dir im Geheimen zu, dass er daraus \"Glückliches-Kleeblatt\"-Bier brauen will, das einen ziemlich glücklich machen soll. Dieser Logik kannst du nicht widersprechen.";
		if (item_get("i.owner=".$session['user']['acctid']." AND tpl_id='cloversix'"))
		{
			addnav("Sechsblättriges geben",$file."?op=give&what=six");
		}
		if (item_get("i.owner=".$session['user']['acctid']." AND tpl_id='cloverfour'"))
		{
			addnav("Vierblättriges geben",$file."?op=give&what=four");
		}
}

output($str_out);

addnav("Zurück zur Wiese","forest_rpg_places.php?op=grassyfield");

page_footer();

?>