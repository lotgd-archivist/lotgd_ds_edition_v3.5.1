<?php
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($_GET['op']=='umgehen')
{
	output('Du umgehst das Gebiet und verlierst dabei `#einen Waldkampf.`0');
	$session['user']['turns']--;
	$session['user']['specialinc'] = '';
}

else if ($_GET['op']=='fels')
{
	output('Du machst dich auf, den Weg über die Felsen zu nehmen. Anfangs noch ein wenig unsicher, aber dann recht schnell und mit einer bemerkenswerten Sicherheit balancierst du über die Felsen.`n`0');
	switch(e_rand(1,5))
	{
		case 1:
		output('Dann passiert es: Du trittst auf loses Geröll und stürzt schwer in eine Felsspalte. Mit allerletzter Kraft kannst du dich daraus befreien.
		`n`n`%Du hast fast alle Lebenspunkte verloren und solltest dringend einen Heiler aufsuchen!`0');
		$session['user']['hitpoints']=1;
		break;
		case 2:
		output('Du stutzt, als du plötzlich etwas glitzern siehst. Voller Freude steckst du `Qeinen Edelstein`0 ein.`0');
		$session['user']['gems']++;
		break;
		case 3:
		if ( $session['user']['hashorse'] > 0 && $session['bufflist']['mount']['rounds'] > 1 )
		{
			output('Auf dem unebenen Gelände kommt dein Tier ins straucheln und rutscht weg. 
			`%Es hat sich dabei verletzt und muss bei Merrick gepflegt werden!`0');
			$session['bufflist']['mount']['rounds']=1;
		}
		else
		{
			output('Dann passiert es: Du trittst auf loses Geröll und stürzt schwer in eine Felsspalte. Mit allerletzter Kraft kannst du dich daraus befreien.
			`n`n`%Du hast fast alle Lebenspunkte verloren und solltest dringend einen Heiler aufsuchen!`0');
			$session['user']['hitpoints']=1;
		}
		break;
		case 4:
		output('Du findest einen einzeln stehenden Strauch, der große `$rote`0 Früchte trägt. Neugierig pflückst du dir eine Frucht und isst diese.`n`0');
		$was = e_rand(1,100);
		if ( $was < 33 )
		{
			output('Die `$Frucht`0 schmeckt bitter und es zieht dir den Magen zusammen.
			`n`%Du verlierst Lebenspunkte.`0');
			$session['user']['hitpoints']=round( $session['user']['hitpoints']*0.5 );
		}
		else
		{
			output('Die `$Frucht`0 schmeckt sehr süß und du spürst, wie deine Lebensgeister neu erwachen.
			`n`9Du gewinnst Lebenspunkte hinzu.`0');
			$session['user']['hitpoints']*=2;
		}
		break;
		case 5:
		output('Ohne größere Probleme meisterst du diesen felsigen Abschnitt und setzt deinen Weg fort.`0');
		break;
	}
	$session['user']['specialinc']='';
}
else //if ($_GET['op']=='')
{
	output('Vor dir erstreckt sich ein Gebiet, übersäht mit Felsen. Etwas entfernt ist sogar ein kleiner Berg. Das Gebiet zu umgehen wird etwas dauern, aber über die Felsen zu steigen ist bestimmt nicht ungefährlich...`0');
	//abschluss intro
	addnav('Gebiet umgehen','forest.php?op=umgehen');
	addnav('Felsen erklimmen','forest.php?op=fels');
	$session['user']['specialinc'] = 'cliff.php';
}
?>