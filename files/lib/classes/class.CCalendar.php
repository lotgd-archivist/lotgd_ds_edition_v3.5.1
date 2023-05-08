<?php
//by bathory
class CCalendar
{
    const GROUP_TYPE_OPEN = 2;
    const GROUP_TYPE_PRIVAT = 4;

    const EVENT_TYPE_ALL = 1;
    const EVENT_TYPE_PRIVAT = 2;
    const EVENT_TYPE_GROUPS = 4;
    const EVENT_TYPE_OPEN = 8;
    const EVENT_TYPE_PRIVAT_AND_GROUPS = 16;

    const EVENTS_ALL = 1;
    const EVENTS_OLD = 2;
    const EVENTS_NEW = 4;

    const EVENTS_RECURING_NONE = 0;
    const EVENTS_RECURING_DAILY = 2;
    const EVENTS_RECURING_WEEKLY = 4;
    const EVENTS_RECURING_MONTLY = 8;
    const EVENTS_RECURING_YEARLY = 16;

    public static function getTurnusText($type)
    {
        if($type == self::EVENTS_RECURING_YEARLY){
            return 'Jährlich';
        }
        else if($type == self::EVENTS_RECURING_MONTLY){
            return 'Monatlich';
        }
        else if($type == self::EVENTS_RECURING_WEEKLY){
            return 'Wöchentlich';
        }
        else if($type == self::EVENTS_RECURING_DAILY){
            return 'Täglich';
        }
        else if($type == self::EVENTS_RECURING_NONE){
            return 'Einmalig';
        }
        return '';
    }

    public static function incDate(DateTime $begin,$diffInSeconds, $interval, $target_date = 'now')
    {
        $start = new DateTime($begin->format('Y-m-d H:i:s'));
        $last = new DateTime($begin->format('Y-m-d H:i:s'));
        $now = new DateTime($target_date);
        while($start < $now)
        {
            $last = new DateTime($start->format('Y-m-d H:i:s'));
            $start->add(new DateInterval($interval));
        }
        $last_end = new DateTime($last->format('Y-m-d H:i:s'));
        $last_end->add(new DateInterval('PT'.$diffInSeconds.'S'));
        if($last_end < $now){
            return $start;
        }
        return $last;
    }

    public static function getNextTurnusStartDate($type, $start, $end)
    {
        $sd = new DateTime($start);
        $ed = new DateTime($end);
        $diffInSeconds = max(0,$ed->getTimestamp() - $sd->getTimestamp());
        if($type == self::EVENTS_RECURING_YEARLY){
            $sd = self::incDate($sd, $diffInSeconds, 'P1Y');
        }
        else if($type == self::EVENTS_RECURING_MONTLY){
            $sd = self::incDate($sd, $diffInSeconds, 'P1M');
        }
        else if($type == self::EVENTS_RECURING_WEEKLY){
            $sd = self::incDate($sd, $diffInSeconds, 'P1W');
        }
        else if($type == self::EVENTS_RECURING_DAILY){
            $sd = self::incDate($sd, $diffInSeconds, 'P1D');
        }
        return $sd->format('Y-m-d H:i:s');
    }

    public static function getNextTurnusText($type, $start, $end)
    {
        $sd = new DateTime($start);
        $ed = new DateTime($end);
        $diffInSeconds = max(0,$ed->getTimestamp() - $sd->getTimestamp());
        if($type == self::EVENTS_RECURING_YEARLY){
            $sd = self::incDate($sd, $diffInSeconds, 'P1Y');
        }
        else if($type == self::EVENTS_RECURING_MONTLY){
            $sd = self::incDate($sd, $diffInSeconds, 'P1M');
        }
        else if($type == self::EVENTS_RECURING_WEEKLY){
            $sd = self::incDate($sd, $diffInSeconds, 'P1W');
        }
        else if($type == self::EVENTS_RECURING_DAILY){
            $sd = self::incDate($sd, $diffInSeconds, 'P1D');
        }
        $out = 'Beginn:`a '.$sd->format('Y-m-d H:i');
        $sd->add(new DateInterval('PT'.$diffInSeconds.'S'));
        $out .= '`0`n&nbsp;&nbsp;&nbsp;Ende: `t'.$sd->format('Y-m-d H:i').'`0';
        return $out;
    }

