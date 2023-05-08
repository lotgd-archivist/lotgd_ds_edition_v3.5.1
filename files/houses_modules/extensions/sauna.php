<?php
// Die Sauna
// by Dragonslayer

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_sauna ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);
	$str_content_md5 = md5($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);

	switch($str_case) {

		// Innen
		case 'in':

			if($arr_content['timestamp'] != getgamedate())
			{
				$str_oil_backup = $arr_content['oil'];
				//$arr_content = array();
				$arr_content['timestamp'] = getgamedate();
				$arr_content['oil'] = (empty($str_oil_backup)?'Tannennadel':$str_oil_backup);
			}

			switch($_GET['act']) {

				case '':
					{
						$str_content = house_get_title('Die Sauna');
						$str_content .= '`tDu befindest dich im Vorraum einer kleinen, gemütlichen Holzsauna.`n
						Hier befinden sich neben einigen gemütlichen Liegen und dem Stapel voller flauschiger Handtücher noch ein Tauchbecken mit eiskaltem Wasser,
						mehrere Duschen und ein ganzes Arsenal an Duftwässerchen, die nur darauf warten, dich in einem ordentlichen Aufguss einzunebeln.`n
						Die Tür zur Sauna ist geschlossen, aber das Prasseln des offenen Feuers unter den Steinen kannst du bis hierher hören. Direkt neben der Tür hängt
						"Das kleine Sauna 1x1". Was möchtest du nun tun?';
						addnav('1?Das Sauna 1x1 lesen',$str_base_file.'&act=read_rules');

						if($arr_content['prepare'][$session['user']['acctid']] == true)
						{
							addnav('k?Dich ankleiden',$str_base_file.'&act=prepare');
						}
						else
						{
							addnav('k?Dich entkleiden',$str_base_file.'&act=prepare');
						}

						addnav('Säubern',$str_base_file.'&act=clean');
						addnav('Einen Saunagang einlegen',$str_base_file.'&act=sauna');
						addnav('T?Ab ins Tauchbecken',$str_base_file.'&act=cool_down');
						addnav('Ausruhen',$str_base_file.'&act=relax');
						addnav('Aufgussmittel wählen',$str_base_file.'&act=oil');
						break;
					}
				case 'read_rules':
					{
						addnav('v?Hab ich verstanden!',$str_base_file);
						$str_content = house_get_title('Das kleine Sauna 1x1');
						$str_content .= '`tFür ein einmaliges Saunaerlebnis empfiehlt ihr persönlicher Fitnessberater:`n
						<ul>
						<li>Niemals mit Straßenkleidung den Saunabereich betreten, wir achten sehr auf Hygiene</li>
						<li>Säubere Dich bevor Du in die Sauna gehst, die anderen Gäste möchten sich entspannen und nicht wie im Trollhaus fühlen.</li>
						<li>Lege einen Saunagang ein, aber nicht zu lang!</li>
						<li>Mache einen Tauchgang um die Lebensgeister wieder anzuregen</li>
						<li>Entspannen und die heilende Kraft eines Saunaganges genießen.</li>
						</ul>';
						break;
					}
				case 'prepare':
					{
						$str_content = house_get_title('Umkleiden');

						//Wieder ankleiden
						if($arr_content['prepare'][$session['user']['acctid']] == true)
						{
							$str_content .= '`tDu lässt dein Handtuch fallen und schnappst dir deine Kleidung.
							1-2-fix bist du auch schon wieder angekleidet.';

							unset($arr_content['prepare'][$session['user']['acctid']]);
						}
						//Entkleiden
						else
						{
							$str_content .= '`tSchnell hüpfst du aus deinen Kleidern und schlingst dir eines der großen Handtücher um.
							Deine Kleidung legst du vorsichtig zusammen und auf einen dafür geeigneten Platz';

							$arr_content['prepare'][$session['user']['acctid']] = true;
						}
						addnav('Fertig',$str_base_file);
						break;
					}
				case 'oil':
					{
						$str_content .= house_get_title('Aufgussmittel wählen');

						if($_GET['change'] != 1)
						{
							$str_form .= form_header($str_base_file.'&act=oil&change=1');
							$arr_form = array(	"Zu verwendendes Saunaöl,title",
												"preview_oil"=>'Vorschau:,preview,oil',
												"oil"=>"Name des ätherischen Öls,text,255"
							);
							$str_form .= generateform($arr_form,array(),false,'Verwenden');
						}
						else
						{
							$arr_content['oil'] = $_POST['oil'];
							addnav('a?Lieber etwas anderes',$str_base_file.'&act=oil');
						}
						addnav('Perfekt, das nehmen wir',$str_base_file);

						$str_content .= '`tDu stehst vor einem kleinen Holzregal voller ätherischer Öle und sonstiger Duftmittel die man zum allgemeinen Wohlbefinden in den Aufguss geben kann.`n
						Momentan wird `y'.$arr_content['oil'].'`t verwendet, du kannst dir aber auch etwas anderes aussuchen.';

						$str_content .= $str_form;

						break;
					}
				case 'clean':
					{
						addnav('s?Dich säubern',$str_base_file.'&act=clean&subact=shower');
						addnav('a?Die Seife aufheben',$str_base_file.'&act=clean&subact=soap');
						addnav('Fertig!',$str_base_file);

						switch($_GET['subact'])
						{
							case '':
								{
									$str_content .= house_get_title('Die Dusche');
									$str_content .= '`tAn der Wand hängen mehrere Eimer, die durch einen ausgekügelten Mechanismus ständig mit frischem Wasser gefüllt werden.
									Da das Wasser zuvor durch die Sauna floss ist es auch noch angenehmen warm.';
									break;
								}
							case 'shower':
								{
									$str_content = house_get_title('Wischi-Waschi machen');

									if($arr_content['prepare'][$session['user']['acctid']] != true)
									{
										$str_content .= '`tDu stellst dich unter einen der Eimer und ziehst vorsichtig an der Schnur.
										Warmes Wasser ergiesst sich über deine Haare und Gesicht. Ein herrliches Gefühl, den Schmutz des Alltags von deiner Haut und Kleidung zu waschen.`n`n
										`$KLEIDUNG?!?`n`n
										`tOh verdammt, du hättest dich vorher doch besser ausziehen sollen. Pitschnass stapfst du aus der Dusche heraus.
										`yMein Gott ist das peinlich, du verlierst einen Charmepunkt';
										$session['user']['charm']=max(0,$session['user']['charm']-1);
									}
									else
									{
										$str_content .= '`tDu stellst dich unter einen der Eimer und ziehst vorsichtig an der Schnur.
										Warmes Wasser ergiesst sich über deine Haare und Gesicht. Ein herrliches Gefühl, den Schmutz des Alltags von deiner Haut zu waschen.
										Nach kurzer Zeit bist du der Ansicht endlich sauber zu sein und trocknest dich artig ab.';
										$arr_content['shower'][$session['user']['acctid']] = true;
									}
									break;
								}
							case 'soap':
								{
									$str_content = house_get_title('Seife aufheben');
									$arr_content['soap'][$session['user']['acctid']] += 1;
									if($arr_content['soap'][$session['user']['acctid']] == 1)
									{
										$str_content .= '`tDu hebst die Seife vom Fussboden auf.`n`n
										`yEs passiert nichts weiter...`t Oder was dachtest du?';
									}
									elseif($arr_content['soap'][$session['user']['acctid']] == 2)
									{
										$str_content .= '`tEs passiert wirklich nichts. Das ist ein jugendfreies Spiel.';
									}
									elseif($arr_content['soap'][$session['user']['acctid']] == 3)
									{
										$str_content .= '`tGlaub es doch, hier wird nichts schweinisches passieren...';
									}
									elseif($arr_content['soap'][$session['user']['acctid']] == 4)
									{
										$str_content .= '`tEy. Du bist echt hartnäckig...';
									}
									elseif($arr_content['soap'][$session['user']['acctid']] == 5)
									{
										$str_content .= '`tJa, ist ja gut. Es passiert ja schon was *räusper* also:`n`n

										`$Dir wird ein Goldstück zugesteckt`n`n

										`tHast du jetzt etwa was Sinnvolles erwartet? Jetzt reichts aber! Ab in die Sauna!';
										$session['user']['gold']++;
									}
									else
									{
										$str_content .= '`tNee, das wars jetzt!';
									}
									break;
								}
						}

						break;
					}
				case 'sauna':
					{
						$str_content .= house_get_title('In der Sauna');
						$str_content .= '`tDu entspannst bequem auf einer der Bänke und lauschst dem Knacksen des Feuers. ';

						//Trägt noch Klamotten
						if($arr_content['prepare'][$session['user']['acctid']] != true)
						{
							$str_content .= 'Aber als du dich so umsiehst, scheinst du dich in einem Punkt ganz besonders von den anderen Saunagästen zu unterscheiden.`n'.
							($session['user']['sex']?'Nein, nicht der Bart':'Nein, nicht die unrasierten Beine').'`n
							Du trägst noch deine komplette Kleidung. Beschämt gehst du aus der Sauna wieder heraus.';
							insertcommentary(1,'/msg `tAls '.$session['user']['name'].'`t bemerkt, dass '.($session['user']['sex']?'sie':'er').' noch gar nicht entkleidet ist, verlässt '.($session['user']['sex']?'sie':'er').' fluchtartig die Sauna.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_sauna');
							$session['user']['charm']=max(0,$session['user']['charm']-5);
							debuglog('-5 Charm für Saunafehlbenutzung');
							addnav('Raus hier',$str_base_file);
						}
						//Hat sich nicht geduscht
						elseif($arr_content['shower'][$session['user']['acctid']] != true)
						{
							$str_content .= 'Leider merkt man doch recht schnell dass du dich vorher nicht ordentlich abgeduscht hast. Dein herber Geruch würde jedes Wildtier neidisch machen.`n
							Beschämt gehst du aus der Sauna wieder heraus.';
							insertcommentary(1,'/msg `tAls '.$session['user']['name'].'`t bemerkt, dass '.($session['user']['sex']?'sie':'er').' noch gar nicht ordentlich geduscht hat, verlässt '.($session['user']['sex']?'sie':'er').' fluchtartig die Sauna und hinterlässt ein Duftwölkchen...nett ausgedrückt.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_sauna');
							$session['user']['charm']=max(0,$session['user']['charm']-5);
							debuglog('-5 Charm für Saunafehlbenutzung');
							addnav('Raus hier',$str_base_file);
						}
						//Artiger Saunierer
						else
						{
							$arr_content['sauna'][$session['user']['acctid']] = true;
							unset($arr_content['cool_down'][$session['user']['acctid']]);

							addnav('Aufguss machen',$str_base_file.'&act=sauna&subact=aufguss');
							if($bool_rowner)
							{
								addnav('Hier mal aufräumen',$str_base_file.'&act=sauna&subact=clean_comments');
							}
							addnav('Raus hier',$str_base_file);

							if($_GET['subact'] == 'aufguss')
							{
								$str_content .= '`n`n`t"`yEin Aufguss wäre jetzt nett`t", denkst du dir und hebst eine Kelle voll, nach '.$arr_content['oil'].'`t duftendem Wasser aus dem kleinen Eimer.`n
								Mit einem lauten `bZISCHHHH`b verdampft das Wasser auf den Steinen';
								insertcommentary(1,'/msg '.$session['user']['name'].'`t macht einen Aufguss. Sofort wird es unglaublich prickelnd heiss auf deiner Haut und die Luft duftet nach '.$arr_content['oil'].'.','h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_sauna');
							}
							elseif ($_GET['subact'] == 'clean_comments')
							{
								$str_content .= '`n`n`t"`yHier könnt man mal wieder sauber machen`t", denkst du dir und machst kurzerhand die ganze Sauna sauber.`n';
								// Sicherung
							    $sql = "UPDATE commentary SET section='h_room".$arr_house['houseid']."-".$arr_ext['id']."_sauna_s' WHERE section='h_room".$arr_house['houseid']."-".$arr_ext['id']."_sauna'";
							    db_query($sql);
							}

							//Ausgabe vor Saunachat
							output($str_content);
							$str_content = '';
							viewcommentary('h_room'.$arr_house['houseid'].'-'.$arr_ext['id'].'_sauna','Kommentar hinzufügen',25,'sagt',false,true,false,0,false,true,3);
						}
						break;
					}
				case 'cool_down':
					{
						$str_content .= house_get_title('Das Eisbecken');
						addnav('Brrr, fertig',$str_base_file);
						if($arr_content['prepare'][$session['user']['acctid']] != true)
						{
							$str_content .= '`tDu gehst vorsichtig in das eiskalte Wasser hinein und tauchst bis zu Brust unter. "`yKomisch, ist ja gar nicht so kalt`t", denkst du dir. Aber das ist ja auch klar wenn man seine ganzen Klamotten noch an hat...`n
							Beschämt steigst du aus dem Becken wieder heraus.
							`yMein Gott ist das peinlich, du verlierst einen Charmepunkt';
							$session['user']['charm']=max(0,$session['user']['charm']-1);

						}
						elseif($arr_content['sauna'][$session['user']['acctid']] != true)
						{
							$str_content .= '`tDu gehst vorsichtig in das eiskalte Wasser hinein und tauchst bis zu Brust unter. "`yKomisch, ist ja gar nicht so kalt`t", denkst du dir. Aber es wird gewiss noch viel kälter sein wenn du frisch aufgeheizt aus der Sauna kommst.`n';
						}
						else
						{
							$str_content .= '`tDu gehst vorsichtig in das eiskalte Wasser hinein und tauchst bis zu Brust unter. `bEieieiei`b ist DAS kalt!';
							$arr_content['cool_down'][$session['user']['acctid']] = true;
						}
						break;
					}
				case 'relax':
					{
						$str_content .= house_get_title('Ausruhen');

						//Positive wirkung
						if($session['user']['turns']>0 && $arr_content['buff'][$session['user']['acctid']] != true && $arr_content['sauna'][$session['user']['acctid']] == true && $arr_content['cool_down'][$session['user']['acctid']] == true && e_rand(1,2) == 2)
						{
							$str_content .= 'Das so ein Saunagang extrem gesund ist merkst du gerade jetzt sehr deutlich. Die Last des Alltags fällt spürbar von deinen Schultern.`n`n';
							foreach ($session['bufflist'] as $name => $arr_buff)
							{
								//Tiere und Knappen + Flüche kann man nicht ablegen
								if($name == 'mount' || $name == 'decbuff')
								{
									continue;
								}
								if($arr_buff['atkmod']<1 || $arr_buff['defmod']<1)
								{
									$str_content .= $arr_buff['name'].'`t - Dieses Hemmnis '.create_lnk('heilen',$str_base_file.'&act=relax&rejuvenate=1&buff='.urlencode($arr_buff['name'])).'`n';
								}
							}
							addnav('Genug ausgeruht!',$str_base_file);
						}
						elseif($_GET['rejuvenate'] == 1)
						{
							$str_buff = urldecode($_GET['buff']);
							if(is_array($session['bufflist'][$str_buff]))
							{
								buff_remove($str_buff);
								$str_content .= '`tDu spürst wie der negative Einfluss durch '.$str_buff.' `tlangsam verfliegt. Hach herrlich.
								`n
								Schade nur, dass man danach immer so geschafft und durstig ist.';
								$session['user']['turns'] -= 3;
								if($session['user']['turns']<0)
								{
									$session['user']['turns'] = 0;
								}
								addnav('Genug ausgeruht!',$str_base_file);
							}
							else
							{
								$str_content .= '`tHier lief etwas schief. Denken wir nicht weiter drüber nach.';
								addnav('Zurück!',$str_base_file);
							}
							$arr_content['buff'][$session['user']['acctid']] = true;
						}
						else
						{
							$str_content .= 'Die Last des Alltags fällt spürbar von deinen Schultern. Du ruhst dich lang und gemütlich aus.';
							$session['user']['turns']--;
							if($session['user']['turns']<0)
							{
								$session['user']['turns'] = 0;
							}
							addnav('Genug ausgeruht!',$str_base_file);
						}
						unset($arr_content['sauna'][$session['user']['acctid']]);
						unset($arr_content['cool_down'][$session['user']['acctid']]);

						break;
					}
			}

			if($str_content_md5  != md5(utf8_serialize($arr_content)))
			{
				db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);
			}
			output($str_content);

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