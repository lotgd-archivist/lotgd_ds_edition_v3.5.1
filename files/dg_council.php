<?php
/*-------------------------------/
Name: dg_council.php
Autor: tcb / talion für Drachenserver (mail: t@ssilo.de)
Erstellungsdatum: 6/05 - 9/05
Beschreibung:	Stellt alle anfallenden Bildschirme des Gildenrats dar (Abstimmung etc.)
				Außerdem: Gildenliste, Ruhmeshalle, Bewerbung, Gründung
				Diese Datei ist Bestandteil des Drachenserver-Gildenmods (DG). 
				Copyright-Box muss intakt bleiben, bei Verwendung Mail an Autor mit Serveradresse.
/*-------------------------------*/

require_once('common.php');
require_once(LIB_PATH.'dg_funcs.lib.php');
require_once('dg_output.php');

checkday();
page_header('Der Gildenrat');

if($session['user']['guildid']) {
	$leader = ($session['user']['guildfunc'] == DG_FUNC_LEADER) ? true : false;
	$treasure = ($session['user']['guildfunc'] == DG_FUNC_LEADER || $session['user']['guildfunc'] == DG_FUNC_TREASURE) ? true : false;
	$war = ($session['user']['guildfunc'] == DG_FUNC_LEADER || $session['user']['guildfunc'] == DG_FUNC_WAR) ? true : false;
	$members = ($session['user']['guildfunc'] == DG_FUNC_LEADER || $session['user']['guildfunc'] == DG_FUNC_MEMBERS) ? true : false;
	$team = ($leader || $treasure || $war || $members) ? true : false;
	$member = ($session['user']['guildfunc'] != DG_FUNC_APPLICANT && $session['user']['guildfunc']) ? true : false;
	$applicant = ($session['user']['guildfunc'] == DG_FUNC_APPLICANT) ? true : false;
	
	if($member) {$gid=$session['user']['guildid'];$guild = &dg_load_guild($gid);}
}

$op = ($_GET['op']) ? $_GET['op'] : '';
$out = '';

