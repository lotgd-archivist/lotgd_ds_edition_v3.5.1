<?php
// Gewölbe
// by Fingolfin

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

/**
 * Mach eingelagerten Wein zu lecker Essig
 * @param array $arr_ext
 */
function wine_to_vinegar($arr_ext)
{
	$arr_list = item_list_get('i.tpl_id="wein" AND deposit2='.$arr_ext['id'],'',true,'id,deposit1,deposit2,gems,description',true);
	
	foreach($arr_list as $arr_item)
	{
		$arr_item['gems']++;
		
		//Zu Essig machen
		if(e_rand(0,100) < ($arr_item['gems']-1)*10)
		{
			item_overwrite('id='.$arr_item['id'],'w_vinegar',array('deposit1'=>$arr_item['deposit1'],'deposit2'=>$arr_item['deposit2']));
		}
		else 
		{
			//"Dieser Wein ist x Jahre alt" aktualisieren
			$str_new_desc = utf8_preg_replace('/(`nDieser Wein ist) \d+ (Jahre alt.)/','',$arr_item['description']);
			$str_new_desc .= '`nDieser Wein ist '.($arr_item['gems']-1).' Jahre alt.';
			
			item_set('id='.$arr_item['id'],array('gems'=>$arr_item['gems'],'description'=>$str_new_desc));
		}
	}
}

