<?php

// Rohgerüst für ein späteres Add-on
// by Maris (Maraxxus@gmx.de)
// Änderungen: Rohgerüst aufgefüllt

// Schlüssel für DDL-location
// --------------------------
// 0 : Atrahor
// 1 : Zeltlager (Hauptplatz)
// 2 : Expeditionsleiter
// 3 : Gemeinschaftszelt
// 4 : Lagerarzt
// 5 : Lagerwache
// 6 : Heiße Quellen
// 7 : Einöde
// 8 : Tropfsteinhöhle
// 9 : Unterwegs
// 10 : Im Zelt
// 11 : Antreteplatz
// 12 : Forscherpfad
// 13 : Schmiede

require_once 'common.php';
require_once(LIB_PATH.'profession.lib.php');
music_set('expedition');
addcommentary(false);
checkday();
page_header('Expedition in die dunklen Lande - 2');

        function get_DDL_location($location)
        {
                switch ($location)
                {
                case 1 :
                        $text="`&Zeltlager`0";
                        break;
                case 2 :
                        $text="`&Expeditionsleiter`0";
                        break;
                case 3 :
                        $text="`&Gemeinschaftszelt`0";
                        break;
                case 4 :
                        $text="`&Lagerarzt`0";
                        break;
                case 5 :
                        $text="`&Lagerwache`0";
                        break;
                case 6 :
                        $text="`&Heiße Quellen`0";
                        break;
                case 7 :
                        $text="`&Einöde`0";
                        break;
                case 8 :
                        $text="`&Tropfsteinhöhle`0";
                        break;
                case 9 :
                        $text="`&Unterwegs`0";
                        break;
                case 10 :
                        $text="`&In einem Privatzelt`0";
                        break;
                case 11 :
                        $text="`&Antreteplatz`0";
                        break;
                }
                return($text);
        }

