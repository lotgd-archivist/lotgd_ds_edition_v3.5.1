<?php
/**
 * prefs_bio.php: Profil + Einstellungen. Umgestellt auf Popup-Modus
 * @author 	partly LOGD-Core, modded and rewritten by talion <t@ssilo.de> + alucard <diablo3-clan@web.de>
 * @version DS-E V/2
 */

$DONT_OVERWRITE_NAV 	= true;
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $BOOL_JS_HTTP_REQUEST 	= true;
}

require_once('common.php');

if(!$session['user']['loggedin'])
{
    exit;
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
popup_header('Einstellungen &amp; Profil',true,true);

$biolink	= 'steckbrief.php?id='.$session['user']['acctid'];
$preflink	= 'prefs_steckbrief.php';
$piclink 	= 'pict.php';
$multilink 	= 'multi_prefs.php';
if(getsetting('bioextranotesmaxlength',-1) > -1) {
    $noteslnk = 'usernotes.php';
}

output(''.JS::encapsulate('window.resizeTo(1010,757);')); // die Größe wird eh beim erstellen des Popups angegeben

$rowex = user_get_aei('
	biotime,
	charclass,
	shortcuts,
	bio_freetexts_count,
	html_locked,
	ext_profile,
	ext_ooc,
	ext_rp,
	ext_multis,
	ext_bio_orte,
	char_birthdate,
	together_with,
	together_yesno
');

$rowex['ext_multis'] = utf8_unserialize($rowex['ext_multis']);
$rowex['ext_bio_orte'] = utf8_unserialize($rowex['ext_bio_orte']);

//erweitertes profil
$ext_prof = utf8_unserialize($rowex['ext_profile']);

//freie bio für alle ;)
$rowex['bio_freetexts_count'] = 60;
$ext_prof['bmount'] =1;
$ext_prof['bdisciple'] = 1;
$ext_prof['extra_info']=true;
$ext_prof['disciple']=true;
$ext_prof['mount']=true;

//Ende




// Char löschen
if ($_GET['op']=='suicide' && getsetting('selfdelete',0)!=0) {



}
else {	// Einstellungen speichern

    if (count($_POST)){	// wenn Einstellungen abgeschickt

        if(isset($_POST['check_acctid']) && $_POST['check_acctid']!=$session['user']['acctid'])
        { //Da war wohl noch ein altes Fenster offen
            echo('<br><b>Check failed:</b> Du kannst nicht das Profil mit der ID '.$_POST['check_acctid'].' speichern!<br><br><b>Fenster schließen und Einstellungen erneut aufrufen.</b>.');
            exit;
        }

        $array_aei_changes = array();



        //end edit by bathi
        $fid_query = db_query('SELECT fieldid FROM account_bio_freetexts WHERE acctid='.$session['user']['acctid'].' ORDER BY fieldid ASC');
        for ($i = 1; $i <= $rowex['bio_freetexts_count']; $i++)
        {
            $fid = db_fetch_assoc($fid_query);
            if (true)
            {
                //$_POST['tf'.$i.'t'] = strip_appoencode($_POST['tf'.$i.'t'], 2); // entfernt Tags die keine Farben sind
                $_POST['tf'.$i.'t'] = mb_substr($_POST['tf'.$i.'t'], 0, 999);
                $_POST['tf'.$i.'t'] = rtrim($_POST['tf'.$i.'t'], "`"); // Farbcodetag vom Ende entfernen

                //$_POST['tf'.$i.'b'] = strip_appoencode($_POST['tf'.$i.'b'], 2);
                $_POST['tf'.$i.'b'] = mb_substr($_POST['tf'.$i.'b'], 0, 255);

                if ($fid) // Eintrag vorhanden und wurde geändert
                {
                    db_query('UPDATE account_bio_freetexts
					                SET
					                        field_title = "'.db_real_escape_string(stripslashes($_POST['tf'.$i.'b'])).'"
					                    ,   field_value = "'.db_real_escape_string(stripslashes($_POST['tf'.$i.'t'])).'"
					                    ,   pos2 = "'.db_real_escape_string(stripslashes($_POST['tf'.$i.'p'])).'"
					                    ,   sort = "'.db_real_escape_string(stripslashes($_POST['tf'.$i.'s'])).'"
					                WHERE fieldid='.$fid['fieldid'].' LIMIT 1'); //WHERE acctid='.$session['user']['acctid'].' AND fieldid
                }
                else
                { // neu angelegt
                    if (true) // ok, Text ist nicht leer
                    {
                        db_query('INSERT INTO account_bio_freetexts
						                (acctid, field_title, field_value,pos2,sort)
						         VALUES
						                (
						                    '.intval($session['user']['acctid']).'
						                ,   "'.db_real_escape_string(stripslashes($_POST['tf'.$i.'b'])).'"
						                ,   "'.db_real_escape_string(stripslashes($_POST['tf'.$i.'t'])).'"
						                ,   "'.db_real_escape_string(stripslashes($_POST['tf'.$i.'p'])).'"
						                ,   "'.intval(stripslashes($_POST['tf'.$i.'s'])).'"
						                )'
                        );
                    }
                }
            }

            unset($_POST['tf'.$i.'b']);
            unset($_POST['tf'.$i.'t']);
            unset($_POST['tf'.$i.'p']);
            unset($_POST['tf'.$i.'s']);

            unset($session['user']['prefs']['tf'.$i.'b']);
            unset($session['user']['prefs']['tf'.$i.'t']);
            unset($session['user']['prefs']['tf'.$i.'p']);
            unset($session['user']['prefs']['tf'.$i.'s']);
        }

        //alle _POST-Variablen werden in $session['user'][prefs] gespeichert, außer die in diesem Array auf 1 gesetzten
        $nonsettings = array(
            'avatar'			=> 1,
            'bio'				=> 1,
            'bio_extra_title'	=> 1,
            'ext_ooc'		=> 1,
            'ext_rp'		=> 1,
            'ext_multis'		=> 1,
            'ext_bio_orte'      => 1,
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
            'nohof' => 1
        );

        foreach($_POST as $key => $val){
            if (!array_key_exists($key,$nonsettings)) {
                $session['user']['prefs'][$key] = stripslashes($_POST[$key]);
            }
        }

        if(isset($_POST['ext_ooc']))$array_aei_changes['ext_ooc'] 	= utf8_html_entity_decode($_POST['ext_ooc']);
        if(isset($_POST['ext_rp']))$array_aei_changes['ext_rp'] 	= utf8_html_entity_decode($_POST['ext_rp']);

        $res = db_squeryf(' SELECT DISTINCT a.name, a.acctid, a.login
					FROM account_multi am
					JOIN accounts a
					ON a.acctid<>"%d" AND (a.acctid=am.master OR a.acctid=am.slave)
					WHERE am.master="%d" OR am.slave="%d"', $Char->acctid, $Char->acctid, $Char->acctid);

        while($r = db_fetch_assoc($res))
        {
            $rowex['ext_multis'][$r['acctid'].'_show'] = $_POST['ext_multis_'.$r['acctid'].'_show'];
            $rowex['ext_multis'][$r['acctid'].'_text'] = mb_substr($_POST['ext_multis_'.$r['acctid'].'_text'],0,300);
        }

        $array_aei_changes['ext_multis'] = addslashes(utf8_serialize($rowex['ext_multis']));

        //ext_bio_orte
        $orte = db_get_all("SELECT id,name FROM rp_worlds_places WHERE acctid='".$Char->acctid."' AND parent=0 ORDER BY id ASC");
        foreach($orte as $m)
        {
            $rowex['ext_bio_orte']['o_'.$m['id'].'_show'] = $_POST['ext_bio_orte_o_'.$m['id'].'_show'];
        }
        $mitg = db_get_all("SELECT p.name AS posname, o.name AS ortname, m.acctid,m.rportid, m.id FROM rp_worlds_members AS m
                                JOIN rp_worlds_positions AS p
                                ON p.id=m.position
                                JOIN rp_worlds_places AS o
                                ON o.id=m.rportid
                                WHERE m.acctid='".$Char->acctid."'
                                ORDER BY m.id ASC
                           ");
        foreach($mitg as $m)
        {
           $rowex['ext_bio_orte']['m_'.$m['id'].'_show'] = $_POST['ext_bio_orte_m_'.$m['id'].'_show'];
        }
        $array_aei_changes['ext_bio_orte'] = addslashes(utf8_serialize($rowex['ext_bio_orte']));

        $array_aei_changes['charclass'] = closetags($_POST['charclass'],'`i`b`c`H');
        $array_aei_changes['char_birthdate'] = $_POST['char_birthdate'];

        if(  true ){
            $ext_prof['colors'] = array(
                'body'		=> CBioCleaner::outputHEX(CBioCleaner::cleanHEX($_POST['color_body'])),
                'body_text'	=> CBioCleaner::outputHEX(CBioCleaner::cleanHEX($_POST['color_body_text'])),

                'ansehen_a'=> CBioCleaner::outputHEX(CBioCleaner::cleanHEX($_POST['color_ansehen'])),
                'schonheit_a'=> CBioCleaner::outputHEX(CBioCleaner::cleanHEX($_POST['color_schonheit'])),

                'value'		=> CBioCleaner::outputHEX(CBioCleaner::cleanHEX($_POST['color_value'])),
                'head'		=> CBioCleaner::outputHEX(CBioCleaner::cleanHEX($_POST['color_head'])),
            );
        }

        $array_aei_changes['ext_profile']  	= db_real_escape_string(utf8_serialize($ext_prof));

        $array_aei_changes['together_with'] = trim($_POST['together_with']);
        $array_aei_changes['together_yesno'] = $_POST['together_yesno'];

        foreach($array_aei_changes as $k => $v)
        {
            $array_aei_changes[$k] = db_real_escape_string(stripslashes(trim($v)));
        }

        //Speichern
        user_set_aei($array_aei_changes);

        output( $msg );
        $str_message .= "`n`@`bEinstellungen gespeichert!`b`0`n";

        //Nochmals laden, damit auch die Werte aktuell sind, die gerade abgeändert wurden

        $rowex = user_get_aei(implode(',',array_keys($rowex)));

        //freie bio für alle ;)
        $rowex['bio_freetexts_count'] = 60;
        $ext_prof['bmount'] =1;
        $ext_prof['bdisciple'] = 1;
        $ext_prof['extra_info']=true;
        $ext_prof['disciple']=true;
        $ext_prof['mount']=true;

        $rowex['ext_multis'] = utf8_unserialize($rowex['ext_multis']);
        $rowex['ext_bio_orte'] = utf8_unserialize($rowex['ext_bio_orte']);
        //ext_bio_orte

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            saveuser();
            die('done');
        }

//Ende

    }	// END Einstellungen abgeschickt
}	// END Einstellungen abspeichern


output($str_message.'
	`b ( <a id="lebiolink" href="'.$biolink.'">Steckbrief ansehen</a> - <a href="' . $piclink . '">Bilderverwaltung</a> - <a href="prefs.php">Profil</a>'.' )`b
');

output('<br /><br /><table width="100" border="0" style="margin:auto;">
                            <tr>
                                <td><a href="prefs_steckbrief.php" class="motd">Steckbrief</a></td>

                                <td><a href="prefs_bio.php" class="motd">Bio</a></td>
                            </tr>
                        </table>');

output('<br /><div id="ajaxresponse" style="text-align: center;"></div>');

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
$form = array();

$form = array_merge($form,array(
    "Char,title"
,"check_acctid"	=> "AccountID,hidden"
,"charclass"		=> "Charakterklasse|?Günstig für das Rollenspiel und die Ausgestaltung deines Charakters, wenn du z.b. die Rasse noch weiter spezialisieren möchtest. Besitzt allein kosmetischen Charakter ; )"


,"rprace"		=> "(RP)Rasse in Bio/Liste überschreiben mit"

,"rpspec"		=> "(RP)Spezialgebiet in Bio überschreiben mit"

,"rptier"		=> "(RP)Tier in Bio überschreiben mit"
,"rphaus"		=> "(RP)Haus in Bio überschreiben mit (nur in dem Level ausgeblendet Modus sichtbar!)"


,'char_birthdate'	=> 'Ingame-Geburtsdatum,text,12|?Format Jahr Monat Tag. Beispiel: 20 10 19 für den 19. 10. im Jahr 20 (Aktuell: '.getgamedate().'). Negative Jahreszahlen sind erlaubt (werden als Datum vor unserer Zeit [v.u.Z.] angezeigt).'
,'birthdate_disp'	=> 'Geburtstag-Anzeige?,select,1,Alter,2,Datum,0,Beides'



,'together_with'	=> "Zusammen mit,usersearch,login|?Login-Name eintragen.<br>Mit wem ist dein Charakter zusammen, ohne gleich Verlobt zu sein.<br><b>`4Verliebt:`0</b> Gegenseite hat dich nicht eingetragen<br><b>`4Zusammen:`0</b> Gegenseite hat dich eingetragen und Partnerschaft ist (auf beiden Seiten) aktiv<br><b>`4Affäre:`0</b> Das selbe wie Zusammen, allerdings bist du verlobt oder verheiratet."
,'together_yesno'	=> "Partnerschaft?,bool|?Wirklich zusammen oder nur verliebt?"


,'v_with'	=> "Verbunden mit,usersearch,login"
,'h_with'	=> "Hass auf,usersearch,login"
,'t_with'	=> "Trauert um,usersearch,login"
,'b_with'	=> "Besessen von,usersearch,login"
,'t2_with'	=> "Träumt von,usersearch,login"

));

//Textfelder
$form = array_merge($form,array(
    "Textfelder,title"
));
$textfeld_query = db_query('SELECT * FROM account_bio_freetexts WHERE acctid='.$session['user']['acctid'].' ORDER BY fieldid ASC');

$zeile = array('Allgemeines','Interessantes','Besitz','Beziehungen','Sonstiges','Über Ava','Unter Ava','Unter Schönheit','Über Steckbrief','Unter Steckbrief');
$zeilef = ',select';
foreach ($zeile as $val) {
    $zeilef .= ','.$val.','.$val;
}

$s255 = ',select';
for($val=1;$val<=255;$val++){
    $s255 .= ','.$val.','.$val;
}

$form = array_merge($form,array('lahelpus' => "Hilfe,viewonly"));

$prefs['lahelpus'] = 'Wenn Beschriftung leer => nur Text angezeigt.
`nWenn Text leer => wird Beschriftung als neuer Abschnitt angezeigt!
`nWenn du einen Char verlinken willst gib seinen Rufnamen (Login) ohne Farbcodes (als Text) ein.
`nCharauflistung mit: LOGIN,LOGIN,LOGIN... wird zu Name, Name und Name.
`nText [LOGIN] Text [LOGIN] Text... wird zu Text Name Text Name Text.
`nText (LOGIN) Text (LOGIN) Text... wird zu Text Rufname Text Rufname Text.
`n[LOGIN|linkname] verlinkt auf einen Spieler mit frei wählbaren Link-Text (Farben erlaubt!).
';

for ($i = 1; $i <= $rowex['bio_freetexts_count']; $i++)
{
    $form = array_merge($form,array("Feld ".$i.",divider"));


    if ($textfeld = db_fetch_assoc($textfeld_query))
    {
        $prefs['tf'.$i.'b'] = stripslashes($textfeld['field_title']);
        $prefs['tf'.$i.'t'] = stripslashes($textfeld['field_value']);
        $prefs['tf'.$i.'p'] = stripslashes($textfeld['pos2']);
        $prefs['tf'.$i.'s'] = stripslashes($textfeld['sort']);

        $form = array_merge($form,array('tf'.$i.'b' => 'Beschriftung => '.appoencode($textfeld['field_title']).',text,255'));
        $form = array_merge($form,array('tf'.$i.'t' => 'eigener Text => '.appoencode(strip_tags($prefs['tf'.$i.'t'])).',text,999'));
        $form = array_merge($form,array('tf'.$i.'p' => 'Position => '.$textfeld['pos2'].''.$zeilef));
        $form = array_merge($form,array('tf'.$i.'s' => 'Sortierung => '.$textfeld['sort'].''.$s255));
    }
    else
    {
        $form = array_merge($form,array('tf'.$i.'b' => 'Beschriftung => (noch nicht gespeichert),text,255'));
        $form = array_merge($form,array('tf'.$i.'t' => 'eigener Text => (noch nicht gespeichert),text,255'));
        $form = array_merge($form,array('tf'.$i.'p' => 'Position => (noch nicht gespeichert)'.$zeilef));
        $form = array_merge($form,array('tf'.$i.'s' => 'Sortierung => (noch nicht gespeichert)'.$s255));

        $prefs['tf'.$i.'b'] = '';
        $prefs['tf'.$i.'t'] = '';
        $prefs['tf'.$i.'p'] = 'Interessantes';
        $prefs['tf'.$i.'s'] = 1;
    }
}


//Superuser dürfen alles ausblenden
$arr_su_ausblendbar = ($Char->isSuperuser() ? array('aus_info' => "Hauptinfo,bool",'aus_aufzeichnungen' => "Aufzeichnungen,bool",'aus_news' => "News,bool") : array('aus_aufzeichnungen' => "Aufzeichnungen,bool",'aus_news' => "News,bool") );

if(!isset($prefs['aus_guestbook']))$prefs['aus_guestbook'] = 1;
if(!isset($prefs['aus_ooc']))$prefs['aus_ooc'] = 1;
if(!isset($prefs['aus_rp']))$prefs['aus_rp'] = 1;
if(!isset($prefs['aus_multi']))$prefs['aus_multi'] = 1;

$form = adv_array_merge($form,array(
        "Einstellungen,title"
    ,'ausblenden' => 'Folgende Steckbrief-Tabs ausblenden:,viewonly'
    ,'aus_stammbaum' => "Stammbaum,bool"
    ,'aus_male' => "Male,bool"
    ,'aus_guestbook' => "Gästebuch,bool"
    ,'aus_ooc' => "OOC,bool"
    ,'aus_rp' => "RP-Info,bool"
    ,'aus_multi' => "Multis,bool"
    )
    ,$arr_su_ausblendbar
    ,array(    'verschoenern' => 'Steckbrief verschönern:,viewonly'
    ,'no_quest' => "Quests ausblenden,bool"
    ,'no_level' => "Level-Daten standardmäßig ausblenden,bool"
    ,'no_gesin' => "Gesinnung ausblenden,bool"
    ,'no_alter' => "Alter ausblenden,bool"
    ,'no_knappe' => "Knappe ausblenden (nur in dem Level ausgeblendet Modus!),bool"
    ,'no_orte' => "Eigene Rp-Orte ausblenden,bool"
    ,'no_mitg' => "Mitgliedschaften (RP-Orte) ausblenden,bool"
    )
);

$form = adv_array_merge($form,CSteckbriefTabs::get_prefs());

//Textfelder
$form = array_merge($form,array(
    "Multis,title"
,"Hier erscheinen nur die Multis aus der Multiverwaltung,divider"
));

$res = db_squeryf(' SELECT DISTINCT a.name, a.acctid, a.login
					FROM account_multi am
					JOIN accounts a
					ON a.acctid<>"%d" AND (a.acctid=am.master OR a.acctid=am.slave)
					WHERE am.master="%d" OR am.slave="%d"', $Char->acctid, $Char->acctid, $Char->acctid);

while($r = db_fetch_assoc($res))
{
    $form[] = strip_appoencode($r['login'],3).',divider';

    $form['ext_multis_'.$r['acctid'].'_show'] = strip_appoencode($r['login'],3).' anzeigen,bool';
    $prefs['ext_multis_'.$r['acctid'].'_show'] = $rowex['ext_multis'][$r['acctid'].'_show'];

    $form[] = 'Vorschau,preview,ext_multis_'.$r['acctid'].'_text';

    $form['ext_multis_'.$r['acctid'].'_text'] = 'Beschreibung,textarea,50,10,300';
    $prefs['ext_multis_'.$r['acctid'].'_text'] = $rowex['ext_multis'][$r['acctid'].'_text'];
}

$form[] = "RP-Orte,title";
$form[] = 'RP-Orte ausblenden?,divider';
$orte = db_get_all("SELECT name,id FROM rp_worlds_places WHERE acctid='".$Char->acctid."' AND parent=0 ORDER BY id ASC");
foreach($orte as $m)
{
    $form['ext_bio_orte_o_'.$m['id'].'_show'] = strip_appoencode($m['name'],3).' ausblenden,bool';
    $prefs['ext_bio_orte_o_'.$m['id'].'_show'] = $rowex['ext_bio_orte']['o_'.$m['id'].'_show'];
}
$form[] = 'Mitgliedschaften ausblenden?,divider';
$mitg = db_get_all("SELECT p.name AS posname, o.name AS ortname, m.acctid,m.rportid,m.id FROM rp_worlds_members AS m
                                JOIN rp_worlds_positions AS p
                                ON p.id=m.position
                                JOIN rp_worlds_places AS o
                                ON o.id=m.rportid
                                WHERE m.acctid='".$Char->acctid."'
                                ORDER BY m.id ASC
                           ");
foreach($mitg as $m)
{
    $form['ext_bio_orte_m_'.$m['id'].'_show'] = strip_appoencode($m['ortname'].' ('.$m['posname'].')',3).' ausblenden,bool';
    $prefs['ext_bio_orte_m_'.$m['id'].'_show'] = $rowex['ext_bio_orte']['m_'.$m['id'].'_show'];
}


$config = CRPBio::check();
$profi = $config['profi'] == 1;

$form[] = 'RP-Info,title';

if($profi)
{
    $form['ext_rp'] = 'Dein Text:,rawhtmleditor';
}
else
{
    $form['ext_rp'] = 'Dein Text:,textarea,60,20,'.getsetting('longbiomaxlength',4096).',true';
}


$prefs['ext_rp'] = $rowex['ext_rp'];

$form[] = 'OOC,title';

if($profi)
{
    $form['ext_ooc'] = 'Dein Text:,rawhtmleditor';
}
else
{
    $form['ext_ooc'] = 'Dein Text:,textarea,60,20,'.getsetting('longbiomaxlength',4096).',true';
}

$prefs['ext_ooc'] = $rowex['ext_ooc'];







if( true ){
    $form[] 					= 'Farben,title';

    $form[] 					= 'Alle anderen Werte werden aus der Bio-Einstellung übernommen!,divider';

    $form['color_body'] 		= 'Hintergrund-Steckbrief,hex_pick';
    $prefs['color_body'] 		= $ext_prof['colors']['body'];

    $form['color_body_text'] 	= 'Haupttextfarbe,hex_pick';
    $prefs['color_body_text'] 	= $ext_prof['colors']['body_text'];

    $form['color_value'] 		= 'Werte,hex_pick';
    $prefs['color_value'] 		= $ext_prof['colors']['value'];
    $form['color_head'] 		= 'Abschnitte,hex_pick';
    $prefs['color_head'] 		= $ext_prof['colors']['head'];

    $form[] 					= '<b>Balken</b>,viewonly';
    $form['color_ansehen'] 	= 'Ansehen,hex_pick';
    $prefs['color_ansehen'] 	= $ext_prof['colors']['ansehen_a'];
    $form['color_schonheit'] 	= 'Schönheit,hex_pick';
    $prefs['color_schonheit'] 	= $ext_prof['colors']['schonheit_a'];

    foreach($prefs as $k => $v){
        if('transparent' == $v){
            $prefs[$k] = '';
        }
    }
}

if(getsetting('history_edit_enabled',1) == 1)
{
    $prefs['history'] = appoencode(show_history(1,$session['user']['acctid'],false,true,true,true));
    $form = array_merge($form,array(
        'Aufzeichnungen,title',
        'history'=>'Eine editierbare Anzeige der Historie des Users,html'
    ));
}

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




$prefs['help'] = appoencode(get_title('Eine kleine Hilfe').'`tDiese kleine Hilfe soll dir erklären, wie du deine Texte für die Biographien deiner Charaktere, Knappen oder Tiere erstellst und dabei die meisten Probleme umschiffst. Zunächst einmal hast du die Möglichkeit deine Biographie mit unserem Editor oder ohne zu erstellen. Diese Wahl ist wichtig. Vermischst du beides wird es komplizierter (aber nicht unmöglich.)`n`n
`bDie Farbtags`b<hr />
Beginnen wir mit den wichtigsten Tags die du neben den Farbtags kennen solltest. Schreibe ein `` und direkt einen Buchstaben und alle folgenden Buchstaben werden in der Farbe angezeigt, die du gewählt hast. Beende einen Farbcode stets mit einem ``0. Du wirst später verstehen warum!`n
<ul>
<li>Zeilenumbruch: &#96;n.</li>
<li>Kursiv: &#96;i</li>
<li>Zentrieren: &#96;c</li>
<li>Fett: &#96;b</li>
</ul>
Wenn du Bilder in deine Texte einfügen möchtest, musst du diese in der Jägerhütte freischalten und anschließend
hier im Profil (unter dem Punkt "Bilder") hochladen. Um das Bild mit der Nr. 0 in den Text einzubauen, schreibst du etwa:`n
[PIC=0]`n
Du kannst auch die Bilder von Dir, Tier, Knappe und - so vorhanden - Haus einbauen!`n
<ul>
<li>Bilder 0-x: [PIC=`i0-x`i]</li>
<li>Avatar: [PIC=p]</li>
<li>Tier: [PIC=m]</li>
<li>Knappe: [PIC=d]</li>
<li>Haus: [PIC=h]</li>
</ul>
`bDer Editor`b<hr />
Sobald du den Wysiwyg - Editor anschaltest arbeitest du mit echtem HTML. Am einfachsten wäre es du verzichtest komplett auf Farb- oder Formatierungstags. Vermischst du diese mit dem Code des HTML Editors, können die Ergebnisse nicht dem entsprechen was du erwartest. (Bilder kannst du natürlich trotzdem wie oben beschrieben einfügen).
Solltest du dennoch versuchen wollen Farbtags und HTML Editor gemeinsam zu verwenden, so beherzige folgende Tipps:`n
<ul>
	<li>Schließe jedes geöffnete Farbtag mit einem ``0 ab. z.B. "`yIch bin ein ``$`$roter`0``0 Text.`t" Das erzeugt sauberen HTML Code.</li>
	<li>Verwende wenn möglich keine &lt;font&gt; Tags um Schriftgrößen, Farben oder Schriftarten auszuwählen. Verwende stattdessen das &lt;span&gt; Tag. Auch damit kannst du Größe, Farbe und Schriftart einstellen</li>
	<ul>
		<li>&lt;span style="font-size:6pt;"&gt;Text&lt/span&gt; würde die Größe des Textes verändern: <span style="font-size:12pt;">Text</span></li>
		<li>&lt;span style="color:blue; "&gt;Text&lt/span&gt; würde die Farbe des Textes verändern: <span style="color:blue">Text</span></li>
		<li>&lt;span style="font-family:Comic Sans;"&gt;Text&lt/span&gt; würde die Schriftart des Textes verändern: <span style="font-family:Comic Sans">Text</span></li>
		<li>Natürlich können diese Dinge auch miteinander kombiniert werden (beachte das ; zwischen den einzelnen Angaben)<br />&lt;span style="font-size:8pt; color:red; font-family:Courier New;"&gt;Text&lt/span&gt; würde folgenden text ergeben: <span style="font-size:8pt; color:red; fon-family:Courier New;">Text</span></li>
	</ul>
</ul>
Abschließend möchten wir dich bitten: Kopiere keinen Text aus Word in den Editor hinein. Niemand kann garantieren wie DAS dann in deiner Biographie aussieht :-)
');
$form[] = 'Hilfe,title';
$form['help'] = 'Eine kleine Hilfe,html';

// Formular anzeigen
$str_lnk = $preflink.'?op=save';
output('`n<form action="'.$str_lnk.'" method="POST" id="rpbioajax" enctype="multipart/form-data">');
showform($form,$prefs,false,'Speichern',9);
output('</form>');
// END Formular anzeigen


popup_footer();
?>