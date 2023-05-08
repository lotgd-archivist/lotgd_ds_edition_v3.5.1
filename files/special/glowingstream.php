<?php
if ($_GET['op']=='')
{
	output('`#Du entdeckst einen schmalen Strom schwach glühenden Wassers, das über runde, glatte, weiße Steine blubbert. Du kannst eine magische Kraft in diesem Wasser fühlen. Es zu trinken, könnte ungeahnte Kräfte in dir freisetzen - oder es könnte dich zum völligen Krüppel machen. Wagst du es, von dem Wasser zu trinken?
	`n`n<a href="forest.php?op=drink">Trinken</a>
	`n<a href="forest.php?op=nodrink">Nicht trinken</a>');
	addnav('Trinken','forest.php?op=drink');
	addnav('Nicht Trinken','forest.php?op=nodrink');
	addnav('','forest.php?op=drink');
	addnav('','forest.php?op=nodrink');
	$session['user']['specialinc']='glowingstream.php';
}
else
{
	$session['user']['specialinc']='';
	if ($_GET['op']=='drink')
	{
		$rand = e_rand(1,10);
		output('`#Im Wissen, dass dieses Wasser dich auch umbringen könnte, willst du trotzdem die Chance wahrnehmen. Du kniest dich am Rand des Stroms nieder und nimmst einen langen, kräftigen Schluck von diesem kalten Wasser. Du fühlst Wärme von deiner Brust heraufziehen, ');
		switch ($rand)
		{
		case 1:
			output('`igefolgt von einer bedrohlichen, beklemmenden Kälte`i. Du taumelst und greifst dir an die Brust. Du fühlst das, was du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt.
			`n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, dass die Steine, die dir aufgefallen sind die blanken Schädel anderer Abenteurer sind, die genauso viel Pech hatten wie du.
			`n`nDunkelheit umfängt dich, während du da liegst und in die Bäume starrst. Dein Atem wird dünner und immer unregelmäßiger. Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem Herzen Besitz ergreift.
			`n`n`^Du bist an den dunklen Kräften des Stroms gestorben.
			`nDa die Waldkreaturen die Gefahr dieses Platzes kennen, meiden sie ihn und deinen Körper als Nahrungsquelle. Du behältst dein Gold.
			`nDie Lektion, die du heute gelernt hast, gleicht jeden Erfahrungsverlust aus.
			`nDu kannst morgen wieder kämpfen.');
			killplayer(0,0,0,'news.php','Tägliche News');
			addnews('`%'.$session['user']['name'].' hat seltsame Kräfte im Wald entdeckt und wurde nie wieder gesehen.');
			break;
		case 2:
			output('`igefolgt von einer bedrohlichen, beklemmenden Kälte`i. Du taumelst und greifst dir an die Brust. Du fühlst das, was du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt.
			`n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, dass die Steine, die dir aufgefallen sind die blanken Schädel anderer Abenteurer sind, die genauso viel Pech hatten wie du.
			`n`nDunkelheit umfängt dich, während du da liegst und in die Bäume starrst. Dein Atem wird dünner und immer unregelmäßiger. Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem Herzen Besitz ergreift.
			`n`nAls du deinen letzten Atem aushauchst, hörst du ein entferntes leises Kichern. Du findest die Kraft, die Augen zu öffnen und siehst eine kleine Fee über deinem Gesicht schweben, die die unachtsam ihren Feenstaub überall über dich verstreut. Dieser gibt dir genug Kraft, dich wieder aufzurappeln. Dein abruptes Aufstehen erschreckt die Fee, und noch bevor du die Möglichkeit hast, ihr zu danken, fliegt sie davon.
			`n`n`^Du bist dem Tod knapp entkommen! Du hast einen Waldkampf und die meisten deiner Lebenspunkte verloren.');
			$session['user']['turns']--;
			$session['user']['hitpoints']=ceil($session['user']['hitpoints']*0.1);
			break;
		case 3:
			output('du fühlst dich GESTÄRKT!
			`n`n`^Deine Lebenspunkte wurden aufgefüllt und du spürst die Kraft für einen weiteren Waldkampf.');
			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
			{
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			}
			$session['user']['turns']++;
			break;
		case 4:
			output('du fühlst deine SINNE GESCHÄRFT! Du bemerkst unter den Kieselsteinen am Bach etwas glitzern.
			`n`n`^Du findest einen EDELSTEIN!');
			$session['user']['gems']++;
			break;
		case 5:
		case 6:
		case 7:
			output('du fühlst dich VOLLER ENERGIE!
			`n`n`^Du bekommst einen zusätzlichen Waldkampf!');
			$session['user']['turns']++;
			break;
			default:
			output('du fühlst dich GESUND!
			`n`n`^Deine Lebenspunkte wurden vollständig aufgefüllt.');
			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
			{
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			}
		}
	}
	else
	{
		output('`#Weil du die verhängnisvollen Kräfte in diesem Wasser fürchtest, entschließt du dich, es nicht zu trinken und gehst zurück in den Wald.');
	}
}
?>
