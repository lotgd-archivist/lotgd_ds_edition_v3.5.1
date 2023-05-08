<?php
/**
* Altar Waldspecial
* 
* 20.7.08: Umgebaut auf alle in Atrahor verfügbaren Spezialfähigkeiten (Salator)
*/

if (!isset($session))
{
	exit();
}

//Hier für alle verfügbaren Spezialfähigkeiten (standardmäßig sind das nur die ersten 3) die Texte definieren
$arr_specialty=array(
	3 => array(
		'specname' => '`^Diebeskünste'
		,'usename' => 'thievery'
		,'introtext' => '`^einen Dolch, '
		,'linktext' => 'D?Nimm den Dolch'
		,'taketext' => 'den Dolch'
	)
	,1 => array(
		'specname' => '`$Dunkle Künste'
		,'usename' => 'darkart'
		,'introtext' => '`$einen Schädel, '
		,'linktext' => 'S?Nimm den Schädel'
		,'taketext' => 'den Schädel'
	)
	,2 => array(
		'specname' => '`%Mystische Kräfte'
		,'usename' => 'magic'
		,'introtext' => '`%einen juwelenbesetzten Stab, '
		,'linktext' => 't?Nimm den Stab'
		,'taketext' => 'den Stab'
	)
	,4 => array(
		'specname' => '`3Heldentum'
		,'usename' => 'heroism'
		,'introtext' => '`3einen Verdienstorden, '
		,'linktext' => 'O?Nimm den Orden'
		,'taketext' => 'den Orden'
	)
	,5 => array(
		'specname' => '`tGaukelei'
		,'usename' => 'jugglery'
		,'introtext' => '`tein Glöckchenband, '
		,'linktext' => 'G?Nimm das Glöckchenband'
		,'taketext' => 'das Glöckchenband'
		,'sex' => 2
	)
	,6 => array(
		'specname' => '`4Verwandlungsmagie'
		,'usename' => 'transmutation'
		,'introtext' => '`4eine formlose Masse, '
		,'linktext' => 'M?Nimm die Masse'
		,'taketext' => 'die Masse'
		,'sex' => 1
	)
	,7 => array(
		'specname' => '`@Druidenzauber'
		,'usename' => 'druid'
		,'introtext' => '`@einen Fledermausflügel, '
		,'linktext' => 'F?Nimm den Flügel'
		,'taketext' => 'den Flügel'
	)
	,8 => array(
		'specname' => '`5Heimtücke'
		,'usename' => 'cattiness'
		,'introtext' => '`5einen Schafspelz, '
		,'linktext' => 'P?Nimm den Pelz'
		,'taketext' => 'den Pelz'
	)
	,10 => array(
		'specname' => '`qElementarmagie'
		,'usename' => 'elemental'
		,'introtext' => '`qeine `#blau`&weiß`$rot`qbraune Kugel, '
		,'linktext' => 'u?Nimm die Kugel'
		,'taketext' => 'die Kugel'
		,'sex' => 1
	)
	,12 => array(
		'specname' => '`fHeilkünste'
		,'usename' => 'healing'
		,'introtext' => '`feinen Beutel Heilkräuter, '
		,'linktext' => 'H?Nimm die Heilkräuter'
		,'taketext' => 'den Heilkräuter-Beutel'
	)
	,13 => array(
		'specname' => '`7Fernkampf'
		,'usename' => 'ranged'
		,'introtext' => '`7einen elfischen Langbogen, '
		,'linktext' => 'L?Nimm den Langbogen'
		,'taketext' => 'den Langbogen'
	)
	,14 => array(
		'specname' => '`uNahkampf'
		,'usename' => 'melee'
		,'introtext' => '`uein Claymore, '
		,'linktext' => 'y?Nimm das Claymore'
		,'taketext' => 'das Claymore'
		,'sex' => 2
	)
	,15 => array(
		'specname' => '`_Waffenloser Kampf'
		,'usename' => 'unarmed'
		,'introtext' => '`_einen Sandsack, '
		,'linktext' => 'a?Untersuche den Sandsack'
		,'taketext' => 'den Sandsack'
	)
	,16 => array(
		'specname' => '`yIllusionsmagie'
		,'usename' => 'illusion'
		,'introtext' => '`yschemenhafte Nebelformen, '
		,'linktext' => 'N?Untersuche den Nebel'
		,'taketext' => 'den Nebel'
	)
	,17 => array(
		'specname' => '`&Weiße Magie'
		,'usename' => 'whitemagic'
		,'introtext' => '`&ein Räucherstäbchen, '
		,'linktext' => 'c?Nimm das Räucherstäbchen'
		,'taketext' => 'das Räucherstäbchen'
		,'sex' => 2
	)
	,11 => array(
		'specname' => '`vNichts Besonderes'
		,'usename' => 'nothingspecial'
		,'introtext' => '`veinen zerbeulten Topf, '
		,'linktext' => 'z?Nimm den zerbeulten Topf'
		,'taketext' => 'den zerbeulten Topf'
	)
);
//noch freie Hotkeys: e i j q w x