switch($op) {
	
	case 'list':
		
		dg_show_header('`;L`Yi`ts`yt`&e der Gi`yl`td`Ye`;n');
		
		output('`c');dg_show_guild_list(0,true);output('`c');
		
		if($member) {addnav($session['userguild']['name'].'`0 betreten','dg_main.php?op=in&gid='.$session['user']['guildid']);}
		else {
			if(!$applicant) {
				addnav('Gilde gründen','dg_council.php?op=found');
			}
		}
		
		addnav('Zurück');
        addnav('Zum Gildenviertel','dg_main.php');
	
		break;
	
	case 'paladin':
		
		dg_show_header('`;P`Ya`tl`ya`&dinfes`yt`tu`Yn`;g');
		
		if(!$member) {output('`&Du willst gerade die Feste der Paladine betreten, als man dich schroff zurückweist: `&"Zutritt nur für Gildenangehörige!"`n');}	
		else {
			
			// Preis für die Dinger bestimmen
			$regalia_left = getsetting('dgregalialeft',10);
			$guild_count = dg_count_guilds();
			$member_count = dg_count_guild_members($gid);
			
			$percent = 30;
			
			if($guild['reputation'] < 30) {$percent += 5;}
			elseif($guild['reputation'] < 50) {$percent += 3;}
			elseif($guild['reputation'] < 70) {$percent += 2;}
			elseif($guild['reputation'] < 90) {$percent += 1;}
			
			$percent += ($member_count * 3);
			
			if($regalia_left < $guild_count*0.25 ) {$percent += 10;}
			elseif($regalia_left < $guild_count*0.5) {$percent += 5;}
			elseif($regalia_left < $guild_count*0.75) {$percent += 2;}
			
			$percent += ($guild['regalia'] * 5);
			
			$percent = min($percent * 0.01,1);
			
			$regalia_price_gold = round(getsetting('dgtrsmaxgold',500000) * $percent);
			$regalia_price_gems = round(getsetting('dgtrsmaxgems',1000) * $percent);
			
			$bribe_price_points = 10;
			// END Preisbestimmung
			
			
			if($_GET['subop'] == 'buy_regalia') {
			
				if($_GET['act'] == 'ok') {
					$guild['regalia']++;
					$guild['gold'] -= $regalia_price_gold;
					$guild['gems'] -= $regalia_price_gems;
					dg_log('Erwirbt eine Insignie für '.$regalia_price_gold.' Gold und '.$regalia_price_gems.' Gems');
					savesetting('dgregalialeft',$regalia_left-1);
					dg_save_guild();
					redirect('dg_council.php?op=paladin&subop=buy_regalia&act=bought');
				}
				elseif($_GET['act'] == 'bought') {
					output('`&Ein kräftiger Paladin packt eine Insignie, wickelt sie sorgfältig in Stoff ein und meint dann zu dir: `&"Ich gratuliere euch, Meister '.$session['user']['login'].', eure Gilde hat eine gute Wahl getroffen! Wir werden das Stück demnächst liefern."');
					addnav('Zum Lager der Paladine','dg_council.php?op=paladin');
				}
				else {
					if($guild['gold'] < $regalia_price_gold || $guild['gems'] < $regalia_price_gems) {
						output('`&Als du dir die Preise nochmal genauer betrachtest, stellst du fest, dass sie die finanziellen Mittel deiner Gilde übersteigen. Schade..');
					}
					else {
						output('`&Grübelnd stehst du vor dem Lager. Willst du für deine Gilde wirklich eine Insignie erwerben?');
						addnav('Ja','dg_council.php?op=paladin&subop=buy_regalia&act=ok');
					}
					addnav('Zum Lager der Paladine','dg_council.php?op=paladin');
				}
				
			}
			
			elseif($_GET['subop'] == 'bribe_king') {
				
				if($_GET['act'] == 'ok') {
				
					if(e_rand(1,2) == 1) {
						$guild['reputation'] = min($guild['reputation']+2,100);
						$guild['points'] -= $bribe_price_points;
						$guild['points_spent'] += $bribe_price_points;
						dg_save_guild();
						redirect('dg_council.php?op=paladin&subop=bribe_king&act=bribed');
					}
					else {
						output('`&Dich gemein anlächelnd klopft dir der Paladin auf die Schulter. `&"Vielen Dank, aber ich habs mir anders überlegt.."');
						addnav('Zum Lager der Paladine','dg_council.php?op=paladin');
					}
	
				}
				elseif($_GET['act'] == 'bribed') {
					output('`&Die Wache sieht wieder starr geradeaus. `Y"Ich werde sehen, was sich tun lässt.."');
					addnav('Zum Lager der Paladine','dg_council.php?op=paladin');
				}
				else {
					output('`&Verstohlen näherst du dich einer scheinbar höhergestellten Wache und raunst ihr ein Angebot ins Ohr. Nachdenklich wendet der Paladin sich dir zu, seine Miene verheißt nichts Gutes. Du willst schon davonlaufen, als er dir grinsend zuflüstert: `Y"'.$bribe_price_points.' Punkte, mein Freund.."`&`n');
					addnav('Zum Lager der Paladine','dg_council.php?op=paladin');
					if($guild['points'] < $bribe_price_points) {
						output('Schade.. So viele Punkte besitzt deine Gilde nicht. Du machst auf dem Absatz kehrt und verschwindest.');
					}
					else {
						output('Zögernd überlegst du, auf das Angebot einzugehen...');
						addnav('Bestechen..','dg_council.php?op=paladin&subop=bribe_king&act=ok');
					}
				}
	
			}
			
			else if($_GET['subop'] == 'ask_mood') {
				
				$king_mood = getsetting('dgkingmood',50);
				
				output('`&Du näherst dich der Wache und grüßt sie ehrerbietig. Auf deine Frage, welcher Art denn die Stimmung des Königs zur Zeit sei, ');
				
				if($king_mood > 90) {
					output('nickt dir der Paladin freundlich zu: `Y"Ausgezeichnet, mein Freund, ausgezeichnet!"');
				}
				else if($king_mood > 70) {
					output('sieht dich der Paladin kurz an und meint: `Y"Ihre Majestät pflegt gute Beziehungen zu den Gilden '.getsetting('townname','Atrahor').'s!"');
				}
				else if($king_mood > 50) {
					output('grübelt der Paladin: `Y"Nun, Ihre Majestät ist mit den Gilden '.getsetting('townname','Atrahor').'s leidlich zufrieden. Sicherlich könnte es besser sein!"');
				}
				else if($king_mood > 30) {
					output('sieht dich der Paladin mitleidig an und lacht: `&"Eisig, um es so zu sagen!"');
				}
				else {
					output('blickt der Paladin starr geradeaus. Als du deine Frage wiederholst, knurrt er: `&"Ihre Majestät ist äußerst unzufrieden mit den Gilden '.getsetting('townname','Atrahor').'s! Gebt gut auf eure Insignien Acht.."');
				}
				
				addnav('Zum Lager der Paladine','dg_council.php?op=paladin');
				
			}
			else {
				
				output('`;A`Yn `td`yi`&e das Gildenviertel umgrenzende Mauer schmiegt sich die Feste der Paladine. Sie wachen im Auftrag des Königs über die Gilden, verwalten die Insignien und repräsentieren den Herrscher.`n`nÜberall stehen wohlgerüstete Krieger herum und erfüllen ihre jeweilige Aufgabe. Von ihnen weiß sicherlich auch einer über die Stimmung des Königs Bescheid. Vielleicht sind einige auch bereit, bei diesem ein gutes Wort einzul`ye`tg`Ye`;n..`n`n');
				
				addnav('Nach Stimmung des Königs fragen','dg_council.php?op=paladin&subop=ask_mood');
				
				addnav('Paladine bestechen','dg_council.php?op=paladin&subop=bribe_king');
				
				if($regalia_left) {
					output('`&Eine Tafel vor dem hohen Lagertor verkündet, dass noch `^'.$regalia_left.'`& Insignien vorhanden sind. Diese kosten für deine Gilde `^'.$regalia_price_gold.'`& Gold und `^'.$regalia_price_gems.'`& Edelsteine.');
					if($leader) {addnav('Insignie kaufen','dg_council.php?op=paladin&subop=buy_regalia');}
				}
				else {
					output('`&Eine Tafel vor dem hohen Lagertor verkündet, dass bereits alle Insignien verkauft wurden! Deine Gilde wird wohl auf eine neue Lieferung warten müssen.');
				}
				
			}
		
		}	// END if member
		
		addnav('Zurück');
		addnav('Zum Gildenviertel','dg_main.php');
		
		break;
		
	case 'plead_king':
		
		$plead_price_turns = 3;
		$plead_price_gold = $session['user']['level'] * 100;
		
		if( $session['user']['guildid'] ) {
			$guild = &dg_load_guild($session['user']['guildid']);
		}
		
		if($_GET['act'] == 'try') {
		
			if($session['user']['gold'] < $plead_price_gold) {
			
			}
			else if($session['user']['turns'] < $plead_price_turns) {
			
			}
			else {
				if(e_rand(1,2) == 1) {
					$guild['reputation']++;
					$session['user']['gold'] -= $plead_price_gold;
					$session['user']['turns'] -= $plead_price_turns;
					output('');
				}
				else {
				
				}
			}
			
			addnav('Zurück');
			addnav('Zum Gildenviertel','dg_main.php');
		}
		
		break;
		
	case 'council':
		
		dg_show_header('`;D`Ye`tr `yR`&ats`ys`ta`Ya`;l');
		
		$council_days_left = 0;
		$vote_days_left = getsetting('dgvotedaysmax',170) - getsetting('dgvotedays',0);
		if($vote_days_left <= 0) {
			$council_days_left = $vote_days_left;
		}
		
		if($_GET['subop'] == 'vote') {
			$vote = (int)$_GET['vote'];
			
			if($_GET['act'] == 'ok') {
			
				$reputation = $vote - 100;
				$reputation = round($reputation*0.25);
				$reputation -= e_rand(0,2);
				
				$guild['reputation'] = max($guild['reputation']+$reputation,0);
				$guild['reputation'] = min($guild['reputation'],100);
			
				$guild['vote'] = $vote;
				
				dg_save_guild();
				
				redirect('dg_council.php?op=council');
			}
			else {
				addnav('Ja, Stimme abgeben!','dg_council.php?op=council&subop=vote&act=ok&vote='.$vote);
				addnav('Nein!','dg_council.php?op=council');
			}
			
		}
		elseif($_GET['voted']) {
			output('`&Du gibst die '.$guild['regalia'].' Stimmen deiner Gilde für einen Steuersatz von `^'.$_GET['voted'].' %`&!');
		}
		elseif($_GET['subop'] == 'results') {
		
			dg_load_guild(0,array('vote','regalia'));
			
			$votes = array();
			
			foreach($session['guilds'] as $g) {
				if($g['vote'] > 0) {
					$votes[ $g['vote'] ] += $g['regalia'];
				}
			}
			
			arsort($votes);
			reset($votes);
			
			output('`yBisheriger Stand:`n`n');
			
			foreach($votes as $v=>$r) {
				
				output('`b`^'.$v.' %:`b `&'.$r.' Stimmen`n');
				
			}
		
			addnav('Zum Ratssal','dg_council.php?op=council');
		}
		
		else {
		
			output('`;S`Yo`tf`yor`&t stechen dir am prachtvollen und beeindruckenden Gebäude des Gildenrats zwei eicherne Türflügel ins Auge. Hinter jenen, so erfährst du, beraten sich die Gilden über gemeinsame Unternehmungen und - wer weiß - vielleicht auch über die Zukunft der Stadt. Eine Tafel verkündet:`n
					'.($council_days_left || 1 ? '`n`b`yDer Gildenrat tagt gerade!`b`n' : '`n`yNoch `^'.$vote_days_left.'`y Tage bis zur Einberufung des nächsten Gildenrates.')
					//' Der derzeitige Steuersatz beträgt `^'.(getsetting('dgtaxmod',1)*100).'`8 %.`n`n'  
					);
			
			if(!$member) {
				output('`&Du stehst hier allerdings vor verschlossenen Türen, die sich nur für Angehörige einer der Gilden öffnen!');
			}
			else {
				addcommentary();
				
				output('`&Hufeisenförmig sind Tische angeordnet und mit gepolsterten Stühlen versehen. Dort nehmen die Führer der einzelnen Gilden Platz, wenn es um wichtige Beratungen geht. Am Rand sind ebenfalls Stühle aufgereiht, die für einfache Mitglieder und deren Zuschauerrolle gedacht sind. Sprechen dürfen hier nämlich nur die F`yüh`tr`Ye`;r.`n');
				
				/*if($council_days_left) {
					
					output('`nJetzt gerade herrscht hier rege Betriebsamkeit, Stimmengewirr durchzieht den Raum. 
					Offensichtlich wird hier noch für `^'.$council_days_left.'`8 Tage eine Abstimmung über den Steuersatz abgehalten.`n');
					
					addnav('Stand der Wahl','dg_council.php?op=council&subop=results');
					
					if($leader && $guild['vote'] == 0) {
						$link = 'dg_council.php?op=council&subop=vote&vote=';
						addnav('Abstimmung');
						addnav('50 % Steuersatz!',$link.'50');
						addnav('75 % Steuersatz!',$link.'75');
						addnav('100 % Steuersatz!',$link.'100');
						addnav('125 % Steuersatz!',$link.'125');
						addnav('150 % Steuersatz!',$link.'150');
					}
					
					output( ($guild['vote'] == 0 ? '`nDeine Gilde hat ihre `^'.$guild['regalia'].'`8 Stimmen noch nicht abgegeben!' : '`nDeine Gilde plädiert mit ihren `^'.$guild['regalia'].'`8 Stimmen für einen Steuersatz von `^'.$guild['vote'].' %`8!') );
				}*/
				output('`n');
				viewcommentary('guildcouncil',($team ? 'Etwas verkünden:':'Du solltest hier besser schweigen!'),25,'verkündet',false,($team?true:false));
				
			}
		}
		
		addnav('Zurück');
        addnav('Zum Gildenviertel','dg_main.php');
		
		break;
	
	case 'hof':
		$subop = ($_GET['subop']) ? $_GET['subop'] : 'gp';
		$order = ($_GET['order']=='asc') ? $_GET['order'] : 'DESC';
		
		addnav('Bestenlisten');
		addnav('Verkaufte Insignien','dg_council.php?op=hof&subop=regalia&order='.$order.'&page='.$page);
		addnav('Vorrätige Insignien','dg_council.php?op=hof&subop=regalia_recent&order='.$order.'&page='.$page);
		addnav('Gildenpunkte','dg_council.php?op=hof&subop=gp&order='.$order.'&page='.$page);
		addnav('Reichtum','dg_council.php?op=hof&subop=gold&order='.$order.'&page='.$page);
		addnav('Edelsteine','dg_council.php?op=hof&subop=gems&order='.$order.'&page='.$page);
		//addnav('Ausbau','dg_council.php?op=hof&subop=build&order='.$order.'&page='.$page);
		addnav('Stärke','dg_council.php?op=hof&subop=strength&order='.$order.'&page='.$page);		
		addnav('Mitglieder','dg_council.php?op=hof&subop=member&order='.$order.'&page='.$page);
		addnav('Steuerzahler','dg_council.php?op=hof&subop=tax&order='.$order.'&page='.$page);
		
		switch($subop) {
			
			case 'gp':
				
				dg_show_hof('Die Gilden mit den '.(($order=='asc')?'wenigsten':'meisten').' Gildenpunkten in dieser Stadt:`n',
							'SELECT guildid,name,points AS data1 FROM dg_guilds ORDER BY points '.$order.', name ASC',
							false,false,array('Gildenpunkte'),array('Punkte'));
				
				break;
			
			case 'regalia':
				
				dg_show_hof('Die '.(($order=='asc')?'geringsten':'größten').' Insignienlieferanten in dieser Stadt:`n',
							'SELECT guildid,name,regalia_sold AS data1 FROM dg_guilds ORDER BY regalia_sold '.$order.', name ASC',
							false,false,
							array('Insignien verkauft'));
				
				break;
			
			case 'regalia_recent':
				
				dg_show_hof('Die zur Zeit an Insignien '.(($order=='asc')?'ärmsten':'reichsten').' Gilden:`n',
							'SELECT guildid,name,regalia AS data1 FROM dg_guilds ORDER BY regalia '.$order.', name ASC',
							false,false,
							array('Insignien auf Lager'));
				
				break;
			
			case 'gold':
				
				dg_show_hof('Die '.(($order=='asc')?'ärmsten':'reichsten').' Gilden dieser Stadt:`n',
							'SELECT guildid,name,gold AS data1 FROM dg_guilds ORDER BY gold '.$order.', name ASC',
							false,false,
							array('Vermögen'),
							array('Gold'));
				
				break;
				
			case 'gems':
				
				dg_show_hof('Die an Edelsteinen '.(($order=='asc')?'ärmsten':'reichsten').' Gilden dieser Stadt:`n',
							'SELECT guildid,name FROM dg_guilds ORDER BY gems '.$order.', name ASC');
				
				break;
				
			case 'build':
				
				$res = db_query('SELECT guildid,build_list,name FROM dg_guilds');
				$guilds = array();
				$builds = array();
				while($g = db_fetch_assoc($res)) {
					$g['build_list'] = utf8_unserialize($g['build_list']);
					$builds[$g['guildid']]['data1'] = 0;
					foreach($g['build_list'] as $id=>$b) {
						if($id > 0) {$builds[$g['guildid']]['data1']+=$b;}
					}
					$guilds[$g['guildid']] = $g;
				}
				$builds = sort($builds);
				$guilds = array_merge($guilds,$builds);
				
				dg_show_hof('Die am '.(($order=='asc')?'wenigsten':'weitesten').' ausgebauten Gilden dieser Stadt:`n',
							$guilds);
				
				break;
			
			case 'strength':
				
				dg_show_hof('Die '.(($order=='asc')?'schwächsten':'stärksten').' Gilden in dieser Stadt:`n',
							'SELECT g.guildid,g.name,ROUND(AVG(a.dragonkills)) AS data1 FROM dg_guilds g,accounts a WHERE a.guildid=g.guildid AND a.guildfunc!='.DG_FUNC_APPLICANT.' GROUP BY g.guildid ORDER BY data1 '.$order.', name ASC',
							false,false,
							array('Durchschnitt an Heldentaten'),
							array('Heldentaten')
							);
				
				break;
				
			case 'member':
				
				dg_show_hof('Die Gilden mit den '.(($order=='asc')?'wenigsten':'meisten').' Mitgliedern in dieser Stadt:`n',
							'SELECT g.guildid,g.name,COUNT(acctid) AS data1 FROM dg_guilds g LEFT JOIN accounts a ON (a.guildid=g.guildid AND a.guildfunc!='.DG_FUNC_APPLICANT.') GROUP BY g.guildid ORDER BY data1 '.$order.', name ASC',
							false,false,
							array('Mitglieder')
							);
				
				break;
				
			case 'tax':
				
				dg_show_hof('Diese Gilden haben bisher am '.(($order=='asc')?'wenigsten':'meisten').' Steuern gezahlt:`n',
							'SELECT g.guildid,g.name,g.gold_tax AS data1, g.gems_tax AS data2 FROM dg_guilds g ORDER BY data1 '.$order.', data2 '.$order.', name ASC',
							false,'`cWeiter so!`c',
							array('Goldsumme','Gemmensumme'),
							array('Gold','Edelsteine')
							);
				
				break;
			}
		
		
		break;
		
	case 'found':
		
		$min_gold = getsetting('dgguildfoundgold',100000);
		$min_gems = getsetting('dgguildfoundgems',100);
		$min_dk = getsetting('dgguildfound_k',20);
		$max_guilds = getsetting('dgguildmax',100);
		
		$subop = ($_GET['subop']) ? $_GET['subop'] : '';
		
		dg_show_header('`;G`Yi`tl`yd`&e grü`yn`td`Ye`;n');
		
		output('`;E`Yi`tn `ye`&del gewandeter, würdevoller Elf - `yGwenmarfar`&, so sein Name - begrüßt dich im Verwaltungsoffizium der Gildengemeinschaft von '.getsetting('townname','Atrahor').'. ');
		
		switch($subop) {
		
			case '': // Gründung allg. Info, Prüfung auf Voraussetzungen
				
				$guilds = dg_count_guilds();
				$fail_count = 0;

				$out = '`n`n'.get_extended_text('guild_found_info').'`n`n';
				
				$out .= '`Y"Eine Gilde zu gründen - keine Aufgabe, die man auf die leichte Schulter nehmen sollte! Lasst Euch mal näher betrachten.." `&woraufhin er dich einer akribischen Inspektion unterzieht:`n`n`Y"';
				
				if($max_guilds <= $guilds) {$out.='Leider gibt es bereits '.$max_guilds.' Gilden. Mehr sind zur Zeit nicht zugelassen!`n';$fail_count++;}
			//	if($min_gold > $session['user']['gold']) {$out.='Du besitzt tragischerweise nicht die benötigten '.$min_gold.' Goldstücke!`n';$fail_count++;}
			//	if($min_gems > $session['user']['gems']) {$out.='Schade nur, dass du nicht die benötigten '.$min_gems.' Edelsteine besitzt!`n';$fail_count++;}
				if($min_dk > $session['user']['dragonkills']) {$out.='Um eine Gilde zu gründen, müsst Ihr schon mindestens '.$min_dk.' Heldentaten vollbracht haben!`n';$fail_count++;}
				
				if($fail_count == 0) {
					$out .= 'Gratuliere, Ihr erfüllt alle formalen Voraussetzungen. Beschreibt Eure Gildenidee nun etwas genauer:"';
					
					$formlink = 'dg_council.php?op=found&subop=found_ok';
					
					foreach($dg_child_types as $k=>$t) {
						$type_enum .= ','.$k.','.$t[0].' ('.$dg_types[$t[3]]['name'].')';
					}	
					
					$arr_form = array(
										'name'=>'Name der Gilde:|?(max. 40 Zeichen inkl. Farbcodes, unveränderlich)',
										'type'=>'Art der Gilde:,enum'.$type_enum
									);
					
					$out .= '`c<form action="'.$formlink.'" method="POST">';
					
					$out .= generateform($arr_form,array(),false,'Gründen');
					
					$out .= '</form>`c';
					
					addnav('',$formlink);
					
					foreach($dg_types as $t) {
						$out .= ('`n`b'.$t['name'].'`b:`n'.$t['desc'].'`n');
					}
					
					//output($out,true);
					
				}
				else {
					$out .= '`nIch muss Eure Gildengründung deshalb leider zurückweisen!"';
				}
				
				output($out,true);
				
				addnav('Zurück');
				addnav('Zum Gildenviertel','dg_main.php');
			
			break;
			
			case 'found_ok': // Abschicken!

                $str_name = utf8_preg_replace('/[`][^'.regex_appoencode(1,false).']/','',$_POST['name']).'`0';
                $int_type = intval($_POST['type']);

                $arr_data = array
                (
                    'founder'=>$Char->acctid,
                    'founded'=>getsetting('gamedate',''),
                    'name'=>$str_name,
                    'type'=>$int_type,
                    'immune_days'=>getsetting('dgimmune',6),
                    'ranks'=>$dg_default_ranks,
                    'state'=>DG_STATE_IN_PROGRESS,
                    'points'=>dg_calc_boni(0,'startpts',getsetting('dgstartpoints',10)),
                    'regalia'=>dg_calc_boni(0,'startregalia',getsetting('dgstartregalia',10)),
                    'guard_hp'=>dg_calc_boni(0,'startguardhp',100)
                );

                db_insert('dg_guilds',$arr_data);

                $int_gid = db_insert_id();

                $Char->guildid = $int_gid;
                $Char->guildfunc = DG_FUNC_LEADER;
                $Char->guildrank = 1;

                dg_addnews($Char->name.'`@ hat die Gilde '.$str_name.'`@ gegründet!',$Char->acctid,$int_gid);

                addhistory('`2Gegründet von '.$Char->name.'`2!',2,$int_gid);
                addhistory('`2Gründung der Gilde '.$str_name.'`2',1,$Char->acctid);

                systemmail($Char->acctid,'`b`2Deine Gilde wurde gegründet!`0`b',
                    'Sei gegrüßt!`nDu kannst nun im Gildenviertel die Residenz deine Gilde beziehen. `nZunächst solltest du dich daran machen,die Gilde fertigzustellen.');

				output('`n`n`@Gilde erfolgreich gegründet!`0`n`n`&Gwenmarfar nimmt deinen Antrag mit einem freundlichen Nicken entgegen. `q"Ich wünsch Euch viel Erfolg mit Eurer Gilde, möge sie ruhmreich sein!"`8`n
				');
				
				addnav('Zum Gildenviertel','dg_main.php');
			
			break;
			
		}
		
		break;	// END found
		
	case 'apply':	// Bewerbung bei einer Gilde
		
		$gid = (int)$_GET['gid'];
		
		if(!$gid) {redirect('dg_main.php');}
		
		$guild = dg_load_guild($gid);
		
		$subop = ($_GET['subop']) ? $_GET['subop'] : '';
		
		switch($subop) {
		
			case '':
				
				$min_dk = getsetting('dgmindkapply',3);
				
				output('`;E`Yi`tn `ye`&del gewandeter, würdevoller Elf - `yGwenmarfar`&, so sein Name - begrüßt dich im Verwaltungsoffizium der Gildengemeinschaft von '.getsetting('townname','Atrahor').'. ');
				
				if($min_dk <= $session['user']['dragonkills']) {
					$left = dg_guild_is_full($gid);
					if($left==0) {
						
						output(' Mit einem Ausdruck des Bedauerns erklärt er dir, dass diese Gilde bereits zu viele Mitglieder hat und deshalb keine weiteren gebrauchen kann.');
						addnav('Zum Gildenviertel','dg_main.php');
					}
					elseif($session['user']['guildfunc'] == DG_FUNC_CANCELLED && $session['user']['guildrank'] > 0) {
						output(' Kopfschüttelnd bedeutet er dir, besser schnell zu verschwinden. Du musst erst noch die Wartezeit von '.$session['user']['guildrank'].' Tagen abwarten, ehe du dich erneut bewerben darfst!');
						addnav('Zum Gildenviertel','dg_main.php');
					}
					else {
					
						output(' Freundlich legt er dir das Bewerbungsformular vor, weist dich jedoch zuerst auf die Informationen der Gilde hin.');
						output('`n`Y"Ich hoffe, dies hilft euch bei eurer endgültigen Entscheidung.."`8 Gwenmarfar nickt dir zu und schiebt eine Feder auf deine Seite.`n`n');
						
						dg_show_guild_bio($gid);
						
						addnav('Ja, Bewerbung abgeben!','dg_council.php?op=apply&subop=ok&gid='.$gid);
						addnav('Nein, zurück!','dg_main.php');
					}
					
				}
				else {
					
					//output('`8Stirnrunzelnd weist er dich zurück: `q"Du hast noch nicht genügend Heldentaten vollbracht! Komm mit drei grünen Lindwurmköpfen wieder, dann sehen wir weiter.."');
					output('`&Stirnrunzelnd weist er dich zurück: `Y"Du hast noch nicht genügend Heldentaten vollbracht! Siege über drei der größten aller Monster, dann sehen wir weiter.."');
					addnav('Zum Gildenviertel','dg_main.php');
					
				}
				
				break;
				
			case 'ok':
				
				// Infomail verschicken
				$sql = 'SELECT acctid FROM accounts WHERE (guildfunc='.DG_FUNC_MEMBERS.' OR guildfunc='.DG_FUNC_LEADER.') AND guildid='.$gid; //.' ORDER BY guildfunc ASC, loggedin DESC LIMIT 1';
				$res = db_query($sql);
				while ($mailto = db_fetch_assoc($res))
				{
					systemmail($mailto['acctid'],'`8Neue Gildenbewerbung!',$session['user']['name'].'`8 hat sich für die Mitgliedschaft bei deiner Gilde '.$guild['name'].'`8 beworben!');
				}
				
				dg_add_member($gid,$session['user']['acctid'],true);
				
				output('`&Der Elf nimmt deine Bewerbung gleichmütig entgegen und legt sie in das passende Fach. `q"Bis bald", `8verabschiedet er dich, `q"du wirst von der Gilde hören!"');
				
				addnav('Zum Gildenviertel','dg_main.php');
				
				break;
				
			case 'cancel':
				
				dg_remove_member($gid,$session['user']['acctid'],true);
				
				output('`Y"Hm, deine Bewerbung zurückziehen also.. hmhm.."`& seufzend greift Gwenmarfar in den Stapel und zerreißt das Pergament vor deinen Augen `Y"So in Ordnung? Dann auf bald!"');
				
				addnav('Zum Gildenviertel','dg_main.php');
				
				break;
		
		}	
		
		break;	// END apply

}	// END main switch

// jegliche Veränderung speichern
dg_save_guild();

if($access_control->su_check(access_control::SU_RIGHT_EDITORGUILDS)) { 
	addnav('Admin');
	addnav('X?Zum Gildeneditor','su_guilds.php');
}

page_footer();
?>
