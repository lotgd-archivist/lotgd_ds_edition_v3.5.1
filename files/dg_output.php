<?php
/*-------------------------------/
Name: dg_output.php
Autor: tcb / talion für Drachenserver (mail: t@ssilo.de)
Erstellungsdatum: 6/05 - 9/05
Beschreibung:	Anzeigefunktionen des Gildensystems; übernehmen häufig benötigte Ausgaben, vornehmlich Listen.
				Diese Datei ist Bestandteil des Drachenserver-Gildenmods (DG).
				Copyright-Box muss intakt bleiben, bei Verwendung Mail an Autor mit Serveradresse.
/*-------------------------------*/

function dg_show_header ($txt) {

	output('`c`b`&'.$txt.'`0`b`c`n`n');

}

function dg_show_furniture ($where) {

	global $gid;

	$properties = ' deposit_guild>0 AND owner='.ITEM_OWNER_GUILD.' AND deposit1='.$gid.' AND deposit2='.($where == 'hall' ? ITEM_LOC_GUILDHALL : ITEM_LOC_GUILDEXT);
	$extra = ' ORDER BY name DESC, id ASC';

	$res = item_list_get ( $properties , $extra , true , ' name,description,id,furniture_guild_hook ' );

	$int_furniture_count = db_num_rows($res);

	if($int_furniture_count > 0) {
		output('<div style="width:500px;margin:10px;border:1px solid #FFFFFF;padding:5px;" align="left">`&`bMobiliar:`b',true);
	}

	$hooks = array();

	while($item = db_fetch_assoc($res)) {

		output("`n- `&".$item['name']."`0 (`i".$item['description']."`i)");

		if(!empty($item['furniture_guild_hook']) && !$hooks[$item['furniture_guild_hook']]) {
			$hooks[$item['furniture_private_hook']] = true;
			addnav($item['name'],'furniture.php?item_id='.$item['id']);
		}

	}
	if($int_furniture_count > 0) {
		output('</div>',true);
	}

}

function dg_show_builds ($gid,$actions=false,$admin_mode=0) {

	global $dg_builds,$dg_types,$dg_child_types,$dg_build_levels;

	$guild = &dg_load_guild($gid,array('build_list','type','gold','gems','points'));

	$out = array('','');	// Um zwischen typeneigenen und fremden Ausbauten zu trennen

	$count = 0;

	$recent_build = $guild['build_list'][0];

	if($admin_mode) {
		$link = 'su_guilds.php?op=edit&subop=save_builds&gid='.$gid;
		output('<form action="'.$link.'" method="POST">',true);
		addnav('',$link);
		if($guild['build_list'][0][0]) {
			$recent_form = '<select name="recent" size="1">';
			for($days=$guild['build_list'][0][1];$days>-1;$days--) {
				$recent_form .= '<option value="'.$days.'" '.($days==$guild['build_list'][0][1]?'selected="selected"':'').'>Noch '.$days.' '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage').'</option>';
			}
			$recent_form .= '</select>';
		}
	}

	foreach($dg_builds as $k=>$b) {

		if(!dg_build_is_allowed($gid,$k)) {continue;}

		$count++;
		$which = 1;
		$lvl = ($guild['build_list'][$k]) ? $guild['build_list'][$k] : 0;
		$max_lvl = 3;
		if( $dg_builds[$k]['special_types'] === true )
		{
			$max_lvl = DG_BUILD_MAX_LVL;
			$which=0;
		}
		else if( in_array($guild['ptype'],$dg_builds[$k]['special_types']) )
		{
			$max_lvl = DG_BUILD_MAX_LVL;
			$which=0;
		}

		$costs = &dg_get_build_cost($guild['ptype'],$k,$lvl);

		if($admin_mode) {

			$lvl_form = '<select name="build'.$k.'" size="1">';
			foreach($dg_build_levels as $l=>$name) {
				//if($l > 0) {
					$lvl_form .= '<option value="'.$l.'" '.($l==$lvl?'selected="selected"':'').'>'.$l.' : '.$name.'</option>';
				//}
			}
			$lvl_form .= '</select>';

		}

		$out[$which] .= '<tr class="'.($count%2?"trlight":"trdark").'"><td>`b'.$b['color'].$b['name'].'`0`b</td>
						<td>'.($admin_mode?$lvl_form.' ':'').$dg_build_levels[$lvl].(($recent_build[0] == $k)?' `i(Im Ausbau!)`i'.($admin_mode?$recent_form:''):'').(($lvl >= $max_lvl)?' `b(Maximum erreicht!)`b ':'').'</td>
						<td '.( ($guild['points'] >= $costs['gp']) ? 'style="color:#6F0"' : 'style="color:#F00"').'>&nbsp;'.number_format($costs['gp'],0,null,' ').'&nbsp;</td>
						<td '.( ($guild['gold'] >= $costs['gold']) ? 'style="color:#6F0"' : 'style="color:#F00"').'>&nbsp;'.number_format($costs['gold'],0,null,' ').'&nbsp;</td>
						<td '.( ($guild['gems'] >= $costs['gems']) ? 'style="color:#6F0"' : 'style="color:#F00"').'>&nbsp;'.number_format($costs['gems'],0,null,' ').'&nbsp;</td>
						<td>&nbsp;'.number_format($costs['days'],0,null,' ').' '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage').'&nbsp;</td>';

		if( $actions ) {
			$out[$which] .= '<td>';
			if(!$recent_build[0] && $lvl < $max_lvl) {
				if($guild['points'] >= $costs['gp'] && $guild['gold'] >= $costs['gold'] && $guild['gems'] >= $costs['gems']) {
					$out[$which] .= ' [ '.create_lnk('`@Beginnen!`0','dg_main.php?op=in&subop=builds&act=start&type='.$k).' ]`n';
				}
				else {
					$out[$which] .= 'Es fehlen: ';
					if($guild['points'] < $costs['gp'])$out[$which] .= '`$'.number_format($costs['gp']-$guild['points'],0,null,' ').'`0 GP ';
					if($guild['gold'] < $costs['gold'])$out[$which] .= '`$'.number_format($costs['gold']-$guild['gold'],0,null,' ').'`0 G ';
					if($guild['gems'] < $costs['gems'])$out[$which] .= '`$'.number_format($costs['gems']-$guild['gems'],0,null,' ').'`0 ES ';
				}

			}	// END if kein Ausbau in Arbeit

			if($lvl > 0) {

				$out[$which] .= ' [ '.create_lnk('`$Abreißen!`0','dg_main.php?op=in&subop=builds&act=del&type='.$k,true,false,'Diesen Ausbau wirklich komplett abreißen?').' ] ';

			}

			$out[$which] .= '</td>';
		}	// END if lvl < max

	}	// END foreach

	output('<table border="0" cellpadding="5" cellspacing="3"><tr class="trhead"><td>`bAusbau`b</td><td>`bStatus`b</td><td>`bGP`b</td><td>`bGold`b</td><td>`bEdelsteine`b</td><td>`bDauer`b</td>'.(($actions)?'<td>`bBauen?`b</td>':'').'</tr>',true);

	if($out[0] != '') {
		output('<tr class="trhead"><td colspan="7">`bSpezialausbauten '.$dg_child_types[$guild['type']][0].':`b</td></tr>',true);
		output($out[0],true);
	}

	if($out[1] != '') {
		output('<tr class="trhead"><td colspan="7">`bSonstige Ausbauten:`b</td></tr>',true);
		output($out[1],true);
	}

	if($admin_mode) {
		output('<tr class="trhead"><td colspan="7" align="right"><input type="submit" value="Speichern"></form></td></tr>',true);
	}

	output('</table>',true);

}