if ($_GET['op']=='spec') //specialty
{
	$session['user']['turns']--;
	output('`#Du hebst '.$arr_specialty[$_GET['what']]['taketext'].'`# von '.(words_by_sex('[seinem|ihrem|seinem] Platz auf. In einem Lichtblitz verschwindet [er|sie|es]',$arr_specialty[$_GET['what']]['sex'])).' und eine seltsame Kraft durchströmt deinen Körper!`n`n');
	
	if (e_rand(0,1)==0)
	{
		output('`n`n`&Du erhältst 10 zusätzliche Anwendungen für die Spezialfähigkeit '.$arr_specialty[$_GET['what']]['specname'].'.
		`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.');
		$session['user']['specialtyuses'][$arr_specialty[$_GET['what']]['usename'].'uses']+=10;
	}
	else
	{
		output('`&Du steigst in der Spezialfähigkeit '.$arr_specialty[$_GET['what']]['specname'].'`& 2 Level auf, was dir eine zusätzliche Anwendung einbringt!');
		$session['user']['specialtyuses'][$arr_specialty[$_GET['what']]['usename']]+=2;
		$session['user']['specialtyuses'][$arr_specialty[$_GET['what']]['usename'].'uses']++;
	}
	
	//addnav('Zurück in den Wald','forest.php');
	$session['user']['specialinc']='';
}

else if ($_GET['op']=="abacus") //Reichtum
{
	$session['user']['turns']--;
	
	if (e_rand(0,1)==0)
	{
		$gold = e_rand($session['user']['level']*30,$session['user']['level']*90);
		$gems = e_rand(1,4);
		output("`#Du nimmst das Rechenbrett von seinem Platz.  Das Rechenbrett verwandelt sich in einen Beutel voller Gold und Edelsteine!
		`n`n Du bekommst $gold Goldstücke und $gems Edelsteine!");
		$session['user']['gold']+=$gold;
		$session['user']['gems']+=$gems;
	}
	else
	{
		$gold = $session['user']['gold']+($session['user']['level']*20);
		output("`@`#Du nimmst das Rechenbrett von seinem Platz.  Das Rechenbrett verwandelt sich in einen Beutel voller Gold!
		`n`n Du bekommst $gold Goldstücke!");
		$session['user']['gold']+=$gold;
		debuglog('Erhielt '.$gold.' Gold beim Altar-Event.');
	}
	
	//addnav("Zurück in den Wald","forest.php");
	$session['user']['specialinc']="";
}

else if ($_GET['op']=="book") //Erfahrung/WK
{
	$session['user']['turns']--;
	
	if (e_rand(0,1)==0)
	{
		$exp=round($session['user']['experience']*0.15);
		output("`#Du nimmst das Buch und beginnst darin zu lesen. Das Wissen in diesem Buch hilft dir viel weiter und du legst es an seinen Platz zurück, damit ein anderer auch noch davon profitieren kann.`n`nDu bekommst $exp Erfahrungspunkte!");
		$session['user']['experience']+=$exp;
	}
	else
	{
		$ffights = e_rand(1,5);
		output("`@`#Du nimmst das Buch und beginnst darin zu lesen.  Das Buch enthält ein Geheimnis, wie du deine heutigen Streifzüge durch den Wald profitabler gestalten kannst.  Du legst das Buch an seinen Platz zurück, damit ein anderer auch noch davon profitieren kann.
		`n`nDu bekommst $ffights zusätzliche Waldkämpfe!");
		$session['user']['turns']+=$ffights;
	}
	
	//addnav("Zurück in den Wald","forest.php");
	$session['user']['specialinc']="";
}

else if ($_GET['op']=="bolt") //alles oder nichts
{
	$session['user']['turns']--;
	$bchance=e_rand(0,7);
	
	if ($bchance==0)
	{
		output("`#Du greifst nach dem Kristallblitz.  Der Blitz verschwindet aus deinen Händen und erscheint wieder auf dem Altar. Nach einigen Versuchen, den Blitz zu bekommen, hast du keine Lust mehr, noch mehr Zeit damit zu vergeuden. Du fürchtest auch, die Götter dadurch herauszufordern.");
		//addnav("Zurück in den Wald","forest.php");
	}
	else if ($bchance==1)
	{
		output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berührst, wirst du rückwärts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fühlst dich sehr mächtig!
		`n`nDu bekommst 10 Anwendungen in drei Fertigkeiten! Leider spürst du, daß diese Macht nicht einmal bis zum nächsten Morgen halten wird.");
		$spkeys=array_keys($arr_specialty);
		shuffle($spkeys);
		for ($i=0;$i<3;$i++)
		{
			$session['user']['specialtyuses'][$arr_specialty[$spkeys[$i]]['usename'].'uses']+=10;
		}
		//addnav("Zurück in den Wald","forest.php");
	}
	else if ($bchance==2)
	{
		output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berührst, wirst du rückwärts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fühlst dich sehr mächtig!
		`n`nDu steigst in drei Fertigkeiten 2 Level auf!");
		$spkeys=array_keys($arr_specialty);
		shuffle($spkeys);
		for ($i=0;$i<3;$i++)
		{
			$session['user']['specialtyuses'][$arr_specialty[$spkeys[$i]]['usename']]+=2;
			$session['user']['specialtyuses'][$arr_specialty[$spkeys[$i]]['usename'].'uses']++;
		}
		//addnav("Zurück in den Wald","forest.php");
	}
	else if ($bchance==3)
	{
		output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berührst, wirst du rückwärts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fühlst dich sehr mächtig!
		`n`nDu bekommst 5 zusätzliche Lebenspunkte!");
		$session['user']['maxhitpoints']+=5;
		$session['user']['hitpoints']+=5;
		//addnav("Zurück in den Wald","forest.php");
	}
	else if ($bchance==4)
	{
		output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berührst, wirst du rückwärts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fühlst dich sehr mächtig!
		`n`nDu bekommst 2 Angriffspunkte und 2 Verteidigungspunkte dazu!");
		$session['user']['attack']+=2;
		$session['user']['defence']+=2;
		//addnav("Zurück in den Wald","forest.php");
	}
	else if ($bchance==5)
	{
		$exp=round($session['user']['experience']*0.2);
		output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berührst, wirst du rückwärts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fühlst dich sehr mächtig!
		`n`nDu bekommst $exp Erfahrungspunkte!");
		$session['user']['experience']+=$exp;
		//addnav("Zurück in den Wald","forest.php");
	}
	else if ($bchance==6)
	{
		$exp=round($session['user']['experience']*0.2);
		output("`#Deine Hand nähert sich dem Kristallblitz, als der Himmel plötzlich vor Wolken überkocht. Du fürchtest, die Götter verärgert zu haben und beginnst zu rennen. Doch noch bevor du die Lichtung verlassen kannst, wirst du von einem Blitz getroffen.
		`n`nDu fühlst dich dümmer! Du verlierst $exp Erfahrungspunkte!");
		$session['user']['experience']-=$exp;
		//addnav("Zurück in den Wald","forest.php");
	}
	else
	{
		output("`#Deine Hand nähert sich dem Kristallblitz, als der Himmel plötzlich vor Wolken überkocht. Du fürchtest, die Götter verärgert zu haben und beginnst zu rennen. Doch noch bevor du die Lichtung verlassen kannst, wirst du von einem Blitz getroffen.
		`n`nDu bist tot!
		`nDu verlierst 5% deiner Erfahrungspunkte und all dein Gold!
		`n`nDu kannst morgen wieder spielen.");
		killplayer(100,5);
		addnav("Tägliche News","news.php");
		addnews($session['user']['name']." wurde von den Göttern niedergeschmettert, da ".($session['user']['sex']?"sie":"er")." von Gier zerfressen war!");
	}
	
	$session['user']['specialinc']="";
}

else if ($_GET['op']=="forgetit") //verlassen
{
	output("`@Du beschließt, das Schicksal lieber nicht herauszufordern und dadurch womöglich die Götter zu verärgern. Du lässt den Altar in Ruhe.
	`nAls du die Lichtung gerade verlassen willst, stolperst du über ein Beutelchen mit einem Edelstein! Die Götter müssen dir wohlgesonnen sein!");
	$session['user']['gems']++;
	//addnav("Zurück in den Wald","forest.php");
	$session['user']['specialinc']="";
}

else //if ($_GET['op']=='')
{
	output('`@Du stolperst über eine Lichtung und bemerkst einen Altar mit '.(count($arr_specialty)+2).' Seiten vor dir. Auf jeder Seite liegt ein anderer Gegenstand. Du siehst ');
	foreach($arr_specialty as $key=>$val)
	{
		addnav($val['linktext'],'forest.php?op=spec&what='.$key);
		output($val['introtext']);
	}
	output('`^ein Rechenbrett `7und ein schlicht aussehendes Buch. `@In der Mitte über dem Altar befindet sich ein `&Kristallblitz.
	`n`n`@Du weißt, dass es dich Zeit für einen ganzen Waldkampf kosten wird, einen der Gegenstände näher zu untersuchen.`n`n`n');
	addnav('R?Nimm das Rechenbrett','forest.php?op=abacus'); //Reichtum
	addnav('B?Nimm das Buch','forest.php?op=book'); //Erfahrung/WK
	addnav('K?Nimm den Kristallblitz','forest.php?op=bolt'); //full risk, full fun
	addnav('');
	addnav('Verlasse den Altar unberührt','forest.php?op=forgetit');
	$session['user']['specialinc'] = 'alter.php';
}
?>
