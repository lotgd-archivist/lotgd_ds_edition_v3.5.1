<?php
/*
* @author Báthory
*/

require_once('inc/global.php');

function editorprocess($r,$filename,$i)
{
	global $zahlreset;

	return '<tr class="'.($i%2?'trdark':'trlight').'" style="'.( ($r['activ']) ? '' : 'background:#777;').'">
				<td><b>'.$r['id'].'</b></td>
				<td><b>'.$r['name'].'</b></td>
				<td><b>'.$r['description'].'</b></td>
				<td><b>'.($r['activ'] ? '<a href="'.$filename.'op='.$_GET['op'].'&do=deac&id='.$r['id'].'">Deak</a>' : '<a href="'.$filename.'op='.$_GET
    ['op'].'&do=ac&id='.$r['id'].'">Akt</a>').'</b></td>
				<td><b><a href="'.$filename.'op='.$_GET['op'].'&sop=edit&id='.$r['id'].'">Edit</a></b></td>
				</tr>';	
}

$name = 'Zählervariabeln';
$table = ' quest_zaehler ';
$orderby = " ORDER BY name";

$head[] = 'Allgemein,title';
$head[] = 'Name,divider';
$head['name'] = 'Name,text,255';

$head['name_book'] = 'Name der für den Spieler bei Bedingungen erscheint,text,255';

$head['description'] = 'Interne Beschreibung,textarea,50,10';

$head[] = 'Automatisches erhöhen bei,divider';

$head['up_fight_win'] = 'Jede Form von Kampf gewonnen,bool';
$head['up_fight_loose'] = 'Jede Form von Kampf verloren,bool';

$head['up_wfight_win'] = 'Waldkampf gewonnen,bool';
$head['up_wfight_loose'] = 'Waldkampf verloren,bool';

$head['up_gfight_win'] = 'Grabkampf gewonnen,bool';
$head['up_gfight_loose'] = 'Grabkampf verloren,bool';

$head['up_heal'] = 'Heilen,bool';
$head['up_die'] = 'Sterben alg,bool';
$head['up_nd'] = 'NewDay,bool';
$head['up_wb'] = 'Wiederbelebung,bool';
$head['up_ort'] = 'Besuch von,select,0,Kein Ort'.$ortef;

$head[] = 'Reset,divider';

$head['rs_fight_win'] = 'Jede Form von Kampf gewonnen,bool';
$head['rs_fight_loose'] = 'Jede Form von Kampf verloren,bool';

$head['rs_wfight_win'] = 'Waldkampf gewonnen,bool';
$head['rs_wfight_loose'] = 'Waldkampf verloren,bool';

$head['rs_gfight_win'] = 'Grabkampf gewonnen,bool';
$head['rs_gfight_loose'] = 'Grabkampf verloren,bool';

$head['rs_heal'] = 'Heilen,bool';
$head['rs_die'] = 'Sterben alg,bool';
$head['rs_nd'] = 'NewDay,bool';
$head['rs_wb'] = 'Wiederbelebung,bool';
$head['rs_ort'] = 'Besuch von,select,0,Kein Ort'.$ortef;


$header = '<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center" width="100%"><tr class="trhead">
				<th>Id</th>
				<th>Name</th>
				<th>Beschreibung</th>
				<th>Aktiv</th>
				<th>Edit</th>
			</tr>';

require_once('inc/editor.php');

?>