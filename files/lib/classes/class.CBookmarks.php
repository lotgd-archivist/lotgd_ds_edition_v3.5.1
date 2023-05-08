<?php
/**
* @author  Báthory
* @version DS-E V/3.x
*/

class CBookmarks
{
    private static $table = 'bookmarks';

    public static function add($acctid=0,$section='')
    {
        global $Char;

        self::del($acctid,$section);

        if($acctid==0)$acctid = $Char->acctid;
        if($section=='')$section = $Char->chat_section;

        return db_query("INSERT INTO ".self::$table." (acctid,section,lasttime,uptime) VALUES ('".intval($acctid)."','".db_real_escape_string($section)."',NOW(),NOW())");
    }

    public static function add_name($section='',$name='')
    {
        global $global_title,$Char;

        if($section=='')$section = $Char->chat_section;
        if($name=='')$name = $global_title;

        db_query("INSERT INTO
                        bookmarks_name
                        (section,name)
                        VALUES
                        ('".db_real_escape_string($section)."' , '".db_real_escape_string($name)."')
                        ON DUPLICATE KEY UPDATE name='".db_real_escape_string($name)."'
                        ");
    }

    public static function get_name($section)
    {
        $n= db_get("SELECT name FROM bookmarks_name WHERE section = '".db_real_escape_string($section)."' LIMIT 1");

        if($section != str_replace(range(0,9),'',$section))
        {
            $found_name = '';

            switch(mb_substr($section,0,4))
            {
                case 'cour':
                {
                    $id = str_replace('court','',$section);
                    $name = self::username($id).'`0';
                    $found_name = 'Gerichstverhandlung von '.$name;
                }
                    break;

                case 'rp_o':
                {
                    //rp_orte_'.( $private ? 'p' : 'o').'_'.$world.'_'.$id,"

                    $ids = explode('_',$section);
                    $wid = intval($ids[3]);
                    $id = intval($ids[4]);

                    $world = db_get("SELECT name FROM rp_worlds WHERE id=".$wid);
                    $place = db_get("SELECT name FROM rp_worlds_places WHERE id=".$id);
                    $parent = db_get("SELECT name FROM rp_worlds_places WHERE id=".intval($place['parent']));

                    $found_name = $world['name'].'`0 > '.$place['name'].( ($place['parent'] > 0) ? '`0 > '.$parent['name'] : '' );
                }
                    break;

                case 'tent':
                {
                    $id = str_replace('tent','',$section);
                    $name = self::username($id).'`0';
                    $found_name = 'Zelt in der Expedition von '.$name;
                }
                    break;

                case 'race':
                {
                    //$str_section = 'raceroom_'.$arr_race['id'];
                    $id = db_real_escape_string(str_replace('raceroom_','',$section));
                    $name = db_get("SELECT colname_plur FROM races WHERE id='".$id."'");
                    $found_name = 'Rassenraum der '.$name['colname_plur'];
                }
                    break;

                case 'Disc':
                {
                    $id = str_replace('Discuss-','',$section);
                    $name = self::username($id).'`0';
                    $found_name = 'Userdiskussion '.$name;
                }
                    break;

                case 'Towe':
                {
                    //Turmruine
                    $found_name = 'Turmruine mit '.self::partnername($section);
                }
                    break;

                case 'Clea':
                {
                    //Baumstamm Nebelgebirge
                    $found_name = 'Baumstamm Nebelgebirge mit '.self::partnername($section);
                }
                    break;

                case 'Dinn':
                {
                    //Dinner
                    $found_name = 'Dinner mit '.self::partnername($section);
                }
                    break;

                case 'hut_':
                {
                    //Die Blockhütte
                    $found_name = 'Blockhütte mit '.self::partnername($section);
                }
                    break;

                case 'wald':
                {
                    //Heiße Quelle
                    $found_name = 'Heiße Quelle mit '.self::partnername($section);
                }
                    break;

                case 'h_ro':
                {
                    $sectionb = str_replace(array('h_room','_folter','_sauna'),'',$section);
                    $ids = explode('-',$sectionb);
                    $hname = self::housename($ids[0]);
                    $ename = self::houseextname($ids[1]);
                    $found_name = ( ($ename != '') ? $ename : $n['name'] ).' im '.$hname;
                }
                    break;

                case 'h_ga':
                {
                    //'h_garden-'.$arr_ext['houseid'],'Rufen:',30,'ruft',
                    $id = str_replace('h_garden-','',$section);
                    $name = self::housename($id);
                    $found_name = 'Garten im '.$name;
                }
                    break;

                case 'hous':
                {
                    //house-'.$row['houseid']
                    $id = str_replace('house-','',$section);
                    $name = self::housename($id);
                    $found_name = $name;
                }
                    break;

                case 'stab':
                {
                    //'stables-'.$arr_ext['houseid'],'Zum Pferd
                    $id = str_replace('stables-','',$section);
                    $name = self::housename($id);
                    $found_name = 'Stall im '.$name;
                }
                    break;

                case 'smit':
                {
                    //'smithy-'.$arr_ext['houseid'],'
                    $id = str_replace('smithy-','',$section);
                    $name = self::housename($id);
                    $found_name = 'Schmiede im '.$name;
                }
                    break;

                case 'guil':
                {

                    $sectionb = str_replace('guild-','',$section);

                    $sectionbi = str_replace('_invent','',$sectionb);
                    $sectionbl = str_replace('_lib','',$sectionb);
                    $sectionbp = str_replace('_party','',$sectionb);
                    $sectionbt = str_replace('_treasure','',$sectionb);
                    $sectionbx = str_replace('_xtrm','',$sectionb);
                    $sectionbx2 = str_replace('_xtrm2','',$sectionb);
                    $sectionbpv = str_replace('_private','',$sectionb);
                    $sectionbw = str_replace('_war','',$sectionb);
                    $sectionbta = str_replace('-talk','',$sectionb);

                    if($sectionb != $sectionbi)
                    {
                        $name = self::guildname($sectionbi);
                        $found_name = 'Lagerräume der '.$name;
                    }
                    elseif($sectionb != $sectionbl)
                    {
                        $name = self::guildname($sectionbl);
                        $found_name = 'Bibliothek der '.$name;
                    }
                    elseif($sectionb != $sectionbp)
                    {
                        $name = self::guildname($sectionbp);
                        $found_name = 'Feier in der '.$name;
                    }
                    elseif($sectionb != $sectionbt)
                    {
                        $name = self::guildname($sectionbt);
                        $found_name = 'Schatzkammer der '.$name;
                    }
                    elseif($sectionb != $sectionbx)
                    {
                        $name = self::guildname($sectionbx);
                        $x = db_get("SELECT ext_room_name FROM dg_guilds WHERE guildid=".$sectionbx);
                        $found_name = 'Raum '.$x['ext_room_name'].' in der '.$name;
                    }
                    elseif($sectionb != $sectionbx2)
                    {
                        $name = self::guildname($sectionbx);
                        $x = db_get("SELECT ext_room_name2 FROM dg_guilds WHERE guildid=".$sectionbx);
                        $found_name = 'Raum '.$x['ext_room_name2'].' in der '.$name;
                    }
                    elseif($sectionb != $sectionbpv)
                    {
                        $name = self::guildname($sectionbpv);
                        $found_name = 'Hinterzimmer der '.$name;
                    }
                    elseif($sectionb != $sectionbw)
                    {
                        $name = self::guildname($sectionbw);
                        $found_name = 'Kriegsbesprechungsraum der '.$name;
                    }
                    elseif($sectionb != $sectionbta)
                    {
                        $found_name = 'Verhandlungsraum der Gilde';
                    }
                }
                    break;
            }


            if($found_name != '')
            {
                return CRPChat::htmlspecialsimple($found_name).' <small><i>`0</i></small>';
            }
        }

        return CRPChat::htmlspecialsimple($n['name']).' <small><i>`0</i></small>';
    }

    public static function houseextname($id)
    {
        global $g_arr_house_extensions;

        $h= db_get("SELECT name,owner,type FROM house_extensions WHERE id='".intval($id)."' LIMIT 1");
        $u= db_get("SELECT name FROM accounts WHERE acctid='".intval($h['owner'])."' LIMIT 1");

        if($h['name']=='')
        {
            $h['name'] = $g_arr_house_extensions[$h['type']]['colname'];
        }

        return $h['name'].'`0 von '.$u['name'].'`0';
    }

    public static function housename($id)
    {
        $h= db_get("SELECT housename,owner FROM houses WHERE houseid='".intval($id)."' LIMIT 1");
        $u= db_get("SELECT name FROM accounts WHERE acctid='".intval($h['owner'])."' LIMIT 1");
        return 'Haus '.$h['housename'].'`0 von '.$u['name'].'`0';
    }

    public static function guildname($id)
    {
        $h= db_get("SELECT name FROM dg_guilds WHERE guildid='".intval($id)."' LIMIT 1");
        return 'Gilde '.$h['name'].'`0';
    }

    public static function username($acctid)
    {
        global $Char;

        if($acctid == $Char->acctid) return $Char->name;

        $u= db_get("SELECT name FROM accounts WHERE acctid='".intval($acctid)."' LIMIT 1");

        return $u['name'];
    }

    public static function partnername($section)
    {
        global $Char;

        $ids = explode('_',$section);
        $id1 = intval($ids[1]);
        if($id1 != $Char->acctid) return self::username($id1).'`0';
        $id1 = intval($ids[2]);
        return self::username($id1).'`0';
    }

    public static function del($acctid=0,$section='')
    {
        global $Char;

        if($acctid==0)$acctid = $Char->acctid;
        if($section=='')$section = $Char->chat_section;

        return db_query("DELETE FROM ".self::$table." WHERE acctid = '".intval($acctid)."' AND section = '".db_real_escape_string($section)."'LIMIT 1");
    }

    public static function toggle($acctid=0,$section='')
    {
        global $Char;

        if($acctid==0)$acctid = $Char->acctid;
        if($section=='')$section = $Char->chat_section;

       if(self::has($acctid,$section))
        {
            return self::del($acctid,$section);
        }

        return self::add($acctid,$section);
    }

    public static function has($acctid=0,$section='')
    {
        global $Char;

        if($acctid==0)$acctid = $Char->acctid;
        if($section=='')$section = $Char->chat_section;

        $row = db_fetch_assoc(db_query("SELECT COUNT(*) AS cnt FROM ".self::$table." WHERE acctid = '".intval($acctid)."' AND section = '".db_real_escape_string($section)."'LIMIT 1"));

        return ($row['cnt']>0);
    }

    public static function read($acctid=0,$section='')
    {
        global $Char;

        if($acctid==0)$acctid = $Char->acctid;
        if($section=='')$section = $Char->chat_section;

        return db_query("UPDATE ".self::$table." SET lasttime=NOW() WHERE acctid = '".intval($acctid)."' AND section = '".db_real_escape_string($section)."' LIMIT 1");
    }

    public static function readAll($acctid=0)
    {
        global $Char;

        if($acctid==0)$acctid = $Char->acctid;

        return db_query("UPDATE ".self::$table." SET lasttime=NOW() WHERE acctid = '".intval($acctid)."' ");
    }

    public static function newcount($acctid=0)
    {
        global $Char;

        if($acctid==0)$acctid = $Char->acctid;
        $row = db_fetch_assoc(db_query("SELECT COUNT(*) AS cnt FROM ".self::$table." WHERE acctid = '".intval($acctid)."' AND uptime > lasttime"));
        return intval($row['cnt']);
    }

    public static function forbbiden($section)
    {
        $arr_forbidden = array('Courtyard','orcfield','darkhorse','goldenegg','grottofake','tempel_der_weisen');

        return in_array($section,$arr_forbidden);
    }

    public static function job($s)
    {
        global $Char;

        require_once(LIB_PATH.'profession.lib.php');

        $del = false;

        if( ($s == 'judges_ooc' || $s == 'judges')  && ($Char->profession!=PROF_JUDGE_NEW && $Char->profession!=PROF_JUDGE_HEAD && $Char->profession!=PROF_JUDGE_ENT && $Char->profession!=PROF_JUDGE) )
        {
            return true;
        }

        if( ($s == 'guardsooc' || $s == 'guards')  && ($Char->profession!=PROF_GUARD && $Char->profession!=PROF_GUARD_ENT && $Char->profession!=PROF_GUARD_HEAD && $Char->profession!=PROF_GUARD_NEW) )
        {
            return true;
        }

        if( ($s == 'temple_secret' || $s == 'temple_ooc')  && ($Char->profession!=PROF_PRIEST && $Char->profession!=PROF_PRIEST_HEAD && $Char->profession!=PROF_PRIEST_NEW) )
        {
            return true;
        }

        if( ($s == 'witch_secret' || $s == 'witch_ooc')  && ($Char->profession!=PROF_WITCH && $Char->profession!=PROF_WITCH_HEAD && $Char->profession!=PROF_WITCH_NEW) )
        {
            return true;
        }

        return $del;
    }

    public static function race($s)
    {
        global $Char;

        $del = false;

        if(mb_strpos($s,'raceroom_') !== false)
        {
            $rid = str_replace('raceroom_','',$s);
            if($rid != $Char->race)return true;
        }

        return $del;
    }

    public static function haus($s)
    {
        global $Char;

        require_once(LIB_PATH.'house.lib.php');

        $del = false;

        //haus
        if(mb_strpos($s,'house-') !== false)
        {
            $hid = intval(str_replace('house-','',$s));
            //besitzer?
            if($hid != $Char->house)
            {
                //key?
                $key = db_fetch_assoc(db_query("SELECT * FROM `keylist` WHERE value1='".$hid."' AND owner = '".$Char->acctid."' AND type='".(HOUSES_KEY_DEFAULT)."' LIMIT 1"));
                if(!isset($key['owner'])) return true;
            }
        }
        //garten
        if(mb_strpos($s,'h_garden-') !== false)
        {
            $hid = intval(str_replace('h_garden-','',$s));
            //besitzer?
            if($hid != $Char->house)
            {
                //key?
                $key = db_fetch_assoc(db_query("SELECT * FROM `keylist` WHERE value1='".$hid."' AND owner = '".$Char->acctid."' AND type='".(HOUSES_KEY_DEFAULT)."' LIMIT 1"));
                if(!isset($key['owner'])) return true;
            }
        }
              //stables
        if(mb_strpos($s,'stables-') !== false)
        {
            $hid = intval(str_replace('stables-','',$s));
            //besitzer?
            if($hid != $Char->house)
            {
                //key?
                $key = db_fetch_assoc(db_query("SELECT * FROM `keylist` WHERE value1='".$hid."' AND owner = '".$Char->acctid."' AND type='".(HOUSES_KEY_DEFAULT)."' LIMIT 1"));
                if(!isset($key['owner'])) return true;
            }
        }

        //gemach
        if(mb_strpos($s,'h_room') !== false)
        {
            $s = str_replace('h_room','',$s);
            $arr1 = explode('-',$s);
            $arr2 = explode('_',$arr1[1]);

            $hid = intval($arr1[0]);
            $eid   = intval($arr2[0]);

            $owna = db_fetch_assoc(db_query("SELECT owner,val FROM house_extensions WHERE id='".$eid."' LIMIT 1"));

            //besitzer?
            if($owna['owner'] != $Char->acctid)
            {
                if($owna['val'] == 0){
                    //key?
                    $key = db_fetch_assoc(db_query("SELECT * FROM `keylist` WHERE value1='".$hid."' AND owner = '".$Char->acctid."' AND type='".(HOUSES_KEY_DEFAULT)."' LIMIT 1"));
                    if(!isset($key['owner'])) return true;
                }else{
                    //key?
                    $key = db_fetch_assoc(db_query("SELECT * FROM `keylist` WHERE value1='".$hid."' AND value2='".$eid."' AND owner = '".$Char->acctid."' AND type='".(HOUSES_KEY_PRIVATE)."' LIMIT 1"));
                    if(!isset($key['owner'])) return true;
                }
            }

        }

        return $del;
    }

    public static function rport($s)
    {
        global $Char;
        if(mb_strpos($s,'rp_orte_p_') !== false)
        {
            //privater rp-ort
            $arr_rp = explode('_',$s);
            //rp _ orte _ p _ 5 _ 555
            $world = intval($arr_rp[3]);
            $id = intval($arr_rp[4]);

            $row = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));

            //öffentlicher privater Ort?
            if($row['restricted'] != 0)
            {
                //besitzer ?
                if($Char->acctid != $row['acctid'])
                {
                    $rights = db_get("SELECT p.* FROM rp_worlds_members AS m
                        JOIN rp_worlds_positions AS p
                        ON p.id=m.position
                        WHERE m.rportid='".CRPPlaces::parent($id)."'
                        AND m.acctid='".intval($Char->acctid)."' LIMIT 1");

                    //hat das recht private orte ohne Schlüssel zu sehen?
                    if( !(isset($rights['allrooms']) && $rights['allrooms'] == 1) )
                    {
                        //nicht dann wenigstens key?
                        $key = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places_keys WHERE placeid='".$id."' AND acctid = '".$Char->acctid."'"));
                        if(!isset($key['placeid'])){
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public static function suorte($s)
    {
        global $Char,$access_control;

        $del = false;

        if(mb_strpos($s,'superuser') !== false && !$access_control->su_check(access_control::SU_RIGHT_SEE_INTERNA))
        {
            return true;
        }

        if($s == 'sulib_new' && !$access_control->su_check(access_control::SU_RIGHT_EDITORLIBRARY))
        {
            return true;
        }

        return $del;
    }

    public static function gilden($s)
    {
        global $Char;

        require_once(LIB_PATH.'dg_funcs.lib.php');

        $del = false;

        if(mb_strpos($s,'guildcouncil') !== false && intval($Char->guildid)==0)
        {
            return true;
        }

        if(mb_strpos($s,'guild-') !== false)
        {
            if(mb_strpos($s,'guild-'.intval($Char->guildid)) === false)
            {
                return true;
            }
            else
            {
                if(mb_strpos($s,'_private') !== false)
                {
                    //hinterzimmer
                    $leader = ($Char->guildfunc == DG_FUNC_LEADER) ? true : false;
                    $treasure = ($Char->guildfunc == DG_FUNC_LEADER || $Char->guildfunc == DG_FUNC_TREASURE) ? true : false;
                    $war = ($Char->guildfunc == DG_FUNC_LEADER || $Char->guildfunc == DG_FUNC_WAR) ? true : false;
                    $members = ($Char->guildfunc == DG_FUNC_LEADER || $Char->guildfunc == DG_FUNC_MEMBERS) ? true : false;
                    $team = ($leader || $treasure || $war || $members) ? true : false;
                    $private = false;
                    $gid = intval(str_replace(array('guild-','_private'),'',$s));
                    if(item_count(' tpl_id="gldprive" AND value1='.$gid.' AND owner='.$Char->acctid)) {$private = true;}

                    if (!$team && !$private)
                    {
                        return true;
                    }
                }
            }
        }

        return $del;
    }

    public static function expe($s)
    {
        global $Char;

        $del = false;

        if(mb_strpos($s,'expedition_') !== false && $s != 'expedition_guest')
        {
            if( $Char->expedition == 0)
            {
                return true;
            }
        }

        if(mb_substr($s,0,4) ==  'tent')
        {
            if( $Char->expedition == 0)
            {
                return true;
            }
            else
            {
                  $id = intval(str_replace('tent','',$s));
                   if($id != $Char->acctid)
                   {
                       $row = db_fetch_assoc(db_query("SELECT acctid FROM account_extra_info WHERE acctid='".$id."' AND DDL_tent=".intval($Char->acctid)));
                       if(!isset($row['acctid'])) return true;
                   }
            }
        }

        return $del;
    }

    public static function cleanUp()
    {
        global $Char;

        $acctid = $Char->acctid;

        $res = db_query("SELECT * FROM ".self::$table." WHERE acctid = '".intval($acctid)."'");

        while($row = db_fetch_assoc($res))
        {
            $s = $row['section'];
            if(self::forbbiden($s) || self::job($s) || self::race($s)
                || self::haus($s) || self::rport($s) || self::suorte($s)
                || self::gilden($s) || self::expe($s)
            )
            {
                self::del($acctid,$s);
            }
        }
    }

    public static function getAjaxList()
    {
        global $Char,$session;
        $posts = array();
        $acctid = $Char->acctid;
        $res = db_query("SELECT * FROM ".self::$table." WHERE acctid = '".intval($acctid)."' AND uptime > lasttime");
        while($row = db_fetch_assoc($res))
        {
            $comres = db_query("SELECT * FROM commentary WHERE section = '".db_real_escape_string($row['section'])."' AND postdate >= '".$row['lasttime']."' AND author NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CHAT).")");
            $comout = '';
            while($cm = db_fetch_assoc($comres))
            {
                $comout .= '<div>'.commentaryline($cm,false).'</div>';
            }
            $posts[$row['id']] = $comout;
        }
        return $posts;
    }

    public static function getList($filename,$acctid=0)
    {
        global $Char,$session;

        $session['cc_section'] = array();

        if($acctid==0)$acctid = $Char->acctid;

        $um = $Char->getMulties();
        $mlts = '<option value="'.$Char->acctid.'"> '.$Char->login.' </option>';
        foreach($um as $m)
        {
            $mlts .= '<option value="'.$m['acctid'].'"> '.$m['login'].' </option>';
        }

        $time = gametime();
        $tomorrow = strtotime(date('Y-m-d H:i:s',$time).' + 1 day');
        $tomorrow = strtotime(date('Y-m-d 00:00:00',$tomorrow));
        $secstotomorrow = $tomorrow-$time;
        $realsecstotomorrow = round($secstotomorrow / (int)getsetting('daysperday',4));

        $all = isset($_GET['all']);
        $res = db_query("SELECT * FROM ".self::$table." WHERE acctid = '".intval($acctid)."' ");

        $head = '<span style="display:none;float:right;" id="c_chat_newday">'.($realsecstotomorrow-2).'</span>
                <table cellspacing="1" style="width:100%;">
                    <tr><td colspan="2">`n<strong><span class="c94">Aktuelle Kommentare</span></strong></td></tr>';

        $head .= '<tr><td colspan="2"><div style="border-bottom:1px solid #aa7800; font-weight:bolder;"><a href="'.$filename.'op=readall'.($all ? '&all=true' : '').'">Alle als gelesen markieren</a></div>`n</td></tr><tr><td colspan="2">';


        while($row = db_fetch_assoc($res))
        {
            $cc = $row['id'];
            $comres = db_query("SELECT * FROM commentary WHERE section = '".db_real_escape_string($row['section'])."' AND postdate >= '".$row['lasttime']."' AND author NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CHAT).")");
            $comout = '';
            while($cm = db_fetch_assoc($comres))
            {
                $comout .= '<div>'.commentaryline($cm,false).'</div>';
            }
            $head .= '<div class="cchatbox" id="c_'.$cc.'_v_area" style="'.((!$all && !$comout)?'display:none;':'').'">';
            $head .= '<div style="border-bottom:1px solid #aa7800;font-weight:bolder; padding-bottom: 3px;">'.self::get_name($row['section']).'<span  id="c_'.$cc.'_v_read" style="'.((!$comout)?'display:none;':'').' "> - <a href="'.$filename.'op=read&section='.$row['section'].($all ? '&all=true' : '').'">Als gelesen markieren</a></span></div>';
            $head .= '<div id="c_'.$cc.'_v_coms" style="'.((!$comout)?'display:none;':'').' border-bottom:1px solid #aa7800; padding:8px; background-color:#000;"><div id="c_'.$cc.'_coms">'.$comout.'</div></div>';
            if(true)
            {
                $config['message'] = 'Hinzufügen';
                $config['show_addform'] = true;
                $config['talkline'] = 'sagt';
                $su = db_get("SELECT su_min FROM commentary WHERE section='".db_real_escape_string($row['section'])."' ORDER BY commentid DESC LIMIT 1");
                $config['su_min'] = intval($su['su_min']);
                $config['max'] = getsetting('chat_post_len_long',500);

                //$session['chatconfig'] = $config;

                $head .='
                    <div style="margin-top: 3px;">
                    <a href="#" class="c_rpchat_link" data-cc="'.$cc.'">Schnell-Antwort</a>
                    `0<div id="c_'.$cc.'_chat_box" class="c_chat_box" style="display:none;">

                    <div class="c_chat_write" id="c_'.$cc.'_chat_write"><br>
                        <div>
                            <span><strong>Vorschau:</strong></span><br>
                            <span id="c_'.$cc.'_chat_text_preview"></span><br><br>
                        </div>
                    </div>
                    <form action="" class="c_rpchat" data-cc="'.$cc.'" method="post">
                        Hinzufügen
                        <span id="c_'.$cc.'_chat_rest"></span><span style="float:right;" class="c_chat_newday"></span>
                        <br>'.
                    (
                    $Char->prefs['chat_big_input']
                        ?
                        '<textarea name="chat_text" autocomplete="off" id="c_'.$cc.'_chat_text" data-preview="chat" class="c_chat_text input" maxlength="'.$config['max'].'" cols="80" rows="'.intval($Char->prefs['chat_big_input']).'"></textarea>'
                        :
                        '<input type="text" name="chat_text" autocomplete="off" id="c_'.$cc.'_chat_text" class="c_chat_text" data-preview="chat" maxlength="'.$config['max'].'" size="80">'
                    ).'<br><br>
                        <input type="button" id="c_'.$cc.'_chat_comsend" class="c_chat_comsend button" style="padding:3px; margin:5px;" value="Hinzufügen">
                        <input type="button" id="c_'.$cc.'_chat_edit" class="c_chat_edit button" style="padding:3px; margin:5px;" value="Letzten Post editieren">
                        <input type="button" id="c_'.$cc.'_chat_recover" class="c_chat_recover button" style="padding:3px; margin:5px;" value="RPG wiederherstellen">
                        '.( (count($um)) ? ' <span>Schreiben als: <select id="c_'.$cc.'_chat_was" class="c_chat_was">'.$mlts.'</select></span>':'').'
                    </form>

                    </div>
                    </div>
                    ';

                $session['cc_section'][$cc] = $row['section'];
                $session['cc_lasttime'][$cc] = $row['lasttime'];
                $session['c_'.$cc.'_chatconfig'] = $config;
            }
            $head .= '</div>';
        }
        $head .= '</td></tr></table>';
        output($head);
    }

    public static function verwaltung($filename,$acctid=0)
    {
        global $Char;

        if($acctid==0)$acctid = $Char->acctid;

        $res = db_query("SELECT * FROM ".self::$table." WHERE acctid = '".intval($acctid)."'");

        $head = '<table cellspacing="1" style="width:100%;">
                    <tr><td colspan="2">`n<strong><span class="c94">Abonnements verwalten</span></strong></td></tr>';

         while($row = db_fetch_assoc($res))
        {
            $head .= '<tr><td colspan="2">`n`n<div style="border-bottom:1px solid #FFCC00;font-weight:bolder;">'.self::get_name($row['section']).' <a style="float:right;" href="'.$filename.'op=del&section='.$row['section'].'">Abo entfernen!</a></div></td></tr>';

        }

        $head .= '<tr><td colspan="2"></td></tr></table>';
         output($head);
    }

    public static function ping($section)
    {
          return db_query("UPDATE ".self::$table." SET uptime=NOW() WHERE section = '".db_real_escape_string($section)."' ");
    }
}
?>