function dg_show_state_info ($gid) {

	$guild = &dg_load_guild($gid,array('points','gold','gems'));

	$int_max_regalia = getsetting('dgmaxregalia',15);

	output('<center><table border="0" cellspacing="5" cellpadding="5"><tr class="trhead"><td>`bGildenpunkte: '.$guild['points'].' | Gold: '.$guild['gold'].' | Edelsteine: '.$guild['gems'].' | Insignien: '.$guild['regalia'].' / '.$int_max_regalia.'`b</td></tr></table></center>`n',true);

}

// admin_mode: 0 Keine Rechte, 1 Ränge setzen, 2 entlassen / aufnehmen, 4 Nur Usereditorlink
function dg_show_member_list ($gid,
			$admin_mode = 0,
			$mail=true,
			$bio=true,
			$online=true,
			$orderby='guildfunc DESC, guildrank ASC, dragonkills DESC, name ASC') {

	global $session,$dg_funcs,$access_control;

	$guild = dg_load_guild($gid,array('ranks','founder'));

	$out = '';

	$bool_lockhtml = $access_control->su_check(access_control::SU_RIGHT_LOCKHTML); //unnötigen JOIN vermeiden
	$sql = 'SELECT
				accounts.acctid,name,login,sex,guildfunc,guildrank,loggedin,dragonkills,activated,laston,expedition,activated
				'.($bool_lockhtml ? ',aei.html_locked' : '').'
			FROM accounts
			'.($bool_lockhtml ? 'INNER JOIN account_extra_info aei ON accounts.acctid=aei.acctid' : '').'
			WHERE guildid='.$gid.((!$admin_mode)?' AND guildfunc!='.DG_FUNC_APPLICANT:'').'
			ORDER BY '.$orderby;
	$res = db_query($sql);

	if(!db_num_rows($res)) {return(false);}
	$out = '<table bgcolor="#999999" border="0" cellpadding="3" cellspacing="1">
	<tr class="trhead">
	<th>Nr.</th>
	<th>Name</th>
	<th>Rang</th>
	<th>Funktion</th>
	<th>Heldentaten</th>
	'.(($online)?'<th>Zuletzt da</th>':'');
	$out .= ($admin_mode) ? '<th>Aktionen</th>' : '';
	$out .= '</tr>';

	$count = 1;

	while($m = db_fetch_assoc($res)) {
		if($mail) {
			$maillink = "mail.php?op=write&to=".rawurlencode($m['login']);
			addnav("",$maillink);
		}

		$out .= '<tr class="'.($count%2?"trlight":"trdark").'"><td>'.$count.'</td>';
		$out .= '<td>'.($bio ? CRPChat::menulink($m) : $m['name']).'`0</td>';
		$out .= '<td>'.$guild['ranks'][$m['guildrank']][$m['sex']].' ('.$m['guildrank'].')</td>';
		$out .= '<td>'.( ($m['guildfunc']) ? $dg_funcs[$m['guildfunc']][$m['sex']] : 'Keine' ).($m['acctid']==$guild['founder']?' `i(Gründer'.($m['sex']?'in':'').')`i':'').'</td>';
		$out .= '<td align="center">'.$m['dragonkills'].'</td>';
		if($online) {
			if (user_get_online(0,$m))
			{
				$laston='`@Jetzt';
			}
			elseif (date('Y-m-d',strtotime($m['laston'])) == date('Y-m-d'))
			{
				$laston='Heute';
			}
			elseif (date('Y-m-d',strtotime($m['laston'])) == date('Y-m-d',strtotime("-1 day")))
			{
				$laston='Gestern';
			}
			else
			{
				$laston=round((strtotime(date('r'))-strtotime($m['laston'])) / 86400,0).' Tage';
			}
			
			$out .= '<td align="right">`4'.$laston.'`0</td>';
		}

		if($admin_mode) {
			$out .= '<td>';

			if($admin_mode == 4 && $access_control->su_check(access_control::SU_RIGHT_EDITORUSER)) {

				$out .= create_lnk('In Usereditor laden','user.php?op=edit&userid='.$m['acctid']);

			}
			else {
				if($m['guildfunc'] != DG_FUNC_APPLICANT) {
					$out .= create_lnk('Ändern','dg_main.php?op=in&subop=member_edit&acctid='.$m['acctid']);
				}

				if($admin_mode >= 2 || $admin_mode < 3) {
					if($m['guildfunc'] == DG_FUNC_APPLICANT) {
						$out .= '`n'.create_lnk('Aufnehmen','dg_main.php?op=in&subop=members&act=accept_applicant&acctid='.$m['acctid']);
						$out .= '`n'.create_lnk('Ablehnen','dg_main.php?op=in&subop=members&act=refuse_applicant&acctid='.$m['acctid']);
					}
					else {
						if($m['guildfunc'] <= $session['user']['guildfunc'] && $m['acctid'] != $session['user']['acctid'] && $m['acctid'] != $guild['founder']) {
							$out .= '`n'.create_lnk('Entlassen','dg_main.php?op=in&subop=members&act=fire&acctid='.$m['acctid'],true,false,'Bist du dir sicher, dieses Mitglied entlassen zu wollen?');
						}
					}	// END wenn Mitglied
				}	// END wenn Adminmode >= 2


			}	// END admin_mode != 4

			$out .= '</td>';
		}	// END if admin_mode

		$out .= '</tr>';

		$count++;

	}	// END while

	$out .= '</table>';

	output($out,true);

}

