<?php
// Gemeinsames Modul für Anwesen, Villa, Gasthaus

function house_build_anwesen ($str_case, $arr_build, $arr_house) {
	
	global $session;

	$str_what = $arr_build['name'];
	$str_where = $arr_build['sex'] ? 'in der' : 'im';
	$str_where .= ' '.$str_what;
		
	switch($str_case) {
		
		// House-Feature (evtl.)
		case 'in':
						
			
		break;
		// END House-Feature
		
		// Bau fertig
		case 'build_finished':

			global $str_out;
					
			$str_out .= '`7Du erhältst `@'.$arr_build['keys_add'].'`7 weitere Schlüssel!';
          			
		break;
		
		// Abreißen
		case 'rip':
			
			global $str_out;
			
			// Sicherheitsabfrage
			if(!isset($_GET['ok'])) {
				
				$str_out .= '`7Deine vergrößerte Schatzkammer wird entfernt. Alles Gold und alle Edelsteine, die danach zuviel in deiner Truhe sind, gehen `4UNWIEDERBRINGLICH VERLOREN!`7`n
								Zusätzlich erhaltene Schlüssel werden entfernt.`n';
								
			}
			else {
												
				// Schatzkammer wird automatisch auf Normalmaß gestutzt!				
				
			}
			
		break;
		
		// Übernachtungsbonus
		case 'wakeup':
			
			global $str_output,$nd;
			
			$str_output .= '`2Du erwachst umgeben von Luxus und Wohlstand '.$str_where.'.`n`n';
			if ($nd == 1)
			{
				if($arr_build['id'] == 10) {
					$reward = e_rand(1,2);
				}
				else {
					$reward = e_rand(1,3);
				}
				$str_output .= "`2Nach dem Aufstehen nimmst du erstmal ein heißes Bad und richtest dich schön her. Du erhältst `#$reward Charmepunkte`2.`n";
				$session['user']['charm']+=$reward;
			}
			else
			{
				$str_output .= 'Nach einem Nickerchen '.$str_where.' bis du gut erholt für neue Taten.';
			}
			
		break;
		
			
	}
	// END Main Switch	
	
}
// END Main Function

?>