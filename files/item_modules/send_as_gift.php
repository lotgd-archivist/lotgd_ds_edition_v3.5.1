<?php

function send_as_gift_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	switch ( $item_hook ) {
		
		case 'send_hook': //Versenden übers Handelshaus
			if(!mb_strpos($item['description'],'`& geschenkt.')){
				$item['description'].='`n`&Das hat dir '.$session['user']['name'].'`& geschenkt.';
			}
			$item['gems']=0;
			$item['gold']=3;
			item_set('id='.$item['id'],$item);
			break;
			
		default:
			output('`n`&Fehler: Unterfunktion '.$item_hook.' nicht gefunden.`n');
			
	}
}

?>