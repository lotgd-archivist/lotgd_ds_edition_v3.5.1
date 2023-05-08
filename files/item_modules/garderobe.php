<?php

function garderobe_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook ) {
		
		case 'furniture':
			
			//Achtung! Die Suchbedingungen gelten nur für Atrahor und 1:1 übernomene Kopien der Item-Daten
			$arr_category=array(
				'1'=>array('search'=>'i.tpl_id="rstdummy"',
					'catname'=>'Rüstungen',
					'footline'=>'`n`&Insgesamt `^{totalcount}`& Rüstungen!`n',
					'listorder'=>' ORDER BY sort_order DESC, value1 DESC,name,id ASC '
					)
				,'0'=>array('search'=>'it.tpl_class="30"',
					'catname'=>'Gewänder',
					'footline'=>'`n`&Insgesamt `^{totalcount}`& Gewänder!`n',
					'listorder'=>' ORDER BY sort_order DESC, hvalue DESC, tpl_id ASC '
					)
				,'100'=>array('search'=>'i.tpl_id="nichts"',
					'catname'=>'Nichts',
					'footline'=>'',
					'listorder'=>' LIMIT 1 '
					)
			);
				
			if($_GET['op']=='')
			{ //Start- und Ansichtsseite
				$sql = "SELECT name FROM accounts WHERE acctid=".$item['owner'];
				$result2 = db_query($sql);
				$rowo = db_fetch_assoc($result2);
				
				$str_out='`&In diesem riesig großen Kleiderschrank im rustikalen Landhaus-Stil befinden sich die sündhaft teuren Kleider von '.$rowo['name'].'`&`n';
				$str_out.="`n<table border='0'><tr class='trhead'><th colspan=2>".strip_appoencode($rowo['name'])."s ".$arr_category[$item['hvalue']]['catname'].":</th></tr>";
			
				
				$result = item_list_get( 'owner='.$item['owner'].' AND '.$arr_category[$item['hvalue']]['search'] , $arr_category[$item['hvalue']]['listorder'] );
				
				$amount=db_num_rows($result);
				
				$totalcount=$value1count=0;
				if (!$amount) 
				{
					$str_out.='<tr class="trdark"><td colspan="2">`iDu kannst allerdings nur ein paar löchrige Socken erblicken.`i</td></tr>';
				}
				
				for ($i=1;$i<=$amount;$i++){
				
					$listitem = db_fetch_assoc($result);
				
					$bgclass=($bgclass=='trdark'?'trlight':'trdark');
					$str_out.='<tr class="'.$bgclass.'">
						<td valign="top">`&'.$listitem['name'].'`0<img src="./images/trans.gif" width=10 height=1 alt=""></td>
						<td valign="top">'.($arr_category[$item['hvalue']]['nodescription']?'':'`&'.$listitem['description'].'`0').'</td>
					</tr>';
					$value1count+=$listitem['value1'];
				}
				$str_out.='</table>';
				$footline=str_replace('{totalcount}',$amount,$arr_category[$item['hvalue']]['footline']);
				$footline=str_replace('{value1count}',$value1count,$footline);
				$str_out.=$footline;

				if($item['owner']==$session['user']['acctid'])
				{
					addnav('Kategorie einstellen',$item_hook_info['link'].'&op=selectcategory');
					addnav('s?Kleider sortieren',$item_hook_info['link'].'&op=sortitems');
				}
			}
			
			elseif($_GET['op']=='selectcategory')
			{ //Ansichtskategorie auswählen
				$str_out.='Was soll in diesem Kleiderschrank liegen?
				`n`n<ul>';
				foreach($arr_category as $key => $value)
				{
					$str_out.='<li>'.create_lnk($value['catname'],$item_hook_info['link'].'&op=setcategory&cat='.$key);
				}
				$str_out.='</ul>`n';
				
				addnav('A?Zur Ansicht',$item_hook_info['link']);
			}

			elseif($_GET['op']=='setcategory')
			{ //Ansichtskategorie einstellen
				$itemcat=intval($_GET['cat']);
				item_set('id='.$item['id'],array('hvalue'=>$itemcat, 'name'=>$itemname),false,1);
				redirect($item_hook_info['link']);
			}

			elseif($_GET['op']=='sortitems')
			{ //Kleider sortieren
				$str_out.=item_set_sort_order('owner='.$item['owner'].' AND '.$arr_category[$item['hvalue']]['search']);
				addnav('A?Zur Ansicht',$item_hook_info['link']);
			}

			output($str_out);
			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);

			break;
			
	}
		
	
}

?>