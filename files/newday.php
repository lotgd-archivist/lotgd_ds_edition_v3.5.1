<?php

require_once('common.php');
require_once(LIB_PATH.'disciples.lib.php');
require_once(LIB_PATH.'profession.lib.php');

// Tier neu laden
Cache::delete(Cache::CACHE_TYPE_SESSION, 'playermount');
//sicherheit und so bathi
//session_regenerate_id(true);

require_once(LIB_PATH.'dg_funcs.lib.php');

//Demo-Account rauswerfen
if($Char->acctid==getsetting('demouser_acctid','0') && $Char->age>0)
{
	user_update(
		array
		(
			'location'=>0,
			'loggedin'=>0,
			'restatlocation'=>0,
			'output'=>'Ausgeloggt am '.date('d.m.Y H:i:s')
		),
		$Char->acctid
	);

	Atrahor::clearSession();
	redirect('demouser.php?op=logout',false,false);
}

// Änderungen?
if ((count($Char->dragonpoints) < (int)$Char->dragonkills) || empty($Char->race) || $Char->specialty==0 )
{
	$rp = $Char->restorepage;
	if(!isset($_GET['resurrection']) || is_null_or_empty($_GET['resurrection'])) unset($session['nav_vars']['resurrection']);
	redirect('char_changes.php?'.( (isset($_GET['resurrection']) && !is_null_or_empty($_GET['resurrection'])) ? 'resurrection='.urlencode($_GET['resurrection']).'' : '').'&rp='.urlencode($rp));
}

if (isset($_GET['rp']))
{
	$Char->restorepage = urldecode($_GET['rp']);
}

/**************
**  SETTINGS **
***************/

$row_extra = user_get_aei();

$turnsperday = getsetting('turns',10);

$dailypvpfights = getsetting('pvpday',3);

//Fishing
if ($session['user']['dragonkills']>5) $fturn = 5;
elseif ($session['user']['dragonkills']>3) $fturn = 4;
elseif ($session['user']['dragonkills']>1) $fturn = 3;
else $fturn = 0;

$changes_new = array();


if(isset($_GET['resurrection'])) CQuest::ressurect();
else CQuest::newday();

if ($_GET['resurrection']=='true')
{
	$resline = '&resurrection=true';
}
else if ($_GET['resurrection']=='egg')
{
	$resline = '&resurrection=egg';
}
//RUNEN MOD
elseif( $_GET['resurrection']=='rune' )
{
	$resline = '&resurrection=rune';
}
//RUNEN END
else {	// Nicht wiedererweckt

	$resline = '';

	$changes_new = array( 'symp_given'=>0, 'doc_visited'=>0, 'poollook'=>0, 'treepick'=>0,
	'fishturn'=>$fturn, 'rouletterounds'=>5, 'seenbard'=>0, 'usedouthouse'=>0,
	'lottery'=>0, 'guildfights'=>0, 'itemsin'=>0, 'itemsout'=>0, 'games_played'=>0, 'profession_tmp'=>0,
	'resurrections_today' =>0, 'kala_visits'=>0, 'wetterhexe_charm'=>0, 'goldmine_visits'=>0);

	// Gourmetpunkte
	if(e_rand(1,5) == 1)
	{
		$changes_new['gourmet'] = max($row_extra['gourmet'] - 10,0);
	}

    user_set_aei(array('questinator' => db_real_escape_string(utf8_serialize(array())) ));

	unset($session['daily']); //Container für unwichtige Begrenzungen pro Spieltag, wichtige sollten in der Datenbank bzw Bitflags stehen.

}

$changes = array ( 'cage_action'=>0, 'dollturns'=>5, 'spittoday'=>0, 'gotfreeale'=>0, 'xchangedtoday'=>0, 'dpower'=>0,
'abused'=>0, 'boughtroomtoday'=>0
, 'goldin'=>0, 'gemsin'=>0, 'goldout'=>0, 'gemsout'=>0, 'seenacademy'=>0, 'witch'=>0,  'abyss'=>0, 'oldspirit'=>0, 'jobturns'=>5, 'gladarena' => 3, 'gladpush' => 5);

$changes = array_merge($changes,$changes_new);

// Rasten
// Nur wenn Spieler nicht tot, nicht im Kerker und nicht halbtot
if($session['user']['spirits'] != RP_RESURRECTION && $session['user']['imprisoned'] == 0 && $session['user']['alive']) {
	$changes['hadnewday'] = 0;
}
else {
	$changes['hadnewday'] = 2;
}
// END Rasten

user_set_aei($changes);
$changes=array();

/******************

** End Settings **

******************/

//Save all output into this variable
$str_output = '';

// Stats
if($session['user']['turns'] > 0 && $session['user']['alive']) {
	user_set_stats( array('turns_not_used'=>'turns_not_used+'.$session['user']['turns']) );
}
// END Stats

page_header('Es ist ein neuer Tag!');

$str_output .= get_title('Ein neuer Tag ist angebrochen');


if ($session['user']['alive']!=true)
{
	$session['user']['resurrections']++;
	$str_output .= '`@Du bist wiedererweckt worden! Dies ist deine '.$session['user']['resurrections'].'te Wiederauferstehung.`0`n';

	$session['user']['alive']=true;
}
$session['user']['age']++;

// Dauern die Ingametage länger als eine Newdayzeit?
$tagesabschnitte = false;
if (getsetting('dayparts','1') > 1) $tagesabschnitte = true;

$str_output .= 'Du öffnest deine Augen und stellst fest, dass dir ein neuer '.($tagesabschnitte?'Tagesabschnitt':'Tag').' geschenkt wurde. Dies ist dein `^'.$session['user']['age'].'.`0 '.($tagesabschnitte?'Tagesabschnitt':'Tag').' in diesem Land. ';
$str_output .= 'Du fühlst dich frisch und bereit für die Welt!`n';
$str_output .= '`2Runden für den aktuellen '.($tagesabschnitte?'Tagesabschnitt':'Tag').': `^'.$turnsperday.'`n';
//WK nach den Zinsen, aber vor den Rassen setzen

// BANK
$maxinterest = ((float)getsetting('maxinterest',15)/100) + 1; //1.15;
$mininterest = ((float)getsetting('mininterest',5)/100) + 1; //1.05;

$interestrate = e_rand($mininterest,$maxinterest);
$interestgold = round($session['user']['goldinbank']*($interestrate-1));

