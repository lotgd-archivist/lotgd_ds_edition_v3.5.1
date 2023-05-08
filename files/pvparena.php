<?php

// 24072004

/*****************************************************
PvP-Arena by anpera 2004

For "Legend of the Green Dragon" 0.9.7 by Eric Stevens

This gives players the possibility to fight against other players with all
their specialties, mounts and buffs. The first non-automated PvP
system for LoGD that comes pretty close to real online PvP. :)

(Quite rough by now but Im still working on it.)

Fully compatible with lonnyl's battle-arena and battlepoint system!

Needs modifications on several files and in database!
SEE INSTALLATION INSTRUCTIONS AT:
http://www.anpera.net/forum/viewtopic.php?t=369

Modified with an idea by LordRaven:
Superuser is able to end fights.

ToDo:
- systemmail on interrupted battles
- bet system (place bets on winner as long as both players have
at least half of their maxhitpoints
*****************************************************/

require_once "common.php";
page_header("Turnierplatz");

music_set('arena');

function stats($row){  // Shows player stats
	global $session;
    $buffs='';

	//Falls $str_output irgendwie global ist xD
	if (!isset($str_output)) $str_output = '';
	if ($row['bufflist1']) $row['bufflist1']=adv_unserialize($row['bufflist1']);
	if ($row['bufflist2']) $row['bufflist2']=adv_unserialize($row['bufflist2']);
	if ($row['acctid1']==$session['user']['acctid'])
	{
		$atk=$row['att1'];
		$def=$row['def1'];
		//while (list($key,$val)=each($row[bufflist1])){
		foreach ($row['bufflist1'] AS $key => $val)
		{
			//output(" $val['name']/$val[badguyatkmod]/$val[atkmod]/$val[badguydefmod]/$val[defmod]`n");
			$buffs .= "`#".$val['name']." `7(".$val['rounds']." Runden übrig)`n";
			if (isset($val['atkmod'])) $atk *= $val['atkmod'];
			if (isset($val['defmod'])) $def *= $val['defmod'];
		}
		if ($row['bufflist2'])
		{
			//while (list($key,$val)=each($row[bufflist2])){
			foreach ($row['bufflist2'] AS $key => $val)
			{
				//output(" $val['name']/$val[badguyatkmod]/$val[atkmod]/$val[badguydefmod]/$val[defmod]`n");
				if (isset($val['badguyatkmod'])) $atk *= $val['badguyatkmod'];
				if (isset($val['badguydefmod'])) $def *= $val['badguydefmod'];
			}
		}
		$atk = round($atk, 2);
		$def = round($def, 2);
		$atk = ($atk == $row['att1'] ? "`^" : ($atk > $row['att1'] ? "`@" : "`$")) . "`b".$atk."`b`0";
		$def = ($def == $row['def1'] ? "`^" : ($def > $row['def1'] ? "`@" : "`$")) . "`b".$def."`b`0";
		if (count($row['bufflist1'])==0){
			$buffs = '`^Keine`0';
		}
	$str_output .= '
		`0<table align="center" border="0" cellpadding="2" cellspacing="2" class="vitalinfo0">
			<tr>
				<td class="charhead" colspan="4">
					`^`bVital-Info`b für diesen Kampf`0
				</td>
			</tr><tr>
				<td class="charinfo">`&Level:`0</td>
				<td class="charinfo">`^`b'.$row['lvl1'].'`b`0</td>
			</tr><tr>
				<td class="charinfo">`&Lebensp.:`0</td>
				<td class="charinfo">`^'.$row['hp1'].'/`0'.$row['maxhp1'].'</td>
			</tr><tr>
				<td class="charinfo">`&Angriff:`0</td>
				<td class="charinfo">'.$atk.'</td>
			</tr><tr>
				<td class="charinfo">`&Verteidigung:`0</td>
				<td class="charinfo">'.$def.'</td>
			</tr><tr>
				<td class="charinfo">`&Waffe:`0</td>
				<td class="charinfo">`^'.$row['weapon1'].'`0</td>
			</tr><tr>
				<td class="charinfo">`&Rüstung:`0</td>
				<td class="charinfo">`^'.$row['armor1'].'`0</td>
			</tr><tr>
				<td class="charinfo" colspan="2">`&Aktionen:`n'.$buffs.'`0</td>
		';
	}
	if ($row['acctid2']==$session['user']['acctid'])
	{
		$atk=$row['att2'];
		$def=$row['def2'];
		
		foreach ($row['bufflist2'] AS $key => $val)
		{
			$buffs .= '`#'.$val['name'].' `7('.$val['rounds'].' Runden übrig)`n';
			if (isset($val['atkmod'])) $atk *= $val['atkmod'];
			if (isset($val['defmod'])) $def *= $val['defmod'];
		}
		if ($row['bufflist1']){
			
			foreach ($row['bufflist1'] AS $key => $val)
			{
				if (isset($val['badguyatkmod'])) $atk *= $val['badguyatkmod'];
				if (isset($val['badguydefmod'])) $def *= $val['badguydefmod'];
			}
		}
		$atk = round($atk, 2);
		$def = round($def, 2);
		$atk = ($atk == $row['att2'] ? "`^" : ($atk > $row['att2'] ? "`@" : "`$")) . "`b".$atk."`b`0";
		$def = ($def == $row['def2'] ? "`^" : ($def > $row['def2'] ? "`@" : "`$")) . "`b".$def."`b`0";
		if (count($row['bufflist2'])==0){
			$buffs = '`^Keine`0';
		}
	$str_output .= '
		`0<table align="center" border="0" cellpadding="2" cellspacing="2" class="vitalinfo0">
			<tr>
				<td class="charhead" colspan="4">
					`^`bVital-Info`b für diesen Kampf`0
				</td>
			</tr><tr>
				<td class="charinfo">`&Level:`0</td>
				<td class="charinfo">`^`b'.$row['lvl2'].'`b`0</td>
			</tr><tr>
			<td class="charinfo">`&Lebenspunkte:`0</td>
			<td class="charinfo">`^'.$row['hp2'].'/`0'.$row['maxhp2'].'
			</td>
			</tr><tr>
			<td class="charinfo">`&Angriff:`0</td>
				<td class="charinfo">'.$atk.'</td>
			</tr><tr>
			<td class="charinfo">`&Verteidigung:`0</td>
				<td class="charinfo">'.$def.'</td>
			</tr><tr>
				<td class="charinfo">`&Waffe:`0</td>
				<td class="charinfo">`^'.$row['weapon2'].'`0</td>
			</tr><tr>
				<td class="charinfo">`&Rüstung:`0</td>
				<td class="charinfo">`^'.$row['armor2'].'`0
			</td>
		</tr>
		<tr>
			<td class="charinfo" colspan="2">
				`&Aktionen:`n'.$buffs.'`0
			</td>
		';
	}
	$str_output .= '</tr></table>`n';
	
	output($str_output);
	
}

