<?php

class MCalendar
{
    function __construct($do, $ispopup = false)
    {
        global $session,$access_control,$Char;
        $filename = 'bathorys_popups.php?mod=calendar&mdo='.$do.'&';

        if(isset($_GET['ajax']))
        {
            if(isset($_GET['grpuser'])){
                $gid = intval($_GET['grpuser']);
                $out = '';
                $members = CCalendar::getGroupMembers($gid,$Char->acctid);
                foreach($members as $member)
                {
                    $out .= appoencode($member['name'].'`0`n');
                }
                echo $out ? $out : 'Niemand';
            }if(isset($_GET['eventuser'])){
            $gid = intval($_GET['eventuser']);
            $out = '';
            $members = CCalendar::getEventMembers($gid,$Char->acctid);
            foreach($members as $member)
            {
                $out .= appoencode($member['name'].'`0`n');
            }
            echo $out ? $out : 'Niemand';
        }else if(isset($_GET['get'])){
            $type = CCalendar::EVENT_TYPE_ALL;
            if('priv' == $_GET['get']){
                $type = CCalendar::EVENT_TYPE_PRIVAT;
            }else if('grp' == $_GET['get']){
                $type = CCalendar::EVENT_TYPE_GROUPS;
            }else if('open' == $_GET['get']){
                $type = CCalendar::EVENT_TYPE_OPEN;
            }else if('privgrp' == $_GET['get']){
                $type = CCalendar::EVENT_TYPE_PRIVAT_AND_GROUPS;
            }
            echo json_encode(array_merge(CCalendar::getEventsParsed($Char->acctid,$type, $_GET['start'], $_GET['end']),CCalendar::getEventsParsed($Char->acctid,$type, $_GET['start'], $_GET['end'],true)));
        }else if (isset($_GET['do']) && $_GET['do'] == 'quickje'){
            if(CCalendar::addEventUser($_POST['id'],$Char->acctid)){
                $event = CCalendar::getEventDataAsGuest($_POST['id']);
                if(isset($event['id'])){
                    systemmail($event['acctid'],"`dJemand nimmt an deinem Kalender-Termin teil!`0","`&".$Char->name."`t nimmt an deinem öffentlichem Termin ".$event['title']."`t teil!");
                }
            }
        }else if (isset($_GET['do']) && $_GET['do'] == 'quickle'){
            CCalendar::deleteEventUser($_POST['id'],$Char->acctid);
            $event = CCalendar::getEventDataAsGuest($_POST['id']);
            if(isset($event['id'])){
                systemmail($event['acctid'],"`4Jemand hat deinen Kalender-Termin abgesagt!`0","`&".$Char->name."`t hat deinen Kalender-Termin ".$event['title']."`t abgesagt!");
            }
        }else if(isset($_GET['do']) && $_GET['do'] == 'quickpriv'){
            $d = CCalendar::getEventData($Char->acctid,$_POST['id']);
            if(isset($d['id'])){
                CCalendar::updateEvent($d['id'],$Char->acctid,$_POST['private'],$_POST['groupid'],$_POST['title'],$_POST['description'],$_POST['start_date'],$_POST['end_date'],$_POST['color'],$_POST['textColor'],$_POST['recuring']);
            }else{
                CCalendar::addEvent($Char->acctid,$_POST['private'],$_POST['groupid'],$_POST['title'],$_POST['description'],$_POST['start_date'],$_POST['end_date'],$_POST['color'],$_POST['textColor'],$_POST['recuring']);
            }
        }else if(isset($_GET['do']) && $_GET['do'] == 'quickdrop'){
            $d = CCalendar::getEventData($Char->acctid,$_POST['id']);
            if(isset($d['id'])){
                CCalendar::updateEvent($d['id'],$Char->acctid,$d['private'],$d['groupid'],$d['title'],$d['description'],$_POST['start'],$_POST['end'],$d['color'],$d['textColor'], $d['recuring']);
            }
        }
        }else{
            popup_header('Kalender');

            $out = '`c`n<table width="100" border="0">
                            <tr>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=" class="motd">Kalender</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=verwa" class="motd">Einträge</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=grp" class="motd">Gruppen</a></td>
                            </tr>
                        </table>`c';
            JS::encapsulate('./jquery/fullcalendar/moment.min.js',true,false,true);
            JS::encapsulate('./jquery/fullcalendar/fullcalendar.min.js',true,false,true);
            JS::encapsulate('./jquery/fullcalendar/de.js',true,false,true);
            JS::encapsulate('./jquery/fullcalendar/atrahor.js?t='.filemtime('./jquery/fullcalendar/atrahor.js'),true,false,true);

            switch($do)
            {
                case 'verwa':
                    $last = new DateTime($Char->calender_last);
                    $gt = CCalendar::getGroups($Char->acctid);
                    $groups = array();
                    foreach($gt as $g){
                        $groups[$g['id']] = $g['name'];
                    }
                    $et = CCalendar::getEventsAsTeilnehmer($Char->acctid);
                    $teiln = array();
                    foreach($et as $e){
                        $teiln[$e['eventid']] = $e;
                    }
                    $on_click_add = "return confirm('Bist du sicher, dass du teilnehmen willst?');";
                    $on_click_del = "return confirm('Bist du sicher, dass du doch nicht mehr teilnehmen willst?');";
                    $out .= '`c`n<table width="100" border="0">
                            <tr>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=verwa" class="motd">Agenda</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=verw" class="motd">Deine Termine</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=new" class="motd">Neuer Termin</a></td>
                            </tr>
                        </table>`c`n`n`c`bTermin-Agenda:`b`n`n';

                    $events = CCalendar::getAgendaUser($Char->acctid);

                    $out .= ' <table style="text-align: center;">
                                <tr class="trhead">
                                    <th>Neu?</th>
                                    <th></th>
                                    <th>Anfang</th>
                                    <th>Ende</th>
                                    <th>Titel</th>
                                    <th>Beschreibung</th>
                                    <th>Gruppe</th>
                                    <th>Ersteller</th>
                                    <th>Privat</th>
                                    <th>Teilnehmer</th>
                                    <th>Teilnehmen?</th>
                                </tr>';

                    $i = 0;
                    foreach($events as $event)
                    {
                        $changed = new DateTime($event['changed']);
                        $class = $i%2?'trlight':'trdark';
                        $i++;
                        $out .= '
                            <tr class="' . $class . '">
                                <td style=" width:25px; background-color:#000;">'.( ( ($event['acctid']!=$Char->acctid) && $last < $changed) ? '<i style="color: #ff4c2c ;" class="fa fa-star fa-lg"></i>' : '' ).'</td>
                                <td style=" width:25px; background-color:'.$event['color'].';"></td>
                                <td>'.(('0000-00-00 00:00:00' !=$event['start_date'] )?str_replace(' ','<br>',mb_substr($event['start_date'],0,-3)):'').'</td>
                                <td>'.(('0000-00-00 00:00:00' !=$event['end_date'] )?str_replace(' ','<br>',mb_substr($event['end_date'],0,-3)):'').'</td>
                                <td>'.$event['title'].'</td>
                                <td>'.$event['description'].'</td>
                                <td>'.($event['groupid'] ? $groups[$event['groupid']] : '').'</td>
                                <td>'.$event['name'].'</td>
                                <td>'.($event['private'] ? '<i class="fa fa-eye fa-lg"></i>' : '<i class="fa fa-globe fa-lg"></i>').'</td>
                                <td>`c<a class="qtip-eventuser" data-gid="'.$event['id'].'" ><i class="fa fa-users fa-lg"></i></a>`c</td>
                                <td>`c
                                    '.(
                            ($event['acctid']!=$Char->acctid) ?
                                (
                                (isset($teiln[$event['id']]))?
                                    '<a class="eventleave" data-gid="'.$event['id'].'" ><i class="fa fa-check fa-lg"></i></a>'
                                    :
                                    '<a class="eventjoin" data-gid="'.$event['id'].'" ><i class="fa fa-close fa-lg"></i></a>'
                                )
                                :
                                ''
                            ).'
                                `c</td>
                            </tr>
                        ';
                    }

                    $out .= '</table>`c`n`n';


                    $out .= '`c`bWiederkehrende-Termin-Agenda:`b`n`n';

                    $events = CCalendar::getAgendaUser($Char->acctid,true);

                    $out .= ' <table style="text-align: center;">
                                <tr class="trhead">
                                    <th>Neu?</th>
                                    <th></th>
                                    <th>Turnus</th>
                                    <th>Nächster Termin</th>
                                    <th>Titel</th>
                                    <th>Beschreibung</th>
                                    <th>Gruppe</th>
                                    <th>Ersteller</th>
                                    <th>Privat</th>
                                    <th>Teilnehmer</th>
                                     <th>Teilnehmen?</th>
                                </tr>';

                    $i = 0;
                    foreach($events as $event)
                    {
                        $changed = new DateTime($event['changed']);
                        $class = $i%2?'trlight':'trdark';
                        $i++;
                        $out .= '
                            <tr class="' . $class . '">
                                <td style=" width:25px; background-color:#000;">'.( ( ($event['acctid']!=$Char->acctid) && $last < $changed) ? '<i style="color: #ff4c2c ;" class="fa fa-star fa-lg"></i>' : '' ).'</td>
                                <td style=" width:25px; background-color:'.$event['color'].';"></td>
                                <td>'.CCalendar::getTurnusText($event['recuring']).'</td>
                                <td>'.CCalendar::getNextTurnusText($event['recuring'],$event['start_date'],$event['end_date']).'</td>
                                <td>'.$event['title'].'</td>
                                <td>'.$event['description'].'</td>
                                <td>'.($event['groupid'] ? $groups[$event['groupid']] : '').'</td>
                                <td>'.$event['name'].'</td>
                                <td>'.($event['private'] ? '<i class="fa fa-eye fa-lg"></i>' : '<i class="fa fa-globe fa-lg"></i>').'</td>
                                <td>`c<a class="qtip-eventuser" data-gid="'.$event['id'].'" ><i class="fa fa-users fa-lg"></i></a>`c</td>
                                <td>`c
                                    '.(
                            ($event['acctid']!=$Char->acctid) ?
                                (
                                (isset($teiln[$event['id']]))?
                                    '<a class="eventleave" data-gid="'.$event['id'].'" ><i class="fa fa-check fa-lg"></i></a>'
                                    :
                                    '<a class="eventjoin" data-gid="'.$event['id'].'" ><i class="fa fa-close fa-lg"></i></a>'
                                )
                                :
                                ''
                            ).'
                                `c</td>
                            </tr>
                        ';
                    }

                    $out .= '</table>`c`n`n';


                    $Char->calender_last = date("Y-m-d H:i:s", time());
                    output($out);
                    break;
                case 'verw':
                    $gt = CCalendar::getGroups($Char->acctid);
                    $groups = array();
                    foreach($gt as $g){
                        $groups[$g['id']] = $g['name'];
                    }
                    $on_click = "return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');";
                    $out .= '`c`n<table width="100" border="0">
                            <tr>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=verwa" class="motd">Agenda</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=verw" class="motd">Deine Termine</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=new" class="motd">Neuer Termin</a></td>
                            </tr>
                        </table>`c`n`n`c`bDeine aktuellen Termine:`b`n`n
                            <table style="text-align: center;">
                                <tr class="trhead">
                                    <th></th>
                                    <th>Anfang</th>
                                    <th>Ende</th>
                                    <th>Titel</th>
                                    <th>Beschreibung</th>
                                    <th>Gruppe</th>
                                    <th>Privat</th>
                                    <th>Teilnehmer</th>
                                     <th>Verwaltung</th>
                                </tr>';
                    $events = CCalendar::getEventsUser($Char->acctid,CCalendar::EVENTS_NEW);
                    $i = 0;
                    foreach($events as $event)
                    {
                        $class = $i%2?'trlight':'trdark';
                        $i++;
                        $out .= '
                            <tr class="' . $class . '">
                                <td style=" width:25px; background-color:'.$event['color'].';"></td>
                                 <td>'.(('0000-00-00 00:00:00' !=$event['start_date'] )?str_replace(' ','<br>',mb_substr($event['start_date'],0,-3)):'').'</td>
                                <td>'.(('0000-00-00 00:00:00' !=$event['end_date'] )?str_replace(' ','<br>',mb_substr($event['end_date'],0,-3)):'').'</td>
                                <td>'.$event['title'].'</td>
                                <td>'.$event['description'].'</td>
                                <td>'.($event['groupid'] ? $groups[$event['groupid']] : '').'</td>
                                 <td>'.($event['private'] ? '<i class="fa fa-eye fa-lg"></i>' : '<i class="fa fa-globe fa-lg"></i>').'</td>
                                <td>`c<a class="qtip-eventuser" data-gid="'.$event['id'].'" ><i class="fa fa-users fa-lg"></i></a>`c</td>
                                <td>`c
                                '.($event['private'] ? '<a href="bathorys_popups.php?mod=calendar&mdo=eventuser&id='.$event['id'].'" ><i class="fa fa-user-plus fa-lg"></i></a>' : '' ).'
                                <a href="bathorys_popups.php?mod=calendar&mdo=edit&id='.$event['id'].'" ><i class="fa fa-pencil fa-lg"></i></a>
                                <a href="bathorys_popups.php?mod=calendar&mdo=del&id='.$event['id'].'" onclick="' . $on_click . '" ><i class="fa fa-trash-o fa-lg"></i></a>
                                `c</td>
                            </tr>
                        ';
                    }

                    $out .= '</table>`n`n`bDeine wiederkehrende Termine:`b`n`n
                            <table style="text-align: center;">
                                <tr class="trhead">
                                    <th></th>
                                    <th>Turnus</th>
                                    <th>Nächster Termin</th>
                                    <th>Titel</th>
                                    <th>Beschreibung</th>
                                    <th>Gruppe</th>
                                    <th>Privat</th>
                                    <th>Teilnehmer</th>
                                     <th>Verwaltung</th>
                                </tr>';
                    $events = CCalendar::getEventsUser($Char->acctid,CCalendar::EVENTS_ALL,true);
                    $i = 0;
                    foreach($events as $event)
                    {

                        $class = $i%2?'trlight':'trdark';
                        $i++;
                        $out .= '
                            <tr class="' . $class . '">
                                <td style=" width:25px; background-color:'.$event['color'].';"></td>
                                  <td>'.CCalendar::getTurnusText($event['recuring']).'</td>
                                  <td>'.CCalendar::getNextTurnusText($event['recuring'],$event['start_date'],$event['end_date']).'</td>
                                <td>'.$event['title'].'</td>
                                <td>'.$event['description'].'</td>
                                <td>'.($event['groupid'] ? $groups[$event['groupid']] : '').'</td>
                               <td>'.($event['private'] ? '<i class="fa fa-eye fa-lg"></i>' : '<i class="fa fa-globe fa-lg"></i>').'</td>
                                <td>`c<a class="qtip-eventuser" data-gid="'.$event['id'].'" ><i class="fa fa-users fa-lg"></i></a>`c</td>
                                <td>`c
                                  '.($event['private'] ? '<a href="bathorys_popups.php?mod=calendar&mdo=eventuser&id='.$event['id'].'" ><i class="fa fa-user-plus fa-lg"></i></a>' : '' ).'
                                <a href="bathorys_popups.php?mod=calendar&mdo=edit&id='.$event['id'].'" ><i class="fa fa-pencil fa-lg"></i></a>
                                <a href="bathorys_popups.php?mod=calendar&mdo=del&id='.$event['id'].'" onclick="' . $on_click . '" ><i class="fa fa-trash-o fa-lg"></i></a>
                                `c</td>
                            </tr>
                        ';
                    }

                    $out .= '</table>`n`n`bDeine abgelaufene Termine:`b`n`n
                            <table style="text-align: center;">
                                <tr class="trhead">
                                    <th></th>
                                    <th>Anfang</th>
                                    <th>Ende</th>
                                    <th>Titel</th>
                                    <th>Beschreibung</th>
                                    <th>Gruppe</th>
                                    <th>Privat</th>
                                    <th>Teilnehmer</th>
                                     <th>Verwaltung</th>
                                </tr>';
                    $events = CCalendar::getEventsUser($Char->acctid,CCalendar::EVENTS_OLD);
                    $i = 0;
                    foreach($events as $event)
                    {
                        $class = $i%2?'trlight':'trdark';
                        $i++;
                        $out .= '
                            <tr class="' . $class . '">
                                <td style=" width:25px; background-color:'.$event['color'].';"></td>
                                        <td>'.(('0000-00-00 00:00:00' !=$event['start_date'] )?str_replace(' ','<br>',mb_substr($event['start_date'],0,-3)):'').'</td>
                                <td>'.(('0000-00-00 00:00:00' !=$event['end_date'] )?str_replace(' ','<br>',mb_substr($event['end_date'],0,-3)):'').'</td>
                                <td>'.$event['title'].'</td>
                                <td>'.$event['description'].'</td>
                                <td>'.($event['groupid'] ? $groups[$event['groupid']] : '').'</td>
                               <td>'.($event['private'] ? '<i class="fa fa-eye fa-lg"></i>' : '<i class="fa fa-globe fa-lg"></i>').'</td>
                                <td>`c<a class="qtip-eventuser" data-gid="'.$event['id'].'" ><i class="fa fa-users fa-lg"></i></a>`c</td>
                                <td>`c
                                <a href="bathorys_popups.php?mod=calendar&mdo=edit&id='.$event['id'].'" ><i class="fa fa-pencil fa-lg"></i></a>
                                <a href="bathorys_popups.php?mod=calendar&mdo=del&id='.$event['id'].'" onclick="' . $on_click . '" ><i class="fa fa-trash-o fa-lg"></i></a>
                                `c</td>
                            </tr>
                        ';
                    }
                    $out .= '</table>`c`n`n';
                    output($out);
                    break;
                case 'new':
                case 'edit':

                    $out .= '`c`n<table width="100" border="0">
                            <tr>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=verwa" class="motd">Agenda</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=verw" class="motd">Deine Termine</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=new" class="motd">Neuer Termin</a></td>
                            </tr>
                        </table>`c`n`n';

                    if(count($_POST)){
                        if('new' == $do)CCalendar::addEvent($Char->acctid,$_POST['private'],$_POST['groupid'],$_POST['title'],$_POST['description'],$_POST['start_date'],$_POST['end_date'],$_POST['color'],$_POST['textColor'], $_POST['recuring']);
                        else if('edit' == $do)CCalendar::updateEvent($_GET['id'],$Char->acctid,$_POST['private'],$_POST['groupid'],$_POST['title'],$_POST['description'],$_POST['start_date'],$_POST['end_date'],$_POST['color'],$_POST['textColor'], $_POST['recuring']);
                        redirect('bathorys_popups.php?mod=calendar&mdo=verw',false,false);
                    }else{
                        $form = array();
                        $gt = CCalendar::getGroups($Char->acctid);
                        $groups = '';
                        foreach($gt as $g){
                            $groups .= ','.$g['id'].','.strip_appoencode($g['name'],3);
                        }
                        $form[]         = 'Kalender-Eintrag,title';
                        $form['title']  =  "Titel,text,255";
                        $form['description'] =  "Beschreibung,textarea,50,10";
                        $form['private'] =  "Privat,select,1,Ja,0,Nein";
                        $form['groupid'] =  "Gruppe,select,0,Keine".$groups;
                        $form['start_date'] =  "Anfang,datetime";
                        $form['end_date'] =  "Ende,datetime";
                        $form['recuring'] =  "Wiederkehrend,select,".CCalendar::EVENTS_RECURING_NONE.",Einmalig,".CCalendar::EVENTS_RECURING_DAILY.",Täglich,".CCalendar::EVENTS_RECURING_WEEKLY.",Wöchentlich,".CCalendar::EVENTS_RECURING_MONTLY.",Monatlich,".CCalendar::EVENTS_RECURING_YEARLY.",Jährlich";
                        $form['color'] =  "Hintergrund-Farbe,hex_pick";
                        $form['textColor'] =  "Text-Farbe,hex_pick";

                        $data = array();
                        if('edit' == $do){
                            $data = CCalendar::getEventData($Char->acctid, $_GET['id']);
                            $data['start_date']= mb_substr($data['start_date'],0,-3);
                            $data['end_date']= mb_substr($data['end_date'],0,-3);
                        }

                        output($out);
                        output('`n<form action="'.$filename.(('edit' == $do)?'&id='.$_GET['id']:'').'" method="POST" enctype="multipart/form-data">');
                        showform($form,$data,false,'Eintragen',9);
                        output('</form>');
                    }

