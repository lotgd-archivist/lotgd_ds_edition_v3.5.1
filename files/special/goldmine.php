<?php

/**
Neue Goldmine v1.0
by Maris (Maraxxus@gmx.de)
**/

require_once(LIB_PATH.'profession.lib.php');

//Definitionen
$bag = 5; // Maximum an Beute

$session['user']['specialinc']='goldmine.php';
$mountbuff = utf8_unserialize($session['user']['specialmisc']);

if ($_GET['op']=='leave')
{
	$session['user']['specialinc']='';
	redirect("forest.php");
}

// Extrastauraum bei besonderen Tieren
$mount_enters=$_GET['mount_enters'];
if ($mount_enters) $bag+=$playermount['mine_bag'];

function digging ($what, $level, $sort=1)
{
	global $session,$bag;
	$chance = 10+($level*10);
	if ($session['user']['race'] == 'zwg')
	{
		$chance+=5;
	}
	else
	if ($session['user']['race'] == 'elf')
	{
		$chance-=5;
	}
	$success = 0;
	$result = '';
	if ($session['user']['pqtemp'])
	{
		$beute = utf8_unserialize($session['user']['pqtemp']);
	}
	else
	{
		$beute = array();
	}

	if(!is_array($beute))
	{
		$beute = array();
	}

	switch ($what)
	{
		case 1:
			$chance+=5;
			if (e_rand(1,100)<=$chance)
			{
				$success = 1;
				$result = '`n`^Du schaffst es einen schönen Brocken Erz aus der Stollenwand zu schlagen!`n';
				if (count($beute)>=$bag)
				{
					$result .='`4Leider kannst du ihn nicht mehr mitnehmen, weil dein Beutesack bereits voll ist.`n';
				}
				else
				{
					$beute[] = 'erz';
					$session['user']['pqtemp'] = utf8_serialize($beute);
				}
			}
			break;

		case 2:
			$chance-=5;
			if (e_rand(1,100)<=$chance)
			{
				$success = 1;
				$result = '`n`^Es gelingt dir ein schönes Goldnugget zu finden!`n';
				if ($sort==2) $result .= 'Bei der Größe wird es sicher einiges wert sein.`n';
				if (count($beute)>=$bag)
				{
					$result .='`4Dein Beutesack ist jedoch bereits voll, so dass du das Nugget leider hier lassen musst.`n';
				}
				else
				{
					if ($sort==1)
					{
						$beute[] = 'gold';
					}
					else $beute[] = 'gold2';
					$session['user']['pqtemp'] = utf8_serialize($beute);
				}
			}

			break;

		case 3:
			$chance-=10;
			if (e_rand(1,100)<=$chance)
			{
				$success = 1;
				$result = '`n`^Du findest einen in Stein eingeschlossenen Edelstein!`n';
				if ($sort==2) $result .= 'Er scheint sogar besonders rein zu sein.`n';
				if (count($beute)>=$bag)
				{
					$result .='`4Jedoch kannst du ihn nicht mit hinauf nehmen, da du schon genug andere Dinge in deinen Beutesack gepackt hast.`n';
				}
				else
				{
					if ($sort==1)
					{
						$beute[] = 'gem';
					}
					else $beute[] = 'gem2';
					$session['user']['pqtemp'] = utf8_serialize($beute);
				}
			}
			break;
	}
	if ($success!=1)
	{
		$result .= "`n`2Du findest nichts außer wertlosen Steinen und einem Tierschädel.`n";
	}

	if (e_rand(1,3) == 2) switch (e_rand(1,12)+$level*2)
	{
		case 1: case 2: case 3: case 4: case 5: case 6: case 7:
			break;

		case 8: case 9: case 10: case 11: case 12: case 13: case 14:
			$loss = e_rand(round($session['user']['maxhitpoints']*0.15),round($session['user']['maxhitpoints']*0.2));
			$result .= "`n`4Als du nach deinem Fund greifst, um ihn näher zu untersuchen rutschen lockere Steine und Staub herab und klemmen deinen Arm ein.`nBeim Herausziehen schneidest du dich an einer scharfen Kante und verlierst $loss Lebenspunkte.`n";
			$session['user']['hitpoints']-=$loss;
			break;

		case 15:
		case 16:
		case 17:
		case 18:
			$loss = e_rand(round($session['user']['maxhitpoints']*0.29),round($session['user']['maxhitpoints']*0.33));
			$result .= "`n`4Leider war dein Schlag gegen die Wand ein wenig zu heftig und die Decke und mehrere, zum Teil große, Felsen stürzen auf dich herab und treffen dich an Kopf und Schulter, du verlierst $loss Lebenspunkte!`n";
			$session['user']['hitpoints']-=$loss;
			break;

		case 19:
		case 20:
		case 21:
		case 22:
			$result .= "`n`4Du hast mit deiner Graberei einen Einsturz verursacht! Lautes Grollen ist das letzte was du hörst als du in dem in sich zusammenfallenden Stollen begraben wirst.`n";
			$session['user']['hitpoints']=0;
			break;
	}

	return($result);
}

