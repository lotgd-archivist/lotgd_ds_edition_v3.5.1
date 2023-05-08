<?php
/**
 * @author bathory
*/

require_once 'common.php';
require_once(LIB_PATH.'jslib.lib.php');
$access_control->su_check(access_control::SU_RIGHT_DEV,true);
$str_out = '`c`&`bRP-Chat-Emotes-Editor`b`n`n';
$str_self = 'su_rpchateditor.php';
switch( $_GET['op'] ){
	case 'del':
		db_query('DELETE FROM commentary_emotes WHERE id='.(int)$_GET['id']);
		jslib_http_command('/mb Eintrag gelöscht!');
		exit();
	break;
	case 'edit':
		if(isset($_GET['id'])) {
			$t = db_fetch_assoc(db_query('SELECT * FROM commentary_emotes WHERE id='.(int)$_GET['id']));
		}
		else {
			$t = array();
		}
		addnav('Zurück', $str_self );
        $rights = '';
        foreach(access_control::$ARR_SURIGHTS as $k => $v){
            if(isset($v['desc']))$rights .= ','.$k.','.$v['desc'];
        }
		$form = array( 	'RP-Chat-Emote,title',
						'id' => 'ID,viewonly',
						'regex' => 'RegEx,text,255',
                        'parse' => 'Parse,textarea,50,10',
                        'right' => "SU-Recht,select,0,Keines".$rights,
                        'lgt' => 'Länge,int',
                        'must' => 'Bedingung,text,255',
                        'name' => 'Namensfeld,text,255',
                        'issa' => 'SA?,select,1,Ja,0,Nein',
                        'type' => 'Typ,text,255',
                        'active' => 'Aktiv?,select,1,Ja,0,Nein',
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
				$sql .= ' commentary_emotes
				    SET
				        regex="'.db_real_escape_string($_POST['regex']).'",
				        parse="'.db_real_escape_string($_POST['parse']).'",
				        `right`="'.intval($_POST['right']).'",
				        lgt="'.intval($_POST['lgt']).'",
				        must="'.db_real_escape_string($_POST['must']).'",
				        name="'.db_real_escape_string($_POST['name']).'",
				        issa="'.intval($_POST['issa']).'",
				        active="'.intval($_POST['active']).'",
				        type="'.db_real_escape_string($_POST['type']).'"
				        ';
				if(!empty($_GET['id'])) {
					$sql .= 'WHERE id='.intval($_GET['id']);
				}
				db_query($sql);
				$str_out .= 'RP-Chat-Emote gespeichert!`n';
			break;
						
		}
		$str_out .= JS::encapsulate(jslib_httpreq_init().'
						function del (id) {
							if(!confirm("RP-Chat-Emote wirklich löschen?")) {
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
		$str_out .= '<table><tr class="trhead">
                        <td>ID</td>
                        <td>RegEx</td>
                        <td>Parse</td>
                        <td>SU-Recht</td>
                        <td>Länge</td>
                        <td>Bedingung</td>
                        <td>Namensfeld</td>
                        <td>SA?</td>
                        <td>Typ</td>
                        <td>Aktiv?</td>
                        <td>Aktionen</td>
                        </tr>';
		addpregnav('/'.$str_self.'\?op=edit&id=\d{1,}/');
		addpregnav('/'.$str_self.'\?op=del&id=\d{1,}/');
		$res = db_query('SELECT * FROM commentary_emotes');
		while($t = db_fetch_assoc($res)) {
			$str_out .= '<tr id="t'.$t['id'].'">
							<td>'.$t['id'].'</td>
							<td>'.$t['regex'].'</td>
							<td>'.$t['parse'].'</td>
							<td>'.access_control::$ARR_SURIGHTS[$t['right']]['desc'].'</td>
							<td>'.$t['lgt'].'</td>
							<td>'.$t['must'].'</td>
							<td>'.$t['name'].'</td>
							<td>'.$t['issa'].'</td>
							<td>'.$t['type'].'</td>
							<td>'.($t['active'] ? '`@Ja`0' : '`$Nein`0').'</td>
							<td>[ <a href="'.$str_self.'?op=edit&id='.$t['id'].'">Edit</a> ]
								[ <a href="javascript:void(0);" id="del_'.$t['id'].'">Del</a> ]
								'.JS::event('#del_'.$t['id'].'','click','del('.$t['id'].');').'
							</td>
						</tr>';
		}
		$str_out .= '</table>';
	break;
}
page_header('RP-Chat-Emotes-Editor');
grotto_nav();
addnav('Optionen');
addnav('Neu', $str_self.'?op=edit' );
//addnav('Reload', $str_self );
output( $str_out );
page_footer();
?>
