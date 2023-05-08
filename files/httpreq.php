<?php
$DONT_OVERWRITE_NAV 	= true;
$BOOL_JS_HTTP_REQUEST 	= true;
require_once('common.php');

$check_newday = true;

$command = false;

switch( $_GET['op'] ){
	//PLUMI Switch
	case 'switch_plu_mi':
		if( !is_array($session['user']['plu_mi']) ){
			$session['user']['plu_mi'] = array();
		}
		$session['user']['plu_mi'][ $_POST['field'] ] = $session['user']['plu_mi'][ $_POST['field'] ] ? 0 : 1;
		
		user_update(
			array
			(
				'plu_mi'=>$session['user']['plu_mi'],
			),
			$session['user']['acctid']
		);		
		
		session_write_close();
	break;

	// AJAX-Suche
	case 'search':

		$command = true;

		// Präfix für JS-Elemente
		$str_prefix = $_GET['prefix'];

		// Art der Suche
		$str_what = $_GET['what'];

		// Eingabe
		$str_search_in = stripslashes(utf8_encode($_POST['search']));
				
		// Suchstring erstellen
		$str_search = str_create_search_string($str_search_in);

		// Fallunterscheidung nach Art der Suche
		switch($str_what) {
			case 'account':

				$sql = 'SELECT acctid,name,login FROM accounts WHERE name LIKE "'.$str_search.'" ORDER BY (login="'.db_real_escape_string($str_search_in).'") DESC, name ASC';
				$res = db_query($sql);
				
				$int_found = db_num_rows($res);
				if($int_found == 0) {
					$str_back = '/exec MessageBox.show("Leider konnte kein Bürger mit diesem Namen gefunden werden.");';
				}
				else {
					$str_back = '/exec var sel = document.getElementById("'.$str_prefix.'search_sel");var o = null;';
					if ($int_found > 50) {
						$str_back .= 'MessageBox.show("Es wurden über 50 Bürger mit einem ähnlichen Namen gefunden. Nur die ersten 50 werden angezeigt.");';
					}					
					// Select-Liste erstellen
					$int_counter = 0;
					while($arr_a = db_fetch_assoc($res)) {
						if(++$int_counter > 50) break;
						$str_back .= "o = new Option('".addslashes(strip_appoencode($arr_a['name'],3))."',".$arr_a['acctid'].");sel.options[sel.options.length] = o;";
					}
					$str_back .= ''.$str_prefix.'search_switch(false);';

				}

				db_free_result($res);
			break;
		}

	break;

	case 'switch_bit':
		user_update(
			array
			(
				$_GET['bn']=>(int)$Char->setBit( $_GET['bit'], $Char->{$_GET['bn']} )
			),
			$session['user']['acctid']
		);			
		session_write_close();
	break;

	case 'collect':
		
		if( md5(getsetting('collect_special_section', '').getsetting('collect_special_lastklick', 0)) ==
			$_GET['collect'] ){
			$sections = getsetting('collect_special_rnd_sections', '');
			$sections = explode(',', $sections);
			savesetting('collect_special_lastklick', time());
			savesetting('collect_special_section',   $sections[array_rand($sections)]);
			db_query('UPDATE account_stats SET collect_special=collect_special+1 WHERE acctid='.((int)$session['user']['acctid']));
			$str_back = getsetting('collect_special_success_msg','Juhu! Du schnappst es dir.');
		}
		else{
			$str_back = 'Da war wohl jemand schneller als du!';
		}
		$command = true;
		$str_back = '/mb '.$str_back;
	break;
	
	case 'su_jump':
		
		//Recht überprüfen
		if($access_control->su_check(access_control::SU_RIGHT_QUICKNAV) == false)
		{
			jslib_http_text_output('error',JSLIB_HTTP_TEXT);
		}
		
		$arr_path = explode("?",$_GET['su_jump_file']);
		
		$arr_path_info = pathinfo($arr_path[0]);
		$str_file = ($arr_path_info['dirname'] != '.'?$arr_path_info['dirname'].'/':'').$arr_path_info['basename'];
		if(file_exists($str_file) == true)
		{
			$str_file .= ($arr_path[1] != ''?'?'.$arr_path[1]:'');
			$command = true;
			addnav('',$str_file);
			$str_back = '/go '.$str_file;
			saveuser();
		}
		else 
		{
			jslib_http_text_output('error',JSLIB_HTTP_TEXT);
		}
		break;
}


if( $command || empty($str_back) )
{
	jslib_http_command($str_back);
}
else
{
	echo $str_back;
}

exit;
?>