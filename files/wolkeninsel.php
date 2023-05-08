<?php

//Idee und Umsetzung
//Morpheus aka Apollon
//für lotgd.at
//Mail to: Apollon@magic.ms
//
//Die Insel ist für die pool.php gemacht, kann aber auch ganz einfach in einem anderen See im Garten sein.
//Instalation: In den Hauptordner kopieren, pool.php öffnen und suchen:
//addnav("Ufer des Sees");
//dahinter einfügen:
//addnav("W?Zur Wolkeninsel","wolkeninsel.php");
//ansonsten wo gewünsct verlinken

require_once 'common.php';

page_header('Die Wolkeninsel');
output('`c`b`}D`Ii`te `yW`golkeni`yn`ts`Ie`}l`0`b`c`n');
if ($_GET['op']=='')
{
        addnav('Weiter','wolkeninsel.php?op=insel');
        output('`}Du `Ige`ths`yt a`gm Ufer des Flusses entlang bis zu der kleinen, weißen Brücke, die zum anderen Ufer führt, welches ständig im Nebel liegt und deshalb vom Garten nicht gesehen werden kann.
        `nVorsichtig gehst du, Schritt für Schritt, über den Steg auf die andere Seite, als sich der Nebel lichtet und du eine Insel mit völlig anderem Wetter inmitten der Wolken e`yrb`tli`Ick`}st.`g
        `nDer Himmel über dir ist klar und blau, die Sonne scheint ');
        switch(e_rand(1,10))
        {
                case 1:
                output('`gund die Vögel singen fröhlich ihre Lieder, während kleine `kFeen `glustig dazu tanzen.`n`n');
                break;
                case 2:
                output('`gund du gehst über dieses wundervolle Fleckchen Erde, jeden Schritt genießend, zum `&Pavillon`g.`n`n');
                break;
                case 3:
                output('`gund ein `YEichhörnchen `gkreuzt deinen Weg, sieht dich verschmitzt an und läuft lustig quiekend zum nächsten Baum.`n`n');
                break;
                case 4:
                output('`gund zwei `&Schwäne `gwatscheln verliebt über die Wiese bis zum See, in dem sie schließlich gemeinsam davon schwimmen.`n`n');
                break;
                case 5:
                output('`gund eine `vEntenmutter `gführt ihre Jungen, quer über die Wiese, zu ihrer ersten Schwimmstunde zum See.`n`n');
                break;
                case 6:
                output('`gund die Luft ist klar und warm, wie an einem schönen `6Sommertag`@.`n`n');
                break;
                case 7:
                output('`gund dein `$Herz `gbeginnt höher zu schlagen bei diesem wundervollen, traumhaft schönen Anblick.`n`n');
                break;
                case 8:
                output('`gund du fühlst dich, als ob du soeben hier `6neu geboren`g worden wärst im Paradies.`n`n');
                break;
                case 9:
                output('`gund du glaubst, auf der Insel der `^Götter`g zu sein, so wunderschön und ruhig wie dieser Ort ist.`n`n');
                break;
                case 10:
                output('`gund du fühlst dich `6seelig `gund zufrieden, diesen wundervollen Ort gefunden zu haben.`n`n');
                break;
        }
}

else if ($_GET['op']=='insel')
{
        output('`}In `Ide`tr M`yit`gte der Insel steht ein kleiner Pavillon, umringt von Bäumen auf einer Wiese, durch die ein sanfter Wind weht und Geschichten erzählt von der Liebe.
        `nAm Ufer ist ein Strand aus feinem, weißem Sand der zum Baden einlädt. Der Boden unter dir scheint so sanft und weich, dass du glaubst, auf Wolken zu wandeln.
        `nÜberall blühen Blumen in den schönsten Farben und ein kleines Rinnsal bahnt sich, lustig plätschernd, seinen Weg zum See, während Du hier und da kleine Feen sehen kannst, die sich im lustigen Tanze in der Luf`yt b`tew`Ieg`}en.`g`n`n');
        if($session['user']['exchangequest']==28)
        {
                output('Du triffst die Fee wieder, der du ganz am Anfang den Edelstein gegeben hast. Da du die juwelenbesetzte Brosche trägst, erkennt sie dass du ihre Worte verstanden hast. Sie bittet dich, ihr zu einem bestimmten Baum zu folgen.`n`n');
                addnav('`%Der Fee folgen`0','exchangequest.php');
        }
        elseif($session['user']['exchangequest']>28 || $access_control->su_check(access_control::SU_RIGHT_DEBUG))
        {
                addnav('W?Tempel der Weisen','exchangequest_temple.php');
        }
        addcommentary();
        viewcommentary('wolkeninsel','Hier flüstern:`n',20,'flüstert');
        addnav('Wandern');
        addnav('G?Zum Garten','gardens.php');
        addnav('d?Zum Stadtzentrum','village.php');
}
page_footer();
?>