<?php
// Common-Datei für Gemächer: Enthält gemeinsam genutzte Codeteile (Einladungen etc.)
// Muss in Main-Funktion eines Extensions-Moduls inkludiert werden, erwartet dessen Vars!

	$bool_howner = $bool_rowner = $bool_not_invited = false;
	$str_base_file = '';
	$arr_content = array();

	function _rooms_common_set_env ($arr_ext,$arr_house) {
		global $g_arr_house_extensions,$bool_howner,$bool_rowner,$bool_not_invited,$session,$str_base_file,$accesskeys,$arr_content;

		// Inhaltsarray erstellen
		$arr_content = array();
		$arr_content = adv_unserialize($arr_ext['content']);

		$str_base_file = 'house_extensions.php?_ext_id='.$arr_ext['id'];

		// Hausbesitzer
		$bool_howner = ($arr_house['owner'] == $session['user']['acctid'] ? true : false);
		// Gemachbesitzer
		$bool_rowner = ($arr_ext['owner'] == $session['user']['acctid'] ? true : false);
		// Hausbewohner
		$bool_not_invited = ($bool_howner || $bool_rowner || db_num_rows(db_query('SELECT id FROM keylist WHERE type='.HOUSES_KEY_DEFAULT.' AND value1='.$arr_house['houseid'].' AND owner='.$session['user']['acctid'])) ? true : false);

		// HausID speichern
		if($bool_not_invited && (empty($session['housekey']) || $session['housekey'] != $arr_house['houseid'])) {
			$session['housekey'] = $arr_house['houseid'];
		}

		// Folgende Navihotkeys garantieren
		$accesskeys['w']=1;$accesskeys['d']=1;$accesskeys['m']=1;$accesskeys['l']=1;$accesskeys['h']=1;
	}

	function _rooms_common_switch ($str_case,$arr_ext,$arr_house) {

		global $g_arr_house_extensions,$bool_howner,$bool_rowner,$bool_not_invited,$session,$str_base_file,$accesskeys,$arr_content,$show_invent;

		switch($str_case) {

			// Im Gemach
			case 'in':
				
				// Variable wird sowohl in Hausinnenraum als auch beim Vergeben der Schlüssel (dort auch nochmal ein Check) benötigt.
				if(isset($g_arr_house_extensions[$arr_ext['type']]['max_invi'])) {
					$int_keys_max = $g_arr_house_extensions[$arr_ext['type']]['max_invi'];
				}
				else {
					/**
					 * @todo was für die settings?
					 */
					$int_keys_max = 100;
				}
				
				switch($_GET['act']) {
					
					// Gemach einstellen
					case 'config':

						// Grenzen für Kommentarlänge
						$max_total = getsetting('chat_post_len_max',8000); // Erstmal nur hart kodiert
						$min_total = getsetting('chat_post_len_long','0'); // Öffentlichen Plätze

						// max. Länge für Beschr.
						$int_max_length = getsetting('housedesclen',500);

						if(isset($_POST) && count($_POST)>0) {
							if($g_arr_house_extensions[$arr_ext['type']]['hide_desc'] !== true)
							{
								$str_name = mb_substr(stripslashes($_POST['name']),0,100);

								$str_desc = closetags(stripslashes($_POST['desc']),'`c`i`b');
								$str_desc = strip_tags($str_desc,'<img>');
								$str_desc = mb_substr($str_desc,0,$int_max_length);
							}

							$int_access = (int)$_POST['access'];
							$int_c_max_len = (int)$_POST['c_max_len'];

							if ($int_c_max_len>$max_total) $int_c_max_len=$max_total;
							if ($int_c_max_len<=0) $int_c_max_len=$min_total;
							$int_c_max_len=max($int_c_max_len,$min_total);

							// Wenn Zutrittsoptionen geändert: Einladungen löschen
							if($int_access != $arr_ext['val']) {
								house_keys_del(' type='.HOUSES_KEY_PRIVATE.' AND value2='.$arr_ext['id'].' ',0);
							}

							if($g_arr_house_extensions[$arr_ext['type']]['hide_desc'] !== true)
							{
								$arr_content['desc'] = $str_desc;
								$arr_ext['name'] = $str_name;

								$str_sql_name = 'name="'.db_real_escape_string($str_name).'",';
							}

							$arr_content['c_max_len'] = $int_c_max_len;
							$arr_ext['val'] = $int_access;

							db_query('UPDATE house_extensions SET '.$str_sql_name.' val='.$int_access.',content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);
							
							// Cache zurücksetzen
							if($g_arr_house_extensions[$arr_ext['type']]['hide_desc'] !== true && !Cache::delete(Cache::CACHE_TYPE_HDD, 'houserooms'.$arr_house['houseid'] ))
							{
								admin_output('Cachereset (houserooms'.$mixed_house['houseid'].') funzt nicht',true);
							}
							
							output('`@Änderungen übernommen!`n`n');

						}

						$arr_description = array(
											'name_pr'=>'Vorschau:,preview,name'
											,'name'=>'Name des Gemachs,text,100'
											, 'desc_pr'=>'Vorschau:,preview,desc'
											,'desc'=>'Beschreibung,textarea,30,15,'.$int_max_length);
						$arr_form = array(
											'access'=>'Zutritt nur mit Einladung? (ACHTUNG: Bei Änderung werden alle Einladungen zurückgezogen!),checkbox,1'
											,'c_max_len_expl'=>',viewonly'
											,'c_max_len'=>'Maximale Zeichenanzahl,int'
										);
						$arr_data = array('name'=>$arr_ext['name'],'desc'=>$arr_content['desc'],'access'=>$arr_ext['val'],
											'c_max_len_expl'=>'Als Herr(in) über dieses Gemach ist es dir hier möglich zu bestimmen wieviel Platz (Zeichen) du dir und deinen Gästen für das Rollenspiel zugestehst.`nDer Wert, den du eingibst, darf den vorgegebenen Maximalwert (derzeit '.$max_total.') nicht überschreiten!`nEs sind nur positive, ganze Zahlen zulässig. Bei einer ungültigen Eingabe, oder wenn du 0 eingibst, wird stattdessen der Standartwert für private Räume (derzeit '.$min_total.') verwendet. Dieser Wert darf auch nicht unterschritten werden!'
											,'c_max_len'=>$arr_content['c_max_len']);

						//Wenn das Ändern der Beschreibung zulässig ist zeige die Felder im Formular an
						if($g_arr_house_extensions[$arr_ext['type']]['hide_desc'] !== true)
						{
							$arr_form = array_merge($arr_form,$arr_description);
						}

						$str_lnk = $str_base_file.'&act=config';
						addnav('',$str_lnk);

						output(house_get_title('Gemach konfigurieren'));

						rawoutput('<form method="POST" action="'.$str_lnk.'">
								'.generateform($arr_form,$arr_data,false,'Übernehmen!').
								'</form>',true);

						addnav('Zurück zum Gemach',$str_base_file);

					break;

					//eingelagerte Items sortieren
					case 'itemsort':
					{
						$str_output.=get_title('Möbelrücken').'Hier hast du die Möglichkeit, deine eingelagerten Möbel neu anzuordnen.`n';
						$str_output.=item_set_sort_order('deposit1='.(int)$session['housekey'].' AND deposit2='.$arr_ext['id'].' AND owner<1234567');
						output($str_output);
						addnav('Zurück zum Gemach',$str_base_file);
						break;
					} //END eingelagerte Items sortieren

					// Einladung vergeben
					case 'invi_set':
						
						// int_keys_max wird zu Beginn dieses Switch gesetzt
						$max_number = $int_keys_max;
						
						$ziel = 0;
						if(!isset($_POST['acctid']))
						{
							$str_output .= '`tLeider konntest du niemanden finden. Versuch es doch einfach noch einmal.';
						}
						else
						{
							$ziel = (int)$_POST['acctid'];
							
							if($ziel == $session['user']['acctid'])
							{
								$str_output .= '`tDein anderes Ich hat gesagt, es mag dich nicht in seinem Gemach haben.';
							}
							else 
							{
							
								// Auf max. Anzahl prüfen
								$arr_tmp = db_fetch_assoc(db_query('SELECT COUNT(*) AS c FROM keylist WHERE type='.HOUSES_KEY_PRIVATE.' AND value2='.$arr_ext['id']));
								$int_count = $arr_tmp['c'];
								if($int_count > $max_number) {
									$str_output .= '`tEs hat bereits die maximale Personenzahl Zugang zu deinem Gemach!';
								}
								else {
									
									// Überprüfen, ob Spieler nicht bereits autorisiert
									// Dies ist der Fall, wenn a) das Gemach für alle offen steht und der Spieler bereits Zutritt zum Haus hat
									// oder b) bereits über eine Einladung verfügt
									if($arr_ext['val'] == 0 &&
										($arr_house['owner'] == $ziel || db_num_rows(db_query('SELECT id FROM keylist WHERE owner='.$ziel.' AND value1='.$arr_house['houseid'].' AND type='.HOUSES_KEY_DEFAULT)))
										) {
										$str_output .= '`tDiese Person hat bereits Zugang zum Haus - da dein Gemach allen Hausbewohnern offen steht, ist eine weitere Einladung nicht nötig!';
									}
									elseif($arr_ext['val'] == 1 && db_num_rows(db_query('SELECT id FROM keylist WHERE owner='.$ziel.' AND value2='.$arr_ext['id'].' AND type='.HOUSES_KEY_PRIVATE))) {
										$str_output .= '`tDiese Person hat bereits Zugang zu deinem Gemach!';
									}
									else {	// autorisieren
	
										house_keys_add(array('value1'=>$arr_house['houseid'],'value2'=>$arr_ext['id'],'value3'=>$arr_ext['owner'],'owner'=>$ziel,'type'=>HOUSES_KEY_PRIVATE));
	
										$sql = 'SELECT name FROM accounts WHERE acctid='.$ziel;
										$res = db_query($sql);
										$name = db_fetch_assoc($res);
	
										$str_output .= '`tDu übergibst '.$name['name'].'`t einen Schlüssel zu deinem Privatgemach!';
										
										$arr_ext['name'] = (!empty($arr_ext['name']) ? $arr_ext['name'] : $g_arr_house_extensions[$arr_ext['type']]['name']);
										
										systemmail($ziel,'`tSchlüssel zu Privatgemach',$session['user']['name'].'`t hat dir freundlicherweise einen Schlüssel zu '.($session['user']['sex'] ? 'ihrem' : 'seinem').' Privatgemach \"'.$arr_ext['name'] .'\" in Haus Nr. '.$session['housekey'].' überreicht.');
	
									}
	
								}
								
							}
						}

						addnav('Zurück',$str_base_file);
						output($str_output,true);

					break;

					case 'invi_del':

						if(isset($_GET['ziel'])) {
							$ziel = (int)$_GET['ziel'];

							if($ziel) {

								$sql = 'SELECT k.owner, a.name FROM keylist k, accounts a WHERE a.acctid=k.owner AND k.id='.$ziel;
								$res = db_query($sql);
								$p = db_fetch_assoc($res);

								house_keys_del(' id = '.$ziel);
								
								$arr_ext['name'] = (!empty($arr_ext['name']) ? $arr_ext['name'] : $g_arr_house_extensions[$arr_ext['type']]['name']);
								
								systemmail($p['owner'],"`tZugang zu Privatgemächern entzogen!`0","`t{$session['user']['name']}`t hat dir den Schlüssel zu ".(($session['user']['sex'])?"ihrem":"seinem")." Privatgemach \"".$arr_ext['name'] ."\" in ".$arr_house['housename']."`t wieder abgenommen.");

								$str_output .= '`tDu nimmst '.$p['name'].' `tden Schlüssel zu deinen privaten Räumen wieder ab!';

							}
							else {
								redirect($str_base_file);
							}
						}

						addnav('Zurück',$str_base_file);

						output($str_output,true);
					break;

					case 'sauber':

						if(!isset($_GET['ok'])) {
							$str_output .= '`tDu entschließt dich, in deinen Privatgemächern etwas aufzuräumen. Doch sei dir darüber im Klaren, dass dann alle Ereignisse der letzten Zeit hier drin in Vergessenheit geraten!';

							addnav('Ja, aufräumen!',$str_base_file.'&act=sauber&ok=1');
							addnav('Nein, zurück!',$str_base_file);
						}
						else {
							// Sicherung
							$sql = 'UPDATE commentary SET deleted_by=16777215 WHERE section="h_room'.$session['housekey'].'-'.$arr_ext['id'].'" AND deleted_by=0';
							db_query($sql);
							// Sicherung Ende

							$str_output .= '`tIn deinem Gemach wurde aufgeräumt - alle mehr oder minder verräterischen Spuren sind verschwunden!';

							addnav('Fein!',$str_base_file);
						}

						output($str_output,true);
					break;

					case 'return_key':

						if($_GET['ok']) {
							house_keys_del('owner='.$session['user']['acctid'].' AND value2='.$arr_ext['id'],1);

							insertcommentary($session['user']['acctid'],'/me `^gibt '.($session['user']['sex'] ? 'ihren' : 'seinen').' Schlüssel zurück.','h_room'.$arr_ext['houseid'].'-'.$arr_ext['id']);

							output('`tStill und leise legst du deinen Schlüssel auf den Türrahmen, ehe du nach einem letzten Blick zurück das Gemach verlässt..');

							addnav('Zum Wohnviertel','houses.php');
						}
						else {

							addnav('Nein, lieber nicht..',$str_base_file);
							addnav('Ja, klar!',$str_base_file.'&act=return_key&ok=1');

							output('`tMöchtest du wirklich deinen Schlüssel zu diesem Gemach wieder abgeben?`n
									Du hättest keinen Zutritt mehr!`n`n');

						}

					break;
					
					//Sortiere Bewohner des Hauses
					case 'set_house_inhabitant_order':
					{
						$str_out.=get_title('Schlüssel sortieren').'Hier hast du die Möglichkeit, deine Mitbewohner neu anzuordnen.`n';
						$str_out.=keylist_set_sort_order('k.id,a.name,k.sort_order','value2='.$arr_ext['id'].' AND type='.HOUSES_KEY_PRIVATE);
						output($str_out);
						addnav('Zurück zum Gemach',$str_base_file);
						break;
					}

					case 'convert':

						if(isset($_GET['housekey'])) {
							// Daten ermitteln
							if($_GET['housekey'] == $session['user']['house']) {
								$arr_content = array('desc'=>$arr_house['private_description'],'c_max_len'=>$arr_house['c_max_length']);
								db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);

								// Kommentare umschreiben
								db_query('UPDATE commentary SET section="h_room'.$arr_ext['houseid'].'-'.$arr_ext['id'].'" WHERE section="h'.$arr_house['houseid'].'-'.$session['user']['acctid'].'privat"');
								// Temp:
								db_query('UPDATE commentary SET section="h_room'.$arr_ext['houseid'].'-'.$arr_ext['id'].'" WHERE section="h_room-'.$arr_ext['id'].'"');

								// Einladungen löschen
								item_delete('tpl_id="prive" AND value1='.$_GET['housekey'].' AND value2='.$session['user']['acctid'],1000);

								$str_output .= '`2Hausdaten erfolgreich konvertiert!`0';
							}
							else {
								$arr_item = item_get('tpl_id="privb" AND owner='.$session['user']['acctid'].' AND value1='.$_GET['housekey'],false,'description,hvalue,value1');
								if(false !== $arr_item) {
									$arr_content = array('desc'=>$arr_item['description'],'c_max_len'=>$arr_item['hvalue']);
									db_query('UPDATE house_extensions SET content="'.db_real_escape_string(utf8_serialize($arr_content)).'" WHERE id='.$arr_ext['id']);

									// Kommentare umschreiben
									db_query('UPDATE commentary SET section="h_room'.$arr_ext['houseid'].'-'.$arr_ext['id'].'" WHERE section="h'.$arr_item['value1'].'-'.$session['user']['acctid'].'privat"');

									// Einladungen löschen
									item_delete('tpl_id="prive" AND value1='.$_GET['housekey'].' AND value2='.$session['user']['acctid'],1000);

									$str_output .= '`2Hausdaten erfolgreich konvertiert!`0';
								}
								else {
									$str_output .= '`$Gemach konnte nicht gefunden werden!`0';
								}
							}
						}
						else {
							$sql = 'SELECT id,housename,i.owner,i.description,i.hvalue, houseid AS housekey FROM items i
								LEFT JOIN houses h ON h.houseid=i.value1
								WHERE i.tpl_id="privb" AND i.owner='.$session['user']['acctid'].' AND value1 != '.$session['user']['house'];
							$res = db_query($sql);

							$str_output .= 'Die Daten welches alten Gemaches möchtest du in dieses Gemach konvertieren?`n`n';

							if(db_num_rows($res) || $session['user']['house']) {
								addnav('Eigene Gemächer');
								$str_output .= '`&Eigene Privatgemächer:`@`n';

								// Eigenes Privatgemach
								if($session['user']['house']) {
									$link = $str_base_file.'&act=convert&private='.$session['user']['acctid'].'&housekey='.$session['user']['house'];
									addnav('Privatgemach in eigenem Haus',$link);
									$str_output .= '`&Eigenes Gemach in eigenem Haus`0: '.$arr_house['private_description'].'`0`n'.create_lnk('Verwenden',$link).'`n';
									$str_output .= '`n~~~`n';
								}
								while($i = db_fetch_assoc($res)) {
									$link = $str_base_file.'&act=convert&private='.$session['user']['acctid'].'&housekey='.$i['housekey'];
									addnav('Gemach in '.strip_appoencode($i['housename'],3),$link);

									$str_output .= '`&Eigenes Gemach in '.$i['housename'].'`0: '.$i['description'].'`0`n'.create_lnk('Verwenden',$link).'`n';
									$str_output .= '`n~~~`n';
								}
							}
						}

						addnav('Zurück',$str_base_file);

						$str_out .= $str_output;
						output($str_out,true);

					break;

					case '':

						$show_invent = true;

						// Wenn Besitzer
						if(	$bool_rowner )
						{
							if(	$g_arr_house_extensions[$arr_ext['type']]['hide_convert'] !== true ||
								$g_arr_house_extensions[$arr_ext['type']]['hide_config'] !== true ||
								$g_arr_house_extensions[$arr_ext['type']]['hide_chat'] !== true)
							{
								addnav('Verwaltung');
							}

							//if($g_arr_house_extensions[$arr_ext['type']]['hide_convert'] !== true)
							//{
							//	addnav('`^Altes Gemach konvertieren!`0',$str_base_file.'&act=convert');
							//}
							if($g_arr_house_extensions[$arr_ext['type']]['hide_config'] !== true)
							{
								addnav('Gemach einrichten',$str_base_file.'&act=config',false,false,false,false);
							}
							if($g_arr_house_extensions[$arr_ext['type']]['max_furn'] >0)
							{
								addnav('Möbel rücken',$str_base_file.'&act=itemsort',false,false,false,false);
							}
							if($g_arr_house_extensions[$arr_ext['type']]['hide_chat'] !== true)
							{
								addnav('Saubermachen',$str_base_file.'&act=sauber',false,false,false,false);
							}

						}
						if($arr_ext['owner'] == 0 && $bool_howner) {
							addnav('Gemach vergeben');
							addnav('Zur Gemachverwaltung','inside_houses.php?act=man_rooms');
						}

						if($g_arr_house_extensions[$arr_ext['type']]['hide_config'] !== true && $g_arr_house_extensions[$arr_ext['type']]['hide_desc'] !== true)
						{
							$arr_ext['name'] = (!empty($arr_ext['name']) ? $arr_ext['name'] : $g_arr_house_extensions[$arr_ext['type']]['name']);

							$str_out .= '`b`c'.$arr_ext['name'].'`0`b in '.$arr_house['housename'].'`0 - '.house_get_floor($arr_ext['loc']).'`c`n';

							if($arr_ext['owner'] > 0) {
								$res = db_query('SELECT name FROM accounts WHERE acctid='.$arr_ext['owner']);
								if(db_num_rows($res)) {
									$arr_owner = db_fetch_assoc($res);
									$str_out .= '`c`^(gehört '.$arr_owner['name'].'`^)`0`c`n';
								}
								db_free_result($res);
							}
                            $arr_content['desc'] = strip_tags($arr_content['desc']);
                            CPicture::replace_pic_tags($arr_content['desc'],$arr_ext['owner'],time());

                            $str_out .= '`n'.$arr_content['desc'].'`n`n';

							output($str_out,true);
						}
						$str_out = '';

						if($g_arr_house_extensions[$arr_ext['type']]['hide_chat'] !== true)
						{
							// Kommentarsektion zeigen
							addcommentary();
							$comment_length=max($arr_content['c_max_len'],getsetting('chat_post_len_long',1500));
							viewcommentary('h_room'.$session['housekey'].'-'.$arr_ext['id'],"Mit Mitbewohnern reden:",30,"sagt",false,true,false,$comment_length,true,true,($arr_ext['val'] ? 2 : 1));
						}
						
												
						$str_out='`n`n

							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr class="frame_label">
											<td class="frame_label_l" width="46"/>
											<td class="frame_label" height="24">`tSonstiges`0</td>
											<td class="frame_label_r" width="46"/>
										</tr>
									</table>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td class="frame_border_l" />
											<td class="frame_main" valign="top" style="text-align:left;">
									<table border="0" width="100%"><tr><td width="50%">`t`bDie Schlüssel:`b `0</td><td>`t`bMobiliar:`b`0</td></tr>
									<tr><td valign="top">';
						$int_keycount = 0;
						if($int_keys_max == 0)
						{
							$str_out .= 'In diesem Gemach ist kein Platz für Einladungen.`n';
						}
						else
						{

							$sql = 'SELECT a.acctid AS aid,a.name AS besitzer,k.*
									FROM keylist k LEFT JOIN accounts a ON a.acctid=k.owner WHERE k.type='.HOUSES_KEY_PRIVATE.' AND k.value2='.$arr_ext['id'].' ORDER BY sort_order DESC, id ASC';
	
							$result = db_query($sql);
	
							$int_keycount = db_num_rows($result);
							
							// int_keys_max wird zu Beginn dieses Switch gesetzt
							$int_keys_avail = $int_keys_max;
							$int_keys_avail = max($int_keys_avail - $int_keycount,0);	// freie Schlüssel
	
							if($bool_rowner) {
								if($int_keys_avail) {
									// Schlüssel
									$str_givekey_lnk = $str_base_file.'&act=invi_set';
									$str_out .= '
												<div id="search_div">
												`tDu hast noch `b'.$int_keys_avail.'`b Schlüssel frei.`n
												Schlüssel vergeben an:`n`n
												'.form_header($str_givekey_lnk,'POST',true,'search_form','if(document.getElementById(\'search_sel\').selectedIndex > -1) {this.submit();} else {search();return false;}').'
													'.jslib_search('document.getElementById("search_form").submit();','Schlüssel vergeben!').'
												</form>
												</div>
												';
								}
								else {
									$str_out .= '`tDu hast leider keine Schlüssel mehr frei.`n';
								}
							}
						}
						
						$str_out .= 'Zutritt haben / hat:`n';

						if($int_keycount == 0 && $arr_ext['val']) {
							$str_out .= '`nNoch niemand!&nbsp;&nbsp;&nbsp;';
						}
						else 
						{
							if($arr_ext['val'] == 0) {
								$str_out .= '`nAlle Bewohner dieses Hauses!'.
											($bool_rowner && !$g_arr_house_extensions[$arr_ext['type']]['hide_config'] ? ' (Du kannst dies unter "Gemach einrichten" ändern)':'').'`n';
								if($int_keycount > 0) {
									$str_out .= 'Sowie: ';
								}
							}
							for ($i=1;$i<=$int_keycount;$i++){

								$item = db_fetch_assoc($result);

								$str_out .= '`n';

								// Wenn Gemacheigentümer, haben wir noch ein paar Rechte
								if($bool_rowner) {
									$str_out .= '`0[ '.create_lnk('X',$str_base_file.'&act=invi_del&ziel='.$item['id'],true,false,'Bist Du sicher, dass du diesen Schlüssel wieder abnehmen möchtest?').' ] ';
								}

								// Wenn Schlüssel uns selber gehört:
								if(!$bool_rowner && $item['owner'] == $session['user']['acctid']) {
									addnav('Schlüssel zurückgeben',$str_base_file.'&act=return_key');
								}

								//$str_out .= '`t'.$i.': `&'.$item['besitzer'].'`0';
								//edit: nun mit verlinkter bio - bathory
								$str_out .= '`t'.$i.': `0'.create_lnk('`&'.$item['besitzer'].'`0','bio.php?id='.$item['aid'],true,false,false,true).' ';

							}
							if($bool_rowner) {
								$str_out .= '`n`0['.create_lnk('umsortieren',$str_base_file.'&act=set_house_inhabitant_order',true,false).']';
							}
						}

						$str_out .= '</td><td valign="top">&nbsp;';
											
						if($g_arr_house_extensions[$arr_ext['type']]['max_furn'] == 0)
						{
							$str_out .= 'In diesem Gemach ist kein Platz für Möbel.';								
						}
						else
						{
							if(HOUSES_BUILD_STATE_ABANDONED == $arr_house['build_state']) {
								$arr_naviconf = array();
							}
							else {
								$arr_naviconf = array('furniture_privateinvited');	
								if($bool_not_invited) {
									// zusätzlich die Möbel zeigen, die nur für Bewohner des Hauses sichtbar sein sollen
									$arr_naviconf[] = 'furniture_private';
								}
							}
							
							
							$str_out .= house_show_furniture($session['housekey'],$arr_ext['id'],$bool_rowner,$arr_naviconf);
							if($bool_rowner)
							{
								$str_out.='/'.$g_arr_house_extensions[$arr_ext['type']]['max_furn'].' Möbel eingelagert)';
							}
						}
						$str_out.='</td></tr></table>
										</td>
												<td class="frame_border_r" />
											</tr>
										</table>
										<table width="100%" border="0" cellpadding="0" cellspacing="0">
											<tr class="frame_label_b">
												<td class="frame_label_lb" width="46"/>
												<td class="frame_label" height="24"><img src="./images/frame/zier_b2.png"/></td>
												<td class="frame_label_rb" width="46"/>
											</tr>
										</table>
									</td>
								</tr>
								</table>';
						
						output($str_out,true);

						// Folgende Navihotkeys garantieren
						$accesskeys['w']=0;$accesskeys['d']=0;$accesskeys['m']=0;$accesskeys['l']=0;$accesskeys['h']=0;

						addnav('Ausgang');

						// Logout-Funktion
						if($bool_not_invited) {
							addnav('L?Einschlafen (LogOut)','login.php?op=logout&loc='.USER_LOC_HOUSE.'&restatloc='.$arr_house['houseid']);
						}

						// Wenn nicht über Einladung in diesem Privatgemach
						if($bool_not_invited) {
							addnav('H?Zurück ins Haus','inside_houses.php');
						}

						addnav('W?Zurück zum Wohnviertel','houses.php?op=enter');
						addnav('d?Zurück zum Stadtzentrum','village.php');
						addnav('M?Zurück zum Marktplatz','market.php');
						
						house_set_room_navs($session['user']['acctid'],$arr_ext['houseid'],true,$arr_ext['id'],$bool_not_invited);
						
					break;

				}



			break;

			// Erweiterte Bauoptionen für Gemächer
			case 'build_start':


			break;

			// Bau fertig
			case 'build_finished':



			break;

			// Abreißen
			case 'rip':

				global $str_out;

				// Überprüfen, ob dann ein invalides Stockwerk entstünde
				// Dach + Turm + Keller dürfen immer abgerissen werden
				if($arr_ext['loc'] != HOUSES_ROOM_BASEMENT && $arr_ext['loc'] != HOUSES_ROOM_ROOF && $arr_ext['loc'] != HOUSES_ROOM_TOWER) {
					$sql = 'SELECT id FROM house_extensions WHERE houseid='.$arr_house['houseid'].' AND id!='.$arr_ext['id'].' AND loc>'.$arr_ext['loc'];
					if(db_num_rows(db_query($sql))) {

						// Überprüfen, ob Gemach in gleichem Stockwerk
						$sql = 'SELECT id FROM house_extensions WHERE houseid='.$arr_house['houseid'].' AND id!='.$arr_ext['id'].' AND loc='.$arr_ext['loc'];
						if(!db_num_rows(db_query($sql))) {
							$str_out .= '`$Wenn du dieses Gemach abreißt, entfällt damit eine tragende Wand. Wenn eine tragende Wand entfällt, entfällt bald auch dein Haus. Also lass es besser bleiben und fang mit dem Abreißen von oben an.`0';
							output($str_out);
							addnav('Zurück','inside_houses.php?act=man_rooms');
							page_footer();
						}
					}
				}
				
				// Zur Sicherheit:
				house_take_room($arr_ext,$arr_house,false);

			break;

		}	// END Main switch
	}


?>