function dg_show_transfer_list ($gid,$acctid=0,$old=false) {

    global $access_control;

	$guild = &dg_load_guild($gid,array('transfers'));

	$out = '<table bgcolor="#999999" border="0" cellpadding="3" cellspacing="1"><tr class="trhead"><td>`bName`b</td><td>`bGold ein-/ausgezahlt`b</td><td>`bEdelsteine ein-/ausgezahlt`b</td><td>`bInsigniensplitter`b</td><td>`bIst Mitglied?`b</td></tr>';

	if(!$acctid) {

		if(!is_array($guild['transfers'])  || count($guild['transfers']) == 0 ) {
			$out .= '<tr><td colspan="5">`iKeine Transfers vorhanden!</td></tr>';
		}
		else {

			$ids = array_keys($guild['transfers']);
			$id_str = implode(',',$ids);
			$names = array();
            $bool_lockhtml = $access_control->su_check(access_control::SU_RIGHT_LOCKHTML);

			$sql = 'SELECT a.acctid,a.name,a.dragonkills,a.level,a.sex,a.guildid,a.guildfunc,
					a.superuser,a.activated,a.login,a.expedition,a.imprisoned
					'.($bool_lockhtml ? ' , aei.html_locked' : '').'
					FROM accounts a 
					'.($bool_lockhtml ? 'INNER JOIN account_extra_info aei ON a.acctid=aei.acctid' : '').'
					WHERE a.acctid IN ('.$id_str.') '.(!$old ? ' AND guildid='.$gid.' AND guildfunc!='.DG_FUNC_APPLICANT : '');
			$res = db_query($sql);
			while($a = db_fetch_assoc($res)) {
				$names[$a['acctid']] = $a;
			}
			$i=0;
			foreach($guild['transfers'] as $k=>$t) {

				$name = $names[$k]['name'];

				// Gleich mal aufräumen..
				if( ( $name == '' || !isset($name) ) && $old ) {
					unset($guild['transfers'][$k]);
				}
				// END gleich mal aufräumen

				if($name != '') {
					$i++;
					$out .= '<tr class="'.($i%2?"trlight":"trdark").'"><td>'.CRPChat::menulink($names[$k]).'</td><td align="center">'.
						(($t['gold_in'])?number_format($t['gold_in'],0,null,' '):'0').' / '.
						(($t['gold_out'])?number_format($t['gold_out'],0,null,' '):'0').
						'</td><td align="center">'.
						(($t['gems_in'])?number_format($t['gems_in'],0,null,' '):'0').' / '.
						(($t['gems_out'])?number_format($t['gems_out'],0,null,' '):'0').'</td>
						<td align="center">'.(($t['ins'])?number_format($t['ins'],0,null,' '):'Bisher keine').'</td>';
					$out .= '<td align="center">'.($names[$k]['guildid']==$gid && $names[$k]['guildfunc']!=DG_FUNC_APPLICANT?'Ja':'Nein').'</td></tr>';
				}
			}
		}

	}
	else {
		$t = &$guild['transfers'][$acctid];
		if(!$t) {$out .= '<tr class="trlight"><td  colspan="4">`iKeine Transfers vorhanden!</td></tr>';}
		else {
			$sql = 'SELECT name FROM accounts WHERE acctid = '.$acctid.' AND guildid='.$gid;
			$res = db_query($sql);
			$name = db_fetch_assoc($res);

			$out .= '<tr class="trlight"><td>'.$name['name'].'</td><td>'.
					(($t['gold_in'])?$t['gold_in']:'0').' / '.
					(($t['gold_out'])?$t['gold_out']:'0').
					'</td><td>'.
					(($t['gems_in'])?$t['gems_in']:'0').' / '.
					(($t['gems_out'])?$t['gems_out']:'0').'</td></tr>';
		}
	}

	$out .= '</table>';
	output($out,true);
}

