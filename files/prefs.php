<?php
/**
* prefs.php: Profil + Einstellungen. Umgestellt auf Popup-Modus
* @author 	partly LOGD-Core, modded and rewritten by talion <t@ssilo.de> + alucard <diablo3-clan@web.de>
* @version DS-E V/2
*/

require_once('common.php');

if(!$session['user']['loggedin'])
{
	exit;
}

$CCharStats = new CCharStats();
$CCharStats->initialize_data($CCharStats->arr_default,array());
$CCharStats->check_prefs_save();

// Wenn neues Template gesetzt werden soll
if (isset($_POST['template'])){
	$overwrite_template = $_POST['template'];
}

if(isset($_GET['on_off_history']))
{
	$id = (int)$_GET['id'];

	// Switch
	$sql = 'UPDATE history SET hidden = IF(hidden=1,0,1) WHERE id='.$id;
	db_query($sql);

	$str_back = '/mb History Eintrag wurde umgeschaltet!';
	jslib_http_command($str_back);
	exit();
}

$BOOL_JSLIB_PLU_MI = true;
popup_header('Einstellungen &amp; Profil',true);

$biolink	= 'bio.php?id='.$session['user']['acctid'];
$preflink	= 'prefs.php';
$piclink 	= 'pict.php';
$multilink 	= 'multi_prefs.php';
if(getsetting('bioextranotesmaxlength',-1) > -1) {
	$noteslnk = 'usernotes.php';
}

output(''.JS::encapsulate('window.resizeTo(800,600);')); // die Größe wird eh beim erstellen des Popups angegeben

