<?php
/**
* user.php: Zentrales Werkzeug für Superuser, um Spieleraccounts zu bearbeiten und zu verwalten
* @author Standardrelease by MightyE / Anpera, überarbeitet by talion <t@ssilo.de>
* @version DS-E V/2
*/

require_once 'common.php';
require_once(LIB_PATH.'dg_funcs.lib.php');
require_once(LIB_PATH.'profession.lib.php');
$str_filename = basename(__FILE__);

$access_control->su_check(access_control::SU_RIGHT_EDITORUSER,true);

function editnav ()
{
	global $row, $access_control,$str_filename;

	addnav('Aktionen');
	if ($_GET['returnpetition']!='')
	{
		addnav('Zurück zur Anfrage','su_petitions.php?op=view&id='.$_GET['returnpetition']);
	}
	//addnav('In memoriam: 2. Aktualisieren-Button');
	//addnav('Aktualisieren','user.php?op=edit&userid='.$_GET['userid']);

	//addnav('Kontrolle');
	addnav('Verbannen','su_bans.php?op=edit_ban&ids[]='.$row['acctid']);
	addnav('Urlaubsmodus',$str_filename.'?op=vacationmode&userid='.$row['acctid']);

	if($access_control->su_check(access_control::SU_RIGHT_DEBUGLOG))
	{
		addnav('Debug-Log anzeigen','su_logs.php?op=search&type=debuglog&account_id='.$_GET['userid']);
	}

	if($access_control->su_check(access_control::SU_RIGHT_EDITORITEMS)) {
		addnav('Inventar','su_item.php?what=items&acctid='.$_GET['userid']);
	}
	if ($row['house'] && $access_control->su_check(access_control::SU_RIGHT_EDITORHOUSES) ){
		addnav("Zum Hausmeister","su_houses.php?op=edit&id=".$row['house']);
	}
	if($access_control->su_check(access_control::SU_RIGHT_BIOS)) {
		$acctid = (int)$_GET['userid'];
		$stammb = new CStammbaum($acctid);
		$stammb->has_tree() ? addnav('Stammbaum bearbeiten','su_stammbaum.php?userid='.$acctid) : false;
	}
	addnav('Knappeneditor','user.php?op=disciple&userid='.$_GET['userid']);
	addnav('Runeneditor','user.php?op=runes&userid='.$_GET['userid']);
	addnav('Specialseditor','user_special.php?op=edit&userid='.$_GET['userid']);

}

page_header('Usereditor');
output(get_title('Der Usereditor'));
$_SESSION['last_user_editor_edit'] = ((int)$_SESSION['last_user_editor_edit']>0)?(int)$_SESSION['last_user_editor_edit']:$session['user']['acctid'];
$output .= '
	<form action="user.php?op=search" method="POST">
		Suche in allen Feldern: '
		//. '<input name="q" id="q">'
		.'<br />'
		. JS::Autocomplete('q', true, true)
		//. '<input type="submit" class="button">
	.'</form>
	<br />
	<div class="trhead">'.plu_mi('petition_search',0,false).'Spezialoptionen:</div>
	<div id="'.plu_mi_unique_id('petition_search').'" style="display:none;">
		<ul>
			<li>'.create_lnk('Selbst editieren','user.php?op=edit&userid='.$session['user']['acctid'],true,true).'
			<li>'.create_lnk('Letzten Eintrag erneut editieren (Acctid: '.(int)$_SESSION['last_user_editor_edit'].')','user.php?op=edit&userid='.(int)$_SESSION['last_user_editor_edit']).'
		</ul>
	</div>
	<hr />
';
$output .= JS::Focus("q",false);
addnav('','user.php?op=search');
grotto_nav();

$str_op = ($_REQUEST['op'] ? $_REQUEST['op'] : '');

