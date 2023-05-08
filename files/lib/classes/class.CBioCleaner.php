<?php

//by bathory

class CBioCleaner
{
    public static $cleanid = 0;

    public static  function clean($html,$css,$int_acctid)
    {
        self::$cleanid = $int_acctid;

        $content = $html;
        $content = closetags($content,'`i`b`c`H');
        $content = utf8_html_entity_decode($content);

        return array('html'=>$content,'css'=>$css);
    }

    public static function cleanHEX($hex,$allow_empty=true,$default='')
    {
        if($allow_empty){
            if(empty($hex)) return '';
        }
        if($hex == 'transparent') return 'transparent';

        if('#' != mb_substr($hex,0,1))$hex = '#'.$hex;

        $hex = utf8_preg_replace("/[^#abcdef0-9]+/i", "", mb_substr(mb_strtoupper($hex),0,7));
        $len = mb_strlen($hex);

        if($len != 7 && $len != 4)
        {
            if('#' != mb_substr($default,0,1))$default = '#'.$default;
            $hex = $default;
        }

        return $hex;
    }

    public static function outputHEX($hex,$allow_empty=true,$default='')
    {
        $hex = self::cleanHEX($hex,$allow_empty,$default);
        if(empty($hex)){
            return 'transparent';
        }
        return $hex;
    }

    public static function check_ext_url($treffer)
    {
        $next=$treffer;
        $f = mb_substr($treffer,0,1);
        $l = mb_substr($treffer,-1);
        if('"' == $f || "'" == $f){
            $treffer = mb_substr($treffer,1);
        }
        if('"' == $l || "'" == $l){
            $treffer = mb_substr($treffer,0,-1);
        }
        if(filter_var($treffer, FILTER_VALIDATE_URL))
        {
            if(getsetting('avatare',1) == 2) {$str_path = CPicture::AVATAR_UPLOAD_DIR;} else {$str_path = CPicture::AVATAR_SECURE_DIR;}
            $b = db_get("SELECT small_letter FROM user_uploads_pictures WHERE userid = '".intval(self::$cleanid)."' AND ext_url LIKE '".db_real_escape_string($treffer)."' LIMIT 1");
            if(isset($b['small_letter'])){
                $next = intval($b['small_letter']);
            }else{
                $quota = CPicture::get_quota(self::$cleanid);
                if($quota < CPicture::MAX_QOUTA)
                {
                    $pict 	= new CPicture( $treffer );
                    if($pict->is_valide()){
                        $bildcnt = db_get("SELECT small_letter  FROM user_uploads_pictures WHERE small_letter NOT IN ('p','h','d','s') AND small_letter NOT LIKE 'mc%' AND userid='".intval(self::$cleanid)."' ORDER BY CAST(small_letter AS SIGNED) DESC LIMIT 1");
                        $next = intval($bildcnt['small_letter'])+1;
                        CPicture::picture_save_ext_url($treffer,$next,self::$cleanid);
                        $pict->save(self::$cleanid,$next, -1, -1, $str_path);
                    }
                }
            }
        }
        return $next;
    }

    public static function fix_url($treffer)
    {
        $letreffer = utf8_html_entity_decode(urldecode($treffer[1]));
        $letreffer = utf8_preg_replace("/[^a-z0-9]+/i", "", self::check_ext_url($letreffer));

        $letreffer = mb_substr($letreffer,0,4);

        $path = CPicture::get_image_path(self::$cleanid,$letreffer,1);

        if ($path)
        {
            return 'url('.$letreffer.')';
        }

        return 'url()';
    }

    public static function fix_img($treffer)
    {
        $letreffer = utf8_html_entity_decode(urldecode($treffer[2]));
        $letreffer = utf8_preg_replace("/[^a-z0-9]+/i", "", self::check_ext_url($letreffer));

        $letreffer = mb_substr($letreffer,0,4);

        $path = CPicture::get_image_path(self::$cleanid,$letreffer,1);

        if ($path)
        {
            return '<img '.$treffer[1].' src="'.$letreffer.'" '.$treffer[3].'>';
        }

        return '';
    }

    public static function fix_url_back($treffer)
    {
        $letreffer = utf8_html_entity_decode(urldecode($treffer[1]));
        $letreffer = utf8_preg_replace("/[^a-z0-9]+/i", "", self::check_ext_url($letreffer));

        $letreffer = mb_substr($letreffer,0,4);

        $path = CPicture::get_image_path(self::$cleanid,$letreffer,1);

        if ($path)
        {
            return 'style="background-image:url('.$letreffer.' repeat top left;"';
        }

        return '';
    }

    public static function check_url($html,$int_acctid)
    {
        self::$cleanid = $int_acctid;
        $regex = '/url\(([^)]*)\)/i';
        $html = utf8_preg_replace_callback($regex,array("CBioCleaner","__check_url"),$html);
        $regex = '/<img([^><]*)src=[\'"]*([^\'"]*)[\'"]*([^><]*)[>]*/i';
        return utf8_preg_replace_callback($regex,array("CBioCleaner","__check_img"),$html);
    }

    public static function __check_url($treffer)
    {
        $letreffer = utf8_html_entity_decode(urldecode($treffer[1]));
        $letreffer = utf8_preg_replace("/[^a-z0-9]+/i", "", $letreffer);

        $letreffer = mb_substr($letreffer,0,4);

        $path = trim(CPicture::get_image_path(self::$cleanid,$letreffer,1));

        if ($path)
        {
            return 'url('.$path.')';
        }

        return 'url()';
    }

    public static function __check_img($treffer)
    {
        $letreffer = utf8_html_entity_decode(urldecode($treffer[2]));
        $letreffer = utf8_preg_replace("/[^a-z0-9]+/i", "", $letreffer);

        $letreffer = mb_substr($letreffer,0,4);

        $path = trim(CPicture::get_image_path(self::$cleanid,$letreffer,1));

        if ($path)
        {
            return '<img '.$treffer[1].' src="'.$path.'" '.$treffer[3].'>';
        }

        return '';
    }
}

?>