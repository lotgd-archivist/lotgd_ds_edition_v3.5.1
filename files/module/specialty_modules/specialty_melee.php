<?php
/**
* Specialty Modul melee
*/

$file = "specialty_melee";

function specialty_melee_info()
{
	global $info,$file;
	$info = array("author"=>"Laulajatar",
	"version"=>"1.0",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Nahkampf",
	"color"=>"`u",
	"category"=>"Kampfkünste",
	"fieldname"=>"melee"
	);
}

function specialty_melee_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_melee_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_melee_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/melee.png" />';
}

function specialty_melee_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	specialty_melee_info();
	switch ($underfunction)
	{
	case "fightnav":
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses>0)
		{
			addnav($info['color']."Nahkampf`0", "");
			addnav($info['color']."&bull; Hieb`7 (1/".$uses.")`0"
			,$beginlink."&skill=melee&l=1"
			,true,false,false,$hotkey);
		}
		
		if ($uses>1)
		{
			addnav($info['color']."&bull; Ausweichen`7 (2/".$uses.")`0"
			,$beginlink."&skill=melee&l=2"
			,true,false,false,$hotkey);
		}
		
		if ($uses>2)
		{
			addnav($info['color']."&bull; Sprungangriff`7 (3/".$uses.")`0"
			,$beginlink."&skill=melee&l=3"
			,true,false,false,$hotkey);
		}
		
		if ($uses>4)
		{
			addnav($info['color']."&bull; Schwerttanz`7 (5/".$uses.")`0"
			,$beginlink."&skill=melee&l=5"
			,true,false,false,$hotkey);
		}
		break;
		
		
	case "backgroundstory":
		output("Im Kampf hast du dich schon immer auf deine Waffen und deine Stärke verlassen und es vorgezogen, deinem Gegner Auge in Auge gegenüberzustehen. Ob mit Schwert, Kampfaxt oder Keule, kein Gegner wird leichtes Spiel mit dir haben.");
		break;
		
		
	case "link":
		
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nschon dein erstes Holzschwert hattest und fleißig mit deinem Vater trainiert hast.'
					,'char_changes.php?setspecialty=' . $mid
					,true
					,true
					,false
					,false
					,$info['color'].$info['specname']."`0"
					,CREATE_LINK_LEFT_NAV_HOTKEY
				)
			);
		break;
		
		
	case "buff":
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_unserialize($GLOBALS[$varvar]['specialtyuses']);
		
		if (($varvar== "session"?$session['user']['specialtyuses']['meleeuses']:$GLOBALS[$varvar]['specialtyuses']['meleeuses']) >= (int)$_GET['l'])
		{
			$creaturedmg = 0;
			
			switch ((int)$_GET['l'])
			{
				
			case 1:
				$buff = array("name"=>$info['color']."Hieb"
				,"rounds"=>1
				,"atkmod"=>2
				,"startmsg"=>$info['color']."Du holst weit aus, um {badguy}`) einen kräftigen Schlag zu verpassen.`n`n"
				,"roundmsg"=>"Du triffst{badguy}`) so hart, dass er Sternchen sieht."
				,"activate"=>"offense");
				
				if ($varvar=="session")
				{
					$session['bufflist']['melee1'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['melee1'] = $buff;
				}
				break;
				
				
			case 2:
				$buff = array("name"=>$info['color']."Ausweichen"
				,"rounds"=>5
				,"defmod"=>3
				,"startmsg"=>$info['color']."Du konzentrierst dich darauf, den Schlägen deines Gegners auszuweichen.`n`n"
				,"roundmsg"=>"Du bist kaum zu treffen."
				,"wearoff"=>"Außer Atem werden deine Bewegungen wieder langsamer."
				,"activate"=>"defense");
				
				if ($varvar=="session")
				{
					$session['bufflist']['melee2'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['melee2'] = $buff;
				}
				break;
				
				
			case 3:
				$buff = array("name"=>$info['color']."Sprungangriff"
				,"rounds"=>2
				,"atkmod"=>4
				,"startmsg"=>$info['color']."Du springst mit aller Kraft auf {badguy}".$info['color']." zu. `n`n"
				,"roundmsg"=>"Du triffst {badguy}`) mit voller Kraft."
				,"wearoff"=>"Du nimmst wieder deine normale Kampfhaltung ein."
				,"activate"=>"offense");
				
				if ($varvar=="session")
				{
					$session['bufflist']['melee3'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['melee3'] = $buff;
				}
				break;
				
				
			case 5:
				$buff = array("name"=>$info['color']."Schwerttanz"
				,"rounds"=>10
				,"minioncount"=>round(($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level'])/2)+2
				,"maxbadguydamage"=>round(($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level'])/2)+2
				,"startmsg"=>$info['color']."Du schwingst deine Klinge so schnell, dass man sie mit bloßem Auge kaum noch erkennen kann, als du dich auf {badguy}`) stürzt.`n`n"
				,"roundmsg"=>"{badguy}`) verschwindet unter einem Hagel deiner Hiebe."
				,"wearoff"=>"Außer Atem lässt du deine Schwerter sinken."
				,"effectmsg"=>"Du triffst {badguy}`) mit `^{damage}`) Schadenspunkten."
				,"effectnodmgmsg"=>"Du versuchst {badguy}`) zu treffen, `\$TRIFFST ABER NICHT!"
				,"activate"=>"offense");
				
				if ($varvar=="session")
				{
					$session['bufflist']['melee5'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['melee5'] = $buff;
				}
				break;
			}
			if ($varvar=="session")
			{
				$session['user']['specialtyuses']['meleeuses']-=$_GET['l'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses']['meleeuses']-=$_GET['l'];
			}
		}
		else
		{
            $buff = array("startmsg"=>"`nDu schlägst nach {badguy}, doch er hält deine Klinge mit seinem kleinen Finger auf.`n`n",
			"rounds"=>1,
			"activate"=>"roundstart"
			);
			if ($varvar=="session")
			{
				$session['bufflist']['melee0'] = $buff;
				$session['user']['reputation']--;
			}
			else
			{
				$GLOBALS[$varvar]['bufflist']['melee0'] = $buff;
			}
		}
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		
		break;
		
	case 'academy_desc':
		output($info['color'].'Selbststudium im Übungsraum 
		`$'.$cost_low .'`^ Gold`n'
		.$info['color'].'Praktische Übung auf dem Kampfplatz 
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n'
		.$info['color'].' Kampftraining mit Warchild 
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;
		
		
	case 'academy_pratice':
		output($info['color'].'Du torkelst auf den Platz, um deine kämpferischen Fähigkeiten zu verbessern. Ziellos versuchst du, auf die Strohpuppe einzuschlagen, triffst jedoch nur dich selbst. Unter dem Gelächter einiger Studenten verschwindest du so schnell dich deine schwankenden Beine noch tragen wollen.`n`n
		`5Du verlierst ein paar Lebenspunkte!');
		$session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.8;
		break;
		
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_CLOUDY_LIGHT))
		{
			$str_output=$info['color'].'`nDas Wetter ist nicht zu warm und nicht zu kalt, genau richtig zum Kämpfen. Du erhältst eine zusätzliche Anwendung!`n';
			$session['user']['specialtyuses']['meleeuses']++;
			return($str_output);
		}
		break;
	}
}

?>