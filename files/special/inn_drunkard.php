<?php
/**
 * @desc Prost
 * @longdesc Nochmal prost
 * @author Dragonslayer
 * @copyright Atrahor, DS V2.5
 */


page_header('Prost!');

$str_backlink = 'inn.php';
$str_backtext = 'Lecker..';

$str_output = '
`tAls du gerade am Tresen der Schenke vorbei gehen möchtest, ertönt lautes Jubelgeschrei!
`n`$Der '.e_rand(1,3).'.000. Kunde heute! Das muss gefeiert werden!
`n`tEhe du dich versiehst, steht Cedrick auch schon vor dir und drückt dir ein schäumendes Ale in die Hand!
`nDa alle um dich herum auch schon anfangen 
`$TRINK, TRINK, TRINK `tzu rufen, bleibt dir wohl auch gar nichts weiter übrig, als das Freibier zu trinken... Welch schreckliches Los...
`n`n`$Prost.`t
';

/** @noinspection PhpUndefinedVariableInspection */
$session['user']['drunkenness']+=20;
$session['bufflist']['101'] = array("name"=>"`#Rausch","rounds"=>10,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
addnav($str_backtext,$str_backlink);
output($str_output);
?>