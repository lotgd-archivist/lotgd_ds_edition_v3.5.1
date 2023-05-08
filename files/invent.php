<?php

require_once("common.php");

page_header('Inventar');

// Bei Eintritt in Beutel: Letzte besuchte Seite speichern
if($_GET['r']) 
{
	set_restorepage_history($g_ret_page);
	redirect('invent.php');
}

$str_ret = get_restorepage_history();
$accesskeys['z']=1; //Zurück-Hotkey Z reservieren

output('`&`c`bGesammelter Besitz von '.$session['user']['name'].'`&:`c`b`n`n');

if($session['user']['weapon'] != 'Fäuste' ||  $session['user']['armor'] != 'Straßenkleidung' ||  $session['user']['kleidung'] != '') 
{

	addnav('Ausrüstung');

	if($session['user']['weapon'] != 'Fäuste')
	{
		addnav( $session['user']['weapon'].'`0 ablegen!' , 'invhandler.php?op=abl&what=weapon&ret='.urlencode(calcreturnpath()) );
	}
	if($session['user']['armor'] != 'Straßenkleidung')
	{
		addnav( $session['user']['armor'].'`0 ablegen!' , 'invhandler.php?op=abl&what=armor&ret='.urlencode(calcreturnpath()) );
	}
	if($session['user']['kleidung'] != '')
	{
		addnav( $session['user']['kleidung'].'`0 ablegen!' , 'invhandler.php?op=abl&what=kleidung&ret='.urlencode(calcreturnpath()) );
	}

}

item_invent_set_env(ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_MULTI | ITEM_INVENT_HEAD_SEARCH | ITEM_INVENT_HEAD_EXPIRES);
item_invent_show_data(item_invent_head('showinvent=1 AND owner='.$session['user']['acctid'],20),'Du besitzt nichts dergleichen, genauer betrachtet: gar nichts.');

addnav('Sonstiges');
$accesskeys['z']=0; //Zurück-Hotkey Z freigeben

if(!empty($str_ret)) 
{
	addnav('Z?Zurück',$str_ret);
}
else 
{
	addnav('Zu den News','news.php');
}

page_footer();
?>