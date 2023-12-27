Legend of the Green Dragon
by Eric "MightyE" Stevens
http://www.mightye.org

Original Software Project Page:
http://sourceforge.net/projects/lotgd

Primary game server:
http://lotgd.net

########################
Das erste deutsche Release des Spielkerns wurde von Anpera erstellt und ist noch immer
als LoGD 0.9.7+jt ext (GER) unter http://www.anpera.net erhältlich.

Die hier vorliegende Version basiert auf der Arbeit von Anpera. Es handelt sich um eine stark erweiterte und optimierte Version von http://www.atrahor.de, auch bekannt als 0.9.7(Dragonslayer Edition V/3.5) 

Sie enthält viele Erweiterungen und Verbesserungen, die sie einzigartig machen, allerdings auch inkompatibel zu vielen Modifikationen, die im Internet zu finden sind.


######################
INSTALLATIONSANLEITUNG
######################


INSTALLATION:
================
Um dieses Paket installieren zu können brauchst Du
einen Webspace mit 

- mindestens 1GB Speicherplatz
- PHP 7.0 oder höher (PHP >= 5.5 sollte funktionieren, wird aber ausdrücklich nicht empfohlen)
- MySQL 5.5 oder höher mit aktivierter InnoDB Unterstützung! (innodb_flush_log_at_trx_commit = 2 für höchste Performace empfohlen, frag deinen Hoster :) !)
- (Optional) phpMyAdmin zum administrieren der Datenbank

Beachte bitte, dass ein Spiel wie dieses aufgrund der enormen benötigten Rechenleistung i.d.R. nicht auf Webspace von Free-Hostern und Billiganbietern installiert werden darf.

MySQL Setup:
Das Erstellen der benötigten Datenbanken sollte recht einfach und problemlos von Statten gehen.
Erstelle eine Datenbank oder verwende eine bereits vorhandene Datenbank.
Achte darauf, dass der User, der Zugriff auf die Datenbank hat, zumindest die folgenden Rechte 
für die Datenbank besitzt:
"Select Table Data", "Insert Table Data", "Update Table Data", 
"Delete Table Data", "Manage indexes", "Lock tables"

Führe anschließend alle Befehle im SQL Script 
-----------------
db/ds_lotgd_35_install.sql 
-----------------
aus, um die benötigten Tabellen zu erstellen und mit einigen Daten zu füllen.
Am einfachsten nutzt du dafür die Importieren-Funktion und wählst die Datei von deiner lokalen Festplatte aus. Die Datei ist mit der Zeichencodierung utf8 gespeichert.

Hinweis: Aufgrund der Größe der Datei ist der Import möglicherweise nur mit aktivierter Option "partieller Import" möglich.

Anmerkung: sollte dein Hoster kein InnoDB unterstützen, musst du die Datei "ds_lotgd_35_install.sql" umschreiben, oder den Hoster wechseln ;)

PHP Setup:
==========
Lade alle Dateien und Ordner aus diesem Archiv auf deinen Webspace in das Verzeichnis aus dem das Spiel später gestartet werden soll. Der Ordner doc kann weggelassen werden.
Bearbeite nun die Datei
------------------
dbconnect.php
------------------
und füge dort deine Zugangsdaten zum MySQL Server und der entsprechenden LOTGD Datenbank ein.

$DB_USER="Dein_DB_Username"; //Wurde dir von deinem Provider mitgeteilt
$DB_PASS="Dein_DB_Passwort"; //Kennst du selbst am Besten
$DB_HOST="meistens localhost"; //Wurde dir von deinem Provider mitgeteilt
$DB_NAME="Dein_DB_Name"; //Name der Datenbank

(wenn möglich) ändere die Zugriffsrechte derart, dass die Datei von niemandem überschrieben werden kann (chmod -w dbconnect.php) und nur der Webserver und niemand sie sonst lesen kann. (chown webservername dbconnect.php - Shellzugriff nötig)
-----------------------------------

Auf folgende Ordner / Dateien benötigt der Webserver Schreibzugriff (chmod 666)
./cache/					(diverse Cache-Daten)
./images/avatar/			(Spieler-Avatare, ungeprüft)
./images/avatar/confirmed/	(Spieler-Avatare, geprüft)
./templates/colors.css		(Farben für die Farbtags)