                    break;
                case 'del':
                    CCalendar::deleteEvent($_GET['id'],$Char->acctid);
                    redirect('bathorys_popups.php?mod=calendar&mdo=verw',false,false);
                    break;

                case 'jogrp':
                    $groups = CCalendar::getPubGroups($Char->acctid);
                    $out .= '`c`n<table width="100" border="0">
                            <tr>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=grp" class="motd">Übersicht</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=newgrp" class="motd">Erstellen</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=jogrp" class="motd">Beitreten</a></td>
                            </tr>
                        </table>`c`n`n`c`bÖffentliche Gruppen, dennen du beitreten kannst:`b`n`n
                            <table style="text-align: center;">
                                <tr class="trhead">
                                    <th>Name</th>
                                    <th>Besitzer</th>
                                    <th style="width: 252px;">Beschreibung</th>
                                    <th>Typ</th>
                                    <th>Mitglieder</th>
                                    <th>Verwaltung</th>
                                </tr>';
                    $i = 0;
                    foreach($groups as $group)
                    {
                        $class = $i%2?'trlight':'trdark';
                        $i++;
                        $out .= '
                            <tr class="' . $class . '">
                                <td>'.$group['name'].'</td>
                                <td>'.$group['ownername'].'</td>
                                <td>'.$group['description'].'</td>
                                <td>'.((CCalendar::GROUP_TYPE_PRIVAT == $group['type']) ? '<i class="fa fa-eye fa-lg"></i>' : '<i class="fa fa-globe fa-lg"></i>').'</td>
                                <td>`c<a class="qtip-grpuser" data-gid="'.$group['id'].'" ><i class="fa fa-users fa-lg"></i></a>`c</td>
                                <td>`c<a href="bathorys_popups.php?mod=calendar&mdo=jogrp2&id='.$group['id'].'" ><i class="fa fa-plus fa-lg"></i></a>`c</td>
                            </tr>
                        ';
                    }
                    $out .= '</table>`c`n`n';
                    output($out);
                    break;
                case 'jogrp2':
                    if(CCalendar::addGroupUser($_GET['id'],$Char->acctid)){
                        $group = CCalendar::getGroupDataAsGuest($_GET['id']);
                        if(isset($group['id'])){
                            systemmail($group['owner'],"`dJemand ist deiner Kalender-Gruppe beigetreten!`0","`&".$Char->name."`t ist deiner Kalender-Gruppe ".$group['name']."`t beigetreten!");
                        }
                    }
                    redirect('bathorys_popups.php?mod=calendar&mdo=jogrp',false,false);
                    break;
                case 'joevent':
                    if(CCalendar::addEventUser($_GET['id'],$Char->acctid)){
                        $event = CCalendar::getEventDataAsGuest($_GET['id']);
                        if(isset($event['id'])){
                            systemmail($event['acctid'],"`dJemand nimmt an deinem Kalender-Termin teil!`0","`&".$Char->name."`t nimmt an deinem öffentlichem Termin ".$event['title']."`t teil!");
                        }
                    }
                    redirect('bathorys_popups.php?mod=calendar&mdo=verwa',false,false);
                    break;
                case 'grpuser':
                    $group = CCalendar::getGroupData($Char->acctid,$_GET['id']);
                    if(isset($group['id'])){
                        $out .= '`c`n<table width="100" border="0">
                            <tr>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=grp" class="motd">Übersicht</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=newgrp" class="motd">Erstellen</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=jogrp" class="motd">Beitreten</a></td>
                            </tr>
                        </table>`c`n`n`c`bMitglieder der Gruppe '.$group['name'].':`b`n`n';
                        if(isset($_POST['login']))
                        {
                            $user = db_get("SELECT acctid FROM accounts WHERE login LIKE '".db_real_escape_string($_POST['login'])."' LIMIT 1");
                            if(isset($user['acctid'])){
                                $usergrp = db_get("SELECT id FROM calendar_groups_user WHERE groupid='".intval($group['id'])."' AND acctid='".intval($user['acctid'])."' LIMIT 1");
                                if(CIgnore::ignores($user['acctid'], $Char->acctid, CIgnore::IGNO_CAL))
                                {
                                    $out.='`$Diese Person will keine Kalender-Einladungen von dir empfangen :\'(!`0`n`n';
                                }
                                else if(CIgnore::ignores($Char->acctid,$user, CIgnore::IGNO_CAL))
                                {
                                    $out.='`$Du ignorierst diese Person ;)!`0`n`n';
                                }
                                else if($user['acctid'] == $Char->acctid)
                                {
                                    $out.='`$Du bist doch schon automatisch drin ;)!`0`n`n';
                                }
                                else if(isset($usergrp['id']))
                                {
                                    $out.='`$Doppelt hält nicht immer besser ;)!`0`n`n';
                                }
                                else
                                {
                                    if(CCalendar::addGroupUser($_GET['id'],$user['acctid'],$Char->acctid)){
                                        systemmail($user['acctid'],"`@Kalender-Gruppen-Einladung erhalten!`0","`&".$Char->name."`t hat dir eine Einladung für die Kalender-Gruppe ".$group['name']."`t geschickt!");
                                    }
                                }
                            }else{
                                $out.='`$Spieler '.utf8_htmlentities($_POST['login']).' nicht gefunden!`0`n`n';
                            }
                        }
                        $on_click = "return confirm('Bist du sicher, dass dieses Mitgleid entfernt werden soll?');";
                        $members = CCalendar::getGroupMembers($_GET['id'],$Char->acctid);
                        if(CCalendar::GROUP_TYPE_PRIVAT==$group['type'])
                        {
                            $out .= '<form action="'.$filename.'&id='.intval($_GET['id']).'" method="POST" enctype="multipart/form-data">';
                            $out .= JS::Autocomplete('login',false,true,null,'login');
                            $out .= ' <input type="submit" value="Hinzufügen" class="button"> </form>';
                        }

                        $out .='`n<table style="text-align: center;">
                                <tr class="trhead">
                                    <th>Name</th>
                                    '.((CCalendar::GROUP_TYPE_PRIVAT==$group['type'])?'<th>Verwaltung</th>':'').'
                                </tr>';
                        $i = 0;
                        foreach($members as $member)
                        {
                            $class = $i%2?'trlight':'trdark';
                            $i++;
                            $out .= '
                            <tr class="' . $class . '">
                                <td>'.$member['name'].'</td>
                                '.((CCalendar::GROUP_TYPE_PRIVAT==$group['type'])?'<td>`c<a href="bathorys_popups.php?mod=calendar&mdo=grpuserdel&id='.$_GET['id'].'&acctid='.$member['acctid'].'" onclick="' . $on_click . '" ><i class="fa fa-trash-o fa-lg"></i></a>`c</td>':'').'
                            </tr>
                        ';
                        }
                        $out .= '</table>`c`n`n';
                    }
                    output($out);
                    break;
                case 'grpuserdel':
                    if(CCalendar::deleteGroupUserAsOwner($_GET['id'],$Char->acctid,$_GET['acctid']))
                    {
                        $group = CCalendar::getGroupData($Char->acctid,$_GET['id']);
                        systemmail($_GET['acctid'],"`4Kalender-Gruppen-Einladung entzogen!`0","`&".$Char->name."`t hat dir eine Einladung für die Kalender-Gruppe ".$group['name']."`t entzogen!");
                    }
                    redirect('bathorys_popups.php?mod=calendar&mdo=grpuser&id='.intval($_GET['id']),false,false);
                    break;
                case 'eventuser':
                    $event = CCalendar::getEventData($Char->acctid,$_GET['id']);
                    if(isset($event['id'])){
                        $out .= '`c`n<table width="100" border="0">
                            <tr>
                             <td><a href="bathorys_popups.php?mod=calendar&mdo=verwa" class="motd">Agenda</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=verw" class="motd">Deine Termine</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=new" class="motd">Neuer Termin</a></td>
                            </tr>
                        </table>`c`n`n`c`bTeilnehmer '.$event['title'].':`b`n`n';
                        if(isset($_POST['login']))
                        {
                            $user = db_get("SELECT acctid FROM accounts WHERE login LIKE '".db_real_escape_string($_POST['login'])."' LIMIT 1");
                            if(isset($user['acctid'])){
                                $userevent = db_get("SELECT id FROM calendar_events_user WHERE eventid='".intval($event['id'])."' AND acctid='".intval($user['acctid'])."' LIMIT 1");
                                if(CIgnore::ignores($user['acctid'], $Char->acctid, CIgnore::IGNO_CAL))
                                {
                                    $out.='`$Diese Person will keine Kalender-Einladungen von dir empfangen :\'(!`0`n`n';
                                }
                                else if(CIgnore::ignores($Char->acctid,$user, CIgnore::IGNO_CAL))
                                {
                                    $out.='`$Du ignorierst diese Person ;)!`0`n`n';
                                }
                                else if($user['acctid'] == $Char->acctid)
                                {
                                    $out.='`$Du bist doch schon automatisch drin ;)!`0`n`n';
                                }
                                else if(isset($userevent['id']))
                                {
                                    $out.='`$Doppelt hält nicht immer besser ;)!`0`n`n';
                                }
                                else
                                {
                                    if(CCalendar::addEventUser($_GET['id'],$user['acctid'],$Char->acctid)){
                                        systemmail($user['acctid'],"`@Zu einem Kalender-Privat-Termin eingeladen!`0","`&".$Char->name."`t hat dich für den Kalender-Privat-Termin ".$event['title']."`t eingeladen!");
                                    }
                                }
                            }else{
                                $out.='`$Spieler '.utf8_htmlentities($_POST['login']).' nicht gefunden!`0`n`n';
                            }
                        }
                        $on_click = "return confirm('Bist du sicher, dass diesen Teilnehmer entfernt werden soll?');";
                        $members = CCalendar::getEventMembers($_GET['id'],$Char->acctid);
                        if($event['private'])
                        {
                            $out .= '<form action="'.$filename.'&id='.intval($_GET['id']).'" method="POST" enctype="multipart/form-data">';
                            $out .= JS::Autocomplete('login',false,true,null,'login');
                            $out .= ' <input type="submit" value="Hinzufügen" class="button"> </form>';
                        }

                        $out .='`n<table style="text-align: center;">
                                <tr class="trhead">
                                    <th>Name</th>
                                    '.(($event['private'])?'<th>Verwaltung</th>':'').'
                                </tr>';
                        $i = 0;
                        foreach($members as $member)
                        {
                            $class = $i%2?'trlight':'trdark';
                            $i++;
                            $out .= '
                            <tr class="' . $class . '">
                                <td>'.$member['name'].'</td>
                                '.(($event['private'])?'<td>`c<a href="bathorys_popups.php?mod=calendar&mdo=eventuserdel&id='.$_GET['id'].'&acctid='.$member['acctid'].'" onclick="' . $on_click . '" ><i class="fa fa-trash-o fa-lg"></i></a>`c</td>':'').'
                            </tr>
                        ';
                        }
                        $out .= '</table>`c`n`n';
                    }
                    output($out);
                    break;
                case 'eventuserdel':
                    if(CCalendar::deleteEventUserAsOwner($_GET['id'],$Char->acctid,$_GET['acctid']))
                    {
                        $event = CCalendar::getEventData($Char->acctid,$_GET['id']);
                        systemmail($_GET['acctid'],"`4Von einem Kalender-Privat-Termin ausgeladen!`0","`&".$Char->name."`t hat dich vom privaten Termin ".$event['title']."`t ausgeladen!");
                    }
                    redirect('bathorys_popups.php?mod=calendar&mdo=eventuser&id='.intval($_GET['id']),false,false);
                    break;
                case 'delgrp':
                    CCalendar::deleteGroup($_GET['id'],$Char->acctid);
                    redirect('bathorys_popups.php?mod=calendar&mdo=grp',false,false);
                    break;
                case 'leavegrp':
                    CCalendar::deleteGroupUser($_GET['id'],$Char->acctid);
                    $group = CCalendar::getGroupDataAsGuest($_GET['id']);
                    if(isset($group['id'])){
                        systemmail($group['owner'],"`4Jemand ist deiner Kalender-Gruppe ausgetreten!`0","`&".$Char->name."`t ist deiner Kalender-Gruppe ".$group['name']."`t ausgetreten!");
                    }
                    redirect('bathorys_popups.php?mod=calendar&mdo=grp',false,false);
                    break;
                case 'leaveevent':
                    CCalendar::deleteEventUser($_GET['id'],$Char->acctid);
                    $event = CCalendar::getEventDataAsGuest($_GET['id']);
                    if(isset($event['id'])){
                        systemmail($event['acctid'],"`4Jemand hat deinen Kalender-Termin abgesagt!`0","`&".$Char->name."`t hat deinen Kalender-Termin ".$event['title']."`t abgesagt!");
                    }
                    redirect('bathorys_popups.php?mod=calendar&mdo=verwa',false,false);
                    break;
                case 'newgrp':
                case 'editgrp':

