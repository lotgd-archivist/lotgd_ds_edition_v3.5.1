<?php

//@author Eleya für atrahor.de, Texte by Japheth

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

page_header('Der Bäcker');

if ($Char->alive==0)
{
    redirect('shades.php');
}
if($Char->prangerdays>0){
    redirect("pranger.php");
}

$str_out = get_title ('`^D`/e`tr `;Bä`Scker');

$str_out .= '`^Au`/c`th wenn vor dem alten Haus nicht ein großes Schild mit der Aufschrift `i"Karlons frische Brote"`i stehen würde, würde man das Haus als Backstube erkennen, denn der frische Duft nach Brot kommt einem schon früh entgegen. Von einem plötzlichen Hungergefühl geleitet betrittst du die Backstube. Zu jeder Jahreszeit herrscht hier glühende Hitze, die von den großen Backofen her rührt, die du im Hintergrund sehen kannst und die Gesellen immer wieder frische Brote mit langen Schiebern wuchten. Vor einem großen Tisch sitzt ein recht beleibter Mann mit weißer Schürze. Du denkst dir, dass das Karlon sein muss. Du näherst dich dem Tisch und betrachtest die Auslage an verschiedenen Broten, Brötchen und allerlei Kuchen und Backwaren. Wie kannst du dich da entscheiden? 
`n`nKarlon lässt dir nicht viel Zeit, lange zu überlegen. Mit seiner volltönenden Stimme fragt er dich barsch: `i`/"Was darf’s denn sein?"`i
`n`tDu wendest dich lieber erst einmal ab und betrachtest das Geschehen in der Backstube. Dabei kommt dir sicher noch eine Idee.`n`n`0';

output($str_out);

viewcommentary('bakery','Hinzufügen',25,'sagt');

addnav('M?Zurück zum Markt','market.php');

page_footer();
?>

