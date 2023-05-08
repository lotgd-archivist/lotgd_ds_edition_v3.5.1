<?php
require_once "common.php";
checkday();
page_header("User löschen");
output("Dein Charakter, sein Inventar und alle seine Kommentare wurden gelöscht!");
addnav("Erwachen","fish.php?op=awake");
page_footer();
