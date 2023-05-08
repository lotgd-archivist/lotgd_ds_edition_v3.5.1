<?php

// 09092004

//Battle Arena - first release
//Created by Lonny Luberts of http://www.pqcomp.com/logd e-mail logd@pqcomp.com
//place this file in the main(logd) folder
//addfield `battlepoints` int(11) NOT null default '0' to accounts
//
//addfield `pqinttemp` int(20) NOT null default '0' to accounts(will re-use this in further modules, temp data field)
//(this version uses 'specialmisc' for that purpose;pqinttemp not needed -- anpera)
//
//in dragon.php after
// ,"beta"=>1
//add
// ,"battlepoints"=>1
//this way battlepoints do not reset after dragon kill
// translation by anpera

require_once "common.php";
checkday();
page_header('Turnierplatz');
output('`c`b`uD`}e`Ir `tTurnierpl`Ia`}t`uz`0`b`c`n');
//checkevent();
if ($_GET['op'] == '')
{
//if(access_control::is_superuser() == false){
//output('`2Die Gladiatoren sind gerade zum Kampftraining. Du wirst warten müssen bis sie damit fertig sind');
//}
//else{
	output('`uD`}e`Ir `tTurnierplatz ist überfüllt mit Zuschauern, der Lärm ist ohrenbetäubend.  Einige Krieger kämpfen in der Mitte des Turnierplatzes um ihre Ehre und um die Platzierung. Du siehst eine Tür zu einem exklusiven Gemeinschaftsraum. Eine große Tafel hängt an einer W`Ia`}n`ud.`n');
	$sql = 'SELECT battlepoints,name FROM accounts WHERE battlepoints > 0  ORDER BY battlepoints DESC,name LIMIT 1';
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$topbattle = $row['battlepoints'];
	$plaque = $row['name'];
	output("`7Auf dieser wird der Turnierchampion angepriesen: ");
	if ($plaque <> "")
	{
		output($plaque.'`7.`n');
	}
	else
	{
		output('Niemand.`n');
	}
	output('`}Auf der Anmeldetabelle sind alle Gladiatoren aufgelistet, gegen die du antreten kannst.`n`0<ul>');
	if ($session['user']['battlepoints'] < 13 || $session['user']['dragonkills']<1)
	{
		output('<li>`2Cicero `tLevel 9`n`0');
	}
	if ($session['user']['battlepoints'] > 12 && $session['user']['dragonkills'] >= 1 && $session['user']['battlepoints'] < 72 )
	{
		output('<li>`@Vibius `^Level 10`n`0');
	}
	if ($session['user']['battlepoints'] >= 36 && $session['user']['dragonkills'] > 2 && $session['user']['battlepoints'] < 180 )
	{
		output('<li>`2Quintus `tLevel 11`n`0');
	}
	if ($session['user']['battlepoints'] >= 72 && $session['user']['dragonkills'] > 3 && $session['user']['battlepoints'] < 252 )
	{
		output('<li>`@Cassius `^Level 12`n`0');
	}
	if ($session['user']['battlepoints'] >= 120 && $session['user']['dragonkills'] > 4 && $session['user']['battlepoints'] < 336 )
	{
		output('<li>`2Lucius `tLevel 13`n`0');
	}
	if ($session['user']['battlepoints'] >= 180 && $session['user']['dragonkills'] > 5)
	{
		output('<li>`@Aurelius `^Level 14`n`0');
	}
	if ($session['user']['battlepoints'] >= 252 && $session['user']['dragonkills'] > 7)
	{
		output('<li>`2Proximo `tLevel 15`n`0');
	}
	if ($session['user']['battlepoints'] >= 336 && $session['user']['dragonkills'] > 9)
	{
		output('<li>`@Maximus `^Level 15`n`0');
	}
	if ($session['user']['battlepoints'] >= 536 and $session['user']['dragonkills'] > 11)
	{
		output('<li>`2Ultimus `tLevel 16`n`0');
	}
	if ($session['user']['battlepoints'] >= 736 and $session['user']['dragonkills'] > 13)
	{
		output('<li>`@Extremus `^Level 16`n`0');
	}
	$rowe=user_get_aei('gladiatorfights');
	$session['gladiatorfights']=$rowe['gladiatorfights'];
	output('</ul>`n`IEin Kampf auf dem Turnierplatz wird dich einen Waldkampf kosten.`n
	`tEs ist sehr empfehlenswert, dass du dich nur in bester Verfassung einem Kampf stellst.`n
	`IDu musst eine Nutzungsgebühr bezahlen.`n
	Du kannst vor einer Heldentat noch '.$rowe['gladiatorfights'].' mal gegen einen Gladiator antreten.`n');
	
	$int_min_hp = round($session['user']['maxhitpoints'] * 0.8);
	
	if ($session['user']['hitpoints'] < $int_min_hp)
	{
		output('Du bist leider schon zu geschwächt, um dich einem Kampf zu stellen. Zumindest solltest du noch über 80% deiner Lebenskraft verfügen!`n');
	}	
	elseif ($session['user']['gold'] < 1)
	{
		output('Leider stellst du fest, dass deine Taschen leer sind.`n');
	}
	elseif ($session['user']['gold'] < 50)
	{
		output('Leider bemerkst du, dass du nicht genug Gold hast.`n');
	}
	elseif ($rowe['gladiatorfights'] < 1)
	{
		output('Sieht so aus, als ob du langsam mal eine Heldentat vollbringen solltest.`n');
	}
	elseif ($session['user']['turns'] < 1)
	{
		output('Für heute bist du jedoch schon zu müde.`n');
	}
	else 
	{
		addnav('Zahle Eintritt (50 Gold)','battlearena.php?op=pay');
	}
//}//end temporäre Deaktivierung
	/*if ($session['user']['battlepoints'] > 120 && $session['user']['dragonkills'] > 4)
	{
		addnav('Gesellschaftsraum','battlearena.php?op=lounge');
	}*/
	addnav('Rangliste','hof.php?op=battlepoints&subop=most');
	addnav('Zurück zum Turnierplatz','pvparena.php');
	addnav('Zurück zum Stadtzentrum','village.php');	
}

