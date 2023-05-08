<?php

/*
* Specialities
* to module by Eliwood, modified by Drachenserver-Team (www.atrahor.de)
* 
*/

$file = "specialty_transmutation";

function specialty_transmutation_info()
{
	global $info,$file;

	$info = array(
		 "author"		=> "Maris"
		,"version"		=> "1.0"
		,"download"		=> ""
		,"filename"		=> $file
		,"specname"		=> "Verwandlungsmagie"
		,"color"		=> "`4"
		,"category"		=> "Magie"
		,"fieldname"	=> "transmutation"
	);
}

function specialty_transmutation_install()
{
	global $info;

	$sql = "
		INSERT INTO
			`specialty`
		SET
			 `filename`		= '".$info['filename']."'
			,`usename`		= '".$info['fieldname']."'
			,`specname`		= '".$info['specname']."'
			,`category`		= '".$info['category']."'
			,`author`		= '".$info['author']."'
			,`active`		= '0'
	";
	db_query($sql);
}

function specialty_transmutation_uninstall()
{
	global $info;

	$sql = "
		DELETE FROM
			`specialty`
		WHERE
			`filename`	= '".$info['filename']."'
	";
	db_query($sql);
}

function specialty_transmutation_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/transmutation.png" />';
}

function specialty_transmutation_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	global
		$session
		,$info
		,$script
		,$cost_low
		,$cost_medium
		,$cost_high
		;

	specialty_transmutation_info();

	switch($underfunction)
	{
		case "fightnav":
			$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
			$hotkey=($mid==$session['user']['specialty']?true:false);

			if ($uses > 0)
			{
				addnav(
					 $info['color'] . "Verwandlungsmagie`0"
					,"");

				addnav(
					 $info['color'] . "&bull; Steinhaut`7 (1/" . $uses . ")`0"
					,$beginlink . "&skill=transmutation&l=1"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 1)
			{
				addnav(
					 $info['color'] . "&bull; Klingenarme`7 (2/" . $uses . ")`0"
					,$beginlink . "&skill=transmutation&l=2"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 2)
			{
				addnav(
					 $info['color'] . "&bull; Flammenleib`7 (3/" . $uses . ")`0"
					,$beginlink . "&skill=transmutation&l=3"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 4)
			{
				addnav(
					 $info['color'] . "&bull; Kraken`7 (5/" . $uses . ")`0"
					,$beginlink . "&skill=transmutation&l=5"
					,true,false,false,$hotkey
				);
			}
		break;

		case "backgroundstory":
			output("
				`5Du erinnerst dich, dass du als Kind kaum Freunde hattest und 
				die meiste Zeit des Tages allein verbacht hast.`n
				Da du dich selbst als hässlich und unannehmbar empfunden hast, 
				fingst du an die Geheimnissde der Verwandlungsmagie zu ergründen.`n
				Nach Jahren harter Arbeit bist du nun am Ziel deiner Wünsche angelangt: 
				Du kannst deinen Körper nach deinem Willen formen!
			");
		break;


		case "link":
			return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nviele Stunden allein damit zugebracht hast dich selbst zu hassen und dir einen anderer Körper zu wünschen.'
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
			$uses = ($varvar== "session"?$session['user']['specialtyuses']['transmutationuses']:$GLOBALS[$varvar]['specialtyuses']['transmutationuses']);

			if ($uses >= (int)$_GET['l'])
			{
				$creaturedmg = 0;
				switch((int)$_GET['l'])
				{

					case 1:
						$buff = array(
							 "startmsg"		=> $info['color'] . "`nDeine Haut wird hart wie Stein und lässt alle Angriffe abprallen.`n`n"
							,"name"			=> $info['color'] . "Steinhaut"
							,"rounds"		=> 2
							,"wearoff"		=> "Deine Haut wird wieder weich und verwundbar."
							,"badguydmgmod"	=> 0
							,"badguyatkmod"	=> 0
							,"activate"		=> "defense"
						);

						$GLOBALS[$varvar]['bufflist']['transmutation1'] = $buff;
					break;

					case 2:
						$buff = array(
							 "startmsg"		=> $info['color'] . "`nDu streckst deine Arme aus und sie verwandeln sich in lange und scharfe Klingen.`n`n"
							,"name"			=> $info['color'] . "Klingenarme"
							,"roundmsg"		=> "Die Klingenarme erhöhen deinen Angriffswert!"
							,"rounds"		=> 4
							,"wearoff"		=> "Deine Arme sind nun wieder normal."
							,"atkmod"		=> 2.3
							,"activate"		=> "offense"
						);

						$GLOBALS[$varvar]['bufflist']['transmutation2'] = $buff;
					break;

					case 3:
						$buff = array(
							 "startmsg"			=> $info['color'] . "`nDein Körper entzündet sich und du stehst komplett in Flammen!`n`n"
							,"name"				=> $info['color'] . "Flammenkörper"
							,"rounds"			=> 5
							,"wearoff"			=> "Deine Flammen sind erloschen."
							,"damageshield"		=> 1.5
							,"effectmsg"		=> "{badguy}`) verbrennt sich an dir die Finger und bekommt `^{damage}`7 Schadenspunkte."
							,"effectnodmgmsg"	=> "{badguy}`) scheint von deinen Flammen unbeeindruckt zu sein!"
							,"effectfailmsg"	=> "{badguy}`) scheint von deinen Flammen unbeeindruckt zu sein!"
							,"activate"			=> "roundstart"
						);

						$GLOBALS[$varvar]['bufflist']['transmutation3'] = $buff;
					break;

					case 5:
						$buff = array(
							 "startmsg"			=> $info['color'] . "`nDir wachsen zusätzliche Arme!`n`n"
							,"name"				=> $info['color'] . "Kraken"
							,"rounds"			=> e_rand(1,5)
							,"wearoff"			=> "Deine zusätzlichen Arme verschwinden."
							,"minioncount"		=> e_rand(1,5)
							,"effectmsg"		=> "`7Du triffst triffst `^{badguy}`7 mit `^{damage}`7 Schadenspunkten."
							,"maxbadguydamage"	=> round($varvar== "session"?$session['user']['attack']*0.8:$GLOBALS[$varvar]['level']*3,0)
							,"minbadguydamage"	=> round($varvar== "session"?$session['user']['attack']*0.6:$GLOBALS[$varvar]['level']*2,0)
							,"activate"			=> "roundstart"
						);

						$GLOBALS[$varvar]['bufflist']['transmutation5'] = $buff;
					break;
				}

				if ($varvar=="session")
				{
					$session['user']['specialtyuses']['transmutationuses'] -= $_GET['l'];
				}
				else
				{
					$GLOBALS[$varvar]['specialtyuses']['transmutationuses'] -= $_GET['l'];
				}
			}
			else
			{
				$buff = array(
					 "startmsg"	=> "`nDu verwandelst dich in einen feuerspuckenden Drachen!`n(Aber nur im Traum...).`n`n"
					,"rounds"	=> 1
					,"activate"	=> "roundstart"
				);
				if($varvar=="session") $session['user']['reputation']--;
				$GLOBALS[$varvar]['bufflist']['transmutation0'] = $buff;
			}

			if ($varvar != 'session')
			{
				$GLOBALS[$varvar]['specialtyuses'] = utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
			}
		break;

		case 'academy_desc':
			output('`3Selbststudium mit dem Buch der Verwandlung: 
			`$'.$cost_low .'`^ Gold`n
			`3Praktischer Unterricht im Verwandeln: 
			`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n
			`3Eine Lehrstunde `$ Warchild `3 nehmen: 
			`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;

		case 'academy_pratice':
			output('
				`3Du konzentrierst dich so gut es dir in deinem Zustand möglich ist und 
				mit einem lauten `^*Poff*`3 verwandelst du dich in eine riesengroße Ale-Flasche.`n
				Dein Aussehen und deine deutlich bemerkbare Fahne ziehen immer mehr durstige Zuschauer an.`n
				In Panik rennst du davon, gehetzt und getrieben, 
				bis der Zauber endlich seine Wirkung verliert und 
				du wieder du selbst bist.`n
				Wenn das mal kein Erlebnis war!`n
			');
		break;

		case 'weather':
			$str_output = '`n
				`^Das Wetter ist dir ziemlich egal, 
				du bist immer motiviert deine Verwandlungsmagie zu nutzen. 
				Du bekommst eine zusätzliche Anwendung.`n
			';
			$session['user']['specialtyuses']['transmutationuses']++;
			return ($str_output);
		break;
	}
}
?>
