<?php
if(!isset($session['santagift'])) {
    /** @noinspection PhpUndefinedVariableInspection */
    $int_santaclaus_post_len = mb_strlen(utf8_preg_replace('/\(.*\)/','',mb_strlen($commentary)));
	if($int_santaclaus_post_len>100) { 
		$session['allowednavs']=array();
		addnav('','forest.php?op=santagift');
		saveuser();
		$return ='/go forest.php?op=santagift';
	}
}
?>
