<?php

//Gefixxt von Devilzimti..

// Modified by Maris

$file = "specialty_blank";  // Ersetze blank mit dem namen der Datei hinter specialty_
// Alle blank müssen ersetzt werden!

function specialty_blank_info()
{
	global $info,$file;
	$info = array(
	"author"=>"", // Dein Name
	"version"=>"", // Die Versionsnummer
	"download"=>"",  // eventueller Download (leerlassen, falls kein Download gewünscht
	"filename"=>$file,  // So lassen
	"specname"=>"Nichts", // Name der Spezialfähigkeit
	"color"=>"`&",  // Farbcode
	"category"=>"Leere", // Kategorie
	"fieldname"=>"blank" // Feldname  (Für Level & Anwendungen)
	);
}

function specialty_blank_install()
{
	global $info;
	specialty_blank_info();

	$sql = "
		INSERT INTO
			`specialty`
		SET
			filename		= '".$info['filename']."'
			,usename		= '".$info['fieldname']."'
			,specname		= '".$info['specname']."'
			,category		= '".$info['category']."'
			,author			= '".$info['author']."'
			,active			= '0'
	";
	db_query($sql);

	// Ab hier optionale Datenbankeinträge

}

function specialty_blank_uninstall()
{
	global $info;
	specialty_blank_info();

	$sql = "
		DELETE FROM
			`specialty`
		WHERE
			`filename`		= '".$info['filename']."'
	";
	db_query($sql);

	// Die installierten, optionalen Datenbankeinträge rückgängig machen

}

function specialty_blank_run(
	 $underfunction
	,$mid				= 0
	,$beginlink			= "forest.php?op=fight"
	,$varvar			= "session"
)
{
	global
		$session
		,$info
		,$script
		,$cost_low
		,$cost_medium
		,$cost_high
		;

    /** @noinspection PhpUndefinedFunctionInspection */
    speciality_blank_info();

	switch($underfunction)
	{
		case "fightnav":
			if ($varvar == 'session')
			{
				$uses = $session['user']['specialtyuses'][$info['fieldname'] . 'uses'];
			}
			else
			{
				$uses = $GLOBALS[$varvar]['specialtyuses'][$info['fieldname'] . 'uses'];
			}
			// --> ~~~~ <-- Mit den Texten ersetzen
			// Erste Anwendung + Titelschema
			if ($uses >= 1)
			{
				addnav($info['color'] . "~~~~", '');

				addnav(
					 $info['color'] . "&#149; ~~~~`7 (1/" . $uses . ")`0"
					,$beginlink . "&skill=" . $info['fieldname'] . "&l=1"
					,true
				);
			}

			// 2. Anwendung
			if ($uses >= 2)
			{
				addnav(
					 $info['color'] . "&#149; ~~~~`7 (2/" . $uses . ")`0"
					,$beginlink . "&skill=" . $info['fieldname'] . "&l=2"
					,true
				);
			}

			// 3. Anwendung
			if ($uses >= 3)
			{
				addnav(
					$info['color'] . "&#149; ~~~~`7 (3/" . $uses . ")`0"
					,$beginlink . "&skill=" . $info['fieldname'] . "&l=3"
					,true
				);
			}

			// 4. Anwendung
			if ($uses >= 5)
			{
				addnav(
					$info['color'] . "&#149; ~~~~`7 (5/" . $uses .")`0"
					,$beginlink . "&skill=" . $info['fieldname'] . "&l=5"
					,true
				);
			}
		break;

		case "backgroundstory":
			output(); // Hintergrundgeschichte, verwende Funktion output
		break;

		case "link":
			// ~~ Ersetzen mit dem Text, den Rest so lassen
			output(
				create_lnk(
					 '('.$info['color'].$info['specname'].'`0)`n`n~~'
					,'char_changes.php?setspecialty=' . $mid
					,true
					,true
					,false
					,false
					,$info['color'].$info['specname']."`0"
					,CREATE_LINK_LEFT_NAV_HOTKEY
				)
			);
		break;

		case "buff":
			if ($varvar == 'session')
			{
				$uses = $session['user']['specialtyuses'][$info['fieldname'] . 'uses'];
			}
			else
			{
				$GLOBALS[$varvar]['specialtyuses'] = utf8_unserialize($GLOBALS[$varvar]['specialtyuses']);
				$uses = $GLOBALS[$varvar]['specialtyuses'][$info['fieldname'] . 'uses'];
			}
			$l = (int)$_GET['l'];

			if ($uses >= $l)
			{
				switch($l)
				{
					case 1:
						$buff = array(
							// Buff für Anwendung 1
						);

						$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '1'] = $buff;
					break;

					case 2:
						$buff = array(
							// Buff für Anwendung 2
						);

						$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '2'] = $buff;
					break;

					case 3:
						$buff = array(
							// Buff für Anwendung 3
						);

						$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '3'] = $buff;
					break;

					case 5:
						$buff = array(
							// Buff für Anwendung 4
						);

						if($varvar=="session")
						{
							$session['user']['reputation']--;  // Stärkster Zauber verwendet? Nicht sehr ehrenhaft...
						}

						$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '5'] = $buff;
					break;
				}

				if ($varvar=="session")
				{
					$session['user']['specialtyuses'][$info['fieldname'] . 'uses'] -= $l;
				}
				else
				{
					$GLOBALS[$varvar]['specialtyuses'][$info['fieldname'] . 'uses'] -= $l;
				}
			}
			else
			{
				$buff = array(
					// Buff, falls User zu wenig Anwenungen hat (Trifft normalerweise nicht ein, aber vielleicht is es ein Cheater ;)
				);
				if($varvar=="session")
				{
					$session['bufflist'][$info['fieldname'] . '0'] = $buff;
					$session['user']['reputation']--; // Cheater sind unehrenhaft =)
				}
				else
				{
					$GLOBALS[$varvar]['bufflist'][$info['fieldname'] . '0'] = $buff;
				}
			}

			if ($varvar != 'session')
			{
				$GLOBALS[$varvar]['specialtyuses']=utf8_serialize($GLOBALS[$varvar]['specialtyuses']);
			}
		break;

		case "academy_desc":
			// Akademie - Beschreibung der Lehrstunden
			output();
		break;


		case "academy_pratice":
			// Praktische Stunde & User betrunken, der Text
			output();

			// Nicht weglöschen, aber anpassen (Produkt ändern)
			$session['user']['hitpoints'] = round($session['user']['hitpoints'] - $session['user']['hitpoints'] * 0.2);
		break;


		case "weahter":
			// Eingreifen in Wetterbonus
		break;
	}
}
?>