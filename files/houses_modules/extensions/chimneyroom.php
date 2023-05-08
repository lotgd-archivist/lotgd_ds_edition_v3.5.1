<?php
// Kaminzimmer
// by talion


// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_chimneyroom ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);
	$str_content_md5 = md5($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);
	$str_output = '`t';

	switch($str_case) {

		// Innen
		case 'in':

			/*if($arr_content['timestamp'] != getgamedate())
			{
			$arr_content['timestamp'] = getgamedate();
			}*/

			switch($_GET['act']) {
				case 'flirt':
					{
						$_GET['id'] = (int)$_GET['choose'];
						$flirt_inc_style = 'chimneyroom';

						$bool_flirtaffianced=true; //verlobt fremdflirten zwecks Auflösung
						$bool_noturnsallowed=true; //Flirt ohne WK erlaubt
						$bool_flirtcharmdiff=true; //Charmeunterschied nicht prüfen
						$flirtmail_subject='`%Flirt!`0';
						$flirtmail_body='`&'.$session['user']['name'].'`6 hat gerade damit begonnen dich heftig anzuflirten';
						$flirtlocation=' im Kaminzimmer ';
						$str_output_noturns = 'Du versuchst ohne Waldkämpfe zu flirten. Eigentlich sollte das hier erlaubt sein. Beschwer dich beim Programmierer.';

						include('./flirt.inc.php');
						$session['user']['seenlover'] = 1;
						addnav('Zurück',$str_base_file);
						output($str_output);
						break;
					}

				case 'warm':
					$str_output .= get_title('`tDer wärmende Kamin');
					if(isset($_GET['addwood']))
					{
						if($session['user']['seenlover'] == 0)
						{
							$arr_item = item_get('id='.(int)$_GET['addwood']);
							if($arr_item !== false)
							{
								//Holzscheite löschen
								if($arr_item['value1'] <= 1)
								{
									item_delete('id='.(int)$_GET['addwood']);
								}
								//Anzahl der Holzscheite verringern
								else
								{
									$arr_item['value1']--;
									item_set('id='.(int)$_GET['addwood'],$arr_item);
								}
	
								//Hier passiert jetzt was nettes
								$res = db_query('SELECT name,acctid FROM accounts WHERE chat_section = "h_room'.$session['housekey'].'-'.$arr_ext['id'].'"');
	
								$str_output .= '
								Ein prasselndes, warmes Feuer im Kamin, ein gemütliches Sofa davor - romantischer könnte es nicht sein! Du weißt, dass es unter diesen Umständen wohl nicht mehr so sehr auf die kleinen und großen Unterschiede zwischen euch ankommt und die Chancen, dass du und '.($session['user']['sex']==0?'deine Angebetete':'dein Angebeteter').' euch näherkommt, viel größer sind. Willst du es nicht einfach mal wagen?`n`n 
	
								`DMit wem willst du ein wenig flirten?`0`n';
								$int_count = 0;
								while ($arr_user = db_fetch_array($res))
								{
									//Mit sich selbst flirten ist pfuibaba
									if($arr_user['acctid'] == $session['user']['acctid'])
									{
										continue;
									}
	
									$str_output .= '`n'.$arr_user['name'].'`0 - '.create_lnk('Auswählen',$str_base_file.'&act=flirt&choose='.$arr_user['acctid']);
									$int_count++;
								}
								
								//Zum testen einkommentieren
								//$str_output .= '`nDir selbst`0 - '.create_lnk('Auswählen',$str_base_file.'&act=flirt&choose=1');
	
								if($int_count == 0)
								{
									$str_output .= '`n`nSchade dass gerade niemand außer dir in diesem Raum weilt. Hallooooo Hand!`n';
								}
							}
							else
							{
								$str_output .= 'Eieiei, böser Fehler hier';
							}
						}
						else 
						{
							$str_output .= 'Zwar überlegst du kurz, ob du nicht den Holzscheit in das prasselnde Feuer werfen solltest, allerdings hast du heute schon genug Romantik hinter dir. Irgendwann ist es genug!';
						}
					}
					else
					{

						$str_output .= "`tDu lässt dich vor dem gemütlich prasselnden Kaminfeuer nieder und streckst ihm deine Füße entgegen. Seine Wärme zieht als wohliger Schauder durch deinen Körper.`n";

						switch (e_rand(0,8)) {
							case 4: //hp
							$str_output .= "Du fühlst dich besonders wohl und erhältst daher `^zusätzliche Lebenskraft.`t";
							$session['user']['hitpoints'] = ceil($session['user']['maxhitpoints'] * 1.01);
							break;
							case 6: //--hp
							$str_output .= "Dabei nickst du kurz ein; als du wieder aufwachst, tust du dies aufgrund des wahrhaft brennenden Schmerzes in deinem Fuß! Du `^verlierst Lebenskraft.`t";
							$session['user']['hitpoints'] = 1;
							break;
						} //switch

					}

					//Alles raus!
					output($str_output);


					addnav('Zurück',$str_base_file);

					break;

				case '':
					{
						//Navs anlegen
						$arr_items = array();
						if(house_has_item($arr_house['houseid'],$arr_ext['id'],'feuerholz','id,value1',$arr_items))
						{
							addnav('Holz nachlegen');
							while($arr_item = db_fetch_array($arr_items))
							{
								$int_count = $arr_item['value1'];
								addnav("Holzscheite ($int_count übrig)",$str_base_file.'&act=warm&addwood='.$arr_item['id']);
							}
						}
						addnav('Dich vor dem Kamin wärmen!',$str_base_file.'&act=warm');
						break;
					}
				default:

					break;
			}

			//Content Array zurückschreiben
			if($str_content_md5  != md5(utf8_serialize($arr_content)))
			{
				db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);
			}

			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;
			// END case in

			// Bau gestartet
		case 'build_start':

			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;

			// Bau fertig
		case 'build_finished':

			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;

			// Abreißen
		case 'rip':

			// Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;

	}	// END Main switch
}


?>
