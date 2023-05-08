<?php
#Folterkammer - Ein Gemach für das Atrahor-Wohnviertel
#
#Das Copyright liegt bei Louis Huppenbauer | Harthas | Taemor
#Änderungen am Inhalt und Code sind erlaubt, sofern der Copyright-Hinweis erhalten bleibt
#
#
#To-Do:

#Die gebrauchten Dateien werden eingebunden
require_once( HOUSES_EXT_PATH.'_rooms_common.php' );

function house_ext_torturechamber ($str_case , $arr_ext , $arr_house) {

	global $session , $str_base_file , $bool_not_invited , $bool_howner , $bool_rowner;

	# Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);
	$str_content_md5 = md5($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);

	switch($str_case) {

		# Innen
		case 'in':
			
			if($arr_content['timestamp'] != getgamedate())
			{
				$arr_content['visited'] = array();
				$arr_content['timestamp'] = getgamedate();
			}
			
			#Binden wir die Arrays ein
			require_once( HOUSES_EXT_PATH.'torturechamber.inc.php' );

			#Definieren oder erstellen wir benötigte Variablen und Konstanten
			$string_output = '';
			$newstext = '';
			define( 'FOLTERKAMMER_ERKUNDET', 2 ); #Wie oft darf denn die Folterkammer erkundet werden?
			define( 'TORTURES_PER_DAY', 5 );


			#Und hier beginnt die ganze Navigation
			switch ( $_GET['act'] ) 
			{

				case 'suchen': #Man kann die Folterkammer auch erkunden
				
				$string_output .= get_title('`tFolterkammer durchsuchen');

				if ( ($session['user']['turns'] > 0) && ((int)$arr_content['visited'][$session['user']['acctid']] <= FOLTERKAMMER_ERKUNDET) )
				{
					$string_output .= '`TNeugierig wie du bist, blickst du dich suchend in der Folterkammer um.`nUnd du findest... ';
					$random = mt_rand( 1, 20 );

					switch ( $random ) 
					{

						case 1:
						case 2:
						case 3:
							$string_output .= 'einen kleinen Knochen. Kaum etwas wert, aber vielleicht nimmt der Händler ihn ja doch.';

							item_add($session['user']['acctid'],'beutdummy',array('tpl_name'=>'Kleiner Knochen','tpl_gold'=>5,'tpl_description'=>'Ein kleiner Knochen, den du in der Folterkammer gefunden hast. Beinahe wertlos.'));

							break;

						case 8:
						case 4:
							$string_output .= 'einen Edelstein! Was für ein Glück. Zufrieden läufst du wieder weiter.';
							$session['user']['gems']++;
							break;

						case 5:
						case 6:
						case 7:
							$string_output .= 'etwas Gold! Hatte wohl jemand hier verloren.';
							$session['user']['gold'] += 200;
							break;

						default:
							$string_output .= 'absolut nichts. Zu schade. Vielleicht ja beim nächsten Mal.';
							break;
					}

					$session['user']['turns']--;

					$arr_content['visited'][$session['user']['acctid']] = ((int)$arr_content['visited'][$session['user']['acctid']])+1;
				}
				else 
				{
					$string_output .= '`TFür heute fühlst du dich bereits etwas zu erschöpft, um dich noch gross umzusehen. Morgen ist ja auch noch ein Tag.';
				}				

				addnav( 'Zurück' );
				addnav( 'Zurück' , $str_base_file );
				break;
				
				case 'opfer_waehlen':
					$string_output .= '`TNatürlich brauchst du ein adäquates Opfer für deine Pläne. Nun, schaun wir mal wer sich dir hier so bereitwillig präsentiert.`n`n';
					
					if(!isset($_GET['subact']) && count($arr_content['subscription']) == 0)
					{
						$string_output .= 'Leider musst Du feststellen, dass sich noch keine der Hausbewohner in die Liste der dankbaren Opfer hat eintragen lassen. Es bleibt dir also nichts anderes übrig, als irgendwen zu schnappen, der grad vorbei kommt. ';
						$bool_torture_anybody = true;
					}
					
					if($_GET['subact'] == 'someone' || $bool_torture_anybody)
					{
						$str_victim = $folter_opfer[ e_rand(0, count($folter_opfer)-1 ) ];
						$string_output .= 'Genussvoll grinsend gehst du durch die Reihe der angeketteten Opfer und bleibst schlussendlich vor einer armseligen Gestalt stehen, die den Namen `y'.$str_victim.'`T trägt, stehen. ';
						$arr_content['tortures'][ $session['user']['acctid'] ]['actor']['name']	=$session['user']['name'];
						$arr_content['tortures'][ $session['user']['acctid'] ]['actor']['id']	=$session['user']['acctid'];
						
						$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name']= $str_victim;
						$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['id']	= 0;
						
						addnav('Zurück',$str_base_file.'&act=foltern');
					}
					else 
					{
						if(isset($_GET['choose']))
						{
							$string_output .= '`TOh ja, das ist es, '.$arr_content['subscription'][(int)$_GET['choose']] ['name'].'`T soll leiden!';
							$arr_content['tortures'][ $session['user']['acctid'] ]['actor']['name']	=$session['user']['name'];
							$arr_content['tortures'][ $session['user']['acctid'] ]['actor']['id']	=$session['user']['acctid'];
							
							$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name']= $arr_content['subscription'][(int)$_GET['choose']] ['name'];
							$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['id']	= $arr_content['subscription'][(int)$_GET['choose']] ['id'];
							addnav('Zurück',$str_base_file.'&act=foltern');
							addnav('Jemand anders wählen',$str_base_file.'&act=foltern');
						}
						else 
						{
							$string_output .= 'Genüsslich grinsend blickst du über die Liste deiner Bewohner, die sich für eure besonderen Spielchen interessieren.`n`n';
							foreach($arr_content['subscription'] as $arr_user)
							{
								//Selbst foltern geht nicht
								if($arr_user['id'] == $session['user']['acctid'])
								{
									continue;
								}
								$string_output .= '`n'.$arr_user['name'].'`0 - '.create_lnk('Auswählen',$str_base_file.'&act=opfer_waehlen&choose='.$arr_user['id']);
							}
							addnav('Zurück',$str_base_file.'&act=foltern');
						}
					}
					break;

				case 'foltern':
					$string_output .= get_title('`tFoltern');
					$string_output .= '`THinter einer der vielen Türen befindest du dich in einem bizarren Raum. Die Wände sind mit allerlei Gegenständen behangen und es hängt ein metallischer Geruch in der Luft - Blut.`n';
					
					//Erstmal ein Opfern auswählen
					if(!isset($arr_content['tortures'][ $session['user']['acctid'] ]))
					{
						$string_output .= '`TZunächst müsstest du dich allerdings entscheiden wen du foltern magst, denn sonst macht die schönste Ausstattung ja keinen Sinn, oder?';
						addnav('Einen Bewohner foltern',$str_base_file.'&act=opfer_waehlen');
						addnav('Irgendwen foltern',$str_base_file.'&act=opfer_waehlen&subact=someone');
					}
					else 
					{
						if ( isset($_GET['was']) ) 
						{
							$folterwerkzeug = $folterwerkzeug[$_GET['was']];
							//Überschreiben von $string_output ist gewünscht
							$string_output = get_title('`T'.$folterwerkzeug['name']).'`T'.str_replace('%victim%',$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name'].'`T',$folterwerkzeug['beschreibung']);
							
							//Kommentar einfügen
							switch (e_rand(0,5)) 
							{
								case 0:
									insertcommentary(1,'/msg`TMan hört die Schreie von '.$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name'].'`T durch die Flure schallen, als '.$arr_content['tortures'][ $session['user']['acctid'] ]['actor']['name'].'`T '.($session['user']['sex'] == 0?'sein/e':'ihr/e').' '.$folterwerkzeug['name'].'`T benutzt.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_folter');		
									break;
							
								case 1:
									insertcommentary(1,'/msg`T'.$session['user']['name'].'`Ts '.$folterwerkzeug['name'].' `Twütet gar boshaft an '.$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name'].'`Ts Körper.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_folter');
									break;
							
                case 2:
                  insertcommentary(1,'/msg`T'.$session['user']['name'].'`T lässt sich voller Freude mit '.($session['user']['sex'] == 0?'seiner/m':'ihrer/m').' `T'.$folterwerkzeug['name'].'`T an '.$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name'].'`T aus.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_folter');
                break;
                
                case 3: 
                  insertcommentary(1,'/msg`T'.$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name'].'`T wird von '.$session['user']['name'].'`Ts '.$folterwerkzeug['name'].'`T übel zugerichtet.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_folter');
                break;
                
                case 4:
                  insertcommentary(1,'/msg`TDie Schreie von '.$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name'].'`T hallen in den Gängen wider, als '.$session['user']['name'].'`T demonstriert, wie man ein/e '.$folterwerkzeug['name'].'`T richtig benutzt.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_folter');
                break;
                
                case 5:
                  insertcommentary(1,'/msg`T'.$session['user']['name'].'`T hat Spaß mit '.($session['user']['sex'] == 0?'seiner/m':'ihrer/m').' '.$folterwerkzeug['name'].'`T, '.$arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name'].'`T eher weniger...','h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_folter');
                break;
              }
							
							//Runde abziehen
							if(e_rand(0,50) == 50)
							{
								$session['user']['turns'] = max(0,$session['user']['turns']-1);
							}
							
							//Wenn gefoltert wird gibts Boni/Mali
							if($arr_content['tortures'][ $session['user']['acctid'] ]['victim']['id'] == 0)
							{
								if(e_rand(1,5) == 5)
								{
									$session['user']['charm'] = max(0,$session['user']['charm']-1);
								}
							}
							else 
							{
								$int_id = $arr_content['tortures'][ $session['user']['acctid'] ]['victim']['id'];
								$str_name  = $arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name'];
								$db_res = db_query('SELECT reputation FROM accounts WHERE acctid='.$int_id);
								$arr_user = db_fetch_array($db_res);

								//Anzahl der Foltervorgänge hochzählen	
								$arr_content['torturecount'][ $str_name ] = ((int)$arr_content['torturecount'][ $str_name ])+1;
								
								//Mit geringer Wahrscheinlichkeit wirkt sich das Spielchen auch auf die Nutzer aus
								if(e_rand(1,100) == 100)
								{
									if($arr_user['reputation']<0)
									{
										user_update(
											array('reputation'=>max(0,$arr_user['reputation']-1)),
											$int_id
										);
										$session['user']['reputation']+=1;
									}
									else 
									{
										$session['user']['reputation'] = max(-50,$session['user']['reputation']-1);
										user_update(
											array('reputation'=>max(0,$arr_user['reputation']+1)),
											$int_id
										);
									}
								}
							}
							addnav('Aktionen');
							addnav( 'Weiter foltern' , $str_base_file.'&act=foltern' );
						}
						else 
						{
														
							$arr_items = house_get_items($arr_ext['houseid'],$arr_ext['id'],'',true);
							
							if(count($arr_items) == 0)
							{
								$str_items_to_buy = '';
								foreach($folterwerkzeug as $item)
								{
									if($item['expose'] === false)
									{
										continue;	
									}
									$str_items_to_buy .= $item['name'].', ';
								}
								$string_output .= 'Leider sind hier bisher noch gar keine Folterinstrumente eingelagert, so dass Du niemanden foltern könntest, selbst wenn du wolltest. Allerdings hat der Wanderhändler wie du ja weißt auch für solche Gelüste das passende Zubehör vorrätig. Im letzten Jahreskatalog stand doch etwas von '.$str_items_to_buy.', oder?';
							}
							else 
							{
								$str_victim_name 	= $arr_content['tortures'][ $session['user']['acctid'] ]['victim']['name'];
								$str_victim_id 		= $arr_content['tortures'][ $session['user']['acctid'] ]['victim']['id'];
								$string_output .= 'Außerdem fallen dir diverse Folterinstrumente ins Auge, mit denen du '.$str_victim_name.' `Tviel "Spaß" bereiten könntest.`nWürdest du vielleicht gerne mal eines ausprobieren?';
								addnav('Folterwerkzeug');
								//Alle eingelagerten items ermöglichen ein wenig Folterei
								foreach ($arr_items as $item)
								{		
									//Wenn das Item auch als Folterwerkzeug erkannt wurde
									if(!empty($folterwerkzeug[$item['tpl_id']]))
									{
										addnav( $folterwerkzeug[ $item['tpl_id'] ]['name'] , $str_base_file.'&act=foltern&was='.$folterwerkzeug[ $item['tpl_id'] ]['id'] );
									}
								}
							}
						}
						
						output($string_output);
						$string_output = '';
						//Chat einbinden!
						viewcommentary('h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_folter','Kommentar hinzufügen',25,'sagt',false,true,false,0,false,true,3);
					}

					addnav( 'Zurück' );
					addnav( 'Zurück in die Folterkammer' , $str_base_file );
					break;

				case '':
					$random = mt_rand( 0 , 300 );
					switch( $random ) 
					{
						case 295: #Manchmal tritt man auf einen Nagel
						case 296:
							$string_output .= '`TDoch in einem Moment der Unachtsamkeit trittst du auf einen kleinen, aber äusserst spitzigen und rostigen Nagel. Das tut weh! Du verlierst leider einige Lebenspunkte. ';

							if ( mt_rand( 1 , 2) == 1 ) 
							{
								$string_output .= 'Und zu allem Übel scheint sich die Wunde auch noch zu entzünden. Du bist etwas geschwächt für die nächsten Runden.';
								
								$arr_buff['name']     = 'Entzündeter Fuss';
								$arr_buff['rounds']   = 30;
								$arr_buff['wearoff']  = 'Die Entzündung ist endlich abgeklungen.';
								$arr_buff['defmod']   = 0.90;
								$arr_buff['atkmod']   = 0.95;
								$arr_buff['roundmsg'] = 'Du kannst nur noch humpeln.';
								$arr_buff['activate'] = 'defense';
								
								buff_add($arr_buff);

							}
							$session['user']['hitpoints'] *= 0.9;

							$newstext = $session['user']['name'].' ist auf einen rostigen Nagel getreten. Hoffentlich gibt das keine Entzündung.';
							break;

						case 297: #... oder man wird gesehen
						case 298:
							$string_output .= '`TAls du dich gerade im Raum umblickst, bemerkst du plötzlich die vollkommen entsetzten Gesichter der Leute auf der Strasse. Sowas hätten sie von dir wohl nicht erwartet. '
							.'Und leider scheint dies deinem Ansehen eher schlecht zu bekommen, denn es sinkt deutlich...`n`n';

							$session['user']['reputation'] = max(-50,$session['user']['reputation']-5);

							$newstext = $session['user']['name'].'`T wurde dabei beobachtet, als '.($session['user']['sex'] == 0? 'er' : 'sie').' gerade die Folterkammer betrat. Pfui, sowas macht man nicht.';
							break;

						case 299: #Oder man ist einfach ungeschickt
						case 300:
							$string_output .= '`TDoch als du gerade einen Schritt gehen willst, rutschst du in einer Blutpfütze aus, und bist von oben bist unten bekleckert. Vor lauter Scham sinkt dein Charme etwas.`n`n';

							$session['user']['charm'] = max(0,$session['user']['charm']-2);

							$newstext = $session['user']['name'].' `0kam blutbekleckert aus der Folterkammer. Was '.($session['user']['sex'] == 0 ? 'er' : 'sie').' da wohl gesucht hat?';
							break;

					}
					if ( !empty($newstext) ) 
					{
						addnews( $newstext );
					}

					addnav( 'Aktionen' );
					addnav( 'Folterkammer betreten', $str_base_file.'&act=foltern' );
					addnav( 'Genauer durchsuchen', $str_base_file.'&act=suchen' );
					addnav( 'Strichliste des Folterknechts', $str_base_file.'&act=stats' );
					addnav( '`/Hinweise`0', $str_base_file.'&act=notice' );
					addnav( 'Opferwahl');
					addnav( 'Als Opfer eintragen', $str_base_file.'&act=subscribe');
					addnav( 'Ein Opfer wählen', $str_base_file.'&act=opfer_waehlen' );
					
					break;
				case 'notice':
					{
						$string_output .= get_title('`tWichtige Hinweise');
						$string_output .= '`$Bitte beachtet folgendes: `yEine Folterkammer bietet selbstverständlich die Möglichkeit zu nicht-jugendfreiem Rollenspiel und regt dieses auch indirekt an. Wir möchten Euch als Spieler jedoch bitten, die hier gebotenen Möglichkeiten im Rahmen des guten Geschmacks zu nutzen und darauf zu achten, dass nur volljährige Mitspieler Zugang erhalten und spielen. Macht dieses Gemach - wenn möglich - zu einem Raum, zu dem nur ausgewählte Leute Zugriff haben.';
						addnav('Zurück',$str_base_file);
						break;	
					}
				case 'subscribe':
					{
						$string_output .= get_title('`tDie Liste der Opfer');
						if($_GET['decission'] == 1)
						{
							//User eintragen oder austragen
							if(isset($arr_content['subscription'][$session['user']['acctid']]))
							{
								$string_output .= '`TDu blickst über die Liste an freiwilligen Opfern und fährst mit dem Finger darüber bis du deinen Namen findest. Dann stichst du dir mit der an einem roten Band baumelnden Feder in den Finger und streichst deinen Namen aus der Liste. Dem Codex folgend wird dich nun niemand mehr foltern!';
								addnav('Zurück',$str_base_file);
								unset($arr_content['subscription'][$session['user']['acctid']]);
								
								//User will nicht mehr gefoltert werden, also müssen alle Sitzungen 
								//in denen der User ausgewählt wurde gelöscht werden
								foreach($arr_content['tortures'] as $arr_torture)
								{
									if($arr_torture['victim']['id'] == $session['user']['acctid'])
									{
										unset($arr_content['tortures'][ $arr_torture['actor']['id'] ]);
									}	
								}
							}
							else 
							{
								$string_output .= '`TDu blickst über die Liste an freiwilligen Opfern und fährst mit dem Finger bedächtig darüber bis du einen freien Platz am Ende einer der Seiten findest. Dann stichst du dir mit der an einem roten Band baumelnden Feder in den Finger und trägst dich in die Liste ein. Dem Codex folgend darf dich nun jeder hier foltern! Mit einem Grinsen wendest du dich ab.';
								addnav('Zurück',$str_base_file);
								$arr_content['subscription'][$session['user']['acctid']] ['id'] = $session['user']['acctid'];
								$arr_content['subscription'][$session['user']['acctid']] ['name'] = $session['user']['name'];
							}
						}
						else 
						{
							$string_output .= '`TDeine morbiden Fantasien haben dich in eine Ecke der Folterkammer geführt, in denen ein Buch steht, dessen Seiten mit Blut geschrieben wurden. Nicht um irgendein okkultes Ritual durchzuführen, sondern um bei vollem Bewusstsein zu versichern, dass du auch ernst meinst was du mit diesem Vertrag unterzeichnest. Mit deiner Unterschrift erklärst du dich einverstanden an gewissen Experimenten teilzunehmen...`n`n'.
							(isset($arr_content['subscription'][$session['user']['acctid']])?'Willst du dich wieder austragen lassen?':'Möchtest Du Dich also tatsächlich eintragen lassen?');
							addnav('Ja, dies ist mein Wille',$str_base_file.'&act=subscribe&decission=1');
							addnav('Nein, noch nicht',$str_base_file);
						}
						break;
					}
				case 'stats':
					{
						$string_output .= get_title('`tDie Liste des Folterknechts');
						$string_output .= '`TMit einem diabolischen Grinsen sitzt ein Alter Mann angekettet in einer Ecke. In das Brett zu seinen Füßen kratzt er mit blutigen Nägeln ein wer in diesem Raum wie oft gefoltert wurde. Nur zum Spass, wie es sich versteht. Du wirfst einen Blick auf das Brett und kannst folgendes lesen:`n`n';
						
						if($_GET['subact'] == 'reset')
						{
							$arr_content['torturecount'] = array();
							$string_output .= '`n`nKurzerhand nimmst du ihm sein altes Brett hin und gibst ihm ein neues. Seltsam, er bemerkt es nichtmal, sondern fängt einfach wieder an seine Beobachtungen mit den Fingernägeln darin einzukratzen.`n`n';
						}
						
						$arr_top_list = $arr_content['torturecount'];
						
						if(count($arr_top_list)>0)
						{
							asort($arr_top_list);
							$int_i = 1;
							foreach ($arr_content['torturecount'] as $key => $val)
							{
								$string_output .= $int_i.': '.$key.'`t ('.$val.'x)`n';
								$int_i++;
							}
						}
						else 
						{
							$string_output .= 'Es wurde noch niemand gefoltert.';	
						}
						addnav('Zurück',$str_base_file);
						if($bool_rowner || $bool_howner)
						{
							addnav('Liste zurücksetzen',$str_base_file.'&act=stats&subact=reset');
						}
						break;	
					}

				default:

					break;
			}//act switch

			//Content Array zurückschreiben
			if($str_content_md5  != md5(utf8_serialize($arr_content)))
			{
				db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);
			}
			
			if(mb_strlen($string_output)>0)
			{
				output( $string_output );
			}
			
			# Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;
			# END case in

			# Bau gestartet
		case 'build_start':

			# Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;

			# Bau fertig
		case 'build_finished':

			# Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;

			# Abreißen
		case 'rip':

			# Gemeinsam genutzten Code holen
			_rooms_common_switch($str_case,$arr_ext,$arr_house);

			break;

	}	# END Main switch
}

?>
