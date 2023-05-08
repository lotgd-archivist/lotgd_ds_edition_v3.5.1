<?php
/**
 * @author bathory
*/

require_once 'common.php';
require_once(LIB_PATH.'jslib.lib.php');
$access_control->su_check(access_control::SU_RIGHT_EDITORWORLD,true);
$str_out = '`c`&`bRP-Welten-Editor`b`n`n';
$str_self = 'su_rpworldeditor.php';

switch( $_GET['op'] ){
	case 'del':
		db_query('DELETE FROM rp_worlds WHERE id='.(int)$_GET['id']);
		jslib_http_command('/mb Eintrag gelöscht!');
		exit();
	break;
	case 'edit':
		if(isset($_GET['id'])) {
			$t = db_fetch_assoc(db_query('SELECT * FROM rp_worlds WHERE id='.(int)$_GET['id']));
		}
		else {
			$t = array();
		}
		addnav('Zurück', $str_self );
		$form = array( 	'RP-Welt,title',
						'id' => 'ID,viewonly',
						'name' => 'Name,text,255',
						'description' => 'Beschreibung,textarea,50,10',
                        'return_name' => 'Return-Name,text,255',
                        'return' => 'Return-URL,text,255',
						);
		$sl = $str_self.'?op=save&id='.$_GET['id'];
		addnav('',$sl);
        output( $str_out );
        rawoutput('<form action="'.$sl.'" method="POST">'.generateform( $form, $t ).'</form>');
		$str_out = '';
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
				$sql .= ' rp_worlds
				    SET
				        name="'.db_real_escape_string($_POST['name']).'",
				        description="'.db_real_escape_string($_POST['description']).'",
				        return_name="'.db_real_escape_string($_POST['return_name']).'",
				        `return`="'.db_real_escape_string($_POST['return']).'"
				        ';
				if(!empty($_GET['id'])) {
					$sql .= 'WHERE id='.intval($_GET['id']);
				}
				db_query($sql);
				$str_out .= 'RP-Welt gespeichert!`n';
			break;
						
		}
		$str_out .= JS::encapsulate(jslib_httpreq_init().'
						function del (id) {
							if(!confirm("RP-Welt wirklich löschen?")) {
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
		$str_out .= '<table><tr class="trhead"><td>ID</td><td>Name</td><td>Return-Name</td><td>Return-URL</td><td>Aktionen</td></tr>';
		addpregnav('/'.$str_self.'\?op=edit&id=\d{1,}/');
		addpregnav('/'.$str_self.'\?op=del&id=\d{1,}/');
		$res = db_query('SELECT * FROM rp_worlds');
		while($t = db_fetch_assoc($res)) {
			$str_out .= '<tr id="t'.$t['id'].'">
							<td>'.$t['id'].'</td>
							<td>'.$t['name'].'</td>
							<td>'.$t['return_name'].'</td>
							<td>'.$t['return'].'</td>
							<td>[ <a href="'.$str_self.'?op=edit&id='.$t['id'].'">Edit</a> ]
								[ <a href="javascript:void(0);" id="del_'.$t['id'].'">Del</a> ]
								'.JS::event('#del_'.$t['id'].'','click','del('.$t['id'].');').'
							</td>
						</tr>';
		}
		$str_out .= '</table>';
	break;
}
page_header('RP-Welten-Editor');
grotto_nav();
addnav('Optionen');
addnav('Neu', $str_self.'?op=edit' );
output( $str_out );
page_footer();
?>
