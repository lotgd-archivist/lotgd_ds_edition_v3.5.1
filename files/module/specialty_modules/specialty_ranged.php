<?php
/**
* Specialty Modul  ranged
*/

$file = "specialty_ranged";

function specialty_ranged_info()
{
	global $info,$file;
	
	$info = array("author"		=> "Laulajatar"
	,"version"		=> "1.0"
	,"download"		=> ""
	,"filename"		=> $file
	,"specname"		=> "Fernkampf"
	,"color"		=> "`7"
	,"category"		=> "Kampfkünste"
	,"fieldname"	=> "ranged"
	);
}

function specialty_ranged_install()
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

function specialty_ranged_uninstall()
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

function specialty_ranged_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/ranged.png" />';
}

function specialty_ranged_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	global
	$session
	,$info
	,$script
	,$cost_low
	,$cost_medium
	,$cost_high
	;
	
	specialty_ranged_info();
	
	switch ($underfunction)
	{
	case 'fightnav':
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses >= 1)
		{
			addnav($info['color'] . $info['specname'] . '`0', '');
			addnav($info['color'] . '&bull; Zielen`7 (1/' . $uses . ')`0'
			,$beginlink . '&skill=' . $info['fieldname'] . '&l=1'
			,true,false,false,$hotkey
			);
		}
		
		if ($uses >= 2)
		{
			addnav($info['color'] . '&bull; Deckung suchen`7 (2/' . $uses . ')`0'
			,$beginlink . '&skill=' . $info['fieldname'] . '&l=2'
			,true,false,false,$hotkey
			);
		}
		
		if ($uses >= 3)
		{
			addnav($info['color'] . '&bull; Feuerpfeil`7 (3/' . $uses . ')`0'
			,$beginlink . '&skill=' . $info['fieldname'] . '&l=3'
			,true,false,false,$hotkey
			);
		}
		
		if ($uses >= 5)
		{
			addnav($info['color'] . '&bull; Pfeilregen`7 (5/' . $uses . ')`0'
			,$beginlink . '&skill=' . $info['fieldname'] . '&l=5'
			,true,false,false,$hotkey
			);
		}
		break;
		
	case 'backgroundstory':
		output('
		Du hast früh erkannt,
		dass es Vorteile birgt aus der Ferne anzugreifen statt sich mitten ins Kampfgetümmel zu stürzen.
		Angefangen hast du mit einfachen Schleudern und Steinen,
		doch mit der Zeit bist du immer besser geworden,
		bis du schließlich auch andere Fernkampfwaffen wie Bogen und Armbrust gemeistert hast.
		');
		break;
		
	case 'link':

		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nschon bald deine erste Schleuder hattest und geübt hast die Äpfel von Nachbars Baum zu schießen.'
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
		}
		else
		{
			$GLOBALS[$varvar]['specialtyuses'] = utf8_unserialize($GLOBALS[$varvar]['specialtyuses']);
			$uses = $GLOBALS[$varvar]['specialtyuses'][$info['fieldname'] . 'uses'];
		}
		$l = (int)$_GET['l'];
		
		if ($uses >= $l)
		{
			switch ($l)
			{
			case 1:
				$buff = array("name"		=> $info['color'] . "Zielen"
				,"rounds"	=> 1
				,"atkmod"	=> 2
				,"startmsg"	=> $info['color'] . "Du zielst ganz genau, um {badguy}" . $info['color'] . " an einer Schwachstelle zu treffen.`n`n"
				,"roundmsg"	=> "Du triffst {badguy}`) mit voller Wucht."
				,"activate"	=> "offense"
				);
				
				$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '1'] = $buff;
				break;
				
			case 2:
				$buff = array("name"		=> $info['color'] . "Deckung suchen"
				,"rounds"	=> 5
				,"defmod"	=> 3
				,"startmsg"	=> $info['color'] . "Du gehst schnell in Deckung, um nicht von den gegnerischen Angriffen getroffen zu werden.`n`n"
				,"roundmsg"	=> "Du versteckst dich vor {badguy}."
				,"wearoff"	=> "{badguy}`) hat dich gefunden und du musst deine Deckung aufgeben."
				,"activate"	=> "defense"
				);
				
				$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '2'] = $buff;
				break;
				
			case 3:
				$buff = array("name"		=> $info['color'] . "Feuerpfeil"
				,"rounds"	=> 2
				,"atkmod"	=> 4
				,"startmsg"	=> $info['color'] . "Du ziehst einen besonderen Pfeil hervor und zündest ihn an.`n`n"
				,"roundmsg"	=> "Du triffst {badguy}`) mit voller Kraft."
				,"wearoff"	=> "Du hast keine Feuerpfeile mehr."
				,"activate"	=> "offense"
				);
				
				$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '3'] = $buff;
				break;
				
				
			case 5:
				$level = ($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level']);
				$buff = array("name"				=> $info['color'] . "Pfeilregen"
				,"rounds"			=> 10
				,"minioncount"		=> round($level / 2) + 2
				,"maxbadguydamage"	=> round($level / 2) + 2
				,"startmsg"			=> $info['color'] . "Du verschießt deine Pfeile so schnell, dass man sie mit bloßem Auge kaum noch erkennen kann.`n`n"
				,"roundmsg"			=> "{badguy}`) verschwindet unter einem Pfeilhagel."
				,"wearoff"			=> "Dir gehen die Pfeile aus."
				,"effectmsg"		=> "Du triffst {badguy}`) mit `^{damage}`) Schadenspunkten."
				,"effectnodmgmsg"	=> "Du versuchst, {badguy}`) zu treffen, `$ TRIFFST ABER NICHT!`)"
				,"activate"			=> "offense"
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
			$buff = array("startmsg"	=> "`nDu zielst auf {badguy}`) und schießt einen Pfeil ab, der jedoch nach einem Meter schon zu Boden fällt.`n`n"
			,"rounds"	=> 1
			,"activate"	=> "roundstart"
			);
			
			if ($varvar=="session")
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
		output($info['color'] . 'Selbststudium im Übungsraum
		`$' . $cost_low . '`^ Gold`n'
		. $info['color'] . 'Praktische Übung auf dem Kampfplatz
		`$' . $cost_medium . '`^ Gold und `$1 Edelstein`^`n'
		. $info['color'] . 'Kampftraining mit Warchild
		`$' . $cost_high . '`^ Gold und `$2 Edelsteine`^`n'
		);
		break;
		
	case 'academy_pratice':
		output($info['color'] . '
		Du torkelst auf den Platz und zückst deinen Bogen.
		Allein um den Pfeil aufzulegen brauchst du schon ein paar Minuten und
		als du ihn dann abschießt verfehlt er die Zielscheibe um Meter und
		zerschmettert stattdessen eine Blumenvase auf der Fensterbank des Hausmeisters.
		Dieser ist darüber alles andere als erfreut und
		wenige weitere Minuten später schleichst du aus der Akademie,
		den zerbrochenen Bogen um den Hals und um ein paar blaue Flecken reicher.`n`n
		`5Du verlierst ein paar Lebenspunkte!
		');
		$session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.8;
		break;
		
	case "weather":
		if (Weather::is_weather(Weather::WEATHER_CLOUDY_LIGHT))
		{
			$str_output = $info['color'] . '`nEs ist nicht zu dunkel und durch die Wolken kann dich die Sonne nicht blenden. Du erhältst eine zusätzliche Anwendung!`n';
			$session['user']['specialtyuses'][$info['fieldname'] . 'uses'] ++;
			return($str_output);
		}
		break;
	}
}

?>