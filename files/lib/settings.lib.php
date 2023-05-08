<?php
/**
 * settings.lib.php: Funktionen zur allgemeinen Verwaltung der Spieleinstellungen und textuellen Gestaltung
 * @author LOGD-Core / Drachenserver-Team
 * @version DS-E V/2
*/

/**
 * Speichert Spieleinstellung in Datenbank
 *
 * @param string ID der Einstellung
 * @param mixed Einstellungswert
 * @return bool true, falls Einstellung übernommen; false, wenn nicht bzw. keine Änderung
 */
function savesetting($settingname,$value)
{
	global $settings;
	//Skip overhead of calling function
	if(!is_array($settings))
	{
		loadsettings();
	}
	if (!isset($settings[$settingname]))
	{
		$sql = 'INSERT INTO settings (setting,value) VALUES ("'.db_real_escape_string($settingname).'","'.db_real_escape_string($value).'")';
	}
	else
	{
		$sql = 'UPDATE settings SET value="'.db_real_escape_string($value).'" WHERE setting="'.db_real_escape_string($settingname).'"';
	}

	db_query($sql);
	$settings[$settingname]=$value;
	if (db_affected_rows()>0)
	{		
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * Lädt Inhalt der Spieleinstellungs-Tabelle aus Datenbank
 *
 */
function loadsettings()
{
	global $settings;
	//as this seems to be a common complaint, examine the execution path of this function,
	//it will only load the settings once per page hit, in subsequent calls to this function,
	//$settings will be an array, thus this function will do nothing.

	if ($settings === false || !is_array($settings))
	{
		$settings=array();
		$sql = 'SELECT * FROM settings';
		$result = db_query($sql);

		while($row = db_fetch_assoc($result))
		{
			$settings[$row['setting']] = $row['value'];
		}
		db_free_result($result);
	}
}

/**
 * Ermittelt einzelne Spieleinstellung, lädt diese aus DB falls noch nicht geschehen.
 * Wenn Einstellung nicht vorhanden, wird sie neu angelegt
 *
 * @param string ID der Einstellung
 * @param string Standardwert, falls Einstellung noch nicht vorhanden
 * @return mixed Inhalt der Einstellung
 */
function getsetting($settingname,$default)
{
	global $settings;
	//Skip overhead of calling function
	if(!is_array($settings))
	{
		loadsettings();
	}

	if (!isset($settings[$settingname]))
	{
		savesetting($settingname,$default);
		return $default;
	}
	else
	{
		if (trim($settings[$settingname])=='')
		{
			$settings[$settingname]=$default;
		}
		return $settings[$settingname];
	}
}

/**
 * Get an extended string from the database
 *
 * @param string $str_text_id contains the id of the text
 * @param string $str_category contains the optional category, set to "*" to search in any category
 * @param bool $bool_get_as_array Set true to receive an array, else receive just the text
 * @param bool $bool_show_sulnk Set true to show an editlink for superuser
 * @param string $str_subcategory contains the optional subcategory, set to "*" to search in any subcategory
 * @param string $str_tag contains an optional tag that describes the text you're looking for
 * @return mixed - array or string (false if an error ocurred)
 */
function get_extended_text($str_text_id = false,$str_category = '*',$bool_get_as_array = false,$bool_show_sulnk = true,$str_subcategory='*',$str_tag='*')
{
	global $session, $access_control;
	if($str_text_id == false || empty($str_text_id))
	{
		return '';
	}
	if($str_category == false || is_null($str_category) || empty($str_category))
	{
		$str_category = 'standard';
	}

	//Sanitize
	$str_text_id = db_real_escape_string(stripslashes($str_text_id));
	$str_category = db_real_escape_string(stripslashes($str_category));
	$str_subcategory = db_real_escape_string(stripslashes($str_subcategory));
	$str_tag = db_real_escape_string(stripslashes($str_tag));

	$str_sql_get_text = 'SELECT id,text,category,author,subcategory,tags FROM extended_text WHERE 1';
	if($str_text_id != '*')
	{
		$str_sql_get_text.= ' AND id="'.$str_text_id.'"';
	}
	if($str_category != '*')
	{
		$str_sql_get_text.= ' AND category = "'.$str_category.'"';
	}
	if($str_subcategory != '*')
	{
		$str_sql_get_text.= ' AND subcategory = "'.$str_subcategory.'"';
	}
	if($str_tag != '*')
	{
		$str_sql_get_text.= ' AND tags LIKE "%'.$str_tag.'%"';
	}

	$str_sql_get_text.= ' ORDER BY id ASC ';

	$db_result = db_query($str_sql_get_text);
	$int_count = db_num_rows($db_result);
	if($int_count==0)
	{
		return '';
	}

	$arr_return_text = array();
	$arr_one = array();

	while($arr_piece = db_fetch_assoc($db_result)) {

		$arr_piece['text'] = stripslashes($arr_piece['text']);

		//Replacing all PHP Sourcecode
		$str_temp_text = $arr_piece['text'];
		utf8_preg_match_all('/{{(.*)}}/sU',$str_temp_text,$arr_matches,PREG_SET_ORDER);
		foreach($arr_matches as $arr_match)
		{
			$arr_match[1] = eval(utf8_eval($arr_match[1]));
			$str_temp_text = str_replace($arr_match[0],$arr_match[1],$str_temp_text);
		}

		$arr_piece['text'] = $str_temp_text;

		if($access_control->su_check(access_control::SU_RIGHT_EDITOREXTTXT) && $bool_get_as_array == false && $bool_show_sulnk)
		{
			$str_link = 'su_extended_text.php?op=edit&id='.$arr_piece['id'];
			addnav('',$str_link);
			$str_html_edit = '[ <span class="colWhiteBlack "><a href="'.$str_link.'">Ändern</a></span> ]<br clear=all />';
			$str_temp_text = $str_html_edit.$str_temp_text;
		}

		$arr_return_text[] = $arr_piece;
		$arr_one = $arr_piece;
	}

	if($bool_get_as_array == true)
	{
		if($int_count == 1) {
			return($arr_one);
		}

		return $arr_return_text;
	}
	else
	{
		return $str_temp_text;
	}
}

/**
 * Write an extended text to the database
 *
 * @param string $str_text_id contains the id of the text
 * @param string $str_text contains the text
 * @param string $str_category contains the optional category
 * @param string $str_author contains the author of the text
 * @param string $str_subcategory contains the optional subcategory
 * @param string $str_tags contains the optional tags
 * @return bool
 */
function set_extended_text($str_text_id = false,$str_text = false, $str_category = 'standard', $str_author = '', $str_subcategory = '', $str_tags = '')
{
	if($str_text_id == false || empty($str_text_id) || $str_text == false || empty($str_text))
	{
		return false;
	}
	if($str_category == false || is_null($str_category) || empty($str_category))
	{
		$str_category = 'standard';
	}

	//Sanitize
	$str_text_id = db_real_escape_string(stripslashes($str_text_id));
	$str_text = db_real_escape_string(stripslashes($str_text));
	$str_category = db_real_escape_string(stripslashes($str_category));
	$str_subcategory = db_real_escape_string(stripslashes($str_subcategory));
	$str_author = db_real_escape_string(stripslashes($str_author));
	$str_tags = db_real_escape_string(stripslashes($str_tags));

	$result = get_extended_text($str_text_id,'*');
	$str_sql_get_text = '';
	if($result != '')
	{
		$str_sql_get_text = 'UPDATE extended_text SET text="'.$str_text.'",category="'.$str_category.'",subcategory="'.$str_subcategory.'",tags="'.$str_tags.'",author="'.$str_author.'" WHERE id="'.$str_text_id.'"';
	}
	else
	{
		$str_sql_get_text = 'INSERT INTO extended_text (id,text,category,subcategory,tags,author) VALUES("'.$str_text_id.'","'.$str_text.'","'.$str_category.'","'.$str_subcategory.'","'.$str_tags.'","'.$str_author.'")';
	}
	$db_result = db_query($str_sql_get_text);
	return ($db_result==false)?false:true;
}

/**
 * Bereinigt Datenbank, entfernt überflüssige / veraltete Inhalte (s. setnewday)
 * Wird zweimal täglich erledigt, das sollte reichen
 *
 * @author LOGD-Core, modded by talion
*/
function cleanup () {

    global $access_control;

	$int_exp_content = (int)getsetting('expirecontent',180);

	if ($int_exp_content > 0)
	{
		$exp_offset = date('Y-m-d H:i:s',time() - ($int_exp_content*86400) );
		$sql = 'DELETE FROM commentary WHERE postdate<\''.$exp_offset.'\'';
		db_query($sql);
		$sql = 'DELETE FROM news WHERE newsdate<\''.$exp_offset.'\'';
		db_query($sql);

		$sql = 'DELETE FROM debuglog WHERE date <\''.$exp_offset.'\'';
		db_query($sql);

		$sql = 'DELETE FROM syslog WHERE date <\''.$exp_offset.'\'';
		db_query($sql);

		$sql = 'DELETE FROM faillog WHERE date <\''.$exp_offset.'\'';
		db_query($sql);
		
	}

	$sql = 'DELETE FROM boards WHERE expire < "'.date('Y-m-d H:i:s').'"';
	db_query($sql);

	//Lösche Mails außer ungelesene und archivierte Mails

    $result_suact=db_query('SELECT acctid FROM accounts WHERE superuser IN (7,'.implode(',',$access_control->get_superuser_sugroups()).') ');

    $str_id_list='0';
    while($row_su=db_fetch_assoc($result_suact))
    {
        $str_id_list .= ','.$row_su['acctid'];
    }

	$sql = 'DELETE FROM mail WHERE (
	(sent<\''.date('Y-m-d H:i:s',time()-(getsetting('oldmail',14)*86400)).'\' AND msgto NOT IN('.$str_id_list.'))
	OR sent<\''.date('Y-m-d H:i:s',time()-(getsetting('modoldmail',21)*86400)).'\'
	) AND archived = 0 AND seen=1';
	db_query($sql);

	$sql = 'SELECT id FROM bans WHERE (banexpire!="0000-00-00" AND banexpire<"'.date('Y-m-d').'")';
	$res = db_query($sql);

	while($b = db_fetch_assoc($res)) {

		$mixed_result = delban($b['id']);

		if(is_array($mixed_result)) {
			if(sizeof($mixed_result) > 0) {
				$str_affected = '`nBetroffen: ';

				foreach ($mixed_result as $a) {
					$str_affected .= $a['login'].'; ';
				}
				systemlog('`@Ban ID '.$b['id'].' abgelaufen!'.$str_affected);
			}
			else {
				systemlog('`@Ban ID '.$b['id'].' abgelaufen! Keine Accounts betroffen.');
			}

		}
		elseif(false === $mixed_result) {
			systemlog('`4Fehler bei Automatiklöschung des Ban ID '.$b['id'].'!');
		}
	}


	// Herrenlose Items löschen
	$res = item_list_get(' (owner=0 AND newday_del>0)');
	$ids = '-1';
	while($i = db_fetch_assoc($res)) {
		$ids .= ','.$i['id'];
	}
	item_delete(' id IN ('.$ids.') ');
	// END Herrenlose Items löschen

	if(getsetting('expire_accounts',1)==1 && LOCAL_TESTSERVER == false)
	{
		$vacation = getsetting('expirevacationacct',365)-getsetting('expire_sendmail_before',5);
		$old = getsetting('expireoldacct',45)-getsetting('expire_sendmail_before',5);
		$new = getsetting('expirenewacct',10);
		$trash = getsetting('expiretrashacct',1);

		// Abgelaufene Accounts: Warnungen verschicken
		$sql = 'SELECT acctid,emailaddress,login FROM accounts WHERE (1=0 '
		.($vacation>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($vacation*86400))."\")\n":"")
		.($old>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($old*86400))."\" AND location !=".USER_LOC_VACATION.")\n":"")
		.") AND (emailaddress!='' AND activated!=".USER_ACTIVATED_SENTNOTICE." AND activated!=".USER_ACTIVATED_MUTE.")";
		$result = db_query($sql);

		while($row = db_fetch_assoc($result))
		{
			if( is_email($row['emailaddress']) ) {
				//Mailtext aus dem extended Text Editor holen
				$str_mail_body = get_extended_text('mail_account_expiration');

				//Einige Tags darin ersetzen
				$str_mail_body = str_replace('%user_name%',$row['login'],$str_mail_body);
				$str_mail_body = str_replace('%town_name%',getsetting('townname','Atrahor'),$str_mail_body);
				$str_mail_body = str_replace('%server_url%',getsetting('server_address','localhost'),$str_mail_body);
				$str_mail_body = str_replace('%amount_dp%',getsetting('expire_donationpoints',300),$str_mail_body);
				$str_mail_body = str_replace('%days_until_deleted%',getsetting('expire_sendmail_before',5),$str_mail_body);
				$str_mail_body = str_replace('%team_name%',getsetting('teamname','Drachenserver-Team'),$str_mail_body);

				//Mail versenden
				$bool_result = send_mail($row['emailaddress'], getsetting('townname','Atrahor').' - Account verfällt',$str_mail_body,
				'Reply-To: '.getsetting('gameadminemail','postmaster@localhost.com')
				);

				//User kennzeichnen dass er angemailt wurde + Donationpoints geben
				user_update(
					array
					(
						'donation'=>array('sql'=>true,'value'=>'donation+'.getsetting('expire_donationpoints',300)),
						'activated'=>USER_ACTIVATED_SENTNOTICE
					),
					(int)$row['acctid']
				);

				//Mail an den User
				systemmail($row['acctid'],'Willkommen zurück','Hallo '.$row['login'].'!`nWir freuen uns, dass Du mal wieder bei uns vorbei gesehen hast!`nAls Dankeschön haben wir Dir '.getsetting('expire_donationpoints',300).' Donationpoints gutgeschrieben.`nViel Spass beim Ausgeben und Spielen!`n`nDein '.getsetting('teamname','Drachenserver-Team'));

				systemlog('`^Account ID '.$row['acctid'].', Login '.$row['login'].' wegen Inaktivität '.($bool_result?'erfolgreich':'erfolglos').' angemailt!`0',0,$row['acctid']);
			}
		}

		// Inaktive Accounts löschen
		$vacation+=getsetting('expire_sendmail_before',5);
		$old+=getsetting('expire_sendmail_before',5);

		$sql = 'SELECT acctid,login FROM accounts WHERE superuser=0 AND (1=0 '
		.($vacation>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($vacation*86400))."\")\n":"")
		.($old>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($old*86400))."\" AND location !=".USER_LOC_VACATION.")\n":"")
		.($new>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-($new*86400))."\" AND level=1 AND dragonkills=0)\n":'')
		.($trash>0?"OR (laston < \"".date('Y-m-d H:i:s',time()-(($trash+1)*86400))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":'')
		.')';

		$res = db_query($sql);

		while($a = db_fetch_assoc($res)) {

			if( user_delete($a['acctid']) ) {

				systemlog('`$Account ID '.$a['acctid'].', Login '.$a['login'].' wegen Inaktivität gelöscht!`0');

			}
		}
	}
	
	// Abgelaufene Sessions ausloggen; dabei diese als abgelaufen markieren, damit sie nicht in den Feldern aufwachen
/*
	user_update(
		array
		(
			'loggedin'=>0,
			'restatlocation'=>USER_RESTATLOC_TIMEOUT,
			'where'=>'loggedin=1 AND superuser=0 AND !('.user_get_online().') '
		)
	);
*/

}

?>