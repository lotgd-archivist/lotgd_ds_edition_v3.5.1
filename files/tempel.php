<?php
// Name: tempel.php
// Autor: tcb / Talion für http://lotgd.drachenserver.de (mail: t@ssilo.de)
// Erstellungsdatum: 5.5.05 - 17.5.05
// Erfordert Mods in Dateien: gardens.php, rock.php, beggar.php, dorfamt.php, bio.php, newday.php, configure.php
// Beschreibung:
//                Führt neues Amt Priester ein, zur Speicherung wird Var profession (Wertebereich von 11-13) genutzt.
//                Priester können verheiraten, scheiden, Flüche aufheben, Kopfgeldträger verfluchen, bekommen Bonus auf mystische Künste
//                Tempel-Location im Garten: Bettelstein hierherverlegt, Erlösung von Kopfgeld gegen Gems möglich, Heiratslocation
//
// Autor: Azura für http://lotgd.drachenserver.de (mail: alexander-glatho@web.de)
// Erstellungsdatum: 1.12.05 - 7.12.05
//                Führt neuen Beruf Hexer ein, zur Speicherung wird Var profession (Wertebereich von 61-63) genutzt.
//                Hexer bilden das gegenstück zu Priestern und können "böse" heiraten vornehmen
//                Im Wald als neuer Punkt zu finden
//
//                Neues Heiratssytem:
//                        - Bei >= 5 Flirts im Garten Verlobung
//                        - Priester muss Heirat starten (Vorsicht: Darf nicht gleichzeitig einer der zu Verheiratenden sein)
//                        - Priester schließt Heirat ab, Weiteres gleichbleibend
//                        Statusvar: 1 = im Gange, 2 = verheiratet, 3 = abgeschlossen
// Änderungen:
//
// 22.02.06 Bugfix und Anpassungen by Maris(Maraxxus@gmx.de)
// 22.08.07 Waldlichtung der Hexen in den Tempel integriert (Salator)

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
require_once(LIB_PATH.'profession.lib.php');
$str_filename = basename(__FILE__);

if($_GET['op'] == 'witches')
{
    page_header("Die Waldlichtung");
}
else
{
    page_header("Der Tempel");
}

addcommentary();
checkday();

define("SCHNELLHOCHZ_KOSTEN",3000);
define("SCHNELLHOCHZ_ERLAUBT",0);
define("STATUS_START",1);
define("STATUS_VERHEIRATET",2);
define("STATUS_ABGESCHLOSSEN",3);
//define("STATUS_INVISIBLE",4); //bit3 setzen, Werte 5-7 ergeben sich daraus
define("TEMPLE_SERVANT_TURNS",2);
define("TEMPLE_SERVANT_MINDAYS",10);
define("TEMPLE_SERVANT_MAX",5);


function show_rules () {
    $str_out = '<table border="0">
										<tr>
												<td valign="top">`4I.</td>
												<td>`&Die Priesterkaste und das Amt des Priesters ist in Ehren zu halten. Keinesfalls darf irgendeine Aktion ergriffen werden, die die unbefleckte Ehre der Priester beschmutzen würde!</td>
										</tr>
										<tr>
												<td valign="top">`4II.</td>
												<td>`&Den Anweisungen des Hohepriesters ist Folge zu leisten. Er repräsentiert die oberste Autorität des Priesterstands!</td>
										</tr>
										<tr>
												<td valign="top">`4III.</td>
												<td>`&Alle Gesetze dieser Stadt gelten in besonderem Maße für Priester!</td>
										</tr>
										<tr>
												<td valign="top">`4IV.</td>
												<td>`&Wer einen Priester bei einem Einbruch angreift und tötet, muss damit rechnen, für einige Tage verflucht zu werden!</td>
										</tr>
										<tr>
												<td valign="top">`4V.</td>
												<td>`&Priester dürfen hilflosen Schutzsuchenden und Personen, die durch besonderen Edelmut hervorragen, einen Segen erteilen!</td>
										</tr>
										<tr>
												<td valign="top">`4VI.</td>
												<td>`&Auf der anderen Seite ist es ihnen erlaubt, rücksichtslose und blinde Barbarei mit Flüchen zu ahnden!</td>
										</tr>
										<tr>
												<td valign="top">`4VII.</td>
												<td>`&Niemals jedoch sollen Priester ihre persönlichen Angelegenheiten mit ihrer Berufung mischen!</td>
										</tr>
								</table>';
    output( $str_out );
}

function show_witchrules() {

    output("`4I. `&Den Anweisungen des Hexenmeisters bzw der Hexenmeisterin ist Folge zu leisten. Sie repräsentieren die oberste Autorität des Zirkels!`n");
    output("`4II. `&Es ist verboten dem Wald und Tieren grundlos Schaden zuzufügen!`n");
    output("`4III. `&Es ist verboten den Ritualplatz zu stören oder laufende Rituale zu unterbrechen!`n");
    output("`4IV. `&Das Tragen von Waffen im Kreis ist nur dem Wächter erlaubt! Die Entweihung der heiligen Stätte wird mit Flüchen bestraft!`n");
    output("`4V. `&Wer einer Hexe das Leben nimmt hat die Konsequenzen dafür zu tragen! Ebenso ist es keiner Hexe erlaubt einen Bürger der Stadt zu töten!`n");
    output("`4VI. `&Es ist verboten den Altar und die geweihten Gegenstände darauf ohne Erlaubnis zu berühren.`n");
    output("`4VII. `&Sobald der Kreis geschlossen ist, darf dieser nur noch betreten oder verlassen werden wenn die ritualführende Hexe dies erlaubt.`n");
}

