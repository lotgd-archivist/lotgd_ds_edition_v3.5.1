<?php

/*
*  Ein kleines Fleckchen in Atrahor dass der langen Geschichte geschuldet ist
*  @author Dragonslayer for Atrahor.de
*/

require_once 'common.php';
$str_filename = basename(__FILE__);
page_header('Das Museum');

$str_backtext = 'Zurück in die Bibliothek';
$str_backlink = 'library.php';

$str_out = '';
addnav('Verlassen');
addnav($str_backtext,$str_backlink);

//ein paar variablen umschreiben damit der Code logisch gruppiert ist
if($_GET['op'] == 'deposit_useritems')
{
	$_GET['op'] = 'user_items';
	$_GET['act'] = 'deposit_useritems';
}

switch ($_GET['op'])
{
	default:
	case '':
		{	
			addnav('Museumsflügel');
			addnav('Exponate unserer Bürger',$str_filename.'?op=user_items');
			addnav('Der Gildenflügel', $str_filename.'?op=guilds');
			addnav('Helden aus Valhalla', $str_filename.'?op=famous_users');
			
			$str_out .= get_title('Das Museum '.getsetting('townname','Atrahor').'s');
			$str_out .= 'Das Museumsgebäude ist ein architektonisches Meisterwerk. Denkt man von außen, dass darin wohl kaum zwei ausgewachsene Zwerge nebeneinander stehen könnten, ohne sich gegenseitig den Platz zu rauben, offenbahrt sich die perspektivische Anomalie bereits die ersten Schritte nach dem Eintreten. Die riesige Eingangshalle ist rundherum mit einem gut erhaltenen Hochrelief geschmückt, welches Titanen im Kampf gegen das Chaos, Götter und die Urstrahlung zeigt. Hier und dort stehen ein paar Novizen und diskutieren, skizzieren und analysieren die Szenerie, so dass ein leises murmeln, von vielen Echos verworfen, den ganzen Saal erfüllt. Einige Tore führen zu anderen Museumsflügeln und auch dort scheint reges Treiben zu herrschen.';
			break;
		}
	case 'user_items':
		{
			addnav('Zurück zum Hauptflügel', $str_filename);
			addnav('Exponate der Bürger');
			$str_out .= get_title('Exponat hinzugefügt');		
			switch ($_GET['act'])
			{
				default:
				case '':
					{
						addnav('Zufällige Exponate',$str_filename.'?op=user_items&order=rand');
						$arr_result = db_get('SELECT count(*) AS count FROM museum_useritems WHERE owner = '.$Char->acctid);
						if($arr_result['count'] > 0)
						{
							addnav('Eigene Exponate anzeigen',$str_filename.'?op=user_items&owner='.$Char->acctid);
						}
						addnav('Exponate hinzufügen',$str_filename.'?op=user_items&act=deposit');
						
						$str_where = '';
						if(isset($_GET['owner']))
						{
							addnav('Alle Exponate anzeigen',$str_filename.'?op=user_items');
							$str_where .= ' AND owner='.(int)$_GET['owner'];
						}						
						
						if($str_where != '')
						{
							$str_where = ' WHERE 1 '.$str_where;
						}
						
						$str_order = '';
						if(isset($_GET['order']) && $_GET['order'] == 'rand')
						{
							addnav('Alle Exponate anzeigen',$str_filename.'?op=user_items');
							$str_order .= ' RAND()';
						}
						if($str_order != '')
						{
							$str_order = ' ORDER BY '.$str_order;
						}	
						
						$str_count_sql='SELECT COUNT(*) AS c FROM museum_useritems'.$str_where.$str_order;						
						$arr_pages = page_nav($str_filename.'?op=user_items', $str_count_sql, 30);						
						$arr_result = db_get_all('SELECT m.*, IFNULL(a.name,"Unbekannter Spender") AS owner FROM museum_useritems m LEFT JOIN accounts a ON (owner=acctid) '.$str_where.$str_order.' LIMIT '.$arr_pages['limit']);
						
						$str_out .= get_title('Exponate der Bürger zu Atrahor');
						$str_out .= 'Vor dir liegt ein Flügel der wie auf den ersten Blick wie ein wildes Sammelsurium aussehen mag. Bei näherer Betrachtung der vielen kleinen Vitrinen und Podeste, Verschläge und Kisten stellt sich heraus, dass es sich hier um die wertvollsten Erinnerungen der Bewohner Atrahors handelt. Fein säuberlich archiviert und liebevoll zusammengetragen von all den Bewohnern deiner Stadt.`n`n';

						if(is_array($arr_result) && count($arr_result) > 0)
						{
							$bool_is_su = $Char->isSuperuser();
							$str_out .= '<div class="trhead">Exponate unserer Bürger</div>';
							foreach ($arr_result as $arr_item)
							{
								$str_class = ($str_class == 'trlight'?'trdark':'trlight');
								$bool_edit_allowed = $bool_is_su || $Char->acctid == $arr_item['owner'];
								$str_out .= '<div class="'.$str_class.'">'.plu_mi($arr_item['id'],0,false).'&nbsp;'.$arr_item['name'].' von '.$arr_item['owner'].'`0';
								
								if($bool_edit_allowed)
								{
									$str_out .= '&nbsp;';
									$str_out .= '['.create_lnk('Editieren',$str_filename.'?op=user_items&act=edit&id='.$arr_item['id'].'&r='.urlencode(calcreturnpath()),true,false).']&nbsp;';
									$str_out .= '['.create_lnk('Löschen',$str_filename.'?op=user_items&act=delete&id='.$arr_item['id'].'&r='.urlencode(calcreturnpath()),true,false,'Soll das Exponat wirklich gelöscht werden?').']';
								}
								$str_out .= '</div>';
								$str_out .= '<div class="'.$str_class.'" id="'.$arr_item['id'].'" style="display:none;" >'.$arr_item['description'].'</div>';
								$str_out .= '<hr>';
								
							}
						}
						else 
						{
							$str_out .=	'`$Dieser Museumsflügel ist allerdings momentan so leer, dass man hier nichtmal Staub und Spinnenweben ansehen könnte`0';
						}
						break;
					}
				case 'delete':
					{
						$int_id = (int)$_GET['id'];
						$str_return_path = isset($_GET['r']) ? urldecode($_GET['r']) : $str_filename.'?op=user_items';
						
						db_query('DELETE FROM museum_useritems WHERE id='.$_GET['id']);
						setStatusMessage('Exponat erfolgreich entfernt');
						redirect($str_return_path);
						break;
					}
				case 'edit':
					{
						$int_id = (int)$_GET['id'];
						$str_return_path = isset($_GET['r']) ? urldecode($_GET['r']) : $str_filename.'?op=user_items';
						
						addnav('Abbrechen',$str_return_path);
						$arr_data = db_get('SELECT * FROM museum_useritems WHERE id='.$int_id);

						array_walk($arr_data,create_function('&$val','$val = str_replace("`","``",$val);'));
						
						$arr_form = array(
							'id'	=> 'ID des Exponates,hidden',
							'owner'	=> 'Eigentümer des Exponates,hidden',
							'name'	=> 'Titel des Exponates',
							'description'	=> 'Beschreibung des Exponates,textarea,80,10',
							'comment'		=> 'Was hat es mit diesem Item auf sich?,textarea,80,10'
						);
						
						$str_out .= get_title('Exponat editieren');
						$str_out .= '`tEiner der Museumszwerge holt Dein Exponat vorsichtig aus der Vitrine und lässt es von dir bearbeiten. Er hat vollstes Vertrauen in deine wohlwollenden Hände, schließlich handelt es sich ja um Deine Erinnerung!`n`n';
						
						$str_out .= form_header($str_filename.'?op=user_items&act=edit_save&r='.urlencode(calcreturnpath()));
						$str_out .= generateform($arr_form,$arr_data);
						$str_out .= form_footer();
						
						break;
					}
				case 'edit_save':
					{
						$str_return_path = isset($_GET['r']) ? urldecode($_GET['r']) : $str_filename.'?op=user_items';
						db_update('museum_useritems',array('owner'=>$_POST['owner'], 'name' => $_POST['name'], 'description' => $_POST['description'], 'comment' => $_POST['comment']), 'id='.(int)$_POST['id']);
						setStatusMessage('Das Exponat wurde erfolgreich bearbeitet');
						redirect($str_return_path);
						break;
					}
				case 'deposit':
					{
						addnav('Zum Museum',$str_filename.'?op=user_items');
						
						$str_out .= '`qDer Archivar betrachtet dich aufmerksam und wartet geduldig darauf welchen Gegenstand du im Museum einlagern möchtest. Nicht jedoch ohne dir vorher noch einmal zu erklären, dass ein einmal übergebenes Exponat nicht wieder in deinen Besitz genommen werden kann.`n`n';

						item_invent_set_env(ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_LOC_PLAYER | ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_SEARCH |ITEM_INVENT_HEAD_RETURN_OUTPUT);
					
						$str_out .= item_invent_show_data(item_invent_head(' owner='.$Char->acctid.' AND i.tpl_id="unikat"',20), 'Du hast nichts was den Archivar interessieren könnte',array('Archivieren'=>'deposit_useritems'));
						
						break;
					}
				case 'deposit_useritems':
					{
						$arr_item = item_get((int)$_GET['id']);
						
						db_insert('museum_useritems',array('owner'=>$arr_item['owner'],'name'=>$arr_item['name'],'description'=>$arr_item['description']));
						$int_id = db_insert_id();
						
						item_delete((int)$_GET['id']);
						
						$str_out .= '`tDer Archivar nimmt dein Exponat entgegen und trägt es in die große Liste der Austellungsstücke ein. Dann trägt er es an einen Platz in der Galerie wo es fortan von allen neugierigen Augen bewundert werden kann. Dir selbst erlaubt er natürlich einen beschreibenden Text hinzuzufügen, denn was wären Exponate ohne die dazugehörige Geschichte?';
						
						addnav('Alle Exponate anzeigen',$str_filename.'?op=user_items');
						addnav('Eigene Exponate anzeigen',$str_filename.'?op=user_items&owner='.$Char->acctid);
						addnav('Editieren',$str_filename.'?op=user_items&act=edit&id='.$int_id);
					}
			}
			$str_wing = $_GET['op'];
			$str_out .= get_title($arr_museum_wings[$str_wing]['title']);
			$str_out .= $arr_museum_wings[$str_wing]['description'];
			break;
		}
	case 'guilds':
		{
			addnav('Zurück zum Hauptflügel', $str_filename);
			addnav('Der Gildenflügel Atrahors');
			switch ($_GET['act'])
			{
				default:
				case '':
					{
						if($access_control->su_check(access_control::SU_RIGHT_EDITORGUILDS))
						{
							addnav('Neue Gilde hinzufügen',$str_filename.'?op=guilds&act=new');
						}
						addnav('Zufällige Gilden',$str_filename.'?op=guilds&order=rand');
						$arr_guilds_user_is_leader = db_get_all('SELECT id FROM museum_guilds WHERE leaders LIKE "%|'.$Char->acctid.'|%"', false, 'id');
						if(count($arr_guilds_user_is_leader) > 0)
						{
							addnav('Eigene Gilden anzeigen',$str_filename.'?op=user_guilds&leader='.$Char->acctid);
						}
						
						$str_where = '';
						if(isset($_GET['leader']))
						{
							addnav('Alle Gilden anzeigen',$str_filename.'?op=user_items');
							$str_where .= ' AND leaders LIKE "%|'.$Char->acctid.'|%"';
						}												
						if($str_where != '')
						{
							$str_where = ' WHERE 1 '.$str_where;
						}
						
						$str_order = '';
						if(isset($_GET['order']) && $_GET['order'] == 'rand')
						{
							addnav('Alle Gilden anzeigen',$str_filename.'?op=guilds');
							$str_order .= ' RAND()';
						}
						if($str_order != '')
						{
							$str_order = ' ORDER BY '.$str_order;
						}
						else 
						{
							$str_order= ' ORDER BY date_founded ASC';
						}
						
						$str_count_sql='SELECT COUNT(*) AS c FROM museum_guilds'.$str_where;						
						$arr_pages = page_nav($str_filename.'?op=guilds', $str_count_sql, 30);						
						$arr_result = db_get_all('SELECT * FROM museum_guilds g '.$str_where.$str_order.' LIMIT '.$arr_pages['limit']);
						
						$str_out .= get_title('Die Gilden Atrahors');
						$str_out .= 'Oh wie viele Bündnisse und Genossenschaften hat Atrahor im Laufe der Geschichte schon gesehen und wieviele Geschichten ließen sich davon am Lagerfeuer erzählen? Einen wahrhaft großen Anteil daran wurde seit jeher den Gilden zugemessen. Hier huldigen wir allen Gilden, die über die Jahre Atrahors Antlitz geprägt haben und nie aus unserem Gedächtnis verschwinden sollen.`n`n';

						if(is_array($arr_result) && count($arr_result) > 0)
						{
							$bool_is_su = $Char->isSuperuser();
							$str_out .= '<div class="trhead">Exponate unserer Bürger</div>';
							foreach ($arr_result as $arr_guild)
							{
								$bool_edit_allowed = $bool_is_su || in_array($Char->acctid, $arr_guilds_user_is_leader);
								$str_class = ($str_class == 'trlight'?'trdark':'trlight');
								
								$str_out .= '<div class="'.$str_class.'">'.plu_mi($arr_guild['id'],0,false).'&nbsp;'.$arr_guild['name'].' (Gegründet von: '.(is_null_or_empty($arr_guild['founder'])?'Unbekannter Meister':$arr_guild['founder']).') `&*`0'.$arr_guild['date_founded'].' - `)†`0'.$arr_guild['date_deleted'];
								
								if($bool_edit_allowed)
								{
									$str_out .= '&nbsp;';
									$str_out .= '['.create_lnk('Editieren',$str_filename.'?op=guilds&act=edit&id='.$arr_guild['id'].'&r='.urlencode(calcreturnpath()),true,false).']&nbsp;';
									$str_out .= '['.create_lnk('Löschen',$str_filename.'?op=guilds&act=delete&id='.$arr_guild['id'].'&r='.urlencode(calcreturnpath()),true,false,'Soll der Gildeneintrag wirklich gelöscht werden?').']';
								}
								$str_out .= '</div>';
								$str_out .= '<div class="'.$str_class.'" id="'.$arr_guild['id'].'" style="display:none;" >'.$arr_guild['bio'].'</div>';
								$str_out .= '<hr>';
								
							}
						}
						else 
						{
							$str_out .=	'`$Dieser Museumsflügel ist allerdings momentan so leer, dass man hier nichtmal Staub und Spinnenweben ansehen könnte`0';
						}
						break;
					}
				case 'delete':
					{
						$int_id = (int)$_GET['id'];
						$str_return_path = isset($_GET['r']) ? urldecode($_GET['r']) : $str_filename.'?op=guilds';
						
						db_query('DELETE FROM museum_guilds WHERE id='.$_GET['id']);
						setStatusMessage('Gilde erfolgreich entfernt');
						redirect($str_return_path);
						break;
					}
				case 'edit':
					{
						$int_id = (int)$_GET['id'];
						$str_return_path = isset($_GET['r']) ? urldecode($_GET['r']) : $str_filename.'?op=guilds';
						
						addnav('Abbrechen',$str_return_path);
						$arr_data = db_get('SELECT * FROM museum_guilds WHERE id='.$int_id);

						array_walk($arr_data,create_function('&$val','$val = str_replace("`","``",$val);'));
						
						$arr_form = array(
							'id'	=> 'ID der Gilde,hidden',
							'date_founded' => 'Gründungstag der Gilde,viewonly',
							'date_deleted' => 'Tag der Schließung,viewonly',
							'name'	=> 'Name der Gilde',
							'founder' => 'Gründer der Gilde',
							'bio'	=> 'Biographie der Gilde,textarea,80,20',							
						);
						
						if($access_control->su_check(access_control::SU_RIGHT_EDITORGUILDS))
						{
							$arr_form = array_merge($arr_form, array('leaders' => 'IDs der Spieler die diesen Eintrag editieren können|?Ehemals Founder und Leader IMMER in folgendem Format eintragen: <b>|ID|</b>'));
						}
						
						$str_out .= get_title('Gilde editieren');
						$str_out .= '`tEiner der Museumszwerge schlägt das große Buch der Gilden auf und wartet darauf deine Korrekturen entgegennehmen zu dürfen.`n`n';
						
						$str_out .= form_header($str_filename.'?op=guilds&act=edit_save&r='.urlencode(calcreturnpath()));
						$str_out .= generateform($arr_form,$arr_data);
						$str_out .= form_footer();
						
						break;
					}
				case 'edit_save':
					{
						$str_return_path = isset($_GET['r']) ? urldecode($_GET['r']) : $str_filename.'?op=guilds';
                        $arr_data = array('name'=>clean_html($_POST['name']), 'bio' => clean_html($_POST['bio']), 'founder' => clean_html($_POST['founder']));
						if(!is_null_or_empty($_POST['leaders']))
						{
							$arr_data['leaders'] = utf8_preg_replace('[^\d\|]','',$_POST['leaders']);
						}

						db_update('museum_guilds',$arr_data, 'id='.(int)$_POST['id']);
						setStatusMessage('Der Gildeneintrag wurde erfolgreich bearbeitet');
						redirect($str_return_path);
						break;
					}
				case 'new':
					{
						$str_return_path = isset($_GET['r']) ? urldecode($_GET['r']) : $str_filename.'?op=guilds';
						addnav('Abbrechen',$str_return_path);
						
						$arr_form = array(
							'id'	=> 'ID der Gilde,hidden',
							'date_founded' => 'Gründungstag der Gilde',
							'date_deleted' => 'Tag der Schließung',
							'name'	=> 'Name der Gilde',
							'founder' => 'Gründer der Gilde',
							'bio'	=> 'Biographie der Gilde,textarea,80,20',
							'leaders' => 'IDs der Spieler die diesen Eintrag editieren können|?Ehemals Founder und Leader IMMER in folgendem Format eintragen: <b>|ID|</b>'						
						);
					
						$str_out .= get_title('Gilde editieren');
						$str_out .= '`tEiner der Museumszwerge schlägt das große Buch der Gilden auf und wartet darauf deine Korrekturen entgegennehmen zu dürfen.`n`n';
						
						$str_out .= form_header($str_filename.'?op=guilds&act=new_save');
						$str_out .= generateform($arr_form,array());
						$str_out .= form_footer();
												
						break;
					}
				case 'new_save':
					{
						$str_return_path = isset($_GET['r']) ? urldecode($_GET['r']) : $str_filename.'?op=guilds';
						
						db_insert('museum_guilds',array('name'=>clean_html($_POST['name']), 'bio' => clean_html($_POST['bio']), 'founder' => clean_html($_POST['founder']), 'date_founded' => $_POST['date_founded'], 'date_deleted' => $_POST['date_deleted'], 'leaders' => utf8_preg_replace('[^\d\|]','',$_POST['leaders'])), 'id='.(int)$_POST['id']);
						setStatusMessage('Der Gildeneintrag wurde erfolgreich hinzugefügt');
						redirect($str_return_path);
						break;	
					}
			}
			break;
		}
	case 'famous_users':
		{
			addnav('Zurück zum Hauptflügel', $str_filename);
			
			$str_count_sql='SELECT COUNT(*) AS c FROM valhalla';						
			$arr_pages = page_nav($str_filename.'?op=famous_users', $str_count_sql, 30);						
			$arr_result = db_get_all('SELECT v.*,r.colname AS race FROM valhalla v LEFT JOIN races r ON (id=race) ORDER BY dragonkills DESC LIMIT '.$arr_pages['limit']);
			
			$str_out .= get_title('Die Helden Atrahors');
			$str_out .= '`tDer Flur zeigt eine Liste bekannter Helden Atrahors welche von uns gehen mussten. Direkt am Eingang ist ein kleiner Anschlag zu sehen, auf dem geschrieben steht:`yDas Museum Atrahors bedankt sich für die Zusammenarbeit bei Odin und Ramius, ohne deren freundliche Unterstützung wir niemals die Daten aus Valhalla hätten zusammentragen können.`t';
			$str_out.='`n`n`0<table cellpadding=2 width="100%" cellspacing=1 bgcolor="#999999" align="center"><tr class="trhead"><td>Valhallas Helden</td>';
			
			if(count($arr_result) > 0)
			{
				foreach ($arr_result as $row)
				{
					$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
					$str_out.='</tr><tr class='.$bgclass.'><td style="padding-bottom:5px;">';
					$str_out .= plu_mi($row['acctid'],0,false).'`^'.$row['name'].' `^* '.getgamedate($row['birth']).' `^&dagger; '.getgamedate($row['death']).' '.($row['sex']?'Sie':'Er').' war ein ruhmreicher '.$row['race'].'`^ mit '.$row['dragonkills'].' Heldentat' . ($row['dragonkills']>1?'en':'');					
					
					$str_out .= '<div id="'.$row['acctid'].'" style="display:none;" >';
					if($row['comments']) 
					{
						
						$arr_comments=adv_unserialize($row['comments']);
						foreach($arr_comments as $key=>$val){
							$str_out.=$val;
							$str_out.='`n<hr>';
						}
						
					}
					else 
					{
						$str_out .= 'Es hat noch niemand einen Nachruf geschrieben, oder er wurde noch nicht in Stein gemeißelt.';
					}
					$str_out .= '<div>';
					
					
				}
				$str_out.='</td></tr>';
				$str_out .= '</table>';
			}
			else 
			{
				$str_out .=	'`$Dieser Museumsflügel ist allerdings momentan so leer, dass man hier nichtmal Staub und Spinnenweben ansehen könnte`0';
			}
			
			break;
		}
	case 'wing':
		{
			$str_wing = $_GET['wing'];
			$str_out .= get_title($arr_museum_wings[$str_wing]['title']);
			$str_out .= $arr_museum_wings[$str_wing]['description'];
			

		}
}

output($str_out);
page_footer();

?>