<?php

// Waffenschaukasten für die lustigen Waldgegnerwaffen

function waffenschrank_hook_process($item_hook , &$item )
{

	global $session,$item_hook_info;

	switch ($item_hook)
	{

		//	Aktionen bei Nutzung
		case 'furniture':
			
			$str_output = '';
			
			$arr_content=utf8_unserialize(($item['content']));
			if(!is_array($arr_content)) $arr_content=array();
			
			$str_output .= '`c`b`hDer Waffenschrank`0`b`c`n';      
			
			if ($item_hook_info['op'] == 'change')
			{
				$str_output .= ($item['value1'] == 1 ? '`KIm Moment können `balle mit Zugang zu deinem Haus`b Waffen in deinen Waffenschrank legen. Möchtest du das ändern, so dass nur noch du Waffen hineinlegen kannst?' : '`KIm Moment kannst `bnur du`b Waffen in deinen Waffenschrank legen. Möchtest du das ändern, so dass alle mit Zugang zu deinem Haus Waffen hineinlegen (aber nicht entfernen) können?'); 
				addnav('Ja',$item_hook_info['link'].'&op=change2');
				addnav('Nein',$item_hook_info['link']);
			}
			
			else if ($item_hook_info['op'] == 'change2')
			{
				if ($item['value1'] == 1)
				{
					item_set(' id='.$item['id'],array('value1' => 0));
				}
				else
				{
					item_set(' id='.$item['id'],array('value1' => 1));
				}
				redirect($item_hook_info['link']);
			}
			
			else if ($item_hook_info['op'] == 'putin')
			{
				$str_output .= '`KDu öffnest deinen Beutel und durchsuchst ihn nach Andenken der Besonderen Art an deine Kämpfe im Wald. Was wäre besser zum Angeben geeignet, als die Waffen deiner besiegten Feinde?`n`n`$Doch du weißt auch, dass du diese Waffe bei dem Versuch, sie jemals wieder aus diesem Schrank zu holen, unwiederbringlich zerstören wirst.`n`n`K`bFolgende Gegenstände kannst du in die Truhe legen:`n`n`b';
				
				$sql2 = 'SELECT name,id,gold
					FROM items
					WHERE owner='.$session['user']['acctid'].'
					AND deposit1="0"
					AND tpl_id IN ("waffedummy","ausweider","quizweap","dmons")
					ORDER BY name ASC';
				$result2 = db_query($sql2);
				$amount = db_num_rows($result2);
				
				if (!$amount) // Haben wir Waffen? 
				{
					$str_output .= '`&`iKeine einzigen!`i';
				}
				
				for ($i=1; $i<=$amount; $i++)
				{
					$items2 = db_fetch_assoc($result2);
				
					$bool_exists = false;
					foreach($arr_content as $key => $val)
					{
						if($val == $items2['name']) $bool_exists = true;
					}
					
					$str_output .= '`0<a href='.utf8_htmlentities($item_hook_info['link'].'&op=putin2&obj_id='.$items2['id']).'>`&-'.$items2['name'].' `&(Wert: `^'.$items2['gold'].' `&Gold)`0</a> '.($bool_exists ? '`i`$schon vorhanden!`i' : '').'`0`n';
					
					addnav("",$item_hook_info['link'].'&op=putin2&obj_id='.$items2['id']);
				}
				
				addnav('Zurück',$item_hook_info['link']);
			}
			
			// Was zum reinlegen ausgewählt
			else if ($item_hook_info['op'] == 'putin2')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner='.$session['user']['acctid'],false);
				$arr_content[]=$items['name'];
				item_set('id='.$item['id'],array('content' => db_real_escape_string(utf8_serialize($arr_content))));
				item_delete('id='.$items['id']);
				
				$str_output .= '`KDu suchst einen hübschen Platz für `&'.$items['name'].' `Kaus und fixierst es dort.`n`n';
				
				addnav('Mehr dazutun',$item_hook_info['link'].'&op=putin');
				addnav("Zurück",$item_hook_info['link']);
			}
			
			else if ($item_hook_info['op'] == 'del')
			{
				$str_output .= '`KWillst du `&'.$arr_content[$_GET['obj_id']].' `Kwirklich aus dem Schaukasten entfernen? Es wird dabei unwiederbringlich zerstört werden!';
				addnav('Ja, weg damit',$item_hook_info['link'].'&op=del2&obj_id='.$_GET['obj_id']);
				addnav('Nein, bloß nicht',$item_hook_info['link']);
					
			}
			else if ($item_hook_info['op'] == 'del2')
			{
				$str_output .= '`KMit einem lauten Knirschen löst du `&'.$arr_content[$_GET['obj_id']].'`K von seiner Stelle und wirfst es weg. Das ist nun nicht mehr zu gebrauchen.';
				unset($arr_content[$_GET['obj_id']]);
				item_set('id='.$item['id'],array('content' => db_real_escape_string(utf8_serialize($arr_content))));
				addnav("Zurück",$item_hook_info['link']);
			}
			
			else //Beim Start
			{
				$sql = "SELECT name FROM accounts WHERE acctid=".$item['owner'];
				$result = db_query($sql);
				$row = db_fetch_assoc($result); 
				
				
				$str_output .= '`KIn einem großen, gläsernen Schaukasten hat '.$row['name'].' `Keinige besondere Erinnerungsstücke an die Kämpfe im Wald ausgestellt.`n`nBisher kannst du hier bewundern:`n`n';
				
				if (($item['owner'] == $session['user']['acctid']) || ($item['value1'] == 1))
				{
					addnav('Waffen');  // Nur Besitzer darf Waffen dazutun und Rechte vergeben
				addnav('Dazutun',$item_hook_info['link'].'&op=putin');
				
				}
				if ($item['owner'] == $session['user']['acctid'])
				{
					addnav('Verwaltung');
					addnav('Rechte einstellen',$item_hook_info['link'].'&op=change');
				}
				
				
				$int_weapons = count($arr_content); // Anzahl waffen
				$int_per_page = 50; // Wie viele pro seite?
				$int_pages = ceil($int_weapons/$int_per_page); // Wie viele seiten?
				asort($arr_content); // alphabetisch, bitte
				if($_GET['page']) // welche seite isses?
				{
					$page = $_GET['page'];
				}
				else
				{
					$page = 1;
				}
				
				addnav('Seiten');
				for($i=1; $i<=$int_pages; $i++)
				{
					addnav(($i == $page ? '`^' : '').'Seite '.$i.'`0',$item_hook_info['link'].'&page='.$i);
				}
				
				if($int_weapons == 0)
				{
					$str_output .= '`&`iLeider noch garnichts...`i';
				}
				else
				{
					$c = 1;
					foreach($arr_content as $key => $val)
					{ 
						if(($c > $int_per_page * ($page-1)) && ($c <= $int_per_page * $page))
						{
							if ($item['owner'] == $session['user']['acctid'])  // Nur Besitzer darf Gegenstände löschen
							{
								$str_output .= '`0<a href="'.utf8_htmlentities($item_hook_info['link'].'&op=del&obj_id='.$key).'">`&'.$val.'`0</a>`n'; 
						addnav('',$item_hook_info['link'].'&op=del&obj_id='.$key);
							}
							else
							{
								$str_output .= '`&'.$val.'`n'; 
							}
						}
					$c++;
					}
					
					$str_output .= '`n`K`iInsgesamt gibt es hier `b'.count($arr_content).'`b Andenken.`i';
				}
				
				// Und wieder rauskommen
				addnav('Sonstiges');
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			}
			
			$str_output .= '`n`n`n';
			output($str_output);
			
			break;

	}
}
?>