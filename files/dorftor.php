<?php

/*
*  Ein kleiner Platz vor den Toren des Dorfes, der den Pranger und ein Glücksspiel beherbergt
*  Lagert das PvP vom Dorfplatz aus
*
*  @author laulajatar für atrahor.de
*  Galgen by Salator
*
*/

require_once 'common.php';
checkday();
addcommentary();
$str_filename = basename(__FILE__);
page_header('Vor den Toren');

switch($_GET['op'])
{
        case 'richtplatz':
                {
                        page_header('Der Richtplatz');
                        $str_output .= '`c`b`SD`Te`(r `)Richtpl`(a`Tt`Sz`0`b`c`n
                          `SA`Tb`(s`)eits des Tores, auf der gestampften Erde eines brachen Ackers, liegt ein trister Ort mit Pranger, Galgen und einem furchteinflößenden Gebäude aus Stein und Eisen; die Außenmauern zieren grausige Skulpturen und Bemalungen verschiedener Wesen der Unterwelt, die kaum einem gesunden Geist entsprungen sein können. Bei öffentlichen Torturen sind die mächtigen Flügeltüren weit geöffnet und geben über eine Kettenabsperrung hinweg den Blick in die `iHalle der Vergeltung`i frei, eine Folterkammer mit zahlreichen Streckbänken und Gestellen, Regalen und beschlagenen Truhen mit den schrecklichen Werkzeugen der Vollstreck`(u`Tn`Sg.`n`n`0';
                        viewcommentary('richtplatz','Rufen',30,'ruft');
                        addnav('Wohin?');
                        addnav('P?Zum Pranger','pranger.php');
                        addnav('G?Zum Galgen',$str_filename.'?op=galgen');
                        addnav('T?Zurück zum Tor',$str_filename);
                        break;
                } // Ende richtplatz

        case 'galgen':
                {
                        page_header('Der Galgen');
                        $expireoldacct=getsetting('expireoldacct',50);
                        $sql = 'SELECT acctid, name, sex, daysinjail, seendragon, DATEDIFF(NOW(),laston) AS data1 FROM accounts
                        WHERE daysinjail>dragonkills+10
                        AND location != '.USER_LOC_VACATION.'
                        AND DATEDIFF(NOW(),laston) <= '.$expireoldacct.'
                        AND DATEDIFF(NOW(),laston) > '.($expireoldacct -6).'
                        AND reputation<51
                        ORDER BY data1 DESC, dragonkills DESC LIMIT 1';
                        $result=db_query($sql);
                        $lasthangman=stripslashes(getsetting('lasthangman',''));
                        if(db_num_rows($result)>0) //es gibt einen Kandidat
                        {
                                $row=db_fetch_assoc($result);

                                if($lasthangman!='' && $lasthangman!=$row['name']) //vorherigen Galgenvogel kicken
                                {
                                        savesetting('lasthangman',''); //leer, neuen Namen erst am letzten Tag speichern
                                        insertcommentary(1,'/msg `4Der Henker stößt den Balken weg, auf dem `$'.$lasthangman.'`4 steht. Wenig später ist `$'.$lasthangman.'`4 tot.','richtplatz_galgen');
                                }

                                if($row['data1']==$expireoldacct) //letzter Tag
                                {
                                        if($lasthangman=='')
                                        {
                                                savesetting('lasthangman',($row['name']));
                                                insertcommentary(1,'/msg `7'.$row['name'].'`7 wird zum Galgen geführt.','richtplatz_galgen');
                                        }
                                        $gems_needed=$row['daysinjail']-$row['seendragon'];
                                        $str_output .= '`c`b`)D`(e`Sr `TGal`Sg`(e`)n`0`b`c`n
                                    `)Der Henker bereitet den Galgen vor, um `0'.$row['name'].'`) aufzuknüpfen.`n
                                        Neben dem Galgen siehst du einen Sack mit Dingen, die dem Delinquienten gehörten. Es ist Brauch, dass diese Dinge dem Henker zufallen. Aber man munkelt, der Henker sei bestechlich und lässt gegen einen Obulus auch Passanten in den Sack greifen.`n`n
                                        Vor dem Galgen demonstrieren ein paar Pazifisten gegen die Todesstrafe uns sammeln Spenden, um `0'.$row['name'].'`) freizukaufen.`nFür jeden Kerkertag wird ein Edelstein verlangt. Es fehlen noch `#'.$gems_needed.' Edelsteine`) bis '.($row['sex']?'sie':'er').' freigekauft werden kann.`n
                                        `~Administrativer Hinweis: Die hier gelisteten Chars sind bereits so gut wie verfallen`n`0';
                                        addnav('Aktionen');
                                        addnav('Henker bestechen',$str_filename.'?op=search&who='.$row['acctid']);
                                        addnav('s?Etwas spenden',$str_filename.'?op=spend&need='.$gems_needed.'&who='.$row['acctid']);
                                }

                                else //noch nicht letzter Tag
                                {
                                        output('`c`b`)D`(e`Sr `TGal`Sg`(e`)n`0`b`c`n
                                        `)In der Todeszelle sitzt '.$row['name'].'`) '.($row['sex']?'deren':'dessen').' Hinrichtung in '.($expireoldacct - $row['data1']).' Tagen (Echtzeit) angesetzt ist.');
                                }
                        }

                        else //kein Kandidat
                        {
                                $str_output .= '`c`b`)D`(e`Sr `TGal`Sg`(e`)n`0`b`c`n
                                In nächster Zeit stehen keine Vollstreckungen an, also gibt es hier nichts zu sehen. Verlassen wirkt der Platz vor dem Galgen, nur ein paar Tauben picken auf dem Boden liegende Körner auf.`n`n';
                                if($lasthangman!='') //letzten Galgenvogel kicken
                                {
                                        savesetting('lasthangman','');
                                        insertcommentary(1,'/msg `4Der Henker stößt den Balken weg, auf dem `$'.$lasthangman.'`4 steht. Wenig später ist `$'.$lasthangman.'`4 tot.','richtplatz_galgen');
                                }
                        }

                        viewcommentary('richtplatz_galgen','Rufen',30,'ruft');
                        addnav('Wohin?');
                        addnav('R?Zurück zum Richtplatz',$str_filename.'?op=richtplatz');
                        addnav('T?Zurück zum Tor',$str_filename);
                        break;
                } // Ende galgen

        case 'search':
                {
                        page_header('Der Galgen');
                        $str_output .= '`c`b`)D`(e`Sr `TGal`Sg`(e`)n`0`b`c`n';
                        if(isset($_GET['what']))
                        {
                                if(($_GET['what']=='gems' && $session['user']['gems']>0) || ($_GET['what']=='gold' && $session['user']['gold']>=4000))
                                {
                                        if($session['daily']['galgenitem']<5)
                                        {
                                                $str_output .= '`)Du greifst in den Sack und findest... `^';
                                                $sql='SELECT id FROM items_classes WHERE class_name IN ("Dokumente","Seltenheit","Sammlerstücke")'; //diese Klassen nicht weiterverteilen
                                                $result=db_query($sql);
                                                $str_classes='0';
                                                while($row=db_fetch_assoc($result))
                                                {
                                                        $str_classes.=','.$row['id'];
                                                }
                                                if($row=item_get('owner='.$_GET['who'].'
                                                AND deposit1<9999999
                                                AND tpl_class NOT IN ('.$str_classes.')
                                                AND tpl_id NOT IN ("exchngdmmy")
                                                ORDER BY RAND()'))
                                                {
                                                        if(!empty($row['send_hook']))
                                                        {
                                                                addnav('Na super...','village.php'); //Notausgang
                                                                $item_hook_info['recipient']['acctid'] = $session['user']['acctid'];
                                                                item_load_hook($row['send_hook'],'send_hook',$row);
                                                        }
                                                        $str_output .= $row['name'].'`)!';
                                                        debuglog('Galgen-Item '.$row['name'].' von',$row['owner']);
                                                        $row['owner']=$session['user']['acctid'];
                                                        $row['deposit1']=$row['deposit2']=0;
                                                        item_set('id='.$row['id'],$row);
                                                        $session['daily']['galgenitem']+=1;
                                                }
                                                else //keine Items mehr zu holen
                                                {
                                                        item_add($session['user']['acctid'],'nichts');
                                                        $str_output .= 'NICHTS`)!';
                                                        $session['daily']['galgenitem']=5;
                                                }
                                        if($_GET['what']=='gems') $session['user']['gems']--;
                                        if($_GET['what']=='gold') $session['user']['gold']-=4000;
                                        }
                                        else //schon 5 Items eingehamstert
                                        {
                                                $str_output .= '`)Du willst dem Delinquienten gerade sein letztes Hemd nehmen, als dich der Henker anspricht: "`QDas geht doch nicht! Ich kann doch niemanden hängen der vollkommen nackt ist!`)"`n
                                                Du denkst dir dass es besser wäre, nicht weiter zu hamstern.';
                                        }
                                }
                                else //kein Gold/Gems
                                {
                                        $str_output .= '`4Das kannst du dir nicht leisten!';
                                }
                        }
                        else
                        {
                                $str_output .= '`)Mit unschuldigem Blick fragst du den Henker was es mit dem Sack neben dem Galgen auf sich hat. Er erklärt dir, dass sich darin der Besitz der Verbrecher befindet und er sich durch Verkauf dieser Dinge seinen Lebensunterhalt verdient.`n
                                Du fragst, wer denn all die Dinge kauft. Daraufhin fängt der Henker an, sein Leid zu klagen, dass der Wanderhändler viel zu selten in der Stadt ist und man die Dinge im Sack ja nicht essen könne. Er druckst etwas herum und rückt schließlich damit raus dass du mal in den Sack greifen darfst wenn du ihm 1 Edelstein oder 4000 Goldstücke gibst.';
                                addnav('Aktionen');
                                addnav('E?1 Edelstein geben',$str_filename.'?op=search&what=gems&who='.$_GET['who']);
                                addnav('G?4000 Gold geben',$str_filename.'?op=search&what=gold&who='.$_GET['who']);
                        }
                        addnav('Wohin?');
                        addnav('G?Zum Galgen',$str_filename.'?op=galgen');
                        addnav('R?Zurück zum Richtplatz',$str_filename.'?op=richtplatz');
                        addnav('T?Zurück zum Tor',$str_filename);
                        break;
                } // Ende search

        case 'spend':
                //freikaufen setzt reputation auf 51, gespendete Edels werden in seendragon gespeichert (sollte per default 0 sein)
                {
                        $str_output .= '`c`b`)Der Galgen`0`b`c`n`)';
                        if($_POST['gems']>0) //Formular abgeschickt
                        {
                                if($session['user']['gems']>=$_POST['gems'])
                                {
                                        $gems_spent=intval($_POST['gems']);
										//fix by bathi
                                        $sql='SELECT name, sex, daysinjail, seendragon FROM accounts WHERE acctid='.intval($_POST['who']);
                                        $row=db_fetch_assoc(db_query($sql));
                                        $gems_needed=$row['daysinjail']-$row['seendragon']-$gems_spent;

                                        $arr_user_update = array
                                        (
                                                'seendragon'=>array('sql'=>true,'value'=>'seendragon+'.intval($_POST['gems']))
                                        );

                                        if($gems_needed<=0)
                                        {
                                                $arr_user_update = array_merge($arr_user_update,array('reputation'=>51));
                                        }
										//fix by bathi
                                        user_update($arr_user_update,intval($_POST['who']));

                                        $session['user']['gems']-=$gems_spent;
                                        $str_output .= 'Du spendest '.$_POST['gems'].' Edelsteine. ';
                                        if($gems_needed<=0) //Menge reicht zum Freikaufen
                                        {
                                                $str_output .= 'Ein Jubel geht durch die Gruppe der Pazifisten. Jetzt kann `0'.$row['name'].'`) freigekauft werden.';
                                                insertcommentary(1,'/msg `IDie Bürger '.getsetting('townname','Atrahor').'s haben es durch ihre Spenden geschafft, `t'.$row['name'].'`I vor dem Tod am Galgen zu bewahren.','richtplatz_galgen');
                                                savesetting('lasthangman','');
                                        }
                                        else //reicht nicht zum Freikaufen
                                        {
                                                $str_output .= 'Jetzt fehlen nur noch '.$gems_needed.' Edelsteine, bis `0'.$row['name'].'`) freigekauft werden kann.';
                                        }
                                        if(!$session['daily']['galgen_spend'])
                                        {
                                                $str_output.='`nFür deinen Großmut bekommst du einen Charmpunkt.';
                                                $session['user']['charm']++;
                                        }
                                }
                                else //nicht genug Edels auf der Hand
                                {
                                        $str_output.='So viele Edelsteine hast du gar nicht dabei.';
                                }
                        }
                        else //Formular für Edelsteinmenge anzeigen
                        {
                                $str_output .= 'Wieviele Edelsteine möchtest du für die Freilassung spenden?`n`n
                                <form action="'.$str_filename.'?op=spend" method="post">
                                Spende <input type="text" name="gems" id="gems" size="2" maxlength="2"> Edelsteine
                                <input type="hidden" name="who" value="'.$_GET['who'].'">
                                <input type="hidden" name="need" value="'.$_GET['need'].'">
                                <input type="submit" value="OK">
                                </form>';
                                addnav('',$str_filename.'?op=spend');
                        }
                        addnav('Wohin?');
                        addnav('G?Zum Galgen',$str_filename.'?op=galgen');
                        addnav('R?Zurück zum Richtplatz',$str_filename.'?op=richtplatz');
                        addnav('T?Zurück zum Tor',$str_filename);
                        break;
                } // Ende spend

        case 'wache':
                {
                        $str_output .= '`c`b`|D`.i`(e T`)orw`(ac`.h`|e`0`b`c`n
                        `|D`.i`(e T`)orw`(ac`.h`|e der Wache, die recht gelangweilt am Tor steht und mit nur mäßigem Interesse die Leute mustert, die die Stadt betreten oder verlassen. Während du den Mann beobachtest, überlegst du, dass er wohl eine ziemlich eintönige Arbeit zu verrichten hat, doch noch ehe du dich entscheiden kannst, ihn anzusprechen, kommt er dir zuvor:`n
                        `|`i„He, was gibt\'s da zu starren?“`i`n
                        `)Du überlegst schon, ob es nicht klüger wäre, einfach wieder zu verschwinden, doch da tritt der Wächter auch schon einen Schritt zur Seite und gibt den Blick auf eine kleine Nische in der Mauer frei. Mit einer lockeren Geste deutet er auf drei kleine, umgedrehte Lederbecher in der Vertiefung.`n
                        `|`i„Wenn du schon da rumstehst wie bestellt und nicht abgeholt, was hälst du dann von einem kleinen Spielchen? Gib mir eins von den glitzernden Steinchen, die ihr immer so mit euch herumtragt, und du darfst raten, unter welchem die rote Kugel ist. Wenn du richtig liegst, dann...“`i`n
                        `)Die Wache gerät offensichtlich gespielt ins Grübeln, denn es scheint nicht das erste Mal zu sein, dass sie einem Vorbeikommenden dieses Angebot unterbreitet.`n
                        `|`i„Dann kannst du dir was hierdraus aussuchen!“`i`n
                        `)Mit dem Fuß tippt der Mann an einen recht gut gefüllten Sack, der neben ihm an der Mauer lehnt. Vermutlich befinden sich Dinge darin, die den Zureisenden aus welchen Gründen auch immer abgenommen wurden, doch es könnte genauso gut nur Müll sein.`n`n
                        Ob es wohl einen Versuch wer`(t i`.s`|t?`n`n';
                        addnav('Was nun?');
                        addnav('Spielen',$str_filename.'?op=spiel');
                        addnav('T?Zurück zum Tor',$str_filename);
                        break;
                } // Ende wache

        case 'spiel':
                {
                        if ($session['user']['gems']<1)
                        {
                                $str_output .= '`c`b`|D`.i`(e T`)orw`(ac`.h`|e`0`b`c`n`s`i"Soll das ein Witz sein?" `i`)Die Wache starrt auf deine leere Hand und stellt sich dann wieder demonstativ vor die kleine  Nische. Vielleicht solltest du ersteinmal den Einsatz haben, ehe du spielen willst...`n`n';
                                addnav('T?Zurück zum Tor',$str_filename);
                        }
                        elseif($Char->getNewdayBit( UBIT_WACHE_SPIEL )!=0)
                        {
                                $str_output .= '`c`b`|D`.i`(e T`)orw`(ac`.h`|e`0`b`c`n`)Mit verschränkten Armen tritt die Wache wieder vor die kleine Nische und schüttelt den Kopf.`n`i`s"So nicht, '.($session['user']['sex']?'Mädel':'Bursche').', du hast heut\' schon was gewonnen, andre wollen ja auch mal."`i`n`)So bald wirst du also nicht noch einmal spielen können. Mit einem Schulterzucken wendest du dich ab, du kannst dein Glück ja an einem anderen Tag noch einmal versuchen.`n`n';
                                addnav('T?Zurück zum Tor',$str_filename);
                        }
                        else
                        {
                                $session['user']['gems']--;
                                $session['shellgame']++;
                                $str_output .= '`c`b`|D`.i`(e T`)orw`(ac`.h`|e`0`b`c`n
                                `)Du entscheidest dich, ein Spielchen zu riskieren und trittst näher an die kleine Nische heran. Nachdem der Wächter deinen Edelstein entgegengenommen hat, hebt er zum Beweis, dass die rote Kugel wirklich vorhanden ist, kurz jeden der drei Lederbecher an. Doch zu schnell, als dass du den Bewegungen folgen könntest, werden die Becher verschoben und gemischt und schließlich in einer Reihe aufgestellt.
                                `s`i„Also, wo ist die Kugel?“`i
                                `)Erwartungsvoll sieht der Mann dich an, und du weißt, dass du nun wohl raten musst...`n`n
                                '.create_lnk('<img src="./images/becher.gif" border="0">',$str_filename.'?op=spiel2&what=1')
                                .create_lnk('<img src="./images/becher.gif" border="0">',$str_filename.'?op=spiel2&what=2')
                                .create_lnk('<img src="./images/becher.gif" border="0">',$str_filename.'?op=spiel2&what=3');
                                //  addnav('e?Der erste Becher',$str_filename.'?op=spiel2&what=1');
                                //  addnav('z?Der zweite Becher',$str_filename.'?op=spiel2&what=2');
                                //  addnav('e?Der dritte Becher',$str_filename.'?op=spiel2&what=3');
                        }
                        break;
                } // Ende Spiel

        case 'spiel2':
                {
                        $kugel=e_rand(1,3);
                        $str_output .= '`c`b`|D`.i`(e T`)orw`(ac`.h`|e`0`b`c`n`)Du überlegst einen Moment und deutest dann auf einen der drei Becher. Die Wache nickt und hebt ihn an, während du mit angehaltenem Atem zusiehst.`n';
                        if ($kugel==$_GET['what'])
                        {
                                $win=e_rand(1,20);
                                switch ($win)
                                {
                                        case 1:
                                                {
                                                        $win_name='einen abgenagten Knochen';
                                                        $win_art='den';
                                                        $win_id='abgnkno';
                                                        break;
                                                }
                                        case 2:
                                                {
                                                        $win_name='einen alten Knochen';
                                                        $win_art='den';
                                                        $win_id='altknochen';
                                                        break;
                                                }
                                        case 3:
                                                {
                                                        $win_name='eine kleine Tonscheibe';
                                                        $win_art='die';
                                                        $win_id='kltonschbe';
                                                        break;
                                                }
                                        case 4:
                                                {
                                                        $win_name='einen Feuerstein';
                                                        $win_art='den';
                                                        $win_id='frstein';
                                                        break;
                                                }
                                        case 5:
                                                {
                                                        $win_name='eine Giftphiole';
                                                        $win_art='die';
                                                        $win_id='gftph';
                                                        break;
                                                }
                                        case 6:
                                                {
                                                        $win_name='eine Glasfigur';
                                                        $win_art='die';
                                                        $win_id='glasfigur';
                                                        break;
                                                }
                                        case 7:
                                                {
                                                        $win_name='eine Glasperle';
                                                        $win_art='die';
                                                        $win_id='glasperle';
                                                        break;
                                                }
                                        case 8:
                                                {
                                                        $win_name='eine alte Münze';
                                                        $win_art='die';
                                                        $win_id='muenze';
                                                        break;
                                                }
                                        case 9:
                                                {
                                                        $win_name='einen kleinen Fleischbrocken';
                                                        $win_art='den';
                                                        $win_id='fleischbr';
                                                        break;
                                                }
                                        case 10:
                                                {
                                                        $win_name='einen Ogerzahn';
                                                        $win_art='den';
                                                        $win_id='ogerzahn';
                                                        break;
                                                }
                                        case 11:
                                                {
                                                        $win_name='eine Spinnenseidenrobe';
                                                        $win_art='die';
                                                        $win_id='seidenrobe';
                                                        break;
                                                }
                                        case 12:
                                                {
                                                        $win_name='ein zerfallenes Pergament';
                                                        $win_art='das';
                                                        $win_id='hintdoc';
                                                        break;
                                                }
                                        case 13:
                                                {
                                                        $win_name='einen kleinen Klumpen Gold';
                                                        $win_art='den';
                                                        $win_id='goldklmp';
                                                        break;
                                                }
                                        case 14:
                                                {
                                                        $win_name='ein Stuhlbein';
                                                        $win_art='das';
                                                        $win_id='sthlbn';
                                                        break;
                                                }
                                        case 15:
                                                {
                                                        $win_name='einen rostigen Nagel';
                                                        $win_art='den';
                                                        $win_id='rstngl';
                                                        break;
                                                }
                                        case 16:
                                                {
                                                        $win_name='eine Holzschnitzerei';
                                                        $win_art='die';
                                                        $win_id='schntzhlzg';
                                                        break;
                                                }
                                        case 17:
                                                {
                                                        $win_name='etwas Acolytenfutter';
                                                        $win_art='das';
                                                        $win_id='acofutter';
                                                        break;
                                                }
                                        case 18:
                                                {
                                                        $win_name='eine Holzschnitzerei';
                                                        $win_art='die';
                                                        $win_id='schntzhlze';
                                                        break;
                                                }
                                        case 19:
                                                {
                                                        $win_name='eine Zaubertafel';
                                                        $win_art='die';
                                                        $win_id='zbrtafel';
                                                        break;
                                                }
                                        default:
                                                {
                                                        $win_name='einen Kompass';
                                                        $win_art='den';
                                                        $win_id='kompass';
                                                        break;
                                                }
                                }
                                $str_output .= '`)Und die Kugel ist darunter! Du hast `@gewonnen`)!`n
                                Die Wache nickt dir anerkennend zu und schiebt dann den vollen Sack ein wenig näher zu dir. Du greifst hinein und ziehst `s'.$win_name.' `)heraus, '.$win_art.' `)du erfreut in deinen Beutel packst.`n`n';
                                item_add($session['user']['acctid'],$win_id);
                                debuglog('gewann bei der Wache: '.$win_name);
                                $Char->setNewdayBit(UBIT_WACHE_SPIEL,1);
                        }
                        else
                        {
                                $str_output .= '`)Doch die Kugel ist nicht darunter! Wie schade, du hast leider `$verloren`)!`n
                                Mit einem bedauernden Schulterzucken wendet die Wache sich ab und lehnt sich wieder an die Mauer, doch mit einem weiteren Edelstein wirst du ihr Interesse sicher wieder wecken können, wenn du es noch einmal versuchen willst...`n`n';
                        }
                        addnav('Und jetzt?');
                        addnav('s?Noch einmal spielen',$str_filename.'?op=spiel');
                        addnav('T?Zurück zum Tor',$str_filename);
                        break;
                } // Ende spiel2

        default:
                {
                    $str_output .= get_title('`|D`.a`(s `)Stadt`(t`.o`|r');
                    Weather::get_weather_text('Dorftor');
                        viewcommentary('dorftor','Sprechen',30,'sagt');
                        addnav('Vor den Toren');
                        addnav('b?'.getsetting('townname','Atrahor').' betreten','village.php');
                        addnav('W?Die Wache ansprechen',$str_filename.'?op=wache');
                        addnav('R?Zum Richtplatz',$str_filename.'?op=richtplatz');

                        addnav('D?Die Ritter','discremover.php');
                        addnav ('Das Zigeunerlager','gypsys.php');
						addnav("Das Krankenlager","krankenlager.php");

                        CRPPlaces::addnav(1);

                        addnav('Die Felder');
                        if (getsetting('pvp',1) && $session['user']['acctid']!=getsetting('demouser_acctid',0))
                        {
                                if(($session['user']['profession']==PROF_GUARD) || ($session['user']['profession']==PROF_GUARD_HEAD))
                                {
                                        addnav('Verbrecher suchen','pvp.php');
                                }
                                else
                                {
                                        addnav('t?Spieler töten','pvp.php');
                                }
                        }
                        addnav('#?Schlafen (logout)','login.php?op=logout');
                        if ($session['user']['dragonkills']>1 || $session['user']['level']>5)
                        {
                                addnav('Seeehr lange Reise (Urlaubsmodus)','vacation.php');
                        }
                        if($session['shellgame'])
                        {
                                debuglog($session['shellgame'].' Edelsteine für Hütchenspiel');
                                unset($session['shellgame']);
                        }
                        break;
                } // Ende default
} // Ende switch

headoutput($str_output);

page_footer();
?>