<?php
/**
 * Specialty Modul unarmed
 */

$file = "specialty_unarmed";

function specialty_unarmed_info()
{
	global $info,$file;

		$info = array(
			"author"	=> "Laulajatar",
			"version"	=> "1.0",
			"download"	=> "",
			"filename"	=> $file,
			"specname"	=> "Waffenloser Kampf",
			"color"		=> "`_",
			"category"	=> "Kampfkünste",
			"fieldname"	=> "unarmed"
		);
}

function specialty_unarmed_install()
{
	global $info;

	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_unarmed_uninstall()
{
	global $info;

	$sql  = "
		DELETE FROM
			`specialty`
		WHERE
			`filename`	= '".$info['filename']."'
	";
	db_query($sql);
}

function specialty_unarmed_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/unarmed.png" />';
}

function specialty_unarmed_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	global
		$session
		,$info
		,$script
		,$cost_low
		,$cost_medium
		,$cost_high
		;

	specialty_unarmed_info();

	switch($underfunction)
	{
		case "fightnav":
			$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
			$hotkey=($mid==$session['user']['specialty']?true:false);

			if ($uses > 0)
			{
				addnav(
					$info['color'] . "Waffenloser Kampf`0"
					,""
				);
				addnav(
					$info['color'] . "&bull; Kinnhaken`7 (1/" . $uses . ")`0"
					,$beginlink . "&skill=unarmed&l=1"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 1)
			{
				addnav(
					$info['color'] . "&bull; Ausweichen`7 (2/" . $uses . ")`0"
					,$beginlink . "&skill=unarmed&l=2"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 2)
			{
				addnav(
					$info['color'] . "&bull; Ansturm`7 (3/" . $uses . ")`0"
					,$beginlink . "&skill=unarmed&l=3"
					,true,false,false,false
				);
			}

			if ($uses > 4)
			{
				addnav(
					$info['color'] . "&bull; Berserkerwut`7 (5/" . $uses . ")`0"
					,$beginlink . "&skill=unarmed&l=5"
					,true,false,false,$hotkey
				);
			}
		break;

		case "backgroundstory":
			output('
				Waffen brauchst du nicht, dein Körper selbst ist deine Waffe. 
				Über Jahre hinweg hast du deinen Körper und deinen Geist trainiert, 
				deine Muskeln gestählt und deine Geschicklichkeit verbessert, 
				bis du es mit bloßen Händen mit jedem anderen Krieger aufnehmen konntest.
			');
		break;

		case "link":
			return(
				create_lnk(
					'('.$info['color'].$info['specname'].'`0)`n`ndeine Stärke trainiert und dich mit jedem geprügelt hast.'
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
				$GLOBALS[$varvar]['specialtyuses']=utf8_unserialize($GLOBALS[$varvar]['specialtyuses']);
			}
			$uses = ($varvar== "session"?$session['user']['specialtyuses']['unarmeduses']:$GLOBALS[$varvar]['specialtyuses']['unarmeduses']);

			if ($uses >= (int)$_GET['l'])
			{
				$creaturedmg = 0;

				switch((int)$_GET['l'])
				{
					case 1:
						$buff = array(
							 "name"		=> $info['color'] . "Kinnhaken"
							,"rounds"	=> 1
							,"atkmod"	=> 2
							,"startmsg"	=> $info['color'] . "Du holst weit aus, um {badguy}`) einen Kinnhaken zu verpassen.`n`n"
							,"roundmsg"	=> "Du triffst {badguy}`) so hart, dass er Sternchen sieht."
							,"activate"	=> "offense"
						);

						$GLOBALS[$varvar]['bufflist']['unarm1'] = $buff;
					break;

					case 2:
						$buff = array(
							 "name"		=> $info['color'] . "Ausweichen"
							,"rounds"	=> 5
							,"defmod"	=> 3
							,"startmsg"	=> $info['color'] . "Du konzentrierst dich darauf, den Schlägen deines Gegners auszuweichen.`n`n"
							,"roundmsg"	=> "Du bist kaum zu treffen."
							,"wearoff"	=> "Außer Atem werden deine Bewegungen wieder langsamer."
							,"activate"	=> "defense"
						);

						$GLOBALS[$varvar]['bufflist']['unarm2'] = $buff;
					break;

					case 3:
						$buff = array(
							 "name"		=> $info['color'] . "Ansturm"
							,"rounds"	=> 2
							,"atkmod"	=> 4
							,"startmsg"	=> $info['color'] . "Du stürmst mit aller Kraft auf {badguy}`) zu.`n`n"
							,"roundmsg"	=> "Du triffst {badguy}`) mit voller Kraft."
							,"wearoff"	=> "Du nimmst wieder deine normale Kampfhaltung ein."
							,"activate"	=> "offense"
						);

						$GLOBALS[$varvar]['bufflist']['unarm3'] = $buff;
					break;

					case 5:
						$level = ($varvar == 'session'?$session['user']['level']:$GLOBALS[$varvar]['level']);
						$buff = array(
							 "name"				=> $info['color'] . "Berserkerwut"
							,"rounds"			=> 10
							,"minioncount"		=> round($level/2)+2
							,"maxbadguydamage"	=> round($level/2)+2
							,"startmsg"			=> $info['color'] . "Mit Schaum vor dem Mund und wildem Blick stürzt du dich auf {badguy}.`n`n"
							,"roundmsg"			=> "{badguy}`) verschwindet unter einem Hagel deiner Schläge und Tritte."
							,"wearoff"			=> "Du hast dich wieder beruhigt."
							,"effectmsg"		=> "Du triffst {badguy}`) mit `^{damage}`) Schadenspunkten."
							,"effectnodmgmsg"	=> "Du versuchst, {badguy}`) zu treffen, `\$TRIFFST ABER NICHT!"
							,"activate"			=> "offense"
						);
				
						$GLOBALS[$varvar]['bufflist']['unarm5'] = $buff;
					break;
				}
				if($varvar == 'session')
				{
					$session['user']['specialtyuses']['unarmeduses'] -= $_GET['l'];
				}
				else
				{
					$GLOBALS[$varvar]['specialtyuses']['unarmeduses'] -= $_GET['l'];
				}
			}
			else
			{
				$buff = array(
					 "startmsg"	=> "`nDu schlägst nach {badguy}, doch er hält deine Faust mit seinem kleinen Finger auf.`n`n"
					,"rounds"	=> 1
					,"activate"	=> "roundstart"
				);

				if ($varvar=="session") $session['user']['reputation']--;
				$GLOBALS[$varvar]['bufflist']['unarm0'] = $buff;
			}

			if ($varvar != 'session')
			{
				$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
			}

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
		if (Weather::is_weather(Weather::WEATHER_CLOUDY_LIGHT)){
			$str_output=$info['color'].'`nDas Wetter ist nicht zu warm und nicht zu kalt, genau richtig zum Kämpfen. Du erhältst eine zusätzliche Anwendung!`n';
			$session['user']['specialtyuses']['unarmeduses']++;
			return ($str_output);
		}
		break;
	}
}

?>