<?php
/*
Der Wortkampf by Tiger313
Idee: Wolf
Umsetzung: Tiger313
Demo: http://www.das-ging-fix.de/dorte/MLC-Board2-1-3/logd/index.php
Komplettüberarbeitung by Salator für Atrahor

Anleitung:
----------
SQL nicht mehr nötig, auf specialmisc umgestellt: Einer=Piratpoints, Zehner=Userpoints
Diese Datei in den special ordner rein
FERTIG
*/
if (!isset($session))
{
	exit();
}

$c=CRPChat::make_color($Char->prefs['commenttalkcolor'],'3');
$answer=array(
1=>$c.'Ich wollte, dass Du Dich wie zuhause fühlst.`0',
2=>$c.'Doch, doch, Du hast sie nur nie gelernt.`0',
3=>$c.'Dann mach\' damit nicht rum wie mit dem Staubwedel.`0',
4=>$c.'Er muss Dir das Fechten beigebracht haben.`0',
5=>$c.'Und Du wirst deine rostige Klinge nie wieder sehen.`0',
6=>$c.'Du kannst so schnell davonlaufen?`0',
7=>$c.'Hattest Du das nicht vor kurzem getan?`0',
8=>$c.'Wenn ich mit DIR fertig bin, bist Du nur noch Filet!`0',
9=>$c.'Sollt\' ich in Deiner Nähe sterben, möcht\' ich, dass man mich desinfiziert!`0',
10=>$c.'Also mal wieder in der Nase gebohrt, wie?`0',
11=>$c.'Wieso? Die könntest Du viel eher brauchen.`0',
12=>$c.'Dann wäre koffeinfreier Kaffee ein erster Schritt zur Läuterung!`0',
13=>$c.'Ich glaub\', es gibt für Dich noch eine Stelle beim Varieté!`0',
14=>$c.'Ich schaudere, ich schaudere.`0',
15=>$c.'Vielleicht solltest du es endlich mal benutzen.`0',
16=>$c.'Da hat sich wohl Dein Spiegelbild in meinem Säbel reflektiert!`0',
17=>$c.'Zu schade, dass dich überhaupt keiner kennt.`0',
18=>$c.'Oh, das ist ein solch übles Klischee!`0',
19=>$c.'Das ich nicht lache! Du und welche Armee?`0',
20=>$c.'In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.`0',
21=>$c.'Für Dein Gesicht bekommst Du \'ne Begnadigung.`0',
22=>$c.'Das war ja auch leicht, Dein Atem hat sie paralysiert!`0',
23=>$c.'Argh! Auch bevor sie deinen Atem riechen.`0',
24=>$c.'Dein Geruch allein reicht aus, und ich wär\' kollabiert!`0',
25=>$c.'Ich könnte es tun, hättest Du nur ein Atemspray!`0',
26=>$c.'Zu schade, dass das hier niemand tangiert.`0',
27=>$c.'Dafür hab\' ich in der Hand nicht die Gicht!`0',
28=>$c.'Aargh! Behalt sie für dich, sonst bekomm\' ich noch Pusteln!`0',
29=>$c.'Das ist ein Lachen, du schwächlicher Wicht! Aargh!`0',
30=>$c.'Dich zu töten wäre dann eine legale Reinigung!`0',
31=>$c.'Grrr ... Dann ist alles klar, Du bist deshalb so dick!`0',
32=>$c.'Wenn ich mit Dir fertig bin brauchst Du \'ne Krücke!`0',
33=>$c.'Ja, ja, ich weiß, ein dreiköpfiger Affe!`0',
34=>$c.'Aargh! Das sind große Worte für \'nen Kerl ohne Grips!`0',
35=>$c.'Ungh! Sie sitzt mir gegenüber, also was fragst Du mich?`0',
36=>$c.'Ungh ... wobei mir vor allem vor Deinem Atem graust.`0',
37=>$c.'Hoffentlich zerrst Du mich nicht ins Separée!`0',
38=>$c.'Aargh! Unglaublich erbärmlich, das sag\' ab jetzt ich.`0',
39=>$c.'Zumindest hat man meine identifiziert!`0',
40=>$c.'Deine Mutter trägt ein Toupet!`0',
41=>$c.'Ungh! Ich werde mich wehren, bis die Griffel Dir qualmen!`0',
42=>$c.'Oooh. Zu schade, dass keine davon in diesen Armen ist.`0',
43=>$c.'Ungh! Mit Ausnahme von Deiner Frau, soviel ist klar!`0',
44=>$c.'Grrrgh! Ich wusste gar nicht, dass Du so weit zählen kannst. Aargh!`0',
45=>$c.'Ungh ... Du bist das hässlichste Wesen, das ich jemals sah ... grr.`0',
46=>$c.'Ungh! Und Babys wohl auch. Na, der Witz ist gelungen.`0',
47=>$c.'Das kommt vom Wohnen im Solarium.`0'
);
//1-10 schlecht, rest gut
$link=array(
1=>'forest.php?op=aoadobgtduesop',
2=>'forest.php?op=aoadebgtduasop',
3=>'forest.php?op=aoedobgtduasop',
4=>'forest.php?op=aoadodgtduasop',
5=>'forest.php?op=aoedebgtduasop',
6=>'forest.php?op=aoebobgtbuesop',
7=>'forest.php?op=aoadobgtbuesop',
8=>'forest.php?op=aoedodgtbuesop',
9=>'forest.php?op=aoebobgtduesop',
10=>'forest.php?op=aoabodgtduesop',
11=>'forest.php?op=aoedobgtduesop',
12=>'forest.php?op=aoebobgtduasop',
13=>'forest.php?op=aoebobgtbuasop',
14=>'forest.php?op=aoedodgtduasop',
15=>'forest.php?op=aoadebgtduesop',
16=>'forest.php?op=aoabobgtduesop',
17=>'forest.php?op=aoadodgtbuesop',
18=>'forest.php?op=aoadobgtduasop',
19=>'forest.php?op=aoebodgtduasop',
20=>'forest.php?op=aoadebgtbuesop'
);

