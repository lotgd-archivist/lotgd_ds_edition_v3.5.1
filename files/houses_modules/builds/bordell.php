<?php
// Gemeinsames Modul für Bordell ff.

function house_build_bordell ($str_case, $arr_build, $arr_house) {
	
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
			
			// "Mafia"-Special
	        $sql = "SELECT beatenup FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
	        $result = db_query($sql);
	        $rowb = db_fetch_assoc($result);
	        addnav("F?\"Die Familie\" befragen","housefeats.php?act=checkfriend");
	        if ($rowb['beatenup']>1)
	        {
	            addnav("S?Schläger anheuern","housefeats.php?act=beater");
	        }
	        else
	        {
	            addnav("b?\"Familie\" beschenken","housefeats.php?act=familygift");
	        }
			
		break;
		
		// Übernachtungsbonus
		case 'wakeup':
			
			global $str_output,$nd;
			
			$happy = array("name"=>"`!Extrem gute Laune","rounds"=>($arr_build['id'] == 100 ? 60 : 75),"wearoff"=>"`!Deine gute Laune vergeht allmählich wieder.`0","defmod"=>1.15,"roundmsg"=>"Du schwelgst in Erinnerung an den Bordellbesuch und tust alles dafür dass es nicht dein Letzter war!","activate"=>"defense");
			
			if ($nd)
			{
				$str_output .= '`2Nach einer langen wild durchzechten Nacht erwachst du gut gelaunt im Bordell.`n`n';
				$str_output .= '`2War das eine Nacht!`n';
				$session['bufflist']['happy']=$happy;

				switch (e_rand(1,3))
				{
					case 1:
						break;
					case 2:
						addnews("`@".$session['user']['name']."`@ wurde gesehen, wie  ".($session['user']['sex']?"sie":"er")." mit einem breiten Grinsen ein Bordell verliess!");

						if ($session['user']['charisma']==4294967295)
						{
							$sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session['user']['marriedto']."";
							$result = db_query($sql);
							$row = db_fetch_assoc($result);
							$partner=$row['name'];
							systemmail($row['acctid'],"`$ Bordellbesuch!`0","`&{$session['user']['name']}
                    		`6 wurde gesehen, wie ".($session['user']['sex']?"sie":"er")." sich im Bordell vergnügt hat. Willst du dir das gefallen lassen ?");
						}
						break;
					case 3:
						break;
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