if ($session['user']['goldinbank']<0 && abs($session['user']['goldinbank'])<(int)getsetting('maxinbank',10000))
{
	$str_output .= '`2Heutiger Zinssatz: `^'.(($interestrate-1)*100).'% `n';
	$str_output .= '`2Zinsen für Schulden: `^'.$interestgold.'`2 Gold.`n';
	$session['user']['goldinbank']+=$interestgold;
}
else if ($session['user']['goldinbank']<0 && abs($session['user']['goldinbank'])>=(int)getsetting('maxinbank',10000))
{
	$str_output .= '`4Die Bank erlässt dir deine Zinsen, da du schon hoch genug verschuldet bist.`n';
}
else if ($session['user']['goldinbank']>=0 && $session['user']['goldinbank']>=(int)getsetting('maxinbank',10000) && $session['user']['turns']<=getsetting('fightsforinterest',4))
{
	$str_output .= '`4Die Bank kann dir keine Zinsen zahlen. Sie würde früher oder später an dir pleite gehen.`n';
}
else if ($session['user']['goldinbank']>=0 && $session['user']['goldinbank']<(int)getsetting('maxinbank',10000) && $session['user']['turns']<=getsetting('fightsforinterest',4))
{
	$str_output .= '`2Aktueller Zinssatz: `^'.(($interestrate-1)*100).'% `n';
	$str_output .= '`2Durch Zinsen verdientes Gold: `^'.$interestgold.'`n';
	$session['user']['goldinbank']+=$interestgold;
}
else
{
	$str_output .= '`2Dein aktueller Zinssatz beträgt `^0% (Die Bank gibt nur den Leuten Zinsen, die dafür arbeiten)`n';
}
// END Bank

$session['user']['turns'] = $turnsperday;
$session['user']['castleturns'] = getsetting('castle_turns',1);



// Schläger
if ($row_extra['beatenup']!=1) {
	$str_output .= '`2Deine Gesundheit wurde wiederhergestellt auf `^'.$session['user']['maxhitpoints'].'`n';
	$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
}
else {
	$str_output .= '`4Die Prügel der letzten Nacht haben Spuren hinterlassen. Du regenerierst nicht.`n';
	$changes['beatenup'] = 0;
}


if ($row_extra['beatenup']>1) {
	$beaten=$row_extra['beatenup']-1;

	if ($row_extra['beatenup']>2) {
		$str_output .= '`6Die "Familie" wird dich noch für '.($beaten-1).' '.($tagesabschnitte?'Tagesabschnitte':'Tage').' ihren Freund nennen!`n';
	}
	else {
		$str_output .= '`6Von nun an bist du kein Freund der "Familie" mehr!`n';
		$beaten=0;
	}

	$changes['beatenup'] = $beaten;

}

// END Schläger


//fuerst schuld

$fuerst_schuld = utf8_unserialize(getsetting('fuerst_schuld',''));

if($Char->acctid == $fuerst_schuld['id']){
    $restschuld = round((($fuerst_schuld['gesamt']-100000)/2500)*4)-$fuerst_schuld['paid'];
    if($restschuld > 0){
        $paid = 0;

        if($fuerst_schuld['days'] > 0){
            if($Char->gems > 0){
                $paid = min($Char->gems,$restschuld);
                $Char->gems -= $paid;
                $fuerst_schuld['paid'] += $paid;
                savesetting('amtskasse', (getsetting('amtskasse','0') + ($paid * 2500)) );
                addhistory('Zahlte mehr oder weniger freiwillig '.$paid.' Edelsteine um die Amtskasse aufzufüllen. Das Volk zeigte seine Dankbarkeit mit verfaulten Tomaten.',1,$Char->acctid);
            }
            systemlog('Fürst-Schuld: '.$paid,$Char->acctid.' '.utf8_serialize($fuerst_schuld));
            systemmail($user_fuerst['id'],'`4Königliche Strafe','`4Der König ist entzürnt, dass Ihr glaubt euch vor eurere Strafe drücken zu können und gibt Euch noch '
                .$fuerst_schuld['days']
                .' Tagesabschnitte Zeit um nun die `bdoppelte`b Summe an Edelsteinen zu bezahlen. Noch fehlen Euch `^'
                .$restschuld
                .'`4 Edelsteine. Dieses mal konntet Ihr `^'.$paid.'`4 Edelsteine bezahlen. Solltet ihr die Strafe nicht rechtzeitig begleichen, werdet Ihr `^alles verlieren`4 was Ihr oder Eure Gilde besitzt.'
            );
        }else{
            //todo
        }

        savesetting('fuerst_schuld',utf8_serialize(array(
            'id' => $Char->acctid,
            'gesamt' => $fuerst_schuld['gesamt'],
            'paid' => $fuerst_schuld['paid'],
            'days' => max(0,($fuerst_schuld['days']-1))
        )));

    }
}

// schuld ende


// Verheiratet
if (($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295) && e_rand(1,3)==2)
{
	$str_output .= '`n`%Du bist verheiratet, es gibt also keinen Grund mehr, das perfekte Image aufrecht zu halten. Du lässt dich ein bisschen gehen.`n Du verlierst einen Charmepunkt.`n';
	$session['user']['charm']= max(0,$session['user']['charm']-1);

}
// END Verheiratet

// Buffs
$tempbuff				= $Char->bufflist;
$Char->bufflist 		= array();
$session['bufflist']=array();
if(is_array($tempbuff))
{
	foreach($tempbuff as $key => $val)
	{
		if ($val['survivenewday']==1){
			$session['bufflist'][$key]=$val;
			$str_output .= $val['newdaymessage'].'`n';
		}
	}
}
// END Buffs

