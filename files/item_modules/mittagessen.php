<?php

function trashitself($item_id=0) {
	global $item;
	item_set('id='.($item_id>0?$item_id:$item['id']),array('owner' => 0, 'deposit1' => 0, 'deposit2' => 0));
}

function mittagessen_hook_process ( $item_hook , &$item ) {

	global $session, $item_hook_info;

	switch ( $item_hook ) {

		//	Aktionen bei Nutzung
		case 'furniture':
			if($item_hook_info['op'] == 'eat') //essen
			{
				if($session['user']['hitpoints']>=$session['user']['maxhitpoints']) //LP sind voll
				{
					output('`&So lecker das Essen auch aussieht, du bist völlig satt.');
				}
				else
				{
					$randmax=min($item['gold'],$session['user']['maxhitpoints']-$session['user']['hitpoints']);
					$randmin=ceil($randmax/3);
					$golddiff=e_rand($randmin,$randmax);
					$healing=ceil($golddiff/2); //2 Goldwert gibt 1LP
					output('`&Du stürzt dich auf den Teller mit '.$item['description'].'. Hmm ist das lecker.`n`@Du regenerierst um '.$healing.' Punkte.');
					insertcommentary($session['user']['acctid'],': `6stürzt sich auf '.$item['description'].'`6.','house-'.$item['deposit1']);
					$session['user']['hitpoints']+=$healing;
					if($item['gold']-$golddiff <=1) //aufgegessen
					{
						insertcommentary(1,'/msg `7'.$item['description'].'`7 ist aufgegessen.','house-'.$item['deposit1']);
						trashitself();
					}
					else
					{
						item_set('id='.$item['id'],array('gold' => $item['gold']-$golddiff));
					}
				}
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			}
			elseif($item_hook_info['op'] == 'delete')
			{
				output('`&Du nimmst die Creation und kippst sie unter ständigem Rühren auf den Kompost.');
				insertcommentary($session['user']['acctid'],': `4findet gebratenes '.$item['description'].'`4 ekelhaft und wirft es weg.','house-'.$item['deposit1']);
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
				trashitself($item['id']);
			}
			else
			{
				output('`&Vor dir steht ein Teller `^'.$item['description'].'`^ mit Bratkartoffeln und Zwiebeln`&, welchen '.$item['special_info'].'`& zubereitet hat.`n
				Diese Creation enthält '.$item['gold'].' Kalorien.`n`n
				Was willst du damit tun?');
				addnav('Essen',$item_hook_info['link'].'&op=eat');
				addnav('Wegwerfen',$item_hook_info['link'].'&op=delete');
				addnav('Sonstiges');
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			}
			break;

		case 'alchemy':

			$houseid = get_restorepage_history(); //rausfinden in welchem Haus gekocht wird
			if(empty($houseid)) {
				output('`n`4Du bist beim Kochen eingeschlafen und mußt von Neuem anfangen.`0');
			}
			else {
				$houseid=intval(str_replace('inside_houses.php?id=','',$houseid));
				if($houseid==0){
					output('`n`4Ein Geier klaut dir dein Essen. Vielleicht solltest du deine Kochversuche in ein Haus verlegen.`0');
				}
			}
			if (item_count('tpl_id="kochkunst" AND deposit1='.$houseid)) //check ob bereits eins vorhanden
			{
				output('`n`4Doch leider musst du feststellen daß ein anderer Koch schneller war.`0 Frustriert gibst du deine Creation den Schweinen.`0`n');
				unset($item_hook_info['product']);
			}
			else //sonst Infos der 1. Zutat übernehmen
			{
				$item_hook_info['product']['tpl_name']='Mittagessen';
				if($item_hook_info['items_in'][0]['tpl_id']=='trph') { //Soylent Grün ist Menschenfleisch
					$item_hook_info['product']['tpl_description']='`2Soylent Grün`0';
				}
				else {
					$item_hook_info['product']['tpl_description']=$item_hook_info['items_in'][0]['name'];
				}
				$item_hook_info['product']['tpl_gold']=$item_hook_info['items_in'][0]['gold'];
				if($item_hook_info['items_in'][0]['gems']>0) {
					$item_hook_info['product']['tpl_gold']+=1500;
				}
				$item_hook_info['product']['deposit1']=$houseid;
				$item_hook_info['product']['tpl_special_info']=$session['user']['name'];
				insertcommentary($session['user']['acctid'],': `@hat Essen gekocht. Es gibt '.$item_hook_info['product']['tpl_description'].'`@ mit Bratkartoffeln.','house-'.$houseid);
			}
			break;

		case 'newday': //owner und deposit auf 0 setzen
		trashitself($item['id']); //keine Ahnung warum hier $item nicht automatisch an die Funktion übergeben wird
		break;

	}


}

?>