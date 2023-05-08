<?php
//bathi
/** @noinspection PhpUndefinedVariableInspection */
$access_control->su_check(access_control::SU_RIGHT_EDITORWORLD,true);

page_header("Quest Editor");

grotto_nav();

if ($access_control->su_check(access_control::SU_RIGHT_EDITORWORLD))
{

    if(!isset($_GET['op']))$_GET['op'] = 'quests';
    addnav('Quests');
    addnav('Quests',''.$filename.'op=quests');
    addnav('Neuer Quest',''.$filename.'op=quests&sop=new');

    addnav('Interaktionen');
    addnav('Interaktionen Übersicht',''.$filename.'op=events_interact');
    addnav('Neue Interaktion',''.$filename.'op=events_interact&sop=new');

    addnav('Orte');
    addnav('Orte Übersicht',''.$filename.'op=orte');
    addnav('Neuer Ort',''.$filename.'op=orte&sop=new');

    addnav('Effekte');
    addnav('Effekte Übersicht',''.$filename.'op=effekt');
    addnav('Neuer Effekt',''.$filename.'op=effekt&sop=new');

    addnav('Bedingungen');
    addnav('Bedingung Übersicht',''.$filename.'op=beding');
    addnav('Neue Bedingung',''.$filename.'op=beding&sop=new');

    addnav('Zählervariabeln');
    addnav('Zähler Übersicht',''.$filename.'op=zahler');
    addnav('Neuer Zähler',''.$filename.'op=zahler&sop=new');

   require_once('su/'.$_GET['op'].'.php');
}

page_footer();
?>