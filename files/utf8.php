<?php
error_reporting (E_ALL ^ E_NOTICE);

header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

ignore_user_abort(true);

function utf8_htmlentities($text, $quote = ENT_QUOTES, $charset = 'UTF-8', $double_encode = false) {
  return htmlentities($text, $quote, $charset, $double_encode);
}

function utf8_html_entity_decode($text, $quote = ENT_QUOTES, $charset = 'UTF-8') {
  return html_entity_decode($text, $quote, $charset);
}

function utf8_htmlspecialsimple($text)
{
    return str_replace(array('<','>'),array('&lt;','&gt;'),$text);
}

function utf8_htmlspecialchars($text, $quote = ENT_QUOTES, $charset = 'UTF-8', $double_encode = false) {
  return htmlspecialchars($text, $quote, $charset, $double_encode);
}

function utf8_preg_split($pattern , $subject , $limit = -1 , $flags = 0){
    if(is_array($pattern)){
        $pattern = array_map(function($p){ return $p.'u';}, $pattern);
    }else{
        $pattern .= 'u';
    }
	return preg_split($pattern, $subject , $limit, $flags);
}

function utf8_preg_replace($pattern ,$replacement , $subject , $limit = -1, &$count = null){
    if(is_array($pattern)){
        $pattern = array_map(function($p){ return $p.'u';}, $pattern);
    }else{
        $pattern .= 'u';
    }
	return preg_replace($pattern ,$replacement , $subject , $limit, $count);
}

function utf8_preg_replace_callback($pattern, $callback , $subject , $limit = -1, &$count = null){
    if(is_array($pattern)){
        $pattern = array_map(function($p){ return $p.'u';}, $pattern);
    }else{
        $pattern .= 'u';
    }
	return preg_replace_callback($pattern, $callback , $subject , $limit, $count);
}

function utf8_preg_quote($str ,$delimiter = '/'){
	return preg_quote($str ,$delimiter);	
}
function utf8_preg_match($pattern , $subject , &$matches = null , $flags = 0 , $offset = 0){
    if(is_array($pattern)){
        $pattern = array_map(function($p){ return $p.'u';}, $pattern);
    }else{
        $pattern .= 'u';
    }
	return preg_match($pattern, $subject , $matches , $flags , $offset);
}

function utf8_preg_match_all($pattern , $subject , &$matches = null , $flags =  PREG_PATTERN_ORDER , $offset = 0){
    if(is_array($pattern)){
        $pattern = array_map(function($p){ return $p.'u';}, $pattern);
    }else{
        $pattern .= 'u';
    }
	return preg_match_all($pattern, $subject , $matches , $flags , $offset);
}

function utf8_byte_offset_to_unit($string, $boff) {
    return mb_strlen(substr($string, 0, $boff));
}

function utf8_ord($c) {
    $h = ord($c{0});
        if ($h <= 0x7F) {
            return $h;
        } else if ($h < 0xC2) {
            return false;
        } else if ($h <= 0xDF) {
            return ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
        } else if ($h <= 0xEF) {
            return ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6
                                     | (ord($c{2}) & 0x3F);
        } else if ($h <= 0xF4) {
            return ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12
                                     | (ord($c{2}) & 0x3F) << 6
                                     | (ord($c{3}) & 0x3F);
        } else {
            return false;
        }
}

function utf8_str_split($str, $l = 0) {
    if ($l > 0) {
        $ret = array();
        $len = mb_strlen($str);
        for ($i = 0; $i < $len; $i += $l) {
            $ret[] = mb_substr($str, $i, $l);
        }
        return $ret;
    }
    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
}

function utf8_strcasecmp($str1, $str2) {
    return strcmp(mb_strtoupper($str1), mb_strtoupper($str2));
}

function utf8_strcspn($str, $mask, $start = null, $length = null) {
	if ( empty($mask) || strlen($mask) == 0 ) {
		return null;
	}
	$mask = preg_replace('!([\\\\\\-\\]\\[/^])!','\\\${1}',$mask);
	if ( $start !== null || $length !== null ) {
		$str = mb_substr($str, $start, $length);
	}
	preg_match('/^[^'.$mask.']+/u',$str, $matches);

	if ( isset($matches[0]) ) {
		return mb_strlen($matches[0]);
	}
	return 0;
}

function utf8_str_pad($input, $length, $padStr = ' ', $type = STR_PAD_RIGHT) {

    $inputLen = mb_strlen($input);
    if ($length <= $inputLen) {
        return $input;
    }

    $padStrLen = mb_strlen($padStr);
    $padLen = $length - $inputLen;

    if ($type == STR_PAD_RIGHT) {
        $repeatTimes = ceil($padLen / $padStrLen);
        return mb_substr($input . str_repeat($padStr, $repeatTimes), 0, $length);
    }

    if ($type == STR_PAD_LEFT) {
        $repeatTimes = ceil($padLen / $padStrLen);
        return mb_substr(str_repeat($padStr, $repeatTimes), 0, floor($padLen)) . $input;
    }

    if ($type == STR_PAD_BOTH) {

        $padLen/= 2;
        $padAmountLeft = floor($padLen);
        $padAmountRight = ceil($padLen);
        $repeatTimesLeft = ceil($padAmountLeft / $padStrLen);
        $repeatTimesRight = ceil($padAmountRight / $padStrLen);

        $paddingLeft = mb_substr(str_repeat($padStr, $repeatTimesLeft), 0, $padAmountLeft);
        $paddingRight = mb_substr(str_repeat($padStr, $repeatTimesRight), 0, $padAmountLeft);
        return $paddingLeft . $input . $paddingRight;
    }
    return $input;
}

