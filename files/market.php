<?php

// Splittet den Dorfplatz
// by Maris (Maraxxus@gmx.de)

require_once 'common.php';

$show_invent = true;

addcommentary();
checkday();

if ($session['user']['alive']==0)
{
	redirect('shades.php');
}

$sql='SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1='.$session['user']['acctid'].' OR acctid2='.$session['user']['acctid'];
$result = db_query($sql);
$row = db_fetch_assoc($result);
if (($row['acctid1']==$session['user']['acctid'] && $row['turn']==1) || ($row['acctid2']==$session['user']['acctid'] && $row['turn']==2))
{
	redirect('pvparena.php');
}

if (getsetting('automaster',1) && $session['user']['seenmaster']!=2)
{
	$expreqd = get_exp_required($session['user']['level'],$session['user']['dragonkills'],true);
	if ($session['user']['experience']>$expreqd && $session['user']['level']<15)
	{
			redirect('train.php?op=autochallenge');
	}
	else if ($session['user']['experience']>$expreqd && $session['user']['level']>=15 && e_rand(1,5) == 3 )
	{
			redirect('boss.php?boss=green_dragon&op=autochallenge');
	}
}

$session['user']['specialinc']='';
$session['user']['specialmisc']='';
addnav('W?Wald','forest.php');
addnav('o?Wohnviertel','houses.php');
addnav('d?Stadtzentrum','village.php');
addnav('V?Vergnügungsviertel','nobelviertel.php');
if (($access_control->su_check(access_control::SU_RIGHT_EXPEDITION_ENTER)) || ($Char->expedition>0))
{
	addnav('x?Expedition','expedition.php');
}
else
{
	addnav('x?Expedition','expedition_guest.php');
}
addnav('G?Gildenviertel','dg_main.php');

addnav('Handel');
if (getsetting('vendor',0)==1)
{
	addnav('h?Wanderhändler','vendor.php');
}
else if ($access_control->su_check(access_control::SU_RIGHT_VENDOR_ENTER))
{
	addnav('h?Wanderhändler (für SU)','vendor.php');
}
else
{
	addnav('keine Spur vom Wanderhändler','');
}

addnav('T?Thorims Waffen','weapons.php');
addnav('P?Phaedras Rüstungen','armor.php');
addnav('S?Mericks Ställe','stables.php');
addnav('J?Johannas Kräuterlädchen','herbalist.php');
addnav('B?Die alte Bank','bank.php');
addnav('Z?Vessas Zelt','gypsy.php');

addnav('Marktplatz');
//addnav('a?Goldpartner','goldpartner.php');
addnav('k?Nadelflinks Nähstube','dressmaker.php');
addnav('Der Bücherladen','bookstore.php');
addnav('i?Die Parfümerie','perfume.php');
addnav('ä?Der Bäcker','bakery.php');
addnav('Der Metzger','butcher.php');
addnav('Der Barbier','barber.php');
addnav('r?Patisserie','coffeehouse.php');

CRPPlaces::addnav(2);

addnav('');
addnav("e?Der Bettelstein","beggar.php");
addnav("Der Goldschrein","downthedrain.php");
if(($session['user']['acctid'] != getsetting('demouser_acctid',0)))addnav("Q?Zum Quizmaster","trivia.php");

// Schnapper Mod by Romulus
if ($_GET['op']!='schnapper')
{
	if (e_rand(1,10)<=3)
	{
			addnav('Schnapper, der Händler','schnapper.php',false,false,false,false);
	}
}
//Adding the Villageparty
if (getsetting('lastparty',0)>time())
{
	addnav('f?Das Stadtfest','dorffest.php');
}

addnav('Information');
addnav('c?`^Drachenbücherei`0','library.php');
addnav('+?`4OOC-Bereich`0','ooc_area.php');

addnav('l?Einwohnerliste','list.php');
addnav('N?Neuigkeiten','news.php');

addnav('Logout');
addnav('#?In die Felder','login.php?op=logout',true);

page_header('Marktplatz');
$str_output = '';
$str_output .= get_title('`/D`te`Ir `uM`Ua`mrkt`Up`ul`Ia`tt`/z`0').
'`uZ`Ia`/h`tlreiche Stände mit allerlei Ware schmücken den großen Platz, welcher in seiner runden Form an das Stadtzentrum angrenzt und stets von zahlreichen Händlern besucht ist. Diese haben ihre Stände am Rand des Marktes aufgebaut und versuchen ihre Waren zumeist lautstark und überzeugend unter die Bewohner und Reisenden zu bringen. Leider stellen sich auch einige Betrüger geschickt an, die ihre untaugliche Ware ebenso anpreisen und gut verkaufen wie ihre rechtschaffenen Konkurrenten. Auch der Wanderhändler schlägt hier von Zeit zu Zeit sein Lager auf.
Allerdings befindet sich auch ein ganz besonderer Händler hier: Mericks Ställe sind kaum zu verkennen, denn um seinen Holzstall machen alle anderen Händler einen großen Bogen. Der Gestank der Tiere ist unverkennbar... Aber auch noch andere Läden und Gebäude wirst du hier auf dem Marktplatz finden, die für eine Stadt von der Größe '.getsetting('townname','Atrahor').'s unabkömmlich sind.
Solltest du nicht nur einkaufen wollen, kannst du dich natürlich auch auf einer der vielen Sitzgelegenheiten niederlassen, die rund um den Platz aufgestellt s`/i`In`ud.`n`0';

$sql = "SELECT * FROM news WHERE onlyuser=0  AND accountid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_BIO).") ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$str_output .= '`n`tE`/i`In`uer der Marktschreier hat es sich zur Aufgabe gemacht, die neusten Nachrichten in einer unglaublichen Lautstärke zu verbreiten, die jedoch kaum Interesse fin`Id`/e`tn`0:`n`n`c`i'.$row['newstext'].'`0`i`c`n';