Spielstart:
===========
Das Spiel ist nun installiert und lässt sich über einen Webbrowser aus dem Installationsverzeichnis heraus starten. Als erstes solltest Du Dich als Admin einloggen.
Während der Installation wurde ein User
-----------------------------------
Username: admin, Passwort: CHANGEME
-----------------------------------
erzeugt, mit dem du in die Superuser-Grotte gehen und das Spiel deinen Wünschen anpassen kannst. Die Spieleinstellungen sind vielfältig, also nimm dir hierfür Zeit, ändere jedoch zuvor schleunigst sowohl deinen Usernamen als auch dein Passwort über den User Editor!

[WICHTIG]: Bearbeite nun die Datei
------------------
config.inc.php
------------------

Probleme?
=========
F: Ich kann mich nicht mit dem oben genannten Usernamen und Passwort einloggen!
A: Erlaube Cookies und Javascript für die Domain unter der das Spiel installiert wurde. 

F: Ich erhalte seltsame Zeichen anstelle der Umlaute ÖÄÜß
A: Dein Apache Webserver ist nicht korrekt eingestellt. Bitte deinen Serveradmin darum die Konfiguration des Apache um die Zeile
AddDefaultCharset UTF-8
zu ergänzen, dann klappt alles prima!
A: Bitte beachte, dass alle Dateien utf-8 codiert sind und nur mit einem utf-8 fähigem Editor verändert werden dürfen, das gleiche gilt für neue Dateien (immer utf-8 kodiert abspeichern)

F: Der MOTD Link leuchtet permanent und bei jedem Seitenaufruf wird ein Popup geöffnet
A: Erstelle als Admin eine neue MOTD (zum Beispiel Begrüßungstext für neue Spieler), dann ist das Problem behoben!

F: Ich bekomme direkt nach der Installation einen Fehler der besagt, dass Windows nicht mit so einem kleinen Datum umgehen kann.
A: PANIK !!! Nein, keine Sorge, beim ersten Start des Spiels werden viele Variablen auf einen Standardwert gesetzt und in der DB gespeichert. Dabei kann es auf manchen Servern zu Fehlern kommen. Einfach neu laden und dann ist alles bueno!

####################
Dankeschön DSV3
####################

Das Spiel unter Atrahor.de/lotgd.drachenserver.de wäre nicht entstanden oder überhaupt so weit gekommen, wenn es da nicht die vielen kleinen Helferlein gäbe, die ihr Leben, ihre Freizeit und ihre Sozialfähigkeit selbstlos aufgegeben hätten. Aus diesem Grunde danken ich den folgenden Spielern ganz besonders herzlich (und werfe Asche auf mein Haupt wenn ich jemanden vergessen habe):

In alphabetischer Reihenfolge nach $session['user']['superuser'] gruppiert ;-) 

Progger
=======
¬Alucard
¬Asgarath
¬Báthory
¬Baras
¬Fossla
¬Jenutan
¬Maris
¬Mikay Kun
¬Salator
¬Takehon
¬Talion
¬Tyndal

Administratoren
===============
*Dragonslayer
*David
*Eleya
*Giennah
*Hârziel
*Ibga
*Jaheira
*Liara
*Sith

Moderatoren
===========
*Acar
*Dériel
*Fýreth
*Felicity
*Kaja
*Lucia
*O-Ren-Ishi
*Sa onserei
*Sersee
*Sha'Lyn
*Shandi
*Yvaïne

Ehrenmitglieder und Helfer
===========================
Caillean
Masher
Morticia
Niphredil
Raciel
Salvan
Valas

####################
Dankeschön DSV3.5
####################

Die DS3.5 wäre ohne die folgenden Personen nie Realität geworden:

Progger
=======
¬Báthory

Administratoren
===============
*Dragonslayer
*Callyshee
*Japeth

Moderatoren
===============
*Linnéa

Ehrenmitglieder und Helfer
===========================
Ceridwen


Und ein großer Dank an alle Beta-Tester!
