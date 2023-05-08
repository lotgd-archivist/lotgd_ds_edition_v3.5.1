<?php
//spirituelle Symbole als Einrichtung und Waffe

function woodencross_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	$upsidedown=mb_strstr(strip_appoencode($item['name']),'kopfstehend');
	
	switch ( $item_hook ) {
		
		case 'furniture':
		{ //Möglichkeiten wenn es ein Möbel ist
			if($_GET['act']=='pray')
			{ //anbeten
				output('`qRituell entzündest du ein paar Kerzen, die auf dem Altar mit dem '.$item['name'].'`q stehen und versinkst in Meditation.
				`nDies kostet dich die Zeit für einen Waldkampf. Ob sonst etwas passiert ist weißt du jedoch nicht.');
				//sollte hier irgendwas passieren oder sind wir einfach nur fies?
				$session['user']['turns']--;
			}
			elseif($_GET['act']=='torture')
			{ //einen Schläfer zum Quälen aussuchen
				$sql='SELECT a.acctid,a.name,a.race,aei.abused,r.colname 
					FROM accounts a 
					LEFT JOIN account_extra_info aei ON a.acctid=aei.acctid
					LEFT JOIN races r ON a.race=r.id
					WHERE restatlocation="'.$session['housekey'].'" 
					AND abused=0
					AND race NOT IN ('.($upsidedown?'"dmn","vmp","wwf"':'"eng"').') 
					ORDER BY laston 
					LIMIT 25';
				$result=db_query($sql);
				if(db_num_rows($result)>0)
				{
					$str_out='<table border="0" align="center" cellspacing="3">
					<tr class="trhead"><th>Name</th><th>Rasse</th><th>Aktion<th></tr>';
					while($row=db_fetch_assoc($result))
					{
						$str_out.='<tr>
						<td>'.$row['name'].'</td>
						<td align="center">'.$row['colname'].'</td>
						<td>'.create_lnk('Quälen',$item_hook_info['link'].'&act=torture2&acctid='.$row['acctid']).'</td>
						</tr>';
					}
					output($str_out.'</table>');
				}
				else
				{
					output('`4In diesem Haus gibt es keine Wesen, die sich für den Versuch '.($upsidedown?'einer Bekehrung':'eines Exorzismus').' eignen.');
				}
			}
			elseif($_GET['act']=='torture2')
			{ //einen Schläfer quälen };->
				$row=db_fetch_assoc(db_query('SELECT name FROM accounts WHERE acctid='.(int)$_GET['acctid']));
				systemmail((int)$_GET['acctid'],'`4Ritual`0',$session['user']['name'].'`Q ist heute Nacht an deine Schlafstätte gekommen und hat mit einem '.$item['name'].'`Q ein Ritual an dir zelebriert.`nDieses traumatische Erlebnis wird dich noch lange verfolgen.');
				user_set_aei(array('abused'=>1),(int)$_GET['acctid']);
				output('`qDu nimmst dir '.$item['name'].'`q und tanzt damit um die Schlafstelle von '.$row['name'].'`q.
				`nOb die Gehirnwäsche funktioniert hat lässt sich zu diesem Zeitpunkt nicht sagen. Sei aber darauf gefasst, Bekanntschaft mit harten Gegenständen in der Hand von '.$row['name'].'`q zu machen.
				`n`nDieses Ritual hat dich jedoch so geschwächt, du wirst heute niemanden mehr bekehren können.');
				$session['user']['playerfights']=0;
			}
			else
			{ //Startbild
				if(
					(!$upsidedown &&
						($session['user']['race']=='dmn' ||
						$session['user']['race']=='vmp' ||
						$session['user']['race']=='wwf')
					)
				|| ($upsidedown && 
						$session['user']['race']=='eng'))
				{ //Rasse passt nicht zum Symbol
					output('`$WAAAH! Du schaffst es einfach nicht, näher an dieses verflixte Ding heranzutreten!');
				}
				else
				{ //Rasse passt zum Symbol
					output('`QDu betrachtest dieses Ding eine Weile, aber es bleibt ein '.$item['name'].'`Q. Hast du etwas anderes erwartet?
					`nManche Wesen glauben, es bringe Seelenheil, ein '.$item['name'].'`Q anzubeten, andere wiederum können nichtmal in die Nähe dieses Symbols.
					`nAls Waffe eingesetzt würde es einen Angriffswert von '.$item['value1'].' haben.'.($item['owner']==$session['uer']['acctid']?' Wenn es nur nicht so gut festgenagelt wäre...':''));
					if($session['user']['turns']>0)
					{
						addnav('Aktionen');
						addnav('Gebet',$item_hook_info['link'].'&act=pray');
						if($session['user']['playerfights']>0)
						{
							addnav(($upsidedown?'Bekehrung':'Exorzismus'),$item_hook_info['link'].'&act=torture');
						}
						addnav('');
					}
				}
			}
			addnav('Nix wie weg hier!',$item_hook_info['back_link']);
			break;
		}

		case 'use':
		{ //Kreuz umdrehen
			if($upsidedown)
			{
				$item['name']=strip_appoencode($item['name']); //oder gibt es eine zuverlässige Möglichkeit, die Trenn-Stelle auch bei eigener Färbung zu finden?
				$name=str_replace('kopfstehendes ','',$item['name']);
			}
			else
			{
				$name='kopfstehendes '.$item['name'];
			}
			output('`n`QDu drehst '.$item['name'].'`Q und damit seine Wirkungsweise um.`n`n');
			item_set('id='.$item['id'],array('name'=>$name));
			addnav('Zum Inventar',$item_hook_info['ret']);
			break;
		}

		case 'equip':
		{ //kleiner Text beim Anlegen
			if($upsidedown)
			{
				output('`n`QDu willst der Welt zeigen, dass du ein wahrer Antichrist bist.`n');
			}
			else
			{
				output('`n`QDu begibst dich auf einen Kreuzzug gegen all das Teuflische da draußen.`n');
			}
			break;
		}

		case 'newday':
		{ //eingelagerte Items mit Angriff>3 resetten
			if($item['value1']>3)
			{
				$item_tpl=item_get_tpl('tpl_id="'.$item['tpl_id'].'"');
				item_set('id='.$item['id'],array(
				'name'=>($upsidedown?'kopfstehendes ':'').$item_tpl['tpl_name'],
				'gold'=>$item_tpl['tpl_gold'],
				'gems'=>$item_tpl['tpl_gems'],
				'value1'=>'2'));
			}
			break;
		}

		default:
			output('`n`&Fehler: Unterfunktion '.$item_hook.' nicht gefunden.`n');
	}
}

?>