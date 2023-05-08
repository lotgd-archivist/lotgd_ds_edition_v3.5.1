<?php

// 27062004

/* lake.php - an ol' temple's lake (Der Tempelsee)
* by weasel
* v2.0
* Extended by Warchild for lotgd.de
* in June 2004
* Idea by Burn
*
* Changelog
* 01.06.2004-11:32-warchild: Modifikation 1: Texte ausgedehnt, mehr Möglichkeiten
* 07.06.2004-12:16-warchild: kleinere Korrekturen
*/
output('`c`b`&Der Tempelsee`0`b`c`n');

if ($_GET['op']=='norp')
{
	$session['user']['specialinc']='';
	output('`2Dir kommt das Ganze etwas komisch vor. Du machst lieber einen großen Bogen um diese Flüssigkeit.`n');
	if (e_rand(1,4) > 3 && $session['user']['turns'] >0)
	{
		output('Deine Neugier treibt dich allerdings dazu an, diesen Ort weiter zu erkunden.
		Doch auch nachdem du in jedem Winkel gestöbert hast und selbst noch `7einen Stein `2aus der Strasse gebrochen hast, kannst du nichts von Bedeutung finden.
		Frustiert stapfst du zurück in den Wald.
		`n`n`^Die verlorene Zeit hättest du besser in einen Waldkampf gesteckt!');
		$session['user']['turns']--;
	}
	//addnav("Zurück in den Wald","forest.php");
}
else if ($_GET['op']=='trinken')
{
	// Wasserfarbe festhalten
	$colour = $_GET['water'];
	
	$session['user']['specialinc']='';
	
	$rand = e_rand(1,3);
	if ($colour=='red')
	{
		$rand += 3;
	}
	else if ($colour=='brown')
	{
		$rand += 6;
	}
	// result: blue 1-3, red 4-6, brown 7-9
	// wished results: blue 2/3 positive, red 1/3 positive, brown negative // not exactly with e_rand. red most
	
	output('`2Du nimmst einen kräftigen Schluck, und wartest ab was passiert..`n`n');
	
	switch ($rand)
	{
		// blue
	case 1:
		output('`2Außer das die Brühe kalt war hast du nichts weiter gespürt.
		`n`2Naja du fühlst dich wenigstens wieder frisch.`n`n');
		//addnav('Zurück in den Wald','forest.php');
		break;
		
	case 2:
		$session['user']['gems']++;
		output('`^Du spürst wie dein Blut pulsiert und sich das blaue Glühen auf deinen Körper überträgt.
		`n`n`^Als du deine Hand ansiehst fällt dir auf, das dort das Glühen gebündelt wird.`n
		`^Ein Edelstein hat sich in deiner Hand gebildet. Du hast jetzt insgesamt '.$session['user']['gems'].',
		`nwird es nicht Zeit, langsam einen Juwelierladen zu eröffnen?');
		//addnav('Zurück in den Wald','forest.php');
		break;
		
	case 3:
		output('`^Du fühlst wie dein Körper regeneriert.
		`n`^Jetzt wo du weißt, dass es eine Heilquelle ist und keine Gefahr davon ausgeht, entspannst du dich noch ein wenig
		`nund träumst davon den Grünen Drachen zu besiegen.');
		if ($session['user']['turns']>0)
		{
			$session['user']['turns']--;
		}
		$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
		//addnav('Zurück in den Wald','forest.php');
		break;
		
		// red
	case 4:
		// Brogads hot Chili by Burn
		output('`^Du probierst das Wasser und stellst fest, dass es genauso schmeckt wie `$Brogads `&heisse `$Chilisoße`^!
		Verdammt, von dem Zeug hättest du schon damals die Finger lassen sollen!
		`n`^Die extreme Schärfe lässt deine Augen tränen und dich unkontrolliert husten.
		Als sich die Soße ihren Weg durch deine Eingeweide wühlt, weißt du instinktiv, dass du die nächsten Stunden heulend in den Büschen verbringen wirst.`n');
		if ($session['user']['turns']<3)
		{
			$session['user']['turns'] = 0;
		}
		else
		{
			$session['user']['turns'] -= 2;
		}
		//addnav('Zurück in den Wald','forest.php');
		break;
		
	case 5:
		// Dämonenblut by Burn
		output('`^Als du das Zeug hinunterschluckst, spürst du, wie sich deine Wahrnehmung leicht verändert. Die eingestürzten Säulenteile tragen zum Teil dämonische Fratzen, die dich höhnisch anzugrinsen scheinen. Du spürst eine Veränderung in deinem Körper...`n');
		if($session['user']['specialty'] == 1) // Darkarts
		{
			output('da die `$Essenz des Bösen`^ durch deine Adern rinnt! Die Dämonen wispern dir dunkle Geheimnisse zu!`n`#');
			increment_specialty();
		}
		else
		{
			output('da etwas `$Bösartiges, Fremdes`^ durch deine Adern rinnt.
			Du bekommst einen juckenden Ausschlag, der auch nach ein paar Tagen nicht verschwinden wird.
			`nDu verlierst einen Charmepunkt!');
			$session['user']['charm']--;
			$session['user']['reputation']--;
		}
		//addnav('Zurück in den Wald','forest.php');
		break;
		
	case 6:
		output('`^Du fühlst wie dein Körper regeneriert.
		`n`^Jetzt wo du weißt, dass es eine Heilquelle ist und keine Gefahr davon ausgeht, entspannst du dich noch ein wenig.
		Dann kehrst du mit neuem Mut in den Wald zurück.
		`nDu kannst heute ein paar Monster mehr erschlagen!`n');
		$session['user']['turns']++;
		$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
		//addnav('Zurück in den Wald','forest.php');
		break;
		
		// brown
	case 7:
		output('`^Die Brühe ist einfach ekelhaft.
		`n`^Du übergibst dich spontan und kehrst dem Ort dann hastig den Rücken zu.
		Glücklicherweise hatte dein unvorsichtiger Trunk keine weiteren Konsequenzen.`n');
		//addnav('Zurück in den Wald','forest.php');
		break;
		
	case 8:
		output('`^Du versuchst zu schlucken, doch irgend ein kleiner Tierknochen bleibt in deinem Hals stecken.
		`n`^Während du auf die Knie sinkst und vergeblich nach Luft schnappst, überlegst du noch, dass es eine dumme Idee war, von dem Zeug zu trinken...
		`n`n`&Du bist gestorben! Du verlierst 10% deiner Erfahrung und kannst morgen wieder spielen!');
		killplayer(0,10,0,'news.php','Tägliche News');
		addnews('`6'.$session['user']['name'].' `6erstickte qualvoll an einem Vogelknochen!');
		break;
		
	case 9:
		output('`^Der faulige Geschmack läßt dich würgen und du stolperst in die Büsche, während sich dein Magen umdreht.
		`nErstaunlicherweise findest du auf dem Rückweg `42 Edelsteine`^ als du in ein verlassenes Äffchennest trittst.`n');
		$session['user']['gems'] += 2;
		//addnav('Zurück in den Wald','forest.php');
		break;
	}
}
else if ($_GET['op']=='spiegel')
{
    /** @noinspection PhpUndefinedVariableInspection */
    output('`2Du beugst dich über den See, um dein Spiegelbild zu betrachten. Das bläuliche Wasser zeigt tatsächlich ein Bild von dir, allerdings ist es ungewöhnlich verschwommen. Als du das Bild genauer betrachtest, stellst du fest, dass es dir weit mehr zeigt, als nur dein Aussehen:
	`n`n`#Ansehen: '.grafbar(100,($session['user']['reputation']+50),'20%',10));
	if($session['user']['charm']<250)
	{
		output('`n`#Charme: '.grafbar(250,$session['user']['charm'],'20%',10));
	}
	else
	{
		$max_charm = db_fetch_assoc(db_query('SELECT acctid,charm FROM accounts WHERE sex='.$session['user']['sex'].' ORDER BY charm DESC LIMIT 1'));
		output('`n`#Charme (relativ '.($session['user']['sex']?'zur charmantesten Bürgerin ':'zum charmantesten Bürger ').getsetting('townname','Atrahor').'s): '.grafbar($max_charm['charm'],$session['user']['charm'],'20%',10).'
		');
	}
	//addnav('Zurück in den Wald','forest.php');
	$session['user']['specialinc']='';
}
else
{
	output('`2Du stehst am Rande einer alten Tempelruine.
	Einige Säulen sind zerfallen und liegen verteilt auf dem Boden. Ein seltsames graues Zwielicht herrscht über diesem Ort, als sei er zwischen Zeit und Raum eingefroren.
	`nDie fein behauenen Rundsteine, mit denen die Umgebung gepflastert sind, sind `@grasbewachsen `2und an unregelmässigen Stellen herausgebrochen, als habe jemand willkürlich Stolperstellen erzeugen wollen.
	Vorsichtig schaust du dich etwas um und entdeckst eine kleine Quelle, die aus einer Wand des Gemäuers austritt.`n`n');
	// Farbe zufällig ermitteln, Codes:
	// bläulich ++ (60%) // percentage does not fit. values in the middle appear more often with e_rand()
	// rötlich + (30%)
	// braun -- (10%)
	$watertype = e_rand(1,10);
	switch ($watertype)
	{
	case 1:
	case 2:
	case 3:
	case 4:
	case 5:
	case 6:
		$colour = 'blue';
		output('`2Dir fällt auf, dass das Wasser `#leicht bläulich `2glüht.
		Nach näherer Untersuchung kannst du nichts feststellen, außer das es eben `#bläulich glüht`2.`n');
		break;
	case 7:
	case 8:
	case 9:
		$colour = 'red';
		output('`2Dir fällt auf, dass das Wasser einen `4roten Schimmer`2 hat.
		Nach näherer Untersuchung kannst du nichts feststellen, außer das es eben einen `4roten Schimmer `2hat.`n');
		break;
	case 10:
		$colour = 'brown';
		output('`2Dir fällt auf, dass das Wasser nur eine stinkende `6braune Suppe `2ist.
		Nach näherer Untersuchung kannst du feststellen, dass in der Suppe einige tote Tiere treiben. `n');
		break;
	}
    /** @noinspection PhpUndefinedVariableInspection */
    output('Das Wasser sammelt sich in einem halbrunden Becken am Fuß der alten Mauer, doch scheint das Becken nicht überzulaufen.
	Wahrscheinlich gibt es irgendwo einen Abfluss.
	`nIn dem kleinen Becken hat sich jedoch genug angesammelt, um von '.($session['user']['sex']?'einer zufällig vorbeiziehenden Kriegerin':'einem zufällig vorbeiziehenden Krieger').' getrunken werden zu können - wenn '.($session['user']['sex']?'sie':'er').' wollte...
	Das Wasser ist glatt und spiegelt die Landschaft wider.`n');
	addnav('Ich hab Durst!','forest.php?op=trinken&water='.$colour);
	addnav('Spiegelbild','forest.php?op=spiegel');
	addnav('Ich lasse es lieber bleiben!','forest.php?op=norp');
	$session['user']['specialinc']='lake.php';
}
?>
