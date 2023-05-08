<?php
require_once 'common.php';
$access_control->su_check(access_control::SU_RIGHT_EDITORWORLD,true);

page_header('Spott Editor');
grotto_nav();
if ($_GET['op']=='edit')
{
	addnav('Spotteditor','su_taunt.php');
	$str_output .= '<form action="su_taunt.php?op=save&tauntid='.$_GET['tauntid'].'" method="POST">';
	addnav('','su_taunt.php?op=save&tauntid='.$_GET['tauntid']);
	if ($_GET['tauntid']!='')
	{
		$sql = 'SELECT * FROM taunts WHERE tauntid='.$_GET['tauntid'];
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$taunt = $row['taunt'];
		$taunt = str_replace('%s','[ihn|sie]',$taunt);
		$taunt = str_replace('%o','[er|sie]',$taunt);
		$taunt = str_replace('%p','[sein|ihr]',$taunt);
		$taunt = str_replace('%x','Zahnstocher`0',$taunt);
		$taunt = str_replace('%X','Scharfe Zähne`0',$taunt);
		$taunt = str_replace('%W','Große grüne Ratte`0',$taunt);
		$taunt = str_replace('%w','[Joe|Jane]Bloe`0',$taunt);
		$str_output .= 'Vorschau: '.get_taunt($taunt).'`0`n`n';
	}
	output($str_output);
	rawoutput('Taunt: <input name="taunt" value="'.utf8_htmlentities($row['taunt']).'" size="70"><br>');
	$str_output = '`nDie folgenden Codes werden unterstützt (Groß- und Kleinschriebung wird unterschieden):`n';
	$str_output .= '%w = Name des Verlierers`n
		%x = Waffe des Verlierers`n
		%s = Geschlecht des Verlierers (ihn/sie)`n
		%p = Geschlecht des Verlierers (sein/ihr)`n
		%o = Geschlecht des Verlierers (er/sie)`n
		%W = Name des Gewinners`n
		%X = Waffe des Gewinners`n
		[männl|weibl] = einzelne Worte geschlechtsspezifisch ersetzen ([Krieger|Kriegerin])`n';
	$str_output .= '<input type="submit" class="button" value="Speichern">';
	$str_output .= '</form>';
}
else if($_GET['op']=='del')
{
	$sql = 'DELETE FROM taunts WHERE tauntid='.$_GET['tauntid'];
	db_query($sql);
	redirect("su_taunt.php?c=x");
}
else if($_GET['op']=='save')
{
	if ($_GET['tauntid']!='')
	{
		$sql = 'UPDATE taunts SET taunt="'.$_POST['taunt'].'" WHERE tauntid='.$_GET['tauntid'];
	}
	else
	{
		$sql = 'INSERT INTO taunts (taunt,editor) VALUES ("'.$_POST['taunt'].'","'.db_real_escape_string($session['user']['login']).'")';
	}
	db_query($sql);
	redirect("su_taunt.php?c=x");
}
else
{
	$sql = 'SELECT * FROM taunts ORDER BY taunt ASC';
	$result = db_query($sql);
	$str_output .= '<table>';
	$int_count = db_num_rows($result);
	for ($i=0;$i<$int_count;$i++)
	{
		$row=db_fetch_assoc($result);
		$str_output .= '<tr>';
		$str_output .= '<td>';
		$str_output .= '[<a href="su_taunt.php?op=edit&tauntid='.$row['tauntid'].'">Edit</a>|<a href="su_taunt.php?op=del&tauntid='.$row['tauntid'].'" onClick="return confirm(\'Diesen Eintrag wirklich löschen?\');">Löschen</a>]';
		addnav('','su_taunt.php?op=edit&tauntid='.$row['tauntid']);
		addnav('','su_taunt.php?op=del&tauntid='.$row['tauntid']);
		$str_output .= '</td>';
		$str_output .= '<td>';
		$str_output .= $row['taunt'];
		$str_output .= '</td>';
		$str_output .= '<td>';
		$str_output .= $row['editor'];
		$str_output .= '</td>';
		$str_output .= '</tr>';
	}
	addnav('','su_taunt.php?c='.$_GET['c']);
	$str_output .= '</table>';
	addnav('Spott hinzufügen','su_taunt.php?op=edit');
}
output($str_output,true);
page_footer();
?>