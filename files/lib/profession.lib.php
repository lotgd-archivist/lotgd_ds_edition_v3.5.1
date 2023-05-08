<?php
define('PROF_PRIEST',11);
define('PROF_PRIEST_HEAD',12);
define('PROF_PRIEST_NEW',13);

define('PROF_GUARD',1);
define('PROF_GUARD_HEAD',2);
define('PROF_GUARD_ENT',3);
define('PROF_GUARD_NEW',5);

define('PROF_JUDGE',21);
define('PROF_JUDGE_HEAD',22);
define('PROF_JUDGE_ENT',23);
define('PROF_JUDGE_NEW',25);

define('PROF_TEMPLE_SERVANT_NEW',31);
define('PROF_TEMPLE_SERVANT',32);

define('PROF_DDL_RECRUIT',41);
define('PROF_DDL_CORPORAL',42);
define('PROF_DDL_SERGEANT',43);
define('PROF_DDL_STSERGEANT',44);
define('PROF_DDL_ENSIGN',45);
define('PROF_DDL_LIEUTENANT',46);
define('PROF_DDL_CAPTAIN',47);
define('PROF_DDL_MAJOR',48);
define('PROF_DDL_COLONEL',49);

define('PROF_WITCH',61);
define('PROF_WITCH_HEAD',62);
define('PROF_WITCH_NEW',63);

// Berufe
define('JOB_FARMER',1);
define('JOB_SMITH',2);
define('JOB_ALCHEMIST',3);
define('JOB_MINER',4);
define('JOB_BANKER',5);
define('JOB_GROCER',6);
define('JOB_SMELTER',7);
define('JOB_SHOWMAN',8);



// Erklärung: Schlüssel = Konstante = Wert der Profession-Var
//				Array-Feld0: Männl. Titel
//				Array-Feld1: Weibl. Titel
//				Array-Feld2: Wird öffentlich angezeigt, true oder false
//				Array-Feld3: Farbe

$profs = array  (
				PROF_PRIEST => array('Priester','Priesterin',true,'`7'),
				PROF_PRIEST_NEW => array('Novize','Novizin',false,'`7'),
				PROF_PRIEST_HEAD => array('Hohepriester','Hohepriesterin',true,'`7'),
				PROF_GUARD => array('Stadtwache','Stadtwache',true,'`4'),
				PROF_GUARD_HEAD => array('Hauptmann der Stadtwache','Hauptmann der Stadtwache',true,'`4'),
				PROF_GUARD_ENT => array('Stadtwache (in Entlassung)','Stadtwache (in Entlassung)',true,'`4'),
				PROF_GUARD_ENT => array('Stadtwache (in Bewerbung)','Stadtwache (in Bewerbung)',false,'`4'),
				PROF_JUDGE => array('Richter','Richterin',true,'`4'),
				PROF_JUDGE_HEAD => array('Oberster Richter','Oberste Richterin',true,'`4'),
				PROF_JUDGE_ENT => array('Richter (in Entlassung)','Richterin (in Entlassung)',true,'`4'),
				PROF_JUDGE_NEW => array('Richter (in Bewerbung)','Richterin (in Bewerbung)',false,'`4'),
				PROF_TEMPLE_SERVANT_NEW => array('Tempeldiener (in Bewerbung)','Tempeldienerin (in Bewerbung)',false,'`8'),
				PROF_TEMPLE_SERVANT => array('Tempeldiener','Tempeldienerin',true,'`8'),
				
				PROF_WITCH => array('Hexer','Hexe',true,'`7'),
				PROF_WITCH_NEW => array('Hexenschüler','Hexenschülerin',false,'`7'),
				PROF_WITCH_HEAD => array('Hohepriester der Hexen','Hohepriesterin der Hexen',true,'`7'),
				
				PROF_DDL_RECRUIT => array('Rekrut in der Bürgerwehr','Rekrutin in der Bürgerwehr',true,'`2'),
				PROF_DDL_CORPORAL => array('Corporal in der Bürgerwehr','Corporal in der Bürgerwehr',true,'`2'),
				PROF_DDL_SERGEANT => array('Sergeant in der Bürgerwehr','Sergeant in der Bürgerwehr',true,'`2'),
				PROF_DDL_STSERGEANT => array('Feldwebel in der Bürgerwehr','Feldwebel in der Bürgerwehr',true,'`2'),
				PROF_DDL_ENSIGN => array('Fähnrich in der Bürgerwehr','Fähnrich in der Bürgerwehr',true,'`2'),
				PROF_DDL_LIEUTENANT => array('Leutnant in der Bürgerwehr','Leutnant in der Bürgerwehr',true,'`2'),
				PROF_DDL_CAPTAIN => array('Hauptmann in der Bürgerwehr','Hauptmann in der Bürgerwehr',true,'`2'),
				PROF_DDL_MAJOR => array('Major in der Bürgerwehr','Major in der Bürgerwehr',true,'`2'),
				PROF_DDL_COLONEL => array('Oberst in der Bürgerwehr','Oberst in der Bürgerwehr',true,'`2')
	     		);
	     		
