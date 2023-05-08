<?php
// Gemeinsames Modul für Gildenhaus ff.

function house_build_gildenhaus ($str_case, $arr_build, $arr_house) {
	
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
			
			// Knappe suchen (im Zunfthaus)
	        $sql = "SELECT name,state FROM disciples WHERE master=".$session['user']['acctid']."";
	        $result = db_query($sql);
	        $rowk['state']=0;
	        if (db_num_rows($result)>0)
	        {
	            $rowk = db_fetch_assoc($result);
	        }
	        if ($rowk['state'] == 0 && db_num_rows($result))
	        {
	            addnav("K?Verlorenen Knappen suchen","housefeats.php?act=searchdisciple");
	        }
			
		break;
		
		// Übernachtungsbonus
		case 'wakeup':
			
			global $str_output,$nd;
			
			if ($nd)
			{
				$sql_where=' WHERE active="1" ';
				if($session['user']['exchangequest']<29)
				{
					$sql_where.=' AND usename!="wisdom" ';
				}
				$sql = 'SELECT specid,specname,filename,usename FROM specialty '.$sql_where.' ORDER BY RAND() LIMIT 1';
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				
				$reward=($arr_build['id'] == 40 ? e_rand(1,4) : e_rand(2,5));

				$str_output .= '`2Du erwachst gut erholt '.$str_where.'.`n`n';
				$str_output .= '`2Die abendliche Diskussion mit den Meistern brachte dir `#'.$reward.'`2 zusätzliche Anwendungen in '.$row['specname'];
				
				$session['user']['specialtyuses'][$row['usename']."uses"]+=$reward;
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