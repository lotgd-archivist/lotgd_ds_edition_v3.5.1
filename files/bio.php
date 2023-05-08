<?php
//bathory
require_once('common.php');

$rowex = user_get_aei('biotime',intval($_GET['id']));
if(isset($rowex['biotime']) && $rowex['biotime'] == BIO_LOCKED){
    die('Diese Bio wurde durch das Team gesperrt!');
}

$BOOL_JSLIB_PLU_MI = true;
if(CIgnore::ignores($_GET['id'], $Char->acctid, CIgnore::IGNO_BIO) && CIgnore::ignores($_GET['id'], $Char->acctid, CIgnore::IGNO_TWOWAY))
{
    die('Diese Person ignoriert dich leider :\'(!');
}
else if(CIgnore::ignores($Char->acctid,$_GET['id'], CIgnore::IGNO_BIO))
{
    die('Du ignorierst diese Person ;)!');
}
$pageid = isset($_GET['p']) ? $_GET['p'] : null;
new CRPBio((int)$_GET['id'], $pageid);
?>
