<?php
/**
 * house_extensions.php: Interface für Hauserweiterungen
 * @author talion <t@ssilo.de>
 * @version DS-E V/3
*/

require_once('common.php');
require_once(LIB_PATH.'house.lib.php');

$int_hid = (int)$session['housekey'];
$int_id = (int)$_GET['_ext_id'];

if(!$int_id) {
	redirect('houses.php?op=enter');
}

$arr_extension = db_fetch_assoc(db_query('SELECT * FROM house_extensions WHERE id='.$int_id));

if(!isset($arr_extension['id'])) {
	page_header();
	addnav('Zurück (ExtID fehlerhaft)','inside_houses.php');
	page_footer();
	exit;
}

if(!$int_hid) {
	$session['housekey'] = $int_hid = $arr_extension['houseid'];
}

$arr_extension_type = $g_arr_house_extensions[$arr_extension['type']];
$str_path = HOUSES_EXT_PATH.$arr_extension_type['inc'];

if(!isset($arr_extension_type['inc']) || !is_file($str_path)) {
	page_header();
	addnav('Zurück (ExtType fehlerhaft)','inside_houses.php');
	page_footer();
	exit;
}

page_header($arr_extension_type['name']);

$str_base_lnk = 'house_extensions.php?_ext_id='.$int_id;

house_extension_run('in',$arr_extension);

page_footer();
?>
