<?php
// Gerümpel-Truhe, Eingelagerte Items liegen in einem virtuellen Privatgemach 65536 über dem Original

function chest_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook ) {
		
		case 'furniture':
			
			$owner=$item['owner'];
			
			if($_GET['act'] == '') {
			
				if ($owner==$session['user']['acctid'])
				{			
					output('`&Du begibst dich zu deiner großen Truhe und öffnest sie mit deinem Schlüssel.`nEs befinden sich `^'.$item['hvalue'].'`& Dinge darin.`nEin Zettel erinnert dich daran dass die Truhe leer sein muß bevor du sie an einen anderen Ort stellst.`n`nWas nun ?`n`n');
					addnav('v?Dinge verstauen',$item_hook_info['link'].'&act=insert');
					addnav('m?Dinge mitnehmen',$item_hook_info['link'].'&act=take');
				}
				else
				{
					$left=$item['hvalue'];
					if ($left<=0) {
						output("`&In dieser Truhe wohnt nur der Hausgeist.`n`n");
					} else {
						$str_out='`&Du öffnest die Truhe und siehst eine Ansammlung von Dingen:`n`n';
						$result=item_list_get('owner='.$item['owner'].' AND deposit1='.$item['deposit1'].' AND deposit2='.($item['deposit2']+65536),'',true,'i.name,i.id,i.deposit1,i.description');
						$str_out.='<table border=0><tr class="trhead"><th>Inhalt</th><th width=80>Aktion</th></tr>';
                        $bgclass='trdark';
						while ($row=db_fetch_assoc($result))
						{
							$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
							$str_out.='<tr class='.$bgclass.'><td>'.$row['name'].'</td><td>&nbsp;</td></tr>';
							$str_out.='<tr class='.$bgclass.'><td colspan=2>'.$row['description'].'</td></tr>';
						}
						output ($str_out);
					}
			
				}
			}
						
			elseif ($_GET['act']=="insert"){
						
				$capacity=50;
				$space=$capacity-$item['hvalue'];
				$str_out='`&Deine Truhe fasst maximal `^'.$capacity.'`& Dinge. Es befinden sich bereits `@'.$item['hvalue'].'`& Dinge darin. Folglich kannst du noch `#'.$space.'`& Dinge hinein tun.`n`n';
				if($space>0)
				{
					$str_out.='Was möchtest du hineinlegen?`n`n<table border=0><tr class="trhead"><th>Du hast im Beutel</th><th width=80>Aktion</th></tr>';
					$result=item_list_get('owner='.$session['user']['acctid'].' AND deposit1=0 AND (it.tpl_class=4 OR it.tpl_class=7)','',true,'i.name,i.id,i.deposit1,i.description');
					while ($row=db_fetch_assoc($result))
					{
						$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
						$str_out.='<tr class='.$bgclass.'><td>'.$row['name'].'</td><td><a href="'.$item_hook_info['link'].'&act=insert2&id='.$row['id'].'">hineinlegen</a></td></tr>';
						$str_out.='<tr class='.$bgclass.'><td colspan=2>'.$row['description'].'</td></tr>';
						addnav('',$item_hook_info['link'].'&act=insert2&id='.$row['id']);
					}
				}
				else $str_out.='Sieht so aus als ob du erstmal Platz schaffen mußt.';
				output ($str_out);
			}
			
			elseif ($_GET['act']=="insert2"){
				$row=item_get('id='.$_GET['id'],false,'name');
				output('`&Du legst `^'.$row['name'].'`& in die Truhe.');
				$item['hvalue'] ++;
				item_set(' id='.$item['id'], $item);
				$row['deposit1'] = $item['deposit1'];
				$row['deposit2'] = $item['deposit2']+65536;
				//$row['gold'] = 1;
				//$row['gems'] = 0;
				item_set(' id='.$_GET['id'], $row);
				addnav('v?Mehr verstauen',$item_hook_info['link'].'&act=insert');
			}
			
			elseif ($_GET['act']=="take"){
			
				$left=$item['hvalue'];
				if ($left<=0) {
					output("`&Deine Truhe ist genauso leer wie dein Kopf!`n`n");
				} else {
					$str_out='`&Was willst du herausnehmen?`n`n';
					$result=item_list_get('owner='.$session['user']['acctid'].' AND deposit1='.$item['deposit1'].' AND deposit2='.($item['deposit2']+65536),'',true,'i.name,i.id,i.deposit1,i.description');
					if(db_num_rows($result)<$left) $str_out.='`nEs liegen noch Dinge an einem Ort wo die Truhe früher stand!`n`n';
					$str_out.='<table border=0><tr class="trhead"><th>Inhalt</th><th width=80>Aktion</th></tr>';
					while ($row=db_fetch_assoc($result))
					{
						$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
						$str_out.='<tr class='.$bgclass.'><td>'.$row['name'].'</td><td><a href="'.$item_hook_info['link'].'&act=take2&id='.$row['id'].'">mitnehmen</a></td></tr>';
						$str_out.='<tr class='.$bgclass.'><td colspan=2>'.$row['description'].'</td></tr>';
						addnav('',$item_hook_info['link'].'&act=take2&id='.$row['id']);
					}
					output ($str_out);
				}
	
			}
			
			elseif ($_GET['act']=="take2"){
				$row=item_get('id='.$_GET['id'],false,'name');
				output('`&Du nimmst `^'.$row['name'].'`& aus der Truhe.');
				$item['hvalue'] --;
				item_set(' id='.$item['id'], $item);
				$row['deposit1'] = 0;
				$row['deposit2'] = 0;
				item_set(' id='.$_GET['id'], $row);
				addnav('m?Mehr mitnehmen',$item_hook_info['link'].'&act=take');
			}
						
			if($_GET['act']) addnav('T?Zur Truhe',$item_hook_info['link']);
			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			
			break;
			
	}
		
	
}

?>