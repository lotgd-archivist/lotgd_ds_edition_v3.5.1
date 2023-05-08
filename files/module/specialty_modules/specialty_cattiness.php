<?php
/**
* SpecialtyModul Cattiness
*/

$file = "specialty_cattiness";

function specialty_cattiness_info()
{
	global $info,$file;
	$info = array("author"=>"Maris",
	"version"=>"0.8",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Heimtücke",
	"color"=>"`5",
	"category"=>"Fähigkeiten",
	"fieldname"=>"cattiness"
	);
}

function specialty_cattiness_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_cattiness_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_cattiness_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/cattiness.png" />';
}

function specialty_cattiness_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	specialty_cattiness_info();
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	switch ($underfunction)
	{
	case "fightnav":
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses>0)
		{
			addnav($info['color']."Heimtücke`0", "");
			addnav($info['color']."&bull; Tot stellen`7 (0/".$uses.")`0"
			,$beginlink."&skill=cattiness&l=0"
			,true,false,false,$hotkey);
			
			addnav($info['color']."&bull; Hohn`7 (1/".$uses.")`0"
			,$beginlink."&skill=cattiness&l=1"
			,true,false,false,$hotkey);
		}
		
		if ($uses>1)
		{
			addnav($info['color']."&bull; Lebender Schild`7 (2/".$uses.")`0"
			,$beginlink."&skill=cattiness&l=2"
			,true,false,false,$hotkey);
		}
		
		if ($uses>2)
		{
			addnav($info['color']."&bull; Präzision`7 (3/".$uses.")`0"
			,$beginlink."&skill=cattiness&l=3"
			,true,false,false,$hotkey);
		}
		break;
		
		
	case "backgroundstory":
		output("`6Du hast dich schon immer über die Gutgläubigkeit der Menschen lustig gemacht und ihre Hilfsbereitschaft auszunutzen gewusst.
		Schnell war dir klar, dass du nur hilflos und schwach tun musst, um sie im rechten Moment zu überraschen und ihnen in den Rücken zu fallen.");
		break;
		
		
	case "link":
		
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nherausgefunden hast, dass List und Tücke stärker sind als jede Waffe.'
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
		
		if (($varvar== "session"?$session['user']['specialtyuses']['cattinessuses']:$GLOBALS[$varvar]['specialtyuses']['cattinessuses']) >= (int)$_GET['l'])
		{
			$creaturedmg = 0;
			
			switch ((int)$_GET['l'])
			{
				
			case 0:
				$buff = array("startmsg"=>$info['color']."`nDu lässt dich scheinbar hart getroffen zu Boden sinken und riskierst dabei einen Treffer.`n`n",
				"name"=>$info['color']."Tot stellen",
				"rounds"=>1,
				"wearoff"=>"Während dein Gegner keine Bedrohung mehr in dir sieht schmiedest du neue Pläne ihn zu überlisten.",
				"atkmod"=>0,
				"defmod"=>0.3,
				"activate"=>"defense"
				);
				if ($varvar=="session")
				{
					$session['bufflist']['cu1'] = $buff;
					if ($session['user']['specialtyuses']['cattinessuses']<=6)
					{
						$session['user']['specialtyuses']['cattinessuses']+=1;
					}
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['cu1'] = $buff;
					if ($GLOBALS[$varvar]['specialtyuses']['cattinessuses']<=6)
					{
						$GLOBALS[$varvar]['specialtyuses']['cattinessuses']+=1;
					}
				}
				break;
				
			case 1:
				$buff = array("startmsg"=>$info['color']."`nDu verspottest die Mutter deines Gegners und machst ihn sehr wütend und unvorsichtig.`n`n",
				"name"=>$info['color']."Hohn",
				"rounds"=>5,
				"wearoff"=>"Dein Gegner hat sich beruhigt.",
				"badguyatkmod"=>1.5,
				"badguydefmod"=>0.3,
				"roundmsg"=>"Dein Gegner prügelt blind auf dich ein und vernachlässigt seine Verteidigung!",
				"activate"=>"offense"
				);
				if ($varvar=="session")
				{
					$session['bufflist']['cu2'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['cu2'] = $buff;
				}
				break;
				
			case 2:
				$buff = array("startmsg"=>$info['color']."`nDu greifst in deine Taschen und ziehst eine gefesselte und gemarterte Blütenfee hervor, die du schützend vor dich hälst.`n`n",
				"name"=>$info['color']."Lebender Schild",
				"rounds"=>5,
				"wearoff"=>"Deine Fee konnte entkommen.",
				"roundmsg"=>"{badguy}`) attackiert dich zögerlicher, um die Fee nicht zu verletzen.",
				"badguyatkmod"=>0.2,
				"activate"=>"defense"
				);
				if ($varvar=="session")
				{
					$session['bufflist']['cu3'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['cu3'] = $buff;
				}
				break;
				
				
			case 3:
				$buff = array("startmsg"=>$info['color']."`nDu fixierst deinen Gegner und zielst auf die Stellen seines Körpers, an denen er besonders empfindlich ist.`n`n",
				"name"=>$info['color']."Präzision",
				"rounds"=>5,
				"wearoff"=>"{badguy}`) ist nun so zerstochen, dass du dir andere empfindliche Stellen suchen musst!",
				"atkmod"=>2.5,
				"roundmsg"=>"{badguy}`) heult vor Schmerz auf!",
				"activate"=>"offense,defense"
				);
				if ($varvar=="session")
				{
					$session['bufflist']['cu5'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['cu5'] = $buff;
				}
				break;
			}
			if ($varvar=="session")
			{
				$session['user']['specialtyuses']['cattinessuses']-=$_GET['l'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses']['cattinessuses']-=$_GET['l'];
			}
		}
		else
		{
            $buff = array("startmsg"=>"`nDu versuchst, {badguy}`) auszutricksen, wirst aber leider durchschaut.`n`n",
			"rounds"=>1,
			"activate"=>"roundstart"
			);
			if ($varvar=="session")
			{
				$session['bufflist']['cu0'] = $buff;
				$session['user']['reputation']--;
			}
			else
			{
				$GLOBALS[$varvar]['bufflist']['cu0'] = $buff;
			}
		}
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		
		break;
		
	case 'academy_desc':
		output('`3Selbststudium mit Büchern über perfide Leute der Geschichte: 
		`$'.$cost_low .'`^ Gold`n
		`3Praktische Übung im Parcours der Heimtücke: 
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n
		`$ Warchilds `3Lehrstunde für Ekelpakete: 
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;
		
		
	case 'academy_pratice':
		output('`^Du betrittst den `7Parcours der Heimtücke`^!`n
		Du torkelst sturzbetrunken umher und machst deine Sache wirklich gut! Doch leider ist dein gespieltes Elend echt und du sackst halb bewusstlos irgendwo am Rande des Parcours zusammen.
		Warchild hat Erbarmen und schleppt dich nach draussen ins Dorf.`n');
		break;
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_FOGGY))
		{
			$str_output='`^`nDer Nebel bietet Fieslingen einen zusätzlichen Vorteil. Du bekommst zwei zusätzliche Anwendungen.`n';
			$session['user']['specialtyuses']['cattinessuses']+=2;
			return($str_output);
		}
		break;
	}
}
?>