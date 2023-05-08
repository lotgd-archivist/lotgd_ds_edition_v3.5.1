<?php
/*
* Zwergenjagd - Dwarf-Hunting
* written by Warchild ( warchild@gmx.org )
* 4 www.lotgd.de
* ---
* Feel free to include this special but please ask the author before doing any modifications oder publications!
* Comments, Ideas welcome (email above).
* Thank you, regards
* Warchild
* ---
* 2/2004
* Version 0.91 ger
* Letzte Änderungen/last changes: translated by kK
* 20.03.2004-00:33-warchild     Wahrscheinlichkeiten geändert. Chance auf 3 gems ist jetzt niedriger
* 26.07.2004-14:36-warchild     Debuglog in comments. Feel free to change this if you like
*
*/

if ($_GET['op']=="")
{
	output("`#Du fühlst dich schläfrig als du durch den Wald wanderst und legst dich unter einen Baum. 
	In deinem Traum siehst du ein paar Zwerge die um einen `7Riesenhaufen Edelsteine`# tanzen.
	Sie lachen und singen! Plötzlich wachst du auf. `n");

	// Player is a dwarf
    /** @noinspection PhpUndefinedVariableInspection */
    if ($session['user']['race'] == 'zwg')
	{
		output("Du fühlst dich an zuhause erinnert und dir fällt ein, dass ein Freund von dir in der Nähe wohnt.
		`n`n<a href='forest.php?op=friend'>Besuch ihn</a>`n<a href='forest.php?op=nofriend'>Weitergehen</a>",true);
		addnav("i?Besuch ihn","forest.php?op=friend");
		addnav("Weitergehen","forest.php?op=nofriend");
		addnav("","forest.php?op=friend");
		addnav("","forest.php?op=nofriend");
	}
	else
	{
		output("Vage erinnerst du dich an deinen Traum, du denkst: Wenn das stimmt, dann...!!!
		`n`n<a href='forest.php?op=dwarf'>Auf Zwergenjagd gehen!</a>
		`n<a href='forest.php?op=nodwarf'>Faulenzen</a>",true);
		addnav("Auf Zwergenjagd gehen!","forest.php?op=dwarf");
		addnav("Faulenzen","forest.php?op=nodwarf");
		addnav("","forest.php?op=dwarf");
		addnav("","forest.php?op=nodwarf");
	}
	$session['user']['specialinc']="searchdwarfs.php";
}
else
{
	$session['user']['specialinc']="";
	if ($_GET['op']=="friend")
	{
		$rand = e_rand(1,7);
		output("`n`#Du verlässt deinen Weg und gehst zur Höhle deines Freundes.
		Du klopfst an die runde Tür und ");
		switch ($rand)
		{
			case 1:
			case 2:
			case 3:
				output("`idein guter alter Kumpel begrüßt dich herzlich indem er dir am Bart zieht, als Zeichen für eure Freundschaft.`i Zusammen unterhaltet ihr euch über eure guten alten Zeiten und trinkt und esst am Kaminfeuer. Als du endlich beschliesst zu gehen fühlst du dich erholt und fit genug für weitere Gefahren!
				`n`n`^Du bekommst einen extra Waldkampf!`n");
				$session['user']['turns']++;
				break;
			case 4:
			case 5:
				output("`ifindest das Haus verlassen vor.`i Achselzuckend gehst du wieder deiner Wege.");
				break;
			case 6:
				output("niemand antwortet dir. Vorsichtig öffnest du die Tür und siehst, auf einem Tisch, eine Nachricht.
				`i`n\"An meinen Freund `7".$session['user']['name']."`#. Leider musste ich den Wald verlassen, da ich den Gestank von Menschen einfach nicht mehr ertragen kann. Da ich nicht genug Zeit hatte, mich richtig zu verabschieden, nimm bitte dieses `7kleine Präsent`# damit du dich immer an mich erinnerst.\"`i
				`n`#Als du gehst überkommt dich ein Gefühl des Stolzes, so einen guten Freund zu haben!
				`n`n`^Du erhältst 1 Edelstein!`n");
				$session['user']['gems']++;   
				break;
			case 7:
				output("`iplötzlich kommt dein Freund aus der Tür 'rausgeflogen. Er steht auf, sieht dich und kommt torkelnd auf dich zu, um dich zu umarmen.`i Er lallt: \"Innen geht die Party ab, komm rein!\"
				`nDu verbringst Stunden um Stunden mit tanzen und saufen. Irgendwann fällst du um und bleibst laut schnarchend liegen.
				`n`7Man kann dein Schnarchen sogar in der Stadt hören!
				`n`n`^Du verlierst all deine heutigen Waldkämpfe.`n");
				$session['user']['turns'] = 0;
				$session['user']['drunkenness']+=50;
				addnews("`^".$session['user']['name']." `7feierte kräftig bei der Zwergenparty und schnarchte so laut, dass niemand in der Stadt schlafen konnte!");
				break;
		}
	}

	else if ($_GET['op']=="dwarf")
	{
	  $rand = e_rand(1,15);
		output("`#Du verlässt deinen Weg und gehst in die nahen Hügel. Erstaunt findest du schon bald eine Höhle mit einer runden Tür. Voller Erwartung und Gier ziehst du deine Waffe und bereitest dich darauf vor, einen kleinen Mann umzubringen, damit du an seinen glitzernden Reichtum kommst!
		`nDu trittst die Tür ein und siehst ");
		switch ($rand)
			{
			case 1:
			case 2:
			case 3:
				output("ein kleines Zwergenmädchen, das auf dem Fussboden sitzt und `7mit einem Edelstein spielt`#! Grinsend reißt du ihr den Stein aus den Händen und lässt ein schreiendes Mädchen zurück.
				`n`n`^Du erhältst einen Edelstein!`n`n");
				$session['user']['gems']++;
				$rand2 = e_rand(1,2);
				switch ($rand2)
					{
						case 1:  
						output("`#Vom Weinen angelockt, kommt ein `7wütender Zwergenvater `# angerannt, `igreift nach seiner Axt`i und stürzt sich auf dich. Von den Schreien seiner Tochter angestachelt, hast du keine Chance gegen ihn!
						`nDu fliehst und dabei verlierst du deinen erbeuteten Edelstein wieder.
						`n`n`^Du verlierst 1 Edelstein!`n");
						if ($session['user']['gems']>0) $session['user']['gems']--;
						break;
						case 2:  output("`#Lachend gehst du von dannen, nur die Schreie des Mädchens hallen noch in deinen Ohren...
						`n`7Doch plötzlich überkommt dich Mitleid mit dem Mädchen und du versuchst vergeblich, dein Gewissen zu beruhigen.
						`n`n`^Du verlierst 1 Charmepunkt!`n");
						if ($session['user']['charm']>0) $session['user']['charm']--;
						break;
					}
					break;
			case 4:
				output("einen grimmigen Zwerg an einem Tisch sitzen, auf dem `7ein paar Edelsteine`# liegen. Als er dich bemerkt greift er nach seiner `7riesigen Doppelaxt`# und versucht seinen Schatz zu verteidigen, aber nach einem erbitterten Kampf, den natürlich du gewinnst, flieht er. Als du mit deinen blutigen Händen nach den `i Edelsteinen`i grabschst, kommt dir ein fröhliches Kichern aus der Kehle!
				`n`n`^Du erhältst 3 Edelsteine!`n");
				$session['user']['gems']+=3;
				break;
			case 5:
			case 6:
			case 7:
				output("nichts! Frustriert murmelst du:`7\"Mist, leer!\" `#Es war wohl doch nur ein schöner Traum...");
				break;
			case 8:
			case 9:
				output("`710 fürchterlich kämpferisch aussehende Zwerge`#, die bei Kaffee und Kuchen Karten spielen. Jedoch als du eintrittst, springen sie auf und umstellen dich mit ihren riesigen Kampfäxten! Sie schlagen dir vor `idich am Leben zu lassen, wenn du ihnen einen Edelstein gibst.`i Sichtlich bedrückt, aber ohne Wahl, gibst du ihnen einen und verschwindest so schnell du kannst.
				`n`n`^Du verlierst 1 Edelstein.`n");
				if ($session['user']['gems']>0) $session['user']['gems']--;
				break;
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
				$gold = e_rand($session['user']['level']*5,$session['user']['level']*15);
				output("eine hastig verlassene und unordentliche Höhle. `7Die Asche im Kamin ist noch warm! `#Du schaust dich um und suchst nach einem Tresor oder sonst einem Versteck. Tatsächlich findest du ein kleines Golddepot hinter einem verstaubten Bild von einer Zwergenoma.
				`nErfreut verlässt du die Höhle.
				`n`n`^Du erhältst ".$gold." Gold.`n");
				$session['user']['gold']+=$gold;
				break;
			case 15:
				output("eine verlassene Höhle. Aber als du unbeschwert hinein rennst um nach zurückgelassenen Schätzen zu suchen, `7fällst du über einen in Kniehöhe gespannten Draht.`# Du wirst ohnmächtig, als dein Kopf mit großem Schwung auf den Boden knallt. Als du endlich aufwachst bemerkst du, unter großen Kopfschmerzen, dass jemand `idein ganzes Gold gestohlen hat!`i
				`nFluchend schleppst du dich in den Wald zurück.
				`n`n`^Du verlierst alles Gold, was du bei Dir hattest!`n");
				$session['user']['gold'] = 0;
				break;
			}
		}
		else
		{
		  output("`n`#Du schüttelst dich und gehst zum Wald zurück, den Traum hast du schon wieder vergessen.");
		}
}
?>
