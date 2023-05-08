<?php
/*************************************************************
HUNTER'S LODGE for LoGD 0.9.7 ext (GER)
by weasel and anpera

mod by tcb - Spezielle Möbelstücke
Auslagerung der Schlüssel by Maris

 *************************************************************/

require_once 'common.php';
require_once(LIB_PATH.'house.lib.php');

$cost=array(
    'lodge_enter'    =>   0 //Mindestpunktzahl zum Betreten der Jägerhütte
,'bio_disc'        => 500 //Knappen-Bio
,'bio_extra'    => 400 //Bio Extrainfo
,'bio_long'        => 200 //verlängerte Bio
,'bio_mount'    => 600 //Tier-Bio
,'bio_textfields'    => 100 //Freibeschreibbare Textfelder für die Bio
,'castle'        => 100 //Eintritt in die Burg
,'darkhorsetavern' => 100 //Eintritt in die DarkHorse-Taverne
,'charm'        =>  20 //Charmepunkte abfragen
,'dollchange'    =>1000 //einzigartige Mumie
,'forestfights'    => 100 //zusätzliche Waldkämpfe
,'gems'            =>  25 //Edelsteine
,'golinda'        => 100 //Heilerin Golinda
,'history'        =>  0 //besonderes Ereignis
,'immun'        => 300 //PvP-Immunität
,'innstays'        =>  30 //10 Nächte in der Kneipe
,'itemcolor'    =>  25 //Möbel färben
,'keys'            =>  10 //Hausschlüssel ersetzen
,'keys_new'        => 100 //zusätzlicher Hausschlüssel
,'keys_new_gems'=>  10 //Edelsteinkosten zusätzlicher Hausschlüssel
,'namechange'    =>  0 //farbiger Name
,'namechange1'    => 0 //erstmalig farbiger Name
,'poison'        =>  20 //Truhenfallengift
,'shortcut'        => 0 //Shortcuts
,'special_item'    => 350 //einzigartiges Möbelstück
,'taunt'        => 100 //Gegnerspott
,'title'        =>  0 //eigener Titel
,'title_options' =>  0 //Titeloptionen (Titel nach hinten stellen oder ausblenden)
,'trophy'        => 200 //Präparierset
,'colorhotkey'    => 100 //benutzergefärbte Hotkeys
//edit by bathi
,'new_msg_char'        => 500 //neuer msg-Char
,'rename_msg_char'     => 200 //msg-Char umbenennen
,'recolor_msg_char'     => 50 //msg-Char umbenennen
//edit by bathi end
);
$cost=array_merge($cost,array(
    'keys_sell'            => round($cost['keys_new']*=-0.5)
,'keys_sell_gems'    => round($cost['keys_new_gems']*=0.5)
,'bio_textfields_sell'  => round($cost['bio_textfields']*-0.5)
,'colorhotkey_sell'   => round($cost['colorhotkey']*-0.5)
));

$str_filename = basename(__FILE__);

addcommentary();
page_header('Jägerhütte');



music_set('hunters_lodge');

if(isset($session['message']))
{
    output('`@'.$session['message'].'`0`n');
    unset($session['message']);
}

addnav('Zurück');
addnav('d?Zum Stadtzentrum','village.php');
if(isset($_GET['op'])) addnav('h?Zur Jägerhütte','lodge.php');

if (!$_GET['op'])
{
    addnav('Punkte','lodge.php?op=points');
}

$config = utf8_unserialize($session['user']['donationconfig']);
$pointsavailable=$session['user']['donation']-$session['user']['donationspent'];

