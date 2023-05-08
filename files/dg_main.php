<?php
/*-------------------------------/
Name: dg_main.php
Autor: tcb / talion für Drachenserver (mail: t [-[at]-] ssilo.de), Gildeneinladungen von Takehon
Erstellungsdatum: 6/05 - 9/05
Beschreibung:    Hauptbestandteil des Gildensystems: Übernimmt den größten Teil der Benutzerführung, stellt Gildenviertel etc. dar
Diese Datei ist Bestandteil des Drachenserver-Gildenmods (DG).
Copyright-Box muss intakt bleiben, bei Verwendung Mail an Autor mit Serveradresse.
/*-------------------------------*/

require_once('common.php');
require_once(LIB_PATH.'dg_funcs.lib.php');
require_once('dg_output.php');

checkday();
page_header('Das Gildenviertel');

if($session['user']['guildid']) {
	$leader = ($session['user']['guildfunc'] == DG_FUNC_LEADER) ? true : false;
	$treasure = ($session['user']['guildfunc'] == DG_FUNC_LEADER || $session['user']['guildfunc'] == DG_FUNC_TREASURE) ? true : false;
	$war = ($session['user']['guildfunc'] == DG_FUNC_LEADER || $session['user']['guildfunc'] == DG_FUNC_WAR) ? true : false;
	$members = ($session['user']['guildfunc'] == DG_FUNC_LEADER || $session['user']['guildfunc'] == DG_FUNC_MEMBERS) ? true : false;
	$team = ($leader || $treasure || $war || $members) ? true : false;
	$member = ($session['user']['guildfunc'] != DG_FUNC_APPLICANT && $session['user']['guildfunc']) ? true : false;
	$applicant = ($session['user']['guildfunc'] == DG_FUNC_APPLICANT) ? true : false;
}
// Zutritt in Hinterzimmer?
$private = ($access_control->su_check(access_control::SU_RIGHT_DEBUG) ? true : false);

$op = ($_GET['op']) ? $_GET['op'] : '';
$out = '';

