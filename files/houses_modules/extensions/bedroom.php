<?php
// Schlafzimmer
// by talion

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_bedroom ($str_case, $arr_ext, $arr_house) {
	
	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;
			
	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);
			
	_rooms_common_set_env($arr_ext,$arr_house);
		
	switch($str_case) {
		
		// Innen
		case 'in':
									
			switch($_GET['act']) {
				
				case 'meditate':
					
					output('`tDu meditierst über ');
					
					switch(e_rand(1,3)) {
						
						case 1:
							output('den Flug der Wolken.');
						break;
						
						case 2:
							output('das Dasein einer Gänsefeder.');
						break;
						
						case 3:
							output('das erfüllte Leben einer Blumenvase.');
						break;
						
					}
					
					// Nutzen wir den Glückskeks, falls vorhanden
					$arr_glk = item_get_tpl('tpl_id="glckskeks"','hookcode');
					if(false !== $arr_glk) {
						item_load_hook('_codehook_','',$arr_glk);
						output('`nDabei kommt dir folgende Einsicht: `^'.mb_substr($GLOBALS['hook_item']['tpl_description'],mb_strpos($GLOBALS['hook_item']['tpl_description'],'"')));
					}
					
					addnav('Zurück',$str_base_file);
					
				break;
				
				case '':
					
					addnav('Meditieren',$str_base_file.'&act=meditate');
					
				break;
						
				default:
					
										
				break;
			}
			
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
