<?php
//by bathory

class CRPChat
{
    public static $section;
    public static $out;

    public static $r;
    public static $shortcodes;
    public static $user;

    public static $status_arr = array(
        array('Unsichtbar','Mich soll keiner sehen!','./images/icons/invisible.gif'),
		array('Anwesend','Ich bin einfach nur hier!','./images/icons/visible.gif'),
		array('Warte','Ich warte auf eine RPG-Verabredung','./images/icons/warte.gif'),
		array('Suche','Ich suche einen RPG-Partner!','./images/icons/suche.gif'),
		array('RPG','Ich spiele. Das RPG kann ruhig um einige Personen erweitert werden.','./images/icons/rpg.gif'),
		array('DND RPG','Nicht stören ich spiele!','./images/icons/rpgdnd.gif'),
		array('Keine Zeit', 'Ich möchte gerade kein Rollenspiel machen! Schreibe mich deswegen bitte nicht an, danke.', './images/icons/stop.png')
    );

    public static function create_cache()
    {
        $res = db_query('SELECT * FROM commentary WHERE cached=0 ORDER BY commentid DESC');
        while ($k = db_fetch_assoc($res)) {
            $proc = CRPChat::process($k['author'], $k['comment'], true);
            db_query('UPDATE commentary SET cached=1, cache="'.db_real_escape_string($proc['cache']).'" WHERE commentid="'.$k['commentid'].'" LIMIT 1');
        }
    }

    public static function oolmenu()
    {
        $menu = array( 'header' => 'Status ändern?');
        foreach(self::$status_arr as $k => $v)
        {
            $menu['data'][self::getStatusOOL(0,$k).' '.$v[0]]=array(
                'httpreq' => array(
                    'link' => 'httpreq_chat.php?do=ool_change&id='.$k
                ),
                'condition' => true
            );
        }
        return CUsermenu::make($menu);
    }

