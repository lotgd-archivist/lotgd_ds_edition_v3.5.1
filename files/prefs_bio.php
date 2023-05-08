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


//by bathory
if(intval($Char->acctid)==0 && isset($_GET['do']))  exit;

if($_GET['do']=='ajax')
{

    switch($_GET['sdo'])
    {
        case 'sortsave':
        {
            echo CRPBio::savesort($Char->acctid,json_decode(stripslashes($_POST['lejson'])));
        }
            break;
        case 'sorttabssave':
        {
            $arr_sort = json_decode(stripslashes($_POST['lejson']));

            if(is_array($arr_sort))
            {
                $arr_out = array();
                foreach($arr_sort as $sort)
                {

                    $arr_out[] = $sort->id;
                }

                user_set_aei(array('stecktabs' => db_real_escape_string(utf8_serialize($arr_out))));

                echo 'done';
            }
            else
            {
                echo 'fail';
            }
        }
            break;
        case 'newpage':
        {
            echo CRPBio::newpage($Char->acctid,intval($_GET['p']));
        }
            break;
        case 'del':
        {
            db_query("UPDATE rpbios SET activ=0,deleted=1 WHERE acctid=".intval($Char->acctid)." AND pageid=".intval($_GET['p'])." LIMIT 1");
            db_query("UPDATE rpbios SET activ=0,deleted=1 WHERE acctid=".intval($Char->acctid)." AND parent=".intval($_GET['p'])."");
        }
            break;
    }
    exit;
}
else if($_GET['do']=='mode')
{
    switch($_GET['profi'])
    {
        case 1:
        {
            db_query("UPDATE rpbios_config SET profi=1 WHERE acctid='".intval($Char->acctid)."' LIMIT 1");
            redirect('prefs_bio.php?do=edit',false,false);
        }
            break;
        case 0:
        {
            db_query("UPDATE rpbios_config SET profi=0 WHERE acctid='".intval($Char->acctid)."' LIMIT 1");
            redirect('prefs_bio.php?do=edit',false,false);
        }
            break;
    }
    exit;
}

