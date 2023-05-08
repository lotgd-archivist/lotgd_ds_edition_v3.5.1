<?php
/**
 * su_configuration.php: Verwaltung der Spieleinstellungen
 * @author LOGD-Core / Drachenserver-Team
 * @version DS-E V/2
*/
/*
 * Takehon: Lost-Update-Lösung
 * Das System fragt nun bei Änderungen nach, wenn sich mittlerweile eine Einstellung geändert hat.
*/

require_once 'common.php';

$access_control->su_check(access_control::SU_RIGHT_GAMEOPTIONS,true);

loadsettings();

output(get_title('`&Spieleinstellungen'));

/**
 * Settings have to be saved
 */
if ($_GET['op']=='save')
{
	//Settings Cache leeren, wenn Änderungen gespeichert werden	
	//Cache::delete(Cache::CACHE_TYPE_HDD , 'settings');

	/*
	if ($_POST['blockdupeemail']==1)
	{
		$_POST['requirevalidemail']=1;
	}*/
	if ($_POST['requirevalidemail']==1)
	{
		$_POST['requireemail']=1;
	}

	output('Übernehme Änderungen... '.((array_key_exists('method', $_GET) && $_GET['method']=="force")?'(Änderungen erzwingen eingeschaltet!)':'').((array_key_exists('way', $_GET) && $_GET['way']=="tryagain")?'(erneuter Versuch)':'').'`n`n');

	$str_log = '';
	$bool_error = false; //Nicht speicherbare Änderungen
	$arr_oldPOST = array();

	$arr_back = utf8_unserialize(urldecode(stripslashes($_POST['settings_back'])));
	unset($_POST['settings_back']);

	foreach ($_POST as $key=>$val)
	{

		// Wenn jemand versucht, zu schummeln ; )
		if($key == 'sugroups') {
			$access_control->su_check(access_control::SU_RIGHT_RIGHTS,true);
			continue;
		}

		$val = stripslashes($val);
		if($arr_back[$key] != $val) {
			$actualsetting = getsetting($key, $val);
			if ((array_key_exists('method', $_GET) && $_GET['method']=="force") || $arr_back[$key] == $actualsetting){

			// Fürst ändern
			if($key == 'townname') {
				$str_oldtitle = 'Fürst von '.db_real_escape_string($settings[$key]);
				$str_newtitle = '`&Fürst von '.db_real_escape_string($val);
				$sql = 'SELECT acctid FROM account_extra_info WHERE ctitle LIKE "%'.$str_oldtitle.'%"';
				$arr_acc = db_fetch_assoc(db_query($sql));

				if(!empty($arr_acc['acctid'])) {
					$sql = 'UPDATE account_extra_info SET ctitle = "'.$str_newtitle.'" WHERE acctid='.$arr_acc['acctid'];
					db_query($sql);

					user_set_name($arr_acc['acctid']);
				}
			}

			savesetting($key,$val);
			$str_log .= '`@=> '.$key.' von "'.$arr_back[$key].'" geändert auf "'.$val.'"`0`n';
			
			} else {
				$str_log .= '`$=> '.$key.' von "'.$arr_back[$key].'" NICHT geändert auf "'.$val.'", da momentaner Wert: "'.$actualsetting.'" !`0`n';
				$arr_oldPOST[$key] = $val;
				$bool_error = true;
			}
		}
	}

	if(!empty($str_log)) {
		systemlog('`3Veränderte Spieleinstellungen:`n'.$str_log,$session['user']['acctid']);
		output(utf8_htmlentities($str_log));
		if ($bool_error == true){
			output('`n`^Einige Änderungen konnten nicht übernommen werden.`nFalls erforderlich, bitte eine der folgenden Aktionen auf jene anwenden.`n`bFehler beim Speichern von ungewünschten Änderungen auf jeden Fall ignorieren!`b`n');
			output('<table border="0"><tr><td>Entweder </td><td><form action="su_configuration.php?op=save&way=tryagain" method="POST">
					<input type="hidden" name="settings_back" value="'.urlencode(utf8_serialize($arr_back)).'">',true);
			foreach ($arr_oldPOST as $oldkey=>$oldval) output('<input type="hidden" name="'.$oldkey.'" value="'.$oldval.'">',true);
			output('<input type="submit" value="Erneut versuchen"></form></td><td> oder </td><td>',true);
			addnav('','su_configuration.php?op=save&way=tryagain');
			output('<form action="su_configuration.php?op=save&method=force" method="POST">
					<input type="hidden" name="settings_back" value="'.urlencode(utf8_serialize($arr_back)).'">',true);
			foreach ($arr_oldPOST as $oldkey=>$oldval) output('<input type="hidden" name="'.$oldkey.'" value="'.$oldval.'">',true);
			output('<input type="submit" value="Änderungen erzwingen (Vorsicht!)"></form></td></tr></table>',true);
			addnav('','su_configuration.php?op=save&method=force');
		}
	}
	else {
		output('Keine Veränderungen vorgenommen, nichts gespeichert!');
	}
	addnav('Zurück zu den Spieleinstellungen',basename(__FILE__));
}



page_header('Spieleinstellungen');
grotto_nav();
addnav('',$REQUEST_URI);


$time = (strtotime(date('1981-m-d H:i:s',strtotime(date('r').'-'.getsetting('gameoffsetseconds',0).' seconds'))))*getsetting('daysperday',4) % strtotime('1981-01-01 00:00:00');
$time = gametime();


$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
$today = mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time));
$dayduration = ($tomorrow-$today) / getsetting('daysperday',4);
$secstotomorrow = $tomorrow-$time;
$secssofartoday = $time - $today;
$realsecstotomorrow = round($secstotomorrow / getsetting('daysperday',4),0);
$realsecssofartoday = round($secssofartoday / getsetting('daysperday',4),0);
$enum='enum';