function dg_show_ranks ($gid,$admin_mode=0) {

	global $session;

	$guild = &dg_load_guild($gid,array('ranks'));

	output('<table bgcolor="#999999" border="0" cellpadding="3" cellspacing="1"><tr class="trhead"><td>Nummer</td><td> <img src="./images/male.gif"> </td><td> <img src="./images/female.gif"> </td>'.($admin_mode ? '<td>Aktion</td>':'').'</tr>',true);

	foreach($guild['ranks'] as $k=>$v) {

		output('<tr class="'.($k%2?"trlight":"trdark").'" valign="top"><td align="center"  valign="middle">'.$k.'</td><td>'.$v[0],true);
		if($admin_mode) {
			rawoutput('<form method="POST" action="dg_main.php?op=in&subop=ranks&act=save&nr='.$k.'"><input type="text" name="man" value="'.$v[0].'" size="20" maxlength="25">');
		}
		output('</td><td>'.$v[1],true);
		if($admin_mode) {
			rawoutput('<br /><input type="text" name="woman" value="'.utf8_htmlspecialchars($v[1]).'" size="20" maxlength="25"></td><td valign="middle"><input type="submit" value="Speichern"></form>');
			addnav('','dg_main.php?op=in&subop=ranks&act=save&nr='.$k);
		}
		output('</td></tr>',true);

	}

	output('</table>',true);;

}

function dg_show_bio (&$char, $showit=true) {

	global $dg_funcs, $vc;
          $out = '';
	if($char['guildfunc'] == DG_FUNC_APPLICANT) 
	{
		$out .= '`nBewirbt sich gerade.';
	}
	else 
	{
	
		if(!$char['guildid'])
		{
			return;
		}
	
		$guild = dg_load_guild($char['guildid'],array('name','ranks','guildid','founder'));
	
		$out .= '`n`'.$vc.$guild['name'];//'`n<a href="dg_main.php?op=show_guild_bio&gid='.$guild['guildid'].'">`'.$vc.$guild['name'].'</a>';
		if($guild['top_repu']) {
			$out .= ' (Zur Zeit angesehenste Gilde '.getsetting('townname','Atrahor').'s!)';
		}
		$out .= '`n`0Rang: `n`'.$vc.$guild['ranks'][$char['guildrank']][$char['sex']];
		$out .= '`n`0Posten: `n`'.$vc.$dg_funcs[$char['guildfunc']][$char['sex']];
		if($guild['founder'] == $char['acctid']) {
			$out .= ' `i(Gründer'.($char['sex']?'in':'').')`i';
		}
	}

	if( $showit ){
		output($out,true);
	}
	return $out;
}

