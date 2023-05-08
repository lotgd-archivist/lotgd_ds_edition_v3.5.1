<?php
// Gemeinsames Modul für Versteck ff.

function house_build_versteck ($str_case, $arr_build, $arr_house) {
	
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

			// Versteck / Gewölbe: Malus 
			if($arr_build['id'] == 30 || $arr_build['id'] == 37) {		
				if ($nd)
				{
					if($arr_build['id'] == 30) {
						$str_output .= '`2Du erwachst in deinem Versteck mit Rückenschmerzen und sehr schlecht erholt.`n`n';
						$str_output .= '`2Die Nacht war so schrecklich, dass du Lebenspunkte verlierst!`n';
						
						$mal = e_rand(30,70);
						$mal*=0.01;
						$ache = array("name"=>"`!Gliederschmerzen","rounds"=>400,"wearoff"=>"`!Es geht dir nun wieder besser.`0","defmod"=>0.95,"atkmod"=>0.95,"roundmsg"=>"Die letzte Nacht war grauenvoll!","activate"=>"offense");
					}
					else {
						$str_output .= '`2Du erwachst im Kellergewölbe mit leichten Gliederschmerzen und nicht so gut erholt.`n`n';
						$str_output .= '`2Die Rast war so unangenehm, dass du Lebenspunkte verlierst!`n';
						
						$mal = e_rand(50,90);
						$mal*=0.01;
						$ache = array("name"=>"`!Leichte Gliederschmerzen","rounds"=>300,"wearoff"=>"`!Es geht dir nun wieder besser.`0","defmod"=>0.97,"atkmod"=>0.97,"roundmsg"=>"Die letzte Nacht war mies!","activate"=>"offense");
					}
					$session['bufflist']['ache']=$ache;
					$session['user']['hitpoints']*=$mal;
					
				}
				else
				{
					
					$str_output .= '`2Du erwachst nach einem Nickerchen '.$str_where.' und bist dankbar, endlich hier raus zu kommen.';
					
				}
			}
			else {
				$str_output .= '`2Du erwachst im Refugium und fühlst dich einigermassen erholt.';
			}
									
		break;
		
			
	}
	// END Main Switch	
	
}
// END Main Function

?>