<?php
/**
 * Farbverwaltung nach einem Originalcode von Anima Azura (auf für Atrahor.de)
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor.de
 */
require_once "common.php";

$access_control->su_check(access_control::SU_RIGHT_EDITORCOLORS,true);

page_header("Farbverwaltung");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");

if ($_GET['op']=="del")
{
	Cache::delete(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY , 'appoencode');
	$sql = "UPDATE appoencode SET active='0' WHERE id='".$_GET['id']."'";
	db_query($sql);
	$_GET['op']="";
	
}
if ($_GET['op']=="undel")
{
	Cache::delete(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY , 'appoencode');
	$sql = "UPDATE appoencode SET active='1' WHERE id='".$_GET['id']."'";
	db_query($sql);
	$_GET['op']="";
	
}
if ($_GET['op']=="forbid")
{
	Cache::delete(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY , 'appoencode');
	$sql = "UPDATE appoencode SET allowed='0' WHERE id='".$_GET['id']."'";
	db_query($sql);
	$_GET['op']="";
	
}
if ($_GET['op']=="allow")
{
	Cache::delete(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY , 'appoencode');
	$sql = "UPDATE appoencode SET allowed='1' WHERE id='".$_GET['id']."'";
	db_query($sql);
	$_GET['op']="";
	
}
if ($_GET['op']=="kill")
{
	Cache::delete(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY , 'appoencode');
	$sql = "DELETE FROM appoencode WHERE id='".$_GET['id']."'";
	db_query($sql);
	$_GET['op']="";
	
}

