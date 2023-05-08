<?php
//by bathory
class CRPBio
{

    private $int_acctid = 0;
    private $str_name = '';
    private $int_pageid = 1;
    private $arr_config = array();
    private $arr_page = array();

    private $str_subquery = "";

    function __construct($int_acctid,$int_pageid)
    {
        global $Char, $access_control, $session,$template;

        $this->int_acctid = intval($int_acctid);

        $stecki = 'steckbrief.php?id='.$this->int_acctid;

        $this->str_name = db_get("SELECT name FROM accounts WHERE acctid=".$this->int_acctid." LIMIT 1");
        if(count($this->str_name)==0) die('Diesen Spieler gibt es nicht');

        $this->str_name = $this->str_name['name'];

        $this->arr_config = db_get("SELECT * FROM rpbios_config WHERE acctid=".$this->int_acctid." LIMIT 1");
        if(count($this->arr_config)==0) redirect($stecki,false,false);

        $this->arr_config['config'] = utf8_unserialize($this->arr_config['config']);
        $this->arr_config['friends'] = utf8_unserialize($this->arr_config['friends']);
        $this->arr_config['exclude'] = utf8_unserialize($this->arr_config['exclude']);

        if(array_key_exists($Char->acctid, $this->arr_config['exclude']))
        {
            redirect($stecki,false,false);
        }

        if($Char->acctid != $this->int_acctid)
        {
            //Rechtevergabe pro seite
            if($Char->acctid==0 || !(isset($session['loggedin']) && $session['loggedin']) )
            {
                redirect($stecki,false,false);

              //  $this->str_subquery = " AND see_anon=1 ";
              //  if($this->arr_config['see_anon']==0) die('Diese Seite gibt es nicht');
            }
            else if( getsetting('demouser_acctid',0) == $Char->acctid )  //demouser
            {
                $this->str_subquery = " AND see_demo=1 ";
                if($this->arr_config['see_demo']==0) redirect($stecki,false,false);
            }
            else if(array_key_exists($Char->acctid, $this->arr_config['friends']))
            {
                $this->str_subquery = " AND see_friends=1 ";
                if($this->arr_config['see_friends']==0) redirect($stecki,false,false);
            }
            else
            {
                $this->str_subquery = " AND see_reg=1 ";
                if($this->arr_config['see_reg']==0) redirect($stecki,false,false);
            }
        }


        if($int_pageid==null)
        {
            $temp_page = db_get("SELECT * FROM rpbios WHERE acctid=".$this->int_acctid." AND parent=0 AND activ=1 ".$this->str_subquery." ORDER BY sort ASC LIMIT 1");
            $this->int_pageid = intval($temp_page['pageid']);
        }
        else
        {
            $this->int_pageid = intval($int_pageid);
        }


        $this->arr_page = db_get("SELECT * FROM rpbios WHERE acctid=".$this->int_acctid." AND pageid=".$this->int_pageid." AND activ=1 ".$this->str_subquery." LIMIT 1");
        if(count($this->arr_page)==0) redirect($stecki,false,false);

        if($this->arr_page['parent'] != 0)
        {
            $tpa = db_get("SELECT * FROM rpbios WHERE acctid=".$this->int_acctid." AND pageid=".$this->arr_page['parent']." AND activ=1 ".$this->str_subquery." LIMIT 1");
            if(count($tpa)==0) redirect($stecki,false,false);
        }

        $lebio = CBioCleaner::check_url('<!DOCTYPE html>
<html lang="en">
    <head>
                <meta charset="UTF-8" />
                <title>RP-Bio von '.strip_appoencode($this->str_name,3).'</title>

                '.JS::encapsulate('./jquery/jquery-ui-1.10.4.custom/js/jquery-1.11.1.min.js',true).'
                 '.JS::encapsulate('./jquery/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js',true).'

                '.JS::encapsulate('jquery/jMenu.jquery.min.js',true).'

                '.JS::encapsulate('
                var atrajQ = jQuery.noConflict();

                  atrajQ(document).ready(function(){
                    atrajQ("#jMenu").jMenu({
                      openClick : false,
                      ulWidth : "auto",
                      effects : {
                        effectSpeedOpen : 150,
                        effectSpeedClose : 150,
                        effectTypeOpen : "slide",
                        effectTypeClose : "hide",
                        effectOpen : "linear",
                        effectClose : "linear"
                      },
                      TimeBeforeOpening : 100,
                      TimeBeforeClosing : 11,
                      animatedText : false,
                      paddingLeft: 1
                    });






                    '.(
            ($Char->prefs['newbio_full'] != 1) ?
                '

                    var myElement = atrajQ("#jMenu");
                    var sWidth = myElement.width()+225;

                    if(sWidth > 1010)window.resizeTo(sWidth, 757);
                    else window.resizeTo(1010, 757);

               '
                :
                '


                window.moveTo(0,0);


if (document.all)
{
  top.window.resizeTo(screen.availWidth,screen.availHeight);
}

else if (document.layers||document.getElementById)
{
  if (top.window.outerHeight<screen.availHeight||top.window.outerWidth<screen.availWidth)
  {
    top.window.outerHeight = screen.availHeight;
    top.window.outerWidth = screen.availWidth;
  }
}
                '
            ).'

                  });

                ').'

                 <link href="./jquery/atrahor/normalize.css" rel="stylesheet" type="text/css" />

                <link href="./templates/atrahor_bio_styles.css" rel="stylesheet" type="text/css" />
                <link href="'.TEMPLATE_PATH.'colors.css" rel="stylesheet" type="text/css" />
                 '.$this->getFonts().'
                <style type="text/css">
                '.$this->getCSS().'
                </style>

                </head>
                <body>
                <div id="userbody" class="userbody">

                '.( (($Char->acctid == $this->int_acctid)) ? '<div style="position:fixed; top:10px; right:15px; z-index:105050;"><a href="prefs_bio.php?do=edit'.( isset($_GET['p']) ? '&amp;sdo=edit&amp;p='.$this->int_pageid.'' : '').'">Bearbeiten</a></div>' : '' ).'

'.( (($Char->acctid > 0)) ? '<div style="position:fixed; top:10px; left:15px; z-index:105050;"><a href="steckbrief.php?id='.$this->int_acctid.'">Steckbrief</a></div> ' : '' ).'

                '.$this->getMENU().'
                <div class="user_content">
                 '.$this->getHTML().'
                 </div>
                 </div>
                 '.$template['scriptfile'].$template['scriptprio'].$template['scriptend'].'
                </body>
                </html>',$this->int_acctid);

        CPicture::replace_pic_tags($lebio,$this->int_acctid);
        echo $lebio;
    }