switch ($_GET['op'])
{
	case 'exit':
		if ($mount_enters==0 && is_array($mountbuff) && count($mountbuff) > 0 && isset($mountbuff['name']))
		{
			$session['bufflist']['mount']=$mountbuff;
			unset($mountbuff);
		}
		$output_str .= "`n`^Du hast es geschafft die Mine lebend zu verlassen!`n`nIn deinem Beutesack befinden sich`n";
		$xp_gain = 0;
		$wk_loss = 1;
		if (trim($session['user']['pqtemp']))
		{
			$beute = adv_unserialize($session['user']['pqtemp']);
						
			$erz = 0;
			$gold = 0; $gold2 = 0;
			$gem = 0; $gem2 = 0;
			$xp_gain = 0;
			$wk_loss = 1;

			foreach ($beute as $key => $val)
			{
				$wk_loss++;
				switch ($val)
				{
					case 'erz':
						$erz ++;
						$xp_gain++;
						item_add($session['user']['acctid'],0,item_get_tpl(' tpl_id="erz" ' ));
						break;

					case 'gold':
						$gold ++;
						$xp_gain+=2;
						item_add($session['user']['acctid'],0,item_get_tpl(' tpl_id="nugget" ' ));
						break;

					case 'gold2':
						$gold2 ++;
						$xp_gain+=2;
						item_add($session['user']['acctid'],0,item_get_tpl(' tpl_id="nuggetbig" ' ));
						break;

					case 'gem':
						$gem ++;
						$xp_gain+=3;
						item_add($session['user']['acctid'],0,item_get_tpl(' tpl_id="gemminor" ' ));
						break;

					case 'gem2':
						$gem2 ++;
						$xp_gain+=3;
						item_add($session['user']['acctid'],0,item_get_tpl(' tpl_id="gemmajor" ' ));
						break;

					case 'kristall':
						$crystal=1;
						$xp_gain+=6;
						item_add($session['user']['acctid'],0,item_get_tpl(' tpl_id="ranfcrst" ' ));
						break;
				}
			}
			$output_str .= "`^$erz Klumpen Eisenerz,`n$gold kleine und $gold2 große Goldnuggets,`n$gem unreine und $gem2 reine Rohedelsteine";
			if ($crystal==1)
			{
				$output_str .= "`n`n`^und ein Kristall vom tiefsten Punkt der Mine!";
			}
			else
			{
				$output_str .= ".";
			}
		}
		else $output_str .= "`^leider gar keine Reichtümer.`n`nAber wenigstens lebst du...";
		$output_str .= "`@`n`nFür dein Abenteuer in der Mine verlierst du $wk_loss Waldkämpfe.";

		$player = user_get_aei('job');
		if ($player['job'] == JOB_MINER)
		{
			$exp_gain = round($session['user']['experience']/100*$xp_gain);
			if ($exp_gain<1000) $exp_gain = 1000;
			$output_str .= "`n`n`^Als Bergarbeiter bekommst du für die gesammelten Bergbauerfahrungen in der Mine $exp_gain Erfahrungspunkte!`n`n";
			$session['user']['experience']+=$exp_gain;
		}

		$session['user']['turns']-=$wk_loss;
		if ($session['user']['turns']<0) $session['user']['turns']=0;
		$session['user']['pqtemp']='';
		$session['user']['specialinc']='';
		addnav("Weiter","forest.php");
		break;

	case 'enter':
		if (array_key_exists('start', $_GET) && $_GET['start']=="true"){
			$arr_aei = user_get_aei('goldmine_visits');
			user_set_aei(array('goldmine_visits'=>($arr_aei['goldmine_visits']+1)));
		}
		
		$level = $_GET['level'];
		$gallery = $_GET['gallery'];
		if ($mount_enters==0 && is_array($session['bufflist']['mount']))
		{
			$mountbuff=$session['bufflist']['mount'];
			unset($session['bufflist']['mount']);
		}
		if ($gallery==0 || $_GET['pos']<=0 || !$_GET['pos'])
		{
			switch ($level)
			{
				case 0:
					$output_str = "`2Du stehst nun im Hauptschacht der Mine, auf der obersten Ebene.`nFahles Tageslicht erreicht dich durch den Ausgang, der sich nicht unweit von dir befindet.`nÜber den Hauptschacht ist es dir möglich mittels eines kleinen Drehkurbelaufzuges in alle Ebenen der Mine vorzudringen.`n`nJe tiefer du dabei gehst, desto höher ist die Chance, dass du noch unberührte Adern findest. Allerdings sind die Bereiche, in denen bislang kaum ein Mensch gearbeitet hat, mit vielerlei Gefahren bestückt! Du könntest dort unten also sehr schnell den Tod finden.`n";
					addnav("Aufzug");
					addnav("Hinunter","forest.php?op=enter&level=1&gallery=0&mount_enters=$mount_enters");
					addnav("Sonstiges");
					addnav("Die Mine verlassen","forest.php?op=exit");
					break;

				case 1:
					$output_str = "`2Du begibst dich mit dem Kurbelaufzug zur ersten Ebene der Mine.`nObwohl du dich ausgiebig umsiehst, entdeckst du nichts außer ein paar Stollen, die jedoch schon lange erschöpft sind.`nEs scheint auf dieser Ebene wirklich nichts für dich zu geben.`n";
					addnav("Aufzug");
					addnav("Hinauf","forest.php?op=enter&level=0&gallery=0&mount_enters=$mount_enters");
					addnav("Hinunter","forest.php?op=enter&level=2&gallery=0&mount_enters=$mount_enters");
					break;

				case 2:
					$output_str = "`2Nun befindest du dich auf der zweiten Ebene der Mine.`nHier ist es dunkel und das Echo deiner Schritte hallt durch die endlos scheinenden Gänge. Die Gewissheit dass der Ausgang nur zwei Etagen über dir liegt wiegt dich ein klein wenig in Sicherheit.`nAuf dieser Ebene kannst du lediglich Eisenerzstollen ausmachen, welche allerdings recht gut ausgebaut sind. Vielleicht findest du ja hier etwas brauchbares.`n";
					addnav("Aufzug");
					addnav("Hinauf","forest.php?op=enter&level=1&gallery=0&mount_enters=$mount_enters");
					addnav("Hinunter","forest.php?op=enter&level=3&gallery=0&mount_enters=$mount_enters");
					addnav("Sonstiges");
					addnav("Erz-Stollen","forest.php?op=enter&level=2&gallery=1&mount_enters=$mount_enters&pos=1");
					break;

				case 3:
					$output_str = "`2Hier, auf der 3. Ebene des Minenschachtes, werden die Geräusche schon merklich dumpfer.Nichts lässt dich mehr erahnen, wo über dir der Ausgang ist.`nEs beschleicht dich ein leichtes Gefühl der Beklemmung hier unten, allerdings macht der alte Kurbelaufzug einen soliden und zuverlässigen  Eindruck.`nAuf dieser Ebene gibt es neben Eisenerzstollen auch eine alte Goldader, die jedoch schon so gut wie erschöpft ist. Aber vielleicht kannst du ja trotzdem noch das ein oder andere Nugget finden.`n";
					addnav("Aufzug");
					addnav("Hinauf","forest.php?op=enter&level=2&gallery=0&mount_enters=$mount_enters");
					addnav("Hinunter","forest.php?op=enter&level=4&gallery=0&mount_enters=$mount_enters");
					addnav("Sonstiges");
					addnav("Erz-Stollen","forest.php?op=enter&level=3&gallery=1&mount_enters=$mount_enters&pos=1");
					addnav("Gold-Stollen","forest.php?op=enter&level=3&gallery=2&mount_enters=$mount_enters&sort=1&pos=1");
					break;

				case 4:
					$output_str = "`2Die 4. Ebene der Mine stellt so ziemlich den Mittelpunkt des ganzen Hauptschachtes dar.`nVon hier aus ist es etwa gleich weit nach oben wie nach unten. Es ist schon ein merkwürdiges Gefühl, das dich beschleicht, wenn du daran denkst, dass du hier ganz allein so tief unter der Erde bist.`nDiese Ebene bietet einen Nebenzweig der Goldader, welcher jedoch noch nicht erschöpft zu sein scheint. Hier ist die Chance etwas zu finden etwas realistischer.`n";
					addnav("Aufzug");
					addnav("Hinauf","forest.php?op=enter&level=3&gallery=0&mount_enters=$mount_enters");
					addnav("Hinunter","forest.php?op=enter&level=5&gallery=0&mount_enters=$mount_enters");
					addnav("Sonstiges");
					addnav("Gold-Stollen","forest.php?op=enter&level=4&gallery=2&mount_enters=$mount_enters&sort=2&pos=1");
					break;

				case 5:
					$output_str = "`2Du befindest dich nun in der 5. Ebene der Mine.`nLeider musst du feststellen, dass ein gewaltiger Einsturz alle Stollen auf dieser Ebene unbegehbar gemacht hat. Leises, stetiges Bersten und Knirschen lässt dich zu dem Schluss kommen, dass hier immer noch alles derart einsturzgefährdet ist, dass du es nicht wagst dich einem der eingestürzten Stollen zu nähern.`n";
					addnav("Aufzug");
					addnav("Hinauf","forest.php?op=enter&level=4&gallery=0&mount_enters=$mount_enters");
					addnav("Hinunter","forest.php?op=enter&level=6&gallery=0&mount_enters=$mount_enters");
					break;

				case 6:
					$output_str = "`2In der 6. Ebene der Mine, in der du dich gerade befindest, wird die Luft schon spürbar stickiger und schwerer zu atmen. Seltsame Geräusche dringen zu dir vor und lassen dich erahnen, dass du hier nicht mehr allein bist.`nWas auch immer in diesen Tiefen haust hat sich wohl aus den höheren Ebenen zurückgezogen um hier ungestört zu sein. Und wenn du ehrlich zu dir bist, willst du seine Ruhe auch nicht stören.`nAuf dieser Ebene gibt es Stollen für Eisenerz, sowie eine Edelsteinader. Bei den wenigen wagemutigen Abenteurern, die jemals so weit unten waren könnte es durchaus sein, dass du hier den großen Fund machst!`n";
					addnav("Aufzug");
					addnav("Hinauf","forest.php?op=enter&level=5&gallery=0&mount_enters=$mount_enters");
					addnav("Hinunter","forest.php?op=enter&level=7&gallery=0&mount_enters=$mount_enters");
					addnav("Sonstiges");
					addnav("Erz-Stollen","forest.php?op=enter&level=6&gallery=1&mount_enters=$mount_enters&pos=1");
					addnav("Edelstein-Stollen","forest.php?op=enter&level=6&gallery=3&mount_enters=$mount_enters&sort=1&pos=1");
					break;

				case 7:
					$output_str = "`2Mit einem deutlich spürbaren Ruck kam der Aufzug auf dieser Ebene zum stoppen und ein beklemmendes Gefühl umfängt dich, als dir klar wird, dass es damit nicht mehr tiefer hinab geht.`nDu kannst kaum atmen und fragst dich, ob du es schaffen wirst die Kurbel des Aufzuges zu drehen um den Ausgang zu erreichen bevor du bewusstlos wirst.`nAuf dieser Ebene gibt es einen Erzstollen, sowie Gold- und Edelsteinadern. Du bist dir sicher, dass diese kaum berührt sind und du hier den großen Reichtum finden kannst.`nEine treppenartige Felsenstruktur erlaubt es dir noch weiter hinabzusteigen.`n";
					addnav("Aufzug");
					addnav("Hinauf","forest.php?op=enter&level=6&gallery=0&mount_enters=$mount_enters");
					addnav("Felsentreppe");
					addnav("Hinunter","forest.php?op=enter&level=8&gallery=0&mount_enters=$mount_enters");
					addnav("Sonstiges");
					addnav("Erz-Stollen","forest.php?op=enter&level=7&gallery=1&mount_enters=$mount_enters&pos=1");
					addnav("Gold-Stollen","forest.php?op=enter&level=7&gallery=2&mount_enters=$mount_enters&sort=2&pos=1");
					addnav("Edelstein-Stollen","forest.php?op=enter&level=7&gallery=3&mount_enters=$mount_enters&sort=2&pos=1");
					break;

				case 8:
					$output_str = "`2Auch wenn du nicht wirklich weißt warum kletterst du die Felsen hinab und findest dich in einer großen Höhle wieder. Unangenehm ist das Gefühl als dir kaltes Wasser in die Stiefel läuft und du feststellen musst, dass du bis über die Knöchel im Wasser stehst.`n`\$Ranf... `2 hat irgendwer mit Blut an einen Felsen geschrieben, und du glaubst etwas aus den Augenwinkeln gesehen zu haben.`nDann jedoch fällt dein Blick auf eine kleine Ebene, auf der sich glitzernde Kristalle befinden, welche von großen, sich windenden Tentakeln umgeben sind.`nWirst du es wagen einen der Steine zu greifen oder willst du lieber fort?`n";
					
					$arr_item_tpl = item_get_tpl('tpl_id="ranfcrst"');
					$int_count = item_count('tpl_id="ranfcrst" AND owner='.$Char->acctid);					
					if($int_count>=$arr_item_tpl['maxcount_per_user'])
					{
						$output_str .= 'Schließlich hast du ja auch schon '.$int_count.' Kristal'.($int_count > 1?'e':'').' und mehr als '.$arr_item_tpl['maxcount_per_user'].' kannst du ja eh nicht bei dir tragen...also überleg dir, ob es dir das Risiko wert ist!';
					}
					
					addnav("Felsentreppe");
					addnav("Hinauf","forest.php?op=enter&level=7&gallery=0&mount_enters=$mount_enters");
					addnav("Kristalle");
					addnav("Zugreifen","forest.php?op=enter&level=8&gallery=4&mount_enters=$mount_enters&pos=1");
					
			}
		}
		else
		{
			$dig = $_GET['dig'];
			$pos = $_GET['pos'];
			$sort = $_GET['sort'];
			switch ($gallery)
			{
				case 1:
					if ($_GET['pos']!=2)
					{
						$output_str = "`2Du folgst dem Stollen über einen einigermaßen befestigten Weg.`nDas silbrig-graue Glänzen an den Schachtwänden lässt dich darauf schließen, dass du dich in einem Eisenerzstollen befindest.`nStaub und kleine Steine knirschen unter deinen Füßen als du den Gang entlang gehst.";
						addnav("Weiter");
						addnav("Zum Stollen","forest.php?op=enter&level=$level&gallery=1&mount_enters=$mount_enters&sort=$sort&pos=".($pos+1));
						addnav("Zurück");
						addnav("Zum Hauptschacht","forest.php?op=enter&level=$level&gallery=0&mount_enters=$mount_enters&sort=$sort&pos=".($pos-1));
					}
					else
					{
						$output_str = "`2Du folgst dem Weg bis an sein Ende.`nHier wäre eine gute Stelle um nach Eisenerz zu graben.`nÜber die Ausrüstung brauchst du dir keine Sorgen zu machen, denn du findest neben ein paar Knochen auch Spitzhacken und Schaufeln in ausreichender Menge.";
						addnav("Graben");
						addnav("Nach Erz graben","forest.php?op=enter&level=$level&gallery=1&dig=1&mount_enters=$mount_enters&sort=$sort&pos=$pos");
						addnav("Zurück");
						addnav("Zum Hauptschacht","forest.php?op=enter&level=$level&gallery=1&mount_enters=$mount_enters&sort=$sort&pos=".($pos-1));
					}
					break;

				case 2:
					if ($_GET['pos']!=2)
					{
						$output_str = "`2Du durchschreitest den in sanftem Gold schimmernden schmalen Stollen.`nVielleicht kannst du ja dieser Goldader noch den ein oder anderen Klumpen des Edelmetalles entreißen.`nJe tiefer du vordringst, desto häufiger stolperst du über Knochen und Dinge von denen du gar nicht wissen willst, worum es sich handelt.";
						addnav("Weiter");
						addnav("Zum Stollen","forest.php?op=enter&level=$level&gallery=2&mount_enters=$mount_enters&sort=$sort&pos=".($pos+1));
						addnav("Zurück");
						addnav("Zum Hauptschacht","forest.php?op=enter&level=$level&gallery=2&mount_enters=$mount_enters&sort=$sort&pos=".($pos-1));
					}
					else
					{
						$output_str = "`2Du folgst dem Gang bis an sein Ende.`nHier wäre jetzt eine geeignete Stelle um Gold zu suchen. Die Reste eines verendeten Schürfers liegen vor dir auf dem Boden, seinen Hammer hält er noch in der Hand. Nun liegt es an dir ob du auch dein Glück versuchen willst.";
						addnav("Graben");
						addnav("Nach Gold graben","forest.php?op=enter&level=$level&gallery=2&dig=1&mount_enters=$mount_enters&sort=$sort&pos=$pos");
						addnav("Zurück");
						addnav("Zum Hauptschacht","forest.php?op=enter&level=$level&gallery=2&mount_enters=$mount_enters&sort=$sort&pos=".($pos-1));
					}
					break;

				case 3:
					if ($_GET['pos']!=2)
					{
						$output_str = "`2Du folgst dem unscheinbaren Gang des Edelsteinstollens und kannst auf den ersten Blick überhaupt nichts besonderes ausmachen. Dennoch hoffst du den ein oder anderen Gemmenstein zu finden und sicher zum Ausgang bringen zu können.`nDass diese Stollen auch immer so lang sein müssen.";
						addnav("Weiter");
						addnav("Zum Stollen","forest.php?op=enter&level=$level&gallery=3&mount_enters=$mount_enters&sort=$sort&pos=".($pos+1));
						addnav("Zurück");
						addnav("Zum Hauptschacht","forest.php?op=enter&level=$level&gallery=3&mount_enters=$mount_enters&sort=$sort&pos=".($pos-1));
					}
					else
					{
						$output_str = "`2Du erreichst irgendwann das Ende des Gangs und findest eine Stelle, die sich sehr gut zum Graben zu eignen scheint.`nIrgendjemand hat wohl seine Schürferausrüstung hier unten vergessen, und du fragst dich warum du keine weiteren Spuren ihres Besitzers entdecken kannst. Andererseits wiederum willst du es auch gar nicht wirklich wissen.";
						addnav("Graben");
						addnav("Nach Edelsteinen suchen","forest.php?op=enter&level=$level&gallery=3&dig=1&mount_enters=$mount_enters&sort=$sort&pos=$pos");
						addnav("Zurück");
						addnav("Zum Hauptschacht","forest.php?op=enter&level=$level&gallery=3&mount_enters=$mount_enters&sort=$sort&pos=".($pos-1));
					}
					break;

				case 4:
					//Tauschquest
				    $indate = getsetting('gamedate','0005-01-01');
				    $date = explode('-',$indate);
				    $month = $date[1];
					if($session['user']['exchangequest']==8 && $month==5 && $_GET['level']==8 && $_GET['exq']!='goon')
					{
						$output_str.='`%Auf deiner Erkundung am tiefsten Punkt der Mine entdeckst du einen blonden Zwerg, welcher, wie es aussieht, erfolglos auf dem Gestein herumhackt. Als du näher kommst hörst du ihn leise fluchen "`^Hätte ich meine Donneraxt nicht verloren, wäre das hier alles kein Problem.`%"
						`nDa fällt dir ein, dass du ja eine merkwürdige Axt nutzlos in deinem Beutel herumschleppst.
						`nWillst du dem Zwerg deine Axt anbieten oder ziehst du es vor, einen der hier liegenden Kristalle zu nehmen und zu verschwinden?`0';
						addnav('`%Zwerg ansprechen`0','exchangequest.php?mount_enters='.$mount_enters);
						addnav('Kristall nehmen',"forest.php?op=enter&level=8&gallery=4&mount_enters=$mount_enters&pos=1&exq=goon");
						continue;
					}
					//end Tauschquest
					if ($session['user']['pqtemp'])
					{
						$beute = utf8_unserialize($session['user']['pqtemp']);
					}
					else
					{
						$beute = array();
					}

					if(!is_array($beute))
					{
						$beute = array();
					}

					foreach ($beute as $key => $val)
					{
						if ($val=='kristall') $kristall=1;
					}

					if (count($beute)>=$bag)
					{
						$output_str .='`4Leider kannst du ihn nicht mehr mitnehmen, weil dein Beutesack bereits voll ist.`n';
						addnav("Zurück","forest.php?op=enter&level=$level&gallery=0&mount_enters=$mount_enters");
					}
					else if ($kristall==1)
					{
						$output_str .='`4Leider hast du bereits einen dieser Kristalle mitgenommen und willst nicht wirklich nochmal das Risiko eingehen.`n';
						addnav("Zurück","forest.php?op=enter&level=$level&gallery=0&mount_enters=$mount_enters");
					}
					else if (e_rand(1,2)==1)
					{
						$output_str .= "`4Behende näherst du dich den Kristallen, doch als du zugreifen willst hat dich schon längst ein Tentakel ergriffen und unter Wasser gezogen.`nDie Kristalle sind auch das letzte was du siehst, als es dunkel um dich herum wird.";
						$session['user']['hitpoints']=0;
					}
					else if (e_rand(1,2)==2)
					{
						$output_str .= "`@Du setzt vorsichtig einen Schritt vor den anderen, aber egal wohin du gehst und wolang du es versuchst, immer sind dort mehrere Tentakel im Weg.`nDir bleibt nichts anderes übrig als diesen Versuch aufzugeben und es später noch einmal zu probieren.";
						addnav("Zurück","forest.php?op=enter&level=$level&gallery=0&mount_enters=$mount_enters");
					}
					else
					{
						$output_str .= "`^Du hälst die Luft an und näherst dich langsam der glitzernden Ebene.`nFlink schnappst du dir einen der Kristalle und siehst zu dass du davon kommst.";
						$beute[] = 'kristall';
						$session['user']['pqtemp'] = utf8_serialize($beute);
						addnav("Weg hier","forest.php?op=enter&level=$level&gallery=0&mount_enters=$mount_enters");
					}
					break;
			}
			if ($dig==1) $output_str .= "`n".digging($_GET['gallery'],$level,$sort);
		}
		break;

	default:
		$arr_aei = user_get_aei('goldmine_visits,job');
		$maxvisits = 2;
		if ($arr_aei['job'] == JOB_MINER){
			$maxvisits = 3;
		}
		if ($session['user']['dragonkills']<1)
		{
			$output_str = '`2Du durchschreitest einen abgelegenen Teil des Waldes und entdeckst, gut verborgen durch dichte Sträucher und Hecken, eine alte Goldmine.`nMan erzählt sich, viele Abenteurer haben schon in den tiefen Stollen auf ihrer Suche nach Reichtum den Tod gefunden und deshalb umfängt auch dich ein mulmiges Gefühl, als du die Holztafeln erblickst, die ausdrücklich vor dem Betreten der Mine warnen.`n`n`6Zwar wäre es ein leichtes für dich, durch einen Spalt zwischen den losen Bretter zu schlüpfen, die den Eingang zur Mine verriegeln, doch angesichts deiner mangelnden Erfahrung scheint dir dieses Abenteuer etwas zu riskant.`n';
			addnav('Zurück in den Wald','forest.php?op=leave');
		}
		elseif($arr_aei['goldmine_visits']>=$maxvisits)
		{
			$output_str = '`2Du gehst in einiger Entfernung an der Mine vorbei und erblickst eine Horde Zwerge darin werkeln. "`yAha, wieder Umbauarbeiten`2", denkst du dir und läufst lieber weiter. Erstens sind arbeitende Zwerge nicht unbedingt freundlich, wenn jemand anders auf ihrer Baustelle spielen will und zweitens hast du eh noch ganz dreckige Finger von deinen vorherigen Buddelarien. Nene, heute lieber ein Bad nehmen, sonst wirst du früher oder später selbst zu einem Minenzwerg...';	
			addnav('Zurück in den Wald','forest.php?op=leave');
		} else {
			
			$output_str ='`2Du durchschreitest einen abgelegenen Teil des Waldes und entdeckst, gut verborgen durch dichte Sträucher und Hecken, die alte Goldmine.`nViele Abenteurer haben schon in den tiefen Stollen auf ihrer Suche nach Reichtum den Tod gefunden und selbst dich umfängt ein mulmiges Gefühl, als du die Holztafeln erblickst, die ausdrücklich vor dem Betreten der Mine warnen.`nEin paar lose Bretter verriegeln den Eingang zur Mine, doch es wäre für dich ein leichtes durch einen Spalt zu schlüpfen.`n';
			$mount_enters=0;
			if (e_rand(1, 100) < $playermount['mine_canenter']) $mount_enters=1;
			if ($mount_enters==1)
			{
				$output_str .= 'Auch dein '.$playermount['mountname'].'`2 kann dich in den Schacht begleiten.`n';
				if ($playermount['mine_bag']!=0)
				{
					$output_str .= "Deswegen wirst du ";
					if ($playermount['mine_bag']>0)
					{
						$output_str .= "`^".$playermount['mine_bag']."`2 Stücke mehr ";
					}
					else
					{
						$output_str .= "`^".abs($playermount['mine_bag'])."`2 Stücke weniger ";
					}
					$output_str .= "mit hinauf bringen können.";
				}
			}
			else
			{
			$output_str .= 'Dein '.$playermount['mountname'].'`2 wirst du allerdings nicht mitnehmen können.`n';
			}
			if ($session['user']['turns']>=3)
			{
				$output_str .= '`nWillst du es wagen die Minen zu betreten?';
				addnav('g?Nach Schätzen graben',"forest.php?op=enter&start=true&level=0&gallery=0&mount_enters=$mount_enters");
				addnav('Zurück in den Wald','forest.php?op=leave');
				
				// Karte von Drawl updaten
				$config = utf8_unserialize($session['user']['donationconfig']);
				if ($config['goldmine']>0 && $_GET['pass']=='conf') $config['goldmine']-=1;
				$session['user']['donationconfig'] = utf8_serialize($config);
			}
			else
			{
				$output_str .= '`n`4Leider bist du schon zu müde um heute nochmal nach Reichtümern zu graben.`n3 Runden solltest du mindestens einplanen!`n';
				addnav('Zurück in den Wald','forest.php?op=leave');
			}
		}
		break;
}

if ($session['user']['hitpoints']>0 && $_GET['dig']!=1 && $_GET['op']=='enter' && ((e_rand(1,12)+$level)>=12))
{
	switch (e_rand(1,9))
	{
		case 1:
		case 2:
		case 3:
			$output_str .= "`n`4Ein paar kleinere Felsen lösen sich von der Decke und prasseln knapp vor dir auf den Boden.`nDa hast du nochmal Glück gehabt!";
			break;

		case 4:
		case 5:
		case 6:
			$loss = e_rand(round($session['user']['maxhitpoints']*0.07),round($session['user']['maxhitpoints']*0.1));
			$output_str .= "`n`4Du stolperst über etwas scharfes und verletzt dir den Fuß.`nDu verlierst $loss Lebenspunkte!`n";
			$session['user']['hitpoints']-=$loss;
			break;

		case 7:
		case 8:
			$loss = e_rand(round($session['user']['maxhitpoints']*0.12),round($session['user']['maxhitpoints']*0.15));
			$output_str .= "`n`4Ein paar lockere Felsbrocken stürzen von der Decke und treffen dich am Kopf!`nDas tat weh, du verlierst $loss Lebenspunkte!`n";
			$session['user']['hitpoints']-=$loss;
			break;

		case 9:
			$loss = e_rand(round($session['user']['maxhitpoints']*0.22),round($session['user']['maxhitpoints']*0.25));
			$output_str .= "`n`4Ein Stützbalken von der Decke bricht laut berstend zusammen und landet mit einem mächtigen Hieb genau auf deiner Schulter. Du verlierst $loss Lebenspunkte und wirst zu Boden gerissen!";
			$session['user']['hitpoints']-=$loss;
			break;
	}
}

if ($session['user']['hitpoints']<=0)
{
	$session['user']['hitpoints']=0;
	clearnav();
	$session['user']['specialinc']='';
	$session['user']['pqtemp']='';

	if ($session['user']['hashorse']>0 && $mount_enters==1)
	{
		if (e_rand(1,100) <= $playermount['mine_cansave'])
		{
			$session['user']['hitpoints']=1;
			$output_str .= "`n`^Als du wieder zur Besinnung kommst findest du dich am Aufzug wieder. Dein ".$playermount['mountname']."`^ hat dich wohl hierher gezogen!`nMit allerletzter Kraft drehst du die Kurbel und begibst dich zum Ausgang, wo du erneut in Ohnmacht fällst.`nDu hast zwar die Mine überlebt, allerdings ist dir dein Beutesack verloren gegangen.`nAußerdem verlierst du alle dir verleibenden Waldkämpfe für heute!";
			$session['user']['turns']=0;
			$session['user']['hitpoints']=1;
			addnav("Weiter","forest.php");
		}
		else
		{
			$output_str .= "`n`4Du bist in der Mine gestorben!`nGlücklicherweise hat es dein ".$playermount['mountname']."`4 geschafft aus der Mine zu entkommen und in die Stadt zu laufen.";
			addnews($session['user']['name'].' `^ging in die Goldmine, kam jedoch nie wieder heraus.');
            CQuest::died();
			addnav("Weiter","shades.php");
		}
	}
	else if ($session['user']['hashorse']>0)
	{
		$output_str .= "`n`4Du bist zwar in der Mine gestorben, aber dein ".$playermount['mountname']."`4 schafft es vom Eingang der Mine aus allein in die Stadt zurück zu finden.";
		addnews($session['user']['name'].' `^ging in die Goldmine, kam jedoch nie wieder heraus.');
        CQuest::died();
		addnav("Weiter","shades.php");
	}
	else
	{
		$output_str .= "`n`4Du bist soeben in der Mine gestorben!";
		addnews($session['user']['name'].' `^ging in die Goldmine, kam jedoch nie wieder heraus.');
        CQuest::died();
		addnav("Weiter","shades.php");
	}
}
output($output_str);
$session['user']['specialmisc'] = utf8_serialize($mountbuff);
?>
