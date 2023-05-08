<?php
/**
* su_creatures.php: Editor für Wald- + Friedhofmonster
* @author LOGD-Core, modded by Drachenserver-Team
* @version DS-E V/2
*/


require_once "common.php";
$access_control->su_check(access_control::SU_RIGHT_EDITORWORLD,true);

//select distinct creaturelevel,max(creaturehealth) as creaturehealth,max(creatureattack) as creatureattack,max(creaturedefense) as creaturedefense,max(creatureexp) as creatureexp,max(creaturegold) as creaturegold from creatures where creaturelevel<17 group by creaturelevel;
//update creatures set creatureattack=2 where creaturelevel=2
$creaturestattable="
+---------------+----------------+----------------+-----------------+-------------+--------------+
| creaturelevel | creaturehealth | creatureattack | creaturedefense | creatureexp | creaturegold |
+---------------+----------------+----------------+-----------------+-------------+--------------+
|             1 |             11 |              1 |               1 |          14 |           36 |
|             2 |             22 |              2 |               3 |          24 |           97 |
|             3 |             33 |              5 |               4 |          34 |          148 |
|             4 |             44 |              7 |               6 |          45 |          162 |
|             5 |             55 |              9 |               7 |          55 |          198 |
|             6 |             66 |             11 |               8 |          66 |          234 |
|             7 |             77 |             13 |              10 |          77 |          268 |
|             8 |             88 |             15 |              11 |          89 |          302 |
|             9 |             99 |             17 |              13 |         101 |          336 |
|            10 |            110 |             19 |              14 |         114 |          369 |
|            11 |            121 |             21 |              15 |         127 |          402 |
|            12 |            132 |             23 |              17 |         141 |          435 |
|            13 |            143 |             25 |              18 |         156 |          467 |
|            14 |            154 |             27 |              20 |         172 |          499 |
|            15 |            165 |             29 |              21 |         189 |          531 |
|            16 |            176 |             31 |              22 |         207 |          563 |
|            17 |            187 |             33 |              23 |         214 |          563 |
+---------------+----------------+----------------+-----------------+-------------+--------------+
";
$creaturestats=Array();
$creaturestattable=explode("\n",$creaturestattable);
$x=0;
while (list($key,$val)=each($creaturestattable))
{
	if (mb_strpos($val,"|")!==false)
	{
		$x++;
		$a = explode("|",$val);
		if ($x==1)
		{
			$stats=array();
			while (list($key1,$val1)=each($a))
			{
				if (trim($val1)>"")
				{
					$stats[$key1]=trim($val1);
				}
			}
		}
		else
		{
			reset($stats);
			while (list($key1,$val1)=each($stats))
			{
				$creaturestats[(int)$a[1]][$val1]=trim($a[$key1]);
			}
		}
	}
}

page_header("Creature Editor");

