<?php
/**
 * @desc Die Schreie eines gequälten schallen durch das Haus
 * @author Dragonslayer
 * @copyright Atrahor, DS V3.42
 */


/** @noinspection PhpUndefinedVariableInspection */
if(house_has_extension($session['housekey'], 'torturechamber')>0)
{
	$str_sql = 'SELECT content FROM house_extensions WHERE houseid='.$session['housekey'].' AND type="torturechamber" ORDER BY RAND()';
	$db_res = db_query($str_sql);
	$arr_result = db_fetch_assoc($db_res);
	$arr_content = utf8_unserialize($arr_result['content']);

	$arr_name = array_keys(asort($arr_content['torturecount']));
	$str_name = $arr_name[0];
	
	switch (e_rand(0,9))
	{
		case 0:

			insertcommentary($session['user']['acctid'],'/msg'.$str_name.'`t\'s Schreie schallen durch die Gänge.','house-'.$session['housekey']);
			break;
		case 1:
			insertcommentary($session['user']['acctid'],'/msg`t Ein gequältes Stöhnen wird von den Wänden reflektiert.','house-'.$session['housekey']);
			break;
		case 2:
			insertcommentary($session['user']['acctid'],'/msg`tAus dem keller ertönt das Quietschen der Streckbänke. Na, da wird gewiss wieder '.$str_name.' gefoltert, wie immer...','house-'.$session['housekey']);
			break;
	}
}
?>