<?php
/**
* login.php:	Script zum Login in das Spiel.
* @author LOGD-Core, modified by Drachenserver-Team
* @version DS-E V/2
*/

require_once('common.php');

//session_regenerate_id(true);

// Wenn Name gegeben ( = Loginversuch )
if ( !empty($_POST['name']) )
{
	// Wenn wir bereits eingeloggt sind
    if ($session['loggedin'])
    {
        redirect("badnav.php");
    }

	$str_name = $_POST['name'];
	$str_pwd = $_POST['password'];
	
	// Nach Account mit eingegebenen Daten suchen
	$arr_user = db_get('SELECT acctid,login,emailvalidation,superuser,emailaddress,lastip,uniqueid,banoverride,password FROM accounts
                            WHERE   login = "'.db_real_escape_string(stripslashes($str_name)).'"
                                AND locked=0');

    if(isset($arr_user['password']))
    {
        if(!CCrypt::verify_password_hash($str_pwd,$arr_user['password']))
        {
            $arr_user = null;
        }
    }
    else
    {
        $arr_user = null;
    }


	// Kein Account mit diesen Daten gefunden!
	if($arr_user === null)
	{
		$session['message']="`4Fehler: Login-Daten waren ungültig.`0 ";

        $arr_user = db_get("SELECT acctid FROM accounts WHERE login='".db_real_escape_string($str_name)."' LIMIT 1");

		// Account gefunden
		if (is_array($arr_user))
		{
				// Faillog hinzufügen
                $_POST['password'] = 'annonym';
				$sql = "INSERT INTO faillog VALUES (0,now(),'".db_real_escape_string(utf8_serialize($_POST))."','".$_SERVER['REMOTE_ADDR']."','".$arr_user['acctid']."','".$_COOKIE['lgi']."')";
				db_query($sql);
				
				//Superusergruppen holen
				$arr_su_groups = access_control::get_superuser_sugroups();

				$sql = "SELECT faillog.*,accounts.superuser,name,login FROM faillog INNER JOIN accounts ON accounts.acctid=faillog.acctid WHERE ip='".$_SERVER['REMOTE_ADDR']."' AND date>'".date("Y-m-d H:i:s",strtotime(date("r")."-1 day"))."'";
				$result2 = db_query($sql);
				$c=0;
				$alert="";
				$su=false;
				while ($row2=db_fetch_assoc($result2))
				{
					//Wenn der fehlgeschlagene Login auf einem Superuseraccount stattfindet, 
					//dann erhöhe die Fail-zahl zusätzlich um 1
					if (in_array($row2['superuser'],$arr_su_groups))
					{
						$c+=1;
						$su=true;
					}
					$c+=1;
					$alert.="`3{$row2['date']}`7: Failed attempt from `&{$row2['ip']}`7 [`3{$row2['id']}`7] to log on to `^{$row2['login']}`7 ({$row2['name']}`7)`n";
				}
				if ($c>=20)
				{					
					// 10 failed attempts for superuser, 20 for regular user
					if ($su)
					{
						systemlog('`$Loginversuche auf Superuseraccount:<br />'.$alert);
						//Superuseraccounts werden NICHT im Forum gebannt.
						Registry::set('ci_no_ban',true);
					}
					//end if($su)
					
					setban(0,'Automatischer Systembann: Zu viele fehlgeschlagene Loginversuche.',date("Y-m-d H:i:s",strtotime(date("r")."+".($c*3)." hours")),$_SERVER['REMOTE_ADDR']);
					Registry::remove('ci_no_ban');

					systemlog('Systembann aufgrund zu vieler fehlgeschlagener Logins, IP: '.$_SERVER['REMOTE_ADDR']);
				}
				//end if($c>=20)
		}
		// end wenn account mit diesem namen vorhanden





        redirect("index.php");
	}	// END Login failed
	

	$session['banoverride'] = (bool)$arr_user['banoverride'];

	// Filter auf diesen Account?
	checkban($arr_user['login'], $arr_user['lastip'], $arr_user['uniqueid'], $arr_user['emailaddress']);

	// Check, ob Email schon bestätigt wurde
	if ($arr_user['emailvalidation']!="" && mb_substr($arr_user['emailvalidation'],0,1)!="x")
	{
		unset($arr_user);
		Atrahor::clearSession();
		$session['message']="`4Fehler: Du musst deine E-Mail Adresse bestätigen lassen, bevor du dich einloggen kannst.";
		redirect("index.php");
	}

	// Anzahl der eingeloggten Spieler ermitteln
    $result = db_fetch_assoc(db_query("SELECT COUNT(*) AS onlinecount FROM accounts WHERE locked=0 AND ".user_get_online() ));
	$onlinecount = $result['onlinecount'];

	// Auf max. Useranzahl checken
	if ($onlinecount>getsetting("maxonline",10) && getsetting("maxonline",10)!=0 && isset($arr_user['superuser']) && $arr_user['superuser']==0)
	{
		unset($arr_user);
		Atrahor::clearSession();
		$session['message']="`4Fehler: Der Server ist voll.`0";
		redirect("index.php");
	}

	
	// Vollständige Userdaten in Session laden
	// user_load($arr_user['acctid']);
    if(!isset($arr_user['acctid']))$arr_user['acctid']=0;
	$Char = new CCharacter( $arr_user['acctid'], true );
	
	// Auf Wartungsmodus checken
	if(!$access_control->su_check(access_control::SU_RIGHT_WARTUNG) && getsetting('wartung',0)) {

		Atrahor::clearSession();
		$session['message']="`4Fehler: `^Der Server befindet sich derzeit im Wartungsmodus.`n
								Die Administration nimmt vermutlich gerade wichtige Änderungen
								am System vor. Bitte warte, bis der Server wieder offen ist!`0";
		redirect("index.php");

	}

	$session['loggedin']=true;

	// Stats
	$arr_laststats = user_get_stats('logintime,onlinetime,comments_rp,comments_rp_ges');
	// Wenn kein korrekter Logout
	if($arr_laststats['logintime'] > 0) {
		// Jetzt updaten
		$int_timeout = (int)getsetting('LOGINTIMEOUT',900) * 0.1;
		$int_timediff = min(strtotime($session['user']['laston']) + $int_timeout,time()) - $arr_laststats['logintime'];
		$arr_laststats['onlinetime'] = $arr_laststats['onlinetime'] + $int_timediff;
	}

	// RP-Kommentare
	{
		$int_don = round($arr_laststats['comments_rp'] * (float)getsetting('rpdon_dpcomment','0'));
        $arr_aei['rprating'] = 1;
		if($int_don > 0) {
			systemmail($session['user']['acctid'],'`b`qBelohnung für dein Rollenspiel!`0`b',
													'`ySei gegrüßt `q'.$session['user']['name'].'`y!`n`n
													Wir möchten Dir für Deine rege Beteiligung am Rollenspiel dieses Servers herzlich danken.
													Als kleines Präsent erhältst Du `q'.$int_don.'`y Donationpoints.`n`n
													Liebe Grüße`nDein '.getsetting('teamname','Drachenserver-Team'));
			$session['user']['donation'] += $int_don;
			debuglog($int_don.' DP für Rollenspiel ('.$arr_laststats['comments_rp'].' Kommentare)');
			$arr_laststats['comments_rp_ges'] += $arr_laststats['comments_rp'];
			$arr_laststats['comments_rp'] = 0;
		}

	}

	// Aktualisiere Laston
	$session['user']['laston'] = date('Y-m-d H:i:s');
	
	$arr_laststats['logintime'] = time();
	user_set_stats($arr_laststats);
	// END Stats
	require_once(LIB_PATH.'browser.lib.php');
	browser_set( $arr_user['acctid']);
	// Wenn wir bereits eingeloggt sind
	if ($session['user']['loggedin'] || $session['user']['restatlocation'] == USER_RESTATLOC_TIMEOUT)
	{
		debuglog('Login nach Timeout (restorepage: '.$session['user']['restorepage'].', '.($session['user']['maxhitpoints']).'LP, '.($session['user']['charm']).'CH, '.($session['user']['gems']).'+'.$session['user']['gemsinbank'].'Gems)',0,true);
		$session['user']['loggedin'] = true;
		$session['user']['restatlocation'] = 0;
		//saveuser(); << saveuser wird doch bei redirect gemacht
		
		if($session['user']['imprisoned']!=0)
		{
			if($session['user']['prangerdays']>0){
				redirect("pranger.php");
			}
			else {
				redirect("prison.php");
			}
		}
		else{
			if( empty($session['user']['restorepage']) ){//wenn restorepage leer ist, dann ab zum DP
				debuglog("leere restorepage beim login");
				redirect("village.php");
			}
			else{
				saveuser();
				header("Location: {$session['user']['restorepage']}");
				exit();
			}
		}
		exit();
	}

	$session['user']['loggedin'] = true;

    if(getsetting('logdnet',1) == 1){
        if (!LOCAL_TESTSERVER)
        {
            $url=getsetting('logdnetserver','http://lotgd.net/').'logdnet.php?addy='.URLEncode(getsetting('server_address','http://www.atrahor.de')).'&desc='.URLEncode(getsetting('serverdesc','')).'&version='.URLEncode(GAME_VERSION);
            if(function_exists('curl_init'))
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_exec($ch);
                curl_close($ch);
            }
            elseif (ini_get('allow_url_fopen') == 'On')
            {
                file($url);
            }
        }
    }

	//Schreibe debugeintrag, der User hat sich eingelogged
	debuglog('Login (Loc '.$session['user']['location'].', Restatloc '.$session['user']['restatlocation'].'): '.($session['user']['maxhitpoints']).'LP, '.($session['user']['charm']).'CH, '.($session['user']['gems']).'+'.$session['user']['gemsinbank'].'Gems',0,true);

	//Anfragen-Sternchen für Superuser
	if($access_control->su_check(access_control::SU_RIGHT_PETITION))
	{
		$arr_petids=db_get_all('SELECT petitionid FROM petitions','petitionid'); //nur vorhandene Anfragen sind interessant
		$arr_aei = user_get_aei('seenpetitions');
		$session['petitions']=utf8_unserialize($arr_aei['seenpetitions']);
		if(!is_array($session['petitions']))
		{
			$session['petitions']=array();
		}
		foreach($session['petitions'] as $key=>$val)
		{ //gelöschte Anfragen auch aus dem Gesehen-Status löschen
			if(!array_key_exists($key,$arr_petids))
			{
				unset($session['petitions'][$key]);
				$aei_needupdate=true;
			}
		}
		if($aei_needupdate)
		{
			user_set_aei(array('seenpetitions'=>db_real_escape_string(utf8_serialize($session['petitions']))));
		}
	} //END Anfragen-Sternchen

	// Je nach Logoutort weiterleiten
	$location = $session['user']['location'];
	$session['user']['location']=0;


	switch($location) {
		case USER_LOC_FIELDS: // In den Feldern
			if($session['user']['alive'])
			{
				redirect("dorftor.php");
			}
			else
			{
				redirect("shades.php");
			}
		break;

		case USER_LOC_INN:	// In der Taverne
			redirect("inn.php?op=strolldown");
		break;

		case USER_LOC_HOUSE:	// Im Haus

			$hausnr=($session['user']['restatlocation']);
			$session['user']['restatlocation'] = 0;
			redirect('inside_houses.php?act=rest&id='.$hausnr.'&getnd=1');
		break;

		case USER_LOC_PRISON:	// Kerker
			if($session['user']['prangerdays']>0){
				redirect("pranger.php");
			}
			else {
				redirect("prison.php");
			}
		break;

		case USER_LOC_VACATION: // war im Urlaubsmodus
			redirect("vacation.php?op=return");
		break;

		default:	// Timeout
			//saveuser(); << saveuser wird doch bei redirect gemacht
			if( empty($session['user']['restorepage']) ){//wenn restorepage leer ist, dann ab zum DP
				debuglog("leere restorepage beim login");
				redirect("village.php");
			}
			else{
				//header("Location: {$session['user']['restorepage']}");
				debuglog('Redirect login.php ~> badnav.php - Default-Wert der location - sollte nicht auftreten!');
				redirect('badnav.php');
				exit();
			}
		break;
	}
	// END Ort feststellen

}	// END if Name gegeben

// LOGOUT
else if ($_GET['op']=="logout")
{

    if ($session['user']['loggedin'])
    {

		$int_loc = (int)$_GET['loc'];
		$int_restatloc = (int)$_GET['restatloc'];

		// Stats
		user_set_stats( array('onlinetime'=>'onlinetime + IF(logintime>0,(UNIX_TIMESTAMP(NOW())-logintime),0)','logintime'=>0) );

		// END Stats

        $sql = "SELECT bufflist FROM accounts WHERE acctid=".$session['user']['acctid'];
        $result = db_query($sql);
        $row = db_fetch_assoc($result);

        if ($row['bufflist'])
        {
            $row['bufflist']=utf8_unserialize($row['bufflist']);
            if ($row['bufflist']['dodo'] != "")
            {
                unset($row['bufflist']['dodo']);
                $row['bufflist']=utf8_serialize($row['bufflist']);

				$row['bufflist'] = db_real_escape_string($row['bufflist']);
				
				user_update(
					array
					(
						'bufflist'=>$row['bufflist']
					),
					$session['user']['acctid']
				);
            }
        }

        debuglog('Logout (Loc '.$int_loc.', Restatloc '.$int_restatloc.'): '.($session['user']['maxhitpoints']).'LP, '.($session['user']['charm']).'CH, '.($session['user']['gems']).'+'.$session['user']['gemsinbank'].'Gems');
        
        user_update(
			array
			(
				'location'=>$int_loc,
				'loggedin'=>0,
				'restatlocation'=>$int_restatloc,
				'output'=>'Ausgeloggt in Loc '.$int_loc.', Restatloc '.$int_restatloc.' am '.date('d.m.Y H:i:s')
			),
			$session['user']['acctid']
		);
    }

	if($session['user']['acctid']==getsetting('demouser_acctid',0))
	{ //Endtext für Demo-Zugang
		Atrahor::clearSession();
		redirect('demouser.php?op=logout',false, false);
	}
	Atrahor::clearSession();
	redirect('index.php',false, false);
}

// If you enter an empty username, don't just say oops.. do something useful.
Atrahor::clearSession();
$session['message']="`4Fehler: Die Login-Daten waren fehlerhaft.`0";
redirect("index.php");
?>