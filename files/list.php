<?php

require_once 'common.php';

//Welche Spalten sollen per default angezeigt werden?
$arr_default_columns = array(
	'level'	=> true,
	'race'	=> true,
	'place' => true,		
	'rpg'	=> true,
	'status'=> false,
	'guild'	=> true
);

$int_ref=intval($_GET['r']);
$str_ref=($int_ref>0?'?r='.$int_ref:'');
$str_ref2=($int_ref>0?'&r='.$int_ref:'');

if ($Char->loggedin)
{
	checkday();
	if ($Char->alive)
	{
		addnav('Zurück');
		//Für freche Mods die gern mal über die Grotte+userliste wieder auf den Dorfplatz wollen ;-)
		if($Char->imprisoned > 0)
		{
			addnav('K?In den Kerker','prison.php');
		}
		else 
		{
			addnav('d?Zum Stadtzentrum','village.php');
			addnav('M?Zum Marktplatz','market.php');
		}
	}
	else
	{
		addnav('Zurück zu den Schatten', 'shades.php');
	}
	if($access_control->su_check(access_control::SU_RIGHT_GROTTO))
	{
		addnav('G?Zur Grotte','superuser.php');
	}
}
else
{
	addnav('Login Seite','index.php'.$str_ref);
}
addnav('Anzeigen');
addnav('Gerade Online','list.php'.$str_ref);
addnav('Alle Spieler','list.php?p=all'.$str_ref2);
addnav('t?Adminteam','list.php?p=su'.$str_ref2);
page_header('Kämpferliste');

// Order the list by level, dragonkills, name so that the ordering is total!
// Without this, some users would show up on multiple pages and some users
// wouldn't show up
if ($_GET['p']=='' && $_GET['op'] == '')
{
	output(get_title('Folgende Bürger '.getsetting('townname','Atrahor').'s sind gerade online:'));
	user_show_list(100,user_get_online(),'level DESC, dragonkills DESC, login ASC',true,$arr_default_columns,200);
}
elseif ($_GET['p'] == 'su')
{
	output(get_title('Das Administrationsteam '.getsetting('townname','Atrahor').'s:'));

	$arr_show = array();
	//Superuser bekommen alle angezeigt
	if($Char instanceof CCharacter && ($Char->isSuperuser()))
	{
		$arr_usergroups = $access_control->user_get_sugroups();
		foreach($arr_usergroups as $id=>$val)
		{
			$arr_show[] = $id;
		}		
		user_show_list(100,' superuser IN('.implode(',',$arr_show).') AND superuser != 0',' superuser DESC ',true,$arr_default_columns);
	}
	else 
	{
		$arr_usergroups = $access_control->get_superuser_sugroups(true);		
		
		foreach($arr_usergroups as $id=>$val)
		{
			if($val[3] == 1 || $id == 7)
			{
				$arr_show[] = $id;
			}
		}
		user_show_list(100,' superuser IN('.implode(',',$arr_show).') ',' superuser DESC ',true,$arr_default_columns);
	}
	
}
else
{
	output('`c`bDie ehrenhaften Bürger '.getsetting('townname','Atrahor').'s:`b`c');
	user_show_list(40,'','level DESC, dragonkills DESC, login ASC',true,$arr_default_columns);
}

page_footer();
?>