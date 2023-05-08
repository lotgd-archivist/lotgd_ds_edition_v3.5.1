<?php
/**
* Specialty Modul illusion
*/

$file = "specialty_illusion";

function specialty_illusion_info()
{
	global $info,$file;
	$info = array("author"=>"Laulajatar",
	"version"=>"1.0",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Illusionsmagie",
	"color"=>"`y",
	"category"=>"Magie",
	"fieldname"=>"illusion"
	);
}

function specialty_illusion_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_illusion_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_illusion_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/illusion.png" />';
}

function specialty_illusion_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	specialty_illusion_info();
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	switch ($underfunction)
	{
	case "fightnav":
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses>0)
		{
			addnav($info['color']."Illusionsmagie`0", "");
			addnav($info['color']."&bull; Blütenblätter`7 (1/".$uses.")`0"
			,$beginlink."&skill=illusion&l=1"
			,true,false,false,$hotkey);
		}
		
		if ($uses>1)
		{
			addnav($info['color']."&bull; Unsichtbar werden`7 (2/".$uses.")`0"
			,$beginlink."&skill=illusion&l=2"
			,true,false,false,$hotkey);
		}
		
		if ($uses>2)
		{
			addnav($info['color']."&bull; Doppelgänger`7 (3/".$uses.")`0"
			,$beginlink."&skill=illusion&l=3"
			,true,false,false,$hotkey);
		}
		
		if ($uses>4)
		{
			addnav($info['color']."&bull; Schwarzer Drache`7 (5/".$uses.")`0"
			,$beginlink."&skill=illusion&l=5"
			,true,false,false,$hotkey);
		}
		break;
		
		
	case "backgroundstory":
		output("Schon immer hat es dich fasziniert, wie leicht andere Wesen glauben, was sie sehen. Mit der Zeit gelang es dir, immer detailgetreuere Illusionen der Welt um dich herum zu schaffen, bis man sie mit bloßem Auge kaum noch von der Wirklichkeit unterscheiden konnte.");
		break;
		
		
	case "link":
		
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`ndie Macht von Illusionen erkannt hast.'
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
		
		if (($varvar== "session"?$session['user']['specialtyuses']['illusionuses']:$GLOBALS[$varvar]['specialtyuses']['illusionuses']) >= (int)$_GET['l'])
		{
			$creaturedmg = 0;
			
			switch ((int)$_GET['l'])
			{
				
			case 1:
				$buff = array("name"=>$info['color']."Blütenblätter",
				"rounds"=>3,
				"badguyatkmod"=>0.5,
				"badguydefmod"=>0.5,
				"startmsg"=>$info['color']."`nDu lässt viele kleine Blütenblätter um {badguy}s Kopf schwirren.`n`n",
				"roundmsg"=>"`^{badguy}`) ist vollkommen verwirrt von den Blütenblättern.",
				"wearoff"=>"Die Blütenblätter verschwinden wieder.",
				"activate"=>"roundstart"
				);
				
				if ($varvar=="session")
				{
					$session['bufflist']['illu1'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['illu1'] = $buff;
				}
				break;
				
				
			case 2:
				$buff = array("name"=>$info['color']."Unsichtbar werden",
				"rounds"=>1,
				"atkmod"=>4,
				"startmsg"=>$info['color']."`nDu verschwindest, um {badguy}`) unbemerkt angreifen zu können.`n`n",
				"roundmsg"=>"Du schleichst dich ungesehen an {badguy}`) heran und verpasst ihm einen kräftigen Schlag.",
				"wearoff"=>"{badguy}`) kommt wieder zu sich.",
				"activate"=>"offense"
				);
				
				if ($varvar=="session")
				{
					$session['bufflist']['illu2'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['illu2'] = $buff;
				}
				break;
				
				
			case 3:
				$buff = array("name"=>$info['color']."Doppelgänger",
				"rounds"=>2,
				"regen"=>$session['user']['level']*20,
				"startmsg"=>$info['color']."`nDu lässt ein genaues Ebenbild von dir erscheinen.`n`n",
				"wearoff"=>"Dein Doppelgänger ist verschwunden.",
				"effectmsg"=>"{badguy}`) kann sich nicht entscheiden und schlägt abwechselnd auf dich und deinen Doppelgänger ein, was dir ein wenig Zeit gibt, deine Wunden zu versorgen. Du heilst um `^{damage} `)Punkte.",
				"effectnodmgmsg"=>"Du bist bereits völlig gesund.",
				"activate"=>"roundstart"
				);
				
				if ($varvar=="session")
				{
					$session['bufflist']['illu3'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['illu3'] = $buff;
				}
				break;
				
				
			case 5:
				$buff = array("name"=>$info['color']."Schwarzer Drache",
				"rounds"=>5,
				"badguyatkmod"=>0,
				"badguydefmod"=>0,
				"startmsg"=>$info['color']."`nDu konzentriertst dich und lässt das Bild eines gigantischen schwarzen Drachen erscheinen.`n`n",
				"roundmsg"=>"`^{badguy}`) ist starr vor Angst und kann sich nicht bewegen.","wearoff"=>
				"Du kannst das Bild des schwarzen Drachen nicht länger aufrecherhalten.",
				"activate"=>"roundstart");
				
				if ($varvar=="session")
				{
					$session['bufflist']['illu5'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['illu5'] = $buff;
				}
				break;
			}
			if ($varvar=="session")
			{
				$session['user']['specialtyuses']['illusionuses']-=$_GET['l'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses']['illusionuses']-=$_GET['l'];
			}
		}
		else
		{
            $buff = array("startmsg"=>"`nDu versucht, eine Illusion zu erzeugen, aber nichteinmal eine Rauchwolke will dir gelingen.`n`n",
			"rounds"=>1,
			"activate"=>"roundstart"
			);
			if ($varvar=="session")
			{
				$session['bufflist']['illu0'] = $buff;
				$session['user']['reputation']--;
			}
			else
			{
				$GLOBALS[$varvar]['bufflist']['illu0'] = $buff;
			}
		}
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		
		break;
		
	case 'academy_desc':
		output('`ySelbststudium in der Bibliothek 
		`$'.$cost_low .'`^ Gold`n
		`yPraktische Übung auf dem Hof 
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n
		`y Privatstunde bei Warchild 
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;
		
		
	case 'academy_pratice':
		output('`yDu gehst auf den Hof und nimmst dir vor, alle mit deinen Illusionen zu beeidrucken! Leider hast du so viel getrunken, dass dein wildes Gefuchtel auch nach fünf Minuten noch kein Ergebnis zeigt. Stattdessen triffst du einen anderen Studenten, der gerade in deiner Nähe vorbeigeht. Fünf Minuten später schleichst du mit hängendem Kopf und einem blauen Auge aus der Akademie und beschließt, das nächste mal nur noch nüchtern wiederzukommen.`n`n
		`5Du verlierst ein paar Lebenspunkte!');
		$session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.8;
		break;
		
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_BOREALIS))
		{
			$str_output='`y`nDas seltsame Wetterleuchten inspiriert dich, deine Magie noch geschickter einzusetzen. Du erhältst eine zusätzliche Anwendung.`n';
			$session['user']['specialtyuses']['illusionuses']++;
			return($str_output);
		}
		break;
	}
}
?>