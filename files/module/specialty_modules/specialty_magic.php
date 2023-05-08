<?php
/**
* Specialty Modul magic
*/

$file = "specialty_magic";

function specialty_magic_info()
{
	global $info,$file;
	$info = array("author"=>"Eric Stevens, to module by Eliwood",
	"version"=>"1.2",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Mystische Kräfte",
	"color"=>"`%",
	"category"=>"Magie",
	"fieldname"=>"magic"
	);
}

function specialty_magic_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_magic_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_magic_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/magic.png" />';
}

function specialty_magic_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	
	specialty_magic_info();
	
	switch ($underfunction)
	{
	case 'fightnav':
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		
		if ($uses >= 1)
		{
			$hotkey=($mid==$session['user']['specialty']?true:false);
			addnav($info['color'] . $info['specname'] . '`0', "");
			
			addnav($info['color'] . '&bull; Regeneration`7 (1/' . $uses . ')`0'
			,$beginlink . '&skill=' . $info['fieldname'] . '&l=1'
			,true,false,false,$hotkey
			);
			
			if ($uses >= 2)
			{
				addnav($info['color'] . '&bull; Erdenfaust`7 (2/' . $uses . ')`0'
				,$beginlink . '&skill=' . $info['fieldname'] . '&l=2'
				,true,false,false,$hotkey
				);
			}
			
			if ($uses >= 3)
			{
				addnav($info['color'] . '&bull; Leben absaugen`7 (3/' . $uses . ')`0'
				,$beginlink . '&skill=' . $info['fieldname'] . '&l=3'
				,true,false,false,$hotkey
				);
			}
			
			if ($uses >= 5)
			{
				addnav($info['color'] . '&bull; Blitz Aura`7 (5/' . $uses . ')`0'
				,$beginlink . '&skill=' . $info['fieldname'] . '&l=5'
				,true,false,false,$hotkey
				);
			}
		}
		break;
		
	case 'backgroundstory':
		output('
		`3Du hast schon als Kind gewusst, dass diese Welt mehr als das Physische bietet, woran du herumspielen konntest.
		Du hast erkannt, dass du mit etwas Training deinen Geist selbst in eine Waffe verwandeln kannst.
		Mit der Zeit hast du gelernt, die Gedanken kleiner Kreaturen zu kontrollieren und ihnen deinen Willen aufzuzwingen.
		');
		break;
		
	case 'link':
		
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`ndie Kraft der Magie entdeckt hast.'
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
		
	case 'buff':
		if ($varvar == 'session')
		{
			$uses = $session['user']['specialtyuses'][$info['fieldname'] . 'uses'];
			$level = $session['user']['level'];
		}
		else
		{
			$GLOBALS[$varvar]['specialtyuses'] = utf8_unserialize($GLOBALS[$varvar]['specialtyuses']);
			$uses = $GLOBALS[$varvar]['specialtyuses'][$info['fieldname'] . 'uses'];
			$level = e_rand(10, 20);
		}
		$l = (int)$_GET['l'];
		
		if ($uses >= $l)
		{
			//$creaturedmg = 0;
			switch ($l)
			{
			case 1:
				$buff = array("startmsg"			=> $info['color'] . "`nDu fängst an zu regenerieren!`n`n"
				,"name"				=> $info['color'] . "Regeneration"
				,"rounds"			=> 5
				,"wearoff"			=> "Deine Regeneration hat aufgehört"
				,"regen"			=> $level
				,"effectmsg"		=> "Du regenerierst um {damage} Punkte."
				,"effectnodmgmsg"	=> "Du bist völlig gesund."
				,"activate"			=> "roundstart"
				);
				
				if ($varvar== "session")
				{
					//$session['user']['reputation']--; //Ansehensverlust deaktiviert
				}
				
				$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '1'] = $buff;
				break;
				
			case 2:
				$buff = array("startmsg"			=> "`n`^{badguy}".$info['color']." wird von einer Klaue aus Erde gepackt und auf den Boden geschleudert!`n`n"
				,"name"				=> $info['color'] . "Erdenfaust"
				,"rounds"			=> 5
				,"wearoff"			=> "Die erdene Faust zerfällt zu Staub."
				,"minioncount"		=> 1
				,"effectmsg"		=> "Eine gewaltige Faust aus Erde trifft {badguy}`) mit `^{damage}`) Schadenspunkten."
				,"minbadguydamage"	=> 1
				,"maxbadguydamage"	=> $level * 3
				,"activate"			=> "roundstart"
				);
				
				$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '2'] = $buff;
				break;
				
			case 3:
				$buff = array("startmsg"			=> $info['color'] . "`nDeine Waffe glüht in einem überirdischen Schein.`n`n"
				,"name"				=> $info['color'] . "Leben absaugen"
				,"rounds"			=> 5
				,"wearoff"			=> "Die Aura deiner Waffe verschwindet."
				,"lifetap"			=> 1 //ratio of damage healed to damage deal
				,"effectmsg"		=> "Du wirst um {damage} Punkte geheilt."
				,"effectnodmgmsg"	=> "Du fühlst ein Prickeln, als deine Waffe versucht, deinen vollständig gesunden Körper zu heilen."
				,"effectfailmsg"	=> "Deine Waffe scheint zu jammern, als du deinem Gegner keinen Schaden machst."
				,"activate"			=> "offense,defense"
				);
				
				$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '3'] = $buff;
				
				break;
				
			case 5:
				$buff = array("startmsg"			=> $info['color'] . "`nDeine Haut glitzert, als du dir eine Aura aus Blitzen zulegst`n`n"
				,"name"				=> $info['color'] . "Blitzaura"
				,"rounds"			=> 5
				,"wearoff"			=> "Mit einem Zischen wird deine Haut wieder normal."
				,"damageshield"		=> 2
				,"effectmsg"		=> "{badguy}`) wird von einem Blitzbogen aus deiner Haut mit `^{damage}`) Schadenspunkten zurückgeworfen."
				,"effectnodmg"		=> "{badguy}`) ist von deinen Blitzen leicht geblendet, ansonsten aber unverletzt."
				,"effectfailmsg"	=> "{badguy}`) ist von deinen Blitzen leicht geblendet, ansonsten aber unverletzt."
				,"activate"			=> "offense,defense"
				);
				
				$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '5'] = $buff;
				break;
			}
			
			if ($varvar=="session")
			{
				$session['user']['specialtyuses'][$info['fieldname'] . 'uses'] -= $l;
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'] . 'uses'] -= $l;
			}
		}
		else
		{
			$buff = array("startmsg"	=> "`nDu legst deine Stirn in Falten und beschwörst die Elemente.  Eine kleine Flamme erscheint. {badguy}`) zündet sich eine Zigarette daran an, dankt dir und stürzt sich wieder auf dich.`n`n"
			,"rounds"	=> 1
			,"activate"	=> "roundstart"
			);
			
			if ($varvar == "session")
			{
				$session['user']['reputation']--;
			}
			
			$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '0'] = $buff;
		}
		
		if ($varvar != 'session')
		{
			$GLOBALS[$varvar]['specialtyuses'] = utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		}
		break;
		
	case 'academy_desc':
		output('
		`3Selbststudium in der Bibliothek:
		`$' . $cost_low . '`^ Gold`n
		`3Praktische Übung in der Magiekammer:
		`$' . $cost_medium . '`^ Gold und `$1 Edelstein`^`n
		`$ Warchilds `3Mystikstunde:
		`$' . $cost_high . '`^ Gold und `$2 Edelsteine`^`n
		');
		break;
		
	case 'academy_pratice':
		output('
		`^Du betrittst die `7Magiekammer`^!`n
		Ein Golem marschiert auf dich zu, doch deine Sicht ist vom Alkohol noch so verschwommen, dass dein Spruch ihn verfehlt!`n
		Statt dessen trifft er dich mit einer grossen Keule und du verlierst das Bewusstsein.`n
		Nach ein paar Minuten wachst du vor der Akademie mit fiesen Kopfschmerzen wieder auf und torkelst zurück in die Stadt.`n`n
		`5Du verlierst ein paar Lebenspunkte!
		');
		$session['user']['hitpoints'] = $session['user']['hitpoints'] - $session['user']['hitpoints'] * 0.2;
		break;
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_TSTORM))
		{
			$str_output='`^`nDie Blitze fördern deine Mystischen Kräfte. Du bekommst eine zusätzliche Anwendung.`n';
			$session['user']['specialtyuses'][$info['fieldname'] . 'uses'] ++;
			return($str_output);
		}
		break;
	}
}
?>