<?php
// Küche
// by talion

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_kitchen ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner,$item_hook_info,$access_control;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);
	
	switch($str_case) {
		
		// Innen
		case 'in':

			/*$res = db_query('SELECT combos,acctid FROM account_extra_info WHERE combos != ""');
			while($a = db_fetch_assoc($res))
			{
				$tmp = utf8_unserialize($a['combos']);
				$a['combos'] = array();
				if(isset($tmp['runes']))
				{
					$a['combos'][ITEM_COMBO_RUNES] = $tmp['runes'];
					unset($tmp['runes']);
				}
				$a['combos'][ITEM_COMBO_ALCHEMY] = $tmp;
				$a['combos'] = db_real_escape_string(utf8_serialize($a['combos']));
				user_set_aei($a,$a['acctid']);
			}*/
			
			//output(house_get_title('Küche'));
			
			$arr_extra_info = array();
			
			$res_kochtopf = item_list_get('special_info="Küchenutensilien - Topf" AND deposit2='.$arr_ext['id'],'ORDER BY hvalue ASC LIMIT 1',false,'id,name,hvalue');
			$str_kochtopf = '`6Verbeulter Helm`0';
			$int_kochtopf_qual = 100;
			$int_kochtopf_max = 3;
			if(db_num_rows($res_kochtopf))
			{
				$arr_kochtopf = db_fetch_assoc($res_kochtopf);
				$str_kochtopf = $arr_kochtopf['name'].'`0';
				$int_kochtopf_qual = $arr_kochtopf['hvalue'];
				//$int_kochtopf_max = $arr_kochtopf['hvalue'];
			}
			
			$arr_item_lst = &$session['kitchen']['items'];
			$int_combotype = ITEM_COMBO_COOKING;
			
			include_once(ITEM_MOD_PATH.'kitchen.php');
			
			switch($_GET['k_act']) {
			
				case 'mix':
					$arr_ids = array();
					$str_del_ids = '';
		
					if(sizeof($arr_item_lst) > 1) {
		
						$str_del_ids = '-1';
		
						foreach($arr_item_lst as $item) {
							$str_del_ids .= ','.$item['id'];
						}
		
						$combo = item_get_combo($arr_item_lst[0]['tpl_id'],$arr_item_lst[1]['tpl_id'],(isset($arr_item_lst[2]) ? $arr_item_lst[2]['tpl_id'] : ''),$int_combotype);
		
						if($combo['combo_id'] > 0) {
		
							if(!empty($combo['result'])) {
								$item_hook_info['product'] = item_get_tpl(' tpl_id="'.$combo['result'].'" ');
							} 
							else {
								$item_hook_info['product']['tpl_name'] = $combo['combo_name'];
							}
		
							// Komboliste
							$arr_combo_ids = item_get_combolist();
							$arr_check_ids = $arr_combo_ids[$int_combotype];
		
							$item_hook_info['min_chance'] = e_rand(1,150);
							
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
							
							// Kochtopf-Quali
							$item_hook_info['min_chance'] = min($item_hook_info['min_chance']+($int_kochtopf_qual*1),255);
							
							$item_hook_info['victory_msg'] = '`c`b`@Schaut doch gut aus!`0`b`c`n`n
								Du hast scheinbar beim Kochen alles richtig gemacht und
								'.$item_hook_info['product']['tpl_name'].'`0 hergestellt!
								`nWeiter so, Maître de Cuisine!`n
								Du verlierst einen Waldkampf.`n';
				
							$item_hook_info['fail_msg'] = '`c`b`4Keine schönen Aussichten!`0`b`c`n`n
								Was am Ende vor dir im Topf liegt, würde selbst als Schweinefutter Tierschützer auf den Plan rufen.
								So gibt das natürlich kein '.$item_hook_info['product']['tpl_name'].'`0..
								`n
								Du verlierst einen Waldkampf.`n';
							$item_hook_info['enable_name_dish'] = false;
							
							$item_hook_info['del_ids'] = $str_del_ids;
							$item_hook_info['items_in'] = $arr_item_lst;
							
							$item_hook_info['house'] = $arr_house;
							$item_hook_info['ext'] = $arr_ext;
		
							$session['user']['turns']--;
		
							if(!$item_hook_info['hookstop']) {
							
								if($item_hook_info['min_chance'] <= $combo['chance']) {
									
									if(!empty($combo['hook'])) {
										item_load_hook($combo['hook'],'alchemy',$combo);
									}
									
									output($item_hook_info['victory_msg']);
		
									if(isset($item_hook_info['product']['tpl_id'])) {
										$int_itemid = item_add($session['user']['acctid'],0,$item_hook_info['product']);
										// Auswahl eines Namens für das Gericht
										if($item_hook_info['enable_name_dish'])
										{
											output('`n`n`bWenn du magst, kannst du deinem Gericht nun noch einen eigenen Namen geben.`b');
											addnav('Besonderes');
											addnav('Gericht taufen!',$str_base_file.'&k_act=name_dish&itemid='.$int_itemid);
										}
										
									}
		
									// Rezeptbuch aktualisieren
									if(!$combo['no_entry'])
									{
										if(!isset($arr_check_ids[$combo['combo_id']])) {
											$arr_check_ids[$combo['combo_id']] = 1;
											item_set_combolist($arr_check_ids);
											output('`n`n`@`bDu nimmst dieses Rezept in dein Kochbuch auf!`b`&`n');
										}
										else {
											$int_max = sizeof($arr_rec_states)-1;
										
											if($arr_check_ids[$combo['combo_id']] < $int_max) {
												$arr_check_ids[$combo['combo_id']]++;
												$arr_combo_ids[$int_combotype] = $arr_check_ids;
												item_set_combolist($arr_combo_ids);
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
										if(!isset($arr_check_ids[$combo['combo_id']])) {
											$arr_check_ids[$combo['combo_id']] = 0;
											$arr_combo_ids[$int_combotype] = $arr_check_ids;
											item_set_combolist($arr_combo_ids);
											output('`n`n`@Du notierst dir dieses Rezept für spätere Versuche.`&`n');
										}
									}
		
								}
								
								item_delete(' id IN ('.$item_hook_info['del_ids'].') AND owner='.$session['user']['acctid']);
		
							}
		
							$arr_item_lst = array();
							
							db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id = '.$arr_ext['id']);
		
						}
						else {	// Keine Combo gefunden
							
							output('`&Du versuchst eine halbe Ewigkeit, aus den Zutaten etwas Vernünftiges zu kochen.
									Irgendwie sieht das aber eher nach dem aus, was am Ende übrigbleibt, wenn ein Troll ein dürres Kalb verspeist..');
						
						}
		
					}
		
					addnav('Zurück',$str_base_file.'&k_act=cook');
				break;
				
				case 'name_dish':
					
					if(!isset($_GET['itemid']))
					{
						output('FEHLER! Itemid nicht gegeben. Bitte Entwickler verständigen.');
						addnav('DP','village.php');
						page_footer();
					}
					
					$int_itemid = (int)$_GET['itemid'];
					
					if(!isset($_POST['name']))
					{
						output('`bGib deinem Gericht einen Namen:`b`n`n
								'.form_header($str_base_file.'&k_act=name_dish&itemid='.$int_itemid)
								.generateform(
												array(	'name_p'=>'Vorschau:,preview,name',
														'name'=>'Name:,text,40'),
												array( 'name' => 'Mahlzeit'),
												false,
												'Taufen!'
												)
								.'</form>'
								);
					}
					else 
					{
						$str_name = stripslashes($_POST['name']);
						$str_name = mb_substr($str_name,0,40);
						$str_name = strip_tags($str_name);
						
						item_set('id='.$int_itemid,array('name'=>$str_name));
						
						output('Du nennst dein Gericht `b'.$str_name.'`b - lecker!');
						
						addnav('Weiter',$str_base_file.'&k_act=cook');
					}
		
				break;
				
				case 'tobanket':
					
					$int_id = (int)$_GET['dish'];
					$arr_dish = item_get('id='.$int_id.' AND value1='.$arr_ext['id'].' AND (tpl_id="mhlzt_res1" OR tpl_id="mhlzt_res2")');
					
					if(false === $arr_dish)
					{
						output('Auf Rumfriemeln an den IDs steht normalerweise die Todesstrafe..');
						addnav('Zurück',$str_base_file);
						page_footer();
					}
					
					// Im Moment max. ein bankettsaal pro Haus. Falls sich das ändert, müsste hier noch eine Auswahlmöglichkeit rein.
					$arr_ext = db_get_all('SELECT content,id FROM house_extensions WHERE houseid='.$arr_ext['houseid'].' AND type="banket" AND owner > 0');
					if(isset($arr_ext[0]))
					{
						$arr_ext[0]['content'] = utf8_unserialize($arr_ext[0]['content']);
						if(!isset($arr_ext[0]['content']['served_kitchen']))
						{
							$arr_ext[0]['content']['served_kitchen'] = array();
						}
						
						$arr_dish['name'] .= ' ('.kitchen_get_qualadj($arr_dish['hvalue']).')';
						// Standardmenge ist 3. Hier kann rumgespielt werden
						$arr_ext[0]['content']['served_kitchen'][$arr_dish['name']] = array('amount'=>3,'qual'=>$arr_dish['hvalue']);
						db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_ext[0]['content'])).'" WHERE id='.$arr_ext[0]['id']);
						output($arr_dish['name'].'`0 wird in den Bankettsaal gebracht. Bon appetit!');
						item_delete('id='.$arr_dish['id']);
					}
					else {
						output('Leider kannst du in diesem Haus keinen Bankettsaal ausfindig machen..');
					}
					addnav('Zurück',$str_base_file.'&k_act=cook');
					
				break;
				
				case 'take':
					
					$int_id = (int)$_GET['dish'];
					$arr_dish = item_get('id='.$int_id.' AND value1='.$arr_ext['id'].' AND (tpl_id="mhlzt_res1" OR tpl_id="mhlzt_res2")');
					
					item_set('id='.$int_id,array('value1'=>0));
					$session['kitchen']['msg'] = '`0Du packst '.$arr_dish['name'].'`0 in deinen Beutel. Pass aber auf, dass es nicht zu lange dort liegt!`n';
					
					redirect($str_base_file.'&k_act=cook&open_table=1');
					
				break;
				
				case 'empty':
					
					$arr_item_lst = array();
					
					//db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id = '.$arr_ext['id']);
					
					redirect($str_base_file.'&k_act=cook');
				break;
			
				case 'insert':
					
					$arr_content['recent_cook'] = $session['user']['acctid'];
					
					$item = item_get(' id='.(int)$_GET['id']);
					$bool_exists = false;
		
					if($item['id'] > 0) {
						if(is_array($arr_item_lst)) {
							foreach($arr_item_lst as $pos=>$i) {
								if($i['id'] == $item['id']) {
									$bool_exists = true;
								}
							}
						}
						if(!$bool_exists) {
							$arr_item_lst[] = $item;
						}
					}
					
				
					//db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id = '.$arr_ext['id']);
					
					redirect($str_base_file.'&k_act=cook&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']);
				break;
			
				case 'change_pos':
			
					$int_pos = (int)$_GET['pos'];
					$int_new_pos = (int)$_GET['new_pos'];
		
					if(!empty($arr_item_lst[$int_pos]) && !empty($arr_item_lst[$int_new_pos])) {
						$arr_item_tmp = $arr_item_lst[$int_pos];
						$arr_item_lst[$int_pos] = $arr_item_lst[$int_new_pos];
						$arr_item_lst[$int_new_pos] = $arr_item_tmp;
					}
					
					//db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id = '.$arr_ext['id']);
		
					redirect($str_base_file.'&k_act=cook&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']);
				break;
			
				case 'out':
			
					array_splice($arr_item_lst,$_GET['pos'],1);
					
					//db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id = '.$arr_ext['id']);

					redirect($str_base_file.'&k_act=cook&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']);
				break;
				
				case 'book':
				case 'book_show':
					
					$str_type = 'kitchen';
					$int_combotype = ITEM_COMBO_COOKING;
					
					include_once('alchemie.inc.php');
					
				break;
				
				case 'futter':
					
					$res = item_tpl_list_get('tpl_class=25');
					
					while($arr_i = db_fetch_assoc($res))
					{
						item_add($session['user']['acctid'],false,$arr_i);
					}
					
					redirect($str_base_file.'&k_act=cook');
					
				break;
				
				case 'cook':
					
					addnav('Gemach');
					addnav('Zurück',$str_base_file);
					addnav('-');
					
					if(isset($session['kitchen']['msg']))
					{
						output($session['kitchen']['msg'].'`n');
						unset($session['kitchen']['msg']);
					}
					
					$bool_open_table = false;
					if(isset($_GET['open_table']))
					{
						$bool_open_table = true;
					}
					
					// Gibt es Mampf, der dorthin geschickt werden könnte?
					$res = item_list_get('value1='.$arr_ext['id'].' AND (tpl_id="mhlzt_res1" OR tpl_id="mhlzt_res2")','',false,'id,name,hvalue,description');
					$int_c = db_num_rows($res);
					output('`c`bKüchentisch ('.$int_c.' Gericht(e))`b`n
								<div class="trlight" style="width:400px;border-style:inset;border-width:1px;">
									'.plu_mi('table',null,$bool_open_table).'
									<div id="'.plu_mi_unique_id('table').'" style="'.($bool_open_table ? '':'display:none;').'text-align:left;">');
					if($int_c)
					{
						// Existiert Bankettsaal in Haus?
						$arr_tmp = db_fetch_assoc(db_query('SELECT COUNT(*) AS c FROM house_extensions WHERE houseid='.(int)$arr_ext['houseid'].' AND type="banket" AND owner>0'));					
						if(!empty($arr_tmp['c']))
						{
							$bool_banket = true;
							unset($arr_tmp);
						}
						//form_header($str_base_file.'&k_act=tobanket').'<select name="dish">');
						$str_tmp_out = '<table>';
						while($arr_i = db_fetch_assoc($res))
						{
							//$str_tmp_out .= '<option value="'.$arr_i['id'].'">'.$arr_i['name'].' ('.kitchen_get_qualadj($arr_i['hvalue']).$arr_i['hvalue'].')</option>';
							$str_tmp_out .= '<tr valign="top"><td>'.$arr_i['name'].' ('.$arr_i['description'].')</td><td>'.($bool_banket ? '['.create_lnk('Zum Bankettsaal',$str_base_file.'&k_act=tobanket&dish='.$arr_i['id']).']':'').'</td><td>['.create_lnk('Mitnehmen',$str_base_file.'&k_act=take&dish='.$arr_i['id']).']</td></tr>';
						}
						output($str_tmp_out.'</table>');
						//output('</select> <input type="submit" value="Zum Bankettsaal!"></form>
					}
					else {
						output('`tBis auf ein paar Krümel der letzten Mahlzeit ist der Küchentisch leer.`0`n');
					}
					output('</div></div>`c`n');
					
					output('In dieser Küche darfst du deine Qualitäten als Koch unter Beweis stellen.`n
								Zunächst ist es von essentieller Bedeutung, die richtigen Instrumente zu wählen.
								Der richtige Kochtopf etwa verbessert den Geschmack deiner Leckereien um ein Vielfaches.`c');
					
					if($session['user']['turns'] <= 0) {
						output('`n`&Heute bist du leider bereits zu erschöpft, um dich noch vor den Herd zu stellen!`n');
			
						page_footer();
					}
					
					addnav('Hilfsmittel');
					addnav('Kochbuch',$str_base_file.'&k_act=book');
			
					// Standard, Inventar mit mögl. Items anzeigen
					$str_ids = '0';
					if(is_array($arr_item_lst) && sizeof($arr_item_lst) > 0) {
			
						foreach ($arr_item_lst as $i) {
							$str_ids .= ','.$i['id'];
						}
						
						output('`n`&`bBisher in deinem Kochtopf `b"'.$str_kochtopf.'`&" (Qualität: '.round(100/$int_kochtopf_qual,1).')`b:`n`b`i `n');
						
						$int_pos = 0;
			
						foreach($arr_item_lst as $i) {
			
							output(' ~~~~ Zutat '.($int_pos+1).': '.$i['name'].'`&'
								.(!empty($arr_content['items'][$int_pos+1]) ? ' [ '.create_lnk('`b&darr;`b',$str_base_file.'&k_act=change_pos&pos='.$int_pos.'&new_pos='.($int_pos+1).'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']).' ]' : '')
								.(!empty($arr_content['items'][$int_pos-1]) ? ' [ '.create_lnk('`b&uarr;`b',$str_base_file.'&k_act=change_pos&pos='.$int_pos.'&new_pos='.($int_pos-1).'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']).' ]' : '')
								.' [ '.create_lnk('Herausnehmen',$str_base_file.'&k_act=out&pos='.$int_pos.'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']).' ]`&
								 `n ');
							$int_pos++;
						}
						output('`i ');
			
						if(sizeof($arr_item_lst) > 1) {
							$link = $str_base_file.'&k_act=mix';
							addnav('',$link);
							output(''.create_lnk('Kochtopf leeren!',$str_base_file.'&k_act=empty'),true);
							output(' -------------- <a href="'.$link.'">Kochen!</a>`n',true);
						}
						output('[ '.create_lnk('Aktualisieren',$str_base_file.'&k_act=cook').' ]',true);
						output('`n`n');
					}
			
					if(sizeof($arr_item_lst) >= 3) {
						output('`nMehr bringst du in den Kochtopf leider nicht hinein!`n`n');
						$options = array(''=>'');
					}
					else {
						$options = array('In den Kochtopf'=>'&k_act=insert&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']);
					}
					output('`c');
			
					item_invent_set_env(ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_SEARCH);
			
					item_invent_show_data(item_invent_head(' owner='.$session['user']['acctid'].' AND showinvent=1 AND
										deposit1!='.ITEM_LOC_EQUIPPED.' AND ( cooking=1 ) AND i.id NOT IN ('.$str_ids.')',20),'`iLeider findest du nichts kochbares.`i',$options);
					
					if($access_control->su_check(access_control::SU_RIGHT_DEV))
					{
						addnav('Gib mir Futter! (SU)',$str_base_file.'&k_act=futter');
					}
					
				break;
				
				default:

					if(empty($_GET['act']))
					{
						addnav('Küche');
						addnav('Kochen',$str_base_file.'&k_act=cook');
						addnav('-');
					}
					
					_rooms_common_switch($str_case,$arr_ext,$arr_house);
					
				break;
			
			}

		break;
		// END case in

		// Bau gestartet
		case 'build_start':

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;

		// Bau fertig
		case 'build_finished':

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;

		// Abreißen
		case 'rip':

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;

	}	// END Main switch
}

?>