    public static function newcount()
    {
        global $Char;
        $acctid = $Char->acctid;
        $row = db_get("SELECT COUNT(*) AS cnt
                              FROM calendar_events
                              WHERE
                                  changed > '".db_real_escape_string($Char->calender_last)."'
                              AND acctid <> '".intval($acctid)."'
                              AND ( (start_date >= NOW()) OR (end_date >= NOW()) )
                              AND (groupid IN (".self::getGroupsSQL($acctid, 'id').") AND ( (private = 0) OR (private = 1 AND id IN (".self::getEventsSQL($acctid, 'id').") ) ))
                              AND acctid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CAL,$acctid).")
                              ORDER BY id ASC");
        return intval($row['cnt']);
    }

    public static function getEventsParsed($acctid, $type, $start, $end, $recurring = false)
    {
        $gt = CCalendar::getGroups($acctid);
        $groups = array();
        foreach($gt as $g){
            $groups[$g['id']] = $g['name'];
        }
        $et = CCalendar::getEventsAsTeilnehmer($acctid);
        $teiln = array();
        foreach($et as $e){
            $teiln[$e['eventid']] = $e;
        }
        $events = self::getEvents($acctid, $type, $start, $end,$recurring);
        for($i=0; $i < count($events); $i++){
            $events[$i]['group'] = appoencode($groups[$events[$i]['groupid']].'`0');
            $events[$i]['start'] = str_replace('T00:00:00','',$events[$i]['start']);
            $events[$i]['end'] = str_replace('T00:00:00','',$events[$i]['end']);
            if('0000-00-00' == $events[$i]['end'])unset($events[$i]['end']);
            if('0000-00-00' == $events[$i]['start'])unset($events[$i]['start']);
            $events[$i]['title_original'] = $events[$i]['title'];
            $events[$i]['description_original'] = $events[$i]['description'];
            $events[$i]['title'] = appoencode(($events[$i]['title']).'`0');
            $events[$i]['description'] = nl2br(appoencode(($events[$i]['description']).'`0'));
            $events[$i]['name'] = (appoencode(($events[$i]['name']).'`0'));
            $members = self::getEventMembers($events[$i]['id'],$acctid);
            $out = '';
            foreach($members as $member)
            {
                $out .= appoencode($member['name'].'`0`n');
            }
            $events[$i]['user'] = $out;
            $events[$i]['teil'] = isset($teiln[$events[$i]['id']]);
            $events[$i]['isOwner'] = ($acctid == $events[$i]['acctid']);
        }
        if(!$recurring){
            return $events;
        }else{
            $events_neu = array();
            for($i=0; $i < count($events); $i++){
                $events_neu = array_merge($events_neu,self::getEventRecuringData($events[$i], $start, $end));
            }
            return $events_neu;
        }
    }

    public static function getEventRecuringData($events, $start, $end)
    {
        $ret = array();
        $type = $events['recuring'];
        $start_date = new DateTime($start);
        $end_date = new DateTime($end);
        $sd = new DateTime($events['start_date']);
        $ed = new DateTime($events['end_date']);
        $diffInSeconds = max(0,$ed->getTimestamp() - $sd->getTimestamp());
        while($sd <= $end_date){
            if($type == self::EVENTS_RECURING_YEARLY){
                $sd = self::incDate($sd, 0, 'P1Y', $start_date->format('Y-m-d'));
                $start_date = new DateTime($sd->format('Y-m-d H:i:s'));
                $start_date->add(new DateInterval('P1Y'));
            }
            else if($type == self::EVENTS_RECURING_MONTLY){
                $sd = self::incDate($sd, 0, 'P1M', $start_date->format('Y-m-d'));
                $start_date = new DateTime($sd->format('Y-m-d H:i:s'));
                $start_date->add(new DateInterval('P1M'));
            }
            else if($type == self::EVENTS_RECURING_WEEKLY){
                $sd = self::incDate($sd, 0, 'P1W', $start_date->format('Y-m-d'));
                $start_date = new DateTime($sd->format('Y-m-d H:i:s'));
                $start_date->add(new DateInterval('P1W'));
            }
            else if($type == self::EVENTS_RECURING_DAILY){
                $sd = self::incDate($sd, 0, 'P1D', $start_date->format('Y-m-d'));
                $start_date = new DateTime($sd->format('Y-m-d H:i:s'));
                $start_date->add(new DateInterval('P1D'));
            }
            $ed = new DateTime($sd->format('Y-m-d H:i:s'));
            $ed->add(new DateInterval('PT'.$diffInSeconds.'S'));
            $events['start_date'] = $sd->format('Y-m-d H:i:s');
            $events['end_date'] = $ed->format('Y-m-d H:i:s');
            $events['start'] = str_replace('T00:00:00','',$sd->format('Y-m-d').'T'.$sd->format('H:i:s'));
            $events['end'] = str_replace('T00:00:00','',$ed->format('Y-m-d').'T'.$ed->format('H:i:s'));
            if('0000-00-00' == $events['end'])unset($events['end']);
            if('0000-00-00' == $events['start'])unset($events['start']);
            $ret[] = $events;
        }
        return $ret;
    }

