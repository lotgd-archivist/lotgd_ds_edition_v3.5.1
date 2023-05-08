<?php
//by bathory
class CRPPlaces
{
    public static function addnav($id,$head=true)
    {
        if($head)addnav('Besondere Orte');
        $p = db_get("SELECT id,name FROM rp_worlds WHERE id = '".intval($id)."' LIMIT 1");
        if(isset($p['name']))addnav('!?'.$p['name'],'rp_places.php?world='.$p['id']);
    }

    public static function parent($id)
    {
        $p = db_get("SELECT parent FROM rp_worlds_places WHERE id='".intval($id)."' LIMIT 1");
        $p = intval($p['parent']);
        if($p == 0){
            return $id;
        }
        return self::parent($p);
    }

    public static function parent_restricted($id)
    {
        $p = db_get("SELECT restricted FROM rp_worlds_places WHERE id='".intval(self::parent($id))."' LIMIT 1");
        return $p['restricted'];
    }

    public static function has_key($id, $acctid)
    {
        $key = db_get("SELECT * FROM rp_worlds_places_keys WHERE placeid IN (".implode(',',self::get_id_chain($id)).") AND acctid = '".$acctid."'");
        return isset($key['placeid']);
    }

    public static function get_id_chain($id)
    {
        $ids[] = $id;
        $p = db_get_all("SELECT id FROM rp_worlds_places WHERE parent='".intval($id)."'");
        foreach($p as $row){
            $ids = array_merge($ids,self::get_id_chain($row['id']));
        }
        return $ids;
    }

   public static function delete($world, $id)
   {
       global $Char;

       $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
       debuglog($worldinfo['name'].' (RP-Ort) gelöscht');

       db_query("DELETE FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' AND acctid='".intval($Char->acctid)."' LIMIT 1");
       db_query("DELETE FROM rp_worlds_places_keys WHERE placeid='".$id."'");

       $subplaces = db_get_all("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND parent='".$id."'");
       foreach($subplaces as $p)
       {
           self::delete($world,$p['id']);
       }

       if($worldinfo['parent'] == 0)
       {
           db_query("DELETE FROM rp_worlds_members WHERE rportid	='".$id."'");
           db_query("DELETE FROM rp_worlds_positions WHERE rportid	='".$id."'");
       }
   }
}