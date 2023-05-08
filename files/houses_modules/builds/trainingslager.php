<?php
// Gemeinsames Modul für trainingslager ff.

function house_build_trainingslager ($str_case, $arr_build, $arr_house) {
	
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
			
			if ($nd)
			{
				//neue Erfahrungsformel
				$int_rec = get_exp_required($session['user']['level']-1,$session['user']['dragonkills']);
				$int_req = get_exp_required($session['user']['level'],$session['user']['dragonkills']);
				
				if($arr_build['id'] == 90) {
					$str_output .= "`2Du erwachst am frühen Morgen durch lautes Schwerterklirren im Trainingslager.`n";
					$kasbon = round(max($int_req - $int_rec,0) * 0.18);
				}
				elseif ($arr_build['id'] == 94) {
					$str_output .= "`2Du erwachst am frühen Morgen durch die lauten Marschgesänge in der Kaserne.`n";
					$kasbon = round(max($int_req - $int_rec,0) * 0.35);
				}
				elseif ($arr_build['id'] == 97) {
					$str_output .= "`2Du erwachst am frühen Morgen durch die lauten Marschgesänge im Söldnerlager.`n";
					$kasbon = round(max($int_req - $int_rec,0) * 0.35);
				}
				
				$str_output .= "Die Geschichten der Veteranen, denen du noch bis spät in die Nacht gelauscht hast, waren dir eine große Lehre. Du erhältst `#$kasbon`2 Erfahrung!";
				$session['user']['experience']+=$kasbon;
				
			}
			else
			{
				$str_output .= '`2Gut erholt wachst du '.$str_where.' auf und bist bereit für neue Abenteuer.';
			}
			
		break;
		
		
	}
	// END Main Switch
	
}
// END Main Function

?>