elseif ($_GET['op'] == 'lounge')
{
	output('`c`b`&Veteranen-Lounge`0`b`c`n`n');
	addcommentary();
	viewcommentary('battlearena','Angeben:',20,'prahlt:');
	if (file_exists('pvparena.php'))
	{
		addnav('Zurück zum Turnierplatz','pvparena.php');
	}
	else
	{
		addnav('Zurück zum Turnierplatz','battlearena.php');
	}
}

elseif ($_GET['op'] == 'rank') //Liste ist in der Ruhmeshalle
{
	output('`3Diese Krieger haben sich bereits im Kampf bewährt.`n`n');
	$sql = 'SELECT battlepoints,name FROM accounts WHERE battlepoints > 0  ORDER BY battlepoints DESC,name';
	$result = db_query($sql);
	for ($i=0; $i<db_num_rows($result); $i++)
	{
		$row = db_fetch_assoc($result);
		if ($row['battlepoints'] > 0)
		{
			output($row['name'].' `7hat '.$row['battlepoints'].' `7Kampfpunkte.`n');
		}
	}
	addnav('Weiter','battlearena.php');
}

elseif ($_GET['op'] == 'pay')
{
	$session['user']['gold']-=50;
	$session['user']['turns']--;
	if(isset($session['gladiatorfights']))
	{
		user_set_aei(array('gladiatorfights'=>$session['gladiatorfights']-1));
		unset($session['gladiatorfights']);
	}
	output('`cWähle deinen Gegner.`c`n');
	addnav('Wähle deinen Gegner');
	if ($session['user']['battlepoints'] < 13 || $session['user']['dragonkills']<1)
	{
		addnav('`2Cicero`0','battlearena.php?op=Cicero');
	}
	if ($session['user']['battlepoints'] > 12 and $session['user']['dragonkills'] >= 1 && $session['user']['battlepoints'] < 72 )
	{
		addnav('`@Vibius`0','battlearena.php?op=Vibius');
	}
	if ($session['user']['battlepoints'] >= 36 and $session['user']['dragonkills'] > 2 && $session['user']['battlepoints'] < 180 )
	{
		addnav('`2Quintus`0','battlearena.php?op=Quintus');
	}
	if ($session['user']['battlepoints'] >= 72 and $session['user']['dragonkills'] > 3 && $session['user']['battlepoints'] < 252 )
	{
		addnav('`@Cassius`0','battlearena.php?op=Cassius');
	}
	if ($session['user']['battlepoints'] >= 120 and $session['user']['dragonkills'] > 4 && $session['user']['battlepoints'] < 336 )
	{
		addnav('`2Lucius`0','battlearena.php?op=Lucius');
	}
	if ($session['user']['battlepoints'] >= 180 and $session['user']['dragonkills'] > 5)
	{
		addnav('`@Aurelius`0','battlearena.php?op=Aurelius');
	}
	if ($session['user']['battlepoints'] >= 252 and $session['user']['dragonkills'] > 7)
	{
		addnav('`2Proximo`0','battlearena.php?op=Proximo');
	}
	if ($session['user']['battlepoints'] >= 336 and $session['user']['dragonkills'] > 9)
	{
		addnav('`@Maximus`0','battlearena.php?op=Maximus');
	}
	if ($session['user']['battlepoints'] >= 536 and $session['user']['dragonkills'] > 11)
	{
		addnav('`2Ultimus`0','battlearena.php?op=Ultimus');
	}
	if ($session['user']['battlepoints'] >= 736 and $session['user']['dragonkills'] > 13)
	{
		addnav('`@Extremus`0','battlearena.php?op=Extremus');
	}
}

