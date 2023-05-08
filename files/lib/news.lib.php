<?php

/**
 * Fügt Eintrag zu den Spielnews hinzu
 *
 * @param string $news Nachricht
 * @param int $int_acctid AccountID; optional, Standard SpielerID
 * @param int $int_onlyuser 1=Fügt Eintrag zu den Spieler-Leistungen hinzu ohne öffentlich zu erscheinen; optional, Standard 0
 */
function addnews($news, $int_acctid = 0, $int_onlyuser = 0)
{
	global $session,$access_control;
	
	//Superusercharaktere erscheinen nicht in den News
	if($access_control->su_lvl_check(1))
	{
		return;
	}
	
	$int_acctid = (!$int_acctid ? $session['user']['acctid'] : $int_acctid);
	
	$sql = 'INSERT INTO news(newstext,newsdate,accountid,onlyuser) VALUES ("'.db_real_escape_string($news).'",NOW(),'.$int_acctid.','.(int)$int_onlyuser.')';
	db_query($sql);

}

/**
 * Fügt Eintrag zu den Straftaten hinzu
 *
 * @param string $crimes Straftat-Text
 */
function addcrimes($crimes)
{
	global $session, $access_control;
	
	//Superusercharaktere erscheinen nicht in den News
	if($access_control->su_lvl_check(1))
	{
		return;
	}
	
	$sql = 'INSERT INTO crimes(newstext,newsdate,accountid) VALUES ("'.db_real_escape_string($crimes).'",NOW(),'.$session['user']['acctid'].')';
	db_query($sql);
	$sql = 'UPDATE account_extra_info SET last_crime=NOW() WHERE acctid='.$session['user']['acctid'];
	db_query($sql);
}

/**
 * Fügt Eintrag zu den aktuellen Fällen im Gericht hinzu
 *
 * @param string $case Straftat-Text
 * @param int $suspect AccountID des Verdächtigen
 */
function addtocases($case,$suspect)
{
	global $session, $access_control;
	
	$sql = 'INSERT INTO cases(newstext,accountid,judgeid,court) VALUES ("'.db_real_escape_string($case).'",'.$suspect.','.$session['user']['acctid'].',0)';
	db_query($sql);
}

/**
 * Fügt Eintrag zu den Expeditionsnews hinzu
 *
 * @param string $news Nachricht
 */
function addnews_ddl($news)
{
	global $session, $access_control;
	
	//Superusercharaktere erscheinen nicht in den News
	if($access_control->su_lvl_check(1))
	{
		return;
	}
	
	$sql = 'INSERT INTO ddlnews(newstext,newsdate,accountid) VALUES ("'.db_real_escape_string($news).'",NOW(),'.$session['user']['acctid'].')';
	db_query($sql);
}
?>