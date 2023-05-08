<?php
//race.php
//idea and written by aska
//
//V1_ger only mounts
// 21.7.06: Edit by Salator: Reiten nur für Reittiere, andere Tiere kämpfen
// 23.7.06: Talion: Code-Beautifier drüberlaufen lassen

if ($_GET['op']=='ride')
{
	$rand = e_rand(0,14);
    /** @noinspection PhpUndefinedVariableInspection */
    $enmount = ($rand*$session['bufflist']['mount']['rounds']/10)+15;
	
	if ($session['bufflist']['mount']['rounds']>$enmount)
	{
        /** @noinspection PhpUndefinedVariableInspection */
        if ($playermount['tavern']>0)
		{
			output('`2Du holst den Reiter noch vor der Stadt ein und kommst auch wieder als ersters zurück. Der Reiter und sein schwarzes Ross kommen erst ein paar Sekunden später an. `Q"Wahrlich, ein schnelles Tier habt ihr da. Nehmt dies."`2`n Du erhältst ');
		}
		else
		{
			output('`2Dein '.$playermount['mountname'].'`2 gewinnt schon nach kurzer Zeit die Oberhand und kann seine Führung verteidigen. Der Fremde muß sich eine Niederlage eingestehen. `Q"Wahrlich, ein starkes Tier habt ihr da. Nehmt dies."`2`n Du erhältst ');
		}
		switch (e_rand(1,5))
		{
		case 1:
		case 2:
			$gold = e_rand($session['user']['level']*50,$session['user']['level']*80);
			$session['user']['gold']+=$gold;
			output('`^'.$gold.' `2Gold.');
			break;
		case 3:
		case 4:
			$session['user']['gems']+=2;
			output('`^2 `2Edelsteine.');
			break;
		case 5:
			$gold = e_rand($session['user']['level']*35,$session['user']['level']*80);
			$session['user']['gold']+=$gold;
			$session['user']['gems']++;
			output('`^einen`2 Edelstein und `^'.$gold.' `2Gold.');
			break;
		}
	}
	else
	{
        /** @noinspection PhpUndefinedVariableInspection */
        if ($playermount['tavern']>0)
		{
			output('`2Du treibst dein Tier an und verfolgst den Reiter und sein Ross. Trotz ein paar Überholversuche schaffst du es nicht in Führung zu gehen. Er kommt als ersters an und meint `Q"Ich würde sagen, ich habe gewonnen."`2 `nDer Mann nimmt dir ');
		}
		else
		{
			output('`2Du treibst dein Tier an und es schlägt sich tapfer. Trotz ein paar erfolgreicher Angriffe schafft es aber nicht, in Führung zu gehen. Nach einiger Zeit gibt es auf. Der Fremde meint `Q"Ich würde sagen, ich habe gewonnen."`2 `nDer Mann nimmt dir ');
			
		}
		switch (e_rand(1,5))
		{
		case 1:
		case 2:
			$session['user']['gold']=($session['user']['gold'] >> 1);
			output('die Hälfte deines Goldes.');
			break;
		case 3:
		case 4:
			if ($session['user']['gems']==0)
			{
				$session['user']['gold']=($session['user']['gold'] >> 1);
				output('die Hälfte deines Goldes.');
				break;
			}
			else if ($session['user']['gems']==1)
			{
				$session['user']['gems']=0;
				output('deinen letzten Edelstein.');
				break;
			}
			else
			{
				$lostgems=($session['user']['gems'] >> 1);
				$session['user']['gems']-=$lostgems;
				output('die Hälfte deiner Edelsteine.');
				debuglog('verlor '.$lostgems.' Edelsteine beim Rennen im Wald.');
				break;
			}
		case 5:
			if ($session['user']['gems']>1)
			{
				$lostgems=round($session['user']['gems'] / 2);
                $session['user']['gold'] =  round($session['user']['gold'] / 2);
				$session['user']['gems']-=$lostgems;
				debuglog('verlor '.$lostgems.' Edelsteine beim Rennen im Wald.');
				output('Die Hälfte deiner Edelsteine und deines Goldes.');
			}
			else
			{
				$session['user']['gold']=0;
				output('all dein Gold.');
			}
			
			break;
		}
		output('`nWütend über deinen Verlust verpasst du deinem Tier einen Klaps und gehst weiter.');
	}
	if ($session['bufflist']['mount']['rounds']>30)
	{
		$session['bufflist']['mount']['rounds']=(int)($session['bufflist']['mount']['rounds']-30);
		output('`nVon dem Rennen ist dein '.$playermount['mountname'].'`2 erschöpft und verliert an Kraft.');
	}
	else
	{
		$session['bufflist']['mount']['rounds']=0;
		output('`nVon dem Rennen ist dein '.$playermount['mountname'].'`2 zu erschöpft um dir heute noch zu helfen.');
	}
}

