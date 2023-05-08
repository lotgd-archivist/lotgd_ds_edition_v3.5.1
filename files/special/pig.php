<?php
/*Wildsau
Autor: ???
Modifycated by Hadriel
Translation by Hadriel
*/
//if (!isset($session)) exit();

if($_GET['op']=='')
{
	$session['user']['specialinc'] = 'pig.php';
	output('`2Während du durch den Wald läufst, spürst du plötzlich einen harten Schlag am Oberschenkel.`n
	Dann einen stechenden Schmerz. Als du nach unten schaust, siehst du den Verursacher: Es ist ein `^Wildschwein`2.`n
	Mit deiner/m '.$session['user']['weapon'].'`2 schlägst du solange auf das Tier ein, bis es sich grunzend verzieht.`n`n
	Du verbindest gerade dein Bein, da fällt dir ein, dass du ja ein `&Jäger `2bist. Doch von dem grunzenden Ungeheuer ist weit und breit nichts mehr zu sehen.`n
	Willst du das Schwein wirklich jagen und dafür einen Waldkampf opfern?');
	$session['user']['hitpoints']*=0.8;
	addnav('J?Jage es!','forest.php?op=huntit');
	addnav('L?Lass es bleiben','forest.php?op=return');
}

elseif($_GET['op']=='huntit')
{
	$session['user']['specialinc'] = 'pig.php';
	output('`^Du rennst so schnell du kannst in die ungefähre Richtung, wo das Tier verschwunden ist. Dann stößt du auf einen Weg. Du glaubst links von dir ein Grunzen zu hören, doch dein Blut rauscht dir in den Ohren. Wohin wirst du dich wenden?');
	addnav('l?Nach links','forest.php?op=left');
	addnav('r?Nach rechts','forest.php?op=right');
	addnav('Geradeaus ins dichte Gestrüpp','forest.php?op=forward');
	$session['user']['turns']--;
}

elseif($_GET['op']=='right' || $_GET['op']=='left' || $_GET['op']=='forward')
{
	$session['user']['specialinc'] = 'pig.php';
	switch($_GET['op'])
	{
		case 'left':
			$weg = 'nach links und dann so schnell du kannst `7den Weg`^ entlang.';
			break;
		case 'right':
			$weg = 'nach rechts und dann so schnell du kannst `7den Weg`^ entlang.';
			break;
		case 'forward':
			$weg = 'geradeaus ins dichte `7Gestrüpp`^. Du kommst nur langsam vorwärts, aber hier kannst du deinen Gegner nicht überhören.';
			break;
	}

	output('`^Du rennst '.$weg.'`n`n');

	$rowe=user_get_aei('hunterlevel');
	$hunterlevel = $rowe['hunterlevel'];
	$money=e_rand(10,60);
	switch(e_rand(0,(20-$hunterlevel)))
	{
		case 0:
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
			output('Du stürzt dich mit voller Wucht auf das, was du für das Schwein hältst. Ein lautes, erschrecktes Quieken gibt dir recht, dann hörst du noch den dumpfen `7*Plumps*`^, wie der Körper umfällt.');
			switch(e_rand(0,3))
			{
				case 0:
					output(' Als du das Schwein genauer untersuchst, findest du einen Edelstein! Das Tier muss ihn wohl mit einem Trüffel verwechselt haben...');
					$session['user']['gems']++;
					break;
				default:
					$gold=e_rand(1,3)*$money*$session['user']['level'];
					output(' Als du das Schwein genauer untersuchst, findest du in seinem Magen '.$gold.' Gold.');
					$session['user']['gold']+=$gold;
					break;
			}
			if(item_count('tpl_id="wildpig" AND deposit1=0 AND deposit2=0 AND owner='.$session['user']['acctid'])==0) //Fleisch für die Küche
			{
				output('`nDu zerlegst das Schwein und steckst sein Fleisch in deinen Beutel.');
				item_add($session['user']['acctid'],'wildpig');
			}
			else
			{
				output('`nDa du noch genügend Fleisch vom letzten erlegten Wildschwein hast, überlässt du dieses hier den Wölfen.');
			}
			break;
		default:
			output('Du stürzt dich mit voller Wucht auf das, was du für das Schwein hälst. Als du schaust, was du aufgespießt hast, musst du enttäuscht feststellen, dass es wohl nur ein paar Blätter waren.');
			break;
	}

	addnav('Z?Zurück in den Wald','forest.php?op=return');
}

else
{
	//addnav('W?Weiter','forest.php');
	output('`2Du machst dich mit schmerzendem Oberschenkel auf den Weg zurück in den Wald.');
	$session['user']['specialinc']='';
}
?>
