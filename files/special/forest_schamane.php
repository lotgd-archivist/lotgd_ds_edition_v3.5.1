<?php

//Version 2.0
//©Fingolfin
//12.04.2007
//Idee: Egoman, Fingolfin

if (!isset($session))
{
	exit();
}

$session['user']['specialinc']=basename(__FILE__);

page_header('Der Schamane');

$str_output = '';
$str_output .= get_title('Der Schamane');

switch($_GET['op'])
{
	//Wird zu Beginn aufgerufen
	case '':

		$str_output .= '`tDu befindest dich im Wald auf der Suche nach bösen Kreaturen, als es um dich herum immer dunkler wird.
		Plötzlich entdeckst du vor dir zwischen den Bäumen etwas Helles. Als du näher kommst fällt dir auf,
		dass es sich um eine kleine heruntergekommene Holzhütte handelt.
		`n`i`yDies muss die Hütte des Schamanen sein, von der die Alten in der Stadt immer erzählen.`i
		`n`tHast du genügend Mut um anzuklopfen?';

		addnav('Anklopfen');
		addnav('Ja klar','forest.php?op=enter');
		addnav('Nein, sofort weg hier!','forest.php?op=escape&case=knock');
		break;

		//Wird bei der Flucht ausgeführt
	case 'escape':

		switch($_GET['case'])
		{
			//Flucht vor dem Klopfen
			case 'knock':

				$str_output .= '`tDu bekommst es mit der Angst zu tun und rennst hektisch von der Hütte weg. Nach einer Ewigkeit findest du den Weg im Wald wieder.`n';
				
				switch (e_rand(1,2))
				{
					case 1:
						{
							$str_output .= '`yDer lange Weg kostet dich 3 Waldkämpfe. Unterwegs stürzt du in einen Graben und verlierst die Hälfte deiner Lebenspunkte.';
			
							$session['user']['turns']=max(0,$session['user']['turns']-3);
							$session['user']['hitpoints'] *= 0.5;
							break;
						}
				}
				$session['user']['specialinc']= '';
				//addnav('Zurück in den Wald','forest.php');
				break;

				//Flucht nach dem Klopfen
			case 'door':

				$str_output .= '`tPanisch versuchst du, dich von der Tür zu entfernen, doch irgendeine unsichtbare Kraft scheint dich vor der Tür festzuhalten.
				Nur mit einer enormen Anstrengung schaffst du es, dich von der Tür fortzubewegen und fliehst durch den Wald.
				`n`yDu hast ganze 2 Waldkämpfe verloren und die Anstrengungen haben fast deine kompletten Lebenspunkte verbraucht.
				Als du erschöpft am Waldrand ausruhst fällt dir auf, dass dir unterwegs einige Goldstücke abhanden gekommen sein müssen.';

				$session['user']['turns']=max(0,$session['user']['turns']-2);

				$session['user']['hitpoints'] = 1;
				$session['user']['gold']=max(0,$session['user']['gold']-80);
				$session['user']['specialinc']= '';

				//addnav('Zurück in den Wald','forest.php');
				break;

				//Flucht nach dem Betreten der Hütte
			case 'inside':

				switch(e_rand(1,7))
				{
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:

						//Flucht gelingt
						$str_output .= '`tAls du von dem Ritual hörst bist du so gar nicht begeistert von der Idee, als Versuchskaninchen zu dienen.
						Du versuchst dich mit Wortgewandheit herrauszureden, doch der alte Mann schaut dich immer böser an.
						Mit einem Mal hältst du seinen Blick nicht mehr aus und rennst Hals über Kopf davon in den Wald.';

						//Wenn Userturn größer 2 Verlust von 3 turns
						if($session['user']['turns']>2)
						{
							$str_output .= '`n`yDu verirrst dich unterwegs und findest erst wieder nach langem Suchen zurück auf den Pfad, von dem du gekommen bist.';

							$session['user']['turns'] -= 3;
						}
						//sonst Verlust der restlichen turns + 100 Gold
						else
						{
							$str_output .= '`4Du verirrst dich, und als du in einen dunkleren Teil des Waldes kommst bleibst du an einer Wurzel hängen und fällst der Länge nach hin.
							`n`tDu verlierst ein wenig Gold und brauchst den Rest des Tages um den Weg aus dem Wald wiederzufinden.';

							$session['user']['turns'] = 0;
							$session['user']['gold']=max(0,$session['user']['gold']-100);
						}

						$session['user']['specialinc']= '';

						//addnav('Weiter','forest.php');
						break;

					case 6:
					case 7:

						//Flucht gelingt nicht -> Tod
						$str_output .= '`tDu hörst das Wort Ritual und bekommst panische Angst. Langsam drehst du dich um und versuchst dich herauszuschleichen,
						doch der alte Mann beginnt bereits unverständliche Silben zu sprechen. Du wirst immer langsamer und kommst der Türe kaum näher.
						Dir wird schwarz vor Augen und du brichst tot zusammen. Das letzte was du hörst:
						`n`yHmmmmm - war wohl nichts...
						`n`n`yDu kannst morgen wieder kämpfen.';

						addnews($session['user']['name'].'`y starb, als '.($session['user']['sex']?'sie':'er').' vor dem Schamanen floh.');
						killplayer();
						$session['user']['specialinc']='';

						break;
				}
				break;
		}
		break;

		//Betreten der Hütte
	case 'enter':

		switch(e_rand(1,5))
		{
			case 1:
			case 2:
			case 3:

				//Klopfen erfolgreich, Möglichkeit des Rituals
				$str_output .= '`tDu nimmst deinen ganzen Mut zusammen und klopfst gegen die schwere Tür. `i`yPoch Poch`i
				`n`tDu wartest eine Weile -
				als niemand aufmacht beschließt du wieder zu gehen, doch in diesem Moment öffnet sich die Tür und ein kleiner, schmächtiger und sehr alter Mann schaut dir entgegen.
				`n`n`yHallo, ich habe zwar gerade wenig Zeit, aber wenn du möchtest kannst du kurz herein kommen.';

				addnav('Hineingehen!','forest.php?op=follow');
				addnav('Nein, bloß weg hier!','forest.php?op=escape&case=door');
				break;

			case 4:
			case 5:

				//Klopfen misslingt, exp + turn Verlust
				$str_output .= '`tGerade als du an die Tür klopfen willst, überkommt dich ein komisches Gefühl und alles um dich herum wird dunkel.
				Als du wieder aufwachst, befindest du dich im Wald und kannst dich an nichts mehr erinnern.
				`n`yDu hast etwas Erfahrung und einen Waldkampf verloren.';

				$session['user']['turns'] -= 1;
				$session['user']['experience'] *= 0.95;
				$session['user']['specialinc']='';

				//addnav('Weiter','forest.php');
				break;
		}
		break;

	case 'follow':

		//Betreten der Hütte kostet 1 turn, Möglichkeit der erneuten Flucht
		$str_output .= '`tDu folgst dem Schamanen in seine Hütte. Es ist dunkel und modrig, in einem Kamin brennt ein Feuer und es riecht nach seltsamen Kräutern.
		Der Schamene dreht sich zu dir um und fragt:
		`n`yIch bin gerade dabei ein Ritual durchzuführen, möchtest du daran teilnehmen?
		`n`n`yEs ist bereits einige Zeit vergangen und du hast einen Waldkampf vertrödelt.
		Traust du dich, das Ritual mitzumachen?';

		$session['user']['turns'] -= 1;

		addnav('Mitmachen','forest.php?op=ritual');
		addnav('Bloß raus hier!','forest.php?op=escape&case=inside');
		break;

	case 'ritual':

		switch(e_rand(1,14))
		{
			case 1:
			case 2:

				//Schamane tötet den Spieler
				$str_output .= '`tDer Schamane beginnt sinnlose Worte vor sich hinzureden und dir wird dabei immer schlechter.
				Dir wird schwindelig und all deine Sinne setzten aus, nach kurzer Zeit fällst du auf den Boden.
				Das letzte was du hörst, is das hinterlistige Kichern des Schamanen.
				`n`n`yDu bist tot.';

				addnews($session['user']['name'].'`y starb, als '.($session['user']['sex']?'sie':'er').' am Ritual des Schamanen teilnahm.');
				killplayer();
				$session['user']['specialinc']='';

				break;

			case 3:
			case 4:
			case 5:
			case 6:
			case 7:

				//Das Ritual gelingt, Gewinn: Gold + turns
				$str_output .= '`tDer Schamane beginnt mit einem seltsamen Singsang und du fühlst wie deine Taschen plötzlich immer schwerer werden.
				Nach kurzer Zeit hört er damit auf und schreit dich barsch an:
				`n`yVerschwinde aus meinem Haus, ich will dich hier nicht mehr sehen!
				`n`tDu bist so erschrocken, dass du nicht fragst warum und wieso, sondern einfach Hals über Kopf aus der Hütte rennst.
				`yDraußen angekommen fasst du in deine Taschen und findest 250 Goldstücke darin. Durch den Gewinn ermuntert verspührst du neue Kraft in dir.';

				$session['user']['turns'] += 2;
				$session['user']['gold'] += 250;
				$session['user']['specialinc'] = '';

				//addnav('Weiter','forest.php');
				break;

			case 8:
			case 9:

				//Schamane tötet den Spieler
				$str_output .= '`tDer Schamane dreht sich mit einen hinterlistigen Grinsen zu dir um und spricht mehrere barsche Laute aus.
				Du hast keine Chance zu reagieren und spürst nicht mehr was mit dir passiert.
				`n`n`yDu bist tot.';

				addnews($session['user']['name'].'`y starb, als '.($session['user']['sex']?'sie':'er').' am Ritual des Schamanen teilnahm.');
				killplayer();
				$session['user']['specialinc']='';

				break;

			case 10:
			case 11:
			case 12:
			case 13:
			case 14:

				//Das Ritual gelingt, Gewinn: Erfahrung
				$str_output .= '`tDer Schamane geht auf dich zu und sticht dir ruckartig etwas in den Bauch.
				Du verziehst dein Gesicht, in dem Glauben schon tot zu sein, doch als du nach unten schaust, siehst du nur wie er seinen Zeigefinger in deinen Bauch drückt.
				`n`yNicht so schreckhaft sein, '.($session['user']['sex']?'Madl':'Jungchen').', und jetzt geh.
				`n`tDu bist immer noch ein wenig benommen und verlässt die Hütte.
				`n`yDu fühlst dich erfahrener als zuvor.';

				$session['user']['experience'] *= 1.08;
				$session['user']['specialinc']='';

				//addnav('Zurück in den Wald','forest.php');
				break;
		}
		break;
}

output($str_output);
//page_footer();

?>