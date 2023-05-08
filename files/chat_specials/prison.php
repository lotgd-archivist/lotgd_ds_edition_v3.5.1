<?php
/**
 * Das Special lässt den User an einem Spiel um seine Freiheit teilnehmen.
 */
/** @noinspection PhpUndefinedVariableInspection */
if (mt_rand(1,500) == 423 && $session['user']['imprisoned']>0)
{
	$session['allowednavs']=array();
	addnav('','prison.php?op=coin_game');
	saveuser();
	$return = '/go prison.php?op=coin_game';
}
?>