switch($op) {

	case '':    // Gildenviertel

		dg_show_header('`;D`Ya`ts `yGi`&ldenvi`yer`tt`Ye`;l');

		output('`;D`Yu`tr`ych `&ein festes, breites Tor vom Rest der Stadt abgetrennt befindet sich das noble Viertel der Gilden. Rundum ist es von einer hohen, steinernen Mauer umgeben, die kein unbefugtes Eindringen zulässt. Doch auch von hier kannst du schon die hohen Dächer und den offensichtlichen Reichtum bewundern. Vor dem Tor halten zwei schwer bewaffnete Paladine des Königs in glänzender Rüstung Wache. Ihre Hellebarden haben sie so gekreuzt, dass niemand an ihnen vorbeikommt.`n');

		$entry = true;

		if($session['user']['dragonkills'] < 1) {
			output('`&"Was willst du denn hier, Fremder?! Scher dich weg!"`8 mit diesen Worten weist dich der Paladin harsch zurück. Vermutlich solltest du mindestens eine Heldentat vollbracht haben, um überhaupt Zutritt zu erlangen!');
			$entry = false;
		}
		elseif($session['user']['dragonkills'] < getsetting('dgmindkapply',3)) {
			output('Nur nach genauer Prüfung gewähren dir die Wachen Zutritt. Misstrauisch verfolgen sie noch lange deine Schritte, genau wissend, dass du hier eigentlich nichts verloren hast.');
		}
		elseif(!$member) {
			output('Die Paladine trauen dir nicht ganz und halten dich lange mit bohrenden Fragen nach deinen Absichten auf, ehe du endlich weitergehen kannst.');
		}
		elseif($member && !$team) {
			output('Die Wachen schenken dir keine weitere Beachtung, als du durch das Tor eilst. Einem Gildenmitglied vertrauen sie vollkommen.');
		}
		elseif($team) {
			output('Würdevoll salutieren die Wachen, als du geschäftig vorbeieilst.');
		}

		if($entry) {

			$guild = array();
			if($member) {
				$guild = &dg_load_guild($session['user']['guildid'],array('name','top_repu'));
			}

			addcommentary();

			output('`&`nEhrfurchtsvoll betrittst du das Gildenviertel. Zu deiner Linken befindet sich der Gildenrat, das höchste Gremium der Gilden '.getsetting('townname','Atrahor').'s.
			Dort hast du auch Gelegenheit, die verschiedensten Listen zu betrachten und eine eigene Gilde zu gründen.
			Zu beiden Seiten der breiten Straßen liegen all die Prachtbauten der einzelnen Gilden.
			`nEin Schild kündet von der nächsten Lieferung des Königs in `Y'.getsetting('dgkingdays',30).'`& '.(getsetting('dayparts','1') > 1?'Tagesabschnitten':'Tagen').'!
			`nWas hast du n`yun `tv`Yo`;r?`n`n');

			viewcommentary('guildquarter','Sprechen:',20,'spricht');

			addnav('Gildenrat');
			addnav('Zum Ratssaal','dg_council.php?op=council');
			//addnav('Zur Paladinfestung','dg_council.php?op=paladin');

			addnav('Gilden');

			// Auf Einladung zu Gildenfest checken
			foreach($session['guilds'] as $g) {
				if($g['building_vars']['party']['eaten'][$session['user']['acctid']] && $session['user']['guildid'] != $g['guildid']) {
					addnav('Zu Gildenfest von '.$g['name'],'dg_main.php?op=in&subop=party&gid='.$g['guildid']);
				}
			}

			if($guild['name']) {
				addnav('b?'.$guild['name'].'`0 betreten','dg_main.php?op=in&gid='.$session['user']['guildid']);
			}
			addnav('Liste der Gilden','dg_council.php?op=list');
			addnav('Ruhmeshalle der Gilden','dg_council.php?op=hof');

		}

		addnav('Zurück');
		addnav('d?Zum Stadtzentrum','village.php');
		addnav('M?Zum Marktplatz','market.php');

		break;

	case 'show_guild_bio':

		if(empty($_GET['gid'])) {
			redirect('news.php');
		}

		dg_show_guild_bio($_GET['gid']);

		addnav('Geschichte');
		addnav('Die Aufzeichnungen','dg_main.php?op=show_guild_history&gid='.$_GET['gid'] );

		addnav('Sonstiges');

		addnav('d?Zum Stadtzentrum','village.php');
		addnav('G?Zum Gildenviertel','dg_main.php');
		addnav('L?Zurück zur Liste','dg_council.php?op=list');

		/*        if($access_control->su_check(access_control::SU_RIGHT_EDITORGUILDS)) {
		addnav('MOD-Aktionen');
		addnav('X?Gildeneditor','su_guilds.php?op=edit&gid='.$_GET['gid']);
		}
		*/
		break;

	case 'show_guild_history':

		if(empty($_GET['gid'])) {
			redirect('news.php');
		}

		$guild = dg_load_guild($_GET['gid'],array('name'));

		dg_show_header('Bisherige Geschichte der Gilde '.$guild['name']);

		show_history(2,$_GET['gid']);

		addnav('Gildenprofil','dg_main.php?op=show_guild_bio&gid='.$_GET['gid']);

		if ($session['user']['guildid'] == $_GET['gid'] && !$applicant)
		{
			addnav('b?'.$guild['name'].'`0 betreten','dg_main.php?op=in&gid='.$session['user']['guildid']);
		}


		break;

	case 'in':

	// ID-Check
	if($_GET['gid']) {
		$session['gid'] = (int)$_GET['gid'];
	}

	if(!$session['gid']) {
		$session['gid'] = $session['user']['guildid'];
	}

	$gid = $session['gid'];

	if(!$gid) {redirect('dg_main.php');}
	// END ID-Check

	$subop = ($_GET['subop']) ? $_GET['subop'] : '';

	$guild = &dg_load_guild($gid,array(),true);

	$founder = ($session['user']['acctid'] == $guild['founder']) ? true : false;

	if($member) {
		dg_show_state_info($gid);
	}

	switch($subop) {

		case '':    // Gildenhalle

			$show_invent = true;

			addcommentary();

			$sql = 'SELECT n.newstext FROM news n WHERE n.guildid='.$gid.' ORDER BY newsid DESC LIMIT 1';
			$res = db_query($sql);
			$n = db_fetch_assoc($res);

			dg_show_header($guild['name'].'`8 - Gildenhalle');

			if(!$team) {

				if(item_count(' tpl_id="gldprive" AND value1='.$gid.' AND owner='.$session['user']['acctid'])) {$private = true;}

			}


			if($guild['state'] == DG_STATE_INACTIVE) {
				output('`8`b`cDiese Gilde befindet sich im inaktiven Zustand, da entweder keine Führungsmitglieder oder weniger als '.getsetting('dgminmembers',3).' Mitglieder insgesamt vorhanden sind. Eventuell hat diese Gilde auch gegen die Regeln verstoßen. Bei Fragen wendet euch per Anfrage an die Moderatoren. Versucht, etwaige Mißstände zu beheben. Die Administration wird, falls keine Lösung möglich ist, die Gilde löschen. So lange bleiben alle Aktionen bis auf den Mitgliederbereich deaktiviert.`8`b`c`n');
			}
			elseif ($guild['state'] == DG_STATE_IN_PROGRESS) {
				output('`8`b`cZur Zeit wird an eurer Gildenresidenz noch eifrig gewerkelt. Ihr alle hofft, dass bald die nötigen Summen zur Verfügung stehen, um die Arbeit zu vollenden. Bis dahin könnt ihr euch schon einmal an all die neuen Möglichkeiten herantasten.`8`b`n`n');

				$int_missing_gold = max(getsetting('dgguildfoundgold',100000) - $guild['gold'],0);
				$int_missing_gems = max(getsetting('dgguildfoundgems',100) - $guild['gems'],0);

				if(isset($_POST['gold']) || isset($_POST['gems'])) {

					// Einzahlungen abwickeln
					$int_gold_in = (int)$_POST['gold'];
					$int_gems_in = (int)$_POST['gems'];

					$int_gold_in = min($int_missing_gold,$int_gold_in);
					$int_gems_in = min($int_missing_gems,$int_gems_in);

					$int_gold_in = min($int_gold_in,$session['user']['gold']);
					$int_gems_in = min($int_gems_in,$session['user']['gems']);

					$guild['gold'] += $int_gold_in;
					$guild['gems'] += $int_gems_in;
					$session['user']['gold'] -= $int_gold_in;
					$session['user']['gems'] -= $int_gems_in;

					dg_save_guild();

					redirect('dg_main.php?op=in');

				}

				if($int_missing_gold <= 0 && $int_missing_gems <= 0) {
					output('`^Der Bau scheint soweit abgeschlossen. Bei nächster Gelegenheit sollte eure Gilde bezugsfertig sein, solange sie auch genügend Mitglieder (mindestens '.getsetting('dgminmembers',3).') und mindestens einen Gildenführer besitzt.');
				}
				else {
					output('<form method="POST" action="'.$str_lnk.'">
					`^Weiterbauen: Es fehlen noch '.$int_missing_gold.' Gold und '.$int_missing_gems.' Edelsteine!`n`n
					'.($int_missing_gold > 0 ? 'Gold in die Baukasse einzahlen: <input type="text" size="6" maxlength="6" name="gold">`n`n' : '').'
					'.($int_missing_gems > 0 ? 'Edelsteine in die Baukasse einzahlen: <input type="text" size="6" maxlength="6" name="gems">`n`n' : '').'
					<input type="submit" value="Einzahlen!">
					</form>',true);
				}

				output('`c`n`n');

			}

			// Folgende Navihotkeys garantieren
			$accesskeys['d']=1;$accesskeys['m']=1;
			addnav('Verwaltung');
			addnav('R?Mitglieder und Ränge','dg_main.php?op=in&subop=members');
			if($guild['state'] == DG_STATE_ACTIVE) {
				addnav('i?Diplomatie und Kampf','dg_main.php?op=in&subop=war');
				addnav('Schatz & Ausbau','dg_main.php?op=in&subop=treasure');
				addnav('Zum Gewölbe hinabsteigen','dg_main.php?op=in&subop=buildings&building_op=deposit');
			}
			if($guild['ext_room_name'] != '') {
				addnav($guild['ext_room_name'],'dg_main.php?op=in&subop=ext_room');
			}
			if($guild['ext_room_name2'] != '') {
				addnav($guild['ext_room_name2'],'dg_main.php?op=in&subop=ext_room2');
			}
			if($team || $private) {
				addnav('Hinterzimmer','dg_main.php?op=in&subop=private');
			}
			addnav('Information');
			addnav('Schwarzes Brett','dg_main.php?op=in&subop=board');
			addnav('Gildenprofil','dg_main.php?op=in&subop=bio');
			addnav('Gildenalmanach','dg_main.php?op=in&subop=buildings&building_op=explain',false,false,false,false);//original-Hotkey i
			addnav('Die Aufzeichnungen','dg_main.php?op=show_guild_history&gid='.$session['gid']);

			if($team)
			{
				addnav('Amt niederlegen','dg_main.php?op=in&subop=retire',false,false,false,false);
			}

			addnav('Austreten','dg_main.php?op=in&subop=leave',false,false,false,false);

			// Weitesten Ausbau anzeigen
			$best_building = dg_get_max_build($gid);

			// Prüfen ob eigener Text vorhanden in der Datenbank in dg_guilds als guild_own_description
			// Der eigene Text kann von der Gildenführung im Hinterzimmer festgelegt werden
			$guild_own_description = strip_tags($guild['guild_own_description']);
			if ($guild_own_description != '') {
				output('`8'.$guild_own_description.($best_building?'`n`8Besonders sticht ein Teil des Gebäudes namens `i'.$dg_builds[$best_building]['color'].$dg_builds[$best_building]['name'].'`8`i im ungefähren Zustand `i'.$dg_build_levels[ $guild['build_list'][$best_building] ].'`i hervor.':'').'`8`n`n');
			}
			else {
				output('`8'.$dg_child_types[$guild['type']][2].($best_building?'`n`8Besonders sticht ein Teil des Gebäudes namens `i'.$dg_builds[$best_building]['color'].$dg_builds[$best_building]['name'].'`8`i im ungefähren Zustand `i'.$dg_build_levels[ $guild['build_list'][$best_building] ].'`i hervor.':'').'`8`n`n');
			}

			output('`c');
			if($n) {output('`8Letzte Neuigkeit: `i'.$n['newstext'].'`i`n`n');}
			else {output('`8Es gibt keine Neuigkeiten!`n`n');}
			output('`c');

			require_once(LIB_PATH.'board.lib.php');
			output('`c');
			$int_pollrights = ($leader ? 2 : 1);
			if(poll_view('guild'.$gid.'_hall',$int_pollrights,$int_pollrights)) {
				output('`n`^~~~~~~~~`0`n`n',true);
			}
			output('`c');

			if($guild['vote']) {output('Ihre '.$guild['regalia'].' Stimmen gibt die Gilde für einen Steuersatz von '.$guild['vote'].' %.`n`n');}

			viewcommentary('guild-'.$gid,'Mit den anderen Gildenmitgliedern sprechen: ',25,'spricht',false,true,false,getsetting('chat_post_len_long',1500),true,true,2);

			// Möbel
			dg_show_furniture('hall');
			// END Möbel

			addnav('Zurück');
			addnav('Zum Gildenviertel','dg_main.php');
			unset($accesskeys['d']); unset($accesskeys['m']);
			addnav('d?Zum Stadtzentrum','village.php');
			addnav('M?Zum Marktplatz','market.php');

			// feststellen, welche Ausbauten verfügbar sind
			$without_op = false;
			if($best_building > 0 && $guild['state'] != DG_STATE_INACTIVE) {
				addnav('Ausbauten');

				foreach($dg_builds as $id => $b) {
					if($guild['build_list'][$id]) {
						if($b['op'] != '') {addnav($b['name'],'dg_main.php?op=in&subop=buildings&building_op='.$b['op']);}
						else {$without_op = true;}
					}

				}
				if($without_op) {addnav('Sonstige','dg_main.php?op=in&subop=buildings&building_op=na');}

			}

			if(is_array($guild['building_vars']['party']) && $guild['building_vars']['party']['gang']) {
				addnav('Besonderes');
				addnav('Zum Gildenfest!','dg_main.php?op=in&subop=party');
			}

			break;

		case 'party':

			$speisen = array(1=>'Pilzsuppe',2=>'Schweinebraten',3=>'Weintrauben');
			$getraenke = array(1=>'Cedriks\' Ale',2=>'LOKIs Zwergenmet',3=>'Drachenschnaps');

			dg_show_header('Gildenfest');

			if(addcommentary()) {    // Zufallskommentare
				switch(e_rand(1,20)) {
					case 1:
						if($guild['building_vars']['party']['musik'] == 1) {
							dg_commentary($gid,'/msg `^Es ertönt eine malerische Harfenmelodie.','party',1);
						}
						break;

					case 2:
						if($guild['building_vars']['party']['tanz'] == 1) {
							dg_commentary($gid,'/msg `^Grazil bewegen sich die Tänzerinnen zur Musik.','party',1);
						}
						break;

					case 3:
						if($guild['building_vars']['party']['gaukler'] == 1) {
							dg_commentary($gid,'/msg `^Einer der Gaukler springt von Tisch zu Tisch und jongliert mit einigen Bällen.','party',1);
						}
						break;

				}
			}

			if($_GET['act'] == 'start') {

				$link = 'dg_main.php?op=in&subop=party&act=start';

				if($guild['points'] < 3) {
					output('Das kann sich die Gilde nicht leisten!');
					addnav('Zurück','dg_main.php?op=in');
				}
				else {
					$last = $guild['building_vars']['party']['last'];
					$diff = gamedate_diff(get_raw_gamedate(),$last);

					//irgendwas haut hier nicht hin wenn noch nie ein Gildenfest war. Abfrage totgelegt by Salator
					//Die letzte Feier deiner Gilde war am 14. Februar 49, heute ist der 14. Februar 49.
					if($diff < 30 && 1==0) {
						output('`8Die letzte Feier deiner Gilde war am '.getgamedate($last).', heute ist der '.getgamedate(getsetting('gamedate','')).'. Noch nicht mal ein Monat und du willst schon wieder feiern?! Arbeite erst mal!');
						addnav('Zurück','dg_main.php?op=in&subop=gpshop');
					}
					else {
						output('`8Auf deinen Befehl hin werden Stühle und Tische herangeschafft, selbige mit Unmengen an leckeren Speisen beladen. Weinfässer rollt man herbei, auf dass es der Gesellschaft nicht an trinkfesten Genüssen mangele. Und zu guter Letzt halten sich auch noch einige Gaukler und ähnliches lustiges Gesinde im Hintergrund: Das Fest kann beginnen!');
						$guild['points'] -= 3;
						$guild['points_spent'] += 3;
						$guild['building_vars']['party']['gang'] = 1;
						$guild['building_vars']['party']['last'] = getsetting('gamedate','');
						$guild['building_vars']['party']['musik'] = 0;
						$guild['building_vars']['party']['tanz'] = 0;
						$guild['building_vars']['party']['gaukler'] = 0;

						// Mitglieder befreundeter Gilden sowieso
						$not = '';
						$yeah = '';
						if(is_array($guild['treaties'])) {
							foreach($guild['treaties'] as $id=>$t) {
								if(dg_get_treaty($t) == -1) {
									$not .= ','.$id;
								}
								elseif(dg_get_treaty($t) == 1) {
									$yeah .= ','.$id;
								}
							}
						}

						$sql = 'SELECT a.acctid FROM accounts a WHERE guildid IN (-1'.$yeah.')';

						$res = db_query($sql);
						while($a = db_fetch_assoc($res)) {
							$guild['building_vars']['party']['eaten'][$a['acctid']] = 0;
						}

						addnav('Los!','dg_main.php?op=in&subop=party');
						dg_addnews('`8Die Gilde '.$guild['name'].'`8 feiert heute ein großes Fest!');
					}
				}

			}    // END if act

			elseif($_GET['act'] == 'stop') {

				$guild['building_vars']['party']['gang'] = 0;
				$guild['building_vars']['party']['musik'] = 0;
				$guild['building_vars']['party']['tanz'] = 0;
				$guild['building_vars']['party']['gaukler'] = 0;
				unset($guild['building_vars']['party']['eaten']);
				addnav('Zurück','dg_main.php?op=in');

			}

			elseif($_GET['act'] == 'eat') {

				$speise = $speisen[$guild['building_vars']['party']['gang']];

				if($guild['building_vars']['party']['eaten'][$session['user']['acctid']] >= 10) {
					output('`8Du gibst alles, bringst aber beim besten Willen keinen Bissen von '.$speise.' mehr rein!`n`n');
					addnav('Weiterfeiern','dg_main.php?op=in&subop=party');
				}
				else {
					$guild['building_vars']['party']['eaten'][$session['user']['acctid']] += $guild['building_vars']['party']['gang'];
					$session['user']['hitpoints'] *= 1.05;
					dg_commentary($gid,': bedient sich an '.$speise.'!','party');
					dg_save_guild();
					redirect('dg_main.php?op=in&subop=party');
				}

			}

			/*elseif($_GET['act'] == 'invite') {
			$not = '';
			if(is_array($guild['treaties'])) {
			foreach($guild['treaties'] as $id=>$t) {
			if(dg_get_treaty($t) == -1) {
			$not .= ','.$id;
			}
			}
			}

			$sql = 'SELECT name FROM accounts WHERE loggedin=1 AND guildid NOT IN (-1'.$not.') ORDER BY name ASC';

			}*/

			elseif($_GET['act'] == 'drink') {

				$prison = false;

				if($session['user']['drunkenness'] < 99) {
					output('`8Du nimmst einen kräftigen Schluck von '.$getraenke[$_GET['what']].'. Kurze Zeit später bemerkst du die berauschende Wirkung!');
					$session['user']['drunkenness']+=5;

					if($session['user']['drunkenness'] >= 99) {
						if($session['user']['race'] == 'zwg') {
							if(e_rand(1,4) == 1) {
								$prison = true;
							}
						}
						else {
							if(e_rand(1,2) == 1) {
								$prison = true;
							}
						}
					}

				}
				else {
					output('`8Du solltest besser nichts mehr trinken. Jeder weitere Schluck würde dich unweigerlich dem Tode nahebringen!');
				}

				if($prison == true) {
					output("`8Du hast zwar zuviel gesoffen, es aber gerade noch überlebt. Du erwachst in der Ausnüchterungszelle.`n");
					output("Du verlierst den Großteil Deiner Lebenspunkte!");
					$session['user']['hitpoints']=1;
					$session['user']['imprisoned']=1;
					addnews($session['user']['name']." entging nur knapp den Folgen einer Alkoholvergiftung und verbringt die Nacht in der Ausnüchterungszelle.");
					addnav("Weiter","prison.php");
				}
				else {
					addnav('Weiterfeiern','dg_main.php?op=in&subop=party');
				}

			}

			elseif($_GET['act'] == 'serve') {

				if(is_numeric($_GET['what'])) {
					$speise = $speisen[$_GET['what']];
					output('Du servierst '.$speise.'!');
					$guild['building_vars']['party']['gang'] = $_GET['what'];
					dg_commentary($gid,'/msg '.$speise.' wird aufgetragen!','party',1);
				}
				else {
					if($_GET['what'] == 'musik') {
						if($guild['building_vars']['party']['musik'] == 1) {
							dg_commentary($gid,'/msg Die Musiker packen ihre Instrumente und verschwinden.','party',1);
							$guild['building_vars']['party']['musik'] = 0;
						}
						else {
							$guild['points']--;
							$guild['points_spent']++;
							dg_commentary($gid,'/msg `^Die Musiker kommen mit Harfen hereinstolziert.','party',1);
							$guild['building_vars']['party']['musik'] = 1;
						}

					}
					elseif($_GET['what'] == 'tanz') {
						if($guild['building_vars']['party']['tanz'] == 1) {
							dg_commentary($gid,'/msg Die Tänzerinnen verschwinden.','party',1);
							$guild['building_vars']['party']['tanz'] = 0;
						}
						else {
							$guild['points']--;
							$guild['points_spent']++;
							dg_commentary($gid,'/msg `^Die Tänzerinnen erscheinen mit sanften Schritten.','party',1);
							$guild['building_vars']['party']['tanz'] = 1;
						}
					}
					elseif($_GET['what'] == 'gaukler') {
						if($guild['building_vars']['party']['gaukler'] == 1) {
							dg_commentary($gid,'/msg Die Gaukler springen hinaus.','party',1);
							$guild['building_vars']['party']['gaukler'] = 0;
						}
						else {
							$guild['points']--;
							$guild['points_spent']++;
							dg_commentary($gid,'/msg `^Die Gaukler kugeln herein.','party',1);
							$guild['building_vars']['party']['gaukler'] = 1;
						}
					}

				}    // END special

				dg_save_guild();
				redirect('dg_main.php?op=in&subop=party');
			}

			else {
				$link = 'dg_main.php?op=in&subop=party&act=';

				output('`8Schon immer war deine Gilde berüchtigt für ihre großen, prunkvollen und vor allem lustigen Feste und wieder musst du feststellen, dass sie sich noch immer blendend auf dieses Handwerk versteht.
				Der ganze Raum ist gefüllt mit allen Gildenmitgliedern und du sitzt mitten an einer riesigen Tafel, die gedeckt ist mit dem feinsten Porzellan und den wertvollsten Kelchen, aus denen dir der Duft eines herrlichen Weins in die Nase steigt. Doch am meisten lassen dir die köstlich aussehenden Speisen den Mund im Wasser zusammenlaufen.
				Während du dir reichlich auf den Teller packst und hastig beginnst, alles hinunter zu schlingen und gelegentlich ein wenig Wein zu trinken, tauchen plötzlich hinter unzähligen Vorhängen luftig gekleidete Tänzer und Tänzerinnen auf, die anfangen elegant zu der fröhlichen Musik des Volkes zu tanzen, die mehrere Musikanten angestimmt haben.
				Völlig ergriffen von dem schönen Tanz und natürlich den hübschen Tänzern, aber besonders den Tänzerinnen, hättest du beinahe zwei Gaukler übersehen, die etwas abseits der Tafel beginnen, Feuer zu speien und mit Keulen und Bällen zu jonglieren. Nur mit halbem Ohr verfolgst du nebenbei noch die vielen Gespräche am Tisch, die Witze, die hier und da schallendes Gelächter hervorrufen und natürlich die Erzählungen von ruhmreichen Taten.`n`n');

				$last = $guild['building_vars']['party']['last'];
				$diff = gamedate_diff(get_raw_gamedate(),$last);

				if($diff >= 2) {
					output('`8Diese Feier dauert bereits zu lang. Ihr solltet euch mal wieder auf den Ernst des Lebens konzentrieren!`n`n');
				}
				else {
					addnav('Getränke');

					foreach($getraenke as $nr=>$g) {
						addnav($g,$link.'drink&what='.$nr);
					}

					addnav('Speisen');

					if($guild['building_vars']['party']['gang']) {
						addnav($speisen[ $guild['building_vars']['party']['gang'] ].' nehmen',$link.'eat&what='.$guild['building_vars']['party']['gang']);
					}
				}
				viewcommentary('guild-'.$gid.'_party',"Fröhlich feiern",30,"sagt",false,true,false,getsetting('chat_post_len_long',1500),true,true,2);

				if($team) {

					addnav('Ablauf');

					if($diff >= 2) {
						addnav('Feier beenden!',$link.'stop');
					}
					else {

						if($guild['building_vars']['party']['gang'] == 1) {
							addnav($speisen[2].' hereinbringen!',$link.'serve&what=2');
						}
						elseif($guild['building_vars']['party']['gang'] == 2) {
							addnav($speisen[3].' hereinbringen!',$link.'serve&what=3');
						}
						else {
							addnav('Feier beenden!',$link.'stop');
						}

						if($guild['points'] > 0) {
							addnav('Aktionen (1 Gildenpunkt)');
							if($guild['building_vars']['party']['musik'] == 0) {addnav('Musiker aufspielen lassen!',$link.'serve&what=musik');}
							else {addnav('Musiker wegschicken!',$link.'serve&what=musik');}

							if($guild['building_vars']['party']['tanz'] == 0) {addnav('Tänzer aufspielen lassen!',$link.'serve&what=tanz');}
							else {addnav('Tänzerinnen wegschicken!',$link.'serve&what=tanz');}

							if($guild['building_vars']['party']['gaukler'] == 0) {addnav('Gaukler aufspielen lassen!',$link.'serve&what=gaukler');}
							else {addnav('Gaukler wegschicken!',$link.'serve&what=gaukler');}
						}
					}

				}

				addnav('Wege');
				if($session['user']['guildid'] == $gid) {
					addnav('Zur Halle','dg_main.php?op=in');
				}
				else {
					addnav('Zum Gildenviertel','dg_main.php');
				}
			}

			break;

		case 'leave':

			if($_GET['act'] == 'ok') {

				dg_remove_member($gid,$session['user']['acctid']);
				dg_commentary($gid,': ist aus der Gilde ausgetreten.','',$session['user']['acctid']);
				dg_addnews($session['user']['name'].'`8 hat die Gilde '.$guild['name'].'`8 verlassen.',$session['user']['acctid']);

				addhistory('`2Austritt aus Gilde '.$guild['name']);

				output('`8Mit einem leicht flauen Gefühl im Bauch überreichst du dein Kündigungsschreiben. Seufzend denkst du an all die schönen Stunden zurück, die du hier verbracht hast, lässt deinen Blick zum letzten Mal durch die Halle schweifen - und machst dich dann auf den Weg zurück in die Stadt.');

				addnav('Zur Stadt','village.php');

			}
			else {

				// Überprüfen, ob es noch andere Führungsmitglieder gibt
				$ok = false;
				if($leader) {
					$sql = 'SELECT acctid FROM accounts WHERE guildid='.$gid.' AND guildfunc='.DG_FUNC_LEADER;
					$res = db_query($sql);
					if(db_num_rows($res) <= 1) {
						output('`8Du kannst diese Gilde nicht verlassen, da sie sonst ohne Führung wäre! Sorge erst für einen Nachfolger auf deinem Posten.');
					}
					else {
						$ok = true;
					}
				}
				else {$ok=true;}

				// Gründer darf nicht austreten
				if($session['user']['acctid'] == $guild['founder']) {
					output('`8Du hast diese Gilde gegründet und nun willst du sie im Stich lassen? Das kannst du nicht tun!');
					$ok = false;
				}

				if($ok) {

					output('`4Willst du deine Mitgliedschaft in der Gilde wirklich kündigen?');

					addnav('Nein, zurück','dg_main.php?op=in');
					addnav('Ja!','dg_main.php?op=in&subop=leave&act=ok');
				}
				else {
					addnav('Zurück','dg_main.php?op=in');
				}

			}

			break;

		case 'retire':

			$str_what = $dg_funcs[$session['user']['guildfunc']][$session['user']['sex']];

			if($_GET['act'] == 'ok') {

				user_update(array (    'guildfunc'=>DG_FUNC_MEMBER),$session['user']['acctid']);

				dg_commentary($gid,': ist als '.$str_what.' zurückgetreten.','',$session['user']['acctid']);
				dg_addnews($session['user']['name'].'`8 hat das Amt als '.$str_what.' in der Gilde '.$guild['name'].'`8 niedergelegt.',$session['user']['acctid']);

				addhistory('`2Rücktritt vom Amt als '.$str_what.' in Gilde '.$guild['name']);

				output('`8Mit einem leicht flauen Gefühl im Bauch überreichst du dein Kündigungsschreiben an die Gildenführung.`n
				Nun bist du wieder ein normales Mitglied der Gilde, ohne besondere Rechte, aber auch ohne besondere Pflichten.');

				addnav('Zum Hauptraum','dg_main.php?op=in');

			}
			else {

				// Überprüfen, ob es noch andere Führungsmitglieder gibt
				$ok = false;
				if($leader) {
					$sql = 'SELECT acctid FROM accounts WHERE guildid='.$gid.' AND guildfunc='.DG_FUNC_LEADER;
					$res = db_query($sql);
					if(db_num_rows($res) <= 1) {
						output('`8Du kannst von deinem Amt nicht zurücktreten, da die Gilde sonst ohne Führung wäre! Sorge erst für einen Nachfolger auf deinem Posten.');
					}
					else {
						$ok = true;
					}
				}
				else {$ok=true;}

				if($ok) {

					output('`4Willst du dein Amt als '.$str_what.' in der Gilde wirklich niederlegen?');

					addnav('Nein, zurück','dg_main.php?op=in');
					addnav('Ja!','dg_main.php?op=in&subop=retire&act=ok');
				}
				else {
					addnav('Zurück','dg_main.php?op=in');
				}

			}

			break;

			// Spieler einladen, der Gilde beizutreten
		case 'invite':

			$min_dks = getsetting('dgmindkapply',3);

			if(mb_strlen($_POST['search']) > 0) {

				$search = str_create_search_string($_POST['search']);

				$sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$search.'"
				AND acctid!='.$session['user']['acctid'].' AND guildid=0 AND guildfunc=0 AND dragonkills >= '.$min_dks;
				$res = db_query($sql);

				if(db_num_rows($res) == 0) {
					output('`8Kein mögliches Ziel mit diesem Namen gefunden!');
				}
				else {
					output('`8Diese Bürger von '.getsetting('townname','Atrahor').'`8 treffen auf deine Suche zu und können Mitglied in deiner Gilde werden:`n`n');

					$link = 'dg_main.php?op=in&subop=invite';

					output('<form action="'.$link.'" method="POST">',true);

					output(' <select name="ziel">',true);

					while ( $p = db_fetch_assoc($res) ) {

						output('<option value="'.$p['acctid'].'">'.strip_appoencode($p['name'],3).'</option>',true);

					}

					output('</select>`n`n',true);

					output('<input type="submit" class="button" value="Einladen!"></form>',true);
					addnav('',$link);
				}
				addnav('Neue Suche','dg_main.php?op=in&subop=invite');
			}    // END if search

			elseif($_POST['ziel']) {

				$ziel = (int)$_REQUEST['ziel'];

				$rec = db_fetch_assoc(db_query('SELECT name,acctid FROM accounts WHERE acctid='.$ziel));

				if(!empty($_POST['msg'])) {

					$str_msg = '`8Die Botschaft scheint dir die Gilde '.$guild['name'].'`8 zu senden.. es handelt sich dabei wohl
					um ein Einladungsschreiben!`n
					Als du das Siegel aufbrichst, kannst du lesen:`n`n
					'.$_POST['msg'].'`n`n
					`@Um diese Einladung anzunehmen, begib dich ins Gildenviertel.';

					systemmail($ziel,'`8Eine Botschaft!',$str_msg);


					user_update(
					array
					(
					'guildfunc'=>DG_FUNC_INVITED,
					'guildrank'=>$rank,
					//Das ist doch eigentlich unnötig
					//'where'=>'acctid='.$ziel.' AND guildid=0 AND guildfunc=0 AND dragonkills >= '.$min_dks
					),
					$ziel
					);

					output('`8Der Bote macht sich eilends auf den Weg. Bald schon wird die Botschaft ihren Empfänger erreichen!');
				}
				else {

					$arr_form = array(
					'name'=>'An:,viewonly',
					'msg'=>'Nachricht:'
					);

					$link = 'dg_main.php?op=in&subop=invite&ziel='.$ziel;
					addnav('',$link);
					output('<form action="'.$link.'" method="POST">',true);
					showform($arr_form,$rec,false,'Botschaft absenden!');
					output('</form>',true);

				}


			}    // END if ziel
			else {
				$link = 'dg_main.php?op=in&subop=hitlist&act=add';
				output('`8Du kannst nur diejenigen Bürger einladen, die bisher gildenlos sind,
				weder kürzlich aus einer Gilde ausgetreten, noch von einer anderen eingeladen sind.
				Weiterhin müssen sie mindestens '.$min_dks.' Heldentat' . ($mindks>1?'en':'').' begangen haben!`n');

				output('<form action="'.$link.'" method="POST">',true);

				output('Name: <input type="input" name="search">',true);

				output('`n`n',true);

				output('<input type="submit" class="button" value="Suchen"></form>',true);
				addnav('',$link);
			}
			addnav('Zurück','dg_main.php?op=in&subop=hitlist');

			break;

			// Umfragen starten
		case 'polls':

			dg_show_header('Gildeninterne Umfragen');

			require_once(LIB_PATH.'board.lib.php');

			poll_add('guild'.$gid.'_'.$_GET['pollsection'],100,1);

			if(!empty($session['polladderror'])) {
				if($session['polladderror'] == 'maxpolls') {
					output('`$An dieser Stelle findet bereits eine Umfrage statt! Entferne bitte zunächst diese, ehe du eine neue eröffnest.`n`n');
				}
			}
			else {
				redirect('dg_main.php?op=in&subop=private');
			}

			if($_GET['pollsection'] == 'private') {

				output('`8Du möchtest also im Hinterzimmer deiner Gilde eine Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur
				darauf wartet, von dir beschriftet und an einer prominenten Stelle aufgehängt zu werden:`n`n');

			}
			else {

				output('`8Du möchtest also in deiner Gilde eine öffentliche Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur
				darauf wartet, von dir beschriftet und in der Gildenhalle platziert zu werden:`n`n');

			}

			addnav('Zurück zum Hinterzimmer','dg_main.php?op=in&subop=private');

			poll_show_addform();


			break;

		case 'treasure':

			if($treasure || $leader) {addcommentary();}

			$taxdays = (int)getsetting('dgtaxdays',12);
			$max_tax_fails = (int)getsetting('dgmaxtaxfails',12);

			$taxdays_left = $taxdays - ($guild['taxdays'] % $taxdays);
			$tax_fails = floor( $guild['taxdays'] / $taxdays );

			if($_GET['act'] == 'lock') {
				$guild['treasure_locked'] = 1;
				dg_commentary($gid,': `4sperrt die Schatzkammer für alle Auszahlungen!','treasure');
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treasure');
			}

			elseif($_GET['act'] == 'unlock') {
				$guild['treasure_locked'] = 0;
				dg_commentary($gid,': `@gibt die Schatzkammer wieder frei.','treasure');
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treasure');
			}
			elseif($_GET['act'] == 'reset_transfer') {
				$guild['transfers'] = array();
				dg_commentary($gid,': `$entfernt sämtliche Aufzeichnungen über Transfer der Mitglieder.','treasure');
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treasure');
			}

			dg_show_header('Schatzkammer');

			output('`8Du musst nur zielstrebig dem Funkeln folgen, das die Schatzkammer in den Gang aussendet, schon findest du jenen Raum, der von großer Bedeutung für die Gilde ist. Hier lagern all die Schätze, hier werden die wichtigsten Transaktionen getroffen. Sieh dich ruhig um, aber sei maßvoll bei dem was du mit dir nimmst:`n`n');

			output('`c`8<table bgcolor="#999999" border="0" cellpadding="3" cellspacing="1"><tr class="trhead"><td>Gold</td><td>Edelsteine</td><td>Gildenpunkte (ausgegeben)</td><td>Insignien</td></tr>',true);

			output('<tr class="trlight"><td>'.$guild['gold'].'</td><td>'.$guild['gems'].'</td><td>'.$guild['points'].' ('.$guild['points_spent'].')</td><td>'.$guild['regalia'].'</td></tr></table>`n`n',true);

			$tax = &dg_calc_tax($gid);

			if(!$guild['taxfree_allowed']) {
				output('`8Steuern (`^'.$tax['gold'].'`8 Gold, `^'.$tax['gems'].'`8 Edelsteine) sind fällig in `4'.$taxdays_left.'`8 '.(getsetting('dayparts','1') > 1?'Tagesabschnitten':'Tagen').'!`n');
				if($tax_fails == 1) {
					output('`4`bDie Gilde hat bereits einmal versäumt, ihre Steuern zu entrichten. Falls dies noch einmal geschieht, wird ein Ausbau gepfändet!`b`8`n`n');
				}
				elseif($tax_fails == 2) {
					output('`4`bDie Gilde hat bereits zweimal versäumt, ihre Steuern zu entrichten. Falls dies noch einmal geschieht, wird `idie Gilde aufgelöst`i!`8`b`n`n');
				}
			}
			else {
				output('Die Gilde ist von sämtlichen Steuerzahlungen befreit!`n`n');
			}

			$tribute = dg_member_tribute($gid,0,0,false);
			output('`8Zur Zeit müssen die Mitglieder `^'.$tribute.'`8 % ihrer Wald- und Schlosserträge als Tribut an die Gilde abtreten!`n');

			$maxgold = dg_calc_boni($gid,'treasure_maxgold',getsetting('dgtrsmaxgold',100000));
			$maxgems = dg_calc_boni($gid,'treasure_maxgems',getsetting('dgtrsmaxgems',1000));

			$maxgold_left = dg_calc_max_transfer_in($gid,'gold');
			$maxgems_left = dg_calc_max_transfer_in($gid,'gems');

			output('`8In den Truhen ist noch Platz für `^'.max($maxgold - $guild['gold'],0).'`8 Gold und `^'.max($maxgems - $guild['gems'],0).'`8 Edelsteine.`n
			Heute können noch `^'.$maxgold_left.'`8 Gold und `^'.$maxgems_left.'`8 Edelsteine eingezahlt werden!`n`n`c');

			viewcommentary('guild-'.$gid.'_treasure',($team ? 'Etwas verkünden:':'Du solltest hier besser schweigen!'),25,'verkündet',false,($team?true:false),false,false,true,true,2);

			$link = 'dg_main.php?op=in&subop=treasure&transferlist_old=';

			output('`n`n');

			if($_GET['transferlist_old']) {
				$link .= '0';
				output('[ <a href="'.$link.'">Nicht-Mitglieder ausblenden</a> ]',true);
			}
			else {
				$link .= '1';
				output('[ <a href="'.$link.'">Nicht-Mitglieder anzeigen</a> ]',true);
			}

			if($leader || $treasure) {
				output(' [ '.create_lnk('`$Liste leeren`8','dg_main.php?op=in&subop=treasure&act=reset_transfer',true,false,'Wollt Ihr wirklich die gesamten Transferaufzeichnungen verwerfen?').' ] ',true);
			}

			addnav('',$link);

			// Transferliste
			dg_show_transfer_list($gid,0,$_GET['transferlist_old']);

			addnav('Gold');
			addnav('Einzahlen','dg_main.php?op=in&subop=transfer&act=gold&in=1');
			if($leader || $treasure) {
				addnav('Auszahlung','dg_main.php?op=in&subop=donate&what=Gold');
			}

			addnav('Edelsteine');
			addnav('Einzahlen','dg_main.php?op=in&subop=transfer&act=gems&in=1');
			if($leader || $treasure) {
				addnav('Auszahlung','dg_main.php?op=in&subop=donate&what=Edelsteine');
			}

			addnav('Gildenpunkte');
			addnav('G?Zum Gildenpunkthändler','dg_main.php?op=in&subop=gpshop');

			addnav('Ausbau');
			addnav('Ausbauten','dg_main.php?op=in&subop=builds');

			addnav('Verschiedenes');
			addnav('Zur Halle','dg_main.php?op=in');

			break;

		case 'gpshop':

			$gp_price_buy = getsetting('dggpgoldcost',30000) * 5;
			$gp_price_gems = 50;
			$gp_price_regalia = dg_calc_boni($gid,'regalia_buy', (getsetting('dgregaliagpcost',100) * ($guild['regalia']+1)) );
			$gp_price_guardhp = dg_calc_boni($gid,'guardhp_buy',0.25);
			$gp_price_aufzeichnung = 10;
			//$gp_price_guardhp += floor($guild['guard_hp'] / 50) * 2;
			//$gp_price_guardhp += 5 - ceil($guild['reputation'] / 10);

			$int_maxguards = dg_calc_boni($gid,'maxguards',0);

			$gp_price_buy_room = 50;

			if($_GET['act'] == 'gp_buy') {

				$guild['points'] += 5;
				$guild['gold'] -= $gp_price_buy;
				dg_commentary($gid,': `4erwirbt `^5`4 Gildenpunkte für `^'.$gp_price_buy.'`4 Gold','treasure');
				dg_log('5 GP für '.$gp_price_buy.' Gold');
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treasure');

			}
			
			elseif($_GET['act'] == 'gp_gems') {

				$guild['points'] += 5;
				$guild['gems'] -= $gp_price_gems;
				dg_commentary($gid,': `4erwirbt `^5`4 Gildenpunkte für `^'.$gp_price_gems.'`4 Edelsteine','treasure');
				dg_log('5 GP für '.$gp_price_gems.' Edelsteine');
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treasure');

			}
			
			elseif($_GET['act'] == 'gp_buy_guardhp') {
				$int_count = (int)$_POST['count'];

				if($int_maxguards < $guild['guard_hp'] + $int_count) {
					output('`$Eine derartige Anzahl von Söldnern fände in den Kasernen deiner Gilde keinen Platz!');
				}
				else {

					$int_price = ceil($gp_price_guardhp * $int_count);
					if($int_price >= $guild['points']) {
						output('`$Eine derartige Anzahl von Söldnern kann sich deine Gilde leider einfach nicht leisten!');
					}
					else {

						$guild['guard_hp'] += $int_count;
						$guild['points'] -= $int_price;
						$guild['points_spent'] += $int_price;
						dg_commentary($gid,': `4wirbt `^'.$int_count.'`4 Gildenwachen für `^'.$int_price.'`4 Gildenpunkte an, die ab sofort der Gilde zur Seite stehen.','treasure');
						dg_save_guild();
						redirect('dg_main.php?op=in&subop=treasure');
					}
				}
			}
			elseif($_GET['act'] == 'gp_buy_room') {
				$guild['points'] -= $gp_price_buy_room;
				$guild['points_spent'] += $gp_price_buy_room;
				dg_commentary($gid,': `4erwirbt `^ein zusätzliches Gemach`4 für `^'.$gp_price_buy_room.'`4 Gildenpunkte','treasure');

				$guild['ext_room_name'] = 'Salon';
				$guild['ext_room_desc'] = 'Ein zusätzliches, vornehmes Gemach.';

				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treasure');
			}
			
			elseif($_GET['act'] == 'gp_buy_room2') {
				$guild['points'] -= $gp_price_buy_room;
				$guild['points_spent'] += $gp_price_buy_room;
				dg_commentary($gid,': `4erwirbt `^ein zusätzliches Gemach`4 für `^'.$gp_price_buy_room.'`4 Gildenpunkte','treasure');

				$guild['ext_room_name2'] = 'Salon';
				$guild['ext_room_desc2'] = 'Ein zusätzliches, vornehmes Gemach.';

				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treasure');
			}
			elseif($_GET['act'] == 'gp_buy_aufzeichnung') {
				$gid = $Char->guildid;
				$int_max_length = 200;

				// Aktuelles Spieldatum
				$str_current_date = getsetting('gamedate','0000-00-00');
				// .. als Array
				$arr_current_date = explode('-',$str_current_date);
				// Max. anwählbares Jahr
				$int_max_year = (int)$arr_current_date[0];
				// Max. anwählbarer Monat
				$int_max_month = (int)$arr_current_date[1];
				// Max. anwählbarer Tag
				$int_max_day = (int)$arr_current_date[2];

				if($_GET['do'] == 'save') {

					// Invalide Spieldaten verhindern
					$int_year = min((int)$_REQUEST['year'],$int_max_year);
					$int_month = (int)$_REQUEST['month'];
					$int_day = (int)$_REQUEST['day'];
					if($int_year == $int_max_year)
					{
						$int_month = min($int_month,$int_max_month);
						if($int_month == $int_max_month)
						{
							$int_day = min($int_day,$int_max_day);
						}
					}

					// this piece of code was taken from chaosmakers gamedate-mod
					$str_gamedate = sprintf('%04d-%02d-%02d',$int_year,$int_month,$int_day);

					$str_msg = stripslashes(urldecode($_REQUEST['msg']));
					$str_msg = mb_substr($str_msg,0,$int_max_length);

					$str_msg_save = '`7~`0 '.$str_msg;

					if($_GET['ok'])
					{
						$guild['points'] -= $gp_price_aufzeichnung;
						$guild['points_spent'] += $gp_price_aufzeichnung;
						dg_commentary($gid,': `4erwirbt `^eine Aufzeichnung`4 für `^'.$gp_price_aufzeichnung.'`4 Gildenpunkte','treasure');
						addhistory($str_msg_save,2,$gid,$str_gamedate);
						output('Alles klar, dein Eintrag wurde gespeichert und in die Annalen deiner Gilde aufgenommen.`n`n');

					}
					else
					{
						output('Deine spezielle Aufzeichnung würde folgendermaßen aussehen:`n`n
						`@'.getgamedate($str_gamedate).' : `2'.$str_msg_save.'`n`n`0
						Entspricht dies deinen Wünschen?`n`n');

						$str_lnk = 'dg_main.php?op=in&subop=gpshop&act=gp_buy_aufzeichnung&do=save&ok=1&day='.$int_day.'&month='.$int_month.'&year='.$int_year.'&msg='.urlencode($str_msg);
						output(create_lnk('Ja, für '.$gp_price_aufzeichnung.' Gildenpunkte eintragen!',$str_lnk));

					}
				}

				elseif ($_GET['do'] == 'start')
				{
					$str_lnk = 'dg_main.php?op=in&subop=gpshop&act=gp_buy_aufzeichnung&do=save';
					$arr_data=array('msg'=>$str_msg,'year'=>$int_year,'month'=>$int_month,'day'=>$int_day);

					allownav($str_lnk);
					output('<form method="POST" action="'.$str_lnk.'">');
					$arr_form = array    (
					'msg'=>'Nachricht:,textarea,70,2,'.$int_max_length,
					'msg_pr'=>'Vorschau:,preview,msg',
					'day'=>'Tag,enum_order,1,31',
					'month'=>'Monat,enum_order,1,12',
					'year'=>'Jahr,enum_order,1,'.$int_max_year

					);
					showform($arr_form,$arr_data,false,'Vorschau!');
					output('</form>');
				}

				dg_save_guild();

			}

			dg_show_header('Gildenpunkte: An- und Verkauf');

			output('`c`8<table bgcolor="#999999" border="0" cellpadding="3" cellspacing="1"><tr class="trhead"><td>Gold</td><td>Edelsteine</td><td>Gildenpunkte (ausgegegeben)</td><td>Insignien</td></tr>',true);
			output('<tr class="trlight"><td>'.$guild['gold'].'</td><td>'.$guild['gems'].'</td><td>'.$guild['points'].' ('.$guild['points_spent'].')</td><td>'.$guild['regalia'].'</td></tr></table>`n`n',true);

			output('`8Einige Tische der Schatzkammer wurden von Gold und Edelsteinen befreit, um Platz für den Gildenpunkthändler zu schaffen. Er ist die wichtigste Anlaufsstelle, wenn es um Angelegenheiten der offiziellen Gildenwährung '.getsetting('townname','Atrahor').'s geht. Hier siehst du seine Angebote:`n`n');

			$link = 'dg_main.php?op=in&subop=gpshop&act=gp_buy';
			output('`T`^Fünf`T Gildenpunkte für `^'.$gp_price_buy.'`T Gold: "`tDie beste Wahl, falls deiner Gilde die Schatzkammern überquellen, du Gold wirklich nicht mehr sehen kannst und / oder ihr dringend Punkte benötigt!"`n');
			if($guild['gold'] >= $gp_price_buy) {
				if($leader || $treasure) {
					output('<a href="'.$link.'">Kaufen!</a>',true);
					addnav('',$link);
				}
			}
			else {output('Die Goldvorräte reichen leider nicht aus!');}
			
			$link = 'dg_main.php?op=in&subop=gpshop&act=gp_gems';
			output('`n`n`T`^Fünf`T Gildenpunkte für `^'.$gp_price_gems.'`T Edelsteine.`n');
			if($guild['gems'] >= $gp_price_gems) {
				if($leader || $treasure) {
					output('<a href="'.$link.'">Kaufen!</a>',true);
					addnav('',$link);
				}
			}
			else {output('Die Edelsteinvorräte reichen leider nicht aus!');}
			

			$link = 'dg_main.php?op=in&subop=gpshop&act=gp_buy_guardhp';

			output('`n`n`TMaximal `^'.($int_maxguards - $guild['guard_hp']).'`T Söldner für `^'.$gp_price_guardhp.'`T Gildenpunkte pro Einheit anwerben (Mindestkosten 1 Punkt): "`tSo eine Gildenwache kann bekanntlich nie stark genug sein. Also habt ihr hier die Möglichkeit, auch künftig ungebetene Besucher aus der Schatzkammer draußen zu halten!"`n');
			if($leader || $treasure || $war) {
				output('<form method="POST" action="'.$link.'">
				Wie viele Söldner willst du in den Dienst deiner Gilde stellen?`n <input type="text" size="3" maxlength="3" name="count" value="0">`n`n
				<input type="submit" value="Anheuern!">
				</form>',true);
				addnav('',$link);

			}

			$link = 'dg_main.php?op=in&subop=party&act=start';
			output('`n`n`T Gildenfest für `^3`T Gildenpunkte: "`tWas gibt es schöneres, als zünftiges Zusammensein bei einem gemütlichen Fest?"`n');
			if($guild['points'] >= 3) {
				if($leader || $treasure) {
					output('<a href="'.$link.'">Beginnen!</a>',true);
					addnav('',$link);
				}
			}
			else {output('Die Anzahl der Gildenpunkte reicht leider nicht aus!');}

			if($guild['ext_room_name'] == '') {
				$link = 'dg_main.php?op=in&subop=gpshop&act=gp_buy_room';
				output('`n`n`T Zusätzliches Gemach für `^'.$gp_price_buy_room.'`T Gildenpunkte: "`tEure Baumeister gestalten ein völlig neues Gemach in der Gildenhalle nach euren Wünschen!"`n');
				if($guild['points'] >= $gp_price_buy_room) {
					if($leader || $treasure) {
						output('<a href="'.$link.'">Bauen!</a>',true);
						addnav('',$link);
					}
				}
				else {output('Die Anzahl der Gildenpunkte reicht leider nicht aus!');}
			}
			
			if($guild['ext_room_name2'] == '') {
				$link = 'dg_main.php?op=in&subop=gpshop&act=gp_buy_room2';
				output('`n`n`T Zusätzliches Gemach für `^'.$gp_price_buy_room.'`T Gildenpunkte: "`tEure Baumeister gestalten ein völlig neues Gemach in der Gildenhalle nach euren Wünschen!"`n');
				if($guild['points'] >= $gp_price_buy_room) {
					if($leader || $treasure) {
						output('<a href="'.$link.'">Bauen!</a>',true);
						addnav('',$link);
					}
				}
				else {output('Die Anzahl der Gildenpunkte reicht leider nicht aus!');}
			}

			$link = 'dg_main.php?op=in&subop=gpshop&act=gp_buy_aufzeichnung&do=start';
			output('`n`n`T Aufzeichnung für `^'.$gp_price_aufzeichnung.'`T Gildenpunkte: "`tHier könnt ihr wichtige Ereignisse der Gilde festhalten lassen!"`n');
			if($guild['points'] >= $gp_price_aufzeichnung) {
				if($leader || $treasure) {
					output('<a href="'.$link.'">Aufzeichnen!</a>',true);
					addnav('',$link);
				}
			}
			else {output('Die Anzahl der Gildenpunkte reicht leider nicht aus!');}

			output('`n`n`b`tGildenpunkte erhält oder verbraucht die Gilde außerdem bei folgenden Gelegenheiten:`b`n`n');
			output(' - `tHeldentat : `^'.$dg_points['dk'].'`T Punkte`n');
			output(' - `tEinmalige Kosten eines Gildenkrieges : `4 - `^'.$dg_points['war_cost'].'`T Punkte`n');
			output(' - `tHochzeit mit befreundeter Gilde : `^'.$dg_points['wedding_friendly'].'`T Punkte`n');
			output(' - `tHochzeit mit neutraler Gilde : `^'.$dg_points['wedding_neutral'].'`T Punkte`n');

			output('`c');

			addnav('Zurück zur Schatzkammer','dg_main.php?op=in&subop=treasure');

			break;

		case 'transfer':

			$maxgold = dg_calc_boni($gid,'treasure_maxgold',getsetting('dgtrsmaxgold',100000));
			$maxgems = dg_calc_boni($gid,'treasure_maxgems',getsetting('dgtrsmaxgems',1000));

			dg_show_header('Gildenschatz - Transfer');

			$allowed = false;

			$_POST['count'] = (isset($_POST['count']) && $_POST['count'] == '' ? 99999999999999 : $_POST['count']);    // Maximum einzahlen

			if($_GET['act'] == 'gold') {

				if($_POST['count']) {

					if($_GET['in']) {
						$count = dg_transfer($gid,(int)$_POST['count'],'gold');
						output('Du zahlst '.$count.' Gold ein');
						if($count > 0) {
							$msg = ': `@zahlt `^'.$count.' Gold`@ in die Gildenkasse';
						}
					}
				}
				if($_GET['in']) {
					$max_transfer = dg_calc_max_transfer_in($gid,'gold');

					if($max_transfer == 0 || $session['user']['gold'] == 0) {output('Heute kannst du nichts mehr einzahlen!');}
					else {
						output('Du kannst heute noch bis zu '.$max_transfer.' Gold einzahlen.');
						$allowed = true;
					}

					if($maxgold <= $guild['gold']) {
						output('`n`n`4Die Goldtruhen sind leider schon voll!');
						$allowed = false;
					}
					else {
						output('`n`n`0In den Truhen ist noch Raum für '.($maxgold - $guild['gold']).' Gold!');
					}

				}


			}    // END if gold

			if($_GET['act'] == 'gems') {

				if($_POST['count']) {

					if($_GET['in']) {
						$count = dg_transfer($gid,(int)$_POST['count'],'gems');
						$str_what = ($count == 1 ? 'Edelstein' : 'Edelsteine');
						output('Du zahlst '.$count.' '.$str_what.' ein.');
						if($count > 0) {
							$msg = ': `@zahlt `^'.$count.' '.$str_what.'`@ in die Gildenkasse';
						}
					}

				}


				if($_GET['in']) {
					$max_transfer = dg_calc_max_transfer_in($gid,'gems');

					if($max_transfer == 0 || $session['user']['gems'] == 0) {output('Heute kannst du nichts mehr einzahlen!');}
					else {
						output('Du kannst heute noch bis zu '.$max_transfer.' Edelsteine einzahlen.');
						$allowed = true;
					}

					if($maxgems <= $guild['gems']) {
						output('`n`n`4Die Edelsteintruhen sind leider schon voll!');
						$allowed = false;
					}
					else {
						output('`n`n`0In den Truhen ist noch Raum für '.($maxgems - $guild['gems']).' Edelsteine!');
					}

				}

			}    // END if gems

			if($msg) {
				dg_commentary($gid,$msg,'treasure');
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treasure');
			}


			if($_GET['in'] == 0 && $guild['treasure_locked']) {
				output('`n`n`4Die Schatzkammer wurde durch die Gildenführung für alle Auszahlungen gesperrt!');
				$allowed = false;
			}

			if($guild['regalia'] == -1) {
				output('`n`n`4Die Gilde besitzt keine weiteren Insignien. Dadurch fehlt der Zugang zur Schatzkammer!');
				$allowed = false;
			}

			if($allowed) {

				define('JSLIB_NO_FOCUS_NEEDED',1);
				$formlink = 'dg_main.php?op=in&subop=transfer&act='.$_GET['act'].'&in='.$_GET['in'];

				output('`n`i(Feld leer lassen, um das Maximum einzuzahlen)`i`n`n<form action="'.$formlink.'" method="POST"><input type="text" name="count" id="count" size="6" maxlength="6"><input type="submit" value="'.($_GET['in'] ? 'Einzahlen':'Abheben').'"></form>
				',true);
                JS::Focus("count");
				addnav('',$formlink);
			}

			addnav('Zurück zur Schatzkammer','dg_main.php?op=in&subop=treasure');

			break;

		case 'donate':

			$acctid = (int)$_POST['acctid'];
			$what = $_GET['what'] == 'Edelsteine' ? 'Edelsteine' : 'Gold';
			$count = (int)$_POST['count'];

			dg_show_header($what.' ausgeben');

			$link = 'dg_main.php?op=in&subop=donate&what='.$what;
			addnav('',$link);

			addnav('Zurück','dg_main.php?op=in&subop=treasure');

			output('<form method="POST" action="'.$link.'">');

			if($acctid) {

				$sql = 'SELECT name, a.acctid, level, gold, gems FROM accounts a LEFT JOIN account_extra_info ai USING(acctid) WHERE a.acctid='.$acctid;
				$res = db_query($sql);
				$m = db_fetch_assoc($res);

				if($_GET['what'] == 'Gold') {
					$left = dg_calc_max_transfer_out($gid,'gold',$m['level']);
					$what_obj = 'gold';
				}
				else {
					$left = dg_calc_max_transfer_out($gid,'gems',$m['level']);
					$what_obj = 'gems';
				}

				$count = min($left,$count);

				if(($count == 1 || $left == 1) && $what == 'Edelsteine') {
					$what = 'Edelstein';
				}

				if($count > 0) {

					$count = dg_transfer($gid,-$count,$what_obj,$acctid,$m);

					if($count > 0) {
						systemmail($acctid,'`2Überweisung',$session['user']['name'].'`2 hat dir im Auftrag deiner Gilde `^'.$count.'`2 '.$what.' überwiesen!');
						dg_commentary($gid,':`4 hat an '.$m['name'].'`4 `^'.$count.'`4 '.$what.' überwiesen!','treasure',$session['user']['acctid']);
					}

					dg_save_guild();
					redirect('dg_main.php?op=in&subop=treasure');

				}
				else {

					if($left > 0) {

						output('`8Du kannst '.$m['name'].'`8 heute noch maximal `^'.$left.'`8 '.$what.' auszahlen:`n`n
						<input type="text" name="count" size="3" maxlength="4" value="0"> '.$what.' <input type="submit" value="auszahlen!">
						<input type="hidden" name="acctid" value="'.$acctid.'">',true);

					}
					else {
						output($m['name'].'`8 hat heute bereits genug '.$what.' von der Gilde erhalten!');
					}
				}    // END count <= 0
			}
			else {

				$sql = 'SELECT login, acctid FROM accounts WHERE guildid='.$gid.' AND guildfunc!='.DG_FUNC_APPLICANT.' ORDER BY login ASC';
				$res = db_query($sql);

				output('`8Welchem Gildenmitglied willst du eine Auszahlung zukommen lassen?`n`n
				<select name="acctid" size="1">',true);

				while($m = db_fetch_assoc($res)) {

					output('<option value="'.$m['acctid'].'">'.$m['login'].'</option>',true);

				}

				output('</select> `n`n
				<input type="submit" value="Weiter!">',true);

			}

			output('</form>',true);

			break;

		case 'ext_room':

			addcommentary();

			dg_show_header($guild['ext_room_name']);

			if($_GET['act'] == 'change') {

				if(mb_strlen($_POST['ext_room_name']) > 3) {

					$guild['ext_room_desc'] = mb_substr($guild['ext_room_desc'],0,600);

					$guild['ext_room_name'] = closetags($_POST['ext_room_name'],'`i`c`b');
					$guild['ext_room_desc'] = closetags($_POST['ext_room_desc'],'`i`c`b');

					dg_save_guild();
					redirect('dg_main.php?op=in&subop=ext_room');

				}
				else {

					$arr_form = array('ext_room_name'=>'Name des Gemachs (min. 4 Zeichen)',
					'ext_room_desc'=>'Beschreibung / Aussehen des Gemachs (max. 600 Zeichen)');

					$str_link = 'dg_main.php?op=in&subop=ext_room&act=change';

					addnav('',$str_link);

					output('<form method="POST" action="'.$str_link.'">',true);

					showform($arr_form,$guild);

					addnav('Zurück');
					addnav('Zum Gemach','dg_main.php?op=in&subop=ext_room');

				}

			}
			else {

				addnav('Aktionen');
				if($team) {
					addnav('Aussehen ändern','dg_main.php?op=in&subop=ext_room&act=change');
				}

				addnav('Zurück');
				addnav('Zur Halle','dg_main.php?op=in');

				output('`0'.closetags($guild['ext_room_desc'],'`i`c`b').'`n`n');

				viewcommentary('guild-'.$gid.'_xtrm','Etwas verkünden:',25,'verkündet',false,true,false,getsetting('chat_post_len_long',1500),false,true,2);

				// Möbel
				dg_show_furniture('ext');
				// END Möbel
			}

			break;
		//Zusatzgemach nummer 2	
		case 'ext_room2':

			addcommentary();

			dg_show_header($guild['ext_room_name2']);

			if($_GET['act'] == 'change') {

				if(mb_strlen($_POST['ext_room_name2']) > 3) {

					$guild['ext_room_desc2'] = mb_substr($guild['ext_room_desc2'],0,1000);

					$guild['ext_room_name2'] = closetags($_POST['ext_room_name2'],'`i`c`b');
					$guild['ext_room_desc2'] = closetags($_POST['ext_room_desc2'],'`i`c`b');

					dg_save_guild();
					redirect('dg_main.php?op=in&subop=ext_room2');
					
				}
				else {

					$arr_form = array('ext_room_name2'=>'Name des Gemachs (min. 4 Zeichen)',
					'ext_room_desc2'=>'Beschreibung / Aussehen des Gemachs (max. 1000 Zeichen)');

					$str_link = 'dg_main.php?op=in&subop=ext_room2&act=change';

					addnav('',$str_link);

					output('<form method="POST" action="'.$str_link.'">',true);

					showform($arr_form,$guild);

					addnav('Zurück');
					addnav('Zum Gemach','dg_main.php?op=in&subop=ext_room2');

				}

			}
			else {

				addnav('Aktionen');
				if($team) {
					addnav('Aussehen ändern','dg_main.php?op=in&subop=ext_room2&act=change');
				}

				addnav('Zurück');
				addnav('Zur Halle','dg_main.php?op=in');

				output('`0'.closetags($guild['ext_room_desc2'],'`i`c`b').'`n`n');

				viewcommentary('guild-'.$gid.'_xtrm2','Etwas verkünden:',25,'verkündet',false,true,false,getsetting('chat_post_len_long',1500),false,true,2);

				// Möbel
				//dg_show_furniture('ext2');
				// END Möbel
			}

			break;

		case 'builds':

			dg_show_header('Die Ausbauten');

			$int_buildlvl_left = getsetting('dgmaxbuilds',30) - dg_get_ges_build($gid);

			if($_GET['act'] == 'start') {

				if($int_buildlvl_left <= 0) {
					output('`8Du musst erkennen, dass die Residenz leider keinen weiteren Platz für Ausbauten bietet.`nFalls ihr nicht auf
					dieses Gebäude verzichten könnt, lasst einen vorhandenen Ausbau abreißen!');
				}
				else {
					output('`8Du bist im Begriff, den Ausbau '.$dg_builds[$_GET['type']]['name'].' in Auftrag zu geben. Dies ist eine ungefähre Beschreibung, was dich erwartet:`n`n'.$dg_builds[$_GET['type']]['desc']);
					addnav('Ausbau beginnen!','dg_main.php?op=in&subop=builds&act=start_ok&type='.$_GET['type']);
				}
				addnav('Zurück','dg_main.php?op=in&subop=builds');

			}

			elseif($_GET['act'] == 'start_ok') {

				dg_build($gid,(int)$_GET['type']);
				dg_commentary($gid,': hat soeben den Ausbau `@'.$dg_builds[$_GET['type']]['name'].'`8 in Auftrag gegeben!','');
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=builds');

			}

			elseif($_GET['act'] == 'del') {

				output('`$Du bist im Begriff, den Ausbau '.$dg_builds[$_GET['type']]['name'].' abreißen zu lassen. Die Gilde wird keine Entschädigung oder Rückerstattung der Baukosten erhalten!');
				addnav('Ausbau abreißen!','dg_main.php?op=in&subop=builds&act=del_ok&type='.$_GET['type']);
				addnav('Zurück','dg_main.php?op=in&subop=builds');

			}

			elseif($_GET['act'] == 'del_ok') {

				unset($guild['build_list'][$_GET['type']]);

				dg_commentary($gid,':`$ hat soeben den Ausbau `@'.$dg_builds[$_GET['type']]['name'].'`$ abreißen lassen!','');
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=builds');

			}
			else {

				output('`8Eine Auflistung aller Ausbauten der Gilde ist dringend nötig, wer soll sonst den Überblick wahren können? Auch haben die Führungsmitglieder der Gilde hier die Möglichkeit, Ausbauten in Auftrag zu geben.`n`n
				Die Residenz der Gilde bietet `b'.($int_buildlvl_left>0?' noch Platz für etwa '.$int_buildlvl_left:'keinen weiteren Platz für').'`b Ausbaustufen.`n`n');

				$recent_build = $guild['build_list'][0];

				if($recent_build[0]) {
					output('Aktueller Ausbau: `b'.$dg_builds[$recent_build[0]]['name'].'`b (Noch `b'.$recent_build[1].'`b '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage').' bis zur Fertigstellung)`n`n');
				}

				dg_show_builds($gid,(($leader || $treasure)?true:false) );

				output('`n`n');

				foreach($dg_builds as $id=>$b) {
					if(dg_build_is_allowed($gid,$id)) {output('`8 - '.$b['desc'].'`n');}
				}

				addnav('Zurück zur Schatzkammer','dg_main.php?op=in&subop=treasure');
			}

			break;

		case 'private':

			if($_GET['act'] == 'invite') {
				$acctid = (int)$_POST['acctid'];

				item_add($acctid,'gldprive',array('tpl_value1'=>$gid));

				systemmail($acctid,'`8Einladung der Gildenführung',$session['user']['name'].'`8 hat dir eine Einladung in das Hinterzimmer der Gilde '.$guild['name'].'`8 überreicht. Vielleicht solltest du mal vorbeischauen.');
				redirect('dg_main.php?op=in&subop=private');
			}
			else if($_GET['act'] == 'cancel') {
					$itemid = (int)$_GET['itemid'];
					item_delete('id='.$itemid);
					redirect('dg_main.php?op=in&subop=private');
				}

				addcommentary();

			dg_show_header('Hinterzimmer der Gilde '.$guild['name']);

			output('`8Natürlich existiert ein abgeschiedener, vom übrigen Gildengebäude abgetrennter Bereich, der nur für die Gildenführer und ihre Minister zugänglich ist. Hier ist Gelegenheit, über führungsinterne Dinge zu beraten:`n`n');

			require_once(LIB_PATH.'board.lib.php');
			output('`c');
			$int_pollrights = ($leader ? 2 : 1);
			if(poll_view('guild'.$gid.'_private',$int_pollrights,$int_pollrights)) {
				output('`n`^~~~~~~~~`0`n`n',true);
			}
			output('`c');

			viewcommentary('guild-'.$gid.'_private','Mit den Führungsmitgliedern der Gilde sprechen:',25,'sagt',false,true,false,getsetting('chat_post_len_long',1500),false,true,2);

			output('`n`n');

			output('`8Folgende Gildenmitglieder haben außer dem Führungsteam noch Zutritt:`n`n');

			$sql = 'SELECT
			a.acctid,a.name,a.login,a.sex,guildfunc,guildrank,loggedin,dragonkills,activated,laston,expedition,activated,
			i.owner,i.id
			'.($bool_lockhtml ? ',aei.html_locked' : '').'
			FROM items i
			LEFT JOIN accounts a ON a.acctid=i.owner
			'.($bool_lockhtml ? 'INNER JOIN account_extra_info aei ON a.acctid=aei.acctid' : '').'
			WHERE i.tpl_id="gldprive" AND i.value1='.$gid.' ORDER BY a.name ASC,a.acctid ASC';
			$res = db_query($sql);
			$counter = 0;
			$ids = '';

			while($i = db_fetch_assoc($res)) {
				$ids .= ','.$i['owner'];
				$counter++;
				output('`n`8'.$counter.' : '.CRPChat::menulink($i).'`8');
				if($leader) {
					$link = 'dg_main.php?op=in&subop=private&act=cancel&itemid='.$i['id'];
					output(' [ <a href="'.$link.'">Ausladen</a> ]',true);
					addnav('',$link);
				}
			}

			if($team) {

				addnav('Aktionen');

				addnav('Umfrage in Hinterzimmer','dg_main.php?op=in&subop=polls&pollsection=private');
				addnav('Umfrage in Gildenhalle','dg_main.php?op=in&subop=polls&pollsection=hall');

				if($leader) {

					addnav('Gildenhallentext schreiben','dg_main.php?op=in&subop=own_text');
					addnav('`4Gilde auflösen','dg_main.php?op=in&subop=del',false,false,false,false);

					$sql = 'SELECT a.name,a.acctid FROM accounts a
					WHERE a.guildid='.$gid.' AND a.acctid NOT IN (0'.$ids.')
					AND a.guildfunc='.DG_FUNC_MEMBER.' ORDER BY a.name ASC,a.acctid ASC';
					$res = db_query($sql);

					if(db_num_rows($res) > 0) {

						$link = 'dg_main.php?op=in&subop=private&act=invite';
						addnav('',$link);

						output('<form method="POST" action="'.$link.'"><select name="acctid" size="1">',true);

						while($a = db_fetch_assoc($res)) {
							output('<option value="'.$a['acctid'].'.">'.$a['name'].'</option>',true);
						}

						output('</select> <input type="submit" value="Einladen!"></form>`n`n',true);
					}

				}

			}

			addnav('Zurück');
			addnav('Zur Halle','dg_main.php?op=in');

			break;

		case 'del':

			if($_GET['act'] == 'ok') {
				dg_massmail($gid,'`4Gilde aufgelöst!',$session['user']['name'].'`4 hat soeben die Gilde '.$guild['name'].'`4, in der du Mitglied warst, aufgelöst!');
				dg_addnews($session['user']['name'].'`4 hat soeben die Gilde '.$guild['name'].'`4 aufgelöst!',$session['user']['acctid']);
				dg_delete_guild($gid);
				redirect('dg_main.php');
			}

			output('`c`$Bist du dir wirklich sicher, deine Gilde auflösen zu wollen? Der gesamte Schatz und sämtliche Ausbauten werden verlorengehen!`c`0');

			addnav('Nein, zurück.','dg_main.php?op=in&subop=private');
			addnav('Ja, auflösen!','dg_main.php?op=in&subop=del&act=ok');

			break;

		case 'members':

			$members_left = dg_guild_is_full($gid);

			if($_GET['act'] == 'refuse_applicant') {
				$acctid = (int)$_GET['acctid'];
				dg_remove_member($gid,$acctid,true);
				systemmail($acctid,'`4Bewerbung zurückgewiesen!','`4Deine Bewerbung auf Mitgliedschaft in der Gilde '.$guild['name'].'`4 wurde von '.$session['user']['name'].'`4 zurückgewiesen!');

				dg_save_guild();

				redirect('dg_main.php?op=in&subop=members');

			}
			elseif($_GET['act'] == 'accept_applicant') {

				if($members_left <= 0) { output('`$Die Gilde verfügt bereits über die maximale Mitgliedsanzahl!`8');  }
				else {
					$acctid = (int)$_GET['acctid'];
					dg_add_member($gid,$acctid);

					$res = db_query('SELECT name FROM accounts WHERE acctid='.$acctid);
					$appl = db_fetch_assoc($res);

					systemmail($acctid,'`@Bewerbung angenommen!','`@Deine Bewerbung auf Mitgliedschaft in der Gilde '.$guild['name'].'`@ wurde von '.$session['user']['name'].'`@ angenommen!');

					dg_addnews($appl['name'].'`8 ist seit heute Mitglied der Gilde '.$guild['name'].'`8.',$acctid);

					addhistory('`2Beitritt zu Gilde '.$guild['name'],1,$acctid);

					dg_commentary($gid,': ist seit heute Mitglied dieser Gilde!','',$acctid);

					dg_save_guild();

					redirect('dg_main.php?op=in&subop=members');
				}

			}
			elseif($_GET['act'] == 'fire') {
				$acctid = (int)$_GET['acctid'];

				$res = db_query('SELECT name,sex FROM accounts WHERE acctid='.$acctid);
				$appl = db_fetch_assoc($res);

				dg_remove_member($gid,$acctid);
				systemmail($acctid,'`4Entlassung aus Gilde!','`4Deine Mitgliedschaft in der Gilde '.$guild['name'].'`4 wurde von '.$session['user']['name'].'`4 gekündigt!');

				dg_addnews($appl['name'].'`8 hat die Gilde '.$guild['name'].'`8 unfreiwillig verlassen.',$acctid);

				addhistory('`$Entlassung aus Gilde '.$guild['name'],1,$acctid);

				dg_commentary($gid,'/msg `5Kräftige Gildenwachen packen '.$appl['name'].'`5 und setzen '.($appl['sex']?'sie':'ihn').' vor die Tür!','',1);

				dg_save_guild();

				redirect('dg_main.php?op=in&subop=members');

			}

			$admin_mode = ($members) ? 2 : 0;
			$admin_mode = ($leader) ? 3 : $admin_mode;

			dg_show_header('Mitglieder &amp; Ränge');

			output('`8Der Saal der Mitglieder ist ein kreisrundes Gemach mit einem riesigen Tisch. An der Wand erblickst du eine Tafel, auf der alle Angehörigen dieser Gilde verzeichnet sind:`n`n');

			if( mb_strlen($guild['professions_allowed']) > 1) {
				$prof_list = explode(',',$guild['professions_allowed']);
				output('Die Gilde ist nur für Personen mit folgenden oder gar keinen Ämtern zugänglich:`n');
				foreach($prof_list as $p) {
					if($p) {
						output($profs[$p][0].'; ');
					}
				}
				output('`n`n');
			}

			output( ($members_left>0?'Noch Platz für `b'.$members_left.'`b Mitglieder!':'Kein Platz mehr für Neuaufnahmen!').'`n`n`0' );

			dg_show_member_list($gid,$admin_mode);

			addnav('Die Ränge','dg_main.php?op=in&subop=ranks');
			addnav('Einladungen','dg_main.php?op=in&subop=invitations');
			if($team)
			{
				addnav('Massenmail (`b1`b Gildenpunkt)','dg_main.php?op=in&subop=massmail');
				//Eingefügter Unikatlink

				addnav('Einzigartiges Möbelstück','dg_main.php?op=in&subop=unikate');
			}

			addnav('Zurück zur Halle','dg_main.php?op=in');

			break;

		case 'invitations':

			$gp_per_hour = getsetting('guildinvitationcost',4);
			dg_show_header('Einladungen in die Gildenhalle');
			$error_log = '';
			$invitationstep = 5;
			if (mb_strlen($_GET['act'])>0)
			{
				output('`c<div class="trlight" style="width:500px;border-style:inset;border-width:1px;">');
			}
			if ($_GET['act'] == 'lastcheck')
			{
				$gp_cost = ceil(($_GET['expire']-$_GET['start'])/60/60)*$gp_per_hour;
				$target = db_fetch_assoc(db_query("SELECT * FROM `accounts` WHERE `acctid` = ".$_GET['targetid']." LIMIT 1"));
				output('`bNeue Einladung`b`n<table border="0"><tr><td>Schritt 5/5&nbsp;</td><td valign="middle">'.grafbar(100,100,100,15).'</td></tr></table>`n');
				output(form_header('dg_main.php?op=in&subop=invitations&act=submit&start='.$_GET['start'].'&expire='.$_GET['expire'].'&targetid='.$target['acctid'].'&gpcost='.$gp_cost).
				'Wer soll die Einladung erhalten: '.$target['name'].'`0'.
				'`nAb wann soll sie gültig sein: '.date('j.n.Y H:i',$_GET['start']).' Uhr'.
				'`nBis wann soll sie gültig sein: '.date('j.n.Y H:i',$_GET['expire']).' Uhr'.
				'`n<table border="0"><tr><td valign="top">Nachricht:&nbsp;</td><td><textarea name="nachricht" class="input" maxlength="1000" cols="40" rows="5" readonly>'.strip_appoencode((get_magic_quotes_gpc()?stripslashes($_POST['nachricht']):$_POST['nachricht']),3).'</textarea></td></tr></table>'.
				'`n`^Bitte versichere dich, dass alle angegebenen Informationen richtig sind. Der Preis beträgt `b'.$gp_cost.'`b Gildenpunkte ('.$gp_per_hour.'GP/h).`n<input type="submit" name="submitbutton" value="Ja, verschicken!" class="button">'.form_footer());
			}
			elseif ($_GET['act'] == 'submit')
			{
				if ($guild['points']<$_GET['gpcost'])
				{
					output('`$`bNicht genügend Gildenpunkte vorhanden!`b`0');
				}
				else
				{
					$description = 'Eine Einladung in die Gilde '.$guild['name'].'`0, gültig vom '.date('j.n.Y H:i',$_GET['start']).' Uhr bis zum '.date('j.n.Y H:i',$_GET['expire']).' Uhr.'.((mb_strlen(trim($_POST['nachricht']))>0)?'`nEs ist außerdem folgende Nachricht beigefügt:`n'.$_POST['nachricht']:'');
					$content = utf8_serialize(array('guildid'=>$guild['guildid'],'start'=>$_GET['start'],'expire'=>$_GET['expire'],'valid'=>true));
					item_add($_GET['targetid'],'guildinvitation',array('tpl_description'=>$description,'content'=>$content));
					$guild['points'] -= $_GET['gpcost'];
					output('`b`jEinladung verschickt!`b`n`n`b`$(Bitte denke daran, dem Glücklichen ggf. per Taube dies mitzuteilen.)`b`0');
				}
			}
			elseif ($_GET['act'] == 'delete')
			{
				$item = item_get(' `id` = '.$_GET['itemid'].' ');
				if($item!==false)
				{
					$content = utf8_unserialize($item['content']);
					$content['valid'] = false;
					item_set(' `id` = '.$_GET['itemid'].' ',array('description'=>$item['description'].'`n`4Die Einladung wurde mit einem fetten roten Stempel vorzeitig entwertet!','content'=>utf8_serialize($content)));
					$array_user = db_fetch_assoc(db_query("SELECT * FROM `accounts` WHERE `acctid` = ".$_GET['target']));
					systemmail($array_user['acctid'],'`4Gildeneinladung entwertet`0','`&Deine Einladung in die Gilde '.$guild['name'].'`& wurde auf Befehl der Gildenleitung hin vorzeitig entwertet!');
					output('`$`bEinladung entwertet!`b`n`&Es wurde eine automatische Benachrichtigung verschickt.`nNoch eine Mitteilung an den Betroffenen:&nbsp;'.CRPChat::menulink($array_user).'`0');
				}
			}
			if ($_GET['act'] == 'message')
			{
				$target = db_fetch_assoc(db_query("SELECT * FROM `accounts` WHERE `acctid` = ".$_GET['targetid']." LIMIT 1"));
				$expire = mktime($_POST['hour'],$_POST['minute'],0,$_POST['month'],$_POST['day'],$_POST['year']);
				$start = $_GET['start'];
				if ($expire>$start)
				{
					output('`bNeue Einladung`b`n<table border="0"><tr><td>Schritt 4/5&nbsp;</td><td valign="middle">'.grafbar(100,80,100,15).'</td></tr></table>`n');
					output(form_header('dg_main.php?op=in&subop=invitations&act=lastcheck&start='.$start.'&expire='.$expire.'&targetid='.$target['acctid']).
					'Wer soll die Einladung erhalten: '.$target['name'].'`0'.
					'`nAb wann soll sie gültig sein: '.date('j.n.Y H:i',$start).' Uhr'.
					'`nBis wann soll sie gültig sein: '.date('j.n.Y H:i',$expire).' Uhr'.
					'`n<table border="0"><tr><td valign="top">Nachricht anhängen:&nbsp;</td><td><textarea name="nachricht" class="input" maxlength="1000" cols="40" rows="5"></textarea></td><td valign="top"><input type="submit" name="submitbutton" value="Weiter" class="button"></td></tr></table>'.form_footer());
				}
				else
				{
					$error_log .= '`$`iUngültiges Datum! (Es muss nach dem Startdatum liegen!)`i`0';
					$_GET['act'] = 'enterdate2';
					$start = $_GET['start'];
				}
			}
			if ($_GET['act'] == 'enterdate2')
			{
				$target = db_fetch_assoc(db_query("SELECT * FROM `accounts` WHERE `acctid` = ".$_GET['targetid']." LIMIT 1"));
				if ($start==0) $start = mktime($_POST['hour'],$_POST['minute'],0,$_POST['month'],$_POST['day'],$_POST['year']);
				if ($start>=time())
				{
					$bald = getdate($start+(24*60*60));
					output('`bNeue Einladung`b`n<table border="0"><tr><td>Schritt 3/5&nbsp;</td><td valign="middle">'.grafbar(100,60,100,15).'</td></tr></table>`n');
					output(form_header('dg_main.php?op=in&subop=invitations&act=message&targetid='.$target['acctid'].'&start='.$start).
					'Wer soll die Einladung erhalten: '.$target['name'].'`0'.
					'`nAb wann soll sie gültig sein: '.date('j.n.Y H:i',$start).' Uhr'.
					'`nBis wann soll sie gültig sein: <input name="day" size="3" maxlength="2" value="'.$bald['mday'].'">.<input name="month" size="3" maxlength="2" value="'.$bald['mon'].'">.<input name="year" size="5" maxlength="4" value="'.$bald['year'].'"> <input name="hour" size="3" maxlength="2" value="'.$bald['hours'].'">:<input name="minute" size="3" maxlength="2" value="'.$bald['minutes'].'"> Uhr'.
					' <input type="submit" name="submitbutton" value="Weiter" class="button">'.form_footer());
				}
				else
				{
					$error_log .= '`$`iUngültiges Datum! (Es muss in der Zukunft liegen.)`i`0';
					$_GET['act'] = 'enterdate1';
					$_POST['username'] = $target['name'];
				}
			}
			if ($_GET['act'] == 'enterdate1')
			{

				$search_result = db_query("SELECT * FROM `accounts` WHERE `name` LIKE '".db_real_escape_string($_POST['username'])."' OR login LIKE '".db_real_escape_string($_POST['username'])."'  LIMIT 1");
				if ($row = db_fetch_assoc($search_result))
				{
					output('`bNeue Einladung`b`n<table border="0"><tr><td>Schritt 2/5&nbsp;</td><td valign="middle">'.grafbar(100,40,100,15).'</td></tr></table>`n');
					$bald = getdate(time()+(5*60)); //fast jetzt
					output(form_header('dg_main.php?op=in&subop=invitations&act=enterdate2&targetid='.$row['acctid']).
					'Wer soll die Einladung erhalten: '.$row['name'].'`0'.
					'`nAb wann soll sie gültig sein: <input name="day" size="3" maxlength="2" value="'.$bald['mday'].'">.<input name="month" size="3" maxlength="2" value="'.$bald['mon'].'">.<input name="year" size="5" maxlength="4" value="'.$bald['year'].'"> <input name="hour" size="3" maxlength="2" value="'.$bald['hours'].'">:<input name="minute" size="3" maxlength="2" value="'.$bald['minutes'].'"> Uhr'.
					' <input type="submit" name="submitbutton" value="Weiter" class="button">'.form_footer());
				}
				else
				{
					$error_log .= '`$`iKeinen passender Eintrag gefunden!`i`0';
					$_GET['act'] = 'searchuser';
				}
			}
			if ($_GET['act'] == 'searchuser')
			{
				output('`bNeue Einladung`b`n<table border="0"><tr><td>Schritt 1/5&nbsp;</td><td valign="middle">'.grafbar(100,20,100,15).'</td></tr></table>`n');
				output(form_header('dg_main.php?op=in&subop=invitations&act=enterdate1').'Wer soll die Einladung erhalten: '.JS::Autocomplete('username', true, true).form_footer());
			}
			if (mb_strlen($_GET['act'])>0)
			{
				output($error_log.'</div>`c`n');
			}
			$str_output = '';
			$array_invitations = item_list_get(" `tpl_id` = 'guildinvitation' ",'',false,'*',true);
			$int_count = 0;
			if (count($array_invitations)>0)
			{
				$str_output .= '<table><tr class="trhead"><th>`&Name</th><th>`&Von</th><th>`&Bis</th>'.(($team || $private)?'<th></th>':'').'</tr>';
				foreach($array_invitations as $current_item)
				{
					$content = utf8_unserialize($current_item['content']);
					if (($content['guildid']==$guild['guildid']) && ($content['expire']>time()) && ($content['valid']))
					{
						$int_count++;
						$array_user = db_fetch_assoc(db_query("SELECT * FROM `accounts` WHERE `acctid` = ".$current_item['owner']));
						$str_output .= '<tr class="'.($int_count%2?'trlight':'trdark').'"><td>'.CRPChat::menulink($array_user).'</td><td>'.date('j.n.Y H:i',$content['start']).' Uhr'.'</td><td>'.date('j.n.Y H:i',$content['expire']).' Uhr'.'</td>'.(($team || $private)?'<td>'.create_lnk('Entwerten','dg_main.php?op=in&subop=invitations&act=delete&itemid='.$current_item['id'].'&target='.$current_item['owner'],true,false,'Willst du diese Einladung wirklich ungültig machen?').'</td>':'').'</tr>';
					}
				}
				$str_output .= '</table>';
			}
			output(get_extended_text('guild_invitations').'`n`n');
			output('`b'.$int_count.'`b Einladung'.($int_count==1?'':'en').' vergeben (es werden nur gültige Einladungen gezählt sowie angezeigt).`n'.($int_count>0?$str_output:''));
			if ($team || $private)
			{
				addnav('Neue Einladung','dg_main.php?op=in&subop=invitations&act=searchuser');
			}
			addnav('Zur Mitgliederliste','dg_main.php?op=in&subop=members');
			break;

		case 'ranks':

			dg_show_header('Die Ränge');

			if($_GET['act'] == 'save') {
				$_POST['man'] = mb_substr( str_replace('`0','',$_POST['man']) ,0,25);
				$_POST['woman'] = mb_substr( str_replace('`0','',$_POST['woman']) ,0,25);
				$guild['ranks'][$_GET['nr']][0] = $_POST['man'].'`0';
				$guild['ranks'][$_GET['nr']][1] = $_POST['woman'].'`0';
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=ranks');
			}

			output('`8Ebenso kann man bei genauerem Hinsehen eine Schriftrolle entdecken, auf der alle Ränge dokumentiert sind, die man in dieser Gilde erreichen kann:`n`n');

			$admin_mode = ($members || $leader) ? 1 : 0;

			dg_show_ranks($gid,$admin_mode);

			addnav('Zur Mitgliederliste','dg_main.php?op=in&subop=members');

			break;

		case 'member_edit':

			$acctid = (int)$_GET['acctid'];

			$sql = 'SELECT guildrank,guildfunc,name,sex FROM accounts WHERE acctid='.$acctid;
			$res = db_query($sql);
			$act = db_fetch_assoc($res);

			if($_GET['act'] == 'save') {

				$func = (int)$_POST['func'];
				$rank = (int)$_POST['rank'];

				if($rank != $act['guildrank']) {
					if($act['guildrank'] < $rank) {
						$msg = ': wurde vom Rang '.$guild['ranks'][$act['guildrank']][$act['sex']].'`& degradiert zu '.$guild['ranks'][$rank][$act['sex']];
						systemmail($acctid,'`4Degradierung!','`4Du hast nun in der Gilde '.$guild['name'].'`4 den Rang '.$guild['ranks'][$rank][$act['sex']].'`4 inne!');
					}
					else {
						$msg = ': wurde vom Rang '.$guild['ranks'][$act['guildrank']][$act['sex']].'`& zu '.$guild['ranks'][$rank][$act['sex']].'`& erhoben!';
						systemmail($acctid,'`@Beförderung!','`@Du hast nun in der Gilde '.$guild['name'].'`@ den Rang '.$guild['ranks'][$rank][$act['sex']].'`@ inne!');
					}
					dg_commentary($gid,$msg,'',$acctid);

					if($acctid == $session['user']['acctid']) {
						$session['user']['guildrank'] = $rank;
					}
					else {
						user_update(
						array
						(
						'guildrank'=>$rank,
						),
						$acctid
						);

					}

				}

				if($func > 0) {
					if($func != $act['guildfunc']) {
						$allowed = true;
						// Überprüfen, ob es außer uns noch einen Führer gibt
						if($acctid == $session['user']['acctid'] && ($func != DG_FUNC_LEADER) ) {
							$sql = 'SELECT COUNT(*) AS anzahl FROM accounts WHERE guildfunc='.DG_FUNC_LEADER.' AND acctid!='.$acctid.' AND guildid='.$gid.' GROUP BY acctid';
							$count = db_fetch_assoc(db_query($sql));
							if($count['anzahl'] == 0) {
								$allowed = false;
							}
						}

						// Überprüfen, ob dieses Amt nicht schon besetzt ist
						if($allowed && ($func == DG_FUNC_WAR || $func == DG_FUNC_MEMBERS || $func == DG_FUNC_TREASURE) ) {

							$sql = 'SELECT COUNT(*) AS anzahl FROM accounts WHERE guildfunc='.$func.' AND acctid!='.$acctid.' AND guildid='.$gid.' GROUP BY acctid';
							$count = db_fetch_assoc(db_query($sql));

							if($count['anzahl'] > 0) {
								$allowed = false;
							}

						}

						if($allowed) {

							if($func != DG_FUNC_MEMBER) {
								$msg = ': hat nun die Funktion '.$dg_funcs[$func][$act['sex']].' inne!';
								systemmail($acctid,'`@Neues Amt!','`@Du hast nun in der Gilde '.$guild['name'].'`@ die Aufgabe '.$dg_funcs[$_POST['func']][$act['sex']].' inne!');

								addhistory('`2Amt '.$dg_funcs[$func][$act['sex']].' in Gilde '.$guild['name'],1,$acctid);

								if($func == DG_FUNC_LEADER) {
									addhistory('`2Neuer Gildenführer '.$act['name'],2,$guild['guildid']);
								}

							}
							else {
								$msg = ': hat nun keine Aufgabe mehr!';
								systemmail($acctid,'`4Kein Amt mehr!','`4Du hast nun in der Gilde '.$guild['name'].'`4 kein Amt mehr inne!');

								addhistory('`$Kein Amt mehr in Gilde '.$guild['name'],1,$acctid);

								if($act['func'] == DG_FUNC_LEADER) {
									addhistory('`$Gildenführer '.$act['name'].'`$ abgesetzt',2,$guild['guildid']);
								}

							}
							dg_commentary($gid,$msg,'',$acctid);

							if($acctid == $session['user']['acctid']) {
								$session['user']['guildfunc'] = $func;
							}
							else {
								user_update(
								array
								(
								'guildfunc'=>$func,
								),
								$acctid
								);
							}
						}    // END if allowed


					}    // END func modded
				}    // END func gegeben

				redirect('dg_main.php?op=in&subop=members');

			}    // END save

			dg_show_header($act['name'].'`8 ändern:`n`n');

			$out = '<form method="POST" action="dg_main.php?op=in&subop=member_edit&act=save&acctid='.$acctid.'">'.
			(($leader)?'Aufgabe: <select name="func" size="1">
			<option value="'.DG_FUNC_MEMBER.'"  '.((DG_FUNC_MEMBER == $act['guildfunc'])?'selected="selected"':'').'>Keine</option>
			<option value="'.DG_FUNC_WAR.'"  '.((DG_FUNC_WAR == $act['guildfunc'])?'selected="selected"':'').'>'.$dg_funcs[DG_FUNC_WAR][$act['sex']].'</option>
			<option value="'.DG_FUNC_MEMBERS.'" '.((DG_FUNC_MEMBERS == $act['guildfunc'])?'selected="selected"':'').'>'.$dg_funcs[DG_FUNC_MEMBERS][$act['sex']].'</option>
			<option value="'.DG_FUNC_TREASURE.'" '.((DG_FUNC_TREASURE == $act['guildfunc'])?'selected="selected"':'').'>'.$dg_funcs[DG_FUNC_TREASURE][$act['sex']].'</option>
			<option value="'.DG_FUNC_LEADER.'" '.((DG_FUNC_LEADER == $act['guildfunc'])?'selected="selected"':'').'>'.$dg_funcs[DG_FUNC_LEADER][$act['sex']].'</option>
			</select>`n`n':'').
			'Rang: <select name="rank" size="1">';

			foreach($guild['ranks'] as $k=>$v) {
				$out .= '<option value="'.$k.'" '.(($k == $act['guildrank'])?'selected="selected"':'').'>'.$v[$act['sex']].'</option>';
			}

			$out .= '</select>`n`n<input type="submit" value="Übernehmen"></form>';

			output($out,true);

			addnav('','dg_main.php?op=in&subop=member_edit&act=save&acctid='.$acctid);
			addnav('Zurück zu den Mitgliedern','dg_main.php?op=in&subop=members');

			break;


		case 'massmail':

			dg_show_header('Rundschreiben an alle Gildenmitglieder verfassen');

			if($guild['points'] >= 1) {
				if($_GET['act'] == 'send') {

					if(mb_strlen($_POST['msg']) > 0) {

						$guild['points']--;

						dg_save_guild();

						$msg = '`8`c`bRundschreiben der Gilde '.$guild['name'].'`b`c`8`n`n'.$_POST['msg'];

						dg_massmail($gid,$_POST['subject'],$msg);

						redirect('dg_main.php?op=in&subop=members');

					}

				}

				output('<form method="POST" action="dg_main.php?op=in&subop=massmail&act=send">Betreff: <input type="text" name="subject" size="50" maxlength="50">`n`nNachricht:`n <textarea name="msg" cols="40" rows="9" class="input"></textarea>`n`n<input type="submit" value="Abschicken!"></form>',true);

				addnav('','dg_main.php?op=in&subop=massmail&act=send');
			}
			else {
				output('Die Gilde kann ihre Boten ja nicht mal bezahlen!');
			}

			addnav('Zur Mitgliederliste','dg_main.php?op=in&subop=members');

			break;

			//Unikate für Gp kaufen eingefügt von Flompy
		case 'unikate':
			if($_GET['act'] == '')
			{
				$max_zeichen = 250 + $_GET['gems']*10;
				dg_show_header('Unikate an Gildenmitglieder vergeben');
				output('
				Du betritst einen kleinen Laden im Gildenviertel, der eher einer Rumpelkammer gleicht. Sofort erklingen leise Glocken und aus dem Hintergrund eilt dir ein kleines Männchen mit Sägespänen in den zerzausten Haaren und durchgeblichener Kleidung entgegen.
				"Soso ich soll dir also ein paar Schätze für deine Freunde herstellen. Na dann lass mal hören, was du dir so vorstellst. Weisst ja was das kostet, ne? `b10 GP pro Gegenstand`b"`n`n`0'.
				form_header('dg_main.php?op=in&subop=unikateauswahl&gems='.$_GET['gems']).
				generateform(
				array(
				'preview_name'    => ',preview,name',
				'name'            => 'Name des Möbelstücks',
				'preview_desc'    => ',preview,desc',
				'desc'            => 'Beschreibung des Möbelstücks,textarea,80,5,'.$max_zeichen,
				),
				array(),
				false,
				'Kaufen'
				).
				form_footer());

				addnav('Mehr Zeichen?');
				addnav('Beschreibungstext für Edelsteine verlängern','dg_main.php?op=in&subop=unikate&act=chars');
				addnav('Zurück');
				addnav('Zurück zur Halle','dg_main.php?op=in');
				addnav('Zurück zu Mitglieder und Ränge','dg_main.php?op=in&subop=members');
			}

			elseif ($_GET['act'] == 'chars')
			{ //Unikate, Beschreibungstext verlängern
				output('
				Für 1 Edelstein kannst du 10 Zeichen zum Beschreibungstext deines Unikates dazukaufen, maximal jedoch die Länge von 250 auf 500 Zeichen verdoppeln. Wie viele Edelsteine willst du dafür ausgeben?`n`n'.
				form_header('dg_main.php?op=in&subop=unikate&act=char_confirm').
				generateform(
				array(
				'gems'=>'Wie viele Edelsteine ausgeben?,int'
				)
				,array(),
				false,
				'Bestätigen'
				).
				form_footer());
				addnav('Mehr Zeichen?');
				addnav('Doch nicht','dg_main.php?op=in&subop=unikate');
			}
			elseif ($_GET['act'] == 'char_confirm')
			{ //Bestätigung Beschreibungstext verlängern
				$int_gems=intval($_REQUEST['gems']);
				if ($_REQUEST['gems'] && ($int_gems < 1 || $int_gems > 25))
				{
					addnav('Mehr Zeichen?');
					output('`b`$'.$int_gems.' ist kein gültiger Wert!`0`b`n`nDu musst mindestens 1 und kannst höchstens 25 Edelsteine zum Verlängern des Beschreibungstextes ausgeben.`n'.
					create_lnk('Nochmal versuchen','dg_main.php?op=in&subop=unikate&act=chars',true,true).' '.
					create_lnk('Doch nicht','dg_main.php?op=in&subop=unikate',true,true));
				}
				elseif ($int_gems > $guild['gems'])
				{
					addnav('Mehr Zeichen?');
					output('`b`$Ihr habt keine '.$int_gems.' Edelsteine!`0`b`n`nDu musst mindestens 1 und kannst höchstens 25 Edelsteine zum Verlängern des Beschreibungstextes ausgeben und musst diese natürlich auch besitzen.'.
					create_lnk('Nochmal versuchen','dg_main.php?op=in&subop=unikate&act=chars',true,true).' '.
					create_lnk('Doch nicht','dg_main.php?op=in&subop=unikate',true,true));
				}
				else
				{
					$zeichen = $int_gems*10;
					addnav('Mehr Zeichen?');
					output('Willst du wirklich '.$int_gems.' Edelsteine ausgeben und damit den Beschreibungstext deines Unikates um '.$zeichen.' Zeichen verlängern?`n`n'.
					create_lnk('Aber sicher','dg_main.php?op=in&subop=unikate&gems='.$int_gems,true,true).' '.
					create_lnk('Doch nicht','dg_main.php?op=in&subop=unikate',true,true));
				}
			}
			break;

		case 'unikateauswahl'://einzigartiges Möbelstück, Vorschau, Auswahl
			addnav('Besonderes Möbelstück');
			$max_zeichen = 250 + $_GET['gems']*10;
			$name = trim(stripslashes($_POST['name']));
			$desc = mb_substr(trim(stripslashes($_POST['desc'])),0,$max_zeichen);

			$sql = 'SELECT acctid,name FROM accounts WHERE guildid="'.$gid.'"'; //sql Abfrage der Gildenmitglieder von $gid
			$res = db_query($sql);
			while($m = db_fetch_array($res))
			{
				$str_list .= '<input type="checkbox" name="empf[]" value="'.$m['acctid'].'"> '.$m['name'].' `n'; //Mitgliederauswahl
			}

			output('Das Möbelstück wird so Aussehen:
			`n`nName:'.utf8_htmlspecialsimple($name).'`0
			`nBeschreibung:'.utf8_htmlspecialsimple($desc).'`0
			`nAn wen sollen die Unikate geliefert werden?
			`n`n'.
			form_header('dg_main.php?op=in&subop=unikatefertig&gems='.$_GET['gems'],'POST',true,'','var count=0;for(i=0;i<document.getElementsByName(\'empf[]\').length;i++){if(document.getElementsByName(\'empf[]\')[i].checked==true) {count++;}} return confirm(\'Dieses Möbelstück an \'+count+\' Empfänger verschicken kostet Dich \'+count*10+\' GP. Bist du damit einverstanden?\')').
			generateform(
			array(
			'gid'    => ',hidden',
			'name'    => ',hidden',
			'desc'    => ',hidden',
			'list'    => ',html',
			'count'    =>    ',hidden'
			),
			array(
			'gid'    => $gid,
			'name'    => utf8_htmlentities(str_replace(array('`','³','²'),array('``','³³','²²'),$name)),
			'desc'    => utf8_htmlentities(str_replace(array('`','³','²'),array('``','³³','²²'),$desc)),
			'list'    => $str_list,
			'count'    => 0
			),
			false,
			'Möbelstück auf die Reise schicken'
			)
			.form_footer());

			addnav('Zurück');
			addnav('Zurück zu Einzigartiges Möbelstück','dg_main.php?op=in&subop=unikate');
			addnav('Zurück zur Halle','dg_main.php?op=in');
			addnav('Zurück zu Mitglieder und Ränge','dg_main.php?op=in&subop=members');
			break;
		case 'unikatefertig':    //unikateversand
			$empf=$_POST['empf'];
			$gemcost=$_GET['gems']*count($empf);
			$gp_cost = count($empf)*10;
			if($guild['points'] >= $gp_cost && $gemcost <= $guild['gems']){
				$guild['points'] -= $gp_cost;  //Abheben der GP
				if ($_GET['gems']){
					$guild['gems'] -= $gemcost;
					$gem_text = ' und '.$gemcost.' ES';
				}
				dg_save_guild();
				$item['tpl_name'] = utf8_htmlspecialsimple(utf8_html_entity_decode($_POST['name'])).'`0';
				$item['tpl_description'] = utf8_htmlspecialsimple(utf8_html_entity_decode($_POST['desc'])).'`0';
				$item['tpl_gold'] = 0;
				$item['tpl_gems'] = 10;

				for($i=0; $i<count($empf); $i++){
					item_add($empf[$i] , 'unikat' , $item );//itemvergabe mit item_add
					debuglog('Gilde '.$gid.' gab 10 GP'.$_GET['gems'].' für Specialitem '.$_POST['name'].' für '.$empf[$i].' aus.');
					systemmail($empf[$i],'`2Ein Geschenk!',' Deine Gilde hat dir ein Unikat namens '.$_POST['name'].'`0 geschenkt.');
				}

				output('Das kleine Männchen winkt dir nur schnell mit der fertigen Liste zum Abschied zu und verschwindet wieder in seinem Hinterzimmer.<br>');
				addnav('Einzigartiges Möbelstück','dg_main.php?op=in&subop=unikate');
				addnav('Zurück zur Halle','dg_main.php?op=in');
				addnav('Zurück zu Mitglieder und Ränge','dg_main.php?op=in&subop=members');
			}
			else{
				$fehl = $_POST['cost'] - $guild['points'];
				if($fehl<0)
				{
					$fehl='keine';
				}
				if($gemcost > $guild['gems'])
				{
					$ges= $gemcost - $guild['gems'];
					$fehlg='und '.$ges.' Edelsteine';
				}
				output('Das kleine Männchen schaut auf eine Liste und meint:" Deine Gilde kann mich ja nichtmal bezahlen, kommt wieder wenn ihr genug gesammelt habt! ');
				output('Euch fehlen '.$fehl.' Gildenpunkte'.$fehlg.'.');
				addnav('Zurück zur Halle','dg_main.php?op=in');
			}

			break;
			//Eingefügte Unikatefunktion Ende
		case 'board':

			dg_show_header('Schwarzes Brett');

			output('`8Deine Gilde besitzt selbstverständlich auch ein schwarzes Brett. An diesem verkündet die Gildenführung wichtige Neuigkeiten, die nicht im allgemeinen Trubel untergehen sollen.`n`n');

			require_once(LIB_PATH.'board.lib.php');

			if($team) {

				board_view_form('Aufhängen','`8Hier kannst Du als Führungsmitglied eine Nachricht hinterlassen:');
				if($_GET['board_action'] == "add") {
					board_add('guild-'.$gid);
					redirect("dg_main.php?op=in&subop=board");
				}
			}

			$del = ($team) ? 2 : 1;
			board_view('guild-'.$gid,$del);

			addnav('Zur Halle','dg_main.php?op=in');

			break;


		case 'own_text':

			$own_text_max =  getsetting('guild_own_description_maxlength',1000);

			if($_GET['act'] == 'save') {

				$guild['guild_own_description'] = mb_substr($_POST['guild_own_description'],0,$own_text_max);
				$guild['guild_own_description'] = strip_tags($guild['guild_own_description']);

				dg_save_guild();

				redirect('dg_main.php?op=in&subop=own_text');

			}

			dg_show_header('Verfasse einen eigenen Text der in der Gildenhalle als Beschreibung erscheint');

			if($leader) {
				$str_lnk = 'dg_main.php?op=in&subop=own_text&act=save';
				addnav('',$str_lnk);

				$arr_data = array('guild_own_description'=>$guild['guild_own_description']);
				$arr_form = array('guild_own_description'=>'Aktueller Text in der Gildenhalle,textarea,40,20');

				output('<form action="'.$str_lnk.'" method="POST">',true);

				showform($arr_form,$arr_data,false,'Beschreiben!');

				output('</form>',true);

			}

			output('`8Aktueller Text in der Gildenhalle: `n'.strip_tags($guild['guild_own_description']));

			addnav('Zur Halle','dg_main.php?op=in');

			break;



		case 'bio':

			$biomax = getsetting('dgbiomax',2048);

			if($_GET['act'] == 'save') {

				$guild['bio'] = strip_tags(mb_substr($_POST['bio'],0,$biomax));
				$guild['rules'] = strip_tags(mb_substr($_POST['rules'],0,$biomax));

				dg_save_guild();

				redirect('dg_main.php?op=in&subop=bio');

			}

			dg_show_header('Profil der Gilde');

			if($leader) {
				$str_lnk = 'dg_main.php?op=in&subop=bio&act=save';
				addnav('',$str_lnk);

				$arr_data = array('bio'=>$guild['bio'],
				'rules'=>$guild['rules']);
				$arr_form = array('bio'=>'Aktuelle Biographie der Gilde,textarea,40,20',
				'rules'=>'Aktuelle Regeln der Gilde,textarea,40,20');

				output('<form action="'.$str_lnk.'" method="POST">',true);

				showform($arr_form,$arr_data,false,'Niederschreiben!');

				output('</form>',true);

			}

			output('`8Aktuelle Bio: `n'.strip_tags($guild['bio']));

			output('`n`n`8Aktuelle Regeln dieser Gilde: `n'.strip_tags($guild['rules'].'`n`n'));

			addnav('Zur Halle','dg_main.php?op=in');

			break;

		case 'hitlist':

			dg_show_header('Kopfgeldliste der Gilde');

			if($_GET['act'] == 'del') {
				dg_hitlist_remove($gid,(int)$_GET['acctid'],false);
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=hitlist');
			}
			elseif($_GET['act'] == 'add') {

				$min_dks = getsetting('dgmindkapply',3);

				if(mb_strlen($_POST['search']) > 0) {

					$ids = $gid.'';
					if(is_array($guild['treaties'])) {
						foreach($guild['treaties'] as $guildid=>$t) {
							if(dg_get_treaty($t) == 1) {
								$ids .= ','.$guildid;
							}
						}
					}

					$search = str_create_search_string($_POST['search']);

					$sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$search.'" AND acctid!='.$session['user']['acctid'].' AND guildid NOT IN ('.$ids.') AND dragonkills >= '.$min_dks;
					$res = db_query($sql);

					if(db_num_rows($res) == 0) {
						output('`8Kein mögliches Ziel mit diesem Namen gefunden!');
					}
					else {
						output('`8Diese Helden treffen auf deine Suche zu:`n`n');

						$link = 'dg_main.php?op=in&subop=hitlist&act=add';

						output('<form action="'.$link.'" method="POST">',true);

						output(' <select name="ziel">',true);

						while ( $p = db_fetch_assoc($res) ) {

							output('<option value="'.$p['acctid'].'">'.strip_appoencode($p['name'],3).'</option>',true);

						}

						output('</select>`n`n',true);

						output('<input type="submit" class="button" value="Hinzufügen"></form>',true);
						addnav('',$link);
					}
					addnav('Neue Suche','dg_main.php?op=in&subop=hitlist&act=add');
				}    // END if search

				elseif($_POST['ziel']) {

					$ziel = ($_POST['ziel'] ? (int)$_POST['ziel'] : (int)$_GET['ziel']);

					$sql = 'SELECT name,level FROM accounts WHERE acctid='.$ziel;
					$res = db_query($sql);
					$acc = db_fetch_assoc($res);

					$max_bounty = $acc['level'] * 500;
					$min_bounty = $acc['level'] * 100;

					if($_POST['bounty']) {
						$bounty = (int)$_POST['bounty'];

						$bounty = min($bounty,$max_bounty);
						$bounty = max($bounty,$min_bounty);

						$pay = round($bounty * 1.1);

						if($guild['gold'] < $pay) {
							output('`8Deine Gilde verfügt nicht über die geforderten `^'.$pay.'`8 Gold!');
							addnav('Zurück','dg_main.php?op=in&subop=hitlist&act=add&ziel='.$ziel);
						}
						else {
							dg_hitlist_add($gid,$ziel,$bounty);
							dg_save_guild();
							redirect('dg_main.php?op=in&subop=hitlist');
						}
					}
					else {

						output('`8Kopfgeld auf '.$acc['name'].'`8 aussetzen (Mindestens '.$min_bounty.' Gold, maximal '.$max_bounty.', 10% Gebühr!) :`n`n');

						$link = 'dg_main.php?op=in&subop=hitlist&act=add';

						output('<form action="'.$link.'" method="POST">',true);

						output('Kopfgeld: <input type="text" maxlength="4" name="bounty"> Gold ',true);

						output('<input type="hidden" name="ziel" value="'.$ziel.'"><input type="submit" class="button" value="Hinzufügen"></form>',true);
						addnav('',$link);
					}

				}    // END if ziel
				else {
					$link = 'dg_main.php?op=in&subop=hitlist&act=add';
					output('`8Du kannst nur auf diejenigen Helden ein Kopfgeld aussetzen, die weder in deiner noch in einer befreundeten Gilde Mitglied sind. Weiterhin müssen sie mindestens '.$min_dks.' Heldentat' . ($min_dks>1?'en':'') . ' besitzen!`nGenerell gilt: 10% Gebühr, Kopfgeld maximal das 800fache des Levels und minimal das 100fache.`n`n');

					output('<form action="'.$link.'" method="POST">',true);

					output('Name: <input type="input" name="search">',true);

					output('`n`n',true);

					output('<input type="submit" class="button" value="Suchen"></form>',true);
					addnav('',$link);
				}
				addnav('Zurück','dg_main.php?op=in&subop=hitlist');
			}    // END add
			else {

				$link = 'dg_main.php?op=in&subop=hitlist&act=add';
				if($leader || $war) {
					addnav('Neuer Auftrag',$link);
				}

				dg_show_hitlist($gid,($leader || $war ? true : false));
				addnav('Zurück','dg_main.php?op=in&subop=war');

			}

			break;

		case 'war':

			// Upgrade-Preise
			$int_def_upgr_price = ($guild['def_upgrade'] + 1) * 20;
			$int_atk_upgr_price = ($guild['atk_upgrade'] + 1) * 20;
			// Waffen- u. Rüstungsbezeichnung
			$str_weapon = $guild['atk_upgrade']>0 ? ' mit der Waffe '.$arr_dg_weaponnames[ $guild['atk_upgrade'] ] : '';
			$str_armor = $guild['def_upgrade']>0 ? ' mit der Rüstung '.$arr_dg_armornames[ $guild['def_upgrade'] ] : '';

			if($team) {addcommentary();}

			dg_show_header('Krieg & Diplomatie');

			if($_GET['act'] == 'cancel') {

				$guild['war_target'] = 0;

				//dg_commentary($gid,': `4erklärt den Angriff für beendet.','');
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=war');
			}
			elseif($_GET['act'] == 'upgrade') {

				$str_what = ($_GET['what'] == 'atk' ? 'atk' : 'def');
				$int_price = ($str_what == 'atk' ? $int_atk_upgr_price : $int_def_upgr_price);

				if($_GET['ok']) {
					$guild[$str_what.'_upgrade']++;
					$guild['points'] -= $int_price;

					dg_commentary($gid,':`@ verstärkt die Gildenwachen mit '.($str_what == 'atk' ? 'neuen Waffen' : 'neuen Rüstungen').'!','war');

					dg_save_guild();
					redirect('dg_main.php?op=in&subop=war');
				}
				else {    // Sicherheitsabfrage
					addnav('Zurück zur Kriegskammer','dg_main.php?op=in&subop=war');

					$str_lnk = 'dg_main.php?op=in&subop=war&act=upgrade&ok=1&what='.$str_what;

					if($guild['points'] >= $int_price) {
						output( create_lnk('Aufrüsten!',$str_lnk), true );
					}
					else {    // Zu wenig Punkte
						output('Zu wenig Punkte');
					}

				}
			}
			elseif($_GET['act'] == 'downgrade') {

				$str_what = ($_GET['what'] == 'atk' ? 'atk' : 'def');

				if($_GET['ok']) {
					$guild[$str_what.'_upgrade'] = 0;

					dg_commentary($gid,':`# nimmt den Gildenwachen sämtliche verbesserten '.($str_what == 'atk' ? 'Waffen' : 'Rüstungen').' ab!','war');

					dg_save_guild();
					redirect('dg_main.php?op=in&subop=war');
				}
				else {    // Sicherheitsabfrage
					addnav('Zurück zur Kriegskammer','dg_main.php?op=in&subop=war');

					$str_lnk = 'dg_main.php?op=in&subop=war&act=downgrade&ok=1&what='.$str_what;

					output( create_lnk('Abrüsten!',$str_lnk), true );

				}

			}
			elseif($_GET['act'] == 'start') {

				$enemy = &dg_load_guild($_GET['target'],array('name','fights_suffered','regalia','guard_hp','atk_upgrade','def_upgrade'));

				if($_GET['ok']) {

					$guild['war_target'] = (int)$_GET['target'];

					$guild['points'] -= $dg_points['war_cost'];
					$guild['points_spent'] += $dg_points['war_cost'];

					// Ansehensverlust
					$int_repuloose = 0;
					$arr_infl = dg_calc_strength(array($gid,$_GET['target']));

					$int_diff = round($arr_infl[$gid] - $arr_infl[$_GET['target']]);

					if($int_diff < 5) {
						$int_repuloose = 4;
					}
					else if($int_diff >= 5 && $int_diff < 10) {
							$int_repuloose = 6;
						}
						else if($int_diff >= 10 && $int_diff < 20) {
								$int_repuloose = 10;
							}
							else if($int_diff >= 20 && $int_diff < 30) {
									$int_repuloose = 16;
								}
								else {
									$int_repuloose = 22;
					}

					dg_log('Ansehensverlust von '.$int_repuloose.' wg. Angriff auf ',$gid,$_GET['target']);

					$guild['reputation'] = max($guild['reputation']-$int_repuloose,0);
					// END Ansehensverlust

					$guild['immune_days'] = 0;

					//dg_commentary($gid,': `4gibt das Signal zum Angriff auf '.$enemy['name'].'`4. Auf sie!','');
					dg_save_guild();
					redirect('dg_main.php?op=in&subop=war');

				}

				if($guild['points'] < $dg_points['war_cost']) {
					output('Die Gilde besitzt nicht die benötigten '.$dg_points['war_cost'].' Punkte, um einen Krieg bezahlen zu können!`n`n');
				}
				else {

					if($enemy['guard_hp'] <= 0) {
						output('`8`bDeine Gilde will sich doch wohl nicht die Hände an solchen Schwächlingen schmutzig machen? Diese Gilde anzugreifen wäre nun wirklich unter ihrer Würde!`b`n`n');
					}
					elseif($enemy['fights_suffered'] > getsetting('dgfightssuf',2)) {
						output('`8`bDiese Gilde wurde heute bereits '.$enemy['fights_suffered'].' mal angegriffen. Da ist bestimmt nichts mehr zu holen!`b`n`n');
					}
					elseif($enemy['fights_suffered_period'] > getsetting('dgfightssufperiod',2)) {
						output('`8`bDiese Gilde wurde in der Vergangenheit bereits zu oft angegriffen. Da ist bestimmt nichts mehr zu holen!`b`n`n');
					}
					else {
						if($guild['atk_upgrade']) {
							$str_equipment_own = ', ausgerüstet mit '.$arr_dg_weaponnames[$guild['atk_upgrade']].', ';
						}
						else {
							$str_equipment_own = '';
						}
						if($enemy['def_upgrade']) {
							$str_equipment_enemy = 'Es scheint, als wären die feindlichen Krieger mit '.$arr_dg_armornames[$enemy['def_upgrade']].' ausgerüstet!';
						}
						else {
							$str_equipment_enemy = '';
						}

						output('`8Ein Bote kehrt von einem Erkundungsgang zurück und berichtet dir:`n
						`^"Die Tore der feindlichen Gilde werden von '.$enemy['guard_hp'].' gut bewaffneten
						Kriegern bewacht. '.$str_equipment_enemy.'`n
						Dem stehen '.$guild['guard_hp'].' Mann'.$str_equipment_own.' auf unserer Seite gegenüber.`n
						Seid ihr euch wirklich sicher, den Angriff befehlen zu wollen?`n`n
						Zunächst könnten die Mitglieder unserer Gilde gegen den Feind vorrücken,
						ehe ihr den Ansturm unserer Truppen anordnet!`n
						Doch beachtet, dass wir unseren Angriff bis spätestens Mitternacht dieses
						Tages abgeschlossen haben sollten, andernfalls verfällt er.."`8');

						addnav('Ja, zeigen wir es ihnen!','dg_main.php?op=in&subop=war&act=start&ok=1&target='.$_GET['target']);

					}

				}

				addnav('Zurück zum Kriegszimmer','dg_main.php?op=in&subop=war');

			}
			else {
				output('`8Du betrittst einen Raum, der vordergründig der Kriegsführung gewidmet ist. Nicht umsonst ist die Wand mit martialischen Symbolen und Gegenständen geschmückt. Die Mitte des Raumes wird vom Strategietisch eingenommen, auf dem der nächste Feldzug geplant wird:`n`n');

				if($guild['guildwar_allowed']==0) {
					output('Die Gilde besitzt nicht das Recht, Gildenkriege zu führen!`n`n');
				}

				if($guild['immune_days']>0) {
					output('Die Gilde ist noch für `b'.$guild['immune_days'].'`b Tage vor Gildenkriegen geschützt, so lange sie nicht selbst einen Angriff beginnt!`n`n');
				}

				// WAFFEN + RÜSTUNGS - Update
				if($team) {
					if($guild['atk_upgrade'] > 0) {
						addnav('Alle Waffenverbesserungen zurücknehmen!','dg_main.php?op=in&subop=war&act=downgrade&what=atk');
					}
					else {
						if($guild['def_upgrade'] < 3) {

							addnav('Gildenwachen-Rüstung verbessern ('.$int_def_upgr_price.' GP)!','dg_main.php?op=in&subop=war&act=upgrade&what=def');

						}
					}
					if($guild['def_upgrade'] > 0) {
						addnav('Alle Rüstungsverbesserungen zurücknehmen!','dg_main.php?op=in&subop=war&act=downgrade&what=def');
					}
					else {
						if($guild['atk_upgrade'] < 3) {

							addnav('Gildenwachen-Waffen verbessern ('.$int_atk_upgr_price.' GP)!','dg_main.php?op=in&subop=war&act=upgrade&what=atk');

						}
					}
				}
				// END Upgrade

				output('Krieger in der Kaserne: `b'.$guild['guard_hp'].$str_weapon.$str_armor.'!`b`n`n');

				viewcommentary('guild-'.$gid.'_war',($team ? 'Etwas verkünden:':'Du solltest hier besser schweigen!'),25,'verkündet',false,($team?true:false),false,false,true,true,2);

				// Infos über aktuell laufenden Krieg abrufen
				if($guild['war_target']) {

					$enemy_guild = &dg_load_guild($guild['war_target'],array('name','state','guard_hp','build_list','type'));

					if($enemy_guild['state'] == DG_STATE_ACTIVE && $guild['guildwar_allowed']) {

						output('`n`n`bIm Krieg mit: '.$enemy_guild['name'].'`b`n',true);

						addnav('Krieg');

						if($war || $leader) {addnav('Krieg beenden','dg_main.php?op=in&subop=war&act=cancel');}

						if($enemy_guild['guard_hp'] <= 0) {
							addnav($enemy_guild['name'].' plündern!','dg_battle.php?battle_op=attack3');
							output('`8`n`bDer Weg ist frei!`b`n');
						}
						else {
							$rowe = user_get_aei('guildfights');
							if($rowe['guildfights'] == 0) {
								addnav($enemy_guild['name'].' angreifen!','dg_battle.php?battle_op=attack1');
							}
							if($war || $leader) {
								addnav('Den Truppen Angriff auf '.$enemy_guild['name'].' befehlen!','dg_battle.php?battle_op=attack2');
							}
							output('`8Zur Zeit stehen unseren Truppen `^'.$enemy_guild['guard_hp'].'`8 Mann gegenüber.');
						}
					}

				}

				addnav('Aktionen');
				addnav('Verträge','dg_main.php?op=in&subop=treaties');
				addnav('Kopfgeldliste','dg_main.php?op=in&subop=hitlist');
				addnav('Zur Halle','dg_main.php?op=in');
			}

			break;

		case 'treaties':
			if($_GET['target']) {
				$enemy = &dg_load_guild($_GET['target'],array('name','treaties','war_target'));
			}
			if($_GET['act'] == 'peace') {    // Friedensangebot

				dg_set_treaty($gid,$_GET['target'],DG_TREATY_PEACE_SELF);
				output('gemacht');
				dg_massmail($_GET['target'],'`8Friedensangebot',$guild['name'].'`8 hat deiner Gilde ein Friedensangebot unterbreitet!',200);
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treaties');

			}
			elseif($_GET['act'] == 'accept_peace') {    // Friedensangebot akzeptieren

				dg_set_treaty($gid,$_GET['target'],DG_TREATY_PEACE_OTHER,false);
				output('angenommen');
				dg_commentary($gid,': `@verkündet, dass diese Gilde einen Friedensvertrag mit '.$enemy['name'].'`@ geschlossen hat!','');
				dg_massmail($_GET['target'],'`8Friedensvertrag akzeptiert!',$guild['name'].'`8 hat das Friedensangebot deiner Gilde angenommen!',200);
				dg_save_guild();
				$newsmsg = '`2Die Gilde '.$guild['name'].'`2 schließt mit '.$enemy['name'].'`2 einen Friedensvertrag ab.';
				dg_addnews($newsmsg);
				addhistory($newsmsg,2,$guild['guildid']);
				addhistory($newsmsg,2,$enemy['guildid']);
				redirect('dg_main.php?op=in&subop=treaties');

			}
			elseif($_GET['act'] == 'neutral') {    // Neutral

				dg_set_treaty($gid,$_GET['target'],0,false);
				output('angenommen');
				dg_massmail($_GET['target'],'`8Neutral!',$guild['name'].'`8 verhält sich von nun an neutral zu deiner Gilde!',200);
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treaties');

			}
			elseif($_GET['act'] == 'refuse_peace') {    // Friedensangebot zurückweisen

				dg_set_treaty($gid,$_GET['target'],0,false);
				output('abgelehnt');
				dg_massmail($_GET['target'],'`8Friedensvertrag zurückgewiesen!',$guild['name'].'`8 hat das Friedensangebot deiner Gilde zurückgewiesen!',200);
				dg_save_guild();
				redirect('dg_main.php?op=in&subop=treaties');

			}
			elseif($_GET['act'] == 'war') {    // Kriegserklärung

				dg_set_treaty($gid,$_GET['target'],DG_TREATY_WAR_SELF,false);
				dg_commentary($gid,': `4verkündet, dass diese Gilde sich ab sofort im Krieg mit '.$enemy['name'].'`4 befindet!','');
				dg_massmail($_GET['target'],'`$Kriegserklärung!',$guild['name'].'`8 hat deiner Gilde den Krieg erklärt!',200);
				dg_save_guild();

				$newsmsg = '`$Die Gilde '.$guild['name'].'`$ erklärt '.$enemy['name'].'`$ den Krieg.';
				dg_addnews($newsmsg);
				addhistory($newsmsg,2,$guild['guildid']);
				addhistory($newsmsg,2,$enemy['guildid']);

				redirect('dg_main.php?op=in&subop=treaties');

			}

			dg_show_header('Verträge');
			$diplo = ($leader || $war) ? 3 : 1;
			dg_show_guild_list(0,false,'name ASC',true, $diplo);

			addnav('Zurück','dg_main.php?op=in&subop=war');

			break;

		case 'guild_talk':

			addcommentary();
			$target = (int)$_GET['target'];
			$section = 'guild-'.(($target > $gid) ? $target.'_'.$gid : $gid.'_'.$target).'-talk';

			$other_g = &dg_load_guild($target,array('name'));

			dg_show_header('Gildengespräch');

			output('`8In einem vornehmen Verhandlungsraum treffen sich Abgesandte der Gilden '.$other_g['name'].'`8 und '.$guild['name'].'`8, um über gemeinsame Aktionen und die Basis ihrer Freundschaft zu diskutieren:`n`n');

			viewcommentary($section,'Mit Abgesandten der anderen Gilde sprechen:',25,'spricht');

			addnav('Zu den Verträgen','dg_main.php?op=in&subop=treaties');
			addnav('Zur Halle','dg_main.php?op=in');

			break;

		case 'buildings':

			// AUSBAUTEN
			include_once('dg_builds.php');
			// END AUSBAUTEN

			break;

	}    // END subop in

	break;    // END in

}    // END main switch

// jegliche Veränderung speichern
dg_save_guild();

if($access_control->su_check(access_control::SU_RIGHT_EDITORGUILDS)) {
	addnav('Admin');
	addnav('X?Zum Gildeneditor','su_guilds.php');

	if($access_control->su_check(access_control::SU_RIGHT_DEV)) {

		//addnav('König rufen!','su_guilds.php?op=callking');

	}
}

page_footer();
?>
