<?php
//by bathory

class CUsermenu
{
    public static function make($menu)
    {
        $ret = '<ul id="menu" style="display: none;">';
        if(isset($menu['header'])){
            $ret .= '<li class="ui-state-disabled" style="text-align:center;">'.$menu['header'].'</li>';
        }
        if(isset($menu['data']) && is_array($menu['data'])){
            foreach ($menu['data'] as $k => $v) {
                if(!isset($v['condition']) || $v['condition']){
                    if(isset($v['url'])){
                        $text = CRPChat::popup((isset($v['icon']) ? self::icon($v['icon']) : '').$k,$v['url']);
                    }else if(isset($v['url_a'])){
                        $text = '<a href="'.$v['url_a'].'">'.(isset($v['icon']) ? self::icon($v['icon']) : '').$k.'</a>';
                    }else{
                        $add = '';
                        if(isset($v['httpreq'])){
                            $add = ' data-httpreq="true"
                                     data-httpreq-link="'.utf8_htmlentities($v['httpreq']['link']).'"
                                     '.( isset($v['httpreq']['msg']['text']) ? 'data-httpreq-msg="'.utf8_htmlentities('<span class="ui-icon ui-icon-'.$v['httpreq']['msg']['icon'].'" style="float: left; margin: 0 7px 50px 0;"></span>'.$v['httpreq']['msg']['text']).'" ' : '').'
                                     ';
                        }
                        $text = '<a href="#"'.$add.'>'.(isset($v['icon']) ? self::icon($v['icon']) : '').$k.'</a>';
                    }
                    $ret .= '<li>'.$text;

                    if(isset($v['submenu']) && is_array($v['submenu'])){
                        $ret .= '<ul class="subusermenu" style="width: 150px; display:none;">';
                        foreach ($v['submenu'] as $sk => $sv) {
                            if(!isset($sv['condition']) || $sv['condition']){
                                if(isset($sv['url'])){
                                    $stext = CRPChat::popup((isset($sv['icon']) ? self::icon($sv['icon']) : '').$sk,$sv['url']);
                                }
                                else if(isset($sv['url_a'])){
                                    $stext = '<a href="'.$sv['url_a'].'">'.(isset($sv['icon']) ? self::icon($sv['icon']) : '').$sk.'</a>';
                                }else{
                                    $sadd = '';
                                    if(isset($sv['httpreq'])){
                                        $sadd = ' data-httpreq="true"
                                     data-httpreq-link="'.utf8_htmlentities($sv['httpreq']['link']).'"
                                     '.( isset($sv['httpreq']['msg']['text']) ? 'data-httpreq-msg="'
                                     .utf8_htmlentities('<span class="ui-icon ui-icon-'.$sv['httpreq']['msg']['icon'].'" style="float: left; margin: 0 7px 50px 0;"></span>'
                                     .$sv['httpreq']['msg']['text']).'" ' : '').'
                                     ';
                                    }
                                    $stext = '<a href="#"'.$sadd.'>'.(isset($sv['icon']) ? self::icon($sv['icon']) : '').$sk.'</a>';
                                }
                                $ret .= '<li>'.$stext.'</li>';
                            }
                        }
                        $ret .= '</ul>';
                    }

                    $ret .= '</li>';
                }
            }
        }
        $ret .= '</ul>';
        return $ret;
    }

    /**
     * @param $icon
     * @return string
     */
    public static function icon($icon)
    {
        return "<span class='ui-icon ui-icon-".$icon."'></span>";
    }

    /**
     * @param $id
     * @return array
     */
    public static function getUserMenuArray($id,$extra='')
    {
        global $Char,$access_control;
        $xtra = explode(',',$extra);
        $self = $Char->isSelf($id);

        $data = db_get("SELECT
                                a.login, a.expedition, a.ddl_rank, a.activated, a.imprisoned, aei.biotime, aei.imgtime
                                FROM accounts AS a
                                JOIN account_extra_info AS aei
                                ON aei.acctid = a.acctid
                                WHERE a.acctid = '".intval($id)."' LIMIT 1");

        if(count($data)){
            $symp = false;
            if(!$self && ($Char->dragonkills >0 || getsetting('symp_dk_lock',1) == 0) ){
                $aei = user_get_aei('symp_given,symp_votes');
                if (($aei['symp_given'] == 0) && ($aei['symp_votes'] < getsetting('max_symp','10'))){
                    $symp = getsetting('max_symp','10') - $aei['symp_votes'];
                }
            }
            $menu = array(
                'header' => $data['login'],
                'data' => array(
                    'Zum Hohepriester!' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=hohep&id='.$id,
                        'condition' => (!$self && in_array('tempel_hp',$xtra))
                    ),
                    'Aufnehmen' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=aufnehmen&id='.$id,
                        'condition' => (!$self && in_array('tempel_auf',$xtra))
                    ),
                    'Ablehnen' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=ablehnen&id='.$id,
                        'condition' => (!$self && in_array('tempel_ab',$xtra))
                    ),
                    'Degradieren' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=hohep_deg&id='.$id,
                        'condition' => (!$self && in_array('tempel_deg',$xtra))
                    ),
                    'Entlassen' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=entlassen&id='.$id,
                        'condition' => (!$self && in_array('tempel_ent',$xtra))
                    ),


                    'Zum Hexenmeister!' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=hohep&id='.$id,
                        'condition' => (!$self && in_array('witch_hp',$xtra))
                    ),
                    'Initiieren' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=aufnehmen&id='.$id,
                        'condition' => (!$self && in_array('witch_auf',$xtra))
                    ),
                    'Ablehnen ' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=ablehnen&id='.$id,
                        'condition' => (!$self && in_array('witch_ab',$xtra))
                    ),
                    'Grad abnehmen' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=hohep_deg&id='.$id,
                        'condition' => (!$self && in_array('witch_deg',$xtra))
                    ),
                    'Verstossen' => array(
                        'icon' => 'person',
                        'url_a' => 'tempel.php?op=entlassen&id='.$id,
                        'condition' => (!$self && in_array('witch_ent',$xtra))
                    ),


                    'Angreifen' => array(
                        'icon' => 'flag',
                        'url_a' => 'pvp.php?act=attack&id='.$id,
                        'condition' => (!$self && in_array('pvp',$xtra))
                    ),
                    'Herausfordern' => array(
                        'icon' => 'flag',
                        'url_a' => 'pvparena.php?op=challenge&acctid='.$id,
                        'condition' => (!$self && in_array('arena',$xtra))
                    ),
                    'Kampf ohne Specials' => array(
                        'icon' => 'flag',
                        'url_a' => 'pvparena.php?op=challenge&nospec=1&acctid='.$id,
                        'condition' => (!$self && in_array('arenano',$xtra))
                    ),
                    'Flirten' => array(
                        'icon' => 'heart',
                        'url_a' => 'gardens.php?op=flirt&id='.$id,
                        'condition' => (!$self && in_array('flirt',$xtra))
                    ),
                    'Editieren' => array(
                        'icon' => 'pencil',
                        'url_a' => isset($xtra[1]) ? $xtra[1] : '',
                        'condition' => ($xtra[0]=='board_edit' && isset($xtra[1]))
                    ),
                    'Brieftaube schreiben' => array(
                        'icon' => 'mail-closed',
                        'url' => 'mail.php?op=write&to='.$id,
                        'condition' => !$self
                    ),
                    'Steckbrief' => array(
                        'icon' => 'person',
                        'url' => 'steckbrief.php?id='.$id
                    ),
                    'Biographie' => array(
                        'icon' => 'script',
                        'url' => 'bio.php?id='.$id,
                    ),
                    'Sympathie++ ('.$symp.')' => array(
                        'icon' => 'heart',
                        'httpreq' => array(
                            'link' => 'httpreq_chat.php?do=sympvote&id='.$id,
                            'msg' => array(
                                'icon' =>'circle-check',
                                'text' =>'Sympathiepunkt wurde erfolgreich an <b>'.$data['login'].'</b> vergeben!'
                            )
                        ),
                        'condition' => $symp
                    ),
                    'Stadtwache' => array(
                        'icon' => 'flag',
                        'condition' => ( $Char->profession==PROF_GUARD_HEAD || $Char->profession==PROF_GUARD ),
                        'submenu' => array(
                            'Einkerkern' => array(
                                'icon' => 'locked',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=kerker&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b> wurde eingekerkert!'
                                    )
                                )
                            )
                        )
                    ),
                    'Expedition' => array(
                        'icon' => 'flag',
                        'condition' => (( $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $Char->ddl_rank >= PROF_DDL_MAJOR )),
                        'submenu' => array(
                            'Einladen' => array(
                                'icon' => 'plus',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=expe_ein&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b> wurde eingeladen!'
                                    )
                                ),
                                'condition' => ( !$data['expedition'] && ( $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $Char->ddl_rank >= PROF_DDL_COLONEL )  )
                            ),
                            'Ausladen' => array(
                                'icon' => 'minus',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=expe_aus&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b> wurde ausgeladen!'
                                    )
                                ),
                                'condition' => ( $data['expedition'] && ( $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $Char->ddl_rank >= PROF_DDL_COLONEL )  )
                            ),
                            'Degradieren' => array(
                                'icon' => 'triangle-1-s',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=expe_down&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b> wurde degradiert!'
                                    )
                                ),
                                'condition' => ( $data['expedition'] && ($data['ddl_rank'] > 0) && $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $Char->ddl_rank > $data['ddl_rank']  )
                            ),
                            'Befördern' => array(
                                'icon' => 'triangle-1-n',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=expe_up&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b> wurde befördert!'
                                    )
                                ),
                                'condition' => ( $data['expedition'] && ($data['ddl_rank'] < PROF_DDL_COLONEL) && $access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) || $Char->ddl_rank > $data['ddl_rank']  )
                            )
                        )
                    ),

                    'Moderation' => array(
                        'icon' => 'gear',
                        'condition' => ( $access_control->su_check(access_control::SU_RIGHT_FIXNAVS) ||  $access_control->su_check(access_control::SU_RIGHT_MUTE) || $access_control->su_check(access_control::SU_RIGHT_PRISON) || $access_control->su_check(access_control::SU_RIGHT_LOCKBIOS)),
                        'submenu' => array(
                            'Fix Badnav' => array(
                                'icon' => 'carat-1-e',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=fix_navs&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b>s navs wurden repariert!'
                                    )
                                ),
                                'condition' => ( $access_control->su_check(access_control::SU_RIGHT_FIXNAVS) )
                            ),

                            'Knebeln' => array(
                                'icon' => 'carat-1-e',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=mute&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b> wurde geknebelt!'
                                    )
                                ),
                                'condition' => ( $data['activated'] != USER_ACTIVATED_MUTE && $access_control->su_check(access_control::SU_RIGHT_MUTE) )
                            ),

                            'Entknebeln' => array(
                                'icon' => 'carat-1-w',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=demute&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b> wurde entknebelt!'
                                    )
                                ),
                                'condition' => ( $data['activated'] == USER_ACTIVATED_MUTE && $access_control->su_check(access_control::SU_RIGHT_MUTE) )
                            ),

                            'Einkerkern' => array(
                                'icon' => 'carat-1-e',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=su_kerker&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b> wurde eingekerkert!'
                                    )
                                ),
                                'condition' => ( $data['imprisoned'] == 0 && $access_control->su_check(access_control::SU_RIGHT_PRISON) )
                            ),

                            'Entkerkern' => array(
                                'icon' => 'carat-1-w',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=su_dekerker&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b> wurde entkerkert!'
                                    )
                                ),
                                'condition' => ( $data['imprisoned'] != 0 && $access_control->su_check(access_control::SU_RIGHT_PRISON) )
                            ),

                            'Bio sperren' => array(
                                'icon' => 'carat-1-e',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=biolock&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b>s Bio und Steckbrief wurden gesperrt!'
                                    )
                                ),
                                'condition' => ( $data['biotime'] != BIO_LOCKED && $access_control->su_check(access_control::SU_RIGHT_LOCKBIOS) )
                            ),

                            'Bio entsperren' => array(
                                'icon' => 'carat-1-w',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=biounlock&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b>s Bio und Steckbrief wurden entsperrt!'
                                    )
                                ),
                                'condition' => ( $data['biotime'] == BIO_LOCKED && $access_control->su_check(access_control::SU_RIGHT_LOCKBIOS) )
                            ),

                            'Bilder sperren' => array(
                                'icon' => 'carat-1-e',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=imglock&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b>s Bilder wurden gesperrt!'
                                    )
                                ),
                                'condition' => ( $data['imgtime'] != BIO_LOCKED && $access_control->su_check(access_control::SU_RIGHT_LOCKIMG) )
                            ),

                            'Bilder entsperren' => array(
                                'icon' => 'carat-1-w',
                                'httpreq' => array(
                                    'link' => 'httpreq_chat.php?do=imgunlock&id='.$id,
                                    'msg' => array(
                                        'icon' =>'circle-check',
                                        'text' =>'<b>'.$data['login'].'</b>s Bilder wurden entsperrt!'
                                    )
                                ),
                                'condition' => ( $data['imgtime'] == BIO_LOCKED && $access_control->su_check(access_control::SU_RIGHT_LOCKIMG) )
                            ),

                        )
                    ),
                )
            );
            return $menu;
        }
        return array();
    }

}