else if($_GET['do']=='akt')
{
    db_query("UPDATE rpbios SET activ=1 WHERE acctid=".intval($Char->acctid)." AND pageid=".intval($_GET['p'])." LIMIT 1");
    redirect('prefs_bio.php?do=edit',false,false);
    exit;
}
else if($_GET['do']=='deak')
{
    db_query("UPDATE rpbios SET activ=0 WHERE acctid=".intval($Char->acctid)." AND pageid=".intval($_GET['p'])." LIMIT 1");
    redirect('prefs_bio.php?do=edit',false,false);
    exit;
}
else if($_GET['do']=='del')
{
    // db_query("UPDATE rpbios SET activ=0,deleted=1 WHERE acctid=".intval($Char->acctid)." AND pageid=".intval($_GET['p'])." LIMIT 1");
    // db_query("UPDATE rpbios SET activ=0,deleted=1 WHERE acctid=".intval($Char->acctid)." AND parent=".intval($_GET['p'])."");
    redirect('prefs_bio.php?do=edit',false,false);
    exit;
}
else if($_GET['do']=='undel')
{
    db_query("UPDATE rpbios SET activ=1,deleted=0,parent=0 WHERE acctid=".intval($Char->acctid)." AND pageid=".intval($_GET['p'])." LIMIT 1");
    db_query("UPDATE rpbios SET activ=1,deleted=0 WHERE acctid=".intval($Char->acctid)." AND parent=".intval($_GET['p'])."");
    redirect('prefs_bio.php?do=edit',false,false);
    exit;
}
else
{
    $config = CRPBio::check();
    $config['friends'] = utf8_unserialize($config['friends']);
    $config['exclude'] = utf8_unserialize($config['exclude']);
    $profi = $config['profi'] == 1;
    $out = '';

    popup_header('Bio-Editor',true,true);

    output(''.JS::encapsulate('window.resizeTo(1010,757);'));

    $biolink	= 'bio.php?id='.$Char->acctid.''.( (isset($_GET['p'])) ? '&p='.intval($_GET['p']) : '').'';
    $piclink 	= 'pict.php';

    output($str_message.'
	`b ( <a id="lebiolink" href="'.$biolink.'">Bio ansehen</a> - <a href="' . $piclink . '">Bilderverwaltung</a> - <a href="prefs.php">Profil</a>'.' )`b
');
    output('<br /><br /><table width="100" border="0" style="margin:auto;">
                            <tr>
                                <td><a href="prefs_steckbrief.php" class="motd">Steckbrief</a></td>

                                <td><a href="prefs_bio.php" class="motd">Bio</a></td>
                            </tr>
                        </table>');


    output('<div style="padding:15px;"><table width="100" border="0" style="margin:auto;">
                            <tr>

                            <td><a href="prefs_bio.php?do=edit" class="motd">Seiten Übersicht</a></td>
                            <td><a href="prefs_bio.php?do=edit&sdo=bin" class="motd">Papierkorb</a></td>
                            <td><a href="prefs_bio.php?do=edit&sdo=config" class="motd">Einstellungen</a></td>
                                <td><a href="prefs_bio.php?do=edit&sdo=friends" class="motd">Freundesliste</a></td>
                                <td><a href="prefs_bio.php?do=edit&sdo=exclude" class="motd">Bannliste</a></td>
                                <td><a href="prefs_bio.php?do=edit&sdo=help" class="motd">Hilfe</a></td>
                            </tr>
                        </table><br />');


    switch($_GET['sdo'])
    {
        case 'help':
        {
            output( get_extended_text('rpbio_hilfe_alg'));

        }
            break;
        case 'saveconfig':
        {

            if($Char->acctid != $_POST['leuser'])
            {
                die("Die Daten wurden nicht gespeichert, da du deinen Account gewechselt hast! (Zu deinem eigenen Schutz damit du deine Bio nicht überschreibst ;) )");
            }

            $clean = CBioCleaner::clean('',stripslashes($_POST['css']),$Char->acctid);
            $css = db_real_escape_string($clean['css']);

            $confdata = array();

            $head['body_back'] = '';
            $head['body_text'] = '';
            $head['body_text_fam'] = '';
            $head['body_text_size'] = '';
            $head['fixwidth'] = '';
            $head['menu_trenn_color'] = '';
            $head['menu_back'] = '';
            $head['menu_top_back']  = '';
            $head['menu_color']  = '';
            $head['menu_top_back_hover']  = '';
            $head['menu_color_hover'] = '';
            $head['menu_text_fam']  = '';
            $head['menu_text_size'] = '';

            foreach($head as $k => $v)
            {
                if($k=='fixwidth')
                {
                    if(mb_strpos($_POST[$k],'%')!== false)
                    {
                        $_POST[$k] = utf8_preg_replace("/[^0-9]+/i", "", $_POST[$k]);
                        $_POST[$k] = max(min(intval($_POST[$k]),100),0).'%';
                    }
                    else if($_POST[$k] != '')
                    {
                        $_POST[$k] = utf8_preg_replace("/[^0-9]+/i", "", $_POST[$k]);
                        $_POST[$k] = max(min(intval($_POST[$k]),1900),500).'px';
                    }

                    $confdata[$k] = $_POST[$k];

                }
                else if($k=='body_text_size' || $k=='menu_text_size')
                {
                    $confdata[$k] = min(max(intval($_POST[$k]),8),18);
                }
                else if($k=='body_text_fam' || $k=='menu_text_fam')
                {
                    $confdata[$k] = $_POST[$k];
                }
                else
                {
                    $confdata[$k] = CBioCleaner::outputHEX(CBioCleaner::cleanHEX($_POST[$k]));
                }
            }
//,see_anon='".intval(isset($_POST['see_anon']))."'
            db_query("UPDATE rpbios_config SET


                ".( (isset($_POST['css'])) ? "css='".$css."'," : "" )."
                config='".db_real_escape_string(utf8_serialize($confdata))."'

                ,see_demo='".intval(isset($_POST['see_demo']))."'
                ,see_reg='".intval(isset($_POST['see_reg']))."'
                ,see_friends='".intval(isset($_POST['see_friends']))."'

                ".( (isset($_POST['fonts'])) ? ",fonts='".db_real_escape_string($_POST['fonts'])."'" : "" )."


            WHERE acctid=".$Char->acctid." LIMIT 1");

            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                die('done');
            }
            else
            {
                redirect('prefs_bio.php?do=edit&sdo=config',false,false);
            }
        }
            break;
        case 'save':
        {

            if($Char->acctid != $_POST['leuser'])
            {
                die("Die Daten wurden nicht gespeichert, da du deinen Account gewechselt hast! (Zu deinem eigenen Schutz damit du deine Bio nicht überschreibst ;) )");
            }

            $pid = intval($_GET['p']);
            $activ = intval($_POST['activ']);
            $titel = db_real_escape_string(strip_tags(stripslashes($_POST['titel'])));

            $clean = CBioCleaner::clean(stripslashes($_POST['content']),stripslashes($_POST['css']),$Char->acctid);

            $content = db_real_escape_string($clean['html']);
            $css = db_real_escape_string($clean['css']);
//, see_anon='".intval(isset($_POST['see_anon']))."'
            db_query("UPDATE rpbios SET
            titel='".$titel."',

            content='".$content."',


       ".( (isset($_POST['css'])) ? "css='".$css."'," : "" )."


       activ='".$activ."'

            ,see_demo='".intval(isset($_POST['see_demo']))."'
            ,see_reg='".intval(isset($_POST['see_reg']))."'
            ,see_friends='".intval(isset($_POST['see_friends']))."'
            ".( (isset($_POST['fonts'])) ? ",fonts='".db_real_escape_string($_POST['fonts'])."'" : "" )."
            WHERE acctid=".$Char->acctid." AND pageid=".$pid." LIMIT 1");

            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                die('done');
            }
            else
            {
                redirect('prefs_bio.php?do=edit&sdo=edit&saved=ok&p='.$pid,false,false);
            }
        }
            break;
        case 'edit':
        {
            $pid = intval($_GET['p']);
            if($_GET['saved']=='ok')   output('`@Erfolgreich gespeichert.`0');
            else output('<div id="ajaxresponse" style="text-align: center;"></div>');
            $val = db_get("SELECT * FROM rpbios WHERE acctid='".$Char->acctid."' AND pageid='".$pid."' LIMIT 1");

            if(count($val)>0)
            {
                $head[] = 'Allgemein,title';
                $head[] = 'Seite,divider';
                // $head['titel_prev'] = 'Vorschau,preview,titel';
                $head['titel'] = 'Titel,text,255';
                $head[] = 'Aktiv,divider';
                $head['activ'] = 'Ist diese Seite zur Zeit aktiv?,bool';

                $head[] = 'Rechtevergabe: Wer darf diese und untergeordnete Seiten sehen?,divider';
                //$head['see_anon'] = 'Unangemeldete Spieler,checkbox,1';
                $head['see_demo'] = 'Der Schnupperzugang,checkbox,1';
                $head['see_reg'] = 'Angemeldete Spieler,checkbox,1';
                $head['see_friends'] = 'Meine Freunde,checkbox,1';

                $head[] = 'Inhalt,title';
                $head[] = 'HTML,divider';

                $head['html_dec'] = 'Hinweise:,viewonly';
                $val['html_dec'] = "Kürzel: Strg+S = speichern. Alt+S = Schnell-Vorschau. `nUm Bilder zu verwenden einfach [PIC=X] schreiben.`nWobei X=Kürzel des Bildes ist. zB X=mc1 => [PIC=mc1].";


                if($profi)
                {
                    $head['content'] = 'HTML,rawhtmleditor';
                    $head[] = 'Design,title';

                    $head[] = 'CSS,divider';
                    $head['css_dec'] = 'Hinweise:,viewonly';
                    $val['css_dec'] = "Kürzel: Strg+S = speichern. Alt+S = Schnell-Vorschau. `nStatt body{} bitte .userbody{} verwenden! `nUm Bilder zu verwenden einfach background(-image): url(X) schreiben.`nWobei X=Kürzel des Bildes wie in [PIC=X] ist. zB X=mc1 => url(mc1).";

                    $head['css'] = 'CSS,csseditor';

                    $head[] = 'Webfonts,title';
                    $head[] = 'Google-Webfonts,divider';

                    $head['fonts_dec'] = 'Hinweise,viewonly';
                    $val['fonts_dec'] = '`n1 Font pro Zeile!`nLink: http://www.google.com/webfonts`nEin Beispiel: family=Sanchez:400,400italic&subset=latin,latin-ext';

                    $head['fonts'] = 'CSS,textarea,50,10';
                }
                else
                {
                    $head['content'] = 'HTML,textarea,80,30,0,true';
                }


                // Farbübersicht (für Laula, by talion)

                $str_colors = '<table><tr class="trhead"><th>Code</th><th>HEX-Code</th><th>Beispiel</th></tr>';
                $res = db_query("
                    SELECT
                        color,
                        code
                    FROM
                        appoencode
                    WHERE
                        allowed	= '1'	AND
                        active	= '1'	AND
                        color		IS NOT null
                    ORDER BY
                        listorder ASC
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
                $val['color_help'] = $str_colors;
                $head[] = 'Farbcodes,title';
                $head['color_help']=',viewonly';


                $val['help'] = get_extended_text('rpbio_hilfe');
                $head[] = 'Hilfe,title';
                $head['help']=',viewonly';

                // END Farbübersicht



                $head['leuser'] = 'leuser,hidden';
                $val['leuser'] = $Char->acctid;

                $str_lnk = 'prefs_bio.php?do=edit&sdo=save&p='.$pid;
                output('`n<form action="'.$str_lnk.'" method="POST" id="rpbioajax" enctype="multipart/form-data">');
                showform($head,$val,false,'Speichern',11);
                output('</form>');
            }
            else
            {
                output("Diese Seite existiert nicht...");
            }
        }
            break;
        case 'config':
        {
            if($_GET['saved']=='ok')   output('`@Erfolgreich gespeichert.`0');
            else output('<div id="ajaxresponse" style="text-align: center;"></div>');

            $val = db_get("SELECT * FROM rpbios_config WHERE acctid='".intval($Char->acctid)."' LIMIT 1");
            if(count($val)==0)$val=array();
            else $val = array_merge($val,utf8_unserialize($val['config']));

            $head[] = 'Rechtevergabe,title';
            $head[] = 'Wer darf deine Bio sehen?,divider';
            //$head['see_anon'] = 'Unangemeldete Spieler,checkbox,1';
            $head['see_demo'] = 'Der Schnupperzugang,checkbox,1';
            $head['see_reg'] = 'Angemeldete Spieler,checkbox,1';
            $head['see_friends'] = 'Meine Freunde,checkbox,1';

            $head[] = 'Design,title';

            $head[] = 'Seite,divider';
            $head['body_back'] = 'Hintergrund-Farbe,hex_pick';
            $head['body_text'] = 'Text-Farbe,hex_pick';
            $head['body_text_fam'] = 'Text-Familie,text';
            $head['body_text_size'] = 'Text-Größe,select,8,8px,9,9px,10,10px,11,11px,12,12px,13,13px,14,14px,15,15px,16,16px,17,17px,18,18px';
            $head['fixwidth'] = 'Breite des Inhalts in % oder px,text';
            $head[] = 'Menu,divider';
            $head['menu_trenn_color'] = 'Farbe der Trennlinie,hex_pick';
            $head['menu_back'] = 'Hintergrund-Farbe,hex_pick';
            $head['menu_top_back'] = 'Link Hintergrund-Farbe,hex_pick';
            $head['menu_color'] = 'Link-Farbe,hex_pick';
            $head['menu_top_back_hover'] = 'Link Hintergrund-Farbe beim drüberfahren,hex_pick';
            $head['menu_color_hover'] = 'Link-Farbe beim drüberfahren,hex_pick';
            $head['menu_text_fam'] = 'Text-Familie,text';
            $head['menu_text_size'] = 'Text-Größe,select,8,8px,9,9px,10,10px,11,11px,12,12px,13,13px,14,14px,15,15px,16,16px,17,17px,18,18px';

            if($profi)
            {
                $head[] = 'Globales-CSS,title';
                $head[] = 'Globales CSS (wird auf jeder Seite aufgerufen),divider';

                $head['css_dec'] = 'Hinweise:,viewonly';
                $val['css_dec'] = "Um Bilder zu verwenden einfach background(-image): url(X) schreiben.`nWobei X=Kürzel des Bildes wie in [PIC=X] ist. zB X=mc1 => url(mc1).";

                $head['css'] = 'CSS,csseditor';

                $head[] = 'Webfonts,title';
                $head[] = 'Google-Webfonts,divider';

                $head['fonts_dec'] = 'Hinweise,viewonly';
                $val['fonts_dec'] = '`n1 Font pro Zeile!`nLink: http://www.google.com/webfonts`nEin Beispiel: family=Sanchez:400,400italic&subset=latin,latin-ext';

                $head['fonts'] = 'CSS,textarea,50,10';
            }

            $head['leuser'] = 'leuser,hidden';
            $val['leuser'] = $Char->acctid;

            if($val['body_text_fam']=='')$val['body_text_fam'] = 'Verdana, Arial, Helvetica, sans-serif';
            if($val['menu_text_fam']=='')$val['menu_text_fam'] = 'Verdana, Arial, Helvetica, sans-serif';

            if($val['body_text_size']=='')$val['body_text_size'] = 12;
            if($val['menu_text_size']=='')$val['menu_text_size'] = 11;

            $str_lnk = 'prefs_bio.php?do=edit&sdo=saveconfig';
            output('`n<form action="'.$str_lnk.'" method="POST"  id="rpbioajax"  enctype="multipart/form-data">');

            foreach($val as $k => $v){
                if('transparent' == $v){
                    $val[$k] = '';
                }
            }

            showform($head,$val,false,'Speichern',11);
            output('</form>');

        }
            break;
        case 'friends':
        {

            if($_GET['act']=='givekey')
            {
                $int_target = intval($_POST['acctid']);

                if($Char->acctid !=  $int_target)
                {
                    $config['friends'][$int_target] = 1;
                }

                db_query("UPDATE rpbios_config SET friends='". utf8_serialize($config['friends']) ."' WHERE acctid='".$Char->acctid."' LIMIT 1");
            }
            else if($_GET['act']=='takekey')
            {
                $int_target = intval($_GET['acctid']);
                unset($config['friends'][$int_target]);
                db_query("UPDATE rpbios_config SET friends='". utf8_serialize($config['friends']) ."' WHERE acctid='".$Char->acctid."' LIMIT 1");
            }

            $str_givekey_lnk = 'prefs_bio.php?do=edit&sdo=friends&act=givekey';
            //jslib_init().
            $footer = '<div id="search_div">
								Als FreundIn eintragen:`n`n`0
								'.form_header($str_givekey_lnk,'POST',true,'search_form','if(document.getElementById(\'search_sel\').selectedIndex > -1) {this.submit();} else {search();return false;}').'
									'.jslib_search('document.getElementById("search_form").submit();','Als FreundIn eintragen!').'
								</form>
								</div>
								';


            $i=1;
            $footer .= '`n`t`bDeine Freunde:`b';
            foreach($config['friends'] as $key => $val)
            {
                $array_user = db_fetch_assoc(db_query("SELECT * FROM accounts WHERE acctid = '".$key."'  LIMIT 1"));
                if(isset($array_user['name']))
                {
                    $footer.='`n`t'.$i.': '.CRPChat::menulink($array_user).'`0 ';
                    $footer.=' `0[ '.create_lnk('X','prefs_bio.php?do=edit&sdo=friends&act=takekey&acctid='.$key.'',true,false,'Bist Du sicher, dass du die Freundschaft beenden willst?').' ] ';
                    $i++;
                }
            }

            output($footer);
        }
            break;
        case 'exclude':
        {
            if($_GET['act']=='givekey')
            {
                $int_target = intval($_POST['acctid']);

                if($Char->acctid !=  $int_target)
                {
                    $array_user = db_fetch_assoc(db_query("SELECT superuser FROM accounts WHERE acctid = '".intval($int_target)."'  LIMIT 1"));
                    $s = $array_user['superuser'];
                    if(1 == $s ||2 == $s ||3 == $s ||4 == $s)
                    {
                        output('`$So leid es mir tut, aber Teammitglieder können nicht gebannt werden. Sie müssen den Inhalt deiner Bio kontrollieren können.`0');
                    }
                    else
                    {
                        $config['exclude'][$int_target] = 1;
                    }

                }

                db_query("UPDATE rpbios_config SET exclude='". utf8_serialize($config['exclude']) ."' WHERE acctid='".$Char->acctid."' LIMIT 1");
            }
            else if($_GET['act']=='takekey')
            {
                $int_target = intval($_GET['acctid']);
                unset($config['exclude'][$int_target]);
                db_query("UPDATE rpbios_config SET exclude='". utf8_serialize($config['exclude']) ."' WHERE acctid='".$Char->acctid."' LIMIT 1");
            }

            $str_givekey_lnk = 'prefs_bio.php?do=edit&sdo=exclude&act=givekey';

            $footer = jslib_init(). '<div id="search_div">
								Folgenden Char von deiner Bio bannen:`n`n`0
								'.form_header($str_givekey_lnk,'POST',true,'search_form','if(document.getElementById(\'search_sel\').selectedIndex > -1) {this.submit();} else {search();return false;}').'
									'.jslib_search('document.getElementById("search_form").submit();','Bannen!').'
								</form>
								</div>
								';


            $i=1;
            $footer .= '`n`t`bDie Bannliste:`b';
            foreach($config['exclude'] as $key => $val)
            {
                $array_user = db_fetch_assoc(db_query("SELECT * FROM accounts WHERE acctid = '".$key."'  LIMIT 1"));
                if(isset($array_user['name']))
                {
                    $footer.='`n`t'.$i.': '.CRPChat::menulink($array_user).'`0 ';
                    $footer.=' `0[ '.create_lnk('X','prefs_bio.php?do=edit&sdo=exclude&act=takekey&acctid='.$key.'',true,false,'Bist Du sicher, dass du den Bann aufheben willst?').' ] ';
                    $i++;
                }
            }

            output($footer);
        }
            break;
        case 'bin':
        {
            $out .= '`c`bGelöschte Seiten Übersicht`b`c`n';

            $seiten_res = db_query("SELECT * FROM rpbios WHERE acctid='".$Char->acctid."' AND parent = 0 AND deleted=1 ORDER BY sort ASC");

            $out .= '<div class="sort"><ol class="">';

            while($seite = db_fetch_assoc($seiten_res))
            {

                $subout = '';
                $subseiten = db_get_all("SELECT * FROM rpbios WHERE acctid='".$Char->acctid."' AND parent = '".$seite['pageid']."'  AND deleted=1 ORDER BY sort ASC");
                if(count($subseiten)>0)
                {
                    $subout .= '<ol>';
                    foreach($subseiten as $subseite)
                    {

                        $subout .= '<li id="'.$subseite['pageid'].'"><div>'.$subseite['titel'].'
                              <a href="prefs_bio.php?do=undel&p='.$subseite['pageid'].'" class="">[Un-Del]&nbsp;</a>
                              </div></li>';
                    }
                    $subout .= '</ol>';
                }
                $out .= '<li id="'.$seite['pageid'].'"><div>'.$seite['titel'].'
                      <a href="prefs_bio.php?do=undel&p='.$seite['pageid'].'" class="">[Un-Del]&nbsp;</a>

                     </div>'.$subout.'</li>';
            }

            $out .= '</ol></div>
                  <div id="dialog" title="Meldung"></div>
                  `n`n';
            output($out);
        }
            break;
        default:
        {
            $out .= '`c`bSeiten Übersicht`b`c`n`nDie Seiten können per Drag&Drop sortiert werden. Es ist möglich Unterseiten zu erstellen (einfach nach Rechts schieben).`n
                  `$WICHTIG: Nur die jeweils ersten 9 Seiten (und pro Seite bis zu 9 Unterseiten) werden nachher in der Bio auch wirklich angezeigt!`0`n';

            $seiten_res = db_query("SELECT * FROM rpbios WHERE acctid='".$Char->acctid."' AND parent = 0 AND deleted=0 ORDER BY sort ASC");

            $out .= '<div class="sort"><ol class="sortable">';
            $ober = 0;
            while($seite = db_fetch_assoc($seiten_res))
            {
                $ober++;
                if($seite['activ']==0) $seite['titel'] = '<strike style="background:#FF0000;">'.$seite['titel'].'</strike>';
                $subout = '';
                $subseiten = db_get_all("SELECT * FROM rpbios WHERE acctid='".$Char->acctid."' AND parent = '".$seite['pageid']."'  AND deleted=0 ORDER BY sort ASC");
                $subout .= '<ol id="ober'.$ober.'">';

                if(count($subseiten)>0)
                {

                    foreach($subseiten as $subseite)
                    {
                        if($subseite['activ']==0) $subseite['titel'] = '<strike style="background:#FF0000;">'.$subseite['titel'].'</strike>';
                        $subout .= '<li id="'.$subseite['pageid'].'"><div>'.$subseite['titel'].'
                              <a href="javascript:delbiopage('.$subseite['pageid'].')" class="edit">&nbsp;<img alt="del" title="löschen" src="./images/icons/del.gif" />&nbsp;</a>
                              <a href="prefs_bio.php?do=edit&sdo=edit&p='.$subseite['pageid'].'" class="edit">&nbsp;<img alt="edit" title="bearbeiten" src="./images/icons/edit.gif" />&nbsp;</a>
                              <a href="prefs_bio.php?do='.(($subseite['activ']==0) ? 'akt' : 'deak').'&p='.$subseite['pageid'].'" class="edit">'.(($subseite['activ']==0) ?
                                '&nbsp;<img alt="akt" title="aktivieren" src="./images/icons/visible.gif" />&nbsp;' : '&nbsp;<img alt="deakt" title="deaktivieren" src="./images/icons/invisible.gif" />&nbsp;').'
                                  </a>
                              </div></li>';
                    }

                }

                $subout .= '</ol>';
                $out .= '<li id="'.$seite['pageid'].'"><div>'.$seite['titel'].'

                      <a href="javascript:addsubbiopage('.$seite['pageid'].','.$ober.')" class="edit">&nbsp;<img alt="add" title="Unterseite hinzufügen" src="./images/icons/add.gif" />&nbsp;</a>

                      <a href="javascript:delbiopage('.$seite['pageid'].')" class="edit">&nbsp;<img alt="del" title="löschen" src="./images/icons/del.gif" />&nbsp;</a>
                      <a href="prefs_bio.php?do=edit&sdo=edit&p='.$seite['pageid'].'" class="edit">&nbsp;<img alt="edit" title="bearbeiten" src="./images/icons/edit.gif" />&nbsp;</a>
                      <a href="prefs_bio.php?do='.(($seite['activ']==0) ? 'akt' : 'deak').'&p='.$seite['pageid'].'" class="edit">'.(($seite['activ']==0) ?
                        '&nbsp;<img alt="akt" title="aktivieren" src="./images/icons/visible.gif" />&nbsp;' : '&nbsp;<img alt="deakt" title="deaktivieren" src="./images/icons/invisible.gif" />&nbsp;'
                    ).'</a>
                      </div>'.$subout.'</li>';
            }

            $out .= '</ol></div>
                  <div id="dialog" title="Meldung"></div>
                  <input type="submit" name="newrpbiopage" id="newrpbiopage" value="Neue Oberseite hinzufügen" />`n`n';
            output($out);
        }
            break;
    }

    output('<a style="position:absolute; top:12px; right:55px;" href="prefs_bio.php?do=mode&profi='.( $profi ? '0' : '1').'">Zum '.( $profi ? 'Easymodous' : 'Profimodous').' wechseln.</a><br style="clear:both;" /></div>');

    popup_footer();
}



?>