$xstate = getsetting("DDL-state",6);
if ($xstate==1 && $_GET['op']!='risestate')
{
        page_header('Expedition in die dunklen Lande');
        output("`4Das Lager wurde vollkommen geplündert und zerstört. Nur noch Trümmer und verkohltes Holz erinnern daran, dass hier einmal ein stolzer Außenposten ".getsetting('townname','Atrahor')."s stand. Du schwelgst in kurzen wehmütiger Erinnerung an bessere Zeiten, bevor du dich wieder auf dein
        Reittier setzt und dich traurig zurück zur Stadt begibst...");
        if ($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN))
        {
                addnav('Mod-Aktionen');
                addnav('Zustand erhöhen','expedition.php?op=risestate',false,false,false,false);
                //addnav('Zustand senken','expedition.php?op=lowerstate',false,false,false,false);
                addnav('Zurück');
        }
        addnav('Zurück nach '.getsetting('townname','Atrahor'),'village.php');
}
else
{
        if ($session['user']['alive']==0)
        {
                redirect('shades.php');
        }

        $session['user']['specialinc']='';
        $session['user']['specialmisc']='';

        switch ($_GET['op'])
        {
        case 'whosthere' :
        {
                $where = $_GET['where'];
                $session['user']['DDL_location'] = $where;
                page_header('Expedition in die dunklen Lande');
                if ($where==1)
                {
                        output('`2Folgende Helden befinden sich gerade mit dir in den Räumen der Expedition:`n`n');
                        $sql = "SELECT         acctid,name,level,login,loggedin,dragonkills,sex,DDL_location
                                FROM accounts
                                WHERE DDL_location>0 AND loggedin=1
                                ORDER BY dragonkills DESC, level DESC
                                LIMIT 50";
                }
                else
                {
                        $DDL_location=get_DDL_location($where);
                        output('`2Anwesende im Raum '.$DDL_location.':`n`n');
                        $sql = "SELECT acctid,name,level,login,loggedin,dragonkills,sex,DDL_location
                                FROM accounts
                                WHERE DDL_location=$where AND loggedin=1
                                ORDER BY dragonkills DESC, level DESC
                                LIMIT 50";
                }
                $result = db_query($sql);
                $str_output.="`0<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>
                <tr class='trhead'>
                <th>DKs</th>
                <th>Level</th>
                <th>Name</th>
                <th><img src=\"./images/female.gif\">/<img src=\"./images/male.gif\"></th>
                ".($where==1?"<th>Wo?</th>":'')."
                </tr>";
                $max = db_num_rows($result);
                for ($i=0; $i<$max; $i++)
                {
                        $row = db_fetch_assoc($result);
                        $str_output.="<tr class='".($i%2?"trdark":"trlight")."'>
                        <td>&nbsp;`^".$row['dragonkills']."`0&nbsp;</td>
                        <td>&nbsp;`^".$row['level']."`0&nbsp;</td>
                        <td>&nbsp;".CRPChat::menulink( $row)."`0&nbsp;</td>
                        <td align=\"center\">".($row['sex']?"<img src=\"./images/female.gif\">":"<img src=\"./images/male.gif\">")."</td>";
                        if ($where==1)
                        {
                                $DDL_location=get_DDL_location($row['DDL_location']);
                                $str_output.="<td>&nbsp;".$DDL_location."&nbsp;</td>";
                        }
                        $str_output.="</tr>";
                }
                output($str_output."</table>");
                $return = utf8_preg_replace("'[&?]c=[[:digit:]-]+'","",$_GET['ret']);
                $return = mb_substr($return,mb_strrpos($return,"/")+1);
                addnav("Zurück",$return);
                break;
        }
        

        
        case 'chief' : //Expeditionsleiter
        {
                $session['user']['DDL_location'] = 2;
                page_header('Expedition in die dunklen Lande - Expeditionsleiter');
                output('`c`b`aE`/x`Ype`;di`Sti`No`Sns`;le`Yit`/e`ar`0`b`c`n`ND`Su `;b`Ye`tg`/ib`ys`&t dich in das Zelt des Expeditionsleiters und siehst, dass auch andere Helden bereits dort sind und sich angeregt unterhalten. Hinter einem improvisierten Tisch sitzt der Leiter dieser Expedition und wird dir Rede und Antwort stehen. An der Wand erkennst du eine Liste derer, die auch eingeladen wurden. Direkt daneben hängt eine weitere Liste, die Regeln für das Verhalten auf dieser Expedition festlegt. Der Expeditionsleiter nimmt auch Kritik entgegen, ebenso wie Wünsche und Anr`ye`/g`tu`Yn`;g`Se`Nn.`0`n`I(OOC- und Feedbackraum)`0`n`n');

                require_once(LIB_PATH.'board.lib.php');
                output('`0`c');
                $int_pollrights = (($session['user']['ddl_rank'] == PROF_DDL_COLONEL) ? 2 : 1);
                if(poll_view('expedition_chief',$int_pollrights,$int_pollrights))
                {
                        output('`n`^~~~~~~~~`0`n`n',true);
                }
                output('`c');

                viewcommentary('expedition_chief','Sagen',25,"sagt");
                addnav('OOC');
                addnav('Regeln für die Expedition','expedition.php?op=rules');
                addnav('Helden vorschlagen','expedition.php?op=propose');
                addnav('Information');
                addnav('Der Auftrag','expedition.php?op=briefing');
                addnav('Rekrutierungsliste','expedition.php?op=recruit');
                if($session['user']['ddl_rank'] == PROF_DDL_COLONEL
                || $session['user']['ddl_rank'] == PROF_DDL_MAJOR
                || $access_control->su_check(access_control::SU_RIGHT_DEV))
                {
                        addnav('Generalstab');
                        addnav ('f?Umfrage erstellen','expedition.php?op=poll&pollsection=chief');
                        addnav ('Notizen des Generalstabs','expedition.php?op=board');
                        addnav ('Mehrfache Tauben','expedition.php?op=massmail');
                }
                addnav('Wer ist hier?');
                addnav('Umsehen','expedition.php?op=whosthere&where=2&ret='.URLEncode($_SERVER['REQUEST_URI']));
                addnav('Zurück');
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
        case 'rules' : //Info: Regeln für die Expedition
        {
                $session['user']['DDL_location'] = 2;
                page_header('Expedition in die dunklen Lande - Expeditionsleiter');
               
			   output('`c`b`IDie Regeln, die der Oberst der Expedition diktiert lauten aktuell:`0`b`c`n`n');
                output('`IRegeln für das Spiel in der Expedition`n`n
`I1. `0Die Bürgerwehr bietet geleitete Massenrollenspiele an, die sich thematisch um Krieg und Schlachten drehen. Dein Charakter sollte sich in ein freiwilliges Militär einfügen können und über entsprechende Fähigkeiten verfügen.`n
`I2. `0Die Mitglieder werden intern vorgeschlagen und diskutiert. Es ist auch möglich sich bei einem Oberst zu bewerben.`n
`I3. `0Die Einladung kann bei Inaktivität und Fehlverhalten zurück gezogen werden.`n
`I4. `0Multispiel ist nicht erlaubt.`n
`n`n');
			   
                viewcommentary('expedition_rules','Sagen',25,"sagt");
                addnav('Zurück','expedition.php?op=chief');
                break;
        }
        case 'briefing' : //Info: Der Auftrag
        {
                $session['user']['DDL_location'] = 2;
                page_header('Expedition in die dunklen Lande - Expeditionsleiter');
                output('`c`b`IDer Auftrag der Expedition`0`b`c`n
`b`I<u>Zum Hintergrund:`b</u>`n
`0Seher und andere magisch Begabte in '.getsetting('townname','Atrahor').' kündigten eine erschreckende Zukunft für die Stadt und ihre Bewohner an. Aus den verfluchten Ebenen nördlich des Regengebirges, im Folgenden die Dunklen Lande genannt, soll eine gewaltige Streitmacht finsterer Kreaturen in die befriedeten Gebiete einfallen und gewaltige Zerstörung und Tod bringen.`nDiesen Warnungen folgend wurde eine stattliche Gruppe der berühmtesten Helden '.getsetting('townname','Atrahor').'s ausgesandt, um die Dunklen Lande zu erkunden und mehr über die Schrecken herauszufinden.`n`n
<u>`b`IDie Expedition:`n`b</u>
`0Das Vorkommando fand eine karge, unwirtliche Steppe vor und errichtete das Lager nahe eines gewaltigen Felsmassivs, eingebettet in steile Klippen. Gut geschützt gegen Angriffe von mehreren Seiten kann es jedoch ebenso zur tödlichen Falle werden, denn es gibt nur einen einzigen Zugang. Der Auftrag der Expedition besteht darin, die Umgebung zu erkunden, Informationen über Landschaft, Pflanzen und Tiere zu gewinnen, sowie das Lager gegen vermeintliche Angriffe zu schützen. Nördlich des Lagers dehnt sich eine weite Einöde tief in die Dunklen Lande aus.`n`n
<u>`b`IDie Umgebung:`b`n</u>
`0In näherer Umgebung des Lager sind Steppen, Sumpflandschaften, Buschland und eine Felsenwüste vorzufinden, die insgesamt als unwirtlich einzustufen sind. Vereinzelte Oasen fruchtbaren Bodens stellen eine wichtige Grundlage für die Versorgung des Lagers dar. Die Tierwelt besteht, nach den ersten Erkenntnissen, aus Kleinechsen, Wildkatzen und Insekten, die keine direkte Bedrohung darstellen.`n`n
<u>`b`IDer Feind:`b`n</u>
`0Feindkontakt ist ausschließlich über die Einöde nördlich des Lagers zu erwarten, welche den einzigen direkt passierbaren Weg tief in die Dunklen Lande darstellt. Zivile Expeditionsteilnehmer seien angewiesen, zu ihrer eigenen Sicherheit diesen Abschnitt zu meiden.`n
Bei den feindlichen Kreaturen handelt es sich um lose Kleingruppen, vermutlich verschiedenen Clans zugehörig. Es ist anzunehmen, dass diese Gruppen, bestehend aus Soldaten und einem Kommandanten, während ihrer Angriffe vereinzelt von Räuberbanden begleitet werden. Die Wesen sind im Kampf ungewöhnlich zäh und sind als große Bedrohung anzusehen.`n`n');
                addnav('Zurück','expedition.php?op=chief');
                break;
        }
        case 'recruit' : //Info: Rekrutierungsliste
        {
                $session['user']['DDL_location'] = 2;
                page_header('Expedition in die dunklen Lande - Expeditionsleiter');
                output('`0Folgende Helden nehmen an der Expedition in die dunklen Lande teil:`n`n');
                $sql = "SELECT acctid,name,level,login,dragonkills,sex,ddl_rank,expedition,
                        IF(".user_get_online().",'`@Online`0','`4Offline`0') AS loggedin
                        FROM accounts
                        WHERE expedition!=0
                        ORDER BY ddl_rank DESC, dragonkills DESC, level DESC
                        LIMIT 100";
                $result = db_query($sql);
                $str_output.="<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>
                <tr class='trhead'>
                <th>DKs</th>
                <th>Level</th>
                <th>Name</th>
                <th><img src=\"./images/female.gif\">/<img src=\"./images/male.gif\"></th>
                <th>Status</th>
                <th>Rang</th>
                </tr>";
                $max = db_num_rows($result);
                for ($i=0; $i<$max; $i++)
                {
                        $row = db_fetch_assoc($result);
                        $str_output.="<tr class='".($i%2?"trdark":"trlight")."'>
                        <td>&nbsp;`^".$row['dragonkills']."`0&nbsp;</td>
                        <td>&nbsp;`^".$row['level']."`0&nbsp;</td>
                        <td>&nbsp;".CRPChat::menulink( $row )."`0&nbsp;</td>
                        <td align=\"center\">".($row['sex']?"<img src=\"./images/female.gif\">":"<img src=\"./images/male.gif\">")."&nbsp;</td>
                        <td>&nbsp;".$row['loggedin']."</td>
                        <td>&nbsp;".get_ddl_rank($row['ddl_rank'])."</td>
                        </tr>";
                }
                output($str_output."</table>");

        		//addnav('Aktionen');
        		//addnav('Helden vorschlagen','expedition.php?op=propose');
                addnav('Zurück');
                addnav('Zum Expeditionsleiter','expedition.php?op=chief');
                break;
        }
        case 'propose' : //Helden vorschlagen
        {
                require_once(LIB_PATH.'board.lib.php');
                $session['user']['DDL_location'] = 2;
                page_header('Expedition in die dunklen Lande - Expeditionsleiter / Rekrutierungsliste');
                if($_GET['board_action'] == 'add') {
                        board_add('expi_new');
                }
                $int_del = ($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) ? 2 : 1);
                board_view('expi_new',$int_del,'Folgende Helden wurden bereits vorgeschlagen:','Es wurden noch keine Helden vorgeschlagen!',true,true);
                output('`n`n`&Möchtest du selbst einen Helden vorschlagen? Dann schreib seinen Namen auf einen Zettel und häng ihn an das Brett:');
                board_view_form('Vorschlagen!','');
                output('`n`n');
                viewcommentary('expedition_recruit','`nHier kannst du über die Vorschläge diskutieren.',25,"sagt");
                addnav('Zurück','expedition.php?op=chief');
                break;
        }
        case 'poll' : //Umfrage erstellen
        {
                require_once(LIB_PATH.'board.lib.php');
                output(get_title('Außenposten-interne Umfragen'));
                poll_add('expedition_'.$_GET['pollsection'],100,1);
                if(!empty($session['polladderror'])) {
                        if($session['polladderror'] == 'maxpolls')
                        {
                                output('`$An dieser Stelle findet bereits eine Umfrage statt! Entferne bitte zunächst diese, ehe du eine neue eröffnest.`n`n');
                        }
                }
                else
                {
                        redirect('expedition.php?op=chief');
                }

                if($_GET['pollsection'] == 'private')
                {
                        output('`8Du möchtest also im Hinterzimmer des Stabszeltes eine Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und an einer prominenten Stelle aufgehängt zu werden:`n`n');

                }
                else
                {
                        output('`8Du möchtest also eine öffentliche Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und für alle gut sichtbar platziert zu werden:`n`n');
                }
                addnav('Zurück zum Stabszelt','expedition.php?op=chief');

                poll_show_addform();
                break;
        }
        case 'board': //Notizen
        {
                require_once(LIB_PATH.'board.lib.php');

                board_view_form('Aufhängen','`&Deine Nachricht:');
                if($_GET['board_action'] == "add") {
                        board_add('expedition');
                        redirect('expedition.php?op=board');
                }
                output('`n`n');
                board_view('expedition',1,'Folgende Zettel hängen neben dem Lageplan:','Keine  Nachrichten vorhanden!',true,true,true);
                addnav('Zurück zum Stabszelt','expedition.php?op=chief');
                break;
        }
        case 'massmail': // Massenmail (im wohnviertel by mikay)
        {
                page_header('Expedition in die dunklen Lande - Expeditionsleiter / Massenmail');
                $str_filename=basename(__FILE__);
                $str_out .= get_title('Taubenschlag unter dem Dach des Hauptquartiers.`0');

                addnav('Abbrechen',$str_filename.'?op=chief');

                $sql='SELECT acctid, name, login, ddl_rank
                        FROM accounts
                        WHERE expedition >0
                                AND acctid!='.(int)$session['user']['acctid'].'
                        ORDER BY ddl_rank DESC';
                $result=db_query($sql);
                $users=array();
                $keys=0;

                while($row=db_fetch_assoc($result))
                {
                        $profs[0][0]='Zivilist';
                        if($row['ddl_rank']!=$lastprofession) $residents.='`n`b'.$profs[$row['ddl_rank']][0].'`b`n';

                        $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" id="inp23421" '.($row['ddl_rank']!=PROF_GUARD_NEW ? 'checked':'').'> '.$row['name'].'
                        '.JS::event('#inp23421','click','chk();').'
                        <br>';
                        $keys++;
                        $lastprofession=$row['ddl_rank'];

                        if ($_POST['title']!='' && $_POST['maintext']!='' && in_array($row['acctid'],$_POST['msg']))
                        {
                                $users[]=$row['acctid'];
                        }
                }

                $mailsends=count($users);

                if ($mailsends<=5)
                {
                        $gemcost=1;
                }
                elseif ($mailsends<=15)
                {
                        $gemcost=2;
                }
                elseif ($mailsends<=25)
                {
                        $gemcost=3;
                }
                elseif ($mailsends>25)
                {
                        $gemcost=4;
                }
                $gemcost=0;

                if ($session['user']['gems']>=$gemcost AND $mailsends>0)
                {
                        foreach($users as $id)
                        {
                                systemmail($id, $_POST['title'], $_POST['maintext'], $session['user']['acctid']);
                        }

                        $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler haben eine Taube erhalten und deine Kosten betragen '.$gemcost.' Edelsteine.<br><br>';
                        $session['user']['gems']-=$gemcost;
                }
                elseif ($session['user']['gems']<$gemcost AND $mailsends>0)
                {
                        $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler hätten eine Taube erhalten, wenn deine Kosten nicht '.$gemcost.' Edelsteine betragen würden. Leider kannst du dies nicht bezahlen.<br><br>';
                }

                if ($keys>0)
                {
                        $str_out .= form_header($str_filename.'?op=massmail')
                        .$sendresult.'
                        <table border="0" cellpadding="0" cellspacing="10">
                                <tr>
                                        <td><b>Betreff:</b></td>
                                        <td><input type="text" name="title" id="title" value="">
                                               '.JS::event('#title','keydown','chk()').'
                                                '.JS::event('#title','focus','chk()').'
                                        </td>
                                </tr>
                                <tr>
                                        <td valign="top"><b>Nachricht:</b></td>
                                        <td><textarea name="maintext" id="maintext" rows="15" cols="50" class="input"></textarea>
                                          '.JS::event('#maintext','keydown','chk()').'
                                                '.JS::event('#maintext','focus','chk()').'
                                        </td>
                                </tr>
                                <tr>
                                        <td valign="top"><b>Senden an:</b></td>
                                        <td>'.$residents.'
                                                `bKosten bis jetzt:`b <span id="cost">0</span> Edelstein(e)!
                                        </td>
                                </tr>
                                <tr>
                                        <td></td>
                                        <td>
                                                <span id="but" style="visibility:hidden;"><input type="submit" value="Tauben auf die Reise schicken!" class="button"><br></span>
                                                <span id="msg">Bitte verfasse nun deine Botschaft und wähle die Empfänger!</span></td>
                                </tr>
                        </table>
                        </form>'.JS::MassMail(true);
                }
                else
                {
                        $str_out .= '`c`bEs wurden noch keine Schlüssel verteilt - und ja, Bombentauben an missliebige Nachbarn sind gegen das Gesetz.`b`c';
                }
                output($str_out);
                break;
        } // END massmail

        case 'inn' : //RPG: Gemeinschaftszelt
        {
                $session['user']['DDL_location'] = 3;
                page_header('Expedition in die dunklen Lande - Gemeinschaftszelt');
                output('`c`b`IDas Gemeinschaftszelt`0`b`c`n`(B`)eh`7u`et`fs`0am legst du die Stoffe des Zeltes, die den Eingang verhüllen, zur Seite und trittst in das größte Zelt, das hier im Lager aufgeschlagen wurde. Der Raum ist vollgestellt mit einfachen Tischen und Bänken und der Boden ist mit Holzdielen ausgelegt. Ganz am Ende erspähst du einen kleinen Tresen, hinter dem gerade die Schankmaid Gläser wäscht. Zu deiner Überraschung hat sie verblüffende Ähnlichkeit mit Violet und so lässt du dir von einem der anwesenden Teilnehmer an der Expedition ihren Namen zuflüstern - Scarlet! Du beobachtest sie einen kurzen Moment und lässt dir dann von ihr etwas Wasser und eine warme Speise bringen. Anschließend lauscht du den Heldengeschichten und Späßen, die hier lauthals erzählt werden. An einem runden Tisch am Rande des Zeltes kannst du zudem ein paar Brettspiele er`fk`ee`7n`)ne`(n.`0`n`n');
                viewcommentary('expedition_inn','Sagen',25,"sagt");
                addnav('Wer ist hier?');
                addnav('Umsehen','expedition.php?op=whosthere&where=3&ret='.URLEncode($_SERVER['REQUEST_URI']));
                addnav('Zurück');
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
       
      
    
case 'doc' : //Lagerarzt
        {
                $session['user']['DDL_location'] = 4;
                page_header('Expedition in die dunklen Lande - Lagerarzt');
                output('`c`b`IDer Lagerarzt`0`b`c`n`(D`)u b`7e`et`fr`0ittst mit zitternden Knien das Zelt des Arztes. Dir wurde zwar der Weg zum Lagerarzt gezeigt, allerdings von diesem Besuch abgeraten. Du kannst dir nicht vorstellen, weshalb man den Arzt nicht aufsuchen sollte, wenn man doch Hilfe benötigt. Als du das Zelt betrittst, zweifelst du plötzlich an deiner Entscheidung. An den Zeltstangen hängen überall übel aussehende Instrumente, die man auf jeden Fall nicht für eine Heilung benötigt...und die sonst eigentlich verboten sind. Mitten im Zelt steht eine große Liege, an der - für deinen Geschmack - zu viel getrocknetes Blut klebt. Händereibend und mit einem erfreuten Lächeln winkt der Lagerarzt dich heran. Du hast das Gefühl, er sieht dich an wie ein Versuchskan`fi`en`7c`)he`(n...`0`n`n');
                $sql = "SELECT wounds FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                $wounds = $row['wounds'];
                switch ($wounds)
                {
                        //Verwundungsstatus
                case 0 :
                        output('`@Du erfreust dich bester Gesundheit!`0`n`n');
                        break;
                case 1 :
                        output('`2Bis auf ein paar leichte Blessuren geht es dir ganz gut.`0`n`n');
                        break;
                case 2 :
                        output('`^Du hast dir in der Schlacht eine leichte Verletzung zugezogen. Vielleicht sollte der Arzt mal einen Blick darauf werfen.`0`n`n');
                        break;
                case 3 :
                        output('`qDu wurdest im Kampf verletzt. Zwar schmerzt die Wunde sehr, jedoch kannst du weiter kämpfen.`0`n`n');
                        break;
                case 4 :
                        output('`4Es geht dir nicht sehr gut. Deine Verwundung bereitet dir große Schmerzen und hindert dich am erneuten Kampf.`0`n`n');
                        break;
                case 5 :
                        output('`$Du wurdest sehr schwer verletzt und warst dem Tode nah. Doch dank der Hilfe deiner Kameraden und des Lagerarztes hast du nun das Schlimmste überstanden. Dennoch wird es etwas dauern, bis du wieder kämpfen kannst.`0`n`n');
                        break;
                }
                viewcommentary('expedition_doc','Sagen',25,"sagt");
                addnav('Aktionen');
                addnav('Heilen lassen','expedition.php?op=heal');
                addnav('Kopf gegen die Wand hauen','expedition.php?op=hurt');
                addnav('Information');
                addnav('Über Verwundungen','expedition.php?op=woundinfo');
                addnav('Wer ist hier?');
                addnav('Umsehen','expedition.php?op=whosthere&where=4&ret='.URLEncode($_SERVER['REQUEST_URI']));
                addnav('Zurück');
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
        case 'heal' : //Heilen
        {
                $session['user']['DDL_location'] = 4;
                page_header('Expedition in die dunklen Lande - Lagerarzt');
                $sql = "SELECT wounds,doc_visited FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                if ($row['wounds']<1)
                {
                        output('`0Es geht dir blendend! Warum solltest du dich also der schmerzhaften Behandlung unterziehen wollen ?`0`n');
                }
                else if ($row['doc_visited']==1)
                {
                        output('`0Du wurdest heute bereits behandelt. Der Doktor kann erstmal nichts mehr für dich tun!`n');
                }
                else
                {
                        output('`0Der Doktor reibt mit sadistischem Grinsen seine Hände und beginnt die Behandlung.`nZwar vermisst du sehr stark die Sanftheit und Vorsicht von Golinda, jedoch bringt auch diese Therapie den gewünschten Erfolg.`n`IEs geht dir etwas besser!`0`n');
                        $sql = "UPDATE account_extra_info SET wounds=wounds-1, doc_visited=1 WHERE acctid=".$session['user']['acctid']."";
                        db_query($sql);
                        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                }
                addnav('Zurück','expedition.php?op=doc');
                break;
        }
        case 'hurt' : //Kopf gegen die Wand hauen
        {
                $session['user']['DDL_location'] = 4;
                page_header('Expedition in die dunklen Lande - Lagerarzt');
                $sql = "SELECT wounds FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                output('`0Ein dumpfer Knall ist zu hören, als du deinen Hohlkopf gegen die Wand schlägst!`n');
                if ($row['wounds']<5)
                {
                        $sql = "UPDATE account_extra_info SET wounds=wounds+1 WHERE acctid=".$session['user']['acctid']."";
                        db_query($sql);
                }
                addnav('Zurück','expedition.php?op=doc');
                break;
        }
        case 'woundinfo' : //Info: Verwundungen
        {
                $session['user']['DDL_location'] = 4;
                page_header('Expedition in die dunklen Lande - Lagerarzt');
                output('`c`b`IÜber Verwundungen`0`b`c`n
`0In den Dunklen Landen begegnest du gefährlichen Kreaturen. Diese fügen dir im Kampf Verletzungen zu, die`n`qzum einen deine Lebenskraft reduzieren und dir zum anderen Verwundungen zufügen.`0`n
Den Verlust der Lebenskraft kann jeder übliche Heiler wieder herstellen, die Verwundung selbst kannst du jedoch <u>nur hier beim Lagerarzt</u> behandeln lassen.`n
Es gibt `q5 Verwundungsstufen`0, von quicklebendig bis dem Tode nah. Eine `bleichte Verletzung`b im Kampf erhöht deine Verwundung um `beine Stufe`b, wohingegen eine `bVerletzung`b (durch den Soldaten oder Kommandanten verursacht) diese um `bzwei Stufen`b erhöht. Du läufst Gefahr eine Verwundung zu erleiden, sobald du den ersten Treffer kassiert hast, d.h. du kann auch bei einem Sieg verwundet werden, es sei denn du hattest einen perfekten Kampf. Eine `bNiederlage`b befördert dich automatisch an den Tropf, also auf `bVerwundungsstufe 5`b.`n
Die Behandlung beim Lagerarzt ist einmal täglich möglich. Sie senkt deine Verwundung um `beine Stufe`b und regeneriert alle verlorene Lebenskraft.`n
Ab `bVerwundungsstufe 4`b kannst du dich nicht mehr in die Einöde begeben!`n
Über Nacht oder durch Wiedererweckung heilen diese Verwundungen <u>nicht</u>!`n
Deine aktuelle Verwundungsstufe kannst du nur im Zelt des Lagerarztes erfahren!`n`n
`0`bDiese sind im einzelnen`b :`n
`0Stufe 0: `@Du erfreust dich bester Gesundheit!`0`n
`0Stufe 1: `2Bis auf ein paar leichte Blessuren geht es dir ganz gut.`0`n
`0Stufe 2: `^Du hast dir in der Schlacht eine leichte Verletzung zugezogen. Vielleicht sollte der Arzt mal einen Blick darauf werfen.`0`n
`0Stufe 3: `qDu wurdest im Kampf verletzt. Zwar schmerzt die Wunde sehr, jedoch kannst du weiter kämpfen.`0`n
`0Stufe 4: `4Es geht dir nicht sehr gut. Deine Verwundung bereitet dir große Schmerzen und hindert dich am erneuten Kampf.`n
`0Stufe 5: `$Du wurdest sehr schwer verletzt und warst dem Tode nah. Doch dank der Hilfe deiner Kameraden und des Lagerarztes hast du nun das Schlimmste überstanden. Dennoch wird es etwas dauern bis du wieder kämpfen kannst.`0`n`n');
                addnav('Zurück','expedition.php?op=doc');
                break;
        }
        case 'pools' : //RPG: Heiße Quellen
        {
                $session['user']['DDL_location'] = 6;
                page_header('Expedition in die dunklen Lande - Heiße Quellen');
                output('`c`b`fD`Fi`#e `3h`§e`Bißen Qu`§e`3l`#l`Fe`fn`0`b`c`n`BVo`§m Z`3el`#tl`fager aus hast du den dampfenden Wasserfall gesehen. Nachdem du einen Weg auf die steinigen Felsen gefunden hast, machst du dich auf die Suche nach dem Ursprung des scheinbar heißen Wassers. Plötzlich fällt dir auf, dass die Steine unter deinen Füßen immer feuchter werden und schließlich siehst du direkt vor dir, mitten im Fels, scheinbar eine Ebene, übersäht mit kleinen Seen, in denen lebhaft das Wasser sprudelt. Erst bei näherem Betrachten glaubst du auf die Spur dieser ungewöhnlichen Wärme zu kommen, die auch den Stein unter deinen Füßen erwärmt: Nicht nur die Quellen dampfen, sondern auch aus einem Spalt im Fels steigt Dampf aus. Da er allerdings so eng ist, dass du nichts erkennen kannst, wendest du dich von den kleinen Quellen ab und folgst den kleinen Bächen, die alle zu einer abgesenkten Stelle fließen; plötzlich stehst du an der Kante des kleinen Gebirges, unmittelbar am Ursprung des Wasserfalls und blickst hinab auf das Z`#el`3tl`§ag`Ber.`n`n');
                viewcommentary('expedition_pools','Blubbern',25,"blubbert");


                addnav('Wer ist hier?');
                addnav('Umsehen','expedition.php?op=whosthere&where=6&ret='.URLEncode($_SERVER['REQUEST_URI']));
                addnav('Zurück');
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
     
   

        case 'cave' : //RPG: Tropfsteinhöhle
        {
                $session['user']['DDL_location'] = 8;
                page_header('Expedition in die dunklen Lande - Tropfsteinhöhle');
                $color=getsetting("DDL-cristals",1);
                switch ($color)
                {
                case 1 :
                        $col='`BD`Fu`* h`f';
                        $col2='`*ü`Fl`Bt.';
                        break;
                case 2 :
                        $col='`BD`wu`F h`*';
                        $col2='`Fü`wl`Bt.';
                        break;
                case 3 :
                        $col='`BD`9u`w h`F';
                        $col2='`wü`9l`Bt.';
                        break;
                case 4 :
                        $col='`BD`!u`9 h`w';
                        $col2='`9ü`!l`Bt.';
                        break;
                case 5 :
                        $col='`BD`1u`! h`9';
                        $col2='`!ü`1l`Bt.';
                        break;
                }
                output('`c`b`BD`Fi`*e `fT`.r`|o`(pfstei`|n`.h`fö`*h`Fl`Be`0`b`c`n'.$col.'ast ein wenig am See die frische Luft genossen, als dir hinter dem Wasserfall ein kleiner Spalt im Felsen auffällt, gut verborgen hinter dem fallendem Strom. Zu deinem Glück kannst du auch noch einen sehr schmalen Pfad erkennen, der genau auf den Spalt zuführt. Schnell tauchst du durch den Wasserfall und findest dich in einem schmalen, kaum mannshohem Gang wieder. Neben dir fließt ein kleiner Bach immer tiefer in das Gestein und du beschließt diesem zu folgen. Immer steiler und tiefer geht es in den Fels, ehe sich der Gang plötzlich in einer riesigen Höhle öffnet. Ein unwirklich scheinendes Licht tänzelt durch die ganze Höhle, dennoch kannst du das Ausmaß nur erahnen. Immer wieder siehst du Tropfsteine an der Decke, den Wänden und auch aus dem Boden scheinen sie zu wachsen. Überall sind kleine Rinnsale, die ebenso wie der Bach zu einem unterirdischen See führen. Das Licht lässt die Wassertropfen immer wieder funkeln und ebenso die unzähligen Kristalle, die in allen verschiedenen Farben schillern!`nDu bemerkst, dass die Kristalle ihre Farben wechseln, je nach dem, wieviel Wasser sie auf dem Boden umsp'.$col2.'`n`n');
                viewcommentary('expedition_cave','Flüstern',25,"flüstert");
                addnav('Wasser stauen');
                addnav('Gar nicht','expedition.php?op=cristals&act=1');
                addnav('Wenig','expedition.php?op=cristals&act=2');
                addnav('Mittel','expedition.php?op=cristals&act=3');
                addnav('Stark','expedition.php?op=cristals&act=4');
                addnav('Komplett','expedition.php?op=cristals&act=5');
                addnav('Wer ist hier?');
                addnav('Umsehen','expedition.php?op=whosthere&where=8&ret='.URLEncode($_SERVER['REQUEST_URI']));
                addnav('Zurück');
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
        case 'cristals' : //Tropsteinhöhle: Farbe ändern
        {
                $act=$_GET['act'];
                savesetting("DDL-cristals",$act);
                redirect("expedition.php?op=cave");
                break;
        }
        case 'milplace' : //RPG: Antreteplatz
        {
                $session['user']['DDL_location'] = 11;
                page_header('Expedition in die dunklen Lande - Antreteplatz');
                output('`c`b`mD`:e`Sr `NA`(n`)trete`(p`Nl`Sa`:t`mz`0`b`c`n`mE`:i`Sn ungenutzter Platz am Rande des Lagers, der mit Kies bedeckt ist und so ein perfekter Ort für die Apelle der Bürgerwehr ist. Regelmäßig müssen hier alle Mitglieder der Lagerwache antreten und salutieren, wenn der Oberst besondere Auszeichnungen oder Orden zu vergeben hat. Aber der Platz wird auch mit Vorliebe von den ranghöheren Offizieren genutzt, um jungen, unerfahrenen Rekruten Disziplin einzuschärfen oder sie mit schweißtreibendem Training in Form zu bringen. Die Flagge der Lagerwache weht lebhaft im Wind, gut sichtbar für die Rekruten, die ebenso durch den herrischen Klang von Hörnern angesport werden sol`Sl`:e`mn.`0`n`n');
                viewcommentary('expedition_mil','Sagen',25,"sagt");
                if ($session['user']['ddl_rank']==PROF_DDL_COLONEL)
                {
                        $pointsleft=getsetting("DDL-medal","0");
                        addnav('Ordenpunkte: '.$pointsleft);
                        addnav('Orden verleihen','expedition.php?op=give_medal');
                }
                addnav('Wer ist hier?');
                addnav('Umsehen','expedition.php?op=whosthere&where=11&ret='.URLEncode($_SERVER['REQUEST_URI']));
                addnav('Zurück');
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
        case 'give_medal' : //Antreteplatz: Orden verleihen: Name suchen
        {
                page_header('Expedition in die dunklen Lande - Antreteplatz');
                $sql = "SELECT name,accounts.acctid,level,login,loggedin,dragonkills,sex,ddl_rank
                        FROM accounts JOIN account_extra_info USING (acctid)
                        WHERE DDL_location=11 AND ".user_get_online()." AND ddl_rank>40 AND ddl_rank<49
                        ORDER BY ddl_rank DESC, level";
                $result = db_query($sql);
                $str_output.="Zur Zeit befinden sich auf dem Antreteplatz:
                `n`n<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>
                <tr class='trhead'>
                <th>DKs</th>
                <th>Level</th>
                <th>Name</th>
                <th><img src=\"./images/female.gif\">/<img src=\"./images/male.gif\"></th>
                <th>Rang</th>
                </tr>";
                $max = db_num_rows($result);
                for ($i=0; $i<$max; $i++)
                {
                        $row = db_fetch_assoc($result);
                        $rank=get_ddl_rank($row['ddl_rank']);
                        $str_output.="<tr class='".($i%2?"trdark":"trlight")."'>
                        <td>`^".$row['dragonkills']."`0</td>
                        <td>`^".$row['level']."`0</td>
                        <td>".create_lnk($row['name'].'`0','expedition.php?op=give_medal2&char='.$row['acctid'])."</td>
                        <td align=\"center\">".($row['sex']?"<img src=\"./images/female.gif\">":"<img src=\"./images/male.gif\">")."</td>
                        <td>`^".$rank."`0</td>
                        </tr>";
                }
                output($str_output."</table>");
                addnav("Neu laden","expedition.php?op=give_medal");
                addnav("Zurück","expedition.php?op=milplace");
                break;
        }
        case 'give_medal2' : //Orden verleihen: Orden auswählen
        {
                // Kosten für Orden :
                // Bestpreis : 3
                // Verwundetenmedaille : 6
                // Bronzenes Ehrenkreuz : 9
                // Silbernes Ehrenkreuz : 12
                // Goldenes Ehrenkreuz : 15
                // Tapferkeitsmedaille : 18
                // Ehrenmedaille : 21
                // Verdienstorden der Bürgerwehr : 23
                //
                page_header('Expedition in die dunklen Lande - Antreteplatz');
                $char=$_GET['char'];
                $sql = "SELECT name,acctid
                        FROM accounts
                        WHERE acctid=".$char;
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                $pointsleft=getsetting("DDL-medal","0");
                output('`IWelchen Orden willst du `0'.$row['name'].'`I verleihen?
                `n`I(Du hast `0'.$pointsleft." `IPunkte übrig.)
                `n`n`0<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>
                <tr class='trhead'>
                <th>Name</th>
                <th>Beschreibung</th>
                <th>Kosten</th>
                </tr><tr class=trlight>
                <td>".create_lnk('`IBestpreis`0','expedition.php?op=give_medal3&char='.$row['acctid'].'&medal=1')."</td>
                <td>Eine Auszeichnung für leistungsfähige Rekruten</td>
                <td>3</td>
                </tr>
                <tr class=trdark>
                <td>".create_lnk('`IVerwundetenmedaille`0','expedition.php?op=give_medal3&char='.$row['acctid'].'&medal=2')."</td>
                <td>Eine Anerkennung für Kämpfer, die in der Schlacht schwer verwundet wurden</td>
                <td>6</td>
                </tr>
                <tr class=trlight>
                <td>".create_lnk('`IBronzenes Ehrenkreuz`0','expedition.php?op=give_medal3&char='.$row['acctid'].'&medal=3')."</td>
                <td>Ein Orden für treue Dienste in der Bürgerwehr</td>
                <td>9</td>
                </tr>
                <tr class=trdark>
                <td>".create_lnk('`ISilbernes Ehrenkreuz`0','expedition.php?op=give_medal3&char='.$row['acctid'].'&medal=4')."</td>
                <td>Ein Orden für besonders treue Dienste in der Bürgerwehr</td>
                <td>12</td>
                </tr>
                <tr class=trlight>
                <td>".create_lnk('`IGoldenes Ehrenkreuz`0','expedition.php?op=give_medal3&char='.$row['acctid'].'&medal=5')."</td>
                <td>Ein Orden für aufopfernde Dienste in der Bürgerwehr</td>
                <td>15</td>
                </tr>
                <tr class=trdark>
                <td>".create_lnk('`ITapferkeitsmedaille`0','expedition.php?op=give_medal3&char='.$row['acctid'].'&medal=6')."</td>
                <td>Die Medaille für höchste Tapferkeit im Kampf</td>
                <td>18</td>
                </tr>
                <tr class=trlight>
                <td>".create_lnk('`IEhrenmedaille`0','expedition.php?op=give_medal3&char='.$row['acctid'].'&medal=7')."</td>
                <td>Eine Auszeichnung für Krieger, die höchste Ehren erlangt haben.</td>
                <td>21</td>
                </tr>
                <tr class=trlight>
                <td>".create_lnk('`IVerdienstorden der Bürgerwehr`0','expedition.php?op=give_medal3&char='.$row['acctid'].'&medal=8')."</td>
                <td>Die höchste Auszeichnung der Bürgerwehr</td>
                <td>24</td>
                </tr>
                </table>");
                addnav("Zurück","expedition.php?op=milplace");
                break;
        }
        case 'give_medal3' : //Orden verleihen abschließen
        {
                page_header('Expedition in die dunklen Lande - Antreteplatz');
                $char=$_GET['char'];
                $sql = "SELECT name,acctid
                        FROM accounts
                        WHERE acctid=".$char;
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                $medal=$_GET['medal'];
                $pointsleft=getsetting("DDL-medal","0");
                if ($pointsleft>=($medal*3))
                {
                        switch ($medal)
                        {
                        case 1 :
                                $mname='`IBestpreis`0';
                                $msg='`0Eine Auszeichnung für leistungsfähige Rekruten. ';
                                break;
                        case 2 :
                                $mname='`IVerwundetenmedaille`0';
                                $msg='`0Eine Anerkennung für Kämpfer, die in der Schlacht schwer verwundet wurden. ';
                                break;
                        case 3 :
                                $mname='`IBronzenes Ehrenkreuz`0';
                                $msg='`0Ein Orden für treue Dienste in der Bürgerwehr. ';
                                break;
                        case 4 :
                                $mname='`ISilbernes Ehrenkreuz`0';
                                $msg='`0Ein Orden für besonders treue Dienste in der Bürgerwehr. ';
                                break;
                        case 5 :
                                $mname='`IGoldenes Ehrenkreuz`0';
                                $msg='`0Ein Orden für aufopfernde Dienste in der Bürgerwehr. ';
                                break;
                        case 6 :
                                $mname='`ITapferkeitsmedaille`0';
                                $msg='`0Die Medaille für höchste Tapferkeit im Kampf. ';
                                break;
                        case 7 :
                                $mname='`IEhrenmedaille`0';
                                $msg='Eine Auszeichnung für Krieger, die höchste Ehren erlangt haben. ';
                                break;
                        case 8 :
                                $mname='`IVerdienstorden der Bürgerwehr`0';
                                $msg='Die höchste Auszeichnung der Bürgerwehr. ';
                                break;
                        }
                        $msg.='`IVerliehen an '.$row['name'];
                        $value=$medal*500;
                        $item['tpl_name'] = $mname;
                        $item['tpl_description'] = $msg;
                        $item['tpl_gold'] = $value;
                        item_add($row['acctid'],'medal',$item);
                        $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_mil',".$session['user']['acctid'].",': `^verleiht `^".$row['name']."`^ die Auszeichnung `#".$mname.".`0')";
                        db_query($sql);
                        addnews_ddl($session['user']['name']." `Ihat heute `0".$row['name']." `Idie Auszeichnung `0".$mname."`I verliehen!");
                        output($mname.' `Iwurde soeben an `0'.$row['name'].' `Iverliehen.');
                        $cost=$medal*3;
                        $pointsleft-=$cost;
                        savesetting("DDL-medal",$pointsleft);
                }
                else
                {
                        output('`qZu wenig Punkte für diese Medaille!');
                }
                addnav("Zurück","expedition.php?op=milplace");
                break;
        }
        case 'mytent' : //RPG: eigenes Zelt
        {
                $session['user']['DDL_location'] = 10;
                $sql = "SELECT login FROM accounts JOIN account_extra_info ON accounts.acctid=account_extra_info.DDL_tent WHERE account_extra_info.acctid=".$session['user']['acctid'];
                $result = db_query($sql);
                page_header('Expedition in die dunklen Lande - Privatzelt');
                $account=$session['user']['acctid'];
                output('`c`b`IDein Zelt`0`b`c`n`(D`)u g`7e`el`fa`0ngst zu deinem Zelt, das ebenso klein und eng ist, wie das der anderen Teilnehmer. Hierhin kannst du dich zurückziehen, falls du etwas Ruhe benötigst oder dich etwas von der anstrengenden Expedition ausruhen möchtest. Dein Hab und Gut hast du gerade so in das kleine Zelte bekommen, sodass du kaum Platz zum Schlafen hast. Stehen ist ebenso nicht möglich, da du dir eine Beule an den viel zu tiefen Stangen holen würdest. Allerdings wird es für kurze Zeit sicherlich gehen, dass du dich in deinem Zelt so klein machst, dass noch eine weitere Person hinei`fn `ep`7a`)ss`(t.`0`n`n');
                if (db_num_rows($result)>0)
                {
                        $row = db_fetch_assoc($result);
                        output('`IDu hast `0'.$row['login'].'`I in dein Zelt eingeladen.`n`n');
                        $visitor=1;
                }
                $room='tent'.$account;
                viewcommentary($room,'Flüstern',25,"flüstert");
                addnav('Aktion');
                addnav('Aufräumen','expedition.php?op=sauber&where='.$room);
                addnav('Unterredung');
                addnav('Jemanden einladen','expedition.php?op=invite');
                if ($visitor==1)
                {
                        addnav('Rauswerfen','expedition.php?op=invitationend');
                }
                addnav('Zurück');
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
        case 'sauber' : //eigenes Zelt aufräumen
        {
                $room=$_GET['where'];
                $roomcopy=$room.'copy';
                $sql = "UPDATE commentary SET section='$roomcopy' WHERE section='$room'";
                db_query($sql);
                redirect('expedition.php?op=mytent');
                break;
        }
        case 'othertent' : //RPG: fremdes Privatzelt
        {
                $session['user']['DDL_location'] = 10;
                page_header('Expedition in die dunklen Lande - Privatzelt');
                $account=$_GET['who'];
                $sql = "SELECT login,sex FROM accounts WHERE acctid=".$account;
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                output('`IDu schlägst die Plane auf Seite und krabbelst zu `0'.$row['login'].' `Iin '.($row['sex']?"ihr ":"sein ").'Zelt. Ihr müsst euch ziemlich eng aneinander kuscheln, da das Zelt eigentlich nur für eine Person ausgelegt ist. Auch solltet ihr eure Stimmen mäßigen, da die Zeltplane dünn ist und es draußen nur so vor neugierigen Ohren wimmelt.`n`n');
                viewcommentary('tent'.$account,'Flüstern',25,"flüstert");
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
        case 'invite' : //Einladung ins Privatzelt
        {
                page_header('Expedition in die dunklen Lande - Privatzelt');
                output("`IDu kannst einen Expeditionsteilnehmer in dein Zelt einladen. Sollte bereits jemand anderes eine Einladung von dir erhalten haben, so wird diese automatisch zurück genommen.`n`n");
                if ($_GET['who']=="")
                {
                        output("`&Wen willst du einladen?`n`0");
                        if ($_GET['subop']!="search")
                        {
                                output("<form action='expedition.php?op=invite&subop=search' method='POST'><input name='name' id='name'><input type='submit' class='button' value='Suchen'></form>",true);
                                output(focus_form_element('name'));
                                addnav("","expedition.php?op=invite&subop=search");
                        }
                        else
                        {
                                addnav("Neue Suche","expedition.php?op=invite");
                                $search = str_create_search_string($_POST['name']);
                                $sql = "SELECT acctid,name,alive,login,
                                        IF(".user_get_online().",'`@Online`0','`4Offline`0') AS loggedin
                                        FROM accounts
                                        JOIN account_extra_info USING(acctid)
                                        WHERE (name LIKE '".$search."' and expedition>0)
                                        ORDER BY login='".db_real_escape_string($_POST['name'])."' DESC, login ASC";
                                $result = db_query($sql);
                                $max = db_num_rows($result);
                                $str_output.="<table border=0 cellpadding=0>
                                <tr class='trhead'>
                                <th>Name</th>
                                <th>Status</th>
                                </tr>";
                                for ($i=0; $i<$max; $i++)
                                {
                                        $row = db_fetch_assoc($result);
                                        $str_output.="<tr>
                                        <td><a href='expedition.php?op=invite&who=".$row['acctid']."'>".$row['name']."</a></td>
                                        <td>".$row['loggedin']." / ".($row['alive']?'`@lebt':'`$tot')."`0</td>
                                        </tr>";
                                        addnav("","expedition.php?op=invite&who=".$row['acctid']);
                                }
                                output($str_output."</table>",true);
                        }
                }
                else
                {
                        $sql = "SELECT acctid,name,login FROM accounts WHERE acctid=\"$_GET[who]\"";
                        $result = db_query($sql);
                        $row = db_fetch_assoc($result);
                        output("`IMöchtest du `0".$row['name']." `Izu einer privaten Unterredung in dein Zelt bitten?`n`n`n");
                        addnav('Ja','expedition.php?op=invite2&who='.$row['acctid']);
                        addnav('Nein');
                        addnav('Neue Suche','expedition.php?op=invite');
                }
                addnav('Zurück','expedition.php?op=mytent');
                break;
        }
        case 'invite2' : //Einladung abschließen
        {
                page_header('Expedition in die dunklen Lande - Privatzelt');
                $sql = "SELECT acctid,name,login,sex FROM accounts WHERE acctid=\"$_GET[who]\"";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                output('`IAlles klar! `0'.$row['name'].' `Ierhält eine Einladung in dein Zelt!`n`n');
                $sql = 'UPDATE account_extra_info SET DDL_tent='.$row['acctid'].' WHERE acctid='.$session['user']['acctid'];
                db_query($sql);
                systemmail($row['acctid'],"`%DDL : `IEinladung ins Zelt von `0".$session['user']['login']."`I!","`I{$session['user']['name']}
                `I wünscht dich in ".($session['user']['sex']?"ihrem ":"seinem ")." Zelt zu sprechen - unverzüglich und allein...");
                addnav('Zurück','expedition.php?op=mytent');
                break;
        }
        case 'invitationend' : //Einladung beenden (Rauswurf)
        {
                $sql = "UPDATE account_extra_info SET DDL_tent=0 WHERE acctid=".$session['user']['acctid']."";
                db_query($sql);
                redirect('expedition.php?op=mytent');
                break;
        }
        case 'guards' : //Lagerwache Hauptraum
        {
                $session['user']['DDL_location'] = 5;
                page_header('Expedition in die dunklen Lande - Lagerwache');
                output('`c`b`IDie Lagerwache`0`b`c`n`IHier kannst du Informationen und Neuigkeiten über Feindkontakt in den Dunklen Landen erfahren.`n`n');
                switch ($session['user']['ddl_rank'])
                {
                	case PROF_DDL_RECRUIT :
                        output('`(D`)u b`7e`et`fr`0ittst das Zelt der Wache. Kaum einen Schritt kannst du in den Raum hinein setzen, als man dir schon einen Eimer und einen Putzlappen in die Hand drückt. Missmutig bringst du das Zelt in Ordung und hast nun eine kleine Pause, bevor dich dein Ausbilder aufs neue quäl`fe`en `7w`)ir`(d.`0`n`n');
                        break;
                	case PROF_DDL_CORPORAL :
                        output('`(D`)u b`7e`et`fr`0ittst das Zelt der Lagerwache. Dein ausbildender Sergeant blickt dich streng an und deutet wortlos auf die Waffen und Rüstungsteile, die wohl dir gehören und dringed der Reinigung und Pflege bedürfen. Alibimäßig machst du dich an die Arbeit um dann kurze Zeit später wieder etwas andere`fs `ez`7u `)tu`(n.`0`n`n');
                        break;
                	case PROF_DDL_SERGEANT :
                        output('`(A`)ls `7d`eu `fd`0as Zelt der Lagerwache betrittst, siehst du wie einige der Soldaten fröhlich plaudernd Karten spielen. Du erkennst einige gute Freunde unter ihnen wieder, und einer rückt auf Seite um einen weiteren Stuhl heranzuziehen. Sie winken dir zu am Spiel teilzu`fn`ee`7h`)me`(n.`0`n`n');
                        break;
                	case PROF_DDL_STSERGEANT :
                        output('`(A`)ls `7d`eu `fd`0as Zelt der Wache betrittst, findest du die Soldaten in unterschiedlichen Beschäftigungen vor. Dein Sergeant erhebt sich und geht auf dich zu.`n"`@Alles klar soweit! Die Rekruten geben ein gutes Bild ab und die Moral ist auch nicht zu beklagen. Sind halt nur alle etwas nervös wegen der ganzen Sache mit den dunklen Kreaturen.`2" sagt er dir und nach einer kurzen Unterhaltung geht er zurück an seine `fA`er`7b`)ei`(t.`0`n`n');
                        break;
                	case PROF_DDL_ENSIGN :
                        output('`(A`)ls `7d`eu `fd`0as Zelt der Lagerwache betrittst, siehst du die Soldaten, wie sie mehr oder weniger sinnvollen Beschäftigungen nachgehen. Kaum einer würdigt dich eines Blickes, und jene, die es tun, nicken dir nur knapp zu. Du glaubst, dass sie hinter deinem Rücken über dic`fh `er`7e`)de`(n.`0`n`n');
                        break;
                	case PROF_DDL_LIEUTENANT :
                        output('`(A`)ls `7d`eu `fd`0ich in das Zelt der Lagerwache begibst, siehst du die Soldaten, wie sie ihre Waffen putzen, Kartenspielen und ausgelassen tratschen.`nEiner ruft dir zu : "`@Tach, '.($session['user']['sex']?"Frau":"Herr").' Leutnant!`2" und gibt dir einen militärischen Gruß. Danach geht er  wieder seiner Beschäftigu`fn`eg `7n`)ac`(h.`0`n`n');
                        break;
                	case PROF_DDL_CAPTAIN :
                        output('`(A`)ls `7d`eu `fd`0as Zelt der Lagerwache betrittst, siehst du die Soldaten, wie sie ihre Waffen putzen, Kartenspielen und ausgelassen tratschen.`nEiner ruft im halblauten Ton : "`@Offizier anwesend!`2" und die anderen erheben sich kurz und salutieren vor dir. Danach geht jeder wieder seiner Beschäftigu`fn`eg `7n`)ac`(h.`0`n`n');
                        break;
                	case PROF_DDL_MAJOR :
                        output('`(A`)ls `7d`eu `fd`0as Zelt der betrittst, findest du einige der Soldaten vor, wie sie ihre Waffen putzen, sowie andere beim Kartenspielen und tratschen.`nNach einem kurzen Moment ruft einer : "`@Achtung!`2" und die Soldaten erheben sich und nehmen Haltung an. Dir wird die Lage gemeldet, und danach geht jeder wieder seiner Beschäftigu`fn`eg `7n`)ac`(h.`0`n`n');
                        break;
                	case PROF_DDL_COLONEL :
                        output('`(A`)ls `7d`eu `fd`0as Zelt der Lagerwache betrittst, siehst du, wie einige deiner Soldaten ihre Waffen putzen, andere über Lageplänen brüten und wieder andere mit Kartenspielen beschäftigt sind.`nSofort brüllt einer laut : "`@Aaaaachtung!`2" und jeder lässt augenblicklich alles fallen, was er
						gerade in Händen hält und nimmt Haltung an. Dir wird die Lage gemeldet und alle blicken dich erwartungs`fv`eo`7l`)l a`(n.`0`n`n');
                        break;
					default :
                        output('`(I`)m Z`7e`el`ft `0der Lagerwache triffst du besonders viele ehrenwerte Mitglieder der Bürgerwehr, zu der du nur allzu gern gehören würdest. Alle möglichen "Zivilisten" berichten hier eifrig von ihren Erfolgen über die Kämpfer der Dunklen Lande um möglichst schnell einen hohen Rang zu bekommen. Doch die Anführer der Bürgerwehr scheinen sich daran keinesfalls zu stören, beziehungsweise dies zu beachten. Sie diskutieren nur die neusten Strategien und setzen auf einer großen Karte auf dem Tisch kleine Figuren hin und her. Was das bedeutet, findest du sicher nur heraus, wenn du genug Krieger besiegt hast und das bereit bist, das Lager zu vert`fe`ei`7d`)ig`(en.`0`n`n');
                        break;
                }
                require_once(LIB_PATH.'board.lib.php');
                $session['user']['DDL_location'] = 2;
                page_header('Expedition in die dunklen Lande - Expeditionsleiter / Rekrutierungsliste');
                if($_GET['board_action'] == 'add') {
                        board_add('expi_guard');
                }
                $int_del = ($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN) ? 2 : 1);
                board_view('expi_guard',$int_del,'`0Folgende Botschaften wurden von der Expeditionsleitung hier verkündet:','Es wurden noch keine Botschaften verkündet!',true,true);
                if($session['user']['ddl_rank'] == PROF_DDL_COLONEL || $int_del == 2) {
                        output('`n`n`0Möchtest du etwas Wichtiges kundtun? Dann verfasse eine Nachricht und häng sie hier auf:');
                        board_view_form('Vorschlagen!','');
                }
                output('`n`n');
                viewcommentary('expedition_guards','Melden',25,"meldet");
                addnav('Information');
                addnav('Befehle','expedition.php?op=explain_orders');
                addnav('Über den Kampf','expedition.php?op=about_battle');
                addnav('Mein Rang','expedition.php?op=myrank');
                addnav('Bürgerwehr');
                addnav('Neuigkeiten','expedition.php?op=news');
                addnav('Mitglieder','expedition.php?op=ranks');
                if (($session['user']['ddl_rank']>40 && $session['user']['ddl_rank']<50) || ($access_control->is_superuser()))
                {
                        addnav('Taktik');
                        addnav('Lagebericht','expedition.php?op=tactics');
                }
                addnav('Wer ist hier?');
                addnav('Umsehen','expedition.php?op=whosthere&where=5&ret='.URLEncode($_SERVER['REQUEST_URI']));
                addnav('Zurück');
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
        case 'explain_orders' : //Lagerwache: Übersicht Tagesbefehle
        {
                $session['user']['DDL_location'] = 5;
                page_header('Expedition in die dunklen Lande - Lagerwache');
                output('`c`b`IDie Tagesbefehle`0`b`c`n`n
`0Wird der Befehl `&"Warten auf Weiteres!" `0ausgegeben, so hat dies keine Konsequenzen.`n`n
`0Lautet der Tagesbefehl `^"Angriff!"`0, so besteht die Möglichkeit, durch erfolgreiche Kämpfe in der Einöde, die Situation des Lagers zum Positiven zu verändern.`n`n
`0Sollte der Befehl `4"Stellungen halten!"`0 gegeben sein, so sind Feinde auf dem Vormarsch. Nur durch erfolgreiche Kämpfe in der Einöde lässt sich nun verhindern, dass das Lager in Bedrängnis gebracht wird.`0');
                addnav('Zurück','expedition.php?op=guards');
                break;
        }
        case 'news' : //Lagerwache: News
        {
                $session['user']['DDL_location'] = 5;
                page_header('Expedition in die dunklen Lande - Lagerwache');
                $newsperpage=30;
                if ($access_control->su_check(access_control::SU_RIGHT_EXPEDITION))
                {
                        output("`0<form action=\"expedition.php?op=news\" method='POST'>",true);
                        output("[Admin] Meldung manuell eingeben? <input name='meldung' size='40'> ",true);
                        output("<input type='submit' class='button' value='Eintragen'>`n`n",true);
                        addnav("","expedition.php?op=news");
                        if ($_POST['meldung'])
                        {
                                $sql = "INSERT INTO ddlnews(newstext,newsdate,accountid) VALUES ('".db_real_escape_string($_POST['meldung'])."',NOW(),0)";
                                db_query($sql);
                                $_POST['meldung']="";
                        }
                        addnav("","expedition.php?op=news");
                }
                addnav("Zurück","expedition.php?op=guards");
                addnav("Blättern");
                $offset = (int)$_GET['offset'];
                $timestamp=strtotime((0-$offset)." days");
                $sql = "SELECT count(*) AS c FROM ddlnews WHERE newsdate='".date("Y-m-d",$timestamp)."'";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                $totaltoday=$row['c'];
                $pageoffset = (int)$_GET['page'];
                if ($pageoffset>0)
                {
                        $pageoffset--;
                }
                $pageoffset*=$newsperpage;
                $sql = "SELECT * FROM ddlnews WHERE newsdate='".date("Y-m-d",$timestamp)."' ORDER BY newsid DESC LIMIT $pageoffset,$newsperpage";
                $result = db_query($sql);
                $date=strftime("%A, %e. %B",$timestamp);
                output("`c`b`0Neuigkeiten bei der Expedition am $date".($totaltoday>$newsperpage?" (Meldungen ".($pageoffset+1)." - ".min($pageoffset+$newsperpage,$totaltoday)." von $totaltoday)":"")."`c`b`0`n");
                for ($i=0; $i<db_num_rows($result); $i++)
                {
                        $row = db_fetch_assoc($result);
                        output("`c`I-=-`y=-=`I-=-`y=-=`I-=-`y=-=`I-=-`y`c");
                        if ($access_control->su_check(access_control::SU_RIGHT_EXPEDITION))
                        {
                                output("[ <a href='superuser.php?op=newsdelete2&newsid=$row[newsid]&return=".URLEncode($_SERVER['REQUEST_URI'])."'>Del</a> ]&nbsp;",true);
                                addnav("","superuser.php?op=newsdelete2&newsid=$row[newsid]&return=".URLEncode($_SERVER['REQUEST_URI']));
                        }
                        output("$row[newstext]`n");
                }
                if (db_num_rows($result)==0)
                {
                        output("`c`I-=-`y=-=`I-=-`y=-=`I-=-`y=-=`I-=-`y`c");
                        output("`b`c`I Bislang nichts neues. Ein ruhiger Tag. `c`b`0");
                }
                output("`c`I-=-`y=-=`I-=-`y=-=`I-=-`y=-=`I-=-`y`c");
                if ($totaltoday>$newsperpage)
                {
                        addnav("Heutige Meldungen");
                        for ($i=0; $i<$totaltoday; $i+=$newsperpage)
                        {
                                addnav("Seite ".($i/$newsperpage+1),"expedition.php?op=news&offset=$offset&page=".($i/$newsperpage+1));
                        }
                }
                addnav("Vorherige Meldungen","expedition.php?op=news&offset=".($offset+1));
                if ($offset>0)
                {
                        addnav("Nächste Meldungen","expedition.php?op=news&offset=".($offset-1));
                }
                break;
        }
        case 'about_battle' : //Lagerwache: Info: Über den Kampf
        {
                $session['user']['DDL_location'] = 5;
                page_header('Expedition in die dunklen Lande - Lagerwache');
                output('`c`b`IDie Tagesbefehle`0`b`c`nIn der `qEinöde`0 hast du die Möglichkeit, dein Lager gegen annähernden Feind zu schützen oder neues Territorium zu erobern.`n
Über den `qFeind`0 ist nicht viel bekannt. Es handelt sich um eine in Kasten gegliederte Kriegerrasse, die stets plötzlich und in großer Zahl angreift.`nDabei tragen diese Wesen `qkeinerlei Rüstung`0, sind sie doch durch eine natürliche, dick ledrige Haut geschützt.`n
Als `qWaffe`0 verwenden sie ihre bloßen Fäuste, mit gefährlichen Stacheln versehene Kampfhandschuhe, Speere oder Schwerter.`n`n
Sie haben die Eigenart `qWunden`0 zu schlagen, die nur sehr schwer zu behandeln sind.`n
Gegen eine derartige Verletzung hilft nur ein Besuch beim `qLagerarzt`0, der `qeinmal pro Tag`0 eine leichte Wunde heilen kann.`nMit einer `qkleinen Wunde`0 wird es noch möglich sein, in der Einöde zu kämpfen, doch mit `qvier`0 dieser Verletzungen, ebenso wie mit einer `qschweren Verwundung`0, welche fünf kleinen Wunden entspricht, ist dies ausgeschlossen.`n`n
Eine `qNiederlage im Kampf`0 bedeutet nicht gleich das Ende, da unsere Feldsanitäter den Schwerverletzten sofort aus dem Gefahrengebiet schaffen und versorgen.`n
Auch bei einem `qSieg`0 gegen deine Widersacher kannst du leichte Verletzungen davon tragen.`n`n
Doch ganz gleich was dir auf der Expedition passiert, es wird dein Leben bei der Rückkehr nach '.getsetting('townname','Atrahor').' kaum beeinträchtigen.`n
Auch so schwer verletzt, dass du nicht mehr in der Einöde kämpfen kannst, wirst du immer noch Heldentat vollbringen können. Dazu heilt der Lagerarzt auch die Lebenskraft komplett.`n');
                addnav("Zurück","expedition.php?op=guards");
                break;
        }
        case 'ranks' : //Lagerwache: Info: Mitglieder mit Rängen
        {
                $session['user']['DDL_location'] = 5;
                page_header('Expedition in die dunklen Lande - Lagerwache');
                output('`c`IFolgende Helden haben durch tapferen Einsatz im Kampf einen Rang in der Bürgerwehr erhalten :`0`c`n`n');
                $sql = "SELECT acctid,name,level,dragonkills,sex,ddl_rank,
                        IF(".user_get_online().",'`@Online`0','`4Offline`0') AS loggedin
                        FROM accounts
                        WHERE expedition!=0 AND ddl_rank>40 AND ddl_rank <50
                        ORDER BY ddl_rank DESC,dragonkills DESC, level DESC
                        LIMIT 50";
                $result = db_query($sql);
                $str_output.="<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>
                <tr class='trhead'>
                <th>DKs</th>
                <th>Level</th>
                <th>Name</th>
                <th><img src=\"./images/female.gif\">/<img src=\"./images/male.gif\"></th>
                <th>Status</th>
                <th>Rang</th>
                </tr>";
                $max = db_num_rows($result);
                for ($i=0; $i<$max; $i++)
                {
                        $row = db_fetch_assoc($result);
                        $rank=get_ddl_rank($row['ddl_rank']);
                        $str_output.="<tr class='".($i%2?"trdark":"trlight")."'>
                        <td>&nbsp;`^".$row['dragonkills']."`0&nbsp;</td>
                        <td>&nbsp;`^".$row['level']."0&nbsp;</td>
                        <td>&nbsp;".CRPChat::menulink( $row)."`0&nbsp;</td>
                        <td align=\"center\">".($row['sex']?"<img src=\"./images/female.gif\">":"<img src=\"./images/male.gif\">")."</td>
                        <td>&nbsp;".$row['loggedin']."&nbsp;</td>
                        <td>&nbsp;`^".$rank."`0&nbsp;</td>
                        </tr>";
                }
                output($str_output."</table>",true);

                addnav("Zurück","expedition.php?op=guards");
                break;
        }
        case 'myrank' : //Lagerwache: Info: eigener Rang
        {
                $session['user']['DDL_location'] = 5;
                page_header('Expedition in die dunklen Lande - Lagerwache');
                output('`0Du ziehst einen Feldwebel auf Seite und fragst ihn, was er denn so von dir und deinem Rang hält.`n`n
`Q"Soso"`0, brummt er,');

                switch ($session['user']['ddl_rank'])
                {
                	case PROF_DDL_RECRUIT :
                        output('`Q"Du bistn Rekrut... Tut mir echt leid. Musst dich von jedem rumscheuchen lassen, hast überhaupt nichts zu sagen und musst für alle die Drecksarbeit erledigen. Und dann ist da noch die Ausbildung... Oh je, wenn ich an meine Zeit zurückdenke, dann wird mir Angst und Bange.`nUnd jetzt geh zurück an die Arbeit und polier die Rüstungen! Ich kontrolliere das gleich..."`n`n');
                        break;
                	case PROF_DDL_CORPORAL :
                        output('`Q"Dem Corporal ist wohl langweilig. Dein Job hier ist es den Sergeants beim Terror... äh... beim Ausbilden der Rekruten zu helfen, zu sagen hast du den Frischlingen allerdings nichts. Aber wenn dich das nicht auslastet, dann gibt es auch noch ein paar Schwerter, die unbedingt geschärft werden müssen. Natürlich hast du auch ein wenig Zeit um dich beim Kartenspiel zu entspannen."`n`n');
                        break;
                	case PROF_DDL_SERGEANT :
                        output('`Q"Ein Sergeant in der Pause. Du bist mit der Ausbildung der Rekruten betraut, sowie mit der Weiterbildung der Corporals. Beide kannst du rumscheuchen, wie du es willst. Den Zivilisten hast du jedoch nichts zu sagen. Und wenn du mal nicht mit der Ausbildung beschäftigt bist, dann nimmt man dich auch gern für unliebsame Wachschichten her. Oh, ich glaube da hinten stehen ein paar Rekruten rum und haben nichts zu tun... Los, los, oder willst du das tollerieren ?"`n`n');
                        break;
                	case PROF_DDL_STSERGEANT :
                        output('`Q"Du bistn Feldwebel, so wie ich. Tja... unser Job ist es Fragen aller Art zu beantworten und zu schauen ob die Sergeants unsre neuen Rekruten nicht rumgammeln lassen. Wir können Sergeants, Corporals und Rekruten Befehle erteilen, von Zivilisten haben wir die Finger zu lassen. Schade eigentlich, aber da kann man nix machen. Soweit alles klar?"`n`n');
                        break;
                	case PROF_DDL_ENSIGN :
                        output('`Q"Ein Fähnrich! Haha, armes Schwein! Möchte nicht in deiner Haut stecken, denn du hast die Pflichten eines Offiziers und die Rechte eines Rekruten. Zwar kannst du Rekruten, Corporals und Sergeants Befehle erteilen, aber dazu musst du erstmal kommen! Der Fähnricht muss so ziemlich alles tun, wovor sich die Offiziere gern drücken, weil es einfach lästig ist. Noch Fragen?"`n`n');
                        break;
                	case PROF_DDL_LIEUTENANT :
                        output('`Q"Ihr seid Leutnant, ein frisch gebackener Offizier. Seid froh, denn Ihr habt die schlimmste Zeit hinter Euch gebracht, ab sofort kann es nur besser werden. Als Leutnant seid Ihr Stellvertreter für alles und jeden, und wenn über euch niemand mehr ist, so könnt ihr sogar mit der Führung des ganzen Lagers betraut werden. Seid Euch also Eurer Position bewusst und macht ihr alle Ehre!`nBefehlen könnt ihr über Rekruten, Corporals, Sergeants, Feldwebel und Fänriche."`n`n');
                        break;
                	case PROF_DDL_CAPTAIN :
                        output('`Q"Hauptmann! Freut mich, dass Ihr meinen Rat sucht! Ihr seid vollwertiger Offizier und angesehenes Mitglied der Bürgerwehr. Ihr habt voll Befehlsgewalt über Rekruten, Corporals, Sergeants, Feldwebel, Fänriche und Leutnants. Auch liegt es an Euch neu eingtroffene Zivilisten im Lager herumzuführen und ihnen alles zu zeigen. Zu befehlen habt Ihr ihnen leider dennoch nichts."`n`n');
                        break;
                	case PROF_DDL_MAJOR :
                        output('`Q"Als Major habt Ihr volle Befehlsgewalt über Rekruten, Corporals, Sergeants, Feldwebel, Fähnriche, sowie über die Leutnants und Hauptleute und sogar die Zivilisten! Ihr könnt Beförderungen und Degradierungen durchführen, jedoch nicht, wenn es Offiziere betrifft. Über Euch steht nur noch der Rang Oberst, dem gegenüber Ihr zu Gehorsam verpflichtet seit."`n`n');
                        break;
                	case PROF_DDL_COLONEL :
                        output('`Q"Ihr seid Oberst und habt Euer Laufbahnziel hier erreicht. Als quasi Chef der Lagerwache habt Ihr Befehlgewalt über alle anderen Ränge und die Zivilisten, und könnt bis hin zum Rang des Majors Beförderungen und Degradierungen durchführen. Doch seid vorsichtig mit den Beförderungen. Denn wen wollt Ihr noch herumscheuchen, wenn es hier nur Häuptlinge gibt?"`n`n');
                        break;
					default :
                        output('`Q"Du bistn Zivilist. Tolle Sache. Zwar kann dir außer jemandem im Rang Major oder Oberst keiner hier groß was befehlen, jedoch bist du auch ein ziemlicher Außenseiter, was die Wache hier betrifft. Pass bloss auf, dass man dich nicht zum Rekruten macht, dann dann haste ausgelacht!`n`n');
                        break;
                }
                addnav("Zurück","expedition.php?op=guards");
                break;
        }
        case 'tactics' : //Lagerwache: Info: Lagebericht
        {
                $session['user']['DDL_location'] = 5;
                page_header('Expedition in die dunklen Lande - Lagerwache');
                output('`c`b`ILagebericht`0`b`c`nDu begibst dich zu den Lageplänen und Karten, um dir einen groben Überblick über die Situation zu verschaffen.`n`nZur Zeit sieht es folgendermaßen aus :`n
Die aktuelle Tagesorder ist `I'.getsetting("DDL_act_order","0").'`0 Tage alt und wird vorraussichtlich bis zum `I'.getsetting("DDL_new_order",3).'.`0 Tag beibehalten.`n
Der taktische Fortschritt unserer Kämpfer liegt derzeit bei `I'.getsetting("DDL-balance","0").'`0.`n
Vorhaben wie "Angriff" oder "Stellungen halten" gelingen bei einem taktischen Fortschritt von mindestens
`I'.getsetting("DDL_balance_win",25).'`0 und scheitern bei `I'.getsetting("DDL_balance_lose",-10).'`0.`n
Bei "Warten auf Weiteres" erhöht ein Fortschritt von mindestens `I'.getsetting("DDL_balance_push",40).'`0 die Chance auf einen "Angriff" bei Ausgabe der nächsten Order.`n
Ein Fortschritt von `I'.(getsetting("DDL_balance_lose",-10)*2).'`0 oder weniger verschiebt die Tendenz zu "Stellungen halten".`n
Jeden Tag verschlechtert sich die Lage um `I'.getsetting("DDL_balance_malus",5).'`0.`n`n');
                addnav('Zurück','expedition.php?op=guards');
                break;
        }
        case 'fight' : //Kampf
        {
                page_header('Expedition in die dunklen Lande - Kampf');
                $battle = true;
                break;
        }
        
        
        case 'risestate' : //MOD-Aktion: Zustand erhöhen
        {
                $state = getsetting("DDL-state",6);
                $newstate=$state+=1;
                if ($newstate>11)
                {
                        $newstate=11;
                }
                savesetting('DDL-state',$newstate);
                redirect('expedition.php');
                break;
        }
        case 'lowerstate' : //MOD-Aktion: Zustand senken
        {
                $state = getsetting("DDL-state",6);
                $newstate=$state-=1;
                if ($newstate<1)
                {
                        $newstate=1;
                }
                savesetting("DDL-state",$newstate);
                redirect('expedition.php');
                break;
        }
        case 'order' : //MOD-Aktion: Befehl zum ...
        {
                $neworder=$_GET['nbr'];
                savesetting("DDL-order",$neworder);
                redirect('expedition.php');
                break;
        }
        case 'run' : //Kampf: Fliehen
        {
                page_header('Expedition in die dunklen Lande - Kampf');
                if (e_rand()%3 == 0)
                {
                        include("battle.php");
                        addnews_ddl($session['user']['name']." `that heute seine Stellung verlassen und ist feige vor `^".$badguy['creaturename']." `tdavon gelaufen!");
                        $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes',".$session['user']['acctid'].",': `4flüchtet aus der Schlacht!`0')";
                        db_query($sql);
                        $badguy=array();
                        $session['user']['badguy']="";
                        $balance=getsetting("DDL-balance","0");
                        $balance_lose=getsetting("DDL_balance_lose",-6);
                        $balance-=3;
                        savesetting("DDL-balance",$balance);
                        $order=getsetting("DDL-order",2);
                        if ($balance<=$balance_lose && $order==1)
                        {
                                output('`4`n`nDie Verteidigung ist misslungen! Der Feind ist durchgebrochen!`n');
                                addnews_ddl("`4Heute wurden wir vom Feind zurück gedrängt!`&`n`&Neuer Tagesbefehl : Warten auf Weiteres!`&");
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes','1','/msg `2Unsere Verteidigung wurde überrant! Der Feind ist durchgebrochen.`0')";
                                db_query($sql);
                                // Rundmail ?
                                savesetting("DDL-balance","0");
                                savesetting("DDL-order",2);
                                savesetting("DDL_act_order","0");
                                savesetting("DDL_opps","0");
                                $state=getsetting("DDL-state",6);
                                $state--;
                                if($state<=1) // Niederlage ?
                                {
                                        output('`4`n`nUnser Lager wurde zerstört!`n');
                                        addnews_ddl("`4Flieht um Euer Leben! Unser Lager wurde zerstört!`&");
                                        $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes','1','/msg `4Unser Lager wurde vollständig zerstört.`0')";
                                        db_query($sql);
                                }
                                savesetting("DDL-state",$state);
                                savesetting("DDL_opps","0");
                        }
                        else if ($balance<=$balance_lose && $order==3)
                        {
                                output('`&`n`nUnser Angriff ist gescheitert! Der Feind hat die Stellungen gehalten!`n');
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes','1','/msg `4Unser Angriff wurde abgewehrt!`0')";
                                db_query($sql);
                                addnews_ddl("`&Heute wurde unser Angriff abgewehrt!`&`n`&Neuer Tagesbefehl : Warten auf Weiteres!`&");
                                // Rundmail ?
                                savesetting("DDL-balance","0");
                                savesetting("DDL-order",2);
                                savesetting("DDL_act_order","0");
                                savesetting("DDL_opps","0");
                                $state=getsetting("DDL-state",6);
                        }
                        redirect('expedition.php');
                }
                else
                {
                        output('`c`b`$Dir ist es nicht gelungen, deinem Gegner zu entkommen!`0`b`c');
                        $battle = true;
                }
                break;
        }
        case 'lake': //Forscherpfad
        {
                $session['user']['DDL_location'] = 12;
                page_header('Expedition in die dunklen Lande - Forscherpfad');
                output(get_extended_text('exp_lake'));
                viewcommentary('expedition_lake','Das Gelände erkunden:',25,'sagt',false,true,false,true);
                addnav('Wer ist hier?');
                addnav('Umsehen','expedition.php?op=whosthere&where=12&ret='.URLEncode($_SERVER['REQUEST_URI']));
                addnav('Zurück');
                addnav('Zum Zeltlager','expedition.php');
                break;
        }
        default : //Expedition Hauptseite
        {
                page_header('Expedition in die dunklen Lande - das Zeltlager');
                $state = getsetting("DDL-state",6);
                $order = getsetting("DDL-order",2);
                $session['user']['DDL_location'] = 1;
                $sql = "SELECT accounts.acctid,accounts.login FROM account_extra_info JOIN accounts USING (acctid)
                WHERE DDL_tent=".$session['user']['acctid'];
                $resultt = db_query($sql);
                output("`c`b`fD`ea`)s `SZ`;el`Ytla`;ge`Sr `)der Expedition in die `Ndunklen L`(a`)n`ed`fe`0`b`c`n`fN`ea`)c`(h`N l`Sa`;ngem Ritt, weit hinaus - weg von ".getsetting('townname','Atrahor')." - lässt du dich erschöpft vom Rücken des Reittieres gleiten und kommst sanft auf dem Grasboden auf. Du hast die hölzernen Wälle passiert, die von unzähligen Wachtürmen unterbrochen werden, auf denen Soldaten die Ebene nach Feinden ausspähen. Zwei große, hölzerne Tore ermöglichen es den Kriegern, das Lager zu betreten. Ein Knappe eilt herbei und bringt dein Tier zu einem Unterstand. Endlich hast du Zeit, das Zeltlager näher zu erkunden und näherst dich zuerst der Stelle, von der aus du den meisten Lärm vernimmst: dem Gemeinschaftszelt. Auf dem Weg dorthin gehst du an mehreren Zelten vorbei, deren Eingänge jeweils von zwei Wachen umstellt sind. Aus einem hörst du gedämpfte Gespräche, die anscheinend von den Leitern der Expedition stammen und deshalb nicht für deine Ohren bestimmt sind. Aus einem anderen vernimmst du metallisches Klirren, so als würden Waffen und Rüstungen gestapelt werden. Bevor du das größte Zelt erreichst, betrachtest du kurz die Umgebung, in der das Lager errichtet wurde: Die Zelte sind auf einer Seite umgeben von den Steilklippen eines kleinen Gebirges, von denen sich vereinzelt ein Wasserfall seinen Weg zu einem See am Fuße des Felsens sucht. Als du den Blick zur anderen Seite wendest blickst du auf eine scheinbar endlos weite Ebene. Einzelne Bäume kannst du lediglich am Rande des Sees ausmachen. Doch am meisten verwirrt dich der immer wolkenverhangene, dunkle Himmel, der das ganze Land in einen unheimlichen Schatte`Sn `Nh`(ü`)l`el`ft...`0`n`n");
                if($state==11) // Feindliches Lager zerstört
                {
                        output('`^Anders als sonst fallen dir diesmal viele bunte Flaggen auf, die rund um das Lager gehisst wurden. Auch die Wachen haben ihre Posten verlassen, von überall her ist ausgelassener Gesang und euphorisches Jubeln zu hören - `@Ihr habt das feindliche Lager zerstört und den Sieg davon getragen!`n`^Doch schon bald wird der Feind wiederkehren und ein neues Lager errichten...`n`n');
                }
                $w = Weather::get_weather();
                output('`)Das Wetter: `y'.$w['name'].'`0.`n');
                switch ($order)
                {
                case 1 :
                        $otext=' `4Stellungen halten!`0';
                        break;
                case 2 :
                        $otext=' `&Warten auf Weiteres!`0';
                        break;
                case 3 :
                        $otext=' `^Angriff!`0';
                        break;
                }
                switch ($state)
                {
                case 1 :
                        $text='`4Das Lager wurde zerstört und die Expedition ist gescheitert!`0';
                        break;
                case 2 :
                        $text='`$Das Lager wird besetzt und steht unter heftigem Abgriff!`0';
                        break;
                case 3 :
                        $text='`$Das Lager wird besetzt!`0';
                        break;
                case 4 :
                        $text='`^Die dunklen Scharen rücken auf das Lager vor!`0';
                        break;
                case 5 :
                        $text='`^Die dunklen Scharen haben die Grenze passiert!`0';
                        break;
                case 6 :
                        $text='`@Alles ist ruhig, es gibt keine feindseligen Kräfte in direkter Nähe zum Lager.`0';
                        break;
                case 7 :
                        $text='`@Unsere Späher haben die Grenze passiert.`0';
                        break;
                case 8 :
                        $text='`#Unsere Kämpfer rücken auf den Posten der dunklen Scharen vor!`0';
                        break;
                case 9;
                        $text='`#Unsere Kämpfer belagern den Posten der dunklen Scharen!`0';
                        break;
                case 10 :
                        $text='`#Unsere Kämpfer belagern den Posten der dunklen Scharen und greifen an!`0';
                        break;
                case 11 :
                        $text='`2Sieg! Der Posten der dunklen Scharen wurde vernichtet!`0';
                        break;
                }
                output('`)Lage: `y'.$text.'`n');
                output('`)Tagesbefehl:`y'.$otext.'`n');
                $sql = "SELECT * FROM ddlnews ORDER BY newsid DESC LIMIT 1";
                $result = db_query($sql);
                $rown = db_fetch_assoc($result);
                output('`n`c`ILetzte Meldung: `y'.$rown['newstext'].'`0`c`n');
              
			    addnav('Zelte');
                addnav('Expeditionsleiter','expedition.php?op=chief');
                addnav('Gemeinschaftszelt','expedition.php?op=inn');
                addnav('A?LagerArzt','expedition.php?op=doc');
                addnav('w?Lagerwache','expedition.php?op=guards');

                addnav('Dein Zelt','expedition.php?op=mytent');
                $max = db_num_rows($resultt);
                if ($max>0)
                {
                        for ($i=0; $i<$max; $i++)
                        {
                                $rowt = db_fetch_assoc($resultt);
                                addnav($rowt['login'].'\'s Zelt','expedition.php?op=othertent&who='.$rowt['acctid']);
                        }
                }
                if ($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ADMIN))
                {
                        addnav('Mod-Aktionen');
                        addnav('Zustand erhöhen','expedition.php?op=risestate',false,false,false,false);
                        addnav('Zustand senken','expedition.php?op=lowerstate',false,false,false,false);
                        addnav('Befehl zum Angriff','expedition.php?op=order&nbr=3',false,false,false,false);
                        addnav('Befehl zum Nichtstun','expedition.php?op=order&nbr=2',false,false,false,false);
                        addnav('Befehl zur Verteidigung','expedition.php?op=order&nbr=1',false,false,false,false);
                }
                addnav('Besondere Orte');
                addnav('p?Antreteplatz','expedition.php?op=milplace');
                addnav('Q?Heiße Quellen','expedition.php?op=pools');
                addnav('Einöde','expedition.php?op=wastes');
                addnav('Tropfsteinhöhle','expedition.php?op=cave');
                addnav('Forscherpfad','expedition.php?op=lake');
                addnav('Wer ist hier?');
                addnav('Umsehen','expedition.php?op=whosthere&where=1&ret='.URLEncode($_SERVER['REQUEST_URI']));
                addnav('Reisen');
                addnav('Zu den Besuchern','expedition_guest.php');
				addnav('Zurück nach '.getsetting('townname','Atrahor'),'village.php');
                addnav('#?In die Felder (logout)','login.php?op=logout',true);
                output('`0Du hörst einige der anderen Teilnehmer dieser Expedition schwatzen:`n');
                viewcommentary('expedition_main','Mitreden',25);
                break;
        }
        }

        if ($battle) //Kampf wie überall
        {
                $session['user']['DDL_location'] = 7;
                include("battle.php");
                if ($victory)
                {
                        output("`n`&Du hast `^".$badguy['creaturename']."`& geschlagen.`0");
                        $DDL_opps=getsetting("DDL_opps","0");
                        $DDL_opps--;
                        if ($DDL_opps<0)
                        {
                                $DDL_opps=0;
                        }
                        savesetting("DDL_opps","$DDL_opps");
                        if (e_rand(1,2)==1)
                        {
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes',".$session['user']['acctid'].",': `@hat `^".$badguy['creaturename']."`@ nieder gestreckt.`0')";
                        }
                        else
                        {
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes',".$session['user']['acctid'].",': `@gelang es, `^".$badguy['creaturename']."`@ in die Flucht zu schlagen.`0')";
                        }
                        db_query($sql);
                        addnews_ddl($session['user']['name']." `&hat `#".$badguy['creaturename']." `&im Kampf geschlagen.`0");
                        switch ($badguy['creaturename'])
                        {
                        case 'Kommandant aus den Dunklen Landen' :
                                $points=3;
                                $wounds=2;
                                break;
                        case 'Soldat aus den Dunklen Landen' :
                                $points=2;
                                $wounds=2;
                                break;
                        default :
                                $points=1;
                                $wounds=1;
                                break;
                        }
                        if ($badguy['diddamage']==0)
                        {
                                output('`n`@Perfekter Kampf!`n');
                                $points*=2;
                        }
                        else
                        {
                                if (e_rand(1,3)==2)
                                {
                                        if ($wounds==1)
                                        {
                                                $attr="leichte ";
                                        }
                                        output('`n`^Du gewinnst den Kampf, erleidest aber eine '.$attr.'Verwundung!`0`n');
                                        $sql = "SELECT wounds FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
                                        $result = db_query($sql);
                                        $row = db_fetch_assoc($result);
                                        $new_wounds=$row['wounds']+$wounds;
                                        if ($new_wounds>5)
                                        {
                                                $new_wounds=5;
                                        }
                                        $sql = "UPDATE account_extra_info SET wounds=$new_wounds WHERE acctid=".$session['user']['acctid']."";
                                        db_query($sql);
                                        if($new_wounds>=4) // 4x klein verletzt
                                        {
                                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes',".$session['user']['acctid'].",': `&wurde im Kampf schwer verwundet und sollte so schnell wie möglich aus dem Kampfgebiet verschwinden.`0')";
                                                db_query($sql);
                                        }
                                }
                        }
                        $badguy=array();
                        $session['user']['badguy']="";
                        $balance=getsetting("DDL-balance","0");
                        $order=getsetting("DDL-order",2);
                        $balance_win=getsetting("DDL_balance_win",25);
                        $balance+=$points;
                        savesetting("DDL-balance","$balance");
                        if ($balance>=$balance_win && $order==3)
                        {
                                output('`&`n`nDer Angriff ist geglückt! Der Feind wurde zurück gedrängt!`n');
                                addnews_ddl("`@Heute gelang uns bei unserem Angriff ein Vorstoss!`&`n`&Neuer Tagesbefehl : Warten auf Weiteres!`&");
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes','1','/msg `2Unser Angriff war ein Erfolg! Der Feind wurde zurück geworfen.`0')";
                                db_query($sql);
                                $medalpoints=getsetting("DDL-medal",10);
                                $medalpoints+=2;
                                savesetting("DDL-medal",$medalpoints);
                                // Rundmail ?
                                savesetting("DDL-balance","0");
                                savesetting("DDL-order",2);
                                savesetting("DDL_act_order","0");
                                savesetting("DDL_opps","0");
                                $state=getsetting("DDL-state",6);
                                $state++;
                                if($state>=11) // Sieg ?
                                {
                                        output('`&`n`nDer feindliche Posten wurde zerstört!`n');
                                        addnews_ddl("`@Sieg! Der feindliche Posten wurde zerstört!`&");
                                        $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes','1','/msg `^Sieg! Des feindliche Posten wurde zerstört. Von überall her erklingen Fanfaren.`0')";
                                        db_query($sql);
                                        $medalpoints=getsetting("DDL-medal",10);
                                        $medalpoints+=3;
                                        savesetting("DDL-medal",$medalpoints);
                                }
                                savesetting("DDL-state",$state);
                                savesetting("DDL_opps","0");
                        }
                        else if ($balance>=$balance_win && $order==1)
                        {
                                output('`&`n`nDer feindliche Angriff ist gescheitert! Wir haben die Stellungen gehalten!`n');
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes','1','/msg `2Der Angriff des Feindes wurde erfolgreich abgewehrt!`0')";
                                db_query($sql);
                                addnews_ddl("`@Heute wurde der Angriff des Feindes abgewehrt!`&`n`&Neuer Tagesbefehl : Warten auf Weiteres!`&");
                                // Rundmail ?
                                savesetting("DDL-balance","0");
                                savesetting("DDL-order",2);
                                savesetting("DDL_act_order","0");
                                savesetting("DDL_opps","0");
                                $state=getsetting("DDL-state",6);
                        }
                        addnav('Weiter','expedition.php?op=wastes');
                }
                else if ($defeat)
                {
                        output("`n`4Du verlierst den Kampf und wirst schwer verletzt.`0`n`&Als du aus der Ohnmacht erwachst, stellst du fest, dass du dich beim Lagerarzt befindest.`0`n");
                        if (e_rand(1,2)==1)
                        {
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes',".$session['user']['acctid'].",': `4wird von `^".$badguy['creaturename']."`4 niedergeschmettert und bleibt regungslos liegen.`0')";
                        }
                        else
                        {
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes',".$session['user']['acctid'].",': `4geht schwer verletzt zu Boden!`0')";
                        }
                        db_query($sql);
                        $session['user']['hitpoints']=1;
                        addnews_ddl($session['user']['name']." `4wurde heute im Kampf schwer verwundet!`0");
                        $sql = "UPDATE account_extra_info SET wounds=5 WHERE acctid=".$session['user']['acctid']."";
                        db_query($sql);
                        $balance=getsetting("DDL-balance","0");
                        $balance--;
                        savesetting("DDL-balance",$balance);
                        $order=getsetting("DDL-order",2);
                        $balance_lose=getsetting("DDL_balance_lose",-6);
                        if ($balance<=$balance_lose && $order==1)
                        {
                                output('`4`n`nDie Verteidigung ist misslungen! Der Feind ist durchgebrochen!`n');
                                addnews_ddl("`4Heute wurden wir vom Feind zurück gedrängt!`&`n`&Neuer Tagesbefehl : Warten auf Weiteres!`&");
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes','1','/msg `2Unsere Verteidigung wurde überrant! Der Feind ist durchgebrochen.`0')";
                                db_query($sql);
                                // Rundmail ?
                                savesetting("DDL-balance","0");
                                savesetting("DDL-order",2);
                                savesetting("DDL_act_order","0");
                                savesetting("DDL_opps","0");
                                $state=getsetting("DDL-state",6);
                                $state--;
                                if($state<=1) // Niederlage ?
                                {
                                        output('`4`n`nUnser Lager wurde zerstört!`n');
                                        addnews_ddl("`4Flieht um Euer Leben! Unser Lager wurde zerstört!`&");
                                        $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes','1','/msg `4Unser Lager wurde vollständig zerstört.`0')";
                                        db_query($sql);
                                }
                                savesetting("DDL-state",$state);
                                savesetting("DDL_opps","0");
                        }
                        else if ($balance<=$balance_lose && $order==3)
                        {
                                output('`&`n`nUnser Angriff ist gescheitert! Der Feind hat die Stellungen gehalten!`n');
                                $sql="INSERT INTO commentary(postdate,section,author,comment) VALUES(now(),'expedition_wastes','1','/msg `4Unser Angriff wurde abgewehrt!`0')";
                                db_query($sql);
                                addnews_ddl("`@Heute wurde unser Angriff abgewehrt!`&`n`&Neuer Tagesbefehl : Warten auf Weiteres!`&");
                                // Rundmail ?
                                savesetting("DDL-balance","0");
                                savesetting("DDL-order",2);
                                savesetting("DDL_act_order","0");
                                savesetting("DDL_opps","0");
                                $state=getsetting("DDL-state",6);
                        }
                        addnav('Weiter','expedition.php?op=doc');
                }
                else
                {
                        fightnav();
                }
        }
}

page_footer();
?>