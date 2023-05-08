<?php
//by bathory
require_once('common.php');
require_once(LIB_PATH.'profession.lib.php');
require_once(LIB_PATH.'disciples.lib.php');
require_once(LIB_PATH.'house.lib.php');
require_once(LIB_PATH.'dg_funcs.lib.php');
require_once('dg_output.php');
require_once(LIB_PATH.'runes.lib.php');
$BOOL_JSLIB_PLU_MI = true;
CSteckbrief::$arr_ausblendbar = array('info','male','aufzeichnungen','news','guestbook','ooc','rp','multi','stammbaum');
$CSteckbrief = new CSteckbrief((int)$_GET['id'], $_GET['char']);

$rowex = user_get_aei('biotime',$CSteckbrief->int_acctid);
if(isset($rowex['biotime']) && $rowex['biotime'] == BIO_LOCKED){
    die('Dieser Steckbrief wurde durch das Team gesperrt!');
}

if(CIgnore::ignores($CSteckbrief->int_acctid, $Char->acctid, CIgnore::IGNO_BIO) && CIgnore::ignores($CSteckbrief->int_acctid, $Char->acctid, CIgnore::IGNO_TWOWAY))
{
    die('Diese Person ignoriert dich leider :\'(!');
}
else if(CIgnore::ignores($Char->acctid,$CSteckbrief->int_acctid, CIgnore::IGNO_BIO))
{
    die('Du ignorierst diese Person ;)!');
}
$CSteckbrief->checkDelHistory();
$CSteckbrief->prepareOutput(array_merge(array('header','info'),CSteckbriefTabs::get_array($CSteckbrief->int_acctid),array('news','footer')));
$CSteckbrief->outputBio();
?>
