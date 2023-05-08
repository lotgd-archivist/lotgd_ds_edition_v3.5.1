<?php
// Gemeinsames Modul für Kerker ff.

function house_build_kerker ($str_case, $arr_build, $arr_house) {
	
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
		
		// Navi
		case 'navi':
			
			
			
		break;
		
		// Übernachtungsbonus
		case 'wakeup':
			
			global $str_output,$nd;
			
			$str_output .= '`2Die Schreie der Gefangenen '.$str_where.' wecken dich am Morgen.`n`n';
						
			if ($nd)
			{
				$str_output .= "`2Für die Übernahme des Wachdienstes entlohnt dich der Kerkermeister mit `#einem Edelstein`2!`n";
				$session['user']['gems']++;
				if($arr_build['id'] > 70) {
					$str_output .= '`nDu erhältst einen Spielerkampf zusätzlich!';
					$session['user']['playerfights']++;
				}
				
			}
			else
			{
				$str_output .= '`2Gut erholt wachst du im Wärterzimmer '.$str_where.' auf und bist bereit für neue Abenteuer.';
			}
									
		break;
		
			
	}
	// END Main Switch	
	
}
// END Main Function

?>