<?php
require_once('inc/global.php');
require_once(LIB_PATH.'runes.lib.php');
function editorprocess($r,$filename,$i)
{
	return '<tr class="'.($i%2?'trdark':'trlight').'" style="'.( ($r['activ']) ? '' : 'background:#777;').'">
				<td><b>'.$r['id'].'</b></td>
				<td><b>'.$r['name'].'</b></td>
				<td><b>'.$r['description'].'</b></td>

				<td><b><ol>'.CQuest::check_bedingungen($r['id'],true).'</ol></b></td>

<td><b>'.($r['activ'] ? '<a href="'.$filename.'op='.$_GET['op'].'&do=deac&id='.$r['id'].'">Deak</a>' : '<a href="'.$filename.'op='.$_GET
    ['op'].'&do=ac&id='.$r['id'].'">Akt</a>').'</b></td>
				<td><b><a href="'.$filename.'op='.$_GET['op'].'&sop=edit&id='.$r['id'].'">Edit</a></b></td>
				</tr>';	
}

$name = 'Bedingung';
$table = ' quest_bedingung ';
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
$head['zaehler_bedingung'] = 'Operator,select,0,=,1,<,2,>,3,<=,4,>=';

$head[] = 'entweder Zahl,divider';
$head['zaehler_bedingung_wert'] = 'Zähler Bedingungswert,text,255';
$head[] = 'oder ein anderer Zähler,divider';
$head['zaehler_bedingung_zahler'] = 'Zähler Bedingungszähler,select,0,Kein'.$zaehlerf;

$head[] = 'Zufall,title';
$head[] = 'Zufall,divider';
$head['zufall'] = 'Wahrscheinlichkeit,select,999,Kein'.$e100;

$head[] = 'Quest,title';
$head[] = 'Quest der erfüllt sein muss,divider';
$head['must_questid'] = 'Quest,select,0,Kein'.$questsf;

$head[] = 'Spieler,title';

$head[] = 'Titel,divider';
$head['titel_bedingung'] = 'Titel muss sein(Farben spielen keine Rolle!),text,255';

$head[] = 'Gold,divider';
$head['gold_bedingung'] = 'Operator,select,0,=,1,<,2,>,3,<=,4,>=';
$head['gold_bedingung_wert'] = 'Zahl,text,255';

$head[] = 'Gold in Bank,divider';
$head['goldinbank_bedingung'] = 'Operator,select,0,=,1,<,2,>,3,<=,4,>=';
$head['goldinbank_bedingung_wert'] = 'Zahl,text,255';

$head[] = 'Edelsteine,divider';
$head['gems_bedingung'] = 'Operator,select,0,=,1,<,2,>,3,<=,4,>=';
$head['gems_bedingung_wert'] = 'Zahl,text,255';

$head[] = 'Edelsteine in Bank,divider';
$head['gemsinbank_bedingung'] = 'Operator,select,0,=,1,<,2,>,3,<=,4,>=';
$head['gemsinbank_bedingung_wert'] = 'Zahl,text,255';

$head[] = 'Level,divider';
$head['level_bedingung'] = 'Operator,select,0,=,1,<,2,>,3,<=,4,>=';
$head['level_bedingung_wert'] = 'Zahl,text,255';

$head[] = 'DKs,divider';
$head['dk_bedingung'] = 'Operator,select,0,=,1,<,2,>,3,<=,4,>=';
$head['dk_bedingung_wert'] = 'Zahl,text,255';

$head[] = 'Waldkämpfe,divider';
$head['wks_bedingung'] = 'Operator,select,0,=,1,<,2,>,3,<=,4,>=';
$head['wks_bedingung_wert'] = 'Zahl,text,255';

$head[] = 'Grabkämpfe,divider';
$head['gf_bedingung'] = 'Operator,select,0,=,1,<,2,>,3,<=,4,>=';
$head['gf_bedingung_wert'] = 'Zahl,text,255';

$head[] = 'Male,divider';
$head['implode_male'] = 'Male,select_multiple,7,0,Egal,1,Erde,2,Luft,4,Feuer,8,Wasser,16,Geist,32,Blutgott';

$head[] = 'Minimaler Runnenrang,divider';
$head['rune_ident'] = 'Runen,select,0,'.runes_get_rank(0,0)
                                 .',1,'.runes_get_rank(1,0)
                                 .',5,'.runes_get_rank(5,0)
                                 .',10,'.runes_get_rank(10,0)
                                 .',15,'.runes_get_rank(15,0)
                                 .',20,'.runes_get_rank(20,0)
                                 .',24,'.runes_get_rank(24,0).'';

$head[] = 'Hat oder ist,divider';
$head['has_horse'] = 'Tier,bool';
$head['has_house'] = 'Haus,bool';
$head['has_disc'] = 'Knappe,bool';
$head['is_drunk'] = 'Betrunken,bool';
$head['is_health'] = 'Max. Lebenspunkte,bool';
//$head['has_bathi'] = 'Bathi-Puppe,bool';

$head[] = 'Items,title';
$head[] = 'Item,divider';

$head['item_id'] = 'Item,select,0,Kein Item'.$items_tplf;
$head['item_cls'] = 'oder Itemklasse,select,0,Kein Item'.$clsf;

$head[] = 'Anzahl an dem Item,divider';
$head['item_anz_bedingung'] = 'Bedingung,select,0,=,1,<,2,>,3,<=,4,>=';
$head['item_anz_bedingung_wert'] = 'entweder Bedingungswert,text,255';
$head['item_anz_bedingung_zahler'] = 'oder Bedingungszähler,select,0,Kein'.$zaehlerf;

$head[] = 'Wetter / Zeit,title';
$head[] = 'Wetter und Co.,divider';
$head['implode_wther'] = 'Wetter,select_multiple,8'.$weatherf;
$head['implode_monat'] = 'Monat,select_multiple,8'.$e12;
$head['implode_tag'] = 'Tag,select_multiple,8'.$e31;
$head['minstd'] = 'Min. Stunde,select'.$e24;
$head['maxstd'] = 'Max. Stunde,select'.$e24;

$header = 'Hinsweis zu den TEST-Farben Gelb = Kein Einfluss, Grün IHR erfüllt sie, Rot IHR erfüllt sie nicht...`n`n<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center" width="100%">
			<tr class="trhead">
				<th>Id</th>
				<th>Name</th>
				<th>Beschreibung</th>
				<th>TEST</th>
				<th>Aktiv</th>
				<th>Edit</th>
			</tr>';

require_once('inc/editor.php');

?>