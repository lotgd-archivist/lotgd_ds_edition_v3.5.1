<?php

$ARR_PETITION_KATS = array(0=>"Keine","Fehler","Fragen","Fragen zu Bans","Vorschläge","Unterhaltsames","Diskussionen","Hilferuf","Namensänderung","Lobpreisungen & Schleimereien");

function petitionmail($subject,$body,$petition,$from,$seen=0,$to=0,$messageid=0)
{
	$subject=str_replace("\n",'',$subject);
	$subject=str_replace("`n",'',$subject);

	$sql = 'INSERT INTO petitionmail (petitionid,messageid,msgfrom,msgto,subject,body,sent,seen) VALUES ('.(int)$petition.','.(int)$messageid.','.(int)$from.','.(int)$to.',"'.db_real_escape_string($subject).'","'.db_real_escape_string($body).'",now(),"'.$seen.'")';
	db_query($sql);
	$sql = 'UPDATE petitions SET status=IF(status=2,1,status), lastact=NOW() WHERE petitionid="'.(int)$petition.'"';
	db_query($sql);
}

function systemmail($to,$subject,$body,$from=0,$noemail=false)
{
	global $session;

    $ret_id = 0;

    $fromline = '';
	$subject=str_replace("\n",'',$subject);
	$subject=str_replace('`n','',$subject);

	$sql = 'SELECT prefs,emailaddress,login FROM accounts WHERE acctid="'.intval($to).'"';
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	db_free_result($result);

	$prefs = utf8_unserialize($row['prefs']);

	if ($prefs['dirtyemail']==false)
	{
		$subject=soap($subject);
		$body=soap($body);
	}

	if($from > 0) {
		user_set_stats( array('mailsent'=>'mailsent+1'), $from );
		user_set_stats( array('mailreceived'=>'mailreceived+1'), $to );
	}

	if($to > 0) {
		user_update(
			array
			(
				'httpreq_flag'=>array('sql'=>true,'value'=>'httpreq_flag | '.HTTPREQ_FLAG_NEW_MAIL),
                'newmail' => 1
			),
			(int)$to
		);
	}

    $body_mail = $body;
    $crypted = 0;

    if($from > 0) {
        $body_mail = CCrypt::mc_encrypt($body);
        $crypted = 1;
    }

	$sql = 'INSERT INTO mail (msgfrom,msgto,subject,body,sent,ip,crypted) VALUES ('.(int)$from.','.(int)$to.',"'.db_real_escape_string($subject).'","'.db_real_escape_string($body_mail).'",now(),"'.$_SERVER['REMOTE_ADDR'].'","'.intval($crypted).'")';

	if(getsetting('forward_yom_admin_enable',1)){
		if($prefs['forward_yom_to_superuser']>0){
			$sql_get_user = "SELECT acctid,login FROM accounts WHERE acctid=".(int)$prefs['forward_yom_to_superuser'];
			$db_res_forward_yom_su = db_query($sql_get_user);
			if(mb_substr_count($subject,'[SU_FWD:') > getsetting('forward_yom_maximum_depth',3)){
				$bool_cycle_check = false;
			}else{
				$bool_cycle_check = true;
			}

			if($bool_cycle_check && db_num_rows($db_res_forward_yom_su)>0){
				$str_body_forward = $body;
				$row_forward_yom_su = db_fetch_assoc($db_res_forward_yom_su);
				if(getsetting('forward_yom_keep_copy',1) == 0){
					$sql = false;
				}else{
					$body = '`^`bDiese Nachricht wurde an '.$row_forward_yom_su['login'].' automatisch weitergeleitet`b`0`n`n'.$body;
				}
				$str_body_forward = '`^`bDiese Nachricht wurde von '.$row['login'].' automatisch an dich weitergeleitet`b`0`n`n'.$str_body_forward;
				$str_subject_forward = '[SU_FWD:'.$row['login'].'] '.$subject;
				systemmail($row_forward_yom_su['acctid'],$str_subject_forward,$str_body_forward,(int)$from);
			}
		}
	}
	if($sql !== false){
		db_query($sql,false);
        $ret_id = db_insert_id(LINK);
	}

	$email=false;
	if(getsetting('emailonmail',0)) {
		if ($prefs['emailonmail'] && $from>0){
			$email=true;
		}elseif($prefs['systemmail'] && $from==0){
			$email=true;
		}
		if (!is_email($row['emailaddress'])){
			$email=false;
		}
	}

	if ($email && !$noemail)
	{
		$sql = 'SELECT name,login FROM accounts WHERE acctid='.$from;
		$result = db_query($sql);
		$row1=db_fetch_assoc($result);
		db_free_result($result);
		if ($row1['name']!='') {
			$fromline = "\nVon: ".strip_appoencode($row1['name'],3)."\n";
		}

		$body = utf8_preg_replace('/[`]n/', "\n", $body);
		$body = stripslashes($body);
		$body = strip_appoencode($body,3);
		$body = utf8_htmlspecialchars($body);

		$subject = stripslashes($subject);
		$subject = strip_appoencode($subject,3);
		$subject = utf8_htmlspecialchars($subject);

		send_mail($row['emailaddress'],'Neue Spiel-Nachricht',
            nl2br("Du hast eine neue Nachricht in ".getsetting('townname','Atrahor')." ( ".getsetting('server_address','localhost')." ) empfangen.".
		$fromline.""
		.'Betreff: '.$subject."\n"
		."Nachricht: \n".$body."\n"
		."\nDu kannst diese Meldungen in deinen Einstellungen abschalten."),
		'From: '.getsetting('gameadminemail','postmaster@localhost')
		);
	}

    return $ret_id;
}