                    $out .= '`c`n<table width="100" border="0">
                            <tr>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=grp" class="motd">Übersicht</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=newgrp" class="motd">Erstellen</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=jogrp" class="motd">Beitreten</a></td>
                            </tr>
                        </table>`c`n`n';

                    if(count($_POST)){
                        if('newgrp' == $do)CCalendar::addGroup($Char->acctid,$_POST['type'],$_POST['name'],$_POST['description']);
                        else if('editgrp' == $do)CCalendar::updateGroup($_GET['id'],$Char->acctid,$_POST['type'],$_POST['name'],$_POST['description']);
                        redirect('bathorys_popups.php?mod=calendar&mdo=grp',false,false);
                    }else{
                        $form = array();
                        $form[]         = 'Kalender-Gruppe,title';
                        $form['name']  =  "Name,text,255";
                        $form['description'] =  "Beschreibung,textarea,50,10";
                        $form['type'] =  "Typ,select,".CCalendar::GROUP_TYPE_PRIVAT.",Private Gruppe,".CCalendar::GROUP_TYPE_OPEN.",Öffentliche Gruppe";
                        $data = array();
                        if('editgrp' == $do){
                            $data = CCalendar::getGroupData($Char->acctid, $_GET['id']);
                        }
                        output($out);
                        output('`n<form action="'.$filename.(('editgrp' == $do)?'&id='.$_GET['id']:'').'" method="POST" enctype="multipart/form-data">');
                        showform($form,$data,false,'Eintragen',9);
                        output('</form>');
                    }
                    break;

                case 'grp':
                    $groups = CCalendar::getGroups($Char->acctid);
                    $on_click_leave = "return confirm('Bist du sicher, dass du diese Gruppe verlassen willst?');";
                    $on_click_del = "return confirm('Bist du sicher, dass du diese Gruppe löschen willst?');";
                    $out .= '`c`n<table width="100" border="0">
                            <tr>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=grp" class="motd">Übersicht</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=newgrp" class="motd">Erstellen</a></td>
                               <td><a href="bathorys_popups.php?mod=calendar&mdo=jogrp" class="motd">Beitreten</a></td>
                            </tr>
                        </table>`c`n`n`c`bDeine eigenen Gruppen:`b`n`n
                            <table style="text-align: center;">
                                <tr class="trhead">
                                    <th>Name</th>
                                    <th>Besitzer</th>
                                    <th style="width: 252px;">Beschreibung</th>
                                    <th>Typ</th>
                                    <th>Mitglieder</th>
                                    <th>Verwaltung</th>
                                </tr>';
                    $i = 0;
                    foreach($groups as $group)
                    {
                        if($Char->acctid == $group['owner']){
                            $class = $i%2?'trlight':'trdark';
                            $i++;
                            $out .= '
                            <tr class="' . $class . '">
                                <td>'.$group['name'].'</td>
                                <td>'.$group['ownername'].'</td>
                                <td>'.$group['description'].'</td>
                                <td>'.((CCalendar::GROUP_TYPE_PRIVAT == $group['type']) ? '<i class="fa fa-eye fa-lg"></i>' : '<i class="fa fa-globe fa-lg"></i>').'</td>
                                <td>`c<a class="qtip-grpuser" data-gid="'.$group['id'].'" ><i class="fa fa-users fa-lg"></i></a>`c</td>
                                <td>`c'.((CCalendar::GROUP_TYPE_PRIVAT == $group['type']) ? '<a href="bathorys_popups.php?mod=calendar&mdo=grpuser&id='.$group['id'].'" ><i class="fa fa-user-plus fa-lg"></i></a>' : '' ).'  <a href="bathorys_popups.php?mod=calendar&mdo=editgrp&id='.$group['id'].'" ><i class="fa fa-pencil fa-lg"></i></a>   <a href="bathorys_popups.php?mod=calendar&mdo=delgrp&id='.$group['id'].'" onclick="' . $on_click_del . '" ><i class="fa fa-trash-o fa-lg"></i></a>`c</td>
                            </tr>
                        ';
                        }
                    }
                    $out .= '</table>`n`n`bGruppen dennen du angehörst:`b`n`n
                            <table style="text-align: center;">
                                <tr class="trhead">
                                    <th>Name</th>
                                    <th>Besitzer</th>
                                    <th style="width: 252px;">Beschreibung</th>
                                    <th>Typ</th>
                                    <th>Mitglieder</th>
                                    <th>Verwaltung</th>
                                </tr>';
                    $i = 0;
                    foreach($groups as $group)
                    {
                        if($Char->acctid != $group['owner']){
                            $class = $i%2?'trlight':'trdark';
                            $i++;
                            $out .= '
                            <tr class="' . $class . '">
                                <td>'.$group['name'].'</td>
                                <td>'.$group['ownername'].'</td>
                                <td>'.$group['description'].'</td>
                                <td>'.((CCalendar::GROUP_TYPE_PRIVAT == $group['type']) ? '<i class="fa fa-eye fa-lg"></i>' : '<i class="fa fa-globe fa-lg"></i>').'</td>
                                <td>`c<a class="qtip-grpuser" data-gid="'.$group['id'].'" ><i class="fa fa-users fa-lg"></i></a>`c</td>
                                <td>`c<a href="bathorys_popups.php?mod=calendar&mdo=leavegrp&id='.$group['id'].'" onclick="' . $on_click_leave . '" ><i class="fa fa-trash-o fa-lg"></i></a> `c</td>
                            </tr>
                        ';
                        }
                    }
                    $out .= '</table>`c`n`n';
                    output($out);
                    break;
                default:
                    $form = array();
                    $gt = CCalendar::getGroups($Char->acctid);
                    $groups = '';
                    foreach($gt as $g){
                        $groups .= ','.$g['id'].','.strip_appoencode($g['name'],3);
                    }
                    $form['id']  =  "ID,hidden";
                    $form['title']  =  "Titel,text,255";
                    $form['description'] =  "Beschreibung,textarea,60,2";
                    $form['private'] =  "Privat,select,1,Ja,0,Nein";
                    $form['groupid'] =  "Gruppe,select,0,Keine".$groups;
                    $form['start_date'] =  "Anfang,datetime";
                    $form['end_date'] =  "Ende,datetime";
                    $form['recuring'] =  "Wiederkehrend,select,".CCalendar::EVENTS_RECURING_NONE.",Einmalig,".CCalendar::EVENTS_RECURING_DAILY.",Täglich,".CCalendar::EVENTS_RECURING_WEEKLY.",Wöchentlich,".CCalendar::EVENTS_RECURING_MONTLY.",Monatlich,".CCalendar::EVENTS_RECURING_YEARLY.",Jährlich";
                    $form['color'] =  "Hintergrund-Farbe,hex_pick_top";
                    $form['textColor'] =  "Text-Farbe,hex_pick_top";
                    JS::encapsulate('var username = "'.$Char->name.'";',false,true);
                    $out .= "
                            <div id='dialog-form' title='Termin'>
                              <form id='theform'>
                                ".generateform($form,array(),true,'',9)."
                              </form>
                            </div>
                            <div class='calselect'>
                                Kalender:
                                <select id='cal-selector'>
                                    <option value='privgrp' selected>Übersicht (Privat+Gruppen+deine öffentlichen)</option>
                                    <option value='' >Übersicht (Privat+Gruppen+alle öffentlichen)</option>
                                    <option value='priv'>Nur Privat</option>
                                    <option value='grp'>Nur Gruppen</option>
                                    <option value='open'>Nur Öffentlich</option>
                                </select>
                            </div>
                            <div id='calendar'></div>";
                    output($out);
                    break;
            }

            popup_footer();
        }
    }
}
?>