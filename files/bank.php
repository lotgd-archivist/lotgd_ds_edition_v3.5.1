<?php

// 22062004

require_once 'common.php';
page_header('Die alte Bank');
output('`c`b`(D`)ie `7A`el`ft`0e Sta`fd`et`7b`)an`(k`0`b`c`n');
define('JSLIB_NO_FOCUS_NEEDED',1);

$demouser_acctid=getsetting('demouser_acctid',0);

switch ($_GET['op'])
{
        case '': //Startbild
        {
                $show_invent = true;
                checkday();
                output('`(D`)ie `7E`em`fp`0fangshalle der alten Bank ist schlicht und dennoch stilvoll eingerichtet, helle Kacheln säumen die Wände und viele Metallverzierungen wurden in die Möbel eingearbeitet.
                Vereinzelt sind ein paar Pflanzen aufgestellt, doch in diesem Gebäude geht es vor allem um das Gold der Bürger und des Fürsten, das im Keller der Bank sicher aufbewahrt wird.
                Einzelne Schreibtische stehen in der Halle, hinter denen die Angestellten ihren Aufgaben nachgehen, zu denen auch die Betreuung der einzelnen Bürger gehört, die hier ihr Gold oder ihre Edelsteine sicher aufbewahren oder abheben m`fö`ec`7h`)te`(n.`0`n
                `n`0Als du dich einem der Tische näherst, blickt ein kleiner Mann zu dir auf, der seine Brille kurz zurechtrückt und dich mustert.
                Sein Auftreten ist vollkommen geschäftsmäßig und seriös. Als du ihn nach deinem derzeitigen Vermögen fragst, blickt er auf sein Buch hinab.
                `v"Nun '.$session['user']['name'].'`v, ich werde sehen, was ich finden kann!" `0Er blättert ein wenig in den Seiten und nickt schließlich, scheinbar ist er fündig geworden.`n');

                if ($session['user']['goldinbank']>=0)
                {
                        output('`v"Nun, nach diesen Aufzeichnungen habt Ihr `^'.$session['user']['goldinbank'].' Gold'.($session['user']['gemsinbank']>0?' `vund `^'.$session['user']['gemsinbank'].' Edelsteine':'').' `vbei dieser Bank. Was kann ich noch für Euch tun?"');
                }
                else
                {
                        output('`v"Nun, nach diesen Aufzeichnungen habt Ihr `bSchulden`b in Höhe von `^'.abs($session['user']['goldinbank']).' Gold `vbei dieser Bank. '.($session['user']['gemsinbank']>0?'Euer Edelsteindepot enthält `^'.$session['user']['gemsinbank'].' Edelsteine`v. ':'').'`vWas kann ich noch für Euch tun?"`n`n `&`i(Schulden verfallen durch eine Heldentat nicht!)`i');
                }
                break;
        }

        case 'transfer': //Gold überweisen Empfängersuche
        {
                output('`0`bGold überweisen`b:`n`n');
                if ($session['user']['goldinbank']>=0)
                {
                        $rowe = user_get_aei('goldout');
                        $maxout = $session['user']['level']*getsetting('maxtransferout',25);
                        $minfer = round(getsetting('transferperlevel',25)/10*((int)$session['user']['level']/2));
                        output('`0Du kannst maximal `^'.getsetting('transferperlevel',25).'`0 Gold pro Level des Empfängers überweisen.
                        `nDu musst mindestens `^'.$minfer.' `0Gold überweisen.
                        `nDu kannst nicht mehr als insgesamt `^'.$maxout.' `0Gold überweisen.');
                        if ($rowe['goldout'] > 0)
                        {
                                output('`0 (Du hast heute schon `^'.$rowe['goldout'].' `0Gold überwiesen.)');
                        }
                        output("`n`n<form action='bank.php?op=transfer2' method='POST'>Wieviel überweisen:
                        <input name='amount' id='amount' size='5'>
                        `nAn: <input name='to' id='to'>
                        `n(Unvollständige Namen werden automatisch ergänzt.
                        `nDu wirst nochmal zum Bestätigen aufgefordert.)
                        `n`n<input type='submit' class='button' value='Vorschau'>`0</form>
                        ".focus_form_element('amount'));
                        addnav("","bank.php?op=transfer2");
                }
                else
                {
                        output('`0Der Bankier weigert sich strikt, Geld für jemanden zu überweisen, der bei der Bank selbst Schulden hat.');
                }
                break;
        }

        case 'transfer2': //Gold überweisen Empfänger bestätigen
        {
                output('`0`bÜberweisung bestätigen`b:`n`n');
                $string = str_create_search_string($_POST['to']);
                $sql = 'SELECT name,login,acctid
                FROM accounts
                WHERE name LIKE "'.$string.'"
                ORDER BY login="'.db_real_escape_string($_POST['to']).'" DESC, login ASC
                LIMIT 100';
                $result = db_query($sql);
                $amt = abs((int)$_POST['amount']);
                $rows=db_num_rows($result);
                if ($rows==1)
                {
                        $row = db_fetch_assoc($result);
                        output('<form action="bank.php?op=transfer3" method="POST">
                        Überweise `^'.$amt.' Gold`0 an `&'.$row['name'].'`0.
                        `n`n<input type="hidden" name="to" value="'.$row['acctid'].'">
                        <input type="hidden" name="amount" value="'.$amt.'">
                        <input type="submit" class="button" value="Überweisung abschließen">
                        </form>');
                        addnav('','bank.php?op=transfer3');
                }
                else if ($rows>100)
                {
                        output('`0Der Bankier schaut dich überfordert an und schlägt dir vor, deine Suche vielleicht etwas mehr einzuengen, indem du den Namen genauer festlegst. Es gibt leider zu viele Bürger in der Stadt, die bei diesem Namen Gold bekommen würden.`n`n');
                        output("<form action='bank.php?op=transfer2' method='POST'>Wieviel ü<u>b</u>erweisen: <input name='amount' id='amount' accesskey='b' width='5' value='$amt'>`n");
                        output("`0A<u>n</u>: <input name='to' accesskey='n' value='". $_POST['to'] . "'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n`n");
                        output("<input type='submit' class='button' value='Vorschau'></form>");
                        JS::Focus('amount');
                        addnav('','bank.php?op=transfer2');
                }
                else if ($rows>1)
                {
                        if($rows==100) output('Es treffen zuviele Namen zu, nur die ersten 100 werden angezeigt');
                        output("<form action='bank.php?op=transfer3' method='POST'>
                        Überweise `^$amt Gold`0 an <select name='to' class='input'>");
                        for ($i=0; $i<db_num_rows($result); $i++)
                        {
                                $row = db_fetch_assoc($result);
                                output("<option value=\"".$row['acctid']."\">".strip_appoencode($row['name'])."</option>",true);
                        }
                        output("</select>
                        `n`n<input type='hidden' name='amount' value='$amt'>
                        <input type='submit' class='button' value='Überweisung abschließen'>
                        </form>");
                        addnav('','bank.php?op=transfer3');
                }
                else
                {
                        output('Es konnte niemand mit diesem Namen gefunden werden. Bitte versuche es nochmal.');
                }
                break;
        }

        case 'transfer3': //Gold überweisen Betragseingabe+Abschluss
        {
                $amt = abs((int)$_POST['amount']);
                output('`0`bÜberweisung abschließen`b`n');
                if ($session['user']['gold']+$session['user']['goldinbank']<$amt)
                {
                        output('`0Wie willst du `^'.$amt.'`0 Gold überweisen, wenn du nur `^'.($session['user']['gold']+$session['user']['goldinbank']).'`0 Gold hast?');
                }
                else
                {
                        $rowe = user_get_aei('goldout');
                        $sql = 'SELECT name,accounts.acctid,level,goldin,lastip,emailaddress,uniqueid
                        FROM accounts
                        LEFT JOIN account_extra_info USING(acctid)
                        WHERE accounts.acctid="'.$_POST['to'].'"';
                        $result = db_query($sql);
                        if (db_num_rows($result)==1)
                        {
                                $row = db_fetch_assoc($result);
                                $maxout = $session['user']['level']*getsetting('maxtransferout',25);
                                $maxtfer = $row['level']*getsetting('transferperlevel',25);
                                $minfer = round(getsetting('transferperlevel',25)/10*((int)$session['user']['level']/2));
                                if ($rowe['goldout']+$amt > $maxout)
                                {
                                        output('`0Die Überweisung wurde nicht durchgeführt: Du darfst nicht mehr als `^'.$maxout.'`0 Gold pro Tag überweisen.');
                                }
                                else if ($maxtfer<$amt)
                                {
                                        output('`0Die Überweisung wurde nicht durchgeführt: `v'.$row['name'].'`0 darf maximal `^'.$maxtfer.'`0 Gold empfangen.');
                                }
                                else if ($row['goldin']>=getsetting('transferreceive',3))
                                {
                                        output('`v'.$row['name'].'`0 hat heute schon zu viele Überweisungen erhalten. Du wirst bis morgen warten müssen.');
                                }
                                else if ($amt<$minfer)
                                {
                                        output('`0Du solltest genug Gold überweisen, damit es sich auch lohnt. Wenigstens `^'.$minfer.'`0 Gold.');
                                }
                                else if ($row['acctid']==$session['user']['acctid'])
                                {
                                        output('`0Du kannst dir nicht selbst Gold überweisen. Das macht keinen Sinn!');
                                }
                                else if (ac_check($row))
                                {
                                        output('`$`bNicht erlaubt!!`b Du darfst kein Gold an deine eigenen Charaktere überweisen!');
                                }
                                else
                                {
                                        debuglog($amt.' Gold überwiesen an', $row['acctid']);
                                        $session['user']['gold']-=$amt;
                                        if ($session['user']['gold']<0)
                                        {
                                                //withdraw in case they don't have enough on hand.
                                                $session['user']['goldinbank']+=$session['user']['gold'];
                                                $session['user']['gold']=0;
                                        }
                                        user_set_aei(array('goldout'=>$rowe['goldout']+$amt));
                                        user_set_aei(array('goldin'=>$row['goldin']+$amt),$row['acctid']);

                                        user_update(array('goldinbank'=>array('sql'=>true,'value'=>'goldinbank+'.$amt)),$row['acctid']);

                                        output('`0Transfer vollständig!');
                                        //$session['user']['donation']+=1;
                                        systemmail($row['acctid'],'`^Du hast eine Überweisung erhalten!`0','`&'.$session['user']['name'].'
                                `0 hat dir `^'.$amt.'`0 Gold auf dein Konto überwiesen!');
                                }
                        }
                        else
                        {
                                output('`0Die Überweisung hat `$nicht geklappt`0. Bitte versuchs nochmal.');
                        }
                }
                break;
        }

        case 'gemstrans': //Edelsteine versenden  Start und Eingabeformular
        {
                output('`0`bEdelsteine versenden`b:`n`n');
                if ($session['user']['gems']+$session['user']['gemsinbank']>0)
                {
                        $rowe=user_get_aei('gemsout');
                        $maxout = max(getsetting('housemaxgemsout',10) - $rowe['gemsout'],0);
                        output('`0Du kannst Edelsteine für eine Versandgebühr von `^10 Gold pro Stück`0 an einen beliebigen Charakter verschenken. Die Gebühr wird von deinem Konto abgezogen.
                        `nUm kein Ziel für Räuber zu werden, wird der Bote für dich heute noch bis zu '.$maxout.' Edelsteine liefern.
                        `n`n`0');
                        output('
                                <div id="search_div">
                                        `IAn wen möchtest du die Edelsteine versenden?`n
                                        `y(Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`0`n
                                        '.form_header('bank.php?op=gemstrans2','POST',true,'search_form','if(document.getElementById(\'search_sel\').selectedIndex > -1) {this.submit();} else {search();return false;}').'
                                                '.jslib_search('document.getElementById("search_form").submit();','Empfänger auswählen!','account','',true).'
                                        </form>
                                </div>
                        ');
                        addnav('','bank.php?op=gemstrans2');
                }
                else
                {
                        output('`0Der kleine Bankier erklärt dir lange und umständlich, dass du keine Edelsteine verschenken kannst, wenn du keine hast!');
                }
                break;
        }

        case 'gemstrans2': //Edelsteine versenden
        {
                if(isset($_POST['quantity']))
                {
                        $quantity=abs(intval($_POST['quantity']));
                        $rowe = user_get_aei('gemsout');
                        $sql = 'SELECT acctid,name,sex,dragonkills,gemsinbank,lastip,emailaddress,uniqueid
                                FROM accounts
                                WHERE acctid="'.(int)$_POST['to'].'"';
                        $result = db_query($sql);
                        if (db_num_rows($result)==1)
                        {
                                $row = db_fetch_assoc($result);
                                $dragonkills=max(1,$row['dragonkills']);
                                $max_storage=min(1000,$dragonkills*10);
                                $rowe=user_get_aei('gemsout');
                                $maxout = max(getsetting('housemaxgemsout',10) - $rowe['gemsout'],0);
                                if($quantity>$maxout)
                                {
                                        $quantity=$maxout;
                                }
                                if ($row['acctid']==$session['user']['acctid'])
                                {
                                        output('`0Du schenkst dir selbst '.$quantity.' Edelsteine. Als du das Geschenk öffnest, denkst du, wie schön ist es doch, mit '.$session['user']['name'].'`0 so gut befreundet zu sein...');
                                }
                                elseif ($row['gemsinbank']+$quantity>$max_storage)
                                {
                                        output('`v'.$row['name'].'`0 hat `$nicht genug Platz`0 in '.($row['sex']?'ihrem':'seinem').' Depot für weitere '.$quantity.' Edelsteine. Du wirst warten müssen, bis sich das ändert.');
                                }
                                elseif (ac_check($row))
                                {
                                        output('`$`bNicht erlaubt!!`b Du darfst keine Edelsteine an deine eigenen Charaktere versenden!');
                                }
                                elseif ($session['user']['gems']+$session['user']['gemsinbank']<$quantity)
                                {
                                        output('`0So viele Edelsteine hast du leider nicht, sodass du diese auch `$nicht versenden`0 kannst.');
                                }
                                else
                                {
                                        $session['user']['goldinbank']-=$quantity*10;
                                        $session['user']['gems']-=$quantity;
                                        if($session['user']['gems']<0)
                                        { //nicht genug auf der Hand, Rest aus dem Depot nehmen
                                                $session['user']['gemsinbank']+=$session['user']['gems'];
                                                $session['user']['gems']=0;
                                        }
                                        debuglog($quantity.' Edelsteine versendet an ',$row['acctid']);
                                        user_set_aei(array('gemsout'=>$rowe['gemsout']+$quantity));

                                        user_update(array('gemsinbank'=>array('sql'=>true,'value'=>'gemsinbank+'.$quantity)),$row['acctid']);

                                        output($quantity." Edelsteine `@erfolgreich`0 versendet! Die Gebühr wurde direkt von deinem Goldkonto abgebucht.");
                                        $mailmessage='`v'.$session['user']['name'].'`0 war so freundlich und hat dir `#'.$quantity.($session['user']['sex']?' ihrer':' seiner').' Edelsteine`0 in dein Bank-Depot legen lassen!';
                                        if($_POST['message']!='')
                                        {
                                                $mailmessage.='`n`n`&'.$session['user']['login'].' hat eine Botschaft beigelegt:`n`^'.$_POST['message'];
                                        }
                                        systemmail($row['acctid'],'`#Du hast '.($quantity>1?'Edelsteine':'einen Edelstein').' geschenkt bekommen!`0',$mailmessage);
                                }
                        }
                        else
                        {
                                output('`0Der Versand hat nicht geklappt. Bitte versuche es nochmal.');
                        }
                }
                else
                { //Menge angeben
                        if(isset($_POST['acctid']) && intval($_POST['acctid'])>0)
                        {
                                $to_acctid=$_POST['acctid'];
                        }
                        elseif(isset($_GET['acctid']) && intval($_GET['acctid'])>0)
                        {
                                $to_acctid=$_GET['acctid'];
                        }
                        else
                        {
                                redirect('bank.php');
                        }

                        $sql = 'SELECT name,dragonkills,gemsinbank
                                FROM accounts
                                WHERE acctid="'.$to_acctid.'"';
                        $result = db_query($sql);
                        $row = db_fetch_assoc($result);
                        $dragonkills=max(1,$row['dragonkills']);
                        $max_storage=min(1000,$dragonkills*10);

                        output('`v'.$row['name'].'`0 hat noch Platz für '.($max_storage-$row['gemsinbank']).' Edelsteine.
                        `n`n`0
                        <form action="bank.php?op=gemstrans2" method="post">
                        <input type="text" name="quantity" id="quantity" size=3 maxlength=2> Edelsteine verschenken
                        `n`nDu kannst '.$row['name'].' noch eine Botschaft beilegen:
                        `n<textarea class="input" name="message" cols=60 rows=3></textarea>
                        `n<input type="hidden" name="to" value='.$to_acctid.'>
                        `n<input type="submit" class="button" value="Versand abschließen">
                        </form>');
                        JS::Focus("quantity");
                        addnav('','bank.php?op=gemstrans2');
                }
                break;
        }

        case 'deposit': //Gold einzahlen Eingabefeld
        {
                output('`0<form action="bank.php?op=depositfinish" method="POST">`0Du hast '.($session['user']['goldinbank']>=0?'ein Guthaben von':'Schulden in Höhe von').' '.abs($session['user']['goldinbank']).' Gold bei der Bank.
                `nWie <u>v</u>iel '.($session['user']['goldinbank']>=0?'einzahlen':'zurückzahlen').':
                <input id="input" name="amount" size=5 accesskey="v">
                <input type="submit" class="button" value="Einzahlen">
                `n`iGib 0 oder gar nichts ein, um alles einzuzahlen.`i`0
                </form>
                '.focus_form_element('input'));
                addnav('','bank.php?op=depositfinish');
                break;
        }

        case 'depositfinish': //Gold einzahlen
        {
                $_POST['amount']=abs((int)$_POST['amount']);
                if ($_POST['amount']==0) //alles einzahlen
                {
                        if($session['user']['prefs']['notall2bank'] || $session['user']['prefs']['notall2bankfix']) //Einstellung: etwas Gold behalten
                        {
                                if($session['user']['prefs']['notall2bank'] && !$session['user']['prefs']['notall2bankfix']) //Einstellung: etwas Gold behalten
								{
										$rest=abs(intval($session['user']['prefs']['notall2bank']));
										
										if($session['user']['gold']>$session['user']['level']*$rest)
										{
												$_POST['amount']=$session['user']['gold']-$session['user']['level']*$rest;
										}
										else //weniger Gold vorhanden als Einstellung verlangt
										{
												$_POST['amount']=0;
										}
                        		}
								else if($session['user']['prefs']['notall2bankfix']) //Einstellung: etwas Gold behalten
								{
										$rest=abs(intval($session['user']['prefs']['notall2bankfix']));
										
										if($session['user']['gold']>$rest)
										{
												$_POST['amount']=$session['user']['gold']-$rest;
										}
										else //weniger Gold vorhanden als Einstellung verlangt
										{
												$_POST['amount']=0;
										}
                        		}
								 else
								{
										$_POST['amount']=$session['user']['gold'];
								}
                        }
                        else
                        {
                                $_POST['amount']=$session['user']['gold'];
                        }
                }
                if ($_POST['amount']==0) //Gold reicht nicht
                {
                        output('`$FEHLER: Du hast kein Gold übrig.`0`n`nDu hast dich dafür entschieden, immer etwas Gold im Beutel haben zu wollen. `n'.($session['user']['sex']?'Eine Frau':'Ein Mann').' deines Standes sollte '.$session['user']['level']*$rest.' Goldstücke im Beutel haben, du hast aber nur '.$session['user']['gold'].' Goldstücke darin.`n`I(Du kannst diese Einstellung in deinem Profil ändern.)`n`n`^');
                }
                elseif ($_POST['amount']>$session['user']['gold']) //mehr eingegeben als vorhanden
                {
                        output('`$FEHLER: Soviel Gold hast du nicht dabei.`0`n`nDu schmeißt deine `^'.$session['user']['gold'].'`0 Gold auf den Schaltertisch und erklärst, dass du die ganzen `^'.$_POST['amount'].'`0 Gold einzahlen möchtest.`n`nDer kleine Mann schaut dich nur verständnislos an. Durch diesen seltsamen Blick verunsichert, zählst du noch einmal nach und erkennst deinen Irrtum. Verdammt, wozu soll ein Krieger rechnen können?');
                }
                else //OK, Gold einzahlen
                {
                        $session['user']['goldinbank']+=$_POST['amount'];
                        $session['user']['gold']-=$_POST['amount'];
                        //debuglog("deposited " . $_POST[amount] . " gold in the bank");
                        output('`0Du zahlst `^'.$_POST['amount'].'`0 Gold auf dein Konto ein. Du hast damit '.($session['user']['goldinbank']>=0?'ein Guthaben von':'Schulden in Höhe von').' `^'.abs($session['user']['goldinbank']).'`0 Gold auf deinem Konto und `^'.$session['user']['gold'].'`0 Gold hast du bei dir.');
                }
                break;
        }

        case 'gemsdepot': //Edelsteindepot Start und Übersicht
        {
                $dragonkills=max(1,$session['user']['dragonkills']);
                $rowe = user_get_aei('gemsin');
                //gemsin: Erhaltene Edels total
                //housemaxgemsout: 50 Edels dürfen entnommen werden
                //Bank-Restmenge = min(50,DK*2) - gemsin, aber nicht unter 0
                $max_gemsperday=max((getsetting('housemaxgemsout',10) - $rowe['gemsin']),0);
                $max_storage=min(1000,$dragonkills*10);

                output('`IDie Bank bietet dir ein Edelstein-Depot zu folgenden Konditionen:
                `n`0Für jede volbrachte Heldentat wird eine Lagerkapazität von 10 Edelsteinen gewährt, jedoch nicht mehr als 1000.
                `nDie tägliche Entnahmemenge beträgt einschließlich Hausschatzentnahmen nicht mehr als '.getsetting('housemaxgemsout',10).'.
                `nFremde werden wie Bürger mit einer vollbrachten Heldentat behandelt.`0
                `n
                `nDein Depot bietet Platz für `I'.$max_storage.' Edelsteine`0.
                `nEs befinden sich `I'.$session['user']['gemsinbank'].' Edelsteine`0 darin.
                `n'.($_GET['act']=='in'?'Somit ist noch für `I'.($max_storage-$session['user']['gemsinbank']).' Edelsteine`0 Platz.':'Heute darfst du noch `I'.$max_gemsperday.' Edelsteine`0 entnehmen.').'
                `0`n`n
                <form action="bank.php?op=gems_in_out" method="post">
                '.($_GET['act']=='in'?'<input type="text" name="deposit" id="deposit" size=4 maxlength=4> Edelsteine deponieren':'<input type="text" name="withdraw" id="withdraw" size=4 maxlength=4> Edelsteine mitnehmen').'
                `n`n<input type="submit" class="button" value="OK">`n
                </form>
                '.focus_form_element($_GET['act']=='in'?'deposit':'withdraw'));
                addnav('','bank.php?op=gems_in_out');
                break;
        }

        case 'gems_in_out': //Edelsteine ein/auszahlen
        {
                $dragonkills=max(1,$session['user']['dragonkills']);
                $quantity=intval($_POST['deposit'])-intval($_POST['withdraw']);
                if($quantity>0)
                { //Einzahlen, Begrenzung ist einzig das Total-Limit (DK*10 oder 1000)
                        if($quantity>$session['user']['gems'])
                        {
                                $quantity=$session['user']['gems'];
                        }

                        $max_storage=min(1000,$dragonkills*10);
                        if($session['user']['gemsinbank']+$quantity>$max_storage)
                        {
                                $space_left=$max_storage-$session['user']['gemsinbank'];
                                $session['user']['gems']-=$space_left;
                                $session['user']['gemsinbank']+=$space_left;
                                output('`0Nachdem du '.$space_left.' Edelsteine in dein Depot gepackt hast, ist dieses so voll, `4mehr geht da beim besten Willen nicht hinein`0.');
                                debuglog($space_left.' Edelsteine auf die Bank');
                        }
                        else
                        {
                                $session['user']['gems']-=$quantity;
                                $session['user']['gemsinbank']+=$quantity;
                                output('`0Du legst '.$quantity.' Edelsteine in dein Depot.');
                                debuglog($quantity.' Edelsteine auf die Bank');
                        }
                }
                elseif($quantity<0)
                { //Auszahlen (Begrenzung DK*2 oder täglich erlaubte Hausentnahme)
                        $quantity*=-1;
                        $rowe = user_get_aei('gemsin');
                        $max_gemsperday=max((getsetting('housemaxgemsout',10) - $rowe['gemsin']),0);
                        if($quantity>$max_gemsperday)
                        {
                                output('`0Du darfst heute nur noch `4'.$max_gemsperday.' Edelsteine`0 entnehmen. ');
                                $quantity=$max_gemsperday;
                        }

                        if($session['user']['gemsinbank']<$quantity)
                        {
                                $quantity=$session['user']['gemsinbank'];
                                $session['user']['gems']+=$quantity;
                                $session['user']['gemsinbank']-=$quantity;
                                output('`0Nachdem du '.$quantity.' Edelsteine aus deinem Depot genommen hast, musst du feststellen, `4mehr ist da einfach nicht drin`0.');
                        }
                        else
                        {
                                $session['user']['gems']+=$quantity;
                                $session['user']['gemsinbank']-=$quantity;
                                output('`0Du nimmst '.$quantity.' Edelsteine aus deinem Depot.');
                        }
                        debuglog($quantity.' Edelsteine von der Bank');
                        user_set_aei(array('gemsin'=>$rowe['gemsin']+$quantity));
                }
                else
                { //plusminus Null
                        output('`0Du legst '.intval($_POST['deposit']).' Edelsteine ins Depot und nimmst '.intval($_POST['withdraw']).' Edelsteine heraus. Damit ändert sich an der Anzahl gar nichts.');
                }
                output('`nInsgesamt hast du jetzt `I'.$session['user']['gemsinbank'].' Edelsteine`0 deponiert.');
                break;
        }

        case 'borrow': //Gold borgen
        {
                if ($session['user']['reputation']<-35)
                {
                        output('Misstrauisch schaut dich der kleine Kerl eine Weile an. Dann, als ob er dein Gesicht erkannt hätte, atmet er ein und erklärt dir vorsichtig, dass er es nicht für klug hält, Leuten von deinem Schlag Geld zu leihen. Offenbar ist ihm dein schlechter Ruf zu Ohren gekommen und ist nun um den Ruf (und das Gold) seiner Bank besorgt...');
                }
                else
                {
                        $maxborrow = $session['user']['level']*getsetting('borrowperlevel',20);
                        output('`0<form action="bank.php?op=withdrawfinish" method="POST">
                        Du hast '.($session['user']['goldinbank']>=0?'ein Guthaben von':'Schulden in Höhe von').' '.abs($session['user']['goldinbank']).' Gold bei der Bank.
                        `nWieviel <u>l</u>eihen?
                        <input id="input" name="amount" size=5 accesskey="l">
                        <input type="hidden" name="borrow" value="x">
                        <input type="submit" class="button" value="Leihen">
                        `n(Mit deinem Level kannst du maximal '.$maxborrow." leihen.
                        `nGold wird abgehoben, bis dein Konto leer ist. Der Restbetrag wird geliehen.)
                        </form>
                        ".focus_form_element('input'));
                        addnav('','bank.php?op=withdrawfinish');
                }
                break;
        }

        case 'withdraw': //Gold auszahlen Eingabeformular
        {
                output('`0<form action="bank.php?op=withdrawfinish" method="POST">
                Du hast '.$session['user']['goldinbank'].' Gold bei der Bank.
                `nWieviel a<u>b</u>heben?
                <input id="input" name="amount" size=5 accesskey="b">
                <input type="submit" class="button" value="Abheben">
                `n`iGib 0 oder gar nichts ein, um alles abzuheben.`i`0
                </form>
                '.focus_form_element('input'));
                addnav('','bank.php?op=withdrawfinish');
                break;
        }

        case 'withdrawfinish': //Gold auszahlen
        {
                $_POST['amount']=abs((int)$_POST['amount']);                
                if ($_POST['amount']==0)
                {
                        $_POST['amount']=abs($session['user']['goldinbank']);
                }
                if ($_POST['amount']>$session['user']['goldinbank'] && $_POST['borrow']=='')
                {
                        output('`$FEHLER: Nicht genug auf dem Konto.`0`n`nNachdem du darüber informiert wurdest, dass du `^'.$session['user']['goldinbank'].'`0 Gold auf dem Konto hast, erklärst du dem Männlein mit der Lesebrille, dass du gerne `^'.$_POST['amount'].'`0 davon abheben würdest.`nDer Bankier schaut dich bedauernd an und erklärt dir die Grundlagen der Mathematik. Nach einer Weile verstehst du deinen Fehler und würdest es gerne nochmal versuchen.');
                }
                else if ($_POST['amount']>$session['user']['goldinbank'])
                {
                        $lefttoborrow = $_POST['amount'];
                        $maxborrow = $session['user']['level']*getsetting('borrowperlevel',20);
                        if ($lefttoborrow<=$session['user']['goldinbank']+$maxborrow)
                        {
                                if ($session['user']['goldinbank']>0)
                                {
                                        output('`0Du nimmst deine verbleibenden `^'.$session['user']['goldinbank'].'`0 Gold und ');
                                        $lefttoborrow-=$session['user']['goldinbank'];
                                        $session['user']['gold']+=$session['user']['goldinbank'];
                                        $session['user']['goldinbank']=0;
                                        //debuglog("withdrew " . $_POST[amount] . " gold from the bank");
                                }
                                else
                                {
                                        output('`0Du ');
                                }
                                if ($lefttoborrow-$session['user']['goldinbank'] > $maxborrow)
                                {
                                        output('fragst, ob du `^'.$lefttoborrow.'`0 Gold leihen kannst. Der kleine Mann informiert dich darüber, dass er dir in deiner gegenwärtigen Situation nur `^'.$maxborrow.'`0 Gold geben kann.');
                                }
                                else
                                {
                                        output('leihst dir `^'.$lefttoborrow.'`0 Gold.');
                                        $session['user']['goldinbank']-=$lefttoborrow;
                                        $session['user']['gold']+=$lefttoborrow;
                                        //debuglog("borrows $lefttoborrow gold from the bank");
                                }
                        }
                        else
                        {
                                output("`0Mit den schlappen `^" . $session['user']['goldinbank'] . "`0 Gold auf deinem Konto bittest du um einen Kredit von `^".($lefttoborrow-$session['user']['goldinbank'])."`0 Gold, aber
                        		der kurze kleine Mann informiert dich darüber, dass du mit deinem Level höchstens `^$maxborrow`0 Gold leihen kannst.");
                        }
                }
                else
                {
                        $session['user']['goldinbank']-=$_POST['amount'];
                        $session['user']['gold']+=$_POST['amount'];
                        //debuglog("withdrew " . $_POST[amount] . " gold from the bank");
                        output('`0Du hast `^'.$_POST['amount'].'`0 Gold von deinem Bankkonto abgehoben. Du hast damit `^'.$session['user']['goldinbank'].'`0 Gold auf deinem Konto und `^'.$session['user']['gold'].'`0 Gold hast du bei dir.');


                }
                break;
        }

        case 'speculate': //Spekulationsgeschäfte
        {
                $object = e_rand(1,30);
                switch ($object)
                {
                        case 1:
                                $object_desc = 'Jemand hat einen Stuhl auf Rädern erfunden, mit dem man sich durch den Raum bewegen kann, ohne aufzustehen!';
                                break;
                        case 2:
                                $object_desc = 'Hier spielt jemand Flöte mit seiner Nase und möchte eine alternative Musikergruppe gründen.';
                                break;
                        case 3:
                                $object_desc = 'Ein Bauer schwört auf das von ihm entwickelte Ochsenmilchmixgetränk und will es im ganzen Land verkaufen.';
                                break;
                        case 4:
                                $object_desc = 'Jemand hat ein beschleunigtes Verfahren für die Beschneidung Tasmanischer Teufel entwickelt und sucht Probanden.';
                                break;
                        case 5:
                                $object_desc = 'Hier behauptet einer, einen Dschin in seiner Butterdose gefangen zu halten. Nun muss er einen starken Mann anheuern, da er sie allein nicht mehr aufbekommt.';
                                break;
                        case 6:
                                $object_desc = 'Ein alter, übel riechender Mann behauptet zu wissen wo ein Schatz vergraben ist und bittet um Ausrüstung und jede Menge Schnaps.';
                                break;
                        case 7:
                                $object_desc = 'Eine Gruppe zerzottelter Kinder möchte mit einem Lied über den Monsun ganz groß rauskommen und benötigt Musikinstrumente.';
                                break;
                        case 8:
                                $object_desc = 'Da hat jemand eine Kutsche erfunden, die sich ohne Pferde fortbewegen kann.';
                                break;
                        case 9:
                                $object_desc = 'Ein Handwerker baut an einer Maschine, die Männern mit der Kraft von 4 Macheten vollautomatisch den Bart schneiden kann!';
                                break;
                        case 10:
                                $object_desc = 'Ein Kleinwüchsiger hat einen Ring mit seltsamen Zeichen geschenkt bekommen und braucht nun dringend eine Kutsche zum nächsten Vulkan.';
                                break;
                        case 11:
                                $object_desc = 'Hier ist ein Akrobat, der für seine Hochseilnummer ein neues Netz benötigt... und eine neue Assistentin.';
                                break;
                        case 12:
                                $object_desc = 'Ein junger Bursche hat etwas mit dem Namen Fantasy-Rollenspiel erfunden und möchte diese Zeitverschwendung nun verbreiten.';
                                break;
                        case 13:
                                $object_desc = 'In der Stadt gibt es einen Greis, der behauptet mit Tieren sprechen zu können. Dafür benötigt er einen Hörtrichter, da er seit einigen Jahren fast taub ist.';
                                break;
                        case 14:
                                $object_desc = 'Ein Mann namens McLuvCoolGanckstarr behauptet, den rhytmischen Sprechgesang erfunden zu haben.';
                                break;
                        case 15:
                                $object_desc = 'Eine Frau ist dabei einen Keuschheitsgürtel für Männer zu erfinden und steht kurz vor dem Durchbruch.';
                                break;
                        case 16:
                                $object_desc = 'Jemand hat eine Biersorte erfunden, die nach Himbeer-Radieschen mit einem Hauch Gurke schmeckt.';
                                break;
                        case 17:
                                $object_desc = 'Eine religiöse Gruppe benötigt irdische Pfründe um der Erleuchtung näher zu kommen.';
                                break;
                        case 18:
                                $object_desc = 'Jemand züchtet Halblingskraut mit der zehnfachen Stärke.';
                                break;
                        case 19:
                                $object_desc = 'Ein Alchemist forscht an einer Methode, Kieselsteine in Goldnuggets zu verwandeln.';
                                break;
                        case 20:
                                $object_desc = 'Ein Handwerksbursche hat einen tragbaren Ofen erfunden, der sich besonders im Wald optimal einsetzen lässt um Nahrung zu kochen.';
                                break;
                        case 21:
                                $object_desc = 'Eine Hausfrau hat Butter entwickelt, die nicht im Kühlschrank hart wird. Nun braucht die dringend jemanden, der den Kühlschrank erfindet.';
                                break;
                        case 22:
                                $object_desc = 'Ein bärtiger Mann behauptet, Wein in Wasser verwandeln zu können und versucht nun die Leute davon zu überzeugen, dass dies sinnvoll ist.';
                                break;
                        case 23:
                                $object_desc = 'Hier vertreibt jemand Zahnkorken für Vampire und Maulkörbe für Werwölfe.';
                                break;
                        case 24:
                                $object_desc = 'Darauf hat die Welt gewartet! Seife am Strick!';
                                break;
                        case 25:
                                $object_desc = 'Ein aufgedrehter Hofnarr mit dem Namen Ronald möchte sein erstes Restaurant für Frikadellenbrötchen eröffnen.';
                                break;
                        case 26:
                                $object_desc = 'Ein in weiß gekleideter Mann mit Spitzbart taucht Hühner in siedendes Öl und versucht, diese in Eimern als Mahlzeit für zwischendurch zu verkaufen.';
                                break;
                        case 27:
                                $object_desc = 'Jemand plant, vor der Höhle des Grünen Drachen eine Mautstelle zu errichten.';
                                break;
                        case 28:
                                $object_desc = 'Hier meint einer, was der Stadt noch fehlt sei eine Rennbahn für Hängebauchschweine.';
                                break;
                        case 29:
                                $object_desc = 'Ein dicker Kerl mit schütterem Haar träumt von einer Plantage nur für Würstchenbäume.';
                                break;
                        case 30:
                                $object_desc = 'Jemand hat eine Cognac-Sorte entwickelt, die nicht betrunken macht, allerdings dafür stark nach Gurkenwasser schmeckt.';
                                break;
                }

                output('`0Du wirfst einen Blick auf die Liste der verzweifelten Männer und Frauen dieser Stadt, die glauben eine bahnbrechende Idee zu haben und nun einen Dum... äh Investor suchen, der ihnen die Umsetzung finanziert.`n`nAh ja, da haben wir doch gleich etwas, das dir aus der Fülle der Angebote ins Auge springt:`n`n`@'.$object_desc.'`0`n`n`nDie Investition in dieses Projekt würde dich `^'.($session['user']['level']*100).' `0 Goldmünzen kosten.`nWillst du es unterstützen?');
                addnav("Ja");
                addnav("Zahlen","bank.php?op=invest");
                addnav("Nein");
                addnav("Lieber nicht","bank.php");
                addnav("Schnell weg");
                break;
        }

        case 'invest' : //Spekulationsgeschäfte
        {
                if ($session['user']['turns']<1)
                {
                        output("Du hast leider heute keine Zeit mehr für Finanzgeschäfte.`nKomm morgen wieder!");
                }
                elseif ($session['user']['gold']<($session['user']['level']*100))
                {
                        output("Du hast leider nicht genug Gold in deiner Tasche um dieses Projekt zu unterstützen.`n");
                }
                else
                {
                        require_once(LIB_PATH.'profession.lib.php');
                        $player = user_get_aei('job,jobturns');
                        $p_job = $player['job'];

                        $s_min = -100;
                        $s_max = 65;
                        if ($p_job==5)
                        {
                                $s_min+=35;
                                $s_max+=15;
                        }

                        $success = e_rand($s_min,$s_max);
                        if ($success==0)
                        {
                                $success++;
                        }
                        if ($success<-50)
                        {
                                $p_result = "entwickelte sich zu einem Disaster!";
                        }
                        elseif ($success<0)
                        {
                                $p_result = "lief nicht so gut wie erwartet.";
                        }
                        elseif ($success<50)
                        {
                                $p_result = "verlief zufriedenstellend.";
                        }
                        else
                        {
                                $p_result = "war ein voller Erfolg!";
                        }
                        output("Das von dir unterstützte Projekt ".$p_result."`n`n");

                        $earning = ($session['user']['level']*100)*$success*0.01;
                        if ($earning<0)
                        {
                                output("`^Du verlierst ".abs($earning)." Goldmünzen.`0`n`n");
                        }
                        else
                        {
                                output("`@Du gewinnst ".$earning." Goldmünzen.`0`n`n");
                                if ($p_job==5 && $player['jobturns']>0)
                                {
                                        $xpgain=round($session['user']['experience']*0.02);
                                        if ($xpgain<50) $xpgain=150;
                                        $player['jobturns']--;
                                        output("`@Als Bankier erhältst du durch dieses gute Geschäft ".$xpgain." Erfahrungspunkte. Dies ist heute dein `g".(5-$player['jobturns']).".`@ erfolgreiches Geschäft.");
                                        $session['user']['experience']+=$xpgain;
                                        user_set_aei(array('jobturns'=>$player['jobturns']));
                                }
                        }
                        $session['user']['gold']+=$earning;
                        output("`n`n`0Die Aufregung hat dich einen Waldkampf gekostet!");
                        $session['user']['turns']--;
                }
                break;
        }

        case 'transferlog': //letzte Transaktionen anzeigen
        {
                $sql='SELECT a.login,date,target,message
                FROM debuglog d
                LEFT JOIN accounts a ON a.acctid=d.target
                WHERE actor='.$session['user']['acctid'].'
                AND target>0
                AND (message LIKE "%Gold überwiesen an%"
                        OR message LIKE "%Edelsteine versendet an%")
                ORDER BY id DESC
                LIMIT 25';
                $result=db_query($sql);
                if(db_num_rows($result)>0)
                {
                        output('Der Herr hinter dem Schalter blättert in seinen Aufzeichnungen und sagt dann `v"Wie ich sehe habt Ihr in den letzten Tagen diese Transaktionen getätigt:"`0
                        `n<table border=0 cellpadding=10>');
                        while($row=db_fetch_assoc($result))
                        {
                                $i++;
                                output('<tr class="'.($i%2?'trlight':'trdark').'">
                                <td style="padding-top:5px; padding-bottom:5px" title="'.$row['date'].'">'.$row['message'].' '.$row['login'].'.</td>
                                </tr>');
                        }
                        output('</table>');
                }
                else
                {
                        output('Der Herr hinter dem Schalter blättert in seinen Aufzeichnungen, kann aber in den letzten Tagen keine Transaktionen von dir finden.');
                }
                break;
        }

        default: //Fehler
                die('Du bist in der Besenkammer gelandet. Wenn du nicht die Putzfrau bist, die hier etwas abstauben will, schreibe bitte eine Anfrage. Code '.$_GET['op']);
}
addnav('Zurück zum Marktplatz','market.php');

if ($_GET['op']!='speculate')
{
        bank_nav();
}

function bank_nav()
{
        global $session,$demouser_acctid;
        addnav('Gold');
        if ($session['user']['goldinbank']>=0)
        {
                addnav('Abheben','bank.php?op=withdraw');
                addnav('Einzahlen','bank.php?op=deposit');
                if (getsetting('borrowperlevel',20))
                {
                        addnav('Kredit aufnehmen','bank.php?op=borrow');
                }
        }
        else
        {
                addnav('Schulden begleichen','bank.php?op=deposit');
                if (getsetting('borrowperlevel',20))
                {
                        addnav('Mehr leihen','bank.php?op=borrow');
                }
        }
        if (getsetting('allowgoldtransfer',1) && ($session['user']['level']>=getsetting('mintransferlev',3) || $session['user']['dragonkills']>0) && $session['user']['acctid']!=$demouser_acctid)
        {
                addnav('Gold überweisen','bank.php?op=transfer');
        }
        addnav('Edelsteine');
        addnav('Deponieren','bank.php?op=gemsdepot&act=in');
        addnav('Mitnehmen','bank.php?op=gemsdepot&act=out');
        if (getsetting('allowgemtransfer',1) && $session['user']['spirits']!=RP_RESURRECTION && $session['user']['acctid']!=$demouser_acctid)
        {
                addnav('Edelsteine versenden','bank.php?op=gemstrans');
        }
        addnav('Statistik');
        addnav('T?Zeige Transaktionen','bank.php?op=transferlog');
        addnav('Finanzgeschäfte');
        addnav('Spekulieren','bank.php?op=speculate');
}
page_footer();
?>