function dg_show_guild_bio ($gid) {

	global $dg_child_types,$profs,$dg_builds;

	$guild = &dg_load_guild($gid,array('name','bio','founded','type','ranks','founder','rules','professions_allowed','regalia','reputation','top_repu','build_list'));

	$count = dg_count_guild_members($gid);

	$str_buildings = '`2An der Gildenresidenz sind bisher ';
	$int_counter = 0;
	if(is_array($guild['build_list'])) {
		$str_buildings .= 'folgende Ausbauten zu erkennen:`n`^';
		foreach ($guild['build_list'] as $id=>$b) {

			if(isset($dg_builds[$id]) && $b > 0) {

				if($int_counter) {
					$str_buildings .= ', ';
				}
				$int_counter++;
				$str_buildings .= $dg_builds[$id]['name'];

			}

		}
	}
	else {
		$str_buildings .= 'keine Ausbauten zu erkennen!';
	}

	dg_show_header('Profil der Gilde '.$guild['name'].'');
	output('<table cellspacing="10" cellpadding="3" width="100%">
			<tr><td valign="top" width="30%">
			`@Typ: '.$dg_child_types[$guild['type']][1].$dg_child_types[$guild['type']][0].'`0`n
			`@Gründung: `^'.getgamedate($guild['founded']).'`n
			`@Insignien: `^'.$guild['regalia'].'`n
			`@Ansehen beim König: `^'.dg_get_reputation($gid).($guild['top_repu'] ? ' (Angesehenste Gilde!)' : '').'`n
			</td>
			<td valign="top">
			'.$str_buildings.'
			</td>
			</tr>
			</table>'
			,true);
	output('`n`n`@`bBio:`b`n`n`^'.closetags(strip_tags($guild['bio']),'`b`c`i').'`n`n`@`bRegeln der Gilde:`b`n`n`^'.strip_tags($guild['rules']).'',true);

	if( mb_strlen($guild['professions_allowed']) > 1) {
		$prof_list = explode(',',$guild['professions_allowed']);
		output('`n`n`@`bDie Gilde ist nur für Personen mit folgenden oder gar keinen Ämtern zugänglich:`b`^ ');

		require_once(LIB_PATH.'profession.lib.php');

		foreach($prof_list as $p) {
			if($p) {
				output($profs[$p][0].' ');
			}
		}
	}

	output('`n`n`^'.$count.' Mitglieder:`n`n',true);
	dg_show_member_list($gid,0,true,true,false);

	$out = '`n`n`@Letzte Leistungen (und Niederlagen) von '.$guild['name'].'`^';
	$result = db_query('SELECT * FROM news WHERE guildid='.$guild['guildid'].' ORDER BY newsdate DESC,newsid ASC LIMIT 30');
	$odate="";
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		if ($odate!=$row['newsdate']){
			$out .= '`n`b`^'.strftime('%A, %e. %B',strtotime($row['newsdate'])).'`b`n';
			$odate=$row['newsdate'];
		}
		$out .= '`^'.$row['newstext'].'`n';

	}

	output($out,true);

}


