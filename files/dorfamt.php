<?php
// Dorfamt von Atrahor
// Basiert auf:
// Dorfamt
// Make by Kev
// Make for www.logd.de.to
// 05-09-2004 September
// E-Mail: logd@gmx.net
// Website: www.logd.de.to
// Copyright 2004 by Kev

// Ergänzungen und Erweiterungen von Dragonslayer, Maris, Talion, Ysandre

require_once "common.php";
require_once(LIB_PATH.'board.lib.php');
page_header("Das Rathaus");

$op = $_GET['op'];
switch ($op){

	case 'office_entry': { //Fürstliches Büro
		output('`b`c`^Fü`/rs`ytliches `/Bü`^ro`b`c`n`n
		`^Di`/e T`yür zum Zimmer des Fürsten wird dir von seinem persönlichen Leibwächter geöffnet, der sich zu jeder Zeit im Raum befinden und loyal zu seinem Herren stehen wird, sollte es zu Unstimmigkeiten kommen. Du solltest es dir also gut überlegen, wie du den Fürsten von '.getsetting('townname','Atrahor').',`^ der hinter seinem breiten Schreibtisch aus Eichenholz sitzt, ansprechen wirst. Direkt vor dem Schreibtisch siehst du einen ebenso fein von Hand gearbeiteten Stuhl, der schon für dich bereitsteht. Doch bevor du dich setzt, erlaubst du dir einen raschen Blick durch das Zimmer. Überall stehen Möbel, die auf Hochglanz poliert und reichlich mit Edelsteinen und Schmuck ausgeschmückt sind. Doch das edelste Stück im ganzen Zimmer ist der riesige Kronleuchter, den die Zwerge in langwähriger Arbeit gänzlich aus Kristallen hergestellt h`/ab`^en.`n`n');

		addnav('Informationen');
		addnav('Bisherige Amtshandlungen','dorfamt.php?op=office_history');
		addnav('Fürstengalerie','dorfamt.php?op=office_ancestors');

		if (trim(strip_appoencode(getsetting('fuerst',''),3)) == trim($session['user']['login']) || $access_control->su_check(access_control::SU_RIGHT_VIEW_SYMPATHY_VOTES))
		{
			addnav('Fürstliches');
			addnav('Steuern','dorfamt.php?op=office_taxes');
			addnav('Strafe für Steuersünder','dorfamt.php?op=office_prison');
			addnav('Wanderhändler herbefehlen','dorfamt.php?op=office_vendor');
			addnav('Amtskasse','dorfamt.php?op=office_budget');

		}
		addnav('Zurück');
		addnav('a?zum Rathaus','dorfamt.php');
		addnav('d?zur Stadt','village.php');
		addcommentary();
		viewcommentary('office_sovereign','Sagen',30,'sagt');
		break;
	}

	case 'office_taxes': { //Steuern festsetzen
		$taxrate=getsetting("taxrate",750);
		$doubletax=$taxrate*2;
		$taxchange=getsetting("taxchange",1);
		output('`c`b`&Steuern?`0`b`c
		`nDein Finanzminister flüstert dir zu:
		`n"`2Bedenkt bei Eurer Steuerpolitik stets, dass ein zu hoher Steuersatz unzufriedene Untertanen schafft.
		`nEin zu niedriger Steuersatz hingegen zwingt uns zu Einsparungen. Das Stadtfest könnte dann beispielweise nicht mehr so oft stattfinden, was die Untertanen natürlich auch wieder unzufrieden macht.
		`n
		`nBislang gilt:
		`nNeuankömmlinge (bis Level 4) und Auserwählte zahlen `^keine Steuern`2.
		`nBewohner (Level 5 bis Level 10) zahlen den einfachen Steuersatz. Dieser beträgt derzeit `^'.$taxrate.' Gold`2.
		`nAlteingesessene (Level 11 bis Level 15) zahlen den doppelten Steuersatz. Dieser beträgt `^'.$doubletax.' Gold.`&"
		`n`n`n');
		if ($taxchange==1)
		{
			output('Diesen Monat kannst du den Steuersatz `@noch einmal`& ändern!`n');
			addnav('Ändern');
			addnav('Steuersatz ändern','dorfamt.php?op=office_change_taxes');
		}
		else
		{
			output('Diesen Monat kannst du den Steuersatz `4nicht mehr`& ändern.`n');
			if ($access_control->su_check(access_control::SU_RIGHT_VIEW_SYMPATHY_VOTES))
			{
				addnav('Mods');
				addnav('Änderung zulassen','dorfamt.php?op=mod_taxes');
			}
		}
		addnav('Zurück');
		addnav('Ins Büro','dorfamt.php?op=office_entry');
		break;
	}

	case 'office_change_taxes': { //Steuern ändern
		$taxrate=getsetting('taxrate',750);
		$taxchange=getsetting('taxchange',1);
		$maxtaxes=getsetting('maxtaxes',2000);
		if ($taxchange==1)
		{
			output("<form action='dorfamt.php?op=office_change_taxes2' method='POST'>
			`&Der Steuersatz liegt bei `^".$taxrate."`& Gold.
			`nWie hoch hättest du ihn gern? (Maximal ".$maxtaxes." Gold)`0
			<input id='input' name='amount' size=4 value='$taxrate'>
			<input type='submit' class='button' value='festlegen'>
			`n</form>
			".focus_form_element('input'));
			addnav('','dorfamt.php?op=office_change_taxes2');
			addnav('Doch nicht ändern','dorfamt.php?op=office_entry');
		}
		else
		{
			output('Du kannst diesen Monat den Steuersatz nicht mehr verändern.');
			addnav('Ins Büro','dorfamt.php?op=office_entry');
		}
		break;
	}

	case 'mod_taxes':
		savesetting('taxchange',1);
		redirect('dorfamt.php?op=office_taxes');
		break;

	case 'mod_prison':
		savesetting('prisonchange',1);
		redirect('dorfamt.php?op=office_prison');
		break;

	case 'mod_vendor':
		savesetting('callvendor',getsetting('callvendormax',5));
		redirect('dorfamt.php?op=office_vendor');
		break;

	case 'office_change_taxes2': { //Steuern ändern Kontrolle
		$taxrate=getsetting('taxrate',750);
		$maxtaxes=getsetting('maxtaxes',2000);
		$mintaxes=getsetting('mintaxes','0');

		// Man kann ja nie wissen...
		if ($mintaxes<0)
		{
			$mintaxes=0;
			savesetting('mintaxes','0');
		}

		if ($maxtaxes<$mintaxes)
		{
			$maxtaxes=$mintaxes;
			savesetting('maxtaxes',$mintaxes);
		}

		$_POST['amount']=floor((int)$_POST['amount']);
		if ($_POST['amount']<$mintaxes)
		{
			output('`&Dein Finanzminister schaut dich skeptisch an.`n
					"`2Wollt Ihr etwa Gold verschenken?`&" fragt er ungläubig.');
			addnav('Nochmal','dorfamt.php?op=office_change_taxes');
		}
		else if ($_POST['amount']>$maxtaxes)
		{
			output('`&Dein Finanzminister schaut dich skeptisch an.`n
					"`2Wollt Ihr eine Revolte provozieren?`&" fragt er ungläubig.');
			addnav('Nochmal','dorfamt.php?op=office_change_taxes');
		}
		else if ($_POST['amount']==$taxrate)
		{
			output('`&Dein Finanzminister nick bestätigend.`n
					"`2Damit bliebe also alles beim alten.`&" sagt er.');
			addnav('Ins Büro','dorfamt.php?op=office_entry');
		}
		else
		{
			output('`&Dein Finanzminister fragt nochmal nach.`n
					"`2Seid Ihr Euch sicher, dass Ihr den Steuersatz auf `^'.$_POST['amount'].'
					Gold`2 ändern wollt?`&"');
			addnav('Ja','dorfamt.php?op=office_change_taxes3&amount='.$_POST['amount']);
			addnav('Nein','dorfamt.php?op=office_change_taxes');
		}
		break;
	}

	case 'office_change_taxes3': { //Steuern ändern fertig
		$taxrate=getsetting('taxrate',750);
		$newtax=$_GET['amount'];
		output('`&Der neue Steuersatz beträgt von nun an `^'.$newtax.' Gold`&!');
		savesetting('taxrate',$newtax);
		savesetting('taxchange','0');
		if ($newtax>0)
		{
			$str_msg = $session['user']['name'].'`^ hat heute den Steuersatz auf '.$newtax.'
			Gold '.($newtax>$taxrate?'erhöht':'gesenkt').'.';
		}
		else
		{
			$str_msg = $session['user']['name'].'`^ hat heute die Steuern abgeschafft!';
		}

		addnews($str_msg);
		board_add('fuerst_act',31,0,$str_msg);

		addnav('Ins Büro','dorfamt.php?op=office_entry');
		break;
	}

	case 'office_prison': { //Strafe für Steuerhinterziehung
		$taxprison=getsetting('taxprison',1);
		$prisonchange=getsetting('prisonchange',1);
		output('`c`b`&Steuerhinterziehung`0`b`c
		`n`&Dein Finanzminister raunt dir zu:`n"`2Steuerhinterzieher wandern derzeit ');
		if ($taxprison==0)
		{
			output('nicht ');
		}
		if ($taxprison==1)
		{
			output('für einen Tag ');
		}
		if ($taxprison>1)
		{
			output('für '.$taxprison.' Tage ');
		}
		output('hinter Gitter.
		`nViel zu wenig wenn Ihr mich fragt.`&"
		`n`n`n');
		if ($prisonchange==1)
		{
			output('Diesen Monat kannst du das Strafmaß für Steuerhinterziehung `@noch einmal`& ändern!`n');
			addnav('Ändern');
			addnav('Strafmaß ändern','dorfamt.php?op=office_change_prison');
		}
		else
		{
			output('Diesen Monat kannst du das Strafmaß für Steuerhinterziehung `4nicht mehr`& ändern.`n');
			if ($access_control->su_check(access_control::SU_RIGHT_VIEW_SYMPATHY_VOTES))
			{
				addnav('Mods');
				addnav('Änderung zulassen','dorfamt.php?op=mod_prison');
			}
		}
		addnav('Zurück');
		addnav('Ins Büro','dorfamt.php?op=office_entry');
		break;
	}

	case 'office_change_prison': { //Strafe ändern
		$prisonchange=getsetting('prisonchange',1);
		$maxprison=getsetting('maxprison',2);
		$taxprison=getsetting('taxprison',1);
		if ($prisonchange==1)
		{
			output("<form action='dorfamt.php?op=office_change_prison2' method='POST'>
			`&Das Strafmaß liegt bei `^".$taxprison."`& Tagen Haft. Darüberhinaus wird das Doppelte der hinterzogenen Steuer gepfändet.
			`nWie hoch hättest du das Strafmaß gern? (Maximal ".$maxprison." Tage)`0
			<input id='input' name='amount' size=4 value='$taxprison'>
			<input type='submit' class='button' value='festlegen'>
			`n</form>
			".focus_form_element('input'));
			addnav('','dorfamt.php?op=office_change_prison2');
			addnav('Doch nicht ändern','dorfamt.php?op=office_entry');
		}
		else
		{
			output('Du kannst diesen Monat das Strafmaß für Steuerhinterziehung nicht mehr verändern.');
			addnav('Ins Büro','dorfamt.php?op=office_entry');
		}
		break;
	}

	case 'office_change_prison2': { //Strafe ändern Kontrolle
		$prisonchange=getsetting('prisonchange',1);
		$maxprison=getsetting('maxprison',3);
		$taxprison=getsetting('taxprison',1);

		$_POST['amount']=floor((int)$_POST['amount']);
		if ($_POST['amount']<0)
		{
			output('`&Dein Finanzminister schaut dich skeptisch an.`n
					"`2Wollt Ihr die Verbrecher auch noch belohnen?`&" fragt er ungläubig.');
			addnav('Nochmal','dorfamt.php?op=office_change_prison');
		}
		else if ($_POST['amount']>$maxprison)
		{
			output('`&Dein Finanzminister seufzt.`n
					"`2Das lässt sich mit der allgemeinen Gesetzgebung nicht vereinbaren.`&" sagt er missmutig.');
			addnav('Nochmal','dorfamt.php?op=office_change_prison');
		}
		else if ($_POST['amount']==$taxprison)
		{
			output('`&Dein Finanzminister nickt bestätigend.`n
					"`2Damit bliebe also alles beim Alten.`&" sagt er.');
			addnav('Ins Büro','dorfamt.php?op=office_entry');
		}
		else
		{
			output('`&Dein Finanzminister fragt nochmal nach.`n
					"`2Seid Ihr Euch sicher, dass Ihr das Strafmaß für Steuerhinterziehung auf `^'.$_POST['amount'].'
					Tage`2 ändern wollt?`&"');
			addnav('Ja','dorfamt.php?op=office_change_prison3&amount='.$_POST['amount']);
			addnav('Nein','dorfamt.php?op=office_change_prison');
		}
		break;
	}

	case 'office_change_prison3': { //Strafe ändern fertig
		$taxprison=getsetting('taxprison',1);
		$newprison=$_GET['amount'];
		output('`&Das neue Strafmaß beträgt von nun an `^'.$newprison.' Tage Kerker`&!');
		savesetting('taxprison',$newprison);
		savesetting('prisonchange','0');
		if ($newprison>0)
		{
			$str_msg = $session['user']['name'].'`^ hat heute das Strafmaß für Steuerhinterziehung auf '.$newprison.' Tage Kerker '.($newprison>$taxprison?'erhöht':'gesenkt').'.';
		}
		else
		{
			$str_msg = $session['user']['name'].'`^ hat heute die Kerkerhaft für Steuerhinterziehung abgeschafft!';
		}

		addnews($str_msg);
		board_add('fuerst_act',31,0,$str_msg);

		addnav('Ins Büro','dorfamt.php?op=office_entry');
		break;
	}

	case 'office_vendor': { //Wanderhändler herbefehlen
		output('`c`b`&Wanderhändler`0`b`c
		`nHier kannst du einen Eilboten in die umliegenden Städte schicken und Aeki damit drohen, ihm die Lizenz zu entziehen, wenn er sich nicht augenblicklich in der Stadt sehen lässt.
		`n`n');
		if (getsetting('vendor',0)==1)
		{
			output('Aber da der Wanderhändler derzeit auf dem Markplatz seine Zelte aufgeschlagen hat, würde eine solche Drohung nichts nützen.`n`n');
			addnav('Ins Büro','dorfamt.php?op=office_entry');
		}
		else
		{
			$callvendor=getsetting('callvendor',5);
			if ($callvendor>0)
			{
				output('`&Du kannst dies in deiner derzeitigen Amtszeit noch `^'.$callvendor.'`&mal tun.');
				addnav('Herbeordern');
				addnav('Wanderhändler rufen','dorfamt.php?op=office_call_vendor');
				addnav('Zurück');
				addnav('Ins Büro','dorfamt.php?op=office_entry');
			}
			else
			{
				output('`&Leider hast du dies schon so oft gemacht, dass er es gar nicht mehr einsieht, auf deine Drohungen einzugehen. Im Nachbardorf verdient er sowieso mehr!');
				addnav('Ins Büro','dorfamt.php?op=office_entry');
				if ($access_control->su_check(access_control::SU_RIGHT_VIEW_SYMPATHY_VOTES))
				{
					addnav('Mods');
					addnav('Rufen zulassen','dorfamt.php?op=mod_vendor');
				}
			}
		}
		break;
	}

	case 'office_call_vendor': { //Wanderhändler rufen fertig
		$callvendor=getsetting('callvendor',5);
		output('`&Dein schnellster Bote macht sich auf den Weg und schleift den Wanderhändler mitsamt seinem Gerümpel auf den Marktplatz.`n`n');
		$callvendor--;
		savesetting('callvendor',$callvendor);
		savesetting('vendor',1);

		board_add('fuerst_act',31,0,$session['user']['name'].'`2 ruft den Wanderhändler herbei!');

		addnav('Ins Büro','dorfamt.php?op=office_entry');
		break;
	}

	case 'office_budget': { //Amtskasse
		$party=getsetting('min_party_level', 500000);
		$stone=getsetting('paidgold','0');
		$stonemax=getsetting('beggarmax','25000');
		$budget=getsetting('amtskasse','0');
		$amtsgems=getsetting('amtsgems','0');
		$lurevendor=getsetting('lurevendor','40000');
		$freeorkburg=getsetting('freeorkburg','30000');
		output('`n`2Die Amtskasse ist mit `^'.$budget. ' `2Goldstücken gefüllt.`n
				Die Truhen fassen maximal `^'.getsetting('maxbudget','2000000').' `2Gold.`n`n
				In den Tresoren lagern `^'.$amtsgems.' `2Edelsteine.`n
				Maximal fassen die Tresore `^'.getsetting('maxamtsgems','100').' `2Edelsteine.`n`n`n`n
				Auf dem Bettelstein sind derzeit `^'.$stone.' `2Gold hinterlegt.`n
				Sein Fassungsvermögen beträgt `^'.$stonemax.' `2Gold.`n`n
				Den Weg zur Orkburg freizuräumen kostet `^'.$freeorkburg.' `2Gold.`n
				Du kannst den Wanderhändler für `^'.$lurevendor.' `2Gold herlocken.`n
				Ein Stadtfest kostet `^'.$party.' Gold`2.`n`n');
		if ($budget>=$party)
		{
			addnav('Stadtfest');
			addnav('Stadtfest ausrichten','dorfamt.php?op=office_budget_party',false,false,false,false);
		}
		if ($budget>=$lurevendor)
		{
			addnav('Wanderhändler');
			addnav('Herlocken','dorfamt.php?op=office_budget_lurevendor');
		}
		if ($budget>=$freeorkburg)
		{
			addnav('Weg zur Orkburg');
			addnav('Freilegen lassen','dorfamt.php?op=office_budget_orkburg');
		}
		if ($budget>=5000)
		{
			addnav('Auf den Bettelstein');
			addnav('5000 Gold','dorfamt.php?op=office_budget2&amount=5000');
			if ($budget>=10000)
			{
				addnav('10000 Gold','dorfamt.php?op=office_budget2&amount=10000');
			}
		}
		if($budget > 0 || $amtsgems > 0) {
			addnav('Belohnung');
			if($budget > 0) {
				addnav('Gold','dorfamt.php?op=office_donate&what=gold');
			}
			if($amtsgems > 0) {
				addnav('Edelsteine','dorfamt.php?op=office_donate&what=gems');
			}
		}
		else
		{
			addnav('Wir sind pleite!');
		}

		$selledgems=getsetting('selledgems',0);
		$costs=(4000-3*$selledgems);
		if (($budget>=$costs && $selledgems>0) || ($amtsgems>0 && $selledgems<100))
		{
			addnav('Edelsteine');
			if ($budget>=$costs && $selledgems>0)
			{
				addnav('Kaufen','dorfamt.php?op=office_budget_buygems');
			}
			if ($amtsgems>0 && $selledgems<100)
			{
				addnav('Verkaufen','dorfamt.php?op=office_budget_sellgems');
			}
		}

		addnav('Zurück');
		addnav('Ins Büro','dorfamt.php?op=office_entry');
		break;
	}

	case 'office_history': { //bisherige Amtshandlungen
		output('`yAn einer eigens dafür aufgestellten Wand verkündet das fürstliche Büro die bisherigen Amtshandlungen:`n`n');
		board_view('fuerst_act',0,'','Bisher wurden in dieser Amtsperiode keine fürstlichen Dekrete vernommen.',false,false,false,false);
		addnav('Zurück');
		addnav('Ins Büro','dorfamt.php?op=office_entry');
		break;
	}

	case 'office_ancestors': { //Fürstengalerie
		$str_recent = stripslashes(getsetting('fuerst',''));
		$sql = 'SELECT gamedate,msg
			FROM history
			WHERE msg LIKE "%Fürst%von '.getsetting('townname','Atrahor').':%"
			AND acctid=0 AND guildid=0
			ORDER BY gamedate ASC';
		$res = db_query($sql);
		// -1, um Amtsinhaber auszusortieren
		$int_count = db_num_rows($res) - 1;
		output('`c`b`&Fürstliche Galerie`0`b`c`n`n');
		if($int_count <= 0) {
			output('`yIm Vorraum des fürstlichen Büros ist offenbar an der holzgetäfelten Wand Platz freigehalten, um dort einmal die Porträts der Amtsinhaber ihren würdigen Platz finden zu lassen.
					Doch da es in der Vergangenheit keine Fürstin oder Fürsten in '.getsetting('townname','Atrahor').' gab, empfängt dich diese Wand nur mit gähnender Leere.`n`n');
		}
		else {
			output('`yIm Vorraum des fürstlichen Büros sind an der holzgetäfelten Wand in einer mehr oder minder langen Reihe die Porträts all jener Bürger aufgereiht, welche
			in der Vergangenheit bisher insgesamt `&'.$int_count.'mal`y das Fürstenamt bekleidet haben:
			`n`n`0');
			$str_out = '<table>';
			$str_startdate = '';
			$str_msg = '';
			// AKtueller Fürst entfällt in dieser Schleife automatisch
			while($f = db_fetch_assoc($res)) {

				// Letzten abschließen
				if(!empty($str_startdate)) {
					$str_out.='<tr><td>`&`iAb `b'.getgamedate($str_startdate).'`b`i`0</td>'.
					//<td>`i`& bis `b'.getgamedate($f['gamedate']).'`b`i</td>
					'<td> - '.$str_msg.' `0</td></tr>
					<tr>
					<td>&nbsp;</td>
					</tr>';
				}

				$str_startdate = $f['gamedate'];
				$str_msg = $f['msg'];
			}
			output($str_out.'</table>',true);
		}
		addnav('Zurück');
		addnav('Ins Büro','dorfamt.php?op=office_entry');
		break;
	}

	case 'office_donate': { //Gold verschenken
		output('`c`b`&Belohnung aus der Amtskasse`0`b`c`n');
		$int_donations = getsetting('fuerst_donations','0');
		$int_max_donations = 100000;
		$int_gem_factor = 2500;

		if($_GET['act'] == 'finished') {
			output($session['office_donate_msg']);
			unset($session['office_donate_msg']);
			addnav('Zurück');
			addnav('Ins Büro','dorfamt.php?op=office_entry');
			page_footer();
			exit();
		}

		$int_acctid = (int)$_REQUEST['acctid'];
		$str_what = ($_GET['what'] == 'gems' ? 'gems' : 'gold');
		$str_whatname = ($str_what == 'gems' ? 'Edelsteine' : 'Gold');

		$str_lnk = 'dorfamt.php?op=office_donate&what='.$str_what;
		addnav('',$str_lnk);
		output('<form method="POST" action="'.utf8_htmlentities($str_lnk).'">');

		// AcctID ist gegeben, Menge eingeben
		if(!empty($int_acctid)) {

			// Account abrufen
			$sql = 'SELECT gold,gems,level,dragonkills,login,name,acctid FROM accounts WHERE acctid='.$int_acctid;
			$res = db_query($sql);
			if(!db_num_rows($res)) {

			}
			$arr_target = db_fetch_assoc($res);

			// Vorräte abrufen
			if($str_what == 'gems') {
				$int_available = (int)getsetting('amtsgems',0);
				$int_max_amount = 3;
			}
			else {
				$int_available = (int)getsetting('amtskasse',0);
				$int_max_amount = 2000;
			}

			$int_amount = (int)$_POST['amount'];

			// Menge gegeben
			if(!empty($int_amount)) {

				// Validieren
				if($int_amount > $int_max_amount) {
					output('`$Nana, solche Mengen wären reichlich ungerecht gegenüber der hart arbeitenden Arbeiterklasse!`n`n');
				}
				elseif($int_amount > $int_available) {
					output('`$Wo soll das denn bitte herkommen? Aus deiner privaten Tasche?`n`n');
				}
				elseif($int_amount <= 0) {
					output('`$Was du auch versuchst, irgendwie macht Null '.$str_whatname.' nicht viel Sinn..`n`n');
				}
				elseif($int_acctid == $session['user']['acctid']) {
					output('`$Schummler! Elender! Sei froh, dass die Götter heute gute Laune haben.. Dich selbst beschenken zu wollen.. also wirklich.`n`n');
				}
				else {

					if($int_amount == 1 && $str_what == 'gems') {
						$str_whatname = 'Edelstein';
					}

					debuglog(' vergab durch Fürstenamt '.$int_amount.' '.$str_whatname.' an ',$int_acctid);
					systemmail($int_acctid,'`2Belohnung vom `^Fürstenamt`2!',
					$session['user']['name'].'`2 hat dir soeben eine fürstliche Belohnung in Höhe von `^'.$int_amount.'
							'.$str_whatname.'`2 aus der Amtskasse zukommen lassen!');

					user_update(
					array
					(
					$str_what=>array('sql'=>true, 'value'=>"$str_what + $int_amount")
					),
					$int_acctid
					);

					board_add('fuerst_act',31,0,$session['user']['name'].'`^ hat soeben '.$arr_target['name'].'`^ '.$int_amount.' '.$str_whatname.' aus der Amtskasse zukommen lassen!');

					if($str_what == 'gems') {
						savesetting('amtsgems',$int_available-$int_amount);
					}
					else {
						savesetting('amtskasse',$int_available-$int_amount);
					}

					$int_donations += $int_amount * ($str_type == 'gems' ? $int_gem_factor : 1); //$str_type sollte $str_what sein, aber ist nun hinnfällig

					savesetting('fuerst_donations',$int_donations);

					// Fertig: redirect auf Meldung
					$session['office_donate_msg'] = '`2Du übergibst deinem fürstlichen Boten den Auftrag, '.$int_amount.' '.$str_whatname.' '.$arr_target['name'].'`2 zu überreichen. Sofort eilt dieser los, um deinen Auftrag auszuführen!';
					redirect('dorfamt.php?op=office_donate&act=finished');
				}
			}

			addnav('Jemand anderes verdient es eher..','dorfamt.php?op=office_donate');

			// Sonst: Eingabefeld
			output('`2Nun, du willst also ein Geschenk an '.$arr_target['name'].'`2 vergeben. Wieviel darf\'s denn sein?
				`n`nAnzahl an '.$str_whatname.' (maximal '.$int_max_amount.', in der Amtskasse liegen zur Zeit '.$int_available.' '.$str_whatname.'):`0
				<input type="text" maxlength="4" size="4" name="amount" id="amount" value="'.$int_amount.'">
				<input type="hidden" name="acctid" value="'.$int_acctid.'">`n`n
				<input type="submit" value="Vergeben!">',true);

		}
		// Sonst: Suchformular
		else {

			$str_search_in = stripslashes($_POST['search']);

			// Suchwort gegeben
			if(mb_strlen($str_search_in) > 2) {

				$str_search = str_create_search_string($str_search_in);

				$sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$str_search.'" ORDER BY IF(login="'.db_real_escape_string($str_search_in).'",1,0) DESC, name ASC LIMIT 100';
				$res = db_query($sql);

				if(!db_num_rows($res)) {
					output('`n`2Es wurden keine Bürger gefunden, die auf deine Eingabe passen!');
					addnav('Lass mich nochmal suchen!','dorfamt.php?op=office_donate');
				}
				else {
					$str_out = '`2Folgende Bürger passen auf deine Eingabe:`0
							<select name="acctid">';
					while($a = db_fetch_assoc($res)) {
						$str_out .= '<option value="'.$a['acctid'].'">'.strip_appoencode($a['name']).'</option>';
					}
					$str_out .= '</select>
							<input type="submit" value="Genau den mein ich!">';
					output($str_out,true);
				}

			}
			// Sonst: Suchfeld
			else {

				output('`2Welchen Bürger '.getsetting('townname','Atrahor').'s willst du mit '.$str_whatname.' aus der Amtskasse bedenken?
				`n`n`0<input type="text" maxlength="40" size="20" name="search" id="search">`n`n
				<input type="submit" value="Suchen!">',true);

			}

		}

		output('</form>',true);
		addnav("Zurück");
		addnav("Ins Büro","dorfamt.php?op=office_entry");
		break;
	}

	case 'office_budget2': { //Gold auf den Bettelstein
		$amount=$_GET['amount'];
		$budget=getsetting('amtskasse' ,0);
		$stone=getsetting('paidgold','0');
		$max=getsetting('beggarmax','25000');
		if ($budget>=$amount)
		{
			if ($stone+$amount>$max)
			{
				$amount=$max-$stone;
				output('`2Der Bettelstein kann leider nur `^'.$max.'`2 Gold fassen.`n');
				if ($amount>0)
				{
					output('`2Also transferierst du lediglich `^'.$amount.'`2 Gold!');
				}
				else
				{
					output('`2Demnach kannst du auch nichts mehr auf ihn transferieren.');
				}
			}
			else
			{
				output('`2Du transferierst `2'.$amount.'}`^ Gold auf den Bettelstein.');
			}

			if ($amount>0)
			{
				$str_msg = '`@Armenspeisung!`& '.$session['user']['name'].'`2 hat soeben `^'.$amount.'`2 Gold auf den Bettelstein transferiert.';
				addnews($str_msg);
				board_add('fuerst_act',31,0,$str_msg);
				savesetting('amtskasse',$budget-$amount);
				savesetting('paidgold',$stone+$amount);
			}
		}
		else
		{
			output('Hoppla, das können wir uns aber gerade überhaupt nicht leisten.');
		}
		addnav('Zurück');
		addnav('Zur Kasse','dorfamt.php?op=office_budget');
		break;
	}

	case 'office_budget_party': { //Dorffest ausrichten
		$amtskasse = getsetting('amtskasse', 0);
		$min_party_level = getsetting('min_party_level', 500000);
		$lastparty = getsetting('lastparty', 0);
		$party_duration= getsetting('party_duration', 1);
		if ($amtskasse>=$min_party_level)
		{
			savesetting('amtskasse',$amtskasse- $min_party_level);
			savesetting('lastparty',time()+86400*$party_duration);
			output('`2So sei es! Möge das Stadtfest beginnen!');
			$str_msg = $session['user']['name'].' `^ hat heute ein Stadtfest veranstaltet!';
			addnews($str_msg);
			board_add('fuerst_act',31,0,$str_msg);
		}
		else
		{
			output('Hoppla, das können wir uns aber gerade gar nicht leisten.');
		}
		addnav('Zurück');
		addnav('Zur Kasse','dorfamt.php?op=office_budget');
		break;
	}

	case 'office_budget_lurevendor': { //Wanderhändler herbeilocken
		$budget=getsetting('amtskasse' ,0);
		$lurevendor=getsetting('lurevendor','40000');
		$vendor=getsetting('vendor','0');
		if ($budget>=$lurevendor)
		{
			if ($vendor==1)
			{
				output('`2Nicht nötig, er ist doch schon da.`n
					Oder willst du ihm etwa die hart verdienten Steuergelder auch noch in den Rachen werfen?`n`n');
			}
			else
			{
				output('`2Du schickst deinen schnellsten Boten in die Nachbardörfer und bietest dem Wanderhändler `^'.$lurevendor.'`2 Gold an, wenn er sich sofort auf deinem Marktplatz blicken lässt.`n
						Das Angebot lässt er sich natürlich nicht zweimal machen.');
				savesetting('amtskasse',$budget-$lurevendor);
				savesetting('vendor','1');

				$str_msg = $session['user']['name'].' `^ hat den Wanderhändler in die Stadt gelockt!';

				addnews($str_msg);
				board_add('fuerst_act',31,0,$str_msg);
			}
		}
		else
		{
			output('Hoppla, das können wir uns jetzt aber gar nicht leisten...');
		}
		addnav('Zurück');
		addnav('Zur Kasse','dorfamt.php?op=office_budget');
		break;
	}

	case 'office_budget_orkburg': { //Weg zur Orkburg freilegen
		$budget=getsetting('amtskasse' ,0);
		$freeorkburg=getsetting('freeorkburg','30000');
		$orkburg=getsetting('dailyspecial','Keines');
		if ($budget>=$lurevendor)
		{
			if ($orkburg=='Orkburg')
			{
				output('`2Nicht nötig, der Weg ist gut freigetreten.`n
						Oder willst du die hart verdienten Steuergelder unnötig an Waldarbeiter verfeuern?`n`n');
			}
			else
			{
				output('`2Du schickst eine Horde Waldarbeiter mit den `^'.$freeorkburg.'`2 Gold zum Toilettenhäuschen, die sich in Windeseile durch das Unterholz hacken und einen schönen, breiten Weg zur Orkburg freilegen.`n
						Leider wird dieser schon morgen wieder total zugewuchert sein.');
				savesetting('amtskasse',$budget-$freeorkburg);
				savesetting('dailyspecial','Orkburg');

				$str_msg = $session['user']['name'].' `^ hat den Weg zur Orkburg freilegen lassen!';

				addnews($str_msg);
				board_add('fuerst_act',31,0,$str_msg);
			}
		}
		else
		{
			output('Hoppla, das können wir uns jetzt aber gar nicht leisten...');
		}
		addnav('Zurück');
		addnav('Zur Kasse','dorfamt.php?op=office_budget');
		break;
	}

	case 'office_budget_buygems': { //Edelsteine von der Zigeunerin kaufen
		$budget=getsetting('amtskasse' ,0);
		$amtsgems=getsetting('amtsgems','0');
		$selledgems=getsetting('selledgems',0);
		$costs=(4000-3*$selledgems);
		$maxgems=getsetting('maxamtsgems','100');
		$spaceleft=$maxgems-$amtsgems;
		output("<form action='dorfamt.php?op=office_budget_buygems2' method='POST'>
		`2Die Zigeunerin hat derzeit `^".$selledgems."`2 Edelsteine auf Lager, zu einem Preis von jeweils `^".$costs." `2Gold.
		`nWieviele Edelsteine hättest du gern? (Die Tresore fassen noch ".$spaceleft." Edelsteine)`0
		<input id='input' name='amount' size=4>
		<input type='submit' class='button' value='kaufen'>
		`n</form>
		".focus_form_element('input'));
		addnav('','dorfamt.php?op=office_budget_buygems2');
		addnav('Doch nichts kaufen','dorfamt.php?op=office_budget');
		break;
	}

	case 'office_budget_buygems2': { //Edelsteinkauf abschließen
		$budget=getsetting('amtskasse' ,0);
		$amtsgems=getsetting('amtsgems','0');
		$selledgems=getsetting('selledgems',0);
		$costs=(4000-3*$selledgems);
		$maxgems=getsetting('maxamtsgems','100');
		$spaceleft=$maxgems-$amtsgems;
		$_POST['amount']=floor((int)$_POST['amount']);

		if ($_POST['amount']<0)
		{
			output('`2Du kannst auf diese Art keine Edelsteine verkaufen!');
		}
		else if ($_POST['amount']==0)
		{
			output('`2Du entscheidest dich, doch nichts zu kaufen.');
		}
		else if ($_POST['amount']>$selledgems)
		{
			output('`2So viele Edelsteine hat die Zigeunerin im Moment nicht.');
		}
		else if (($_POST['amount']*$costs)>$budget)
		{
			output('`2Das übersteigt deine finanziellen Fähigkeiten!');
		}
		else if ($_POST['amount']>$spaceleft)
		{
			output('`2So viele Edelsteine können die Tresore leider nicht mehr fassen!');
		}
		else
		{
			$amount=$_POST['amount'];

			board_add('fuerst_act',31,0,$session['user']['name'].'`^ kauft '.$amount.' Edelsteine!');

			output('`2Du kaufst `^'.$amount.' `2Edelsteine von der Zigeunerin und deponierst sie in den Tresoren.');
			$selledgems-=$amount;
			if ($selledgems>0)
			{
				savesetting('selledgems',$selledgems);
			}
			else
			{
				savesetting('selledgems','0');
			}
			$amtsgems+=$amount;
			savesetting('amtsgems',$amtsgems);
			$budget-=$amount*$costs;
			savesetting('amtskasse',$budget);
		}
		addnav('Zurück','dorfamt.php?op=office_budget');
		break;
	}

	case 'office_budget_sellgems': { //Edelsteine verkaufen
		$budget=getsetting('amtskasse','0');
		$amtsgems=getsetting('amtsgems','0');
		$selledgems=getsetting('selledgems','0');
		$spaceleft=100-$selledgems;
		$scost=(3000-$selledgems);
		output("<form action='dorfamt.php?op=office_budget_sellgems2' method='POST'>
		`2Die Zigeunerin hat derzeit `^$selledgems`2 Edelsteine auf Lager und kauft bis zu `^$spaceleft`2 weitere Steine zu einem Preis von jeweils `^$scost `2Gold an.
		`nWieviele Edelsteine willst du verkaufen? (Du hast noch $amtsgems
		Edelsteine)`0
		<input id='input' name='amount' size=4>
		<input type='submit' class='button' value='verkaufen'>
		`n</form>".focus_form_element('input'));
		addnav('','dorfamt.php?op=office_budget_sellgems2');
		addnav('Doch nichts kaufen','dorfamt.php?op=office_budget');
		break;
	}

	case 'office_budget_sellgems2': {  //Edelsteinverkauf abschließen
		$budget=getsetting('amtskasse','0');
		$amtsgems=getsetting('amtsgems','0');
		$selledgems=getsetting('selledgems','0');
		$scost=(3000-$selledgems);
		$spaceleft=100-$selledgems;
		$_POST['amount']=floor((int)$_POST['amount']);

		if ($_POST['amount']<0)
		{
			output('`2Du kannst auf diese Art keine Edelsteine kaufen!');
		}
		else if ($_POST['amount']==0)
		{
			output('`2Du entscheidest dich doch nichts zu verkaufen.');
		}
		else if ($_POST['amount']>$spaceleft)
		{
			output('`2So viele Edelsteine will die Zigeunerin im Moment nicht.');
		}
		else if ($_POST['amount']>$amtsgems)
		{
			output('`2So viele Edelsteine hast du gar nicht!');
		}
		else
		{
			$amount=$_POST['amount'];

			board_add('fuerst_act',31,0,$session['user']['name'].'`^ verkauft '.$amount.' Edelsteine!');

			output('`2Du verkaufst der Zigeunerin `^'.$amount.' `2Edelsteine.');
			$selledgems+=$amount;
			savesetting('selledgems',$selledgems);
			$amtsgems-=$amount;
			if ($amtsgems>0)
			{
				savesetting('amtsgems',$amtsgems);
			}
			else
			{
				savesetting('amtsgems','0');
			}
			$budget+=$amount*$scost;
			savesetting('amtskasse',$budget);
		}
		addnav('Zurück','dorfamt.php?op=office_budget');
		break;
	}


	case 'dame1':{

		output('`]Du `Zsc`:haust dich ein wenig in den Vorzimmern der hohen Herren um und entdeckst, hübsch geschminkt und über und über mit Ringen, Ketten und Broschen behangen, das furchteinflößendste und gefährlichste Wesen, dass dir je begegnet ist: `Zdie Vorzimmerdame`:!
		`nSie ist es, die in vornehmen Kreisen die neuesten Gerüchte an den Mann bringt und dabei auch gut und gern ihr schlechtes Gedächtnis mit ihrer Phantasie unterstützt.`nDir bleibt fast das Herz stehen, als sie dich ansieht und erwartungsvoll mit den Wimpern klim`Zpe`]rt.');
		addnav('Was nun ?');
		addnav('Ansehen steigern','dorfamt.php?op=dame2');
		addnav('Gerüchte streuen','dorfamt.php?op=dame3');

		$player = user_get_aei("job");

		if ($player['job']==0)
		{
			addnav("Beruf anmelden","dorfamt.php?op=getjob");
		}
		else
		{
			addnav("Beruf aufgeben","dorfamt.php?op=losejob");
		}
		addnav("Laufen!","dorfamt.php");
		break;
	}

	case 'getjob':{

		require_once(LIB_PATH.'profession.lib.php');
		require_once(LIB_PATH.'house.lib.php');

		$int_j = $_GET['j'];
		if (empty($int_j))
		{

			addnav('Berufe');

			output("Welchen Beruf würdest du gern annehmen?`n");

			foreach ($g_arr_prof_jobs as $int_id => $arr_j) {
				if(isset($arr_j['locked_right'])) {
					if(!$access_control->su_check($arr_j['locked_right'])) {
						continue;
					}
				}
				output('`n'.$arr_j['name'].': ');
				if($arr_j['needs_own_house'] && !$session['user']['house'])
				{
					output('`iZur Ausübung dieses Berufes benötigst du ein eigenes Haus. Leider verfügst du noch über kein solches!`i');
				}
				elseif(isset($arr_j['min_dk']) && $session['user']['dragonkills'] < $arr_j['min_dk'])
				{
					output('`iZur Ausübung dieses Berufes ist mehr Erfahrung erforderlich - mindestens '.$arr_j['min_dk'].' Heldentaten solltest du vollbracht haben!`i');
				}
				elseif ($session['user']['gems'] < $arr_j['cost'])
				{
					output('`iDu benötigst '.$arr_j['cost'].' Edelsteine, um diesen Beruf anzunehmen!');
				}
				else
				{
					output(create_lnk($arr_j['name'].' als Beruf wählen','dorfamt.php?op=getjob&j='.$int_id,true,true));
					//,'Bist du dir da auch sicher..?'));
				}
			}

			addnav('Held bleiben');
			addnav("Zurück","dorfamt.php?op=dame1");
		}
		else
		{
			if ($_GET['confirm']==1)
			{
				output("Du gibst dein Leben als ... Held ... auf und bist nun ");

				// Modul runnen
				$arr_j = $g_arr_prof_jobs[$int_j];

				output($arr_j['name']);

				user_set_aei(array('job'=>$int_j));
				$session['user']['gems']-=$arr_j['cost'];

				$str_ext_type = house_ext_from_job($int_j);

				$arr_ext = db_fetch_assoc(db_query('SELECT * FROM house_extensions WHERE houseid='.$session['user']['house'].' AND type="'.$str_ext_type.'"'));
				if(isset($arr_ext['id'])) {
					house_extension_run('job_get',$arr_ext);
				}
			}
			else
			{
				$arr_j = $g_arr_prof_jobs[$int_j];
				output($arr_j['name'].'`n`n'.$arr_j['desc'].'`n`nDurch die Wahl dieses Berufes reduziert sich die Anzahl deiner Runden pro Tag um etwa ein Fünftel.`nZudem verlangt die Zunft als Nachweis deiner Fertigkeiten '.$arr_j['cost'].' Edelsteine, die du in Ausübung deines Handwerks verdient hast.`n`n');
				if ($arr_j['needs_own_house'])
				{
					output("Auch erfordert dieser Beruf, dass du ein eigenes Haus besitzt.`n`nWillst du also immer noch ".$arr_j['name'].' werden?');
				}

				addnav("Ja");
				addnav("Ich will!","dorfamt.php?op=getjob&j=".$int_j."&confirm=1");
				addnav("Ach nee");
				addnav("Was gabs noch?","dorfamt.php?op=getjob");
			}

			addnav("Zurück","dorfamt.php?op=dame1");
		}
		break;
	}

	case 'losejob':{

		require_once(LIB_PATH.'profession.lib.php');
		require_once(LIB_PATH.'house.lib.php');

		$player = user_get_aei('job');

		$str_ext_type = house_ext_from_job($player['job']);

		$arr_ext = db_fetch_assoc(db_query('SELECT * FROM house_extensions WHERE houseid='.$session['user']['house'].' AND type="'.$str_ext_type.'"'));

		$arr_house = db_fetch_assoc(db_query('SELECT status FROM houses WHERE houseid='.$session['user']['house']));

		$_int_housetype_bonus = ($g_arr_house_builds[$arr_house['status']]['house_extension_max_lvl_bonus'] == $str_ext_type) ? $g_arr_house_builds[$arr_house['status']]['house_extension_max_lvl_bonus_value'] : 0;

		$_max_lvl = $_int_housetype_bonus + $g_arr_house_extensions[$str_ext_type]['maxlvl_else'];

		if (!$_GET['confirm'])
		{

			output("Dein derzeitiger Beruf ist ");

			$arr_j = $g_arr_prof_jobs[$player['job']];

			if(!sizeof($arr_j)) {
				output('.. ein Bug.');
			}
			else {

				output($arr_j['name'].'.');

				output("`nWillst du ihn jetzt aufgeben?");

				if(isset($arr_ext['id']) && $arr_ext['level'] > $_max_lvl) {
					output('`n`n`$Beachte, dass dadurch dein Anbau von Stufe '.$arr_ext['level'].' auf Stufe '.$_max_lvl.' zurückgestuft würde! Alle überzähligen Dinge die danach nicht mehr in den Anbau passen würden, werden Zwangskonfisziert!`0');
				}

				addnav("Arbeitslos melden");
				addnav("JA","dorfamt.php?op=losejob&confirm=1");
			}

			addnav("Weiter schuften");
			addnav("Zurück","dorfamt.php?op=dame1");
		}
		else
		{
			output("Alles klar!`nDu bist nun wieder... ein Held.`n");
			user_set_aei(array('job'=>0));

			if(isset($arr_ext['id'])) {
				if($arr_ext['level'] > $_max_lvl) {
					db_query('UPDATE house_extensions SET level='.$_max_lvl.' WHERE id='.$arr_ext['id']);
					debuglog('hat Job aufgegeben und Anbau '.$arr_ext['type'].' auf Max.Lvl zurückgestuft');
				}
				house_extension_run('job_cancel',$arr_ext);
			}

			addnav("Zurück","dorfamt.php?op=dame1");
		}
		break;
	}

	case 'dame2': { //Vorzimmerdame bestechen
		output('Nachdem du der Vorzimmerdame mitgeteilt hast, dass du gern ein wenig beliebter wärst und dass dich keiner so richtig leiden kann, wischt sie sich demonstrativ ein Tränchen von der Wange und schaut dich an. "`#Na das dürfte nicht allzu schwer sein. Ich kann den Leuten ja mal erzählen was für ein tolle'.($session['user']['sex']?'s Mädel ':'r Bursche ').'Du bist.
		`nSo etwas hat aber seinen Preis... Einen Edelstein für zwei nette Heldengeschichten !`0"`n`n');
		if($session['user']['daysinjail']>$session['user']['dragonkills'])
		{
			output('Plötzlich fängt die Vorzimmedame verhalten an zu grinsen. Sie hat wohl die Ursache für die ungebräunten Streifen in deinem Gesicht erkannt: Ein vergittertes Fenster!
			`nNatürlich sehen auch die anderen Bürger in '.getsetting('townname','Atrahor').' diese Streifen. Ob sie da wirklich die Heldengeschichten über dich glauben?`n`n');
		}
		output('`&Wieviele Edelsteine willst du ihr geben?`0
		<form action="dorfamt.php?op=dame21" method="POST">
		<input name="buy" id="buy">
		<input type="submit" class="button" value="Geben">
		</form>
		'.focus_form_element('buy'));
		addnav('','dorfamt.php?op=dame21');
		addnav('Lieber doch nicht','dorfamt.php?op=dame1');
		break;
	}

	case 'dame21': { //Vorzimmerdame bestechen - fertig
		$buy = $_POST['buy'];
		if (($buy>$session['user']['gems']) || ($buy<1))
		{
			output('`&Na das ging nach hinten los... Du bietest ihr Edelsteine an, die du nicht hast. In der Hoffnung, dass sie nun keine Gerüchte über deine Armut streut, eilst du davon.');
			addnav('Weg hier!','village.php');
		}
		else
		{
			$eff=$buy*2;
			output('`&Die Dame lässt deine '.$buy.' Edelsteine in ihrem Handtäschchen verschwinden und lächelt dich an. Dein Ansehen steigt um '.$eff.'.`n');
			$session['user']['gems']-=$buy;
			if ($buy>4)
			{
				debuglog('Gibt '.$amt.' Edelsteine im Rathaus für Ansehen.');
			}
			$session['user']['reputation']+=$eff;
			if ($session['user']['reputation']>50)
			{
				$session['user']['reputation']=50;
			}
			addnav('Zurück','dorfamt.php?op=dame1');
		}
		break;
	}

	case 'dame3': { //Gerüchte streuen
		output('`&Die Frau schaut dich an. "`#Sooo... und um wen geht es?`&" fragt sie.`n`n');

		if ($_GET['who']=='')
		{
			addnav('Äh.. um niemanden!','dorfamt.php');
			if ($_GET['subop']!='search')
			{
				$str_out.='`0<form action="dorfamt.php?op=dame3&amp;subop=search" method="POST">
				<input name="name">
				<input type="submit" class="button" value="Suchen">
				</form>';
				addnav('','dorfamt.php?op=dame3&subop=search');
			}
			else
			{
				addnav('Neue Suche','dorfamt.php?op=dame3');
				$search = str_create_search_string($_POST['name']);
				$sql = 'SELECT acctid,name,alive,location,sex,level,reputation,laston,loggedin,login
					FROM accounts
					WHERE (locked=0 AND name LIKE "'.db_real_escape_string($search).'")
					ORDER BY login="'.db_real_escape_string($_POST['name']).'" DESC, level DESC
					LIMIT 51';
				$result = db_query($sql);
				$max = db_num_rows($result);
				if ($max > 50)
				{
					$str_out.='`n`n"`#Na... damit könnte ja jeder gemeint sein..`0"`n';
					$max = 50;
				}
				$str_out.='<table border=0 cellpadding=0>
				<tr class="trhead">
				<th>Name</th>
				<th>Level</th>
				</tr>';
				for ($i=0; $i<$max; $i++)
				{
					$row = db_fetch_assoc($result);
					$str_out.='<tr class="'.($i%2?'trdark':'trlight').'">
					<td><a href="dorfamt.php?op=dame3&amp;who='.$row['acctid'].'">'.$row['name'].'`0</a></td>
					<td align="right">'.$row['level'].'</td>
					</tr>';
					addnav('','dorfamt.php?op=dame3&who='.$row['acctid']);
				}
				$str_out.='</table>';
			}
		}
		else
		{

			$sql = 'SELECT acctid,login,name,reputation FROM accounts WHERE acctid='.$_GET['who'];
			$result = db_query($sql);
			if (db_num_rows($result)>0)
			{
				$row = db_fetch_assoc($result);

				$str_out.='`&Die Vorzimmerdame lächelt. "`#Aber natürlich! '.($row['name']).'`#! Der Name ist mir ein Begriff... Ich denke dass ich sicherlich ein paar alte Geschichten bekannt werden lassen kann.
				`nDie Leute sollen ruhig wissen mit wem sie es da zu tun haben! Aber... die Sache wird nicht ganz billig werden, denn ich muss sehr viel in den Akten suchen... und...so.
				`nZwei kleine Gerüchte würde einen Edelstein kosten..`&"
				`n
				`n`nWieviele Edelsteine willst du ihr geben?`0
				<form action="dorfamt.php?op=dame31&amp;who='.$row["acctid"].'" method="POST">
				<input name="buy" id="buy">
				<input type="submit" class="button" value="Geben">
				</form>
				'.focus_form_element('buy');
				addnav('','dorfamt.php?op=dame31&who='.$row['acctid']);
				addnav('Lieber doch nicht','dorfamt.php?op=dame1');
			}
			else
			{
				$str_out.='"`#Ich kenne niemanden mit diesem Namen.`&"';
			}
		}
		output($str_out);
		break;
	}

	case 'dame31': { //Gerüchte streuen - fertig
		$buy = $_POST['buy'];
		$sql = 'SELECT acctid,name,reputation,login,sex FROM accounts WHERE acctid='.$_GET['who'];
		$result = db_query($sql);
		if (db_num_rows($result)>0)
		{
			$row = db_fetch_assoc($result);

			if (($buy>$session['user']['gems']) || ($buy<1))
			{
				output('`&Na das ging nach hinten los... Du bietest ihr Edelsteine an, die du nicht hast. In der Hoffnung, dass sie nun keine Gerüchte über DICH verstreut, eilst du davon.');
				addnav('Weg hier!','village.php');
			}
			else
			{
				$eff=$buy*2;
				output("`&Die Dame lässt deine $buy Edelsteine in ihrem Handtäschchen verschwinden und lächelt dich an. Das Ansehen von ".($row['name'])."`& sinkt um $eff Punkte.`n");
				$session['user']['gems']-=$buy;
				if ($buy>2)
				{
					debuglog("Gibt $buy Edelsteine im Rathaus für Gerüchte.",$row['acctid']);
				}
				$rep=$row['reputation']-$eff;
				if ($rep<-50)
				{
					$rep=-50;
				}

				user_update(
				array
				(
				'reputation'=>$rep
				),
				$row['acctid']
				);

				$chance=e_rand(1,3);
				if ($chance==1)
				{
					systemmail($row['acctid'],'`$Gerüchte!`0','`@'.$session['user']['name'].'`& hat die Vorzimmerdame im Rathaus bestochen, damit diese üble Gerüchte über dich verbreitet! Dein Ansehen ist um '.$eff.' Punkte gesunken! Willst du dir sowas gefallen lassen?');
				}
				else
				{
					systemmail($row['acctid'],'`$Gerüchte!`0','`&Jemand hat die Vorzimmerdame im Rathaus bestochen, damit diese üble Gerüchte über dich verbreitet! Dein Ansehen ist um '.$eff.' Punkte gesunken! Willst du dir sowas gefallen lassen?');
				}
				if ($buy >= 5)
				{
					$news="`@Gerüchte besagen, dass `^".$row['name']."";
					switch (e_rand(1,15))
					{
						case 1 :
							$news=$news." `@heimlich in der Nase bohrt!";
							break;
						case 2 :
							$news=$news." `@nicht ohne ".($row['sex']?"ihr":"sein")." Kuscheltier einschlafen kann!";
							break;
						case 3 :
							$news=$news." `@etwas mit ".($row['sex']?"Violet":"Seth")." am Laufen haben soll!";
							break;
						case 4 :
							$news=$news." `@ganz übel aus dem Mund riechen soll.";
							break;
						case 5 :
							$news=$news." `@mehr Haare ".($row['sex']?"an den Beinen ":"auf dem Rücken ")."haben soll als ein Bär!";
							break;
						case 6 :
							$news=$news." `@sich regelmäßig am Bettelstein bedienen soll!";
							break;
						case 7 :
							$news=$news." `@sich bei Angst die Hosen vollmachen soll!";
							break;
						case 8 :
							$news=$news." `@im Bett eine Niete sein soll!";
							break;
						case 9 :
							$news=$news." `@für Geld die Hüllen fallen lassen soll!";
							break;
						case 10 :
							$news=$news." `@ein Alkoholproblem haben soll!";
							break;
						case 11 :
							$news=$news." `@Angst im Dunkeln haben soll!";
							break;
						case 12 :
							$news=$news." `@einen Hintern wie ein Ackergaul haben soll!";
							break;
						case 13 :
							$news=$news." `@sehr oft bitterlich weinen soll!";
							break;
						case 14 :
							$news=$news." `@eine feuchte Aussprache haben soll!";
							break;
						case 15 :
							$news=$news." `@eine Perücke tragen soll!";
							break;
					}

					// In die News und in die Bio des Opfers
					$sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('".db_real_escape_string($news)."',NOW(),".$row['acctid'].")";
					db_query($sql);
				}
				addnav("Zurück","dorfamt.php?op=dame1");
			}

		}
		break;
	}

	case 'steuernzahlen': { //Steuern Info
		output('`@"Steuern zahlen könnt Ihr dritten Gang rechts..."
		`n`2Als du zu einem kleinen alten Mann kommst, blickt dieser auf und sagt:
		`n`@"Also Du willst steuern Zahlen?
		`nHm, ich guck ma deine Akte durch! Moment bitte...Da ist sie ja"
		`n`^Privatakte...
		`n
		`n`2Name: `^'.$session['user']['name'].'
		`n`2Alter: `^'.$session['user']['age'].'`^ Tage
		`n`2Level: `^'.$session['user']['level'].'
		`n
		`n`^Sonstige Informationen...
		`n
		`n`2Gold: `^'.$session['user']['gold'].'
		`n`2Gold auf der Bank: `^'.$session['user']['goldinbank'].'
		`n`2Edelsteine: `^'.$session['user']['gems'].'
		`n`2Edelsteine auf der Bank: `^'.$session['user']['gemsinbank'].'
		`n
		`n`n');

		$taxrate=getsetting('taxrate',750);
		$doubletax=2*$taxrate;
		$taxprison=getsetting('taxprison',1);

		if ($taxrate>0)
		{
			output('`b`^Steuern für Neuankömmlinge und Auserwählte:`n`2
				Es müssen keine Steuern entrichtet werden!`b
				`n`n
				`^Steuern zwischen Level 5 und 10:`n`2
				Die Steuer beträgt derzeit `^'.$taxrate.' Gold`2!
				`n`n
				`^Steuern über Level 10:`n`2
				Die Steuer beträgt derzeit `^'.$doubletax.' Gold`2!
				`n`n');
			if ($taxprison==1)
			{
				output('`4Auf Steuerhinterziehung steht ein Tag Kerker!`0');
			}
			else
			{
				output('`4Auf Steuerhinterziehung stehen '.$taxprison.' Tage Kerker!`0');
			}
			output('`n`n`^Solltest du über nicht genügend Gold verfügen, so kannst du dich dieses Mal von den 
			Steuern befreien lassen.`n`n
			Außerdem kannst du versuchen, dich um die Steuern zu drücken. Mit den Konsequenzen musst du dann 
			aber selbst zu Recht kommen!');
		}
		else
		{
			output('`^Derzeit werden keine Steuern erhoben!`n`n');
		}

		if ($Char->getBit(CHOSEN_FULL,'marks') < CHOSEN_FULL)
		{
			addnav('Steuern');
			if ($session['user']['level']>=5)
			{
				addnav('Steuern zahlen','dorfamt.php?op=steuernzahlen_ok');
				addnav('d?Sich darum drücken', 'dorfamt.php?op=druecken');
			}//endif
		}//endif
		else
		{
			output('`n`n`2Der alte Mann lächelt dich plötzlich ganz fürsorglich an und sagt:`n
			`@"Euren Großmut in Ehren, aber Auserwählte zahlen keine Steuern..."`n`n');
		}
		addnav('Wege');
		addnav('a?Rathaus','dorfamt.php');
		addnav('d?Stadtzentrum','village.php');
		break;
	}
	
	case 'druecken': //Steuern versuchen nicht zu bezahlen
		{
		
			
			switch($rand = e_rand(1, 100))
			{
				case ($rand <= 35): //Man muss die Steuern trotzdem zahlen
					output('`2Mit geschwollener Brust und hocherhobenem Haupt trittst du vor das Pult und 
							beschwerst dich über die zu hohen Steuern. Der kleine alte Mann lässt sich jedoch 
							von deiner Beschwerde nicht beeindrucken und fordert deinen Anteil der Steuern wie 
							gehabt. Dein Mut hat dich schnell bei seinem durchdringenden Blick verlassen. 
							Murrend bezahlst du das Gold und gehst weiter deines Weges.');
					addnav('Weiter');
					addnav('S?Doch Steuern zahlen', 'dorfamt.php?op=steuernzahlen_ok');
					break;
				
				case ($rand <= 50): //Ein Aufschub der Steuertage
					output('Du trittst vor den kleinen alten Mann und versuchst dich daran dem einfältigen 
							Kerl eine Lügengeschichte aufzutischen, weswegen du deine Steuern nicht zahlen 
							kannst. Skeptisch schaut er dich über den Rand seiner dicken Hornbrille an und 
							mit jedem verstreichenden Moment, kommt dir der alte Mann mehr und mehr wie 
							ein ekliges Insekt vor. Du gerätst ins Stammeln und schließlich merkst du auch, 
							was du für bescheuertes Zeug loslässt. Dein Gesicht läuft glühend Rot an, aber 
							anstatt zornig auf dich zu werden, weil du ihn angelogen hast, lächelt dich der 
							kleine alte Mann an. `n
							`@"An deinen Geschichten solltest du noch ein wenig feilen. Aber für deinen Mut 
							werde ich dich belohnen. Ich gewähre dir einen Aufschub von drei Tagen, bis dahin 
							solltest du etwas Gold beiseite legen, hm?"');
					$Char->steuertage += 3;
					addnav('Zurück...');
					addnav('a?ins Rathaus','dorfamt.php');
					addnav('d?zum Stadtzentrum','village.php');
					break;
					
				case ($rand <= 75): //man kommt in den Kerker
					$taxprison = getsetting('taxprison',1);
					output('`2Schon wieder Steuern und schon wieder hast du nicht genügend Gold. Lautstark 
							beklagst du dich in dem Zimmer über diese Unmenge, die von einem verlangt wird. 
							Das stachelt auch die anderen Steuerzahler an, die gerade selbst dabei waren, 
							ihren Anteil abzugeben. Wie aus dem Nichts zücken plötzlich alle Anwesenden 
							brennende Fackeln und Mistgabeln. Der arme kleine alte Mann wird bedrängt, doch 
							irgendwie scheint die Stadtwache viel schneller von dieser Revolte Wind bekommen 
							zu haben, als es möglich ist. Eure Revolution wird binnen weniger Sekunden 
							niedergeschlagen und als die Wachen den Aufrührer haben wollen, deuten alle auf 
							dich. Das dürfte heute wohl nicht dein Tag sein und das Gesetz schlägt auch noch 
							zu. `4Viel Spaß im Gefängnis!');
					$Char->steuertage = 7;
					
					if ($taxprison > 0)
					{
						$Char->imprisoned = $taxprison;
					}
					else
					{
						$Char->imprisoned = 1;
					}//endif
					addnav('Weiter');
					addnav('Schwedische Gardinen','prison.php');
					addnews($Char->name.' ist wegen der Anzettelung einer Revolte im Rathaus im Kerker gelandet.');
					debuglog(' sitzt nun wegen einer Revolte im Kerker.');
					break;
					
				case ($rand <= 90): //man muss nur noch die Hälfte der Steuer zahlen
					output('`2Du hast dem kleinen alten Mann eine beinahe unglaubliche Geschichte aufgetischt, 
							warum du die Steuern dieses Mal nicht zahlen kannst. Aber vielleicht solltest du 
							beim nächsten Mal nicht so dick auftragen. Trotzdem ist er so von deiner Phantasie 
							beeindruckt, dass er dir `@die Hälfte der Steuern erlässt. `2Na wenigstens etwas!');
					addnav('Weiter');
					addnav('S?Doch Steuern zahlen', 'dorfamt.php?op=steuernzahlen_ok&success=half');
					break;
					
				default: //man muss keine Steuern zahlen
					output('`2In zerlumpter Kleidung und vor Dreck starrendem Gesicht trittst du auf das Pult 
							zu. Mit großen Kulleraugen siehst du den alten kleinen Mann an und erzählst ihm 
							unter Schluchzen und Jammern, warum du deine Steuern nicht zahlen kannst. Deine 
							herzergreifende Geschichte rührt den kleinen alten Mann so sehr, dass er dir `@die 
							Steuern für dieses Mal erlassen wird. `2Noch bevor du das Zimmer verlassen kannst, 
							rät er dir, immer wieder ein wenig Gold beiseite zu legen, damit du beim nächsten 
							Zahltag nicht wieder in so eine Bredouille kommst. Dein Schauspielunterricht hat 
							sich also nun endlich bezahlt gemacht!');
					addnav('Weiter');
					addnav('S?Steuern erlassen bekommen', 'dorfamt.php?op=steuernzahlen_ok&success=all');
					break;
			}//endswitch
		}
		break;
		
	case 'steuernzahlen_ok': { //Steuern bezahlen
		$taxrate=getsetting("taxrate",750);
		$cost = ($session['user']['level'] >= 11) ? $taxrate*2 : $taxrate;
		
		switch ($_GET['success'])
		{
			case 'half':
				$cost = round(($cost/2));
				break;
			case 'all':
				$cost *= 0;
		}//endswitch

		if ($cost>0)
		{
			if ($session['user']['steuertage']<=1)
			{
				if ($session['user']['gold']>=$cost)
				{
					output('`2Du zahlst deine `^'.$cost.' Gold`2 ein!`n
					`^Wenigstens einer der die Steuern hier bezahlt...`n
					`2Der Kassierer grinst dich an und verabschiedet dich! ');
					$session['user']['gold']-=$cost;
					savesetting('amtskasse' ,getsetting('amtskasse',0)+ $cost);
				}
				else
				{
					output('`2Der Mann sagt: `^Du hast nicht genug Gold dabei, wie willst Du da die '.$cost.' zahlen?`n
					`^Gut, dann nehmen wir halt etwas von der Bank, hm?`n');
					if ($session['user']['goldinbank']<$cost)
					{
						if($session['user']['gold']+$session['user']['goldinbank']<$cost)
						{
							output('Auch nicht? Dann halt Edelsteine!`n');
							if ($session['user']['gems']<1 && $session['user']['gemsinbank']<21)
							{
								output('Du armer Tropf, Du hast ja gar nichts! Na gut, dieses mal sehe ich noch darüber hinweg! Troll Dich`n');
							}
							else //Steuern zahlen mit ES
							{
								$session['user']['gems']--;
								if($session['user']['gems']<0)
								{
									$session['user']['gemsinbank']+=$session['user']['gems'];
									$session['user']['gems']=0;
									output('Kein Edelstein dabei? Aber auf der Bank hast du noch genug.`n');
								}
								output('Na wenigstens etwas...jetzt troll Dich!`n');
								savesetting('amtskasse' ,getsetting('amtskasse',0)+ $cost);
							}
						}
						else //Steuern zahlen mit Bargold + Bankguthaben
						{
							savesetting('amtskasse' ,getsetting('amtskasse',0)+ $cost);
							$cost-=$session['user']['gold'];
							output('Auch nicht? Dann schaun wir mal ob Dein Gold und Bankguthaben zusammen reichen.`nGut. Das macht dann noch 10 Gold Mehraufwand-Gebühr. Und jetzt troll Dich!`n'); //ob irgendjemand merkt dass es keine Gebühr kostet?
							$session['user']['goldinbank']-=$cost;
							$session['user']['gold']=0;
						}
					}
					else //Steuern zahlen mit Bankguthaben
					{
						output('`^Na wenigstens etwas...jetzt troll Dich!`n');
						$session['user']['goldinbank']-=$cost;
						savesetting('amtskasse' ,getsetting('amtskasse',0)+ $cost);
					}
				}
				// END nicht genug Gold in Hand

				debuglog('zahlte Steuern im Rathaus.');
				if (getsetting('amtskasse','0')>getsetting('maxbudget','2000000'))
				{
					savesetting('amtskasse',getsetting('maxbudget','2000000'));
				}
				$session['user']['steuertage']=7;
			}
			else
			{
				output('`2Der Mann sagt: `^Du brauchst heute noch keine Steuern zahlen.');
			}
		}
		else
		{
			if ($_GET['success'] == 'all')
			{
				output('Hattest du ein Glück, dass man dir die Steuern erlassen hat.');
			}
			else
			{
				output('`^Derzeit werden keine Steuern erhoben!`0`n');
			}//endif
			$session['user']['steuertage']=7;
		}
		addnav('Zurück');
		addnav('a?zum Rathaus','dorfamt.php');
		addnav('d?zum Stadtzentrum','village.php');
		break;
	}

	case 'namechange': { //Liste der letzten Namensänderungen
		output('`n`c`7Die letzten Namensänderungen in '.getsetting('townname','Atrahor').':`&`n');
		board_view('namechange',($access_control->su_check(access_control::SU_RIGHT_EDITORUSER) > 1 ? 2 : 0),'','In letzter Zeit hat niemand seinen Namen geändert!',false);
		output('`c');
		addnav('Zurück','dorfamt.php');
		break;
	}

	 /* Eleya: ausgelagert in ooc_area.php
	 
    case 'creatures': { //Auflistung der Waldmonster
		$order=($_GET['order']>''?'creature'.$_GET['order']:'creaturename');
		$dir=($_GET['dir']==1?' DESC, ':' ASC, ');
		$base_link='dorfamt.php?op=creatures&order='.$_GET['order'].'&dir='.$_GET['dir'].'';
		$count_sql='SELECT count(*) AS c FROM creatures WHERE creaturelevel<17';
		$sql='SELECT creaturename,creaturelevel,creatureweapon,creaturelose
			FROM creatures
			WHERE creaturelevel<17
			ORDER BY '.$order.$dir.
				($order=='creaturename'?'':' creaturename ASC, ').
				($order=='creaturelevel'?'':' creaturelevel ASC, ').'
				creatureid ASC
			';
		$arr_page_res = page_nav($base_link,$count_sql,30);
		$sql .= ' LIMIT '.$arr_page_res['limit'];
		$result=db_query($sql);
		$str_out .= '`i`7In den Wäldern von '.getsetting('townname','Atrahor').' wurden bereits `^`b'.$arr_page_res['count'].'`b`7 unterschiedliche Monster gesehen!`0`i`n`n';
		$str_out.='<table width="95%" border=0 bgcolor="#888888" align="center">
		<tr class="trhead">
		<th>'.create_lnk('Name'.($order=='creaturename'?($_GET['dir']==1?' &darr;':' &uarr;'):''),'dorfamt.php?op=creatures&order=name&dir='.($_GET['dir']==1?0:1).'&page=0').'</th>
		<th>'.create_lnk('Level'.($order=='creaturelevel'?($_GET['dir']==1?'&nbsp;&darr;':'&nbsp;&uarr;'):''),'dorfamt.php?op=creatures&order=level&dir='.($_GET['dir']==1?0:1).'&page=0').'</th>
		<th>'.create_lnk('Waffe'.($order=='creatureweapon'?($_GET['dir']==1?' &darr;':' &uarr;'):''),'dorfamt.php?op=creatures&order=weapon&dir='.($_GET['dir']==1?0:1).'&page=0').'</th>
		</tr>';
		$trclass='trdark';
		
		while($row=db_fetch_assoc($result))
		{
			$creaturelose=strip_appoencode($row['creaturelose'],3);
			$creaturelose=str_replace('"','&quot;',$creaturelose);
			$str_out.='<tr class="'.$trclass.'">
			<td title="'.$creaturelose.'">'.$row['creaturename'].'</td>
			<td align="center">'.$row['creaturelevel'].'</td>
			<td>'.$row['creatureweapon'].'</td>
			</tr>';
			$trclass=($trclass=='trlight'?'trdark':'trlight');
		}
		$str_out.='</table>';
		addnav('Zurück');
		addnav('+?Zum OOC-Bereich','ooc_area.php');
		addnav('D?Zum Dorf','village.php');
		output($str_out);
		break;
	 }*/

	default : //Eingangshalle
	{
		output('`c`b`sD`ea`)s`( R`Nat`(h`)a`eu`ss`0`b`c
		`n`eDu trittst in eine große Halle, die an beiden Seiten von weißen Marmorsäulen gesäumt wird. Gegenüber des Eingangstores befindet sich ein freundlich aussehender Schreibtisch und dahinter eine noch freundlicher aussehende Dame, die sich mit einigen Papieren beschäftigt. `nAls Du näher trittst hebt die `]Em`Zpf`:angs`Zda`]me`e den Blick, sieht Dich an und fragt nach Deinem Begehr!
		`n `Z"Willkommen, bitte nicht wundern, die Amtssprache wird Euch seltsam erscheinen. Was kann ich für Euch tun?"
		`n`eAn den Wänden hängen einige Informationstafeln:
		`n`0`c`^-~-
		`n`)In der Amtskasse befinden sich `^' .number_format(getsetting('amtskasse', 0),0,'',' '). ' `)Goldstücke!`0`c');
		$sql = 'SELECT a.name,a.login,b.* FROM boards b LEFT JOIN accounts a ON a.acctid=b.author WHERE b.section="namechange" ORDER BY b.postdate DESC, b.expire DESC LIMIT 1';
		$res = db_query($sql);
		if(db_num_rows($res))
		{
			$msg = db_fetch_assoc($res);
			output('`c`^-~-
			`n`7Die letzte Namensänderung in '.getsetting('townname','Atrahor').':`&`n`&'.$msg['name'].'`&:`n`^'.strip_tags(closetags($msg['message'],'`b`c`i')));
		}
		else
		{
			output('`c`^-~-
			`n`7In letzter Zeit hat niemand seinen Namen geändert!');
		}
		output('`0`c');

		output('`n`2Du hörst einige andere Stadtbewohner diskutieren:`n');
		addcommentary();
		viewcommentary('dorfamt','Diskutieren',20);

		addnav('Steuern');
		addnav('Steuern Zahlen','dorfamt.php?op=steuernzahlen');

		addnav('Stadtwache');
		if (($session['user']['profession']==1) || ($session['user']['profession']==2) || ($access_control->su_check(access_control::SU_RIGHT_DEBUG)) )
		{
			addnav('Hauptquartier betreten','wache.php?op=hq');
		}
		addnav('Stadtwachen auflisten','wache.php?op=showg');
		addnav('Gerichtshof');
		addnav('Gericht betreten','court.php?op=court');

		addnav('Magistrat');

		addnav('Vorzimmerdame','dorfamt.php?op=dame1');
		if((bool)getsetting('symp_active','0') || $access_control->su_check(access_control::SU_RIGHT_VIEW_SYMPATHY_VOTES)) {
			addnav('Fürstliches Büro','dorfamt.php?op=office_entry');
		}

		if ($session['user']['profession']==0)
		{
			addnav('Stadtwache werden','wache.php?op=bewerben');
		}
		if ($session['user']['profession']==5)
		{
			addnav('Bewerbung zurückziehen','wache.php?op=bewerben_abbr');
		}

		if ($session['user']['profession']==0)
		{
			addnav('Richter werden','court.php?op=bewerben');
		}
		if ($session['user']['profession']==25)
		{
			addnav('Bewerbung zurückziehen','court.php?op=bewerben_abbr');
		}

		if (($session['user']['profession']==1) || ($session['user']['profession']==2))
		{
			addnav('Austreten?');
			addnav('Entlassung erbitten','wache.php?op=leave',false,false,false,false);
		}

		if (($session['user']['profession']==21) || ($session['user']['profession']==22))
		{
			addnav('Austreten?');
			addnav('Entlassung erbitten','court.php?op=leave',false,false,false,false);
		}
		addnav('Sonstiges...');
		
		addnav('Ahnenforscher','stammbaum.php');
		addnav('Chronist','chronist.php');
		addnav('N?Letzte Namensänderungen','dorfamt.php?op=namechange');
		
		//Eleya: OoC-Räume ausgelagert in ooc_area.php

		addnav('Zur Stadt');
		addnav('Z?Zurück zum Stadtzentrum','village.php');
	}
}
page_footer();
?>
