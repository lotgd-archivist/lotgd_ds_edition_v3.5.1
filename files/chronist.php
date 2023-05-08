<?php

//@author Eleya für atrahor.de, Texte by Callyshee

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

page_header('Der Chronist');

if ($Char->alive==0)
{
    redirect('shades.php');
}
if($Char->prangerdays>0){
    redirect("pranger.php");
}

$str_out = get_title ('`YD`;er `TC`Shro`Tni`;s`Yt`0');

$str_out .= Weather::get_weather_text('Chronist');

output($str_out);

viewcommentary('chronist','Hinzufügen',25,'sagt');

addnav('Z?Zurück zum Rathaus','dorfamt.php');
addnav('Zum Planungsbereich','ooc_area.php?op=mrpg');

page_footer();
?>