elseif ($_GET['op'] == "win")
{
	$gladiator=$_GET['op2'];
	switch($gladiator)
	{
	case "Cicero":
	{
		$winnings = e_rand(75,100);
		$points=1;
		$session['user']['reputation']+=(11-$session['user']['level']);
		break;
	}
	case "Vibius":
	{
		$points=2;
		$winnings = e_rand(90,175);
		$session['user']['reputation']+=(12-$session['user']['level']);
		break;
	}
	case "Quintus":
	{
		$points=3;
		$winnings = e_rand(110,228);
		$session['user']['reputation']+=(13-$session['user']['level']);
		break;
	}
	case "Cassius":
	{
		$points=4;
		$winnings = e_rand(150,300);
		$session['user']['reputation']+=(14-$session['user']['level']);
		break;
	}
	case "Lucius":
	{
		$points=5;
		$winnings = e_rand(190,409);
		$session['user']['reputation']+=(15-$session['user']['level']);
		break;
	}
	case "Aurelius":
	{
		$points=6;
		$winnings = e_rand(273,580);
		$session['user']['reputation']+=(16-$session['user']['level']);
		break;
	}
	case "Proximo":
	{
		$points=7;
		$winnings = e_rand(333,680);
		$session['user']['reputation']+=(16-$session['user']['level']);
		break;
	}
	case "Maximus":
	{
		$points=8;
		$winnings = e_rand(399,777);
		$session['user']['reputation']+=(17-$session['user']['level']);
		break;
	}
	case "Ultimus":
	{
		$points=9;
		$winnings = e_rand(499,977);
		$session['user']['reputation']+=(17-$session['user']['level']);
		break;
	}
	case "Extremus":
	{
		$points=10;
		$winnings = e_rand(499,977);
		$session['user']['reputation']+=(17-$session['user']['level']);
		break;
	}
	default:
	{
		$points=0;
		$winnings = e_rand(1,50);
	}
	}
	//addnews("`5".$session['user']['name']."`8 hat $gladiator`8 in der Arena besiegt!");
	output('Gratulation! Du hast '.$gladiator.' geschlagen!  Du bekommst '.$points.' Kampfpunkte!`n
	Du gewinnst '.$winnings.' Gold!`n');
	$session['user']['gold']+=$winnings;
	$session['user']['battlepoints']+=$points;
	if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
	{
		output("`# Die Turnierärzte versorgen deine Wunden.");
		$session['user']['hitpoints'] = min($session['user']['hitpoints']+=round($session['user']['maxhitpoints']*0.5),$session['user']['maxhitpoints']);
	}
	if ($session['user']['hitpoints']==$session['user']['maxhitpoints'])
	{
		output('`n`4Ausgezeichneter Kampf! Du bekommst zusätzlich zum Gewinn dein Eintrittsgeld zurück!`n');
		$session['user']['gold']+=50;
	}
	addnav('Weiter','battlearena.php');
}

