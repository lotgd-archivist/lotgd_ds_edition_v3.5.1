<?php
/**
 * Datei definiert Bossgegner für Atrahor
 */

/**
 * Wo liegen die Include Dateien für die Bossgegner
 */
define('BOSS_PATH','./module/boss_modules/');

/**
 * Array für die Bossgegner. Jeder Bossgegner muss hier registriert werden
 */
$g_arr_boss = array
(
	'green_dragon' => array
	(
		'id'		=> 'green_dragon',			//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`@Der Grüne Drache`0',	//Name mit Farbcode
		'inc'		=> 'green_dragon.php',		//Includedatei mit dem nötigen Code
		'min_dk'	=> 0,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 1,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0,						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => false,//Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> 'G?`@Den Grünen Drachen suchen`0',	//Wie lautet der Name der Navigation
		'sex'		=> 1,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 18,						//Level des Drachen
		'weapon'	=> 'Gigantischer Flammenstoss',	//Waffenname
		'attack'	=> 45,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 25,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 300,						//Mindesthitpoints
		'gain_reputation'=> 2,					//Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 2,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>5,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>5,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'black_dragon' => array
	(
		'id'		=> 'black_dragon',			//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`yDer Schwarze Drache`0',	//Name mit Farbcode
		'inc'		=> 'black_dragon.php',		//Includedatei mit dem nötigen Code
		'min_dk'	=> 1,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 10,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0, //Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => false,//Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> 'S?`$Den Schwarzen Drachen suchen`0',	//Wie lautet der Name der Navigation
		'sex'		=> 1,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 20,						//Level des Drachen
		'weapon'	=> '`WGigantische Zähne und Klauen',	//Waffenname
		'attack'	=> 45,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 30,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 350,						//Mindesthitpoints
		'gain_reputation'=> 3,					//Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 3,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>10,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>10,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'jormungandr' => array
	(
		'id'		=> 'jormungandr',			//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`yJörmungandr, der Weltumspannende`0',	//Name mit Farbcode
		'inc'		=> 'jormungandr.php',		//Includedatei mit dem nötigen Code
		'min_dk'	=> 0,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 1,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0, 						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => true, //Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> 'H?`yDer Riese Hymir`0',	//Wie lautet der Name der Navigation
		'sex'		=> 0,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 20,						//Level des Drachen
		'weapon'	=> '`WPest und Galle',			//Waffenname
		'attack'	=> 45,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 30,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 300,						//Mindesthitpoints
		'gain_reputation'=> 3,					//Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 5,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>10,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>15,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'nidhoggr' => array
	(
		'id'		=> 'nidhoggr',				//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`yNidhöggr`0',			//Name mit Farbcode
		'inc'		=> 'nidhoggr.php',			//Includedatei mit dem nötigen Code
		'min_dk'	=> 0,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 2,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0, 						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => true, //Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> 'N?`yNidhöggr - Der Menschenwürger`0',	//Wie lautet der Name der Navigation
		'sex'		=> 0,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 20,						//Level des Drachen
		'weapon'	=> '`WMissgunst und Bosheit',			//Waffenname
		'attack'	=> 25,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 40,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 500,						//Mindesthitpoints
		'gain_reputation'=> 5,					//Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 3,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>10,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>15,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'lichking' => array
	(
		'id'		=> 'lichking',				//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`(Lichkönig`0',			//Name mit Farbcode
		'inc'		=> 'lichking.php',			//Includedatei mit dem nötigen Code
		'min_dk'	=> 0,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 1,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0, 						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => true, //Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> 'L?`(Lichkönig folgen`0',//Wie lautet der Name der Navigation
		'sex'		=> 0,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 20,						//Level des Drachen
		'weapon'	=> '`WKörperlose Angst',	//Waffenname
		'attack'	=> 45,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 25,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 300,						//Mindesthitpoints
		'gain_reputation'=> 5,					//Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 3,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>15,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>15,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'huntgod' => array
	(
		'id'		=> 'huntgod',				//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`QDer Große Hirsch`0',	//Name mit Farbcode
		'inc'		=> 'huntgod.php',			//Includedatei mit dem nötigen Code
		'min_dk'	=> 0,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 1,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0,						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => true,//Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> 'P?`@Die letzte Prüfung`0',	//Wie lautet der Name der Navigation
		'sex'		=> 0,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 18,						//Level des Drachen
		'weapon'	=> 'Mystisches Geweih',		//Waffenname
		'attack'	=> 45,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 25,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 300,						//Mindesthitpoints
		'gain_reputation'=> 2,					//Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 2,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>5,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>5,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'jackolantern' => array
	(
		'id'		=> 'jackolantern',			//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`QJack-O-Lantern`0',	//Name mit Farbcode
		'inc'		=> 'jackolantern.php',		//Includedatei mit dem nötigen Code
		'min_dk'	=> 0,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 1,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0, 						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => true, //Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> 'J?`QJack-O-Lantern`0',	//Wie lautet der Name der Navigation
		'sex'		=> 0,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 20,						//Level des Drachen
		'weapon'	=> '`QSeelen',				//Waffenname
		'attack'	=> 25,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 40,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 500,						//Mindesthitpoints
		'gain_reputation'=> 5,					//Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 3,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>10,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>15,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'fenris' => array
	(
		'id'		=> 'fenris',				//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`mFenriswolf`0',		//Name mit Farbcode
		'inc'		=> 'fenris.php',			//Includedatei mit dem nötigen Code
		'min_dk'    => 50,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 1,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0,						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => true,	//Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> 'R?`(Rufe Hugin und Munin`0',    //Wie lautet der Name der Navigation
		'sex'		=> 0,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 18,						//Level des Drachen
		'weapon'	=> '`eScharfe Zähne',		//Waffenname
		'attack'	=> 50,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 25,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 500,						//Mindesthitpoints
		'gain_reputation'=> 2,                  //Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 2,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>10,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>10,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'grinch' => array
	(
		'id'		=> 'grinch',				//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`@Der Grinch`0',		//Name mit Farbcode
		'inc'		=> 'grinch.php',			//Includedatei mit dem nötigen Code
		'min_dk'    => 0,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 1,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0,						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => true,	//Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> '`fIn Weihnachtsstimmung ausbrechen`0',    //Wie lautet der Name der Navigation
		'sex'		=> 0,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 18,						//Level des Drachen
		'weapon'	=> '`@Scharfe Krallen',		//Waffenname
		'attack'	=> 50,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 25,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 500,						//Mindesthitpoints
		'gain_reputation'=> 2,                  //Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 2,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>5,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>10,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'killer_cony' => array
	(
		'id'		=> 'killer_cony',			//ID des Bossgegners
		'enabled'	=> false,					//Eingeschaltet
		'name'		=> '`5Ki`=ll`%er`xka`%in`=ch`5en`0',		//Name mit Farbcode
		'inc'		=> 'killer_cony.php',		//Includedatei mit dem nötigen Code
		'min_dk'    => 10,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 2,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0,						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => false,//Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>true,				//Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name'	=> '`5Das Ki`=ll`%er`xka`%in`=ch`5en`0`0',    //Wie lautet der Name der Navigation
		'sex'		=> 0,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 30,						//Level des Drachen
		'weapon'	=> '`8Raffzähne`0',			//Waffenname
		'attack'	=> 400,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 40,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 500000,					//Mindesthitpoints
		'gain_reputation'=> 10,					//Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 5,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>20,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>10,						//Wieviele Charmepunkte werden verloren beim Tod
	),
	'skylla' => array
	(
		'id' => 'skylla', //ID des Bossgegners
		'enabled' => true, //Eingeschaltet
		'name' => '`BSkylla`0', //Name mit Farbcode
		'inc' => 'skylla.php', //Includedatei mit dem nötigen Code
		'min_dk' => 40, //Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta' => 1, //Alle wieviel DK erscheint der Gegner
		'min_exp' => 0, //Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl' => 15, //Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => true, //Soll Funktion additional_nav_preconditions aufgerufen werden
		//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false, //Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name' => 'O?Dem Meer ein Opfer darbringen', //Wie lautet der Name der Navigation
		'sex' => 1, //Geschlecht 0=männlich, 1=weiblich
		'img' => null, //Pfad zu einem Bild dass den Boss zeigt (optional)
		'level' => 18, //Level des Drachen
		'weapon' => 'Schnappende Köpfe', //Waffenname
		'attack' => 60, //Attacklevel (wird noch abhängig vom User gebufft)
		'defense' => 60, //Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints' => 300, //Mindesthitpoints
		'gain_reputation'=> 3, //Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 3, //Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>6, //Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>6, //Wieviele Charmepunkte werden verloren beim Tod
	), 

	'hel' => array
	(
		'id' => 'hel', //ID des Bossgegners
		'enabled' => true, //Eingeschaltet
		'name' => '`BHel`0', //Name mit Farbcode
		'inc' => 'hel.php', //Includedatei mit dem nötigen Code
		'min_dk' => 0, //Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta' => 1, //Alle wieviel DK erscheint der Gegner
		'min_exp' => 0, //Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl' => 15, //Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => true,//Soll Funktion additional_nav_preconditions aufgerufen werden
		//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false, //Darf der Drache mehrfach am Tag herausgefordert werden
		'nav_name' => 'M?Modgúdr', //Wie lautet der Name der Navigation
		'sex' => 1, //Geschlecht 0=männlich, 1=weiblich
		'img' => null, //Pfad zu einem Bild dass den Boss zeigt (optional)
		'level' => 18, //Level des Drachen
		'weapon' => 'Endgültigkeit', //Waffenname
		'attack' => 55, //Attacklevel (wird noch abhängig vom User gebufft)
		'defense' => 65, //Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints' => 300, //Mindesthitpoints
		'gain_reputation'=> 3, //Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 3, //Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>5, //Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>5, //Wieviele Charmepunkte werden verloren beim Tod

	),
	'lechuck' => array
	(
		'id'		=> 'lechuck',				//ID des Bossgegners
		'enabled'	=> true,					//Eingeschaltet
		'name'		=> '`(Le`)Chuck`0',			//Name mit Farbcode
		'inc'		=> 'lechuck.php',			//Includedatei mit dem nötigen Code
		'min_dk'    => 1,						//Wieviele DKs sind nötig damit der Boss erscheint
		'dk_delta'	=> 1,						//Alle wieviel DK erscheint der Gegner
		'min_exp'	=> 0,						//Wieviele Erfahrungspunkte muss der User mindestens haben
		'min_lvl'	=> 15,						//Welches Level muss der User mindestens besitzen
		'additional_nav_preconditions' => false,//Soll Funktion additional_nav_preconditions aufgerufen werden
												//um zu überprüfen, ob der Bossgegner angezeigt werden darf?
		'multiple_challenge'=>false,			//Darf der Boss mehrfach am Tag herausgefordert werden
		'nav_name'	=> '`(Dichter `)Nebel`0',    //Wie lautet der Name der Navigation
		'sex'		=> 0,						//Geschlecht 0=männlich, 1=weiblich
		'img'		=> null,					//Pfad zu einem Bild dass den Boss zeigt (optional)
		'level'		=> 18,						//Level des Bosses
		'weapon'	=> '`8Klauen`0',			//Waffenname
		'attack'	=> 15,						//Attacklevel (wird noch abhängig vom User gebufft)
		'defense'	=> 30,						//Defenselevel (wird noch abhängig vom User gebufft)
		'hitpoints'	=> 500,						//Mindesthitpoints
		'gain_reputation'=> 10,					//Wieviele Ansehenpunkte werden gewonnen beim Sieg
		'defeat_reputation'=> 15,				//Wieviele Ansehenpunkte werden verloren beim Tod
		'gain_charm'=>2,						//Wieviele Charmepunkte werden gewonnen beim Sieg
		'defeat_charm'=>5,						//Wieviele Charmepunkte werden verloren beim Tod
	)
);

/**
 * Falls diese Datei in einer Funktion aufgerufen wurde,
 * wird die Variable global registriert.
 */
$GLOBALS['g_arr_boss'] = $g_arr_boss;


/**
 * Wenn die Moduldatei existiert wird sie inkludiert und der Array der den Bossgegner
 * definiert wird zurückgegeben
 *
 * @param string $str_name ID des Bossgegners
 * @return array Array des Bossgegners
 */
function boss_load_boss($str_name)
{
	global $g_arr_boss, $g_str_base_file;
	if(array_key_exists($str_name, $g_arr_boss) && file_exists(BOSS_PATH.$g_arr_boss[$str_name]['inc']))
	{
		include_once(BOSS_PATH.$g_arr_boss[$str_name]['inc']);
	}
	else
	{
		clear_data(true,false,false,true);
		page_header('Error:'.$str_name);
		output($str_name.': '.$g_arr_boss[$str_name]['inc'].' existiert nicht in '.BOSS_PATH. ' oder '.$g_arr_boss[$str_name]['name'].' existiert nicht in der Library Datei');
		addnav('Zurück',$g_str_base_file);
		page_footer();
	}
	return boss_get_boss_array($str_name);
}

/**
 * Gib den Array des Bossgegners zurück
 *
 * @param string $str_name ID des Bossgegners
 * @return array Array des Bossgegners
 */
function boss_get_boss_array($str_name)
{
	global $g_arr_boss;
	if(array_key_exists($str_name, $g_arr_boss))
	{
		return $g_arr_boss[$str_name];
	}
	else
	{
		return array();
	}
}

/**
 * Erstellt aus dem Bossgegner einen Atrahor Badguy Array
 * und powert diesen hoch anhand der Leistungswerte des Users.
 * Hierzu wird der aktuell geladene Bossgegner genutzt, weswegen zuvor
 * boss_load_boss($str_name) ausgeführt werden muss.
 *
 * @return array Badguy array
 */
function boss_get_badguy_array()
{
	global $session,$g_arr_current_boss, $badguy;
	$badguy = array(
	"creaturename"	=>$g_arr_current_boss['name'],
	"creaturelevel"	=>$g_arr_current_boss['level'],
	"creatureweapon"=>$g_arr_current_boss['weapon'],
	"creatureattack"=>$g_arr_current_boss['attack'],
	"creaturedefense"=>$g_arr_current_boss['defense'],
	"creaturehealth"=>$g_arr_current_boss['hitpoints'],
	"boss"			=>true,
	"diddamage"		=>0);

	// First, find out how each dragonpoint has been spent and count those
	// used on attack and defense.
	// Coded by JT, based on collaboration with MightyE
	$points = 0;
	if(is_array($session['user']['dragonpoints']))
	{
		foreach($session['user']['dragonpoints'] as $val)
		{
			if ($val=='at' || $val=='de')
			{
				$points++;
			}
		}
	}

	// Now, add points for hitpoint buffs that have been done by the dragon
	// or by potions!
	$points += (int)(($session['user']['maxhitpoints']-150)/5);

	// Okay.. *now* buff the dragon a bit.
	$points = round($points*0.85,0);

	$atkflux = e_rand(0, $points);
	$defflux = e_rand(0,$points-$atkflux);
	$hpflux = ($points - ($atkflux+$defflux)) * 5;
	$badguy['creatureattack']	+=$atkflux;
	$badguy['creaturedefense']	+=$defflux;
	$badguy['creaturehealth']	+=$hpflux;
	$badguy['creaturehealth']	*=1.65;

	$float_forest_bal = getsetting('forestbal',1.5);

	$badguy['creatureattack'] 	*= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
	$badguy['creaturedefense'] 	*= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
	$badguy['creaturehealth'] 	*= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];

	$badguy['creaturehealth'] = round($badguy['creaturehealth']);

	return $badguy;
}

/**
 * Sammelt alle nötigen Voraussetzungen zusammen ob der Endgegner herausgefordert werden darf
 * und schreibt ggf einen Naveintrag
 *
 * @param string $str_name ID des Bossgegners
 * @return bool true wenn alles erfüllt wurde, sonst false
 * @todo Autochallenge einbauen
 */
function boss_get_nav($str_name)
{
	global $g_arr_boss, $session, $access_control;
	if(array_key_exists($str_name, $g_arr_boss))
	{
		if(
			//Enabled
			$g_arr_boss[$str_name]['enabled'] == true &&
			//Mindestlevel erreicht
			$session['user']['level'] >= $g_arr_boss[$str_name]['min_lvl'] &&
			//Nur alle dk_delta Dragonkills anzeigen
			$session['user']['dragonkills'] % $g_arr_boss[$str_name]['dk_delta'] == 0 &&
			//Mindest DK Zahl
			$session['user']['dragonkills'] >= $g_arr_boss[$str_name]['min_dk'] &&
			//Mindest EXP
			$session['user']['experience'] >= $g_arr_boss[$str_name]['min_exp'] &&
			//Mehrere Angriffe gegen den Drachen am Tag erlaubt
			($session['user']['seendragon'] == 0 || $g_arr_boss[$str_name]['multiple_challenge'] === true) &&
			//Wenn es zusätzliche Bedingungen für die Bossnav gibt, werden diese ausgeführt innerhalb der Funktion. Die Funktion muss bool zurückgeben
			(($g_arr_boss[$str_name]['additional_nav_preconditions'] == true && boss_additional_nav_preconditions($str_name))
			|| $g_arr_boss[$str_name]['additional_nav_preconditions'] == false)
			)

		{
			addnav($g_arr_boss[$str_name]['nav_name'],'boss.php?boss='.$str_name);
			return true;
		}
		else
		{
			return false;
		}
		/* Entkommentieren um Check-Ergebnisse anzuzeigen *
		elseif($access_control->su_check(access_control::SU_RIGHT_DEV))
		{
			output('Boss enabled = '.$g_arr_boss[$str_name]['enabled'].'
			`nLevel>='.$g_arr_boss[$str_name]['min_lvl'].': '.($session['user']['level'] >= $g_arr_boss[$str_name]['min_lvl']?'true':'false').'
			`nDK_delta: '.($session['user']['dragonkills'] % $g_arr_boss[$str_name]['dk_delta'] == 0?'true':'false').'
			`nminDK>'.$g_arr_boss[$str_name]['min_dk'].': '.($session['user']['dragonkills']>=$g_arr_boss[$str_name]['min_dk']?'true':'false').'
			`nExp>'.$g_arr_boss[$str_name]['min_exp'].': '.($session['user']['experience']>=$g_arr_boss[$str_name]['min_exp']?'true':'false').'
			`nPreconditions: '.$g_arr_boss[$str_name]['additional_nav_preconditions'].'
			`nFunc vorhanden: '.(function_exists('boss_additional_nav_preconditions')?'true':'false'));
			if(function_exists('boss_additional_nav_preconditions'))
			{
				output('`nFunktionsergebnis: '.boss_additional_nav_preconditions($str_name));
			}
		}
		/**/
	}
}

/**
 * Für bestimmte Voraussetzungen für das auftreten eines Bossereignisses können in einer separaten Funktion
 * abgefragt werden. Diese Funktion überprüft, ob eine entsprechende Funktion für den aktuellen Boss vorhanden
 * ist und führt diese aus.
 *
 * @param string $str_name ID des Bossgegners
 * @return bool Die Bedingung wurde erfüllt/nicht erfüllt
 */
function boss_additional_nav_preconditions($str_name)
{
	include_once(BOSS_PATH.$str_name.'.php');
	if(function_exists('boss_check_additional_nav_preconditions'))
	{
		return boss_check_additional_nav_preconditions();
	}
	else
	{
		return true;
	}
}

/**
 * Berechnet alle Mali wenn ein User den Endgegner nicht besiegen konnte.
 * User töten, Items löschen, ...
 */
function boss_calc_defeat()
{
	global $session,$g_arr_current_boss,$badguy,$item_hook_info;

	//Bossbalance etwas einstellen
	if ($session['user']['balance_dragon'] > 0)
	{
		$session['user']['balance_dragon']=round($session['user']['balance_dragon']*0.5);
	}
	else
	{
		$session['user']['balance_dragon']--;
	}
	$session['user']['balance_dragon'] = max(-10,$session['user']['balance_dragon']);

	$session['user']['reputation'] -= $g_arr_current_boss['defeat_reputation'];
	$session['user']['charm']=max(0,$session['user']['charm'] - $g_arr_current_boss['defeat_charm']);

	$str_loose_log = 'Gld: '.$session['user']['gold'];

	$session['user']['gold']=0;
	$session['user']['hitpoints']=0;
	$session['user']['badguy']="";

	// item
	$item_hook_info ['min_chance'] = item_get_chance();

	$res = item_list_get(' owner='.$session['user']['acctid'].' AND deposit1=0 AND loose_dragon_death='.$item_hook_info ['min_chance'] , 'ORDER BY RAND() LIMIT 1' );

	if (db_num_rows($res) )
	{
		$item = db_fetch_assoc($res);

		if (item_delete(' id='.$item['id'] ) )
		{
			$str_loose_log .= ',Item: '.$item['name'];
			output('`n`4Du verlierst `^'.$item['name'].'`4!`n');
		}

	}

	// Knappe verlieren
		$gamedate=getsetting('gamedate','0005-01-01').'-'.getsetting('actdaypart',1);
			$sql = 'SELECT name,state,level FROM disciples 
			WHERE master='.(int)$session['user']['acctid'].' 
				AND state !=22
				AND free_day!="'.$gamedate.'"';
			
			$result = db_query($sql);
			$rowk = db_fetch_assoc($result);

	$kname=$rowk['name'];
	$kstate=$rowk['state'];

	if (($kstate>0) && ($kstate<20))
	{
		output('`n`4'.$g_arr_current_boss['name'].'`4 hat dich besiegt und deinen Knappen `^'.$kname.' `4 verschleppt!`n`n');
		disciple_remove();
		$str_loose_log .= ', Knappe';
	}

	debuglog("Drachentod: ".$str_loose_log);
}

/**
 * Berechnet alle Boni wenn der Endgegner besiegt wurde.
 * Setzt Waffen und Rüstungen, setzt den User wieder auf Level 1,
 * Gilden, Knappen, ... alles
 */
function boss_calc_victory_bonus()
{
	global $g_arr_current_boss, $session, $badguy, $titles, $Char;
	// Account Extra Info laden
	$row_extra = user_get_aei();
	// END Account Extra Info laden

	$sql = 'describe accounts';
	$result = db_query($sql);
	$hpgain = $session['user']['maxhitpoints'] - ($session['user']['level']*10);

	// Ausrüstung entfernen
	item_set_weapon('Fäuste',0,0,0,0,2);
	item_set_armor('Straßenkleidung',0,0,0,0,2);

	
	if ($session['user']['goldinbank']<0)
	{
		$debuglog.=', Schuldenerlass '.$session['user']['goldinbank'];
		$session['user']['goldinbank']=round($session['user']['goldinbank']/10);
		$debuglog.=' ~> '.$session['user']['goldinbank'];

	}
	else
	{
		//Bänker-Check - diese behalten 10% ihres Kontos
		if ($row_extra['job']==5)
		{
			$banker = 1;
			$debuglog.=', Bänkerbonus '.$session['user']['goldinbank'];
			$session['user']['goldinbank']=round($session['user']['goldinbank']/10);
			$debuglog.=' ~> '.$session['user']['goldinbank'];
		}
	}

	$nochange = array(
		 'acctid'			=> 1
		,'name'				=> 1
		,'sex'				=> 1
		,'password' 		=> 1
		,'marriedto'		=> 1
		,'charisma'			=> 1
		,'title'			=> 1
		,'login'			=> 1
		,'dragonkills'		=> 1
		,'locked'			=> 1
		,'loggedin'			=> 1
		,'superuser'		=> 1
		,'superuser_id_switch' => 1
		,'gems'				=> 1
		,'gemsinbank'		=> 1
		,'hashorse'			=> 1
		,'lastip'			=> 1
		,'uniqueid'			=> 1
		,'dragonpoints'		=> 1
		,'goldinbank'		=> ( ($session['user']['goldinbank'] < 0) || ($banker ==1) ? 1 : 0 )
		,'laston'			=> 1
		,'prefs'			=> 1
        ,'lastmotd'			=> 1
        ,'lastmotc'			=> 1
		,'emailaddress'		=> 1
		,'emailvalidation'	=> 1
		,'dragonage'		=> 1
		,'donation'			=> 1
		,'donationspent'	=> 1
		,'donationconfig'	=> 1
		,'pvpflag'			=> 1
		,'daysinjail'		=> 1
		,'charm'			=> 1
		,'house'			=> 1
		,'housekey'			=> 1
		,'banoverride'		=> 1
		,'punch'			=> 1
		,'battlepoints'		=> 1
		,'reputation'		=> 1
		,'petid'			=> 1
		,'petfeed'			=> 1
		,'marks'			=> 1
		,'profession'		=> 1
		,'ddl_rank'			=> 1
		,'activated'		=> 1
		,'guildid'			=> 1
		,'guildfunc'		=> 1
		,'guildrank'		=> 1
		,'expedition'		=> 1
		,'balance_dragon'	=> 1
		,'surights'			=> 1
		,'plu_mi'			=> 1
		,'conf_bits'		=> 1
		,'chat_status'		=> 1
		,'hadnewday'		=> 1
		,'exchangequest'	=> 1
		,'rename_weapons'	=> 1
		,'specialty'		=> 1
		,'race'				=> 1
		,'kleidung'			=> 1
    ,'nohof'			=> 1
    ,'calender_last' => 1
    ,'newmail' => 1
    ,'quests_temp' => 1
	);


	$bestage=$row_extra['bestdragonage'];

	$session['user']['dragonage'] = $session['user']['age'];
	if ($session['user']['dragonage'] <  $row_extra['bestdragonage'] ||	$row_extra['bestdragonage'] == 0)
	{
		$bestage = $session['user']['dragonage'];
	}
	$int_count = db_num_rows($result);
	for ($i=0; $i<$int_count; $i++)
	{
		$row = db_fetch_assoc($result);
		if ($nochange[$row['Field']])
		{
			continue;
		}
		else
		{
			$session['user'][$row['Field']] = $row["Default"];
		}
	}

	//Buffs löschen
	$session['bufflist'] = array();

	//Dragonboni
	$session['user']['dragonkills']++;
	$session['user']['reputation']+=$g_arr_current_boss['gain_reputation'];
	$session['user']['charm']+=$g_arr_current_boss['gain_charm'];

	$session['user']['laston']=date("Y-m-d H:i:s",time());

	//Hitpoints setzen
	$session['user']['maxhitpoints']+=$hpgain;
	$session['user']['hitpoints']=$session['user']['maxhitpoints'];

	//Dem Spieler Gold geben
	$int_start_gold = getsetting('newplayerstartgold',50);
	$session['user']['gold']+= min($int_start_gold * $session['user']['dragonkills'],6 * $int_start_gold);

	//User DP Punkte für den Sieg geben
	$points = min($session['user']['dragonkills'], getsetting('maxdp_dk',50) );

	//Logeintrag für Donationpoints
	$log = 'DK: Erhält '.$points.' Punkte. Davor: '.$session['user']['donation'];
	$session['user']['donation'] += $points;
	$log .= ' Danach: '.$session['user']['donation'];
	debuglog($log.$debuglog);

	//
	// Andere Einstellungen
	//

	// GILDENMOD
	require_once(LIB_PATH.'dg_funcs.lib.php');
	if ($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT)
	{
		$g = &dg_load_guild($session['user']['guildid'],array('points','type','build_list'));
		$session['user']['gold'] = dg_calc_boni($session['user']['guildid'],'player_dkgold',$session['user']['gold']);
		$g['points'] += $dg_points['dk'];
		dg_log($session['user']['login'].' DK: '.$dg_points['dk'].' GP');
		dg_save_guild();
	}
	// END GILDENMOD

	// Heldentatenzähler gesamt inkrementieren
	savesetting('dkcounterges',getsetting('dkcounterges',0)+1);


	// Handle titles (modded by talion, get rid of these odd name / color code problems
	//by adding additional backup fields for name and title in account_extra_info)

	$newtitle=$titles[$session['user']['dragonkills']][$session['user']['sex']];
	if (empty($newtitle))
	{
		$newtitle = $titles[sizeof($titles)-1][$session['user']['sex']];
	}

	$session['user']['title'] = $newtitle;

	// Name aktualisieren
	user_set_name(0);

	// END handle titles

	if(is_array($session['user']['dragonpoints']))
	{
		foreach($session['user']['dragonpoints'] as $val)
		{
			if ($val=="at" || $val=='atk')
			{
				$session['user']['attack']++;
			}
			elseif ($val=="de" || $val == 'def')
			{
				$session['user']['defence']++;
			}
		}
	}

	//Wer hat als letzter den Endgegner besiegt
	savesetting("newdragonkill",($session['user']['name']));

	//falls verurteilt: 2 Tage erlassen
	$row_extra['sentence']=max(0,$row_extra['sentence']-2);

	// ACCOUNT extra speichern
	user_set_aei(array('sentence'=>$row_extra['sentence'],'mastertrain'=>0,'worms'=>0,'minnows'=>0,'boatcoupons'=>0,'bestdragonage'=>$bestage,'gladiatorfights'=>15,'seenpirate'=>0) );

	// dragonkill ends arenafight
	$sql = "DELETE FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid'];
	db_query($sql);

	//Items verlieren
	$res = item_list_get(' owner='.$Char->acctid.' AND (
	(loose_dragon = 1 AND (deposit1='.ITEM_LOC_EQUIPPED.' OR deposit1=0))
	OR
	(loose_dragon = 2)) ' );
	$list = '-1';
	while ($i = db_fetch_assoc($res) )
	{
		$list .= ','.$i['id'];
	}
	item_delete(' id IN ( '.$list.' ) ','' );

	//Einträge in der User history
	if ($session['user']['dragonkills'] == 1)
	{
		addhistory('`^Erste Heldentat');
	}
	elseif ($session['user']['dragonkills'] == 10)
	{
		addhistory('`^Zehnte Heldentat');
	}
	elseif ($session['user']['dragonkills'] == 100)
	{
		addhistory('`^Hundertste Heldentat');
	}
	elseif ($session['user']['dragonkills'] == 1000)
	{
		addhistory('`^Tausendste Heldentat');
	}

	//Verlobung verzögern
	if($session['user']['charisma']>1 && $session['user']['charisma']<999)
	{
		$session['user']['charisma']--;
	}
}

/**
 * Wenn der Endgegner perfekt besiegt wurde, dann bekommt der User
 * ein paar zusätzliche Boni, die hier berechnet werden
 */
function boss_calc_flawless_victory_bonus()
{
	global $session;

	$session['user']['gold'] += 3*getsetting('newplayerstartgold',50);
	$session['user']['gems'] += 1;

	//User DP Punkte für den Sieg geben
	$points = min($session['user']['dragonkills'], getsetting('maxdp_dk',50) );

	$session['user']['donation']+=$points;
	debuglog($points.' Zusatzpunkte für eine perfekte Heldentat');

	//Drache war zu leicht, modifier etwas erhöhen
	if ($session['user']['balance_dragon'] < 0)
	{
		$session['user']['balance_dragon'] = 1;
	}
	else
	{
		$session['user']['balance_dragon']+=2;
	}
	$session['user']['balance_dragon'] = min(20,$session['user']['balance_dragon']);
}

/**
 * Schreibe einen Newseintrag wenn der User verloren hat
 */
function boss_write_defeat_news()
{
	addnews(boss_get_defeat_news_text().'`n'.get_taunt());
}

/**
 * Schreibe einen Newseintrag wenn der User gewonnen hat
 */
function boss_write_victory_news()
{
	addnews(boss_get_victory_news_text());
}

/**
 * Aufräumarbeiten am Ende des Skripts
 * Diese Funktion sollte immer aufgerufen werden, wenn der User das Bossskript verlässt.
 * Es setzt badguy und specialmisc zurück und führt sonstige Aufräumarbeiten durch
 */
function boss_clean_up()
{
	global $session, $badguy;
	unset ($badguy);

	$badguy=array();
	$session['user']['badguy']='';
	$session['user']['specialmisc']='';
}
?>