switch($_GET['op'])
{
	case '':
	{
		output('`3 Auf deiner Suche kommt dir auf einmal ein `4Pirat `3entgegen.
		`nEr scheint ziemlich verärgert zu sein. Du versuchst, ihn nicht direkt anzuschauen.
		`nAls du ihn aus Versehen im Vorbeilaufen ganz leicht berühst, dreht der Pirat durch.
		`nEr fordert dich zu Kampf heraus und du ziehst sofort dein Schwert, aber der `4Pirat `3zeigt dir einen Vogel und meint:
		`n\'`9Was willst du mit dem Zahnstocher? Nur Schwächlinge wie Du brauchen Waffen. Die stärkste Waffe sind `bWÖRTER`b, also kämpfe mit denen oder garnicht.`3\'
		`n`n`3 Was machst du?`n');
		
		addnav('Kämpfen','forest.php?op=kampf');
		addnav('Zurück in den Wald','forest.php?op=wald');
		$session['user']['specialinc']='beleidgterpirat.php';
		break;
	}

	case 'kampf':
	{
		output('`3 Du packst dein Schwert wieder weg.
		`n`3Der Pirat stellt sich dir gegenüber auf, räuspert sich noch mal und fängt an!`n`n');
		$session['user']['specialinc']='beleidgterpirat.php';
		$session['user']['specialmisc']=0;
		addnav('Klicke auf die richtige Antwort','');
		switch (e_rand(1,3))
		{
		case 1:
			output('`9Mein Schwert wird Dich aufspießen wie ein Schaschlik!
			`0`n`n`n'.create_lnk($answer[1],$link[2]).'
			`n`n'.create_lnk($answer[2],$link[3]).'
			`n`n'.create_lnk($answer[3],$link[14]).'
			`n`n'.create_lnk($answer[4],$link[8]).'
			`n`n'.create_lnk($answer[5],$link[6]));
			break;

			case 2:
			output('`9Deine Fuchtelei hat nichts mit Fechtkunst zu tun!
			`0`n`n`n'.create_lnk($answer[4],$link[4]).'
			`n`n'.create_lnk($answer[2],$link[13]).'
			`n`n'.create_lnk($answer[1],$link[7]).'
			`n`n'.create_lnk($answer[3],$link[2]).'
			`n`n'.create_lnk($answer[5],$link[6]));
			break;

			case 3:
			output('`9Niemand wird mich verlieren sehen, auch Du nicht!
			`0`n`n`n'.create_lnk($answer[6],$link[16]).'
			`n`n'.create_lnk($answer[5],$link[1]).'
			`n`n'.create_lnk($answer[7],$link[2]).'
			`n`n'.create_lnk($answer[8],$link[3]).'
			`n`n'.create_lnk($answer[9],$link[4]));
			break;
		}
		break;
	}

	//*****************Anfang Kombinationen zu Verwirung**************//
	case 'aoadobgtduesop':
	case 'aoadebgtduasop':
	case 'aoedobgtduasop':
	case 'aoadodgtduasop':
	case 'aoedebgtduasop':
	case 'aoebobgtbuesop':
	case 'aoadobgtbuesop':
	case 'aoedodgtbuesop':
	case 'aoebobgtduesop':
	case 'aoabodgtduesop':
	{
		$session['user']['specialmisc']++;
		$session['user']['specialinc']='beleidgterpirat.php';
		output('`9HaHaHa! Punkt für mich!`n`n
		`qNeuer Stand:`n '.($session['user']['specialmisc']%10).' Punkte Pirat `n '.(floor($session['user']['specialmisc']/10)).' Punkte Du!`n`n`0');
		if ($session['user']['specialmisc']%10 <5)
		{
			output(create_lnk('`#Weiter`0','forest.php?op=kampf2',true,true,'',false,'Weiter',1));
		}
		else
		{
			output(create_lnk('`#Weiter`0','forest.php?op=ergebnis',true,true,'',false,'Weiter',1));
		}
		break;
	}
	case 'aoedobgtduesop':
	case 'aoebobgtduasop':
	case 'aoebobgtbuasop':
	case 'aoedodgtduasop':
	case 'aoadebgtduesop':
	case 'aoabobgtduesop':
	case 'aoadodgtbuesop':
	case 'aoadobgtduasop':
	case 'aoebodgtduasop':
	case 'aoadebgtbuesop':
	{
		$session['user']['specialmisc']+=10;
		$session['user']['specialinc']='beleidgterpirat.php';
		output('`9Oh Mist, das war gemein! Punkt für dich!`n`n
		`qNeuer Stand:`n '.($session['user']['specialmisc']%10).' Punkte Pirat `n '.(floor($session['user']['specialmisc']/10)).' Punkte Du!`n`n`0');
		if ($session['user']['specialmisc']<50)
		{
			output(create_lnk('`#Weiter`0','forest.php?op=kampf2',true,true,'',false,'Weiter',1));
		}
		else
		{
			output(create_lnk('`#Weiter`0','forest.php?op=ergebnis',true,true,'',false,'Weiter',1));
		}
		break;
	}
	//*****************Ende Kombinationen zu Verwirung**************//

	case 'kampf2':
	{
		$session['user']['specialinc']='beleidgterpirat.php';
		addnav('Klicke auf die richtige Antwort','');
		$dice=$_POST['dice']>0?$_POST['dice']:e_rand(1,66);
		switch ($dice)
		{
		case 1:
			output('`9Mein Schwert wird Dich aufspießen wie ein Schaschlik!
			`0`n`n`n'.create_lnk($answer[14],$link[1]).'
			`n`n'.create_lnk($answer[19],$link[7]).'
			`n`n'.create_lnk($answer[1],$link[5]).'
			`n`n'.create_lnk($answer[3],$link[11]).'
			`n`n'.create_lnk($answer[5],$link[10]));
			break;

		case 2:
			output('`9Deine Fuchtelei hat nichts mit Fechtkunst zu tun!
			`0`n`n`n'.create_lnk($answer[18],$link[2]).'
			`n`n'.create_lnk($answer[1],$link[1]).'
			`n`n'.create_lnk($answer[3],$link[10]).'
			`n`n'.create_lnk($answer[5],$link[9]).'
			`n`n'.create_lnk($answer[2],$link[18]));
			break;

		case 3:
			output('`9Niemand wird mich verlieren sehen, auch Du nicht.
			`0`n`n`n'.create_lnk($answer[6],$link[12]).'
			`n`n'.create_lnk($answer[5],$link[1]).'
			`n`n'.create_lnk($answer[7],$link[2]).'
			`n`n'.create_lnk($answer[8],$link[3]).'
			`n`n'.create_lnk($answer[9],$link[4]));
			break;

		case 4:
			output('`9Ich hatte mal einen Hund, der war klüger als Du.
			`0`n`n`n'.create_lnk($answer[10],$link[5]).'
			`n`n'.create_lnk($answer[4],$link[14]).'
			`n`n'.create_lnk($answer[33],$link[6]).'
			`n`n'.create_lnk($answer[12],$link[7]).'
			`n`n'.create_lnk($answer[13],$link[8]));
			break;

		case 5:
			output('`9Du hast die Manieren eines Bettlers.
			`0`n`n`n'.create_lnk($answer[14],$link[3]).'
			`n`n'.create_lnk($answer[4],$link[4]).'
			`n`n'.create_lnk($answer[1],$link[13]).'
			`n`n'.create_lnk($answer[15],$link[5]).'
			`n`n'.create_lnk($answer[16],$link[6]));
			break;

		case 6:
			output('`9Jeder hier kennt Dich als unerfahrenen Dummkopf.
			`0`n`n`n'.create_lnk($answer[5],$link[7]).'
			`n`n'.create_lnk($answer[4],$link[8]).'
			`n`n'.create_lnk($answer[9],$link[9]).'
			`n`n'.create_lnk($answer[17],$link[15]).'
			`n`n'.create_lnk($answer[18],$link[10]));
			break;

		case 7:
			output('`9Du kämpfst wie ein dummer Bauer.
			`0`n`n`n'.create_lnk($answer[3],$link[1]).'
			`n`n'.create_lnk($answer[34],$link[2]).'
			`n`n'.create_lnk($answer[43],$link[3]).'
			`n`n'.create_lnk($answer[19],$link[4]).'
			`n`n'.create_lnk($answer[14],$link[16]));
			break;

		case 8:
			output('`9Meine Narbe im Gesicht stammt aus einem harten Kampf.
			`0`n`n`n'.create_lnk($answer[10],$link[17]).'
			`n`n'.create_lnk($answer[17],$link[5]).'
			`n`n'.create_lnk($answer[8],$link[6]).'
			`n`n'.create_lnk($answer[20],$link[7]).'
			`n`n'.create_lnk($answer[21],$link[8]));
			break;

		case 9:
			output('`9Menschen fallen mir zu Füßen, wenn ich komme.
			`0`n`n`n'.create_lnk($answer[22],$link[9]).'
			`n`n'.create_lnk($answer[23],$link[18]).'
			`n`n'.create_lnk($answer[9],$link[10]).'
			`n`n'.create_lnk($answer[24],$link[1]).'
			`n`n'.create_lnk($answer[25],$link[3]));
			break;

		case 10:
			output('`9Dein Schwert hat schon bessere Zeiten gesehen.
			`0`n`n`n'.create_lnk($answer[1],$link[5]).'
			`n`n'.create_lnk($answer[17],$link[7]).'
			`n`n'.create_lnk($answer[5],$link[19]).'
			`n`n'.create_lnk($answer[26],$link[9]).'
			`n`n'.create_lnk($answer[27],$link[2]));
			break;

		case 11:
			output('`9Du bist kein Gegner für mein geschultes Gehirn.
			`0`n`n`n'.create_lnk($answer[46],$link[1]).'
			`n`n'.create_lnk($answer[14],$link[7]).'
			`n`n'.create_lnk($answer[13],$link[5]).'
			`n`n'.create_lnk($answer[15],$link[11]).'
			`n`n'.create_lnk($answer[28],$link[10]));
			break;

		case 12:
			output('`9Trägst Du immer noch Windeln?
			`0`n`n`n'.create_lnk($answer[29],$link[2]).'
			`n`n'.create_lnk($answer[13],$link[1]).'
			`n`n'.create_lnk($answer[26],$link[10]).'
			`n`n'.create_lnk($answer[1],$link[9]).'
			`n`n'.create_lnk($answer[11],$link[18]));
			break;

		case 13:
			output('`9An deiner Stelle würde ich zur Landratte werden.
			`0`n`n`n'.create_lnk($answer[7],$link[12]).'
			`n`n'.create_lnk($answer[30],$link[1]).'
			`n`n'.create_lnk($answer[16],$link[2]).'
			`n`n'.create_lnk($answer[25],$link[3]).'
			`n`n'.create_lnk($answer[31],$link[4]));
			break;

		case 14:
			output('`9Alles, was Du sagst, ist dumm.
			`0`n`n`n'.create_lnk($answer[16],$link[5]).'
			`n`n'.create_lnk($answer[1],$link[14]).'
			`n`n'.create_lnk($answer[26],$link[6]).'
			`n`n'.create_lnk($answer[32],$link[7]).'
			`n`n'.create_lnk($answer[47],$link[8]));
			break;

		case 15:
			output('`9Hast Du eine Idee, wie Du hier lebend herauskommst?
			`0`n`n`n'.create_lnk($answer[14],$link[3]).'
			`n`n'.create_lnk($answer[42],$link[4]).'
			`n`n'.create_lnk($answer[11],$link[13]).'
			`n`n'.create_lnk($answer[18],$link[5]).'
			`n`n'.create_lnk($answer[2],$link[6]));
			break;

		case 16:
			output('`9Mein Schwert wird Dich in 1000 Stücke reißen!
			`0`n`n`n'.create_lnk($answer[30],$link[7]).'
			`n`n'.create_lnk($answer[44],$link[8]).'
			`n`n'.create_lnk($answer[9],$link[9]).'
			`n`n'.create_lnk($answer[3],$link[15]).'
			`n`n'.create_lnk($answer[26],$link[10]));
			break;

		case 17:
			output('`9Niemand wird sehen, dass ich so schlecht kämpfe wie Du.
			`0`n`n`n'.create_lnk($answer[38],$link[1]).'
			`n`n'.create_lnk($answer[34],$link[2]).'
			`n`n'.create_lnk($answer[35],$link[3]).'
			`n`n'.create_lnk($answer[36],$link[4]).'
			`n`n'.create_lnk($answer[6],$link[16]));
			break;

		case 18:
			output('`9Nach dem letzten Kampf war meine Hand blutüberströmt.
			`0`n`n`n'.create_lnk($answer[10],$link[17]).'
			`n`n'.create_lnk($answer[17],$link[5]).'
			`n`n'.create_lnk($answer[16],$link[6]).'
			`n`n'.create_lnk($answer[18],$link[7]).'
			`n`n'.create_lnk($answer[21],$link[8]));
			break;

		case 19:
			output('`9Kluge Gegner laufen weg, bevor sie mich sehen.
			`0`n`n`n'.create_lnk($answer[22],$link[9]).'
			`n`n'.create_lnk($answer[23],$link[18]).'
			`n`n'.create_lnk($answer[9],$link[10]).'
			`n`n'.create_lnk($answer[24],$link[1]).'
			`n`n'.create_lnk($answer[25],$link[3]));
			break;

		case 20:
			output('`9Überall in der Gegend kennt man meine Klinge.
			`0`n`n`n'.create_lnk($answer[33],$link[5]).'
			`n`n'.create_lnk($answer[37],$link[7]).'
			`n`n'.create_lnk($answer[17],$link[19]).'
			`n`n'.create_lnk($answer[38],$link[9]).'
			`n`n'.create_lnk($answer[8],$link[2]));
			break;

		case 21:
			output('`9Bis jetzt wurde jeder Gegner von mir eliminiert!
			`0`n`n`n'.create_lnk($answer[37],$link[1]).'
			`n`n'.create_lnk($answer[30],$link[7]).'
			`n`n'.create_lnk($answer[16],$link[5]).'
			`n`n'.create_lnk($answer[22],$link[11]).'
			`n`n'.create_lnk($answer[9],$link[10]));
			break;

		case 22:
			output('`9Du bist so hässlich wie ein Affe im Negligé!
			`0`n`n`n'.create_lnk($answer[13],$link[2]).'
			`n`n'.create_lnk($answer[19],$link[1]).'
			`n`n'.create_lnk($answer[4],$link[10]).'
			`n`n'.create_lnk($answer[8],$link[9]).'
			`n`n'.create_lnk($answer[37],$link[18]));
			break;

		case 23:
			output('`9Dich zu töten wäre eine legale Beseitigung!
			`0`n`n`n'.create_lnk($answer[30],$link[12]).'
			`n`n'.create_lnk($answer[9],$link[1]).'
			`n`n'.create_lnk($answer[20],$link[2]).'
			`n`n'.create_lnk($answer[41],$link[3]).'
			`n`n'.create_lnk($answer[21],$link[4]));
			break;

		case 24:
			output('`9Warst Du schon immer so hässlich oder bist Du mutiert?
			`0`n`n`n'.create_lnk($answer[24],$link[5]).'
			`n`n'.create_lnk($answer[16],$link[14]).'
			`n`n'.create_lnk($answer[39],$link[6]).'
			`n`n'.create_lnk($answer[18],$link[7]).'
			`n`n'.create_lnk($answer[1],$link[8]));
			break;

		case 25:
			output('`9Ich spieß\' Dich auf wie eine Sau am Buffet!.
			`0`n`n`n'.create_lnk($answer[1],$link[3]).'
			`n`n'.create_lnk($answer[31],$link[4]).'
			`n`n'.create_lnk($answer[8],$link[13]).'
			`n`n'.create_lnk($answer[32],$link[5]).'
			`n`n'.create_lnk($answer[16],$link[6]));
			break;

		case 26:
			output('`9Wirst Du laut Testament eingeäschert oder einbalsamiert?
			`0`n`n`n'.create_lnk($answer[39],$link[7]).'
			`n`n'.create_lnk($answer[7],$link[8]).'
			`n`n'.create_lnk($answer[24],$link[9]).'
			`n`n'.create_lnk($answer[9],$link[15]).'
			`n`n'.create_lnk($answer[18],$link[10]));
			break;

		case 27:
			output('`9Ein jeder hat vor meiner Schwertkunst kapituliert!
			`0`n`n`n'.create_lnk($answer[3],$link[1]).'
			`n`n'.create_lnk($answer[4],$link[2]).'
			`n`n'.create_lnk($answer[15],$link[3]).'
			`n`n'.create_lnk($answer[19],$link[4]).'
			`n`n'.create_lnk($answer[24],$link[16]));
			break;

		case 28:
			output('`9Ich werde Dich richten - und es gibt kein Plädoyer!
			`0`n`n`n'.create_lnk($answer[19],$link[17]).'
			`n`n'.create_lnk($answer[17],$link[5]).'
			`n`n'.create_lnk($answer[8],$link[6]).'
			`n`n'.create_lnk($answer[20],$link[7]).'
			`n`n'.create_lnk($answer[21],$link[8]));
			break;

		case 29:
			output('`9Himmel bewahre! Für einen Hintern wäre Dein Gesicht eine Beleidigung!
			`0`n`n`n'.create_lnk($answer[28],$link[9]).'
			`n`n'.create_lnk($answer[20],$link[18]).'
			`n`n'.create_lnk($answer[21],$link[10]).'
			`n`n'.create_lnk($answer[24],$link[1]).'
			`n`n'.create_lnk($answer[12],$link[3]));
			break;

		case 30:
			output('`9Fühl\' ich den Stahl in der Hand, bin ich in meinem Metier!
			`0`n`n`n'.create_lnk($answer[1],$link[5]).'
			`n`n'.create_lnk($answer[17],$link[7]).'
			`n`n'.create_lnk($answer[13],$link[19]).'
			`n`n'.create_lnk($answer[26],$link[9]).'
			`n`n'.create_lnk($answer[27],$link[2]));
			break;

		case 31:
			output('`9Haben sich Deine Eltern nach Deiner Geburt sterilisiert?
			`0`n`n`n'.create_lnk($answer[11],$link[1]).'
			`n`n'.create_lnk($answer[14],$link[7]).'
			`n`n'.create_lnk($answer[43],$link[5]).'
			`n`n'.create_lnk($answer[39],$link[11]).'
			`n`n'.create_lnk($answer[28],$link[10]));
			break;

		case 32:
			output('`9En garde! Touché!
			`0`n`n`n'.create_lnk($answer[29],$link[2]).'
			`n`n'.create_lnk($answer[13],$link[1]).'
			`n`n'.create_lnk($answer[3],$link[10]).'
			`n`n'.create_lnk($answer[1],$link[9]).'
			`n`n'.create_lnk($answer[18],$link[18]));
			break;

		case 33:
			output('`9Überall im Drachental wird mein Name respektiert!
			`0`n`n`n'.create_lnk($answer[26],$link[12]).'
			`n`n'.create_lnk($answer[30],$link[1]).'
			`n`n'.create_lnk($answer[16],$link[2]).'
			`n`n'.create_lnk($answer[25],$link[3]).'
			`n`n'.create_lnk($answer[31],$link[4]));
			break;

		case 34:
			output('`9Niemand kann mich stoppen: mich - den Schrecken der See!
			`0`n`n`n'.create_lnk($answer[16],$link[5]).'
			`n`n'.create_lnk($answer[25],$link[14]).'
			`n`n'.create_lnk($answer[26],$link[6]).'
			`n`n'.create_lnk($answer[32],$link[7]).'
			`n`n'.create_lnk($answer[33],$link[8]));
			break;

		case 35:
			output('`9Mein Mienenspiel zeigt Dir meine Missbilligung!
			`0`n`n`n'.create_lnk($answer[14],$link[3]).'
			`n`n'.create_lnk($answer[10],$link[4]).'
			`n`n'.create_lnk($answer[21],$link[13]).'
			`n`n'.create_lnk($answer[18],$link[5]).'
			`n`n'.create_lnk($answer[12],$link[6]));
			break;

		case 36:
			output('`9Ganze Inselreiche haben vor mir kapituliert!
			`0`n`n`n'.create_lnk($answer[30],$link[7]).'
			`n`n'.create_lnk($answer[12],$link[8]).'
			`n`n'.create_lnk($answer[9],$link[9]).'
			`n`n'.create_lnk($answer[22],$link[15]).'
			`n`n'.create_lnk($answer[26],$link[10]));
			break;

		case 37:
			output('`9Du hast soviel Sexappeal wie ein Croupier!
			`0`n`n`n'.create_lnk($answer[16],$link[1]).'
			`n`n'.create_lnk($answer[34],$link[2]).'
			`n`n'.create_lnk($answer[1],$link[3]).'
			`n`n'.create_lnk($answer[36],$link[4]).'
			`n`n'.create_lnk($answer[37],$link[16]));
			break;

		case 38:
			output('`9Bist Du das? Es riecht hier so nach Jauche und Dung!
			`0`n`n`n'.create_lnk($answer[30],$link[17]).'
			`n`n'.create_lnk($answer[45],$link[5]).'
			`n`n'.create_lnk($answer[10],$link[6]).'
			`n`n'.create_lnk($answer[40],$link[7]).'
			`n`n'.create_lnk($answer[21],$link[8]));
			break;

		case 39:
			output('`9Wurdest Du damals von einem Schwein adoptiert?
			`0`n`n`n'.create_lnk($answer[22],$link[9]).'
			`n`n'.create_lnk($answer[16],$link[18]).'
			`n`n'.create_lnk($answer[35],$link[10]).'
			`n`n'.create_lnk($answer[24],$link[1]).'
			`n`n'.create_lnk($answer[11],$link[3]));
			break;

		case 40:
			output('`9Auch wenn Du es nicht glaubst, aus Dir mach\' ich Haschee!
			`0`n`n`n'.create_lnk($answer[19],$link[1]).'
			`n`n'.create_lnk($answer[14],$link[7]).'
			`n`n'.create_lnk($answer[7],$link[5]).'
			`n`n'.create_lnk($answer[8],$link[11]).'
			`n`n'.create_lnk($answer[5],$link[10]));
			break;

		case 41:
			output('`9Ich lass\' Dir die Wahl: erdolcht, erhängt oder guillotiniert!
			`0`n`n`n'.create_lnk($answer[8],$link[1]).'
			`n`n'.create_lnk($answer[41],$link[7]).'
			`n`n'.create_lnk($answer[24],$link[5]).'
			`n`n'.create_lnk($answer[9],$link[11]).'
			`n`n'.create_lnk($answer[5],$link[10]));
			break;

		case 42:
			output('`9Dein Geplänkel bringt mich richtig in Schwung!
			`0`n`n`n'.create_lnk($answer[46],$link[2]).'
			`n`n'.create_lnk($answer[1],$link[1]).'
			`n`n'.create_lnk($answer[20],$link[10]).'
			`n`n'.create_lnk($answer[5],$link[9]).'
			`n`n'.create_lnk($answer[12],$link[18]));
			break;

		case 43:
			output('`9Ich weiß nicht, welche meiner Eigenschaften Dir am meisten imponiert!
			`0`n`n`n'.create_lnk($answer[24],$link[12]).'
			`n`n'.create_lnk($answer[33],$link[1]).'
			`n`n'.create_lnk($answer[39],$link[2]).'
			`n`n'.create_lnk($answer[8],$link[3]).'
			`n`n'.create_lnk($answer[9],$link[4]));
			break;

		case 44:
			output('`9Jetzt werde ich Dich erstechen, da hilft kein Protegée!
			`0`n`n`n'.create_lnk($answer[10],$link[5]).'
			`n`n'.create_lnk($answer[19],$link[14]).'
			`n`n'.create_lnk($answer[5],$link[6]).'
			`n`n'.create_lnk($answer[12],$link[7]).'
			`n`n'.create_lnk($answer[13],$link[8]));
			break;

		case 45:
			output('`9Ist ein Blick in den Spiegel nicht jeden Tag für Dich eine Erniedrigung?
			`0`n`n`n'.create_lnk($answer[12],$link[3]).'
			`n`n'.create_lnk($answer[4],$link[4]).'
			`n`n'.create_lnk($answer[20],$link[13]).'
			`n`n'.create_lnk($answer[15],$link[5]).'
			`n`n'.create_lnk($answer[16],$link[6]));
			break;

		case 46:
			output('`9Ich lauf\' barfuß auf glühenden Kohlen und im Schnee!
			`0`n`n`n'.create_lnk($answer[5],$link[7]).'
			`n`n'.create_lnk($answer[6],$link[8]).'
			`n`n'.create_lnk($answer[26],$link[9]).'
			`n`n'.create_lnk($answer[13],$link[15]).'
			`n`n'.create_lnk($answer[18],$link[10]));
			break;

		case 47:
			output('`9Du bist eine Schande für Deine Gattung, so dilettiert!
			`0`n`n`n'.create_lnk($answer[21],$link[1]).'
			`n`n'.create_lnk($answer[32],$link[2]).'
			`n`n'.create_lnk($answer[18],$link[3]).'
			`n`n'.create_lnk($answer[40],$link[4]).'
			`n`n'.create_lnk($answer[39],$link[16]));
			break;

		case 48:
			output('`9Deine Mutter trägt ein Toupet!
			`0`n`n`n'.create_lnk($answer[18],$link[17]).'
			`n`n'.create_lnk($answer[17],$link[5]).'
			`n`n'.create_lnk($answer[8],$link[6]).'
			`n`n'.create_lnk($answer[20],$link[7]).'
			`n`n'.create_lnk($answer[21],$link[8]));
			break;

		case 49:
			output('`9Durch meine Fechtkunst bin ich zum Siegen prädestiniert!
			`0`n`n`n'.create_lnk($answer[22],$link[9]).'
			`n`n'.create_lnk($answer[26],$link[18]).'
			`n`n'.create_lnk($answer[9],$link[10]).'
			`n`n'.create_lnk($answer[24],$link[1]).'
			`n`n'.create_lnk($answer[2],$link[3]));
			break;

		case 50:
			output('`9Es mit mir aufzunehmen gleicht einer Odysse!
			`0`n`n`n'.create_lnk($answer[25],$link[17]).'
			`n`n'.create_lnk($answer[37],$link[5]).'
			`n`n'.create_lnk($answer[8],$link[6]).'
			`n`n'.create_lnk($answer[13],$link[7]).'
			`n`n'.create_lnk($answer[18],$link[8]));
			break;

		case 51:
			output('`9Mein Antlitz zeugt von edler Abstammung!
			`0`n`n`n'.create_lnk($answer[15],$link[9]).'
			`n`n'.create_lnk($answer[21],$link[18]).'
			`n`n'.create_lnk($answer[9],$link[10]).'
			`n`n'.create_lnk($answer[28],$link[1]).'
			`n`n'.create_lnk($answer[26],$link[3]));
			break;

		case 52:
			output('`9Ungh ... Memmen wie Dich vernasch\' ich zum Frühstück.
			`0`n`n`n'.create_lnk($answer[36],$link[2]).'
			`n`n'.create_lnk($answer[41],$link[1]).'
			`n`n'.create_lnk($answer[32],$link[10]).'
			`n`n'.create_lnk($answer[42],$link[9]).'
			`n`n'.create_lnk($answer[31],$link[18]));
			break;

		case 53:
			output('`9Ich habe Muskeln an Stellen, von denen Du nichts ahnst.
			`0`n`n`n'.create_lnk($answer[42],$link[12]).'
			`n`n'.create_lnk($answer[41],$link[1]).'
			`n`n'.create_lnk($answer[36],$link[2]).'
			`n`n'.create_lnk($answer[39],$link[3]).'
			`n`n'.create_lnk($answer[43],$link[4]));
			break;

		case 54:
			output('`9Gib auf oder ich zerquetsch\' dich wie eine lästige Mücke!
			`0`n`n`n'.create_lnk($answer[41],$link[5]).'
			`n`n'.create_lnk($answer[32],$link[14]).'
			`n`n'.create_lnk($answer[28],$link[6]).'
			`n`n'.create_lnk($answer[35],$link[7]).'
			`n`n'.create_lnk($answer[38],$link[8]));
			break;
		case 55:
			output('`9Meine Großmutter hat mehr Kraft als Du Wicht!
			`0`n`n`n'.create_lnk($answer[33],$link[3]).'
			`n`n'.create_lnk($answer[4],$link[4]).'
			`n`n'.create_lnk($answer[27],$link[13]).'
			`n`n'.create_lnk($answer[28],$link[5]).'
			`n`n'.create_lnk($answer[46],$link[6]));
			break;

		case 56:
			output('`9Nach diesem Spiel trägst Du den Arm in Gips!
			`0`n`n`n'.create_lnk($answer[32],$link[7]).'
			`n`n'.create_lnk($answer[29],$link[8]).'
			`n`n'.create_lnk($answer[14],$link[9]).'
			`n`n'.create_lnk($answer[34],$link[15]).'
			`n`n'.create_lnk($answer[5],$link[10]));
			break;

		case 57:
			output('`9Aargh ... ich zerreiße deine Hand in eine Million Fetzen!
			`0`n`n`n'.create_lnk($answer[21],$link[1]).'
			`n`n'.create_lnk($answer[28],$link[2]).'
			`n`n'.create_lnk($answer[18],$link[3]).'
			`n`n'.create_lnk($answer[40],$link[4]).'
			`n`n'.create_lnk($answer[44],$link[16]));
			break;

		case 58:
			output('`9Aaagh ... hey, schau mal da drüben!
			`0`n`n`n'.create_lnk($answer[33],$link[17]).'
			`n`n'.create_lnk($answer[40],$link[5]).'
			`n`n'.create_lnk($answer[8],$link[6]).'
			`n`n'.create_lnk($answer[20],$link[7]).'
			`n`n'.create_lnk($answer[21],$link[8]));
			break;

		case 59:
			output('`9Aargh ... ich werde deine Knochen zu Brei zermalmen.
			`0`n`n`n'.create_lnk($answer[28],$link[9]).'
			`n`n'.create_lnk($answer[41],$link[18]).'
			`n`n'.create_lnk($answer[39],$link[10]).'
			`n`n'.create_lnk($answer[45],$link[1]).'
			`n`n'.create_lnk($answer[25],$link[3]));
			break;

		case 60:
			output('`9Ich kenne Läuse mit stärkeren Muskeln.
			`0`n`n`n'.create_lnk($answer[28],$link[17]).'
			`n`n'.create_lnk($answer[17],$link[5]).'
			`n`n'.create_lnk($answer[34],$link[6]).'
			`n`n'.create_lnk($answer[22],$link[7]).'
			`n`n'.create_lnk($answer[35],$link[8]));
			break;

		case 61:
			output('`9Alle Welt fürchtet die Kraft meiner Faust.
			`0`n`n`n'.create_lnk($answer[3],$link[9]).'
			`n`n'.create_lnk($answer[36],$link[18]).'
			`n`n'.create_lnk($answer[46],$link[10]).'
			`n`n'.create_lnk($answer[43],$link[1]).'
			`n`n'.create_lnk($answer[26],$link[3]));
			break;

		case 62:
			output('`9Ungh ... gibt es auf dieser Welt eine größere Memme als dich?
			`0`n`n`n'.create_lnk($answer[33],$link[1]).'
			`n`n'.create_lnk($answer[45],$link[2]).'
			`n`n'.create_lnk($answer[18],$link[3]).'
			`n`n'.create_lnk($answer[40],$link[4]).'
			`n`n'.create_lnk($answer[35],$link[16]));
			break;

		case 63:
			output('`9Ungh ... Du bist das hässlichste Wesen, das ich jemals sah... grr.
			`0`n`n`n'.create_lnk($answer[43],$link[17]).'
			`n`n'.create_lnk($answer[17],$link[5]).'
			`n`n'.create_lnk($answer[37],$link[6]).'
			`n`n'.create_lnk($answer[15],$link[7]).'
			`n`n'.create_lnk($answer[1],$link[8]));
			break;

		case 64:
			output('`9Ungh ... viele Menschen sagen, meine Kraft ist unglaublich.
			`0`n`n`n'.create_lnk($answer[28],$link[9]).'
			`n`n'.create_lnk($answer[38],$link[18]).'
			`n`n'.create_lnk($answer[31],$link[10]).'
			`n`n'.create_lnk($answer[45],$link[1]).'
			`n`n'.create_lnk($answer[46],$link[3]));
			break;

		case 65:
			output('`9Ungh ... Ich hab\' mit diesen Armen schon Kraken bezwungen.
			`0`n`n`n'.create_lnk($answer[46],$link[17]).'
			`n`n'.create_lnk($answer[38],$link[5]).'
			`n`n'.create_lnk($answer[32],$link[6]).'
			`n`n'.create_lnk($answer[42],$link[7]).'
			`n`n'.create_lnk($answer[31],$link[8]));
			break;

		case 66:
			output('`9Ungh, ha ... sehe ich da Spuren von Angst in deinem Gesicht?
			`0`n`n`n'.create_lnk($answer[39],$link[9]).'
			`n`n'.create_lnk($answer[29],$link[18]).'
			`n`n'.create_lnk($answer[45],$link[10]).'
			`n`n'.create_lnk($answer[11],$link[1]).'
			`n`n'.create_lnk($answer[35],$link[3]));
			break;

		default: //Fehler
			output('`9Du weißt aber, was du hier tust, ja?
			`0`n`n`n'.create_lnk($answer[29],$link[5]).'
			`n`n'.create_lnk($answer[33],$link[7]).'
			`n`n'.create_lnk($c.'Ich verkaufe diese feinen Lederjacken.`0',$link[19]).'
			`n`n'.create_lnk($answer[25],$link[9]).'
			`n`n'.create_lnk($answer[43],$link[2]));
			break;
		}
		break;
	}

	case 'ergebnis':
	{
		output('`c`7Der `6Sieger `7steht fest.`n Es ist .........`n`c ');
		$piratpoints=$session['user']['specialmisc']%10;
		if ($piratpoints <5)
		{ //User gewinnt
			$piratbonus=max(5-$piratpoints,0); //für jeden Piratpunkt etwas vom Lohn abziehen
			$expplu = round($session['user']['experience'] * $piratbonus * 0.03);
			$session['user']['experience']+=$expplu;
			output('`c`b '.$session['user']['name'].'`b
			`n`7'.($session['user']['sex']?'Sie':'Er').' gewinnt somit `2'.$expplu.' `7Erfahrung und macht `4'.$piratbonus.' `7Piratenpunkte.`c`n');
			$buff = array('name'=>'`6Gesteigertes Ego`0','rounds'=>$piratbonus*12,'wearoff'=>'`5`bDein Höhenflug ist vorbei.`b`0','defmod'=>1.6,'roundmsg'=>'Dich kann nichts umhauen, dein EGO ist enorm!!','activate'=>'offense');
			$session['bufflist']['pirat']=$buff;
			addnews('`7'.$session['user']['name'].'`7 hat die Begegnung mit einem `9Piraten `7'.($piratbonus==5?'glänzend':'gut').' überstanden.');
			
			//Wenn alle Spieler gemeinsam 100 Piratenpunkte gemacht haben erhält man einen Extrabonus
			$piratbonus+=(int)getsetting('beleidgterpirat',0);
			if($piratbonus>99)
			{
				output('`n`n`0Nachdem der `9Pirat`0 deprimiert von dannen gezogen ist, erblickst du einen Pfahl, auf dem der `#Kopf des Navigators`0 steckt.
				`nDu weißt, dass dieser Kopf die Fähigkeit besitzt, dich einmal sicher durch das Labyrinth der Katakomben zu führen. Also nimmst du ihn mit.`n');
				item_add($session['user']['acctid'],'navikopf');
				$piratbonus=0;
			}
			savesetting('beleidgterpirat',$piratbonus);
		}
		else
		{ //Pirat gewinnt
			$userpoints=floor($session['user']['specialmisc']/10);
			$userbonus=max(5-$userpoints,0); //für jeden Userpoint etwas von der Strafe abziehen
			$expplu = round($session['user']['experience'] * $userbonus * 0.02);
			$session['user']['experience']-=$expplu;
			$session['user']['hitpoints'] =1;
			output('`c`9`bDER PIRAT`b `n`7Du verlierst deswegen `2'.$expplu.' `7Erfahrung und fast dein `4Leben`7.`c`n');
			$buff = array('name'=>'`6Demoralisation`0','rounds'=>$userbonus*12,'wearoff'=>'`5`bDeine Motivation kehrt zurück!`b`0','defmod'=>0.7,'roundmsg'=>'Du willst einfach nur nach hause.','activate'=>'offense');
			$session['bufflist']['pirat']=$buff;
			addnews('`9Ein Pirat `7hat '.$session['user']['name'].'`7 fast zu Tode beleidigt.');
		}
		$session['user']['turns'] --;
		$session['user']['specialinc']='';
		$session['user']['specialmisc'] =0;
		break;
	}

	case 'wald':
	{
		output('`3Du ignorierst den Piraten und gehst zurück in den Wald.');
		$session['user']['specialmisc'] =0;
		//$session['user']['turns'] --;
		$session['user']['specialinc']='';
		//addnews('`7 '.$session['user']['name'].'`7 war zu FEIGE, gegen einen `9Piraten`7 in einem Kampf ohne Waffen anzutreten.');
		break;
	}
	default:
	{
		output('`3Das ist ein ganz klares "Unentschieden".');
		$session['user']['specialmisc'] =0;
		$session['user']['specialinc']='';
		break;
	}
}

/** @noinspection PhpUndefinedVariableInspection */
if($session['user']['specialinc']>'' && $access_control->su_check(access_control::SU_RIGHT_DEV))
{
	output('`0`n`n<form action="forest.php?op=kampf2" method=POST>
	Cheatcode(1-66): <input type="text" name="dice">
	<input type="submit" class="button" value="OK">
	</form>');
	addnav('','forest.php?op=kampf2');
}
?>
