<?php
//by bathory
class CSteckbriefTabs
{

    public static $move =  array('male'=>'Male','stammbaum'=>'Stammbaum','rp'=>'RP-Info','ooc'=>'OOC','multi'=>'Multis','guestbook'=>'Gästebuch','aufzeichnungen'=>'Aufzeichnungen');

    public static function get_prefs()
    {
        $charForm = self::get_char_form();
        $return = array(
            "Tabs,title"
        ,'tabsForm' => $charForm.',viewonly');
        return $return;
    }

    public static function get_array($acctid)
    {
        $selfda = user_get_aei('stecktabs',$acctid);

        $selfda = utf8_unserialize($selfda['stecktabs']);

        if(count($selfda) != count(self::$move))
        {
            foreach(self::$move as $k => $v)
            {
                if(!in_array($k,$selfda))
                {
                    $selfda[] = $k;
                }
            }
        }

        return $selfda;
    }

	private static  function get_char_form()
	{
		global $Char;
		$return = '<div class="sort"><ol class="sortabletabs">';

        $selfda = self::get_array($Char->acctid);

        foreach($selfda as $k => $v)
        {
            $return .= '<li id="'.$v.'"><div>'.self::$move[$v].'</div></li>';
        }

        $return .= '</ol></div><div id="dialog" title="Meldung"></div>';

		return $return;
	}
}
?>