for ($i=0;$i<=86400;$i+=900)
{
	$enum.=",$i,".((int)($i/60/60)).":".($i/60 %60);
}

$weather_enum = 'radio';
foreach(Weather::$weather as $id=>$w)
{
	$w['name'] = str_replace(',','',$w['name']);
	$weather_enum.=','.$id.','.$w['name'].'.';
}

$setup = array(

	'Servereinstellungen,title',
	'server_name'=>'Der Name des Servers|?Der logische Name des Servers. Wir ehren unsere liebe Dame Charly mit diesem Setting',
	'server_address'=>'Komplette Server URL',
	'server_address_no_protocoll'=>'Server URL ohne Protokoll',
	'min_age'=>'Mindestalter (für Char-Erstellung)',

    'LOGINTIMEOUT'=>'Sekunden Inaktivität bis man als Inaktiv gilt (für PvP) man wird nicht ausgeloggt!,int',

	'locale'=>'Einstellung für lokal unterschiedl. Darstellungen (Zeit etc.; Keine = Server-Default),text,10|?Der Wert für explizit deutsche Darstellung lautet de_DE',
	'maxonline'=>'Maximal gleichzeitig online (0 für unbegrenzt),int',
	'server_meta_keywords'=>'Keywords die in den Head Bereich jedes HTML Dokuments geschrieben werden (Suchmaschinenoptimierung),textarea,60,20',
	'server_meta_description'=>'Eine Beschreibung die in den Head Bereich jedes HTML Dokuments geschrieben werden (Suchmaschinenoptimierung),textarea,60,20',
	'server_source_available'=>'Sourcecode-Link anzeigen,bool|?Schaltet den Sourcecode Link global an oder ab!',

    'Mailadressen,divider',
	'gameadminemail'=>'Admin Email',
	'petitionemail'=>'Anfragen Email (Absender)',

	'Paypal,divider',
	'paypal_enabled'=>'Sollen die Paypal Links angezeigt werden?,bool',
	'paypal_author_enabled'=>'Soll der Paypal Link für den Autor angezeigt werden,bool',
	'paypal_server_enabled'=>'Soll der Paypal Link für den aktuellen Server angezeigt werden,bool',
	'paypal_email'=>'E-Mail Adresse für den PayPal Account des Admins',

	'Werbung,divider',
	'ad_enabled'=>'Soll Werbung angeschaltet werden?,bool',
	'ad_html'=>'HTML Fragment das für die Werbung eingesetzt wird,textarea,60,20',
	'ad_conversion' => 'HTML Fragment das bei erfolgreicher conversion gesetzt wird,textarea,60,20|?Z.B. Für Google Ads',

	'Inhalte löschen (0 für nie löschen),divider',
	'lastcleanup'=>'Datetime der letzten Säuberung',
	'cleanupinterval'=>'Sekunden zwischen Säuberungen,int',
	'expirecontent'=>'Tage die Kommentare und News aufgehoben werden,int',

	'Spieleinstellungen,title',
    'Bathis-Ecke,divider',
    'quest_activ'=>'Quest-System aktiv?,bool',
	'Präsenz,divider',
	'wartung'=>'Wartungsmodus an,bool|?Um einzelne Accounts für den Wartungsmodus freizuschalten, kannst du die Rechtesektion im Usereditor verwenden.',
	'blocknewchar'=>'Neuanmeldungen sperren?,bool',
	'loginbanner'=>'Login Banner (unterhalb der Login-Aufforderung; 255 Zeichen)',

	'nav_help_enabled'=>'Hilfetext bei Navigationslinks einschalten,bool',
	'defaultskin'=>'Standardskin ( Ordnername )',
	'townname'=>'Name der Stadt:',
	'teamname'=>'Bezeichnung des Administrationsteams:',
	'Alles mögliche,divider',
	'soap'=>'Userbeiträge säubern (filtert Gossensprache),bool',
	'superuser_silvester'=>'Grotten-Konfetti aktiv,bool',
	'emailonmail'=>'Email-Benachrichtigung bei Brieftauben-Eingang,bool',
	'automaster'=>'Meister jagt säumige Lehrlinge,bool',
	'multimaster'=>'Meister kann mehrmals pro Tag herausgefordert werden?,bool',
	'limithp'=>'Lebenpunkte maximal Level*12+5*DPinHP+x*DK (0=deaktiviert),int',
	'autofight'=>'Automatische Kampfrunden ermöglichen,bool',
	'witchvisits'=>'Erlaubte Besuche bei der Hexe,int',
	'symp_active'=>'Sympathiepunktesystem / Fürst aktiv,bool',
	'max_symp'=>'Vergebbare Sympathiepunkte pro Monat,int',
	'symp_per_acc'=>'Max. Anzahl an Symp.punkten die auf einen Chara verteilt werden können,int',
	'dailyspecial'=>'Heutiges besonderes Ereignis',
	'enable_commentemail'=>'User dürfen Chatmitschnitte an ihre Mail senden,bool',
	'enable_modcall'=>'"Mod rufen"-Button unter Chats anbieten,bool',
	'history_edit_enabled'=>'Möglichkeit zum ändern und hinzufügen von Aufzeichnungen anbieten,bool',
	'lib_alternative_author'=>'Alternative zur Anzeige des Autors bei Bibliotheksbüchern wie dem Gesetzbuch',
	'Hilfeanfragen,divider',
	'petition_mail_assignment_message'=>'Brieftaubenbenachrichtigung bei Anfragen Zuordnung.,bool|?Wird einem Superuser eine Anfrage zugeordnet bekommt dieser eine Brieftaube!',

	'LoGD-Netz Einstellungen (file wrappers müssen in der PHP Konfiguration aktiviert sein!!),divider',
	'logdnet'=>'Beim LoGD-Netz eintragen?,bool',
	'serverdesc'=>'Serverbeschreibung (255 Zeichen)',
	'logdnetserver'=>'LoGD-Netz Zentralserver (Default: http://lotgd.net)',
	'Neue Tage / Raum und Zeit,divider',
	'fightsforinterest'=>'Höchste Anzahl an übrigen Waldkämpfen um Zinsen zu bekommen,int',
	'maxinterest'=>'Maximaler Zinssatz (%),int',
	'mininterest'=>'Minimaler Zinssatz (%),int',
	'daysperday'=>'Spieltage pro Kalendertag,int',
	'dispnextday'=>'Zeit zum nächsten Tag in Vital Info,bool',
	'specialtybonus'=>'Zusätzliche Einsätze der Spezialfertigkeit am Tag,int',
	'activategamedate'=>'Spieldatum aktiv,bool',
	'gamedateformat'=>'Datumsformat (zusammengesetzt aus: %Y; %y; %m; %n; %d; %j)',
	'gametimeformat'=>'Zeitformat',

	'Orte,title',
	'Die Schenke,divider',
	'maxales'=>'Maximale Anzahl Ale die bei einer "Runde" spendiert werden kann,int',
	'paidales'=>'Ale das als "Runde" spendiert wurde (Wert-1),int',
	'Expedition,divider',
	'DDL_new_order'=>'DDL-Lagenwechsel nach spätestens x Tagen,int',
	'DDL_balance_malus'=>'DDL-Punkteabzug pro Tag,int',
	'DDL_balance_push'=>'DDL-Punkteschwelle um Lageänderunge herbeizuführen,int',
	'DDL_balance_win'=>'DDL-Punkteschwelle damit Angriff gelingt,int',
	'DDL_balance_lose'=>'DDL-Negativpunkteschwelle zur Niederlage,int',
	'DDL-restart'=>'DDL-Lager nach x Tagen erneuern,int',
	'DDL_comments_req'=>'DDL-Anzahl der Posts in der Einöde bis neue Gegner erscheinen,int',
	'Stadtfest,divider',
	'lastparty'=>'Wann war das letzte Bürgerfest',
	'min_party_level'=>'Wieviel Geld muss für eine Party vorhanden sein,int',
	'amtskasse'=>'Gold in der Amtskasse,int',
	'party_duration'=>'Wieviele Tage soll das Stadtfest dauern (1;2;0.5;...),int',
	'party_force_party'=>'Überschreibe alle anderen Settings. Das Stadtfest findet statt - Punkt! :-),bool',
	'Wald,divider',
	'turns'=>'Waldkämpfe pro Tag,int',
	'resurrection_turns_loss'=>'WK-Verlust bei Wiedererweckung in %,int|?ab der 2. Erweckung pro Ingame-Tag: Wert + 5% Steigerung',
	'dropmingold'=>'Waldkreaturen lassen mindestens 1/4 des möglichen Goldes fallen,bool',
	'lowslumlevel'=>'Mindestlevel bei dem perfekte Kämpfe eine Extrarunde geben,int',
	'forestbal'=>'Prozentsatz der pro perfektem Kampf auf Monsterstärke aufgeschlagen wird',
	'forestdkbal'=>'Prozentsatz mit dem Heldenpunkteeinfluss auf Monsterstärke multipliziert wird',
	'foresthpbal'=>'Zahl durch die max. LP geteilt werden ehe sie auf DP-Einfluss addiert werden',
	'Schloss,divider',
	'castle_turns_wk'=>'Anzahl an WKs die man für eine Schlossrunde erhält,int',
	'wk_castle_turns'=>'Anzahl an WKs die eine Schlossrunde kostet,int',
	'castle_turns'=>'Schlossrunden pro Tag ,int',
	'castlegemdesc'=>'Abweichung vom max. Edelsteingewinn / Runde über dem max.,int',
	'castlegolddesc'=>'Abweichung vom max. Goldgewinn / Runde über dem max.,int',

	'Behörden,title',
	'Büro des Fürsten,divider',
	'fuerst'=>'Fürst,viewonly',
	'taxrate'=>'Derzeitiger Steuersatz,int',
	'mintaxes'=>'Mindeststeuersatz,int',
	'maxtaxes'=>'Höchstmöglicher Steuersatz,int',
	'taxprison'=>'Derzeitige Anzahl Kerkertage für Steuerhinterziehung,int',
	'maxprison'=>'Höchststrafe für Steuerhinterziehung,int',
	'taxfee'=>'Gebühr für automatischen Steuer-Bankeinzug in %,int',
	'callvendormax'=>'Fürst kann wie oft in seiner Amtszeit den Wanderhändler holen,int',
	'beggarmax'=>'Maximales Fassungsvermögen des Bettelsteins,int',
	'maxbudget'=>'Maximale Größe der Staatskasse,int',
	'maxamtsgems'=>'Maximale Anzahl an Edelsteinen in den Tresoren,int',
	'lurevendor'=>'Kosten um den Wanderhändler anzulocken,int',
	'freeorkburg'=>'Kosten um die Orkburg freizulegen,int',
	'Ämter,divider',
	'numberofguards'=>'Maximale Zahl an Stadtwachen',
	'numberofpriests'=>'Maximale Zahl an Priestern',
	'numberofwitches'=>'Maximale Zahl an Hexen',
	'numberofjudges'=>'Maximale Zahl an Richtern',
	'guardreq'=>'Nötige DKs um Stadtwache zu werden',
	'judgereq'=>'Nötige DKs um Richter zu werden',
	'priestreq'=>'Nötige DKs um Priester / Hexer zu werden',
	'guard_max_imprison'=>'Max. Anzahl an Stadtwacheneinkerkerungen pro Spieltag und Wache',

	'Accounts,title',
	'Account-Erstellung,divider',
	'newplayerstartgold'=>'Gold mit dem ein neuer Char startet,int',
	'requireemail'=>'E-Mail Adresse beim Anmelden verlangen,bool',
	'requirevalidemail'=>'E-Mail Adresse bestätigen lassen,bool',
	'blockdupeemail'=>'Nur ein Account pro E-Mail Adresse,bool',
	'spaceinname'=>'Erlaube Leerzeichen in Benutzernamen,bool',
	'specialkeys'=>'Erlaube Sonderzeichen in Benutzernamen,bool',
	'criticalchars'=>'Zeichen die nicht in Namen vorkommen dürfen (regulärer Ausdruck /[..Eingegebene Zeichen..]/)!),text,100',
	'allletter_up_allow'=>'Namen nur in Großbuchstaben erlauben,bool',
	'firstletter_up'=>'Erster Buchstabe immer in Großschreibung,bool',
	'name_casechange'=>'Änderung der Groß-/Kleinschreibung des Namens in Jägerhütte erlauben,bool',
	'nameminlen'=>'Mindestlänge für Login in Zeichen (Ohne Farbcodes)',
	'namemaxlen'=>'Maximallänge für Login in Zeichen (Ohne Farbcodes)',
	'titleminlen'=>'Mindestlänge für eigenen Titel in Zeichen (Ohne Farbcodes)',
	'titlemaxlen'=>'Maximallänge für eigenen Titel in Zeichen (Mit Farbcodes)',
	'name_maxcolors'=>'Maximalanzahl an Farbcodes im Namen,int',
	'title_maxcolors'=>'Maximalanzahl an Farbcodes in eigenem Titel,int',
	'msg_chars_max'=>'Maximalanzahl an Msg-Chars,int',
	'selfdelete'=>'Erlaube den Spielern ihren Charakter zu löschen,bool',
	'avatare'=>'Erlaube den Spielern Avatare + Bilder hochzuladen,enum,0,Nein,1,Ohne Prüfung,2,Mit Prüfung',
	'recoveryage'=>'Tage ab denen ein Spieler täglich Extra-Erfahrung bekommt,int',
	'recoveryexp'=>'Anzahl der Extra-Erfahrungspunkte (*DKs) pro Tag,int',
	'cowardlevel'=>'Level den ein Spieler haben muss um Feigling zu werden,int',
	'cowardage'=>'Tageanzahl seit DK um Feigling zu werden,int',
	'coward_title_enabled'=>'Soll der Feiglingstitel vergeben werden?,bool',
	'maxagepvp'=>'Max Tageanzahl seit DK für PvP und Ruhmeshalle,int',
	'race_change_allowed'=>'Rassenwechsel in der Schenke erlauben,bool',
	'unaccepted_namechange'=>'Abgelehnte Namen werden geändert zu -unzulässiger Name xxx-,bool',
	'Accountlöschung,divider',
	'expire_accounts'=>'Sollen Accounts entfallen dürfen?,bool',
	'expiretrashacct'=>'Tage die Accounts gespeichert werden die nie eingeloggt waren,int',
	'expirenewacct'=>'Tage die Level 1 Accounts ohne Heldentat aufgehoben werden,int',
	'expirevacationacct'=>'Tage die Accounts im Urlaubsmodus aufgehoben werden,int',
	'expireoldacct'=>'Tage die alle anderen Accounts aufgehoben werden,int',
	'expire_donationpoints'=>'Anzahl der Donationpoints die Usern gegeben werden wenn sie sich doch wieder einloggen,int',
	'expire_sendmail_before'=>'Anzahl der Tage vor der Löschung an dem eine Erinnerungsmail geschrieben wird,int',
	'vacation_ban_time'=>'Tage Mindestabwesenheit für Urlaubsmodus (Systembann),int',
	'Biographie,divider',
	'htmleditor_enabled'=>'Soll der HTML Editor verfügbar sein?,bool|?Wenn der HTML Editor verfügbar ist, werden einige Textboxen mit einem Online HTML Editor ersetzt der eine leichtere Bearbeitung (z.B. der Bio) ermöglichen soll',

	'longbiomaxlength'=>'Maximale Zeichenanzahl Steckbrief Rp-/OOC-Reiter,int',
	'bioextranotesmaxlength'=>'Maximale Zeichenanzahl d. Usernotes (-1 zum Ausschalten),int',
	'Testzugang,divider',
	'demouser_public'=>'Test-Zugang verfügbar?,bool',
	'demouser_acctid'=>'Account-ID des Testzugangs (Vorsicht!),int,|?Hier bietet sich die ID eines gelöschten Spielers an um Inkonsistenzen mit der DB zu vermeiden.',

	'Mod-spezifisches,title',
	'libdp'=>'Max. vergebbare Donationpoints pro angenommenem Buch,int',
	'rebirth_dks'=>'Nötige DKs für Erneuerung',
	'wallchangetime'=>'Geschmiere an der Mauer kann erst nach x Sekunden geändert werden,int',
	'maxsentence'=>'Höchststrafe in Tagen',
	'locksentence'=>'Tage im Kerker ab denen es Sicherheitsverwahrung gibt',
	'user_rename'=>'Preis in DP für Namensänderung nach Erneuerung / Wiedergeburt',
	'deathjackpot'=>'Derzeitiger Stand des Tot-o-Lotto Jackpots,int',
	'deathjackpotmax'=>'Maximaler Stand des Tot-o-Lotto Jackpots,int',

	'RP-Belohnung,divider',
	'rpdon_dpcomment'=>'DP pro x (Mindestlänge Zeichen),int|?Angabe von Dezimalstellen möglich; 0: Funktion deaktiviert.',
	'rpdon_minlen'=>'Mindestlänge für DPS,int',
	'rpdon_sections'=>'Chatsections in denen Kommentare gezählt werden|?Durch Kommata getrennt; interne Bezeichner. section,section,...',

	'Häuser und Gilden,title',
	'Gilden,divider',
	'dgguildmax'=>'Max. Anzahl an Gilden,int',
	'dgguildfoundgems'=>'Gems zur Gründung,int',
	'dgguildfoundgold'=>'Gold zur Gründung,int',
	'dgguildfound_k'=>'DKs zur Gründung,int',
	'dgmaxmembers'=>'Max. Mitgliederzahl ohne Ausbauten,int',
	'dgminmembers'=>'Min. Mitgliederzahl,int',
	'dgplayerfights'=>'Max. Kämpfe eines Spielers gegen Gildenwachen pro Spieltag,int',
	'dgimmune'=>'Spieltage Immunität für eine neu gegründete Gilde,int',
	'dggpgoldcost'=>'Kosten eines GP in Gold,int',
	'dggpgemcost' => 'Kosten eines GP in Edelsteinen,int',
	'dgtaxdays'=>'Alle x Spieltage Steuern,int',
	'dgmaxtaxfails'=>'x mal Steuern nicht zahlen damit Gilde aufgelöst,int',
	'dgtaxgold'=>'Basis-Goldkosten der Steuer,int',
	'dgtaxgems'=>'Basis-Gemkosten der Steuer,int',
	'dgmaxgemstransfer'=>'Max. Edelsteinauszahlung pro Lvl,int',
	'dgmaxgoldtransfer'=>'Max. Goldauszahlung pro Lvl,int',
	'dgmaxgoldin'=>'Max. Goldeinzahlung pro Spieltag,int',
	'dgmaxgemsin'=>'Max. Gemeinzahlung pro Spieltag,int',
	'dgtrsmaxgold'=>'Max Gold in Schatzkammer,int',
	'dgtrsmaxgems'=>'Max Gems in Schatzkammer,int',
	'dgminmembertribute'=>'Mindesttribut der Mitglieder in %,int',
	'dgmindkapply'=>'Mindestanzahl an DKs für Mitgliedschaft,int',
	'dgstartgold'=>'Startgold,int',
	'dgstartgems'=>'Startgems,int',
	'dgstartpoints'=>'StartGP,int',
	'dgstartregalia'=>'Startinsignien,int',
	'dgbiomax'=>'Max. Zeichenanzahl der Bio,int',
	'dgminregaliaval'=>'Min. Preis / Insignie in GP,int',
	'dgmaxregaliaval'=>'Max. Preis / Insignie in GP (sinkt pro halbe Insignie die im Durchschnitt mehr verkauft wurde um 1),int',
	'dg_invent_out_price'=>'Faktor mit dem der Wert eines Items beim Auslagern aus Gildeninventar multipliziert wird um die Gebühr zu ermitteln,int',
	'guild_own_description_maxlength'=>'Max. Zeichenanzahl für selbsterstellte Texte die in der Gildenhalle angezeigt werden anstelle des Standarts,int',
	'guildinvitationcost'=>'Kosten für eine Gildeneinladung in GP/h,int',
	'Häuser,divider',
	'housemaxgemsout'=>'Max. Anzahl an Edelsteinen / Tag aus Haus entnehmbar,int',
	'newhouses'=>'Bauen neuer Häuser möglich ?,bool',
	'maxhouses'=>'Maximale Anzahl an Häusern ?,int',
	'housegetdks'=>'Min. DKs für Häuserbau / kauf?,int',
	'housekeylvl'=>'Min. Lvl (bei 0 DKs) für Schlüsselvergabe?,int',
	'houseextdks'=>'Min. DKs für Hausausbau?,int',
	'housetrsgoldmax'=>'Max. Gold in Hausschatz (Standard),int',
	'housetrsgemsmax'=>'Max. Gems in Hausschatz (Standard),int',
	'housetrsshare'=>'Bei Schlüsselabnahme Teil aus Hausschatz an Betroffenen?,bool',
	'housedesclen'=>'Max. Länge für Hausbeschreibung?,int',
	'housefreerooms'=>'Anzahl an Räumen für deren Bau es eine Gutschrift gibt,int',
	'housefreeroomsplus'=>'Anzahl an Räumen für deren Bau es eine Gutschrift gibt in ausgebautem Haus,int',
	'housefreeroomsgold'=>'Gutschrift an Gold für ebendiese Räume,int',
	'housefreeroomsgems'=>'Gutschrift an Gems für ebendiese Räume,int',
	'housemaxrooms'=>'Max. Anzahl an Räumen in nicht ausgebautem Haus,int',
	'housemaxroomsplus'=>'Max. Anzahl an Räumen in ausgebautem Haus,int',
	'housefreekeys'=>'Min. Anzahl an Schlüsseln in nicht ausgebautem Haus,int',
	'housefreekeysplus'=>'Min. Anzahl an Schlüsseln in ausgebautem Haus,int',
	'housemaxkeys'=>'Max. Anzahl an Schlüsseln in nicht ausgebautem Haus,int',
	'housemaxkeysplus'=>'Max. Anzahl an Schlüsseln in ausgebautem Haus,int',
	'housemaxextensions'=>'Max. Anzahl an Anbauten in nicht ausgebautem Haus,int',
	'housemaxextensionsplus'=>'Max. Anzahl an Anbautem in ausgebautem Haus,int',
	'housebuildcostgold'=>'Baukosten für ein Haus in Gold,int',
	'housebuildcostgems'=>'Baukosten für ein Haus in Gems,int',
	'houseabandonedmintime'=>'Mindestzeit in Sekunden bevor ein verlassenes Haus zum Verkauf gestellt wird,int',

	'Post und Handel,title',
	'Handelseinstellungen,divider',
	'borrowperlevel'=>'Maximalwert den ein Spieler pro Level leihen kann (Bank),int',
	'maxinbank'=>'+/- Maximalbetrag für den noch Zinsen bezahlt/verlangt werden,int',
	//vom alten System 'bankmaxgemstrf'=>'Max. Anzahl an Gemüberweisungen / Tag,int',
	'allowgoldtransfer'=>'Erlaube Überweisungen (Gold),bool',
	'allowgemtransfer'=>'Erlaube Überweisungen (Edelsteine),bool',
	'transferperlevel'=>'Maximalwert den ein Spieler pro Level empfangen oder nehmen kann,int',
	'mintransferlev'=>'Mindestlevel für Überweisungen (bei 0 DKs),int',
	//vom alten System 'bankgemtrflvl'=>'Minimallvl um Edelsteinüberweisungen empfangen zu können,int',
	'maxtransferout'=>'Menge die ein Spieler an andere überweisen kann (Wert x Level),int',
	'innfee'=>'Gebühr für Expressbezahlung in der Kneipe (x oder x%),int',
	'selledgems'=>'Edelsteine die Vessa vorrätig hat,int',
	'gypsy_maxselledgems'=>'Maximalwert Edelsteine bei Vessa,int',
	'vendor'=>'Händler heute in der Stadt?,bool',
	'paidgold'=>'Gold das für Bettelstein spendiert wurde,int',
	'Brieftauben,divider',
	'mailsizelimit'=>'Maximale Anzahl an Zeichen in einer Nachricht,int',
	'inboxlimit'=>'Anzahl an Nachrichten in der Inbox,int',
	'modinboxlimit'=>'Dergleichen für MODs,int',
	'oldmail'=>'Alte Nachrichten automatisch löschen nach x Tagen. x =,int',
	'modoldmail'=>'Dergleichen für MODs,int',
	'show_yom_contacts'=>'Zeige das Adressbuch in der YOM an,bool',
	'max_yom_contacts'=>'Maximale Anzahl an YOM Kontakten,int',
	'message2mail_activated'=>'Dürfen YoMs per Mail archiviert werden?,bool',
	'automatic_header_generation'=>'Sollen leere Betreffzeilen automatisch gefüllt werden?,bool',
	'automatic_header_length'=>'Wieviele Zeichen sollen vom Text in den Header übernommen werden?,int',
	'archive_yom_anabled'=>'Soll die Möglichkeit Brieftauben dauerhaft zu speichern angeschaltet werden?,bool',
	'archive_yom_limit'=>'Wieviele Brieftauben dürfen von Usern dauerhaft gespeichert werden?,int',
	'archive_yom_mod_limit'=>'Wieviele brieftauben dürfen von Mods dauerhaft gespeichert werden?,int',
	'forward_yom_enable' => 'Weiterleitung der Brieftauben an andere User zulassen?,bool|?Momentan nicht implementiert',
	'forward_yom_admin_enable' => 'Weiterleitung der Brieftauben an andere Grottenolme für Teammitglieder zulassen?,bool',
	'forward_yom_keep_copy' => 'Soll von weitergeleiteten Nachrichten eine Kopie beim eigentlichen Empfänger bleiben,bool',
	'forward_yom_maximum_depth' => 'Wie oft darf die Mail maximal weitergeleitet werden,int|?Damit keine Endlosschleifen auftreten wird die Anzahl der maximalen Weiterleitungen begrenzt.',
	'Mail Transfer Agent Einstellungen,divider',
	'mail_sender_address'=>'Welcher Absender wird verwendet|?Muss zum versendenden Mailserver passen, ansonsten werden die Mails von vielen Servern als Spam abgelehnt',


	'Kopfgeld und PvP,title',
	'PvP,divider',
	'pvp'=>'Spieler gegen Spieler aktivieren,bool',
	'pvpday'=>'Spielerkämpfe pro Tag,int',
	'pvpimmunity'=>'Tage die neue Spieler vor PvP sicher sind,int|?Ingame-Tage die ein neuer Spieler automatisch vor PvP Angriffen geschützt ist',
	'pvpminexp'=>'Mindest-Erfahrungspunkte für PvP-Opfer,int|?Ab wieviel Erfahrungspunkte ist ein Spieler nicht mehr durch die Neulingsimmunität geschützt?',
	'pvpattgain'=>'Prozentsatz der Erfahrung des Opfers den der Angreifer bei Sieg bekommt,int',
	'pvpattlose'=>'Prozentsatz an Erfahrung den der Angreifer bei Niederlage verliert,int',
	'pvpdefgain'=>'Prozentsatz an Erfahrung des Angreifers den der Verteiger bei einem Sieg gewinnt,int',
	'pvpdeflose'=>'Prozentsatz an Erfahrung den der Verteidiger bei Niederlage verliert,int',
	'pvpmindkxploss'=>'DKs Unterschied zwischen Täter und Opfer bis zu dem noch 0% XP abgezogen werden,int',
	'pvpimmu_daysaftercrime'=>'PVP-Immu kann erst x Tage nach Straftat gekauft werden,int',
	'pvp_immu_return'=>'Rückgabe der PVP Immunität erlauben?,bool',
	'Kopfgeld,divider',
	'bountymin'=>'Mindestbetrag pro Level der Zielperson,int',
	'bountymax'=>'Maximalbetrag pro Level der Zielperson,int',
	'bountylevel'=>'Mindestlevel um Opfer sein zu können,int',
	'bountyfee'=>'Gebühr für Dag Durnick in Prozent,int',
	'maxbounties'=>'Anzahl an Kopfgeldern die ein Spieler pro Tag aussetzen darf,int',

	'Community,title',
	'RSS Einstellungen,divider',
	'rss_enable_motd_feed'=>'Automatisch RSS feed für MOTD erstellen?,bool',
	'rss_motd_feed_address' => 'Eine alternative Adresse des MOTD Webfeeds|?Zum Beispiel zur Verwendung mit Feedburner',
	'rss_motd_title'=>'Titel des MOTD RSS Feeds',
	'rss_motd_description'=>'Beschreibung des RSS Feeds',	
	'rss_enable_motc_feed'=>'Automatisch RSS feed für MOTC erstellen?,bool',
	'rss_motc_feed_address' => 'Eine alternative Adresse des MOTC Webfeeds|?Zum Beispiel zur Verwendung mit Feedburner',
	'rss_motc_title'=>'Titel des MOTC RSS Feeds',
	'rss_motc_description'=>'Beschreibung des RSS Feeds',
	'rss_item_count'=>'Default Anzahl der RSS Items,int',
	'rss_link'=>'Wohin linken die RSS Items',
	'rss_image'=>'Link zu einem Bild das den Feed beschreibt',

	'Chat,title',
	'Chat,divider',
	'chat_post_len'=>'Max. Postlänge klein,int',
	'chat_post_len_long'=>'Max. Postlänge groß,int',
	'chat_post_len_max'=>'Max. Postlänge gesamt,int',
	'max_posts_edits'=>'Max. anzahl an Editierungen pro Beitrag (Letzter und inline),int',
	'Wer ist hier,divider',
	'chat_who_is_here'=>'"Wer ist hier?" aktiviert?,bool',
	'user_list_chat_status'=>'Chat Status in der Userliste. Welche Stati werden angezeigt und welche nicht?,bitflag,Suche Partner,RPG kann erweitert werden,DND, Warte auf RPG Partner, Unsichtbar, Sichtbar',
	
	'Sonstige Informationen,title',
	'weather'=>'Heutiges Wetter:,'.$weather_enum,
	'newplayer'=>'Neuster Spieler',
	'Letzter neuer Tag: '.date('h:i:s a',strtotime(date('r').'-$realsecssofartoday seconds')).',viewonly',
	'Nächster neuer Tag: '.date('h:i:s a',strtotime(date('r').'+$realsecstotomorrow seconds')).',viewonly',
	'Aktuelle Spielzeit: '.getgametime(true).',viewonly',
	'Tageslänge: '.($dayduration/60/60).' Stunden,viewonly',
	'Aktuelle Serveruhrzeit: '.date('Y-m-d h:i:s a').',viewonly',
	'gameoffsetseconds'=>'Offset der Spieltage,$enum',
	'gamedate'=>'aktuelles Spieldatum (Y-m-d)',
	'exsearch_limit'=>'x Suchen für die Offlinesuche,int|?Wie oft darf in der Spielerliste gesucht werden wenn man nicht online ist.',
	'exsearch_time'=>'x Minuten Wartezeit bei Offlinesuche,int|?Wie lange muss man warten bevor man wieder die Spielerliste offline durchsuchen darf.',
	'form_submit'=>'Standardbeschriftung des Submit-Buttons',
	'hasegg'=>'Besitzer des Goldenen Eies (User-ID),viewonly',
	'jslib_buildID'=>'JSLIB Build-ID,viewonly',
	'onlinetop'=>'Onlinerekord,viewonly',

	'petitionemailsent'=>'Anzahl versander E-Mails als Anfragenantwort,viewonly'
	);



