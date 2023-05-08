<?php
// Gemeinsames Modul für Gruft ff.

function house_build_gruft ($str_case, $arr_build, $arr_house) {
	
	global $session;

	$str_what = $arr_build['name'];
	$str_where = ((mb_substr($str_what,-1)=='n')?'in den':($arr_build['sex'] ? 'in der' : 'im'));
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
			if ($session['user']['level']>1)
			{
				addnav("B?Dem Blutgott opfern","housefeats.php?act=sacrifice");
			}
			
			if($session['bufflist']['decbuff']['state']>0)
			{
				if(($session['user']['race']=='vmp' || $session['user']['race']=='wwf' || $session['user']['race']=='dmn') && $session['bufflist']['decbuff']['state']<19)
				{
					$str_sure='Vorsicht! Dein Knappe könnte sich dauerhaft verändern. Willst du das?';
				}
				addnav($session['bufflist']['decbuff']['realname']."`0 beißen","housefeats.php?act=dbite",false,false,false,false,$str_sure);
			}
		}
		break;
		
		// Übernachtungsbonus
		case 'wakeup':
		{
			global $str_output,$nd;
			
			$str_output .= '`2Du erwachst '.$str_where.' und klappst stilecht den Sargdeckel hoch.`n`n';
			
			$gruftbon=($arr_build['id'] == 60 ? e_rand(10,50) : e_rand(30,60));
			
			if ($nd)
			{
				$str_output .= "`2Ramius gefällt das finstre Treiben so gut, dass er dir `#$gruftbon`2 Gefallen gewährt!`n";
				$session['user']['deathpower'] += $gruftbon;
			}
			else
			{
				$str_output .= '`2Gut erholt wachst du '.$str_where.' auf und bist bereit für neue Abenteuer.';
			}
		}
		break;
		
		
	}
	// END Main Switch
	
}
// END Main Function

?>