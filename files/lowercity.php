<?php

/**
 * Die Unterstadt - Katakomben etc.
 *
 * @author  Eleya für atrahor.de (mit Unterstützung von Japh, Cally und Zannah)
 * @version Release: 2013-10-30
 */
require_once 'common.php';

$show_invent = true;
$filename = basename(__FILE__);
addcommentary();
checkday();

if ($Char->alive == 0) {
    redirect('shades.php');
}
if ($Char->prangerdays > 0) {
    redirect("pranger.php");
}

$session['user']['specialinc'] = '';
$session['user']['specialmisc'] = '';

switch ($_GET['op']) {
    case 'gruft':
        page_header('Die Gruft');
        $str_output .= get_title('`JD`2i`6e G`Tru`;ft') . '`JEin w`2en`6ig abse`6its vo`Tn dem `;weitläufigen Gräberfeld ragt ein größeres Gebäude hervor, welches aufgrund seiner düsteren Ausstrahlung von den meisten gemieden wird. Es hat einen pyramidenförmigen Grundriss, ist allerdings nach oben hin abgeflacht. Die Mauern sind aus dicken, dunkelgrauen Steinen, die selbst im strahlenden Sonnenschein noch bedrohlich wirken. Fenster besitzt es keine, nur ein großes Flügeltor, welches sich mit einem lauten Knarren öffnet – wenn denn jemand es wagt, hineinzuschauen. Die Gruft besitzt keinerlei Schmuck bis auf zwei Statuen, die das Tor "bewachen", menschliche Gestalten mit grausig-schönen Gesichtszügen und erhobenen Schwertern, die sich über dem Tor kreuzen. Auch das Innere ist schmucklos gehalten; der Raum wird auch bei weit geöffneten Toren nur spärlich erleuchtet, denn er ist nach Norden ausgerichtet. Vom Tor führen zunächst einmal einige Stufen in den eigentlichen Raum. Dort sind zwei Grabplatten in den Boden eingelassen, doch nur Eingeweihte wissen, dass hier niemals wirklich jemand bestattet wurde. Vielmehr lassen sich beide Grabplatten relativ leicht öffnen und geben einen Gang frei, der in die Katakomben Atrah`Tors fü`Shr`6t – die `2Unter`Jstadt.`n';
        output($str_output);
        viewcommentary('lowercity_gruft', 'Hinzufügen', 25);
        addnav('K?In die Katakomben', 'lowercity.php?op=katakomben');
        addnav('Z?Zum Friedhof', 'friedhof.php');
        addnav('d?Zum Stadtzentrum', 'village.php');
        break;

    case 'katakomben':
        page_header('Die Katakomben');
        $str_output .= get_title('`;Di`|e K`.at`*ako`Fmben') . '`;Versch`|ach`.tel`*te, in di`Fe Erd`fe gegrabene Gänge, in die sich nur hinein wagen sollte, wer sich dort wirklich auskennt. Meist sind sie so groß, dass ein Mensch aufrecht stehen kann, nur selten muss man sich ducken oder gar kriechen. In regelmäßigen Abständen sind Halterungen für Fackeln in die Wände geschlagen, in denen meist auch eine Fackel zum Anzünden für den bereitsteht, der sich seinen Weg durch die Gänge bahnt. Hin und wieder mündet einer der Gänge in eine größere Höhle, manchmal kann es auch sein, dass man unterwegs eine weitläufigere Halle passiert. Wer diese Gänge angelegt hat, das ist heute längst in Vergessenheit geraten. Waren es Zwerge, die dieses Gebiet einst besiedelt hatten, bevor die Menschen es für sich beanspruchten? Minenarbeiter auf der Suche nach wertvollen Metallen? Gesetzlose, die einen Unterschlupf schaffen wollten und ihn im Laufe der Jahre`f immer weiter `*au`.sge`|dehn`;t haben?`n';
        output($str_output);
        viewcommentary('lowercity_katakomben', 'Hinzufügen', 25);
        addnav('H?In die Höhle', 'lowercity.php?op=hoehle');
        addnav('S?Zum unterirdischen See', 'lowercity.php?op=see');
        addnav('Zurück');
        addnav('K?Zum Keller', 'lowercity.php?op=keller');
        addnav('G?Zur Gruft', 'lowercity.php?op=gruft');
        addnav('d?Zum Stadtzentrum', 'village.php');
        break;

    case 'hoehle':
        page_header('Die Höhle');
        $str_output .= get_title('`SD`;i`(e `)Hö`(h`;l`Se`0') . '`SE`;i`(n`)er von mehreren größeren Räume am Ende der Katakombengänge. Hier findet eine größere Gruppe von Menschen problemlos Platz, und so könnte sie sich sicherlich als Versammlungsraum für eine Gemeinschaft von Gesetzlosen dienen, ebenso als Schlafplatz für eine Sippe. Alternativ könnte man sie aber auch als Lagerplatz für Schmuggelware nutzen – vorausgesetzt, man ist sich sicher, dass man sie auch wiederfin`(d`;e`St...`0`n';
        output($str_output);
        viewcommentary('lowercity_hoehle', 'Hinzufügen', 25);
        addnav('Z?Zu den Katakomben', 'lowercity.php?op=katakomben');
        addnav('S?Zum unterirdischen See', 'lowercity.php?op=see');
        addnav('Zurück');
        addnav('d?Zum Stadtzentrum', 'village.php');
        break;

    case 'see':
        page_header('Der See');
        $str_output .= get_title('`SD`;e`Cr S`;e`Se`0') . '`SD`;i`Ce Feuchtigkeit ist spürbar in dieser Grotte, an Boden und Decke haben sich Tropfsteine gebildet und man hört immer wieder das Geräusch von Tropfen, die auf dem Boden aufschlagen. Dominierend ist jedoch der große See, der sich vermutlich im Laufe der Jahrhunderte auf dem felsigen Grund angesammelt hat, mehrere Mannslängen lang und vermutlich so tief, dass man in der Mitte nicht mehr stehen kann. Wird die Höhle durch Fackelschein erleuchtet, so ergibt sich eine ganz besondere, geheimnisvolle Atmosphäre. Weniger geheimnisvoll ist jedoch der penetrante, modrige Geruch, der hier, wo Frischluft Mangelware ist, von dem Gewässer ausge`;h`St.`0`n';
        output($str_output);
        viewcommentary('lowercity_see', 'Hinzufügen', 25);
        addnav('Z?Zu den Katakomben', 'lowercity.php?op=katakomben');
        addnav('H?Zur Höhle', 'lowercity.php?op=hoehle');
        addnav('Zurück');
        addnav('d?Zum Stadtzentrum', 'village.php');
        break;

    case 'keller':
        page_header('Der Keller');
        $str_output .= get_title('`;D`Te`Sr Kell`Te`;r`0') . '`;Ein`Te `Smorsche Treppe führt hinunter in den Keller des verlassenen Hauses, sie knirscht erschreckend bei jedem Schritt, dennoch scheint sie jedes Gewicht zu halten, zumindest vorerst. Glücklicherweise fällt durch die schlecht gezimmerten Bodendielen Licht in den Raum und hüllt ihn so in ein dämmriges Zwielicht, welches einen zumindest die nähere Umgebung erkennen lässt. Davon, dass hier ehemals Lebensmittel gelagert wurden, zeugen nur noch eine halb verrottete Kartoffelkiste und schief hängende Regale an den Wänden. Nichts Besonderes befindet sich hier und doch, warum liegt da ein Teppich auf dem sonst nur von Schmutz bedeckten Boden?
Unter ihm verbirgt sich eine Luke, die zu einem geheimen Gang tief in die Erde führt, vielleicht sogar direkt in die Unter`Tsta`;dt?`0`n`n';
        output($str_output);
        viewcommentary('lowercity_keller', 'Hinzufügen', 25);
        addnav('H?Hinabsteigen', 'lowercity.php?op=katakomben');
        addnav('Z?Zurück nach oben', 'slums.php?op=oldhouse');
        addnav('G?Zur Dunklen Gasse', 'slums.php');
        break;

    default:
        page_header('Whatever');
        $str_output .= get_title('lalala`0') . 'Das ist ein Ort, den niiieemand betreten wird :D`0`n';
        output($str_output);
        addnav('H?In die Höhle', 'lowercity.php?op=hoehle');
        addnav('S?Zum unterirdischen See', 'lowercity.php?op=see');
        addnav('Zurück');
        addnav('G?Zur dunklen Gasse', 'slums.php');
        addnav('Z?Zum Friedhof', 'friedhof.php');
        addnav('d?Zum Stadtzentrum', 'village.php');
        viewcommentary('lowercity', 'Hinzufügen', 25);
        break;
}
page_footer();
