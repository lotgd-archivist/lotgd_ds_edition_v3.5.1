<?php
/**
* motcrss.php: 
* @author saris
* @version DS-E V/3.x
*/

require_once('common.php');

$rss = new CRSS(true,false);
$rss->addChannel(array('rss_title'=>getsetting('rss_motc_title','LOTGD Webfeed'),'rss_description'=>getsetting('rss_motc_description',''),'rss_link'=>getsetting('rss_link','http://atrahor.de'),'lastBuildDate'=>gmdate('D, d M Y H:i:s').' GMT','generator'=>'LOTGD Dragonslayer Edition','rss_image'=>getsetting('rss_image','LOTGD Webfeed')));

$str_sql = 'SELECT id as id, headline as title, body as description, UNIX_TIMESTAMP(time) as pubdate, 0 AS motdtype 
			FROM motd_coding 
			WHERE public = 1 
			ORDER BY time DESC 
			LIMIT '.(int)(getsetting('rss_item_count',10));
$db_result = db_query($str_sql);

while($arr_row = db_fetch_assoc($db_result)){
	$rss->addNode(array_merge($arr_row,array('category'=>'MOTC','link'=>getsetting('rss_link','http://atrahor.de'))));
}

$rss->output();
?>