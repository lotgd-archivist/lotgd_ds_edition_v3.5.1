<?php
//Stalltier Editor
//übersetzt von Fossla für atrahor.de am 15.7.06
//Komplettüberarbeitung für DSE 2.5 by Salator 15.9.08

require_once "common.php";
$access_control->su_check(access_control::SU_RIGHT_EDITORMOUNTS,true);

page_header("Stalltier Editor");
addnav('Zurück');
grotto_nav();
$str_filename = basename(__FILE__);

addnav('Aktionen');
if(isset($_GET['usermounts']))
{
	addnav('U?Stalltiere anzeigen',$str_filename);
}
else 
{
	addnav('Usertiere anzeigen',$str_filename.'?usermounts=1');
	addnav('Tier hinzufügen','su_mounts.php?op=edit');
}

output("`c`b`&Stalltiereditor`0`b`c`n");

if(isset($session['message']))
{
	output($session['message']);
	unset($session['message']);
}

if ($_GET['op']=='')
{
	if(isset($_GET['usermounts']))
	{
		$sql = 'SELECT * FROM mounts WHERE creator != 0 ORDER BY mountcategory, mountcostgems, mountcostgold';
	}
	else 
	{
		$sql = 'SELECT * FROM mounts WHERE creator = 0 ORDER BY mountcategory, mountcostgems, mountcostgold';
	}
	
	$result = db_query($sql);
	$cat = '';
	
	$str_output.='
	<table border="0" cellspacing="0" cellpadding="3" align="center">
	<tr class="trlight">
		<th>Name</th>
		<th>Preis</th>
		<th>Eigenschaften</th>
		<th>Mountbuff</th>
		<th>Aktionen</th>
	</tr>';
	
	$int_count = db_num_rows($result);
	for ($i=0; $i<$int_count; $i++)
	{
		$row = db_fetch_assoc($result);
		if ($cat!=$row['mountcategory'])
		{
			$str_output.='<tr class="trhead">
			<td colspan="5" align="center">Kategorie: '.$row['mountcategory'].'</td>
			</tr>';
			$cat = $row['mountcategory'];
			$trclass='trlight';
		}
		//Den Buff laden
		$row['mountbuff'] = adv_unserialize($row['mountbuff']);
		$str_mountbuff_desc = '';
		foreach ($row['mountbuff'] as $key=>$val)
		{
			$str_mountbuff_desc .= "<b>$key</b>: $val`0<br/><hr/><br/>";
		}
		
		$trclass=($trclass=='trdark'?'trlight':'trdark');
		$str_output.='
		<tr class="'.$trclass.'">
			<td>'.($row['mountactive']?'':'<strike>').create_lnk($row['mountname'].'`0','su_mounts.php?op=edit&id='.$row['mountid']).($row['mountactive']?'':'</strike>').'</td>
			<td>'.$row['mountcostgems'].'&nbsp;Edels, '.$row['mountcostgold'].'&nbsp;Gold</td>
			<td>DK:&nbsp;'.$row['mindk'].', WK:&nbsp;'.$row['mountforestfights'].', Tav:&nbsp;'.$row['tavern'].', Mine:&nbsp;'.$row['mine_canenter'].'|'.$row['mine_cansave'].'</td>
			<td>'.jslib_hint('<img src="./images/icons/petition_view.png" alt="Buff ansehen"/>',$str_mountbuff_desc).'</td>
			<td width="40" align="right">'.create_lnk('<img border="0" src="./images/icons/petition_delete.png" alt="Löschen" title="Löschen"/>','su_mounts.php?op=del&id='.$row['mountid']).'</td>
		</tr>';
	}
	output($str_output.'</table>');
}

elseif ($_GET['op']=="save")
{	
	if ($_POST['mount_data']['mountid']>0)
	{
		$mount = db_get('SELECT * FROM mounts WHERE mountid='.$_POST['mount_data']['mountid']);
	}
	
	if(!is_null_or_empty($_POST['mount_data']['newcategory']))
	{
		$_POST['mount_data']['mountcategory']=$_POST['mount_data']['newcategory'];
	}
	
	unset($_POST['mount_data']['newcategory']);

	$arr_keys='';
	$arr_vals='';
	$arr_set_sql='';

	foreach($_POST['mount_data'] as $key=>$val)
	{
		if (is_array($val))
		{
			$val = utf8_serialize($val);
		}
		if ((int)$_POST['mount_data']['mountid'] != 0)
		{
			$arr_set_sql[] = "$key='".db_real_escape_string($val)."'";
		}
		else
		{
			$keys[] = $key;
			$vals[] = "'".db_real_escape_string($val)."'";
		}
	}
	if ($_POST['mount_data']['mountid']>0)
	{
		$sql = 'UPDATE mounts SET '.implode(',',$arr_set_sql).' WHERE mountid="'.$_POST['mount_data']['mountid'].'"';
	}
	else
	{
		$sql='INSERT INTO mounts ('.implode(',',$keys).') VALUES ('.implode(',',$vals).')';
	}
	db_query($sql);
	if (db_affected_rows()>0)
	{
		$session['message']='`c`@Das Tier wurde gespeichert!`0`c`n';
		systemlog('Mount '.$mount['mountname'].'`0 geändert!',$session['user']['acctid']);
		Cache::delete(Cache::CACHE_TYPE_SESSION, 'playermount' );
	}
	else
	{
		$session['message']='`c`$Tier `bnicht`b gespeichert!`0`c`n';
	}
	redirect('su_mounts.php');
}
elseif ($_GET['op']=="edit")
{
	addnav("Zurück zum Editor","su_mounts.php");
	$mount=array();
	if($_GET['id']>'')
	{
		$sql = "SELECT * FROM mounts WHERE mountid='".(int)$_GET['id']."'";
		$result = db_query($sql);
		if (db_num_rows($result)<=0)
		{
			output("`iDieses Stalltier wurde nicht gefunden.`i");
		}
		else
		{
			$arr_mount_data 				= db_fetch_assoc($result);
			$arr_mount_data['mountbuff']	= utf8_unserialize(($arr_mount_data['mountbuff']));
			
			//Formdaten erstellen
			$arr_mount_data = generate_form_data($arr_mount_data,'mount_data');
		}
	}
	
	// Alte Kampfmeldungen durch neue ersetzen
	$arr_mount_data['mount_data[mountbuff]']['msg_round'] = is_null_or_empty($arr_mount_data['mount_data[mountbuff][msg_round]']) == false ? $arr_mount_data['mount_data[mountbuff][msg_round]'] : $arr_mount_data['mount_data[mountbuff][roundmsg]'];
	
	$arr_mount_data['mount_data[mountbuff][msg_no_effect]'] = is_null_or_empty($arr_mount_data['mount_data[mountbuff][msg_no_effect]']) == false ? $arr_mount_data['mount_data[mountbuff][msg_no_effect]'] : '';
	
	$arr_mount_data['mount_data[mountbuff][msg_wearoff]'] = is_null_or_empty($arr_mount_data['mount_data[mountbuff][msg_wearoff]']) == false ? $arr_mount_data['mount_data[mountbuff][msg_wearoff]'] : $arr_mount_data['mount_data[mountbuff][wearoff]'];
	
	$arr_mount_data['mount_data[mountbuff][msg_lifetap_success]'] = is_null_or_empty($arr_mount_data['mount_data[mountbuff][msg_lifetap_success]']) == false ? $arr_mount_data['mount_data[mountbuff][msg_lifetap_success]'] : '';
	
	$arr_mount_data['mount_data[mountbuff][msg_lifetap_fail]'] = is_null_or_empty($arr_mount_data['mount_data[mountbuff][msg_lifetap_fail]']) == false ? $arr_mount_data['mount_data[mountbuff][effectfailmsg]'] : '';
	
	$arr_mount_data['mount_data[mountbuff][msg_regen_success]'] = is_null_or_empty($arr_mount_data['mount_data[mountbuff][msg_regen_success]']) == false ? $arr_mount_data['mount_data[mountbuff][msg_regen_success]'] : '';
	
	$arr_mount_data['mount_data[mountbuff][msg_regen_fail]'] = is_null_or_empty($arr_mount_data['mount_data[mountbuff][msg_regen_fail]']) == false ? $arr_mount_data['mount_data[mountbuff][msg_regen_fail]'] : '';
	
	$arr_mount_data['mount_data[mountbuff][msg_effect_success]'] = is_null_or_empty($arr_mount_data['mount_data[mountbuff][msg_effect_success]']) == false ? $arr_mount_data['mount_data[mountbuff][msg_effect_success]'] : $arr_mount_data['mount_data[mountbuff][effectmsg]'];
	
	$arr_mount_data['mount_data[mountbuff][msg_effect_fail]'] = is_null_or_empty($arr_mount_data['mount_data[mountbuff][msg_effect_fail]']) == false ? $arr_mount_data['mount_data[mountbuff][msg_effect_fail]'] : $arr_mount_data['mount_data[mountbuff][effectfailmsg]'];

	$sql='SELECT mountcategory FROM mounts GROUP BY mountcategory';
	$result=db_query($sql);
	while($row=db_fetch_assoc($result))
	{
		$cat.=','.$row['mountcategory'].','.$row['mountcategory'];
	}

	$arr_form=array(
		'Eigenschaften,title'
		,'mount_data[mountid]'			=>'ID,hidden|?Die ID, unter der das Tier in der DB gespeichert ist.'
		,'mount_data[mname_prev]'		=> 'Namens-Vorschau:,preview,mount_data[mountname]'
		,'mount_data[mountname]'		=> 'Tiername,40'
		,'mount_data[mdesc_prev]'		=> 'Beschreibungs-Vorschau:,preview,mount_data[mountdesc]'
		,'mount_data[mountdesc]'		=> 'Beschreibung für Mericks Ställe,textarea,66,8'
		,'mount_data[mountcategory]'	=> 'Tier-Kategorie,enum'.$cat
		,'mount_data[newcategory]'		=> 'oder neue Kategorie eingeben,40'
		,'mount_data[mountcostgold]'	=> 'Kosten an Gold,int'
		,'mount_data[mountcostgems]'	=> 'Kosten an Edelsteinen,int'
		,'mount_data[mountactive]'		=> 'Für Spieler freigegeben?,bool'
		,'mount_data[mindk]'			=> 'Erhältlich ab DK,int'
		,'mount_data[mountforestfights]'=> 'Zusätzliche Waldkämpfe pro Tag,int'
		,'mount_data[tavern]'			=> 'Findet DarkHorse Taverne,bool'
		,'mount_data[newday_prev]'		=> 'Newday-Vorschau:,preview,mount_data[newday]'
		,'mount_data[newday]'			=> 'Nachricht am neuen Tag,88'
		,'mount_data[recharge_prev]'	=> 'Recharge-Vorschau:,preview,mount_data[recharge]'
		,'mount_data[recharge]'			=> 'Nachricht bei vollkommener Erholung,88'
		,'mount_data[partrecharge_prev]'=> 'PartRecharge-Vorschau:,preview,mount_data[partrecharge]'
		,'mount_data[partrecharge]'		=> 'Nachricht bei teilweiser Erholung,88'
		,'mount_data[trainingcost]'		=> 'Faktor für Kosten bei Tiertrainer,int|?Wert hoch der schon erhaltenen Runden'

		,'Kampf,title'
		,'mount_data[aname_prev]'					=> 'Aktionsname-Vorschau:,preview,mount_data[mountbuff][name]'
		,'mount_data[mountbuff][name]'				=> 'Aktionsname|?'
		,'mount_data[msg_round_prev]'				=> ',preview,mount_data[mountbuff][msg_round]'
		,'mount_data[mountbuff][msg_round]'			=> 'In jeder Kampfrunde wirst du folgende Aktion von deinem Tier bemerken,88'		
		,'mount_data[msg_no_effect_prev]'			=> ',preview,mount_data[mountbuff][msg_no_effect]'
		,'mount_data[mountbuff][msg_no_effect]'		=> 'Dein Tier betrachtet das Geschehen nur passiv,88|?Manchmal kann es vorkommen dass dein Tier in einer Runde nichts macht. Dies kannst du hier beschreiben.'		
		,'mount_data[msg_wearoff_prev]'				=> ',preview,mount_data[mountbuff][msg_wearoff]'
		,'mount_data[mountbuff][msg_wearoff]'		=> 'Was macht dein Tier sobald es erschöpft ist und dich fortan nicht mehr unterstützen kann?,88|?Meldung wenn die Runden des Tieres aufgebraucht sind.'		
		,'mount_data[msg_lifetap_success_prev]'		=> ',preview,mount_data[mountbuff][msg_lifetap_success]'
		,'mount_data[mountbuff][msg_lifetap_success]'=> 'Was macht dein Tier falls es Schaden zu Lebensenergie wandeln muss?,88'
		,'mount_data[msg_lifetap_fail_prev]'		=> ',preview,mount_data[mountbuff][msg_lifetap_fail]'
		,'mount_data[mountbuff][msg_lifetap_fail]'	=> 'Was macht dein Tier falls es keinen Schaden zu Lebensenergie wandeln muss?,88|?Solltest du bei bester Gesundheit sein und dein Tier versucht Schaden zu Lebensenergie zu wandeln,<br />dann erscheint diese Nachricht.'
		,'mount_data[msg_regen_success_prev]'		=> ',preview,mount_data[mountbuff][msg_regen_success]'
		,'mount_data[mountbuff][msg_regen_success]'	=> 'Was macht dein Tier wenn es dich heilt,88|?Wenn dich dein Tier um eine bestimmte Menge an Punkten je Runde heilt,<br />erscheint diese Nachricht.'
		,'mount_data[msg_regen_fail_prev]'			=> ',preview,mount_data[mountbuff][msg_regen_fail]'
		,'mount_data[mountbuff][msg_regen_fail]'	=> 'Effekt Nachricht bei vollkommener Gesundheit des Spielers,88|?Wenn dich dein Tier um eine bestimmte Menge an Punkten je Runde heilt,<br />du jedoch bei bester Gesundheit bist, erscheint diese Nachricht.'
		,'mount_data[msg_effect_success_prev]'		=> ',preview,mount_data[mountbuff][msg_effect_success]'
		,'mount_data[mountbuff][msg_effect_success]'=> 'Effekt Nachricht,88|?Diese Nachricht wird ausgegeben wenn dein Tier normal angreift.'
		,'mount_data[msg_effect_fail_prev]'			=> ',preview,mount_data[mountbuff][msg_effect_fail]'
		,'mount_data[mountbuff][msg_effect_fail]'	=> 'Effekt Fehlschlag Nachricht,88|?Ist der Angriff deines Tiers fehlgeschlagen wird diese Nachricht ausgegeben.'

		,'mount_data[effects]'			=> 'Effekte,divider'
		,'mount_data[mountbuff][rounds]'			=> 'Runden am neuen Tag,int'
		,'mount_data[mountbuff][atkmod]'			=> 'Spieler Angriffs-Multiplikator,int'
		,'mount_data[mountbuff][defmod]'			=> 'Spieler Verteidigungs-Multiplikator,int'
		,'mount_data[mountbuff][regen]'			=> 'Heilt Spieler x Punkte pro Runde,int'
		,'mount_data[mountbuff][minioncount]'		=> 'Mehrfach-Effekt,int'
		,'mount_data[mountbuff][minbadguydamage]'	=> 'Min Punkte Gegnerschaden,int'
		,'mount_data[mountbuff][maxbadguydamage]'	=> 'Max Punkte Gegnerschaden,int'
		,'mount_data[mountbuff][lifetap]'			=> 'Lebenskraft-Multiplikator(Lifetap),int|?Multipliziert vom Spieler zugefügten Schaden mit Faktor und addiert den Wert zu Spielerlebenspunkten hinzu.'
		,'mount_data[mountbuff][damageshield]'		=> 'Schadensabwehr,int|?Multipliziert vom Spieler zugefügten Schaden mit Faktor und zieht den finalen Wert vom Gegner ab.'
		,'mount_data[mountbuff][badguydmgmod]'		=> 'Gegnerschaden Multiplikator,int|?Multipliziert den vom Gegner zugefügten Schaden mit Faktor und zieht den finalen Wert vom Spieler ab.'
		,'mount_data[mountbuff][badguyatkmod]'		=> 'Gegner Angriffs-Multiplikator,int|?Multipliziert den vom Gegner zugefügten Schaden mit Faktor und zieht den finalen Wert vom Spieler ab.'
		,'mount_data[mountbuff][badguydefmod]'		=> 'Gegner Verteidigungs-Multiplikator,int|?Multipliziert den vom Spieler zugefügten Schaden mit Faktor und zieht den finalen Wert vom Gegner ab.'
		,'mount_data[mountbuff][activate]'			=> 'Aktivieren bei|?Mögliche Werte: roundstart,offense,defense Für Mehrfachauswahl Begriffe durch Komma getrennt schreiben'

		,'Mine+Stall,title'
		,'mount_data[mine_canenter]'	=> 'Kann Mine betreten (in %),int'
		//,'mount_data[mine_candie]'		=> 'Kann in Mine sterben (in %),int'
		,'mount_data[mine_cansave]'		=> 'Kann Spieler aus Mine retten (in %),int'
		//,'mount_data[mine_t_msg_prev]'	=> 'mine_tethermsg-Vorschau:,preview,mine_tethermsg'
		//,'mount_data[mine_tethermsg]'	=> 'Nachricht zum Anbinden`ndes Tieres vor der Mine,88'
		//,'mount_data[mine_d_msg_prev]'	=> 'mine_deathmsg-Vorschau:,preview,mine_deathmsg'
		//,'mount_data[mine_deathmsg]'	=> 'Nachricht bei Tod in der Mine,88'
		//,'mount_data[mine_s_msg_prev]'	=> 'mine_savemsg-Vorschau:,preview,mine_savemsg'
		//,'mount_data[mine_savemsg]'		=> 'Nachricht wenn der Spieler`ngerettet wurde,88'
		,'mount_data[mine_bag]'			=> 'Zusätzlicher Stauraum für Minenausbeute,int'

		,'stables'			=> 'Ställe,divider'
		,'mount_data[mountproduct]'		=> 'Namensteil für Wurst und Dung'
		,'mount_data[mount_sausage]'	=> 'Goldwert des Tieres nach Schlachtung'
	);
	output(form_header('su_mounts.php?op=save'));
	showform($arr_form,$arr_mount_data);
	output(form_footer());
}
elseif($_GET['op'] == 'del') 
{
	$id = (int)$_GET['id'];
	
	addnav("Zurück zum Editor","su_mounts.php");

	$mount = db_get('SELECT * FROM mounts WHERE mountid='.$id);
	
	output('`bTier löschen`b`n`n');
	
	if(null == $mount) {
		output('FEHLER: Mount mit ID '.$id.' nicht gefunden!');
	}
	else {
		
		$lnk = 'su_mounts.php?op=del&id='.$id.'&del=1';
		
		if(!isset($_GET['del'])) {
			// ANzahl der Spieler mit diesem Mount
			list($count) = db_fetch_array(db_query('SELECT COUNT(hashorse) FROM accounts WHERE hashorse='.$id));
			
			if($count > 0) {
				$res = db_query('SELECT mountname,mountid FROM mounts WHERE mountid<>'.$id);
				$mounts['0'] = 'Nichts';
				while($m = db_fetch_array($res)) {
					$mounts[$m[1]] = $m[0];
				}
				$mountsel = '';
				$mountsel = form_sel_options($mounts,false,true);
				
				output('`$Achtung: '.$count.' Spieler besitzen noch ein '.$mount['mountname'].'`$! Was soll mit diesen geschehen?`n`n
						'.form_header($lnk).'
							Durch ein <select name="replaceby">
								'.$mountsel.'
							</select>`n
							<input type="submit" value="Ersetzen und Löschen!">
						</form>');
			}
			else {
				output('`$Soll '.$mount['mountname'].'`$ wirklich gelöscht werden?`n`n'
						.create_lnk('Ja!',$lnk));
			}
		}
		else {
			
			db_query('DELETE FROM mounts WHERE mountid='.$id.' LIMIT 1');
						
			if(db_affected_rows()) {
			
				// Wenn Ersetzung
				if(!empty($_POST['replaceby'])) {
					db_query('UPDATE accounts SET hashorse='.(int)$_POST['replacedby'].' WHERE hashorse='.$id);
					output('`0'.db_affected_rows().'`0 '.$mount['mountname'].'`0 wurden ersetzt!`n`n');
				}
										
				output($mount['mountname'].'`0 wurde gelöscht!`n`n');
				systemlog('Mount '.$mount['mountname'].'`0 gelöscht!',$session['user']['acctid']);
			}
			else {
				output('Löschen fehlgeschlagen!');
			}
			
		}
		
		
	}
	
}

page_footer();
?>