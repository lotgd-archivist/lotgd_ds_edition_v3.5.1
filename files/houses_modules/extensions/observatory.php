<?php
// Das Observatorium
// by Dragonslayer

// Gemeinsam genutzten Code holen
require_once(HOUSES_EXT_PATH.'_rooms_common.php');

function house_ext_observatory ($str_case, $arr_ext, $arr_house) {

	global $session,$str_base_file,$bool_not_invited,$bool_howner,$bool_rowner;

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);
	$str_content_md5 = md5($arr_ext['content']);

	_rooms_common_set_env($arr_ext,$arr_house);

	$str_content = '';

	//Ein paar Sternnamen
	$arr_stars = array('Acamar','Achernar','Achird','Acrux','Acubens','Adhafera','Adhara','Agena','Hadar','Agetenar','Ain','Oculus Boreus','Ain al Rami','Alamak','Almaak','Almach','Alasco','Alava','Albaldah','Albali','Albireo','Alcyone','Aldebaran','Cor Tauri','Alderamin','Al Dhanab','Aldhibah','Aldhibain','Alfecca Meridiana','Algenib','Algieba','Algiedi Prima','Algiedi Secunda','Algol','Gorgonea Prima','Algorab','Algorel','Alheka','Alhena','Alifa','Alioth','Al Kaff al Jidhmah','Alkafzah','Alkaid','Benetnasch','Alkalurops','Alkes','Alkhiba','Alkor','Saidak','Almaaz','Al Anz','Alnair','Al Nair al Kentaurus','Al Nath','Alnilam','Alnitak','Alniyat','Alphard','Alphekka','Gemma','Alpheraz','Sirrah','Alpherg','Alphirk','Alrai','Alrakis','Al Rischa','Kaïtain','Okda','Alsciaukat','Mabsuthat','Alshain','Alsuhail','Al Suhail al Wazn','Al Suud al Nujam','Altaïr','Altarf','Althalimain','Aludra','Alula Borealis','Alwaid','Alya','Alzirr','Ancha','Ankaa','Nair al Zaurak','Antares','Arcturus','Arktur','Arich','Porrich','Postvarta','Arkab Posterior','Arkab Prior','Arneb','Asad Australis','Ascella','Asellus Australis','Asellus Borealis','Asmidiske','Asterope','Atik','Atlas','Atria','Avior','Azha','Baten Kaitos','Becrux','Mimosa','Beid','Bellatrix','Betelgeuze','Beteigeuze','Biham','Baham','Botein','Canopus','Suhail','Capella','Castor','Apollo','Celaeno','Chaph','Chara','Cheleb','Kelb al Rai','Choo','Chord','Cor Caroli','Dabih','Deneb','Deneb al Giedi','Deneb-el-Okab','Denebola','Diphda','Deneb Kaitos','Dschubba','Dubhe','Edasich','Electra','Eltamin','Enif','Formalhaut','Fum-el-Samakah','Furud','Gacrux','Giansar','Gienah','Girtab','Gomeisa','Gorgonea Quarta','Gorgonea Secunda','Gorgonea Tertia','Graffias','Akrab','Granatstern','Haedi','Sadatoni','Hoedus I','Hamal','Han','Haratan','Hassaleh','Head of Hydrus','Heze','Hoedus II','Homan','Hyadum Primus','Kaprah','Kaus Australia','Kaus Borealis','Kaus Meridionalis','Keid','Ke Kouan','Ke Kwan','Kerb','Kitalpha','Kochab','Koo She','Kornephoros','Kraz','Kursa','Lesath','Maia','Marfak','Marfik','Markab','Markeb','Matar','Mebsuta','Megrez','Meissa','Heka','Mekbuda','Men','Menkar','Menkarlina','Menkent','Menkib','Merak','Merope','Mesartim','Metallah','Caput Trianguli','Miaplacidus','Minelauva','Auva','Minkar','Gienah Ghurab','Mintaka','Mira','Mirach','Miram','Mirfak','Algenib','Misam','Mizar','Mufrid','Muliphen','Isis','Murzim','Mirzam','Muscida','Nair-al-Butain','Nair al Saif','Hatysa','Nash','Alnasl','Nashira','Nekkar','Nembus','Nihal','Nunki','Peacock','Peione','Phakt','Phaet','Phekda','Pherkad Maior','Polaris','Polaris Australis','Polis','Pollux','Hercules','Praecipua','Prokyon','El-Gomaisa','Propus','Tejat Prior','Rana','Ras-Algethi','Ras-Alhague','Regor','Al-Suhail-al-Muhlif','Regulus','Rigel','Rijl-al-Awwa','Ruchbah','Rukbat','Rutilicus','Sabik','Sadalbari','Sad el Barr','Sadalmelik','Sadalsuud','Sadr','Saiph','Sargas','Sarin','Sceptrum','Schaula','Scheat','Menkib','Schedir','Scheliak','Segin','Seginus','Sheratan','Sidus Ludovicianum','Sirius','Sothis','Aschere','Canicula','Skat','Scheat','Spica','Subra','Suhail Hadar','Naos','Sulaphat','Syrma','Tabit','Taïs','Talita','Tania Australis','Tania Borealis','Tarazed','Taygete','Tejat Posterior','Calx','Theemini','Thuban','Toliman','Rigel Kentaurus','Torcularis Septentrionalis','Trapez','Turais','Tureis','Tuza','Tyl','Unuk-al-Hai','Unuk','Vindemiatrix','Wasat','Wazn','Wezn','Wega','Wei','Wezea','Yed Post','Yed Prior','Yen','Yildun','Zaniah','Zar','Zaurak','Zavijava','Zibal','Zosma','Zuben-al-gubi','Zuben-el-akribi','Zuben-el-dschenubi','Zuben-el-genubi','Zuben-el-hakrabi','Zuben-el-schemali','Brachium');
	//Ein paar Sternbilder in Latein und deutsch
	$arr_constellation = array("Andromeda"=>"Andromeda","Antlia Pneumatica"=>"Luftpumpe","Apus"=>"Paradiesvogel","Aquarius"=>"Wassermann","Aquila"=>"Adler","Ara"=>"Altar","Aries"=>"Widder","Auriga"=>"Fuhrmann","Bootes"=>"Baerenhueter","Caelum"=>"Grabstichel","Camelopardalis"=>"Giraffe","Cancer"=>"Krebs","Canes Venatici"=>"Jagdhunde","Canis Major"=>"Großer Hund","Canis Minor"=>"Kleiner Hund","Capricornus"=>"Steinbock","Carina"=>"Kiel des Schiffs","Cassiopeia"=>"Cassiopeia","Centaurus"=>"Zentaur","Cepheus"=>"Kepheus","Cetus"=>"Walfisch","Chamaeleon"=>"Chamaeleon","Circinus"=>"Zirkel","Columba"=>"Taube","Coma Berenices"=>"Haar der Berenike","Corona Australis"=>"Suedliche Krone","Corona Borealis"=>"Noerdliche Krone","Corvus"=>"Rabe","Crater"=>"Becher","Crux Australis"=>"Kreuz des Suedens","Cygnus"=>"Schwan","Delphinus"=>"Delphin","Dorado"=>"Schwertfisch","Draco"=>"Drache","Equuleus"=>"Fuellen","Eridanus"=>"Fluss Eridanus","Fornax Chemica"=>"Chemischer Ofen","Gemini"=>"Zwillinge","Grus"=>"Kranich","Hercules"=>"Herkules","Horologium"=>"Pendeluhr","Hydra"=>"Wasserschlange","Hydrus"=>"Kleine Wasserschlange","Indus"=>"Inder","Lacerta"=>"Eidechse","Leo"=>"Loewe","Leo Minor"=>"Kleiner Loewe","Lepus"=>"Hase","Libra"=>"Waage","Lupus"=>"Wolf","Lynx"=>"Luchs","Lyra"=>"Leier","Mensa"=>"Tafelberg","Microscopium"=>"Mikroskop","Monocerus"=>"Einhorn","Musca"=>"Fliege","Norma"=>"Winkelmaß","Octans"=>"Oktant","Ophiuchus"=>"Schlangentraeger","Orion"=>"Orion","Pavo"=>"Pfau","Pegasus"=>"Pegasus","Perseus"=>"Perseus","Phoenix"=>"Phoenix","Pictor"=>"Maler","Piscis Austrinus"=>"Suedlicher Fisch","Pisces"=>"Fische","Puppis"=>"Achterdeck des Schiffs","Pyxis Nautica"=>"Schiffskompass","Reticulum"=>"Netz","Sagitta"=>"Pfeil","Sagittarius"=>"Schuetze","Scorpius"=>"Skorpion","Sculptor"=>"Bildhauer","Scutum"=>"Schild","Serpens"=>"Schlange","Sextans"=>"Sextant","Taurus"=>"Stier","Telescopium"=>"Teleskop","Triangulum Australe"=>"Suedliches Dreieck","Triangulum"=>"Dreieck","Tucana"=>"Tukan","Ursa Major"=>"Großer Baer (Großer Wagen)","Ursa Minor"=>"Kleiner Baer (Kleiner Wagen)","Vela"=>"Segel des Schiffs","Virgo"=>"Jungfrau","Piscis Volans"=>"Fliegender Fisch","Vulpecula"=>"Fuchs");
	//Ein paar weitere Sternbilder fuer das schwere Quiz
	$arr_constellation_picture = array(1=>"Andromeda","Fuhrmann","Baehrenshueter","Kassiopeia","Drache","Fluss Eridanus","Zwillinge","Herkules","Kleine Wasserschlange","Eidechse","Kleiner Baer","Loewe","Hase","Luchs","Pegasus","Fische","Achterschiff","Schuetze","Skorpion","Stier","Jungfrau","Giraffe","Orion","Einhorn","Goldfisch","Schlangentraeger","Waage","Adler","Steinbock","Wassermann","Suedlicher Fisch","Kranich","Tukan","Perseus","Walfisch","Ofen","Phoenix");

	switch($str_case) {

		// Innen
		case 'in':

			if($arr_content['timestamp'] != getgamedate())
			{
				$arr_content = array();
				$arr_content['timestamp'] = getgamedate();
			}


			switch($_GET['act']) {

				case '':
					$time = getgametime(true);
					$hour = get_gametime_part('h',$time);

					$str_content .= house_get_title('Das Observatorium');
					$arr_wetter = Weather::$weather[Weather::$actual_weather];
					$str_content .= '`cEs ist jetzt etwa `y'.$time.'. Das Wetter ist "'.$arr_wetter['name'].'"`c`n`n';
					$str_content .= '`tLeise setzt du deinen Schritt über die Türschwelle in den kleinen Raum und hältst inne.
					Deine Schritte hallen trotz aller Vorsicht auf dem harten, kalten Marmorboden wieder,
					als du den Blick durch den Raum schweifen lässt. Astrologische Gerätschaften soweit das Auge reicht, in jeder nur vorstellbaren Größe
					stehen vor den großen, runden Fensterscheiben, die hinauf ins ';
					$str_content .= ($hour >= 18 || $hour <= 6)?'dunkle':'helle';
					$str_content .= ' Himmelzelt zeigen.';

					addnav('Wettervorhersage',$str_base_file.'&act=weather');
					addnav('Sternliste betrachten',$str_base_file.'&act=personal_stars');

					//Alles weitere gibts nur nachts
					if ($hour >= 18 || $hour <= 6 || $session['user']['acctid']==2310)
					{
						$str_content .= '`nSterne funkeln am Firmament und warten nur darauf, von dir erforscht zu werden.';

						//Den Himmel kann man nur bei gutem Wetter beobachten
						if(Weather::is_weather(Weather::WEATHER_COLDCLEAR | Weather::WEATHER_CLOUDLESS | Weather::WEATHER_HOT |
						Weather::WEATHER_FROSTY | Weather::WEATHER_WARM))
						{
							$str_content .= '`nAußerdem ist der Himmel so klar, dass du bestimmt ein paar Sternschnuppen sehen kannst. ';
							addnav('Suche Sternschnuppen',$str_base_file.'&act=falling_star');

							//Wenn im Haus ein Teleskop eingelagert ist
							if(house_has_item($arr_ext['houseid'],$arr_ext['id'],'teleskop'))
							{
								$str_content .= ' Zum Glück gibt es hier ein Teleskop.';
								addnav('Studiere die Sterne',$str_base_file.'&act=stars');
								addnav('Die Zukunft deuten',$str_base_file.'&act=future');
								addnav('Die Mondphase betrachten',$str_base_file.'&act=moon');
								addnav('Verschenke einen Stern',$str_base_file.'&act=name_star');
							}
							else
							{
								$str_content .= '`nSchade, dass es hier kein Teleskop gibt, mit dem hätte man hier sicher einen noch viel besseren Ausblick!';
							}
						}
						//Bei seltsamen Wetterleuchten
						elseif(Weather::is_weather(Weather::WEATHER_BOREALIS))
						{
							$str_content .= '`nDas wundervollste sind heute jedoch die vielen Wetterleuchten, die sich unheimlich wabernd über den ganzen Himmel ziehen
							und erst hinter dem Horizont sich im Nirgendwo verlieren.';
							addnav('Betrachte die Wetterleuchten',$str_base_file.'&act=borealis');

							//Wenn im Haus ein Teleskop eingelagert ist
							if(house_has_item($arr_ext['houseid'],$arr_ext['id'],'teleskop'))
							{
								$str_content .= ' Zum Glück gibt es hier ein Teleskop.';
								addnav('Studiere die Sterne',$str_base_file.'&act=stars');
								addnav('Die Zukunft deuten',$str_base_file.'&act=future');
								addnav('Verschenke einen Stern',$str_base_file.'&act=name_star');
							}
							else
							{
								$str_content .= '`nSchade, dass es hier kein Teleskop gibt, mit dem hätte man hier sicher einen noch viel besseren Ausblick!';
							}
						}
						else
						{
							$str_content .= '`nLeider lässt das momentane Wetter nichts weiter zu. Zu schade auch! Bei klarerem Wetter gäbe es hier bestimmt viel zu erkunden.`n
							Aber hey, wenn du das Teleskop ein wenig weiter nach unten drehst, dann kannst du ja vielleicht gucken was in '.getsetting('townname','Atrahor').' so los ist.';
							addnav('Ein wenig spannern',$str_base_file.'&act=voyeur');
						}
					}
					else
					{
						$str_content .= '`nSobald es draußen dunkel wird und das Wetter es zulässt, hast du von hier gewiss einen herrlichen Blick auf das Firmament.';
					}
					break;
				case 'moon':
					{
						addnav('Zurück',$str_base_file);
						$str_content .= house_get_title('Die Mondphase betrachten');

						$int_moon_date = getsetting('moon_date',1);
						$str_moon = '<center><img src="./images/moon/moon_'.$int_moon_date.'.jpg"></center>';
						if($int_moon_date==1)
						{
							$str_header = 'Neumond';
						}
						elseif($int_moon_date==15)
						{
							$str_header = 'Vollmond';
						}
						elseif($int_moon_date<15)
						{
							$str_header = 'Zunehmender Mond';
						}
						if($int_moon_date>15)
						{
							$str_header = 'Abnehmender Mond';
						}
					
						$str_content .= '`c'.print_frame($str_moon,$str_header,0,true).'`c
						Du betrachtest den Mond durch das Teleskop hinweg und freust dich darüber die vielen kleinen und großen Krater darauf so klar und deutlich erkennen zu können.';
						break;
					}
				case 'borealis':
					{
						addnav('Zurück',$str_base_file);
						$str_content .= house_get_title('Betrachte die Wetterleuchten');
						$str_content .= '`tNordlichter, Borealis oder einfach Wetterleuchten. Kein Mensch kann in Worte fassen mit welch majestätischer Anmut diese endlos langen Bänder voll
						schimmernder Farben den Himmel erhellen, so dass selbst die Sterne in Ihrer Schönheit verblassen.`n
						Mit ehrfürchtiger Stille betrachtest du das Schauspiel. Immer wieder gaukeln deine Augen dir vor, dass sich Formen aus den wabernden Bahnen bilden.
						Tiere, geliebte Menschen, belanglose Gegenstände...`n
						`yDer Schleier der Götter ist ein Spiegel deiner Seele`n`t
						so sagt ein altes Sprichwort in '.getsetting('townname','Atrahor').'.
						Und so lässt du deinen Gedanken freien Lauf, betrachtest das Naturschauspiel am nächtlichen Himmel und denkst an ';

						if($session['user']['marriedto'] > 0 && $session['user']['marriedto'] < 4294967295)
						{
							$row=db_fetch_assoc(db_query('SELECT name FROM accounts WHERE acctid='.$session['user']['marriedto']));
							$str_content.=$row['name'];
						}
						else
						{
							$str_content.='Magratea';
						}

						$str_content .= '`t. Was das wohl bedeuten könnte?';
						//wenn hier nichts passiert braucht auch kein WK abgezogen werden $session['user']['turns']--;
						break;
					}
				case 'name_star':
					$str_content .= house_get_title('Verschenke einen Stern');

					switch ($_GET['subact'])
					{
						case '':
							{
							addnav('Zurück',$str_base_file);
							$str_content .= '`tWas für eine zauberhafte Idee. Du könntest den Himmel nach einem bisher unentdeckten Stern absuchen und diesen an deine Liebsten verschenken.`n
							Das kostet zwar die astronomische Summe von `y`b20 Edelsteinen`b`t, aber hey, es ist ein Stern!';

							$str_give_star_lnk = $str_base_file.'&act=name_star&subact=give_star';
							addnav('',$str_give_star_lnk);

							$str_search = '
							<div id="search_div">
							`tWem willst du einen Stern schenken?`n`n
							'.form_header($str_give_star_lnk,'POST',true,'search_form','if(document.getElementById(\'search_sel\').selectedIndex > -1) {this.submit();} else {search();return false;}').'
								'.jslib_search('document.getElementById("search_form").submit();','Stern verschenken!').'
							</form>
							</div>
							';

							$str_content .= $str_search;
							break;
							}
						case 'give_star':
							{
								//Die Acctid muss gültig sein
								if($_POST['acctid']>0)
								{
									addnav('Jemand anders aussuchen',$str_base_file.'&act=name_star');
									addnav('Abbrechen',$str_base_file);
									$db_res = db_query('SELECT name FROM accounts WHERE acctid='.(int)$_POST['acctid']);
									if(db_num_rows($db_res)<1)
									{
										$str_content .= 'Es konnte leider kein Spieler mit dieser ID gefunden werden, versuchs bitte nochmal.';
									}
									else
									{
										$arr_result = db_fetch_assoc($db_res);
										$str_content .= 'Beschwingt machst du dich ans Werk und suchst den Himmel nach dem wunderschönsten Stern ab, den du '.$arr_result['name'].' widmen möchtest.
										Nach einer Weile hast du ihn entdeckt. Du winkst einen Astrologen heran, den du bittest nachzusehen, ob dieser Stern schon kartographiert wurde.';

										//Der User muss genug Gold haben
										if($session['user']['gems']<20)
										{
											$str_content .= 'Der Stern wäre vermutlich noch frei gewesen, aber leider kannst du den Astrologen nicht bezahlen und musst somit erstmal ohne
											dein Geschenk an '.$arr_result['name'].' leben.`n';
											addnav('Schade',$str_base_file);
										}
										else
										{
											$str_content .= 'Du hast Glück, er ist noch frei und somit bittet der Astrologe dich um
											den gewünschten Namen und eine Widmung, die du auf ein Pergament schreiben lassen musst.`n
											<hr>`n';

											$str_give_star_lnk = $str_base_file.'&act=name_star&subact=give_star_send';
											addnav('',$str_give_star_lnk);

											$arr_form = array("Persönlicher Stern für ".$arr_result['name'].",title",
											"preview_starname"=>'Vorschau:,preview,starname',
											"starname"=>"Sternenname,text,255|?Der Name des Sterns den du verschenken möchtest",
											"preview_message"=>'Vorschau:,preview,message',
											"message"=>"Eine Nachricht,text,255|?Eine Nachricht die du dem Besitzer hinterlassen willst.",
											"acctid"=>"AcctID des Users an den Der Stern gehen soll,hidden");

											$arr_values = array("acctid"=>(int)$_POST['acctid']);

											$str_content .= form_header($str_give_star_lnk,'POST',true,'search_form').
											generateform($arr_form,$arr_values,false,'Verschenken').'
											</form>';
										}
									}
								}
								else
								{
									$str_content .= '`tHier lief jetzt irgendwas schief, versuchs bitte nochmal.';
									addnav('Verschenke einen Stern',$str_base_file.'&act=name_star');
									addnav('Abbrechen',$str_base_file);
								}
								break;
							}
						case 'give_star_send':
							{
								$str_content .= '`tSobald du fertig bist und deine Unterschrift darunter gesetzt hast nimmt dir der Astrologe den Zettel und 20 Edelsteine ab, kritzelt ein paar Details in das große Buch der Sterne und wendet sich dem Fenster zu. Während er eine Brieftaube vorbereitet sagt er dir:`n
								"`ySo, der Stern wurde an den von dir eingegebenen Koordinaten unserer Hemisphäre festgehalten und personalisiert. Ich sende jetzt nur noch diese Taube hier an den Empfänger und dann sind wir eigentlich auch schon... fertig!"`n
								`n
								`tDu dankst dem Astrologen und wendest dich ab.';

								$session['user']['gems'] -= 20;

								$str_starname = trim(utf8_html_entity_decode(stripslashes($_POST['starname'])));
								$arr_item['tpl_name'] = '`9Stern - `0'.$str_starname;
							    $arr_item['tpl_description'] = trim(utf8_html_entity_decode(stripslashes($_POST['message'])));
								$arr_item['tpl_gold'] = 1;
								$arr_item['tpl_gems'] = 0;

								item_add((int)$_POST['acctid'] , 'gift_star' , $arr_item );

								systemmail((int)$_POST['acctid'],'`2Ein Geschenk!',$session['user']['name'].'`2 hat dir einen `9Stern`2 namens '.$str_starname.'`2 geschenkt. Wann immer du in den klaren Nachthimmel siehst, wirst du nun an '.$session['user']['name'] .' `2erinnert werden.');
								debuglog('Verschenkte einen Stern '.$str_starname,(int)$_POST['acctid']);

								addnav('Das ging ja schnell',$str_base_file);
								break;
							}
					}
					break;
				case 'personal_stars':
					{
						addnav('Buch schließen',$str_base_file);

						$str_content .= house_get_title('Die Liste der persönlichen Sterne');

						$str_content .= '`tIn einer Ecke des Oberservatoriums steht ein Podest mit einem dicken Wälzer darauf. Der Einband enthält den Schriftzug`n
						`y`bAstronomicon`b - Die stets aktuelle Liste der registrierten Sterne`t`n
						Wow, wer hier wohl alles einen Stern erworben hat...`n
						Neugierig schlägst du die Seiten auf. Dort stehen, chronologisch geordnet, alle Sterne der Bewohner '.getsetting('townname','Atrahor').'s`n`n<hr>`n`n';

						$arr_page_res = page_nav($str_base_file.'&act=personal_stars',item_count('i.tpl_id="gift_star"'));


						$str_sql = 	'SELECT i.name, accounts.name as owner FROM '.ITEMS_TABLE.' i
									LEFT JOIN accounts ON ( i.owner=accounts.acctid )
									WHERE i.tpl_id="gift_star"
									ORDER BY i.id ASC
									LIMIT '.$arr_page_res['limit'];
						$db_res = db_query($str_sql);

						if($db_res === false || db_num_rows($db_res)<1)
						{
							$str_content .= 'Noch wurden keine Sterne registriert';
						}
						else
						{

							$str_content .= '<center><table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999">
								<tr class="trhead"><td><b>Besitzer</b></td><td>Name</td></tr>';

							while ($arr_star = db_fetch_assoc($db_res))
							{
								$str_tr_class = ($str_tr_class == "trdark")?"trlight":"trdark";
								$str_content .= "<tr class='".$str_tr_class."'>";
								$str_content .= "<td>".$arr_star['owner']."</td>";
								$str_content .= "<td>".$arr_star['name']."</td>";
								$str_content .= "</tr>";
							}
							$str_content .= '</table></center>';
						}

						break;
					}
				case 'weather':
					//Aktuelles Wetter laden
					$arr_weather = Weather::get_weather();
					$list = array();

					//Erstelle eine Liste aller Wettertypen die NICHT auf das aktuelle Wetter folgen
					foreach(Weather::$weather as $id => $w) {
						if(!in_array($arr_weather['id'],$w['follows_after'])) {
							$list[] = $id;
						}
					}

					$weather_id = $list[ e_rand(0,sizeof($list)-1) ];

					$str_content .= house_get_title('Der Wetterbericht');

					$str_content .= '`tDu begibst dich an das große Fenster des Observatoriums und schaust interessiert hinaus.
					Eine der besten Vorraussetzungen, um eine Wettervorhersage zu treffen, wie du findest.`n
					Du schnupperst in die Luft und hälst deine Hand aus dem Fenster, um die Temperatur zu prüfen.
					Ja, das momentane Wetter zu erkennen ist keine Kunst. Du bist folglich der Meinung, dass man das Wetter als `0';
					$str_content .= $arr_weather['name'];
					$str_content .= '`t bezeichnen könnte.`n`n
					Weit schwieriger wird nun eine Voraussage zu treffen. Du überlegst kurz und gestehst dir ein, dass eine Voraussage
					auch nichts weiter als eine Sage wäre und musst grinsen. Aber das Ausschlussverfahren hilft hier bestimmt auch weiter.
					Du bist dir ziemlich sicher, dass das Wetter morgen keinesfalls als `0';
					$str_content .= Weather::$weather[$weather_id]['name'];
					$str_content .= '`t bezeichnet werden kann, soviel lehrt dich deine Erfahrung.`n
					Nunja, immerhin etwas.';

					addnav('Zurück',$str_base_file);
					break;
				case 'future':
					$int_count_stars = count($arr_stars);
					$int_house = e_rand(1,12);

					$arr_astralogy_houses = array('Der Aszendent legt fest, wo und wie man im Leben steht, wie die körperliche und seelische Konstitution ist, welches Temperament man hat und welche Schicksalstendenzen aus diesen Anlagen notwendigerweise erwachsen.',
					'Das 2. Haus betrifft den erworbenen, beweglichen Besitz, vor allem Einkommen und Finanzen - aber auch erworbene Fähigkeiten und erlerntes Wissen.',
					'Das 3. Haus betrifft das Verhältnis zur engeren Umwelt und alles, was der Verbindung zur dieser Umwelt dient, sowie das Alltagsdenken.',
					'Das 3. Haus  bedingen Heim und Haus. Dazu gehört das Elternhaus und die Heimat. Besonders aber das eigene Zuhause und darüber hinaus der Besitz an Grund und Boden.',
					'Im 5. Haus findet sich Liebe als Lebensgefühl und Erlebnis, sowie das Verhältnis zu Spiel und Spekulation.',
					'Das 6. Haus beschreibt die Arbeit als tägliche Pflicht, Mitarbeiter, Hilfskräfte und Hilfsmittel sowohl eventuelle körperliche Anfälligkeiten als auch Krankheiten.',
					'Das 7. Haus bedingt den Lebensgefährten, aber auch den Partner schlechthin.',
					'Das 8. Haus berichtet von außen zufallenden Gewinne (oder mögliche Verluste) wie beispielweise eine Erbschaft oder finanzielle Erträge.',
					'Das 9. Haus bestimmt alles, was aus der eigenen kleinen Welt herausführt. Die Reisen in ferne Länder oder gar Auswanderung. Aber auch die der Weiterentwicklung der Menschheit dienenden Pläne und Einrichtungen. Ebenso der Schritt aus dem Alltagsdenken ins Weltanschauliche: Philosophie und Religion.',
					'Das 10. Haus ist die Stellung in der Öffentlichkeit. Aufstieg, Ansehen und Ehren.',
					'Das 11. Haus zeigt Freunde, von denen man Unterstützung erwarten kann.',
					'Das 12. Haus weist Beschränkungen und Hemmungen, deren Ursache oft unklar bleibt, auch heimliche Gegner, die aus dem Hinterhalt wirken.');


					$str_content .= get_title('Die Zukunft lesen');
					$str_content .= '`t"`yUm die Zukunft zu erkennen, musst du in die Vergangenheit sehen!`t" Ein Weiser Spruch. Fürwahr, die Zukunft aus den Sternen zu lesen
					ist eine schwierige Aufgabe, aber keine minder schöne! Du begibst dich an das Teleskop und richtest es auf den klaren Nachthimmel. Ein erster rascher Blick offenbart dir `y'.
					$arr_stars[e_rand(0,$int_count_stars-1)] .'`t. Du stellst das Teleskop daran ein, deinen Blick scharf und orientierst dich von dort aus weiter.`n`n';

					if(!isset($arr_content['fortune'][$session['user']['acctid']]) && !isset($session['daily']['fortune']))
					{

						$str_content .= 'Ah ja, so muss es sein! Alle Sterne entlang der Ekliptik deuten genau auf das Eine hin!&nbsp;`y
						'.$arr_stars[e_rand(0,$int_count_stars-1)].'`t ist stark im `b'.$int_house.'ten`b Haus.`n`n`y'.
						$arr_astralogy_houses[$int_house-1]
						.'`t `n`nDas bedeutet ';

						$fortune = e_rand(1,15);
						switch ($fortune)
						{
							case 1:
								$str_content .= '... `yDu weichst erschrocken zurück, heute sieht es gar nicht gut aus für dich.';
								$session['user']['hitpoints']=1;
								$session['user']['gold']-=100;
								$session['user']['charm']-=1;
								$session['user']['gems']-=1;
								if ($session['user']['gold'] < 0)
								{
									$session['user']['gold'] = 0;
								}
								if ($session['user']['gems'] < 0)
								{
									$session['user']['gems'] = 0;
								}
								break;
							case 2:
								$str_content .= '`yheute solltest du es ruhiger angehen lassen.';
								$session['user']['turns']-=2;
								if ($session['user']['turns'] < 0)
								{
									$session['user']['turns'] = 0;
								}
								break;
							case 3:
							case 11:
								$str_content .= '`ydie Liebe ist dein Begleiter';
								$session['user']['charm']++;
								break;
							case 4:
								$str_content .= '`ydu hast etwas Wichtiges in deinem Leben verloren.';
								$session['user']['goldinbank']-=500;
								if ($session['user']['goldinbank'] < 0)
								{
									$session['user']['goldinbank'] = 0;
								}
								break;
							case 5:
								$sql='SELECT houseid, gems FROM houses WHERE owner='.$session['user']['acctid'];
								$result=db_query($sql);
								$row=db_fetch_assoc($result);
								if ($row['gems']>0)
								{
									$row['gems']--;
									$sql = 'UPDATE houses SET gems='.$row['gems'].' WHERE houseid='.$row['houseid'];
									db_query($sql);

									insertcommentary(1,'/msg Eine Elster landet am offenen Fenster, fliegt zur Schatztruhe und schnappt sich einen Edelstein.','house-'.$row['houseid']);

									$str_content .= '`ywährend du dich hier aufhältst, droht deinem Hause ein Verlust.';
								}
								else
								{
									$str_content .= '`ydu solltest um dein Leben fürchten, wenn du heute in den Feldern schläfst.';
								}
								break;
							case 7:
								$str_content .= '`ydu wirst im Laufe des Tages eine freudige Überraschung erleben.';
								$session['user']['goldinbank']+=1000;
								break;
							case 8:
							case 20:
								$str_content .= '`ydein Tag wird lang und produktiv sein.';
								$session['user']['turns']+=2;
								break;
							case 9:
								$sql='SELECT houseid, gems FROM houses WHERE owner='.$session['user']['acctid'];
								$result=db_query($sql);
								$row=db_fetch_assoc($result);
								if ($row['houseid'])
								{
									$row['gems']++;
									$sql = 'UPDATE houses SET gems='.$row['gems'].' WHERE houseid='.$row['houseid'];
									db_query($sql);

									insertcommentary(1,'/msg Ein Edelstein fällt vom Himmel und kullert direkt vor die Schatztruhe.','house-'.$row['houseid']);

									$str_content .= '`ywährend du dich hier aufhältst, mehrt sich dein Besitz.';
								}
								else
								{
									$str_content .= '`yTand wird dich erfreuen';
									item_add($session['user']['acctid'],'glasfigur');
								}
								break;
							case 10:
								$str_content .= '`y'.$arr_stars[e_rand(0,$int_count_stars-1)].' starkes Leuchten verleiht dir Kraft.';
								$session['user']['hitpoints']+=50;
								break;
							case 12:
								$str_content .= '`y'.$arr_stars[e_rand(0,$int_count_stars-1)].' hat stark negativen Einfluss auf dein Wohlbefinden.';
								$session['user']['hitpoints']*=0.5;
								if ($session['user']['hitpoints'] < 0)
								{
									$session['user']['hitpoints'] = 1;
								}
								break;
							case 13:
								$str_content .= '`y'.$arr_stars[e_rand(0,$int_count_stars-1)].' Leuchten wirkt wie eine starke Droge auf dich.';
								$session['user']['drunkenness']=80;
								break;
							case 14:
								$str_content .= '`y'.$arr_stars[e_rand(0,$int_count_stars-1)].' steht im Bande der Götter und bescheint deine Seele wohlwollend.';
								$session['user']['hitpoints']+=50;
								break;
							case 15:
								$str_content .= '`y'.$arr_stars[e_rand(0,$int_count_stars-1)].' weist auf einen perfekten Tag hin. Nutze ihn!';
								$session['user']['hitpoints']+=10;
								$session['user']['gold']+=100;
								$session['user']['charm']+=1;
								$session['user']['gems']+=1;
								break;
							default:
								$str_content .= 'die Linse ist verschmutzt.';
						}
					}
					else
					{
						$str_content .= 'Leider tippt dich einer der anderen Hobbyastronomen an und weist dich darauf hin, dass niemand seine Zukunft zu genau kennen sollte.
						Er bittet dich, morgen wieder zu versuchen in die Zukunft zu blicken.';
					}
					$arr_content['fortune'][$session['user']['acctid']]=true;
					$session['daily']['fortune'] = true;

					addnav('Zurück',$str_base_file);
					break;
				case 'stars':
					switch ($_GET['mode'])
					{
						//Einfaches Spiel, lateinische namen raten
						case 'easy':
							{
								//Wenn eine Antwort gegeben wurde
								if(isset($_GET['answer']))
								{
									$int_answer = (int)$_GET['answer'];
									$str_solution = urldecode($_GET['solution']);

									$arr_constellation_latin = array_values(array_flip($arr_constellation));

									$arr_constellation_latin = array_values(array_flip($arr_constellation));
									$arr_constellation_german = array_values($arr_constellation);
									$arr_constellation_reverse = array_flip($arr_constellation);

									$str_content .= get_title('Sternbilder raten');

									//Ist die Antwort korrekt
									if($arr_constellation_german[$int_answer] == $str_solution)
									{
										$str_content .= '`t Wunderbar, herzlichen Glückwunsch! `y'.$arr_constellation_latin[$int_answer].'`t ist die richtige Antwort.';
										
										if(!isset($session['daily']['stars_won_easy']))
										{
											$str_content .= '`n`nDu gewinnst etwas Meteorstaub';
											item_add($session['user']['acctid'],'metestaub');
										}
										else 
										{
											$str_content .= '`n`nDu gewinnst etwas Gold';
											$session['user']['gold']+=100;
										}
										
										$session['daily']['stars_won_easy'] = true;
										$arr_content['game'][$session['user']['acctid']]=true;
									}
									else
									{
										$str_content .= '`tLeider, leider! Aber hier lagst du daneben. Die richtige Antwort wäre `y';
										$str_content .= $arr_constellation_reverse[$str_solution];
										$str_content .= '`t gewesen. Da musst du wohl noch ein wenig studieren.';
									}
									addnav('Zurück',$str_base_file.'&act=stars');
								}
								else
								{
									$session['user']['turns']--;
									$int_count_constellations = count($arr_constellation)-1;
									$int_rand = e_rand(0,$int_count_constellations);

									$arr_constellation_latin = array_values(array_flip($arr_constellation));
									$arr_constellation_german = array_values($arr_constellation);

									$str_content .= get_title('Sternbilder raten');
									$str_content .= '`tBeim leichten Spiel schaust du durch das Teleskop und erkennst das Sternbild an seinem Namen.
									Aber wie lautete noch gleich der Name des Sternbildes in der Sprache der Gelehrten? (Hinweis: Latein)`n`n';
									$str_content .= 'Das Sternbild, welches du durch das Teleskop erkennen kannst, nennt sich im Volksmund: `y';

									$str_content .= $arr_constellation_german[$int_rand];

									$str_content .= '`t. Aber wie nennt man es denn nun in der Sprache der Gelehrten?`n`n';

									do
									{
										$int_rand_2 = e_rand(0,$int_count_constellations);
									}while ($int_rand_2 == $int_rand);

									do
									{
										$int_rand_3 = e_rand(0,$int_count_constellations);
									}while ($int_rand_3 == $int_rand || $int_rand_3 == $int_rand_2);

									do
									{
										$int_rand_4 = e_rand(0,$int_count_constellations);
									} while($int_rand_4 == $int_rand || $int_rand_4 == $int_rand_2 || $int_rand_4 == $int_rand_3 );

									$arr_rand = array($int_rand,$int_rand_2,$int_rand_3,$int_rand_4);

									shuffle($arr_rand);

									$str_content .= create_lnk('- Ist es '.$arr_constellation_latin[$arr_rand[0]].'?',$str_base_file.'&mode=easy&act=stars&answer='.$arr_rand[0].'&solution='.urlencode($arr_constellation_german[$int_rand]),true,true,'',false,$arr_constellation_latin[$arr_rand[0]]).'`n';
									$str_content .= create_lnk('- Ist es '.$arr_constellation_latin[$arr_rand[1]].'?',$str_base_file.'&mode=easy&act=stars&answer='.$arr_rand[1].'&solution='.urlencode($arr_constellation_german[$int_rand]),true,true,'',false,$arr_constellation_latin[$arr_rand[1]]).'`n';
									$str_content .= create_lnk('- Ist es '.$arr_constellation_latin[$arr_rand[2]].'?',$str_base_file.'&mode=easy&act=stars&answer='.$arr_rand[2].'&solution='.urlencode($arr_constellation_german[$int_rand]),true,true,'',false,$arr_constellation_latin[$arr_rand[2]]).'`n';
									$str_content .= create_lnk('- Ist es '.$arr_constellation_latin[$arr_rand[3]].'?',$str_base_file.'&mode=easy&act=stars&answer='.$arr_rand[3].'&solution='.urlencode($arr_constellation_german[$int_rand]),true,true,'',false,$arr_constellation_latin[$arr_rand[3]]).'`n';
								}
								break;
							}
							//Einfaches Spiel, Bilder erkennen
						case 'difficult':
							{
								//Wenn eine Antwort gegeben wurde
								if(isset($_GET['answer']))
								{
									$str_answer = urldecode($_GET['answer']);
									$int_solution = (int)$_GET['solution'];

									$str_content .= get_title('Sternbilder raten');

									//Ist die Antwort korrekt
									if($arr_constellation_picture[$int_solution] == $str_answer)
									{
										$str_content .= '`t Wunderbar, herzlichen Glückwunsch! `y'.$arr_constellation_picture[$int_solution].'`t ist die richtige Antwort.';
										
										
										if(!isset($session['daily']['stars_won_difficult']))
										{
											$str_content .= '`n`nDu gewinnst etwas Meteorstaub und einen Edelstein';
											item_add($session['user']['acctid'],'metestaub');
											$session['user']['gems']+=1;
										}
										else 
										{
											$str_content .= '`n`nDu gewinnst etwas Gold';
											$session['user']['gold']+=100;
										}
										
										$arr_content['game'][$session['user']['acctid']]=true;
										$session['daily']['stars_won_difficult'] = true;
									}
									else
									{
										$str_content .= '`tLeider, leider! Aber hier lagst du daneben.`nDa musst du wohl noch ein wenig studieren.';
									}
									addnav('Zurück',$str_base_file.'&act=stars');
								}
								else
								{
									$session['user']['turns']--;
									$int_count_constellations = count($arr_constellation_picture);
									$int_rand = e_rand(1,$int_count_constellations);

									$str_content .= get_title('Sternbilder raten');
									$str_content .= '`tBeim schwierigen Spiel schaust du durch das Teleskop und erkennst ein Sternbild am Firmament.
									Aber wie lautete noch gleich der Name des Sternbildes?`n`n';
									$str_content .= 'Das Sternbild, welches du durch das Teleskop erkennen kannst, sieht wie folgt aus:<br clear="all" >';

									$str_content .= '<div style="text-align:center;"><img src="./images/sternbilder/'.$int_rand.'.jpg" ></div><br clear="all" >';

									$str_content .= 'Aber wie nennt es denn nun?`n`n';

									do
									{
										$int_rand_2 = e_rand(1,$int_count_constellations);
									}while ($int_rand_2 == $int_rand);

									do
									{
										$int_rand_3 = e_rand(1,$int_count_constellations);
									}while ($int_rand_3 == $int_rand || $int_rand_3 == $int_rand_2);

									do
									{
										$int_rand_4 = e_rand(1,$int_count_constellations);
									} while($int_rand_4 == $int_rand || $int_rand_4 == $int_rand_2 || $int_rand_4 == $int_rand_3 );

									$arr_rand = array($int_rand,$int_rand_2,$int_rand_3,$int_rand_4);

									shuffle($arr_rand);

									$str_content .= create_lnk('- Ist es '.$arr_constellation_picture[$arr_rand[0]].'?',$str_base_file.'&mode=difficult&act=stars&answer='.urldecode($arr_constellation_picture[$arr_rand[0]]).'&solution='.$int_rand,true,true,'',false,$arr_constellation_picture[$arr_rand[0]]).'`n';
									$str_content .= create_lnk('- Ist es '.$arr_constellation_picture[$arr_rand[1]].'?',$str_base_file.'&mode=difficult&act=stars&answer='.urldecode($arr_constellation_picture[$arr_rand[1]]).'&solution='.$int_rand,true,true,'',false,$arr_constellation_picture[$arr_rand[1]]).'`n';
									$str_content .= create_lnk('- Ist es '.$arr_constellation_picture[$arr_rand[2]].'?',$str_base_file.'&mode=difficult&act=stars&answer='.urldecode($arr_constellation_picture[$arr_rand[2]]).'&solution='.$int_rand,true,true,'',false,$arr_constellation_picture[$arr_rand[2]]).'`n';
									$str_content .= create_lnk('- Ist es '.$arr_constellation_picture[$arr_rand[3]].'?',$str_base_file.'&mode=difficult&act=stars&answer='.urldecode($arr_constellation_picture[$arr_rand[3]]).'&solution='.$int_rand,true,true,'',false,$arr_constellation_picture[$arr_rand[3]]).'`n';
								}
								break;
							}

						default:
							{
								$str_content .= get_title('Studiere den Sternenhimmel');

								if($session['user']['turns']<1)
								{
									$str_content .= '`tAn Weisheit hat man vielleicht nie genug, aber an Aufregung an einem Tage bestimmt.
									Du bist sehr erschöpft und kannst dich einfach nicht mehr richtig konzentrieren.
									Vielleicht solltest du wiederkommen wenn du etwas erholter bist.';
								}
								elseif(isset($arr_content['game'][$session['user']['acctid']]))
								{
									$str_content .= '`tAn Weisheit hat man nie genug. Deswegen möchtest du deine Kenntnisse erneut testen.
									Allerdings bittet dich einer der Astronomen, auch den anderen Besuchern das Teleskop eine Zeit lang zu überlassen,
									so dass du wohl oder übel morgen wiederkommen musst.';
								}
								else
								{
									$str_content .= '`tAn Weisheit hat man nie genug. Deswegen testest du deine Kenntnisse anhand eines kleinen Spiels. Du stößt das Teleskop
									sanft an und wartest bis es stehen bleibt. Dann siehst du hindurch und erkennst einzelne Sterne, die sich zu einem Sternbild formieren.
									Jetzt gibt es eine einfache und eine schwierige Variante. Welche möchtest du wählen?';

									addnav('Die einfache Variante reicht',$str_base_file.'&act=stars&mode=easy');
									addnav('Ich will den vollen Spaß',$str_base_file.'&act=stars&mode=difficult');
								}
								addnav('Zurück',$str_base_file);
								break;
							}
					}

					break;
				case 'falling_star':
					$str_content .= house_get_title('Sternschnuppensuche');
					$str_content .= '`tDu trittst an ein Fenster und betrachtest verträumt den Nachthimmel. Herrlich, wie klar es wieder ist.
					Wahrscheinlich wirst du ein paar Sternschnuppen sehen. Eine tolle Vorstellung. So verlockend, dass du dir einen Schemel
					nimmst, an das Fenster rückst , deine Ellenbogen auf den Fenstersims stemmst und deinen Kopf darauf bettest.
					Lange Zeit beobachtest du den Himmel und die funkelnden Sterne daran.';
					$int_rand = e_rand(1,10);
					if($int_rand==10 && !isset($arr_content['falling_star'][$session['user']['acctid']]))
					{
						$str_content .= '`nLange Zeit passiert nichts, doch dann, mit einem Male, erblickst du einen goldenen Schweif, der einmal
						quer über den Himmel zieht. Du schließt die Augen und wünschst dir etwas. Ein uralter Brauch, doch du fühlst dich danach irgendwie gut.';

						$arr_buff = array('name'=>'`FGute Laune','rounds'=>30,'wearoff'=>'`FDie schöne Erinnerung an die Sternschnuppe verblasst!`0','atkmod'=>1.1,'roundmsg'=>'`FDu fühlst dich gut und schlägst härter zu!`0','activate'=>'offense');
						buff_add($arr_buff);

						$session['user']['turns'] = max(0,$session['user']['turns']-1);

						$arr_content['falling_star'][$session['user']['acctid']]=true;
					}
					elseif (isset($arr_content['falling_star'][$session['user']['acctid']]))
					{
						$str_content .= '`nDu entspannst noch eine Weile am Fenster, doch kannst du beim besten willen nicht noch eine Sternschnuppe erblicken.';
						$session['user']['turns'] = max(0,$session['user']['turns']-1);
					}
					else
					{
						$str_content .= '`nLeider hast du kein Glück. Etwas enttäuscht wendest du dich ab.';
						$session['user']['turns'] = max(0,$session['user']['turns']-1);
					}

					addnav('Zurück',$str_base_file);
					break;
				case 'voyeur':
					$str_content .= house_get_title('Voyeurismus');
					$str_content .= '`tHm, wenn der Himmel schon keinen Blick auf die Sterne gewährt, dann könntest du ja mal einen Blick in die anderen Fenster riskieren.
					Also richtest du dein Teleskop nach unten und schaust dich um. Interessant, interessant: `y';

					$db_result = db_query('SELECT name FROM accounts ORDER BY RAND()');
					$arr_result = db_fetch_assoc($db_result);

					$arr_verb = array('steht','liegt','redet','schwätzt','plaudert','schmust');
					$arr_ort = array('am Fenster', 'im Türrahmen', 'im Hof', 'in den Ställen','auf dem Klo','in der Schenke');
					$arr_rest = array('mit irgendwem','und sieht glücklich aus','und sieht traurig aus', 'und scheint wütend', 'und scheint erregt', 'und trinkt dabei etwas');

					$str_content .= $arr_result['name'].' '.$arr_verb[e_rand(0,count($arr_verb)-1)].' '.$arr_ort[e_rand(0,count($arr_ort)-1)].' '.$arr_rest[e_rand(0,count($arr_rest)-1)].'.';
					addnav('Zurück',$str_base_file);
					break;
				default:

					break;
			}

			if($str_content_md5  != md5(utf8_serialize($arr_content)))
			{
				db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);
			}

			output ($str_content);

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
