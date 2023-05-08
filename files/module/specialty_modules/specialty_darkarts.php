<?php
/**
* Specialty Modul Darkarts
*/

$file = "specialty_darkarts";

function specialty_darkarts_info()
{
	global $info,$file;
	$info = array("author"=>"Eric Stevens, to module by Eliwood",
	"version"=>"1.0",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Dunkle Künste",
	"color"=>"`$",
	"category"=>"Magie",
	"fieldname"=>"darkart"
	);
}

function specialty_darkarts_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_darkarts_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_darkarts_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/darkarts.png" />';
}

function specialty_darkarts_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	specialty_darkarts_info();
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	switch ($underfunction)
	{
	case "fightnav":
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses>0)
		{
			addnav($info['color']."Dunkle Künste`0", "");
			addnav($info['color']."&bull; Skelette herbeirufen`7 (1/".$uses.")`0",
			$beginlink."&skill=darkarts&l=1"
			,true,false,false,$hotkey);
		}
		
		if ($uses>1)
		{
			addnav($info['color']."&bull; Voodoo`7 (2/".$uses.")`0",
			$beginlink."&skill=darkarts&l=2"
			,true,false,false,$hotkey);
		}
		
		if ($uses>2)
		{
			addnav($info['color']."&bull; Geist verfluchen`7 (3/".$uses.")`0",
			$beginlink."&skill=darkarts&l=3"
			,true,false,false,$hotkey);
		}
		
		if ($uses>4)
		{
			addnav($info['color']."&bull; Seele verdorren`7 (5/".$uses.")`0",
			$beginlink."&skill=darkarts&l=5"
			,true,false,false,$hotkey);
		}
		break;
		
		
	case "backgroundstory":
		output("`5Du erinnerst dich, dass du damit aufgewachsen bist, viele kleine Waldkreaturen zu töten, weil du davon überzeugt warst, sie haben sich gegen dich verschworen.
		Deine Eltern haben dir einen idiotischen Zweig gekauft, weil sie besorgt darüber waren, dass du die Kreaturen des Waldes mit bloßen Händen töten musst.
		Noch vor deinem Teenageralter hast du damit begonnen, finstere Rituale mit und an den Kreaturen durchzuführen, wobei du am Ende oft tagelang im Wald verschwunden bist.
		Niemand außer dir wusste damals wirklich, was die Ursache für die seltsamen Geräusche aus dem Wald war...");
		break;
		
		
	case "link":
		
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nviele Kreaturen des Waldes getötet hast.'
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
		
		if (($varvar== "session"?$session['user']['specialtyuses']['darkartuses']:$GLOBALS[$varvar]['specialtyuses']['darkartuses']) >= (int)$_GET['l'])
		{
			$creaturedmg = 0;
			
			switch ((int)$_GET['l'])
			{
				
			case 1:
				$buff = array('startmsg'=>$info['color'].'`nDu rufst die Geister der Toten und skelettartige Hände zerren an `^{badguy}'.$info['color'].' aus den Tiefen ihrer Gräber.`n`n',
				'name'=>$info['color'].'Skelettdiener',
				'rounds'=>5,
				'wearoff'=>'Deine Skelettdiener zerbröckeln zu Staub.',
				'minioncount'=>round(($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level'])/3)+1,
				'maxbadguydamage'=>round(($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level'])/2,0)+1,
				'effectmsg'=>'`)Ein untoter Diener trifft {badguy}`) mit `^{damage}`) Schadenspunkten.',
				'effectnodmgmsg'=>'`)Ein untoter Diener versucht {badguy}`) zu treffen, aber `$TRIFFT NICHT`)!',
				'activate'=>'roundstart'
				);
				if ($varvar=="session")
				{
					$session['bufflist']['da1'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['da1'] = $buff;
				}
				break;
				
			case 2:
				$buff = array('startmsg'=>$info['color'].'`nDu holst eine winzige Puppe, die aussieht wie {badguy}'.$info['color'].', hervor.`n`n',
				'effectmsg'=>'Du stößt eine Nadel in die {badguy}`)-Puppe und machst damit `^{damage}`) Schadenspunkte!',
				'minioncount'=>1,
				'maxbadguydamage'=>round(($varvar== 'session'?$session['user']['attack']:$GLOBALS[$varvar]['level'])*3,0),
				'minbadguydamage'=>round(($varvar== 'session'?$session['user']['attack']:$GLOBALS[$varvar]['level'])*1.5,0),
				'activate'=>'roundstart'
				);
				if ($varvar=="session")
				{
					$session['bufflist']['da2'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['da2'] = $buff;
				}
				break;
				
			case 3:
				$buff = array('startmsg'=>$info['color'].'`nDu sprichst einen Fluch auf die Ahnen von {badguy}.`n`n',
				'name'=>$info['color'].'Geist verfluchen',
				'rounds'=>5,
				'wearoff'=>'Dein Fluch ist gewichen.',
				'badguydmgmod'=>0.5,
				'roundmsg'=>'{badguy}`) taumelt unter der Gewalt deines Fluchs und macht nur halben Schaden.',
				'activate'=>'defense'
				);
				if ($varvar=='session')
				{
					$session['bufflist']['da3'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['da3'] = $buff;
				}
				break;
				
			case 5:
				$buff = array('startmsg'=>$info['color'].'`nDu streckst deine Hand aus und `^{badguy}'.$info['color'].' fängt an aus den Ohren zu bluten.`n`n',
				'name'=>$info['color'].'Seele verdorren',
				'rounds'=>5,
				'wearoff'=>'Die Seele deines Opfers hat sich erholt.',
				'badguyatkmod'=>0,
				'badguydefmod'=>0,
				'roundmsg'=>'{badguy}`) kratzt sich beim Versuch, die eigene Seele zu befreien, fast die Augen aus und kann nicht angreifen oder sich verteidigen.',
				'activate'=>'offense,defense'
				);
				if ($varvar=='session')
				{
					//$session['user']['reputation']--; //Ansehensverlust deaktiviert
					$session['bufflist']['da5'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['da5'] = $buff;
				}
				break;
			}
			if ($varvar=="session")
			{
				$session['user']['specialtyuses']['darkartuses']-=(int)$_GET['l'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses']['darkartuses']-=$_GET['l'];
			}
		}
		else
		{
			$buff = array('startmsg'=>'`nErschöpft versuchst du deine dunkelste Magie: einen schlechten Witz.  {badguy}`) schaut dich nachdenklich eine Minute lang an. Endlich versteht er den Witz und stürzt sich lachend wieder auf dich.`n`n',
			'rounds'=>1,
			'activate'=>'roundstart'
			);
			if ($varvar=='session')
			{
				$session['user']['reputation']--;
				$session['bufflist']['da0'] = $buff;
			}
			else
			{
				$GLOBALS[$varvar]['bufflist']['da0'] = $buff;
			}
		}
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		
		break;
		
	case 'academy_desc':
		output('`3Selbststudium der Dunklen Künste: 
		`$'.$cost_low .'`^ Gold`n
		`3Praktischer Unterricht im Tiere quälen: 
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n
		`3Eine Lehrstunde beim Meister der dunklen Künste, `$ Warchild `3selbst, nehmen: 
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;
		
		
	case 'academy_pratice':
		output('`^Du betrittst den `7Tierkäfig`^!`n
		Ein niedlich aussehendes, weißes Kaninchen sitzt in der Mitte des Käfigs und glotzt dich an. Du holst zum Schlag aus, doch auf einmal springt es auf dich zu und `$ gräbt seine Zähne in deine Hand!`^ Glücklicherweise bist du noch zu betrunken um den Schmerz zu fühlen...`n
		aber dafür wird deine Hand morgen höllisch weh tun!`n
		Mit einer bandagierten Hand verlässt Du den Ort.`n`n
		`5Du verlierst ein paar Lebenspunkte!');
		$session['user']['hitpoints'] = $session['user']['hitpoints'] - $session['user']['hitpoints'] * 0.2;
		break;
		
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_RAINY))
		{
			$str_output='`^`nDer Regen schlägt dir aufs Gemüt, aber erweitert deine Dunklen Künste. Du bekommst eine zusätzliche Anwendung.`n';
			$session['user']['specialtyuses']['darkartuses']++;
			return($str_output);
		}
		break;
	}
}
?>