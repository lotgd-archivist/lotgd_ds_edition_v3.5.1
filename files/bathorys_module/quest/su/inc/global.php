<?php
//bathi
global $zahlreset, $efek, $bed,$zaehler,$ievents,$orte,$atm,$events,$items_tpl,$cls,$quests;

$op = $_GET['op'];
$sop = ($_GET['sop']=='new') ? 'edit' : $_GET['sop'];

if($sop=='edit' && ($op=='beding' || $op=='effekt' || $op=='quests'))
{
    $items_tpl_r = db_query("SELECT tpl_id,tpl_name FROM items_tpl ORDER BY tpl_id ASC");
    $items_tpl = array();
    $items_tplf = '';
    while($items_tpl_d = db_fetch_assoc($items_tpl_r)){
        $items_tpl[$items_tpl_d['tpl_id']] = $items_tpl_d['tpl_name'];
        $items_tplf .= ','.$items_tpl_d['tpl_id'].','.strip_appoencode(str_replace(array(',','`0'),'',$items_tpl_d['tpl_id'].' - '.$items_tpl_d['tpl_name']));
    }
}

if($sop=='edit' && ($op=='beding'))
{
    $cls_r = db_query("SELECT id,class_name FROM items_classes ORDER BY class_name ASC");
    $cls = array();
    $clsf = '';
    while($cls_d = db_fetch_assoc($cls_r)){
        $cls[$cls_d['id']] = $cls_d['class_name'];
        $clsf .= ','.$cls_d['id'].','.strip_appoencode(str_replace(array(',','`0'),'',$cls_d['class_name']));
    }
}

if($sop=='edit' && ($op=='events_interact'))
{
    $atm_r = db_query("SELECT creatureid,creaturename,creaturelevel FROM creatures ORDER BY creaturename ASC");
    $atm = array();
    $atmf = '';
    while($atm_d = db_fetch_assoc($atm_r)){
        $atm[$atm_d['creatureid']] = $atm_d['creaturename'];
        $atmf .= ','.$atm_d['creatureid'].','.strip_appoencode(str_replace(array(',','`0'),'',$atm_d['creaturename']).' (lvl: '.$atm_d['creaturelevel'].')');
    }
}

if(($op=='events_interact') || ($op=='beding'))
{
    $quests_r = db_query("SELECT id,name FROM quest_events_orte ORDER BY name ASC");
    $quests = array();
    $questsf = '';
    while($quests_d = db_fetch_assoc($quests_r)){
        $quests[$quests_d['id']] = $quests_d['name'];
        $questsf .= ','.$quests_d['id'].','.strip_appoencode(str_replace(array(',','`0'),'',$quests_d['name']));
    }
}

if($op=='quests' || $op=='events_interact' || $op=='effekt' || $op=='zahler')
{
    $orte_r = db_query("SELECT * FROM quest_orte WHERE activ=1 ORDER BY name ASC");
    $orte = array();
    $ortef = '';
    while($orte_d = db_fetch_assoc($orte_r)){
        $orte[$orte_d['id']] = $orte_d['name'];
        $ortef .= ','.$orte_d['id'].','.strip_appoencode($orte_d['name']);
    }
}

if($sop=='edit' && ($op=='beding' || $op=='effekt'))
{
    $zaehler_r = db_query("SELECT * FROM quest_zaehler WHERE activ=1 ORDER BY name ASC");
    $zaehlerf = '';
    $zaehler = array();
    while($zaehler_d = db_fetch_assoc($zaehler_r)){
        $zaehler[$zaehler_d['id']] = $zaehler_d['name'];
        $zaehlerf .= ','.$zaehler_d['id'].','.strip_appoencode($zaehler_d['name']);
    }
}

if($op=='quests' || $op=='events_interact')
{

    $efek_r = db_query("SELECT * FROM quest_effekte WHERE activ=1 ORDER BY name ASC");
    $efekf = '';
    $efek = array();
    while($efek_d = db_fetch_assoc($efek_r)){
        $efek[$efek_d['id']] = $efek_d['name'];
        $efekf .= ','.$efek_d['id'].','.strip_appoencode($efek_d['name']);
    }


    $bed_r = db_query("SELECT * FROM  quest_bedingung WHERE activ=1 ORDER BY name ASC");
    $bedf = '';
    $bed = array();
    while($bed_d = db_fetch_assoc($bed_r)){
        $bed[$bed_d['id']] = $bed_d['name'];
        $bedf .= ','.$bed_d['id'].','.strip_appoencode($bed_d['name']);
    }
}

$zahlreset = array('nie','sterben','heilen','newday','sieg');
$zahlresetf = ',0,nie,1,sterben,2,heilen,3,newday,4,sieg';

$e10 = '';
for($i=1;$i<=10;$i++){$e10.=','.$i.', '.$i.' ';}
$e18 = '';
for($i=1;$i<=16;$i++){$e18.=','.$i.', '.$i.' ';}
$e100 = '';
for($i=0;$i<=100;$i++){$e100.=','.$i.', '.$i.' ';}
$e100o0 = '';
for($i=1;$i<=100;$i++){$e100o0.=','.$i.', '.$i.' ';}

if($op=='beding')
{
    $e24 = ',0,Kein';
    for($i=1;$i<25;$i++){$e24.=','.$i.', '.($i).' ';}
    $e31 = ',0,Kein';
    for($i=1;$i<32;$i++){$e31.=','.$i.', '.($i).' ';}
    $e12 = ',0,Kein';
    for($i=1;$i<13;$i++){$e12.=','.$i.', '.($i).' ';}
    $weatherf = ',0,Kein';
    foreach(Weather::$weather as $k => $v) $weatherf .= ','.$k.','.str_replace(',',' ',$v['name']);
}
?>