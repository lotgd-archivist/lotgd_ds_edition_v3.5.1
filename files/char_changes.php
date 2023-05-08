<?php

require_once('common.php');
page_header('Charakterveränderungen');
$str_output = '';

// Problem scheint zu sein, dass beim Mitschleifen des rp-Links URL-Spezialzeichen wie '&' verloren gehen.
// slayers Lösung mit den persistenten Navs ist besser, aber das hier sollte auch funktionieren.

$arr_nav_vars = persistent_nav_vars(array('resurrection','rp'));

$resline = urlencode(urldecode($arr_nav_vars['resurrection']));
$rp = urlencode(urldecode($arr_nav_vars['rp']));

$str_filename = basename(__FILE__);

//Der User möchte sein "Pack" ändern
if($_GET['op'] == 'change_pack')
{
	$Char->specialty = 0;
	$Char->race = '';	
}

if($_GET['op'] == 'nochange_pack')
{
	// Permanenz-Boni
	race_set_boni(true,false,$session['user']);

	if ($session['user']['dragonkills']==0 && $session['user']['level']==1){
		addnews('`#'.$session['user']['name'].' `#hat unsere Welt betreten. Willkommen!');
		addhistory('Ankunft in '.getsetting('townname','Atrahor'));
	}
}

if (count($Char->dragonpoints) == $Char->dragonkills && $session['changed_dragonpoints'] == true)
{
	$sql = 'SELECT * FROM specialty WHERE specid="'.$Char->specialty.'"';
	$row = db_get($sql);
	$str_specialty = $row['specname'];
	
	$sql = 'SELECT name FROM races WHERE id="'.$Char->race.'"';
	$row = db_get($sql);
	$str_race = $row['name'];

	$str_output .= get_title('`yDein Lebensweg').'`tGestärkt durch deinen Kampf ist es dir nun vergönnt deinen Lebensweg zu überdenken.`nMöchtest du Dinge aus deiner Vergangenheit verändern und Rasse, Spezialisierung und Gesinnung verändern?
  	`n`n<hr>
	`yMomentan Charakterisieren dich folgende Attribute:`t`n
	<b>Rasse</b>:'.$str_race.'`n
	<b>Spezialfertigkeit</b>:'.$str_specialty.'`n`n`n
	';
	output($str_output);
	addnav('a?Charakterwerte ändern',$str_filename.'?op=change_pack');
	addnav('b?Charakterwerte beibehalten',$str_filename.'?op=nochange_pack');	
	unset($session['changed_dragonpoints']);
}
//Wenn alle Dinge die wir hier abhandeln gesetzt wurden, dann gehts zurück zur Newday Datei
elseif ((count($Char->dragonpoints) == (int)$Char->dragonkills) && !empty($Char->race) && $Char->specialty != 0 )
{
	redirect('newday.php?resurrection='.$resline.'&rp='.$rp);
}

//Drachenpunkte
if (count($session['user']['dragonpoints']) < $session['user']['dragonkills'])
{
	page_header('Heldenpunkte');
	
	//Der User hat einen Wert ausgewählt
	if(!is_null_or_empty($_GET['dk']))
	{
		$session['user']['dragonpoints'][] = $_GET['dk'];
	
		switch($_GET['dk']){
	
			case 'hp':
	
				$session['user']['maxhitpoints']+=5;
	
				break;
	
			case 'at':
	
				$session['user']['attack']++;
	
				break;
	
			case 'de':
	
				$session['user']['defence']++;
	
				break;
	
		}
	
		// Nein, der Drachenhort zählt nicht als Haus ;)
	
		$session['user']['restatlocation']=0;
		$session['changed_dragonpoints'] = true;
		redirect($str_filename);
	}
	//Der user muss einen Wert auswählen
	else 
	{	
		$str_output = get_title('Der Lohn deiner Heldentaten');
	
		if($_GET['wks']=='verbot') 
		{
			$str_output .= get_extended_text('heldenpunkte_wkverbot').'`n`n';
		}
	
		if(is_array($session['user']['dragonpoints']))
		{
			$int_de = 0;
			$int_at = 0;
			$int_ff = 0;
			$int_hp = 0;
			foreach($session['user']['dragonpoints'] as $val)
			{
				if ($val=='at' || $val=='atk')
				{
					$int_at++;
				}
				elseif ($val=='de' || $val == 'def')
				{
					$int_de++;
				}
				elseif ($val=='ff')
				{
					$int_ff++;
				}
				elseif ($val=='hp')
				{
					$int_hp++;
				}
			}
			$str_output .= '
			<table style="margin:auto; text-align:center; margin-bottom:10px;">
				<tr class="trdark">
					<td colspan="4">`&<b>Deine bisherigen Heldentaten hast du in folgende Boni investiert:</b>`0</td>
				</tr>
				<tr class="trhead">
					<th><b>Lebenspunkte</b></th>
					<th><b>Waldkämpfe</b></th>
					<th><b>Angriff</b></th>
					<th><b>Verteidigung</b></th>
				</tr>
				<tr class="trlight">
					<td>'.$int_hp.'</td>
					<td>'.$int_ff.'</td>
					<td>'.$int_at.'</td>
					<td>'.$int_de.'</td>
				</tr>
			</table>
			<hr>
			';
		}
	
		addnav('Max Lebenspunkte +5','char_changes.php?dk=hp');
	
		if (($session['user']['dragonkills']>=60) && ($int_ff>((int)($session['user']['dragonkills']/3))))
		{
			addnav('Waldkämpfe +1','char_changes.php?wks=verbot');
		} 
		else 
		{
			addnav('Waldkämpfe +1','char_changes.php?dk=ff');
		}
	
		addnav('Angriff + 1','char_changes.php?dk=at');
	
		addnav('Verteidigung + 1','char_changes.php?dk=de');
	
		// geplante weitere Auswahlmöglichkeiten: Spezialfähigkeiten die nicht in der Akademie gelernt werden können
		//	if($session['user']['dragonkills']>10) {
		//	addnav('Feuermagie + 1','char_changes.php?dk=mf&resurrection='.$resline.'&rp='.$rp);
		//	addnav('Wassermagie + 1','char_changes.php?dk=mw&resurrection='.$resline.'&rp='.$rp);
		//	addnav('Erdmagie + 1','char_changes.php?dk=mw&resurrection='.$resline.'&rp='.$rp);
		//	addnav('Luftmagie + 1','char_changes.php?dk=ma&resurrection='.$resline.'&rp='.$rp);
		//	addnav('Geistmagie + 1','char_changes.php?dk=ms&resurrection='.$resline.'&rp='.$rp);
		//	}
	
		$str_output .= '`tDu hast noch `y`b'.($session['user']['dragonkills']-count($session['user']['dragonpoints'])).'`b`t  Heldenpunkte übrig. Wie willst du sie einsetzen?`n`n';
	
		$str_output .= 'Du bekommst `y`b1`b`t Heldenpunkt für jede deiner Heldentaten. Die Änderungen der Eigenschaften durch Heldenpunkte sind permanent.';
		output($str_output);
	}	
}
else if (empty($session['user']['race']))
{
	page_header('Ein wenig über deine Vorgeschichte');
	if (!empty($_GET['setrace']))
	{
		$str_race = urldecode(stripslashes($_GET['setrace']));

		$session['user']['race'] = $str_race;
		$arr_race = race_get($session['user']['race'],true);

		// Beschreibung
		output($arr_race['chosen_msg']);

		// Permanenz-Boni
		race_set_boni(true,false,$session['user']);

		addnav('Weiter','char_changes.php');
		
		/*
		if ($session['user']['specialty']==0)
		{
			addnav('Weiter','char_changes.php');
		}
		else
		{
			addnav('Weiter','newday.php?resurrection='.$resline.'&rp='.$rp);
		}
		*/

		if ($session['user']['dragonkills']==0 && $session['user']['level']==1){
			addnews('`#'.$session['user']['name'].' `#hat unsere Welt betreten. Willkommen!');
			addhistory('Ankunft in '.getsetting('townname','Atrahor'));
		}
	}
	else
	{
		// Rassenliste abrufen

		$sql = 'SELECT long_desc,id,name,colname FROM races WHERE active=1 AND mindk <= '.(int)$session['user']['dragonkills'].' AND superuser <= '.(int)$session['user']['superuser'].' ORDER BY name ASC';
		$res = db_query($sql);

		$str_out = '`b`c`&Welcher Rasse gehörst du an?`b`c`n`n
					<table border="0">';
		$str_racelnk = basename(__FILE__).'?setrace=';

		while($r = db_fetch_assoc($res)) {

			$str_out .= '<tr class="trdark"><td colspan="2">&nbsp;</td></tr>
						<tr class="trlight"><td valign="top">'.
			create_lnk('&raquo;`b'.$r['colname'].'`b',$str_racelnk.urlencode($r['id'])).': </td>'.
			'<td>'.$r['long_desc'].'</td></tr>';
			addnav($r['name'],$str_racelnk.urlencode($r['id']));

		}
		$str_out .= '</table>';
		$str_output .= $str_out;
		output($str_output);
	}
}	// END Rasse nicht gesetzt
elseif ((int)$session['user']['specialty']==0)
{
	if (!isset($_GET['setspecialty']))
	{
		page_header('Ein wenig über deine Vorgeschichte');
		$str_output .= 'Du erinnerst dich, dass du als Kind:`n`n';
		output($str_output,true);
		$str_output = '';
		$str_where=' WHERE active="1" ';
		if($session['user']['exchangequest']<29)
		{
			$str_where.=' AND usename!="wisdom" ';
		}
		$sql = 'SELECT * FROM specialty '.$str_where.' ORDER BY category,specid';
		$result = db_query($sql);
		$i=0;
		$category = '';
		$str_out = '';
		while($row = db_fetch_assoc($result))
		{			
			$file = $row['filename'];
			if(file_exists('./module/specialty_modules/'.$file.'.php'))
			{
				require_once('./module/specialty_modules/'.$file.'.php');
				if ($category!=$row['category'])
				{
					addnav($row['category']);
					$category = $row['category'];
				}
				$str_class = ($i&1) ? 'trlight' : 'trdark';
				$f_image 	= $file.'_image';
				$f_info 	= $file.'_info';
				$f_run 		= $file.'_run';
				
				$f_info();
				$str_out .= '<div class="'.$str_class.'" style="height:75px;">
				<div style="float:left;">'.$f_image().'</div>
				<div style="padding-top:3px;">'.$f_run('link',$row['specid']).'</div>
				</div><div style="clear:both;"></div>';
								
				$i++;
			}			
		}
		output($str_out);
	}
	else
	{
		addnav('Weiter',$str_filename);
		$sql = 'SELECT * FROM specialty WHERE specid="'.$_GET['setspecialty'].'"';
		$row = db_fetch_assoc(db_query($sql));
		page_header($row['specname']);
		switch($_GET['setspecialty'])
		{
			case $row['specid']:
				$file = $row['filename'];
				if(file_exists('./module/specialty_modules/'.$file.'.php'))
				{
					require_once ('./module/specialty_modules/'.$file.'.php');
					$f1 = $file.'_info';
					$f2 = $file.'_run("backgroundstory");';
					$f1();
                    eval(utf8_eval($f2));
				}
				break;
		}
		$session['user']['specialty']=(int)$_GET['setspecialty'];
	}
}	// END specialty nicht gesetzt
page_footer();
?>