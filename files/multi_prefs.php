<?php
$DONT_OVERWRITE_NAV 	= true;
$BOOL_JS_HTTP_REQUEST 	= true;
require_once('common.php');
if (!$session['user']['loggedin']) exit;

popup_header('Multiverwaltung',true);
$acctid 	= intval($session['user']['acctid']);

function outmsg($msg, $success = false){
    output('`n`n`c`'.( $success ? '2' : '4' ).$msg.'`c`0`n`n');
}

if(!empty($_POST['multi_login']) && !empty($_POST['multi_password'])){
    $login  = $_POST['multi_login'];
    $pass	= $_POST['multi_password'];
    if( $session['multi_fail'][$login] >= 3 ){
        outmsg('Zu viele Fehlversuche für diesen Account!');
    }
    else{
        $res = db_query('SELECT acctid, login, name, password  FROM accounts WHERE login LIKE "'.db_real_escape_string($login).'" AND acctid<>"'.intval($acctid).'"');
        if( db_num_rows($res) == 1 ){
            $r = db_fetch_assoc($res);
            if(CCrypt::verify_password_hash($pass,$r['password']))
            {
                $ret = db_query('INSERT INTO account_multi (master,slave) VALUES ("'.intval($acctid).'","'.intval($r['acctid']).'")');
                if( !$ret ){
                    outmsg('Fehler beim Eintragen des Multis!');
                }
            }
            else{
                if(!isset($session['multi_fail'][$login]) ){
                    $session['multi_fail'][$login]=0;
                }
                $session['multi_fail'][$login]++;
                if($session['multi_fail'][$login] == 3){
                    debuglog("`43 Fehlversuche bei Multi-Login @ login: {$login}");
                }
                outmsg('Ungültige Zugangsdaten!');
            }
        }
        else{
            if( !isset($session['multi_fail'][$login]) ){
                $session['multi_fail'][$login]=0;
            }
            $session['multi_fail'][$login]++;
            if($session['multi_fail'][$login] == 3){
                debuglog("`43 Fehlversuche bei Multi-Login @ login: {$login}");
            }
            outmsg('Dieser Account kann nicht hinzugefügt werden!');
        }
    }



}

if(!empty($_GET['delid'])){
    $delid		= intval($_GET['delid']);
    $res = db_query('DELETE FROM account_multi WHERE (master="'.intval($acctid).'" AND slave="'.intval($delid).'") OR (master="'.intval($delid).'" AND slave="'.intval($acctid).'")');
    outmsg('Multi gelöscht!',true);
}

$arr_multis = array();
$preflink	= 'prefs.php';
$res = db_query(' SELECT DISTINCT a.name, a.acctid, a.login
					FROM account_multi am
					JOIN accounts a
					ON a.acctid<>"'.intval($acctid).'" AND (a.acctid=am.master OR a.acctid=am.slave)
					WHERE am.master="'.intval($acctid).'" OR am.slave="'.intval($acctid).'"');

while($r = db_fetch_assoc($res)){
    array_push($arr_multis, $r);
}

$form_layout = array(
    ",title",
    "multi_login" 		=> "Login,text|?Loginname des Multis",
    "multi_password" 	=> "Passwort,password|?Passwort des Multis"
);
$str_out = '`b<a href="' . $preflink . '">Zurück zum Profil</a>`n
			<form method="POST" action="multi_prefs.php">
			'.generateform($form_layout, array()).'
			</form>
			<br><br>
			`bEingetragene Multis`b:
			<div id="multi_list">';
foreach( $arr_multis as $multi ){
    $str_out .= "<div id='multi".$multi['acctid']."'><a href='multi_prefs.php?delid=".$multi['acctid']."'><img border='0' src='./images/icons/petition_delete.png' title='Löschen' alt='Löschen'></a>".$multi['name']."</div>";
}
$str_out .= '</div>';
output($str_out);
popup_footer();
?>