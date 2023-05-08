<?php
/*
Werte für chestlock (bitweise)
1=Gems Entnahme gesperrt
2=Gold Entnahme gesperrt
*/
function rechenbrett_hook_process($item_hook , &$item )
{

	global $session,$item_hook_info;
    if(!isset($str_output))$str_output='';
	switch ($item_hook )
	{

		case 'furniture':

			if ($_GET['act'] == '')
			{

				$sql = "SELECT houseid,gold,gems,owner FROM houses WHERE houseid=".$session['housekey']." ORDER BY houseid DESC";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);

				$str_output.="Du begibst dich zum Rechenbrett und beginnst fleißig die bunten Kügelchen hin und her zu schieben, um dir einen Überblick über das Edelstein- und Goldguthaben deiner Mitbewohner zu verschaffen.`n`n";

				$sql = 'SELECT keylist.*,accounts.acctid AS aid,accounts.name AS besitzer FROM keylist LEFT JOIN accounts ON accounts.acctid=keylist.owner WHERE value1='.$row['houseid'].' AND type='.HOUSES_KEY_DEFAULT.' ORDER BY id ASC';
				$result = db_query($sql);

				$str_output.="<table border='0' cellpadding='4' cellspacing='1'>
				<tr class='trhead'>
				<th colspan=2>Name</th>
				<th>Gold</th>
				<th>Edelst.</th>";
				if ($session['user']['house']==$row['houseid'])
				{
					$str_output.="<th>Reset</th>
					<th>Zugriff auf</th>";
				}
				$str_output.="</tr>";
				$lst=1;
				$goldsum=$row['gold'];
				$gemssum=$row['gems'];

				$result = db_query($sql);

				for ($i=1; $i<=db_num_rows($result); $i++)
				{
					$item = db_fetch_assoc($result);
					if ($item['owner']<>$row['owner'])
					{

						$str_output.="<tr class='".($lst%2?"trlight":"trdark")."'>
						<td>$lst: </td>
						<td".($item['besitzer']?">$item[besitzer]":" align=right>---`4verloren")."`0</td>
						<td align=right>";
						$str_output.=($item['gold']>=0?'`@':'`$').$item['gold'].'&nbsp;`0</td>
						<td align=right>'.($item['gems']>=0?'`@':'`$').$item['gems'].'&nbsp;`0</td>
						<td>';
						$goldsum-=$item['gold'];
						$gemssum-=$item['gems'];

						if ($session['user']['house']==$row['houseid'])
						{
							$str_output.=create_lnk('Gold',$item_hook_info['link'].'&act=reset&who='.$item['owner'].'&hid='.$row['houseid'].'&what=gold').'
							| '.create_lnk('Edels',$item_hook_info['link'].'&act=reset&who='.$item['owner'].'&hid='.$row['houseid'].'&what=gems').'&nbsp;</td>
							<td>';

							if (($item['chestlock']&2)==0)
							{
								$str_output.=create_lnk('`@Gold`0',$item_hook_info['link'].'&act=lock&who='.$item['owner'].'&hid='.$row['houseid'].'&what=gold');
							}
							else
							{
								$str_output.=create_lnk('`$Gold`0',$item_hook_info['link'].'&act=unlock&who='.$item['owner'].'&hid='.$row['houseid'].'&what=gold');
							}
							$str_output.=' | ';
							if (($item['chestlock']&1)==0)
							{
								$str_output.=create_lnk('`@Edels`0',$item_hook_info['link'].'&act=lock&who='.$item['owner'].'&hid='.$row['houseid'].'&what=gems');
							}
							else
							{
								$str_output.=create_lnk('`$Edels`0',$item_hook_info['link'].'&act=unlock&who='.$item['owner'].'&hid='.$row['houseid'].'&what=gems');
							}
						}
						else
						{
							$str_output.="</tr>";
						}

						$lst+=1;
					}
				}

				$str_output.='</table>
				`nDamit bleiben `^'.$goldsum.'`0 Gold und `^'.$gemssum.'`0 Edelsteine übrig, die wohl dem Hausbesitzer gehören.
				`n`nBei der ganzen Rechnerei wurden fremde Ereignisse wie Diebstahl nicht berücksichtigt.';
				output($str_output);


			}
			else if ($_GET['act']=="reset")
			{
				if($_GET['what']=='gold') $str_set='gold=0';
				elseif($_GET['what']=='gems') $str_set='gems=0';
				else $str_set='gold=0,gems=0';
				$sql = 'UPDATE keylist SET '.$str_set.' WHERE owner = '.(int)$_GET['who'].' AND value1 = '.(int)$_GET['hid'].' AND type='.HOUSES_KEY_DEFAULT;
				db_query($sql) or die(sql_error($sql));
				redirect($item_hook_info['link']);
			}
			else if ($_GET['act']=="lock")
			{
				if($_GET['what']=='gold') $str_set='chestlock=chestlock+2';
				elseif($_GET['what']=='gems') $str_set='chestlock=chestlock+1';
				else $str_set='chestlock=3';
				$sql = 'UPDATE keylist SET '.$str_set.' WHERE owner = '.(int)$_GET['who'].' AND value1 = '.(int)$_GET['hid'].' AND type='.HOUSES_KEY_DEFAULT;
				db_query($sql) or die(sql_error($sql));
				redirect($item_hook_info['link']);
			}
			else if ($_GET['act']=="unlock")
			{
				if($_GET['what']=='gold') $str_set='chestlock=chestlock-2';
				elseif($_GET['what']=='gems') $str_set='chestlock=chestlock-1';
				else $str_set='chestlock=0';
				$sql = 'UPDATE keylist SET '.$str_set.' WHERE owner = '.(int)$_GET['who'].' AND value1 = '.(int)$_GET['hid'].' AND type='.HOUSES_KEY_DEFAULT;
				db_query($sql) or die(sql_error($sql));
				redirect($item_hook_info['link']);
			}

			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);

			break;

	}
}
?>