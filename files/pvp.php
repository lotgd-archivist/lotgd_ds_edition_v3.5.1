<?php

require_once "common.php";
require_once(LIB_PATH.'dg_funcs.lib.php');
require_once(LIB_PATH.'profession.lib.php');

$pvptime = intval(getsetting("pvptimeout",600));
$pvptimeout = date('Y-m-d H:i:s',time() - $pvptime);
//$pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
$guard=($session['user']['profession']==PROF_GUARD || $session['user']['profession']==PROF_GUARD_HEAD ? true:false);

page_header('Spielerkampf!');
if ($_GET['op']=='' && $_GET['act']!='attack' && $Char->getConfBit(UBIT_DISABLE_PVP) == 0)
{ //Startseite
	checkday();
	pvpwarning();
	output('`4Du machst dich auf in die Felder, wo einige unwissende Krieger schlafen.`n`nDu hast noch `^'.$session['user']['playerfights'].'`4 PvP Kämpfe übrig für heute.');
	if(false)//($session['user']['age'] > getsetting('maxagepvp',50) )
	{
		output('`nDu spürst allerdings instinktiv, dass es wohl besser wäre, sich erst um die Drachenplage zu kümmern.');
	}
	elseif($session['user']['profession'] == PROF_TEMPLE_SERVANT )
	{
		output('`nAls Tempeldiener kehrst du jedoch besser gleich wieder um...');
	}
	else
	{
		addnav('Krieger auflisten','pvp.php?op=list');
	}
	addnav('Zurück');
	addnav("Zurück zum Tor","dorftor.php");
	//addnav('Zum Stadtzentrum','village.php');
	//addnav('Zum Marktplatz','market.php');
}
elseif ($Char->getConfBit(UBIT_DISABLE_PVP) == true)
{
	output(words_by_sex('`4Nanana, du [böser Junge|böses Mädchen]! Die Götter haben dir doch verboten andere anzugreifen!
			`nHusch, zurück in die Stadt!'));
	addnav('Zurück in die Stadt','village.php');
}
elseif ($_GET['op']=='list')
{
	checkday();
	pvpwarning();
	
	$res = item_list_get(' hot_item>0 AND owner>0 AND deposit1=0 ','',true,'owner');
	if(db_num_rows($res)) {
		$arr_hot_owners = db_create_list($res,'owner');
	}
	else {
		$arr_hot_owners = array();
	}
	
	$days = getsetting('pvpimmunity', 5);
	$exp = getsetting('pvpminexp', 1500);
	$locsql=USER_LOC_FIELDS;
	$bool_lockhtml = $access_control->su_check(access_control::SU_RIGHT_LOCKHTML);
	$sql = 'SELECT 	a.name,
					a.alive,
					a.race,
					a.location,
					a.profession,
					a.sex,
					a.level,
					a.dragonkills,
					a.laston,
					a.loggedin,
					a.imprisoned,
					a.expedition,
					a.activated,
					a.login,
					a.pvpflag,
					a.acctid,
					g.name AS guildname,
					a.guildid,
					a.guildfunc
					'.($bool_lockhtml ? ',aei.html_locked' : '').'
					'.($guard ? ',aei.sentence' : '').'
				FROM accounts a
				LEFT JOIN dg_guilds g ON (g.guildid=a.guildid AND a.guildfunc!='.DG_FUNC_APPLICANT.') 
				'.(($bool_lockhtml || $guard) ? 'INNER JOIN account_extra_info aei ON a.acctid=aei.acctid' : '').'
				WHERE
					(a.locked=0) AND
					(a.age > '.$days.' OR a.dragonkills > 0 OR a.pk > 0 OR a.experience > '.$exp.') AND
					(a.profession <> 21 AND a.profession <>22 ) AND
					(	level >= '.($session['user']['level']-1).' AND 
						level <= '.($session['user']['level']+2).'
					) AND
					(a.alive=1 AND a.location='.$locsql.') AND
					(a.race<>"" AND a.specialty>0) AND
					'.($guard ? '' : '(a.dragonkills >= '.($session['user']['dragonkills']-5).') AND').'
					!('.user_get_online(0,0,true).') AND
					(a.acctid <> '.$session['user']['acctid'].')
				ORDER BY a.level DESC';

	$result = db_query($sql);
	if ($session['user']['pvpflag']==PVP_IMMU)
	{
		output('`n`&Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!`0`n`n');
	}
	
	if($session['user']['guildid'])
	{
		$guild = &dg_load_guild($session['user']['guildid'],array('treaties'));
	}
	
	$str_out = '`c<table bgcolor="#999999" border="0" cellpadding="3" cellspacing="0">
	<tr class="trhead">
	<th>Name</th>
	<th width="130" align="center">Level</th>
	<th>Gilde</th>
	</tr>';
	
	$count = db_num_rows($result);
	
	if($count == 0)
	{
		$str_out .= '<tr><td colspan="4" class="trlight">`iLeider erblickst du niemanden, der für dich in Frage käme!`0`i</td></tr>';
	}	
	
	for ($i=0;$i<$count;$i++)
	{
		$row = db_fetch_assoc($result);
		
		$row['guildname'] = (!empty($row['guildname'])) ? $row['guildname'] : ' - ';
		$state_str = '';
		if($row['guildid'] && $guild && $row['guildname'] != ' - ')
		{
			$state = dg_get_treaty($guild['treaties'][$row['guildid']]);
			if($state==1)
			{
				$state_str = ' `@(befreundet)';	
			}
			elseif($state==-1)
			{
				$state_str = ' `4(Feind)';
			}
		}
		
		$immu = (
					(
						($row['pvpflag']>$pvptimeout) 
					&& 	(
								($session['user']['profession']==0) 
							|| 	($session['user']['profession']>2)
						)
					&& !isset($arr_hot_owners[$row['acctid']])
					) 
				|| (($session['user']['guildid']>0) && ($session['user']['guildid'] == $row['guildid']))
				);
		if( !$immu )
		{
			addnav('','pvp.php?act=attack&id='.$row['acctid']);
		}
		$str_out .= '<tr class="'.($i%2?'trlight':'trdark').'">
		<td>'.($row['sentence']>0?'<img src="./images/oldscroll.GIF" width=16 height=16 alt=""> ':'')
            .CRPChat::menulink($row,($immu ? '' : 'pvp')).($immu ? ' `i(immun)`i' : '')
            .'`0</td>
		<td align="center">'.$row['level'].' ('.$row['dragonkills'].' DKs)</td>
		<td>'.$row['guildname'].$state_str.'`0</td>
		</tr>'; 
	}
	$str_out .= '</table>`c';
	output($str_out,true);
	
	addnav('Krieger auflisten','pvp.php?op=list');
	addnav('Zurück');
	addnav('Zurück zum Tor','dorftor.php');

	if (getsetting('hasegg',0)>0)
	{
		$sql = 'SELECT name FROM accounts WHERE acctid = '.getsetting('hasegg',0);
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		output('`n`n'.$row['name'].' hat das goldene Ei!');
	}
}

else if ($_GET['act'] == 'attack')
{ //einen angreifen
	$sql = "SELECT name AS creaturename,
		level AS creaturelevel,
		weapon AS creatureweapon,
		gold AS creaturegold,
		experience AS creatureexp,
		maxhitpoints AS creaturehealth,
		attack AS creatureattack,
		defence AS creaturedefense,
		bounty AS creaturebounty,
		loggedin,
		location,
		dragonkills,
		laston,
		alive,
		race,
		a.acctid,
		lastip,
		emailaddress,
		pvpflag,
		uniqueid,
		guildid,
		guildfunc,
		aei.creaturewin,
		aei.creaturelose
	FROM accounts a
	LEFT JOIN account_extra_info aei ON a.acctid=aei.acctid
	WHERE ";
	$sql .= ($_GET['name']) ? " login='".$_GET['name']."'" : " a.acctid=".$_GET['id'];
	
	$result = db_query($sql);
	if (db_num_rows($result)>0)
	{
		$row = db_fetch_assoc($result);
		
		$sql2 = 'SELECT acctid,sentence FROM account_extra_info WHERE acctid='.$row['acctid'];
		$result2 = db_query($sql2);
		$row2 = db_fetch_assoc($result2);

		// Hot Items
		$res = item_list_get(' hot_item>0 AND owner='.$row['acctid'].' AND deposit1=0 ','',true,'owner');
		if(db_num_rows($res))
		{
			$row['hot_item'] = 1;
		}

		//Check ob Gegner gültig ist
		if (abs($session['user']['level']-$row['creaturelevel'])>2 && $row['location']!=USER_LOC_HOUSE)
		{ //in Feldern: Levelcheck
			output('`$Fehler:`4 Dieser Spieler ist nicht in deinem Levelbereich!');
		}
		elseif (($row['pvpflag'] > $pvptimeout && $row['hot_item']!=1) && $guard==false)
		{ //wurde schon angegriffen oder hat Immu
			output('`$Uuuups:`4 Dieser Krieger ist gerade anderweitig ... beschäftigt. Du wirst etwas auf deine Chance warten müssen!');
		}
		elseif ((($session['user']['dragonkills'] > $row['dragonkills']+5) && ($row['location']!=USER_LOC_HOUSE)) && $guard==false)
		{ //in Feldern: DK-Check
			output('`$Mööööp:`4 Dieser Gegner ist unter deiner Würde!');
		}
		elseif (ac_check($row))
		{ //Multi-Check
			output('`$`bNicht schummeln!!`b Du darfst deinen eigenen Charakter nicht angreifen!');
		}
		elseif (user_get_online(0,$row))
		{
			output('`$Fehler:`4 Dieser Krieger ist inzwischen online.');
		}
		elseif ((int)$row['alive']!=1)
		{
			output('`$Fehler:`4 Dieser Krieger lebt nicht.');
		}
		elseif ($session['user']['playerfights']<=0)
		{
			output('`4Du bist zu müde, um heute einen weiteren Kampf mit einem Krieger zu riskieren.');
		}
		else
		{ //ja, Gegner ist gültig
			$battle=true;
			$row['pvp']=1;
			$row['creatureexp'] = round($row['creatureexp'],0);
			$row['playerstarthp'] = $session['user']['hitpoints'];
			$session['user']['badguy']=utf8_serialize($row);
			$session['user']['playerfights']--;
			$session['user']['buffbackup']='';
			$session['user']['buffbackup']='';
			if ($session['user']['pvpflag']==PVP_IMMU)
			{
				$session['user']['pvpflag']="1986-10-06 00:42:00";
				output('`n`b`4Deine Immunität ist hiermit verfallen!`0`b`n');
			}
			pvpwarning(true);

			if ($guard==false && $row['hot_item']!=1)
			{ //PvP-Flag nur setzen wenn keine Stadtwache und Opfer ohne Hot-Item
				user_update(
					array
					(
						'pvpflag'=>array('sql'=>true,'value'=>'NOW()'),
					),
					$row['acctid']
				);
			}
		}
	}
	else
	{
		output('Dieser Gegner '.$_GET['name'].' '.$_GET['id'].' wurde nicht gefunden. Schreibe bitte eine Anfrage.');
	}

	if (!$battle)
	{
		addnav('Zurück zum Stadtzentrum','village.php');
	}
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
		output('`0`b`&'.$badguy['creaturelose'].'`0`b`n');
		if ($guard)
		{ //Stadtwache
			output('`b`$Du hast '.$badguy['creaturename'].'`$ festgenommen und in den Kerker gesperrt!`0`b`n');
			
			$row2 = user_get_aei('sentence',$badguy['acctid']);
			if ($row2['sentence']==0)
			{
				addcrimes('`^HINWEIS`3 : `3Stadtwache `4'.$session['user']['name'].'`3 nimmt `4'.$badguy['creaturename'].'`$ ohne Haftbefehl `3fest.`n`&(Verfahren nur bei mehrfacher Wiederholung eröffnen!)');
			}
		}
		else
		{ //keine Stadtwache
			output('`b`$Du hast '.$badguy['creaturename'].'`$ besiegt!`0`b`n
			`#Du erbeutest `^'.$badguy['creaturegold'].'`# Gold!`n');
			$session['user']['gold']+=$badguy['creaturegold'];
			
			//Rassenzähler
			$arr_cas = utf8_unserialize((getsetting('race_casualties','')));
			if(isset($arr_cas[$badguy['race']][$session['user']['race']]))
			{
				$arr_cas[$badguy['race']][$session['user']['race']]++;
			}
			else
			{
				$arr_cas[$badguy['race']][$session['user']['race']] = 1;
			}
			savesetting('race_casualties',utf8_serialize($arr_cas));
			//Rassenzähler Ende
		}
		
		// Bounty Check - Darrell Morrone
		if ($badguy['creaturebounty']>0)
		{
			output('`#Außerdem erhältst du das Kopfgeld in Höhe von `^'.$badguy['creaturebounty'].'`# Gold!`n');
			$session['user']['reputation']+=2;
			$session['user']['gold']+=$badguy['creaturebounty'];
			$bountyrew=1;
			$bounty_crimestext='`&Hinweis: '.$badguy['creaturebounty'].' Gold Kopfgeld. ';
		}
		// End Bounty Check - Darrell Morrone

		$exp = round(getsetting('pvpattgain',10)*$badguy['creatureexp']/100,0);
		$expbonus = round(($exp * (1+0.1*($badguy['creaturelevel']-$session['user']['level']))) - $exp,0);
		if ($expbonus>0)
		{
			output('`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^'.$expbonus.'`# Erfahrungspunkte!`n');
			$session['user']['reputation']++;
		}
		else if ($expbonus<0)
		{
			output('`#*** Weil dieser Kampf so leicht war, verlierst du `^'.abs($expbonus).'`# Erfahrungspunkte!`n');
			$session['user']['reputation']--;
		}
		output('`#Du bekommst insgesamt `^'.($exp+$expbonus).'`# Erfahrungspunkte!`n`0');

		// start: xp-loss for killing lowdk players
		$xplossfactor = 0;
		$mindks = getsetting('pvpmindkxploss',10);
		$dksdiff = $session['user']['dragonkills'] - $badguy['dragonkills'];
		if ($dksdiff>$mindks)
		{
			$xplossfactor = 1 - (($badguy['dragonkills'] + 3) / ($session['user']['dragonkills']));
			if ($guard==false)
			{
				$session['user']['reputation']--;
			}
			$loss = max(round(($exp+$expbonus) * $xplossfactor),0);
			if($loss > 0) {
				output('`#Davon werden dir `$'.$loss.' `#Erfahrungspunkte abgezogen, weil dein Gegner '.$dksdiff.' Heldentat' . ($dksdiff > 1?'en':'') . ' weniger als du hat.');
			}
		}
		// end: xp-loss for killing lowdk players
		
		// GILDENMOD
		if ($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT)
		{
			
			$our_guild = &dg_load_guild($session['user']['guildid'],array('hitlist'));
			
			// Gildenkopfgeld:
			if ($our_guild['hitlist'][$badguy['acctid']])
			{
				$bounty = dg_hitlist_remove($session['user']['guildid'],$badguy['acctid']);
				output('`n`n`8Da '.$badguy['creaturename'].'`8 auf der Kofpgeldliste deiner Gilde stand, erhältst du `^'.$bounty.'`8 Gold als Belohnung!');
			}
			
			if ($badguy['guildid'] && $badguy['guildfunc'] != DG_FUNC_APPLICANT &&
			($session['user']['profession'] != PROF_GUARD && $session['user']['profession'] != PROF_GUARD_HEAD) )
			{
				
				output(dg_pvp_kill($badguy,1));
				
			}
			
		}
		// END GILDENMOD
		
		debuglog(($guard?'Festnahme: ':'PvP: '.$badguy['creaturegold'].' Gold ').$badguy['creaturebounty'].' Kopfgeld, Opfer:', $badguy['acctid']);
		$session['user']['experience']+=($exp+$expbonus-$loss);
		
		$badguy_news = db_real_escape_string('`4'.$badguy['creaturename'].'`3 hat dank der Stadtwache '.$session['user']['name'].'`3 eine gerechte Strafe erhalten!');
		
		if ($badguy['location']==USER_LOC_INN)
		{
			$killedin="`6der Kneipe";
			if ($guard==false)
			{ //keine Stadtwache
				addnews('`4'.$session['user']['name'].'`3 besiegt `4'.$badguy['creaturename'].'`3 brutal in einem Zimmer in der Kneipe!');
				addcrimes($bounty_crimestext.'`4'.$session['user']['name'].'`3 besiegt `4'.$badguy['creaturename'].'`3 brutal in einem Zimmer in der Kneipe!');
				$session['user']['reputation']-=6;
			}
			else
			{ //Stadtwache
				$sql = "INSERT INTO news SET newstext='".$badguy_news."',newsdate=NOW(),accountid=".$badguy['acctid'];
				db_query($sql);
			}
		}
		else if ($badguy['location']==USER_LOC_HOUSE)
		{
			$killedin="`6`2einem Haus";
			if ($guard==false)
			{ //keine Stadtwache
				addnews('`4'.$session['user']['name'].'`3 besiegt `4'.$badguy['creaturename'].'`3 bei einem Einbruch ins Haus!');
				addcrimes($bounty_crimestext.'`4'.$session['user']['name'].'`3 besiegt `4'.$badguy['creaturename'].'`3 bei einem Einbruch ins Haus!');
				$session['user']['reputation']-=12;
			}
			else
			{ //Stadtwache
				$sql = "INSERT INTO news SET newstext='".$badguy_news."',newsdate=NOW(),accountid=".$badguy['acctid'];
				db_query($sql);
			}
		}
		else
		{
			$killedin="`@den Feldern";
			if ($guard==false)
			{ //keine Stadtwache
				addnews('`4'.$session['user']['name'].'`3 besiegt `4'.$badguy['creaturename'].'`3 in einem Kampf in den Feldern.');
				addcrimes($bounty_crimestext.'`4'.$session['user']['name'].'`3 besiegt `4'.$badguy['creaturename'].'`3 in einem Kampf in den Feldern.');
				$session['user']['reputation']-=3;
			}
			else
			{ //Stadtwache
				$sql = "INSERT INTO news SET newstext='".$badguy_news."',newsdate=NOW(),accountid=".$badguy['acctid'];
				db_query($sql);
			}
		}

		// Add Bounty Kill to the News - Darrell Mororne
		if ($badguy['creaturebounty']>0)
		{
			addnews('`4'.$session['user']['name'].'`3 erhält `4'.$badguy['creaturebounty'].' Gold`3 als Lohn für die Ergreifung von `4'.$badguy['creaturename'].'`3!');
			$session['user']['reputation']++;
		}
		
		if ($guard==false)
		{ //keine Stadtwache
			
			// Items verlieren / gewinnen, PvP-Gewinn
			$item_hook_info ['min_chance'] = e_rand(1,100 );
			$item_hook_info ['loose_str'] = '';
			$item_hook_info ['win_str'] = '';
			$item_hook_info ['badguy'] = $badguy;
			
			$res = item_list_get(' owner='.$badguy['acctid'].' AND (deposit1 = 0 OR deposit1 = 9999999) AND pvp_victory_hook != "" ' );
			
			while ($i = db_fetch_assoc($res ) )
			{
				
				item_load_hook($i['pvp_victory_hook'] , 'pvp_victory' , $i );
				
			}
			output('`n' . $item_hook_info['win_str'] );

			$item_hook_info ['min_chance'] = e_rand(1,100 );
			$item_hook_info ['loose_str'] = '';
			$item_hook_info ['win_str'] = '';
			$item_hook_info ['badguy'] = $badguy;
			
			// Angelegte Items bei Sieg
			$res = item_list_get(' owner='.$session['user']['acctid'].' AND deposit1 = 9999999 AND pvp_victory_hook != "" ' );

			while ($i = db_fetch_assoc($res ) )
			{

				item_load_hook($i['pvp_victory_hook'] , 'pvp_victory' , $i );

			}
			output('`n' . $item_hook_info['win_str'] );

			// END Item Hook
			
			$sql = "SELECT gold FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$badguy['creaturegold']=((int)$row['gold']>(int)$badguy['creaturegold']?(int)$badguy['creaturegold']:(int)$row['gold']);
			
			
		}
		
		// Erfahrungsverlust fürs Opfer by Gunnar Kreitz
		$lostexp = round($badguy['creatureexp']*getsetting("pvpdeflose",5)/100,0);
		// start: xp-loss for killing lowdk players
		$lostexp -= round($lostexp*$xplossfactor,0);
		// end: xp-loss for killing lowdk players
		
		// Stats
		user_set_stats(array('pvpkilled'=>'pvpkilled+1'), $badguy['acctid'] );
		user_set_stats(array('pvpkills'=>'pvpkills+1') );

		// END Stats
		
		if ($guard==false)
		{ //keine Stadtwache
			$mailmessage = '`^'.$session['user']['name'].words_by_sex('`2 hat dich mit [seiner|ihrer] Waffe `^').$session['user']['weapon'].words_by_sex('`2 in '.$killedin.'`2 angegriffen und gewonnen!
			`n`n[Er|Sie] hatte anfangs `^'.$badguy['playerstarthp'].'`2 Lebenspunkte und kurz bevor du gestorben bist, hatte [er|sie] noch `^'.$session['user']['hitpoints'].'`2 Lebenspunkte übrig.
			`n`nDu hast `$'.$lostexp.'`2 deiner Erfahrungspunkte (etwa '.(round(getsetting('pvpdeflose',5)-$xplossfactor*getsetting('pvpdeflose',5))).'%) und `^'.$badguy['creaturegold'].'`2 Gold verloren.')
			.($badguy['creaturebounty']>0?' Dein Angreifer kassierte außerdem das Kopfgeld in Höhe von `^'.$badguy['creaturebounty'].' `2Gold ein.':'')
			. $item_hook_info['loose_str']
			.' `n`nDas Gericht wurde über diesen Fall bereits informiert. Du brauchst nichts weiter tun, denn Selbstjustiz ist ebenfalls strafbar.';
			systemmail($badguy['acctid'],'`2Du wurdest in '.$killedin.'`2 umgebracht',$mailmessage);

			user_update(
				array
				(
					'alive'=>0,
					'bounty'=>0,
					'goldinbank'=>array('sql'=>true,'value'=>'goldinbank-IF(gold<'.$badguy['creaturegold'].',gold-'.$badguy['creaturegold'].',0)'),
					'gold'=>array('sql'=>true,'value'=>'gold-'.$badguy['creaturegold']),
					'experience'=>array('sql'=>true,'value'=>'if(experience<'.$lostexp.',0,experience-'.$lostexp.')')
				),
				(int)$badguy['acctid']
			);
		}
		else
		{ //Stadtwache
			$mailmessage = '`^'.$session['user']['name'].words_by_sex('`2 hat dich mit [seiner|ihrer] Waffe `^').$session['user']['weapon'].words_by_sex('`2 in '.$killedin.'`2 gestellt und festgenommen!
			`n`n[Er|Sie] hatte anfangs `^'.$badguy['playerstarthp'].'`2 Lebenspunkte und kurz bevor du besiegt wurdest, hatte [er|sie] noch `^'.$session['user']['hitpoints'].'`2 Lebenspunkte übrig.
			`n`n Dein Angreifer kassierte `^'.$badguy['creaturebounty'].' `2Gold für deine Festnahme. Nun sitzt du im Kerker!');
			systemmail($badguy['acctid'],'`2Du wurdest in '.$killedin.'`2 festgenommen!',$mailmessage);

			$sentence=($row2['sentence']==0 ? 2 : $row2['sentence']);
			
			user_update(
				array
				(
					'alive'=>1,
					'bounty'=>0,
					'location'=>USER_LOC_PRISON,
					'imprisoned'=>$sentence,
					'restatlocation'=>0
				),
				(int)$badguy['acctid']
			);
			
			$sql = 'UPDATE account_extra_info SET sentence=0 WHERE acctid='.(int)$badguy['acctid'];
			db_query($sql);
			
			$sql = 'UPDATE keylist SET hvalue=0 WHERE hvalue>0 AND owner='.$badguy['acctid'];
			db_query($sql);
		}

		$_GET['op']='';
		if ($badguy['location']==USER_LOC_INN)
		{
			addnav('Zurück zur Kneipe','inn.php');
		}
		else if ($badguy['location']==USER_LOC_HOUSE)
		{
			addnav('Zurück zum Wohnviertel','houses.php');
		}

		elseif(!$session['user']['alive'])
		{
			addnav('Zurück zum Friedhof','shades.php');
		}
		else
		{
			addnav('Zurück zum Tor','dorftor.php');
		}

		//Trophäensammler
		$rowextra = user_get_aei('trophyhunter');
		
		if ($rowextra['trophyhunter']==1 && $guard==false)
		{
			if(item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND hvalue=".$badguy['acctid']) <3) //3 Teile eines Users selber sammeln erlaubt
			{
				output("`n`n`^Du überlegst dir, ob du dir nicht vielleicht ein Andenken an diesen Kampf mitnehmen solltest...");
				addnav("Trophäe");
				$who=rawurlencode($badguy['creaturename']);
				$id=$badguy['acctid'];
				addnav('Trophäe mitnehmen','trophy.php?op=look&who='.$who.'&id='.$id.'&dks='.$badguy['dragonkills'].'&where='.$badguy['location']);
			}
			else
			{
				output('`n`n`^Dieses Opfer hast du schon genug verstümmelt, du darfst dir keine weitere Trophäe abschneiden.');
			}
		}
		$badguy=array();
		
	}
	else if ($defeat)
	{
		addnav('Tägliche News','news.php');
		if ($badguy['location']==USER_LOC_INN)
		{
			$killedin='`6der Kneipe';
		}
		else if ($badguy['location']==USER_LOC_HOUSE)
		{
			$killedin='`2einem Haus';
		}
		else
		{
			$killedin='`@den Feldern';
		}
		
		$badguy['acctid']=(int)$badguy['acctid'];
		$badguy['creaturegold']=(int)$badguy['creaturegold'];
		
		// GILDENMOD
		$gp_add_msg = '';
		if ($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT
		&& $badguy['guildid'] && $badguy['guildfunc'] != DG_FUNC_APPLICANT)
		{
			
			$gp_add_msg = dg_pvp_kill($badguy,0);
			
		}
		// END GILDENMOD
		
		
		// Items verlieren, PvP-Verlust
		$item_hook_info ['min_chance'] = e_rand(1,100 );
		$item_hook_info ['badguy_acctid'] = $badguy ['acctid'];
		$item_hook_info ['loose_str'] = '';
		$item_hook_info ['win_str'] = '';
		$item_hook_info ['badguy'] = $badguy;
		
		$res = item_list_get(' owner= '.$session['user']['acctid'].' AND deposit1 = 0 AND pvp_defeat_hook != "" ' );
		
		while ($i = db_fetch_assoc($res ) )
		{
			
			item_load_hook($i['pvp_defeat_hook'] , 'pvp_defeat' , $i );
			
		}
		// END Item Hook
		
		
		
		//trophäen bei passiven pvp by bathi
		$pt_add_msg = '';
		
		$rowextraBadguy = user_get_aei('trophyhunter',$badguy['acctid']);
		
		if ($rowextraBadguy['trophyhunter']==1 && $guard==false)
		{
			if(item_count("owner=".$badguy['acctid']." AND tpl_id='trph' AND hvalue=".$session['user']['acctid']) <3) //3 Teile eines Users selber sammeln erlaubt
			{
				$what = '';
				$id = (int)$session['user']['acctid'];
				$bid = (int)$badguy['acctid'];
				$where = $badguy['location'];
				$dks = $session['user']['dragonkills'];
				$name = $session['user']['name'];
				
				// Kopf dieses Spielers bereits vorhanden ?
				if ( item_count("owner=".$bid." AND tpl_id='trph' AND value2='7' AND hvalue='".$id."'") == 0)
				{
					$what='Der '.($where<0?'vergammelte ':'').'Kopf';
					$nmb = 7;
				}
			
				// Rumpf dieses Spielers bereits vorhanden ?
				else if ( item_count("owner=".$bid." AND tpl_id='trph' AND value2='8' AND hvalue='".$id."'") == 0)
				{
					$what='Der '.($where<0?'vergammelte ':'').'Rumpf';
					$nmb = 8;
				}
				
				// 2 Ohren dieses Spieler bereits vorhanden ?
				else if ( item_count("owner=".$bid." AND tpl_id='trph' AND value2='1' AND hvalue='".$id."'") <=1)
				{
					$what='Ein '.($where<0?'vergammeltes ':'').'Ohr';
					$nmb = 1;
				}
			
				// 2 Augen dieses Spielers bereits vorhanden ?
				else if ( item_count("owner=".$bid." AND tpl_id='trph' AND value2='2' AND hvalue='".$id."'") <=1)
				{
					$what='Ein '.($where<0?'vergammeltes ':'').'Auge';
					$nmb = 2;
				}
			
				// 2 Hände dieses Spielers bereits vorhanden ?
				else if ( item_count("owner=".$bid." AND tpl_id='trph' AND value2='3' AND hvalue='".$id."'") <=1)
				{
					$what='Eine '.($where<0?'vergammelte ':'').'Hand';
					$nmb = 3;
				}
			
				// 2 Füße dieses Spielers bereits vorhanden ?
				else if ( item_count("owner=".$bid." AND tpl_id='trph' AND value2='4' AND hvalue='".$id."'") <=1)
				{
					$what='Ein '.($where<0?'vergammelter ':'').'Fuß';
					$nmb = 4;
				}
			
				// 2 Beine dieses Spielers bereits vorhanden ?
				else if ( item_count("owner=".$bid." AND tpl_id='trph' AND value2='5' AND hvalue='".$id."'") <=1)
				{
					$what='Ein '.($where<0?'vergammeltes ':'').'Bein';
					$nmb = 5;
				}
			
				// 2 Arme dieses Spielers bereits vorhanden ?
				else if ( item_count("owner=".$bid." AND tpl_id='trph' AND value2='6' AND hvalue='".$id."'") <=1)
				{
					$what='Ein '.($where<0?'vergammelter ':'').'Arm';
					$nmb = 6;
				}
			
				
				$value=($dks+1)*25;
			
				$item['tpl_name'] = db_real_escape_string($what." von ".$name);
				$item['tpl_gold'] = $value;
				$item['tpl_value1'] = $dks;
				$item['tpl_value2'] = $nmb;
				$item['tpl_hvalue'] = $id;
				
				if($where<0)
				{
					$item['tpl_description'] = db_real_escape_string($what." von ".$name."`0. Erworben in einer fiesen Grabschändung.");
				}
				else
				{
					$item['tpl_description'] = db_real_escape_string($what." von ".$name."`0. Erworben in einem fairen Kampf.");
				}
			
				item_add($bid,'trph',$item);
					
				$pt_add_msg = "`n`^".$what." von ".$name." wird dir als Andenken an diesen glorreichen Kampf dienen...`n";
				
			}
			else
			{
				$pt_add_msg = '`n`^Dieses Opfer hattest du schon genug verstümmelt, du durftest dir keine weitere Trophäe abschneiden.`n';
			}
		}
		
		//end
		
		
		
		systemmail($badguy['acctid'],'`2Du warst in '.$killedin.'`2 erfolgreich! ','`^'.$session['user']['name'].'`2 hat dich in '.$killedin.'`2 angegriffen, aber du hast gewonnen!
		`n`nDafür hast du `^'.round($session['user']['experience']*getsetting('pvpdefgain',10)/100,0).'`2 Erfahrungspunkte und `^'.$session['user']['gold'].'`2 Gold erhalten!'
		. $pt_add_msg . $gp_add_msg . $item_hook_info['win_str']
		);
		
		
		
		addnews('`%'.$session['user']['name'].'`5 wurde bei '.($session['user']['sex']?'ihrem':'seinem').'`5 Angriff auf`% '.$badguy['creaturename'].'`5 in '.$killedin.' `5getötet.`n'.get_taunt());
		
		user_update(
			array
			(
				'gold'=>array('sql'=>true,'value'=>'gold+'.(int)$session['user']['gold']),
				'experience'=>array('sql'=>true,'value'=>'experience+'.round($session['user']['experience']*getsetting('pvpdefgain',10)/100,0))
			),
			(int)$badguy['acctid']
		);
		
		$session['user']['alive']=false;
		
		debuglog('PvP-Tod: Items: \''.$item_hook_info['loose_str'].'\', Gold: '.$session['user']['gold'].', Gegner: ', $badguy['acctid']);
		
		$session['user']['gold']=0;
		$session['user']['hitpoints']=0;
		$session['user']['experience']=round($session['user']['experience']*(100-getsetting("pvpattlose",15))/100,0);
		$session['user']['badguy']="";
		
		output('`b`&Du wurdest von `%'.$badguy['creaturename'].' `&besiegt!!!`n
		`4Alles Gold, das du bei dir hattest, hast du verloren!`n'
		. $item_hook_info['loose_str']
		.'`4'.getsetting('pvpattlose',15).'%  deiner Erfahrung ging verloren!`n
		Du kannst morgen wieder kämpfen.');
		$session['user']['reputation']--;
		$badguy=array();
		page_footer();
	}

	else
	{
		fightnav(false,false);
	}
}

dg_save_guild();

page_footer();
?>