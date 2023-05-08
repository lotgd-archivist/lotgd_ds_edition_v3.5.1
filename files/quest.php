<?php
require_once 'common.php';

(!CQuest::is_activ()) ? redirect('village.php') : false;

$id = intval($_GET['id']);

$quest_data = CQuest::get_quest($id);
if($quest_data)
{
    addnav('Quest');

    $quest_user = CQuest::get_user_quest($id);

    page_header(strip_appoencode($quest_data['questname'],3));

    $out = '`0'.get_title($quest_data['questname']);

    $start =  ($quest_user!=false) ? false : true;

    if($start)
    {
        if($_GET['op']=='take')
        {
            $out .= '`n`n`@Die Quest wurde deinem Buch hinzugefügt!`0`n`n';
            CQuest::make_effects($quest_data['implode_start_effekt']);
            CQuest::open_user_quest($id);

            if ($Char->alive==0)
            {
                addnav('Zu den Schatten','shades.php');
            }
            else
            {
                addnav($quest_data['ortname'],$quest_data['link']);
            }
        }
        else
        {
            $stars = '';
            $nostars = '';
            for($i=0;$i<$quest_data['dificulty'];$i++) $stars .= '☆';
            for($i=0;$i<(10-$quest_data['dificulty']);$i++) $nostars .= '☆';

            $rest = ($quest_data['verfall']==0) ? '`@Unbegrenzt' : '`t'.$quest_data['verfall'].' Tage';


            $out .= '
            <div style="margin:auto; margin-top: 6px; padding-top: 6px; width:750px;">
            <div style="padding:8px; border-bottom: #aa7800 3px solid; width: 95%; margin: auto; margin-bottom: 5px;clear:both; min-height:60px;">
                          <div style="padding:2px; border-bottom: #676767 1px solid; width: 100%; margin: auto; margin-bottom: 3px;clear:both;">
                          '.$quest_data['questname'].' <span style="color:#676767;float:right;">'.$nostars.'</span><span style="color:#F7E117;float:right;">'.$stars.'</span>
                          </div>
                          <div style="clear:both; margin-top:15px; margin_bottom::15px;">
                          <p style="float:right; width:180px; border-left:1px dotted #676767; padding-left:4px; font-size:13px;">Zeit: '.$rest.'</p>
                          <p style="width:520px;font-size:13px;">'.words_by_sex($quest_data['start_out']).'</p>
                          </div>
                          '.CQuest::make_belohnung_div($quest_data,true,false,true).'
                   </div><br /><br /><br />';



            addnav('`@Annehmen`0','quest.php?id='.$id.'&op=take');
            addnav('`4Ablehnen`0',$quest_data['link']);
        }
    }
    else
    {
        //beendet?
        if($quest_user['step'] == CQuest::count_steps($id) && CQuest::check_bedingungen($quest_data['implode_belohnung_bedingung']))
        {
            $stars = '';
            $nostars = '';
            for($i=0;$i<$quest_data['dificulty'];$i++) $stars .= '☆';
            for($i=0;$i<(10-$quest_data['dificulty']);$i++) $nostars .= '☆';

            $rest = '`t'.round($quest_user['age']/getsetting('dayparts','1'),2).'`0 Tage';


            $out .= '
            <div style="margin:auto; margin-top: 6px; padding-top: 6px; width:750px;">
            <div style="padding:8px; border-bottom: #aa7800 3px solid; width: 95%; margin: auto; margin-bottom: 5px;clear:both; min-height:60px;">
                          <div style="padding:2px; border-bottom: #676767 1px solid; width: 100%; margin: auto; margin-bottom: 3px;clear:both;">
                          '.$quest_data['questname'].' <span style="color:#676767;float:right;">'.$nostars.'</span><span style="color:#F7E117;float:right;">'.$stars.'</span>
                          </div>
                          <div style="clear:both; margin-top:15px; margin_bottom::15px;">
                          <p style="float:right; width:180px; border-left:1px dotted #676767; padding-left:4px; font-size:13px;">Zeit gebraucht: '.$rest.'</p>
                          <p style="width:520px;font-size:13px;">'.words_by_sex($quest_data['end_out']).'</p>
                          </div>
                          '.CQuest::make_belohnung_div($quest_data).'
                   </div><br /><br /><br />';

            CQuest::make_effects($quest_data['implode_end_effekt']);
            CQuest::give_belohnung($quest_data);
            CQuest::close_user_quest($id);
        }
        else
        {
            $stars = '';
            $nostars = '';
            for($i=0;$i<$quest_data['dificulty'];$i++) $stars .= '☆';
            for($i=0;$i<(10-$quest_data['dificulty']);$i++) $nostars .= '☆';

            $rest = '`@Unbegrenzt';
            if($quest_data['verfall']>0) $rest =  ($quest_data['verfall'] - round($quest_user['age']/getsetting('dayparts','1'),2)).' Tag'.( (($quest_data['verfall'] - round($quest_user['age']/getsetting('dayparts','1'),2))==1) ? '' : 'e' );
            $rest = 'Restzeit: '.( (($quest_data['verfall'] - round($quest_user['age']/getsetting('dayparts','1'),2))<5) ? '`$' : '`@' ).$rest.'`0';


            $out .= '
            <div style="margin:auto; margin-top: 6px; padding-top: 6px; width:750px;">
            <div style="padding:8px; border-bottom: #aa7800 3px solid; width: 95%; margin: auto; margin-bottom: 5px;clear:both; min-height:60px;">
                          <div style="padding:2px; border-bottom: #676767 1px solid; width: 100%; margin: auto; margin-bottom: 3px;clear:both;">
                          '.$quest_data['questname'].' <span style="color:#676767;float:right;">'.$nostars.'</span><span style="color:#F7E117;float:right;">'.$stars.'</span>
                          </div>
                          <div style="clear:both; margin-top:15px; margin_bottom::15px;">
                          <p style="float:right; width:180px; border-left:1px dotted #676767; padding-left:4px; font-size:13px;">'.$rest.'</p>
                          <p style="width:520px;font-size:13px;">'.words_by_sex($quest_data['middle_out']).'</p>
                          </div>
                          '.CQuest::make_belohnung_div($quest_data).'
                   </div><br /><br /><br />';

        }

        if ($Char->alive==0)
        {
            addnav('Zu den Schatten','shades.php');
        }
        else
        {
            addnav($quest_data['ortname'],$quest_data['link']);
        }

    }

    output($out);
    page_footer();
}
else
{
    redirect('village.php');
}
?>