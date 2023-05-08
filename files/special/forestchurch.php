<?php
//forestchurch.php - Specialevent für Auserwählte
//Idea: Valas
//Autor: Salator (salator@gmx.de)
//Date: 16.2.07
//benötigt ein Item blackquill welches für ca 10 Gems verkaufsfähig ist

$session['user']['specialinc'] = 'forestchurch.php';

if ($_GET['op']=='')
{ //Start
	if($session['user']['marks']<CHOSEN_FULL)
	{ //nicht Auserwählt
		$session['user']['specialinc'] = '';
		redirect('forest.php?op=search');
	}
	else
	{ //Auserwählt
		output('`c`@Eine unbekannte Lichtung`0`c
		`n`n`2Du gehst deinen gewohnten Weg durch den Wald, Ausschau haltend nach einigen Kreaturen, welche es zu Beseitigen gilt. Plötzlich fällt dir ein dir bislang unbekannter Pfad auf, welcher augenscheinlich zu einer kleinen Lichtung führt.
		`nOhne viel darüber nachzudenken folgst du diesem Weg, bis der Sonnenschein zwischen den Ästen den Weg zu dir findet. In der Hoffnung etwas Interessantes zu entdecken lässt du deinen Blick umherschweifen... mit Erfolg!
		`nAm Rande der Lichtung entdeckst du eine kleine Kirche, von welcher du bislang niemals etwas gehört hast. Gepackt von der Neugier bewegst du dich vorsichtig auf sie zu und als du letztendlich durch die offenstehende Tür gegangen bist kannst du erkennen, dass das Dach des Gebäudes teilweise eingestürzt ist. Direkt unter dieser Stelle ist auch der Holzboden eingebrochen und auf der darunterliegenden Erde ist ein kleines Blumenbeet zu erkennen, welches durch das zerstörte Dach vom Sonnenlicht erhellt wird.
		`nDu überlegst ob du dir das etwas genauer anschauen sollst. ');
		if ($session['user']['prefs']['noimg']==0) {
			output('`n`n`c<img src="./images/waldkirche.jpg">`c');
		}
		addnav('Dem Blumenbeet nähern','forest.php?op=goon');
		addnav('Zurück in den Wald','forest.php?op=leave');
		//if(access_control::is_superuser()) addnav('Testkampf','forest.php?op=test');

	}
}

elseif ($_GET['op']=='goon')
{ //weitergehen, der Fremde kommt
	output('`2Ruhigen Schrittes und nichts böses denkend begibst du dich in Richtung des Blumenbeets. Kurz davor bleibst du stehen und betrachtest die wunderschönen Blumen in den verschiedensten Farben, als die Ruhe plötzlich unterbrochen wird.
	`nDurch die zerstörte Stelle im Dach landet in rasender Geschwindigkeit ein dir unbekannter Mann inmitten des Blumenbeets und wirbelt dabei Mengen von Blütenblättern auf. Vor Schreck springst du einige Schritte zurück und starrst fassungslos auf den Fremden. Mit dem linken Bein kniet er auf dem Boden, das andere stützt er mit dem Fuß auf und sein Blick ist dem Boden gewidmet.
	`n"`6Wer... bist Du?`2" stotterst du unsicher, als der Fremde den Blick hebt und seine selten langen weißen Haare den Blick auf sein Gesicht frei geben. Eine Antwort erhältst du vorerst nicht, pures Schweigen geht von deinem Gegenüber aus, ehe er sich langsam erhebt, wodurch sein schwarzer Mantel besser zur Geltung kommt.
	`n"`^Du bist also auserwählt...`2" spricht er kühl in monotoner Stimmlage, eher feststellend als fragend. "`^Dann wollen wir mal sehen, ob Du Deiner Male würdig bist.`2" fügt er schließlich noch hinzu, als eine äußerst lange Masamune wie aus dem Nichts in seiner rechten Hand erscheint.');
    $badguy = array(
	"creaturename"=>'`4Mysteriöser Mann`0'
	,"creaturelevel"=>$session['user']['level']
	,"creatureweapon"=>'Masamune'
	,"creatureattack"=>$session['user']['attack']
	,"creaturedefense"=>$session['user']['defence']
	,"creaturehealth"=>((int)$session['user']['maxhitpoints']/100)*100+50
	,"diddamage"=>0);

	$atkflux = e_rand(0,$session['user']['dragonkills']/5);
	$defflux = e_rand(0,($session['user']['dragonkills']/5-$atkflux));
	$hpflux = ($session['user']['dragonkills']/5 - ($atkflux+$defflux)) * 5;
	$badguy['creatureattack']+=$atkflux;
	$badguy['creaturedefense']+=$defflux;
	$badguy['creaturehealth']+=$hpflux;
	$session['user']['badguy']=createstring($badguy);

	addnav('K?Auf in den Kampf','forest.php?op=fight');
	addnav('Wegrennen','forest.php?op=flee');
}

elseif ($_GET['op']=='blood')
{ //nach Niederlage Lebenskraft opfern
	output('Du erinnerst dich daran, wie schwer es war, die Male zu bekommen und opferst lieber 15% deiner Lebenskraft.');
	$session['user']['maxhitpoints']*=0.85;
	$session['user']['hitpoints']=$session['user']['maxhitpoints']*0.1;
	$session['user']['specialinc'] = '';
}

elseif ($_GET['op']=='marks')
{ //nach Niederlage Male opfern
	$row_extra=user_get_aei('bloodchampdays');
	if(($session['user']['marks'] & CHOSEN_BLOODGOD) ==0)
	{
		output('Dir wurde das Mal der Erde genommen.');
        /** @noinspection PhpUndefinedVariableInspection */
        $Char->setBit(CHOSEN_EARTH,$Char->marks,0);
	}
	elseif(($session['user']['marks'] & CHOSEN_BLOODGOD) && $row_extra['bloodchampdays']==0)
	{
		output('Dir wurde das Zeichen des Blutgottes genommen.');
        /** @noinspection PhpUndefinedVariableInspection */
        $Char->setBit(CHOSEN_BLOODGOD,$Char->marks,0);
	}
	elseif($row_extra['bloodchampdays']>0)
	{
		output('Der Fremde fängt schallend an zu lachen: "`tDu elender Schwächling hast ja noch einen Kampf mit dem Champ des Blutgotts offen. Dieses Spektakel will ich mir nicht entgehen lassen. Sieh zu dass Du verschwindest!`&"');
	}
	else
	{
		output('Hier stimmt etwas nicht. Irgendwie sind deine Male durcheinandergeraten. Schreibe bitte eine Anfrage.');
	}
	$session['user']['hitpoints']=$session['user']['maxhitpoints']*0.1;
	$session['user']['specialinc'] = '';
}
elseif ($_GET['op']=='fight')
{
	$battle=true;
}

elseif ($_GET['op']=='run')
{
	output('`%Zu spät, du hattest deine Chance!`0`n');
	$battle=true;
}

elseif ($_GET['op']=='test')
{ //Kampf testen
	$session['user']['specialmisc']='forestchurch.test';
	redirect ('forest.php?op=goon');
}

elseif ($_GET['op']=='leave')
{ //Event verlassen
	$session['user']['specialinc'] = '';
	output('`2Du machst dir nichts aus zerfallenen Kirchen und kehrst in den Wald zurück.');
}

elseif ($_GET['op']=='flee')
{ //aus dem Kampf fliehen
	$session['user']['specialinc'] = '';
	output('`2Das sieht dir dann doch etwas `bzu`b mysteriös aus. Du beschließt, schnell von hier zu verschwinden.');
	if (($session['user']['marks'] & CHOSEN_BLOODGOD) && ($session['user']['level']>2) && (e_rand(1,5)==3))
	{ //chance daß einen der Blutgott holt
		output('`n`4Deine Feigheit spricht sich aber in Götterkreisen schnell herum. Du wirst Konsequenzen erwarten müssen.');
		user_set_aei(array('bloodchampdays' =>1));
		systemmail($session['user']['acctid'],'`$Von: Blutgott!`0','`&Sterblicher!`nWisse dass ich, der Blutgott, deiner überdrüssig geworden bin! Ich fordere dich auf, die Feste der Auserwählten aufzusuchen und dich im Kampf gegen meinen Champion als würdig zu zeigen!`nDu hast 3 Tage Zeit. Solltest du dieser Herausforderung nicht nachkommen, so betrachte unseren Pakt als nichtig!');
	}
}

else
{ //undefinierte Aktion
	output('Du weißt nicht wie du hier her gekommen bist, denkst aber, dass es besser wäre diesen Ort schnell zu verlassen.');
}

if ($battle)
{
	include_once ('battle.php');
	if ($victory)
	{
		if($session['user']['specialmisc']=='forestchurch.test')
		{
			output('`n`&Gewonnen mit '.$session['user']['hitpoints'].' LP Rest');
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			$session['user']['specialmisc']='';
			$session['user']['specialinc']='';
		}
		elseif($session['user']['exchangequest']==27)
		{
			redirect('exchangequest.php');
		}
		else
		{
			$experience=round($session['user']['experience']*0.1);
			headoutput('`c`b`^Sieg!`0`b`c`n
			`2Als du zum finalen Schlag ausholen willst springt der Fremde in die Luft, wo er von einem schwarzen Flügel eingehüllt wird. "`^Bemerkenswert... doch du bist nicht der, nachdem ich suchte...`2" sind die letzten Worte, die du von ihm hörst, ehe sich der Flügel in einzelne Federn auflöst und von dem Mann, der gerade noch hinter jenen verschwand, keine Spur mehr übrig bleibt. Verwirrt machst du kehrt, verlässt diesen Ort und kehrst in den Wald zurück...
			`n`n`&Du erhältst 1 permanenten Lebenspunkt.
			`nDeine Abwehrkraft steigt um 1 Punkt.
			`nDeine Erfahrung steigt um '.$experience.' Punkte.
			`nDu hast eine der schwarzen Federn eingesteckt.
			`n`n<hr>`n');
			$badguy=array();
			$session['user']['badguy']='';
			$session['user']['specialinc'] = '';
			$session['user']['maxhitpoints']++;
			$session['user']['defence']++;
			$session['user']['experience']+=$experience;
			item_add($session['user']['acctid'],'blackquill');
		}
	} //end Sieg

	elseif($defeat)
	{

		if($session['user']['specialmisc']=='forestchurch.test')
		{
			output('`n`&Verloren, badguy hat '.$badguy['creaturehealth'].' LP Rest');
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			$session['user']['specialmisc']='';
			$session['user']['specialinc']='';
		}
		else
		{
			headoutput('`c`b`$Niederlage!`0`b`c`n
			`&Der mysteriöse Fremde hat dich geschlagen!
			`n`n`4Als du am Boden liegst hält dieser seine Masamune auf deine Brust und stellt dich vor folgende Entscheidung:`n
			"Ich habe Dich besiegt und fordere meinen Tribut. Entweder Du opferst 15% Deiner Lebenskraft oder ich nehme Dir eins deiner Male."
			`n`n<hr>`n');
			addnews('`@'.$session['user']['name'].'`t hat sich als Auserwählter unwürdig erwiesen und die Niederlage teuer bezahlt.');
			$badguy=array();
			$session['user']['badguy']='';
			$session['user']['hitpoints']=1;
			addnav('Lebenskraft opfern','forest.php?op=blood');
			addnav('Male opfern','forest.php?op=marks');
		}
	} //end Niederlage
	else
	{
		fightnav();
	}

}
?>