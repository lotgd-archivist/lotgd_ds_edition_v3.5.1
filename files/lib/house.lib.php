<?php
// Möbel
define('HOUSES_PRIVATE_FURNITURE',20); // Basiswert für Gemächer in unausgebauten Häusern
define('HOUSES_FURNITURE',45);	// Basiswert für unausgebaute Häuser

// houses.build_state
define('HOUSES_BUILD_STATE_IP',1);			// Ausbau im Bau
define('HOUSES_BUILD_STATE_EXT',2);			// Erweiterung im Bau
define('HOUSES_BUILD_STATE_INIT',4);		// Haus im Bau
define('HOUSES_BUILD_STATE_SELL',8);		// Haus zum Verkauf
define('HOUSES_BUILD_STATE_ABANDONED',16);	// Verlassen
define('HOUSES_BUILD_STATE_EMPTY',32);		// Leeres Grundstück
define('HOUSES_BUILD_STATE_RUIN',64);		// Bauruine

// house_extensions.loc
/**
 * Keller
 */
define('HOUSES_ROOM_BASEMENT',1);
/**
 * Erdgeschoss
 */
define('HOUSES_ROOM_GROUND',2);
/**
 * 1. Stock
 */
define('HOUSES_ROOM_1ST',4);
/**
 * 2. Stock
 */
define('HOUSES_ROOM_2ND',8);
/**
 * Dachgeschoss
 */
define('HOUSES_ROOM_ROOF',16);
/**
 * Turmgeschoss
 */
define('HOUSES_ROOM_TOWER',32);

// keylist.type
/**
 * Standardschlüssel
 */
define('HOUSES_KEY_DEFAULT',0);
/**
 * Gemachschlüssel
 */
define('HOUSES_KEY_PRIVATE',1);

// Pfade zu Moduldateien
define('HOUSES_BUILDS_PATH','./houses_modules/builds/');
define('HOUSES_EXT_PATH','./houses_modules/extensions/');

//
//Erklärung
//'id' 			= Array-Key des Anbaus Achtung! Als Key keine Sonderzeichen verwenden!
//'name' 		= Name des Anbaus
//'colname'		= Name mit Farbcodes
//'goldcost' 	= Basiskosten Gold
//'gemcost'		= Basiskosten Gems
//'max_amount'	= Max. Anzahl an Anbauten dieses Typs
//'desc' 		= Beschreibung
//'inc' 		= Moduldatei (z.B. bedroom.php)
//'special_job'	= ID des Berufs, der mit diesem Anbau verknüpft werden soll
//'maxlvl_job' 	= Max. Ausbaustufe mit Job
//'maxlvl_else'	= Max. Ausbaustufe ohne Job
//'room' 		= Ist der Anbau ein Gemach?
//'locked_right'= Zum Bauen dieses Anbaus benötigtes Recht
//'hide_config'	= Die Möglichkeit das Gemach zu konfigurieren abschalten?
//'hide_convert'= Die Möglichkeit ein vorhandenes Gemach zu konvertieren abschalten?
//'hide_chat'	= Die Möglichkeit zu chatten abschalten?
//'floor_level'	= Nur bei Gemächern: Das Haus darf nur auf diesen Stockwerken errichtet werden.
//'max_furn' 	= Nur bei Gemächern: Max. Anzahl an Möbeln
//'max_invi' 	= Nur bei Gemächern: Max. Anzahl an Einladungen
$g_arr_house_extensions = array
(
'stables'=>array(
'id'			=> 'stables'			// ID (= Array-Key) des Anbaus
,'name'			=> 'Die Ställe'			// Name
,'colname'		=> '`2Die Ställe`0'		// Name mit Farbcodes
,'goldcost'		=> 100000				// Basiskosten Gold
,'gemcost'		=> 250					// Basiskosten Gems
,'max_amount'	=> 1					// Max. Anzahl an Anbauten dieses Typs
,'desc'			=> 'In den Ställen können Tiere abgestellt werden, um sich sodann nützlich zu machen oder dem Besitzer das Führen eines Zweittiers zu ermöglichen.'	// Beschreibung
,'inc'			=> 'stables.php'		// Moduldatei
,'special_job'	=> JOB_FARMER			// ID des Berufs, der mit diesem Anbau verknüpft werden soll
,'maxlvl_job'	=> 10					// Max. Ausbaustufe mit Job
,'maxlvl_else'	=> 3					// Max. Ausbaustufe ohne Job
,'room'			=> false				// Gemach?
//,'locked_right'	=> access_control::SU_RIGHT_ISATALION	// Zum Bauen dieses Anbaus benötigtes Recht
),

'smithy'=>array(
'id'			=> 'smithy'
,'name'			=> 'Die Schmiede'
,'colname'		=> '`7Die Schmiede`0'
,'goldcost'		=> 50000
,'gemcost'		=> 50
,'max_amount'	=> 1
,'desc'			=> 'Das ist die Schmiede.'
,'inc'			=> 'smithy.php'
,'special_job'	=> JOB_SMITH
,'maxlvl_job'	=> 10					// Max. Ausbaustufe mit Job
,'maxlvl_else'	=> 3					// Max. Ausbaustufe ohne Job
,'locked_right'	=> access_control::SU_RIGHT_ISATALION	// Zum Bauen dieses Anbaus benötigtes Recht
,'room'			=> false				// Gemach?
),

'garden'=>array(
'id'			=> 'garden'
,'name'			=> 'Der Garten'
,'colname'		=> '`2Der Garten`0'
,'goldcost'		=> 50000
,'gemcost'		=> 50
,'max_amount'	=> 1
,'desc'			=> 'Ein (mit der richtigen Pflege) prächtig blühender Garten, der noch dazu einiges an Ertrag abwerfen kann.'
,'inc'			=> 'garden.php'
//,'special_job'	=> JOB_SMITH
,'maxlvl_job'	=> 3					// Max. Ausbaustufe mit Job
,'maxlvl_else'	=> 1					// Max. Ausbaustufe ohne Job
,'room'			=> false				// Gemach?
),

'alchemie'=>array(
'id'			=> 'alchemie'
,'name'			=> 'Alchemistisches Labor'
,'colname'		=> '`6Alchemistisches Labor`0'
,'goldcost'		=> 50000
,'gemcost'		=> 55
,'max_amount'	=> 1
,'desc'			=> '`5In diesem alchemistischen Labor kannst du allerlei alchemistische Prozeduren versuchen. Es ermöglicht die Herstellung neuer Dinge aus verschiedenen Zutaten.`0.'
,'inc'			=> 'alchemie.php'
,'special_job'	=> JOB_ALCHEMIST
,'maxlvl_job'	=> 7					// Max. Ausbaustufe mit Job
,'maxlvl_else'	=> 3					// Max. Ausbaustufe ohne Job
//,'locked_right'	=> access_control::SU_RIGHT_ISATALION	// Zum Bauen dieses Anbaus benötigtes Recht
,'room'			=> false				// Gemach?
),

/*	Die Veranda lässt sich erst vernünftig einsetzen wenn die Küche fertig ist,
da ich sowohl Wettereffekte als auch Festessen aus der Küche einbauen wollte
Ausbaustufe 1 erhält eine Badestelle,
Ausbaustufe 2 weiß ich noch nicht
Zugang zum Garten wenn man einen hat
'pation'=>array(
'id'			=> 'patio'
,'name'			=> 'Die Veranda'
,'colname'		=> '`6Veranda`0'
,'goldcost'		=> 5000
,'gemcost'		=> 25
,'max_amount'	=> 1
,'desc'			=> 'Auf der Veranda lässt es sich prima entspannen`0.'
,'inc'			=> 'patio.php'
,'maxlvl_else'	=> 3					// Max. Ausbaustufe ohne Job
//,'locked_right'	=> access_control::SU_RIGHT_ISATALION	// Zum Bauen dieses Anbaus benötigtes Recht
,'room'			=> false				// Gemach?
),*/

'bedroom'=>array(
'id'			=> 'bedroom'
,'name'			=> 'Schlafgemach'
,'colname'		=> '`7Schlafgemach`0'
,'goldcost'		=> 5000
,'gemcost'		=> 10
,'max_amount'	=> 10
,'desc'			=> 'Ein Schlafgemach eignet sich hervorragend als Ruhestätte, um dem hektischen Treiben in den Gassen zu entgehen. Hier kannst du dir nicht nur eine Pause gönnen, sondern auch ein wenig in Stille verweilen.'
,'inc'			=> 'bedroom.php'
,'room'			=> true
,'max_furn'		=> 30					// Nur bei Gemächern: Max. Anzahl an Möbeln
,'max_invi'		=> 10					// Nur bei Gemächern: Max. Anzahl an Einladungen
),

'gummizelle'=>array(
'id'			=> 'gummizelle'
,'name'			=> 'Gummizelle'
,'colname'		=> '`^Gummizelle`0'
,'goldcost'		=> 1000
,'gemcost'		=> 10
,'max_amount'	=> 5
,'desc'			=> 'Eine .... äh... Gummizelle?! ICH MUSS HIER RAUS!'
,'inc'			=> 'gummizelle.php'
,'room'			=> true
,'max_furn'		=> 25					// Nur bei Gemächern: Max. Anzahl an Möbeln
,'max_invi'		=> 25					// Nur bei Gemächern: Max. Anzahl an Einladungen
),

'bathroom'=>array(
'id'			=> 'bathroom'
,'name'			=> 'Badezimmer'
,'colname'		=> '`#Badezimmer`0'
,'goldcost'		=> 5000
,'gemcost'		=> 15
,'max_amount'	=> 10
,'desc'			=> 'So ein Badezimmer ist zwar nicht sehr groß, dafür fühlst du dich sofort wohl! Ein entspannendes Bad tut auch dem härtesten Krieger und der hitzigsten Dame gut.'
,'inc'			=> 'bathroom.php'
,'room'			=> true
,'max_furn'		=> 10
,'max_invi'		=> 3
),

'chimneyroom'=>array(
'id'			=> 'chimneyroom'
,'name'			=> 'Kaminzimmer'
,'colname'		=> '`qKaminzimmer`0'
,'goldcost'		=> 5000
,'gemcost'		=> 15
,'max_amount'	=> 3
,'desc'			=> 'Ein Feuer im Kamin lädt vor allem in kalten Nächten zum Aufwärmen ein. Nicht nur für ein kuscheliges Rendezvous zu zweit gedacht, sondern auch für eine gesellige Runde.'
,'inc'			=> 'chimneyroom.php'
,'room'			=> true
,'max_furn'		=> 30
,'max_invi'		=> 15
),

'banket'=>array(
'id'			=> 'banket'
,'name'			=> 'Bankettsaal'
,'colname'		=> '`tBankettsaal`0'
,'goldcost'		=> 10000
,'gemcost'		=> 100
,'max_amount'	=> 1
,'desc'			=> 'Eine gescheite Zeremonie braucht einen gescheiten Bankettsaal - das steht fest. Hier ist die Gelegenheit jedes Hausherrn, sein Haus zu einer feuchtfröhlichen Stätte des Frohsinns zu machen!'
,'inc'			=> 'banket.php'
,'room'			=> true
,'max_furn'		=> 60
,'max_invi'		=> 150
),

'emptyroom'=>array(
'id'			=> 'emptyroom'
,'name'			=> 'Leerer Raum'
,'colname'		=> '`tEin leerer Raum`0'
,'goldcost'		=> 1000
,'gemcost'		=> 10
,'max_amount'	=> 5
,'desc'			=> 'Ach herrlich, ein leerer Raum! Was man hier nicht alles einbauen und einlagern könnte...'
,'inc'			=> 'emptyroom.php'
,'room'			=> true
,'max_furn'		=> 25
,'max_invi'		=> 5
),

'storeroom'=>array(
'id'			=> 'storeroom'
,'name'			=> 'Abstellkammer'
,'colname'		=> '`tEine Abstellkammer`0'
,'goldcost'		=> 5000
,'gemcost'		=> 15
,'max_amount'	=> 2
,'desc'			=> 'In einer Abstellkammer kann man so allerlei Dinge einlagern, die man nicht ständig mit sich herumschleppen möchte - ideal für Messis und Sammler'
,'inc'			=> 'storeroom.php'
,'room'			=> true
,'max_furn'		=> 100
,'max_invi'		=> 5
,'hide_config'	=> false		//Die Möglichkeit das Gemach zu konfigurieren abschalten?
,'hide_convert'	=> true			//Die Möglichkeit ein vorhandenes Gemach zu konvertieren abschalten?
),

'rathole'=>array(
'id'			=> 'rathole'
,'name'			=> 'Mauseloch'
,'colname'		=> '`tEin Mauseloch`0'
,'goldcost'		=> 50
,'gemcost'		=> 1
,'max_amount'	=> 5
,'desc'			=> 'Ja, ein echtes Mauseloch! Bewohnt von einem Nagetier! Jeder sollte eines haben!'
,'inc'			=> 'rathole.php'
,'room'			=> true
,'max_furn'		=> 0
,'max_invi'		=> 0
,'hide_config'	=> true			//Die Möglichkeit das Gemach zu konfigurieren abschalten?
,'hide_convert'	=> true			//Die Möglichkeit ein vorhandenes Gemach zu konvertieren abschalten?
,'hide_chat'	=> true			//Die Möglichkeit zu chatten abschalten?
),

'ritualroom'=>array(
'id'			=> 'ritualroom'
,'name'			=> 'Ritualkammer'
,'colname'		=> '`!Ritualkammer`0'
,'goldcost'		=> 5000
,'gemcost'		=> 15
,'max_amount'	=> 2
,'desc'			=> 'Eine Ritualkammer bietet genau die richtige Atmosphäre, genau das passende Karma, um magische Rituale ihre volle Wirkung entfalten zu lassen. Ein Muss für jeden Magier.'
,'inc'			=> 'ritualroom.php'
,'room'			=> true
,'max_furn'		=> 30
,'max_invi'		=> 15
),

'observatory'=>array(
'enabled'		=> true		//Soll der Gemachtyp in der Liste angezeigt werden?
,'id'			=> 'observatory'
,'name'			=> 'Observatorium'
,'colname'		=> '`!Observatorium`0'
,'goldcost'		=> 15000
,'gemcost'		=> 60
,'max_amount'	=> 1
,'desc'			=> 'Das Observatorium ist der perfekte Ort für jeden Sternegucker, Wetterfrosch oder Romantiker.'
,'inc'			=> 'observatory.php'
,'room'			=> true
,'max_furn'		=> 10
,'max_invi'		=> 15
,'hide_config'	=> false		//Die Möglichkeit das Gemach zu konfigurieren abschalten?
,'hide_convert'	=> true			//Die Möglichkeit ein vorhandenes Gemach zu konvertieren abschalten?
,'hide_desc'	=> true 		//Ermöglicht es Einladungen auszusprechen, verbietet jedoch die Textänderung
,'hide_chat'	=> false		//Die Möglichkeit zu chatten abschalten?
,'floor_level'	=> HOUSES_ROOM_TOWER|HOUSES_ROOM_ROOF //Das Haus darf nur auf diesen Stockwerken errichtet werden.
),

'sauna'=>array(
'enabled'		=> true
,'id'			=> 'sauna'
,'name'			=> 'Sauna'
,'colname'		=> '`!Sauna`0'
,'goldcost'		=> 5000
,'gemcost'		=> 15
,'max_amount'	=> 2
,'desc'			=> 'Eine Sauna ist der ideale Entspannungsort nach einem anstrengenden Tag im Wald.'
,'inc'			=> 'sauna.php'
,'room'			=> true
,'max_furn'		=> 0
,'max_invi'		=> 8
,'hide_config'	=> false		//Die Möglichkeit das Gemach zu konfigurieren abschalten?
,'hide_convert'	=> true			//Die Möglichkeit ein vorhandenes Gemach zu konvertieren abschalten?
,'hide_desc'	=> true 		//Ermöglicht es Einladungen auszusprechen, verbietet jedoch die Textänderung
),

'arch'=>array(
'enabled'		=> true
,'id'			=> 'arch'
,'name'			=> 'Kellergewölbe'
,'colname'		=> '`!Kellergewölbe`0'
,'goldcost'		=> 5000
,'gemcost'		=> 15
,'max_amount'	=> 1
,'desc'			=> 'Ein Kellergewölbe, in dem man vieles lagern und vergessen kann. Wenn man genauer hinschaut gibt es auch vieles zu entdecken.'
,'inc'			=> 'arch.php'
,'room'			=> true
,'max_furn'		=> 40
,'max_invi'		=> 10
,'hide_config'	=> false		//Die Möglichkeit das Gemach zu konfigurieren abschalten?
,'hide_convert'	=> true			//Die Möglichkeit ein vorhandenes Gemach zu konvertieren abschalten?
,'hide_desc'	=> false 		//Ermöglicht es Einladungen auszusprechen, verbietet jedoch die Textänderung
,'hide_chat'	=> false		//Die Möglichkeit zu chatten abschalten?
,'floor_level'	=> HOUSES_ROOM_BASEMENT //Das Haus darf nur auf diesen Stockwerken errichtet werden.
),

'kitchen'=>array(
'enabled'		=> true
//,'locked_right'	=> access_control::SU_RIGHT_GROTTO
,'id'			=> 'kitchen'
,'name'			=> 'Küche'
,'colname'		=> '`tKüche`0'
,'goldcost'		=> 10000
,'gemcost'		=> 75
,'max_amount'	=> 1
,'desc'			=> 'Eine Küche ist der richtige Ort, um einfache Nahrungsmittel in ein leckeres Essen zu verwandeln.'
,'inc'			=> 'kitchen.php'
,'room'			=> true
,'max_furn'		=> 5
,'max_invi'		=> 8
),

'torturechamber'=>array(
'enabled'		=> true
,'id'			=> 'torturechamber'
,'name'			=> 'Folterkammer'
,'colname'		=> '`4Folterkammer`0'
,'goldcost'		=> 3000
,'gemcost'		=> 15
,'max_amount'	=> 1
,'desc'			=> 'Wer hätte denn nicht gern seine persönliche Folterkammer im Keller? Praktisch für gewisse Spiele...'
,'inc'			=> 'torturechamber.php'
,'room'			=> true
,'max_furn'		=> 20
,'max_invi'		=> 10
,'floor_level'	=> HOUSES_ROOM_BASEMENT #Folterkammern wollen wir nur im Keller.
,'hide_config'	=> false		//Die Möglichkeit das Gemach zu konfigurieren abschalten?
,'hide_convert'	=> true			//Die Möglichkeit ein vorhandenes Gemach zu konvertieren abschalten?
),

'hunterroom'=>array(
'id'			=> 'hunterroom'
,'name'			=> 'Jägerzimmer'
,'colname'		=> '`@Jägerzimmer`0'
,'goldcost'		=> 5000
,'gemcost'		=> 15
,'max_amount'	=> 1
,'desc'			=> 'Ein Jägerzimmer bietet genau die richtige Atmosphäre, um mit anderen Jägern und Jagd-Interessierten über die weidmännische Kunst zu philosophieren. Du könntest deine schönsten Jagdtrophäen hier platzieren. Ein Muss für jeden Jäger.'
,'inc'			=> 'hunterroom.php'
,'room'			=> true
,'max_furn'		=> 30
,'max_invi'		=> 15
),

'trainingroom'=>array(
'enabled'		=> true
,'id'			=> 'trainingroom'
,'name'			=> 'Trainingsraum'
,'colname'		=> '`@Trainingsraum`0'
,'goldcost'		=> 10950
,'gemcost'		=> 18
,'max_amount'	=> 1
,'desc'			=> 'Wenn du deine Kampfkünste verbessern willst oder einfach nur deine erworbenen Geräte ausstellen möchtest, dieser Trainingsraum bietet ausreichend Platz für allerlei Kampfübungsgeräte aus der Werkstatt von Bregomil Auerhahn.'
,'inc'			=> 'trainingroom.php'
,'room'			=> true
,'max_furn'		=> 40
,'max_invi'		=> 15
),

);


