<?php
//
/*
* Author der Basisversion:    anpera (logd@anpera.de)
/**
Ausbau-Erweiterung (1.+2. Stufe):
Für bestimmte Kosten (upgold, upgems) kann ein Haus erweitert werden.
**/
/*******************************************/
// Privatraum-Mod by talion
/*******************************************/
// Ausgliederung der Schlüssel in die Tabelle keylist by Maris
// 07.08.05 Update auf neue Haustypen by Maris
// 09.01.06 Ausgliederung der Chaträume in "inside_houses.php" by Maris
// 10.03.06 Ausgliederung des PvPs / Einbruchs in "houses_pvp.php" by talion
// 23.01.07 Endlich sauber :-) by Dragonslayer


require_once('common.php');
require_once(LIB_PATH.'wakeup.lib.php');
require_once(LIB_PATH.'house.lib.php');

checkday();
is_new_day();

$str_filename = basename(__FILE__);
$str_output = '';

// Diebe können Nachrichten oder Erkennungszeichen im Haus hinterlassen
if ($_GET['op']=='nachricht')
{
        $msg = $_POST['msg'];
        if ($msg!='')
        {
                $sql='INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"house-'.(int)$_GET['id'].'",'.$session['user']['acctid'].',"`@'.$msg.'`V")';
                db_query($sql);
        }
}
page_header('Das Wohnviertel');

