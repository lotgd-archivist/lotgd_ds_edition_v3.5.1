<?php
/**
* Specialty Modul jugglery
*/

$file = "specialty_jugglery";

function specialty_jugglery_info()
{
	global $info,$file;
	$info = array("author"=>"Maris",
	"version"=>"1.0",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Gaukelei",
	"color"=>"`t",
	"category"=>"Fähigkeiten",
	"fieldname"=>"jugglery"
	);
}

function specialty_jugglery_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
	// Ab hier Optionale Datenbankeinträge
}

function specialty_jugglery_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
	// Die Installierten, Optionale Datenbankeinträge Rückgängig machen
}

function specialty_jugglery_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/jugglery.png" />';
}

function specialty_jugglery_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	specialty_jugglery_info();
	
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	
	switch ($underfunction)
	{
	case "fightnav":
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses>0)
		{
			addnav($info['color']."Gaukelei`0", "");
			addnav($info['color']."&bull; Salto`7 (1/".$uses.")`0"
			,$beginlink."&skill=jugglery&l=1"
			,true,false,false,$hotkey);
		}
		
		if ($uses>1)
		{
			addnav($info['color']."&bull; Jonglieren`7 (2/".$uses.")`0"
			,$beginlink."&skill=jugglery&l=2"
			,true,false,false,$hotkey);
		}
		
		if ($uses>2)
		{
			addnav($info['color']."&bull; Feuerspucken`7 (3/".$uses.")`0"
			,$beginlink."&skill=jugglery&l=3"
			,true,false,false,$hotkey);
		}
		
		if ($uses>4)
		{
			addnav($info['color']."&bull; Huckepack`7 (5/".$uses.")`0"
			,$beginlink."&skill=jugglery&l=5"
			,true,false,false,$hotkey);
		}
		break;
		
		
	case "backgroundstory":
		output("`6Du hast früh bemerkt, dass deine Finger ein besonderes Geschick aufweisen, und auch der Rest deines Körpers für allerlei akrobatische Meisterleistung zu gebrauchen ist.`nAuch war dir von Anfang an bewusst, dass du diese Gabe vor einem breiten Publikum gut zu Gold machen kannst!");
		break;
		
		
	case "link":
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`neine sehr gute Körperbeherrschung und sehr flinke Finger hattest, und dass es dir Freude bereitet hat, Andere zum Lachen zu bringen.'
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
		
		if (($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']) >= (int)$_GET['l'])
		{
			$creaturedmg = 0;
			switch ((int)$_GET['l'])
			{
			case 1:
				$buff = array("startmsg"=>$info['color']."`nMit einem gewaltigen Salto wirbelst du über den Kopf deines Gegners direkt hinter ihn.`n`n",
				"name"=>$info['color']."Salto",
				"rounds"=>1,
				"roundmsg"=>"Du nutzt deine Chance und verpasst {badguy}`) einen Tritt in den Allerwertesten.",
				"badguydefmod"=>0,
				"atkmod"=>2,
				"activate"=>"offense"
				);
				if ($varvar=="session")
				{
					$session['bufflist'][$info['fieldname'].'1'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist'][$info['fieldname'].'1'] = $buff;
				}
				break;
				
			case 2:
				$buff = array("startmsg"=>$info['color']."`nDu holst deine besten Holzkegel hervor und beginnst zu jonglieren.`n`n",
				"name"=>$info['color']."Jonglieren",
				"rounds"=>10,
				"wearoff"=>"Du hast alle deine Kegel verloren.",
				"minioncount"=>1,
				"maxbadguydamage"=>round(($varvar== "session"?$session['user']['level']:$GLOBALS[$varvar]['level'])*2,0)-2,
				"effectmsg"=>"`)Ein Kegel trifft {badguy}`) mit `^{damage}`) Schadenspunkten.",
				"effectnodmgmsg"=>"`)Ein Kegel saust knapp am Kopf von {badguy}`) vorbei.",
				"activate"=>"roundstart"
				);
				if ($varvar=="session")
				{
					$session['bufflist'][$info['fieldname'].'2'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist'][$info['fieldname'].'2'] = $buff;
				}
				break;
				
			case 3:
				$buff = array("startmsg"=>$info['color']."`nDu holst eine kleine Flasche \"Jesters Best Whiskey\" hervor und entzündest eine Fackel.`n`n",
				"name"=>$info['color']."Feuerspucken",
				"rounds"=>3,
				"wearoff"=>"Nun ist die Flasche leer.",
				"effectmsg"=>"Du nimmst einen guten Schluck und bläst ihn in die Fackel. Die Stichflamme macht {badguy} `^{damage}`) Schadenspunkte!",
				"minioncount"=>1,
				"maxbadguydamage"=>round(($varvar== "session"?$session['user']['attack']:$GLOBALS[$varvar]['level'])*1.5,0),
				"minbadguydamage"=>round(($varvar== "session"?$session['user']['attack']:$GLOBALS[$varvar]['level'])*1,0),
				"activate"=>"roundstart"
				);
				if ($varvar=="session")
				{
					$session['bufflist'][$info['fieldname'].'3'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist'][$info['fieldname'].'3'] = $buff;
				}
				break;
				
			case 5:
				$buff = array("startmsg"=>$info['color']."`nDu schlägst mehrere Räder und hüpfst gekonnt auf {badguy}s Rücken.`n`n",
				"name"=>$info['color']."Huckepack",
				"rounds"=>3,
				"wearoff"=>"{badguy}`) hat dich endlich abgeschüttelt.",
				"roundmsg"=>"Aus deiner sicheren Position verpasst du {badguy}`) ein paar deftige Kopfnüsse.",
				"badguyatkmod"=>0,
				"defmod"=>3,
				"activate"=>"defense"
				);
				if ($varvar=="session")
				{
					$session['bufflist'][$info['fieldname'].'5'] = $buff;
					//$session['user']['reputation']--; //Ansehensverlust deaktiviert
					// Stärkster Zauber verwendet? Nicht sehr ehrenhaft...
				}
				else
				{
					$GLOBALS[$varvar]['bufflist'][$info['fieldname'].'5'] = $buff;
				}
				break;
			}
			if ($varvar=="session")
			{
				$session['user']['specialtyuses'][$info['fieldname'].'uses']-=(int)$_GET['l'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']-=(int)$_GET['l'];
			}
		}
		else
		{
			$buff = array("startmsg"=>"`nDu singst {badguy}`) die Ballade vom geprügelten Gaukler, doch irgendwie hat das seine Motivation dich nieder zu strecken nur noch gesteigert.`n`n",
			"rounds"=>1,
			"activate"=>"roundstart"
			);
			if ($varvar=="session")
			{
				$session['bufflist'][$info['fieldname'].'0'] = $buff;
				$session['user']['reputation']--;
			}
			else
			{
				$GLOBALS[$varvar]['bufflist'][$info['fieldname'].'0'] = $buff;
			}
		}
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		
		break;
		
		
	case 'academy_desc':
		output('`3Selbststudium mit Büchern über die Kunst des Unterhaltens: 
		`$'.$cost_low .'`^ Gold`n
		`3Praktische Übung mit der Gauklertrickkiste: 
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n
		`$ Warchilds `3Lehrstunde für Nachwuchsgaukler: 
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;
		
		
	case 'academy_pratice':
		output('`^Du versuchst dich an der `7Gauklertrickkiste`^!`n
		Durch deinen Alkoholrausch ermutigt wagst du Einzigartiges... doch das einzige Einzigartige ist dein Scheitern:`nDu haust dir einen Jonglierkegel gegen den Kopf, schneidest dich dutzende Male an den Kanten von einfachen Spielkarten, das Kaninchen springt direkt aus dem Hut in dein Gesicht und beißt dir in die Nase, und zuguterletzt setzt du dich beim Feuerspucken selbst in Brand.`n
		Das war amüsant für alle Zuschauer, jedoch nicht lehrreich für dich.`n
		Halbtot und immer noch sturzbesoffen wankst du zurück ins Dorf.`n`n
		`5Du verlierst einige Lebenspunkte!');
		$session['user']['hitpoints'] = $session['user']['hitpoints'] - $session['user']['hitpoints'] * 0.1;
		break;
		
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_RAINY))
		{
			$str_output='`^`nDer Regen schlägt Allen aufs Gemüt, Zeit sie aufzumuntern mit fröhlicher Gaukelei! Du erhältst 3 Anwendungen extra!`n';
			$session['user']['specialtyuses']['juggleryuses']+=3;
			return($str_output);
		}
		break;
	}
}