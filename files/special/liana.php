<?php
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($_GET['op']=='')
{
	output('Beeindruckt schaust du nach oben. Aus den gewaltigen Baumkronen
	hier hängen viele grosse `2Lianen`0 herunter. Schön und friedlich sieht es aus.`0');
	//abschluss intro
	addnav('Lianen ansehen','forest.php?op=look');
	addnav('Klettern und schwingen','forest.php?op=use');
	addnav('Weitergehen','forest.php?op=cont');
	$session['user']['specialinc'] = 'liana.php';
}

else if ($_GET['op']=='look')
{
	output('Du trittst näher um dir die `2Lianen`0 aus der Nähe anzusehen. Du greifst
	dir das Ende einer besonders hübschen Liane, atmest den frischen Pflanzenduft
	ein und testest mal die Reißfestigkeit.`n`n`0');
	$chance = e_rand(1,100);
	if ( $chance < 30 ) {
		output('`2Plötzlich scheint die Liane lebendig zu werden.`0 Sie umschlingt dich
		und auch die anderen Lianen scheinen sich plötzlich zu regen.`n
		`2Sie halten dich fest!`0`n`n');
		switch ( e_rand (1,3) )
		{
			case 1:
			output('Mit ganzer Kraft reisst du dich los und verschwindest.`0');
			break;
			
			case 2:
			output('Du bist geschockt und benötigst einen Moment, bevor du dich
			losreissen kannst.`n
			`@Du verlierst einen Waldkampf.`0');
			$session['user']['turns']--;
			break;
			
			default:
			output('Ein eiskalter Schauer jagt über deinen Rücken und du bist
			geschockt. Du versuchst dich loszureissen und erst mit letzter Kraft
			gelingt es dir.`n
			`@Du verlierst einen Waldkampf und die Hälfte deiner Lebenspunkte.`0');
			$session['user']['turns']--;
			$session['user']['hitpoints'] = ceil ( $session['user']['hitpoints'] / 2 );
			break;
		}
	}
	else
	{
		output('Plötzlich schwingen die Lianen zur Seite, es schaut aus, also ob
		sich ein Vorhang öffnet. `2Dadurch wird ein schmaler Pfad freigegeben, den
		du mutig entlanggehst.`n`n`0');
		switch ( e_rand(1,3) ) {
			case 1:
			output('Der Pfad ist eine Abkürzung in einen anderen Teil des Waldes.
			Du gewinnst Zeit und kannst deswegen heute `teinen zusätzlichen Waldkampf`0
			absolvieren.`0');
			$session['user']['turns']++;
			break;
			
			case 2:
			output('Der Pflanzenduft, den du schon an der Liane gerochen hast, wird
			intensiver. Und er scheint eine positive Wirkung zu haben: `9Du regenerierst
			vollständig.`0');
			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			break;
			
			default:
			output('Du bist dir recht sicher, dass hier lange niemand gegangen ist.
			Deshalb bist du besonders wachsam und entdeckst so `^ein wenig Gold am
			Wegesrand.`0');
			$session['user']['gold']+= 100;
			break;
		}
	}
	$session['user']['specialinc']='';
}

else if ($_GET['op']=='use')
{
	output('Du prüfst mit einigen kräftigen Rucken die Festigkeit der `2Lianen`0. Für
	dich fühlt sich alles fest und sicher an, und so beginnst du, eine Liane
	raufzuklettern.`n`n`0');
	switch ( e_rand(1,4) )
	{
		case 1:
		output('Leider bist du kein Lianen-Experte und so hast du die Festigkeit
		völlig falsch eingeschätzt. `2Die Liane gibt nach und du fällst krachend zu
		Boden. Durch deine Verletzung verlierst du viele Lebenspunkte.`0');
		$session['user']['hitpoints'] = ceil ( $session['user']['hitpoints']*0.15 );
		break;
		
		case 2:
		output('Dich packt sportlicher Ehrgeiz und du probierst, von Liane zu Liane
		zu schwingen. Nach ein paar Versuchen gelingt es dir. Nun kannst du dieses
		Waldstück sehr zügig durchqueren, `2dadurch gewinnst du Zeit für einen zusätzlichen
		Waldkampf.`0');
		$session['user']['turns']++;
		break;
		
		case 3:
		output('Dich packt sportlicher Ehrgeiz und du probierst, von Liane zu Liane
		zu schwingen. Nach ein paar Versuchen gelingt es dir und mit kindlicher Freude
		vergnügst du dich in luftiger Höhe.`n
		Glücklich ziehst du weiter.`0');
		break;
		
		default:
		$exp = round ( $session['user']['experience']*0.05 );
		output('Mühelos kletterst du an der Liane in die Baumkrone hinauf. Du hast
		von dort oben einen wunderbaren Überblick über den Wald. Du prägst dir den
		Verlauf einiger Wege ein und kannst das Wissen für dich nutzen.`n
		`2Du erhältst '.$exp.' Erfahrungspunkte.`0');
		$session['user']['experience']+= $exp;
		break;
	}
	$session['user']['specialinc']='';
}

else
{ // einfach weitergehen
	output('`@Du verlässt dieses Waldstück, die Lianen sind dir nicht geheuer.`0');
	$session['user']['specialinc']='';
}
?>
