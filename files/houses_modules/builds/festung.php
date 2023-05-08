<?php
// Gemeinsames Modul für Festung, Turm, Burg

function house_build_festung ($str_case, $arr_build, $arr_house) {
	
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
			            			
		break;
		
		// Abreißen
		case 'rip':
			
			
						
		break;
		
		// Übernachtungsbonus
		case 'wakeup':
			
			global $str_output,$nd;
			
			$str_output .= '`2Gut erholt erwachst du '.$str_where.' und bist bereit für neue Abenteuer.`n`n';
			if ($nd == 1)
			{
				$str_output .= "Die sichere Umgebung hat dich mal wieder richtig gut schlafen lassen. Du bekommst";
				if($arr_build['id'] == 20) {
 					$session['user']['turns']++;
 					$str_output .= " einen zusätzlichen Waldkampf für heute.`n";
				}
				else {
					$fesbon = e_rand(1,2);
					$session['user']['turns']+=$fesbon;
 					$str_output .= " $fesbon zusätzliche Waldkämpfe für heute.`n";
				}
			}
			else
			{
				$str_output .= '';
			}
			
		break;
		
			
	}
	// END Main Switch	
	
}
// END Main Function

?>