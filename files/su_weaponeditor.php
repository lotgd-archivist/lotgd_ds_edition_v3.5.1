<?php
require_once "common.php";

$access_control->su_check(access_control::SU_RIGHT_EDITOREQUIPMENT,true);

page_header("Waffeneditor");
$weaponlevel = (int)$_GET['level'];
grotto_nav();
addnav("Waffeneditor","su_weaponeditor.php?level=$weaponlevel");
addnav(false);

addnav("h?Waffe hinzufügen","su_weaponeditor.php?op=add&level=$weaponlevel");
$values = array(1=>48,225,585,990,1575,2250,2790,3420,4230,5040,5850,6840,8010,9000,10350);
output('<h3>`&Waffen für ' . $weaponlevel . ' Heldentat' . ($weaponlevel>1?'en':'') . '`0</h3>');

$weaponarray=array("Waffen,title",
"weaponid"=>"Waffen ID,hidden",
"weaponname"=>"Waffenname",
"damage"=>"Schaden,enum,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12,13,13,14,14,15,15",
"Waffen,title");
$arr_desc=utf8_unserialize(getsetting('weaponclasses','a:0:{}'));

if ($_GET['op']=="edit" || $_GET['op']=="add")
{
	if ($_GET['op']=="edit")
	{
		$sql = "SELECT * FROM weapons WHERE weaponid='$_GET[id]'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
	}
	else
	{
		$sql = "SELECT max(damage+1) AS damage FROM weapons WHERE level=$weaponlevel";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
	}
	output("<form action='su_weaponeditor.php?op=save&level=$weaponlevel' method='POST'>",true);
	addnav("","su_weaponeditor.php?op=save&level=$weaponlevel");
	showform($weaponarray,$row);
	output("</form>",true);
}

else if ($_GET['op']=="del")
{
	$sql = "DELETE FROM weapons WHERE weaponid='$_GET[id]'";
	db_query($sql);
	redirect("su_weaponeditor.php?level=$weaponlevel");
}

else if ($_GET['op']=="save")
{
	if ((int)$_POST['weaponid']>0)
	{
		$sql = "UPDATE weapons SET weaponname=\"$_POST[weaponname]\",damage=\"$_POST[damage]\",value=".$values[$_POST['damage']]." WHERE weaponid='$_POST[weaponid]'";
	}
	else
	{
		$sql = "INSERT INTO weapons (level,damage,weaponname,value) VALUES ($weaponlevel,\"$_POST[damage]\",\"$_POST[weaponname]\",".$values[$_POST['damage']].")";
	}
	db_query($sql);
	redirect("su_weaponeditor.php?level=$weaponlevel");
}

else if ($_GET['op']=='setcat')
{
	$arr_desc[$weaponlevel]=addstripslashes($_POST['description']);
	savesetting('weaponclasses',utf8_serialize($arr_desc));
	redirect('su_weaponeditor.php?level='.$weaponlevel);
}

else if ($_GET['op']=='')
{
	$sql = "SELECT max(level+1) AS level FROM weapons";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$max = $row['level'];
	for ($i=0; $i<=$max; $i++)
	{
		addnav("Waffen für $i DKs","su_weaponeditor.php?level=$i");
	}
	$str_output='`n<form action="su_weaponeditor.php?op=setcat&level='.$weaponlevel.'" method="POST">
	Name der Kategorie:
	<input type="text" name="description" value="'.stripslashes($arr_desc[$weaponlevel]).'">
	<input type="submit" class="button" value="Setzen">
	</form>`n';
	addnav('','su_weaponeditor.php?op=setcat&level='.$weaponlevel);
	$str_output.='<table>';
	$sql = "SELECT * FROM weapons WHERE level=".(int)$_GET['level']." ORDER BY damage";
	$result= db_query($sql);
	for ($i=0; $i<db_num_rows($result); $i++)
	{
		$row = db_fetch_assoc($result);
		if ($i==0)
		{
			$str_output.='<tr class="trhead">';
			$str_output.='<th>Ops</th>';
			while (list($key,$val)=each($row))
			{
				$str_output.='<th>'.$key.'</th>';
			}
			$str_output.="</tr>\n";
			reset($row);
		}
		$str_output.="<tr>";
		$str_output.="<td>[<a href='su_weaponeditor.php?op=edit&id=$row[weaponid]&level=$weaponlevel'>Edit</a>|<a href='su_weaponeditor.php?op=del&id=$row[weaponid]&level=$weaponlevel' onClick='return confirm(\"Diese Waffe wirklich löschen?\");'>Löschen</a>]</td>";
		addnav("","su_weaponeditor.php?op=edit&id=$row[weaponid]&level=$weaponlevel");
		addnav("","su_weaponeditor.php?op=del&id=$row[weaponid]&level=$weaponlevel");
		while (list($key,$val)=each($row))
		{
			$str_output.="<td>$val</td>";
		}
		$str_output.="</tr>\n";
	}
	output($str_output.'</table>');
}
page_footer();
?>