$dkff=0;
//dragonpoints ist beim ersten DK noch kein Array und führt deswegen zu Fehlern
if(is_array($session['user']['dragonpoints']))
{
	foreach($session['user']['dragonpoints'] as $key => $val)
	{
		if ($val=='ff')
		{
			$dkff++;
		}
	}
	if ($dkff>0)
	{
		$str_output .= '`n`2Du erhöhst deine Waldkämpfe um `^'.$dkff.'`2 durch deine Heldentaten!';
	}

	// Vieh
	if ($session['user']['hashorse'])
	{
		// Mount neu laden
		getmount($session['user']['hashorse'],true);

		$session['bufflist']['mount']=utf8_unserialize($playermount['mountbuff']);

		if ($row_extra['hasxmount']==1) {
			$session['bufflist']['mount']['name']=$row_extra['xmountname'].' `&('.$session['bufflist']['mount']['name'].'`&)';
		}

		$session['bufflist']['mount']['rounds']+=$row_extra['mountextrarounds'];
	}
	// END Vieh

	// Wiederauferstehungen
	if($_GET['resurrection']!='')
	{
		$Char->setNewdayBit(UBIT_NEWDAY_RESET_RESURRECTION,0);
		$spirits=-6;
		$changes['resurrections_today'] = $row_extra['resurrections_today']+1;
		$session['user']['restorepage']='village.php?c=1';

		if ($_GET['resurrection']=="true") //normale Wiedererweckung
		{
			addnews('`&'.$session['user']['name'].'`& wurde von `$Ramius`& wiedererweckt.');
			if ($session['user']['marks']>=CHOSEN_FULL) {
				$session['user']['deathpower']=max(0,$session['user']['deathpower']-80);
			}
			else {
				$session['user']['deathpower']=max(0,$session['user']['deathpower']-100);
			}
		}
		elseif ($_GET['resurrection']=='egg') //Wiedererweckung durch Ei
		{
			addnews('`&'.$session['user']['name'].'`& hat das `^goldene Ei`& benutzt und entkam so dem Schattenreich.');
			savesetting('hasegg',0);

			item_set(' tpl_id="goldenegg" ',array('owner'=>0));

		}
		elseif ($_GET['resurrection']=='rune') //Wiedererweckung durch Rune
		{
			addnews('`q'.$session['user']['name'].'`q hat die Magie der `#Eiwaz-Rune`q benutzt um aus dem Schattenreich zu entkommen.');
			item_delete('tpl_id="r_eiwaz" AND owner='.$session['user']['acctid'],1);
		}
	}
	else
	{
		$Char->setNewdayBit(UBIT_NEWDAY_RESET, 0);
		$r1 = e_rand(-1,1);
		$r2 = e_rand(-1,1);
		$spirits = $r1+$r2;
	}

	// LAUNE
	$sp = array((-6)=>'Auferstanden',(-2)=>'Sehr schlecht',(-1)=>'Schlecht','0'=>'Normal',1=>'Gut',2=>'Sehr gut');

	$session['user']['spirits'] = $spirits;

	// END Laune

	// Specialties
	$sb = getsetting('specialtybonus',1);
	$str_output .= '`n`n`2Für dein Spezialgebiet erhältst du zusätzlich '.$sb.' Anwendung(en).`n';

	restore_specialty();
	// END specialties

	// RASSEN
	$arr_race = race_get($session['user']['race'],true);

	if(!empty($arr_race['newday_msg']))
	{
		$str_output .= '`n`n'.$arr_race['newday_msg'];
	}

	// Boni
	race_set_boni(false,false,$session['user']);

	// END RASSEN

	$str_output .= '`n`2Dein Geist und deine Stimmung ist `^'.$sp[$session['user']['spirits']].'`2!`n';
	if (abs($session['user']['spirits'])>0){
		$str_output .= '`2Deswegen `^';
		if($session['user']['spirits']>0){
			$str_output .= 'bekommst du zusätzlich ';
		}
		else{
			$str_output .= 'verlierst du ';
		}
		$str_output .= abs($session['user']['spirits']).' Runden`2.`n';
	}

	// Allg. Wertesetzen
	$session['user']['laston'] = date('Y-m-d H:i:s');

	$session['user']['bounties']=0;

	if ($session['user']['maxhitpoints']<6) {
		$session['user']['maxhitpoints']=6;
	}
	//WK und Schlossrunde werden jetzt unter dem Bank-Teil initialisiert. Müssen vor den Rassen-Boni stehen
	$session['user']['turns']+=$session['user']['spirits']+$dkff;
	$session['user']['playerfights'] = $dailypvpfights;

	$session['user']['seendragon'] = 0;
	$session['user']['seenmaster']=0;
	$session['user']['mazeturn']=0;
	$session['user']['seenlover'] = 0;
	$session['user']['fedmount'] = 0;
	if ($_GET['resurrection']!='true' && $_GET['resurrection']!='egg' && $_GET['resurrection']!='rune'){
		$session['user']['soulpoints']=50 + 5 * $session['user']['level'];
		$session['user']['gravefights']=getsetting('gravefightsperday',10);
		$session['user']['reputation']+=5;
		//Tauschquest-Bonus
		if($session['user']['exchangequest']==30)
		{
			$session['user']['gravefights']+=2;
		}
	}

	$session['user']['recentcomments']=$session['user']['lasthit'];
	$session['user']['lasthit'] = date("Y-m-d H:i:s");
	if ($session['user']['drunkenness']>66){
		$str_output .= '`&Wegen deines schrecklichen Katers wird dir 1 Runde abgezogen.';
		$session['user']['turns']--;
	}
	$session['user']['drunkenness']=0;
	//temporäre Methode um die vielen negativen Charmpunkt-Logs zu unterbinden. Trotzdem bei Gelegenheit die Ursachen beseitigen!
	if($session['user']['charm']<3 &&$row_extra['hadnewday']==0)
	{
		$session['user']['charm']=3;
	}

	//Kleines Wesen: Bonus und Malus, nicht bei Wiedererweckung oder Haft
	if ($row_extra['kleineswesen']>0 && $_GET['resurrection']=='' && $session['user']['imprisoned']==0)
	{
	    $str_output.='`n`@Weil du einen fantastischen Traum hattest, erhältst du `^'.$row_extra['kleineswesen'].'`@ zusätzliche Runden!`n';
	    $session['user']['turns']+=$row_extra['kleineswesen'];
	    $changes['kleineswesen']=0;
	}
	elseif ($row_extra['kleineswesen']<0 && $_GET['resurrection']=='' && $session['user']['imprisoned']==0)
	{
	    $str_output.='`n`$Weil du einen schlimmen Albtraum hattest, verlierst du `^'.abs($row_extra['kleineswesen']).'`$ Runden!`@`n';
	    $session['user']['turns']+=$row_extra['kleineswesen'];
	    $changes['kleineswesen']=0;
	}
	//End Kleines Wesen

	// NEWDAY SEMAPHORE
	// following by talisman & JT
	//Set global newdaysemaphore

	$lastnewdaysemaphore = convertgametime(strtotime(getsetting('newdaysemaphore','0000-00-00 00:00:00')));
	$gametoday = date('Ymd',gametime());

	if ($gametoday!=date('Ymd',$lastnewdaysemaphore)){
		$sql = 'LOCK TABLES settings WRITE';
		db_query($sql);

		// Talion: We have to get it right from the database to ensure that it is the newest version
		$arr_tmp = db_fetch_assoc(db_query('SELECT value FROM settings WHERE setting="newdaysemaphore"'));
		$lastnewdaysemaphore = convertgametime(strtotime(stripslashes($arr_tmp['value'])));

		if ($gametoday!=date('Ymd',$lastnewdaysemaphore)){
			//we need to run the hook, update the setting, and unlock.
			savesetting('newdaysemaphore',date('Y-m-d H:i:s'));
			$sql = 'UNLOCK TABLES';
			db_query($sql);
            systemlog('setnewday');
			require_once 'setnewday.php';
		}
		else
		{
			//someone else beat us to it, unlock.
			$sql = 'UNLOCK TABLES';
			db_query($sql);
			$str_output .= 'Somebody beat us to it';
		}
	}

	$w = Weather::get_weather();
	$str_output .= '`nEin Blick zum Himmel verrät dir das derzeitige Wetter: `6'.$w['name'].'`@.`n';
	// Wettereffekt nicht bei Wiedererweckung oder Haft
	if (($_GET['resurrection']=='') && ($session['user']['imprisoned']==0)) {
		$sql = 'SELECT * FROM specialty WHERE specid="'.$session['user']['specialty'].'"';
		$row = db_fetch_assoc(db_query($sql));
		require_once './module/specialty_modules/'.$row['filename'].'.php';
		$f = $row['filename'].'_run';
		$str_output.=$f('weather');
	}
	//End global newdaysemaphore code and weather mod.

	if ($session['user']['hashorse'])
	{
		$str_output .= str_replace('{weapon}',$session['user']['weapon'],'`n`&'.$playermount['newday'].'`n`0');
		if ($playermount['mountforestfights']>0)
		{
			$str_output .= '`n`&Ein '.$playermount['mountname'].' `&als Begleiter lässt dich `^'.((int)$playermount['mountforestfights']).'`& Runden zusätzlich kämpfen.`n`0';
			$session['user']['turns']+=(int)$playermount['mountforestfights'];
		}
		elseif ($playermount['mountforestfights']<0)
		{
			$str_output .= '`n`&Dein '.$playermount['mountname'].' `&kostet dich `^'.((int)abs($playermount['mountforestfights'])).'`& Waldrunden.`n`0';
			$session['user']['turns']+=(int)$playermount['mountforestfights'];
		}
	}
	else
	{
		$str_output .= '`n`&Du schnallst dein(e/n) `%'.$session['user']['weapon'].'`& auf den Rücken und ziehst los ins Abenteuer.`n`0';
	}

	//knappe
	$arr_disc = get_disciple($session['user']['acctid']);

	if (is_array($arr_disc) && $arr_disc['state'] > 0) {
		$str_output .= '`&Dein Knappe ' . $arr_disc['name'] . '`& erwartet dich schon voller Spannung auf die Abenteuer dieses Tages.';
		$session['bufflist']['decbuff'] = $arr_disc['buff'];
	} else {
		$gamedate = getsetting('gamedate','0005-01-01').'-'.getsetting('actdaypart',1);
		$disciple = db_get("SELECT name, free_day FROM disciples WHERE master=" . $session['user']['acctid'] . " LIMIT 1");
		
		if ($disciple && ($disciple['free_day'] >= $gamedate)) { // Spieler hat einen Knappen aber dieser frei
			$str_output .= '`&Dein Knappe ' . $disciple['name'] . '`& ist noch in der Schule.';
		}
	}
	// END knappe


	// BONI durch DP

	// WK durch DP
	$config = utf8_unserialize($session['user']['donationconfig']);

	if (!is_array($config['forestfights'])) {
		$config['forestfights']=array();
	}

	foreach($config['forestfights'] as $key=>$val){
		$config['forestfights'][$key]['left']--;
		$str_output .= '`n`@Du bekommst eine Extrarunde für die Punkte vom `^'.$val['bought'].'`@.';
		$session['user']['turns']++;
		if ($val['left']>1)
		{
			$str_output .= ' Du hast `^'.($val['left']-1).'`@ '.($tagesabschnitte?'Tagesabschnitte':'Tage').' von diesem Kauf übrig.';
		}
		else
		{
			unset($config['forestfights'][$key]);
			$str_output .= ' Dieser Kauf ist damit abgelaufen.';
		}
	}
	// Golinda
	if ($config['healer'] > 0) {
		$config['healer']--;
		if ($config['healer'] > 0) {
			$str_output .= '`n`@Golinda ist bereit, dich noch '.$config['healer'].' weitere '.($tagesabschnitte?'Tagesabschnitte':'Tage').' zu behandeln.';
		}
		else {
			$str_output .= '`n`@Golinda wird dich nicht länger behandeln.';
			unset($config['healer']);
		}
	}
	// Goldmine
	if ($config['goldmineday']>0) {
		$config['goldmineday']=0;
	}

	$session['user']['donationconfig']=utf8_serialize($config);
	// END BONI durch DP

	// Heimsuchung
	if ($row_extra['hauntedby']>'')
	{
		$str_output .= '`n`n`)Du wurdest von '.$row_extra['hauntedby'].'`) heimgesucht und verlierst eine Runde!';
		$session['user']['turns'] = max(0,$session['user']['turns']-1);
		$changes['hauntedby'] = '';
	}

	//Stadtwache
	/* Entlassung geändert, ist jetzt sofort wirksam weil das hier falsch war.
	if ($session['user']['profession']==PROF_GUARD_ENT) {
	$session['user']['profession']=0;
	$str_output .= '`n`&Mit dem heutigen Tag endet dein Dienst bei der Stadtwache.`n';
	}*/
	if ($session['user']['profession']==PROF_GUARD) {
		$str_output .= '`n`&Als `@Mitglied der Stadtwache`& hast du 2 Spielerkämpfe mehr!`n`n';
		$session['user']['playerfights']+=2;
		if ($changes['resurrections_today']==0) {
			$lohn = $session['user']['level'] * 200;
			$session['user']['goldinbank'] += $lohn;
			if(e_rand(1,6)==1){
				$str_output .= '`&Außerdem wurden `^'.$lohn.' Goldstücke und 1 Edelstein `&Lohn auf dein Bankkonto gezahlt!`n';
				$session['user']['gems'] += 1;
			}
			else
			{
				$str_output .= '`&Außerdem wurden `^'.$lohn.' Goldstücke `&Lohn auf dein Bankkonto gezahlt!`n';
			}
		}
	}
	elseif ($session['user']['profession']==PROF_GUARD_HEAD) {
		$str_output .= '`n`&Als `4Hauptmann der Stadtwache`& hast du 3 Spielerkämpfe mehr!`n`n';
		$session['user']['playerfights']+=3;
		if ($changes['resurrections_today']==0) {
			$lohn = $session['user']['level'] * 200 + 500;
			$session['user']['goldinbank'] += $lohn;
			if(e_rand(1,6)==1){
				$str_output .= '`&Außerdem wurden `^'.$lohn.' Goldstücke und 1 Edelstein `&Lohn auf dein Bankkonto gezahlt!`n';
				$session['user']['gems'] += 1;
			}
			else
			{
			$str_output .= '`&Außerdem wurden `^'.$lohn.' Goldstücke `&Lohn auf dein Bankkonto gezahlt!`n';
			}
		}
	}

	// Priester
	elseif ($session['user']['profession']==PROF_PRIEST || $session['user']['profession']==PROF_PRIEST_HEAD) {
		$str_output .= '`n`&Als `7`bPriester`b`& erhältst du 1 Anwendung in mystischen Kräften zusätzlich!`n';
		$session['user']['specialtyuses']['magicuses']++;
		if ($changes['resurrections_today']==0 && e_rand(1,4)==1) {
			$lohn = e_rand(100,1500);
			$str_output .= 'Außerdem sind die Bürger zufrieden mit dir, weshalb du `^'.$lohn.' Goldstücke`& aus Spenden erhälst.';
			$session['user']['gold']+=$lohn;
		}
	}

	// Hexen
	elseif ($session['user']['profession']==PROF_WITCH || $session['user']['profession']==PROF_WITCH_HEAD) {
		$str_output .= '`n`&Als `7`bHexe'.($session['user']['sex']?'':'r').'`b`& erhältst du 1 Anwendung in mystischen Kräften zusätzlich!`n';
		$session['user']['specialtyuses']['magicuses']++;
		if ($changes['resurrections_today']==0 && e_rand(1,4)==1) {
			$lohn = e_rand(100,1500);
			$str_output .= 'Außerdem sind die Bürger zufrieden mit dir, weshalb du `^'.$lohn.' Goldstücke`& aus Spenden erhälst.';
			$session['user']['gold']+=$lohn;
		}
	}

	// Tempeldiener
	elseif ($session['user']['profession']==PROF_TEMPLE_SERVANT && $_GET['resurrection'] == '') {

		$changes['temple_servant'] = ($row_extra['temple_servant'] >= 20 ? ceil($row_extra['temple_servant']*0.05) : $row_extra['temple_servant']);
		$changes['temple_servant']++;

		$days_left = 8 - $changes['temple_servant'];

		if($days_left <= 0) {
			$str_output .= '`n`@Dein Dienst als `7`bTempeldiener`b`@ neigt sich dem Ende zu!';
			addnews($session['user']['name'].'`8s Zeit als Tempeldiener ist Vergangenheit.');
			$session['user']['profession'] = 0;
			$changes['temple_servant'] = 5;	// Tage vor neuerlichem Dienst
		}
		else {
			$str_output .= '`n`6Als `7`bTempeldiener`b`6 musst du noch '.$days_left.' '.($tagesabschnitte?'Tagesabschnitte':'Tage').' arbeiten!`n';
		}

	}
	else if ($session['user']['profession']!=PROF_TEMPLE_SERVANT && $row_extra['temple_servant'] > 0 && $_GET['resurrection'] == '') {
		$changes['temple_servant'] = $row_extra['temple_servant']-1;
	}

	// Richter
	// eingeführt für Lohnzahlung
	if ($session['user']['profession']==PROF_JUDGE && $changes['resurrections_today']==0) {
		$lohn = $session['user']['level'] * 100;
		$str_output .= '`n`n`&Als `FRichter`& erhälst du einen Lohn von `^'.$lohn.' Goldstücken`&!';
		$session['user']['goldinbank'] += $lohn;
	}
	elseif ($session['user']['profession']==PROF_JUDGE_HEAD && $changes['resurrections_today']==0) {
		$lohn = $session['user']['level'] * 100 + 250;
		$str_output .= '`n`n`&Als `FRichter`& erhälst du einen Lohn von `^'.$lohn.' Goldstücken`&!';
		$session['user']['goldinbank'] += $lohn;
	}

	//Berufe

	if ($row_extra['job']>0) // && $row_extra['job']<11)
	{
		$my_job = $row_extra['job'];
		$wkloss = floor($session['user']['turns']*0.2);
		$str_output .= '`n`2'.$g_arr_prof_jobs[$my_job]['newdaymsg'].'`2 Daher hast du `^'.$wkloss.'`2 Runden weniger.`n`0';
		$session['user']['turns']=max(0,$session['user']['turns']-$wkloss);
	}

	//Berufe Ende

	//Kerker-Addon
	if ($session['user']['imprisoned']>0) {

		$session['user']['daysinjail']++;

		if ($session['user']['imprisoned']==1) {
			$str_output .= '`n`^Deine Haftstrafe ist beendet und du kannst den Kerker verlassen.`&`n';
			$session['user']['imprisoned']=0;
			$session['user']['steuertage']=7;
			$session['user']['location']=0;

		}
		else {
			$session['user']['imprisoned']=max(0,$session['user']['imprisoned']-1);
			$str_output .= '`n`^Du bist im Kerker gefangen und musst noch '.($session['user']['imprisoned']).' '.($tagesabschnitte?'Tagesabschnitte':'Tage').' absitzen, bevor du frei gelassen wirst!`&`n';
		}
	}
	if ($session['user']['imprisoned']<0) {
		$str_output .= '`n`^Du wurdest von einem MOD oder einem ADMIN auf unbestimmte Zeit eingekerkert. Kläre mit diesem, wann du wieder frei gelassen wirst!`&`n';
	}
	if ($session['user']['prangerdays']>0){
		$session['user']['prangerdays']--;
	}
	// END Kerker-Addon

	// Male
	if ($session['user']['marks'] & CHOSEN_BLOODGOD)
	{
		$dice=($session['user']['exchangequest'] == 30 ? e_rand(1,100) : e_rand(1,60));
		if ($row_extra['bloodchampdays']>3)
		{ //Frist verstrichen, Pakt nichtig
			$Char->setBit(CHOSEN_BLOODGOD,'marks',0);
			$changes['bloodchampdays']=0;
			systemmail($session['user']['acctid'],'`$Von : Blutgott!`0','`&Sterblicher!`nDeine Feigheit ist mir zuwider! Betrachte unseren Pakt als nichtig!');
		}
		elseif ($row_extra['bloodchampdays']>0)
		{ //Herausforderung läuft
			systemmail($session['user']['acctid'],'`$Von : Blutgott!`0','`&Sterblicher!`nMein Champion wartet auf dich!`nBeweise Mut und fordere ihn heraus! Du hast noch '.($row_extra['bloodchampdays']==3?'`4bis heute Abend`& dafür':(3-$row_extra['bloodchampdays']).' '.($tagesabschnitte?'Tagesabschnitte':'Tage').' ').'Zeit!');
			$changes['bloodchampdays'] = $row_extra['bloodchampdays']+1;
		}
		elseif($session['user']['level']>2 && $dice==15)
		{ //neue Herausforderung
			$changes['bloodchampdays']=1;
			systemmail($session['user']['acctid'],'`$Von : Blutgott!`0','`&Sterblicher!`nWisse dass ich, der Blutgott, deiner überdrüssig geworden bin! Ich fordere dich auf, die Feste der Auserwählten aufzusuchen und dich im Kampf gegen meinen Champion als würdig zu zeigen!`nDu hast 3 '.($tagesabschnitte?'Tagesabschnitte':'Tage').' Zeit. Solltest du dieser Herausforderung nicht nachkommen, so betrachte unseren Pakt als nichtig!');
		}
	}
	// END Male

	//DK-Verweigerung
	
	if($session['user']['prefs']['deacautoexp'] != true)
	{
	    /*
		$cowardlevel = getsetting('cowardlevel',10);
	
		if (($session['user']['level']>=$cowardlevel) && ($session['user']['dragonkills']>=2) && ($session['user']['marks']<31) && $session['user']['superuser'] == 0)
		{
	
			if ($session['user']['age']>=getsetting('maxpvpage',50)){
				$str_output .= '`n`@Du bist nun schon eine ganze Weile hier und hast immer noch keine Heldentat vollbracht.
				`n`@Die Leute fangen schon an, über dich und deine Feigheit zu reden. Du solltest dich beeilen!
				`n(Einige Spielfunktionen sind deaktiviert, bis du eine Heldentat vollbracht hast.)
				`n';
				$session['user']['reputation']-=5;
			}

			if ($session['user']['age']>=getsetting('cowardage',60)) {
	
				if (getsetting('coward_title_enabled',1) == 1 && $session['user']['title']!='Feigling')
				{
					$newtitle = 'Feigling';
	
					$regname = ($row_extra['cname'] ? $row_extra['cname'] : $session['user']['login']);
	
					$session['user']['name'] = $newtitle.' '.$regname;
					$session['user']['title'] = $newtitle;
	
					$str_output .= '`n`@Von nun an bist du bekannt als '.$session['user']['name'].'`0!`n`n';
					addnews('`@'.$regname.'`@ ist aufgrund '.($session['user']['sex']?'ihrer':'seiner').' Feigheit vor dem Drachen von nun an bekannt als '.$session['user']['name'].'!`n');
	
				}
				else {
					if(e_rand(1,5) == 1) {
						//$sql = 'INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"village",'.$session['user']['acctid'].',": `\@eilt hastig durch das Stadtzentrum, verfolgt von einer Horde Kinder, die '.($session['user']['sex']?'sie':'ihn').' johlend mit Steinchen bewerfen!")';
						//db_query($sql);
						addnews('`@'.$session['user']['name'].'`@ ist wieder in der Stadt! Haltet Eier und faules Obst bereit!');
					}
				}
	
				$str_output .= '`@Deine Feigheit vor dem Drachen spricht sich herum! Du verlierst Ansehen!`n';
				$session['user']['reputation']-=10;
			}
		}
	     */

		// Wiedergewinn von Erfahrung für Drückeberger, aber nicht für Fremde oder SU-Accounts^^
		$recoveryage=getsetting('recoveryage',75);
		$recoveryexp=getsetting('recoveryexp',500);
		$exp=0;
	
		if ($session['user']['age']>=$recoveryage && $session['user']['dragonkills']>=1 && (($Char->superuser==0) || ($Char->superuser>4)))
		{
			$exp+=($recoveryexp*$session['user']['dragonkills']);
			if ($session['user']['age']>=($recoveryage*2))
			{
				$exp+=($recoveryexp*$session['user']['dragonkills']);
			}
			$exp+=$recoveryexp;
			if ($session['user']['age']>=($recoveryage*3))
			{
				$exp+=($recoveryexp*$session['user']['dragonkills']);
			}
			$str_output .= '`^`nDu bist nun schon derart lange in der Stadt, dass Teile deiner Erinnerung zurückkehren, die du bei deiner letzten Heldentat verloren hast.`nDeine Erfahrung steigt um '.$exp.' Punkte.`n';
			$session['user']['experience']+=$exp;
		}
	}
	// END DK-Ausweichler

	//Steuern zahlen
	$str_output .= '`n';

	$taxrate = ($session['user']['level'] > 10 ? 2 : 1) * getsetting('taxrate',750);
	$taxprison = getsetting('taxprison',1);

	if (($session['user']['marks']<CHOSEN_FULL) && ($session['user']['imprisoned']==0) && ($session['user']['level'] >= 5) && $taxrate>0 ) {

		if ($session['user']['steuertage']==3) {
			$str_output .= '`^`cIn zwei '.($tagesabschnitte?'Tagesabschnitten':'Tagen').' musst Du Steuern zahlen gehen!`c`n`n';
		}
		elseif ($session['user']['steuertage']==2) {
			$str_output .= '`^`c'.($tagesabschnitte?'Am nächsten Tagesabschnitt':'Morgen').' musst Du Steuern zahlen gehen!`c`n`n';
		}
		elseif ($session['user']['steuertage']==1) {

			if($session['user']['dragonkills'] > 0) {
				$taxfee = getsetting('taxfee',20); //Bearbeitungsgebühr bei Bankeinzug
				$banktaxrate = $taxrate + round($taxrate * $taxfee  / 100,0);

				$str_output .= '`^`cEs ist Zahltag - du musst Steuern zahlen!';

				if ($session['user']['prefs']['taxfrombank'] && $session['user']['goldinbank']>=$banktaxrate)
				{
					$str_output .= ' Weil du die Erlaubnis gegeben hast, wird der Betrag von '.$banktaxrate.' Gold (enthält '.$taxfee.'% Gebühr) von deinem Bankkonto eingezogen.`n';
					$mailsubject='`qSteuern eingezogen`0';
					$mailtext='`&Deine Steuern in Höhe von `^'.$banktaxrate.' Gold`& (inklusive '.$taxfee.'% Bearbeitungsgebühr) wurden von deinem Bankkonto eingezogen.`n`nHochachtungsvoll`nDein Steuereintreiber';
					savesetting ('amtskasse' ,getsetting ('amtskasse',0)+ $banktaxrate);
					if (getsetting('amtskasse','0')>getsetting('maxbudget','2000000'))
					{
						savesetting('amtskasse',getsetting('maxbudget','2000000'));
					}
					$session['user']['goldinbank']-=$banktaxrate;
					$session['user']['steuertage']=7;
					debuglog('zahlte Steuern. (Bankeinzug!)');
				}
				elseif ($taxprison>0)
				{
					$str_output .= ' Tust du es nicht, wanderst du in den Kerker!`n';
					$mailsubject='`$Heute ist Zahltag!`0';
					$mailtext='`&Vergiss nicht, heute deine Steuern (`^'.$taxrate.' Gold`&) zu zahlen! Tust du es nicht, wirst du in den Kerker gesperrt und dein Bankkonto wird gepfändet!`n`nHochachtungsvoll`nDein Steuereintreiber';
					debuglog('wurde ermahnt heute Steuern zu zahlen. (Ansonsten Kerker!) [' . $session['user']['goldinbank'] . '/' . $banktaxrate . ']');
				}
				else
				{
					$mailsubject='`$Heute ist Zahltag!`0';
					$mailtext='`&Vergiss nicht heute deine Steuern (`^'.$taxrate.' Gold`&) zu zahlen! Tust du es nicht, wird dein Bankkonto gepfändet!`n`nHochachtungsvoll`nDein Steuereintreiber';
					debuglog('wurde ermahnt heute Steuern zu zahlen. (Ohne Kerkerdrohung!) [' . $session['user']['goldinbank'] . '/' . $banktaxrate . ']');
				}

				systemmail($session['user']['acctid'],$mailsubject,$mailtext);
			}
			else {
				$str_output .= '`^`cHeute wäre Zahltag - du müsstest heute Steuern zahlen. Als Neuankömmling bist du jedoch noch davon befreit!`n';
				debuglog('hätte Steuern zahlen müssen, ist aber noch Neuling!');
			}

		}
		elseif ($session['user']['steuertage']<1)
		{
			if($session['user']['dragonkills'] > 0) {

				$taxrate *= 2;

				if ($taxprison>0)
				{
					$mailtext='`&Du wusstest, dass du Steuern zahlen musst und hast dich dennoch geweigert. Du hieltest es nicht einmal für nötig im Rathaus zu erscheinen und zu erklären warum du das Gold nicht hast.`nDafür haben sie dich jetzt geholt. Mitten in der Nacht wurdest du festgenommen und in den Kerker geworfen. Von deinem Bankkonto wurden '.$taxrate.' Gold gepfändet. Lass dir das fürs nächste Mal eine Lehre sein!';
					$str_output .= '`^Da du Deine Steuern nicht gezahlt hast, kommst du in den Kerker! Außerdem wurden '.$taxrate.' Gold von der Bank gepfändet!`n`n';
					$session['user']['imprisoned']=$taxprison;
					$session['user']['restatlocation']=0;
					debuglog('hinterzog Steuern und landete im Kerker.');
					addnews('`2'.$session['user']['name'].'`2 hat die Steuern nicht gezahlt und büßt dafür im Kerker!');
				}
				else
				{
					$mailtext='`&Du wusstest, dass du Steuern zahlen musst und hast dich dennoch geweigert. Du hieltest es nicht einmal für nötig, im Rathaus zu erscheinen und zu erklären, warum du das Gold nicht hast.`nDafür wurde jetzt das Doppelte des hinterzogenen Betrags von deinem Konto gepfändet, nämlich '.$taxrate.' Gold. Sei froh, dass so etwas derzeit nicht mit Kerker bestraft wird und lass es dir fürs nächste Mal eine Lehre sein!';
					$str_output .= '`^Da du Deine Steuern nicht gezahlt hast wurden '.$taxrate.' Gold von der Bank gepfändet!`n`n';
					debuglog('hinterzog Steuern.');
				}
				systemmail($session['user']['acctid'],'`$Steuerhinterziehung!`0',$mailtext);
				savesetting ('amtskasse' ,getsetting ('amtskasse',0)+ $taxrate);
				if (getsetting('amtskasse','0')>getsetting('maxbudget','2000000'))
				{
					savesetting('amtskasse',getsetting('maxbudget','2000000'));
				}
				$session['user']['goldinbank']-=$taxrate;
				$session['user']['steuertage']=7;
			}
			else {
				$session['user']['steuertage']=7;
			}
		}

		$session['user']['steuertage']--;

	}
	// END Steuern zahlen

	$rp = $session['user']['restorepage'];
	// wird beim Newday-Redirect (checkday()) gesetzt, wenn badnav.php die aktuelle Seite ist
	// verhindert ein doppeltes Aufrufen einer Seite, die Boni für den Spieler enthält
	$bool_badnav = isset($_GET['badnav']) ? (bool)$_GET['badnav'] : false;

	$multisres = db_query("SELECT acctid FROM accounts WHERE lastip='".db_real_escape_string($Char->lastip)."' OR uniqueid='".db_real_escape_string($Char->uniqueid)."' ");
	$multis = '0';	
	while($row = db_fetch_assoc($multisres))
	{
		$multis .= ','.$row['acctid'];
	}
	
		// korrekt ausgeloggt ?
		if ($session['user']['imprisoned']!=0) {
			addnav('Weiter','prison.php');
		}
		else if ($bool_badnav || empty($rp)) {
			addnav('Weiter','news.php');
		}
		else
		{
			addnav('Weiter',calcreturnpath($rp));
		}	

	// Ehre & Ansehen
	$Char->reputation = min( $Char->getMaxReputation(), $Char->reputation);
	//$session['user']['reputation'] = min($maximumrep,$session['user']['reputation']);

	if ($session['user']['reputation']<=-50){
		$session['user']['reputation']=-50;
		$str_output .= '`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du 1 Runde weniger kämpfen. Außerdem sind deine Feinde vor dir gewarnt.`nDu solltest dringend etwas für deine Ehre tun!';
		$session['user']['turns']--;
		$session['user']['playerfights']--;
	}
	elseif ($session['user']['reputation']<=-30){
		$str_output .= '`n`8Deine Ehrenlosigkeit hat sich herumgesprochen! Deine Feinde sind vor dir gewarnt, weshalb dir 1 Spielerkampf weniger gelingen wird.`nDu solltest dringend etwas für deine Ehre tun!';
		$session['user']['playerfights']--;
	}
	elseif ($session['user']['reputation']<-10){
		$str_output .= '`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du 1 Runde weniger kämpfen.';
		$session['user']['turns']--;
	}
	elseif ($session['user']['reputation']>=30){
		if ($session['user']['reputation']>50) {
			$session['user']['reputation']=50;
		}
		$str_output .= '`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du 1 Runde und 1 Spielerkampf mehr kämpfen.';
		$session['user']['turns']++;
		$session['user']['playerfights']++;
	}
	elseif ($session['user']['reputation']>10){
		$str_output .= '`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du 1 Runde mehr kämpfen.';
		$session['user']['turns']++;
	}
	// END Ehre
	output($str_output); //Zwischenausgabe, sonst stehen Ausgaben der Items oben
	$str_output='';

	// Newday-Hooks der Items
	// (Werden auch zur Kombo-Überprüfung eingesetzt)
	$arr_playeritems = array();
	$int_weight_total = 0;
	$bool_item_dam = false;
	$str_notinvent = '';

	$res = item_list_get( ' owner='.$session['user']['acctid'] );

	while($i = db_fetch_assoc($res)) {

		// Wenn angelegt oder im Inventar: Zur Kombo-Überprüfung einsetzen
		if($i['deposit1'] == 0 || $i['deposit1'] == ITEM_LOC_EQUIPPED) {

			// Wenn Gewicht zu groß, gammelt Item unter freiem Himmel. Gewisse Gefahren
			if($i['weight'] >= (int)getsetting('invent_maxweight',500)) {

				$str_notinvent .= $i['name'].',';

				if(!$bool_item_dam) {

					switch (e_rand(1,100)) {

						case 1:
						case 2:
						case 3:
							$bool_item_dam = true;
							break;

						default:

							break;

					}

				}

			}

			// Gewicht aufaddieren, wenn in Inventar
			$int_weight_total += $i['weight'];

			$arr_playeritems[$i['tpl_id']] = $i['id'];
			if(!empty($i['newday_hook'])) {

				item_load_hook($i['newday_hook'],'newday',$i);

			}
		}
		else {

			if(!empty($i['newday_furniture_hook'])) {

				item_load_hook($i['newday_furniture_hook'],'newday',$i);

			}

		}

		// HOT Items:
		if($i['hot_item'] && $i['deposit1'] == 0 && $session['user']['pvpflag'] == PVP_IMMU) {
			$str_output .= '`n`c`4'.$i['name'].'`4 setzt deine PvP-Immunität außer Kraft, solange du es bei dir trägst!`c`n';
		}

	}
	// END newday hooks

	// Item-Kombos
	$sql = 'SELECT * FROM items_combos WHERE type='.ITEM_COMBO_NEWDAY;
	$res = db_query($sql);

	while($c = db_fetch_assoc($res)) {

		$bool_ok = true;

		if($c['id1'] && !$arr_playeritems[$c['id1']]) {
			$bool_ok = false;
		}
		if($c['id2'] && !$arr_playeritems[$c['id2']]) {
			$bool_ok = false;
		}
		if($c['id3'] && !$arr_playeritems[$c['id3']]) {
			$bool_ok = false;
		}

		if($bool_ok) {

			if(!empty($c['hook'])) {
				item_load_hook($c['hook'],'newday',$c);
			}
			if(!$item_hook_info['hookstop']) {

				$str_buffs = ','.$c['buff'];

				item_set_buffs(ITEM_BUFF_NEWDAY | ITEM_BUFF_FIGHT,$str_buffs);

				$str_output .= '`n`n`^'.$c['combo_name'].'`^ zeigt einen Effekt!`0';
			}

		}

	}
	// END Item-Kombos

	// Items: Gewicht-Mali
	if(!empty($str_notinvent)) {
		$str_notinvent = mb_substr($str_notinvent,0,mb_strlen($str_notinvent)-1);

		$str_output .= '`n`n`QFolgende Gegenstände lagern wegen ihres Gewichts zur Zeit unter freiem Himmel und sollten schnellstmöglich untergestellt werden: '
		.$str_notinvent;
	}
	$int_overweight = $int_weight_total - getsetting('invent_badweight',500);
	if($int_overweight > 0) {
		$arr_buff = array('name'=>'`$Übergewicht`0','defmod'=>1-0.001*$int_overweight,'rounds'=>'500','roundmessage'=>'`$Dein Übergewicht hindert dich in deiner Verteidigung!');
		$session['bufflist']['overweight'] = $arr_buff;
	}
	// END Items Gewicht-Mali

	// GILDEN-UPDATE
	if($session['user']['guildfunc'] == DG_FUNC_CANCELLED) {	// Gildenhopping verhindern
		$session['user']['guildrank']--;
		if($session['user']['guildrank'] <= 0) {
			$session['user']['guildfunc'] = 0;
			$session['user']['guildrank'] = 0;
			$str_output .= '`n`n`8`cDu darfst nun wieder einer Gilde beitreten!';
		}
		else {
			$str_output .= '`n`n`8`cDu musst noch '.$session['user']['guildrank'].' '.($tagesabschnitte?'Tagesabschnitte':'Tage').' warten, ehe du wieder einer Gilde beitreten darfst!';
		}
	}

	if($session['user']['guildid']) {

		if($session['user']['guildfunc'] != DG_FUNC_APPLICANT) {
			dg_player_update($session['user']['guildid']);
			dg_player_boni($session['user']['guildid']);
		}

	}

	// END GILDEN

	// Rezeptbuch
	if(empty($_GET['resurrection']) && (e_rand(1,10) == 1)) {
		$arr_combo_ids = utf8_unserialize($row_extra['combos']);
		$arr_alchemy_ids = array();
		if(isset($arr_combo_ids[ITEM_COMBO_ALCHEMY]))
		{
			// Referenz auf entsprechende Kategorie
			$arr_alchemy_ids = &$arr_combo_ids[ITEM_COMBO_ALCHEMY];
		}

		if(!empty($arr_alchemy_ids)) {

			$arr_combo_keys = array();
			$int_kill_cid = 0;

			// Am wenigsten geübte werden zuerst verlernt
			// Keys für alle Bekanntheitsgrade ermitteln
			for($i=1; $i<=3; $i++) {
				$arr_combo_keys = array_keys($arr_alchemy_ids,$i);
				if(empty($arr_combo_keys)) {
					continue;
				}
				$int_kill_cid = e_rand(0,sizeof($arr_combo_keys)-1);
				if($int_kill_cid >= 0) {
					if($arr_alchemy_ids[$int_kill_cid] > 0)
					{
						$arr_alchemy_ids[$int_kill_cid]--;
					}
					else
					{
						$int_kill_cid = 0;
					}
					break;
				}
			}

			if($int_kill_cid > 0) {
				$str_output .= '`n`$Dein alchemistisches Können verblasst nach und nach..`0';
				$changes['combos'] = db_real_escape_string(utf8_serialize($arr_combo_ids));
			}
		}
	}
	// END Rezeptbuch

	//Der Fremde: Bonus und Malus
	if ($row_extra['ctitle']=='`$Ramius '.($session['user']['sex']?'Sklavin':'Sklave'))
	{
		$str_output .= '`n';

		if ($session['user']['reputation']<0)
		{
			$str_output .= '`$`nDein Herr, Ramius, ist begeistert von deinen Greueltaten und gewährt dir seine `bbesondere`b Gnade!
				`nSeine Gnade ist diesmal besonders ausgeprägt - und du erhältst 2 zusätzliche Waldkämpfe!`n';
			$session['user']['turns']+=2;
			$session['user']['hitpoints']*=1.15;
			$session['bufflist']['Ramius1'] = array('name'=>'`$Ramius `bbesondere`b Gnade','rounds'=>200,'wearoff'=>'`$Ramius hat dir für heute genug geholfen.','atkmod'=>1.5,'roundmsg'=>'`$Eine Stimme in deinem Kopf befiehlt: `i`bZerstöre!`b Bring Leid über die Lebenden!`i','activate'=>'offense');
		}
		else
		{
			switch(e_rand(1,10))
			{
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
					$str_output .= '`$`nAls dein Herr, Ramius, von deinem guten Ruf erfuhr, überlegte er, ob er dich motivieren oder tadeln sollte ... und entschied sich fürs Motivieren.`n';
					$str_output .= '`$Seine Gnade ist heute mit dir - und du erhältst 2 zusätzliche Waldkämpfe!`n';
					$session['user']['turns']+=2;
					$session['user']['hitpoints']*=1.1;
					$session['bufflist']['Ramius2'] = array('name'=>'`$Ramius Gnade','rounds'=>150,'wearoff'=>'`$Ramius hat dir für heute genug geholfen.','atkmod'=>1.1,'roundmsg'=>'`$Eine Stimme in deinem Kopf befiehlt: `i`bZerstöre!`b Bring Leid über die Lebenden!`i','activate'=>'offense');
					break;
				case 6:
				case 7:
				case 8:
				case 9:
				case 10:
					$str_output .= '`$`nAls dein Herr, Ramius, von deinem guten Ruf erfuhr, überlegte er, ob er dich motivieren oder tadeln sollte ... und entschied sich fürs Tadeln.`n';
					$str_output .= '`$Sein Zorn ist heute mit dir - und du verlierst 2 Waldkämpfe!`n';
					$session['user']['turns']-=2;
					$session['user']['hitpoints']*=0.9;
					$session['bufflist']['Ramius3'] = array('name'=>'`$Ramius Zorn','rounds'=>200,'wearoff'=>'`$Ramius Zorn ist vorüber - für heute.','defmod'=>0.9,'roundmsg'=>'`$Ramius ist zornig auf dich!','activate'=>'offense');
					break;
			}
		}
	}
	// END Der Fremde

}	// END normaler Newday

if($changes['resurrections_today']>1) //Waldkampfverlust bei Wiedererweckung: 1. nix, 2. 30%, dann immer 5% mehr
{
	$turns_loss=round($session['user']['turns']*(getsetting('resurrection_turns_loss',25)+($changes['resurrections_today']*5))/100);
	$str_output.='`n`n`4Dies ist deine '.$changes['resurrections_today'].'. Wiedererweckung an diesem '.($tagesabschnitte?'Tagesabschnitt':'Tag').'. Du fühlst dich total schlapp und verlierst deshalb '.$turns_loss.' Waldrunden.';
	$session['user']['turns']-=$turns_loss;
}

if(count($changes)>0)
{
	user_set_aei($changes);
}
output($str_output,true);

page_footer();

?>
