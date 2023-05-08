<?php
/**
* dg_funcs.lib.php: Funktionsbibliothek für das Gildensystem, muss eingebunden werden, will man diese nutzen: Keine automatische Einbindung in common!
*				Enthält zusätzlich die Konstantendefinitionen und div. Arrays
*				Diese Datei ist Bestandteil des Drachenserver-Gildenmods (DG).
*				Copyright-Box muss intakt bleiben, bei Verwendung Mail an Autor mit Serveradresse.
*				Entwicklungszeitraum 6/05 - 9/05
* @author talion <t@ssilo.de>
* @version DS-E V/2
*/

define('DG_FUNC_APPLICANT',1);
define('DG_FUNC_MEMBER',2);
define('DG_FUNC_WAR',3);
define('DG_FUNC_TREASURE',4);
define('DG_FUNC_MEMBERS',5);
define('DG_FUNC_LEADER',6);
define('DG_FUNC_CANCELLED',99);
define('DG_FUNC_INVITED',98);

define('DG_TREATY_WAR_SELF',1);
define('DG_TREATY_WAR_OTHER',2);
define('DG_TREATY_PEACE_SELF',3);
define('DG_TREATY_PEACE_OTHER',4);

define('DG_STATE_INACTIVE',1);
define('DG_STATE_ACTIVE',2);
define('DG_STATE_IN_PROGRESS',3);

define('DG_GLD_BOOK',1);

define('DG_GUILD_TYPE_WARRIOR',1);
define('DG_GUILD_TYPE_WIZARD',2);
define('DG_GUILD_TYPE_THIEVES',3);
define('DG_GUILD_TYPE_TRADE',4);
define('DG_GUILD_TYPE_SECT',5);

define('DG_BUILD_MAX_LVL',5);
define('DG_MAX_BUILDLVL_GES',30);

$dg_funcs = array(
					DG_FUNC_APPLICANT => array('Bewerber','Bewerberin'),
					DG_FUNC_MEMBER => array('Mitglied','Mitglied'),
					DG_FUNC_WAR => array('Kriegsmeister','Kriegsmeisterin'),
					DG_FUNC_TREASURE => array('Schatzmeister','Schatzmeisterin'),
					DG_FUNC_MEMBERS => array('Lehrmeister','Lehrmeisterin'),
					DG_FUNC_LEADER => array('Gildenführer','Gildenführerin'),
					DG_FUNC_CANCELLED => array('Ausgetreten/Wartezeit','Ausgetreten/Wartezeit')
					);

$dg_states = array(
					DG_STATE_INACTIVE => '`iOhne Genehmigung`i',
					DG_STATE_ACTIVE => '',
					DG_STATE_IN_PROGRESS => '`iIm Bau`i'
					);

$dg_default_ranks = array(
					1 => array('Gildenmeister','Gildenmeisterin'),
					2 => array('Konsul','Konsulin'),
					3 => array('Vizekonsul','Vizekonsulin'),
					4 => array('Gildenrat','Gildenrätin'),
					5 => array('Ehrenmitglied','Ehrenmitglied'),
					6 => array('Vollmitglied','Vollmitglied'),
					7 => array('Meister','Meisterin'),
					8 => array('Geselle','Gesellin'),
					9 => array('Lehrling','Lehrling'),
					10 => array('Novize','Novizin')
				);

$dg_points = array(
					'regalia_stolen'=>5,
					'warmaster_killed'=>3,
					'guard_killed'=>2,
					'npc_guard_killed'=>0,
					'friendly_killed'=>-2,
					'neutral_killed'=>0,
					'enemy_killed'=>0,
					'war_cost'=>4,
					'war_round'=>0,
					'war_attack'=>1,
					'wedding_friendly'=>8,
					'wedding_neutral'=>4,
					'peace'=>1,
					'newday'=>1,
					'dk'=>1
					);

define('DG_BUILD_WAFFENKAMMER',1);
define('DG_BUILD_WACHTURM',2);
define('DG_BUILD_BIBLI',3);
define('DG_BUILD_LABOR',4);
define('DG_BUILD_VERSTECK',5);
define('DG_BUILD_SCHATZKAMMER',6);
define('DG_BUILD_SCHMIEDE',7);
define('DG_BUILD_GIFT',8);
define('DG_BUILD_KONTOR',9);
define('DG_BUILD_WALL',10);
define('DG_BUILD_JUWELIER',11);
define('DG_BUILD_GEHEIM',12);
define('DG_BUILD_STALL',13);
define('DG_BUILD_HALLE',14);
define('DG_BUILD_ALTAR',15);
define('DG_BUILD_GEWOELBE',16);

