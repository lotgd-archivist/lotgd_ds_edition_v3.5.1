<?php

function idol_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook )
	{
		case 'pvp_victory':
			
			$badguy = $item_hook_info['badguy'];
			
			output('`n`^Du nimmst '.$badguy['creaturename'].' `^das '.$item['name'].'`^ ab!`0`n');
			addnews('`^'.$session['user']['name'].'`^ nimmt '.$badguy['creaturename'].'`^ das '.$item['name'].' `^ab!');
			
			if($item['value1']>=20)
			{
				$item['value1']=e_rand(5,19);
			}
			item_set(' id='.$item['id'], array('owner'=>$session['user']['acctid'],'value1'=>$item['value1']) );
			
			break;

		case 'newday':

			if ($item['value1']>=25)
			{
				systemmail($session['user']['acctid'],'`$Du hast etwas verloren!`0','`& Das '.$item['name'].'`& ist letzte Nacht auf unerklärliche Weise aus deinem Besitz verschwunden!');
				item_delete( ' id='.$item['id']);
			}
			else
			{
				switch($item['tpl_id'])
				{
					case 'idolgnie':
					{
						$amount=3;
						
						if ($session['user']['marks'] & CHOSEN_AIR)
						{
							$extra='wird durch dein Mal der Luft verstärkt und';
							$amount=6; 
						}
						
						output('`n`n`^Das `!Idol des Genies`^ '.$extra.' erhöht deine Donation Punkte um '.$amount.'!`n');
						$session['user']['donation']+=$amount;
						break;
					}

					case 'idolfish':
					{
						$amount=3; //orig 4
						
						if ($session['user']['marks'] & CHOSEN_WATER)
						{
							$extra='wird durch dein Mal des Wassers verstärkt und';
							$amount=6; //orig 8
						}
						
						output('`n`n`^Das `2Idol des Anglers`^ '.$extra.' gewährt dir für heute '.$amount.' zusätzliche Angelrunden!`n');
						$sql = "UPDATE account_extra_info SET fishturn=fishturn+$amount WHERE acctid = ".$session['user']['acctid'];
						db_query($sql) or die(sql_error($sql));
						break;
					}

					case 'idolkmpf':
					{
						$amount=1; //orig 2
						
						if ($session['user']['marks'] & CHOSEN_FIRE)
						{
							$extra='wird durch dein Mal des Feuers verstärkt und';
							$amount=2; //orig 4
						}
						
						output('`n`n`^Das `4Idol des Kriegers`^ '.$extra.' erhöht deine heutigen PvP-Kämpfe um '.$amount.'!`n');
						$session['user']['playerfights']+=$amount;
						break;
					}

					case 'idolrnds':
					{
						$amount=4; //orig 5
						
						if ($session['user']['marks'] & CHOSEN_EARTH)
						{
							$extra='wird durch dein Mal der Erde verstärkt und';
							$amount=8; //orig 10
						}
						
						output('`n`n`^Das Idol des Waldläufers '.$extra.' erhöht deine heutigen Runden um '.$amount.'!`n');
						$session['user']['turns']+=$amount;
						break;
					}

					case 'idoldead':
					{
						$amount=2; //orig 3
						
						if ($session['user']['marks'] & CHOSEN_SPIRIT)
						{
							$extra='wird durch dein Mal des Geistes verstärkt und';
							$amount=4; //orig 6
						}
						
						output('`n`n`^Das `&Idol des Totenbeschwörers`^ '.$extra.' gewährt dir heute '.$amount.' zusätzliche Grabkämpfe!`n');
						$session['user']['gravefights']+=$amount;
						break;
					}

				}
				$item_change['value1'] = $item['value1']+1;
				item_set('id='.$item['id'],$item_change);
			}
	}
	
}

?>