//
// Erklärung:
// - id: status-Wert, der Ausbau kennzeichnet; = Array-Key
// - name: Name des Ausbaus ohne Formatierungscodes
// - sex: Genus des Abankusbau-Namens. 0 = mask., 1 = fem.
// - colname: Name mit Formatierungscodes
// - next: Array mit IDs der nächstmöglichen Ausbaustufen
// - goldcost: Kosten in Gold
// - gemcost: Kosten in Gemmen
// - desc: Beschreibung
// - inc: 	Dateiname der Moduldatei. Es können für versch. cases auch versch. Module benannt werden, nach dem Schema:
//			array('build_finished'=>xy.php,'rip'=>zz.php,...)
//			Wenn dieses Feld ein String, wird genannte Datei für alle Cases herangezogen
// - goldmulti:	Faktor, mit der max. Gold in Schatztruhe multipliziert wird
// - gemmulti:	Faktor, mit der max. Gemmen in Schatztruhe multipliziert wird
// - navs: 	Array mit Navi-Optionen für den Haus-Innenraum. Schema: array( Naviname => Navilink )
//			Mit 'code' => true kann für diesen Navipunkt die Ausführung des 'navi'-Case in der gegebenen Moduldatei bestimmt werden
// - finished_msg: Nachricht, die ausgegeben wird, wenn der Bau beendet ist
// - enable_zero: Kann als Initialausbau errichtet werden
// - max_rooms: Max. Anzahl an Gemächern in Haus
// - max_keys: Max. Anzahl an Schlüsseln in Haus
// - keys_add: Schlüssel, die durch den Ausbau hinzugefügt (oder, wenn < 0, abgezogen) werden
// - forbidden_ext: ID der Anbauten (s.o., ohne Gemächer), die in jeweiligem Anbau verboten sind. Bsp: array('stables'=>true)
// - invi: Wenn true, ist Hausbio für Außenstehende nur eingeschränkt sichtbar
$g_arr_house_builds = array
(
10 => array	(
'id'			=> 10
,'name'			=> 'Anwesen'
,'sex'			=> 0
,'colname'		=> '`%Anwesen'
,'next'			=> array(14,17)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> '`7Ein `%Anwesen`7 würde sehr viel mehr an Reichtümern horten können als ein gewöhnliches Haus.`0'
,'inc'			=> 'anwesen.php'
,'goldmulti'	=> 5
,'gemmulti'		=> 3
,'finished_msg'	=> '`&Dein neues Anwesen wird viel mehr an Reichtümern aufnehmen können als dein altes Haus.`n'
,'enable_zero'	=> true
,'max_rooms'	=> 25
,'keys_add'		=> 5
),

14 => array	(
'id'			=> 14
,'name'			=> 'Villa'
,'sex'			=> 1
,'colname'		=> '`%Villa'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Eine `%Villa`7 würde noch viel mehr an Reichtümern horten können als ein Anwesen.`0'
,'inc'			=> 'anwesen.php'
,'goldmulti'	=> 10
,'gemmulti'		=> 6
,'finished_msg'	=> '`&Deine neue Villa wird noch mehr Reichtümer horten können als das Anwesen.`n'
,'max_rooms'	=> 27
,'keys_add'		=> 3
),

17 => array	(
'id'			=> 17
,'name'			=>'Gasthaus'
,'sex'			=> 0
,'colname'		=> '`%Gasthaus'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Ein `%Gasthaus`7 würde etwas mehr an Reichtümern horten können und zusätzlich die Möglichkeit der Stärkung bei einer guten Suppe bieten.`0'
,'inc'			=> 'anwesen.php'
,'goldmulti'	=> 5
,'gemmulti'		=> 3
,'navs'			=> array
(
'Mütterchens Kohlsuppe kosten'=>'housefeats.php?act=soup'
)
,'finished_msg'	=> '`&Dein neues Gasthaus wird dir und deinen Mitbewohnern eine willkommene Möglichkeit zur Rast und Stärkung bieten.`n'
,'max_rooms'	=> 30
,'keys_add'		=> 3
),

20 => array	(
'id'			=> 20
,'name'			=>'Festung'
,'sex'			=> 1
,'colname'		=> '`QFestung'
,'next'			=> array(24,27)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> '`7Eine `QFestung`7 bietet zusätzlichen Schutz gegen Angriffe.`0'
,'inc'			=> 'festung.php'
,'navs'			=> array
(
'In den Keller'=>'housefeats.php?act=cry'
)
,'finished_msg'	=> '`&Deine neue Festung wird ein sehr sicherer Ort für alle werden, die darin schlafen.'
,'enable_zero'	=> true
),

24 => array	(
'id'			=> 24
,'name'			=>'Turm'
,'sex'			=> 0
,'colname'		=> '`QTurm'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Ein `QMagierturm`7 bietet weiteren Schutz gegen Angriffe und ermöglicht ein Ritual zur Stärkung der mystischen Kräfte.`0'
,'inc'			=> 'festung.php'
,'navs'			=> array
(
'In den Keller'=>'housefeats.php?act=cry',
'R?Ritual abhalten (1 Edelstein)'=>'housefeats.php?act=ritual'
)
,'finished_msg'	=> '`&Dein neuer Turm wird durch seine Höhe noch sicherer für dich und deine Mitbewohner sein und durch seine Nähe zu den Sternen der perfekte Ort für magische Praktiken.'
),

27 => array	(
'id'			=> 27
,'name'			=>'Burg'
,'sex'			=> 1
,'colname'		=> '`QBurg'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Eine `QBurg`7 bietet extremen Schutz gegen Angriffe.`0'
,'inc'			=> 'festung.php'
,'navs'			=> array
(
'In den Keller'=>'housefeats.php?act=cry'
)
,'finished_msg'	=> '`&Deine neue Burg ist der Inbegriff von Sicherheit und Schutz. Es gibt praktisch keinen Ort, an dem du und deine Gäste unbesorgter und besser schlafen können.'
),

30 => array	(
'id'			=> 30
,'name'			=> 'Versteck'
,'sex'			=> 0
,'colname'		=> '`tVersteck'
,'next'			=> array(34,37)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> '`7Ein `tVersteck`7 ist kaum ein Ort zum bequemen Wohnen. Wer sich hier verkriecht ist von niemandem aufzuspüren. Dafür gibt es allerding kaum Lagermöglichkeiten für Gold und Edelsteine.`n`^Ein Versteck kann höchstens 5 Zimmer haben. Alle Schlüssel bis auf 5 werden verloren gehen, solange der Ausbau besteht!`0'
,'inc'			=> 'versteck.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array()
,'finished_msg'	=> '`&Dein neues Versteck wird jenen Unterschlupf bieten, die es sich mit Allem und Jedem verscherzt haben und nirgendwo mehr sicher sind. Der Hausschatz wird nur minimal, die Nacht nicht sehr erholsam sein, du hast nur noch 5 Schlüssel!`n'
,'enable_zero'	=> true
,'max_rooms'	=> 5
,'max_keys'		=> 5
,'invi'			=> true
),

34 => array	(
'id'			=> 34
,'name'			=> 'Refugium'
,'sex'			=> 0
,'colname'		=> '`tRefugium'
,'next'			=> array()
,'goldcost'		=> 100000
,'gemcost'		=> 100
,'desc'			=> '`7Ein `tRefugium`7 verliert den Nachteil des schlechten Schlafes und bietet weiterhin nahezu Unangreifbarkeit.`0'
,'inc'			=> 'versteck.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array()
,'finished_msg'	=> '`&Dein neues Refugium nutzt seinen Keller um die Versteckmöglichkeiten seiner Gäste größer und komfortabler zu gestalten. Du und deine Mitbewohner können dort nun ohne Beeinträchtigung nächtigen.'
,'max_rooms'	=> 8
,'max_keys'		=> 5
,'invi'			=> true
),

37 => array	(
'id'			=> 37
,'name'			=> 'Kellergewölbe'
,'sex'			=> 0
,'colname'		=> '`tKellergewölbe'
,'next'			=> array()
,'goldcost'		=> 100000
,'gemcost'		=> 100
,'desc'			=> '`7Ein `tKellergewölbe`7 mindert den Nachteil des schlechten Schlafes und bietet zusätzlich 2 weitere Schlüssel.`0'
,'inc'			=> 'versteck.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array()
,'finished_msg'	=> '`&Dein neues Kellergewölbe ist so gestaltet, dass es einen Bewohner mehr aufnehmen kann, auch der Komfort wurde ein klein wenig gehoben. Dennoch ist es immer noch nicht die schönste Art zu übernachten.`n'
,'max_rooms'	=> 8
,'max_keys'		=> 7	// sollte sich durch Mindestschlüsselsatz automatisch auffüllen
,'invi'			=> true
),

40 => array	(
'id'			=> 40
,'name'			=> 'Gildenhaus'
,'sex'			=> 0
,'colname'		=> '`5Gildenhaus'
,'next'			=> array(44,47)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> '`7Ein `5Gildenhaus`7 würde die Möglichkeit bieten zusätzlich Anwendungen im Spezialgebiet zu erhalten, wenn diese aufgebraucht sind.`0'
,'inc'			=> 'gildenhaus.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Mit den Gildenmeistern reden (1000 Gold)'=>'housefeats.php?act=fill'
)
,'finished_msg'	=> '`&Dein neues Gildenhaus wird seinen Bewohnern neue Anwendungen in ihren Spezialfähigkeiten gewähren, wenn diese aufgebraucht sind.'
,'enable_zero'	=> true
),