// Erklärung:
//		Array-Feld 0: Name 1: Gildentyp 2: Goldkosten 3: Edelsteinkosten 4: Farbe
//
$dg_builds = array(
					DG_BUILD_WAFFENKAMMER => array('name'=>'Waffenkammer','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>120000,'gemcost'=>120,'color'=>'','op'=>'waffenkammer','desc'=>'Die `bWaffenkammer`b stellt einen sicheren Aufbewahrungsort für Waffen dar - auch über eine Heldentat hinaus.'),
					DG_BUILD_WACHTURM => array('name'=>'Wachturm','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>100000,'gemcost'=>100,'color'=>'','op'=>'','desc'=>'Der `bWachturm`b stärkt durch seine wuchtige Mauern und den Ausblick die Verteidigung unserer Gildenwache.'),
					DG_BUILD_BIBLI => array('name'=>'Bibliothek','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>200000,'gemcost'=>160,'color'=>'','op'=>'bibli','desc'=>'Die `bBibiliothek`b bietet das günstige und rasche Lernen neuer Fähigkeiten.'),
					DG_BUILD_LABOR => array('name'=>'Alchemielabor','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>150000,'gemcost'=>120,'color'=>'','op'=>'labor','desc'=>'Das `bAlchemielabor`b ermöglicht das Brauen von Zaubertränken.'),
					DG_BUILD_VERSTECK => array('name'=>'Versteck','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>150000,'gemcost'=>110,'color'=>'','op'=>'','desc'=>'Das `bVersteck`b erhöht die Anzahl der Söldner, die maximal für die Gildenwache angeworben werden können und senkt ( ab dem Dritten Ausbau ) die Steuerlast.'),
					DG_BUILD_SCHATZKAMMER => array('name'=>'Schatzkammer','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>180000,'gemcost'=>100,'color'=>'','op'=>'','desc'=>'Die `bSchatzkammer`b steigert die Anzahl an möglichen Transfers sowie die Größe der Truhen.'),
					DG_BUILD_SCHMIEDE => array('name'=>'Schmiede','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>200000,'gemcost'=>150,'color'=>'','op'=>'schmiede','desc'=>'Die `bSchmiede`b kann stärkere Rüstungen fertigen'),
					DG_BUILD_GIFT => array('name'=>'Giftmischerei','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>200000,'gemcost'=>170,'color'=>'','op'=>'gift','desc'=>'Die `bGiftmischerei`b lässt die Mitglieder an sämtlichen Wachen vorbei in Häuser einbrechen.'),
					DG_BUILD_KONTOR => array('name'=>'Handelskontor','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>200000,'gemcost'=>170,'color'=>'','op'=>'kontor','desc'=>'Das `bHandelskontor`b bietet Rabatte bei verschiedenen Händlern.'),
					DG_BUILD_WALL => array('name'=>'Magischer Schutzwall','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>100000,'gemcost'=>100,'color'=>'','op'=>'','desc'=>'Der `bMagische Schutzwall`b erhöht die Stärke der Gildenwache.'),
					DG_BUILD_JUWELIER => array('name'=>'Juwelier','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>200000,'gemcost'=>150,'color'=>'','op'=>'juwelier','desc'=>'Der `bJuwelier`b ermöglicht die Herstellung von Edelsteinen.'),
					DG_BUILD_GEHEIM => array('name'=>'Geheimdienst','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>130000,'gemcost'=>100,'color'=>'','op'=>'geheim','desc'=>'Der `bGeheimdienst`b lässt dich Häuser ausspionieren.'),
					DG_BUILD_HALLE => array('name'=>'Versammlungshalle','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>150000,'gemcost'=>100,'color'=>'','op'=>'','desc'=>'Die `bVersammlungshalle`b erhöht die Anzahl der Mitglieder, die in deiner Gilde Platz finden.'),
					DG_BUILD_ALTAR => array('name'=>'Altar','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>100000,'gemcost'=>100,'color'=>'','op'=>'altar','desc'=>'Der `bAltar`b lässt die Gildengurus Wunder bewirken.'),
					DG_BUILD_STALL => array('name'=>'Tierstall','special_types'=>array(),'forbidden_types'=>array(),'goldcost'=>100000,'gemcost'=>100,'color'=>'','op'=>'stall','desc'=>'Der `bStall`b beherbergt einige sehr seltene Tiere.'),
					DG_BUILD_GEWOELBE => array('name'=>'Lagerkeller','special_types'=>true,'forbidden_types'=>array(),'goldcost'=>120000,'gemcost'=>120,'color'=>'','op'=>'','desc'=>'Der `bLagerkeller`b schafft neuen Platz in überfüllten Gewölben.')
					);

$dg_build_levels = array(
					0 => 'Nicht vorhanden',
					1 => 'Rohbau',
					2 => 'Baracke',
					3 => 'Haus',
					4 => 'Prunkhaus',
					5 => 'Perfektion'
					);

// 0: Name, 1: Farbe, 2: Beschreibung, 3: Elterntyp
$dg_types = array(
					DG_GUILD_TYPE_WARRIOR => array('name'=>'`AKriegerbund','desc'=>'`AOb Raubritter, Ritterorden, Paladinorden oder Kopfgeldjäger, jene Gilden folgen dem Pfad der Klinge und gelten als mächtige Feinde im Kampf, jedoch auch als gute Verbündete in der Not.`n'),
					DG_GUILD_TYPE_WIZARD => array('name'=>'`WMagierbund','desc'=>'`WDie Gelehrten der Heilerorden, Magierzirkel, Dunklen Orden, Horten des Wissens oder Akademien intensivieren ihr Wissen und ihre Fähigkeiten mit jeder Sekunde ihres Lebens`nBöse Zungen mögen behaupten, manche von ihnen könnten mit Tränken ihre Feinde in Frösche verwandeln, doch gibt es ebenso Gerüchte über großartige magische Taten.`n'),
					DG_GUILD_TYPE_THIEVES => array('name'=>'`(Verbrecherbund','desc'=>'`(Die Zeit der Assassinenorden, Meisterdiebe und Schmuggler ist die Dämmerung, sie bevorzugen heimliche Aktivitäten und lassen sich nur selten in die Karten schauen. Vielleicht besser so, wird ihnen doch auch eine stetige Abneigung zur Wahrheit nachgesagt.`n'),
					DG_GUILD_TYPE_SECT => array('name'=>'`yGemeinschaft','desc'=>'`yDie Gemeinschaft zieht wohl das bunteste Volk an. Ob Geheimbund oder Kult, dort geben sie sich ihren Geheimnissen oder dunklen Ritualen hin. Auch Schausteller sind in der Gemeinschaft zu finden, ebenso wie die eine oder andere Gemeinde.`n'),
					DG_GUILD_TYPE_TRADE => array('name'=>'`/Händlerbund','desc'=>'`/Jene Krämer, Handelsbünde und Handwerkerzünfte lieben es zu Handeln und zu Feilschen, sie sind stolz ihren Stand und jene Waren, die sie feilbieten und vielleicht auch vorher selbst hergestellt werden. Mag auch mancher von ihren die eigene Großmutter für eine Münze hergeben, gehören sie doch unweigerlich auf jeden Marktplatz.`n')
					);
$dg_child_types = array(
					1=>array('Raubritter','`A','`8Ein dunkler Weg führt zu der finster wirkenden Festung der `4`bRaubritter`b`8, auf deren Wappen die Namen der ruhmreichen Krieger geschrieben stehen. Wachtürme säumen die dicken Steinmauern, Eisenfenster gebe dem Gebäude ein kerkerartiges Aussehen. In der Luft hängen die Gerüche von Schweiß, Dreck und flüssigem Metall, durch die Arbeit der Krieger und Schmiede.',DG_GUILD_TYPE_WARRIOR),
					array('Ritterschaft','`A','`8Prunkvoll im Licht von Aufmerksamkeit und Ehre steht ein mittelgroßes, schlossähnliches Anwesen auf einem guten Grundstück in der Nähe des Rathauses. Hohe Mauern umgeben es und versperren dir die Sicht, man hört jedoch zusammenstoßende Lanzen, Schlachtrufe vom Übungsplatz hinter der Festung. Spärlicher Beifall erklingt gelegentlich von den anderen Mitgliedern der `bRitterschaft`b.',DG_GUILD_TYPE_WARRIOR),
					array('Paladinorden','`A','`8Ehre und Ansehen im Dienste des Guten: Das ist das Ziel des heroischen `bPaladinordens`b. Ihre Residenz ähnelt einer prachtvollen Burg, die starken Mauern scheinen in der Sonne gleißend weiß. Im Inneren geben sich die Paladine und ihre Schüler schweißtreibenden, die Muskeln und den Geist stählenden Exerzitien hin.',DG_GUILD_TYPE_WARRIOR),
					array('Kopfgeldjäger','`A','`8Du näherst dich vorsichtig dem kleinen, verkommenen Gebäude aus Holz. Leise knirschend geht die Türe auf und gibt den Blick auf eine Art Büro frei. Du siehst in der Eingangshalle eine Liste mit Preisen. „Kopf ab! - Schon ab 5000 Goldstücken!“ Nun weißt du, dass du dich in der Nähe der ehrlosen `bKopfgeldjäger`b befindest.',DG_GUILD_TYPE_WARRIOR),
					array('Heilerorden','`W','`8Im Licht der Sonne strahlt das Kloster beruhigend auf dich herab. Du betrittst es ohne zu zögern, als dir auch schon der Geruch von Kräutern und Heilmitteln in der Nase kitzelt. Du siehst die Schwestern und Brüder des `bHeilerordens`b auf und ab gehen und sich um Verletzte und Kranke kümmern. Die Heilung dieser Orte ist berühmt.',DG_GUILD_TYPE_WIZARD),
					array('Hort des Wissens','`W','`8Alt... oder eher ehrwürdig... oder sogar beides? So läßt sich das Gebäude vor dir am besten beschreiben. Die Mauern aus schwarzem Felsen unbestimmter Dicke, dazu die schweren dunklen Hölzer! Ein Ort voller Mystik und Verschwiegenheit. Immer wieder betreten Gestalten, in lange Gewänder gehüllt, dieses Haus, bringen Bücher, Schriften und andere Gegenstände hinein. Aber nie sieht man eine der Schriften es verlassen! ~Was~ sich innen befindet, weiß außer den Robenträgern wohl niemand... für jeden Beobachter muß dieses ein `bHort des Wissens`b sein...',DG_GUILD_TYPE_WIZARD),
					array('Magierzirkel','`W','`8Magischer Nebel schützt das gesamte Grundstück in dessen Mittelpunkt sich ein Turm in die Höhe erstreckt. Die Mauern sind aus feinstem Marmor und scheinen von Zaubern durchflochten. Eine starke Aura und ein sanftes, unwirkliches Schimmern begleiten die feinen Zinnen, die gefährlicher wirken als sie in Wirklichkeit sind. Man spürt deutlich die mystischen Kräfte, die von dem `b`WMagierzirkel`b`8 ausgehen.',DG_GUILD_TYPE_WIZARD),
					array('Akademie','`W','`8Ein Ehrfurcht gebietendes Gebäude, in welchem ein reger Austausch von Wissen stattfindet. Hier ist der Ort, zu lehren und zu lernen, zu disputieren, zu forschen und zu erfinden...',DG_GUILD_TYPE_WIZARD),
					array('Dunkler Orden','`W','`8Schon während ihrer Ausbildung haben sie ihre Grausamkeit und Skrupellosigkeit gezeigt. Viele von ihnen wurden der Akademien verwiesen und schlossen sich in finstren Gemeinschaften zusammen. In den tiefen Katakomben ihrer geheimnisumwitterten Feste versucht sich der `bDunkle Orden`b an den grausamsten Experimenten und dunkelsten Magien.',DG_GUILD_TYPE_WIZARD),
					array('Assasinenorden','`(','`8Im Hinterhof eines unansehnlichen Gebäudes hinter der Schenke lauschst du auf Geräusche. Versteckte Augen verfolgen jeden der sich nähert, bereit Verdächtige umzulegen, bevor sie auch nur bemerken, dass sie beobachtet werden. Der Ruf dieses `bAssasinenordens`b ist legendär, ebenso wie ihre Arbeit. Präzise und sauber, doch alles zu seinem Preis.',DG_GUILD_TYPE_THIEVES),
					array('Meisterdiebe','`(','`8Unauffällig plant hier ein besonderer Verband seine Raubzüge. Exakte und geschickte Aktionen zur richtigen Zeit am richtigen Ort sind ihre Spezialität. Selbst Aufmerksamkeit und Vorsicht können etwaige Reichtümer nicht vor diesen `bMeisterdieben`b schützen. ',DG_GUILD_TYPE_THIEVES),
					array('Schmuggler','`(','`8Ein Gebäude wie jedes andere, welches höchstens seine Unauffälligkeit charakterisiert. Im Inneren jedoch gibt es zahlreiche ausgeklügelte Verstecke für... auf nicht ganz legalem Weg erworbene Waren.',DG_GUILD_TYPE_THIEVES),
					array('Handelsbund','`/','`8Hinter dem Rathaus, auf einem öffentlichen, sehr prunkvollen Platz, befindet sich der große, mit vielen Edelsteinen verzierte Handelsraum der Händlergilde. Die verschiedensten Waren werden dort an vielen Ständen vorgestellt und angepriesen. Im hinteren Bereich befinden sich Tagungsräume für die Zusammentreffen und Schatzkammern gefüllt mit Gold und Edelsteinen, der Profit des `b`^Handelbunds`b`8.',DG_GUILD_TYPE_TRADE),
					array('Handwerkerzunft','`/','`8Wie könnte eine Stadt, die immer wieder vom Grünen Drachen heimgesucht wird, ohne sie leben. In Windeseile erschaffen sie Häuser, Brücken, Tore, Mauern und Gilden. Einige von ihnen haben sich auch auf feinere Gebiete wie die Tischlerei spezialisiert. Kurz gesagt: Was auch immer die Bewohner an Handwerkszeug benötigen, die `bHandwerkerzünfte`b können es aus ihren wohl anzuschauenden Bürgerhäusern liefern.`8.',DG_GUILD_TYPE_TRADE),
					array('Krämer','`/','`8Ein großes Gebäude, welches ein wenig an eine Markthalle erinnert - schließlich kommen hier auch die kleinen Händler, die `/Krämer`8 zusammen, um untereinander Geschäfte zu machen, Preisabsprachen zu treffen und sich auszutauschen.',DG_GUILD_TYPE_TRADE),
					array('Geheimbund','`y','`8Tief in unterirdischen Verliesen, geschützt durch die vielen Geheimgänge sowie Fallen liegt der `bGeheimbund`b. Rätselhafte, komplizierte Runen und Symbole weisen den Weg in sein Inneres. Weisheit, Gerissenheit und Intelligenz gelten hier als oberste Priorität. Die Pflichten bestehen darin, das Geheime aufzudecken, die Bedeutungen, die im Verborgenen lagen, ans Licht zu bringen.',DG_GUILD_TYPE_SECT),
					array('Kult','`y','`8Von Schlingpflanzen und Unkraut überwuchert befindet sich in der hintersten Ecke des Gildenviertels ein baufälliges Gebäude. Unter den Pflanzen schauen kunterbunt bemalte Wände und halbblinde Fensteröffnungen hervor. Vor dem breiten Eingang rauchen mehrere halbnackte, dürre Wesen mit leeren Augen wohlriechende Kräuter, die sie scheinbar das unüberhörbare Murmeln von ewiggleichen Mantras ertragen lässt. An erhöhter Stelle haben langbärtige, ältere Herren einige Dumm..,Schüler um sich geschart: Was für ein `bKult`b`8!',DG_GUILD_TYPE_SECT),
					array('Gemeinde','`y','`8Weit ab von den Bewohnern der Stadt liegt euer kleines, friedliches Anwesen, beladen mit Kultsymbolen. Nur hier könnt ihr euren Glauben stärken und festigen, ohne den störenden Einflüssen der Aussenwelt zu unterliegen. Absolute und bedingungslose Hingabe an eure Götter ist die oberste Pflicht der `bGemeinde`bmitglieder.',DG_GUILD_TYPE_SECT),
					array('Sekte','`y','`8Ein düsteres, für Außenstehende nicht gerade einladend wirkendes Gebäude, in dem ihr euren Glauben ausleben und Pläne zur Missionierung (oder Auslöschung) der Ungläubigen schmieden könnt.',DG_GUILD_TYPE_SECT),
					array('Schausteller','`y','`8Hier kommt das bunte Volk zusammen: Schauspieler, Musiker, Künstler. Wenn man sich einmal nicht der Ausübung der Kreativität widmet, wird hier auch gelacht und ausgelassen gefeiert.',DG_GUILD_TYPE_SECT));

$arr_dg_weaponnames = array(0 => 'Holzknüppel',
							1 => 'Stahlschwert',
							2 => 'Streitkolben aus Mondsilber',
							3 => 'Mithril-Langschwert'
							);
$arr_dg_armornames = array(0 => 'Lederwamst',
							1 => 'Kettenhemd',
							2 => 'Mondsilber-Schuppenpanzer',
							3 => 'Mithril-Plattenrüstung'
							);

$dg_session_copy = array();
$session['guilds'] = array();

function &dg_calc_strength ($gid_array) {

	$gid_str = implode(',',$gid_array);
	$res_array = array();

	$sql = 'SELECT AVG(dragonkills) AS avgdk, guildid FROM accounts WHERE guildid IN(-1,'.$gid_str.') AND guildfunc != '.DG_FUNC_APPLICANT.' GROUP BY guildid';
	$res = db_query($sql);

	while($a = db_fetch_assoc($res)) {

		$res_array[$a['guildid']] = $a['avgdk'];

	}

	return($res_array);

}


// Funktion berechnet alle sich durch Ausbauten und Gildentypen dynamisch ergebenden Boni
// $part gibt den Namen des Boni an, etwa 'attack' für den Einfluss der Ausbauten auf den Angriffswert des Spielers
// $val der Wert, welcher modifiziert werden soll
function dg_calc_boni ($gid,$part,$val) {

	$g = &dg_load_guild($gid,array('build_list','type'));

	switch($part) {

		case 'player_guildfights':

			$val *= 1;

			break;

		case 'regalia_steal_active':

			$val += ($g['ptype'] == DG_GUILD_TYPE_THIEVES ? 1 : 0);

			break;

		case 'regalia_steal_passive':

			$val -= ($g['ptype'] == DG_GUILD_TYPE_WIZARD || $g['ptype'] == DG_GUILD_TYPE_THIEVES ? 1 : 0);

			break;

		case 'steal_factor_active':

			$val += ($g['ptype'] == DG_GUILD_TYPE_THIEVES ? 0.01 : 0);

			break;

		case 'steal_factor_passive':

			$val -= ($g['ptype'] == DG_GUILD_TYPE_THIEVES ? 0.005 : 0);

			break;


		case 'transfers_out':

			$val *= 1;

			break;

		case 'maxgoldin':

			$val *= 1 + $g['build_list'][DG_BUILD_SCHATZKAMMER] * 0.03;
			$val *= ($g['ptype'] == DG_GUILD_TYPE_TRADE) ? 1 : 1;

			break;

		case 'maxgemsin':

			$val *= 1 + $g['build_list'][DG_BUILD_SCHATZKAMMER] * 0.03;
			$val *= ($g['ptype'] == DG_GUILD_TYPE_TRADE) ? 1 : 1;

			break;

		case 'treasure_maxgold':

			$val *= 1 + $g['build_list'][DG_BUILD_SCHATZKAMMER] * 0.02;

			break;

		case 'treasure_maxgems':

			$val *= 1 + $g['build_list'][DG_BUILD_SCHATZKAMMER] * 0.02;

			break;

		case 'rebates_weapon':

			$val += ($g['ptype'] != DG_GUILD_TYPE_WIZARD) ? round($g['build_list'][DG_BUILD_KONTOR]*1.3) : 0;

			break;

		case 'rebates_armor':

			$val += max( round($g['build_list'][DG_BUILD_KONTOR]*1.3)-1 , 0 );

			break;

		case 'rebates_vendor':

			$val += round($g['build_list'][DG_BUILD_KONTOR] * 1.3);

			break;

		case 'rebates_spells':

			$val += ($g['ptype'] == DG_GUILD_TYPE_WIZARD) ? round($g['build_list'][DG_BUILD_KONTOR]*1.3) : max( round($g['build_list'][DG_BUILD_KONTOR]*1.3)-2,0);

			break;

		case 'members':

			// Halle
			// Lvl * 2 dazu
			$halle = $g['build_list'][DG_BUILD_HALLE];
			$val += $halle * 2;

			$val += round( min(dg_get_ges_build($gid)*0.25,12) );

			break;

		case 'guard_atk':

			$val *= pow(1.03,$g['build_list'][DG_BUILD_WACHTURM]);

			break;

		case 'guard_def':

			$val *= pow(1.03,$g['build_list'][DG_BUILD_WALL]);


			break;

		case 'guard_hp_before':

			$val = round($val * 0.5);

			break;

		case 'maxguards':

//			$val = 500 - ($g['build_list'][DG_BUILD_WACHTURM] * 5);
			$val = 500;
			$val += $g['build_list'][DG_BUILD_VERSTECK] * 30;

			break;

		case 'tax':

			//$val = ($g['ptype']==DG_GUILD_TYPE_SECT?round($val*0.85):$val);

			$val = ($g['build_list'][DG_BUILD_VERSTECK] >= 3?round($val*0.85):$val);

			break;

		case 'member_tribute':

			$val *= 1;

			break;

		case 'player_dkgold':

			$val *= ($g['ptype']==DG_GUILD_TYPE_TRADE?1:1);

			break;

		case 'player_turns':

			$val *= 1;

			break;

		case 'player_castleturns':

			$val *= 1;

			break;

		case 'startgold':

			$val += ($g['ptype'] == DG_GUILD_TYPE_TRADE ? 10000 : 10000);

			break;

		case 'startgems':

			$val += ($g['ptype'] == DG_GUILD_TYPE_THIEVES ? 5 : 5);

			break;

		case 'startpts':

			$val += ($g['ptype'] == DG_GUILD_TYPE_WARRIOR ? 10 : 10);

			break;

		case 'startregalia':

			$val += ($g['ptype']==DG_GUILD_TYPE_SECT || $g['ptype'] == DG_GUILD_TYPE_WIZARD ?2:2);

			break;

		case 'startguardhp':

			$val += 0;

			break;

		case 'guardhp_buy':

			$val *= ($g['ptype']==DG_GUILD_TYPE_WARRIOR?1:1);

			break;

		case 'invent_space':

			// 10 % mehr Platz pro Gewölbelevel
			$val = ($g['build_list'][DG_BUILD_GEWOELBE] > 0
					? $val + round($val*0.1*$g['build_list'][DG_BUILD_GEWOELBE])
					: $val);

			break;

	}

	return($val);

}

function dg_guild_is_full ($gid) {

	$count = dg_count_guild_members($gid);

	$max_count = getsetting('dgmaxmembers',25);
	$max_count = dg_calc_boni($gid,'members',$max_count);

	return( max($max_count-$count,0) );

}

function dg_set_treaty ($gid,$target,$state=0,$offer=true) {

	global $session;

	$our_g = &dg_load_guild($gid,array('treaties','war_target'));
	$other_g = &dg_load_guild($target,array('treaties','war_target'));

	if($state == DG_TREATY_PEACE_SELF) {
		$own_state = array(DG_TREATY_PEACE_SELF,($offer) ? 1 : 0);
		$target_state = array(DG_TREATY_PEACE_OTHER,($offer) ? 1 : 0);
		if($our_g['war_target'] == $target) {
			$our_g['war_target'] = 0;
		}
	}
	elseif($state == DG_TREATY_PEACE_OTHER) {
		$own_state = array(DG_TREATY_PEACE_OTHER,($offer) ? 1 : 0);
		$target_state = array(DG_TREATY_PEACE_SELF,($offer) ? 1 : 0);
		if($our_g['war_target'] == $target) {
			$our_g['war_target'] = 0;
		}
	}
	elseif($state == DG_TREATY_WAR_SELF) {
		$own_state = array(DG_TREATY_WAR_SELF,0);
		$target_state = array(DG_TREATY_WAR_OTHER,0);
	}
	elseif($state == DG_TREATY_WAR_OTHER) {
		$own_state = array(DG_TREATY_WAR_OTHER,0);
		$target_state = array(DG_TREATY_WAR_SELF,0);
	}
	else {	// neutral
		$own_state = array(0,0);
		// Bei Frieden mitaktualisieren, bei Krieg lassen
		if($other_g['treaties'][$gid] == DG_TREATY_PEACE_OTHER || $other_g['treaties'][$gid] == DG_TREATY_PEACE_SELF) {
			$target_state = array(0,0);
		}
	}

	$our_g['treaties'][$target] = $own_state;
	$session['guilds'][$target]['treaties'][$gid] = $target_state;
	//$other_g['treaties'][$gid] = $target_state;

}

// Funktion zur schnellen Feststellung des aktuellen Vertragsstatus. Gibt entweder 0 für neutral, -1 für Krieg, oder 1 für Frieden zurück
// Erwartet ein Vertragsarray
function dg_get_treaty ($treaty) {

	if(($treaty[0] == DG_TREATY_PEACE_OTHER || $treaty[0] == DG_TREATY_PEACE_SELF)
		&& ($treaty[1] == 0)) {

		return(1);

	}

	if($treaty[0] == DG_TREATY_WAR_OTHER || $treaty[0] == DG_TREATY_WAR_SELF
		) {

		return(-1);

	}

	return(0);

}

function dg_get_reputation ($gid) {

	$g = &dg_load_guild($gid,array('reputation'));

	if($g['reputation'] >= 100) return('Perfekt');
	if($g['reputation'] >= 90) return('Sehr gut');
	if($g['reputation'] >= 70) return('Gut');
	if($g['reputation'] >= 50) return('Durchschnittlich');
	if($g['reputation'] >= 30) return('Schlecht');
	return('Furchtbar');

}

function dg_pvp_kill ($badguy,$active) {
	global $session,$dg_points;

	$state = 0;

	if($active == 0) {	// Passiv
		$guild = &dg_load_guild($badguy['guildid'],array('treaties','points'));	// Gilde des Angegriffenen laden
		$guildid = $session['user']['guildid']; // ID des Angreifers
		$dk_diff = $badguy['dragonkills'] - $session['user']['dragonkills'];
	}
	else {
		$guild = &dg_load_guild($session['user']['guildid'],array('treaties','points'));	// Gilde des Angreifers laden
		$guildid = $badguy['guildid'];	// ID des Angegriffenen
		$dk_diff = $session['user']['dragonkills'] - $badguy['dragonkills'];
	}

	$state = dg_get_treaty($guild['treaties'][$guildid]);

	$points = 0;

	// Fallunterscheidung:
	if($state==-1) {
		$gp_add_msg = '`n`n`@Du hast ein Mitglied einer feindlichen Gilde getötet!';

		if($dk_diff <= 5) {
			$points = $dg_points['enemy_killed'];
			if($points > 0) {
				$gp_add_msg .= '`nDafür erhält deine Gilde `^'.$points.'`@ Gildenpunkte!';
				if($dk_diff < -15) {
					$points++;
					$gp_add_msg .= '`nWeil dein Gegner so stark war, gibt es sogar noch `^einen `@Gildenpunkt dazu.';
				}
				dg_log($points.' GP für feindlichen Kill',$guild['guildid'],$guildid);
			}

		}
		else {
			$points=0;
			$gp_add_msg .= '`nWeil dein Gegner so schwach war, gibt es `4keinen `@Gildenpunkt dazu!';
		}

	}
	elseif($state==1 && $active==1) {
		$gp_add_msg = '`n`n`4Du hast ein Mitglied einer befreundeten Gilde getötet!';

		$points = -1 * $dg_points['friendly_killed'];
		if($points > 0) {
			$gp_add_msg .= '`nDafür werden deiner Gilde `^'.($points*-1).'`4 Gildenpunkte abgezogen!';
			dg_log($points.' GP für befreundeten Kill',$guild['guildid'],$guildid);
		}
	}
	else {
		$gp_add_msg = '`n`n`@Du hast ein Mitglied einer neutralen Gilde getötet!';

		if($dk_diff <= 5) {
			$points = $dg_points['neutral_killed'];
			if($points > 0) {
				$gp_add_msg .= '`nDafür erhält deine Gilde `^'.$points.'`@ Gildenpunkte!';
				if($dk_diff < -15) {
					$points++;
					$gp_add_msg .= '`nWeil dein Gegner so stark war, gibt es sogar noch `^einen `@Gildenpunkt dazu.';
				}
				dg_log($points.' GP für neutralen Kill',$guild['guildid'],$guildid);
			}

		}
		else {
			$points=0;
			$gp_add_msg .= '`nWeil dein Gegner so schwach war, gibt es `4keinen `@Gildenpunkt dazu!';
		}

	}

	$guild['points']=max($guild['points']+$points,0);

	return($gp_add_msg);

}

function &dg_load_guild ($gid=0,$fields=array(),$overwrite=false,$order_sql='guildid ASC') {

	global $session,$dg_session_copy,$dg_child_types;

	$fields_str = '*';

	if(sizeof($fields) > 0) {

		if($gid && !$overwrite) {	// Überprüfung, ob bereits vorhanden

			foreach($fields as $k=>$a) {

				if(isset($session['guilds'][$gid][$a])) {unset($fields[$k]);}

			}

			if(sizeof($fields) == 0) {
				$ref = &$GLOBALS['session']['guilds'][$gid];return($ref);
			}
		}

		$fields_str = implode(',',$fields);
		$fields_str .= (mb_strstr($fields_str,'guildid') === false) ? ',guildid' : '';

	}

	else {

		if( is_array($session['guilds'][$gid]) ) {$ref = &$GLOBALS['session']['guilds'][$gid];return($ref);}

	}
	$sql = 'SELECT '.$fields_str.' FROM dg_guilds '.( ($gid) ? 'WHERE guildid='.$gid : '').' ORDER BY '.$order_sql;
	$res = db_query($sql);

	if(!db_num_rows($res)) {return(false);}

	while($g = db_fetch_assoc($res)) {

		if($g['type']) {
			$g['ptype'] = $dg_child_types[$g['type']][3];
		}

		if(mb_strlen($g['treaties']) > 3) {

			$g['treaties'] = utf8_unserialize($g['treaties']);

		}
		else
		{
			$g['treaties'] = array();
		}

		if(mb_strlen($g['ranks']) > 3) {

			$g['ranks'] = utf8_unserialize($g['ranks']);

		}
		else
		{
			$g['ranks'] = array();
		}

		if(mb_strlen($g['transfers']) > 3) {

			$g['transfers'] = utf8_unserialize($g['transfers']);

		}
		else
		{
			$g['transfers'] = array();
		}

		if(mb_strlen($g['build_list']) > 3) {

			$g['build_list'] = utf8_unserialize($g['build_list']);

		}
		else
		{
			$g['build_list'] = array();
		}

		if(mb_strlen($g['hitlist']) > 3) {

			$g['hitlist'] = utf8_unserialize($g['hitlist']);

		}
		else
		{
			$g['hitlist'] = array();
		}

		if(mb_strlen($g['building_vars']) > 3) {

			$g['building_vars'] = utf8_unserialize($g['building_vars']);

		}
		else
		{
			$g['building_vars'] = array();
		}

		if(is_array($session['guilds'][$g['guildid']])) {
			$session['guilds'][$g['guildid']] = array_merge($g,$session['guilds'][$g['guildid']]);
		}
		else {
			$session['guilds'][$g['guildid']] = $g;
		}
		//$dg_session_copy[$g['guildid']] = $g;

		if($session['user']['guildid'] == $g['guildid']) {

			$session['userguild'] =& $session['guilds'][$g['guildid']];

		}

		if(!is_array($dg_session_copy[$g['guildid']])) {
			$dg_session_copy[$g['guildid']] = array();
		}
		// Manche Arrays muss man zu ihrem Glück (und einer Übergabe OHNE Referenz) zwingen..
		// Funktioniert möglicherw. auch durch Verwendung des GLOBALS-Arrays
		$dg_session_copy[$g['guildid']] = array_merge($dg_session_copy[$g['guildid']],$session['guilds'][$g['guildid']]);

	}

	db_free_result($res);

	if($gid) {
		$ref = &$GLOBALS['session']['guilds'][$gid];
		return($ref);
	}

}

function dg_hitlist_add ($gid,$acctid,$bounty) {
	$g = &dg_load_guild($gid,array('treaties','hitlist','gold'));

	$g['gold'] -= $bounty * 1.1;
	$g['hitlist'][$acctid] = array('bounty'=>$bounty,'date'=>getsetting('gamedate',''));

}

function dg_hitlist_remove ($gid,$acctid,$success=true) {
	global $session;

	$g = &dg_load_guild($gid,array('hitlist','gold'));

	$bounty = $g['hitlist'][$acctid]['bounty'];

	if($success) {
		$session['user']['gold'] += $bounty;
	}
	else {
		$g['gold'] += $bounty;
	}
	unset($g['hitlist'][$acctid]);
	return($bounty);
}

function dg_count_guilds ($state=-1) {

	$sql = 'SELECT COUNT(*) AS anzahl FROM dg_guilds '.(($state != -1) ? ' WHERE state='.$state : '');
	$nr = db_fetch_assoc(db_query($sql));

	return($nr['anzahl']);

}

function dg_count_guild_members ($gid,$appl=false) {

	$sql = 'SELECT COUNT(*) AS nr FROM accounts WHERE guildid='.(int)$gid.($appl==false?' AND guildfunc!='.DG_FUNC_APPLICANT:'');
	$count = db_fetch_assoc(db_query($sql));
	return($count['nr']);

}

function dg_log ($message,$gid=0,$target=0) {
	/*todo deaktiviert bathi
	 *
	 * global $session;

	$gid = (!$gid) ? $session['user']['guildid'] : $gid;

	$sql = 'DELETE FROM dg_log WHERE date <\''.date( 'Y-m-d H:i:s' ,time()-( getsetting('expirecontent',180)*2*86400) ).'\'';
	db_query($sql);
	$sql = "INSERT INTO dg_log SET date=NOW(),guild=".$gid.",target=".$target.",message='".db_real_escape_string($message)."'";
	db_query($sql);
	 */
}

/**
 * Löscht eine Gilde
 *
 * @param int GildenID der zu löschenden Gilde
 * @return bool false / true
 */
function dg_delete_guild ($gid) {

	global $session,$dg_session_copy;

	if(!$gid) {return(false);}

	$guild = & dg_load_guild($gid,array('gold','gems','name','founder','founded','state','last_state_change', 'bio'));
	dg_load_member_list($gid);

	// Gildeninventar entfernen
	item_delete(' owner='.ITEM_OWNER_GUILD.' AND deposit1='.$gid);

	if(sizeof($guild['memberlist']) > 0) {

		// Schatz verteilen
		if($guild['last_state_change'] == '0000-00-00 00:00:00') {
			// In diesen Feldern hatten wir die Baukosten für die Gilde gespeichert
			user_update(
				array
				(
					'gold'=>array('sql'=>true,'value'=>'gold+'.$guild['gold']),
					'gems'=>array('sql'=>true,'value'=>'gems+'.$guild['gems']),
				),
				$guild['founder']
			);
			$guild['gold'] = 0;
			$guild['gems'] = 0;
		}
		else {

			$guild['gold'] = floor($guild['gold'] / sizeof($guild['memberlist']));
			$guild['gems'] = floor($guild['gems'] / sizeof($guild['memberlist']));

		}


		if( in_array($session['user']['acctid'],$guild['memberlist']) !== false) {

			$session['user']['guildid']	= 0;
			$session['user']['guildrank'] = 0;
			$session['user']['guildfunc'] = 0;
			$session['user']['gold'] += $guild['gold'];
			$session['user']['gems'] += $guild['gems'];

		}

		$member_list = implode(',',$guild['memberlist']);	

		//Bevor die User rausgeschmissen werden tragen wir die Gilde in das Museum ein.
		$arr_guild_leaders = db_get_all('SELECT acctid FROM accounts WHERE acctid IN(-1,'.$member_list.') AND (guildfunc = '.DG_FUNC_LEADER.' OR guildrank = 1) ',false,'acctid');
		$leader_list = '';
		foreach ($arr_guild_leaders as $leader)
		{
			$leader_list .= '|'.$leader.'|';
		}
		$arr_founder = db_get('SELECT IFNULL(a.name,"In Vergessenheit geraten") AS name FROM dg_guilds g LEFT JOIN accounts a ON (a.acctid = g.founder) WHERE g.guildid='.$gid);
		db_insert('museum_guilds',array('name'=>$guild['name'], 'bio' => $guild['bio'], 'founder' => $arr_founder['name'], 'leaders' => $leader_list, 'date_founded' => $guild['founded'], 'date_deleted' => getgamedate()));
		
		//User aus der Gilde entfernen und Stati zurücksetzen
		user_update(
			array
			(
				'guildid'=>0,
				'guildrank'=>0,
				'guildfunc'=>0,
				'gold'=>array('sql'=>true,'value'=>'gold+'.$guild['gold']),
				'gems'=>array('sql'=>true,'value'=>'gems+'.$guild['gems']),
				'where'=>'acctid IN(-1,'.$member_list.') AND acctid!='.$session['user']['acctid']
			)
		);
	}

	$sql = "DELETE FROM dg_guilds WHERE guildid=".$gid;
	db_query($sql);

	// Einladungen löschen
	item_delete('tpl_id="gldprive" AND value1='.$gid);

	$sql = 'UPDATE dg_guilds SET war_target=0 WHERE war_target='.$gid;
	db_query($sql);

	$sql = 'DELETE FROM history WHERE guildid='.$gid;
	db_query($sql);

	// Polls löschen
	require_once(LIB_PATH.'board.lib.php');
	poll_delete(0, 'guild'.$gid.'_hall');
	poll_delete(0, 'guild'.$gid.'_private');

	unset($session['guilds'][$gid]);
	unset($dg_session_copy[$gid]);

	return(true);
}

// Berechnet die maximale Menge an Gold / Gems, die (gildenweit) pro Tag eingezahlt werden darf
// what: 'gold', 'gems'
function dg_calc_max_transfer_in ($gid,$what) {
	global $session;

	$what = ($what == 'gold' ? 'gold' : 'gems');

	$guild = &dg_load_guild($gid,array('regalia','build_list','gold_in','gems_in','gold','gems'));

	// Theoretisch mögl. Maximum an einem Tag
	$res = dg_calc_boni($gid,'max'.$what.'in',getsetting('dgmax'.$what.'in',0));


	// Effekt durch Insignien
	$res *= min(1 + $guild['regalia'] * 0.05, 3);

	// Begrenzen durch Schatztruhenkapazität
	$maxres = dg_calc_boni($gid,'treasure_max'.$what,getsetting('dgtrsmax'.$what,0));
	$maxres = max($maxres - $guild[ $what ],0);

	// Begrenzen durch schon erfolgte Einzahlungen
	$res = max(0 , $res - $guild[$what.'_in']);

	// Beides gegeneinander begrenzen
	$res = min($res,$maxres);

	return( round($res) );

}

function dg_calc_max_transfer_out ($gid,$what,$acctid,$lvl=1) {
	global $session;

	$what = ($what == 'gold' ? 'gold' : 'gems');

	$user = user_get_aei('goldin,gemsin');

	if($what == 'gold') {
		$res = getsetting('dgmaxgoldtransfer',400) * $lvl;
	}
	else {
		$res = getsetting('dgmaxgemstransfer',5);
	}

	$res = round(dg_calc_boni($gid,'transfers_out',$res));

	$res = max(0 , $res - $user[$what.'in']);

	return( round($res) );

}

/**
 * Erledigt Transferhandlungen Spieler <-> Gildenschatz.
 * Prüft dafür auch auf Limits. Abspeichern der Gilde nach Funktionsausführung nicht vergessen!
 *
 * @param int ID der betroffenen Gilde
 * @param int Zu transferierender Betrag. > 0 -> Einzahlung, < 0 Auszahlung
 * @param string Zu transferierende Währung: 'gold' oder 'gems'
 * @param int ID des betroffenen Accounts. (Optional, Standard 0 = Spieler)
 * @param array Daten (level, gold, gems) des betroffenen Accounts, ansonsten werden diese aus DB abgerufen bzw. aus session['user'] bezogen.
 * @return int Transferierte Summe
 */
function dg_transfer ($gid,$val,$what,$acctid=0,$data=array()) {

	global $session;

	if($val == 0) {
		return(0);
	}

	$acctid = (int)$acctid;
	$acctid = ($acctid == 0 ? $session['user']['acctid'] : $acctid);

	$guild = &dg_load_guild($gid,array('gold','gems','transfers','gold_in','gems_in'));

	$what = ($what == 'gold' ? 'gold' : 'gems');

	// Spielerdaten, falls nicht gegeben
	if(!isset($data[$what]) || !isset($data['level'])) {
		if($int_id == $session['user']['acctid']) {
			$data['level'] = $session['user']['level'];
			$data['gold'] = $session['user']['gold'];
			$data['gems'] = $session['user']['gems'];
		}
		else {
			$res = db_query('SELECT level,gold,gems FROM accounts WHERE acctid='.$acctid);
			if(!db_num_rows($res)) {
				echo('Spieler '.$acctid.' NICHT gefunden!');
				return (0);
			}
			$data = db_fetch_assoc($res);
		}
	}

	if($val > 0) {	// Einzahlung
		$max_count = dg_calc_max_transfer_in($gid,$what,$acctid,$data['level']);

		// Begrenzen durch vorhandene Summen
		$max_count = min($data[$what],$max_count);

		$val = min($max_count,$val);

		$guild[$what] += $val;
		if($acctid == $session['user']['acctid']) {
			$session['user'][$what] -= $val;
		}
		else {
			user_update(
				array
				(
					$what=>array('sql'=>true,'value'=>$what.'-'.$val),
				),
				$acctid
			);
		}

		$guild[$what.'_in'] += $val;

		// Transferliste updaten
		$guild['transfers'][$acctid][$what.'_in'] += $val;

	}
	else {	// Auszahlung
		$val *= -1;
		$max_count = dg_calc_max_transfer_out($gid,$what,$acctid,$data['level']);

		// Begrenzen durch vorhandene Summen
		$max_count = min($guild[$what],$max_count);

		$val = min($max_count,$val);

		$guild[$what] -= $val;
		if($acctid == $session['user']['acctid']) {
			$session['user'][$what] += $val;
		}
		else {

			user_update(
				array
				(
					$what=>array('sql'=>true,'value'=>$what.'+'.$val),
				),
				$acctid
			);
		}

		// Transferliste updaten
		$guild['transfers'][$acctid][$what.'_out'] += $val;

		$sql = 'UPDATE account_extra_info SET '.$what.'in='.$what.'in+'.$val.' WHERE acctid='.$acctid;
		$res = db_query($sql);
	}

	return($val);

}

// Gibt Array ('gold','gems') mit eingezahlten Mengen zurück
function dg_member_tribute ($gid,$gold,$gems,$transfer=true) {

	global $session;

	$percent = getsetting('dgminmembertribute',4);

	$g = &dg_load_guild($gid,array('state','gold','gems','transfers','build_list','type','gold_tribute','gems_tribute'));

	// Wenn Gilde inaktiv
	if(DG_STATE_INACTIVE == $g['state']) {
		return(array(0,0));
	}

	// DurchschnittsAusbaulvl zählen
	$lvl_avg = ceil(dg_get_avg_build($gid) * 2);

	$percent = min( $percent + $lvl_avg, 24);
	$percent *= 0.01;

	if(!$transfer) {return($percent*100);}

	$gold = dg_calc_boni( $gid,'member_tribute',round($gold*$percent,0) );
	$gems = dg_calc_boni( $gid,'member_tribute',round($gems*$percent,0) );

	// Häfte geht weg als .. 'Steuer' ; )
	$g['transfers'][$session['user']['acctid']]['gold_in'] += round($gold * 0.5);
	$g['transfers'][$session['user']['acctid']]['gems_in'] += round($gems * 0.5);
	$g['gold'] += round($gold * 0.5);
	$g['gems'] += round($gems * 0.5);

	// DEBUG
	$g['gold_tribute'] += round($gold * 0.5);
	$g['gems_tribute'] += round($gems * 0.5);
	// END DEBUG

	return(array($gold,$gems));
}

function &dg_calc_tax ($gid) {

	$guild = dg_load_guild($gid,array('build_list','taxdays','type','taxfree_allowed','regalia','gold','gems'));

	$taxcost_gold = (int)getsetting('dgtaxgold',5000);
	$taxcost_gems = (int)getsetting('dgtaxgems',5);
	$taxdays = (int)getsetting('dgtaxdays',12);

	$tax_not_paid = floor( $g['taxdays'] / $taxdays);

	$nr = max(dg_count_guild_members($gid)-1,1);
	$build_lvl_avg = ceil( dg_get_avg_build($gid) * $nr * 0.2 );
	$gold_per = round( $guild['gold'] * 0.005 );
	$gems_per = round( $guild['gems'] * 0.005 );

	$tax = array();

	$tax['gold'] =$gold_per + max( (($tax_not_paid) * 0.1), 1) * $taxcost_gold * (1 + $build_lvl_avg*0.005) * (1 + ($nr * $nr) * 0.03 );
	$tax['gems'] =$gems_per + max( (($tax_not_paid) * 0.1), 1) * $taxcost_gems * (1 + $build_lvl_avg*0.005) * (1 + ($nr * $nr) * 0.03 );

	// Noch Boni durch Ausbauten und Typ reinbringen
	$tax['gold'] = round(dg_calc_boni($gid,'tax',$tax['gold'])) * ($guild['taxfree_allowed']?0:1);
	$tax['gems'] = round(dg_calc_boni($gid,'tax',$tax['gems'])) * ($guild['taxfree_allowed']?0:1);

	return($tax);

}

// Funktion überprüft $gid Gilde auf letztes spielerabhängiges Update und verrichtet dieses, falls nötig
//
function dg_player_update ($gid) {

	global $dg_builds,$dg_points,$session;

	$gameday_length = (int)getsetting('daysperday',12);
	$taxdays = (int)getsetting('dgtaxdays',12);

	$gameday_length = 24 / $gameday_length;

	// Darf nur einmal gleichzeitg ausgeführt werden - verwende Slayers Semaphore-Code
	$int_handle = enter_mutex_code();

	// Neuladen garantieren
	unset($GLOBALS['session']['guilds'][$gid]);

	$g = &dg_load_guild($gid);

	// Wenn Update nötig
	if(time()-strtotime($g['lastupdate']) < $gameday_length*3600 || $g['state'] != DG_STATE_ACTIVE) {
		// Semaphor freigeben (passiert eigentl. automatisch am Ende des Requests, aber grade bei Newday kann da ja noch ne Menge passieren)
		exit_mutex_code($int_handle);
		return(false);
	}
		
	$g['immune_days'] = $g['immune_days'] > 0 ? $g['immune_days']-1 : 0;

	// Wenn keine Topgilde und noch keine Insignie, verpassen wir ihr einen Insigniensplitter
	if($g['top_repu'] == 0 && $g['regalia'] == 0) {

		if(e_rand(1,25) == 1) {

			if(item_count(' tpl_id="insgnteil" AND deposit1='.$gid.' AND owner='.ITEM_OWNER_GUILD) == 0) {
				dg_commentary($gid,'/msg`8Ein unbekannter, maskierter Bote sprengt in eiligem Ritt am Gildentor vorbei. Die Augen seines Pferdes scheinen zu leuchten. Kaum ist das Getrappel der Hufe verhallt, entdeckt ihr einen Beutel mit einem Insigniensplitter!','',1);
				item_add(ITEM_OWNER_GUILD,'insgnteil',array('deposit1'=>$gid));
			}

		}

	}

	// In letzter Zeit erfolgte Angriffe langsam verrechnen
	$g['fights_suffered_period'] = max($g['fights_suffered_period']-1,0);

	if($g['war_target']) {
		if($dg_points['war_round'] > $g['points']) {	// Gilde kann sich Krieg nicht mehr leisten, beenden
			$g['war_target'] = 0;
		}
		else {
			$g['points']-=$dg_points['war_round'];
		}
	}

	// Wenn nur Frieden, GP
	/*$peace = true;

	if($g['war_target'] != 0) {$peace=false;}
	else {

		$sql = 'SELECT guildid FROM dg_guilds WHERE guildid!='.$gid.' AND state = '.DG_STATE_ACTIVE;
		$res = db_query($sql);

		if(db_num_rows($res)) {
			while($tg = db_fetch_assoc($res)) {
				$tr = $g['treaties'][$tg['guildid']];
				$t = ($tr[1] ? 0 : $tr[0]);

				if($t != DG_TREATY_WAR_OTHER && $t != DG_TREATY_PEACE_SELF && $t != DG_TREATY_PEACE_OTHER) {
					$peace = false;
					break;
				}
			}
		}
		else {$peace = false;}
	}

	db_free_result($res);*/

	$g['taxdays']++;
	$g['lastupdate'] = date('Y-m-d H:i:s');

	// Ausbau weiterbauen
	if($g['build_list'][0][0]) {
		$g['build_list'][0][1]--;

		if($g['build_list'][0][1] <= 0) {	// Ausbau vollendet

			$type = $g['build_list'][0][0];
			$g['build_list'][$type] = min($g['build_list'][$type]+1,DG_BUILD_MAX_LVL);
			$g['build_list'][0][0] = 0;

			dg_massmail($gid,'Ausbau fertiggestellt!','Deine Gilde hat den Ausbau '.$dg_builds[$type]['name'].' fertiggestellt!');
			dg_log('Ausbau '.$dg_builds[$type]['name'].' auf Lvl '.$g['build_list'][$type].' fertig!');

			dg_addnews('`@Die Gilde '.$g['name'].'`@ hat soeben ihren Ausbau fertiggestellt!');

			addhistory('`2Erweiterung des Ausbaus '.$dg_builds[$type]['name'].' fertiggestellt',2,$g['guildid']);

		}
	}
	// END Ausbau

	$tax_not_paid = floor( $g['taxdays'] / $taxdays);

	if( ($g['taxdays'] % $taxdays) == 0 ) {

		$tax = &dg_calc_tax($gid);

		if($tax['gold'] > 0 || $tax['gems'] > 0) {

			if($g['gold'] <= $tax['gold'] || $g['gems'] <= $tax['gems']) {

				dg_log('Steuern ('.$tax['gold'].' Gold, '.$tax['gems'].' Gems) zum '.$tax_not_paid.'. Mal nicht bezahlt. Gold: '.$g['gold'].', Gems: '.$g['gems']);

				$g['reputation'] = max($g['reputation']-4,0);

				if($tax_not_paid == 2) {

					// Weitesten Ausbau um eine Stufe zurücksetzen
					$best_building = dg_get_max_build($g['guildid']);

					if($best_building) {
						$building = $dg_builds[$best_building]['name'];
						$g['build_list'][$best_building]--;
						dg_log(' wurde wegen Steuerhinterziehung der Ausbau '.$building.' um eine Stufe zurückgesetzt');
						dg_commentary($g['guildid'],'/msg Der Steuereintreiber pfändete den Ausbau '.$building.' und warf ihn dadurch um eine Stufe zurück!','',1);
						dg_massmail($g['guildid'],'Ausbau zurückgestuft!','Da deine Gilde zum 2. Mal hintereinander ihre Steuern nicht bezahlen konnte, wurde der Ausbau '.$building.' gepfändet und um eine Stufe zurückgesetzt!',202);
						dg_addnews('`&Der Gilde '.$g['name'].'`& wurde wegen Steuerhinterziehung ein Ausbau gepfändet!');
					}
					else { 	// Schwein gehabt
						dg_log(' wurde wegen Steuerhinterziehung KEIN Ausbau um eine Stufe zurückgesetzt');
					}

				}

				if($tax_not_paid > 2) {

					dg_massmail($g['guildid'],'`4Gilde aufgelöst!','`4Da deine Gilde zum 3. Mal hintereinander ihre Steuern nicht bezahlen konnte, wurde sie aufgelöst!');
					dg_addnews('`4Die Gilde '.$g['name'].'`4 wurde wegen massiver Steuerhinterziehung aufgelöst!');
					dg_delete_guild($g['guildid']);
					// Semaphor freigeben (passiert eigentl. automatisch am Ende des Requests, aber grade bei Newday kann da ja noch ne Menge passieren)
					exit_mutex_code($int_handle);
					return;

				}


			}	// END keine Bezahlung
			else {

				$g['gold'] -= $tax['gold'];
				$g['gems'] -= $tax['gems'];

				// DEBUG
				$g['gold_tax'] += $tax['gold'];
				$g['gems_tax'] += $tax['gems'];
				// END DEBUG

				dg_log('bezahlte '.$tax['gold'].' Gold und '.$tax['gems'].' Edelsteine als Steuern');
				dg_commentary($g['guildid'],'/msg`2 Der Steuereintreiber hat durch einen beherzten Griff in die Kasse die geforderten `^'.$tax['gold'].' Gold`2 und `^'.$tax['gems'].' Edelsteine`2 mitgenommen!','treasure',1);
				$g['taxdays'] = 0;

			}
		}	// END if tax > 0

	}	// END Steuern fällig

	dg_save_guild();
	dg_log('Player-Update (acctid '.$session['user']['acctid'].'): taxdays='.$g['taxdays']);

	// END Semaphore
	exit_mutex_code($int_handle);

}

function dg_update_guilds () {

	global $session;

	$int_regalia_sold = 0;

	// Alle Gilden laden
	dg_load_guild(0,array('build_list','gold','gems','type','treaties','regalia','war_target','guard_hp','guard_hp_before','points','gold_in','gems_in','regalia_sold','name','reputation','top_repu','state'),false,'RAND()');

	if(count($session['guilds']) == 0) {return;}

	$min_gold = getsetting('dgguildfoundgold',100000);
	$min_gems = getsetting('dgguildfoundgems',100);

	// König-Tage senken
	$int_king_days = (int)getsetting('dgkingdays',30);
	$int_king_days--;
	$bool_king = false;
	if($int_king_days <= 0) {
		$bool_king = true;
		$int_king_days = e_rand(33,38);

		// Insignienpreis ermitteln
		$int_regalia_sold = 0;
		$int_guildcount = 0;
		foreach($session['guilds'] as $g) {

			if($g['state'] != DG_STATE_ACTIVE) {
				continue;
			}

			$int_guildcount++;

			// extremwerte (alles > 10) besonders gewichten
			$int_regalia_sold += $g['regalia'] + max(0,$g['regalia']-10);
		}

		// Neuen Insignienpreis abspeichern
		if($int_regalia_sold > 0) {

			// Max. + Min. Preis für Insignien
			$int_max_val = getsetting('dgmaxregaliaval',18);
			$int_min_val = getsetting('dgminregaliaval',2);

			// Insignien pro Gilde verkauft: Dabei auf 0.5 runden
			$float_soldfactor = round(($int_regalia_sold / $int_guildcount) * 2) / 2;

			switch($float_soldfactor) {
				case 9.5:$int_regalia_value=max($int_max_val-19,$int_min_val);break;
				case 9.0:$int_regalia_value=max($int_max_val-18,$int_min_val);break;
				case 8.5:$int_regalia_value=max($int_max_val-17,$int_min_val);break;
				case 8.0:$int_regalia_value=max($int_max_val-16,$int_min_val);break;
				case 7.5:$int_regalia_value=max($int_max_val-15,$int_min_val);break;
				case 7.0:$int_regalia_value=max($int_max_val-14,$int_min_val);break;
				case 6.5:$int_regalia_value=max($int_max_val-13,$int_min_val);break;
				case 6.0:$int_regalia_value=max($int_max_val-12,$int_min_val);break;
				case 5.5:$int_regalia_value=max($int_max_val-11,$int_min_val);break;
				case 5.0:$int_regalia_value=max($int_max_val-10,$int_min_val);break;
				case 4.5:$int_regalia_value=max($int_max_val-9,$int_min_val);break;
				case 4.0:$int_regalia_value=max($int_max_val-8,$int_min_val);break;
				case 3.5:$int_regalia_value=max($int_max_val-7,$int_min_val);break;
				case 3.0:$int_regalia_value=max($int_max_val-6,$int_min_val);break;
				case 2.5:$int_regalia_value=max($int_max_val-5,$int_min_val);break;
				case 2.0:$int_regalia_value=max($int_max_val-4,$int_min_val);break;
				case 1.5:$int_regalia_value=max($int_max_val-3,$int_min_val);break;
				case 1.0:$int_regalia_value=max($int_max_val-2,$int_min_val);break;
				case 0.5:$int_regalia_value=max($int_max_val-1,$int_min_val);break;
				case 0.0:$int_regalia_value=$int_max_val;break;
				default:$int_regalia_value=$int_min_val;break;
			}

			$int_regalia_value = max($int_min_val,$int_regalia_value-e_rand(0,2));

		}
		else {
			$int_regalia_value = 30;
		}
		savesetting('dgregaliaprice',$int_regalia_value);
		savesetting('dglastking',date('Y-m-d H:i:s'));

	}
	savesetting('dgkingdays' , $int_king_days );

	// Gilden

	foreach($session['guilds'] as $gid=>$g) {
		$session['guilds'][$gid]['war_target'] = 0;
		$session['guilds'][$gid]['fights_suffered'] = 0;

		$session['guilds'][$gid]['gold_in'] = 0;
		$session['guilds'][$gid]['gems_in'] = 0;

		if($g['state'] == DG_STATE_INACTIVE) {
			continue;
		}

		// Gilden im Bau freischalten
		if($g['state'] == DG_STATE_IN_PROGRESS) {

			if($g['gold'] >= $min_gold && $g['gems'] >= $min_gems) {

				// Valide?
				if(dg_count_guild_members($gid) >= getsetting('dgminmembers',3) &&
					 db_num_rows(db_query('SELECT acctid FROM accounts WHERE guildid='.$gid.' AND guildfunc='.DG_FUNC_LEADER))) {

					// Freischalten!
					$session['guilds'][$gid]['gold'] -= $min_gold;
					$session['guilds'][$gid]['gems'] -= $min_gems;
					$session['guilds'][$gid]['state'] = DG_STATE_ACTIVE;

					dg_massmail($gid,'`@Gilde fertiggestellt!','Deine Gilde ist nun offiziell freigegeben. Damit seid ihr ein vollwertiger Bestandteil der Gildengemeinschaft von '.getsetting('townname','Atrahor').'.`n`nHerzlichen Glückwunsch!');
				}

			}

			// Sonst gibt es hier nichts zu tun
			continue;

		}


		// König schaut vorbei, GP berechnen!
		if($bool_king) {

			// Bei Ansehen > Durchschnitt: langsam absinken lassen
			if($g['reputation'] > 50) {
				$int_loss = 5;
				if($g['reputation'] > 100) {
					$int_loss += e_rand(1,round($g['reputation'] * 0.1));
				}
				$session['guilds'][$gid]['reputation'] = max($g['reputation']-($g['top_repu'] ? $g['top_repu']+1 : $int_loss),50);
			}

			$int_points = 0;
			$int_points_com = 0;

			// Experimenteller Ansatz, GP für Kommentare
			if(getsetting('dggetcompoints',0)) {
				$arr_member_list = dg_load_member_list($gid);
				$str_authors = '-1,'.implode(',',$arr_member_list);
				$str_mindate = getsetting('dglastking',date('Y-m-d H:i:s'));

				$sql = 'SELECT COUNT(*) AS anzahl FROM commentary WHERE
						author IN ('.$str_authors.') AND
						self = 1 AND
						(section = "village" OR section = "marketplace" OR section LIKE "%guild%") AND
						postdate > "'.$str_mindate.'"';
				$arr_count = db_fetch_assoc(db_query($sql));

				if($arr_count['anzahl'] > 0) {

					$int_points_com = $arr_count['anzahl'] / sizeof($arr_member_list);

				}
				$int_points += $int_points_com;
			}

			if($g['reputation'] < 20) {
				$float_regalia_mod = 0.8;
			}
			else if($g['reputation'] < 50) {
				$float_regalia_mod = 0.9;
			}
			else if($g['reputation'] > 90) {
				$float_regalia_mod = 1.1;
			}
			else {
				$float_regalia_mod = 1;
			}

			if($session['guilds'][$gid]['top_repu']) {

				$float_regalia_mod = 1.2;

			}

			$session['guilds'][$gid]['top_repu'] = 0;

			$int_regalia_price_ges = round($session['guilds'][$gid]['regalia'] * $int_regalia_value * $float_regalia_mod);

			if($int_regalia_price_ges > 0) {
				dg_commentary($gid,'/msg`8Die Paladine des Königs erwerben `^'.$session['guilds'][$gid]['regalia'].'`8 Insignien der Gilde gegen `^'.$int_regalia_price_ges.'`8 Gildenpunkte.','',1);

				$int_points += $int_regalia_price_ges;

				$session['guilds'][$gid]['regalia_sold'] += $session['guilds'][$gid]['regalia'];
				$session['guilds'][$gid]['reputation'] += $session['guilds'][$gid]['regalia'];

				// Gilde mit höchstem Ansehen ermitteln
				if($int_maxrep < $session['guilds'][$gid]['reputation']) {

					$int_gid_maxrep = $gid;
					$int_maxrep = $session['guilds'][$gid]['reputation'];
					$int_maxrep_regalia = $session['guilds'][$gid]['regalia'];

				}

				$session['guilds'][$gid]['regalia'] = 0;

			}

			$session['guilds'][$gid]['points'] += $int_points;

			dg_log('Erhielt '.$int_points.' Pkt, davon '.$int_points_com.' für Kommentare; Gab '.$g['regalia'].' Insignien; Neues Ansehen: ' . $session['guilds'][$gid]['reputation'] . '.',$gid);

		}	// END bool_king

		// Krieg
		if($session['guilds'][$gid]['guard_hp_before'] > 0) {
			// Wenn Hälfte der vorherigen Wachen mehr ist als aktueller Stand
			$int_guards_to_replace = dg_calc_boni($gid,'guard_hp_before',0);
			$session['guilds'][$gid]['guard_hp'] = max($int_guards_to_replace,$session['guilds'][$gid]['guard_hp']);
		}
		$session['guilds'][$gid]['guard_hp_before'] = 0;

		// Beenden
		$session['guilds'][$gid]['war_target'] = 0;

		// Überzählige Wachen gehen nach Hause ; )
		$int_max_guards = dg_calc_boni($gid,'maxguards',0);
		$int_guards_toomuch = $g['guard_hp'] - $int_max_guards;
		if($int_guards_toomuch > 0) {

			$session['guilds'][$gid]['guard_hp'] = $int_max_guards;
			dg_commentary($gid,'/msg`8'.$int_guards_toomuch.' Gildensöldner '.($int_guards_toomuch == 1 ? 'verlässt':'verlassen').' das Gildenhaus mit geschulterten Waffen! Scheinbar reichen die Unterkünfte nicht, um allen Unterschlupf zu gewähren.','',1);

		}

		// Verträge aufräumen
		if(is_array($g['treaties'])) {
			foreach($g['treaties'] as $target=>$t) {
				if( !is_array($session['guilds'][$target]) ) {
					unset($session['guilds'][$gid]['treaties'][$target]);
				}
			}
		}

	}

	if($bool_king) {

		if($int_gid_maxrep) {
			dg_commentary($int_gid_maxrep,'/msg`8Für ihre besonderen Verdienste verleiht der König den Mitgliedern dieser Gilde zeitweilig besondere Privilegien!','',1);

			$session['guilds'][$int_gid_maxrep]['top_repu'] = $int_maxrep_regalia;
			savesetting('dgtopguild',($session['guilds'][$int_gid_maxrep]['name']));
		}
		else {
			savesetting('dgtopguild','');
		}

	}

	// ... und abspeichern
	dg_save_guild();

}

// $func: entsprechend der DG_FUNC-Werte, zusätzlich: 0: an alle, 200: an das gesamte Team, 201: an alle bis auf Bewerber (Standard), 202: nur an Führung
// Vars: {name} wird durch Mitgliedsnamen ersetzt
function dg_massmail ($gid,$subj,$txt,$func=201) {

	global $session;

	$funcs = '';
	if($func > 0) {

		if($func == 201) {
			$funcs = ' AND guildfunc!='.DG_FUNC_APPLICANT;
		}
		elseif($func == 200) {
			$funcs = ' AND (guildfunc='.DG_FUNC_WAR.' OR guildfunc='.DG_FUNC_TREASURE.' OR guildfunc='.DG_FUNC_MEMBERS.' OR guildfunc='.DG_FUNC_LEADER.')';
		}
		elseif($func == 202) {
			$funcs = ' AND (guildfunc='.DG_FUNC_LEADER.')';
		}

	}

	$sql = 'SELECT acctid,name,guildfunc FROM accounts WHERE guildid='.$gid.$funcs;
	$res = db_query($sql);

	while($m = db_fetch_assoc($res)) {

		//if($m != $session['user']['acctid']) {
			$subj = str_replace('{name}',$m['name'],$subj);
			$txt = str_replace('{name}',$m['name'],$txt);
			systemmail($m['acctid'],$subj,$txt);

		//}

	}

}

function dg_load_member_list ($gid) {

	global $session;

	if(is_array($session['guilds'][$gid]['memberlist'])) {return(true);}

	$session['guilds'][$gid]['memberlist'] = array();

	$sql = "SELECT acctid FROM accounts WHERE guildid=".$gid;
	$res = db_query($sql);

	if(db_num_rows($res) == 0) {return(false);}

	while($m = db_fetch_assoc($res)) {

		$session['guilds'][$gid]['memberlist'][$m['acctid']] = $m['acctid'];

	}

	db_free_result($res);

	return($session['guilds'][$gid]['memberlist']);

}

function dg_add_member ($gid,$acctid,$apply=false) {

	global $session,$dg_default_ranks;

	$func = ($apply) ? DG_FUNC_APPLICANT : DG_FUNC_MEMBER;
	$rank = count($dg_default_ranks);

	if($acctid == $session['user']['acctid']) {

		$session['user']['guildid'] = $gid;
		$session['user']['guildrank'] = $rank;
		$session['user']['guildfunc'] = $func;

	}
	else {

		user_update(
			array
			(
				'guildid'=>$gid,
				'guildrank'=>$rank,
				'guildfunc'=>$func
			),
			$acctid
		);

	}

}

function dg_commentary ($gid,$msg,$part='',$acctid=0) {

	global $session;

	$acctid = ($acctid == 0) ? $session['user']['acctid'] : (int)$acctid;

	$section = 'guild-'.$gid.(($part)?'_'.$part:'');

	$sql = 'INSERT INTO commentary SET author='.$acctid.',comment="'.db_real_escape_string($msg).'",postdate=NOW(),section="'.$section.'"';
	db_query($sql);

}

/**
 * Entfernt Spieler aus Gilde
 *
 * @param int ID der Gilde
 * @param int AccountID
 * @param bool Als Bewerber oder vollwertiges Mitglied entfernen (Wartezeit nein / ja)
 */
function dg_remove_member ($gid,$acctid,$apply=false) {

	global $session;

	$guildfunc = ($apply ? 0 : DG_FUNC_CANCELLED);
	$guildrank = ($apply ? 0 : 10);

	if($acctid == $session['user']['acctid']) {

		$session['user']['guildid'] = 0;
		$session['user']['guildrank'] = $guildrank;
		$session['user']['guildfunc'] = $guildfunc;

	}
	else {

		user_update(
			array
			(
				'guildid'=>0,
				'guildrank'=>$guildrank,
				'guildfunc'=>$guildfunc
			),
			$acctid
		);

	}

	// Einladungen löschen
	item_delete('tpl_id="gldprive" AND owner='.$acctid);

	// Stimmen löschen
	require_once(LIB_PATH.'board.lib.php');
	poll_delete(0, 'guild'.$gid.'_hall', $acctid, true);
	poll_delete(0, 'guild'.$gid.'_private', $acctid, true);

}

function dg_save_guild () {

	global $dg_session_copy,$session;

	if(!is_array($session['guilds']))
	{
		return;
	}

	foreach($session['guilds'] as $id=>$g) {

		$sql = "UPDATE dg_guilds SET ";

		foreach($g as $k=>$v) {

			if( ( $v != $dg_session_copy[$id][$k] && isset($dg_session_copy[$id][$k]) ) || !isset($dg_session_copy[$id][$k]) && $k!='memberlist' && $k!='ptype' ) {

				if (is_array($v)){
					$sql.= $k."='".db_real_escape_string(utf8_serialize($v))."', ";
				}
				else{
					$sql.= $k."='".db_real_escape_string(stripslashes($v))."', ";
				}
			}

		}

		if(mb_strlen($sql) > 24) {

			$sql = mb_substr($sql,0,mb_strlen($sql)-2);
			$sql .= ' WHERE guildid='.(int)$id;

			db_query($sql);

		}

	}

}

function dg_build_is_allowed ($gid,$build_id) {

	global $dg_builds;

	$b = &$dg_builds[$build_id];

	$g = &dg_load_guild($gid,array('type'));

	if(!in_array($g['ptype'],$b['forbidden_types']) && ($b['forbidden_types'] !== true || in_array($g['ptype'],$b['special_types'])) ) {return(true);}

	return(false);

}

function dg_get_avg_build ($gid) {

	$guild = &dg_load_guild($gid,array('build_list'));
	$lvl_ges = 0;
	$counter = 0;
	if(is_array($guild['build_list'])) {
		foreach($guild['build_list'] as $k=>$lvl) {
			if($k>0 && $lvl>0) {
				$lvl_ges += $lvl;
				$counter++;
			}
		}
	}

	if($counter > 0) {
		$lvl_ges /= $counter;
	}

	return($lvl_ges);

}

function dg_get_ges_build ($gid) {

	$guild = &dg_load_guild($gid,array('build_list'));
	$lvl_ges = 0;
	if(is_array($guild['build_list'])) {
		foreach($guild['build_list'] as $k=>$lvl) {
			if($k>0) {
				$lvl_ges += $lvl;
			}
		}
	}

	return($lvl_ges);

}

function dg_get_max_build ($gid) {

	$guild = &dg_load_guild($gid,array('build_list'));

	$best_building_lvl = 0;
	$best_building = 0;
	if(is_array($guild['build_list'])) {
		foreach($guild['build_list'] as $b=>$lvl) {
			if($lvl > $best_building_lvl && $b>0) {
				$best_building = $b;
				$best_building_lvl = $lvl;
			}
		}
	}

	return($best_building);

}

// $g_type: Gildentyp
// $b_type: Ausbau
// $b_lvl: AKTUELLER Ausbaulvl
function &dg_get_build_cost ($g_type,$b_type,$b_lvl) {

	global $dg_builds;

	$gold_mod = 2;
	$gems_mod = 2;
	$days_mod = 3;
	$gp_mod = 1;

	if( is_array($dg_builds[$k]['special_types']) ) {
		if( !in_array($g_type,$dg_builds[$b_type]['special_types']) ) {
			$gold_mod = 2;
			$gems_mod = 2;
			$gp_mod = 1.1;
			$days_mod = 3;
		}
	}

	$int_gp_factor = ($dg_builds[$b_type]['gpcost'] ? $dg_builds[$b_type]['gpcost'] : 100);

	$costs = array (
					'gold' => round($dg_builds[$b_type]['goldcost'] * $gold_mod),
					'gems' => round($dg_builds[$b_type]['gemcost'] * $gems_mod),
					'gp' => round(($b_lvl+1) * ($int_gp_factor) * $gp_mod),
					'days' => round(($b_lvl+2) * 4 * $days_mod)
					);

	return($costs);

}

function dg_build ($gid, $type) {
	global $session;

	$guild = &dg_load_guild($gid,array('build_list','type'));
	$costs = &dg_get_build_cost($guild['ptype'],$type,$guild['build_list'][$type]);

	$guild['build_list'][0][0] = $type;
	$guild['build_list'][0][1] = $costs['days'];
	$guild['points_spent'] += $costs['gp'];
	$guild['points'] -= $costs['gp'];
	$guild['gold'] -= $costs['gold'];
	$guild['gems'] -= $costs['gems'];

	dg_log('Ausbau '.$type.' gestartet für '.$costs['gp'].' GP');

}

function dg_addnews ($msg,$acctid=0,$gid=0) {
	global $session;
	$gid = ($gid==0) ? $session['user']['guildid']:$gid;
	$sql = 'INSERT INTO news SET newstext="'.db_real_escape_string($msg).'",newsdate=NOW(),guildid='.$gid.',accountid='.$acctid;
	db_query($sql);
}

// Erledigt bestimmte Boni, die der Spieler am neuen Tag bekommt
function dg_player_boni ($gid) {
	global $session;

	$g = &dg_load_guild($gid,array('lastupdate','taxdays','gold','gems','build_list','treaties','points','state','name','type','taxfree_allowed','top_repu'));

	$session['user']['castleturns'] = dg_calc_boni($gid,'player_castleturns',$session['user']['castleturns']);
	$session['user']['turns'] = dg_calc_boni($gid,'player_turns',$session['user']['turns']);

	if($g['top_repu'] && e_rand(1,6) == 1) {

		switch(e_rand(1,5)) {

			case 1:
			case 2:

				// Paladinbuff
				$str_msg = 'Seid gegrüßt, '.$session['user']['login'].'!`n
							Mein edler Herr sendet Euch als Dank für die Dienste Eurer Gilde eine Leibwache zur Begleitung in gefahrvollen Kämpfen.
							`n`nGez. Tualon von Augenstein, Hauptmann der königlichen Paladingarde '.getsetting('townname','Atrahor').'s';

				$arr_paladin_buff = array('name'=>'`^Paladin des Königs`0',
									'miniouncount'=>1,
									'minbadguydmg'=>$session['user']['level']*2,
									'maxbadguydmg'=>$session['user']['level']*6,
									'wearoff'=>'Der `^Paladin`0 zieht sich erschöpft zurück.',
									'effectmsg'=>'Mit wütendem Eifer drischt der `^Paladin`0 auf deinen Gegner ein!',
									'rounds'=>50,
									'activate'=>'roundstart,offense,defense'
									);

				systemmail($session['user']['acctid'],'`8Nachricht des Königs',$str_msg);

				$session['bufflist']['paladin'] = $arr_paladin_buff;

			break;

			case 3:
			case 4:
			case 5:

				// Futter für das Vieh
				$str_msg = 'Seid gegrüßt, '.$session['user']['login'].'!`n
							Mein edler Herr sendet Euch als Dank für die Dienste Eurer Gilde besonders gehaltvolles Futter für Euren tierischen Begleiter.
							`n`nGez. Tualon von Augenstein, Hauptmann der königlichen Paladingarde '.getsetting('townname','Atrahor').'s';

				systemmail($session['user']['acctid'],'`8Nachricht des Königs',$str_msg);

				item_add($session['user']['acctid'],'mountfttr');

			break;


		}

	}

}

/**
 * Zeigt in der Gildenbibliothek eine Tabelle mit den Büchern und zusätzlichen Informationen an
 *
 * @param int $gid ID der Gilde
 * @param int $theme Themenbereich des Buches
 */
function dg_show_books ($gid,$theme = 0) {
	global $guild;

	switch ($_GET['act']) {

		case 'book_goon':

			global $Char;
			$sql = "SELECT bookid,title,activated,su_activated,theme FROM `dg_books` WHERE guildid = $gid AND acctid = ".$Char->acctid." ORDER BY theme ASC,title ASC";
			$result = db_query($sql);

			if (db_num_rows($result)) {
				$output = "<table>
				<tr>
					<td width=200>`&`bBuchtitel`b:`n</td><td>`&`bStatus`b:`n</td>
				</tr>";

				$int_theme = -1;
				$arr_book_ids = array();
				while ($row = db_fetch_assoc($result)) {
					if($row['theme'] != $int_theme)
					{
						$int_theme = $row['theme'];
						$output .= "
							<tr>
								<td colspan='2'><br /><u>`b".$guild['building_vars']['bibliothek']['dg_book_themes'][$int_theme].":`b</u>`0</td>
							</tr>";
					}
					$output .= "<tr><td><a href='dg_main.php?op=in&subop=buildings&building_op=library&act=book_goon2&bookid=" . $row['bookid'] . "'>" . $row['title'] . "</a></td><td>";
					if ($row['activated']!=0) {
						$output .= "`8freigegeben";
					}
					else {
						$output .= "`8(noch) nicht angenommen";
					}

					if ($row['su_activated']==1) {
						$output .= jslib_hint(' `@(!)`0','Dieses Buch wurde von der Administration freigegeben');
					}
					elseif ($row['su_activated']==2)
					{
						$output .= jslib_hint(' `$(!)`0','Dieses Buch wurde von der Administration gesperrt');
					}
					else
					{
						$output .= jslib_hint(' `^(!)`0','Dieses Buch wurde von der Administration noch nicht freigegeben');
					}

					$output .= '</td></tr>';
					$arr_book_ids[] = $row['bookid'];
				}
				$str_book_ids = implode('|',$arr_book_ids);
				addpregnav('/dg_main.php\?op=in&subop=buildings&building_op=library&act=book_goon2&bookid=('.$str_book_ids.')/');
				$output .= "</table>";
			}
			else {
				$output = "`8Du hast noch keine Bücher eingereicht die du fortsetzen könntest.";
			}

			break;

		case 'show':

			$sql = "SELECT bookid,title,activated,author,su_activated FROM `dg_books` WHERE guildid = $gid AND theme = '" . $theme . "' AND activated='1' AND su_activated!='-1' ORDER BY title ASC";
			$result = db_query($sql);
			$limit = db_num_rows($result);

			if ($limit > 0) {
				$output = "<table>
				<tr>
					<td width=200>`&`b`cBuchtitel:`c`b`n</td><td>`&`b`cAutor:`c`b`n</td>
				</tr>";
				$arr_book_ids = array();
				while ($row = db_fetch_assoc($result)) {
					$output .= "<tr><td><a href='dg_main.php?op=in&subop=buildings&building_op=library&act=show2&r=1&bookid=" . $row['bookid'] . "'>" . $row['title'] . "</a></td><td>" . $row['author'] . "</td></tr>";
					$arr_book_ids[] = $row['bookid'];
				}
				$output .= "</table>";
				$str_book_ids = implode('|',$arr_book_ids);
				addpregnav('/dg_main.php\?op=in&subop=buildings&building_op=library&act=show2&r=1&bookid=('.$str_book_ids.')/');
			}
			else {
				$output = "`8Es gibt derzeit keine Bücher zu diesem Thema.";
			}

			break;


		case 'edit_books':

			$arr_themes = $guild['building_vars']['bibliothek']['dg_book_themes'];
			$lvl = $guild['build_list'][DG_BUILD_BIBLI];
			$output = '<table>';

			if(!is_array($arr_themes))
			{
				$arr_themes = array();
			}

			$sqlb = "SELECT bookid,title,theme,activated,author,su_activated FROM `dg_books` WHERE guildid = $gid ORDER BY theme ASC";
			$result = db_query($sqlb);

			$arr_book_ids = array();
			$int_theme = -1;
			while ($row = db_fetch_assoc($result)) {

				if($int_theme != $row['theme'])
				{
					$str_theme = is_null_or_empty($row['theme'])||!array_key_exists($row['theme'],$arr_themes)?'Unkategorisiert':$arr_themes[$row['theme']];
					$output .= '<tr><td>`8`bThema ' . $row['theme'] . ': ' . $str_theme . '`b</td><td>`8`b`cAutoren:`c`b</td><td>`8`b`cAktionen:`c`b</td><td>`c`bZusatzinfo`b`c</td></tr>';
					$int_theme = $row['theme'];
				}
				if ($row['su_activated']==1) {
					$admin_decision = jslib_hint(' `@(!)`0','Dieses Buch wurde von der Administration freigegeben');
				}
				elseif ($row['su_activated']==2)
				{
					$admin_decision = jslib_hint(' `$(!)`0','Dieses Buch wurde von der Administration gesperrt');
				}
				else
				{
					$admin_decision = jslib_hint(' `^(!)`0','Dieses Buch wurde von der Administration noch nicht freigegeben');
				}

				$output .='
				<tr>
					<td>' .create_lnk( $row['title'],'dg_main.php?op=in&subop=buildings&building_op=library&act=show2&r=1&bookid=' . $row['bookid']) . '</td>
					<td>' . $row['author'] . '</td>
					<td>
						'.create_lnk('[bearbeiten]','dg_main.php?op=in&subop=buildings&building_op=library&act=edit_book&bookid=' . $row['bookid']).'
						'.create_lnk('[entfernen]','dg_main.php?op=in&subop=buildings&building_op=library&act=submit&subact=del&bookid=' . $row['bookid'],false,false,'Soll das Buch wirklich endgültig gelöscht werden?').'
						'.create_lnk('['.($row['activated']==0?'freischalten':'sperren').']','dg_main.php?op=in&subop=buildings&building_op=library&act=submit&subact=activate_deactivate&bookid=' . $row['bookid']).'
					</td>
					<td>`c'.$admin_decision.'`c</td>
				</tr>';
				$arr_book_ids[] = $row['bookid'];
			}
			$output .= '<tr><td colspan="4">&nbsp;</td></tr>';

			$str_book_ids = implode('|',$arr_book_ids);
			addpregnav('/dg_main.php\?op=in&subop=buildings&building_op=library&act=edit_book&bookid=('.$str_book_ids.')/');
			addpregnav('/dg_main.php\?op=in&subop=buildings&building_op=library&act=submit&subact=(activate_deactivate|del)&bookid=('.$str_book_ids.')/');

			$output .= '</table>';

			break;

	}

	output($output);

}


/**
 * Zeigt ein einzelnes Buch an
 * überprüft NICHT ob das Buch angezeigt werden darf
 *
 * @param int $bookid BuchID
 */
function dg_show_single_book($bookid) {

	$sql = "SELECT * FROM `dg_books` WHERE bookid = ".(int)$bookid;
	$row = db_fetch_assoc(db_query($sql));

	$str_output = '';

	if($row == null)
	{
		$str_output .= '`c`$Dieses Buch existiert leider nicht:'.$bookid.'`0`c';
	}
	else
	{
		dg_show_header('Buch: '.$row['title']);
		$str_output .= 'Dieses Buch wurde verfasst von '.$row['author'].'`n`n';
		$str_output .= ($row['su_activated'] == 2)?'`c`$Dieses Buch wurde von der Administration gesperrt. Das ist nichts schlimmes, fragt bitte per Anfrage nach was der Grund dafür ist, falls ihr noch nicht darauf angeschrieben wurdet.`0`c':
							($row['su_activated'] == 0)?'`((Dieses Buch wurde von der Administration noch nicht geprüft)`0':
							'';
		$str_output .= '<hr />'. str_replace("\n",'`n',$row['txt']);
	}
	output ($str_output);
}

/**
 * Säubert ein in der Bibliothek abgegebenes Buch von ungültigem HTML Code
 *
 * @param string $str_input Eingabestring
 */
function dg_clean_books_html($str_input)
{
	return clean_html(soap(closetags($str_input,'`b`c`i')),true,true,false);
}

?>
