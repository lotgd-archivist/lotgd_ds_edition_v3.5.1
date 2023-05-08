<?php

//@author Eleya für atrahor.de, Texte by Callyshee

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

page_header('Die Gerber');

if ($Char->alive==0)
{
	redirect('shades.php');
}
if($Char->prangerdays>0){
	redirect('pranger.php');
}

$str_out = get_title ('`SD`Ti`;e Gerb`Te`Sr');

$str_out .= '`SI`Tn `;einer Seitengasse, tief im zwielichtigen Viertel von Atrahor, haben sich die Gerber mit ihrem geruchsintensiven Gewerbe niedergelassen. Selten verirrt sich ein Kunde direkt hierher, bevorzugen jene Herrschaften es doch, bereits fertige Ware bei dem Händler ihrer Wahl zu erstehen, ohne nähere Bekanntschaft mit dem schmutzigen Teil der Arbeit zu machen.
So bleiben zumindest die Geheimnisse alter Meister gewahrt, während Lehrlinge und Gesellen keine Störung bei ihrer Arbeit widerfährt. Von dieser gibt es wahrlich genug, gegerbtes Leder muss gefärbt, Häute von Fleisch befreit und schließlich mit Salzen behandelt werden, damit das Leder haltbar blei`Tb`St.`n`n`0';

output($str_out);

viewcommentary('tanner','Hinzufügen',25,'sagt');

addnav('G?Zur Dunklen Gasse','slums.php');

page_footer();
?>
