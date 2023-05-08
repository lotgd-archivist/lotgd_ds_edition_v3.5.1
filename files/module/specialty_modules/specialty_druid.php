<?php
/**
* Specialty Modul druid
*/

$file = "specialty_druid";

function specialty_druid_info()
{
	global $info,$file;
	$info = array("author"=>"Maris",
	"version"=>"0.9",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Druidenzauber",
	"color"=>"`@",
	"category"=>"Magie",
	"fieldname"=>"druid"
	);
}

function specialty_druid_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_druid_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_druid_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/druid.png" />';
}

function specialty_druid_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	specialty_druid_info();
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	switch ($underfunction)
	{
	case 'fightnav':
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses>0)
		{
			addnav($info['color'].'Druidenzauber`0', '');
			addnav($info['color'].'&bull; Ranken`7 (1/'.$uses.')`0'
			,$beginlink.'&skill=druid&l=1'
			,true,false,false,$hotkey);
		}
		
		if ($uses>1)
		{
			addnav($info['color'].'&bull; Wunden heilen`7 (2/'.$uses.')`0'
			,$beginlink.'&skill=druid&l=2'
			,true,false,false,$hotkey);
		}
		
		if ($uses>2)
		{
			addnav($info['color'].'&bull; Krähenruf`7 (3/'.$uses.')`0'
			,$beginlink.'&skill=druid&l=3'
			,true,false,false,$hotkey);
		}
		
		if ($uses>4)
		{
			addnav($info['color'].'&bull; Druidenrache`7 ('.$uses.'/'.$uses.')`0'
			,$beginlink.'&skill=druid&l=5'
			,true,false,false,false);
		}
		break;
		
		
	case 'backgroundstory':
		output('`3Du hast schon als Kind die Natur geliebt und teilweise sehr heftig überreagiert, wenn du gesehen hast wie jemand ihr Schaden zufügte.`nDeshalb hattest du auch nur wenige Freunde und hast deine Zeit lieber im Wald verbracht, wo du von einem alten und sehr weisen Mann die Geheimnisse der Naturmagie gelehrt bekamst.`nNach seinem Tod hast du den Wald verlassen, doch dein Herz blieb immer dort.`n');
		break;
		
		
	case 'link':
		
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`ndie Kräfte der Natur für dich zu nutzen gelernt hast.'
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
		
		if (($varvar== "session"?$session['user']['specialtyuses']['druiduses']:$GLOBALS[$varvar]['specialtyuses']['druiduses']) >= (int)$_GET['l'])
		{
			$creaturedmg = 0;
			switch ((int)$_GET['l'])
			{
			case 1:
				$buff = array('startmsg'=>$info['color'].'`nDu beschwörst mächtige Ranken, die deinen Gegner festhalten!`n`n',
				'name'=>$info['color'].'Ranken',
				'rounds'=>5,
				'wearoff'=>'Die Ranken ziehen sich zurück',
				'badguyatkmod'=>0.75,
				'badguydefmod'=>0.75,
				'roundmsg'=>'{badguy}`) wird von Ranken festgehalten und beim Angreifen und Verteidigen behindert.',
				'activate'=>'offense,defense');
				if ($varvar=='session')
				{
					$session['bufflist']['dru1'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['dru1'] = $buff;
				}
				break;
			case 2:
				$buff = array('startmsg'=>$info['color'].'`nDu legst deine Hand auf eine deiner Wunden und sie schliesst sich.`n`n',
				'name'=>$info['color'].'Wunden heilen',
				'rounds'=>1,
				'regen'=>($varvar== 'session'?$session['user']['level']*20:$GLOBALS[$varvar]['level']*1.5),
				'effectmsg'=>'Du heilst um {damage} Punkte.',
				'effectnodmgmsg'=>'Du bist völlig gesund.',
				'activate'=>'roundstart'
				);
				if ($varvar=='session')
				{
					$session['bufflist']['dru2'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['dru2'] = $buff;
				}
				break;
			case 3:
				$amount=e_rand(1,($varvar== "session"?$session['user']['level']:$GLOBALS[$varvar]['level'])+1);
				$buff = array('startmsg'=>$info['color'].'`nDeinem Ruf folgend eilen dir '.$amount.' Krähen zu Hilfe.`n`n',
				'name'=>$info['color'].'Krähenruf',
				'rounds'=>5,
				'wearoff'=>'`)Die Krähen lassen von {badguy}`) ab.',
				'minioncount'=>$amount,
				'maxbadguydamage'=>round(($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level'])/2,0)+4,
				'effectmsg'=>'`)Eine Krähe trifft {badguy}`) mit `^{damage}`) Schadenspunkten.',
				'effectnodmgmsg'=>'`)Eine Krähe sinkt im Sturzflug auf {badguy}`) zu, aber `$TRIFFT NICHT`)!',
				'activate'=>'roundstart'
				);
				if ($varvar=='session')
				{
					$session['bufflist']['dru3'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['dru3'] = $buff;
				}
				break;
			case 5:
				if ($varvar=='session')
				{
					$session['user']['hitpoints']*=0.01;
					$session['user']['hitpoints']++;
				}
				else
				{
					$GLOBALS[$varvar]['hitpoints']*=0.01;
					$GLOBALS[$varvar]['hitpoints']++;
				}
				
				$buff = array('startmsg'=>$info['color'].'`nDu holst tief Luft zu einem ohrenbetäubenden Schrei und schleuderst fast deine gesamte Lebenskraft in einem gewaltigen Blitz heraus.`n`n',
				'name'=>$info['color'].'Druidenrache',
				'rounds'=>1,
				'minioncount'=>1,
				'minbadguydamage'=>round(($varvar== 'session'?$session['user']['attack']:($GLOBALS[$varvar]['level'])+5)*3,0),
				'maxbadguydamage'=>round(($varvar== 'session'?$session['user']['attack']:($GLOBALS[$varvar]['level'])+5)*15,0)-2,
				'effectmsg'=>'{badguy}`) wird von deinem Blitz mit `^{damage}`) Schadenspunkten getroffen.',
				'effectnodmg'=>'{badguy}`) wird von deinem Blitz zurückgeschleudert, ist ansonsten aber unverletzt.',
				'effectfailmsg'=>'{badguy}`) wird von deinem Blitz zurückgeschleudert, ist ansonsten aber unverletzt.',
				'activate'=>'roundstart'
				);
				$_GET['l']=($varvar=="session"?$session['user']['specialtyuses']['druiduses']:$GLOBALS[$varvar]['specialtyuses']['druiduses']);
				if ($varvar=="session")
				{
					$session['bufflist']['dru5'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['dru5'] = $buff;
				}
				break;
			}
			if ($varvar=="session")
			{
				$session['user']['specialtyuses']['druiduses']-=(int)$_GET['l'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses']['druiduses']-=$_GET['l'];
			}
		}
		else
		{
			$buff = array("startmsg"=>"`nDu rufst alle Tiere des Waldes zu Sturm auf {badguy}`). Doch lediglich ein einzelner Regenwurm folgt deinem Ruf...`n`n",
			"rounds"=>1,
			"activate"=>"roundstart"
			);
			if ($varvar=="session")
			{
				$session['user']['reputation']--;
				$session['bufflist']['dru0'] = $buff;
			}
			else
			{
				$GLOBALS[$varvar]['bufflist']['dru0'] = $buff;
			}
		}
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		
		break;
		
	case 'academy_desc':
		output('`3Selbststudium in der Bibliothek: 
		`$'.$cost_low .'`^ Gold`n
		`3Praktische Übung im Zauberwald: 
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n
		`$ Warchilds `3Bio-Unterricht: 
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;
	case 'academy_pratice':
		output('`^Du betrittst den `7Zauberwald`^!`n
		Sturzbesoffen torkelst du durch den Wald, Tiere siehst du keine, denn die hast du ja mit deiner Alkoholfahne längst vertrieben!`n
		Statt dessen marschierst du direkt in ein tiefes Loch...`n
		Warchild hat Mitleid mit dir und zieht dich raus, danach setzt er dich vor die Tür.`n
		`5Du verlierst ein paar Lebenspunkte!');
		$session['user']['hitpoints'] = $session['user']['hitpoints'] - $session['user']['hitpoints'] * 0.3;
		break;
		
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_RAINY))
		{
			$str_output='`^`nRegen... Ohne Wasser kein Leben! Du erhältst eine Anwendung in Druidenzauber!`n';
			$session['user']['specialtyuses']['druiduses']++;
			return($str_output);
		}
		break;
	}
}
?>