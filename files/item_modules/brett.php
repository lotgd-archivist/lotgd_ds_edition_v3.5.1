<?php

function brett_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook ) {
		
		case 'furniture':
			$str_boardId = 'housex'.$item_hook_info['hid'];
			if($item_hook_info['private']) {
				$str_boardId .= '_'.$item_hook_info['private'];
			}
			
			// Wie viele Nachrichten gleichzeitig?
			$int_max_posts = 3;
			
			if(isset($_GET['ext'])){ //Mitbewohner-Schreibrecht setzen
				item_set(' id='.$item['id'],array('value1' => intval($_GET['ext'])));
				redirect($item_hook_info['link']);	
			}
			// Je nachdem ob Privatgemach oder Haus Besitzerinfo abrufen
			$arr_house = db_fetch_assoc(db_query('
				SELECT h.owner,a.name AS ownername,a.sex 
				FROM '.($item_hook_info['private'] ? 'house_extensions':'houses').' h 
				LEFT JOIN accounts a ON a.acctid=h.owner 
				WHERE 
				'.($item_hook_info['private'] 
				?'h.id='.$item_hook_info['private']
				:'h.houseid='.$item_hook_info['hid'])
			));
			$bool_owner = ($arr_house['owner'] == $session['user']['acctid'] ? true : false);
							
			output('`&Du betrachtest das dreckige Stück Holz genauer, welches an der Wand angebracht ist. Auf den zweiten Blick fällt dir erst auf, dass hier anscheinend des öfteren'.(!empty($arr_house['ownername']) ? ' von '.$arr_house['ownername'].'`&' : '').' beschriftete Pergamentstückchen provisorisch in das verwitterte Holz gepinnt werden.`n`n');
			
			require_once(LIB_PATH.'board.lib.php');

			board_view($str_boardId,($bool_owner ? 2:($item['value1']==1 ? 1:0)),'An der Wand sind folgende Nachrichten zu lesen:','Es scheinen keine Nachrichten vom Eigentümer vorhanden zu sein.',true,false,false,true,200,true);	
			
			output('`n`n');
			
			if($bool_owner) {
				
				board_view_form("Aufhängen","`&Hier kannst Du als Hauseigentümer".($session['user']['sex'] ? 'in' : '')." eine Nachricht hinterlassen:");
				if($_GET['board_action'] == "add") {
					if(board_add($str_boardId,100,$int_max_posts) != -1) {
						redirect($item_hook_info['link']);	
					}
					output('`n`&Das Platzangebot reicht leider für gerade einmal '.$int_max_posts.' Nachrichten gleichzeitig - entferne zuerst eine der bereits vorhandenen Nachrichten.');
				}
				if ($item['value1']==1){
					addnav('Bewohnern Schreibrecht entziehen',$item_hook_info['link'].'&ext=0');
				}
				else{
					addnav('Bewohnern Schreibrecht geben',$item_hook_info['link'].'&ext=1');
				}
			}
			elseif ($item['value1']==1){ //Schreibrecht für alle
				board_view_form("Aufhängen","`&Hier kannst Du eine Nachricht hinterlassen:");
				if($_GET['board_action'] == "add") {
					if(board_add($str_boardId,100,1) != -1) {
						redirect($item_hook_info['link']);	
					}
					output('`n`&Du wirst doch nicht das ganze Brett für dich nutzen wollen? Entferne zuerst deine vorhandene Nachricht.');
				}		
			}
					
			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			
		break;
			
	}
		
	
}

?>
