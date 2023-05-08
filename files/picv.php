<?php

require_once('common.php');
$quota = CPicture::get_quota();
if($quota < CPicture::MAX_QOUTA)
{
    if( count($_FILES) )
    {
        $bildcnt = db_get("SELECT small_letter  FROM user_uploads_pictures WHERE small_letter NOT IN ('p','h','d','s') AND small_letter NOT LIKE 'mc%' AND userid='".intval($session['user']['acctid'])."' ORDER BY CAST(small_letter AS SIGNED)  DESC LIMIT 1");
        $next = intval($bildcnt['small_letter'])+1;

        if(getsetting('avatare',1) == 2) {
            $str_path = CPicture::AVATAR_UPLOAD_DIR;
        }
        else
        {
            $str_path = CPicture::AVATAR_SECURE_DIR;
        }
        $str_output = 'Server-Fehler!';
        if ($_FILES['file']['name'] != '')
        {
            $pict 	= new CPicture( $_FILES['file'] );
            if(!$pict->is_valide()) {
                $str_output = 'Erlaubt sind als Dateitypen für hochgeladene Bilder nur .jpg, .gif und .png! Bei deinem Bild handelt es sich um keinen solchen Typen.';
            }
            else {
                CPicture::picture_save_author('',$next);
                $pict->save($session['user']['acctid'],$next, -1, -1, $str_path);
                $quota = CPicture::get_quota();
                $perc = min(100,round((100*$quota)/CPicture::MAX_QOUTA));
                $col = 'green';
                if($perc > 60)$col = 'yellow';
                if($perc > 75)$col = 'orange';
                if($perc > 90)$col = 'red';
                $str_output = 'done_'.$perc.'_'.$col.'_'.(100-$perc).'% frei ('.bytesToSize($quota).'/'.bytesToSize(CPicture::MAX_QOUTA).')';
            }
        }
        die($str_output);
    }
}else{
    die('Du hast die Maximale Quota erreicht, du muss einige Bilder löschen oder durch kleinere Versionen (statt png z.B. jpg verwenden) ersetzen!');
}