<?php

function kpuppe_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook )
	{
		case 'furniture':
		{
			if($_GET['act']=="puppegeben")
			{
				$capacity=round($item['hvalue']/25)+1;
				$left=$capacity-$item['value2'];
				
				output("Gespeicherte Kraft in der Puppe: ".$item['value2']."
				`nKapazität der Puppe: ".$capacity."
				`n`nWie viele Runden möchtest du in der Puppe speichern?
				`n`n<form action='".$item_hook_info['link']."&act=puppegeben2' method='POST'>
				<input name='trai' id='trai'>
				<input type='submit' class='button' value='Runden opfern'>
				</form>".focus_form_element('trai'));
				addnav("",$item_hook_info['link']."&act=puppegeben2");
			}

			else if($_GET['act']=="puppegeben2")
			{
				$saved= $item['value2'];
				$capacity=round($item['hvalue']/25)+1;
				$left=$capacity-$item['value2'];
				
				$save = abs((int)$_POST['trai']);
				$save=min($save,$left,$session['user']['turns']);
				
				if ($save<=0 || ($session['user']['acctid']!=$item['owner'] && ac_check($item['owner'])))
				{
					output("`&So sehr du dich bemühst, du bekommst keine Kraft in die Puppe hinein!`n");
				}
				else
				{
					output("`&Du speicherst `^$save`& Runden in der Kadaverpuppe.");
					$session['user']['turns']-=$save;
					$saved+=$save;
					
					item_set( ' id="'.$item['id'].'"' , array('value2'=>$saved) );
				}
			}

			else if ($_GET['act']=="puppenehmen")
			{
				
				$sql = "SELECT dollturns FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
				$result = db_query($sql);
				$dturns = db_fetch_assoc($result);
				$turnsleft=$dturns['dollturns'];
				if ($turnsleft<=0)
				{
					output("`&Du warst heute schon oft genug an der Kadaverpuppe und ekelst dich einfach nur noch.`nVersuchs morgen nochmal!");
				}
				else
				{
					if ($session['user']['turns']<2 && $item['owner'] != $session['user']['acctid']) {
						output("`&Heute hast du keine Kraft mehr, um dich noch mit der Puppe zu befassen.
						`nDu solltest mindestens noch 2 Runden übrig haben, bevor du dich dem stinkenden Kameraden hier widmest!`n");
					}
					else
					{
						$available=$item['value2'];
						$capacity=round($item['hvalue']/25)+1;
						if ($available==0)
						{
							output("`&In der Puppe ist zur Zeit keine Kraft gespeichert!");
						}
						else
						{
							output("`&Du begibst dich zur Kadaverpuppe, um die in ihr gespeicherte Kraft in dich aufzunehmen.`n");
							$chance=e_rand(1,5);
							// Nachteil nicht für den Besitzer
							if ($chance==2 && $session['user']['acctid']!=$item['owner'])
							{
								// Kann mal daneben gehen
								output("`&Doch anstatt dir Kraft zu geben entzieht dir die verfluchte Puppe diese sogar!
								`nDu reißt dich los und ziehst dich verschreckt zurück.
								`n`nDu `4verlierst 2 Waldkämpfe!`&");
								$session['user']['turns']-=2;
								$available=min($capacity,$available+2);
							}
							else
					 		// postivies Ergebnis
							{
								output("`&Du fühlst neue Kräfte in dich strömen und `@erhältst einen Waldkampf zurück!`&`n`n");
								$session['user']['turns']++;
								$available--;
							}
							
							item_set(' id='.$item['id'] , array('value2'=>$available) );
							
							$turnsleft--;
							$sql = "UPDATE account_extra_info SET dollturns=$turnsleft WHERE acctid=".$session['user']['acctid']."";
							db_query($sql);
				  		} //end available>0
					} //end turns>2
				} //end turnsleft
			} //end act=puppenehmen

			else //if($_GET['act'] == '')
			{
				$capacity=round($item['hvalue']/25)+1;
				$left=$capacity-$item['value2'];
				output("`&Diese Puppe kann, wenngleich sie auch übel riecht, ein treuer Freund in schweren Zeiten sein, wenn man sich nur gut genug um sie kümmert.
				`nEtwas ganz Besonderes liegt auf diesem aus zusammengenähtem Fleisch bestehenden Ding. Du weißt nicht was es ist, aber es scheint dir gleichsam Kraft zu nehmen wie zu geben.
				`nVielleicht solltest du dich einmal etwas näher mit der Puppe befassen.
				`n`nGespeicherte Kraft in der Puppe: ".$item['value2']."
				`nKapazität der Puppe: ".$capacity."`n");
				addnav("g?Kraft geben",$item_hook_info['link']."&act=puppegeben");
				addnav("n?Kraft nehmen",$item_hook_info['link']."&act=puppenehmen");
			}

			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			
			break;
		} // end case furniture
	}//end switch
	
}

?>