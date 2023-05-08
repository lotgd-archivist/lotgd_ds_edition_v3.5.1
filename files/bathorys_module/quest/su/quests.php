<?php

require_once('inc/global.php');

function editorprocess($r,$filename,$i)
{
    global $orte,$bed;

    $bb = '';
    $bf = explode(',',$r['implode_sehen_bedingung']);
    foreach($bf as $k => $v)
    {
        $bb .= '`n'.$bed[$v];
    }
    $bb = mb_substr($bb,2);



    return '<tr class="'.($i%2?'trdark':'trlight').'" style="'.( ($r['activ']) ? '' : 'background:#777;').'">
				<td><b>'.$r['id'].'</b></td>
				<td><b>'.$r['name'].'</b></td>
				<td><b>'.$r['description'].'</b></td>
				<td><b>'.$r['dificulty'].'</b></td>

				<td><b>'.$orte[$r['ort']].'</b></td>


                   <td><b>'.$bb.'</b></td>


<td><b>'.($r['activ'] ? '<a href="'.$filename.'op='.$_GET['op'].'&do=deac&id='.$r['id'].'">Deak</a>' : '<a href="'.$filename.'op='.$_GET
    ['op'].'&do=ac&id='.$r['id'].'">Akt</a>').'</b></td>
				<td><b><a href="'.$filename.'op='.$_GET['op'].'&sop=edit&id='.$r['id'].'">Edit</a></b></td>
				</tr>';
}

$name = 'Quests';
$table = ' quest_events_orte ';
$orderby = " ORDER BY name ASC";

$head[] = 'Allgemein,title';
$head[] = 'Name,divider';

$head['name'] = 'Interner Name,text,255';

$head['name_prev'] = 'Vorschau,preview,questname';
$head['questname'] = 'Quest Name,text,255';

$head['nav_prev'] = 'Vorschau,preview,nav';
$head['nav'] = 'Nav-Text,text,255';

$head['description'] = 'Interne Beschreibung,textarea,50,10';

$head[] = 'Ort,divider';
$head['ort'] = 'Ort,select'.$ortef;

$head[] = 'Schwierigkeit,divider';
$head['dificulty'] = 'Schwierigkeit,select'.$e10;

$head[] = 'Verfällt,divider';
$head['verfall'] = 'IG-Tage,select,0,nie'.$e100o0;

$head[] = 'Ausgaben,title';

$head[] = 'Auftrags-Text:,divider';
$head['start_out'] = 'Output,textarea,50,10';
$head[] = 'Auftrag noch nicht erfüllt:,divider';
$head['middle_out'] = 'Output,textarea,50,10';
$head[] = 'Auftrag erfüllt:,divider';
$head['end_out'] = 'Output,textarea,50,10';

$head[] = 'Bedingungen,title';

$head[] = 'Bedingung um Quest zu sehen,divider';
$head['implode_sehen_bedingung'] = 'Bedingungen auswählen,select_multiple,8,0,Keine'.$bedf;

$head[] = 'Bedingung für Belohnung,divider';
$head['implode_belohnung_bedingung'] = 'Bedingungen auswählen,select_multiple,8,0,Keine'.$bedf;

$head[] = 'Effekte,title';

$head[] = 'Effekt Anfang,divider';
$head['implode_start_effekt'] = 'Effekte auswählen,select_multiple,8,0,Kein'.$efekf;

$head[] = 'Effekt Ende,divider';
$head['implode_end_effekt'] = 'Effekte auswählen,select_multiple,8,0,Kein'.$efekf;

$head[] = 'Belohnung,title';
$head[] = 'Belohnung,divider';
$head['gold'] = 'Gold,text,255';
$head['gems'] = 'Edelsteine,text,255';
$head['charme'] = 'Charme,text,255';
$head['implode_items'] = 'Items,select_multiple,30,0,Kein Item'.$items_tplf;
$head['dps'] = 'DPs,text,255';
$head['plp'] = 'Perma LPs,text,255';
$head['wks'] = 'WKs,text,255';
$head['gfs'] = 'Grabkämpfe,text,255';
$head['gefal'] = 'Gefallen,text,255';
$head['exp'] = 'Erfahrung,text,255';


$header = '<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center" width="100%"><tr class="trhead">
				<th>Id</th>
				<th>Name</th>
				<th>Beschreibung</th>
				<th>Schwierigkeit</th>
				<th>Ort</th>
				
                 <th>Bedingungen</th>
                <th>Aktiv</th>
				<th>Edit</th>
			</tr>';

require_once('inc/editor.php');

?>