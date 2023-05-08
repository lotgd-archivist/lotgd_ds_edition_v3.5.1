<?php

function zauber_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook ) {
		
		case 'battle':
								
			break;
			
		case 'battle_arena':
						
			break;
			
		case 'newday':
			
			// Counter runterzählen						
			if( $item['hvalue'] <= 1) {
			
				item_delete( ' id='.$item['id'] );
				
			}
			else {
				
				// Counter runterzählen, Anwendungen für den Tag auffüllen
				item_set(' id='.$item['id'] , array('hvalue'=>$item['hvalue']-1,'value1'=>$item['value2']));				
				
			}
			
			break;
			
		case 'use':
					$list = '';
			if($item['buff1'] > 0) {$list .= ','.$item['buff1'];}
			if($item['buff2'] > 0) {$list .= ','.$item['buff2'];}
			
			$buffs = item_get_buffs( ITEM_BUFF_USE , $list );
			
			output('`QDu benutzt '.$item['name'].'`Q!');
			
			if(sizeof($buffs) > 0) {
				
				output('`n`nKurz darauf bemerkst du schon die ersten Effekte..`n');
				
				foreach($buffs as $b) {
					
					output($b['roundmsg'].'`n');		
					
				}
			}
									
			item_set_buffs( ITEM_BUFF_USE , $buffs );
					
			$item['gold']=round($item['gold']*($item['value1']/($item['value2']+1)));
			$item['gems']=round($item['gems']*($item['value1']/($item['value2']+1)));
			
			$item['value1']--;
			
			if ($item['value1']<=0 && $item['hvalue']<=0){
				item_delete(' id='.$item['id']);
			}else{
				item_set(' id='.$item['id'], $item);
			}
			
			addnav('Zum Beutel',$item_hook_info['ret']);
			
			break;
	}
		
	
}

?>