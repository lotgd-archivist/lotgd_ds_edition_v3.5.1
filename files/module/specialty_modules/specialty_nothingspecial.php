<?php

$file = "specialty_nothingspecial";

function specialty_nothingspecial_info()
{
	global $info,$file;
	$info = array(
	"author"=>"Laulajatar",
	"version"=>"1.0",
	"download"=>"",
	"filename"=>$file,
	"specname"=>"Nichts Besonderes",
	"color"=>"`v",
	"category"=>"Fähigkeiten",
	"fieldname"=>"nothingspecial"
	);
}

function specialty_nothingspecial_install()
{
	global $info;
	$sql  = "INSERT INTO specialty (filename,usename,specname,category,author,active) ";
	$sql .= "VALUES ('".$info['filename']."','".$info['fieldname']."','".$info['specname']."','".$info['category']."','".$info['author']."','0')";
	db_query($sql);
}

function specialty_nothingspecial_uninstall()
{
	global $info;
	$sql  = "DELETE FROM specialty WHERE filename='".$info['filename']."'";
	db_query($sql);
}

function specialty_nothingspecial_image()
{
	return '<img border="0" src="'.IMAGE_PATH.'specialty/nothingspecial.png" />';
}

function specialty_nothingspecial_run($underfunction,$mid=0,$beginlink="forest.php?op=fight",$varvar="session")
{
	global $session,$info,$script,$cost_low,$cost_medium,$cost_high;
	specialty_nothingspecial_info();
	switch($underfunction)
	{
		case "fightnav":
			$uses = ($varvar=="session"?$session['user']['specialtyuses'][$info['fieldname'].'uses']:$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'].'uses']);
			$hotkey=($mid==$session['user']['specialty']?true:false);

			if ($uses>0)
		{
			addnav($info['color']."Nichts Besonderes`0", "");
			addnav($info['color']."&bull; Ans Schienbein treten`7 (1/".$uses.")`0"
			,$beginlink."&skill=nothingspecial&l=1"
			,true,false,false,$hotkey);
		}

		if ($uses>1)
		{
			addnav($info['color']."&bull; Erste Hilfe`7 (2/".$uses.")`0"
			,$beginlink."&skill=nothingspecial&l=2"
			,true,false,false,$hotkey);
		}

		if ($uses>2)
		{
			addnav($info['color']."&bull; Um Hilfe Rufen`7 (3/".$uses.")`0"
			,$beginlink."&skill=nothingspecial&l=3"
			,true,false,false,$hotkey);
		}

		if ($uses>3)
		{
			addnav($info['color']."&bull; Verstecken`7 (4/".$uses.")`0"
			,$beginlink."&skill=nothingspecial&l=4"
			,true,false,false,$hotkey);
		}
		break;


		case "backgroundstory":
		output("Eigentlich führst du ein ganz normales, ehrliches Leben und hast nichts außergewöhnliches gelernt. Du interessierst dich nicht für den Kampf, nicht für besonderes Wissen und auch Magie liegt dir fern. Doch damit bist du bisher ganz gut zurechtgekommen, denn du weißt, dass du auch ohne all das glücklich sein kannst.");
		break;


		case "link":
		
			return(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`nmit den anderen Kindern gespielt hast und eigentlich keine nennenswerten Interessen hattest.'
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

		if (($varvar== "session"?$session['user']['specialtyuses']['nothingspecialuses']:$GLOBALS[$varvar]['specialtyuses']['nothingspecialuses']) >= (int)$_GET['l']){
			$creaturedmg = 0;
			
			$uselevel=($varvar== "session"?$session['user']['level']:$GLOBALS[$varvar]['level']);
			
			switch((int)$_GET['l']){

				case 1:
				$buff = array("name"=>$info['color']."Ans Schienbein treten"
					,"rounds"=>1
					,"atkmod"=>2
					,"startmsg"=>$info['color'].'Du holst aus um {badguy}'.$info['color'].' so richtig feste vors Schienbein zu treten.`n`n'
					,"roundmsg"=>"Du trittst {badguy}`) so fest du kannst."
					,"activate"=>"offense");
				
				if($varvar=="session") $session['bufflist']['nothspec1'] = $buff;
				else $GLOBALS[$varvar]['bufflist']['nothspec1'] = $buff;
				break;


				case 2:
				$buff = array("name"=>$info['color']."Erste Hilfe"
					,"rounds"=>1
					,"atkmod"=>0.2
					,"defmod"=>0.2
					,"regen"=>$session['user']['level']*30
					,"startmsg"=>$info['color']."Du setzt dich auf den Boden, um deine Wunden zu versorgen, ohne weiter auf {badguy}`) zu achten.`n`n"
					,"effectmsg"=>"Du regenerierst `^{damage}`) Punkte."
					,"effectnodmgmsg"=>"Du bist bereits völlig gesund."
					,"wearoff"=>"Du stehst auf um weiterzukämpfen."
					,"activate"=>"roundstart");
				
				if($varvar=="session")
				{
					$session['bufflist']['nothspec2'] = $buff;
				}
				else $GLOBALS[$varvar]['bufflist']['nothspec2'] = $buff;
				break;


				case 3:
				$buff = array("name"=>$info['color']."Um Hilfe Rufen"
					,"rounds"=>5
					,"minioncount"=>e_rand(1,$uselevel+1)
					,"maxbadguydamage"=>round($uselevel/2,0)+4
					,"startmsg"=>$info['color']."Du holst tief Luft und rufst so laut du kannst um Hilfe.`n`n"
					,"roundmsg"=>"Dir eilen einige aufmerksame Bürger zur Hilfe."
					,"effectmsg"=>"Ein Bürger trifft {badguy}`) mit `^{damage}`) Schaden."
					,"effectnodmgmsg"=>"Ein Bürger versucht, {badguy}`) zu treffen, `\$TRIFFT ABER NICHT!"
					,"wearoff"=>"Die Bürger ziehen sich zurück."
					,"activate"=>"offense");
				
				if($varvar=="session") $session['bufflist']['nothspec3'] = $buff;
				else $GLOBALS[$varvar]['bufflist']['nothspec3'] = $buff;
				break;


				case 4:
				$buff = array("name"=>$info['color']."Verstecken"
					,"rounds"=>10
					,"badguyatkmod"=>0.5
					,"startmsg"=>$info['color']."Du nimmst die Beine in die Hand und versteckst dich vor {badguy}.`n`n"
					,"roundmsg"=>"{badguy}`) kann dich in deinem Versteck kaum erwischen."
					,"wearoff"=>"{badguy}`) hat dich gefunden."
					,"activate"=>"roundstart");
				
				if($varvar=="session") $session['bufflist']['nothspec4'] = $buff;
				else $GLOBALS[$varvar]['bufflist']['nothspec4'] = $buff;
				break;
			}
			if($varvar=="session") $session['user']['specialtyuses']['nothingspecialuses']-=$_GET['l'];
			else $GLOBALS[$varvar]['specialtyuses']['nothingspecialuses']-=$_GET['l'];
		}
		else
		{
            $buff = array(
			"startmsg"=>"`nDu stehst dumm rum und tust garnichts, während {badguy}`) auf dich zustürmt.`n`n",
			"rounds"=>1,
			"activate"=>"roundstart"
			);
			if($varvar=="session")
			{

				$session['bufflist']['nothspec0'] = $buff;
				$session['user']['reputation']--;
			}
			else $GLOBALS[$varvar]['bufflist']['nothspec0'] = $buff;
		}

		$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);

		break;

		case 'academy_desc':
		output($info['color'].'Selbststudium in der Bibliothek 
		`$'.$cost_low .'`^ Gold`n'
		.$info['color'].'Praktische Übung in der Halle 
		`$'.$cost_medium .'`^ Gold und `$1 Edelstein`^`n'
		.$info['color'].' Privatunterricht bei Warchild 
		`$'.$cost_high .'`^ Gold und `$2 Edelsteine`^`n');
		break;


		case 'academy_pratice':
		output($info['color'].'Eine gewaltige Alkoholfahne vor die hertragend betrittst du den Raum, in der Hoffnung mit den anderen Studenten etwas lernen zu können. Nachdem du jedoch ein alchemistisches Experiment ruiniert, einen Bannkreis zerstört und einen Bogen zerbrochen hast, ergreifen sie dich und setzen dich mit einem Tritt vor die Tür. Mit schmerzendem Hinterteil machst du dich davon, um ein andermal nüchtern wiederzukommen.`n`n
		`5Du verlierst ein paar Lebenspunkte!');
		$session['user']['hitpoints'] = $session['user']['hitpoints']  * 0.8;
		break;


		case 'weather':
		{
			$str_output=$info['color'].'`nDas Wetter ist dir ziemlich egal, du kannst immer tun, was du so tust. Du erhältst eine zusätzliche Anwendung!`n';
			$session['user']['specialtyuses']['nothingspecialuses']++;
			return ($str_output);
		}
		break;
	}
}

?>