switch($str_op) {

	case 'search':

		$arr_users = CCharacter::getChars($_POST['q'],'acctid',
			array(
				'a.acctid'	=> array('type'=>CCharacter::SEARCH_EXACT , 'mode'=> null, 'open_bracket' => false, 'close_bracket' => false),
				'login' 	=> array('type'=>CCharacter::SEARCH_SOUNDEX  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
				'name' 		=> array('type'=>CCharacter::SEARCH_LIKE_EXT  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
				'emailaddress' 		=> array('type'=>CCharacter::SEARCH_LIKE  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
				'lastip' 		=> array('type'=>CCharacter::SEARCH_LIKE  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
				'uniqueid' 		=> array('type'=>CCharacter::SEARCH_LIKE  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
			)
		);


		$int_count = count($arr_users);
		if ($int_count<=0)
		{
			output('`$Keine Ergebnisse gefunden`0');

			$where='';
		}
		elseif ($int_count>100){
			output('`$Zu viele Ergebnisse gefunden. Bitte Suche einengen.`0');

			$where='';
		}
		elseif ($int_count==1)
		{
			$_GET['page']=0;
		}
		else
		{
			$_GET['page']=0;
		}

	break;	// END search

	case 'logout_all':

		if($_GET['act'] == 'ok') {

			user_update(
				array
				(
					'loggedin'=>0,
					'where'=>'superuser=0 AND loggedin=1'
				)
			);
			//GESAMTEN Memorycache leeren!
			Cache::purge(Cache::CACHE_TYPE_MEMORY);
			output(db_affected_rows().' Spieler erfolgreich ausgeloggt!');

		}
		else {

			$sql = "SELECT COUNT(*) AS a FROM accounts WHERE loggedin=1 AND superuser=0";
			$count = db_fetch_row(db_query($sql));

			output($count[0].' Spieler wirklich ausloggen?`n`n'.create_lnk('Ab ins Körbchen!','user.php?op=logout_all&act=ok'),true);

		}
	break;	// END logout all

	case 'edit':

		$_SESSION['last_user_editor_edit'] = (int)$_GET['userid'];
		$result = db_query("SELECT * FROM accounts WHERE acctid=".(int)$_GET['userid']);
		$row = db_fetch_assoc($result);
		//Superuserrechte laden
		$row['surights'] = utf8_unserialize(($row['surights']));

		$arr_rights = array();
		$arr_usergroup = access_control::user_get_sugroups( $row['superuser'] );

		if( false !== $arr_usergroup ){

			$arr_grprights = $arr_usergroup[2];

			// Einzelrechte überschreiben Gruppenrechte
			$arr_rights = array_merge_assoc( $arr_grprights, $row['surights'] );
		}

		$result2 = db_query("SELECT * FROM account_extra_info WHERE acctid=".(int)$_GET['userid']);
		$row2 = db_fetch_assoc($result2);
		
		// FORMULAR-ARRAY erstellen

		if($access_control->su_check(access_control::SU_RIGHT_RIGHTS)) {
			$arr_grps = user_get_sugroups();

			$sugroups = '';
			if(is_array($arr_grps)) {
				foreach($arr_grps as $lvl=>$grp) {

					$sugroups .= ','.$lvl.','.$grp[0].'/'.$grp[1].(0 == $lvl ? ' (Standard-Spieler)' : '');

				}
			}

			$ugrp = array();

			// Wenn dieser User einer Gruppe angehört
			if(isset($arr_grps[$row['superuser']])) {
				$ugrp = $arr_grps[$row['superuser']];
				$ugrp_rights = $ugrp[2];
			}

			$surights = array('Superuser-Rechte,title');
			$str_dependence = '';
			foreach($access_control as $r=>$v) {

				$str_dependence = '';

				// Titel
				if(is_string($v)) {
					$surights[] = $v.',title,2';
				}
				else {

					if(!empty($v['dependent'])) {
						$str_dependence = '|?(Abhängig von: '.$access_control[$v['dependent']]['desc'].')';
					}

					$surights['surights['.$r.']'] = $v['desc'].($ugrp[0] ? '`nAktueller Wert: '.($arr_rights[$r] ? '`@Ja`0' : '`$Nein`0').' / Gruppenwert: '.($ugrp_rights[$r] ? '`@Ja`0' : '`$Nein`0') : '').',enum,-1,Gruppeneinstellung,0,Nein,1,Ja'.$str_dependence;
				}

			}

		}

		$mounts=',0,Keins';
		$sql = 'SELECT mountid,mountname,mountcategory FROM mounts ORDER BY mountcategory';
		$result = db_query($sql);
		while ($m = db_fetch_assoc($result)){
			$mounts.=','.$m['mountid'].','.$m['mountcategory'].': '.strip_appoencode($m['mountname'],3);
		}

		$specialties=',0,Keins';
		$sql = 'SELECT specname,category,specid FROM specialty ORDER BY category, specname';
		$result = db_query($sql);
		while ($m = db_fetch_assoc($result)){
			$specialties.=','.$m['specid'].','.$m['category'].': '.strip_appoencode($m['specname'],3);
		}

		$professions = ',0,Keiner';
		$joblist = ',0,Keiner';

		foreach($profs as $k=>$p) {

			$professions .= ','.$k.','.$p[0].'/'.$p[1];

		}

		foreach($jobs as $k2=>$p2) {

			$joblist .= ','.$k2.','.$p2[0].'/'.$p2[1];

		}

		$guildfuncs = '';

		foreach($dg_funcs as $k=>$f) {

			$guildfuncs .= ','.$k.','.$f[0].'/'.$f[1];

		}

		$races=',,Unbekannt';
		$sql = 'SELECT name,id FROM races WHERE active=1 ORDER BY name ASC';
		$result = db_query($sql);
		while ($m = db_fetch_assoc($result)){
			$races.=','.$m['id'].','.$m['name'];
		}
		//edit by bathi
		$msgChars = adv_unserialize($row2['msg_chars']);
		//edit end
		
		$userinfo = array(
			'Accountdaten &amp; Namen,title',
			'acctid'=>'User ID,viewonly|?Die Accountid, unter der der Account in der DB gespeichert ist.',

			'name'=>'Voller Name,viewonly|?Zum Ändern des Gesamtnamens bitte die einzelnen Bestandteile (Login, Farbname, Titel, eigener Titel) editieren.',
			'login'=>'Login|?Loginname des Accounts.',
			'title'=>'Regulärer Titel',
			'ctitle'=>'Eigener Titel',
			'ctitle_backup'=>'Eigener Titel - Backup',
			'cname'=>'Eigener (farbiger) Name',
			'csign'=>'Besonderes Signum vor dem Namen (max. 3 Zeichen)',
			'title_postorder'=>'Titel hinter den Namen setzen,bool',
			'title_hide'=>'Titel ausblenden,bool',

			'newpassword'=>'Neues Passwort',
			'emailaddress'=>'Email-Adresse',
			'loggedin'=>'Eingeloggt,bool',
			'banoverride'=>'Verbannungen übergehen,bool',
			'specialinc'=>'aktuelles SpecialEvent',
			'superuser'=>'Superuser,'.($access_control->su_check(access_control::SU_RIGHT_RIGHTS) ? 'enum'.$sugroups : 'viewonly'),
			'superuser_id_switch' => 'ID des zugehörigen Superuser Chars,int|?Die ID des Admincharakters die bei einem Superuser Invoke geladen wird!',
			'conf_bits'=>'Konfigurationsflags,bitflag,Chaching deaktiviert <i>(nicht empfohlen)<i>,Darf keine Sympathiepunkte bekommen,Darf nicht an PVP teilnehmen,Darf Knappe nicht mehr weggeben?',

			'Charakterdaten,title',
			'sex'=>'Geschlecht,enum,0,Männlich,1,Weiblich',
			'race'=>'Rasse,enum'.$races,
			'specialty'=>'Spezialgebiet,enum'.$specialties,
			'birthday'=>'Ankunftsdatum (Format: YYYY-MM-DD)',
			'char_birthdate'=>'Geburtsdatum des Spielers|?Anleitung s. Profil',
			'charclass'=>'Charakterklasse',
			'profession'=>'Amt,enum'.$professions,
			'job'=>'Beruf,enum'.$joblist,
			'marriedto'=>'Partner-ID (4294967295 = Violet/Seth),int',
			'charisma'=>'Flirts (4294967295 = verheiratet mit Partner),int',
			'expedition'=>'Zutritt zur Expedition?,bool',

			'ext_rp'=>'RP-Info'.($row2['ext_rp'] == null ? ' (nicht vorhanden),viewonly':',textarea,60,30'),
            'ext_ooc'=>'OOC'.($row2['ext_ooc'] == null ? ' (nicht vorhanden),viewonly':',textarea,60,30'),

            'msg_chars'=>'Msg-Chars (1 pro Zeile)'.( count($msgChars) == 0 ? ' (nicht vorhanden),viewonly':',textarea,60,30'),

			'guildid'=>'GildenID,int',
			'guildrank'=>'Gildenrang (1-'.count($dg_default_ranks).'),int',
			'guildfunc'=>'Funktion in der Gilde,enum'.$guildfuncs,

			'Werte,title',
			'dragonkills'=>'Heldentaten,int',
			'level'=>'Level,int',
			'experience'=>'Erfahrung,int',
			'hitpoints'=>'Lebenspunkte (aktuell),int',
			'maxhitpoints'=>'Maximale Lebenspunkte,int',
			'alive'=>'Lebendig,bool|?Wirkt nur, wenn LP > 0!',
			'deathpower'=>'Gefallen bei Ramius,int',
			'gravefights'=>'Grabkämpfe übrig,int',
			'soulpoints'=>'Seelenpunkte (HP im Tod),int',
			'turns'=>'Runden übrig,int',
			'castleturns'=>'Schlossrunden übrig,int',
			'maze'=>'aktuelle Schlosskarte,int',
			'fishturn'=>'Angelrunden,int',
			'playerfights'=>'Spielerkämpfe übrig,int',
			'attack'=>'Angriffswert (inkl. Waffenschaden),int',
			'defence'=>'Verteidigung (inkl. Rüstung),int',
			'spirits'=>'Stimmung (nur Anzeige),enum,'.RP_RESURRECTION.',RP-Wiedererweckung,-6,Wiedererweckt,-2,Sehr schlecht,-1,Schlecht,0,Normal,1,Gut,2,Sehr gut',
			'resurrections'=>'Auferstehungen,int',
			'reputation'=>'Ansehen (-50 - +50),int',
			'sentence'=>'Zu x Tagen Haft verurteilt,int',
			'imprisoned'=>'Haftstrafe in Tagen,int',
			'daysinjail'=>'Verbrachte Tage im Kerker,int',
			'charm'=>'Charme,int',
			'sympathy'=>'Sympatiepunkte,int',
			'battlepoints'=>'Arenapunkte,int',
			'gladiatorfights'=>'Gladiatorkämpfe vor DK übrig,int',
			'age'=>'Tage seit Level 1,int',
			'dragonage'=>'Alter bei der letzten Heldentat,int',
			'marks'=>'Male,bitflag,Mal der Erde,Mal der Luft,Mal des Feuers,Mal des Wassers,Mal des Geistes,Pakt mit Blutgott',

			'Ausstattung &amp; Besitz,title',
			'gems'=>'Edelsteine,int',
			'gemsinbank'=>'Gems auf der Bank,int',
			'gold'=>'Bargold,int',
			'goldinbank'=>'Gold auf der Bank,int',
			'minnows'=>'Fliegen im Beutel,int',
			'worms'=>'Würmer im Beutel,int',
			'boatcoupons'=>'Bootscoupons im Beutel,int',
			'weapon'=>'Name der Waffe',
			'weapondmg'=>'Waffenschaden,int',
			'weaponvalue'=>'Kaufwert der Waffe,int',
			'armor'=>'Name der Rüstung',
			'armordef'=>'Verteidigungswert,int',
			'armorvalue'=>'Kaufwert der Rüstung,int',
			'house'=>'Haus-ID,int',
			'hashorse'=>'Tier,enum'.$mounts,
			'xmountname'=>'Name des Tieres',

			'Aktueller Spieltag / Übrige Aktionen,title',
			'seenlover'=>'Geflirtet,bool',
			'seendragon'=>'Bosse heute versucht,bool',
			'seenmaster'=>'Meister befragt,bool',
			'fedmount'=>'Tier gefüttert,bool',
			'seenbard'=>'Barden gehört,bool',
			'usedouthouse'=>'Plumpsklo besucht,bool',
			'treepick'=>'Baum des Lebens besucht,bool',
			'boughtroomtoday'=>'Zimmer für heute bezahlt,bool',
			'hadnewday'=>'Rastbonus erhalten,enum,0,Nein,1,Ja,2,Wiedererweckt',
			'witch'=>'Hexenbesuche,int',
			'cage_action'=>'Käfigkämpfe heute angezettelt,int',
			'rouletterounds'=>'übrige Rouletterunden (Zehner = Todeszähler),int',
			'gotfreeale'=>'Frei-Ale (MSB: getrunken - LSB: spendiert),int',
			'goldin'=>'Goldeingang heute,int',
			'goldout'=>'Goldausgang heute,int',
			'gemsin'=>'Gemeingang heute,int',
			'gemsout'=>'Gemausgang heute,int',
			'guildtransferred_gold'=>'Gildentransfer (gold),int',
			'guildtransferred_gems'=>'Gildentransfer (gems),int',
			'guildfights'=>'Gildenkämpfe heute,int',
			'temple_servant'=>'Tempeldienertage(x20=heute geleistet),int',
			'drunkenness'=>'Betrunken (0-100),int',
			'pvpflag'=>'Letzter PvP-Kampf ('.PVP_IMMU.' = Immu an)',
			'last_crime'=>'Letzte Straftat',
			'balance_forest'=>'Waldbalance|?-10 / +20, > 0 verstärkt Werte der Waldmonster, < 0 verringert sie.',
			'balance_dragon'=>'Bossbalance|?-10 / +20, > 0 verstärkt Werte der Bosse, < 0 verringert sie.',
			'location'=>'Aufenthaltsort,enum,0,Felder,1,Kneipe,2,Haus,3,Kerker,'.USER_LOC_VACATION.',Urlaubsmodus',

			'Freischaltungen / DP,title',
			'rename_weapons'=>'individuelle Waffe/Rüstung,bitflag,Waffe umbenennen,Rüstung färben',

			'hasxmount'=>'Tier getauft,bool',
			'trophyhunter'=>'Präparierset gekauft,bool',
			'advanced_title_options'=>'Erweiterte Titeloptionen freigeschaltet,bool',

			'Spezielle Ruhmeshalleneinträge,title',
			'bestdragonage'=>'Jüngstes Alter bei einer Heldentat,int',
			'beerspent'=>'Anzahl spendierter Ales,int',
			'disciples_spoiled'=>'Anzahl verheizter Knappen,int',
			'timesbeaten'=>'Verpügelt worden,int',
			'runaway'=>'Aus dem Kampf geflohen,int',

			'exchangequest'=>'Tauschquest-Level,int',
			'hunterlevel'=>'Jagd-Level,int',

			'Weitere Infos,title',
			'laston'=>'Zuletzt Online,viewonly',
			'lasthit'=>'Letzter neuer Tag,viewonly',
            'lastmotd'=>'Datum der letzten MOTD,viewonly',
            'lastmotc'=>'Datum der letzten MOTC,viewonly',
			'lastip'=>'Letzte IP,viewonly',
			'uniqueid'=>'Unique ID,viewonly',
			'allowednavs'=>'Zulässige Navigation,viewonly',
			'dragonpoints'=>'Eingesetzte Heldenpunkte,viewonly',
			'bufflist'=>'Spruchliste,viewonly',
			'prefs'=>'Einstellungen,viewonly',
			'donationconfig'=>'Spendenkäufe,viewonly',
			'ext_profile'=>'Profilerweiterungen,viewonly',

			'User Einstellungen,title',
			'prefs[output_compression]'=>'Output compression,bool|?Mehr Belastung für den Server, weniger Datentraffic',
			'Maileinstellungen,divider',
			'prefs[emailonmail]'=>'Email bei Brieftaube versenden,bool',
			'prefs[systemmail]'=>'Email bei systemmails versenden,bool',
			'prefs[dirtyemail]'=>'Kein Wortfilter bei Brieftauben,bool',
			'prefs[forward_yom_to_superuser]'=>'Mail an Superuser weiterleiten,int|?-1 setzt das setting außer Kraft, sonst ID eingeben',
			'Farbeinstellungen,divider',
			'prefs[commenttalkcolor]'=>'Kommentarfarbe,color_pick,1,$',
			'prefs[commentemotecolor]'=>'Emote farbe,color_pick,1,$',
			'prefs[disc_commenttalkcolor]'=>'Emote farbe,color_pick,0,$',
			'prefs[disc_commentemotecolor]'=>'Emote farbe,color_pick,0,$',
			'Chateinstellungen,divider',
			'prefs[chat_show_reload]'=>'Zeit bis zum nächsten Reload anzeigen,bool',
			'prefs[chat_show_rest]'=>'Restzeichenanzeige,bool',
			'prefs[preview]'=>'Chat Previews einblenden,bool',
			'prefs[timestamps]'=>'Zeit vor Posts anzeigen,bool',
			'prefs[minimail]'=>'Minimail anzeigen,bool',
			'prefs[nav_help_enabled]'=>'Hilfetext bei Navigationslinks einschalten,bool',
			'prefs[sx0]'=>'Shortcut 1',
			'prefs[sx1]'=>'Shortcut 2',
			'prefs[sx2]'=>'Shortcut 3',

			'prefs[chat_big_input]'=>'Anzahl der Chat-Eingabezeilen,int|?0 bedeutet: eine einzige Zeile',
			'Features an/abschalten,divider',
			'prefs[nocolors]'=>'Farben abschalten,bool',
			'prefs[noimg]'=>'Navigationsbilder deaktivieren,bool',


			'prefs[birthdate_disp]'=>'Geburtsdatum in Bio einblenden,bool',
			'prefs[notall2bank]'=>'Nicht alles zur Bank bringen,int',
			'prefs[taxfrombank]'=>'Steuerbankeinzug verwenden,bool',
			'prefs[nohotkeys]'=>'Hotkeys deaktivieren,bool',
			'prefs[quicknav_enabled]'=>'Soll das Quicknav Feld angezeigt werden?,bool',

			'Textfelder ('.$row2['bio_freetexts_count'].' Felder),divider'
			);
			for ($i = 1; $i <= $row2['bio_freetexts_count']; $i++){
				$userinfo = array_merge($userinfo,array('prefs[tf'.$i.'t]' => 'Feld '.$i));
			}

		$extrainfo = array(
		);


		// END Formular-Array

		// Speichern
		if($_GET['act'] == 'save') {
			$sql1	= "UPDATE `accounts` SET `acctid` = '" . $row['acctid'] . "',";
			$sql2	= "UPDATE account_extra_info SET `acctid` = '" . $row['acctid'] . "',";
			$log	= '';

			//edit by bathi
			$_POST['msg_chars'] = explode("\n", trim($_POST['msg_chars']));
			$clean = array();
			for($i=0;$i<count($_POST['msg_chars']);$i++){if(trim($_POST['msg_chars'][$i]) != ''){$clean[] = $_POST['msg_chars'][$i];}}
			$_POST['msg_chars'] = addslashes(utf8_serialize($clean));
			//bathi end
			
			// Rassenänderung: Boni zurücksetzen
			if($row['race'] != $_POST['race']) {
				$arr_change = $_POST;
				// Bisherige Rasse!
				$str_newrace = $_POST['race'];
				$arr_change['race'] = $row['race'];
				// Alte Boni abnehmen
				race_set_boni(true,true,$arr_change);
				// Neue Boni verteilen
				$arr_change['race'] = $str_newrace;
				race_set_boni(true,false,$arr_change);
				$_POST = $arr_change;
			}

			// Auf gleiche Logins checken
			if( $_POST['login'] != $row['login'] && db_num_rows(db_query('SELECT acctid FROM accounts WHERE LOWER(login)="'.addstripslashes(mb_strtolower($_POST['login'])).'"')) ) {
				$_POST['login']	= $row['login'];
			}

			// Bei Namensänderung ein bißchen aufpassen
			// cname und Login müssen bis auf Farbcodes identisch sein
			if(strip_appoencode($_POST['cname'],3) != $_POST['login']) {

				// Ansonsten Farbname weg
				$_POST['cname'] = '';

			}

			// Jetzt noch Gesamtnamen korrekt setzen
			// Muss vor saveuser kommen, da beim Sessionuser noch Änderungen vorgenommen werden!
			if(mb_substr($_POST['name'],0,34) != 'Neuling mit unzulässigem Namen Nr.') {
				$_POST['name'] = user_set_name($_GET['userid'],false,$_POST);
			}

			// Sonderrechte speichern
			if($access_control->su_check(access_control::SU_RIGHT_RIGHTS)) {
				foreach($_POST['surights'] as $key=>$r) {
					if($r == -1) {
						unset($_POST['surights'][$key]);
					}
				}

				if(sizeof($_POST['surights']) > 0) {
					$_POST['surights'] = addslashes(utf8_serialize(user_set_surights($_POST['surights'],$ugrp_rights)));
				}
				else {
					$_POST['surights'] = '';
				}
				// Zu Formular-Schablone hinzufügen
				$userinfo['surights'] = true;
			}

			if(sizeof($_POST['prefs']) > 0)
			{
				$arr_user_prefs = utf8_unserialize($row['prefs']);
				foreach ($arr_user_prefs as $key => $val)
				{
					if(!array_key_exists($key,$_POST['prefs']))
					{
						$_POST['prefs'][$key] = $arr_user_prefs[$key];
					}
					elseif ($_POST['prefs'][$key] != $val)
					{
						$log .= '[Einstellung] ' . $key . ' = ' . $_POST['prefs'][$key] . ' (davor: ' . $arr_user_prefs[$key] . '),`n';
					}
				}
				$_POST['prefs'] = addslashes(utf8_serialize($_POST['prefs']));
			}
			else
			{
				unset($_POST['prefs']);
			}

			foreach($_POST as $key=>$val)
			{
				if(isset($row[$key]))
				{
					if ($row[$key] != stripslashes($val))
					{
						$sql1	.= $key . " = '".addstripslashes($val)."',";
						if ($key == 'prefs') continue;
						$log	.= $key . ' = ' . $val . ' (davor: ' . (is_array($row[$key])?print_r($row[$key],true):stripslashes($row[$key])) . '),`n';
					}
				}
				elseif ($key=="newpassword" )
				{
					if (!empty($val))
					{
						$sql1	.= "password = '".CCrypt::make_password_hash($val)."', ";
						$log	.= 'Neues Passwort,`n';
					}
				}
				elseif (isset($row2[$key]) && $row2[$key] != stripslashes($val))
				{
					$sql2	.= $key . " = '".addstripslashes($val)."',";
					$log	.= $key . ' = ' . $val . ' (davor: ' . stripslashes($row2[$key]) . '),`n';
				}

			}
			$sql1=mb_substr($sql1,0,mb_strlen($sql1)-1);
			$sql2=mb_substr($sql2,0,mb_strlen($sql2)-1);
			$sql1.=' WHERE acctid='.$_GET['userid'];
			$sql2.=' WHERE acctid='.$_GET['userid'];

			if (mb_strlen($log)>3) $log = mb_substr($log, 0, mb_strlen($log)-3);
			debuglog('Useredit - Editierte User`n(Setzte: ' . $log . ')',$_GET['userid']);
			systemlog('Useredit - Editierte User',$session['user']['acctid'],$_GET['userid']);

			//we must manually redirect so that our changes go in to effect *after* our user save.
			addnav('','su_petitions.php?op=view&id='.$_GET['returnpetition']);
			addnav('','user.php');

			saveuser();

			db_query($sql1);
			db_query($sql2);
			//Cache::delete(Cache::CACHE_TYPE_MEMORY,'user_data_'.$_GET['userid']);

			if (!empty($_GET['returnpetition'])){
				header('Location: su_petitions.php?op=view&id='.$_GET['returnpetition']);
			}
			else{
				header('Location: user.php');
			}

			exit();
		}
		// END Speichern

		$userinfo = adv_array_merge($userinfo,$extrainfo,$surights);
		debuglog('`&Benutzer '.$row['name'].'`& im Usereditor geöffnet.');


		foreach($access_control as $r=>$v) {

			if(isset($row['surights'][$r])) {
				$row['surights['.$r.']'] = $row['surights'][$r];
				unset($row['surights'][$r]);
			}
			else {
				$row['surights['.$r.']'] = -1;
			}

		}


		$arr_user_prefs = utf8_unserialize($row['prefs']);

		foreach($arr_user_prefs as $r=>$v)
		{
			$row['prefs['.$r.']'] = $arr_user_prefs[$r];
		}
	
		$row = adv_array_merge($row,$row2,$arr_user_prefs);
		
		//edit by bathi
		$row['msg_chars'] = implode("\n",$msgChars);
		//bathi end
		
		output("<form action='user.php?op=special&amp;userid=".$_GET['userid']."".($_GET['returnpetition']!=""?"&amp;returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
		addnav("","user.php?op=special&userid=".$_GET['userid']."".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
		output("`n`c".$row['name']."`c`n<input type='submit' class='button' name='newday' value='Neuen Tag gewähren'>",true);
		output("<input type='submit' class='button' name='fixnavs' value='Defekte Navs reparieren'>",true);
		if(!empty($row['emailvalidation'])) {
			output("<input type='submit' class='button' name='clearvalidation' value='E-Mail als gültig markieren'>",true);
		}
		if($access_control->su_check(access_control::SU_RIGHT_DEV)) //auf Wunsch der Admins, die Knöpfe braucht man sowieso nur bei Fehlern
		{
			output("<input type='submit' class='button' name='reset_values' value='ATK+DEF Reset (!)'>",true);
		}
		if($access_control->su_check(access_control::SU_RIGHT_RESET_DRAGONPOINTS))
		{
			output("<input type='submit' class='button' name='reset_dragonpoints' value='Heldenpunkte Reset (!)'>",true);
		}
		if($access_control->su_check(access_control::SU_RIGHT_ANONYMIZE_USER))
		{
			output("<input type='submit' class='button' name='anonymize_user' value='User anonymisieren (!)' onClick='return confirm(\"Achtung, der User wird anonymisiert und ist für das System praktisch unbrauchbar!\")'>",true);
		}

		output("</form>",true);

		output("<form action='user.php?op=edit&amp;act=save&amp;userid=".$_GET['userid']."".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
		addnav("","user.php?op=edit&act=save&userid=".$_GET['userid']."".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
		addnav("","user.php?op=edit&userid=".$_GET['userid']."".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");

		editnav();


		output("<input type='submit' class='button' value='Speichern'>",true);
		showform($userinfo,$row);
		output("</form>",true);
		addnav("","user.php?op=lasthit&userid=".$_GET['userid']);

	break;	// END edit

	case 'special':

		if ($_POST['newday']!='')
		{
			user_update(
				array
				(
					'lasthit'=>date('Y-m-d H:i:s',strtotime(date('r').'-'.(86500/getsetting('daysperday',4)).' seconds'))
				),
				(int)$_GET['userid']
			);
		}
		elseif($_POST['fixnavs']!='')
		{
			user_update(
				array
				(
					'allowednavs'=>'',
					'output'=>'',
					'restorepage'=>'',
					'specialinc'=>'',
					'pqtemp'=>'',
					'specialmisc'=>'',
				),
				(int)$_GET['userid']
			);
		}
		elseif($_POST['clearvalidation']!='')
		{
			user_update(
				array
				(
					'emailvalidation'=>'',
				),
				(int)$_GET['userid']
			);
		}
		elseif ($_POST['reset_values'])
		{

			$sql = 'SELECT dragonpoints,weapondmg,armordef,level,race FROM accounts WHERE acctid='.(int)$_GET['userid'];
			$arr_tmp = db_fetch_assoc(db_query($sql));

			$arr_dp = utf8_unserialize($arr_tmp['dragonpoints']);

			$arr_tmp['attack'] = $arr_tmp['weapondmg'] + $arr_tmp['level'];
			$arr_tmp['defence'] = $arr_tmp['armordef'] + $arr_tmp['level'];

			if(is_array($arr_dp)) {
				foreach($arr_dp as $key=>$val)
				{
					if ($val=='atk' || $val == 'at')
					{
						$arr_tmp['attack']++;
					}
					if ($val=='def' || $val == 'de')
					{
						$arr_tmp['defence']++;
					}
				}
			}

			if(!empty($arr_tmp['race'])) {
				$arr_race = race_get($arr_tmp['race'],true);
				race_set_boni(true,false,$arr_tmp);
			}

			debuglog('setzte ATK (='.$arr_tmp['attack'].') + DEF (='.$arr_tmp['defence'].') zurück für',$_GET['userid']);

			user_update(
				array
				(
					'attack'=>$arr_tmp['attack'],
					'defence'=>$arr_tmp['defence'],
				),
				(int)$_GET['userid']
			);
		}
		elseif ($_POST['reset_dragonpoints']) {

			$sql = 'SELECT dragonpoints,attack,defence,maxhitpoints,level,weapondmg,armordef FROM accounts WHERE acctid='.$_GET['userid'];
			$arr_tmp = db_fetch_assoc(db_query($sql));

			$arr_dp = utf8_unserialize($arr_tmp['dragonpoints']);

			if(is_array($arr_dp)) {
				foreach ($arr_dp as $key=>$val) {

					if($val == 'atk' || $val == 'at') {
						$arr_tmp['attack']--;
					}
					if($val == 'def' || $val == 'de') {
						$arr_tmp['defence']--;
					}
					if($val == 'hp') {
						$arr_tmp['maxhitpoints'] -= 5;
					}

				}
			}

			$arr_tmp['attack'] = max($arr_tmp['attack'],$arr_tmp['level']+$arr_tmp['weapondmg']);
			$arr_tmp['defence'] = max($arr_tmp['defence'],$arr_tmp['level']+$arr_tmp['armordef']);
			$arr_tmp['maxhitpoints'] = max($arr_tmp['maxhitpoints'],5*$arr_tmp['level']);

			debuglog('setzte Heldenpunkte zurück, ATK(='.$arr_tmp['attack'].'), DEF (='.$arr_tmp['defence'].'), HP (='.$arr_tmp['maxhitpoints'].') für',$_GET['userid']);

			$arr_tmp['dragonpoints'] = array();

			// User kurz ausloggen..
			user_update(
				array
				(
					'loggedin'=>0,
					'dragonpoints'=>$arr_tmp['dragonpoints'],
					'attack'=>$arr_tmp['attack'],
					'defence'=>$arr_tmp['defence'],
					'maxhitpoints'=>$arr_tmp['maxhitpoints'],
					'lasthit'=>'0000-00-00 00:00:00'
				),
				(int)$_GET['userid']
			);
		}
		elseif ($_POST['anonymize_user'])
		{
			$rand = md5(microtime());

			// User kurz ausloggen..
			user_update(
				array
				(
					'emailaddress'=>$rand,
					'lastip'=>$rand,
					'lasthit'=>'0000-00-00 00:00:00',
					'uniqueid'=>$temp,
					'output'=>'',
					'password'=>$rand
				),
				(int)$_GET['userid']
			);
			debuglog('Anonymisierte User',$_GET['userid']);
		}


		if (empty($_GET['returnpetition']))
		{
			$str_lnk = 'user.php?op=edit&userid='.$_GET['userid'];
		}
		else
		{
			$str_lnk = 'su_petitions.php?op=view&id='.$_GET['returnpetition'];
		}
		// Von Hand weiterleiten
		addnav('',$str_lnk);
		saveuser();

		header('Location:'.$str_lnk);
		exit();

	break;	// END special

	case 'vacationmode':
		{
			$int_uid = (int)$_GET['userid'];
			clearoutput();
			addnav('Zurück zum Useredit','user.php?op=edit&userid='.$int_uid);

			switch ($_GET['act'])
			{
				default:
				case '':
					{
						$user = new CCharacter($int_uid);
						$arr_vacationmode = db_get('SELECT * FROM bans WHERE loginfilter="'.$user->login.'" AND banreason LIKE "%Urlaubsmodus%"');

						$str_out .= get_title('Urlaubsmodus');
						$str_out .= 'Du kannst einen User hier in den Urlaubsmodus versetzen oder ihn von dort aus wieder reaktivieren.';

						if($arr_vacationmode === null)
						{
							$arr_form = array(
								'days'	=> 'Wieviele Tage soll der User im Urlaubsmodus bleiben?,int'
							);
							$str_out .= form_header($str_filename.'?op=vacationmode&act=set&userid='.$int_uid);
							$str_out .= generateform($arr_form,array());
							$str_out .= form_footer();
						}
						else
						{
							$str_out .= '`n`$Der User befindet sich bereits im Urlaubsmodus und würde erst am '.$arr_vacationmode['banexpire'].' wieder Atrahor betreten dürfen. Möchtest du ihn von dort aus wieder zurückholen?`0';
							addnav('Ja, zurückholen!',$str_filename.'?op=vacationmode&act=remove&userid='.$int_uid);
						}
						break;
					}
				case 'set':
					{
						$user = new CCharacter($int_uid);
						$user->setVacationmode((int)$_POST['days']);
						setStatusMessage('Der User wurde in den Urlaubsmodus versetzt');
						redirect('user.php?op=edit&userid='.$int_uid);
						break;
					}
				case 'remove':
					{
						$user = new CCharacter($int_uid);
						$user->removeVacationmode();
						setStatusMessage('Der User wurde aus dem Urlaubsmodus reaktiviert');
						redirect('user.php?op=edit&userid='.$int_uid);
						break;
					}
			}
			output($str_out);

			break;
		}

	// Knappeneditor
	case 'disciple':

		$int_uid = (int)$_GET['userid'];
		$int_did = (int)$_POST['id'];

		addnav('Zurück zum Useredit','user.php?op=edit&userid='.$int_uid);

		if($int_did>0)
		{
			$sql = ($int_did == -1 ? 'INSERT INTO ' : 'UPDATE ');
			$sql .= ' disciples
					SET name="'.$_POST['name'].'",state='.$_POST['state'].',oldstate='.$_POST['oldstate'].',extra='.$_POST['extra'].',level='.$_POST['level'].',master='.$int_uid;
			$sql .= ($int_did > -1 ? ' WHERE id='.$int_did : '');
			db_query($sql);

			if(db_affected_rows()) {
				output('`@`b`cKnappe erfolgreich editiert!`c`b`0`n`n');
			}
			else {
				output('`$`b`cKnappe NICHT editiert!`c`b`0`n`n');
			}
		}

		$sql = 'SELECT * FROM disciples WHERE master='.$int_uid;
		$res = db_query($sql);

		if(db_num_rows($res) == 0) {
			$arr_data = array('id'=>-1);
		}
		else {
			$arr_data = db_fetch_assoc($res);
		}

		$str_state_enum = ',-1,tot,0,inaktiv';
		for($i=1;$i<=24;$i++) {
			$str_state_enum .= ','.$i.','.get_disciple_stat($i);
		}

		$arr_form = array(

							'name'=>'Name des Knappen:',
							'state'=>'Aktueller Status des Knappen:,enum'.$str_state_enum,
							'oldstate'=>'Status-Backup:,enum'.$str_state_enum,
							'level'=>'Level des Knappen:,enum_order,0,100',

							'id'=>'ID des Knappen,hidden'
							);

		$str_lnk = 'user.php?op=disciple&userid='.$int_uid;
		addnav('',$str_lnk);
		output('<form method="POST" action="'.utf8_htmlentities($str_lnk).'">',true);

		showform($arr_form,$arr_data,false,'Speichern');

		output('</form>',true);


	break;

	// Runeneditor
	case 'runes':

		$int_uid = (int)$_GET['userid'];

		addnav('Zurück');
		addnav('Zum Useredit','user.php?op=edit&userid='.$int_uid);

		// Runeninfos abrufen
		$res = db_query('SELECT * FROM runes_extrainfo');

		if(!db_num_rows($res)) {
			output('Runen? Was ist das?!');
			page_footer();
			exit();
		}

		$str_out = '';

		if(isset($_POST['save'])) {

			$arr_tmp['runes_ident'] = array();
			if( is_array($_POST['runes']) ){
				foreach ($_POST['runes'] as $id) {
					$arr_tmp['runes_ident'][$id] = true;
				}
			}

			$arr_tmp['runes_ident'] = addslashes(utf8_serialize($arr_tmp['runes_ident']));

			user_set_aei($arr_tmp,$int_uid);

			$str_out .= '`n`@Erfolgreich gespeichert!`0`n`n';

		}

		$lres = db_query('	SELECT DISTINCT i.value2 AS id
							FROM items i
							LEFT JOIN items_tpl it ON it.tpl_id = i.tpl_id
							LEFT JOIN account_extra_info aei ON aei.acctid = i.owner
							WHERE it.tpl_class = "'.getsetting('runes_classid',19).'"
							AND i.tpl_id <> "r_dummy"
							AND NOT INSTR( aei.runes_ident, CONCAT( ":", it.tpl_value2, ";" ) )
							AND i.owner = '.$int_uid.'');
		$lost = array();
		while( $l = db_fetch_assoc($lres) ){
			$lost[ $l['id'] ] = true;
		}

		$arr_tmp = user_get_aei('runes_ident',$int_uid);
		$arr_tmp['runes_ident'] = utf8_unserialize($arr_tmp['runes_ident']);

		$str_lnk = 'user.php?op=runes&userid='.$int_uid;
		addnav('',$str_lnk);
		$str_out .= '`n`c`bIdentifizierte Runen dieses Benutzers:`b`n`n
					`$`b*`b`& = Besitzt Rune im identifizierten Zustand, hat aber das Wissen nicht!`0
					<form method="POST" action="'.utf8_htmlentities($str_lnk).'"><input type="hidden" value="1" name="save">';
		$str_out .= '<table>';
		$int_row = 0;
		$bool_lostthis=false;
		while($r = db_fetch_assoc($res))
		{
			if( !$int_row )
			{
				$str_out .= '<tr>';
			}
			$bool_lostthis = $lost[ $r['id'] ];
			$str_out .= '<td><input type="checkbox" name="runes[]" value="'.$r['id'].'" '.($arr_tmp['runes_ident'][$r['id']] ? 'checked="checked"':'').' /></td><td>'.($bool_lostthis?'`$`b':'`&').$r['name'].' (id: '.$r['id'].')'.($bool_lostthis?'`b':'').'`0</td>';
			$int_row++;
			if( $int_row==4 )
			{
				$str_out .= '</tr>'; $int_row=0;
			}

		}

		$str_out .= '</table><input type="submit" value="Speichern!" />
					</form>`n`n`c'.plu_mi('runes_check',0,false).'`bCheck:`b`n<div style="text-align: left; width: 250px; display:none;" id="'.plu_mi_unique_id('runes_check').'">'.nl2br(str_replace(' ','&nbsp;',print_r($arr_tmp['runes_ident'],true))).'</div>
					';
		output($str_out,true);

	break;

	case 'logoff':

		$id = (int)$_GET['userid'];
		user_update(
			array
			(
				'loggedin'=>0,
				'lasthit'=>0
			),
			$id
		);

		addnav('User Info bearbeiten','user.php?op=edit&userid=$id');

		output("Der User wurde ausgelogged!");

	break;	// END logoff

	default:	// Standardanzeige



	break;	// END default

}	// END Main-Switch (op)

if (isset($_GET['page']))
{
	$str_output = '';
	$order = 'acctid';
	if (!empty($_GET['sort']))
	{
		$order = $_GET['sort'];
	}
	$offset=(int)$_GET['page']*100;
	//$sql = "SELECT acctid,login,name,level,laston,lastip,uniqueid,emailaddress,activated FROM accounts ".($where>""?"WHERE $where ":"")."ORDER BY \"$order\" LIMIT $offset,100";

	//public static function getChars($str_search_txt,$str_what = '`a.acctid`,`login`,`name`',$arr_search_in = array(), $str_where_addition = '', $str_order_by = '', $str_limit = '0')

	$arr_users = CCharacter::getChars($_POST['q'],'acctid,login,name,level,laston,lastip,uniqueid,emailaddress,activated',
		array(
			'a.acctid'	=> array('type'=>CCharacter::SEARCH_EXACT , 'mode'=> null, 'open_bracket' => false, 'close_bracket' => false),
			'login' 	=> array('type'=>CCharacter::SEARCH_SOUNDEX  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
			'name' 		=> array('type'=>CCharacter::SEARCH_LIKE_EXT  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
			'emailaddress' 		=> array('type'=>CCharacter::SEARCH_LIKE  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
			'lastip' 		=> array('type'=>CCharacter::SEARCH_LIKE  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
			'uniqueid' 		=> array('type'=>CCharacter::SEARCH_LIKE  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false),
		),
		'',$order,$offset.',100'
	);

	$str_output .= "
	<table>
		<tr>
		<td>Ops</td>
		<td><a href='user.php?sort=login'>Login</a></td>
		<td><a href='user.php?sort=name'>Name</a></td>
		<td><a href='user.php?sort=acctid'>ID</a></td>
		<td><a href='user.php?sort=level'>Lev</a></td>
		<td><a href='user.php?sort=laston'>Zuletzt da</a></td>
		<td><a href='user.php?sort=lastip'>IP</a></td>
		<td><a href='user.php?sort=uniqueid'>ID</a></td>
		<td><a href='user.php?sort=emailaddress'>E-Mail</a></td>
		</tr>";

	addpregnav("/user.php\?sort=(login|name|acctid|level|laston|lastip|uniqueid)/");

	$rn=0;
	$int_count = count($arr_users);
	foreach ($arr_users as $row)
	{
		$loggedin=user_get_online(0,$row,true);
		$laston=round((strtotime(date('r'))-strtotime($row['laston'])) / 86400,0).' Tage';
		if (mb_substr($laston,0,2)=='1 ')
		{
			$laston='1 Tag';
		}
		if (date('Y-m-d',strtotime($row['laston'])) == date('Y-m-d'))
		{
			$laston='Heute';
		}
		if (date('Y-m-d',strtotime($row['laston'])) == date('Y-m-d',strtotime(date('r').'-1 day')))
		{
			$laston='Gestern';
		}
		if ($loggedin)
		{
			$laston='Jetzt';
		}
		$row['laston']=$laston;
		if ($row[$order]!=$oorder)
		{
			$rn++;
		}
		$oorder = $row[$order];
		$str_output .= "<tr class='".($rn%2?"trlight":"trdark")."'>";

		$str_output .= "<td>";

		//ADDED LOG OFF HERE
		$str_output .= "[<a href='user.php?op=edit&amp;userid=".$row['acctid']."'>Edit</a>|"
				.create_lnk('Del','su_delete.php?ids[]='.$row['acctid'].'&ret='.urlencode(calcreturnpath()) ).'|'.
				create_lnk('Ban','su_bans.php?op=edit_ban&ids[]='.$row['acctid'].'&ret='.urlencode(calcreturnpath()) ).'|'.
				create_lnk('Logs','su_logs.php?op=search&type=debuglog&account_id='.$row['acctid']).'|'.
				'|'.
				"<a href='user.php?op=logoff&amp;userid=".$row['acctid']."'>Logout</a>]";
		addnav("","user.php?op=edit&userid=".(int)$row['acctid']);
		addnav("","user.php?op=setupban&userid=".(int)$row['acctid']);
		//ADDED LOG OFF HERE

		addnav("","user.php?op=logoff&userid=".(int)$row['acctid']);

		$str_output .= "</td><td>";
		$str_output .= $row['login'];
		$str_output .= "</td><td>";
		$str_output .= $row['name'];
		$str_output .= "</td><td>";
		$str_output .= $row['acctid'];
		$str_output .= "</td><td>";
		$str_output .= $row['level'];
		$str_output .= "</td><td>";
		$str_output .= $row['laston'];
		$str_output .= "</td><td>";
		$str_output .= $row['lastip'];
		$str_output .= "</td><td>";
		$str_output .= $row['uniqueid'];
		$str_output .= "</td><td>";
		$str_output .= $row['emailaddress'];
		$str_output .= "</td>";

		$str_output .= "</tr>";
	}
	$str_output .= "</table>";
}
output($str_output);
page_footer();
?>
