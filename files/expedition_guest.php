<?php

require_once 'common.php';
require_once(LIB_PATH.'profession.lib.php');
music_set('expedition');
addcommentary(false);
checkday();

if ($session['user']['alive']==0)
{
	redirect('shades.php');
}

$session['user']['specialinc']='';
$session['user']['specialmisc']='';

page_header('Expedition in die dunklen Lande');

switch ($_GET['op'])
{
  
        case 'briefing' : //Info: Der Auftrag
        {
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
                addnav('Zurück','expedition_guest.php');
                break;
        }
        case 'recruit' : //Info: Rekrutierungsliste
        {
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
   
                addnav('Zurück','expedition_guest.php');
                break;
        }
		
		case 'rules' : //Info: Regeln für die Expedition
        {
                output('`c`b`IDie Regeln, die der Oberst der Expedition diktiert lauten aktuell:`0`b`c`n`n');
                output('`IRegeln für das Spiel in der Expedition`n`n
`I1. `0Die Bürgerwehr bietet geleitete Massenrollenspiele an, die sich thematisch um Krieg und Schlachten drehen. Dein Charakter sollte sich in ein freiwilliges Militär einfügen können und über entsprechende Fähigkeiten verfügen.`n
`I2. `0Die Mitglieder werden intern vorgeschlagen und diskutiert. Es ist auch möglich sich bei einem Oberst zu bewerben.`n
`I3. `0Die Einladung kann bei Inaktivität und Fehlverhalten zurück gezogen werden.`n
`I4. `0Multispiel ist nicht erlaubt.`n
`n`n');
                
                addnav('Zurück','expedition_guest.php');
                break;
        } 

case 'propose' : //Helden vorschlagen
        {
                require_once(LIB_PATH.'board.lib.php');
                if($_GET['board_action'] == 'add') {
                        board_add('expig_new');
                }
                board_view('expig_new',1,'Folgende Helden haben sich beworben:','Es haben sich noch keine Helden beworben!',true,true);
                output('`n`n`&Möchtest du dich selbst als Helden vorschlagen? Dann schreib deinen Namen auf einen Zettel und häng ihn an das Brett:');
                board_view_form('Vorschlagen!','');
                output('`n`n');
                viewcommentary('expedition_guest_recruit','`nHier kannst du über die Bewerbungen diskutieren.',25,"sagt");
                addnav('Zurück','expedition_guest.php');
                break;
        }

		default:
		addnav('Expedition');
		addnav('Regeln für die Expedition','expedition_guest.php?op=rules');
		//addnav('Als Held bewerben','expedition_guest.php?op=propose');
		addnav('Information');
		addnav('Der Auftrag','expedition_guest.php?op=briefing');
		addnav('Rekrutierungsliste','expedition_guest.php?op=recruit');
		addnav('Zurück');
		addnav('Atrahor','village.php');
		output("`c`b`fD`ea`)s `SBesucher-Z`;el`Ytla`;ge`Sr `)der Expedition in die `Ndunklen L`(a`)n`ed`fe`0`b`c`n`fN`ea`)c`(h`N l`Sa`;ngem Ritt, weit hinaus - weg von ".getsetting('townname','Atrahor')." - lässt du dich erschöpft vom Rücken des Reittieres gleiten und kommst sanft auf dem Grasboden auf. Du hast die hölzernen Wälle passiert, die von unzähligen Wachtürmen unterbrochen werden, auf denen Soldaten die Ebene nach Feinden ausspähen. Zwei große, hölzerne Tore ermöglichen es den Kriegern, das Lager zu betreten. Ein Knappe eilt herbei und bringt dein Tier zu einem Unterstand. Endlich hast du Zeit, das Zeltlager näher zu erkunden und näherst dich zuerst der Stelle, von der aus du den meisten Lärm vernimmst: dem Gemeinschaftszelt. Auf dem Weg dorthin gehst du an mehreren Zelten vorbei, deren Eingänge jeweils von zwei Wachen umstellt sind. Aus einem hörst du gedämpfte Gespräche, die anscheinend von den Leitern der Expedition stammen und deshalb nicht für deine Ohren bestimmt sind. Aus einem anderen vernimmst du metallisches Klirren, so als würden Waffen und Rüstungen gestapelt werden. Bevor du das größte Zelt erreichst, betrachtest du kurz die Umgebung, in der das Lager errichtet wurde: Die Zelte sind auf einer Seite umgeben von den Steilklippen eines kleinen Gebirges, von denen sich vereinzelt ein Wasserfall seinen Weg zu einem See am Fuße des Felsens sucht. Als du den Blick zur anderen Seite wendest blickst du auf eine scheinbar endlos weite Ebene. Einzelne Bäume kannst du lediglich am Rande des Sees ausmachen. Doch am meisten verwirrt dich der immer wolkenverhangene, dunkle Himmel, der das ganze Land in einen unheimlichen Schatte`Sn `Nh`(ü`)l`el`ft...`0`n`n");
		viewcommentary('expedition_guest','Sagen',25,"sagt");
		
		break;

      
}

page_footer();
?>