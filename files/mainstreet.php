<?php

//@author Eleya für atrahor.de, Texte by Japheth

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

page_header('Die Hauptstraße');

if ($Char->alive==0)
{
    redirect('shades.php');
}
if($Char->prangerdays>0){
    redirect("pranger.php");
}

$str_out = get_title ('`&Di`se H`eauptstra`sß`&e');

$str_out .= '`eD`si`&e `7Hauptstraße zieht sich wie eine große Schlange durch Atrahor. Sie verbindet die kleinen Gassen und Straßen und führt schließlich zu den großen Plätzen der Stadt. Anders als all die kleinen Straßen ist die Hauptstraße sehr sorgfältig gepflastert worden und kleine Blumenbeete oder ordentlich geschnittene Sträucher finden sich neben der Straße. Sie wird von Straßenlaternen gesäumt, die jeden Abend von einem Wächter angezündet werden. In gewissen Abständen findet sich auch immer wieder eine Bank, auf der sich jeder setzen kann und seine müden Füße ausruhen, denn der Verkehr auf der Straße kann ganz schön ermüdend sein. All die Ochsenkarren, feinen Kutschen und billigen Mietwagen scheinen die Straße ganz für sich zu beanspruchen. An einem arbeitsreichen Tag kann es schon einmal regelrechten Stau geben. Doch dafür ist die Hauptstraße dafür gut bewacht und kaum ein Dieb wird sich so offen seinem Gewerbe widmen wol`&l`se`en.`n`n`0';

output($str_out);

viewcommentary('mainstreet','Hinzufügen',25,'sagt');

addnav('W?Zurück zum Wohnviertel','houses.php');
addnav('Zum Stadtplatz','village.php');
addnav('Zum Marktplatz','market.php');

page_footer();
?>