else if ($_GET['op']=='ignore')
{
	$session['user']['specialinc']='';
    /** @noinspection PhpUndefinedVariableInspection */
    output('`2Sein '.($playermount['tavern']?'Pferd':$playermount['mountname']).'`2 hatte irgendwie einen irren Blick.');
}

else if ($_GET['op']=='')
{
    /** @noinspection PhpUndefinedVariableInspection */
    if ($session['user']['hashorse']>0)
	{
        /** @noinspection PhpUndefinedVariableInspection */
        if ($playermount['tavern']>0)
		{
			if ($session['bufflist']['mount']['rounds']==0)
			{
				output('`2Ein Mann taucht auf seinem schwarzen Pferd neben dir auf. `Q"Wie wärs mit einem Rennen?"`2 fragt er dich und braust schon davon. Du versuchst dein/en '.$playermount['mountname'].'`2 anzutreiben doch das Tier ist für heute schon zu erschöpft für ein Rennen. Du hörst noch ein irres Lachen aus der Richtung in die der Reiter abgedüst ist, kümmerst dich jedoch nicht weiter darum.');
			}
			else
			{
				output('`2Ein Mann taucht auf seinem schwarzen Pferd neben dir auf. `Q"Wie wärs mit einem Rennen? Bis zur Stadt und zurück?"`2 fragt er dich und braust schon davon.`nWillst du seine Herausforderung annehmen? Wer weiß, was er als Gegenleistung bei deiner Niederlage fordert..');
				addnav('Reiten','forest.php?op=ride');
				addnav('Ignorieren','forest.php?op=ignore');
				$session['user']['specialinc']='race.php';
			}
		}
		else
		{
			if ($session['bufflist']['mount']['rounds']==0)
			{
				output('`2Ein Mann taucht mit seinem seinem '.$playermount['mountname'].'`2 neben dir auf. `Q"Wie wärs mit einem Wettkampf?"`2 fragt er dich und bringt sein Tier in Stellung. Du versuchst dein/en '.$playermount['mountname'].'`2 anzutreiben doch das Tier ist für heute schon zu erschöpft für einen Kampf. Du hörst noch ein irres Lachen als der Fremde verschwindet, kümmerst dich jedoch nicht weiter darum.');
			}
			else
			{
				output('`2Ein Mann taucht mit seinem '.$playermount['mountname'].'`2 neben dir auf. `Q"Wie wärs mit einem Wettkampf?"`2 fragt er dich und bringt sein Tier in Stellung.`nWillst du seine Herausforderung annehmen? Wer weiß, was er als Gegenleistung bei deiner Niederlage fordert..');
				addnav('r?Herausforderung annehmen','forest.php?op=ride');
				addnav('Ignorieren','forest.php?op=ignore');
				$session['user']['specialinc']='race.php';
			}
			
		}
	}
	else
	{
		output('`2Ein Mann reitet auf seinem schwarzen Pferd an dir vorbei. Was würdest du dafür geben, auch so ein Tier zu haben...');
	}
}
?>
