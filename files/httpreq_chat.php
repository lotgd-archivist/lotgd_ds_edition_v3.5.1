<?php
$DONT_OVERWRITE_NAV 	= true;
$BOOL_JS_HTTP_REQUEST 	= true;
require_once('common.php');

if(intval($Char->acctid) == 0)exit;


switch ($_REQUEST['do']) {

    case 'fix_navs':
    {
        if($access_control->su_check(access_control::SU_RIGHT_FIXNAVS))
        {
            $id = intval($_REQUEST['id']);
            user_update(
                array
                (
                    'allowednavs'=>'',
                    'output'=>'',
                    'restorepage'=>'',
                    'specialinc'=>'',
                    'pqtemp'=>'',
                    'specialmisc'=>'',
                ),
                $id
            );
            debuglog($Char->login.' hat navs reset von '.$id,$id);
            echo 'done';
        }
    }
        break;

    case 'mute':
    {
        if($access_control->su_check(access_control::SU_RIGHT_MUTE))
        {
            $id = intval($_REQUEST['id']);
            user_update(
                array
                (
                    'activated'=>USER_ACTIVATED_MUTE,
                ),
                $id
            );
            systemmail($id,'`\$Geknebelt!`0','`@'.$Char->name.'`& hat dich geknebelt, so dass du nun keine Kommentare mehr schreiben kannst. Warscheinlich hast du dich schlecht benommen oder gegen die Regeln verstoßen. Wenn du dir nicht sicher bist, solltest du vielleicht mal in einer Mail nach dem Grund fragen.');
            debuglog($Char->login.' stummmschaltung von '.$id,$id);
            echo 'done';
        }
    }
        break;

    case 'demute':
    {
        if($access_control->su_check(access_control::SU_RIGHT_MUTE))
        {
            $id = intval($_REQUEST['id']);
            user_update(
                array
                (
                    'activated'=>0,
                ),
                $id
            );
            systemmail($id,'`@Knebel entfernt!`0','`@'.$Char->name.'`& hat dich wieder von deinem Knebel befreit.');
            debuglog($Char->login.' stummmschaltung aufgehoben von '.$id,$id);
            echo 'done';
        }
    }
        break;

    case 'su_kerker':
    {
        if($access_control->su_check(access_control::SU_RIGHT_PRISON))
        {
            $id = intval($_REQUEST['id']);
            user_update(
                array
                (
                    'location'=>USER_LOC_PRISON,
                    'restatlocation'=>0,
                    'imprisoned'=>-1,
                    'allowednavs'=>utf8_serialize(array('prison.php' => true)),
                    'output'=>JS::encapsulate('window.location = "./prison.php";'),
                    'restorepage'=>'prison.php',
                    'specialinc'=>'',
                    'pqtemp'=>'',
                    'specialmisc'=>'',
                ),
                $id
            );
            systemmail($id,'`\$Eingekerkert!`0','`@'.$Char->name.'`& hat dich in den Kerker sperren lassen. Warscheinlich hast du dich schlecht benommen oder gegen die Regeln verstoßen. Wenn du dir nicht sicher bist, solltest du vielleicht mal in einer Mail nach dem Grund fragen.');
            debuglog($Char->login.' einkerkerung von '.$id,$id);
            echo 'done';
        }
    }
        break;

    case 'su_dekerker':
    {
        if($access_control->su_check(access_control::SU_RIGHT_PRISON))
        {
            $id = intval($_REQUEST['id']);
            user_update(
                array
                (
                    'location'=>0,
                    'imprisoned'=>0
                ),
                $id
            );
            systemmail($id,'`@Freilassung!`0','`@'.$Char->name.'`& hat dich wieder aus dem Kerker befreit.');
            debuglog($Char->login.' entkerkerung von '.$id,$id);
            echo 'done';
        }
    }
        break;

    case 'biolock':
    {
        if($access_control->su_check(access_control::SU_RIGHT_LOCKBIOS))
        {
            $id = intval($_REQUEST['id']);
            user_set_aei(
                array
                (
                    'biotime'=>BIO_LOCKED
                ),
                $id
            );
            systemmail($id,'`\$Bio/Steckbrief gesperrt!`0','`@'.$Char->name.'`& hat deine Bio/Steckbrief gesperrt. Warscheinlich hast du dich schlecht benommen oder gegen die Regeln verstoßen. Wenn du dir nicht sicher bist, solltest du vielleicht mal in einer Mail nach dem Grund fragen.');
            debuglog($Char->login.' Bio/Steckbrief gesperrt von '.$id,$id);
            echo 'done';
        }
    }
        break;

    case 'biounlock':
    {
        if($access_control->su_check(access_control::SU_RIGHT_LOCKBIOS))
        {
            $id = intval($_REQUEST['id']);
            user_set_aei(
                array
                (
                    'biotime'=>'0000-00-00 00:00:00'
                ),
                $id
            );
            systemmail($id,'`@Bio/Steckbrief entsperrt!`0','`@'.$Char->name.'`& hat deine Bio/Steckbrief wieder entsperrt.');
            debuglog($Char->login.' Bio/Steckbrief entsperrt von '.$id,$id);
            echo 'done';
        }
    }
        break;

    case 'imglock':
    {
        if($access_control->su_check(access_control::SU_RIGHT_LOCKIMG))
        {
            $id = intval($_REQUEST['id']);
            user_set_aei(
                array
                (
                    'imgtime'=>BIO_LOCKED
                ),
                $id
            );
            systemmail($id,'`\$Bilder gesperrt!`0','`@'.$Char->name.'`& hat deine Bilder gesperrt. Warscheinlich hast du dich schlecht benommen oder gegen die Regeln verstoßen. Wenn du dir nicht sicher bist, solltest du vielleicht mal in einer Mail nach dem Grund fragen.');
            debuglog($Char->login.' Bilder gesperrt von '.$id,$id);
            echo 'done';
        }
    }
        break;

    case 'imgunlock':
    {
        if($access_control->su_check(access_control::SU_RIGHT_LOCKIMG))
        {
            $id = intval($_REQUEST['id']);
            user_set_aei(
                array
                (
                    'imgtime'=>'0000-00-00 00:00:00'
                ),
                $id
            );
            systemmail($id,'`@Bilder entsperrt!`0','`@'.$Char->name.'`& hat deine Bilder wieder entsperrt.');
            debuglog($Char->login.' Bilder entsperrt von '.$id,$id);
            echo 'done';
        }
    }
        break;

    case 'sympvote':
    {
        echo $Char->giveSympVote(intval($_REQUEST['id']));
    }
        break;

    case 'kerker':
    {
        if( $Char->profession==PROF_GUARD_HEAD || $Char->profession==PROF_GUARD )
        {
            $ok = 0;
            $enemy = db_fetch_assoc(db_query('SELECT login, name, sex, imprisoned,loggedin, ((maxhitpoints/30)+(attack*1.5)+(defence)) AS strength, chat_section FROM accounts WHERE acctid='.intval($_REQUEST['id'])));
            $strength = (($session['user']['maxhitpoints']/30)+($session['user']['attack']*1.5)+($session['user']['defence']));

            if( $strength < $enemy['strength'] ){
                $str_back = ''.$enemy['name'].' ist zu stark für dich!';
            }
            else if( $enemy['imprisoned'] ){
                $str_back = ''.$enemy['name'].' sitzt schon im Kerker!';
            }
            else if( !$enemy['loggedin'] ){
                $str_back = ''.$enemy['name'].' ist verschwunden!';
            }

            else{
                $time = date('Y-m-d H:i:s',time()-600);
                if( $enemy['chat_section'] == $session['user']['chat_section'] ){
                    user_update(
                        array
                        (
                            'imprisoned'=>-5,
                            'location'=>USER_LOC_PRISON,
                            'restatlocation'=>0
                        ),
                        intval($_REQUEST['id'])
                    );

                    $msg = ': `5überwindet '.$enemy['login'].', packt '.($enemy['sex']?'sie':'ihn').' mit eisernem Griff und führt '.($enemy['sex']?'sie':'ihn').' Richtung Kerker!';

                    $sql = 'INSERT INTO commentary SET comment="'.db_real_escape_string($msg).'",postdate=NOW(),author='.$session['user']['acctid'].',section="'.db_real_escape_string($enemy['chat_section']).'"';
                    db_query($sql);

                    $sql = 'UPDATE account_extra_info SET profession_tmp=1 WHERE acctid='.$session['user']['acctid'];
                    db_query($sql);

                    debuglog('nutzte seine Stadtwachenfähigkeiten und verhaftete ',intval($_REQUEST['id']));
                    addnews($session['user']['name'].'`# hat '.$enemy['name'].'`# in '.($session['user']['sex']?'ihrer':'seiner').' Eigenschaft als Stadtwache festgenommen und in den Kerker gesteckt!');

                    systemmail(intval($_REQUEST['id']),'`$Verhaftet!',$session['user']['name'].'`$ hat dich soeben in '.($session['user']['sex']?'ihrer':'seiner').' Eigenschaft als Stadtwache festgenommen. Du darfst nun einen Tag im Kerker verbringen!');
                    $str_back = 'Du hast '.$enemy['name'].' eingekerkert!';
                }
                else{
                    $str_back = $enemy['name'].' ist verschwunden!';
                }

                echo $str_back;
            }
        }

    }
        break;

    case 'expe_down':
    {
        $row = db_fetch_assoc(db_query('SELECT name,login,ddl_rank FROM accounts WHERE acctid='.intval($_REQUEST['id'])));
        $ddl_rank=$row['ddl_rank'];
        if($ddl_rank==PROF_DDL_RECRUIT){
            $ddl_rank=0;
        }
        elseif($ddl_rank>PROF_DDL_RECRUIT && $ddl_rank<=PROF_DDL_COLONEL){
            if( $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $session['user']['ddl_rank']>$ddl_rank ){
                $ddl_rank--;
            }
            else{
                $str_back = 'Diese Degradierung ist dir untersagt. Bitte wende dich an deinen Vorgesetzten.';
                $no_act = true;
            }
        }
        else{
            $str_back = $row['login'].' ist garnicht in der Bürgerwehr!';
            $no_act = true;
        }

        if( !$no_act ){
            $rank=get_ddl_rank($ddl_rank);

            user_update(
                array
                (
                    'ddl_rank'=>$ddl_rank,
                ),
                intval($_REQUEST['id'])
            );
            systemmail($row['acctid'],'`$DDL: Degradierung!`0','`@'.$session['user']['name'].'`& hat dich zum '.$rank.' '.($ddl_rank?'der Bürgerwehr':'').' degradiert!');
            addnews_ddl($session['user']['name'].'`& hat '.$row['name'].' zum `^'.$rank.'`& degradiert!');
            $str_back = 'Du hast '.$row['login'].' zum '.$rank.' '.($ddl_rank?'der Bürgerwehr':'').' degradiert!';
        }

        echo $str_back;
    }
        break;

    case 'expe_up':
    {
        $row = db_fetch_assoc(db_query('SELECT name,login,ddl_rank FROM accounts WHERE acctid='.intval($_REQUEST['id'])));
        $ddl_rank=$row['ddl_rank'];

        if($ddl_rank==0){
            $ddl_rank=PROF_DDL_RECRUIT;
        }
        elseif ($ddl_rank>=PROF_DDL_RECRUIT && $ddl_rank<PROF_DDL_COLONEL){
            if( $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $session['user']['ddl_rank']>$ddl_rank ){
                $ddl_rank++;
            }
            else{
                $str_back = 'Diese Beförderung ist dir untersagt. Bitte wende dich an deinen Vorgesetzten.';
                $no_act = true;
            }
        }
        elseif($ddl_rank==PROF_DDL_COLONEL){
            $str_back = get_ddl_rank($ddl_rank).' '.$row['login'].' kann nicht weiter befördert werden!';
            $no_act = true;
        }
        else{
            $str_back = $row['login'].' hat schon ein anderes Amt!';
            $no_act = true;
        }

        if( !$no_act ){
            $rank= get_ddl_rank($ddl_rank);

            user_update(
                array
                (
                    'ddl_rank'=>$ddl_rank,
                ),
                intval($_REQUEST['id'])
            );

            systemmail(intval($_REQUEST['id']),'`$DDL: Beförderung!`0','`@'.$session['user']['name'].'`& hat dich zum '.$rank.' der Bürgerwehr befördert!');
            addnews_ddl($session['user']['name'].'`& hat '.$row['name'].' zum `^'.$rank.'`& befördert!');
            $str_back = 'Du hast '.$row['login'].' zum '.$rank.' der B&uuml;rgerwehr bef&ouml;rdert!';
        }

        echo $str_back;
    }
        break;

    case 'expe_ein':
    {
        if( $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $Char->ddl_rank >= PROF_DDL_COLONEL ){

            user_update(
                array
                (
                    'expedition'=>1,
                ),
                intval($_REQUEST['id'])
            );

            systemmail( intval($_REQUEST['id']),'`\$Einladung zur Expedition!`0','`@Du wurdest zu einer Expedition in die dunklen Lande eingeladen. Du kannst das Lager über das Stadtzentrum erreichen.');

            debuglog('hat zu Expedition eingeladen: ', intval($_REQUEST['id']));

            echo 'done';
        }
    }
        break;

    case 'expe_aus':
    {
        if( $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $Char->ddl_rank >= PROF_DDL_COLONEL ){

            user_update(
                array
                (
                    'expedition'=>0,
                ),
                intval($_REQUEST['id'])
            );

            systemmail(intval($_REQUEST['id']),'`\$Die Expedition ist für dich beendet!`0','`@Deine Einladung zur Expedition wurde dir entzogen. Du kannst in einer Anfrage nach dem Grund fragen.');

            debuglog('hat aus Expedition entlassen: ',intval($_REQUEST['id']));

            echo 'done';
        }
    }
        break;

    //usermenu end

    case 'parseconfig':
    {
        $appoencode = get_appoencode();
        $arr = array();
        $arr['regex'] = regex_appoencode(1, false);
        $arr['a'] = $appoencode;
        foreach ($appoencode as $k => $a) {
            $arr['m_hexcol'][$k] = $a['color'];
        }
        echo utf8_serialize($arr);
    }
        break;

    case 'usermenu':
    {
        $id = intval($_REQUEST['id']);
        $extra = $_REQUEST['xtra'];
        if($id > 0){
            echo CUsermenu::make(CUsermenu::getUserMenuArray($id,$extra));
        }
    }
        break;

    case 'ool_change':
    {
        $id = intval($_REQUEST['id']);

        $Char->chat_status=$id;
        saveuser();
        session_write_close();

        echo 'done';
    }
        break;

    case 'oolmenu':
    {
        echo CRPChat::oolmenu();
    }
        break;

    case 'edit_get':
    {
        $d = CRPChat::getEditPost(false);
        if (isset($d['comment'])) echo $d['comment'];
    }
        break;

    case 'edit_get_byid':
    {
        $id = intval($_REQUEST['id']);
        $d = CRPChat::getEditPost(false,$id);
        if (isset($d['comment'])) echo $d['comment'];
    }
        break;

    case 'edit_get_form':
    {
        $id = intval($_REQUEST['id']);
        echo CRPChat::getEditForm($id);
    }
        break;

    case 'abo':
    {
        CBookmarks::toggle();
    }
        break;
    case 'comperpage':
    {
        $Char->prefs['commentlimit'][$Char->chat_section] = intval($_REQUEST['com']);
        saveuser();
    }
        break;
    case 'chatpage':
    {
        $arr = array();
        $arr['newday'] = false;
        if($Char->chat_section != '')
        {
            $arr['tipping'] = '';
            $arr['posts'] = CRPChat::getpostsajax();
            $arr['newmail'] = $Char->newmail;
            $arr['ool'] = CRPChat::getOOL();


            if( !$Char->prefs['deacautond'] && is_new_day() ){
                $Char->output .= '<!--CheckNewDay()-->';
                $session['debug'] .= 'newday in httpreq';
                $session['allowednavs']=array();
                addnav('','newday.php');
                saveuser();
                $arr['newday'] = true;
            }

        }
        $arr['ool_status'] = CRPChat::getStatusOOL(0,false,true,false);
        echo utf8_serialize($arr);
    }
        break;
    case 'chatpagefull':
    {
        echo CRPChat::getposts();
    }
        break;
    case 'nonrpg':
    {
        $session['disable_npc_comment'] = !$session['disable_npc_comment'];
    }
        break;
    case 'chatsave':
    {
        if(!isset($_REQUEST['chat_text']) && isset($_REQUEST['value']) )
        {
            $_REQUEST['chat_text'] = $_REQUEST['value'];
        }

        $r = CRPChat::savePost($_REQUEST['chat_text']);
        echo (true === $r) ? 'done' : $r;
    }
        break;

    case 'cchatposts':
    {
        echo utf8_serialize(CBookmarks::getAjaxList());
    }
        break;

    case 'chatconfig':
    {
        $was = intval($_REQUEST['was']);
        $valid = false;

        if ($Char->isSelf($was)) {
            $valid = true;
            $was = $Char->acctid;
        } else if ($Char->isMulti($was)) {
            $valid = true;
        }

        if ($valid) {
            echo utf8_serialize(CRPChat::chatConfig($was));
        }
    }
        break;
}

exit;