elseif ($_GET['op'] == 'loose')
{
	$session['user']['hitpoints']=$session['user']['maxhitpoints'];
	$who = $_GET['op2'];
	switch($who)
	{
		case 'Cicero':
		case 'Vibius':
		{
			$session['user']['battlepoints']-=1;
			break;
		}
		case 'Quintus':
		case 'Cassius':
		{
			$session['user']['battlepoints']-=2;
			break;
		}
		case 'Lucius':
		case 'Aurelius':
		{
			$session['user']['battlepoints']-=3;
			break;
		}
		case 'Proximo':
		case 'Maximus':
		{
			$session['user']['battlepoints']-=4;
			break;
		}
		case 'Ultimus':
		case 'Extremus':
		{
			$session['user']['battlepoints']-=5;
			break;
		}
	}
	output('Du hast gegen '.$who.' verloren.`n`tDie Heiler der Arena versorgen deine Wunden.`n');
	//	addnews($session['user']['name'].' hat gegen $who in der Arena verloren.');
	addnav('Weiter','battlearena.php');
	if ($session['user']['battlepoints']<0)
	{
		$session['user']['battlepoints']=0;
	}
}

elseif ($_GET['op'] == 'Cicero')
{
	$badguy = array('creaturename'=>'`@Cicero`0'
	,'creaturelevel'=>9
	,'creatureweapon'=>'Iaculum'
	,'creatureattack'=>70
	,'creaturedefense'=>70
	,'creaturehealth'=>120
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creaturehealth']+=e_rand(1,50);
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'Vibius')
{
	$badguy = array('creaturename'=>'`@Vibius`0'
	,'creaturelevel'=>10
	,'creatureweapon'=>'Nagelkeule'
	,'creatureattack'=>75
	,'creaturedefense'=>75
	,'creaturehealth'=>140
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creaturehealth']+=e_rand(1,60);
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'Quintus')
{
	$badguy = array('creaturename'=>'`@Quintus`0'
	,'creaturelevel'=>11
	,'creatureweapon'=>'Sichel'
	,'creatureattack'=>80
	,'creaturedefense'=>80
	,'creaturehealth'=>160
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creaturehealth']+=e_rand(1,70);
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'Cassius')
{
	$badguy = array('creaturename'=>'`@Cassius`0'
	,'creaturelevel'=>12
	,'creatureweapon'=>'Schlagstock'
	,'creatureattack'=>85
	,'creaturedefense'=>85
	,'creaturehealth'=>180
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creaturehealth']+=e_rand(1,80);
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'Lucius')
{
	$badguy = array('creaturename'=>'`@Lucius`0'
	,'creaturelevel'=>13
	,'creatureweapon'=>'Lanze'
	,'creatureattack'=>90
	,'creaturedefense'=>90
	,'creaturehealth'=>200
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creaturehealth']+=e_rand(1,90);
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'Aurelius')
{
	$badguy = array('creaturename'=>'`@Aurelius`0'
	,'creaturelevel'=>14
	,'creatureweapon'=>'Hasta'
	,'creatureattack'=>95
	,'creaturedefense'=>95
	,'creaturehealth'=>220
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creaturehealth']+=e_rand(1,100);
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'Proximo')
{
	$badguy = array('creaturename'=>'`@Proximo`0'
	,'creaturelevel'=>15
	,'creatureweapon'=>'Harpune'
	,'creatureattack'=>100
	,'creaturedefense'=>100
	,'creaturehealth'=>240
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creaturehealth']+=e_rand(1,110);
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'Maximus')
{
	$badguy = array('creaturename'=>'`@Maximus`0'
	,'creaturelevel'=>15
	,'creatureweapon'=>'Gladiatorenschwert'
	,'creatureattack'=>125
	,'creaturedefense'=>125
	,'creaturehealth'=>340
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creatureattack']+=e_rand(5,50);
	$badguy['creaturehealth']+=e_rand(1,160);
	$badguy['creaturedefense']+=e_rand(5,50);
	if ($badguy['creatureattack'] < $session['user']['attack'])
	{
		$badguy['creatureattack'] = ($session['user']['attack'] + e_rand(5,15));
	}
	if ($badguy['creaturehealth'] < $session['user']['hitpoints'])
	{
		$badguy['creaturehealth'] = ($session['user']['hitpoints'] + e_rand(5,150));
	}
	//not doing defence, don't want to make him unbeatable
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'Ultimus')
{
	$badguy = array('creaturename'=>'`@Ultimus`0'
	,'creaturelevel'=>16
	,'creatureweapon'=>'Todbringer'
	,'creatureattack'=>155
	,'creaturedefense'=>155
	,'creaturehealth'=>640
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creatureattack']+=e_rand(5,50);
	$badguy['creaturehealth']+=e_rand(1,160);
	$badguy['creaturedefense']+=e_rand(5,50);
	if ($badguy['creatureattack'] < $session['user']['attack'])
	{
		$badguy['creatureattack'] = ($session['user']['attack'] + e_rand(5,15));
	}
	if ($badguy['creaturehealth'] < $session['user']['hitpoints'])
	{
		$badguy['creaturehealth'] = ($session['user']['hitpoints'] + e_rand(5,150));
	}
	//not doing defence, don't want to make him unbeatable
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'Extremus')
{
	$badguy = array('creaturename'=>'`@Extremus`0'
	,'creaturelevel'=>16
	,'creatureweapon'=>'Todbringer'
	,'creatureattack'=>220
	,'creaturedefense'=>220
	,'creaturehealth'=>$session['user']['maxhitpoints']*0.8
	,'creaturegold'=>0
	,'diddamage'=>0);
	
	$badguy['creatureattack']+=e_rand(5,50);
	$badguy['creaturehealth']+=e_rand(1,260);
	$badguy['creaturedefense']+=e_rand(5,50);
	if ($badguy['creatureattack'] < $session['user']['attack'])
	{
		$badguy['creatureattack'] = ($session['user']['attack'] + e_rand(5,15));
	}
	if ($badguy['creaturehealth'] < $session['user']['hitpoints'])
	{
		$badguy['creaturehealth'] = ($session['user']['hitpoints'] + e_rand(5,150));
	}
	//not doing defence, don't want to make him unbeatable
	$session['user']['badguy']=createstring($badguy);
	$_GET['op']='prefight';
}

elseif ($_GET['op'] == 'fight')
{
	$battle=true;
}

else
{
	output('ungültige Op: '.$_GET['op']);
	addnav('weiter','battlearena.php');
}

if ($_GET['op'] == 'prefight')
{
	output('`IDu wirst auf den Turnierplatz geführt und buchstäblich auf den Kampfplatz geworfen.
	`nDie Menge jubelt vor Begeisterung auf, als du ziemlich unsanft vor den Füßen deines Gegners landest.
	`n'.$badguy['creaturename'].' `Istürzt sich wie ein Wirbelwind auf dich und der Kampf beginnt.`n`0');
	$session['user']['specialmisc']=$badguy['creaturehealth'];
	$battle=true;
}

if ($battle)
{
	include_once("battle.php");
	/*
	if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!="")
	{
		$_GET['skill']="";
		if ($_GET['skill']=="")
		{
			$session['user']['buffbackup']=utf8_serialize($session['bufflist']);
		}
		$session['bufflist']=array();
	}
	*/
	if ($victory)
	{
		output('`n`7Du hast `^'.$badguy['creaturename'].' besiegt.`n
		`#Die Menge gröhlt: "'.$session['user']['name'].'`#, '.$session['user']['name'].'`#".`n
		`6Moderator: '.$session['user']['name'].'`6 traf mit einem vernichtenden Schlag!
		`n`n`3Deine Gesundheit: 
		`n'.grafbar($session['user']['maxhitpoints'],$session['user']['hitpoints'],'50%',15,'yourhealth').'`n
		`n'.$badguy['creaturename'].'`3\'s Gesundheit: 
		`n'.grafbar($session['user']['specialmisc'],0,'50%',15,'badguy').'`n`n');
		$op2=strip_appoencode($badguy['creaturename'],3);
		addnav('Weiter','battlearena.php?op=win&op2='.$op2);
		$badguy=array();
		$session['user']['badguy']='';
		/*
		if (!is_array($session['bufflist']) || count($session['bufflist']) <= 0)
		{
			$session['bufflist'] = utf8_unserialize($session['user']['buffbackup']);
			if (is_array($session['bufflist']))
			{
			}
			else
			{
				$session['bufflist'] = array();
			}
		}
		$session['user']['buffbackup'] = "";
		*/
	}
	else if ($defeat)
	{
		output('`n`7Du wurdest von `^'.$badguy['creaturename'].' `7geschlagen.`n
		`#Die Menge gröhlt: "'.$badguy['creaturename'].' `#'.$badguy['creaturename'].'`#".`n
		`6Moderator: '.$badguy['creaturename'].'`6 macht den letzten Schlag!
		`n`n`3Deine Gesundheit: 
		`n'.grafbar($session['user']['maxhitpoints'],0,'50%',15,'yourhealth').'`n`n
		`n'.$badguy['creaturename'].'`3\'s Gesundheit: 
		`n'.grafbar($session['user']['specialmisc'],$badguy['creaturehealth'],'50%',15,'badguy'));
		$session['user']['hitpoints']=1;
		$op2=strip_appoencode($badguy['creaturename'],3);
		addnav('Weiter','battlearena.php?op=loose&op2='.$op2);
		/*
		if (!is_array($session['bufflist']) || count($session['bufflist']) <= 0)
		{
			$session['bufflist'] = utf8_unserialize($session['user']['buffbackup']);
			if (is_array($session['bufflist']))
			{
			}
			else
			{
				$session['bufflist'] = array();
			}
		}
		$session['user']['buffbackup'] = "";
		*/
	}
	else
	{
		fightnav(true,false);
		output('`n');
		switch (e_rand(1,11))
		{
		case 1:
			output('`b'.$badguy['creaturename'].'`4 versucht einen billigen Trick.`0`b`n');
			break;
		case 2:
			break;
		case 3:
			break;
		case 4:
			output('`b'.$badguy['creaturename'].'`4 knurrt dich an.`0`b`n');
			break;
		case 5:
			output('`b'.$badguy['creaturename'].'`4 versucht, dir ein Ohr abzubeissen!`0`b`n');
			break;
		case 6:
			output('`b'.$badguy['creaturename'].'`4 schimpft dich einen Feigling!`0`b`n');
			break;
		case 7:
			break;
		case 8:
			output('`b'.$badguy['creaturename'].'`4 behauptet, deine Oma kämpft besser!`0`b`n');
			break;
		case 9:
			output('`b'.$badguy['creaturename'].'`4 sagt, du kämpfst wie ein Kind!`0`b`n');
			break;
		case 10:
			output('`b'.$badguy['creaturename'].'`4 sagt, dass du hässlich bist und dass dir deine Mami komische Sachen zum Anziehen gibt!`0`b`n');
			break;
		case 11:
			break;
		}
		switch (e_rand(1,15))
		{
		case 1:
			output('`#Die Menge tobt vor Begeisterung!`n');
			break;
		case 2:
			output('`#Die Menge gröhlt: "'.$session['user']['name'].' `#'.$session['user']['name'].'`#".`n');
			break;
		case 3:
			output('`#Die Menge gröhlt: "'.$badguy['creaturename'].' `#'.$badguy['creaturename'].'`#".`n');
			break;
		case 4:
			output('`#Die Menge wird still.`n');
			break;
		case 5:
			output('`#Die Menge wird nervös!`n');
			break;
		case 6:
			output('`#Die Menge macht eine Welle.`n');
			break;
		case 7:
			output('`#Die Spannung steigt.`n');
			break;
		case 8:
			output('`#Die Menge brüllt: "Nieder mit '.$badguy['creaturename'].' `#".`n');
			break;
		case 9:
			output('`#Die Menge brüllt: "Nieder mit '.$session['user']['name'].' `#".`n');
			break;
		case 10:
			output('`#Die Menge kommt in Bewegung!`n Einige Zuschauer fallen in die Arena, nur um anschließend von einer Wache wieder weggetragen zu werden.`n');
			break;
		case 11:
			output('`#Die Menge ruft:  "Mach ihn fertig! Mach ihn fertig!".`n');
			break;
		case 12:
			output('`#Die Menge schreit was das Zeug hält!`n');
			break;
		case 13:
			output('`#Die Menge tobt.`n');
			break;
		case 14:
			output('`#Ein dicker, fetter Kerl bemalt sich mit roten Kringeln und führt einen Tanz auf.`n');
			break;
		case 15:
			output('`#Ein Fan rennt in die Arena, im Eifer des Gefechts streifst du ihn und er fliegt in eine Ecke der Arena.`n');
			break;
		}
		/*
		output("`6Moderator: ");
		if ($selfdmg > 0)
		{
			output("`6Autsch, ".$badguy['creaturename']."`6 trifft ".$session['user']['name']."`6 mit $selfdmg Punkten!`n");
		}
		if ($selfdmg == 0)
		{
			output($badguy['creaturename']."`6 holt nach ".$session['user']['name']."`6 aus, trifft aber nicht!`n");
		}
		if ($selfdmg < 0)
		{
			output($badguy['creaturename']."`6 holt nach ".$session['user']['name']."`6 aus, trifft aber nicht!`n".$session['user']['name']."`6 nutzt das aus und trifft ".$badguy['creaturename']."`6.`n");
		}
		output("`6Moderator: ");
		if ($creaturedmg > 0)
		{
			output("`6Autsch, ".$session['user']['name']."`6 trifft ".$badguy['creaturename']."`6 mit $creaturedmg Punkten!`n");
		}
		if ($creaturedmg == 0)
		{
			output($session['user']['name']."`6 holt nach ".$badguy['creaturename']."`6 aus, trifft aber nicht!`n");
		}
		if ($creaturedmg < 0)
		{
			output($session['user']['name']."`6 holt nach ".$badguy['creaturename']."`6 aus, trifft aber nicht!`n".$badguy['creaturename']."`6 nutzt das aus und trifft ".$session['user']['name']."`6.`n");
		}
		*/
		output('`n`3Deine Gesundheit: 
		`n'.grafbar($session['user']['maxhitpoints'],$session['user']['hitpoints'],'50%',15,'yourhealth').'`n
		`n'.$badguy['creaturename'].'`3\'s Gesundheit: 
		`n'.grafbar($session['user']['specialmisc'],$badguy['creaturehealth'],'50%',15,'badguy'));
	}
}
else
{
	
}

page_footer();
?>