<?php
/**
 * @desc Modified by Dragonslayer for Atrahor.de
 * @longdesc +Inserted a possibility to turn specials on and off
             +Inserted a link to the sourcecode
             +Altered the DB and SQL Parts
             +Added categories for the specials
             +Added a possibility for testing the Specials
             -Removed/Altered some performance decreasing parts
 */


//Dieser Part wird nur durch einen asynchronen HTTP REQUEST aufgerufen
//und gibt alle User zurück die mit der übergebenen IP/ID assoziiert sind
if(isset($_REQUEST['get_more_file_info']))
{
	$DONT_OVERWRITE_NAV 	= true;
	$BOOL_JS_HTTP_REQUEST 	= true;
	require_once('common.php');
	

	
	//Führt implizit ein die() aus und gibt den Text zurück der ausgegeben wird
	jslib_http_text_output(appoencode($str_output));
}

require_once 'common.php';
page_header('Einstellungen Special');

$str_file = basename(__FILE__);

addnav('Specialeditor');
addnav('Files aktualisieren',$str_file.'?op=neu');
addnav('Eigenschaften festlegen',$str_file.'?op=edit');
addnav('t?Specials testen',$str_file.'?editor_op=test');

addnav('Kategorien');
addnav('Kategorien verwalten',$str_file.'?op=administrate_categories');


//Globale variable wird benötigt um die Kategorie Dropdownbox darzustellen
$arr_categories = array();
/**
 * Erstellt die Elemente der Dropdownliste für die Special Kategorien
 *
 * @param int $int_category_id Element welches gewählt wurde und auf "Selected" gesetzt werden muss
 * @return string Der zurückgegebene String enthält alle Option Elemente für die Kategorie Dropdown Box
 */
function str_create_category_dropdown_items($int_category_id)
{
	global $arr_categories;
	$str_output = '';
	if(count($arr_categories) != 0)
	{
		foreach ($arr_categories as $arr_category)
		{
			$str_output .= '<option value="'.$arr_category['category_id'].'" '.(($int_category_id == $arr_category['category_id'])?'selected':'').'>'.utf8_ucfirst($arr_category['category_name']).'</option>';
		}
	}
	else
	{
		$str_sql = 'SELECT category_id, category_name FROM special_category';
		$db_result = db_query($str_sql);

		while ($arr_category = db_fetch_assoc($db_result))
		{
			$arr_categories[] = $arr_category;
			$str_output .= '<option value="'.$arr_category['category_id'].'" '.(($int_category_id == $arr_category['category_id'])?'selected':'').'>'.$arr_category['category_name'].'</option>';
		}
	}

	return $str_output;
}


