<?php
// Lagerraum
// by Dragonslayer

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_storeroom ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner,$arr_content;
	
	_rooms_common_set_env($arr_ext,$arr_house);

	$str_content = '';

	switch($str_case) {

		// Innen
		case 'in':

			switch($_GET['act']) {

				case 'seek':
					$str_content .= house_get_title('Du schwelgst in Erinnerungen');

    				$res = house_get_items($arr_ext['houseid'],$arr_ext['id'],' ORDER BY rand() LIMIT 1');

					$str_content .= '`tDu stöberst eine Weile in den Gegenständen und hast mit einem Mal `0';

					if(db_num_rows($res)>0)
					{

						$arr_item = db_fetch_assoc($res);

						$str_content .= '`t ein(e) `0';

						$str_content .= $arr_item['name'];

						$str_content .= '`t in deinen Händen. Ach wie schön, was da für Erinnerungen dran hängen...`0';

						$str_content .= '`tAchtlos schmeisst Du den Plunder wieder weg.';

						addnav('Weiterbuddeln!',$str_base_file.'&act=seek');
					}
					else
					{
						$str_content .= '`tNICHTS in den Händen. Absolut reines, pures NICHTS. Ungefiltertes existenzielles NICHTS, ja du möchtest sogar behaupten: Gar NICHTS`0';
					}

				    addnav('Zurück',$str_base_file);
				    output($str_content);

				break;

				case 'clean':
					$str_content .= house_get_title('Saubermachen!');
					$str_content .= '`tDu schwingst den Mopp wie ein Weltmeister und verjagst Spinnenweben und Staubkrümel. Du bist ein Virtuose des Staubwedels!`0';

					$arr_content['last_clean'] = time();

					db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);
					addnav('Zurück',$str_base_file);
				    output($str_content);
					break;

				case '':

					if(!empty($arr_ext['name']))
					{
						// Das dem User auf jeden Fall unter die Nase reiben
						$arr_ext['name'] = trim($arr_ext['name']);
						$arr_ext['name'] .= ' (Eine Abstellkammer)';
					}
					if(!empty($arr_content['desc']))
					{
						$arr_content['desc'] .= '`n';
					}
					else
					{
						$arr_content['desc'] .= '`tDas ist eine Abstellkammer wie jede andere auch. Gerümpel überall und mittendrin alle möglichen Dinge
						die man bis zum jüngsten Gericht vergessen aufhebt. Was soll man dazu noch großartig sagen... es passt enorm viel hinein und alles
						ist total verstaubt. So ist das eben mit Lagerräumen.';
					}
					if(!isset($arr_content['last_clean']))
					{
						// erster Schaden nach 5 Tagen
						$arr_content['last_clean'] = time() - round(864000 * 0.5);
					}
					
					if($arr_content['last_clean']<(time()-864000) && e_rand(1,20)==1)
					{
    					$res = house_get_items($arr_ext['houseid'],$arr_ext['id'],' LIMIT 1');
						if(db_num_rows($res))
						{
	    					$arr_item = db_fetch_assoc($res);
	
	    					//Ich darf nix löschen hat Talinchen gesagt :-(
	    					//item_delete('id='.$arr_item['id'],1);
	    					// Talinchen: Items verlieren an Wert
	    					if(e_rand(1,3) != 3) {
	    						$arr_item['gold'] = max($arr_item['gold']-5,0);
	    					}
	    					else {
	    						$arr_item['gems'] = max($arr_item['gems']-1,0);
	    					}
	    					item_set('id='.$arr_item['id'],$arr_item);
	
							insertcommentary(1,'/msg `tVerdammte Ratten, die haben dein '.$arr_item['name'].' angefressen, da liegen noch überall die Reste rum. Du hättest mal ab und an putzen sollen','h_room'.$arr_house['houseid'].'-'.$arr_ext['id']);
						}
					}

					addnav('Durchstöbern!',$str_base_file.'&act=seek');
					addnav('Putzen!',$str_base_file.'&act=clean');

					output($str_content);

				break;
				default:

				break;
			}

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

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
