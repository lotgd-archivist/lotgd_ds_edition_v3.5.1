<?php
/**
 * su_usergroups.php: Tool zum Einstellen der Superuser-Berechtigungen
 * @author talion
 * @version DS-E V/2
*/

require_once('common.php');
page_header('Superusereditor');
$str_filename = basename(__FILE__);

//Auf dieser Seite wird nicht mit dem gecachten Objekt gearbeitet
$access_control = new access_control();

// Gruppen aus Settings laden
$arr_grps = user_get_sugroups(-1,false);
if(!is_array($arr_grps)) {
	$arr_grps = array();
}

// Wenn Standard-Spielergruppe aus irgendeinem Grund nicht mehr vorhanden, wiederherstellen
if(!isset($arr_grps[0])) {
	$arr_grps[0] = array(0 => 'Spieler', 1=>'Spieler', 2 => array(), 3 => 1);
	savesetting('sugroups',utf8_serialize($arr_grps));
}

ksort($arr_grps);


addnav('Zurück');
grotto_nav();
addnav('Aktionen');

output("`c`b`&Superusereditor`0`b`c");

if($session['message'] != '') {
	output('`n`b'.$session['message'].'`b`n`n');
	$session['message'] = '';
}

// MAIN SWITCH
$op = ($_REQUEST['op'] ? $_REQUEST['op'] : '');