function is_email($email)
{
    if(filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return true;
    }
    return false;
}

function secure_header($str) {
    $str = str_replace("\r", '', $str);
    $str = str_replace("\n", '', $str);
    return trim($str);
}

function send_mail_bathi($to, $subject, $message, $from = null, $namefrom = null, $nameto = null, $arr_attachments = array())
{
    if(is_null($from)){
        $from = getsetting('mail_sender_address','postmaster@localhost');
    }

    $reply = getsetting('gameadminemail','postmaster@localhost.com');

    if(!is_email($to) || !is_email($from) || !is_email($reply)) {
        debuglog("Mail Nachricht konnte nicht versendet werden - Mailer Error: E-Mail nicht valide TO: ".$to." FROM: ".$from." REPLY: ".$reply);
        return false;
    }

    if(is_null($namefrom)){
        $namefrom = getsetting('server_name','Charlie');
    }

    if(!is_null($nameto)){
        $to = $nameto.'<'.$to.'>';
    }

    #---------------------------------------------------------------

    $namefrom = secure_header($namefrom);
    $from = secure_header($from);
    $reply = secure_header($reply);
    $to = secure_header($to);
    $subject = secure_header($subject);

    #---------------------------------------------------------------

    $trenner = md5( time() );

    #---------------------------------------------------------------

    $header  = "Reply-To: " .$namefrom. "<" .$reply. ">\r\n";
    $header .= "Return-Path: ".$from. "\r\n";
    $header .= "Message-ID: <".time()."-".$from.">\r\n";
    $header .= "X-Mailer: PHP\r\n";
    $header .= "From: ".$namefrom."<".$from. ">\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed;\r\n";
    $header .= " boundary = " .$trenner;
    $header .= "\r\n\r\n";

    $content  = "This is a multi-part message in MIME format\r\n";
    $content .= "--" .$trenner. "\r\n";
    $content .= "Content-Type: text/html; charset=UTF-8\r\n";
    $content .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $content .= $message. "\r\n\r\n";

    if(is_array($arr_attachments) && count($arr_attachments)>0)
    {
        foreach ($arr_attachments as $arr_file)
        {
            //$arr_file['encoding'] 	= 'base64';
            //encoding wird zur zeit ignoriert TODO?
            $content .= "--" .$trenner. "\r\n";
            $content .= "Content-Type: ".$arr_file['type']."; name=\"" .$arr_file['name']. "\"\r\n";
            $content .= "Content-Transfer-Encoding: base64\r\n";
            $content .= "Content-Disposition: attachment; filename=\"" .$arr_file['name']. "\"\r\n\r\n";
            $content .= chunk_split(base64_encode($arr_file['content']), 76, "\n");
            $content .= "\r\n";
        }
    }

    if(!mail($to, utf8_htmlspecialchars($subject), $content, $header, ' -oi -f '.$from)){
        debuglog("Mail Nachricht konnte nicht versendet werden - Mailer Error: Send error");
        return false;
    }else{
        return true;
    }
}

function send_mail($to, $subject, $message, $headers = '', $additional_parameters = null, $from = null, $namefrom = null, $nameto = null, $arr_attachments = array(),$bool_html = true)
{
    return send_mail_bathi($to, $subject, $message, $from, $namefrom, $nameto, $arr_attachments);
}

?>
