<?php

// Inventar in Kämpfen NIE anzeigen
$show_invent = false;

if(!isset($_GET['auto']))$_GET['auto']='';
//Anzahl der Kampfrunden die automatisch durchgeführt werden sollen
$int_rounds_to_fight=max(1,intval($_GET['auto']));

///
/// Funktionen
///

/**
 * Aktiviere Spielerbuffs
 *
 * @param string $tag welche Buffs sollen aktiviert werden (roundstart, offense, defense)
 * @return array Array mit den Ergebnissen der Buffs
 */
function activate_buffs($tag)
{
	global $session, $badguy, $out;
    $msg='';
	$result = array();
	$result['invulnerable'] = 0;
	$result['dmgmod'] = 1;
	$result['badguydmgmod'] = 1;
	$result['atkmod'] = 1;
	$result['badguyatkmod'] = 1;
	$result['defmod'] = 1;
	$result['badguydefmod'] = 1;
	$result['lifetap'] = array();
	$result['dmgshield'] = array();

	if(is_array($session['bufflist']))
	{
		foreach($session['bufflist'] as $key => $buff)
		{
			if (isset($buff['startmsg']))
			{
				$msg = $buff['startmsg'];
				$msg = str_replace('{badguy}', $badguy['creaturename'], $msg);
				$out .= '`%'.$msg.'`0';
				unset($session['bufflist'][$key]['startmsg']);
			}
			
			//Ist der gewünschte Bufftyp im aktuell bearbeiteten Buff enthalten? (roundstart, offense, defense)
			$activate = mb_strpos($buff['activate'], $tag);
			if ($activate !== false)
			{
				$activate = true;
			}
			// handle mb_strpos == 0;

			// If this should activate now and it hasn't already activated,
			// do the round message and mark it.
			if ($activate && !$buff['used'])
			{
				// mark it used.
				$session['bufflist'][$key]['used'] = 1;
				// msg_round der Tiere umbenennen
				if (isset($buff['msg_round']))
				{
					$buff['roundmsg'] = $buff['msg_round'];
					unset($buff['msg_round']);
				}
				// if it has a 'round message', run it.
				if (isset($buff['roundmsg']))
				{
					$msg = $buff['roundmsg'];
					$msg = str_replace('{badguy}', $badguy['creaturename'], $msg);
					$out .= '`)'.$msg.'`0`n';
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
			if (!empty($buff['lifetap']))
			{
				$result['lifetap'][] = $buff;
			}
			if (isset($buff['damageshield']) && $buff['damageshield'] != 1)
			{
				$result['dmgshield'][] = $buff;
			}
			if (!empty($buff['regen']) && $activate)
			{
				// Wenn PHP-Code
				if(is_string($buff['regen']) && mb_strlen($buff['regen']) > 0) {
					$buff['regen'] = stripslashes($buff['regen']);
                    eval(utf8_eval("\$buff['regen'] = $buff[regen];"));
				}

				$hptoregen = (int)$buff['regen'];
				$hpdiff = $session['user']['maxhitpoints'] - $session['user']['hitpoints'];
				// Don't regen if we are above max hp
				$session['user']['hitpoints'] = min($session['user']['maxhitpoints'] , $session['user']['hitpoints'] + $hptoregen);
				/* Ichdenke die obige Linie ersetzt die folgenden komplett, oder?
				if ($hpdiff < 0)
				{
					$hpdiff = 0;
				}
				if ($hpdiff < $hptoregen)
				{
					$hptoregen = $hpdiff;
				}
				$session['user']['hitpoints'] += $hptoregen;
				*/
				// Now, take abs value just incase this was a damaging buff
				$hptoregen = abs($hptoregen);
				if ($hptoregen == 0)
				{
					// Tier-Variable heißt anders
					if (!isset($buff['effectnodmgmsg']))
						$msg = $buff['effectnodmgmsg'];
					else
						$msg = $buff['msg_regen_fail'];
				}
				else
				{
					// Tier-Variable heißt anders
					if (!isset($buff['regen_success']))
					{
						if ($buff['msg_regen_success']!="") $buff['regen_success'] = $buff['msg_regen_success'];
						else $buff['regen_success'] = $buff['msg_effect_success'];
					}
					#$msg = is_null_or_empty($buff['regen_success'])?$buff['regen_success']:'';
					$msg = is_null_or_empty($buff['regen_success'])?'':$buff['regen_success'];
				}
				$msg = str_replace('{badguy}', $badguy['creaturename'], $msg);
				$msg = str_replace('{damage}', $hptoregen, $msg);
				$out .= '`)'.$msg.'`0`n';

			}
			if (!empty($buff['minioncount']) && $activate)
			{

				$who = -1;
				if (!is_null_or_empty($buff['maxbadguydamage']))
				{
					$buff['maxbadguydamage'] = stripslashes($buff['maxbadguydamage']);
                    eval(utf8_eval("\$buff['maxbadguydamage'] = $buff[maxbadguydamage];"));
					
					$max = $buff['maxbadguydamage'];

					if (!is_null_or_empty($buff['minbadguydamage']))
					{
						$buff['minbadguydamage'] = stripslashes($buff['minbadguydamage']);
                        eval(utf8_eval("\$buff['minbadguydamage'] = $buff[minbadguydamage];"));
						$min = $buff['minbadguydamage'];
					}
					else 
					{
						$min = 0;	
					}
					
					$who = 0;
				}
				else
				{
					$max = $buff['maxgoodguydamage'];
					$min = $buff['mingoodguydamage'];
					$who = 1;
				}
				
				for ($i = 0; $who >= 0 && $i < $buff['minioncount']; $i++)
				{
					$damage = e_rand($min, $max);

					if ($who == 0)
					{
						$badguy['creaturehealth'] -= $damage;
					}
					elseif ($who == 1)
					{
						$session['user']['hitpoints'] -= $damage;
					}
					if ($damage < 0)
					{
						if (isset($buff['effectfailmsg'])) $msg = $buff['effectfailmsg'];
						else $msg = $buff['msg_effect_fail'];
					}
					elseif ($damage == 0)
					{
						if (isset($buff['effectnodmgmsg'])) $msg = $buff['effectnodmgmsg'];
						else $msg = $buff['msg_no_effect'];
					}
					elseif ($damage > 0)
					{
						if (isset($buff['effectmsg'])) $msg = $buff['effectmsg'];
						else $msg = $buff['msg_effect_success'];
					}
					if ($msg>'')
					{
						$msg = str_replace('{badguy}', $badguy['creaturename'], $msg);
						$msg = str_replace('{goodguy}', $session['user']['name'], $msg);
						$msg = str_replace('{damage}', $damage, $msg);
						$out .= '`)'.$msg.'`0`n';
					}
				}
			}
		}
	}
	return $result;
}

function process_lifetaps($ltaps, $damage)
{
	global $session, $badguy, $out;

	foreach ($ltaps as $key=>$buff)
	{
		$healhp = $session['user']['maxhitpoints'] - $session['user']['hitpoints'];
		if ($healhp < 0)
		{
			$healhp = 0;
		}
		if ($healhp == 0)
		{	
			$msg = is_null_or_empty($buff['nohealmsg']) ? 'Dein Tier möchte dich heilen, dies hast du jedoch nicht nötig. Es geht dir blendend.' : $buff['nohealmsg'];
		}
		else
		{
			if ($healhp > $damage * $buff['lifetap'])
			{
				$healhp = $damage * $buff['lifetap'];
			}
			if ($healhp < 0)
			{
				$healhp = 0;
			}
			if ($damage > 0)
			{
				if (isset($buff['msg_lifetap_success'])) $buff['healmsg'] = $buff['msg_lifetap_success'];
				$msg = is_null_or_empty($buff['healmsg']) ? 'Du wirst von deinem Tier geheilt.' : $buff['healmsg'];
			}
			elseif ($damage == 0)
			{
				if (isset($buff['msg_lifetap_fail'])) $buff['effectfailmsg'] = $buff['msg_lifetap_fail'];
				$msg = $buff['effectfailmsg'];
			}
			elseif ($damage < 0)
			{
				if (isset($buff['msg_lifetap_fail'])) $buff['effectfailmsg'] = $buff['msg_lifetap_fail'];
				$msg = $buff['effectfailmsg'];
			}
		}
		$session['user']['hitpoints'] += $healhp;
		$msg = str_replace('{badguy}',$badguy['creaturename'], $msg);
		$msg = str_replace('{damage}',$healhp, $msg);
		$msg = str_replace('{hitpoints}',$healhp, $msg);
		if (mb_strlen($msg) > 0)
		{
			$out .= '`)'.$msg.'`n';
		}
	}
}

/**
 * Bearbeitet Buffs die Schaden absorbieren
 *
 * @param array $dshield Die Buffs die Schaden absorbieren
 * @param int $damage zugefügter Schaden
 */
function process_dmgshield($dshield, $damage)
{
	global $session, $badguy, $out;

	foreach ($dshield as $key=>$buff)
	{
		$realdamage = $damage * $buff['damageshield'];
		if ($realdamage < 0)
		{
			$realdamage = 0;
		}
		if ($realdamage > 0)
		{
			if (isset($buff['msg_effect_success'])) $buff['effectmsg'] = $buff['msg_effect_success'];
			$msg = $buff['effectmsg'];
		}
		else if ($realdamage == 0)
		{
			if (isset($buff['msg_effect_fail'])) $buff['effectnodmgmsg'] = $buff['msg_effect_fail'];
			$msg = $buff['effectnodmgmsg'];
		}
		else if ($realdamage < 0)
		{
			if (isset($buff['msg_effect_fail'])) $buff['effectfailmsg'] = $buff['msg_effect_fail'];
			$msg = $buff['effectfailmsg'];
		}
		$badguy['creaturehealth'] -= $realdamage;
		$msg = str_replace('{badguy}',$badguy['creaturename'], $msg);
		$msg = str_replace('{damage}',$realdamage, $msg);
		if ($msg != '')
		{
			$out .= '`)'.$msg.'`n';
		}
	}
}

/**
 * Lösche Buffs aus der Session die keine Runden mehr aufweisen
 */
function expire_buffs()
{
	global $session, $badguy, $out;
	if(is_array($session['bufflist']))
	{
		foreach($session['bufflist'] as $key => $buff)
		{
			if ($buff['used'])
			{
				//Rundenzahl wird um die Anzahl der Benutzungen des Buffs reduziert
				$session['bufflist'][$key]['rounds'] -= $session['bufflist'][$key]['used'];
				$session['bufflist'][$key]['used'] = 0;
				if ($session['bufflist'][$key]['rounds'] <= 0)
				{
					if ($buff['wearoff']||$buff['msg_wearoff'])
					{
						$msg = $buff['wearoff']?$buff['wearoff']:$buff['msg_wearoff'];
						$msg = str_replace('{badguy}', $badguy['creaturename'], $msg);
						$out .= '`)'.$msg.'`n';
					}
					unset($session['bufflist'][$key]);
				}
			}
		}
	}
}

///
/// Ende Funktionen
///

//Gegner laden
/** @noinspection PhpUndefinedVariableInspection */
$badguy = utf8_unserialize($session['user']['badguy']);
if ($badguy['creaturelevel']==0)
{
	$adjustment = 0;
}
else
{
	$adjustment = ($session['user']['level']/$badguy['creaturelevel']);
}

if (isset($badguy['pvp']) && $badguy['pvp'])
{
	$adjustment=1;
}

$out = '';
$hp_name = ($session['user']['alive'])?'Lebenspunkte':'Seelenpunkte';

/////////////
// FIGHT
if ($_GET['op']=='fight')
{
	// spells
	if ($_GET['skill']=='zauber')
	{
		$id = (int)$_GET['itemid'];
		if($id == 0)
		{
			$out .= 'Merkwürdig.. Der Zauber scheint sich in Luft aufgelöst zu haben. Immer diese Billigware..';
		}
		else
		{
			$item = item_get(' id= '.$id );

			if (!empty($item['battle_hook']))
			{
				item_load_hook($item['battle_hook'],'battle',$item);
			}
             $list = '';
            /** @noinspection PhpUndefinedVariableInspection */
            if(!$item_hook_info['hookstop']) {
				if($item['buff1'] > 0) {$list .= ','.$item['buff1'];}
				if($item['buff2'] > 0) {$list .= ','.$item['buff2'];}
				item_set_buffs( ITEM_BUFF_FIGHT , $list );
				$item['gold']=round($item['gold']*($item['value1']/($item['value2']+1)));
				$item['gems']=round($item['gems']*($item['value1']/($item['value2']+1)));
				$item['value1']--;
				if ($item['value1']<=0 && $item['hvalue']<=0){

					//Fehler, ID ist manchmal leer
					// ID darf nicht leer sein! Wenn die ID leer ist, läuft irgendwas anderes schief
					//if(!empty($item['id']))
					//{
						item_delete(' id='.(int)$item['id']);
					//}
				}
				else{
					item_set(' id='.$item['id'], $item);
				}
			}
		}

	}
	// end spells
	if ($_GET['skill']=='godmode')
	{
		$session['bufflist']['godmode']=array('name'=>'`&GOD MODE',
		'rounds'=>1,
		'wearoff'=>'Du bist wieder sterblich.',
		'atkmod'=>25,
		'defmod'=>25,
		'invulnerable'=>1,
		'startmsg'=>'`n`&Du fühlst dich gottgleich`n`n',
		'activate'=>'roundstart'
		);
	}
	elseif (file_exists('./module/specialty_modules/specialty_'.$_GET['skill'].'.php'))
	{
		require_once('./module/specialty_modules/specialty_'.$_GET['skill'].'.php');
		$f_info = 'specialty_'.$_GET['skill'].'_info';
		$f_info();
		$f_run = 'specialty_'.$_GET['skill'].'_run';
		$f_run('buff');
	}
}
// end if fight


if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
{
	$out .= '`c`b`$~ ~ ~ Kampf ~ ~ ~`0`b`c
	`n`@Du hast den Gegner `^'.$badguy['creaturename'].'`@ entdeckt, der sich mit seiner Waffe `%'.$badguy['creatureweapon'].'`@ auf dich stürzt!`0`n`n';

	if ($session['user']['alive'])
	{
		$out .= '`2Level: `6'.$badguy['creaturelevel'].'`0`n';
	}
	else
	{
		$out .= '`2Level: `6Untoter`0`n';
	}


	//kleines Extra by Takehon: Lebenspunkteanzeige mit Grafbar, dynamisch, immer 150px breit
	if ($session['user']['prefs']['showhpbar']==true)
	{
		$hp_value=round($session['user']['hitpoints']);
		$hp_maxvalue=($session['user']['alive']) ? max($session['user']['maxhitpoints'],$session['user']['hitpoints']) : ($session['user']['level'] * 5 + 50);
		$maxwert=max($badguy['creaturehealth'],$hp_maxvalue);
		$faktor=150/$maxwert;
		$out .= "`b`2Beginn der Runde:`0`b
		`n<table border='0'>
		<tr>
		<td>`2".$badguy['creaturename']."`2's ".$hp_name.": `6".round($badguy['creaturehealth'])."&nbsp;&nbsp;`0</td>
		<td>".grafbar($hp_maxvalue,$badguy['creaturehealth'],round($maxwert*$faktor),10,'badguyhealth_start')."</td>
		</tr><tr>
		<td>`2DEINE ".$hp_name.": `6".round($hp_value)."&nbsp;&nbsp;`0</td>
		<td>".grafbar($hp_maxvalue,$hp_value,round($hp_maxvalue*$faktor),10,'yourhealth_start')."</td>
		</tr>
		</table>`n";
	}
	else
	{
		$out .= '`2`bBeginn der Runde:`b`n
		`2'.$badguy['creaturename'].'`2\'s '.$hp_name.': `6'.round($badguy['creaturehealth']).' `0`n
		`2DEINE '.$hp_name.': `6'.round($session['user']['hitpoints']).'`0`n';
	}

}

//Alle Buffs werden auf "unbenutzt" gesetzt
if(is_array($session['bufflist']))
{
	foreach($session['bufflist'] as $key => $buff)
	{
		$session['bufflist'][$key]['used'] = 0;
	}
}

if ($badguy['pvp'] &&
	count($session['bufflist'])>0 &&
	is_array($session['bufflist']))
{
	if (mb_strlen($session['user']['buffbackup'])==0)
	{
		$out .= '`&Die Götter verbieten den Einsatz jeder Spezialfähigkeit!`n';
		$session['user']['buffbackup']=utf8_serialize($session['bufflist']);
		$session['bufflist']=array();
		if ($_GET['bg']==1)
		{
			$session['bufflist']['bodyguard'] = array('startmsg'=>'`n`$'.$badguy['creaturename'].'
			ist durch einen Leibwächter geschützt!`n`n',
			'name'=>'`&Leibwächter',
			'rounds'=>5,
			'wearoff'=>'Der Leibwächter scheint eingeschlafen zu sein.',
			'minioncount'=>1,
			'maxgoodguydamage'=> round($session['user']['level']>>1,0) +1,
			'effectmsg'=>'`7{badguy}\'s Leibwächter trifft dich mit `${damage}`7 Schadenspunkten.',
			'effectnodmgmsg'=>'`7{badguy}\'s Leibwächter versucht dich zu treffen, aber `$TRIFFT NICHT`7!',
			'activate'=>'roundstart'
			);
		}
		elseif ($_GET['bg']==2)
		{
			$session['bufflist']['heimvorteil'] = array('startmsg'=>'`n`$'.$badguy['creaturename'].'
			`$hat einen gewaltigen Heimvorteil!`n`n',
			'name'=>'`$Nachteil',
			'rounds'=>20,
			'wearoff'=>'Der Heimvorteil ist deinem Gegner nicht mehr von Vorteil.',
			'minioncount'=>1,
			'maxgoodguydamage'=> round($session['user']['level']+5),
			'effectmsg'=>'`7Durch {badguy}`7\'s Heimvorteil bekommst du zusätzlich `${damage}`7 Schadenspunkte.',
			'effectnodmgmsg'=>'',
			'activate'=>'roundstart'
			);
		}
		// Festung
		elseif ($_GET['bg']==3)
		{
			$session['bufflist']['festung'] = array('startmsg'=>'`n`$'.$badguy['creaturename'].'
			`$hat in seiner Festung dir gegenüber einen gewaltigen Vorteil!`n`n',
			'name'=>'`$Verteidigungsanlagen',
			'rounds'=>50,
			'wearoff'=>'Die Festung kann deinem Gegner nun auch nicht mehr helfen.',
			'minioncount'=>2,
			'maxgoodguydamage'=> round($session['user']['level']),
			'effectmsg'=>'`7Durch die Verteidigungsanlagen bekommst du zusätzlich `${damage}`7 Schadenspunkte.',
			'effectnodmgmsg'=>'',
			'activate'=>'roundstart'
			);
		}
		elseif ($_GET['bg']==4)
		// Turm
		{
			$session['bufflist']['turm'] = array('startmsg'=>'`n`$'.$badguy['creaturename'].'
			`$hat in seinem Turm dir gegenüber einen enormen Vorteil!`n`n',
			'name'=>'`$Gute Verteidigungsanlagen',
			'rounds'=>75,
			'wearoff'=>'Der Turm kann deinen Gegner nun auch nicht mehr schützen.',
			'minioncount'=>3,
			'maxgoodguydamage'=> round($session['user']['level']/1,2)+1,
			'effectmsg'=>'`7Durch die Verteidigungsanlagen bekommst du zusätzlich `${damage}`7 Schadenspunkte.',
			'effectnodmgmsg'=>'',
			'activate'=>'roundstart'
			);
		}
		elseif ($_GET['bg']==5)
		// Burg
		{
			$session['bufflist']['burg'] = array('startmsg'=>'`n`$'.$badguy['creaturename'].'
			`$hat in seiner Burg dir gegenüber einen extremen Vorteil!`n`n',
			'name'=>'`$Paranoid gute Verteidigungsanlagen',
			'rounds'=>100,
			'wearoff'=>'Die Burg kann deinem Gegner nun auch nicht mehr helfen.',
			'minioncount'=>4,
			'maxgoodguydamage'=> round($session['user']['level']/1,2)+1,
			'effectmsg'=>'`7Durch die Verteidigungsanlagen bekommst du zusätzlich `${damage}`7 Schadenspunkte.',
			'effectnodmgmsg'=>'',
			'activate'=>'roundstart'
			);
		}
	}
}
// Run the beginning of round buffs (this also calculates all modifiers)

// Alle $int_rounds_to_fight Kampfrunden abarbeiten
while ($int_rounds_to_fight > 0)
{
    $int_rounds_to_fight--;
	if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
	{
		//Wetterbeeinflussung
		if ($session['user']['alive'] && $session['user']['buffbackup']=='')
		{
			if (e_rand(1,6)==2)
			{
				//Auswirkung des Wetters auf den User im Kampf abholen
				$arr_battle_influence_buff = Weather::get_battle_influence_buff();
				
				if(count($arr_battle_influence_buff) > 0)
				{
					$session['bufflist']['weather'] =& $arr_battle_influence_buff;
				}
								
			}
		}
		//Wetterbeeinflussung

		// GILDENmod
		if (is_array($guild_buffs))
		{
			$session['bufflist'] = array_merge($session['bufflist'],$guild_buffs);
		}
		// END Gildenmod

		//Buffing am Rundenstart
		$buffset 		= activate_buffs('roundstart');
		$creaturedefmod	= $buffset['badguydefmod'];
		$creatureatkmod	= $buffset['badguyatkmod'];
		$atkmod			= $buffset['atkmod'];
		$defmod			= $buffset['defmod'];
	}

	if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
	{
		if ($badguy['pvp'])
		{
			$adjustedcreaturedefense = $badguy['creaturedefense'];
		}
		else
		{
			$adjustedcreaturedefense = ($creaturedefmod*$badguy['creaturedefense'] / ($adjustment*$adjustment));
		}
		$creatureattack = $badguy['creatureattack']*$creatureatkmod;
		$adjustedselfdefense = ($session['user']['defence'] * $adjustment * $defmod);
		// Wenn kein Schaden entsteht, irgendwann abbrechen: Sonst Endlosschleife
		$int_iterations = 0;
		while ($creaturedmg==0 && $selfdmg==0)
		{
			//---------------------------------
			$atk = $session['user']['attack']*$atkmod;
			if (e_rand(1,20)==1)
			{
				$atk*=3;
			}
			$patkroll = e_rand(0,$atk);
			$catkroll = e_rand(0,$adjustedcreaturedefense);
			$creaturedmg = 0-(int)($catkroll - $patkroll);
			if ($creaturedmg<0)
			{
				//output("`#DEBUG: Initial (<0) creature damage $creaturedmg`n");
				$creaturedmg = (int)($creaturedmg>>1);
				//output("`#DEBUG: Modified (<0) creature damage $creaturedmg`n");
				$creaturedmg = round($buffset['badguydmgmod']*$creaturedmg,0);
				//output("`#DEBUG: Modified (<0) creature damage $creaturedmg`n");
			}
			if ($creaturedmg > 0)
			{
				//output("`#DEBUG: Initial (>0) creature damage $creaturedmg`n");
				$creaturedmg = round($buffset['dmgmod']*$creaturedmg,0);
				//output("`#DEBUG: Modified (>0) creature damage $creaturedmg`n");
			}
			//output("`#DEBUG: Attack score: $atk`n");
			//output("`#DEBUG: Creature Defense Score: $adjustedcreaturedefense`n");
			//output("`#DEBUG: Player Attack roll: $patkroll`n");
			//output("`#DEBUG: Creature Defense roll: $catkroll`n");
			//output("`#DEBUG: Final Creature Damage: $creaturedmg`n");
			$pdefroll = e_rand(0,$adjustedselfdefense);
			$catkroll = e_rand(0,$creatureattack);
			$selfdmg = 0-(int)($pdefroll - $catkroll);
			if ($selfdmg<0)
			{
				//output("`#DEBUG: Initial (<0) self damage $selfdmg`n");
				$selfdmg=(int)($selfdmg>>1);
				//output("`#DEBUG: Modified (<0) self damage $selfdmg`n");
				$selfdmg = round($selfdmg*$buffset['dmgmod'], 0);
				//output("`#DEBUG: Modified (<0) self damage $selfdmg`n");
			}
			if ($selfdmg > 0)
			{
				//output("`#DEBUG: Initial (>0) self damage $selfdmg`n");
				$selfdmg = round($selfdmg*$buffset['badguydmgmod'], 0);
				//output("`#DEBUG: Modified (>0) self damage $selfdmg`n");
			}
			//output("`#DEBUG: Defense score: $adjustedselfdefense`n");
			//output("`#DEBUG: Creature Attack score: $creatureattack`n");
			//output("`#DEBUG: Player Defense roll: $pdefroll`n");
			//output("`#DEBUG: Creature Attack roll: $catkroll`n");
			//output("`#DEBUG: Final Player damage: $selfdmg`n");
			//output("`#DEBUG: count: $int_rounds_to_fight`n");
			$int_iterations++;
			if($int_iterations > 100)
			{
				break;
			}
		}
	}
	else
	{
		$creaturedmg=0;
		$selfdmg=0;
		$int_rounds_to_fight=0;
	}
	// Handle god mode's invulnerability
	if ($buffset['invulnerable'])
	{
		$creaturedmg = abs($creaturedmg);
		$selfdmg = -abs($selfdmg);
	}

	if (e_rand(1,3)==1 &&
		($_GET['op']=='search' ||
		($badguy['pvp'] && $_GET['act']=='attack')))
	{
		// Beginn: LP feststellen
		$badguy['starthp'] = $badguy['creaturehealth'];
		$badguy['actor_starthp'] = $session['user']['hitpoints'];

		if ($badguy['pvp'])
		{
			$out .= '`b`^'.$badguy['creaturename'].'`$\'s Fähigkeiten erlauben deinem Gegner den ersten Schlag!`0`b`n`n';
		}
		else
		{
			$out .= '`b`^'.$badguy['creaturename'].'`$ überrascht dich und hat den ersten Schlag!`0`b`n`n';
		}
		$_GET['op']='run';
		$surprised=true;
	}
	else
	{
		if ($_GET['op']=='search')
		{
			$out .= '`b`$Dein Können erlaubt dir den ersten Angriff!`0`b`n`n';
			// Beginn: LP feststellen
			$badguy['starthp'] = $badguy['creaturehealth'];
			$badguy['actor_starthp'] = $session['user']['hitpoints'];
		}
		$surprised=false;
	}

	if ($_GET['op']=='fight' || $_GET['op']=='run')
	{
		if ($_GET['op']=='fight')
		{
			if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
			{
				$buffset = activate_buffs('offense');
				if ($atk > $session['user']['attack'])
				{
					if ($atk > $session['user']['attack']*3)
					{
						if ($atk>$session['user']['attack']*4)
						{
							$out .= '`&`bDu holst zu einem <font size="+1">MEGA</font> Powerschlag aus!!!`b`n';
						}
						else
						{
							$out .= '`&`bDu holst zu einem DOPPELTEN Powerschlag aus!!!`b`n';
						}
					}
					else
					{
						if ($atk>($session['user']['attack']<<1))
						{
							$out .= '`&`bDu holst zu einem Powerschlag aus!!!`b`0`n';
						}
						elseif ($atk>$session['user']['attack']*1.25)
						{
							$out .= '`7`bDu holst zu einem kleinen Powerschlag aus!`b`0`n';
						}
					}
				}
				if ($creaturedmg==0)
				{
					$out .= '`4Du versuchst `^'.$badguy['creaturename'].'`4 zu treffen, aber `$TRIFFST NICHT!`n';
					if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
					{
						process_dmgshield($buffset['dmgshield'], 0);
					}
					if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
					{
						process_lifetaps($buffset['lifetap'], 0);
					}
				}
				else if ($creaturedmg<0)
				{
					$out .= '`4Du versuchst `^'.$badguy['creaturename'].'`4 zu treffen, aber der `$ABWEHRSCHLAG `4trifft dich mit `$'.(0-$creaturedmg).'`4 Schadenspunkten!`n';
					$badguy['diddamage']=1;
					$session['user']['hitpoints']+=$creaturedmg;
					if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
					{
						process_dmgshield($buffset['dmgshield'],-$creaturedmg);
					}
					if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
					{
						process_lifetaps($buffset['lifetap'],$creaturedmg);
					}
				}
				else
				{
					$out .= '`4Du triffst `^'.$badguy['creaturename'].'`4 mit `^'.$creaturedmg.'`4 Schadenspunkten!`n';
					$badguy['creaturehealth']-=$creaturedmg;
					if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
					{
						process_dmgshield($buffset['dmgshield'],-$creaturedmg);
					}
					if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
					{
						process_lifetaps($buffset['lifetap'],$creaturedmg);
					}
				}
				if ($creaturedmg>$session['user']['punch'])
				{
					$session['user']['punch']=$creaturedmg;
					$out .= '`@`b`c--- DAS WAR DEIN BISHER HÄRTESTER SCHLAG! ---`c`b`n';
				}
			}
		}
		else if ($_GET['op']=='run' && !$surprised)
		{
			$out .= '`4Du bist zu beschäftigt damit wegzulaufen wie ein feiger Hund und kannst nicht gegen `^'.$badguy['creaturename'].'`4 kämpfen.`n';
		}
		// We need to check both user health and creature health. Otherwise the user
		// can win a battle by a RIPOSTE after he has gone <= 0 HP.
		//-- Gunnar Kreitz
		if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
		{
			$buffset = activate_buffs('defense');
			if ($selfdmg==0)
			{
				$out .= '`^'.$badguy['creaturename'].'`4 versucht dich zu treffen, aber `$TRIFFT NICHT!`n';
				if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
				{
					process_dmgshield($buffset['dmgshield'], 0);
				}
				if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
				{
					process_lifetaps($buffset['lifetap'], 0);
				}
			}
			else if ($selfdmg<0)
			{
				$out .= '`^'.$badguy['creaturename'].'`4 versucht dich zu treffen, aber dein `^ABWEHRSCHLAG`4 trifft mit `^'.(0-$selfdmg).'`4 Schadenspunkten!`n';
				$badguy['creaturehealth']+=$selfdmg;
				if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
				{
					process_lifetaps($buffset['lifetap'], -$selfdmg);
				}
				if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
				{
					process_dmgshield($buffset['dmgshield'], $selfdmg);
				}
			}
			else
			{
				$out .= '`^'.$badguy['creaturename'].'`4 trifft dich mit `$'.$selfdmg.'`4 Schadenspunkten!`n';
				$session['user']['hitpoints']-=$selfdmg;
				if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
				{
					process_dmgshield($buffset['dmgshield'], $selfdmg);
				}
				if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0)
				{
					process_lifetaps($buffset['lifetap'], -$selfdmg);
				}
				$badguy['diddamage']=1;
			}
			// Specialfähigkeiten des Badguy:
			if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0 &&
			$badguy['starthp'] > 0 && $badguy['actor_starthp'] > 0 && !$badguy['pvp'] && !$badguy['acctid']
			&& isset($badguy['special_func']) )
			{
				$badguy_hp_diff = round($badguy['creaturehealth']/$badguy['starthp'],2);
				$actor_hp_diff = round($session['user']['hitpoints']/$badguy['actor_starthp'],2);
				$diff = $badguy_hp_diff - $actor_hp_diff;
				// Wenn es schlecht für Badguy ausschaut
				if ($diff < 0)
				{
					// Feststellen, ob Spieler stark gebufft
					// -2 wegen Multiplikatoren: 1 = Basis, zwei Werte hier
					$buffs_actor = $atkmod + $defmod - 2;
					$buffs_badguy = 2 - ($creatureatkmod + $creaturedefmod);
					$buffs_actor += $buffs_badguy;
					if ($buffs_actor > $badguy['special_minbuff'])
					{
						if ($badguy['special_uses'] > 0)
						{
							// Special einsetzen!
							$badguy['special_func']( $badguy );
						}
					}
					// END stark gebufft
				}
				// END Diff groß genug
			}
			// END Special vorhanden
		}
	}
	expire_buffs();


	$creaturedmg=0;
	$selfdmg=0;
	if ($int_rounds_to_fight>1 && $session['user']['hitpoints']>0 && $badguy['creaturehealth']>0)
	{
		$out .= '`2`bNächste Runde:`b`n';
	}
	if ($session['user']['hitpoints']<=0 || $badguy['creaturehealth']<=0)
	{
		$int_rounds_to_fight=-1;
	}

	if ($badguy['creaturehealth']<=0 && $session['user']['hitpoints']>0)
	{
		$victory=true;
        CQuest::fight(true);
		$defeat=false;
		$int_rounds_to_fight=0;
	}
	else
	{
		if ($session['user']['hitpoints']<=0)
		{
			$defeat=true;
            CQuest::fight(false);
			$victory=false;
			$int_rounds_to_fight=0;
		}
		else
		{
			$defeat=false;
			$victory=false;
		}
	}
}

