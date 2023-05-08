<?php
// Gummizelle (OOC-Raum)
// by talion

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_gummizelle ($str_case, $arr_ext, $arr_house) {
	
	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;
			
	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);
			
	_rooms_common_set_env($arr_ext,$arr_house);
		
	switch($str_case) {
		
		// Innen
		case 'in':
								
			$str_out = '';
			
			switch($_GET['act']) {
						
				case '':
					// Gummizellen-Funktion
					if($bool_not_invited) {
						addnav('Gummizelle');
						addnav('Durch die Gegend hopsen',$str_base_file.'&act=hop');
						addnav('In dunkle Ecke setzen',$str_base_file.'&act=corner');
					}
				break;			
				
				case 'hop':
					
					$str_out = '`qDu hopst durch die Zelle und fühlst dich dabei wie ein Quietscheentchen in seinen besten Jahren.`n
								Deine durchschnittliche Hops-Geschwindigkeit beträgt im Moment '.e_rand(1,10).' Fuß in der Minute.';
					
					addnav('Erstmal Pause',$str_base_file);
										
				break;
				
				case 'corner':
					
					$str_out = '`!Du setzt dich in die dunkelste, einsamste, verlassenste (usw.) Ecke der Zelle und schluchzt still vor dich hin.`n
									Im Märchen würde dir nun eine schöne Taube oder eine Fee mit üppigen Rundungen erscheinen.`n`n`n`n
									Leider sind wir hier nicht im Märchen. Für dein Rumgeheule bekommst du einen Waldkampf abgezogen.`n`n';
					if($session['user']['turns'] > 0) {
						$session['user']['turns']--;	
					}
					else {
						$str_out .= 'Faul bist du auch noch?! Jetzt schau aber, dass du dich vom Acker machst! Geh hopsen, Gringo!`n';
					}
					
					$str_out .= '`n(Insgeheim hoffst du wohl, beim nächsten Mal was besseres zu erhalten. Wer weiß.. und pass auf die zufrieden seufzende Tür auf, sonst verlierst du gleich noch einen Waldkampf!)';
					
					addnav('*SCHLUCHZ*',$str_base_file);
										
				break;
				
				default:
					
				break;
			}
			
			output($str_out);
			
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
