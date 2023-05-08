<?php
/**
* create.php:	Script zur Erstellung eines neuen Accounts
* @author LOGD-Core, modified by Drachenserver-Team
* @version DS-E V/2
*/
require_once('common.php');

page_header( getsetting('townname','Atrahor').' - Registratur' );

function show_created_screen ($str_name,$str_password) {

	$trash = getsetting("expiretrashacct",1);
	$new = getsetting("expirenewacct",10);
	$old = getsetting("expireoldacct",45);

	output("<form action='login.php' method='POST' name='loginform'>
				<input name='name' value='".$str_name."' type='hidden'>
				<input name='password' value='".$str_password."' type='hidden'>
				<input type='submit' class='button' value='Hier klicken zum Einloggen!'>
			</form>`n`n

			`n`n"
	.($trash>0?"Charaktere, die nie einloggen, werden nach $trash Tag(en) Inaktivität gelöscht.`n":"")
	.($new>0?"Charaktere, die nie Level 2 erreichen, werden nach $new Tag(en) Inaktivität gelöscht.`n":"")
	.($old>0?"Charaktere, die Level 2 erreicht haben, werden nach $old Tag(en) Inaktivität gelöscht.":"")
	."",true);
	output("`n`n`n`b`^Hinweis:`b`0`nSolltest du Probleme mit dem Login haben, musst du vermutlich erst Cookies zulassen!");
	rawoutput(getsetting('ad_conversion',''));

}

addnav('Startseite','index.php'.($_GET['r']>0?'?r='.intval($_GET['r']):''));

$str_op = $_GET['op'];