44 => array	(
'id'			=> 44
,'name'			=> 'Zunfthaus'
,'sex'			=> 0
,'colname'		=> '`5Zunfthaus'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Ein `5Zunfthaus`7 würde eine leichtere Möglichkeit bieten öfter ins Schloss zu können.`0'
,'inc'			=> 'gildenhaus.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Mit den Gildenmeistern reden (1000 Gold)'=>'housefeats.php?act=fill'
,'A?Zu den Abenteurern'=>'housefeats.php?act=adventure'
,'code'=>true
)
,'finished_msg'	=> '`&Dein neues Zunfthaus wird künftig erfahrene Abenteurer anlocken, von deren Erfahrung alle profitieren können.'
),

47 => array	(
'id'			=> 47
,'name'			=> 'Handelshaus'
,'sex'			=> 0
,'colname'		=> '`5Handelshaus'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Ein `5Handelshaus`7 ermöglicht dir den Kauf und Verkauf von Edelsteinen bei einem Händler von weit her.`0'
,'inc'			=> 'gildenhaus.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Mit den Gildenmeistern reden (1000 Gold)'=>'housefeats.php?act=fill'
,'S?Zum Schmuckhändler'=>'housefeats.php?act=gems',
'i?Zum Lieferanten'=>'housefeats.php?act=sendtrophy'
)
,'finished_msg'	=> '`&Dein neues Handelshaus wird der zentrale Punkt des Handelns werden.`nGeschäftsmänner werden von weither kommen.'
),

50 => array	(
'id'			=> 50
,'name'			=> 'Bauernhof'
,'sex'			=> 0
,'colname'		=> '`tBauernhof'
,'next'			=> array(54,57)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> '`7Ein `tBauernhof`7 ist ein Ort an dem sich Tiere besonders wohl fühlen und neue Kraft schöpfen können.`0'
,'inc'			=> 'bauernhof.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Tier versorgen'=>'housefeats.php?act=feed'
)
,'finished_msg'	=> '`&Dein neuer Bauernhof wird beliebig oft die Tiere seiner Gäste versorgen können.'
,'enable_zero'	=> true
,'house_extension_max_lvl_bonus' => 'stables'	//Ausbau begünstigt einen Anbautyp
,'house_extension_max_lvl_bonus_value' => 1		//Um wieviele Punkte begünstigt?
),

54 => array	(
'id'			=> 54
,'name'			=> 'Tierfarm'
,'sex'			=> 1
,'colname'		=> '`tTierfarm'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Eine `tTierfarm`7 ermöglicht das fachgerechte Training von Tieren.`0'
,'inc'			=> 'bauernhof.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		 => array
(
'r?Tier versorgen'=>'housefeats.php?act=feed'
,'i?Zum Tiertrainer'=>'housefeats.php?act=trainanimal'
)
,'finished_msg'	=> '`&Deine neue Tierfarm wird nicht nur die Versorgung der Tiere gewährleisten, sondern auch ihre Ausbildung.'
,'house_extension_max_lvl_bonus' => 'stables'	//Ausbau begünstigt einen Anbautyp
,'house_extension_max_lvl_bonus_value' => 1		//Um wieviele Punkte begünstigt?
),

57 => array	(
'id'			=> 57
,'name'			=> 'Gutshof'
,'sex'			=> 0
,'colname'		=> '`tGutshof'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Ein `tGutshof`7 ist ein Ort an dem sich schnell durch Arbeit Gold verdienen lässt.`0'
,'inc'			=> 'bauernhof.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Tier versorgen'=>'housefeats.php?act=feed'
,'a?Hart arbeiten'=>'housefeats.php?act=workhard'
)
,'finished_msg'	=> '`&Dein neuer Gutshof wird die Arbeit derer, die ihn bewohnen, reichlich vergolden und bietet somit eine gute Einnahmequelle bei finanziellen Engpässen.'
,'house_extension_max_lvl_bonus' => 'stables'	//Ausbau begünstigt einen Anbautyp
,'house_extension_max_lvl_bonus_value' => 1		//Um wieviele Punkte begünstigt?
),

60 => array	(
'id'			=> 60
,'name'			=> 'Gruft'
,'sex'			=> 1
,'colname'		=> '`TGruft'
,'next'			=> array(64,67)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> '`7Eine `TGruft`7 ist eine dunkle und finstre Unterkunft für dunkle und finstre Kreaturen. Hier kann man u.A. dem Blutgott huldigen.`0'
,'inc'			=> 'gruft.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array('code'=>true)
,'finished_msg'	=> '`&Deine neue Gruft wird den dunklen Göttern sicherlich gut gefallen.'
,'enable_zero'	=> true
),

64 => array	(
'id'			=> 64
,'name'			=> 'Krypta'
,'sex'			=> 1
,'colname'		=> '`TKrypta'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Eine `TKrypta`7 ermöglicht es bei Ramius ein gutes Wort für Verstorbene einzulegen.`0'
,'inc'			=> 'gruft.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'code'=>true
,'A?Zum Ahnenschrein'=>'housefeats.php?act=givepower'
)
,'finished_msg'	=> '`&Deine neue Krypta ermöglicht es dir und deinen Gästen mit kürzlich Verstorbenen in Kontakt zu treten und sie bei ihrer Suche nach Wiedererweckung zu unterstützen.'
),

67 => array	(
'id'			=> 67
,'name'			=> 'Katakomben'
,'sex'			=> 1
,'colname'		=> '`TKatakomben'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`TKatakomben`7 beherbergen einen rituellen Opferschrein mit dem es möglich ist sich selbst ins Reich der Toten zu befördern.`0'
,'inc'			=> 'gruft.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'code'=>true
,'O?Zum Opferschrein'=>'housefeats.php?act=suicide'
)
),

70 => array	(
'id'			=> 70
,'name'			=> 'Kerker'
,'sex'			=> 0
,'colname'		=> '`qKerker'
,'next'			=> array(74,77)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> '`7Ein `qKerker`7 hält üble Schurken und Verbrecher gefangen und erteilt ihnen ihre gerechte Strafe.`0'
,'inc'			=> 'kerker.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'q?Gefangene quälen'=>'housefeats.php?act=torture'
)
,'finished_msg'	=> '`&Dein neuer Kerker wird dir und deinen Mitbewohnern eine hohe Verantwortung über die Gefangenen übertragen.'
,'enable_zero'	=> true
),

74 => array	(
'id'			=> 74
,'name'			=> 'Gefängnis'
,'sex'			=> 0
,'colname'		=> '`qGefängnis'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Ein `qGefängnis`7 macht es möglich, Insassen zu befreien, allerdings zu einem hohen Preis.`0'
,'inc'			=> 'kerker.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'q?Gefangene quälen'=>'housefeats.php?act=torture'
,'O?Zum Oberaufseher'=>'housefeats.php?act=exchange'
)
,'finished_msg'	=> '`&Dein neues Gefängnis lässt dich und deine Mitbewohner ein wenig mehr Kontrolle über die Haftdauer der Insassen ausüben, wenn auch zu einem gewissen Preis.'
),

77 => array	(
'id'			=> 77
,'name'			=> 'Verlies'
,'sex'			=> 0
,'colname'		=> '`qVerlies'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Ein `qVerlies`7 ermöglicht es Gefangene in brutalen Käfigkämpfen gegen Bestien antreten zu lassen und mit einer guten Wette noch etwas Gold zu verdienen.`0'
,'inc'			=> 'kerker.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'q?Gefangene quälen'=>'housefeats.php?act=torture'
,'a?Zur Gefangenenarena'=>'housefeats.php?act=arena'
)
,'finished_msg'	=> '`&Dein neues Verlies bietet dir und deinen Mitbewohnern eine weitere grausige Möglichkeit die Gefangenen zu disziplinieren.'
),

80 => array	(
'id'			=> 80
,'name'			=> 'Kloster'
,'sex'			=> 0
,'colname'		=> '`&Kloster'
,'next'			=> array(84,87)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> '`7Ein `&Kloster`7 ist ein Ort der Heilung und der Frömmigkeit. Hier wird selbstlos jeder armen Seele geholfen.`0'
,'inc'			=> 'kloster.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Heilung erbitten'=>'housefeats.php?act=healing'
)
,'finished_msg'	=> '`&Dein neues Kloster wird seinen Bewohnern und allen Gästen stets Hilfe und Heilung bieten.'
,'enable_zero'	=> true
),

84 => array	(
'id'			=> 84
,'name'			=> 'Abtei'
,'sex'			=> 1
,'colname'		=> '`&Abtei'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Eine `&Abtei`7 ist ein Ort des Segens. Bei ausreichend Spende und Gebet wird dieser Segen jedem gewährt.`0'
,'inc'			=> 'kloster.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Heilung erbitten'=>'housefeats.php?act=healing'
,'S?Den Segen erbitten'=>'housefeats.php?act=bless'
)
,'finished_msg'	=> '`&Deine neue Abtei wird den Göttern sicherlich gut gefallen. Sei dir ihres Segens, bei eintsprechend hoher Opfergabe, gewiss.'
),

87 => array	(
'id'			=> 87
,'name'			=> 'Ritterorden'
,'sex'			=> 0
,'colname'		=> '`&Ritterorden'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Ein `&Ritterorden`7 ermöglicht es einen jungen Knappen als treuen Wegbegleiter zu erhalten.`0'
,'inc'			=> 'kloster.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Heilung erbitten'=>'housefeats.php?act=healing'
,'K?Einen Knappen annehmen (20 Edelsteine)'=>'housefeats.php?act=disciple'
,'code'=>true
)
,'finished_msg'	=> '`&Dein neuer Ritterorden zieht Recken aus dem ganzen Land an, die Helden suchen, um sie auf ihren Abenteuern zu begleiten.'
),

