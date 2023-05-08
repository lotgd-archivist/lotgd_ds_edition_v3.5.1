<?php
/**
* create.php:	Script zur Erstellung eines neuen Accounts
* @author LOGD-Core, modified by Drachenserver-Team
* @version DS-E V/2
*/

/*in newday.php oben einfügen:
//Demo-Account rauswerfen 
if($session['user']['acctid']==getsetting('demouser_acctid','0') && $session['user']['age']>0)
{
	$sql = "UPDATE `accounts` 
	SET `location`='0',`loggedin`='0',`restatlocation`='0',`output`='Ausgeloggt am ".date('d.m.Y H:i:s')."'
	WHERE `acctid`=".$session['user']['acctid'];
	db_query($sql);

	Atrahor::clearSession();
	redirect('demouser.php?op=logout');
}

Interaktionen wie Edelsteinversand unterbinden
Tauben einschränken
Anfragen wie im ausgeloggten Zustand

*/
require_once('common.php');

page_header( getsetting('townname','Atrahor').' - Registratur' );

function show_created_screen ($str_name,$str_password) {

	output("<form action='login.php' method='POST' name='loginform'>
			<input name='name' value='".$str_name."' type='hidden'>
			<input name='password' value='".$str_password."' type='hidden'>
			<input type='submit' class='button' value='Hier klicken zum Einloggen!'>
			</form>`n`n

			Dieser Demo-Account verfällt durch Ausloggen bzw Timeout, oder spätestens zum neuen Spieltag.",true);
}

function show_registration_text()
{
}

addnav('Startseite','index.php'.($_GET['r']>0?'?r='.intval($_GET['r']):''),false,false,false,false);

$demoacctid=intval(getsetting('demouser_acctid',0));
$sql='SELECT count(*) AS c FROM accounts WHERE '.user_get_online();
$result = db_query($sql);
$onlinecount = db_fetch_assoc($result);

if($demoacctid==0) 
//Demozugang deaktiviert
{
	output('Hier gibt es leider keinen Demo-Zugang.');
}

elseif(getsetting('maxonline',0)-$onlinecount['c']<2) 
//Server voll
{
	output(get_title('`$Server voll!`0').'Im Moment ist die maximal mögliche Zahl an Spielern online. Bitte versuche es später nochmal.');
}

elseif(user_get_online($demoacctid)===true) 
//Demozugang in Benutzung
{
	output(get_title('`$Pech gehabt!`0').'Der Demo-Zugang wird bereits von jemandem genutzt. Du wirst dich gedulden müssen bis derjenige fertig ist. Oder du meldest dich gleich an, das tut auch nicht weh ;). Bitte beachte aber bei der Anmeldung unsere Regeln, besonders im Hinblick auf den Namen deines Charakters!');
	addnav('`yCharakter erstellen`0','create.php',false,false,false,false);
}

elseif($_SERVER['REMOTE_ADDR']==getsetting('demouser_last_IP',0))
//IP-Sperre
{
	if($_GET['op']!='logout')
	{
		$row['laston']=date('Y-m-d H:i:s',strtotime("-31 minutes"));
		$sql='SELECT laston FROM accounts WHERE acctid='.$demoacctid;
		$result=db_query($sql);
		if(db_num_rows($result)==1)
		{
			$row=db_fetch_assoc($result);
		}
		if($row['laston']<date('Y-m-d H:i:s',strtotime("-30 minutes")))
		{
			savesetting('demouser_last_IP','0');
		}
		output(get_title('`$IP-Sperre!`0').'Du warst doch gerade erst hier. Der Demo-Zugang ist nicht zum regulären Spielen gedacht. Bitte lass Anderen auch eine Chance!`n`n');
	}
	output(get_title('`IDeine Zeit als Besucher ist leider abgelaufen.').'
	`nNeugierig geworden?
	`nWenn es dir hier gefallen hat, dann melde dich doch gleich an!
	`nErstelle einen eigenen Charakter und entwickele ihn nach deinen Wünschen! Schalte neue Orte, Features und verfügbare Rassen frei und entdecke immer neue Herausforderungen! Individuelles Fantasy-Rollenspiel mit über 2000 Spieler-Charakteren erwartet dich!
	`n');
	addnav('`yCharakter erstellen`0','create.php?r='.$demoacctid,false,false,false,false);
}

else
{
// Filter auf PC checken
checkban();

$str_op = $_GET['op'];

switch($str_op) {

	// Standard: Charakter erstellen - Formular anzeigen
	default:

		// Wenn keine Neuanmeldungen möglich
		if (getsetting("blocknewchar","0")==1)
		{
			output(get_title('Die Anmeldungen in '.getsetting('townname','Atrahor').' sind momentan gesperrt!'));
			output("`c`tIm Moment sind leider keine Neuanmeldungen möglich. Wenn Du den Grund erfahren möchtest, so schreibe Bitte eine Anfrage.`0`c");
			page_footer();
		}

		// Anmeldeform. abgeschickt
		if ($str_op == 'create')
		{

			$str_pass1 = $_POST['pass1'];
			$str_pass2 = $_POST['pass2'];
			$str_name = $_POST['name'];
			$str_mail = $_POST['email'];

			// EMail checken
			// Emailaddy gegeben?
			if ( (getsetting("requireemail",0)==1 && is_email($str_mail)) || getsetting("requireemail",0)==0)
			{

				// Ban?
				if (checkban(false, false, false, $str_mail, 0, false))
				{
					output(get_title('`$Fehler:'));
					output("Du bist hier nicht erwünscht (E-Mail Adresse gesperrt).`c`n");
					page_footer();
					exit;
				}

				// Blacklist?
				if( check_blacklist( BLACKLIST_EMAIL, stripslashes(mb_strtolower($str_mail)) ) )
				{
					output(get_title('`$Fehler:'));
					output("Du bist hier nicht erwünscht (E-Mail Adresse verboten).`c`n");
					page_footer();
					exit;
				}

				// Auf doppelte Emailaddys checken
				if (getsetting("blockdupeemail",0)==1 && getsetting("requireemail",0)==1)
				{
					$sql = "SELECT login FROM accounts WHERE emailaddress='$str_mail'";
					$result = db_query($sql);
					if (db_num_rows($result)>0)
					{
						$blockaccount=true;
						$msg.="Du kannst nur einen Account pro Emailadresse haben.`n";
					}
				}
			}
			else
			{
				$msg.="Du musst eine gültige E-Mail Adresse eingeben. Diese wird für bestimmte Funktionen des Spiels verwendet!`n";
				$blockaccount=true;
			}

			// Passwörter
			// Passwort zu kurz
			if (mb_strlen($str_pass1)<=3)
			{
				$msg.="Dein Passwort muss mindestens 4 Zeichen lang sein.`n";
				$blockaccount=true;
			}

			// Passwortkontrolle falsch
			if ($str_pass1!=$str_pass2)
			{
				$msg.="Die Passwörter stimmen nicht überein.`n";
				$blockaccount=true;
			}

			// Name checken
			// Auf jeden Fall Formatierungstags raus
			$str_name = strip_appoencode($str_name,3);

			// Auf Korrektheit prüfen
			// Auf Korrektheit prüfen
			$str_rename_result = evaluate_user_rename( user_rename(0, stripslashes($str_name), false, false) );

			if(true !== $str_rename_result) {				

				$blockaccount = true;

			}

			// Account anlegen!
			if (!$blockaccount)
			{

				$int_sex = $_POST['sex']==1 ? 1 : 0;

				// Namen in reiner Großschreibung verhindern
				if(!getsetting('allletter_up_allow',1)) {
					if(ctype_upper($str_name)) {
						$str_name = mb_strtolower($str_name);
					}
				}
				// 1. Buchstabe immer groß
				if(getsetting('firstletter_up',1)) {
					$str_name = utf8_ucfirst($str_name);
				}

				// Emailvalidation
				if (getsetting("requirevalidemail",0))
				{
					$emailverification=md5(date("Y-m-d H:i:s").$str_mail);
				}

				// Empfehlung
				$int_refid = (int)$_GET['r'];
				if ( $int_refid > 0 )
				{
					$referer=$int_refid;
				}
				else
				{
					$referer=0;
				}
				
				//alten DemoUser löschen
				user_delete($demoacctid);
				$sql='DELETE FROM news WHERE accountid='.$demoacctid;
				db_query($sql);
				
				//Für jeden Account werden per Default folgende Preferences gesetzt
				$arr_prefs = array(
				'preview' => 1,
				'minimail' => 1,
				'hide_who_is_here' => 1,

				);

                $passsss = md5(',5f@Qrp~7<gu.Y3gGv8e8;4JM(q/pE,5f@Qrp~7<gu'.time().'.Y3gGv8e8;4JM(q/pE,5f@Qrp~7<gu.Y3gGv8e8;4JM(q/pE');

				// Datensatz in accounts anlegen
				$sql = "INSERT INTO accounts
						SET
							acctid=$demoacctid,
							name='Besucher $str_name',
							title='Besucher',
							password='".CCrypt::make_password_hash($passsss,true)."',
							sex=$int_sex,
							login='~$str_name',
							laston=NOW(),
							uniqueid='".$_COOKIE['lgi']."',
							lastip='".$_SERVER['REMOTE_ADDR']."',
							gold=".(int)getsetting("newplayerstartgold",50).",
							emailaddress='user@domain.invalid',
							emailvalidation='',
							activated = ".USER_ACTIVATED_MUTE_AUTO.",
							prefs = '".utf8_serialize($arr_prefs)."',
							lastmotd=DATE(NOW()),
							lastmotc=DATE(NOW()),
							race = 'npc',
							specialty = 1
						";


				db_query($sql);
				if (db_affected_rows(LINK)<=0)
				{
					output('`$Fehler`^: Dein Account konnte aus unbekannten Gründen nicht erstellt werden. Versuchs bitte einfach nochmal oder schreibe eine Anfrage.');
					page_footer();
				}

				// Datensatz in Extra-Info anlegen

				$sql = "INSERT INTO account_extra_info
						SET
							acctid=".$demoacctid.",
							birthday='".getsetting('gamedate','0000-00-00')."',
							referer='".$referer."',
							namecheck=16777215,
							namecheckday=0
						";
				db_query($sql);

				// Datensatz in Statistik anlegen
				//entfällt

				{
					output(get_title('Dein Charakter wurde erstellt.').'
					Name: `^'.$str_name.'`0`n
					Geschlecht: '.($int_sex?'weiblich':'männlich').'`n
					Rasse: `2Demo-User`0`n
					Fähigkeit: `4Dunkle Künste`0`n`n');

					$sql = "SELECT login,password FROM accounts WHERE acctid=$demoacctid";
					$result = db_query($sql);
					$row = db_fetch_assoc($result);
					show_created_screen($row['login'],$passsss);

				}

				$sql='SELECT login FROM accounts WHERE uniqueid="'.$session['uniqueid'].'" AND acctid<>'.$demoacctid;
				$result=db_query($sql);
				if(db_num_rows($result)>0)
				{
					$logtext='`nassoziiert mit ';
					while($same_id=db_fetch_assoc($result))
					{
						$logtext.=$same_id['login'].' ';
					}
				}
				systemlog('`@Neuer Demo-Account, IP: '.$_SERVER['REMOTE_ADDR'].', Name: '.$str_name.'`0'.$logtext);
				savesetting('demouser_last_IP',$_SERVER['REMOTE_ADDR']);

			}
			// END Account anlegen
			// Wenn Anmeldung fehlerhaft
			//Wird direkt über dem Formular angezeigt!
			else
			{
				$str_error_message = '`c`$Fehler`^:`n'.$str_rename_result.'`c`n';
				$str_op='';
			}
		}
		// END Formular abgesendet

		// Formular anzeigen
		if ($str_op=='')
		{

			$str_out .= get_title('Demo-Account');

$vorsilben = array(1=>'Bel','Lu','Dant','Rik','Tal','Dre','Rhag','Hord','Meib','Ast','Kor','Ver','Krag','Kyth','Alb','Tig','Aver','Bor','My','Ang','Dil','Sar','Or','Dra','Drik','Ruk','Nib','Man','Da','Nil','Art','Lak','Tith','Tumk','Est','Erc','Proc','Mar','Cael','Ag','Khaz','Ach','Kal','Art','Ask','Ka','Miy','Bik','Mik','Tar','Wol','Ray','Hal','Rob','Tak','Kar','As','Zor','Nogl','Sedi','Werl','Dir','Bone','Dark','Cap','Ver','Besid','Hage','Cunpol','Deriter','Sawan','Pes','Moad','Crim','Lyni','Ast','Mer','Ror','Des','Vert','War','Lan');
$nachsilben = array(1=>'nu','is','us','ilo','ker','yanki','uz','ius','ven','ar','lay','var','hut','ic','rav','rol','kul','kal','ven','sharr','cil','rak','ahm','lino','ibo','ivo','filo','avo','in','sard','ys','ar','ir','lion','er','ak','tram','icule','enay','ian','acs','har','orus','ka','onis','pil','icles','ra','in','us','ilo','is','as','ik','ak','at','it','ard','ar','ak','re','vreal','ustil','lisdo','vrel','werd','kryon','rit','mak','alk','zar','ad','id','et','wik','lik','dil','lin','en','ketch','asad','lon','gon','ron','rin','lion');
			$name=$vorsilben[e_rand(1,count($vorsilben))].$nachsilben[e_rand(1,count($nachsilben))];

			$arr_data = array('sex'=>0,'pass1'=>'demo','pass2'=>'demo','email'=>'user@domain.de','name'=>$name);

			$arr_data = array_merge($arr_data,$_POST);

			$arr_form = array('name'=>'Wie soll Dein Name in dieser Welt lauten?',
			'pass1'=>'Gebe bitte ein Passwort an:,hidden',
			'pass2'=>'Wiederhole dieses Passwort:,hidden',
			'email'=>'Deine E-Mail Adresse:,hidden',
			'sex'=>'Dein Geschlecht in dieser Welt ist:,radio,1,Weiblich,0,Männlich');

			$str_lnk = 'demouser.php?op=create'.(!empty($_GET['r'])?'&r='.$_GET['r']:'');

			$str_out .= '`0<form action="'.$str_lnk.'" method="POST">
			`tMit dem Demo-Zugang kannst du dir ein Bild machen, was dich in diesem Spiel erwartet. 
			`nDer Demo-Zugang verfällt beim Logout oder zum neuen Spieltag und ist im Funktionumfang stark eingeschränkt.
			`n`nBitte wähle einen `y`bNamen`b`t für deine Spielfigur.
			`nNamen von Prominenten, Personen der Zeitgeschichte oder Film-Helden sind hier nicht erwünscht.`n
			`n';

			//Infotext wegen email entfällt

			//Wenn ein Fehler aufgetreten ist dann soll die fehlermeldung da zu sehen sein wo der User hinguckt,
			//nämlich auf das Formular!
			$str_out .= $str_error_message;

			$str_out .= ''.generateform($arr_form,$arr_data,false,'Weiter',0);

			$str_out .= "</form>";
			output($str_out);

		}
		// END Formular anzeigen

	// END default
	break;
}
// END Main-Switch
}
page_footer();
?>