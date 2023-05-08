<?php

//@author Eleya für atrahor.de, Texte by Dériel

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

page_header('Der Bücherladen');

if ($Char->alive==0)
{
    redirect('shades.php');
}
if($Char->prangerdays>0){
    redirect("pranger.php");
}

$str_out = get_title ('`TD`Ye`tr `yB`&ücherl`ya`td`Ye`Tn');

$str_out .= '`&S`yc`thon aus einiger Entfernung macht ein nostalgisches Ladenschild mit einer darauf abgebildeten Eule auf den ortsansässigen Bücherladen aufmerksam. In goldenen, abblätternden Lettern hat man kunstvoll über der Eingangstür den Namen des Geschäftsführers `YL`ti`yb`&er`yi`tu`Ys `tverewigt; ein freundlicher, alter Herr mit grauem Haar und großen, blasssilbernen Augen, dessen einzige und größte Leidenschaft seine gesammelten Werke sind.`n`n
Das kleine Schaufenster des Ladens ist staubig und lässt grade so viel Licht ins Innere des dunklen Raumes, dass man am Tage keine Kerzen entzünden muss, um die zahlreichen `SEi`Tnbän`Sde`t zu entziffern. Alles wirkt ein wenig alt, karg und wird beengt durch die vielen bis zur Decke reichenden Regale voller adäquater, literarischer Werke.`n`n
Sich selbst und der anspruchsvollen Kundschaft damit einen Gefallen entrichtend, hat der belesene Eigentümer des Ladens trotz des latenten Platzmangels ein paar große, abgenutzte `ZSe`zss`Zel`t herschaffen lassen, welche dazu einladen nach Herzenslust in seinem Sammelsurium zu schmökern ehe man (vielleicht) eine Kaufentscheidung trif`yf`&t.`n`n`0';

output($str_out);

viewcommentary('bookstore','Hinzufügen',25,'sagt');

addnav('M?Zurück zum Marktplatz','market.php');

page_footer();
?>