switch (e_rand(1,1500))
{
	case 100 :
	case 101 :
			if($session['user']['gems']<500)
			{
					$str_output .= '`n`^Du findest einen Edelstein vor dir auf dem Boden, den du natürlich sofort einsteckst!`n`n`@';
					$session['user']['gems']++;
			}
			else
			{
					$str_output .= '`n`$Dir fällt ein Edelstein aus der Tasche, was du jedoch erst später bemerkst. Den Edelstein zu suchen ist aussichtslos, den hat sicher schon jemand anderes gefunden.`n`n`@';
					$session['user']['gems']--;
			}
			break;
	case 150 :
	case 151 :
	case 152 :
			if ($session['user']['gold']>0)
			{
					$goldlost=ceil($session['user']['gold']*0.15);
					$str_output .= '`n`4Jemand rempelt dich an und entfernt sich unter wortreicher Entschuldigung rasch. Dann stellst du fest, dass man dir '.$goldlost.' Gold gestohlen hat!`n`n`@';
					$session['user']['gold']-=$goldlost;
					debuglog('wurde von Taschendieben um '.$goldlost.' Gold erleichtert');
			}
			break;
	case 200 :
	case 201 :
	case 202 :
			if ($session['user']['turns']>0)
			{
					$str_output .= '`n`^Jemand kommt dir gut gelaunt entgegen gelaufen und reicht dir ein Ale. Deine Laune bessert sich dadurch und du hast heute eine Runde mehr!`n`n`@';
					$session['user']['turns']++;
			}
			break;
	case 250 :
	case 251 :
			$str_output .= '`n`4Jemand rennt eilig vor einer Stadtwache davon und stößt dich grob bei Seite, da du ihm im Weg stehst. Du stürzt und landest mit dem Gesicht in einem Kuhfladen. Leute drehen sich zu dir um und zeigen lachend auf dich. Du verlierst einen Charmepunkt!`@`n`n';
			$session['user']['charm']=max(0,$session['user']['charm']-1);
			break;
		default:
			if($session['user']['age'] == 1 && !$session['reloffered'] && e_rand(1,4) == 1) {		// Gerade Heldentat gemacht
					// Überprüfen, ob noch nicht alle Reliquien vergeben, unser Freund noch keinen Steckbrief und auch noch kein Angebot bekommen hat
					if( item_count('tpl_id="drstb" AND owner='.$session['user']['acctid']) == 0 && item_count('tpl_id="drrel_ksn" OR tpl_id="drrel_gld"') < 2) {
							redirect('marketevents.php?op=rel');
					}
		}
}

$w = Weather::get_weather();

if (getsetting('activategamedate','0')==1)
{
	$str_output .= '`tWir schreiben den `&'.getgamedate().'`t im Zeitalter des Drachen.`n';
}
$str_output .= 'Die Uhr an einer großen Säule zeigt `&'.getgametime(true).'`t. ';
$str_output .= 'Das heutige Wetter: `&'.$w['name'].'`0.';


// Die Mauer (by Maris)
$message=getsetting('wall_msg','0');
$time=getsetting('wall_chgtime','0');

$oldtime=(strtotime($time));
$acttime=(strtotime(date('Y-m-d H:i:s')));
$newtime=$acttime-$oldtime;
//Farbe bereits trocken ?

$wallchangetime=getsetting('wallchangetime','300');
//Zeit zwischen den Änderungen

$str_output .= '`n`n`&Dein Blick fällt auf eine hüfthohe Mauer aus weißen Ziegeln. ';
if ($message=='0')
{
	$str_output .= 'Sie muss gerade frisch angestrichen worden sein.`n';
	if ($newtime>$wallchangetime)
	{
		$str_output .= create_lnk('Die Mauer beschmieren','whitewall.php?op=write');
	}
}
else
{
	$str_output .= 'Jemand hat in großen Buchstaben darauf geschmiert:`n`^'.$message.'`n`0';
	if ($newtime>$wallchangetime)
	{
		$str_output .= create_lnk('Überschmieren','whitewall.php?op=write');
		$author=getsetting('wall_author','0');
		if ($session['user']['login']!=$author)
		{
			$str_output .= ' | ';
			$str_output .= create_lnk('Verändern','whitewall.php?op=change');
		}
	}
}
if($newtime>7200 && $message!='0') //Nach 2 Stunden wird die Mauer neu gestrichen (gelöscht)
{
	savesetting('wall_author','0');
	savesetting('wall_chgtime',date('Y-m-d H:i:s'));
	savesetting('wall_msg','0');
}
if ($_GET['op']=='toolate')
{
	if ($newtime<60)
	{
		$str_output .= '`4Es muss dir jemand zuvor gekommen sein. Die Farbe ist zu feucht um jetzt überschrieben zu werden.`0`n';
	}
	else
	{
		redirect('market.php');
	}
}
// Mauer Ende

$str_output .= '`n`n`%`0In der Nähe hörst du einige Leute schwatzen:`n';
output($str_output);
viewcommentary('marketplace','Hinzufügen',25);
page_footer();
?>