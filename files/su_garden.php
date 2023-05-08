<?php

## Garten-Editor
#haupsächlich zum Löcher-Stopfen, noch in Arbeit
#Löcher können immernoch "unerwartet" auftreten (im content[occupied] )
#vermutlich, wenn eine Pflanze "niemandem" gehört und der
#Gartenbesitzer die Pflanze entfernt.
#
# by Ravenclaw (aka Tyndal)

require_once "common.php";
page_header();

if ($_GET['garden'] > "")
{
	$change = false;
	$garden = db_fetch_assoc(db_query("SELECT he.*,h.houseid,a.name AS howner 
										FROM house_extensions AS he 
										LEFT JOIN houses AS h ON he.houseid=h.houseid 
										JOIN accounts AS a ON h.owner=a.acctid 
											WHERE id=".$_GET['garden']));
	$content = utf8_unserialize($garden['content']);
	
	if ($_GET['act'] == 'holes')
	{
		$holes = explode(",",$_POST['holes']);
		foreach($content as $key => $val)
		{
			if (in_array($val,$holes))
			{
				$content[$key] = 0;
			}
		}
		foreach($content['occupied'] as $key => $val)
		{
			if (in_array($val,$holes))
			{
				$content['occupied'][$key] = 0;
			}
		}
		unset($holes);
		$change_content = utf8_serialize($content);
		db_query("UPDATE house_extensions SET content='".$change_content."' WHERE id=".$garden['id']) or die("Fehler");
	}
	
	$crops = db_query("SELECT c.id,c.sizeh,c.sizev,c.stage,c.position,ct.path FROM crops AS c LEFT JOIN crops_tpl AS ct ON c.sort=ct.id WHERE garden=".$_GET['garden']."");
	
	while ($crop = db_fetch_assoc($crops))
	$wha[($crop['position'])] = $crop;
	$done = Array();
	
	for($i = 1; $i < 25; $i++)
	{
		if ($content['occupied'][$i] < 1) 
		{
			$what[$i] = "<td id='feld".$i."'><img src='./images/garden/soil.png'></td>";
		}
		else if (is_array($wha[$i]))
		{
			if (!$done[($wha[$i]['id'])])
			{
				$what[$i] = "<td id='feld".$i."' colspan=".$wha[$i]['sizeh']." rowspan=".$wha[$i]['sizev']." style='background-image:url(./images/garden/soil.png)'>
							<img src='./images/garden/".$wha[$i]['path']."/".$wha[$i]['stage'].".png'>
							</td>";
				$done[($wha[$i]['id'])] = 1;
			}
		}
	}
		
	
	$occu = $content['occupied'];
	if (is_array($occu))
	{
		ksort($occu);
		foreach($occu as $key => $val)
		{
			if ($what[$key] > "" || $done[$val])
			{
				unset($occu[$key]);
				continue;
			}
			else
			{
				if (!$done[$val])
				{
					$sizeh = 1;	$sizev = 1; $le = 0; $he = 0;
					while($occu[($key + ++$le)] == $occu[$key])	$sizeh++;
					while($occu[($key + (++$he)*6)] == $occu[$key])	$sizev++;
					$what[$key] = "<td colspan='$sizeh' rowspan='$sizev' align='center' width='".(50*$sizeh)."' height='".(50*$sizev)."'>$val`n`4Loch`0</td>";
					$done[$val] = 1;
					unset($occu[$key]);
					$holes .= ",".$val;
				}
				else
				{
					unset($occu[$key]);
				}
			}
		}
		#$str_out.= "`n`n".disp_array($hole)."`n`n";
	}
	
	$height = 1;
	$str_out.="<table border=1 cellspacing=0><tr>";

	for ($i=1;$i<25;$i++)
	{
			if (ceil($i/6) > $height)
			{
				$height++;
				$str_out.= "</tr><tr>";
			}
		if ($what[$i] == '') continue;
		else
		{
			$str_out .= $what[$i];
		}
	}
	
	$str_out.="</tr></table>";
	
	$holes = mb_substr($holes,1);
	$str_out.= "`n`c<form action='su_garden.php?garden=".$garden['id']."&act=holes' method='POST'>
				<input type='hidden' name='holes' value='".$holes."'>
				<input type='submit' value='".($holes > ""?"Löcher entfernen'>":"Sinnlos Erde verteilen!'> `$(keine Löcher gefunden, Stopfen erzwingen)`0")."
				</form>`c`n`n";
	addnav("","su_garden.php?garden=".$garden['id']."&act=holes");
	
	addnav("Aktualisieren","su_garden.php?garden=".$_GET['garden']);
	addnav("Zum Haus","su_houses.php?op=edit&id=".$garden['houseid']);
}

switch ($_GET['op'])
{
	case 'look' :
		switch($_GET['act'])
		{
			case '' :
			case 'search' :
				addnav("ganze Liste anzeigen","su_garden.php?op=look&act=list");
				$str_out .= "`n`n<form action='su_garden.php?op=look&act=find' method='POST'>
							<table>
							<tr><td colspan=2>Wonach möchtest du suchen ?</td></tr>
							<tr><td>Charakter-Name : </td><td><input name='name'></td></tr>
							<tr><td>Hausname : </td><td><input name='hname'></td></tr>
							<tr><td colspan=2 align='center'><input type='submit' value='Suchen'></td></tr>
							</table>
							</form>";
				addnav("","su_garden.php?op=look&act=find");
			break;
			
			case 'find' :
				if ($_POST['name'] > "" || $_POST['hname'] > "")
				{
					for($i = 0; $i < mb_strlen($_POST['name']); $i++)
					{
						$search_name .= "%".$_POST['name'][$i];
					}
					for($i = 0; $i < mb_strlen($_POST['hname']); $i++)
					{
						$search_hname .= "%".$_POST['hname'][$i];
					}
					$search_name .= "%";
					$search_hname .= "%";
					$sql = "SELECT he.id,h.housename,h.houseid AS hid,a.name 
								FROM house_extensions AS he 
								LEFT JOIN houses AS h ON he.houseid=h.houseid
								JOIN accounts AS a ON h.owner=a.acctid
									WHERE type='garden'".
									($_POST['name']>""?" AND a.name LIKE \"".$search_name."\"":'').
									($_POST['hname']>""?" AND h.housename LIKE \"".$search_hname."\"":'').
									"";
					$str_out.= $sql."`n".$_POST['name']."`n".$_POST['hname']."`n";
					$dbres = db_query($sql);
					if (db_num_rows($dbres) > 0)
					{
						$str_out .= "<table border=1 cellspacing=0 cellpadding=2>";
						while($row = db_fetch_assoc($dbres))
						{
							$str_out .= "<tr>
										<td><a href='su_garden.php?garden=".$row['id']."'>Garten".$row['id']."</a></td>
										<td><a href='su_houses.php?op=edit&id='".$row['houseid'].">".$row['housename']."</a></td>
										<td>".$row['name']."</td>
										</tr>";
							addnav("","su_garden.php?garden=".$row['id']);
						}
						$str_out .= "</table>";
					}
					else
					{
						$str_out .= "Leider wurde nichts Passendes gefunden.";
					}
				}
				else
				{
					$str_out .= "Bitte einen Charakter- oder Hausnamen angeben oder den Nav \"ganze Liste anzeigen\" anklicken. Danke.";
				}
				addnav("Zurück zur Suche","su_garden.php?op=look&act=search");
			break;
			
			case 'list' :
				$this_link = "su_garden.php?op=look&act=list";
				$howmany_sql = "SELECT count(*) AS c FROM house_extensions WHERE type='garden'";
				$arr_page_res = page_nav($this_link,$howmany_sql,25);
				$sql = "SELECT he.*,h.housename,h.houseid,a.name AS howner 
							FROM house_extensions AS he 
							LEFT JOIN houses AS h ON he.houseid=h.houseid 
							JOIN accounts AS a ON h.owner=a.acctid 
								WHERE type='garden' 
									ORDER BY id ASC";
				$dbres = db_query($sql." LIMIT ".$arr_page_res['limit']);
				if (db_num_rows($dbres) > 0)
				{
					$str_out.= "<table border=1 cellspacing=0 cellpadding=2><tr align='center'>
								<td>Garten-ID</td>
								<td>Besitzer</td>
								<td>Haus</td>
								</tr>";
					while($row = db_fetch_assoc($dbres))
					{
						$str_out .= "<tr>
									<td><a href='su_garden.php?garden=".$row['id']."'>Garten ".$row['id']."</a></td>
									<td> ".$row['howner']."`0</td>
									<td> <a href='su_houses.php?op=edit&id=".$row['houseid']."'>".$row['housename']."`0</a></td>
									</tr>";
						addnav("","su_garden.php?garden=".$row['id']);
					}
					$str_out.= "</table>";
				}
				else
				{
					$str_out .= "Keine Gärten gefunden.";
				}
			break;
		}
	break;

	case 'holesearch' :
		if ($_POST)
		{
			if ($_POST['von'] == "")	$_POST['von'] = 0;
			if ($_POST['wieviele'] == "")	$_POST['wieviele'] = 9999;
			$gardens = db_query("SELECT he.id,he.content FROM house_extensions AS he WHERE type='garden' AND he.id>=".$_POST['von']." ORDER BY he.id ASC LIMIT 0,".$_POST['wieviele']);
			while($g = db_fetch_assoc($gardens))
			{
				$id = $g['id'];
				$plants = utf8_unserialize($g['content']);
				foreach($plants as $key => $val)
				{
					if (!is_int($key) || $val == 0)
					{
						unset($plants[$key]);
					}
				}
				$holes[$id]['may'] = count($plants);
				$holes[$id]['has'] = db_num_rows(db_query("SELECT id FROM crops WHERE garden=".$g['id']));
				
				if ( ($_POST['onlybad']?$holes[$id]['has']<$holes[$id]['may']:1) )
				{
					$how_many++;
					$str_out.= "<a href='su_garden.php?garden=$id'>
								".($holes[$id]['has']<$holes[$id]['may']?"`4":"`@")."Garten ".$id." (".$holes[$id]['has']." von ".$holes[$id]['may']." Pflanzen)</a>`n";
					addnav("","su_garden.php?garden=$id");
				}
			}
			if ($how_many == 0)
			{
				$str_out.= "`@Keine Löcher gefunden.`0`n";
			}
		}
		else
		{
			$str_out.= "<form action='su_garden.php?op=holesearch' method='POST'>
						Löcher suchen bei Gärten 
						von ID <input name='von' value='".$_POST['von']."'>
						, <input name='wieviele' value='".$_POST['wieviele']."'> finden.`n
						`c<input type='checkbox' name='onlybad' checked>Nur Gärten mit Löchern anzeigen ?
						`n<input type='submit' value='Suchen'>`c
						</form>";
			addnav("","su_garden.php?op=holesearch");
		}
	break;
}

output($str_out);

addnav("Allgemeines");
addnav("Gärten anzeigen","su_garden.php?op=look");
addnav("Löcher suchen","su_garden.php?op=holesearch");

addnav("Zurück");
addnav("Zur Grotte","superuser.php");
page_footer();
?>