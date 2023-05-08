<?php
/**
 * Ein Editor um Texte zu erstellen, die in Abhängigkeit des aktuellen Wetters abgerufen werden können
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 */

include_once('common.php');

//Für das schnelle an und abschalten
if(isset($_GET['on_off']))
{
	$access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS ,true);

	$id = (int)$_GET['id'];

	// Switch
	$sql = 'UPDATE weather_texts SET enabled = IF(enabled=1,0,1) WHERE id='.$id;
	db_query($sql);

	$str_back = '/mb Wetter wurde umgeschaltet!';
	jslib_http_command($str_back);
	exit();
}

//Revidieren wenn Autoren Wettertexte verfassen
if(isset($_GET['revise']))
{
	$access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS ,true);

	$id = (int)$_GET['id'];

	// Switch
	$sql = 'UPDATE weather_texts SET revised = IF(revised=1,0,1) WHERE id='.$id;
	db_query($sql);

	$str_back = '/mb Wetter wurde umgeschaltet!';
	jslib_http_command($str_back);
	exit();
}

page_header('Wettertext Editor');
$str_out = '';
$str_filename = basename(__FILE__);

if($Char->isSuperuser())
{
	grotto_nav();
}

switch ($_GET['op'])
{
	default:
	case '':
		{			
			addnav('Optionen');
			addnav('Eintrag hinzufügen',$str_filename.'?op=add');
			
			if($access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS))
			{
				addnav('Kategorien verwalten',$str_filename.'?op=categories');
			}
			
			$arr_weather_texts = db_get_all('SELECT w.id AS id, IFNULL(c.category,"Nicht zugeordnet") AS category ,LEFT(text,256) AS text,IF(php != "","ja","nein") AS php,enabled,revised FROM weather_texts w LEFT JOIN weather_texts_categories c ON (w.category=c.id) ORDER BY c.category, id');
			
			$str_out .= get_title('Wettertext Editor');
			
			if(count($arr_weather_texts) == 0)
			{
				$str_out .= '`tEs wurden noch keine Wettertexte gefunden. Ruft die Mods, das darf ja wohl nicht wahr sein! So eine Arbeistverweigerung ist mir ja noch nie untergekommen, zu meiner Zeit hätte man sow einen Frevel mit dem Rohrstock geahndet. Aber heutzutage verweichlicht die Jugend ja immer mehr so dass heute jeder dahergelaufene Grottenolm sich solch eine Faulheit leisten kann. Pfff. Ich bin zu alt für den Scheiss.';
			}
			else 
			{
				$str_out .= '
				<table style="width:95%; margin-left:auto; margin-right:auto;">
					<tr class="trhead">
						<th>Kategorie</th>
						<th>Text</th>
						<th>PHP Code?</th>
						<th>Optionen</th>
					</tr>
				';
				
				foreach ($arr_weather_texts as $arr_weather_text)
				{
					$str_class = $str_class == 'trlight' ? 'trdark' : 'trlight';
					$str_out .= '
						<tr class="'.$str_class.'">
							<td nowrap="nowrap">'.$arr_weather_text['category'].'</td>
							<td>'.$arr_weather_text['text'].' [...]</td>
							<td>'.$arr_weather_text['php'].'</td>
							<td nowrap="nowrap">
								'.($access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS) ? 
									  create_lnk('bearbeiten',$str_filename.'?op=edit&id='.$arr_weather_text['id'],false).' | 
									'.create_lnk('löschen',$str_filename.'?op=delete&id='.$arr_weather_text['id'],false,false,'Wirklich löschen?').' |
									'.jslib_int_switch($str_filename.'?on_off=1&id='.$arr_weather_text['id'],$arr_weather_text['enabled']) .' |
									'.jslib_int_switch($str_filename.'?revise=1&id='.$arr_weather_text['id'],$arr_weather_text['enabled'],'Überprüft','Nicht überprüft') : ':-P').'
							</td>
						</tr>
					';
				}
				$str_out .= '</table>';
				addpregnav('/'.$str_filename.'\?op=(edit|delete)&id=\d+/');
				addpregnav('/'.$str_filename.'\?on_off=1&id=\d+/');
			}
			break;
		}
	case 'add':
	case 'edit':
		{
			$arr_categories = db_get_all('SELECT * FROM weather_texts_categories ORDER BY category ASC');
			
			//Formtag für Kategorien generieren
			$str_category_select = 'Bitte die Kategorie des Wettertexts auswählen,select';
			foreach ($arr_categories as $arr_category)
			{
				$str_category_select .= ','.$arr_category['id'].','.$arr_category['category'];
			}
			
			//Formtag für Wetter generieren
			$str_weather_select = 'Bitte das zugehörige Wetter auswählen,select_multiple,8,0,-- Beliebiges Wetter --';
			
			$Weather = new Weather();
			$arr_weather = array();
			foreach($Weather as $key => $arr_weather_type)
			{
				$arr_weather[$key] = $arr_weather_type['name'];				
			}
			asort($arr_weather);
			foreach ($arr_weather as $key => $val)
			{
				$str_weather_select .= ','.$key.','.str_replace(',','',$val);
			}
			$str_weather_select .= '|?Mehrere Einträge auswählen indem STRG gedrückt hält und die Werte auswählt.';
			unset($Weather);
						
			$arr_data = array();
			if(is_null_or_empty($_REQUEST['id']) == false)
			{
				$int_id = (int)$_REQUEST['id'];
				$arr_data = db_get('SELECT * FROM weather_texts WHERE id='.$int_id);
				
				//Kategorie Array wieder auseinanderfusseln
				$arr_data['weather'] = explode('||',$arr_data['weather']);
				array_walk($arr_data['weather'],create_function('&$val','$val = str_replace("|","",$val);'));				
			}			
			
			//Farbtags für Ausgabe in Form vorbereiten
			array_walk($arr_data,create_function('&$val','$val = str_replace("`","``",$val);'));
			
			//Form erstellen
			$arr_form = array();			
			$arr_form['id']			= 'ID für die DB,hidden';
			
			//Ein paar spezielle reinstellungen für Grottenolme
			if($access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS))
			{
				$arr_form['enabled']	= 'Ist der Wettertext freigeschaltet?,bool';
				$arr_form['revised']	= 'Wurde der Wettertext grottenseitig überprüft?,bool';
			}
			
			$arr_form['category']	= $str_category_select;
			$arr_form['weather']	= $str_weather_select;
			$arr_form['text_preview'] = ',preview,text';
			$arr_form['text']		= 'Der Wettertext,textarea,80,10|?Bitte beachte, dass du dort wie immer PHP-Code in doppelt geschweiften Klammern einbinden kannst : {{ return "was auch immer"; }}';
			
			//Der PHP Code wird nur von Grottenolmen eingetragen!!!
			if($access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS))
			{
				$arr_form['phpcodediv'] = 'PHP Code,divider';
				$arr_data['php_description'] = '`$Dem PHP Quelltext wird stets automatisch ein "global $Char,$session,$arrWeather" vorangestellt, so dass dies nicht jedesmal aufs Neue gemacht werden muss. Bitte achtet darauf dass der Code nicht bei jedem Seitenreload erneut aufgerufen wird und ein Buff/Item oder sonstwas gibt. Immer eine Variable in Atrahor::$Session ablegen und abfragen!';
				$arr_form['php_description'] = 'Beschreibung für den PHP Code,html';
				$arr_form['php'] = 'PHP Code der ggf ausgeführt wird wenn dieses Wetter auftritt,textarea,80,20';
			}
			
			addnav('Abbrechen',$str_filename);
			$str_out .= get_title('Eintrag hinzufügen/editieren');
			$str_out .= form_header($str_filename.'?op=save');
			$str_out .= generateform($arr_form,$arr_data);
			$str_out .= form_footer();
			break;
		}
	case 'delete':
		{
			//Cheater raus!
			$access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS,true);
			
			setStatusMessage('Eintrag erfolgreich gelöscht');
			$int_id = (int)$_REQUEST['id'];
			db_query('DELETE FROM weather_texts WHERE id='.$int_id);
			systemlog('Wettertext Nr.'.$int_id.' wurde gelöscht!');
			redirect($str_filename);
			break;			
		}
	case 'save':
		{
			if(is_null_or_empty($_POST['id']) == false)
			{
				$int_id = (int)$_POST['id'];
				if($int_id > 0)
				{
					$arr_data = array();			
				
					//Ein paar spezielle Einstellungen für Grottenolme
					if($access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS))
					{
						$arr_data['enabled']	= is_null_or_empty($_POST['enabled']) == false ? 1 : 0;
						$arr_data['revised']	= is_null_or_empty($_POST['revised']) == false ? 1 : 0;
						$arr_data['php']		= addstripslashes($_POST['php']);
					}
					
					$arr_data['weather']	= array_reduce($_POST['weather'],create_function('$x,$y','$x .= "|".$y."|"; return $x;'));
					$arr_data['category']	= (int)$_POST['category'];
					$arr_data['text']		= addstripslashes($_POST['text']);
					
					db_update('weather_texts',$arr_data,' id='.$int_id);
					setStatusMessage('Eintrag erfolgreich bearbeitet');
					redirect($str_filename.'?op=edit&id='.$int_id);
				}
				else 
				{
					setStatusMessage('Die ID war nicht gültig');
				}
			}
			else 
			{
				$arr_data = array();			
				
				//Ein paar spezielle reinstellungen für Grottenolme
				if($access_control->su_check(access_control::SU_RIGHT_EDITOR_WEATHER_TEXTS))
				{
					$arr_data['enabled']	= is_null_or_empty($_POST['enabled']) == false ? 1 : 0;
					$arr_data['revised']	= is_null_or_empty($_POST['revised']) == false ? 1 : 0;
					$arr_data['php']		= addstripslashes($_POST['php']);
				}
				
				$arr_data['weather']	= array_reduce($_POST['weather'],create_function('$x,$y','$x .= "|".$y."|"; return $x;'));
				$arr_data['category']	= (int)$_POST['category'];
				$arr_data['text']		= addstripslashes($_POST['text']);
				
				db_insert('weather_texts',$arr_data);
				$int_id = db_insert_id();
				setStatusMessage('Eintrag erfolgreich hinzugefügt');
				redirect($str_filename.'?op=edit&id='.$int_id);
			}
			break;
		}
	case 'categories':
		{
			
			$arr_categories = db_get_all('SELECT * FROM weather_texts_categories ORDER BY category ASC');
			
			addnav('Kategorien');
			addnav('Zurück zu den Texten',$str_filename);
			addnav('Neue Kategorie hinzufügen',$str_filename.'?op=add_category');
			$str_out .= get_title('Kategorien bearbeiten');
			$str_out .= '`tDie Kategorien gruppieren Wettertexte zusammen. Anhand dieser Gruppen werden die erstellten Texte später im Code gefunden und ausgegeben. `n
			Die Codezeile Weather::get_weather_text(\'Spielplatz\'); gibt somit einen zum aktuellen Wetter passenden Text zurück der in der Kategorie Spielplatz liegt.';
			
			if(count($arr_categories) == 0)
			{
				$str_out .= '`tWoaaaaaas? Keine Kategorien? Also Leude Leude Leude...ich hab mal die Kategorie "Nirvana" automatisch für dich angelegt. Weil ohne die jeht nüscht.';
				
				//Die Standardkategorie MUSS da sein und auf ID eins liegen
				db_query('TRUNCATE weather_texts_categories');
				db_insert('weather_texts_categories',array('category' => 'Nirvana'));
				setStatusMessage('Standardkategorie erfolgreich hinzugefügt');
			}
			else 
			{
				$str_out .= '
				<table>
					<tr class="trhead">
						<th>Kategorie</th>
						<th>Optionen</th>
					</tr>
				';
				
				foreach ($arr_categories as $arr_category)
				{
					//Die Standardkategorie darf nicht gelöscht werden
					if($arr_category['id'] == 1)
					{
						continue;
					}
					
					$str_class = $str_class == 'trlight' ? 'trdark' : 'trlight';
					
					$str_out .= '
						<tr class="'.$str_class.'">
							<td nowrap="nowrap">'.$arr_category['category'].'</td>
							<td nowrap="nowrap">
									'.create_lnk('bearbeiten',$str_filename.'?op=edit_category&id='.$arr_category['id'],false).' | 
									'.create_lnk('löschen',$str_filename.'?op=delete_category&id='.$arr_category['id'],false,false,'Wirklich löschen?').'
							</td>
						</tr>
					';
				}
				$str_out .= '</table>';
				addpregnav('/'.$str_filename.'\?op=(edit_category|delete_category)&id=\d+/');
			}
			break;
		}
	case 'add_category':
	case 'edit_category':
		{
			$arr_data = array();
			if(is_null_or_empty($_REQUEST['id']) == false)
			{
				$int_id = (int)$_REQUEST['id'];
				$arr_data = db_get('SELECT * FROM weather_texts_categories WHERE id='.$int_id);
			}
			
			//Farbtags für Ausgabe in Form vorbereiten
			array_walk($arr_data,create_function('&$val','$val = str_replace("`","``",$val);'));
			
			//Form erstellen
			$arr_form = array();
			$arr_form['id']			= 'ID für die DB,hidden';			
			$arr_form['category']	= 'Name der Kategorie,focus';
			
			addnav('Abbrechen',$str_filename.'?op=categories');
			$str_out .= get_title('Eintrag hinzufügen/editieren');
			$str_out .= form_header($str_filename.'?op=save_category');
			$str_out .= generateform($arr_form,$arr_data);
			$str_out .= form_footer();
			break;
		}
	case 'delete_category':
		{
			setStatusMessage('Kategorie erfolgreich gelöscht');
			$int_id = (int)$_REQUEST['id'];
			//Kategorie löschen
			db_query('DELETE FROM weather_texts_categories WHERE id='.$int_id);
			
			//Vorhandene Kategorien auf Standard setzen.
			db_query('UPDATE weather_texts 
				SET category=
					IF(
						REPLACE(category,"|'.$int_id.'|","") = "",
						"|1|",
						REPLACE(category,"'.$int_id.'","")
					) 
					WHERE category LIKE "%'.$int_id.'%";');
			systemlog('Wettertext Kategorie Nr.'.$int_id.' wurde gelöscht!');
			redirect($str_filename.'?op=categories');
			break;
		}
	case 'save_category':
		{
			if(is_null_or_empty($_POST['id']) == false)
			{
				$int_id = (int)$_POST['id'];
				if($int_id > 0)
				{
					$arr_data = array();			
				
					$arr_data['category']	= addstripslashes($_POST['category']);
					
					db_update('weather_texts_categories',$arr_data,' id='.$int_id);
					setStatusMessage('Kategorie erfolgreich bearbeitet');
					redirect($str_filename.'?op=categories');
				}
				else 
				{
					setStatusMessage('Die ID war nicht gültig');
				}
			}
			else 
			{
				$arr_data = array();							
				
				$arr_data['category']	= addstripslashes($_POST['category']);
				
				db_insert('weather_texts_categories',$arr_data);
				
				setStatusMessage('Kategorie erfolgreich hinzugefügt');
				redirect($str_filename.'?op=categories');
			}
			break;
		}
}
output($str_out);
page_footer();
?>