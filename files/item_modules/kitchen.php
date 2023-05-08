<?php

// Hilfsprozedur
function kitchen_get_qualadj ($int_qual)
{
	$str_qual = '';
	if($int_qual > 250)
	{
		$str_qual = 'Absolut ungenießbar';
	}
	elseif($int_qual > 230)
	{
		$str_qual = 'Ungenießbar';
	}
	elseif($int_qual > 210)
	{
		$str_qual = 'Abscheulich';
	}
	elseif($int_qual > 190)
	{
		$str_qual = 'Abstoßend';
	}
	elseif($int_qual > 170)
	{
		$str_qual = 'Unterdurchschnittlich';
	}
	elseif($int_qual > 150)
	{
		$str_qual = 'Mittelmäßig';
	}
	elseif($int_qual > 130)
	{
		$str_qual = 'Ordentlich';
	}
	elseif($int_qual > 110)
	{
		$str_qual = 'Überdurchschnittlich';
	}
	elseif($int_qual > 90)
	{
		$str_qual = 'Gut';
	}
	elseif($int_qual > 70)
	{
		$str_qual = 'Sehr gut';
	}
	elseif($int_qual > 50)
	{
		$str_qual = 'Exquisit';
	}
	elseif($int_qual > 30)
	{
		$str_qual = 'Hervorragend';
	}
	elseif($int_qual > 10)
	{
		$str_qual = 'Absolut exzellent';
	}
	elseif($int_qual > 1)
	{
		$str_qual = 'Absolut erstklassig';
	}
	elseif($int_qual == 1)
	{
		$str_qual = 'Besser geht\'s nicht!';
	}
	return $str_qual;
}

function kitchen_process_gourmet ($int_qual,&$str_ret,$float_fac=1.0)
{
	global $session;
	
	// Wettbewerb aus
	if(getsetting('kitchen_gourmetpts',1) == 0)
	{
		return false;
	}
	
	// Ein Palindrom, ein Palindrom!
	$str_pot = getsetting('kitchen_toppot','dnrgrgl');
	
	$int_points = round((255 / max(1,$int_qual)) * 0.1 * $float_fac);
	if($int_points > 0) {
		db_query('UPDATE account_extra_info SET gourmet = gourmet + '.$int_points.' WHERE acctid='.$session['user']['acctid']);
		
		// Spieler noch nicht top
		if(item_count('tpl_id="'.$str_pot.'" AND owner='.$session['user']['acctid']) == 0)
		{
			// 100 Punkte müssen mindestens erreicht werden
			$res = db_query('SELECT acctid FROM account_extra_info WHERE gourmet>100 ORDER BY gourmet DESC LIMIT 1');
			$arr_tmp = db_fetch_assoc($res);
			if($arr_tmp['acctid'] == $session['user']['acctid'])
			{
				if(($arr_tpl = item_get_tpl('tpl_id="'.$str_pot.'"','tpl_name')) === false)
				{
					systemlog('Küchen-Addon: Der Topf ('.$str_pot.') für den besten Gourmet existiert noch nicht!');
					return false;
				}
				// Gourmet-König
				$str_ret .= '`nDein Ruf als herausragender Kenner heimischer Küche hat dir den Titel des `n`n
						`c`b'.getsetting('townname','Atrahor').'-Schlemmers`c`b`n`n
						eingebracht! Herzlichen Glückwunsch! '.$arr_tpl['tpl_name'].' darf nun eine Weile unter deinen Händen brodeln.';
				if(item_set('tpl_id="dnrgrgl"',array('deposit1'=>0,'deposit2'=>0,'owner'=>$session['user']['acctid']),true,1) === false)
				{
					item_add($session['user']['acctid'],'dnrgrgl');
				}
				return true;															
			}
		}
	}
	return false;
}

function kitchen_set_qual (&$int_qual,&$str_desc,$int_vary=30)
{
	$int_vary = ceil($int_qual * 0.01 * e_rand(1,$int_vary));
	if(e_rand(1,4) <= 2)
	{
		$int_vary *= -1;
	}

	$int_qual = min(max($int_qual + $int_vary,0),255);
	if(!is_null($str_desc))
	{
		$str_qual = kitchen_get_qualadj($int_qual);
		$str_desc .= '`nQualität: '.$str_qual;
	}
}