if ($session['user']['hitpoints']>0 &&
$badguy['creaturehealth']>0 &&
($_GET['op']=='fight' || $_GET['op']=='run'))
{

	//kleines Extra by Takehon: Lebenspunkteanzeige mit Grafbar, dynamisch, immer 150px breit
	if ($session['user']['prefs']['showhpbar']==true)
	{
		$hp_value=round($session['user']['hitpoints']);
		$hp_maxvalue=($session['user']['alive'])?max($session['user']['maxhitpoints'],$session['user']['hitpoints']):($session['user']['level'] * 5 + 50);
		$maxwert=max($badguy['creaturehealth'],$hp_maxvalue);
		$faktor=150/$maxwert;
		$out .= "`2`bEnde der Runde:`b`0
		`n<table border='0'>
		<tr>
		<td>`2".$badguy['creaturename']."`2's ".$hp_name.": `6".round($badguy['creaturehealth'])."&nbsp;&nbsp;`0</td>
		<td>".grafbar($hp_maxvalue,$badguy['creaturehealth'],round($maxwert*$faktor),10,'badguyhealth_end')."</td>
		</tr><tr>
		<td>`0`2DEINE ".$hp_name.": `6".round($hp_value)."&nbsp;&nbsp;`0</td>
		<td>".grafbar($hp_maxvalue,$hp_value,round($hp_maxvalue*$faktor),10,'yourhealth_end')."</td>
		</tr>
		</table>`n";
	}
	else
	{
		$out .= '`2`bEnde der Runde:`b`n
		`2'.$badguy['creaturename'].'`2\'s '.$hp_name.': `6'.round($badguy['creaturehealth']).' `0`n
		`2DEINE '.$hp_name.': `6'.round($session['user']['hitpoints']).'`0`n';
	}
}

