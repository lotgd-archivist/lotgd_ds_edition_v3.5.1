<?php
/****************************************
 * Heldenpunkte neu verteilen            *
 *  aka der Schrein der Erinnerung       *
 * von n2code (Takehon/Takeo/Knightnike) *
 * n2code@herr-der-mails.de              *
 *****************************************/
/*
Installation:
	->	Datenbank:
		ALTER TABLE `account_extra_info` ADD `dragonpoints_changed` TINYINT( 1 ) UNSIGNED NOT null DEFAULT '0' COMMENT 'Sind schon Änderungen an den Heldenpunkten erfolgt?'
	->	Verlinkung:
		füge in der rock.php nach
		addnav('E?Schrein der Erneuerung','rebirth.php');
		folgendes ein
		addnav('Schrein der Erinnerung','dragonpointchanger.php'); //ggf. Dateiname ändern
	->  Wiedergeburt:
		füge in die rebirth.php nach
		user_set_aei(array('ctitle'=>'','cname'=>'','ctitle_backup'=>'')); (Zeile 209)
		folgendes ein
		user_set_aei(array('dragonpoints_changed'=>0),$session['user']['acctid']);
	->  Extended Text namens "dragonpointreset_info" einfügen, wird über dem Komplettresetanfrageformular angezeigt
	->	Sonstiges (für alle die's interessiert, Werte werden ja eigentlich automatisch generiert, wenn nicht vorhanden):
		setting 'cost_dragonpoints_change' - Wieviel DP kostet eine Änderung pro 5 verändernte Heldenpunkte?
		setting 'max_dragonpoints_change' - Wieviel Heldenpunkte darf man maximal ändern?
		$Session['daily']['dragonpointtrance'] - aus Performancegründen die User nur einmal am Tag diese u.U. riesige Seite (die mit der Auflistung) laden lassen
*/
require_once "common.php";
$str_self = basename(__FILE__);
$str_output = '';
page_header('Schrein der Erinnerung');
$str_output = "`c`b`&S`sc`eh`7rein der Erinner`eu`sn`&g`b`c`n`n";
if($_GET['op'] == "")
{
	addnav('Zurück zum Club', 'rock.php');
	addnav('Aktionen');
	addnav('Über die Fehler der Vergangenheit nachdenken', $str_self . '?op=change&subop=start');
	addnav('Geist vollständig leeren', $str_self . '?op=reset');
	$str_output .= '`7Hierher kommen die erfahrensten Krieger aus aller Welt, um über die Fehler, welche sie in ihrer Vergangenheit begangen haben, nachzudenken. Wer hier meditiert, kann in dem Zustand einer tiefen Trance sogar einige schicksalshafte Entscheidungen rückgängig machen und für sich zum Besseren ändern.`n`n';
	$str_output .= '`&Du kannst hier gegen eine Gebühr von ' . getsetting('cost_dragonpoints_change', 500) . ' Donationpoints pro 5 Änderungen maximal ' . getsetting('max_dragonpoints_change', 20) . ' deiner Heldenpunkte `beinmalig`b neu vergeben.`nIn `bgut begründeten Ausnahmefällen`b (das ' . getsetting('teamname', 'Drachenserver-Team') . ' behält sich vor zu bestimmen, in welchen Fällen dies zu gestatten ist) ist auch ein Komplettreset der Heldenpunkte möglich. Die Kosten hierfür belaufen sich auf mindestens 2000 DP und können in jedem individuellen Fall unterschiedlich hoch sein.';
}
elseif(($_GET['op'] == "change") && ($_GET['subop'] == "start"))
{
	if((isset(Atrahor::$Session['daily']['dragonpointtrance']) && Atrahor::$Session['daily']['dragonpointtrance'] == 1))
	{
		$str_output .= '`qGerade, als du wieder mit dem Ritual anfangen willst, bemerkst du wieder diese schrecklichen Kopfschmerzen, die dir noch vom letzten Mal geblieben sind. Vielleicht solltest du noch etwas warten, bis sie abgeklungen sind, bevor du dich schon wieder in Trance versetzt...';
		addnav('Zurück zum Club', 'rock.php');
	}
	else
	{
		Atrahor::$Session['daily']['dragonpointtrance'] = 1;
		$str_output .= '`sDu beginnst mit dem Ritual und lässt dich wie in einem Tempel auf einer kleinen Matte nieder und verschränkst die Beine. Kaum hast du die Augen geschlossen, fühlst du auch schon die magische wärmende Aura die an diesem Ort herrscht und wie dich ihre Wellen erfassen. Getragen von ihnen schwebt dein Geist an einen anderen Ort. Als du deine Augen wieder öffnest befindest du dich nicht mehr dort, wo du sie geschlossen hast. Anstattdessen bekommst du auf einmal die Erkenntnis, dass...';
		addnav('Weiter', $str_self . '?op=change');
	}
}
elseif(($_GET['op'] == "change") && ($_GET['subop'] == "abort"))
{
	addnav('Zurück zum Club', 'rock.php');
	$str_output .= '`hDein Geist kehrt aus der mentalen Ebene zurück in deinen Körper. Du öffnest die Augen und findest dich im Schrein der Erinnerung im Club der Veteranen wieder. Zwar dröhnt dein Kopf jetzt sehr, doch das war es wert.'; //ja, ich denk bei "mentaler Ebene" an POP-T2T^^
}
elseif(($_GET['op'] == "change") && ($_GET['subop'] == ""))
{
	$arr_aei = user_get_aei('dragonpoints_changed', $session['user']['acctid']);
	if($arr_aei['dragonpoints_changed'] == 1)
	{
		$str_output .= '`4...du von der Möglichkeit, die Fehler deiner Vergangenheit zu beseitigen, in deinem Leben bereits Gebrauch gemacht hast! Wenn du noch einmal eine solche Aktion unternehmen würdest, würdest du damit am Ende noch den Wächter der Zeit auf dich aufmerksam machen...'; //nur Anspielungen heute, tssss... ;)
		addnav('Erwachen', $str_self . '?op=change&subop=abort');
	}
	else
	{
		$str_output .= '...du nun die Möglichkeit hast `bmaximal ' . getsetting('max_dragonpoints_change', 20) . '`b deiner Heldenpunkte zu ändern:';
		addnav('Aktionen');
		addnav('Abbruch', $str_self . '?op=change&subop=abort');
		$arr_dp = $session['user']['dragonpoints'];
		$editform = array();
		foreach($arr_dp as $key => $val)
		{
			$editform[$key] = 'Heldenpunkt der ' . ($key + 1) . '. Heldentat,enum,at,Angriff,de,Verteidigung,hp,Lebenspunkte,ff,Waldkämpfe';
		}
		$editform['reason'] = 'Begründung für die Änderungen (optional aber erwünscht),textarea,30,5';
		$str_output .= '<form action="' . $str_self . '?op=change&subop=submit" method="POST">';
		$str_output .= generateform($editform, $arr_dp, false, 'Weiter');
		addnav('', $str_self . '?op=change&subop=submit');
	}
}
elseif(($_GET['op'] == "change") && ($_GET['subop'] == "submit"))
{
	$int_changes = 0;
	$str_changes = '';
	$str_before = '';
	$str_after = '';
	$str_reason = ((mb_strlen(trim($_POST['reason'])) > 0) ? $_POST['reason'] : 'n/a');
	$arr_new_dp = array();
	foreach($_POST as $key => $val)
	{
		if(array_key_exists($key, $session['user']['dragonpoints']))
		{
			if($_POST[$key] != $session['user']['dragonpoints'][$key])
			{
				$int_changes++;
				if($session['user']['dragonpoints'][$key] == "ff")
					$str_before = 'Waldkampf';
				if($session['user']['dragonpoints'][$key] == "at")
					$str_before = 'Angriff';
				if($session['user']['dragonpoints'][$key] == "de")
					$str_before = 'Verteidigung';
				if($session['user']['dragonpoints'][$key] == "hp")
					$str_before = 'Lebenspunkte';
				if($_POST[$key] == "ff")
					$str_after = 'Waldkampf';
				if($_POST[$key] == "at")
					$str_after = 'Angriff';
				if($_POST[$key] == "de")
					$str_after = 'Verteidigung';
				if($_POST[$key] == "hp")
					$str_after = 'Lebenspunkte';
				$str_changes .= '`n&nbsp;&nbsp;&nbsp;-> Heldenpunkt der ' . ($key + 1) . '. Heldentat von "' . $str_before . '" auf "' . $str_after . '" ändern';
			}
			$arr_new_dp[$key] = $val;
		}
	}
	$int_cost = ceil((double)$int_changes / 5.0) * getsetting('cost_dragonpoints_change', 500);
	$str_changes = 'Folgende Änderungen sollen vorgenommen werden (' . $int_changes . ' Änderung' . ($int_changes > 1 ? 'en' : '') . '):' . $str_changes . '`n`n';
	$str_output .= $str_changes;
	if($int_changes > getsetting('max_dragonpoints_change', 20))
	{
		$str_output .= '`$`bMehr als ' . getsetting('max_dragonpoints_change', 20) . ' Änderungen sind nicht möglich!`b';
	}
	elseif($int_changes <= 0)
	{
		$str_output .= '`$`bKeine Änderungen vorgenommen!`b';
	}
	else
	{
		$str_output .= 'Die Kosten dafür belaufen sich auf `b' . $int_cost . '`b Donationpoints.';
		$str_output .= '`n`n`c`b`$Mir ist bewusst, dass diese Änderungen an meinen Heldenpunkten für meinen Charakter nur EINMALIG (vollständige Wiedergeburt ausgeschlossen) möglich sind und dass sie ' . $int_cost . ' Donationpoints kosten. Mit dem Klicken dieses Buttons möchte ich sie bestätigen und somit durchführen.`b`c';
		$str_output .= '<div align="center"><form action="' . $str_self . '?op=change&subop=do" method="POST">';
		$str_output .= '<input type="hidden" name="new_points" value="' . urlencode(utf8_serialize($arr_new_dp)) . '">';
		$str_output .= '<input type="hidden" name="reason" value="' . urlencode($str_reason) . '">';
		$str_output .= '<input id="download_button" type="submit" value="Download starten"></div></form></div>';
		$str_output .= JS::encapsulate('
			var count = 30;
			counter();
			function counter () {
				if(count == 0) {
					document.getElementById("download_button").value = "Änderungen vornehmen";
					document.getElementById("download_button").disabled = false;
				} else {
					document.getElementById("download_button").value = "(noch "+count+" Sekunden)";
					document.getElementById("download_button").disabled = true;
					count--;
					setTimeout("counter()",1000);
				}
			}
		');
		addnav('', $str_self . '?op=change&subop=do');
	}
	addnav('Aktionen');
	addnav('Abbruch', $str_self . '?op=change&subop=abort');
}
elseif(($_GET['op'] == "change") && ($_GET['subop'] == "do"))
{
	addnav('Weiter', $str_self . '?op=change&subop=abort');
	$arr_new_dp = utf8_unserialize(urldecode($_POST['new_points']));
	$int_ff = 0;
	$int_at = 0;
	$int_de = 0;
	$int_hp = 0;
	$int_changes = 0;
	foreach($arr_new_dp as $key => $val)
	{
		if(array_key_exists($key, $session['user']['dragonpoints']))
		{
			if($arr_new_dp[$key] != $session['user']['dragonpoints'][$key])
			{
				$int_changes++;
				if($session['user']['dragonpoints'][$key] == "ff")
					$int_ff--;
				if($session['user']['dragonpoints'][$key] == "at")
					$int_at--;
				if($session['user']['dragonpoints'][$key] == "de")
					$int_de--;
				if($session['user']['dragonpoints'][$key] == "hp")
					$int_hp -= 5;
				if($arr_new_dp[$key] == "ff")
					$int_ff++;
				if($arr_new_dp[$key] == "at")
					$int_at++;
				if($arr_new_dp[$key] == "de")
					$int_de++;
				if($arr_new_dp[$key] == "hp")
					$int_hp += 5;
			}
		}
	}
	$int_cost = ceil((double)$int_changes / 5.0) * getsetting('cost_dragonpoints_change', 500);
	if((count($session['user']['dragonpoints']) != count($arr_new_dp)) || ($session['user']['dragonkills'] != count($arr_new_dp)))
	{
		$str_output .= '`$`bUps... da scheint ein kleiner Fehler aufgetreten zu sein...`b`nDie Administration wurde automatisch informiert, du brauchst nichts weiter zu tun.';
		systemlog('HACKVERSUCH (?) - unstimmige Anzahl Elemente des Drachenpunkte-Arrays: ' . count($arr_new_dp), 0, $session['user']['acctid']);
	}
	elseif($int_changes > getsetting('max_dragonpoints_change', 20))
	{
		$str_output .= '`$`bUps... da scheint ein kleiner Fehler aufgetreten zu sein...`b`nDie Administration wurde automatisch informiert, du brauchst nichts weiter zu tun.';
		systemlog('HACKVERSUCH (?) - manipuliertes POST-Array bei der Heldenpunkte-Änderung: ' . $int_changes . ' Änderungen übertragen', 0, $session['user']['acctid']);
	}
	elseif($session['user']['donation'] - $session['user']['donationspent'] < $int_cost)
	{
		$str_output .= '`$`bUps... so viele Donationpoints hast du gar nicht...`b';
	}
	else
	{
		$str_output .= '`b`^Du bezahlst ' . $int_cost . ' Donationpoints und...`b`#';
		$session['user']['donationspent'] += $int_cost;
		if($int_at != 0)
			$str_output .= '`nDu ' . ($int_at > 0 ? 'bekommst' : 'verlierst') . ' ' . abs($int_at) . ' permanente' . (abs($int_at) == 1 ? 'n' : '') . ' Angriffspunkt' . (abs($int_at) != 1 ? 'e' : '') . '.';
		if($int_de != 0)
			$str_output .= '`nDu ' . ($int_de > 0 ? 'bekommst' : 'verlierst') . ' ' . abs($int_de) . ' permanente' . (abs($int_de) == 1 ? 'n' : '') . ' Verteidigungspunkt' . (abs($int_de) != 1 ? 'e' : '') . '.';
		if($int_hp != 0)
			$str_output .= '`nDu ' . ($int_hp > 0 ? 'bekommst' : 'verlierst') . ' ' . abs($int_hp) . ' permanente' . (abs($int_hp) == 1 ? 'n' : '') . ' Lebenspunkt' . (abs($int_hp) != 1 ? 'e' : '') . '.';
		if($int_ff != 0)
			$str_output .= '`nDu kannst von nun an jeden Tagesabschnitt ' . abs($int_ff) . ' Runde' . (abs($int_at) != 1 ? 'n' : '') . ' ' . ($int_ff > 0 ? 'mehr' : 'weniger') . ' im Wald kämpfen.';
		$session['user']['maxhitpoints'] += $int_hp;
		$session['user']['attack'] += $int_at;
		$session['user']['defence'] += $int_de;
		$session['user']['dragonpoints'] = $arr_new_dp;
		user_set_aei(array('dragonpoints_changed' => 1), $session['user']['acctid']);
		systemlog('Schrein der Erinnerung: ' . ($int_at != 0 ? (($int_at > 0 ? '+' : '') . $int_at) : '') . 'AT ' . ($int_de != 0 ? (($int_de > 0 ? '+' : '') . $int_de) : '') . 'DE ' . ($int_hp != 0 ? (($int_hp > 0 ? '+' : '') . $int_hp) : '') . 'HP ' . ($int_ff != 0 ? (($int_ff > 0 ? '+' : '') . $int_ff) : '') . 'WK (' . $int_cost . 'DP) - Grund: ' . urldecode($_POST['reason']), 0, $session['user']['acctid']);
		clearnav();
		addnav('Erwachen', $str_self . '?op=change&subop=abort');
	}
}
elseif(($_GET['op'] == "reset") && ($_GET['subop'] == ""))
{
	addnav('Aktionen');
	addnav('Abbruch', $str_self);
	$str_output .= get_extended_text('dragonpointreset_info');
	$str_output .= '`n<form action="' . $str_self . '?op=reset&subop=submit" method="POST">';
	$str_output .= '<textarea name="formular" class="input" cols="80" rows="20"></textarea>`n`n';
	$str_output .= '<input type="submit" value="Anfrage absenden"></form>';
	addnav('', $str_self . '?op=reset&subop=submit');
}
elseif(($_GET['op'] == "reset") && ($_GET['subop'] == "submit"))
{
	addnav('Zurück zum Club', 'rock.php');
	$arr_aei = user_get_aei('dragonpoints_changed');
	$formular = '`bBeantragung eines Heldenpunkte-Resets:`b`n`n' . $_POST['formular'];
	if($arr_aei['dragonpoints_changed'] == 1)
	{
		$formular .= '`n`n`iSysteminfo: Benutzer hat schon einmal seine Drachenpunkte geändert/ändern lassen`i';
	}
	db_insert('petitions', array(
			'author' 	=> $session['user']['acctid'],
			'date'		=> array('sql'=>true,'value'=>'NOW()'),
			'body'		=> $formular,
			'email'		=> $session['user']['emailaddress'],
			'charname'	=> $session['user']['login'],
			'pageinfo'	=> output_array($session,"Session:"),
			'lastact'	=> array('sql'=>true,'value'=>'NOW()'),
			'IP'		=> $session['lastip'],
			'ID'		=> $session['uniqueid'],
			'connected'	=> '',
			'kat'		=> 6
		)
	);

	$str_output .= '`2`bDeine Anfrage wurde versandt!`b';
}
output($str_output, true);
page_footer();
?>