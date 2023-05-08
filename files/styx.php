<?php

// 22062004

// Another work from that stupid german guy who lives for LoGD by Eric Stevens
//
// v. 21042004
//
// Escape from death, or haunt the world of the living from beyond your grave
// or do other things you wouldn't have thought to be possible at all.
//
// You can download the complete 0.9.7+jt extended(GER) which contains this piece of code
// from somewhere on Hatetepe://w³.anpera.net

require_once "common.php";
checkday();
switch ($_GET['op'])
{
case 'enterdead':
	page_header('Der Seelenfluss');
	output('
		`9Deine Seele folgt dem Fluss der Toten aufwärts. 
		Du siehst viele Seelen, die im Fluss mitgerissen werden, 
		einige werden auf Booten von toten Fährmännern gefahren und andere versuchen wie du die Flucht.
	');
	if (e_rand(1,4)==2)
	{
		output('
			`9 Schließlich siehst du ein Licht am Horizont, aus dem der Fluss zu entspringen scheint. 
			Eilig bewegst du dich darauf zu.`n
			`n
			`#Dir gelingt die Flucht aus dem Totenreich!`n
			`n
			`9Erschöpft öffnest du die Augen. Dein Körper benötigt dringend Heilung, 
			wenn du ihn nicht sofort wieder verlieren willst. Wie lange du tot warst, 
			kannst du nicht sagen, aber sehr lange kann es nicht gewesen sein.
		');
		$session['user']['alive']		=  1;
		$session['user']['hitpoints']	=  1;
		$session['user']['spirits']		= -6;
		if ($session['user']['turns'] > 2)
		{
			$session['user']['turns']  -=  2;
		}
		addnav('In die Stadt','village.php');
		addnews('`&'.$session['user']['name'].'`& gelang die Flucht aus dem Totenreich.');
	}
	else
	{
		output('
			`9 Die vielen Hände, die aus dem Fluss nach dir greifen, lassen dich nur langsam vorankommen. 
			Schließlich zerren sie dich ganz in den Fluss. 
			Du wirst zurück ins Totenreich geschwemmt - direkt vor `$Ramius`9\' Füße.`n
			`n
			Dein Fluchtversuch ist gescheitert.
		');

		addnav('Zum Friedhof','graveyard.php');
	}
	break;

case 'explore':
	page_header('Der Seelenfluss');
	switch ($_GET['subop'])
	{
	case 'hand':
		if (e_rand(1,2)==1)
		{
			output('
				`9Du streckst die Hand nach dem seltsamen kleinen Leuchten aus 
				und spürst etwas warmes in deiner Handfläche. Ein leises Summen erfüllt die Luft 
				und nach einem Augenblick schwirrt das Licht in Richtung des Mausoleums davon. 
				Was es da wohl zu suchen hat?
			');
			$session['user']['deathpower'] += 5;
			addnav('Zum Friedhof','graveyard.php');
		}
		else
		{
			output('
				`9Du streckst die Hand nach dem seltsamen kleinen Leuchten aus 
				und spürst etwas warmes in deiner Handfläche. Doch schon nach ein paar Augenblicken 
				wird es nicht nur warm, sondern heiß und du ziehst erschrocken die Hand zurück. 
				Ein bösartiges Kichern ist aus der Richtung des Mausoleums zu hören und du beschließt, 
				lieber von hier zu verschwinden.
			');
			$session['user']['deathpower'] -= 5;
			addnav('Zum Friedhof','graveyard.php');
		}
		break;

	case 'favor':
		output('
			`9Für ein paar mehr Gefallen bei `$Ramius`9 würdest du sogar deine Seele verkaufen. Ups! 
			Lieber doch nicht. So versprichst du dieser trotteligen Seele ein paar deiner Edelsteine, 
			die dir hier unten ja sonst wirklich nichts nützen. Wie du `4Hatetepe`9 die Steinchen übergeben sollst, 
			ist dir in dem Moment egal - dir wäre es sogar recht, wenn er die Steine nie einfordern würde.`n
			`4Hatetepe`9 verspricht, ein gutes Wort für dich bei `$Ramius`9 einzulegen. 
			Gerade als du ihn fragen willst, was er hier unten überhaupt mit Edelsteinen anfangen will, 
			findest du dich auf dem Friedhof wieder...
		');
		$session['user']['deathpower'] += 10;
		$session['user']['gems'] -= 2;
		addnav('Weiter...','graveyard.php');
		break;

	case 'gem':
		$gems=intval($_GET['num']);
		output('
			`4Hatetepe`9 verspricht dir, '.($gems>1?$gems.' Edelsteine':'einen Edelstein').' für dich in der Stadt bereit zu legen. 
			Gerade als du ihn fragen willst, wie er das schaffen will, 
			findest du dich auf dem Friedhof wieder...
		');
		addnav('Weiter...','graveyard.php');
		$session['user']['gems'] +=$gems;
		if($gems==1)
		{
			$session['user']['deathpower'] -= 5;
		}
		elseif($gems==2)
		{
			$session['user']['deathpower'] -= 50;
		}
		elseif($gems==3)
		{
			$session['user']['deathpower'] -= 150;
		}
		elseif($gems==4)
		{
			$session['user']['deathpower'] -= 300;
		}
		break;
	
	case 'gf':
		output('Du versprichst Hatetepe einen deiner Edelsteine und er gibt dir einen hübsch bunten Zettel, der dich berechtigt, heute eine Seele mehr zu quälen. Und auch wenn du nicht so recht an die Wirkung dieses Zettels glaubst, so spürst du doch neue Kraft in dir.');
		$session['user']['gravefights'] ++;
		$session['user']['gems'] --;
		addnav('Weiter...','graveyard.php');
		break;

	case 'gf3':
		output('Du versprichst Hatetepe fünf deiner Edelsteine und er gibt dir einen hübsch bunten Zettel, der dich berechtigt, heute drei Seele mehr zu quälen. Und auch wenn du nicht so recht an die Wirkung dieses Zettels glaubst, so spürst du doch neue Kraft in dir.');
		$session['user']['gravefights'] += 3;
		$session['user']['gems'] -= 5;
		addnav('Weiter...','graveyard.php');
		break;

	case 'spit':
		if ($session['user']['deathpower']<=0)
		{
			output('
				`9Du hast keine Gefallen mehr übrig, die du auf diese Weise verspielen könntest. 
				Traurig darüber, dass du wohl gerade deine Chance, heute noch aus dem Totenreich zu kommen, 
				verspielt hast, machst du dich auf den Weg zurück zum Friedhof.
			');
		}
		else
		{
			$session['user']['deathpower']--;
			$row=db_fetch_assoc(db_query('SELECT name FROM accounts ORDER BY rand() LIMIT 1'));
			output('
				`9Du spuckst auf einen Grabstein mit der Aufschift `I'.$row['name'].'`9. Diese Tat kostet dich einen Gefallen.`n 
				Du hast noch `b`4'.$session['user']['deathpower'].'`b`9 Gefallen.`n`n
			');
			addnav('Weiter spucken','styx.php?op=explore&subop=spit');
		}
		addnav('Zum Friedhof','graveyard.php');
		break;

	case 'spuken':
		if ($session['user']['deathpower']<=0)
		{
			output('
				`9Du hast keine Gefallen mehr übrig, die du auf diese Weise verspielen könntest. 
				Traurig darüber, dass du wohl gerade deine Chance, heute noch aus dem Totenreich zu kommen, 
				verspielt hast, machst du dich auf den Weg zurück zum Friedhof.
			');
		}
		else
		{
			output('
				`9Du verlierst einen Gefallen und nimmst mit der Welt der Lebenden Kontakt auf.`n 
				Du hast noch `b`4'.$session['user']['deathpower'].'`b`9 Gefallen.`n`n
			');
			addcommentary();
			switch ($_GET['where'])
			{
			case '1':
				viewcommentary('pvparena','Spuke',10,'seufzt von irgendwo her');
				break;
			case '2':
				viewcommentary('village','Spuke',25,'seufzt von irgendwo her');
				break;
			case '3':
				viewcommentary('academy','Spuke',25,'spukt durch die Hallen');
				break;
			case '4':
				viewcommentary('gardens','Spuke',30,'seufzt von irgendwo her');
				break;
			case '5':
				viewcommentary('inn','Spuke',20,'seufzt von irgendwo her');
				break;
			case '6':
				viewcommentary('hunterlodge','Spuke',20,'seufzt von irgendwo her');
				break;
			case '7':
				viewcommentary('well','Spuke',25,'klagt aus der Tiefe');
                    break;
				default:
				viewcommentary('grassyfield','Spuke',10,'seufzt von irgendwo her');
			}
			$session['user']['deathpower']--;
		}
		addnav('Zum Friedhof','graveyard.php');
		break;

	default:
		//$where	= e_rand(1,8); //Spuken für Gefallen ist doch deaktiviert ;)
		switch (e_rand(1,3))
		{
		case '1':
			output('
				`9"`!Sei gegrüsst!`9", spricht dich eine alte Seele an, 
				die schon seit Ewigkeiten hier zu sein scheint, "`!Ich bin `4Hatetepe`!, 
				der tote Händler, der nie gestorben ist, immer auf dem Sprung und schon ewig hier. 
				Ich tausche hier meine Waren, die mir nie gehörten. Sie bringen dir sowohl im Totenreich, 
				wie auch im Reich der Lebenden einen Vorteil, der keiner ist. 
				Also, kann ich dir materiellen oder spirituellen Besitz anbieten oder abknöpfen?`9"
			');
			addnav('Kaufen');
			if ($session['user']['gems']>0)
			{
				addnav('1 Grabkampf  für 1 Edelstein','styx.php?op=explore&subop=gf');
			}
			if ($session['user']['gems']>4)
			{
				addnav('3 Grabkämpfe für 5 Edelsteine','styx.php?op=explore&subop=gf3');
			}
			if ($session['user']['deathpower']>4)
			{
				addnav('1 Edelstein für 5 Gefallen','styx.php?op=explore&subop=gem&num=1');
			}
			if ($session['user']['deathpower']>49)
			{
				addnav('2 Edelsteine für 50 Gefallen','styx.php?op=explore&subop=gem&num=2');
			}
			if ($session['user']['deathpower']>149)
			{
				addnav('3 Edelsteine für 150 Gefallen','styx.php?op=explore&subop=gem&num=3');
			}
			if ($session['user']['deathpower']>300)
			{
				addnav('4 Edelsteine für 300 Gefallen','styx.php?op=explore&subop=gem&num=4');
			}
			if ($session['user']['gems']>1)
			{
				addnav('10 Gefallen für 2 Edelsteine','styx.php?op=explore&subop=favor');
			}
			//Für Laola von Talion: Funktion deaktiviert.
			//if ($session['user'][deathpower]>0) addnav('Spuken für Gefallen','styx.php?op=explore&subop=spuken&where=$where');
			if ($session['user']['deathpower']>0)
			{
			addnav('Spucken für Gefallen','styx.php?op=explore&subop=spit');
			}

			addnav('Sonstiges');
			addnav('Zum Friedhof','graveyard.php');
			break;

		case '2':
			output('
				`9Du kannst nichts besonderes entdecken. Doch gerade als du dich wieder umdrehen willst, 
				fällt dir ein kleines, helles Licht auf, das um dich herumschwirrt. 
				Es ist kaum größer als ein Glühwürmchen und strahlt ein schönes, warmes Licht aus.
			');
			addnav('Was willst du tun?');
			addnav('Hand ausstrecken','styx.php?op=explore&subop=hand');
			//Für Laola von Talion: Funktion jetzt ENDGÜLTIG deaktiviert.
			//addnav('Spuken für Gefallen','styx.php?op=explore&subop=spuken&where=$where');
			addnav('Zum Friedhof','graveyard.php');
			break;

		case '3':
			output('`9Du entdeckst hier absolut nichts besonderes.');
			addnav('Zum Friedhof','graveyard.php');
			break;
		}
	}
	break;

default:
	page_header('Der Seelenfluss');
	if (!$session['user']['alive'])
	{
		output('
			`9Du bemerkst einen seltsamen Schimmer und wandelst darauf zu.`n
			Du hast den `bFluss der Seelen`b gefunden! Jenen merkwürdigen Ort, 
			der angeblich das Reich der Toten und die Welt der Lebenden verbindet 
			und wo all die toten Kreaturen herkommen, 
			die einst den Wald und jetzt den Friedhof bevölkern. Du witterst eine Chance, 
			dem Totenreich zu entfliehen, 
			aber du weißt auch um die Gefahren einer solchen Unternehmung bescheid.`n
			`n
			Wirst du den Fluchtversuch wagen? Oder willst du diesen sagenhaft Ort näher untersuchen?
		');
		addnav('Fluchtversuch','styx.php?op=enterdead');
		addnav('Ort untersuchen','styx.php?op=explore');
		addnav('Zurück zum Friedhof','graveyard.php');
	}
	else
	{
		redirect('village.php');
	}
}

page_footer();
?>