$rowex = user_get_aei('
	biotime,
	charclass,
	shortcuts,
	bio_freetexts_count,
	html_locked,
	ext_profile,
	char_birthdate,
	together_with,
	together_yesno
');

//erweitertes profil
$ext_prof = utf8_unserialize($rowex['ext_profile']);


// Char löschen
if ($_GET['op']=='suicide' && getsetting('selfdelete',0)!=0) {

	if(isset($_POST['reason'])) {
        if(!CCrypt::verify_password_hash($_POST['pass'],$session['user']['password']))
        {
			output('`n`n`$Das angegebene Passwort ist falsch!`0`n`n');
		}
		else {

			if(mb_strlen($_POST['reason']) > 20) {

				user_delete($session['user']['acctid']);
				output('`n`n`$Dein Charakter, sein Inventar und alle seine Kommentare wurden gelöscht!');
                systemlog($session['user']['login'].' ('.$session['user']['acctid'].') hat sich gelöscht! Grund:'.utf8_htmlspecialsimple($_POST['reason']));
				addnews("`#".$session['user']['name']."`# beging Selbstmord.");
				Atrahor::clearSession();
				$session['user'] = array();
				$session['loggedin'] = false;
				$session['user']['loggedin'] = false;

				popup_footer(false);
				exit();
			}
			else {
				output('`n`n`$Teile uns bitte deine Beweggründe in mindestens 20 Zeichen Länge mit.`0`n`n');
			}
		}
	}

	output('`n`n`$Selbstmord`b`&`c`n`n
			Achtung! Du bist gerade im Begriff, deinen Charakter und all seinen Besitz unwiderruflich zu löschen!`n`n
			`c[ <a href="'.$preflink.'">Bloß nicht! - Zurück zum Profil</a> ]`c`n`n
			Falls du dir sicher bist, diesen Schritt vollziehen zu wollen, so bitten wir dich darum, uns kurz deine Gründe
			zu nennen. Wir sind ständig bestrebt, '.getsetting('townname','Atrahor').' so schön wie möglich zu gestalten. Deine
			Rückmeldung würde uns dabei sehr helfen.`n
			Vielen Dank!`n
			`c
			<form method="POST" action="'.$preflink.'?op=suicide">

				<textarea name="reason" cols="30" rows="10" class="input">'.(isset($_POST['reason']) ? $_POST['reason'] : '').'</textarea>`n`n

				Gib bitte hier zur Sicherheit dein Passwort ein: <input type="password" name="pass">`n`n

				<input type="submit" value="Das Leben ist Schmerz (JA, Charakter löschen!)">

			</form>`c');

	popup_footer(false);
	exit();

}
else {	// Einstellungen speichern

	if (count($_POST)){	// wenn Einstellungen abgeschickt

		if(isset($_POST['check_acctid']) && $_POST['check_acctid']!=$session['user']['acctid'])
		{ //Da war wohl noch ein altes Fenster offen
			echo('<br><b>Check failed:</b> Du kannst nicht das Profil mit der ID '.$_POST['check_acctid'].' speichern!<br><br><b>Fenster schließen und Einstellungen erneut aufrufen.</b>.');
			exit;
		}

		if(is_null_or_empty(trim($_POST['pass1'])) == false && is_null_or_empty(trim($_POST['pass2']) == false))
		{
			if ($_POST['pass1']!=$_POST['pass2'])
			{
				output("`#Deine Passwörter stimmen nicht überein.`n");
			}
			else
			{
				if ($_POST['pass1']!='')
				{
					if (mb_strlen($_POST['pass1'])>3){
						$session['user']['password'] = CCrypt::make_password_hash($_POST['pass1']);

						output('`#Dein Passwort wurde geändert.`n');
						debuglog("hat das Passwort geändert.");
					}
					else
					{
						output('`#Dein Passwort ist zu kurz. Es muss mindestens 4 Zeichen lang sein.`n');
					}
				}
			}
		}

        $session['user']['nohof']= $_POST['nohof'];

		//alle _POST-Variablen werden in $session['user'][prefs] gespeichert, außer die in diesem Array auf 1 gesetzten
		$nonsettings = array(
			'avatar'			=> 1,
			'bio'				=> 1,
			'bio_extra_title'	=> 1,
			'bio_msg_char_title'	=> 1,
			'char_birthdate'	=> 1,
			'charclass'			=> 1,
			'check_acctid'		=> 1,
			'color_body'		=> 1,
			'color_body_text'	=> 1,
			'color_btn_back'	=> 1,
			'color_btn_text'	=> 1,
			'color_btn_back_a'	=> 1,
			'color_btn_text_a'	=> 1,
			'color_head'		=> 1,
			'color_help'		=> 1,
			'color_value'		=> 1,
			'disc_avatar'		=> 1,
			'disc_bio'			=> 1,
			'emailaddress'		=> 1,
			'mount_avatar'		=> 1,
			'mount_bio'			=> 1,
			'pass1'				=> 1,
			'pass2'				=> 1,
			'template'			=> 1,
			'bio_freetexts_count' => 1,
			'color_schonheit' => 1,
			'color_ansehen' => 1,
            'nohof' => 1,

		);

		foreach($_POST as $key => $val){
			if (!array_key_exists($key,$nonsettings)) {
				$session['user']['prefs'][$key] = stripslashes($_POST[$key]);
			}
		}

		//Wofür gibt's diese praktische Funktion, die alles erledigt?
		//Beim Templatewechsel stand nämlich bei Änderung bisher immer noch das alte Template im Auswahlfeld...
		if (!empty($_POST['template']))
		{
			define_template($_POST['template']);
		}


		if (isset($_POST['emailaddress']) && $_POST['emailaddress']!=$session['user']['emailaddress']){
			if (is_email($_POST['emailaddress'])){
				if (getsetting("requirevalidemail",0)==1){
					output("`#Die E-Mail Adresse kann nicht geändert werden, die Systemeinstellungen verbieten es. (E-Mail Adressen können nur geändert werden, wenn der Server mehr als einen Account pro Adresse zulässt.) Sende eine Petition, wenn du deine Adresse ändern willst, weil sie nicht mehr länger gültig ist.`n");
				}
				else{
					output("`#Deine E-Mail Adresse wurde geändert.`n");
					$session['user']['emailaddress']=$_POST['emailaddress'];
				}
			}
			else {
				output("`#Das ist keine gültige E-Mail Adresse.`n");
			}
		}

          user_set_aei($array_aei_changes);

		output( $msg );
		$str_message .= "`n`@`bEinstellungen gespeichert!`b`0`n";

		//Nochmals laden, damit auch die Werte aktuell sind, die gerade abgeändert wurden
		$rowex = user_get_aei(implode(',',array_keys($rowex)));

	}	// END Einstellungen abgeschickt
}	// END Einstellungen abspeichern


output($str_message.'
	`b ( <a href="'.$biolink.'">Bio ansehen</a> - <a href="prefs_bio.php">Bioverwaltung</a> - <a href="' . $piclink . '">Bilderverwaltung</a> - <a href="' . $multilink . '">Multiverwaltung</a>'.(!empty($noteslnk) ? ' - <a href="' . $noteslnk . '">Notizen</a>':'').' )`b
');

$str_select['skin'] = 'select';
$str_select['popup'] = 'select,,Standard';

$sql = "
	SELECT
		*
	FROM
		`skins`
	WHERE
		`activated`		= '1'	AND
		`superuser`		" . ($Char->isSuperuser()?'>=':'=') . " '0'
	ORDER BY
		`name`
";
$res = db_query($sql);
while ($row = db_fetch_object($res))
{
	$str_select[$row->type] .= ',' . $row->folder . ',' . $row->name;
}


// Hilfetext für die Biographie
/*$str_biodesc = plu_mi('prefs_bio', 0, false) . ' Kleine Erklärung zur Formatierung deiner Bio:
				<div id="' . plu_mi_unique_id('prefs_bio') . '" style="display:none">
				Jeweils &#96; und dann das angegebene Zeichen. Alle diese Formatierungen müssen auch wieder geschlossen werden,
				d.h. wenn der Text normal weitergehen soll, nochmal denselben Code eingeben.`n
				Zeilenumbruch: &#96;n.`n
				Kursiv: &#96;i`n
				Zentrieren: &#96;c`n
				Fett: &#96;b`n
				Wenn du Bilder in deine Bio einfügen möchtest, musst du diese in der Jägerhütte freischalten und anschließend
				hier im Profil (unter dem Punkt "Bilder") hochladen. Um das Bild mit der Nr. 0 in den Text einzubauen, schreibst du etwa:`n
				[PIC=0]`n
				Du kannst auch die Bilder von Dir, Tier, Knappe und - so vorhanden - Haus einbauen!`n
				Avatar: [PIC=p]`n
				Tier: [PIC=m]`n
				Knappe: [PIC=d]`n
				Haus: [PIC=h]
				</div>`n';
*/

// Datenarray erstellen
$prefs = $session['user']['prefs'];
$prefs['emailaddress'] = $session['user']['emailaddress'];
//$prefs['bio'] = $rowex['bio'];
$prefs['charclass'] = $rowex['charclass'];
$prefs['nohof'] = $session['user']['nohof'];

$prefs['template'] = ($_COOKIE['template'] != '' ? $_COOKIE['template'] : getsetting('defaultskin','yarbrough.htm'));
$prefs['email'] = $session['user']['emailaddress'];
$prefs['char_birthdate'] = $rowex['char_birthdate'];

// Formulararray erstellen
$prefs['check_acctid']=$session['user']['acctid'];//verhindert das Speichern wenn der User inzwischen seinen Account gewechselt hat
$form=array(
		"Allgemein,title"
        //,"slowinet"	=> "Hast du eine langsame Internetverbindung < DSL 2000?,bool"
		,"template"		=> 'Skin,'.$str_select['skin']
		,"template_pop"	=> 'Popup-Skin,' . $str_select['popup']
		,"pass1"		=> 'Neues Passwort,password|?Lasse das Feld leer, wenn du es nicht ändern möchtest.'
		,"pass2"		=> 'Passwort wiederholen,password'
		,"emailonmail"	=> "E-Mail senden wenn du eine Brieftaube bekommst?,bool"
		,"systemmail"	=> "E-Mail bei Systemmeldungen senden?,bool|?Z.b. Niederlage im PvP."
		,"dirtyemail"	=> "Kein Wortfilter in Brieftauben?,bool"
		,"nocolors"		=> "Die Textfarben deaktivieren?,bool"
		,"noimg"		=> "Navigationsbilder deaktivieren?,bool"
        ,"showinvent"	=> "Inventar- und Profil-Link auf dem Stadtzentrum zeigen?,bool|?Wenn Ja hast du 2 Links mehr in der Navigationsleiste, wenn Nein werden diese Links nur in der Vitalinfo angezeigt"
		,"nohotkeys"	=> "Die Hotkeys deaktivieren?,bool|?Der Seitenwechsel per Tastatur über die farbig hervorgehobenen Buchstaben ist nicht mehr möglich"


,"check_acctid"	=> "AccountID,hidden"
);

if (getsetting("requirevalidemail",0)==0) {
	$form = array_merge($form,array(
		"emailaddress"	=> "E-Mail Adresse`n"
	));
}
else {
	$form = array_merge($form,array(
		"emailaddress"	=> "E-Mail Adresse`n,viewonly|?Nutze die Funktion 'Anfrage schreiben', um die Administration über eine evtl. neue Emailadresse in Kenntnis zu setzen und sie ändern zu lassen."
	));
}



if(getsetting('nav_help_enabled',0) == 1)
{
	$form = array_merge($form,array(
		'nav_help_enabled'	=> 'Hilfetext bei Navigationslinks einschalten?,bool'
	));
}

//Chef Kommt
$form = array_merge($form,array(
		'chef_kommt'=> 'Chef kommt - Paniktaste aktiv?,bool|?Wenn man auf Arbeit ist und der Chef kommt, dann genügt ein Druck auf ESC und schon sieht man wie ein Workaholic aus.'
));


$form = array_merge($form,array(
		"Spiel,title"
		,'ggg' => 'Gegenstände:,viewonly'
		,"dontstack" 	=> "Item-Stacking deaktivieren?,bool"
		,"dontstackguild" 	=> "Item-Stacking in der Gilde deaktivieren?,bool"
		,"itemsperpage" 	=> "Items pro Seite global überschreiben auf?,select,0,-,5,5,10,10,20,20,30,30,40,40,50,50,60,60,70,70,80,80,90,90,100,100"
		,'mmm' => 'Marktstände:,viewonly'
		,"usershopsshowdesc" => "Beschreibungen anzeigen?,bool"
		,'rrr' => 'Runen:,viewonly'
		,"runenmagienojs" => "Runenmagie vereinfachte version aktivieren?,bool"
,'rprprp' => 'RP:,viewonly'
,"deacautoexp" => "Auto EXP nach ".getsetting('recoveryage',75)." Tagen deaktivieren?,bool"
,"deacautond" => "Auto Newday deaktivieren?,bool"
,'hofhof' => 'Ruhmeshalle:,viewonly'
,"nohof" => "Nicht in der Ruhmeshalle erscheinen?,bool"
,"norprace" => "In der Einwohnerliste keine RP-Rasse anzeigen?,bool|?Wenn dies aktiviert ist wird in der Einwohnerliste die echte Standard Rasse angezeigt und nicht die RP-Rasse."
,'biobio' => 'Bio:,viewonly'));


$form = array_merge($form,array("newbio_big" => "Die Bio/Steckbrief gleich groß anzeigen?,bool"
,"newbio_full" => "Bio/Steckbrief immer in Fullscreen anzeigen?,bool"

));

$form = array_merge($form,array(
	"Char,title"
	,"notall2bank"		=> "Bank: etwas Gold behalten?,text,2|?Du behältst mit der Funktion 'Alles einzahlen' etwas Gold auf der Hand. Die Zahl wird mit deinem Level multipliziert."
	,"notall2bankfix"		=> "Bank: etwas Gold behalten (Fixbetrag)?,text,9|?Du behältst mit der Funktion 'Alles einzahlen' einen Fixbetrag Gold auf der Hand. Setzt das vorherige ausser Kraft.."
	,"taxfrombank"		=> "Steuern vom Bankkonto`neinziehen?,bool|?Sofern du am Tagesanfang genug Gold auf dem Konto hast, werden die Steuern <b>(+ " . getsetting('taxfee',20) . "% Bearbeitungsgebühr!)</b> automatisch eingezogen. Andernfalls musst du wie bisher im Rathaus erscheinen."
	,"healerdebit"		=> "Fehlende Heilkosten vom Bankkonto nehmen?,bool|?Der Heiler holt sich fehlendes Gold vom Bankkonto, falls genug vorhanden ist."
	,"showhpbar"		=> "Beim Kampf grafischen Lebens- bzw.`nSeelenpunktebalken anzeigen?,bool|?Zeigt einen Balken an, der die Lebenspunkte mit denen deines Gegners <b>vergleicht</b>. Das heißt, wenn dein Gegner viel schwächer ist als du, werden seine LP vielleicht schon am Anfang gelb angezeigt, obwohl er noch nicht geschwächt wurde, und umgekehrt."

	
));



$form = adv_array_merge($form,$CCharStats->get_prefs());

//Chateinstellungen
$form = array_merge($form,
    array(	'Chat,title',
        'chat_block' 		=> 'Posts im Blocksatz darstellen?,bool',
        'chat_fettkursiv' 		=> 'In Posts Fett und Kursiv ausblenden?,bool',
        'chat_newlines' 		=> 'In Posts Absätze darstellen?,bool',
        'chat_comperpage' 		=> 'Anzahl an Posts im Chat anzeigen?,select,0,Standard,5,5,10,10,15,15,20,20,25,25,30,30,50,50,75,75,100,100,150,150,200,200',
        'timestamps'		=> "Uhrzeit vor Chatnachrichten anzeigen?,bool|?Wenn aktiv, wird vor jedem Chatbeitrag die Uhrzeit angezeigt, zu der er geschrieben wurde.",
        'minimail'			=> "Mailinfo direkt neben dem Eingabefeld?,bool|?Wenn aktiv, erscheint bei Eintreffen neuer Mails ein kleines Symbol neben dem Eingabefeld.",
        'chat_big_input'	=> 'Zeilen beim Eingabefeld,select,0,1,5,5,10,10,15,15,20,20|?Wenn 1, ist das Absenden mit Enter möglich. Wenn Mehrere Zeilen eingestellt werden, wird Strg + Enter benötigt.',

        'chat_noautocol' 		=> 'Ungefärbtes Gesagtes automatisch einfärben deaktivieren?,bool',
        'chat_simpleautocol' 		=> 'Vereinfachtes autofärben aktivieren?,bool,text,|?Ein " wird automatisch durch unten festgelegtes Anfang- und Endzeichen ersetzt',
    )
);


$form[] = '<b>Post-Einstellungen</b>,viewonly';

$form = array_merge($form,
    array(	'commenttalkcolor'	=> "Farbe für Gesagtes in Kommentaren`n,hex_pick",    //'`'.($prefs['commenttalkcolor'] != '' ? $prefs['commenttalkcolor'] : '#')."Farbe`0 für Gesagtes in Kommentaren (Ohne &#0096; !)`n",
        'commentemotecolor' => "Farbe für Aktionen in Kommentaren`n,hex_pick", //'`'.($prefs['commentemotecolor'] != '' ? $prefs['commentemotecolor'] : '&')."Farbe`0 für Aktionen in Kommentaren (Ohne &#0096; !)`n",
        'commentbegin' => 'Anfang vom gesagten (Leer = "),text,|?Beliebige Anzahl an Sonderzeichen + Leerzeichen + Farben + Formatierungen erlaubt und müssen nicht im Post eingetippt werden.',
        'commentend' => 'Ende vom gesagten (Leer = "),text,|?Beliebige Anzahl an Sonderzeichen + Leerzeichen + Farben + Formatierungen erlaubt und müssen nicht im Post eingetippt werden.',

        'commentbeout' => 'Anfang und Endzeichen ausblenden?,bool',
        'commentbecol' => 'Anfang und Endzeichen nicht einfärben?,bool',

        'disc_commenttalkcolor'	=> "Farbe für Gesagtes des Knappen in Kommentaren`n,hex_pick", //'`'.($prefs['disc_commenttalkcolor'] != '' ? $prefs['disc_commenttalkcolor'] : '#')."Farbe`0 für Gesagtes des Knappen in Kommentaren (Ohne &#0096; !)`n",
        'disc_commentemotecolor' => "Farbe`0 für Aktionen des Knappen in Kommentaren`n,hex_pick", //'`'.($prefs['disc_commentemotecolor'] != '' ? $prefs['disc_commentemotecolor'] : '&')."Farbe`0 für Aktionen des Knappen in Kommentaren (Ohne &#0096; !)`n"

        'disc_commentbegin' => 'Knappe: Anfang vom gesagten (Leer = "),text,|?Beliebige Anzahl an Sonderzeichen + Leerzeichen + Farben + Formatierungen erlaubt und müssen nicht im Post eingetippt werden.',
        'disc_commentend' => 'Knappe: Ende vom gesagten (Leer = "),text,|?Beliebige Anzahl an Sonderzeichen + Leerzeichen + Farben + Formatierungen erlaubt und müssen nicht im Post eingetippt werden.',

        'disc_commentbeout' => 'Knappe: Anfang und Endzeichen ausblenden?,bool',
        'disc_commentbecol' => 'Knappe: Anfang und Endzeichen nicht einfärben?,bool',

    )
);

//by bathi
$form[] = '<b>MsgChars-Post-Einstellungen</b>,viewonly';
$aei = user_get_aei('msg_chars');
$msgChars = adv_unserialize($aei['msg_chars']);
$has = count($msgChars);
$arr_msgChars = array();
for($i=0; $i<$has;$i++){
  	$arr_msgChars['msgChar_'.$i.'_commenttalkcolor'] = appoencode("Farbe für Gesagtes des MsgChars ".$msgChars[$i]." in Kommentaren`n").",hex_pick";
    $arr_msgChars['msgChar_'.$i.'_commentemotecolor'] = appoencode("Farbe`0 für Aktionen des MsgChars ".$msgChars[$i]." in Kommentaren`n").",hex_pick";

    $arr_msgChars['msgChar_'.$i.'_commentbegin'] = appoencode("MsgChars ".$msgChars[$i]."`0: Anfang vom gesagten (Leer = \"),text|?Beliebige Anzahl an Sonderzeichen + Leerzeichen + Farben + Formatierungen erlaubt und müssen nicht im Post eingetippt werden.")."";
    $arr_msgChars['msgChar_'.$i.'_commentend'] = appoencode("MsgChars ".$msgChars[$i]."`0: Ende vom gesagten (Leer = \"),text|?Beliebige Anzahl an Sonderzeichen + Leerzeichen + Farben + Formatierungen erlaubt und müssen nicht im Post eingetippt werden.")."";

    $arr_msgChars['msgChar_'.$i.'_commentbeout'] = appoencode("MsgChars ".$msgChars[$i]."`0: Anfang und Endzeichen ausblenden?").",bool";
    $arr_msgChars['msgChar_'.$i.'_commentbecol'] = appoencode("MsgChars ".$msgChars[$i]."`0: Anfang und Endzeichen nicht einfärben").",bool";
}

$form = array_merge($form,$arr_msgChars);
//by bathi end

$form[] = '<b>Shortcuts</b>,viewonly';
for ($i=0;$i<=$rowex['shortcuts'];$i++){
    $form = array_merge($form,array('sx'.$i => 'Shortcut %x'.$i.' => '.appoencode($prefs['sx'.$i])));
}

$form[] = '<b>Hexfarben-Shortcuts</b>,viewonly';
for ($i=0;$i<=9;$i++){
    $form = array_merge($form,array('fx'.$i => 'Shortcut %f'.$i.' => '.appoencode('²'.( ($prefs['fx'.$i] != '') ? $prefs['fx'.$i] : '#FFFFFF' ).';Farbiger Testtext!`0,hex_pick')));
}

//
// Automatische Mailweiterleitung für Superuser
//
if(getsetting('forward_yom_admin_enable',1) && $access_control->su_check(access_control::SU_RIGHT_FORWARD_YOM_TO_SUPERUSER))
{
	$form[] = 'Mail Einstellungen,divider';
	$str_forward_yom_to = 'An welchen Superuser sollen deine Brieftauben weitergeleitet werden?,select';

	//-1 setzt die weiterleitung außer Kraft
	$str_forward_yom_to .= ',-1,Weiterleitung deaktivieren';

	// Superuser auswählen
	$res = db_query('SELECT acctid,login FROM accounts WHERE superuser>0 ORDER BY login ASC');

	while($a = db_fetch_assoc($res)) {

		if($session['user']['acctid']==$a['acctid'])
		{
			continue;
		}

		$str_forward_yom_to .= ','.$a['acctid'].','.$a['login'];
	}
	$form['forward_yom_to_superuser'] = $str_forward_yom_to;
}
//
// ENDE Automatische Mailweiterleitung für Superuser
//

// Farbübersicht (für Laula, by talion)

$str_colors = '<table><tr class="trhead"><th>Code</th><th>HEX-Code</th><th>Beispiel</th></tr>';
$res = db_query("
	SELECT
		`color`,
		`code`
	FROM
		`appoencode`
	WHERE
		`allowed`	= '1'	AND
		`active`	= '1'	AND
		`color`		IS NOT null
	ORDER BY
		`listorder` ASC
");
while($c = db_fetch_assoc($res)) {

	$str_colors .= '
		<tr>
			<td>`b&#0096;'.$c['code'].':`b</td>
			<td>'.$c['color'].'</td><td>`'.$c['code'].'Laula fährt im komplett verwahrlosten Schlitten quer durch Atrahor.`0</td>
		</tr>
	';

}
$str_colors .= '</table>';
$prefs['color_help'] = $str_colors;
$form = array_merge($form,array('Farbcodes,title','color_help'=>',viewonly'));
// END Farbübersicht



if($access_control->su_check(access_control::SU_RIGHT_DEBUG))
{ //Einstellungen für Entwickler
	$form = array_merge($form,array(
		'Entwickler,title'
		,'charinfo_debugfield'=>'Debug in der Vitalinfo|?Ein beliebiges Feld aus accounts oder aei, welches ständig in der Vitalinfo ausgegeben wird. leer=deaktiviert'
		,'quicknav_enabled'=>'Soll das Quicknav Feld angezeigt werden?,bool'
	));
}

// Formular anzeigen
$str_lnk = $preflink.'?op=save';
output('`n<form action="'.$str_lnk.'" method="POST" enctype="multipart/form-data">');
showform($form,$prefs,false,'Speichern',8);
output('</form>');
// END Formular anzeigen



// Nur Löschung zulassen, wenn User am Leben: Soll verhindern, dass frustrierte Spieler sich gedankenlos löschen
if ($session['user']['alive'] && getsetting('selfdelete',0)!=0) {
	output("`n`n`n<form action='".$preflink."?op=suicide&userid={$session['user']['acctid']}' method='POST'>");
	output("<input type='submit' class='button' value='Charakter löschen'>");
	output('</form>');
}

popup_footer();
?>