function show_priest_list ($admin_mode=0) {
    global $access_control;
    $bool_lockhtml = $access_control->su_check(access_control::SU_RIGHT_LOCKHTML);
    $str_out = '';
    $sql = 'SELECT         a.name,
										a.profession,
										a.acctid,
										a.login,
										a.loggedin,
										a.activated,
										a.expedition,
										a.imprisoned,
										a.laston
										'.($bool_lockhtml ? ',aei.html_locked' : '').'
						FROM accounts a
						'.($bool_lockhtml ? 'INNER JOIN account_extra_info aei ON a.acctid=aei.acctid ' : '').
        'WHERE a.profession='.PROF_PRIEST_HEAD.' OR a.profession='.PROF_PRIEST;
    $sql .= ($admin_mode>=1) ? ' OR a.profession='.PROF_PRIEST_NEW : '';
    $sql .= ' ORDER BY profession DESC, name';

    $res = db_query($sql);

    if(db_num_rows($res) == 0) {
        $str_out .= '`n`iEs gibt keine Priester/innen!`i`n';
    }
    else {

        $str_out .= '<table border="0" cellpadding="5" cellspacing="2" bgcolor="#999999">
				<tr class="trhead">
				<th>Nr.</th>
				<th>Name</th>
				<th>Funktion</th>
				<th>Status</th>
				</tr>';
        $cnt = db_num_rows($res);
        for($i=1; $i<=$cnt; $i++) {

            $p = db_fetch_assoc($res);

            $xtra = $name = '';



            switch( $p['profession'] ) {

                case PROF_PRIEST_HEAD:
                    $name .= '`bHohepriester/in`b';
                    if($admin_mode>=4) {
                        $xtra .= ',tempel_ent';
                        //$str_out .= '`n<a href="tempel.php?op=entlassen&id='.$p['acctid'].'">Entlassen</a>';
                        addnav("","tempel.php?op=entlassen&id=".$p['acctid']);

                        $xtra .= ',tempel_deg';
                        //$str_out .= '`n<a href="tempel.php?op=hohep_deg&id='.$p['acctid'].'">Degradieren</a>';
                        addnav("","tempel.php?op=hohep_deg&id=".$p['acctid']);
                    }
                    break;

                case PROF_PRIEST:
                    $name .= 'Priester/in';
                    if($admin_mode>=3) {
                        $xtra .= ',tempel_ent';
                        //$str_out .= '`n<a href="tempel.php?op=entlassen&id='.$p['acctid'].'">Entlassen</a>';
                        addnav("","tempel.php?op=entlassen&id=".$p['acctid']);

                        if($admin_mode>=4) {
                            $xtra .= ',tempel_hp';
                            //$str_out .= '`n<a href="tempel.php?op=hohep&id='.$p['acctid'].'">Zum Hohepriester machen</a>';
                            addnav("","tempel.php?op=hohep&id=".$p['acctid']);
                        }
                    }
                    break;

                case PROF_PRIEST_NEW:
                    $name .= 'Novize/in';
                    if($admin_mode>=3) {
                        $xtra .= ',tempel_auf';
                        //  $str_out .= '`n<a href="tempel.php?op=aufnehmen&id='.$p['acctid'].'">Aufnehmen</a>';
                        addnav("","tempel.php?op=aufnehmen&id=".$p['acctid']);
                        $xtra .= ',tempel_ab';
                        // $str_out .= '`n<a href="tempel.php?op=ablehnen&id='.$p['acctid'].'">Ablehnen</a>';
                        addnav("","tempel.php?op=ablehnen&id=".$p['acctid']);
                        if($admin_mode>=4) {
                            $xtra .= ',tempel_hp';
                            addnav("","tempel.php?op=hohep&id=".$p['acctid']);
                        }
                    }
                    break;

                default:
                    break;
            }

            $str_out .= '<tr class="'.($i%2?'trlight':'trdark').'">
						<td>'.$i.'</td>
						<td>'.CRPChat::menulink( $p, $xtra ).'</td><td>`7'.$name;
            $str_out .= '</td>
						<td>'.(user_get_online(0,$p)?'`@online`&':'`4offline`&').'</td>
						</tr>';

        }        // END for


        $str_out .= '</table>';


    }        // END priester vorhanden
    output( $str_out, true );

}        // END show_priest_list

function show_witch_list($admin_mode=0) {
    global $access_control;
    $bool_lockhtml = $access_control->su_check(access_control::SU_RIGHT_LOCKHTML);
    $str_out = '';
    $sql = 'SELECT         a.name,
										a.profession,
										a.acctid,
										a.login,
										a.loggedin,
										a.activated,
										a.expedition,
										a.imprisoned,
										a.laston
										'.($bool_lockhtml ? ',aei.html_locked' : '').'
						FROM accounts a
						'.($bool_lockhtml ? 'INNER JOIN account_extra_info aei ON a.acctid=aei.acctid ' : '').
        'WHERE a.profession='.PROF_WITCH_HEAD.' OR a.profession='.PROF_WITCH;
    $sql .= ($admin_mode>=1) ? ' OR a.profession='.PROF_WITCH_NEW : '';
    $sql .= ' ORDER BY profession DESC, name';

    $res = db_query($sql);

    if (db_num_rows($res) == 0)
    {
        $str_out .= '`n`iEs gibt keine Hexen!`i`n';
    }
    else
    {
        $str_out .= '<table border="0" cellpadding="5" cellspacing="2" bgcolor="#999999">
				<tr class="trhead">
				<th>Nr.</th>
				<th>Name</th>
				<th>Funktion</th>
				<th>Status</th>
				</tr>';

        for ($i=1; $i<=db_num_rows($res); $i++)
        {

            $p = db_fetch_assoc($res);

            $xtra = $name = '';


            switch ($p['profession'] )
            {

                case PROF_WITCH_HEAD:
                    $name .= '`bHexenmeister/in`b';
                    if ($admin_mode>=4)
                    {
                        $xtra .= ',witch_deg';
                        // output('`n<a href="tempel.php?op=hohep_deg&id='.$p['acctid'].'">Grad abnehmen</a>',true);
                        addnav('','tempel.php?op=hohep_deg&id='.$p['acctid']);
                    }
                    break;

                case PROF_WITCH:
                    $name .= 'Hexe/r';
                    if ($admin_mode>=3)
                    {
                        $xtra .= ',witch_ent';
                        //output('`n<a href="tempel.php?op=entlassen&id='.$p['acctid'].'">Verstossen</a>',true);
                        addnav('','tempel.php?op=entlassen&id='.$p['acctid']);

                        if ($admin_mode>=4)
                        {
                            $xtra .= ',witch_hp';
                            //output('`n<a href="tempel.php?op=hohep&id='.$p['acctid'].'">Weihe zum Hexenmeister</a>',true);
                            addnav('','tempel.php?op=hohep&id='.$p['acctid']);
                        }
                    }
                    break;

                case PROF_WITCH_NEW:
                    $name .= 'Schüler/in';
                    if ($admin_mode>=3)
                    {
                        $xtra .= ',witch_auf';
                        //output('`n<a href="tempel.php?op=aufnehmen&id='.$p['acctid'].'">Initiieren</a>',true);
                        addnav('','tempel.php?op=aufnehmen&id='.$p['acctid']);

                        $xtra .= ',witch_ab';
                        //output('`n<a href="tempel.php?op=ablehnen&id='.$p['acctid'].'">Ablehnen</a>',true);
                        addnav('','tempel.php?op=ablehnen&id='.$p['acctid']);
                    }
                    break;

                default:
                    break;
            }


            $str_out .= '<tr class="'.($i%2?'trlight':'trdark').'">
				<td>'.$i.'</td>
				<td>'.CRPChat::menulink( $p , $xtra).'</td><td>`7'.$name;

            $str_out .= '</td>
						<td>'.(user_get_online(0,$p)?'`@online`&':'`4offline`&').'</td>
						</tr>';
        }
        // END for
        $str_out .= '</table>';

    }
    output($str_out,true);
    // END hexe vorhanden

} // END show_witch_list

function show_servant_list ($admin_mode=0) {

    $sql = 'SELECT         a.name,
										a.profession,
										a.acctid,
										a.login,
										a.loggedin,
										a.daysinjail,
										a.activated,
										a.expedition,
										a.imprisoned,
										i.temple_servant,
										i.html_locked
						FROM accounts a
						LEFT JOIN account_extra_info i ON i.acctid=a.acctid
						WHERE a.profession='.PROF_TEMPLE_SERVANT;
    $sql .= ' ORDER BY profession DESC, name';
    $res = db_query($sql);

    if(db_num_rows($res) == 0) {
        $str_out = '`n`iEs gibt keine Tempeldiener!`i`n';
    }
    else {

        $str_out = '<table border="0" cellpadding="5" cellspacing="2" bgcolor="#999999">
				<tr class="trhead">
				<th>Nr.</th>
				<th>Name</th>
				<th>Häftlingstage</th>
				<th>Arbeitstage bisher</th>
				<th>Status</th>
				'.($admin_mode ? '<th>Aktionen</th>' : '').'
				</tr>';
        $cnt = db_num_rows($res);
        for($i=1; $i<=$cnt; $i++) {

            $p = db_fetch_assoc($res);

            $p['temple_servant'] = ($p['temple_servant'] >= 20 ? $p['temple_servant']*0.05 : $p['temple_servant']);

            $str_out .= '<tr class="'.($i%2?'trlight':'trdark').'">
						<td>'.$i.'</td>
						<td>'.CRPChat::menulink( $p).'</td>
						<td>'.$p['daysinjail'].'</td><td>'.$p['temple_servant'].'</td>
						<td>'.(($p['loggedin'])?'`@online`&':'`4offline`&').'</td>';

            if($admin_mode) {
                $str_out .= '<td><a href="tempel.php?op=servant_stop&id='.$p['acctid'].'">Entlassen</a></td>';
                addnav("","tempel.php?op=servant_stop&id=".$p['acctid']);
            }

            $str_out .= '</tr>';

        }        // END for

        $str_out .= '</table>';

    }        // END Diener vorhanden

    output( $str_out, true );

}

function show_flirt_list ($admin_mode=0,$married=0) {
    $link = calcreturnpath();
    $link .= '&';

    $ppp = 90000;

    $count_sql = "SELECT COUNT(*) AS anzahl FROM accounts a WHERE ";

    $str_search = '';

    if(!empty($_POST['search']))
    {
        $str_search = str_create_search_string($_POST['search']);
    }

    if($married < 2) {

        if(!empty($str_search)) {
            $str_search = ' AND (a.name LIKE "'.$str_search.'" OR b.name LIKE "'.$str_search.'") ';
        }

        $sql = 'SELECT         a.name AS name_a,
												a.acctid AS acctid_a,
												b.name AS name_b,
												b.acctid AS acctid_b,
												a.login AS login_a,
												b.login AS login_b
								FROM accounts a,accounts b
								WHERE
										a.marriedto=b.acctid  '.$str_search;
        if($married) {
            $sql .= 'AND ( a.charisma = 4294967295 AND b.charisma = 4294967295 )';
            $count_sql .= 'a.charisma=4294967295 AND a.marriedto>0 AND a.marriedto<4294967295';
        }
        else {
            $sql .= 'AND ( a.charisma = 999 AND b.charisma = 999 )';
            $count_sql .= 'a.charisma=999 AND a.marriedto>0 AND a.marriedto<4294967295';
        }

        $sql .= 'ORDER BY name_a, name_b';

    }
    else {
        if(!empty($str_search)) {
            $str_search = ' AND (a.name LIKE "'.$str_search.'") ';
        }

        $sql = 'SELECT a.sex,a.name AS name_a,a.acctid AS acctid_a, a.login AS login_a FROM accounts a
										WHERE a.marriedto=4294967295 '.$str_search;
        $sql .= 'ORDER BY name_a';
        $count_sql .= 'a.marriedto=4294967295';
    }

    $count_res = db_query($count_sql);
    $c = db_fetch_assoc($count_res);
    $bs = array();
    if($c['anzahl'] == 0) {
        output("`iEs gibt keine Paare!`i");
    }
    else {

        // wegen Paaren
        if($married < 2) {$c['anzahl'] = floor($c['anzahl'] * 0.5);}

        $page = max((int)$_GET['page'],1);

        $last_page = ceil($c['anzahl'] / $ppp);

        for($i=1; $i<=$last_page; $i++) {

            $offs_max = min($i * $ppp,$c['anzahl']);
            $offs_min = ($i-1) * $ppp + 1;

            addnav("Seite ".$i." (".$offs_min." - ".$offs_max.")",$link."page=".$i);

        }

        $offs_min = ($page-1) * $ppp;

        $sql .= " LIMIT ".$offs_min.",".$ppp;

        $res = db_query($sql);

        $str_searchlnk = $link;
        addnav('',$str_searchlnk);

        output('<table border="0" cellpadding="3">
								<tr class="trhead" colspan="10">
										<form method="POST" action="'.$str_searchlnk.'">
												<input type="text" name="search" maxlenghth="50" value="'.stripslashes($_POST['search']).'"> <input type="submit" value="Suchen">
										</form>
								</tr>
								<tr class="trhead">
								<th>Nr.</th>',true);
        if($married < 2) {
            output('<th>Name</th>
						<th>Name</th>',true);
        }
        else {
            output('<th> Spieler</th>
						<th> NPC</th>',true);
        }
        output( (($admin_mode)?'<th>Aktionen</th>':'').'
				</tr>',true);

        while($p = db_fetch_assoc($res)) {

            if($married<2)
            {
                if(isset($bs[$p['login_a']])  && $bs[$p['login_a']]==42)
                {
                    continue;
                }
                else
                {
                    $bs[$p['login_b']]=42;
                }
            }

            $offs_min++;
            $mail_a = ($admin_mode>=2) ? '<a href="mail.php?op=write&to='.rawurlencode($p['login_a']).'" target="_blank" onClick="'.popup("mail.php?op=write&to=".rawurlencode($p['login_a']) ).';return false;"><img src="./images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>' : '';
            $mail_b = ($admin_mode>=2) ? '<a href="mail.php?op=write&to='.rawurlencode($p['login_b']).'" target="_blank" onClick="'.popup("mail.php?op=write&to=".rawurlencode($p['login_b']) ).';return false;"><img src="./images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>' : '';
            $bio_a        = '<a href="javascript:void(0);" target="_blank" onClick="'.popup('bio.php?id='.$p['acctid_a']).';return false;">'.$p['name_a'].'</a>';
            $bio_b        = '<a href="javascript:void(0);" target="_blank" onClick="'.popup('bio.php?id='.$p['acctid_b']).';return false;">'.$p['name_b'].'</a>';

            output('<tr class="'.(($offs_min%2)?'trdark':'trlight').'"><td>'.$offs_min.'</td>',true);
            output('<td>'.$mail_a.$bio_a.'</td>',true);
            if($married < 2) {output('<td>'.$mail_b.$bio_b.'</td>',true);}
            else {output('<td>'.(($p['sex']==0)?'Violet':'Seth').'</td>',true);}

            if($admin_mode>=2) {
                output('<td>',true);
                if(!$married) {
            
                    if(getsetting("temple_status",0) == 0 || getsetting("temple_status",0) == STATUS_ABGESCHLOSSEN) {
                        output('<a href="tempel.php?op=hochz&id1='.$p['acctid_a'].'&id2='.$p['acctid_b'].'">Hochzeit beginnen</a>',true);
                        addnav("","tempel.php?op=hochz&id1=".$p['acctid_a']."&id2=".$p['acctid_b']);
                        output('`n<a href="tempel.php?op=trennung&id1='.$p['acctid_a'].'&id2='.$p['acctid_b'].'">Verlobung lösen</a>',true);
                        addnav("","tempel.php?op=trennung&id1=".$p['acctid_a']."&id2=".$p['acctid_b']);
                    }
                    elseif(getsetting("temple_id1",0) == $p['acctid_a'] || getsetting("temple_id2",0) == $p['acctid_b']) {
                        output('`iHochzeit im Gange`i',true);
                    }

                }
                else {
                    if($married==2) {
                        output('<a href="tempel.php?op=scheidung&id1='.$p['acctid_a'].'&npc=1">Trennen</a>',true);
                        addnav("","tempel.php?op=scheidung&id1=".$p['acctid_a']."&npc=1");
                    }
                    else {
                        output('<a href="tempel.php?op=scheidung&id1='.$p['acctid_a'].'&id2='.$p['acctid_b'].'">Trennen</a>',true);
                        addnav("","tempel.php?op=scheidung&id1=".$p['acctid_a']."&id2=".$p['acctid_b']);
                    }

                }
                output('</td>',true);
            }

            output('</tr>',true);

        }        // END for

        output('</table>',true);

    }        // END paare vorhanden

}        // END show_flirt_list

{ //allgemeines Variablen setzen
    $op = (isset($_GET['op'])) ? $_GET['op'] : '';
    $priest = 0;
    $witch = 0;
    if ($access_control->su_check(access_control::SU_RIGHT_DEBUG))
    {
        if($session['tempeldebug']=='witch') $witch = 4;
        if($session['tempeldebug']=='priest') $priest = 4;
    }
    elseif($session['user']['profession'] == PROF_PRIEST_NEW)
    {
        $priest = 0;
    }
    elseif($session['user']['profession'] == PROF_PRIEST)
    {
        $priest = 2;
    }
    elseif($session['user']['profession'] == PROF_PRIEST_HEAD)
    {
        $priest = 3;
    }
    else if ($session['user']['profession'] == PROF_WITCH_NEW)
    {
        $witch = 0;
    }
    else if ($session['user']['profession'] == PROF_WITCH)
    {
        $witch = 2;
    }
    else if ($session['user']['profession'] == PROF_WITCH_HEAD)
    {
        $witch = 3;
    }
}

