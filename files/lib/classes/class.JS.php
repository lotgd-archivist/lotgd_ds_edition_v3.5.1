<?php
/**
* @author  Báthory
* @version DS-E V/3.x
*/

class JS
{
    public static $defer = false;

    public static function Autocomplete($name, $submit = false, $focus_input = false, $str_default_val = null, $str_display_field = null)
    {
        return '<input type="text" id="txtSearch" class="jqueryuserautocomplete'.($str_display_field == null? 'name':'login').'"
                                name="' . $name . '" autocomplete="off"
                                value="'.($str_default_val == null ? '' : utf8_htmlspecialchars($str_default_val)).'">'
                                .($submit ? ' <input type="submit" value="Suche" class="button"> ' : '').($focus_input?self::Focus("txtSearch",false):'');
    }

    public static function MessageBox($str_text,$str_title,$bool_out=true)
    {
        $js = self::encapsulate('MessageBox.show("'.self::c($str_text).'", "'.self::c($str_title).'");');
        if($bool_out) output($js,true);
        return $js;
    }

    public static function Focus($str,$bool_out=true)
    {
        if(!defined('JSLIB_NO_FOCUS_NEEDED')) {
            define('JSLIB_NO_FOCUS_NEEDED',1);
        }
        $js = self::encapsulate('atrajQ("#'.$str.'").focus();');
        if($bool_out) output($js,true);
        return $js;
    }

    public static function CloseLink($str,$bool_out=false)
    {
        $js = '<a href="javascript:void(0);" onclick="window.close();">'.$str.'</a>';
        if($bool_out) output($js,true);
        return $js;
    }