if ($_GET['op']=='')
{
	addnav('S?Unwichtiges, Spielstände...','su_configuration.php?op=notlisted');

	$output .= '<form action="su_configuration.php?op=save" method="POST">
				<input type="hidden" name="settings_back" value="'.urlencode(utf8_serialize($settings)).'">';
	addnav('','su_configuration.php?op=save');

	showform($setup,$settings);

	$output .= '</form>';
}

if($_GET['op']=='notlisted')
{
	addnav('S?Zurück zu den Settings','su_configuration.php');
	
	//Array für alles was gar nicht angezeigt werden soll
	$arr_hidesettings=array(
		'old_symp_vote_list',
		'sugroups',
		'title_array'
	);
	
	$arr_notlisted=array();
	foreach($settings as $key=>$val)
	{
		if(!array_key_exists($key,$setup) && !in_array($key,$arr_hidesettings))
		{
			$arr_notlisted[$key]=$key;
		}
	}
	ksort($arr_notlisted);
	$arr_notlisted=array_merge(array('Nicht aufgeführte Settings,title'),$arr_notlisted);
	
	$output .= '<form action="su_configuration.php?op=save" method="POST">
				<input type="hidden" name="settings_back" value="'.urlencode(utf8_serialize($settings)).'">';
	addnav('','su_configuration.php?op=save');

	showform($arr_notlisted,$settings);

	$output .= '</form>';
}

page_footer();
?>
