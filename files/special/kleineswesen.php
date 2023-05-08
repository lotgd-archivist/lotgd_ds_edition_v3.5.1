<?php

// Das kleine Wesen im Wald, Version 1.16DS
//
// Was ist das bloß für ein nerviges Geräusch ...
//
// Erdacht und umgesetzt von Oliver Wellinghoff.
// E-Mail: wellinghoff [-[at]-] gmx.de
// Erstmals erschienen auf: http://www.green-dragon.info
//
// 08.11.2007: Für Atrahor modifiziert von Salator:
// setting (enthält acctid,Name,Zähler) statt neue DB-Tabelle
// Bonus+Malus nur 1 Feld und nach account_extra_info verlagert
// Code völlig überarbeitet
//
// 30.5.2009: Für Atrahor modifiziert von Takehon:
// setting enthält serialisiertes Array mit den Infos, weil der Delimiter "," mit dem neuen Farbzeichen "`," sich nicht verträgt
//
// Vorbereitungen:
//
// *** *** ***
//
/*
ALTER TABLE `account_extra_info` ADD `kleineswesen` TINYINT NOT null DEFAULT 0;
*/
// Suche in newday.php die Zeile:
// $config = utf8_unserialize($session['user']['donationconfig']);
//
//Achtung! Code ist geändert für Dragonslayer Edition
// Füge davor ein:
/*

//Kleines Wesen: Bonus und Malus, nicht bei Wiedererweckung oder Haft
//$row_extra=user_get_aei();
if ($row_extra['kleineswesen']>0 && $_GET['resurrection']=='' && $session['user']['imprisoned']==0)
{
    $str_output.='`n`@Weil du einen fantastischen Traum hattest, erhältst du `^'.$row_extra['kleineswesen'].'`@ zusätzliche Runden für heute!`n';
    $session['user']['turns']+=$row_extra['kleineswesen'];
    $changes['kleineswesen']=0;
}
elseif ($row_extra['kleineswesen']<0 && $_GET['resurrection']=='' && $session['user']['imprisoned']==0)
{
    $str_output.='`n`$Weil du einen schlimmen Albtraum hattest, verlierst du `^'.abs($row_extra['kleineswesen']).'`$ Runden für heute!`n';
    $session['user']['turns']+=$row_extra['kleineswesen'];
    $changes['kleineswesen']=0;
}
//user_set_aei($changes);
//End Kleines Wesen

*/
// Optional: Suche in dragon.php die Zeile:
// //Handle custom titles
// if ($session['user']['ctitle'] == ""){
//
// Füge davor ein:
/*

//Kleines Wesen
//$klein = explode(',',getsetting('kleineswesen','0,Violet,0'));
$klein = utf8_unserialize(getsetting('kleineswesen',utf8_serialize(array(0,"Violet",0))));

if ($session['user']['acctid']== $klein[0])
{
    //savesetting('kleineswesen','0,Drache Poldi,0');
    savesetting('kleineswesen',utf8_serialize(array(0,"Drache Poldi",0)));
}
else */
// *** *** ***
//
//  - Version vom 10.10.2004 -

if (!isset($session))
{
    exit();
}

$session['user']['specialinc'] = 'kleineswesen.php';
//$klein = explode(',',getsetting('kleineswesen','0,Violet,0'));
$klein = utf8_unserialize(getsetting('kleineswesen',utf8_serialize(array(0,"Violet",0))));