    public static function getpostsajax()
    {
        global $Char,$session;

        self::create_cache();

        $lastid = intval($session['lastid']);
        $lastdate = isset($session['lastdate']) ? $session['lastdate'] : '0000-00-00 00:00:00';

        $ret = array();
        $ret['new'] = array();
        $ret['edited'] = array();

        $result = db_get_all(
            "SELECT 	author,commentid,cache,postdate
			FROM	 	commentary
			WHERE 		section = '".db_real_escape_string($Char->chat_section)."'
			            ".( $session['disable_npc_comment'] ? " AND self = 1 " : "  " )."
						AND deleted_by = 0
						AND author NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CHAT).")
						AND real_acctid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CHAT).")
						AND (editdate > '".db_real_escape_string($lastdate)."' OR commentid > '".$lastid."')
			ORDER BY	commentid DESC"
        );
        $time = TIME_INT;
        $found = false;
        foreach ($result as $row)
        {
            if($row['commentid'] > $lastid)
            {
                if(!$found)
                {
                    $session['lastid'] = $row['commentid'];
                    $found = true;
                }
                $ret['new'][$row['commentid']] = self::postAsHtml($row);
            }
            else
            {
                $ret['edited'][$row['commentid']] = self::postAsHtml($row,false);
            }
        }

        $session['lastdate'] = date("Y-m-d H:i:s", $time);

        CBookmarks::read();

        session_write_close();
        if($session['lasthit'] - strtotime($session['laston_back']) > getsetting('LOGINTIMEOUT',900) * 0.5) {
            user_update(
                array
                (
                    'laston'=>array('sql'=>true,'value'=>'NOW()'),
                ),
                $Char->acctid
            );
        }

        return $ret;
    }

    public static function make_color($col, $def){
        if('' == $col)return self::make_color_sub($def);
        return self::make_color_sub($col);
    }

    public static function make_color_sub($col){
        $len = mb_strlen($col);
        if($len == 7){
            return '²'.$col.';';
        }else if($len == 6){
            return '²#'.$col.';';
        }else if($len == 2){
            return '`'.mb_substr($col,1,1);
        }else if($len == 1){
            return '`'.$col;
        }
        return '²#FFFFFF;';
    }

    /**
     * @param int $acctid
     * @return array
     */
    public static function chatConfig($acctid = 0)
    {
        global $Char,$session;

        self::$r = array();
        self::$shortcodes = array();

        if($Char->isSelf($acctid))
        {
            self::$user = $Char;
        }
        else
        {
            $arr_u = db_get("SELECT * FROM accounts WHERE acctid='".intval($acctid)."' LIMIT 1");
            self::$user = (object)$arr_u;
            self::$user->prefs = utf8_unserialize(self::$user->prefs);
        }

        self::$r['name']	= self::$user->name;
        self::$r['ecol']	= self::make_color(self::$user->prefs['commentemotecolor'],'&');
        self::$r['tcol']	= self::make_color(self::$user->prefs['commenttalkcolor'],'3');

        self::$r['cbeg']	= (!self::$user->prefs['commentbegin']  ? '"' : self::$user->prefs['commentbegin']);
        self::$r['cbeg_s'] = trim(strip_appoencode(self::$r['cbeg'],3));
        self::$r['cend']	= (!self::$user->prefs['commentend']  ? '"' : self::$user->prefs['commentend']);
        self::$r['cend_s'] = trim(strip_appoencode(self::$r['cend'],3));

        self::$r['cout']	= (!self::$user->prefs['commentbeout']  ? false : true);
        self::$r['ccol']	= (!self::$user->prefs['commentbecol']  ? false : true);

        self::$r['noccol']	= (!self::$user->prefs['chat_noautocol']  ? false : true);
        self::$r['simccol']	= (!self::$user->prefs['chat_simpleautocol']  ? false : true);

        $aei = user_get_aei('msg_chars',$acctid);
        $msgChars = utf8_unserialize($aei['msg_chars']);

        self::$r['mc'] = count($msgChars);
        for($i=0; $i < self::$r['mc']; $i++)
        {
            self::$r['mc'.$i]	        =   $msgChars[$i];
            self::$r['mc'.$i.'ecol']	=   self::make_color(self::$user->prefs['msgChar_'.$i.'_commentemotecolor'],'&');
            self::$r['mc'.$i.'tcol']	=   self::make_color(self::$user->prefs['msgChar_'.$i.'_commenttalkcolor'],'3');

            self::$r['mc'.$i.'cbeg']	= (!self::$user->prefs['msgChar_'.$i.'_commentbegin']  ? '"' : self::$user->prefs['msgChar_'.$i.'_commentbegin']);
            self::$r['mc'.$i.'cbeg_s'] = trim(strip_appoencode(self::$r['mc'.$i.'cbeg'],3));
            self::$r['mc'.$i.'cend']	= (!self::$user->prefs['msgChar_'.$i.'_commentend']  ? '"' : self::$user->prefs['msgChar_'.$i.'_commentend']);
            self::$r['mc'.$i.'cend_s'] = trim(strip_appoencode(self::$r['mc'.$i.'cend'],3));

            self::$r['mc'.$i.'cout']	= (!self::$user->prefs['msgChar_'.$i.'_commentbeout']  ? false : true);
            self::$r['mc'.$i.'ccol']	= (!self::$user->prefs['msgChar_'.$i.'_commentbecol']  ? false : true);
        }


        $kn = db_get("SELECT name, state FROM disciples WHERE master=".$acctid);

        self::$r['kn'] = isset($kn['name']) ? stripslashes($kn['name']) : '';
        self::$r['state'] = isset($kn['state']) ? $kn['state'] : '';
        self::$r['kecol']	= self::make_color(self::$user->prefs['disc_commentemotecolor'],'&');
        self::$r['ktcol']	= self::make_color(self::$user->prefs['disc_commenttalkcolor'],'3');

        self::$r['kcbeg']	= (!self::$user->prefs['disc_commentbegin']  ? '"' : self::$user->prefs['disc_commentbegin']);
        self::$r['kcbeg_s'] = trim(strip_appoencode(self::$r['kcbeg'],3));
        self::$r['kcend']	= (!self::$user->prefs['disc_commentend']  ? '"' : self::$user->prefs['disc_commentend']);
        self::$r['kcend_s'] = trim(strip_appoencode(self::$r['kcend'],3));

        self::$r['kcout']	= (!self::$user->prefs['disc_commentbeout']  ? false : true);
        self::$r['kccol']	= (!self::$user->prefs['disc_commentbecol']  ? false : true);

        self::$shortcodes['%en'] = self::$user->name;
        self::$shortcodes['%wn'] = self::$user->weapon;
        self::$shortcodes['%rn'] = self::$user->armor;

        self::$shortcodes['%kn'] = self::$r['kn'];

        for($i=0; $i < 10; $i++)
        {
            self::$shortcodes['%f'.$i] = (self::$user->prefs['fx'.$i] != '') ? '²'.trim(self::$user->prefs['fx'.$i]).';' : false;
        }

        if($Char->marriedto > 0)
        {
            $partner=db_get("SELECT name FROM accounts WHERE acctid='".intval($Char->marriedto)."'");
            self::$shortcodes['%pn'] = $partner['name'];
        }
        else
        {
            self::$shortcodes['%pn'] = '';
        }

        for($i=0; $i < 10; $i++)
        {
            self::$shortcodes['%x'.$i] = (self::$user->prefs['sx'.$i] != '') ? self::$user->prefs['sx'.$i] : false;
        }

        $aei = user_get_aei('xmountname, hasxmount',$acctid);
        if($Char->hashorse > 0 )
        {
            $mount=db_get("SELECT mountname FROM mounts WHERE mountid='".intval($Char->hashorse)."'");
            self::$shortcodes['%ta'] = $mount['mountname'];
        }
        else
        {
            self::$shortcodes['%ta'] = '';
        }

        if( $aei['hasxmount'] == 1 )
        {
            self::$shortcodes['%tn'] = $aei['xmountname'];
        }
        else
        {
            self::$shortcodes['%tn'] = self::$shortcodes['%ta'];
        }

        if($Char->house > 0)
        {
            $house=db_get("SELECT housename FROM houses WHERE houseid='".$Char->house."'");
            self::$shortcodes['%hn'] = $house['housename'];
        }
        else
        {
            self::$shortcodes['%hn'] = '';
        }

        if($Char->guildid > 0)
        {
            $guild=db_get("SELECT name FROM dg_guilds WHERE guildid='".$Char->guildid."'");
            self::$shortcodes['%gn'] = $guild['name'];
        }
        else
        {
            self::$shortcodes['%gn'] = '';
        }

        self::$r['shortcodes'] = self::$shortcodes;
        self::$r['m_rights'] = $Char->rights;

        $usecchat = isset($_REQUEST['cc']) && intval($_REQUEST['cc']) > -1;

        $config = $usecchat ? $session['c_'.intval($_REQUEST['cc']).'_chatconfig'] : $session['chatconfig'];

        self::$r['m_verb'] = $config['talkline'];
        self::$r['su_min'] = $config['su_min'];
        self::$r['max'] = $config['max'];

        self::$r['comperpage'] = self::comPerPage();

        self::$r['emotes'] = db_get_all(
            "SELECT * FROM commentary_emotes
                WHERE
                 active = 1
            ORDER BY lgt DESC"
        );
        return self::$r;
    }

    public static function comPerPage()
{
    global $Char;
    $cml = $Char->prefs['commentlimit'];
    $cprefs =  intval($Char->prefs['chat_comperpage']);
    $ccml = intval($cml[$Char->chat_section]);
    $cdef = 25;
    if($ccml > 0) return $ccml;
    if($cprefs > 0) return $cprefs;
    return $cdef;
}

    public static function savePost($text,$self=1)
    {
        global $Char;

        if($Char->activated != USER_ACTIVATED_MUTE && $Char->activated != USER_ACTIVATED_MUTE_AUTO && !$Char->isDemoUser() && $text != '')
        {
	        return self::processAndInsert($text,$self);
        }

	    return false;
    }

    /**
     * @param $rawmsg
     * @param int $self
     */
    public static function processAndInsert($rawmsg,$self=0)
    {
        global $Char;

        $was = intval($_REQUEST['was']);
        $valid = false;

        if($Char->isSelf($was))
        {
            $valid = true;
            $was = $Char->acctid;
        }
        else if($Char->isMulti($was))
        {
            $valid = true;
        }

        if($valid)
        {
            $proc = self::process($was, $rawmsg);
            if(is_array($proc)){
	            return self::insert($was, $proc['comment'], $proc['cache'], $proc['su_min'], $self, $proc['flag']);
            }
        }

	    return false;
    }

    public static function insertFromArray($str_table, $arr_data = array())
    {
        $save_str = '';
        $i = 0;

        foreach($arr_data as $k => $v)
        {
            if(is_array($v) && isset($v['sql']) && $v['sql'])
            {
                $save_str .= ' '.(($i!=0) ? ',' : '').$k.' = '.$v['value'].' ';
            }
            else
            {
                $save_str .= ' '.(($i!=0) ? ',' : '').$k.' = "'.db_real_escape_string($v).'" ';
            }
            $i++;
        }

        return db_query("INSERT INTO ".$str_table." SET ".$save_str." ");
    }

    public static  function updateFromArray($str_table, $str_key, $arr_data = array())
    {
        $save_str = '';
        $i = 0;

        foreach($arr_data as $k => $v)
        {
            if(is_array($v) && isset($v['sql']) && $v['sql'])
            {
                $save_str .= ' '.(($i!=0) ? ',' : '').$k.' = '.$v['value'].' ';
            }
            else
            {
                $save_str .= ' '.(($i!=0) ? ',' : '').$k.' = "'.db_real_escape_string($v).'" ';
            }
            $i++;
        }

        return db_query("UPDATE ".$str_table." SET ".$save_str." WHERE ".$str_key." = '".intval($arr_data[$str_key])."' LIMIT 1");
    }

    /**
     * @param $author
     * @param $msg
     * @param $cache
     * @param $section
     * @param int $su_min
     * @param int $self
     * @param int $flags
     */
    public static function insertcommentary ($author, $msg, $cache, $section, $su_min=1, $self=0, $flags=0)
    {
        global $Char;

        if($_REQUEST['edit'] != 'true')
        {
            CBookmarks::ping($section);
            $array_user = db_get("SELECT * FROM accounts WHERE acctid = '".intval($author)."'  LIMIT 1");
        }

        $doppelpost = false;

        if($_REQUEST['edit'] != 'true'){
            $last_post = db_get("SELECT comment FROM commentary WHERE section='".db_real_escape_string($section)."' AND author='".intval($Char->acctid)."' AND postdate > DATE_SUB(NOW(), INTERVAL 4 SECOND) ORDER BY commentid DESC LIMIT 1");
            if(isset($last_post['comment']) && $last_post['comment'] == $msg){
                $doppelpost = true;
            }
        }
        if(!$doppelpost){

            $data = array(
                'postdate'=>array('sql'=>true,'value'=>'NOW()')
            , 'author'=>$author
            , 'real_acctid'=>(1 == $author) ? $Char->acctid : 0
            , 'comment'=>$msg
            , 'cache'=>$cache
            , 'section'=>$section
            , 'su_min'=>$su_min
            , 'self'=>$self
            , 'flags'=>$flags
            , 'edited'=>0
            , 'cached'=>1
            );

            if($_REQUEST['edit'] == 'true')
            {
                if(isset($_REQUEST['pk'])){
                    $d = self::getEditPost(true,$_REQUEST['pk']);
                }else{
                    $d = self::getEditPost(true);
                }

                if(isset($d['commentid']))
                {
                    unset($data['postdate']);
                    $data['editdate']=array('sql'=>true,'value'=>'NOW()');
                    $data['commentid'] = $d['commentid'];
                    $data['edited'] = array('sql'=>true,'value'=>'edited+1');
                    self::updateFromArray('commentary','commentid',$data);

                    $data_old['author']= $Char->acctid;
                    $data_old['comment']='`$'.$d['section'].' ('.$d['postdate'].')`0 '.$d['comment'];
                    $data_old['section']='comment_revision';
                    $data_old['edited']=0;
                    $data_old['postdate']=array('sql'=>true,'value'=>'NOW()');
                    $data_old['cache']= self::menulink(array('acctid'=>$Char->acctid, 'name' => $Char->name)).appoencode($data_old['comment'].'`0');
                    self::insertFromArray('commentary',$data_old);
                }
            }
            else
            {
                self::insertFromArray('commentary',$data);
            }
        }

    }

    public static function getEditForm($id)
    {
        global $session,$Char;

        $d = self::getEditPost(false,$id);
        if(!isset($d['comment'])) return '';

        $text = utf8_htmlentities($d['comment']);

        $config = $session['chatconfig'];

        $um = $Char->getMulties();
        $mlts = '<option value="'.$Char->acctid.'"> '.$Char->login.' </option>';
        foreach($um as $m)
        {
            $mlts .= '<option value="'.$m['acctid'].'" '.( ($m['acctid'] == $d['author']) ? 'selected' : '').'> '.$m['login'].' </option>';
        }

        return '<div id="editformit"><div class="the_chat_edit" id="chat_edit_div">
                        <div id="chat_edit_preview_hidden">
                            <span><strong>Vorschau:</strong></span><br>
                            <span id="chat_edit_preview"></span><br>
                        </div>
                    </div>
                    <form action="#" id="rpchatedit" method="post">
                        <span id="chat_edit_rest"></span>
                        <br>'.
                (
                $Char->prefs['chat_big_input']
                    ?
                    '<textarea name="chat_text" autocomplete="off" id="chat_text_edit" data-preview="chat" data-editid="'.$id.'" class="input" maxlength="'.$config['max'].'" cols="80" rows="'.intval($Char->prefs['chat_big_input']).'">'.$text.'</textarea>'
                    :
                    '<input type="text" name="chat_text" autocomplete="off" id="chat_text_edit" data-preview="chat" data-editid="'.$id.'" maxlength="'.$config['max'].'" size="80" value="'.$text.'">'
                ).'<br>
                    <input type="button" id="chat_edit_comsend" class="button" style="padding:3px; margin:5px;" value="Hinzufügen">
                    <input type="button" id="chat_edit_end" class="button" style="padding:3px; margin:5px;" value="Abbrechen">

                    '.( (count($um)) ? ' <span>Schreiben als: <select id="chat_edit_was">'.$mlts.'</select></span>':'').'

                    </form></div>';
    }

    /**
     * @param bool $extradata
     * @return array|bool|mixed|string
     */
    public static function getEditPost($extradata=false,$id='keine')
    {
        global $Char, $session;

        if('keine' === $id)
        {
            $usecchat = isset($_REQUEST['cc']) && intval($_REQUEST['cc']) > -1;
            if($usecchat){
                $cc = intval($_REQUEST['cc']);
                $sect = isset($session['cc_section'][$cc]) ? $session['cc_section'][$cc] : false;
                $last = isset($session['cc_lasttime'][$cc]) ? $session['cc_lasttime'][$cc] : 0;
                if($sect){
                    $d =  db_get(
                        "SELECT author, comment, edited ".( $extradata ? ", commentid , postdate, section" : "" )." FROM commentary
                        WHERE
                            author IN (".$Char->getMultiesIDs().")
                        AND section='".db_real_escape_string($sect)."'
                        AND postdate >= '".db_real_escape_string($last)."'
                        AND self=1
                        AND deleted_by = 0
                        ORDER BY commentid DESC LIMIT 1"
                    );
                }
            }else{
                $d =  db_get(
                    "SELECT author, comment, edited ".( $extradata ? ", commentid , postdate, section" : "" )." FROM commentary
            WHERE
                    author IN (".$Char->getMultiesIDs().")
                AND section='".db_real_escape_string($Char->chat_section)."'
                AND self=1
                AND deleted_by = 0
                    ORDER BY commentid DESC LIMIT 1"
                );
            }
        }else{
            $d =  db_get(
                "SELECT author, comment, edited ".( $extradata ? ", commentid , postdate, section" : "" )." FROM commentary
            WHERE
                    author IN (".$Char->getMultiesIDs().")
                AND section='".db_real_escape_string($Char->chat_section)."'
                AND self=1
                AND deleted_by = 0
                AND commentid = '".intval($id)."'
                LIMIT 1"
            );
        }

        if(isset($d['edited']) && intval($d['edited']) < getsetting("max_posts_edits",3))
        {
            return $d;
        }
        return array();
    }

    /**
     * @param $author
     * @param $msg
     * @param $cache
     * @param int $su_min
     * @param int $self
     * @param int $flag
     */
    public static function insert($author, $msg, $cache, $su_min=1, $self=0, $flag=0)
    {
        global $Char,$bool_comment_written,$rcomment_sections,$rcomment_sections_inside,$rcomment_sections_public,$session;

        $usecchat = isset($_REQUEST['cc']) && intval($_REQUEST['cc']) > -1;
        $section = $Char->chat_section;
        if($usecchat){
            $cc = intval($_REQUEST['cc']);
            $section = isset($session['cc_section'][$cc]) ? $session['cc_section'][$cc] : false;
        }
        if($section){
            if(!$usecchat && $_REQUEST['edit'] != 'true')
            {
                // Zufallskommentare
                // by talion
                if(e_rand(1,2) == 1 && $rcomment_sections[$section]) {
                    $weather_id = (int)getsetting('weather',1);
                    $time = gametime();
                    $hour = (int)date('H',$time);
                    $month = (int)date('m',strtotime(getsetting('gamedate','')) );
                    $section_inside = $rcomment_sections_inside[$section];
                    $section_public = $rcomment_sections_public[$section];
                    $sql = 'SELECT comment,gap,id,chance FROM random_commentary WHERE
							(section="'.$section.'" OR
							section="" '
                        .($section_inside ? ' OR (section = "all_inside")' : ' OR (section = "all_outside")')
                        .($section_public ? ' OR (section = "all_public")' : ' OR (section = "all_private")').
                        ') AND (chance > 0) AND
                    (weather = '.$weather_id.' OR weather=0) AND
							(month_min <= '.$month.' AND month_max >= '.$month.') AND
							(hour_min <= '.$hour.' AND hour_max >= '.$hour.') AND
							(rldate = CURDATE() OR rldate = "0000-00-00")
							ORDER BY RAND()';
                    $res = db_query($sql);
                    if( db_num_rows($res) ) {
                        $history = utf8_unserialize(getsetting('rcomhistory',''));
                        $random = e_rand(1,250);
                        while( $c = db_fetch_assoc($res) ) {
                            if($c['chance'] >= $random) {
                                $last = false;
                                if(is_array($history[$section])) {
                                    $start_count = sizeof($history[$section])-1;
                                    $max_count = max($start_count - $c['gap'],-1);
                                    for($i = $start_count; $i > $max_count; $i--) {
                                        if($history[$section][$i] == $c['id']) {$last=true;}
                                    }
                                }
                                if($last == false) {
                                    $proc = self::process(1, $c['comment']);
                                    if(is_array($proc)) self::insertcommentary(1, $proc['comment'], $proc['cache'], $section, $proc['su_min'], $self, $proc['flag'] );
                                    $history[$section][] = $c['id'];
                                    savesetting('rcomhistory',utf8_serialize($history));
                                    break;
                                }
                            }
                        }	// END while
                        db_free_result($res);
                    }
                }
                // END Zufallskommentare
            }

            self::insertcommentary($author, $msg, $cache, $section, $su_min, $self, $flag );

            if($_REQUEST['edit'] != 'true')
            {
                // Stats + RP-Belohnung
                $int_len = mb_strlen($msg);
                $arr_change = array( 'comments'=>'comments+1','commentlength'=>'commentlength+'.$int_len );
                if(getsetting('rpdon_dpcomment','0') && $int_len >= getsetting('rpdon_minlen',100))
                {
                    $comis = max(0,round($int_len / intval(getsetting('rpdon_minlen',100)) ));

                    $arr_sections = explode(',',getsetting('rpdon_sections','village'));
                    if(in_array($section,$arr_sections) || mb_substr($section,0,10) == 'rp_orte_o_') {
                        $arr_change['comments_rp'] = 'comments_rp+'.intval($comis);
                    }
                }
                user_set_stats( $arr_change );
                // END Stats

                $bool_comment_written = true;

                // Wenn wir uns dem Timeout nähern: Mal updaten
                // laston_back wird in user_load gesetzt und enthält den Wert des letzten lastons
                if($session['lasthit'] - strtotime($session['laston_back']) > getsetting('LOGINTIMEOUT',900) * 0.5) {

                    user_update(
                        array
                        (
                            'laston'=>array('sql'=>true,'value'=>'NOW()'),
                        ),
                        $Char->acctid
                    );
                }

                $commentary = $msg;
                $return = true;

                //sectionspecial
                if(!$usecchat && is_file('./chat_specials/'.$section.'.php') ){
                    require_once('./chat_specials/'.$section.'.php');
                }
                session_write_close();
                return $return;
            }
        }
        return true;
    }

    /**
     * @param $text
     * @return mixed
     */
    public static function htmlspecialsimple($text)
    {
        return str_replace(array('<','>'),array('&lt;','&gt;'),$text);
    }


    /**
     * @param array $cm
     * @return string
     */
    public static function menulink($cm,$xtra='')
    {
        if($cm === null)return '';
        $cm['author'] = isset($cm['author']) ? $cm['author'] : $cm['acctid'];
        return appoencode('`0<span class="usermenu" data-xtra="'.$xtra.'" data-id="'.$cm['author'].'">`&'.$cm['name'].'`0'.'</span>`0');
    }

    public static function loadRights($acctid){

        $user = db_get("SELECT surights,superuser FROM accounts WHERE acctid='".intval($acctid)."' LIMIT 1");

        $rights = utf8_unserialize($user['surights']);

        if( $user['superuser'] > -1 ){
            $arr_usergroup = CCharacter::getSUGroups( $user['superuser'] );

            if( false !== $arr_usergroup ){

                $arr_grprights = $arr_usergroup[2];
                $rights = array_merge_assoc( $arr_grprights, $rights );
            }
        }

        return $rights;
    }

    /**
     * @param $author
     * @param $rawmsg
     * @return array|bool
     */
    public static function process($author, $rawmsg, $nomax = false)
    {
        global $cache, $cbeg, $cend,$ecol,$tcol,$cout,$ccol,$sa;

        $commentary = utf8_html_entity_decode(trim($rawmsg));
        $commentary = str_replace(array("\r\n", "\r", "\n"), "`n", $commentary);
        $commentary = strip_appoencode($commentary,2,array('b','i','n'));
        $commentary = closetags($commentary,'`b`i');
        $rawmsg = $commentary;
       // $commentary = String::preg_replace('/([\S]{20,39})([\S]{20,39})/','$1 $2',$commentary);
        if(!empty($commentary))
        {
            $flag = 0;
            $sa = self::chatConfig($author);

            if(!$nomax)$commentary = mb_substr($commentary,0, $sa['max']);

            foreach($sa['shortcodes'] as $k => $v)
            {
                $commentary = str_replace($k, $v, $commentary);
            }

            $commentary = self::htmlspecialsimple($commentary);
            $cache = '';

            $rights = self::loadRights($author);

            foreach($sa['emotes'] as $emote)
            {
                utf8_preg_match("#^".$emote['regex']."#", $commentary, $match);
                if(count($match) > 1)
                {
                    if($emote['right'] != 0 && !$rights[$emote['right']])
                    {
                        $commentary = ': '.mb_substr($commentary,$emote['lgt']);
                    }
                    else
                    {
                        unset($match[0]);
                        $cache = '`0`&'.$emote['parse'];

                        foreach($match as $ek => $ev)
                        {
                            $cache = str_replace('<$m'.$ek.'>',$ev,$cache);
                            $emote['must'] = str_replace('<$m'.$ek.'>',$ev,$emote['must']);
                            $emote['name'] = str_replace('<$m'.$ek.'>',$ev,$emote['name']);
                            $emote['type'] = str_replace('<$m'.$ek.'>',$ev,$emote['type']);
                        }

                        if($emote['must'] != '' && !$sa[$emote['must']])
                        {
                            $commentary = ': '.mb_substr($commentary,$emote['lgt']);
                        }
                        else
                        {
                            foreach($sa as $sk => $sv)
                            {
                                if(!is_array($sv))
                                {
                                    $cache = str_replace('<'.$sk.'>',$sv,$cache);
                                }
                            }
                            //autocol
                            if(!$sa['noccol']){
                                $cbeg = self::htmlspecialsimple($sa[$emote['type'].'cbeg']);
                                $cend = self::htmlspecialsimple($sa[$emote['type'].'cend']);
                                $cbeg_s = $sa['simccol'] ? '"' : self::htmlspecialsimple($sa[$emote['type'].'cbeg_s']);
                                $cend_s = $sa['simccol'] ? '"' : self::htmlspecialsimple($sa[$emote['type'].'cend_s']);
                                $ecol = $sa[$emote['type'].'ecol'];
                                $tcol = $sa[$emote['type'].'tcol'];
                                $cout = $sa[$emote['type'].'cout'];
                                $ccol = $sa[$emote['type'].'ccol'];
                                $cache = utf8_preg_replace_callback('/'.utf8_preg_quote($cbeg_s).'(.*)'.utf8_preg_quote($cend_s).'/sU', function ($m){
                                    global $cache, $cbeg, $cend,$ecol,$tcol,$cout,$ccol,$sa;

                                    if(false){//utf8_preg_match('/(`[^'.utf8_preg_quote($ecol).utf8_preg_quote($tcol).'bciHn0]{1}|²#[a-fA-F0-9]{6};|²#[a-fA-F0-9]{3};)\s*'.preg_quote(utf8_htmlentities($m[0])).'/sU',utf8_htmlentities($cache)) || utf8_preg_match('/³[^³]*'.preg_quote(utf8_htmlentities($m[0])).'[^³]*³/sU',utf8_htmlentities($cache))){
                                        return $sa['simccol'] ? $cbeg.$m[1].$cend : $m[0];
                                    }else{
                                        if(!$cout && $ccol){
                                            return $ecol.$cbeg.$tcol.$m[1].$ecol.$cend.$ecol;
                                        }else if ($cout){
                                            return $tcol.$m[1].$ecol;
                                        } else{
                                            return $tcol.$cbeg.$tcol.$m[1].$tcol.$cend.$ecol;
                                        }
                                    }
                                },$cache);
                                $cache = closetags($cache,'`b`i');
                            }
                            $name = ($emote['issa'] == 0) ? $emote['name'] : $sa[$emote['name']];
                            $cache = ' <span class="usertext">'.self::menulink( array('author' => $author, 'name' => $name) ).$cache.'</span>'.'`0';
                            $cache = str_replace('`','``',appoencode($cache));
                            break;
                        }
                    }
                }
            }

            return array('comment'=> $rawmsg ,'flag' => $flag, 'cache' => $cache, 'su_min' => $sa['su_min']);
        }

        return false;
    }

    /**
     * @param $first
     * @return string
     */
    public static function timestamp($first)
    {
        return '<span data-tooltip="true" title="'.self::timePassed($first,TIME_INT).' | '.date("j-m-y G:i:s",$first).'" ><span style="color:#fff;">['.date('H:i',$first).']</span></span> ';
    }

    /**
     * @param $first
     * @param $second
     * @return bool|string
     */
    public static function timePassed($first, $second)
    {
        if($first > $second) return false;
        $td['dif'] = $second - $first;
        $td['sec'] = $td['dif'] % 60;
        $td['min'] = (($td['dif'] - $td['sec']) / 60) % 60;
        $td['std'] = (((($td['dif'] - $td['sec']) /60)-$td['min']) / 60) % 24;
        $td['day'] = floor( ((((($td['dif'] - $td['sec']) /60)-$td['min']) / 60) / 24) );
        return ( ($td['day']>0) ? $td['day'].'T ' : '').( ($td['std']>0) ? $td['std'].'S ' : '').( ($td['min']>0) ? $td['min'].'m ' : '').( ($td['sec']>0) ? $td['sec'].'s' : '');
    }

    /**
     * @param $row
     * @param bool $surround
     * @return string
     */
    public static function  postAsHtml($row, $surround = true)
    {
        global $Char;

        if($Char->prefs['chat_fettkursiv']){
            $row['cache'] = str_replace(array('<i>','<i >','</i>','<strong>','<strong >','</strong>'),'',$row['cache']);
        }
        if(!$Char->prefs['chat_newlines']){
            $row['cache'] = str_replace('<br>','',$row['cache']);
            $row['cache'] = str_replace('<br />','',$row['cache']);
        }

        $postmenu = '<div style="background-color:#'.(( $row['author'] == $Char->acctid || $Char->isMulti($row['author']) ) ?'303030':'000000')
            .'; display:none; padding:0px; text-align:right;  position:absolute; top:0; right:0;" data-id="'.$row['commentid'].'" class="postmenu" id="postmenu'.$row['commentid'].'">
            '.( ( $row['author'] == $Char->acctid || $Char->isMulti($row['author']) ) ? '<span class="editpost ui-icon ui-icon-pencil" data-id="'.$row['commentid'].'" style="float:right;"></span>' : '').'
        </div>';

        $text = (($Char->prefs['timestamps']) ? self::timestamp(strtotime($row['postdate'])) : '').'<span style="color:#fff;">'.$row['cache'].'</span>';

        $out = '
        <div class="chathovermenu" id="chathovermenu'.$row['commentid'].'" data-id="'.$row['commentid'].'">
             <div style="position:relative;" data-id="'.$row['commentid'].'" id="post'.$row['commentid'].'">'.$text.''.$postmenu.'</div>
        </div>';

        return $surround ? '<div id="comment'.$row['commentid'].'" data-id="'.$row['commentid'].'" style="width:100%;">'.$out.'</div>' : $out;
    }

    public static function getposts()
    {
        global $Char,$session;

        self::create_cache();

        $limit = self::comPerPage();
        $com = intval($_REQUEST['coms']);

        $result = db_get_all(
            "SELECT 	author,commentid,cache,postdate
			FROM	 	commentary
			WHERE 		section = '".db_real_escape_string($Char->chat_section)."'
			            ".( $session['disable_npc_comment'] ? " AND self = 1 " : "  " )."
						AND deleted_by = 0
						AND author NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CHAT).")
						 AND real_acctid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CHAT).")
			ORDER BY	commentid DESC
			LIMIT 		".($com*$limit).",$limit"
        );
        $time = TIME_INT;

        foreach ($result as $row)
        {
            self::$out = self::postAsHtml($row).self::$out;
        }

        if($com == 0)
        {
            if(isset($result[0]['commentid']))$session['lastid']=$result[0]['commentid'];
            else  $session['lastid']=0;
            $session['lastdate'] = date("Y-m-d H:i:s", $time);
            CBookmarks::read();
        }

        return '<div id="chat_out" class="chat_out">
                    <div id="chat" class="chat">
                        <div id="chat_area" class="chat_area">
                            <div id="chat_page0" style="'.($Char->prefs['chat_block'] ? 'text-align:justify;' : '').'">'.self::$out.'</div>
                            <div style="clear:both;"></div>
                        </div>
                    </div>
                </div>';
    }

    public static function getchat($config)
    {
        global $Char,$REQUEST_URI,$session;

        $out =self::getposts().'
        <div id="comscroll_nav"><span id="coms_f" class="ui-icon ui-icon-circle-arrow-e" style="float:right; display:none;"></span>
        <span id="coms_m" class="ui-icon ui-icon-circle-triangle-e" style="float:right; display:none;"></span>
        <span id="coms_p" class="ui-icon ui-icon-circle-triangle-w" style="float:left;"></span><br style="clear:both;"></div>';

        if($Char->activated == USER_ACTIVATED_MUTE_AUTO)
        {
            $out .= '`^`bNoch hast du dich noch nicht als würdig erwiesen, hier etwas zu schreiben. Falls du dies ändern willst, wende deine
					Schritte gen `iDrachenbücherei`i im Stadtzentrum und durchschreite dort die Prüfung, die dich zum Bürger '.getsetting('townname','Atrahor').'s machen wird!`b`0`n';
        }
        else if($Char->activated != USER_ACTIVATED_MUTE && $config['show_addform'])
        {
            $limit = self::comPerPage();

            $um = $Char->getMulties();
            $mlts = '<option value="'.$Char->acctid.'"> '.$Char->login.' </option>';
            foreach($um as $m)
            {
                $mlts .= '<option value="'.$m['acctid'].'"> '.$m['login'].' </option>';
            }

            $link = $REQUEST_URI;
            addnav('',$link);

            $time = gametime();
            $tomorrow = strtotime(date('Y-m-d H:i:s',$time).' + 1 day');
            $tomorrow = strtotime(date('Y-m-d 00:00:00',$tomorrow));
            $secstotomorrow = $tomorrow-$time;
            $realsecstotomorrow = round($secstotomorrow / (int)getsetting('daysperday',4));

            $out .= '`0<div class="chat_write" id="chat_write"><br>
                        <div id="chat_text_preview_hidden">
                            <span><strong>Vorschau:</strong></span><br>
                            <span id="chat_text_preview"></span><br><br>
                        </div>
                    </div>
                    <form action="'.$link.'" id="rpchat" method="post">
                        '.$config['message'].'
                        <span id="chat_rest"></span><span style="display:none;float:right;" id="chat_newday">'.($realsecstotomorrow-2).'</span>
                        <br>'.
                (
                $Char->prefs['chat_big_input']
                    ?
                    '<textarea name="chat_text" autocomplete="off" id="chat_text" data-preview="chat" class="input" maxlength="'.$config['max'].'" cols="80" rows="'.intval($Char->prefs['chat_big_input']).'"></textarea>'
                    :
                    '<input type="text" name="chat_text" autocomplete="off" id="chat_text" data-preview="chat" maxlength="'.$config['max'].'" size="80">'
                ).'<br><br>

                        <input type="button" id="chat_comsend" class="button" style="padding:3px; margin:5px;" value="Hinzufügen (Strg+Enter)">
                        <input type="button" id="chat_edit" class="button" style="padding:3px; margin:5px;" value="Letzten Post editieren (Strg+E)">
                        <input type="button" id="chat_recover" class="button" style="padding:3px; margin:5px;" value="RPG wiederherstellen">
                        '.( (count($um)) ? ' <span>Schreiben als: <select id="chat_was">'.$mlts.'</select></span>':'').'
                        <br>
                        '.self::popup('<input type="button" id="chat_email" class="button" style="padding:3px; margin:5px;" value="An EM@il">','comment2mail.php').'
                        '.( $Char->prefs['minimail'] ? self::popup('<img src="./images/mail-message-new.png" border="0" alt="Neue Brieftaube!" id="chat_newmail" style="display:none;">','mail.php'):'').'
                        <input type="button" id="chat_nonrpg" class="button" style="padding:3px; margin:5px;" value="NichtRPG '.($session['disable_npc_comment'] ? 'ein':'aus').'!">
                        '.(!CBookmarks::forbbiden(self::$section) ? '<input type="button" id="chat_abo" class="button" style="padding:3px; margin:5px;" value="Abo '.(CBookmarks::has() ? 'aus':'ein').'!">':'').'
                        <br>
                    </form>
                    <div align="right" id="comperpage_div">
                        Kommentare pro Seite:
                        <select id="comperpage">
                            <option value="5" '.($limit==5 ? 'selected' : '').'> 5 </option>
                            <option value="10" '.($limit==10 ? 'selected' : '').'> 10 </option>
                            <option value="15" '.($limit==15 ? 'selected' : '').'> 15 </option>
                            <option value="20" '.($limit==20 ? 'selected' : '').'> 20 </option>
                            <option value="25" '.($limit==25 ? 'selected' : '').'> 25 </option>
                            <option value="30" '.($limit==30 ? 'selected' : '').'> 30 </option>
                            <option value="50" '.($limit==50 ? 'selected' : '').'> 50 </option>
                            <option value="75" '.($limit==75 ? 'selected' : '').'> 75 </option>
                            <option value="100" '.($limit==100 ? 'selected' : '').'> 100 </option>
                            <option value="150" '.($limit==150 ? 'selected' : '').'> 150 </option>
                            <option value="200" '.($limit==200 ? 'selected' : '').'> 200 </option>
                        </select>
                    </div>
                    <br><br>';
        }

        return $out;
    }

    /**
     * @return string
     */
    public static function getOOL()
    {
        global $Char;

        if(getsetting('chat_who_is_here',0) == 0)return '';

        $res = db_get_all(
            "SELECT name, acctid, chat_status, login, expedition, imprisoned, activated
               FROM accounts
                    WHERE
                            chat_section='".db_real_escape_string($Char->chat_section)."'
                        AND ".user_get_online()."
                        ".( $Char->isSuperuser() ? '' : ' AND  chat_status <> '.CHAT_STATUS_INVISIBLE.' ' )."
                        AND acctid<>'".intval($Char->acctid)."'
                        AND acctid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_OOL).")
                    ORDER BY dragonkills DESC"
        );

        $t = '<table>';
        if(count($res)){
            foreach($res as $row){
                $t .= '<tr><td>'.self::getStatusOOL(0,$row['chat_status']).' '.self::menulink($row).'</td></tr>';
            }
        }else{
            $t .= '<tr><td><span class="c113">Es ist kein anderer Spieler hier!</span></td></tr>';
        }
        $t .= '</table>';
        return $t;
    }

    /**
     * @param int $acctid
     * @param bool $status
     * @param bool $show_menu
     * @param bool $show_div
     * @return string
     */
    public static function getStatusOOL($acctid = 0, $status = false, $show_menu = false, $show_div = false)
    {
        global $Char;

        if($status === false){
            if($Char->isSelf($acctid)){
                $status = $Char->chat_status;
                $show_menu = true;
            }else{
                //todo
            }
        }

        $img = '<img src="'.self::$status_arr[$status][2].'" border="0" alt="'.self::$status_arr[$status][0].'" '.($show_menu ? ' id="ool_status" ' : '').' data-tooltip="true" style="margin-right:3px;vertical-align:middle;" title="'.self::$status_arr[$status][0].'">';
        if($show_div) return '<span id="ool_status_div">'.$img.'</span>';
        return $img;
    }

    public static function popup($html,$link)
    {
        return '<a onClick="'.popup($link).';return false;" href="'.$link.'">'.$html.'</a>';
    }
}