switch ($op) {

    case '':
    { //Tempel Startseite
        $show_invent = true;

        if ($access_control->su_check(access_control::SU_RIGHT_DEBUG))
        {
            $witch = 0;
            $priest = 4;
            $session['tempeldebug']='priest';
        }
        output("`b`c`)Der Tempel`c`b`n");
        output("`4E`zh`[rf`)ur`ech`&tsvoll gehst du die Stufen empor, betritts einen kleinen Vorraum, der dich ins das Innere des Tempel bringt. Du siehst nach oben, hoch über dir spannt sich das kuppelförmige Dach wie ein Zelt über die Weite, an der Frontseite in einen Rundbogen übergehende Tempelhalle. Durch hohe, schmale Rundbogenfenster an den Seitenwänden fällt etwas Licht in den Raum. Darunter verläuft ein quadratischer Säulengang, hinter dem eine Pforte ins Allerheiligste führt, welches nur den Priestern Zugang gewährt.`n
Den vorderen Teil dominiert ein erhöht stehender, marmorner Altar, verziert mit vielerlei magischen Symbolen. Auf der rechten Seite, hinter den Säulen, entdeckst du einen weiteren, aber kleineren Altar, der für Opfer gedacht zu sei`en s`)ch`[ei`zn`4t.`n`n`n`n");

        if(getsetting("temple_status",0) > 0)
        {
            $sql = "SELECT name,acctid
										FROM accounts
										WHERE acctid=".getsetting('temple_id1',0)."
										OR acctid=".getsetting('temple_id2',0)."
										ORDER BY sex";
            $res = db_query($sql);
            $p1 = db_fetch_assoc($res);
            $p2 = db_fetch_assoc($res);

            if(getsetting("temple_status",0) == STATUS_START)
            {
                output("`c`i`&Heute wird hier das wunderschöne Fest der Hochzeit von ".$p1['name']."`& und ".$p2['name']."`& begangen!");
            }
            elseif(getsetting("temple_status",0) == STATUS_VERHEIRATET || getsetting("temple_status",0) == STATUS_ABGESCHLOSSEN)
            {
                output("`c`i`&".$p1['name']."`& und ".$p2['name']."`& haben gerade geheiratet! Herzlichen Glückwunsch!");
            }
            output("`i`c`n`n");
        }

        viewcommentary("temple","`aLeise sprechen:",25,"raunt");

        if($priest >= 2)
        {
            addnav("Priester");
            addnav("A?Zum Allerheiligsten","tempel.php?op=secret");

            if(getsetting('temple_priest_id',0) == $session['user']['acctid'])
            {
                addnav("Aktionen");
                if(getsetting('temple_status',0) == STATUS_START)
                {
                    addnav("`bVerheiraten`b","tempel.php?op=hochz_ok&heirat=1");
                    addnav("+?Verheiraten+Segnen","tempel.php?op=hochz_ok&heirat=1&segen=1");
                    addnav("Hochzeit abbrechen","tempel.php?op=hochz_ende&status=0",false,false,false,false);
                }
                elseif(getsetting('temple_status',0) == STATUS_VERHEIRATET)
                {
                    addnav("`bZeremonie abschließen`b","tempel.php?op=hochz_ende&status=".STATUS_ABGESCHLOSSEN);
                }
            }
        }

        addnav("Tempel");
        addnav("Opfern","tempel.php?op=opfer");
        addnav("Liste der Priester","tempel.php?op=priest_list");
        addnav("Liste der Diener","tempel.php?op=servant_list&public=1");
        addnav("Ehepaare","tempel.php?op=married_list_public");
        addnav("Schwarzes Brett","tempel.php?op=board");
        if($session['user']['charisma']==999 && SCHNELLHOCHZ_ERLAUBT)
        {
            addnav("Schnellhochzeit (".SCHNELLHOCHZ_KOSTEN." Gold)","tempel.php?op=hochz_schnell");
        }

        addnav("Erlösung von Sünden");
        if($session['user']['profession'] == 0)
        {
            addnav('Als Tempeldiener anfangen!','tempel.php?op=servant_apply');
        }
        else if($session['user']['profession'] == PROF_TEMPLE_SERVANT)
        {
            addnav('Tempel fegen','tempel.php?op=serve');
            addnav('Priestern die Schuhe küssen','tempel.php?op=serve&what=kiss');
        }


        addnav('Kopfgeld','tempel.php?op=bounty_del');

        addnav("Verschiedenes");
        addnav("G?Zurück in den Garten","gardens.php");
        addnav("Zurück zum Stadtzentrum","village.php");

        break;
    }

    case 'witches':
    { //Waldlichtung Startseite

        if ($access_control->su_check(access_control::SU_RIGHT_DEBUG))
        {
            $witch = 4;
            $priest = 0;
            $session['tempeldebug']='witch';
        }
        output("`b`c`PDi`ke `GWa`gldlic`Ght`ku`Png`c`b`n");
        output("`PDi`ke `GWa`gld`alichtung ist von Ästen und Laub freigeräumt. Mit jungen Zweigen ist die
				Form eines großen Kreises auf dem Boden angedeutet, in dessen Mitte ein steinerner Altar aufgebaut ist.`n
				Auf dem Altar befinden sich drei schwarze Kerzen und ein Weihrauchbehältnis, außerdem eine Schale mit frischem Wasser und eine Schale mit Meersalz. Ein seltsamer Zauber umgibt diesen Ort mit Stille und Frieden. Es scheint als vergehe die Zeit hier in einem anderen Maße als außerhalb der L`gic`Ght`ku`Png.
				`n`n`n`n");

        $witch_status=getsetting("witch_status",0);
        if($witch_status > 0)
        {

            $sql = "SELECT name,acctid FROM accounts
						WHERE acctid=".getsetting('witch_id1',0)." OR acctid=".getsetting('witch_id2',0)." ORDER BY sex";
            $res = db_query($sql);
            $p1 = db_fetch_assoc($res);
            $p2 = db_fetch_assoc($res);

            if ($witch_status == STATUS_START)
            {
                output("`c`i`&Heute wird hier das Ritual der Hochzeit von ".$p1['name']."`& und ".$p2['name']."`& begangen!");
            }
            else if ($witch_status == STATUS_VERHEIRATET || $witch_status == STATUS_ABGESCHLOSSEN)
            {
                output("`c`i`&".$p1['name']."`& und ".$p2['name']."`& haben gerade geheiratet! Herzlichen Glückwunsch!");
            }
            output("`i`c`n`n");
        }

        viewcommentary("witch","Leise sprechen:",25,"raunt");

        if ($witch >= 2)
        {
            addnav("Hexen");
            addnav("Tor zur Zwischenwelt","tempel.php?op=darkdimension");
            if (getsetting('witch_witch_id',0) == $session['user']['acctid'])
            {
                addnav("Aktionen");
                if ($witch_status == STATUS_START)
                {
                    //addnav("Bannkreis errichten","tempel.php?op=lockroom");
                    addnav("`bVerheiraten`b","tempel.php?op=hochz_ok&heirat=1");
                    addnav("+?Verheiraten+Segnen","tempel.php?op=hochz_ok&heirat=1&segen=1");
                    addnav("Hochzeit abbrechen","tempel.php?op=hochz_ende&status=0",false,false,false,false);
                }
                else if ($witch_status == STATUS_VERHEIRATET)
                {
                    addnav("`bZeremonie abschließen`b","tempel.php?op=hochz_ende&status=".STATUS_ABGESCHLOSSEN);
                }
            }

        }
        else
        {
            addnav("Mystisches");
            addnav("Tor zur Zwischenwelt","tempel.php?op=darkdimension");
        }

        addnav("Waldlichtung");
        addnav("x?Liste der Hexen","tempel.php?op=witch_list");
        addnav("Regeln");
        addnav("R?Die Regeln der Hexen","tempel.php?op=witchrules");
        if ($session['user']['charisma']==999 && SCHNELLHOCHZ_ERLAUBT)
        {
            addnav("Schnellhochzeit (".SCHNELLHOCHZ_KOSTEN." Gold)","tempel.php?op=hochz_schnell");
        }


        addnav("Verschiedenes");
        addnav("Zurück in den Wald","forest.php");
        break;
    }

    case 'serve':
    { //Arbeit als Tempeldiener

        $sql = 'SELECT temple_servant FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
        $res = db_query($sql);
        $info = db_fetch_assoc($res);
        $info['daysinjail'] = $session['user']['daysinjail'];

        output('`&Eifrig machst du dich auf, deinen Pflichten als Tempeldiener nachzukommen.');

        if($session['user']['turns'] < TEMPLE_SERVANT_TURNS) {
            output('`nDoch leider bist du schon zu erschöpft dafür!');
        }
        else if($info['temple_servant'] >= 20) {
            output('`nDoch dann denkst du dir, dass du heute schon genug geschuftet hast und kehrst wieder um.');
        }
        else {
            $session['user']['turns'] -= TEMPLE_SERVANT_TURNS;
            $info['temple_servant'] *= 20; // harte Arbeit markieren

            if($_GET['what'] == 'kiss') {

                $sql = 'SELECT name,acctid,sex FROM accounts WHERE profession='.PROF_PRIEST.' OR profession='.PROF_PRIEST_HEAD.' ORDER BY RAND() LIMIT 1';
                $res = db_query($sql);

                if(db_num_rows($res)) {
                    $acc = db_fetch_assoc($res);

                    output('`n`&Eilfertig lässt du dich auf die Knie herab und beginnst, die Schuhe von Priester'.($acc['sex'] ? 'in':'').' '.$acc['name'].'`& auf Hochglanz zu bringen! ');

                    if(e_rand(1,3) == 1) {
                        output( ($acc['sex'] ? 'Sie':'Er').' ist mit Sicherheit zufrieden und gewährt dir zusätzliche Erlösung..');
                        if(e_rand(1,2) == 1) {
                            systemmail($acc['acctid'],'`VGute Arbeit des Tempeldieners!',$session['user']['name'].'`V hat deine Schuhe wirklich perfekt sauber gel.. geputzt! Ausgezeichnete Arbeit!');
                        }
                        $lose = 2;
                    }
                    else {
                        output( ($acc['sex'] ? 'Sie':'Er').' scheint allerdings etwas unzufrieden mit deiner Putzleistung zu sein.. das musst du noch üben!');
                        $lose = 1;
                    }

                }

            }
            else {        // Kehren
                output('`n`&Nach Stunden mühsamer Arbeit ist alles blitzblank. Die Priester werden sicher zufrieden sein!`n');
                $lose = 1;
            }

            $info['daysinjail']-=$lose;

            $sql = 'UPDATE account_extra_info SET temple_servant='.$info['temple_servant'].' WHERE acctid='.$session['user']['acctid'];
            db_query($sql);

            $session['user']['daysinjail'] = $info['daysinjail'];

            output('`n`&Du verlierst '.TEMPLE_SERVANT_TURNS.' Waldkämpfe und dein Strafregister vermindert sich um '.$lose.' Tag'.($lose > 1 ? 'e' : '').'! Es verbleiben '.($info['daysinjail']).' Tage. Noch genug zu tun..');
        }

        addnav('Zurück zum Tempel','tempel.php');

        break;
    }

    case 'servant_apply':
    { //Bewerbung als Tempeldiener

        $sql = 'SELECT temple_servant FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
        $res = db_query($sql);
        $info = db_fetch_assoc($res);

        $info['daysinjail'] = $session['user']['daysinjail'];

        $allowed = true;

        if($info['temple_servant'] > 0) {

            output('`&Die Priester wollen dich nicht schon wieder im Tempel sehen! Sie erklären dir, dass
										du noch mindestens '.$info['temple_servant'].' Sonnenumläufe auf eine neuerliche Gelegenheit
										warten musst.');
            $allowed = false;

        }

        if($session['user']['profession'] != 0) {
            $allowed = false;
        }

        if($info['daysinjail'] < TEMPLE_SERVANT_MINDAYS) {
            $allowed = false;
            output('`&Deine Sünden sind wohl nicht ausreichend.. auf jeden Fall weigern sich die Priester hartnäckig, dich als Tempeldiener anzunehmen!');
        }

        if($allowed) {

            $sql = 'SELECT acctid FROM accounts WHERE profession='.PROF_TEMPLE_SERVANT;
            $res = db_query($sql);

            if(db_num_rows($res) > TEMPLE_SERVANT_MAX) {
                $allowed = false;
                output('`&Leider, so erfährst du, gibt es bereits zu viele Tempeldiener. Versuch es später noch einmal!');
            }

        }

        if($allowed) {

            output('`&Die Priester begrüßen dich als neuen Tempeldiener und überreichen dir dein Gewand, das du die nächsten Tage bei deiner harten Arbeit tragen wirst. Nicht sehr eindrucksvoll, sicher, aber nur so vergeben dir die Götter einen Teil deiner Sünden..`nEs versteht sich wohl von selbst, dass du als Tempeldiener keinerlei Straftaten begehen darfst!');

            $session['user']['profession'] = PROF_TEMPLE_SERVANT;
            addnews($session['user']['name'].'`8 wird nun einige Zeit als Tempeldiener ehrliche Arbeit leisten.');
            $sql = 'UPDATE account_extra_info SET temple_servant=1 WHERE acctid='.$session['user']['acctid'];
            db_query($sql);
        }

        addnav('Zum Tempel','tempel.php');

        break;
    }

    case 'servant_stop':
    { //Dienst als Tempeldiener beenden

        $sql = 'SELECT name FROM accounts WHERE acctid='.(int)$_GET['id'];
        $acc = db_fetch_assoc(db_query($sql));

        user_update(
            array
            (
                'profession'=>0
            ),
            (int)$_GET['id']
        );

        $sql = 'UPDATE account_extra_info SET temple_servant = 20 WHERE acctid='.(int)$_GET['id'];
        db_query($sql);

        systemmail($_GET['id'],'`4Entlassung!',$session['user']['name'].'`4 hat dich aus deinem Amt als Tempeldiener entlassen!');

        $sql = 'INSERT INTO news SET newstext = "'.db_real_escape_string($acc['name']).'`8s Zeit als Tempeldiener ist Vergangenheit.",newsdate=NOW(),accountid='.$_GET['id'];
        db_query($sql);

        redirect('tempel.php?op=servant_list');
        break;
    }

    case 'servant_list':
    { //Liste der Tempeldiener

        if(!$_GET['public'] && $priest>1) {
            show_servant_list(true);
            addnav('Zurück zum Allerheiligsten','tempel.php?op=secret');
        }
        else {
            show_servant_list();
        }

        addnav('Zurück zum Tempel','tempel.php');

        break;
    }

    case 'secret':
    { //das Allerheiligste
        output("`&Du schlüpfst durch die versteckte Pforte in den prachtvollen, heiligsten Bereich des Tempels. Nur Priester haben hier Zutritt.`n`n");

        require_once(LIB_PATH.'board.lib.php');
        output('`0`c');
        $int_pollrights = (($session['user']['ddl_rank'] == PROF_DDL_COLONEL) ? 2 : 1);
        if(poll_view('temple_secret',$int_pollrights,$int_pollrights))
        {
            output('`n`^~~~~~~~~`0`n`n',true);
        }
        output('`c');

        viewcommentary("temple_secret","Sprechen:",25,"spricht");

        addnav("Registratur");

        addnav("P?Liste der Priester","tempel.php?op=priest_list_admin");
        addnav("l?Liste der Verlobten","tempel.php?op=flirt_list");
        addnav("h?Liste der Verheirateten","tempel.php?op=married_list");
        addnav("S?Liste der Seth / Violetopfer","tempel.php?op=married_list_npc");
        addnav("T?Liste der Tempeldiener","tempel.php?op=servant_list");
        addnav("B?Zum schwarzen Brett","tempel.php?op=board");
        addnav("y?Systemmeldungen","tempel.php?op=sysboard");
        addnav("W?Tor zur Wirklichkeit","tempel.php?op=priest_ooc");
        addnav("R?Die goldenen Regeln der Priester","tempel.php?op=rules");
        if ($session['user']['profession'] == PROF_PRIEST_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG))
        {
            addnav("---");
            addnav("Massenmail","tempel.php?op=massmail");
            addnav("Umfrage erstellen","tempel.php?op=poll");
        }

        addnav("Aktionen");

        addnav("Flüche / Segen","tempel.php?op=fluch_liste_auswahl");
        addnav("Verfluchen / Segnen","tempel.php?op=fluch");
        if(getsetting("temple_status",0) == 0 || getsetting("temple_status",0) == STATUS_ABGESCHLOSSEN)
        {
            addnav("!?Aufräumen!","tempel.php?op=sauber");
        }

        if ($session['user']['profession'] == PROF_PRIEST_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG))
        {
            addnav("Allerheiligstes Aufräumen!","tempel.php?op=sauber&what=inner_sanctum",false,false,false,false,'Allerheiligstes wirklich aufräumen?');
            if(getsetting("temple_status",0) != 0 && getsetting("temple_status",0) != STATUS_ABGESCHLOSSEN) {
                addnav('Hochzeit abbrechen','tempel.php?op=hochz_ende&status=0&msg=0',false,false,false,false,'Willst du wirklich die gerade laufende Zeremonie abbrechen?');
            }
        }

        if($session['user']['profession'] == PROF_PRIEST)
        {
            addnav("Kündigen","tempel.php?op=aufh",false,false,false,false);
        }

        //if(getsetting("temple_spenden",0) >= 50) {addnav("Wunder wirken!","tempel.php?op=wunder");}

        addnav("Verschiedenes");

        addnav("u?Zurück zum Vorraum","tempel.php");
        addnav("Zurück zum Stadtzentrum","village.php");
        break;
    }

    case 'darkdimension':
    { //Zwischenwelt
        if ($witch >= 2)
        {
            output("`b`c<span style=\"color:#89A84B\">Die Zwischenwelt`c`b`n");
            output('`aDu schlüpfst durch ein magisches Tor und betrittst die Zwischenwelt, einen Raum außerhalb der Realität und jeder Vorstellungskraft. Verschwommen kannst du die Waldlichtung ausserhalb dieses geschützten Kreises erkennen. Ein Hauch von Heiligkeit umgibt dich. Nur Hexen haben zu diesem besonderen Ort Zutritt.`n`n');

            require_once(LIB_PATH.'board.lib.php');
            output('`0`c');
            $int_pollrights = (($session['user']['ddl_rank'] == PROF_DDL_COLONEL) ? 2 : 1);
            if(poll_view('witch_secret',$int_pollrights,$int_pollrights))
            {
                output('`n`^~~~~~~~~`0`n`n',true);
            }
            output('`c');

            viewcommentary('witch_secret','Sprechen:',25,'spricht');

            addnav('Magischer Spiegel');
            addnav('x?Liste der Hexen','tempel.php?op=witch_list_admin');
            addnav('l?Liste der Verlobten','tempel.php?op=flirt_list');
            addnav('h?Liste der Verheirateten','tempel.php?op=married_list');
            addnav('S?Liste der Seth / Violetopfer','tempel.php?op=married_list_npc');
            addnav('T?Zur Trauerweide','tempel.php?op=witchboard');
            addnav('y?Systemmeldungen','tempel.php?op=sysboard');
            addnav('Dimensionssprung','tempel.php?op=witch_ooc');
            if ($session['user']['profession'] == PROF_WITCH_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG))
            {
                addnav('---');
                addnav('Massenmail','tempel.php?op=massmail2');
                addnav('Umfrage erstellen','tempel.php?op=poll2');
            }

            addnav('Aktionen');
            addnav('Flüche / Segen','tempel.php?op=fluch_liste_auswahl');
            addnav('Verfluchen / Segnen','tempel.php?op=fluch');
            addnav('!?Aufräumen!','tempel.php?op=sauber');

            if ($session['user']['profession'] == PROF_WITCH_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG))
            {
                addnav("Zwischenwelt Aufräumen!","tempel.php?op=sauber&what=inner_sanctum",false,false,false,false,'Zwischenwelt wirklich aufräumen?');
                if(getsetting("witch_status",0) != 0) {
                    addnav('Hochzeit abbrechen','tempel.php?op=hochz_ende&status=0&msg=0',false,false,false,false,'Willst du wirklich die gerade laufende Zeremonie abbrechen?');
                }
            }

            if ($session['user']['profession'] == PROF_WITCH)
            {
                addnav('Hexendasein beenden','tempel.php?op=aufh',false,false,false,false);
            }

            addnav('Verschiedenes');
            addnav('R?Zurück zum Ritualplatz','tempel.php?op=witches');
            addnav('Zurück in den Wald','forest.php');
        }
        else
        {
            output('`aDu schleichst durch die Büsche und Sträucher und näherst dich dem geheimen Ort, an dem sich die Hexen in eine andere Welt zurückzuziehen pflegen.`n
						Die Luft knistert und eine seltsame Spannung breitet sich in dir aus, als du dich dem Tor näherst. Doch da du nicht dem Zirkel angehörst bleibt dir der Durchgang versperrt und du kannst nur das Weite suchen, bevor man dich noch entdeckt.`0`n`n');
            addnav('Zurück zum Ritualplatz','tempel.php?op=witches');
        }
        break;
    }

    case 'priest_ooc':
    { //OOC-Raum der Priester
        output('`c`b`)Das Hauptquartier der Priester`b`c`n
				`&Nachdem dir eine bislang unbekannte Pforte im Allerheiligsten aufgefallen ist, hast du beschlossen einmal nachzuschauen, was sich dahinter befindet. Kaum hast du die Tür geöffnet, wird dein Körper von einem hellen Licht erfasst, welches dich dann auch umgehend in das innere zieht. Du staunst nicht schlecht, als dein Hintern plötzlich auf einem bequemen Schreibtischstuhl sitzt und vor deinen Augen ein mysteriöses, flackerndes etwas herumsteht. Ob das wohl der Sagenumwogene PC ist, von welchem viele erzählen, dass sie darin gefangen wären und von einem höheren Wesen gesteuert werden? Richtig und dieses höhere Wesen bist du, also tu nicht so als würdest du das nicht wissen und schreib in die komische Textzeile da unten, was du deinen Virtuellen Kollegen hinterlassen möchtest!
				`n`n');
        addnav("Ins Allerheiligste","tempel.php?op=secret");
        viewcommentary("temple_ooc","Sagen:",30,"sagt");
        break;
    }

    case 'witch_ooc':
    { //OOC-Raum der Hexen
        output('`c`b<span style=\"color:#89A84B\">Die Ritualkammer der Hexen</span>`b`c`n
				`aNachdem dir eine bislang unbekannte Dimension in der Zwischenwelt aufgefallen ist, hast du beschlossen einmal nachzuschauen, was sich dahinter befindet. Kaum hast du das Portal geöffnet, wird dein Körper von einem hellen Licht erfasst, welches dich dann auch umgehend in das Innere zieht. Du staunst nicht schlecht, als dein Hintern plötzlich auf einem bequemen Schreibtischstuhl sitzt und vor deinen Augen ein mysteriöses, flackerndes etwas herumsteht. Ob das wohl der Sagenumwogene PC ist, von welchem viele erzählen, dass sie darin gefangen wären und von einem höheren Wesen gesteuert werden? Richtig und dieses höhere Wesen bist du, also tu nicht so als würdest du das nicht wissen und schreib in die komische Textzeile da unten, was du deinen Virtuellen Kollegen hinterlassen möchtest!
				`n`n');
        addnav("Zur Zwischenwelt","tempel.php?op=darkdimension");
        viewcommentary("witch_ooc","Sagen:",30,"sagt");
        break;
    }

    case 'rules':
    { //Regeln der Priester
        output("`aFür die Ewigkeit bestimmt sind hier die Regeln der Priester festgehalten:`n`n");
        show_rules();
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':''));
        break;
    }

    case 'witchrules':
    { //Regeln der Hexen
        output("`aFür die Ewigkeit bestimmt sind hier die Regeln der Hexen festgehalten:`n`n");
        show_witchrules();
        addnav("Zurück","tempel.php?op=".($witch>0?'darkdimension':'witches'));
        break;
    }

    case 'priest_list_admin':
    case 'priest_list':
    { //Liste der Priester
        output("`&In Stein gemeißelt erkennst du eine Liste aller Priester/innen:`n`n");
        show_priest_list($priest);

        if($session['user']['profession'] == 0)
        {
            addnav("Ich will Priester/in werden!","tempel.php?op=bewerben");
        }
        if($session['user']['profession'] == PROF_PRIEST_NEW)
        {
            addnav("Bewerbung zurückziehen","tempel.php?op=bewerben_abbr");
        }
        addnav('Zurück');
        if($priest>1)
        {
            addnav("Zum Allerheiligsten","tempel.php?op=secret");
        }
        addnav("Zum Tempel","tempel.php");
        break;
    }

    case 'witch_list_admin':
    case 'witch_list':
    { //Liste der Hexen
        output("`aAuf einer Schriftrolle befindet sich eine Liste aller Hexen:`n`n");
        show_witch_list($witch);

        if ($session['user']['profession'] == 0)
        {
            addnav("Ich will Hexe werden!","tempel.php?op=apply_witch");
        }
        if ($session['user']['profession'] == PROF_WITCH_NEW)
        {
            addnav("Bewerbung zurückziehen","tempel.php?op=bewerben_abbr");
        }
        addnav('Zurück');
        if($witch>1)
        {
            addnav("Zur Zwischenwelt","tempel.php?op=darkdimension");
        }
        addnav("Zur Waldlichtung","tempel.php?op=witches");
        break;
    }

    case 'bewerben':
    { //als Priester bewerben

        $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_PRIEST." OR profession=".PROF_PRIEST_HEAD.")";
        $res = db_query($sql);
        $p = db_fetch_assoc($res);

        if($session['user']['dragonkills'] < getsetting('priestreq',15)) {
            output("`&Du musst mindestens ".getsetting('priestreq',15)." Heldentaten vollbracht haben, um Priester werden zu können!");
            addnav("Zurück","tempel.php?op=priest_list");
        }
        elseif($p['anzahl'] >= getsetting("numberofpriests",3)) {
            output("`&Es gibt bereits ".$p['anzahl']." Priester. Mehr werden zur Zeit nicht benötigt!");
            addnav("Zurück","tempel.php?op=priest_list");
        }
        else {
            output("`&Nach reiflicher Überlegung beschließt du, das Amt des Priesters anzustreben. Weiterhin gelten für den Priesterstand die folgenden, unverletzbaren Regeln:`n`n");
            show_rules();
            output("`n`&Als Priester wärst du daran unbedingt gebunden!`nSteht dein Entschluss immer noch fest?");
            addnav("Ja!","tempel.php?op=bewerben_ok&id=".$session['user']['acctid']);
            addnav("Nein, zurück!","tempel.php?op=priest_list");
        }
        break;
    }

    case 'bewerben_ok':
    { //Bewerbung als Priester abschließen
        $session['user']['profession'] = PROF_PRIEST_NEW;

        $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_PRIEST_HEAD." ORDER BY loggedin DESC, RAND() LIMIT 1";
        $res = db_query($sql);
        if(db_num_rows($res)) {
            $p=db_fetch_assoc($res);
            systemmail($p['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& hat sich für den Posten des Priesters beworben. Du solltest seine Bewerbung überprüfen und ihn gegegebenfalls einstellen.");
        }

        output("`&Du reichst deine Bewerbung bei den Priestern ein, die diese gewissenhaft prüfen und Dir dann Bescheid geben werden!`n");
        addnav("Zurück","tempel.php?op=priest_list");
        break;
    }

    case 'bewerben_abbr':
    { //Abbruch Bewerbung
        $session['user']['profession'] = 0;

        output("`&Du hast deine Bewerbung erfolgreich zurückgenommen!`n");
        addnav("Zurück","tempel.php?op=".($priest>0?'priest_list':'witch_list'));
        break;
    }

    case 'apply_witch':
    { //als Hexe bewerben

        $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_WITCH." OR profession=".PROF_WITCH_HEAD.")";
        $res = db_query($sql);
        $p = db_fetch_assoc($res);

        if ($session['user']['dragonkills'] < getsetting('priestreq',15))
        {
            output("`aDu musst mindestens ".getsetting('priestreq',15)." Heldentaten vollbracht haben, um Hexer werden zu können!");
            addnav("Zurück","tempel.php?op=witch_list");
        }
        else if ($p['anzahl'] >= getsetting("numberofwitches",3))
        {
            output("`aEs gibt bereits ".$p['anzahl']." Hexen. Mehr werden zur Zeit nicht benötigt!");
            addnav("Zurück","tempel.php?op=witch_list");
        }
        else
        {
            output("`aNach reiflicher Überlegung beschließt du, ein Hexer werden zu wollen. Weiterhin gelten für den Hexenzirkel die folgenden, unverletzbaren Regeln:`n`n");
            show_witchrules();
            output("`n`aAls Hexer wärst du daran unbedingt gebunden!`nSteht dein Entschluss immer noch fest?");
            addnav("Ja!","tempel.php?op=apply_witch_ok&id=".$session['user']['acctid']);
            addnav("Nein, zurück!","tempel.php?op=witch_list");
        }
        break;
    }

    case 'apply_witch_ok':
    { //Bewerbung als Hexe abschließen
        $session['user']['profession'] = PROF_WITCH_NEW;

        $sql = "SELECT acctid FROM accounts WHERE profession=".PROF_WITCH_HEAD." ORDER BY loggedin DESC, RAND() LIMIT 1";
        $res = db_query($sql);
        if (db_num_rows($res))
        {
            $p=db_fetch_assoc($res);
            systemmail($p['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& würde gern dem Zirkel beitreten. Du solltest die Bewerbung überprüfen und entsprechend handeln.");
        }

        output("`aDu reichst deine Bewerbung bei den Hexen ein, die diese gewissenhaft prüfen und Dir dann Bescheid geben werden!`n");
        addnav("Zurück","tempel.php?op=witch_list");
        break;
    }

    case 'aufh':
    { //Amt Kündigen
        output("
						`&Du überlegst noch einmal, ob es wirklich dein Wunsch ist
						dein Amt als " . ($priest>0? 'Priester' : 'Hexe') . " nun aufzugeben.`n
						Bist du dir sicher?
				");
        addnav("Kündigen!","tempel.php?op=aufh_best",false,false,false,false,'Wirklich aufhören?');
        addnav("Nicht doch!","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;
    }

    case 'aufh_best':
    { //Kündigung abschließen
        $session['user']['profession'] = 0;

        $sql = "
						SELECT
								`acctid`
						FROM
								`accounts`
						WHERE
								`profession`        = '" . ($witch>0? PROF_WITCH_HEAD : PROF_PRIEST_HEAD) . "'
						ORDER BY
								`loggedin` DESC
								,RAND()
						LIMIT
								1
				";
        $res = db_query($sql);
        if(db_num_rows($res))
        {
            $p = db_fetch_assoc($res);
            systemmail($p['acctid'],"`&Kündigung!`0","`&".$session['user']['name']."`& hat beschlossen sein Amt aufzugeben.");
        }

        if($priest>0)
        {
            addnews($session['user']['name']." `&hat ".($session['user']['sex'] ? 'ihr':'sein')." Priester-Amt niedergelegt!");
            addhistory('`2Würden des Priesteramtes niedergelegt');
        }
        else
        {
            addnews($session['user']['name']." `&ist seit dem heutigen Tage nicht mehr im Zirkel der Hexen!");
            addhistory('`2Aufgabe des Hexendaseins');
        }

        output("`&Etwas wehmütig legst du die Insignien ab und bist ab sofort wieder ein normaler Bürger!`n");
        addnav("Zurück","tempel.php?op=".($priest>0?'':'witches'));
        addnav("Zum Stadtzentrum","village.php");
        break;
    }

    case 'entlassen':
    { //Priester/Hexe rauswerfen
        if ($priest>0)
        {
            output('Diesen Priester wirklich entlassen?`n');
        }
        else
        {
            output('Diese Hexe wirklich entlassen?`n');
        }
        addnav("Ja!","tempel.php?op=entlassen_ok&id=".$_GET['id']);
        addnav("Zurück","tempel.php?op=".($priest>0?'priest_list':'witch_list'));
        break;
    }

    case 'entlassen_ok':
    { //Entlassung abschließen
        $pid = (int)$_GET['id'];

        // Für Debugzwecke
        if($session['user']['acctid'] == $pid) {$session['user']['profession'] = 0;}

        user_update(
            array
            (
                'profession'=>0
            ),
            $pid
        );

        $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
        $res = db_query($sql);
        $p = db_fetch_assoc($res);

        if($priest>0)
        {
            systemmail($pid,"Du wurdest entlassen!",$session['user']['name']."`& hat dich aus dem Priesterstand entlassen.");

            $sql = "INSERT INTO news SET newstext = '".db_real_escape_string($p['name'])." `&wurde heute aus der ehrenvollen Gemeinschaft der Priester entlassen!',newsdate=NOW(),accountid=".$pid;
            db_query($sql);

            addhistory('`$Entlassung aus dem Priesteramt',1,$pid);

            output("Priester wurde entlassen!`n");
            addnav("Zurück","tempel.php?op=priest_list_admin");
        }
        else
        {
            systemmail($pid,"Du wurdest verstoßen!",$session['user']['name']."`& hat dich aus dem Hexenzirkel verstoßen.");

            $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
            $res = db_query($sql);
            $p = db_fetch_assoc($res);

            $sql = "INSERT INTO news SET newstext = '".db_real_escape_string($p['name'])." `&wurde heute aus dem Hexenzirkel entlassen!',newsdate=NOW(),accountid=".$pid;
            db_query($sql);

            addhistory('`$Entlassung aus dem Hexenzirkel',1,$pid);

            output("Hexe wurde entlassen!`n");
            addnav("Zurück","tempel.php?op=witch_list_admin");
        }
        break;
    }

    case 'aufnehmen':
    { //einen Bewerber aufnehmen
        $pid = (int)$_GET['id'];
        $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
        $res = db_query($sql);
        $p = db_fetch_assoc($res);

        if($priest>0)
        {
            $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_PRIEST." OR profession=".PROF_PRIEST_HEAD.")";
            $max_anzahl=getsetting("numberofpriests",3);
            $backlink="?op=priest_list_admin";
            $int_amt=PROF_PRIEST;
            $str_amtname=' Priester';
            $mailtext=$session['user']['name']."`& hat deine Bewerbung zur Aufnahme in die Priesterkaste angenommen. Damit bist du vom heutigen Tage an offiziell Mitglied dieser ehrenwerten Kaste!";
            $newstext=db_real_escape_string($p['name'])." `&wurde heute offiziell in die ehrenvolle Gemeinschaft der Priester aufgenommen!";
        }
        else
        {
            $sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_WITCH." OR profession=".PROF_WITCH_HEAD.")";
            $max_anzahl=getsetting("numberofwitches",3);
            $backlink="?op=witch_list_admin";
            $int_amt=PROF_WITCH;
            $str_amtname=' Hexer';
            $mailtext=$session['user']['name']."`& hat dich in den Zirkel eingeweiht. Damit bist du vom heutigen Tage an offiziell Mitglied dieser Gemeinschaft!";
            $newstext=db_real_escape_string($p['name'])." `&wurde heute in den Hexenzirkel initiiert!";
        }
        $res = db_query($sql);
        $p = db_fetch_assoc($res);

        if($p['anzahl'] >= $max_anzahl)
        {
            output("Es gibt bereits ".$p['anzahl'].$str_amtname."! Mehr sind zur Zeit nicht möglich.");
            addnav("Zurück",'tempel.php'.$backlink);
        }
        else {

            // Für Debugzwecke
            if($session['user']['acctid'] == $pid) {$session['user']['profession'] = $int_amt;}

            user_update(
                array
                (
                    'profession'=>$int_amt
                ),
                $pid
            );

            $sql = "SELECT name FROM accounts WHERE acctid=".$pid;
            $res = db_query($sql);
            $p = db_fetch_assoc($res);

            systemmail($pid,"Du wurdest aufgenommen!",$mailtext);

            $sql = "INSERT INTO news SET newstext = '".$newstext."',newsdate=NOW(),accountid=".$pid;
            db_query($sql);

            addhistory('`2Aufnahme als '.$str_amtname,1,$pid);

            addnav("Willkommen!","tempel.php".$backlink);

            output("Das neue Mitglied ist jetzt aufgenommen!");
        }
        break;
    }

    case 'ablehnen':
    { //Bewerber ablehnen
        $pid = (int)$_GET['id'];

        // Für Debugzwecke
        if($session['user']['acctid'] == $pid)
        {
            $session['user']['profession'] = 0;
        }

        if($_POST['message']!='')
        {
            user_update(
                array
                (
                    'profession'=>0
                ),
                $pid
            );
            systemmail($pid,"Deine Bewerbung wurde abgelehnt!",$_POST['message']);
            output('Eine weitere Bewerbung findet ihren Platz in Ablage P.`n`n');
        }
        else
        {
            output('<form action="tempel.php?op=ablehnen&id='.$pid.'" method="post">
						Dem Bewerber wird dieser Bescheid zugesandt:
						`n`n<textarea name="message" class="input" cols=70 rows=4>'.$profs[$session['user']['profession']][$session['user']['sex']].' '.$session['user']['login'].' hat deine Bewerbung als '.($priest>0?'Priester':'Hexe').' abgelehnt.</textarea>
						`n<input type="submit" id="submit" class="button" value="Mitteilung senden">
						</form>`n');
            addnav('','tempel.php?op=ablehnen&id='.$pid);
        }

        addnav('Zurück','tempel.php'.($priest>0?'?op=priest_list_admin':'?op=witch_list_admin'));
        break;
    }

    case 'hohep':
    { //Beförderung zum Führungsmitglied
        $pid = (int)$_GET['id'];

        if($priest>0)
        {
            $int_amtid=PROF_PRIEST_HEAD;
            $str_amtname=' Hohepriester';
            $str_backlink='?op=priest_list_admin';
        }
        else
        {
            $int_amtid=PROF_WITCH_HEAD;
            $str_amtname=' Hexenmeister';
            $str_backlink='?op=witch_list_admin';
        }

        // Für Debugzwecke
        if($session['user']['acctid'] == $pid)
        {
            $session['user']['profession'] = $int_amtid;
        }

        user_update(
            array
            (
                'profession'=>$int_amtid
            ),
            $pid
        );

        systemmail($pid,"Du wurdest befördert!",$session['user']['name']."`& hat dich zum ".$str_amtname." ernannt.");

        addhistory('`2Weihe zum '.$str_amtname,1,$pid);

        addnav("Hallo Chef!","tempel.php".$backlink);
        break;
    }

    case 'hohep_deg':
    { //Führungsmitglied degradieren
        $pid = (int)$_GET['id'];

        if($priest>0)
        {
            $int_amtid=PROF_PRIEST;
            $str_amtold=' Hohepriester';
            $str_amtname=' Priester';
            $str_backlink='?op=priest_list_admin';
        }
        else
        {
            $int_amtid=PROF_WITCH;
            $str_amtold=' Hohepriester';
            $str_amtname=' Hexer';
            $str_backlink='?op=witch_list_admin';
        }

        // Für Debugzwecke
        if($session['user']['acctid'] == $pid)
        {
            $session['user']['profession'] = $int_amtid;
        }

        user_update(
            array
            (
                'profession'=>$int_amtid
            ),
            $pid
        );

        systemmail($pid,"Du wurdest degradiert!",$session['user']['name']."`& hat dir den Rang ".$str_amtold." entzogen.");

        addhistory('`2Herabsetzung zum normalen '.$str_amtname,1,$pid);

        addnav("Das wars dann!","tempel.php".$backlink);
        break;
    }

    case 'lockroom':
    { //
        output("`anoch ohne Funktion");
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;
    }

    case 'sauber':
    { //Aufräumen Sicherheitsabfrage
        output('`0Du denkst dir, dass es mal wieder an der Zeit wäre '.($priest>0?'den Tempel':'die Lichtung').' von den Ereignissen der Vergangenheit zu bereinigen, um das nächste Ritual vorbereiten zu können. Alle Ereignisse geraten damit in Vergessenheit.
				`nIst es das was du willst?
				`n`n`0Diese Funktion verschiebt die Kommentare im öffentlichen Teil des Tempels in einen unsichtbaren, nur von Admins zugänglichen Raum und können auch nur von ihnen zurückgeholt werden!
				`n`qVorsicht: Benutzt man diese Funktion während einer Hochzeit, wird diese abgebrochen!');
        addnav('Ja, aufräumen!','tempel.php?op=sauber_ok&what='.$_GET['what']);
        addnav('Nein, zurück','tempel.php?op='.($priest>0?'secret':'darkdimension'));
        break;
    }

    case 'sauber_ok':
    { // Raum freigeben und Kommentare entfernen
        if($_GET['what'] == 'inner_sanctum')
        {
            $str_section = ($priest>0?'temple':'witch');
            // Sicherung
            $sql = "UPDATE commentary SET section='".$str_section."_secret_s' WHERE section='".$str_section."_secret'";
            db_query($sql);
            // Sicherung Ende
        }
        else
        {
            if($priest>0)
            {
                savesetting('temple_id1','0');
                savesetting('temple_id2','0');
                savesetting('temple_status','0');
                savesetting('temple_priest_name',' ');
                savesetting('temple_priest_id','0');

                // Sicherung
                $sql = "UPDATE commentary SET section='temple_s' WHERE section='temple'";
                db_query($sql);
                // Sicherung Ende
            }
            else
            {
                savesetting('witch_id1','0');
                savesetting('witch_id2','0');
                savesetting('witch_status','0');
                savesetting('witch_witch_name',' ');
                savesetting('witch_witch_id','0');

                // Sicherung
                $sql = "UPDATE commentary SET section='witch_s' WHERE section='witch'";
                db_query($sql);
                // Sicherung Ende
            }
        }

        redirect("tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;
    }

    case 'hochz':
    { //prüfen ob aktuell eine Hochzeit stattfindet

        if($_GET['id1']==$session['user']['acctid'] || $_GET['id1']==$session['user']['acctid'])
        {
            output("Du kannst dich nicht selbst verheiraten! Frage einen anderen Priester/Hexer, ob er das für dich übernimmt.");
            addnav("Zurück","tempel.php?op=married_list");
        }
        elseif($priest>0)
        {
            if(getsetting("temple_status",0) != 0 && getsetting("temple_status",0) != STATUS_ABGESCHLOSSEN) {
                output("Gerade jetzt findet eine Hochzeit statt! Du willst doch da nicht stören?");
                addnav("Zurück","tempel.php?op=married_list");
            }
            else {
                if($_GET['id1'] && $_GET['id2']) {
                    savesetting("temple_id1",(int)$_GET['id1']);        // Partner 1
                    savesetting("temple_id2",(int)$_GET['id2']);        // Partner 2
                }

                savesetting("temple_status",STATUS_START);        // Status
                savesetting("temple_priest_id",$session['user']['acctid']);

                output("Du eröffnest die Zeremonie!");

                insertcommentary($session['user']['acctid'],": `geröffnet die Zeremonie!",'temple');

                addnav("Los gehts!","tempel.php");
            }
        }
        elseif($witch>0)
        {
            if (getsetting("witch_status",0) != 0 && getsetting("witch_status",0) != STATUS_ABGESCHLOSSEN)
            {
                output("Gerade jetzt findet ein Hochzeitsritual statt! Du willst doch da nicht stören?");
                addnav("Zurück","tempel.php?op=married_list");
            }
            else
            {
                if ($_GET['id1'] && $_GET['id2'])
                {
                    savesetting("witch_id1",(int)$_GET['id1']); // Partner 1
                    savesetting("witch_id2",(int)$_GET['id2']); // Partner 2
                }
                savesetting("witch_status",STATUS_START); // Status
                savesetting("witch_witch_id",$session['user']['acctid']);

                output("Du eröffnest die Zeremonie!");

                insertcommentary($session['user']['acctid'],": `geröffnet die Zeremonie!",'witch');

                addnav("Los gehts!","tempel.php?op=witches");
            }
        }
        break;
    }

    case 'hochz_ok':
    { //beide als Verheiratet setzen

        if($priest>0)
        {
            $p1['acctid']=intval(getsetting('temple_id1',0));
            $p2['acctid']=intval(getsetting('temple_id2',0));
            $int_marry_leader=getsetting('temple_priest_id',0);
            $str_backlink='';
            $buff_item='tmplsgn';
        }
        else
        {
            $p1['acctid']=intval(getsetting('witch_id1',0));
            $p2['acctid']=intval(getsetting('witch_id2',0));
            $int_marry_leader=getsetting('witch_witch_id',0);
            $str_backlink='?op=witches';
            $buff_item='hxsgn';
        }

        if($p1['acctid'] == $int_marry_leader || $p2['acctid'] == $int_marry_leader)
        {
            output("Du kannst dich nicht selbst verheiraten! Frage einen anderen Priester/Hexer, ob er das für dich übernimmt.");
        }
        else
        {
            $sql = "SELECT acctid,name,guildid,guildfunc FROM accounts
										WHERE acctid=".$p1['acctid']." OR acctid=".$p2['acctid']." ORDER BY sex";
            $res = db_query($sql);
            $p1 = db_fetch_assoc($res);
            $p2 = db_fetch_assoc($res);

            // Hier evtl. LOCK TABLE...

            user_update(
                array
                (
                    'charisma'=>4294967295,
                    'charm'=>array('sql'=>true,'value'=>'charm+1'),
                    'donation'=>array('sql'=>true,'value'=>'donation+1'),
                    'gems'=>array('sql'=>true,'value'=>'gems+1'),
                    'where'=>'acctid='.$p1['acctid'].' OR acctid='.$p2['acctid']
                )
            );

            $sql = "INSERT INTO news SET newstext = '`%".db_real_escape_string($p1['name'])." `&und `%".db_real_escape_string($p2['name'])."`& haben heute feierlich den Bund der Ehe geschlossen!!!',newsdate=NOW(),accountid=".$p1['acctid'];
            db_query($sql);

            systemmail($p1['acctid'],"`&Verheiratet!`0","`& Du und `&".$p2['name']."`& habt im Rahmen einer feierlichen und wunderschönen Zeremonie im Tempel geheiratet!`nGlückwunsch!`nAls Geschenk erhält jeder von euch einen Edelstein.");
            systemmail($p2['acctid'],"`&Verheiratet!`0","`& Du und `&".$p1['name']."`& habt im Rahmen einer feierlichen und wunderschönen Zeremonie im Tempel geheiratet!`nGlückwunsch!`nAls Geschenk erhält jeder von euch einen Edelstein.");

            addhistory('`vHeirat mit '.$p1['name'],1,$p2['acctid']);
            addhistory('`vHeirat mit '.$p2['name'],1,$p1['acctid']);

            if($_GET['segen']>0)
            {
                item_add($p1['acctid'],$buff_item);
                item_add($p2['acctid'],$buff_item);
            }

            if($priest>0)
            {
                savesetting("temple_status",STATUS_VERHEIRATET);        // Status
                insertcommentary($session['user']['acctid'],": `gerklärt ".$p1['name']."`g und ".$p2['name']."`g offiziell zu Mann und Frau!",'temple');
            }
            else
            {
                savesetting("witch_status",STATUS_VERHEIRATET);        // Status
                insertcommentary($session['user']['acctid'],": `gerklärt ".$p1['name']."`g und ".$p2['name']."`g offiziell zu Mann und Frau!",'witch');
            }

            // Gildensystem
            require_once(LIB_PATH.'dg_funcs.lib.php');
            $state = 0;
            if( ($p1['guildid']  && $p1['guildfunc'] != DG_FUNC_APPLICANT) ) {
                $guild1 = &dg_load_guild($p1['guildid'],array('treaties','points'));
            }
            if( ($p2['guildid']  && $p2['guildfunc'] != DG_FUNC_APPLICANT) ) {
                $guild2 = &dg_load_guild($p2['guildid'],array('treaties','points'));
            }
            if($guild1 && $guild2) {$state = dg_get_treaty($guild2['treaties'][$p1['guildid']]);}

            $points = ($state == 1 ? $dg_points['wedding_friendly'] : ($state == 0 ? $dg_points['wedding_neutral'] : 0) );

            if($guild1) {$guild1['points'] += $points;}
            if($guild2) {$guild2['points'] += $points;}

            dg_save_guild();
            // END Gildensystem

        }

        redirect('tempel.php'.$str_backlink);
        break;
    }

    case 'hochz_ende':
    { //Hochzeit abschließen und Raum freigeben

        if($priest>0)
        {
            if(isset($_GET['msg'])) {
                insertcommentary(1,'/msg `8Eine göttliche Intervention beendet die Zeremonie!`0','temple');
                debuglog(' bricht im Tempel eine Hochzeit ab.');
            }
            else {
                insertcommentary($session['user']['acctid'],': '.($_GET['status']>0?'`gschließt':'`4bricht').' die Zeremonie ab.','temple');
            }
            savesetting("temple_status",$_GET['status']);
            savesetting("temple_priest_id","0");
            redirect('tempel.php');
        }
        else
        {
            if(isset($_GET['msg'])) {
                insertcommentary(1,'/msg `$Eine göttliche Intervention beendet die Zeremonie!`0','witch');
                debuglog(' bricht auf der Waldlichtung eine Hochzeit ab.');
            }
            else {
                insertcommentary($session['user']['acctid'],': '.($_GET['status']>0?'`gschließt':'`4bricht').' die Zeremonie ab.','witch');
            }
            savesetting("witch_status",$_GET['status']);
            savesetting("witch_witch_id","0");
            redirect('tempel.php?op=witches');
        }
        break;
    }

    case 'hochz_schnell':
    { //automatische Systemhochzeit (falls erlaubt)
        if($session['user']['gold'] < SCHNELLHOCHZ_KOSTEN)
        {
            output("`&Du verfügst leider nicht über genug Gold, weswegen die Priester deinen Antrag zurückweisen!");
        }
        else
        {
            output("`&Willst Du wirklich diesen Schritt gehen? Bedenke auch, dass eine Schnellhochzeit nicht die Vorteile einer priesterlichen Zeremonie bietet!");
            addnav("Ja, ich will!","tempel.php?op=hochz_schnell_ok");
        }
        addnav("Zum Tempel","tempel.php");
        break;
    }

    case 'hochz_schnell_ok':
    { //Schnellhochzeit durchführen
        $session['user']['gold'] -= SCHNELLHOCHZ_KOSTEN;

        $sql = "SELECT name,acctid FROM accounts
								WHERE acctid=".$session['user']['marriedto'];
        $res = db_query($sql);
        $p = db_fetch_assoc($res);

        user_update(
            array
            (
                'charisma'=>4294967295
            ),
            $p['acctid']
        );

        $session['user']['charisma'] = 4294967295;

        addnews("`%".$session['user']['name']." `&und `%".$p['name']."`& haben heute mehr oder weniger feierlich den Bund der Ehe geschlossen!!!");

        systemmail($session['user']['acctid'],"`&Verheiratet!`0","`& Du und `&".$p['name']."`& habt im Rahmen einer eiligen, kleinen Feier geheiratet!`nGlückwunsch!");
        systemmail($p['acctid'],"`&Verheiratet!`0","`& Du und `&".$session['user']['name']."`& habt im Rahmen einer eiligen, kleinen Feier geheiratet!`nGlückwunsch!");

        output("Du hast ".$p['name']."`0 geheiratet. Herzlichen Glückwunsch! Auch wenn die Zeremonie etwas lieblos war...");

        addnav("Zum Tempel","tempel.php");
        addnav("Zum Stadtzentrum","village.php");

        break;
    }

    case 'scheidung':
    { //Scheidung von Spieler/Seth/Violet

        if(!$_GET['npc'])
        { //2 Spieler
            $id1 = (int)$_GET['id1'];
            $id2 = (int)$_GET['id2'];

            $sql = "SELECT name,acctid FROM accounts
										WHERE acctid=".$id1." OR acctid=".$id2." ORDER BY sex";
            $res = db_query($sql);
            $p1 = db_fetch_assoc($res);
            $p2 = db_fetch_assoc($res);

            // Hier evtl. LOCK TABLE...

            user_update(
                array
                (
                    'charisma'=>0,
                    'marriedto'=>0,
                    'where'=>'acctid='.$id1.' OR acctid='.$id2
                )
            );

            $sql = "INSERT INTO news SET newstext = '`%".db_real_escape_string($p1['name'])." `&und `%".db_real_escape_string($p2['name'])."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!', newsdate=NOW(),accountid=".$p1['acctid'];
            db_query($sql);

            addhistory('`tScheidung von '.$p1['name'],1,$p2['acctid']);
            addhistory('`tScheidung von '.$p2['name'],1,$p1['acctid']);

            systemmail($p1['acctid'],"`&Scheidung!`0","`& Du und `&".$p2['name']."`& habt Euch getrennt und Eure Ehe anulliert!");
            systemmail($p2['acctid'],"`&Scheidung!`0","`& Du und `&".$p1['name']."`& habt Euch getrennt und Eure Ehe anulliert!");

            insertcommentary($session['user']['acctid'],": `gerklärt ".$p1['name']."`g und ".$p2['name']."`g als geschieden!",$witch>0?'witch':'temple');
        }
        else
        { //Seth/Violet
            $id = (int)$_GET['id1'];

            $sql = "SELECT name,acctid,sex FROM accounts
										WHERE acctid=".$id;
            $res = db_query($sql);
            $p = db_fetch_assoc($res);

            user_update(
                array
                (
                    'charisma'=>0,
                    'marriedto'=>0,
                ),
                $id
            );

            $npc_name = (($p['sex']==0)?"Violet":"Seth");

            $sql = "INSERT INTO news SET newstext = '`%".db_real_escape_string($p['name'])." `&und `%".$npc_name."`& haben sich heute getrennt und ihre Ehe für nichtig erklärt!', newsdate=NOW(),accountid=".$p['acctid'];
            db_query($sql);

            systemmail($p['acctid'],"`&Scheidung!`0","`& Du und `&".$npc_name."`& habt Euch getrennt und Eure Ehe anulliert!");
            insertcommentary($session['user']['acctid'],": `gerklärt ".$p['name']."`g und ".$npc_name."`g als geschieden!",$witch>0?'witch':'temple');

        }

        output("Erfolgreich geschieden!");
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));

        break;
    }

    case 'trennung':
    { //Verlobung auflösen

        $id1 = (int)$_GET['id1'];
        $id2 = (int)$_GET['id2'];

        $sql = "SELECT name,acctid FROM accounts
								WHERE acctid=".$id1." OR acctid=".$id2." ORDER BY sex";
        $res = db_query($sql);
        $p1 = db_fetch_assoc($res);
        $p2 = db_fetch_assoc($res);

        user_update(
            array
            (
                'charisma'=>0,
                'marriedto'=>0,
                'where'=>'acctid='.$id1.' OR acctid='.$id2
            )
        );

        addhistory('`tVerlobung mit '.$p1['name'].' aufgelöst',1,$p2['acctid']);
        addhistory('`tVerlobung mit '.$p2['name'].' aufgelöst',1,$p1['acctid']);

        systemmail($p1['acctid'],"`&Trennung!`0","`& Du und `&".$p2['name']."`& habt Euch getrennt und Eure Verlobung anulliert!");
        systemmail($p2['acctid'],"`&Trennung!`0","`& Du und `&".$p1['name']."`& habt Euch getrennt und Eure Verlobung anulliert!");

        insertcommentary($session['user']['acctid'],": `gerklärt ".$p1['name']."`gs und ".$p2['name']."`gs Verlobung als aufgelöst!",$witch>0?'witch':'temple');

        output("Verlobung gelöst!");
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;
    }

    case 'flirt_list':
        show_flirt_list(max($priest,$witch));
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;

    case 'married_list':
        show_flirt_list(max($priest,$witch),1);
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;

    case 'married_list_npc':
        show_flirt_list(max($priest,$witch),2);
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;

    case 'married_list_public':
        show_flirt_list(0,1);
        addnav("Zurück","tempel.php");
        break;

//--------------------- Ende Heiratsbereich ----------------------

    case 'opfer':
    { //den Göttern opfern
        output("`&Hier kannst Du in Meditation versinken, die Götter um ein Geschenk bitten und dafür ein Opfer bringen. Sie werden dir entweder permanente Lebenskraft, Edelsteine oder Gold abnehmen - je nachdem, wonach ihnen der Sinn steht.`nWie viele Runden willst Du meditieren?");

        addnav("Wie lange?");
        if($session['user']['turns'] >= 2) addnav("... 2 Runden","tempel.php?op=opfer_ok&runden=2");
        if($session['user']['turns'] >= 5) addnav("... 5 Runden","tempel.php?op=opfer_ok&runden=5");
        if($session['user']['turns'] >= 10) addnav("... 10 Runden","tempel.php?op=opfer_ok&runden=10");
        if($session['user']['castleturns']) addnav('... 1 Schlossrunde','tempel.php?op=gardenmaze');
        addnav("Weg hier!");
        addnav("... Zurück!","tempel.php");
        break;
    }

    case 'opfer_ok':
    { //Götter-Opfer Ergebnis
        $runden = $_GET['runden'];
        $glueck = e_rand ( 0, ( 20 - $runden ) );
        if($glueck == 0) { $glueck = 2; }
        elseif($glueck > 0 && $glueck < 10) {$glueck = 1;}
        else {$glueck = 0.1;}
        $was = e_rand(1,7);
        $menge = e_rand(1,10);
        $msg = "";
        $val1 = 0;
        $val_gold = 0;

        $session['user']['turns'] -= $runden;

        output("`&Du atmest ruhig ein und aus, ein und aus... fühlst deine Entspannung wachsen. Schließlich bist du den Göttern ganz nah und bietest ihnen ein Opfer. Sie nehmen dir...");

        switch($was) {

            case 1:
                $menge = ceil($menge * 0.5);

                if( ($session['user']['maxhitpoints']-$menge) > $session['user']['level'] * 10 ) {

                    $session['user']['maxhitpoints'] -= $menge;
                    debuglog("Opferte ".$menge." LP im Tempel!");

                    $val1 = ceil($runden * $menge * 0.4 * e_rand(1,2) * $glueck);
                    $val1 = min($val1,min($session['user']['level']+10,20));
                    $val_gold = $val1 * 200;

                    $item = array('tpl_name'=>"Göttliche Rüstung",'tpl_description'=>"Eine Rüstung mit ".$val1." Verteidigung, die du von den Göttern als Dank für dein Opfer erhalten hast.",'tpl_value1'=>$val1,'tpl_gold'=>$val_gold);

                    item_add($session['user']['acctid'],'rstdummy',$item);

                    $msg = "`^".$menge."`0 permanente Lebenskraft.`nVor deinen Füßen liegt nun eine neue, schimmernde Rüstung mit ".$val1." Verteidigung!";

                }
                else {
                    $msg = "`^".$menge."`0 permanente Lebenskraft, die du leider nicht hast! Unbefriedigt erhebst du dich.";
                    $menge = 0;
                }

                break;

            case 2:
            case 3:

                if( $menge <= $session['user']['gems'] ) {

                    $session['user']['gems'] -= $menge;
                    debuglog("Opferte ".$menge." Edels im Tempel!");

                    $val1 = ceil($runden * $menge * 0.2 * e_rand(1,2) * $glueck);
                    $val1 = min($val1, min($session['user']['level']+10,20) );
                    $val_gold = $val1 * 200;

                    $item = array('tpl_name'=>"Göttliche Waffe",'tpl_description'=>"Eine Waffe mit ".$val1." Angriff, die du von den Göttern als Dank für dein Opfer erhalten hast.",'tpl_value1'=>$val1,'tpl_gold'=>$val_gold);

                    item_add($session['user']['acctid'],'waffedummy',$item);

                    $msg = "`^".$menge."`0 Edelsteine!`nVor deinen Füßen liegt eine neue, glänzende Waffe mit ".$val1." Angriff!";

                }
                else {
                    $msg = "`^".$menge."`0 Edelsteine, die du leider nicht hast! Unbefriedigt erhebst du dich.";
                    $menge = 0;
                }


                break;

            case 4:
            case 5:

                $menge *= 500;

                if( $menge <= $session['user']['gold'] ) {

                    $session['user']['gold'] -= $menge;

                    $val1 = ceil($runden * $menge * 0.001 * e_rand(1,3) * $glueck) * 0.01;
                    $val1 = min(max($val1,1.1),1.6);
                    $val_gold = floor($val1 * 1500);

                    $item = array('tpl_value1'=>$val1,'tpl_gold'=>$val_gold);

                    item_add($session['user']['acctid'],'gtlschtzzb',$item);

                    $msg = "`^".$menge."`0 Gold!`nVor deinen Füßen liegt ein seltener Zauberspruch!";

                }
                else {
                    $msg = "`^".$menge."`0 Gold, das du leider nicht hast! Unbefriedigt erhebst du dich.";
                    $menge = 0;
                }

                break;

            case 6:
            case 7:
                $msg = "gar nichts. Sie halten dich für \"zu gierig\". Was immer das heißen mag.";
                $menge = 0;
                break;

        }

        if($menge > 0) {

            if($glueck < 1) { $msg.= "`nHeute ist wohl nicht dein Glückstag.. Die Götter scheinen von deiner Ernsthaftigkeit nicht überzeugt gewesen zu sein!`n";        }
            elseif($glueck > 1) { $msg.= "`nDu musst der Liebling der Götter sein!`n";        }
        }

        output($msg);

        if($session['user']['turns'] >= 2) {addnav("Nochmal meditieren","tempel.php?op=opfer");}
        addnav("Zum Tempel","tempel.php");

        break;
    }

    case 'gardenmaze':
    { //schickt den User in den Schlossgarten
        output('`&Du atmest ruhig ein und aus, ein und aus... fühlst deine Entspannung wachsen. Schließlich bist du den Göttern ganz nah und bietest ihnen ein Opfer.`nSie nehmen dir 10% deiner Lebenskraft und führen dich an einen verlassenen Ort.');
        $session['user']['hitpoints']*=0.9;
        addnav("Weiter","abandoncastle.php?choose=2");
        break;
    }

    case "wunder":
    { //sieht unfertig aus
        output("");

        addnav("Alle von den Toten erwecken!","tempel.php?op=wunder_ok&wunder=auferstehung");
        addnav("Sofortiges Stadtfest!","tempel.php?op=wunder_ok&wunder=auferstehung");
        addnav("Sehr gute Stimmung für alle!","tempel.php?op=wunder_ok&wunder=auferstehung");
        addnav("!","tempel.php?op=wunder_ok&wunder=auferstehung");
        break;
    }

    case 'wunder_ok':
    { //noch mehr unfertiges
        switch($_GET['wunder']) {

            case '':

                break;

            default:
                break;

        }

        break;
    }

    case 'fluch':
    { //jemanden verfluchen/segnen - Namenssuche
        output("Als ".($priest>0?'Priester':'Hexe')." kannst du allen Helden einen Fluch aufzwingen, der sie beim Kampf beeinträchtigt. Oder einen Segen, je nachdem. Beides verschwindet von selbst nach einiger Zeit.`n`n");

        if(!$_POST['name'])
        {
            output('<form action="tempel.php?op=fluch" method="POST">',true);
            output('<input type="text" size="20" name="name">',true);
            output('<input type="submit" size="20" name="ok" value="Suchen">',true);
            output('</form>',true);
            addnav("","tempel.php?op=fluch");
        }
        else
        {

            $ziel = rawurldecode($_POST['name']);

            $name = str_create_search_string($ziel);

            $sql = "SELECT acctid,name FROM accounts WHERE name LIKE '".$name."' AND locked=0";
            $res = db_query($sql);

            if(!db_num_rows($res)) {
                output("`iKeine Übereinstimmung gefunden!`i");
            }
            elseif(db_num_rows($res) >= 100) {
                output("`iZu viele Übereinstimmungen! Grenze deinen Suchbegriff etwas ein.`i");
            }
            else {
                output('<form action="tempel.php?op=fluch_ok" method="POST">',true);
                output('<select name="id" size="1">',true);
                while($p = db_fetch_assoc($res)){
                    output("<option value=\"".$p['acctid']."\">".strip_appoencode($p['name'],3)."</option>",true);
                }
                output('</select> `n',true);
                output('<select name="buff" size="1"><option value="f1">Fluch</option><option value="f2">Schlimmer Fluch</option><option value="s1">Segen</option></select>`n',true);
                output('<input type="submit" size="20" name="ok" value="Los!">',true);
                output('</form>',true);
                addnav("","tempel.php?op=fluch_ok");
            }
        }
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;
    }

    case 'fluch_ok':
    { //Fluch/Segen hinzufügen
        $str_buff=$_POST['buff'];
        if($witch>0) $str_buff='h'.$str_buff;

        if($str_buff == "f1")
        {
            item_add((int)$_POST['id'],'tmplflch1');
            systemmail((int)$_POST['id'],"`4Verflucht!",$session['user']['name']." `4hat dich für deine Freveltaten in seiner Eigenschaft als Priester mit dem Fluch der Tempelpriester belegt!");
            output("Du begibst dich in eine tiefe Trance. Nachdem du eine dem Opfer ähnelnde Stoffpuppe misshandelt hast, fühlst du die Energie des Fluches!`n`n");
        }

        elseif($str_buff == "f2")
        {
            item_add((int)$_POST['id'],'tmplflch2');
            systemmail((int)$_POST['id'],"`4Verflucht!",$session['user']['name']." `4hat dich für deine Freveltaten in seiner Eigenschaft als Priester mit dem schlimmen Fluch der Tempelpriester belegt!");
            output("Du begibst dich in eine tiefe Trance. Nachdem du eine dem Opfer ähnelnde Stoffpuppe misshandelt hast, fühlst du die Energie des Fluches!`n`n");
        }

        elseif($str_buff == "s1")
        {
            item_add((int)$_POST['id'],'tmplsgn');
            systemmail((int)$_POST['id'],"`@Gesegnet!",$session['user']['name']." `@hat dich in seiner Eigenschaft als Priester mit einem göttlichen Segen bedacht!");
            output("Du begibst dich in eine tiefe Trance. Nachdem Du eine der Person ähnelnde Stoffpuppe gestreichelt hast, fühlst du die Energie des Segens!`n`n");
        }

        elseif ($str_buff == "hf1")
        {
            item_add((int)$_POST['id'],'hxflch1');
            systemmail((int)$_POST['id'],"`4Verflucht!",$session['user']['name']." `4hat dich für deine Freveltaten mit dem Fluch der Hexen belegt!");
            output("Du begibst Dich in eine tiefe Trance. Nachdem du eine dem Opfer ähnelnde Stoffpuppe misshandelt hast, fühlst du die Energie des Fluches!`n`n");
        }

        else if ($str_buff == "hf2")
        {
            item_add((int)$_POST['id'],'hxflch2');
            systemmail((int)$_POST['id'],"`4Schlimm verflucht!",$session['user']['name']." `4hat dich für deine Freveltaten mit dem schlimmen Fluch der Hexen belegt!");
            output("Du begibst Dich in eine tiefe Trance. Nachdem du ein Dutzend Nadeln in eine dem Opfer ähnelnde Stoffpuppe gestossen hast, fühlst du die Energie des Fluches!`n`n");
        }

        else if ($str_buff == "hs1")
        {
            item_add((int)$_POST['id'],'hxsgn');
            systemmail((int)$_POST['id'],"`@Gesegnet!",$session['user']['name']." `@hat dich im Namen der Hexen mit einem Segen bedacht!");
            output("Du begibst dich in eine tiefe Trance. Nachdem du eine der Person ähnelnde Stoffpuppe gestreichelt hast, fühlst du die Energie des Segens!`n`n");
        }
        debuglog('Fluch '.$str_buff.' auf',$_POST['id']);
        output("`&Der Zauber wurde ausgesprochen!`n");
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;
    }

    case 'fluch_liste_auswahl':
    { //Liste der Verfluchten/Gesegneten
        $sql = "SELECT a.name, a.acctid FROM items i
								INNER JOIN accounts a ON a.acctid = i.owner
								LEFT JOIN items_tpl it ON it.tpl_id=i.tpl_id
								WHERE (it.curse>0 OR i.tpl_id IN('tmplflch1','tmplflch2','tmplsgn','hxflch1','hxflch2','hxsgn'))
								GROUP BY i.owner ORDER BY a.name";

        $res = db_query($sql);

        output("Du schaust in den magischen Spiegel und erkennst auf einer langen Liste sämtliche Helden, denen Flüche oder Segen anhängen:`n`n");

        if(db_num_rows($res) == 0)
        {
            output("`iEs gibt keine Verfluchten oder Gesegneten!`i");
        }
        else
        {
            output('<table border="0"  cellpadding="3">
						<tr class="trhead">
						<th>Nr.</th>
						<th>Name</th>
						<th>Aktionen</th>
						</tr>',true);

            for($i=1; $i<=db_num_rows($res); $i++)
            {
                $p = db_fetch_assoc($res);
                output('<tr class="'.($i%2?'trlight':'trdark').'">
								<td>'.$i.'</td>
								<td>'.$p['name'].'</td>
								<td><a href="tempel.php?op=fluch_liste&id='.$p['acctid'].'">Erscheinungen anzeigen</a></td>
								</tr>',true);
                addnav("","tempel.php?op=fluch_liste&id=".$p['acctid']);
            }        // END for
            output('</table>',true);
        }        // END flüche vorhanden
        output('',true);
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;
    }

    case 'fluch_liste':
    { //Liste der Flüche/Segen der Zielperson
        $sql = "SELECT a.name, a.acctid, i.id, i.name AS fluchname, i.hvalue FROM items i
								INNER JOIN accounts a ON i.owner = a.acctid
								LEFT JOIN items_tpl it ON it.tpl_id=i.tpl_id
								WHERE (it.curse>0 OR i.tpl_id IN('tmplflch1','tmplflch2','tmplsgn','hxflch1','hxflch2','hxsgn'))
								AND i.owner=".(int)$_GET['id']." ORDER BY i.name";

        $res = db_query($sql);

        output("Bald darauf werden diese Flüche und Segen sichtbar:`n`n");
        output('<table border="0" cellpadding="3">
				<tr class="trhead">
				<th>Nr.</th>
				<th>Name</th>
				<th>Tage verbleibend</th>
				<th>Aktionen</th>
				</tr>',true);

        for($i=1; $i<=db_num_rows($res); $i++) {

            $p = db_fetch_assoc($res);
            output('<tr class="'.($i%2?'trlight':'trdark').'">
						<td>'.$i.'</td>
						<td>'.$p['fluchname'].'</td>
						<td>'.(($p['hvalue'] == 0) ? 'unbegrenzt':$p['hvalue']).'</td>
						<td><a href="tempel.php?op=fluch_del&id='.$p['id'].'">Aufheben</a></td>
						</tr>',true);
            addnav("","tempel.php?op=fluch_del&id=".$p['id']);
        }        // END for

        output('</table>',true);
        addnav("Zurück","tempel.php?op=fluch_liste_auswahl");
        break;
    }

    case 'fluch_del':
    { //einen Fluch/Segen aufheben
        $i = item_get(' id='.(int)$_GET['id'],false);
        item_delete(' id='.(int)$_GET['id']);
        output("Du konzentrierst dich auf den Fluch oder Segen und spürst bereits nach kurzer Zeit, wie er schwächer und schwächer wird. Schließlich weißt du:`nEr ist Vergangenheit!");
        debuglog('nimmt Fluch '.$i['name'].' von',$i['owner']);

        if($i['tpl_id'] == "tmplsgn")
        {
            systemmail($i['owner'],"Segen aufgehoben!",$session['user']['name']." `@hat in seiner Eigenschaft als Priester den Segen von dir genommen.");
        }
        else if($i['tpl_id'] == 'hxsgn')
        {
            systemmail($i['owner'],"Segen aufgehoben!",$session['user']['name']." `@hat im Namen der Hexen den Segen von dir genommen.");
        }
        else if($i['tpl_id'] == 'tmplflch1' || $i['tpl_id'] == 'tmplflch2')
        {
            systemmail($i['owner'],"Fluch aufgehoben!",$session['user']['name']." `@hat dich in seiner Eigenschaft als Priester von deinem schrecklichen Fluch \"".$i['name']."\" befreit.");
        }
        else if($i['tpl_id'] == 'hxflch1' || $i['tpl_id'] == 'hxflch2')
        {
            systemmail($i['owner'],"Fluch aufgehoben!",$session['user']['name']." `@hat dich im Namen der Hexen von deinem schrecklichen Fluch \"".$i['name']."\" befreit.");
        }
        addnav("Zurück","tempel.php?op=fluch_liste_auswahl");
        break;
    }

    case 'bounty_del':
    { //von Kopfgeld freikaufen
        $gemcount = floor($session['user']['bounty'] * 0.001) * $session['user']['level'];
        $gemcount = min( max($gemcount, 3) , 50);

        if($_GET['act'] == 1)
        {
            if($session['user']['gems'] < $gemcount)
            {
                output("Leider hast du nicht so viele Edelsteine.");
            }
            else
            {
                $session['user']['gems'] -= $gemcount;

                if(e_rand(1,2)==1)
                {
                    output("Die Götter erlassen dir deine Sünden (Kopfgeld verfallen)!");
                    $session['user']['bounty'] = 0;
                }
                else
                {
                    output("Die Götter gewähren dir keine Entlastung!");
                }
            }
        }

        else
        {
            if($session['user']['bounty'] == 0)
            {
                output("Auf dich ist kein Kopfgeld ausgesetzt. Was willst du also hier?");
            }
            else
            {
                output("Willst du für `^".$gemcount." `&Edelsteine um Erlösung von deinen Sünden (Kopfgeld in Höhe von `^".$session['user']['bounty']."`& Gold) bitten? Wisse jedoch, dass auf die Götter kein Verlass ist..");
                addnav("Ja!","tempel.php?op=bounty_del&act=1");
            }
        }
        addnav("Zum Tempel","tempel.php");
        break;
    }

    case 'board':
    { //schwarzes Brett für Priester
        output("`&Neugierig betrachtest du die Wand neben der Pforte näher. Du erkennst Pergamente, die über bald anstehende Hochzeiten informieren.`n`n");

        board_view('tempel',($priest>=2)?2:0,'An der Wand sind folgende Nachrichten zu lesen:','Es scheinen keine Nachrichten vorhanden zu sein.');

        output("`n`n");
        if($priest >= 2) {
            board_view_form("Aufhängen","`&Hier kannst du als Priester eine Nachricht hinterlassen:");
            if($_GET['board_action'] == "add") {
                board_add('tempel');
                redirect("tempel.php?op=board");
            }
        }
        addnav("Zurück","tempel.php?op=".($priest>1?'secret':''));
        break;
    }

    case 'witchboard':
    { //schwarzes Brett für Hexen

        output("`b`c<span style=\"color:#89A84B\">Die Trauerweide`c`b`n");
        output("`aDu schreitest unter den mächtigen Baum, der seine Äste bis fast auf den Boden hängen lässt und betrittst scheinbar eine andere Welt.
				`nGeschützt von den schirmenden Zweigen der Trauerweide schwirren Feen, kaum mehr als winzige Lichtpunkte, um den mächtigen Stamm des Baues herum.
				`nSie flüstern dir Neuigkeiten ins Ohr und nehmen jedes deiner Worte wissbegierig auf, um es weiter zu erzählen.`n`n");

        board_view('witch',($witch>=2)?2:0,'Folgendes wird dir zugeflüstert:','Die Feen scheinen stumm zu sein.');

        output("`n`n");
        if ($witch >= 2)
        {
            board_view_form("Flüstern","`&Hier kannst du einer Fee etwas zuflüstern:");
            if ($_GET['board_action'] == "add")
            {
                board_add('witch');
                redirect("tempel.php?op=witchboard");
            }
        }
        addnav("Zurück","tempel.php?op=".($witch>1?'darkdimension':''));
        break;
    }

    case 'sysboard':
    { //schwarzes Brett für Systemmeldungen
        output("`&Hier hängen die letzten Verlobungen und Scheidungsgesuche aus.`n`n");

        board_view('tempel_sys',($priest>=2 || $witch>=2)?2:0,'Folgende Aktionen sind noch unbearbeitet:','Niemand will sich scheiden lassen.',true,true);

        output("`n`n");
        if($priest >= 2 || $witch>=2) {
            board_view_form("Aufhängen","`&Hier kannst Du als Priester/Hexe eine Nachricht hinterlassen:");
            if($_GET['board_action'] == "add") {
                board_add('tempel_sys');
                redirect("tempel.php?op=sysboard");
            }
        }
        addnav("Zurück","tempel.php?op=".($priest>0?'secret':'darkdimension'));
        break;
    }

    case 'massmail': // Massenmail (im wohnviertel by mikay)
    {
        $str_out .= get_title('Taubenschlag unter dem Dach des Tempels.`0');

        addnav('Zurück','tempel.php?op=secret');

        $sql='SELECT acctid, name, login, profession
            FROM accounts
            WHERE profession='.PROF_PRIEST.'
            OR profession='.PROF_PRIEST_HEAD.'
            OR profession='.PROF_PRIEST_NEW.'
            AND acctid!='.(int)$session['user']['acctid'].'
            ORDER BY profession DESC';
        $result=db_query($sql);
        $users=array();
        $keys=0;

        while($row=db_fetch_assoc($result))
        {
            $profs[0][0]='Zivilist';
            if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';

            $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" id="inp0798" '.($row['profession']!=PROF_PRIEST_NEW ? 'checked':'').'> '.$row['name'].'

            '.JS::event('#inp0798','click','chk();').'

            <br>';
            $keys++;
            $lastprofession=$row['profession'];

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
            $str_out .= form_header('tempel.php?op=massmail')
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
            </form>
            '.JS::MassMail(true);
        }
        else
        {
            $str_out .= '`c`bEs wurden noch keine Schlüssel verteilt - und ja, Bombentauben an missliebige Nachbarn sind gegen das Gesetz.`b`c';
        }
        output($str_out);
        break;
    } // END massmail

    case 'poll': //Umfrage erstellen
    {
        require_once(LIB_PATH.'board.lib.php');
        output(get_title('Umfragen der Priester'));
        poll_add('temple_secret'.$_GET['pollsection'],100,1);
        if(!empty($session['polladderror'])) {
            if($session['polladderror'] == 'maxpolls')
            {
                output('`$An dieser Stelle findet bereits eine Umfrage statt! Entferne bitte zunächst diese, ehe du eine neue eröffnest.`n`n');
            }
        }
        else
        {
            redirect('tempel.php?op=secret');
        }

        if($_GET['pollsection'] == 'private')
        {
            output('`8Du möchtest also im Diskussionsraum eine Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und an einer prominenten Stelle aufgehängt zu werden:`n`n');

        }
        else
        {
            output('`8Du möchtest also eine öffentliche Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und für alle gut sichtbar platziert zu werden:`n`n');
        }
        addnav('Zurück zum Allerheiligsten','tempel.php?op=secret');

        poll_show_addform();
        break;
    }

    case 'massmail2': // Massenmail (im wohnviertel by mikay)
    {
        $str_out .= get_title('Taubenschlag am Rande der Lichtung.`0');

        addnav('Zurück','tempel.php?op=darkdimension');

        $sql='SELECT acctid, name, login, profession
            FROM accounts
            WHERE profession='.PROF_WITCH.'
            OR profession='.PROF_WITCH_HEAD.'
            OR profession='.PROF_WITCH_NEW.'
            AND acctid!='.(int)$session['user']['acctid'].'
            ORDER BY profession DESC';
        $result=db_query($sql);
        $users=array();
        $keys=0;

        while($row=db_fetch_assoc($result))
        {
            $profs[0][0]='Zivilist';
            if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';

            $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" id="inp0998" '.($row['profession']!=PROF_WITCH_NEW ? 'checked':'').'> '.$row['name'].'
             '.JS::event('#inp0998','click','chk();').'
            <br>';
            $keys++;
            $lastprofession=$row['profession'];

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
            $str_out .= form_header('tempel.php?op=massmail2')
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
            </form>
            '.JS::MassMail(true);
        }
        else
        {
            $str_out .= '`c`bEs wurden noch keine Schlüssel verteilt - und ja, Bombentauben an missliebige Nachbarn sind gegen das Gesetz.`b`c';
        }
        output($str_out);
        break;
    } // END massmail

    case 'poll2': //Umfrage erstellen
    {
        require_once(LIB_PATH.'board.lib.php');
        output(get_title('Umfragen der Hexen'));
        poll_add('witch_secret'.$_GET['pollsection'],100,1);
        if(!empty($session['polladderror'])) {
            if($session['polladderror'] == 'maxpolls')
            {
                output('`$An dieser Stelle findet bereits eine Umfrage statt! Entferne bitte zunächst diese, ehe du eine neue eröffnest.`n`n');
            }
        }
        else
        {
            redirect('tempel.php?op=darkdimension');
        }

        if($_GET['pollsection'] == 'private')
        {
            output('`8Du möchtest also im Diskussionsraum eine Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und an einer prominenten Stelle aufgehängt zu werden:`n`n');

        }
        else
        {
            output('`8Du möchtest also eine öffentliche Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und für alle gut sichtbar platziert zu werden:`n`n');
        }
        addnav('Zurück zum Pausenraum','court.php?op=judgesooc');

        poll_show_addform();
        break;
    }

    default:
        output("Hier dürfte ich gar nicht sein.. op:".$op.",is_priest:".$priest.',is_witch:'.$witch);
        addnav("Zurück zum Stadtzentrum","village.php");
        break;

}

page_footer();

// END tempel.php
?>