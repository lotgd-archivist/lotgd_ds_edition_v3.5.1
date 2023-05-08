<?php
/**
 * Diese Datei erstellt ein Interface über das die Spieler Anfragen an das Team senden können
 * @version DS V3
 */
require_once('common.php');
$str_filename = basename(__FILE__);
popup_header( ($_GET['op'] == 'help' || $_GET['op']=='faq') ? "FAQ" : "Hilfe / Melden",true);

if($_GET['op'] == 'help' || $_GET['op']=='faq') {
    $str_default = 'faq_start';
    $str_page = (empty($_GET['page']) ? $str_default : $_GET['page']);
    $str_txt = get_extended_text($str_page,'rules_faq',false,false);
    if (false !== $str_txt) {
        output($str_txt,true);
    }
    else {
        output('`$Seite konnte nicht gefunden werden!');
    }
}
else
{
	$demouser_acctid=(int)getsetting('demouser_acctid',0);
	if($_GET['op'] == 'submit')
	{
		if (count($_POST)>0){
			if(empty($_POST['description'])) {
				$str_output .='`$Das Nichts ist zweifelsohne eine erhabene Tatsache, nur wird die Administration mit einer leeren Anfrage
						wohl weniger als nichts anfangen können.`n`0';
			}
			elseif( (empty($_POST['email']) || !is_email(stripslashes($_POST['email']))) && (!$session['user']['loggedin'] || $session['user']['acctid']==$demouser_acctid)) {
				$str_output .='`$Wie willst du denn eine Antwort auf diese Anfrage erhalten, wenn du keine gültige EMail-Adresse angibst?`n`0';
			}
			else
			{
				if($_GET['subop'] == 'petition')
				{
					$p = $session['user']['password'];
					unset($session['user']['password']);

						if(!$session['user']['loggedin'] || $session['user']['acctid']==$demouser_acctid)
						{
							$sql = 'SELECT login,acctid,uniqueid,lastip FROM accounts WHERE lastip = "'.db_real_escape_string($session['lastip']).'" OR uniqueid = "'.db_real_escape_string($session['uniqueid']).'" ORDER BY login, acctid';
							$res = db_query($sql);
							$sec_info = '';
							while($r = db_fetch_assoc($res) ) {
								$sec_info .= '`n'.$r['login'].' (AcctID '.$r['acctid'].', IP '.$r['lastip'].', ID '.$r['uniqueid'].')';
							}
						}else{
                            $_POST['email'] = $session['user']['emailaddress'];
                            $_POST['charname'] = $session['user']['login'];
                        }

						db_insert('petitions',
									array(
											'author' 	=> ($session['user']['acctid']==$demouser_acctid ? 0 : (int)$session['user']['acctid']),
											'date'		=> array('sql'=>true,'value'=>'NOW()'),
											'body'		=> $_POST['description'],
											'email'		=> $_POST['email'],
											'charname'	=> $_POST['charname'],
											'pageinfo'	=> output_array($session,"Session:"),
											'lastact'	=> array('sql'=>true,'value'=>'NOW()'),
											'IP'		=> $session['lastip'],
											'ID'		=> $session['uniqueid'],
											'connected'	=> $sec_info,
											'kat'		=> (int)$_POST['kat']
										)
									);
						$session['user']['password']=$p;
						if($session['user']['acctid']>0) {
							user_set_stats(array('petitions'=>'petitions+1'));
						}
						$str_output .= "Deine Anfrage wurde an das Team gesendet. Bitte hab etwas Geduld, Antworten und Reaktionen können eine Weile dauern, wir bemühen uns aber alles so schnell wie möglich zu beantworten.";
                    output('`n`n'.$str_output.'`n`n',true);
                    popup_footer(false);
                }
			}
            output('`n`n'.$str_output,true);
		}
	}

	$arr_data_anfrage = array('charname'=> isset($_POST['charname']) ? stripslashes($_POST['charname']) : $session['user']['login'],
						'email'=> isset($_POST['email']) ? stripslashes($_POST['email']) : $session['user']['emailaddress'],
						'description'=>stripslashes($_POST['description'])
						);

    if(!$session['user']['loggedin'] || $session['user']['acctid']==$demouser_acctid)
    {
        $arr_form_anfrage = array('charname'=>'Name deiner Spielfigur:',
            'email'=>'Deine E-Mail Adresse:',
            'description'=>'Beschreibe dein Problem:`n,textarea,35,8');
    }else{
        $arr_form_anfrage = array(
            'description'=>'Beschreibe dein Problem:`n,textarea,35,8');
    }

	$str_output_petition .= '`c`b`&Hilfe / Melden`&`b`c<hr>';
	$str_output_petition .= '
							<form action="petition.php?op=submit&subop=petition" method="POST">';
	$str_output_petition .= generateform($arr_form_anfrage,$arr_data_anfrage,false,'Absenden!');
	$str_output_petition .= '</form>';

	if($session['user']['loggedin'] == 1)
	{
		$statuses=array(0=>"`bUngelesen`b","Gelesen","Abgearbeitet");

		$str_sql_my_petitions ='

		SELECT 		p.petitionid,p.prio,p.date, p.status,p.lastact,p.kat,p.p_for,p.body
					FROM 		petitions p
					WHERE		p.author = '.$session['user']['acctid'].'
					ORDER BY 	p.petitionid DESC';

		$db_res = db_query($str_sql_my_petitions,false);
		$int_count = db_num_rows($db_res);

		//Wenn der Nutzer anfragen gestellt hat, dann stelle den Bearbeitungsstatus dar
		if($int_count>0)
		{
			$str_output_petition .= '
			<hr>
			`c`bDeine Anfragen`b`c
			<table width="100%">
				<tr class="trhead">
					<td>Anfragedatum</td>
					<td>Status</td>
					<td>Zuordnung</td>
				</tr>
			';
			while($arr_result = db_fetch_assoc($db_res))
			{
				$str_class = $str_class == 'trlight'?'trdark':'trlight';
				$str_output_petition .= '
				<tr class="'.$str_class.'">
					<td>'.date('d.m.y H:i:s',strtotime($arr_result['date'])).'</td>
					<td>'.$statuses[$arr_result['status']].'</td>
					<td>'.(!empty($arr_result['p_for']) ? $arr_result['p_for'].'`0' : '').'</td>
				</tr>
				<tr class="'.$str_class.'">
					<td colspan="3">'.plu_mi('petition_'.$arr_result['petitionid'],0,false).' Anfragetext
											<div id="'.plu_mi_unique_id('petition_'.$arr_result['petitionid']).'" style="display:none;padding: 11px; border-top:1px solid #888;">
												'.utf8_htmlentities($arr_result['body']).'
											</div>
					</td>
				</tr>';
			}
			$str_output_petition .= '
			</table>';
		}
	}

	$arr_mainform = array(
	'petition'=>'Quelltext für eine Anfrage an die Administration,html',
	);
	$arr_maindata = array(
	'petition'=>appoencode($str_output_petition),

	);
	showform($arr_mainform,$arr_maindata,true);
}
popup_footer(false);
?>
