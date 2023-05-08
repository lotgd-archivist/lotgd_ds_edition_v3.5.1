<?php

/*
  Eleya für atrahor.de: Ersatz der ooc.php
  OOC-Bereiche zusammengefügt
*/

require_once 'common.php';

$show_invent = true;
addcommentary();
checkday();
require_once(LIB_PATH.'board.lib.php');

page_header("OOC-Bereich");

$str_output = '';
switch ($_GET['op'])
{
case 'creatures': { //Auflistung der Waldmonster
        $order=($_GET['order']>''?'creature'.$_GET['order']:'creaturename');
        $dir=($_GET['dir']==1?' DESC, ':' ASC, ');
        $base_link='ooc_area.php?op=creatures&order='.$_GET['order'].'&dir='.$_GET['dir'].'';
        $count_sql='SELECT count(*) AS c FROM creatures WHERE creaturelevel<17';
        $sql='SELECT creaturename,creaturelevel,creatureweapon,creaturelose
            FROM creatures
            WHERE creaturelevel<17
            ORDER BY '.$order.$dir.
                ($order=='creaturename'?'':' creaturename ASC, ').
                ($order=='creaturelevel'?'':' creaturelevel ASC, ').'
                creatureid ASC
            ';
        $arr_page_res = page_nav($base_link,$count_sql,30);
        $sql .= ' LIMIT '.$arr_page_res['limit'];
        $result=db_query($sql);
        $str_out .= '`i`7In den Wäldern von '.getsetting('townname','Atrahor').' wurden bereits `^`b'.$arr_page_res['count'].'`b`7 unterschiedliche Monster gesehen!`0`i`n`n';
        $str_out.='<table width="95%" border=0 bgcolor="#888888" align="center">
        <tr class="trhead">
        <th>'.create_lnk('Name'.($order=='creaturename'?($_GET['dir']==1?' &darr;':' &uarr;'):''),'ooc_area.php?op=creatures&order=name&dir='.($_GET['dir']==1?0:1).'&page=0').'</th>
        <th>'.create_lnk('Level'.($order=='creaturelevel'?($_GET['dir']==1?'&nbsp;&darr;':'&nbsp;&uarr;'):''),'ooc_area.php?op=creatures&order=level&dir='.($_GET['dir']==1?0:1).'&page=0').'</th>
        <th>'.create_lnk('Waffe'.($order=='creatureweapon'?($_GET['dir']==1?' &darr;':' &uarr;'):''),'ooc_area.php?op=creatures&order=weapon&dir='.($_GET['dir']==1?0:1).'&page=0').'</th>
        </tr>';
        $trclass='trdark';
        
        while($row=db_fetch_assoc($result))
        {
            $creaturelose=strip_appoencode($row['creaturelose'],3);
            $creaturelose=str_replace('"','&quot;',$creaturelose);
            $str_out.='<tr class="'.$trclass.'">
            <td title="'.$creaturelose.'">'.$row['creaturename'].'</td>
            <td align="center">'.$row['creaturelevel'].'</td>
            <td>'.$row['creatureweapon'].'</td>
            </tr>';
            $trclass=($trclass=='trlight'?'trdark':'trlight');
        }
        $str_out.='</table>';
        addnav('Zurück');
        addnav('Zum OoC-Bereich','ooc_area.php');
        addnav('d?Zum Stadtzentrum','village.php');
        output($str_out);
        break;
    }
    break;
    
case 'diskus': {
        output('`c`b`IDiskussionsraum`0`b`c`nDer Debattierraum liegt vor Dir!`n
        Hier bekommt das Volk Gehör und die Admins hören sich Wünsche, Anregungen und Beschwerden an, solange sie entsprechend formuliert sind. Bitte denkt an einen angemessenen Umgangston!  ');
        output('Wie Dir scheint ist schon eine rege Diskussion im Gange!`n`n');
        addcommentary(false);
        viewcommentary("rat","Rufen",30,"ruft");

        addnav('OOC - Raum','ooc_area.php?op=ooc');
        addnav('RP-Suche','ooc_area.php?op=brett');
		addnav('Bio-Hilfe','ooc_area.php?op=biobrett');
        addnav('MRPG-Planungsbereich','ooc_area.php?op=mrpg');
        //addnav('`NSt`$ad`^ion','ooc_area.php?op=football');

        if($session['user']['alive'])
        {
                addnav('Zurück','ooc_area.php');
        }
        else
        {
                addnav('Zurück','shades.php');
        }
}
break;

case 'ooc': {
        output('`c`b`IOOC-Raum`0`b`c`nKomischer Name, denkst Du Dir, als du die Tür zu diesem Raum aufstösst!`n');
        output('Überall an den Wänden stehen leuchtende Scheiben und einige dir bekannte und weniger
        bekannte Gesichter starren wie gebannt darauf und klimpern auf bemalten Brettern herum - seltsame Runen.`n`n');
        output('Du hast den OOC Raum betreten. Wenn Du Gespräche führen möchtest, die sich außerhalb deines Charakters befinden,
        so führe sie bitte hier! Sollten sich andere Mitspieler irgendwo anders OOC unterhalten, dann weise sie bitte freundlich
        per Brieftaube darauf hin, dass dies hier der richtige Ort dafür wäre!`0`n`n');
        addcommentary(false);
        viewcommentary("OOC","Tippen",30,"tippt");

        addnav('Diskussionsraum','ooc_area.php?op=diskus');
        addnav('RP-Suche ','ooc_area.php?op=brett');
		addnav('Bio-Hilfe','ooc_area.php?op=biobrett');
        addnav('MRPG-Planungsbereich','ooc_area.php?op=mrpg');
        //addnav('`NSt`$ad`^ion','ooc_area.php?op=football');

        if($session['user']['alive'])
        {
                addnav('Zurück','ooc_area.php');
        }
        else
        {
                addnav('Zurück','shades.php');
        } 
}
break;

case 'brett': {
 page_header('RP-Suche');
        output('`c`b`IRollenspiel-Suche`0`b`c`nAn der Wand des OOC-Raums entdeckst du ein kleines, schwarzes Brett. Eine kleine Tafel informiert dich darüber, dass du hier OOC nach Spielpartnern suchen kannst, jeglicher anderer Spam jedoch gelöscht wird.`n`n');
        board_view('ooc',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,'Folgende Nachrichten hängen am Brett:','Es befinden sich keine Nachrichten am Brett',true, true, false, true);
        output('`n`n');
        board_view_form('Aufhängen','Auch du kannst eine Nachricht hinterlassen:`n');
        output('`n`n');
        if ($_GET['board_action'] == "add")
        {
                if (board_add('ooc',180,1) == -1)
                {
                        output('`4Du hast doch schon einen Zettel aufgehängt, das sollte wirklich reichen.`n`n');
                }
                else
                {
                        redirect("ooc_area.php?op=brett");
                }
        }
        addnav('Diskussionsraum','ooc_area.php?op=diskus');
        addnav('OOC - Raum','ooc_area.php?op=ooc');
        addnav('MRPG-Planungsbereich','ooc_area.php?op=mrpg');
        //addnav('`NSt`$ad`^ion','ooc_area.php?op=football');

        if($session['user']['alive'])
        {
                addnav('Zurück','ooc_area.php');
        }
        else
        {
                addnav('Zurück','shades.php');
        } 
}
break;
case 'biobrett': {
 page_header('Hilfsgesuche für Biogestaltung ');
        output('`c`b`IHilfsgesuche für Biogestaltung`0`b`c`nAn der Wand des OOC-Raums entdeckst du ein kleines, schwarzes Brett. Eine kleine Tafel informiert dich darüber, dass du hier OOC nach Hilfe für Biogestaltung suchen kannst, jeglicher anderer Spam jedoch gelöscht wird.`n`n');
        board_view('bioooc',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,'Folgende Nachrichten hängen am Brett:','Es befinden sich keine Nachrichten am Brett',true, true, false, true,200,true);
        output('`n`n');
        board_view_form('Aufhängen','Auch du kannst eine Nachricht hinterlassen:`n');
        output('`n`n');
        if ($_GET['board_action'] == "add")
        {
                if (board_add('bioooc',180,1) == -1)
                {
                        output('`4Du hast doch schon einen Zettel aufgehängt, das sollte wirklich reichen.`n`n');
                }
                else
                {
                        redirect("ooc_area.php?op=biobrett");
                }
        }
        addnav('Diskussionsraum','ooc_area.php?op=diskus');
        addnav('OOC - Raum','ooc_area.php?op=ooc');
        addnav('MRPG-Planungsbereich','ooc_area.php?op=mrpg');
        //addnav('`NSt`$ad`^ion','ooc_area.php?op=football');

        if($session['user']['alive'])
        {
                addnav('Zurück','ooc_area.php');
        }
        else
        {
                addnav('Zurück','shades.php');
        } 
}
break;
case 'football': {
        output('`c`b`NFuß`$ballsta`^dion`0`b`c`n`NDurch `$ein `^Tor, welches in den Farben schwarz rot gold gestrichen wurde, trittst du ein in das Stadion. Hier, so scheint es dir, ist der richtige Ort für ein kleines Spielchen zwischen Freunden oder das übliche Gerede samt dem Geheimnissen des Abseits, welches wohl nur wenige Frauen je wirklich verstehen werden. Der Rasen ist grün, der Himmel blau und nicht viel – außer der Ausstieg der Lieblingsmannschaft und ein Hörsturz durch die Vuvuzelas - könnte die Stimmung hier `$wohl `Ntrüben.`0`n`n');
        addcommentary(false);
        viewcommentary("ooc_football","Tippen",30,"tippt");

        addnav('OOC - Raum','ooc_area.php?op=ooc');
				addnav('Diskussionsraum','ooc_area.php?op=diskus');
        addnav('RP-Suche ','ooc_area.php?op=brett');
        addnav('MRPG-Planungsbereich','ooc_area.php?op=mrpg');

        if($session['user']['alive'])
        {
                addnav('Zurück','ooc_area.php');
        }
        else
        {
                addnav('Zurück','shades.php');
        } 
}
break;

case 'mrpg': {
$str_out = get_title ('MRPG-Planungszimmer');

$str_out .= Weather::get_weather_text('MRPG');

output($str_out);
        addcommentary(false);
        viewcommentary("ooc_mrpg","Rufen",30,"ruft");

        addnav('Schwarzes Brett','ooc_area.php?op=mrpg_brett');
        addnav('Der Chronist','chronist.php');
        addnav('');
        addnav('OOC - Raum','ooc_area.php?op=ooc');
        addnav('RP-Suche','ooc_area.php?op=brett');
		addnav('Bio-Hilfe','ooc_area.php?op=biobrett');
        addnav('Diskussionsraum','ooc_area.php?op=diskus');

        if($session['user']['alive'])
        {
                addnav('Zurück','ooc_area.php');
        }
        else
        {
                addnav('Zurück','shades.php');
        }
}
break;

case 'mrpg_brett': {
$str_out = get_title ('MRPG-Planungszimmer');

output('`c`b`ISchwarzes Brett`0`b`c`nText`n`n');
        board_view('mrpg',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,'Folgende Nachrichten hängen am Brett:','Es befinden sich keine Nachrichten am Brett',true, true, false, true);
        output('`n`n');
        board_view_form('Aufhängen','Auch du kannst eine Nachricht hinterlassen:`n');
        output('`n`n');
        if ($_GET['board_action'] == "add")
        {
                if (board_add('mrpg',180,1) == -1)
                {
                        output('`4Du hast doch schon einen Zettel aufgehängt, das sollte wirklich reichen.`n`n');
                }
                else
                {
                        redirect("ooc_area.php?op=mrpg_brett");
                }
        }

        addnav('MRPG-Planungsbereich','ooc_area.php?op=mrpg');
        addnav('Der Chronist','chronist.php');
        addnav('');
        addnav('OOC - Raum','ooc_area.php?op=ooc');
        addnav('RP-Suche','ooc_area.php?op=brett');
		addnav('Bio-Hilfe','ooc_area.php?op=biobrett');
        addnav('Diskussionsraum','ooc_area.php?op=diskus');

        if($session['user']['alive'])
        {
                addnav('Zurück','ooc_area.php');
        }
        else
        {
                addnav('Zurück','shades.php');
        }
}
break;      


default:
  {


addnav('');
addnav('O?OOC-Raum','ooc_area.php?op=ooc');
addnav('D?Diskussionsraum','ooc_area.php?op=diskus');
addnav('R?Raum des Lernens','library.php?op=rp_train');
addnav('P?RP-Suche','ooc_area.php?op=brett');
addnav('Bio-Hilfe','ooc_area.php?op=biobrett');
addnav('MRPG-Planungsbereich','ooc_area.php?op=mrpg');
//addnav('');
//addnav('`NSt`$ad`^ion','ooc_area.php?op=football');
addnav('');
addnav('Waldmonster-Liste','ooc_area.php?op=creatures');
addnav('Ruhmeshalle','hof.php');
addnav('');
addnav('a?Goldpartner','goldpartner.php');

    if ($session['user']['dragonkills']>1 || $session['user']['level']>5)
    {
        addnav('Urlaub');
        addnav('Seeehr lange Reise (Urlaubsmodus)','vacation.php');
    }
addnav('');
if($Char->alive==0)
{
addnav('Z?Zurück zu den Schatten','shades.php');
}

else
{
addnav('Z?Zurück zum Stadtzentrum','village.php');
addnav('M?Zum Marktplatz','market.php');
}

$str_output .= get_title('OOC-Bereich').'`tDu betrittst durch ein schlichtes Tor eine Zone, die dir seltsam fremd und dennoch bekannt vorkommt. Fast scheint es dir, als könntest du hier sein, was du wirklich bist und auch Begriffe wie Internet, Globale Erderwärmung und Coca Cola machen plötzlich in wieder einen Sinn.`n
Dies ist also die OOC-Zone, der Bereich, der für Angelegenheiten außerhalb des Rollenspiels und deines Charakters reserviert ist. Hier findest du neben einem OOC-Raum für Gespräche ebenfalls einen Diskussionsraum, in dem du Vorschläge für das Spiel machen kannst und für Neulinge bietet der Raum des Lernens einen Platz, an dem sie ungestört herumprobieren dürfen.`n
Auch die Suche nach RP-Partnern findet hier ihr neues Zuhause, ebenso die Ruhmeshalle und die Waldmonsterliste.`0`n`n`n';

  }
}
output($str_output);

page_footer();
?>
