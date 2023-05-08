<?php

function xmas_card_hook_process($item_hook , &$item )
{

	global $session,$item_hook_info;
    if(!isset($str_output))$str_output='';
	switch ($item_hook )
	{

		case 'use':
			{
				if(!is_array($item['content']))
				{
					$item['content'] = adv_unserialize($item['content']);
				}
				
				$str_output .= get_title('`IEine Weihnachtskarte`0').'`c'.print_frame('
	        		<table style="width:600px;">
	        			<tr>
	        				<td colspan="2" style="text-align:center; border-bottom:1px solid gold; padding-bottom:5px;">
	        					`IEine Weihnachtskarte von '.$item['content']['sender'].'`I aus dem Jahr '.$item['content']['year'].'
	        				</td>
	        			</tr>
			        	<tr>
			        		<td  valign="top" style="width:256px;"><img id="preview_image" width="256" height="256" src="./images/xmas/'.$item['content']['image'].'" /></td>
			        		<td valign="top">			        								
			        			'.stripslashes($item['content']['message']).'
			        		</td>
			        	</tr>
			        </table>',
			    '`IFrohohohe Weihnachten`0',0,true)
	        	.'`c';
	        							
				addnav('Zurück',$item_hook_info['ret']);
				output($str_output);
				break;
			}
	}
}
?>