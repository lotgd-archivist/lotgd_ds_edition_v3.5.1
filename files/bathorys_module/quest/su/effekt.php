<?php
require_once('inc/global.php');

function editorprocess($r,$filename,$i)
{
    /** @noinspection PhpUndefinedVariableInspection */
    return '<tr class="'.($i%2?'trdark':'trlight').'" style="'.( ($r['activ']) ? '' : 'background:#777;').'">
				<td><b>'.$r['id'].'</b></td>
				<td><b>'.$r['name'].'</b></td>
				<td><b>'.$r['description'].'</b></td>

				<td><b>'.($r['activ'] ? '<a href="'.$filename.'op='.$_GET['op'].'&do=deac&id='.$r['id'].'">Deak</a>' : '<a href="'.$filename.'op='.$_GET
    ['op'].'&do=ac&id='.$r['id'].'">Akt</a>').'</b></td>
				<td><b><a href="'.$filename.'op='.$_GET['op'].'&sop=edit&id='.$r['id'].'">Edit</a></b></td>
				</tr>';	
}

$name = 'Effekt';
$table = ' quest_effekte ';
$orderby = " ORDER BY name";

$head[] = 'Allgemein,title';
$head[] = 'Name,divider';
$head['name'] = 'Name,text,255';
$head[] = 'Beschreibung,divider';
$head['description'] = 'Interne Beschreibung,textarea,50,10';

$head[] = 'Zähler,title';

$head[] = 'Zähler,divider';
$head['zaehlerid'] = 'Zähler Name,select,0,Kein'.$zaehlerf;

$head[] = 'Operator,divider';
$head['zaehler_bedingung'] = 'Operator,select,0,=,1,+,2,-,3,*,4,/';

$head[] = 'entweder Zahl,divider';
$head['zaehler_bedingung_wert'] = 'Zahl,text,255';
$head[] = 'oder ein anderer Zähler,divider';
$head['zaehler_bedingung_zahler'] = 'Zähler,select,0,Kein'.$zaehlerf;


$head[] = 'Teleport,title';
$head[] = 'Teleport,divider';
$head['is_teleport'] = 'Teleportieren?,bool';
$head[] = 'Ort,divider';
$head['teleport_ort'] = 'Ort,select'.$ortef;

$head[] = 'Spieler,title';
$head[] = 'Töten,divider';
$head['is_death'] = 'Spieler töten?,bool';


$userstats = array(
    'reputation' => 'Ansehen',
    'gold' => 'Gold',
    'goldinbank' => 'Gold in Bank',
    'gems' => 'Edelsteine',
    'gemsinbank' => 'Edelsteine in Bank',
    'charm' => 'Charme',
    'turns' => 'Waldkämpfe',
    'gravefights' => 'Grabkämpfe',
    'drunkenness' => 'Betrunkenheit',
    'playerfights' => 'Spielerkämpfe',
    'hitpoints' => 'aktuelle Lebenspunkte',
);

foreach($userstats as $id => $namet)
{
    $head[] = $namet.',divider';
    $head[$id.'_bedingung'] = 'Operator,select,0,=,1,+,2,-,3,*,4,/';
    $head[$id.'_bedingung_wert'] = 'Modifikator,text,255';
}

$head[] = 'Items,title';

$head[] = 'Item GEBEN,divider';
$head['item_give_id'] = 'Item,select,0,Kein Item'.$items_tplf;
$head['item_give_anz'] = 'Anzahl,select'.$e18;

$head[] = 'Item ABNEHMEN,divider';
$head['item_take_id'] = 'Item,select,0,Kein Item'.$items_tplf;
$head['item_take_anz'] = 'Anzahl,select'.$e18;

$head[] = 'Buff,title';


$form = array(
    'Name,divider',
    'buff_buff_name' => 'Buff-Name',
    'Buff-Meldungen,divider',
    'buff_roundmsg' => 'Meldung jede Runde',
    'buff_wearoff' => 'Ablaufmeldung',
    'buff_effectmsg' => 'Effektmeldung',
    'buff_effectnodmgmsg'=>'Kein Schaden Meldung',
    'buff_effectfailmsg'=>'Fehlschlag Meldung',
    'Effekte,divider',
    'buff_rounds'=>'Hält Runden (nach Aktivierung),int',
    'buff_atkmod'=>'Multiplier. Angriffsmulti Spieler,int',
    'buff_defmod'=>'Multiplier. Verteidigungsmulti Spieler,int',
    'buff_regen'=>'Feste LP-Regeneration,int',
    'buff_minioncount'=>'Anzahl der "Buffelemente",int',
    'buff_minbadguydamage'=>'Deren min. Schaden',
    'buff_maxbadguydamage'=>'Deren max. Schaden',
    'buff_lifetap'=>'Multiplier. Gegnerschaden->Leben,int',
    'buff_damageshield'=>'Multiplier. Gegnerschaden->Gegnerabzug,int',
    'buff_badguydmgmod'=>'Multiplier für Gegnerschaden,int',
    'buff_badguyatkmod'=>'Multiplier für Gegnerangriff,int',
    'buff_badguydefmod'=>'Multiplier für Gegnerdef,int',
    'buff_activate'=>'Aktivieren bei `n(Mögl.: roundstart offense defense durch Kommata getrennt)',
    'Nicht-Kampf,divider',
    'buff_survive_death'=>'Bleibt beim Tod bestehen,bool|?Sinnvoll z.B. bei bestimmten Knappen'
);


$head = array_merge($head,$form);


$header = '<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center" width="100%">
			<tr class="trhead">
				<th>Id</th>
				<th>Name</th>
				<th>Beschreibung</th>
				<th>Aktiv</th>
				<th>Edit</th>
			</tr>';

require_once('inc/editor.php');

?>