<?php
// Gemeinsames Modul für Kloster ff.

function house_build_kloster ($str_case, $arr_build, $arr_house) {
	
	global $session,$Char;

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
		{
			global $access_control;
			if($arr_build['id'] == 87 && $session['bufflist']['decbuff']['state']>0 )
			{
				addnav($session['bufflist']['decbuff']['realname']."`0 1 Tag abgeben","housefeats.php?act=discschool",false,false,false,false);
			}
		}
		break;
		
		// Übernachtungsbonus
		case 'wakeup':
			
			global $str_output,$nd;
			
			$str_output .= '`2Gut erholt wirst du '.$str_where.' in aller Früh durch Glockenläuten geweckt.`n`n';
			
			if ($nd)
			{
				if($arr_build['id'] == 80) {
					$str_output .= "`2Durch ein opulentes Frühstück und den Segen der Nonnen fühlst du dich gestärkt.`n";
					$session['user']['hitpoints'] *= 1.1;
				}
				elseif ($arr_build['id'] == 84) {
					$str_output .= "`2Durch ein opulentes Frühstück und den Segen der Klosterbrüder fühlst du dich gestärkt.`n";
					$session['user']['hitpoints'] *= 1.3;
				}
				elseif ($arr_build['id'] == 87) {
					$str_output .= "`2Durch ein opulentes Frühstück und den Segen des Ordens fühlst du dich gestärkt.`n";
					$session['user']['hitpoints'] *= 1.3;
				}
				
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