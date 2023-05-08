**Archival note**

DO NOT RUN THIS VERSION

This version is an unpatched release of DSv3.5.1 that lacks a couple of critical patches for **severe** security vulnerabilities. 

This branch is only made available for archival purposes. If you are interested in running the DragonSlayer Edition in production, please get in touch with the maintainer of this repository.

**End of archival note**


Legend of the Green Dragon
by Eric "MightyE" Stevens
http://www.mightye.org

Original Software Project Page:
http://sourceforge.net/projects/lotgd

Primary game server:
http://lotgd.net

########################
Das erste deutsche Release des Spielkerns wurde von Anpera erstellt und ist noch immer
als LoGD 0.9.7+jt ext (GER) unter http://www.anpera.net erh�ltlich.

Die hier vorliegende Version basiert auf der Arbeit von Anpera. Es handelt sich um eine stark erweiterte und optimierte Version von http://www.atrahor.de, auch bekannt als 0.9.7(Dragonslayer Edition V/3.5) 

Sie enth�lt viele Erweiterungen und Verbesserungen, die sie einzigartig machen, allerdings auch inkompatibel zu vielen Modifikationen, die im Internet zu finden sind.


######################
INSTALLATIONSANLEITUNG
######################


INSTALLATION:
================
Um dieses Paket installieren zu k�nnen brauchst Du
einen Webspace mit 

- mindestens 1GB Speicherplatz
- PHP 7.0 oder h�her (PHP >= 5.5 sollte funktionieren, wird aber ausdr�cklich nicht empfohlen)
- MySQL 5.5 oder h�her mit aktivierter InnoDB Unterst�tzung! (innodb_flush_log_at_trx_commit = 2 f�r h�chste Performace empfohlen, frag deinen Hoster :) !)
- (Optional) phpMyAdmin zum administrieren der Datenbank

Beachte bitte, dass ein Spiel wie dieses aufgrund der enormen ben�tigten Rechenleistung i.d.R. nicht auf Webspace von Free-Hostern und Billiganbietern installiert werden darf.

MySQL Setup:
Das Erstellen der ben�tigten Datenbanken sollte recht einfach und problemlos von Statten gehen.
Erstelle eine Datenbank oder verwende eine bereits vorhandene Datenbank.
Achte darauf, dass der User, der Zugriff auf die Datenbank hat, zumindest die folgenden Rechte 
f�r die Datenbank besitzt:
"Select Table Data", "Insert Table Data", "Update Table Data", 
"Delete Table Data", "Manage indexes", "Lock tables"

F�hre anschlie�end alle Befehle im SQL Script 
-----------------
db/ds_lotgd_35_install.sql 
-----------------
aus, um die ben�tigten Tabellen zu erstellen und mit einigen Daten zu f�llen.
Am einfachsten nutzt du daf�r die Importieren-Funktion und w�hlst die Datei von deiner lokalen Festplatte aus. Die Datei ist mit der Zeichencodierung utf8 gespeichert.

Hinweis: Aufgrund der Gr��e der Datei ist der Import m�glicherweise nur mit aktivierter Option "partieller Import" m�glich.

Anmerkung: sollte dein Hoster kein InnoDB unterst�tzen, musst du die Datei "ds_lotgd_35_install.sql" umschreiben, oder den Hoster wechseln ;)

PHP Setup:
==========
Lade alle Dateien und Ordner aus diesem Archiv auf deinen Webspace in das Verzeichnis aus dem das Spiel sp�ter gestartet werden soll. Der Ordner doc kann weggelassen werden.
Bearbeite nun die Datei
------------------
dbconnect.php
------------------
und f�ge dort deine Zugangsdaten zum MySQL Server und der entsprechenden LOTGD Datenbank ein.

$DB_USER="Dein_DB_Username"; //Wurde dir von deinem Provider mitgeteilt
$DB_PASS="Dein_DB_Passwort"; //Kennst du selbst am Besten
$DB_HOST="meistens localhost"; //Wurde dir von deinem Provider mitgeteilt
$DB_NAME="Dein_DB_Name"; //Name der Datenbank

(wenn m�glich) �ndere die Zugriffsrechte derart, dass die Datei von niemandem �berschrieben werden kann (chmod -w dbconnect.php) und nur der Webserver und niemand sie sonst lesen kann. (chown webservername dbconnect.php - Shellzugriff n�tig)
-----------------------------------

