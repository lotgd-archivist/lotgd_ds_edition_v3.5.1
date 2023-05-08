<?php
/*********************************************
Lots of Code from: lonnyl69 - Big thanks for help!
By: Kevin Hatfield - Arune v1.0
Written for Fishing Add-On - Poseidon Pool
06-19-04 - Public Release

Translation and simple modifications by deZent deZent@onetimepad.de


ALTER TABLE accounts ADD wormprice int(11) unsigned not null default '0';
ALTER TABLE accounts ADD minnowprice int(11) unsigned not null default '0';
ALTER TABLE accounts ADD wormavail int(11) unsigned not null default '0';
ALTER TABLE accounts ADD minnowavail int(11) unsigned not null default '0';
ALTER TABLE accounts ADD trades int(11) unsigned not null default '0';
ALTER TABLE accounts ADD worms int(11) unsigned not null default '0';
ALTER TABLE accounts ADD minnows int(11) unsigned not null default '0';
ALTER TABLE accounts ADD fishturn int(11) unsigned not null default '0';
add to newday.php
$session['user']['trades'] = 10;
if ($session['user'][dragonkills]>1)$session['user'][fishturn] = 3;
if ($session['user'][dragonkills]>3)$session['user'][fishturn] = 4;
if ($session['user'][dragonkills]>5)$session['user'][fishturn] = 5;
Now in village.php:
addnav("Poseidon Pool","pool.php");

translated into german by deZent
********************************************/

require_once 'common.php';
checkday();
addcommentary();

music_set('waldsee');

$show_invent = true;

page_header('Der Waldsee');
output('`c`b`WD`Be`9r `{W`wal`{d`9s`Be`We`0`b`c
`n`WA`Bu`9s `{d`wer Ferne schon kündigt ein leises Rauschen und harmonisches Vogelzwitschern einen ruhigen Ort an, dessen Idylle sich scheinbar von niemanden stören lässt. Über einen schmalen Pfad, der gesäumt ist von niedergetretenem Gras, welches von den vielen Wesen zeugt, die diesen Ort zuvor aufsuchten, gelangt man an die glitzernde Wasserfläche. Lässt man ein paar vereinzelte Bäume hinter sich, tritt man auf die Wiese hinaus, die den See umgibt. Das zarte Gras, welches die Schritte abfedert, duftet noch immer nach dem morgendlichen Tau der es benetzt und auch leichte Nebeldünste über der Wasseroberfläche sind hier nicht selten.`nDer Wind kräuselt die Wasseroberfläche nur leicht, lässt die sich spiegelnden Bilder verzerrt erscheinen, wenngleich ein Sturm diese wohl aufpeitschen könnte. Im Sommer jedoch lädt das klare Blau des Wassers höchstens zu einem erfrischenden Bad ein, während sich im Winter eine Eisschicht über das Gewässer zieht, die bei genügend Stärke wohl auch einen erwachsenen Mann tragen kann. Doch besonders sticht aus dieser Vollkommenheit der Natur ein Steg aus Holz hervor, der etwas abseits auf den See hinaus`{r`9a`Bg`Wt.`n`n');
if($session['user']['exchangequest']==5)
{
	$indate = getsetting('gamedate','0005-01-01');
	$date = explode('-',$indate);
	if(($date[1]==4 && $date[2]==30) || ($date[1]==5 && $date[2]==1))
	{
		output('`%Als du heute am See entlangläufst fällt dir ein Steinkreis auf, in dem Holz aufgeschichtet ist. Du überlegst, wer denn hier am See ein Feuer machen will. Plötzlich fällt es dir wie Schuppen von den Augen: Heute ist Beltane! Sicher werden sich bald einige Hexen und Magiere hier einfinden um eine Räucherung zu zelebrieren.`nMöchtest du den Abend am See verbringen? Dies würde dich alle verbleibenden Waldkämpfe kosten.');
		addnav('`%Verbringe den Abend am See`0','exchangequest.php');
	}
}
viewcommentary('pool', 'Sag was', 25, 'flüstert');
addnav('Waldsee');
if ($session['user']['dragonkills']>1)addnav('Angelshop','bait.php');
addnav('S?Zum Steg','fish.php');

if($session['bufflist']['`FLanger Atem'])
{
	addnav('Tauchen','watercave.php');
}
else if(access_control::is_superuser())
{
	addnav('Tauchen(Superuser)','watercave.php?user=super');
}
else if($session['user']['race']=='mwn')
{
  addnav('Tauchen','watercave.php');
}
addnav('F?Zu den Fröschen','frogs.php');
addnav('Nebelpfad','nebelgebirge.php');
addnav('Zurück');
addnav('Zurück zum Stadtzentrum','village.php');
page_footer();
?>