function utf8_strrev($str){
	preg_match_all('/./us', $str, $ar);
	return join('',array_reverse($ar[0]));	
}

function utf8_ucfirst($str){
    switch ( mb_strlen($str) ) {
        case 0:
            return '';
        break;
        case 1:
            return mb_strtoupper($str);
        break;
        default:
            preg_match('/^(.{1})(.*)$/us', $str, $matches);
            return mb_strtoupper($matches[1]).$matches[2];
        break;
    }
}

function utf8_substr_replace($str, $repl, $start , $length = null ) {
    preg_match_all('/./us', $str, $ar);
    preg_match_all('/./us', $repl, $rar);
    if( $length === null ) {
        $length = mb_strlen($str);
    }
    array_splice( $ar[0], $start, $length, $rar[0] );
    return join('',$ar[0]);
}

function utf8_ucwords($str) {
    $pattern = '/(^|([\x0c\x09\x0b\x0a\x0d\x20]+))([^\x0c\x09\x0b\x0a\x0d\x20]{1})[^\x0c\x09\x0b\x0a\x0d\x20]*/u';
    return preg_replace_callback($pattern, 'utf8_ucwords_callback',$str);
}

function utf8_ucwords_callback($matches) {
    $leadingws = $matches[2];
    $ucfirst = mb_strtoupper($matches[3]);
    $ucword = utf8_substr_replace(ltrim($matches[0]),$ucfirst,0,1);
    return $leadingws . $ucword;
}

function utf8_wordwrap($string, $width=75, $break="\n", $cut = false) {
    if (!$cut) {
        $regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.',}\b#U';
    } else {
        $regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.'}#';
    }
    $string_length = mb_strlen($string);
    $cut_length = ceil($string_length / $width);
    $i = 1;
    $return = '';
    while ($i < $cut_length) {
        preg_match($regexp, $string,$matches);
        $new_string = $matches[0];
        $return .= $new_string.$break;
        $string = substr($string, strlen($new_string));
        $i++;
    }
    return $return.$string;
}

function utf8_levenshtein($str1, $str2,$int_cost_ins=1,$int_cost_rep=1,$int_cost_del=1)
{
    $str1 = trim( mb_convert_encoding($str1,'ISO-8859-15'));
	$str2 = trim( mb_convert_encoding($str2,'ISO-8859-15'));
	return levenshtein($str1, $str2,$int_cost_ins,$int_cost_rep,$int_cost_del);
}

function utf8_encode_items(&$item, $key)
{
    $item = utf8_encode($item);
}

function utf8_setcookie($name, $value, $expire = 0, $path = '/') {
    setcookie ($name, $value, $expire, $path, $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']), true);
}

function utf8_unserialize($serial_str) {
    $serial_str = trim($serial_str);

	if($serial_str == null || $serial_str == '' || $serial_str == 'a:0:{}' || strlen($serial_str) == 0)
	{
        return array();
	}

    $arr = json_decode($serial_str,true);

    if(	$arr === false || !is_array($arr) )
    {
        return array();
    }
    else
    {
        return $arr;
    }
}

function utf8_serialize($arr) {
    return json_encode($arr,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_FORCE_OBJECT);
}

function utf8_eval($str_code) {

    $s = array( ' htmlentities',
                ' html_entity_decode',
                ' htmlspecialchars',
                ' preg_split',
                ' preg_replace',
                ' preg_replace_callback',
                ' preg_quote',
                ' preg_match',
                ' preg_match_all',
                ' ord',
                ' str_split',
                ' strcasecmp',
                ' strcspn',
                ' str_pad',
                ' strrev',
                ' ucfirst',
                ' substr_replace',
                ' ucwords',
                ' wordwrap',
                ' levenshtein',
                ' unserialize',
                ' serialize',
                ' split',
                ' strcut',
                ' strimwidth',
                ' stripos',
                ' stristr',
                ' strlen',
                ' strpos',
                ' strrchr',
                ' strrichr',
                ' strripos',
                ' strrpos',
                ' strstr',
                ' strtolower',
                ' strtoupper',
                ' strwidth',
                ' substitute_character',
                ' substr_count',
                ' substr'
            );
    $r = array( ' utf8_htmlentities',
                ' utf8_html_entity_decode',
                ' utf8_htmlspecialchars',
                ' utf8_preg_split',
                ' utf8_preg_replace',
                ' utf8_preg_replace_callback',
                ' utf8_preg_quote',
                ' utf8_preg_match',
                ' utf8_preg_match_all',
                ' utf8_ord',
                ' utf8_str_split',
                ' utf8_strcasecmp',
                ' utf8_strcspn',
                ' utf8_str_pad',
                ' utf8_strrev',
                ' utf8_ucfirst',
                ' utf8_substr_replace',
                ' utf8_ucwords',
                ' utf8_wordwrap',
                ' utf8_levenshtein',
                ' utf8_unserialize',
                ' utf8_serialize',
                ' mb_split',
                ' mb_strcut',
                ' mb_strimwidth',
                ' mb_stripos',
                ' mb_stristr',
                ' mb_strlen',
                ' mb_strpos',
                ' mb_strrchr',
                ' mb_strrichr',
                ' mb_strripos',
                ' mb_strrpos',
                ' mb_strstr',
                ' mb_strtolower',
                ' mb_strtoupper',
                ' mb_strwidth',
                ' mb_substitute_character',
                ' mb_substr_count',
                ' mb_substr'
            );

    $str_code = str_replace($s,$r,$str_code);

    return $str_code;
}

//utf8 ende

?>