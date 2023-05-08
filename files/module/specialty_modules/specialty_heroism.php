<?php
/*/ Projekt Special-Modules

SQL:

DROP TABLE IF EXISTS `specialty`;
CREATE TABLE `specialty` (
`specid` int(5) UNSIGNED NOT null auto_increment,
`filename` varchar(50) NOT null,
`specname` varchar(50) NOT null,
`usename` varchar(50) NOT null,
`author` varchar(50) NOT null,
`activ` enum('0','1'),
PRIMARY KEY (`specid`)
) TYPE=MyISAM;

ALTER TABLE accounts ADD `specialtyuses` text;
/*/

// Modified by Maris

$file = "specialty_heroism";

function specialty_heroism_info()
{
	global $info,$file;
	$info = array(
	"author"=>"Maris",
	"version"=>"1.0",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Heldentum",
	"color"=>"`3",
	"category"=>"Kampfkünste",
	"fieldname"=>"heroism"
	);
}

function specialty_heroism_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_heroism_image()
{
	//Ja ich weiß dass das doppelt ist, das richtige Bild kommt erst demnächst
	return '<img border="0" src="'.IMAGE_PATH.'specialty/melee.png" />';
}

function specialty_heroism_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_heroism_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	specialty_heroism_info();
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	switch($underfunction)
	{
		case "fightnav":
			$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
			$hotkey=($mid==$session['user']['specialty']?true:false);

			if ($uses>0)
			{
				addnav($info['color']."Heldentum`0", "");
				addnav($info['color']."&bull; Posieren`7 (1/".$uses.")`0"
				,$beginlink."&skill=heroism&l=1"
				,true,false,false,$hotkey);
			}

			if ($uses>1)
			{
				addnav($info['color']."&bull; Tapferkeit`7 (2/".$uses.")`0"
				,$beginlink."&skill=heroism&l=2"
				,true,false,false,$hotkey);
			}

			if ($uses>2)
			{
				addnav($info['color']."&bull; Kühner Angriff`7 (3/".$uses.")`0"
				,$beginlink."&skill=heroism&l=3"
				,true,false,false,$hotkey);
			}

			if ($uses>4)
			{
				addnav($info['color']."&bull; Führungstalent`7 (5/".$uses.")`0"
				,$beginlink."&skill=heroism&l=5"
				,true,false,false,$hotkey);
			}
		break;


		case "backgroundstory":
		output("`6Du hast es schon immer geliebt, im Mittelpunkt zu stehen und warst stets für Andere da, wenn sie in Not waren.
		Auch hast du früh bemerkt, dass du eine besondere Begabung besitzt, Andere zu motivieren. Angst war dir immer ein Fremdwort.");
		break;


		case "link":
		
		return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nimmer der wackere Anführer warst, der mutig und entschlossen gegen jedes Unrecht vorging.'
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

		if (($varvar== "session"?$session['user']['specialtyuses']['heroismuses']:$GLOBALS[$varvar]['specialtyuses']['heroismuses']) >= (int)$_GET['l']){
			$creaturedmg = 0;
			
			switch((int)$_GET['l']){

				case 1:
				$buff = array(
				'startmsg'=>$info['color'].'`nDu stemmst deine Fäuse in deine Hüften und wirfst dein Haar in den Wind, was `^{badguy}'.$info['color'].' ziemlich beeindruckt.`n`n',
				'name'=>$info['color'].'Posieren',
				'rounds'=>5,
				'wearoff'=>'Dein Gegner zeigt sich nun wieder unbeeindruckt.',
				'roundmsg'=>'{badguy}`) ist von deinem Auftreten stark beeindruckt und kann sich nicht so gut wehren.',
				'badguydefmod'=>0.5,
				'activate'=>'offense'
				);
				if($varvar=='session') $session['bufflist']['hs1'] = $buff;
				else $GLOBALS[$varvar]['bufflist']['hs1'] = $buff;
				break;


				case 2:
				$buff = array(
				'startmsg'=>$info['color'].'`nDu kneifst deine Augen zusammen und gehst in Abwehrposition.`n`n',
				'name'=>$info['color'].'Tapferkeit',
				'rounds'=>5,
				'wearoff'=>'Dein Gegner weicht etwas zurück und lockt dich aus der Verteidigung.',
				'defmod'=>3,
				'roundmsg'=>'Dein Verteidigungswert steigt!',
				'activate'=>'defense'
				);
				if($varvar=='session')
				{
					$session['bufflist']['hs2'] = $buff;
				}
				else $GLOBALS[$varvar]['bufflist']['hs2'] = $buff;
				break;


				case 3:
				$buff = array(
				'startmsg'=>$info['color'].'`nDu spannst deinen Kinnmuskel an stürmst auf {badguy}`^ zu.`n`n',
				'name'=>$info['color'].'Kühner Angriff',
				'rounds'=>2,
				'wearoff'=>'Dein Angriffsschwung kam zum Erliegen.',
				'roundmsg'=>'{badguy}`) reisst ängstlich die Augen auf als du zuschlägst.',
				'atkmod'=>4,
				'activate'=>'offense'
				);
				if($varvar=='session') $session['bufflist']['hs3'] = $buff;
				else $GLOBALS[$varvar]['bufflist']['hs3'] = $buff;
				break;


				case 5:
				$buff = array(
				'startmsg'=>$info['color'].'`nDu rufst laut "`^Zu den Waffen!'.$info['color'].'" und ein wütender Mob eilt dir zur Hilfe.`n`n',
				'name'=>$info['color'].'Mob',
				'rounds'=>10,
				'wearoff'=>'Der Mob hat sich nun verstreut.',
				'minioncount'=>round(($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level'])/2)+2,
				'maxbadguydamage'=>round(($varvar== 'session'?$session['user']['level']:$GLOBALS[$varvar]['level'])/2,0)+2,
				'roundmsg'=>'Der Mob stürzt sich unter deiner Führung auf {badguy}!',
				'effectmsg'=>'`)Einer deiner Gefolgsleute trifft {badguy}`) mit `^{damage}`) Schadenspunkten.',
				'effectnodmgmsg'=>'`)Einer deiner Gefolgsleute versucht {badguy}`) zu treffen, `$TRIFFT ABER NICHT`)!',
				'activate'=>'roundstart'
				
				);
				if($varvar=='session') $session['bufflist']['hs5'] = $buff;
				else $GLOBALS[$varvar]['bufflist']['hs5'] = $buff;
				break;
			}
			if($varvar=="session") $session['user']['specialtyuses']['heroismuses']-=$_GET['l'];
			else $GLOBALS[$varvar]['specialtyuses']['heroismuses']-=$_GET['l'];
		}
		else
		{
            $buff = array(
			"startmsg"=>"`nDu versuchst, {badguy}`) mit dem schallenden Klang deiner Stimme in die Flucht zu jagen, doch leider hast du gerade einen Frosch im Hals.`n`n",
			"rounds"=>1,
			"activate"=>"roundstart"
			);
			if($varvar=="session")
			{
				$session['bufflist']['hs0'] = $buff;
				$session['user']['reputation']--;
			}
			else $GLOBALS[$varvar]['bufflist']['hs0'] = $buff;
		}

		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);

		break;

		case "academy_desc":
		output("`3Selbststudium mit Büchern über die Taten großer Leute: 
		`$".$cost_low ."`^ Gold`n
		`3Praktische Übung auf der Bühne: 
		`$".$cost_medium ."`^ Gold und `$1 Edelstein`^`n
		`$ Warchilds `3Lehrstunde für kleine und große Helden: 
		`$".$cost_high ."`^ Gold und `$2 Edelsteine`^`n");
		break;


		case "academy_pratice":
		output('`^Du betrittst die große `7Showbühne`^!`n
		Du torkelst unbeholfen über die Bühne und hast dank des Alkohols deine Sinne kaum mehr unter Kontrolle.`n
		Mehrere Male fällst du der Länge nach hin, und bei angeberischen Paradeübungen rammst du dir den Säbel selbst ins Bein.`n
		Was für ein Glück, dass dir niemand zusieht, denn deine Alkoholfahne hat den letzten Zuschauer schon lange vertrieben.`n
		Du humpelst gedemütigt nach draussen.`n`n
		`5Du verlierst ein paar Lebenspunkte!');
		$session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.8;
		break;


		case "weather":
		if (Weather::is_weather(Weather::WEATHER_WARM))
		{
			$str_output='`^`nDer Sonnenschein lässt dich heute noch imposanter erscheinen. Du bekommst eine zusätzliche Anwendung.`n';
			$session['user']['specialtyuses']['heroismuses']++;
			return ($str_output);
		}
		break;
	}
}
?>
