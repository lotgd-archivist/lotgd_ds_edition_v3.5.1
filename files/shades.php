<?php
/**
 * shades.php: Die Unterwelt - Hauport für die Toten.
 * @author LOGD-Core
 * @version DS-E V/2
*/

require_once('common.php');

if ($session['user']['imprisoned']>0) {
	redirect("prison.php");
}

page_header('Land der Schatten');
addcommentary();
checkday();

music_set('unterwelt');

if ($session['user']['alive']) {
	redirect('village.php');
}

/**
 * @DS alle Buffs werden abgearbeitet und ggf gelöscht.
 */
buff_process_death();

$str_output .= '`c`b`,D`Ai`4e Schatt`Ae`,n`0`b`c
`n`eD`)u`( w`Nandelst jetzt unter den Toten, du bist nur noch ein Schatten. Überall um dich herum sind die Seelen der in alten Schlachten und bei gelegentlichen Unfällen gefallenen Kämpfer. Jede trägt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben.`n`n
In der Stadt dürfte es jetzt etwa `&'.getgametime(true).'`N sein, aber hier herrscht die Ewigkeit und Zeit gibt es mehr als genug.`n`n';

// Asgarath - Ab sofort wird im Totenreich eine Statue der untoten Knappen angezeigt
$sql = 'SELECT disciples.name AS name,disciples.level AS level ,accounts.name AS master FROM disciples LEFT JOIN accounts ON accounts.acctid=disciples.master WHERE best_one=2 LIMIT 1';
$result = db_query($sql);
if (db_num_rows($result)>0) {
    $rowk = db_fetch_assoc($result);

    $str_output .='`NEine kleine verfallene Statue ehrt `q'.$rowk['name'].'`N, einen untoten Knappen der '.$rowk['level'].'. Stufe, der zusammen mit '.$rowk['master'].'`N eine Heldentat vollbrachte.`n`n';
}
$str_output .='`NDie verlorenen Seelen flüstern ihre Qualen und plagen deinen Geist mit ihrer Verzweifl`(u`)n`eg.`n`n`0';

output($str_output);

addnav('Das Totenreich');
addnav('Der Friedhof der Seelen','graveyard.php');
addnav('Der Friedhof (Oberwelt)','friedhof.php');
addnav('Halle der Geister','halle_der_geister.php');

//RUNEN MOD
//wenn man eine eiwazrune hat, kommt man wieder nach oben
if( item_count('tpl_id="r_eiwaz" AND owner='.$session['user']['acctid']) > 0 ){
	addnav('Runenkraft');
	addnav('Benutze eine Eiwaz-Rune','newday.php?resurrection=rune',false,false,false,false,'Willst du wirklich eine Eiwaz-Rune dafür verwenden, in die Welt der Lebenden zurückzukehren?');
}
//RUNEN END


if ($session['user']['acctid']==getsetting('hasegg',0)){
    addnav('Das goldene Ei');
	addnav('Benutze das goldene Ei','newday.php?resurrection=egg');
}

addnav('Sonstiges');
addnav('b?`^Drachenbücherei`0','library.php');
addnav('+?`4OOC-Bereich`0','ooc_area.php');

addnav('Einwohnerliste','list.php');
addnav('R?In Ruhmeshalle spuken','hof.php');
addnav('Zurück');
addnav('Neuigkeiten','news.php');

if ($access_control->su_check(access_control::SU_RIGHT_LIVE_DIE))
{
	addnav('Back to Life','superuser.php?op=iwilldie',false,false,false,false,'Willst du wirklich hinter dem Rücken von Ramius wieder in die Welt der Lebenden klettern?');
}

if ($access_control->su_check(access_control::SU_RIGHT_NEWDAY))
{
	addnav('Neuer Tag','superuser.php?op=newday',false,false,false,false,'Willst du wirklich einen neuen Tag beginnen?');
}

addnav('Logout');
addnav('#?Schlaf der Schlaflosen','login.php?op=logout',true);


page_footer();
?>