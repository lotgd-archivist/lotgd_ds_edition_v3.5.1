<?php
/**
* su_logs.php: Logviewer (Faillog, Debuglog, Systemlog) mit Filter- und Suchoptionen
* @author talion <t@ssilo.de>
* @version DS-E V/2
*/

$str_filename = basename(__FILE__);
require_once('common.php');

// Grundlegende Anzeigenvars
$str_logname = '';
$str_tableheader = '';

// Suchmaske

$int_y_to = date('Y');
$int_m_to = date('m');
$int_d_to = date('d');
$int_y_from = $int_y_to;
$int_m_from = $int_m_to - 1;
if($int_m_from <= 0) {
	$int_m_from += 12;
	$int_y_from--;
}
$int_d_from = 1;

$arr_form = array(
					'account_id'		=>'AccountID ODER Login,int',
					'target_id'			=>'ZielID ODER Login,int',
					'message'			=>'Stichwortsuche in Log-Nachricht',
					'from_d'			=>'VON Tag,enum_order,1,31',
					'from_m'			=>'VON Monat,enum_order,1,12',
					'from_y'			=>'VON Jahr,enum_order,'.($int_y_to-1).','.($int_y_to+1),
					'to_d'				=>'BIS Tag,enum_order,1,31',
					'to_m'				=>'BIS Monat,enum_order,1,12',
					'to_y'				=>'BIS Jahr,enum_order,'.($int_y_to-1).','.($int_y_to+1),
					);
$arr_data = array(
					'account_id'		=> $_REQUEST['account_id'],
					'target_id'			=> $_REQUEST['target_id'],
					'message'			=> $_REQUEST['message'],
					'from_d'			=> (empty($_REQUEST['from_d']) ? $int_d_from : (int)$_REQUEST['from_d']),
					'from_m'			=> (empty($_REQUEST['from_m']) ? $int_m_from : (int)$_REQUEST['from_m']),
					'from_y'			=> (empty($_REQUEST['from_y']) ? $int_y_from : (int)$_REQUEST['from_y']),
					'to_d'				=> (empty($_REQUEST['to_d']) ? $int_d_to : (int)$_REQUEST['to_d']),
					'to_m'				=> (empty($_REQUEST['to_m']) ? $int_m_to : (int)$_REQUEST['to_m']),
					'to_y'				=> (empty($_REQUEST['to_y']) ? $int_y_to : (int)$_REQUEST['to_y']),
					'max_results'		=> (empty($_REQUEST['max_results']) ? 25 : (int)$_REQUEST['max_results'])
					);
					
if( (int)$arr_data['account_id'] == 0 && !empty($arr_data['account_id']) ) {
	$arr_tmp = db_fetch_assoc(db_query('SELECT acctid FROM accounts WHERE login="'.$arr_data['account_id'].'" LIMIT 1'));
	if($arr_tmp['acctid'] > 0) {
		$arr_data['account_id'] = $arr_tmp['acctid'];
	}
	else {
		$arr_data['account_id'] = 0;
	}	
}
if( (int)$arr_data['target_id'] == 0  && !empty($arr_data['target_id']) ) {
	$arr_tmp = db_fetch_assoc(db_query('SELECT acctid FROM accounts WHERE login="'.$arr_data['target_id'].'" LIMIT 1'));
	if($arr_tmp['acctid'] > 0) {
		$arr_data['target_id'] = $arr_tmp['acctid'];
	}
	else {
		$arr_data['target_id'] = 0;
	}	
}

// Welche Art von Logs wollen wir haben? 
$str_type = (empty($_REQUEST['type']) ? 'syslog' : $_REQUEST['type']);