switch($_GET['op']) {
        case 'scrub':

                if ($session['user']['turns']>0)
                {
                        $houseid=$_GET['id'];
                        $sql='SELECT trick, housename FROM houses WHERE houseid='.(int)$_GET['id'];
                        $result = db_query($sql);
                        $row = db_fetch_assoc($result);
                        $trick=utf8_unserialize($row['trick']);
                        $str_output .= '`0Du schrubbst eine ganze Weile an der Fassade deines Hauses und entfernst ';
                        if ($trick['eggs'])
                        {
                                $str_output .= 'die '.$trick['eggs'].'`2 Eier ';
                        }
                        if ($trick['eggs'] && $trick['dung'])
                        {
                                $str_output .= 'und ';
                        }
                        if ($trick['dung'])
                        {
                                $str_output .= 'den '.$trick['dung'];
                                systemmail($trick['dungid'],'`4Schabernack gescheitert`0','`&Der '.$trick['dung'].'`&, den du am Eingang zu '.$row['housename'].'`& plaziert hast, wurde soeben entdeckt und entsorgt!`nSchade...');
                        }
                        $str_output .= '.`nDabei verlierst du einen Waldkampf.';
                        $session['user']['turns']--;
                        $sql = 'UPDATE houses SET trick="" WHERE houseid='.(int)$_GET['id'];
                        db_query($sql);
                }
                else
                {
                        $str_output .= '`0Du bist wahrlich schon zu müde, um jetzt noch dein Haus zu putzen.';
                }

                if(isset($session['houses_bio_ret_querystring'])) {
                        addnav('Zurück','houses.php?'.$session['houses_bio_ret_querystring'].'&ret_id='.$_GET['id']);
                }
                else {
                        addnav('Zum Wohnviertel','houses.php');
                }

        break;

        case 'trick':

                $houseid=(int)$_GET['id'];
                if (!$_GET['item'])
                {
                        $str_output .= '`n<table border="0"><tr><td>`0`bFolgende deiner Gegenstände eignen sich für einen Schabernack:`b</td></tr><tr><td valign="top">';

                        $result = item_list_get('i.owner='.$session['user']['acctid'].' AND (i.tpl_id = "thedung" or i.tpl_id = "eiersch")','ORDER BY i.value1, i.id ASC',false);

                        $amount=db_num_rows($result);
                        if (!$amount)
                        {
                                $str_output .= '`iDu hast wohl Löcher in den Taschen!';
                        }
                        $arr_allowed_item_ids = array();
                        for ($i=1;$i<=$amount;$i++)
                        {
                                $item_s = db_fetch_assoc($result);
                                $str_output .= '<a href=houses.php?op=trick&id='.$houseid.'&item='.$item_s['id'].'>`&-'.$item_s['name'].'</a>`0`n';
                                $arr_allowed_item_ids[]=$item_s['id'];
                                //addnav('','houses.php?op=trick&id='.$houseid.'&item='.$item_s['id']);
                        }
                        addpregnav('/houses.php\?op=trick&id='.$houseid.'&item=('.join('|',$arr_allowed_item_ids).')/');
                        unset($arr_allowed_item_ids);

                        $str_output .= '</td></tr></table>';
                        addnav('Zurück','houses.php');
                }
                else if (e_rand(1,6)>1)
                {
                        $item_s = item_get(' id = '.$_GET['item'],false);
                        $sql='SELECT housename,trick FROM houses WHERE houseid='.(int)$_GET['id'];
                        $result = db_query($sql);
                        $row = db_fetch_assoc($result);
                        $trick=utf8_unserialize($row['trick']);
                        if ($item_s['tpl_id']=='thedung')
                        {
                                if ($trick['dung'])
                                {
                                        $str_output .= '`0Du schleichst in böser Absicht an '.$row['housename'].'`0 und musst feststellen, dass schon jemand die gleiche Idee hatte!`nEine ganze Menge '.$trick['dung'].'`0 wartet nur darauf, dass jemand hineintritt.`n`nDu entschliesst dich alles so zu lassen, wie es ist und eilst davon, bevor man dich noch für den Übeltäter hält.`n';
                                }
                                else
                                {
                                        $str_output .= '`IAlles klar!`n`0Du versteckst deine Ladung '.$item_s['name'].'`0 am Eingang von '.$row['housename'].'`0 und hastest eilig davon.`n`nWer da wohl reintreten wird?';
                                        $trick['dung']=$item_s['name'];
                                        $trick['dungid']=$session['user']['acctid'];
                                        $s_trick=utf8_serialize($trick);
                                        $sql = 'UPDATE houses SET trick="'.db_real_escape_string($s_trick).'" WHERE houseid='.(int)$_GET['id'];
                                        db_query($sql);
                                        item_delete( ' id='.$item_s['id']);
                                }
                        }
                        else if ($item_s['tpl_id']=='eiersch')
                        {
                                $str_output .= '`0Du holst weit aus und schleuderst alle `I'.$item_s['value1'].'`0 Eier in der Schachtel gegen die Frontseite von `I'.$row['housename'].'`0.`nDann siehst du zu, dass du schnell fort kommst.';
                                $trick['eggs']+=$item_s['value1'];
                                $s_trick=utf8_serialize($trick);
                                $sql = 'UPDATE houses SET trick="'.db_real_escape_string($s_trick).'" WHERE houseid='.(int)$_GET['id'];
                                db_query($sql);
                                item_delete( ' id='.$item_s['id']);
                        }
                        addnav('Zurück','houses.php');
                }
                else
                {
                        $item_s = item_get(' id = '.$_GET['item'],false);
                        if ($item_s['tpl_id']=='eiersch')
                        {
                                $penalty=175*$session['user']['level'];
                        }
                        else
                        if ($item_s['tpl_id']=='thedung')
                        {
                                $penalty=500*$session['user']['level'];
                        }
                        $days=1;
                        if ($penalty>=1500)
                        {
                                $days=2;
                        }

                        $str_output .= '`IOhoh!`n`0Eine Stadtwache hat dich schon eine ganze Weile bei deinem Treiben beobachtet und hält es nun für angebracht, einzugreifen.`n`nWegen grobem Unfug sollst du nun `I'.$penalty.' Goldstücke`0 Strafe zahlen oder musst für '.($days==1?'einen Tag':$days.' Tage').' in den Kerker!`n`nWas willst du tun?';
                        addnav('Strafe annehmen');
                        addnav('Zahlen','houses.php?op=punishment&penalty='.$penalty.'&pay=1');
                        addnav('Kerker','houses.php?op=punishment&penalty='.$days.'&jail=1');
                        addnav('Widersetzen');
                        addnav('Kämpfen','houses.php?op=resist&fight=1');
                        addnav('Flüchten','houses.php?op=resist');
                }
        break;

        case 'punishment':

                $penalty=$_GET['penalty'];
                if ($_GET['pay']==1)
                {
                        $str_output .= '`0Zähneknirschend erklärst du dich bereit die '.$penalty.' Goldmünzen Strafe zu zahlen.';
                        if ($session['user']['gold']<$penalty)
                        {
                                $str_output .= '`n`0Da du allerdings das nötige Kleingeld nicht dabei, hast wird die Strafe von deinem Bankkonto beglichen.`n`n';
                                $session['user']['goldinbank']-=$penalty;
                        }
                        else
                        {
                                $session['user']['gold']-=$penalty;
                        }
                        addnav('W?Zurück ins Wohnviertel','houses.php');
                }
                else
                {
                        $str_output .= '`0Schweren Herzens erklärst du dich bereit, deine Strafe abzusitzen.`n';
                        $session['user']['imprisoned']+=$penalty;
                        addnav('Weiter','prison.php');
                }
        break;

        case 'resist':

                if($_GET['fight']==1)
                {
                        $str_output .= '`0Du wartest einen günstigen Moment ab und schleuderst der Wache deine Faust ins Gesicht.`n';
                        addnav('Weiter','houses_pvp.php?op=trick');
                }
                else
                {
                        if (e_rand(1,2)==1)
                        {
                                $str_output .= '`0Du läufst so schnell du kannst und findest dich völlig außer Puste im Stadtzentrum wieder.`n';
                                addnav('Weiter','village.php');
                        }
                        else
                        {
                                $str_output .= '`0Du läufst so schnell du kannst, doch leider kannst du der Wache nicht entkommen.`nDu musst kämpfen!';
                                addnav('Weiter','houses_pvp.php?op=trick');
                        }
                }
        break;

        case 'build':

                $goldcost = getsetting('housebuildcostgold',30000);
                $gemcost = getsetting('housebuildcostgems',50);

                // MIt Hausbau beginnen
                if ($_GET['act']=='start')
                {
                        $newhouses = getsetting('newhouses',true);
                        $max_houses = getsetting('maxhouses',300);
                        $sql = 'SELECT COUNT(*) AS c FROM houses h WHERE build_state != '.HOUSES_BUILD_STATE_EMPTY;
                        $res = db_query($sql);
                        $anzahl = db_fetch_assoc($res);

                        if (!$newhouses) {
                                $str_output .= '`0Der Mann vom Grundstücksamt schaut dich betroffen an und erklärt dir wortreich, dass derzeit keine Baugenehmigungen erteilt werden.`n';
                                $str_output .= '`0Du wirst dir wohl einen Schlüssel zu einem bereits bestehenden Haus besorgen oder ein Haus kaufen müssen.';
                        }
                        elseif ($anzahl['c'] >= $max_houses)
                        {
                                $str_output .= '`0Der Mann vom Grundstücksamt schaut dich betroffen an und erklärt dir wortreich, dass alle '.$max_houses.' Grundstücke bereits bebaut sind.`n';
                                $str_output .= '`0Du wirst dir wohl einen Schlüssel zu einem bereits bestehenden Haus besorgen oder ein Haus kaufen müssen.';
                        }
                        else
                        {
                                // Erst mal schauen, ob leere Grundstücke da sind
                                $sqlfree = 'SELECT * FROM houses WHERE build_state='.HOUSES_BUILD_STATE_EMPTY.' ORDER BY houseid ASC LIMIT 1';

                                $resultfree = db_query($sqlfree);
                                $number_free=db_num_rows($resultfree);
                                // Wenn frei, dann bebaue ein Grundstück...
                                if ($number_free>0)
                                {
                                        $myhouse = db_fetch_assoc($resultfree);
                                        $sql = 'UPDATE houses
                                                        SET owner='.$session['user']['acctid'].',status=0,build_state='.HOUSES_BUILD_STATE_INIT.',housename="'.db_real_escape_string($session['user']['login']).'s Haus",
                                                        gold=0,gems=0,description=""
                                                        WHERE houseid='.$myhouse['houseid'];
                                        db_query($sql);
                                        $housenr = $myhouse['houseid'];
                                }
                                else
                                // ...sonst lege ein neues an.
                                {
                                        $sql = 'INSERT INTO houses (owner,status,build_state,gold,gems,housename) VALUES ('.$session['user']['acctid'].',0,'.HOUSES_BUILD_STATE_INIT.',0,0,"'.db_real_escape_string($session['user']['login']).'s Haus")';
                                        db_query($sql);
                                        $housenr = db_insert_id();
                                }
                                if ($housenr == 0)
                                {
                                        redirect('houses.php');
                                }

                                $str_output .= '`0Du erklärst das Fleckchen Erde zu deinem Besitz und kannst mit dem Bau von Hausnummer `I'.$housenr.'`0 beginnen!`n`n';
                                $str_output .= '`0<form action="houses.php?op=build&act=build2" method="POST">';
                                $str_output .= '`nGebe einen Namen für dein Haus ein: <input name="housename" maxlength="25">`n';
                                $str_output .= '`nWieviel Gold anzahlen? <input type="gold" name="gold">`n';
                                $str_output .= '`nWieviele Edelsteine? <input type="gems" name="gems">`n';
                                $str_output .= '<input type="submit" class="button" value="Bauen">';
                                addnav('','houses.php?op=build&act=build2');
                        }
                }
                // An Haus weiterbauen
                else if ($_GET['act']=='build2')
                {
                        $sqlcheck = 'SELECT * FROM houses WHERE owner='.$session['user']['acctid'].' ORDER BY houseid DESC';
                        $resultcheck = db_query($sqlcheck);
                        $number_of_houses=db_num_rows($resultcheck);
                        if ($number_of_houses>0)
                        {
                                $rowcheck = db_fetch_assoc($resultcheck);
                        }

                        $paidgold=(int)$_POST['gold'];
                        if ($_POST['housename']!='')
                        {
                                $housename=stripslashes($_POST['housename']);
                        }
                        else
                        {
                                $housename=stripslashes($rowcheck['housename']);
                        }
                        $paidgems=(int)$_POST['gems'];
                        if ($session['user']['gold']<$paidgold || $session['user']['gems']<$paidgems)
                        {
							$str_output .= '`0Du hast nicht genug dabei!';
							// Für die, die zu dumm sind, ihr Gold zu zählen beim ersten Einzahlen; Hausname bleibt erhalten
							$sql = 'UPDATE houses SET housename="'.db_real_escape_string($housename).'" WHERE houseid='.$rowcheck['houseid'];
							db_query($sql);
							addnav('Nochmal','houses.php?op=build');
                        }
                        else if ($session['user']['turns']<1)
                        {
                                $str_output .= '`0Du bist zu müde, um heute noch an deinem Haus zu arbeiten!';
                        }
                        else if ($paidgold<0 || $paidgems<0)
                        {
                                $str_output .= '`0Versuch hier besser nicht zu beschummeln.';
                        }
                        else
                        {
                                $str_output .= '`0Du baust für `I'.$paidgold.'`0 Gold und `I'.$paidgems.'`@ Edelsteine an deinem Haus "'.$housename.'`0"...`n';
                                $rowcheck['gold']+=$paidgold;
                                $session['user']['gold']-=$paidgold;
                                $str_output .= '`nDu verlierst einen Waldkampf.';
                                $session['user']['turns']--;
                                if ($rowcheck['gold']>$goldcost)
                                {
                                        $str_output .= '`n`0Du hast die kompletten Goldkosten bezahlt und bekommst das überschüssige Gold zurück.';
                                        $session['user']['gold']+=$rowcheck['gold']-$goldcost;
                                        $rowcheck['gold']=$goldcost;
                                }
                                $rowcheck['gems']+=$paidgems;
                                $session['user']['gems']-=$paidgems;
                                if ($rowcheck['gems']>$gemcost)
                                {
                                        $str_output .= '`n`0Du hast die kompletten Edelsteinkosten bezahlt und bekommst überschüssige Edelsteine zurück.';
                                        $session['user']['gems']+=$rowcheck['gems']-$gemcost;
                                        $rowcheck['gems']=$gemcost;
                                }
                                $goldtopay=$goldcost-$rowcheck['gold'];
                                $gemstopay=$gemcost-$rowcheck['gems'];
                                $done=round(100-((100*$goldtopay/$goldcost)+(100*$gemstopay/$gemcost))/2);
                                $str_output .= '`n`0Dein Haus ist damit zu `I'.$done.'%`0 fertig. Du musst noch `I'.$goldtopay.'`0 Gold und `I'.$gemstopay.' `0Edelsteine bezahlen, bis du einziehen kannst.';
                                if ($rowcheck['gems']>=$gemcost && $rowcheck['gold']>=$goldcost)
                                {
                                        $int_keys = house_get_max_keys(0,false);
                                        $str_output .= '`n`n`b`0Glückwunsch!`b Dein Haus ist fertig!
														Du bekommst `b'.($int_keys+1).'`b Schlüssel überreicht, von denen du '.$int_keys.' an andere weitergeben kannst und besitzt nun deine eigene kleine Burg.`n';
                                        $rowcheck['gems']=0;
                                        $rowcheck['gold']=0;
                                        $rowcheck['build_state'] = 0;
                                        addnews('`2'.$session['user']['name'].'`3 hat das Haus `2'.$rowcheck['housename'].'`3 fertiggestellt.');
                                        addhistory('`3Hat das Haus `2'.$rowcheck['housename'].'`3 fertiggestellt.');
                                        

                                        //Dragonslayer: Make only one query to insert the 10 Keys
                                        $arr_changes = array('owner'=>$session['user']['acctid'],'value1'=>$rowcheck['houseid'],'gold'=>0,'gems'=>0);
                                        house_keys_add($arr_changes,$int_keys);

                                        $session['user']['house'] = $rowcheck['houseid'];
                                }
                                $sql = 'UPDATE houses SET gold='.$rowcheck['gold'].',gems='.$rowcheck['gems'].',housename="'.db_real_escape_string($housename).'",build_state='.(int)$rowcheck['build_state'].' WHERE houseid='.$rowcheck['houseid'];
                                db_query($sql);

                        }
                }
                else
                {
                        $sqlcheck = 'SELECT * FROM houses WHERE owner='.$session['user']['acctid'];
                        $resultcheck = db_query($sqlcheck);
                        $number_of_houses=db_num_rows($resultcheck);
                        if ($number_of_houses>0)
                        {
                                $rowcheck = db_fetch_assoc($resultcheck);
                        }
                        if ($number_of_houses>0 && $rowcheck['build_state']==0)
                        {
                                $str_output .= '`0Du hast bereits Zugang zu einem fertigen Haus und brauchst kein weiteres. Wenn du ein neues oder ein eigenes Haus bauen willst, musst du erst aus deinem jetzigen Zuhause ausziehen.';
                        }
                        else if ($session['user']['dragonkills']<getsetting('housegetdks',1) )
                        {
                                $str_output .= '`0Du hast noch nicht genug Erfahrung, um ein eigenes Haus kaufen zu können. Du kannst aber bei einem Freund einziehen, wenn er dir einen Schlüssel für sein Haus gibt.';
                        }
                        else if ($session['user']['turns']<1)
                        {
                                $str_output .= '`0Du bist zu erschöpft, um heute noch irgendetwas zu bauen. Warte bis morgen.';
                        }
                        else if ($number_of_houses>0 && $rowcheck['build_state']==HOUSES_BUILD_STATE_INIT)
                        {
                                $str_output .= '`0Du besichtigst die Baustelle deines neuen Hauses mit der Hausnummer `I'.$rowcheck['houseid'].'`0.`n`n';
                                $goldtopay=$goldcost-$rowcheck['gold'];
                                $gemstopay=$gemcost-$rowcheck['gems'];
                                $done=round(100-((100*$goldtopay/$goldcost)+(100*$gemstopay/$gemcost))/2);
                                $str_output .= grafbar(100,$done,'100%',20);
                                $str_output .= '`n`0Es ist zu `I'.$done.'%`0 fertig. Du musst noch `I'.$goldtopay.'`0 Gold und `I'.$gemstopay.' `0Edelsteine bezahlen.`nWillst du jetzt weiter bauen?`n`n';
                                $str_output .= '`0<form action="houses.php?op=build&act=build2" method="POST">';
                                $str_output .= '`n`0Wieviel Gold zahlen? <input type="gold" name="gold">`n';
                                $str_output .= '`n`0Wieviele Edelsteine? <input type="gems" name="gems">`n';
                                $str_output .= '<input type="submit" class="button" value="Bauen">';
                                addnav('','houses.php?op=build&act=build2');
                        }
                        else
                        {
                                $str_output .= '`0Du siehst ein schönes Fleckchen für ein Haus und überlegst dir, ob du nicht selbst eines bauen solltest, anstatt ein vorhandenes zu kaufen oder noch länger in Kneipe und Feldern zu übernachten.';
                                $str_output .= ' Ein Haus zu bauen würde dich `^'.$goldcost.' Gold`0 und `#'.$gemcost.' Edelsteine`0 kosten. Du mußt das nicht auf einmal bezahlen, sondern könntest immer wieder mal für einen kleineren Betrag ein Stück ';
                                $str_output .= 'weiter bauen. Wie schnell du zu deinem Haus kommst, hängt also davon ab, wie oft und wieviel du bezahlst.`n';
                                $str_output .= 'Du kannst in deinem zukünftigen Haus alleine wohnen oder es mit anderen teilen. Es bietet einen sicheren Platz zum Übernachten und einen Lagerplatz für einen Teil deiner Reichtümer.';
                                $str_output .= ' Ein gestartetes Bauvorhaben kann nicht abgebrochen werden.`n`nWillst du mit dem Hausbau beginnen?';
                                addnav('Hausbau beginnen','houses.php?op=build&act=start');
                        }
                }
                addnav('W?Zurück zum Wohnviertel','houses.php');
                addnav('d?Zurück zum Stadtzentrum','village.php');
                addnav('M?Zurück zum Marktplatz','market.php');
                addnav('Zur Hauptstraße','mainstreet.php');
        break;

        case 'buy':

                if ($session['user']['dragonkills']<getsetting('housegetdks',1) )
                {
                        $str_output .= '`0Der Mann vom Amt lacht dich nur schallend aus und bittet dich wieder zu kommen, wenn du groß bist.';
                }
                else if (!$_GET['id'])
                {

                        // Seit längerem verlassene Häuser zum Verkauf stellen
                        $int_mintime = (int)getsetting('houseabandonedmintime',864000);
                        $sql = 'SELECT * FROM houses WHERE build_state='.HOUSES_BUILD_STATE_ABANDONED.' AND lastchange<="'.date('Y-m-d H:i:s',time() - $int_mintime).'"';
                        $res = db_query($sql);
                        $int_count = db_num_rows($res);
                        if($int_count) {
                                while($arr_h = db_fetch_assoc($res)) {
                                        house_sell($arr_h);
                                }
                                systemlog($int_count.' verlassene Häuser zum Verkauf gestellt!');
                        }
                        db_free_result($res);
                        // END verl. H. z. Verk.

                        redirect('houses.php?tosell=1');
                }
                else
                {
                        $sql = 'SELECT * FROM houses WHERE houseid='.(int)$_GET['id'].' AND build_state = '.HOUSES_BUILD_STATE_SELL;
                        $result = db_query($sql);
                        if(!db_num_rows($result))
                        {
                                $str_output .= '`0Zu spät!`n`n';
                                $str_output .= '`0Der Verkäufer zieht sein Angebot überraschend zurück - vielleicht ist dir auch jemand anderes zuvorgekommen. Auf jeden Fall wirst du dich wohl nach einem anderen Haus umsehen müssen.';
                                output($str_output);
                                addnav('Zurück zum Wohnviertel','houses.php');
                                page_footer();
                        }

                        $row = db_fetch_assoc($result);
                        if ($row['owner'])
                        {
                                $gold=0;
                                $gems=0;
                        }
                        else
                        {
                                extract(house_get_price($row));
                        }
                        $goldbuycost=$gold+$row['gold'];
                        $gemsbuycost=$gems+$row['gems'];

                        if ($session['user']['acctid']==$row['owner'])
                        {
                                $str_output .= '`0Du hängst doch zu sehr an deinem Haus und beschließt, es noch nicht zu verkaufen.';
                                $session['user']['house']=$row['houseid'];
                                db_query('UPDATE houses SET build_state=0,gold=0,gems=0 WHERE houseid='.$row['houseid']);

                                // Gelöschte Schlüssel wiedergeben
                                house_keys_set('owner=0 AND value1='.$row['houseid'].' AND type='.HOUSES_KEY_DEFAULT,array('owner'=>$session['user']['acctid']),house_get_max_keys(0,true));

                        }
                        else if ($session['user']['gold']<$goldbuycost || $session['user']['gems']<$gemsbuycost)
                        {
                                $str_output .= '`0Dieses edle Haus übersteigt wohl deine finanziellen Mittel.';
                        }
                        else
                        {

                                $str_output .= '`0Glückwunsch zu deinem neuen Haus!`n`n';
                                addhistory('`3Hat das Haus `2'.$row['housename'].'`3 erworben.');

                                $session['user']['gold']-=$goldbuycost;
                                $session['user']['gems']-=$gemsbuycost;
                                $session['user']['house']=$row['houseid'];

                                $str_output .= '`0Du übergibst `I'.$goldbuycost.'`0 Gold und `I'.$gemsbuycost.'`0 Edelsteine an den Verkäufer und dieser händigt dir dafür einen Satz Schlüssel für Haus `b'.$row['houseid'].'`b`0 aus.';

                                if ($row['owner']>0)
                                {
                                        user_update(
                                                array
                                                (
                                                        'goldinbank'=>array('sql'=>true,'value'=>'goldinbank+'.$goldbuycost),
                                                        'gems'=>array('sql'=>true,'value'=>'gems+'.$gemsbuycost),
                                                        'house'=>array('sql'=>true,'value'=>'IF(house='.(int)$_GET['id'].',0,house)')
                                                ),
                                                $row['owner']
                                        );
                                        systemmail($row['owner'],'`@Haus verkauft!`0','`&'.$session['user']['name'].'
                            `0 hat dein Haus gekauft. Du bekommst `^'.$goldbuycost.'`0 Gold auf die Bank und `#'.$gemsbuycost.'`0 Edelsteine!');
                                }

                                $session['user']['house']=$row['houseid'];

                                $sql = 'UPDATE houses SET owner='.$session['user']['acctid'].',status=0,build_state=0,description="",gold=0,gems=0
                                                WHERE houseid='.$row['houseid'];
                                db_query($sql);

                                // Schlüssel übergeben
                                house_keys_set('value1='.$row['houseid'],array('owner'=>$session['user']['acctid']),house_get_max_keys(0,false));
                                addnav('Dein Domizil');
                                addnav('Haus betreten!','inside_houses.php?id='.$row['houseid']);
                                addnav('Zurück');

                        }
                }
                addnav('W?Zurück zum Wohnviertel','houses.php');
                addnav('d?Zurück zum Stadtzentrum','village.php');
                addnav('M?Zurück zum Marktplatz','market.php');
                addnav('Zur Hauptstraße','mainstreet.php');
        break;

        case 'sell':

                $sql = 'SELECT * FROM houses WHERE owner='.$session['user']['acctid'].' ORDER BY houseid DESC';
                $result = db_query($sql);

                if(!db_num_rows($result)) {
                        $str_output .= '`n`n`$Fehler: Ich konnte kein Haus finden, das dir gehört! Schreibe bitte eine Anfrage.`n';
                        page_footer();
                        exit();
                }

                $row = db_fetch_assoc($result);

                // Preise: $gold = Goldpreis, $gems = Gempreis
                extract(house_get_price($row));
                // Beim Verkauf nur halben Kaufpreis gutschreiben, um hier keine Spekulation aufkommen zu lassen!
                $gold = round($gold*0.5);
                $gems = round($gems*0.5);

                if ($_GET['act']=='sold')
                {
                        $bool_ok = true;

                        if(!isset($_GET['makler'])) {
                                $halfgold=(int)$_POST['gold'];
                                $halfgems=(int)$_POST['gems'];
                                if (($halfgold<$gold/20 && $halfgems<$gems/5) || ($halfgold==0 && $halfgems<$gems) || ($halfgold<$gold && $halfgems==0))
                                {
                                        $str_output .= '`0Du solltest vielleicht erst deinen Ale-Rausch ausschlafen, bevor du über einen Preis nachdenkst. Wie? Du bist nüchtern? Das glaubt dir so kein Mensch.';
                                        addnav('Neuer Preis','houses.php?op=sell');
                                        $bool_ok = false;
                                }
                                else if ($halfgold>$gold*2 || $halfgems>$gems*4)
                                {
                                        $str_output .= '`0Bei so einem hohen Preis bist du dir nicht sicher, ob du wirklich verkaufen sollst. Überlege es dir nochmal.';
                                        addnav('Neuer Preis','houses.php?op=sell');
                                        $bool_ok = false;
                                }
                        }

                        if($bool_ok) {

                                if(!isset($_GET['makler'])) {
                                        house_sell($row,$halfgold,$halfgems,$session['user']['acctid']);

                                        $str_output .= '`0Dein Haus steht ab sofort für `I'.$halfgold.'`0 Gold und `I'.$halfgems.'`0 Edelsteine zum Verkauf. Du und alle Mitbewohner habt den Schatz des Hauses gleichmäßig ';
                                        $str_output .= ' unter euch aufgeteilt und deine Untermieter haben ihre Schlüssel abgegeben.';
                                }
                                else {
                                        house_sell($row);

                                        $str_output .= '`0Dem Makler entfährt ungewollt ein freudiges Glucksen, als er dir `I'.$gold.'`0 Gold und die `I'.$gems.'`0 Edelsteine vorzählt.`n`n';
                                        $str_output .= 'Ab sofort steht dein Haus zum Verkauf und du kannst ein neues bauen, woanders mit einziehen oder ein anderes Haus kaufen.';
                                        $session['user']['goldinbank']         += $gold;
                                        $session['user']['gems']                 += $gems;
                                }

                        }
                }
                else
                {
                        $str_output .= '`0Du denkst ernsthaft darüber nach, dein Häuschen zu verkaufen. Wenn du selbst einen Preis festlegst, bedenke, daß er auf einmal bezahlt werden muss ';
                        $str_output .= ' und vom Käufer nicht in Raten abgezahlt werden kann. Außerdem kannst du weder ein neues Haus bauen, noch in diesem Haus wohnen, bis es verkauft ist.';
                        $str_output .= ' Du bekommst dein Geld erst, wenn das Haus verkauft ist. Der Verkauf lässt sich abbrechen, indem du selbst das Haus von dir kaufst.';
                        $str_output .= '`nWenn du sofort Geld sehen willst, musst du dein Haus für `I'.$gold.'`0 Gold und `I'.$gems.'`0 Edelsteine an einen Makler verkaufen.`n
                                                        Dein Haus wird auf jeden Fall baldmöglichst zu einem einfachen Wohnhaus umgebaut, um es einfacher verkaufen zu können.`n`n';

                        if($row['dmg'] >= 100) {
                                $str_output .= '`0Du solltest darüber nachdenken, zunächst die Schäden an deinem Haus zu beheben! Dann kannst du einen besseren Preis erzielen.`0`n`n';
                                addnav('Reparieren!','inside_houses.php?act=repair');
                        }

                        // Wenn Anbauten vorhanden, müssen diese zunächst abgerissen werden
                        $int_count = db_num_rows(db_query('SELECT id FROM house_extensions WHERE houseid='.$row['houseid'].' AND loc IS null'));
                        if($int_count == 0) {
                                $str_output .= '`0<form action="houses.php?op=sell&act=sold" method="POST">';
                                $str_output .= '`nWieviel Gold willst du verlangen? <input type="gold" name="gold">`n';
                                $str_output .= '`nWieviele Edelsteine soll das Haus kosten? <input type="gems" name="gems">`n';
                                $str_output .= '<input type="submit" class="button" value="Zu diesem Preis anbieten!">';
                                addnav('','houses.php?op=sell&act=sold');
                                addnav('An den Makler','houses.php?op=sell&act=sold&makler=1',false,false,false,false,'Wirklich an den Makler verkaufen?');
                        }
                        else {
                                $str_output .= '`n`qDie Behörden erlauben in einem verkauften Haus aus Sicherheitsgründen keine Anbauten.`n
                                                        In deinem Haus jedoch existier'.($int_count == 1 ? 't noch ein Anbau' : 'en noch Anbauten').', die du zunächst
                                                        selbst abreißen musst, um unnötige Verluste zu vermeiden. Wenn du dies erledigt hast, kannst du erneut versuchen,
                                                        dein Haus zu verkaufen!`0';
                                addnav('Zur Anbauten-Übersicht','inside_houses.php?id='.$row['houseid'].'&act=build_extensions');
                        }
                }
                addnav('Zurück');
                addnav('W?Zum Wohnviertel','houses.php');
                addnav('d?Zum Stadtzentrum','village.php');
                addnav('M?Zum Marktplatz','market.php');
                addnav('Zur Hauptstraße','mainstreet.php');
        break;

        case 'enter':

                $session['housekey'] = 0;

                $show_invent = true;

                $str_out = house_get_title('`&D`eu `)h`Ya`;s`St`N Zugang zu folgenden H`Sä`;u`Ys`)e`er`&n:').'`c`((klicke auf ein Haus, um eine Liste aller dortigen Gemächer zu sehen, zu denen du Zutritt hast)`0`c`n`n';

                addnav('Haus betreten');

                $sql = 'SELECT k.*,h.status,h.build_state,h.houseid,h.housename,a.acctid,a.name AS ownername, a.name,
                	a.superuser,
					a.activated,
					a.login,
					a.alive,
					a.expedition,
					a.imprisoned,
					a.location,
					a.sex,
					a.level,
					a.laston,
					a.loggedin,
					a.lastip,
					a.uniqueid,
					a.race FROM keylist k
                                        LEFT JOIN houses h ON h.houseid=k.value1
                                        LEFT JOIN accounts a ON a.acctid=h.owner
                                        WHERE k.owner='.$session['user']['acctid'].'
                                        ORDER BY k.type ASC, k.house_sort_order DESC, h.houseid ASC
                                        ';
                $result = db_query($sql);

                $str_out .= '`c<table cellpadding="4" cellspacing="1" align="center" width="500">
                                                <tr class="trhead"><td width="80">`bHausnummer`b</td><td width="150">`bName`b</td><td width="100">`bStatus`b</td><td width="150">`bBesitzer`b</td></tr>';

                // IDs zum Abruf der Gemächer + JS-Strings
                $arr_houserooms = array();
                $arr_access_tohouse = array();

                if ($session['user']['house']>0)
                {

                        $sql = 'SELECT houseid,housename,status,build_state FROM houses WHERE houseid='.$session['user']['house'];
                        $result2 = db_query($sql);
                        $row2 = db_fetch_assoc($result2);

                    $row2['housename'] =  utf8_html_entity_decode($row2['housename']);

                        if($row2['build_state'] == HOUSES_BUILD_STATE_INIT) {
                                $str_out .= '<tr><td colspan="5" align="center">`&`iDein Haus ist noch im Bau oder steht zum Verkauf`i`0</td></tr>';
                        }
                        else {
                                $str_lnk = 'inside_houses.php?outside=1&id='.$row2['houseid'];

                                $str_hname_raw = strip_appoencode($row2['housename'],3);

                                //addnav( 'H?Dein Haus: '.$str_hname_raw, ' ', false, 'room_men('.$row2['houseid'].');' );
                                addnav( 'H?Dein Haus: '.$str_hname_raw, $str_lnk);
                                addnav( '', $str_lnk);
                                addnav('','login.php?op=logout&loc='.USER_LOC_HOUSE.'&restatloc='.$row2['houseid']);

                                $str_out .= '<tr bgcolor="#000000">
                                                                <td align="center">'.$row2['houseid'].'</td>
                                                                <td colspan="3">
                                                                        <a href="javascript:void(0);" id="apq'.$row2['houseid'].'">'.$row2['housename'].' `&(dein eigenes)`0</a>
                                                                        '.JS::event('#apq'.$row2['houseid'].'','click','room_men('.$row2['houseid'].');').'
                                                                        ';
                                                                //<td colspan="3">'.create_lnk($row2['housename'],$str_lnk).' (dein eigenes)</td>
                                $str_out .= '</tr>';

                                $arr_houserooms[$row2['houseid']] = 'Array('.$row2['houseid'].',"'.addslashes($str_hname_raw).'",1';
                                $arr_access_tohouse[$row2['houseid']] = true;
                        }

                }

                $int_nr_of_houses = db_num_rows($result);

                if ($int_nr_of_houses==0)
                {
                        $str_out .= '<tr><td colspan="5" align="center">`&`iDu hast zu keinem Haus Zutritt`i`0</td></tr>';
                }
                else
                {
                        $rebuy=0;
                        $bool_div_set = false;
                        $accesskeys['w']=1;$accesskeys['d']=1;$accesskeys['m']=1;
                        for ($i=0; $i<$int_nr_of_houses; $i++)
                        {
                                $item = db_fetch_assoc($result);

                            $item['housename'] =  utf8_html_entity_decode($item['housename']);

                                if (!isset($arr_houserooms[$item['value1']]) && $item['acctid']!=$session['user']['acctid'])
                                {
                                        $str_trclass = ($str_trclass == 'trdark' ?'trlight':'trdark');
                                        $str_lnk = 'inside_houses.php?outside=1&id='.$item['houseid'];

                                        $str_hname_raw = strip_appoencode($item['housename'],3);

                                        // Wenn Zugang zum Haus
                                        if($item['type'] == HOUSES_KEY_DEFAULT) {
                                                //addnav('',$str_lnk);
                                                //addnav('','login.php?op=logout&loc='.USER_LOC_HOUSE.'&restatloc='.$item['houseid']);
                                                addnav($str_hname_raw, $str_lnk);
                                                $arr_access_tohouse[$item['houseid']] = true;
                                        }
                                        else {
                                                // Trennlinie
                                                // Letzter Eintrag noch Zugang zum Haus?
                                                if(!$bool_div_set) {
                                                        $str_out .= '<tr class="trhead"><td colspan="5">`bZu Gast in folgenden Häusern:`b</td></tr>';
                                                        $bool_div_set = true;
                                                }
                                        }
                                        //addnav( $str_hname_raw, ' ', false, 'room_men('.$item['houseid'].');' );

                                        $str_out .= '<tr class="'.$str_trclass.'">
                                                                        <td align="center">'.$item['houseid'].'</td>
                                                                        <td>
                                                                                <a href="javascript:void(0);" id="apz'.$item['houseid'].'">`&'.$item['housename'].'`0</a>
                                                                                 '.JS::event('#apz'.$item['houseid'].'','click','room_men('.$item['houseid'].');').'
                                                                        </td>
                                                                        <td>';

                                                                        //<td>'.create_lnk($item['housename'],$str_lnk).'</td><td>';

                                        $str_out .= get_house_state($item['status'],$item['build_state'],false);
                                        $str_out .= '</td><td>'.CRPChat::menulink( $item ).'</td></tr>';

                                        $arr_houserooms[$item['houseid']] = 'Array('.$item['houseid'].',"'.addslashes($str_hname_raw).'",'.($item['type'] == HOUSES_KEY_DEFAULT ? '1':'0');
                                }
                        }
                }
                $str_out .= '<tr><td colspan="4" align="right">`0['.create_lnk('umsortieren',$str_filename.'?op=set_house_order',true,false).']</td></tr>';

                $str_out .= '</table>`c';

                // Gemächer abrufen, falls nötig

                // wird benötigt, um weiter unten die navs erstellen zu können
                $str_room_ids = '';
                if(sizeof($arr_houserooms)) {

                        $sql = 'SELECT he.*,a.login AS oname,k.id AS keyid FROM house_extensions he
                                        LEFT JOIN keylist k ON (k.value2 = he.id AND k.type='.HOUSES_KEY_PRIVATE.' AND k.owner='.$session['user']['acctid'].')
                                        LEFT JOIN accounts a ON (a.acctid = he.owner)
                                        WHERE
                                                he.houseid IN ('.implode(',',array_keys($arr_houserooms)).')
                                                AND he.level > 0 AND he.loc IS NOT null AND he.owner > 0
                                        AND (he.owner = '.$session['user']['acctid'].' OR he.val = 0 OR k.id IS NOT null)
                                        ORDER BY he.houseid,he.loc DESC';
                        $res = db_query($sql);

                        while($arr_r = db_fetch_assoc($res)) {

                                if(!isset($arr_r['keyid']) && !isset($arr_access_tohouse[$arr_r['houseid']])) {
                                        continue;
                                }

                                $arr_r['name'] = (empty($arr_r['name']) ? $g_arr_house_extensions[$arr_r['type']]['name'] : strip_appoencode($arr_r['name'],3));

                                if(mb_strlen($arr_r['name']) > 19) {
                                        $arr_r['name'] = mb_substr($arr_r['name'],0,19).'..';
                                }

                                $arr_houserooms[$arr_r['houseid']] .= ',Array('.$arr_r['id'].',"'.addslashes($arr_r['name']).'","'.addslashes($arr_r['oname']).'","'.$arr_r['loc'].'")';

                                //addnav('','house_extensions.php?_ext_id='.$arr_r['id'].'');
                                if(!empty($str_room_ids))
                                {
                                        $str_room_ids .= '|';
                                }
                                $str_room_ids .= $arr_r['id'];
                        }
                        rawoutput('<!-- '.var_export($arr_houserooms,true).' -->');

                        $str_out .= JS::encapsulate('var mens = Array();
                                                        var rooms = Array(' . implode('),',$arr_houserooms) . '));
                                                        LOTGD.loadLibrary("popmenu");
                                                var men_vis = -1;
                                                var first_opened = -1;
                                                        function room_men (hid) {

                                                                if(first_opened > -1) {
                                                                        hid = first_opened;
                                                                        first_opened = -1;
                                                                }

                                                                if(!isSet(LOTGD.popMenu) || !isSet(LOTGD.Hint)) {
                                                                        first_opened = hid;
                                                                        new libLoadWaiter("popmenu",[room_men],true);
                                                                        return;
                                                                }

                                                                if(men_vis == -1) {
                                                                        LOTGD.addEvent(document, "click", function (){if(men_vis==true) {room_men(-1);}});
                                                                        men_vis = false;
                                                                }

                                                                var index = -1;
                                                                if(hid > -1) {
                                                                        for(i=0;i<mens.length;i++) {
                                                                                if(mens[i].m_hid == hid) {
                                                                                        index = i;
                                                                                        break;
                                                                                }
                                                                        }

                                                                        if(index == -1) {
                                                                                for(i=0;i<rooms.length;i++) {
                                                                                        if(rooms[i][0] == hid) {
                                                                                                index = i;
                                                                                                break;
                                                                                        }
                                                                                }
                                                                                var last_loc = -1;
                                                                                i = mens.length;
                                                                                mens[i] = new LOTGD.popMenu();
                                                                                mens[i].m_container = document.getElementById("main_content");
                                                                                mens[i].m_hid = rooms[index][0];
                                                                                mens[i].addItem(         new LOTGD.MenuItem ( {type:MIT_LABEL, label:rooms[index][1]} ),
                                                                                                                        new LOTGD.MenuItem( {type:MIT_BREAK} )
                                                                                                                );
                                                                                if(rooms[index][2]) {
                                                                                        mens[i].addItem( new LOTGD.MenuItem ( {type:MIT_NORMAL, label:"Haus betreten", link:"inside_houses.php?outside=1&id="+rooms[index][0]} ),
                                                                                                                        new LOTGD.MenuItem ( {type:MIT_NORMAL, label:"Schlafen (LogOut)", link:"login.php?op=logout&loc='.USER_LOC_HOUSE.'&restatloc="+rooms[index][0]} )
                                                                                                                        );
                                                                                }

                                                                                for(j=3; j<rooms[index].length; j++) {
                                                                                        if(last_loc != rooms[index][j][3]) {
                                                                                                loc_name = "Erdgeschoß";
                                                                                                switch(rooms[index][j][3]) {
                                                                                                        case "'.HOUSES_ROOM_BASEMENT.'": loc_name = "Keller"; break;
                                                                                                        case "'.HOUSES_ROOM_1ST.'": loc_name = "1. Stock"; break;
                                                                                                        case "'.HOUSES_ROOM_2ND.'": loc_name = "2. Stock"; break;
                                                                                                        case "'.HOUSES_ROOM_ROOF.'": loc_name = "Dachgeschoß"; break;
                                                                                                        case "'.HOUSES_ROOM_TOWER.'": loc_name = "Turmgeschoß"; break;
                                                                                                }
                                                                                                mens[i].addItem(
                                                                                                                        new LOTGD.MenuItem( {type:MIT_BREAK} ),
                                                                                                                        new LOTGD.MenuItem( {type:MIT_LABEL, label:loc_name} )
                                                                                                                        );
                                                                                                last_loc = rooms[index][j][3];
                                                                                        }
                                                                                        if(rooms[index][j][2].length) {
                                                                                                men_it = new LOTGD.MenuItem ( {type:MIT_NORMAL, label:rooms[index][j][1], link:"house_extensions.php?_ext_id="+rooms[index][j][0], hint:"Gehört "+rooms[index][j][2]} );
                                                                                        }
                                                                                        else {
                                                                                                men_it = new LOTGD.MenuItem ( {type:MIT_NORMAL, label:rooms[index][j][1], link:"house_extensions.php?_ext_id="+rooms[index][j][0]} );
                                                                                        }
                                                                                        mens[i].addItem( men_it );
                                                                                }
                                                                                mens[i].showAt();
                                                                                index = i;
                                                                        }
                                                                }

                                                                i = 0;
                                                                while(i < mens.length) {
                                                                        mens[i].setVisibility(false);
                                                                        i++;
                                                                }

                                                                men_vis = false;

                                                                if(index > -1) {
                                                                        mens[index].show();
                                                                        window.setTimeout("men_vis=true;",100);
                                                                }
                                                        }
                                                        ');
                }
                // END Gemächer, falls nötig

                // Navs anlegen
                $str_hids = implode('|',array_keys($arr_access_tohouse));
                // Zum Logout
                addpregnav('/login.php\?op=logout&loc='.USER_LOC_HOUSE.'&restatloc=('.$str_hids.')/');
                // Ins Haus
                addpregnav('/inside_houses.php\?outside=1&id=('.$str_hids.')/');
                // In die Gemächer
                addpregnav('/house_extensions.php\?_ext_id=('.$str_room_ids.')/');
                unset($str_hids);
                unset($str_room_ids);

                if ($rebuy==1)
                {
                        addnav('Verkauf rückgängig','houses.php?op=buy&id='.$session['user']['house']);
                }
                if (getsetting('dailyspecial',0)=='Waldsee')
                {
                        $str_out .= '`n`n`(Während du deine Schlüssel suchst, fällt dir ein kleiner `GT`ar`Ya`;mpelp`Yf`aa`Gd `(auf...`0';
                        addnav('');
                        addnav('Trampelpfad','forestlake.php');
                }

                $accesskeys['w']=0;$accesskeys['d']=0;$accesskeys['m']=0;
                addnav('Zurück');
                addnav('d?Zum Stadtzentrum','village.php');
                addnav('M?Zum Marktplatz','market.php');
                addnav('W?Zum Wohnviertel','houses.php');
                addnav('Zur Hauptstraße','mainstreet.php');

                $str_output .= $str_out;
        break;

        case 'set_house_order':
                $str_output = get_title('Hauszugänge sortieren').'`0Hier hast du die Möglichkeit, deine Hausschlüssel neu anzuordnen.`n';
                $str_output = keylist_set_sort_order('k.id,h.housename AS name,k.house_sort_order AS sort_order, h.status', 'k.owner='.$session['user']['acctid'].' AND h.owner!='.$session['user']['acctid'].' AND type='.HOUSES_KEY_DEFAULT,false,'house_sort_order',true);
                addnav('Zurück zum Wohnviertel',$str_filename.'?op=enter');
        break;

        default:
                $show_invent = true;

                $str_output .= '`c`b`&D`ea`Ys `;W`So`Nhn`Svi`;e`Yr`et`&el`0`b`c`n';
                $session['housekey']=0;

                $bool_houseenter_nav = false;
                if($session['user']['house'] > 0) {
                        $bool_houseenter_nav = true;
                }
                else {
                        $sql = 'SELECT id FROM keylist WHERE owner='.$session['user']['acctid'].' ORDER BY id ASC LIMIT 1';
                        if(db_num_rows(db_query($sql))) {
                                $bool_houseenter_nav = true;
                        }
                }


                if ($bool_houseenter_nav)
                {
                        addnav('Wohnviertel');
                        addnav('Haus betreten','houses.php?op=enter');
                }

                addnav('Aktionen');
                if ($session['user']['house'])
                {
                        addnav('-?Haus verkaufen','houses.php?op=sell');
                }
                else
                {
                        if (!$session['user']['house'] )
                        {
                                addnav('Haus kaufen','houses.php?op=buy');
                        }

			addnav('Haus bauen','houses.php?op=build');
                }

                addnav('Zurück');
                addnav('d?Zum Stadtzentrum','village.php');
                addnav('M?Zum Marktplatz','market.php');
                addnav('Zur Hauptstraße','mainstreet.php');
              
                addnav('Sonderbare Orte');
                addnav('Der Spielplatz','spielplatz.php');
                addnav('b?Dorfbrunnen','well.php');
                addnav('k?Dunkle Gasse','slums.php');

                $bool_admin = $access_control->su_check(access_control::SU_RIGHT_COMMENT);

                // Rassenräume
                $arr_race = race_get($session['user']['race']);

                // Wenn Rassenraum im Wohnviertel
                if($arr_race['raceroom'] == 2) {
                        addnav($arr_race['raceroom_nav'],'racesspecial.php?race='.$arr_race['id']);
                }

                // Wenn Spieler alle Rassenräume betreten kann
                if($arr_race['raceroom_all'] || $access_control->su_check(access_control::SU_RIGHT_COMMENT)) {

                        $sql = 'SELECT id,raceroom_nav,raceroom FROM races WHERE raceroom=2 AND id != "'.$session['user']['race'].'"';
                        $res = db_query($sql);

                        while($r = db_fetch_assoc($res)) {
                                addnav($r['raceroom_nav'],'racesspecial.php?race='.$r['id']);
                        }

                }

                $str_output .= '`;M`Ye`eh`&rere verwinkelte Gassen, aber auch große Hauptwege führen fort von den viel bevölkerten Plätzen in das Wohnviertel von '.getsetting('townname','Atrahor').'. Riesige, prächtige Bauten reihen sich ebenso wie heruntergekommene, verwahrloste Bruchbuden nebeneinander auf. Die meisten der Wege sind gut gepflegt und mit Blumenbeeten oder Bäumen versehen, um dem Viertel einen harmonischen Anblick zu verleihen. Hier richten sich die Bürger der Stadt häuslich ein und verbringen wohl die Nacht oder auch den Tag zurückgezogen vor dem lauten Alltag in Atra`eh`Yo`;r.`0 ';

                if ($session['user']['house'])
                {
                        $str_output .= '`n`SS`;t`Yolz schwingst du den `^Schlüssel`Y zu deinem eigenen Haus im Gehen hin und h`;e`Sr.`n';
                }

                include_once('houses_view.inc.php');

                $search = houses_view_get_search();

                $arr_p_inf = page_nav('houses.php','SELECT COUNT(*) AS c FROM houses WHERE '.$search.' 1 ',9,'','');

                $str_output .= '`n`c<div class="trlight" style="width:400px;border-style:inset;border-width:1px;">'.plu_mi('hsearch',(empty($search) ? 0 : 1),false).' `bSuche:`b';

                $str_output .= '`n<div id="'.plu_mi_unique_id('hsearch').'" style="padding:20px;width:400px;text-align:left;'.(empty($search) ? 'display:none;' : '').'">
                                                        '.form_header('houses.php').'Nach Hausname oder Nummer <input name="search" value="'.stripslashes(utf8_htmlentities($_REQUEST['search'])).'"> <input type="submit" class="button" value="Suchen"></form>`n
                                                        '.form_header('houses.php').'Nach Hausart
                                                        <select name="htype" size="1">
                                                        <option value="0">Wohnhaus</option>
                                                        ';
                if(!isset($_REQUEST['htype']))$_REQUEST['htype']=null;
                foreach ($g_arr_house_builds as $int_id=>$arr_b) {
                        $str_output .= '<option value="'.$int_id.'" '.($int_id == $_REQUEST['htype'] ? 'selected="selected"':'').'>'.$arr_b['name'].'</option>';
                }
                $str_output .= '</select> <input type="submit" class="button" value="Suchen"></form>`n
                                                '.form_header('houses.php').'Häuser zum Verkauf <input name="tosell" value="1" type="hidden"> <input type="submit" class="button" value="Suchen"></form>`n
                                                </div></div>`c';

                // Straßenangabe nur anzeigen, wenn keine Suche gestartet
                if(empty($search)) {
                        $str_output .= '`n`n`SI`;n`Ysgesamt gibt es in '.getsetting('townname','Atrahor').' '.$arr_p_inf['maxpage'].' Straßen. Willst du eine bestimmte davon aufsuch`;e`Sn?`n
                                                        <div>'.form_header('houses.php').'`&J`ea, `)gehe zur <input name="page" type="text" maxlength="3" size="3" value="'.$arr_p_inf['page'].'">. Stra`eß`&e! <input type="submit" value="Los"></form></div>';
                }
                else {
                        $str_output .= '`0`n`c&raquo; '.create_lnk('`&Alle Häuser '.getsetting('townname','Atrahor').'s ansehen!`0','houses.php').'`c`n';
                }

                //$str_output .= '</form>';

                $str_output .= '
                <div align="center" id="h_t">
                '.houses_view_get_out($arr_p_inf['page'],$arr_p_inf['maxpage'],$search);

                $str_lnk = 'houses_httpreq.php?op=h_page&maxp='.$arr_p_inf['maxpage'].'&search='.urlencode($str_raw_search)
                                        .($int_htype > -1 ? '&htype='.$int_htype : '')
                                        .($bool_tosell ? '&tosell=1' : '')
                                        .'&p=';
                addpregnav('/'.utf8_preg_quote($str_lnk).'[\d]{1,3}/');

                $str_output .= '
                </div>
                '.JS::encapsulate('
                        '.jslib_httpreq_init().'
                        var g_houses = Array();
                        var g_streets = Array();
                        function hb (id) {
                                if(!g_houses[id]) {
                                        document.getElementById("h_bio_box").innerHTML = "<img src=\""+LOTGD.m_dir+"img/wait.gif\" alt=\"Lade..\" title=\"Lade..\">";
                                        g_req.send("houses_httpreq.php?op=h_bio&id="+id,
                                                                                        function (r) {
                                                                                                var cmd = LOTGD.getCommandFromRequest(r);
                                                                                                if( !LOTGD.parseCommand(cmd) ){
                                                                                                        g_houses[id] = parse(r.responseXML.getElementsByTagName("root")[0].firstChild.nodeValue);
                                                                                                        document.getElementById("h_bio_box").innerHTML = g_houses[id];
                                                                                                }
                                                                                        },
                                                                                        function () {MessageBox.show("Es gibt gerade Probleme. Bitte schreibe eine Anfrage!");},
                                                                                        null,
                                                                                        null);
                                }
                                else {
                                        document.getElementById("h_bio_box").innerHTML = g_houses[id];
                                }

                        }

                        function hs (p) {
                                if(!g_streets[p]) {
                                        document.getElementById("h_t").innerHTML = "<img src=\""+LOTGD.m_dir+"img/wait.gif\" alt=\"Lade..\" title=\"Lade..\">";
                                        g_req.send("'.$str_lnk.'"+p,
                                                                                        function (r) {
                                                                                                var cmd = LOTGD.getCommandFromRequest(r);
                                                                                                if( !LOTGD.parseCommand(cmd) ){
                                                                                                        g_streets[p] = parse(r.responseXML.getElementsByTagName("root")[0].firstChild.nodeValue);
                                                                                                        document.getElementById("h_t").innerHTML = g_streets[p];
                                                                                                }
                                                                                        },
                                                                                        function () {MessageBox.show("Es gibt gerade Probleme. Bitte schreibe eine Anfrage!");},
                                                                                        null,
                                                                                        null);
                                }
                                else {
                                        document.getElementById("h_t").innerHTML = g_streets[p];
                                }
                        }
                        '.(isset($_GET['ret_id']) ? 'hb('.(int)$_GET['ret_id'].');' : '')).
                    JS::event('.hs','click','var id = atrajQ(this).data("id"); hs(id);').JS::event('.hb','click','var id = atrajQ(this).data("id"); hb(id);');

                addpregnav('/houses_httpreq.php\?op=h_bio&id=[\d]+/');
                break;
}

output($str_output,true);
page_footer();
?>