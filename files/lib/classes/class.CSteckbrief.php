<?php
/**
 * steckbrief.php: 		Zeigt Steckbrief + News eines Spielers an
 *				Sympathie-Addon by Maris
 *				erfordert Felder "sympathy", "symp_votes" und "symp_given" in der Tabelle account_extra_info,
 *				entsprechenden Eintrag in hof.php und Rücksetzung von symp_given in newday.php
 *				completely rewritten by Alucard
 * 				frei beschreibbare Textfelder hinzugefügt von Fussel
 * @author OOP + fixes by Báthory
 * @version DS-E V/3.x
 */

class CSteckbrief
{

    private $int_maxSymp = 10;
    private $arr_bioData = array();
    private $str_bioOutput = '';
    public $int_acctid = 0;
    private $str_char = '';
    private $arr_profilHead = array();
    private $arr_profilVal = array();
    private $hc = '';
    private $vc = '';

    private $arr_config = array();

    public static $arr_ausblendbar = array();

    function __construct($int_acctid, $str_char)
    {
        global $session, $access_control;

        $this->int_maxSymp = getsetting('max_symp','10');

        $result = db_query('SELECT
			activated, '.($session['user']['profession'] == PROF_GUARD || $session['user']['profession'] == PROF_GUARD_HEAD ? '((maxhitpoints/30)+(attack*1.5)+(defence)) AS strength, ' : '').'
			login, loggedin, laston, accounts.name, level, sex, title, specialty, hashorse, acctid, age, marriedto, pvpflag, charisma, charm, weapon,
			armor, kleidung, imprisoned, profession, resurrections, dragonkills, race, house, punch, reputation, marks, exchangequest,
			guildid, guildfunc, guildrank, expedition, r.name AS racename, prefs
			FROM accounts
			LEFT JOIN races r ON r.id = accounts.race
			WHERE '.
            ($int_acctid>0 ? 'acctid='.$int_acctid : 'login="'.$str_char.'"') );

        if(db_num_rows($result) == 0)
        {
            clearnav();
            $session['user']['output']='';

            echo('Diese Bio existiert nicht!<br /><br />Bitte teile dem Admin-Team mit, was du getan hast um hier zu landen, damit der Fehler behoben werden kann.');
            exit;
        }
        CBioCleaner::$cleanid = $int_acctid;
        $this->int_acctid = $int_acctid;
        $this->getBioData(db_fetch_assoc($result));
    }

    public function isNew()
    {
        return true;
    }

    protected function getBioData($arr_row)
    {
        $this->arr_bioData['prefs'] = utf8_unserialize($arr_row['prefs']);

        if(!isset($this->arr_bioData['prefs']['aus_guestbook']))$this->arr_bioData['prefs']['aus_guestbook'] = 1;
        if(!isset($this->arr_bioData['prefs']['aus_ooc']))$this->arr_bioData['prefs']['aus_ooc'] = 1;
        if(!isset($this->arr_bioData['prefs']['aus_rp']))$this->arr_bioData['prefs']['aus_rp'] = 1;
        if(!isset($this->arr_bioData['prefs']['aus_multi']))$this->arr_bioData['prefs']['aus_multi'] = 1;

        $this->arr_bioData['row'] = $arr_row;
        $this->arr_bioData['extra'] = $this->getExtra($arr_row['acctid']);
        $this->arr_bioData['disciple'] = $this->getDisciple($arr_row['acctid']);
        $this->arr_bioData['skill'] = $this->getSkill($arr_row['specialty']);
        $this->arr_bioData['marks'] = $arr_row['marks'];
        $this->arr_bioData['marks2'] = $this->getMarks($arr_row['marks']);
        $this->arr_bioData['symp'] = $this->getSymp($arr_row['acctid']);
        $this->arr_bioData['mount'] = $this->getMount($arr_row['hashorse']);
        $this->arr_bioData['house'] = $this->getHouse($arr_row['house']);
        $this->arr_bioData['laston'] = $this->getLastOn($arr_row);
        $this->arr_bioData['p_ei'] = utf8_unserialize($this->arr_bioData['extra']['ext_profile']);

        $this->arr_bioData['p_ei']['bmount'] =1;
        $this->arr_bioData['p_ei']['bdisciple'] = 1;


        $this->arr_bioData['p_ei']['extra_info']=true;
        $this->arr_bioData['p_ei']['disciple']=true;

        $this->arr_bioData['p_ei']['mount']=true;

        $this->arr_bioData['p_ei']['marks'] = true;

        $this->arr_bioData['name'] = $arr_row['name'];
        $this->arr_bioData['cleanname'] = strip_appoencode($arr_row['name'],3);
        $this->arr_bioData['arr_textfield'] = $this->getTextfields($arr_row['acctid']);
        $this->int_acctid = $arr_row['acctid'];
        CBioCleaner::$cleanid = $this->int_acctid;
        $this->str_char = $arr_row['login'];
        $this->vc = $this->extBioColor($this->arr_bioData['p_ei']['colors'], 'value', '@',true);
        $this->hc = $this->extBioColor($this->arr_bioData['p_ei']['colors'], 'head', '0',true);


        $this->arr_config = db_get("SELECT * FROM rpbios_config WHERE acctid=".$this->int_acctid." LIMIT 1");
        $this->arr_config['config'] = utf8_unserialize($this->arr_config['config']);
    }

    private function getConf($str_name,$mix_default,$color=true)
    {
        if($color) return CBioCleaner::outputHEX($this->arr_config['config'][$str_name],false,$mix_default);
        $ret =  ( (isset($this->arr_config['config'][$str_name]) && $this->arr_config['config'][$str_name] != '') ? $this->arr_config['config'][$str_name] : $mix_default);
        return  ''.( ($color && $ret != 'transparent') ? '#' : '' ).''.$ret ;
    }