// admin_mode: 0 Keine Rechte, 1 Gilden zulassen / zurückweisen, 2 Alle Rechte
// diplo: 0 Keine Dilpomatie-Anzeige, 1 nur Anzeige, 2 nur ANgriff starten, 3 Verträge ändern
function dg_show_guild_list ($admin_mode = 0,
			$actions=false,
			$orderby='guildid ASC',
			$bio=true,
			$diplo=0) {

	global $session,$dg_states,$dg_points,$dg_child_types;

	$out = '';
	dg_load_guild(0,array('name','founded','state','type','immune_days','guildid','treaties','last_state_change','professions_allowed','guildwar_allowed','building_vars','war_target'));

	if( !count($session['guilds']) ) {output('`n`n`iZur Zeit sind keine Gilden vorhanden!`i`n`n');return(false);}

	if($diplo) {
		$guild = &$session['guilds'][$session['user']['guildid']];
		$treaties = &$guild['treaties'];

		// Feststellen, ob eine andere Gilde gerade einen Angriff auf uns laufen hat. Wenn ja, keine Statusänderungen
		// bei dieser Gilde möglich
		$sql = 'SELECT guildid FROM dg_guilds WHERE war_target='.$session['user']['guildid'];
		$res = db_query($sql);
		if(db_num_rows($res)) {

			$arr_warguilds = db_create_list($res, 'guildid');

		}

	}

	if($admin_mode) {

		$sql = 'SELECT a.acctid,a.guildfunc,a.guildid FROM accounts a LEFT JOIN dg_guilds g ON g.guildid=a.guildid WHERE a.guildid > 0 AND a.guildfunc!='.DG_FUNC_APPLICANT;
		$res2 = db_query($sql);

		$guilds_valid = array();

		while($a = db_fetch_assoc($res2)) {
			$guilds_valid[$a['guildid']]['membercount']++;

			if($a['guildfunc'] == DG_FUNC_LEADER) {
				$guilds_valid[$a['guildid']]['leader_count']++;
			}

		}

	}

	ksort($session['guilds']);

	$out = '<table border="0" cellpadding="5" cellspacing="1"><tr class="trhead"><td>`bName`b</td><td>`bGegründet`b</td>';
	$out .= ($diplo) ? '<td>`bVertrag`b</td>' : '';
	//$out .= ($actions || $diplo > 1) ? '<td>`bAktionen`b</td>' : '';
	$out .= ($admin_mode) ? '<td>`bStatusänderung`b</td><td>`bValide?`b</td>' : '';
	$out .= '</tr>';

	$count = 1;

	foreach($session['guilds'] as $g) {

		if($g['guildid'] == $session['user']['guildid'] && $diplo) {
			continue;
		}

		$trclass = ($count%2?"trlight":"trdark");

		$biolink = ( ($bio) ? '<a href="dg_main.php?op=show_guild_bio&gid='.$g['guildid'].'">' : '' );
		$biolink .= '`b'.$g['name'].'`b';
		$biolink .= ( ($bio) ? '</a>' : '' );
		if($bio) {
			addnav('','dg_main.php?op=show_guild_bio&gid='.$g['guildid']);
		}
		$out .= '<tr class="'.$trclass.'">';
		$out .= '<td>'.$biolink.'';
		$out .= ' ('.$dg_child_types[$g['type']][1].$dg_child_types[$g['type']][0].'`0'.
					(!empty($dg_states[$g['state']]) ? ', '.$dg_states[$g['state']] : '')
					.')</td>';
		$out .= '<td>&nbsp;'.getgamedate($g['founded']).'</td>';
		//$out .= '<td align="center">'.$dg_states[$g['state']].'</td>';

		if($diplo) {

			$out .= '<td>';
			if($treaties[ $g['guildid'] ][0] == DG_TREATY_WAR_SELF) {
				$out.='`4Krieg`0';
			}
			elseif($treaties[ $g['guildid'] ][0] == DG_TREATY_WAR_OTHER) {
				$out.='`4Krieg`0';
			}
			elseif($treaties[ $g['guildid'] ][0] == DG_TREATY_PEACE_SELF) {
				$out.='`@Frieden`0';
			}
			elseif($treaties[ $g['guildid'] ][0] == DG_TREATY_PEACE_OTHER) {
				$out.='`@Frieden`0';
			}
			else {
				$out.='Neutral';
			}
			if($guild['war_target'] == $g['guildid']) {
				$out.=' `i(Angriff läuft)`i';
			}
			if($treaties[ $g['guildid'] ][1] == 1) {
				$out .= ' `i(Angebot offen)`i';
			}
			$out .= '</td>';
		}

		if($actions || $diplo > 1) {

			$out .= '</tr><tr class="'.$trclass.'"><td colspan="5">`b&raquo;`b ';

			if($diplo > 1) {

				if($g['state'] == DG_STATE_ACTIVE && !$arr_warguilds[$g['guildid']] && $guild['war_target'] != $g['guildid']) {

					// Bei Krieg
					if($treaties[ $g['guildid'] ][0] == DG_TREATY_WAR_SELF
					|| $treaties[ $g['guildid'] ][0] == DG_TREATY_WAR_OTHER) {

						$link = 'dg_main.php?op=in&subop=treaties&act=neutral&target='.$g['guildid'];
						$out.='<a href="'.$link.'"> Neutral</a> | ';
						addnav('',$link);

						if($guild['guildwar_allowed'] && $guild['war_target'] == 0 && $g['guildwar_allowed']) {

							if($g['immune_days'] > 0) {
								$out .= '`iimmun`i';
							}
							else {
								$link = 'dg_main.php?op=in&subop=war&act=start&target='.$g['guildid'];
								$out.='<a href="'.$link.'">ANGRIFF ('.$dg_points['war_cost'].' Gildenpunkte)</a>';
								addnav('',$link);
							}

						}

						elseif($guild['war_target'] == $g['guildid']) {
							$link = 'dg_main.php?op=in&subop=war&act=cancel&target='.$g['guildid'];
							$out.='<a href="'.$link.'">ANGRIFF beenden</a>';
							addnav('',$link);
						}


					}
					// END Krieg

					// Bei Frieden
					elseif($treaties[ $g['guildid'] ][0] == DG_TREATY_PEACE_SELF
					|| $treaties[ $g['guildid'] ][0] == DG_TREATY_PEACE_OTHER
					&& $diplo >= 3) {

						// Friedensangebot am Laufen
						if($treaties[ $g['guildid'] ][1] == 1) {

							// Wenn Angebot von anderer Seite kommt:
							if($treaties[ $g['guildid'] ][0] == DG_TREATY_PEACE_OTHER) {
								$link = 'dg_main.php?op=in&subop=treaties&act=accept_peace&target='.$g['guildid'];
								$out.='<a href="'.$link.'">Angebot annehmen</a> | ';
								addnav('',$link);
								$link = 'dg_main.php?op=in&subop=treaties&act=refuse_peace&target='.$g['guildid'];
								$out.='<a href="'.$link.'">Angebot ablehnen</a> | ';
								addnav('',$link);
								$link = 'dg_main.php?op=in&subop=guild_talk&target='.$g['guildid'];
								$out.='<a href="'.$link.'">Gespräch</a>';
								addnav('',$link);
							}

						}
						else {	// sonst: -> Neutral

							$link = 'dg_main.php?op=in&subop=treaties&act=neutral&target='.$g['guildid'];
							$out.='<a href="'.$link.'">Neutral</a> | ';
							addnav('',$link);

							$link = 'dg_main.php?op=in&subop=guild_talk&target='.$g['guildid'];
							$out.='<a href="'.$link.'">Gespräch</a>';
							addnav('',$link);

						}

					}	// END Frieden

					else {	// Neutral

						$link = 'dg_main.php?op=in&subop=treaties&act=war&target='.$g['guildid'];
						$out.='<a href="'.$link.'">Krieg</a> | ';
						addnav('',$link);

						$link = 'dg_main.php?op=in&subop=treaties&act=peace&target='.$g['guildid'];
						$out.='<a href="'.$link.'">Frieden</a> | ';
						addnav('',$link);

						$link = 'dg_main.php?op=in&subop=guild_talk&target='.$g['guildid'];
						$out.='<a href="'.$link.'">Gespräch</a>';
						addnav('',$link);

					}

				}	// END if STATE_ACTIVE

			}	// END if diplo


			if($actions) {


				if($session['user']['guildid'] == $g['guildid']) {

					if($session['user']['guildfunc'] == DG_FUNC_APPLICANT) {

						$out .= '<a href="dg_council.php?op=apply&subop=cancel&gid='.$g['guildid'].'">Bewerbung zurückziehen</a>';
						addnav('','dg_council.php?op=apply&subop=cancel&gid='.$g['guildid']);

					}

				}
				elseif($session['user']['guildid'] == 0) {	// noch kein Mitglied, noch keine Bewerbung
					if(mb_strlen($g['professions_allowed']) == 0 || mb_strstr($g['professions_allowed'],$session['user']['profession'].',')) {
						$out .= '<a href="dg_council.php?op=apply&gid='.$g['guildid'].'">Bewerben</a>';
						addnav('','dg_council.php?op=apply&gid='.$g['guildid']);
					}
				}

			}	// END if actions

			$out .= '</td>';

		}	// END if actions | diplo

		if($admin_mode) {

			//$str = dg_calc_strength(array($g['guildid']));
//			$out .= '<td>'.round($str[$g['guildid']],1).'</td>';

			$out .= '<td>'.($g['last_state_change']!='0000-00-00 00:00:00' ? date('d. m. Y',strtotime($g['last_state_change'])) : 'Frisch gegründet').'</td>';

			$out .= '<td>';

			if($guilds_valid[$g['guildid']]['leader_count'] > 0 && $guilds_valid[$g['guildid']]['membercount'] >= getsetting('dgminmembers',3)) {
				$out .= '`@Ja`0';
			}
			else {
				$out .= '`4Nein`0';
			}

			$out .= '</td>';

			$out .= '<tr class="'.$trclass.'"><td colspan="5">`b&raquo;`b ';
			$out .= '<a href="su_guilds.php?op=logs&gid='.$g['guildid'].'">Logs</a>';
			addnav('','su_guilds.php?op=logs&gid='.$g['guildid']);

			if($admin_mode >= 2) {
				$out .= ' | <a href="su_guilds.php?op=edit&gid='.$g['guildid'].'">Edit</a>';
				addnav('','su_guilds.php?op=edit&gid='.$g['guildid']);

				$out .= ' | <a href="su_guilds.php?op=delete&gid='.$g['guildid'].'" onClick="return confirm(\'Willst du diese Gilde wirklich löschen?\');">Del</a>';
				addnav('','su_guilds.php?op=delete&gid='.$g['guildid']);
			}

			if($g['state'] == DG_STATE_INACTIVE) {
				$out .= ' | <a href="su_guilds.php?op=activate&gid='.$g['guildid'].'">Aktivieren</a>';
				addnav('','su_guilds.php?op=activate&gid='.$g['guildid']);

			}
			else {
				$out .= ' | <a href="su_guilds.php?op=deactivate&gid='.$g['guildid'].'" onClick="return confirm(\'Willst du diese Gilde wirklich deaktivieren?\');">Deaktivieren</a>';
				addnav('','su_guilds.php?op=deactivate&gid='.$g['guildid']);

			}
			$out .= ' | <a href="dg_main.php?op=in&gid='.$g['guildid'].'">Zum HQ</a>';
			addnav('','dg_main.php?op=in&gid='.$g['guildid']);
			$out .= '</td></tr>';

		}	// END if admin_mode

		$out .= '</tr><tr><td align="center" colspan="5">`b~~~`b</td></tr>';

		$count++;

	}	// END while

	$out .= '</table>';

	output($out,true);

}

