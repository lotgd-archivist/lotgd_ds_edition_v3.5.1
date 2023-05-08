<?php
/**
 * Diese Datei enthält Informationen über LotGD, Atrahor und das Spiel im Allgemeinen
 *
 * @author Atrahor Team
 */

require_once 'common.php';
page_header('Über '.getsetting('townname','Atrahor').' das Legend of the Green Dragon basierende Spiel in der Dragonslayer Edition');

checkday();

/** braucht einer die Cookies hier?
if(isset($_COOKIE['lasthit'])) {
	setcookie('lasthit',0,strtotime(date('r').'+365 days'));
}
*/
$int_ref=intval($_GET['r']);
$str_ref=($int_ref>0?'?r='.$int_ref:'');
$str_ref2=($int_ref>0?'&r='.$int_ref:'');

if($_GET['op']=='gpl')
{
	output(get_extended_text('GPL'));
}
else
{
	/*
	* NOTICE This section may not be modified, please modify the Server Specific section above.
	*/
	// Ätsch, wurde modifiziert, liegt jetzt sauber und sicher in der Datenbank
	output(get_extended_text('about_lotgd'),true);
}

if ($session['user']['loggedin'])
{
	addnav('Zurück zu den News','news.php');
}
else
{
	addnav('Login','index.php'.$str_ref);
}

addnav('Informationen');
addnav('About LoGD','about.php?d=1'.$str_ref2);
addnav('GNU GPL','about.php?op=gpl'.$str_ref2);

page_footer();
?>
