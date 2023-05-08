<?php
//by bathory

class CIgnore
{
    const IGNO_YOM = 1;
    const IGNO_CHAT = 2;
    const IGNO_LIST = 3;
    const IGNO_OOL = 4;
    const IGNO_BIO = 5;
    const IGNO_BOARDS = 6;
    const IGNO_CAL = 7;

    const IGNO_TWOWAY = 100;

    public static $cache = array();

    public static function ignores($acctid, $ignoreid, $ignotype = null)
    {
        $type = ($ignotype != null) ? ' AND type'.intval($ignotype).'=1 ' : '';

        $check = db_get("SELECT id FROM account_ignore WHERE acctid = '".intval($acctid)."' AND ignoreid = '".intval($ignoreid)."' ".$type." LIMIT 1");

        if(isset($check['id']))
        {
            return true;
        }

        return false;
    }

    public static function ignore($ignoreid, $reason='', $ignotypes = array())
    {
        global $Char;

        if(!self::ignores($Char->acctid, $ignoreid))
        {
            db_query("INSERT INTO account_ignore (acctid, ignoreid, reason, date) VALUES ('".intval($Char->acctid)."','".intval($ignoreid)."', '".db_real_escape_string(mb_substr($reason,0,255))."', NOW())");

            foreach($ignotypes as $type)
            {
                self::set_type($ignoreid, $type, 1);
            }

            return true;
        }

        return false;
    }

    public static function set_type($ignoreid, $ignotype = null, $ignoval = 0)
    {
        global $Char;

        if(self::ignores($Char->acctid, $ignoreid) && intval($ignotype) > 0)
        {
            db_query("UPDATE account_ignore SET type".intval($ignotype)."='".intval($ignoval)."' WHERE acctid = '".intval($Char->acctid)."' AND ignoreid = '".intval($ignoreid)."' LIMIT 1");
            return true;
        }

        return false;
    }

    public static function set_reason($ignoreid, $reason)
    {
        global $Char;

        if(self::ignores($Char->acctid, $ignoreid))
        {
            db_query("UPDATE account_ignore SET reason = '".db_real_escape_string(mb_substr($reason,0,255))."' WHERE acctid = '".intval($Char->acctid)."' AND ignoreid = '".intval($ignoreid)."' LIMIT 1");
            return true;
        }

        return false;
    }

    public static function unignore($ignoreid)
    {
        global $Char;

        if(self::ignores($Char->acctid, $ignoreid))
        {
            db_query("DELETE FROM account_ignore WHERE acctid = '".intval($Char->acctid)."' AND ignoreid = '".intval($ignoreid)."' LIMIT 1");
            return true;
        }

        return false;
    }

    public static function ignore_list($acctid = 0)
    {
        global $Char;

        if($acctid == 0) $acctid = $Char->acctid;

        return db_get_all("SELECT i.*,a.name FROM account_ignore AS i JOIN accounts AS a ON i.ignoreid=a.acctid WHERE i.acctid = '".intval($acctid)."' ORDER BY id ASC");
    }

    public static function ignore_sql($ignotype = null, $acctid = 0)
    {
        global $Char;

        if($acctid == 0) $acctid = $Char->acctid;

        if(!empty(self::$cache[$acctid][$ignotype])){
            return self::$cache[$acctid][$ignotype];
        }

        $type = ($ignotype != null) ? ' AND type'.intval($ignotype).'=1 ' : '';

        $all = db_get_all("SELECT ignoreid FROM account_ignore WHERE acctid = '".intval($acctid)."' ".$type);
        $ret = '-1';

        foreach($all as $v)
        {
            $ret .= ','.$v['ignoreid'];
        }

        //twowayignores
        $all_two = db_get_all("SELECT acctid FROM account_ignore WHERE  ignoreid= '".intval($acctid)."'  AND type".intval(self::IGNO_TWOWAY)."=1 ".$type);

        foreach($all_two as $v_two)
        {
            $ret .= ','.$v_two['acctid'];
        }
        self::$cache[$acctid][$ignotype] = $ret;
        return $ret;
    }
}