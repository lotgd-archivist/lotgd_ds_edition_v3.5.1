<?php
/*-------------------------------/
Name: dg_builds.php
Autor: tcb / talion für Drachenserver (mail: t@ssilo.de)
Erstellungsdatum: 6/05 - 9/05
Beschreibung:	Enthält Ausbauten, die einen eigenen Raum besitzen. Wird an passender Stelle in dg_main.php inkludiert
				Diese Datei ist Bestandteil des Drachenserver-Gildenmods (DG).
				Copyright-Box muss intakt bleiben, bei Verwendung Mail an Autor mit Serveradresse.
				Besonderer Dank für Texte, Ideen und Tests gebührt folgenden Spielern und Spielerinnen des Drachenservers:
				Sith, Sersee, Ibga, Salvan und LOKI!
Gildenbibliothek mit der Funktion gildenintern Bücher zu veröffentlichen nachträglich hinzugefügt von Fossla (@atrahor.de) - 5/07
/*-------------------------------*/


	switch($_GET['building_op']) {

			case 'na':	// Auflistung der Vorteile bei Ausbauten ohne eigenen Raum

//				output('`tIm Verborgenen wirken durch Ausbauten viele Effekte, die sonst nicht wahrnehmbar wären. Hier sind jene aufgelistet:`n`n');
				output('`tDieser Bereich deines Gildenhauses ist zur Zeit versperrt. Hinter der Barrikade kannst du eifriges Hämmern und Sägen hören.`n`n');

				/*if($guild['build_list'][DG_BUILD_HALLE]) { output('Deine `bVersammlungshalle`b erhöht die maximale Mitgliederzahl um '.dg_calc_boni($gid,'members',5).' Helden.`n'); }
				if($guild['build_list'][DG_BUILD_VERSTECK]) { output('Dein `bVersteck`b erhöht die maximale Mitgliederzahl um '.dg_calc_boni($gid,'members', ($guild['ptype'] == DG_GUILD_TYPE_WIZARD || $guild['ptype'] == DG_GUILD_TYPE_THIEVES ? 1 : 0) ).' Helden.`n'); }
				if($guild['build_list'][DG_BUILD_WACHTURM]) { output('Dein `bWachturm`b verstärkt die Basislebenskraft deiner Gildenwache um '.dg_calc_boni($gid,'guard_hp_max',0).' Punkte.`n'); }
				if($guild['build_list'][DG_BUILD_SCHATZKAMMER]) { $transfer_plus = (dg_calc_boni($gid,'maxgoldin',1) - 1) * 100;output('Deine `bSchatzkammer`b ermöglicht '.($transfer_plus > 0 ? ' um '.$transfer_plus.' % höhere Ein- und Auszahlungen und ' : '').' um '.( (dg_calc_boni($gid,'treasure_maxgold',1) - 1) * 100).' % vergrößerte Schatztruhen.`n'); }
				if($guild['build_list'][DG_BUILD_WALL]) { output('Dein `bMagischer Schutzwall`b schwächt den Angriff der Gegner um '.(dg_calc_boni($gid,'magic_guard',0)*100).' Prozent.`n'); }*/

				addnav('Zurück','dg_main.php?op=in');

			break;

			// AUSBAUTEN
			case 'explain':

				dg_show_header('Gildenalmanach');

				$str_manual = get_extended_text('leader_manual');
				$str_history = get_extended_text('regalia_history_ext');

				output('`8'.$str_history.'`n`n'.$str_manual);

				addnav('Zur Halle','dg_main.php?op=in');

			break;

			// Lagerhalle
			case 'deposit':

				$str_type = $_REQUEST['type'];
				$str_act = $_REQUEST['act'];

				// Max. Anzahl an Slots
				$int_max = 1000;
				// Bereits vorhanden
				$int_count = 0;
				// Übriger Platz
				$int_left = 0;
				// Bewertungsfaktor für Edelsteine bei Itemtransferbegrenzung
				$int_gemsfactor = getsetting('maxitemsgemsfactor',5000);
				// BasisSQL
                /** @noinspection PhpUndefinedVariableInspection */
                $str_show_sql = ' owner='.ITEM_OWNER_GUILD.' AND deposit1='.$gid.' AND ';
				// Einlager-SQL
                /** @noinspection PhpUndefinedVariableInspection */
                $str_deposit_sql = ' owner='.$session['user']['acctid'].' AND deposit1=0 AND deposit2=0 AND ';
				// Anzahl der ben. Splitter für eine Insignie
				$int_parts_needed = 2;

				switch($str_type) {
					case 'regalia':
						$str_show_sql .= ' i.tpl_id="insgnteil" ';
						$str_deposit_sql .= ' i.tpl_id="insgnteil" ';
						$int_invent_look = ITEM_INVENT_HEAD_MULTI | ITEM_INVENT_HEAD_SEARCH;
						$str_header = 'Insigniensplitter:';

						$int_max = 20;
						$int_count = item_count($str_show_sql);
						$int_left = max($int_max - $int_count,0);

                        /** @noinspection PhpUndefinedVariableInspection */
                        $str_intro_txt =
							'`8In einem weiteren geräumigen Kellergewölbe, das von Fackeln hell erleuchtet wird, befindet sich
							die Insignienschmiede deiner Gilde. Vor den Säulen am Eingang halten Tag und Nacht mehrere Söldner
							Wache, ohne die Insignien aus den Augen zu lassen. Auf Holzbalken, unter Decken verborgen lagern
							sie, das Kapital deiner Gilde, während Werktische und verschiedene komplizierte Apparaturen bereitstehen,
							um sie aus ihren Einzelteilen zusammenzusetzen.`n
							Zur Zeit stehen '.$guild['regalia'].' Insignien zum Verkauf bereit, es können noch `b'.$int_left.'`b Insigniensplitter hier deponiert werden.`n`n
							';

					break;

					case 'furniture':
						$str_show_sql .= ' deposit_guild>0 ';
						$str_deposit_sql .= ' deposit_guild>0 ';
						//$int_invent_look = ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_ORDER;
						$int_invent_look = ITEM_INVENT_HEAD_CATS;
						$str_header = 'Möbel:';

						$int_max = 30;
						$int_count = item_count($str_show_sql,true);
						$int_left = max($int_max - $int_count,0);

						$str_intro_txt = '`8Den Möbelstücken deiner Gilde ist eine eigene Abteilung gewidmet. Kein Wunder, belegen
											sie doch selbst im gestapelten Zustand relativ viel Platz im Gewölbe.`n
											Ein Mitglied der Gildenführung kann anordnen, dass ein bestimmtes Möbelstück in die oberen
											Räume des Gildenhauses geschafft wird.`n`n
											`$Achtung: Einmal eingelagerte Möbelstücke gehen in den Besitz der Gilde über und können nicht mehr
											zurückgegeben werden!`8`n`n';

					break;

					case 'loot':
					default:

						// Bei abgeschicktem Formular den SQL erweitern
			            if (isset($_POST['submit']) && !empty($_POST['item_owner'])) {
							$owner = db_real_escape_string(stripslashes($_POST['item_owner']));
							$owner = db_fetch_assoc(db_query("SELECT acctid FROM accounts WHERE login='$owner'"));
							$owner = $owner['acctid'];

							$str_show_sql .= ' guildinvent>0 AND deposit_guild=0 AND i.tpl_id!="insgnteil" AND deposit2=' . $owner . ' ';
							$str_deposit_sql .= ' guildinvent>0 AND deposit_guild=0 AND i.tpl_id!="insgnteil" AND deposit2=' . $owner . ' ';
			            } 
			            elseif(isset($_GET['owner'])) 
			            {
							$str_show_sql .= ' guildinvent>0 AND deposit_guild=0 AND i.tpl_id!="insgnteil" AND deposit2=' . $_GET['owner'] . ' ';
							$str_deposit_sql .= ' guildinvent>0 AND deposit_guild=0 AND i.tpl_id!="insgnteil" AND deposit2=' . $_GET['$owner'] . ' ';
                  		}
                  		else 
                  		{
							$str_show_sql .= ' guildinvent>0 AND deposit_guild=0 AND i.tpl_id!="insgnteil" ';
							$str_deposit_sql .= ' guildinvent>0 AND deposit_guild=0 AND i.tpl_id!="insgnteil" ';
							$int_max = dg_calc_boni($gid,'invent_space',30);
							$int_count = item_count($str_show_sql,true);
							$int_left = max($int_max - $int_count,0);

							$str_intro_txt = '`8Auf alle Winkel und Ecken sind die Kleinteile, die Beutestücke und alchemistischen
							Erfolgserlebnisse der Gildenmitglieder verteilt. Du schätzt, dass der Platz noch für
							ungefähr `b'.$int_left.'`b Stücke ausreichen könnte.`n`n';
            			}
						
						if($_GET['act']=='in') 
			            {
							$int_invent_look = ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_MULTI;
						}
						else
						{
							$int_invent_look = ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_MULTI;
						}
						$str_header = 'Beute:';

						/*$int_max = dg_calc_boni($gid,'invent_space',30);
						$int_count = item_count($str_show_sql,true);
						$int_left = max($int_max - $int_count,0);

						$str_intro_txt = '`8Auf alle Winkel und Ecken sind die Kleinteile, die Beutestücke und alchemistischen
											Erfolgserlebnisse der Gildenmitglieder verteilt. Du schätzt, dass der Platz noch für
											ungefähr `b'.$int_left.'`b Stücke ausreichen könnte.`n`n';
						*/
						$str_intro_txt .= '`8Du hast außerdem auch die Möglichkeit gezielt nach Gegenständen der anderen Gildenmitglieder zu suchen.`nSuche Gegenstände von: ';

						// Stellt Drop-Down Menü dar um Items nach Besitzern zu filtern
			            $sql_item_owner = 'SELECT acctid,login FROM accounts WHERE guildid=' . $session['user']['guildid'] . ' AND guildfunc!='.DG_FUNC_APPLICANT.' ORDER BY login ASC';
			            $result_item_owner = db_query($sql_item_owner);
			            $str_lnk = 'dg_main.php?op=in&subop=buildings&building_op=deposit&type='.$str_type;
			            addnav('','dg_main.php?op=in&subop=buildings&building_op=deposit&type='.$str_type);
			            $str_intro_txt .= '<form method="POST" action="' . $str_lnk . '">
			              <select name="item_owner" size="1">
			              <option value="">allen</option>
			              <option value="">~~~</option>';

			            while($name = db_fetch_assoc($result_item_owner))
			            {
		                    if(isset($_GET['owner']) && ($_GET['owner'] == $name['acctid'])) 
		                    {
					            	  $str_intro_txt .= '<option value="' . $name['login'] . '" selected>' . $name['login'] . '</option>';
		                    }
		                    elseif(!empty($owner) && $owner == $name['acctid']) {
		                      $str_intro_txt .= '<option value="' . $name['login'] . '" selected>' . $name['login'] . '</option>';
		                    }
		                    else {
		                      $str_intro_txt .= '<option value="' . $name['login'] . '">' . $name['login'] . '</option>';
		                    }
			            }
			            $str_intro_txt .= '</select>
			            <input type="submit" name="submit" value="Suchen"></form>';
						// ENDE des Drop-Downs

					break;
				}

				dg_show_header('Lagerräume der Gilde - '.$str_header);


				switch($str_act) {

					// Einlagern
					case 'in':
						// Wenn Item gegeben
						if(!empty($_GET['id']) || !empty($_POST['ids'])) {

							if(is_array($_POST['ids']) && sizeof($_POST['ids']) > 0) {
								$str_ids = implode(',',$_POST['ids']);
								//fix bathi
								$arr_items = db_create_list(item_list_get(' id IN ('.db_intval_in_string(stripslashes($str_ids)).') AND owner='.$session['user']['acctid'].' AND '.$str_deposit_sql));
							}
							else {
								$arr_items = array(item_get('id='.(int)$_GET['id']));
							}

							$int_val = 0;

							foreach ($arr_items as $arr_item) {

								item_set(' id='.$arr_item['id'],
									array(
										'owner'=>ITEM_OWNER_GUILD,
										'deposit1'=>$gid,
										'deposit2'=>$arr_item['owner']
										)
									);

								output('`qDu suchst für '.$arr_item['name'].'`q einen Platz in den weitläufigen
										Lagerräumen deiner Gilde.`n');

								dg_commentary($gid,':`@ deponiert '.$arr_item['name'].'`@ im Gewölbe.','invent');

								if($str_type == 'regalia') {
									$guild['transfers'][$session['user']['acctid']]['ins']++;
								}

								$int_val += $arr_item['gold'] + $arr_item['gems'] * 500;

								$int_left--;
								if($int_left <= 0) {
									break;
								}

							}

                            /** @noinspection PhpUndefinedVariableInspection */
                            user_set_aei(array('itemsout'=>$rowe['itemsout']+$int_val));

							if($int_left > 0) {

								addnav('Mehr einlagern','dg_main.php?op=in&subop=buildings&building_op=deposit&act=in&type='.$str_type);

							}

							//$session['user']['turns']--;

						}
						else if($int_left <= 0) {
							output('`8Dieser Bereich des Gildenlagers ist leider schon völlig überfüllt. Maximal können `b'.$int_max.'`b Gegenstände
										an dieser Stelle gelagert werden, zur Zeit sind es jedoch `b'.$int_count.'`b!
										Hier muss erst Platz geschaffen werden.');
						}
						else {

							// op bereits durch Baselink gegeben
							$arr_options = array('Einlagern'=>'');

							item_invent_set_env($int_invent_look);

							item_invent_show_data(item_invent_head($str_deposit_sql,20),'Nach einer ausgiebigen Inspektion deines Beutels steht fest, dass du nichts dabei hast, das sich einzulagern lohnte.',$arr_options);

						}

						addnav('Zurück');
						addnav('Zu den Lagerräumen','dg_main.php?op=in&subop=buildings&building_op=deposit&type='.$str_type);
					break;

					// Auslagern
					case 'out':

						$arr_item = item_get('id='.(int)$_GET['id']);

						$float_pricefactor = getsetting('dg_invent_out_price',0.25);

						$int_goldprice = round($arr_item['gold'] * $float_pricefactor);
						$int_gemprice = round($arr_item['gems'] * $float_pricefactor);

						$int_val = $arr_item['gold'] + $arr_item['gems'] * $int_gemsfactor;

						// Kontrolle, ob Grenze für heute noch nicht überschritten
                        /** @noinspection PhpUndefinedVariableInspection */
                        if($rowe['itemsin'] + $int_val > getsetting('maxitemsin',8000) && $rowe['itemsin'] > 0) {
							output('`qLeider hast du heute bereits zu viele oder zu wertvolle Gegenstände
									mit dir genommen. Dein schlechtes Gewissen gegenüber den Armen dieser
									Welt hindert dich daran, dich zu bedienen.');
						}
						else if($session['user']['gold'] < $int_goldprice || $session['user']['gems'] < $int_gemprice) {	// Vermögen ausreichend?
							output('`qDu musst bei genauerer Betrachtung feststellen, dass dein Vermögen noch nicht einmal
									ausreicht, dem Lagermeister die geforderten '.$int_goldprice.' Gold, '.$int_gemprice.' Edelsteine zu bezahlen!');
						}
						else if($session['user']['turns'] <= 0) {	// Genug Runden?
							output('`qLeider bist du heute bereits zu geschwächt, um noch Krämerware durch die Gegend zu schleppen.`n
									Du beschließt, bis morgen zu warten.');
						}
						else {

							$session['user']['gold'] -= $int_goldprice;
							$session['user']['gems'] -= $int_gemprice;

							if($arr_item['deposit2'] != $session['user']['acctid'] && $arr_item['deposit2'] > 0) {

								$sql = 'SELECT name,acctid FROM accounts WHERE acctid='.$arr_item['deposit2'].' AND guildid='.$gid;
								$res = db_query($sql);

								if(db_num_rows($res)) {

									$str_msg = '`3Ein Bote bringt dir eine Nachricht:`n
												`#Ich habe mir erlaubt, den Gegenstand '.$arr_item['name'].'`#, welchen Ihr in der Lagerhalle
												unserer Gilde deponiert hattet, an mich zu nehmen.`n
												Gezeichnet`n
												'.$session['user']['name'].'`#';

									systemmail($arr_item['deposit2'],'`3Gegenstand entnommen!',$str_msg);
								}

							}

							dg_commentary($gid,':`$ entnimmt '.$arr_item['name'].'`$ aus dem Gildenlager.','invent');

							item_set(' id='.$arr_item['id'],
									array(
										'owner'=>$session['user']['acctid'],
										'deposit1'=>0,
										'deposit2'=>0,
										'gold'=>round($arr_item['gold'] * 0.75),
										'gems'=>round($arr_item['gems'] * 0.75)
										)
									);



							user_set_aei(array('itemsout'=>$rowe['itemsin']+$int_val));

							$session['user']['turns']--;

							output('`qDu nimmst '.$arr_item['name'].'`q an dich.');
						}	// END wenn Grenze noch nicht überschritten

            addnav('Mehr von diesem Besitzer','dg_main.php?op=in&subop=buildings&building_op=deposit&type='.$str_type.'&owner='.$arr_item['deposit2']);
						addnav('Zurück');
						addnav('Zu den Lagerräumen','dg_main.php?op=in&subop=buildings&building_op=deposit&type='.$str_type);

					break;

					// Möbel in Gilde verstauen
					case 'furniture_set':
						// Kontrolle, wie viele dieser Items wir als Möbelstück verwenden dürfen
						$arr_item = item_get('id='.(int)$_GET['id']);

						$bool_change = true;

						$int_depo = ($_GET['what'] == 'ext' ? ITEM_LOC_GUILDEXT : ($_GET['what'] == 'hall' ? ITEM_LOC_GUILDHALL : 0));

						if($int_depo > 0) {
							if( item_count(' tpl_id="'.$arr_item['tpl_id'].'" AND owner='.ITEM_OWNER_GUILD.' AND deposit1='.$gid.' AND deposit2='.$int_depo) >= $arr_item['deposit_guild']) {
								addnav('Zurück');
								addnav('Zu den Lagerräumen','dg_main.php?op=in&subop=buildings&building_op=deposit&type='.$str_type);
								output('`8Du kannst maximal '.$arr_item['deposit_guild'].' Exemplare dieses Möbelstücks in einem Raum abstellen!');

								$bool_change = false;
							}
						}

						if($bool_change) {
							item_set(' id='.$arr_item['id'],
										array(
											'deposit2'=>$int_depo
											)
										);
							redirect('dg_main.php?op=in&subop=buildings&building_op=deposit&type=furniture');
						}
					break;

					// Möbel wegwerfen
					case 'remove':

						$arr_item = item_get('id='.(int)$_GET['id'],false);

						dg_commentary($gid,':`$ entfernt '.$arr_item['name'].'`$ aus dem Gildenlager.','invent');

						item_delete(' id='.(int)$_GET['id']);

						redirect('dg_main.php?op=in&subop=buildings&building_op=deposit&type=furniture');
					break;

					// Insignie produzieren
					case 'produce_regalia':
						$int_max_regalia = getsetting('dgmaxregalia',15);

						if($int_max_regalia > $guild['regalia'] && $int_count >= $int_parts_needed) {

							item_delete(' owner='.ITEM_OWNER_GUILD.' AND tpl_id="insgnteil" AND deposit1='.$gid, $int_parts_needed);

							$guild['regalia']++;

							dg_commentary($gid,'/msg`@Eine neue Insignie wurde geschaffen!','invent',1);
						}

						output('`8Eifrige, huschende Gnome wuseln hin und her, bringen die Apparaturen zum
								Dampfen. Bald schon liegt etwas Rauch in der Luft. Pfeifend walzen die Pressen
								die Insigniensplitter zusammen, um sie anschließend in sengender Hitze zu vereinen.`n
								Spritzelnde Funken prallen an die Mauern des Gewölbes, die fleißigen Arbeiter können gerade
								noch zur Seite springen, als sich der Deckel eines Kessels schwerfällig hebt und ein glitzerndes,
								pyramidenförmiges und fast schon blendend schönes Artefakt hervorhebt! Um es herum scheint die Luft
								vor Magie zu vibrieren... eine Insignie wurde geschaffen!`n`n
								Du kannst sie nun den regelmäßig erscheinenden Paladinen des Königs zum Verkauf anbieten oder im Lager
								verschimmeln lassen.');

						addnav('Hurra!','dg_main.php?op=in&subop=buildings&building_op=deposit&type=regalia');
					break;

					// Eingangsansicht
					default:

						// Itemid gegeben = näher betrachen
						if($_GET['id']) {

							$arr_item = item_get('id='.(int)$_GET['id']);

							output('`qDu wirfst einen genaueren Blick auf '.$arr_item['name'].'`q. Wenn du es beschreiben solltest,
									würdest du es in etwa so tun:`n'.$arr_item['description'].'`q.`n');

							if($arr_item['deposit2'] == $session['user']['acctid']) {
								output('`qDu erinnerst dich, dass dieser Gegenstand einmal dir gehört hat!`n');
							}
							else {

								$sql = 'SELECT name,acctid FROM accounts WHERE acctid='.$arr_item['deposit2'].' AND guildid='.$gid;
								$res = db_query($sql);

								if(db_num_rows($res)) {

									$arr_owner = db_fetch_assoc($res);
									output('`qAn dem Gegenstand baumelt ein Kärtchen, auf welchem ein Name verzeichnet ist: '.$arr_owner['name'].'`q`n');

								}
								else {
									output('`qDer Gegenstand scheint niemandem zu gehören.`n');
								}
							}



							if($str_type == 'loot') {
								addnav('Aktionen');
								output('`n`qDu hast die Möglichkeit, '.$arr_item['name'].'`q mit dir zu nehmen, benötigst dafür allerdings einen Waldkampf!');
								addnav('Mitnehmen','dg_main.php?op=in&subop=buildings&building_op=deposit&act=out&id='.$arr_item['id'].'&type='.$str_type);
							}

							if($str_type == 'furniture') {
								addnav('Aktionen');

								output('`n`qDu hast die Möglichkeit, '.$arr_item['name'].'`q endgültig aus der Gilde zu entfernen!');
								addnav('Wegwerfen','dg_main.php?op=in&subop=buildings&building_op=deposit&act=remove&id='.$arr_item['id'].'&type='.$str_type);

								// Wenn noch nicht eingelagert
								if($arr_item['deposit2'] != ITEM_LOC_GUILDHALL && $arr_item['deposit2'] != ITEM_LOC_GUILDEXT) {
									output('`n`qDu hast die Möglichkeit, '.$arr_item['name'].'`q als Möbelstück in den Gildenräumen zu verwenden!');
									if(!empty($guild['ext_room_name'])) {
										addnav('In '.$guild['ext_room_name'].'`0 packen!','dg_main.php?op=in&subop=buildings&building_op=deposit&act=furniture_set&id='.$arr_item['id'].'&what=ext');
									}
									addnav('In Gildenhalle packen!','dg_main.php?op=in&subop=buildings&building_op=deposit&act=furniture_set&id='.$arr_item['id'].'&what=hall');
								}
								else {
									output('`n`qDu hast die Möglichkeit, '.$arr_item['name'].'`q von seinem aktuellen Standplatz als Möbelstück wieder in die Gewölbe zu verfrachten!');

									addnav('In Gewölbe packen!','dg_main.php?op=in&subop=buildings&building_op=deposit&act=furniture_set&id='.$arr_item['id'].'&what=out');
								}
							}

							addnav('Zurück');
							addnav('Zu den Lagerräumen','dg_main.php?op=in&subop=buildings&building_op=deposit&type='.$str_type);

						}
						else {

							output('`8Du steigst die breiten, abgetretenen Treppenstufen herab in den Keller des Gildenhauses.`n
									Neugierig inspizierst du das Lagergewölbe deiner Gilde. Schmale, in die Decke
									eingelassene und natürlich vergitterte Fensteröffnungen lassen etwas Licht in die unterirdischen
									Räume und auf die sorgsam aufgestapelten Dinge fallen.`n
									`n');

							$str_lnk = 'dg_main.php?op=in&subop=buildings&building_op=deposit&type=';

							addnav('Gewölbe - Eingang');
							addnav((empty($str_type) ? '`^':'').'Inventur',$str_lnk);

							addnav('Gewölbe - Beutestücke');
							addnav('Einlagern',$str_lnk.'loot&act=in');
							addnav(($str_type=='loot' ? '`^':'').'Ansehen',$str_lnk.'loot');

							addnav('Gewölbe - Insignien');
							addnav('Einlagern',$str_lnk.'regalia&act=in');
							addnav(($str_type=='regalia' ? '`^':'').'Ansehen',$str_lnk.'regalia');

							addnav('Gewölbe - Möbel');
							addnav('Einlagern',$str_lnk.'furniture&act=in');
							addnav(($str_type=='furniture' ? '`^':'').'Ansehen',$str_lnk.'furniture');

							if(!empty($str_type)) {

								output($str_intro_txt);

								if($str_type == 'regalia') {

									output('`8Bisher liegen in den angrenzenden Lagerkellern der Gilde '.$int_count.' Insigniensplitter bereit,
									die bereits zur Weiterverarbeitung auserkoren sind.`n');

									$int_max_regalia = getsetting('dgmaxregalia',15);

									if($int_max_regalia <= $guild['regalia']) {
										output('`$Die Insignienlager der Gilde sind voll. Bis zum nächsten Erscheinen der Paladine kann die
												Gilde keine Insignien mehr produzieren.`n');
									}
									else {

										if($int_count >= $int_parts_needed) {
											output('Dies sollte reichen, um daraus eine Insignie zu schmieden! Sobald ein Mitglied
												der Führungsriege den Befehl dazu gibt, wird mit der Produktion begonnen.`n`n');
                                            /** @noinspection PhpUndefinedVariableInspection */
                                            if($team) {
												addnav('Aktionen');
												addnav('`@Insignie produzieren!`0','dg_main.php?op=in&subop=buildings&building_op=deposit&act=produce_regalia&type=regalia');
											}
										}
									}
								}

								item_invent_set_env(ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_ORDER);
								$arr_options = array('Näher betrachten'=>'');
								$dd = $session['user']['prefs']['dontstackguild'] ? true : false;
								item_invent_show_data(item_invent_head($str_show_sql,20),'Die Lagerhallen der Gilde sind in diesem Bereich völlig leer.',$arr_options,'',$dd);
							}
							else {
								addcommentary();
                                /** @noinspection PhpUndefinedVariableInspection */
                                viewcommentary('guild-'.$gid.'_invent',($team ? 'Etwas verkünden:':'Du solltest hier besser schweigen!'),25,'verkündet',false,($team?true:false),false,false,true,true,2);
							}

							addnav('Zurück');
						}

					break;

				}

				$rowe = user_get_aei('itemsin,itemsout');


				addnav('Zur Halle','dg_main.php?op=in');

			break;
			// END Lagerräume

			case 'waffenkammer':
				/** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_WAFFENKAMMER];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Die Waffenkammer ('.$dg_build_levels[$lvl].')');

				$min = 65 - pow(1.3,$lvl) * 4;

                /** @noinspection PhpUndefinedVariableInspection */
                $item = $guild['building_vars'][DG_BUILD_WAFFENKAMMER]['itemlist'][$session['user']['acctid']];
				if(is_array($item)) {
					$price_factor = 2.5 - pow(1.05,$lvl);
					$price_gold = max(round($item['gold'] * $price_factor),1500);
					$price_gems = max(round($item['gems'] * $price_factor),2);
				}

				if($_GET['act'] == 'in') {

					if($guild['building_vars'][DG_BUILD_WAFFENKAMMER]['itemlist'][$session['user']['acctid']]) {
						output('`6"Du hast schon was deponiert. Hol das erst mal raus!"`3');
					}
					else {

						if($_GET['id']) {
							$item = item_get( ' id='.(int)$_GET['id'] , false );

							$guild['building_vars'][DG_BUILD_WAFFENKAMMER]['itemlist'][$session['user']['acctid']] = $item;

							output('`3Du lieferst '.$item['name'].'`3 ab. Der Ork wirft deine Waffe mehr in die Vitrine, als dass er sie legt und blickt sich dann weiter grimmig um. Insbesondere in deiner Richtung.');

							item_delete( ' id='.(int)$_GET['id'] );
						}
						else {

							output('`6"Welche von den Dingern willst du da reintun?" `3raunzt er dir zu`n`n');

							item_invent_set_env(ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_SEARCH);

							$arr_options = array('Einlagern'=>'');

							item_invent_show_data(item_invent_head(' tpl_class=8 AND equip='.ITEM_EQUIP_WEAPON.' AND deposit1!='.ITEM_LOC_EQUIPPED.' AND owner='.$session['user']['acctid'], 20),'Leider findest du keine einzige Waffe in deinem Beutel.',$arr_options);

						}
					}

					addnav('Zurück','dg_main.php?op=in&subop=buildings&building_op=waffenkammer');

				}

				elseif($_GET['act'] == 'out') {

					if($session['user']['gold'] >= $price_gold && $session['user']['gems'] >= $price_gems) {

						$item = $guild['building_vars'][DG_BUILD_WAFFENKAMMER]['itemlist'][$session['user']['acctid']];
						unset($guild['building_vars'][DG_BUILD_WAFFENKAMMER]['itemlist'][$session['user']['acctid']]);

						if(e_rand(1,100) < $min) {
							output('`3Als du erneut die Kammer betrittst, um dein Eigentum abzuholen, drückt dir der Ork ziemlich hastig die Klinge in die Hand und nuschelt was von `6"Kampfübungen... hat ziemlich gelitten... Klinge ist leicht beschädigt"`3 Du betrachtest deine Waffe und stellst fest, dass das etwas kaputte Material ab sofort wohl etwas weniger Schaden machen wird. Als du dich gerade beschweren willst, fuchtelt der Ork nur angsteinflößend mit den Armen und gibt komische Laute von sich, so dass du lieber schnell diesen Ort verlässt.
							`n`n
							`3Der Angriff scheint von '.$item['value1'].' auf '.($item['value1']-1).' gesunken zu sein!');
							$item['value1']--;
							if($item['value1'] <= 0) {
								output('`nDadurch ist die Waffe zu nichts mehr zu gebrauchen!');
								unset($item);
							}

						}

						if(is_array($item)) {

							$item['tpl_value1'] = $item['value1'];
							$item['tpl_value2'] = $item['value2'];
							$item['tpl_gold'] = $item['gold'];
							$item['tpl_gems'] = $item['gems'];
							$item['tpl_name'] = $item['name'];
							$item['tpl_id'] = isset($item['tpl_id']) ? $item['tpl_id'] : 'waffedummy';
							$item['tpl_description'] = $item['description'];

							item_add($session['user']['acctid'],0,$item);

							$session['user']['gems'] -= $price_gems;
							$session['user']['gold'] -= $price_gold;

							output('`n`n`3Du zahlst dem Ork den Preis und nimmst '.$item['name'].'`3 wieder an dich.');
						}
					}
					else {
						output('`3Der Ork will gerade deine Waffe einer der Vitrinen entnehmen, als sein Blick auf die wenigen Goldstücke fällt, die du ihm hingelegt hast `6"Das ist alles? Da behalt ich das Ding lieber mal!"`3 Verärgert wirft er dir deine Münzen entgegen und schaut dich grimmig an, so dass du schnell das Weite suchen willst.');
					}
					addnav('Zurück','dg_main.php?op=in&subop=buildings&building_op=waffenkammer');
				}
				else {

					output('`3Du trittst durch eine düstere Eichentür, hinter der du bisher nur den Kerker vermutet hättest, denn hier im Kellergewölbe herrscht Dunkelheit, die nur von vereinzelten Fackeln durchbrochen wird und eine eisige Stille. Doch zu deiner Verwunderung trittst du in einem Raum, der einem Paradies für jeden Kämpfer gleicht. Überall stehen glänzende, aber auch schon rostende Rüstungen, stählerne Helme liegen sorgfältig geordnet auf einem Holzbalken, doch deine Aufmerksamkeit wird vor allem angezogen von den unzähligen Waffen, die hier bereitliegen. Zwischen all den Schwertern, Dolchen und Äxten fällt dir plötzlich ein missmutig gelaunter Ork ins Auge, der dich ungeduldig anraunzt.
					`6"Was willst\'n du hier?"`3 Schon der Gestank der Kreatur, aber auch der Anblick lässt den Ekel in dir herauf kriechen, doch du antwortest, ohne es dir anmerken zu lassen `9"Ich möchte meine Klinge in eure Obhut geben"`3. Fordernd streckt der Ork seine Hand nach deinem Schwert aus `6"Für nen richtigen Preis, pass ich drauf auf"`3.`n`n');

					// Waffen von Nichtmehr-Existierenden bzw. Ausgetretenen entfernen
					if(count($guild['building_vars'][DG_BUILD_WAFFENKAMMER]['itemlist']) > 0) {
                        /** @noinspection PhpUndefinedVariableInspection */
                        $arr_member_list = dg_load_member_list($gid);

						foreach($guild['building_vars'][DG_BUILD_WAFFENKAMMER]['itemlist'] as $acctid=>$i) {
							if(!isset($arr_member_list[$acctid])) {
								unset($guild['building_vars'][DG_BUILD_WAFFENKAMMER]['itemlist'][$acctid]);
							}
						}
					}
					// END Redundanzcheck

					if(is_array($item)) {

						output('`n`3Bisher deponiert: '.$item['name'].'`3'.(!empty($item['description']) ? ' ('.$item['description'].'`3)' : '').'.`n');

						if($session['user']['level'] > 1) {
							output('`3Kosten für\'s Herausholen: `^'.$price_gold.'`3 Gold und `^'.$price_gems.'`3 Edelsteine.');
							addnav('Herausholen','dg_main.php?op=in&subop=buildings&building_op=waffenkammer&act=out');
						}
						else {
							output('`3Auf Level 1 kannst du die Waffe nicht herausholen!');
						}

					}
					else {

						// Vorerst mal auf Lvl 15 auch deponieren möglich
						if($session['user']['level'] < 16) {
							addnav('Deponieren','dg_main.php?op=in&subop=buildings&building_op=waffenkammer&act=in');
						}
						else {
							output('`n`3Auf Level 15 kannst du keine Waffe deponieren!');
						}
					}


					addnav('Zur Halle','dg_main.php?op=in');
				}

				break;

			case 'schmiede':
				/** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_SCHMIEDE];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Die Schmiede ('.$dg_build_levels[$lvl].')');

				$impr_lvl = min($lvl,4);	// jeden Lvl ein Upgrade auf Verbesserung, max. 4
				$impr = $impr_lvl;
				$price_rebate = pow( 1.25 , max($lvl,0) ) * 300;
				$price_gold = round(4500 + ($impr_lvl * 800) - $price_rebate);

				if($_GET['act'] == 'ok') {

                    /** @noinspection PhpUndefinedVariableInspection */
                    if($price_gold > $session['user']['gold']) {
						output('`n`tDie Zornesröte steigt ihm ins Gesicht, nachdem du ihm voll Verlegenheit gestanden hast, nicht genug Gold dabeizuhaben: `T"Wie kannst du es wagen, mich zu belästigen?! Für umsonst "`t - dieses Wort spricht er voller Widerwillen aus - `T"arbeitet KEIN Zwerg! Verschwinde, du, bevor.."`t Drohend hebt er seine Axt und tut einen Schritt in deine Richtung. Du machst dich besser davon...');
					}
					else {

						$name = $session['user']['armor'].' G:'.$impr;
						$skill = $session['user']['armordef'] + $impr;
						$val = $session['user']['armorvalue'] + $price_gold * 0.5;

						item_set_armor($name, $skill, $val, 0, 0, 1);

						$session['user']['gold'] -= $price_gold;

						output('`n`tEr hämmert voller Inbrunst auf deiner Rüstung herum, so dass die Funken stieben! Befriedigt überreicht er dir gegen '.$price_gold.' Gold das gute Stück. `T"Hier hast du! Und nun lass mich weiterarbeiten."');
					}
				}
				else {

                    /** @noinspection PhpUndefinedVariableInspection */
                    output('`tDu betrittst forschen Schrittes die hintere Gewölbeecke, die dem Schmied deiner Gilde bestimmt ist. Ein ohrenbetäubender Lärm erfüllt die stickige Luft, wenn Azaghal, der zwergene Schmied, seinen Hammer auf den Amboss niederfahren lässt. Nachdem du ihm vorsichtig auf die Schulter getippt hast, hält er inne und wendet dir sein bärtiges, verschwitztes Antlitz zu, etwas ergrimmt über die Unterbrechung: `T"'.$session['user']['name'].'`T, nehme ich an! Falls es dein Begehren ist, diese/s '.$session['user']['armor'].'`T zu verstärken, so sag es gleich.."');

					if(mb_strpos($session['user']['armor'],' G:')) {

						output('`n`n`tDie Zornesröte steigt ihm ins Gesicht, nachdem er einen genaueren Blick auf deine Rüstung geworfen hat: `T"Wie kannst du es wagen, mich zu belästigen?! An diesem exzellenten Stück kann selbst ich nichts mehr tun! Verschwinde, du, bevor.."`t Drohend hebt er seine Axt und tut einen Schritt in deine Richtung. Du machst dich besser davon...');

					}
					elseif($session['user']['armordef']==0)
					{
						output('`t Plötzlich fängt er an zu lachen. `T"'.$session['user']['armor'].'`T? Nene, daran kann `bich`b nichts verbessern."');
					}
					else {

						output('`t Du willst gerade zum Sprechen ansetzen, als er in seinen Bart grummelt `T"`^'.$price_gold.' Gold`T, dafür mache ich daraus ein hervorragendes, hm.. ich nenne es mal `^'.$session['user']['armor'].' G:'.$impr.'`t! Also, was ist nun?"');

						$price_bonus = max($lvl - 5,0);
						$lvl -= $price_bonus;
						$link = 'dg_main.php?op=in&subop=buildings&building_op=schmiede&act=ok';
						output('`n`nJa, <a href="'.$link.'">verbesser\' meine Rüstung!</a>',true);

						addnav('',$link);
					}

				}

				addnav('Zur Halle','dg_main.php?op=in');

				break;	// END schmiede

			case 'juwelier':
				/** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_JUWELIER];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Der Juwelier ('.$dg_build_levels[$lvl].')');

				$impr_lvl = ceil($lvl / 2);	// jeden 2. Lvl ein Upgrade auf Wahrscheinlichkeit, ansonsten nur auf Preis
				$price_rebate = max($lvl - $impr_lvl,0) * 200;
				$price_gold = 5500 + ($impr_lvl * 100) - $price_rebate;

				$transferred = user_get_aei('gemsin');

				$max = getsetting('dgmaxgemstransfer',2) + $impr_lvl;
				$max = max($max - $transferred['gemsin'],0);

				if($_GET['act'] == 'ok') {

                    /** @noinspection PhpUndefinedVariableInspection */
                    if($price_gold > $session['user']['gold']) {
						output('`rDie Elfe schüttelt nur stumm ihren hübschen Kopf, als du ihr deine Goldvorräte zeigst.');
					}
					else {
						$session['user']['gold'] -= $price_gold;
						$min = 50 - (pow(1.25,$impr_lvl) * 5);
						if(e_rand(1,100) >= $min) {
							$session['user']['gems']++;

							user_set_aei(array('gemsin'=>$transferred['gemsin']+1));

							output('`rLächelnd überreicht sie dir einen funkelnden Juwel: `5"Ich hoffe, ihr seid zufrieden!"');
						}
						else {
							output('`rMit einer Miene des Bedauerns erklärt sie dir, dass dein Gold leider verloren ist. Die Herstellung schlug fehl!');
						}
					}
				}

				else {

                    /** @noinspection PhpUndefinedVariableInspection */
                    output('`rSchon von weiten kannst du die Türen zum Juwelier ausmachen, denn ebenso wie der gesamte Raum dahinter, ist auch die Eingangstür prunkvoll mit Schmuck und Edelsteinen verziert.
					Als du in den recht kleinen Raum trittst, wirst du schier geblendet, angesichts all des Golds und der Juwelen.
					Schließlich haben sich deine Augen daran gewöhnt und du kannst eine hübsche, junge Elfe hinter einem langen Verkaufstresen erkennen, die dir reich geschmückt mit Ketten, Ringen und Armreifen entgegenlächelt.
					In den Vitrinen liegen die seltensten und schönsten Schmuckstücke die du je gesehen hast. Plötzlich erhebt die Elfe ihre zarte Stimme:
					`5"Schau dich ruhig um, '.$session['user']['login'].'. '.($max > 0?'Für nur `^'.$price_gold.'`5 Gold können wir dir einen dieser hübschen Steine anfertigen! Aber es gibt selbstverständlich keine Garantie auf Erfolg..':'Doch leider können wir dir heute nichts mehr anbieten. Unsere Vorräte sind erschöpft!').'"');

					$link = 'dg_main.php?op=in&subop=buildings&building_op=juwelier&act=ok';
					output('`n`n'.($max > 0?'<a href="'.$link.'">Einen Edelstein herstellen!</a>':'Keine Edelsteine mehr machbar!'),true);

					addnav('',$link);
				}

				addnav('Zur Halle','dg_main.php?op=in');

				break;	// End juwelier

			case 'geheim':
				/** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_GEHEIM];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Der Geheimdienst ('.$dg_build_levels[$lvl].')');

				$impr_lvl = ceil($lvl / 2);	// jeden 2. Lvl ein Upgrade auf Wahrscheinlichkeit, ansonsten nur auf Preis
				$price_rebate = max($lvl - $impr_lvl,0) * 120;
				$price_gold = 500 + ($impr_lvl * 300) - $price_rebate;

				if($_GET['act'] == 'ok') {

					$hid = (int)$_POST['hid'];

					$sql = 'SELECT housename,description,attacked,a.name,h.gold,h.gems,h.status FROM houses h LEFT JOIN accounts a ON a.acctid=owner WHERE houseid='.$hid;
					$res = db_query($sql);
					if(db_num_rows($res) == 0) {
						output('`!Der Spion schüttelt nur stumm den Kopf. `1"Dieses Haus gibt es nicht!"`!, raunt er dir zu');
						addnav('Neue Suche','dg_main.php?op=in&subop=buildings&building_op=geheim');
					}
					else {
                        /** @noinspection PhpUndefinedVariableInspection */
                        if($price_gold > $session['user']['gold']) {
							output('`!Der Spion schüttelt nur stumm den Kopf. `1"Umsonst arbeiten tun nur die Dummen!"`!, raunt er dir zu');
						}
						else {
							$house = db_fetch_assoc($res);

							$session['user']['gold'] -= $price_gold;

							$min = 40 - ($impr_lvl * 6);
							if(e_rand(1,100) >= $min && ($house['status'] < 30 || $house['status'] >= 40)  ) {

								// Code aus houses.php

								$pvptime = getsetting("pvptimeout",600);

								$pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));

								$days = getsetting("pvpimmunity", 5);

								$exp = getsetting("pvpminexp", 1500);

								if(is_array($guild['treaties'])) {
									foreach($guild['treaties'] as $id=>$t) {
										if( dg_get_treaty($t)==1 ) {	// wenn Frieden mit dieser Gilde
                                            /** @noinspection PhpUndefinedVariableInspection */
                                            $ids .= ','.$id;
										}
									}
								}

								// Hot Items
								$res = item_list_get(' hot_item>0 AND owner>0 AND deposit1=0 ','',true,'owner');
								if(!db_num_rows($res)) {
									$str_immu_off = '-1';
								}
								else {
									$arr_hot_owners = db_create_list($res,false,true);
									$str_immu_off = implode(',',$arr_hot_owners);
								}

								$sql = "SELECT acctid,name,maxhitpoints,defence,attack,level,laston,loggedin,a.gold FROM keylist k LEFT JOIN accounts a ON a.acctid=k.owner
										WHERE (k.value1=".$hid." AND k.hvalue=".$hid.") AND
										(locked=0) AND
										(alive=1 AND location=".USER_LOC_HOUSE.") AND
										!(".user_get_online().") AND
										(age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
										(acctid <> ".$session['user']['acctid'].") AND
										(pvpflag <> '".PVP_IMMU."' OR acctid IN (".$str_immu_off.")) AND
										(pvpflag < '$pvptimeout' OR pvpflag='".PVP_IMMU."') AND
										(guildid = 0 OR guildid NOT IN (0".$ids.") OR guildfunc=".DG_FUNC_APPLICANT.") ORDER BY maxhitpoints DESC
										";
								$res = db_query($sql);

								output('`!Nach einiger Zeit übergibt man dir eine Schriftrolle mit folgendem Inhalt:`&`n`n`b'.$house['housename'].'`b`0 (Besitzer: '.$house['name'].'`0)`n'.$house['description'].'`n`n'.($house['attacked'] > 0 ? '`&`n(Heute bereits '.$house['attacked'].'mal angegriffen!)':'`&`n(Wurde heute noch nicht beraubt)').'`&`n`n');
								if($lvl > 2) {
									output('Gold im Haus: '.$house['gold'].', Edelsteine im Haus: '.$house['gems'].'`n`n');
								}

								output('Diese Helden stehen bereit, um gegen dich anzutreten:`n`n');

								while($a = db_fetch_assoc($res)) {

									output('`n'.$a['name'].'`0 ');
									if($lvl > 3) {
										$dif = ($session['user']['attack'] - $a['attack']) + ($session['user']['defence'] - $a['defence']);
										output(' `i(');
										if($dif > 20) {
											output('Nicht der Rede wert');
										}
										elseif($dif > 10) {
											output('Schwächer');
										}
										elseif($dif > 0) {
											output('Ähnlich stark');
										}
										elseif($dif < -20) {
											output('Zu stark');
										}
										elseif($dif < -10) {
											output('Stärker');
										}
										elseif($dif < 0) {
											output('Ähnlich stark');
										}
										output(')`i');
									}
									if($lvl > 4) {
										output(' - Hat `^'.$a['gold'].'`0 Gold dabei!');
									}

								}

							}
							else {
								$sql = 'SELECT name FROM accounts ORDER BY dragonkills DESC LIMIT 0,10';
								$res = db_query($sql);

								output('`!Nach sehr kurzer Zeit übergibt man dir eine Schriftrolle mit folgendem Inhalt:`n`n');

								while($a = db_fetch_assoc($res)) {
									output($a['name'].'`n');
								}
								output('`n`n`!Irgendwas stimmt hier doch nicht ganz...`n');

								if($house['status'] >= 30 && $house['status'] < 40) {output('Vielleicht ist das Haus ja ein Versteck?!');}

							}
						}
					}	// END haus gefunden
				}

				else {

					$link = 'dg_main.php?op=in&subop=buildings&building_op=geheim&act=ok';
					output('`!Während du suchend dem dunkelsten Gang im ganzen Gebäude folgst, fällt dir plötzlich ein kaum merklicher, sehr schwacher Lichtschein auf, der durch eine nicht ganz geschlossene Tür fällt.
					Du näherst dich der Tür, hörst gedämpfte Stimme miteinander flüstern. Langsam öffnest du die Holztür und trittst in einen düsteren Raum, der nur von einer Lampe, die in der Mitte des Raumes an der Decke angebracht ist, erhellt wird.
					Die Blicke vieler Gestalten, deren Gesicht fast vollständig durch die Kapuzen der schwarzen Mäntel verborgen ist, richten sich auf dich. Einer der Männer tritt auf dich zu, nimmt dich beiseite und du erlaubst dir ohne Begrüßung die Frage:
					"Seid ihr die Ausgestoßenen? Die Spione, die für Gold fast alles herausfinden können?"
					Die vermummte Gestalt mustert dich lange und nickt schließlich: `1"Du musst uns nur die Hausnummer nennen und wir finden gegen `^'.$price_gold.'`1 Gold für dich heraus, wer sich dort in Sicherheit wiegt."
					`n`n
					<form action="'.$link.'" method="POST">Hausnr.: <input name="hid" type="text" size="4" maxlength="4"> <input type="submit" value="Haus ausspionieren!"></form>
					',true);

					addnav('',$link);
				}

				addnav('Zur Halle','dg_main.php?op=in');

				break;	// End

			case 'library':

				switch($_GET['act']) {

					case 'new_book':
						// Formular um die Daten für ein neues Buch einzugeben
						/** @noinspection PhpUndefinedVariableInspection */
                        $lvl = $guild['build_list'][DG_BUILD_BIBLI];

						dg_show_header('Neues Buch einreichen');
						addnav('Zurück');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');
						addnav('','dg_main.php?op=in&subop=buildings&building_op=library&act=submit');
						output("<form action='dg_main.php?op=in&subop=buildings&building_op=library&act=submit' method='POST'>
						Themenzuordnung: <select name='theme' size='1'>");
						
						$arr_themes = $guild['building_vars']['bibliothek']['dg_book_themes'];
						if(!is_array($arr_themes))
						{
							$arr_themes = array();	
						}
						foreach ($arr_themes as $key=>$str_theme)
						{	
							if(is_null_or_empty($str_theme))
							{
								continue;
							}
							output('<option value="'.$key.'">' . $str_theme);
                            /** @noinspection PhpUndefinedVariableInspection */
                            $i++;
						}
						output("</select>`n`n
						Titel des Werks: <input type='Text' name='title' value='' size='40' maxlength='250'>`n`n
						Dein Wissen über dieses Thema:`n
						<textarea name='txt' class='input' cols='60' rows='20'></textarea>`n`n
						<input type='Submit' name='submit_new_book' value='Einreichen'>
						</form>");

						break;


					case 'book_goon':
						// Listet die selbst verfassten Bücher auf die fortgesetzt werden können

						dg_show_header('Folgende Bücher kannst du fortsetzen:');
						addnav('Zurück');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');

                        /** @noinspection PhpUndefinedVariableInspection */
                        $acctid = $session['user']['acctid'];

                        /** @noinspection PhpUndefinedVariableInspection */
                        dg_show_books($guild['guildid']);

						break;

					case 'book_goon2':
						// Möglichkeit ein selbst verfasstes Buch noch weiter zu ergänzen

						$sql = "SELECT * FROM `dg_books` WHERE bookid=" . $_GET['bookid'];
						$row = db_fetch_assoc(db_query($sql));

						dg_show_header('Buch fortsetzen');
						addnav('Zurück');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');
						addnav('','dg_main.php?op=in&subop=buildings&building_op=library&act=submit');
						output("<form action='dg_main.php?op=in&subop=buildings&building_op=library&act=submit' method='POST'>
						<input value='" . $row['bookid'] . "' name='bookid' type='hidden'>
						`8Titel des Werks: `&" . $row['title'] . "`n`n
						`8Dein Wissen über dieses Thema:`n
						<textarea name='txt' class='input' cols='60' rows='20'>" . str_replace("`","&#x0060;",$row['txt']) . "</textarea>`n`n
						<input type='Submit' name='submit_book_goon' value='Erneut Einreichen'>
						</form>");

						break;

					case 'edit_themes':
						// Verwaltet die Titel der Themen denen die Bücher zugeordnet werden

						dg_show_header('Themen verwalten:');
						addnav('Zurück');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');
						addnav('','dg_main.php?op=in&subop=buildings&building_op=library&act=submit');

                        /** @noinspection PhpUndefinedVariableInspection */
                        $lvl = $guild['build_list'][DG_BUILD_BIBLI];

						output("<form action='dg_main.php?op=in&subop=buildings&building_op=library&act=submit' method='POST'>
						`&Eure Bibliothek befindet sich in Ausbaustufe $lvl. Deswegen dürfen $lvl Themen erstellt werden:`n`n");
						$i = 1;
						$arr_themes = $guild['building_vars']['bibliothek']['dg_book_themes'];
						
						while ($i <= $lvl) 
						{						
							output("Thema " . $i . ": <input type='Text' name='theme" . $i . "' value='" . str_replace("`","&#x0060;",$arr_themes[$i]) . "' size='30'> " . $arr_themes[$i] . "`&`n");
							$i++;
						}
						output("<input type='Submit' name='submit_edit_themes' value='Aktualisieren!'></form>");

						break;

					case 'edit_books':
						// Für Amtsinhaber zum bearbeiten oder löschen von Büchern

						dg_show_header('Bücher bearbeiten / deaktivieren / löschen');

						addnav('Zurück');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');

                        /** @noinspection PhpUndefinedVariableInspection */
                        dg_show_books($guild['guildid']);

						break;

					case 'edit_book':
						//bearbeitet ein einzelnes Buch nach Auswahl bei edit_books bzw $_GET['bookid']

						dg_show_header('Buch bearbeiten');

						addnav('Zurück');
						addnav('Zur Buchauswahl','dg_main.php?op=in&subop=buildings&building_op=library&act=edit_books');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');
						addnav('','dg_main.php?op=in&subop=buildings&building_op=library&act=submit');
						output("<form action='dg_main.php?op=in&subop=buildings&building_op=library&act=submit' method='POST'>
              			<input value='" . $_GET['bookid'] . "' name='bookid' type='hidden'>");

                        /** @noinspection PhpUndefinedVariableInspection */
                        $arr_themes = $guild['building_vars']['bibliothek']['dg_book_themes'];

						$sqla = "SELECT * FROM `dg_books` WHERE bookid=" . $_GET['bookid'];
						$rowa = db_fetch_assoc(db_query($sqla));

						output("`8Themenzuordnung: (bisher: " . $arr_themes[$rowa['theme']] . "`8)`n<select name='theme' size='1'>");

						if(!is_array($arr_themes))
						{
							$arr_themes = array();	
						}
						
						foreach ($arr_themes as $key=>$str_theme)
						{	
							if(is_null_or_empty($str_theme))
							{
								continue;
							}
							$arr_themes = $guild['building_vars']['bibliothek']['dg_book_themes'];
							output('<option value="'.$key.'" '.($rowa['theme']==$key?'selected':'').'>' . $arr_themes[$key]);
						}
						output("</select>`n`n
	              		Titel des Werks: <input type='Text' name='title' value='" . str_replace("`","&#x0060;",$rowa['title']) . "' size='40' maxlength='250'>`n`n
	              		Dein Wissen über dieses Thema:`n
	              		<textarea name='txt' class='input' cols='60' rows='20'>" . str_replace("`","&#x0060;",$rowa['txt']) . "</textarea>`n`n
	              		<input type='Submit' name='submit_edit_book' value='Speichern'>
	              		</form>");

						break;

					case 'show':

						addnav('Zurück');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');

                        /** @noinspection PhpUndefinedVariableInspection */
                        $arr_themes = $guild['building_vars']['bibliothek']['dg_book_themes'];

						dg_show_header('Liste der Bücher zum Thema: ' . $arr_themes[(int)$_GET['theme']]);
                        /** @noinspection PhpUndefinedVariableInspection */
                        dg_show_books($session['user']['guildid'],(int)$_GET['theme']);

						break;

					case 'show2':

						$sql = "SELECT theme FROM dg_books WHERE bookid = " . (int)$_GET['bookid'];
						$row = db_fetch_assoc(db_query($sql));
						$theme = $row['theme'];
						
						if($_GET['r']) 
						{
                            /** @noinspection PhpUndefinedVariableInspection */
                            set_restorepage_history($g_ret_page);
							redirect('dg_main.php?op=in&subop=buildings&building_op=library&act=show2&bookid='.(int)$_GET['bookid']);
						}
						
						$str_ret = get_restorepage_history();

						addnav('Zurück');
						addnav('Zur Buchauswahl',$str_ret );//'dg_main.php?op=in&subop=buildings&building_op=library&act=show&theme='.$theme);
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');

						dg_show_single_book((int)$_GET['bookid']);

						break;
					
					case 'edit_rights':
						dg_show_header('Zugriffsrechte verwalten');

						addnav('Zurück');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');
						
						if(isset($_POST['submit']))
						{
							if (is_array($_POST['edit_rights']))
							{
								$guild['building_vars']['bibliothek']['editrights'] = $_POST['edit_rights'];
							}	
						}
						
						$str_output = 'Wähle aus der folgenden Liste aus welche Gruppen aus deiner Gilde Bücher und Themen verwalten dürfen. Keine Sorge, du kannst dich selbst nicht aussperren.`n';
						
						$str_output .= form_header('dg_main.php?op=in&subop=buildings&building_op=library&act=edit_rights');
						$str_output .= '<select id="functions" name="edit_rights[]" size="6" multiple="multiple">';

                        /** @noinspection PhpUndefinedVariableInspection */
                        foreach($dg_funcs as $key => $arr_dg_func)
						{
							//Bestimmte Leute dürfen kein Recht erhalten.
							if($key==99 || $key==1)
							{
								continue;
							}
							if(!is_array($guild['building_vars']['bibliothek']['editrights']))
							{
								$guild['building_vars']['bibliothek']['editrights'] = array();	
							}
							$str_selected = (in_array($key,$guild['building_vars']['bibliothek']['editrights']))?'selected':'';
							$str_output .= '<option value="'.$key.'" '.$str_selected.'>'.$arr_dg_func[0].'</option>';
						}
						$str_output .= '</select><br />';
						$str_output .= '<input type="submit" name="submit" class="button" value="Speichern">';
						$str_output .= '</form>';
						
						output($str_output);
												
						break;
						
					case 'learn':
						/** @noinspection PhpUndefinedVariableInspection */
                        $lvl = $guild['build_list'][DG_BUILD_BIBLI];
                        /** @noinspection PhpUndefinedVariableInspection */
                        dg_show_header('Die Bibliothek ('.$dg_build_levels[$lvl].')');
		
						addcommentary();
		
						$allow_non_special = ($lvl > 3) ? true : false;
		
						$str_where=' WHERE active="1" ';
                        /** @noinspection PhpUndefinedVariableInspection */
                        if($session['user']['exchangequest']<30)
						{//Weisheit nur mit Brosche erlernbar
							$str_where.=' AND usename!="wisdom" ';
						}
						$sql = 'SELECT specname,specid,usename FROM specialty '.$str_where.' ORDER BY category,specid';
						$res = db_query($sql);
		
						$rowe = user_get_aei('seenacademy');
						
						if($_GET['subact'] == 'ok') {

                            /** @noinspection PhpUndefinedVariableInspection */
                            if((int)$_GET['price'] > $Char->gold) {
								output('`gLeider besitzt du nicht genügend Gold, weswegen dir der Mann die Benutzung der Bücher verweigert!');
							}
							else {
								$temp_spec = 0;
								if($session['user']['specialty'] != $_GET['spec']) 
								{
									$temp_spec = $session['user']['specialty'];
									$session['user']['specialty'] = $_GET['spec'];
								}
		
								$Char->gold -= (int)$_GET['price'];
		
								increment_specialty();
		
								if($temp_spec) {
									$session['user']['specialty'] = $temp_spec;
								}
		
								user_set_aei(array('seenacademy'=>1));
		
								output('`gZufrieden und mit rauchendem Kopf lehnst du dich zurück - die Schufterei hat etwas gebracht! Du klappst das Buch zu und machst dich auf den Rückweg.');
		
							}
						}
						else {
		
							output('`gMit dem festen Vorsatz heute etwas über deine besonderen Fähigkeiten zu lernen lenkst du deine Schritte zum Schreibtisch des Bibliothekars und machst auf dich aufmerksam. Als er aufblickt und dich fragend ansieht, erklärst du ihm, dass du dein Wissen über deine besonderen Fähigkeiten erweitern möchtest. Er schreibt dir in feiner Schrift mehrere Buchnummern auf ein Stück Pergament, zeigt dir den Weg und du machst dich, die Regalreihen genau abzählend, auf den Weg. Als du endlich vor den entsprechenden Büchern stehst, wirst du gleichzeitig vor die Wahl gestellt, welche Fähigkeit du vertiefen möchtest, denn du weißt, dass deine Zeit nur reicht, um eines dieser Bücher gründlich zu studieren ...   
							'.($rowe['seenacademy']?'Leider weißt du auch, dass dir heute nicht mehr danach ist, deine Fähigkeiten noch weiter zu üben.':'') );
		
							if($rowe['seenacademy'] == 0) {					
		
								while($spec = db_fetch_assoc($res)) {
									//Eigenes Special ist billiger und kriegt 25% Rabatt
                                    /** @noinspection PhpUndefinedVariableInspection */
                                    $bool_own_specialty = ($spec['specid'] == $Char->specialty);
									if($bool_own_specialty)
									{
										$float_discount = 0.75;
									}
									else 
									{
										$float_discount = 1;	
									}
									$int_act_magic_level = $Char->specialtyuses[$spec['usename']] + 1;
									$int_price = max(100,round( ( $int_act_magic_level*(675 - $lvl*100)*$float_discount) ) );
									
									if($bool_own_specialty || $allow_non_special)
									{
										$link = 'dg_main.php?op=in&subop=buildings&building_op=library&act=learn&subact=ok&spec='.$spec['specid'].'&price='.$int_price;
										output('
										`n`n
										<a href="'.$link.'">Stufe '.$int_act_magic_level.' '.$spec['specname'].' erlernen!</a> ('.$int_price.' Gold)
										',true);
										addnav('',$link);
									}
								}
							}

						}
		
						addnav('Zurück');
						addnav('Zur Halle','dg_main.php?op=in');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');
						break;

					case 'submit':
						// führt Änderungen in den Daten in der Tabelle durch

						if (isset($_POST['submit_new_book'])) {
							//HTML kommt raus
							$_POST['txt'] = dg_clean_books_html($_POST['txt']);
							// Ein soeben eingereichtes Buch wird in die Datenbank Tabelle dg_books gespeichert
                            /** @noinspection PhpUndefinedVariableInspection */
                            $sql = "INSERT INTO
                                                `dg_books` (`bookid`, `guildid`, `theme`, `acctid`, `author`, `activated`, `title`, `txt`)
                                                VALUES
                                                (null, '" . $session['user']['guildid'] . "', '" . db_real_escape_string($_POST['theme']) . "', '" . $session['user']['acctid'] . "', '" . db_real_escape_string($session['user']['name']) . "', '0', '" . db_real_escape_string($_POST['title']) . "', '" . db_real_escape_string($_POST['txt']) . "');";
							db_query($sql);

							dg_show_header('Buch wurde eingereicht');
							output('`8Vielen Dank, dein Buch wurde in die Gildenbibliothek eingereicht. Bis du es allerdings in den Regalen stehen sehen wirst, musst du dich wohl noch ein wenig gedulden bis ein Bibliothekar sich dein Werk genauer angesehen hat und es freigibt.');

							// Systemmail an den Lehrmeister (wenn Posten besetzt ist)
							$sql = 'SELECT acctid FROM accounts WHERE (guildfunc='.DG_FUNC_MEMBERS.' OR guildfunc='.DG_FUNC_LEADER.') AND guildid = ' . $session['user']['guildid'];
							$res = db_query($sql);
                while ($mailto = db_fetch_assoc($res)) 
								{
								systemmail($mailto['acctid'],'`b`gNeues Buch eingereicht`0`b',"`g".$session['user']['name']."`8 aus deiner Gilde hat soeben ein neues Buch eingereicht.`n`8Nun ist es an dir den Inhalt des Werkes `g".$_POST['title']." `8zu kontrollieren und es danach gegebenenfalls für alle zugänglich in die Regale zu stellen.");
								}

						} 
						elseif (isset($_POST['submit_book_goon'])) {
							//HTML kommt raus
							$_POST['txt'] = dg_clean_books_html($_POST['txt']);
							// Ein fortgesetztes Buch wird neu gespeichert, muss aber nicht von der Gilde neu freigegeben werden. Der Adminstatus ändert sich jedoch
							//fix bathi
							$sql = "UPDATE `dg_books` SET `txt` = '" . db_real_escape_string($_POST['txt']) . "', su_activated='0' WHERE `bookid` = " . intval($_POST['bookid']) . " LIMIT 1;";
							db_query($sql);

							dg_show_header('Buch wurde fortgesetzt');
							output('`8Vielen Dank, dein Buch wurde ergänzt und wird umgehend in die Regale zurück gestellt sobald die Tinte getrocknet ist...');

						} 
						elseif (isset($_POST['submit_edit_themes'])) {
							// Aktualisiert die Titel der Themen

                            /** @noinspection PhpUndefinedVariableInspection */
                            $lvl = $guild['build_list'][DG_BUILD_BIBLI];
							
							$i = 1;
							while ($i <= $lvl) {
								$guild['building_vars']['bibliothek']['dg_book_themes'][$i]=$_POST['theme' . $i];
								$i++;
							}
							dg_show_header('Themen Verwaltung');
							output('`8Die Kategorien wurden neu beschriftet.');

						} 
						elseif (isset($_POST['submit_edit_book'])) {
							// Bearbeitet ein existentes Buch

							//HTML kommt raus
							$_POST['txt'] = dg_clean_books_html($_POST['txt']);
							//fix by bathi
							$sql = "UPDATE `dg_books` SET `theme` = '" . db_real_escape_string($_POST['theme']) . "', `title` = '" . db_real_escape_string($_POST['title']) . "', `txt` = '" . db_real_escape_string($_POST['txt']) . "', su_activated='0' WHERE bookid = " . intval($_POST['bookid']) . " LIMIT 1;";
							db_query($sql);

							redirect('dg_main.php?op=in&subop=buildings&building_op=library&act=edit_books');

						} 
						elseif ($_GET['subact']=='activate_deactivate') {

							$sql = "UPDATE `dg_books` SET `activated` = IF(`activated`=0,1,0) WHERE `bookid` = " . (int)$_GET['bookid'] . " LIMIT 1;";
							db_query($sql);
							redirect('dg_main.php?op=in&subop=buildings&building_op=library&act=edit_books');

						} 
						elseif ($_GET['subact']=='del') {

							$sql = "DELETE FROM `dg_books` WHERE `bookid` = " . $_GET['bookid'] . " LIMIT 1;";
							db_query($sql);

							redirect('dg_main.php?op=in&subop=buildings&building_op=library&act=edit_books');

						}

						addnav('Zurück');
						addnav('In die Bibliothek','dg_main.php?op=in&subop=buildings&building_op=bibli');

						break;

				}
				break;
				
			case 'bibli':
				/** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_BIBLI];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Die Bibliothek ('.$dg_build_levels[$lvl].')');
				
				output('`gViele Wesen mit Büchern in den Händen kommen dir auf deinem Weg in die große Bibliothek entgegen. Du öffnest langsam die riesigen Flügeltüren aus Holz, trittst in die weitläufige Halle ein und hast das Gefühl, den Überblick zu verlieren. Der ganze Raum ist von Regalen durchzogen, in denen jedes Buch - genau beschriftet - ordentlich eingeordnet ist. Langsam gehst du den Mittelgang entlang, wirfst immer wieder rechts und links einen Blick in die Regalreihen, es gibt sicher kein Thema, zu dem hier nicht irgendeine Lektüre zu finden ist.`n  
				Am Ende des Ganges, hinter einem Schreibtisch, sitzt ein schon recht alt wirkender Mann, der dir aus seinem faltigen Gesicht entgegen schaut; `ier ermahnt dich, dass du Geheimnisse, die du in deinem Drachentöterleben gesammelt hast, lieber für dich behälst.`i Du weißt, dass er jedes Buch, das in den unzähligen Regalreihen steht, kennt und dir weiterhelfen kann, wenn du selbst nicht findest, was du suchst.`n  
				In einer anderen Ecke der Halle stehen einige Tische und Sitzgelegenheiten, damit man gleich vor Ort etwas in aller Ruhe nachschlagen oder sich Notizen anfertigen kann. `n`n`bRegeln der Gildenbibliothek:`b`n`n In die Gildenbibliothek kommen alle Bücher, die für die Gilde relevant sind. So können sich in dieser Bibliothek Abhandlungen über die Geschichte der Gilde, RP-Plots, Charaktervorstellungen usw. finden. Die Gildenbibliothek ist vor allem ein RP-Element und alle Bücher, die mehr als Hinweise auf Level-Elemente wie die Erringung von Malen usw.enthalten, werden der Fairness halber von den zuständigen Mitgliedern des Teams gesperrt.`n`n
				`c
				<table>
					<tr>
						<td style="min-width:150px;">`&`bThemen:`b`0</td><td>`&`bBücher:`b`0</td>
					</tr>');
					//für jedes erlaubte Thema, je nach Ausbaufortschritt, die Titel der Themen anzeigen
				$lvl = $guild['build_list'][DG_BUILD_BIBLI];
                /** @noinspection PhpUndefinedVariableInspection */
                while ($i <= $lvl) {
					$sql1 = "SELECT bookid FROM `dg_books` WHERE guildid='" . $guild['guildid'] . "' AND theme='$i' AND activated='1' AND su_activated!='2'";
					$count = db_num_rows(db_query($sql1));
							
					$arr_themes = $guild['building_vars']['bibliothek']['dg_book_themes'];
						
					if(!is_null_or_empty($arr_themes[$i]))
					{
                        /** @noinspection PhpUndefinedVariableInspection */
                        $int_count++;
						output("
						<tr>
							<td>`n<a href='dg_main.php?op=in&subop=buildings&building_op=library&act=show&theme=".$i."'>" . $arr_themes[$i] . "</a></td>
							<td align='center'>`n" . $count . "</td>
						</tr>");
						addnav('','dg_main.php?op=in&subop=buildings&building_op=library&act=show&theme='.$i);
					}
				
					$i++;
				}
				if($int_count == 0)
				{
					output("<tr><td colspan='2'>Es wurden noch keine Kategorien von der Gildenleitung definiert</td></tr>");
				}	
				output('</table>`c`n`n`n');

                /** @noinspection PhpUndefinedVariableInspection */
                viewcommentary('guild-'.$gid.'_lib','Sprechen:',25,'spricht',false,true,false,getsetting('chat_post_len_long',1500),false,true,2);

				addnav('Zurück');
				addnav('Zur Halle','dg_main.php?op=in');
				addnav('Fähigkeiten erlernen','dg_main.php?op=in&subop=buildings&building_op=library&act=learn');

				//Welcher User hat Editrechte (Festgelegt vom Gildenadmin)
                /** @noinspection PhpUndefinedVariableInspection */
                $bool_editrights = (is_array($guild['building_vars']['bibliothek']['editrights']) && in_array($session['user']['guildfunc'], $guild['building_vars']['bibliothek']['editrights'])) ? true: false;

				//Adminrechte hat nur der Gildenführer und ein Grottenolm
                /** @noinspection PhpUndefinedVariableInspection */
                $bool_adminrights = ($session['user']['guildid'] == $guild['guildid'] && $session['user']['guildfunc'] == DG_FUNC_LEADER) || $access_control->su_check(access_control::SU_RIGHT_EDITORLIBRARY);
				
				//Verwalten dürfen stets die Gildenführung, Lehrmeister und Leute mit SU Recht für den Editor oder Mitglieder
				$bool_editrights = $bool_editrights || ($session['user']['guildfunc'] == DG_FUNC_LEADER || $session['user']['guildfunc'] == DG_FUNC_MEMBERS || $access_control->su_check(access_control::SU_RIGHT_EDITORLIBRARY ));				
				
				//Entfernen um scharf zu schalten
				if ($bool_editrights) {
					// nur für Gildenführer & Lehrmeister
					addnav('Verwaltung');
					addnav('Themen bearbeiten','dg_main.php?op=in&subop=buildings&building_op=library&act=edit_themes');
					if($bool_adminrights) addnav('Verwaltungsrechte','dg_main.php?op=in&subop=buildings&building_op=library&act=edit_rights');
				}
				addnav('Neues Buch','dg_main.php?op=in&subop=buildings&building_op=library&act=new_book');
				addnav('Buch fortsetzen','dg_main.php?op=in&subop=buildings&building_op=library&act=book_goon');

				if ($bool_editrights) {
					// nur für Gildenführer & Lehrmeister
					addnav('Bücher bearbeiten','dg_main.php?op=in&subop=buildings&building_op=library&act=edit_books');
				}

				break;	// End bibli

			case 'labor':

				// Zaubertränke herstellen für
				// Angriff (ab lvl 4), Def (ab lvl 1)
				// Je höher Lvl, desto mehr Prozent Zuwachs
				/** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_LABOR];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Das Alchemielabor ('.$dg_build_levels[$lvl].')');

				$name = $_GET['what'] == 'angr' ? 'Angriff' : 'Verteidigung';

				$price_gold = 1000;
				$impr = round(pow(1.05,$lvl),2) * 100;

				if($_GET['act'] == 'ok') {

                    /** @noinspection PhpUndefinedVariableInspection */
                    if($price_gold > $session['user']['gold']) {
						output('`V"Hat es noch nicht einmal Gold dabei, ist denn das zu glauben.. Was für ein nutzloses Wesen, nein, halt inne,.. ist das da nicht die fehlende Zutat?"`5 bei diesen Worten schaut er dich derart bedrohlich an, dass du es für besser hältst, zu verschwinden.');
					}
					else {
						$what = $_GET['what'];

						// Zu bereits vorhandenen Zaubern der selben Art dazuzählen
						$arr_item = item_list_get(' owner='.$session['user']['acctid'].' AND i.tpl_id="trnk'.$what.'" ','',false,'*',true);
						$count = 0;
						
						foreach($arr_item as $item){
							$count += $item['value1'];
		
						}
						
						//if($count > 0) {

							if($count >= 5) {
								output('`R"Es bekommt wohl gar nich genug? Seine Taschen beulen schon vor Tränken!"');
							}
							else {
								//item_set(' id='.$item['id'], array('value1'=>$item['value1']+1,'hvalue2'=>$impr,'gold'=>$item['gold']+($price_gold*0.8)) );
								
								$item['tpl_description'] = '`7Dieser exklusiv in der Gilde '.$guild['name'].'`7 gebraute Zaubertrank stärkt '.$name.' für eine gewisse Zeit.';
								$item['tpl_value1'] = 1;
								$item['tpl_value2'] = 1;
								$item['tpl_hvalue2'] = $impr;
								$item['tpl_gold'] = $price_gold*0.8;

								item_add($session['user']['acctid'],'trnk'.$what,$item);
								
								$session['user']['gold'] -= $price_gold;

								output('`R"Erbärmlich für welch Grabszeuch manche Wesen doch Golddd ausgeben.. "`5 der Goblin keucht heftig, wirft dir den Zaubertrank zu und rührt weiter in seinem Kessel, ohne dich zu beachten.');
							}
						//}
//						else {
//
//							$item['tpl_description'] = '`7Dieser exklusiv in der Gilde '.$guild['name'].'`7 gebraute Zaubertrank stärkt '.$name.' für eine gewisse Zeit.';
//							$item['tpl_value1'] = 1;
//							$item['tpl_value2'] = 1;
//							$item['tpl_hvalue2'] = $impr;
//							$item['tpl_gold'] = $price_gold*0.8;
//
//							item_add($session['user']['acctid'],'trnk'.$what,$item);
//
//							$session['user']['gold'] -= $price_gold;
//
//							output('`R"Erbärmlich für welch Grabszeuch manche Wesen doch Golddd ausgeben.. "`5 der Goblin keucht heftig, wirft dir den Zaubertrank zu und rührt weiter in seinem Kessel, ohne dich zu beachten.');
//						}

					}
				}	// END ok

				else {

					output('`5Du betrittst ein offenkundiges Arbeitszimmer, schwach erleuchtet von Öllampen, deren Schwaden die Luft stickig werden lassen und der beißende Geruch allerlei alchemistischer Tinkturen und Tränke lässt dir die Augen tränen. Benommen siehst du dich in dem kleinen Raum etwas genauer um und dein Blick schweift über Wände, bedeckt mit Regalen, voll von alten, staubigen Pergamenten und Büchern, kleinen Fläschchen und Phiolen, sowie allerlei Kräuter und Ingredentien. In der Mitte des Raumes befindet sich ein kleiner Tisch, an dem ein noch kleinerer Gnom vor einem Kessel hockt und hin und wieder kichernd und vor sich hin brabbelnd die eine oder andere Zutat nachwirft.
						Als schließlich auch Goblinaugen im Kessel verschwinden, lässt du ein kurzes Räuspern hören.
						`R"Was will es hier? Was stört es mich?"`5 brabbelt dir die kleine Kreatur entgegen ohne dich anzusehen. `R"Es wird wohl kaum für meine Zwergenbart-Suppe gekommen sein, also sprich!"
					`5');

					$percent = $impr - 100;

					$link = 'dg_main.php?op=in&subop=buildings&building_op=labor&act=ok&what=def';
					output('`n`n<a href="'.$link.'">Verteidigungszaubertrank brauen!</a> `0('.$price_gold.' Gold, `R"Derr bietet dirr `^'.$percent.'`R Prrrozent Zuwachs!"`0)',true);
					addnav('',$link);

					if($lvl > 3) {
						$link = 'dg_main.php?op=in&subop=buildings&building_op=labor&act=ok&what=angr';
						output('`n`n<a href="'.$link.'">Angriffszaubertrank brauen!</a> `0('.$price_gold.' Gold, `R"Derr bietet dirr `^'.$percent.'`R Prrrozent Zuwachs!"`0)',true);
						addnav('',$link);
					}

				}

				addnav('Zur Halle','dg_main.php?op=in');

				break;	// End alchemie

			case 'gift':

				// Ermöglicht direkten Zugriff auf Hausschatz, ohne gegen Wache etc. kämpfen zu müssen
				// Kostet Gold, PvP-Kämpfe und Ansehen
				// Mit gewisser Wahrscheinlichkeit: Tod und Expverlust
				/** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_GIFT];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Die Giftmischerei ('.$dg_build_levels[$lvl].')');

				$price_gold = ($lvl > 3) ? 500 : 750;
				$price_pvp = 2;
				$price_turns = 0;

				if($_GET['act'] == 'ok') {

					$hid = (int)$_POST['hid'];

                    /** @noinspection PhpUndefinedVariableInspection */
                    if(db_num_rows(db_query('SELECT id FROM keylist WHERE value1='.$hid.' AND owner='.$session['user']['acctid'])) || $session['user']['house'] == $hid) {
						output('`@Es gibt einfachere Methoden, um in dieses Haus zu gelangen.. Versuchs doch mal mit einem Schlüssel!');
					}
					else {

						$sql = 'SELECT housename,description,a.name,h.gold,h.gems FROM houses h LEFT JOIN accounts a ON a.acctid=owner WHERE houseid='.$hid;
						$res = db_query($sql);
						if(db_num_rows($res) == 0) {
							output('`@Ein Haus mit dieser Nummer existiert nicht!');
							addnav('Neue Suche','dg_main.php?op=in&subop=buildings&building_op=gift');
						}
						else {
							if($price_gold > $session['user']['gold']) {
								output('`@Verärgert stellst du fest, dass der Preis deine finanziellen Möglichkeiten übersteigt.');
							}
							else {
								if($price_pvp > $session['user']['playerfights']) {
									output('`@Heute hast du bereits zu viele deiner Spielerkämpfe aufgebraucht. Da lässt sich nichts mehr machen.');
								}
								elseif($price_turns > $session['user']['turns']) {
									output('`@Du verfügst leider über keinen Waldkampf mehr!');
								}
								else {
									$house = db_fetch_assoc($res);

									$session['user']['gold'] -= $price_gold;
									$session['user']['playerfights'] -= $price_pvp;
									$session['user']['turns'] -= $price_turns;

									output('`@Du holst die Phiole mit dem Gift hervor. Der Gestank betäubt dich selbst durch das Glas noch erheblich. Dann ziehst du langsam den Korken aus der Öffnung..');

									$min = 48 - 5 * pow(1.3,$lvl);
									if(e_rand(1,100) >= $min) {

										output('`n`n`@Tatsächlich! Es klappt: Du schläferst die patrouillierende Stadtwache ein und steigst durch ein Fenster ins Haus..`nLeider verbreiten sich schon bald Gerüchte über deine Giftbrauerei. Dein Ansehen sinkt!');
										$session['housekey'] = $hid;
										$session['user']['reputation'] -= 20;

//										addnews($session['user']['name'].'`7 machte erfolgreich Gebrauch von der Giftmischerei seiner Gilde.');

										addnav('Einsteigen..','houses_pvp.php?op=einbruch2&hidden=1&id='.$hid);

									}
									else {

										if(e_rand(1,5) == 1) {
											output('`n`n`@.. da merkst du auch schon, wie dir schwindlig wird und du zu Boden sinkst.`nDu bist tot und verlierst '.(round($session['user']['experience']*0.1)).' Erfahrung!');

											killplayer(0, 10, 0, '');

											addnews($session['user']['name'].'`3 hat in der Giftmischerei '.($session['user']['sex'] ? 'ihrer':'seiner').' Gilde leider den falschen Trank erwischt..');
											addnav('Zu den News','news.php');
										}
										else {
											output('`n`n`@.. da merkst du auch schon, wie dir auf einmal speiübel wird.`nDu verlierst fast alle Lebenspunkte!');
											$session['user']['hitpoints'] = 1;
										}
									}
								}	// END genug Kämpfe
							}
						}	// END haus gefunden
					}	// END fremdes haus
				}

				else {

					$link = 'dg_main.php?op=in&subop=buildings&building_op=gift&act=ok';
					output('`@Aus dieser Ecke der Gilde treiben dir schon von weitem gelbgrüne Schwaden entgegen. Ein penetranter Geruch nach Schwefel lässt dich fast taumeln. Zu deiner Rechten köcheln verschiedenste
							Töpfe und Kessel munter vor sich hin. Dies ist die Giftmischerei: Deine Alchemisten werden dir hier vorzüglichste Tränke brauen, um jede noch so starke Stadtwache und Haustier in sanften Schlummer zu hüllen.`n
							Jedoch kostet dieses Unterfangen `^'.$price_gold.'`@ Gold und `^'.$price_pvp.'`@ Spielerkämpfe. '.($price_turns > 0 ? 'Einen Waldkampf sowieso. ':'').'Auch ist es nicht gewiss, ob das Gift nicht so stark ist, dass es dich selbst tötet!`n`n');
					output('<form action="'.$link.'" method="POST">Hausnr.: <input name="hid" type="text" size="5" maxlength="5"> <input type="submit" value="Wachen einschläfern!"></form>',true);

					addnav('',$link);

				}

				if($session['user']['hitpoints'] > 0) {addnav('Zur Halle','dg_main.php?op=in');}

				break;	// End giftmsicherei

			case 'kontor':

				// Erste 3 Lvl gibt es nur Rabatte auf:
				//  - Wanderhändler
				//  - Mighty E / Zauberladen bei den Magiern
				// Ab Lvl 4:
				//	- Zauberladen
				// Ab Lvl 7:
				//  - Pegasus
				// dabei bringt jeweils ein Lvl 1 % Rabatt
				/** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_KONTOR];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Das Handelskontor ('.$dg_build_levels[$lvl].')');


                /** @noinspection PhpUndefinedVariableInspection */
                $arr = array('Pegasus'=>dg_calc_boni($gid,'rebates_armor',0),
								'Wanderhändler'=>dg_calc_boni($gid,'rebates_vendor',0),
								'Thorim'=>dg_calc_boni($gid,'rebates_weapon',0),
								'Zauberladen'=>dg_calc_boni($gid,'rebates_spells',0));

				output('`^Das sogenannte Handelskontor entpuppt sich als ein riesiger Lagerraum. Wo der ohnehin rare Platz nicht durch Säcke und Kisten belegt ist, drängen sich emsige Arbeiter, die ebendiese Waren durch die Gegend schleppen. An der Wand ist ein kleiner Raum für eine Tafel freigehalten. Auf dieser stehen in klaren Lettern sämtliche Rabatte geschrieben, die die Gilde auf dem derzeitigen Markt bekommt:`n`n');

				$out = '`c<table bgcolor="#999999" border="0" cellpadding="3" cellspacing="1"><tr class="trhead"><td>Händler</td><td>Rabatt</td></tr>';
				$i=0;

				foreach($arr as $name=>$reb) {
					$i++;
					$out .= '<tr class="'.($i%2?"trlight":"trdark").'"><td>`b`@'.$name.':`b </td><td>`^'.$reb.' %</td></tr>';
				}
				$out .= '</table>`c';
				output($out,true);

				addnav('Zur Halle','dg_main.php?op=in');

				break;	// End kontor

			case 'opfer':

				// Opferstätte bringt der Gilde Punkte.. und dem Opfer den Tod ;)
				$lvl = 0;//$guild['build_list'][DG_BUILD_OPFER];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Die Opferstätte ('.$dg_build_levels[$lvl].')');

				$points = round($lvl * 0.8);
				$exp_loose = 0.85 + max($lvl * 0.01,0.1);

				if($_GET['act'] == 'ok') {

					$session['user']['alive'] = 0;
					$session['user']['hitpoints'] = 0;
					$session['user']['gravefights'] = 0;
					$session['user']['experience'] *= 0.9;
					addnews($session['user']['name'].'`2 brachte seinem Guru voll Hingabe ein Menschenopfer dar - leider bemerkte er zu spät, WER das Opfer sein sollte.');
					output('`$Ungeduldig seufzend lässt du dich auf den Stein sinken, der sehr glitschig ist - und auch noch abfärbt! Du blickst der einen Gestalt ins Gesicht und beschwerst dich über die Arbeitsbedingu.. Als letzter Gedanke (kurz bevor du dich gewundert hast, seit wann dein Kopf fliegen kann) fällt dir ein, was du dich vorhin gefragt hast: Wieso dir niemand sagen konnte, ob das Opfern ein guter Job ist.. Das kostet dich Erfahrung!`n`nDeine Gilde erhält dafür '.$points.' Gildenpunkte.');
					addnav('Ich fühl mich so tot..','news.php');
                    /** @noinspection PhpUndefinedVariableInspection */
                    $guild['points'] += $points;

				}
				else {
					output('`$Mit stolzgeschwellter Brust schreitest du durch einen nicht enden wollenden, abschüssigen Gang auf ein helles Licht zu. Hier werden Opferungen vorgenommen, hat man dir erzählt. Und deine Gilde bekommt dafür wertvolle Macht!
							Wieso also solltest du nicht auch deinen Teil beitragen und beim Opfern helfen? Während du durch das Portal trittst und den blutverschmierten Opferstein betrachtest, spukt dir ein bestimmter Gedanke durch den Kopf. Leider ist dieser immer noch
							sehr in Mitleidenschaft gezogen.. Verdammte Zauberkräuter. Was dir dein Guru da aufgeschwatzt hat, kann ja gar nicht gesund sein. Egal. Die Stimme, die da schreit, sie kommt dir bekannt vor.. irgendwoher. Und wie der schreit.. als würde er geopfert, und nicht irgendein Schaf! Oder so, du hast nicht wirklich
							eine Ahnung, was Opfern angeht. Aber das kann ja noch werden. Diese Schreie.. wird ja immer schlimmer. Irgendwas müssen die falsch machen. Schluss. Aus. Du zeigst es ihnen jetzt, wie das geht!');

                    /** @noinspection PhpUndefinedVariableInspection */
                    if($session['user']['level'] == 1 && $session['user']['level'] == 15) {
						output('`nKomisch.. in deiner Magengegend meldet sich ein Gefühl.. es will dir so etwas sagen, wie.. dein Level.. passt.. *grummel*... nicht zum Rest..');
					}
					else {
						output('`nLeicht schwankend kommst du vor einem Stein zu stehen. Der Stein hat eine Färbung, rot, so ähnlich wie Blut. Daneben stehen zwei bullige Typen, gekleidet in Nachthemden. Die haben die gleiche Farbe wie der Stein. Und was dir auch schon wieder absolut unverständlich ist:
						Was soll dieses Grinsen? Du hast ihnen schließlich sogar das Angebot gemacht, beim Opfern zu helfen. Während der eine unkontrolliert in sein Hemd prustet, fordert dich der andere auf, doch bitte auf dem Stein Platz zu nehmen, das Opfer komme gleich.');
						$link = 'dg_main.php?op=in&subop=buildings&building_op=opfer&act=ok';
						addnav('',$link);
						output('`n`n<a href="'.$link.'">Ja, ich will warten!</a>',true);
					}

					addnav('Zur Halle','dg_main.php?op=in');
				}

				break;	// End opfer

			case 'altar':

				// Altar lässt Gildenführer Massensegen vergeben
				/** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_ALTAR];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Der Altar ('.$dg_build_levels[$lvl].')');
				$points = 3;
				$impr = pow(1.2,$lvl);	// exponentielle Steigerung

				if($_GET['act'] == 'ok') {

					if($points > $guild['points']) {
						output('`#Der Altar lässt nur ein trockenes Knirschen vernehmen. Wahrscheinlich reichen die Punkte deiner Gilde nicht für ein solches Wunder!');
					}
					else {

                        /** @noinspection PhpUndefinedVariableInspection */
                        user_update(
							array
							(
								'hitpoints'=>array('sql'=>true,'value'=>'ROUND(hitpoints*'.$impr.')'),
								'where'=>'guildid='.$gid.' AND guildfunc!='.DG_FUNC_APPLICANT
							)
						);

                        /** @noinspection PhpUndefinedVariableInspection */
                        $session['user']['hitpoints'] *= $impr;

						dg_massmail($gid,'`@Ein Wunder!','`2Der Guru deiner Gilde hat ein gigantisches Wunder bewirkt, das zwar einige Gildenpunkte gekostet, dafür aber auch dir neue Lebenskraft gegeben hat!');

						//dg_addnews($guild['name'].'`2 hat sich entschlossen, einige Gildenpunkte gegen neue Kraft einzutauschen!');
						insertcommentary(1,'/msg `2'.$session['user']['name'].'`2 hat sich entschlossen, einige Gildenpunkte gegen neue Kraft einzutauschen!','guild-'.$gid);

						output('`#Erschrocken stolperst du erstmal einige Schritte zurück. Die Kerzen flackern auf, im dichten Rauch kannst du Schemen erkennen (zumindest deine Phantasie) und ein Heulen ist zu hören, wie als würde der Wind durch Mauerritzen pfeifen.`n
								Es hat funktioniert! Du fühlst neue Lebenskraft in dir, genau wie all deine Mitstreiter.');

						$guild['points_spent'] += $points;
						$guild['points'] -= $points;
					}

				}
				else {
					output('`#Hübsch, ist dein erster Gedanke beim Anblick des Altars: Goldene und silberne Kelche, Kerzen, Geschnitzte Figuren von nackten Dämonen oder Engeln (Das kannst du nicht so genau ausmachen), in alle Richtungen drehbare Kreuze und Pentagramm-Schablonen zum Selbermalen. Eben alles, was so zu einem gescheiten Altar dazugehört!`n
							Du ahnst schon, damit lässt sich bestimmt was anfangen. Direkt daneben hängt ein vergilbtes Pergament: Lieber Kunde! Wir gratulieren Euch zum Kauf dieses einzigartigen Altars. Er eignet sich für jede Art von rituellem Massenereignis, sei es Liebeszauber oder Stinkmorchelfluch. Bitte nicht vergessen: Wir garantieren für keinerlei Funktionstüchtigkeit!`nGez. Königl. Zauberhafte Altarmanufaktur.');

					$link = 'dg_main.php?op=in&subop=buildings&building_op=altar&act=ok';
					addnav('',$link);
					output('`n`n<a href="'.$link.'">Lasst uns ein Lebenskraftwunder bewirken!</a> ('.$points.' Gildenpunkte)',true);

				}

				addnav('Zur Halle','dg_main.php?op=in');

				break;	// End altar

			case 'stall':
				define('DG_STALL_RENAME_COST',3);

                /** @noinspection PhpUndefinedVariableInspection */
                $lvl = $guild['build_list'][DG_BUILD_STALL];
                /** @noinspection PhpUndefinedVariableInspection */
                dg_show_header('Der Tierstall ('.$dg_build_levels[$lvl].')');

				$arr_names = $guild['building_vars']['stall']['names'];
				$arr_animals = array(
					'goldschaf' => array('name'=>(!empty($arr_names['goldschaf']) ? $arr_names['goldschaf'] : '`^Goldschaf`0'),'minlvl'=>1,'desc'=>'Das `^Goldschaf `& sucht für dich im Wald nach zusätzlichen Goldvorkommen. "Mähhh"','goldprice'=>399,'gemprice'=>0,'effectmsg'=>'`^Dein Goldschaf scharrt mit seinen Hufen gut verborgene Münzen frei!`0','wearoff'=>'`^Dein Goldschaf trabt blökend davon.`0','rounds'=>30,'goldfind'=>1.7,'oname'=>'Goldschaf')
					,'beutegeier' => array('name'=>(!empty($arr_names['beutegeier']) ? $arr_names['beutegeier'] : '`6Beutegeier`0'),'minlvl'=>2,'desc'=>'Der `6Beutegeier`& rafft Beutestücke an sich. "Krrrrr"','goldprice'=>999,'gemprice'=>2,'effectmsg'=>'`6Dein Beutegeier pickt mit einem heiseren Krächzen auf deinem Gegner herum, um ihm ein Beutestück zu entlocken!`0','wearoff'=>'`6Dein Beutegeier schwingt sich schwerfällig in die Lüfte.`0','rounds'=>20,'failmsg'=>'`6Leider ist der Beutegeier erfolglos..`0','cname'=>$arr_names['beutegeier'],'oname'=>'Beutegeier')
					,'gemelster' => array('name'=>(!empty($arr_names['gemelster']) ? $arr_names['gemelster'] : '`7Edelsteinelster`0'),'minlvl'=>3,'desc'=>'Die `7Edelsteinelster`& ist ein raffiniertes Biest, das im verlassenen Schloß noch den kleinsten Glitzer aufspürt.','goldprice'=>1199,'gemprice'=>2,'effectmsg'=>'`7Deine Edelsteinelster hüpft mit einem Glitzern in den Augen herum und hält Ausschau nach Gemmensteinen!`0','wearoff'=>'`7Deine Edelsteinelster flattert davon.`0','rounds'=>10,'failmsg'=>'`6Leider ist die Elster erfolglos..`0','cname'=>$arr_names['gemelster'],'oname'=>'Edelsteinelster')
					);

				// Tier mitnehmen
				if($_GET['act'] == 'get') {

					$str_animal = $_GET['animal'];
					$ok = true;

					output('`&Neugierig zeigst du auf die Stalltür, hinter der sich '.$arr_animals[$str_animal]['name'].'`& verbirgt!');

					foreach($arr_animals as $animal => $a) {
                        /** @noinspection PhpUndefinedVariableInspection */
                        if($session['bufflist'][$animal]) {
							output('`n`$'.$a['name'].'`$ hinter dir würde sich wohl kaum mit '.$arr_animals[$str_animal]['name'].'`$ vertragen!');
							$ok = false;
						}
					}

					if($session['user']['gold'] < $arr_animals[$str_animal]['goldprice'] || $session['user']['gems'] < $arr_animals[$str_animal]['gemprice']) {
						output('`n`$Beschämt musst du feststellen, dass deine Besitztümer nicht ausreichen, um das Futter für '.$arr_animals[$str_animal]['name'].'`$ bezahlen zu können!');
						$ok = false;
					}

					if($ok) {
						output(' Kurz darauf führst du deinen Begleiter an einer langen, reißsicheren Leine nach draußen.');

						$session['user']['gold'] -= $arr_animals[$str_animal]['goldprice'];
						$session['user']['gems'] -= $arr_animals[$str_animal]['gemprice'];

						$guild['building_vars']['stall'][$str_animal] = $session['user']['acctid'];

						$session['bufflist'][$str_animal] = $arr_animals[$str_animal];
					}


				}
				else if($_GET['act'] == 'drop') {	// Tier abgeben

					$str_animal = $_GET['animal'];

					output('`&Widerwillig lässt du '.$arr_animals[$str_animal]['name'].'`& im Stall zurück.');

                    /** @noinspection PhpUndefinedVariableInspection */
                    unset($session['bufflist'][$str_animal]);
					$guild['building_vars']['stall'][$str_animal] = 0;

				}
				else if($_GET['act'] == 'rename') {	// Tiere benennen

					// Speichern
					if($_GET['save']) {

						$session['msg'] = '';

						foreach($arr_animals as $animal => $a) {
							if($_POST[$animal.'_taufe'] && $_POST[$animal] != $a['name']) {
								if($guild['points'] >= DG_STALL_RENAME_COST) {
									$str_name = strip_appoencode(mb_substr($_POST[$animal],0,40),2);

									$guild['building_vars']['stall']['names'][$animal] = $str_name;
                                    /** @noinspection PhpUndefinedVariableInspection */
                                    dg_commentary($gid,'/msg`2Gildenführer '.$session['user']['login'].' hat soeben in einer mehr oder minder feierlichen Zeremonie '.$a['oname'].'`2 auf den Namen '.$str_name.'`2 getauft!','',1);
									$guild['points'] -= DG_STALL_RENAME_COST;
									$session['msg'] .= '`2Mit dem Spruch, den du vom guten alten Merick aufgeschnappt hast, versuchst du '.$a['oname'].'`2 zu taufen:`n
											`3"Und im Namen von Epona, Fury und Lassie taufe ich dich auf den Namen... '.$str_name.'`3!"`n`n';
								}
								else {
									$session['msg'] .= '`2Leider verfügt die Gilde nicht über genügend Gildenpunkte, um '.$a['oname'].' zu taufen..`n`n';
								}

							}
						}

						dg_save_guild();
						redirect('dg_main.php?op=in&subop=buildings&building_op=stall&act=rename&saved=1');

					}
					else if($_GET['saved']) {
                        /** @noinspection PhpUndefinedVariableInspection */
                        output($session['msg']);
						unset($session['msg']);
					}

					output('`n`2Wie du erkennst, verfügt der Stall über einen eigenen, eher provisorisch eingerichteten Altar, auf dem alles bereitliegt, was man eben so für eine Goldschaftaufe braucht..`n
							Du weißt, dass pro Taufe `^'.DG_STALL_RENAME_COST.'`2 Gildenpunkte fällig werden! Lasse die Namenstafeln leer, um den Standardnamen wiederherzustellen. Erlaubt sind nur Farbcodes, keine Sonderzeichen.`n
							Wenn du die Umbenennung eurer Gildentiere nun durchführen möchtest, so kannst du mit den auf dem Altar
							bereitliegenden Mittelchen üben, ehe du ein Häkchen vor den Namen setzt und die Taufe vollendest:`n`n');

					$str_lnk = 'dg_main.php?op=in&subop=buildings&building_op=stall&act=rename&save=1';
					addnav('',$str_lnk);

					$arr_form = array();

					foreach($arr_animals as $animal => $a) {
						$arr_form[$animal.'_taufe'] = 'Taufe für '.$a['oname'].' durchführen?,checkbox,1';
						$arr_form[$animal] = $a['oname'].' '.$a['name'].'`0 umtaufen in:,text,40';
						$arr_form[$animal.'_pr'] = 'Vorschau:,preview,'.$animal;
					}

					output('<form method="POST" action="'.$str_lnk.'">',true);
					showform($arr_form,$guild['building_vars']['stall']['names'],false,'Taufen!');
					output('</form>',true);

					addnav('Zurück zum Stall','dg_main.php?op=in&subop=buildings&building_op=stall');

				}
				else {	// Startbildschirm

					output('`5Dies ist der Stall deiner Gilde, eine Bretterbude, der Boden ist mit festgestampftem
							Stroh bedeckt, in abgetrennten Verschlägen schnauben die Tiere.
							`nHier hast du die Möglichkeit, seltene Exemplare der Tierwelt mit dir zu nehmen!');

                    /** @noinspection PhpUndefinedVariableInspection */
                    if($leader) {
						output('`nDir als Gildenführer ist außerdem die Ehre vorbehalten, gegen ein paar Gildenpunkte
								die Tiere im Namen deiner Gilde zu taufen und ihnen so eine sehr individuelle Note zu verleihen..');
						addnav('`2Tiere taufen`0','dg_main.php?op=in&subop=buildings&building_op=stall&act=rename');
					}


					foreach($arr_animals as $str_animal => $arr_info) {

						$usedby = '';

                        /** @noinspection PhpUndefinedVariableInspection */
                        if($session['bufflist'][$str_animal]) {
							addnav(''.$arr_info['name'].' zurückbringen!`0','dg_main.php?op=in&subop=buildings&building_op=stall&act=drop&animal='.$str_animal);
						}

						if($lvl >= $arr_info['minlvl']) {

							output('`n`n`&'.$arr_info['desc'].'`&');

							$str_usedby = '';

							if($guild['building_vars']['stall'][$str_animal] > 0) {

								$sql = 'SELECT bufflist,name,loggedin,activated,laston,acctid FROM accounts WHERE acctid='.$guild['building_vars']['stall'][$str_animal];
								$user = db_fetch_assoc(db_query($sql));

								$user['bufflist'] = utf8_unserialize($user['bufflist']);

								if(isset($user['bufflist'][$str_animal])) {

									$online = user_get_online(0,$user);

									if($online) {

										$usedby = $user['name'];

									}
									else {

										unset($user['bufflist'][$str_animal]);
										user_update(
											array
											(
												'bufflist'=>db_real_escape_string(utf8_serialize($user['bufflist']))
											),
											$user['acctid']
										);

									}

								}
								else {	// Buff bereits abgelaufen

									$guild['building_vars']['stall'][$str_animal] = 0;

								}

							}	// END Tier in use

							if($session['bufflist'][$str_animal]) {
								output('`n`&Du selbst hast '.$arr_info['name'].'`& noch bei dir.');
							}
							else if($usedby != '') {
								output('`n`&Doch leider ist '.$arr_info['name'].'`& gerade mit '.$usedby.'`& unterwegs! Du wirst wohl noch warten müssen.`n');
							}
							else {
								$link = 'dg_main.php?op=in&subop=buildings&building_op=stall&act=get&animal='.$str_animal;
								addnav($arr_info['oname'].' mitnehmen',$link);
								addnav('',$link);
								output('`n'.$arr_info['name'].'`& <a href="'.$link.'">mitnehmen</a> ('.$arr_info['goldprice'].' Gold'.($arr_info['gemprice']>0 ? ' '.$arr_info['gemprice'].' Edelsteine' : '').') !`n',true);
							}

						}
					}	// END foreach

				}	// END wenn keine aktion

				addnav('Zurück');
				addnav('Zurück zur Halle','dg_main.php?op=in');

				break;	// END stall

			// END AUSBAUTEN

	}	// END switch building_op

?>
