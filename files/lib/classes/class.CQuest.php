<?php
//by bathory

class CQuest
{
    const DONE = 1;
    const OPEN = 0;
    const LOST = 2;

    const BETRETEN = 0;
    const ANSPRECHEN = 1;
    const JANEIN = 2;
    const KAMPF = 3;
    const TAUSCH = 4;

    private static $teleport = false;
    private static $teleport_link = '';

    public static function make_effects($efkts)
    {
        $arr = explode(',',$efkts);
        foreach($arr as $id)
        {
            if($id > 0)
            {
                self::make_effect($id);
            }

        }

        if(self::$teleport)
        {
            self::$teleport=false;
            $link = self::$teleport_link;
            self::$teleport_link='';
            redirect($link);
        }
    }

    public static function count_steps($id)
    {
        $z = db_get("SELECT COUNT(*) AS cnt FROM quest_events_interact WHERE questid=".intval($id));

        if(isset($z['cnt']))
        {
            return intval($z['cnt']);
        }

        return 0;
    }

    private static function make_effect($id)
    {
        global $Char;

        $b = db_get("SELECT e.*,o.link FROM quest_effekte AS e
                    JOIN quest_orte AS o
                    ON o.id=e.teleport_ort
                    WHERE e.id='".intval($id)."' LIMIT 1");

        if($b['is_teleport']){
            self::$teleport=true;
            self::$teleport_link=$b['link'];
        }

        if($b['item_give_id'] != '0' && $b['item_give_anz'] > 0){
             self::give_same_item_multi($b['item_give_id'],$b['item_give_anz']);
        }

        if($b['item_take_id'] != '0' && $b['item_take_anz'] > 0){
            self::take_item($b['item_take_id'],$b['item_take_anz']);
        }

        if($b['zaehlerid'] > 0 && ($b['zaehler_bedingung_wert'] != '' || $b['zaehler_bedingung_zahler'] > 0) ){

             self::set_zaehler($b['zaehlerid'],$b['zaehler_bedingung'],$b['zaehler_bedingung_wert'],$b['zaehler_bedingung_zahler']);
        }

        $user_beds = array(
            'gold','goldinbank','reputation','gems','gemsinbank','charm','turns','gravefights','drunkenness','playerfights','hitpoints'
        );

        foreach($user_beds as $v)
        {
           self::set_spieler($v,$b[$v.'_bedingung'],$b[$v.'_bedingung_wert']);
        }

        if($b['buff_buff_name'] != '')
        {
            $buff = array();
            foreach($b as $k => $v)
            {
                if(mb_substr($k,0,5) == 'buff_')
                {
                        $buff[str_replace('buff_','',$k)] = $v;
                }
            }
            if(isset($buff['name']))buff_add($buff);
        }

        if($b['is_death']){
            $Char->kill(0,5,false,null,null,0);
        }
    }

    private static function set_spieler($key,$op,$val)
    {
        global $Char;

        if($val == '') return false;

        $val = intval($val);

        switch($op)  //,0,=,1,+,2,-,3,*,4,/';
        {
            case 0:
            {
                $Char->$key = $val;
            }
                break;
            case 1:
            {
                $Char->$key += $val;
            }
                break;
            case 2:
            {
                $Char->$key -= $val;
            }
                break;
            case 3:
            {
                $Char->$key *= $val;
            }
                break;
            case 4:
            {
                $Char->$key /= $val;
            }
                break;
        }

        $Char->$key = round($Char->$key);
    }

    private static function set_zaehler($key,$op,$val1,$key2)
    {
        global $Char;

        if($key == 0) return false;
        $old = self::get_zaehler($key);

        $val = 0;
        if($val1 != '')
        {
            $val = intval($val1);
        }
        else if ($key2 > 0)
        {
            $val = self::get_zaehler($key2);
        }

        $newval = 0;

        switch($op)  //,0,=,1,+,2,-,3,*,4,/';
        {
            case 0:
            {
                $newval = $val;
            }
                break;
            case 1:
            {
                $newval = $old + $val;
            }
                break;
            case 2:
            {
                $newval = $old - $val;
            }
                break;
            case 3:
            {
                $newval = $old * $val;
            }
                break;
            case 4:
            {
                $newval =  $old / $val;
            }
                break;
        }

        db_query("UPDATE quest_user_zaehler
                    SET value='".intval($newval)."'
                    WHERE
                        acctid='".intval($Char->acctid)."'
                        AND zaehler='".intval($key)."'
                    LIMIT 1");
    }

    private static function get_zaehler($key)
    {
        global $Char;

        $z = db_get("SELECT value FROM quest_user_zaehler WHERE zaehler='".intval($key)."' AND acctid='".intval($Char->acctid)."' LIMIT 1");

        if(isset($z['value']))
        {
            return $z['value'];
        }
        else
        {
            db_query("INSERT INTO quest_user_zaehler (id,acctid,zaehler,value) VALUES (null ,'".intval($Char->acctid)."','".intval($key)."', 0 )");
            return 0;
        }
    }

    public static function give_same_item_multi($item,$anzahl)
    {
        for($i=0;$i<intval($anzahl);$i++)
        {
            self::give_items($item);
        }
    }

    public static function take_item($item,$anzahl=1)
    {
        global $Char;

        if($item != '0') item_delete(' tpl_id="'.db_real_escape_string($item).'" AND owner='.$Char->acctid.' ',intval($anzahl));
    }

    public static function give_items($itemss)
    {
        global $Char;

        $items = explode(',',$itemss);
        if(count($items) > 0)
        {
            require_once(LIB_PATH.'runes.lib.php');

            foreach($items as $item)
            {
                $rune 	= db_get("SELECT id FROM ".RUNE_EI_TABLE." WHERE tpl_id='".db_real_escape_string($item)."' LIMIT 1");

                if(isset($rune['id']))
                {
                    $known 	= runes_get_known(false);

                    if( $known[$rune['id']] ){
                        $tpl	= item_get_tpl('tpl_class = '.RUNE_CLASS_ID.' AND tpl_value2='.$rune['id'], 'tpl_id, tpl_name');
                        item_add($Char->acctid, $tpl['tpl_id']);
                    }
                    else{
                        item_add($Char->acctid, RUNE_DUMMY_TPL, array('tpl_value2'=>$rune['id']));
                    }

                }
                else
                {
                   if($item != '0' && $item != '') item_add($Char->acctid,$item);
                }
            }
        }

    }

    public static function give_belohnung($row)
    {
        global $Char;

        $Char->maxhitpoints += intval($row['plp']);
        $Char->donation += intval($row['dps']);
        $Char->gold += intval($row['gold']);
        $Char->gems += intval($row['gems']);
        $Char->charm += intval($row['charme']);
        $Char->turns += intval($row['wks']);
        $Char->gravefights += intval($row['gfs']);
        $Char->deathpower += intval($row['gefal']);
        $Char->experience += intval($row['exp']);

        self::give_items($row['implode_items']);

        debuglog('Quest beendet: '.var_export($row,true));
    }

    public static function open_user_quest($id)
    {
        global $Char;
        db_query("INSERT INTO quest_user
                        (id,acctid,questid,step,status,age,date_start)
                    VALUES
                        (null ,'".intval($Char->acctid)."','".intval($id)."',0,".intval(self::OPEN).",0,NOW() )");
    }

    public static function close_user_quest($id)
    {
        global $Char;
        db_query("UPDATE quest_user SET status='".intval(self::DONE)."',date_end=NOW() WHERE acctid='".intval($Char->acctid)."' AND questid='".intval($id)."' LIMIT 1");

        $u = self::get_user_quest($id);
        $q = self::get_quest($id);
        $d = user_get_aei("quests_sterne,quests_time,quests_solved");

        user_set_aei(array(
            'quests_sterne'     =>      ($d['quests_sterne']+$q['dificulty']),
            'quests_time'       =>      ($d['quests_time']+$u['age']),
            'quests_solved'     =>      ($d['quests_solved']+1)
        ));

    }

    public static function stepup_user_quest($id)
    {
        global $Char;
        db_query("UPDATE quest_user SET step=step+1 WHERE acctid='".intval($Char->acctid)."' AND questid='".intval($id)."' LIMIT 1");
    }

    public static function check_bedingungen($beds,$testonly=false,$reset=false)
    {
        $letext = '';

        $arr = explode(',',$beds);
        foreach($arr as $id)
        {
            if($id > 0)
            {
                $lebdu = self::check_bedingung($id,$testonly,$reset);
                if($testonly === false && $lebdu === false)
                {
                    return false;
                }
                else if($testonly)
                {
                    $letext .= $lebdu;
                }
            }

        }
        if(!$testonly)
        {
            return true;
        }
        else
        {
            return $letext;
        }
    }

    private static function check_bedingung($id,$testonly=false,$reset=false)
    {
        global $Char;

        $letext = '';
        $temp_bool = true;
        $beds_out = array('=','<','>','<=','>=');

        $b = db_get("SELECT * FROM quest_bedingung WHERE id='".intval($id)."' LIMIT 1");

        //zufall
        if($b['zufall'] < 999)
        {
            $letext .= '<li>`^Glück</li>';
            if(e_rand(0,100) > $b['zufall'] ){
                if(!$testonly) {
                    return false;
                }
            }
        }

        if($b['titel_bedingung'] != '')
        {
            $titel1 = trim(str_replace($Char->login,'',strip_appoencode($Char->name,3)));
            $titel2 = mb_substr($titel1,1);
            $titbed = strip_appoencode($b['titel_bedingung'],3);

            if($titbed != $titel1 && $titbed != $titel2) {
                if(!$testonly) {
                    return false;
                } else {
                    $temp_bool = false;
                }
            }

            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Titel tragen: '.$b['titel_bedingung'].'</li>';
            $temp_bool = true;
        }

        $user_beds = array(
            'gold'=>'gold',
            'goldinbank'=>'goldinbank',
            'gems'=>'gems',
            'gemsinbank'=>'gemsinbank',
            'level'=>'level',
            'dk'=>'dragonkills',
            'wks'=>'turns',
            'gf'=>'gravefights'
        );

        $user_beds_out = array(
            'gold'=>'Gold',
            'goldinbank'=>'Gold auf der Bank',
            'gems'=>'Edelsteine',
            'gemsinbank'=>'Edelsteine auf der bank',
            'level'=>'Level',
            'dk'=>'Heldentaten',
            'wks'=>'Waldkämpfe',
            'gf'=>'Grabkämpfe'
        );

        foreach($user_beds as $k => $v)
        {
            if(!self::check_spieler($v,$b[$k.'_bedingung'],$b[$k.'_bedingung_wert'])) {
                if(!$testonly) {
                    return false;
                } else {
                    $temp_bool = false;
                }
            };
            if($b[$k.'_bedingung_wert'] != '')$letext .= '<li>`'.($temp_bool ? '@' : '4').''.$user_beds_out[$k].' '.$beds_out[$b[$k.'_bedingung']].' '.$b[$k.'_bedingung_wert'].' ('.$Char->$v.')</li>';
            $temp_bool = true;
        }

        if($b['rune_ident']>0)
        {
            require_once(LIB_PATH.'runes.lib.php');
            $kn = runes_get_known();

            if(count($kn) < $b['rune_ident']) {
                if(!$testonly) {
                    return false;
                } else {
                    $temp_bool = false;
                }
            }

            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Mindest Runenrang: '.runes_get_rank($b['rune_ident'],0).'</li>';
            $temp_bool = true;
        }

        if(1 == $b['has_horse'])
        {
            if(0 == $Char->hashorse)
            {
                if(!$testonly)
                {
                    return false;
                }
                else
                {
                    $temp_bool = false;
                }
            }

            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Ein Tier besitzen</li>';
            $temp_bool = true;
        }

        if(1 == $b['has_bathi'])
        {
            if( $Char->bathi != 8)
            {
                if(!$testonly)
                {
                    return false;
                }
                else
                {
                    $temp_bool = false;
                }
            }

            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Bathis-Puppe besitzen</li>';
            $temp_bool = true;
        }

        if(1 == $b['has_house']) {
            if(0 == $Char->house){
                if(!$testonly) {
                    return false;
                } else {
                    $temp_bool = false;
                }
            }

            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Ein Haus besitzen</li>';
            $temp_bool = true;
        }

        if(1 == $b['is_drunk']) {
            if(0 == $Char->drunkenness){
                if(!$testonly) {
                    return false;
                } else {
                    $temp_bool = false;
                }
            }
            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Angetrunken sein</li>';
            $temp_bool = true;
        }

        if(1 == $b['is_health']) {
            if($Char->hitpoints != $Char->maxhitpoints){
                if(!$testonly) {
                    return false;
                } else {
                    $temp_bool = false;
                }
            }
        }

        if(1 == $b['has_disc'])
        {
            $disc = db_get('SELECT master FROM disciples WHERE master = '.intval($Char->acctid).' LIMIT 1');
            if(!isset($disc['master'])) {
                if(!$testonly) {
                    return false;
                } else {
                    $temp_bool = false;
                }
            }
            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Einen Knappen besitzen</li>';
            $temp_bool = true;
        }

        $male = explode(',',$b['implode_male']);

        foreach($male as $mal)
        {
            $mal = intval($mal);
            if($mal > 0)
            {
                if(!($Char->marks & $mal)){
                    if(!$testonly) {
                        return false;
                    } else {
                        $temp_bool = false;
                    }
                }
                $malname = '';

                switch($mal)
                {
                    case CHOSEN_AIR :   $malname = 'Das Mal der Luft besitzen';
                        break;
                    case CHOSEN_EARTH :   $malname = 'Das Mal der Erde besitzen';
                        break;
                    case CHOSEN_FIRE :   $malname = 'Das Mal des Feuers besitzen';
                        break;
                    case CHOSEN_SPIRIT :   $malname = 'Das Mal des Geistes besitzen';
                        break;
                    case CHOSEN_WATER :   $malname = 'Das Mal des Wassers besitzen';
                        break;
                    case CHOSEN_BLOODGOD :   $malname = 'Ein Pakt mit dem Blutsgott haben';
                        break;
                }

                $letext .= '<li>`'.($temp_bool ? '@' : '4').''.$malname.'</li>';
                $temp_bool = true;
            }
        }

        $time = getgametime(true);
        $time = explode(':',$time);
        $uhr = intval($time[0]);

        if($uhr == 0)$uhr=24;


        if($b['minstd'] > 0 && $b['maxstd'] > 0)
        {
            if($b['minstd'] < $b['maxstd'])
            {
                if($uhr < $b['minstd']) {
                    if(!$testonly) { return false; } else { $temp_bool = false;}
                }
                if($uhr > $b['maxstd']) {
                    if(!$testonly) { return false; } else { $temp_bool = false;}
                }
            }
            else
            {
                if($uhr < $b['minstd'])
                {
                    if($uhr > $b['maxstd']) {
                        if(!$testonly) { return false; } else {$temp_bool = false; }
                    }
                }
            }

            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Zwischen '.$b['minstd'].' Uhr und '.$b['maxstd'].' Uhr</li>';
            $temp_bool = true;
        }

        else if($b['minstd'] > 0)
        {
            if($uhr < $b['minstd']) {
                if(!$testonly) { return false; } else {$temp_bool = false; }
            }

            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Zwischen '.$b['minstd'].' Uhr und 24 Uhr</li>';
            $temp_bool = true;

        }
       // if($Char->acctid==45195)die(print_r($b['minstd']));
        else if($b['maxstd'] > 0)
        {
            if($uhr == 24)$uhr = 0;

            if($uhr > $b['maxstd']) {
                if(!$testonly) { return false; } else { $temp_bool = false;}
            }

            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Zwischen 0 Uhr und '.$b['maxstd'].' Uhr</li>';
            $temp_bool = true;
        }

        $ws = explode(',',$b['implode_wther']);
        $wb = (count($ws) > 0 && $b['implode_wther'] != 0) ? false : true;

        $dolewetter = !$wb;

        $wetter = 'Wetter aus einem der folgenden:<ul>';

        foreach($ws as $w)
        {
            if($w > 0)
            {
                 if(Weather::$actual_weather == $w)
                 {
                     $wb = true;
                 }

                $wetter .= '<li style="font-size:8px;">'.Weather::$weather[$w]['name'].( isset(Weather::$weather[$w]['name_night']) ? ' / '.Weather::$weather[$w]['name_night'] : ''  ).'</li>';
            }
        }

        $wetter .= '</ul>';

        if(!$wb){
            if(!$testonly) { return false; } else {$temp_bool = false; }
        }

        if($dolewetter)
        {
            $letext .= '<li>`'.($temp_bool ? '@' : '4').$wetter.'</li>';
            $temp_bool = true;
        }

        $date = array();

        get_gamedate_parts($date);

        $monat = intval($date['m']);
        $tag = intval($date['d']);

        $ms = explode(',',$b['implode_monat']);
        $mb = (count($ms) > 0 && $b['implode_monat'] != 0) ? false : true;

        $dolemonat= !$mb;

        $diemonate = array("","Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", " August", "September", "Oktober","November","Dezember");

        $montxt = 'Monat aus einem der folgenden:<ul>';

        foreach($ms as $m)
        {
            if($m > 0)
            {
                if($monat == $m)
                {
                    $mb = true;

                }

                $montxt .= '<li style="font-size:8px;">'.$diemonate[$m].'</li>';
            }
        }

        $montxt .= '</ul>';

        if(!$mb){
            if(!$testonly) { return false; } else { $temp_bool = false;}
        }

        if($dolemonat)
        {
            $letext .= '<li>`'.($temp_bool ? '@' : '4').$montxt.'</li>';
            $temp_bool = true;
        }

        $ts = explode(',',$b['implode_tag']);
        $tb = (count($ts) > 0 && $b['implode_tag'] != 0) ? false : true;

        $doletag = !$tb;

        $daytxt = 'Monatstag aus einem der folgenden:<ul>';

        foreach($ts as $t)
        {
            if($t > 0)
            {
                if($tag == $t)
                {
                    $tb = true;

                }
                $daytxt .= '<li style="font-size:8px;">'.$t.'</li>';
            }
        }

        $daytxt .= '</ul>';

        if(!$tb){
            if(!$testonly) { return false; } else {$temp_bool = false; }
        }

        if($doletag)
        {
            $letext .= '<li>`'.($temp_bool ? '@' : '4').$daytxt.'</li>';
            $temp_bool = true;
        }

        if(($b['item_id'] != '0' || $b['item_cls'] != '0') && ( $b['item_anz_bedingung_zahler'] != 0 || $b['item_anz_bedingung_wert'] != '' ) )
        {

            $hastobe = 0;
            $has = 0;

            if($b['item_anz_bedingung_wert'] != '') $hastobe = $b['item_anz_bedingung_wert'];
            else if($b['item_anz_bedingung_zahler'] != 0) $hastobe = self::get_zaehler($b['item_anz_bedingung_zahler']);

            if($b['item_id'] != '0')  $has = item_count( ' tpl_id="'.db_real_escape_string($b['item_id']).'" AND owner='.intval($Char->acctid) );
            else if($b['item_cls'] != '0') $has = item_count( ' tpl_class="'.db_real_escape_string($b['item_cls']).'" AND owner='.intval($Char->acctid), true );

            switch($b['item_anz_bedingung']) //0,=,1,<,2,>,3,<=,4,>=
            {
                case 0:
                {
                    if(!($hastobe == $has)) {
                        if(!$testonly) { return false; } else { $temp_bool = false;}
                    }
                }
                    break;
                case 1:
                {
                    if(!($has < $hastobe)) {
                        if(!$testonly) { return false; } else {$temp_bool = false; }
                    }
                }
                    break;
                case 2:
                {
                    if(!($has > $hastobe)) {
                        if(!$testonly) { return false; } else {$temp_bool = false; }
                    }
                }
                    break;
                case 3:
                {
                    if(!($has <= $hastobe)) {
                        if(!$testonly) { return false; } else {$temp_bool = false; }
                    }
                }
                    break;
                case 4:
                {
                    if(!($has >= $hastobe)) {
                        if(!$testonly) { return false; } else {$temp_bool = false; }
                    }
                }
                    break;
            }


            $itemname = db_get("SELECT tpl_name FROM items_tpl WHERE tpl_id='".db_real_escape_string($b['item_id'])."' LIMIT 1");

            $letext .= '<li>`'.($temp_bool ? '@' : '4').'Item: '.str_replace('`0','',strip_appoencode($itemname['tpl_name'])).' '.$beds_out[$b['item_anz_bedingung']].' '.$hastobe.' ('.$has.')</li>';
            $temp_bool = true;
        }


        if(($b['zaehlerid'] != 0) && ( $b['zaehler_bedingung_wert'] != 0 || $b['zaehler_bedingung_zahler'] != '' ) )
        {
            $hastobe = 0;
            $has = 0;

            if($b['zaehler_bedingung_wert'] != '') $hastobe = $b['zaehler_bedingung_wert'];
            else if($b['zaehler_bedingung_zahler'] != 0) $hastobe = self::get_zaehler($b['zaehler_bedingung_zahler']);

            if($b['zaehlerid'] != 0) $has = self::get_zaehler($b['zaehlerid']);

            if($reset)$has=0;

            switch($b['zaehler_bedingung']) //0,=,1,<,2,>,3,<=,4,>=
            {
                case 0:
                {
                    if(!($hastobe == $has)) {
                        if(!$testonly) { return false; } else { $temp_bool = false;}
                    }
                }
                    break;
                case 1:
                {
                    if(!($has < $hastobe)) {
                        if(!$testonly) { return false; } else {$temp_bool = false; }
                    }
                }
                    break;
                case 2:
                {
                    if(!($has > $hastobe)) {
                        if(!$testonly) { return false; } else {$temp_bool = false; }
                    }
                }
                    break;
                case 3:
                {
                    if(!($has <= $hastobe)) {
                        if(!$testonly) { return false; } else {$temp_bool = false; }
                    }
                }
                    break;
                case 4:
                {
                    if(!($has >= $hastobe)) {
                        if(!$testonly) { return false; } else {$temp_bool = false; }
                    }
                }
                    break;
            }

            $itemname = db_get("SELECT name_book FROM quest_zaehler WHERE id='".intval($b['zaehlerid'])."' LIMIT 1");

            $letext .= '<li>`'.($temp_bool ? '@' : '4').''.str_replace('`0','',strip_appoencode($itemname['name_book'])).' '.$beds_out[$b['zaehler_bedingung']].' '.$hastobe.' ('.$has.')</li>';
            $temp_bool = true;
        }

        if($b['must_questid'] > 0)
        {
            $q = db_get("SELECT id FROM quest_user WHERE acctid='".intval($Char->acctid)."' AND status='".intval(self::DONE)."' AND questid='".intval($b['must_questid'])."' LIMIT 1");
            if(!isset($q['id'])) {
                if(!$testonly) { return false; } else {$temp_bool = false; }

                $itemname = db_get("SELECT questname FROM quest_events_orte WHERE id='".intval($b['must_questid'])."' LIMIT 1");

                $letext .= '<li>`'.($temp_bool ? '@' : '4').'Quest "'.str_replace('`0','',strip_appoencode($itemname['questname'])).'" erfüllt haben</li>';
                $temp_bool = true;

            }
        }

        if(!$testonly) {
            return true;
        }
        else{
             return $letext;
        }
    }

    public static function get_user_quest($id)
    {
        global $Char;

        $z = db_get("SELECT * FROM quest_user WHERE questid='".intval($id)."' AND acctid='".intval($Char->acctid)."' LIMIT 1");

        if(isset($z['id']))
        {
            return $z;
        }

        return false;

    }

    public static function get_quest($id)
    {
        global $Char;

        $z = db_get("SELECT q.*,o.link,o.name AS ortname FROM quest_events_orte AS q
                        JOIN quest_orte AS o
                        ON o.id=q.ort
                        WHERE
                        q.id='".intval($id)."'
                       AND  q.activ=1
                         LIMIT 1");

        if(isset($z['id']))
        {
            return $z;
        }

        return false;

    }

    public static function get_quest_aktion($id)
    {
        global $Char;

        $z = db_get("SELECT q.*,o.link,o.name AS ortname FROM quest_events_interact AS q
                        JOIN quest_orte AS o
                        ON o.id=q.ort
                        WHERE
                        q.id='".intval($id)."'
                       AND  q.activ=1
                         LIMIT 1");

        if(isset($z['id']))
        {
            return $z;
        }

        return false;

    }

    private static function check_spieler($key,$op,$val)
    {
        global $Char;
        if($val=='')return true;
        switch($op) //0,=,1,<,2,>,3,<=,4,>=
        {
            case 0:
            {
                if($Char->$key == $val) return true;
            }
                break;
            case 1:
            {
                if($Char->$key < $val) return true;
            }
                break;
            case 2:
            {
                if($Char->$key > $val) return true;
            }
                break;
            case 3:
            {
                if($Char->$key <= $val) return true;
            }
                break;
            case 4:
            {
                if($Char->$key >= $val) return true;
            }
                break;
        }

        return false;
    }

    public static function checkplace($place)
    {
        global $Char;
        if(!self::is_activ()) return false;
        if($_GET['op']=='fight') return false;

        if(mb_strpos($place,'&c=') !== false)$place = mb_substr($place,0,mb_strpos($place,'&c='));
        if(mb_strpos($place,'?c=') !== false)$place = mb_substr($place,0,mb_strpos($place,'?c='));

        $ort = db_get("SELECT id FROM quest_orte WHERE link='". db_real_escape_string($place) ."' LIMIT 1");

        if(isset($ort['id']) && $ort['id'] > 0)
        {
            $ids = '0';
            $res = db_query("SELECT zaehler FROM quest_user_zaehler WHERE acctid=".intval($Char->acctid));
            while ($row = db_fetch_assoc($res)) {
                $ids .= ','.$row['zaehler'];
            }
            $tempres = db_query("SELECT id FROM quest_zaehler WHERE id NOT IN (".$ids.")");
            while($temp = db_fetch_assoc($tempres ))
            {
                db_query("INSERT INTO quest_user_zaehler (id,acctid,zaehler,value) VALUES (null ,'".intval($Char->acctid)."','".intval($temp['id'])."', 0 )");
            }

            self::process_zaehler_ort($ort['id']);

            //interaktionen
            $acts = db_query("SELECT i.*,u.step FROM quest_events_interact  AS i
                                JOIN quest_user AS u
                                ON i.questid=u.questid
                                WHERE
                                        ort='". intval($ort['id']) ."'
                                    AND activ=1
                                    AND u.status='".self::OPEN."'
                                    AND u.acctid='".intval($Char->acctid)."'
                                    AND i.interactid=u.step+1
                                    ORDER BY i.id ASC
                                    ");
            $cl_acts = array();
            while($act = db_fetch_assoc($acts))
            {
                //bedingungen checken!
                $bed = self::check_bedingungen($act['implode_sehen_bedingung']);

                //sofort interaction testen!
                if($bed && $act['typ']==self::BETRETEN) redirect('quest_aktion.php?id='.$act['id']);

                if($bed) $cl_acts[$act['link']] = 'quest_aktion.php?id='.$act['id'];
            }

            //quests
            $quests = db_query("SELECT * FROM quest_events_orte
                                    WHERE
                                            ort='". intval($ort['id']) ."'
                                        AND activ=1
                                        AND id NOT IN (SELECT questid FROM quest_user WHERE acctid='".intval($Char->acctid)."' AND status > ".self::OPEN.")
                                        ORDER BY id ASC
                                        ");

            $cl_quests = array();
            while($quest = db_fetch_assoc($quests))
            {

                //bedingungen checken!
                $bed = self::check_bedingungen($quest['implode_sehen_bedingung']);

                if(self::get_user_quest($quest['id'])!==false)$bed=true;

                if($bed) $cl_quests[$quest['nav']] = 'quest.php?id='.$quest['id'];
            }

            if(count($cl_quests)>0) addnav('`@Quests`0');
            foreach($cl_quests as $k => $v) addnav($k,$v);

            if(count($cl_acts)>0) addnav('`dAktionen`0');
            foreach($cl_acts as $k => $v) addnav($k,$v);
        }

        return false;
    }

    private static function process_zaehler_ort($id)
    {
        global $Char;

        //reset zaehler
        db_query("UPDATE quest_user_zaehler AS u
                 JOIN quest_zaehler AS q
                 ON u.zaehler=q.id
                    SET
                        u.value=0
                     WHERE
                            u.acctid = '".intval($Char->acctid)."'
                        AND q.activ=1
                        AND q.rs_ort=".intval($id)."
                  ");

        //increment zaehler
        db_query("UPDATE quest_user_zaehler AS u
                 JOIN quest_zaehler AS q
                 ON u.zaehler=q.id
                    SET
                        u.value=u.value+1
                     WHERE
                            u.acctid = '".intval($Char->acctid)."'
                        AND q.activ=1
                        AND q.up_ort=".intval($id)."
                  ");
    }

    private static function process_zaehler($do)
    {
        global $Char;

        //reset zaehler
        db_query("UPDATE quest_user_zaehler AS u
                 JOIN quest_zaehler AS q
                 ON u.zaehler=q.id
                    SET
                        u.value=0
                     WHERE
                            u.acctid = '".intval($Char->acctid)."'
                        AND q.activ=1
                        AND q.rs_".$do."=1
                  ");

        //increment zaehler
        db_query("UPDATE quest_user_zaehler AS u
                 JOIN quest_zaehler AS q
                 ON u.zaehler=q.id
                    SET
                        u.value=u.value+1
                     WHERE
                            u.acctid = '".intval($Char->acctid)."'
                        AND q.activ=1
                        AND q.up_".$do."=1
                  ");
    }

    public static function newday()
    {
        global $Char;
        if(!self::is_activ()) return false;

        self::process_zaehler('nd');

        //incremnt age of open quests!
        db_query("UPDATE quest_user AS u
                 JOIN quest_events_orte AS q
                 ON u.questid=q.id
                    SET
                        u.age=u.age+1
                     WHERE
                            u.acctid = '".intval($Char->acctid)."'
                        AND u.status='".intval(self::OPEN)."'
                        AND q.activ=1
                        AND q.verfall > 0
                  ");
        //set lost if to old

        db_query("UPDATE quest_user AS u
                 JOIN quest_events_orte AS q
                 ON u.questid=q.id
                    SET
                        u.status='".intval(self::LOST)."'
                     WHERE
                            u.acctid = '".intval($Char->acctid)."'
                        AND u.status='".intval(self::OPEN)."'
                        AND q.activ=1
                        AND q.verfall > 0
                        AND u.age >= (q.verfall * ".intval(getsetting('dayparts','1')).")
                  ");

    }

    public static function ressurect()
    {
        global $Char;
        if(!self::is_activ()) return false;
        self::process_zaehler('wb');
    }

    public static function fight($victory=false,$typ='')
    {
        if(!self::is_activ()) return false;
        if($victory)self::process_zaehler($typ.'fight_win');
        else self::process_zaehler($typ.'fight_loose');
    }

    public static function heal()
    {
        global $Char;
        if(!self::is_activ()) return false;
        self::process_zaehler('heal');
    }

    public static function died()
    {
        global $Char;
        if(!self::is_activ()) return false;
        self::process_zaehler('die');
    }


    //aktiv

    public static function is_activ()
    {
        global $Char;
        /*
        $access = array(44993);
        $sus = array(1,2);
        if(in_array($Char->acctid,$access)) return true;
        if(in_array($Char->superuser,$sus)) return true;
        */
        if(getsetting('quest_activ',0)==1) return true;
        return false;
    }

    //Darstellung

    public static function makelist($status=0)
    {
        global $Char;
         $out = '';
        $res = db_query("SELECT u.*,q.*,o.name AS ortname FROM quest_user AS u
                            JOIN quest_events_orte AS q ON u.questid=q.id
                            JOIN quest_orte AS o ON q.ort = o.id
                            WHERE u.acctid = '".intval($Char->acctid)."' AND u.status='".intval($status)."' AND q.activ=1 ORDER BY u.date_start ASC");

        while($row = db_fetch_assoc($res))
        {
               //☆★  sterne
            $stars = '';
            $nostars = '';
            for($i=0;$i<$row['dificulty'];$i++) $stars .= '☆';
            for($i=0;$i<(10-$row['dificulty']);$i++) $nostars .= '☆';

            $rest = '';
            if($status == self::OPEN)
            {
                $rest = '`@Unbegrenzt';
                if($row['verfall']>0) $rest =  ($row['verfall'] - round($row['age']/getsetting('dayparts','1'),2)).' Tag'.( (($row['verfall'] - round($row['age']/getsetting('dayparts','1'),2))==1) ? '' : 'e' );
                $rest = 'Restzeit: '.( (($row['verfall'] - round($row['age']/getsetting('dayparts','1'),2))<5) ? '`$' : '`@' ).$rest.'`0';
            }else{
                $rest = 'Zeit gebraucht: `t'.round($row['age']/getsetting('dayparts','1'),2).'`0 Tag'.( ((round($row['age']/getsetting('dayparts','1'),2))==1) ? '' : 'e' ).' `0';
            }

            $steps = '';
            if($row['step'] > 0)
            {
                $steps = '<div style="margin-top: 6px; padding-top: 6px; border-top: 1px #676767 dotted; width:300px;">Bereits abgeschlossene Schritte:<ol style="font-size:10px;">';
                 $step_res = db_query("SELECT q.interactid,q.questname,o.name AS ortname FROM quest_events_interact AS q
                                            JOIN quest_orte AS o ON q.ort = o.id
                                            WHERE
                                                    q.activ=1
                                                AND questid='".intval($row['questid'])."'
                                                AND interactid <= '".intval($row['step'])."'
                                                ORDER BY interactid ASC");

                while($step_row = db_fetch_assoc($step_res))
                {
                    $steps .= '<li>'.$step_row['questname'].' <span style="font-size:8px;"> (`t'.$step_row['ortname'].'`0)</span></li>';
                }

                $steps .= '</ol></div>';
            }

              $out .= '<div style="padding:8px; border-bottom: #aa7800 3px solid; width: 95%; margin: auto; margin-bottom: 5px;clear:both; min-height:60px;">
                          <div style="padding:2px; border-bottom: #676767 1px solid; width: 100%; margin: auto; margin-bottom: 3px;clear:both;">
                          '.$row['questname'].' <span style="color:#676767;float:right;">'.$nostars.'</span><span style="color:#F7E117;float:right;">'.$stars.'</span>
                          </div>
                          <div style="clear:both;">
                          <p style="float:right; width:180px; border-left:1px dotted #676767; padding-left:4px; font-size:10px;">'.$rest.'<br />Ort: `t'.$row['ortname'].'`0</p>
                          <p style="width:520px;font-size:10px;min-height:50px;">'.words_by_sex($row['start_out']).''.$steps.'</p>
                          </div>
                          '.self::make_belohnung_div($row,($status == self::OPEN)).'
              </div>';
        }

        return $out;
    }

    public static function make_belohnung_div($row,$showbed=true,$showsee=false,$reset=false)
    {

        global $Char;

        if(!isset($row['step']))
        {
            $user_quest = self::get_user_quest($row['id']);
            $row['step'] = $user_quest['step'];
        }

        $ret = '';

        if($showbed)
        {
            $ret = '<div style="margin:auto; margin-top: 6px; padding-top: 6px; border-top: 1px #676767 dotted; width:710px;font-size: 9px;">Bedingungen:<br /><br /> ';

            $steps = self::count_steps($row['questid']);

            $ret .= '<ol>
           '.( ($steps > 0) ? ' <li>`'.( ($steps <= $row['step']) ? '@' : '4').$steps.' Unterquests lösen ('.$row['step'].')</li>' : '').'

            '.self::check_bedingungen($row['implode_belohnung_bedingung'],true,$reset).'</ol></div>';
        }

        if($showsee)
        {
            $ret = '<div style="margin:auto; margin-top: 6px; padding-top: 6px; border-top: 1px #676767 dotted; width:710px;font-size: 9px;">Bedingungen um den Quest zu sehen:<br /><br /> ';
            $ret .= '<ol>
            '.self::check_bedingungen($row['implode_sehen_bedingung'],true).'</ol></div>';
        }


        $ret .= '<div style="margin:auto; margin-top: 6px; padding-top: 6px; border-top: 1px #676767 dotted; width:710px; min-height: 55px; font-size: 9px;">Belohnung:<br /><br /> ';

        $proz = round(100/9)-2;

        if($row['gold'] > 0) $ret .= '<div style="text-align:center; height:20px; line-height:20px; float:left;
        margin-left: 5px; padding: 3px; border: 1px #676767 dotted; background-color:#202020; color:#fefefe; width:'.$proz.'%;">

        '.jslib_hint('<img style="vertical-align: middle;" src="/bathorys_module/quest/images/icons/gold.gif" />','Gold','lotgdHintSweet').'

         '.$row['gold'].' </div> ';


        if($row['gems'] > 0) $ret .= '<div style="text-align:center; height:20px; line-height:20px; float:left; margin-left:
        5px; padding: 3px; border: 1px #676767 dotted; background:#202020; color:#fefefe; width:'.$proz.'%;">
         '.jslib_hint('<img  style="vertical-align: middle;" src="/bathorys_module/quest/images/icons/gem.gif" />','Edelsteine','lotgdHintSweet').'


        '.$row['gems'].' </div> ';

        if($row['charme'] > 0) $ret .= '<div style="text-align:center; height:20px; line-height:20px; float:left; margin-left:
        5px; padding: 3px; border: 1px #676767 dotted; background:#202020; color:#fefefe; width:'.$proz.'%;">
         '.jslib_hint('<img  style="vertical-align: middle;" src="/bathorys_module/quest/images/icons/charme.png" />','Charmepunkte','lotgdHintSweet').'


        '.$row['charme'].' </div> ';

        if($row['dps'] > 0) $ret .= '<div style="text-align:center; height:20px; line-height:20px; float:left; margin-left:
        5px; padding: 3px; border: 1px #676767 dotted; background:#202020; color:#fefefe; width:'.$proz.'%;">
          '.jslib_hint('<img  style="vertical-align: middle;" src="/bathorys_module/quest/images/icons/dps.png" />','Donationpoints','lotgdHintSweet').'
         '.$row['dps'].' </div> ';
        if($row['plp'] > 0) $ret .= '<div style="text-align:center; height:20px; line-height:20px; float:left; margin-left:
        5px; padding: 3px; border: 1px #676767 dotted; background:#202020; color:#fefefe; width:'.$proz.'%;">
         '.jslib_hint('<img  style="vertical-align: middle;" src="/bathorys_module/quest/images/icons/permalps.png" />','Permanente Lebenspunkte','lotgdHintSweet').'
         '.$row['plp'].' </div> ';
        if($row['wks'] > 0) $ret .= '<div style="text-align:center; height:20px; line-height:20px; float:left; margin-left:
        5px; padding: 3px; border: 1px #676767 dotted; background:#202020; color:#fefefe; width:'.$proz.'%;">
         '.jslib_hint('<img  style="vertical-align: middle;" src="/bathorys_module/quest/images/icons/wks.png" />','Waldkämpfe','lotgdHintSweet').'
         '.$row['wks'].' </div> ';
        if($row['gfs'] > 0) $ret .= '<div style="text-align:center; height:20px; line-height:20px; float:left; margin-left:
        5px; padding: 3px; border: 1px #676767 dotted; background:#202020; color:#fefefe; width:'.$proz.'%;">
         '.jslib_hint('<img style="vertical-align: middle;"  src="/bathorys_module/quest/images/icons/gfs.png" />','Grabkämpfe','lotgdHintSweet').'
         '.$row['gfs'].' </div> ';
        if($row['gefal'] > 0) $ret .= '<div style="text-align:center; height:20px; line-height:20px; float:left; margin-left:
        5px; padding: 3px; border: 1px #676767 dotted; background:#202020; color:#fefefe; width:'.$proz.'%;">
         '.jslib_hint('<img  style="vertical-align: middle;" src="/bathorys_module/quest/images/icons/gefallen.png" />','Gefallen','lotgdHintSweet').'
         '.$row['gefal'].' </div> ';
        if($row['exp'] > 0) $ret .= '<div style="text-align:center; height:20px; line-height:20px; float:left; margin-left:
        5px; padding: 3px; border: 1px #676767 dotted; background:#202020; color:#fefefe; width:'.$proz.'%;">
         '.jslib_hint('<img  style="vertical-align: middle;" src="/bathorys_module/quest/images/icons/exp.png" />','Erfahrung','lotgdHintSweet').'
         '.($row['exp']).' </div> <br style="clear:both;" />';

        $items = explode(',',$row['implode_items']);
        if($items[0]=='0' || $items[0]=='') unset($items[0]);
        if(count($items) > 0)
        {
            $ret .= ''.'<div style="clear:both;margin-left: 5px; margin-top:8px; padding: 3px; border: 1px #676767 dotted; background:#202020; color:#fefefe; width:95.5%;">

                '.jslib_hint('<img  style="vertical-align: middle;" src="/bathorys_module/quest/images/icons/item.gif" />','Items','lotgdHintSweet').'

            ';

            foreach($items as $item)
            {
                $name =  db_get("SELECT tpl_name FROM items_tpl WHERE tpl_id='".db_real_escape_string($item)."' LIMIT 1");

               //rune ?
                if(mb_substr($item,0,2)=='r_')
                {
                    require_once(LIB_PATH.'runes.lib.php');
                    $sql 	= "SELECT id FROM ".RUNE_EI_TABLE." WHERE tpl_id='".db_real_escape_string($item)."' LIMIT 1";
                    $res 	= db_query( $sql );
                    $rune 	= db_fetch_assoc($res);
                    $known 	= runes_get_known(false);

                    if( !$known[$rune['id']] ){
                        $name['tpl_name'] = 'Unbekannte Rune';
                    }
                }

                $ret .= $name['tpl_name'].'`0, ';
            }

            $ret = mb_substr($ret,0,-2);
        }

        return $ret.'</div></div>';
    }

    public static function nav()
    {
        if(!self::is_activ()) return false;

        return '<a href="javasctipt:void(0);" target="_blank" onClick="'.popup('bathorys_popups.php?mod=quest&mdo=book', array('width'=>800,'height'=>600)).';return false;">
                            <img src="./images/icons/bio.gif" style="vertical-align:middle" title="" border="0" alt=""> `IQuests`0</a>`n';
    }
}
?>