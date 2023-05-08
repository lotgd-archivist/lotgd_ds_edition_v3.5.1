<?php
/**
 * invhandler.php: neuer Itemhandler für Drachenserver-Itemsystem
 * @author talion <t@ssilo.de>
 * @version DS-E V/2
*/

require_once('common.php');
require_once(LIB_PATH.'house.lib.php');

$id = (int)$_REQUEST['id'];
$ret = $_REQUEST['ret'];

$base_link = 'invhandler.php?ret='.urlencode($ret).'&id='.$id;

if ( isset($id) ){
	$item = item_get('id='.$id);
}
else {
	$item = array();

	if(!isset($_POST['ids'])) {
		redirect($ret);
	}
}

page_header('Inventar');

$str_op = $_REQUEST['op'];

switch($str_op) {

	// Inventar, Benutzen
	case 'use':

		$item_hook_info['link'] = $base_link;
		$item_hook_info['ret'] = $ret;

		item_load_hook($item['use_hook'],'use',$item);

		break;

	// Wegwerfen
	case 'wegw':

		// Multiselect
		if(!empty($_POST['ids']) && is_array($_POST['ids'])) {
			$str_ids = implode(',',$_POST['ids']);
			//fix by bathi
			$res_items = item_list_get(' id IN ('.db_intval_in_string(stripslashes($str_ids)).') AND owner='.$session['user']['acctid'].' AND deposit1 !='.ITEM_LOC_EQUIPPED.' AND it.throw > 0 ','',true,'name,id');

			if(db_num_rows($res_items) == 0) {
				redirect($ret);
			}

		}

		if($_GET['act'] != 'ok') {
			$str_lnk = $base_link.'&op=wegw&act=ok';

			if(isset($res_items)) {
				output('`QBist du dir sicher, die folgenden Gegenstände unwiederbringlich aufzugeben?`n`n');
				$str_lnk .= '&ids='.$str_ids;
				while ($item = db_fetch_assoc($res_items)) {
					output('`q'.$item['name'].'`q`n');
				}
			}
			else {
				if(empty($item)) {
					redirect($ret);
				}
				output('`QBist du dir sicher, '.$item['name'].'`Q unwiederbringlich aufzugeben?');
			}

			addnav('Nein, zurück!',$ret);

			addnav('Ja, weg damit!',$str_lnk);
			
		}
		else {

			// Mehrere Items?
			if(isset($_GET['ids'])) {
				item_delete('id IN ('.db_real_escape_string(stripslashes($_GET['ids'])).') AND owner='.$session['user']['acctid'].' AND deposit1 !='.ITEM_LOC_EQUIPPED);
				output('`QDu schleppst all die Gegenstände in eine dunkle Seitengasse und lässt sie dort vergammeln.');
			}
			else {
				output('`QDu schleppst '.$item['name'].'`Q in eine dunkle Seitengasse und lässt es dort stehen und liegen. Da wird sich schon jemand drum kümmern..');
				item_delete('id='.$id);
			}

			addnav('Zum Inventar',$ret);

		}

		break;

	// Einlagern in Haus o. Gemach
	case 'einl':

		// Multiselect
		if(!empty($_POST['ids']) && is_array($_POST['ids'])) {
			$str_ids = implode(',',$_POST['ids']);
		}

		if($_GET['act'] == 'house') {

			$sql = 'SELECT k.*,h.status,h.houseid,h.owner,h.housename FROM keylist k LEFT JOIN houses h ON h.houseid=k.value1 WHERE k.owner='.$session['user']['acctid'].'  AND type='.HOUSES_KEY_DEFAULT.' ORDER BY id ASC';
			$res = db_query($sql);

			while($k = db_fetch_assoc($res)) {
				$link = $base_link.'&op=einl&act=ok&housenr='.$k['houseid'].'&ids='.urlencode($_GET['ids']);
				output('<a href="'.$link.'">'.$k['housename'].' ('.item_count(' deposit1 = '.$k['houseid'].' AND deposit2 = 0  AND tpl_id != "trph" ').'/'.get_max_furniture($k['status']).')</a>`n',true);
				allownav($link);

			}
			addnav('Zurück',$base_link.'&op=einl&ids='.urlencode($_GET['ids']));

		}
        elseif($_GET['act'] == 'private') {

            output('`QDu besitzt Schlüssel zu Privatgemächern in diesen Häusern:`n`n');

            $sql = 'SELECT he.*,h.status,h.houseid,h.owner,h.housename FROM house_extensions he
					LEFT JOIN houses h USING(houseid)
					WHERE he.owner='.$session['user']['acctid'].' AND loc IS NOT null AND level > 0
					ORDER BY id ASC';
            $res = db_query($sql);

            while($k = db_fetch_assoc($res)) {
                $max_count_ges = 0;
                // Wenn durch den Gemachtyp Vorgaben gemacht werden:
                if(isset($g_arr_house_extensions[$k['type']]['max_furn'])) {
                    $max_count_ges = $g_arr_house_extensions[$k['type']]['max_furn'];
                }
                // Sonst allg. Limit
                else {
                    $max_count_ges = get_max_furniture($k['status'],true);
                }
                $k['name'] = '`&'.(empty($k['name']) ? $g_arr_house_extensions[$k['type']]['name'] : $k['name'].'`& ('.$g_arr_house_extensions[$k['type']]['name'].')');
                $k['name'] .= '`0`& - '.house_get_floor($k['loc']).' in '.$k['housename'].'`0';
                $k['name'] .= ' ('.item_count(' deposit1 = '.$k['houseid'].' AND deposit2 = '.$k['id'].'   AND tpl_id != "trph" ').'/'.$max_count_ges.')'; //Füllstandsanzeiger^^
                $link = $base_link.'&op=einl&act=ok&housenr='.$k['houseid'].'&private='.$k['id'].'&ids='.urlencode($_GET['ids']);
                output('`0<a href="'.$link.'">'.$k['name'].'</a>`n',true);
                allownav($link);

            }
            addnav('Zurück',$base_link.'&op=einl&ids='.urlencode($_GET['ids']));

        }
        elseif($_GET['act'] == 'rport') {

            output('`QDu besitzt folgende RP-Orte:`n`n');

            $sql = 'SELECT * FROM rp_worlds_places WHERE acctid='.intval($session['user']['acctid']).' ORDER BY id ASC';
            $res = db_query($sql);

            while($k = db_fetch_assoc($res)) {
                $link = $base_link.'&op=einl&act=rportok&rportnr='.$k['id'].'&ids='.urlencode($_GET['ids']);
                output('`0<a href="'.$link.'">'.$k['name'].'</a>`n',true);
                allownav($link);

            }
            addnav('Zurück',$base_link.'&op=einl&ids='.urlencode($_GET['ids']));

        }
        elseif($_GET['act'] == 'rportok')
        {

            $rportnr = (int)$_GET['rportnr'];

            if(!empty($_GET['ids'])) {
                $res = item_list_get(' owner='.$session['user']['acctid'].' AND id IN ('.db_real_escape_string(stripslashes(urldecode($_GET['ids']))).') AND deposit1=0 AND deposit2=0');
                $arr_items = db_create_list($res);

            }
            else {
                $arr_items = array($item);
            }

            if(!is_array($arr_items) || sizeof($arr_items) == 0) {
                redirect($ret);
            }

                $count_ges = item_count(' deposit1 = 23422342 AND deposit2 = '.$rportnr.' ');

                foreach ($arr_items as $item) {

                    if($item['tpl_class']==7)
                    {
                        // Check auf Gesamtzahl dieses Stücks
                        $max_count = $item['deposit_private'];

                        $count = item_count( '  deposit1 = 23422342 AND deposit2 = '.$rportnr.'  AND tpl_id="'.$item['tpl_id'].'"' );
                        output('`n');

                        if($max_count == 0)
                        {
                            output('`QMal ehrlich, du willst '.$item['name'].'`Q doch nicht wirklich einlagern?! Nicht mal ein Erztroll würde sowas tun..');
                        }
                        elseif($count >= $max_count)
                        {
                            output('`QDu kannst von diesem edlen Stück maximal '.$max_count.' Exemplare eingelagert haben. Mehr hätte einfach keinen Stil..');
                        }
                        else {
                            output("`QDu suchst für `q".$item['name']."`Q einen Ehrenplatz in deinem Haus, an dem `q".$item['name']."`Q von jetzt an den Staub fangen wird.");

                            item_set(' id='.$item['id'] , array('deposit1'=>23422342,'deposit2'=>$rportnr) );
                            $count_ges++;
                        }
                    }
                     else
                     {
                         output('`QMal ehrlich, warum solltest du '.$item['name'].'`Q in einem RP-Ort lagern wollen? Das ist doch gar kein Möbelstück...');
                     }

                }


        }
		elseif($_GET['act'] == 'ok') {

			$housenr = (int)$_GET['housenr'];
			$private = (int)$_GET['private'];

			if(!empty($_GET['ids'])) {
				$res = item_list_get(' owner='.$session['user']['acctid'].' AND id IN ('.db_real_escape_string(stripslashes(urldecode($_GET['ids']))).') AND deposit1=0 AND deposit2=0');
				$arr_items = db_create_list($res);

			}
			else {
				$arr_items = array($item);
			}

			if(!is_array($arr_items) || sizeof($arr_items) == 0) {
				redirect($ret);
			}

			$sql = 'SELECT status FROM houses WHERE houseid='.$housenr;
			$res = db_query($sql);
			$house = db_fetch_assoc($res);

			if($private) {
				$sql = 'SELECT type FROM house_extensions WHERE id='.$private;
				$res = db_query($sql);
				$room = db_fetch_assoc($res);

				// Wenn durch den Gemachtyp Vorgaben gemacht werden:
				if(isset($g_arr_house_extensions[$room['type']]['max_furn'])) {
					$max_count_ges = $g_arr_house_extensions[$room['type']]['max_furn'];
				}
				// Sonst allg. Limit
				else {
					$max_count_ges = get_max_furniture($house['status'],true);
				}

			}
			else {
				$max_count_ges = get_max_furniture($house['status']);
			}
			
			if($max_count_ges == 0)
			{
				output('`QHier ist kein Platz für Möbel!');
			}
			else 
			{
				$count_ges = item_count(' deposit1 = '.$housenr.' AND deposit2 = '.$private.'   AND tpl_id != "trph" ');
						
				foreach ($arr_items as $item) {
													
					if($count_ges >= $max_count_ges) {
						output('`n`QDu hast hier bereits `q'.$count_ges.'`Q Möbel deponiert. Mehr passt einfach nicht rein!');
						break;
					}
									
					// Check auf Gesamtzahl dieses Stücks
					$max_count = $item['deposit'.($private ? '_private' : '')];
					$count = item_count( ' deposit1 = '.$housenr.' AND deposit2 = '.$private.' AND tpl_id="'.$item['tpl_id'].'"' );
					output('`n');
					if($max_count == 0) {
						output('`QMal ehrlich, du willst '.$item['name'].'`Q doch nicht wirklich einlagern?! Nicht mal ein Erztroll würde sowas tun..');
					}
					elseif($count >= $max_count) {
						output('`QDu kannst von diesem edlen Stück maximal '.$max_count.' Exemplare eingelagert haben. Mehr hätte einfach keinen Stil..');
					}
					else {
						output("`QDu suchst für `q".$item['name']."`Q einen Ehrenplatz in deinem Haus, an dem `q".$item['name']."`Q von jetzt an den Staub fangen wird.");
	
						item_set(' id='.$item['id'] , array('deposit1'=>$housenr,'deposit2'=>$private) );
						$count_ges++;
					}
				}				
			}
						
		}
		else {

			output('`QWohin willst du `q'.(isset($str_ids) ? 'die Gegenstände' : $item['name']).'`Q bringen?');

			if($session['user']['house']) {addnav('Ins Haus',$base_link.'&op=einl&act=ok&housenr='.$session['user']['house'].'&ids='.urlencode($str_ids));}
            if(db_num_rows(db_query("SELECT he.id FROM house_extensions he WHERE he.loc IS NOT null AND he.owner=".$session['user']['acctid']." LIMIT 1")) > 0) {
                addnav('In Privatgemächer',$base_link.'&op=einl&act=private&ids='.urlencode($str_ids));
            }

            if(db_num_rows(db_query("SELECT * FROM rp_worlds_places WHERE acctid=".intval($session['user']['acctid'])." LIMIT 1")) > 0) {
                addnav('In RP-Ort',$base_link.'&op=einl&act=rport&ids='.urlencode($str_ids));
            }

		}

		addnav('Zum Inventar',$ret);

		break;

	// Auslagern aus Haus o. Gemach
	case 'ausl':

		// Multiselect
		if(!empty($_POST['ids']) && is_array($_POST['ids'])) {
			$str_ids = implode(',',$_POST['ids']);
			output('`QDu packst die Gegenstände wieder in dein Inventar.');

			item_set('id IN ('.db_intval_in_string($str_ids).') AND owner='.$session['user']['acctid'].' AND deposit1 !='.ITEM_LOC_EQUIPPED,array('deposit1'=>0,'deposit2'=>0) );
		}
		else {
			output('`QDu packst '.$item['name'].'`Q wieder in dein Inventar.');

			item_set('id='.$id,array('deposit1'=>0,'deposit2'=>0) );
		}

		addnav('Zum Inventar',$ret);

		break;

	// Waffe, Rüstung o.ä. anlegen
	case 'ausr':

		// Hook
		$item_hook_info['op'] = 'ausr';
		if(!empty($item['equip_hook'])) {
			item_load_hook($item['equip_hook'],'equip',$item);
		}

		if(!$item_hook_info['hookstop']) {

			if($item['equip'] == ITEM_EQUIP_WEAPON) {

				$w_old = item_set_weapon($item['name'],$item['value1'],$item['gold'],$id);

				$old_name = $w_old['name'];

				$old_attack = $session['user']['attack'] - $session['user']['weapondmg'] + $w_old['value1'];

				output('`QDu tauschst `q'.$old_name.'`Q gegen '.$item['name'].'`Q.
						Dein Angriff verändert sich dadurch von '.$old_attack.' auf '.$session['user']['attack'].'!');

			}

			else if($item['equip'] == ITEM_EQUIP_ARMOR) {
				
				if($item['value1'] == 0)//luxusgewand
				{
					if($session['user']['kleidung'] == ''){
						output('`QDu ziehst '.$item['name'].'`Q an. Schick siehst du aus!');
						
						item_set(' id='.$item['id'], array('deposit1'=>ITEM_LOC_EQUIPPED));
						
					}else{
						output('`QDu tauscht '.$session['user']['kleidung'].'`Q durch '.$item['name'].'`Q aus. Steht dir auch viel besser!');
						
						$old_kleidung = item_get(' name="'.db_real_escape_string($session['user']['kleidung']).'" AND owner='.$session['user']['acctid'].' AND deposit1='.ITEM_LOC_EQUIPPED);
						item_set(' id='.$old_kleidung['id'], array('deposit1'=>0));
						item_set(' id='.$item['id'], array('deposit1'=>ITEM_LOC_EQUIPPED));
					}
					
					$session['user']['kleidung'] = $item['name'];
				}
				else
				{
				
					$a_old = item_set_armor($item['name'],$item['value1'],$item['gold'],$id);
	
					$old_name = $a_old['name'];
	
					$old_defence = $session['user']['defence'] - $session['user']['armordef'] + $a_old['value1'];
	
					output('`QDu tauschst `q'.$old_name.'`Q gegen '.$item['name'].'`Q.
							Deine Verteidigung verändert sich dadurch von '.$old_defence.' auf '.$session['user']['defence'].'!');
				}

			}
		}

		addnav('Zum Inventar',$ret);

		break;

	// Angelegtes Item ablegen und in Invent zurückpacken
	case 'abl':

		if($_GET['what'] != '') {
			$item_hook_info['what'] = $_GET['what'];
		}
		else {
			if($item['equip'] == ITEM_EQUIP_WEAPON) {
				$item_hook_info['what'] = 'weapon';
			}
			else if($item['equip'] == ITEM_EQUIP_ARMOR) {
				if($item['value1'] == 0)//luxusgewand
				{
					$item_hook_info['what'] = 'kleidung';
				}
				else
				{
					$item_hook_info['what'] = 'armor';	
				}
			}
		}

		// Hook
		$item_hook_info['op'] = 'abl';
		if(!empty($item['equip_hook'])) {
			item_load_hook($item['equip_hook'],'equip',$item);
		}

		if(!$item_hook_info['hookstop']) {

			if($item_hook_info['what'] == 'weapon') {

				$old = $session['user']['attack'];

				// ohne Params, um Fäuste zu setzen
				$w_old = item_set_weapon();

				$old_name = $w_old['name'];

				output('`QDu legst `q'.$old_name.'`Q ab.
						Dein Angriff verändert sich dadurch von '.$old.' auf '.$session['user']['attack'].'!');

			}

			else if($item_hook_info['what'] == 'armor') {

				$old = $session['user']['defence'];

				// ohne Params, um Straßenkleidung zu setzen
				$a_old = item_set_armor();

				$old_name = $a_old['name'];

				output('`QDu legst `q'.$old_name.'`Q ab.
						Deine Verteidigung verändert sich dadurch von '.$old.' auf '.$session['user']['defence'].'!');

			}
			else if($item_hook_info['what'] == 'kleidung') {
				output('`QDu legst `q'.$session['user']['kleidung'].'`Q ab.');
				
				$kleidung = item_get(' name="'.db_real_escape_string($session['user']['kleidung']).'" AND owner='.$session['user']['acctid'].' AND deposit1='.ITEM_LOC_EQUIPPED);
				if($kleidung['id'] > 0)item_set(' id='.$kleidung['id'], array('deposit1'=>0));
				$session['user']['kleidung'] = '';
				

			}
		}

		addnav('Zum Inventar',$ret);

		break;


}

page_footer();
?>
