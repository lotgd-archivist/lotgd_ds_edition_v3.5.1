<?php
/**
* Specialty Modul healing
*/

$file = "specialty_healing";

function specialty_healing_info()
{
	global $info,$file;
	$info = array("author"=>"Laulajatar",
	"version"=>"1.0",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Heilkünste",
	"color"=>"`f",
	"category"=>"Fähigkeiten",
	"fieldname"=>"healing"
	);
}

function specialty_healing_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_healing_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_healing_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/healing.png" />';
}

function specialty_healing_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	specialty_healing_info();
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	switch ($underfunction)
	{
	case "fightnav":
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses>0)
		{
			addnav($info['color']."Heilkünste`0", "");
			addnav($info['color']."&bull; Wundsalbe`7 (1/".$uses.")`0"
			,$beginlink."&skill=healing&l=1"
			,true,false,false,$hotkey);
		}
		
		if ($uses>1)
		{
			addnav($info['color']."&bull; Chloroform`7 (2/".$uses.")`0"
			,$beginlink."&skill=healing&l=2"
			,true,false,false,$hotkey);
		}
		
		if ($uses>2)
		{
			addnav($info['color']."&bull; Kräutertrank`7 (3/".$uses.")`0"
			,$beginlink."&skill=healing&l=3"
			,true,false,false,$hotkey);
		}
		
		if ($uses>4)
		{
			addnav($info['color']."&bull; Wunderheilung`7 (".$uses."/".$uses.")`0"
			,$beginlink."&skill=healing&l=5"
			,true,false,false,$hotkey);
		}
		break;
		
		
	case "backgroundstory":
		output("Schon früh hast du dich dafür entschieden, deine Fähigkeiten zum Wohl anderer einzusetzen. Du hast lange Zeit Wissen über die verschiedensten Heilmethoden und –Pflanzen gesammelt und weißt fast jede Krankheit und Verletzung zu behandeln. Egal ob mit Kräutern, Tränken, Verbänden oder magischen Kräften, du tust alles dafür, denen zu helfen, die deine Hilfe benötigen.");
		break;
		
		
	case "link":
		
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`njedes Tier gesundpflegen wolltest, dass du verletzt gefunden hast.'
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
		
		if (($varvar== "session"?$session['user']['specialtyuses']['healinguses']:$GLOBALS[$varvar]['specialtyuses']['healinguses']) >= (int)$_GET['l'])
		{
			$creaturedmg = 0;
			
			switch ((int)$_GET['l'])
			{
				
			case 1:
				$buff = array("name"=>$info['color']."Wundsalbe",
				"rounds"=>5,
				"regen"=>$session['user']['level'],
				"startmsg"=>$info['color']."`nDu packst eine kleine Dose Salbe aus und versorgst deine Wunden.`n`n",
				"wearoff"=>"Die Salbe ist alle.",
				"effectmsg"=>"Du schmierst dir Salbe auf deine Wunden und heilst um `^{damage} `)Punkte.",
				"effectnodmgmsg"=>"Du bist bereits völlig gesund.",
				"activate"=>"roundstart"
				);
				
				if ($varvar=="session")
				{
					$session['bufflist']['heal1'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['heal1'] = $buff;
				}
				break;
				
				
			case 2:
				$buff = array("name"=>$info['color']."Chloroform",
				"rounds"=>5,
				"badguyatkmod"=>0.5,
				"badguydefmod"=>0.5,
				"startmsg"=>$info['color']."`nDu holst ein kleines Fläschchen heraus und versuchst, {badguy}`) zu betäuben.`n`n",
				"roundmsg"=>"{badguy}`) ist ganz benommen und kann nicht richtig angreifen oder sich verteidigen.",
				"wearoff"=>"{badguy}`) kommt wieder zu sich.",
				"activate"=>"roundstart"
				);
				
				if ($varvar=="session")
				{
					$session['bufflist']['heal2'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['heal2'] = $buff;
				}
				break;
				
				
			case 3:
				$buff = array("name"=>$info['color']."Kräutertrank",
				"rounds"=>2,
				"atkmod"=>4,
				"startmsg"=>$info['color']."`nDu holst eine kleine Flasche hervor, in der sich dein eigens hergestellter Kräutertrank befindet. Beherzt nimmst du einen großen Schluck.`n`n",
				"roundmsg"=>"Von deinem Kräutertrank gestärkt schlägst du auf `^{badguy}`) ein.",
				"wearoff"=>"Die Wirkung des Tranks ist verflogen.",
				"activate"=>"offense"
				);
				
				if ($varvar=="session")
				{
					$session['bufflist']['heal3'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['heal3'] = $buff;
				}
				break;
				
				
			case 5:
				$buff = array("name"=>$info['color']."Wunderheilung",
				"rounds"=>1,
				"regen"=>$session['user']['maxhitpoints']-$session['user']['hitpoints'],
				"startmsg"=>$info['color']."`nDu konzentrierst dich auf deine Heilkräfte.`n`n",
				"effectmsg"=>"Du regenerierst vollständig.",
				"effectnodmgmsg"=>"Du bist bereits völlig gesund.",
				"activate"=>"roundstart");
				$_GET['l']=($varvar=="session"?$session['user']['specialtyuses']['healinguses']:$GLOBALS[$varvar]['specialtyuses']['healinguses']);
				if ($varvar=="session")
				{
					$session['bufflist']['heal5'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['heal5'] = $buff;
				}
				break;
			}
			if ($varvar=="session")
			{
				$session['user']['specialtyuses']['healinguses']-=$_GET['l'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses']['healinguses']-=$_GET['l'];
			}
		}
		else
		{
            $buff = array("startmsg"=>"`nDu versuchst dich zu heilen, doch es gelingt dir nichtmal, einen kleinen blauen Fleck verschwinden zu lassen.`n`n",
			"rounds"=>1,
			"activate"=>"roundstart"
			);
			if ($varvar=="session")
			{
				$session['bufflist']['heal0'] = $buff;
				$session['user']['reputation']--;
			}
			else
			{
				$GLOBALS[$varvar]['bufflist']['heal0'] = $buff;
			}
		}
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		
		break;
		
	case 'academy_desc':
		output('`fSelbststudium in der Bibliothek 
		`$'.$cost_low .'`^ Gold`n
		`fPraktische Übung im Lazarett 
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n
		`f Privatstunde bei Warchild 
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;
		
		
	case 'academy_pratice':
		output('`fDu näherst dich dem Lazarett, um dich in der Heilkunst zu üben. Doch die Alkoholfahne, die dir vorausschwebt, ruft eine Krankenschwester herbei, die damit nicht einverstanden ist. Als du trotzdem versuchst, zum Bett eines Kranken vorzudringen zeigt die Schwester dir, dass sie sich nicht nur aufs Heilen versteht. Mit blutender Nase machst du dich auf den Heimweg, um deinen Rausch auszuschlafen.`n`n
`5Du verlierst ein paar Lebenspunkte!');
		$session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.8;
		break;
		
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_WARM))
		{
			$str_output='`f`nEs ist so ein wundervolles Wetter, dass manche Verletzungen von ganz alleine zu verschwinden scheinen. Du erhältst eine zusätzliche Anwendung!`n';
			$session['user']['specialtyuses']['healinguses']++;
			return($str_output);
		}
		break;
	}
}

?>