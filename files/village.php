<?php
/**
 * Das Stadtzentrum ist die zentrale Anlaufstelle des Spiels.
 * Von hier aus kommt man zu allen weiteren Spielebenen
 */

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

if ($Char->alive==0)
{
	redirect('shades.php');
}
if($Char->prangerdays>0){
	redirect("pranger.php");
}

$sql='SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1='.$Char->acctid.' OR acctid2='.$Char->acctid;
$result = db_query($sql);
$row = db_fetch_assoc($result);
if(($row['acctid1']==$Char->acctid && $row['turn']==1) || ($row['acctid2']==$Char->acctid && $row['turn']==2))
{
	redirect('pvparena.php');
}

if (getsetting('automaster',1) && $Char->seenmaster!=2)
{
	$expreqd = get_exp_required($Char->level,$Char->dragonkills,true);
	if ($Char->experience>$expreqd && $Char->level<15)
	{
		redirect('train.php?op=autochallenge');
	}
	elseif ($Char->experience>$expreqd && $Char->level>=15 && e_rand(1,3) == 3 )
	{
		redirect('boss.php?boss=green_dragon&op=autochallenge');
	}
}

//Load specials
spc_get_special('village',1);

//$Char->specialinc='';
$Char->specialmisc='';//salator: Das bitte drinlassen, nutze ich für Dunkle Gasse und Grabraub

clearoutput();

// Muss nach clearoutput stehen!
music_set('dorfplatz');

$w = Weather::get_weather();
addnav('');
addnav('W?Wald','forest.php');
addnav('o?Wohnviertel','houses.php');
addnav('M?Marktplatz','market.php');
addnav('V?Vergnügungsviertel','nobelviertel.php');

if (($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ENTER)) || ($Char->expedition>0))
{
	addnav('x?Expedition','expedition.php');
}
else
{
	addnav('x?Expedition','expedition_guest.php');
}

if (($Char->dragonkills>0))
{
	addnav('G?Gildenviertel','dg_main.php');
}
else
{
	addnav('u?Raum des Lernens','library.php?op=rp_train');
}
addnav('Stadtzentrum');
addnav('T?Trainingslager','train.php');
addnav("c?Marducs Akademie","academy.php");
if (getsetting('pvp',1))
{
	addnav('a?Der Turnierplatz','pvparena.php');
}
addnav('K?Der Kerker','prison.php');

addnav('');
addnav('E?Schenke zum Eberkopf','inn.php',true);
addnav('J?Jägerhütte','lodge.php');
addnav('F?Seltsamer Felsen', 'rock.php');
addnav('D?Die große Eiche','greatoaktree.php');
addnav('-?Rathaus','dorfamt.php');

addnav('Sonstige Orte');
//addnav('Verlassenes Schloss','abandoncastle.php',false, false, false, false);		(findet sich jetzt im Wald ;))
addnav('R?Rosengarten', 'gardens.php');
addnav('s?Waldsee','pool.php');
addnav('h?Der Friedhof &dagger;&dagger;','friedhof.php');
addnav('.?Das Stadttor','dorftor.php');

//Adding the Villageparty
if((getsetting ('lastparty',0)>time()) || getsetting('party_force_party',0) == 1)
{
	addnav('P?Das Stadtfest','dorffest.php');
}
else
{
	addnav('i?Die Festwiese','dorffest.php?op=meadow');
}

CRPPlaces::addnav(4);

addnav('Information');
addnav('b?`^Drachenbücherei`0','library.php');
addnav('+?`4OOC-Bereich`0','ooc_area.php');

addnav('l?Einwohnerliste','list.php');
addnav('N?Neuigkeiten','news.php');

if($Char->prefs['showinvent'])
{
	addnav('B?Dein Beutel','invent.php?r=1');
	addnav('Profileinstellungen','prefs.php',false,true);
}

if($access_control->su_check(access_control::SU_RIGHT_GROTTO)) {
	addnav('Admin');
	addnav('<?`bAdmin Grotte`b','superuser.php');
}

if($access_control->su_check(access_control::SU_RIGHT_LIVE_DIE))
{
	addnav('Lemming spielen','superuser.php?op=iwilldie',false,false,false,false,'Möchtest Du Dich wirklich von der hohen Klippe gen Ramius Stürzen?');
}

addnav('Logout');
addnav('#?In die Felder','login.php?op=logout',true);

