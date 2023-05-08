<?php
/** @noinspection PhpUndefinedVariableInspection */
if ($session['user']['drunkenness']>60)
{
    /** @noinspection PhpUndefinedVariableInspection */
    $int_max_chance = ceil(1300 / (mb_strlen($commentary) + 1));
	$int_max_chance = max($int_max_chance,4);
				
	if(e_rand(1,$int_max_chance)==1) {
		$session['allowednavs']=array();
		addnav('','fireshrine.php');
		saveuser();
		$return = '/go fireshrine.php';
	}
}
?>
