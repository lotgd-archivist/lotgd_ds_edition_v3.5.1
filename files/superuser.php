<?php
require_once 'common.php';
addcommentary(false);

page_header('Kuschelecke');

//Slayer ist faul und will nicht immer ins "Weltliche" wenn er seine Position auf der Karte umändert :-)
$show_invent = true;

$str_filename = basename(__FILE__);

/**
 * Erledigt Formalitäten des Wechsels zwischen SU-Bereich und dem Weltlichen
 *
 * @param string $str_return_var		Key des Zwischenspeichers in der Session, in dem Output, Navis, Restorepage des Weltlichen liegen
 */
function superuser_bounce ($str_return_var)
{
    global $session;

    if(is_array($session[$str_return_var])
        && !empty($session[$str_return_var]['output'])
        && is_array($session[$str_return_var]['allowednavs'])
        && !empty($session[$str_return_var]['restorepage'])
    )
    {

        $session['debug'] .= ' SU-Bounce: '.$str_return_var;

        // Wenn Seite neugeladen werden darf, direkt weiterleiten
        foreach ($session[$str_return_var]['allowednavs'] as $str_nav => $b) {
            $str_nav = calcreturnpath($str_nav);
            if($str_nav != 'badnav.php' && $str_nav == $session[$str_return_var]['restorepage'])
            {
                $str_ret = $session[$str_return_var]['restorepage'];
                unset($session[$str_return_var]);
                redirect($str_ret);
            }
        }

        $session['debug'] .= ', zeige Seite '.$session[$str_return_var]['restorepage'];

        // Link auf Mails aktualisieren
        $session[$str_return_var]['output'] = utf8_preg_replace('/\<span id="MAILBOXLINK"\>(.*)\<\/span\>/i',maillink(),$session[$str_return_var]['output']);
        $session['allowednavs']	= $session[$str_return_var]['allowednavs'];
        $session['user']['output'] = $session[$str_return_var]['output'];
        $session['user']['restorepage'] = $session[$str_return_var]['restorepage'];
        $session['user']['chat_section'] = $session[$str_return_var]['chat_section'];
        $g_ret_page = '';
        unset($session[$str_return_var]);
        echo($session['user']['output']);
        saveuser(true);
        session_write_close();
        exit;
    }
}

grotto_nav(array('mundane'=>true,'petition'=>true));

