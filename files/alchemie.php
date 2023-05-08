<?php
/**
* alchemie.php: Stellt Basisanwendung nebst Userführung für Alchemie / Misch / Komboaktionen
*				des Dragonslayeredition-Itemsystems bereit. Ist mehr oder minder sehr vielseitig einsetz-
				bar: Häuser, Inventar etc.
* @author talion <t@ssilo.de>
* @version DS-E V/2
*/

require_once('common.php');

// Array mit Rezeptstatus
$arr_rec_states = array(0=>'unbekannt',1=>'bekannt',2=>'beherrscht',3=>'perfektioniert');


page_header('Alchemistischer Schmelztiegel');

output('`&`b`cAlchemistischer Schmelztiegel`b`c`n`n');

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
				$arr_combo_ids = utf8_unserialize($arr_tmp['combos']);

				$item_hook_info['min_chance'] = e_rand(1,255);

				if(isset($arr_combo_ids[$combo['combo_id']]) && $arr_combo_ids[$combo['combo_id']]>0) {
					if($arr_combo_ids[$combo['combo_id']] >= 2) {
						$item_hook_info['min_chance'] = max($item_hook_info['min_chance']-10,1);
					}
					elseif($arr_combo_ids[$combo['combo_id']] >= 3) {
						$item_hook_info['min_chance'] = max($item_hook_info['min_chance']-15,1);
					}
					elseif ($arr_combo_ids[$combo['combo_id']] >= 1) {
						$item_hook_info['min_chance'] = max($item_hook_info['min_chance']-5,1);
					}
				}
				else {
					$item_hook_info['min_chance'] = min($item_hook_info['min_chance']+5,255);
				}

				$item_hook_info['victory_msg'] = '`c`b`@Es hat geklappt!`&`b`c`n`n
											Du hast die knifflige alchemistische Prozedur erfolgreich zu Ende gebracht und
											'.$item_hook_info['product']['tpl_name'].'`& hergestellt!`nWeiter so, Meister!`n
											Du verlierst einen Waldkampf.`n';

				$item_hook_info['fail_msg'] = '`c`b`4Das war wohl nichts!`&`b`c`n`n
											Mitten in der Prozedur rutscht dir ein Kolben aus der Hand und zerspringt auf dem Boden
											in 1000 Scherben! Schade, so gibt das natürlich kein '.$item_hook_info['product']['tpl_name'].'`&..
											Die Zutaten sind leider nicht mehr zu gebrauchen.`n
											Du verlierst einen Waldkampf.`n';
				$item_hook_info['del_ids'] = $str_del_ids;
				$item_hook_info['items_in'] = $session['items_alchemie'];

				$session['user']['turns']--;

				if(!$item_hook_info['hookstop']) {

					if($item_hook_info['min_chance'] <= $combo['chance']) {

						output($item_hook_info['victory_msg']);

						if(!empty($combo['hook'])) {
							item_load_hook($combo['hook'],'alchemy',$combo);
						}

						if(isset($item_hook_info['product']['tpl_id'])) {
							item_add($session['user']['acctid'],0,$item_hook_info['product']);
						}

						// Rezeptbuch aktualisieren
						if(!isset($arr_combo_ids[$combo['combo_id']])) {
							$arr_combo_ids[$combo['combo_id']] = 1;
							user_set_aei(array('combos'=>db_real_escape_string(utf8_serialize($arr_combo_ids))));
							output('`n`n`@`bDu nimmst diese alchemistische Prozedur in dein Rezeptbuch auf!`b`&`n');
						}
						else {
							if($arr_combo_ids[$combo['combo_id']] < 3) {
								$arr_combo_ids[$combo['combo_id']]++;
								user_set_aei(array('combos'=>db_real_escape_string(utf8_serialize($arr_combo_ids))));
								output('`n`n`@`bDu verbesserst dein Können in der Anwendung dieses Rezepts auf '.$arr_rec_states[$arr_combo_ids[$combo['combo_id']]].'!`b`&`n');
							}
							else {
								output('`n`n`2`bIn der Anwendung dieses Rezepts bist du bereits ein Meister.`b`&`n');
							}
						}
						// END Rezeptbuch aktualisieren

					}
					else {

						output($item_hook_info['fail_msg']);
						if(!isset($arr_combo_ids[$combo['combo_id']])) {
							$arr_combo_ids[$combo['combo_id']] = 0;
							user_set_aei(array('combos'=>db_real_escape_string(utf8_serialize($arr_combo_ids))));
							output('`n`n`@Du notierst dir dieses Rezept für spätere Versuche.`&`n');
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

		addnav('Zurück','alchemie.php');
	break;

	case 'start':

		unset($session['items_alchemie']);

		// Übergebenen Return-String speichern
		set_restorepage_history($_REQUEST['ret']);

		redirect('alchemie.php?itemid='.$_GET['itemid']);

	break;

	case 'end':

		unset($session['items_alchemie']);

		$ret = get_restorepage_history();

		if(empty($ret)) {
			$ret = 'news.php';
		}

		redirect($ret);
	break;

	case 'empty':

		unset($session['items_alchemie']);

		redirect('alchemie.php');
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

		redirect('alchemie.php?cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']);
	break;

	case 'change_pos':

		$int_pos = (int)$_GET['pos'];
		$int_new_pos = (int)$_GET['new_pos'];

		if(!empty($session['items_alchemie'][$int_pos]) && !empty($session['items_alchemie'][$int_new_pos])) {
			$arr_item_tmp = $session['items_alchemie'][$int_pos];
			$session['items_alchemie'][$int_pos] = $session['items_alchemie'][$int_new_pos];
			$session['items_alchemie'][$int_new_pos] = $arr_item_tmp;
		}

		redirect('alchemie.php?cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']);
	break;

	case 'out':

		array_splice($session['items_alchemie'],$_GET['pos'],1);

		redirect('alchemie.php?cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']);
	break;

	case 'book':

		addnav('Buch schließen','alchemie.php');

		// Komboliste
		$arr_tmp = user_get_aei('combos');
		$arr_combo_ids = utf8_unserialize($arr_tmp['combos']);
		unset($arr_combo_ids['runes']);
		$int_cid = (int)$_GET['cid'];

		if(!empty($int_cid)) {

			$arr_combo = db_fetch_assoc(db_query('SELECT * FROM items_combos WHERE combo_id='.$int_cid));

			$session['items_alchemie'] = array();

			$str_ids = '0';

			for($i=1; $i<=$arr_combo_ids[$int_cid]; $i++) {
				if(!empty($arr_combo['id'.$i])) {

					// Wenn keine Wildcard
					if('*' != $arr_combo['id'.$i]) {

						// Item auswählen
						$arr_item = item_get(' owner='.$session['user']['acctid'].' AND i.tpl_id="'.$arr_combo['id'.$i].'" AND i.id NOT IN ('.db_real_escape_string($str_ids).') AND deposit1!='.ITEM_LOC_EQUIPPED.'',false);

						if(false === $arr_item) {
							output('`$Zutat '.$i.' befindet sich leider nicht in deinem Besitz..`&`n`n');
							break;
						}

						// Item nicht zweimal in Mischung packen
						$str_ids .= ','.$arr_item['id'];

						$session['items_alchemie'][$i-1] = $arr_item;

					}

				}
			}

			if(sizeof($session['items_alchemie'])) {
				redirect('alchemie.php');
			}
			else {
				output('`&Irgendwie solltest du dieses Rezept besser von Hand mischen. Faulheit ist nicht immer von Vorteil!`n`n');
				page_footer();
				exit;
			}

		}

		$str_out = '`c`bRezeptbuch '.$session['user']['login'].'s`b`c`n`n';

		if(empty($arr_combo_ids)) {
			$str_out .= 'Bisher tummeln sich auf den leeren Seiten deines Rezeptbuches nur unmotiviert hingekritzelte Strichmännchen.
						Hoffentlich bist du als Alchemist begabter..:`n`n
							<img src="./images/strichm.gif" alt="Abscheuliches Strichmännchen">';
		}
		else {

			$sql = 'SELECT * FROM items_combos WHERE combo_id IN ('.db_real_escape_string(implode(',',array_keys($arr_combo_ids))).') AND chance>0 ORDER BY result ASC';
			$res = db_query($sql);

			if(!db_num_rows($res)) {
				$str_out .= 'Bisher tummeln sich auf den leeren Seiten deines Rezeptbuches nur unmotiviert hingekritzelte Strichmännchen.
							Hoffentlich bist du als Alchemist begabter..:`n`n
							<img src="./images/strichm.gif" alt="Abscheuliches Strichmännchen">';
			}
			else {

				$str_out .= '<table cellpadding="5" cellspacing="5">
								<tr>
									<td>`~`bRezept`b</td><td>`~`bFortschritt`b</td><td>`~`bBetrachten`b</td><td>`~`bEinsetzen`b</td>
								</tr>';
				while($arr_combo = db_fetch_assoc($res)) {

					$str_state = $arr_rec_states[$arr_combo_ids[$arr_combo['combo_id']]];

					$str_out .= '<tr>
									<td>`~'.$arr_combo['combo_name'].'`0</td>
									<td>`~'.$str_state.'`0</td>
									<td>'.create_lnk('`~ &raquo; Betrachten`0','alchemie.php?act=book_show&cid='.$arr_combo['combo_id']).'</td>
									<td>'.($session['user']['turns'] > 0 ? create_lnk('`~ &raquo; Einsetzen`0','alchemie.php?act=book&cid='.$arr_combo['combo_id']) : '`4Zu erschöpft.`0').'</td>
								</tr>';
				}
				$str_out .= '</table>
							';
			}

		}

		$str_out = show_scroll($str_out);

		output('<div align="center">'.$str_out.'</div>',true);


	break;

	case 'book_show':

		// Komboliste
		$arr_tmp = user_get_aei('combos');
		$arr_combo_ids = utf8_unserialize($arr_tmp['combos']);
		unset($arr_combo_ids['runes']);

		$int_cid = (int)$_GET['cid'];

		$arr_combo = db_fetch_assoc(db_query('SELECT * FROM items_combos WHERE combo_id='.$int_cid));

		$str_state = $arr_rec_states[$arr_combo_ids[$int_cid]];

		$str_out .= '`c`b'.$arr_combo['combo_name'].' ('.$str_state.'):`b`c`n`n';

		// Zielitem auswählen
		$arr_item = item_get_tpl(' tpl_id="'.$arr_combo['result'].'"');
		$str_out .= 'Zur Herstellung von `b'.$arr_item['tpl_name'].'`b`0 nehme man:`n`n';

		for($i=1; $i<=3; $i++) {
			if(!empty($arr_combo['id'.$i])) {

				$str_out .= 'Zum '.ordinal($i).': `b';

				if($arr_combo_ids[$int_cid] < $i) {
					$str_out .= '`iUnbekannte Zutat`i';
				}
				else {

					// Wenn Wildcard
					if('*' == $arr_combo['id'.$i]) {
						$str_out .= '`iBeliebige Zutat`i';
					}
					else {

						// Item auswählen
						$arr_item = item_get_tpl(' alchemy=1 AND tpl_id="'.$arr_combo['id'.$i].'"');

						if(false === $arr_item) {
							$str_out .= '`$Nicht-existente Zutat`0';
						}
						else {
							$int_count = item_count('tpl_id="'.$arr_combo['id'.$i].'" AND owner='.$session['user']['acctid'].' AND deposit1!='.ITEM_LOC_EQUIPPED.'');
							$str_out .= $arr_item['tpl_name'].'`0'.($int_count > 0 ? ' (`@'.$int_count.'x vorhanden`0)' : ' (`$nicht vorhanden`0)');
						}


					}
				}

				$str_out .= '`b`n`n';

			}
		}

		output('<div align="center">'.show_scroll($str_out).'</div>');

		if($session['user']['turns'] > 0) {
			addnav('Dieses Rezept verwenden!','alchemie.php?act=book&cid='.$int_cid);
		}
		addnav('Zurück zum Buch','alchemie.php?act=book');
		addnav('Buch schließen','alchemie.php');

	break;

	case 'convert':

		$arr_item = item_get('id='.(int)$_GET['itemid']);

		$item_tpl = item_get_tpl('tpl_id="alchemtgl"','tpl_gold,tpl_gems');
		$item_tpl['tpl_gold'] = round($item_tpl['tpl_gold'] * 1.1);
		$item_tpl['tpl_gems'] = round($item_tpl['tpl_gems'] * 1.1);

		$session['user']['gems'] += $item_tpl['tpl_gems'];
		$session['user']['gold'] += $item_tpl['tpl_gold'];

		output('Dragonslayer sprach: Es werde Licht! und es ward Licht. Und Dragonslayer sah, dass es gut war. Und du hast nun '.$item_tpl['tpl_gold'].' Gold und '.$item_tpl['tpl_gems'].' Edelsteine wieder (was sogar noch 10% über dem Kaufpreis liegt - wenn das nicht mal ein Geschäft war..)!
				Dein alchemistischer Schmelztiegel verschwindet in einer Dimensionslücke und ward fortan nimmermehr gesehen..');

		item_delete('id='.$arr_item['id'],1);
		addnav('JIPPIE!','village.php');

	break;

	default:

		output('In dieser magischen, eher beengten Kammer, in der sich die Tinkturen und Extrakte bis an die Decke stapeln,
				kannst du alchemistische Experimente durchführen. Auf dem Werktisch ist Platz für bis zu drei Zutaten - die
				richtige Mixtur musst du selbst finden. Dabei spielt natürlich auch die Reihenfolge eine Rolle..`n
				Falls dein Rezept ein Ergebnis hervorbringt, wirst du einen Waldkampf benötigen. Ansonsten kannst du ohne Gefahr
				mischen und versuchen.`n`c');

		$str_ids = '0';

		addnav('Zurück');
		addnav($str_back_txt,'alchemie.php?act=end');

		$arr_item = item_get('id='.(int)$_GET['itemid']);

		if($arr_item['owner'] == $session['user']['acctid'])
		{
			addnav('Aktionen');
			addnav('`^Rückerstattung des Kaufpreises!`0','alchemie.php?act=convert&itemid='.$arr_item['id'],false,false,false,false,'Bist du dir sicher?');

		}
		output('`n`$Schmelztiegel sind nun im Anbau Alchemistisches Labor integriert. Der Besitzer des Tiegels kann diesen gegen eine Entschädigung zurückgeben. Demnächst werden die Schmelztiegel ihre Kraft verlieren!`0`n');
		//page_footer();


		//addnav('Hilfsmittel');
		//muss weg, neues Datenformat. addnav('Rezeptbuch','alchemie.php?act=book');

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
					.(!empty($session['items_alchemie'][$int_pos+1]) ? ' [ '.create_lnk('`b&darr;`b','alchemie.php?act=change_pos&pos='.$int_pos.'&new_pos='.($int_pos+1).'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']).' ]' : '')
					.(!empty($session['items_alchemie'][$int_pos-1]) ? ' [ '.create_lnk('`b&uarr;`b','alchemie.php?act=change_pos&pos='.$int_pos.'&new_pos='.($int_pos-1).'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']).' ]' : '')
					.' [ '.create_lnk('Herausnehmen','alchemie.php?act=out&pos='.$int_pos.'&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']).' ]`&
					 `n ');
				$int_pos++;
			}
			output('`i ');

			if(sizeof($session['items_alchemie']) > 1) {
				$link = 'alchemie.php?act=mix';
				addnav('',$link);
				output(''.create_lnk('Leeren!','alchemie.php?act=empty'),true);
				output(' -------------- <a href="'.$link.'">Mischen!</a>',true);
			}

			output('`n`n');
		}

		if(sizeof($session['items_alchemie']) >= 3) {
			output('`nMehr bringst du in den alchemistischen Schmelztiegel leider nicht hinein!`n`n');
			$options = array(''=>'');
		}
		else {
			$options = array('Mischen'=>'&act=insert&cat='.$_REQUEST['cat'].'&page='.$_REQUEST['page']);
		}
		output('`c');

		item_invent_set_env(ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_SEARCH);

		item_invent_show_data(item_invent_head(' owner='.$session['user']['acctid'].' AND showinvent=1 AND
							i.tpl_id!="alchemtgl" AND deposit1!='.ITEM_LOC_EQUIPPED.' AND ( alchemy=1 ) AND i.id NOT IN ('.$str_ids.')',20),'`iLeider bietet sich kein Gegenstand aus deinem Beutel für eine solche
	                        Mischung an..`i',$options);



	break;

}

page_footer();
?>
