<?php
/**
 * alchemie.php: Alchemie- / Schmelztiegel-Anbau für Häuser
 * @author talion <t@ssilo.de>
 * @version DS-E V/3
*/


function house_ext_alchemie ($str_case, $arr_ext, $arr_house) {

	global $session,$g_arr_house_extensions,$item_hook_info;

	$str_base_file = 'house_extensions.php?_ext_id='.$arr_ext['id'];

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);

	switch($str_case) {

		// In der Kammer
		case 'in':

			// Array mit Rezeptstatus
			$arr_rec_states = array(0=>'schon mal gehört..',1=>'bekannt',2=>'beherrscht',3=>'gemeistert',4=>'perfektioniert');

			output(house_get_title('Alchemistischer Schmelztiegel'));

			// Navitext für 'Beenden'-Button
			$str_back_txt = 'Kammer schließen';

			switch($_GET['act']) {

				case 'mix':

					$arr_ids = array();
					$str_del_ids = '';

					if(sizeof($session['items_alchemie']) > 1) {

						$str_del_ids = '-1';

						foreach($session['items_alchemie'] as $item) {
							$str_del_ids .= ','.$item['id'];
						}

						$combo = item_get_combo($session['items_alchemie'][0]['tpl_id'],$session['items_alchemie'][1]['tpl_id'],(isset($session['items_alchemie'][2]) ? $session['items_alchemie'][2]['tpl_id'] : ''),ITEM_COMBO_ALCHEMY);

						if($combo['combo_id'] > 0) {

							if(!empty($combo['result'])) {
								$item_hook_info['product'] = item_get_tpl(' tpl_id="'.$combo['result'].'" ');
							} else {
								$item_hook_info['product']['tpl_name'] = $combo['combo_name'];
							}

							// Komboliste
							$arr_tmp = user_get_aei('combos');
							$arr_combo_ids_all = utf8_unserialize($arr_tmp['combos']);
							$arr_combo_ids = &$arr_combo_ids_all[ITEM_COMBO_ALCHEMY];

							$item_hook_info['min_chance'] = e_rand(1,255);

							if(!$combo['no_entry'])
							{
								if(isset($arr_check_ids[$combo['combo_id']]) && $arr_check_ids[$combo['combo_id']]>0) {
									if($arr_check_ids[$combo['combo_id']] >= 2) {
										$item_hook_info['min_chance'] = max($item_hook_info['min_chance']-10,1);
									}
									elseif($arr_check_ids[$combo['combo_id']] >= 3) {
										$item_hook_info['min_chance'] = max($item_hook_info['min_chance']-15,1);
									}
									elseif($arr_check_ids[$combo['combo_id']] >= 4) {
										$item_hook_info['min_chance'] = max($item_hook_info['min_chance']-20,1);
									}
									elseif ($arr_check_ids[$combo['combo_id']] >= 1) {
										$item_hook_info['min_chance'] = max($item_hook_info['min_chance']-5,1);
									}
								}
								else {
									$item_hook_info['min_chance'] = min($item_hook_info['min_chance']+5,255);
								}
							}

							// Wahrscheinlichkeitsbonus
							if($arr_ext['level'] > 1) {
								$item_hook_info['min_chance'] = max($item_hook_info['min_chance']-($arr_ext['level']-1)*5,1);
							}

							$item_hook_info['victory_msg'] = get_title('`@Es hat geklappt!').
														'`&Du hast die knifflige alchemistische Prozedur erfolgreich zu Ende gebracht und
														'.$item_hook_info['product']['tpl_name'].'`& hergestellt!`nWeiter so, Meister!`n
														Du verlierst einen Waldkampf.`n';

							$item_hook_info['fail_msg'] = get_title('`4Das war wohl nichts!').
														'`&Mitten in der Prozedur rutscht dir ein Kolben aus der Hand und zerspringt auf dem Boden
														in 1000 Scherben! Schade, so gibt das natürlich kein '.$item_hook_info['product']['tpl_name'].'`&..
														Die Zutaten sind leider nicht mehr zu gebrauchen.`n
														Du verlierst einen Waldkampf.`n';
							$item_hook_info['del_ids'] = $str_del_ids;
							$item_hook_info['items_in'] = $session['items_alchemie'];

							$session['user']['turns']--;

							if(!$item_hook_info['hookstop']) {

								if($item_hook_info['min_chance'] <= $combo['chance']) {

									output($item_hook_info['victory_msg']);

									$arr_tmp = user_get_aei('job,jobturns');

									// Berufbonus
									if($arr_tmp['job']==$g_arr_house_extensions[$arr_ext['type']]['special_job'] && $arr_tmp['jobturns']>0) {
										$int_xpgain = max(round($session['user']['experience'] * 0.02),100);
										$arr_tmp['jobturns']--;
										output('`nAls Alchemist erhältst du '.$int_xpgain.' Erfahrungspunkte! Dies ist heute deine `g'.(5-$arr_tmp['jobturns']).'.`& erfolgreiche Mischung.`n');
										$session['user']['experience'] += $int_xpgain;
										user_set_aei(array('jobturns'=>$arr_tmp['jobturns']));

									}

									if(!empty($combo['hook'])) {
										item_load_hook($combo['hook'],'alchemy',$combo);
									}

									if(isset($item_hook_info['product']['tpl_id'])) {
										item_add($session['user']['acctid'],0,$item_hook_info['product']);
									}

									// Rezeptbuch aktualisieren
									if(!$combo['no_entry'])
									{
										if(!isset($arr_combo_ids[$combo['combo_id']])) {
											$arr_combo_ids[$combo['combo_id']] = 1;
											user_set_aei(array('combos'=>db_real_escape_string(utf8_serialize($arr_combo_ids_all))));
											output('`n`n`@`bDu nimmst diese alchemistische Prozedur in dein Rezeptbuch auf!`b`&`n');
											// Berufbonus
											if($arr_tmp['job']==$g_arr_house_extensions[$arr_ext['type']]['special_job']) {
												$int_xpgain = 2000;
												output('Als Alchemist bekommst du für das Entdecken eines neuen Rezepts '.$int_xpgain.' Erfahrungspunkte!`n');
												$session['user']['experience'] += $int_xpgain;

											}

										}
										else {
											$int_max = ($arr_tmp['job'] == $g_arr_house_extensions[$arr_ext['type']]['special_job'] ? sizeof($arr_rec_states)-1 : sizeof($arr_rec_states)-2);
	
											if($arr_combo_ids[$combo['combo_id']] < $int_max) {
												$arr_combo_ids[$combo['combo_id']]++;
												user_set_aei(array('combos'=>db_real_escape_string(utf8_serialize($arr_combo_ids_all))));
												output('`n`n`@`bDu verbesserst dein Können in der Anwendung dieses Rezepts auf '.$arr_rec_states[$arr_combo_ids[$combo['combo_id']]].'!`b`&`n');
											}
											else {
												output('`n`n`2`bIn der Anwendung dieses Rezepts bist du bereits ein Meister.`b`&`n');
											}
										}
									}
									// END Rezeptbuch aktualisieren

								}
								else {

									output($item_hook_info['fail_msg']);
									if(!$combo['no_entry'])
									{
										if(!isset($arr_combo_ids[$combo['combo_id']])) {
											$arr_combo_ids[$combo['combo_id']] = 0;
											user_set_aei(array('combos'=>db_real_escape_string(utf8_serialize($arr_combo_ids_all))));
											output('`n`n`@Du notierst dir dieses Rezept für spätere Versuche.`&`n');
										}
									}

								}
								
								item_delete(' id IN ('.$item_hook_info['del_ids'].') AND owner='.$session['user']['acctid']);

							}

							unset($session['items_alchemie']);

						}
						else {	// Keine Combo gefunden

							output('`&Du versuchst eine halbe Ewigkeit die unterschiedlichen Gegenstände irgendwie in
									eine sinnvolle Verbindung miteinander zu bringen, doch nichts passiert.');

						}

					}

					addnav('Zurück',$str_base_file);
				break;

				case 'start':

					unset($session['items_alchemie']);

					// Übergebenen Return-String speichern
					set_restorepage_history($_REQUEST['ret']);

					redirect($str_base_file);

				break;

				case 'end':

					unset($session['items_alchemie']);

					$ret = 'inside_houses.php';

					redirect($ret);
				break;

				case 'empty':

					unset($session['items_alchemie']);

					redirect($str_base_file);
				break;

				case 'insert':

					$item = item_get(' id='.(int)$_GET['id']);
					$bool_exists = false;

					if($item['id'] > 0) {
						if(is_array($session['items_alchemie'])) {
							foreach($session['items_alchemie'] as $pos=>$i) {
								if($i['id'] == $item['id']) {
									$bool_exists = true;
								}
							}
						}
						if(!$bool_exists) {
							$session['items_alchemie'][] = $item;
						}
					}

					redirect($str_base_file.'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page'].'&loc='.$_REQUEST['loc']);
				break;

				case 'change_pos':

					$int_pos = (int)$_GET['pos'];
					$int_new_pos = (int)$_GET['new_pos'];

					if(!empty($session['items_alchemie'][$int_pos]) && !empty($session['items_alchemie'][$int_new_pos])) {
						$arr_item_tmp = $session['items_alchemie'][$int_pos];
						$session['items_alchemie'][$int_pos] = $session['items_alchemie'][$int_new_pos];
						$session['items_alchemie'][$int_new_pos] = $arr_item_tmp;
					}

					redirect($str_base_file.'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page'].'&loc='.$_REQUEST['loc']);
				break;

				case 'out':

					array_splice($session['items_alchemie'],$_GET['pos'],1);

					redirect($str_base_file.'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page'].'&loc='.$_REQUEST['loc']);
				break;

				case 'book':
					
					$int_combotype = ITEM_COMBO_ALCHEMY;
					$str_type = 'alchemy';
					include_once('alchemie.inc.php');

				break;

				case 'book_show':

					$int_combotype = ITEM_COMBO_ALCHEMY;
					$str_type = 'alchemy';
					include_once('alchemie.inc.php');

				break;

				default:

					output('In dieser magischen, eher beengten Kammer, in der sich die Tinkturen und Extrakte bis an die Decke stapeln,
							kannst du alchemistische Experimente durchführen. Auf dem Werktisch ist Platz für bis zu drei Zutaten - die
							richtige Mixtur musst du selbst finden. Dabei spielt natürlich auch die Reihenfolge eine Rolle..`n
							Falls dein Rezept ein Ergebnis hervorbringt, wirst du einen Waldkampf benötigen. Ansonsten kannst du ohne Gefahr
							mischen und versuchen.`n`n
							Dieser alchemistische Schmelztiegel befindet sich auf Stufe '.$arr_ext['level'].'. Mit der Stufe steigt die Wahrscheinlichkeit dafür, dass deine alchemistischen Prozeduren gelingen.`c');

					$str_ids = '0';

					addnav('Zurück');
					addnav($str_back_txt,$str_base_file.'&act=end');

					addnav('Hilfsmittel');
					addnav('Rezeptbuch',$str_base_file.'&act=book');

					if($session['user']['turns'] <= 0) {
						output('`n`&Heute bist du leider bereits zu erschöpft, um alchemistische Experimente durchzuführen!`n');

						page_footer();
						exit;
					}

					// Standard, Inventar mit mögl. Items anzeigen
					if(is_array($session['items_alchemie']) && sizeof($session['items_alchemie']) > 0) {

						$str_ids = '0';
						foreach ($session['items_alchemie'] as $i) {
							$str_ids .= ','.$i['id'];
						}

						output('`&`bBisher im Schmelztiegel:`n`b`i `n');

						$int_pos = 0;

						foreach($session['items_alchemie'] as $i) {

							output(' ~~~~ Zutat '.($int_pos+1).': '.$i['name'].'`&'
								.(!empty($session['items_alchemie'][$int_pos+1]) ? ' [ '.create_lnk('`b&darr;`b',$str_base_file.'&act=change_pos&pos='.$int_pos.'&new_pos='.($int_pos+1).'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page'].'&loc='.$_REQUEST['loc']).' ]' : '')
								.(!empty($session['items_alchemie'][$int_pos-1]) ? ' [ '.create_lnk('`b&uarr;`b',$str_base_file.'&act=change_pos&pos='.$int_pos.'&new_pos='.($int_pos-1).'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page'].'&loc='.$_REQUEST['loc']).' ]' : '')
								.' [ '.create_lnk('Herausnehmen',$str_base_file.'&act=out&pos='.$int_pos.'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page'].'&loc='.$_REQUEST['loc']).' ]`&
								 `n ');
							$int_pos++;
						}
						output('`i ');

						if(sizeof($session['items_alchemie']) > 1) {
							$link = $str_base_file.'&act=mix';
							addnav('',$link);
							output(''.create_lnk('Leeren!',$str_base_file.'&act=empty'),true);
							output(' -------------- <a href="'.$link.'">Mischen!</a>',true);
						}

						output('`n`n');
					}

					if(sizeof($session['items_alchemie']) >= 3) {
						output('`nMehr bringst du in den alchemistischen Schmelztiegel leider nicht hinein!`n`n');
						$options = array(''=>'');
					}
					else {
						$options = array('Mischen'=>'&act=insert&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page'].'&loc='.$_REQUEST['loc']);
					}
					output('`c');

					item_invent_set_env(ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_SEARCH);

					item_invent_show_data(item_invent_head(' owner='.$session['user']['acctid'].' AND showinvent=1 AND
										i.tpl_id!="alchemtgl" AND deposit1!='.ITEM_LOC_EQUIPPED.' AND ( alchemy=1 ) AND i.id NOT IN ('.$str_ids.')',20),'`iLeider bietet sich kein Gegenstand aus deinem Beutel für eine solche
				                        Mischung an..`i',$options);



				break;

			}

		break;
		// END in der Kammer

		// Bau fertig
		case 'build_finished':


		break;

		// Abreißen
		case 'rip':



		break;

	}
	// END Main Switch

}
// END Main Function

?>