function arenanav($row){ // Navigation during fight

	global $session;

	if ($row['turn']==1){
		$badguy = array(
			"acctid"	=> $row['acctid2'],
			"name"		=> $row['name2'],
			"level"		=> $row['lvl2'],
			"hitpoints"	=> $row['hp2'],
			"attack"	=> $row['att2'],
			"defense"	=> $row['def2'],
			"weapon"	=> $row['weapon2'],
			"armor"		=> $row['armor2'],
			"bufflist"	=> $row['bufflist2']
		);
		$goodguy = array(
			"name"			=> $row['name1'],
			"level"			=> $row['lvl1'],
			"hitpoints"		=> $row['hp1'],
			"maxhitpoints"	=> $row['maxhp1'],
			"attack"		=> $row['att1'],
			"defense"		=> $row['def1'],
			"weapon"		=> $row['weapon1'],
			"armor"			=> $row['armor1'],
			"specialtyuses"	=> $row['specialtyuses1'],
			"bufflist"		=> $row['bufflist1']
		);
	}
	if ($row['turn']==2){
		$badguy = array(
			"acctid"	=> $row['acctid1'],
			"name"		=> $row['name1'],
			"level"		=> $row['lvl1'],
			"hitpoints"	=> $row['hp1'],
			"attack"	=> $row['att1'],
			"defense"	=> $row['def1'],
			"weapon"	=> $row['weapon1'],
			"armor"		=> $row['armor1'],
			"bufflist"	=> $row['bufflist1']
		);
		$goodguy = array(
			"name"			=> $row['name2'],
			"level"			=> $row['lvl2'],
			"hitpoints"		=> $row['hp2'],
			"maxhitpoints"	=> $row['maxhp2'],
			"attack"		=> $row['att2'],
			"defense"		=> $row['def2'],
			"weapon"		=> $row['weapon2'],
			"armor"			=> $row['armor2'],
			"specialtyuses"	=> $row['specialtyuses2'],
			"bufflist"		=> $row['bufflist2']
		);
	}

	$GLOBALS['goodguy']=$goodguy;
	$GLOBALS['badguy']=$badguy;

	if ($goodguy['hitpoints']>0 && $badguy['hitpoints']>0) {
		$str_output = '';
		$str_output .= '`c`b`$~ ~ ~ Kampf ~ ~ ~`0`b`c`n';
		$str_output .= '`@Du hast den Gegner `^'.$badguy['name'].'`@ entdeckt, der sich mit seiner Waffe `%'.$badguy['weapon'].'`@';
		// Let's display what buffs the opponent is using - oh yeah
		$buffs = '';
		$disp['bufflist'] = adv_unserialize($badguy['bufflist']);

		foreach ($disp['bufflist'] AS $key => $val)
		{
			$buffs .= ' `@und `#'.$val['name'].' `7('.$val['rounds'].' Runden)';
		}
		$str_output .= $buffs.' `@auf dich stürzt!`0`n
			`n
			`2Level: `t'.$badguy['level'].'`0`n
			`2`bErgebnis der letzten Runde:`b`n
			`2'.$badguy['name']."`2's Lebenspunkte: `t".$badguy['hitpoints'].'`0`n
			`2DEINE Lebenspunkte: `t'.$goodguy['hitpoints'].'`0`n
			`n
			<hr />`n
			'.$row['lastmsg'];
		output($str_output);
	}
	addnav('Kampf');
	addnav('Kämpfen','pvparena.php?op=fight&act=fight');
	addnav('`bBesondere Fähigkeiten`b');
	$sql_mod = "
		SELECT 
			* 
		FROM 
			`specialty` 
		WHERE 
			`active`	= '1'
	";
	$result = db_query($sql_mod);
	while($row_mod = db_fetch_assoc($result))
	{
	require_once "./module/specialty_modules/".$row_mod['filename'].".php";
	// $f1 = $row['filename']."_info";
	// $f1();

	$f2 = $row_mod['filename']."_run";
	$f2("fightnav",0,"pvparena.php?op=fight","goodguy");
	}

	if ($row['turn']==1) $owner=$row['acctid1'];
	if ($row['turn']==2) $owner=$row['acctid2'];

	// spells by anpera, modded by talion
	if($row['nospecials']==0)
	{
		$result = item_list_get("
			`owner`		= '".$session['user']['acctid']."' AND 
			`value1`	> '0' AND
			(`battle_mode` = '2' OR `battle_mode` = '3') ", " 
		GROUP BY 
			`name` 
		ORDER BY 
			`value1` ASC, 
			`name` ASC, 
			`id` ASC 
		LIMIT 
			9
			", true , ' SUM(`value1`) AS `anzahl`, `name`, `id` ' );

		$int_count = db_num_rows($result);

		if ($int_count>0) addnav('Zauber');

		for ($i=1;$i<=$int_count;$i++)
		{
			$row = db_fetch_assoc($result);

			addnav($i.'?'.$row['name'].' `0('.$row['anzahl'].'x)','pvparena.php?op=fight&skill=zauber&itemid='.$row['id']);
		}
	}
	// end spells


}
function activate_buffs($tag) { // activate buffs (from battle.php with modifications for multiplayer battle)
	global $goodguy,$badguy,$message,$session;
	
	$result = array();
	$result['invulnerable'] = 0; // not in use
	$result['dmgmod'] = 1;
	$result['badguydmgmod'] = 1; // not in use
	$result['atkmod'] = 1;
	$result['badguyatkmod'] = 1; // not in use
	$result['defmod'] = 1;
	$result['badguydefmod'] = 1;
	$result['lifetap'] = array();
	$result['dmgshield'] = array();
	
	foreach ($goodguy['bufflist'] AS $key => $buff)
	{
		if (isset($buff['startmsg'])) {
			$msg = $buff['startmsg'];
			$msg = str_replace("{badguy}", $badguy['name'], $msg);
			output("`%$msg`0");
			$message=$message.$goodguy['name'].': "`0`i'.$msg.'`0`i"`n';
			unset($goodguy['bufflist'][$key]['startmsg']);
		}
		$activate = mb_strpos($buff['activate'], $tag);
		if ($activate !== false) $activate = true; // handle mb_strpos == 0;
		// If this should activate now and it hasn't already activated,
		// do the round message and mark it.
		if ($activate && !$buff['used']) {
			// mark it used.
			$goodguy['bufflist'][$key]['used'] = 1;
			// if it has a 'round message', run it.
			if (isset($buff['roundmsg'])) {
				$msg = $buff['roundmsg'];
				$msg = str_replace("{badguy}", $badguy['name'], $msg);
				if ($msg > ""){
					output("`)$msg`0`n");
					$message=$message.$goodguy['name'].': "`0`i'.$msg.'`0`i"`n';
				}
			}
		}
		// Now, calculate any effects and run them if needed.
		if (isset($buff['invulnerable']))
		{
			$result['invulnerable'] = 1;
		}
		if (isset($buff['atkmod']))
		{
			$result['atkmod'] *= $buff['atkmod'];
		}
		if (isset($buff['badguyatkmod']))
		{
			$result['badguyatkmod'] *= $buff['badguyatkmod'];
		}
		if (isset($buff['defmod']))
		{
			$result['defmod'] *= $buff['defmod'];
		}
		if (isset($buff['badguydefmod']))
		{
			$result['badguydefmod'] *= $buff['badguydefmod'];
		}
		if (isset($buff['dmgmod']))
		{
			$result['dmgmod'] *= $buff['dmgmod'];
		}
		if (isset($buff['badguydmgmod']))
		{
			$result['badguydmgmod'] *= $buff['badguydmgmod'];
		}
		if (isset($buff['lifetap']))
		{
			array_push($result['lifetap'], $buff);
		}
		if (isset($buff['damageshield']))
		{
			array_push($result['dmgshield'], $buff);
		}
		if (isset($buff['regen']) && $activate)
		{
			$hptoregen = (int)$buff['regen'];
			$hpdiff = $goodguy['maxhitpoints'] - $goodguy['hitpoints'];
			// Don't regen if we are above max hp
			if ($hpdiff < 0) $hpdiff = 0;
			if ($hpdiff < $hptoregen) $hptoregen = $hpdiff;
			$goodguy['hitpoints'] += $hptoregen;
			// Now, take abs value just incase this was a damaging buff
			$hptoregen = abs($hptoregen);
			if ($hptoregen == 0)
			{
				$msg = $buff['effectnodmgmsg'];
			}
			else
			{
				$msg = $buff['effectmsg'];
			}
			$msg = str_replace("{badguy}", $badguy['name'], $msg);
			$msg = str_replace("{damage}", $hptoregen, $msg);
			if ($msg > ""){
				output("`)".$msg."`0`n");
				$message = $message.$goodguy['name'].': "`0`i'.$msg.'`0`i"`n';
			}
		}
		if (isset($buff['minioncount']) && $activate) {
			$who = -1;

			if (isset($buff['maxbadguydamage']) &&  $buff['maxbadguydamage'] != '')
			{
				if (isset($buff['maxbadguydamage'])  && $buff['maxbadguydamage'] != '')
				{
					$buff['maxbadguydamage'] = stripslashes($buff['maxbadguydamage']);
                    eval(utf8_eval("\$buff['maxbadguydamage'] = $buff[maxbadguydamage];
					"));
				}
				$max = $buff['maxbadguydamage'];

				if (isset($buff['minbadguydamage']) && $buff['minbadguydamage'] != '')
				{
					$buff['minbadguydamage'] = stripslashes($buff['minbadguydamage']);
                    eval(utf8_eval("\$buff['minbadguydamage'] = $buff[minbadguydamage];
					"));
				}
				$min = $buff['minbadguydamage'];
				$who = 0;
			}
			else
			{
				$max = $buff['maxgoodguydamage'];
				$min = $buff['mingoodguydamage'];
				$who = 1;
			}
			for ($i = 0; $who >= 0 && $i < $buff['minioncount']; $i++) {
				$damage = e_rand($min, $max);

				if ($who == 0)
				{
					$badguy['hitpoints'] -= $damage;
				}
				else if ($who == 1) 
				{
					$goodguy['hitpoints'] -= $damage;
				}

				if ($damage < 0)
				{
					$msg = $buff['effectfailmsg'];
				}
				else if ($damage == 0)
				{
					$msg = $buff['effectnodmgmsg'];
				}
				else if ($damage > 0)
				{
					$msg = $buff['effectmsg'];
				}
				if ($msg>"") {
					$msg = str_replace("{badguy}", $badguy['name'], $msg);
					$msg = str_replace("{goodguy}", $session['user']['name'], $msg);
					$msg = str_replace("{damage}", $damage, $msg);
					output('`)'.$msg.'`0`n');
					$message=$message.$goodguy['name'].': "`0`i'.$msg.'`0`i"`n';
				}
			}
		}
	}
	
	foreach ($badguy['bufflist'] AS $key => $buff)
	{
		$activate = mb_strpos($buff['activate'], $tag);
		if ($activate !== false) $activate = true;
		if ($activate && !$buff['used'])
		{
			$badguy['bufflist'][$key]['used'] = 1;
		}
		if (isset($buff['atkmod']))
		{
			$result['badguyatkmod'] *= $buff['atkmod'];
		}
		if (isset($buff['defmod']))
		{
			$result['badguydefmod'] *= $buff['defmod'];
		}
		if (isset($buff['badguyatkmod']))
		{
			$result['atkmod'] *= $buff['badguyatkmod'];
		}
		if (isset($buff['badguydefmod']))
		{
			$result['defmod'] *= $buff['badguydefmod'];
		}
		if (isset($buff['badguydmgmod']))
		{
			$result['dmgmod'] *= $buff['badguydmgmod'];
		}
	}
	return $result;
}
function process_lifetaps($ltaps, $damage) {
	global $goodguy,$badguy,$goodguy,$message;

	$str_output = '';

	foreach($ltaps AS $key => $buff)
	{
		$healhp = $goodguy['maxhitpoints'] - $goodguy['hitpoints'];
		if ($healhp < 0) $healhp = 0;
		if ($healhp == 0)
		{
			$msg = $buff['effectnodmgmsg'];
		}
		else
		{
			if ($healhp > $damage * $buff['lifetap']) $healhp = $damage * $buff['lifetap'];
			if ($healhp < 0) $healhp = 0;
			if ($damage > 0)
			{
				$msg = $buff['effectmsg'];
			}
			else if ($damage == 0)
			{
				$msg = $buff['effectfailmsg'];
			}
			else if ($damage < 0)
			{
				$msg = $buff['effectfailmsg'];
			}
		}
		$goodguy['hitpoints'] += $healhp;
		$msg = str_replace("{badguy}",$badguy['name'], $msg);
		$msg = str_replace("{damage}",$healhp, $msg);
		if ($msg > ''){
			$str_output .= '`)'.$msg.'`n';
			$message = $message.$goodguy['name'].': "`0`i'.$msg.'`0`i"`n';
		}
	}
	output($str_output);
}

function process_dmgshield($dshield, $damage) {
	global $session,$badguy,$goodguy,$message;

	$str_output = '';

	foreach ($dshield AS $key => $buff)
	{
		$realdamage = $damage * $buff['damageshield'];
		if ($realdamage < 0) $realdamage = 0;
		if ($realdamage > 0) {
			$msg = $buff['effectmsg'];
		} else if ($realdamage == 0) {
			$msg = $buff['effectnodmgmsg'];
		} else if ($realdamage < 0) {
			$msg = $buff['effectfailmsg'];
		}
		$badguy['hitpoints'] -= $realdamage;
		$msg = str_replace("{badguy}",$badguy['name'], $msg);
		$msg = str_replace("{damage}",$realdamage, $msg);
		if ($msg > ""){
			$str_output .= '`)'.$msg.'`n';
			$message = $message.$goodguy['name'].': "`0`i'.$msg.'`0`i"`n';
		}
	}
}
function expire_buffs() {
	global $badguy,$message;
	$str_output = '';
	$goodguy = $GLOBALS['goodguy'];

	foreach ($goodguy['bufflist'] AS $key => $buff)
	{
		if ($buff['used']) {
			$GLOBALS['goodguy']['bufflist'][$key]['used'] = 0;
			$GLOBALS['goodguy']['bufflist'][$key]['rounds']--;

			if ($GLOBALS['goodguy']['bufflist'][$key]['rounds'] <= 0) {
				if ($buff['wearoff']) {
					$msg = $buff['wearoff'];
					$msg = str_replace("{badguy}", $badguy['name'], $msg);
					if ($msg > ""){
						$str_output .= '`)'.$msg.'`n';
						$message = $message.$goodguy['name'].': "`0`i'.$msg.'`0`i"`n';
					}
				}
				unset($GLOBALS['goodguy']['bufflist'][$key]);
			}
		}
	}
	output($str_output);
}

addcommentary();
$cost=$session['user']['level']*20;

if ($_GET['op']=='challenge') //jemanden suchen / herausfordern
{
	if($_GET['acctid']=='' && $session['user']['playerfights']>0 && ($session['user']['level']>4 || $session['user']['dragonkills']>0))
	{
		$search='';
		if ((isset($_POST['search']) && $_POST['search']!='') || $_GET['search']>"")
		{
			if ($_GET['search']>'') $_POST['search']=$_GET['search'];
			$search = str_create_search_string($_POST['search']);
			$search ="a.name LIKE '".$search."' AND ";
			$linkoptions='&search='.$_POST['search'];
		}
		if(isset($_POST['dkmin']) || $_GET['dkmin']>0)
		{
			$dkmin=intval($_POST['dkmin']) + intval($_GET['dkmin']);
			if($dkmin>0)
			{
				$search.="a.dragonkills >= ".$dkmin." AND ";
				$linkoptions.='&dkmin='.$dkmin;
			}
		}
		if(isset($_POST['dkmax']) || $_GET['dkmax']>0)
		{
			$dkmax=intval($_POST['dkmax']) + intval($_GET['dkmax']);
			if($dkmax>0)
			{
				$search.="a.dragonkills <= ".$dkmax." AND ";
				$linkoptions.='&dkmax='.$dkmax;
			}
		}
		if($_POST['online']=='1' || $_GET['online']=='1')
		{
			$timeout = getsetting('LOGINTIMEOUT',900);
			$timeout_date = date( 'Y-m-d H:i:s' , time() - $timeout );
			$search.="a.loggedin=1 AND laston>'".$timeout_date."' AND";
			$linkoptions.='&online=1';
		}

		$ppp=25; // Players Per Page to display
		if (!$_GET['limit'])
		{
			$page=0;
		}
		else
		{
			$page=(int)$_GET['limit'];
			addnav('Vorherige Seite','pvparena.php?op=challenge&limit='.($page-1).$linkoptions);
		}
		$limit="".($page*$ppp).','.($ppp+1); // love PHP for this ;)
		pvpwarning();
		$days = getsetting('pvpimmunity', 5);
		$exp = getsetting('pvpminexp', 1500);
		
		output('`tWen willst du zu einem Duell mit allen Fähigkeiten herausfordern? Die Turniergebühr kostet dich `^'.$cost.' `tGold. Dein Gegner wird nicht unvorbereitet zustimmen.`n
		Herausforderungen aussprechen kannst du außerdem nur gegen Gegner, die mindestens Stufe fünf erlangt oder aber bereits eine Heldentat vollbracht haben.`n
		`nDu kannst heute noch `4'.$session['user']['playerfights'].'`t mal gegen einen anderen Krieger antreten.`n`n
		`0<form action="pvparena.php?op=challenge" method="POST">`wSuchen nach:`0
		`nName: <input name="search" value="'.$_POST['search'].'">
		`nDK von: <input name="dkmin" value="'.$_POST['dkmin'].'" size="3">
		bis: <input name="dkmax" value="'.$_POST['dkmax'].'" size="3">
		`nonline: <select name="online">
		<option value="1">Ja</option>
		<option value="0"'.($_POST['online']==='0'?' selected':'').'>Egal</option>
		</select>
		`n<input type="submit" class="button" value="Suchen">
		</form>
		`n',true);
		addnav('','pvparena.php?op=challenge');
		
		$bool_lockhtml = $access_control->su_check(access_control::SU_RIGHT_LOCKHTML);
		$sql = "
			SELECT 	
				a.login,
				a.acctid,
				a.imprisoned,
				a.activated,
				a.expedition,
				a.sex,
				a.name,
				a.profession,
				a.alive,
				a.location,
				a.level,
				a.laston,
				a.loggedin,
				a.pvpflag,
				a.dragonkills,
				a.experience,
				a.locked
				".($bool_lockhtml ? ", aei.html_locked" : "")."
			FROM 
				accounts a
				".($bool_lockhtml ? "INNER JOIN account_extra_info aei ON a.acctid = aei.acctid" : "")." 
			WHERE 
				".$search."
				(a.locked	= '0')	AND".
//				(age 			> '".$days."' OR a.dragonkills > '0' OR pk > '0' OR a.experience > '".$exp."') AND
//				age 			<= '".getsetting('maxagepvp',50)."' 	AND
//				a.level		>= '".($session['user']['level']-1)."' 	AND 
//				a.level		<= '".($session['user']['level']+2)."' 	AND
				"(a.level > 4 OR a.dragonkills > 0) AND 
				a.acctid	!= '".$session['user']['acctid']."'
			ORDER BY 
				a.login='".db_real_escape_string($_POST['search'])."' DESC,
				a.dragonkills 	DESC,
				a.level 		DESC
			LIMIT 
				".$limit;
		$result = db_query($sql);
		if (db_num_rows($result)>$ppp) 
		{
			addnav('Nächste Seite','pvparena.php?op=challenge&limit='.($page+1).$linkoptions);
		}
		$str_out = '
			<table border="0" cellpadding="2" cellspacing="1" bgcolor="#999999">
				<tr class="trhead">
					<th>Name</th>
					<th>DK</th>
					<th>Level</th>
					<th>Status</th>
				</tr>';
		$max = db_num_rows($result);
		for ($i=0;$i<$max;$i++)
		{
			$row = db_fetch_assoc($result);
			$loggedin=user_get_online(0,$row);
			$str_out .= '
				<tr class="'.($i%2?'trlight':'trdark').'">
					<td>'.CRPChat::menulink($row,'arena,arenano' ).'`0</td>
					<td align="right">'.$row['dragonkills'].'</td>
					<td align="center">'.$row['level'].'</td>
					<td>'.($loggedin?'`#Online`0':'`3Offline`0').'</td>
				</tr>';
			addnav('','pvparena.php?op=challenge&acctid='.$row['acctid']);
			addnav('','pvparena.php?op=challenge&nospec=1&acctid='.$row['acctid']);
		}
		$str_out .= '</table>';
		output($str_out);
		addnav('Zurück zum Turnierplatz','pvparena.php');
	}
	else if ($session['user']['playerfights']<=0)
	{
		output('`tDu kannst heute keinen weiteren Krieger mehr herausfordern.');
		addnav('Zurück zum Turnierplatz','pvparena.php');
	}
	else if ($session['user']['level']<=4 && $session['user']['dragonkills']==0)
	{
		output('`tDer Arenawärter lacht sich darüber kaputt, dass ein so kleiner Schwächling wie du in der Arena kämpfen will. Vielleicht solltest du wirklich erst etwas mehr Kampferfahrung sammeln.');
		addnav('Zurück zum Turnierplatz','pvparena.php');
	}
	else
	{
		if ($session['user']['gold']>=$cost)
		{
			$sql = "
				SELECT 
					`acctid`,
					`name`,
					`level`,
					`sex`,
					`hitpoints`,
					`maxhitpoints`,
					`lastip`,
					`emailaddress`,
					`uniqueid` 
				FROM 
					`accounts` 
				WHERE 
					`acctid`	= '".$_GET['acctid']."'
			";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);

			if (ac_check($row))
			{
				output('`n`4Du kannst deine eigenen oder derart verwandte Spieler nicht zu einem Duell herausfordern!`0`n`n');
			}
			else
			{
				$sql = "
					SELECT 
						* 
					FROM 
						`pvp` 
					WHERE 
						`acctid2`	= '".$session['user']['acctid']."' 	OR 
						`acctid1`	= '".$row['acctid']."' 				OR 
						`acctid2`	= '".$row['acctid']."'
				";
				$result = db_query($sql);
				if (db_num_rows($result))
				{
					output('`tBei dieser Herausforderung ist dir jemand zuvor gekommen!');
				}
				else
				{
					$nospec = (bool)$_GET['nospec'];
					if ($nospec)
					{
						$specs = utf8_serialize(array());
						$buffs = $specs;
					}
					else
					{
						$specs = db_real_escape_string(utf8_serialize($session['user']['specialtyuses']));
						$buffs = db_real_escape_string(is_array($session['user']['bufflist'])?utf8_serialize($session['user']['bufflist']):$session['user']['bufflist']);
					}
					$nospec = (int)$nospec;
					
					$sql = "
						INSERT INTO 
							`pvp` 
						SET
							`acctid1`		= '".$session['user']['acctid']."',
							`acctid2`		= '".$row['acctid']."',
							`name1`			= '".db_real_escape_string($session['user']['name'])."',
							`name2`			= '".db_real_escape_string($row['name'])."',
							`lvl1`			= '".$session['user']['level']."',
							`lvl2`			= '".$row['level']."',
							`hp1`			= '".$session['user']['hitpoints']."',
							`maxhp1`		= '".$session['user']['maxhitpoints']."',
							`att1`			= '".$session['user']['attack']."',
							`def1`			= '".$session['user']['defence']."',
							`weapon1`		= '".db_real_escape_string($session['user']['weapon'])."',
							`armor1`		= '".db_real_escape_string($session['user']['armor'])."',
							`specialtyuses1`	= '".$specs."',
							`bufflist1`		= '".$buffs."',
							`turn`			= '0',
							`nospecials`	= '".$nospec."'
					";
					db_query($sql);
					if (db_affected_rows(LINK)<=0) redirect('pvparena.php');
					output('
						`tDu hast`4 '.$row['name'].' `tzu einem Duell herausgefordert und 
						wartest nun auf '.($row['sex']?'ihre':'seine').' Antwort. 
						Du könntest '.$row['name'].'`t den Kampf schmackhafter machen, 
						indem du '.($row['sex']?'ihr':'ihm').' die Arenagebühr 
						von  `^'.($row['level']*20).'`t Gold überweist.`n
					');
					if ($session['user']['dragonkills']<2) 
						output('
							`n
							`n
							`i(Du kannst jetzt ganz normal weiterspielen. 
								Wenn '.$row['name'].'`t sich meldet, bekommst du eine Nachricht.)`i
						');
					systemmail($row['acctid'],'`2Du wurdest herausgefordert!','
						`2'.$session['user']['name'].'`2 (Level '.$session['user']['level'].') 
						hat dich zu einem Duell auf dem Turnierplatz 
						'.($nospec?'`&`bohne Spezialfähigkeiten`b`2 ':'').'herausgefordert. 
						Du kannst diese Herausforderung auf dem Turnierplatz annehmen oder ablehnen, 
						solange sich dein Level nicht ändert.`n
						Bereite dich gut vor und betritt den Turnierplatz erst dann!
					');
					$session['user']['gold']-=$cost;
					$session['user']['reputation']++;
				}
			}
			addnav('Zurück zum Turnierplatz','pvparena.php');
		}
		else
		{
			output('`4Du hast nicht genug Gold dabei, um die Arenagebühr zu bezahlen. Mit rotem Kopf ziehst du ab.');
			addnav('Zurück zum Turnierplatz','pvparena.php');
		}
	}
	addnav('Zurück zum Stadtzentrum','village.php');
}

else if ($_GET['op']=='deny') //Herausforderung ablehnen
{
	$sql = "
		DELETE FROM 
			`pvp` 
		WHERE 
			`acctid2`	= '".$session['user']['acctid']."'
	";
	db_query($sql);
	$sql = "
		SELECT 
			`acctid`,
			`name` 
		FROM 
			`accounts` 
		WHERE 
			`acctid`	= '".$_GET['id']."'
	";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	output('
		`tBeim Anblick deines Gegners '.$row['name'].' `twird dir Angst und Bange. 
		Mit ein paar sehr dürftigen Ausreden wie "nicht genug Gold" lehnst du die Herausforderung ab und 
		verlässt schnell den Turnierplatz.
	');
	systemmail($row['acctid'],'`2Herausforderung abgelehnt','
		`2'.$session['user']['name'].'`2 hat deine Herausforderung abgelehnt. 
		Vielleicht solltest du '.($session['user']['sex']?'ihr':'ihm').' etwas für den Kampf anbieten - 
		falls '.($session['user']['sex']?'sie':'er').' dich besiegen kann.
	');
	addnav('Zurück zum Stadtzentrum','village.php');
}

else if ($_GET['op']=='accept') //Herausforderung annehmen
{
	if($session['user']['gold']<$cost)
	{
		output('`4Du kannst dir die Arena-Nutzungsgebühr von `^'.$cost.'`4 Gold nicht leisten.');
		addnav('Zurück zum Stadtzentrum','village.php');
	}
	else if($session['user']['playerfights']<=0)
	{
		output('`4Du kannst heute nicht mehr gegen andere Krieger antreten.');
		addnav('Zurück zum Stadtzentrum','village.php');
	}
	else
	{
		if ($_GET['nospec'])
		{
			$specs = utf8_serialize(array());
			$buffs = $specs;
		}
		else
		{
			$specs = db_real_escape_string(utf8_serialize($session['user']['specialtyuses']));
			$buffs = db_real_escape_string(is_array($session['user']['bufflist'])?utf8_serialize($session['user']['bufflist']):$session['user']['bufflist']);
		}
		$sql = "
			UPDATE 
				`pvp` 
			SET 
				`name2`				= '".db_real_escape_string($session['user']['name'])."',
				`hp2`				= '".$session['user']['hitpoints']."',
				`maxhp2`			= '".$session['user']['maxhitpoints']."',
				`att2`				= '".$session['user']['attack']."',
				`def2`				= '".$session['user']['defence']."',
				`weapon2`			= '".db_real_escape_string($session['user']['weapon'])."',
				`armor2`			= '".db_real_escape_string($session['user']['armor'])."',
				`specialtyuses2`	= '".$specs."',
				`bufflist2`			= '".$buffs."',
				`turn`				= '2' 
			WHERE 
				`acctid2`	= '".$session['user']['acctid']."'
		";
		db_query($sql);
		if (db_affected_rows(LINK)<=0) redirect('pvparena.php');
		$sql = "
			SELECT 
				* 
			FROM 
				`pvp` 
			WHERE 
				`acctid1`	= '".$session['user']['acctid']."' OR 
				`acctid2`	= '".$session['user']['acctid']."'
		";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$row['specialtyuses1'] = adv_unserialize($row['specialtyuses1']);
		$row['specialtyuses2'] = adv_unserialize($row['specialtyuses2']);

		$session['user']['gold'] -= $cost;
		$session['user']['reputation']++;
		arenanav($row);
		stats($row);
	}
}

else if ($_GET['op']=='back') //Herausforderung zurückziehen
{
	$sql = "
		SELECT 
			`acctid`,
			`name` 
		FROM 
			`accounts` 
		WHERE 
			`acctid`	= '".$_GET['id']."'
	";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	output('
		`tDu bist es Leid, auf '.$row['name'].'`t zu warten und ziehst deine Herausforderung zurück. 
		Die Arenagebühr bekommst aber trotz langer Verhandlungen mit der Arena-Leitung nicht zurück.`n
	');
	$sql = "
		DELETE FROM 
			`pvp` 
		WHERE 
			`acctid1`	= '".$session['user']['acctid']."'
	";
	db_query($sql);
	$session['user']['reputation']--;
	systemmail($row['acctid'],'`2Herausforderung zurückgezogen','
		`2'.$session['user']['name'].'`2 hat '.($session['user']['sex']?'ihre':'seine').' Herausforderung zurückgezogen.
	');
	addnav('Zurück zum Stadtzentrum','village.php');
}

else if ($_GET['op']=='fight')
{
	$sql = "
		SELECT 
			* 
		FROM 
			`pvp` 
		WHERE 
			`acctid1`	= '".$session['user']['acctid']."' OR 
			`acctid2`	= '".$session['user']['acctid']."'
	";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	if ($row['turn']==1)
	{
		$badguy = array(
			'acctid'	=> $row['acctid2'],
			'name'		=> $row['name2'],
			'level'		=> $row['lvl2'],
			'hitpoints'	=> $row['hp2'],
			'attack'	=> $row['att2'],
			'defense'	=> $row['def2'],
			'weapon'	=> $row['weapon2'],
			'armor'		=> $row['armor2'],
			'bufflist'	=> $row['bufflist2']
		);
		$goodguy = array(
			'name'			=> $row['name1'],
			'level'			=> $row['lvl1'],
			'hitpoints'		=> $row['hp1'],
			'maxhitpoints'	=> $row['maxhp1'],
			'attack'		=> $row['att1'],
			'defense'		=> $row['def1'],
			'weapon'		=> $row['weapon1'],
			'armor'			=> $row['armor1'],
			'specialtyuses'	=> $row['specialtyuses1'],
			'bufflist'		=> $row['bufflist1']
		);
	}
	elseif ($row['turn']==2)
	{
		$badguy = array(
			'acctid'	=> $row['acctid1'],
			'name'		=> $row['name1'],
			'level'		=> $row['lvl1'],
			'hitpoints'	=> $row['hp1'],
			'attack'	=> $row['att1'],
			'defense'	=> $row['def1'],
			'weapon'	=> $row['weapon1'],
			'armor'		=> $row['armor1'],
			'bufflist'	=> $row['bufflist1']
		);
		$goodguy = array(
			'name'			=> $row['name2'],
			'level'			=> $row['lvl2'],
			'hitpoints' 	=> $row['hp2'],
			'maxhitpoints'	=> $row['maxhp2'],
			'attack'		=> $row['att2'],
			'defense'		=> $row['def2'],
			'weapon'		=> $row['weapon2'],
			'armor'			=> $row['armor2'],
			'specialtyuses'	=> $row['specialtyuses2'],
			'bufflist'		=> $row['bufflist2']
		);
	}
	stats($row);
	$adjustment=1;
	$goodguy['bufflist'] = adv_unserialize($goodguy['bufflist']);
	$badguy['bufflist'] = adv_unserialize($badguy['bufflist']);

	// spells
	if ($_GET['skill']=='zauber')
	{

		$id = (int)$_GET['itemid'];
		$zauber = item_get( ' id= '.$id );
		$item = $zauber;

		if(!empty($zauber['battle_hook'])) {
			item_load_hook($zauber['battle_hook'],'battle_arena',$zauber);
		}

		if(!$item_hook_info['hookstop']) {
			if($item['buff1'] > 0) {$list .= ','.$item['buff1'];}
			if($item['buff2'] > 0) {$list .= ','.$item['buff2'];}

			$buffs = item_get_buffs( ITEM_BUFF_FIGHT , $list );

			if(sizeof($buffs) > 0) 
			{
				foreach($buffs as $b) 
				{
					$GLOBALS['goodguy']['bufflist'][$b['name']] = $b;
				}
			}

			$item['gold']=round($item['gold']*($item['value1']/($item['value2']+1)));
			$item['gems']=round($item['gems']*($item['value1']/($item['value2']+1)));

			$item['value1']--;

			if ($item['value1']<=0 && $item['hvalue']<=0)
			{
				item_delete(' id='.$item['id']);
			}
			else
			{
				item_set(' id='.$item['id'], $item);
			}
		}

	}
	// end spells

	elseif(file_exists('./module/specialty_modules/specialty_'.$_GET['skill'].'.php'))
	{
		require_once('./module/specialty_modules/specialty_'.$_GET['skill'].'.php');
		$f = 'specialty_'.$_GET['skill'].'_info';
		$f();
		$f1 = 'specialty_'.$_GET['skill'].'_run';

		$f1('buff',0,0,'goodguy');
	}
	if ($goodguy['hitpoints']>0 && $badguy['hitpoints']>0) 
	{
		$str_output ='`c`b`$~ ~ ~ Kampf ~ ~ ~`0`b`c`n';
		$str_output.='`@Du hast den Gegner `^'.$badguy['name'].'`@ entdeckt, der sich mit seiner Waffe `%'.$badguy['weapon'].'`@';
		// Let's display what buffs the opponent is using - oh yeah
		$buffs = '';
		$disp['bufflist']=$badguy['bufflist'];
		foreach ($disp['bufflist'] AS $key => $val){
			$str_output.=' `@und `#'.$val['name'].' `7('.$val['rounds'].' Runden)';
		}
		$str_output.=' `@auf dich stürzt!`n`n
		`2Level: `t'.$badguy['level'].'`n
		`2`bBeginn der Runde:`b`n
		`2'.$badguy['name'].'`2\'s Lebenspunkte: `t'.$badguy['hitpoints'].'`n
		`2DEINE Lebenspunkte: `t'.$goodguy['hitpoints'].'`0`n`n';
	}
	output($str_output); //Zwischenausgabe
	$str_output='';
	foreach ($goodguy['bufflist'] AS $key => $buff)
	{
		$buff['used']=0;
	}

	if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0)
	{
		$buffset = activate_buffs("roundstart");
		$creaturedefmod=$buffset['badguydefmod'];
		$creatureatkmod=$buffset['badguyatkmod'];
		$atkmod=$buffset['atkmod'];
		$defmod=$buffset['defmod'];
	}
	if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0)
	{
		$adjustedcreaturedefense = $badguy['defense'];
		$creatureattack = $badguy['attack']*$creatureatkmod;
		$adjustedselfdefense = ($goodguy['defense'] * $adjustment * $defmod);

		// Wenn kein Schaden entsteht, irgendwann abbrechen: Sonst Endlosschleife
		$int_iterations = 0;

		while($creaturedmg==0 && $selfdmg==0 && $int_iterations < 100)
		{
			$int_iterations ++;

			$atk = $goodguy['attack']*$atkmod;
			if (e_rand(1,20)==1) $atk*=3;
			$patkroll = e_rand(0,$atk);
			$catkroll = e_rand(0,$adjustedcreaturedefense);
			$creaturedmg = 0-(int)($catkroll - $patkroll);
			if ($creaturedmg<0) 
			{
				$creaturedmg = (int)($creaturedmg/2);
				$creaturedmg = round($buffset['badguydmgmod']*$creaturedmg,0);
			}
			if ($creaturedmg > 0) 
			{
				$creaturedmg = round($buffset['dmgmod']*$creaturedmg,0);
			}
			$pdefroll = e_rand(0,$adjustedselfdefense);
			$catkroll = e_rand(0,$creatureattack);
			$selfdmg = 0-(int)($pdefroll - $catkroll);
			if ($selfdmg<0) 
			{
				$selfdmg=(int)($selfdmg/2);
				$selfdmg = round($selfdmg*$buffset['dmgmod'], 0);
			}
			if ($selfdmg > 0) 
			{
				$selfdmg = round($selfdmg*$buffset['badguydmgmod'], 0);
			}
		}
	}
	if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0)
	{
		$buffset = activate_buffs('offense');
		if ($atk > $goodguy['attack']) 
		{
			if ($atk > $goodguy['attack']*3)
			{
				if ($atk > $goodguy['attack']*4)
				{
					$str_output.='`&`bDu holst zu einem <big><big>MEGA</big></big> Powerschlag aus!!!`b`n';
				}
				else
				{
					$str_output.='`&`bDu holst zu einem DOPPELTEN Powerschlag aus!!!`b`n';
				}
			}
			else
			{
				if ($atk>$goodguy['attack']*2)
				{
					$str_output.='`&`bDu holst zu einem Powerschlag aus!!!`b`0`n';
				}
				elseif ($atk>$goodguy['attack']*1.25)
				{
					$str_output.='`7`bDu holst zu einem kleinen Powerschlag aus!`b`0`n';
				}
			}
		}
		if ($creaturedmg==0)
		{
			$str_output.='`4Du versuchst `^'.$badguy['name'].'`4 zu treffen, aber `$TRIFFST NICHT!`n';
			$message=$message.'`^'.$goodguy['name'].'`4 versucht dich zu treffen, aber `$TRIFFT NICHT!`n';
			if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0)
			{
				process_dmgshield($buffset['dmgshield'], 0);
			}
			if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) 
			{
				process_lifetaps($buffset['lifetap'], 0);
			}
		}
		else if ($creaturedmg<0)
		{
			$str_output.='`4Du versuchst `^'.$badguy['name'].'`4 zu treffen, aber der `$ABWEHRSCHLAG `4trifft dich mit `$'.(0-$creaturedmg).'`4 Schadenspunkten!`n';
			$message=$message.'`^'.$goodguy['name'].'`4 versucht dich zu treffen, aber dein `$ABWEHRSCHLAG`4 trifft mit `$'.(0-$creaturedmg).'`4 Schadenspunkten!`n';
			$badguy['diddamage']=1;
			$goodguy['hitpoints']+=$creaturedmg;
			if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) 
			{
				process_dmgshield($buffset['dmgshield'],-$creaturedmg);
			}
			if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) 
			{
				process_lifetaps($buffset['lifetap'],$creaturedmg);
			}
		}
		else
		{
			$str_output.='`4Du triffst `^'.$badguy['name'].'`4 mit `^'.$creaturedmg.'`4 Schadenspunkten!`n';
			$message=$message.'`^'.$goodguy['name'].'`4 trifft dich mit `^'.$creaturedmg.'`4 Schadenspunkten!`n';
			$badguy['hitpoints']-=$creaturedmg;
			if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) 
			{
				process_dmgshield($buffset['dmgshield'],-$creaturedmg);
			}
			if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) 
			{
				process_lifetaps($buffset['lifetap'],$creaturedmg);
			}
		}
		// from hardest punch mod -- remove if not installed!!
		if ($creaturedmg>$session['user']['punch']){
			$session['user']['punch']=$creaturedmg;
			$str_output.='`@`b`c--- DAS WAR DEIN BISHER HÄRTESTER SCHLAG! ---`c`b`n';
		}
		// end hardest punch
	}
	if ($goodguy['hitpoints']>0 && $badguy['hitpoints']>0) 
	{
		$buffset = activate_buffs("defense");
	}
	expire_buffs();
	if ($goodguy['hitpoints']>0 && $badguy['hitpoints']>0)
	{
		$str_output.='`n`2`bEnde der Runde:`b`n';
		$str_output.="`2".$badguy['name']."`2's Lebenspunkte: `t".$badguy['hitpoints']."`0`n";
		$str_output.='`2DEINE Lebenspunkte: `t'.$goodguy['hitpoints'].'`0`n';
	}

	$goodguy['bufflist'] = utf8_serialize($goodguy['bufflist']);
	$badguy['bufflist'] = utf8_serialize($badguy['bufflist']);
	if ($row['acctid1'])
	{ // battle still in DB? Result of round:
		if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0)
		{
			$message = db_real_escape_string($message);
			if ($row['turn']==1) 
			{
				$sql = "
					UPDATE 
						`pvp` 
					SET 
						`hp1`				= '".$goodguy['hitpoints']."',
						`hp2`				= '".$badguy['hitpoints']."',
						`specialtyuses1`	= '".$goodguy['specialtyuses']."',
						`bufflist1`			= '".db_real_escape_string($goodguy['bufflist'])."',
						`lastmsg`			= '".$message."',
						`turn`				= '2' 
					WHERE 
						`acctid1`			= '".$session['user']['acctid']."'
				";
			}
			elseif ($row['turn']==2) 
			{
				$sql = "
					UPDATE 
						`pvp` 
					SET 
						`hp1`				= '".$badguy['hitpoints']."',
						`hp2`				= '".$goodguy['hitpoints']."',
						`specialtyuses2`	= '".$goodguy['specialtyuses']."',
						`bufflist2`			= '".db_real_escape_string($goodguy['bufflist'])."',
						`lastmsg`			= '".$message."',
						`turn`				= '1' 
					WHERE 
						`acctid2`			= '".$session['user']['acctid']."'
				";
			}
			db_query($sql);
			if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
			$str_output.='`n`n`2Du erwartest den Zug deines Gegners.';
			addnav('Aktualisieren','pvparena.php');
		}
		else if ($badguy['hitpoints']<=0) //Eigener Sieg
		{
			$win=$badguy['level']*20+$goodguy['level']*20;
			$exp=$badguy['level']*10-(abs($goodguy['level']-$badguy['level'])*10);

			//Wenn das eigene Leven kleiner ist...
			if ($badguy['level']<=$goodguy['level'])
			{
				$session['user']['battlepoints']+=2;
			}
			else
			{
				//3* die Differenz
				$session['user']['battlepoints']+=3*($badguy['level']-$goodguy['level']);
			}
			$session['user']['reputation']+=5;
			$str_output.='`n`&Kurz vor deinem finalen Todesstoß beendet der Turnierwärter euren Kampf und erklärt dich zum Sieger!`0`n`b`$Du hast '.$badguy['name'].' `$besiegt!`0`b`n`#Du bekommst das Preisgeld in Höhe von `^'.$win.'`# Gold und deinen gerechten Lohn an Arenakampfpunkten!`nDu bekommst insgesamt `^'.$exp.'`# Erfahrungspunkte!`n`0';
			$session['user']['donation']+=1;
			$session['user']['gold']+=$win;
			$session['user']['playerfights']--;
			$session['user']['experience'] = max($session['user']['experience']+$exp,0);
			$exp = round(getsetting('pvpdeflose',5)*10,0);
			user_update(
				array
				(
					'charm'=>array('sql'=>true,'value'=>'charm-1'),
					'experience'=>array('sql'=>true,'value'=>'GREATEST(experience-'.$exp.',0)'),
					'playerfights'=>array('sql'=>true,'value'=>'playerfights-1')
				),
				$badguy['acctid']
			);

			systemmail($badguy['acctid'],'`2Du wurdest im Turnier besiegt',
				'`^'.$goodguy['name'].'`2 hat dich mit '.($session['user']['sex']?'ihrem':'seinem').' 
				`^'.$goodguy['weapon'].'`2 im Turnier besiegt!`n
				`n
				'.($session['user']['sex']?'Sie':'Er').' hatte am Ende noch `^'.$goodguy['hitpoints'].'`2 Lebenspunkte übrig.`n
				`n
				Du hast `$'.$exp.'`2 Erfahrungspunkte verloren.
			');
			addnews('`$'.$goodguy['name'].'`t besiegt `$'.$badguy['name'].'`t bei einem Duell auf dem Turnierplatz`t!');
			$sql = "
				DELETE FROM 
					`pvp` 
				WHERE 
					`acctid1`	= '".$session['user']['acctid']."' OR 
					`acctid2`	= '".$session['user']['acctid']."'
			";
			db_query($sql);
		}
		else if ($goodguy['hitpoints']<=0)
		{
			$exp=$badguy['level']*10-(abs($goodguy['level']-$badguy['level'])*10);
			$win=$badguy['level']*20+$goodguy['level']*20;
			if ($badguy['level']>=$goodguy['level'])
			{
				$points=2;
			}
			else
			{
				$points=3*($goodguy['level']-$badguy['level']);
			}
			$badguy['acctid']=(int)$badguy['acctid'];
			$badguy['creaturegold']=(int)$badguy['creaturegold'];
			systemmail($badguy['acctid'],'`2 du warst im Turnier erfolgreich! ','
				`^'.$session['user']['name'].'`2 hat im Turnier verloren!`n
				`n
				Dafür hast du `^'.$exp.'`2 Erfahrungspunkte und `^'.$win.'`2 Gold erhalten!
			');
			
			user_update(
				array
				(
					'gold'=>array('sql'=>true,'value'=>'gold+'.$win),
					'experience'=>array('sql'=>true,'value'=>'GREATEST(experience+'.$exp.',0)'),
					'playerfights'=>array('sql'=>true,'value'=>'playerfights-1'),
					'battlepoints'=>array('sql'=>true,'value'=>'battlepoints+'.$points),
					'reputation'=>array('sql'=>true,'value'=>'reputation+5'),
				),
				$badguy['acctid']
			);
			
			$exp = round(getsetting("pvpdeflose",5)*10,0);
			$session['user']['experience'] = max($session['user']['experience']-$exp,0);
			$session['user']['playerfights']--;
			$str_output.='`n`b`&Du wurdest von `%'.$badguy['name'].'`& besiegt!!!`n'.get_taunt().'`n`4Du hast `^'.$exp.' Erfahrungspunkte verloren!`n';
			if ($session['user']['charm']>0) $session['user']['charm']--;
			addnews('`$'.$badguy['name'].'`t besiegt `$'.$goodguy['name'].'`t bei einem Duell auf dem Turnierplatz`t!');
			$sql = "
				DELETE FROM 
					`pvp` 
				WHERE 
					`acctid1`	= '".$session['user']['acctid']."' OR 
					`acctid2`	= '".$session['user']['acctid']."'
			";
			db_query($sql);
		}
	}
	else
	{
		output('`tEuer Kampf wurde vorzeitig beendet. Die Nutzungsgebühr kommt dem Ausbau des Turnierplatzes zugute.');
	}
	output($str_output);
	addnav('Zurück zum Stadtzentrum','village.php');
}

else if ($_GET['op']=='del')
{
	$sql = "
		DELETE FROM 
			`pvp` 
		WHERE 
			`acctid1`	= '".$_GET['kid1']."' AND 
			`acctid2`	= '".$_GET['kid2']."'
	";
	db_query($sql);
	output('Der Turnierrichter beendet einen langweiligen Kampf.');
	systemmail($_GET['kid1'],'`2Dein Turnierkampf wurde beendet!','`@Dein Kampf auf dem Turnierplatz wurde vom Kampfrichter beendet.');
	systemmail($_GET['kid2'],'`2Dein Turnierkampf wurde beendet!','`@Dein Kampf auf dem Turnierplatz wurde vom Kampfrichter beendet.');

	addnav('Zum Turnierplatz','pvparena.php');
	addnav('Zurück zum Stadtzentrum','village.php');
}

else //if ($_GET['op']=='')
{
	$sql = "
		SELECT 
			* 
		FROM 
			`pvp` 
		WHERE 
			`acctid1`	= '".$session['user']['acctid']."' OR 
			`acctid2`	= '".$session['user']['acctid']."'
	";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);

	$row['specialtyuses1']=adv_unserialize($row['specialtyuses1']);
	$row['specialtyuses2']=adv_unserialize($row['specialtyuses2']);

	$text=0;
	if($row['acctid1']==$session['user']['acctid'] && $row['turn']==0)
	{
		$text=1;
		output('
			`tDa du noch eine Herausforderung mit `&'.$row['name2'].' `toffen hast, 
			machst du dich auf in Richtung Turnierplatz, um nach deinem Gegner Ausschau zu halten. 
			Doch der scheint nirgendwo in Sicht zu sein.`n
		');
		addnav('Herausforderung zurücknehmen','pvparena.php?op=back&id='.$row['acctid2']);
		addnav('Gladiator herausfordern','battlearena.php'); // ONE arena for TWO things - if installed ;)
		if ($session['user']['battlepoints'] > 120 && $session['user']['dragonkills'] > 4)
		{
			addnav('Gesellschaftsraum','battlearena.php?op=lounge');
		}
		addnav('Zurück zum Stadtzentrum','village.php');
		stats($row);
	}
	else if($row['acctid1']==$session['user']['acctid'] && $row['turn']==1)
	{
		stats($row);
		arenanav($row);
	}
	else if($row['acctid1']==$session['user']['acctid'] && $row['turn']==2)
	{
		stats($row);
		output('`tDein Gegner `&'.$row['name2'].'`t hat seinen Zug noch nicht gemacht.`n`n`0');
		$text=1;
		addnav('Gladiator herausfordern','battlearena.php');
		if ($session['user']['battlepoints'] > 120 && $session['user']['dragonkills'] > 4)
		{
			addnav('Gesellschaftsraum','battlearena.php?op=lounge');
		}
		addnav('Zurück zum Stadtzentrum','village.php');
	}
	else if($row['acctid2']==$session['user']['acctid'] && $row['turn']==0)
	{
		output('
			`tDu wurdest von `&'.$row['name1'].' `therausgefordert. 
			Wenn du die Herausforderung annimmst, 
			musst du `^'.$cost.'`t Gold Turniergebühr bezahlen.`n
		');
		addnav('Du wurdest herausgefordert');
		addnav('Herausforderung annehmen','pvparena.php?op=accept&nospec='.$row['nospecials']);
		addnav('Feige ablehnen','pvparena.php?op=deny&id='.$row['acctid1']);
	}
	else if($row['acctid2']==$session['user']['acctid'] && $row['turn']==1)
	{
		stats($row);
		output('`tDein Gegner `&'.$row['name1'].'`t hat seinen Zug noch nicht gemacht.`n`n');
		$text=1;
		addnav('Gladiator herausfordern','battlearena.php');
		if ($session['user']['battlepoints'] > 120 && $session['user']['dragonkills'] > 4)
		{
			addnav('Gesellschaftsraum','battlearena.php?op=lounge');
		}
		addnav('Zurück zum Stadtzentrum','village.php');
	}
	else if($row['acctid2']==$session['user']['acctid'] && $row['turn']==2)
	{
		stats($row);
		arenanav($row);
	}
	else
	{
		$text=1;
		if(false)//($session['user']['age'] > getsetting('maxagepvp',50))
		{
			output('
				"Stopp!" der Turnierwächter versperrt dir mit seiner Lanze den Weg zur Anmeldung und 
				macht nicht den Eindruck, als wollte er dich vorbeilassen 
				"Geh erstmal die elende Drachenbrut ausrotten, bevor du hier deine Zeit mit Kämpfen verschwendest!"`n
			');
			addnav('Zurück zum Stadtzentrum','village.php');
			page_footer();
		}
		else 
		{
			addnav('Spieler herausfordern','pvparena.php?op=challenge');
			addnav('Gladiator herausfordern','battlearena.php');
			addnav('Stier herausfordern','bullfight.php');
			if ($session['user']['battlepoints'] > 120 && $session['user']['dragonkills'] > 4)
			{
				addnav('Gesellschaftsraum','battlearena.php?op=lounge');
			}
		}

		addnav('Zurück zum Stadtzentrum','village.php');
	}
	if($text==1)
	{
		checkday();
		addnav('Aktualisieren','pvparena.php');
		$str_output .= '
			`c`b`uD`}e`Ir `tTurnierpl`Ia`}t`uz`0`b`c
			`n`uD`}u `Ib`tetrittst den großen Turnierplatz und sofort umfängt dich der gewohnte Kampflärm. Nicht weit entfernt von dir kreuzen zwei Krieger die Klingen und in einer Ecke steht eine Gruppe von Männern und Frauen, die über die Vorzüge und Nachteile von fremdländisch klingenden Waffen debattieren.
Gleich welcher Rasse oder welchem Geschlecht jemand hier angehört, an diesem Ort zählt nur die Ehre und der Kampfgeist beim Messen der Fähigkeiten gegeneinan`Id`}e`ur.`n
		';
		$sql = "
			SELECT 
				* 
			FROM 
				`pvp` 
			WHERE 
				`acctid1` 	> '0'	AND 
				`acctid2` 	> '0'	AND 
				`turn`		> '0'
		";
		$result = db_query($sql);

		if($access_control->su_check(access_control::SU_RIGHT_EDITORUSER))
		{
			$del = true;
		}
		else
		{
			$del = false;
		}

		$max = db_num_rows($result);
		if($max)
		{
			$str_output .= "
				Du beobachtest das bunte Treiben auf dem Platz eine Weile.`n` Folgende Krieger kämpfen gerade gegeneinan`Yd`Te`Sr:`n
				`n`0
				`c<table border='0' cellpadding='2' cellspacing='1' bgcolor='#999999' align='center'>
					<tr class='trhead'>
						<th>Herausforderer</th>
						<th>Verteidiger</th>
						<th>Stand (LP)</th>
						".($del?"<th>Ops</th>":"")."
					</tr>
			";
			for($i=0;$i<$max;$i++)
			{
				$row = db_fetch_assoc($result);
				$str_output .= '
					<tr class="'.($i%2?'trlight':'trdark').'">
						<td>
							'.$row['name1'].'
						</td>
						<td>
							'.$row['name2'].'
						</td>
						<td align="center">
							'.$row['hp1'].' : '.$row['hp2'].'
						</td>
						'.($del?
							'<td>
								<a href="pvparena.php?op=del&amp;kid1='.$row['acctid1'].'&amp;kid2='.$row['acctid2'].'">Löschen</a>
							</td>'
							:
							''
						).'
					</tr>';
				if($del) addnav('','pvparena.php?op=del&kid1='.$row['acctid1'].'&kid2='.$row['acctid2']);
			}
			$str_output .= '</table>`c`n`n';
		}
		else
		{
			$str_output .= '`n`n`uIm Moment laufen keine Kämpfe zwischen den Helden dieser Welt.`n`n';
		}
		output($str_output);
		unset($str_output);
		viewcommentary('pvparena','Rufen:',10,'ruft');
		$str_output = '';
		$str_output .= '`n`n';
		$result2 = db_query("
			SELECT 
				`newsdate`,
				`newstext` 
			FROM 
				`news` 
			WHERE 
				`newstext`	LIKE '%Arena`t!' 
			ORDER BY 
				`newsid`	DESC 
			LIMIT 
				10
		");
		//for ($i=0;$i<db_num_rows($result2);$i++)
		while($row2 = db_fetch_assoc($result2))
		{
			$str_output .= '`n`0'.$row2['newsdate'].': '.$row2['newstext'];
		}
		output($str_output);
		unset($str_output);
	}
}

page_footer();

// this is not the end ;)
?>