    private function getFonts()
    {
        $arr = explode("\n",$this->arr_config['fonts']);
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

    private function getCSS()
    {
        $out = $this->arr_config['css'];

        CBioCleaner::$cleanid = $this->int_acctid;
        $regex = '/url\s*\(([^)]*)\)/i';
        $out = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_url"),$out);

        $out = str_ireplace(array('http','javascript','vbscript','expression','data:text','base64'),'',strip_tags($out));

        return $out;
    }

    protected function getExtra($int_uid)
    {
        $aei = user_get_aei(
            'symp_given, symp_votes, sympathy, acctid, job, biotime, cname, ctitle, xmountname,hasxmount,charclass,
							 html_locked, birthday, avatar,  runes_ident, char_birthdate, ext_profile, ext_rp,ext_ooc, ext_multis,ext_bio_orte,
							 together_with,quests_sterne,quests_solved,quests_time',$int_uid
        );
        return $aei;
    }

    protected function getDisciple($int_mid)
    {
        $resdisc = db_query('SELECT * FROM disciples WHERE master = '.$int_mid);
        return ((db_num_rows($resdisc) > 0) ? db_fetch_assoc($resdisc) : '');
    }

    protected function getMarks($int_marks)
    {
        return get_marks_state($int_marks);
    }

    protected function getSkill($int_specid)
    {
        $rowskill = db_fetch_assoc(db_query("SELECT specid,specname FROM specialty WHERE specid = '".$int_specid."' "));
        return array($rowskill['specid']=>$rowskill['specname']);
    }

    protected function getHouse($int_house)
    {
        return (($int_house > 0) ? db_fetch_assoc(db_query("SELECT housename,status,build_state,houseid FROM houses WHERE houseid='".$int_house."'")) : '');
    }

    protected function getMount($int_mountid)
    {
        $mount = db_fetch_assoc(db_query("SELECT mountname FROM mounts WHERE mountid='".$int_mountid."'"));
        if ($mount['mountname']=='')
        {
            $mount['mountname'] = '`iKeines`i';
        }
        return $mount;
    }

    protected function getSymp($int_uid)
    {
        $symp_points = db_fetch_assoc(db_query("SELECT COUNT(*) AS c FROM sympathy_votes WHERE to_user=".$int_uid));
        $symp_points = $symp_points['c'];
        $symp_disp = '';

        if($symp_points == 0) { $symp_disp = 'keine'; }
        else if($symp_points < 4) { $symp_disp = 'sehr wenig'; }
        else if($symp_points < 8) { $symp_disp = 'wenig'; }
        else if($symp_points < 16) { $symp_disp = 'einiges'; }
        else if($symp_points < 26) { $symp_disp = 'viel'; }
        else if($symp_points < 51) { $symp_disp = 'sehr viel'; }
        else { $symp_disp = 'unglaublich viel'; }

        return $symp_disp;
    }

    protected function getLastOn($arr_row)
    {
        if(user_get_online(0,$arr_row))
        {
            return 'Jetzt';
        }
        else
        {
            $laston = round((strtotime(date('r')) - strtotime($arr_row['laston'])) / 86400,0).' Tage';
            if (mb_substr($laston,0,2)=='1 ') { $laston = '1 Tag'; }
            else if (date('Y-m-d',strtotime($arr_row['laston'])) == date('Y-m-d')) { $laston = 'Heute'; }
            else if (date('Y-m-d',strtotime($arr_row['laston'])) == date('Y-m-d',strtotime(date('r').'-1 day'))) { $laston = 'Gestern'; }
            return $laston;
        }
    }

    protected function getTextfields($int_acctid)
    {
        $arr_textfield = array();
        $res_textfield = db_query('SELECT * FROM account_bio_freetexts WHERE acctid='.$int_acctid
            .' ORDER BY sort ASC');
        $i = 0;
        while ($textfield = db_fetch_assoc($res_textfield))
        {
                $arr_textfield[$textfield['pos2']][$i] = array('title' => clean_html($textfield['field_title'],true,true,false,true,false,true), 'value' => clean_html($textfield['field_value'],true,true,false,true,false,true) );
                $i++;
        }
        return $arr_textfield;
    }

    protected function dateToGerman($timestamp)
    {
        $tages = array("Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag");
        $tag = strftime("%w",$timestamp);
        $tag1 = strftime("%d",$timestamp);
        $monate = array("01"=>"Januar","02"=>"Februar","03"=>"März","04"=>"April","05"=>"Mai","06"=>"Juni","07"=>"Juli","08"=>"August","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Dezember");
        $monat = strftime("%m",$timestamp);
        $datum = $tages[$tag].", ".$tag1.". ".$monate[$monat];
        return $datum;
    }

    private function extBioColor(&$arr_colors, $str_section, $str_default, $appo = false)
    {
        $val = ($arr_colors[$str_section] != '' ? $arr_colors[$str_section] : $str_default);

        if($appo){
            if($val == 'transparent')$val= $str_default;
            return ((mb_strlen($val) > 1) ? '²' : '`').$val.((mb_strlen($val) > 1) ? ';' : '');
        }else{
            return CBioCleaner::outputHEX($val);
        }
    }

    protected function getImage($str_src, $str_alt = '', $int_x = 200, $int_y = 200)
    {
        if($str_src != '')
        {
            if(($str_src))
            {
                $ret = '<img src="'.$str_src.'" ';
                $ret .= 'alt="'.utf8_htmlspecialchars(strip_appoencode($str_alt,3)).'">';
            }
            else
            {
                $ret = '`n`n(kein Bild)&nbsp;&nbsp;&nbsp;';
            }
        }
        else
        {
            $ret = '`n`n(kein Bild)&nbsp;&nbsp;&nbsp;';
        }

        return $ret;
    }

    public function checkDelHistory()
    {
        return (($_GET['op'] == 'del_history') ? $this->delHistory($_GET['history_id']) : false);
    }

    private function delHistory($int_id)
    {
        db_query("DELETE FROM history WHERE id=".(int)$int_id);
        return true;
    }

    public function prepareOutput($arr_fields)
    {
        foreach($arr_fields as $key => $val)
        {
            if(in_array($val,self::$arr_ausblendbar))
            {
                if($this->arr_bioData['prefs']['aus_'.$val] != '1')
                {
                    $this->getField($val,count($arr_fields)-2);
                }
            }
            else
            {
                $this->getField($val,count($arr_fields)-2);
            }
        }
    }

    public function outputBio()
    {
        $this->str_bioOutput = appoencode($this->str_bioOutput);
        $this->str_bioOutput =  utf8_preg_replace_callback("/(\[.+\])/iUS",array('CSteckbrief','replace_names'),$this->str_bioOutput);
        $this->str_bioOutput =  utf8_preg_replace_callback("/(\(.+\))/iUS",array('CSteckbrief','replace_cnames'),$this->str_bioOutput);
        $this->str_bioOutput = appoencode($this->str_bioOutput);
        echo $this->str_bioOutput;
    }


    protected function getField($str_field,$cols=9)
    {
        global $session,$Char,$template;

        switch($str_field)
        {
            case 'rp':
                $this->arr_profilHead['rp_t'] = 'RP-Info,title,1';
                $this->arr_profilHead['rp'] = ',viewonly';

                $this->arr_bioData['extra']['ext_rp'] = clean_html($this->arr_bioData['extra']['ext_rp'],true,true,false,true,true,true);

                CBioCleaner::$cleanid = $this->int_acctid;

                $regex = '/<img([^><]*)src=[\'"]*([^\'"]*)[\'"]*([^><]*)[>]*/i';
                $this->arr_bioData['extra']['ext_rp'] = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_img"),$this->arr_bioData['extra']['ext_rp']);

                $regex = '/url\s*\(([^)]*)\)/i';
                $this->arr_bioData['extra']['ext_rp'] = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_url"),$this->arr_bioData['extra']['ext_rp']);

                $regex = '/background\s*=\s*[\'"]*([^\'"]*)[\'"]*/i';
                $this->arr_bioData['extra']['ext_rp'] = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_url_back"),$this->arr_bioData['extra']['ext_rp']);

                $this->arr_bioData['extra']['ext_rp'] = CBioCleaner::check_url(str_ireplace(array('http','javascript','vbscript','expression','data:text','base64'),'',$this->arr_bioData['extra']['ext_rp']),$this->int_acctid);

                CPicture::replace_pic_tags($this->arr_bioData['extra']['ext_rp'],$this->int_acctid);

                $this->arr_profilVal['rp'] = $this->arr_bioData['extra']['ext_rp'];
                break;
            case 'ooc':
                $this->arr_profilHead['ooc_t'] = 'OOC,title,1';
                $this->arr_profilHead['ooc'] = ',viewonly';

                $this->arr_bioData['extra']['ext_ooc'] = clean_html($this->arr_bioData['extra']['ext_ooc'],true,true,false,true,true,true);

                CBioCleaner::$cleanid = $this->int_acctid;

                $regex = '/<img([^><]*)src=[\'"]*([^\'"]*)[\'"]*([^><]*)[>]*/i';
                $this->arr_bioData['extra']['ext_ooc'] = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_img"),$this->arr_bioData['extra']['ext_ooc']);

                $regex = '/url\s*\(([^)]*)\)/i';
                $this->arr_bioData['extra']['ext_ooc'] = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_url"),$this->arr_bioData['extra']['ext_ooc']);

                $regex = '/background\s*=\s*[\'"]*([^\'"]*)[\'"]*/i';
                $this->arr_bioData['extra']['ext_ooc'] = utf8_preg_replace_callback($regex,array("CBioCleaner","fix_url_back"),$this->arr_bioData['extra']['ext_ooc']);

                $this->arr_bioData['extra']['ext_ooc'] = CBioCleaner::check_url(str_ireplace(array('http','javascript','vbscript','expression','data:text','base64'),'',$this->arr_bioData['extra']['ext_ooc']),$this->int_acctid);

                CPicture::replace_pic_tags($this->arr_bioData['extra']['ext_ooc'],$this->int_acctid);

                $this->arr_profilVal['ooc'] = $this->arr_bioData['extra']['ext_ooc'];
                break;
            case 'multi':
                $this->arr_profilHead['multi_t'] = 'Multis,title,1';
                $this->arr_profilHead['multi'] = ',viewonly';

                $mulprefs = utf8_unserialize($this->arr_bioData['extra']['ext_multis']);

                $res = db_squeryf(' SELECT DISTINCT a.name, a.acctid, a.login
					FROM account_multi am
					JOIN accounts a
					ON a.acctid<>"%d" AND (a.acctid=am.master OR a.acctid=am.slave)
					WHERE am.master="%d" OR am.slave="%d"', $this->int_acctid, $this->int_acctid, $this->int_acctid);
                $multis = '<table cellspacing="5" cellpadding="5" style="max-width: 60%; margin: auto;">';
                while($r = db_fetch_assoc($res)){

                    if(1 == $mulprefs[$r['acctid'].'_show'])
                    {
                        $p_img = '[PIC=p]';
                        CPicture::replace_pic_tags($p_img, $r['acctid'],null,null,false,100);
                        if($p_img=='Kein Bild hochgeladen!')$p_img='';
                        $multis .= '
                    <tr style="" >
                    <td '.( ($p_img != '') ? '' : '').'><a href = "steckbrief.php?id='.$r['acctid'].'">'.$p_img.'</a>'.'</td>
                    <td '.( ($mulprefs[$r['acctid'].'_text'] != '') ? 'valign="top"' : 'valign="middle"').'>

                    <a href = "steckbrief.php?id='.$r['acctid'].'">`&'.$r['name'].'`0</a>

                     '.( ($mulprefs[$r['acctid'].'_text'] != '') ? '<div style="padding-top:4px;">'.appoencode($mulprefs[$r['acctid'].'_text']).'</div>' : '').'
                    </td>
                    </tr>
                    ';
                    }
                    //style="border: 1px solid '.$this->extBioColor($this->arr_bioData['p_ei']['colors'], 'btn_back', '550000').';"
                }
                $multis .= '</table>';
                $this->arr_profilVal['multi'] = '`c'.$multis.'`c';
                break;
            case 'guestbook':
                $this->arr_profilHead['guestbook_t'] = 'Gästebuch,title,1';
                $this->arr_profilHead['guestbook'] = ',viewonly';

                if(isset($_POST['gbtext']))
                {
                    db_query("INSERT INTO bio_guestbook (owner,acctid,text,date,ip) VALUES (
                        '".intval($this->int_acctid)."'
                        , '".intval($Char->acctid)."'
                        , '".db_real_escape_string(utf8_htmlspecialchars($_POST['gbtext']))."'
                        , NOW()
                        , '".db_real_escape_string($_SERVER['REMOTE_ADDR'])."'
                        )");

                    systemmail($this->int_acctid,"`^Gästebuch-Eintrag erhalten!`0",'`&'.$Char->name.' hat einen Eintrag in deinem Gästebuch hinterlassen!`n`n'.($_POST['gbtext']));
                }

                if(isset($_GET['gbdel']) && isset($_GET['gbid']))
                {
					$gdata_old = db_get("SELECT * FROM bio_guestbook WHERE deleted = 0 AND owner='".intval($this->int_acctid)."' AND id='".intval($_GET['gbid'])."' LIMIT 1");
					
					if(count($gdata_old) && ($Char->acctid == $gdata_old['acctid']) || ($Char->acctid == $gdata_old['owner']))
					{
						 db_query("UPDATE bio_guestbook SET deleted=1 WHERE owner='".intval($this->int_acctid)."' AND id='".intval($_GET['gbid'])."' LIMIT 1");
					}
                }

                $gdata = '';

                $gdata_res = db_query("SELECT * FROM bio_guestbook WHERE deleted = 0 AND owner = '".intval($this->int_acctid)."' ORDER BY id DESC");

                while($gdata_row = db_fetch_assoc($gdata_res))
                {

                    $array_user = db_fetch_assoc(db_query("SELECT name,acctid FROM accounts WHERE acctid = '".$gdata_row['acctid']."'  LIMIT 1"));

                    $gdata .= '<div style="border-bottom: 1px solid '.$this->extBioColor($this->arr_bioData['p_ei']['colors'], 'btn_back_a', '550000').'; padding: 15px; margin:auto; width:500px; margin-bottom: 8px;">

                    <a href="steckbrief.php?id='.$array_user['acctid'].'">'.$array_user['name'].'</a>

                    '.( ( ($Char->acctid == $gdata_row['acctid']) || ($Char->acctid == $gdata_row['owner']) ) ? '
                    <span style="float:right; font-size:10px;">[<a href="steckbrief.php?id='.$this->int_acctid.'&gbdel=1&gbid='.$gdata_row['id'].'">del</a>]</span>
                    ' : '' ).'<br style="clear:both;" /><br />
                    '.closetags($gdata_row['text'],'`b`c`i').'
                    </div>';
                }

                $gdata .= '<br /><form action="steckbrief.php?id='.$this->int_acctid.'" method="post" >
                          <table border="0" cellpadding="5" cellspacing="0" style="margin:auto;">
                            <tr>
                              <td align="right" valign="top">Eintrag:</td>
                              <td><textarea name="gbtext" rows="10" cols="50"
                              style="background: '.$this->extBioColor($this->arr_bioData['p_ei']['colors'], 'body', '000000').';
                              color:'.$this->getConf('body_text','FFFFFF').'; "></textarea></td>
                            </tr>
                            <tr>
                              <td align="right"></td>
                              <td>
                                <input type="submit" value=" Absenden ">
                                <input type="reset" value=" Abbrechen">
                              </td>
                            </tr>
                          </table>
                        </form>';

                $this->arr_profilVal['guestbook'] = $gdata;
                break;

            case 'stammbaum':
                $stammb = new CStammbaum($this->int_acctid);
                if($stammb->has_tree())
                {
                    $this->arr_profilHead['gtree_t'] = 'Stammbaum,title,1';
                    $this->arr_profilHead['gtree'] = ',viewonly';
                    $this->arr_profilVal['gtree'] = $stammb->get_tree();
                }
                unset($stammb);
                break;

            case 'footer':
                $this->str_bioOutput .=  generateform($this->arr_profilHead,$this->arr_profilVal,true,'',$cols,true).'</div>'.$template['scriptfile'].$template['scriptprio'].$template['scriptend'].'</body></html>';
                $this->str_bioOutput = utf8_preg_replace('/(<span class="c)(\d+)(">)(\s*)(<\/span>)/','$4',$this->str_bioOutput);
                break;

            case 'header':



                $this->str_bioOutput = '<!DOCTYPE HTML PUBLIC \'-//W3C//DTD HTML 4.01 Transitional//DE\'>
					<html>
					<head>
						<title>Charakter Steckbrief: '.$this->arr_bioData['cleanname'].'</title>
						<link href="./templates/atrahor_bio_styles.css" rel="stylesheet" type="text/css">
						<style type="text/css">


                             html, body{
                                   padding:0;
                                   margin:0;
                                }
                                body {
                                    background:'.$this->extBioColor($this->arr_bioData['p_ei']['colors'], 'body', '000000').';
                                    color:'.$this->extBioColor($this->arr_bioData['p_ei']['colors'], 'body_text', 'CCCCCC').';
                                    font-family:'.$this->getConf('body_text_fam','Verdana, Arial, Helvetica, sans-serif',false).';
                                    font-size: '.$this->getConf('body_text_size',12,false).'px;
                                }

                                td {
                                    font-family:'.$this->getConf('body_text_fam','Verdana, Arial, Helvetica, sans-serif',false).';
                                    font-size: '.$this->getConf('body_text_size',12,false).'px;
                                }

                             .user_content {

                                                padding:10px;
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

                            #ulmenu {
                              display:table;
                                padding:0
                                text-align:center;
                                margin:auto;
                                font-family:'.$this->getConf('menu_text_fam','Verdana, Arial, Helvetica, sans-serif',false).';
                            }

                            #ulmenu li {
                                display:table-cell;
                                background-color:'.$this->getConf('menu_back','000000').';
                                margin:0;
                                min-height:30px;
                            }

                            #ulmenu li span {
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

                              #ulmenu li span:hover, #ulmenu .form_title_selected span {
                                    background-color: '.$this->getConf('menu_top_back_hover','a52a2a').';
                                    color:'.$this->getConf('menu_color_hover','fff').';
                            }

                            #ulmenu li ul {
                                display:none;
                                position:absolute;
                                padding:0;
                                margin:0
                            }

                            #ulmenu li ul li {
                                display:block;
                                padding:0
                             }

                            #ulmenu li ul li span {
                                font-size: '.($this->getConf('menu_text_size',11,false)-1).'px;
                                text-transform:none;
                                display:block;
                                margin:0;
                                margin-left:5px;
                                margin-right:5px;
                            }

						</style>
						<style type="text/css">@import url('.TEMPLATE_PATH.'colors.css);</style>

                        '.$this->getFonts().'

                         <style type="text/css">
                '.$this->getCSS().'
                </style>

						'.JS::encapsulate('./jquery/jquery-ui-1.10.4.custom/js/jquery-1.11.1.min.js',true).'

 '.JS::encapsulate('./jquery/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js',true).'

                        '.JS::encapsulate('
                        var atrajQ = jQuery.noConflict();
                          atrajQ(document).ready(function(){







         '.(
                    ($Char->prefs['newbio_full'] != 1) ?
                        '







                           '.(
                        ($Char->prefs['newbio_big'] != 1) ?
                            '

                            var myTable = atrajQ("#infotable");

                            var myMenu = atrajQ("#ulmenu");
                            var sWidth = myTable.width();
                            var sHeight = myTable.height()+220;



                            if(myMenu.width() > myTable.width()-200 )
                            {
                               var sWidth = myMenu.width()+200;
                            }




                                    if(sWidth < (770)) sWidth = 770;

                                    if(sHeight < (600)) sHeight = 600;

                                   '.( ($this->arr_bioData['prefs']['aus_info']==0) ? 'window.resizeTo(sWidth, sHeight);' : '').'
                    '
                            :
                            '
                            var myElement = atrajQ("#ulmenu");
                            var sWidth = myElement.width()+225;

                            if(sWidth > 1010)window.resizeTo(sWidth, 757);
                            else window.resizeTo(1010, 757);
                            '
                        ).'








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

                    '.JS::jqueryInit().'

					</head>
					<body> <div class="user_content">
					'.jslib_init();

                if($this->arr_bioData['p_ei']['marks'] || $this->arr_bioData['extra']['job'] > 0)
                {
                    $this->str_bioOutput .= '
						'.JS::encapsulate('
							LOTGD.m_on_document_loaded.push(
								function(){
									'.
                        ($this->arr_bioData['marks2']['air'] 		? 'LOTGD.Hint.add(document.getElementById("markAir"), "<b>Mal der Luft<\/b>",false,true);' : '').
                        ($this->arr_bioData['marks2']['earth'] 	? 'LOTGD.Hint.add(document.getElementById("markEarth"), "<b>Mal der Erde<\/b>",false,true);' : '').
                        ($this->arr_bioData['marks2']['spirit'] 	? 'LOTGD.Hint.add(document.getElementById("markSpirit"), "<b>Mal des Geistes<\/b>",false,true);' : '').
                        ($this->arr_bioData['marks2']['water'] 	? 'LOTGD.Hint.add(document.getElementById("markWater"), "<b>Mal des Wassers<\/b>",false,true);' : '').
                        ($this->arr_bioData['marks2']['fire'] 	? 'LOTGD.Hint.add(document.getElementById("markFire"), "<b>Mal des Feuers<\/b>",false,true);' : '').
                        ($this->arr_bioData['marks'] >= CHOSEN_BLOODGOD 	? 'LOTGD.Hint.add(document.getElementById("markBlood"), "<b>Pakt mit dem Blutgott<\/b>",false,true);' : '').
                        '
								});
						').'


						'.( (($Char->acctid == $this->int_acctid)) ? '<div style="position:fixed; top:10px; right:15px; z-index:105050;"><a href="prefs_steckbrief.php">Bearbeiten</a></div>' : '' ).'

'.( (($Char->acctid > 0)) ? '<div style="position:fixed; top:10px; left:15px; z-index:105050;"><a href="bio.php?id='.$this->int_acctid.'">Biographie</a></div> ' : '' ).'


						';
                }
                break;

            case 'info':
                $this->arr_profilHead['player_t'] = str_replace(' ',' ',$this->str_char).',title,1';
                $this->arr_profilHead['player'] = ',viewonly';
                $this->arr_profilVal['player'] = $this->getInfoPage($this->arr_bioData['prefs']['no_level']);
                break;
            case 'male':
                if( $this->arr_bioData['p_ei']['marks'] )
                {
                    $this->arr_profilHead['male_t'] = 'Male,title,1';
                    $this->arr_profilHead['male'] = ',viewonly';
                    $p_male .= '`n`n`n<center>
						<table width="500">
							<tr>
								<td align="center">
									<img src="./images/mal/pentagramm'.($this->arr_bioData['marks'] >= CHOSEN_FULL ? '1':'').'.png">`n
									<img id="markAir" src="./images/mal/luft'.($this->arr_bioData['marks2']['air'] ? '1':'').'.png" style="position: relative; top: -175px; left: -70px; z-index: 2;">
									<img id="markEarth" src="./images/mal/erde'.($this->arr_bioData['marks2']['earth'] ? '1':'').'.png" style="position: relative; top: -5px; left: -55px; z-index: 3;">
									<img id="markSpirit" src="./images/mal/geist'.($this->arr_bioData['marks2']['spirit'] ? '1':'').'.png" style="position: relative; top: -290px; left: 0px; z-index: 4;">
									<img id="markWater" src="./images/mal/wasser'.($this->arr_bioData['marks2']['water'] ? '1':'').'.png" style="position: relative; top: -175px; left: 110px; z-index: 5;">
									<img id="markFire" src="./images/mal/feuer'.($this->arr_bioData['marks2']['fire'] ? '1':'').'.png" style="position: relative; top: -5px; left: 0px; z-index: 6;">'.
                        ($this->arr_bioData['marks'] >= CHOSEN_BLOODGOD ? '`n<img id="markBlood" src="./images/mal/blut.png" style="position: relative; top: -178px; left: 0px; z-index: 7;">' : '').
                        '</td>
							</tr>
						</table></center>';
                    //PNG-Fix funktioniert nicht, Gifs anzeigen
                    if(mb_strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0'))
                    {
                        $p_male = str_replace('.png','.gif',$p_male);
                    }
                    $this->arr_profilVal['male'] = $p_male;
                }
                break;

            case 'aufzeichnungen':
                $this->arr_profilHead['hist_t'] = 'Aufzeichnungen,title,1';
                $this->arr_profilHead['hist'] = ',viewonly';
                $bool_show_all_history = isset($_GET['show_all_history']);

                $p_history = '`c<input type="button" value="'.($bool_show_all_history?'Versteckte ausblenden':'Alle anzeigen').'" onClick="location.href=\'steckbrief.php?id='.$this->int_acctid.($bool_show_all_history==false?'&amp;show_all_history=1':'').'\'" />`c<hr />';

                $p_history .= show_history(1,$this->int_acctid,false,$bool_show_all_history,false,true,true);
                $this->arr_profilVal['hist'] = $p_history;

                #$this->arr_profilVal['hist'] = str_replace("`^Besonderes Ereignis:`0","<img src='./images/icons/profil.gif'>",$p_history);
                $this->arr_profilVal['hist'] = str_replace("`^Besonderes Ereignis:`0","`y~`0",$p_history);
                #$this->extBioColor($this->arr_bioData['p_ei']['colors'], 'body_text', 'CCCCCC')
                //$this->arr_profilVal['hist'] = utf8_preg_replace('/`@(\d+\.\D+\d+)/',"$1<span style='color:".$this->extBioColor($this->arr_bioData['p_ei']['colors'], 'body_text', 'CCCCCC')."'>",$this->arr_profilVal['hist']);



                break;

            case 'news':

                if(($this->arr_bioData['prefs']['no_level'] && !isset($_GET['sl'])))$dont = true;
                if((!$this->arr_bioData['prefs']['no_level'] && isset($_GET['st'])))$dont = true;

                if( !$dont  )
                {
                    $this->arr_profilHead['news_t'] = 'Leistungen,title,1';
                    $this->arr_profilHead['news'] = ',viewonly';
                    $result = db_query('SELECT newstext,newsdate FROM news WHERE accountid='.$this->int_acctid.'
				 AND newstext != "'.db_real_escape_string('`qDer Wanderhändler ist heute in der Stadt`0').'"
				  AND newstext != "'.db_real_escape_string('`qKeine Spur vom Wanderhändler...`0').'"
				 ORDER BY newsdate DESC,newsid ASC');
                    $odate='';
                    $displayed_days=0;
                    $news_count = db_num_rows($result);
                    $news_out = '';
                    for ($i=0;$i<$news_count;$i++)
                    {
                        $news_row = db_fetch_assoc($result);
                        if ($odate!=$news_row['newsdate'])
                        {
                            if($i>70 && $displayed_days>1)
                            {
                                //$news_out.='`0Admin-Info: '.$i.' Einträge';
                                break;
                            }
                            //$news_out.='`n`b`@'.strftime('%A, %e. %B',strtotime($news_row['newsdate'])).'`b`n';
                            $news_out.='`n`@`b'.$this->dateToGerman(strtotime($news_row['newsdate'])).'`b`n';
                            $odate=$news_row['newsdate'];
                            $displayed_days++;
                        }
                        $news_out .= $news_row['newstext'].'`n';
                    }
                    $p_news .= $news_out.'`0';
                    $this->arr_profilVal['news'] = $p_news;
                }

                break;


        }
    }

    public static function replace_names($treffer)
    {
        $split = explode('|',mb_substr($treffer[1],1,-1));

        $name = trim($split[0]);

        //if(trim(str_replace('_','',$name)) != '')
        {
            $t = db_get("SELECT acctid,name FROM accounts WHERE login LIKE '".db_real_escape_string($name)."' LIMIT 1");
        }

        if(isset($t['acctid']))
        {
            return '<a href="steckbrief.php?id='.$t['acctid'].'">`&'.( ($split[1] != '') ? $split[1] : $t['name'] ).'</a>';
        }

        return $treffer[0];
    }

    public static function replace_cnames($treffer)
    {
        $name = trim(mb_substr($treffer[1],1,-1));

        //if(trim(str_replace('_','',$name)) != '')
        {
            $t = db_get("SELECT a.acctid,a.login,e.cname FROM accounts AS a JOIN account_extra_info AS e ON a.acctid=e.acctid
            WHERE login LIKE '".db_real_escape_string($name)."' LIMIT 1");
        }

        if(isset($t['acctid']))
        {
            return '<a href="steckbrief.php?id='.$t['acctid'].'">`&'.( ($t['cname'] != '') ? $t['cname'] : $t['login'] ).'</a>';
        }

        return $treffer[0];
    }

    protected function freefields($id)
    {
        $p_left= '';
        if(isset($this->arr_bioData['arr_textfield'][$id]))
        {
            foreach ($this->arr_bioData['arr_textfield'][$id] as $textfield)
            {
                if($textfield['value'] != '')
                {

                    $split = explode(',',$textfield['value']);
                    $user = array();

                    foreach($split as $u)
                    {
                        $t = db_get("SELECT acctid,name FROM accounts WHERE login LIKE '".db_real_escape_string(trim($u))."' LIMIT 1");

                        if(isset($t['acctid']))
                        {
                            $user[] = $t;
                        }
                    }

                    if(count($user) > 0)
                    {
                        $textfield['value'] = '';

                        for($i=0; $i < count($user); $i++)
                        {
                            $add = '';
                            if($i > 0) $add = ', ';
                            if($i == (count($user)-1)) $add = ' und ';
                            if(count($user)==1) $add = '';
                            $textfield['value'] .= $add.'<a href="steckbrief.php?id='.$user[$i]['acctid'].'">`&'.$user[$i]['name'].'</a>';
                        }
                    }

                }

                if($textfield['title'] != '' && $textfield['value'] != '')
                {
                    $p_left .= '`0'. $textfield['title'] .': '.$this->vc . $textfield['value'] .'`n`0';
                }
                else if($textfield['title'] == '' && $textfield['value'] != '')
                {
                    $p_left .= '`0'.$this->vc . $textfield['value'] .'`n`0';
                }
                else if($textfield['title'] != '' && $textfield['value'] == '')
                {
                    $p_left .= '`n'.$this->hc.'`b'.$textfield['title'].':`b`n`0';
                }
            }
        }

        return $p_left;
    }

    protected function count_freefields($id)
    {
        $p_left= 0;
        if(isset($this->arr_bioData['arr_textfield'][$id]))
        {
            foreach ($this->arr_bioData['arr_textfield'][$id] as $textfield)
            {
                if($textfield['title'] != '' && $textfield['value'] != '')
                {
                    $p_left++;
                }
                else if($textfield['title'] == '' && $textfield['value'] != '')
                {
                    $p_left++;
                }
                else
                {
                    return $p_left;
                }
            }
        }
        return $p_left;
    }

    protected function getInfoPage($nolevel=0)
    {
        global $session,$profs,$jobs,$dg_funcs,$g_arr_prof_jobs;

        if(isset($_GET['sl'])) $nolevel=0;
        if(isset($_GET['st'])) $nolevel=1;

        $p_player = '<center><table id="infotable" style="margin:auto;">
					<colgroup>
						<col width="260">
						<col width="220">
						<col width="260">
					</colgroup>
					<tr><td colspan="3">';


        $p_player .= $this->freefields('Über Steckbrief');

        $p_player .= $this->arr_bioData['row']['name'].'`0';
        if ($this->arr_bioData['row']['marks'] >= CHOSEN_FULL && !$nolevel) {
            $p_player .= ', '.($this->arr_bioData['row']['sex']?'die':'der').' Auserwählte`0';
        }

        $p_player .= '&nbsp;<a href="mail.php?op=write&amp;to='.$this->int_acctid.'" target="_blank"
            onClick="'.popup('mail.php?op=write&to='.$this->int_acctid).';return false;">
            <img title="Mail schreiben" src="./images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>';

        $p_player .= '&nbsp;<a href="mail.php?op=neuerkontakt2&id='.$this->int_acctid.'" target="_blank"
            onClick="'.popup('mail.php?op=neuerkontakt2&id='.$this->int_acctid).';return false;"><img src="./images/icons/plus.gif"
            width="16" height="16" title="Zum Adressbuch hinzufügen" alt="Adden" border="0"></a>';

        if($nolevel || isset($_GET['sl']))
        {
            $p_player .= '&nbsp;<img style="" src="./bathorys_module/quest/images/icons/'.(isset($_GET['sl'])==false?'lvl':'rp').'.png"
            title="'.(isset($_GET['sl'])?'Level-Elemente ausblenden':'Level-Elemente anzeigen').'"
            onClick="location.href=\'steckbrief.php?id='.$this->int_acctid.(isset($_GET['sl'])==false?'&amp;sl=1':'').'\'" />';
        } else   if(!$nolevel  || isset($_GET['st']))

        {
            $p_player .= '&nbsp;<img style=""  src="./bathorys_module/quest/images/icons/'.(isset($_GET['st'])==false?'rp':'lvl').'.png"
            title="'.(isset($_GET['st'])?'Level-Elemente anzeigen':'Level-Elemente ausblenden').'"
            onClick="location.href=\'steckbrief.php?id='.$this->int_acctid.(isset($_GET['st'])==false?'&amp;st=1':'').'\'" />';
        }

        $p_player .= '`n';
        if($this->arr_bioData['row']['profession'])
        {
            $prof = &$profs[$this->arr_bioData['row']['profession']];
            if($prof[2])
            {
                $p_player .= '`n`b'.$this->hc.'Amt: ';
                $p_player .= $prof[3].$prof[$this->arr_bioData['row']['sex']];
                $p_player .= '`0`b`n';
            }
        }

        if(!$nolevel)
        {
        if($this->arr_bioData['row']['imprisoned']>0) { $p_player .= '`n(Im Kerker für '.($this->arr_bioData['row']['imprisoned']).' Tage.)'; }
        if($this->arr_bioData['row']['imprisoned']<0) { $p_player .= '`n(Auf unbestimmte Zeit im Kerker.)';}
        if($this->arr_bioData['row']['activated'] == USER_ACTIVATED_MUTE) { $p_player .= '`n(Von einem Mod geknebelt.)'; }
        if($this->arr_bioData['extra']['html_locked'] == 1) { $p_player .= '`n(HTML gesperrt.)'; }
        }

        $picture_special_include = false;
        //by Salator: Ich weiß nicht ob es nur mit dem Titel mal Probleme gab, nur mit Komplettname wird das Bild bei Sternchenträgern nicht ersetzt.
        if ($this->arr_bioData['row']['title']=='Flauschihase' || mb_substr($this->arr_bioData['row']['name'],0,13)=='Flauschihase ')
        {
            $p_img .= './images/fluffy.jpg';
        }
        elseif ($this->arr_bioData['row']['title']=='`2Kröte`0' || mb_substr($this->arr_bioData['row']['name'],0,10)=='`2Kröte`0 ')
        {
            $p_img .= './images/toad.jpg';
        }
        elseif ($this->arr_bioData['row']['title']=='`2Frosch`0' || mb_substr($this->arr_bioData['row']['name'],0,11)=='`2Frosch`0 ')
        {
            $p_img .= './images/kermit.jpg';
        }
        elseif (getsetting("avatare",0)){
            $p_img = CPicture::get_image_path($this->int_acctid,'p',1);
            $picture_special_include = true;
        }

        if (($p_img) && $picture_special_include)
        {
            $p_img = '[PIC=p]';
            CPicture::replace_pic_tags($p_img, $this->int_acctid);
        }
        else
        {
            $p_img = $this->getImage($p_img,strip_appoencode($this->arr_bioData['row']['name'],3),300, 300);
        }

        //LINKE SEITE


        $p_left .= '`n`b'.$this->hc.'Allgemeines:`0`b`n';
        $p_left .= '`0Rufname: '.$this->vc
            .(empty($this->arr_bioData['extra']['cname']) ? $this->arr_bioData['row']['login']:$this->arr_bioData['extra']['cname'])
            .' <img src="./images/' . ($this->arr_bioData['row']['sex']?'female':'male') . '.gif">`n`0';


        if(!$nolevel)
        {
        $p_left .= '`0Titel: '.$this->vc.$this->arr_bioData['row']['title'].'`n`0';
        }

        if(!$nolevel)
        {
        if (getsetting('activategamedate','0')==1 && $this->arr_bioData['extra']['birthday']!='') { $p_left .= 'Ankunft: '.$this->vc.getgamedate($this->arr_bioData['extra']['birthday']).'`n`0'; }
        }

        $p_left .= 'Zuletzt gesehen: '.$this->vc.$this->arr_bioData['laston'].'`n`0';

        $p_left .= $this->freefields('Allgemeines');

        $p_left .= '`n'.$this->hc.'`bInteressantes:`b`n`0';


        if(!$nolevel)
        {
        if ($this->arr_bioData['row']['dragonkills']>0) { $p_left .= 'Heldentat' . ($this->arr_bioData['row']['dragonkills']>1?'en':'') . ': '.$this->vc.$this->arr_bioData['row']['dragonkills'].'`n`0';}

        $p_left .= 'Level: '.$this->vc.$this->arr_bioData['row']['level'].'`n`0';
        }

        $rprace = trim($this->arr_bioData['prefs']['rprace']);
        if($rprace!='') $p_left .= 'Rasse: '.$this->vc.$rprace.'`n`0';
        else $p_left .= 'Rasse: '.$this->vc.$this->arr_bioData['row']['racename'].'`n`0';

        if(mb_strlen($this->arr_bioData['extra']['charclass']) > 0)
        {
            $p_left .= 'Klasse: '.$this->vc.closetags($this->arr_bioData['extra']['charclass'],'`b`c`i').'`n`0';
        }

        // Geburtsdatum by talion
        $str_birthdate = '';
        if(!empty($this->arr_bioData['extra']['char_birthdate']) && !$this->arr_bioData['prefs']['no_alter'])
        {
            $str_birthdate = 'unbekannt';
            $arr_date_info = explode('-',getsetting('gamedate','0000-00-00'));
            $arr_birthdate_info = explode(' ',$this->arr_bioData['extra']['char_birthdate']);
            // Abstand in Jahren
            $int_age = $arr_date_info[0] - $arr_birthdate_info[0];
            // Wenn valide
            if($int_age >= 0 && sizeof($arr_birthdate_info) == 3)
            {
                // Wenn valide (Monat <= aktueller Monat, Tag <= aktueller Tag)
                if($int_age > 0 || ($arr_birthdate_info[1] <= $arr_date_info[1] && $arr_birthdate_info[2] <= $arr_date_info[2]))
                {
                    // Wenn noch keinen Geburtstag dieses Jahr
                    if(($arr_birthdate_info[1] > $arr_date_info[1]) || ($arr_birthdate_info[1] == $arr_date_info[1] && $arr_birthdate_info[2] > $arr_date_info[2]))
                    {
                        $int_age--;
                    }
                }
                // Geburtsdatum formatieren
                $str_birthdate = getgamedate($arr_birthdate_info[0].'-'.$arr_birthdate_info[1].'-'.$arr_birthdate_info[2]);
                $str_birthdate_only = $str_birthdate;
                $str_birthdate .= '`n(`i'.$int_age.' Jahr'.($int_age != 1 ?'e':'').'`i)`0';

            }
            if($this->arr_bioData['prefs']['birthdate_disp']==1)
            {
                $p_left .= 'Alter: '.$this->vc.$int_age.' Jahr'.($int_age != 1 ?'e':'').'`n`0';
            }
            else if($this->arr_bioData['prefs']['birthdate_disp']==2)
            {
                $p_left .= 'Geburtsdatum: '.$this->vc.$str_birthdate_only.'`n`0';
            }
            else
            {
                $p_left .= 'Geburtsdatum: '.$this->vc.$str_birthdate.'`n`0';
            }
        }
        // END geburtsdatum


        $rpspec = trim($this->arr_bioData['prefs']['rpspec']);
        if($rpspec!='') $p_left .= 'Spezialgebiet: '.$this->vc.$rpspec.'`n`0';
        else $p_left .= 'Spezialgebiet: '.$this->vc.$this->arr_bioData['skill'][$this->arr_bioData['row']['specialty']].'`n`0';


        // Freitext-Felder
        $p_left .= $this->freefields('Interessantes');

        $rptier = trim($this->arr_bioData['prefs']['rptier']);

        $rphaus = trim($this->arr_bioData['prefs']['rphaus']);

        if($nolevel && (mb_strlen($this->arr_bioData['row']['kleidung']) > 0 || $this->count_freefields('Besitz') > 0 || $rptier != '' || $rphaus != '') )
        {
        $p_left .= '`n'.$this->hc.'`bBesitz:`b`n`0';
        }
        else if(!$nolevel)
        {
            $p_left .= '`n'.$this->hc.'`bBesitz:`b`n`0';
        }

        //hat ein tier


        if($rptier!='')
        {
            $p_left .= 'Tier: '.$this->vc.$rptier.'`n`0';
        }
        else
        {
            if($this->arr_bioData['row']['hashorse'] && !$nolevel)
            {
                if ($this->arr_bioData['extra']['hasxmount']==1)
                {
                    $p_left .= 'Tier: '.$this->arr_bioData['extra']['xmountname'].' `&('.closetags($this->arr_bioData['mount']['mountname'],'`b`c`i').'`&)`n`0';
                }
                else
                {
                    $p_left .= 'Tier: '.$this->vc.closetags($this->arr_bioData['mount']['mountname'],'`b`c`i').'`n`0';
                }
            }
        }



        if($rphaus != '' && $nolevel)
        {
            $p_left .= 'Haus: '.$this->vc.$rphaus.'`n`0';
        }

        //hat ein haus
        if ($this->arr_bioData['row']['house'] && !$nolevel)
        {
            $p_left .= 'Haus: '.$this->vc.closetags($this->arr_bioData['house']['housename'],'`b`c`i').'`n'.$this->vc.'('.get_house_state($this->arr_bioData['house']['status'],$this->arr_bioData['house']['build_state'],false).','.$this->vc.' Nr. '.$this->arr_bioData['row']['house'].')`n`0';
        }

        if(!$nolevel)
        {
        $p_left .= 'Waffe: '.$this->vc.closetags($this->arr_bioData['row']['weapon'],'`b`i`c').'`n`0';
        $p_left .= 'Rüstung: '.$this->vc.closetags($this->arr_bioData['row']['armor'],'`b`i`c').'`n`0';
        }

        if(mb_strlen($this->arr_bioData['row']['kleidung']) > 0)
        {
            $desc_row = db_get("SELECT description FROM items WHERE name LIKE '". db_real_escape_string($this->arr_bioData['row']['kleidung']) ."' AND owner='". $this->int_acctid ."'  LIMIT 1");
            $p_left .= jslib_hint('Bekleidung: '.$this->vc.closetags($this->arr_bioData['row']['kleidung'],'`b`i`c').'`n`0', addslashes(strip_appoencode($desc_row['description'],2)),'lotgdHintSweet');
            //$p_left .= 'Bekleidung: '.$this->vc.closetags($this->arr_bioData['row']['kleidung'],'`b`i`c').'`n`0';
        }

        if(!$nolevel)
        {
        //hat Tauschquest begonnen
        if ($this->arr_bioData['row']['exchangequest'])
        {
            $arr_questitems=array('keins','eine Murmel','eine Feder','Liebesgedicht','eine Rose','Räuchermischung','ein Rubin','Weihwasser','Donneraxt','Mithril-Erz','eine Flöte','Handspiegel','keins von Wert','guter Met','nordischer Honig','frisches Brot','Wanderkarte','bunte Steine','Zaubertafel','große Muschel','Perlenkette','Parfum','Auftrag des Geistes','Drachenzahn','Nebelflasche','Lolli','alte Brosche','Juwelen-Brosche','Leuchtende Brosche','Brosche der alten Völker',color_from_name('Brosche der alten Völker',$this->arr_bioData['extra']['ctitle'].$this->arr_bioData['extra']['cname']));
            $p_left .= 'Spezial: '.$this->vc.closetags($arr_questitems[$this->arr_bioData['row']['exchangequest']],'`b`c`i').'`n`0';
        }
        }

        $p_left .= $this->freefields('Besitz');

        //RECHTE SEITE
        if( $this->arr_bioData['row']['guildid'] )
        {
            $p_right .= '`n`b'.$this->hc.'Gilde:`0`b';
            if($this->arr_bioData['row']['guildfunc'] == DG_FUNC_APPLICANT)
            {
                $p_right .= '`nBewirbt sich gerade.';
            }
            else
            {
                $guild = dg_load_guild($this->arr_bioData['row']['guildid'],array('name','ranks','guildid','founder'));

                $p_right .= '`n'.$this->vc.$guild['name'];
                $p_right .= '`n`0Rang: '.$this->vc.$guild['ranks'][$this->arr_bioData['row']['guildrank']][$this->arr_bioData['row']['sex']];
                $p_right .= '`n`0Posten: '.$this->vc.$dg_funcs[$this->arr_bioData['row']['guildfunc']][$this->arr_bioData['row']['sex']];
                if($guild['founder'] == $this->arr_bioData['row']['acctid'])
                {
                    $p_right .= ' `i(Gründer'.($this->arr_bioData['row']['sex']?'in':'').')`i';
                }
            }
            $p_right .= '`n';
        }

        $orteprefs = utf8_unserialize($this->arr_bioData['extra']['ext_bio_orte']);
        $excl_orte = '0';
        $excl_mitg = '0';

        foreach($orteprefs as $k => $v){
            if($v){
                $k = str_replace('_show','',$k);
                $sfx = mb_substr($k,0,2);
                $k = intval(str_replace($sfx,'',$k));
                if($sfx == 'o_'){
                    $excl_orte .= ','.$k;
                }else if($sfx == 'm_'){
                    $excl_mitg .= ','.$k;
                }
            }
        }

        $orte = db_get_all("SELECT p.name, w.name AS wname FROM rp_worlds_places AS p
                            JOIN rp_worlds AS w
                            ON w.id = p.world
                            WHERE p.acctid='".$this->int_acctid."' AND p.parent=0 AND p.id NOT IN (".$excl_orte.") ORDER BY p.id ASC");

        if(count($orte)>0  && !$this->arr_bioData['prefs']['no_orte'])
        {
            $p_right .= '`n`b'.$this->hc.'Orte:`0`b';
            foreach($orte as $m)
            {
                $p_right .= '`n'.$m['name'].' `0('.$m['wname'].'`0)`0';
            }
            $p_right .= '`n';
        }

        $mitg = db_get_all("SELECT p.name AS posname, o.name AS ortname, m.acctid,m.rportid FROM rp_worlds_members AS m
                                JOIN rp_worlds_positions AS p
                                ON p.id=m.position
                                JOIN rp_worlds_places AS o
                                ON o.id=m.rportid
                                WHERE m.acctid='".$this->int_acctid."'  AND m.id NOT IN (".$excl_mitg.")
                                ORDER BY m.id ASC
                           ");

        if(count($mitg)>0  && !$this->arr_bioData['prefs']['no_mitg'])
        {
            $p_right .= '`n`b'.$this->hc.'Mitgliedschaften:`0`b';
            foreach($mitg as $m)
            {
                $p_right .= '`n'.$m['ortname'].'`0 ('.$m['posname'].'`0)`0';
            }
            $p_right .= '`n';
        }

        if(!$nolevel)
        {

        if ($this->arr_bioData['row']['marks']>0 && (!$this->arr_bioData['p_ei']['marks'] || $this->arr_bioData['prefs']['aus_male'] ) )
        {
            $p_right .= '`n`b'.$this->hc.'Trägt:`b`n ';
            if ($this->arr_bioData['marks2']['spirit']) 	$p_right .= '`tMal des Geistes`n`0';
            if ($this->arr_bioData['marks2']['water']) 	$p_right .= '`@Mal des Wassers`n`0';
            if ($this->arr_bioData['marks2']['fire']) 	$p_right .= '`4Mal des Feuers`n`0';
            if ($this->arr_bioData['marks2']['air']) 		$p_right .= '`9Mal der Luft`n`0';
            if ($this->arr_bioData['marks2']['earth']) 	$p_right .= '`^Mal der Erde`n`0';
            if ($this->arr_bioData['row']['marks'] >= CHOSEN_BLOODGOD) $p_right .= '`n`4Hat einen Pakt mit dem Blutgott.`n`0';
        }

        }

        if (

            (trim($this->arr_bioData['row']['marriedto']) != 0 && ($this->arr_bioData['row']['charisma']==4294967295 || $this->arr_bioData['row']['charisma']==999 || $this->arr_bioData['row']['marriedto']==4294967295)  )
            ||	trim($this->arr_bioData['extra']['together_with']) != ''
            ||	trim($this->arr_bioData['prefs']['v_with']) != ''
            ||	trim($this->arr_bioData['prefs']['h_with']) != ''
            ||	trim($this->arr_bioData['prefs']['t_with']) != ''
            ||	trim($this->arr_bioData['prefs']['b_with'] ) != ''
            ||	trim($this->arr_bioData['prefs']['t2_with']) != ''
            || $this->count_freefields('Beziehungen')  > 0
        )
        {
            $p_right .= '`n'.$this->hc.'`bBeziehungen:`b`n`0';
        }

        $married = false;
        $married_name = '';
        if ($this->arr_bioData['row']['marriedto'])
        {
            if ($this->arr_bioData['row']['marriedto']==4294967295)
            {
                $p_right .= '`0Verheiratet mit: '.$this->vc.($this->arr_bioData['row']['sex']?'Seth':'Violet').'`0`n';
            }
            elseif ($this->arr_bioData['row']['charisma']==4294967295 || $this->arr_bioData['row']['charisma']==999)
            {
                $sql = "SELECT name,acctid,login FROM accounts WHERE acctid='".$this->arr_bioData['row']['marriedto']."'";
                $result = db_query($sql);
                $partner = db_fetch_assoc($result);
                if(!empty($partner['acctid'])) {
                    $p_right .= (($this->arr_bioData['row']['charisma']==999)?'Verlobt':'Verheiratet').' mit: <a href="steckbrief.php?id='.$partner['acctid'].'">'.$this->vc.$partner['name'].'</a>`n`0';
                    $married = true;
                    $married_name = mb_strtolower($partner['login']);
                }
            }
        }


        $together = mb_strtolower(strip_appoencode($this->arr_bioData['extra']['together_with']));
        if( !empty($together) && (!$married || $married_name!=$together) )
        {
            $sql = "SELECT acc.name, acc.acctid, aei.together_with, aei.together_yesno FROM account_extra_info aei
					JOIN accounts acc ON acc.acctid=aei.acctid
					WHERE acc.login = '".db_real_escape_string($together)."'";
            $result = db_query($sql);
            $partner = db_fetch_assoc($result);

            if(!empty($partner['acctid']))
            {
                if( mb_strtolower(strip_appoencode($partner['together_with'])) == mb_strtolower($this->arr_bioData['row']['login']) && ($partner['together_yesno'] && $this->arr_bioData['prefs']['together_yesno']))
                {
                    if( $married )
                    {
                        $str_together = 'Affäre mit';
                    }
                    else
                    {
                        $str_together = 'Zusammen mit';
                    }
                }
                else
                {
                    $str_together = 'Verliebt in';
                }
                $p_right .= $str_together.': <a href="steckbrief.php?id='.$partner['acctid'].'">'.$this->vc.$partner['name'].'</a>`n`0';
            }
            else
            {
                if(!$this->arr_bioData['prefs']['together_yesno'])
                {
                    $str_together = 'Verliebt in';
                }
                else if($married)
                {
                    $str_together = 'Affäre mit';
                }
                else
                {
                    $str_together = 'Zusammen mit';
                }

                $p_right .= $str_together.': '.$this->vc.strip_appoencode($this->arr_bioData['extra']['together_with'],2).'`n`0';
            }
        }

        $gef = array('v_with' => 'Verbunden mit','h_with' => 'Hass auf','t_with' => 'Trauert um','b_with' => 'Besessen von','t2_with' => 'Träumt von');

        foreach($gef as $k => $v)
        {
            $together = trim(mb_strtolower(strip_appoencode($this->arr_bioData['prefs'][$k])));
            if( !empty($together) && $together != '' )
            {
                $sql = "SELECT acc.name, acc.acctid, aei.together_with, aei.together_yesno FROM account_extra_info aei
						JOIN accounts acc ON acc.acctid=aei.acctid
						WHERE acc.login = '".db_real_escape_string($together)."'";
                $result = db_query($sql);
                $partner = db_fetch_assoc($result);

                $str_together = $v;

                if(!empty($partner['acctid']))
                {
                    $p_right .= $str_together.': <a href="steckbrief.php?id='.$partner['acctid'].'">'.$this->vc.$partner['name'].'</a>`n`0';
                }
                else
                {
                    $p_right .= $str_together.': '.$this->vc.strip_appoencode($this->arr_bioData['prefs'][$k],2).'`n`0';
                }
            }
        }

        $p_right .= $this->freefields('Beziehungen');

        if(!$nolevel || $this->count_freefields('Sonstiges') > 0)
        {

        $p_right .= '`n'.$this->hc.'`bSonstiges:`b`n`0';

        }

        if(!$nolevel)
        {
        $p_right .= 'Bester Angriff: '.$this->vc.$this->arr_bioData['row']['punch'].'`n`0';
        $p_right .='Alter seit letzter Heldentat: '.$this->vc.$this->arr_bioData['row']['age'].' '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage').'`n`0';
        $p_right .='Wiedererweckt: '.$this->vc.$this->arr_bioData['row']['resurrections'].'x`n`0';
        //if((bool)getsetting('symp_active','0')) {
        //	output("`^Sympathie: `@$this->arr_bioData['extra'][sympathy]`n");
        //}

        //Runenrang
        $kr = array();
        $kr = utf8_unserialize($this->arr_bioData['extra']['runes_ident']);


        $kr = runes_only_known($kr);

        $p_right .='Runenrang: '.$this->vc.runes_get_rank(count($kr), $this->arr_bioData['row']['sex']).'`n`0';
        $p_right .='Sympathie: '.$this->vc.$this->arr_bioData['symp'].'`n`0';

        //Beruf ausgeben
        if($this->arr_bioData['extra']['job'] > 0)
        {
            $this_job = &$jobs[$this->arr_bioData['extra']['job']];
            $job_info = $g_arr_prof_jobs[$this->arr_bioData['extra']['job']];
            $p_right .='Beruf: '.$this->vc.$this_job[$this->arr_bioData['row']['sex']].'`n`0';
        }


        }

        $p_right .= $this->freefields('Sonstiges');

        if(!$nolevel)
        {
        if($this->arr_bioData['extra']['quests_solved']>0 && !$this->arr_bioData['prefs']['no_quest'] && !$nolevel)  //
        {
            $p_right .= '`n'.$this->hc.'`bQuests:`b`n`0';
            $p_right .= '<span style="margin-right:6px;">Erfüllt:</span> '.$this->vc.$this->arr_bioData['extra']['quests_solved'].' | ⌛ '.round($this->arr_bioData['extra']['quests_time']/getsetting('dayparts','1'),2).' Tag'.( (round($this->arr_bioData['extra']['quests_time']/getsetting('dayparts','1'),2) != 1) ? 'e' : '').'`n`0';
            $p_right .= 'Sterne: '.$this->vc.$this->arr_bioData['extra']['quests_sterne'].' | Ø '.round($this->arr_bioData['extra']['quests_sterne']/$this->arr_bioData['extra']['quests_solved'],2).'☆`n`0';
        }
        }


        $charmsteps = array( 'sehr hässlich' => 30, 'hässlich' => 100, 'unschön' => 150, 'durchschnittlich schön' => 300, 'schön' => 500, 'sehr schön' => 750, 'unbeschreiblich schön' => 1000, 'Dorfschönheit' => 6000);
        $c_i = 0;
        $c_name = $c_val = 0;
        $c_nxtname = '';
        //note by Salator: Ich weiß nicht warum, aber mit der auf Atrahor installierten PHP-Version müssen die Zeilen mit next weg
        foreach( $charmsteps as $k => $v )
        {
            $c_i++;
            if( $v >= $this->arr_bioData['row']['charm'] )
            {
                $c_name = $k;
                $c_val  = $v;

                if(sizeof($charmsteps) > $c_i)
                {
                    //next($charmsteps);
                    $c_nxtname = ' &raquo; '.key(array_slice($charmsteps, $c_i, 1, true));
                    //$c_nxtname = ' &raquo; '.key($charmsteps);
                }

                break;
            }

        }

        if( !$c_val ){
            $max_charm = db_fetch_assoc(db_query('SELECT acctid,charm FROM accounts WHERE sex='.$this->arr_bioData['row']['sex'].' ORDER BY charm DESC LIMIT 1'));
            //$c_val = $max_charm['charm'];
            if( $max_charm['acctid'] == $this->int_acctid )
            {
                $c_name = '`n`b`i'.($this->arr_bioData['row']['sex'] ? 'Die' : 'Der').' Schönste!`b`i';
                $c_val  = 0;
            }
            else
            {
                $i = count($charmsteps)-1;
                $c_name = array_keys($charmsteps);
                $c_name = $c_name[ $i ];
                $c_val 	= $charmsteps[ $c_name ];
            }
        }


        $p_player .= '		</td>
						</tr>
						<tr>
							<td valign="top">';
        $p_player .=  $p_left;
        $p_player .= '		</td>
							<td width="220" valign="top" align="left" > <div style="padding-right: 40px; padding-top:0px;">';


        $p_player .= $this->freefields('Über Ava');
        $p_player .= '`n`c'.$p_img.'`c`n`n';

        $p_player .= $this->freefields('Unter Ava');

        if(!$nolevel)
        {
        $p_player .= '`c'.$this->hc.'Ansehen:&nbsp;'.grafbar(100, ($this->arr_bioData['row']['reputation']+50),200,12,'',($this->extBioColor($this->arr_bioData['p_ei']['colors'], 'ansehen_a', '')==''), $this->extBioColor($this->arr_bioData['p_ei']['colors'], 'ansehen_a', '')).'`n';
        $p_player .= ''.$this->hc.'Schönheit: ';
        if( $c_val ){
            $p_player .= grafbar($c_val, $this->arr_bioData['row']['charm'],200,12,'',($this->extBioColor($this->arr_bioData['p_ei']['colors'], 'schonheit_a', '')==''), $this->extBioColor($this->arr_bioData['p_ei']['colors'], 'schonheit_a', ''));
        }
        $p_player .= ''.$this->hc.$c_name.$c_nxtname.'`c';
        }

        $p_player .= $this->freefields('Unter Schönheit');

        $p_player .= '		`0</div></td>
							<td valign="top">';
        $p_player .= $p_right;
        $p_player .= '		</td>
						</tr>
						<tr><td colspan="3">&nbsp;</td></tr>
						<tr>
							<td colspan="3">';


        if(!$nolevel)
        {
        if ($this->arr_bioData['row']['pvpflag']=='5013-10-06 00:42:00') {$p_player .= '<span style="color: '.$this->extBioColor($this->arr_bioData['p_ei']['colors'], 'body_text', '00B0B0').'">`iSteht unter besonderem Schutz`i</span>';}
        }

        if ((isset($this->arr_bioData['disciple']) && isset($this->arr_bioData['disciple']['state']) && $this->arr_bioData['disciple']['state']>0) && ! ($this->arr_bioData['prefs']['no_knappe'] && $nolevel))
        {
            $p_player .= '`n'.$this->arr_bioData['row']['name'].'<span style="color: '.$this->extBioColor($this->arr_bioData['p_ei']['colors'], 'body_text', '00B0B0').'"> wird begleitet von '.($this->arr_bioData['row']['sex']?'ihrem':'seinem').' '.get_disciple_stat($this->arr_bioData['disciple']['state']).' Knappen `3'.$this->arr_bioData['disciple']['name'].'`0.</span>`n`0';
        }

        $p_player .= $this->freefields('Unter Steckbrief');

        $p_player .= '		</td>
						</tr>
					</table></center>';



        return $p_player;
    }

    protected function cleanSpan($str)
    {
        if(mb_strpos($str,"</span>") !== false)
        {
            $str = '`0'.str_replace("</span>","`0</span>",$str);
            $str = str_replace("`0`0","`0",$str);
        }
        return $str;
    }
}
?>
