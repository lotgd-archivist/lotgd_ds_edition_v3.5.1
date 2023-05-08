<?php

/**
Lagert Möbel aus um houses.php zu entlasten
by Maris

modded by talion: new itemsys
**/
require_once("common.php");
require_once(LIB_PATH."house.lib.php");
page_header();

if(!$session['housekey'])
{
	redirect('houses.php?op=enter');
}

$id = (int)$_GET['item_id'];
$item = item_get('id='.$id);

if(!$item['id'])
{
	addnav('Zurück!','inside_houses.php');
	page_footer();
	exit;
}

$item_hook_info['private'] = $item['deposit2'];
$item_hook_info['hid'] = $item['deposit1'];
$item_hook_info['back_msg'] = ($item['deposit2'] ? 'Zurück zum Gemach' : 'Zurück zum Haus');
$item_hook_info['back_link'] = ($item['deposit2'] ? 'house_extensions.php?_ext_id='.$item['deposit2'] : 'inside_houses.php?id='.$item['deposit1']);
$item_hook_info['link'] = 'furniture.php?item_id='.$id.'&hid='.$item['deposit1'].'&private='.$item['deposit2'];

$item_hook_info['section'] = ($item['deposit2'] ? 'h_room'.$item['deposit1'].'-'.$item['deposit2'] : 'house-'.$item['deposit1']);
$item_hook_info['op'] = $_GET['op'];

$hook = 'furniture'.($item['deposit2'] ? '_private' : '');

if($item[ $hook.'_hook' ] != '') {

	item_load_hook($item[ $hook.'_hook' ],'furniture',$item);

	// das Haus mit üüübel Schaden belasten (1%-Chance, darf (für bloßen Klick) nicht zu hoch sein)!
	house_add_dmg($session['housekey'],1,1);

}
else {
	addnav('Zurück','inside_houses.php');
}

page_footer();
?>