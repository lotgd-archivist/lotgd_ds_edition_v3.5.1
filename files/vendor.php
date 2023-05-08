<?php

// 24072004

// The vendor Aeki sells furniture for houses and buys items found at beaten monsters in the forest.
//
// Vendor only appears on a few (game) days in village
// This is controlled by weather mod by Talisman
//
// by anpera (2004) while listening to music by 'The Sweet' ;)

// modded and rewritten completely by talion while listening to music by 'The Lemonheads' ; )
// to fit into the new drachenserver-itemsystem

require_once "common.php";
$player = user_get_aei('job');
$p_job = $player['job'];

$show_invent = true;
$buy_done = true;

require_once(LIB_PATH.'dg_funcs.lib.php');
if($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT) {
	$rebate = dg_calc_boni($session['user']['guildid'],'rebates_vendor',0);
}

page_header("Wanderhändler");

if($_GET['op'] == 'buy_do') {
	

	$tpl_arr = array();
	$isanew = true;

	if(!empty($_POST['ids']) && is_array($_POST['ids']) && is_numeric($_POST['ids'][0])) {
		$tpl_arr = $_POST['ids'];
		$isanew = false;
	}
	else if(!empty($_POST['ids']) && is_array($_POST['ids']) && !is_numeric($_POST['ids'][0])) {
		$tpl_arr = $_POST['ids'];
		$isanew = true;
	}
	else if(isset($_GET['tpl_id'])) {
		$tpl_arr[0] = $_GET['tpl_id'];
		$isanew = true;
	}
	else {
		$tpl_arr[0] = (int)$_GET['id'];
		$isanew = false;
	}	
	
	foreach($tpl_arr as $k => $v)
	{
		
		if($isanew) {
			$_GET['tpl_id'] = $v;
			unset($_GET['id']);
		}
		else {
			$_GET['id'] = $v;
			unset($_GET['tpl_id']);
		}
	
		$show_invent = false;
	
		$item_hook_info['dealer'] = 'vendor';
		$item_hook_info['do'] = 'buy';
	
		$item_hook_info['codeloc'] = 'start';
		$item_hook_info['msg'] = '';
			
			if($_GET['tpl_id']) {
				$item_hook_info['tpl'] = true;
				$item = item_get_tpl(' tpl_id="'.$_GET['tpl_id'].'" ');
				item_load_hook($item['trade_hook'],'trade',$item);
		
				$name = $item['tpl_name'];
		
				$item_hook_info['goldprice'] = round($item['tpl_gold'] * $_GET['gold_r']);
				$item_hook_info['gemsprice'] = round($item['tpl_gems'] * $_GET['gems_r']);
		
				$item['tpl_gold'] = round($item_hook_info['goldprice'] * 0.5);
		
				// 10%iger Händlerbones (Preis modifizieren)
				if ($p_job==6)
					{
						$item_hook_info['goldprice'] *= 0.9;
					}
		
				$item['tpl_gems'] = round($item_hook_info['gemsprice'] * 0.5);
		
				if($session['user']['gold'] < $item_hook_info['goldprice'] || $session['user']['gems'] < $item_hook_info['gemsprice'])
				{
					$buy_done = false;
				}
				else
				{
					if (item_add($session['user']['acctid'],0,$item)>0){
						$buy_done = true;
					} else {
						$buy_done = false;
					}
				}
			
			}
			else {
				$item_hook_info['tpl'] = false;
				$item = item_get(' id="'.$_GET['id'].'" AND owner='.ITEM_OWNER_VENDOR, true);
				if(is_array($item))
				{
					item_load_hook($item['trade_hook'],'trade',$item);
		
					$name = $item['name'];
		
					$item_hook_info['goldprice'] = round($item['gold'] * $_GET['gold_r']);
					$item_hook_info['gemsprice'] = round($item['gems'] * $_GET['gems_r']);
		
					$item['gold'] = round($item_hook_info['goldprice'] * 0.5);
		
					// 10%iger Händlerbones (Preis modifizieren)
					if ($p_job==6)
					{
						$item_hook_info['goldprice'] *= 0.9;
					}
		
					$item['gems'] = round($item_hook_info['gemsprice'] * 0.5);
					
					if($session['user']['gold'] < $item_hook_info['goldprice'] || $session['user']['gems'] < $item_hook_info['gemsprice'])
					{
						$buy_done = false;
					}
					else
					{
						item_set('id='.(int)$_GET['id'],array('deposit1'=>0,'deposit2'=>0,'owner'=>$session['user']['acctid'],'gold'=>$item['gold'],'gems'=>$item['gems']) );
					}
				}
				else
				{
					output('`$Da war jemand schneller!`n');
					$name='`$NICHTS`q';
				}
		
			
			}
			
		if ($buy_done){
			$item_hook_info['msg'] = '`qDer Händler reibt sich die Hände und übergibt dir '.$name.', während du '.($item_hook_info['goldprice']?'`^'.$item_hook_info['goldprice'].' `qGold':'').' '.($item_hook_info['gemsprice']?'`#'.$item_hook_info['gemsprice'].'`q Edelsteine':'').' abzählst. `n';
	
			$item_hook_info['codeloc'] = 'end';
	
			item_load_hook($item['trade_hook'],'trade',$item);
	
			output($item_hook_info['msg']);
	
			$session['user']['gold'] -= $item_hook_info['goldprice'];
			$session['user']['gems'] -= $item_hook_info['gemsprice'];
		} else {
			output('`$"Ohh, das tut mir aber Leid, ich kann dir das nicht verkaufen!", meint Aeki im letzten Moment.`n');
		}

	}
	
	if($isanew) {
				addnav('Mehr kaufen','vendor.php?op=buy&act=new');
				addnav('H?Zum Händler','vendor.php');
		}
		else {
				addnav("Mehr kaufen","vendor.php?op=buy&act=old");
				addnav('H?Zum Händler','vendor.php');
		}
}
else if($_GET['op'] == 'sell_do') {

	$show_invent = false;
	$arr_items = array();

	// Multiselect
	if(!empty($_POST['ids']) && is_array($_POST['ids'])) {
		$str_ids = implode(',',$_POST['ids']);
		//fiy bathi
		$res_items = item_list_get(' id IN ('.db_intval_in_string(stripslashes($str_ids)).') AND owner='.$session['user']['acctid'].' AND (it.vendor = 2 OR it.vendor = 3) ','',true,'name,id,it.tpl_id,gold,gems,vendor');

		if(db_num_rows($res_items) == 0) {
			redirect('vendor.php?op=sell');
		}

		$arr_items = db_create_list($res_items);

	}
	else {
		if(empty($_GET['id']) || ($arr_tmp = item_get(' id="'.(int)$_GET['id'].'" ')) === false) {
			redirect('vendor.php?op=sell');
		}

		$arr_items = array($arr_tmp);
	}

	$goldprice_ges = 0;
	$gemsprice_ges = 0;

	foreach ($arr_items as $item) {

		$item_hook_info['goldprice'] = round($item['gold'] * $_GET['gold_r']);

		// 10%iger Händlerbones (Preis modifizieren)
		if ($p_job==6)
		{
			$item_hook_info['goldprice'] *= 1.1;
		}

		$item_hook_info['gemsprice'] = round($item['gems'] * $_GET['gems_r']);

		$item_hook_info['dealer'] = 'vendor';
		$item_hook_info['do'] = 'sell';
		item_load_hook($item['trade_hook'],'trade',$item);

		$goldprice_ges += $item_hook_info['goldprice'];
		$gemsprice_ges += $item_hook_info['gemsprice'];

		//@DS Falls sich einer beschwert dass er irgendwas nicht bekommen hätte beim Verkauf
		debuglog('verkaufte '.$item['name'].'`0 für '.$item_hook_info['goldprice'].' Gold und '.$item_hook_info['gemsprice'].' Edelsteine');

		// Wenn Gebraucht-Ankauf bei Wanderhändler möglich
		if($item['vendor'] == 1 || $item['vendor'] == 3) {
			// Der Wanderhändler kann auch nicht unendlich viel aufnehmen, irgendwann muss er aussortieren!
			// Doppelt Vorhandenes kommt weg
			item_delete(' tpl_id="'.$item['tpl_id'].'" AND owner='.ITEM_OWNER_VENDOR);

			item_set(' id='.$item['id'],array('deposit1'=>0,'deposit2'=>0,'gold'=>$item_hook_info['goldprice'],'gems'=>$item_hook_info['gemsprice'],'owner'=>ITEM_OWNER_VENDOR) );
		}
		else {	// Neuware
			item_delete(' id='.$item['id']);
		}
	}

	$session['user']['gold'] += $goldprice_ges;
	$session['user']['gems'] += $gemsprice_ges;

	output('`qMit einem breiten und siegessicheren Grinsen gibt er dir '.(($goldprice_ges || $gemsprice_ges)?'die vereinbarten '.($goldprice_ges?'`^'.$goldprice_ges.' `qGold':'').' '.($gemsprice_ges?'`#'.$gemsprice_ges.'`q Edelsteine':'') : 'einen feuchten Händedruck').' und schnappt sich die Ware.');

	addnav('Mehr verkaufen','vendor.php?op=sell&loc=beutel');
	addnav('H?Zum Händler','vendor.php');

}