$jobs = array   (
                JOB_FARMER => array('Bauer','Bäuerin',true,'`2'),
                JOB_SMITH => array('Schmied','Schmiedin',true,'`&'),
                JOB_ALCHEMIST => array('Alchemist','Alchemistin',true,'`%'),
                JOB_MINER => array('Minenarbeiter','Minenarbeiterin',true,'`e'),
                JOB_BANKER => array('Bankier','Bänkerin',true,'`^'),
                JOB_GROCER => array('Krämer','Krämerin',true,'`6'),
                JOB_SMELTER => array('Schmelzer','Schmelzerin',false,'`Q'),
                JOB_SHOWMAN => array('Schausteller','Schaustellerin','`F')
                );

$g_arr_prof_jobs = array	(
							JOB_FARMER => array(
                                                    'name'=>'Bauer',
                                                    'desc'=>'Ein Leben als Bauer ist ein Leben voller harter Arbeit und Entbehrung. Dein Tag beginnt früh und endet spät. Dafür hast du dir eine Professionalität im Umgang mit Tieren angeeignet, welche es dir gestattet deine Abenteuer im Kampf einzuschränken und dafür deinen Erfahrungsschatz durch Viehzucht zu erhöhen.',
                                                    'newdaymsg'=>'Als Bauer beginnt dein Tag sehr früh und arbeitsam.',
                                                    //'locked_right'=>access_control::SU_RIGHT_GROTTO,
                                                    'min_dk'=>10,
                                                    'cost'=>25,
													'needs_own_house'=>true
                                                    ),

                            JOB_SMITH => array(
                                                    'name'=>'Schmied',
                                                    'desc'=>'Als Schmied bist du in der Lage glühenden Stahl zu todbringenden Waffen zu formen. Rohe Kraft und eine hohe Zähigkeit erlauben es dir in deiner Schmiede sowohl neue Waffen zu fertigen, wie auch bereits existierende Stücke aufzuwerten und zu veredeln.',
                                                    'newdaymsg'=>'Als Schmied bist du schon sehr früh auf den Beinen und hast viel zu tun.',
                                                    'locked_right'=>access_control::SU_RIGHT_GROTTO,
                                                    'min_dk'=>30,
                                                    'cost'=>25,
													'needs_own_house'=>true
                                                    ),

                            JOB_ALCHEMIST => array(
													'name'=>'Alchemist',
                                                    'desc'=>'Als Alchemist bist du der Bezwinger der Elemente. Du mischst, rührst, schüttelst die verschiedensten Ingredenzien zusammen - und selbst Rezepte, denen Uneingeweihte nur ein verkohltes Instrumentarium entlocken können, bringst du bald zur Meisterschaft.',
                                                    'newdaymsg'=>'Als Alchemist sitzt du schon lange vor Sonnenaufgang bei deinen blubbernden Kolben und studierst dort deine Rezepte.',
													//'locked_right'=>access_control::SU_RIGHT_GROTTO,
													'min_dk'=>15,
													'cost'=>25,
													'needs_own_house'=>true
													),

                            JOB_MINER => array(
                                                    'name'=>'Minenarbeiter',
                                                    'desc'=>'Als Minenarbeiter kennst du dich ganz genau mit Gestein und Stollen aus. Du bist ein Experte im Abbau von Erzen und fühlst dich unter Tage zuhaus. Dadurch besteht dort für dich eine deutlich niedrigere Unfallgefahr.',
                                                    'newdaymsg'=>'Als Minenarbeiter hast du nicht wirklich viel von deiner Nachtruhe, da du immer darauf bedacht sein musst der erste in der Mine zu sein, damit dir die anderen nicht das Gold und die Edelsteine vor der Nase wegschnappen.',
                                                    //'locked_right'=>access_control::SU_RIGHT_GROTTO,
                                                    'min_dk'=>15,
                                                    'cost'=>15,
													'needs_own_house'=>false
                                                    ),

                            JOB_BANKER => array(
													'name'=>'Bankier',
                                                    'desc'=>'Als Bankier bist du ein Meister der Rechenkunst und der Verwaltung von Vermögen. Geschickte und verteilte Goldanlagen erlauben es dir zum einen vorteilhafter aus waghalsigen Geschäften hervorzugehen, und zum anderen ein Zehntel deines Bankkontos über eine Heldentat hinweg zu retten.',
                                                    'newdaymsg'=>'Plagende Gedanken an dein Vermögen und deine aktuelle Anlagestrategie lassen dich schlecht und unruhig schlafen.',
													//'locked_right'=>access_control::SU_RIGHT_GROTTO,
													'min_dk'=>60,
													'cost'=>50,
													'needs_own_house'=>true
													),

                            JOB_GROCER => array(
													'name'=>'Krämer',
                                                    'desc'=>'Als Krämer widmest du dein Tagwerk dem gewinnbringenden An- und Verkauf von Gütern aller Art. Feilschen liegt dir im Blut, und du schreckst auch nicht davor zurück dem alten Großmütterchen seine letzten Taler abzuschwatzen. Daher hast du im Handel einen 10%igen Vorteil.',
                                                    'newdaymsg'=>'Schon früh erhebst du dich aus deinem Bett um vor dem Spiegel dein abgebrühtes Gesicht einzustudieren und dir neue Händlertricks einfallen zu lassen.',
													//'locked_right'=>access_control::SU_RIGHT_GROTTO,
													'min_dk'=>20,
													'cost'=>30,
													'needs_own_house'=>false
													),

                            JOB_SMELTER => array(
													'name'=>'Schmelzer',
                                                    'desc'=>'Als Schmelzer bist du ein Experte im Umgang mit rohen Erzen und Metallen. Du weisst ganz genau wie du unförmige Steinbrocken in harten und zuverlässigen Stahl verwandeln kannst und dabei nichts verschenkst.',
                                                    'newdaymsg'=>'Früh musst du aufstehen, da heute, wie auch jeden anderen Tag, wieder eine Menge Arbeit auf dich wartet.',
													'locked_right'=>access_control::SU_RIGHT_GROTTO,
													'min_dk'=>30,
													'cost'=>25,
													'needs_own_house'=>false
													),
													
                            JOB_SHOWMAN => array(
													'name'=>'Schausteller',
                                                    'desc'=>'Als Schausteller ist es deine Aufgabe und dein Vergnügen deinen Mitmenschen eine kurzweilige Zeit zu bescheren.',
                                                    'newdaymsg'=>'Du schläfst etwas länger aus als andere, denn die Nacht war wieder lang.',
													//'locked_right'=>access_control::SU_RIGHT_GROTTO,
													'min_dk'=>10,
													'cost'=>25,
													'needs_own_house'=>false
													),													

                                                    );
// END Berufe

function get_ddl_rank ($profession) 
{
	switch ($profession)
	{
		case 41 :
			$rank='Rekrut';
			break;
		case 42 :
			$rank='Corporal';
			break;
		case 43 :
			$rank='Sergeant';
			break;
		case 44 :
			$rank='Feldwebel';
			break;
		case 45 :
			$rank='Fähnrich';
			break;
		case 46 :
			$rank='Leutnant';
			break;
		case 47 :
			$rank='Hauptmann';
			break;
		case 48 :
			$rank='Major';
			break;
		case 49 :
			$rank='Oberst';
			break;
		default :
			$rank='Zivilist';
			break;
	}
	return ($rank);
}
	

?>
