<?php
/**
 * SPecialty Modul whitemagic
 */

$file = "specialty_whitemagic";

function specialty_whitemagic_info()
{
	global $info,$file;

	$info = array(
		"author"	=> "Laulajatar",
		"version"	=> "1.0",
		"download"	=> "",
		"filename"	=> $file,
		"specname"	=> "Weiße Magie",
		"color"		=> "`&",
		"category"	=> "Magie",
		"fieldname"	=> "whitemagic"
	);
}

function specialty_whitemagic_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_whitemagic_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_whitemagic_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/whitemagic.png" />';
}

function specialty_whitemagic_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	global
		 $session
		,$info
		//,$script	//wofür?!
		,$cost_low
		,$cost_medium
		,$cost_high
		;

	specialty_whitemagic_info();

	switch($underfunction)
	{
		case "fightnav":

			$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
			$hotkey=($mid==$session['user']['specialty']?true:false);

			if ($uses > 0)
			{
				addnav(
					$info['color'] . "Weiße Magie`0"
					,""
				);
				addnav(
					$info['color'] . "&bull; Heilende Hände`7 (1/" . $uses . ")`0"
					,$beginlink . "&skill=whitemagic&l=1"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 1)
			{
				addnav(
					$info['color'] . "&bull; Heiliger Blitz`7 (2/" . $uses . ")`0"
					,$beginlink . "&skill=whitemagic&l=2"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 2)
			{
				addnav(
					$info['color'] . "&bull; Widerstand`7 (3/" . $uses . ")`0"
					,$beginlink . "&skill=whitemagic&l=3"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 4)
			{
				addnav(
					$info['color'] . "&bull; Bannkreis`7 (5/" . $uses . ")`0"
					,$beginlink . "&skill=whitemagic&l=5"
					,true,false,false,$hotkey
				);
			}
		break;


		case "backgroundstory":
			output("
				Für dich stand schon immer fest, 
				dass du nur den Weg des Guten einschlagen würdest. 
				Deine Stärken liegen darin, das Böse abzuwenden und 
				die Kraft des Lichtes zu benutzen, 
				um dich und andere zu schützen.
			");
		break;


		case "link":
			return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nstets auf der guten Seite standest und nach Wegen gesucht hast, diese zu verteidigen.'
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
			if ($varvar != 'session')
			{
				$GLOBALS[$varvar]['specialtyuses'] = utf8_unserialize($GLOBALS[$varvar]['specialtyuses']);
			}
			$uses = ($varvar== "session"?$session['user']['specialtyuses']['whitemagicuses']:$GLOBALS[$varvar]['specialtyuses']['whitemagicuses']);

			if ( $uses >= (int)$_GET['l'])
			{
				$creaturedmg = 0;

				switch((int)$_GET['l']){
					case 1:
						$buff = array(
							 "name"			=> $info['color'] . "Heilende Hände"
							,"rounds"		=> 5
							,"regen"		=> $session['user']['level']
							,"startmsg"		=> $info['color'] . "Du konzentrierst dich auf deine Kräfte und spürst, wie deine Wunden sich schließen.`n`n"
							,"wearoff"		=> "Deine Konzentration lässt nach."
							,"effectmsg"	=> "Du regenerierst um `^{damage}`) Punkte."
							,"activate"		=> "roundstart"
						);

						$GLOBALS[$varvar]['bufflist']['whmag1'] = $buff;
					break;

					case 2:
						$attack = ($varvar == 'session' ? $session['user']['attack'] : $GLOBALS[$varvar]['level']);

						$buff = array(
							 "name"				=> $info['color'] . "Heiliger Blitz"
							,"rounds"			=> 1
							,"minioncount"		=> 1
							,"minbadguydamage"	=> round($attack * 1.5)
							,"maxbadguydamage"	=> round($attack * 3)
							,"startmsg"			=> $info['color'] . "Du konzentrierst dich und lässt einen hellen Blitz aus dem Himmel in deinen Gegner fahren.`n`n"
							,"effectmsg"		=> "Du triffst {badguy}`) mit `^{damage}`) Schadenspunkten."
							,"activate"			=> "roundstart"
						);

						$GLOBALS[$varvar]['bufflist']['whmag2'] = $buff;
					break;

					case 3:
						$buff = array(
							 "name"		=> $info['color'] . "Widerstand"
							,"rounds"	=> 5
							,"defmod"	=> 4
							,"startmsg"	=> $info['color'] . "Du sammelt deine Kräfte und erhöhst deine Verteidigung.`n`n"
							,"roundmsg"	=> "Deine Verteidigung ist erhöht."
							,"wearoff"	=> "Deine Verteidigung lässt wieder nach."
							,"activate"	=> "defense"
						);

						$GLOBALS[$varvar]['bufflist']['whmag3'] = $buff;
					break;

					case 5:
						$buff = array(
							 "name"			=> $info['color'] . "Bannkreis"
							,"rounds"		=> 5
							,"badguyatkmod"	=> 0
							,"badguydefmod"	=> 0
							,"startmsg"		=> $info['color'] . "Du sammelst deine Kräfte und ziehst einen mächtigen Bannkreis um {badguy}.`n`n"
							,"roundmsg"		=> "Der Bannkreis hält {badguy}`) gefangen."
							,"wearoff"		=> "Der Bannkreis verschwindet wieder."
							,"activate"		=> "roundstart"
						);
				
						$GLOBALS[$varvar]['bufflist']['whmag5'] = $buff;
					break;
				}
				if ($varvar=="session")
				{
					$session['user']['specialtyuses']['whitemagicuses'] -= $_GET['l'];
				}
				else
				{
					$GLOBALS[$varvar]['specialtyuses']['whitemagicuses'] -= $_GET['l'];
				}
			}
			else
			{
				$buff = array(
					"startmsg"	=> "`nDu konzentrierst dich auf deine Kräfte, aber nur ein kleiner Funke entspringt deinen Handflächen und versengt einen Grashalm.`n`n",
					"rounds"	=> 1,
					"activate"	=> "roundstart"
				);

				if($varvar=="session") $session['user']['reputation']--;

				$GLOBALS[$varvar]['bufflist']['whmag0'] = $buff;
			}

			if ($varvar != 'session')
			{
				$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
			}
		break;

		case 'academy_desc':
			output(
				$info['color'].'Selbststudium mit Büchern über Weiße Magie `$'.$cost_low.'`^ Gold`n
				'.$info['color'].'Praktische Übung in der Magiekammer `$'.$cost_medium.'`^ Gold und `$1 Edelstein`^`n
				'.$info['color'].' Warchilds Privatunterricht für Weiße Magier `$'.$cost_high.'`^ Gold und `$2 Edelsteine`^`n
			');
		break;

		case 'academy_pratice':
			output(
				$info['color'] . 'Du torkelst in den Raum für Magie und machst dich bereit, 
				alle Anwesenden von der Macht des Lichtes zu überzeugen! 
				Leider ist dein unverständliches Gelalle alles andere als magisch und 
				der einzige Zauber, der dir gelingt, 
				richtet sich nach einem Augenblick gegen dich selbst 
				und schleudert dich gegen die Wand.`n
				`n
				`n
				`5Du verlierst ein paar Lebenspunkte!
			');
			$session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.8;
		break;

		case 'weather':
			if (Weather::is_weather(Weather::WEATHER_SNOW))
			{
				$str_output = $info['color'] . '`nDie Welt ist in friedliches Weiß gehüllt. 
					Allein dieser Anblick reicht aus, dass du dich gestärkt fühlst. 
					Du erhältst eine zusätzliche Anwendung!`n
				';
				$session['user']['specialtyuses']['whitemagicuses']++;
				return ($str_output);
			}
		break;
	}
}

?>