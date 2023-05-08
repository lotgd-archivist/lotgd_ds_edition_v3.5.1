<?php
/************************************************
* Nachtbar "Titty Twister", ein Ort für Gesindel, Vampire und Dämonen
* Autor: Salator (salator@gmx.de)
* für lotgd Dragonslayer Version 2.5
*
* special greetings to X-Fusion, deine Musik ist sehr inspirierend beim Programmieren der Nachtbar :)
*************************************************/

define('LOCNAME','`4Nachtbar `$"Güldenes Andúril"`0'); //Name der Bar
define('BARTENDER','José'); //Name des Barkeepers
define('OPENTIME','17:00'); //Startzeit  der Bar
define('CLOSETIME','07:00'); //Schliesszeit der Bar
define('SUNRISE','06:00'); //Zeit für die Ankündigung der Schließung

require_once('common.php');
require_once(LIB_PATH.'board.lib.php');
checkday();
addcommentary();
page_header('Die Nachtbar');
//music_set ('tittytwister'); //X-Fusion - Archenemys Revenge
//admin_output('specialmisc='.$session['user']['specialmisc'].'`n',false);
switch ($_GET['op'])
{
        case 'bartender':
        { //Barkeeper (Getränke, Trophäenhandel)
                $alecost = $session['user']['level']*7;
                $absinthcost = $session['user']['level']*15;
                $kadavercost = $session['user']['level']*100;
                switch($_GET['act'])
                {
                        case 'beer': //mieses Bier
                        {
                                output('`mEbenso freundlich wie bestimmt verlangst du ein Bier');
                                if ($session['user']['drunkenness']>66)
                                {
                                        output(', `maber `Q'.BARTENDER.' `mfährt unbekümmert damit fort, das Glas weiter zu polieren, an dem er gerade arbeitet. `4"Du hast genug gehabt '.($session['user']['sex']?'Mädl':'Bursche').'`m.\' ');
                                }
                                else
                                {
                                        if ($session['user']['gold']>=$alecost)
                                        {
                                                $session['user']['drunkenness']+=20;
                                                $session['user']['gold']-=$alecost;
                                                output('`m.  `Q'.BARTENDER.' `mnimmt ein Glas und schenkt schäumendes Bier aus einem angezapften Fass hinter ihm ein. Er gibt dem Glas einen ordentlichen Schwung und es saust über die Theke, wo du es mit deinen Kriegerreflexen fängst.`n`nDu drehst dich um, trinkst von diesem herzhaften Gesöff und lächelst zufrieden in die Runde.`n`n');
                                                switch (e_rand(1,3))
                                                {
                                                case 1:
                                                        output('`)Du fühlst dich gesund!');
                                                        $session['user']['hitpoints']+=round($session['user']['maxhitpoints']*0.1,0);
                                                        break;
                                                case 2:
                                                case 3:
                                                        output('`)Doch plötzlich dreht sich dir der Magen um! Du brüllst in die erstbeste Ecke...');
                                                        $session['user']['hitpoints']-=round($session['user']['maxhitpoints']*0.1,0);
                                                        if($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
                                                }
                                                if ($session['user']['drunkenness']>33)
                                                {
                                                        $session['user']['reputation']--;
                                                }
                                                $session['bufflist']['101'] = array("name"=>"`#Rausch","rounds"=>10,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
                                        }
                                        else
                                        {
                                                        output('`m. `Q'.BARTENDER.' `mzapft ein frisches Bier und trinkt es vor deinen Augen aus. `4"DU nicht! DU bist hier nicht willkommen, wenn du nicht zahlen kannst!" `mblafft er dich an.');
                                        }
                                break;
                                }
                        break;
                        }

                        case 'absinth':
                        {
                                output('`mDu schlägst mit der Faust auf die Bar und verlangst einen Drachenschnaps');
                                if ($session['user']['drunkenness']>=99)
                                {
                                        output(' mit 3 Tropfen Laudanum, aber das ist das letzte woran du dich erinnern kannst. Als du mit fürchterlichen Kopfschmerzen aufwachst findest du dich im Schattenreich wieder.');
                                        addnews('`w'.$session['user']['name'].'`ws Schnapsleiche wurde aus der Nachtbar getragen.');
                                        $session['user']['drunkenness']=99;
                                        killplayer(0,0,0,'shades.php','In die Kiste');
                                        $session['bufflist']['headache'] = array('survive_death'=>1,'name'=>'`$Kopfschmerzen`0','rounds'=>30,'wearoff'=>'`&Deine Kopfschmerzen verschwinden`0','atkmod'=>0.2,'defmod'=>0.2,'roundmsg'=>'Du hast fürchterliche Kopfschmerzen','activate'=>'defense');
                                }
                                else
                                {
                                        if ($session['user']['gold']>=$absinthcost)
                                        {
                                                $session['user']['drunkenness']+=33;
                                                $session['user']['gold']-=$absinthcost;
                                                output('`m.  `Q'.BARTENDER.' `mnimmt ein Glas und füllt es mit `Ggrünem `mDrachenschnaps. Du kippst dir das Gesöff in den Rachen und wendest dich wieder der Menge zu.`n');
                                                if ($session['user']['drunkenness']>90)
                                                {
                                                    output('`mNach dem dritten Drachenschnaps siehst du die Welt, wie sie -wirklich- ist. '.($session['user']['sex']?'Der stramme Jüngling':'Das hübsche Mädel').' neben dir verwandelt sich plötzlich in einen Dämon und auch die anderen Gäste sind in Wirklichkeit Vampire, Dämonen und Werwölfe.');
                                                }
                                                elseif ($session['user']['drunkenness']>65)
                                                {
													output('`mNach dem zweiten Drachenschnaps siehst du die Welt, wie sie -nicht- ist. Die hübschen '.($session['user']['sex']?'Jungs':'Mädels').' unter den Gästen werfen dir verführerische Blicke zu.');
                                                }
                                                else
                                                {
                                                        output('`mNach dem ersten Drachenschnaps ist die Welt noch in Ordnung.');
                                                }
                                                if (e_rand(1,5)==3)
                                                {
                                                        output('`n`n`)Du fühlst dich stark!');
                                                        $session['user']['playerfights']++;
                                                }
                                                if ($session['user']['drunkenness']>33)
                                                {
                                                        $session['user']['reputation']--;
                                                }
                                                $session['bufflist']['101'] = array("name"=>"`#Rausch","rounds"=>10,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
                                                Atrahor::$Session['drachenschnaps'] = true;
                                        }
                                        else
                                        {
                                                output('`m. `Q'.BARTENDER.' `mfüllt ein Glas mit `Ggrünem `mDrachenschnaps und trinkt es vor deinen Augen aus. `4"DU nicht! DU bist hier nicht willkommen, wenn du nicht genug Gold zum Bezahlen hast!"`m blafft er dich grob an.');
                                        }
                                }
                        break;
                        }

                        case 'blackmarket': //Kadaververwertungsanstalt
                        {
                                if (item_count(' owner='.$session['user']['acctid'].' AND tpl_id="trph" '))
                                {
                                        if($session['user']['specialmisc']&2)
                                        {
                                                output('`)In der Küche ist leider keinen Platz mehr, um noch weitere Zutaten zu lagern.');
                                        }
                                        elseif($session['user']['specialmisc']&1)
                                        {
                                                $result = item_list_get(' owner='.$session['user']['acctid'].' AND tpl_id="trph" ','',false);
                                                $amount=(db_num_rows($result));
                                                $out='`mDu flüsterst `Q'.BARTENDER.' `mzu, dass du ein paar erlesene Zutaten für seinen Spezialpudding hast. Plötzlich wird er hellhörig.`nWas willst du ihm aus deinem Rucksack anbieten?`n`)'.BARTENDER.' wird 30% vom Verkaufswert als Provision behalten.`n';
                                                for ($j=1;$j<=$amount;$j++) {
                                                        $partsname=db_fetch_assoc($result);
                                                        $choice=rawurlencode($partsname['name']);
                                                        $value=$partsname['value1'];
                                                        $itemid=$partsname['id'];
                                                        $out.='`n<a href=\'tittytwister.php?op=bartender&act=blackmarket2&choice='.$choice.'&value='.$value.'&itemid='.$itemid.'&gold='.$partsname['gold'].'\'>'.$partsname['name'].'</a> `7('.$value.' DK)';
                                                        addnav('','tittytwister.php?op=bartender&act=blackmarket2&choice='.$choice.'&value='.$value.'&itemid='.$itemid.'&gold='.$partsname['gold']);
                                                }
                                                output($out);
                                        }
                                        else
                                        {
                                                output('`mDu plauderst eine Weile mit `Q'.BARTENDER.' `mund rein zufällig lenkst du das Thema auf "nicht frei erhältliche" Waren. Doch genau in diesem Moment kommt ein weiterer Gast an die Theke und `Q'.BARTENDER.' `mist auf einmal sehr beschäftigt...');
                                                $session['user']['specialmisc']+=1;
                                        }
                                }
                                else
                                {
                                        output('`Q'.BARTENDER.' `mschaut in deinen Beutel und lacht laut los `4"Diesen Plunder bietest du hier an? Dafür gibt dir bestenfalls Aeki was!"');
                                }
                        break;
                        }

                        case 'blackmarket2': //Embryovernichtungslager
                        {
                                $gold=round($_GET['gold']*0.7);
                                output('`Q`n'.BARTENDER.' `mgibt dir `)'.$gold.' `mGold. '.$_GET['choice'].' `mverschwindet in der Küche.`n');
                                item_delete(' id='.$_GET['itemid']);
                                $session['user']['gold']+=$gold;
                                $session['user']['specialmisc']=$session['user']['specialmisc']^3;
                        break;
                        }

                        case 'kadaver': //Kadaverpudding
                        {
                                if($session['user']['specialmisc']&4)
                                {
                                        output('`mEs wird gerade ein neuer Topf voll Pudding zubereitet. Du wirst noch etwas warten müssen.');
                                }
                                elseif ($session['user']['gold']<$kadavercost)
                                {
                                        output('`mDu verlangst einen Kadaverpudding. `Q'.BARTENDER.' `mholt eine Schüssel voll und schlürft sie genüsslich vor deinen Augen aus. `4"DU nicht! DU bist hier nicht willkommen! Hier gibt es nichts geschenkt, nur weil du kein Gold hast" `mgibt er dir unfreundlich zu verstehen.');
                                }
                                else
                                {
                                        output('`mDu verlangst einen Kadaverpudding und bekommst eine Schüssel mit schleimig-grünem Inhalt.`n');
                                        $session['user']['specialmisc']^=4;
                                        $session['user']['gold']-=$kadavercost;
                                        $min_chance=1;
                                        if($session['user']['race']=='vmp' || $session['user']['race']=='wwf' || $session['user']['race']=='dmn') $min_chance+=2;
                                        if($session['user']['specialmisc']&2) $min_chance++; //kadaver verkauft
                                        if($session['user']['turns']>15) $min_chance++;
                                        if($session['user']['dragonkills']>30) $min_chance--;
                                        if($session['user']['race']=='men') $min_chance--; //der Mensch schmeckt am süßesten
                                        //admin_output($min_chance,false);
                                        switch(e_rand($min_chance,9))
                                        {
                                                case 1: //Aussaug-Tod, sollten Vampire, Werwölfe, Dämonen nicht bekommen
                                                        output('`4"Das Dinner ist serviert!"`m hörst du `Q'.BARTENDER.' `mnoch sagen als sich eine Horde Vampire auf dich stürzt und dich aussaugt.`n`$Du bist tot.');
                                                        addnews('`6'.$session['user']['name'].'`t wurde von blutrünstigen Vampiren ausgesaugt.');
                                                        killplayer();
                                                        break;
                                                case 2: //Schleimtod
                                                        output('`mDu beginnst die Suppe auszulöffeln, die du dir eingebrockt hast, als du eine Veränderung an dir bemerkst. Deine Haut wird ebenso grün und schleimig wie das Zeug in der Schüssel. `n`$Wenig später kippst du um und findest dich bei Ramius wieder.');
                                                        addnews('`6'.$session['user']['name'].'`4 hat sich eingeschleimt und durfte Ramius besuchen.');
                                                        killplayer();
                                                        break;
                                                case 3: //AP Verlust
                                                        output('`mDu beginnst die Suppe auszulöffeln, die du dir eingebrockt hast, als du eine Veränderung an dir bemerkst. Deine Knochen werden weich wie Gummi. Da waren wohl verdorbene Zutaten dabei...`n`$Du wirst die nächsten Tage nicht mehr so fest zuschlagen können.');
                                                                $session['user']['attack']--;
                                                                if ($session['user']['attack']<1)
                                                                {
                                                                        $session['user']['attack']=1;
                                                                }
                                                        break;
                                                case 4: //LP Verlust
                                                        output('`mDieser Pudding schmeckt einfach super, hat aber auch betäubende Wirkung. Zwar musst du mit der Ohnmacht kämpfen, doch isst du weiter unter Krämpfen.`n`$Du verlierst die Hälfte deiner Lebenspunkte.');
                                                                $session['user']['hitpoints']*=0.5;
                                                                if ($session['user']['hitpoints']<1)
                                                                {
                                                                        $session['user']['hitpoints']=1;
                                                                }
                                                        break;
                                                case 5: //WK verlieren
                                                        output('`mDu beginnst die Suppe auszulöffeln, die du dir eingebrockt hast, als sich deine Finger in Klauen verwandeln. Offenbar wurden Werwolf-Kadaver verarbeitet.`n`$Während sich dein Aussehen wolfsähnlich verändert, verlierst du alle verbleibenden Waldkämpfe.');
                                                            $session['user']['turns']=0;
                                                        break;
                                                case 6: //Prügelei
                                                        output('`mDu fängst gerade an, deinen Pudding auszulöffeln, als sich ein langhaariger Typ in stahlbesetzter schwarzer Drachenleder-Rüstung neben dich setzt und dir aufdringlich beim Essen zusieht. Schließlich packt er selbst einen Löffel aus und bedient sich an deiner Schüssel.`n`nWillst du dir das bieten lassen?');
                                                        $badguy = array(
                                                        "creaturename"=>"`TSchwarzer Schläger`0"
                                                        ,"creaturelevel"=>16
                                                        ,"creatureweapon"=>"Peitsche"
                                                        ,"creatureattack"=>1
                                                        ,"creaturedefense"=>40
                                                        ,"creaturehealth"=>1000
                                                        ,"creaturegold"=>423
                                                        ,"diddamage"=>0);
                                                        $userattack=$session['user']['attack']+e_rand(1,3);
                                                        $userhealth=round($session['user']['hitpoints']/2);
                                                        $userdefense=$session['user']['defense']+e_rand(1,3);
                                                        $badguy['creaturelevel']=$session['user']['level'];
                                                        $badguy['creatureattack']+=($userattack-4);
                                                        $badguy['creaturehealth']+=$userhealth;
                                                        $badguy['creaturedefense']+=$userdefense;
                                                        $session['user']['badguy']=createstring($badguy);
                                                        addnav('v?Den Unhold verprügeln','tittytwister.php?op=fight');
                                                        break;
                                                case 7: //PVP bekommen
                                                        output('`mNichtsahnend beginnst du den Pudding auszulöffeln, als es in der Schüssel klimpert. Du fischst einen Ring heraus. Diesen Ring hast du doch schonmal in deinem Freundeskreis gesehen... Du  weißt auch, dass der Träger ihn nie freiwillig herausgeben würde.`nDu wirst wütend! `)Jetzt wäre der richtige Zeitpunkt, sich zu rächen.');
                                                            $session['user']['playerfights']++;
                                                        break;
                                                case 8: //LP bekommen
                                                        output('`mDer Pudding schmeckt köstlich! So eine Stärkung zwischendurch, das hast du jetzt gebraucht!');
                                                            $session['user']['hitpoints']*=1.3;
                                                        break;
                                                case 9: //WK bekommen
                                                        output('`mDu löffelst den Pudding bis zum letzten Tropfen aus und hast nun Kraft für `)einen`m weiteren Waldkampf.');
                                                            $session['user']['turns']++;
                                                        break;
                                                case 10: //AP bekommen
                                                        output('`mWie kräftigend doch so ein Pudding ist!`n`)Du wirst die nächsten Tage fester zuschlagen können.');
                                                            $session['user']['attack']++;
                                                        break;
                                                default:
                                                        output('Igitt!');
                                        }
                                }
                                break;
                        }

                        case 'holy': //Gag am Rande
                        {
                                output('`Q`n'.BARTENDER.' `mhält dich für betrunken, gewährt dir aber noch einen Wunsch. Also verlangst du einen `CHoly Bartender`m. `Q'.BARTENDER.' `mschaut dich fragend an: `4"Ich weiß leider nicht, wie man einen Holy Bartender macht." `mDu grinst und antwortest `#"ICH weiß es."`n`n');
                                switch(e_rand(1,3))
                                {
                                        case 1:
                                        {
                                                output('`mMit deinen sprituellen Kräften löst du die Verankerung des riesigen Kronleuchters, welcher auf `Q'.BARTENDER.' `mstürzt und ihn aufspießt.');
                                                break;
                                        }
                                        case 2:
                                        {
                                                output('`mDu schleuderst ein `QMinotaurenvernichtungselixier`t auf `Q'.BARTENDER.'`m, welcher sich daraufhin auflöst. Woher wußtest du nur, dass `Q'.BARTENDER.'`m in Wirkichkeit ein Minotaurus ist?');
                                                break;
                                        }
                                        default:
                                        {
                                                output('`mDu ziehst dein '.$session['user']['weapon'].'`m und machst `Q'.BARTENDER.'`m einen Kopf kürzer.');
                                        }
                                }
                                output('`n`n`mDa `Q'.BARTENDER.' `mjetzt tot ist, kann er auch keine '.$absinthcost.' Gold von dir kassieren. Schnell machst du dich aus dem Staub.`n');
                        break;
                        }

                        default:
                        {
                                output('`c`b`4An der Theke`b`c`n`Q'.BARTENDER.' `mschaut dich mit einer übertriebenen Freundlichkeit an. Du kennst solche Leute: Die würden ihre eigene Großmutter an den Drachen verfüttern, wenn sie dadurch einen geringen Vorteil hätten. Offenbar hält `Q'.BARTENDER.' `mdein Auftauchen allerdings nicht für vorteilhaft und fragt dich deshalb schroff `4"Was willst\'n du?"`m!');
                                $drunkenness = array(-1=>'absolut nüchtern',
                                0=>'ziemlich nüchtern',
                                1=>'kaum berauscht',
                                2=>'leicht berauscht',
                                3=>'angetrunken',
                                4=>'leicht betrunken',
                                5=>'betrunken',
                                6=>'ordentlich betrunken',
                                7=>'besoffen',
                                8=>'richtig zugedröhnt',
                                9=>'fast bewusstlos'
                                );
                                $drunk = round($session['user']['drunkenness']/10-.5,0);
                                if ($drunkenness[$drunk])
                                {
                                        output('`n`n`7Du fühlst dich '.$drunkenness[$drunk].'`n`n');
                                }
                                else
                                {
                                        output('`n`n`7Du fühlst dich nicht mehr.`n`n');
                                }
                                addnav('PlörrBräu ('.$alecost.' Gold)','tittytwister.php?op=bartender&act=beer');
                                addnav('Drachenschnaps ('.$absinthcost.' Gold)','tittytwister.php?op=bartender&act=absinth');
                                if($session['user']['drunkenness']>=50) addnav('Holy Bartender ('.$absinthcost.' Gold)','tittytwister.php?op=bartender&act=holy');
                                addnav('Kadaverpudding ('.$kadavercost.' Gold)','tittytwister.php?op=bartender&act=kadaver');
                                addnav('Schwarzmarkt','tittytwister.php?op=bartender&act=blackmarket');
                        }
                }
        break;
        }

        case 'seeddealer':
        { //illegale Substanzen
                output('`c`b`SD`]e`Zr `:finstere Wanderdr`:u`Zi`]d`Se`c`b`n');

                if ($_GET['sop']=='')
                {
                        output('`SE`]i`Zn `:fremder Druide sitzt mit tief ins Gesicht gezogener Kapuze etwas abseits an einem Tisch und raucht etwas, was einen eigentümlichen Geruch verbreitet. Niemand kennt seinen richtigen Namen, alle nennen ihn nur den "Hempel". Man sagt, er handle mit Pflanzen, welche für heidnische Rituale benötigt werden, die nicht das Wohlwollen der Obrigkeit fin`Zd`]e`Sn.`n`n`0');
                        addnav('Waren ansehen','tittytwister.php?op=seeddealer&sop=browse');
                }

                else if ($_GET['sop']=='browse')
                {
                        $sql = 'SELECT id FROM items_classes WHERE class_name="Saatgut"';
                        $result = db_query($sql);
                        $rowc = db_fetch_assoc($result);

                          $sql = 'SELECT tpl_id,tpl_name,tpl_description,tpl_gold,tpl_gems
                                FROM items_tpl
                                WHERE tpl_class='.$rowc['id'].'
                                AND vendor_new=0
                                AND spellshop=0
                                OR tpl_id IN ("tollkirsch","wermut","hlblkraut")
                                ORDER BY tpl_class DESC, tpl_name ASC';
                        $result = db_query($sql);
                        $str_out='`:Der düstere Fremde kann dir diese Dinge verkaufen:`0';

                        $str_out.='`n`n<table border="0" cellpadding="0" width=95%>';
                        $str_out.="<tr class='trhead'><th>Name</th><th>Beschreibung</th><th align='right'>Preis</th></tr>";

                        for ($i=0;$i<db_num_rows($result);$i++)
                        {
                                  $row = db_fetch_assoc($result);
                                $bgcolor=($bgcolor=='trdark'?'trlight':'trdark');
                                $str_out.='<tr class="'.$bgcolor.'">
                                <td valign="top">'.create_lnk($row['tpl_name'],"tittytwister.php?op=seeddealer&sop=buy&id=".$row['tpl_id']).'</td>
                                <td>'.$row['tpl_description'].'</td>
                                <td align="right" valign="top">`^'.$row['tpl_gold'].'&nbsp;Gold'.($row['tpl_gems']>0?'<br>`#'.$row['tpl_gems'].'&nbsp;Gemmen':'').'`0</td>
                                </tr>';
                        }
                        $str_out.='</table>';

                        output($str_out);
                        $show_invent = true;
                }

                else if ($_GET['sop']=="buy")
                {
                          $sql = 'SELECT * FROM items_tpl WHERE tpl_id="'.$_GET['id'].'"';
                        $result = db_query($sql);
                        if (db_num_rows($result)==0)
                        {
                                  output('`qDu denkst, dir etwas ganz Besonderes ausgesucht zu haben, doch der düstere Fremde meint nur: "`QTut mir leid, aber mit '.$_GET['id'].' kann ich nicht dienen.`q"');
                                addnav('Nochmal suchen','tittytwister.php?op=seeddealer&sop=browse');
                        }
                        else
                        {
                                  $row = db_fetch_assoc($result);
                                if ($row['tpl_gold']>$session['user']['gold'] || $row['tpl_gems']>$session['user']['gems'])
                                {
                                        output('`$Das kannst du dir nicht leisten!`0');
                                }
                                else
                                {
                                        output('`qDu übergibst dem finsteren Druiden einen kleinen Beutel mit Gold und bekommst dafür einen kleinen Beutel mit '.$row['tpl_name'].'`q.
                                        `n`n"`QEin sehr guter Kauf, '.($session['user']['sex']?'Madame':'Meister').', '.$row['tpl_description'].'
                                        `nUnd wenn Ihr wieder etwas braucht, zögert nicht, mich anzusprechen.`q"');
                                         $session['user']['gold']-=$row['tpl_gold'];
                                         $session['user']['gems']-=$row['tpl_gems'];

                                        $row['tpl_gold'] = round($row['tpl_gold'] * 0.75);
                                        item_add($session['user']['acctid'],'',$row);

                                        addnav('Mehr kaufen','tittytwister.php?op=seeddealer&sop=browse');
                                }
                        }
                }
        break;
        }

        case 'regalia':
        { //Insignien-Schwarzmarkt
                if($_GET['act']=='buy')
                {
                        output('`mDu deutest auf die Kiste mit Insigniensplittern und gibst Veri zu verstehen, dass du einen solchen kaufen möchtest. Veri sagt zu dir: "`F');
                        if($session['user']['gems']>2)
                        {
                                if(item_count('tpl_id=\'insgnteil\' AND owner='.$session['user']['acctid'])<2)
                                {
                                        $now=time();
                                        $sql='SELECT last_regalia_blackmarket FROM dg_guilds WHERE guildid='.$session['user']['guildid'];
                                        $row=db_fetch_assoc(db_query($sql));
                                        $buy_possible=strtotime($row['last_regalia_blackmarket'])+(172800/getsetting('daysperday',4)); //2 Spieltage
                                        if($row['last_regalia_buy']=='0000-00-00 00:00:00' || $buy_possible<$now)
                                        {
                                                $sql='UPDATE dg_guilds SET last_regalia_blackmarket="'.date('Y-m-d H:i:s',$now).'" WHERE guildid='.$session['user']['guildid'];
                                                db_query($sql);
                                                item_add($session['user']['acctid'],'insgnteil');
                                                output('So a Scherbli willscht ham? A guad, hier hosd aans. Gibscht ma dreie von dera Edels un guad is."`n`mDu tust wie geheißen 3 Edelsteine auf den Tisch und schnappst dir den Splitter. Zufrieden verlässt du die Kammer.');
                                                //in german: Einen Insigniensplitter willst du haben? Na gut, gib mir 3 Edelsteine und du bekommst einen.
                                                $session['user']['gems']-=3;

                                                debuglog('kaufte Insigniensplitter in Dunkler Gasse');

                                        }
                                        else output('Joa weischd, von deina Sippe war nauer scho aaner doa un wolld a Scherbli ham. Un i kann ned zulassn, dass do a greeßre Sammlung is wie meene. Derwegen gibsch heit keen fir dich."`n`mUnverrichteter Dinge ziehst du weiter.');
                                        //in german: Ja weißt du, von deiner Gilde war neulich schon einer da. Und ich kann nicht zulassen daß da eine größere Sammlung ist als meine. Deswegen gibt es heute keinen Insigniensplitter für dich.
                                        $session['buyregalia']=2;
                                }
                                else
                                {
                                        output('Die Dinger kannst do garned tragen!"`n`mDu siehst ein, dass die wirkich recht schwer sind, du hast ja schon einen davon im Beutel.');
                                }
                        }
                        else
                        {
                                output('Jo weischd, i verschenk de Scherblis ned. Da mussd scho a poar Edels hertun."');
                        }
                }
                elseif($_GET['act']=='sell')
                {
                        if(item_delete('tpl_id=\'insgnteil\' AND owner='.$session['user']['acctid'],1))
                        {
                                output('`mDu bietest Veri einen Insigniensplitter aus deinem Beutel an. Veri betrachtet das Teil mit Kennerblick und sagt dann `F"Guad, i kauf\'n." `mOhne viele weitere Worte gibt er dir ein Säckchen mit 1000 Goldstücken.');
                                $session['user']['gold']+=1000;
                                if($session['user']['guildid'])
                                {
                                        insertcommentary(1,'/msg `7Es wurde beobachtet wie '.$session['user']['name'].'`7 einen Insigniensplitter verhökert hat!','guild-'.$session['user']['guildid']);
                                }
                        }
                        else
                        {
                                output('`mMit ernster Mine greifst du in deinen Beutel und bietest Veri deinen unsichtbaren Insigniensplitter zum Kauf an.
                                `nVeri gibt dir dafür einen ebenso unsichtbaren Haufen Goldmünzen.
                                `n`nAls du die Kammer verlassen hast, hörst du die Räuber schallend lachen. Scheinbar hast du dich gerade zum Narren gemacht...');
                        }
                }
                else
                {
                        output('`SD`]u `Zf`:o`mlgst einem langen Gang, an dessen Ende sich der Versammlungsraum von Harpax Veris Räuberbande befindet. Oder eigentlich wäre die Bezeichnung "Schatzkammer" treffender, denn überall sind Kisten mit Gold, Edelsteinen und sogar Insigniensplittern gestapelt.
                        `nDie Räuber ziehen ihre Waffen, als du eintrittst, stecken sie aber wieder weg, als Veri ihnen ein Zeichen gibt. Denn von dir geht ja keine Gefahr aus, du willst nur han`:d`Ze`]l`Sn.`n');
                        if(!$session['buyregalia'])
                        {
                                $session['buyregalia']=2;
                                $guildlimit=ceil(getsetting('dgguildmax',10)/4);
                                $sql='SELECT guildid FROM dg_guilds ORDER BY reputation ASC, points ASC LIMIT '.$guildlimit;
                                $result=db_query($sql);
                                while ($row=db_fetch_assoc($result))
                                {
                                        if($session['user']['guildid']==$row['guildid'])
                                        {
                                                output('`mDir wird nun auch klar, warum deine Gilde nicht im Ansehen des Königs steigt. Offenbar hat der Gnom mit seiner Räuberbande die Paladine überfallen und eure mühsam produzierten Insignien geraubt. Und nun bietet er dir die Splitter zum Verkauf an...');
                                                $session['buyregalia']=1;
                                                break;
                                        }
                                }
                        }
                }
                if($session['buyregalia']==1) addnav('k?Insigniensplitter kaufen','tittytwister.php?op=regalia&act=buy');
                addnav('v?Insigniensplitter verkaufen','tittytwister.php?op=regalia&act=sell');
                if($access_control->su_check(access_control::SU_RIGHT_DEBUG))
                {
                        addnav('Superuser');
                        addnav('Insigniensplitter kaufen','tittytwister.php?op=regalia&act=buy',false,false,false,false);
                }
        break;
        }

        case 'santanico':
        { //Tänzerin heranwinken und evtl gute Laune bekommen
                output('`mDu winkst mit deinem Goldbeutel nach der Tänzerin, auf dass sie nur für dich tanze. Es dauert auch nicht lange, bis sie an deinem Platz tanzt. Mit verführerischen Posen nimmt sie deinen Goldbeutel');
                $cost=450+e_rand(-50,100);
                //admin_output('cost='.$cost,false);
                if($session['user']['gold']<$cost)
                {
                        output(', schaut dich dann abfällig an und wendet sich einem zahlungskräftigeren Gast zu.');
                }
                else
                {
                        if(e_rand(1,3)>1)
                        {
                                if($session['user']['sex'])
                                {
                                        output(' und tanzt jetzt nur für dich. Du siehst ihr genau zu und träumst davon, selbst so verführerisch tanzen zu können.');
                                }
                                else
                                {
                                        output(' und tanzt jetzt nur für dich. Sie nimmt dein Getränk und lässt es an ihrem Bein herunterlaufen, wo du es wegschlürfst.');
                                }
                                $session['bufflist']['happy']=array("name"=>"`!Extrem gute Laune","rounds"=>45,"wearoff"=>"`!Deine gute Laune vergeht allmählich wieder.`0","defmod"=>1.15,"roundmsg"=>"Du schwelgst in Erinnerung an die Tänzerin und tust alles dafür dass du sie wiedersiehst!","activate"=>"defense");
                        }
                        else
                        {
                                output(' und tanzt jetzt nur für dich. Spielerisch wirft sie dich zurück, setzt dir ihren Fuß auf die Brust und... `$verwandelt sich! `mBöse lächelnd flüstert sie dir zu "`4Ich werde dich nicht ganz aussaugen. Du wirst -nur- mein Sklave sein."`n`n`$Kurze Zeite später, nachdem sie sich satt getrunken hat, fühlst du dich wesentlich schwächer.');
                                $session['bufflist']['slave']=array("name"=>"`!Sklaverei","rounds"=>45,"wearoff"=>"`!Der Dämon lässt von dir ab.`0","defmod"=>.85,"roundmsg"=>"Deine Sklaven-Status schwächt dich!","activate"=>"defense");
                        }
                        $session['user']['gold']=0;
                }
        break;
        }

        case 'vampire':
        { //der alte Vampir
        	$bool_vampire_tittytwister = (((bool)getsetting('vampire_tittytwister',0)) && Atrahor::$Session['daily']['vampire_tittytwister'] == false);
        	if($bool_vampire_tittytwister == false)
        	{
        		output('`c`b`SE`]i`Zn`: d`munkler `:G`Za`]n`Sg`b`c
                        `n`SD`]e`Zr `:E`mingang ist sehr verdreckt und stinkt scheusslich. Beinahe hast du das Gefühl dich durch die Kloake zu wühlen. Wenn er etwas größer wäre könnte man sich vielleicht hindurchzwängen, aber da passt maximal ein Gnomenkind h`mi`:n`Ze`]i`Sn.');
        	}
        	elseif($session['user']['marks']<32 && Atrahor::$Session['daily']['vampire_tittytwister'] == false ) //mit Blutgott würde es hier immer 1000 Gold geben
        	{
        		Atrahor::$Session['daily']['vampire_tittytwister'] = true;
        		output('`c`b`SE`]i`Zn`: d`munkler `:G`Za`]n`Sg`b`c
                        `n`SD`]u `Zs`:t`mehst vor einem langen, unbefestigten Gang, der scheinbar in einen entlegenen, wesentlich gefährlicheren Teil des Waldes führt. Ein dunkler Nebel zieht herauf, sodass du kaum die Hand vor Augen sehen kannst und du spürst, wie sich ein kaltes Grausen in die Luft erhebt. Noch kannst du gefahrlos umkehren und dich in den warmen Schankraum der Nachtbar setzen. Wagst du es, weiterzug`:e`Zh`]e`Sn?');
        		addnav('Gehe tapfer weiter','paths.php?ziel=vampire');
        	}
        	else
        	{
        		output('`c`b`SD`]e`Zr`: a`mlte Va`:m`Zp`]i`Sr`b`c
                        `n`TFurchtlos gehst du den langen Weg entlang zum alten Vampir, wissend, dass er dir nichts anhaben kann.
                        `nIhr unterhaltet euch eine Weile über den Blutgott und die Welt, ehe du weiterziehst.');
        	}
        	break;
        }

        case 'board':
        { //Meldung ans schwarze Brett
                if ($_GET['act']=="add1")
                {
                        $msgprice=$session['user']['level']*60*(int)$_GET['amt'];
                        if ($_GET['board_action'] == "")
                        {
                                output('`Q'.BARTENDER.' `mkramt einen Zettel und einen Stift hervor. `4"Das macht dann `^'.$msgprice.'`4 Gold. Schreib deine Nachricht hier drauf!"`n`n');

                                board_view_form('Ans schwarze Brett',
                                'Gib deine Nachricht ein:');
                        }
                        else
                        {
                                if ($session['user']['gold']<$msgprice)
                                {
                                        output('`mAls `Q'.BARTENDER.' `mbemerkt, dass du offensichtlich nicht genug Gold hast, schnauzt er dich an: `4"So kommen wir nicht ins Geschäft, '.($session['user']['sex']?"Amiga":"Amigo").'! Sieh zu, dass du Land gewinnst! Oder im Lotto."');
                                }
                                else
                                {
                                        if (board_add('tittytwist',(int)$_GET['amt'],1) == -1)
                                        {
                                                output('`Q'.BARTENDER.' `mverdreht die Augen: `4"Du hast schon einen Zettel da hängen. Reiß den erst ab."');
                                        }
                                        else
                                        {
                                                output('`mMürrisch nimmt `Q'.BARTENDER.' `mdein Gold und den Zettel und ohne ihn nochmal durchzulesen, heftet er ihn zu den anderen an das schwarze Brett.');
                                                $session['user']['gold']-=$msgprice;
                                        }
                                }
                        }
                }
                else
                {
                        $msgprice=$session['user']['level']*60;
                        $msgdays=(int)getsetting("daysperday",4);
                        output('`Q'.BARTENDER.'`m bemerkt, dass du eine Nachricht ans schwarze Brett heften willst. Sofort eilt er zu dir: `4"Du willst also dunkle Geschäfte machen? Das wird aber nicht billig. Nun gut, wie lange soll die Nachricht denn zu sehen sein?" `mfragt er dich fordernd und nennt die Preise.');
                        addnav($msgdays.' Tage (`^'.$msgprice.'`0 Gold)','tittytwister.php?op=board&act=add1&amt=1');
                        addnav(($msgdays*3).' Tage (`^'.($msgprice*3).'`0 Gold)','tittytwister.php?op=board&act=add1&amt=3');
                        addnav(($msgdays*10).' Tage (`^'.($msgprice*10).'`0 Gold)','tittytwister.php?op=board&act=add1&amt=10');
                        if ($session['user']['message']>"")
                        {
                                output("`nEr macht dich noch darauf aufmerksam, dass er deine alte Nachricht entfernen wird, wenn du jetzt eine neue anbringen willst.");
                        }
                }
                break;
        }

        case 'viewboard':
        { //schwarzes Brett ansehen
                board_view('tittytwist',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,
                                '`mAn der Tafel hängen diese Pergamente:`0',
                                '`mAn dieser Tafel hängt kein Angebot oder Gesuch aus.`0');
                addnav('Nachricht hinzufügen','tittytwister.php?op=board');
                break;
        }

        case 'fight':
        { //Prügelei
                $fight=true;
                include "battle.php";
                if ($victory)
                {
                        $badguy['creaturegold']=round(e_rand($badguy['creaturegold']/4,3*$badguy['creaturegold']/4));
                        $badguy['creatureexp']=e_rand(3,6)*$badguy['creaturelevel'];
                        headoutput('`@`c`bSieg!`b`c`n`n
                        `b`$Du hast '.$badguy['creaturename'].'`$ erledigt!`b`n
                        `#Du erbeutest `^'.$badguy['creaturegold'].'`# Goldstücke!`n
                        Du bekommst `^'.($badguy['creatureexp']).'`# Erfahrungspunkte!`n`0');
                        $session['user']['gold']+=$badguy['creaturegold'];
                        $session['user']['playerfights']--;
                        if($badguy['creaturegold']>5000) debuglog('erbeutete '.$badguy['creaturegold'].' Gold bei Prügelei in der Nachtbar');
                        $fight=false;
                }
                else if ($defeat)
                {
                        headoutput('`n`&Kurz vor dem endgültigen Todesstoß läßt '.$badguy['creaturename'].'`& von dir ab. Du hast nur noch 1 Lebenspunkt und verlierst all dein Gold, aber du hast Glück, noch am Leben zu sein!`n<hr>`n');
                        $session['user']['hitpoints']=1;
                        $session['user']['gold']=0;
                        $fight=false;
                }
                else
                {
                        fightnav(false,true);
                }
                break;
        }

        default:
        { //Nachtbar Startseite bzw tagsüber nur Chatfeld
                $time = getgametime(true);
                if ($session['user']['dragonkills']<2) //kein Zugang für Neulinge
                {
                        output('`SZ`]a`Zg`:h`maft öffnest du die Tür und erblickst im schummrigen Licht die ü-bels-ten Gestalten. Du bemerkst, dass dieser Ort nichts für dich ist und drehst schnell wieder um. Vielleicht willst du ja wiederkommen wenn du etwas größer bist?`nEinige wohl vom Gestank in der Bar gestörte Gäste unterhalten sich auch vor de`:r `ZT`]ü`Sr:`n`n');
						viewcommentary('spelunke');
                }
                elseif ($time < OPENTIME && $time > CLOSETIME) //nur nachts geöffnet
                {
                          output('`c`b'.LOCNAME.'`b`c
                        `n`SDieses Lokal hat geöffnet vom Abendrot bis zum Morgengrauen.
                        `n`SS`]o `Zs`:t`meht es groß über der Tür. Tatsächlich ist hier gerade überhaupt nichts los, nur `Q'.BARTENDER.' `mräumt die Trümmer der letzten Nacht weg und ein paar Besoffene liegen noch in den Ecken.
                        `nEinige Stammgäste unterhalten sich vor de`:r `ZT`]ü`Sr:`n`n');
                viewcommentary('spelunke');
                }
                elseif ($session['user']['playerfights']>0 && e_rand(1,20)==1) //Zufallsevent Prügelei
                {
                        $query_result = db_query('SELECT name,level,weapon,attack,defence,hitpoints,gold FROM accounts WHERE loggedin=0 AND dragonkills>2 AND sex=0 ORDER BY rand() LIMIT 1');
                        $arr_result_user = db_fetch_assoc($query_result);
                        if($arr_result_user['name'])
                        {
                                $badguy = array(
                                'creaturename'=>$arr_result_user['name']
                                ,'creaturelevel'=>$arr_result_user['level']
                                ,'creatureweapon'=>$arr_result_user['weapon']
                                ,'creatureattack'=>$arr_result_user['attack']
                                ,'creaturedefense'=>$arr_result_user['defence']
                                ,'creaturehealth'=>$arr_result_user['hitpoints']
                                ,'creaturegold'=>$arr_result_user['gold']
                                ,'diddamage'=>0);
                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy['creaturelevel']=$session['user']['level'];
                                $badguy['creatureattack']+=($userattack-4);
                                $badguy['creaturehealth']+=$userhealth;
                                $badguy['creaturedefense']+=$userdefense;
                                $session['user']['badguy']=utf8_serialize($badguy);
                                output('`4`b'.$arr_result_user['name'].'`b`m rempelt dich an und verschüttet dein Bier.');
                                addnav('v?Den Unhold verprügeln','tittytwister.php?op=fight');
                        }
                        else
                        {
                                output('`mJemand, der `$Ramius`m verdammt ähnlich sieht, rempelt dich an und
verschüttet dein Bier. Der hatte wohl selbst schon eins zuviel.');
                        }
                        addnav('k?Besser keinen Streit','tittytwister.php');
                }
                else
                {
                	output('`c`b`SN`]a`Zc`:h`mtbar "Güldenes And`:ú`Zr`]i`Sl"`b`c
                	`n`SDä`]m`Zm`:ri`mges Licht dringt durch die schmutzigen Fenster der Schenke, die man nur durch den unverkennbaren Gestank des Ale und die kräftigen Stimme, die auf die dunkle Gasse dringen, als Schenke erkennt. Doch sobald man den Raum betreten hat, ist der Unterschied zum Eberkopf unverkennbar. Viele unheimliche Gestalten haben sich hier versammelt, denen manch gesetzestreuer Bürger lieber aus dem Weg gehen würde. Der Umgangston ist rauh und immer wieder kommt es hier und da zu kleinen Schlägereien, bei denen keine Rücksicht auf die schäbige Einrichtung des Schankraums genommen wird. Zur Unterhaltung der Besucher tanzt eine sehr leichtbekleidete Brünette auf einer langen Tafel.
                	`nDer Schenkenbesitzer `Q'.BARTENDER.'`m '.(e_rand(1,3)==1?'entleert währenddessen eine weitere Flasche seines selbstgebrannten Schnapses in d`:ie `ZGl`]äs`Ser.':'scheint davon aber nur gelangweilt zu sein und schenkt niemanden sonderlich viel Aufmer`:ks`Zam`]ke`Sit.').'`n`n');
                	if ($time > SUNRISE && $time < CLOSETIME) output('`(Draußen beginnt es zu dämmern. Zeit, auszutrinken und nach Hause zu gehen.`n`n');
                	viewcommentary('spelunke','Gröhlen:',15,'gröhlt');//english: den of thieves
                	addnav('J?Sprich mit Barkeeper '.BARTENDER.'','tittytwister.php?op=bartender');
                	if (getsetting('vendor',0)==1)
                	{
                		addnav('u?Wanderdruide','tittytwister.php?op=seeddealer');
                	}
                	else if (access_control::is_superuser())
                	{
                		addnav('u?Wanderdruide(SU)','tittytwister.php?op=seeddealer');
                	}
                	addnav('Tänzerin heranwinken','tittytwister.php?op=santanico');
                	addnav('V?Zum alten Vampir','tittytwister.php?op=vampire');
                	addnav('Armdrücken','pressarm.php');
                	if($session['user']['guildid']) addnav('H?Kammer von Harpax Veri','tittytwister.php?op=regalia');
                	addnav('Séparé nehmen','bordello.php');
                	addnav('B?Schwarzes Brett','tittytwister.php?op=viewboard');
                }
        }
}
if($session['user']['alive'] && !$fight)
{
	addnav('Zurück');
	if($_GET['op'])
	{
		addnav('B?In die Bar','tittytwister.php');
	}
	addnav('G?Zur dunklen Gasse','slums.php');
	if(!$_GET['op'])
	{
		addnav('W?Zum Wohnviertel','houses.php');
		addnav('d?Zum Stadtzentrum','village.php');
	}
}
page_footer();
?>