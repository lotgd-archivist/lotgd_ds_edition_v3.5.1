<?php

require_once('inc/global.php');

$lastquest = 0;
$laststep = 0;
$header = '';

function editorprocess($r,$filename,$i)
{
     global $orte,$bed,$quests,$lastquest,$laststep;

    $bb = '';
    $bf = explode(',',$r['implode_sehen_bedingung']);
    foreach($bf as $k => $v)
    {
        $bb .= '`n'.$bed[$v];
    }
    $bb = mb_substr($bb,2);

       $typ = array('Betreten','Ansprechen','Ja/Nein','Kampf','Tausch');
    $return = '';



    if($lastquest==0)
    {
        $laststep = 0;
        $return .= '`n`nQuest: <b>'.$quests[$r['questid']].'</b>`n`n<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center" width="100%"><tr class="trhead">
				<th>Step</th>
				<th>Name</th>
				<th>Ort</th>
				<th>Beschreibung</th>
				<th>Typ</th>
				<th>Bedingungen</th>
				<th>Up</th>
				<th>Dn</th>
				<th>Aktiv</th>
				<th>Edit</th>
			</tr>';
    }
    if($lastquest!=0 && $r['questid']!=$lastquest)
    {
        $laststep = 0;
        $return .= '</table>`n`nQuest: <b>'.$quests[$r['questid']].'</b>`n`n<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center" width="100%"><tr class="trhead">
				<th>Step</th>
				<th>Name</th>
				<th>Ort</th>
				<th>Beschreibung</th>

				<th>Typ</th>
				<th>Bedingungen</th>
				<th>Up</th>
				<th>Dn</th>
				<th>Aktiv</th>
				<th>Edit</th>
			</tr>';
    }
     $lastquest =  $r['questid'];
	$return .= '<tr class="'.($i%2?'trdark':'trlight').'" style="'.( ( ($r['interactid'] == $laststep) || ( ($r['interactid'] - $laststep) > 1 ) ) ? 'background-color:#ff0000;background-image:none;' : '' ).''.( ($r['activ']) ? '' : 'background:#777;').'">
				<td><b>'.$r['interactid'].'</b></td>
				<td><b>'.$r['name'].'</b></td>

				<td><b>'.$orte[$r['ort']].'</b></td>

				<td><b>'.$r['description'].'</b></td>
               <td><b>'.$typ[$r['typ']].'</b></td>
                   <td><b>'.$bb.'</b></td>

<th><b><a href="'.$filename.'op='.$_GET['op'].'&do=up&q='.$r['questid'].'&id='.$r['id'].'&t='.$r['interactid'].'">UP</a></b></th>
			<th><b><a href="'.$filename.'op='.$_GET['op'].'&do=down&q='.$r['questid'].'&id='.$r['id'].'&t='.$r['interactid'].'">DN</a></b></th>





				<td><b>'.($r['activ'] ? '<a href="'.$filename.'op='.$_GET['op'].'&do=deac&id='.$r['id'].'">Deak</a>' : '<a href="'.$filename.'op='.$_GET
    ['op'].'&do=ac&id='.$r['id'].'">Akt</a>').'</b></td>
				<td><b><a href="'.$filename.'op='.$_GET['op'].'&sop=edit&id='.$r['id'].'">Edit</a></b></td>
				</tr>';
    $laststep = $r['interactid'];
    return $return;
}

$name = 'Interaktionen';
$table = ' quest_events_interact ';
$orderby = " ORDER BY questid,interactid";

$head[] = 'Allgemein,title';
$head[] = 'Name,divider';
$head['name'] = 'Interner Name,text,255';
$head['name_prev'] = 'Vorschau,preview,questname';
$head['questname'] = 'Interaktions Name (fürs Buch),text,255';

$head[] = 'Vorschau,preview,link';
$head['link'] = 'Nav-Text,text,255';