    private function getCSS()
    {
        $out = '
                html, body{
                   padding:0;
                   margin:0;
                }
                body {
                    background-color:'.$this->getConf('body_back','000000').';
                    color:'.$this->getConf('body_text','FFFFFF').';
                    font-family:'.$this->getConf('body_text_fam','Verdana, Arial, Helvetica, sans-serif',false).';
	                font-size: '.$this->getConf('body_text_size',12,false).'px;
                }
                .user_content {

                    padding:10px;
                     min-width:10px;
                     '.( ($this->getConf('fixwidth','',false) != '') ? 'width:'.$this->getConf('fixwidth','',false).';' : '' ).'
                     margin:auto;
                     margin-top:35px;
                }

                #lemenu{
                   border-bottom:1px solid '.$this->getConf('menu_trenn_color','e5e5e5').';
                   width:100%;
                   position:fixed;
                   left:0px;
                   top:0px;
                   z-index: 9999;
                    background-color:'.$this->getConf('body_back','000000').';
                }

                #lemenu * {
                     padding:0;
                   margin:0;
                }

                #jMenu {
                  display:table;
                    padding:0;
                    text-align:center;
                    margin:auto;
                     font-family:'.$this->getConf('menu_text_fam','Verdana, Arial, Helvetica, sans-serif',false).';
                }

                #jMenu li {
                    display:table-cell;
                    background-color:'.$this->getConf('menu_back','000000').';
                    margin:0;

                }

                #jMenu li a {
                        padding:5px;
                        margin:5px;

                        display:block;
                        background-color: '.$this->getConf('menu_top_back','322f32').';
                        color:'.$this->getConf('menu_color','fff').';

                        cursor:pointer;
                       font-size: '.$this->getConf('menu_text_size',11,false).'px;
                        min-width:80px;
                        text-align:center;
                }



                #jMenu li a:hover, #jMenu .active {
                        background-color: '.$this->getConf('menu_top_back_hover','a52a2a').';
                        color:'.$this->getConf('menu_color_hover','fff').';
                }

                #jMenu li ul {
                    display:none;
                    position:absolute;
                    padding:0;
                    margin:0;
                }

                #jMenu li ul li {
                    display:block;
                    padding:0;


                 }

                #jMenu li ul li a {
                    font-size: '.($this->getConf('menu_text_size',11,false)-1).'px;
                    text-transform:none;
                    display:block;
                     margin:0;
                    margin-left:5px;
                    margin-right:5px;

                    word-wrap: break-word;
                    border-top:5px solid '.$this->getConf('menu_back','000000').';
                }

                #jMenu li ul li:last-child a {
                    border-bottom:5px solid '.$this->getConf('menu_back','000000').';
                }

                img {
                 margin:0;
                 padding:0;
                 vertical-align:bottom;
                }

                ';
        $out .= strip_tags($this->arr_config['css']);
        $out .= strip_tags($this->arr_page['css']);

        CBioCleaner::$cleanid = $this->int_acctid;
        $regex = '/url\s*\(([^)]*)\)/i';
        $out = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_url"),$out);

        $out = str_ireplace(array('http','javascript','vbscript','expression','data:text','base64'),'',$out);

        return $out;
    }

    private function getFonts()
    {

        $arr = array_merge(explode("\n",$this->arr_config['fonts']),explode("\n",$this->arr_page['fonts']));

        $out = '';

        foreach($arr as $font)
        {
            $font = stripslashes(str_ireplace(array('http','javascript','vbscript'),'',$font));
            if(preg_match('|^family=[^:&/]*(:[0-9a-z,]*)?(&subset=[a-z,-]*)?$|i',trim($font)))
            {
                 $out .= "
                            <link href='//fonts.googleapis.com/css?".utf8_htmlspecialchars($font)."' rel='stylesheet' type='text/css'>
                        ";
            }

        }

        return $out;
    }


    private function getMENU()
    {
        $out = '<div id="lemenu"><ul id="jMenu">';

        $seiten_res = db_query("SELECT * FROM rpbios WHERE acctid='".$this->int_acctid."' AND parent = 0 AND activ=1 ".$this->str_subquery." ORDER BY sort ASC LIMIT 9");
        $is_active = false;
        while($seite = db_fetch_assoc($seiten_res))
        {
            $subout = '';

            if($this->int_pageid == $seite['pageid']) $is_active = true;

            $subseiten = db_get_all("SELECT * FROM rpbios WHERE acctid='".$this->int_acctid."' AND parent = '".$seite['pageid']."' AND activ=1 ".$this->str_subquery." ORDER BY sort ASC LIMIT 9");
            if(count($subseiten)>0)
            {
                $subout .= '<ul>';
                foreach($subseiten as $subseite)
                {
                    if($this->int_pageid == $subseite['pageid']) $is_active = true;
                    $subout .= '<li><a href="bio.php?id='. $this->int_acctid .'&amp;p='. $subseite['pageid'] .'" '.( ($this->int_pageid == $subseite['pageid']) ? ' class="active" ' : '').'>'.$subseite['titel'].'</a></li>';
                }
                $subout .= '</ul>';
            }
            $out .= '<li><a href="bio.php?id='. $this->int_acctid .'&amp;p='. $seite['pageid'] .'" class="fNiv'.( $is_active ? ' active' : '').'">'.$seite['titel'].'</a>'.$subout.'</li>';
            $is_active = false;
        }

        $out .= '</ul> </div>';
        return $out;
    }

    private function getHTML()
    {
        $out = $this->arr_page['content'];

        $out = clean_html($out,true,true,false,false,true,true);

        $out = appoencode(str_replace('`&amp;','`&',$out));

        $out =  utf8_preg_replace_callback("/(\[.+\])/iUS",array('CSteckbrief','replace_names'),$out);
        $out =  utf8_preg_replace_callback("/(\(.+\))/iUS",array('CSteckbrief','replace_cnames'),$out);

        $out = appoencode(str_replace('`&amp;','`&',$out));

        CBioCleaner::$cleanid = $this->int_acctid;
        $regex = '/<img([^><]*)src=[\'"]*([^\'"]*)[\'"]*([^><]*)[>]*/i';
        $out = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_img"),$out);

        $regex = '/url\s*\(([^)]*)\)/i';
        $out = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_url"),$out);

        $regex = '/background\s*=\s*[\'"]*([^\'"]*)[\'"]*/i';
        $out = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_url_back"),$out);

        $out = str_ireplace(array('http','javascript','vbscript','expression','data:text','base64'),'',$out);

        return $out;
    }

    private function getConf($str_name,$mix_default,$color=true)
    {
        if($color) return CBioCleaner::outputHEX($this->arr_config['config'][$str_name],false,$mix_default);
        $ret =  ( (isset($this->arr_config['config'][$str_name]) && $this->arr_config['config'][$str_name] != '') ? $this->arr_config['config'][$str_name] : $mix_default);
        return  ''.( ($color && $ret != 'transparent') ? '#' : '' ).''.$ret ;
    }

    public static function newpage($int_acctid,$parent=0)
    {
        $lastpage = db_get("SELECT pageid FROM rpbios WHERE acctid='".$int_acctid."' ORDER BY pageid DESC");
        $nextpage = intval($lastpage['pageid']) + 1;

        $lastsort = db_get("SELECT sort FROM rpbios WHERE acctid='".$int_acctid."' AND parent=".intval($parent)." ORDER BY sort DESC LIMIT 1");

        if(db_query("INSERT INTO rpbios (id,acctid,pageid,parent,titel,content,css,sort) VALUES (null,'".$int_acctid."','".$nextpage."',".intval($parent).",'Neue Seite','','',".( ($lastsort['sort']+1) ).")"))
        {
            return $nextpage;
        }

        return 'fail';
    }

    public static function savesort($int_acctid,$arr_sort)
    {
        if(is_array($arr_sort))
        {
            $bigsort = 1;
            foreach($arr_sort as $sort)
            {
                db_query("UPDATE rpbios SET sort=".intval($bigsort).",parent=0 WHERE acctid=".intval($int_acctid)." AND pageid=".intval($sort->id)." LIMIT 1");
                $smalsort = 1;
                foreach($sort->subs as $subsort)
                {
                    db_query("UPDATE rpbios SET sort=".intval($smalsort).",parent=".intval($sort->id)." WHERE acctid=".intval($int_acctid)." AND pageid=".intval($subsort)." LIMIT 1");
                    $smalsort++;
                }

                $bigsort++;
            }
            return 'done';
        }

        return 'fail';
    }

    public static function check()
    {
        global $Char;

        $arr_config = db_get("SELECT * FROM rpbios_config WHERE acctid=".$Char->acctid." LIMIT 1");
        if(count($arr_config)==0)
        {
            db_query("INSERT INTO rpbios_config (id,acctid,css,config) VALUES (null,'".intval($Char->acctid)."','','')");
            $arr_config = db_get("SELECT * FROM rpbios_config WHERE acctid=".$Char->acctid." LIMIT 1");
        }
        return $arr_config;
    }

    public static function can_see()
    {
        return false;
    }
}
?>
