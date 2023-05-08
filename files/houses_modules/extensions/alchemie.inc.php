<?php
/**
* alchemie.inc.php: Stellt 
* @author talion <t@ssilo.de>
* @version DS-E V/3
*/

// Array mit Rezeptstatus
//$arr_rec_states = array(0=>'schon mal gehört..',1=>'bekannt',2=>'beherrscht',3=>'gemeistert',4=>'perfektioniert');

$str_act_e = ($str_type == 'alchemy' ? 'act' : 'k_act');
$str_case = $_GET[$str_act_e];

switch($str_case)
{
	case 'book':
		addnav('Buch schließen',$str_base_file.($str_type == 'kitchen' ? '&k_act=cook':''));

		// Komboliste
		$arr_combo_ids = item_get_combolist(0,$int_combotype);
		
		$int_cid = (int)$_GET['cid'];

		if(!empty($int_cid)) {

			$arr_combo = db_fetch_assoc(db_query('SELECT * FROM items_combos WHERE combo_id='.$int_cid));

			if($str_type == 'alchemy')
			{
				$arr_item_lst = &$session['items_alchemie'];
			}
			else {
				$arr_item_lst = &$session['kitchen']['items'];
			}

			$str_ids = '0';

			for($i=1; $i<=$arr_combo_ids[$int_cid]; $i++) {
				if(!empty($arr_combo['id'.$i])) {

					// Wenn keine Wildcard
					if('*' != $arr_combo['id'.$i]) {

						// Item auswählen
						$arr_item = item_get(' owner='.$session['user']['acctid'].' AND i.tpl_id="'.$arr_combo['id'.$i].'" AND i.id NOT IN ('.db_real_escape_string($str_ids).') AND deposit1!='.ITEM_LOC_EQUIPPED.'',false);

						if(false === $arr_item) {
							$arr_item_lst = array();
							output('`$Zutat '.$i.' befindet sich leider nicht in deinem Besitz..`&`n`n');
							break;
						}

						// Item nicht zweimal in Mischung packen
						$str_ids .= ','.$arr_item['id'];

						$arr_item_lst[$i-1] = $arr_item;

					}

				}
			}
			
			if(sizeof($arr_item_lst)) {
				redirect($str_base_file);
			}
			else {
				if($str_type == 'alchemy')
				{
					output('`&Irgendwie solltest du dieses Rezept besser von Hand mischen. Faulheit ist nicht immer von Vorteil!`n`n');
				}
				elseif($str_type == 'kitchen')
				{
					output('`&Irgendwie solltest du dieses Rezept besser von Hand kochen. Faulheit ist nicht immer von Vorteil!`n`n');
				}
				page_footer();
				exit;
			}

		}
		
		if($str_type == 'alchemy')
		{
			$str_what = 'Rezept';
			$str_prof = 'Alchemist';
		}
		elseif($str_type == 'kitchen')
		{
			$str_what = 'Koch';
			$str_prof = 'Koch';
		}
		$str_out = '`c`b'.$str_what.'buch '.$session['user']['login'].'s`b`c`n`n';
		
		if(empty($arr_combo_ids)) {
			
			$str_out .= 'Bisher tummeln sich auf den leeren Seiten deines '.$str_what.'buches nur unmotiviert hingekritzelte Strichmännchen.
						Hoffentlich bist du als '.$str_prof.' begabter...`n`n
							<img src="./images/strichm.gif" alt="Abscheuliches Strichmännchen">';
		}
		else {

			//by Salator: eine 0 in den String um SQL-Fehler zu umgehen
			$sql = 'SELECT * FROM items_combos WHERE combo_id IN (0'.db_real_escape_string(implode(',',array_keys($arr_combo_ids))).') AND chance>0 ORDER BY result ASC';
			$res = db_query($sql);

			if(!db_num_rows($res)) {
				$str_out .= 'Bisher tummeln sich auf den leeren Seiten deines '.$str_what.'buches nur unmotiviert hingekritzelte Strichmännchen.
						Hoffentlich bist du als '.$str_prof.' begabter..:`n`n
							<img src="./images/strichm.gif" alt="Abscheuliches Strichmännchen">';
			}
			else {

				$str_out .= '<table cellpadding="5" cellspacing="5">
								<tr>
									<td>`~`bRezept/Fortschritt`b</td><td>`~`bAktionen`b</td>
								</tr>';
				while($arr_combo = db_fetch_assoc($res)) {

					$str_state = $arr_rec_states[$arr_combo_ids[$arr_combo['combo_id']]];
					$int_state = $arr_combo_ids[$arr_combo['combo_id']];

					$str_output[$int_state] .= '<tr>
									<td>`~'.$arr_combo['combo_name'].'`n<i>'.$str_state.'</i>`0</td>
									<td>'.create_lnk('`~ &raquo; Betrachten`0',$str_base_file.'&'.$str_act_e.'=book_show&cid='.$arr_combo['combo_id']).'`n
									'.($session['user']['turns'] > 0 ? create_lnk('`~ &raquo; Einsetzen`0',$str_base_file.'&'.$str_act_e.'=book&cid='.$arr_combo['combo_id']) : '`4Zu erschöpft zum Einsetzen.`0').'</td>
								</tr>
								<tr>
								<td colspan="2">
								<hr />
								</td>
								</tr>';
				}
				$str_out .= $str_output[4].$str_output[3].$str_output[2].$str_output[1].$str_output[0].'</table>
							';
			}

		}

		$str_out = show_scroll($str_out);

		output('<div align="center">'.$str_out.'</div>',true);

	break;

	case 'book_show':

		$arr_combo_ids = item_get_combolist(0,$int_combotype);

		$int_cid = (int)$_GET['cid'];

		$arr_combo = db_fetch_assoc(db_query('SELECT * FROM items_combos WHERE combo_id='.$int_cid));

		$str_state = $arr_rec_states[$arr_combo_ids[$int_cid]];

		$str_out .= '`c`b'.$arr_combo['combo_name'].' ('.$str_state.'):`b`c`n`n';

		// Zielitem auswählen
		$arr_item = item_get_tpl(' tpl_id="'.$arr_combo['result'].'"');
		$str_out .= 'Zur Herstellung von `b'.$arr_item['tpl_name'].'`b`0 nehme man:`n`n';
		
		$str_check_field = ($str_type == 'alchemy' ? 'alchemy' : 'kitchen');
		
		for($i=1; $i<=3; $i++) {
			if(!empty($arr_combo['id'.$i])) {

				$str_out .= 'Zum '.ordinal($i).': `b';

				// Ausgeschaltet
				if($arr_combo_ids[$int_cid] < -42) {
					$str_out .= '`iUnbekannte Zutat`i';
				}
				else {

					// Wenn Wildcard
					if('*' == $arr_combo['id'.$i]) {
						$str_out .= '`iBeliebige Zutat`i';
					}
					else {

						// Item auswählen
						$arr_item = item_get_tpl(' '.$str_check_field.'=1 AND tpl_id="'.$arr_combo['id'.$i].'"');

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
			addnav('Dieses Rezept verwenden!',$str_base_file.'&'.$str_act_e.'=book&cid='.$int_cid);
		}
		addnav('Zurück zum Buch',$str_base_file.'&'.$str_act_e.'=book');
		addnav('Buch schließen',$str_base_file.($str_type == 'kitchen' ? '&k_act=cook':''));

	break;
		
}

?>
