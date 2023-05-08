<?php

// 22062004

// Ramius' Shrine by unknown
// found at sourceforge project page
// translation and addons by anpera

require_once "common.php";

page_header("Schrein des Ramius");
output("<span style='color: #9900FF'>",true);

addcommentary();
checkday();

output("`c`b`,S`Ac`4hrein des Rami`Au`,s`0`b`c
`n`,I`An `4einer sehr stillen Nebenhöhle des Clubs entdeckst du einen Schrein des Gottes der Unterwelt. Hier kannst du beten, um geliebte Verstorbene wiederzuerwecken.
Die Inschriften verraten dir, daß es dich den dreifachen Aufwand kostet, einen anderen zu erwecken, als wenn du dich selbst von Ramius wiedererwecken lässt.
`n`n Nachdem du dich eine Weile darauf konzentriert hast, kannst du erkennen, daß du ".$session['user']['deathpower']." Gefallen bei `\$Ramius`4 hast.`n");

addnav("Zurück zum Club","rock.php");

if ($_GET['op']=="")
{
	checkday();
	$count=0;
	if ($session['user']['deathpower']>=150 && $session['user']['marriedto']>0 && $session['user']['charisma']==4294967295)
	{
		addnav("Ehepartner wiedererwecken","shrine.php?op=weiter&what=partner");
		output("`nDu kannst deinen Ehepartner für 150 Gefallen aus dem Reich der Toten zurückhol`Ae`,n.");
		$count++;
	}
	if ($session['user']['deathpower']>=300)
	{
		addnav("Krieger erwecken","shrine.php?op=weiter&what=normal");
		output("`nDu kannst einen beliebigen Krieger für 300 Gefallen erweck`Ae`,n.");
		$count++;
	}
	if ($session['user']['acctid']==getsetting("hasegg",0))
	{
		addnav("Goldenes Ei benutzen","shrine.php?op=weiter&what=egg");
		output("`nDu kannst das `^goldene Ei`0 benutzen, um jemanden wieder zu erweck`Ae`,n.");
		$count++;
	}
	if (!$count) 
	{
		output("`n Damit kannst du hier nichts anfang`Ae`,n.");
	}
}

