<?php
// idea of gargamel @ www.rabenthal.de
// für Atrahor als Knast-Einweiser erweitert + kleine Modifikationen von Salator

if (!isset($session))
{
	exit();
}

output('`3Auf deinem Weg durch den Wald triffst du auf einen Waldhüter.`0
`n`nEr trägt an seiner Uniform das Wappen von `6'.getsetting('townname','Atrahor').'`0. Scheint amtlich zu sein....
`n`nDer Waldhüter weist dich darauf hin, dass er dafür zuständig ist, im Wald für Ordnung zu sorgen. Und da er noch in der Probezeit ist, arbeitet er wirklich sehr genau!`n`n');

$row_extra=user_get_aei('sentence');
if($row_extra['sentence']> getsetting('locksentence',4))
{ //ab in den Knast
	output('`6"Aha, '.$session['user']['name'].'`6, drückt sich schon seit Tagen vor der Kerkerstrafe. Geht '.($session['user']['sex']?'sie':'er').' freiwillig mit, oder muss ich die Stadtwache verständigen?"
	`nMit diesen Worten überreicht er dir ein Schreiben, welches dich dazu auffordert, bis zum Tagesende die Haftstrafe anzutreten.`n');
	addnav('K?In den Kerker mitgehen','prison.php?op=imprison2');
	addnav('W?In den Wald flüchten','forest.php'); //man bleibt bis zum newday frei, solange man diverse Orte meidet
	$session['user']['imprisoned']=max($row_extra['sentence'],getsetting("maxsentence",5));
	user_set_aei(array('sentence'=>0));
	systemmail($session['user']['acctid'],'`$Vollzugsbescheid!`0','Gegen Dich liegt ein Haftbefehl über '.$session['user']['imprisoned'].' Tage vor. Du hast bis zum Tagesende Zeit, Deine Strafe anzutreten. Wenn Du der Aufforderung nicht nachkommst wird Dich die Stadtwache holen.');
	debuglog('wurde vom Waldhüter in den Kerker eingewiesen');
}
else
{
	switch (e_rand(1,4))
	{
	case 1: // Weg
		{
		$gold = $session['user']['level']*10;
		$goldb = $session['user']['level']*12;
		$geb = $goldb - $gold;
		output('`6"Aha, '.$session['user']['name'].'`0 `6auf frischer Tat ertappt!"`0
		`n`nDu weißt zunächst gar nicht, was er meint, aber dann wird es dir schnell klar.
		`n`n`6"Du bist hier mitten in einer Schonung! Das wurde hier alles extra angepflanzt und liebevoll gepflegt, und Du latschst hier durch?"`0 regt sich der Waldhüter auf. `6"Dafür muss ich eine Verwarnung aussprechen, was ich hiermit gerne tue"`0 fährt der Waldhüter fort und weist auch gleich auf die Kosten hin:`^ '.$gold.' Goldstücke.`n`n`0');
		if ($session['user']['gold'] >$gold )
		{
			output('Der Betrag wird sofort vom Waldhüter kassiert.`0');
			$session['user']['gold']-= $gold;
		}
		else if ($session['user']['goldinbank'] > $goldb )
		{
			output('Da du nicht genug Gold dabei hast, wird der Waldhüter eine Zahlungsforderung an die Bank senden. Weil er nun mehr Arbeit mit dir hat, kommen noch`^ '.$geb.' Gold`0 Gebühren hinzu, so dass die Bank dir insgesamt '.$goldb.' Gold belastet.`0');
			$session['user']['goldinbank']-=$goldb;
		}
		else if (($session['user']['gold'] + $session['user']['goldinbank']) > $goldb )
		{
			output('Da du die Strafe nicht gleich komplett zahlen kannst, sendet der Waldhüter eine Zahlungsforderung über den Restbetrag an die Bank. Weil er nun mehr Arbeit mit dir hat, kommen noch`^ '.$geb.' Gold`0 Gebühren dazu.`0');
			$goldb-= $session['user']['gold'];
			$session['user']['gold']=0;
			$session['user']['goldinbank']-= $goldb;
		}
		else
		{
			output('Auch wenn du zur Zeit nicht genug Geld hast, um die Strafe zu bezahlen, wirst du nicht darum herumkommen. Der Waldhüter schickt eine Zahlungsforderung an die Bank, die dir automatisch einen Kredit gewährt.
Weil der nun mehr Arbeit mit dir hat, kommen noch`^ '.$geb.' Gold`0 Gebühren dazu.`0');
			$goldb-= $session['user']['gold'];
			$session['user']['gold']=0;
			$session['user']['goldinbank']-= $goldb;
		}
		output('`n`n`2Der Waldhüter rät dir zum Abschied, zukünftig auf den Wegen zu bleiben.`0');
		break;
		}
		
	case 2: // Alkoholkontrolle
		{
		output('`6"In letzter Zeit haben wir verstärkt Probleme mit Trunkenbolden, die nichts als Ärger machen."`0 eröffnet der Waldhüter das Gespräch. "`6Ich werde daher einen amtlichen Alkoholtest mit Dir durchführen"`0 informiert er dich.
		`n`n`8Du musst ihn kräftig anhauchen.`n`n`0');
		if ($session['user']['drunkenness'] >= 66 )
		{
			output('`6"Oh mann... Du hast ja eine kräftige Fahne. Kommst Du direkt aus der Taverne?"`0 fragt er dich. Du weißt keine rechte Antwort, denn er hat ja recht.
			`n`n`QDamit andere Stadtbewohner nicht von dir belästigt werden, vertreibt er dich für heute aus dem Wald. Du kannst morgen wieder Kämpfen.`0');
			$session['user']['turns']=0;
		}
		else if ($session['user']['drunkenness'] >= 25 )
		{
			output('`6"Erzähl mir bloß nicht, dass Du nichts getrunken hast!"`0 hält dir der Waldhüter vor. "`6Aber Du scheinst nur einen kleinen Glimmer zu haben. Ruhe Dich etwas aus, dann kannst Du weiterziehen."`0 Der Waldhüter belässt es bei dieser Ermahnung und verschwindet.
			`n`n `QDu schläfst 3 Runden lang und ziehst dann weiter.`0');
			$session['user']['turns']-=3;
		}
		else
		{
			output('`6"Du gehörst offensichtlich zu den ehrenwerten Stadtbewohnern"`0 lobt dich der Waldhüter für deine Nüchternheit.
			`n`n`9Weil du so positiv aufgefallen bist, bekommst du einen Charmepunkt.`0');
			$session['user']['charm']+=1;
		}
		break;
		}
		
	case 3: // Reitweg
		{
        /** @noinspection PhpUndefinedVariableInspection */
        if ($session['user']['hashorse']>0 && $playermount['mountcategory']=='Reittiere')
		{
			output('`$"Halt! Sofort HALT!"`0 brüllt dich der Waldhüter an. Fragend deutet er neben dich. "'.$playermount['mountname'].'" entgegnest du knapp.
			`nDer Waldhüter weist dich nun ausführlich darauf hin, dass das Reiten nur auf den dafür besonders gekennzeichneten Wegen erlaubt ist. "`6Und hier NICHT!"`0 schließt sein Vortrag.
			`n`nAuf der Suche nach einem Reitweg `Qverlierst du einen Waldkampf.`0');
			$session['user']['turns']-=1;
		}
		else if ($session['user']['hashorse']>0 )
		{
			output('`6"'.($session['user']['sex']?'Gute Frau, ':'Guter Mann, ').'ein '.$playermount['mountname'].'`6 kann hier nicht frei herumrennen!"`0 belehrt dich der Waldhüter.
			`n`nDu siehst ein, dass das andere Bewohner erschrecken könnte und kommst daher mit einer Ermahnung davon.
			`n`n`7Du verlierst einen Charmepunkt.`0');
			$session['user']['charm']-=1;
		}
		else
		{
			output('`8Der Waldhüter mustert dich mit prüfenden Blicken. Aber er hat offenbar nichts zu beanstanden, denn er geht wortlos weiter.
			`n`n `^Du erhälst einen Charmepunkt.`0');
			$session['user']['charm']+=1;
		}
		break;
		}
		
	case 4: // Verschmutzung
		{
		output('`6"Schön das Du Deine Waffe säuberst. Nur wirf gefälligst das gebrauchte Tuch nicht in den Wald!"`0 herrscht dich der Waldhüter an.
		`n`nWeil du einsichtig bist, lässt er dich gehen.
		`n`n`2Für die Waldverschmutzung verlierst du einen Charmepunkt.`0');
		$session['user']['charm']-=1;
		break;
		}
	}
}
?>
