<?php
/**
* motdrss.php: 
* @author saris
* @version DS-E V/3.x
*/

require_once('common.php');

$rss = new CRSS(true,true);

$str_sql = 'SELECT motditem as id, motdtitle as title, motdbody as description, UNIX_TIMESTAMP(motddate) as pubdate, motdtype 
			FROM motd 
			WHERE motdgroup = 0 
			ORDER BY motddate DESC 
			LIMIT '.(int)(getsetting('rss_item_count',10));
$db_result = db_query($str_sql);

while($arr_row = db_fetch_assoc($db_result)){
	$rss->addNode(array_merge($arr_row,array('category'=>'MOTD','link'=>getsetting('rss_link','http://atrahor.de'))));
}

$rss->output();
?>