Auf folgende Ordner / Dateien ben�tigt der Webserver Schreibzugriff (chmod 666)
./cache/					(diverse Cache-Daten)
./images/avatar/			(Spieler-Avatare, ungepr�ft)
./images/avatar/confirmed/	(Spieler-Avatare, gepr�ft)
./templates/colors.css		(Farben f�r die Farbtags)


Spielstart:
===========
Das Spiel ist nun installiert und l�sst sich �ber einen Webbrowser aus dem Installationsverzeichnis heraus starten. Als erstes solltest Du Dich als Admin einloggen.
W�hrend der Installation wurde ein User
-----------------------------------
Username: admin, Passwort: CHANGEME
-----------------------------------
erzeugt, mit dem du in die Superuser-Grotte gehen und das Spiel deinen W�nschen anpassen kannst. Die Spieleinstellungen sind vielf�ltig, also nimm dir hierf�r Zeit, �ndere jedoch zuvor schleunigst sowohl deinen Usernamen als auch dein Passwort �ber den User Editor!

[WICHTIG]: Bearbeite nun die Datei
------------------
config.inc.php
------------------

Probleme?
=========
F: Ich kann mich nicht mit dem oben genannten Usernamen und Passwort einloggen!
A: Erlaube Cookies und Javascript f�r die Domain unter der das Spiel installiert wurde. 

F: Ich erhalte seltsame Zeichen anstelle der Umlaute ����
A: Dein Apache Webserver ist nicht korrekt eingestellt. Bitte deinen Serveradmin darum die Konfiguration des Apache um die Zeile
AddDefaultCharset UTF-8
zu erg�nzen, dann klappt alles prima!
A: Bitte beachte, dass alle Dateien utf-8 codiert sind und nur mit einem utf-8 f�higem Editor ver�ndert werden d�rfen, das gleiche gilt f�r neue Dateien (immer utf-8 kodiert abspeichern)

F: Der MOTD Link leuchtet permanent und bei jedem Seitenaufruf wird ein Popup ge�ffnet
A: Erstelle als Admin eine neue MOTD (zum Beispiel Begr��ungstext f�r neue Spieler), dann ist das Problem behoben!

F: Ich bekomme direkt nach der Installation einen Fehler der besagt, dass Windows nicht mit so einem kleinen Datum umgehen kann.
A: PANIK !!! Nein, keine Sorge, beim ersten Start des Spiels werden viele Variablen auf einen Standardwert gesetzt und in der DB gespeichert. Dabei kann es auf manchen Servern zu Fehlern kommen. Einfach neu laden und dann ist alles bueno!

####################
Dankesch�n DSV3
####################

Das Spiel unter Atrahor.de/lotgd.drachenserver.de w�re nicht entstanden oder �berhaupt so weit gekommen, wenn es da nicht die vielen kleinen Helferlein g�be, die ihr Leben, ihre Freizeit und ihre Sozialf�higkeit selbstlos aufgegeben h�tten. Aus diesem Grunde danken ich den folgenden Spielern ganz besonders herzlich (und werfe Asche auf mein Haupt wenn ich jemanden vergessen habe):

In alphabetischer Reihenfolge nach $session['user']['superuser'] gruppiert ;-) 

Progger
=======
�Alucard
�Asgarath
�B�thory
�Baras
�Fossla
�Jenutan
�Maris
�Mikay Kun
�Salator
�Takehon
�Talion
�Tyndal

Administratoren
===============
*Dragonslayer
*David
*Eleya
*Giennah
*H�rziel
*Ibga
*Jaheira
*Liara
*Sith

Moderatoren
===========
*Acar
*D�riel
*F�reth
*Felicity
*Kaja
*Lucia
*O-Ren-Ishi
*Sa onserei
*Sersee
*Sha'Lyn
*Shandi
*Yva�ne

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
Dankesch�n DSV3.5
####################

Die DS3.5 w�re ohne die folgenden Personen nie Realit�t geworden:

Progger
=======
�B�thory

Administratoren
===============
*Dragonslayer
*Callyshee
*Japeth

Moderatoren
===============
*Linn�a

Ehrenmitglieder und Helfer
===========================
Ceridwen


Und ein gro�er Dank an alle Beta-Tester!