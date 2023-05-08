<?php
/**
 * Diese Datei enthält Informationen über LotGD, Atrahor und das Spiel im Allgemeinen
 *
 * @author Atrahor Team
 */

require_once 'common.php';
page_header('Über '.getsetting('townname','Atrahor').' das Legend of the Green Dragon basierende Spiel in der Dragonslayer Edition');
checkday();
$int_ref=intval($_GET['r']);
$str_ref=($int_ref>0?'?r='.$int_ref:'');
$str_ref2=($int_ref>0?'&r='.$int_ref:'');

if($_GET['op']=='setup')
{
	$time = gametime();

	// by Moonraven
	$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
	$today = mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time));
	$dayduration = ($tomorrow-$today) / getsetting('daysperday',4);
	$secstotomorrow = $tomorrow-$time;
	$secssofartoday = $time - $today;
	$realsecstotomorrow = round($secstotomorrow / getsetting('daysperday',4),0);
	$realsecssofartoday = round($secssofartoday / getsetting('daysperday',4),0);

	$setup = array(
	'Spieleinstellungen,title',
	'pvp'=>'Spieler gegen Spieler erlaubt,viewonly',
	'pvpday'=>'Erlaubte Anzahl Spielerkämpfe pro Tag,viewonly',
	'pvpimmunity'=>'Tage die Spieler sicher vor PvP sind,viewonly',
	'pvpminexp'=>'Nötige Erfahrungspunkte bevor ein Spieler im PvP angreifbar wird,viewonly',
	'soap'=>'Spielerbeiträge "bereinigen" (Wortfilter),viewonly',
	'newplayerstartgold'=>'Startmenge an Gold für neue Charaktere,viewonly',
	'avatare'=>'Avatare erlaubt?,viewonly',
	'maxonline'=>'Maximal gleichzeitig online (0 für unbegrenzt),viewonly',

	'Neue Tage,title',
	'fightsforinterest'=>'Um Zinsen zu bekommen muss ein Spieler weniger Waldkämpfe haben als,viewonly',
	'maxinterest'=>'Maximaler Zinssatz (%),viewonly',
	'mininterest'=>'Minimaler Zinssatz (%),viewonly',
	'daysperday'=>'Spieltage pro Kalendertag,viewonly',
	'specialtybonus'=>'Extras des Spezialgebiets täglich einsetzen,viewonly',

	'Handelseinstellungen,title',
	'borrowperlevel'=>'Maximum das ein Spieler pro Level leihen kann,viewonly',
	'transferperlevel'=>'Maximum das ein Spieler pro Level des Empfängers überweisen kann,viewonly',
	'mintransferlev'=>'Mindestlevel für Überweisungen,viewonly',
	'transferreceive'=>'Überweisungen die ein Spieler pro Tag empfangen darf,viewonly',
	'maxtransferout'=>'Absolutes Maximum das ein Spieler pro Tag und Level überweisen darf,viewonly',

	'Kopfgeld,title',
	'bountymin'=>'Mindestbetrag pro Level der Zielperson,viewonly',
	'bountymax'=>'Maximalbetrag pro Level der Zielperson,viewonly',
	'bountylevel'=>'Mindestlevel um Opfer sein zu können,viewonly',
	'bountyfee'=>'Gebühr für Dag Durnick in Prozent,viewonly',
	'maxbounties'=>'Anzahl an Kopfgeldern die ein Spieler pro Tag aussetzen darf,viewonly',

	'Wald,title',
	'turns'=>'Waldkämpfe (Züge) pro Tag,viewonly',
	'dropmingold'=>'Waldbewohner lassen wenigstens 1/4 des möglichen Golds fallen,viewonly',
	'lowslumlevel'=>'Mindestlevel zum Herumstreifen,viewonly',

	'Mail Einstellungen,title',
	'mailsizelimit'=>'Maximale Nachrichtengröße,viewonly',
	'inboxlimit'=>'Maximale Anzahl an Nachrichten in der Inbox,viewonly',
	'oldmail'=>'Alte Nachrichten werden automatisch gelöscht nach Tagen,viewonly',

	'Inhaltsverfallsdatum (0 für keines),title',
	'expirecontent'=>'Tage die Kommentare und Neuigkeiten aufgehoben werden,viewonly',
	'expiretrashacct'=>'Accounts die sich nie eingeloggt haben werden nach x Tagen gelöscht. x =,viewonly',
	'expirenewacct'=>'Level 1 Charaktere ohne Heldentat werden nach x Tagen gelöscht. x =,viewonly',
	'expireoldacct'=>'Alle anderen Accounts werden nach x Tagen Inaktivität gelöscht. x =,viewonly',


	'Nützliche Infos,title',
	'Tageslänge: '.round(($dayduration/60/60),0).' Stunden,viewonly',
	'Aktuelle Serveruhrzeit: '.date('Y-m-d h:i:s a').',viewonly',
	'Letzter neuer Tag: '.date('h:i:s a',strtotime(date('r').'-'.$realsecssofartoday.' seconds')).',viewonly',
	'Aktuelle Spielzeit: '.getgametime(true).',viewonly',
	'Nächster neuer Tag: '.date('h:i:s a',strtotime(date('r').'+'.$realsecstotomorrow.' seconds')).' ('.date('H\\h i\\m s\\s',strtotime('1980-01-01 00:00:00 + '.$realsecstotomorrow.' seconds')).'),viewonly',
	);

	output("`@<h3>Einstellungen für diesen Server</h3>`n`n",true);
	showform($setup,$settings,true);

}
else
{
	output(get_extended_text('about_server'),true);
}

if ($session['user']['loggedin'])
{
	addnav('Zurück zu den News','news.php');
}
else
{
	addnav('Login','index.php'.$str_ref);
}

addnav('Informationen');
addnav('Über '.getsetting('townname','Atrahor'),'about_server.php'.$str_ref);
addnav('');
addnav('Spieleinstellungen','about_server.php?op=setup'.$str_ref2);

page_footer();
?>
