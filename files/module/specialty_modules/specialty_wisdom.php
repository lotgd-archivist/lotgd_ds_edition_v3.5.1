<?php
/**
* Specialty Modul wisdom
*/

$file = 'specialty_wisdom';

function specialty_wisdom_info()
{
	global $info,$file;
	$info = array('author'=>'für Tauschquest',
	'version'=>'1.0',
	'download'=>'',
	'filename'=>$file,
	'specname'=>'Weisheit',
	'color'=>'`g',
	'category'=>'Fähigkeiten',
	'fieldname'=>'wisdom'
	);
}

function specialty_wisdom_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_wisdom_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_wisdom_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/wisdom.png" />';
}

function specialty_wisdom_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	specialty_wisdom_info();
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	switch ($underfunction)
	{
	case 'fightnav':
		$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
		$hotkey=($mid==$session['user']['specialty']?true:false);
		
		if ($uses>0)
		{
			addnav($info['color'].'Weisheit`0', '');
			addnav($info['color']."&bull; Relativität`7 (1/".$uses.")`0",
			$beginlink."&skill=wisdom&l=1"
			,true,false,false,$hotkey);
		}
		if ($uses>1)
		{
			addnav($info['color']."&bull; Meditation`7 (2/".$uses.")`0",
			$beginlink."&skill=wisdom&l=2"
			,true,false,false,$hotkey);
		}
		if ($uses>2)
		{
			addnav($info['color']."&bull; Erinnerung`7 (3/".$uses.")`0",
			$beginlink."&skill=wisdom&l=3"
			,true,false,false,$hotkey);
		}
		if ($uses>4)
		{
			addnav($info['color']."&bull; Runen-Orakel`7 (5/".$uses.")`0",
			$beginlink."&skill=wisdom&l=5"
			,true,false,false,$hotkey);
		}
		break;
		
		
	case 'backgroundstory':
		output('`&Du hast schon früh gelernt, dein Wissen weise zu nutzen und bewiesen, dass dein Wille stark genug ist, um jedes Rätsel zu lösen, aus welchem Grund es dir auch gelungen ist, deine Weisheit im Kampf gegen den Feind zu gebrauchen. Mit all dem Wissen, welches du in deinem bisherigen Leben gesammelt hast streifst du nun durch die Lande, deinem Feind stets einen Zug vorrausdenkend.');
		break;
		
		
	case 'link':
		
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nimmer sehr wissensdurstig warst und das Wissen zu deinem Vorteil einsetzen konntest.'
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
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_unserialize($GLOBALS[$varvar]['specialtyuses']);
		
		if (($varvar== "session"?$session['user']['specialtyuses']['wisdomuses']:$GLOBALS[$varvar]['specialtyuses']['wisdomuses']) >= (int)$_GET['l'])
		{
			$creaturedmg = 0;
			
			switch ((int)$_GET['l'])
			{
				
			case 1:
				$buff = array('startmsg'=>$info['color'].'`nDu erklärst deinem Gegner die Relativitätstheorie!`n`n',
				'name'=>$info['color'].'Relativität',
				'rounds'=>5,
				'wearoff'=>'Deinem Gegner ist die Sache relativ egal.',
				'badguyatkmod'=>0.75,
				'badguydefmod'=>0.75,
				'roundmsg'=>'{badguy}`) grübelt über die Relativitätstheorie und kann sich nicht auf den Kampf konzentrieren.',
				'activate'=>'offense,defense');
				if ($varvar=='session')
				{
					$session['bufflist']['hs2'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['hs2'] = $buff;
				}
				break;
				
				
			case 2:
				$buff = array('startmsg'=>$info['color'].'`nDu schließt für einen Moment die Augen und gehst in dich. Du erholst dich ein wenig.`n`n',
				'name'=>$info['color'].'Meditation',
				'rounds'=>1,
				'regen'=>($varvar== 'session'?$session['user']['level']*20:$GLOBALS[$varvar]['level']*1.5),
				'effectmsg'=>'Du heilst um {damage} Punkte.',
				'effectnodmgmsg'=>'Du bist völlig gesund.',
				'activate'=>'roundstart'
				);
				if ($varvar=='session')
				{
					$session['bufflist']['hs1'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['hs1'] = $buff;
				}
				break;
				
				
			case 3:
				$buff = array('startmsg'=>$info['color'].'`nDu erinnerst dich an vielerlei Verteidigungskünste, die du früher gelernt hast. Dein Verteidigungswert vervielfacht sich.`n`n',
				'name'=>$info['color'].'Erinnerung',
				'rounds'=>5,
				'wearoff'=>'Dein Gegner hat dich durchschaut.',
				'roundmsg'=>'Du weichst deinem Gegner gekonnt aus und kassierst weniger Schadenspunkte.',
				'defmod'=>4,
				'activate'=>'defense'
				);
				if ($varvar=='session')
				{
					$session['bufflist']['hs3'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['hs3'] = $buff;
				}
				break;
				
				
			case 5:
				$buff = array('startmsg'=>$info['color'].'`nDu wirfst deinem Gegner einige Runensteine vor die Füße und erklärst ihm, dass die Steine sagen, du würdest ihn in wenigen Augenblicken dem Erdboden gleich machen.`n`n',
				'name'=>$info['color'].'Runen-Orakel',
				'rounds'=>6,
				'wearoff'=>'{badguy}`) glaubt den Runen nicht länger.',
				'badguyatkmod'=>0,
				'badguydefmod'=>0,
				'roundmsg'=>'{badguy}`) hat sehr weiche Knie und traut sich nicht, dich anzugreifen.',
				'activate'=>'offense,defense'
				
				);
				if ($varvar=='session')
				{
					$session['bufflist']['hs5'] = $buff;
				}
				else
				{
					$GLOBALS[$varvar]['bufflist']['hs5'] = $buff;
				}
				break;
			}
			if ($varvar=="session")
			{
				$session['user']['specialtyuses']['wisdomuses']-=$_GET['l'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses']['wisdomuses']-=$_GET['l'];
			}
		}
		else
		{
            $buff = array('startmsg'=>'`nDu versuchst, noch schnell einen Löffel Weisheit zu fressen, doch {badguy}`) lacht dich nur aus.`n`n',
			'rounds'=>1,
			'activate'=>'roundstart'
			);
			if ($varvar=='session')
			{
				$session['bufflist']['hs0'] = $buff;
				$session['user']['reputation']--;
			}
			else
			{
				$GLOBALS[$varvar]['bufflist']['hs0'] = $buff;
			}
		}
		
		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
		
		break;
		
	case 'academy_desc':
		output('`&Selbststudium mit wissenschaftlichen Büchern:
		`$'.$cost_low .'`^ Gold
		`n`&Praktische Übung im Labor:
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^
		`n`$ Warchilds `&Präsentation für erfolgreiche Gelehrte:
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^
		`n');
		break;
		
		
	case 'academy_pratice':
		output('`^Mit wichtiger Mine betrittst du den `7Saal`^!`n
		Du torkelst unbeholfen über die Bühne und hast dank des Alkohols deine Sinne kaum mehr unter Kontrolle.`n
		Unfähig, noch etwas lesen zu können erwischst du prompt eine falsche Zutat. Mit einem Knall löst sich deine Präsentation in Rauch auf.`n
		Was für ein Glück, dass dir niemand zusieht, denn deine Alkoholfahne hat den letzten Zuschauer schon lange vertrieben.`n
		Schwarz im Gesicht humpelst du gedemütigt nach draussen.`n`n
		`5Du verlierst ein paar Lebenspunkte!');
		$session['user']['hitpoints'] *= 0.8;
		break;
		
		
	case 'weather':
		if (Weather::is_weather(Weather::WEATHER_WINDY))
		{
			$str_output='`^`nDu hoffst auf freundlicheres Wetter und wälzt erstmal ein paar Bücher um dein Wissen aufzufrischen. Du bekommst eine zusätzliche Anwendung.`n';
			$session['user']['specialtyuses']['wisdomuses']++;
			return($str_output);
		}
		break;
	}
}
?>