page_header('Stadtzentrum');
$str_output .= get_title('`TS`Yt`ta`yd`&tzentrum Atra`yh`to`Yr`Ts').'
`TD`Ye`tr `ygrößte Platz der Stadt erstreckt sich weitläufig genau im Herzen Atrahors. Das beinahe runde, sorgfältig gepflasterte Zentrum wird von vielen, wichtigen Gebäuden gesäumt, sodass der Weg zum Rathaus, der Schenke oder zum Gerichtssaal nur einem Katzensprung gleicht. Auf dem Platz sind mehrere Bänke aufgestellt, die eine kurze Rast ermöglichen, während einzelne Bäume und kleine Beete die Atmosphäre auflockern, obwohl jene nicht mit der Pracht des nahen Gartens mithalten können. Der Brunnen auf dem Platz bietet die Möglichkeit, sauberes Trinkwasser zu schöpfen und sich damit zu stärken. Regelmäßig patrouillieren hier Wachen, um für Recht und Ordnung zu sorgen und statten dabei dem Kerker jedes Mal einen kurzen Besuch ab. Das Stadtzentrum
'.getsetting('townname','Atrahor').'s lädt geradezu ein, sich mit anderen Bürgern zu treffen und das blühende Leben der Stadt zu genie`tß`Ye`Tn. `n
`n`&Ein `YS`Tc`Shi`Tl`Yd`& verbietet das Blankziehen von Waffen im Stadtzentrum unter Androhung von Kerkerhaft!`n`n
`&Zwei `rMa`_rk`[twe`_ib`rer`& stehen am Rande des Brunnens und unterhalten sich lautstark über die Neuigkeiten, die sich vor kurzem zugetragen haben:';

$fuerst_schuld = utf8_unserialize(getsetting('fuerst_schuld',''));
$restschuld = round((($fuerst_schuld['gesamt']-100000)/2500)*4)-$fuerst_schuld['paid'];
if(($restschuld > 0) && ($fuerst_schuld['days'] > 0)){
    $user_fuerst = db_get("SELECT acctid, name FROM accounts WHERE acctid='".intval($fuerst_schuld['id'])."' LIMIT 1");
    if(count($user_fuerst)){
        $anz_tomaten = getsetting('fuerst_tomaten',0);
        $row['newstext'] = $user_fuerst['name'].'`4 hat noch `^'.$fuerst_schuld['days'].'`4 Tagesabschnitte Zeit um `^'.$restschuld.'`4 von '.($fuerst_schuld['paid']+$restschuld).' ES in die Amtskasse einzuzahlen, sonst muss er Atrahor `^nackt`4 verlassen.';
        if($_GET['op'] == 'tomaten'){
            $anz_tomaten++;
            savesetting('fuerst_tomaten',$anz_tomaten);
            redirect('village.php');
        }
        $row['newstext'] .= '`n`n`&'.$user_fuerst['name'].'`@ wurde bereits mit `^'.$anz_tomaten.'`@ faulen Tomaten beworfen. '.create_lnk('Mit einer faulen Tomate bewerfen!','village.php?op=tomaten');
    }
}

if(!isset($row['newstext'])){
    $sql = "SELECT * FROM news WHERE onlyuser=0  AND accountid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_BIO).") ORDER BY newsid DESC LIMIT 1";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
}

$str_output .= '`n`n`c`i'.$row['newstext'].'`0`i`c`n';

require('village_littleevents.php');

if (getsetting('activategamedate','0')==1)
{
	$str_output .= '`tWir schreiben den `&'.getgamedate().'`t im Zeitalter des Drachen.`n';
}
$str_output .= '`tDie magische Sonnenuhr zeigt `y'.getgametime(true).'`I.`0` ';
$str_output .= '`tDas heutige Wetter: `&'.$w['name'].'`t.`0` ';

//Abfrage auf best_one=1 gesetzt, damit es keine Probleme mit best_one=2 für untote Knappen gibt
$sql = 'SELECT disciples.name AS name,disciples.level AS level ,accounts.name AS master FROM disciples LEFT JOIN accounts ON accounts.acctid=disciples.master WHERE best_one=1 LIMIT 1';
$result = db_query($sql);
if (db_num_rows($result)>0) {
	$rowk = db_fetch_assoc($result);

	$str_output .= '`n`n`0Eine kleine Statue ehrt `q'.$rowk['name'].'`0, einen Knappen der '.$rowk['level'].'. Stufe, der zusammen mit '.$rowk['master'].'`0 eine Heldentat vollbrachte.';
}

$str_output .= '`n`n`YIn der Nähe reden einige Stadtbewohner:`0`n';

output($str_output);
viewcommentary('village','Hinzufügen',25);
page_footer();
?>