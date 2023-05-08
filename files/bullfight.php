<?php

// 08082006
/*
Stierkampf für brave Bürger
by Salator (salator@gmx.de)
basierend auf pvp.php und trophy.php
Aktivierung: in pvparena:	addnav('Stierkampfarena','bullfight.php');
*/
require_once "common.php";
require_once(LIB_PATH.'dg_funcs.lib.php');

$pvptime = getsetting("pvptimeout",600);
$pvptimeout = date("Y-m-d H:i:s",time()-$pvptime);
$crimedate=date("Y-m-d H:i:s",time()-(21*86400));
$extraprice = 200;

page_header('Stierkampf!');
if ($_GET['op']=='' && $_GET['act']!='attack')
{
	checkday();
	output('`4Du machst dich auf in die Arena, wo einige wilde Stiere warten.`n`n');
	$row=user_get_aei('last_crime');
	if ($row['last_crime'] < $crimedate)
//    if ($session['user']['daysinjail']<=$session['user']['dragonkills']/10)
	{
		if (false)// ($session['user']['age'] > getsetting('maxagepvp',50) )
		{
			output('`nDu spürst allerdings instinktiv, dass es wohl besser wäre, erst eine richtige Heldentat zu vollbringen.');
		}
		else
		{
			output('Du hast noch Kraft für `^'.$session['user']['playerfights'].'`4 Stierkämpfe.');
			addnav('Stiere auflisten','bullfight.php?op=list');
			addnav('Zum Stierzüchter','bullfight.php?op=zucht');
		}
	}
	else
	{
		output('Stierkampf ist etwas für ehrenwerte Bürger, du mußt dir eingestehen in den letzten Wochen nicht ehrenwert gelebt zu haben.');
	}
	addnav('Zurück');
	addnav('d?Zum Stadtzentrum','village.php');
	addnav('A?Zurück zur Arena','pvparena.php');
}
else if ($_GET['op']=="zucht")
{
	checkday();
	$row2=user_get_aei('stierextra');
	output('`TDeine Schritte führen dich zum Stierzüchter und ihr beginnt euch angeregt über die diesjährigen Tiere zu unterhalten. Erstaunlich, was es bei der Zucht so alles zu beachten gilt...`n`n');
	if($row2['stierextra'] == 1){
		addnav('Vertrag kündigen','bullfight.php?op=zucht3');
		output('`T...allzu lange interessiert dich dieses Thema als Laie jedoch nicht und deshalb wendest du dich nach ein paar verabschiedenden Worten ab um zu schauen, welche Stiere dir durch eure besondere Vereinbarung zum Kampf zur Verfügung stehen.`n');
	}else{
		output('`TGerade willst du dich anderen Themen, wie etwa dem Wetter zuwenden, als er dir ein besonderes Angebot unterbreitet. Für eine Gebühr von '.$extraprice.' Donationpoints könnte dein Namen auf einer besonderer Liste stehen, welche die Auswahl an Stieren erweitert, so erzählt er dir im Vertrauen. Allein die Möglichkeit ist natürlich keine Garantie dafür, dass dann stets ein Stier für dich bereit steht, aber die Chancen steigen natürlich. Nun musst du dich wohl entscheiden, ob es dir das Wert ist.`n');

        $pointsavailable=$session['user']['donation']-$session['user']['donationspent'];

        if($pointsavailable > $extraprice)addnav('A?Angebot annehmen','bullfight.php?op=zucht2');
        else addnav('A?Angebot annehmen (nicht genug DPs)','');
	}
	addnav('Stiere auflisten','bullfight.php?op=list');
	addnav('Zurück','bullfight.php');
}
else if ($_GET['op']=="zucht2")
{
	checkday();
	$Char->donationspent += $extraprice;
	output('`TNach kurzem Zögern überreichst du die geforderte '.$extraprice.' DP dem Stierzüchter und mit einem Handschlag wird die Abmachung besiegelt. Nun stehen dir also mehr jüngere und ältere Stiere zur Verfügung. `n`n');
	user_set_aei(array('stierextra'=>1));
	addnav('Stiere auflisten','bullfight.php?op=list');
	addnav('Zurück','bullfight.php');
}
else if ($_GET['op']=="zucht3")
{
	output('`TBist du sicher, dass du den Vertrag kündigen willst? Du bekommst zwar '.round($extraprice/2).' DP (die Hälfte des Original-Preises) wieder gutgeschrieben, verzichtest aber auf die Sonderkonditionen. `n`n');
	addnav('Nein!','bullfight.php');
	addnav('Ja! Vertrag kündigen!','bullfight.php?op=zucht4');
}
else if ($_GET['op']=="zucht4")
{
	$Char->donationspent -= round($extraprice/2);
	output('`TDu erhälst '.round($extraprice/2).' DP und dein Vertrag ist gekündigt! `n`n');
	user_set_aei(array('stierextra'=>0));
	addnav('Stiere auflisten','bullfight.php?op=list');
	addnav('Zurück','bullfight.php');
}
else if ($_GET['op']=="list")
{
	checkday();
	$days = getsetting('pvpimmunity', 5);
	$exp = getsetting('pvpminexp', 1500);
	$dk = round($session['user']['dragonkills']*0.9);
	if ($dk>130) $dk=130; //Chance für alte Spieler
	if ($dk==0) $dk=1; //Neulingsflut unterbinden
	$minlevel = $session['user']['level']-1;
	$maxlevel = $session['user']['level']+2;
	$row2=user_get_aei('stierextra');
	if($row2['stierextra'] == 1){
		$minlevel -= 2;
		$maxlevel += 2;
	}
	
	$sql = 'SELECT name,alive,location,profession,sex,level,laston,loggedin,login,pvpflag,acctid,dragonkills FROM accounts WHERE
	(locked=0) AND
	(dragonkills >= '.$dk.') AND
	(level >= '.$minlevel.' AND level <= '.$maxlevel.') AND
	(alive=0 AND location='.USER_LOC_FIELDS.') AND
	(race!=\'\' AND specialty>0) AND
	(pvpflag<>\'5013-10-06 00:42:00\' AND pvpflag<>\'1986-10-06 00:42:00\') AND
	!('.user_get_online(0,0,true).') AND
	(hitpoints = 0)
	ORDER BY level DESC LIMIT 30';

/*test
	$sql = "SELECT accounts.name,alive,location,profession,sex,level,laston,loggedin,login,pvpflag,acctid,dragonkills FROM accounts  WHERE
	locked=0 AND
	(age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
	(race!='' AND specialty>0) AND
	loggedin=0 AND
	(acctid <> ".$session['user']['acctid'].")
	ORDER BY level DESC";
//*/
	$result = db_query($sql);
	
	output("`c<table bgcolor='#999999' border='0' cellpadding='3' cellspacing='0'><tr class='trhead'><td width='200'>Name</td><td width='45'>Level</td><td width='45'>Alter</td><td>Ops</td></tr>");
	
	$count = db_num_rows($result);
	
	if ($count == 0)
	{
		output('<tr><td colspan="4" class="trlight">`iLeider ist gerade kein Stier verfügbar, mit dem ein fairer Kampf möglich wäre!`0`i</td></tr>');
	}
	
	for ($i=0; $i<$count; $i++)
	{
		$row = db_fetch_assoc($result);
		$str_out.='<tr class="'.($i%2?'trlight':'trdark').'"><td>'.utf8_ucwords(mb_strtolower(utf8_strrev($row['login']))).'</td><td>'.$row['level'].'</td><td>'.$row['dragonkills'].'</td><td>[ ';
		if ($row['pvpflag']>$pvptimeout)
		{
			$str_out.='`ierschöpft`i ]</td></tr>';
		}
		else
		{
				$str_out.='<a href="bullfight.php?act=attack&id='.$row['acctid'].'">Kampf</a> ]</td></tr>';
				addnav('','bullfight.php?act=attack&id='.$row['acctid']);
		}
	}
	output($str_out.'</table>`c');
	addnav('Stiere auflisten','bullfight.php?op=list');
	addnav('d?Zurück zur Stadt','village.php');
	addnav('A?Zurück zur Arena','pvparena.php');
	
}
else if ($_GET['act'] == 'attack')
{
	$sql = 'SELECT login AS creaturename,
	level AS creaturelevel,
	gold AS creaturegold,
	experience AS creatureexp,
	maxhitpoints AS creaturehealth,
	attack AS creatureattack,
	defence AS creaturedefense,
	pvpflag,
	dragonkills,
	acctid
	FROM accounts
	WHERE acctid='.$_GET['id'];
	
	$result = db_query($sql);
	if (db_num_rows($result)>0)
	{
		$row = db_fetch_assoc($result);
		$row['creaturename']='Stier '.utf8_ucwords(mb_strtolower(utf8_strrev($row['creaturename'])));
		$row['creatureweapon']='Hörner';
		$row['creaturehealth']=e_rand($row['creaturehealth']*0.8,$row['creaturehealth']*1.1);
		if ($row['creaturegold']==0 || $row['creaturegold']>1000)
		{
			$row['creaturegold']=e_rand($row['creaturelevel']*10,$row['creaturelevel']*50);
		}
		if ($session['user']['playerfights']>0)
		{
			user_update(array('pvpflag'=>array('sql'=>true,'value'=>'now()')),$row['acctid']);
			
			$battle=true;
			$row['pvp']=1;
			$row['creatureexp'] = round($row['creatureexp'],0);
			$row['playerstarthp'] = $session['user']['hitpoints'];
			$session['user']['badguy']=createstring($row);
			$session['user']['playerfights']--;
			$session['buffbackup']='';
			$session['user']['buffbackup']='';

		}
		else
		{
			output('`4Du bist zu müde, um heute einen weiteren Stierkampf zu riskieren.');
		}
	}
	if (!$battle)
	{
		addnav('d?Zurück zur Stadt','village.php');
		addnav('A?Zurück zur Arena','pvparena.php');
	}
}
if ($_GET['op']=='take') //Trophäe mitnehmen
{
	$name=rawurldecode($_GET['who']);
	$dks=$_GET['dks'];
	$id=$_GET['id'];

	$value=($dks+1)*25;
	if($_GET['set']==1)
	{
		output('`3Du machst dich an deine blutige Arbeit......`nDer Kopf von '.$name.'`3 verschwindet kurze Zeit später in deinem Rucksack.`n`n');
	}
	else
	{
		output('`3Du zückst deine Mitgliedskarte der Jägerhütte. Während man dir 3 Punkte streicht macht sich ein geübter Präparator an seine blutige Arbeit...`nDer Kopf von '.$name.'`3 verschwindet kurze Zeit später in deinem Rucksack.`n`n');
		$session['user']['donationspent']+=3;
		debuglog('gab 3 DP für den Kopf von '.$name);
	}

	$item['tpl_name'] = db_real_escape_string('Der Kopf von '.$name);
	$item['tpl_gold'] = $value;
	$item['tpl_value1'] = $dks;
	$item['tpl_value2'] = 7;
	$item['tpl_hvalue'] = $id;
	$item['tpl_description'] = 'Der Kopf von '.$name.'`0. Erworben in einem fairen Kampf.';

	item_add($session['user']['acctid'],'trph',$item,true);

	addnav('d?Zurück zur Stadt','village.php');
	addnav('A?Zurück zur Arena','pvparena.php');

}
if ($_GET['op']=='run')
{
	output('Deine Ehre verbietet es dir, wegzulaufen.');
	$battle=true;
}
if ($_GET['skill']!='')
{
	output('Deine Ehre verbietet es dir, deine besonderen Fähigkeiten einzusetzen.');
	$_GET['skill']='';
}
if ($_GET['op']=='fight' || $_GET['op']=='run')
{
	$battle=true;
}
if ($battle)
{
	include('battle.php');
	if ($victory)
	{
		$exp = round(getsetting('pvpattgain',10)*$badguy['creatureexp']/100,0);
		$expbonus = round(($exp * (1+.1*($badguy['creaturelevel']-$session['user']['level']))) - $exp,0);
		output('`b`&'.$badguy['creaturelose'].'`0`b`n');
		output('`b`$Du hast '.$badguy['creaturename'].' besiegt!`0`b`n');
		output('`#Du erbeutest `^'.$badguy['creaturegold'].'`# Gold!`n');
		$session['user']['gold']+=$badguy['creaturegold'];

		if ($expbonus>0)
		{
			output('`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^'.$expbonus.'`# Erfahrungspunkte!`n');
			$session['user']['reputation']++;
		}
		elseif ($expbonus<0)
		{
			output('`#*** Weil dieser Kampf so leicht war, verlierst du `^'.abs($expbonus).'`# Erfahrungspunkte!`n');
			$session['user']['reputation']--;
		}
		output('Du bekommst insgesamt `^'.($exp+$expbonus).'`# Erfahrungspunkte!`n`0');
		// start: xp-loss for killing lowdk players
		$xplossfactor = 0;
		$mindks = getsetting('pvpmindkxploss',10);
		$dksdiff = $session['user']['dragonkills'] - $badguy['dragonkills'];
		if ($dksdiff>$mindks)
		{
			$xplossfactor = 1 - (($badguy['dragonkills'] + 3) / ($session['user']['dragonkills']));
			$loss = round(($exp+$expbonus) * $xplossfactor);
			output('`#Davon werden dir `$'.abs($loss).' `#Erfahrungspunkte abgezogen, weil der Stier so jung war.');
		}
		// end: xp-loss for killing lowdk players
		$session['user']['experience']+=($exp+$expbonus-$loss);
		
		addnews('`@'.$session['user']['name'].'`3 gewann einen Stierkampf gegen `4'.$badguy['creaturename'].'`3.');
		$sql='UPDATE account_extra_info SET bullfightwins=bullfightwins+1 WHERE acctid='.$session['user']['acctid'];
		db_query($sql);

		addnav('Zurück zur Stadt','village.php');
		addnav('A?Zurück zur Arena','pvparena.php');

		//Trophäensammler, mit Präparierset kostenlos, sonst3DP
		$id=$badguy['acctid'];
		if (($session['user']['donation']-$session['user']['donationspent'])>=3 && ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='7' AND name LIKE '%".db_real_escape_string($badguy['creaturename'])."%'") == 0)) //Suche nach Name ist beabsichtigt
		{
			output('`n`n`^Du überlegst dir, ob du dir nicht vielleicht ein Andenken an diesen Kampf für deinen Schaukasten mitnehmen solltest...');
			addnav('Trophäe');
			$who=rawurlencode($badguy['creaturename']);
			$rowextra = user_get_aei('trophyhunter');
			if ($rowextra['trophyhunter']==1)
			{
				addnav('Kopf mitnehmen','bullfight.php?op=take&set=1&who='.$who.'&id='.$id.'&dks='.$badguy['dragonkills']);
			}
			else
			{
				output('`n`$Die Haltbarmachung kostet dich 3 Donationpoints!');
				addnav('Kopf mitnehmen `$(3DP)','bullfight.php?op=take&who='.$who.'&id='.$id.'&dks='.$badguy['dragonkills']);
			}
		}

		$badguy=array();
		
	}
	else if ($defeat)
	{
		addnav('d?Zurück zur Stadt','village.php');
		addnav('N?Tägliche News','news.php');
		
		addnews('`%'.$session['user']['name'].'`5 hat einen Stierkampf gegen`% '.$badguy['creaturename'].' `5  verloren.`n'.get_taunt(false));
	
		$session['user']['gold']=0;
		$session['user']['hitpoints']=1;
		$session['user']['experience']=round($session['user']['experience']*(100-getsetting('pvpattlose',15))/100,0);
		$session['user']['badguy']="";
		$session['user']['playerfights']=0;
		$session['user']['turns']=0;
		
		output('`b`&Du wurdest von `%'.$badguy['creaturename'].' `&besiegt!!!
		`n`4Alles Gold, das du bei dir hattest, hast du verloren!
		`n'.getsetting('pvpattlose',15).'% deiner Erfahrung ging verloren!
		`nDu bist zu schwach um heute noch zu kämpfen.');
		$session['user']['reputation']--;
		$badguy=array();
	}
	else
	{
		fightnav(false,false);
	}
}
page_footer();
?>
