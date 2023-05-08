<?php
/** @noinspection PhpUndefinedVariableInspection */
$gold = e_rand($session['user']['level']*10,$session['user']['level']*50);
$session['user']['gold']+=$gold;
output('`^Das Glück lächelt dich an. Du findest '.$gold.' Gold!`0');
$session['user']['specialinc'] = '';
?>