if ($_GET['op']=="")
{
	if (!empty($_GET['saveorder']))
	{
		//Cache::delete(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY , 'appoencode');
		asort($_POST['order']);
		$keys = array_keys($_POST['order']);
		$i = 0;
		foreach ($keys AS $key)
		{
			$i++;
			$sql = 'UPDATE appoencode SET listorder="'.$i.'" WHERE id="'.$key.'"';
			db_query($sql);
		}
		//
	}
		
	addnav("Neue Farbe einfügen","su_colors.php?op=add");

	addnav("CSS schreiben","su_colors.php?op=writecss");
	addpregnav('/su_colors.php\?sort=(listorder|id|color|code)/');
	addnav('','su_colors.php?saveorder=1');
	$str_output .= "
	<center>
	Sortieren nach [<a href='su_colors.php?sort=listorder'>Sortierung</a> ] | [<a href='su_colors.php?sort=id'>ID</a> ] | [<a href='su_colors.php?sort=color'>Farbe</a> ] | [<a href='su_colors.php?sort=code'>Code</a> ]
	<form action='su_colors.php?saveorder=1' method='post'>
	<table>
		<tr class='trhead'>
			<th>Aktionen</th>
			<th>Farbtag</th>
			<th>HEX-Farbe</th>
			<th>Zusatztag</th>
			<th>Style</th>
			<th>Sort.</th>
		</tr>";

	if($_GET['sort']!='')
	{
		$_SESSION['sort_by'] = mixed_check_parameter($_GET['sort']);
	}
	elseif($_SESSION['sort_by']=='')
	{
		$_SESSION['sort_by'] = 'listorder';
	}
	else
	{
		//$_SESSION['sort_by'] = $_SESSION['sort_by'];
	}

	$str_sql = "SELECT * FROM appoencode ORDER BY ".$_SESSION['sort_by']." ASC";
	$db_result = db_query($str_sql);
	$db_rows=db_num_rows($db_result);

	while($row = db_fetch_assoc($db_result))
	{
		addnav("","su_colors.php?op=edit&id={$row['id']}");
		addnav("","su_colors.php?op=kill&id={$row['id']}");
		addnav("","su_colors.php?op=allow&id={$row['id']}");
		addnav("","su_colors.php?op=forbid&id={$row['id']}");
		addnav("","su_colors.php?op=del&id={$row['id']}");
		addnav("","su_colors.php?op=undel&id={$row['id']}");

		$order_options = '';
		for ($i=1; $i<=$db_rows; $i++)
		{
			$order_options .= '<option value="'.$i.'"'.($i==$row['listorder']?' selected="selected"':'').'>'.$i.'</option>';
		}

				$str_class = ($str_class == 'trdark')?'trlight':'trdark';
		$str_output .= "
		<tr class='".$str_class."'>
			<td>
				[<a href='su_colors.php?op=edit&id={$row['id']}'>Edit</a> |
				<a href='su_colors.php?op=kill&id={$row['id']}'>Del</a> | ".
				(($row['allowed'])?"<a href='su_colors.php?op=forbid&id={$row['id']}'>Verbieten</a> | ":"<a href='su_colors.php?op=allow&id={$row['id']}'>Erlauben</a> | ").
				(($row['active'])?"<a href='su_colors.php?op=del&id={$row['id']}'>Deakt.</a>]":"<a href='su_colors.php?op=undel&id={$row['id']}'>Akt.</a>]").
			"</td>";
		if (empty($row['color']))
		{
			$row['color']=null;
		}
		if (empty($row['tag']))
		{
			$row['tag']=null;
		}
		if (empty($row['style']))
		{
			$row['style']=null;
		}
		$str_output .= "
			<td align='center'>{$row['code']}</td>
			<td>".(!empty($row['color']) ? '`'.$row['code'] : '')." {$row['color']}`0</td>
			<td>{$row['tag']}</td>
			<td>{$row['style']}</td>
			<td><select name='order[".$row["id"]."]'>
				".$order_options."
				</select>
				</td>
		</tr>";
		$rows++;
	}
	$str_class = ($str_class=='trdark'?'trlight':'trdark');
	$str_output.='<tr class='.$str_class.'>
	<td colspan="6" style="text-align:right"><input type="submit" class="button" value="Sortierung speichern!" /></td>
	</tr>';
	$str_output .= "</table></form></center>";
}
elseif ($_GET['op']=="add")
{
	$str_output .= "Neue farben hinzufügen:`n";
	addnav("Zurück zur Farbverwaltung","su_colors.php");
	output($str_output);
	colorform(array());
}
elseif ($_GET['op']=="edit")
{
	addnav("Zurück zur Farbverwaltung","su_colors.php");
	$sql = "SELECT * FROM appoencode WHERE id='".$_GET['id']."'";
	$result = db_query($sql);
	if (db_num_rows($result)<=0)
	{
		$str_output .= "`iFarbe nicht gefunden.`i";
	}
	else
	{
		$str_output .= "Farbeneditor:`n";
		$row = db_fetch_assoc($result);
		output($str_output);
		colorform($row);
	}
}
elseif ($_GET['op']=="writecss")
{
	addnav("Zurück zur Farbverwaltung","su_colors.php");

	$str_out = write_appoencode_css();

	$str_file = './templates/colors.css';

	$fhandler = fopen($str_file,'w+');
	fwrite($fhandler,$str_out);
	fclose($fhandler);
	
	get_appoencode(true,true);
}
elseif ($_GET['op']=="save")
{
	$keys='';
	$vals='';
	$sql='';
	$i=0;
	if(is_array($_POST['color']))
	{
		foreach($_POST['color'] as $key => $val)
		{
			if (is_array($val))
			{
				$val = db_real_escape_string(utf8_serialize($val));
			}
			if ($_GET['id']>"")
			{
				if (empty($val))
				{
					$sql.=($i>0?",":"")."$key=null";
				}
				else
				{
					$sql.=($i>0?",":"")."$key='$val'";
				}
			}
			else
			{
				$keys.=($i>0?",":"")."$key";
				if (empty($val))
				{
					$vals.=($i>0?",":"")."null";
				}
				else
				{
					$vals.=($i>0?",":"")."'$val'";
				}
			}
			$i++;
		}
		if ($_GET['id']>"")
		{
			$sql="UPDATE appoencode SET $sql WHERE id='".$_GET['id']."'";
		}
		else
		{
			$sql="INSERT INTO appoencode ($keys) VALUES ($vals)";
		}

		db_query($sql);
		if (!db_error(LINK))
		{

			Cache::delete(Cache::CACHE_TYPE_HDD|Cache::CACHE_TYPE_MEMORY , 'appoencode');
			//Cache löschen

			$str_output .= "Farbe gespeichert!";
		}
		else
		{
			$str_output .= "Fehler beim Speichern: $sql";
		}
	}
	addnav("Zurück zur Farbverwaltung","su_colors.php");
}




function colorform($color)
{
	global $output;
	if (empty($color['color']))
	{
		$color['color']='';
	}
	if (empty($color['tag']))
	{
		$color['tag']='';
	}
	if (empty($color['style']))
	{
		$color['style']='';
	}
	$str_output = form_header("su_colors.php?op=save&id={$color['id']}");

	$str_output .="
	<table>
		<tr class='trdark'>
			<td>Farbtag:</td><td><input name='color[code]' value=\"".utf8_htmlentities($color['code'])."\"></td>
		</tr>
		<tr class='trdark'>
			<td>".(!empty($color['color']) ? appoencode('`'.$color['code']) : '')."HEX-Farbe:</td><td><input name='color[color]' value='".utf8_htmlentities($color['color'])."'></td>
		</tr>
		<tr class='trdark'>
			<td>Zusatztag:</td><td><input name='color[tag]' value=\"".utf8_htmlentities($color['tag'])."\"></td>
		</tr>
		<tr class='trdark'>
			<td>Style:</td><td><input name='color[style]' value=\"".utf8_htmlentities($color['style'])."\"></td>
		</tr>
	</table>
	<input type='submit' class='button' value='Speichern'></form>";

	$output .= $str_output;
}
output ($str_output);
page_footer();
?>