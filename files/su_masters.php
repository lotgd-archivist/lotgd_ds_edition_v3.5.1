<?php
/*
* su_masters.php
* Version:   15.08.2004
* Author:   bibir
* Email:   logd_bibir@email.de
*
* Purpose: show and edit masters
*
#
# Tabellenstruktur für Tabelle `masters`
#

DROP TABLE IF EXISTS `masters`;
CREATE TABLE `masters` (
  `creaturename` varchar(50) default null,
  `creaturelevel` int(11) default null,
  `creatureweapon` varchar(50) default null,
  `creaturelose` varchar(180) default null,
  `creaturewin` varchar(180) default null,
  `creaturehealth` int(11) default null,
  `creatureattack` int(11) default null,
  `creaturedefense` int(11) default null,
  PRIMARY KEY  (`creaturelevel`)
) TYPE=MyISAM AUTO_INCREMENT=15 ;
*/

require_once "common.php";
$access_control->su_check(access_control::SU_RIGHT_EDITORWORLD,true);

page_header("Meistereditor");

addnav("W?Zurück zum Weltlichen","village.php");
addnav("G?Zurück zur Grotte","superuser.php");
//addnav("K?Zurück zu den Kampf-Editoren","superuser.php?op=fightsystem_editors");

output('`b`cMeistereditor`c`b`n`n');

if($_GET['op']=="edit"){
    addnav("Liste ansehen","su_masters.php");
     if($_GET['subop']=="save"){
        // fehler abfangen
        if($_POST['creaturename']=="" || $_POST['creatureweapon']==""|| $_POST['creaturelose']==""|| $_POST['creaturewin']==""){
            output("`n`4Es wurden unzulässige Texte eingegeben.");
        } else if($_POST['creaturehealth']<=0 || $_POST['creatureattack']<=0 || $_POST['creaturedefense']<=0){
            output("`n`4Falsche Werte fuer Lebenspunkte, Angriff und/oder Verteidigung angegeben.");
        } else {
            output("`2Meister wird geändert`0`n");
            $sql = "UPDATE masters SET creaturename='".$_POST['creaturename']."',
                                       creatureweapon='".$_POST['creatureweapon']."',
                                       creaturehealth='".$_POST['creaturehealth']."',
                                       creatureattack='".$_POST['creatureattack']."',
                                       creaturedefense='".$_POST['creaturedefense']."',
                                       creaturelose='".$_POST['creaturelose']."',
                                       creaturewin='".$_POST['creaturewin']."'
                    WHERE creaturelevel=".$_GET['level'];
            db_query($sql);
        }
    }

   output("Diesen Meister editieren");
//   output('`nGroße Platzhalter (%X, %W) sind Werte (Name, Waffe) des Siegers, kleine Platzhalter die des Verlierers.');
   $sql = "SELECT * FROM masters WHERE creaturelevel=".$_GET['level'];
   $result = db_query($sql);
   $row = db_fetch_assoc($result);
   output("`0<form action=\"su_masters.php?op=edit&subop=save&level=".$_GET['level']."\" method='POST'>",true);
   output("<table><tr><td>Level</td><td>".$row['creaturelevel']."</td></tr>",true);
   output("<tr><td>Name</td><td><input type='text' name='creaturename' maxlength='50' value='".utf8_htmlentities($row['creaturename'],ENT_QUOTES)."'></td></tr>",true);
   output("<tr><td>Waffe</td><td><input type='text' name='creatureweapon' maxlength='50' value='".utf8_htmlentities($row['creatureweapon'],ENT_QUOTES)."'></td></tr>",true);
   output("<tr><td>Lebenspunkte</td><td><input type='text' name='creaturehealth'  value='".$row['creaturehealth']."'></td></tr>",true);
   output("<tr><td>Angriff</td><td><input type='text' name='creatureattack'  value='".$row['creatureattack']."'></td></tr>",true);
   output("<tr><td>Verteidigung</td><td><input type='text' name='creaturedefense'  value='".$row['creaturedefense']."'></td></tr>",true);
   rawoutput("<tr><td>Nachricht beim Tod</td><td><input type='text' name='creaturelose' size='55' maxlength='180' value='".utf8_htmlentities($row['creaturelose'],ENT_QUOTES)."'></td></tr>",true);
   rawoutput("<tr><td>Nachricht beim Sieg</td><td><input type='text' name='creaturewin' size='55' maxlength='180' value='".utf8_htmlentities($row['creaturewin'],ENT_QUOTES)."'></td></tr>",true);
   output('</table>',true);

   output("<input type='submit' class='button' value='Speichern'></form>",true);
   	output('`nDie folgenden Codes werden unterstützt:`n
	%w = Name des Verlierers`n
	%x = Waffe des Verlierers`n
	%s = Geschlecht des Verlierers (Tod: ihn/sie, Sieg: ihm/ihr)`n
	%p = Geschlecht des Verlierers (sein/ihr)`n
	%o = Geschlecht des Verlierers (er/sie)`n
	%W = Name des Gewinners`n
	%X = Waffe des Gewinners`n
	Farbe der Meldung bei Tod \'&, bei Sieg \'^');
   addnav("","su_masters.php?op=edit&subop=save&level=".$_GET['level']);
} else {
   addnav("Aktualisieren","su_masters.php");
   $sql = "SELECT * FROM masters ORDER BY creaturelevel ASC";
   $result = db_query($sql);
   output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999' align=center>",true);
   output("<tr class='trhead'><th>Editieren</th><th>Level</th><th>Meistername</th><th>Waffe</th><th>Lebenspunkte</th><th>Angriff</th><th>Verteidigung</th></tr>",true);
   output("<tr class='trhead'><th>&nbsp;</th><th colspan=\"3\">Nachricht beim Tod</th><th colspan=\"3\">Nachricht beim Sieg</th></tr>",true);
   while($row = db_fetch_assoc($result)){
      output("<tr></tr>",true);
      output("<tr class='trlight'><td>[<a href='su_masters.php?op=edit&level=".$row['creaturelevel']."'>Edit</a>]</td>",true);
      addnav("","su_masters.php?op=edit&level=".$row['creaturelevel']);
      output("<td>".$row['creaturelevel']."</td>",true);
      output("<td>".$row['creaturename']."</td>",true);
      output("<td>".$row['creatureweapon']."</td>",true);
      output("<td>".$row['creaturehealth']."</td>",true);
      output("<td>".$row['creatureattack']."</td>",true);
      output("<td>".$row['creaturedefense']."</td>",true);
      output("</tr><tr class='trdark'><td></td>",true);
      rawoutput("<td colspan=\"3\">".$row['creaturelose']."</td>",true);
      rawoutput("<td colspan=\"3\">".$row['creaturewin']."</td>",true);
      output("</tr>",true);
   }
   output("</table>",true);
}

page_footer();
?> 