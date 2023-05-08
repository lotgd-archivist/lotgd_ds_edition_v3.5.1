<?php
// Gemeinsames Modul für Bauernhof ff.

function house_build_bauernhof ($str_case, $arr_build, $arr_house) {
	
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
			
			// Wenn Stall existiert:
			$sql = 'SELECT * FROM house_extensions WHERE type="stables" AND houseid='.$arr_house['houseid'];
			$res = db_query($sql);
			if(db_num_rows($res)) {
				$arr_stall = db_fetch_assoc($res);
				
				global $g_arr_house_extensions,$str_out;
				
				if($arr_stall['level'] < $g_arr_house_extensions['stables']['maxlvl_job']) {
					$str_out .= '`n`tDein Hausausbau begünstigt deinen Stall, wodurch dieser um eine Stufe steigt!`0`n';
					db_query('UPDATE house_extensions SET level=level+1 WHERE id='.$arr_stall['id']);		
				}
			}
			db_free_result($res);
            			
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
			
			if($arr_build['id'] == 50) {
				$str_output .= '`2Ein lauter Hahnenschrei weckt dich in aller Früh auf dem Bauernhof.`n`n';
				$baubon = $session['user']['level']*30;
			}
			elseif ($arr_build['id'] == 54) {
				$str_output .= "`2Ein lautes Schnauben und Wiehern weckt dich in aller Früh auf der Tierfarm.`n`n";
				$baubon = $session['user']['level']*70;
			}
			elseif ($arr_build['id'] == 57) {
				$str_output .= "`2Die Arbeit ruft in aller Früh auf dem Gutshof.`n`n";
				$baubon = $session['user']['level']*70;
			}
										
			if ($nd)
			{
				$str_output .= "`2Du hast hart gearbeitet und bekommst dafür `#$baubon`2 Gold!`n";
				$session['user']['gold'] += $baubon;
				
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