90 => array	(
'id'			=> 90
,'name'			=> 'Trainingslager'
,'sex'			=> 0
,'colname'		=> '`vTrainingslager'
,'next'			=> array(94,97)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> '`7Ein `vTrainingslager`7 beherbergt junge wie alte Krieger. Von den Veteranen kann man sehr viel lernen!`0'
,'inc'			=> 'trainingslager.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Mit den Veteranen trainieren (3000 Gold)'=>'housefeats.php?act=train'
)
,'finished_msg'	=> '`&Dein neues Trainingslager wird dir und deinen Mitbewohnern eine gute Kampfausbildung ermöglichen.'
,'enable_zero'	=> true
),

94 => array	(
'id'			=> 94
,'name'			=> 'Kaserne'
,'sex'			=> 1
,'colname'		=> '`vKaserne'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7In einer `vKaserne`7 lassen sich mit schweißtreibendem Training Angriff und Verteidigung verbessern!`0'
,'inc'			=> 'trainingslager.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Mit den Veteranen trainieren (3000 Gold)'=>'housefeats.php?act=train'
,'s?Mit den Meistern trainieren ('.($session['user']['level']*750).' Gold)'=>'housefeats.php?act=mastertrain'
)
,'finished_msg'	=> '`&Deine neue Kaserne wird dir und deinen Mitbewohnern die Möglichkeit geben durch harten und schmerzhaften Drill an Kampfeskraft zu gewinnen.'
),

97 => array	(
'id'			=> 97
,'name'			=> 'Söldnerlager'
,'sex'			=> 0
,'colname'		=> '`vSöldnerlager'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> '`7Im `vSöldnerlager`7 werten geschickte Schmiede Waffen und Rüstungen auf!`0'
,'inc'			=> 'trainingslager.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Mit den Veteranen trainieren (3000 Gold)'=>'housefeats.php?act=train'
,'s?Zum Lagerschmied'=>'housefeats.php?act=smith'
)
,'finished_msg'	=> '`&Dein neues Söldnerlager zieht Schurken aller Art, darunter auch begabte Schmiede, an.`nDiese werden für geringes Entgelt deine Ausrüstung verbessern.'
),

100 => array	(
'id'			=> 100
,'name'			=> 'Bordell'
,'sex'			=> 0
,'colname'		=> '`7Bordell'
,'next'			=> array(104,107)
,'goldcost'		=> 300000
,'gemcost'		=> 200
,'desc'			=> 'Ein Bordell ist ein Ort der Freude und der Lust. Nach einem Besuch ist so mancher Krieger erfolgreicher im Kampf.`0'
,'inc'			=> 'bordell.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Amüsieren (2000 Gold)'=>'housefeats.php?act=amuse'
)
,'finished_msg'	=> '`&Dein neues Bordell wird dir und deinen Gästen sicherlich sehr viel Freude bereiten.'
,'enable_zero'	=> true
),

104 => array	(
'id'			=> 104
,'name'			=> 'Rotlichtpalast'
,'sex'			=> 0
,'colname'		=> '`7Rotlichtpalast'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> 'Im Rotlichtpalast lassen sich wilde, stimmungserheiternde Orgien feiern.`0'
,'inc'			=> 'bordell.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'r?Amüsieren (2000 Gold)'=>'housefeats.php?act=amuse'
,'Orgie (3000 Gold)'=>'housefeats.php?act=orgy'
)
,'finished_msg'	=> '`&Dein neuer Rotlichtpalast bietet dir eine weitere Möglichkeit deine Stimmung zu verbessern.'
),

107 => array	(
'id'			=> 107
,'name'			=> 'üble Spelunke'
,'sex'			=> 1
,'colname'		=> '`7üble Spelunke'
,'next'			=> array()
,'goldcost'		=> 500000
,'gemcost'		=> 500
,'desc'			=> 'Eine Spelunke zieht Gauner, Ganoven und Schläger an, die sich gern für ein kleines Entgeld beauftragen lassen.`0'
,'inc'			=> 'bordell.php'
,'goldmulti'	=> 1
,'gemmulti'		=> 1
,'navs' 		=> array
(
'code'=>true
,'r?Amüsieren (2000 Gold)'=>'housefeats.php?act=amuse'
,'O?Zum Orkisch Roulette'=>'housefeats.php?act=roulette'
)
,'finished_msg'	=> '`&Die Spelunke hat neben der Möglichkeit des Amüsierens für dich auch noch einige schlagkräftige Argumente für deine Feinde übrig.'
),
);


/**
 * Fügt einen oder mehrere Schlüssel zur DB hinzu
 *
 * @param array Assoz. Array der zu ändernden Spalten
 * @param int Anzahl der Schlüssel mit diesen Eigenschaften, die hinzugefügt werden sollen
 * @return bool false bei Fehler, sonst true
 * @author talion
 */
function house_keys_add ($arr_changes, $int_count = 1) {

	// Build up a multiple INSERT if necessary
	$str_sql = 'INSERT INTO keylist (owner,value1,value2,type,chestlock,gold,gems,description) ';

	$str_sql.='VALUES ';
	for($i=0;$i<$int_count;$i++)
	{
		$str_tupel = (isset($arr_changes['owner']) ? (int)$arr_changes['owner'].',' : '0,').'
			'.(isset($arr_changes['value1']) ? (int)$arr_changes['value1'].',' : '0,').'
			'.(isset($arr_changes['value2']) ? ''.(int)$arr_changes['value2'].',' : '0,').'
			'.(isset($arr_changes['type']) ? (int)$arr_changes['type'].',' : '0,').'
			'.(isset($arr_changes['chestlock']) ? (bool)$arr_changes['chestlock'].',' : '0,').'
			'.(isset($arr_changes['gold']) ? (int)$arr_changes['gold'].',' : '0,').'
			'.(isset($arr_changes['gems']) ? (int)$arr_changes['gems'].',' : '0,').'
			'.(isset($arr_changes['description']) ? '"'.db_real_escape_string(stripslashes($arr_changes['description'])).'"' : '""');
		if($i==0)
		{
			$str_sql .= "\n".' ('.$str_tupel.') ';
		}
		else
		{
			$str_sql .= ",\n".' ('.$str_tupel.') ';
		}
	}

	// Send query to DB
	db_query($str_sql);
	if(db_errno(LINK)) {
		return (false);
	}

	return (true);

}

/**
 * Löscht einen oder mehrere Schlüssel aus der DB
 *
 * @param string SQL-WHERE Bedingungen
 * @param int Begrenzung der zu löschenden Schlüssel (0 für unbegrenzt, default 1)
 * @return int -1 bei Fehler, sonst Anzahl der gelöschten Schlüssel
 * @author talion
 */
function house_keys_del ($str_where, $int_limit = 1) {

	// Build up the DELETE-Query
	$str_sql = 'DELETE FROM keylist WHERE ';

	$str_sql .= $str_where;

	if($int_limit > 0) {
		$str_sql .= ' LIMIT '.$int_limit;
	}

	// Send query to DB
	db_query($str_sql);

	if(db_errno(LINK)) {
		return (-1);
	}

	return (db_affected_rows(LINK));

}

/**
 * Verändert einen oder mehrere Schlüssel in der DB
 *
 * @param string SQL-WHERE Bedingungen
 * @param array Assoz. Array der zu ändernden Spalten
 * @param int Begrenzung der zu ändernden Schlüssel
 * @return int -1 bei Fehler, sonst Anzahl der geänderten Schlüssel
 * @author talion
 */
function house_keys_set ($str_where, $arr_changes, $int_limit = 1) {

	$str_sql = 'UPDATE keylist SET
					'.(isset($arr_changes['houseid']) ? 'houseid='.(int)$arr_changes['houseid'].',' : '').'
					'.(isset($arr_changes['owner']) ? 'owner='.(int)$arr_changes['owner'].',' : '').'
					'.(isset($arr_changes['value1']) ? 'value1='.(int)$arr_changes['value1'].',' : '').'
					'.(isset($arr_changes['value2']) ? 'value2='.(int)$arr_changes['value2'].',' : '').'
					'.(isset($arr_changes['type']) ? 'type='.(int)$arr_changes['type'].',' : '').'
					'.(isset($arr_changes['chestlock']) ? 'chestlock='.(int)$arr_changes['chestlock'].',' : '').'
					'.(isset($arr_changes['gold']) ? 'gold='.(int)$arr_changes['gold'].',' : '').'
					'.(isset($arr_changes['gems']) ? 'gems='.(int)$arr_changes['gems'].',' : '').'
					'.(isset($arr_changes['description']) ? 'description="'.db_real_escape_string(stripslashes($arr_changes['description'])).'",' : '').'
					id=id
				WHERE ';

	$str_sql .= $str_where;

	$str_sql .= ' LIMIT '.$int_limit;

	// Send query to DB
	db_query($str_sql);

	if(db_errno(LINK)) {
		return (-1);
	}

	return (db_affected_rows(LINK));

}

/**
 * Setzt ein Gemach zurück bzw. löscht es
 *
 * @param mixed $mixed_room Entweder Array mit Raumdaten oder ID des Raums
 * @param mixed $mixed_house Entweder Array mit Hausdaten oder ID des Hauses
 * @param bool $bool_del Raum löschen? (standard false = nur Zurücksetzen)
 */
