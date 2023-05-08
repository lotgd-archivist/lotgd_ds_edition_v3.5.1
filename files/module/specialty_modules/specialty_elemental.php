<?php
/**
* Specialty Modul elemental
*/

$file = "specialty_elemental";

function specialty_elemental_info()
{
	global $info,$file;
	$info = array("author"=>"Laulajatar",
	"version"=>"1.0",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Elementarmagie",
	"color"=>"`q",
	"category"=>"Magie",
	"fieldname"=>"elemental"
	);
}

function specialty_elemental_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_elemental_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_elemental_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/elemental.png" />';
}

function specialty_elemental_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	specialty_elemental_info();
	switch ($underfunction)
	{
	case "fightnav":
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses>0)
		{
			addnav($info['color']."Elementarmagie`0", "");
			addnav($info['color']."&bull; Feuerball`7 (1/".$uses.")`0"
			,$beginlink."&skill=elemental&l=1"
			,true,false,false,$hotkey);
		}
		
		if ($uses>1)
		{
			addnav($info['color']."&bull; Erdenstärke`7 (2/".$uses.")`0"
			,$beginlink."&skill=elemental&l=2"
			,true,false,false,$hotkey);
		}
		
		if ($uses>2)
		{
			addnav($info['color']."&bull; Wasseraura`7 (3/".$uses.")`0"
			,$beginlink."&skill=elemental&l=3"
			,true,false,false,$hotkey);
		}
		
		if ($uses>4)
		{
			addnav($info['color']."&bull; Windklingen`7 (5/".$uses.")`0"
			,$beginlink."&skill=elemental&l=5"
			,true,false,false,$hotkey);
		}
		break;
		
		
	case "backgroundstory":
		output("Die Kraft der Elemente ist für dich die einzig wahre Macht. Seit deiner Kindheit hast du dich mit ihnen beschäftigt und gelernt Feuer, Wasser, Erde und Luft zu rufen und zu kontrollieren. Die Elemente gehorchen deinem Wort, sodass du sie sogar ihren Zorn gegen deine Feinde richten kannst.");
		break;
		
		
	case "link":

		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nfasziniert von den Naturgewalten warst und es dein sehnlichster Wunsch war, sie deinem Wort gehorchen zu lassen.'
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
		
		if (($varvar== "session"?$session['user']['specialtyuses']['elementaluses']:$GLOBALS[$varvar]['specialtyuses']['elementaluses']) >= (int)$_GET['l'])
		{
			$creaturedmg = 0;
			
			switch ((int)$_GET['l'])
			{
				
			case 1:
				$buff = array("name"=>$info['color']."Feuerball"
				,"rounds"=>1
				,"minioncount"=>1
				,"minbadguydamage"=>$session['user']['level']*2
				,"maxbadguydamage"=>$session['user']['level']*4
				,"startmsg"=>$info['color']."Du konzentrierst dich und lässt einen Feuerball auf deiner Handfläche erscheinen, den du {badguy}`) entgegenschleuderst.`n`n"
				,"effectmsg"=>"Du triffst {badguy}`) mit `^{damage}`) Schadenspunkten."
				,"activate"=>"roundstart");
				
				if ($varvar=="session")
				{
					$session['bufflist']['elem1'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['elem1'] = $buff;
				}
				break;
				
				
			case 2:
				$buff = array("name"=>$info['color']."Erdenstärke"
				,"rounds"=>2
				,"atkmod"=>3
				,"startmsg"=>$info['color']."Du konzentrierst dich und machst dir die Stärke der Erde zu Nutzen.`n`n"
				,"roundmsg"=>"Du schlägst mit der Kraft der Erde selbst zu."
				,"wearoff"=>"Die Erdenkraft verlässt dich wieder."
				,"activate"=>"offense");
				
				if ($varvar=="session")
				{
					$session['bufflist']['elem2'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['elem2'] = $buff;
				}
				break;
				
				
			case 3:
				$buff = array("name"=>$info['color']."Wasseraura"
				,"rounds"=>5
				,"defmod"=>4
				,"startmsg"=>$info['color']."Du konzentrierst dich und lässt eine Wand aus Wasser erscheinen, die um dich herum in der Luft zu stehen scheint.`n`n"
				,"roundmsg"=>"Die Wasseraura fängt einen Teil des Schadens ab."
				,"wearoff"=>"Die Wasseraura verschwindet wieder."
				,"activate"=>"defense");
				
				if ($varvar=="session")
				{
					$session['bufflist']['elem3'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['elem3'] = $buff;
				}
				break;
				
				
			case 5:
				$buff = array("name"=>$info['color']."Windklingen"
				,"rounds"=>10
				,"minioncount"=>round(($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level'])/2)+2
				,"maxbadguydamage"=>round(($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level'])/2)+2
				,"startmsg"=>$info['color']."Du konzentrierst dich und formst die Luft um dich herum zu unzähligen, messerschafen Klingen, die du {badguy}".$info['color']." entgegenschleuderst.`n`n"
				,"roundmsg"=>"Die Windklingen wirbeln auf {badguy}`) zu."
				,"wearoff"=>"Die Windklingen verschwinden wieder."
				,"effectmsg"=>"Eine Windklinge trifft {badguy}`) mit `^{damage}`) Schadenspunkten."
				,"effectnodmgmsg"=>"Eine Windklinge versucht, {badguy}`) zu treffen, `$ TRIFFT ABER NICHT!`)"
				,"activate"=>"offense");
				
				if ($varvar=="session")
				{
					$session['bufflist']['elem5'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['elem5'] = $buff;
				}
				break;
			}
			if ($varvar=="session")
			{
				$session['user']['specialtyuses']['elementaluses']-=$_GET['l'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses']['elementaluses']-=$_GET['l'];
			}
		}
		else
		{
            $buff = array("startmsg"=>"`nDu ruft nacht der Kraft der Elemente... doch alles bleibt still.`n`n",
			"rounds"=>1,
			"activate"=>"roundstart"
			);
			if ($varvar=="session")
			{
				$session['bufflist']['elem0'] = $buff;
				$session['user']['reputation']--;
			}
			else
			{
				$GLOBALS[$varvar]['bufflist']['elem0'] = $buff;
			}
		}
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		
		break;
		
	case 'academy_desc':
		output($info['color'].'Selbststudium in der Bibliothek 
		`$'.$cost_low .'`^ Gold`n
		'.$info['color'].'Praktische Übung in der Magiekammer 
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n'
		.$info['color'].' Privatunterricht bei Warchild 
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;
		
		
	case 'academy_pratice':
		output($info['color'].'Du betrittst noch leicht torkelnd den Übungsraum und stellst dich in eine Ecke, um die Elementarkräfte zu beschwören. Für einen Augenblick sieht es auch so aus, als würde das Feuer im Kamin deinem Wort gehorchen, doch so besoffen, wie du bist, kannst du dich nicht lange genug konzentrieren. Mit angesengten Kleidern und Haaren ergreifst du die Flucht, vielleicht solltest du es nüchtern noch einmal versuchen.`n`n
`		5Du verlierst ein paar Lebenspunkte!');
		$session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.8;
		break;
		
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_FROSTY))
		{
			$str_output=$info['color'].'`nViele mögen dieses Wetter hassen, doch dir gibt die Macht der Elemente neue Kraft. Du erhältst eine zusätzliche Anwendung!`n';
			$session['user']['specialtyuses']['elementaluses']++;
			return($str_output);
		}
		break;
	}
}

?>