<?php

function item_giveback($item)
{
	$item['tpl_name']=$item['name'];
	$item['tpl_value1']=$item['value1'];
	$item['tpl_value2']=$item['value2'];
	$item['tpl_gold']=$item['gold'];
	$item['tpl_gems']=$item['gems'];
	$item['tpl_description']=$item['description'];
	$item['tpl_hvalue']=$item['hvalue'];
	$item['tpl_hvalue2']=$item['hvalue2'];
	$item['tpl_special_info']=$item['special_info'];
	$item['tpl_weight']=$item['weight'];
	item_add($item['owner'],0,$item);
}

function stock_hook_process($item_hook , &$item )
{

    global $session,$item_hook_info;

    switch ($item_hook )
    {

    case 'alchemy':
        for ($j=0; $j<=2; $j++)
        {
            if ($item_hook_info['items_in'][$j]['hvalue']!=0 || $item_hook_info['items_in'][$j]['hvalue2']==0)
            {
                $error = 1;
            }
        }
        if ($error == 1)
        {
            output("`n`4Was immer du dir da zusammenbasteln wolltest - es zerfällt sofort wieder!`0`n");
            //item_add($session['user']['acctid'],0,$item_hook_info['items_in'][0]);
            //item_add($session['user']['acctid'],0,$item_hook_info['items_in'][1]);
            //item_add($session['user']['acctid'],0,$item_hook_info['items_in'][2]);
            item_giveback($item_hook_info['items_in'][0]);
            item_giveback($item_hook_info['items_in'][1]);
            item_giveback($item_hook_info['items_in'][2]);
        }
        else
        {
            $sql = "SELECT * from items WHERE tpl_id='stockalt'";
            $result = db_query($sql);
            if (!db_num_rows($result))
            {
                output("`n`@Du beschwörst den hässlichen Stock!`n`0");
                $itemnew = item_get_tpl(' tpl_id="stockalt" ' );
                $itemnew['tpl_value2'] = $item_hook_info['items_in'][0]['hvalue2'];
                $itemnew['tpl_hvalue'] = $item_hook_info['items_in'][1]['hvalue2'];
                $itemnew['tpl_hvalue2'] = $item_hook_info['items_in'][2]['hvalue2'];
                item_add($session['user']['acctid'],0,$itemnew);
            }
            else
            {
                $row = db_fetch_assoc($result);
                if ($row['owner']==$session['user']['acctid'])
                {
                    output("`n`4Wieso willst du etwas herbeirufen was du schon längst besitzt?`n`0");
		            //item_add($session['user']['acctid'],0,$item_hook_info['items_in'][0]);
		            //item_add($session['user']['acctid'],0,$item_hook_info['items_in'][1]);
		            //item_add($session['user']['acctid'],0,$item_hook_info['items_in'][2]);
		            item_giveback($item_hook_info['items_in'][0]);
		            item_giveback($item_hook_info['items_in'][1]);
		            item_giveback($item_hook_info['items_in'][2]);
                }
                else
                {
                    for ($j=0; $j<=2; $j++)
                    {
                        if ($item_hook_info['items_in'][$j]['hvalue2']!=4)
                        {
                            $myval[$j] = $item_hook_info['items_in'][$j]['hvalue2'];
                        }
                        else
                        {
                            $myval[$j] = e_rand(1,3);
                        }
                    }
                    $yourval[0] = $row['value2'];
                    $yourval[1] = $row['hvalue'];
                    $yourval[2] = $row['hvalue2'];
                    for ($j=0; $j<=2; $j++)
                    {
                        if ($yourval[$j]==4)
                        {
                            $yourval[$j]=e_rand(1,3);
                        }
                    }
                    $points = 0;
                    for ($j=0; $j<=2; $j++)
                    {
                        if ($myval[$j]==1)
                        {
                            if ($yourval[$j]==2)
                            {
                                $points--;
                            }
                            if ($yourval[$j]==3)
                            {
                                $points++;
                            }
                        }
                        else if ($myval[$j]==2)
                        {
                            if ($yourval[$j]==3)
                            {
                                $points--;
                            }
                            if ($yourval[$j]==1)
                            {
                                $points++;
                            }
                        }
                        else if ($myval[$j]==3)
                        {
                            if ($yourval[$j]==1)
                            {
                                $points--;
                            }
                            if ($yourval[$j]==2)
                            {
                                $points++;
                            }
                        }
                    }
                    if ($points>0)
                    {
                        $sql = "SELECT name, loggedin FROM accounts WHERE acctid=".$row['owner'];
                        $res2 = db_query($sql);
                        $row2 = db_fetch_assoc($res2);
                        if (!$row2['loggedin'])
                        {
                            output("`n`@Du beschwörst den hässlichen Stock aus dem Besitz von ".$row2['name']."`@!`n`0");
                            $title = user_get_aei('ctitle');
                            if (!$title['ctitle'])
                            {
                                $title['ctitle'] = $session['user']['title'];
                            }
                            systemmail($row['owner'],'`^Verlust eines Gegenstandes!`0','`&Durch Magie wurde dir der hässliche Stock entrissen!`nDu bist dir sicher, dass es ein'.($session['user']['sex']?'e '.$title['ctitle']:' '.$title['ctitle']).' `&gewesen sein muss!');
                            if ($row['deposit1'] == 9999999)
                            {
                                $row['deposit1']=0;
                                user_update(
									array
									(
										'weapon'=>'Fäuste',
										'attack'=>array('sql'=>true,'value'=>'attack-weapondmg'),
										'weapondmg'=>0,
										'weaponvalue'=>0
									),
									$row['owner']
								);
                            }
                            $row['value2'] = $item_hook_info['items_in'][0]['hvalue2'];
                            $row['hvalue'] = $item_hook_info['items_in'][1]['hvalue2'];
                            $row['hvalue2'] = $item_hook_info['items_in'][2]['hvalue2'];
                            $row['owner'] = $session['user']['acctid'];
                            item_set('id='.$row['id'],$row);
                        }
                        else
                        {
                            output($row2['name']."`& ist leider gerade wache und lässt sich den hässlichen Stock nicht so einfach entreissen!`n");
				            //item_add($session['user']['acctid'],0,$item_hook_info['items_in'][0]);
				            //item_add($session['user']['acctid'],0,$item_hook_info['items_in'][1]);
				            //item_add($session['user']['acctid'],0,$item_hook_info['items_in'][2]);
				            item_giveback($item_hook_info['items_in'][0]);
				            item_giveback($item_hook_info['items_in'][1]);
				            item_giveback($item_hook_info['items_in'][2]);
                        }
                    }
                    else
                    {
                        output("`4`nDeine Kräfte reichen dieses Mal nicht für die Beschwörung aus!`n`0");
						//item_add($session['user']['acctid'],0,$item_hook_info['items_in'][0]);
						//item_add($session['user']['acctid'],0,$item_hook_info['items_in'][1]);
						//item_add($session['user']['acctid'],0,$item_hook_info['items_in'][2]);
						item_giveback($item_hook_info['items_in'][0]);
						item_giveback($item_hook_info['items_in'][1]);
						item_giveback($item_hook_info['items_in'][2]);
                    }
                }
            }
        }
        break;

    }
}
?>