function kitchen_hook_process ( $item_hook , &$item ) {

	global $session, $item_hook_info;

	switch ( $item_hook ) {
		
		// Verderbnis-Hook am Newday
		case 'newday':
		case 'newday_deposit':
			
			// Markieren für potenzielle Verderbnis
			if($item['hvalue2'] == 0)
			{
				switch(e_rand(1,10))
				{
					case 2:
						$item['hvalue2'] = 1;
						$item['hvalue'] = min($item['hvalue']+5,255);
						$item['name'] = '`g'.$item['name'].'`0';
						$item['description'] .= '`n`gRiecht komisch..`0';
						item_set('id='.$item['id'],$item);
						output('`n`n'.$item['name'].'`0 sieht ziemlich übel aus. Vielleicht solltest du es schnell essen, ehe es die Maden tun.`n`n');
					break;
				}
				
			}
			else 
			{
				if(e_rand(1,5) == 2)
				{
					item_delete('id='.$item['id']);
					output('`n`n'.$item['name'].'`0 war so verdorben, dass du es lieber entfernt hast. All die Maden waren wirklich kein schöner Anblick mehr..`n`n');
				}
			}
												
		break;
		
		// Handels-Hook
		case 'trade':
			
			if($item_hook_info['codeloc'] == 'start' && $item_hook_info['do'] == 'buy')
			{
				$str_tpl = '';
				// Qualitäts-Varianz
				if($item_hook_info['tpl'])
				{
					$str_tpl = 'tpl_';
				}
				
				kitchen_set_qual($itemnew[$str_tpl.'hvalue'],$itemnew[$str_tpl.'description']);
							
			}
					
		break;
		
		// Benutzen-Hook
		case 'use':
			
			output('Du probierst '.$item['name'].'`0.');
			if($item['hvalue'] < 150)
			{
				output('`n.. und lässt es dir munden - ausgezeichnete Küche!');
				// Gourmet-Punkte
				$str_ret = '';
				kitchen_process_gourmet($item['hvalue'],$str_ret);
				output($str_ret);
			}
			else {
				output('`n.. kannst es aber nicht wirklich genießen. Irgendwas fehlt.. vermutlich Geschmack.');
			}
			
			item_delete('id='.$item['id']);
			addnav('Zurück zum Beutel',$item_hook_info['ret']);
			
		break;
		
		// Combo-Hook für die Mahlzeiten
		case 'alchemy':
			
			// Werte der Zutaten in bestimmten Bereichen aufaddieren
			$item_hook_info['product']['tpl_hvalue'] = 0;
			$item_hook_info['product']['tpl_gold'] = $item_hook_info['product']['tpl_gems'] = 0;
			
			$str_ingr = '';
			
			foreach ($item_hook_info['items_in'] as $arr_i) {
				$item_hook_info['product']['tpl_gold'] += round($arr_i['gold']*0.75);
				$item_hook_info['product']['tpl_gems'] += round($arr_i['gems']*0.75);
				if(!empty($str_ingr))
				{
					$str_ingr .= ', ';
				}
				
				// Wenn schon Mahlzeit reingepackt wird: als tpl endstufe hernehmen
				if($arr_i['tpl_id'] == 'mhlzt_res1')
				{
					if(!isset($arr_tpl_new))
					{
						// evtl. noch weitere Felder abrufen
						$arr_tpl_new = item_get_tpl('tpl_id="mhlzt_res2"','tpl_id');
						$item_hook_info['product']['tpl_id'] = $arr_tpl_new['tpl_id'];
					}
					$str_ingr .= mb_substr($arr_i['description']
										,mb_strpos($arr_i['description'],'Zutaten: ')+9
										);
					// Qualität: Durchschnittswert
					$item_hook_info['product']['tpl_hvalue'] = round($arr_i['hvalue']*0.5);
				}
				else {
					$str_ingr .= strip_appoencode($arr_i['name'],3);
					$item_hook_info['product']['tpl_hvalue'] += round($arr_i['hvalue']*0.5);
				}
			}
			
			// Zutaten alphabetisch sortieren
			$arr_tmp = explode(', ',$str_ingr);
			//$arr_tmp = array_walk($arr_tmp,'trim');
			sort($arr_tmp);
			$str_ingr = implode(', ',$arr_tmp);
			// END Zutaten alphabet. sortieren
						
			// Qualität ermitteln (Je > min_chance, desto schlechter)
			$item_hook_info['min_chance'] -= (500 / ($item_hook_info['product']['tpl_hvalue']+1)); // +1 damit kein DIV0 entsteht
			$item_hook_info['min_chance'] = max($item_hook_info['min_chance'],1);
			
			$str_qual = kitchen_get_qualadj($item_hook_info['min_chance']);
			$item_hook_info['product']['tpl_hvalue'] = $item_hook_info['min_chance'];
		
			$item_hook_info['product']['tpl_description'] = '`0Ein Gericht von '.$session['user']['name'].'`0 der Qualität "`b'.$str_qual.'`b" bestehend aus den Zutaten: '.$str_ingr;
			
			$item_hook_info['victory_msg'] .= '`n`n`bDein Gericht hat die Qualität: "'.$str_qual.'"`b`n';
			
			$item_hook_info['product']['tpl_name'] = 'Mahlzeit von '.$session['user']['login'];
							
			// Auswahl eines Namens für das Gericht
			$item_hook_info['enable_name_dish'] = true;
								
			// In der Küche lagern
			/*$item_hook_info['product']['deposit1'] = $item_hook_info['house']['houseid'];
			$item_hook_info['product']['deposit2'] = $item_hook_info['ext']['id'];*/
			$item_hook_info['product']['tpl_value1'] = $item_hook_info['ext']['id'];
			
			output('`n`bDu richtest deine Mahlzeit auf dem Küchentisch an.`b`n');										
		break;

	}


}

?>