if ($_GET['op']=='')
{
	$str_output.=get_title('`7Specialeditor').'
	Mit diesem Tool kann festgelegt werden, welches Special, ab welcher Anzahl an Heldentaten, wie oft und mit welcher Wahrscheinlichkeit eintreten wird.';
}
elseif ($_GET['op']=='neu')
{
	if ($dir = dir('./special'))
	{
		$files_on_hdd = array();
		while (false !== ($file = $dir->read()))
		{
			if (mb_strpos($file,'.php')>0)
			{
				$files_on_hdd[] = $file;
			}
		}
		$dir->close();
		// eingetragene specials auslesen
		$sql = 'SELECT filename FROM special_events';
		$result = db_query($sql);
		$anzahl = db_num_rows($result);
		// in array speichern
		while($row = db_fetch_assoc($result))
		{
			$files_in_db[]=$row['filename'];
		}

		if (count($files_on_hdd)==0)
		{
			$str_output.='`b`@<h3>Keine Specials vorhanden</h3>`n';
		}
		else
		{
			$str_output .= '`c`b`7Special Einstellungen:`0`b`c`n`7';


			// checken
			$i=0;
			foreach($files_on_hdd as $val)
			{
				if (!in_array($val,$files_in_db))
				{
					$sql = "
						INSERT INTO
							special_events
							(filename, descr, prio, dk, anzahl)
						VALUES
							('".$val."', 'keine Beschreibung vorhanden', '0', '0', '0')";
					db_query($sql);
					$str_output .= strip_appoencode($sql).'<br />';
					$i++;
				}
			}

			$str_output.='`bEs wurden <u>'.$i.'</u> neue Specials eingetragen.`b`n';
		}
	}
	else
	{
		$str_output.='`c`b`$FEHLER!!!`b`c`&Kann den Ordner mit den Specials nicht finden. Bitte benachrichtige den Admin!! Du bist der Admin?!?... Ja... das könnte sich zum Problem entwickeln';
	}

	// gelöschte Specials aus DB löschen
	$j=0;

	if (count($files_in_db)>0)
	{
		foreach($files_in_db as $val)
		{
			$str_path = './special/'.$val;
			if (!file_exists($str_path))
			{
				$sql = "
					DELETE FROM
						special_events
					WHERE
						filename	= '".$val."'
				";
				db_query($sql);
				$str_output .= $sql.'`n';
				$j++;
			}
		}
	}

	$str_output.='`bEs wurden <u>'.$j.'</u> Specials aus der Datenbank gelöscht`b`n`0';


	if ($i+$j==0)
	{
		$str_output='<h2>Es gibt keine Veränderungen im Special-Ordner... </h2>';
	}

}
elseif($_GET['op']=='edit')
{
	$sql = "
		SELECT
			*
		FROM
			special_events
		ORDER BY
			category_id 	ASC,
			filename 		ASC
	";
	$result = db_query($sql);
	$anzahl = db_num_rows($result);
	if ($anzahl)
	{
		$str_output.='
	    `n`n
	     Special Editor`n`n
	     Priorität absteigend! Je niedrieger die Prio ist, desto öfters kommt das Special dran!`n
	     Achte darauf, dass mind. ein Special Prio 0 und DK 0 hat!`n`n
	     '.JS::encapsulate('
	     function spc_editor_mark_item(id)
	     {
	     	document.getElementById("id_"+id).checked = true;
	     }
	     ').'
	     <form action="su_specialeditor.php?op=save" method="POST">';
		addnav('','su_specialeditor.php?op=save');
		$str_output.='<table width="600" cellpadding="1" cellspacing="0">';
		$str_output.='<tr class="trhead">
	               <th>Nr.</th>
	               <th>Dateiname</th>
	               <th>Autor</th>
	               <th>Priorität</th>
	               <th>MinDk</th>
	               <th>Freigeschaltet</th>
	               <th>Kategorie</th>
	             </tr>';
		$i=0;
		$color[0]='#008000';
		$color[1]='#14EAD3';
		$color[2]='#E6E629';
		$color[3]='#F26A10';
		$color[4]='#FF0000';
		while($row = db_fetch_assoc($result))
		{
			$str_back_class = ($str_back_class == 'trlight')?'trdark':'trlight';
			$str_output.='<tr class="'.$str_back_class.'">';
			$str_output.='<td><input type="checkbox" id="id_'.$i.'" name="data['.$i.'][checked]" value="1"></td>';
			$str_output.='<td>'.$row['filename'].'</td>';
			$str_output.='<td style="color: black;"><input name="data['.$i.'][author]" value="'.$row['author'].'" id="spc_editor_mark_item_'.$i.'" >
			'.JS::event('#spc_editor_mark_item_'.$i,'focus','spc_editor_mark_item('.$i.');').'
			</td>';
			
			$str_output.="<td><select name='data[".$i."][prio]' style='background-color:".$color[$row['prio']]."; color:black;' id='spc_editor_mark_item_".$i."'>
                        <option value='0' ".($row['prio']=='0'?"selected":"")." style='background-color:".$color[0]."; color:black;'>sehr häufig</option>
                        <option value='1' ".($row['prio']=='1'?"selected":"")." style='background-color:".$color[1]."; color:black;'>häufig</option>
                        <option value='2' ".($row['prio']=='2'?"selected":"")." style='background-color:".$color[2]."; color:black;'>recht selten</option>
                        <option value='3' ".($row['prio']=='3'?"selected":"")." style='background-color:".$color[3]."; color:black;'>sehr selten</option>
                        <option value='4' ".($row['prio']=='4'?"selected":"")." style='background-color:".$color[4]."; color:black;'>deaktiviert</option>
                       </select>
                       ".JS::event('#spc_editor_mark_item_'.$i,'focus','spc_editor_mark_item('.$i.');')."
                 </td>";
			$str_output.="<td><input type='text' name='data[".$i."][dk]' value='$row[dk]' size='3' id='spc_editor_mark_item_".$i."'>
			".JS::event('#spc_editor_mark_item_'.$i,'focus','spc_editor_mark_item('.$i.');')."
                 </td>";
			$str_output.="<td>
			<select name='data[".$i."][released]' style='background-color:".$color[($row['released']?'0':'4')]."; color:black;' id='spc_editor_mark_item_".$i."'>
			<option value='0' ".($row['released']=='0'?"selected":"")." style='background-color:".$color[4]."; color:black;'>nein</option>
			<option value='1' ".($row['released']=='1'?"selected":"")." style='background-color:".$color[0]."; color:black;'>ja</option>
			</select>
			".JS::event('#spc_editor_mark_item_'.$i,'focus','spc_editor_mark_item('.$i.');')."
			</td>";
			
			$str_output.="<td><select name='data[".$i."][category_id]' id='spc_editor_mark_item_".$i."'>";
			$str_output.=str_create_category_dropdown_items($row['category_id']);
			$str_output.='</select>'.JS::event('#spc_editor_mark_item_'.$i,'focus','spc_editor_mark_item('.$i.');');
			$str_output.="<input type='hidden' name='data[".$i."][filename]' value='$row[filename]'>";
			$str_output.="<input type='hidden' name='data[".$i."][row_id]' value='$row[row_id]'>";
			$str_output.='</td>';
			$str_output.='</tr>';
			$str_output.="<tr class='".$str_back_class."'>
				<td colspan='7' style='border-bottom:1px solid gold;'>".plu_mi($i,0,false)." Beschreibungen<br />
				<div id='".plu_mi_unique_id($i)."' style='display:none'>
				Grottenolminterne Beschreibung:<br><textarea class='input' name='data[$i][descr]' rows='3' cols='40' id='spc_editor_mark_item_".$i."' >".stripslashes($row['descr'])."</textarea>
				".JS::event('#spc_editor_mark_item_'.$i,'focus','spc_editor_mark_item('.$i.');')."
				<br />Öffentliche Beschreibung:<br><textarea class='input' name='data[$i][public_description]' rows='3' cols='40' id='spc_editor_mark_item_".$i."'>".stripslashes($row['public_description'])."</textarea>
				".JS::event('#spc_editor_mark_item_'.$i,'focus','spc_editor_mark_item('.$i.');')."
				</div></td></tr>";
			$i++;
		}

		//Categories  Array wird ab hie rnicht mehr gebraucht. Also weg damit
		unset($arr_categories);

		$str_output.='</table><br>';
		$str_output.='<input type="submit" name="s1" value="Einstellungen speichern"></form>';
	} // ende check ob was in DB steht
	else
	{  // steht nix in DB
		$str_output.='<h1>Du solltest erstmal ein paar Specials importieren!</h1>';
	}
}
elseif($_GET['op']=='save')
{
	$count = count($_POST['data']);
	for ($i=0;$i<$count;$i++)
	{
		if(!isset($_POST['data'][$i]['checked']))
		{
			continue;
		}
		$sql = "
			UPDATE
				special_events
			SET
				prio		= '".abs((int)$_POST['data'][$i]['prio'])."',
				dk			= '".abs((int)$_POST['data'][$i]['dk'])."',
				author		= '".db_real_escape_string($_POST['data'][$i]['author'])."',
				descr		= '".db_real_escape_string($_POST['data'][$i]['descr'])."',
				public_description		= '".db_real_escape_string($_POST['data'][$i]['public_description'])."',
				anzahl		= '".abs((int)$_POST['data'][$i]['anzahl'])."',
				released	= '".(int)$_POST['data'][$i]['released']."',
				category_id	= '".(int)$_POST['data'][$i]['category_id']."'
			WHERE
				row_id		= '".(int)$_POST['data'][$i]['row_id']."'
		";
		db_query($sql);
		$check = db_error($link);
		if ($check!='')
		{
			$str_output.='<br /><b>'.$check.'</b><br />';
		}
	}
	$str_output .= "<b>Einstellungen gespeichert!</b>";
}
else if($_GET['op']=='administrate_categories')
{
	$str_sql = 'SELECT * FROM special_category';
	$db_result = db_query($str_sql);
	$str_output = '`c`bListe aller vorhandenen Kategorien`b`c`n`n';
	$str_output .= '`tDie folgende Liste enthält alle vorhandenen Kategorien in die Specials eingruppiert werden können.
	Jedem Special muss eine eindeutige Kategorie zugewiesen werden, damit es auch nur an dem für das Special vorgesehenen Ort auftaucht.
	Die Standard Kategorie für Specials lautet forest. Diese kann auch nicht gelöscht werden.`n`n`0<hr/>`n';

	while ($arr_category = db_fetch_assoc($db_result))
	{
		$str_output .= '<div>'.$arr_category['category_name'].' - ';
		$str_output .= '['.create_lnk('editieren',$str_file.'?op=administrate_categories_edit&category_id='. $arr_category['category_id'],false).']';
		$str_output .= '['.create_lnk('löschen',$str_file.'?op=administrate_categories_del&category_id='. $arr_category['category_id'],false).']';
		$str_output .= '</div>';
	}
	addpregnav('/'.$str_file.'\?op=administrate_categories_(edit|del)&category_id=\d+/');
	addnav('Neue Kategorie hinzufügen',$str_file.'?op=administrate_categories_edit');
}
else if($_GET['op']=='administrate_categories_del')
{
	if((int)$_GET['category_id'] != 1)
	{
		$str_sql = 'UPDATE special_events SET category_id=1, released=0 WHERE category_id='.(int)$_GET['category_id'];
		$db_result = db_query($str_sql);

		$str_sql = 'DELETE FROM special_category WHERE category_id='.(int)$_GET['category_id'];
		$db_result = db_query($str_sql);

		if($db_result !== false)
		{
			$str_output .= 'Die gewünschte Kategorie wurde gelöscht, alle darauf verweisenden Specials wurden auf die Standardkategorie gesetzt und deaktiviert';
		}
		else
		{
			$str_output .= 'Die gewünschte Kategorie wurde nicht gelöscht!';
		}
	}
	else
	{
		$str_output .= 'Die Kategorie forest kann nicht gelöscht werden!';
	}
}
else if($_GET['op']=='administrate_categories_edit')
{
	if(isset($_POST['add_category']))
	{
		$str_sql = 'INSERT INTO special_category (category_name) VALUES ("'.$_POST['category_name'].'")';
		$str_output .= $str_sql;
		$db_result = db_query($str_sql);
		if($db_result !== false)
		{
			$str_output .= 'Die gewünschte Kategorie wurde eingefügt';
		}
		else
		{
			$str_output .= 'Die gewünschte Kategorie kann nicht eingefügt werden';
		}
	}
	if(isset($_POST['edit_category']))
	{
		$str_sql = 'UPDATE special_category SET category_name="'.$_POST['category_name'].'" WHERE category_id='.(int)$_POST['category_id'];
		$db_result = db_query($str_sql);
		if($db_result !== false)
		{
			$str_output .= 'Die gewünschte Kategorie wurde editiert';
		}
		else
		{
			$str_output .= 'Die gewünschte Kategorie konnte nicht editiert werden';
		}
	}

	//Editiere vorhandenes special
	if(isset($_GET['category_id']))
	{
		if((int)$_GET['category_id'] != 1)
		{

			$str_sql = 'SELECT * FROM special_category WHERE category_id='.(int)$_GET['category_id'];
			$db_result = db_query($str_sql);
			$arr_category = db_fetch_assoc($db_result);

			if(db_num_rows($db_result) == 1)
			{
				$str_output .= '<form method="POST" action="'.$str_file.'?op=administrate_categories_edit">';
				$str_output .= 'Ändere den Namen der Kategorie<br />';
				$str_output .= '<input type="text" name="category_name" value="'.$arr_category['category_name'].'"></input>';
				$str_output .= '<input type="submit" name="edit_category" value="Abschicken"></input>';
				$str_output .= '<input type="hidden" name="category_id" value="'.$arr_category['category_id'].'"></input>';
				$str_output .= '</form>';
			}
			else
			{
				$str_output .= 'Die Kategorie konnte nicht gefunden werden';
			}
		}
		else
		{
			$str_output .= 'Die Kategorie forest kann nicht editiert werden!';
		}
	}
	//Erstelle neue Kategorie
	else
	{
		$str_output .= '<form method="POST" action="'.$str_file.'?op=administrate_categories_edit">';
		$str_output .= 'Erstelle eine neue Kategorie<br />';
		$str_output .= '<input type="text" name="category_name" value=""></input>';
		$str_output .= '<input type="submit" name="add_category" value="Abschicken"></input>';
		$str_output .= '</form>';
	}
	addnav('',$str_file.'?op=administrate_categories_edit');
}
//Testen von Specials

if($_GET['editor_op'] == 'test')
{
	$session['user']['specialinc'] = '';
	$str_output .= '`n`nSUPERUSER Specials:`n
			Alle hier getesteten Specials verhalten sich so als wären sie dort aufgerufen wo sie auch normalerweise zu finden sind. Das bedeutet: `bNach Beendigung des Specials landet ihr auch bspw. im Wald/Schenke/usw.`b`n`n`0';
	$query_result = db_query('SELECT filename,category_name FROM special_events LEFT JOIN special_category USING (category_id) ORDER BY category_name ASC, filename ASC');

	$str_category = '';
	while (($row = db_fetch_assoc($query_result)) !== false)
	{
		if($str_category != $row['category_name'])
		{
			$str_category = $row['category_name'];
			$str_output .= '<div class="trlight">`b'.$row['category_name'].'`b</div>' ;
		}
		$str_output .= create_lnk($row['filename'],$str_file.'?editor_op=run_test&file='.$row['filename'],true).'`n';
	}
}
elseif ($_GET['editor_op'] == 'run_test')
{
	spc_get_special('forest',1000,$_GET['file'],array('editor_op','file'));
}

addnav('Zurück');
grotto_nav();
addnav('In den Wald','forest.php');

output($str_output,true);
page_footer();
?>