switch ($_GET['op'])
{
case '':
    {
        if ($session['user']['acctid'] == $klein[0])
        {
            output('`@Ist das nicht ... doch, das ist der Ort, an dem du neulich verkleinert wurdest! Du erschauderst und gehst mit schnellen Schritten weiter.`n`n Wobei, jetzt bist du ja gar nicht mehr klein ... war das alles womöglich nur eine Illusion? Ein Traum? Wer weiß ...
            `n`nAuf jeden Fall möchtest du hier nicht länger verweilen.');
            $session['user']['specialinc'] = '';
        }
        else
        {
            output('`@Du ziehst durch den Wald und schwelgst in der selbstbewussten Gewissheit zukünftiger Heldentaten. In Gedanken schon fast beim Grünen Drachen angelangt, bleibst du plötzlich genervt stehen. Dieses Piepen! Wie von einer Maus! Schon seit geraumer Zeit verfolgt es dich ... Also jetzt reicht\'s aber!
            `n`@Du bückst dich, um den Boden abzusuchen. Das Piepen verstummt für einen Moment - woher kommt es? Dann wird es lauter und hektischer als je zuvor. Dort, zwischen den Blättern: ein niedliches, kleines Wesen, das kaum einen Fingernagel hoch ist und dir seltsam bekannt vorkommt. Dem Aussehen nach könnte es `#'.$klein[1].'`@ sein ... Aber ist das denn möglich?!
            `n`nWas wirst du jetzt tun?
            `n`n`@<a href="forest.php?op=mitnehmen">Wie süß! Ich nehme es mit.</a>
            `@`n`n<a href="forest.php?op=zertreten">Jetzt reicht\'s! Ich zertrete es.</a>
            `@`n`n<a href="forest.php?op=ruhe">Ich lasse es in Ruhe - so schlimm ist sein Piepen nun auch wieder nicht.</a>');
            addnav('','forest.php?op=mitnehmen');
            addnav('','forest.php?op=zertreten');
            addnav('','forest.php?op=ruhe');
            addnav('Mitnehmen','forest.php?op=mitnehmen');
            addnav('Zertreten','forest.php?op=zertreten');
            addnav('In Ruhe lassen','forest.php?op=ruhe');
            $session['user']['specialinc'] = 'kleineswesen.php';
        }
        break;
    }

case 'mitnehmen':
    {
        output('`#"Ein Kinderspiel!"`@ denkst du dir. Aber weit gefehlt! Das kleine Wesen erweist sich als schnell und wendig. Du musst dein ganzes Geschick aufbringen, um ihm bei seinen rasanten Haken zu folgen. Gebückt eilst du von einem Busch zum nächsten - und von einem Baum zum anderen.
        `n`nDein Ehrgeiz ist geweckt!`n`n');
        switch (e_rand(1,10))
        {
        case 1:
        case 2:
        case 3:
        case 4:
            output('`@Minuten werden Stunden, Meter werden zu Kilometern ... In deiner Euphorie ist dir nicht aufgefallen, dass du immer kleiner geworden bist - und das kleine Wesen immer größer!
            `n`#'.$klein[1].'`@ steht nun über dir und lacht.
            `n`n`#"Tja, was soll ich sagen? Wage es ja nicht, mir zu folgen und mich mit Deinen Hilfeschreien zu belästigen!"
            `n`n`@Das Lachen geht dir nicht mehr aus dem Kopf ...
            `nJetzt bist du allein.
            `nIm Wald.
            `nKlein.
            `nAber niedlich!
            `nMm, darüber musst du erst mal in Ruhe nachdenken ...
            `n`nWeil du so niedlich geworden bist, erhältst du `^1`@ Charmepunkt!');
            if ($session['user']['turns']<=4)
            {
                $expplus=round($session['user']['experience']*0.08);
                output('`n`nDu bekommst `^'.$expplus.'`@ Erfahrungspunkte hinzu, verlierst aber `$'.$session['user']['turns'].'`@ Waldkämpfe!');
                $session['user']['turns']=0;
            }
            else
            {
                $expplus=round($session['user']['experience']*0.05);
                output('`n`nDu bekommst `^'.$expplus.'`@ Erfahrungspunkte hinzu, verlierst aber `$4`@ Waldkämpfe!');
                $session['user']['turns']-=4;
            }
            $session['user']['experience']+=$expplus;
            $session['user']['charm']+=1;
            addnews('`$Von nun an muss sich '.$session['user']['name'].'`$ im Wald vor Käfern in Acht nehmen!');
            //savesetting('kleineswesen',''.$session['user']['acctid'].','.$session['user']['name'].',0');
            savesetting('kleineswesen',utf8_serialize(array($session['user']['acctid'],$session['user']['name'],0)));
            $session['user']['specialinc']='';
            break;
        case 5:
        case 6:
        case 7:
        case 8:
            $gems = e_rand(2,3);
            $expplus=round($session['user']['experience']*0.08);
            output('`@Du jagst und jagst ... ein Grüner Drache ist nichts dagegen! Endlich bekommst du das Wesen zu fassen. In dem Moment, in dem du es berührst, wirst du zurückgeschleudert.
            `n`n Aus einer verpuffenden roten Wolke geht `#'.$klein[1].'`@ hervor!
            `n`n`@Als du die verlorengeglaubte Seele bis zum Stadtrand geleitet hast, bekommst du für deine ehrvolle Tat eine Belohnung in Höhe von `^'.$gems.'`@ Edelsteinen!
            `n`nDu bekommst `^'.$expplus.'`@ Erfahrungspunkte hinzu und verlierst einen Waldkampf!');
            $session['user']['gems']+=$gems;
            $session['user']['reputation']+=2;
            $session['user']['experience']+=$expplus;
            $session['user']['turns']-=1;
            addnews('`@'.$session['user']['name'].'`@ kehrte mit der verlorengeglaubten Seele `#'.$klein[1].'`@ aus dem Wald zurück!');
            debuglog($session['user']['name'].'\'s verloren geglaubte Seele war:'.$klein[1].','.$klein[0]);
            if ($klein[0]>0)
            {
                $roundbonus = (e_rand(2,4));
                user_set_aei(array('kleineswesen' => $roundbonus),$klein[0]);

                $mailmessage1 = '`@Als du heute erwachst, fühlst du dich äußerst erholt. In deinem Traum warst du ein kleines Wesen, kaum einen Fingernagel hoch und riefst verzweifelt um Hilfe. Niemand, dem du im Wald begegnetest, reagierte auf dich ...
                `n Doch dann - endlich! - blieb jemand stehen. Es war `^'.$session['user']['name'].'`@! Stundenlang versuchte '.($session['user']['sex']?'sie':'er').', dich zu berühren, doch du konntest nicht anders - irgendetwas zwang dich, immer wieder wegzulaufen. Aber `^'.$session['user']['name'].'`@ ließ sich nicht beirren, berührte dich schließlich und errettete dich damit. Als wenn das noch nicht genug gewesen wäre, geleitete '.($session['user']['sex']?'sie':'er').' dich sogar noch bis zur Stadt zurück.
                `n`nWenn das kein Traum gewesen wäre, müsstest du '.($session['user']['sex']?'ihr':'ihm').' nun äußerst dankbar sein'.($klein[2]>0?', zumal vorher schon '.$klein[2].' Bürger achtlos vorbeigegangen sind':'').'. Es war doch nur ein Traum, oder?
                `n`nWeil du besonders gut geschlafen hast, wirst du morgen `^'.$roundbonus.'`@ zusätzliche Waldkämpfe erhalten!`n`n';
                systemmail($klein[0],'`@Du hattest einen fantastischen Traum!',$mailmessage1);
            }
            $arr_npc=array('Violet','Seth','Dag Durnick','Cedrik','Aeki','Phaedra','Thorim','Merick','Vessa','Petersen');
            $dice=e_rand(1,count($arr_npc))-1;
            //savesetting('kleineswesen','0,'.$arr_npc[$dice].',0');
            savesetting('kleineswesen',utf8_serialize(array(0,$arr_npc[$dice],0)));
            $session['user']['specialinc']='';
            break;
        case 9:
            $gold =  e_rand(200,700) * e_rand(3,7);
            $turns = e_rand(1,min(2,$session['user']['turns']));
            output('`@Du jagst und jagst ... ein Grüner Drache ist nichts dagegen! Es wird immer später ... aber dein Ehrgeiz ist - einmal entfacht - nicht aufzuhalten. Viele Stunden sind vergangen, als deine körperlichen Kräfte dich verlassen. Du sinkst erschöpft zu Boden - und siehst das kleine Wesen auf dem Baumstumpf vor dir stehen. Es zu greifen wäre nun ein Kinderspiel, aber dazu reicht deine Kraft nicht mehr aus ... Zum ersten Mal hörst du genau hin, was es dir zu sagen hat:
            `#"Du hast Dich eifrig bemüht, mich zu berühren - wer mich berührt, macht mich frei! Leider darf ich es Dir nicht zu einfach machen ... Aber dafür, dass Du es versucht hast, möchte ich Dir etwas zeigen."
            `n`@Das kleine Wesen hüpft dreimal im Kreis auf dem Baumstumpf herum, woraufhin dieser in einer roten Wolke verpufft. Es bleibt keine Spur von dem seltsamen Wesen - dafür lässt es aber ein kleines Säckchen zurück!
            `n`n`@In dem Säckchen befinden sich `^'.$gold.'`@ Goldstücke!
            `n`n`^Du verlierst '.$turns.' Waldkämpfe!');
            $session['user']['gold']+=$gold;
            $session['user']['turns']-=$turns;
            $session['user']['specialinc']='';
            break;
        case 10:
            $turns = e_rand(1,min(3,$session['user']['turns']));
            output('`@Du jagst und jagst ... ein Grüner Drache ist nichts dagegen! Es wird immer später ... aber dein Ehrgeiz ist - einmal entfacht - nicht aufzuhalten. Viele Stunden sind vergangen, als deine körperlichen Kräfte dich verlassen. Du sinkst erschöpft zu Boden - und siehst das kleine Wesen auf dem Baumstumpf vor dir stehen. Es zu greifen wäre nun ein Kinderspiel, aber dazu reicht deine Kraft nicht mehr aus ... Zum ersten Mal hörst du genau hin, was es dir zu sagen hat:
            `#"Du hast Dich eifrig bemüht, mich zu berühren - wer mich berührt, macht mich frei! Leider darf ich es Dir nicht zu einfach machen ... Na ja. Damit du beim nächsten Mal etwas wendiger bist, nimm diese Hilfe!"
            `n`@Das kleine Wesen hüpft dreimal im Kreis auf dem Baumstumpf herum, woraufhin dieser in einer roten Wolke verpufft. Es bleibt keine Spur von dem seltsamen Wesen - aber du fühlst dich frischer als je zuvor!
            `n`n`@Du bekommst `^1`@ permanenten Lebenspunkt!
            `n`n`^Du verlierst '.$turns.' Waldkämpfe!');
            $session['user']['turns']-=$turns;
            $session['user']['maxhitpoints']++;
            $session['user']['hitpoints']++;
            $session['user']['specialinc']='';
            break;
        }
        break;
    }

case 'zertreten':
    {
        output('`@Schweren Herzens hebst du deinen Fuß, schaust hinauf zu den Baumwipfeln und`n- trittst mit einem kräftigen Ruck zu!`n`n');
        switch (e_rand(1,10))
        {
        case 1:
            output('`@Als du den Fuß wieder hebst, stellst du mit Erstaunen fest, dass das kleine Wesen verschwunden ist. Offenbar hat er es mit der Angst bekommen und ist geflohen. Dir fällt ein Stein vom Herzen - so ist es für alle Beteiligten besser. Erfüllt von neuer Frische setzt du deinen Weg fort.
            `n`n`^Du erhältst einen zusätzlichen Waldkampf!');
            $session['user']['turns']+=1;
            $session['user']['reputation']-=4;
            $session['user']['specialinc']='';
            break;
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
            output('`@Als du den Fuß wieder hebst, stellst du angewidert fest, dass du ganze Arbeit geleistet hast: von dem kleinen Wesen ist nur noch Matsch übrig geblieben. War das wirklich nötig? Na ja, immerhin hat das Piepen aufgehört. Aber du brauchst eine Weile, um diesen Vorfall zu vergessen.
            `n`n`^Du verlierst einen Waldkampf!');
            $session['user']['reputation']-=10;
            $session['user']['turns']-=1;
            addnews('`$'.$session['user']['name'].'`$ hat die Hilfeschreie von `b'.$klein[1].'`b`$ leider `bvöllig`b missverstanden ...');
            if ($klein[0]>0)
            {
                $roundbonus = (e_rand(3,5));
                user_set_aei(array('kleineswesen' => -$roundbonus),$klein[0]);
                $mailmessage1 = '`@Heute nacht wachst du schweißgebadet auf. In deinem Traum warst du ein kleines Wesen, kaum einen Fingernagel hoch und riefst verzweifelt um Hilfe. Niemand, dem du im Wald begegnetest, reagierte auf dich ...`n Doch dann - endlich! - blieb jemand stehen. Es war `^'.$session['user']['name'].'`@! Aber '.($session['user']['sex']?'sie':'er').' blieb nicht stehen, um dir zu helfen ...
                `n`n Es graut dir noch immer bei der Erinnerung daran, wie es sich anfühlte, als '.($session['user']['sex']?'ihr':'sein').' Fuß niederraste und dich zermatschte. '.($klein[2]>0?'Wenn dich doch einer der '.$klein[2].' Bürger, die vorher achtlos an dir vorbeigegangen sind, bemerkt hätte ... ':'').'Aber zum Glück war das alles ja nur ein Traum ... war es doch, oder?
                `n`nWeil du schlecht geschlafen hast, wirst du morgen `$'.$roundbonus.'`@ Waldkämpfe einbüßen!`n`n';
                systemmail($klein[0],'`$Du hattest einen schrecklichen Albtraum!',$mailmessage1);
            }
            $arr_npc=array('Violet','Seth','Dag Durnick','Cedrik','Aeki','Phaedra','Thorim','Merick','Vessa','Petersen');
            $dice=e_rand(1,count($arr_npc))-1;
            //savesetting('kleineswesen','0,'.$arr_npc[$dice].',0');
            savesetting('kleineswesen',utf8_serialize(array(0,$arr_npc[$dice],0)));
            $session['user']['specialinc']='';
            break;
        case 8:
        case 9:
        case 10:
            output('`@Erschrocken stellst du fest, dass dein Tritt kurz vor dem Boden gestoppt wurde. Von diesem kleinen Wesen?! - Zumindest ist es nicht so klein, als dass es dich nicht gegen einen Baum schleudern könnte! Du rappelst dich auf und rennst schreiend davon.
            `n`n`^Du verlierst die meisten deiner Lebenspunkte!
            `n`^Du verlierst einen Waldkampf!');
            $session['user']['reputation']-=20;
            $session['user']['hitpoints']=1;
            $session['user']['turns']-=1;
            addnews('`$'.$session['user']['name'].'`$ wurde im Wald von einem Däumling erniedrigt.');
            $session['user']['specialinc']='';
            break;
        }
        break;
    }

case 'ruhe':
    {
        $klein[2]++;
        //savesetting('kleineswesen',implode(',',$klein));
        savesetting('kleineswesen',utf8_serialize($klein));
        switch (e_rand($klein[2],10))
        {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
            output('`@Du reißt dich zusammen und musst das Piepen noch etliche Stunden ertragen. Aber letzten Endes war es wirklich nicht so schlimm.');
            break;
        case 7:
        case 8:
            output('`@Du reißt dich zusammen und musst das Piepen noch etliche Stunden ertragen. Arrrrrrgh! Wenn es doch bloß aufhörte! Es bringt dich beinahe um den Verstand.
            `n`n`^Weil du dich nicht konzentrieren kannst, verlierst du einen Waldkampf!');
            $session['user']['turns']--;
            break;
        default:
            output('`@Du reißt dich zusammen und musst das Piepen noch etliche Stunden ertragen. Aber letzten Endes war es wirklich nicht so schlimm.
            `n`nAls du später bei Cedrik ein Ale schlürfst, erfährst du, dass noch mindestens '.$klein[2].' Andere dieses Piepen gehört haben.');
            break;
        }
        $session['user']['specialinc']='';
    break;
    }
}
?>