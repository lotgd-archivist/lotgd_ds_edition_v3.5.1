<?php

function set_magic_quotes(&$vars)
{
	if (is_array($vars))
	{
		if(get_magic_quotes_gpc())
		{
			array_walk_recursive($vars,'addstripslashes');
		}
		else
		{
			array_walk_recursive($vars,'own_addslashes');
		}
	}
	else
	{
		if(get_magic_quotes_gpc())
		{
			$vars = stripslashes($vars);
		}
		$vars = addslashes($vars);
	}
}



function db_query($sql, $logquery=true, $bool_unbuffered = false)
{
	global $mysqli, $session,$BOOL_JS_HTTP_REQUEST,$REQUEST_URI;

    $r = $mysqli->query($sql);
	if(!$r) {
		if( $BOOL_JS_HTTP_REQUEST ){
			$str_msg = '`^SQL-ERROR!<br />Bitte schicke diese Meldung mit einer Fehlerbeschreibung in einer Anfrage an die Administratoren!<br />`%';
			$str_msg .= db_error(LINK);
			if( db_errno(LINK) == 1064 ){//bei syntaxerror
				$str_msg .= '<br />`^SQL-CODE:`%<br />';
				$str_msg .= $sql;
			}
			jslib_http_command('/mb '.$str_msg);
		}
		else{
			$str_msg = '<i>Adresse: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'</i>
						<pre>'.utf8_htmlentities($sql).'</pre>
						<b>'.db_errno(LINK).':</b> '.db_error(LINK);

			// Nur Systemlogeintrag vornehmen, wenn feststeht, dass nicht Systemlog den Fehler hervorruft
			if(!mb_strpos($sql,'syslog')) {
				systemlog('`&DB-Fehler: `^'.$str_msg, 0, $session['user']['acctid']);
			}

			echo('<div style="font-family:Helvetica; color:darkblue;">
				<h2 align="center" style="color:green;">Don\'t Panic!</h2>
				Soeben ist durch eine äußerst unwahrscheinliche Dimensionslücke weit draußen in den unerforschten
				Einöden eines total aus der Mode gekommenen Ausläufers des westlichen Spiralarms der Galaxis
				ein Datenbankfehler im Innenleben dieses Servers aufgetreten.<br>
				Bitte kopiere den untenstehenden Fehlertext und teile ihn der Administration per Anfrage mit!
				Du solltest auch beschreiben, was du unmittelbar davor getan / angeklickt hast.<br>
				Danke für dein Verständnis!<br>
				Hier kommt die Meldung:
				<p>'.$str_msg.'</p>
				Um weiterspielen zu können, sollte ein Klick auf den Zurück-Button deines Browsers ausreichen. Falls nicht,
				schließe das Browserfenster und rufe die Adresse neu auf. Schreibe dann von der Startseite aus eine Anfrage.</div>'
				);
				exit;
		}
	}
	return $r;
}

function db_get_all($sql,$assoc=false,$field = false)
{
	$ret = array();
	$res = db_query($sql);

	while ($row = db_fetch_assoc($res)) {
		if ($assoc) {
			$ret[$row[$assoc]] = $row;
		}
		else
		{
			if($field === false)
			{
				$ret[] = $row;
			}
			else
			{
				$ret[] = $row[$field];
			}
		}
	}

	return $ret;
}

function db_get($sql)
{
	$res = db_query($sql);

	if(!db_num_rows($res)) {
		return null;
	}

	return db_fetch_assoc($res);

}

function db_squeryf()
{
    $args = func_get_args();
    $sql = array_shift($args);

    $sql = vsprintf($sql, $args);
	return db_query($sql);
}

function db_insert_id($link=false)
{
	global $mysqli;
	return $mysqli->insert_id;
}

function db_error($link)
{
    global $mysqli;
    return $mysqli->error;
}

function db_errno($link)
{
    global $mysqli;
    return $mysqli->errno;
}

function db_result(mysqli_result$res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
}

function db_fetch_assoc(mysqli_result $result)
{
    return $result->fetch_assoc();
}

function db_fetch_row(mysqli_result $result)
{
    return $result->fetch_row();
}

function db_fetch_object(mysqli_result$result)
{
    return $result->fetch_object();
}

function db_fetch_array(mysqli_result $result)
{
    return $result->fetch_array();
}

function db_real_escape_string($string)
{
	global $mysqli;
	return $mysqli->real_escape_string($string);
}

//by bathi gegen sql injections in IN(...)
function db_intval_in_string($string)
{
	if($string != '0') 
     { 
          $temp = explode(',',$string); 
          $clean = array(0 => 0); 
          foreach($temp as $k => $v){ 
               $tempid = intval($v); 
               if($tempid > 0)$clean[] = $tempid; 
          } 
          $clean_str = implode(',',$clean); 
          return $clean_str;
     }
	 return 0;
}

function db_num_rows(mysqli_result $result)
{
	return $result->num_rows;
}