switch($_GET['op']) {
    case 'superuser_id_switch':
    {
        // wenn badnav in der RP: Nicht switchen!
        if($g_ret_page == 'badnav.php') {
            $session['allowednavs'] = $session['user']['allowednavs'];
            redirect('badnav.php','Kein SU-Switch möglich, wenn RP == badnav.php!');
        }
        $str_basefile = basename(__FILE__);
        $int_acctid_switch = ($Char->superuser_id_switch != false)?$Char->superuser_id_switch : $Char->acctid;
        $int_acctid = $Char->acctid;
        $str_restorepage = calcreturnpath($g_ret_page);
        //Alten Charakter sichern, ggf. irgendwelche Werte ändern
        $allowed_navs_now = $session['allowednavs'];
        $allowed_navs_user = $session['user']['allowednavs'];
        //Damit nicht beide Chars gleichzeitig als online angezeigt werden
        $Char->loggedin = 0;
        //Damit man nach dem switchen und nachfolgendem Grottenjump nicht an der
        //Restorepage des anderen Chars rauskommt
        unset($session['su_return']);
        //Speichern VOR dem Session löschen (aktualisiert auch Memcache)
        $Char->save();
        //Neuen Char laden und ggf Werte ändern
        try
        {
            $Char = new CCharacter($int_acctid_switch,true);

            //Wenn man zu einem Superuserchar switcht,
            //dann gibts
            //- keinen newday
            if($access_control->su_lvl_check(1))
            {
                //Superuser Chars sind nicht feige
                $Char->age = 0;

                //Letzter neuer Tag war jetzt
                $Char->lasthit = date('Y-m-d H:i:s');

                //Letzter "Hit" war jetzt
                $Char->laston = date('Y-m-d H:i:s');

                //Zu welchem User muss zurückgeswitched werden
                $Char->superuser_id_switch = $int_acctid;
            }
            //Beim zurückswitchen gibts auch keinen Timeout
            else
            {
                $Char->laston = date('Y-m-d H:i:s');
            }
            //Char einloggen
            $Char->loggedin = 1;
            //Navs zurückschreiben
            $Char->allowednavs = $allowed_navs_now;
            $session['allowednavs'] = $allowed_navs_now;
        }
        catch (Exception $e)
        {
            systemlog('Die ID '.$Char->superuser_id_switch.' ist keine existierende superuser_id_switch id!');
        }

        redirect($str_restorepage);
        break;
    }
    case 'newsdelete':
    {
        $access_control->su_check(access_control::SU_RIGHT_NEWS,true);
        $sql = "DELETE FROM news WHERE newsid='$_GET[newsid]'";
        db_query($sql);
        $return = $_GET['return'];
        $return = utf8_preg_replace("'[?&]c=[[:digit:]-]*'",'',$return);
        $return = mb_substr($return,mb_strrpos($return,'/')+1);
        redirect($return);
        break;
    }
    case 'newsdelete2':
    {
        $access_control->su_check(access_control::SU_RIGHT_NEWS,true);
        $sql = "DELETE FROM ddlnews WHERE newsid='$_GET[newsid]'";
        db_query($sql);
        $return = $_GET['return'];
        $return = utf8_preg_replace("'[?&]c=[[:digit:]-]*'",'',$return);
        $return = mb_substr($return,mb_strrpos($return,'/')+1);
        redirect($return);
        break;
    }
    case 'iwilldie':
    {
        $access_control->su_check(access_control::SU_RIGHT_GROTTO,true);
        debuglog('nutzte Lemmingbutton '.($session['user']['alive'] ? 'nach unten':'nach oben'));
        $session['user']['alive'] = ($session['user']['alive'] ? 0 : 1);
        $session['user']['hitpoints'] = ($session['user']['alive'] ? $session['user']['maxhitpoints'] : 0);
        redirect($session['user']['alive'] ? 'village.php':'shades.php');
        break;
    }
    case 'newday':
    {
        $access_control->su_check(access_control::SU_RIGHT_NEWDAY,true);
        debuglog('löste Neuen Tag aus.');
        $session['user']['restorepage'] = 'village.php';
        redirect('newday.php');
        break;
    }
    // Grotten-Einstiegspunkt
    case 'intro_pet':
    case 'intro_grotte':

        if($g_ret_page != 'superuser.php' && mb_substr($g_ret_page,0,3) != 'su_')
        {

            $session['su_return'] = array	(
                'restorepage'=>$g_ret_page,
                'output'=>$session['user']['output'],
                //'allowednavs'=>utf8_unserialize($session['user']['allowednavs']),
                'allowednavs'=>$session['user']['allowednavs'],
                'chat_section'=>$session_copy['chat_section']
            );

        }

        if($_GET['op'] == 'intro_pet')
        {
            $access_control->su_check(access_control::SU_RIGHT_PETITION,true);
            redirect('su_petitions.php');
        }

        $access_control->su_check(access_control::SU_RIGHT_GROTTO,true);

        superuser_bounce('su_return2grotto');

        redirect('superuser.php');

        break;
    // END Grotten-Einstiegspunkt
    // Grotten-Ausstiegspunkt
    case 'superuser_ret':

        // Letzte Grottenseite speichern
        $session['su_return2grotto'] = array(
            'restorepage'=>$g_ret_page,
            'output'=>$session['user']['output'],
            //'allowednavs'=>utf8_unserialize($session['user']['allowednavs']),
            'allowednavs'=>$session['user']['allowednavs'],
            'chat_section'=>$session_copy['chat_section']
        );

        superuser_bounce('su_return');

        if($session['user']['alive'])
        {
            redirect('village.php');
        }
        else {
            redirect('shades.php');
        }

        break;
    // END Grotten-Ausstiegspunkt
    case 'restore_rights':
    {
        $arr_rights = array();
        end($access_control);
        $int_lastkey = (int)key($access_control);
        ksort($access_control);
        for($i=0; $i<=$int_lastkey; $i++) {
            if(!isset($access_control[$i])) {
                $arr_rights[$i] = 0;
            }
            else {
                $arr_rights[$i] = 1;
            }
        }
        ksort($arr_rights);
        $arr_groups = array( 	0 => array(0 => 'Spieler', 1=>'Spieler', 2 => array(), 3 => 1),
            1 => array(0 => 'Admin', 1 => 'Admins', 2 => $str_rights, 3 => 1) );
        $session['user']['superuser'] = 1;
        systemlog('`5Superuser-Gruppen zurückgesetzt!`0',$session['user']['acctid']);
        savesetting( 'sugroups', (utf8_serialize($arr_groups)) );
        redirect('superuser.php');
        break;
    }
    default:
    {
        $access_control->su_check(access_control::SU_RIGHT_GROTTO,true);
        viewcommentary('superuser','`0Mit anderen Göttern unterhalten:',25,'sagt');

        // Prüfung, ob SU-Rechte vorhanden
        $arr_groups = utf8_unserialize((getsetting('sugroups','')) );
        if(empty($arr_groups) || sizeof($arr_groups) == 0) {
            addnav('`^SU-Rechte reparieren!`0','superuser.php?op=restore_rights');
        }
        // END Prüfung auf Rechte

        addnav('Arbeit');
        if ($access_control->su_check(access_control::SU_RIGHT_PETITION)) addnav('A?Anfragen','su_petitions.php');
        addnav('Sonstiges');
        if ($access_control->su_check(access_control::SU_RIGHT_PETITION)) addnav('E?Einwohnerliste','list.php');
        if ($access_control->su_check(access_control::SU_RIGHT_PETITION)) addnav('#?Notausgang','login.php?op=logout');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORUSER))addnav('Editoren - User');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORLIBRARY)) addnav('o?Bibliothek-Editor','su_library_editor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_DONATIONS)) addnav(',?Donationpoints','su_donation.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDIT_RIGHTS)) addnav('Gruppeneditor','su_usergroups.php',false,false,false,false);
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORGUILDS)) addnav('G?Gilden-Editor','su_guilds.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORHOUSES)) addnav('H?Hausmeister','su_houses.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORUSER)) addnav('Schwarze Liste','su_blacklist.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORUSER)) addnav('i?User-Editor','user.php');
        if ($access_control->su_check(access_control::SU_RIGHT_BAN_USER)) addnav('Verbannungen','su_bans.php',false,false,false,false);
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD))addnav('Editoren - Spielwelt');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITOREXTTXT)) addnav('Extended-Texts-Editor','su_extended_text.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORCOLORS)) addnav('Farben-Editor','su_colors.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORHOUSES)) addnav('Garten-Editor','su_garden.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORITEMS)) addnav('-?Item-Editor','su_item.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) addnav('Monster-Editor','su_creatures.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) addnav('Pflanzen-Editor','su_crops.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) addnav('Quest-Editor','bathorys_module.php?mod=quest&mdo=superuser');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) addnav('Rätsel-Editor','su_riddleeditor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORRACES)) addnav('Rassen-Editor','su_races.php');
        if ($access_control->su_check(access_control::SU_RIGHT_DEV)) addnav('RP-Chat-Emotes', 'su_rpchateditor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) addnav('RP-Welten-Editor','su_rpworldeditor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITOREQUIPMENT)) addnav('Rüstungs-Editor','su_armoreditor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) addnav('Runen-Editor','su_runeedit.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORCASTLES)) addnav('Schloss-Editor','su_mazeedit.php');
        if ($access_control->su_check(access_control::SU_RIGHT_DEV)) addnav('Skin-Verwaltung', 'su_skins.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORSPECIAL)) addnav('Specialevent-Editor','su_specialeditor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORSPECIALTIES)) addnav('Spezialitäten-Editor','su_speciality_editor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) addnav('Spott-Editor','su_taunt.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORMOUNTS)) addnav('Stalltier-Editor','su_mounts.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORTITLES)) addnav('Titel Editor','su_titleeditor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD)) addnav('Trivia-Editor','su_trivia.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITOREQUIPMENT)) addnav('Waffen-Editor','su_weaponeditor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS )) addnav('Wettertext Editor','su_weather_texts_editor.php');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORRANDOMCOM)) addnav('Zufallskommentar-Editor','su_randomcomment.php');
        if ($access_control->su_check(access_control::SU_RIGHT_LOGOUTALL))addnav('Mechanik');
        if ($access_control->su_check(access_control::SU_RIGHT_LOGOUTALL)) addnav('Alle Spieler ausloggen','user.php?op=logout_all',false,false,false,false);
        if ($access_control->su_check(access_control::SU_RIGHT_GAMEOPTIONS)) addnav('Spieleinstellungen','su_configuration.php');
        if ($access_control->su_check(access_control::SU_RIGHT_GAMEOPTIONS)) addnav('Wortfilter','su_badword.php',false,false,false,false);
        if ($access_control->su_check(access_control::SU_RIGHT_DEBUGLOG))addnav('Aufzeichnungen');
        if ($access_control->su_check(access_control::SU_RIGHT_DEBUGLOG)) addnav('1?Debuglog','su_logs.php?type=debuglog');
        if ($access_control->su_check(access_control::SU_RIGHT_FAILLOG)) addnav('2?Faillog','su_logs.php?type=faillog');
        if ($access_control->su_check(access_control::SU_RIGHT_SYSLOG)) addnav('4?Systemlog','su_logs.php?type=syslog');
        if ($access_control->su_check(access_control::SU_RIGHT_EDITORUSER)) addnav('5?Neujahrslog', 'su_neujahr.php');
    }
}
page_footer();
?>