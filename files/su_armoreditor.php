<?php
require_once "common.php";

$access_control->su_check(access_control::SU_RIGHT_EDITOREQUIPMENT,true);

page_header("Rüstungseditor");
$armorlevel = (int)$_GET['level'];
grotto_nav();
addnav("Rüstungseditor","su_armoreditor.php?level=$armorlevel");
addnav(false);

addnav("h?Rüstung hinzufügen","su_armoreditor.php?op=add&level=$armorlevel");
$values = array(1=>48,225,585,990,1575,2250,2790,3420,4230,5040,5850,6840,8010,9000,10350);
output('<h3>`&Rüstungen für ' . $armorlevel . ' Heldentat' . ($armorlevel>1?'en':'') . '`0</h3>');

$armorarray=array("Rüstungen,title",
"armorid"=>"Rüstungs ID,hidden",
"armorname"=>"Rüstungsname",
"defense"=>"Verteidigung,enum,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12,13,13,14,14,15,15",
"Rüstungen,title");
$arr_desc=utf8_unserialize(getsetting('armorclasses','a:0:{}'));

if ($_GET['op']=="edit" || $_GET['op']=="add")
{
	if ($_GET['op']=="edit")
	{
		$sql = "SELECT * FROM armor WHERE armorid='$_GET[id]'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
	}
	else
	{
		$sql = "SELECT max(defense+1) AS defense FROM armor WHERE level=$armorlevel";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
	}
	output("<form action='su_armoreditor.php?op=save&level=$armorlevel' method='POST'>",true);
	addnav("","su_armoreditor.php?op=save&level=$armorlevel");
	showform($armorarray,$row);
	output("</form>",true);
}

else if ($_GET['op']=="del")
{
	$sql = "DELETE FROM armor WHERE armorid='$_GET[id]'";
	db_query($sql);
	redirect("su_armoreditor.php?level=$armorlevel");
}

else if ($_GET['op']=="save")
{
	if ((int)$_POST['armorid']>0)
	{
		$sql = "UPDATE armor SET armorname=\"$_POST[armorname]\",defense=\"$_POST[defense]\",value=".$values[$_POST['defense']]." WHERE armorid='$_POST[armorid]'";
	}
	else
	{
		$sql = "INSERT INTO armor (level,defense,armorname,value) VALUES ($armorlevel,\"$_POST[defense]\",\"$_POST[armorname]\",".$values[$_POST['defense']].")";
	}
	db_query($sql);
	redirect("su_armoreditor.php?level=$armorlevel");
}

else if ($_GET['op']=='setcat')
{
	$arr_desc[$armorlevel]=addstripslashes($_POST['description']);
	savesetting('armorclasses',utf8_serialize($arr_desc));
	redirect('su_armoreditor.php?level='.$armorlevel);
}

else if ($_GET['op']=='')
{
	$sql = "SELECT max(level+1) AS level FROM armor";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$max = $row['level'];
	for ($i=0; $i<=$max; $i++)
	{
		addnav("Rüstungen für $i DKs","su_armoreditor.php?level=$i");
	}
	$str_output='`n<form action="su_armoreditor.php?op=setcat&level='.$armorlevel.'" method="POST">
	Name der Kategorie:
	<input type="text" name="description" value="'.stripslashes($arr_desc[$armorlevel]).'">
	<input type="submit" class="button" value="Setzen">
	</form>`n';
	addnav('','su_armoreditor.php?op=setcat&level='.$armorlevel);
	$str_output.='<table>';
	$sql = "SELECT * FROM armor WHERE level=".(int)$_GET['level']." ORDER BY defense";
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
		$str_output.="<td>[<a href='su_armoreditor.php?op=edit&id=$row[armorid]&level=$armorlevel'>Edit</a>|<a href='su_armoreditor.php?op=del&id=$row[armorid]&level=$armorlevel' onClick='return confirm(\"Diese Rüstung wirklich löschen?\");'>Löschen</a>]</td>";
		addnav("","su_armoreditor.php?op=edit&id=$row[armorid]&level=$armorlevel");
		addnav("","su_armoreditor.php?op=del&id=$row[armorid]&level=$armorlevel");
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

