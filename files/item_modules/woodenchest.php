<?php

// Truhe zur Aufbewahrung gildeninventarfähiger Gegenstände für gildenlose Spieler
// Ursprungscode Eichhörnchenzuchtfarm by Maris (Maraxxus@gmx.de)
// Edit: Laulajatar

function woodenchest_hook_process($item_hook , &$item )
{

	global $session,$item_hook_info;

	switch ($item_hook )
	{

		//	Aktionen bei Nutzung
		case 'furniture':
			$arr_content=utf8_unserialize(($item['content']));
			if(!is_array($arr_content)) $arr_content=array();
			
			if ($item['owner']!=$session['user']['acctid'])
			{
				output('`&Du stellst dich vor die Truhe und betrachtest sie eine Weile. Schließlich bist du dir sicher: Es gibt nur eine Person, die den Schlüssel dazu hat und das bist `bnicht`b du.`n');
				addnav('Sonstiges');
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			}
			else if ($item_hook_info['op'] == 'take')
			{
				$items = $arr_content[$_GET['obj_id']];
				output('`&Du greifst in die Truhe und nimmst '.$items['name'].'`& in die Hand.`nWas willst du nun damit tun? Zum Mitnehmen benötigst du einen Waldkampf.`n`n');
				
				if ($session['user']['turns'] > 0) addnav("Mitnehmen",$item_hook_info['link'].'&op=take2&obj_id='.$items['id']);
			  addnav("Zurück legen",$item_hook_info['link']);
			}
			
			else if ($item_hook_info['op'] == 'take2')
			{
				$items = $arr_content[$_GET['obj_id']];
				if ($item['id'])
				{
					$items['tpl_name']=$items['name'];
					$items['tpl_description']=$items['description'];
					$items['tpl_value1']=$items['value1'];
					$items['tpl_value2']=$items['value2'];
					$items['tpl_hvalue']=0;
					$items['tpl_hvalue2']=0;
					$items['tpl_gold']=0;
					$items['tpl_gems']=0;
					$items['tpl_special_info']=$items['special_info'];
					$items['original_id']=$items['id']; //muss in item_add noch implementiert werden //Dragonslayer: Wurde gemacht!
					if(item_add($session['user']['acctid'],0,$items))
					{
						unset($arr_content[$items['id']]);
						item_set($item['id'],array('content' => db_real_escape_string(utf8_serialize($arr_content))));
						output("`&Du packst ".$items['name']."`& wieder in deinen Beutel!`n`n");
						$session['user']['turns']--;
						addnav("Auf gehts!",$item_hook_info['link']);
					}
					else
					{
						output('error recreating item');
						addnav("Mist!",$item_hook_info['link']);
					}
				}
				else
				{
					output('`&Noch während du den Gegenstand aus der Truhe nimmst, fällt er dir durch die Finger. Na hoppla!`n');
					addnav('Zurück',$item_hook_info['link']);
				}
			}
			else if ($item_hook_info['op'] == 'putin')
			{
				// 3 Beute, 14 Zauber, 25 Nahrungsmittel, 26 Rohstoffe, 29 Saatgut -> ,25,26,29
				output("`&Du öffnest deinen Beutel und betrachtest, was du so dabei hast.`n`n`\$Bedenke aber, dass alle hier abgelegten Gegenstände danach höchstens noch ideellen Wert für dich haben werden.`n`n<table border='0'><tr><td>`t`bFolgende Gegenstände kannst du in die Truhe legen:`b</td></tr><tr><td valign='top'>");
				$sql = 'SELECT i.id,i.name,i.gold,i.gems FROM items i LEFT JOIN items_tpl it ON i.tpl_id=it.tpl_id WHERE owner='.$session['user']['acctid'].' AND deposit1="0" AND it.tpl_class IN (3,14) ORDER BY i.name ASC';
				$result = db_query($sql);
				$amount=db_num_rows($result);
				if (!$amount)
				{
					output("`iKeine einzigen!");
				}
				for ($i=1; $i<=$amount; $i++)
				{
					$items = db_fetch_assoc($result);
					output("<a href=".$item_hook_info['link']."&op=putin2&obj_id=".$items['id'].">`&-".$items['name']."`&(Wert: `^".$items['gold']."`&Gold / `#".$items['gems']."`&Edelsteine`&)</a>`0`n");
					addnav("",$item_hook_info['link'].'&op=putin2&obj_id='.$items['id']);
				}
				output('</td></tr></table>');
				addnav('Zur Truhe',$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'putin2')
			{
				output("`&Du legst den Gegenstand in die Truhe und klappst den Deckel zu. Hier ist er ganz sicher sicher aufgehoben.`n`n");
				$items = item_get(' id='.$_GET['obj_id'].' AND owner='.$session['user']['acctid'],false);
				$arr_content[$items['id']]=$items;
				item_set('id='.$item['id'],array('content' => db_real_escape_string(utf8_serialize($arr_content))));
				
				item_delete('id='.$items['id']);
				$session['user']['turns']--;
				addnav("Zurück",$item_hook_info['link']);
			}
			
			else
			{
				output("`&Du holst den einzigen Schlüssel zu der großen, alten Truhe aus deiner Tasche, steckst ihn ins Schloss, drehst ihn um und klappst den Deckel hoch.`n`n");
				output("`n<table border='0'><tr><td>`t`bDerzeit befinden sich in der Truhe:`b</td></tr><tr><td valign='top'>",true);
				$amount=count($arr_content);
				if (!$amount)
				{
					output("`iNur ein paar Spinnenweben und Staub!`i");
				}
				foreach ($arr_content as $key => $items)
				{
					output("<a href=".$item_hook_info['link']."&op=take&obj_id=".$items['id'].">`&-".$items['name']."</a>`0`n",true);
					addnav("",$item_hook_info['link'].'&op=take&obj_id='.$items['id']);
				}
				output("</td></tr></table>",true);
				if ($amount<5)
				{
					addnav('Gegenstände');
					addnav('Hineinlegen',$item_hook_info['link'].'&op=putin');
				}
				else
				{
					addnav("Truhe voll!");
				}
				output("`n`n`n");
				addnav('Sonstiges');
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			}
			break;

	}
}
?>