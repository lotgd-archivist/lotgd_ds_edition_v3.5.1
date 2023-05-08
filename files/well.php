<?php

// 21072004

/*
* Author:        Chaosmaker
* Email:                webmaster@chaosonline.de
*
* Purpose:        Well for throwing keys in
*
* Features:        Throw key into well, chat
*
* Keys thrown into this well are lost
*/

require_once("common.php");
addcommentary();
checkday();

page_header('Der Dorfbrunnen');

// Schlüssel wegwerfen
if ($_GET['op']=='throwkey')
{
        if($_GET['id'] > '')
        {
                output('`0Du wirfst einen deiner unbenötigten Schlüssel in den Brunnen und wartest lange auf das Platschen.`nDer Brunnen muss sehr tief sein.');
                db_query('DELETE FROM keylist WHERE id='.(int)$_GET['id']);
        }
        else
        {
                if($session['user']['house'])
                {
                        $sql='SELECT k.id, k.value1, k.owner, a.name
                                FROM keylist k
                                LEFT JOIN accounts a ON a.acctid=k.owner
                                WHERE value1='.(int)$session['user']['house'].'
                                AND type=0
                                ORDER BY id';
                        $result=db_query($sql);
                        $int_keys=db_num_rows($result);
                        if($int_keys>9) //erstmal ohne Berücksichtigung der Ausbau-Besonderheiten
                        {
                                $str_out='<table border=0 bgcolor="#999999">
                                <tr class="trhead"><th>Nummer</th><th>Besitzer</th><th>Aktion</th></tr>';
                                for($i=1;$i<=$int_keys;$i++)
                                {
                                        $row=db_fetch_assoc($result);
                                        $str_out.='<tr class="'.($i%2?'trdark':'trlight').'">
                                        <td align="center">'.$i.'</td>
                                        <td>';
                                        if($row['owner']==0 || $row['owner']==$session['user']['acctid'])
                                        {
                                                $str_out.=($row['owner']==0?'`2hat niemand':'`@hast du selbst').'`0</td>
                                                <td>'.create_lnk('Schlüssel beseitigen','well.php?op=throwkey&id='.$row['id']);
                                                $int_freekeys++;
                                        }
                                        else
                                        {
                                                $str_out.='ist bei '.$row['name'].'`0</td>
                                                <td>&nbsp;';
                                        }
                                        $str_out.='</td></tr>';
                                }
                                $str_out.='</table>`n`n';
                                output($str_out);
                                if($int_freekeys) output('`0Wiedereinmal stellst du fest, dass du weniger Freunde in der Stadt als Schlüssel für dein Haus hast. Also überlegst du, dich der unnützen Schlüssel zu entledigen.`n`n');
                        }
                        else
                        {
                                output('`0Du zählst deine Schlüssel durch, es sind genau '.$int_keys.'. Glaubst du wirklich, da ist einer zuviel?');
                        }
                }
                else
                {
                        output('`0Du hast doch gar kein eigenes Haus, von dem du einen Schlüssel wegwerfen könntest. Oder wolltest du etwa einen fremden Schlüssel wegwerfen?');
                }
                addnav('Nein!!','well.php');
        }
}

elseif ($_GET['op']=='throwgold' && !isset($_GET['comscroll']) && $_POST['section']==''){
        output('`0Du wirfst eines deiner Goldstücke hinein und zählst die Sekunden bis zum Platsch. Nach `I'.(e_rand(1,10)/2).'`0 Sekunden hörst du es.`n`nWieviele Goldstücke wohl da unten liegen?');
        $session['user']['gold']--;
        savesetting('gold_in_well',getsetting('gold_in_well',0)+1);
        if($session['daily']['well_dig']<5)
        {
                output('`n`0Du könntest ja runterklettern und nachschauen, ob du einen kleinen Schatz findest.');
                addnav('Nachschauen','well.php?op=dig');
                if($session['user']['exchangequest']==21)
                {
                        output('`n`0Und was für dich besonders interessant ist, in einem Brunnen gibt es Wasser.');
                }
        }
        else
        {
                output('`n`0Für heute hast du aber genug vom Brunnentauchen.');
        }
}

