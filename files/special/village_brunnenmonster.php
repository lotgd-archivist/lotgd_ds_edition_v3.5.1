<?php
require_once "common.php";
checkday();
page_header("Stadtzentrum");

if ($_GET['op']=='')
{
	// Wenn neblig und Abendgrauen / Morgendämmerung
	$time = gametime();
	$hour = (int)date('H',$time);

	if (Weather::is_weather(Weather::WEATHER_FOGGY) &&
	( ($hour < 10 && $hour > 4) || ($hour < 22 && $hour > 16) ))
	{
		$session['user']['specialinc'] = basename(__FILE__);
		output("`@Wie du auf dem Stadtplatz stehst, hörst du plötzlich ein lautes Gurgeln und ein dumpfes Dröhnen aus dem Schacht des Dorfbrunnens. Nahezu unheimlich dringen die Geräusche an dein Ohr und du bekommst eine Gänsehaut.`nAuch bemerkst du, dass es mit einem Mal neblig und kalt zu werden scheint.`nWas wirst du tun ?`0");

		insertcommentary(1,'/msg `7Der Brunnen gibt plötzlich seltsame gurgelnde und schabende Geräusche von sich. Leichter Nebel liegt in der Luft.`0','village');

		addnav("Dich dem Brunnen nähern","village.php?op=closer");
		addnav("Sicheren Abstand nehmen","village.php?op=flee");

	}
	else
	{
		// Keine Bedingungen für ein ordentliches Brunnenmonster
		redirect('village.php');
	}

}
else if ($_GET['op']=="flee")
{
	insertcommentary($session['user']['acctid'],': weicht erschreckt und verunsichert zurück und hält einen deutlichen Abstand zum  Brunnen.`0','village');

	$session['user']['specialinc'] = '';
	redirect('village.php');
}
else if ($_GET['op']=="closer")
{
	insertcommentary($session['user']['acctid'],': nähert sich neugierig dem Brunnen.`0','village');

	output("`@Nahezu bedrohlich wirkt dieser verfluchte Brunnen auf dich, doch hat er auch eine gewisse Anziehungskraft. Du glaubst zu hören wie etwas versucht den Brunnenschacht hinauf zu klettern.`nDeine Neugier ringt mit deiner Angst.`n");

	addnav("Über den Brunnenrand schauen","village.php?op=edge");
	addnav("Dich entfernen","village.php?op=away");

	if(item_count('tpl_id="klfale" OR tpl_id="strgale" OR tpl_id="strgalehlf" OR tpl_id="concale" AND i.owner='.$session['user']['acctid'])>0)
	{
		output(' Aber da du eh gerade den Schalk im Nacken sitzen hast könntest Du ja mal schauen ob man der Situation nicht noch eine gewisse Komik abgewinnen könnte');
		addnav('Schabernack');
		addnav('Das Brunnenmonster ärgern','village.php?op=stress_well_monster');
	}
	output('Was tust du?`0');
}
else if ($_GET['op']=='stress_well_monster')
{
	$str_output = '';
	if(isset($_GET['item']))
	{
		$str_output = '`1Du erinnerst Dich mit Freuden zurück:`n`&Wie war das doch gleich? Monster + Bier = ...`n`n`1 Na warte...';
		if(item_count('tpl_id="klfale" AND i.owner='.$session['user']['acctid'])>0)
		{
			addnav('Kleines Alefässchen','village.php?op=stress_well_monster&item=klfale');
		}
		if(item_count('tpl_id="strgale" AND i.owner='.$session['user']['acctid'])>0)
		{
			addnav('Starkbier','village.php?op=stress_well_monster&item=strgale');
		}
		if(item_count('tpl_id="strgalehlf" AND i.owner='.$session['user']['acctid'])>0)
		{
			addnav('Starkbier halbvoll', 'village.php?op=stress_well_monster&item=strgalehlf');
		}
		if(item_count('tpl_id="concale" AND i.owner='.$session['user']['acctid'])>0)
		{
			addnav('Starkbierkonzentrat','village.php?op=stress_well_monster&item=concale');
		}
	}
	else
	{
		switch ($_GET['item'])
		{
			case 'klfale':
				$str_output .= 'Du schnappst dir ein kleines Alefass und lässt es ganz beiläufig den Schacht hinunterpoltern';
				$int_start = 0;
			break;
			case 'strgale':
				$int_start = 40;
			break;
			case 'strgalehlf':
				$int_start = 20;
			break;
			case 'concale':
				$int_start = 60;
				$str_output .= 'Mit einem hinterhältigen Grinsen stellst du das Fass mit dem Starkbierkonzentrat neben dir auf den Brunnenrand.
				 Du schaust Dich noch einmal um ob auch niemand hinsieht, aber bei dem Nebel der immer rund um den Brunnen herrscht sollte das nicht der Fall sein.`n
				 PLUMPS. Mit ';
			break;
		}
		switch (e_rand($int_start, 100))
		{
			case 0:
			case 100:
		}
	}
	output($str_output);
}
else if ($_GET['op']=="away")
{
	$ops= e_rand(1,3);
	switch ($ops)
	{
		case 1 :
		case 2 :
			//output("`@Du ziehst es vor, doch lieber in Sicherheit zu bleiben.`0");
			insertcommentary($session['user']['acctid'],': hat es sich wohl doch anders überlegt und entfernt sich rasch vom Brunnen.`0','village');

			$session['user']['specialinc'] = '';
			redirect("village.php");
			break;
		case 3 :

			insertcommentary(1,'/msg `7Plötzlich schnellt ein langer, dürrer, tentakelähnlicher Strang aus dem Brunnen hervor und schlingt sich um den Hals von `&'.$session['user']['name'].'`7, um '.($session['user']['sex']?"sie ":"ihn ").' in den Brunnen zu ziehen!`0','village');

			output("`@Du drehst dich um, bereit dich wieder zu entfernen, als plötzlich ein langes, schlankes Tentakel aus dem Brunnen herausschiesst und sich um deinen Hals schlingt.`nDu bekommst kaum Luft und bist wie gelähmt!`nWas nun ?`0");
			addnav("Um Hilfe rufen","village.php?op=helpme");
			addnav("Auf den Strang einschlagen","village.php?op=hack");
			break;
	}
}
else if ($_GET['op']=="helpme")
{

	insertcommentary($session['user']['acctid'],': ruft verzweifelt mit Armen und Beinen strampend und im Würgegriff des Stranges : "Hilfe! So helft mir doch!".`0','village');

	$ops= e_rand(1,2);
	switch ($ops)
	{
		case 1 :
			output("`@Durch das beherzte Eingreifen einiger Helden in deiner Nähe wirst du gerettet und der Strang zieht sich in den Brunnen zurück.`nDu hast Lebenspunkte verloren!");
			$session['user']['hitpoints']*=0.7;

			insertcommentary(1,'/msg `7Die schnelle Hilfe der Helden auf dem Stadtplatz rettet `&'.$session['user']['name'].'`7 das Leben und der Strang zieht sich in den Brunnen zurück.`0','village');

			$session['user']['specialinc'] = '';
			addnav("Glück gehabt!","village.php");
			break;
		case 2 :
			output("`@Dein Schreien und Wimmern hat dir nicht geholfen. Der Strang zieht dich in den Brunnen herab, wo dich ein sehr unangenehmer Tod erwartet.`0");
			addnav("weiter","village.php?op=die");
			break;
	}
}
else if ($_GET['op']=="die")
{
	insertcommentary(1,'/msg `7Der Strang zieht `&'.$session['user']['name'].'`7 gnadenlos in den Brunnen. Kurze Zeit später sind laute krachende und knirschende Geräusche zu hören, dann wird alles still.`0','village');
	addnews("`@".$session['user']['name']."`@ wurde von etwas in den Brunnen gezogen und starb!");

	killplayer(0,0,0,'');
	$session['user']['specialinc'] = '';
	redirect ('shades.php');
}
else if ($_GET['op']=="hack")
{
	$ops= e_rand(1,3);
	switch ($ops)
	{
		case 1 :
			output("`@Es gelingt dir dich freizuschlagen und der Strang zieht sich in den Brunnen zurück.`nDu hast Lebenspunkte verloren!`0");
			$session['user']['hitpoints']*=0.8;

			insertcommentary(1,'/msg `7Der Strang lässt von `&'.$session['user']['name'].'`7 ab und zieht sich in den Brunnen zurück.`0','village');

			$session['user']['specialinc'] = '';
			addnav("Glück gehabt!","village.php");
			break;
		case 2 :
		case 3 :
			output("`@Du schlägst nach dem Strang und windest dich, allerdings erfolglos. Der Strang zieht dich in den Brunnen herab, wo dich ein sehr unangenehmer Tod erwartet.`0");
			addnav("weiter","village.php?op=die");
			break;
	}
}
else if ($_GET['op']=="edge")
{

	insertcommentary(1,'/msg `7Plötzlich schnellt ein langer, dürrer, tentakelähnlicher Strang aus dem Brunnen hervor und schlingt sich um den Hals von `&'.$session['user']['name'].'`7, um '.($session['user']['sex']?"sie ":"ihn ").' in den Brunnen zu ziehen!`0','village');

	output("`@Du beugst dich über den Rand des Brunnens um hinein zu schauen und erkennst, dass in der Tiefe ein kleines rotes Lichtlein leutet.`nDu kneifst die Augen zusammen um es besser erkennen zu können, und stellst fest, dass es sich um irgendein Symbol handelt, dass du jedoch noch nie in deinem Leben zuvor gesehen hast.`n`n`0");
	output("<IMG SRC=\"./images/symbol.jpg\" align='middle'>",true);
	output("`n`n`@Als Du dich aufrichtest um zu gehen schnellt plötzlich ein langer tentakelähnlicher Strang aus der Tiefe hinauf und schlingt sich um deinen Hals.`nWas nun?`0");
	addnav("Um Hilfe rufen","village.php?op=helpme");
	addnav("Auf den Strang einschlagen","village.php?op=hack");
}
?>