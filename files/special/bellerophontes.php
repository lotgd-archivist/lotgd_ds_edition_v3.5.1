<?php
// Bellerophontes' Turm
//
// Bellerophontes' Turm birgt viele Überraschungen.
// Wohl dem, der es schafft, ihn zu erreichen!
// Wohl dem ... ?
//
// Erdacht und umgesetzt von Oliver Wellinghoff alias Harasim dalYkbar Drassim.
// E-Mail: wellinghoff@gmx.de
// Erstmals erschienen auf: http://www.green-dragon.info
//
//  - 25.06.2004 -
//  - Version vom 08.07.2004 -
//  - Mod by talion: Kommentare nur noch manchmal, Code bereinigt, Bugfixing

//$session['user']['specialinc'] = "bellerophontes.php";
$session['user']['specialinc']=basename(__FILE__);



switch ($_GET['op'])
{
	case '':
	{
		output("`@Vor dir liegt ein langer, gerader Waldweg, über dem die Bäume zu dicht wachsen, als dass man reiten könnte. Es ist schon seit langem nichts Aufregendes mehr passiert - da erblickst du, als du eine Kreuzung erreichst, plötzlich etwas am Ende des ausgetrampelten Pfades: einen Turm im dunstigen Zwielicht des Waldes.
		`n`nWas wirst du tun?
		`n`n".create_lnk('Weitergehen und versuchen, den Turm zu finden,','forest.php?op=weiter',true,true,'',false,'Weitergehen',1)."
		`n oder ".create_lnk('hier abbiegen und den Weg verlassen.','forest.php?op=abbiegen1',true,true,'',false,'Abbiegen',1)."`n");
		break;
	}
	
	case "abbiegen1":

	{
		output("`@Du biegst an der Kreuzung ab und verlässt den Weg.");
		$session['user']['specialinc']="";
		break;
	}
	
	case "weiter":
	{
		switch (e_rand(1,10))
		{
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:


			$turns2 = min(e_rand(1,5), $session['user']['turns']);

			$session['user']['turns']-=$turns2;
			output("`@Du folgst dem Pfad immer tiefer in den Wald hinein, stundenlang, doch der Turm bleibt fest am Horizont. Es ist, als könnte man nicht zu ihm gelangen .... Du willst schon aufgeben - als er plötzlich mit jedem weiteren Schritt einige Hundert Meter näher kommt!
			`n`n`^Bis hierher zu gelangen hat dich bereits ".$turns2." Waldkämpfe gekostet!
			`n`n`@<a href='forest.php?op=turm'>Weiter.</a>", true);
			addnav("","forest.php?op=turm");
			addnav("Weiter","forest.php?op=turm");
			break;
		case 7:
		case 8:
		case 9:
		case 10:
			if ($session['user']['turns']==1)
			{
				output("`@Du folgst dem Pfad immer tiefer in den Wald, stundenlang. Er scheint nicht enden zu wollen - und immer siehst du den Turm an seinem Ende. An der nächsten Weggabelung bleibst du stehen.
				`n`nDas war dein `^letzter`@ Waldkampf und es ist schon dunkel geworden!
				`n`nDu machst dich mit dem festen Vorsatz auf den Heimweg, morgen noch einmal zu versuchen, den Turm zu erreichen.");
				$session['user']['turns']=0;
				$session['user']['specialinc']="";
				break;
			}
			else
			{
				output("`@Du folgst dem Pfad immer tiefer in den Wald, stundenlang. Er scheint nicht enden zu wollen - und immer siehst du den Turm an seinem Ende. An der nächsten Weggabelung bleibst du stehen. Weiter nach dem Turm zu suchen wird dich möglicherweise alle deine Waldkämpfe kosten, aber du spürst, dass du `bganz dicht dran`b bist ...
				`n`n`@<a href='forest.php?op=weiter2'>Weiter.</a>
				`n`n`@<a href='forest.php?op=abbiegen2'>Abbiegen.</a>");
				addnav("","forest.php?op=weiter2");
				addnav("","forest.php?op=abbiegen2");
				addnav("Weitergehen","forest.php?op=weiter2");
				addnav("Abbiegen","forest.php?op=abbiegen2");
				break;
			}
		}
		break;
	}
	
	case "abbiegen2":
	{
		output("`@Du biegst an der Kreuzung ab und verlässt den Weg.
		`n`n`^Bis hierher zu gelangen hat dich jedoch bereits einen Waldkampf gekostet!");
		$session['user']['turns']--;
		$session['user']['specialinc']="";
		break;
	}
	
	case "weiter2":

	{
		output("`@Du gibst nicht auf und folgst dem Pfad noch tiefer in den Wald hinein. Er scheint noch immer nicht enden zu wollen, und es wird immer dunkler. Noch etwa eine Stunde und auch das letzte Licht, das sich seinen Weg durch die Bäume kämpft, wird erloschen sein - und immer siehst du den Turm vor dir, am Ende des Weges.`n`n");
		switch (e_rand(1,15))
		{
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:

			if ($session['user']['turns']>=20)
			{
				$expplus=round($session['user']['experience']*0.08);

			}
			else if ($session['user']['turns']>=13)
			{
				$expplus=round($session['user']['experience']*0.07);

			}
			else if ($session['user']['turns']>=6)
			{
				$expplus=round($session['user']['experience']*0.05);

			}
			else
			{
				$expplus=round($session['user']['experience']*0.04);
			}
			output("`@Schließlich kannst du deine Hand kaum noch vor Augen sehen - doch der Turm bleibt am Horizont, als würde es dort niemals dunkel werden. Es hilft nichts; schwer enttäuscht nimmst du die nächste Abzweigung und gelangst spät in der Nacht und völlig übermüdet zurück in die Stadt. Da du im Dunkeln nichts sehen konntest, hast du dir einige derbe Schrammen eingehandelt. Immerhin eine Erfahrung, die man nicht jeden Tag macht.`n`n
			`n`nDu bekommst `^".$expplus."`@ Erfahrungspunkte hinzu, verlierst aber alle verbliebenen Waldkämpfe!");
			$session['user']['experience']+=$expplus;
			$session['user']['hitpoints']=round($session['user']['hitpoints']*0.80);
			$session['user']['experience']+=$expplus;
			$session['user']['turns']=0;
			$session['user']['specialinc']="";
			break;
		case 6:
		case 7:
		case 8:
		case 9:
		case 10:
		case 11:
		case 12:
		case 13:
		case 14:
		case 15:
			output("`@Schließlich kannst du deine Hand kaum noch vor Augen erkennen - doch der Turm bleibt am Horizont, als würde es dort niemals dunkel werden. Du willst schon an der nächsten Abbiegung aufgeben - als der Turm beginnt, sich mit jedem weiteren Schritt um einige Hundert Meter zu nähern! Er liegt trotz der späten Stunde noch immer im Hellen ...
			`n`n`^Die Suche hat dich alle verbliebenen Waldkämpfe gekostet!
			`n`n`@<a href='forest.php?op=turm'>Weiter.</a>");
			$session['user']['turns']=0;
			addnav("","forest.php?op=turm");
			addnav("Weiter","forest.php?op=turm");
			break;
		}
		break;
	}
	
	case "turm":
	{
		output("`@Nun stehst du vor ihm, einem verwitterten, mit Efeu bewachsenen Wehrturm, der von den Überresten einer einstigen Mauer umgeben ist. Den Eingang bildet eine schwere Eichentür, die kein Zeichen der Verwitterung aufweist. An einem Pfosten ist ein weißes Pferd mit Flügeln angebunden; ein Pegasus, der friedlich grast, und an dessen Sattel ein praller Lederbeutel hängt. Schaust du nach oben, erblickst du einen Balkon.
		`n`nWas wirst du tun?
		`n`n".create_lnk('An die schwere Eichentür klopfen.','forest.php?op=klopfen',true,true,'',false,'Klopfen')."
		`n`n".create_lnk('Zum Balkon hinaufrufen.','forest.php?op=rufen',true,true,'',false,'Rufen')."
		`n`n".create_lnk('Zu dem Pegasus gehen und den Beutel stehlen.','forest.php?op=stehlen',true,true,'',false,'Stehlen')."
		`n`n".create_lnk('Versuchen, die Eichentür zu öffnen, um unbemerkt hineinzugelangen.','forest.php?op=oeffnen',true,true,'',false,'Öffnen')."
		`n`n".create_lnk('Über das Efeu zum Balkon hinaufklettern.','forest.php?op=klettern',true,true,'',false,'Klettern')."
		`n`n".create_lnk('Dem Ganzen den Rücken kehren - das sieht doch sehr verdächtig aus ...','forest.php?op=gehen',true,true,'',false,'Gehen'));
		














		break;
	}
	
	case "klopfen":

	{
		output("`@Du nimmst all deinen Mut zusammen und klopfst an die Eichentür. Die Schritte schwerer Eisenstulpen ertönen aus dem Innern des Turmes und werden immer lauter ...`n`n");
		switch (e_rand(1,13))
		{
		case 1:
		case 2:
		case 3:
			$explose=$session['user']['experience']*0.03;
			output("`@Jemand drückt die Tür von innen auf - doch wer es war sollst du nie erfahren. Die Wucht muss jedenfalls gewaltig gewesen sein, sonst hättest du es überlebt.
			`n`n`\$ Du bist tot!
			`n`@Du verlierst `\$".$explose."`@ Erfahrungspunkte und all dein Gold!
			`nDu kannst morgen weiterspielen.");
			$session['user']['experience']-=$explose;
			killplayer(100,0,0,'news.php','Tägliche News');


			addnews("`\$`b".$session['user']['name']."`b `\$wurde im Wald von einer schweren Eichentür erschlagen.");

			break;
		case 4:
		case 5:
		case 6:
		case 7:
		case 8:
		case 9:
		case 10:
			output("Zumindest in deiner Einbildung. Als sich dein Herzschlag wieder beruhigt, musst du zu deiner Enttäuschung feststellen, dass wohl niemand zu Hause ist. Du gehst zurück in den Wald.");
			$session['user']['specialinc']="";
			break;
		case 11:
			output("Die Tür öffnet sich und du stehst vor Bellerophontes, dem großen Heros und Chimärenbezwinger! Und tatsächlich, auf einem Tisch im Innern siehst du das Mischwesen liegen; halb Löwe, halb Skorpion. Aber dein Blick wird sofort wieder auf den Helden gezogen, diesen überaus stattlichen Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er trägt eine strahlend weiße Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, der den Göttern entstammt ... `#'Das Orakel von Delphi hatte vorhergesagt, dass jemand kommen würde, um mich nach bestandenem Kampf zu ermorden.'
			`@Er mustert dich - und beginnt dann schallend zu lachen: `#'Aber damit kann es `bDich`b ja wohl kaum gemeint haben, Wurm!'
			`n`n `@Er nimmt sich etwas Zeit und zeigt dir, wie man sich im Wald verteidigt, damit du deinen Weg zur Stadt sicher zurücklegen kannst!
			`n`n`^Du erhältst 1 Punkt Verteidigung!");
			$session['user']['defence']++;
			$session['user']['specialinc']="";
			break;
		case 12:
		case 13:
			output("Die Tür öffnet sich und du stehst vor Bellerophontes, dem großen Heros und Chimärenbezwinger! Und tatsächlich, auf einem Tisch im Innern siehst du das Mischwesen liegen; halb Löwe, halb Skorpion. Aber dein Blick wird sofort wieder auf den Helden gezogen, diesen überaus stattlichen Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er trägt eine strahlend weiße Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, der den Göttern entstammt ... `#'Das Orakel von Delphi hatte vorhergesagt, dass jemand kommen würde, um mich nach bestandenem Kampf zu ermorden.'
			`@Er mustert dich - und beginnt dann schallend zu lachen: `#'Aber damit kann es `bDich`b ja wohl kaum gemeint haben, Wurm!'
			`n`n`@Er nimmt sich etwas Zeit und zeigt dir, wie man groß und stark wird!
			`n`n`^Du erhältst 1 Punkt Angriff!");
			$session['user']['attack']++;
			$session['user']['specialinc']="";
			break;
		}
		break;
	}
	
	case "rufen":
	{
		switch (e_rand(1,10))
		{
		case 1:
		case 2:
			output("`@Du räusperst dich und rufst so laut du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'
			`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...
			`n`n ... als jemand zurückruft: `#'Nein, hier ist niemand!'
			`n`n`@Tja, das nenne ich ein Pech! Du findest es zwar seltsam, dass niemand zu Hause ist, schließlich steht ja draußen der Pegasus, aber dir bleibt wohl nichts anderes übrig, als diesen Ort zu verlassen.");
			$session['user']['specialinc']="";
			break;
		case 3:
		case 4:
		case 5:
		case 6:
			$gold = e_rand(400,1000) * $session['user']['level'];
			$expplus = round($session['user']['experience']*0.03);
			output("`@Du räusperst dich und rufst so laut Du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'
			`@Du willst gerade zu einem erneuten Rufen ansetzen ...
			`n`n ... als jemand zurückruft: `#'Herakles, bist Du's? Nimm Dir von dem Gold in dem Beutel, es ist auch das Deine!'
			`n`@Mit etwas dumpferer Stimme rufst du zurück - `#'Danke!'`@ -, greifst in den Beutel auf dem Rücken des Pegasus und begibst dich so schnell du kannst zurück zur Stadt.`n`n
			`@Du bekommst `^".$expplus." `@Erfahrungspunkte hinzu und `^".$gold." `@Goldstücke!");
			$session['user']['experience']+=$expplus;
			$session['user']['gold']+=$gold;
			$session['user']['specialinc']="";
			break;
		case 7:
		case 8:
		case 9:

			$gems = e_rand(2,5);
			output("`@Du räusperst dich und rufst so laut du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'
			`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...
			`n`n ... als jemand an den Balkon tritt: ein stattlicher Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er trägt eine strahlend weiße Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, der den Göttern entstammt ...
			`n`n`#'Sei gegrüßt, Sterblicher! Du hast große Entbehrungen auf Dich genommen, um meinen Turm zu erreichen. Dafür hast Du Dir eine Belohnung redlich verdient! Nimm! Und berichte in aller Welt, dass ich, Bellerophontes, die Chimäre besiegt habe!'`&
			`n`n `@Er wirft dir einen Beutel herunter!`n
			`nIn dem Beutel befanden sich `^$gems`@ Edelsteine!");
			$session['user']['gems']+=$gems;

			
			if (e_rand(1,4) == 4)
			{
				addnav("Zurück zum Wald","forest.php");
				addnav("Tägliche News","news.php");
				addnews("`@`b".$session['user']['name']."`b `@hielt heute in der Stadt einen langen Vortrag über `#Bellerophontes'`@ großartige Heldentaten!");
				//$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session['user']['acctid'].",': `\@stellt sich in die Nähe des Dorfbrunnens, räuspert sich und hält einen langen Vortrag über die Heldentaten eines gewissen `#Bellerophontes`@!')";
				//db_query($sql);
			}
			
			$session['user']['specialinc']="";
			break;
		case 10:
			output("`@Du räusperst dich und rufst so laut du kannst hinauf: `#'Haaaalloooo! Ist da jemand?'
			`@Nichts. Du willst gerade zu einem erneuten Rufen ansetzen ...
			`n`n ... als jemand an den Balkon tritt: ein stattlicher Mann mit langem, dunklem Haar, das von einem Reif gehalten wird. Er trägt eine strahlend weiße Robe, die das Zeichen des Poseidon ziert, und hat den ehrfurchtgebietenden Blick eines Mannes, der den Göttern entstammt ...
			`#Ich habe viel von Deinen Heldentaten gehört, ".$session['user']['name']."`#! Hier, dies soll Dir auf deinen Wegen behilflich sein! Nach meinem Sieg über die Chimäre brauche ich es nicht mehr.'`@
			`n`n Er überreicht dir sein Amulett des Lebens!
			`n`n`@Du erhältst `^5`@ permanente Lebenspunkte!");
			$session['user']['maxhitpoints']+=5;
			$session['user']['hitpoints']+=5;
			$session['user']['specialinc']="";
			break;
		}
		break;
	}
	
	case "stehlen":
	{
		switch (e_rand(1,10))
		{
		case 1:
		case 2:
		case 3:
			$expplus = round($session['user']['experience']*0.04);
			output("`@Ein wahrhaft edles Tier ... weiß wie Milch in der Sonne ... umgeben von einem blendenden Schimmer ...
			`@Aber jetzt bleibt keine Zeit für Sentimentalitäten! Du greifst nach dem Beutel und ... 
			`n`n ... wirst von den Hufen des kräftigen Tiers gegen die Mauerreste geschleudert. Erschrocken, aber froh um dein Leben rappelst du dich auf und rennst davon.
			`n`n`@Du bekommst `^".$expplus."`@ Erfahrungspunkte hinzu, verlierst aber fast alle deine Lebenspunkte!`n");
			$session['user']['hitpoints']=1;
			$session['user']['experience']+=$expplus;
			$session['user']['specialinc']="";
			break;
		case 4:
		case 5:
			$explose = round($session['user']['experience']*0.05);
			output("`@Ein wahrhaft edles Tier ... weiß wie Milch in der Sonne ... umgeben von einem blendenden Schimmer ...
			`@Aber jetzt bleibt keine Zeit für Sentimentalitäten! Du greifst nach dem Beutel und ... `n`n ... wirst von seinem Gewicht zu Boden gerissen. Er ist voller Gold, wer hätte das gedacht? Und je mehr du herausnimmst, desto schwerer scheint er zu werden! Gierig holst du immer mehr heraus, und mehr, und mehr ... das Gold sprudelt nur so hervor - und hat dich bald begraben.
			`\$`n`nDu bist tot!
			`n`n`@Du verlierst `\$".$explose."`@ Erfahrungspunkte und all dein Gold!
			`n`nDu kannst morgen weiterspielen.");
			$session['user']['experience']-=$explose;
			killplayer(100,0,0,'news.php','Tägliche News');


			addnews("`\$`b".$session['user']['name']."`b `\$wurde in ".($session['user']['sex']?"ihrer":"seiner")." Gier unter einem riesigen Haufen griechischer Goldmünzen begraben.");
			break;
		case 6:
		case 7:
		case 8:



			$foundgold = e_rand(1000,4000) * $session['user']['level'];
			$expplus = round($session['user']['experience']*0.03);
			output("`@Ein wahrhaft edles Tier ... weiß wie Milch in der Sonne ... umgeben von einem blendenden Schimmer ...
			`@Aber jetzt bleibt keine Zeit für Sentimentalitäten! Du greifst nach dem Beutel und ... `n`n ... wirst von seinem Gewicht zu Boden gerissen. Er ist voller Gold, wer hätte das gedacht? Und je mehr du herausnimmst, desto schwerer scheint er zu werden! Du nimmst soviel Gold mit, wie du tragen kannst und verschwindest von diesem seltsamen Ort. Schade, dass man den Beutel nicht mitnehmen kann ...
			`n`n`@Du erhältst `^".$expplus."`@ Erfahrungspunkte und erbeutest `^".$foundgold." `@Goldstücke!`n");
			$session['user']['gold'] += $foundgold;
			$session['user']['experience']+=$expplus;
			addnav("Zurück zum Wald","forest.php");
			addnav("Tägliche News","news.php");
			addnews("`b`@".$session['user']['name']."`b `@gelang es, dem griechischen Heros `#Bellerophontes`^ ".$foundgold."`@ Goldmünzen zu stehlen!");
			$session['user']['specialinc']="";
			break;
		case 9:
		case 10:
			$expplus = round($session['user']['experience']*0.35);
			output("`@Ein wahrhaft edles Tier ... weiß wie Milch in der Sonne ... umgeben von einem blendenden Schimmer ...
			`@Aber jetzt bleibt keine Zeit für Sentimentalitäten! Du greifst nach dem Beutel und ... 
			`n`n ... hältst kurz bevor du ihn berühren kannst inne. Der Turm, der Pegasus, der Beutel ... das alles kommt dir doch sehr, sehr merkwürdig vor. Du nimmst dieses Ereignis als wertvolle Erfahrung, von der du noch deinen Enkeln wirst erzählen können, und gehst deines Weges.
			`n`n`@Du erhältst `^".$expplus."`@ Erfahrungspunkte!`n");
			$session['user']['experience']+=$expplus;
			if (e_rand(1,4) == 1)
			{
				addnav("Zurück zum Wald","forest.php");
				addnav("Tägliche News","news.php");
				addnews("`@`b".$session['user']['name']."`b `@hat ein wundervolles Märchen über einen seltsamen Turm im Wald geschrieben - und `balle`b Stadtbewohner schwärmen davon!");
				//$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village',".$session['user']['acctid'].",': `\@freut sich, als ".($session['user']['sex']?"sie":"er")." einige Dorfbewohner über das Märchen sprechen hört, das ".($session['user']['sex']?"sie":"er")." geschrieben hat!')";
				//db_query($sql);
			}
			$session['user']['specialinc']="";
			break;
		}
		break;
	}
	
	case "oeffnen":
	{
		switch (e_rand(1,10))
		{
		case 1:
		case 2:
			$explose = round($session['user']['experience']*0.03);
			output("`@Zu deiner Freude bemerkst du, dass die Tür unverschlossen ist! Vorsichtig versuchst du sie aufzuschieben ... als sie plötzlich ... aus ... den ... Angeln ...
			`n`n `#'Neeeeeeeiiiiiiin ...!'
			`\$`n`nDu bist tot!
			`n`@Du verlierst `\$".$explose."`@ Erfahrungspunkte und all dein Gold!
			`n`@Du kannst morgen weiterspielen.");
			$session['user']['experience']-=$explose;
			killplayer(100,0,0,'news.php','Tägliche News');


			addnews("`\$`b".$session['user']['name']."`b `\$wurde im Wald von einer schweren Eichentür erschlagen.");
			break;
		case 3:
		case 4:
		case 5:
		case 6:
		case 7:
		case 8:
		case 9:
		case 10:
			output("`@Zu deiner Freude bemerkst du, dass die Tür unverschlossen ist! Vorsichtig schiebst du sie auf ... und wirfst einen ersten Blick hinein. Du siehst einen gemütlichen Vorraum, von dem aus eine Wendeltreppe nach oben führt. Es gibt einen Holztisch, der sich unter der Last des schwerverletzten Körper eines seltsamen Wesens biegt. Es ist halb Löwe, halb Skorpion ... eine Chimäre!
			`n`nDas ist aber interessant ... Du gehst hinein, um dir das Mischwesen genauer anzusehen.");
			addnav("Weiter","forest.php?op=drinnen");
			break;
		}
		break;
	}
	
	case "drinnen":
	{
		switch (e_rand(1,10))
		{
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
			$expplus = round($session['user']['experience']*0.05);
			output("`@Das Wesen ist tot. Der Wunde nach muss es mit einem einzigen Schwertstreich erlegt worden sein. Wenn da nur nicht die Verbrennungen wären ... Als du plötzlich die schnellen Schritte schwerer Eisenstulpen auf der Treppe vernimmst, greifst du panisch nach dem ersten Gegenstand, den du zu fassen bekommst - ganz ohne Beute willst du diese Gefahr nicht auf dich genommen haben. Es ist ein bronzenes Amulett ...
			`n`n`@Du hast dem griechischen Heros Bellerophontes das Amulett des Lebens gestohlen!
			`n`n`@Du erhältst `^".$expplus."`@ Erfahrungspunkte!
			`n`n`@Du erhältst `^5`@ permanente Lebenspunkte!");
			$session['user']['maxhitpoints']+=5;
			$session['user']['hitpoints']+=5;
			$session['user']['experience']+=$expplus;
			$session['user']['specialinc']="";
			break;
		case 6:
		case 7:
			$explose = round($session['user']['experience']*0.07);
			output("`@Das Wesen ist tot. Der Wunde nach muss es mit einem einzigen Schwertstreich erlegt worden sein. Wenn da nur nicht die Verbrennungen wären ...
			`@Als du plötzlich die schnellen Schritte schwerer Eisenstulpen auf der Treppe vernimmst, greifst du panisch nach dem ersten Gegenstand, den du zu fassen bekommst - ganz ohne Beute willst du diese Gefahr nicht auf dich genommen haben. Es ist ein bronzenes Amulett - das du wünschtest, nun lieber nicht in der Hand zu halten. Vor dir steht der griechische Heros Bellerophontes, Reiter des Pegasus und Bezwinger der Chimären!
			`#'Wer bist Du, Wurm, dass Du es wagst, mich zu bestehlen?!'
			`n`n`@Er erweist sich als wahrer Meister der Rhetorik und streckt dich kurzerhand mit seinem Flammenschwert nieder.
			`\$`n`nDu bist tot!
			`n`@Du verlierst `\$".$explose."`@ Erfahrungspunkte und all dein Gold!
			`n`@Du kannst morgen weiterspielen.");
			$session['user']['experience']-=$explose;
			killplayer(100,0,0,'news.php','Tägliche News');



			addnews("`\$Der ebenso gemeine wie unfähige Dieb `b".$session['user']['name']."`b `\$wurde von `#Bellerophontes`\$ mit einem Flammenschwert in der Mitte zerteilt.");
			break;
		case 8:
		case 9:
		case 10:
			$expplus = round($session['user']['experience']*0.08);
			output("`@Der Wunde nach muss das Wesen mit einem einzigen Schwertstreich erlegt worden sein. Wenn da nur nicht die Verbrennungen wären ... Na, Hauptsache es ist tot. Als du plötzlich die schnellen Schritte schwerer Eisenstulpen auf der Treppe vernimmst, greifst du panisch nach dem ersten Gegenstand, den du zu fassen bekommst - ganz ohne Beute willst du diese Gefahr nicht auf dich genommen haben. Es ist ein bronzenes Amulett - das dir aus der Hand rutscht, als du dich umdrehst. Vor dir steht der griechische Heros Bellerophontes, Reiter des Pegasus und Bezwinger der Chimären! Er reißt sein flammendes Schwert nach oben, um zum Schlag auszuholen. Jetzt ist es aus!
			`#'Runter mit Dir, Du Wurm!'`@ Reflexartig tust du, wie dir geheißen und spürst die Hitze des Schwertes an deiner Wange entlangsausen. Wi-der-lich-es, grünes Chimärenblut bespritzt dich über und über. Dankbar schaust du auf, deinem Retter ins Gesicht.
			`n`n `#'Das wäre beinahe Dein Tod gewesen, Du schäbiger Dieb. Aber diesmal sei Dir der Schrecken Lehre genug!' `@Bellerophontes ist gnädig und jagt dich mit Fußtritten nach draußen.
			`n`n`@Du erhältst `^".$expplus."`@ Erfahrungspunkte!
			`n`nDu verlierst `\$2 `@Charmepunkte!
			`n`n`@Auf der Flucht hast du die Hälfte deines Goldes verloren!`n");
			$session['user']['charm']-=2;
			$session['user']['experience']+=$expplus;

			$session['user']['gold']*=0.50;
			$session['user']['specialinc']="";
			break;
		}
		break;
	}
	
	case "klettern":
	{
		switch (e_rand(1,10))
		{
		case 1:
		case 2:
			$explose = round($session['user']['experience']*0.03);
			output("`@Du greifst nach dem Efeu und ziehst einige Male daran. Alles in Ordnung, es scheint zu halten. Vorsichtig beginnst du hinaufzuklettern ...
			`@Du hast gerade die Hälfte des Weges bis zum Balkon erklommen, als du plötzlich mit einem Fuß hängen bleibst. Du schüttelst ihn, um ihn freizubekommen, doch vergebens - die Pflanze scheint dich bei sich behalten zu wollen! In Panik verfallen, wirst du immer hektischer, aber alle Mühe wird bestraft: schon bald kannst du dich überhaupt nicht mehr bewegen. Die Pflanze hält dich für die Ewigkeit gefangen.
			`\$`n`nDu bist tot!
			`@`n`nDu verlierst `\$".$explose."`@ Erfahrungspunkte und all dein Gold!
			`@`n`nDu kannst morgen weiterspielen.");
			$session['user']['experience']-=$explose;
			killplayer(100,0,0,'news.php','Tägliche News');



			addnews("`\$`b".$session['user']['name']."`b `\$verhedderte sich im Efeu von `#Bellerophontes'`\$ Turm und ist dort verhungert.");
			break;
		case 4:
		case 5:
		case 6:
		case 7:
		case 8:
			output("`@Du greifst nach dem Efeu und ziehst einige Male daran. Alles in Ordnung, es scheint zu halten. Vorsichtig beginnst du hinaufzuklettern ...
			Das ist aber einfach! Ohne Probleme erklimmst du das Efeu bis zum Balkon. Mit einem letzten, kraftvollen Zug hievst du deinen edlen Körper über die Brüstung und erblickst: Bellerophontes, den griechischen Heros!
			Er tritt dir mit gemessenen Schritten entgegen, während du nichts empfindest als Bewunderung für seine großartige Erscheinung: langes, dunkles Haar, das von einem Reif gehalten wird; eine strahlend weiße Robe, die das Zeichen des Poseidon ziert; der ehrfurchtgebietende Blick eines Mannes, der den Göttern entstammt ...
			Dein Bewusstsein schwindet und du hast einen Traum, wie keinen je zuvor. Ein großes Mischwesen aus Löwe und Skorpion kommt darin vor ...
			`n`nAls du wieder erwachst, liegst du irgendwo im Wald und schwelgst noch immer - mit genauer Erinnerung an Bellerophontes' ästhetische Kampftaktik!
			`n`nDa du von nun an anmutiger kämpfen wirst, erhältst du `^2`@ Charmepunkte!
			`n`nDu erhältst `^1`@ Punkt Angriff!");
			$session['user']['charm']+=2;

			$session['user']['attack']++;
			$session['user']['specialinc']="";
			break;
		case 3:
		case 9:
		case 10:
			$explose = round($session['user']['experience']*0.07);
			output("`@Du greifst nach dem Efeu und ziehst einige Male daran. Alles in Ordnung, es scheint zu halten. Vorsichtig beginnst du hinaufzuklettern ...
			Das ist aber einfach! Ohne Probleme erklimmst du das Efeu bis zum Balkon. Mit einem letzten, kraftvollen Zug hievst du deinen edlen Körper über die Brüstung und erblickst: Bellerophontes, den griechischen Heros!
			`@Er tritt dir mit gemessenen Schritten entgegen, während du nichts empfindest als Bewunderung für seine großartige Erscheinung: langes, dunkles Haar, das von einem Reif gehalten wird; eine strahlend weiße Robe, die das Zeichen des Poseidon ziert; der ehrfurchtgebietende Blick eines Mannes, der den Göttern entstammt ...
			Kam erst der Schlag und dann der Flug? Oder war es umgekehrt?
			`\$`n`nDu bist tot!
			`n`n`@Du verlierst `\$".$explose."`@ Erfahrungspunkte und während des Fluges all dein Gold!
			`n`n`@Du kannst morgen weiterspielen.");
			$session['user']['experience']-=$explose;
			killplayer(100,0,0,'news.php','Tägliche News');



			addnews("`\$Es wurde beobachtet, wie `b".$session['user']['name']."`b`\$ aus heiterem Himmel herab auf den Stadtplatz fiel und beim Aufprall zerplatzte.");
			
			break;
		}
		break;
	}
	
	case "gehen":

	{
		output("`@Du verlässt diesen seltsamen Ort und kehrst in den Wald zurück. Eine vernünftige Entscheidung! Aber dein Entdeckerherz fragt sich, ob `bVernunft`b für einen Abenteurer die beste aller Eigenschaften ist ...");
		$session['user']['specialinc']="";
		break;
	}
	
	default:
		output('ungültige Operation '.$_GET['op']);
}
?>
