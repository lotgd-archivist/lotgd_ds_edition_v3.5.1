<?php

//@author Eleya für atrahor.de, Texte by Callyshee

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

page_header('Der Barbier');

if ($Char->alive==0)
{
	redirect('shades.php');
}
if($Char->prangerdays>0){
	redirect('pranger.php');
}

$str_out = get_title ('`OD`ze`Zr Barbi`ze`Or');

$str_out .= '`OD`zu `Zbetrittst den kleinen Laden des Barbiers und wirst von ihm freundlich begrüßt. Momentan hat ein Kunde auf dem Stuhl in der Mitte des Raumes platz genommen und so schaust du dich ein wenig um.
Die üblichen Gerätschaften des Barbiers sind hier zu finden, eine Zange, Scheren, mehrere Messer und auch ein Leder zum Schärfen von diesen, schließlich soll niemand bei einer Rasur geschnitten werden.`n
Ein paar Blutegel liegen in einer Schale und dies erinnert dich daran, dass du einige Dienste des Mannes garantiert nicht in Anspruch nehmen wirst, sondern dich lieber von qualifizierteren Händen behandeln läs`zs`Ot.`n`n`0';

output($str_out);

viewcommentary('barber','Hinzufügen',25,'sagt');

addnav('M?Zurück zum Markt','market.php');

page_footer();
?>