switch($str_op) {

	case 'val':
		
		// Filter auf PC checken
		checkban();
		
		$str_vali = $_GET['id'];

		$sql = "SELECT login,name,password,emailaddress,uniqueid,lastip FROM accounts WHERE emailvalidation='$str_vali' AND emailvalidation!=''";
		$result = db_query($sql);
		// Wenn Account mit dieser ValidierungsID existiert
		if (db_num_rows($result)>0)
		{

			$row = db_fetch_assoc($result);

			checkban($row['login'], $row['lastip'], $row['uniqueid'], $row['emailaddress']);

			// Passwort vergessen, neues aussuchen
			if (mb_substr($str_vali,0,1)=='x')
			{
				$str_pass1 = $_POST['pass1'];
				$str_pass2 = $_POST['pass2'];

				$form = true;
				if (!empty($str_pass1))
				{
					$form = false;
					if ($str_pass1 != $str_pass2)
					{
						output("`#Deine Passwörter stimmen nicht überein.`n");
						$form = true;
					}

					if (mb_strlen($str_pass1)<=3)
					{
						output("`#Dein Passwort ist zu kurz. Es muss mindestens 4 Zeichen lang sein.`n");
						$form = true;
					}

					// Mit den Passwörtern stimmt alles
					if(!$form) {
						
						//es gibt hier kein korrektes $session['user'], workaround für user_update
						$session['user']['emailvalidation']=$session['user']['password']='';

                        $lepass = md5($str_pass1);

						user_update(
							array(
								'emailvalidation'=>'',
								'password'=>CCrypt::make_password_hash($lepass, true),
								'where'=>"emailvalidation='$str_vali' AND emailvalidation!=''"
							)
						);
						db_query($sql);
						output("`#`cDein Passwort wurde geändert. Du kannst jetzt einloggen.`c`0");

						$row['password'] = $lepass;

						show_created_screen($row['login'],$row['password']);

						$form = false;
					}

				}	// END Wenn Pw gegeben

				if ($form)
				{
					$arr_form = array('pass1'=>'Dein neues Passwort:,password',
										'pass2'=>'Passwort bestätigen:,password');

					$str_lnk = 'create.php?op=val&id='.$str_vali;

					output("`&`c`bNeues Passwort wählen`b`c`n");
					output("`0<form action=\"$str_lnk\" method='POST'>",true);
					showform($arr_form,array(),false,'Neues Passwort speichern!');
					output("</form>",true);
				}

			}
			// Standard der EMail-Aktivierung
			else
			{

				//es gibt hier kein korrektes $session['user'], workaround für user_update
				$session['user']['emailvalidation']=$session['user']['password']='';
				
				user_update(
							array(
								'emailvalidation'=>'',
								'where'=>"emailvalidation='$str_vali' AND emailvalidation!=''"
							)
				);

				output("`#`cDeine E-Mail Adresse wurde bestätigt. Du kannst dich jetzt auf der Startseite einloggen.`c`0");

				//das geht hier nicht show_created_screen($row['login'],$row['password']);

				savesetting("newplayer",($row['name']));

			}
		}
		else
		{
			output("`#Deine E-Mail Adresse konnte nicht bestätigt werden. Möglicherweise wurde sie schon bestätigt. Versuch mal dich einzuloggen und schreibe eine Anfrage, falls es nicht klappt.");
			page_footer();
			exit;
		}
	// END Validierung
	break;

	// Passwort vergessen
	case 'forgot':

		$str_login = $_POST['charname'];

		if (!empty($str_login))
		{

			$sql = "SELECT login,emailaddress,emailvalidation,password FROM accounts WHERE login='".db_real_escape_string($str_login)."'";
			$result = db_query($sql);

			// Wenn Account gefunden
			if (db_num_rows($result)>0)
			{
				$row = db_fetch_assoc($result);

				// Wenn gültige Emailadresse
				if ( is_email( $row['emailaddress'] ) )
				{
					// Wenn Validierung noch nicht aktiviert, nun vornehmen
					if ($row['emailvalidation']=='')
					{
						$row['emailvalidation']=mb_substr("x".md5(date("Y-m-d H:i:s").$row['password']),0,32);
						
						//es gibt hier kein korrektes $session['user'], workaround für user_update
						$session['user']['emailvalidation']=$session['user']['password']='';
						
						user_update(
							array(
								'emailvalidation'=>$row['emailvalidation'],
								'where'=>"login='".$row['login']."'"
							)
						);
					}

					// EMail versenden
					send_mail($row['emailaddress'],
					getsetting('townname','Atrahor')."-Account: Passwort vergessen",
					"Jemand von ".$_SERVER['REMOTE_ADDR']." hat ein vergessenes Passwort von deinem Account angefordert.  Wenn du das warst, ist hier dein"
					." Link. Du kannst damit einloggen und dein Passwort im Profil einstellen.\n\n"
					."Wenn du diese E-Mail nicht angefordert hast, keine Panik! Du hast sie bekommen, sonst niemand."
					."\n\n  http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=$row[emailvalidation]\n\nDanke für's Spielen!",
					"From: ".getsetting("gameadminemail","postmaster@localhost.com")
					);
					output("`#Eine neue Bestätigungsmail wurde an die mit diesem Account gespeicherte Adresse verschickt. Du kannst sie zum Einloggen und zum ändern des Passworts verwenden. Solltest du innerhalb der nächsten paar Minuten keine Mail bekommen, schicke bitte eine Anfrage nach Hilfe ab!");

				}
				else
				{
					output("`#Bei diesem Account wurde keine gültige E-Mail Adresse angegeben. Wir können mit dem vergessenen Passwort nicht helfen.");

				}
			}
			else
			{
				output("`#Dieser Charakter kann nicht gefunden werden. Suche mal in der Einwohnerliste danach, vielleicht wurde der Charakter gelöscht.");
			}
		}
		// Noch keine Passwort-Anfrage abgeschickt, Formular anzeigen
		else
		{
			$arr_form = array('charname'=>'Gebe den Login-Namen deines Charakters ein (ohne Titel):');
			$str_lnk = 'create.php?op=forgot';
			output('`c`&`bVergessenes Passwort:`b`c`n`n
						<form action="'.$str_lnk.'" method="POST">',true);
			showform($arr_form,array(),false,'Passwort per Mail zuschicken',0);
			output('</form>',true);
		}

	// END Passwort vergessen
	break;

	// Standard: Charakter erstellen - Formular anzeigen
	default:
		
		// Filter auf PC checken
		checkban();
		
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
						$str_rename_result .= "Du kannst nur einen Account pro Emailadresse haben.`n";
					}
				}
			}
			else
			{
				$str_rename_result.="Du musst eine gültige E-Mail Adresse eingeben. Diese wird für bestimmte Funktionen des Spiels verwendet!`n";
				$blockaccount=true;
			}

			// Passwörter
			// Passwort zu kurz
			if (mb_strlen($str_pass1)<=3)
			{
				$str_rename_result.="Dein Passwort muss mindestens 4 Zeichen lang sein.`n";
				$blockaccount=true;
			}

			// Passwortkontrolle falsch
            if ($str_pass1!=$str_pass2)
            {
                $str_rename_result.="Die Passwörter stimmen nicht überein.`n";
                $blockaccount=true;
            }

            if ( ($_POST['rules1'] != 'true') ||  ($_POST['rules2'] != 'true') ||  ($_POST['rules3'] != 'true') ||  ($_POST['rules4'] != 'true')||  ($_POST['rules5'] != 'true') )
            {
                $str_rename_result.="Du musst alle Regeln und Bedingungen lesen, verstehen und akzeptieren.`n";
                $blockaccount=true;
            }

			// Name checken
			// Auf jeden Fall Formatierungstags raus
			$str_name = trim(strip_appoencode($str_name,3));

			// Auf Korrektheit prüfen
			$tmp_rename_result = evaluate_user_rename( user_rename(0, stripslashes($str_name), false, false) );

			if(is_string($tmp_rename_result))
			{
				$str_rename_result .= $tmp_rename_result;
			}

			if(true !== $tmp_rename_result) {				

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

				//Getting the titles from the settings table
				//Dragonslayer
				$titles = utf8_unserialize((getsetting('title_array',null)) );
				$title = addslashes($titles[0][$int_sex]);

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
				
				
				//Für jeden Account werden per Default folgende Preferences gesetzt
				$arr_prefs = array(
				'preview'		=> 1,
				'minimail'		=> 1,
				'nav_help_enabled' => 1,
				'showinvent'	=> 1,
				);

                $passss = md5($str_pass1);

				// Datensatz in accounts anlegen
				$sql = "INSERT INTO accounts
						SET
							name='$title $str_name',
							title='$title',
							password='".CCrypt::make_password_hash($passss, true)."',
							sex=$int_sex,
							login='$str_name',
							laston=NOW(),
							uniqueid='".$_COOKIE['lgi']."',
							lastip='".$_SERVER['REMOTE_ADDR']."',
							gold=".(int)getsetting("newplayerstartgold",50).",
							emailaddress='$str_mail',
							emailvalidation='$emailverification',
							prefs = '".utf8_serialize($arr_prefs)."'
						";


				db_query($sql);
				if (db_affected_rows(LINK)<=0)
				{
					output("`$Fehler`^: Dein Account konnte aus unbekannten Gründen nicht erstellt werden. Versuchs bitte einfach nochmal oder schreibe eine Anfrage.");
					page_footer();
				}

				// Datensatz in Extra-Info anlegen
				$int_acctid = db_insert_id();

				$sql = "INSERT INTO account_extra_info
						SET
							acctid=".$int_acctid.",
							birthday='".getsetting('gamedate','0000-00-00')."',
							referer='".$referer."'
						";
				db_query($sql);

				// Datensatz in Statistik anlegen
				$sql = "INSERT INTO account_stats
						SET
							acctid=".$int_acctid."
						";
				db_query($sql);

				if ($emailverification!="")
				{
					// Aktivierungsmail versenden
					send_mail($_POST['email'],
						getsetting('townname','Atrahor')."-Account: Bestätigung",
						"Um deinen Charakter in ".getsetting('townname','Atrahor')." freizuschalten, musst du nur noch auf den folgenden Link klicken.\n\n
						http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=$emailverification\n\n ~ Danke für's Spielen!",
						"From: ".getsetting("gameadminemail","postmaster@localhost.com")
					);
					output("`4Eine E-Mail wurde an `$$str_mail`4 geschickt, um die Adresse zu bestätigen. Klicke auf den Link darin, um den Account zu aktivieren.`0`n`n");
				}
				else
				{
					output(get_title('Dein Charakter wurde erstellt. Du kannst Dich jetzt einloggen.'));

					$sql = "SELECT login,password FROM accounts WHERE acctid=$int_acctid";
					$result = db_query($sql);
					$row = db_fetch_assoc($result);
					show_created_screen($row['login'],$passss);

					savesetting("newplayer",$title.' '.$str_name);

				}

				systemlog('`@Neuen Spieler registriert: `0',0,$int_acctid);

			}
			// END Account anlegen
			// Wenn Anmeldung fehlerhaft
			//Wird direkt über dem Formular angezeigt!
			else
			{
				$str_error_message = '`c`$Fehler`^:`n'.$str_rename_result.'`0`c`n';
				$str_op='';
			}
		}
		// END Formular abgesendet

		// Formular anzeigen
		if ($str_op=='')
		{

			$str_out .= get_title('Charakter erstellen');

			$arr_data = array('sex'=>0);
			$titles = utf8_unserialize((getsetting('title_array',null)) );

			$arr_data = array_merge($arr_data,$_POST);
			$arr_data['name']=stripslashes($arr_data['name']);

			$arr_form = array('name'=>'Wie soll Dein Name in dieser Welt lauten?',
			'pass1'=>'Gebe bitte ein Passwort an:,password',
			'pass2'=>'Wiederhole dieses Passwort:,password',
			'email'=>'Deine E-Mail Adresse:,text,255',
			'sex'=>'Dein Geschlecht in dieser Welt ist:,radio,1,Weiblich,0,Männlich');

			$str_lnk = 'create.php?op=create'.(!empty($_GET['r'])?'&r='.$_GET['r']:'');

			$str_out .= '`0<form action="'.$str_lnk.'" method="POST" id="create_account_form">
			`tDein Name darf unter Anderem `y`bkeinen Titel`b`t (Lord, Graf, Meister etc.) und `y`bkeine Beschreibung`b`t (ScharfesSchwert, grünerHund etc.) enthalten.
			 `nEr sollte nach Mittelalter klingen, mindestens jedoch nach Mythen und Sagen.
			`nNamen von Prominenten, Personen der Zeitgeschichte oder Film-Helden sind nicht erwünscht.
			`n
			Als kleine `y`bAnregung`b`t für einen neuen Namen können wir dir unseren ~`b<a href="#" onClick="'.popup('namegenerator.php',array('height'=>380)).';return false;">Namengenerator</a>`b~ anbieten.`n
			`n';

            $str_out .= '`tDie Eingabe einer `y`bE-Mail Adresse`b`t ';
			if(getsetting("requireemail",0)==0)
			{
				$str_out .= "ist `boptional`b. Wenn du aber keine eingibst, dann funktionieren viele bequeme Funktionen des Spiels nicht mehr, z.B.`n
				<ul>
					<li>Das Zurücksetzen deines Passworts, falls du es vergessen haben solltest,</li>
					<li>Das Archivieren von Ingame Nachrichten oder Rollenspiel,</li>
					<li>Die Automatische Mitteilung über neue Spielnachrichten</li>
				</ul>";
			}
			else
			{
				$str_out .= "wird `bbenötigt`b, damit viele bequeme Funktionen des Spiels funktionieren, z.B.`n`0
				<ul>
					<li>Das Zurücksetzen deines Passworts, falls du es vergessen haben solltest,</li>
					<li>Das Archivieren von Ingame Nachrichten oder Rollenspiel,</li>
					<li>Die Automatische Mitteilung über neue Spielnachrichten</li>
				</ul>`n
				";
				if(getsetting("requirevalidemail",0)==1)
				{
					$str_out .= 'Außerdem wird eine E-Mail zur `bBestätigung`b an die eingegebene Adresse geschickt, bevor du dich einloggen kannst.';
				}
                $str_out .= 'Sei aber versichert, deine Mailadresse wird auf keinen Fall an Dritte weitergegeben!';
			}

            $str_out .= '<hr>`n';

			//Wenn ein Fehler aufgetreten ist dann soll die fehlermeldung da zu sehen sein wo der User hinguckt,
			//nämlich auf das Formular!
			$str_out .= $str_error_message;

			//$str_out .= generateform($arr_form,$arr_data,false,getsetting('townname','Atrahor').' betreten!');

				$str_submit = '<input type="submit" class="button" value="'.getsetting('townname','Atrahor').' Betreten">';
			
			$str_out.='`0<table border=0 cellspacing=5 align="center">
			<tr>
			<td align="right">Geschlecht: </td>
			<td><select name="sex" id="chartitle"">
			<option value=0 '.($arr_data['sex']==0?'selected':'').'>männlich</option>
			<option value=1 '.($arr_data['sex']==1?'selected':'').'>weiblich</option>
			</select></td>
			</tr><tr>
			<td align="right">Name: </td>
			<td><input type=text name="name" id="charname" value="'.$arr_data['name'].'" size=20 maxlength=25></td>
			</tr>

			<tr>
			<td align="right">Gib bitte ein Passwort ein:</td>
			<td><input type="password" name="pass1" value="'.$arr_data['pass1'].'"></td>
			</tr><tr>
			<td align="right">Wiederhole dieses Passwort:</td>
			<td><input type="password" name="pass2" value="'.$arr_data['pass2'].'"></td>
			</tr><tr>
			<td align="right">Deine E-Mail Adresse'.( (getsetting("requireemail",0)==0) ? ' (optional)' : '' ).':</td>
			<td><input type="text" name="email" value="'.$arr_data['email'].'"></td>
			</tr>

			<tr>
			<td colspan=2><input type="checkbox" name="rules1" value="true"> Ich habe die <a href="./static/nutzungsbestimmungen.html" target="_blank" onClick="'.popup('./static/nutzungsbestimmungen.html',array('width' => '1000')).';return false;">Nutzungsbestimmungen</a> gelesen, verstanden und akzeptiere sie.</td>
			</tr>

			<tr>
			<td colspan=2><input type="checkbox" name="rules2" value="true"> Ich habe die <a href="./static/spielregeln.html" target="_blank" onClick="'.popup('./static/spielregeln.html',array('width' => '1000')).';return false;">Spielregeln</a> gelesen, verstanden und akzeptiere sie.</td>
			</tr>

			<tr>
			<td colspan=2><input type="checkbox" name="rules3" value="true"> Ich habe die <a href="./static/netiquette.html" target="_blank" onClick="'.popup('./static/netiquette.html',array('width' => '1000')).';return false;">Netiquette</a> gelesen, verstanden und akzeptiere sie.</td>
			</tr>

			<tr>
			<td colspan=2><input type="checkbox" name="rules4" value="true"> Ich habe die <a href="./static/datenschutzrichtlinien.html" target="_blank" onClick="'.popup('./static/datenschutzrichtlinien.html',array('width' => '1000')).';return false;">Datenschutzrichtlinien</a> gelesen, verstanden und akzeptiere sie.</td>
			</tr>

			<tr>
			<td colspan=2><input type="checkbox" name="rules5" value="true"> Ja, ich bestätige hiermit `bwahrheitsgemäß`b, dass ich `bmindestens '.getsetting("min_age",16).' Jahre`b alt bin.</td>
			</tr>

			<tr>
			<td align="center" colspan=2>'.$str_submit.'</td>
			</tr>
			</table>';

			$str_out .= "</form>";
			output($str_out);

		}
		// END Formular anzeigen

	// END default
	break;
}
// END Main-Switch

page_footer();
?>