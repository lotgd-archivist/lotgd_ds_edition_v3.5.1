<?php

/**
 * Spielversion
 */
define('GAME_VERSION','0.9.7(Dragonslayer Edition V/3.5)');

/**
 * Copyright wird unten im Template angezeigt!
 */
define('COPYRIGHT','&copy;2004-'.date("Y").' <a href="http://www.atrahor.de">Atrahor-Team</a>; based on LoGD 0.9.7 (<a href="http://www.atrahor.de/about.php?op=gpl">GPLv2</a>) &copy;2002-2003 <a target="_blank" href="http://lotgd.net">Eric Stevens</a>');

/**
 * Zeitzoneneinstellung für PHP Zeit
 */
define('DEFAULT_TIMEZONE','Europe/Berlin');

/**
 * Pfad zum Lib Ordner
 */
define('LIB_PATH','./lib/');

/**
 * Pfad zum Class Ordner
 */
define('CLASS_PATH',LIB_PATH.'classes/');

/**
 * Pfad zum Temp Ordner
 */
define('TEMP_PATH','./temp/');

/**
 * Pfad zum Cache Ordner
 */
define('CACHE_PATH','./cache/files/');

/**
 * Pfad zum Temp Ordner
 */
define('TEMPLATE_PATH','./templates/');

/**
 * Pfad zum Modul Ordner
 */
define('MODULE_PATH','./modules/');

/**
 * Pfad zum Bilder Ordner
 */
define('IMAGE_PATH','./images/');

/**
 * Setzt verschiedene Mechanismen des Spiels außer Kraft
 * wenn der Code auf einem lokalen Testserver ausgeführt wird.
 * Dieser Wert muss auf true gesetzt werden, wenn ein lokaler
 * Testserver verwendet wird!
 */
define('LOCAL_TESTSERVER',true);

//ganz wichtig einen eigenen zu generiere, dieser hier dient rein als platzhalter!
// unter http://rumkin.com/tools/password/pass_gen.php
// letters to use: 0123456789abcdef
// length: 64
// generate und hier einfügen
define('ENCRYPTION_KEY', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
//ganz wichtig einen eigenen zu generiere, dieser hier dient rein als platzhalter!
// unter http://rumkin.com/tools/password/pass_gen.php
// letters to use: 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
// length: 64
// generate und hier einfügen
define('SECRET_IMG_KEY', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');

?>
