<?php
require_once 'common.php';

$key = preg_replace("/[^A-Za-z0-9_]/", '', $_GET['k']);
$file =  $_SESSION['img'][$key];

if(file_exists($file))
{
    $ctype="image/jpg";
    $file_extension = pathinfo($file, PATHINFO_EXTENSION);

    switch( $file_extension ) {
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
    }

    header('Pragma: public');
    header('Cache-Control: max-age=86400');
    header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));

    header('Content-type: ' . $ctype);

    readfile($file);
}

?>
