<?php

function schaukasten_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook ) {
		
		case 'furniture':
			
			//Achtung! Die Suchbedingungen gelten nur für Atrahor und 1:1 übernomene Kopien der Item-Daten
			$arr_category=array(
				'0'=>array('search'=>'i.tpl_id="trph"',
					'catname'=>'Trophäen',
					'footline'=>'`n`&Die {totalcount} Trophäen haben einen Gesamtwert von `^{value1count}`& Heldentaten!`n',
					'listorder'=>' ORDER BY hvalue,name,id ASC ',
					'nodescription'=>1
					)
				,'1'=>array('search'=>'i.tpl_id="unikat"',
					'catname'=>'Unikate',
					'footline'=>'`n`&Insgesamt `^{totalcount}`& Unikate!`n`n',
					'listorder'=>' ORDER BY id DESC '
					)
				,'2'=>array('search'=>'it.tpl_class="'.getsetting('runes_classid',0).'"',
					'catname'=>'Runen',
					'footline'=>'`n`&Insgesamt `^{totalcount}`& Runen!`n',
					'listorder'=>' ORDER BY value2,id '
					)
				,'3'=>array('search'=>'it.tpl_class="4" AND i.tpl_id NOT IN ("fttrsack") ',
					'catname'=>'Geschenke',
					'footline'=>'`n`&Insgesamt `^{totalcount}`& Geschenke!.`n',
					'listorder'=>' ORDER BY id DESC '
					)
				,'10'=>array('search'=>'i.tpl_id="waffedummy"',
					'catname'=>'Waffen',
					'footline'=>'`n`&Insgesamt `^{totalcount}`& Waffen!`n',
					'listorder'=>' ORDER BY value1 DESC,name,id ASC '
					)
				,'11'=>array('search'=>'i.tpl_id="rstdummy"',
					'catname'=>'Rüstungen',
					'footline'=>'`n`&Insgesamt `^{totalcount}`& Rüstungen!`n',
					'listorder'=>' ORDER BY value1 DESC,name,id ASC '
					)
				,'12'=>array('search'=>'it.tpl_class="30"',
					'catname'=>'Gewänder',
					'footline'=>'`n`&Insgesamt `^{totalcount}`& Gewänder!`n',
					'listorder'=>' ORDER BY hvalue DESC, tpl_id ASC '
					)
				,'99'=>array('search'=>'it.tpl_class="3"',
					'catname'=>'Beute',
					'footline'=>'`n`&Insgesamt `^{totalcount}`& Fundstücke!`n',
					'listorder'=>' ORDER BY name '
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
				
				$str_out='`&In einer herrlich großen Vitrine im rustikalen Landhaus-Stil hat '.$rowo['name'].'`& ein paar ganz besondere Dinge zum Betrachten ausgestellt.`0';
				$str_out.="`n`n<table border='0'><tr class='trhead'><th colspan=2>Von ".strip_appoencode($rowo['name'])." gesammelte ".$arr_category[$item['hvalue']]['catname'].":</th></tr>";
			
				
				$result = item_list_get( 'owner='.$item['owner'].' AND hide=0 AND '.$arr_category[$item['hvalue']]['search'] , 'ORDER BY sort_order DESC' );
				
				$amount=db_num_rows($result);
				
				$totalcount=$value1count=0;
				if (!$amount) 
				{
					$str_out.='<tr class="trdark"><td colspan="2">`iLeider gibt es hier außer einem abgekauten Apfel nichts zu sehen.`i</td></tr>';
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
				}
				
				if($item['owner']==$session['user']['acctid'])
				{
					addnav('Sortieren',$item_hook_info['link'].'&op=sortit');
				}
			}
			
			elseif($_GET['op']=='sortit')
			{ 
				$str_out.='Hier hast du die Möglichkeit, deine eingelagerten Unikate neu anzuordnen.`n';
				$str_out.=item_set_sort_order('owner='.$item['owner'].' AND '.$arr_category[$item['hvalue']]['search'], false, 'sort_order', true);
				addnav('A?Zur Ansicht',$item_hook_info['link']);
			}
			
			elseif($_GET['op']=='selectcategory')
			{ //Ansichtskategorie auswählen
				$str_out.='Was soll in dieser Vitrine liegen?
				`n`n<ul>';
				foreach($arr_category as $key => $value)
				{
					$str_out.='<li>'.create_lnk($value['catname'],$item_hook_info['link'].'&op=setcategory&cat='.$key);
				}
				$str_out.='</ul>`nAchtung! Das Ändern der Kategorie ändert auch den Namen des Schaukastens, wobei eine eigene Färbung entfällt.';
				
				addnav('A?Zur Ansicht',$item_hook_info['link']);
			}

			elseif($_GET['op']=='setcategory')
			{ //Ansichtskategorie einstellen
				$itemcat=intval($_GET['cat']);
				$itemname='Schaukasten für '.$arr_category[$itemcat]['catname'];
				item_set('id='.$item['id'],array('hvalue'=>$itemcat, 'name'=>$itemname),false,1);
				redirect($item_hook_info['link']);
			}

			output($str_out);
			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);

			break;
			
	}
		
	
}

?>