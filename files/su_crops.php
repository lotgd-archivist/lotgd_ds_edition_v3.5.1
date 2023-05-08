<?php
/**
 * su_trivia.php: Editor für Pflanzen. Basierend auf su_.php von Alucard und diversen Editoren von Talion
 * @author maris <maraxxus@gmx..de>
 * @version DS-E V/2.5
*/

require_once 'common.php';
require_once(LIB_PATH.'jslib.lib.php');
$access_control->su_check(access_control::SU_RIGHT_EDITORWORLD,true);

$str_out = '`c`&`bGartenpflanzen-Editor`b`n`n';
$str_self = basename(__FILE__);

switch( $_GET['op'] ){
	case 'del':

		db_query('DELETE FROM crops_tpl WHERE id='.(int)$_GET['id']);
		db_query('DELETE FROM crops WHERE sort='.(int)$_GET['id']);

		jslib_http_command('/mb Pflanzenart ausgerottet!');
		exit();

	break;

	case 'edit':

		if(isset($_GET['id'])) {
			$t = db_fetch_assoc(db_query('SELECT * FROM crops_tpl WHERE id='.(int)$_GET['id']));
			$t['size'] = implode("\n",utf8_unserialize(($t['size'])));
			$t['stage'] = implode("\n",utf8_unserialize(($t['stage'])));
			$t['fruit'] = implode("\n",utf8_unserialize(($t['fruit'])));
			$t['assert'] = implode("\n",utf8_unserialize(($t['assert'])));
		}
		else {
			$t = array();
		}

		addnav('Zurück', $str_self );
		$form = array( 	'Pflanzenart bearbeiten,title',
						'id' => 'ID,viewonly',
						'name' => 'Artenname,text,100',
						'stage' => 'Wachstumsstadien,textarea,20,7|?Bezeichnungen mit neuer Zeile trennen',
						'size' => 'Platzbedarf,textarea,20,7|?Bezeichnungen mit neuer Zeile trennen. Eingabe x:axb . x=> Alter in Tagen; a,b => Breite u. Höhe',
						'fruit' => 'Ertrag,textarea,20,3|?Bezeichnungen mit neuer Zeile trennen, Form -> Ben. Stufe : tpl_name : min_Ertrag : max_Ertrag : Zeitraum zwischen 2 Ernten (in Tagen) : zerstört die Pflanze (0 oder 1)',
						'assert' => 'Aggressivität,textarea,10,7|?Negativeinfluss auf Pflanzen anderer Arten. In Prozent, darf höher als 100 sein. Eingabe zeilenweise für entsprechendes Wachstumsstadium',
						'sensibility' => 'Empfindlichkeit,int|?In Prozent, darf höher als 100 oder negativ sein',
						'lifespan' => 'Lebensdauer,int|?Stirbt auf jeden Fall nach x Tagen',
						'sprout' => 'Keimungsrate,int|?In Prozent, sollte niedriger als 100 sein',
						'pest' => 'Unkraut,bool|?1 = Pflanze wird als Unkraut gewertet - sonst 0',
						'path' => 'Bilderpfad|?Relativer Pfadname zu den jpegs. Bilder hochladen nicht vergessen!'
						);

		$sl = $str_self.'?op=save&id='.$_GET['id'];
		addnav('',$sl);
		$str_out .= '<form action="'.$sl.'" method="POST">'.generateform( $form, $t ).'</form>';

	break;

	default:
		switch( $_GET['op'] ){
			case 'save':

				if(!empty($_GET['id'])) {
					$sql = 'UPDATE ';
				}
				else {
					$sql = 'INSERT INTO ';
				}

				$arr_size = explode("\n",stripslashes($_POST['size']));
				$arr_stage = explode("\n",stripslashes($_POST['stage']));
				$arr_fruit = explode("\n",stripslashes($_POST['fruit']));
				$arr_assert = explode("\n",stripslashes($_POST['assert']));

				$sql .= '`crops_tpl` SET `name`="'.$_POST['name'].'",`path`="'.$_POST['path'].'",`assert`="'.db_real_escape_string(utf8_serialize($arr_assert)).'",`sensibility`='.(int)$_POST['sensibility'].',`lifespan`='.(int)$_POST['lifespan'].',`pest`='.(int)$_POST['pest'].',`sprout`='.(int)$_POST['sprout'].',`size`="'.db_real_escape_string(utf8_serialize($arr_size)).'",`stage`="'.db_real_escape_string(utf8_serialize($arr_stage)).'",`fruit`="'.db_real_escape_string(utf8_serialize($arr_fruit)).'"';

				if(!empty($_GET['id'])) {
					$sql .= 'WHERE id='.(int)$_GET['id'];
				}

				db_query($sql);

				$str_out .= 'Pflanzenart gespeichert!`n';
			break;

		}

		$str_out .= JS::encapsulate(jslib_httpreq_init().'
						function del (id) {

							if(!confirm("Wirklich löschen? Alle Pflanzen dieser Art gehen verloren!")) {
								return;
							}

							g_req.send( "'.$str_self.'?op=del&id="+id,
															function (req) {
																document.getElementById("t"+id).style.display = "none";
																LOTGD.parseCommand(LOTGD.getCommandFromRequest(req));
															},
															function () {alert("Fehler bei Ausführung des Befehls!");},
															null,
															null
													);
						}
						');

		$str_out .= '<table><tr class="trhead"><td>ID</td><td>Pflanzenart</td><td>Unkraut</td><td>Empfindlichkeit</td><td>Lebensdauer</td><td>Keimungsrate</td></tr>';

		addpregnav('/'.$str_self.'\?op=(edit|del)&id=\d+/');

		$res = db_query('SELECT * FROM crops_tpl');

		while($t = db_fetch_assoc($res)) {
			$str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');

			$str_out .= '<tr class="'.$str_trclass.'" id="t'.$t['id'].'">
							<td>'.$t['id'].'</td>
							<td>'.$t['name'].'</td>
							<td>'.($t['pest'] ? 'ja' : 'nein').'</td>
							<td>'.$t['sensibility'].'</td>
							<td>'.$t['lifespan'].'</td>
							<td>'.$t['sprout'].'</td>
							<td nowrap="nowrap">[ <a href="'.$str_self.'?op=edit&id='.$t['id'].'">Edit</a> ]
								[ <a href="javascript:void(0);" id="del_'.$t['id'].'">Del</a> ]
								'.JS::event('#del_'.$t['id'].'','click','del('.$t['id'].');').'
							</td>
						</tr>';

		}

		$str_out .= '</table>';

	break;
}

page_header('Pflanzen-Editor');
grotto_nav();
addnav('Optionen');
addnav('Neue Art', $str_self.'?op=edit' );

output( $str_out );
page_footer();
?>