if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD))
{
	grotto_nav();
	if ($_POST['save']<>"")
	{
		if (!isset($_POST['location']))
		{
			$_POST['location']=0;
		}
		if ($_POST['id']!='')
		{
			$sql="UPDATE creatures SET ";
			
			foreach ($_POST as $key=>$value)
			{
				if (mb_substr($key,0,8)=="creature")
				{
					$sql.="$key = '$value', ";
				}
			}
			reset($creaturestats[(int)$_POST['creaturelevel']]);
			foreach ($creaturestats[$_POST['creaturelevel']] as $key=>$value)
			{
				if ($key!="creaturelevel" && mb_substr($key,0,8)=="creature")
				{
					$sql.="$key = \"".db_real_escape_string($value)."\", ";
				}
			}
			$sql.=" location=\"".(int)($_POST['location'])."\" ";
			$sql.= " WHERE creatureid='$_POST[id]'";
			
			db_query($sql);
			output(db_affected_rows()." ".(db_affected_rows()==1?"Eintrag":"Einträge")." geändert.");
		}
		else
		{
			$cols = array();
			$vals = array();
			
			foreach ($_POST as $key=>$value)
			{
				if (mb_substr($key,0,8)=="creature" || $key=="location")
				{
					array_push($cols,$key);
					array_push($vals,$value);
				}
			}
			reset($creaturestats[(int)$_POST['creaturelevel']]);
			foreach ($creaturestats[$_POST['creaturelevel']] as $key=>$value)
			{
				if ($key!="creaturelevel")
				{
					array_push($cols,$key);
					array_push($vals,$value);
				}
			}
			$sql="INSERT INTO creatures (".join(",",$cols).",createdby) VALUES(\"".join("\",\"",$vals)."\",\"".db_real_escape_string($session['user']['login'])."\")";
			db_query($sql);
		}
		Cache::delete(Cache::CACHE_TYPE_HDD , 'forestcreatures'.$_POST['creaturelevel']);
	}
	if ($_GET['op']=="count")
	{
		$sql='SELECT COUNT(*) AS c,creaturelevel FROM creatures GROUP BY creaturelevel ORDER BY creaturelevel ASC';
		$result=db_query($sql);
		$str_out='`^Alle Monster:`0`n';
		while ($row=db_fetch_assoc($result))
		{
			$str_out.='Level '.$row['creaturelevel'].': '.$row['c'].' Monster`n';
		}
		$sql='SELECT COUNT(*) AS c,creaturelevel FROM creatures WHERE location=1 GROUP BY creaturelevel ORDER BY creaturelevel ASC';
		$result=db_query($sql);
		$str_out.='`n`^Monster auf dem Friedhof:`0`n';
		while ($row=db_fetch_assoc($result))
		{
			$str_out.='Level '.$row['creaturelevel'].': '.$row['c'].' Monster`n';
		}
		output($str_out);
	}
	if ($_GET['op']=="del")
	{
		$sql = 'DELETE FROM creatures WHERE creatureid = '.$_GET['id'];
		db_query($sql);
		if (db_affected_rows()>0)
		{
			output("Kreatur gelöscht`n`n");
		}
		else
		{
			output("Kreatur nicht gelöscht: ".db_error(LINK));
		}
		$_GET['op']="";
	}
	
	if ($_GET['op']=="")
	{
		
		addnav('Aktionen');
		addnav("Eine Kreatur hinzufügen","su_creatures.php?op=add");
		addnav("Zählen","su_creatures.php?op=count");
		addnav('~');
		addnav('Meister-Editor','su_masters.php');
		
		$arr_res = page_nav('su_creatures.php','SELECT count(*) AS c FROM creatures');
		
		$sql = 'SELECT * FROM creatures';
		if ($_POST['searchform']==1)
		{
			$sql.=' WHERE 1
			'.($_POST['c_name']?' AND creaturename LIKE "%'.db_real_escape_string($_POST['c_name']).'%"':'').'
			'.($_POST['c_level']?' AND creaturelevel = "'.intval($_POST['c_level']).'"':'').'
			'.($_POST['c_weapon']?' AND creatureweapon LIKE "%'.db_real_escape_string($_POST['c_weapon']).'%"':'').'
			'.($_POST['c_lose']?' AND creaturelose LIKE "%'.db_real_escape_string($_POST['c_lose']).'%"':'').'
			'.($_POST['c_win']?' AND creaturewin LIKE "%'.db_real_escape_string($_POST['c_win']).'%"':'').'
			';
		}
		$sql.=' ORDER BY creaturelevel,creaturename ASC LIMIT '.$arr_res['limit'];
		$result = db_query($sql);
		$str_output.="<form action='su_creatures.php' method='post'>
		Name: <input type='text' name='c_name'><br>
		Level: <input type='text' name='c_level'><br>
		Waffe: <input type='text' name='c_weapon'><br>
		Spruch: <input type='text' name='c_lose'><br>
		Spott: <input type='text' name='c_win'><br>
		<input type='hidden' name='searchform' value='1'>
		<input type='submit' value='Suchen'>
		</form>`n";
		addnav('','su_creatures.php');
		$str_output.='<table>
		<tr class="trhead">
		<th>Ops</th>
		<th>Kreaturname</th>
		<th>Level</th>
		<th>Waffe</th>
		<th>Autor</th>
		</tr><tr class="trhead">
		<th>&nbsp;</th>
		<th colspan="4">Nachricht bei Tod und Sieg</th>
		</tr>';
		addnav('','su_creatures.php');
		
		$int_count = db_num_rows($result);
		
		for ($i=0; $i<$int_count; $i++)
		{
			$row = db_fetch_assoc($result);
			$str_output.='<tr class="'.($i%2?'trlight':'trdark').'">';
			if ($row['creaturelevel']==17 || $row['creaturelevel']==18)
			{
				$str_output.='<td> [Edit|Del] </td>';
			}
			else
			{
				$str_output.='<td>['.create_lnk('Edit','su_creatures.php?op=edit&page='.$_GET['page'].'&id='.$row['creatureid']).'|'.
				create_lnk('Del','su_creatures.php?op=del&page='.$_GET['page'].'&id='.$row['creatureid'],true,false,'Bist du dir sicher, dass du diese Kreatur löschen willst?').']</td>';
			}
			$str_output.='<td>`^'.$row['creaturename'].'`0</td>
			<td>'.$row['creaturelevel'].'</td>
			<td>`%'.$row['creatureweapon'].'`0</td>
			<td>'.$row['createdby'].'</td>
			</tr><tr class="'.($i%2?'trlight':'trdark').'">
			<td>&nbsp;</td>
			<td colspan="4">Monster tot: `b'.$row['creaturelose'].'`b<br>
			Spieler tot: `5'.($row['creaturewin']?$row['creaturewin']:'Zufallsspott').'`0</td>
			</tr>';
		}
		$str_output.='</table>';
		
		output($str_output);
	}
	else
	{
		if ($_GET['op']=="edit" || $_GET['op']=="add")
		{
			if ($_GET['op']=="edit")
			{
				$sql = "SELECT * FROM creatures WHERE creatureid=$_GET[id]";
				$result = db_query($sql);
				if (db_num_rows($result)<>1)
				{
					output("`4Fehler`0, diese Kreatur wurde nicht gefunden!");
				}
				else
				{
					$row = db_fetch_assoc($result);
				}
			}
			
			$arr_form = array('creaturename_pr'=>'Kreaturname-Vorschau:,preview,creaturename',
			'creaturename'=>'Kreaturname,text,50',
			'creatureweapon_pr'=>'Kreaturwaffe-Vorschau:,preview,creatureweapon',
			'creatureweapon'=>'Kreaturwaffe,text,50',
			'creaturelose_pr'=>'Nachricht-Vorschau:,preview,creaturelose',
			'creaturelose'=>'Nachricht bei Monster-Tod,text,120',
			'creaturewin_pr'=>'Nachricht-Vorschau:,preview,creaturewin',
			'creaturewin'=>'Nachricht bei Spieler-Tod,text,120',
			'creaturelevel'=>'Level,enum_order,1,16',
			'location'=>'Kreatur auch auf Friedhof,bool'
			);
			
			output("`c`&`bKreatur bearbeiten`b`c`n
<form action='su_creatures.php?page=".$_GET['page']."' method='POST'>",true);
			output("<input name='id' value=\"".utf8_htmlentities($_GET['id'])."\" type='hidden'><input name='save' value=\"1\" type='hidden'>",true);
			showform($arr_form,$row);
			output("</form>",true);
			output('`nBeim Spieler-Tod werden die folgenden Codes unterstützt (Groß- und Kleinschreibung wird unterschieden):`n
			%w = Name des Spielers`n
			%x = Waffe des Spielers`n
			%s = Geschlecht des Spielers (ihn/sie)`n
			%p = Geschlecht des Spielers (sein/ihr)`n
			%o = Geschlecht des Spielers (er/sie)`n
			(%W = Name des Monsters)`n
			(%X = Waffe des Monsters)`n
			[männl|weibl] = einzelne Worte geschlechtsspezifisch ersetzen ([Krieger|Kriegerin])`n');
			$taunt = str_replace('%x','Zahnstocher`0',$row['creaturewin']);
			$taunt = str_replace('%X',$row['creatureweapon'].'`0',$taunt);
			$taunt = str_replace('%W',$row['creaturename'].'`0',$taunt);
			$taunt = str_replace('%w','JoeBloe`0',$taunt);
			output('`nVorschau: '.get_taunt($taunt.'&nbsp;').'`n`n');
			addnav("","su_creatures.php?page=".$_GET['page']);
		}
		addnav("Zurück zum Monster-Editor","su_creatures.php?page=".$_GET['page']."");
	}
}

page_footer();
?>

