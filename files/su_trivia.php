<?php
/**
 * su_trivia.php: Editor für Maris' Trivia. Basierend auf su_.php von Alucard
 * @author talion <t@ssilo.de>
 * @version DS-E V/2.5
*/

require_once 'common.php';
require_once(LIB_PATH.'jslib.lib.php');

$access_control->su_check(access_control::SU_RIGHT_EDITORWORLD,true);


$str_out = '`c`&`bTrivia-Editor`b`n`n';
$str_self = 'su_trivia.php';

switch( $_GET['op'] ){
	case 'del':
		
		db_query('DELETE FROM trivia WHERE id='.(int)$_GET['id']);
		
		jslib_http_command('/mb Eintrag gelöscht!');	
		exit();
		
	break;
	
	case 'edit':

		if(isset($_GET['id'])) {
			$t = db_fetch_assoc(db_query('SELECT * FROM trivia WHERE id='.(int)$_GET['id']));
            $t['question'] = utf8_htmlspecialchars($t['question']);
			$t['answer'] = implode("\n",utf8_unserialize(($t['answer'])));
			$t['solution'] = implode("\n",utf8_unserialize(($t['solution'])));
		}
		else {
			$t = array();
		}
		
		addnav('Zurück', $str_self );
		$form = array( 	'Rätsel bearbeiten,title',
						'id' => 'ID,viewonly',
						'question' => 'Frage,text,255',
						'answer' => 'Antworten,textarea,50,10',
						'solution' => 'Lösungen,textarea,50,10',
						'correct' => 'Korrekt,int'
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
				
				$arr_answers = explode("\n",stripslashes($_POST['answer']));
				$arr_solutions = explode("\n",stripslashes($_POST['solution']));
								
				$sql .= '`trivia` SET `question`="'.$_POST['question'].'",`correct`='.(int)$_POST['correct'].',`answer`="'.db_real_escape_string(utf8_serialize($arr_answers)).'",`solution`="'.db_real_escape_string(utf8_serialize($arr_solutions)).'"';
				
				if(!empty($_GET['id'])) {
					$sql .= 'WHERE id='.(int)$_GET['id'];	
				}
												
				db_query($sql);
				
				$str_out .= 'Trivia gespeichert!`n';
			break;
						
		}

		$str_out .= JS::encapsulate(jslib_httpreq_init().'
						function del (id) {
							
							if(!confirm("Trivia wirklich löschen?")) {
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
			
		$str_out .= '<table><tr class="trhead"><td>ID</td><td>Frage</td><td>Aktionen</td></tr>';
				
		addpregnav('/'.$str_self.'\?op=edit&id=\d{1,}/');
		addpregnav('/'.$str_self.'\?op=del&id=\d{1,}/');
		
		$res = db_query('SELECT * FROM trivia');
		
		while($t = db_fetch_assoc($res)) {
			
			$str_out .= '<tr id="t'.$t['id'].'">
							<td>'.$t['id'].'</td>
							<td>'.$t['question'].'</td>
							<td>[ <a href="'.$str_self.'?op=edit&id='.$t['id'].'">Edit</a> ] 
								[ <a href="javascript:void(0);" id="del_'.$t['id'].'">Del</a> ]
								'.JS::event('#del_'.$t['id'].'','click','del('.$t['id'].');').'
							</td>
						</tr>';
			
		}
				
		$str_out .= '</table>';
	
	break;
}

page_header('Trivia-Editor');
grotto_nav();
addnav('Optionen');
addnav('Neu', $str_self.'?op=edit' );

output( $str_out );
page_footer();
?>
