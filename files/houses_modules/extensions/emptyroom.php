<?php
// Leerer Raum (Hier kann praktisch alles rein)
// by talion

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_emptyroom ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);

	switch($str_case) {

		// Innen
		case 'in':

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