$head['description'] = 'Interne Beschreibung,textarea,50,10';

$head[] = 'Ort,divider';
$head['ort'] = 'Ort,select'.$ortef;

$head[] = 'Typ der Interaktion,divider';
$head['typ'] = 'Typ,select,0,Sofort beim betreten,1,Beim Ansprechen sofort,2,Beim Ansprechen Ja/Nein,3,Beim Ansprechen Kampfherausforderung,4,Beim Ansprechen Tauschangebot';


$head[] = 'Oberquest,divider';
$head['questid'] = 'Quest,select'.$questsf;

$head[] = 'Step,divider';
$head['interactid'] = 'Step,select'.$e100o0;

$head[] = 'Bedingungen,title';

$head[] = 'Bedingung für Interaktion,divider';
$head['implode_sehen_bedingung'] = 'Bedingungen auswählen,select_multiple,8,0,Keine'.$bedf;

$head[] = 'Ausgaben,title';

$head[] = 'Ansprech-Text:,divider';
$head['start_out'] = 'Output,textarea,50,10';
$head[] = 'Interaktion wurde angenommen:,divider';
$head['middle_out'] = 'Output,textarea,50,10';
$head[] = 'Interaktion wurde abgelehnt:,divider';
$head['end_out'] = 'Output,textarea,50,10';

$head[] = 'Effekt,title';
$head[] = 'Effekt Start,divider';
$head['implode_efk_start'] = 'Effekte auswählen,select_multiple,8,0,Kein'.$efekf;

$head[] = 'Effekt bei Ende (egal ob Kampf verloren!) nicht wenn abgelehnt,divider';
$head['implode_efk_end'] = 'Effekte auswählen,select_multiple,8,0,Kein'.$efekf;


$head[] = 'Kampf,title';
$head[] = 'Eigenes Monster-Kampf,divider';
$head['is_kampf_eigen'] = 'Eigenes Monster Kampf?,bool';
$head['kampf_eigen_creaturename'] = 'creaturename,text,255';
$head['kampf_eigen_creaturelevel'] = 'creaturelevel,text';
$head['kampf_eigen_creatureweapon'] = 'creatureweapon,text';
$head['kampf_eigen_creatureattack'] = 'creatureattack,text';
$head['kampf_eigen_creaturedefense'] = 'creaturedefense,text';
$head['kampf_eigen_creaturehealth'] = 'creaturehealth,text';
$head['kampf_eigen_creatureanz'] = 'Anzahl an Monster,text';

$head[] = 'Personen-Kampf,divider';
$head['is_kampf_person'] = 'Person Kampf?,bool';

$head['kampf_personid_name'] = 'Personenen 1 pro Zeile,textarea,50,10';

$head['kampf_personid_level'] = 'Person Level,select,999,An Spieler anpassen'.$e18;
$head[] = 'Monster-Kampf,divider';
$head['is_kampf_monster'] = 'Monster Kampf?,bool';
$head['implode_kampf_monsterid'] = 'Monster,select_multiple,8,0,Kein'.$atmf;
$head['kampf_monsterid_level'] = 'Monster Level,select,888,Original-Werte,999,An Spieler anpassen'.$e18;

$head[] = 'Effekt wenn Kampf gewonnen,divider';
$head['implode_kampf_erfolg_efkuz'] = 'Effekte auswählen,select_multiple,8,0,Kein'.$efekf;

$head[] = 'Effekt wenn Kampf verloren,divider';
$head['implode_kampf_not_erfolg_efkuz'] = 'Effekte auswählen,select_multiple,8,0,Kein'.$efekf;

$head[] = 'Ausgabe am Ende des Kampfs,divider';
$head['kampf_aus_erfolg'] = 'Bei Erfolg,textarea,50,10';
$head['kampf_aus_not_erfolg'] = 'Falls kein Erfolg,textarea,50,10';




require_once('inc/editor.php');

?>