else if ($_GET['op']=="weiter")
{
	$what=$_GET['what'];
	if ($what=="partner")
	{
		$sql = "SELECT name,login,acctid,alive,deathpower FROM accounts WHERE alive=0 AND acctid=".$session['user']['marriedto']."";
		$result = db_query($sql);
		if (db_num_rows($result))
		{
			$row = db_fetch_assoc($result);
			output("<form action='shrine.php?op=pickname&what=$what' method='POST'>
			`&".$row['name']."`6 hat ".$row['deathpower']." Gefallen bei `\$Ramius`6. Wiedererwecken?
			<input type='hidden' name='to' value=\"".utf8_htmlentities($row['login'])."\">
			`n`n<input type='submit' class='button' value='Wiedererwecken'>
			</form>");
			addnav("","shrine.php?op=pickname&what=$what");
		}
		else
		{
			output("`n`%Dein".($session['user']['sex']?" Partner":"e Partnerin")." ist nicht tot!");
			addnav("Zurück zum Schrein","shrine.php");
		}
	}
	else
	{
		output("Bitte gib den Namen dessen ein, den du wiedererwecken willst:
		`n`n<form action='shrine.php?op=findname&what=$what' method='POST'>Name:<input name='to'> (Unvollständige Namen werden automatisch ergänzt).
		`n`n`n<input type='submit' class='button' value='Vorschau'></form>");
        JS::Focus('to');
		addnav("","shrine.php?op=findname&what=$what");
		output("`n`n");
	}
}

else if ($_GET['op']=="findname")
{
	$what=$_GET['what'];
	$string = str_create_search_string($_POST['to']);
	$sql = "SELECT name,login,acctid,alive,deathpower FROM accounts WHERE alive=0 AND name LIKE '".$string."' ORDER BY login='".db_real_escape_string($_POST['to'])."' DESC LIMIT 100";
	$result = db_query($sql);
	if (db_num_rows($result)==1)
	{
		$row = db_fetch_assoc($result);
		output("<form action='shrine.php?op=pickname&what=$what' method='POST'>
		`&".$row['name']."`6 hat ".$row['deathpower']." Gefallen bei `\$Ramius`6. Wiedererwecken?
		<input type='hidden' name='to' value=\"".utf8_htmlentities($row['login'])."\">
		`n`n<input type='submit' class='button' value='Wiedererwecken'>
		</form>");
		addnav("","shrine.php?op=pickname&what=$what");
	}
	elseif(db_num_rows($result)>1)
	{
		if(db_num_rows($result)>100)
		{
			output("Der Schrein macht Geräusche, als kämen zu viele körperlose Seelen in Frage. Du solltest die Person genauer beschreiben.
			`n`n<form action='shrine.php?op=findname&what=$what' method='POST'>
			Name: <input name='to' value='". $_POST['to'] . "'> (Unvollständige Namen werden automatisch ergänzt).
			`n<input type='submit' class='button' value='Vorschau'></form>");
			addnav("","shrine.php?op=findname&what=$what");
		}
		output("<form action='shrine.php?op=pickname&what=$what' method='POST'>
		`6Erwecke <select name='to' class='input'>");
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<option value=\"".utf8_htmlentities($row['login'])."\">".strip_appoencode($row['name'],3)."</option>",true);
		}
		output("</select>
		`n`n<input type='submit' class='button' value='Wiedererwecken'>
		</form>");
		addnav("","shrine.php?op=pickname&what=$what");
	}
	else
	{
		output("`&Es konnte niemand mit diesem Namen gefunden werden.");
	}
	addnav("Neue Suche","shrine.php?op=weiter&what=$what");
}

else if($_GET['op']=="pickname") 
{
	$what=$_GET['what'];
	$result = db_query($sql = "SELECT name,acctid,alive,lasthit,lastip,emailaddress,uniqueid FROM accounts WHERE login='".$_POST['to']."' AND alive=0");
	if (db_num_rows($result)==1)
	{
		$row = db_fetch_assoc($result);
		if (ac_check($row))
		{
			output("`%Die Götter gewähren dir diesen Wunsch nicht. Du kannst deine eigenen oder derart verwandte Krieger nicht wiedererwecken.");
		}
		else
		{
			if ($what=="partner")
			{
				$session['user']['deathpower']-=150;
				addnews("`&".$session['user']['name']."`& hat ".($session['user']['sex']?"ihren Mann":"seine Frau")." ".$row['name']."& aus dem Reich der Toten erweckt.");
			}
			else if ($what=="egg")
			{
				addnews("`&".$session['user']['name']."`& hat das `^goldene Ei`& benutzt, um ".$row['name']."& aus dem Reich der Toten zu erwecken.");
				savesetting("hasegg","0");
				item_set(' tpl_id="goldenegg"', array('owner'=>0) );
			}
			else
			{
				$session['user']['deathpower']-=300;
				addnews("`&".$session['user']['name']."`& hat ".$row['name']."& aus dem Reich der Toten erweckt.");
			}
			$session['user']['donation']+=1;
			
			//falls ein LostUpdate eintritt noch den Ahnenschrein nutzen
			$sql='UPDATE account_extra_info SET dpower=32767 WHERE acctid='.$row['acctid'];
			db_query($sql);
			
			$session['user']['reputation']+=5;
			systemmail($row['acctid'],"`^Du wurdest mit einem Gebet bedacht","`&".$session['user']['name']."`6 hat dich mit einem Gebet bedacht, welches Ramius veranlasst hat, dir den Weg in die Welt der Lebenden zu ebnen! Du solltest ".($session['user']['sex']?"ihr":"ihm")." dafür dankbar sein.");
			debuglog(' hat an Ramius Schrein wiedererweckt: ',$row['acctid']);
		}
	}
	else
	{
		output("Das hat nicht geklappt. Versuche es nochmal.");
		addnav("Zurück","shrine.php");
	}
}

output("</span>",true);

page_footer();
?>
