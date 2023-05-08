<?php

//@author Eleya für atrahor.de, Texte by Japheth

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

page_header('Die Seilbrücke');

if ($Char->alive==0)
{
    redirect('shades.php');
}
if($Char->prangerdays>0){
    redirect("pranger.php");
}

$str_out = get_title ('`6D`Yi`Te Seilbrüc`Yk`6e');

$str_out .= '`6V`Yo`Tr dir wird der Wald langsam lichter und plötzlich fehlt jeder Baum. Du stehst am Rande einer Klippe und blickst auf eine Schlucht hinab, deren weit entferntes Ende mit einem reißenden Fluss zu bestechen weiß. Die Schlucht ist nicht sonderlich groß. Aber ein Sprung zur anderen Seite wird dir das Leben kosten. Schon willst du umdrehen und dich neuen Heldentaten in diesem Teil des Waldes widmen, als dir eine Brücke wenige Meter von dir entfernt ins Auge sticht. Sie besteht nur aus einem dicken Seil, das von einem Ende zum anderen führt und zwei weiteren Seilen darüber, die man als Geländer benutzen kann. Die Seile sehen recht alt aus und wenn du den Blick wiederum nach unten wendest, so siehst du den verräterischen Schimmer blanker Knochen auf einem Felsen. Dennoch sieht der Wald vor dir recht verheißungsvoll aus und so unbedacht wie andere vor dir bist du nicht. Wirst du also die Brücke überquere`Yn`6?`n`n`0';

output($str_out);

viewcommentary('ropeway','Hinzufügen',25,'sagt');

addnav('Z?Zurück zur Kreuzung','forest_rpg_places.php');
addnav('T?Tiefer dunkler Wald','forest_rpg_places.php?op=deepforest');

page_footer();
?>
