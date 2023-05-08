<?php
// Bankettsaal
// by talion


// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_banket ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);

	switch($str_case) {

		// Innen
		case 'in':

			$arr_to_serve = array(1=>array('Wildbret',10),2=>array('Schweinebraten',5),3=>array('Rotwein aus den südlichen Landen',20),4=>array('Eber aus den Dunklen Landen',30),5=>array('Waldbeerengelee',3),6=>array('Karaffe Wasser',1));
			
			switch($_GET['act']) {

				case 'serve':

					$str_out .= house_get_title('Bankettsaal - Speisen auftragen');

					if(isset($_POST['serve'])) {

						$int_id = (int)$_POST['serve'];

						$arr_serve = $arr_to_serve[$int_id];
						$int_amount = (int)$_POST['amount'];
						$int_amount = max($int_amount,1);

						if(isset($arr_serve)) {

							$int_cost = round($arr_serve[1] * $int_amount);

							if($int_amount > 100) {
								$str_out .= '`tWo soll das denn alles herkommen?! Ein bisschen Bescheidenheit bei der Anzahl der Portionen wäre angebracht!`0';
							}
							elseif ($int_cost > $session['user']['gold']) {
								$str_out .= '`tDies würde dich '.$int_cost.' Gold kosten - das kannst du dir nicht leisten!`0';
							}
							else {
								if(isset($arr_content['served'][$int_id])) {
									$arr_content['served'][$int_id] += $int_amount;
								}
								else {
									$arr_content['served'][$int_id] = $int_amount;
								}

								$str_out .= '`tDu zahlst '.$int_cost.' Gold, woraufhin '.$arr_serve[0].'`t aufgetragen wird.';
								$session['user']['gold'] -= $int_cost;

								db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);

								insertcommentary(1,'/msg `tDie Diener tragen eilfertig '.$arr_serve[0].'`t herbei und kredenzen es auf dem Bankett.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id']);

							}

						}

					}
					else {
							$str_food_enum='';
						foreach ($arr_to_serve as $int_id=>$arr_s) {
							$str_food_enum .= ','.$int_id.','.$arr_s[0].' ('.$arr_s[1].' Gold pro Portion)';
						}
												
						$arr_form = array	(
											'serve'=>'Speise / Trank:,enum'.$str_food_enum,
											'amount'=>'Portionen:,int'
											);
						$str_out .= form_header($str_base_file.'&act=serve').generateform($arr_form,array(),false,'Bestellen!').'</form>';

					}

				    addnav('Zurück',$str_base_file);

				break;

				case 'use':
					
					// amount of dishes still waiting for hungry guests
					$int_serve_info = 0;
					if(isset($_GET['kitchen']))
					{
						$str_id = urldecode(stripslashes($_GET['what']));
						if(isset($arr_content['served_kitchen'][$str_id]))
						{
							$int_serve_info = &$arr_content['served_kitchen'][$str_id]['amount'];
							$int_qual = $arr_content['served_kitchen'][$str_id]['qual'];
						}
						$str_what = $str_id;
					}
					else 
					{
						$int_id = (int)$_GET['what'];
						if(isset($arr_content['served'][$int_id]))
						{
							$int_serve_info = &$arr_content['served'][$int_id];
						}
						$str_what = $arr_to_serve[$int_id][0];		
					}
					
					if(empty($int_serve_info)) {
						$str_out .= '`tLeider war da wohl jemand schneller als du.. es ist nichts mehr übrig!';
					}
					else {
						insertcommentary($session['user']['acctid'],': `8nimmt sich etwas von '.$str_what.'`8.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id']);

						$int_serve_info--;
						if($int_serve_info <= 0) {
							if(isset($_GET['kitchen']))
							{
								unset($arr_content['served_kitchen'][$str_id]);
							}
							else {
								unset($arr_content['served'][$int_id]);
							}
							insertcommentary(1,'/msg `8Das war\'s dann wohl auch mit '.$str_what.'`8.. nichts mehr übrig!','h_room'.$arr_house['houseid'].'-'.$arr_ext['id']);
						}
						
						if(isset($int_qual))
						{
							include_once(ITEM_MOD_PATH.'kitchen.php');
							// Gourmet-Punkte (aber nur ein Drittel der normalen Menge)
							if(kitchen_process_gourmet($int_qual,$str_out,0.3))
							{
								insertcommentary(1,'/msg `8'.$session['user']['name'].'`8 hat sich soeben dank seines auf diesem Bankett abermals erwiesenen erlesenen Geschmacks den Titel des '.getsetting('townname','Atrahor').'-Schlemmers verdient!','h_room'.$arr_house['houseid'].'-'.$arr_ext['id']);
							}
						}
						
						db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);

						redirect($str_base_file);
					}

					addnav('Zurück',$str_base_file);

				break;

				case 'empty':

					if(isset($_GET['what'])) {
						// amount of dishes still waiting for hungry guests
						$int_serve_info = 0;
						if(isset($_GET['kitchen']))
						{
							$str_id = urldecode(stripslashes($_GET['what']));
							if(isset($arr_content['served_kitchen'][$str_id]))
							{
								$int_serve_info = &$arr_content['served_kitchen'][$str_id];
							}
							$str_what = $str_id;
						}
						else 
						{
							$int_id = (int)$_GET['what'];
							if(isset($arr_content['served'][$int_id]))
							{
								$int_serve_info = &$arr_content['served'][$int_id];
							}
							$str_what = $arr_to_serve[$int_id][0];		
						}

						if(!empty($int_serve_info)) {

							$str_out .= '`tDu weist die Diener an, '.$str_what.' wieder wegzuschaffen - selbstverständlich kommen sie deinem Befehl sogleich nach.';

							if(isset($_GET['kitchen']))
							{
								unset($arr_content['served_kitchen'][$str_id]);
							}
							else {
								unset($arr_content['served'][$int_id]);
							}

							db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);

							insertcommentary(1,'/msg `tDie Diener schaffen '.$str_what.'`t rasch wieder weg.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id']);

						}

					}
					else {
						$str_out .= '`tDu weist die Diener an, das gesamte Bankett wieder wegzuschaffen - selbstverständlich kommen sie deinem Befehl sogleich nach.';

						unset($arr_content['served']);
						unset($arr_content['served_kitchen']);
						
						db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);

						insertcommentary(1,'/msg `tAuf Geheiß des Hausherrn wird das gesamte Bankett leergeräumt.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id']);
					}

					addnav('Zurück',$str_base_file);

				break;

				case '':

					if($arr_ext['owner'] == $session['user']['acctid']) {
						addnav('Speisen auftragen!',$str_base_file.'&act=serve');
						if(sizeof($arr_content['served'])) {
							addnav('Bankett leeren!',$str_base_file.'&act=empty');
						}
					}
					if(sizeof($arr_content['served'])) {
						addnav('Bankett');
						foreach ($arr_content['served'] as $int_id => $int_amount) {
							$str_served = $arr_to_serve[$int_id][0];
							addnav($str_served,$str_base_file.'&act=use&what='.$int_id);
							if($arr_ext['owner'] == $session['user']['acctid']) {
								addnav($str_served.'`0 abtragen!',$str_base_file.'&act=empty&what='.$int_id);
							}
						}
					}
					
					// das hier ist kein Standardfutter, sondern kommt aus der Küche
					if(sizeof($arr_content['served_kitchen']))
					{
						addnav('Frisch aus der Küche');
						foreach ($arr_content['served_kitchen'] as $str_name => $int_amount) {
							$str_served = $str_name;
							addnav($str_served,$str_base_file.'&act=use&kitchen=1&what='.urlencode($str_name));
							if($arr_ext['owner'] == $session['user']['acctid']) {
								addnav($str_served.'`0 abtragen!',$str_base_file.'&act=empty&kitchen=1what='.urlencode($str_name));
							}
						}
					}

				break;
				default:

				break;
			}

			output($str_out);

			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;
		// END case in

		// Bau gestartet
		case 'build_start':

			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;

		// Bau fertig
		case 'build_finished':

			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;

		// Abreißen
		case 'rip':

			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;

	}	// END Main switch
}


?>