    public static function MassMail($bool_free=false,$bool_out=false,$arr = array("msg[]","title","maintext","msg","but","cost"))
    {
        $js = self::encapsulate('var els = document.getElementsByName("'.$arr[0].'");
            function chk () {
                var ok = false; var c = 0;
                for(i=0;i<els.length;i++) {if(els[i].checked) {ok = true;c++; }}
                if(!document.getElementById("'.$arr[1].'").value && !document.getElementById("'.$arr[2].'").value) {ok = false;}
                document.getElementById("'.$arr[3].'").style.visibility = (ok ? "hidden" : "visible");
                document.getElementById("'.$arr[4].'").style.visibility = (ok ? "visible" : "hidden");
                '.( $bool_free ? 'c = 0;' : 'if(c <= 3) {c = 1;}else if(c <= 10) {c = 2;}else if(c <= 25) {c = 3;}else {c = 4;}').'
                document.getElementById("'.$arr[5].'").innerHTML = c;}');
        if($bool_out) output($js,true);
        return $js;
    }

    public static function SetHeader($bool_jslib=false,$bool_motd=false,$bool_motc=false,$bool_ace=false,$bool_setmotdfalse=true)
    {
        global $template,$session,$Char;

        $loggedin = (0 != $Char->acctid);

        if($loggedin && $bool_jslib) $template['JS_LIB'] = jslib_init();
        $hs = '';
        $fs = '';
        if($loggedin && $bool_motd)
        {
            $session['needtoviewmotd']=true;
            $fs .= self::encapsulate(popup('motd.php'));
        }
        else if($bool_setmotdfalse)
        {
            $session['needtoviewmotd']=false;
        }
        $session['needtoviewmotc'] = $bool_motc;
        $hs .=self::jqueryInit($bool_ace);
        $template['headscript'] .= $hs;
        $template['headscript'] .= $fs;
    }

    public static function jqueryInit($bool_ace=false)
    {
        global $Char;

        if(0 == $Char->acctid)
        {
            $css = '<link type="text/css" href="./jquery/atrahor/game.css" rel="stylesheet" />
<link type="text/css" href="./templates/frame.css" rel="stylesheet" />
<link type="text/css" href="./templates/colors.css" rel="stylesheet" />';
            $js = '
        '.self::encapsulate('./jquery/jquery-ui-1.10.4.custom/js/jquery-1.11.1.min.js',true,false,true).'
        '.self::encapsulate('var atrajQ = jQuery.noConflict();',false,false,true).'
        '.self::encapsulate('./jquery/atrahor/guest.js',true,false,true);
        }
        else
        {
            $css = '<link type="text/css" href="./jquery/atrahor/game.css?id='.filemtime('./jquery/atrahor/game.css').'" rel="stylesheet" />
        <link type="text/css" href="./jquery/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link type="text/css" href="./templates/colors.css" rel="stylesheet" />
        <link type="text/css" href="./jslib/menu.lib.css" rel="stylesheet" />
        <link type="text/css" href="./jslib/mb.lib.css" rel="stylesheet" />
        <link type="text/css" href="./templates/frame.css" rel="stylesheet" />
       ';

            $js = '
        '.self::encapsulate('./jquery/jquery-ui-1.10.4.custom/js/jquery-1.11.1.min.js',true,false,true).'
        '.self::encapsulate('var atrajQ = jQuery.noConflict();',false,false,true).'
        '.self::encapsulate('./jquery/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js',true,false,true).'
        '.self::encapsulate('jquery/jquery.mjs.nestedSortable.js',true,false,true).'
        '.self::encapsulate('jquery/colorpicker/jquery.minicolors.min.js',true,false,true).'
         '.self::encapsulate('jquery/select2/select2.min.js',true,false,true).'
         '.self::encapsulate('jquery/select2/select2_locale_de.js',true,false,true).'

        '.self::encapsulate('./jquery/shortcut.js',true,false,true).'
        '.self::encapsulate('./jquery/jqueryui-editable/js/jqueryui-editable.min.js',true,false,true).'
        '.self::encapsulate('./jquery/datetimepicker/jquery.datetimepicker.js',true,false,true).'
        '.self::encapsulate('./jquery/qtip/jquery.qtip.min.js',true,false,true).'
        '.self::encapsulate('./jquery/confirm/jquery-confirm.min.js',true,false,true).'
        '.self::encapsulate('./jquery/atrahor/game.js?id='.filemtime('./jquery/atrahor/game.js').'',true,false,true).'
        '.self::encapsulate('./jquery/atrahor/chat.js?id='.filemtime('./jquery/atrahor/chat.js').'',true,false,true).'
        '.self::encapsulate('./jquery/atrahor/timer.js?id='.filemtime('./jquery/atrahor/timer.js').'',true,false,true).'
        ' ;
        }

        $ace = $bool_ace ? self::encapsulate('jquery/ace/ace.js',true,false,true) : '';
        return $css . $js . ($bool_ace ? $ace : '');
    }
    public static function encapsulate($str_js,$src=false,$prio=false,$forcefile=false)
    {
        global $template;
        if(self::$defer){
            if($src){
                $js='<script type="text/javascript" src="'.$str_js.'"></script>
                ';
            }else{
                $js='<script type="text/javascript">'.$str_js.'</script>
                ';
            }
            if($forcefile){
                $template['scriptfile'].=$js;
            }else if($prio){
                $template['scriptprio'].=$js;
            }else{
                $template['scriptend'].=$js;
            }
        }else{
            if($src){
                return '<script type="text/javascript" src="'.$str_js.'"></script>';
            }else{
                return '<script type="text/javascript">'.$str_js.'</script>';
            }
        }
        return '';
    }

    public static function event($id,$on,$js)
    {
        if($id !== str_replace(" ","",$id)){
            systemlog("js event id error: ".$id.' on: '.$on.' js: '.$js);
        }
        return self::encapsulate('
                atrajQ(document).on( "'.$on.'","'.$id.'",function(event) {
                '.$js.'
                });
            ',false,false,false);

    }

    public static function c($str_js)
    {
        $js =  str_replace('"','\\'.'"',$str_js);
        $js =  str_replace('\\'.'\\'.'"','\\'.'"',$js);

        return $js;
    }

    public static function cleanID($id)
    {
        $id =  trim(str_replace(" ","",$id));
        return $id;
    }
}
?>
