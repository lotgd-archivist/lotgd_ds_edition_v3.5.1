<?php
/**
 * racesspecial.php: Räume, die nur Spielern bestimmter Rassen zugänglich sind
 *		 				Gedenkstein hinzugefügt von Fossla (maikesonja@gmx.de)
 * @author maris <maraxxus@gmx.de>, modded by talion: Neues Rassensystem
 * @version DS-E V/2
*/

require_once "common.php";
checkday();
addcommentary();
function showcasulties($victim)
{
	$arr_casualties = array();
	$arr_casualties = utf8_unserialize((getsetting('race_casualties','')));
	
	if(!sizeof($arr_casualties) || !sizeof($arr_casualties[$victim]))
	{
		output('Der Tod ist zynisch, humorvoll und notorisch unpünktlich.');
		return;
	}

	// Rassenliste
	$res = db_query('SELECT colname_plur,id FROM races WHERE active=1');
	$arr_races = db_create_list($res,'id');
	
	$arr_race_cas = $arr_casualties[$victim];
	arsort($arr_race_cas);
	
	$int_total = 0;
	            $str_out = '';
	foreach ($arr_race_cas as $str_id => $int_number)
	{
		$int_total += $int_number;
		
		$str_out .= '`$'.$int_number.'`y wurden durch '.($arr_races[$str_id]?$arr_races[$str_id]['colname_plur']:'rassenlose').'`& ';
		
		switch(e_rand(1,3))
		{
			case 1:$str_out .= 'niedergestreckt';break;
			case 2:$str_out .= 'getötet';break;
			case 3:$str_out .= 'gemeuchelt';break;
		}
		
		$str_out .= '!`n';
	}
	$str_out .= '`n`yWir trauern um alle `$'.$int_total.'`y unserer getöteten Schwestern und Brüder!';
	
	output($str_out);
	return;
}

$str_raceid = $_GET['race'];

if(empty($str_raceid))
{
	redirect('village.php');
}

$arr_race = race_get($str_raceid,true);

if($_GET['op'] == 'show_list')
{
	page_header('Die Rassenliste');
	
	output ('`c`b`yEine Liste am Rande dieses Ortes zeigt Dir auf magische Weise alle '.$arr_race['name_plur'].' in '.getsetting('townname','Atrahor').':`0`b`c`n');
	
	user_show_list(50,' race="'.$arr_race['id'].'"','dragonkills DESC, name ASC');
	
	addnav('Zurück','racesspecial.php?race='.$str_raceid);
}

elseif($_GET['op'] == 'pvp_deads')
{
	page_header('Der Gedenkstein');
	
	output ('`c`b`(Auf einem dunklen Stein steht geschrieben:`0`b`c`n');
	
	showcasulties($str_raceid);
	
	addnav('Zurück','racesspecial.php?race=' . $str_raceid);
}

else
{
	page_header(strip_appoencode($arr_race['raceroom_name'],3));
	
	output('`c`b`&'.$arr_race['raceroom_name'].'`0`b`c`n'.$arr_race['raceroom_desc'].'`0`n`n',true);
	
	addcommentary(false);
	
	$str_section = 'raceroom_'.$arr_race['id'];
	
	viewcommentary($str_section,'Sagen:',25);
	
	addnav('R?Zur Rassenliste','racesspecial.php?op=show_list&race='.$str_raceid);
	addnav('G?Zum Gedenkstein','racesspecial.php?op=pvp_deads&race='.$str_raceid);
	if($session['user']['exchangequest']==26)
	{
		addnav('S?`%Zum Stammesältesten`0','exchangequest.php?race='.$str_raceid);
	}
	
	addnav('Zurück');
	if($arr_race['raceroom'] == 1)
	{
		addnav('W?Zum Wald','forest.php');
	}
	else
	{
		addnav('W?Zum Wohnviertel','houses.php');
	}
}

page_footer();
?>
