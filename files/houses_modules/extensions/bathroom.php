<?php
// Badezimmer
// by talion


// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_bathroom ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);

	switch($str_case) {

		// Innen
		case 'in':

			switch($_GET['act']) {

				case 'bath':

					output("`2Du betrittst das Badezimmer, um dich ein wenig frisch zu machen. 					Schnell ziehst du dich aus und gleitest in die Badewanne. Sorgfältig wäscht du den Dreck aus dem Wald ab.`n
					Du fühlst dich so richtig wohl und könntest für immer hier bleiben, doch langsam wird das Wasser kälter und du steigst daher wieder aus der Badewanne.`n");
				    if ($session['user']['sex']) { //weiblich
				        output("Du rasierst dir noch schnell die Beine, bevor du wieder in deine Kleider springst. Nach einer Stunde Schminken vor dem Spiegel bist du bereit für neue Abenteuer.`n");
				    }
					else { //männlich
				        output("Du rasierst dich noch und ziehst dich danach schnell wieder an. Erfrischt geht es auf zu neuen Abenteuern.`n");
				    } //if
				    switch (e_rand(0,7)) {
				    case 4: //get charmpoint
				        output("Du fühlst dich besonders erfrischt und schön und erhältst daher `6einen Charmepunkt.`2");
				        $session['user']['charm']++;
				        break;
				    case 6: // loose charmpoint
				        output("Du schneidest dich beim Rasieren und verlierst daher `6einen Charmepunkt.`2");
				        $session['user']['charm']--;
				        break;
				    } //switch

				    addnav('Zurück',$str_base_file);

				break;

				case '':

					addnav('In die Badewanne steigen!',$str_base_file.'&act=bath');

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
