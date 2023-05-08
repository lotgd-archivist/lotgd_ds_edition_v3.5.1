<?php

// Die Standuhr
// Gecodet von Ventus
// www.Elfen-Portal.de
// nach einer Idee von Magix_Lady

//Kleinere Tweaks und Änderungen von Dragonslayer für lotgd.drachenserver.de
//3. Verjüngungs-Fall eingefügt von Salator

switch ($_GET['op'])
{

	case 'rechts':
	output('`# Du drehst den Zeiger vorwärts. Der Zeiger wandert über Sekunden, über Minuten und über Stunden in die Zukunft.`n
	Helles, intensives Licht umschließt dich. Als das Licht verschwindet, ');

	switch (e_rand(1,3))
	{
		case 1:
		output('`%fühlst du dich älter und weiser. ');
        /** @noinspection PhpUndefinedVariableInspection */
        $session['user']['experience']*=1.1;
		$session['user']['age']+=5;
		//addnav('Zurück in den Wald','forest.php');
		break;
		case 2:
		output('`%bist du nur noch ein lebloser Leichnam, da du um 1000 Jahre gealtert bist. Das nächste mal solltest du die Uhr nicht ganz soweit drehen...`n
		`$ Du bist tot!');
		killplayer(100,0,0,'news.php','Tägliche News');
        /** @noinspection PhpUndefinedVariableInspection */
        addnews($session['user']['name'].' hat eine tödliche Zeitreise unternommen.');
		break;
		case 3:
		output('fühlst du dich älter als du sein müsstest. Du stinkst, als hättest du dich eine Woche lang nicht gewaschen!');
        /** @noinspection PhpUndefinedVariableInspection */
        $session['user']['age']+=3;
		$session['user']['charm']=max(0,$session['user']['charm']-2);
		addnews($session['user']['name'].' hat eine Zeitreise unternommen.');
		break;
		default: //Fehler
		output('fühlst du dich auch nicht anders als vorher.');
	}
	break;

	case 'links':
	output('`# Du drehst den Zeiger zurück. Der Zeiger wandert über Sekunden, über Minuten und über Stunden in die Vergangenheit.
 	Helles, intensives Licht umschließt dich. Als das Licht verschwindet, ');
	switch (e_rand(1,3))
	{

		case 1:
		output('`% scheint die Sonne tief, als wäre es noch früh am Morgen. Du fühlst dich ausgeschlafen, als könntest du diesen Tag nun ein weiteres mal erleben!');
        /** @noinspection PhpUndefinedVariableInspection */
        $session['user']['turns']+=8;
		//addnav('Zurück in den Wald','forest.php');
		break;

		case 2:
		output('`%bildest du dich langsam zurück zum Kind. Am Ende bleibt nur deine Ausrüstung übrig, die mit einer seltsamen weißen Flüssigkeit klebriger Konsistenz verschmiert ist...`n
		`$Du bist tot!');
		killplayer(0,0,0,'news.php','Tägliche News');
        /** @noinspection PhpUndefinedVariableInspection */
        addnews($session['user']['name'].' hat eine tödliche Zeitreise unternommen.');
		break;
		case 3:
		/** @noinspection PhpUndefinedVariableInspection */
        if($session['user']['age']>2)
		{
			output('fühlst du dich jünger als du sein müsstest. Du bist so richtig jung und frisch!');
			$session['user']['age']-=2;
			$session['user']['charm']++;
		}
		else
		{
			if($session['user']['dragonkills']>0)
			{ //auf zum zuletzt getöteten Drachen, oder is das fies?
				// talion: nicht fies, aber gefährlich; kann zuviel schiefgehen
				/*output('fühlst du dich jünger als du sein müsstest. Wolltest du nicht gerade deinen '.$session['user']['dragonkills'].'. Drachen töten?');
				$session['user']['maxhitpoints']+=10*(15-$session['user']['level']);
				$session['user']['soulpoints']+=5*(15-$session['user']['level']);
				$session['user']['attack']+=(15-$session['user']['level']);
				$session['user']['defence']+=(15-$session['user']['level']);
				$session['user']['level']=15;
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
				$session['user']['dragonkills']--;
				$session['user']['age']=$session['user']['dragonage'];
				$session['user']['experience']= get_exp_required($session['user']['level'],$session['user']['dragonkills'])+200;
				item_set_weapon('High-Grade Camrosklinge',17,7654,0,0,2);
				item_set_armor('High-Grade Yazata-Brustpanzer',17,7654,0,0,2);
				$result=db_query('SELECT usename FROM specialty WHERE specid='.$session['user']['specialty']);
				$row=db_fetch_assoc($result);
				$session['user']['specialtyuses'][$row['usename']]=15;
				restore_specialty();*/
				output('fühlst du dich jünger als du sein müsstest. Du bist so richtig jung und frisch!');
				$session['user']['charm']++;
				$session['user']['age']=1;
			}
			else
			{
				output('fühlst du dich so jung, dein Eintritt in diese Welt steht unmittelbar bevor.');
				$session['user']['age']=1;
			}
		}
		addnews($session['user']['name'].' hat eine Zeitreise unternommen.');
		break;
		default: //Fehler
		output('fühlst du dich auch nicht anders als vorher.');
	}
	break;

	case 'raus':
	$session['user']['specialinc']='';
	redirect('forest.php');
	break;

	default:
	output('`#Du betrittst eine Lichtung, in deren Mitte sich eine seltsam glitzernde,
	uralt aussehende Standuhr befindet. Du beschließt, sie dir näher anzusehen und entdeckst einen goldenen Spruch auf ihrem Sockel: `n
	`$ Seit Jahrtausenden steh ich hier,`n
	was du dir wünscht, geben kann ich\'s dir.`n
	Dreh\' meine Zeiger, vor oder zurück,`n
	vielleicht hast du Glück...`n
	Doch sei gewarnt!`n
	Drehst du falschherum,`n
	ist deine Zeit bald um!`n
	`%Was wirst du tun?');

	addnav('R?Dreh die Uhrzeiger nach Rechts','forest.php?op=rechts');
	addnav('L?Dreh die Uhrzeiger nach Links','forest.php?op=links');
	addnav('u?Renn um dein Leben!!','forest.php?op=raus');
	$session['user']['specialinc']='uhr.php';
	break;
}
?>