if ($victory || $defeat)
{
	// Unset the bodyguard buff at the end of the fight.
	// Without this, the bodyguard persists *and* the older buffs are held
	// off for a while! :/
	if (isset($session['bufflist']['bodyguard']))
	{
		unset($session['bufflist']['bodyguard']);
	}
	if (isset($session['bufflist']['weather']))
	{
		unset($session['bufflist']['weather']);
	}
	if (isset($session['bufflist']['heimvorteil']))
	{
		unset($session['bufflist']['heimvorteil']);
	}
	if (isset($session['bufflist']['festung']))
	{
		unset($session['bufflist']['festung']);
	}
	if (isset($session['bufflist']['turm']))
	{
		unset($session['bufflist']['turm']);
	}
	if (isset($session['bufflist']['burg']))
	{
		unset($session['bufflist']['burg']);
	}

	if (!is_array($session['bufflist']) || count($session['bufflist']) <= 0)
	{
		if (!$badguy['maze'])
		{
			$session['bufflist'] = utf8_unserialize($session['user']['buffbackup']);
		}
		if (is_array($session['bufflist']))
		{
			if (count($session['bufflist'])>0 && $badguy['pvp'])
			{
				$out .= '`&Die Götter gewähren dir wieder alle deine speziellen Fähigkeiten.`n`n';
			}
		}
		else
		{
			$session['bufflist'] = array();
		}
	}
	if (!$badguy['maze'])
	{
		$session['user']['buffbackup'] = "";
	}
	$session['user']['badguy'] = '';
	$session['user']['hitpoints']=max(0,$session['user']['hitpoints']);
}
else
{
	
	$session['user']['badguy']=utf8_serialize($badguy);
}

output($out);
?>