if ($_GET['op']=='')
{
    output('`c`b`IDie Jägerhütte`0`b`c`n');

    output('`0Du folgst einem schmalen Pfad, der hinter den Ställen entlang führt. Am Ende dieses Pfades steht die Jägerhütte. Ein Türsteher stoppt dich und möchte deine Mitgliedskarte sehen. `n`n ');

    if ($session['user']['donation']>=$cost['lodge_enter'])
    {
        output('Nach dem Zeigen deiner Mitgliedskarte sagt er, "`7Sehr schön, willkommen in der J. C. Petersen Jägerhütte.  Du hast noch `$`b'.$pointsavailable.'`b`7 Punkte zur Verfügung,`0" und lässt dich rein.
		`n`nDu betrittst einen Raum, der durch einen großen Kamin am anderen Ende beherrscht wird. Die holzgetäfelten Wände werden mit Waffen, Schilden und angebrachten Jagdtrophäen einschließlich den Köpfen von einigen Drachen bedeckt, die im flackernden Licht des Kamines zu leben scheinen.
		`n`nViele hohe Stühle füllen den Raum. In dem Stuhl, der am nächsten beim Feuer ist, sitzt J. C. Petersen und liest "Alchemie Heute."
		`n`nWährend du dich näherst, hebt ein großer Jagdhund, der zu seinen Füßen liegt, den Kopf und überlegt ob er dich kennt.
		Als er dich als vertrauenswürdig einstuft, legt er sich wieder hin und schläft weiter.`n`n');
        //output('`b`4Solltest du allerdings auch nur auf die Idee kommen, die Anwesenden mit Protzereien oder Gejammer über die Anzahl deiner Punkte zu langweilen, wird er deine Mitgliedskarte genüsslich zwischen seinen rasiermesserscharfen Zähnen zerfetzen. Mindestens...`4`b`n`n');
        //output('In der Nähe ein schroffes Jägergerede:`n');
        //viewcommentary('hunterlodge','Hinzufügen',25);

        addnav('Kostenlos!');


        addnav('N?Farbiger Name','lodge.php?op=namechange');
        addnav('Titel ändern','lodge.php?op=title');
        addnav('Titeloptionen','lodge.php?op=title_options');
        addnav('~?RP-Namensänderung','lodge.php?op=rename');
        addnav('Aufzeichnungen','lodge.php?op=history');
        addnav('Auto-Aufzeichnungen färben','lodge.php?op=history_autocol');
        addnav('Aufzeichnungen um Text erweitern','lodge.php?op=history_text');
        addnav('Shortcuts kaufen','lodge.php?op=shortcut1');
        // addnav('Bio-Bilder','lodge.php?op=prof&buy=pic');

        addnav('Punkte einsetzen');
        addnav('Charmepunkte abfragen ('.$cost['charm'].' Punkte)','lodge.php?op=charm');

        addnav('10 Nächte in der Kneipe ('.$cost['innstays'].' Punkte)','lodge.php?op=innstays');
        addnav('2 Edelsteine ('.($cost['gems']*2).' Punkte)','lodge.php?op=gems');
        addnav('Extra Waldkämpfe für 30 Tage ('.$cost['forestfights'].' Punkte)','lodge.php?op=forestfights');
        addnav('H?Heilerin Golinda für 30 Tage ('.$cost['golinda'].' Punkte)','lodge.php?op=golinda');

        addnav('B?Karte zur Burg ('.$cost['castle'].' Punkte)','lodge.php?op=reiten1');
        addnav('T?Karte zur Taverne ('.$cost['darkhorsetavern'].' Punkte)','lodge.php?op=darkhorsetavern1');
        addnav('Jägerbedarf (ab 200 Punkte)','lodge.php?op=huntweapon');

        addnav('Gegnerspott '.($config['taunt']?'ändern':'('.$cost['taunt'].' Punkte)'),'lodge.php?op=taunt',false,false,false,false);
        addnav('r?Präparierset (200 Punkte)','lodge.php?op=trophy');
        addnav('Einzigartige Mumie ('.$cost['dollchange'].' Punkte)','lodge.php?op=dollchange',false,false,false,false);
        addnav('v?PvP-Immunität'.($session['user']['pvpflag'] == PVP_IMMU?'':'( '.$cost['immun'].' Punkte)'),'lodge.php?op=immun');

        //edit by bathi
        addnav('Msg-Charakter');
        addnav('Neuen Msg-Charakter kaufen ('.$cost['new_msg_char'].' Punkte)','lodge.php?op=new_msg_char');
        addnav('Msg-Charakter umbenennen ('.$cost['rename_msg_char'].' Punkte)','lodge.php?op=rename_msg_char');
        addnav('Msg-Charakter umfärben ('.$cost['recolor_msg_char'].' Punkte)','lodge.php?op=recolor_msg_char');
        //end edit by bathi

        addnav('Heimwerkerbedarf');
        if ($session['user']['house']>0)
        {
            addnav('u?Hausschlüssel','lodge.php?op=keys1');
        }
        addnav('M?Einzigartiges Möbelstück ('.$cost['special_item'].' Punkte)','lodge.php?op=item');
        addnav('f?Möbel färben ('.$cost['itemcolor'].' Punkte)','lodge.php?op=itemcolor');
        addnav('Giftphiole erwerben ('.$cost['poison'].' Punkte)','lodge.php?op=poison');



        addnav('Sonstiges');
        addnav('Selbstfärbbare Hotkeys '.($config['colorhotkey']?'ändern':'('.$cost['colorhotkey'].' Punkte)'),'lodge.php?op=colorhotkey');
    }
    else
    {
        output('Du ziehst die Karte deines Lieblingsgasthauses heraus, wo 9 von 10 Slots mit dem kleinen Profil von Cedrik abgestempelt sind.
		`n`n
		Der Türsteher schaut flüchtig auf deine Karte, rät dir nicht soviel zu trinken und weist dir den Weg zurück.');

    }
}
else if ($_GET['op']=='points') //Punkte Übersicht
{
    output('`c`b`&Punkte:`0`b`c
		`n`&Legend of the Green Dragon bietet dir die Möglichkeit, spezielle "Donationpoints" zu sammeln, mit denen du Sonderfunktionen freischalten kannst.`n
		Diese Punkte gibt es für besondere (geheime) Leistungen und für sogenannte "Referrals" (Empfehlungen). Erst wenn du mindestens '.$cost['lodge_enter'].' Donationpoint besitzt, kommst du in die Jägerhütte.`n`n
		Klicke im Eingangsbereich der Jägerhütte auf "Empfehlungen", wenn du wissen willst, wie du auf diesem Weg an Donationpoints kommst.`n');

    if(getsetting('paypal_author_enabled',1)==1) {
        output('`nWenn du den `bursprünglichern Erfinder von LoGD belohnen`b willst, kannst du pro gespendetem US-$ ebenfalls 100 Punkte kassieren.
			Schicke dazu irgendeinen Beweis deiner Spende, z.B. einen Screenshot der PayPal-Bestätigung, an `3'.getsetting('gameadminemail','').'`&.`n
			Für eine Spende an den Erfinder (Eric Stevens a.k.a. MightyE) benutze den PayPal-Author-Link, der auf jeder Seite zu finden ist.`n');
    }
    if(getsetting('paypal_server_enabled',1)==1) {
        output('`nWenn du den `bBetreiber des Servers unterstützen`b willst, kannst du pro gespendetem Euro ebenfalls 100 Punkte kassieren.
		Schicke dazu irgendeinen Beweis deiner Spende, z.B. einen Screenshot der PayPal-Bestätigung, an `3'.getsetting('gameadminemail','').'`&.`n
		Für eine Spende an den Serverbetreiber kannst du`n
		a) eine Anfrage schreiben um die Kontonummer für Überweisungen zu erfahren oder`n
		b) den PayPal-SiteAdmin-Link benutze, der auf jeder Seite zu finden ist.`n');
    }
    output('`n
	`bDas kannst du mit diesen Punkten anstellen:`b`n
	- Umsonst in der Kneipe wohnen (10 Nächte für '.$cost['innstays'].' Punkte)`n
	- Edelsteine kaufen (2 Stück für '.($cost['gems']*2).' Punkte)`n
	- Zusätzliche Waldkämpfe kaufen ('.$cost['forestfights'].' Punkte für 30 Tage lang 1 extra Kampf; maximal 5 mehr pro Tag)!`n
	- "Zur Burg" im Wald freischalten ('.$cost['castle'].' Punkte)`n
	- "Zur Taverne" im Wald freischalten ('.$cost['darkhorsetavern'].' Punkte)`n


	- Ein Präparierset kaufen (200 Punkte)`n
	- PvP-Immunität kaufen ('.$cost['immun'].' Punkte für permanente Immunität)`n


	- Anzeige der Charmepunkte ('.$cost['charm'].' Punkte)`n
	- Tödliches Gift erwerben ('.$cost['poison'].' Punkte)`n

	- Ersatzschlüssel ('.$cost['keys'].' Punkte) und zusätzliche Schlüssel ('.$cost['keys_new'].' Punkte + '.$cost['keys_new_gems'].' Edelsteine) für dein Haus kaufen.`n
	- Besondere, von dir gestaltete Möbel ('.$cost['special_item'].' Punkte) für dein Haus kaufen.`n

	`n`n`7Du hast noch `$`b'.$pointsavailable.'`b`7 Punkte von insgesamt `4'.$session['user']['donation'].' `7gesammelten Punkten übrig.
	');
}

elseif ($_GET['op']=='rename')
{
    $str_out = '`n`n`b`yNa`&me`snsände`&ru`yng:`b`n`&Möchtest du deinen `bNamen`b ändern`&, so kannst du dies hier auch ohne kompletten Verlust der weltlichen Güter tun.
`n`n`3ACHTUNG: Du verlierst dann aber deine Gildenmitgliedschaft, deinen Partner und dein Amt und es werden Einträge in deine Aufzeichnungen und Rathaus gemacht wie bisher.`n`n
`b`4WICHTIG: Zum einloggen musst du dann natürlich deinen neuen Namen verwenden ;)!`b`0`n`n';


    if(true)
    {
        $error = '';
        $old = $Char->login;

        if(isset($_POST['form_submit']))
        {
            $newname = stripslashes(trim( str_replace(range(0,9),'', strip_appoencode($_POST['newname'],3) ) ));

            // Namen in reiner Großschreibung verhindern
            if(!getsetting('allletter_up_allow',1)) {
                if(ctype_upper($newname)) {
                    $newname = mb_strtolower($newname);
                }
            }
            // 1. Buchstabe immer groß
            if(getsetting('firstletter_up',1)) {
                $newname = utf8_ucfirst($newname);
            }

            $race	 = trim($_POST['race']);
            $sex	 = intval($_POST['sex']);

            $arr_races = db_get('SELECT id FROM races WHERE id = "'.db_real_escape_string($race).'"
            AND active = "1"
            AND superuser <= "'.intval($Char->superuser).'"
            AND mindk <= "'.intval($Char->dragonkills).'"

            LIMIT 1');

            if(mb_strtolower($Char->login) == mb_strtolower($newname))
            {
                $error = 'Der Name sollte schon anders lauten...';
            }
            else if($sex < 0 || $sex > 1)
            {
                $error = 'Irgendwas stimmt mit dem Geschlecht nicht...';
            }
            else if(!isset($arr_races['id']))
            {
                $error = 'Irgendwas stimmt mit der Rasse nicht...';
            }

            if($error == '')
            {
                $tmp_rename_result = evaluate_user_rename( user_rename($Char->acctid, $newname ) );

                if(is_string($tmp_rename_result) && $tmp_rename_result != '')
                {
                    $error = $tmp_rename_result;
                }
            }


            if($error == '')
            {
                debuglog('RP-Namensänderung! Von: '.$old.' nach: '.$newname,0,true);

                $pid = $Char->acctid;

                // Eintrag in History
                addhistory('`^`b'.$old.' hat einen neuen Namen angenommen!`b',1,$pid);

                // Eintrag im Rathaus
                $sql = 'INSERT INTO boards SET section="namechange",author='.$pid.',message="Früherer Name: '.$old.'",postdate=NOW(),expire="'.date("Y-m-d H:i:s",strtotime(date("r")."+100 days")).'"';
                db_query($sql);

                // Titelreset
                $newtitle=$titles[$Char->dragonkills][$sex];
                if (empty($newtitle))
                {
                    $newtitle = $titles[sizeof($titles)-1][$sex];
                }
                user_set_aei(array('ctitle'=>'','ctitle_backup'=>''));

                // Aus Gilde entfernen, Kommentar
                if($Char->guildid > 0)
                {
                    insertcommentary(1,'/msg '.$old.' `7ist spurlos aus der Stadt verschwunden...','guild-'.$Char->guildid,1,1);
                    require_once(LIB_PATH.'dg_funcs.lib.php');
                    dg_remove_member($Char->guildid,$pid,true);
                }

                $pacctid=$Char->marriedto;

                if ($pacctid>0 && $paccitid<4294967295)
                {
                    // Erst Partner löschen
                    user_update(
                        array
                        (
                            'charisma'=>0,
                            'marriedto'=>0
                        ),
                        $pacctid
                    );
                    systemmail($pacctid,'`v'.$old.' `vist verschwunden','`v'.$old.' `vhat die Stadt verlassen. Du bist nun wieder solo!');
                }

                $Char->charisma = 0;
                $Char->marriedto = 0;

                $Char->profession = 0;
                $Char->expedition = 0;
                $Char->sex = $sex;
                $Char->title = $newtitle;
                $Char->race = $race;

                // Gesamtname aktualisieren
                user_set_name(0);
                $str_out .= '`3Du hast einen neuen Namen angenommen. Dein neuer Name lautet `#'.$newname.'`3!';
            }
        }


        if(!isset($_POST['form_submit']) || $error != '')
        {

            $str_out .= '`n`$'.$error.'`0`n';

            //Rassen abrufen
            $arr_races = db_get_all('SELECT name,id FROM races WHERE
            active = "1"
            AND superuser <= "'.intval($Char->superuser).'"
            AND mindk <= "'.intval($Char->dragonkills).'"

            ORDER BY name ASC');
            $str_form_races = ',select';
            foreach ($arr_races as $arr_race)
            {
                $str_form_races .= ','.$arr_race['id'].','.$arr_race['name'];
            }

            //Geschlecht
            $str_form_sex = ',select,0,männlich,1,weiblich';

            $arr_form = array();
            $arr_form['newname'] = '`gGib hier deinen neuen Namen ein (Ohne Farbcodes und Titel!)';
            $arr_form['race']	 = '`gDie neue Rasse'.$str_form_races;
            $arr_form['sex']	 = '`gDas neue Geschlecht'.$str_form_sex;

            $arr_data = array();
            $arr_data['newname'] = $_POST['newname'];
            $arr_data['race']	 = $_POST['race'];
            $arr_data['sex']	 = $_POST['sex'];

            $str_lnk = 'lodge.php?op=rename';
            $str_out .= form_header($str_lnk).generateform($arr_form,$arr_data).form_footer();
        }

    }


    output($str_out);
}

else if ($_GET['op']=='golinda') //Golinda
{
    output('30 Tage Zugang zu Golinda der Heilerin kosten `&'.$cost['golinda'].' Punkte`0. Golinda heilt zum halben Preis.');
    if ($pointsavailable<$cost['golinda'])
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        addnav('Bestätige Zugang zu Golinda');
        addnav('JA','lodge.php?op=golindaconfirm');
    }
}

else if ($_GET['op']=='golindaconfirm') //Golinda bestätigen
{
    if ($pointsavailable >= $cost['golinda'])
    {
        $config['healer'] += 30;
        output('J. C. Peterson gibt dir eine Karte und sagt "Mit dieser Karte kannst du an 30 verschiedenen Tagen bei Golinda vorstellig werden."');
        $session['user']['donationspent']+=$cost['golinda'];
        debuglog('Gab '.$cost['golinda'].'DP für Golinda');
    }
}

else if ($_GET['op']=='reiten1') //zur Burg
{
    if ($config['castle'])
    {
        output('Du hast diese Option bereits gekauft.');
    }
    else
    {
        output('Hiermit schaffst du dir für `&'.$cost['castle'].' Punkte`0 die Möglichkeit, im Wald mithilfe dieser Karte direkt zur Burg gelangen zu können.');
        if ($pointsavailable<$cost['castle'])
        {
            output('`n`n`$Du hast nicht genug Punkte!`0');
        }
        else
        {
            addnav('Bestätige Freischaltung');
            addnav('JA','lodge.php?op=reiten2');
        }
    }
}

else if ($_GET['op']=='reiten2') //zur Burg bestätigen
{
    if ($pointsavailable >= $cost['castle'])
    {
        $config['castle'] = 100;
        output('J. C. Peterson gibt dir eine Karte und sagt "Mit dieser Karte findest du den Weg zur Burg."');
        $session['user']['donationspent']+=$cost['castle'];
        debuglog('Gab '.$cost['castle'].'DP für Orkburg');
    }
}

else if ($_GET['op']=='darkhorsetavern1') //zur Taverne
{
    if ($config['darkhorsetavern'])
    {
        output('Du hast diese Option bereits gekauft.');
    }
    else
    {
        output('Hiermit schaffst du dir für `&'.$cost['darkhorsetavern'].' Punkte`0 die Möglichkeit, im Wald mithilfe dieser Karte direkt zur Dark-Horse-Taverne gelangen zu können.');
        if ($pointsavailable<$cost['darkhorsetavern'])
        {
            output('`n`n`$Du hast nicht genug Punkte!`0');
        }
        else
        {
            addnav('Bestätige Freischaltung');
            addnav('JA','lodge.php?op=darkhorsetavern2');
        }
    }
}

else if ($_GET['op']=='darkhorsetavern2') //zur Taverne bestätigen
{
    if ($pointsavailable >= $cost['darkhorsetavern'])
    {
        $config['darkhorsetavern'] = 100;
        output('J. C. Peterson gibt dir eine Karte und sagt "Mit dieser Karte findest du den Weg zur Taverne."');
        $session['user']['donationspent']+=$cost['darkhorsetavern'];
        debuglog('Gab '.$cost['castle'].'DP für Tavernenzugang');
    }
}

else if ($_GET['op']=='title_options') //erweiterte Titeloptionen zum Ausblenden oder hinten an stellen
{
    $rowex = user_get_aei('advanced_title_options');

    if ($rowex['advanced_title_options']==1)
    {
        output('Du hast die Optionen bereits freigeschaltet.');
    }
    else
    {
        output('Hiermit kannst du für `&'.$cost['title_options'].' Punkte`0 weiter Titeloptionen freischalten.`n
						Die erweiterten Titeloptionen geben dir die Möglichkeit deinen Titel hinter den Namen zu stellen oder vollkommen auszublenden.`n`0');
        if ($pointsavailable<$cost['title_options'])
        {
            output('`n`n`$Du hast nicht genügend Punkte!`0');
        }
        else
        {
            addnav('Bestätige Freischaltung');
            addnav('JA','lodge.php?op=title_options2');
        }
    }
}

else if ($_GET['op']=='title_options2') //erweiterte Titeloptionen bestätigen
{
    if ($pointsavailable >= $cost['title_options'])
    {
        user_set_aei(array('advanced_title_options'=>1));
        output('J. C. Peterson gewährt dir nun weitere Optionen sobald du deinen Titel änderst.');
        $session['user']['donationspent']+=$cost['title_options'];
        debuglog('Gab '.$cost['title_options'].'DP für die erweiterten Titeloptionen');
        $config['title_options']=1;
    }
}

else if ($_GET['op']=='shortcut1') //eigene RPG-Kürzel
{
    $sqlex = 'SELECT shortcuts FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
    $resex = db_query($sqlex);
    $rowex = db_fetch_assoc($resex);

    if ($rowex['shortcuts']>=9)
    {
        output('Du hast bereits 10 Shortcuts.`nMehr kannst du nicht erwerben!');
    }
    else
    {
        output('Hiermit kannst du dir für `&'.$cost['shortcut'].' Punkte`0 einen weiteren Shortcut erwerben.`n
						Shortcuts belegst du in deinen Einstellungen mit kurzen Texten (Namen, häufig verwendete Begriffe etc.) und kannst sie im RPG mit den Kürzeln %x0 - %x9 aufrufen, wodurch sie durch den von dir voreingestellten Text ersetzt werden.`nSie dürfen farbig sein, aber keine anderen Shortcuts enthalten.`n`n
						Du hast bereits `^'.($rowex['shortcuts']+1).'`& von `^10 möglichen`& Shortcuts.`n`0');
        if ($pointsavailable<$cost['shortcut'])
        {
            output('`n`n`$Du hast nicht genug Punkte!`0');
        }
        else
        {
            addnav('Bestätige Freischaltung');
            addnav('JA','lodge.php?op=shortcut2');
        }
    }
}

else if ($_GET['op']=='shortcut2') //eigenen RPG-Kürzel bestätigen
{
    if ($pointsavailable >= $cost['shortcut'])
    {
        $sql = 'UPDATE account_extra_info SET shortcuts=shortcuts+1 WHERE acctid='.$session['user']['acctid'];
        db_query($sql);
        output('J. C. Peterson gewährt dir einen weiteren Shortcut und gibt dir die Möglichkeit dich eleganter auszudrücken.');
        $session['user']['donationspent']+=$cost['shortcut'];
        debuglog('Gab '.$cost['shortcut'].'DP für Shortcuts');
        $config['shortcuts']+=1;
    }
}

else if ($_GET['op']=='textfield1') //frei beschriebbare Textfelder für die Bio
{
    $sqlex = 'SELECT bio_freetexts_count FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
    $resex = db_query($sqlex);
    $rowex = db_fetch_assoc($resex);

    $maxTextfields = getsetting('maxBioTextfields',3);

    if ($rowex['bio_freetexts_count']>=$maxTextfields)
    {
        output('Du hast bereits '.$maxTextfields.' Textfelder.`nMehr kannst du nicht erwerben!');

        addnav('Verkaufen');
        addnav('Textfeld verkaufen','lodge.php?op=textfield3');
    }
    else
    {
        output('Hiermit kannst du für `&'.$cost['bio_textfields'].' Punkte`0 ein weiteres Textfeld erwerben.`n
						Textfelder belegst du in deinen Einstellungen.`nSie dürfen farbig sein, aber keine Zeilenumbrüche enthalten.`n`n
						Du hast bereits `^'.$rowex['bio_freetexts_count'].'`0 von `^'.$maxTextfields.' möglichen`0 Textfeldern (das erste gibt es geschenkt).`n`0');
        if ($pointsavailable<$cost['bio_textfields'])
        {
            output('`n`n`$Du hast nicht genug Punkte!`0');
        }
        else
        {
            addnav('Bestätige Kauf');
            addnav('JA','lodge.php?op=textfield2');
            addnav('Verkaufen');
            addnav('Textfeld verkaufen','lodge.php?op=textfield3');
        }
    }
}

else if ($_GET['op']=='textfield2') //frei beschriebbare Textfelder für die Bio bestätigen
{
    if ($pointsavailable >= $cost['bio_textfields'])
    {
        $sql = 'UPDATE account_extra_info SET bio_freetexts_count=bio_freetexts_count+1 WHERE acctid='.$session['user']['acctid'];
        db_query($sql);
        output('J. C. Peterson gewährt dir ein weiteres Textfeld für deine Biographie.');
        $session['user']['donationspent']+=$cost['bio_textfields'];
        debuglog('Gab '.$cost['bio_textfields'].'DP für ein Textfeld für die Bio');
        $config['bio_textfields']+=1;
    }
}

else if ($_GET['op']=='textfield3') //Textfelder verkaufen
{
    $sqlex2 = 'SELECT bio_freetexts_count FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
    $resex2 = db_query($sqlex2);
    $rowex2 = db_fetch_assoc($resex2);

    $minTextfields = 1;

    if ($rowex2['bio_freetexts_count']<=$minTextfields)
    {
        output('Du hast keine zusätzlich erworbenen Textfelder mehr. Was willst du noch hier?');
    }
    else
    {
        output('Hiermit kannst du eines deiner freien Textfelder zum halben Preis verkaufen.`n
						Bist du sicher, dass du das tun willst?`n`0');

        addnav('Bestätige Kauf');
        addnav('JA','lodge.php?op=textfield4');

    }
}

else if ($_GET['op']=='textfield4') //Verkauf bestätigen
{

    $sql = 'UPDATE account_extra_info SET bio_freetexts_count=bio_freetexts_count-1 WHERE acctid='.$session['user']['acctid'];
    db_query($sql);
    output('J. C. Peterson streicht das Textfeld aus deiner Biografie.');
    $session['user']['donationspent']+=$cost['bio_textfields_sell'];
    debuglog('Verkaufte ein Textfeld für '.$cost['bio_textfields_sell'].'');
    $config['bio_textfields']-=1;

}

else if ($_GET['op']=='forestfights') //zusätzliche Waldkämpfe
{
    if (!is_array($config['forestfights']))
    {
        $config['forestfights']=array();
    }
    output('1 Extra Waldkampf pro Tag für 30 Tage kostet `&'.$cost['forestfights'].' Punkte`0. Du bekommst einen extra Waldkampf an jedem Tag, an dem du spielst.`n');
    if ($pointsavailable<$cost['forestfights'])
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        addnav('Bestätige Extra Waldkämpfe');
        addnav('JA','lodge.php?op=fightbuy');
    }
    reset($config['forestfights']);
    foreach($config['forestfights'] as $key=>$val)
    {
        output("Du hast noch ".$val['left']." Tage, an denen zu einen zusätzlichen Waldkampf für deine am ".$val['bought']." ausgegebenen Punkte bekommst.`n");
    }
}

else if ($_GET['op']=='fightbuy') //zusätzliche Waldkämpfe bestätigen
{
    if (count($config['forestfights'])>=5)
    {
        output('Du kannst pro Tag maximal 5 extra Waldkämpfe haben.`n');
    }
    else
    {
        if ($pointsavailable>$cost['forestfights'])
        {
            array_push($config['forestfights'],array('left'=>30,'bought'=>date('M d')));
            output('Du wirst in den nächsten 30 Tagen, an denen du spielst, einen extra Waldkampf haben.');
            $session['user']['donationspent']+=$cost['forestfights'];
            debuglog('Gab '.$cost['forestfights'].'DP für extra Waldkampf');
        }
        else
        {
            output('Extra Waldkämpfe zu kaufen kostet '.$cost['forestfights'].' Punkte, aber du hast nicht so viele.');
        }
    }
}

else if ($_GET['op']=='innstays') //Übernachtungen in der Kneipe kaufen
{
    output('10 freie Übernachtungen in der Kneipe kosten `&'.$cost['innstays'].' Punkte`0. Bist du dir sicher, dass du das willst?');
    if ($pointsavailable<$cost['innstays'])
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        addnav('Bestätige 10 freie Übernachtungen');
        addnav('JA','lodge.php?op=innconfirm');
    }
}

else if ($_GET['op']=='innconfirm') //Übernachtungen in der Kneipe bestätigen
{
    if ($pointsavailable>=$cost['innstays'])
    {
        output('J. C. Petersen gibt dir eine Karte und sagt "Coupon: Gut für 10 Übernachtungen in der Schenke Zum Eberkopf"');
        $config['innstays']+=10;
        $session['user']['donationspent']+=$cost['innstays'];
        debuglog('Gab '.$cost['innstays'].'DP für Schlafen in Kneipe');
    }
}

else if ($_GET['op']=='charm') //Anzeige Charmepunkte
{
    output('Du fragst J. C. Petersen, ob er dein Aussehen beurteilen kann. Er mustert dich kurz und verspricht dir dann, dass er dir für die Kleinigkeit von `&'.$cost['charm'].' Punkten`0 eine ehrliche Antwort geben wird.');
    if ($pointsavailable<$cost['charm'])
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        addnav('Bestätige Charmepunkt-Anzeige');
        addnav('JA','lodge.php?op=charmconfirm');
    }
}

else if ($_GET['op']=='charmconfirm') //Charmepunkte bestätigen
{
    if ($pointsavailable>=$cost['charm'])
    {
        if ($session['user']['charm']<=0)
        {
            output('J. C. Petersen schaut dich angewidert an und sagt "`7Du bist hässlich wie die Nacht, ich kann einfach nichts Schönes an dir finden.`0"');
        }
        else if ($session['user']['charm']==1)
        {
            output('J. C. Petersen schaut dich kurz an und sagt "`7Du bist genauso häßlich wie jeder gemeine Bürger, mehr als `^1 Punkt`0 wird dir kein Preisrichter geben.`0"');
        }
        else
        {
            $max_charm = db_fetch_assoc(db_query('SELECT acctid,charm FROM accounts WHERE sex='.$session['user']['sex'].' ORDER BY charm DESC LIMIT 1'));
            $max_charm=max(1,$max_charm['charm']);
            $rel_charm=round($session['user']['charm'] * 10000 / $max_charm);
            $rel_charm/=100;
            output('J. C. Petersen mustert dich noch einmal ganz genau und sagt "`7Du bist `^'.$session['user']['charm'].'`7mal so schön wie der gemeine Bürger. Das sind etwa '.$rel_charm.' Prozent '.($session['user']['sex']?'von der':'vom').' derzeit Schönsten.`0"');
        }
        $session['user']['donationspent']+=$cost['charm'];
        debuglog('Gab '.$cost['charm'].'DP für Charmepunktanzeige');
    }
}

else if ($_GET['op']=='poison') //Truhenfallengift kaufen
{
    output('Du fragst J. C. Petersen frei heraus, ob er dir nicht etwas seines tödlichen und verbotenen Giftes aushändigen kann. Sofort packt er dich am Kragen und hält dir den Mund zu, dann zieht er dich in eine Ecke und gibt dir zu verstehen, dass dich eine Phiole `&'.$cost['poison'].' Punkte`0 kosten wird und 3 Ladungen enthält. Weiterhin macht er dir klar, dass dir sein Jagdhund dorthin beissen wird, wo es besonders weh tut, solltest du noch einmal auf die Idee kommen dieses Thema laut anzusprechen.');
    if ($pointsavailable<$cost['poison'])
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        addnav('Bestätige Erwerb von Gift');
        addnav('JA','lodge.php?op=poisonconfirm');
    }
}

else if ($_GET['op']=='poisonconfirm') //Truhenfallengift bestätigen
{
    if ($pointsavailable>=$cost['poison'])
    {
        output('Petersen öffnet ein kleines Wandschränkchen und holt eine winzige Phiole mit grünem Inhalt heraus.`nDieses Gift reicht für 3 Ladungen, schau dir einfach eine Truhenfalle deiner Wahl im Haus an und fülle sie damit auf!`n');
        item_add($session['user']['acctid'],'gftph');
        $session['user']['donationspent']+=$cost['poison'];
        debuglog('Gab '.$cost['poison'].'DP für Truhengift');
    }
}

else if ($_GET['op']=='gems') //Edelsteine kaufen
{
    output('2 Edelsteine für `&'.($cost['gems']*2).' Punkte`0. Bist du dir sicher, dass du das willst?');
    if ($pointsavailable<$cost['gems']*2)
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        addnav('Bestätige 2 Edelsteine');
        addnav('JA','lodge.php?op=gemsconfirm');
        if($pointsavailable>$cost['gems']*3)
        {
            allownav('lodge.php?op=gemsconfirm');
            output('`n`nDu kannst auch größere Mengen (bis zu '.floor($pointsavailable/$cost['gems']).') kaufen.
												<form action="lodge.php?op=gemsconfirm" method="post">
												<input type="text" name="amount" size=3 maxlength=3>
												<input type="submit" class="button" value="Edelsteine kaufen">
												</form>');
        }
    }
}

else if ($_GET['op']=='gemsconfirm') //Edelsteinkauf bestätigen
{
    $amount=max(2,intval($_POST['amount']));
    $dp_cost=$amount*$cost['gems'];

    if ($pointsavailable>=$dp_cost)
    {
        output('J. C. Petersen gibt dir '.$amount.' Edelsteine und sagt "Damit, mein Freund, wird Dein Leben leichter werden"');
        $session['user']['gems']+=$amount;
        $session['user']['donationspent']+=$dp_cost;
        debuglog('Gab '.$dp_cost.'DP für Edelsteine');
    }
    else
    {
        output('J. C. Petersen nimmt einen Zettel mit der Überschrift "`$Schlechtschein`0", schreibt deinen Namen und die Zahl '.($dp_cost-$pointsavailable).' darauf.
											`nEin Schlechtschein!? Es gibt doch gar keinen Schlechtschein! Da fällt dir ein, dass du dir für '.$pointsavailable.' Punkte ja `^höchstens '.floor($pointsavailable/$cost['gems']).'`0 Edelsteine leisten kannst.');
        addnav('Nochmal versuchen','lodge.php?op=gems');
    }
}

else if ($_GET['op'] == 'title') //eigener Titel
{
    $arr_tmp = user_get_aei('ctitle, title_postorder, title_hide, advanced_title_options');
    $str_ctitle = $arr_tmp['ctitle'];
    $postorder = $arr_tmp['title_postorder'];
    $hide = $arr_tmp['title_hide'];
    $advanced_options = ($arr_tmp['advanced_title_options']==1? true : false);
    unset($arr_tmp);

    output('`c`bTitel ändern`b`c`n
											Hier darfst du dir einen einzigartigen Titel geben, der vor deinem Loginnamen angezeigt werden wird.`n
											Natürlich gilt es, einige elementare Hinweise zu beachten:`n
											`$Es ist nicht erlaubt, offizielle Spieltitel (Bauernjunge etc.) anzunehmen, um sich damit durch Täuschung anderer Spieler Vorteile zu erschleichen, das heißt, offizielle Spieltitel müssen mit einem Farbverlauf gestaltet werden.`&`n
											Weiterhin sollte der Titel natürlich den Regeln (keine Beleidigungen etc.) entsprechen.`n`n');

    if ($advanced_options) {
        output('`0Da du die weiteren Titeloptionen freigeschaltet hast, kannst du nun auch festlegen dass dein Titel hinter deinem Namen angezeigt wird, '
            .'zum Beispiel um ihn als Nachnamen zu nutzen.`n'
            .'Du kannst auch durch die Verwendung von %s% genau festlegen, wo dein Name eingebaut werden soll. zB: Titel %s% Nachname.`n'
            .'Außerdem hast du die Möglichkeit den Titel vollständig auszublenden, wenn du keine Verwendung dafür hast.`n`n');
    }

    if($_GET['finished'])
    {
        output('`n`n`c`@`b');

        if(!empty($str_ctitle))
        {
            output('Gratulation, du besitzt hiermit den eigenen Titel '.$str_ctitle.'`@!`n');
        }
        else
        {
            output('Du setzt deinen Titel zurück auf `&'.$session['user']['title'].'`@!`n');
        }

        output('Zusammen ergibt das '.$session['user']['name'].'`@!`b`c`0`n`n');

        $session['user']['donationspent'] += $cost['title'];
        debuglog('Gab '.$cost['title'].'DP für eigenen Titel');

        page_footer();
        exit;
    }

    output('Den Titel zu ändern kostet '.$cost['title'].' Punkte.');

    if($pointsavailable < $cost['title'])
    {
        output('`nLeider verfügst du über zu wenig Punkte, um dir das leisten zu können!');
        page_footer();
        exit;
    }

    output('`n`n`0Wie soll dein eigener Titel aussehen? (Lasse das Feld leer, um deinen normalen Titel '.$session['user']['title'].'`0 wiederherzustellen)`n`n');

    $str_newtitle = stripslashes($_POST['newtitle']);

    if(isset($_POST['newtitle']))
    {

        $str_msg = '';

        $str_newtitle = str_replace('`0','',$str_newtitle);
        // Alle anderen Tags als erlaubte Farbcodes rausschmeißen
        $str_newtitle = utf8_preg_replace('/[`][^'.regex_appoencode(1,false).']/','',$str_newtitle);

        output('Du wählst: `b'.$str_newtitle.'`b`n`n');

        // Auf was wollen wir alles kontrollieren (Standard reicht hier nicht aus)?
        $int_options = USER_NAME_BADWORD | USER_NAME_BLACKLIST | USER_NAME_EXCLUSIVE_TITLE | USER_NAME_NOCHANGE;// | USER_NAME_OFFICIALTITLE;

        $str_result = user_retitle(0,false,$str_newtitle,true,$int_options);

        if(mb_strpos($str_newtitle,'"') !== false)
        {
            output('`c`n`n`$`bDein Titel darf kein " enthalten!`b`n`n`c');
        }
        else if(true !== $str_result)
        {

            switch($str_result)
            {

                case 'ctitle_blacklist':
                    $str_msg .= 'Diesen Titel darfst du leider nicht wählen, da er von den Göttern verboten wurde.`n';
                    break;

                case 'ctitle_tooshort':
                    $str_msg .= 'Dieser Titel ist zu kurz (Mindestens '.getsetting('titleminlen',3).' Zeichen).`n';
                    break;

                case 'ctitle_toolong':
                    $str_msg .= 'Dieser Titel ist zu lang (Maximal '.getsetting('titlemaxlen',25).' Zeichen).`n';
                    break;

                case 'ctitle_badword':
                    $str_msg .= 'Dieser Titel enthält verbotene oder anstößige Wörter.`n';
                    break;

                case 'ctitle_officialtitle':
                case 'ctitle_exclusive':
                    $str_msg .= 'Diesen Titel darfst du nicht nehmen.`n';
                    break;

                case 'ctitle_changeforbidden':
                    $str_msg .= 'Deinen aktuellen Titel darfst du leider nicht auf diese Weise ändern.`n';
                    break;

                case 'ctitle_toomuchcolors':
                    $str_msg .= 'Dein gewählter Titel enthält zu viele Farbcodes. Maximal erlaubt sind '.getsetting('title_maxcolors',7).'.`n';
                    break;

                default:
                    $str_msg .= '';
                    break;

            }

            output($str_msg);

        }
        else
        {
            if ($advanced_options) {
                if(empty($_POST['newtitle'])) { // zurücksetzen auf Standard Titel -> Titel (änderbar durch Ereignisse) immer vorne anzeigen
                    user_set_aei(array('title_postorder'=>false,'title_hide'=>$_POST['title_hide']));
                } else {
                    user_set_aei(array('title_postorder'=>$_POST['title_postorder'],'title_hide'=>$_POST['title_hide']));
                }
            }
            user_set_name(0);
            redirect('lodge.php?op=title&finished=1');
        }

    }
    else
    {
        $str_newtitle = (!empty($str_ctitle) ? $str_ctitle : '');
    }

    $str_lnk = 'lodge.php?op=title';
    allownav($str_lnk);

    $arr_form = array(
        'newtitle'=>'Dein neuer Titel mit oder ohne Farbcodes:'
    );
    $arr_data = array(
        'newtitle'=>$str_newtitle
    );

    if ($advanced_options) {
        $arr_form['title_postorder'] = 'Nachname statt Titel,bool|?Ist diese Option gewählt, wird der Titel hinter dem Namen angezeigt.';
        $arr_form['title_hide'] = 'Keinen Titel,bool|?Ist diese Option gewählt, wird für den Charakter nurnoch der Name angezeigt.';
        $arr_data['title_postorder'] = $postorder;
        $arr_data['title_hide'] = $hide;
    }

    output('`&Vorschau: ');
    rawoutput(js_preview('newtitle'));

    output(form_header($str_lnk,'POST',true,'',$str_onsubmit));

    showform($arr_form,$arr_data,false,'Einstellungen übernehmen!');

    output(form_footer());

}

else if ($_GET['op']=='namechange') //Name färben
{
    output('`c`bNamensfarbe ändern`b`c`n');

    $arr_tmp = user_get_aei('cname');
    $str_cname = $arr_tmp['cname'];
    unset($arr_tmp);

    if ($config['namechange']==1)
    {
        $int_cost = $cost['namechange'];
        output('Da du schon vorher viele Punkte für die Farbänderung gegeben hast kostet es dich diesmal nur `&'.$cost['namechange'].' Punkte`0.');
    }
    else
    {
        $int_cost = $cost['namechange1'];
        output('Da es deine erste Farbänderung ist kostet es dich `&'.$cost['namechange1'].' Punkte`0. Beim nächsten Wechsel fallen nur '.$cost['namechange'].' Punkte Kosten an.');
    }

    if($_GET['finished'])
    {
        output('`n`n`c`@`bGratulation, '.(!empty($str_cname) ? 'du wählst dir den farbigen Namen '.$str_cname : 'du setzt deinen Namen farblich zurück').'`2!`b`c`0`n`n');

        $session['user']['donationspent'] += $int_cost;
        debuglog('Gab '.$int_cost.'DP für farbigen Namen');

        $config['namechange']=1;

        $session['user']['donationconfig'] = utf8_serialize($config);

        page_footer();
        exit;
    }

    if($pointsavailable < $int_cost)
    {
        output('`nLeider verfügst du über zu wenig Punkte, um dir das leisten zu können!');
        page_footer();
        exit;
    }

    output('`n`nDein geänderter Name muss der selbe Name sein wie vor der Farbänderung, nur dass er jetzt die Farbcodes enthalten darf.`n`n');

    if(!empty($str_cname))
    {
        output('Dein farbiger Name bisher ist: ');
        $output.=$str_cname;
        output(', und so wird er aussehen: '.$str_cname);
    }
    else

    {
        output('Bisher besitzt du keinen farbigen Namen!');
    }

    output('`n`n`0Wie soll dein farbiger Name in Zukunft aussehen?`n`n');

    $str_newname = stripslashes($_POST['newname']);

    if(!empty($str_newname))
    {

        $str_msg = '';

        $str_newname = str_replace('`0','',$str_newname);
        // Alle anderen Tags als erlaubte Farbcodes rausschmeißen
        $str_newname = utf8_preg_replace('/[`][^'.regex_appoencode(1,false).']/','',$str_newname);

        output('Du wählst: `b'.$str_newname.'`b`n`n');

        $str_result = evaluate_user_rename(user_rename(0,$str_newname,true,false,USER_NAME_NOCHANGE|USER_NAME_FIRST_LOWER|USER_NAME_ALL_UPPER));


        if(true !== $str_result)
        {
            output('`$'.$str_result.'`0`n');
        }
        else
        {
            user_set_name(0);
            redirect('lodge.php?op=namechange&finished=1');
        }

    }
    else
    {
        $str_newname = (!empty($str_cname) ? $str_cname : $session['user']['login']);
    }

    $str_lnk = 'lodge.php?op=namechange';
    allownav($str_lnk);

    $arr_form = array('newname'=>'Dein neuer Name mit Farbcodes:');
    $arr_data = array('newname'=>$str_newname);

    $str_onsubmit = 'if(document.getElementById(\'newname\').value.length == 0) { alert(\'Also leer soll dein Name doch nicht sein, oder?\'); return false; } else { return true; }';

    output('`&Vorschau: ');
    rawoutput(js_preview('newname'));

    output(form_header($str_lnk,'POST',true,'',$str_onsubmit));

    showform($arr_form,$arr_data,false,'Diese Färbung übernehmen!');

    output(form_footer());

}

else if ($_GET['op']=='immun') //PvP-Immunität kaufen
{
    // HOT Items
    $bool_hot = (bool)item_count(' hot_item>0 AND owner='.$session['user']['acctid'].' AND deposit1=0 ',true);

    $crimedate=date("Y-m-d H:i:s",time()-(getsetting('pvpimmu_daysaftercrime',7)*86400));
    $row=user_get_aei('last_crime');

    if ($session['user']['pvpflag']==PVP_IMMU)
    {
        output('J. C. Petersen nickt dir zu und gibt dir zu verstehen, dass du noch immer unter seinem Schutz stehst.');
        if($bool_hot) {
            output('`nJedoch trägst du da etwas bei dir, das diesen Schutz beeinträchtigen könnte..');
        }
        if(getsetting('pvp_immu_return',0) == 1)
        {
            addnav('Immunität `iaufgeben`i?','lodge.php?op=immunlose',false,false,false,false,'Möchtest Du deine PvP-Immunität wirklich aufheben?');
        }
    }
    else if (($session['user']['pvpflag']=='1986-10-06 00:42:00' && $session['user']['marks'] & CHOSEN_FULL < CHOSEN_FULL) || $row['last_crime'] > $crimedate)
    {
        output('J. C. Petersen zeigt dir einen Vogel und macht dir sehr schnell klar, dass er vorerst nichts mehr für dich tun kann. Er kann niemanden schützen, der selbst mordend durchs Land zieht.');
    }
    else
    {
        output('Du fragst J. C. Petersen, ob er deinen Aufenthaltsort vor herumstreifenden Dieben und Mördern verbergen kann.
									Er nickt und verspricht dir, dass dir für die Kleinigkeit von `&'.$cost['immun'].' Punkten`0 niemand mehr ein Haar krümmen wird. Er wird auch mit Dag Durnick reden. Allerdings kann er für nichts mehr garantieren, wenn du selbst einen Mord begehst!`n`n');
        if($bool_hot) {
            output('`nAußerdem trägst du da etwas bei dir, das diesen Schutz beeinträchtigen könnte..`n`n');
        }
        output($cost['immun'].' Punkte für permanente PvP Immunität ausgeben?
									`n(Die Immunität verfällt, sobald du selbst PvP machst, oder ein Kopfgeld auf jemanden aussetzt und kann dann `bnicht`b mehr so schnell erneuert werden!)');
        addnav('Immunität bestätigen?');
        addnav('JA','lodge.php?op=immunconfirm');
    }
}

else if ($_GET['op']=='immunconfirm') //PvP-Immu bestätigen
{
    if ($pointsavailable>=$cost['immun'])
    {
        output('J. C. Petersen nutzt seinen Einfluss, um dich für PvP-Spieler unangreifbar zu machen. Es kann auch kein (weiteres) Kopfgeld auf dich ausgesetzt werden.`nDenke daran, dass du nur so lange geschützt bist, bis du selbst jemanden angreifst, oder jemanden auf Dag\'s ');
        output(' Kopfgeldliste setzt. Tust du das, kann selbst Petersen dir in Zukunft nicht mehr helfen.');
        $session['user']['pvpflag']=PVP_IMMU;
        $session['user']['donationspent']+=$cost['immun'];
        debuglog('Gab '.$cost['immun'].'DP für PvP-Immu');
    }
    else
    {
        output('Du hast nicht genug Punkte!');
    }
}

else if ($_GET['op']=='immunlose') //PvP-Immu rückgängig
{
    output('
								J.C.Petersen schaut dich entrüstet an, holt aber eine kleine Flasche aus einem Schränkchen.`n
								Er sagte noch etwas wie `iEigentlich ja nur für mich`i und übergießt dich mit ein wenig davon...`n
								`n
								Du merkst langsam wie sein Schutz nicht mehr wirkt...
								');
    $session['user']['pvpflag'] = "0000-00-00 00:00:00";
    debuglog('Gab seine PvP-Immunität bei J.C. Petersen `bfreiwillig`b auf!');
}

else if ($_GET['op']=='keys1') //Hausschlüssel kaufen/ersetzen
{
    $sql = 'SELECT k.*,a.acctid FROM keylist k
									LEFT JOIN accounts a ON a.acctid=k.owner
									WHERE value1='.$session['user']['house'].' AND type='.HOUSES_KEY_DEFAULT.' ORDER BY id ASC';
    $result = db_query($sql);
    $keycount = db_num_rows($result);
    $lost = array();

    while ($k = db_fetch_assoc($result))
    {
        if ($k['owner'] == 0 || $k['acctid'] == 0)
        {
            $lost[] = $k;
        }
    }

    if (sizeof($lost))
    {
        output("`c`b`&Verlorene Schlüssel:`0`b`c
									`n<table cellpadding=2 align='center'>
									<tr class='trhead'>
									<th>Nr.</th>
									<th>Aktion</th>
									</tr>",true);
        for ($i=0; $i<sizeof($lost); $i++)
        {
            $row = $lost[$i];
            $bgcolor=($i%2==1?"trlight":"trdark");
            output("<tr class='$bgcolor'>
										<td>".$session['user']['house']."</td>
										<td>".create_lnk('Ersetzen ('.$cost['keys'].' Punkte)','lodge.php?op=keys2&id='.$row['id'])."</td>
										</tr>",true);
            allownav('lodge.php?op=keys2&id='.$row['id']);
        }
        output("</table>`n",true);
    }
    else
    {
        output('Der Schlüsselsatz für dein Haus ist komplett. ');
    }

    $sql = 'SELECT status,build_state FROM houses WHERE owner='.$session['user']['acctid'];
    $res = db_query($sql);

    $house = db_fetch_assoc($res);
    if ($keycount<house_get_max_keys($house['status']))
    {
        output('Willst du einen zusätzlichen Schlüssel für '.$cost['keys_new'].' Punkte und '.$cost['keys_new_gems'].' Edelsteine kaufen?');
        addnav('Neu kaufen');
        addnav('Zusätzlicher Schlüssel ('.$cost['keys_new'].'&nbsp;Punkte + '.$cost['keys_new_gems'].'&nbsp;Edelsteine)','lodge.php?op=keys2&id=new');
    }
    else
    {
        output('Du hast alle Schlüssel und vergrößern kannst du dein '.get_house_state($house['status'],$house['build_state'],false).' auch nicht!');
    }
    $free_keys = db_num_rows(db_query('SELECT * FROM keylist WHERE value1='.$Char->house.' AND type='.HOUSES_KEY_DEFAULT.' AND owner='.$Char->acctid.' ORDER BY id ASC'));
    if ($free_keys>0)
    {
        addnav('Schlüssel verkaufen','lodge.php?op=keys4');
    }
}

else if ($_GET['op']=='keys2') //Bestätigung Schlüssel kaufen
{
    if ($_GET['id']=='new')
    {
        output('`b'.$cost['keys_new'].'`b Punkte und `#'.$cost['keys_new_gems'].' Edelsteine`0');
    }
    else
    {
        output('`b'.$cost['keys'].'`b Punkte');
    }
    output(' für diesen Schlüssel ausgeben?');
    addnav('Schlüsselkauf bestätigen?');
    addnav('JA','lodge.php?op=keys3&id='.$_GET['id']);
}

else if ($_GET['op']=='keys3') //Schlüsselkauf abschließen
{
    if ($_GET['id']=='new')
    {
        if ($pointsavailable<$cost['keys_new'])
        {
            output('Du hast nicht genug Punkte übrig.');
        }
        else if ($session['user']['gems']<$cost['keys_new_gems'])
        {
            output('Du hast nicht genug Edelsteine dabei.');
        }
        else
        {
            $sql = 'SELECT * FROM keylist WHERE value1='.$session['user']['house'].' AND type='.HOUSES_KEY_DEFAULT.' ORDER BY id ASC';
            $result = db_query($sql);
            $nummer=db_num_rows($result)+1;
            db_free_result($result);
            $sql='INSERT INTO keylist (owner,value1) VALUES ('.$session['user']['acctid'].','.$session['user']['house'].')';
            db_query($sql);
            $session['user']['donationspent']+=$cost['keys_new'];
            $session['user']['gems']-=$cost['keys_new_gems'];
            debuglog('Gab '.$cost['keys_new'].'DP+'.$cost['keys_new_gems'].'ES für Hausschlüssel');
            output("Du hast jetzt `b$nummer`b Schlüssel für dein Haus! Überlege gut, an wen du sie vergibst.");
        }
    }
    else
    {
        if ($pointsavailable<$cost['keys'])
        {
            output("Du hast nicht genug Punkte übrig.");
        }
        else
        {
            $nummer=$_GET['id'];
            $sql="UPDATE keylist SET owner=".$session['user']['acctid'].",hvalue=0,chestlock=0,gold=0,gems=0 WHERE id=$nummer";
            db_query($sql);
            $session['user']['donationspent']+=$cost['keys'];
            debuglog('Gab '.$cost['keys'].'DP für Ersatzschlüssel');
            output("Der Schlüssel wurde ersetzt.");
        }
    }
}

else if ($_GET['op']=='keys4') //Schlüssel verkaufen
{
    output('Möchtest du wirklich einen deiner Hausschlüssel zum halben Preis ('.$cost['keys_sell'].' DP und '.$cost['keys_sell_gems'].' Edelsteine) wieder verkaufen?');
    addnav('Schlüsselverkauf bestätigen?');
    addnav('JA','lodge.php?op=keys5');
}

else if ($_GET['op']=='keys5') //Bestätigung Schlüssel verkaufen
{
    $sql='SELECT * FROM keylist WHERE value1='.$Char->house.' AND type='.HOUSES_KEY_DEFAULT.' AND owner='.$Char->acctid.' ORDER BY id ASC';
    $result = db_query($sql);

    if (db_num_rows($result)>0)
    {
        $sql='DELETE FROM keylist WHERE value1='.$Char->house.' AND type='.HOUSES_KEY_DEFAULT.' AND owner='.$Char->acctid.' ORDER BY id ASC LIMIT 1';
        db_query($sql);
        $pointsavailable -= $cost['keys_sell'];
        $session['user']['donationspent'] += $cost['keys_sell'];
        $Char->gems+=$cost['keys_sell_gems'];
        debuglog('Verkaufte einen Schlüssel für '.$cost['keys_sell'].' DP und '.$cost['keys_sell_gems'].' ES.');
        $nummer = db_num_rows(db_query('SELECT * FROM keylist WHERE value1='.$Char->house.' AND type='.HOUSES_KEY_DEFAULT.' ORDER BY id ASC'));
        output("Du besitzt jetzt nur noch `b$nummer`b Schlüssel für dein Haus! Überlege gut, an wen du sie vergibst.");
    }
    else
    {
        output('Du hast momentan keine freien Schlüssel, die du verkaufen könntest. Du musst sie erst den Besitzern abnehmen.');
    }
}

else if ($_GET['op'] == 'item') //einzigartiges Möbelstück
{
    $max_zeichen = 250 + $_GET['gems']*10;

    if ($_GET['gems']) $gem_text = ' und `b'.$_GET['gems'].'`b Edelsteine';

    $res = item_list_get(" tpl_id='unikat' AND owner=".$session['user']['acctid'],'',false);
    $anzahl = db_num_rows($res);

    output('Hier hast du die Möglichkeit, Dir für '.$cost['special_item'].' Punkte'.$gem_text.' ein einzigartiges, nach deinen Wünschen gestaltetes Möbelstück fertigen zu lassen. Für besonders ausführliche Wünsche verlangt Petersen eine zusätzliche Bezahlung in Edelsteinen.`n');
    output('Außerdem bietet Petersen dir auch an, dieses Möbelstück an andere Einwohner '.getsetting('townname','Atrahor').'s zu versenden.`n');
    if ($pointsavailable >= $cost['special_item'])
    {
        $arr_form = array(
            'name_prev' => ',preview,name',
            'name' => 'Name des Möbelstücks,text,90',
            'desc_prev' => ',preview,desc',
            'desc' => 'Beschreibung: ('.$max_zeichen.')`n,textarea,40,8,'.$max_zeichen);

        if((date('m')==12 && isBetween(23,date('d'),27)))
        {
            $arr_form['sendasxmasgift'] = 'Als Weihnachtsgeschenk versenden,bool|?Stellt dem Namen des Unikates ein "Weihnachtsgeschenk" voran';
        }

        output('`n`nPetersen benötigt nun die folgenden Informationen von dir:
												`n`n`0'.
            form_header('lodge.php?op=item_confirm&gems='.$_GET['gems']).
            generateform($arr_form,array(),false,'Kaufen').
            form_footer());

        addnav('Unikate bearbeiten');
        addnav('Beschreibungstext für Edelsteine verlängern','lodge.php?op=item_chars');
        addnav('Ein Unikat modifizieren','lodge.php?op=item_change');
    }
    else
    {
        output('`n`nLeider hast du nicht genug Punkte.`n');
        addnav('Ein Unikat modifizieren','lodge.php?op=item_change');
    }
    output('`n<hr>Bisher wurden für dich '.$anzahl.' besondere'.($anzahl==1?'s':'').' Möbel hergestellt:`n');
    while ($item = db_fetch_assoc($res))
    {
        output('`n`i'.$item['name'].'`i');
    }
    output ('`n`n');

}
else if ($_GET['op'] == 'item_chars') //einzigartiges Möbelstück, Beschreibungstext verlängern
{
    output('Für 1 Edelstein kannst du 10 Zeichen zum Beschreibungstext deines Unikates dazukaufen, maximal jedoch die Länge von 250 auf 500 Zeichen verdoppeln. Wie viele Edelsteine willst du dafür ausgeben?`n`n'.
        form_header('lodge.php?op=char_confirm').generateform(array('gems'=>'Wie viele Edelsteine ausgeben?,int'),array(),false,'Bestätigen').'</form>');

    addnav('Mehr Zeichen?');
    addnav('Doch nicht','lodge.php?op=item');
}

else if ($_GET['op'] == 'char_confirm') //Bestätigung Beschreibungstext verlängern
{
    $int_gems=intval($_POST['gems']);
    if ($_POST['gems'] && ($int_gems < 1 || $int_gems > 25))
    {
        output('`b`$'.$_POST['gems'].' ist kein gültiger Wert!`0`b`n`nDu musst mindestens 1 und kannst höchstens 25 Edelsteine zum Verlängern des Beschreibungstextes ausgeben.');
        addnav('Mehr Zeichen?');
        addnav('Nochmal versuchen','lodge.php?op=item_chars');
        addnav('Doch nicht','lodge.php?op=item');
    }
    else if ($int_gems > $session['user']['gems'])
    {
        output('`b`$Du hast keine '.$int_gems.' Edelsteine!`0`b`n`nDu musst mindestens 1 und kannst höchstens 25 Edelsteine zum Verlängern des Beschreibungstextes ausgeben und musst diese natürlich auch besitzen.');
        addnav('Mehr Zeichen?');
        addnav('Nochmal versuchen','lodge.php?op=item_chars');
        addnav('Doch nicht','lodge.php?op=item');
    }
    else
    {
        $zeichen = $int_gems*10;
        output('Willst du wirklich '.$int_gems.' Edelsteine ausgeben und damit den Beschreibungstext deines Unikates um '.$zeichen.' Zeichen verlängern?`n`n');
        addnav('Mehr Zeichen?');
        addnav('Aber sicher!','lodge.php?op=item&gems='.$int_gems);
        addnav('Doch nicht','lodge.php?op=item');
    }

}

else if ($_GET['op'] == 'item_confirm') //einzigartiges Möbelstück, Vorschau
{
    addnav('Besonderes Möbelstück');
    addnav('Nee, nochmal neu','lodge.php?op=item');

    $max_zeichen = 250 + $_GET['gems']*10;

    // warum auch immer da mehrfach escaped wird..
    if(!is_null_or_empty($_POST['sendasxmasgift']))
    {
        $name = ($_POST['sendasxmasgift']?'`DWeih`$nachtsgesc`Dhenk`^ - ':"").trim(stripslashes($_POST['name']));
    }
    else
    {
        $name = trim(stripslashes($_POST['name']));
    }

    if ($_GET['gems']) $gem_text = ' und `b'.$_GET['gems'].'`b Edelsteine';

    $desc = trim(stripslashes($_POST['desc']));
    output('Wirklich `b'.$cost['special_item'].'`b Punkte'.$gem_text.' für dieses einzigartige Möbelstück ausgeben? Es wird ungefähr so aussehen:
													`n`n'.utf8_htmlspecialsimple($name).' `&('.utf8_htmlspecialsimple($desc).'`&)`0
													`nWillst du es selbst verwenden oder an jemanden verschenken?
													`n`n<form method="POST" action="lodge.php?op=item_ok&amp;gems='.$_GET['gems'].'">
													`n<input type="hidden" name="name" value="');
    rawoutput(utf8_htmlentities($name));
    output('">
													<input type="hidden" name="desc" value="');
    rawoutput(utf8_htmlentities($desc));
    output('">
													<input type="submit" name="ok_selbst" value="Selbst verwenden!">
													<input type="submit" name="ok_geschenk" value="Verschenken">
													`n</form>');
    allownav('lodge.php?op=item_ok&gems='.$_GET['gems']);
}

else if ($_GET['op'] == 'item_ok') //einzigartiges Möbelstück bestätigen
{
    // Falls jemand der Laula erklären kann, warum das 252 sein muss und bei 250 2 Zeichen abschneidet.. bitte Oo
    //(inzwischen entfernter) Zeilenumbruch durch rawoutput?
    $max_zeichen = 252 + $_GET['gems']*10;

    $name = trim(stripslashes($_POST['name']));
    $desc = trim(stripslashes(mb_substr($_POST['desc'],0,$max_zeichen)));

    if ($_GET['act'] == 'search' && mb_strlen($_POST['search']) > 2)
    {
        $search = str_create_search_string($_POST['search']);

        $sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$search.'" AND acctid!='.$session['user']['acctid'].' ORDER BY (login="'.db_real_escape_string($_POST['search']).'") DESC, login';
        $res = db_query($sql);
        $link = 'lodge.php?op=item_ok&gems='.$_GET['gems'];

        output($name.' `&('.$desc.'`&)`0
															`n`n<form action="'.utf8_htmlentities($link).'" method="POST">
															<input type="hidden" name="name" value="');
        rawoutput(utf8_htmlentities($name));
        output('">
															<input type="hidden" name="desc" value="');
        rawoutput(utf8_htmlentities($desc));
        output('">
															<select name="acctid">');

        while ($p = db_fetch_assoc($res) )
        {
            output('<option value="'.$p['acctid'].'">'.strip_appoencode($p['name'],3).'</option>');
        }

        output('</select>`n`n
															<input type="submit" class="button" value="Auswählen!">
															</form>');
        allownav($link);
    }
    else if ($_POST['ok_geschenk'])
    {
        $link = 'lodge.php?op=item_ok&act=search&gems='.$_GET['gems'];
        output($name.' `&('.$desc.'`&)`0
																`n`nAn wen willst du das Unikat versenden?
																`n`n<form action="'.utf8_htmlentities($link).'" method="POST">
																<input type="hidden" name="name" value="');
        rawoutput(utf8_htmlentities($name));
        output('">
																<input type="hidden" name="desc" value="');
        rawoutput(utf8_htmlentities($desc));
        output('">
																Name: <input type="text" name="search">
																<input type="submit" class="button" value="Suchen!">
																</form>');
        allownav($link);

    }
    // END Geschenk
    else
    {
        addnav('Besonderes Möbelstück');
        $acctid = (int)$_POST['acctid'];

        $session['user']['donationspent'] += $cost['special_item'];
        if ($_GET['gems'])
        {
            $session['user']['gems']-= $_GET['gems'];
            $gem_text = ' und '.$_GET['gems'].' ES';
        }

        if ($acctid)
        {
            debuglog('Gab '.$cost['special_item'].' DP'.$gem_text.' für Specialitem '.$name.' für',$acctid);
        }
        else
        {
            debuglog('Gab '.$cost['special_item'].' DP'.$gem_text.' für Specialitem '.$name);
        }

        $item['tpl_name'] = utf8_htmlspecialsimple(utf8_html_entity_decode($name)).'`0';
        $item['tpl_description'] = utf8_htmlspecialsimple(utf8_html_entity_decode($desc)).'`0';
        $item['tpl_gold'] = 0;
        $item['tpl_gems'] = 10;

        item_add(($acctid ? $acctid : $session['user']['acctid']) , 'unikat' , $item );

        output('Petersen protokolliert gewissenhaft diesen Wunsch und meint dann:`n');
        if (!$acctid)
        {
            output('`7"Dein besonderes Möbelstück steht nun für dich bereit. Viel Spaß damit..."');
            addnav('Noch eines erstellen','lodge.php?op=item');
        }
        else
        {
            systemmail($acctid,'`2Ein Geschenk!',$session['user']['name'].'`2 hat dir ein Unikat namens '.$name.'`2 zum Geschenk gemacht. Du kannst es mit dir rumtragen, es anbeten oder einfach in ein Haus oder Privatgemach stellen! Ist das nicht nett?`n(Kleiner Tipp: Du findest es in deinem Inventar.)');
            output('`7"Dein besonderes Möbelstück wurde an die gewünschte Person geliefert. Hoffentlich gefällt es..."');
        }
        user_set_stats(array('unique_items_made' => 'unique_items_made+1'),$Char->acctid);

        output('`0, woraufhin er sich wieder seinem Buch zuwendet.');
    }
}
else if($_GET['op']=='item_change')
{
    $int_cost_ins = 2;
    $int_cost_rep = 1;
    $int_cost_del = 1;
    $int_cost_basis = 2;

    $str_output = get_title('Unikate modifizieren');
    addnav('Zurück');
    addnav('Zurück zu den Unikaten',$str_filename.'?op=item');
    if(!isset($_GET['subop']))
    {
        $str_output .= '`tAls du Petersen darauf ansprichst eines seiner Meisterstücke doch noch einmal anzupacken und abzuändern rümpft dieser die Nase und deutet auf einen Imp in der Ecke des Raumes. "`yDer da macht das`t" meint er, schließlich ist es unter Künstlern verpöhnt alte und vollendete Werke noch einmal zu bearbeiten. Aber der kleine Imp macht einen fitten Eindruck und so fragst du ihn nach seinen Preisen. `b'.$int_cost_basis.' Edelsteine Grundreis`b und dann nochmal einen `bAufpreis je nach Aufwand`b findest du einen fairen Deal und so zeigst du ihm das zu ändernde Unikat.`0`n`n';

        $res = item_list_get(" tpl_id='unikat' AND owner=".$Char->acctid,'',false);
        $arr_ids = array();
        while ($item = db_fetch_assoc($res))
        {
            $str_output .= '<a href="'.$str_filename.'?op=item_change&subop=chosen&itemid='.$item['id'].'">'.$item['name'].'</a><br />';
            $arr_ids[] = $item['id'];
        }
        addpregnav('/'.$str_filename.'\?op=item_change&subop=chosen&itemid=('.join('|',$arr_ids).')/');
    }
    elseif($_GET['subop'] == 'chosen')
    {
        addnav('Ein anderes wählen',$str_filename.'?op=item_change');
        $arr_item = item_get((int)$_GET['itemid']);

        if($arr_item == false)
        {
            $str_output .= 'Also hier lief jetzt was schief, das Item existiert gar nicht mehr?';
        }
        else
        {
            $arr_item['name'] = utf8_preg_replace('/(.*)`0$/','$1',$arr_item['name']);
            $arr_item['description'] = utf8_preg_replace('/(.*)`0$/','$1',$arr_item['description']);

            if(isset($_POST['name']))
            {
                $_POST['name'] = stripslashes(htmlspecialchars_decode(utf8_preg_replace('/(.*)`0$/','$1',$_POST['name'])));
            }
            if(isset($_POST['description']))
            {
                $_POST['description'] = stripslashes(htmlspecialchars_decode(utf8_preg_replace('/(.*)`0$/','$1',$_POST['description'])));
            }

            $str_md5_save = md5($_POST['name'].$_POST['description']);

            //Speichern
            if(isset($_POST['submit_changes']) && $str_md5_save == $_POST['checksum'])
            {
                $arr_changes['name'] = addstripslashes($_POST['name'].'`0');
                $arr_changes['description'] = addstripslashes($_POST['description'].'`0');
                item_set($arr_item['id'],$arr_changes);

                $Char->gems -= (int)$_POST['costs'];
                $str_output .= '`tDer Imp grunzt zufrieden, schnappt sich deine Edelsteine und einen Hammer. Nein, du verstehst nicht. Einen `bGROßEN`b Hammer! Er verschwindet mit deinem Schmuckstück in einer kleinen Nische, zieht einen Vorhang zu und beginnt Geräusche zu machen, die du eigentlich nicht hören möchtest. Als du nach endlos scheinenden Sekunden dein Unikat wieder in den Händen hälst sieht es glücklicherweise genau so aus wie du es dir gewünscht hast.';
            }
            else
            {
                if(isset($_GET['preview']))
                {
                    $int_name_cost = utf8_levenshtein($arr_item['name'],$_POST['name'],$int_cost_ins,$int_cost_rep,$int_cost_del);
                    $int_name_cost = ceil($int_name_cost/5);

                    $int_desc_cost = adv_levenshtein($arr_item['description'],$_POST['description'],$int_cost_ins,$int_cost_rep,$int_cost_del);
                    $int_desc_cost = ceil($int_desc_cost/5);

                    $int_cost_all = $int_cost_basis+$int_name_cost+$int_desc_cost;

                    $str_output_prev .= '`n<hr />`n`n`tOk, nochmal zum Mitschreiben:`n`n
																`bAlter Name`b: '.$arr_item['name'].'`t`n
																`bNeuer Name`b: '.$_POST['name'].'`t`n`n
																`bAlte Beschreibung`b: '.$arr_item['description'].'`t`n
																`bNeue Beschreibung`b: '.$_POST['description'].'`t`n`n';

                    if($int_name_cost+$int_desc_cost == 0)
                    {
                        $str_costs = 'nichts, denn du hast ja auch nichts verändert.';
                    }
                    else
                    {
                        $str_and = ($int_name_cost > 0 && $int_desc_cost> 0)?' und ':'';
                        $str_costs = $int_cost_basis.' Edelsteine Grundgebühr und '.($int_name_cost>0?$int_name_cost.' Edelsteine für den Namen':'').$str_and.($int_desc_cost>0?$int_desc_cost.' Edelsteine für die Beschreibung':'');
                    }

                    $str_output_prev .= 'Das kostet dich `b'.$str_costs.'`b`n';
                    $str_output_prev .= ($Char->gems < ($int_cost_all) ? '`$Leider hast du nicht genug Edelsteine bei dir`0' : 'Bist du damit einverstanden?');
                }

                $arr_form = array(
                    'old_name' => 'So lautete der Name des Unikats bisher,viewonly',
                    'name_pr'=>'Vorschau:,preview,name',
                    'name'    => 'Der Name des Unikats',
                    'old_description' => 'So lautete die Beschreibung des Unikats bisher,viewonly',
                    'description_pr'=>'Vorschau:,preview,description',
                    'description'    => 'Die Beschreibung deines Unikats',

                );

                $arr_data = array(
                    'old_name' => $arr_item['name'],
                    'name' => str_replace(array('`','³','²'),array('``','³³','²²'), !is_null_or_empty($_POST['name']) ? $_POST['name'] : $arr_item['name'] ),
                    'old_description' => $arr_item['description'],
                    'description' => str_replace(array('`','³','²'),array('``','³³','²²'), !is_null_or_empty($_POST['name']) ? $_POST['description'] : $arr_item['description'] )
                );

                if(isset($_POST['name']) && isset($_POST['description']) && $int_name_cost+$int_desc_cost > 0 && $int_cost_all <= $Char->gems)
                {
                    $arr_form['submit_changes'] = 'Einverstanden,submit_button,submit';
                    $arr_form['costs'] = 'Kosten für die Umbenennung,hidden';
                    $arr_data['costs'] = $int_cost_all;

                    $arr_form['checksum']    ='Prüfsumme,hidden';
                    $arr_data['checksum']    =md5($_POST['name'].$_POST['description']);
                }

                $str_output .= 'Der Imp schaut sich das Unikat kurz an, nickt dann schief und fragt dich wie du es gern verändert haben möchtest.`n';
                $str_output .= form_header($str_filename.'?op=item_change&subop=chosen&preview=1&itemid='.(int)$_GET['itemid']);
                $str_output .= generateform($arr_form, $arr_data,false,'Überprüfen lassen');
                $str_output .= form_footer();
                $str_output .= $str_output_prev;
            }
        }

    }
    output($str_output);
}

else if ($_GET['op']=='itemcolor') //Möbel von Aeki färben
{
    switch($_GET['act'])
    {
        case 'input':
            $row=item_get('id='.$_GET['id']);
            output('Vorschau: `^'.js_preview('name').'`0
											`n<form method="POST" action="lodge.php?op=itemcolor&amp;act=confirm&amp;id='.$row['id'].'">
											<input type="text" name="name" id="name" value="');
            rawoutput($row['name']);
            output('">
											`n<input type="submit" class="button" value="Speichern">
											</form>
											`n`nBeschreibung: `&'.$row['description']);
            allownav('lodge.php?op=itemcolor&act=confirm&id='.$row['id']);
            addnav('Zur Liste','lodge.php?op=itemcolor');
            break;
        case 'confirm':
            $row=item_get('id='.$_GET['id']);
            $temp_newname=strip_appoencode($_POST['name']);
            $temp_newname=str_replace('`0','',$temp_newname);
            $temp_oldname=strip_appoencode($row['name']);
            $temp_oldname=str_replace('`0','',$temp_oldname);
            if($temp_newname<>$temp_oldname)
            {
                $session['message']='`$Der Name darf nicht geändert werden, einzig Farbcodes sind erlaubt!`0`n';
                redirect('lodge.php?op=itemcolor&act=input&id='.$row['id']);
            }
            else
            {
                $colname=$_POST['name'];
                if(!mb_strrchr($colname,'0')) $colname .= '`0';
                $arr_data=array('name' => $colname);
                if(item_set('id='.$row['id'],$arr_data))
                {
                    output('Petersen schwingt den Pinsel und gibt dir wenig später dein '.$_POST['name'].'`0 in neuem Glanz zurück.`nVorsicht! Die Farbe braucht eine Weile zum Trocknen!');
                    $session['user']['donationspent']+=$cost['itemcolor'];
                    debuglog('Gab '.$cost['itemcolor'].' DP für farbiges Möbelstück');
                }
                else
                {
                    output('Petersen zuckt mit den Schultern und sagt "`7Ich weiß auch nicht wie das passieren konnte, aber die Farbtöpfe sind völlig leer.`0"');
                }
                addnav('Mehr färben','lodge.php?op=itemcolor');
            }
            break;
        default:
            if($pointsavailable<$cost['itemcolor']) $str_out.='`c`b`$Fehler!`0`b`nMöbel färben kostet '.$cost['itemcolor'].' Punkte, welche du nicht hast.`c';
            else
            {
                $result=item_list_get('owner='.$session['user']['acctid'].' AND (tpl_class=7 OR tpl_class=18) AND tpl_id != "unikat" AND deposit1 != "9999999"','',true,'name,id,description');
                //class 7 = Möbel, class 18 = Apparatur
                $str_out .= '`cDu kannst folgende Möbel für '.$cost['itemcolor'].'DP neu färben:
											`n`n<table>
											<tr class="trhead">
											<th>Name</th>
											<th>Aktion</th>
											<th>Beschreibung</th>
											</tr>';
                while($row=db_fetch_assoc($result))
                {
                    $bgcolor=($bgcolor=='trlight'?'trdark':'trlight');
                    $str_out .= '<tr class="'.$bgcolor.'">
												<td>'.$row['name'].'`0</td>
												<td>'.create_lnk('färben','lodge.php?op=itemcolor&act=input&id='.$row['id']).'</td>
												<td>'.closetags(mb_substr($row['description'],0,40),'`b`c`i').'...`0</td>
												</tr>';
                }
                $str_out .= '</table>`c`n`n';
            }
            output($str_out);
    }
}


else if ($_GET['op']=='huntweapon') //Jagdtrophäen
{
    output(get_title('`IJägerbedarf').'J. C. Peterson erklärt dir den Sinn der Jagdwaffen: "`7Du kannst diese Waffe 5 mal pro Heldenzyklus im Wald einsetzen und versuchen, damit ein zur Jagd freigegebenes Tier zu töten. Gelingt dir dies, kannst du dir eine Jagdtrophäe mitnehmen, jedoch pro Heldenzyklus von jeder Art nur eine.`0"
										`n`nFolgende Jagdwaffen sind im Angebot:
										`nJagdspeer: `&200 Punkte`0
										`nPfeil und Bogen: `&300 Punkte`0
										`nArmbrust: `&400 Punkte `0
										`nSilberpfeile: `&500 Punkte`0
										`n`n');
    $item=item_get('tpl_id="huntweapon" AND owner='.$session['user']['acctid']);
    $itemcontent=utf8_unserialize($item['content']);
    if(!$item['id'] && $pointsavailable>=200) //neu kaufen
    {
        output('
											`nFür den Anfang solltest du dich im Umgang mit dem Jagdspeer üben. Nachdem du eine Weile damit gejagt hast kannst du dir auch eine bessere Waffe kaufen.
											`nJäger zu sein bedeutet aber nicht nur Spaß, sondern auch Verantwortung.
											`n
											`nBist du bereit, die Tiere des Waldes zu achten und nicht aus Lust und Habgier zu töten?
											`nBist du bereit, `b200 Punkte`b für deine erste Jagdwaffe auszugeben?
											`nSo antworte mit einem deutlichen "Ja"!
											`0`n`n`c<form action="lodge.php?op=huntweapon2&lvl=2" method="post">
											<input type="submit" class="button" value="JA">
											</form>`c');
        allownav('lodge.php?op=huntweapon2&lvl=2');
    }
    elseif($itemcontent['weaponage']==$session['user']['dragonkills']) //Waffe wurde erst gewechselt
    {
        output('J. C. Petersen erklärt dir, dass nur Übung den Meister macht. Du solltest '.$item['name'].' mindestens einen Heldenzyklus nutzen, ehe du es mit etwas anderem versuchst.');
    }
    elseif($item['hvalue2']>=5) //hat schon beste Waffe
    {
        output('Wie du selbst sehen kannst, gibt es keine bessere Jagdwaffe als du bereits besitzt.');
    }
    elseif($pointsavailable>=($item['hvalue2']*100+100)) //Update
    {
        $lvl=$item['hvalue2']+1;
        output('Du besitzt im Moment: '.$item['name'].'. Die nächstbessere Waffe würde dich `b'.($lvl*100).' Punkte`b kosten.
											`nWillst du die Punkte ausgeben?
											`0`n`c<form action="lodge.php?op=huntweapon2&lvl='.$lvl.'" method="post">
											<input type="submit" class="button" value="JA, kaufen">
											</form>`c');
        allownav('lodge.php?op=huntweapon2&lvl='.$lvl);
    }
    else //zu teuer
    {
        output('`$Du hast nicht genug Punkte!`0
											`n`nDu besitzt im Moment: '.$item['name'].'. Die nächstbessere Waffe kostet '.($item['hvalue2']*100+100).' Punkte');
    }
    if($item['id']>0)
    {
        addnav('Nur für Jäger');
        addnav('Mitgliederliste','lodge.php?op=hunterlist');
        addnav('Vereinszimmer','lodge.php?op=trophylist');
        addnav('Trophäe verkaufen','lodge.php?op=trophysell');

        //Boss einfügen
        $rowe=user_get_aei('hunterlevel');
        if($rowe['hunterlevel']==6)
        {
            include_once(LIB_PATH.'boss.lib.php');
            boss_get_nav('huntgod');
        }
        if($rowe['hunterlevel']==7)
        {
        }
    }
}

else if ($_GET['op']=='huntweapon2') //Jagdtrophäen Waffenkauf
{
    if($_GET['lvl']==2) //neu kaufen
    {
        output(get_title('Willkommen im Kreis der Jäger!').'Für 200 Punkte überreicht dir Petersen einen brandneuen Jagdspeer und wünscht dir "Weidmanns Heil".
								`nDu verabschiedest dich mit einem "Weidmanns Dank".');
        $itemcontent['weaponage']=$session['user']['dragonkills'];
        $item['content']=utf8_serialize($itemcontent);
        $item['hvalue2']=2; //Waffenstärke und Berechnungsgrundlage
        item_add($session['user']['acctid'],'huntweapon',$item);
        $session['user']['donationspent']+= 200;
        debuglog('Gab 200 DP für Jagdwaffe');
    }
    else //Update
    {
        $item=item_get('tpl_id="huntweapon" AND owner='.$session['user']['acctid']);
        if($item)
        {
            $itemcontent=utf8_unserialize($item['content']);
            $arr_names=array(3=>'Pfeil und Bogen',4=>'Armbrust',5=>'Silberpfeile');
            $item['hvalue2']++; //Waffenstärke
            $points=$item['hvalue2']*100;
            output(get_title('Gratulation!').'Für '.$points.' Punkte tauschst du dein '.$item['name'].' gegen '.$arr_names[$item['hvalue2']].'.
									`nWeidmanns Heil!');
            $item['value']=5; //5 Anwendungen
            $item['hvalue']+=10; //Treffsicherheit
            $item['name']=$arr_names[$item['hvalue2']];
            $itemcontent['weaponage']=$session['user']['dragonkills'];
            $item['content']=utf8_serialize($itemcontent);
            item_set('id='.$item['id'],$item);
            $session['user']['donationspent']+= $points;
            debuglog('Gab '.$points.' DP für Jagdwaffe');
        }
        else
        {
            output('Deine Jagdwaffe hat sich scheinbar auf mysteriöse Weise dematerialisiert. Lass das mal von einem Admin prüfen.');
        }
    }
    addnav('Zurück zur Übersicht','lodge.php?op=huntweapon');
}

else if ($_GET['op']=='trophy') //Präparierset
{
    $resextra = db_query('SELECT trophyhunter FROM account_extra_info WHERE acctid='.$session['user']['acctid']);
    $rowextra = db_fetch_assoc($resextra);

    if ($rowextra['trophyhunter']==1)
    {
        output('Du hast doch bereits dein eigenes von J. C. Petersen signiertes Präparierset.`nOder weißt du etwa nicht was du damit anstellen sollst ?`n');
    }
    else
    {
        output('J. C. Petersen zeigt dir die vielen Jagdtrophäen in seiner Hütte, die er selbst herstestellt hat. Nun bietet er dir sein persönliches Präparierset für läppische `&'.$cost['trophy'].' Punkte`0 an.');
        if ($pointsavailable<$cost['trophy'])

        {
            output('`n`n`$Du hast nicht genug Punkte!`0');
        }
        else
        {
            addnav('Bestätige Freischaltung');
            addnav('JA','lodge.php?op=trophy2');
        }
    }
}

else if ($_GET['op']=='trophy2') //Präparierset Bestätigung
{
    if ($pointsavailable >= $cost['trophy'])
    {
        $sql = 'UPDATE account_extra_info SET trophyhunter=1 WHERE acctid = '.$session['user']['acctid'];
        db_query($sql);
        output('Gratulation! Du besitzt nun dein eigenes Präparierset und bist somit im Stand deine eigenen Trophäen herzustellen.');
        $session['user']['donationspent']+=$cost['trophy'];
        debuglog('Gab '.$cost['trophy'].'DP für Präparierset');
    }
}

else if ($_GET['op']=='trophysell') //Liste aller Trophäen zum Verkaufen
{
    $sql='SELECT *
									FROM items
									WHERE owner='.$session['user']['acctid'].'
									AND tpl_id="trph"
									AND deposit1=0
									AND deposit2=0
									ORDER BY hvalue ASC, value2=9 DESC, name
									LIMIT 100';
    $result=db_query($sql);
    $str_out.='<table>
									<tr class="trhead">
									<th>Name</th>
									<th>Wert</th>
									<th>Aktion</th>
									</tr>';
    while($item=db_fetch_assoc($result))
    {
        $trclass=($trclass=='trdark'?'trlight':'trdark');
        $str_out.='<tr class="'.$trclass.'">
										<td>'.$item['name'].'`0</td>
										<td align="right">`^'.$item['gold'].'`0 Gold`n
										`#'.$item['gems'].'`0 Gems</td>
										<td>&nbsp; '.create_lnk('Verkaufen','lodge.php?op=trophysell2&id='.$item['id']).'</td>
										</tr>';
    }
    $str_out.='</table>';
    output($str_out);
    addnav('n?Doch nichts verkaufen','lodge.php?op=huntweapon');
}

else if ($_GET['op']=='trophysell2') //Verkauf der Jagdtrophäen / Mitleidsabnahme der Alter-Mann-Trophäen
{
    $item=item_get('id='.$_GET['id']);
    if($item['value2']==9 && $item['hvalue']==0 || $_GET['act']=='OK') //normale Jagdtrophäe
    {
        output($item['name'].'`0, was für eine wundervolle Trophäe.
											`nPetersen gratuliert dir zu deinem Erfolg und überreicht dir `^'.$item['gold'].' Gold`0 und `#'.$item['gems'].' Edelsteine`0 als Lohn.');
        $session['user']['gold']+=$item['gold'];
        $session['user']['gems']+=$item['gems'];

        //item set owner=petersen, value1=timestamp, special_info=name
        item_set('id='.(int)$item['id'],array('owner' => ITEM_OWNER_PETERSEN, 'value1' => strtotime(date('r')), 'special_info' => $session['user']['name']),1);

        //löschen aller items mit owner=petersen außer die neuesten 30
        $sql='SELECT id
											FROM items
											WHERE owner = '.ITEM_OWNER_PETERSEN.'
											AND tpl_id="trph"
											ORDER BY value1 DESC
											LIMIT 30 , 50';
        $result=db_query($sql);
        if(db_num_rows($result)>0)
        {
            while ($row=db_fetch_assoc($result))
            {
                item_delete('id='.(int)$row['id'].'');
            }
        }
    } //END normale Jagdtrophäe

    elseif($item['value2']==0 && $item['hvalue']==0) //Alter-Mann-Trophäe
    {
        output($item['name'].'`0, was für eine wundervolle Trophäe.
										`nPetersen schaut dich irgendwie... mitleidig... an, als er dir '.$item['gold'].' Gold in die Hand drückt.');
        $session['user']['gold']+=$item['gold'];
        item_delete('id="'.$item['id'].'"');
    } //END Alter_Mann_Trophäe

    elseif($item['value2']==10 && $item['hvalue']==0) //einmalige Jagdtrophäe
    {
        output('J.C. Petersen ist sichtlich überrascht, als du ihm dein '.$item['name'].' anbietest. So etwas bekommt selbst er nicht oft zu sehen.
										`nDer Legende nach ist es einem Sterblichen `$nur ein einziges Mal vergönnt`0, den Champion des Waldgottes zu besiegen und in den Besitz des mystischen Geweihs zu kommen.
										`n`nBist du dir wirklich sicher, dass du dieses `&einzigartige Exemplar verkaufen`0 willst?
										`n`n'.create_lnk('Ja, weg damit','lodge.php?op=trophysell2&id='.$_GET['id'].'&act=OK'));
    } //END einmalige Jagdtrophäe

    else //Der Rest sind dann PvP-Trophäen und Stierköpfe
    {
        output('Petersen schüttelt den Kopf. '.$item['name'].'`0 passt überhaupt nicht in die Ausstellung der Jägerhütte.');
    }
    addnav('Jagdwaffen ansehen','lodge.php?op=huntweapon');
    addnav('Nur für Jäger');
    addnav('Mitgliederliste','lodge.php?op=hunterlist');
    addnav('Vereinszimmer','lodge.php?op=trophylist');
    addnav('Mehr verkaufen','lodge.php?op=trophysell');
} //END Jagdtrophäen verkaufen

else if ($_GET['op']=='trophylist') //die letzten 30 verkauften Jagdtrophäen
{
    $arr_items=item_list_get('owner='.ITEM_OWNER_PETERSEN,'ORDER BY value1 DESC',false,'*',true);
    $str_out.=get_title('Das Vereinszimmer').'
										Ehrfurchtsvoll betrittst du das Vereinszimmer. Hier ist jedes freie Fleckchen an der Wand und auf dem Boden mit Jagdtrophäen bedeckt, eine schöner als die andere. Du erblickst unendlich viele Bärenfelle, prachtvolle Zwölfender und sogar einen weißen Wolf. An der Decke wurden ausgestopfte Vögel an dünnen Seilen befestigt, so dass sie sich im Luftzug wiegen.
										`nPetersen lässt es sich nicht nehmen, dich auf die neuesten Exemplare aufmerksam zu machen:
										`n`n`0<table>';
    foreach($arr_items as $item)
    {
        $int_count++;
        $str_out.='<tr class="'.($int_count%2?'trlight':'trdark').'">
											<td>'.$item['name'].' von '.$item['special_info'].'`0</td>
											</tr>';
    }
    $str_out.='</table>';
    output($str_out);
    addnav('Jagdwaffen ansehen','lodge.php?op=huntweapon');
    addnav('Nur für Jäger');
    addnav('Mitgliederliste','lodge.php?op=hunterlist');
    addnav('Trophäe verkaufen','lodge.php?op=trophysell');
} //END Jagdtrophäenliste

else if ($_GET['op']=='hunterlist') //Auflistung aller Jäger die mindestens 1 Trophäe bekommen haben
{
    $sql='SELECT name,hunterlevel
											FROM account_extra_info aei
											LEFT JOIN accounts a ON a.acctid=aei.acctid
											WHERE hunterlevel>0
											ORDER BY hunterlevel DESC, aei.acctid ASC';
    $result=db_query($sql);
    $str_out.=get_title('Die erfolgreichen Jäger').'
											Petersen führt genau Buch, wer in dieser Stadt Wild mit Anrecht auf eine Trophäe erlegt hat.
											Diese Liste, welche nach Rang und Alter sortiert ist, kann von jedem Jagdsmann eingesehen werden.
											`n`n<table>';
    $arr_titles=array('none'
    ,'Jagdsprösslinge'
    ,'Jungjäger'
    ,'Jäger'
    ,'Jägermeister'
    ,'Oberförster'
    ,'Hüter des Waldes'
    ,'Beherrscher des Waldes'
    );
    while($row=db_fetch_assoc($result))
    {
        if($row['hunterlevel']!=$int_level)
        {
            $str_out=str_replace('#PLACEHOLDER#',$int_count,$str_out);
            $int_count=0;
            $str_out.='<tr class="trhead">
													<th>#PLACEHOLDER# '.$arr_titles[$row['hunterlevel']].':</th>
													</tr>';
            $int_level=$row['hunterlevel'];
        }
        $int_count++;
        $str_out.='<tr class="'.($int_count%2?'trlight':'trdark').'">
											<td>'.$row['name'].'</td>
											</tr>';
    }
    $str_out=str_replace('#PLACEHOLDER#',$int_count,$str_out);
    $str_out.='</table>';
    output($str_out);
    addnav('Jagdwaffen ansehen','lodge.php?op=huntweapon');
    addnav('Nur für Jäger');
    addnav('Vereinszimmer','lodge.php?op=trophylist');
    addnav('Trophäe verkaufen','lodge.php?op=trophysell');
} //END Jägerliste

else if ($_GET['op']=='dollchange') //Kadaverpuppe in Unikat umwandeln
{
    if ($item=item_get('tpl_id="kpuppe" AND owner='.$session['user']['acctid']))
    {
        if($_GET['act']=='OK')
        {
            output('Petersen holt seine Utensilien, die er für die Mumifizierung braucht, dann begebt ihr euch in Richtung Wohnviertel zu deinem Haus.
													`n`nDir kommt es gar nicht so lange vor, aber als Petersen seine Arbeit beendet hat, sind ganze 3 Tage vergangen...
													`nPetersen streicht dir 1000 Punkte von deiner Karte, während du das Ergebnis betrachtest. Tatsache, sieht genau so aus wie vorher, riecht aber nicht mehr so muffig.');
            item_set('id='.$item['id'],array('tpl_id'=>'unikat', 'description'=>$item['description'].'`nZustand: mumifiziert'));
            $session['user']['donationspent']+=$cost['dollchange'];
            $session['user']['age']+=3;
            debuglog('gab '.$cost['dollchange'].'DP für Mumie');
        }
        else
        {
            output('Du hast gehört, dass J. C. Petersen auch Mumien aus besonderen Puppen herstellt, also sprichst du ihn darauf an.
													`nPetersen erklärt dir, dass eine Mumifizerung ein sehr aufwändiger Prozess ist. Er bietet dir aber seine Dienste für läppische 1000 Punkte an.
													`n`n`2Für diesen Preis wird er zu dir nach Hause kommen und das wertvolle `q'.$item['name'].'`2-Exemplar in eine einzigartige Mumie verwandeln. Am Aussehen von '.$item['name'].'`2 wird sich fast nichts ändern, auf die bisherige Funktion musst du jedoch verzichten.');
            if ($pointsavailable<$cost['dollchange'])
            {
                output('`n`n`$Du hast nicht genug Punkte!`0');
            }
            else
            {
                addnav('Bestätige Mumifizierung');
                addnav('JA','lodge.php?op=dollchange&act=OK');
            }
        }
    }
    else
    {
        output('Du hast gehört, dass J. C. Petersen auch Mumien aus besonderen Puppen herstellt, also sprichst du ihn darauf an.
												`nPetersen erklärt dir, dass eine Mumifizerung ein sehr aufwändiger Prozess ist.
												`n`$Und vor allem braucht er eine Puppe dafür, welche du dir aber schon selbst besorgen musst.');
    }
} //END dollchange

else if($_GET['op']=='taunt') //Gegnerspott für PvP
{
    function get_taunt_prev($taunt='',$sex=0) //Negation der Funktion get_taunt()
    {
        global $session,$badguy;

        if($taunt=='')
        {
            if($badguy['creaturewin']!='' && $taunt!==false)
            {
                $taunt = $badguy['creaturewin'];
            }
            else
            {
                $sql = 'SELECT taunt FROM taunts ORDER BY rand('.e_rand().') LIMIT 1';
                $result = db_query($sql);
                $taunt = db_fetch_assoc($result);
                $taunt = $taunt['taunt'];
            }
        }

        $taunt = str_replace('%s',($sex?'sie':'ihn'),$taunt);
        $taunt = str_replace('%o',($sex?'sie':'er'),$taunt);
        $taunt = str_replace('%p',($sex?'ihr':'sein'),$taunt);
        $taunt = str_replace('%x',($badguy['creatureweapon']),$taunt);
        $taunt = str_replace('%X',$session['user']['weapon'],$taunt);
        $taunt = str_replace('%W',$session['user']['name'],$taunt);
        $taunt = str_replace('%w',$badguy['creaturename'],$taunt);
        $taunt = words_by_sex($taunt,$sex);

        $taunt='`5'.$taunt.'`0';

        return $taunt;
    }
    $badguy['creaturename']='Bauerntrampel `2Joe`^Bloe';
    $badguy['creatureweapon']='`#Zahnstocher';

    if($_GET['act']=='confirm' && $pointsavailable >= $cost['taunt']) //Spott-Kauf bestätigen
    {
        $config['taunt'] = 1;
        output('J. C. Peterson beglückwünscht dich zu deiner Entscheidung, etwas Individualität in allfällige Streitigkeiten zu bringen.`n`n');
        $session['user']['donationspent']+=$cost['taunt'];
        debuglog('Gab '.$cost['taunt'].'DP für Gegnerspott');
    }

    if ($config['taunt']) //Spott ist bereits gekauft
    {
        if(isset($_POST['changetaunt'])) //Änderung abgeschickt
        {
            $changes['creaturewin']=strip_tags($_POST['creaturewin']);
            $changes['creaturewin']=closetags($changes['creaturewin'],'`b`c`i');
            $changes['creaturewin']=addstripslashes($changes['creaturewin']);
            $changes['creaturelose']=strip_tags($_POST['creaturelose']);
            $changes['creaturelose']=closetags($changes['creaturelose'],'`b`c`i');
            $changes['creaturelose']=addstripslashes($changes['creaturelose']);
            user_set_aei($changes);
            output('`@Änderungen übernommen.`0`n`n');
        }

        $rowe=user_get_aei('creaturewin,creaturelose');
        $creaturewin=stripslashes($rowe['creaturewin']);
        $creaturelose=stripslashes($rowe['creaturelose']);
        output('Hier hast du nun die Möglichkeit, die Sprüche zu ändern.');
        rawoutput('<form action="lodge.php?op=taunt " method="post">
										<br>Text unter dem Kampf, wenn du verlierst:
										<br><input type="text" name="creaturelose" size=100 maxlength=120 value="'.utf8_htmlentities($creaturelose).'">
										<br><br>Spott in den News, wenn du gewinnst:
										<br><input type="text" name="creaturewin" size=100 maxlength=120 value="'.utf8_htmlentities($creaturewin).'">
										<br><input type="submit" class="button" value="Speichern">
										<input type="hidden" name="changetaunt" value=1>
										</form>');
        output('`n`2Beim Spott werden die folgenden Codes unterstützt:
										`n`7%w`0 = Name des Verlierers ('.$badguy['creaturename'].'`0)
										`n`7%x`0 = Waffe des Verlierers ('.$badguy['creatureweapon'].'`0)
										`n`7%s`0 = Geschlecht des Verlierers (ihn/sie)
										`n`7%p`0 = Geschlecht des Verlierers (sein/ihr)
										`n`7%o`0 = Geschlecht des Verlierers (er/sie)
										`n`7%W`0 = Name des Gewinners ('.$session['user']['name'].'`0)
										`n`7%X`0 = Waffe des Gewinners ('.$session['user']['weapon'].'`0)
										`n`7[männl|weibl]`0 = Passagen geschlechtsspezifisch ersetzen (Frisö[r|se])
										`n`n<hr>So würde dein derzeitiger Spott aussehen:
										'.($creaturewin?'':'`n`$Du hast noch keinen Spott definiert, es wird ein Zufalls-Spott angezeigt.`0').'
										`n`n`%'.$badguy['creaturename'].'`5 wurde bei seinem Angriff auf `4'.$session['user']['name'].'`5 getötet.
										`n'.get_taunt_prev($creaturewin));
        $badguy['creaturename']='Flittchen `2Jane`^Bloe';
        output('`n`n`%'.$badguy['creaturename'].'`5 wurde bei ihrem Angriff auf `4'.$session['user']['name'].'`5 getötet.
										`n'.get_taunt_prev($creaturewin,1));
        if($creaturelose>'')
        {
            output('<hr>
											So würde dein derzeitiger Todesspruch aussehen:
											`n`n`b`&'.$creaturelose.'`0`b`n
											`b`$Du hast '.$session['user']['name'].'`$ besiegt!`0`b`n
											`#Du erbeutest `^'.$session['user']['gold'].'`# Gold!
											`n...');
        }
        allownav('lodge.php?op=taunt');
    }
    else //Spott ist noch nicht gekauft
    {
        output('Du bist es leid, in den News immer nur Sprüche zu lesen wie:
									`n`n`%'.$badguy['creaturename'].'`5 wurde bei seinem Angriff auf `4'.$session['user']['name'].'`5 getötet.
									`n'.get_taunt_prev(false).'
									`n`n`0Hiermit schaffst du dir die Möglichkeit, deine Angreifer so richtig zu verspotten, wenn sie dir im Kampf unterliegen.
									');
        if ($pointsavailable<$cost['taunt'])
        {
            output('`n`n`$Du hast nicht genug Punkte!`0');
        }
        else
        {
            addnav('Bestätige Freischaltung');
            addnav('JA','lodge.php?op=taunt&act=confirm');
        }
    }
}


else if ($_GET['op'] == 'history_text')
{
    if($_GET['subop']=='chosen')
    {
        $data = db_get("SELECT * FROM history WHERE id='".intval($_GET['hid'])."' AND acctid='".intval($session['user']['acctid'])."' LIMIT 1");

        if(isset($data['id']) && $_GET['do']=='save')
        {
            db_query("UPDATE history SET text='".db_real_escape_string(mb_substr(stripslashes($_POST['text']),0,2000))."' WHERE id='".intval($data['id'])."'  AND acctid='".intval($session['user']['acctid'])."' LIMIT 1 ");
        }

        else if(isset($data['id']))
        {
            $str_lnk =  'lodge.php?op=history_text&subop=chosen&hid='.$data['id'].'&do=save';
            allownav($str_lnk);
            output('<form method="POST" action="'.$str_lnk.'">');
            $arr_form = array    (
                'text'=>'Nachricht:,textarea,70,2,2000',
            );
            showform($arr_form,$data);
            output('</form>');
        }
    }

    $sql = 'SELECT * FROM history WHERE acctid='.$session['user']['acctid'].' ORDER BY id DESC';
    $res = db_query($sql);
    if(!db_num_rows($res)) {
        $str_out = '`n`n`&Bisher hast du GAR NICHTS erreicht...`n`n';
    }
    else {
        $str_out = '`n`n`&Deine Aufzeichnungen:`n`n';
        while($h = db_fetch_assoc($res)) {
            $str_out .= '<hr>`&'.$h['gamedate'].' '.$h['msg'].'`n'.( ($h['text']!='') ? $h['text'].'`n' : '').'`& '.create_lnk('Editieren','lodge.php?op=history_text&subop=chosen&hid='.$h['id']).'`n`n';
        }
    }


    output( $str_out );
}
else if ($_GET['op'] == 'history_autocol')
{
    $int_max_length = 2000;

    if($_GET['subop']=='chosen')
    {
        $data = db_get("SELECT * FROM history WHERE id='".intval($_GET['hid'])."'
                                    AND acctid='".intval($session['user']['acctid'])."'
                                    AND NOT (msg LIKE '%Besonderes Ereignis:%' OR msg LIKE '%`y~`0%')
                                    LIMIT 1");

        if(isset($data['id']) && $_GET['do']=='save')
        {
            $old = strip_appoencode($data['msg'],3);
            $new = strip_appoencode(mb_substr(stripslashes($_POST['msg']),0,$int_max_length),3);

            if($new != $old)
            {
                output("`n`b`4Nur umfärben ist erlaubt! Text ist nicht mehr gleich. Text muss sein:`b`& ".$old);

                $str_lnk =  'lodge.php?op=history_autocol&subop=chosen&hid='.$data['id'].'&do=save';
                allownav($str_lnk);
                output('<form method="POST" action="'.$str_lnk.'">');
                $arr_form = array    (
                    'msg'=>'Nachricht:,textarea,70,2,'.$int_max_length,
                );
                showform($arr_form,$_POST);
                output('</form>');

            }
            else
            {
                db_query("UPDATE history
                                            SET msg='".db_real_escape_string(mb_substr(stripslashes($_POST['msg']),0,$int_max_length))."'
                                            WHERE id='".intval($data['id'])."'
                                            AND acctid='".intval($session['user']['acctid'])."'
                                            LIMIT 1 ");
            }
        }

        else if(isset($data['id']))
        {
            $str_lnk =  'lodge.php?op=history_autocol&subop=chosen&hid='.$data['id'].'&do=save';
            allownav($str_lnk);
            output('<form method="POST" action="'.$str_lnk.'">');
            $arr_form = array    (
                'msg'=>'Nachricht:,textarea,70,2,'.$int_max_length,
            );
            showform($arr_form,$data);
            output('</form>');
        }
    }

    $sql = 'SELECT * FROM history WHERE acctid='.$session['user']['acctid'].' AND NOT (msg LIKE "%Besonderes Ereignis:%" OR msg LIKE "%`y~`0%") ORDER BY id DESC';
    $res = db_query($sql);
    if(!db_num_rows($res)) {
        $str_out = '`n`n`&Bisher hast du GAR NICHTS erreicht...`n`n';
    }
    else {
        $str_out = '`n`n`&Deine Aufzeichnungen:`n`n';
        while($h = db_fetch_assoc($res)) {
            $str_out .= '<hr>`&'.$h['gamedate'].' '.$h['msg'].'`n'.'`& '.create_lnk('Editieren','lodge.php?op=history_autocol&subop=chosen&hid='.$h['id']).'`n`n';
        }
    }


    output( $str_out );

}

else if ($_GET['op'] == 'history') //Besonderes Ereignis eintragen
{

    $int_max_length = 230;

    // Aktuelles Spieldatum
    $str_current_date = getsetting('gamedate','0000-00-00');
    // .. als Array
    $arr_current_date = explode('-',$str_current_date);
    // Max. anwählbares Jahr
    $int_max_year = (int)$arr_current_date[0];
    // Max. anwählbarer Monat
    $int_max_month = (int)$arr_current_date[1];
    // Max. anwählbarer Tag
    $int_max_day = (int)$arr_current_date[2];

    addnav('Aktionen');
    addnav('Einträge verändern',$str_filename.'?op=history_change');

    if($_GET['act'] == 'save') {

        // Invalide Spieldaten verhindern
        $int_year = min((int)$_REQUEST['year'],$int_max_year);
        $int_month = (int)$_REQUEST['month'];
        $int_day = (int)$_REQUEST['day'];
        if($int_year == $int_max_year)
        {
            $int_month = min($int_month,$int_max_month);
            if($int_month == $int_max_month)
            {
                $int_day = min($int_day,$int_max_day);
            }
        }

        // this piece of code was taken from chaosmakers gamedate-mod
        $str_gamedate = sprintf('%04d-%02d-%02d',$int_year,$int_month,$int_day);

        $str_msg = stripslashes(urldecode($_REQUEST['msg']));
        $str_msg = mb_substr($str_msg,0,$int_max_length);

        $str_msg_save = '`y~`0'.$str_msg;

        if($_GET['ok'])
        {

            $session['user']['donationspent'] += $cost['history'];
            debuglog('Gab '.$cost['history'].'DP für spezielle Aufzeichnung');
            addhistory($str_msg_save,1,0,$str_gamedate);
            redirect('lodge.php?op=history&act=done');
        }
        else
        {
            output('Deine spezielle Aufzeichnung würde folgendermaßen aussehen:`n`n
				`@'.getgamedate($str_gamedate).' : `2'.$str_msg_save.'`n`n`0
				Entspricht dies deinen Wünschen?`n`n');

            $str_lnk = 'lodge.php?op=history&act=save&ok=1&day='.$int_day.'&month='.$int_month.'&year='.urlencode($int_year).'&msg='.urlencode($str_msg);
            output(create_lnk('Ja, für '.$cost['history'].' DP eintragen!',$str_lnk));

        }
    }
    elseif ($_GET['act'] == 'done')
    {

        output('`@Petersen nimmt deinen Wunsch entgegen und reicht ihn weiter in das Hinterzimmer der Hütte. Bereits nach kurzer Zeit
			kannst du das Ergebnis betrachten:`n`n');
        show_history(1,$session['user']['acctid']);
        page_footer();
        exit;

    }
    elseif ($_GET['act'] == 'del')
    {

        output('`@Wie gewünscht, streicht Petersen diesen Eintrag aus deinen Aufzeichnungen.`n`n');

        db_query('DELETE FROM history WHERE id='.(int)$_GET['id']);

        addnav('Neuen Eintrag vornehmen','lodge.php?op=history');

        page_footer();
        exit;

    }
    else
    {
        output('Petersen hat hervorragende Verbindungen zu den Geschichtsschreibern des Landes. Deshalb kann er dir gegen
			`&'.$cost['history'].' Punkte `0 zu einem Eintrag deiner Wahl
			in deinen Aufzeichnungen verhelfen. Hierbei kannst du entweder selbst ein (natürlich gültiges) Datum angeben oder das
			des heutigen Tages verwenden. An den Text deiner Aufzeichnung wird vorne das Zeichen "~" angefügt.`n
			Achtung: Diese Option dient der Ausgestaltung eures Rollenspiel-Charakters! Unsinnige Spaß-Einträge werden ohne Entschädigung entfernt!`n');

        $int_day = $int_max_day;
        $int_month = $int_max_month;
        $int_year = $int_max_year;

    }

    $str_lnk = 'lodge.php?op=history&act=save';

    if($pointsavailable < $cost['history']) {
        output('`$Doch leider, leider kannst du dir das gar nicht leisten.. Schade.');
    }
    else
    {
        $arr_data=array('msg'=>$str_msg,'year'=>$int_year,'month'=>$int_month,'day'=>$int_day);
        if($_GET['act']=='edit')
        {
            $sql = 'SELECT * FROM history WHERE id='.$_GET['id'];
            $res = db_query($sql);
            $arr_data=db_fetch_assoc($res);
            $arr_data['gamedate']=explode('-',$arr_data['gamedate']);
            $arr_data['year']=$arr_data['gamedate'][0];
            $arr_data['month']=$arr_data['gamedate'][1];
            $arr_data['day']=$arr_data['gamedate'][2];
            $arr_data['msg']=str_replace('`y~`0','',$arr_data['msg']);

        }
        allownav($str_lnk);
        output('<form method="POST" action="'.$str_lnk.'">');
        $arr_form = array    (
            'msg'=>'Nachricht:,textarea,70,2,'.$int_max_length,
            'msg_pr'=>'Vorschau:,preview,msg',
            'day'=>'Tag,enum_order,1,31',
            'month'=>'Monat,enum_order,1,12',
            'year'=>'Jahr,text|?Negative Jahre werden als v.u.Z. angezeigt. Maximale Jahresangabe ist zur Zeit '.$int_max_year

        );
        showform($arr_form,$arr_data,false,'Vorschau!');
        output('</form>');
    }

    $sql = 'SELECT id,gamedate,msg FROM history WHERE acctid='.$session['user']['acctid'].' AND (msg LIKE "%Besonderes Ereignis:%" OR msg LIKE "%`y~`0%") ORDER BY id DESC';
    $res = db_query($sql);
    if(!db_num_rows($res)) {
        $str_out = '`n`n`&Bisher hast du bei Petersen noch keine Einträge zu deinen Aufzeichnungen hinzufügen lassen.`n`n';
    }
    else {
        $str_out = '`n`n`&Bisher hast du bei Petersen die folgenden Einträge zu deinen Aufzeichnungen hinzufügen lassen:`n`n';
        while($h = db_fetch_assoc($res)) {
            $str_out .= '<hr>`&'.$h['gamedate'].' '.$h['msg'].'`&`n ~~ '.create_lnk('Streichen!','lodge.php?op=history&act=del&id='.$h['id'],true,false,'Willst du diesen Eintrag wirklich aus deinen Aufzeichnungen streichen?');
            $str_out .= '`& | '.create_lnk('Als&nbsp;Vorlage','lodge.php?op=history&act=edit&id='.$h['id']);
            $str_out .= '`& | '.create_lnk('Editieren','lodge.php?op=history_change&subop=chosen&hid='.$h['id']).'`n`n';
        }
    }

    output($str_out,true);
}
else if($_GET['op']=='history_change')
{
    $int_max_length = 230;
    $str_history_header = '`y~`0';

    // Aktuelles Spieldatum
    $str_current_date = getsetting('gamedate','0000-00-00');
    // .. als Array
    $arr_current_date = explode('-',$str_current_date);
    // Max. anwählbares Jahr
    $int_max_year = (int)$arr_current_date[0];
    // Max. anwählbarer Monat
    $int_max_month = (int)$arr_current_date[1];
    // Max. anwählbarer Tag
    $int_max_day = (int)$arr_current_date[2];


    $str_output = get_title('Geschichtseinträge modifizieren');
    addnav('Zurück');
    addnav('Zurück zu den Einträgen',$str_filename.'?op=history');
    if(!isset($_GET['subop']))
    {
        $str_output .= '`tMit der Chronik in der Hand und einer Brille auf der Nase stellst du dich vor Petersen hin und erklärst ihm, dass einige der von ihm für dich hinterlegten Geschichtseinträge fehlerhaft seien und du um eine Änderung bittest. Natürlich ist er wenig begeistert und das macht er auch deutlich "`yWillst du etwa sagen, dass ich mich verschrieben hätte?`t" Du überlegst kurz einfach frech "`yJa`t" zu antworten, besinnst dich aber eines besseren und beschwichtigst ihn, dass es wohl dein Fehler gewesen sein muss, so als wenn eine unbekannte Macht die Fäden zieht und dich Dinge tun lässt, die du manchmal gar nicht tun willst. Petersen nickt verstehend. "`yDas gefühl haben wir doch alle manchmal`t" Mit einem grinsen auf den Lippen erklärt er dir die Preise für dein Vorhaben: `b'.$int_cost_basis.' Edelsteine Grundpreis`b und dann nochmal einen `bAufpreis je nach Aufwand`b findest du einen fairen Deal und so zeigst du ihm den zu ändernden Eintrag.`0`n`n';


        $sql = 'SELECT `id`,`gamedate`,`msg` FROM `history` WHERE `acctid`='.$Char->acctid.' AND (`msg` LIKE "%Besonderes Ereignis:%" OR `msg` LIKE "%`y~`0%") ORDER BY `id` DESC';
        $res = db_query($sql);
        if(!db_num_rows($res))
        {
            $str_output .= '`n`n`&Bisher hast du bei Petersen noch keine Einträge zu deinen Aufzeichnungen hinzufügen lassen.`n`n';
        }
        else
        {
            $str_output .= '`n`n`&Bisher hast du bei Petersen die folgenden Einträge zu deinen Aufzeichnungen hinzufügen lassen:`n`n';
            while($h = db_fetch_assoc($res))
            {
                $str_output .= '<hr>`&'.$h['gamedate'].' '.$h['msg'].'`&`n ~~ <a href="'.$str_filename.'?op=history_change&subop=chosen&hid='.$h['id'].'">Editieren</a><br />';
                $arr_ids[] = $h['id'];
            }
            addpregnav('/'.$str_filename.'\?op=history_change&subop=chosen&hid=('.join('|',$arr_ids).')/');
        }
    }
    elseif($_GET['subop'] == 'chosen')
    {
        addnav('Ein anderes wählen',$str_filename.'?op=history_change');
        $arr_item = db_get('SELECT `id`,`gamedate`,`msg` FROM `history` WHERE `id`='.(int)$_GET['hid']);

        if($arr_item == false)
        {
            $str_output .= 'Also hier lief jetzt was schief, der Geschichtseintrag existiert gar nicht mehr?';
        }
        else
        {
            $vuz = (mb_substr($arr_item['gamedate'],0,1)=='-');

            $arr_gamedate            = explode('-',$arr_item['gamedate']);
            $arr_item['year']        = $arr_gamedate[0];
            $arr_item['month']        = $arr_gamedate[1];
            $arr_item['day']        = $arr_gamedate[2];

            if($vuz)
            {
                $arr_gamedate            = explode('-',$arr_item['gamedate']);
                $arr_item['year']        = -1*intval($arr_gamedate[1]);
                $arr_item['month']        = $arr_gamedate[2];
                $arr_item['day']        = $arr_gamedate[3];
            }

            $arr_item['msg']        = str_replace($str_history_header,'',$arr_item['msg']);

            $arr_data = $arr_item;

            if(isset($_POST['msg']))
            {
                $str_msg = stripslashes(htmlspecialchars_decode(utf8_preg_replace('/(.*)`0$/','$1',$_POST['msg'])));
                $str_msg = str_replace($str_history_header,'',$str_msg);
                $str_msg = mb_substr($str_msg,0,$int_max_length);

                // Invalide Spieldaten verhindern
                $int_year = min((int)$_REQUEST['year'],$int_max_year);
                $int_month = (int)$_REQUEST['month'];
                $int_day = (int)$_REQUEST['day'];
                if($int_year >= $int_max_year)
                {
                    $int_year = $int_max_year;
                    $int_month = min($int_month,$int_max_month);
                    if($int_month == $int_max_month)
                    {
                        $int_day = min($int_day,$int_max_day);
                    }
                }

                $arr_data['msg']     = $str_msg;
                $arr_data['year']     = $int_year;
                $arr_data['month']     = $int_month;
                $arr_data['day']     = $int_day;
            }



            // this piece of code was taken from chaosmakers gamedate-mod
            $str_gamedate_save = sprintf('%04d-%02d-%02d',$int_year,$int_month,$int_day);

            $str_msg_save = '`y~`0'.$str_msg;


            $str_md5_save = md5($str_msg_save.$str_gamedate_save);

            //Speichern
            if(isset($_POST['submit_changes']) && $str_md5_save == $_POST['checksum'])
            {
                db_query('UPDATE history SET msg="'.db_real_escape_string($str_msg_save).'", gamedate = "'.db_real_escape_string($str_gamedate_save).'" WHERE id='.(int)$_GET['hid']);

                $Char->gems -= (int)$_POST['costs'];

                $str_output .= '`tPetersen nickt, greift sich mit der einen Hand einen Federkiel und pinselt einfach im Buch der Geschichte herum...also wenn das so einfach ist...dann...dann hat er dich beschissen. Naja, wenigstens stimmt jetzt dein Eintrag.';
            }
            else
            {
                if(isset($_GET['preview']))
                {
                    $str_output_prev .= '`n<hr />`n`n`tOk, nochmal zum Mitschreiben:`n`n
					`bAlter Eintrag`b: '.$arr_item['msg'].'`t`n
					`bNeuer Eintrag`b: '.$str_msg_save.'`t`n`n
					`bAltes Datum`b: '.$arr_item['gamedate'].'`t`n
					`bNeues Datum`b: '.$str_gamedate_save.'`t`n`n';
                }

                $arr_form = array    (
                    'msg'        =>'Nachricht:,textarea,70,2,'.$int_max_length,
                    'msg_pr'    =>'Vorschau:,preview,msg',
                    'day'        =>'Tag,enum_order,1,31',
                    'month'        =>'Monat,enum_order,1,12',
                    'year'=>'Jahr,text|?Negative Jahre werden als v.u.Z. angezeigt. Maximale Jahresangabe ist zur Zeit '.$int_max_year
                );

                if(isset($_POST['msg']))
                {
                    $arr_form['submit_changes'] = 'Einverstanden,submit_button,submit';
                    $arr_form['checksum']    ='Prüfsumme,hidden';
                    $arr_data['checksum']    =md5($str_msg_save.$str_gamedate_save);
                }

                $str_output .= 'Petersen schaut sich deinen Änderungswunsch kurz an, nickt kurz und fragt dich wie du es gern verändert haben möchtest.`n';
                $str_output .= form_header($str_filename.'?op=history_change&subop=chosen&preview=1&hid='.(int)$_GET['hid']);
                $str_output .= str_replace(array('`','³','²'),array('``','³³','²²'),generateform($arr_form, $arr_data,false,'Überprüfen lassen'));
                $str_output .= form_footer();
                $str_output .= $str_output_prev;
            }
        }

    }
    output($str_output);
}
else if ($_GET['op']=='colorhotkey') //benutzergefärbter Hotkey
{
    if ($config['colorhotkey']==1)
    {
        addnav('Aktionen');
        addnav('Standardfarbe wählen','lodge.php?op=colorhotkey&subop=default');
        addnav('Eigene Farbe wählen','lodge.php?op=colorhotkey&subop=choose');
        addnav('Option verkaufen','lodge.php?op=colorhotkey2');
        if (array_key_exists('subop', $_GET))
        {
            if ($_GET['subop']=="default")
            {
                user_set_aei(array('hotkey_hexcode'=>'default'));
                Cache::set(Cache::CACHE_TYPE_SESSION , 'hotkey_hexcode', 'default');
                output('`n`nStandardfarbe gesetzt!');
                clearnav();
                addnav('Weiter','lodge.php?op=colorhotkey');
            }
            else if ($_GET['subop']=="choose")
            {
                $arr_aei = user_get_aei('hotkey_hexcode');
                output(form_header('lodge.php?op=colorhotkey&subop=choosesubmit').'`n`nHEX-Code (siehe Farbcodes im Profil): #<input name="hexcode" maxlength="6"'.($arr_aei['hotkey_hexcode']!="default"?' value="'.$arr_aei['hotkey_hexcode'].'"':'').'> <input type="submit" value="Bestätigen"></form>',true);
            }
            else if ($_GET['subop']=="choosesubmit")
            {
                $str_hex = trim($_POST['hexcode']);
                if (utf8_preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/', $str_hex))
                {
                    output('`n`n`2`bHexcode #'.$str_hex.' gesetzt :)`b');
                    user_set_aei(array('hotkey_hexcode'=>$str_hex));
                    Cache::set(Cache::CACHE_TYPE_SESSION , 'hotkey_hexcode', $str_hex);
                    clearnav();
                    addnav('Weiter','lodge.php?op=colorhotkey');
                }
                else
                {
                    output('`n`n`4Ups, das geht nicht, gib bitte einen gültigen HEX-Code an!');
                    clearnav();
                    addnav('Zurück','lodge.php?op=colorhotkey&subop=choose');
                }
            }
        }
        else
        {
            $arr_aei = user_get_aei('hotkey_hexcode');
            output('`n`nMomentan haben deine Hotkeys '.($arr_aei['hotkey_hexcode']=="default"?'die Standardfarbe.':'diese Farbe: <span class="navhi">#'.$arr_aei['hotkey_hexcode'].'</span>').'`n`nIn der Navigationsleiste kannst du dich entweder für die Standardfärbung, welche vom Skin abhängig ist, oder für eine eigene Farbe als Farbe für deine Hotkeys entscheiden.',true);
        }
    }
    else if (array_key_exists('subop', $_GET) && $_GET['subop']=="confirm")
    {
        if ($pointsavailable >= $cost['colorhotkey'])
        {
            $config['colorhotkey'] = 1;
            user_set_aei(array('hotkey_hexcode'=>'default'));
            output('`n`nJ. C. Peterson gibt dir eine Karte und sagt: "Melde dich damit bei dem kleinen Gnom hinter meiner Hütte, der wird dafür sorgen, dass dir von nun an ganz nach deinem Wunsch gefärbte Zeichen den Weg weisen!"');
            $session['user']['donationspent']+=$cost['colorhotkey'];
            debuglog('Gab '.$cost['colorhotkey'].'DP für selbstfärbbare Hotkeys');
            clearnav();
            addnav('Weiter','lodge.php?op=colorhotkey');
        }
    }
    else
    {
        output('`n`nDu kannst gegen `&'.$cost['colorhotkey'].' Punkte`0 die Farbe deiner Hotkeys unabhängig vom Skin selbst bestimmen');
        if ($pointsavailable<$cost['colorhotkey'])
        {
            output('`n`n`$Du hast nicht genug Punkte!`0');
        }
        else
        {
            addnav('Bestätige Kauf von selbstfärbbaren Hotkeys');
            addnav('JA','lodge.php?op=colorhotkey&subop=confirm');
        }
    }
}

else if ($_GET['op']=='colorhotkey2') //benutzergefärbte Hotkeys verkaufen
{
    output('`n`nHier kannst du die Option, deine Hotkeys selbst einzufärben, wieder verkaufen. Deine Hotkeys haben dann wieder die vom Skin abhängige Standardfarbe.`nAchtung: Du kannst die Standardfarben auch wieder einstellen, ohne diese Option zu verkaufen, also überlege dir das hier gut!',true);

    addnav('Verkauf bestätigen');
    addnav('Ja!','lodge.php?op=colorhotkey3');
}

else if ($_GET['op']=='colorhotkey3')
{

    user_set_aei(array('hotkey_hexcode'=>'default'));
    Cache::set(Cache::CACHE_TYPE_SESSION , 'hotkey_hexcode', 'default');
    output('`n`nAlles wieder beim Alten!');
    clearnav();
    addnav('Weiter','lodge.php?op=colorhotkey');

    $config['colorhotkey'] = 0;
    output('`n`nJ. C. Petersen nimmt die Karte von dir zurück und sagt: "Ich hoffe, du bereust diese Entscheidung nicht"');
    $session['user']['donationspent']+=$cost['colorhotkey_sell'];
    debuglog('Verkaufte die selbstfärbbaren Hotkeys für '.$cost['colorhotkey_sell'].'');
    clearnav();
    addnav('Weiter','lodge.php?op=colorhotkey');

}
//edit by bathi
else if ($_GET['op']=='new_msg_char')
{
    $aei = user_get_aei('msg_chars');
    $msgChars = adv_unserialize($aei['msg_chars']);
    $has = count($msgChars);
    $max = getsetting('msg_chars_max',2);

    if ($has >= $max)
    {
        output('Du hast bereits '.$has.' von '.$max.' Msg-Charakter.`nMehr kannst du nicht erwerben!');
    }
    else
    {
        output('Hiermit kannst du dir für `&'.$cost['new_msg_char'].' Punkte`0 einen weiteren Msg-Charakter erwerben.`n
			Msg-Charakter kannst du im RPG mit den Kürzeln /mc0 - /mc'.($max-1).' am Anfang deines Posts verwenden.`nSie dürfen farbig sein.`n`n
			Du hast bereits `^'.$has.'`& von `^'.$max.' möglichen`& Msg-Charakter.`n`0');
        if ($pointsavailable<$cost['new_msg_char'])
        {
            output('`n`n`$Du hast nicht genug Punkte!`0');
        }
        else
        {
            addnav('Bestätige Freischaltung');
            addnav('JA','lodge.php?op=new_msg_char2');
        }
    }
}
else if ($_GET['op']=='new_msg_char2')
{
    if ($pointsavailable >= $cost['new_msg_char'])
    {
        $aei = user_get_aei('msg_chars');
        $msgChars = adv_unserialize($aei['msg_chars']);

        if ($_GET['subop']=='check')
        {
            $str_name = stripslashes(trim($_POST['newname']));
            $str_name = mb_substr($str_name, 0, 60);
            $str_name = strip_appoencode($str_name,2);
            $str_name_of = strip_appoencode($str_name,3);
            $str_valid = evaluate_user_rename( user_rename(0, stripslashes(trim(strip_appoencode($_POST['newname'],3))), false, false, USER_NAME_BADWORD | USER_NAME_BLACKLIST, true)  );

            if(true !== $str_valid) {
                output('Ein verstaubter Archivar teilt dir mit, dass dieser Name nicht gewährt werden kann. Falls du dir sicher bist, dass der Alte keine Ahnung hat, frage über \'Hilfe anfordern\' nach den genauen Gründen.`n'.$str_valid);
                addnav('Schade!');
                addnav('Nochmal!','lodge.php?op=new_msg_char2');
                addnav('Zurück!','lodge.php');
            }
            elseif(mb_strlen($str_name_of) < 3)
            {
                output('`b`4Achtung!`b`n`^Naja, wir wollen mal nicht untertreiben, also 3 Buchstaben sollte ein Name schon haben, oder? Stell dir doch mal den armen Msg-Charakter vor. Da wird jemand auf dem Stadtplatz geschlagen, ruft "AUUU" und dein Msg-Charakter fühlt sich angesprochen...oder "IIIIEH" oder "Bäääää"...also das wollen wir unseren Msg-Charakteren nicht zumuten!');
                addnav('Schade!');
                addnav('Nochmal!','lodge.php?op=new_msg_char2');
                addnav('Zurück!','lodge.php');
            }
            elseif(mb_strlen($str_name_of) > 40 && (!mb_strpos($str_name_of,' ') || mb_strpos($str_name_of,' ') > 38)){
                output('`b`4Achtung!`b`n`^Im Namen deines Msg-Charakter muss nach höchstens 38 Zeichen ein Leerzeichen kommen, da es sonst zu Darstellungsfehlern in den Chatsections kommt.');
                addnav('Schade!');
                addnav('Nochmal!','lodge.php?op=new_msg_char2');
                addnav('Zurück!','lodge.php');
            }
            else{
                if(mb_strrpos($str_name,'`0') != mb_strlen($str_name)-2) {
                    $str_name .= '`0';
                }
                $msgChars[] = $str_name;
                user_set_aei(array('msg_chars' => db_real_escape_string(utf8_serialize($msgChars)) ));


                $rowex = user_get_aei('ext_profile');
                $ext_prof = adv_unserialize($rowex['ext_profile']);
                $ext_prof['has_msg_char_bio'] = true;
                user_set_aei(array('ext_profile'  => db_real_escape_string(utf8_serialize($ext_prof)) ));

                output('J. C. Peterson gewährt dir einen weiteren Msg-Charakter und gibt dir die Möglichkeit eines weiteren Gefährten der auf den Namen '.$str_name.' hört.');
                $session['user']['donationspent']+=$cost['new_msg_char'];
                debuglog('Gab '.$cost['new_msg_char'].'DP für Msg-Charakter');
            }


        }
        else{
            $out = '`n`n`4`bTauft meinen Msg-Charakter auf:`b`0`n';
            $out .= js_preview('newname').'`n';
            $out .= '<form action="lodge.php?op=new_msg_char2&subop=check" method="POST"><input name="newname" id="newname" value="" size="30" maxlength="60"> <input type="submit" value="Taufen"></form>';
            output($out);
            addnav('','lodge.php?op=new_msg_char2&subop=check');
        }
    }
}
else if ($_GET['op']=='rename_msg_char')
{
    $aei = user_get_aei('msg_chars');
    $msgChars = adv_unserialize($aei['msg_chars']);
    $has = count($msgChars);
    $max = getsetting('msg_chars_max',2);

    output('Hiermit kannst du für `&'.$cost['rename_msg_char'].' Punkte`0 einen Msg-Charakter umbenennen.`n`0');
    if ($pointsavailable<$cost['rename_msg_char'])
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        output('`n`&Wähle den Msg-Charakter aus:`n`n`0');
        for($i=0; $i<$has; $i++){
            output('<a href="lodge.php?op=rename_msg_char2&mid='.$i.'">'.$msgChars[$i].'</a>`n`0');
            addnav('','lodge.php?op=rename_msg_char2&mid='.$i);
        }
    }

}
else if ($_GET['op']=='rename_msg_char2')
{
    if ($pointsavailable >= $cost['rename_msg_char'])
    {
        $aei = user_get_aei('msg_chars');
        $msgChars = adv_unserialize($aei['msg_chars']);
        $mid = intval($_GET['mid']);

        if ($_GET['subop']=='check')
        {
            $str_name = stripslashes(trim($_POST['newname']));
            $str_name = mb_substr($str_name, 0, 60);
            $str_name = strip_appoencode($str_name,2);
            $str_name_of = strip_appoencode($str_name,3);
            $str_name_of_old = strip_appoencode($msgChars[$mid],3);
            $str_valid = evaluate_user_rename( user_rename(0, stripslashes(trim(strip_appoencode($_POST['newname'],3))), false, false, USER_NAME_BAN | USER_NAME_BADWORD, true));

            //$bool_blacklist = check_blacklist(BLACKLIST_LOGIN, stripslashes(trim(strip_appoencode($_POST['newname'],3))));

            if($str_name_of == $str_name_of_old){
                output('`4Der Name ist gleich geblieben! Wenn du ihn nur umfärben willst, profitiere von den günstigeren Umfärbe-Angebot.');
                addnav('Danke!');
                addnav('Aber ich will ihn umbenennen!','lodge.php?op=rename_msg_char2&mid='.$mid);
                addnav('Das werde ich!','lodge.php');
            }
            //elseif($bool_blacklist === true){
//						output('`4Dieser Name ist von den Göttern nicht erwünscht! Und ich glaube kaum, dass dein Msg-Charakter gern '.$str_name.'`& heissen will!`n');
//						addnav('Schade!');
//						addnav('Nochmal!','lodge.php?op=recolor_msg_char2&mid='.$mid);
//						addnav('Zurück!','lodge.php');
//					}
            elseif(true !== $str_valid) {
                output('Ein verstaubter Archivar teilt dir mit, dass dieser Name nicht gewährt werden kann. Falls du dir sicher bist, dass der Alte keine Ahnung hat, frage über \'Hilfe anfordern\' nach den genauen Gründen.`n'.$str_valid);
                addnav('Schade!');
                addnav('Nochmal!','lodge.php?op=rename_msg_char2&mid='.$mid);
                addnav('Zurück!','lodge.php');
            }
            elseif(mb_strlen($str_name_of) < 3)
            {
                output('`b`4Achtung!`b`n`^Naja, wir wollen mal nicht untertreiben, also 3 Buchstaben sollte ein Name schon haben, oder? Stell dir doch mal den armen Msg-Charakter vor. Da wird jemand auf dem Stadtplatz geschlagen, ruft "AUUU" und dein Msg-Charakter fühlt sich angesprochen...oder "IIIIEH" oder "Bäääää"...also das wollen wir unseren Msg-Charakteren nicht zumuten!');
                addnav('Schade!');
                addnav('Nochmal!','lodge.php?op=rename_msg_char2&mid='.$mid);
                addnav('Zurück!','lodge.php');
            }
            elseif(mb_strlen($str_name_of) > 40 && (!mb_strpos($str_name_of,' ') || mb_strpos($str_name_of,' ') > 38)){
                output('`b`4Achtung!`b`n`^Im Namen deines Msg-Charakter muss nach höchstens 38 Zeichen ein Leerzeichen kommen, da es sonst zu Darstellungsfehlern in den Chatsections kommt.');
                addnav('Schade!');
                addnav('Nochmal!','lodge.php?op=rename_msg_char2&mid='.$mid);
                addnav('Zurück!','lodge.php');
            }
            else{
                if(mb_strrpos($str_name,'`0') != mb_strlen($str_name)-2) {
                    $str_name .= '`0';
                }
                $msgChars[$mid] = $str_name;
                user_set_aei(array('msg_chars' => db_real_escape_string(utf8_serialize($msgChars)) ));
                output('J. C. Peterson tauft deinen Msg-Charakter auf den Namen '.$str_name.'.');
                $session['user']['donationspent']+=$cost['rename_msg_char'];
                debuglog('Gab '.$cost['rename_msg_char'].'DP für die Umbenennung eines Msg-Charakter');
            }


        }
        else{
            output('`n`n`4`bTauft meinen Msg-Charakter auf:`b`0`n');
            output(js_preview('newname').'`n');
            $out = '<form action="lodge.php?op=rename_msg_char2&mid='.$mid.'&subop=check" method="POST"><input name="newname" id="newname" value="'.$msgChars[$mid].'" size="30" maxlength="60"> <input type="submit" value="Taufen"></form>';
            rawoutput($out);
            addnav('','lodge.php?op=rename_msg_char2&mid='.$mid.'&subop=check');
        }
    }
}
else if ($_GET['op']=='recolor_msg_char')
{
    $aei = user_get_aei('msg_chars');
    $msgChars = adv_unserialize($aei['msg_chars']);
    $has = count($msgChars);
    $max = getsetting('msg_chars_max',2);

    output('Hiermit kannst du für `&'.$cost['recolor_msg_char'].' Punkte`0 einen Msg-Charakter umfärben.`n`0');
    if ($pointsavailable<$cost['recolor_msg_char'])
    {
        output('`n`n`$Du hast nicht genug Punkte!`0');
    }
    else
    {
        output('`n`&Wähle den Msg-Charakter aus:`n`n`0');
        for($i=0; $i<$has; $i++){
            output('<a href="lodge.php?op=recolor_msg_char2&mid='.$i.'">'.$msgChars[$i].'</a>`n`0');
            addnav('','lodge.php?op=recolor_msg_char2&mid='.$i);
        }
    }

}
else if ($_GET['op']=='recolor_msg_char2')
{
    if ($pointsavailable >= $cost['recolor_msg_char'])
    {
        $aei = user_get_aei('msg_chars');
        $msgChars = adv_unserialize($aei['msg_chars']);
        $mid = intval($_GET['mid']);

        if ($_GET['subop']=='check')
        {
            $str_name = stripslashes(trim($_POST['newname']));
            $str_name = mb_substr($str_name, 0, 60);
            $str_name = strip_appoencode($str_name,2);
            $str_name_of = trim(strip_appoencode($str_name,3));
            $str_name_of_old = trim(strip_appoencode($msgChars[$mid],3));

            $str_valid = evaluate_user_rename( user_rename(0, stripslashes(trim(strip_appoencode($_POST['newname'],3))), false, false, USER_NAME_BAN | USER_NAME_BADWORD));

            //$bool_blacklist = check_blacklist(BLACKLIST_LOGIN, stripslashes(trim(strip_appoencode($_POST['newname'],3))));
            //rawoutput($str_name_of);
            //rawoutput($str_name_of_old);
            if($str_name_of != $str_name_of_old){
                output('`4Der Name ist nicht gleich geblieben! Wenn du ihn umbennen willst, musst du mein Umbennenungs-Dienst in Anspruch nehmen!');
                addnav('Peinlich!');
                addnav('Aber ich wollte ihn gar nicht umbenennen!','lodge.php?op=recolor_msg_char2&mid='.$mid);
                addnav('Das werde ich!','lodge.php');
            }
            //elseif($bool_blacklist === true){
//						output('`4Dieser Name ist von den Göttern nicht erwünscht! Und ich glaube kaum, dass dein Msg-Charakter gern '.$str_name.'`& heissen will!`n');
//						addnav('Schade!');
//						addnav('Nochmal!','lodge.php?op=recolor_msg_char2&mid='.$mid);
//						addnav('Zurück!','lodge.php');
//					}
            elseif(true !== $str_valid) {
                output('Ein verstaubter Archivar teilt dir mit, dass dieser Name nicht gewährt werden kann. Falls du dir sicher bist, dass der Alte keine Ahnung hat, frage über \'Hilfe anfordern\' nach den genauen Gründen.`n'.$str_valid);
                addnav('Schade!');
                addnav('Nochmal!','lodge.php?op=recolor_msg_char2&mid='.$mid);
                addnav('Zurück!','lodge.php');
            }
            elseif(mb_strlen($str_name_of) < 3)
            {
                output('`b`4Achtung!`b`n`^Naja, wir wollen mal nicht untertreiben, also 3 Buchstaben sollte ein Name schon haben, oder? Stell dir doch mal den armen Msg-Charakter vor. Da wird jemand auf dem Stadtplatz geschlagen, ruft "AUUU" und dein Msg-Charakter fühlt sich angesprochen...oder "IIIIEH" oder "Bäääää"...also das wollen wir unseren Msg-Charakteren nicht zumuten!');
                addnav('Schade!');
                addnav('Nochmal!','lodge.php?op=recolor_msg_char2&mid='.$mid);
                addnav('Zurück!','lodge.php');
            }
            elseif(mb_strlen($str_name_of) > 40 && (!mb_strpos($str_name_of,' ') || mb_strpos($str_name_of,' ') > 38)){
                output('`b`4Achtung!`b`n`^Im Namen deines Msg-Charakter muss nach höchstens 38 Zeichen ein Leerzeichen kommen, da es sonst zu Darstellungsfehlern in den Chatsections kommt.');
                addnav('Schade!');
                addnav('Nochmal!','lodge.php?op=recolor_msg_char2&mid='.$mid);
                addnav('Zurück!','lodge.php');
            }
            else{
                if(mb_strrpos($str_name,'`0') != mb_strlen($str_name)-2) {
                    $str_name .= '`0';
                }
                $msgChars[$mid] = $str_name;
                user_set_aei(array('msg_chars' => db_real_escape_string(utf8_serialize($msgChars)) ));
                output('J. C. Peterson tauft deinen Msg-Charakter auf den Namen '.$str_name.'.');
                $session['user']['donationspent']+=$cost['recolor_msg_char'];
                debuglog('Gab '.$cost['recolor_msg_char'].'DP für die Umfärbung eines Msg-Charakter');
            }


        }
        else{
            output('`n`n`4`bTauft meinen Msg-Charakter auf:`b`0`n');
            output(js_preview('newname').'`n');
            $out = '<form action="lodge.php?op=recolor_msg_char2&mid='.$mid.'&subop=check" method="POST"><input name="newname" id="newname" value="'.$msgChars[$mid].'" size="30" maxlength="60"> <input type="submit" value="Taufen"></form>';
            rawoutput($out);
            addnav('','lodge.php?op=recolor_msg_char2&mid='.$mid.'&subop=check');
        }
    }
}
//end edit by bathi


$session['user']['donationconfig'] = utf8_serialize($config);

page_footer();
?>
