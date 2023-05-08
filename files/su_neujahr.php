<?php

/**
 * Mini-Script für's Neujahrspecial
 * @author Jenutan for Atrahor.de 2007
 **/

require_once('common.php');

$str_filename = basename(__FILE__);

$str_output = '';
$tablename = '`user_online_newyear`';

page_header('Neujahrs-"Kontrolle"');
addnav('Zurück');
addnav('zur Grotte', 'superuser.php');
addnav('Aktualisieren', $str_filename);
addnav('Tabelle löschen und neu anlegen', $str_filename . '?op=neu',false,false,false,false,'Möchtest Du die Aufzeichnungen wirklich löschen?');



switch ($_GET['op'])
{
	case 'neu':
		$sql = "
			DROP TABLE IF EXISTS " . $tablename . "
		";
		$res = db_query($sql);
		$str_output .= 'Alte Datenbank gelöscht (Falls vorhanden gewesen)!`n';
		$sql = "
			CREATE TABLE " . $tablename . " (
				`acctid` BIGINT null ,
				`given` BOOL NOT null DEFAULT '0',
				UNIQUE (
					`acctid`
				)
			) ENGINE = MYISAM
		";
		db_query($sql);
		$str_output .= 'Neue Datenbank angelegt!`n';
	break;

	case '':
		$str_output .= 'Hier wurden alle User aufgezeichnet, die kurz nach Mitternacht am 1.1. online waren.`n`n';
		
		
		$sql = "
			SELECT
				`accounts`.`login`,
				" . $tablename . ".*
			FROM
				`accounts`,
				" . $tablename . "
			WHERE
				`accounts`.`acctid` = " . $tablename . ".`acctid`
		";
		$res = db_query($sql);

		$str_output .= form_header($str_filename . "?op=vergebe") . '<table>';
		while ($row = db_fetch_object($res))
		{
			$str_output .= '<tr><td>';
			if (!$row->given)$str_output .= '<input type="checkbox" name="togive[]" value="' . $row->acctid . '" checked="checked" />';
			$str_output .= '</td><td>' . $row->acctid . '</td><td>' . $row->login . '</td><td>';
			if ($row->given) $str_output .= '`$Schon vergeben!`0';
			$str_output .= '</td></tr>';
		}
		$str_output .= '
				</table>
				`n
				`n
				Wie viele Donas? <input name="howmuch" />`n
				<input type="submit" value="Vergeben" />
			</form>
		';
	break;
	
	case 'vergebe':
		$vergabe = (int) $_POST['howmuch'];
		if ($vergabe <= 0) die('nochmal zurück und die Menge überprüfen <:P');
		
		$sql = "0 = 1 ";

		foreach ($_POST['togive'] AS $key => $val)
		{
			$str_output .= 'Vergebe ' . $vergabe . ' Donas an Acctid: ' . $val . '`n';
			$sql .= " OR `acctid` = '" . $val . "' ";
			debuglog('vergab ' . $vergabe . ' Donas (Neujahrsscript) an:', $val);
		}
		
		$bool_result = user_update(
			array
			(
				'donation'=>array('sql'=>true,'value'=>'donation+'.$vergabe),
				'where'=>$sql
			)
		);

		if ($bool_result)
		{
			$str_output .= 'DB-Query erfolgreich ausgeführt!';
			$sql = "
				UPDATE
					" . $tablename . "
				SET
					`given` = '1'
			";
			db_query($sql);
		}
	break;

	default:
		$str_output .= 'Fehler ;)';

}

output($str_output);
page_footer();
?>