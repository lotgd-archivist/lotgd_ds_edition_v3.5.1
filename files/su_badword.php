<?php
require_once 'common.php';
$access_control->su_check(access_control::SU_RIGHT_GAMEOPTIONS,true);

$str_output = '';

page_header('Wortfilter.');
grotto_nav();
addnav('Liste aktualisieren','su_badword.php');

$output .= 'Hier kannst du Wörter festlegen, die das Spiel ausfiltert. Benutze ein * am Anfang oder am Ende ';
$output .= 'eines Worts, um Wortkombinationen mit dem Wort zu filtern (wildcard). Die Wörter werden nur gefiltert, wenn der ';;
$output .= 'Wortfilter in den Spieleinstellungen aktiviert ist.';;

$output .= '<form action="su_badword.php?op=add" method="POST">Wort hinzufügen: <input name="word"><input type="submit" class="button" value="Hinzufügen"></form>';
$output .= '<form action="su_badword.php?op=remove" method="POST">Wort entfernen: <input name="word"><input type="submit" class="button" value="Entfernen"></form>';
$output .= '<form action="su_badword.php?op=test" method="POST">Wort testen: <input name="word"><input type="submit" class="button" value="Test"></form>';

addnav('','su_badword.php?op=add');
addnav('','su_badword.php?op=remove');
addnav('','su_badword.php?op=test');
$sql = 'SELECT * FROM nastywords';
$result = db_query($sql);
$row = db_fetch_assoc($result);
$words = explode(' ',$row['words']);
reset($words);

if ($_GET['op']=='add')
{
    array_push($words,stripslashes($_POST['word']));
}
else if ($_GET['op']=='remove')
{
    unset($words[array_search(stripslashes($_POST['word']),$words)]);
}
else if ($_GET['op']=='test')
{
    $str_output = '`7Das Testergebnis lautet: `^'.soap($_POST['word']).'`7.  (Wenn der Wortfilter in den Spieleinstellungen deaktiviert ist, wird dieser Test nicht funtkionieren).`n`n';
}

sort($words);
$lastletter='';
foreach ($words as $key => $val)
{
    if (trim($val)=='')
    {
        unset($words[$key]);
    }
    else
    {
        if (mb_substr($val,0,1)!=$lastletter)
        {
            $lastletter = mb_substr($val,0,1);
            $str_output .= '`n`n`^`b' . mb_strtoupper($lastletter) . '`b`@`n';
        }
        $str_output .= $val.' ';
    }
}
if ($_GET['op']=='add' || $_GET['op']=='remove')
{
    $sql = 'DELETE FROM nastywords';
    db_query($sql);
    $sql = 'INSERT INTO nastywords VALUES ("' . db_real_escape_string(join(' ',$words)) . '")';
    db_query($sql);
}
if(mb_strlen($str_output)>0)
{
	output($str_output);
}
page_footer();
?>