function db_affected_rows($link=false)
{
	global $mysqli;
	return $mysqli->affected_rows;
}


function db_free_result(mysqli_result $result)
{
	$result->free();
    return true;
}

/**
* @author talion
*/
function db_create_list(mysqli_result $result, $str_key = false, $bool_nokey = false)
{
	$arr_list = array();
	$mixed_first = false;

	while($row = db_fetch_assoc($result)) {

		if($bool_nokey && sizeof($row) == 1) {
			$mixed_first = reset($row);
		}

		if( false !== $str_key && isset($row[$str_key]) ) {
			if(!empty($mixed_first)) {
				$arr_list[ $row[$str_key] ] = $mixed_first;
			}
			else {
				$arr_list[ $row[$str_key] ] = $row;
			}
		}
		else {
			if(!empty($mixed_first)) {
				$arr_list[] = $mixed_first;
			}
			else {
				$arr_list[] = $row;
			}
		}
	}
	return($arr_list);
}

/**
 * @author talion
 */
function db_insert ($str_table, $arr_data, $int_amount=1) {

	$str_sql = 'INSERT INTO `'.$str_table.'` ';

	$str_fields = '             (';
	$str_values = ' VALUES      ';
	$str_tupel	= ' ( ';
	$str_data = '';

	foreach ($arr_data as $str_field => $data) {

		if(is_array($data)) {
			if(isset($data['sql']) && $data['sql'] === true) {
				$str_data = $data['value'];
			}
			else {
				$str_data = '"'.db_real_escape_string(utf8_serialize($data)).'"';
			}
		}
		else if(is_string($data)) {
			$str_data = '"'.db_real_escape_string(stripslashes($data)).'"';
		}
		else {
			$str_data = '"'.$data.'"';
		}
		$str_tupel .= $str_data.',';

		$str_fields .= '`'.$str_field.'`,';

	}

	$str_tupel = mb_substr($str_tupel,0,mb_strlen($str_tupel)-1).')';
	$str_fields = mb_substr($str_fields,0,mb_strlen($str_fields)-1).')';

	if($int_amount > 1) {
		$str_tupel .= "\n,";
		$str_tupel = str_repeat($str_tupel,$int_amount);
		$str_tupel = mb_substr($str_tupel,0,mb_strlen($str_tupel)-1);
	}

	$str_sql .= $str_fields."\n".$str_values."\n".$str_tupel;

	db_query($str_sql);

	if(!db_errno(LINK)) {
		return(true);
	}
	else {
		return(false);
	}
}

/**
 * @author dragonslayer
 */
function db_update ($str_table, $arr_data, $str_where)
{
	if(count($arr_data) == 0)
	{
		return false;
	}

	$str_sql = 'UPDATE `'.$str_table.'` ';
	$str_data = 'SET ';

	foreach ($arr_data as $str_field => $data)
	{
		if(is_array($data))
		{
			$str_data .= '`'.$str_field.'`="'.db_real_escape_string(utf8_serialize($data)).'",';
		}
		else
		{
			$str_data .= '`'.$str_field.'`="'.addstripslashes($data).'",';
		}
	}

	$str_data = mb_substr($str_data,0,mb_strlen($str_data)-1);

	$str_where = 'WHERE '.$str_where;

	$str_sql .= $str_data."\n".$str_where;

	db_query($str_sql);

	if(!db_errno(LINK))
	{
		return(true);
	}
	else
	{
		return(false);
	}
}

function str_create_search_string($str_input_string = '',$str_split_char='%', $str_remove_chars = '#[\s\W\d]#')
{
	if($str_input_string == '')
	{
		return $str_input_string;
	}

	$str_input_string = stripslashes($str_input_string);
	$str_input_string = utf8_preg_replace($str_remove_chars,'',$str_input_string);

    if(function_exists('utf8_str_split'))
	{
        $arr_temp = utf8_str_split($str_input_string, 1);
    }
    else
	{
        $arr_temp = utf8_preg_split('#(?<=.)(?=.)#s', $str_input_string);
    }
	$str_return = $str_split_char.db_real_escape_string(implode($str_split_char,$arr_temp)).$str_split_char;
	return $str_return;
}

function sql_error($sql)
{
	return 'SQL = <pre>$sql</pre>'.db_error(LINK);
}

function arrayToXML( $arr, $item='item', $root='root', $header=false ){
	$str ='';
	foreach($arr as $key => $val){
		$k_add = '';
		if( is_numeric($key) ){
			$k_add = 'id="'.$key.'"';
			$key = $item;
		}
		if( is_array($val) ){
			$val = arrayToXML($val,$item,'');
		}
		else{
			$val = "<![CDATA[".$val."]]>";
		}
		$str .= "<".$key." ".$k_add.">".$val."</".$key.">";
	}
	$str = ($header ? '<?xml version="1.0" encoding="UTF-8"?>' : '').(!empty($root)?"<".$root.">":'').$str.(!empty($root)?"</".$root.">":'');
	return $str;
}

?>