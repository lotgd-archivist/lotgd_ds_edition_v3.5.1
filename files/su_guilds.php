<?php
/*-------------------------------/
Name: su_guilds.php
Autor: tcb / talion für Drachenserver (mail: t@ssilo.de)
Erstellungsdatum: 6/05 - 9/05
Beschreibung:	Diese Datei ist Bestandteil des Drachenserver-Gildenmods (DG). 
				Copyright-Box muss intakt bleiben, bei Verwendung Mail an Autor mit Serveradresse.
/*-------------------------------*/

require_once('common.php');
require_once(LIB_PATH.'dg_funcs.lib.php');
require_once('dg_output.php');
require_once(LIB_PATH.'profession.lib.php');

$access_control->su_check(access_control::SU_RIGHT_EDITORGUILDS,true);

//checkday(3);
page_header('Der Gildeneditor');

grotto_nav();

$op = ($_GET['op']) ? $_GET['op'] : '';

$str_filename = basename(__FILE__);

// Gilden komplett neu laden
//dg_load_guild(0,array(),true);

switch($op) {

	case '':
		
		addnav('Aktionen');
		addnav('Neue Gilde',$str_filename.'?op=new');
				
		dg_show_guild_list(2);
		
	break;
		
	case 'edit':
		
		$gid = ($_GET['gid']) ? $_GET['gid'] : 0;
		
		if(!$gid) {redirect('su_guilds.php');}
		
		$guild = &dg_load_guild($gid);
		
		if($_GET['subop'] == 'save') {
															
			foreach($_POST as $k=>$v) {
				if (isset($guild[$k])){
					$guild[$k] = $v;
				}
			}
			dg_save_guild();			
			redirect('su_guilds.php?op=edit&gid='.$gid);
			
		}
		elseif($_GET['subop'] == 'save_builds') {
			
			if($_POST['recent'] && is_array($guild['build_list'][0])) {
				$guild['build_list'][0][1] = $_POST['recent'];
				if($_POST['recent'] == 0) {	// wenn abgeschlossen..
					$type = $g['build_list'][0][0];
					$guild['build_list'][$type] = min($guild['build_list'][$type]+1,12);
					$guild['build_list'][0][0] = 0;
				}
			}
												
			foreach($dg_builds as $k=>$b) {
				$guild['build_list'][$k] = (int)$_POST['build'.$k];
			}
						
			dg_save_guild();
									
			redirect('su_guilds.php?op=edit&gid='.$gid);
			
		}
		
				
		dg_show_member_list($gid,4);
		output('`n`n');
		dg_show_builds($gid,false,1);
		
		$types = '0,Keiner';

		foreach($dg_child_types as $k=>$t) {
			
			$types .= ','.$k.','.$t[0].' ('.$dg_types[$t[3]]['name'].')';
			
		}
		
		
		$prof_list = '';
		foreach($profs as $k=>$p) {
			
			$prof_list .= $k.': '.$p[0].';`n';
			
		}
		
		output('`n`n');
				
		$edit_form = array(
						'Allgemeines,title',
						'guildid'=>'Gildenid,viewonly',
						'name'=>'Gildenname',
						'bio'=>'Gildenbio,textarea,40,20',
						'rules'=>'Gildenregeln,textarea,40,20',
						'guild_own_description'=>'Gildenhalle,textarea,40,20',
						'points'=>'Gildenpunkte,int',
						'reputation'=>'Ansehen,int',
						'atk_upgrade'=>'Angriffsupgrade,enum_order,0,3',
						'def_upgrade'=>'Verteidigungsupgrade,enum_order,0,3',
						'state'=>'Gildenstatus,enum,'.DG_STATE_INACTIVE.',Inaktiv,'.DG_STATE_ACTIVE.',Aktiv',
						'founded'=>'Gildengründung,viewonly',
						'founder'=>'Gründer (Userid),int',
						'guard_hp'=>'Aktuelle Anzahl an Gildenwachen,int',
						'guard_hp_before'=>'Anzahl an Gildenwachen vor Krieg,int',
						'war_target'=>'Aktuelles Kriegsziel (Gildenid),int',
						'immune_days'=>'Verbleibende Spieltage Immunität,int',
						'regalia'=>'Insignien,int',
						'gold'=>'Gold,int',
						'gems'=>'Edelsteine,int',
						'gold_in'=>'Goldeinzahlung bisher an diesem Spieltag,int',
						'gems_in'=>'Gemeinzahlung bisher an diesem Spieltag,int',
						'taxdays'=>'Tage seit letzter Steuerzahlung,int',
						'fights_suffered'=>'Angriffe an diesem Spieltag,int',
						'fights_suffered_period'=>'Angriffe in letzter Zeit,int',
						'type'=>'Gildentyp,enum,'.$types,
						'Sondereinstellungen,title',
						'professions_allowed'=>'Erlaubte Ämter in Gilde (Zahlenwert mit Komma. Leerlassen für alle Ämter:`n'.$prof_list.')',
						'guildwar_allowed'=>'Gildenkrieg für diese Gilde erlaubt,enum,1,Ja,0,Nein',
						'taxfree_allowed'=>'Steuerfreiheit für diese Gilde,enum,1,Ja,0,Nein',
						'Listen & Sonstiges,title',
						'lastupdate'=>'Letztes Update,viewonly',
						'build_list'=>'Ausbauten,viewonly',
						'points_spent'=>'Ausgegebene Punkte,viewonly',
						'treaties'=>'Verträge,viewonly',
						'transfers'=>'Transfers,viewonly',
						'ranks'=>'Ränge,viewonly'
						);
						
		$savelink = 'su_guilds.php?op=edit&subop=save&gid='.$gid;
		
		output('<form action="'.$savelink.'" method="POST">',true);
				
		showform($edit_form,$guild);
		
		output('</form>',true);
		
		addnav('',$savelink);
		addnav('Logs','su_guilds.php?op=logs&gid='.$gid);
		addnav('E?Zum Editor','su_guilds.php');
				
	break;
		
	case 'delete':
		
		$gid = ($_GET['gid']) ? $_GET['gid'] : 0;
		
		if(!$gid) {redirect('su_guilds.php');}
		
		if($_GET['subop'] == 'ok') {
			
			dg_massmail($gid,'`4Gilde gelöscht!','`4Die Gilde, in der du Mitglied warst, wurde von den Mods aufgelöst.`n
								Noch vorhandene Schätze wurden auf die Mitglieder verteilt.');			
			dg_delete_guild($gid);
			
			redirect('su_guilds.php');
						
		}
		else {
			
			output('`4Gilde ID '.$gid.' ('.$session['guilds'][$gid]['name'].'`4) wirklich löschen?');
			addnav('Nein!','su_guilds.php');
			addnav('Ja!','su_guilds.php?op=delete&subop=ok&gid='.$gid);
						
		}
		
	break;	// END del
		
	case 'activate':
		
		$gid = ($_GET['gid']) ? $_GET['gid'] : 0;
		
		if(!$gid) {redirect('su_guilds.php');}
		
		$guild = &dg_load_guild($gid);
				
		// Wenn erst gegründet
		if($guild['last_state_change'] == '0000-00-00 00:00:00') {
			
			// Wieder in den Bauzustand versetzen
			$guild['state'] = DG_STATE_IN_PROGRESS;
			
		}
		else {
			
			$guild['state'] = DG_STATE_ACTIVE;
			$guild['last_state_change'] = date('Y-m-d H:i:s');
					
		}
		
		dg_massmail($gid,'`@Gilde aktiviert!','`@Deine Gilde wurde von den Göttern wieder freigegeben und kann nun weiter genutzt werden.');
						
		dg_save_guild();
						
		redirect('su_guilds.php');
		
	break;
		
	case 'deactivate':
		
		$gid = ($_GET['gid']) ? $_GET['gid'] : 0;
		
		if(!$gid) {redirect('su_guilds.php');}
		
		$guild = &dg_load_guild($gid);
		
		dg_massmail($gid,'`4Gilde deaktiviert!','`4Deine Gilde wurde von den Mods deaktiviert. Wahrscheinlich hat sie nicht genügend Mitglieder (min. '.getsetting('dgminmembers',3).') bzw. keinen Gildenführer.');
						
		if($guild['state'] != DG_STATE_IN_PROGRESS) {
			$guild['last_state_change'] = date('Y-m-d H:i:s');
		}
		else {
			$guild['last_state_change'] = '0000-00-00 00:00:00';
		}
		
		$guild['state'] = DG_STATE_INACTIVE;
				
		dg_save_guild();
					
		redirect('su_guilds.php');
		
	break;
		
	case 'logs':
		
		$gid = $_GET['gid'];
		
		$sql = 'SELECT dg_log.*,g1.name as guildname,g2.name as targetname FROM dg_log LEFT JOIN dg_guilds as g1 ON g1.guildid=dg_log.guild LEFT JOIN dg_guilds as g2 ON g2.guildid=dg_log.target WHERE dg_log.guild='.$gid.' OR dg_log.target='.$gid.' ORDER by dg_log.date DESC,dg_log.logid ASC LIMIT 500';
		$result = db_query($sql);
		$odate = "";
		while($row=db_fetch_assoc($result)) {

			$dom = date("D, M d",strtotime($row['date']));
			if ($odate != $dom){
				output("`n`b`@".$dom."`b`n");
				$odate = $dom;
			}
			$time = date("H:i:s", strtotime($row['date']));
			output($time.' - '.$row['guildname'].' '.$row['message']);
			if ($row['target']) output(' '.$row['targetname']);
			output("`n");
		}
		
		addnav('Zurück',$g_ret_page);
		
	break;
		
	case 'callking':
				
		savesetting('dgkingdays','0');		
		savesetting('newdaysemaphore','0000-00-00 00:00:00');		
		
		$session['user']['lasthit'] = 0;
				
		addnav('Zurück','dg_main.php');
		
	break;
		
	case 'new':
		
		dg_show_header('Gilde anlegen');
		
		$str_out = '';
		$int_id = (int)$_REQUEST['id'];

		// AccountID gegeben: Restformular anzeigen!
		if(!empty($int_id)) {
			
			$str_name = $_POST['name'];
			$int_type = (int)$_POST['type'];
			
			$arr_acc = db_fetch_assoc(db_query('SELECT name,login,acctid FROM accounts WHERE acctid='.$int_id));
			
			// Wenn Daten gegeben: Speichern!
			if(!empty($str_name) && !empty($int_type)) {
				
				$arr_data = array
									(
										'founder'=>$int_id,
										'founded'=>getsetting('gamedate',''),
										'name'=>$str_name,
										'type'=>$int_type,
										'immune_days'=>getsetting('dgimmune',6),
										'ranks'=>$dg_default_ranks,
										'state'=>DG_STATE_IN_PROGRESS,
										'points'=>dg_calc_boni($gid,'startpts',getsetting('dgstartpoints',10)),
										'regalia'=>dg_calc_boni($gid,'startregalia',getsetting('dgstartregalia',10)),
										'guard_hp'=>dg_calc_boni($gid,'startguardhp',100)
									);
				
				db_insert('dg_guilds',$arr_data);
				
				$int_gid = db_insert_id();
				
				user_update(
					array
					(
						'guildid'=>$int_gid,
						'guildfunc'=>DG_FUNC_LEADER,
						'guildrank'=>1
					),
					$int_id
				);
												
				dg_addnews($arr_acc['name'].'`@ hat die Gilde '.$str_name.'`@ gegründet!',$arr_acc['acctid'],$int_gid);
					
				addhistory('`2Gegründet von '.$arr_acc['name'].'`2!',2,$int_gid);
				addhistory('`2Gründung der Gilde '.$str_name.'`2',1,$arr_acc['acctid']);
				
				systemmail($int_id,'`b`2Deine Gilde wurde angenommen!`0`b',
									'Sei gegrüßt!`n`nDie von dir eingereichte Gildenidee wurde von den Göttern für gut befünden und freigeschaltet.`n
									Du kannst nun im Gildenviertel die Residenz deine Gilde beziehen. Zunächst solltest du dich daran machen,
									die Gilde fertigzustellen.');
									
				$session['message'] = '`@Gilde erfolgreich angelegt!`0';
				redirect($str_filename);
				
			}
			else {
				
				addnav('Anderer Gründer!',$str_filename.'?op=new');
				
				$str_lnk = $str_filename.'?op=new&id='.$int_id;
				addnav('',$str_lnk);
				$str_out .= '`c<form method="POST" action="'.$str_lnk.'">';
				
				foreach($dg_child_types as $k=>$t) {
					$type_enum .= ','.$k.','.$t[0].' ('.$dg_types[$t[3]]['name'].')';						
				}	
				
				$arr_form = array(
									'founder_name'=>'Gründer:,viewonly',
									'name'=>'Name der Gilde:|?(max. 40 Zeichen inkl. Farbcodes, unveränderlich)',
									'type'=>'Art der Gilde:,enum'.$type_enum									
								);
								
				$str_out .= generateform($arr_form,array('founder_name'=>$arr_acc['name']),false,'Gilde anlegen!');
				
				$str_out .= '</form>';
					
			}

		}
		else {	// Sonst: Suchformular anzeigen

			$str_lnk = $str_filename.'?op=new';
			addnav('',$str_lnk);
			
			if(getsetting('dgguildmax',100) <= dg_count_guilds()) {
				$str_out .= '`$Achtung: `&Das in den Spieleinstellungen festgelegte Gildenlimit wurde bereits erreicht.`n`n';	
			}
						
			$str_out .= '`c<form method="POST" action="'.$str_lnk.'">';

			// Name eingegeben?
			if(!empty($_POST['search']) && mb_strlen($_POST['search']) > 2) {

				$str_search = str_create_search_string($_POST['search']);

				$sql = 'SELECT login, acctid FROM accounts WHERE login LIKE "'.$str_search.'" AND guildid=0 ORDER BY login ASC';
				$res = db_query($sql);

				if(!db_num_rows($res)) {
					$str_out .= '`nEs wurden keine Accounts gefunden, die auf deine Eingabe passen!';
				}
				else {
					$str_out .= '<select name="id">';
					while($a = db_fetch_assoc($res)) {
						$str_out .= '<option value="'.$a['acctid'].'">'.$a['login'].'</option>';
					}
					$str_out .= '</select>';
				}
				
				addnav('Neue Suche!',$str_filename.'?op=new');

			}
			else {	// Sonst: Eingabefeld
				$str_out .= 'Name des Gründers (gesucht wird nur nach Spielern, die keiner Gilde angehören): <input type="text" name="search"> ';
			}

			$str_out .= ' <input type="submit" value="Übernehmen">
						</form>`c';

		}

		output($str_out,true);
		
		addnav('Zurück');
		addnav('Zum Editor',$str_filename);
		
	break;
		
}	

page_footer();
?>