function house_ext_arch ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);
	$str_content_md5 = md5($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);

	switch($str_case) {

		// Innen
		case 'in':

			//Einmal pro Ingamejahr
			$time_delta = get_gamedate_part('y');
			if($arr_content['wine_aging']['year'] != $time_delta)
			{
				wine_to_vinegar($arr_ext);
				$arr_content['wine_aging']['year'] = $time_delta;
			}
			
			if($arr_content['timestamp'] != getgamedate())
			{
				$arr_content['timestamp'] = getgamedate();
				$arr_content['arch_drunk'] = array();
				$arr_content['arch_harvest'] = false;
				$arr_content['user_harvest'] = array();
				
			}
			
			

			$str_output = '';

			switch($_GET['act'])
			{
				case '':

					addnav('Gewölbe erforschen',$str_base_file.'&act=explore');
					addnav('Den Tunnel betreten',$str_base_file.'&act=tunnel');
					addnav('Nach Kräutern suchen',$str_base_file.'&act=herb');
					addnav('Zum Weinregal',$str_base_file.'&act=wine');

					break;

				case 'explore':

					$str_output .= house_get_title('Im Gewölbe');

					if($session['user']['turns']<1)
					{
						$str_output .= '`tPuh, wie anstrengend... Noooch eine Runde durch das Gewölbe - nein, das muss nun wirklich nicht mehr sein. Ein wenig frische Luft würde dir eh gut tun.';
					}
					else
					{

						$str_output .= '`tDu wanderst durch das uralte Gewölbe das sich vor dir erstreckt und schaust dich aufmerksam um. Langsam aber stetig dringst du dabei in den hinteren Teil vor, den du schon lange nicht mehr besucht hast. ';
						switch(e_rand(1,5))
						{
							case 1:
							case 2:

								$str_output .= 'Alles um dich herum ist total verstaubt und die Luft riecht unangenehm abgestanden. Du hälst es zwar noch eine Weile hier aus, doch schon bald kannst du es nicht mehr ertragen und gehst wieder zum Eingang zurück.`n`n
								`TDein kleiner Ausflug hat dir die Lust auf das Kämpfen kräftig verdorben.';
								break;

							case 3:

								$str_output .= 'Gerade biegst du um einen Pfeiler, als vor dir plötzlich ein Skelett auf dem Boden liegt. Geschockt springst du ein paar Schritte zurück und machst dich schnell wieder auf den Weg zum Eingang.`n`n
								`TDiesen Schock musst du erst einmal verarbeiten.';

								$arr_buff = array(
								'name'=>'`#Schock',
								'rounds'=>20,
								'wearoff'=>'`#Du hast die Begegnung mit dem Skelett inzwischen vergessen.`0',
								'atkmod'=>0.95,
								'defmod'=>0.95,
								'roundmsg'=>'`#Die Erinnerung an das Skelett nagt an dir!`0',
								'activate'=>'offense,defense'
								);
								buff_add($arr_buff);
								break;

							case 4:
								if(house_has_extension($arr_ext['houseid'],'rathole'))
								{
									$str_sql = 'SELECT content FROM house_extensions WHERE houseid='.$arr_ext['houseid'].' AND type="rathole" ORDER BY RAND()';
									$db_res = db_query($str_sql);
									$arr_result = db_fetch_assoc($db_res);
									$arr_content_rathole = utf8_unserialize($arr_result['content']);
									$ratname = (empty($arr_content_rathole['ratname'])?'die Hausratte':$arr_content_rathole['ratname']);

									$str_output = house_get_title('Bei den Mäusen');

									$str_output .= '`tDu schaust dich im Gewölbe um und siehst wie eine Maus vor deinen Füßen vorbei huscht. Gerade noch kannst du erkennen, wie sie in einem Loch in der Wand verschwindet. Du näherst dich dem Loch und gehst auf die Knie, um hineinschauen zu können. Dir bleibt fast der Atem stehen.`n`n
			               		`&Du siehst '.$ratname.' `&';

									switch(e_rand(1,6))
									{
										case 1:
											$str_output .= 'mit anderen Mäusen an einem kleinen Tisch Poker spielen.';
											break;
										case 2:
											$str_output .= 'mit anderen Mäusen an einem kleinen Tisch Skat spielen.';
											break;
										case 3:
											$str_output .= 'in einer Miniaturbadewanne ein gemütliches Bad nehmen.';
											break;
										case 4:
											$str_output .= 'genüsslich in ein großes Stück Käse beißen, dass fast das ganze Loch füllt.';
											break;
										case 5:
											$str_output .= 'mit anderen Mäusen an einem kleinen Tisch Monopoly spielen.';
											break;
										case 6:
											$str_output .= 'gerade noch in einem Gang verschwinden und bemerkst eine Katze die sich hinter dich geschlichen hat.';
											break;
									}
									$str_output .= '`n`n`tSachen gibts...';
								}
								else
								{
									$str_output .= '`tDu schaust dich im Gewölbe um und siehst wie eine Maus vor deinen Füßen vorbeihuscht. Achselzuckend gehst Du weiter. Es ist schließlich ein Kellergewölbe, da gibts nunmal auch ein paar Nager...`n`n';
								}
								$session['user']['turns']++; //Ausgleich für den Abzug
								break;
							case 5:

								$str_output .= 'Du beginnst die Wände des Gewölbes zu untersuchen und nach einiger Zeit entdeckst du seltsame Wandmalereien, die die Steine bedecken. Du versuchst sie zu enträtseln, hast aber nur wenig Erfolg.`n`n
                     		`TWas für eine Entdeckung. Du machst dich mit deinem Wissen wieder auf den Rückweg.';

								$session['user']['experience'] += $session['user']['level'] * 50;
								break;
						}
					}


					$session['user']['turns'] = max($session['user']['turns']-1,0);
					addnav('Zurück',$str_base_file);
					break;

				case 'tunnel':

					$str_output .= house_get_title('Im Tunnel');

					switch($_GET['msg'])
					{
						case '':

							$link = $str_base_file.'&act=tunnel&msg=true';
							addnav('',$link);
							addnav('Lieber nicht',$str_base_file);

							$str_output .= '`tDu machst dich auf den Weg in das Gewölbe und nach einer Weile erreichst du die hinterste Ecke. Dir fällt ein kleiner Tunnel auf, der in der Wand des Gewölbes verschwindet. Du fragst dich, ob dieser Gang schon früher da war, aber natürlich machst du dich auf ihn zu erkunden. Nach einer geraumen Zeit und vielen Wegzweigungen kommst du plötzlich an einem Gitter an, hinter dem du einen erleuchteten Raum entdeckst.`n`n
							`yAls du genauer hinschaust fällt dir auf, dass es sich um ein weiters Gewölbe handelt das dem deinen ähnelt.`n
							Du könntest etwas rufen und hoffen, dass du gehört wirst!`n`n';
							$str_form = form_header($link);
							$arr_form_layout = array(
							"preview_message"=>'Vorschau:,preview,message',
							"message"=>"Deine Nachricht,text,500"
							);
							$str_form .= generateform($arr_form_layout,array(),false,'Rufen!');

							$str_form .= '</form>';

							$str_output .= $str_form;
							break;

						case 'true':

							$str_sql = 'SELECT id,houseid FROM house_extensions WHERE type="arch" AND id != '.$arr_ext['id'].' ORDER BY RAND()';
							$db_res = db_query($str_sql);
							$arr_result = db_fetch_assoc($db_res);
							insertcommentary(1,'/msg Von irgendwoher scheint dir eine Stimme etwas zuzurufen: '.$_POST['message'].'`0','h_room'.$arr_result['houseid'].'-'.$arr_result['id']);

							$session['user']['turns']=max(0,$session['user']['turns']-1);

							$str_output .= '`tDu rufst so laut du kannst in der Hoffnung, dass dich jemand hört, doch als du keine Antwort bekommst machst du dich wieder auf den Rückweg durch das Tunnelsystem. Nach einer Weile siehst du wieder den Eingang zu deinem Gewölbe und du bist sehr erleichtert, dass du dich nicht verirrt hast.`n`n
							`yDu stellst fest, dass dich dieser kleine Ausflug `#einen `yWaldkampf gekostet hat.';

							addnav('Zurück',$str_base_file);
							break;
					}
					break;

				case 'herb':

					$str_output .= house_get_title('Auf der Suche');

					$str_output .= '`jDu machst dich auf den Weg in das Gewölbe. Du kannst dich daran erinnern, dass du bei deinen letzten Besuchen stets seltsame Kräuter in einer entfernten Ecke gesehen hast. In diese Richtung machst du dich wieder auf den Weg und nach einer Weile kommst du an genau dieser Ecke vorbei.`n`n';

					if($arr_content['arch_harvest'] != true)
					{
						$int_rand = e_rand(1,3);
						if(isset($arr_content['user_harvest'][$session['user']['acctid']]))
						{
							$int_rand = 3;
						}
						switch($int_rand)
						{
							case 1:
							case 2:

								$str_output .= '`gErfreut siehst du etwas Grünes zwischen den Mauersteinen hervorblitzen. Du machst dich daran es vorsichtig herauszuholen und stellst fest, dass es `^Wermut `gist. Leider gibt es hier nichts weiter und so machst du dich wieder auf den Rückweg.';

								item_add($session['user']['acctid'],'wermut');
								$arr_content['user_harvest'][$session['user']['acctid']] = true;
								$arr_content['arch_harvest'] = true;
								break;

							case 3:

								$str_output .= '`gEnttäuscht musst du feststellen, dass hier noch nichts nachgewachsen ist.';
								$arr_content['user_harvest'][$session['user']['acctid']] = true;
								break;
						}

						$session['user']['turns']=max(0,$session['user']['turns']-1);

					}
					else
					{
						$str_output .= '`gEnttäuscht musst du feststellen, dass hier anscheinend jemand schneller war und bereits fleissig geerntet hat.';
					}

					addnav('Zurück',$str_base_file);
					break;

				case 'wine':

					$str_output .= house_get_title('Weinkeller');
					switch ($_GET['subact'])
					{
						case '':
						{
							addnav('Flasche dazutun',$str_base_file.'&act=wine&subact=putin');
							$str_output .= '`tDu erinnerst dich an die Weinflaschen, die in diesem Gewölbe lagern und machst dich auf die Suche. Nach einiger Zeit findest du das inzwischen total verstaubte Regal.`n`n';

							//$arr_content['arch_drunk'][$session['user']['acctid']]
							if (! house_has_item($arr_ext['houseid'],$arr_ext['id'],'wein')	)
							{
								$str_output .= 'Aber so toll ein Weinregal auch aussieht, erst mit Flaschen darin ist es so richtig schön. Hier lagern bisher jedoch leider keine.';
							}
							else
							{
								$str_sql = 'SELECT accounts.name, accounts.acctid, count(owner) AS c
								FROM '.ITEMS_TABLE.' i
								LEFT JOIN accounts ON ( i.owner=accounts.acctid )
								WHERE i.tpl_id="wein" AND deposit2='.$arr_ext['id'].'
								GROUP BY owner';
								$arr_results = db_get_all($str_sql);

								$str_output .= 'Ah, wie wundervoll die Flaschen hier doch aussehen. Da könnte man glatt schwach werden...';
								if(!isset($arr_content['arch_drunk'][$session['user']['acctid']]))
								{
									$str_output .= '
									<center><table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999">
											<tr class="trhead"><th>Besitzer</th><th>Menge</th><th>Aktion</th></tr>
											<tr><td colspan=3 align="center">Erlesene Weine</td></tr>';

									foreach ($arr_results as $arr_result)
									{
										$str_tr_class = ($str_tr_class == "trdark")?"trlight":"trdark";
										$str_output .= "<tr class='".$str_tr_class."'>";
										$str_output .= "<td>".$arr_result['name']."</td>";
										$str_output .= "<td align='center'>".$arr_result['c']."</td>";
										$str_output .= "<td>".create_lnk('1 Flasche trinken',$str_base_file.'&act=wine&subact=drink&userid='.$arr_result['acctid']."&username=".urlencode($arr_result['name']))."</td>";
										$str_output .= "</tr>";
									}
									
									$str_sql = 'SELECT accounts.name, accounts.acctid, count(owner) AS c
									FROM '.ITEMS_TABLE.' i
									LEFT JOIN accounts ON ( i.owner=accounts.acctid )
									WHERE i.tpl_id="w_vinegar" AND deposit2='.$arr_ext['id'].'
									GROUP BY owner';
									$arr_results = db_get_all($str_sql);

									if(count($arr_results)>0)
									{
										$str_output.='<tr><td colspan=3 align="center">Feinster Essig</td></tr>';
										foreach ($arr_results as $arr_result)
										{
											$str_tr_class = ($str_tr_class == "trdark")?"trlight":"trdark";
											$str_output .= "<tr class='".$str_tr_class."'>";
											$str_output .= "<td>".$arr_result['name']."</td>";
											$str_output .= "<td align='center'>".$arr_result['c']."</td>";
											$str_output .= "<td>".create_lnk('1 Flasche entnehmen',$str_base_file.'&act=wine&subact=vinegar&userid='.$arr_result['acctid']."&username=".urlencode($arr_result['name']))."</td>";
											$str_output .= "</tr>";
										}
									}
									$str_output .= '</table></center>';
								}
								else
								{
									$str_output .= 'Andererseits hattest Du ja heute bereits eine Flasche und zuviel soll auch nicht gesund sein. Also lässt du sie besser in Ruhe weiter altern.';
								}
							}
							break;
						}

						case 'drink': //Wein trinken
						{
							$int_owner = (int)$_GET['userid'];
							$str_owner = stripslashes(($_GET['username']));
							if($int_owner==$session['user']['acctid'])
							{
								$str_owner=($session['user']['sex']?'ihr selbst':'ihm selbst');
							}
							$arr_item = item_get('owner='.$int_owner.' AND tpl_id="wein" AND deposit2='.$arr_ext['id']);
							if(!is_array($arr_item))
							{
								$str_output .= 'Du kannst dir nicht erklären wieso, aber da war jemand schneller als du und hat die Flasche Wein aus deiner Hand wegmaterialisiert. Sachen gibts...';
							}
							elseif($int_owner!=$session['user']['acctid'] && ac_check($int_owner))
							{
								$str_output.='Weil du '.$str_owner.'`0 sehr genau kennst, weißt du natürlich, dass die Flasche nicht dazu bestimmt ist, hier und jetzt getrunken zu werden. Also legst du sie wieder ins Regal.';
							}
							else
							{
								$str_output .= 'In einem unauffälligen Moment greifst du nach der Flasche und öffnest sie. Es dauert nicht lange und du hast die Flasche komplett geleert.`n
								`n
								`4Ob das so eine gute Idee war? Jedenfalls fühlst du dich zu neuen Taten berufen!';

								$arr_buff = array(
								'name'=>'`4Weinrausch',
								'rounds'=>15,
								'wearoff'=>'`4Dein Rausch lässt nach.`0',
								'atkmod'=>1.15,
								'defmod'=>0.9,
								'roundmsg'=>'`ADer Wein lässt dich deine Deckung vergessen, aber hart zuschlagen!`0',
								'activate'=>'offense,defense'
								);
								buff_add($arr_buff);

								$session['user']['turns'] = max(0, $session['user']['turns'] - 2);

								//Je älter die Flasche, desto mehr Alk ist darin (Alter = #gemscost)
								//Die rechnung ist zwar beknackt, aber es reicht für diesen Zweck voll aus
								$session['user']['drunkenness'] += (20 + ($arr_item['gems'] * 5) );
								$arr_content['arch_drunk'][$session['user']['acctid']] = 1;

								item_delete('owner='.$int_owner.' AND tpl_id="wein" AND deposit2='.$arr_ext['id'],1);

								insertcommentary($session['user']['acctid'],': `that sich einer Flasche Wein von `I'.$str_owner.'`t bedient.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id']);
								if($int_owner!=$session['user']['acctid'])
								{
									debuglog('trank eine Flasche Wein von',$int_owner);
								}
							}

							break;
						}

						case 'vinegar': //Essig entnehmen
						{
							$int_owner = (int)$_GET['userid'];
							$str_owner = stripslashes(($_GET['username']));
							if($int_owner==$session['user']['acctid'])
							{
								$str_owner=($session['user']['sex']?'ihr selbst':'ihm selbst');
							}
							$arr_item = item_get('owner='.$int_owner.' AND tpl_id="w_vinegar" AND deposit2='.$arr_ext['id']);
							if(!is_array($arr_item))
							{
								$str_output .= 'Du kannst dir nicht erklären wieso, aber da war jemand schneller als du und hat die Flasche Essig aus deiner Hand wegmaterialisiert. Sachen gibts...';
							}
							elseif($int_owner!=$session['user']['acctid'] && ac_check($int_owner))
							{
								$str_output.='Weil du '.$str_owner.'`0 sehr genau kennst, weißt du natürlich, dass die Flasche für andere Dinge bestimmt ist. Also legst du sie wieder ins Regal.';
							}
							else
							{
								$str_output .= 'Du greifst nach der Flasche und lässt sie in deinem Beutel verschwinden.';

								item_set('owner='.$int_owner.' AND tpl_id="w_vinegar" AND deposit2='.$arr_ext['id'],array('owner'=>$session['user']['acctid'], 'deposit1'=>0, 'deposit2'=>0),true,1);

								insertcommentary($session['user']['acctid'],': `that sich eine Flasche `GWeinessig`t von `I'.$str_owner.'`t genommen.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id']);
								if($int_owner!=$session['user']['acctid'])
								{
									debuglog('nahm eine Flasche Essig von',$int_owner);
								}
							}

							break;
						}

						case 'putin': //Flasche dazutun
						{
							$arr_wine=item_get('tpl_id="wein" AND deposit1=0 AND deposit2=0 AND owner='.$session['user']['acctid']);
							if(is_array($arr_wine))
							{
								$arr_wine['deposit1']=$arr_ext['houseid'];
								$arr_wine['deposit2']=$arr_ext['id'];
								if(item_set('id='.$arr_wine['id'],$arr_wine,true,1))
								{
									$str_output.='`tDu legst deine Weinflasche in das Regal. Irgendwann wird sicher die passende Gelegenheit sein, diese Flasche zu öffnen.
									`n`7(Solltest du die Flasche wieder ins Inventar packen wollen, so nutze die Auslagern-Funktion in deinem Inventar)';
								}
								else
								{
									$str_output.='`tIn diesem Weinregal ist einfach kein Platz für noch eine weitere Flasche. Und bevor das gute Stück wegen Gewaltanwendung kaputtgeht behältst du es lieber im Inventar.';
								}
							}
							else
							{
								$str_output.='`tWenn es doch hier unten nicht so verdammt dunkel wäre... Du bist dir sicher, eine Weinflasche im Inventar zu haben, findest aber keine.';
							}
							break;
						}
					}
					addnav('Zurück',$str_base_file);
					break;
			}

			if($str_content_md5  != md5(utf8_serialize($arr_content)))
			{
				db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);
			}

			$str_output .= '`0';
			output($str_output);

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
