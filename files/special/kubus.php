<?php
// idea of baldawin @ www.rabenthal.de
// programmed by gargamel @ www.rabenthal.de

if (!isset($session))
{
	exit();
}

if ($_GET['op']=='')
{
	output('Du machst wie immer deinen alltäglichen Rundgang im Wald und hörst plötzlich in deiner unmittelbaren Nähe das Knacken eines zertretenen Zweiges. Als du dich - bereit für das Schlimmste - schnell in die Richtung des Geräusches drehst, erwartet dich eine angenehme Überraschung:
	`n`^'.($session['user']['sex']?'Ein wunderschöner junger Mann ':'Eine wunderschöne junge Frau ').'`0wirft dir stumm ein verführerisches Lächeln zu...
	`nEinen Moment überlegst du, was du davon halten sollst...
	`nDu hast '.($session['user']['sex']?'diesen Mann ':'diese Frau ').'noch nie gesehen und kennst nichtmal '.($session['user']['sex']?'seinen ':'ihren ').'Namen...`0');
	//abschluss intro
	if ($session['user']['sex']>0)
	{
		//frau
		addnav('Gib dich ihm hin','forest.php?op=hin');
		addnav('Lass ihn stehen','forest.php?op=weg');
	}
	else
	{
		addnav('Gib dich ihr hin','forest.php?op=hin');
		addnav('Lass sie stehen','forest.php?op=weg');
	}
	$session['user']['specialinc'] = 'kubus.php';
}

else if ($_GET['op']=='hin')
{
	output('Du zwinkerst '.($session['user']['sex']?'dem Fremden':'der Fremden').' zu und kurze Zeit später seid ihr beide auch schon im nächsten Gebüsch verschwunden...`n`n`0');
	$grenzwert = 70;
	if ($session['user']['charisma']==4294967295)
	{
		$grenzwert = 40;
	}
	$chance = e_rand(1,100);
	if ($chance < $grenzwert )
	{
		// positiv
		if($session['user']['exchangequest']==11)
		{
			redirect('exchangequest.php');
		}
		output('Als du wieder Herr deiner Sinne bist, ist '.($session['user']['sex']?'der mysteriöse Mann':'die mysteriöse Frau').' wie vom Erdboden verschluckt.
		`n\'Schade...\' denkst du, ziehst dich wieder an und setzt deinen Weg fort.
		`n`n`^Du fühlst dich großartig, darum bekommst du 2 Charmepunkte und regenerierst vollständig.`0');
		$session['user']['charm']+=2;
		if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
		{
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
		}
	}
	else
	{
		// negativ
		output('Während Eures rauschhaften Liebesspieles spürst du eine Veränderung an '.($session['user']['sex']?'ihm':'ihr').'... Du schließt deine Augen...
		`nAls du sie wieder öffnest, möchtest du nicht wahrhaben was du siehst:
		`n`n'.($session['user']['sex']?'Der Unbekannte ':'Die Unbekannte ').'starrt dich mit einer hässlichen Fratze an und verfällt in wildes Kichern! Scheinbar bist du einem '.($session['user']['sex']?'Inkubus ':'Sukkubus ').'auf den Leim gegangen! Nachdem du das Liebesspiel überstanden hast, verkriecht sich der Dämon schallernd lachend im Wald, auf das ihm '.($session['user']['sex']?'die nächste unvorsichtige Abenteuerin ':'der nächste unvorsichtige Abenteurer ').'über den Weg läuft...
		`n`n`^Du fühlst dich benutzt und ausgelaugt, daher verlierst du 2 Charmepunkte und bist sehr schwach!`0');
		$session['user']['charm']=max(0,$session['user']['charm']-2);
		$session['user']['hitpoints']=1;
	}
	$session['user']['specialinc']="";
}

else
{
	// einfach weitergehen
	output('Ein wenig komisch fühlst du dich schon... daher verneigst du dich nur höflich und wünschst '.($session['user']['sex']?'dem Mann':'der Frau').' einen angenehmen Tag und gehst weiter deines Weges.`0');
	$session['user']['specialinc']='';
}
?>