// Gleich auf Rechte checken, Vars zuweisen
switch($str_type) {
	case 'debuglog':
		$access_control->su_check(access_control::SU_RIGHT_DEBUGLOG,true);
		$str_logname = 'Debuglog';
		$str_tableheader = '<th>Datum</th>
							<th>Name</th>
							<th>Nachricht</th>
							<th>Ziel</th>';
							
		$str_date_from = '';
		$str_date_to = '';
		if($arr_data['from_d'] > 0 && $arr_data['from_m'] > 0 && $arr_data['from_y'] > 0) {
			$str_date_from = $arr_data['from_y'].'-'.$arr_data['from_m'].'-'.$arr_data['from_d'].' 00:00:00';
		}
		if($arr_data['to_d'] > 0 && $arr_data['to_m'] > 0 && $arr_data['to_y'] > 0) {
			$str_date_to = $arr_data['to_y'].'-'.$arr_data['to_m'].'-'.$arr_data['to_d'].' 23:59:59';
		}
		
		$arr_form['id'] = 'Unique ID';
		$arr_form['ip'] = 'IP-Adresse';
		$arr_data['id'] = $_REQUEST['id'];
		$arr_data['ip'] = $_REQUEST['ip'];
		
		$str_where = '		WHERE 		1  	
										'.($arr_data['account_id'] > 0 	? 'AND d.actor = "'.$arr_data['account_id'].'"' : '').'
										'.($arr_data['target_id'] > 0 	? 'AND d.target = "'.$arr_data['target_id'].'"' : '').'
										'.(!empty($arr_data['message'])	? 'AND d.message LIKE "%'.$arr_data['message'].'%"' : '').'
										'.(!empty($str_date_from)		? 'AND d.date >= "'.$str_date_from.'"' : '').'
										'.(!empty($str_date_to)			? 'AND d.date <= "'.$str_date_to.'"' : '').'
										'.(!empty($arr_data['ip'])		? 'AND d.ip LIKE "%'.$arr_data['ip'].'%"' : '').'
										'.(!empty($arr_data['id'])		? 'AND d.uid LIKE "%'.$arr_data['id'].'%"' : '');
										
		$str_count_sql = 'SELECT COUNT(*) AS c FROM debuglog d '.$str_where;
						
		$str_data_sql = '	SELECT 		d.*, a_actor.login AS actorname, a_target.login AS targetname, a_actor.acctid AS actorid
							FROM		debuglog d
							LEFT JOIN	accounts a_actor 	ON a_actor.acctid = d.actor
							LEFT JOIN	accounts a_target 	ON a_target.acctid = d.target
							'.$str_where.'
							ORDER BY 	d.date DESC
							LIMIT 		'
						;
	break;
	
	case 'faillog':
		$access_control->su_check(access_control::SU_RIGHT_FAILLOG,true);
		$str_logname = 'Fehlgeschlagene Logins';
		$str_tableheader = '<th>Datum</th>
							<th>Login</th>
							<th>PW gegeben`n(JS&nbsp;deaktiviert?)</th>
							<th>IP</th>
							<th>ID</th>';
							
		$str_date_from = '';
		$str_date_to = '';
		if($arr_data['from_d'] > 0 && $arr_data['from_m'] > 0 && $arr_data['from_y'] > 0) {
			$str_date_from = $arr_data['from_y'].'-'.$arr_data['from_m'].'-'.$arr_data['from_d'].' 00:00:00';
		}
		if($arr_data['to_d'] > 0 && $arr_data['to_m'] > 0 && $arr_data['to_y'] > 0) {
			$str_date_to = $arr_data['to_y'].'-'.$arr_data['to_m'].'-'.$arr_data['to_d'].' 23:59:59';
		}
		
		unset($arr_form['target_id']);
		unset($arr_form['message']);
		$arr_form['id'] = 'Unique ID';
		$arr_form['ip'] = 'IP-Adresse';
		$arr_data['id'] = $_REQUEST['id'];
		$arr_data['ip'] = $_REQUEST['ip'];
		
		$str_where = 	'WHERE 		1	
										'.($arr_data['account_id'] > 0 	? 'AND f.acctid = "'.$arr_data['account_id'].'"' : '').'
										'.(!empty($arr_data['ip'])		? 'AND f.ip LIKE "%'.$arr_data['ip'].'%"' : '').'
										'.(!empty($arr_data['id'])		? 'AND f.id LIKE "%'.$arr_data['id'].'%"' : '').'
										'.(!empty($str_date_from)		? 'AND f.date >= "'.$str_date_from.'"' : '').'
										'.(!empty($str_date_to)			? 'AND f.date <= "'.$str_date_to.'"' : '');
		
		$str_count_sql = 'SELECT COUNT(*) AS c FROM faillog f '.$str_where;
		
		$str_data_sql = '	SELECT 		f.*, a.login AS actorname, a.acctid AS actorid
							FROM		faillog f
							LEFT JOIN	accounts a 	USING( acctid )
							'.$str_where.'
							ORDER BY 	f.date DESC
							LIMIT 		'
						;
						
	break;
	
	// Standard: Syslog
	default:
		$access_control->su_check(access_control::SU_RIGHT_SYSLOG,true);
		$str_logname = 'Systemlog';
		$str_type = 'syslog';
		
		$str_tableheader = '<th>Datum</th>
							<th>Name</th>
							<th>Nachricht</th>
							<th>Ziel</th>';
		
		$str_date_from = '';
		$str_date_to = '';
		if($arr_data['from_d'] > 0 && $arr_data['from_m'] > 0 && $arr_data['from_y'] > 0) {
			$str_date_from = $arr_data['from_y'].'-'.$arr_data['from_m'].'-'.$arr_data['from_d'].' 00:00:00';
		}
		if($arr_data['to_d'] > 0 && $arr_data['to_m'] > 0 && $arr_data['to_y'] > 0) {
			$str_date_to = $arr_data['to_y'].'-'.$arr_data['to_m'].'-'.$arr_data['to_d'].' 23:59:59';
		}		
				
		$str_where = 'WHERE 		1	
										'.($arr_data['account_id'] > 0 	? 'AND s.actor = "'.$arr_data['account_id'].'"' : '').'
										'.($arr_data['target_id'] > 0 	? 'AND s.target = "'.$arr_data['target_id'].'"' : '').'
										'.(!empty($arr_data['message'])	? 'AND s.message LIKE "%'.$arr_data['message'].'%"' : '').'
										'.(!empty($str_date_from)		? 'AND s.date >= "'.$str_date_from.'"' : '').'
										'.(!empty($str_date_to)			? 'AND s.date <= "'.$str_date_to.'"' : '');
										
		$str_count_sql = 'SELECT COUNT(*) AS c FROM syslog s '.$str_where;
		
		$str_data_sql = '	SELECT 		s.*, a_actor.login AS actorname, a_target.login AS targetname, a_actor.acctid AS actorid
							FROM		syslog s
							LEFT JOIN	accounts a_actor 	ON a_actor.acctid = s.actor
							LEFT JOIN	accounts a_target 	ON a_target.acctid = s.target
							'.$str_where.'
							ORDER BY 	s.date DESC
							LIMIT 		'
						;
		
	break;
}

page_header('Logviewer - '.$str_logname);

output('`c`b`&Logviewer - '.$str_logname.'`0`b`c`n');

// Grundnavi erstellen
addnav('Zurück');

if( !empty($_REQUEST['ret']) ) {
	addnav('Zum Ausgangspunkt',urldecode($_REQUEST['ret']));
}	

grotto_nav();

addnav('Aktionen');
addnav('Aktuellste 100 / Start',$str_filename.'?op=search&type='.$str_type.'&max_results=100');
// END Grundnavi erstellen

// Evtl. Fehler / Erfolgsmeldungen anzeigen
if($session['message'] != '') {
	output('`n`b'.$session['message'].'`b`n`n');
	$session['message'] = '';
}
// END Evtl. Fehler / Erfolgsmeldungen anzeigen

function show_log_search () {
	
	global $str_filename,$arr_form,$arr_data,$str_type;
	
	$str_out = '';
	
	$str_lnk = $str_filename.'?op=search&type='.$str_type.'&ret='.urlencode($_REQUEST['ret']);
		
	addnav('',$str_lnk);
				
	$str_out .= '<form method="POST" action="'.utf8_htmlentities($str_lnk).'">';
				
	// Suchmaske zeigen
	$str_out .= generateform($arr_form,$arr_data,false,'Suchen!');
			
	$str_out .= '</form><hr />';
	
	return($str_out);
	
}

// MAIN SWITCH
$str_op = ($_REQUEST['op'] ? $_REQUEST['op'] : '');

switch($str_op) {
	
	// Suchergebisse
	case 'search':	
		
		$str_out .= show_log_search();
				
		$str_out .= '`n<table cellpadding="2" cellspacing="1" align="center">
						<tr class="trhead">'.$str_tableheader.'</tr>';
		
		$str_tr_class = 'trlight';
		
		$str_baselnk = $str_filename . '?op=search&type='.$str_type;
		foreach($arr_data as $key => $val) {
			$str_baselnk .= (!empty($val) ? '&'.$key.'='.urlencode($val) : '');
		}
		
		$arr_pages = page_nav($str_baselnk,$str_count_sql,$arr_data['max_results']);
		$str_data_sql .= $arr_pages['limit'];
		
		$res = db_query($str_data_sql);
		
		if(db_num_rows($res) == 0) {
			
			$str_out .= '`iKeine Ergebnisse gefunden!`i';
			
		}
		
		addnav('Account-Aktionen');
		
		// Links zum Useredit nur einmal anbieten
		$bool_useredit_actor = false;
		$bool_useredit_target = false;
		
		// Ergebnisse zeigen
		while($l = db_fetch_assoc($res)) {
			
			if($arr_data['account_id'] && !empty($l['actorname']) && !$bool_useredit_actor) {
				if($access_control->su_check(access_control::SU_RIGHT_EDITORUSER)) {
					addnav('Usereditor: '.$l['actorname'], 'user.php?op=edit&userid='.$arr_data['account_id']);
				}
				addnav($l['actorname'].' als ZielID',$str_filename.'?op=search&type='.$str_type.'&target_id='.$arr_data['account_id'].'&ret='.urlencode($_REQUEST['ret']) );
				$bool_useredit_actor = true;
			}
			if($arr_data['target_id'] && !empty($l['targetname']) && !$bool_useredit_target) {
				if($access_control->su_check(access_control::SU_RIGHT_EDITORUSER)) {
					addnav('Usereditor: '.$l['targetname'], 'user.php?op=edit&userid='.$arr_data['target_id']);
				}
				addnav($l['targetname'].' als AccountID',$str_filename.'?op=search&type='.$str_type.'&account_id='.$arr_data['target_id'].'&ret='.urlencode($_REQUEST['ret']));
				$bool_useredit_target = true;
			}
			
			$str_out .= '<tr class="'.$str_tr_class.'">';
			
			if($str_type == 'syslog' || $str_type == 'debuglog') {
			
				$str_showfull_addon = '';
				if(mb_strlen($l['message']) > 200) {
					if($_REQUEST['show_full'] != $l['id']) {
						$str_baselnk .= '&show_full='.$l['id'];
						$l['message'] = mb_substr($l['message'],0,197).'...';
					}
					else {
						$str_baselnk .= '&show_full=0';
					}
					$str_baselnk .= '&page='.$arr_pages['page'];
					addnav('',$str_baselnk);
					// create_lnk nicht verwendbar wegen Anker!
					$str_showfull_addon = '`^[ <a href="'.$str_baselnk.'#l'.$l['id'].'"><b>'.($_REQUEST['show_full'] != $l['id'] ? '+' : '-').'</b></a> ]';
				}
			}
			
			switch($str_type) {
			
				case 'debuglog':
					
					$str_lnk_accountid = $str_filename.'?op=search&type='.$str_type.'&account_id='.$l['actorid'].'&ret='.urlencode($_REQUEST['ret']);
					$str_lnk_targetid = $str_filename.'?op=search&type='.$str_type.'&account_id='.$l['target'].'&ret='.urlencode($_REQUEST['ret']);
					
					$l['targetname'] = (empty($l['targetname']) && $l['target'] > 0 ? '`$`igelöscht`i`0' : $l['targetname']);
					$l['actorname'] = (empty($l['actorname']) && $l['actor'] > 0 ? '`$`igelöscht`i`0' : $l['actorname']);
					
					$str_out .= '<td nowrap>'.date('d.m.Y H:i:s',strtotime($l['date'])).'</td>';
					$str_out .= '<td>'.$l['actorname'].($arr_data['account_id'] != $l['actorid'] ? '`n'.create_lnk('Accountsuche',$str_lnk_accountid) : '').'</td>';
					$str_out .= '<td><a name="l'.$l['id'].'"></a>'.$l['message'].$str_showfull_addon.(!empty($l['ip']) ? '`nIP:'.$l['ip'] : '').(!empty($l['uid']) ? ', ID:'.$l['uid'] : '').'</td>';
					$str_out .= '<td>'.$l['targetname'].($l['target'] > 0 ? '`n'.create_lnk('Accountsuche',$str_lnk_targetid) : '').'</td>';
				
				break;

				
				case 'syslog':
				
					$str_lnk_accountid = $str_filename.'?op=search&type='.$str_type.'&account_id='.$l['actorid'].'&ret='.urlencode($_REQUEST['ret']);
					$str_lnk_targetid = $str_filename.'?op=search&type='.$str_type.'&account_id='.$l['target'].'&ret='.urlencode($_REQUEST['ret']);
					
					$l['targetname'] = (empty($l['targetname']) && $l['target'] > 0 ? '`$`igelöscht`i`0' : $l['targetname']);
					$l['actorname'] = (empty($l['actorname']) && $l['actor'] > 0 ? '`$`igelöscht`i`0' : $l['actorname']);
					
					$str_out .= '<td  nowrap>'.date('d.m.Y H:i:s',strtotime($l['date'])).'</td>';
					$str_out .= '<td align="center">'.(empty($l['actor']) ? '`i`r'.getsetting('server_name','Charlie').'`0`i' : $l['actorname'].($arr_data['account_id'] != $l['actorid'] ? '`n'.create_lnk('Accountsuche',$str_lnk_accountid) : '') ).'</td>';
					$str_out .= '<td><a name="l'.$l['id'].'"></a>'.$l['message'].$str_showfull_addon.'`0</td>';
					$str_out .= '<td>'.$l['targetname'].($l['target'] > 0 ? '`n'.create_lnk('Accountsuche',$str_lnk_targetid) : '').'</td>';
					
				break;
				
				case 'faillog':
					
					$str_lnk_accountid = $str_filename.'?op=search&type='.$str_type.'&account_id='.$l['actorid'].'&ret='.urlencode($_REQUEST['ret']);
					$str_lnk_id = $str_filename.'?op=search&type='.$str_type.'&id='.$l['id'].'&ret='.urlencode($_REQUEST['ret']);
					$str_lnk_ip = $str_filename.'?op=search&type='.$str_type.'&ip='.$l['ip'].'&ret='.urlencode($_REQUEST['ret']);
					
					$l['actorname'] = (empty($l['actorname']) && $l['actorid'] > 0 ? '`$`igelöscht`i`0' : $l['actorname']);
					
					$arr_post = utf8_unserialize($l['post']);
					
					$str_out .= '<td  nowrap>'.date('d.m.Y H:i:s',strtotime($l['date'])).'</td>';
					$str_out .= '<td align="center">'.$l['actorname'].($arr_data['account_id'] != $l['actorid'] ? '`n'.create_lnk('Accountsuche',$str_lnk_accountid) : '').'</td>';
					$str_out .= '<td>'.(!empty($arr_post['password']) ? '`$Ja`0' : '`@Nein`0').'</td>';
					$str_out .= '<td>'.$l['ip'].($arr_data['ip'] != $l['ip'] ? '`n'.create_lnk('Suche IP',$str_lnk_ip) : '').'</td>';
					$str_out .= '<td>'.$l['id'].($arr_data['id'] != $l['id'] ? '`n'.create_lnk('Suche ID',$str_lnk_id) : '').'</td>';
					
				break;
				
			}
			
			$str_out .= '</tr>';
		
		}
		
		$str_out .= '</table>';
		// END Ergebnisse zeigen
		
		output($str_out, true);
			
	break;
	// END Suchergebnisse
		
	// Hm..
	default:
		redirect($str_filename.'?op=search&type='.$str_type.'&max_results=100'.'&ret='.urlencode($_REQUEST['ret']));
		//output( show_log_search(), true );
	break;
}


page_footer();
?>