else if ($_GET['op']=='buy'){ // Wig-Wam Bam

	output('`}S`It`tolz präsentiert dir der Händler `IAeki`t seinen Wagen. Zu jedem der seltsamen Gegenstände, Artefakte und Zauber scheint er eine kleine Geschichte zu kennen. Dabei scheint er auffällig oft darauf hinzuweisen, dass viele Leute, von denen er etwas gekauft hat, den wahren Wert dieser Dinge nicht zu kennen schei`tn`Ie`}n.
	`n'.($rebate?'`}Z`Iu`tfrieden teilt er dir mit, dass du aufgrund deiner Gildenmitgliedschaft `^'.$rebate.' %`t Rabatt erhält`}s`It!':'').' ');

	addnav('Neuwaren','vendor.php?op=buy&act=new');
	addnav('Gebrauchtwaren','vendor.php?op=buy&act=old');

	$rebate = (100 - $rebate) * 0.01;

	if($_GET['act'] == 'old') {

		output('`n`n`}S`Ie`tine Gebrauchtwar`Ie`}n:`n`n');

		item_invent_set_env(ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_SHOP_BUY | ITEM_INVENT_HEAD_SEARCH| ITEM_INVENT_HEAD_NOT_STACKABLE | ITEM_INVENT_HEAD_EXPIRES | ITEM_INVENT_HEAD_MULTI,$rebate,$rebate);

		// Nur Waren anzeigen, die nicht als Neuware erhältlich sind
		item_invent_show_data(item_invent_head(' owner='.ITEM_OWNER_VENDOR.'
				AND (
				( (vendor=1 OR vendor=3) AND (vendor_new=0) )
				 OR ( i.tpl_id="trph" AND hvalue!='.$session['user']['acctid'].' )
				 ) ',20), 'Leider hat Aeki rein gar nichts anzubieten..');

	}
	else {

		item_invent_set_env(ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_SHOP_BUY | ITEM_INVENT_HEAD_SEARCH | ITEM_INVENT_HEAD_NOT_STACKABLE | ITEM_INVENT_HEAD_EXPIRES | ITEM_INVENT_HEAD_MULTI,$rebate,$rebate,true);

		output('`n`n`}S`Ie`tine Neuwar`Ie`}n:`n`n');
		item_invent_show_data(item_invent_head(' vendor_new=1 ',20), 'Leider hat Aeki rein gar nichts anzubieten..');

	}
	addnav('Zurück');
	addnav('Zum Händler','vendor.php');

}

else if ($_GET['op']=='sell'){ // Ballroom Blitz

	output('`}D`Ie`tr Händler begutachtet deinen Besitz. Mit dem geübten Auge eines Kenners sortiert er die Dinge aus, die ihn interessieren würden und nennt dir einen Preis daf`Iü`}r.`n`n');

	item_invent_set_env(ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_MULTI | ITEM_INVENT_HEAD_SHOP_SELL | ITEM_INVENT_HEAD_SEARCH | ITEM_INVENT_HEAD_EXPIRES);

	item_invent_show_data(item_invent_head(' owner='.$session['user']['acctid'].' AND (vendor=2 OR vendor=3) AND deposit1 != '.ITEM_LOC_EQUIPPED,20), 'Überraschenderweise besitzt du jedoch nichts, das Aeki interessieren würde!');

	if($session['user']['exchangequest']==9)
	{
		addnav('Mithril-Erz verkaufen','exchangequest.php',false,false,false,false);
	}
	addnav('Zurück');
	addnav('Zum Händler','vendor.php');

}
else if($_GET['op'] == 'rel') {
	$show_invent = false;

	$arr_item = item_get_tpl(' tpl_id="drrel_gld" ');

	$session['user']['gold'] -= $arr_item['tpl_value1'];
	debuglog('gab '.$arr_item['tpl_value1'].' Gold für Drachenreliquie');

	$arr_item['tpl_value1'] = time();

	item_add($session['user']['acctid'],0,$arr_item);
	item_delete(' (tpl_id="drstb") AND owner='.$session['user']['acctid']);

	output('`n`n`}A`Ie`tki überreicht dir vorsichtig ein schweres, fast gänzlich schwarz gefärbtes Horn.
				Bewundernd und gleichzeitig unschlüssig streichst du darüber.
				Schließlich verschwindet die kostbare Reliquie in deinem Rucksa`Ic`}k.');

	addnews(
		'`^Soeben wurde ' . $session['user']['name'].'`^ dabei beobachtet,
		wie ' . ($session['user']['sex'] ? 'sie' : 'er') .' Aekis Stand mit einer Drachenreliquie verließ!'
	);
	$sql = "
		UPDATE
			`account_extra_info`
		SET
			`treasure_f`	= `treasure_f` + 1
		WHERE
			`acctid`		= '" . $session['user']['acctid'] . "'
	";
	db_query($sql);

	addnav('Zurück');
	addnav("Zum Händler","vendor.php");

}
else{ // Teenage Rampage
	checkday();
	if (!getsetting("vendor",0)) {

		if(!access_control::is_superuser()) {
			redirect("market.php");
		}
		else {
			output('`c`bWanderhändler - heute nur für DICH als Gott ; )`b`c`n`n');
		}

	}

	output('`}H`Ie`tute ist der Wanderhändler `IAeki `twieder in der Stadt! Direkt vor `!Thorim`ts Waffenladen hat er seinen Wagen aufgebaut, was Thorim sichtlich missfällt. Da er aber selbst hin und wieder Handel mit ihm betreibt, läßt er ihn gewähren.
	`nNeugierig näherst du dich dem Wagen, um zu sehen, ob der Händler diesmal etwas Interessantes für dich dabei hat. Vielleicht hast du aber auch etwas, das du ihm verkaufen kan`tn`Is`}t?`n');

	if( item_count(' (tpl_id="drstb") AND owner='.$session['user']['acctid']) >= 1 ) {

		$sql = 'SELECT a.name FROM items LEFT JOIN accounts a ON owner=acctid WHERE tpl_id="drrel_gld"';
		$res = db_query($sql);
		$int_count = db_num_rows($res);

		if(0 == $int_count) {	// Noch keiner hat die Reliquie

			// value1 enthält Preis
			$arr_item = item_get_tpl(' tpl_id="drrel_gld" ','tpl_name,tpl_description,tpl_value1');

			output(' `}B`Ie`tsonders sticht dir ein handbeschriebenes, ziemlich krakeliges Schild neben seinem Stand ins Auge: `Q"Verkaufe Drachenreliquie, Modell \''.$arr_item['tpl_name'].'`t\'. Nur heute, nur hier, nur mit mir! `b'.$arr_item['tpl_value1'].'`b Go`Il`}d."`n');
			if($session['user']['gold'] >= $arr_item['tpl_value1']) {
				addnav($arr_item['tpl_name'],'vendor.php?op=rel');
			}

		}
		// END noch keiner hat Rel
		else {

			$arr_owner = db_fetch_assoc($res);

			output('`n`n`tUnter beredsamen Beschwichtigungen teilt dir Aeki mit, dass sich '.$arr_owner['name'].'`0 noch
					vor dir die Drachenreliquie unter den Nagel gerissen hat.`0');
		}
	}


	addnav('Waren durchstöbern','vendor.php?op=buy');
	addnav('Etwas verkaufen','vendor.php?op=sell&loc=beutel');
	addnav('Zurück');

}

addnav("Zum Marktplatz","market.php");

page_footer();
// reading source code can seriously damage your eyes! Well, at least it can take out the fun of a game...
?>