    public static function getEventData($acctid, $id)
    {
        return db_get("SELECT * FROM calendar_events WHERE (id = '".intval($id)."' AND acctid = '".intval($acctid)."') LIMIT 1");
    }

    public static function getEventsUser($acctid, $type = self::EVENTS_ALL, $recurring = false)
    {
        $zt = $recurring ? ' <> ' : ' = ';

        if($recurring){
            $order = " ORDER BY e.recuring ASC, e.start_date ASC ";
        }else{
            $order = " ORDER BY e.start_date ASC ";
        }

        if(self::EVENTS_ALL == $type){
            return db_get_all("SELECT a.name,e.*
                              FROM calendar_events AS e JOIN accounts AS a ON e.acctid=a.acctid
                              WHERE
                                  e.recuring ".$zt." '".self::EVENTS_RECURING_NONE."' AND
                                  e.acctid = '".intval($acctid)."'
                              ".$order);
        }else if(self::EVENTS_NEW == $type){
            return db_get_all("SELECT a.name,e.*
                              FROM calendar_events AS e JOIN accounts AS a ON e.acctid=a.acctid
                              WHERE
                              e.recuring ".$zt." '".self::EVENTS_RECURING_NONE."' AND
                                  e.acctid = '".intval($acctid)."'
                                  AND ( (e.start_date >= NOW()) OR (e.end_date >= NOW()) )
                              ".$order);
        }else if(self::EVENTS_OLD == $type){
            return db_get_all("SELECT a.name,e.*
                              FROM calendar_events AS e JOIN accounts AS a ON e.acctid=a.acctid
                              WHERE
                              e.recuring ".$zt." '".self::EVENTS_RECURING_NONE."' AND
                                  e.acctid = '".intval($acctid)."'
                                  AND ( (e.start_date <= NOW() AND e.end_date = '0000-00-00 00:00:00') OR (e.start_date <= NOW() AND e.end_date <= NOW()) OR (e.start_date = '0000-00-00 00:00:00' AND e.end_date <= NOW()) )
                              ".$order);
        }
        return array();
    }

    public static function getAgendaUser($acctid, $recurring = false)
    {
        $zt = $recurring ? ' <> ' : ' = ';

        if($recurring){
            $order = " ORDER BY e.recuring ASC, e.start_date ASC ";
        }else{
            $order = " ORDER BY e.start_date ASC ";
        }

        return db_get_all("SELECT a.name,e.*
                              FROM calendar_events AS e JOIN accounts AS a ON e.acctid=a.acctid
                              WHERE
                              e.recuring ".$zt." '".self::EVENTS_RECURING_NONE."' AND
                                  (
                                        (e.private = 0 AND e.groupid = 0)
                                     OR (e.private = 1 AND e.id IN (".self::getEventsSQL($acctid, 'id').") AND e.groupid = 0)
                                     OR (e.groupid IN (".self::getGroupsSQL($acctid, 'id').") AND ( e.private = 0 OR (e.private = 1 AND e.id IN (".self::getEventsSQL($acctid, 'id').") )))
                                  )
                                  AND e.acctid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CAL,$acctid).")
                                  ".( $recurring ? " " : " AND ( (e.start_date >= NOW()) OR (e.end_date >= NOW()) ) " )."
                              ".$order);
    }

    public static function getEvents($acctid, $type, $start, $end, $recurring = false)
    {
        $zt = $recurring ? ' <> ' : ' = ';
        if($recurring){
            $date_limit = " AND e.start_date <= '".db_real_escape_string($end)."' ";
        }else{
            $date_limit = " AND ( (  e.start_date >= '".db_real_escape_string($start)."'  AND   e.start_date <= '".db_real_escape_string($end)."'   ) OR (  e.end_date >= '".db_real_escape_string($start)."'  ) ) ";
        }
        if(self::EVENT_TYPE_ALL == $type){
            return db_get_all("SELECT a.name,e.*, DATE_FORMAT(e.start_date,'%Y-%m-%dT%H:%i:%s') AS start, DATE_FORMAT(e.end_date,'%Y-%m-%dT%H:%i:%s') AS `end`
                              FROM calendar_events AS e JOIN accounts AS a ON e.acctid=a.acctid
                              WHERE
                              e.recuring ".$zt." '".self::EVENTS_RECURING_NONE."' AND
                                  ((e.private = 0 AND e.groupid = 0) OR (e.private = 1 AND e.id IN (".self::getEventsSQL($acctid, 'id').") AND e.groupid = 0) OR (e.groupid IN (".self::getGroupsSQL($acctid, 'id').") AND ( (e.private = 0) OR (e.private = 1 AND e.id IN (".self::getEventsSQL($acctid, 'id').")) )) )
                                  AND e.acctid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CAL,$acctid).")
                                 ".$date_limit."
                              ORDER BY e.id ASC");
        }else if(self::EVENT_TYPE_PRIVAT_AND_GROUPS == $type){
            return db_get_all("SELECT a.name,e.*, DATE_FORMAT(e.start_date,'%Y-%m-%dT%H:%i:%s') AS start, DATE_FORMAT(e.end_date,'%Y-%m-%dT%H:%i:%s') AS `end`
                              FROM calendar_events AS e JOIN accounts AS a ON e.acctid=a.acctid
                              WHERE
                              e.recuring ".$zt." '".self::EVENTS_RECURING_NONE."' AND
                                  ((e.private = 0 AND e.acctid = '".intval($acctid)."' AND e.groupid = 0) OR (e.private = 1 AND e.id IN (".self::getEventsSQL($acctid, 'id').") AND e.groupid = 0) OR (e.groupid IN (".self::getGroupsSQL($acctid, 'id').") AND ( (e.private = 0) OR (e.private = 1 AND e.id IN (".self::getEventsSQL($acctid, 'id').") ) )) )
                                  AND e.acctid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CAL,$acctid).")
                                  ".$date_limit."
                              ORDER BY e.id ASC");
        }else if(self::EVENT_TYPE_PRIVAT == $type){
            return db_get_all("SELECT a.name,e.*, DATE_FORMAT(e.start_date,'%Y-%m-%dT%H:%i:%s') AS start, DATE_FORMAT(e.end_date,'%Y-%m-%dT%H:%i:%s') AS `end`
                              FROM calendar_events AS e JOIN accounts AS a ON e.acctid=a.acctid
                              WHERE
                              e.recuring ".$zt." '".self::EVENTS_RECURING_NONE."' AND
                                  (e.private = 1 AND e.id IN (".self::getEventsSQL($acctid, 'id').") AND e.groupid = 0)
                                  ".$date_limit."
                              ORDER BY e.id ASC");
        }else if(self::EVENT_TYPE_GROUPS == $type){
            return db_get_all("SELECT a.name,e.*, DATE_FORMAT(e.start_date,'%Y-%m-%dT%H:%i:%s') AS start, DATE_FORMAT(e.end_date,'%Y-%m-%dT%H:%i:%s') AS `end`
                              FROM calendar_events AS e JOIN accounts AS a ON e.acctid=a.acctid
                              WHERE
                              e.recuring ".$zt." '".self::EVENTS_RECURING_NONE."' AND
                                  (e.groupid IN (".self::getGroupsSQL($acctid, 'id').") AND ( (e.private = 0) OR (e.private = 1 AND e.id IN (".self::getEventsSQL($acctid, 'id').") ) ))
                                  AND e.acctid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CAL,$acctid).")
                                  ".$date_limit."
                              ORDER BY e.id ASC");
        }else if(self::EVENT_TYPE_OPEN == $type){
            return db_get_all("SELECT a.name,e.*, DATE_FORMAT(e.start_date,'%Y-%m-%dT%H:%i:%s') AS start, DATE_FORMAT(e.end_date,'%Y-%m-%dT%H:%i:%s') AS `end`
                              FROM calendar_events AS e JOIN accounts AS a ON e.acctid=a.acctid
                              WHERE
                              e.recuring ".$zt." '".self::EVENTS_RECURING_NONE."' AND
                                  (e.private = 0 AND e.groupid = 0)
                                  AND e.acctid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CAL,$acctid).")
                                 ".$date_limit."
                              ORDER BY e.id ASC");
        }
        return array();
    }

    private static function getGroupsSQL($acctid, $get='*')
    {
        return "SELECT ".$get." FROM calendar_groups WHERE owner = '".intval($acctid)."' OR id IN (SELECT groupid FROM calendar_groups_user WHERE acctid = '".intval($acctid)."') ORDER BY id ASC";
    }

    private static function getEventsSQL($acctid, $get='*')
    {
        return "SELECT ".$get." FROM calendar_events WHERE acctid = '".intval($acctid)."' OR id IN (SELECT eventid FROM calendar_events_user WHERE acctid = '".intval($acctid)."') ORDER BY id ASC";
    }

    public static function getGroupData($acctid, $id)
    {
        return db_get("SELECT * FROM calendar_groups WHERE (id = '".intval($id)."' AND owner = '".intval($acctid)."') LIMIT 1");
    }

    public static function getGroupDataAsGuest($id)
    {
        return db_get("SELECT id,owner,name FROM calendar_groups WHERE id = '".intval($id)."' LIMIT 1");
    }

    public static function getEventDataAsGuest($id)
    {
        return db_get("SELECT id,acctid,title FROM calendar_events WHERE id = '".intval($id)."' LIMIT 1");
    }

    public static function getGroups($acctid)
    {
        return db_get_all("SELECT g.*,a.name AS ownername FROM calendar_groups AS g JOIN accounts AS a ON g.owner=a.acctid WHERE owner = '".intval($acctid)."' OR id IN (SELECT groupid FROM calendar_groups_user WHERE acctid = '".intval($acctid)."') ORDER BY id ASC");
    }

    public static function getEventsAsTeilnehmer($acctid)
    {
        return db_get_all("SELECT * FROM calendar_events_user WHERE acctid = '".intval($acctid)."' ORDER BY id ASC");
    }

    public static function getPubGroups($acctid)
    {
        return db_get_all("SELECT g.*,a.name AS ownername FROM calendar_groups AS g JOIN accounts AS a ON g.owner=a.acctid WHERE `type` = '".CCalendar::GROUP_TYPE_OPEN."' AND id NOT IN (".self::getGroupsSQL($acctid,'id').") AND owner NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_CAL,$acctid).") ORDER BY id ASC");
    }

    public static function isPubGroup($id)
    {
        $g = db_get("SELECT id FROM calendar_groups WHERE id = '".intval($id)."' AND `type` = '".CCalendar::GROUP_TYPE_OPEN."' LIMIT 1");
        return isset($g['id']);
    }

    public static function isPubEvent($id)
    {
        $g = db_get("SELECT id FROM calendar_events WHERE id = '".intval($id)."' AND private = 0 LIMIT 1");
        return isset($g['id']);
    }

    public static function deleteEvent($id, $acctid)
    {
        if(self::isEventOwner($id,$acctid)){
            db_query("DELETE FROM calendar_events WHERE id = '".intval($id)."' AND acctid = '".intval($acctid)."' LIMIT 1");
            db_query("DELETE FROM calendar_events_user WHERE eventid = '".intval($id)."' ");
        }
    }

    public static function updateEvent($id, $acctid, $private, $groupid, $title, $description, $start_date, $end_date, $color, $textColor, $recuring)
    {
        db_query("UPDATE calendar_events
                  SET
                  private = '".intval($private)."',
                  groupid = '".intval($groupid)."',
                  title = '".db_real_escape_string(utf8_htmlspecialsimple($title))."',
                  description = '".db_real_escape_string(utf8_htmlspecialsimple($description))."',
                  start_date = '".db_real_escape_string($start_date)."',
                  end_date = '".db_real_escape_string($end_date)."',
                  color = '".db_real_escape_string(utf8_htmlspecialsimple($color))."',
                  textColor = '".db_real_escape_string(utf8_htmlspecialsimple($textColor))."',
                  changed = NOW(),
                  recuring = '".db_real_escape_string(utf8_htmlspecialsimple($recuring))."',
                  notified = 0
                  WHERE (id = '".intval($id)."' AND acctid = '".intval($acctid)."') LIMIT 1");

        db_query("UPDATE calendar_events_user SET notified=0 WHERE eventid=".intval($id));
    }

    public static function addEvent($acctid, $private, $groupid, $title, $description, $start_date, $end_date, $color, $textColor, $recuring)
    {
        db_query("INSERT INTO calendar_events (id, acctid, private, groupid, title, description, start_date, end_date, color, textColor, changed, recuring)
                  VALUES (
                  NULL,
                  '".intval($acctid)."',
                  '".intval($private)."',
                  '".intval($groupid)."',
                  '".db_real_escape_string(utf8_htmlspecialsimple($title))."',
                  '".db_real_escape_string(utf8_htmlspecialsimple($description))."',
                  '".db_real_escape_string($start_date)."',
                  '".db_real_escape_string($end_date)."',
                  '".db_real_escape_string(utf8_htmlspecialsimple($color))."',
                  '".db_real_escape_string(utf8_htmlspecialsimple($textColor))."',
                  NOW(),
                  '".db_real_escape_string(utf8_htmlspecialsimple($recuring))."'
                  )");
    }

    public static function deleteGroup($id, $owner)
    {
        if(self::isGroupOwner($id,$owner)){
            db_query("DELETE FROM calendar_groups WHERE id = '".intval($id)."' AND owner = '".intval($owner)."' LIMIT 1");
            db_query("DELETE FROM calendar_groups_user WHERE groupid = '".intval($id)."' ");
        }
    }

    public static function getGroupMembers($groupid, $owner)
    {
        if(self::isGroupMember($groupid,$owner))
        {
            return db_get_all("SELECT u.*,a.name FROM calendar_groups_user AS u JOIN accounts AS a ON u.acctid=a.acctid WHERE groupid='".intval($groupid)."' ORDER BY a.login ASC");
        }
        else if(self::isGroupOwner($groupid,$owner))
        {
            return db_get_all("SELECT u.*,a.name FROM calendar_groups_user AS u JOIN accounts AS a ON u.acctid=a.acctid WHERE groupid='".intval($groupid)."' ORDER BY a.login ASC");
        }
        else if (self::isPubGroup($groupid))
        {
            return db_get_all("SELECT u.*,a.name FROM calendar_groups_user AS u JOIN accounts AS a ON u.acctid=a.acctid WHERE groupid='".intval($groupid)."' ORDER BY a.login ASC");
        }
        return array();
    }

    public static function getEventMembers($id, $acctid)
    {
        if(self::isEventMember($id,$acctid))
        {
            return db_get_all("SELECT u.*,a.name FROM calendar_events_user AS u JOIN accounts AS a ON u.acctid=a.acctid WHERE eventid='".intval($id)."' ORDER BY a.login ASC");
        }
        else if(self::isEventOwner($id,$acctid))
        {
            return db_get_all("SELECT u.*,a.name FROM calendar_events_user AS u JOIN accounts AS a ON u.acctid=a.acctid WHERE eventid='".intval($id)."' ORDER BY a.login ASC");
        }
        else if (self::isPubEvent($id))
        {
            return db_get_all("SELECT u.*,a.name FROM calendar_events_user AS u JOIN accounts AS a ON u.acctid=a.acctid WHERE eventid='".intval($id)."' ORDER BY a.login ASC");
        }
        return array();
    }

    public static function updateGroup($id, $owner, $type, $name, $description)
    {
        db_query("UPDATE calendar_groups
                  SET
                  `type` = '".intval($type)."',
                  `name` = '".db_real_escape_string(utf8_htmlspecialsimple($name))."',
                  description = '".db_real_escape_string(utf8_htmlspecialsimple($description))."'
                  WHERE id = '".intval($id)."' AND owner = '".intval($owner)."' LIMIT 1");
    }

    public static function addGroup($owner, $type, $name, $description)
    {
        db_query("INSERT INTO calendar_groups (id, owner, `type`, `name`, description)
                  VALUES (
                  NULL,
                  '".intval($owner)."',
                  '".intval($type)."',
                  '".db_real_escape_string(utf8_htmlspecialsimple($name))."',
                  '".db_real_escape_string(utf8_htmlspecialsimple($description))."'
                  )");
    }

    public static function deleteGroupUserAsOwner($groupid, $owner, $acctid)
    {
        if(self::isGroupOwner($groupid,$owner)){
            return db_query("DELETE FROM calendar_groups_user WHERE groupid = '".intval($groupid)."' AND acctid = '".intval($acctid)."' LIMIT 1");
        }
        return false;
    }

    public static function deleteEventUserAsOwner($id, $owner, $acctid)
    {
        if(self::isEventOwner($id,$owner)){
            return db_query("DELETE FROM calendar_events_user WHERE eventid = '".intval($id)."' AND acctid = '".intval($acctid)."' LIMIT 1");
        }
        return false;
    }

    public static function isGroupOwner($groupid, $owner)
    {
        $data = self::getGroupData($owner,$groupid);
        return (isset($data['owner']) && $data['owner'] == $owner);
    }

    public static function isEventOwner($eventid, $acctid)
    {
        $data = self::getEventData($acctid,$eventid);
        return (isset($data['acctid']) && $data['acctid'] == $acctid);
    }

    public static function isGroupMember($groupid, $acctid)
    {
        $data = db_get("SELECT id FROM calendar_groups_user WHERE groupid='".intval($groupid)."' AND acctid='".intval($acctid)."' LIMIT 1");
        return (isset($data['id']));
    }

    public static function isEventMember($eventid, $acctid)
    {
        $data = db_get("SELECT id FROM calendar_events_user WHERE eventid='".intval($eventid)."' AND acctid='".intval($acctid)."' LIMIT 1");
        return (isset($data['id']));
    }

    public static function deleteGroupUser($groupid, $acctid)
    {
        db_query("DELETE FROM calendar_groups_user WHERE groupid = '".intval($groupid)."' AND acctid = '".intval($acctid)."' LIMIT 1");
    }

    public static function deleteEventUser($id, $acctid)
    {
        db_query("DELETE FROM calendar_events_user WHERE eventid = '".intval($id)."' AND acctid = '".intval($acctid)."' LIMIT 1");
    }

    public static function checkGroupUser($groupid, $acctid)
    {
        $d = db_get("SELECT id FROM calendar_groups_user WHERE groupid = '".intval($groupid)."' AND acctid = '".intval($acctid)."' LIMIT 1");
        return isset($d['id']);
    }

    public static function checkEventUser($id, $acctid)
    {
        $d = db_get("SELECT id FROM calendar_events_user WHERE eventid = '".intval($id)."' AND acctid = '".intval($acctid)."' LIMIT 1");
        return isset($d['id']);
    }

    public static function addGroupUser($groupid, $acctid, $owner=0)
    {
        if(self::isPubGroup($groupid) || self::isGroupOwner($groupid,$owner)){
            return db_query("INSERT INTO calendar_groups_user (id, groupid, acctid)
                  VALUES (
                  NULL,
                  '".intval($groupid)."',
                  '".intval($acctid)."'
                  )");
        }
        return false;
    }

    public static function addEventUser($id, $acctid, $owner=0)
    {
        if(self::isPubEvent($id) || self::isEventOwner($id,$owner)){
            return db_query("INSERT INTO calendar_events_user (id, eventid, acctid)
                  VALUES (
                  NULL,
                  '".intval($id)."',
                  '".intval($acctid)."'
                  )");
        }
        return false;
    }

}