<?php
require_once 'common.php';

(!CQuest::is_activ()) ? redirect('village.php') : false;

if(!isset($Char->quests_temp['id']))$Char->quests_temp['id'] = intval($_GET['id']);

$id = $Char->quests_temp['id'];

$quest_data = CQuest::get_quest_aktion($id);
if($quest_data)
{
    addnav('Aktion');
    page_header(strip_appoencode($quest_data['questname'],3));

    if('fight' != $_GET['op'])$dataout = get_title($quest_data['questname']);

    if('fight' == $_GET['op'])
    {
        $battle=true;
        include 'battle.php';
        if ($victory)
        {
            if(count($Char->quests_temp['badguys'])>0)
            {
                $Char->badguy = array_shift($Char->quests_temp['badguys']);
                redirect('quest_aktion.php?op=fight&id='.$id);
            }
            else
            {
                output('`n`n`@`b'.words_by_sex($quest_data['kampf_aus_erfolg']).'`b`0`n`n');
                CQuest::make_effects($quest_data['implode_kampf_erfolg_efkuz']);
                addnav('Weiter','quest_aktion.php?op=finish&up=yes&id='.$id);
            }
        }
        else if ($defeat)
        {
            output('`n`n`$`b'.words_by_sex($quest_data['kampf_aus_not_erfolg']).'`b`0`n`n');

            if ($Char->alive==0)
            {
                $Char->soulpoints=1;
            }
            else
            {
                $Char->hitpoints=1;
            }

            CQuest::make_effects($quest_data['implode_kampf_not_erfolg_efkuz']);
            addnav('Weiter','quest_aktion.php?op=finish&up=no&id='.$id);
        }
        else
        {
            fightnav(true,false);
        }
    }
    else if('fightprep' == $_GET['op'])
    {
        $Char->quests_temp['badguys'] = array();

        if($quest_data['is_kampf_person'])
        {
            $monsters = explode("\n",$quest_data['kampf_personid_name']);

            foreach($monsters as $monster)
            {
                $badguy=array();

                if(999 == $quest_data['kampf_personid_level'])
                {
                    $new=db_get("SELECT * FROM creatures WHERE creaturelevel = '".intval($Char->level)."' LIMIT 1 ");
                    $new['creaturename'] = $monster;
                    $badguy = $new;
                }
                else
                {
                    $new=db_get("SELECT * FROM creatures WHERE creaturelevel = '".intval($quest_data['kampf_personid_level'])."' LIMIT 1 ");
                    $new['creaturename'] = $monster;
                    $badguy = $new;
                }

                if(isset($badguy['creatureid']))
                {
                    $Char->quests_temp['badguys'][] = createstring($badguy);
                }
            }
        }

        if($quest_data['is_kampf_monster'])
        {
            $monsters = explode(',',$quest_data['implode_kampf_monsterid']);
            foreach($monsters as $monster)
            {
                $badguy=db_get("SELECT * FROM creatures WHERE creatureid = '".intval($monster)."' LIMIT 1 ");

                if(isset($badguy['creatureid']))
                {
                    if(999 == $quest_data['kampf_monsterid_level'])
                    {
                        $new=db_get("SELECT * FROM creatures WHERE creaturelevel = '".intval($Char->level)."' LIMIT 1 ");
                    }
                    else if(888 > $quest_data['kampf_monsterid_level'])
                    {
                        $new=db_get("SELECT * FROM creatures WHERE creaturelevel = '".intval($quest_data['kampf_monsterid_level'])."' LIMIT 1 ");
                    }
                    if(isset($new))
                    {
                        $new['creaturename'] = $badguy['creaturename'];
                        $badguy = $new;
                    }
                    $Char->quests_temp['badguys'][] = createstring($badguy);
                }
            }
        }

        if($quest_data['is_kampf_eigen'])
        {
            $monsters = intval($quest_data['kampf_eigen_creatureanz']);

            for($i = 0; $i < $monsters; $i++)
            {
                $badguy = array("creaturename"=>$quest_data['kampf_eigen_creaturename'],
                    "creaturelevel"=>intval($quest_data['kampf_eigen_creaturelevel']),
                    "creatureweapon"=>$quest_data['kampf_eigen_creatureweapon'],
                    "creatureattack"=>intval($quest_data['kampf_eigen_creatureattack']),
                    "creaturedefense"=>intval($quest_data['kampf_eigen_creaturedefense']),
                    "creaturehealth"=>intval($quest_data['kampf_eigen_creaturehealth']),
                    "diddamage"=>0);


                $Char->quests_temp['badguys'][] = createstring($badguy);
            }
        }

        if(count($Char->quests_temp['badguys'])>0)
        {
            $Char->badguy = array_shift($Char->quests_temp['badguys']);
            redirect('quest_aktion.php?op=fight&id='.$id);
        }
        else
        {
            systemlog('QUEST ERROR: Keine Badguys id:'.$id);
            redirect('quest_aktion.php?op=finish&up=no&id='.$id);
        }
    }
    else if('take' == $_GET['op'])
    {
        $dataout .= words_by_sex($quest_data['middle_out']);

        if($quest_data['is_kampf_monster']!=0 || $quest_data['is_kampf_person']!=0 || $quest_data['is_kampf_eigen']!=0)
        {
            addnav('Auf in den Kampf!','quest_aktion.php?op=fightprep&id='.$id);
        }
        else
        {
            addnav('Weiter','quest_aktion.php?op=finish&up=yes&id='.$id);
        }

    }
    else if('notake' == $_GET['op'])
    {
        $dataout .= words_by_sex($quest_data['end_out']);
        addnav($quest_data['ortname'],$quest_data['link']);
    }
    else if('finish' == $_GET['op'])
    {
        $Char->quests_temp = array();
        CQuest::make_effects($quest_data['implode_efk_end']);
        if($_GET['up']=='yes')CQuest::stepup_user_quest($quest_data['questid']);

        if ($Char->alive==0)
        {
            redirect('shades.php');
        }
        else
        {
            redirect($quest_data['link']);
        }
    }
    else
    {
        //Start Effekt
        CQuest::make_effects($quest_data['implode_efk_start']);
        $dataout .= words_by_sex($quest_data['start_out']);

        switch($quest_data['typ'])
        {
            case 0: //sofort
            {
                //wenn kein text oder kampf dann unsichtabere aktion!
                if($quest_data['start_out'] == '' && $quest_data['is_kampf_monster']==0 && $quest_data['is_kampf_person']==0 && $quest_data['is_kampf_eigen'] == 0)
                {
                    redirect('quest_aktion.php?op=finish&up=yes&id='.$id);
                }
                else if($quest_data['start_out'] == '' && ($quest_data['is_kampf_monster']!=0 || $quest_data['is_kampf_person']!=0 || $quest_data['is_kampf_eigen']!=0))
                {
                    redirect('quest_aktion.php?op=fightprep&id='.$id);
                }
                else
                {
                    addnav('`@Los!','quest_aktion.php?op=take&id='.$id);
                }
            }
                break;
            case 1:  //sofort
            {
                addnav('`@Los!','quest_aktion.php?op=take&id='.$id);
            }
                break;
            case 2:   //ja nein
            {
                addnav('`@Ja!','quest_aktion.php?op=take&id='.$id);
                addnav('`$Nein!','quest_aktion.php?op=notake&id='.$id);
            }
                break;
            case 3:   //kampf
            {
                addnav('`@Ich kämpfe!','quest_aktion.php?op=take&id='.$id);
                addnav('`$Lieber nicht...','quest_aktion.php?op=notake&id='.$id);
            }
                break;
            case 4:    //tausch
            {
                addnav('`@Lasst uns tauschen!','quest_aktion.php?op=take&id='.$id);
                addnav('`$Nein! Meins ist viel schöner...!','quest_aktion.php?op=notake&id='.$id);
            }
                break;
        }
    }

    output($dataout);
    page_footer();
}
else
{
    redirect('village.php');
}
?>