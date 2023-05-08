<?php
/*
cardhouse.php: LoGD - Kartenhauserweiterung
1.11.2004
Erstellt von Trillian

19.2.2009
Komplettüberarbeitung by Salator und Umzug ins Totenreich
Spezielle Anpassungen für Atrahor, dieses Script ist auf normalen LogD NICHT lauffähig!

benötigte Datenbankerweiterungen:
ALTER TABLE `account_extra_info` ADD `maxbonestack` SMALLINT UNSIGNED NOT null ;


Aenderungen in hof.php
die folgenden Zeilen einfügen:

 elseif ($_GET['op']=='cards')
{
$order = 'DESC';
if ($_GET[subop] == 'least') $order = 'ASC';
$sql = 'SELECT name, maxbonestack AS data1 FROM accounts WHERE maxbonestack>0 AND locked=0  ORDER BY maxbonestack $order, level $order, experience $order, acctid $order LIMIT $limit';
$adverb = 'geschicktesten';
if ($_GET[subop] == 'least') $adverb = 'amateurhaftesten';
$title = 'Die $adverb Knochentürme';
$headers = array('Größter Turm');
$none = 'Es gibt noch keine "Hochstapler" in diesem Land';
display_table($title, $sql, $none, false, $headers, false);
}


*/

require_once "common.php";

checkday();

page_header("Knochentürme bauen");

$str_filename=basename(__FILE__);

output(get_title('`7Die Knochenhexe'));
if ($_GET['op']=='enter')
{
	output('`4Du betrittst die kleine Kammer und irgendetwas kommt dir merkwürdig vor. Du schaust nach links, nach rechts, nach oben, und bist dir schliesslich sicher: Die Kammer IST innen größer als außen...
	`n`nDer gesamte Boden der Kammer ist mit den `7ausgebleichten Gebeinen`4 von Verstorbenen bedeckt, einige davon wurden mit dir unbekannten Zeichen und Symbolen versehen.
	`nIn der Mitte der Kammer ist ein Pentagramm auf den Boden gemalt, an drei seiner Spitzen steht bereits ein Knochenturm.
	`n`n');
	$session['daily']['bonestacker']=0;
	$rowe = user_get_aei('witch');
	if ($rowe['witch']<getsetting('witchvisits',3))
	{
		output('`%Willst du es wagen und mit dem Bau beginnen, oder willst du diese seltsame Kammer doch lieber wieder verlassen?`n`n');
		addnav('b?Turmbau beginnen',$str_filename.'?op=build');
	}
	else
	{
		output('`%Leider hast du heute schon genug Hexen belästigt.`n`n');
	}
	addnav('Kammer verlassen',$str_filename.'?op=finish');
}

else if ($_GET['op']=='build')
{
	if ($session['daily']['bonestacker']==0)
	{
		output('`)Du beginnst mit dem Bau deines Knochenturms.');
		db_query('UPDATE account_extra_info SET witch=witch+1 WHERE acctid='.$session['user']['acctid']);
	}
	else
	{
		output('`)Du baust weiter an deinem Knochenturm.');
	}
	if (e_rand(1,20) == 1)
	{
		output('`n`4Doch die Konstruktion bricht zusammen.');
		$limit = e_rand(90,170);
		$rowe = user_get_aei('maxbonestack');
		if ($session['daily']['bonestacker'] > $rowe['maxbonestack'])
		{
			db_query('UPDATE account_extra_info SET maxbonestack='.$session['daily']['bonestacker'].' WHERE acctid='.$session['user']['acctid']);
		}
		if ($session['daily']['bonestacker']<$limit)
		{
			output('`n`RDie ganze Arbeit umsonst, frustriert verlässt du die Kammer.`n`n');
			addnav('Zurück zu den Schatten','shades.php');
		}
		else
		{
			output('`n`4Du wirst unter dem riesigen Knochenhaufen begraben.
			`nWeil du selbst nur noch ein Haufen Knochen warst wird man dich nie wiederfinden. Du `$verlierst alle Gefallen bei Ramius`4 und wirst nun als Baumaterial für Knochentürme dienen.
			`nAllerdings war das eine Erfahrung, die man nicht jeden Tag macht.`n`n');
			$session['user']['deathpower']=0;
			$session['user']['experience']*=1.02;
			addnews('`9'.$session['user']['name'].'`9 wurde unter einem riesigen Knochenhaufen begraben.');
			debuglog('verlor alle Gefallen beim Knochenturm-Bau');
			addnav('Zu den News','news.php');
		}
		unset($session['daily']['bonestacker']);
	}
	else
	{
		$session['daily']['bonestacker']+=2;
		output('`n`8Die Knochen scheinen zu halten, dein Turm besteht jetzt aus '.$session['daily']['bonestacker'].' Knochen.`n`n');
		addnav('b?Weiterbauen',$str_filename.'?op=build');
		addnav('Aufhören',$str_filename.'?op=finish');
	}
}

else if ($_GET['op']=='finish')
{
	if ($session['daily']['bonestacker'] ==0)
	{
		output('`4Dir kommt diese Kammer doch etwas ungewöhnlich vor und du machst dich lieber aus dem Staub.');
	}
	else
	{
		output('`QStolz bewunderst du deinen Knochenturm, der immerhin aus ganzen '.$session['daily']['bonestacker'].' Knochen besteht.`0`n');
		$rowe = user_get_aei('maxbonestack');
		if ($session['daily']['bonestacker'] > $rowe['maxbonestack'])
		{
			db_query('UPDATE account_extra_info SET maxbonestack='.$session['daily']['bonestacker'].' WHERE acctid='.$session['user']['acctid']);
			output('`qDiesen Turm wirst du noch lange in Erinnerung behalten.`0');
		}
	}
	unset($session['daily']['bonestacker']);
	addnav('Zurück zu den Schatten','shades.php');
}

else
{
	output('`4Du stehst vor einer kleinen Kammer. Der Geist einer typischen Hexe, lang und dünn mit langer Hakennase und einem spitzen schwarzen Hut, sitzt darin und stapelt Knochen.
	`nAls dich die Hexe bemerkt, erzählt sie dir in großen Worten von `7magischen Knochen`4 und was für große Knochentürme man aus diesen bauen kann.
	`nSie bietet dir an, es selbst einmal zu versuchen.`n`n');
	$sql = 'SELECT name
		FROM account_extra_info aei
		LEFT JOIN accounts a ON a.acctid=aei.acctid
		WHERE maxbonestack > 0
		ORDER BY aei.maxbonestack DESC, a.dragonkills ASC, aei.acctid DESC';
	$result = db_query($sql);
	
	if (db_num_rows($result) > '0')
	{
		$row = db_fetch_assoc($result);
		output('`QDen größten Knochenturm hat bis jetzt '.$row['name'].'`Q gebaut.`n`n');
	}
	
	addnav('b?Kammer betreten',$str_filename.'?op=enter');
	addnav('Zurück zu den Schatten','shades.php');
}

page_footer();

?>
