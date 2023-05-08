<?php
require_once('common.php');

checkday();
require_once(LIB_PATH.'board.lib.php');
page_header('Debattierräume');

if ($_GET['op']=="diskus")
{
        output("`c`b`IDiskussionsraum`0`b`c`nDer Debattierraum liegt vor Dir!`n
        Hier bekommt das Volk Gehör und die Admins hören sich Wünsche, Anregungen und Beschwerden an, solange sie entsprechend formuliert sind. Bitte denkt an einen angemessenen Umgangston!  ");
        output("Wie Dir scheint ist schon eine rege Diskussion im Gange!`n`n");
        addcommentary(false);
        viewcommentary("rat","Rufen",30,"ruft");

        addnav('OOC - Raum','ooc.php?op=ooc');
        addnav('RP-Suche','ooc.php?op=brett');

        if($session['user']['alive'])
        {
                addnav("Zurück","dorfamt.php");
        }
        else
        {
                addnav("Zurück","shades.php");
        }

}

else if ($_GET['op']=="ooc")
{
        output("`c`b`IOOC-Raum`0`b`c`nKomischer Name, denkst Du Dir, als du die Tür zu diesem Raum aufstösst!`n");
        output("Überall an den Wänden stehen leuchtende Scheiben und einige dir bekannte und weniger
        bekannte Gesichter starren wie gebannt darauf und klimpern auf bemalten Brettern herum - seltsame Runen.`n`n");
        output("Du hast den OOC Raum betreten. Wenn Du Gespräche führen möchtest, die sich außerhalb deines Charakters befinden,
        so führe sie bitte hier! Sollten sich andere Mitspieler irgendwo anders OOC unterhalten, dann weise sie bitte freundlich
        per Brieftaube darauf hin, dass dies hier der richtige Ort dafür wäre!`n`n");
        addcommentary(false);
        viewcommentary("OOC","Tippen",30,"tippt");

        addnav('Diskussionsraum','ooc.php?op=diskus');
        addnav('RP-Suche ','ooc.php?op=brett');

        if($session['user']['alive'])
        {
                addnav("Zurück","dorfamt.php");
        }
        else
        {
                addnav("Zurück","shades.php");
        }
}

else if ($_GET['op']=="brett")
{
        page_header('RP-Suche');
        output('`c`b`IRollenspiel-Suche`0`b`c`nAn der Wand des OOC-Raums entdeckst du ein kleines, schwarzes Brett. Eine kleine Tafel informiert dich darüber, dass du hier OOC nach Spielpartnern suchen kannst, jeglicher anderer Spam jedoch gelöscht wird.`n`n');
        board_view('ooc',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,'Folgende Nachrichten hängen am Brett:','Es befinden sich keine Nachrichten am Brett',true, true, false, true);
        output('`n`n');
        board_view_form('Aufhängen','Auch du kannst eine Nachricht hinterlassen:`n');
        output('`n`n');
        if ($_GET['board_action'] == "add")
        {
                if (board_add('ooc',14,1) == -1)
                {
                        output('`4Du hast doch schon einen Zettel aufgehängt, das sollte wirklich reichen.`n`n');
                }
                else
                {
                        redirect("ooc.php?op=brett");
                }
        }
        addnav('Diskussionsraum','ooc.php?op=diskus');
        addnav('OOC - Raum','ooc.php?op=ooc');

        if($session['user']['alive'])
        {
                addnav("Zurück","dorfamt.php");
        }
        else
        {
                addnav("Zurück","shades.php");
        }
}

page_footer();
?>