<?php



$file = 'specialty_thievery';

function specialty_thievery_info()
{
	global $info, $file;

	$info = array(
		 "author"		=> "Eric Stevens, to module by Eliwood"
		,"version"		=> "1.2"
		,"download"		=> ""
		,"filename"		=> $file
		,"specname"		=> "Diebeskünste"
		,"color"		=> "`^"
		,"category"		=> "Fähigkeiten"
		,"fieldname"	=> "thievery"
	);
}

function specialty_thievery_install()
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

function specialty_thievery_uninstall()
{
	global $info;
	$sql = "
		DELETE FROM
			`specialty`
		WHERE
			`filename`		= '".$info['filename']."'
	";
	db_query($sql);
}

function specialty_thievery_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/thievery.png" />';
}

function specialty_thievery_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	global
		 $session
		,$info
		,$script
		,$cost_low
		,$cost_medium
		,$cost_high
		;

	specialty_thievery_info();

	switch($underfunction)
	{

		case "fightnav":
			$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
			$hotkey=($mid==$session['user']['specialty']?true:false);

			if ($uses > 0)
			{
				addnav($info['color'] . "Diebeskünste`0","");

				addnav(
					 $info['color'] . "&bull; Beleidigen`7 (1/" . $uses . ")`0"
					,$beginlink . "&skill=thievery&l=1"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 1)
			{
				addnav(
					 $info['color'] . "&bull; Waffe vergiften`7 (2/" . $uses . ")`0"
					,$beginlink . "&skill=thievery&l=2"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 2)
			{
				addnav(
					$info['color'] . "&bull; Versteckter Angriff`7 (3/" . $uses . ")`0"
					,$beginlink . "&skill=thievery&l=3"
					,true,false,false,$hotkey
				);
			}

			if ($uses > 4)
			{
				addnav(
					$info['color'] . "&bull; Angriff von hinten`7 (5/" . $uses . ")`0"
					,$beginlink."&skill=thievery&l=5"
					,true,false,false,false
				);
			}
		break;

		case "backgroundstory":
			output("
				`6Du hast schon sehr früh bemerkt, 
				dass ein gewöhnlicher Rempler im Gedränge 
				dir das Gold eines vom Glück bevorzugteren Menschen einbringen kann. 
				Auch weisst du, 
				dass die Rücken deiner Feinde anfälliger gegenüber kleinen Waffen sind 
				als die Vorderseite gegenüber großen.
			");
		break;

		case "link":

			return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`ngelernt hast, zu stehlen und dich zu verstecken.'
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
			$uses = ($varvar=='session'?$session['user']['specialtyuses']['thieveryuses']:$GLOBALS[$varvar]['specialtyuses']['thieveryuses']);

			if ($uses >= (int)$_GET['l'])
			{
				$creaturedmg = 0;
            
				switch((int)$_GET['l'])
				{

					case 1:
						$buff = array(
							 'startmsg'		=> $info['color'] . '`nDu gibst deinem Gegner einen schlimmen Namen und bringst `7{badguy}' . $info['color'] . ' zum Weinen.`n`n'
							,'name'			=> $info['color'] . 'Beleidigung'
							,'rounds'		=> 5
							,'wearoff'		=> 'Dein Gegner putzt sich die Nase und hört auf zu weinen.'
							,'roundmsg'		=> '{badguy}`) ist deprimiert und kann nicht so gut angreifen.'
							,'badguyatkmod'	=> 0.5
							,'activate'		=> 'defense'
						);

						$GLOBALS[$varvar]['bufflist']['ts1'] = $buff;
					break;

					case 2:
						$weapon = ($varvar == 'session' ? $session['user']['weapon'] : 'Waffe');
						$buff = array(
							 'startmsg'		=> $info['color'] . '`nDu reibst Gift auf dein(e/n) ' . $weapon . '.`n`n'
							,'name'			=> $info['color'] . 'Vergiftete Waffe'
							,'rounds'		=> 5
							,'wearoff'		=> 'Das Blut deines Gegners hat das Gift von deiner Waffe gewaschen.'
							,'atkmod'		=> 2
							,'roundmsg'		=> 'Dein Angriffswert vervielfacht sich!'
							,'activate'		=> 'offense'
						);

						//if ($varvar=='session') $session['user']['reputation']--; //Ansehensverlust deaktiviert
						$GLOBALS[$varvar]['bufflist']['ts2'] = $buff;
					break;

					case 3:
						$buff = array(
							 'startmsg'		=> $info['color'] . '`nMit dem Geschick eines erfahrenen Diebes scheinst du zu verschwinden und kannst `7{badguy}'.$info['color'].' aus einer günstigeren und sichereren Position angreifen.`n`n'
							,'name'			=> $info['color'] . 'Versteckter Angriff'
							,'rounds'		=> 5
							,'wearoff'		=> 'Dein Opfer hat dich gefunden.'
							,'roundmsg'		=> '{badguy}`) kann dich nicht finden.'
							,'badguyatkmod'	=> 0
							,'activate'		=> 'defense'
						);

						$GLOBALS[$varvar]['bufflist']['ts3'] = $buff;
					break;

					case 5:
						$buff = array(
							 'startmsg'		=> $info['color'] . '`nMit deinen Fähigkeiten als Dieb verschwindest du und schiebst `7{badguy}' . $info['color'] . ' von hinten eine dünne Klinge zwischen die Rückenwirbel!`n`n'
							,'name'			=> $info['color'] . 'Angriff von hinten'
							,'rounds'		=> 5
							,'wearoff'		=> 'Dein Opfer ist nicht mehr so nett, dich hinter sich zu lassen!'
							,'atkmod'		=> 3
							,'defmod'		=> 3
							,'roundmsg'		=> 'Dein Angriffswert und deine Verteidigung vervielfachen sich!'
							,'activate'		=> 'offense,defense'
						);

						$GLOBALS[$varvar]['bufflist']['ts5'] = $buff;
					break;
				}

				if ($varvar == 'session')
				{
					$session['user']['specialtyuses']['thieveryuses'] -= $_GET['l'];
				}
				else
				{
					$GLOBALS[$varvar]['specialtyuses']['thieveryuses'] -= $_GET['l'];
				}
			}
			else
			{
				$buff = array(
					 "startmsg"		=> "`nDu versuchst, {badguy}`) anzugreifen, indem du deine besten Diebeskünste in die Praxis umsetzt - aber du stolperst über deine eigenen Füße.`n`n"
					,"rounds"		=> 1
					,"activate"		=> "roundstart"
				);

				if ($varvar == 'session') $session['user']['reputation']--;
				$GLOBALS[$varvar]['bufflist']['ts0'] = $buff;
				
			}

			if ($varvar != 'session')
			{
				$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
			}
		break;

		case "academy_desc":
			output("`3Selbststudium mit Büchern über das stille Handwerk: 
			`$".$cost_low ."`^ Gold`n
			`3Praktische Übung im Diebeslabyrinth: 
			`$".$cost_medium ."`^ Gold und `$1 Edelstein`^`n
			`$ Warchilds `3Lehrstunde für Nachwuchsdiebe: 
			`$".$cost_high ."`^ Gold und `$2 Edelsteine`^`n");
		break;

		case 'academy_pratice':
			output('
				`^Du betrittst das `7Labyrinth der Fallen`^!`n
				Während du, immer langsam an der Wand lang wegen des Alkohols, dich in Richtung des Eingangs bewegst (oh Mann du bist betrunken!), kann Warchild ein grausames Lächeln nicht unterdrücken.`n
				Um es kurz zu machen: Du wirst dreimal von einer vergifteten Nadel gestochen, schneidest dich zweimal an einem versteckten Draht und einmal übersiehst Du die grosse Falltür, durch die man direkt in den Müllkübel fällt, der vor der Akademie steht.`n
				Halbtot sammelst du die Reste von dir wieder zusammen und wankst zurück ins Dorf.`n`n
				`5Du verlierst viele Lebenspunkte!
			');
			$session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.1;
		break;

		case 'weather':
			if (Weather::is_weather(Weather::WEATHER_FOGGY))
			{
				$str_output = '`^`nDer Nebel bietet Dieben einen zusätzlichen Vorteil. Du bekommst eine zusätzliche Anwendung.`n';
				$session['user']['specialtyuses']['thieveryuses']++;
				return ($str_output);
			}
		break;
	}
}
?>