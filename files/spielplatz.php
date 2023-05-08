<?php
/**
* @author Eleya für atrahor.de
* Der Spielplatz vom Spielkind ;)
* Mein erster eigener RP-Ort, entstanden mit Hilfe von Takehon (->N2logd) und Sa onserei. Danke! :)
*/

require_once 'common.php';
checkday();
addcommentary();

define('OPENTIME','7');
define('CLOSETIME','20');

page_header('Der Spielplatz');

$time=get_gametime_part('h');
$str_out = get_title('`qD`Ie`^r `/S`yp`&iel`fp`*l`Fa`wt`9z`0');

if ($time < OPENTIME || $time > CLOSETIME)
{
	//Nachts schlafen die Kinder natürlich alle ;)
	$str_out .= '`qM`Ii`^t`/t`yen `&im Wohnviertel befindet sich eine große, von hohen Bäumen umgebene Grünfläche, auf der allerlei Spielgeräte für Kinder aufgebaut sind. So finden sich hier zwei Schaukeln, die an einem eigens dafür aufgestellten Holzgestell befestigt worden sind, ein großer Sandkasten mit einer Rutsche und eine aus einem langen Holzbrett angefertigte Wippe. Das bei den Kindern aber eindeutig beliebteste Spielgerät ist eine große Kletterburg, die auf Pfählen steht und nur über eine wackelige Hängebrücke zu erreichen ist. Wem das immer noch nicht genug Abenteuer ist, der kann auch versuchen, auf einem der zahlreichen Bäume herumzuklettern, deren niedrigste Äste so bodennah sind, dass größere Kinder sie ohne Probleme erreichen können. Eine weitere Attraktion ist zudem der kleine Bach, der sich leise vor sich hin plätschernd über die Wiese des Spielplatzes schlängelt, nicht besonders tief, aber sicherlich ausreichend für feucht-fröhliche Wasserschlachten.`n`nNatürlich stehen am Rande des Spielplatzes ein paar Holzbänke, auf denen die Eltern sich niederlassen und ihren Kleinen beim fröhlichen Spiel zusehen können.`n`nMitten in der Nacht spielt natürlich kein Kind mehr hier. Und auch du solltest eigentlich nicht mehr unterwegs sein, ab ins Bett m`fi`*t `Fd`wi`9r!`n`n';
}
else
{
	$str_out .= Weather::get_weather_text('Spielplatz');
}
output($str_out);
viewcommentary('spielplatz','Mitspielen',25,'sagt');

addnav('Z?Zurück zum Wohnviertel','houses.php');

page_footer();
?>