switch($op) {
	
	case 'edit_single_right':
	{		
		switch ($_REQUEST['subop'])
		{
			case '':
			{
				addnav('Zurück',$str_filename);
				
				$str_out = get_title('`yEinzelne Userrechte editieren').'`tWähle das zu editierende Userrecht aus`0`n`n';
				foreach(access_control::$ARR_SURIGHTS as $key => $arr_right)
				{
					if(is_numeric($key))
					{
						$str_out .= create_lnk($key." : ".$arr_right['desc'],$str_filename.'?op=edit_single_right&subop=edit&right='.$key).'<br />';
					}
					else 
					{
						$str_out .= $arr_right.'<br />';
					}
				}
				output($str_out);
				break;
			}
			case 'edit':
			{
				addnav('Zurück',$str_filename.'?op=edit_single_right');
				
				$int_right = (int)$_REQUEST['right'];
				$str_out = '';
				
				$arr_form['tools'] = 'Auswahl:,viewonly';
				$arr_data['tools'] = '
								[ <a href="#" onClick="for(i=0;i<document.getElementsByTagName(\'input\').length;i++) {document.getElementsByTagName(\'input\')[i].checked=true;}">Alle markieren!</a> ]&nbsp;
								[ <a href="#" onClick="for(i=0;i<document.getElementsByTagName(\'input\').length;i++) {document.getElementsByTagName(\'input\')[i].checked=false;}"> Alle demarkieren!</a> ]';
				
				$arr_form['right'] = 'Das zu ändernde Recht,hidden';
				$arr_data['right'] = $int_right;
				foreach($arr_grps as $key => $arr_group)				
				{
					$arr_form['group_id['.$key.']'] = $arr_group[0].',checkbox,1';
					$arr_data['group_id['.$key.']'] = $arr_group[2][$int_right];
				}
				
				$str_out .= form_header($str_filename.'?op=edit_single_right&subop=save');
				$str_out .= generateform($arr_form,$arr_data);
				$str_out .= form_footer();
				
				output($str_out);
				
				break;
			}
			case 'save':
			{
				$str_out = '';
				$int_right = (int)$_REQUEST['right'];
				
				if(!array_key_exists($int_right,access_control::$ARR_SURIGHTS))
				{
					$session['message'] = '`$Das Recht existiert nicht';
					redirect($str_filename.'?op=edit_single_right');
				}
				
				foreach($arr_grps as $key => $arr_group)
				{
					//Recht mit übergeben
					$arr_grps[$key][2][$int_right] = array_key_exists($key,(array)$_REQUEST['group_id'])? 1 : 0;					
				}
								
				savesetting( 'sugroups', (utf8_serialize($arr_grps)) );

				$session['message'] = '`@Erfolgreich gespeichert!`0';

				//Objekt leeren, es hat offensichtlich neue Informationen
				Cache::delete(Cache::CACHE_TYPE_MEMORY,'obj_access_control');

				redirect($str_filename.'?op=edit_single_right');
				
				break;		
			}
		}
		break;
	}

	case 'editgroup':

		addnav("E?Edit beenden","su_usergroups.php");

		$id = !isset($_REQUEST['id']) ? -1 : (int)$_REQUEST['id'];

		if($id > -1) {
			$arr_editgrp = user_get_sugroups($id);
			$arr_editgrp = array('',
								'name_sing'=>$arr_editgrp[0],
								'name_plur'=>$arr_editgrp[1],
								'surights'=>$arr_editgrp[2],
								'lst_show'=>$arr_editgrp[3],
								'is_superuser'=>$arr_editgrp[4]);

			$arr_editgrp_rights = $arr_editgrp['surights'];

			foreach($arr_editgrp_rights as $r=>$v) {
				$arr_editgrp['surights['.$r.']'] = $v;
			}
		}

		$str_dependence = '';

		//$surights = array('Superuser-Rechte,title');

		foreach($access_control as $r=>$v) {

			$str_dependence = '';

			// Titel
			if(is_string($v)) {
				$surights[] = $v.',title';
				$surights['tools_'.$v] = 'Auswahl:,viewonly';
				$arr_editgrp['tools_'.$v] = '
								[ <a href="#" onClick="for(i=0;i<document.getElementsByTagName(\'input\').length;i++) {document.getElementsByTagName(\'input\')[i].checked=true;}">Alle markieren!</a> ]&nbsp;
								[ <a href="#" onClick="for(i=0;i<document.getElementsByTagName(\'input\').length;i++) {document.getElementsByTagName(\'input\')[i].checked=false;}"> Alle demarkieren!</a> ]';
			}
			else {
				if(!empty($v['dependent'])) {
					$str_dependence = '`n(Abhängig von: '.$access_control[$v['dependent']]['desc'].')';
				}
				$surights['surights['.$r.']'] = ''.$v['desc'].$str_dependence.'<p>&nbsp;</p>,checkbox,1';
			}

		}

		if(0 == $id) {
			$arr_editgrp['info'] = 'Standard-Spielergruppe; alle neuangemeldeten Accounts landen automatisch in dieser Gruppe!';
		}

		$form = array('Allgemeines,title',
						'info'=>'Bemerkungen,viewonly',
						'name_sing'=>'Name Singular',
						'name_plur'=>'Name Plural',
						'lst_show'=>'In "Wer ist online?"-Liste auf Startseite gesondert aufführen?,bool',
						'is_superuser'=>'Ist das eine Superusergruppe?,bool'
						);

		$form = array_merge($form,$surights);

		$link = "su_usergroups.php?op=savegroup";

		$out .=	"<form method=\"POST\" action=\"".$link."\">";
		addnav("",$link);

		if($_GET['copy']) {
			$arr_editgrp['name_sing'] = 'Kopie '.$arr_editgrp['name_sing'];
			$id = -1;
		}
		else {
			$out .=	"<input type=\"hidden\" value=\"".$id."\" name=\"id\">";
			addnav('Kopie anlegen','su_usergroups.php?op=editgroup&id='.$id.'&copy=1');
		}

		output($out,true);
		showform($form,$arr_editgrp,false,'Speichern',6);

	break;


	// Gruppe löschen
	case 'delgroup':

		$id = (int)$_GET['id'];

		$sql = 'SELECT login FROM accounts WHERE superuser='.$id.' ORDER BY acctid';
		$res = db_query($sql);

		if(0 == $id) {
			output('`$Diese Gruppe darf nicht gelöscht werden!`n');
		}
		else {

			if(db_num_rows($res)) {

				output('`$Folgende Superuser-Accounts befinden sich noch in dieser Gruppe:`n`n');
				while($a = db_fetch_assoc($res)) {
					output('`&'.$a['login'].'`n');
				}
				output('`n`$Bitte zuerst diese Accounts einer anderen Gruppe zuordnen!');

			}
			else {

				unset($arr_grps[$id]);

				savesetting( 'sugroups', (utf8_serialize($arr_grps)) );

				$session['message'] = '`@Erfolgreich gelöscht!`0';

				redirect('su_usergroups.php');

			}
		}

	break;

	// Speichern
	case 'savegroup':

		$id = (int)$_REQUEST['id'];

		// Übersetzung der Formulardaten in numerische Array-Schlüssel
		$arr_savegrp = array(0=>$_POST['name_sing'],
								1=>$_POST['name_plur'],
								2=>user_set_surights($_POST['surights']),
								3=>$_POST['lst_show'],
								4=>$_POST['is_superuser']?true:false);

		if($id > -1) {
			systemlog('Superuser-Gruppe '.$arr_grps[$id][0].' geändert.',$session['user']['acctid']);

			$arr_grps[$id] = $arr_savegrp;
		}
		else {
			ksort($arr_grps);
			end($arr_grps);
			$int_lastkey = (int)key($arr_grps);
			$arr_grps[$int_lastkey+1] = $arr_savegrp;
		}

		savesetting( 'sugroups', (utf8_serialize($arr_grps)) );

		$session['message'] = '`@Erfolgreich gespeichert!`0';

		//Objekt leeren, es hat offensichtlich neue Informationen
		Cache::delete(Cache::CACHE_TYPE_MEMORY,'obj_access_control');

		redirect('su_usergroups.php');

	break;

	// User mit SU-Rechten ermitteln
	case 'check_su_user':

		$out = '';

		if($_GET['act'] == 'reset') {
			$int_acctid = (int)$_GET['id'];

			if($int_acctid) {

				if($int_acctid == $session['user']['acctid']) {
					$out .= '`$DAS willst du nicht wirklich ; )`0`n`n';
				}
				else {
					user_update(
						array
						(
							'surights'=>'',
							'superuser'=>0
						),
						$int_acctid
					);
					systemlog('`7SU-Rechte zurückgesetzt.',$session['user']['acctid'],$int_acctid);
					$out .= '`@AcctID '.$int_acctid.' wurde der Bürde seiner SU-Rechte enthoben!`0`n`n';
				}

			}
		}

		addnav('Zurück','su_usergroups.php');
		

		$sql = "SELECT name,acctid,superuser,surights FROM accounts WHERE ( (surights != '' AND surights != 'a:0:{}'  AND surights != '{}') OR superuser>0) ORDER BY acctid ASC";
		$res = db_query($sql);
		
		$arr_rights = array();

		$out .= '`&`bZeige User mit Superuser-Rechten:`b`n`n';
		$navout = '`bFolgende User haben SU-Rechte:`b`n`n';
		while($a = db_fetch_assoc($res)) {
			$navout .= '<a href="#'.$a['acctid'].'">`&'.$a['name'].'`0</a>`n';
			$str_grpname = '`i'.(isset($arr_grps[ $a['superuser'] ]) ? $arr_grps[ $a['superuser'] ][0] : 'Keine Gruppe').'`i';
			$out .= '<hr>`n<a name="'.$a['acctid'].'"></a>`&`b'.$a['acctid'].', '.$a['name'].'`&:`b ('.$str_grpname.')`n
					[ '
					.create_lnk('Alle Rechte abnehmen!','su_usergroups.php?op=check_su_user&act=reset&id='.$a['acctid'],true,false,'Wirklich Rechte abnehmen?')
					.' ] [ '
					.create_lnk('In Usereditor laden!','user.php?op=edit&userid='.$a['acctid'])
					.' ]`n`&';

			if($a['superuser']) {
				//$out .= '`@Darf Grotte betreten.`0`n`n';
			}

			$arr_rights = array();
			$arr_urights = utf8_unserialize(($a['surights']) );

			if(isset($arr_grps[ $a['superuser'] ])) {
				$arr_usergroup = $arr_grps[ $a['superuser'] ];

				// Einzelrechte überschreiben Gruppenrechte
				$arr_rights = $arr_usergroup[2];
				if(is_array($arr_urights)) {
					foreach($arr_urights as $key=>$r) {
						$arr_rights[$key] = ($r == 1 ? 2 : 0);
					}
				}
			}
			else {
				$arr_rights = $arr_urights;
			}

			// Ausgabe
			foreach ($arr_rights as $key => $val) {
				if($val) {
					$out .= '`^'.$access_control[$key]['desc'].'`& => `@Ja '.($val == 2 ? '(Sonderrecht)' : '').'`0`n';
				}
			}

		}

		output($navout.'<hr/>'.$out,true);


	break;

	// Standardansicht, Auswahl
	default:

		$out = '`c<table cellspacing="2" cellpadding="2"><tr class="trhead">
					<td>`bID`b</td>
					<td>`bName`b</td>
					<td>`bAktionen`b</td>
				</tr>';

		addnav('Neue Gruppe','su_usergroups.php?op=editgroup&id=-1');
		addnav('User mit Rechten','su_usergroups.php?op=check_su_user');
		addnav('Einzelnes Recht bearbeiten',$str_filename.'?op=edit_single_right');
		
		foreach($arr_grps as $id => $g) {

			$style = ($style == 'trlight' ? 'trdark' : 'trlight');
			$editlink = create_lnk('Edit','su_usergroups.php?op=editgroup&id='.$id);
			$dellink = create_lnk('Del','su_usergroups.php?op=delgroup&id='.$id,true,false,'Die Gruppe wirklich löschen?');

			$out .= '<tr class="'.$style.'">
						<td>'.$id.(0 == $id ? ' `$(Standard-Spielergruppe)`0' : '').'</td>
						<td>'.$g[0].' / '.$g[1].'`&</td>
						<td>
							[ '.$editlink.' ]
							[ `$'.$dellink.'`& ]
						</td>
					</tr>';

		}

		$out .= '</table>`c';

		output($out,true);

	break;

}

page_footer();
?>