<?php

require_once 'common.php';

$udata = user_get_aei('questinator');
$udata = utf8_unserialize($udata['questinator']);

$show_invent = true;

$str_filename = basename(__FILE__);
$str_out = get_title('`n³`4I got a jar of quests!`2³');

page_header('Der Questinator');
addnav('Zurück');
addnav('Zurück zur Eiche','greatoaktree.php');
if($_GET['op']!='')addnav('Zurück zum Questinator',$str_filename);

addnav('Kurioses');
addnav('Ne Runde prahlen',$str_filename.'?op=prahlen');
addnav('So ne komische Liste',$str_filename.'?op=listquests');
addnav('Press die Zitrone',$str_filename.'?op=zitrone');
addnav('Ene, mene, muh',$str_filename.'?op=muh');

switch($_GET['op'])
{
    case 'listquests':
    {
        $str_out .= '<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center" width="100%"><tr class="trhead">
				<th></th>
				<th>Name</th>
				<th>Sterne</th>
				<th>Helden</th>
			</tr>';

        $res = db_query("SELECT id,questname,dificulty FROM quest_events_orte WHERE activ=1 ORDER BY dificulty,questname ASC");
        $i=1;
        while($row = db_fetch_assoc($res))
        {

            $stars = '';
            $nostars = '';
            for($t=0;$t<$row['dificulty'];$t++) $stars .= '☆';
            for($t=0;$t<(10-$row['dificulty']);$t++) $nostars .= '☆';

            $cnt = db_get("SELECT COUNT(*) AS cnt FROM quest_user WHERE status='".CQuest::DONE."' AND questid='".intval($row['id'])."' ");
            $u = CQuest::get_user_quest($row['id']);
            $color = '`t';

            if(isset($u['status']) && $u['status']==CQuest::DONE) $color = '`@';
            if(isset($u['status']) && $u['status']==CQuest::OPEN) $color = '`^';
            if(isset($u['status']) && $u['status']==CQuest::LOST) $color = '`$';

            $str_out .= '<tr class="'.($i%2?'trdark':'trlight').'">
            <td>'.$i.'</td>
            <td>'.$color.strip_appoencode($row['questname']).'`0</td>
            <td><span style="color:#F7E117;">'.$stars.'</span><span style="color:#676767;">'.$nostars.'</span></td>
            <td>'.intval($cnt['cnt']).'</td></tr>';
            $i++;
        }

        $str_out .= '</table>`nLegende:<table cellspacing="5" cellpaddinf="5"><tr>
         <td>`@Erledigt`0</td>
         <td>`^Offen`0</td>
         <td>`$Verfallen`0</td>
         <td>`tNicht gefunden`0</td>
         </tr></table>';

        output($str_out.'`n`n');
    }
        break;
    case 'muh':
    {
        if(isset($_GET['co']) && isset($_GET['id']))
        {
            $co = intval($_GET['co']);
            $id = intval($_GET['id']);

            if($id > 0 && $co > 0)
            {
                $Char->gems -= $co;
                db_query("DELETE FROM quest_user WHERE questid='".$id."' AND acctid='".intval($Char->acctid)."' LIMIT 1");

                $d = user_get_aei("quests_sterne,quests_time,quests_solved");

                $udata['canmuh']=0;
            }
        }

        $str_out .= '`c³`kUnd weg bist du!`y Der Große Questinator, nicht zu verwechseln mit dem großen Nagus, kann dich `k³`n`4einmal pro Tagesabschnitt`4`n³`k eine Quest vergessen lassen.

        Ob du aber danach noch deine Mutter kennst steht in den Sternen...`f³`c';

        $str_out .= '`n`n<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center" width="100%"><tr class="trhead">
				<th></th>
				<th>Name</th>
				<th>Sterne</th>
				<th>Kosten</th>
				<th>Ene, mene, muh</th>
			</tr>';

        $res = db_query("SELECT q.id,q.questname,q.dificulty,u.status FROM quest_events_orte AS q
                            JOIN quest_user AS u ON q.id=u.questid
                        WHERE
                                q.activ=1
                                 AND u.acctid='".intval($Char->acctid)."'
                                 AND u.status='".intval(CQuest::LOST)."'
                        ORDER BY dificulty,questname ASC");
        $i=1;

        if(!isset($udata['canmuh'])){
            $udata['canmuh']=1;
        }

        $can = ($udata['canmuh'] == 1);

        while($row = db_fetch_assoc($res))
        {
            $color = '`t';

            $stars = '';
            $nostars = '';
            for($t=0;$t<$row['dificulty'];$t++) $stars .= '☆';
            for($t=0;$t<(10-$row['dificulty']);$t++) $nostars .= '☆';

            if($row['status']==CQuest::DONE) $color = '`@';
            if($row['status']==CQuest::OPEN) $color = '`^';
            if($row['status']==CQuest::LOST) $color = '`$';

            $cost =  round($row['dificulty']*1.3);

            if($row['status']==CQuest::DONE) $cost *= 3;
            if($row['status']==CQuest::LOST) $cost *= 2;
            $link = $str_filename.'?op=muh&co='.$cost.'&id='.$row['id'];
            if($can)
            {

                addnav('',$link);
            }
            $str_out .= '<tr class="'.($i%2?'trdark':'trlight').'">
            <td>'.$i.'</td><td>'.$color.strip_appoencode($row['questname']).'`0</td>
            <td><span style="color:#F7E117;">'.$stars.'</span><span style="color:#676767;">'.$nostars.'</span></td>
            <td>`k'.$cost.' `twertvolle Edelsteine</td>
            <td>'.( ($Char->gems >= $cost) ? ( $can ? '<a href="'.$link.'">Und weg bist du!</a>' : 'Heute nemmer du!') : 'Nixa ES haben du!').'</td>
            </tr>';
            $i++;
        }

        $str_out .= '</table>`nLegende:<table cellspacing="5" cellpaddinf="5"><tr>
         <td>`@Erledigt`0</td>
         <td>`^Offen`0</td>
         <td>`$Verfallen`0</td>
         </tr></table>';

        output($str_out.'`n`n');
    }
        break;
    case 'zitrone':
    {
        if(!isset($udata['canzit'])){
            $udata['canzit']=1;
        }

        $can = ($udata['canzit'] == 1);

        $str_out .= '`n`n`c³`^Die große Gelbe Zitrone (auch bekannt als Großer Nagus) - `d Ein Wunder modernster Gähn-Technik - steht vor dir.`^³`c';

        if($can && !isset($_GET['d']))
        {
            $str_out .= '`c`n`n`dWas sagst du zu ihr?
           `n`n'.create_lnk('Geld und Gold das lieb ich sehr, und hab ich´s erst von anderen, geb ich´s nicht wieder her.',$str_filename.'?op=zitrone&d=1').'
            `n'.create_lnk('Ein toter Kunde kann nicht soviel kaufen, wie ein lebender.',$str_filename.'?op=zitrone&d=2').'
            `n'.create_lnk('Wenn jemand sagt: "Es ist nicht wegen des Geldes", dann lügt er.',$str_filename.'?op=zitrone&d=3').'
            `n'.create_lnk('Lob ist billig; verteile es großzügig auf deine Kunden.',$str_filename.'?op=zitrone&d=4').'
            `n'.create_lnk('Vertraue nie einem Mann, der einen besseren Anzug trägt, als du!',$str_filename.'?op=zitrone&d=5').'
            `n'.create_lnk('Genug ist niemals genug.',$str_filename.'?op=zitrone&d=6').'
            `n'.create_lnk('Jede Minute wird ein Kunde geboren.',$str_filename.'?op=zitrone&d=7').'
            `c';
        }
        else if(!isset($_GET['d']))
        {

            $str_out .= '`n`c`$Für heute hast du wohl ausgepresst...`c';
        }


        if(isset($_GET['d']))
        {
            $d = intval($_GET['d']);

            if($d > 0)
            {
                if($can)
                {
                    $r = e_rand(1,7);
                    $s = e_rand(1,7);
                    if($d==$r||$d==$s)
                    {
                        $q = db_get("SELECT id FROM quest_events_orte WHERE activ=1 AND id NOT IN (SELECT questid FROM quest_user WHERE acctid='".intval($Char->acctid)."') ORDER BY RAND() LIMIT 1");

                        if(isset($q['id']))
                        {
                            $quest_data = CQuest::get_quest($q['id']);

                            $stars = '';
                            $nostars = '';
                            for($i=0;$i<$quest_data['dificulty'];$i++) $stars .= '☆';
                            for($i=0;$i<(10-$quest_data['dificulty']);$i++) $nostars .= '☆';

                            $rest = ($quest_data['verfall']==0) ? '`@Unbegrenzt' : '`t'.$quest_data['verfall'].' Tage';

                            $str_out .= '`n`c`^Die Zitrone spricht zu dir:`c`n';

                            $str_out .= '
            <div style="margin:auto; margin-top: 6px; padding-top: 6px; width:750px;">
            <div style="padding:8px; border-bottom: #aa7800 3px solid; width: 95%; margin: auto; margin-bottom: 5px;clear:both; min-height:60px;">
                          <div style="padding:2px; border-bottom: #676767 1px solid; width: 100%; margin: auto; margin-bottom: 3px;clear:both;">
                          '.$quest_data['questname'].' <span style="color:#676767;float:right;">'.$nostars.'</span><span style="color:#F7E117;float:right;">'.$stars.'</span>
                          </div>
                          <div style="clear:both; margin-top:15px; margin_bottom::15px;">
                          <p style="float:right; width:180px; border-left:1px dotted #676767; padding-left:4px; font-size:10px;">Zeit: '.$rest.'`0<br>Ort: `d'.$quest_data['ortname'].'</p>
                          <p style="width:520px;font-size:11px;">'.words_by_sex($quest_data['start_out']).'</p>
                          </div>
                          '.CQuest::make_belohnung_div($quest_data,false,true).'
                   </div><br /><br /><br />';
                        }
                         else
                         {
                             $str_out .= '`n`c`^Diese Zitrone ist restlos leer gepresst zur Zeit...`c';
                         }


                    }
                    else
                    {
                        $str_out .= '`n`c`$Sie ist leider sauer, komm morgen wieder.`c';
                    }
                    $udata['canzit']=0;
                }

            }
        }

        output($str_out.'`n`n');
    }
        break;
    case 'prahlen':
    {
        addcommentary();

        $str_out .= '`n`n`c³`zHier werden Legenden geboren. Du hörst die mäc`dhtigsten Questmaster dieser Welt prahlen.`z³`n`n`i`&(Info: Dies hier ist ein Quest bezogener OOC-Raum.)`i`c';
        output($str_out.'`n`n');
        viewcommentary('questinator_prahlen','Prahlen?',25,'prahlt');


    }
        break;
    default:
        {
        output($str_out.'`n`n`c³`IDer große Questinator steht vor dir und singt "`6I got a jar of quests! I got a jar of quests! I got a jar of quests! I got a jar of quests!`t"...`d³`n
        ³`dDu willst dein Finger heben um eine Frage zu stellen... entscheidest dich dann aber es lieber nicht zu tun. Vermutlich eine weise Entscheidung....`I³

        `c`n`n');
        }
        break;
}

user_set_aei(array('questinator' => db_real_escape_string(utf8_serialize($udata)) ));

page_footer();

?>