elseif ($_GET['op']=='dig'){
        output('`0Du fühlst dich unbeobachtet, also entledigst du dich deiner Kleider und steigst hinab in den Brunnen.`n`n');
        $session['daily']['well_dig']++;
        switch(e_rand(1,10)){
        case 1:
        case 2:
        case 3:
        case 6:
                $sql='SELECT name FROM accounts WHERE loggedin=1 AND alive=1 AND sex!='.$session['user']['sex'].' ORDER BY RAND() LIMIT 1';
                $result=db_query($sql);
                if(db_num_rows($result)>0) {
                        $row=db_fetch_assoc($result);
                        $name=$row['name'];
                }
                else {
                        $name=($session['user']['sex']?'`2S`@al`6a`qto`Qr':'`5Violet');
                }
                output('`0Gerade als du anfangen willst nach Goldstücken zu suchen, hörst du von oben ein `m"Ups, Tschuldigung"`0. Am Brunnenrand kannst du gerade noch das Gesicht von '.$name.'`0 erkennen, '.($session['user']['sex']?'der':'die').' dich wohl entdeckt hat und sich nun rasch entfernt.`n`n`qWie peinlich...');
                addnews($session['user']['name'].'`# wurde beim Nacktbaden im Dorfbrunnen erwischt!');
                $session['user']['charm']--;
                break;
        case 5:
                output('`0An den glitschigen Steinen findest du nur sehr schlecht Halt. Es kommt wie es kommen muss und du rutscht ab. Du verlierst Lebenspunkte und einen Waldkampf.');
                $session['user']['hitpoints']=max(1,$session['user']['hitpoints'] >> 1);
                $session['user']['turns']=max(0,$session['user']['turns']-1);
                if($session['user']['exchangequest']==21)
                {
                        output('`nWie zum Geier willst du denn jetzt wieder aus dem Brunnen rauskommen?');
                        addnav('`%Inventar durchsuchen`0','exchangequest.php');
                }
                break;
        case 8:
        case 9:
        output('`0Als du so nach unten schaust, siehst du eine rötlich-golden schimmernde Kugel. Sie erinnert dich an ein Märchen, in dem es Glück bringt, wenn man an der Kugel reibt.`n');
        addnav('Die Kugel reiben','well.php?op=kugel');
                break;
        case 10:
                $gold=getsetting('gold_in_well',0);
                savesetting('gold_in_well',0);
                output('`0Als du so nach unten schaust siehst du ein paar Goldmünzen im Wasser schimmern. Insgesamt kannst du `^'.$gold.' `0Goldstücke einsammeln. Ist das nicht toll?`nSchnell steigst du wieder nach oben, nicht dass dich noch jemand erwischt...');
                $session['user']['gold']+=$gold;
                break;
        default:
                output('`0Außer, dass du jetzt ziemlich feucht bist, hat dir der Ausflug nichts gebracht.`nVerdammt, wo ist denn bloß dein Handtuch?');
        }
}

elseif($_GET['op']=='kugel') // Prust! Der muß mit rein! Gefunden bei http://drache.air.hl-users.com/logd/source.php?url=/logd/dorfbrunnen.php
{
        output('`0Du reibst an der `iKugel`i, die zu leuchten beginnt. Du denkst dabei an deinen Schatz und hoffst das alles in Ordnung ist.');
        if($session['daily']['well_dig']==2) insertcommentary(1,'/msg Aus dem Brunnen dringen merkwürdig schabende und glucksende Geräusche. Wenig später ist ein Stöhnen zu hören. Dann wird es gespenstisch still.','village');
}

elseif($_GET['op']=='water') //hier gibts Wasser für die Küche
{
        if($session['user']['turns']>0 && item_count('tpl_id="trinkwasser" AND owner='.$session['user']['acctid'])<5)
        {
                output('`0Du löst die Winde und lässt den Eimer hinabsausen, um ihn anschließend mühevoll wieder nach oben zu ziehen und in dein Tragegefäß zu entleeren.
                `nDu erhältst eine Portion Trinkwasser.');
                if($session['user']['hitpoints']<($session['user']['level']*3))
                {
                        output('`n`0Einen Schluck Wasser trinkst du sofort und fühlst dich etwas frischer.');
                        $session['user']['hitpoints']++;
                }
                item_add($session['user']['acctid'],'trinkwasser');
                addnav('Wasser heim bringen','houses.php?op=enter');
        }
        elseif($session['user']['turns']==0)
        {
                output('`0Leider bist du schon viel zu müde, um heute noch Wasser zu schöpfen.');
                addnav('Ab ins Bett','houses.php?op=enter');
        }
        else
        {
                output('Der Brunnen scheint ausgetrocknet zu sein. Ob das vielleicht daran liegt, dass du mehr Wasser mit dir rumschleppst als du brauchst? Wenn das jeder so macht, wo soll es denn herkommen?');
        }
}

else
{
        output('`c`b`(D`)e`7r `eD`fo`0rfbr`fu`en`7n`)e`(n`c`b`n`(I`)dy`7l`el`fi`0sch wirkt dieser Ort am Rande des Wohnviertels, fernab der alltäglichen Stadthektik und des immer wiederkehrenden Trotts. Durch die zahlreichen Gassen findet man zu dem steinernen Platz, auf dem sich mittig ein alt wirkender Brunnen erkennen lässt. Ein aus morschem Holz zusammengezimmertes Gerüst wurde über dem Schacht erbaut, sodass die Bewohner mittels Eimer und der am Gerüst befindlichen Seilwinde Wasser schöpfen können. Einige aufgestellte Bänke laden zum Ausruhen ein, wenngleich es mindestens ebenso interessant scheint, zu erkunden, wie tief der Brunnenschacht wohl s`fe`ei`7n `)ma`(g..`n`n`0Einige Leute sitzen hier und unterhalten sich.`n');
        viewcommentary('well','Mit Umstehenden reden:',25,'sagt');
        addnav('s?Wasser schöpfen','well.php?op=water',true);
        if ($session['user']['gold']>1) addnav('G?1 Gold hineinwerfen','well.php?op=throwgold');
        addnav('Schlüssel wegwerfen','well.php?op=throwkey',false,false,false,false);
        addnav('E?Zur alten Eiche','schatzsuche.php');
}

addnav('Zurück');
if(isset($_GET['op'])) addnav('B?Zum Brunnenplatz','well.php');
addnav('W?Ins Wohnviertel','houses.php');
addnav('d?Zum Stadtzentrum','village.php');
addnav('M?Zum Marktplatz','market.php');

page_footer();
?>