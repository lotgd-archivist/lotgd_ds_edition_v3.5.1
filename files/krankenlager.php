<?php

require_once 'common.php';

checkday(); //Check auf Newday. Nicht zwingend notwendig, aber doch irgendwie merkwürdig, wenns nicht da ist^^

addcommentary(); //Wichtig für die Kommentarsektion

$show_invent = true; //Inventar wird angezeigt. Wenn man das nicht will, kann es weggelassen werden

if ($Char->alive==0)
{
    redirect('shades.php');
}
if($Char->prangerdays>0){
    redirect("pranger.php");
}
//Wenn der Char tot bzw. am Pranger ist, wird er zurückgeschickt, wo er hingehört  Muss nicht sein, wenn der Char von diesen Orten sowieso nicht hinkommen kann, aber schadet auch nicht

page_header('Krankenlager'); //Wie es schon sagt, der Page Header. Nicht der Titel, der dann auch im Beschreibungstext steht, sondern der, der oben im Browser angezeigt wird. Bitte keine Farbcodes verwenden 

$str_out = get_title ('`4K`Ar`,a`Nn`Sken`Sl`Na`,g`Ae`4r`0'); //Das ist jetzt der Titel des Beschreibungstextes, der darf eingefärbt werden  Es gibt noch andere Variablen außer $str_out, aber das ist grade die, die ich in meiner Datei abgespeichert hab.

$str_out .= '`4So`Agl`,ei`Nch `Snach dem Betreten des schlichten Gebäudes schlägt er einem entgegen, dieser typische Geruch von Krankheit und Verfall, nur wenig gemindert durch den Duft frischer Kräuter, welche in einer kleinen angebauten Kammer nahe dem Eingang lagern, neben einem spärlichen Vorrat an Verbandsmaterial und anderen Utensilien. Ein kurzer schmaler Gang führt sogleich zu einem Saal, in dem Betten stehen, meist nur durch Vorhänge voneinander abgetrennt. Hierher kommen sie also, die Gebrechlichen und suchen nach Heilung, manchmal sogar nach einem Wunder. Wer versucht ihnen zu helfen, ist hier gern sehen, auch wenn diese Person vielleicht nicht die gleichen Qualifikationen wie der Heiler aus dem Wald und Golinda `Nbi`,et`Aen ka`4nn.`0`n`n';
output($str_out); //Der Beschreibungstext. Farbcodes, Zeilenumbrüche, Anführungszeichen (in dem Fall hier bitte die doppelten, nicht die einfachen) möglich, am Ende am besten ein `0 und ein, zwei Zeilenumbrüche für die Optik 


viewcommentary('krankenlager','Hinzufügen',25,'sagt'); //Fügt Kommentarsektion hinzu, hier bitte eindeutigen Namen eingeben, der Rest kann so gelassen werden, wie er ist.

addnav('u?Zurück zum Stadttor','dorftor.php'); //Addnavs, also Links zu den verschiedenen Orten. Buchstabe + ? Bezeichnet den gewünschten Hotkey, hier muss Groß- und Kleinschreibung beachtet werden. Danach der Name des Links, dann die Datei, die verlinkt wird.

page_footer();
?>