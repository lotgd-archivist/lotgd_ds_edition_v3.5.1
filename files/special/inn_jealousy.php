<?php
/**
 * @desc Kleine Eifersuchtsszene in der Kneipe
 * @author Dragonslayer
 * @copyright Atrahor, DS V2.5
 */

page_header('Eine Eifersuchtsszene');

$str_backlink = 'inn.php';
$str_backtext = 'Zurück zur Kneipe';

/** @noinspection PhpUndefinedVariableInspection */
if($session['user']['marriedto'] == 4294967295)
{


	$str_sql = 'SELECT name FROM accounts WHERE marriedto = 4294967295 AND sex ='.$session['user']['sex'].' AND acctid != '.$session['user']['acctid'].' ORDER BY RAND() LIMIT 1';
	$db_result = db_query($str_sql);
	$str_opponent = db_fetch_assoc($db_result);
	$str_user_sex = $session['user']['sex'];
	$str_opponent_sex = !$str_user_sex;

	$str_output = '
	`tAls du gerade am Tresen der Schenke vorbei gehen möchtest, hörst du ein lautes Gezeter.`n
	Vor dir steht '.$str_opponent['name'].' `tund schaut dich grimmig an!`n
	`$"'.(($str_user_sex)?'Du billiges Flittchen':'Du schamloser Casanova').'! Was muss ich hören? DU willst mit
	'.(($str_user_sex)?'Seth':'Violet').' verheiratet sein? `bICH`b bin mit
	'.(($str_user_sex)?'Seth':'Violet').' verheiratet, also lass Deine Pfoten von '.(($str_user_sex)?'ihm':'ihr').'!"`n`n
	`&`bPATSCH !`b`n`n
	`tEhe du dich versiehst, hast du dir auch schon eine eingefangen.`n`nNachdem die Vögelchen wieder weg sind, schaust du dich wütend um, um dich gebührend
	zu revanchieren, aber '.(($str_opponent_sex)?'der eifersüchtige Bock':'die wütende Furie').' scheint schon weg zu sein.
	Du zuckst mit den Schultern und kümmerst dich nicht weiter darum...`n`n
	`uOb '.(($str_user_sex)?'Seth':'Violett').' wirklich ein doppeltes Spiel treibt?...Näää, `INIEEEEE!
	';

	$session['user']['hitpoints'] -= 1;

	if($session['user']['hitpoints']<1)
	{
		$session['user']['hitpoints'] = 1;
	}

}
else
{
       $str_output = 'Hast du noch mal Glück gehabt, das betrifft dich nämlich nciht!';
}

addnav($str_backtext,$str_backlink);
output($str_output);
?>