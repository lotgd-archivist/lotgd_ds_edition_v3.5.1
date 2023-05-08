<?php

require_once(LIB_PATH.'runes.lib.php');

function runen_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook ){
		
		case 'furniture':
			//Einrichtungshook by Salator 
			$sql = "SELECT name FROM accounts WHERE acctid=".$item['owner'];
			$result2 = db_query($sql);
			$rowo = db_fetch_assoc($result2);
			
			output('`&In einer herrlich großen Vitrine im rustikalen Landhaus-Stil hat '.$rowo['name'].'`& ein paar ganz besondere Dinge zum Betrachten ausgestellt.`0`n`n
			`n<table border="0"><tr class="trhead"><th colspan="2">Von '.strip_appoencode($rowo['name']).' gesammelte Runen:</th></tr>');
		
			$sql='SELECT i.tpl_id, i.name,i.special_info, COUNT(*) as c 
				FROM items i 
				JOIN items_tpl it ON it.tpl_id = i.tpl_id 
				WHERE owner='.$item['owner'].' 
				AND (it.tpl_class='.RUNE_CLASS_ID.' 
					AND it.tpl_id <> "'.RUNE_DUMMY_TPL.'" 
					OR it.tpl_id IN ('.RUNE_MAGIC_STUFF.'))
				GROUP BY i.tpl_id 
				ORDER BY i.special_info, i.value2';
			$result = db_query($sql);
			//$result=runes_get(false,true,$item['owner']); //wäre natürlich einfacher, das ruft aber nur eigene Runen ab und gruppiert nicht

			$totalcount=0;
			if (db_num_rows($result)==0)
			{
				output('<tr><td colspan"2">`iLeider gibt es hier außer ein paar missratenen Kritzeleien nichts zu sehen.`i</td></tr>'); 
			}
			else
			{
				while($rune = db_fetch_assoc($result))
				{
					$bgclass=($bgclass=='trdark'?'trlight':'trdark');
					$str_out.='<tr class="'.$bgclass.'">
					<td align="center"><img src="./images/runes/'.($rune['special_info']!=''?$rune['special_info']:mb_substr($rune['tpl_id'],2)).'.png" alt="Runenstein"></td>
					<td>`F'.$rune['name'].'`0'.($rune['c']>1?'`n('.$rune['c'].' Exemplare)':'').'</td>
					</tr>';
					$totalcount+=$rune['c'];
				}
			}
			output($str_out.'</table>
			`n`&Insgesamt gibt es hier `^'.$totalcount.'`& Runen!`n`0');
			
			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			
			break;

			case 'find_forest':
			$class_id 	= getsetting('runes_classid',0);
			$ident	  	= user_get_aei('runes_ident');
			$ident  	= utf8_unserialize($ident['runes_ident']);
			$sql		= 'SELECT id FROM '.RUNE_EI_TABLE.' WHERE seltenheit<='.e_rand(1,255).' ORDER BY RAND() LIMIT 1';
			$res		= db_query( $sql );
			if( $res ){
				$rune	= db_fetch_assoc( $res );
				if( $rune ){			
					$item['tpl_value2'] = $rune[ 'id' ];
				}
			}			
			
			if( !$item['tpl_value2'] ){
				$item_hook_info['hookstop'] = 1;	
			}
			elseif( isset($ident[$rune[ 'id' ]]) ){
				$item	= item_get_tpl('tpl_class = '.$class_id.' AND tpl_value2='.$rune[ 'id' ]);
			}		
		break;	
		
		
		case 'send_hook':
			$dest 	= $item_hook_info['recipient']['acctid'];		
			$ident	= user_get_aei('runes_ident', $dest);
			$ident  = utf8_unserialize($ident['runes_ident']);
			
			if( $item['tpl_id'] != RUNE_DUMMY_TPL && !isset($ident[$item['value2']]) ){
				$tpl					= item_get_tpl('tpl_id="'.RUNE_DUMMY_TPL.'"');
				$item['name'] 			= $tpl['tpl_name']; 	 	    	    	    	    	    	    	 							
				$item['description'] 	= $tpl['tpl_description']; 									
				$item['gold'] 			= $tpl['tpl_gold']; 							
				$item['gems'] 			= $tpl['tpl_gems']; 								
				$item['value1'] 		= $tpl['tpl_value1']; 								 								
				$item['hvalue'] 		= $tpl['tpl_hvalue']; 								
				$item['hvalue2'] 		= $tpl['tpl_hvalue2'];
				$item['tpl_id']			= RUNE_DUMMY_TPL;
				$item_hook_info['mail_msg'] = '- '.$item['name'];
			}
			else if($item['tpl_id'] == RUNE_DUMMY_TPL && isset($ident[$item['value2']]) ){
				$tpl					= item_get_tpl('tpl_class = '.$item['tpl_class'].' AND tpl_value2='.$item['value2']);
				$item['name'] 			= $tpl['tpl_name'];  		 	 	    	    	    	    	    	    	 							
				$item['description'] 	= $tpl['tpl_description']; 									
				$item['gold'] 			= $tpl['tpl_gold']; 							
				$item['gems'] 			= $tpl['tpl_gems']; 								
				$item['value1'] 		= $tpl['tpl_value1']; 								 								
				$item['hvalue'] 		= $tpl['tpl_hvalue']; 								
				$item['hvalue2'] 		= $tpl['tpl_hvalue2'];
				$item['tpl_id']			= $tpl['tpl_id'];
				$item_hook_info['mail_msg'] = '- '.$item['name'];
			}	
		
		break;
		
		
		default:
			echo $item_hook."<br>";
			print_r($item_hook_info);
			die();
			
		
		break;
	
	}
	
	
}
	
	
	
	
	
	
?>