// Entnommen aus hof.php
function dg_show_hof ($title, $sql, $none=false, $foot=false, $data_header=false, $tag=false){
	global $session, $subop, $order;

	$gpp = 50;
	$count_sql = 'SELECT COUNT(*) AS c FROM dg_guilds WHERE state!='.DG_STATE_INACTIVE;

	$result = db_query($count_sql);
	$row = db_fetch_assoc($result);
	$totalguilds = $row['c'];

	$page = 1;
	if ($_GET['page']) $page = (int)$_GET['page'];
	$pageoffset = $page;
	if ($pageoffset > 0) $pageoffset--;
	$pageoffset *= $gpp;
	$from = $pageoffset+1;
	$to = min($pageoffset+$gpp, $totalguilds);
	$limit = $pageoffset.','.$gpp;

	addnav('Sortieren nach');
	addnav('Besten','dg_council.php?op=hof&subop='.$subop.'&page='.$page);
	addnav('Schlechtesten','dg_council.php?op=hof&subop='.$subop.'&order=asc&page='.$page);
	addnav("Seiten");
	for($i = 0; $i < $totalguilds; $i += $gpp) {
		$pnum = ($i/$gpp+1);
		$min = ($i+1);
		$max = min($i+$gpp,$totalguilds);
		addnav('Seite '.$pnum.' ('.$min.'-'.$max.')', 'dg_council.php?op=hof&subop='.$subop.'&order='.$order.'&page='.$pnum);
	}
	addnav('Sonstiges');
	addnav('Zurück zum Gildenviertel','dg_main.php');

	dg_show_header('Ruhmeshalle der Gilden (Seite '.$page.': '.$from.'-'.$to.')');

	output('`c'.$title,true);

	output('`n<table cellspacing="2" cellpadding="3" align="center"><tr class="trhead">',true);
	output('<td>`bRang`b</td><td>`bName`b</td>', true);
	if ($data_header !== false) {
		for ($i = 0; $i < count($data_header); $i++) {
			output("<td>`b".$data_header[$i]."`b</td>", true);
		}
	}
	if(!is_array($sql)) {
		$result = db_query($sql);
	}
	$count = (is_array($sql) ? sizeof($sql) : db_num_rows($result));

	if ($count==0){
		$size = ($data_header === false) ? 2 : 2+count($data_header);
		if ($none === false) $none = "Keine Gilden gefunden";
		output('<tr class="trlight"><td colspan="'. $size .'" align="center">`&' . $none .'`0</td></tr>',true);
	} else {
		for ($i=0;$i<$count;$i++){

			if(!is_array($sql)) {$row = db_fetch_assoc($result);}
			else {$row = $sql[$i];}

			if ($row['guildid']==$session['user']['guildid']){
				//output("<tr class='hilight'>",true);
				output('<tr bgcolor="#005500">',true);
			} else {
				output('<tr class="'.($i%2?"trlight":"trdark").'">',true);
			}
			output('<td>'.($i+$from).'.</td><td>`&'.$row['name'].'`0</td>',true);
			if ($data_header !== false) {
				for ($j = 0; $j < count($data_header); $j++) {
					$id = 'data' . ($j+1);
					$val = $row[$id];
					if ($tag !== false) $val = $val . " " . $tag[$j];
					output('<td align="right">'.$val.'</td>',true);
				}
			}
			output("</tr>",true);
		}
	}
	output("</table>", true);
	if ($foot !== false) output('`n`c'.$foot.'`c');
}

