<?php

function stinkbombe_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook ) {
		
		case 'newday':


			if(e_rand(1,10) == 1) {
			
				output('`n`c`$WHAM!`c`n`n`4Was da gerade in die Luft geflogen ist, war einmal deine `6Stink`$bombe.`4 Jetzt ist es nur noch... `6Stink`4. Du verlierst den Großteil deiner Lebenspunkte und solltest zunächst dem Heiler einen Besuch abstatten!`0`n`n`&(Aber wasch dich davor..)`0`n`n');    
				
				$session['user']['hitpoints'] = 3;
				
				item_delete(' id='.$item['id']);
			
			}
			
			
		break;
		
		case 'use':
		
			if(!empty($_GET['hid'])) {
			    				
				if($_GET['hid'] != $session['user']['house']) {
					if(e_rand(1,2) == 1) {
						item_delete(' id='.$item['id']);
						
						$session['user']['hitpoints'] = 3;
						
						output('`n`c`$WHAM!`c`n`n`4Was da gerade in die Luft geflogen ist, war einmal deine `6Stink`$bombe.`4 Jetzt ist es nur noch... `6Stink`4. Du verlierst den Großteil deiner Lebenspunkte und solltest zunächst dem Heiler einen Besuch abstatten!`0`n`n`&(Aber wasch dich davor..)`0`n`n');    
						
						addnav('Ach menno..',$item_hook_info['ret']);
						
						page_footer();
						exit;
					}
				}
				 
				$res = db_query('SELECT acctid FROM accounts WHERE restatlocation='.$_GET['hid']);
				
				if(!db_num_rows($res)) {
					output('Leider schläft dort drin gar niemand.. So macht das keinen Spaß!');
					
					addnav('Ach menno..',$item_hook_info['ret']);
					page_footer();
					exit;
				}
				
				$arr_id_lst = db_create_list($res,false,true);
				
				if(e_rand(1,3) != 1) {
					$str_haunter = '`6Stink`$bombe`0';
					$str_who = 'Ein Unbekannter';
				}
				else {
					$str_haunter = '`6Stink`$bombe`0 von `b'.db_real_escape_string($session['user']['name']).'`b`0';
					$str_who = $session['user']['name'];
				}
				
				output('`&Ohne weiter zu zögern deponierst du die Stinkbombe vor dem Haus Nr. '.$_GET['hid'].' und schleichst dann innerlich kichernd von dannen. Hoffentlich haben die hier Schlafenden auch so viel Spaß, wenn deine Kleine ihre verhängnisvolle Wirkung entfaltet. Und bete zu den Göttern, dass sie nicht herausfinden, wem sie diese Unterhaltung verdanken..');  
				
				insertcommentary(1,'/msg `6'.$str_who.' hat soeben eine Stinkbombe vor dem Haus versteckt! Dieses traumatische und vor allem stinkende Erlebnis wird die Schlafenden noch lange verfolgen..','house-'.$_GET['hid']);  
				
				$str_ids = implode(',',$arr_id_lst);
				
				user_set_aei(array('hauntedby'=>$str_haunter),-1,' acctid IN ('.db_real_escape_string($str_ids).') ');

				item_delete(' id='.$item['id']);
							
				addnav('Ver-duft-en..',$item_hook_info['ret']);
				page_footer();
				exit;
			}
			
			
			$sql = 'SELECT restatlocation, COUNT( * ) AS c, housename, houseid
			FROM accounts
			LEFT JOIN houses ON houseid = restatlocation
			WHERE restatlocation >0
			GROUP BY restatlocation
			ORDER BY c DESC
			LIMIT 25 ';
			
			$res = db_query($sql);
			
			output('`&Dies ist eine Liste der bekanntesten und auffälligsten Häuser Atrahors.. Such dir aus, vor welchem dieser Häuser du deine Kleine verstecken möchtest.`n
			'.($session['user']['house'] ? 'Alternativ kannst du als Freund exzentrischen Humors auch dein eigenes Haus damit beglücken.`n' : '').'`n
			<ul>',true);
			
			$str_lnk = $item_hook_info['link'].'&op=use&hid=';
			
			if($session['user']['house']) {
			
				output('<li>'.create_lnk('Dein eigenes Haus',$str_lnk.$session['user']['house'],true,true).'</li>',true);
			
			}
			
			while($h = db_fetch_assoc($res)) {
				
				output('<li>'.create_lnk(''.strip_appoencode($h['housename'],3),$str_lnk.$h['houseid'],true,true).'</li>',true);
											
			}
			
			addnav('Zurück');
			addnav('Stinkbombe weglegen',$item_hook_info['ret']);
			
		break;
		

				
			
	}
		
	
}

?>