function house_take_room ($mixed_room, $mixed_house, $bool_del=false) {

	if(!isset($mixed_room['id'])) {
		$res = db_query('SELECT * FROM house_extensions WHERE id='.(int)$mixed_room);
		if(!db_num_rows($res)) {
			return;
		}
		$mixed_room = db_fetch_assoc($res);
		db_free_result($res);
	}

	if(!isset($mixed_house['houseid'])) {
		$res = db_query('SELECT * FROM houses WHERE houseid='.(int)$mixed_house);
		if(!db_num_rows($res)) {
			return;
		}
		$mixed_house = db_fetch_assoc($res);
		db_free_result($res);
	}

	// Einladungen in dieses Gemach entfernen
	// neu: Mit Systemmail
	$sql = 'SELECT id,owner FROM keylist WHERE value2='.$mixed_room['id'].' AND type='.HOUSES_KEY_PRIVATE;
	$res = db_query($sql);

	while($arr_k = db_fetch_assoc($res)) {
		systemmail($arr_k['owner'],'`tGemach geräumt!','`tEin Gemach im Haus '.$mixed_house['housename'].'`t, zu dem du Zugang hattest, wurde geräumt.
							 Damit ist auch dein Schlüssel dorthin nutzlos geworden.');
	}
	db_free_result($res);
	// so war's vorher (Wird auch hier noch verwendet, um die Schlüssel schnell zu entfernen):
	house_keys_del('type='.HOUSES_KEY_PRIVATE.' AND value2='.$mixed_room['id'],0);

	// Möbel für Privatgemächer zurücksetzen (Vorsicht: Keine falschen Owner [Gilden] mitnehmen!)
	item_set(' owner < 1234567 AND deposit1='.$mixed_house['houseid'].' AND deposit2='.$mixed_room['id'], array('deposit1'=>0,'deposit2'=>0) );

	// Cache zurücksetzen
	if(!Cache::delete(Cache::CACHE_TYPE_HDD,'houserooms'.$mixed_house['houseid']))
	{
		admin_output('Cachereset (houserooms'.$mixed_house['houseid'].') funzt nicht',true);
	}

	// Raum löschen?
	if($bool_del) {
		// Modul runnen
		house_extension_run('rip_auto',$mixed_room,$mixed_house);

		db_query('DELETE FROM house_extensions WHERE id='.$mixed_room['id']);
	}
	// .. oder nur zurücksetzen?
	else {
		db_query('UPDATE house_extensions SET owner=0,content="",name="" WHERE id='.$mixed_room['id']);
	}
}

/**
 * Verkauft ein Haus; kümmert sich dabei um Abnehmen der Schlüssel + Gemächer
 * Löscht alle Hausanbauten (auch Gemächer)!
 * Setzt auch house-Spalte in accounts-table zurück
 *
 * @param mixed $mixed_house Entweder Array mit Hausdaten oder ID des Hauses
 * @param int $int_gold Goldpreis, zu dem das Haus verkauft werden soll (standard 0 = Maklerpreis)
 * @param int $int_gems Gempreis, zu dem das Haus verkauft werden soll (standard 0 = Maklerpreis)
 * @param int $int_owner Verkäufer-AcctID (standard 0 = Makler)
 */
function house_sell ($mixed_house,$int_gold=0,$int_gems=0,$int_owner=0) {

	global $session;

	if(!isset($mixed_house['houseid'])) {
		$res = db_query('SELECT * FROM houses WHERE houseid='.(int)$mixed_house);
		if(!db_num_rows($res)) {
			return;
		}
		$mixed_house = db_fetch_assoc($res);
		db_free_result($res);
	}

	// Account ändern
	if($mixed_house['owner'] == $session['user']['acctid']) {
		$session['user']['house'] = 0;
	}
	else {
		user_update(
			array
			(
				'house'=>0
			),
			$mixed_house['houseid']
		);
	}

	// Gold und Edelsteine an Bewohner verteilen und Schlüssel einziehen
	$sql = 'SELECT k.owner,k.id,he.id AS rid
			FROM keylist k
			LEFT JOIN house_extensions he ON (he.owner = k.owner AND he.houseid = k.value1 AND he.loc IS NOT null)
			WHERE k.value1='.$mixed_house['houseid'].' AND k.type='.HOUSES_KEY_DEFAULT.' AND k.owner > 0 AND k.owner != '.$mixed_house['owner'];
	$result = db_query($sql);

	$bool_trsshare = getsetting('housetrsshare',1);
	if($bool_trsshare) {
		$amt=db_num_rows($result);
		$goldgive=round($mixed_house['gold']/($amt+1));
		$gemsgive=round($mixed_house['gems']/($amt+1));
	}

	for ($i=0; $i<$amt; $i++)
	{
		$item = db_fetch_assoc($result);
		if($bool_trsshare && $item['rid']) {

			user_update(
				array
				(
					'goldinbank'=>array('sql'=>true,'value'=>'goldinbank+'.$goldgive),
					'gems'=>array('sql'=>true,'value'=>'gems+'.$gemsgive)
				),
				$item['owner']
			);

		}

		if($item['rid']) {
			house_take_room(array('id'=>$item['rid']),$mixed_house,false);
		}

		systemmail($item['owner'],'`@Rauswurf!`0',
		'2Das Haus `b'.$mixed_house['housename'].'`b`2, '
		.($item['rid'] ? 'in dem du als Untermieter gewohnt hast' : 'zu dem du Zugang hattest')
		.', wurde verkauft.`n'
		.($bool_trsshare ? 'Du bekommst `^'.$goldgive.'`2 Gold auf die Bank und `#'.$gemsgive.'`2 Edelsteine aus dem gemeinsamen Schatz ausbezahlt!' : '')
		);
	}

	// Alle Schlüssel auf verloren setzen (die erhalten bleiben sollen)
	// Anzahl an Schlüsseln, die maximal erhalten bleiben soll (Default-Anzahl = 9)
	// Zusätzliche verlorene Schlüssel sind Bonus beim Hauskauf - Glück gehabt
	$int_maxkeys = house_get_max_keys(0,false); // evtl. eitgene settings
	$sql = 'UPDATE keylist SET owner=0,chestlock=0,gold=0,gems=0 WHERE value1='.$mixed_house['houseid'].' AND type='.HOUSES_KEY_DEFAULT.'
			LIMIT '.$int_maxkeys;
	db_query($sql);

	// Alle noch im Haus schlafenden Bewohner rausschmeißen

	user_update(
		array
		(
			'restatlocation'=>0,
			'location'=>0,
			'where'=>'location='.USER_LOC_HOUSE.' AND restatlocation='.$mixed_house['houseid']
		)
	);

	// Alle Schlüssel löschen, die zu Privatgemächern gehören ODER nicht verloren sind
	house_keys_del('value1='.$mixed_house['houseid'].' AND (type='.HOUSES_KEY_PRIVATE.' OR owner > 0)',0);

	// Räume + Anbauten löschen
	$sql = 'DELETE FROM house_extensions WHERE houseid='.$mixed_house['houseid'];
	db_query($sql);
	// Anbauten-Cache zurücksetzen
	if(!Cache::delete(Cache::CACHE_TYPE_HDD,'houserooms'.$mixed_house['houseid']))
	{
		admin_output('Cachereset (houserooms'.$mixed_house['houseid'].') funzt nicht',true);
	}

	// Alle Möbel auslagern
	item_set('deposit1='.$mixed_house['houseid'].' AND owner < 1234567',array('deposit1'=>0,'deposit2'=>0));

	// Haus zurücksetzen (Schlüsselsatz korrigieren)
	house_check($mixed_house,0);

	// Hausavatar löschen
	if($mixed_house['owner']) {
		CPicture::clear_old($mixed_house['owner'],'h');
	}

	// Haus updaten:
	// Gold / Gems auf angeg. Werte,
	// build_state auf 'im verkauf',
	// extension / Ausbau im Bau entfernen,
	// Eigentümer auf angeg. AcctID,
	// Beschreibung löschen,
	// Status auf 0 (=Wohnhaus),
	// RP-Zeichenzahl löschen,
	// Flags zurücksetzen.
	// Hausname, tricks, dmg, dmg_info bleibt
	$sql = 'UPDATE houses
			SET 	gold='.$int_gold.',gems='.$int_gems.',
					build_state='.HOUSES_BUILD_STATE_SELL.',extension=0,owner='.$int_owner.',description="",status=0,c_max_length=0,
					pvpflag_houses="0000-00-00 00:00:00",lastchange="0000-00-00 00:00:00"
			WHERE houseid='.$mixed_house['houseid'];
	db_query($sql);

}

/**
 * Verwandelt ein Haus in ein leeres Grundstück, benutzt dazu die Funktion house_sell()
 *
 * @param mixed $mixed_house Entweder Array mit Hausdaten oder ID des Hauses
 * @uses house_sell()
 */
function house_deconstruct ($mixed_house) {

	if(!isset($mixed_house['houseid'])) {
		$res = db_query('SELECT * FROM houses WHERE houseid='.(int)$mixed_house);
		if(!db_num_rows($res)) {
			return;
		}
		$mixed_house = db_fetch_assoc($res);
		db_free_result($res);
	}

	// Dadurch wird das Haus größtenteils zurückgesetzt. Den Rest erledigen wir mit dem Query unten
	house_sell($mixed_house,0,0,0);

	// Lösche noch dazu alle Schlüssel
	house_keys_del('value1='.$mixed_house['houseid'],0);

	// Erweiterte Aktionen
	$sql = 'UPDATE houses SET build_state='.HOUSES_BUILD_STATE_EMPTY.',housename="Leeres Grundstück",dmg=0,dmg_info="",trick=""
			WHERE houseid='.$mixed_house['houseid'];
	db_query($sql);

}

/**
 * Berechnet auf Basis der in den Settings gespeicherten Werte und des Schadens des Hauses den Preis
 *
 * @param array Komplette Hausdaten
 * @return array 'gold' => Goldpreis, 'gems' => Gempreis
 */
function house_get_price ($arr_house) {

	if(sizeof($arr_house) == 0 || !is_array($arr_house)) {
		return false;
	}

	// Baukosten als Preis
	$basegold = getsetting('housebuildcostgold',30000);
	$basegems = getsetting('housebuildcostgems',50);

	// Durch Schäden am Haus sinkt der Preis
	if($arr_house['dmg'] >= 100) {
		$int_dmglvl = floor($arr_house['dmg'] * 0.01);
		// Mit jedem dmglvl: 10% weniger
		$basegold *= 1 - ($int_dmglvl * 0.1);
		$basegems *= 1 - ($int_dmglvl * 0.1);
	}

	return(array('gold'=>$basegold,'gems'=>$basegems));

}


/**
 * Gibt den Typ einer Haus Erweiterung zurück
 *
 * @param int $int_ext_id House extension id
 * @return string
 */
function house_get_extension_type($int_ext_id)
{
	$db_result = db_query('SELECT type FROM house_extensions WHERE id='.(int)$int_ext_id);
	$arr_result = db_fetch_array($db_result);
	return $arr_result[0];
}

/**
 * Kontrolliert Min-/Max-Werte für Schlüssel und Gemächer für bestimmten geg. Haus-Status
 * Achtung: Ändert den Ausbau-Status selbst nicht! Dies muss davor / danach geschehen!
 *
 * @param mixed $mixed_house Entweder Array mit Hausdaten oder ID des Hauses
 * @param int $int_new_status Neuer Haus-Status, auf den kontrolliert werden soll
 * @param bool $bool_msg Sollen aufklärende Systemmails an die betroffenen Bewohner versendet werden? (standard false)
 */
function house_check ($mixed_house,$int_new_status, $bool_msg=false) {

	global $g_arr_house_builds;

	if(!isset($mixed_house['houseid'])) {
		$res = db_query('SELECT * FROM houses WHERE houseid='.(int)$mixed_house);
		if(!db_num_rows($res)) {
			return;
		}
		$mixed_house = db_fetch_assoc($res);
		db_free_result($res);
	}

	// Anzahl an vorhandenen Schlüsseln ermitteln
	// Dabei verlorene Schlüssel und solche des Haushern zuerst
	$sql = 'SELECT k.owner,k.id,he.id AS rid
			FROM keylist k
			LEFT JOIN house_extensions he ON (he.owner = k.owner AND he.houseid = k.value1 AND he.loc IS NOT null AND k.owner!='.$mixed_house['owner'].')
			WHERE k.value1='.$mixed_house['houseid'].' AND k.type='.HOUSES_KEY_DEFAULT.'
			ORDER BY (k.owner='.$mixed_house['owner'].' OR k.owner=0) DESC';
	$res = db_query($sql);

	$int_keys = db_num_rows($res);
	$str_debug .= '| keys_num: '.$int_keys;

	// Anzahl an erlaubten Schlüsseln ermitteln
	$int_max_keys = house_get_max_keys($int_new_status);
	$str_debug .='| max_keys: '.$int_max_keys;

	// Anzahl an min. Schlüsseln ermitteln
	$int_min_keys = house_get_max_keys($int_new_status,false);
	$str_debug .='| min_keys: '.$int_min_keys;

	// erlaubt schlägt nach unten immer min.
	$int_min_keys = min($int_max_keys,$int_min_keys);
	$str_debug .='| min_keys: '.$int_min_keys;

	// Schlüsselveränderungen durch vorhergehende Ausbauten
	$int_added_keys = 0;
	$arr_bld_lst = house_get_build_chain($mixed_house['status']);

	$i = 0;
	while($arr_b = $g_arr_house_builds[$arr_bld_lst[$i]]) {
		if(isset($arr_b['keys_add'])) {
			$int_added_keys += $arr_b['keys_add'];
		}
		$i++;
	}

	// Schlüsselveränderungen durch neuen Ausbau
	$arr_bld_lst = house_get_build_chain($int_new_status);

	$i = 0;
	while($arr_b = $g_arr_house_builds[$arr_bld_lst[$i]]) {
		if(isset($arr_b['keys_add'])) {
			// subtrahieren, da diese Schlüssel später dazukommen (s.u.)
			$int_added_keys -= $arr_b['keys_add'];
		}
		$i++;
	}

	$str_debug .='| added_keys: '.$int_added_keys;

	// Schlüssel ermitteln, die letztendlich abgezogen / hinzuaddiert werden müssen
	$int_keys = max($int_keys - $int_max_keys,0) +
	min($int_keys - $int_min_keys,0) +
	$int_added_keys;

	$str_debug .='| int_keys: '.$int_keys;

	// Schlüssel dazu!
	if($int_keys < 0) {
		house_keys_add(array('type'=>HOUSES_KEY_DEFAULT,'value1'=>$mixed_house['houseid'],'owner'=>$mixed_house['owner']),abs($int_keys));
	}
	// Schlüssel weg!
	elseif ($int_keys > 0) {
		for($i=0;$i<$int_keys;$i++) {
			if(!$arr_key = db_fetch_assoc($res)) {
				break;
			}
			$str_debug .='| '.$i.':'.$arr_key['id'];

			if(!empty($arr_key['owner']) && $arr_key['owner'] != $mixed_house['owner']) {

				// Wenn Gemach
				if($arr_key['rid']) {
					house_take_room(array('id'=>$arr_key['rid']),$mixed_house,false);
				}

				if($bool_msg) {
					systemmail($arr_key['owner'],'`@Rauswurf!`0',
					'2Das Haus `b'.$mixed_house['housename'].'`b`2, '
					.($arr_key['rid'] ? 'in dem du als Untermieter gewohnt hast' : 'zu dem du Zugang hattest')
					.', bietet nach einem Umbau nicht mehr genug Platz für dich.`n'
					);
				}

			}

			house_keys_del('id='.$arr_key['id']);
		}
	}
	db_free_result($res);
	// END Schlüssel

	// Gemächer
	// Anzahl an vorhandenen Gemächern ermitteln
	// Dabei leere und solche des Haushern zuerst
	$sql = 'SELECT * FROM house_extensions
			WHERE
				houseid='.$mixed_house['houseid'].' AND loc IS NOT null
				ORDER BY loc DESC, (owner='.$mixed_house['owner'].' OR owner=0) DESC';
	$res = db_query($sql);

	$int_rooms = db_num_rows($res);
	$str_debug .='| int_rooms: '.$int_rooms;

	// Anzahl an erlaubten Gemächern ermitteln
	$int_max_rooms = house_get_max_rooms($int_new_status);
	$str_debug .='| int_max_rooms: '.$int_max_rooms;

	// Gemächer ermitteln, die letztendlich abgezogen / hinzuaddiert werden müssen
	$int_rooms = max($int_rooms - $int_max_rooms,0);
	$str_debug .='| +- int_rooms: '.$int_rooms;

	// Gemächer dazu!
	if($int_rooms < 0) {

	}
	// Gemächer weg!
	elseif ($int_rooms > 0) {
		for($i=0;$i<$int_rooms;$i++) {
			if(!$arr_room = db_fetch_assoc($res)) {
				break;
			}

			house_take_room($arr_room,$mixed_house,true);

			if($arr_room['owner'] > 0 && $arr_room['owner'] != $mixed_house['owner'] && $bool_msg) {
				systemmail($arr_room['owner'],'`@Rauswurf!`0',
				'2Das Haus `b'.$mixed_house['housename'].'`b`2, in welchem du ein Privatgemach bewohnt hast,
							bietet nach einem Umbau nicht mehr genug Platz für dieses. Deshalb musstest du das Gemach räumen.`n'
							);
			}
		}
	}
	db_free_result($res);
	// END Gemächer

	debuglog('house_check ID '.$mixed_house['houseid'].': '.$str_debug);

}


/**
 * Enter description here...
 *
 * @param unknown_type $state
 * @param unknown_type $private
 * @return unknown
 * @todo Dynamisieren!
 */
function get_max_furniture ($state=0,$private=false) {

	$var = ($private ? HOUSES_PRIVATE_FURNITURE : HOUSES_FURNITURE);

	if($state >= 10 && $state < 20) {
		$var += 5;
	}	// Villa etc.

	if($state > 0) {
		$var += 5;
	}	// 1. Ausbaustufe


	return($var);

}

/**
 * Liefert Ausdruck für Haustyp zurück
 *
 * @param int $state Ausbaustatus des Hauses
 * @param int $build_state Baustatus des Hauses
 * @param bool $check Artikel vorstellen ja / nein
 * @param bool $col Farbigen Namen verwenden ja / nein (Standard ja)
 * @return string Fertiger Ausdruck
 */
function get_house_state ($state,$build_state,$check,$col=true)
{

	global $g_arr_house_builds;

	if($state == 0) {
		$str_ret = ($check ? 'ein ' : '').($col ? '`!' : '').'Wohnhaus';
	}
	else {
		if(isset($g_arr_house_builds[$state])) {
			$arr_build = $g_arr_house_builds[$state];
			$str_ret = ($check ? ($arr_build['sex'] ? 'eine ' : 'ein ') : '').
			($col ? $arr_build['colname'] : $arr_build['name']);
		}
		else {
			$str_ret = ($col ? '`$' : '').'UNBEKANNTER AUSBAU!';
		}
	}

	switch($build_state) {
		case 0 :
			break;
		case HOUSES_BUILD_STATE_IP :
		case HOUSES_BUILD_STATE_EXT :
			$str_ret .= ' '.($col ? '`6' : '').'im Ausbau';
			break;
		case HOUSES_BUILD_STATE_INIT :
			// Hier nur diese Info ausgeben
			$str_ret = ($check ? 'eine ':'').($col ? '`6' : '').'Baustelle';
			break;
		case HOUSES_BUILD_STATE_SELL :
			$str_ret .= ' '.($col ? '`^' : '').'zum Verkauf';
			break;
		case HOUSES_BUILD_STATE_ABANDONED :
			$str_ret = ' '.($col ? '`4' : '').'Verlassen!';
			break;
		case HOUSES_BUILD_STATE_EMPTY :
			// Hier nur diese Info ausgeben
			$str_ret = ($check ? 'ein ':'').''.($col ? '`@' : '').'unbebautes Grundstück';
			break;
		case HOUSES_BUILD_STATE_RUIN :
			// Hier nur diese Info ausgeben
			$str_ret = ($check ? 'eine ':'').($col ? '`4' : '').'Bauruine';
			break;
		default :
			$str_ret .= ' '.($col ? '`$' : '').'(UNBEKANNTER build_state!)';
			break;
	}

	$str_ret .= ($col ? '`0' : '');
	return($str_ret);
}

/**
 * Gibt Gegner für Kerker-Arenakampf zurück
 *
 * @param int $number Nummer des Gegners
 * @return string Gegnername
 */
function get_opponent($number) {
	switch($number) {
		case 1 :
			$opp='`^ein Dackel`0';
			break;
		case 2 :
			$opp='`^ein Waschbär`0';
			break;
		case 3 :
			$opp='`^ein Schwarm Bienen`0';
			break;
		case 4 :
			$opp='`^ein Noob`0';
			break;
		case 5 :
			$opp='`^eine Würgeschlange`0';
			break;
		case 6 :
			$opp='`^ein Rudel Frettchen`0';
			break;
		case 7 :
			$opp='`^ein Schlagersänger`0';
			break;
		case 8 :
			$opp='`^ein Schwarzbär`0';
			break;
		case 9 :
			$opp='`^eine Schwiegermutter`0';
			break;
		case 10 :
			$opp='`^Hunk, der Halboger`0';
			break;
	}
	return($opp);
}

/**
 * Überprüft ob im aktuellen Haus / Gemach ein Item eingelagert ist, welches einem bestimmten Template entspricht
 *
 * @param int $int_hid HausID
 * @param string $str_id ID einer Extension
 * @return 1 wenn solch eine Extension vorliegt, sonst false;
 */
function house_has_extension($int_hid, $str_id)
{
	$str_sql = 'SELECT IF(COUNT(*)>0,1,0) AS found FROM house_extensions WHERE houseid='.(int)$int_hid.' AND type="'.$str_id.'"';
	$arr_anz = db_fetch_assoc(db_query($str_sql));
	return ($arr_anz['found']>0);
}

/**
 * Überprüft ob im aktuellen Haus / Gemach ein Item eingelagert ist, welches einem bestimmten Template entspricht
 *
 * @param int $int_hid HausID
 * @param int $int_ext_id ID einer Extension (bzw. 0, wenn Hauptraum)
 * @param string $str_tpl_id Template ID des Items nach dessen Existenz gesucht werden soll
 * @param array Wenn nicht null wird das/die gefundene/n items in diesem Array gespeichert
 * @return true wenn solch ein Item vorliegt, sonst false;
 */
function house_has_item($int_hid, $int_ext_id, $str_tpl_id = '', $str_what = 'id',&$db_resultset = null)
{
	global $session;

	if(empty($str_tpl_id))
	{
		return false;
	}

	$str_tpl_id = addstripslashes($str_tpl_id);
	$int_ext_id = (int)$int_ext_id;
	$int_hid = (int)$int_hid;

	$properties = ' owner < 1234567 AND deposit'.($int_ext_id > 0 ? '_private' : '').'>0 AND deposit1='.$int_hid.' AND deposit2='.$int_ext_id;
	$str_extra = 'AND tpl_id="'.$str_tpl_id.'" LIMIT 1';

	//Item laden
	$res = item_list_get($properties , $str_extra , true , $str_what );

	if($db_resultset !== null)
	{
		$db_resultset = $res;
	}

	return (db_num_rows($res)>0);
}

/**
 * Gibt alle im aktuellen Haus / Gemach eingelagerten Items zurück
 *
 * @param int $int_hid HausID
 * @param int $int_ext_id ID einer Extension (bzw. 0, wenn Hauptraum)
 * @param string $str_extra Extra SQL Anweisung die die zurückgegebenen items einschränkt, ordnet oder limitiert
 * @param bool $bool_return_array legt fest ob ein DB resultset oder ein Array mit den gesammelten Items zurückgegeben werden soll
 * @return mixed Ein DB resultset oder ein Array mit den gesammelten Items
 */
function house_get_items($int_hid, $int_ext_id, $str_extra ='', $bool_return_array = false)
{
	global $session;

	$int_ext_id = (int)$int_ext_id;
	$int_hid = (int)$int_hid;

	$properties = ' owner < 1234567 AND deposit'.($int_ext_id > 0 ? '_private' : '').'>0 AND deposit1='.$int_hid.' AND deposit2='.$int_ext_id.' ';
	$res = item_list_get($properties , $str_extra , true , ' tpl_id,name,description,id,furniture_hook,furniture_private_hook,gold,gems ' );

	$arr_items = array();

	if($bool_return_array == true)
	{
		while($arr_item = db_fetch_assoc($res))
		{
			$arr_items[]=$arr_item;
		}
		return $arr_items;
	}
	else
	{
		return $res;
	}
}

/**
 * Ruft Haus-Extensionmodul auf
 *
 * @param string $str_case Modul-Case (für Modul-Switch)
 * @param mixed $mixed_ext entweder ID einer Extension (dann wird diese abgerufen) oder die vollst. Daten dieser
 * @param array $arr_house vollständige Daten des aktuellen Hauses; ansonsten wird dieses abgerufen (optional, Standard false)
 */
function house_extension_run ($str_case, $mixed_ext, $arr_house=array()) {

	global $g_arr_house_extensions;

	// Falls nicht gegeben, Extension ermitteln
	if(is_int($mixed_ext)) {
		$mixed_ext = db_fetch_assoc(db_query('SELECT * FROM house_extensions WHERE id='.$mixed_ext));
	}

	if(empty($mixed_ext)) {
		echo('Extension nicht gegeben!');
		return;
	}

	// Extension-Typ ermitteln
	$str_type = $mixed_ext['type'];
	if(!isset($g_arr_house_extensions[$str_type])) {
		echo('ERROR: house_extension_run - '.$str_type.' nicht vorhanden!');
		return;
	}

	$arr_ext_type = $g_arr_house_extensions[$str_type];

	// Pfad bestimmen
	$str_path = HOUSES_EXT_PATH;

	// Wenn ein inc-File für alle gegeben:
	if(!is_array($arr_ext_type['inc'])) {
		$str_path .= $arr_ext_type['inc'];
		// Funktionsname = Dateiname bis zum ersten Punkt
		$str_func_name = 'house_ext_'.mb_substr($arr_ext_type['inc'],0,mb_strpos($arr_ext_type['inc'],'.'));
	}
	// Wenn versch. inc-Files für versch. Fälle:
	else {
		// Aus Array wählen
		$str_path .= $arr_ext_type['inc'][$str_case];
		// Funktionsname = Dateiname bis zum ersten Punkt
		$str_func_name = 'house_ext_'.mb_substr($arr_ext_type['inc'][$str_case],0,mb_strpos($arr_ext_type['inc'][$str_case],'.'));
	}

	if(!is_file($str_path)) {
		echo('ERROR: house_extension_run - '.$str_path.' nicht gefunden!');
		//return;
	}

	if(!function_exists($str_func_name)) {
		require_once($str_path);
	}

	if(!function_exists($str_func_name)) {
		echo('ERROR: house_extension_run - '.$str_func_name.' nicht aufrufbar!');
		return;
	}

	// Hausinfos abrufen
	if(!isset($arr_house['houseid'])) {

		$arr_house = db_fetch_assoc(db_query('SELECT * FROM houses WHERE houseid='.$mixed_ext['houseid']));
	}

	if(empty($arr_house)) {
		echo('Haus nicht gegeben!');
		return;
	}

	$str_func_name($str_case, $mixed_ext, $arr_house);
	return;

}

/**
 * Ruft Haus-Ausbaumodul auf
 *
 * @param string $str_case Modul-Case (für Modul-Switch)
 * @param int $int_build ID eines Ausbaus (-> $g_arr_house_builds)
 * @param mixed $mixed_house vollständige Daten des aktuellen Hauses; alternativ ID, um dieses abzurufen (optional, Standard false)
 */
function house_build_run ($str_case, $int_build, $mixed_house) {

	global $g_arr_house_builds;

	// Ausbau ermitteln
	$arr_build = $g_arr_house_builds[$int_build];

	if(empty($arr_build)) {
		echo('ERROR: house_build_run - Ausbau nicht gegeben!');
		return;
	}

	// Pfad bestimmen
	$str_path = HOUSES_BUILDS_PATH;

	// Wenn ein inc-File für alle gegeben:
	if(!is_array($arr_build['inc'])) {
		$str_path .= $arr_build['inc'];
		// Funktionsname = Dateiname bis zum ersten Punkt
		$str_func_name = 'house_build_'.mb_substr($arr_build['inc'],0,mb_strpos($arr_build['inc'],'.'));
	}
	// Wenn versch. inc-Files für versch. Fälle:
	else {
		// Aus Array wählen
		$str_path .= $arr_build['inc'][$str_case];
		// Funktionsname = Dateiname bis zum ersten Punkt
		$str_func_name = 'house_build_'.mb_substr($arr_build['inc'][$str_case],0,mb_strpos($arr_build['inc'][$str_case],'.'));
	}

	if(!is_file($str_path)) {
		echo('ERROR: house_build_run - '.$str_path.' nicht gefunden!');
		return;
	}

	if(!function_exists($str_func_name)) {
		require_once($str_path);
	}

	if(!function_exists($str_func_name)) {
		echo('ERROR: house_extension_run - '.$str_func_name.' nicht aufrufbar!');
		return;
	}

	// Hausinfos abrufen
	if(is_int($mixed_house)) {
		$mixed_house = db_fetch_assoc(db_query('SELECT * FROM houses WHERE houseid='.$mixed_house));
	}

	if(empty($mixed_house)) {
		echo('Haus nicht gegeben!');
		return;
	}

	$str_func_name($str_case, $arr_build, $mixed_house);
	return;

}

/**
 * Sucht aus der Extensionliste die zur gegebenen JobID passende Extension
 *
 * @param int $int_job JobID
 * @return mixed String mit ExtensionID, sonst false
 */
function house_ext_from_job ($int_job) {

	global $g_arr_house_extensions;

	foreach ($g_arr_house_extensions as $arr_ext) {
		if(isset($arr_ext['special_job'])) {
			if($arr_ext['special_job'] == $int_job) {
				return($arr_ext['id']);
			}
		}
	}

	return (false);

}

/**
 * Funktion ermittelt Vorgänger-Ausbauten (Entwicklungskette) eines Ausbaus
 *
 * @param int $int_status Zu untersuchender Ausbaustatus
 * @return array Liste mit Ausbau-IDs (erster Ausbau = letztes Element im Array)
 */
function house_get_build_chain ($int_status) {

	global $g_arr_house_builds;

	$arr_ret = array();

	while(isset($g_arr_house_builds[$int_status])) {

		// In Liste speichern
		$arr_ret[] = $int_status;

		// Alle Ausbauten durchlaufen und nach Vorgänger-Ausbau durchsuchen
		foreach ($g_arr_house_builds as $arr_bld) {
			if(is_array($arr_bld['next']) && sizeof($arr_bld['next'])) {
				if(in_array($int_status,$arr_bld['next'])) {
					// Das ist Vorgänger-Ausbau
					$int_status = $arr_bld['id'];
					break;
				}
			}
		}

		// Wenn sich nichts geändert hat, gibt es kein Ergebnis: Abbruch
		if($int_status == $arr_ret[sizeof($arr_ret)-1]) {
			return($arr_ret);
		}

	}

	return($arr_ret);

}

/**
 * Levelabh. Kostenfunktion für Anbauten
 *
 * @param int $int_gold Basispreis Gold
 * @param int $int_gems Basispreis Gems
 * @param int $int_lvl aktueller Level
 * @return array Array ( 'gold' => Goldpreis, 'gems' => Gemspreis )
 */
function house_calc_ext_costs ($int_gold,$int_gems,$int_lvl) {

	$int_gold = round($int_gold * pow(1.1,$int_lvl));
	$int_gems = round($int_gems * pow(1.1,$int_lvl));

	return(array('gold'=>$int_gold,'gems'=>$int_gems));

}

/**
 * Gibt die Bezeichnung für das Stockwerk (gespeichert in 'loc') eines Gemachs zurück
 *
 * @param int $int_floor Stockwerknummer
 * @param bool $bool_col Mit Farbe oder ohne, Standard false
 * @return string Bezeichnung
 */
function house_get_floor ($int_floor,$bool_col=false) {
	switch ($int_floor) {
		case HOUSES_ROOM_BASEMENT: 				return(($bool_col ? '`Y' : '').'Keller'.($bool_col ? '`0' : ''));
		case HOUSES_ROOM_GROUND: 				return(($bool_col ? '`t' : '').'Erdgeschoß'.($bool_col ? '`0' : ''));
		case HOUSES_ROOM_1ST:	 				return(($bool_col ? '`&' : '').'1. Stock'.($bool_col ? '`0' : ''));
		case HOUSES_ROOM_2ND:	 				return(($bool_col ? '`v' : '').'2. Stock'.($bool_col ? '`0' : ''));
		case HOUSES_ROOM_ROOF:				 	return(($bool_col ? '`F' : '').'Dachgeschoß'.($bool_col ? '`0' : ''));
		case HOUSES_ROOM_TOWER:					return(($bool_col ? '`w' : '').'Turmgeschoß'.($bool_col ? '`0' : ''));
		default:								return(($bool_col ? '`t' : '').'Erdgeschoß'.($bool_col ? '`0' : ''));
	}
}

/**
 * Gibt die max. / default- Anzahl an Gemächern für geg. Ausbaustatus zurück
 *
 * @param int $int_status Ausbaustatus
 * @param bool $bool_mode Default-Anzahl (false) oder max. Anzahl (true, Standard)?
 * @return int Max. Anzahl
 */
function house_get_max_rooms ($int_status,$bool_mode=true) {
	global $g_arr_house_builds;

	// Max. Anzahl
	if($bool_mode) {
		if(0 == $int_status) {
			return(getsetting('housemaxrooms',10));
		}
		if(isset($g_arr_house_builds[$int_status]['max_rooms'])) {
			return($g_arr_house_builds[$int_status]['max_rooms']);
		}
		// Sonst: Standardwert für Ausbau
		return(getsetting('housemaxroomsplus',20));
	}
	else {	// Default-Anzahl
		if(0 == $int_status) {
			return(getsetting('housefreerooms',2));
		}
		// Sonst: Standardwert für Ausbau (mit Max.wert begrenzen)
		return( min(getsetting('housefreeroomsplus',4),house_get_max_rooms($int_status,true)) );
	}

}

/**
 * Gibt die max.- / default-Anzahl an Schlüsseln für geg. AUsbaustatus zurück
 *
 * @param int $int_status Ausbaustatus
 * @param bool $bool_mode Default-Anzahl (false) oder max. Anzahl (true, Standard)?
 * @return int Max. Anzahl
 */
function house_get_max_keys ($int_status,$bool_mode=true) {
	global $g_arr_house_builds;

	// Max. Anzahl
	if($bool_mode) {
		if(0 == $int_status) {
			return(getsetting('housemaxkeys',100));
		}
		if(isset($g_arr_house_builds[$int_status]['max_keys'])) {
			return($g_arr_house_builds[$int_status]['max_keys']);
		}
		// Sonst: Standardwert für Ausbau
		return(getsetting('housemaxkeysplus',100));
	}
	else {	// Default-Anzahl
		if(0 == $int_status) {
			return(getsetting('housefreekeys',9));
		}
		// Sonst: Standardwert für Ausbau
		return(getsetting('housefreekeysplus',9));
	}



}

/**
 * Gibt eine Warnungsmeldung zurück, die angibt, wieviele Schlüssel und Räume durch den angestrebten AUsbau verlorengehen würden.
 *
 * @param int $int_hid HausID
 * @param int $int_target_status Zielstatus (ID des angestrebten Ausbaus, 0 für unausgebaut)
 * @return string Warnungsmeldung
 */
function house_get_rip_warning ($int_hid,$int_target_status) {

	$int_hid = (int)$int_hid;

	if($int_hid == 0) {
		return false;
	}

	$str_ret = '';

	// Räume
	$arr_tmp = db_fetch_assoc(db_query('SELECT COUNT(*) AS c FROM house_extensions WHERE loc IS NOT null AND level > 0 AND houseid='.$int_hid));
	$int_rooms = $arr_tmp['c'];

	// Schlüssel
	$arr_tmp = db_fetch_assoc(db_query('SELECT COUNT(*) AS c FROM keylist WHERE value1='.$int_hid));
	$int_keys = $arr_tmp['c'];

	unset($arr_tmp);

	$int_max_rooms = house_get_max_rooms($int_target_status);
	$int_max_keys = house_get_max_keys($int_target_status);

	if($int_max_rooms < $int_rooms) {
		$str_ret .= '`n`$Im Augenblick befinden sich in diesem Haus '.$int_rooms.' Gemächer. Platz wäre jedoch nur für '.$int_max_rooms.' Räume! Das heißt, '.($int_rooms - $int_max_rooms).' Räume würden abgerissen!`n`0Um Überraschungen zu vermeiden solltest du überzählige Gemächer selbst entfernen.';
	}
	if($int_max_keys < $int_keys) {
		$str_ret .= '`n`$Im Augenblick befinden sich in diesem Haus '.$int_keys.' Schlüssel. Platz wäre jedoch nur für '.$int_max_keys.' Schlüssel! Das heißt, '.($int_keys - $int_max_keys).' Schlüssel würden entfernt!';
	}

	return($str_ret);

}


$g_arr_house_dmg_types = array	(
array('name'=>'Riss in der Wand','msg'=>'Ein unschöner Riss zieht sich vom Boden bis zur Decke durch die sonst so schöne Wand..','stop'=>'housefeat')
);


/**
 * Fügt dem Haus Schaden zu
 *
 * @param mixed $mixed_house Entweder Referenz auf Array mit Hausdaten oder ID des Hauses
 * @param int $int_add Höhe des Schadens, der hinzugefügt werden soll
 * @param int $int_chance Wahrscheinlichkeit für Schaden (Zw. 1 und 100; Optional. default 50%)
 * @return int Höhe des tatsächlich zugefügten Schadens
 */
function house_add_dmg (&$mixed_house,$int_add,$int_chance=50) {

	global $g_arr_house_dmg_types;

	// Temporär ausgeschaltet
	return 0;

    /** @noinspection PhpUnreachableStatementInspection */
    $int_add = (int)$int_add;
	if($int_add == 0) {
		return false;
	}

	$int_chance = (int)$int_chance;
	if($int_chance == 0) {
		return false;
	}

	if($int_chance >= e_rand(1,100)) {

		if(!isset($mixed_house['dmg'])) {
			$res = db_query('SELECT * FROM houses WHERE houseid='.(int)$mixed_house);
			if(!db_num_rows($res)) {
				return;
			}
			$mixed_house = db_fetch_assoc($res);
			db_free_result($res);
		}

		$int_old_dmg = $mixed_house['dmg'];
		$mixed_house['dmg'] += $int_add;

		// Schaden am Haus einrichten (Hunderterschwelle überschritten)?
		if(floor($mixed_house['dmg'] * 0.01) > floor($int_old_dmg * 0.01)) {

			$mixed_house['dmg_info'] = utf8_unserialize($mixed_house['dmg_info']);

			// Welchen Schaden wollen wir haben?
			$int_dmg_id = e_rand(0,sizeof($g_arr_house_dmg_types)-1);
			$arr_dmg = $g_arr_house_dmg_types[$int_dmg_id];

			// Existiert Schaden schon? Dann automatisch Hausschatz in Mitleidenschaft ziehen
			if(isset($mixed_house['dmg_info'][$int_dmg_id])) {
				$int_gems = ceil($mixed_house['gems'] * 0.1);
				$int_gems = min($int_gems,6);
				if($int_gems > 0) {
					$int_gems = e_rand(1,$int_gems);
					insertcommentary(1,'/msg `$Durch die immensen Schäden am Haus verschwinde'.($int_gems == 1 ? 't':'n').' '.$int_gems.' Edelstein'.($int_gems == 1 ? '':'e').' aus dem Hausschatz!','house-'.$mixed_house['houseid']);
					$mixed_house['gems'] -= $int_gems;
				}
			}
			// Sonst: Schaden anrichten
			else {
				$mixed_house['dmg_info'][$int_dmg_id] = true;
				// Msg
				if(isset($arr_dmg['msg'])) {
					insertcommentary(1,'/msg '.$arr_dmg['msg'],'house-'.$mixed_house['houseid']);
				}
				// Callback
				if(isset($arr_dmg['func']) && function_exists($arr_dmg['func'])) {
					$arr_dmg['func']($mixed_house);
				}
			}

			$mixed_house['dmg_info'] = utf8_serialize($mixed_house['dmg_info']);

		}

		$sql = 'UPDATE houses SET dmg = '.$mixed_house['dmg'].',dmg_info="'.db_real_escape_string($mixed_house['dmg_info']).'",gems='.$mixed_house['gems'].'
				WHERE houseid='.$mixed_house['houseid'];
		db_query($sql);

		return($int_add);

	}

	return(0);

}


/**
 * Gibt HTML-Code für Möbelansicht in Haus und Gemach zurück; erstellt Navi
 *
 * @param int $int_depo1 deposit1-Wert der Möbel
 * @param int $int_depo2 deposit2-Wert der Möbel; wenn > 0, wird von Privatgemach ausgegangen
 * @param bool $bool_owner Eigentümer des Gemachs / Hauses?
 * @param array $arr_naviconf 	Assoz. Array String -> Bool
 * 								Welche Art von Möbelnavi soll angezeigt werden? Liste von Hooknames (furniture, furniture_private, furniture_privateinvited)
 * @return string Code
 */
function house_show_furniture ($int_depo1,$int_depo2,$bool_owner=false,$arr_naviconf=array('furniture'),$bool_empty=true) {

	$properties = ' owner < 1234567 AND deposit'.($int_depo2 > 0 ? '_private' : '').'>0 AND deposit1='.$int_depo1.' AND deposit2='.$int_depo2;
	$extra = ' ORDER BY sort_order DESC, name DESC, id ASC';
	$res = item_list_get($properties , $extra , true , ' name,description,id,furniture_hook,furniture_private_hook,furniture_privateinvited_hook ' );
	$count = db_num_rows($res);
	$hooks = array();
	$furniturenav = array('{type:MIT_LABEL, label:"Einrichtung"}');
    $str_out = '';
	if($count) {
		$str_out = '<input type="button" id="furn_but" value="Mobiliar einblenden">'.JS::event('#furn_but','click','furn_toggle();').'`n';
		$js_out = 'var g_furn_vis = false;
					function furn_toggle () {
						var i = 1;
						var data = null;
						while(isSet(data = document.getElementById("fd"+i))) {
							data.style.display = (!g_furn_vis ? "inline" : "none");
							i++;
						}
						g_furn_vis = (!g_furn_vis ? true : false);
						document.getElementById("furn_but").value = (!g_furn_vis ? "Mobiliar einblenden" : "Mobiliar ausblenden");
					}';
		if($bool_owner) {
			$str_lnk = 'houses_httpreq.php?op=furniture_out';
			addpregnav('/'.utf8_preg_quote($str_lnk).'&id=[\d]+/');

            $js_out .=	jslib_httpreq_init().'
					function fo (id) {
						if(!confirm("Wirklich auslagern?")) {
							return false;
						}
						g_req.send("'.$str_lnk.'&id="+id,
									function (r) {
										LOTGD.parseCommand(LOTGD.getCommandFromRequest(r));
										document.getElementById("f"+id).style.visibility = "hidden";
										for(i=0;i<a_m.length;i++) {
											for(j=0;j<a_m[i].m_items;j++) {
												if(a_m[i].m_items[j].link == "furniture.php?id="+id) {
													a_m[i].m_items[j].setVisibility(false);
													return;
												}
											}
										}
									},
									function () {MessageBox.show("Es gibt gerade Probleme. Bitte schreibe eine Anfrage!");},
									null,
									null);
					}';
		}
		$str_out .= JS::encapsulate($js_out);
	}
	else {
		if($int_depo2 && $bool_empty) {
			$str_out = '`iDieses Gemach ist noch ohne Mobiliar!`i`n';
		}
		else if($bool_empty) {
			$str_out = '`iDas Haus ist noch ohne Mobiliar!`i`n';
		}
	}
	$str_hookname = 'furniture'.($int_depo2 > 0 ? '_private':'').'_hook';
	for($i=1; $i<=$count; $i++)
	{
		$item = db_fetch_assoc($res);
		$str_out.='`n`c<hr width=90% style="border:1px solid #484848;">`c<div id="f'.$item['id'].'">
					'.($bool_owner ? '`0[ <a href="javascript:void(0);" id="fo_'.$item['id'].'" title="Auslagern!">X</a> ]
					'.JS::event('#fo_'.$item['id'].'','click','fo('.$item['id'].');').'
					' : '').' `&'.$item['name'].'`0 <div id="fd'.$i.'" style="display:none;">(`0'.appoencode(closetags('`0`i'.$item['description'].'`i`0','`i`c`b')).'`0)</div></div>';
		
		if(sizeof($arr_naviconf)) {
			foreach($arr_naviconf as $str_hookname) {
				$str_hookname .= '_hook';
				if ($item[$str_hookname] != '' && !$hooks[$item['id']])
				{
					$hooks[$item['id']] = true;
					$str_lnk = 'furniture.php?item_id='.$item['id'];
					$furniturenav[$item['name']] = $str_lnk;
				}
			}
		}
	}
	//Möbelzähler für Raumeigentümer (ja, ich weiß das ist hässlich, aber ich krieg die max_furn nicht hier rein)
	if($bool_owner && $int_depo2 && $bool_empty)
	{
		$str_out.='`n('.$count;
	}

	db_free_result($res);

	if(sizeof($furniturenav) > 1) {
		addnav('Mobiliar');
		foreach ($furniturenav as $str_txt=>$str_lnk) {
			addnav($str_txt,$str_lnk);
		}
	}
	
	return($str_out);

}

/**
 * Gibt Überschrift für Wohnviertel zurück
 *
 * @param string $str_title Überschrift
 * @return string Formatierte Überschrift
 */
function house_get_title ($str_title) {

	return get_title($str_title);

}

/**
 * Gibt Navigation für Gemächer aus
 *
 * @param int $int_aid AccountID, dessen Gemachzugänge abgerufen werden sollen
 * @param int $int_hid HausID, dessen Gemächer abgerufen werden sollen
 * @param bool $bool_navs Navigation ausgeben? Falls false, wird nur ein assoziat. Array mit den Räumenavi-Javascripts
 * 							ausgegeben (HOUSES_ROOM_BASEMENT => Navis usw.). Standard: true
 * @param int $int_id_to_skip ID eines Raumes, dessen Navi nicht ausgegeben wird. Standard: 0
 * @param bool $bool_house_access Hat Spieler allgemein Zugang zu Haus oder nur zu Gemach? Standard: true
 */
function house_set_room_navs ($int_aid,$int_hid,$bool_navs=true,$int_id_to_skip=0,$bool_house_access=true)
{
	global $g_arr_house_extensions;

	// Zugangsberechtigungen abrufen
	$sql = 'SELECT value2 FROM keylist WHERE owner='.$int_aid.' AND value1='.$int_hid.' AND type='.HOUSES_KEY_PRIVATE;
	$arr_user_keys = db_create_list(db_query($sql),'value2',true);

	// Räumearray zusammenstellen
	$arr_rooms = array	(
	HOUSES_ROOM_BASEMENT 	=> array('{type:MIT_LABEL, label:"Keller"}','{type:MIT_BREAK}'),		// Keller
	HOUSES_ROOM_GROUND		=> array('{type:MIT_LABEL, label:"Erdgeschoß"}','{type:MIT_BREAK}'),	// Erdgeschoß
	HOUSES_ROOM_1ST			=> array('{type:MIT_LABEL, label:"1. Stock"}','{type:MIT_BREAK}'),		// 1. Stock
	HOUSES_ROOM_2ND			=> array('{type:MIT_LABEL, label:"2. Stock"}','{type:MIT_BREAK}'),		// 2. Stock
	HOUSES_ROOM_ROOF		=> array('{type:MIT_LABEL, label:"Dachgeschoß"}','{type:MIT_BREAK}'),	// Dachgeschoß
	HOUSES_ROOM_TOWER		=> array('{type:MIT_LABEL, label:"Turm"}','{type:MIT_BREAK}')			// Turm
	);
	
	$arr_room_data = Cache::get(Cache::CACHE_TYPE_HDD,'houserooms'.$int_hid);
	
	if($arr_room_data == false)
	{
		$sql = 'SELECT he.type,he.owner,he.id,he.name,he.loc,he.val,a.login AS oname
				FROM house_extensions he
				LEFT JOIN accounts a ON a.acctid = he.owner
				WHERE he.houseid='.$int_hid.' AND he.loc IS NOT null AND he.level > 0 AND he.owner > 0';
		/*if($int_id_to_skip)
		{
		$sql .= ' AND id != '.(int)$int_id_to_skip;
		}
		$res = db_query($sql);*/
		$arr_room_data = db_get_all($sql);
		Cache::set(Cache::CACHE_TYPE_HDD,'houserooms'.$int_hid,$arr_room_data);
	}

	if(sizeof($arr_room_data))
	{

		foreach($arr_room_data as $e)
		{

			$arr_e_type = $g_arr_house_extensions[$e['type']];

			if($e['id'] == $int_id_to_skip)
			{
				continue;
			}

			// Wenn beschränkter Zugang & keinen Zutritt & != eigenes: Weiter
			if($e['owner'] > 0 && $e['owner'] != $int_aid && $e['val'] && !isset($arr_user_keys[$e['id']])) {
				continue;
			}

			// Wenn kein Zugang zum Haus (sondern nur auf Einladung in einzelnem Gemach)
			if(!$bool_house_access)
			{
				// Gemachnavi nur anzeigen, wenn auch dort eingeladen oder selber Eigentümer
				if($e['owner'] != $int_aid && !isset($arr_user_keys[$e['id']]))
				{
					continue;
				}
			}

			// Namen ermitteln
			if(empty($e['name'])) {
				$e['name'] = $arr_e_type['name'];
			}
			else {
				$e['name'] = strip_appoencode($e['name'],3);
			}
			$e['name'] = addslashes($e['name']);

			if(mb_strlen($e['name']) > 19) {
				$e['name'] = mb_substr($e['name'],0,19).'..';
			}

			$str_js = '{type:MIT_NORMAL, label:"'.$e['name'].'", link:"house_extensions.php?_ext_id='.$e['id'].'"';

			if(!empty($e['oname'])) {
				$str_js .= ', hint:"Gehört '.addslashes($e['oname']).'"';
			}

			$str_js .= '}';

			// Im Flag steckt das Stockwerk
			if(isset($arr_rooms[$e['loc']])) {
				//$arr_rooms[$e['val']][$e['id']] = $e['name'];
				$arr_rooms[$e['loc']][] = $str_js;

			}
			// Wenn Gemach in der Luft schwebt: Erdgeschoss
			else {
				$arr_rooms[HOUSES_ROOM_GROUND][] = $str_js;
			}

			addnav('','house_extensions.php?_ext_id='.$e['id']);

		}
	}

	//db_free_result($res);
	// END Räumearray zusammenstellen

	// Räume-Navis
	if($bool_navs)
	{
		if(count($arr_rooms[HOUSES_ROOM_BASEMENT]) > 2 || count($arr_rooms[HOUSES_ROOM_GROUND]) > 2 || count($arr_rooms[HOUSES_ROOM_1ST]) > 2 || count($arr_rooms[HOUSES_ROOM_2ND]) > 2 || count($arr_rooms[HOUSES_ROOM_ROOF]) > 2 || count($arr_rooms[HOUSES_ROOM_TOWER]) > 2) {
			addnav('Räume');
			if(sizeof($arr_rooms[HOUSES_ROOM_TOWER]) > 2) {
				addnav_menu('Turm',$arr_rooms[HOUSES_ROOM_TOWER],false,false,false,false);
			}
			if(sizeof($arr_rooms[HOUSES_ROOM_ROOF]) > 2) {
				addnav_menu('Dachgeschoß',$arr_rooms[HOUSES_ROOM_ROOF],false,false,false,false);
			}
			if(sizeof($arr_rooms[HOUSES_ROOM_2ND]) > 2) {
				addnav_menu('2. Stock',$arr_rooms[HOUSES_ROOM_2ND],false,false,false,false);
			}
			if(sizeof($arr_rooms[HOUSES_ROOM_1ST]) > 2) {
				addnav_menu('1. Stock',$arr_rooms[HOUSES_ROOM_1ST],false,false,false,false);
			}
			if(sizeof($arr_rooms[HOUSES_ROOM_GROUND]) > 2) {
				addnav_menu('Erdgeschoß',$arr_rooms[HOUSES_ROOM_GROUND],false,false,false,false);
			}
			if(sizeof($arr_rooms[HOUSES_ROOM_BASEMENT]) > 2) {
				addnav_menu('Keller',$arr_rooms[HOUSES_ROOM_BASEMENT],false,false,false,false);
			}
		}
		return array();
	}
	else
	{
		return($arr_rooms);
	}
}

/**
	* @author salator
	* @desc gibt Userinterface zur manuellen Sortierung von Schlüsseln aus
	* @param string SQL-WHERE Konditionen (Achtung: nur Felder der KEYLIST-Table verfügbar!)
	* @param string weitere SQL-Bedingungen
	* @param string Feld nach dem per Default sortiert werden soll
	* @param int Flags die Angeben welches Optionale Feld angezeigt werden soll. XOR verknüpft!
	* @return fertiges html-Formular (ohne Zurück-Link!)
	*/
function keylist_set_sort_order ($sql_what, $sql_where, $sql_extra=false, $str_sort_field = 'sort_order', $bool_show_housetype = false)
{
	global $session, $_POST;
	if($sql_extra===false)
	{
		$sql_extra='ORDER BY '.$str_sort_field.' DESC, id ASC';
	}
	$filename=$_SERVER['REQUEST_URI'];
	$filename=mb_substr($filename,mb_strrpos($filename,'/')+1);

	if(is_array($_POST['sortorder']))
	{
		foreach($_POST['sortorder'] as $key_id => $sort_order)
		{
			if($_POST['sortorig'][$key_id]!=$sort_order)
			{
				$sort_order=min(intval($sort_order),255);
				$val=max(0,$sort_order);
				if(db_query('UPDATE keylist SET '.$str_sort_field.'='.$sort_order.' WHERE id='.$key_id) !=false)
				{
					$counter++;
				}
			}
		}
		if($counter>0)
		{
			$str_out.='`n`@'.$counter.' Schlüssel wurde(n) verändert.`0';
		}
	}

	$arr_items=db_get_all('SELECT '.$sql_what.' FROM keylist k LEFT JOIN accounts a ON acctid=k.owner LEFT JOIN houses h ON h.houseid=k.value1 WHERE '.$sql_where.' '.$sql_extra);
	if(count($arr_items)>0)
	{
		$str_out.='`0`n<form action="'.$filename.'" method="post">
			<table border="0">
			<tr class="trhead">
			<th>Level</th>
			<th>Name</th>
			'.($bool_show_housetype ? '<th>Haustyp</th>':'').'
			</tr>';
		foreach ($arr_items as $number => $key)
		{
			$str_out.='<tr class="'.$trclass.'">
				<td>
				<input type="text" name="sortorder['.$key['id'].']" value="'.$key['sort_order'].'" size="3" maxlength="3">
				<input type="hidden" name="sortorig['.$key['id'].']" value="'.$key['sort_order'].'">
				</td>
				<td>'.$key['name'].'</td>
				'.($bool_show_housetype ? '<td>'.get_house_state($key['status'],0,false).'</td>':'').'
				</tr>';
		}
		$str_out.='</table>
			<input type="submit" value="Speichern" class="button">
			</form>
			(Zulässige Werte sind 0-255, höhere Werte stehen oben)`n`n';
		addnav('',$filename);
	}
	else
	{
		$str_out.='Diese Auswahl enthält keine Schlüssel`n`n';
	}
	return ($str_out);
}

?>