function dg_show_hitlist ($gid,$admin_mode = false) {

	global $dg_funcs,$access_control;

	$g = &dg_load_guild($gid,array('hitlist','treaties'));

	$ids = '';

	if(!is_array($g['hitlist']) || sizeof($g['hitlist']) == 0) {
		output('`iKeine Aufträge vorhanden!`i');
		return;
	}

	foreach($g['hitlist'] as $victim => $o) {
		$ids .= ','.$victim;
	}
	
	$bool_lockhtml = $access_control->su_check(access_control::SU_RIGHT_LOCKHTML);
	$sql = 'SELECT a.acctid,a.name,a.dragonkills,a.level,a.sex,a.guildid,a.guildfunc,
					a.superuser,a.activated,a.login,a.alive,a.expedition,a.imprisoned,a.location,a.laston,a.loggedin,
					'.($bool_lockhtml ? 'aei.html_locked,' : '').'
					g.name AS guildname 					
			FROM accounts a 
			LEFT JOIN dg_guilds g ON g.guildid=a.guildid 
			'.($bool_lockhtml ? 'INNER JOIN account_extra_info aei ON a.acctid=aei.acctid' : '').'
			WHERE a.acctid IN (-1'.$ids.') 
			ORDER BY dragonkills DESC,level DESC,name ASC,acctid ASC';
	$res = db_query($sql);
	
	$out = '<table bgcolor="#999999" border="0" cellpadding="3" cellspacing="1"><tr class="trhead"><td>Name</td><td>Gilde (Funktion) / Vertrag</td><td>DKs</td><td>Level</td><td>Kopfgeld</td><td>Datum</td>'.($admin_mode ? '<td>Aktionen</td>':'');

	$counter = 0;

	while($a = db_fetch_assoc($res)) {

		$out .= '<tr class="'.($counter % 1 ? 'trlight' : 'trdark').'">
					<td>'.CRPChat::menulink($a).'`&</td>
					<td>';
		if($a['guildname']!='') {
			$out .= $a['guildname'].'`& ('.$dg_funcs[$a['guildfunc']][$a['sex']].')';
			$treaty = dg_get_treaty($g['treaties'][$a['guildid']]);

			if($treaty == 1) {$out.=' / `@Frieden`&';}
			elseif($treaty == -1) {$out.=' / `4Krieg`&';}
			elseif($treaty == 0) {$out.=' / Neutral`&';}
		}
		else {
			$out .= 'Keine';
		}

		$out .= '</td>
				<td>'.$a['dragonkills'].'</td>
				<td>'.$a['level'].'</td>
				<td>`^'.$g['hitlist'][$a['acctid']]['bounty'].'`& Gold</td>
				<td>'.getgamedate($g['hitlist'][$a['acctid']]['date']).'</td>';

		if($admin_mode) {
			$link = 'dg_main.php?op=in&subop=hitlist&act=del&acctid='.$a['acctid'];
			$out .= '<td><a href="'.$link.'">Entfernen</a></td>';
			addnav('',$link);
		}

		$out.='</tr>';

		$counter++;

	}

	$out .= '</table>';
	output($out,true);
}
?>
