<?php
// Raum für Bregomils Übungsgeräte (alle einzeln nutzbar)
// by Salator

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_trainingroom ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner,$g_arr_house_extensions;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);

	switch($str_case) {

		// Innen
		case 'in':

			switch($_GET['act'])
			{
				case '':
				{
					//Beschreibungstext modifizieren: Eine Liste aller Kampfübungsgeräte incl. Benutzen-Links
					$result=item_list_get('deposit1='.(int)$arr_house['houseid'].' AND deposit2='.(int)$arr_ext['id'].' AND tpl_id IN("zielsch","strpuppe","sandsack","lovedoll")'
					,'ORDER BY sort_order DESC, name ASC'
					,true
					,'id,name,description,hvalue,tpl_id');
					  $str_items='';
					while($item=db_fetch_assoc($result))
					{
						if($item['tpl_id']=='sandsack')
						{
							$desc=mb_substr($item['description'],42,-11);
						}
						elseif($item['tpl_id']=='strpuppe')
						{
							$desc=mb_substr($item['description'],51,-1);
						}
						elseif($item['tpl_id']=='zielsch')
						{
							$desc=mb_substr($item['description'],53,-1);
						}
						elseif($item['tpl_id']=='lovedoll')
						{
							$desc=mb_substr($item['description'],56,-1);
						}
						else
						{
							$desc=$item['name'];
						}
						$desc=str_replace(' ','-',$desc.'`&-'.$item['name']);
						$str_items.='`n'.create_lnk($desc,'furniture.php?item_id='.$item['id']);
					}
					if($str_items>'')
					{
						global $arr_content;
						$arr_content['desc'].='`nDu lässt deinen Blick durch den Raum schweifen und entdeckst '.$str_items;
					}
					//END Beschreibungstext modifizieren

					if($bool_rowner)
					{
						addnav('Geräte einlagern',$str_base_file.'&act=putin');
					}
					break;
				}

				case 'putin': //Auswahlliste zum Geräte einlagern
				{
					if($_GET['id']>0)
					{
                        $str_out='';
						if(item_count('deposit1='.(int)$arr_house['houseid'].' AND deposit2='.(int)$arr_ext['id']) >= $g_arr_house_extensions[$arr_ext['type']]['max_furn'])
						{
							$str_out.='`$Dieser Raum ist leider schon voll.`n`n`0';
						}
						elseif(item_set('id='.(int)$_GET['id'],array('deposit1'=>$arr_house['houseid'], 'deposit2'=>$arr_ext['id'])))
						{
							$str_out.='`@OK, Item erfolgreich eingelagert.`n`n`0';
						}
						else
						{
							$str_out.='`$Einlagern fehlgeschlagen!`n`n`0';
						}
					}
					
					$sql='SELECT id,name,description
						FROM items
						WHERE owner='.$session['user']['acctid'].'
							AND tpl_id IN("zielsch","strpuppe","sandsack","lovedoll")
							AND deposit1=0
							AND deposit2=0
						ORDER BY hvalue ASC, name
						LIMIT 100';
					$result=db_query($sql);
					if(db_num_rows($result)>0)
					{
						$str_out.=get_title('Auswahl der Geräte').'
						<table bgcolor="#999999">
						<tr class="trhead">
						<th>Name</th>
						<th>Beschreibung</th>
						<th>Aktion</th>
						</tr>';
						while($item=db_fetch_assoc($result))
						{
							$trclass=($trclass=='trdark'?'trlight':'trdark');
							$str_out.='<tr class="'.$trclass.'">
							<td>'.$item['name'].'`0</td>
							<td>`^'.$item['description'].'`0</td>
							<td>&nbsp;'.create_lnk('Einlagern',$str_base_file.'&act=putin&id='.$item['id']).'</td>
							</tr>';
						}
						$str_out.='</table>';
					}
					else
					{
						$str_out.=get_title('Leere Taschen').'Vielleicht solltest du erstmal ein paar Dinge bei Bregomil kaufen, ehe du ans Einlagern denkst...`n';
					}
					output($str_out);
					addnav('Zurück',$str_base_file);
					break;
				}

				default:
					break;
			}

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;
		// END case in

		// Bau gestartet
		case 'build_start':

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;

		// Bau fertig
		case 'build_finished':

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;

		// Abreißen
		case 'rip':

			_rooms_common_switch($str_case,$arr_ext,$arr_house);

		break;

	}	// END Main switch
}

?>
