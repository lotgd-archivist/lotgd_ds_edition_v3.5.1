<?php
//////////////////
// Der Angelsee //
//////////////////

// 04.09.06: Neugeschrieben und extrem korrigiert by Maris (Maraxxus@gmx.de)

// Basiert auf der Idee von:
/*********************************************
Lots of Code from: lonnyl69 - Big thanks for the help.
By: Kevin Hatfield - Arune v1.0
06-19-04 - Public Release
Written for Fishing Add-On - Poseidon Pool
Translation and simple modifications by deZent deZent@onetimepad.de
*********************************************/

require_once 'common.php';
checkday();
addcommentary();
music_set('waldsee');
page_header('Der magische See');

$sql = 'SELECT worms,minnows,boatcoupons,fishturn FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
$result = db_query($sql);
$rowf = db_fetch_assoc($result);
$minnows=$rowf['minnows'];
$worms=$rowf['worms'];
$boatcoupons=$rowf['boatcoupons'];
$fishturn=$rowf['fishturn'];



/*******************
Minnows
*******************/

function check1()
{
        global $session;
        global $minnows;
        global $worms;
        global $boatcoupons;
        global $fishturn;
        global $Char;

        if($fishturn<0)
        {
                output('`q... oder lieber doch nicht. Für heute hast du vom Angeln definitiv genug!');
                return;
        }
        elseif($minnows<0)
        {
                output('`qKurze Zeit später siehst du ein, dass sich die Fische nicht mit einem blanken Haken zufrieden geben.');
                return;
        }
        elseif($_GET['su_action'])
        {
        $int_decide = $_GET['su_action'];
        }
        else
        {
                $int_decide = e_rand(1,50);
        }

        switch ($int_decide)
        {
                // Boot (0)
                case 1:
                case 2:
                        output('`wEin Boot zieht an dir vorüber - wie romantisch!
                        `nAber leider werden dadurch auch die Fische aufgescheucht und du fängst nichts.');
                break;

                // Goldfund (+)
                case 3:
                case 4:
                $a=e_rand(2,75);
                        output('`wDu fängst einen kleinen Beutel...
                        `n`nDarin findest du `^'.$a.' Gold!');
                        $session['user']['gold']+= $a;
                break;

                // Verletzung (-)
                case 5:
                        $b=e_rand(10,100);
                        output('`wBeim Auswerfen verfängt sich der Angelhaken in deinem Ohr!!!!
                        `n`n`qDu verlierst `^'.$b.' Lebenspunkte.');
                        $session['user']['hitpoints'] -= $b;
                        if ($session['user']['hitpoints']<=0)
                        {
                                $session['user']['hitpoints']=1;
                                output('`n`n`$ Ramius akzeptiert deinen jämmerlichen Anglertod nicht!
                                `n Er gibt dir einen Lebenspunkt, da er sein Schattenreich nicht mit unfähigen Weicheiern füllen möchte!
                                `n`n`!So ein gefährlicher See!
                                `n`4Du entscheidest dich, heute lieber nicht mehr zu angeln...');
                                $fishturn=0;
                        }
                break;

                // Nix wars (0)
                case 6:
                        output('`wMit all deinem Können hast du nichts gefangen!');
                break;

                // Stiefel (0)
                case 7:
                case 8:
                        output('`wDu bist dir sicher, dass du einen schweren Fisch am Haken hast!!!
                        `n`n`@.........
                        `n`@Leider war es doch nur ein alter, vergammelter Stiefel');
                break;

                // Verletzung (-)
                case 9:
                        output('`qDein Haken verfängt sich in deiner Hand!!
                        `nDu `4verlierst 12 Lebenspunkte.');
                        $session['user']['hitpoints']-=12;
                        if ($session['user']['hitpoints']<=0)
                        {
                                $session['user']['hitpoints']=1;
                                output('`n`n`$ Ramius akzeptiert deinen jämmerlichen Anglertod nicht!
                                `n Er gibt dir einen Lebenspunkt, da er sein Schattenreich nicht mit unfähigen Weicheiern füllen möchte!');
                        }
                break;

                // Eingeschlafen (0)
                case 10:
                        output('`wLeider bist du beim Fischen eingeschlafen und hast nicht mitbekommen ob etwas angebissen hat!');
                break;

                // 1 Edelstein (+)
                case 11:
                        output('`wGerade als du deine Leine einholst, siehst du im feuchten Gras etwas schimmern.....
                        `n`n`^`bDu findest einen Edelstein!!!`b');
                        $session['user']['gems']+=1;
                break;

                // 3 Würmer (+)
                case 12:
                case 13:
                        output('`wDu fängst etwas...
                        `n`n`mEin alter Schinken hängt an deinem Angelhaken...
                        `n`n`7`bDarin findest du 3 Würmer!`b');
                        addnav('Würmer behalten?');
                        addnav('In den Beutel!','fish.php?op=wormsplus&wp=3');
                break;

                // Silberkreuz (++)
                case 14:
                        output('`wDu fängst ein seltsames `&Silberkreuz`w!
                        `n`n`7Als du es vom Haken nimmst beginnt es leicht zu leuchten.
                        `nEin pulsierendes Leuchten erhellt das Ufer!!!');
                        if(mb_strstr($session['user']['weapon'],'glühend'))
                        {
                                output('`n`n`b`4Deine Waffe glüht bereits...`b');
                        }
                        else
                        {
                                output('`w Du fühlst dich stärker und auch etwas zäher!
                                `n`#Deine Verteidigung erhöht sich um `^einen Punkt`#.
                                `nDeine Waffe wird um `^einen Punkt`# stärker.
                                `nDeine Lebenspunkte erhöhen sich `^permanent um einen Punkt`#.');
                                debuglog('Weapon - Glowing enhancement from pool');
                                $session['user']['maxhitpoints']+=1;
                                $session['user']['defence']+=1;
                                $newweapon = 'glühend - '.$session['user']['weapon'];
                                $atk = $session['user']['weapondmg']+1;
                                item_set_weapon($newweapon, $atk, -1, 0, 0, 1);
                        }
                break;

                // schwere Verletzung (-)
                case 15:
                        output('`wDer Wind erfasst deine Angelschnur und wickelt sie um deinen Hals... Der Haken verfängt sich in deinem Mund!
                        `n`n`QIn Panik ziehst du an deiner Angel!
                        `n`4Dabei ziehst du die Schlinge noch fester zu und fällst auf den Boden!
                        `n`$ Ramius akzeptiert deinen jämmerlichen Anglertod nicht!
                        `nEr gibt dir einen Lebenspunkt, da er sein Schattenreich nicht mit unfähigen Weicheiern füllen möchte!');
                        $fishturn=0;
                        $session['user']['hitpoints']=1;
                break;

                // Fliege weg (0)
                case 16:
                case 17:
                        output('`wDeine Fliege ist dir vom Haken gehüpft und freut sich ihres Lebens...
                        `n`n`qSeit wann können Fliegen springen?!?!');
                break;

                // Nix wars (0)
                case 18:
                        output('`wDu hast nichts gefangen!');
                break;

                // Charmeverlust (-)
                case 19:
                case 20:
                        if($Char->race=='mwn')
                        {
												output('`wDu rutschst aus und fällst ins Wasser!
												`nNatürlich ist es ein Leichtes für dich, dich wieder ans Ufer zu begeben, doch dort musst du feststellen, dass du nicht nur klatschnass bist, sondern sich eine Ladung Algen in deinen Haaren und Kleidern verfangen hat.
												`nDu fühlst dich hässlich und verlierst 2 Charmepunkte!');
												}
												else
												{
												output('`wDu rutschst aus und fällst ins Wasser!
                        `nDa du nicht gut schwimmen kannst, kannst du dich gerade noch an Land retten.
                        `n`qDurch diese peinliche Vorstellung verlierst du 2 Charmepunkte!');
                        }
                        $session['user']['charm']=max(0,$session['user']['charm']-2);
                break;

                // Charmegewinn (+)
                case 21:
                        output('`wDu hast Mitleid mit der Fliege und schenkst ihr die Freiheit!
                        `n`gDabei fühlst du dich sehr gut und erhältst einen Charmepunkt.');
                        $session['user']['charm']+=1;
                break;

                // Volle LP (+)
                case 22:
                case 23:
                        output('`wDu fängst einen enormen Barsch!
                        `n`n`7Da du eh Hunger hast isst du ihn noch am See.');
                        $session['user']['hitpoints']=max($session['user']['maxhitpoints'],$session['user']['hitpoints']);
                break;

                // Alle Köder weg (-)
                case 24:
                        output('`wDu spürst einen Ruck an der Angel!
                        `n`n`#Du ziehst mit einem Ruck... stolperst zurück und stößt deinen Beutel mit Ködern ins Wasser.
                        `n`qDu verlierst alle deine Köder!');
                        $minnows=0;
                        $worms=0;
                break;

                // Verletzung (-)
                case 25:
                case 26:
                        output('`wDu spürst einen Ruck an der Angel!
                        `n`nDu springst zurück und zerrst mit all deiner Kraft an der Rute!
                        `n`qZUVIEL für deine Rute! Sie bricht und schlägt dir ins Gesicht!
                        `n`QAUTSCH! Direkt ins Auge.... das hat weh getan!');
                        $session['user']['hitpoints']-=75;
                        if ($session['user']['hitpoints']<=0)
                        {
                                $session['user']['hitpoints']=1;
                                output('`n`n`$Ramius akzeptiert deinen jämmerlichen Anglertod nicht!
                                `n Er gibt dir einen Lebenspunkt, da er sein Schattenreich nicht mit unfähigen Weicheiern füllen möchte!');
                        }
                break;

                // Fluch (--)
                case 27:
                        $earn=e_rand(150,600);
                        output('`wDu ziehst eine verfaulte Wasserleiche an Land!
                        `n`n`7Nach kurzem Überlegen untersuchst du ihren Goldbeutel,
                        `n`^und findest '.$earn.' Gold!
                        `n`n`q Die Seejungfrau des Sees findet deine Aktion jedoch nicht sehr nett und verflucht dich!
                        `n`Q Du verlierst einen Punkt Angriff und einen Punkt Verteidigung.
                        `n`qAußerdem darfst du heute nicht mehr angeln!');
                        $fishturn=0;
                        $session['user']['gold']+=$earn;
                        $session['user']['attack']=max(ceil($session['user']['level']/2),$session['user']['attack']-1);
                        $session['user']['defence']=max(ceil($session['user']['level']/2),$session['user']['defence']-1);
                break;

                // Erfahrung (+)
                case 28:
                case 29:
                case 30:
                        output('`wDu fängst leider nichts!
                        `n`n Eine Erfahrung mehr in deinem Leben..
                        `n`7 Du lernst, dass man nicht immer gewinnen kann und bekommst 100 Erfahrungspunkte.');
                        $session['user']['experience']+=100;
                break;

                // 5 Würmer
                case 31:
                        output('`wBeim Auswerfen der Leine siehst du eine Box mit Würmern neben dir im Gebüsch!
                        `n`n`7Du findest 5 Würmer!');
                        addnav('Würmer behalten?');
                        addnav('In den Beutel!','fish.php?op=wormsplus&wp=5');
                break;

                // 2 Edelsteine (+)
                case 32:
                        output('`wDu fängst einen kleinen Lederbeutel!
                        `n`n`^Darin findest du 2 Edelsteine!');
                        $session['user']['gems']+=2;
                break;

                // Nix (0)
                case 33:
                case 34:
                        output('`wDu siehst eine kleine Welle, die sich sehr schnell auf deinen Köder zubewegt!
                        `n`n`$ZU`7 schnell für deinen Geschmack!
                        `nSicherheitshalber ziehst du deine Angel schnell wieder ein!');
                break;

                // Kleine Verletzung (-)
                case 35:
                case 36:
                        output('`wEin kleiner Goldfisch springt ans Ufer und beißt dir in den Zeh!
                        `n`qAUTSCH!');
                        $session['user']['hitpoints']-=5;
                        if ($session['user']['hitpoints']<=0)
                        {
                                $session['user']['hitpoints']=1;
                                output('`n`$Ramius akzeptiert deinen jämmerlichen Anglertod nicht!
                                `n Er gibt dir einen Lebenspunkt, da er sein Schattenreich nicht mit unfähigen Weicheiern füllen möchte!');
                        }
                break;

                // At-Bonus (+)
                case 37:
                        output('`wDu triffst genau ins Zentrum des Sees!
                        `n`n`gEin Blitz durchfährt deinen Körper.
                        `n`7Die Götter meinen es heute gut mit dir! `^Du fühlst dich stärker! `2Dein Angriff steigt um einen Punkt.');
                        $session['user']['attack']++;
                break;

                // At-Abzug (-)
                case 38:
                        output('`wDu fängst einen prächtigen Fisch, der in allen Farben des Regenbogens leuchtet!
                        `n`qPech! Dieser Fisch war wohl einem Gott heilig, der dich nun für deinen Frevel straft!
                        `nEin Blitz durchzuckt deinen Körper und du fühlst dich schwächer!
                        `n`4Du verlierst einen Angriffspunkt.');
                        $session['user']['attack']=max(ceil($session['user']['level']/2),$session['user']['attack']-1);
                break;

                // Gold weg (-)
                case 39:
                        output('`qDu stolperst über einen Stein und fällst ins Wasser!
                        `n`n`wNatürlich landest du an der seichtesten Stelle des Sees und knallst mit dem Kopf auf einen Stein.
                        `n`4Als du wieder aufwachst stellst du fest, dass dir jemand dein ganzes Gold gestohlen hat!');
                        $session['user']['hitpoints']=1;
                        $fishturn=0;
                        $session['user']['gold']=0;
                break;

                // Nix (0)
                case 40:
                        output('`wDu hast nichts gefangen!`n`n');
                break;

                // Wasserschrein (0)
                case 41:
                        db_query("UPDATE account_extra_info SET fishturn=$fishturn,minnows=$minnows,worms=$worms,boatcoupons=$boatcoupons WHERE acctid = ".$session['user']['acctid']);
                        redirect ('watershrine.php');
                break;

                // Forelle (+)
                case 42:
                case 43:
                        output('`wDu hast eine Forelle gefangen!
                        `nDie wird bestimmt sehr gut schmecken. Bis zur Zubereitung packst du sie erstmal in dein Inventar.');
                        $itemnew = item_get_tpl('tpl_id="fsh_frl"');

                        if( is_array($itemnew) )
                        {
                                include_once(ITEM_MOD_PATH.'kitchen.php');
                                kitchen_set_qual($itemnew['tpl_hvalue'],$itemnew['tpl_description']);

                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;

                // Goldfisch (+)
                case 44:
                case 45:
                        output('`wDu hast einen Goldfisch gefangen!
                        `nOb der wohl schmeckt? Bis zur Zubereitung packst du ihn erstmal in dein Inventar.');
                        $itemnew = item_get_tpl('tpl_id="fsh_gld"');

                        if( is_array($itemnew) )
                        {
                                include_once(ITEM_MOD_PATH.'kitchen.php');
                                kitchen_set_qual($itemnew['tpl_hvalue'],$itemnew['tpl_description']);

                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;

                // Nix (0)
                default:
                        output('`wMit all deinem Können hast du nichts gefangen!');
                break;
        }
        db_query("UPDATE account_extra_info SET fishturn=$fishturn,minnows=$minnows,worms=$worms,boatcoupons=$boatcoupons WHERE acctid = ".$session['user']['acctid']);
}

/************************
Worms
************************/

function check2()
{
        global $session;
        global $minnows;
        global $worms;
        global $boatcoupons;
        global $fishturn;
        global $fishdelete;
        if($fishturn<0)
        {
                output('`q... oder lieber doch nicht. Für heute hast du vom Angeln definitiv genug!');
                return;
        }
        elseif($worms<0)
        {
                output('`wDu wirfst deinen unsichtbaren Wurm aus und fängst einen unsichtbaren, prachtvollen Fisch! `qTja, schön wärs...');
                return;
        }
        elseif($_GET['su_action'])
        {
        $int_decide = $_GET['su_action'];
        }
        else
        {
                $int_decide = e_rand(1,50);
        }

        switch ($int_decide)
        {
                // Nichts (0)
                case 1:
                case 2:
                        output('`wDu hast, wenn man es genauer betrachtet, NICHTS gefangen!');
                break;

                // Bauernkinder ärgern (0)
                case 3:
                case 4:
                case 5:
                        $sql = "SELECT name FROM accounts WHERE dragonkills=0 AND ".user_get_online()." ORDER BY RAND() LIMIT 1";
                                $result = db_query($sql);
                                $amount = db_num_rows($result);

                                if ($amount>0)
                                {
                                        $row=db_fetch_assoc($result);
                                        $name=$row['name'];
                                }
                                else
                                {
                                        $name='jemand';
                                }
                        output('`7Du holst weit mit der Angel aus, sehr weit, sehr sehr weit...
                        `n`n\'`4AUTSCH!!!`7\' hörst du `&'.$name.' `7 lauthals rufen.
                        `nSchnell lässt du die Angel fallen und suchst dir einen anderen Platz.');
                break;

                // 3 Edelsteine (+)
                case 6:
                        output('`wDu fängst einen schweren Lederbeutel...
                        `n`gDarin findest du `^3 Edelsteine!');
                        $session['user']['gems']+=3;
                break;

                // Charmebonus (+)
                case 7:
                        output('`wDu fängst einen enormen Fisch!
                        `nViele Fischer werden auf dich neidisch sein.
                        `n`2Du bekommst 1 Charmepunkt!');
                        $session['user']['charm']+=1;
                break;

                // Nix... (0)
                case 8:
                case 9:
                        output('`7Deine Angelschnur ist gerissen!
                        `nDu verlierst deinen Köder.');
                break;

                // 15 Fliegen (+)
                case 10:
                case 11:
                case 12:
                        output('`wAls du deinen Haken einholst siehst du, dass du einen Büschel Seegras gefangen hast.
                        `nDer Büschel stinkt so sehr, dass sofort `215 Fliegen dran hängen.');
                        addnav('Fliegen behalten?');
                        addnav('In den Beutel!','fish.php?op=minnowsplus&mp=15');
                break;

                // Nix (0)
                case 13:
                case 14:
                        output('`wAuch nach einer Stunde hast du noch nichts gefangen!');
                break;

                // Wieder nix (0)
                case 15:
                case 16:
                        output('`7Du siehst jemanden hinter dem Gebüsch und rufst ihm laut `i`&HALLO!`7`i zu.
                        `nIn diesem Moment fällt dir ein wie dumm das von dir war...
                        `n`n`qNatürlich weißt du, dass du für die nächste Stunde alle Fische verscheucht hast!');
                break;

                // Immer noch nix (0)
                case 17:
                        output('`wDu hast nichts gefangen!');
                break;

                // Nichts (0)
                case 18:
                        output('`7Du hast den Köder neben den See geworfen... Eine Stunde später bist du dir endlich sicher, dass man an Land keine Fische fangen kann...');
                break;

                // Nix (0)
                case 20:
                output('`wDu hast nichts gefangen!');
                break;

                // Verteidigungs-Bonus (+)
                case 21:
                        output('`wAls du deine Leine einholst, siehst du etwas Glühendes am Haken hängen.
                        `n`gEin schwacher Energiestoß trifft deinen Körper.
                        `n`n`2Deine Verteidigung steigt um `^2 Punkte!');
                        $session['user']['defence']+=2;
                break;

                // Kristall (++)
                case 22:
                        output('`wDu fängst einen Kristall!
                        `n`n`#Als du den Kristall in deiner Hand hältst..
                        `nbeginnt das schwarze Wasser blau zu leuchten!!!
                        `n`n');
                        if(mb_strstr($session['user']['weapon'],'gehärtet'))
                        {
                                output('`b`4Deine Waffe ist bereits gehärtet!`b');
                        }
                        else
                        {
                                output('`wDeine Waffe wird schwerer und irgendwie fühlt sie sich mächtiger an.
                                `nDie Stärke deiner Waffe erhöht sich um `^5 Punkte!
                                `n`wDeine Verteidigung steigt um `^3 Punkte.
                                `n`wDu erhältst `^5 permanente Lebenspunkte`w dazu!');
                                debuglog('Weapon - Crystalized enhancement from pool');
                                $session['user']['maxhitpoints']+=5;
                                $session['user']['defence']+=3;
                                $newweapon = 'gehärtet - '.$session['user']['weapon'];
                                $atk = $session['user']['weapondmg']+5;
                                item_set_weapon($newweapon, $atk, -1, 0, 0, 1);
                                $fishturn=0;
                                addnews('`@'.$session['user']['name'].'`@ hat heute beim Angeln einen großen Fang gemacht!');
                        }
                break;

                // Großer Fisch (0)
                case 23:
                case 24:
                case 25:
                        output('`wDu fängst einen gigantischen Fisch!!
                        `nZappelnd ziehst du ihn ans Ufer!
                        `n`qAls du ihn mit all deinen Kräften an Land gezogen hast und feststellst, dass er nicht zurück ins Wasser will, sondern sich schnappend in deine Richtung bewegt, ziehst du schnell deine Waffe.!
                        `n`wUnsicher stellst du dich dem Fisch..`n');
                        if ($session['user']['attack']<35)
                        {
                                output('`4Gerade als du zustechen willst, packt dich der Fisch unerwartet am Fuß und zieht dich ins Wasser.
                                `n`n Du wehrst dich mit all deiner Kraft, doch das pechschwarze Wasser raubt dir bereits den Blick zur Sonne.
                                `n Der Fisch zieht dich immer weiter in die Tiefen des Sees...
                                `nDu verlierst 500 Erfahrungspunkte.');
                                $session['user']['experience']=max(0,$session['user']['experience']-500);
                                $session['user']['hitpoints']=max(0,$session['user']['hitpoints']-250);
                                if ($session['user']['hitpoints']<=0)
                                {
                                        $fishturn=0;
                                        addnav('Ramius wartet...');
                                        killplayer(0,0,0,'shades.php','Na dann...');
                                        addnews('`%'.$session['user']['name'].'`w hatte eine unheimliche Begegnung mit einem Fisch.');
                                }
                        }
                        else
                        {
                                output('`wDer Fisch packt dich am Fuß, du nutzt deine Chance und erlegst ihn gekonnt mit deine(m) '.$session['user']['weapon'].'!
                                `n`7Leider ist der Fisch zu schwer um ihn mit zu nehmen - `2dennoch erhältst du 1000 Erfahrungspunkte.');
                                $session['user']['experience']+=1000;
                        }
                break;

                // Gold weg (-)
                case 26:
                case 27:
                        output('`7Du bist beim Fischen eingeschlafen....
                        `nAls du wieder aufwachst, stellst du fest, dass dein `qganzes Gold verschwunden ist!');
                        $session['user']['gold']=0;
                break;

                // 2 Edelsteine weg (-)
                case 28:
                        output('`wEtwas zerrt an deiner Angel. Du verlierst das Gleichgewicht und stolperst mit einem Ruck nach vorn,`n');
                        if ($session['user']['gems']>1)
                        {
                                output('wodurch sich dein Edelsteinbeutel öffnet und du `42 Edelsteine verlierst!');
                                $session['user']['gems']-=2;
                        }
                        else
                        {
                                output('kannst dich aber gerade noch so halten.');
                        }
                break;

                // Charme-Bonus (+)
                case 29:
                case 30:
                        output('`7Weit entfernt siehst du den Umriss einer Gestalt durch den dichten Nebel schimmern... Es könnte '.($session['user']['sex']?'ein Klabautermann ':'eine Seejungfrau ').'sein...
                        `n`n`gEs ist '.($session['user']['sex']?'ein Klabautermann ':'eine Seejungfrau ').'!! `2Du bekommst einen Charmepunkt!');
                        $session['user']['charm']+=1;
                break;

                // Def-Abzug (-)
                case 31:
                        output('`wAls du deine Leine einholst siehst du etwas bedrohlich Glühendes am Haken hängen.
                        `n`qEin schmerzhafter Energiestoß trifft deinen Körper.
                        `n`n`QDeine Verteidigung sinkt um `4einen Punkt!');
                        $session['user']['defence']=max(ceil($session['user']['level']/2),$session['user']['defence']-1);
                break;

                // Anglertod (-)
                case 32:
                case 33:
                        output('`wDer Wind erfasst deine Angelschnur und wickelt sie um deinen Hals... Der Haken verfängt sich in deinem Mund!
                        `n`n`QIn Panik ziehst du an deiner Angel!
                        `n`4Dabei ziehst du die Schlinge noch fester zu und fällst auf den Boden!');
                        $session['user']['hitpoints']=0;
                        $fishturn=0;
                        addnav('Ätsch, erwischt...');
            CQuest::died();
                        addnav('Na toll!','shades.php');
                        addnews('`%'.$session['user']['name'].'`& hat beim Angeln einen `bzu`b großen Fang gemacht.');
                break;

                // Gold weg (-)
                case 34:
                        output('`7Du hast einen Beutel `^Gold`7 gefangen!
                        `nGanz auf all das Gold fixiert zählst du die Münzen!
                        `n`4BOOM! `qDu wurdest von etwas stumpfen getroffen... Und gehst zu Boden!
                        `n`n`&`iWieder einer auf den alten Goldbeuteltrick reingefallen`i`7 hörst du gerade noch, als bei dir das Licht ausgeht!
                        `n`QDein ganzes Gold ist natürlich auch weg...
                        `nFür heute wars das mit dem Angeln!');
                        $session['user']['hitpoints']=1;
                        $fishturn=0;
                        $session['user']['gold']=0;
                break;

                // Fluch der Götter (--)
                case 35:
                        output('`7Du denkst dir, dass es schon sehr unwürdig für einen Krieger ist, in aller Ruhe zu angeln, während der Drache sein Unwesen treibt.
                        `nDiese Ansicht teilen auch die Götter.
                        `n`n`4Sie verfluchen dich! Dein Angriff und deine Verteidigung sinken um jeweils 2 Punkte!');
                        addnews('`@'.$session['user']['name'].'`@ bekam heute beim Angeln von den Göttern eine Lektion erteilt.');
                        $session['user']['defence']=max(ceil($session['user']['level']/2),$session['user']['defence']-2);
                        $session['user']['attack']=max(ceil($session['user']['level']/2),$session['user']['attack']-2);
                        $fishturn=0;
                break;

                // Meißel (++)
                case 36:
                        output('`wDu hast einen Meißel am Haken!
                        `n`n`gAls du über die vielfältigen Einsatzgebiete eines Meißels nachdenkst, berührst du versehntlich deine Rüstung.');
                        if(mb_strstr($session['user']['armor'],'verstärkt'))
                        {
                                output('`n`b`4Leider war deine Rüstung auch zuvor schon verstärkt. Ein zweites Mal funktioniert das nicht. Schaaade!`0`b');
                        }
                        elseif($session['user']['armordef']==0 && $session['user']['armor']!='Straßenkleidung')
                        { //User hat Luxusgewand an
                                output('`n`b`4Dummerweise hast du keine Rüstung sondern deine Sonntagskleidung an, welche nun einen Schmutzfleck aufweist. Du verlierst einen Charmepunkt.`0`b');
                                $session['user']['charm']=max(0,$session['user']['charm']-1);
                        }
                        else
                        {
                                output('`n`2Wow..irgendwie passt deine Rüstung jetzt viel besser als zuvor. Sie wirkt auch irgendwie stabiler!
                                `n`&Deine Rüstung wird um `^3 Punkte`& stärker!
                                `n`nMit der neuen Rüstung siehst du viel besser aus!
                                `n`^Du bekommst 1 Charmepunkt!
                                `n`gVor lauter Freude wirfst du den Meißel wieder in den See.');
                                debuglog('Armor - Chisel enhancement from pool');
                                $newarmor = 'verstärkt '.$session['user']['armor'];
                                $session['user']['charm']+=1;
                                item_set_armor($newarmor, $session['user']['armordef']+3, -1, 0, 0, 1);
                        }
                break;

                // Nix (0)
                case 37:
                        output('`wDu hast nichts gefangen!');
                break;

                // Wasserschrein (0)
                case 38:
                        db_query("UPDATE account_extra_info SET fishturn=$fishturn,minnows=$minnows,worms=$worms,boatcoupons=$boatcoupons WHERE acctid = ".$session['user']['acctid']);
                        redirect ('watershrine.php');
                break;

                // Joke (0)
                case 39:
                case 40:
                        output('`wDu triffst ziemlich in die Mitte des Sees!
                        `nEtwas schweres hängt an deinem Angelhaken... du ziehst... mit aller Kraft...
                        `n`n`4PLOPP!
                        `nDu hast den Stöpsel gezogen!
                        `nDer komplette See, einschliesslich dir wird in den Abfluss gesogen!
                        `nDeine Existenz ist ausgelöscht!`n');
                        $fishdelete=1;
                        addnav('Weiter','fish_delete.php');
                break;

                // Lachs (+)
                case 41:
                        output('`wDu fängst einen prächtigen Lachs!
                        `nIn deinem Inventar wird er gut aufgehoben sein.');
                        $itemnew = item_get_tpl('tpl_id="fsh_lax"');
                        if( is_array($itemnew) )
                        {
                                include_once(ITEM_MOD_PATH.'kitchen.php');
                                kitchen_set_qual($itemnew['tpl_hvalue'],$itemnew['tpl_description']);
                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;

                // Flunder (+)
                case 42:
                case 43:
                        output('`wDu fängst eine Flunder!
                        `nIn deinem Inventar wird sie gut aufgehoben sein.');
                        $itemnew = item_get_tpl('tpl_id="fsh_fld"');
                        if( is_array($itemnew) )
                        {
                                include_once(ITEM_MOD_PATH.'kitchen.php');
                                kitchen_set_qual($itemnew['tpl_hvalue'],$itemnew['tpl_description']);
                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;

                // Aal (+)
                case 44:
                case 45:
                        output('`wDu fängst einen Aal!
                        `nIn deinem Inventar wird er gut aufgehoben sein.');
                        $itemnew = item_get_tpl('tpl_id="fsh_aal"');
                        if( is_array($itemnew) )
                        {
                                include_once(ITEM_MOD_PATH.'kitchen.php');
                                kitchen_set_qual($itemnew['tpl_hvalue'],$itemnew['tpl_description']);
                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;

                // Karpfen (+)
                case 46:
                        output('`wDu fängst einen Karpfen!
                        `nIn deinem Inventar wird er gut aufgehoben sein.');
                        $itemnew = item_get_tpl('tpl_id="fsh_krp"');
                        if( is_array($itemnew) )
                        {
                                include_once(ITEM_MOD_PATH.'kitchen.php');
                                kitchen_set_qual($itemnew['tpl_hvalue'],$itemnew['tpl_description']);
                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;

                // Topf (0)
                case 47:
                        output('`wDu hast etwas schweres an der Angel!
                        `n`#Ach, leider nur ein verbeulter Topf... du nimmst ihn dennoch mit.');
                        $itemnew = item_get_tpl('tpl_id="fsh_topf"');
                        if( is_array($itemnew) )
                        {
                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;

                // Nix (0)
                default:
                        output('`wMit all deinem Können hast du nichts gefangen!');
                break;
        }
        db_query("UPDATE account_extra_info SET fishturn=$fishturn,minnows=$minnows,worms=$worms,boatcoupons=$boatcoupons WHERE acctid = ".$session['user']['acctid']);
}

/*******************
Boat
*******************/

function check4()
{
        global $session;
        global $minnows;
        global $worms;
        global $boatcoupons;
        global $fishturn;
        if($fishturn<0)
        {
                output('`q... oder lieber doch nicht. Für heute hast du vom Angeln definitiv genug!');
                return;
        }
        elseif($boatcoupons<0)
        {
                output('`qLeider jedoch musst du vorher bezahlen um mit einem Boot rausfahren zu können!');
                return;
        }
        elseif($_GET['su_action'])
        {
                $int_decide = $_GET['su_action'];
        }
        else
        {
                $int_decide = e_rand(1,50);
        }

        switch ($int_decide)
        {
                // Nix (0)
                case 1:
                case 2:
                case 3:
                        output('`wVoller Vorfreude springst du ins Boot und greifst die Ruder.
                        `nLeider machst du dabei so einen Lärm, dass alle Fische gewarnt sind und du nichts fängst!');
                break;

                // Wieder nix (0)
                case 4:
                case 5:
                case 6:
                        if($session['user']['exchangequest']==19) redirect('exchangequest.php');
                        output('`7Was für ein dämlicher Knoten!
                        `nDu brauchst eine halbe Ewigkeit um dein Boot vom Steg zu lösen und als du es endlich geschafft hast, da ist deine Zeit auch schon um.
                        `nDieser Coupon war vollkommen verschwendet!');
                break;

                // 2 Edelsteine + Ansehen (+)
                case 7:
                case 8:
                        output('`^Mann über Bord!!
                        `n`wIn direkter Nähe zu dir stürzt ein anderer Angler aus seinem Boot und droht zu ertrinken!
                        `n`gDu rettest ihm das Leben und wirst von ihm mit `^2 Edelsteinen`g belohnt.
                        `nZusätzlich steigt dein Ansehen in der Stadt um `^5 Punkte`g!');
                        $session['user']['gems']+=2;
                        $session['user']['reputation']+=5;
                break;

                // Gestank (-)
                case 9:
                case 10:
                        output('`wDu ruderst auf den See hinaus...
                        `n`qPlötzlich stellst du fest, dass dein Boot ein Leck hat und Langsam vollläuft!
                        `n`wMit aller Kraft versuchst du das Ufer zu erreichen, doch es ist bereits zu spät&nbsp;- du gehst im hohen Schilf unter.
                        `n`6Zwar ist das Wasser dort nicht tief und du kannst dich auch an Land retten, allerdings bist du über und über mit Algen behangen.
                        `n`4Du stinkst grauenhaft!');
                        $res = item_tpl_list_get( 'tpl_id="fldgestank" LIMIT 1' );
                        if( db_num_rows($res) )
                        {
                                $itemnew = db_fetch_assoc($res);
                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                        $session['bufflist']['Höllengestank'] = array(
                                'name'=>'Höllengestank',
                                'rounds'=>10,
                                'wearoff'=>'`QDas Blut deines Gegners überdeckt den Höllengestank.`0',
                                'roundmsg'=>'`QDer verfluchte Höllengestank an dir macht deinen Gegner besonders aggressiv`0',
                                'badguyatkmod'=>1.08,
                                'activate'=>'offense');
                break;

                // sterben (-)
                case 11:
                        output('`wDu ruderst weit auf den See hinaus und wirfst die Angel aus.
                        `n`qDabei verlierst du das Gleichgewicht und fällst ins Wasser.
                        `n`4Irgendetwas hat da unten bereits auf dich gewartet und zieht dich in die Tiefe!
                        `n`$Du bist tot.');
                        $session['user']['hitpoints']=0;
                        $fishturn=0;
                        addnav('Stirb!');
                        addnav('Ok...','shades.php');
                    CQuest::died();
                        addnews('`%'.$session['user']['name'].'`w verlor beim Rudern das Gleichgewicht und wurde in die Tiefen des Sees gezogen!');
                break;

                // Schatz (+)
                case 13:
                        $earn=e_rand(1000,5000);
                        output('`wAls du ein gutes Stück hinausgerudert bist, wirfst du die Angel aus.
                        `nIrgendetwas scheint an deinem Haken festzuhängen!
                        `n`n`gDu ziehst mit aller Kraft und kannst eine kleine Truhe bergen.
                        `n`2Sie enthält `^'.$earn.' Goldmünzen!!!');
                        $session['user']['gold']+=$earn;
                break;

                // Rundenbonus (+)
                case 14:
                case 15:
                case 16:
                case 17:
                        output('`wDu ruderst auf den See hinaus und entdeckst mehrere abgelegene Stellen, die von Fischen nur so zu wimmeln scheinen.
                        `n`gVorsichtig legst du wieder an Land an, um sie nicht zu vertreiben.
                        `n`2Mit diesem Wissen kannst du heute weitere drei mal angeln!');
                        $fishturn+=3;
                break;

                // Rundenabzug (-)
                case 18:
                case 19:
                case 20:
                        output('`wNachdem du ein gutes Stück hinaus gerudert bist und deine Angel ausgeworfen hast, beißt plötzlich etwas an.
                        `nEs muss ein gewaltiger Fisch sein - denn er zieht dich an der Angel mitsamt dem Boot quer durch den See!
                        `nDie anderen Angler winken dir zunächst amüsiert zu, doch als sie merken, dass du ihnen mit deinem Geschrei gerade alle Fische vertrieben hast, ballen sie zornig die Fäuste.
                        `n`qAls du irgendwann wieder am sicheren Ufer angelangt bist entscheidest du, dass es besser wäre, dich heute hier nicht mehr sehen zu lassen.');
                        $fishturn=0;
                break;

                // Nix (0)
                case 21:
                case 22:
                        if($session['user']['exchangequest']==19) redirect('exchangequest.php');
                        output('`7Irgendwie bist du wohl zu schwer, oder das Boot zu alt. Es sinkt noch am Ufer.
                        `nDu kannst dich gerade noch rausretten, ohne nass zu werden.');
                break;

                // Zu Ramius (0)
                case 23:
                        output('`wIn der Mitte des Sees wird dein Boot plötzlich von einem Strudel erfasst und in die Tiefe gerissen.
                        `nDu hast einen Direktzugang zu Ramius Reich gefunden!
                        `n`qDa der Gott der Toten zur Zeit gar nichts mit dir anfangen kann, gewährt er dir 100 Gefallen, damit du so schnell wie möglich wieder aus dem Totenreich verschwindest ohne ihn groß zu belästigen.');
                        $session['user']['deathpower']+=100;
                        $session['user']['hitpoints']=0;
                        $fishturn=0;
                        addnav('Ramius besuchen');
                        addnav('Tach auch!','shades.php');
                    CQuest::died();
                        addnews('`%'.$session['user']['name'].'`w weiß jetzt, dass es Strudel im See gibt!');
                break;

                // Würmer oder Fliegen (+)
                case 25:
                case 26:
                case 27:
                        output('`wAls du in deinem Boot auf den See hinaus ruderst, siehst du plötzlich die Ausrüstung eines Anglers an dir vorbei treiben.
                        `nDie Rute ist wohl hinüber, allerdings kannst du zwei Köderbeutel ausmachen.
                        `n`gIn einem sind Fliegen, im anderen Würmer. Einen dieser Beutel könntest du zu dir ins Boot ziehen.');
                        addnav('Würmer einstecken!','fish.php?op=wormsplus&wp=7');
                        addnav('Fliegen einstecken!','fish.php?op=minnowsplus&mp=10');
                break;

                // Charmeverlust (-)
                case 28:
                case 29:
                        output('`7Du ruderst wie ein Weltmeister, kommst aber nicht vom Fleck.
                        `nBis dir irgendwann jemand mitteilt, dass dein Boot immer noch am Steg befestigt ist.
                        `n`qPeinlich, peinlich! Und weil das auch jeder mitbekommen hat `4verlierst du einen Charmepunkt.');
                        $session['user']['charm']=max(0,$session['user']['charm']-1);
                break;

                // Ungeheuer (0)
                case 30:
                        output('`wGerade als du schön weit rausgerudert bist, schlingen sich Tentakel um dein Boot und zerren daran.
                        `n`QEin Seeungeheuer hat es auf dich abgesehen!!!`n');
                        if ($session['user']['attack']<50)
                        {
                                output('`4Du haust wie wahnsinnig mit deiner Waffe auf die Tentakel ein, aber es hilft dir nichts, du wirst mitsamt dem Boot in einem Happs verschlungen...');
                                $session['user']['experience']*=0.95;
                                $session['user']['hitpoints']=0;
                                $fishturn=0;
                                addnav('So long, Fishburger');
                                addnav('Sterben','shades.php');
                            CQuest::died();
                                addnews('`%'.$session['user']['name'].'`w machte Bekanntschaft mit einem Seeungeheuer!');
                        }
                        else
                        {
                                output('`#Mit Heldenmut und cleverem Einsatz gelingt es dir, alle Tentakel mit deiner Waffe zu treffen.
                                `nDie Bestie ist davon so überrascht, dass sie von dir ablässt - `2du erhältst 2500 Erfahrungspunkte.');
                                $session['user']['experience']+=2500;
                        }
                break;

                // Forelle (+)
                case 31:
                case 32:
                case 33:
                case 34:
                        output('`wDu hast eine Forelle gefangen!
                        `nDie wird bestimmt sehr gut schmecken. Bis zur Zubereitung packst du sie erstmal in dein Inventar.');
                        $res = item_tpl_list_get( 'tpl_name="Forelle" LIMIT 1' );
                        if( db_num_rows($res) )
                        {
                                $itemnew = db_fetch_assoc($res);
                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;

                // Lachs (+)
                case 35:
                case 36:
                case 37:
                case 38:
                        output('`wDu fängst einen prächtigen Lachs!
                        `nIn deinem Inventar wird er gut aufgehoben sein.');
                        $res = item_tpl_list_get( 'tpl_name="Lachs" LIMIT 1' );
                        if( db_num_rows($res) )
                        {
                                $itemnew = db_fetch_assoc($res);
                                item_add( $session['user']['acctid'], 0, $itemnew);
                        }
                break;

                // Goldverlust (-)
                case 39:
                case 40:
                        output('`wDu ruderst auf den See hinaus. Auf einmal wird dein Boot von einer kräftigen Windböe gepackt und durchgeschüttelt.
                        `n`qAls sich alles wieder beruhigt hat stellst du fest, dass sich dein Goldbeutel gelöst hat und ins Wasser gefallen ist.
                        `nDu siehst ihn gerade noch untergehen!');
                        $session['user']['gold']=0;
                break;

                default:
                        output('`wDiese Bootsfahrt war zwar schön, aber völlig umsonst - du hast nichts gefangen!');
                break;
        }
        db_query("UPDATE account_extra_info SET fishturn=$fishturn,minnows=$minnows,worms=$worms,boatcoupons=$boatcoupons WHERE acctid = ".$session['user']['acctid']);
}

/************************
Fishing with Golden Egg
************************/

function check3()
{
        global $session;
        global $minnows;
        global $worms;
        global $boatcoupons;
        global $fishturn;
        $chance=(e_rand(1,10));
        if($_GET['su_action'])
        {
                $chance = $_GET['su_action'];
        }

        switch ($chance)
        {
                case 1:
                        output('`VGerade als du in Träumen um den Reichtum schwelgst, der dich erwartet...
                        `nmacht es
                        `n`n`4BOOM`V
                        `n`nHalb bewusstlos siehst du gerade noch jemanden mit dem `^goldenen Ei`V unter dem Arm davonlaufen.');
                        $session['user']['hitpoints']=1;
                break;

                case 2:
                        output('`VDas `^Ei`V geht unter... aber plötzlich beginnt das Wasser an der Stelle wild zu blubbern und zu schäumen.
                        `nEtwas SEHR großes nähert sich aus den Tiefen des Sees!!!!
                        `nVor lauter Schreck lässt du die Angel fallen, die vom Gewicht des `^Ei`Vs sofort untergeht.');
                break;

                case 3:
                        output('`VDu spürst einen Ruck und bevor du dich versehen kannst, hältst du nur noch eine abgebissene Leine in der Hand!');
                break;

                case 4:
                        output('`VSchon bald beginnt etwas heftig an deinem Köder zu zerren.
                        `nEmsig ziehst du es an Land...
                        `nZwar ist das `^Ei`V fort, jedoch befindet sich stattdessen eine seltsame Waffe an deiner Leine.
                        `n`nDu hast das legendäre Schwert `4Ausweider`V gefunden!!!
                        `n`nIn Angst man könne es dir wieder wegnehmen eilst du sofort zum Stadtzentrum und vergisst vor lauter Aufregung deine alte Waffe am Ufer.');
                        $item_ausw = item_get_tpl(' tpl_id="ausweider" ');
                        $ausw_id = item_add($session['user']['acctid'],0,$item_ausw);
                        item_set_weapon($item_ausw['tpl_name'], $item_ausw['tpl_value1'], $item_ausw['tpl_gold'], $ausw_id, 0, 2);
                break;

                case 5:
                        output('`VDu spürst einen Ruck an deiner Leine!
                        `nAls du die Angel einholst, entdeckst du anstelle des Köders einen kleinen Eimer
                        `ngefüllt mit `@50`V Edelsteinen!!!');
                        $session['user']['gems']+=50;
                break;

                case 6:
                        output('`VDie Fee des Sees nimmt dein Geschenk dankend an.
                        `nAls Zeichen ihrer Wertschätzung belohnt sie dich reich.
                        `n`nDu erhältst:
                        `n`@10 permanente Lebenspunkte,
                        `n5 Punkte Angriff und 5 Punkte Verteidigung,
                        `n10 Punkte Charme
                        `nund 300 Gefallen bei Ramius!');
                        $session['user']['maxhitpoints']+=10;
                        $session['user']['attack']+=5;
                        $session['user']['defence']+=5;
                        $session['user']['charm']+=10;
                        $session['user']['deathpower']+=300;
                break;

                case 7:
                        output('`VDu hast etwas sehr Schweres an der Leine!
                        `nEmsig ziehst du es an Land...
                        `nPlötzlich beginnt deine Leine zu glühen und das Glühen geht auch auf dich über.
                        `nDas `^Ei`V ist zwar fort, doch eine `%Schutzaura`V umgibt dich!');
                        if($session['user']['armordef']==0 && $session['user']['armor']!='Straßenkleidung')
                        { //User hat Luxusgewand an
                                output('`n`nÜberglücklich willst du dir deine alte, schwere Rüstung vom Leib reißen und fortwerfen, als du gerade noch rechtzeitig bemerkst dass du ja deine Sonntagskleidung an hast.
                                `nDu bemerkst, dass die Schutzaura wohl erst wirkt wenn du deine Kleidung wechselst.');
                                $item_ausw = item_get_tpl(' tpl_id="schtzaura" ');
                                $item_ausw['gold']=1;
                                $item_ausw['gems']=0;
                                item_add($session['user']['acctid'],0,$item_ausw);
                        }
                        else
                        {
                                output('`n`nÜberglücklich reißt du dir deine alte, schwere Rüstung vom Leib und wirfst sie fort. Die wirst du nicht wieder brauchen.');
                                $item_ausw = item_get_tpl(' tpl_id="schtzaura" ');
                                $ausw_id = item_add($session['user']['acctid'],0,$item_ausw);
                                item_set_armor($item_ausw['tpl_name'], $item_ausw['tpl_value1'], $item_ausw['tpl_gold'], $ausw_id);
                        }
                break;

                case 8:
                        output('`VDas `^Ei`V geht unter wie ein Stein!
                        `nDie schlaff herabhängene Leine gibt dir das ungute Gefühl, dass diese Aktion nicht besonders clever war.');
                break;

                case 9:
                        output('`VEtwas hat angebissen!
                        `nDoch mit gewaltiger Kraft wird dir deine Angelrute mitsamt `^Ei`V aus den Händen gerissen... So ein Pech aber auch...');
                break;

                case 10:
                        output('`VEs tut sich absolut gar nichts...
                        `nAls du nach einer ganzen Weile deinen wertvollen Köder wieder an Land ziehen willst merkst du, dass nur noch ein wertloser Stein an deiner Leine hängt!');
                break;
                default:
                        output('Du solltest nicht hier sein. Fehlercode'.$chance);
        }
        db_query("UPDATE account_extra_info SET fishturn=$fishturn,minnows=$minnows,worms=$worms,boatcoupons=$boatcoupons WHERE acctid = ".$session['user']['acctid']);
}



/************************
Fishprank
************************/

function fishprank()
{
        global $session;
        global $minnows;
        global $worms;
        global $fishturn;

        $str_output = '`0...und wartest geduldig darauf, dass ein Fisch an die Leine geht, doch nichts geschieht. Nach einer geraumen Zeit ziehst du enttäuscht deine Angel wieder ein, doch was ist das???`n`n
        An deiner Angel hängt kein Köder mehr sondern ein kleiner Zettel auf dem folgendes steht:`n
        `vHeute wars wohl nichts mit Angeln`n
        Gruß '.getsetting('fishprank','').'`n`n
        `0Verwundert entfernst du den Zettel und probierst es mit einem neuen Köder, doch erneut tut sich nichts - alle Fische scheinen verschwunden zu sein.`n`n
        `tDu gibst auf und lässt es für heute sein.';
        output($str_output);

        savesetting('fishprank','');

        $session['user']['turns']=max(0,$session['user']['turns']-1);
        $fishturn = 0;
        user_set_aei(array('fishturn'=>0,'minnows'=>$minnows,'worms'=>$worms),$session['user']['acctid']);
}



/************************
Auswertung des Parameters
************************/
$event=$_GET['op'];
switch ($event)
{
        case 'awake':
                output('`@Puh!
                `nDiese Tagträume werden aber auch immer gemeiner!');
        break;

        case 'wormsplus':
                output('Du steckst die Würmer in deinen Köderbeutel...');
                $worms+=$_GET['wp'];
                $inventory=$worms+$minnows;
                if ($inventory>100)
                {
                        output('`n`4Leider bekommst du nicht alle dort hinein, da der Beutel bereits zu voll ist.');
                        $worms-=($inventory-100);
                }
                db_query("UPDATE account_extra_info SET fishturn=$fishturn,minnows=$minnows,worms=$worms,boatcoupons=$boatcoupons WHERE acctid = ".$session['user']['acctid']);
        break;

        case 'minnowsplus':
                output('Du steckst die Fliegen in deinen Köderbeutel...');
                $minnows+=$_GET['mp'];
                $inventory=$worms+$minnows;
                if ($inventory>100)
                {
                        output('`n`4Leider bekommst du nicht alle dort hinein, da der Beutel bereits zu voll ist.');
                        $minnows-=($inventory-100);
                }
                db_query("UPDATE account_extra_info SET fishturn=$fishturn,minnows=$minnows,worms=$worms,boatcoupons=$boatcoupons WHERE acctid = ".$session['user']['acctid']);
        break;

        // Minnows
        case 'check1':
        if (($fishturn > 0) AND ($minnows>0) AND ($session['user']['hitpoints']>0))
        {
                output('Du wirfst Deine Angel aus...`n`n');
                $minnows--;
                $fishturn--;
                if(getsetting('fishprank','') != '')
                {
                        fishprank();
                }
                else
                {
                        check1();
                }
        }
        elseif($_GET['su_action'])
        {
                check1();
        }
        break;

        // Worms
        case 'check2':
        if (($fishturn > 0) AND ($worms>0) AND ($session['user']['hitpoints']>0))
        {
                output('Du wirfst Deine Angel aus...`n`n');
                $worms--;
                $fishturn--;
                if(getsetting('fishprank','') != '')
                {
                        fishprank();
                }
                else
                {
                        check2();
                }
        }
        elseif($_GET['su_action'])
        {
                check2();
        }
        break;

        // Boat
        case 'check4':
        if (($fishturn > 0) AND ($boatcoupons>0) AND ($session['user']['hitpoints']>0))
        {
                output('Du entwertest einen Coupon und besteigst eins der Ruderboote...`n`n');
                $boatcoupons--;
                $fishturn--;
                check4();
        }
        elseif($_GET['su_action'])
        {
                check4();
        }
        break;

        // Golden Egg
        case 'check3':
        if (($session['user']['acctid']==getsetting('hasegg',0) AND ($fishturn > 0) AND ($session['user']['hitpoints']>0)))
        {
                output('`VDu wickelst Deine Leine sorgfältig um das `^goldene Ei`V und lässt es vorsichtig zu Wasser...`n`n');
                savesetting('hasegg','0');
                item_set(' tpl_id="goldenegg"', array('owner'=>0) );
                addnews('`@'.$session['user']['name'].'`@ hat das `^Goldene Ei`@ beim Angeln verloren`V!');
                $fishturn=0;
                check3();
        }
        break;

        default:
        output('`BI`§n `3ei`#ne`Fr kleinen Einbuchtung des Sees ragt der Steg, den man schon aus der Ferne erblicken kann, in den See hinaus. Das Schilfgras wirkt an diesem Ort noch höher und wiegt sich spielerisch im Wind. Als letztes Anzeichen von Zivilisation scheint die Natur den Steg langsam zurückzuerobern, denn Algen kriechen die Holzstämme hinauf, die das Gebilde tragen und tief in den Grund des Sees gerammt wurden. `nWeit hinaus führt der Steg zu tieferen Stellen des Sees. Wahrscheinlich tummeln sich dort mehr Fische, die man so einfacher fangen kann. Bei jedem Schritt hörst du ein leises Knarren und so ist es fraglich, ob das morsche Holz wirklich noch Personen tragen kann. Ganz am Ende ist der Zauber des Lichtes, das sich im Wasser bricht, noch intensiver als am Ufer und das leise Geräusch der Wellen, die gegen die Holzpfähle treffen, noch besse`#r z`3u h`§ör`Ben.');
        break;
}

/************
Fischen...
************/

if (!$fishdelete==1)
{
        if ($session['user']['hitpoints']>0)
        {
            if ($session['user']['dragonkills']>1)
            {
            if ((($minnows>0) || ($worms>0) || ($boatcoupons>0)) && ($fishturn>0)) addnav('Angeln');
                if (($minnows>0) && ($fishturn>0)) addnav('Fliege auswerfen','fish.php?op=check1');
                if (($worms>0) && ($fishturn>0)) addnav('Wurm auswerfen','fish.php?op=check2');
                if (($boatcoupons>0) && ($fishturn>0)) addnav('Ein Boot nehmen','fish.php?op=check4');
                if (($session['user']['acctid']==getsetting('hasegg',0)) && ($fishturn>0)) addnav('`VDas `^goldene Ei`V als Köder verwenden','fish.php?op=check3');
                if ($session['user']['exchangequest']==10) addnav('`%Flöte spielen','exchangequest.php');
                /*
                if ($access_control->su_check(access_control::SU_RIGHT_DEBUG))
                {
                        addnav('Debug-Aktionen');
                        addnav('Glühend','fish.php?op=check1&su_action=14',false,false,false,false);
                        addnav('Gehärtet','fish.php?op=check2&su_action=22',false,false,false,false);
                        addnav('Meißel','fish.php?op=check2&su_action=36',false,false,false,false);
                        addnav('Stöpsel','fish.php?op=check2&su_action=39',false,false,false,false);
                        addnav('Stinken','fish.php?op=check4&su_action=10',false,false,false,false);
                        addnav('Bauernkinder ärgern','fish.php?op=check2&su_action=5',false,false,false,false);
                        addnav("Wasserschrein","fish.php?op=check1&su_action=41",false,false,false,false);
                }
                */
            }
                addnav('Wege');
            if ($session['user']['dragonkills']>1)addnav('Angelshop','bait.php');

                //Bossgegner Jörmungandr einfügen
                include_once(LIB_PATH.'boss.lib.php');
                boss_get_nav('jormungandr');

                addnav('S?Zurück zum See','pool.php');
                addnav('Zurück zum Stadtzentrum','village.php');

                output('`n`n');
                viewcommentary('fishing', 'Etwas schreiben', 25, 'sagt');
        }
}
headoutput('`c`b`§D`3e`#r `FS`#t`3e`§g`0`b`c
`nDu hast in deinem Beutel:
`n`IFliegen - `y'.($minnows>0?$minnows:'0').'
`n`IWürmer - `y'.($worms>0?$worms:'0').'
`n`IBootscoupons - `y'.($boatcoupons>0?$boatcoupons:'0').'
`n`n`IRunden zum fischen - `q'.($fishturn>0?$fishturn:'0').'
`n`n`0');

page_footer();

?>