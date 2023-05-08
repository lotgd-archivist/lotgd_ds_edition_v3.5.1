<?php
// Leerer Raum (Hier kann praktisch alles rein)
// by talion

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_hunterroom ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;

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
					//Wenn die eingelagerten Trophäen nicht unter Möbel erscheinen weil die normalerweise nicht einlagerfähig sind machen wir eben eine Liste im Beschreibungstext...
					$result=item_list_get('deposit1='.(int)$arr_house['houseid'].' AND deposit2='.(int)$arr_ext['id'].' AND tpl_id="trph"'
					,'GROUP BY name ORDER BY sort_order DESC, name ASC'
					,true
					,'name,COUNT(*) as count');
					
					while($row=db_fetch_assoc($result))
					{
						$str_trphs.=', `n'.$row['count'].'x '.$row['name'];
					}
					if($str_trphs>'')
					{
						global $arr_content;
						$str_trphs=mb_substr($str_trphs,2);
						$arr_content['desc'].='`nDu lässt deinen Blick die Wände entlangschweifen und entdeckst '.$str_trphs.'.';
					}
					//END Beschreibungstext modifizieren

					$rowe=user_get_aei('hunterlevel');
					if($rowe['hunterlevel']>0)
					{
						addnav('B?Dein Trophäen-Buch',$str_base_file.'&act=trphstat');
						if($bool_rowner)
						{
							//keine weiteren Bedingungen. Falls sich dadurch das Möbel-Limit umgehen lässt ist mir das auch egal.
							addnav('Trophäen einlagern',$str_base_file.'&act=putin');
						}
					}
					break;
				}

				case 'putin': //Auswahlliste zum Trophäen einlagern
				{
					$sql='SELECT *
						FROM items
						WHERE owner='.$session['user']['acctid'].'
							AND tpl_id="trph"
							AND deposit1=0
							AND deposit2=0
							AND (value2=9 OR value2=10)
						ORDER BY hvalue ASC, name
						LIMIT 100';
					$result=db_query($sql);
					if(db_num_rows($result)>0)
					{
						$str_out=get_title('Auswahl der Trophäen').'
						<table>
						<tr class="trhead">
						<th>Name</th>
						<th>Wert</th>
						<th>Aktion</th>
						</tr>';
                        $trclass='trdark';
						while($item=db_fetch_assoc($result))
						{
							$trclass=($trclass=='trdark'?'trlight':'trdark');
							$str_out.='<tr class="'.$trclass.'">
							<td>'.$item['name'].'`0</td>
							<td align="right">`^'.$item['gold'].'`0 Gold`n
							`#'.$item['gems'].'`0 Gems</td>
							<td>&nbsp; '.create_lnk('Einlagern',$str_base_file.'&act=putin2&id='.$item['id']).'</td>
							</tr>';
						}
						$str_out.='</table>
						`n(zum Auslagern bitte das Inventar-Menü nutzen, sortieren geht über den Punkt Möbelrücken)';
						output($str_out);
					}
					else
					{
						output(get_title('Leere Taschen').'Vielleicht solltest du erstmal ein paar Trophäen sammeln, ehe du ans Einlagern denkst...`n');
					}
					addnav('Zurück',$str_base_file);
					break;
				}

				case 'putin2':
				{
					if(item_set('id='.(int)$_GET['id'],array('deposit1'=>$arr_house['houseid'], 'deposit2'=>$arr_ext['id'])))
					{
						output('`@OK, Trophäe erfolgreich eingelagert.');
					}
					else
					{
						output('`$Einlagern fehlgeschlagen!');
					}
					addnav('Mehr einlagern',$str_base_file.'&act=putin');
					addnav('Zurück zum Gemach',$str_base_file);
					break;
				}

				case 'trphstat': //Übersicht über bereits erlegte Tiere
				{
					$items=item_get('tpl_id="huntweapon" AND owner='.$session['user']['acctid']);
					if($items['content'])
					{
						$arr_trphnames=array(
							'canin'=>'Kaninchen'
							,'fox'=>'Fuchs'
							,'badger'=>'Dachs'
							,'pig'=>'Wildschwein'
							,'deer'=>'Reh'
							,'hart'=>'Hirsch'
							,'wulf'=>'Wolf'
							,'bear'=>'Bär'
							,'god'=>'einzigartiges mystisches Geweih des Waldgott-Champions'
							);
						$arr_content=utf8_unserialize($items['content']);
						output(get_title('`@Bisherige Leistungen').'Du schaust in deine Aufzeichnungen und zählst, dass du bereits `^'.array_sum($arr_content['trphcount']).' Trophäen`0 erbeutet hast. Das sind im Einzelnen:`n');
						foreach($arr_content['trphcount'] as $key => $val)
						{
							output ('`n'.$val.'x '.$arr_trphnames[$key]);
						}
					}
					addnav('Buch schließen',$str_base_file);
					break;
				}

				default:
					//addnav('Huch!?',$str_base_file);
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
