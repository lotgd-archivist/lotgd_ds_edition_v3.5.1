<?php

require_once "common.php";
page_header("Mericks Ställe");

// Haustier-Mod by Chaosmaker <webmaster@chaosonline.de>
// http://logd.chaosonline.de

// Anpassung, Bugfixes etc by Maris (Maraxxus@gmx.de)
// Anpassung ans neue Itemsystem by talion
// 19.1.07 Noch mehr bugfixes by Maris

/**
 * Gibt ein Tier zurück
 *
 * @param int $petid Die ID des Items, welches das Tier enthält
 * @return array Gibt ein Array zurück, welches das Tier enthält
 */
function getpet($petid) {

	if(is_int($petid)) {
		$row = item_get(' id="'.$petid.'"' );
	}
	else {
		$row = item_get_tpl(' tpl_id="'.$petid.'"' );
	}

	if ($row['tpl_id']!='') {
		return $row;
	}
	else {
		return array();
	}
}

getmount($Char->hashorse,true);
$pointsavailable=$Char->donation-$Char->donationspent;
$playerpet = getpet((int)$Char->petid);

if($Char->hashorse > 0)
{
	if($playermount['creator'] != 0)
	{
		//Die Werte stammen aus der
		//cost_gold_basis und cost_gems_basis
		$repaygold = getsetting('stables_mount_editor_cost_gold_basis',5000);
		$repaygems = getsetting('stables_mount_editor_cost_gems_basis',20);
	}
	else
	{
		$repaygold = round($playermount['mountcostgold']*0.45,0);
		$repaygems = round($playermount['mountcostgems']*0.45,0);
	}
}
else
{
	$repaygold=0;
	$repaygems=0;
}
if($Char->petid > 0){
	$playerpet = getpet((int)$Char->petid);
	$petrepaygems = round($playerpet['gems']*2/3);
}
$futtercost = $Char->level * 20;
$pointsavailable=$Char->donation - $Char->donationspent;

addnav('Zurück');
addnav('Zurück zum Marktplatz','market.php');

output('`c`b `SM`Te`(r`)icks Stä`(l`Tl`Se`0`b`c`n');
if ($_GET['op']=='')
{
	checkday();
	output('`SE`Tt`(w`)as abseits der anderen Gebäude und Händlerstände findet man ein Gebilde aus Holz vor, bei dem jeden Besucher wohl schon die Nase den rechten Weg weisen könnte. Der Geruch von Heu und Stroh mischt sich hier mit dem der Tiere, und scheint fast schon aufdringlich, sofern man an diese "Duftmischung" nicht gewöhnt ist.
	`nHier kümmert sich `fMerick`7 um die verschiedensten Tierarten. Jeder findet bei dem stämmigen Zwerg etwas nach seinen Vorstellungen...
	`n`n
	Du näherst dich ihm, als er plötzlich herumwirbelt und seine Heugabel in deine ungefähre Richtung stre`(c`Tk`St. "`SAch,
	\'tschuldigung min '.($Char->sex?'Mädl':'Jung').', heb dich nit kommen hörn un heb gedenkt,
	du bischt sicha Cedrik, der ma widda sein Zwergenweitwurf ufbessern will. Naaahw, wat
	kann ich für disch tun?"');
}
elseif ($_GET['op']=='examinepet')
{
	$pet = getpet($_GET['id']);
	if (count($pet)==0)
	{
		output('`S"Ach, ich heb keen solches Tier da!"`), ruft der Zwerg!');
	}
	else
	{

		output('`S"Ai, ich heb wirklich n paar feine Viecher hier!",`) kommentiert der Zwerg.`n`n
		Kreatur: `&'.$pet['tpl_name'].'`n
		Beschreibung: `&'.$pet['tpl_description'].'`n
		`7Preis: `^'.$pet['tpl_gold'].'`& Gold, `%'.$pet['tpl_gems'].'`& Edelstein'.($pet['tpl_gems']==1?'':'e').'`n
		`n');
		addnav('Kaufen');
		addnav('Dieses Tier kaufen','stables.php?op=buypet&id='.$pet['tpl_id']);
	}

}
elseif($_GET['op']=='examine')
{
	$sql = 'SELECT * FROM mounts WHERE mountid='.$_GET['id'];
	$result = db_query($sql);
	if (db_num_rows($result)<=0){
		output('`S"Ach, ich heb keen solches Tier da!"`), ruft der Zwerg!');
	}
	else{
		$mount = db_fetch_assoc($result);
		$int_dksleft = $mount['mindk'] - $Char->dragonkills;
		output('`S"Ai, ich heb wirklich n paar feine Viecher hier!",`) kommentiert der Zwerg.`n`n');
		output('`7Kreatur: `&'.$mount['mountname'].'`n');
		output('`7Beschreibung: `&'.$mount['mountdesc'].'`n');
		output('`7Preis: `^'.$mount['mountcostgold'].'`& Gold, `%'.$mount['mountcostgems'].'`& Edelstein'.($mount['mountcostgems']==1?'':'e').'`n');
		output('`n');
		if($int_dksleft > 0)
		{
			output('`SAch, da bischt du noch zu unerfahren für, min '.($Char->sex?'Mädl':'Jung').'!
			Wennscht noch `b'.$int_dksleft.'`b Heldedaden mehr g\'moacht hascht, kannscht widderkommn!`n');
		}
		elseif($Char->hashorse==0)
		{
			addnav('Kaufen');
			addnav('Dieses Tier kaufen','stables.php?op=buymount&id='.$mount['mountid'],false,false,false,true,($Char->hashorse>0?'Du kannst nur 1 Tier gleichzeitig führen. '.strip_appoencode($playermount['mountname'],3).' in Zahlung geben?':''));
		}
		else
		{// Sicherheitsabfragen werden immer seltener gelesen, also automatischer Tiertausch komplett weg
			addnav('Kaufen');
			addnav('Du hast schon ein Tier','');
		}
	}
}
elseif ($_GET['op']=='buypet')
{
	$tpl_id = $_GET['id'];

	$pet = getpet($tpl_id);

	if (count($pet)==0)
	{
		output('`S"Ach, ich heb keen solches Tier da!"`), ruft der Zwerg!');
	}
	else
	{
		if ($Char->gold < $pet['tpl_gold'] || ($Char->gems+$petrepaygems) < $pet['tpl_gems'])
		{
			output('`)Merick schaut dich schief von der Seite an. `S"Ähm, was gläubst du was du hier machst? Kanns u nich sehen, dass '.$pet['tpl_name'].' `^'.$pet['tpl_gold'].'`& Gold und `%'.$pet['tpl_gems'].'`& Edelsteine kostet?`7"');
		}
		else
		{
			$feeddays = getsetting("daysperday",4);
			if ($Char->petid>0)
			{
				output('`)Du übergibst dein '.$playerpet['tpl_name'].' und bezahlst den Preis für dein neues Tier. Merick führt ein schönes neues `&'.$pet['tpl_name'].'`)-Exemplar  für dich heraus und gibt dir Futter für '.$feeddays.' Tage dazu!`n`n');
			}
			else
			{
				output('`)Du bezahlst den Preis für dein neues Tier und Merick führt ein schönes neues `&'.$pet['tpl_name'].'`)-Exemplar für dich heraus und gibt dir Futter für '.$feeddays.' Tage dazu!`n`n');
			}
			// delete old pet
			if($Char->petid > 0) {
				item_delete(' id='.$Char->petid);
			}
			// insert new pet
			$pet['tpl_hvalue'] = $Char->house;
			$Char->petid = intval(item_add($Char->acctid, $tpl_id, $pet));
			$Char->petfeed = date('Y-m-d H:i:s',time() + $feeddays * (3600*24 / getsetting('daysperday',4)));
			$goldcost = -$pet['tpl_gold'];
			$Char->gold += $goldcost;
			$gemcost = $petrepaygems - $pet['tpl_gems'];
			$Char->gems += $gemcost;
			debuglog(($goldcost <= 0?'spent ':'gained ') . abs($goldcost) . ' gold and ' . ($gemcost <= 0?'spent ':'gained ') . abs($gemcost) . ' gems trading for a new pet');
			// Recalculate so the selling stuff works right
			$playerpet = getpet((int)$Char->petid);
			$petrepaygems = round($playerpet['gems']*2/3,0);
		}
	}
}
elseif($_GET['op']=='buymount')
{
	getmount($Char->hashorse,true);
	$sql = 'SELECT * FROM mounts WHERE mountid='.$_GET['id'];
	$result = db_query($sql);
	if (db_num_rows($result)<=0){
		output('`S"Ach, ich heb keen solches Tier da!",`) ruft der Zwerg!');
	}
	else
	{
		$mount = db_fetch_assoc($result);
		if (($Char->gold+$repaygold) < $mount['mountcostgold'] || ($Char->gems+$repaygems) < $mount['mountcostgems'])
		{
			output('`)Merick schaut dich schief von der Seite an. `S"Ähm, was gläubst du was du hier machst? Kanns u nich sehen, dass '.$mount['mountname'].' `^'.$mount['mountcostgold'].'`& Gold und `%'.$mount['mountcostgems'].'`& Edelsteine kostet?`7"');
		}
		else
		{
			if ($Char->hashorse>0){
				output('`)Du übergibst dein '.$playermount['mountname'].' und bezahlst den Preis für dein neues Tier. Merick führt ein schönes neues `&'.$mount['mountname'].'`7-Exemplar  für dich heraus!`n`n');
				$Char->reputation--;
				Cache::delete(Cache::CACHE_TYPE_SESSION, 'playermount');
				$session['bufflist']['mount']=utf8_unserialize($mount['mountbuff']);
			}
			else{
				output('`)Du bezahlst den Preis für dein neues Tier und Merick führt ein schönes neues `&'.$mount['mountname'].'`)-Exemplar für dich heraus!`n`n');
			}

			$sql = 'UPDATE account_extra_info SET hasxmount=0,mountextrarounds=0,xmountname="",mount_sausage='.$mount['mount_sausage'].' WHERE acctid='.$Char->acctid;
			db_query($sql);

			$Char->hashorse=$mount['mountid'];
			$goldcost = $repaygold-$mount['mountcostgold'];
			$Char->gold+=$goldcost;
			$gemcost = $repaygems-$mount['mountcostgems'];
			$Char->gems+=$gemcost;
			debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new mount");
			$session['bufflist']['mount']=utf8_unserialize($mount['mountbuff']);
			// Recalculate so the selling stuff works right
			$repaygold = round($playermount['mountcostgold']*2/3,0);
			$repaygems = round($playermount['mountcostgems']*2/3,0);
			$session['bufflist']['mount']=utf8_unserialize($mount['mountbuff']);
		}
	}
}
elseif ($_GET['op']=='sellpet')
{
	getmount($Char->hashorse,true);
	item_delete(' id='.$Char->petid);
	$Char->gems += $petrepaygems;
	$Char->petid = 0;
	$Char->petfeed = '0000-00-00 00:00:00';
	output('`)So schwer es dir auch fällt, dich von deinem '.$playerpet['name'].' zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n
	Aber in dem Moment, in dem du die `%'.$petrepaygems.'`) Edelsteine erblickst, fühlst du dich gleich ein wenig besser.');
	debuglog('gained '.$petrepaygems.' gems selling their pet');
}
elseif($_GET['op']=='sellmount')
{
	if($playermount['creator'] == $Char->acctid)
	{
		db_query('DELETE FROM mounts WHERE mountid='.$Char->hashorse);
		debuglog('verkaufte seine eigene Zucht wieder bei Merick!');
		$Char->reputation-=5;
	}
	$Char->reputation-=2;
	$Char->gold+=$repaygold;
	$Char->gems+=$repaygems;
	$Char->hashorse=0;
	debuglog('gained '.$repaygold.' gold and '.$repaygems.' gems selling their mount');
	unset($session['bufflist']['mount']);
	Cache::delete(Cache::CACHE_TYPE_SESSION, 'playermount');
	user_set_aei(array('hasxmount' => 0, 'mountextrarounds' => 0, 'xmountname' => '', 'mount_sausage' => 0));

	output('`)So schwer es dir auch fällt, dich von deinem '.$playermount['mountname'].'`7 zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n
	Aber in dem Moment, in dem du die '.($repaygold>0?'`^'.$repaygold.'`) Gold '.($repaygems>0?' und ':''):'').($repaygems>0?'`%'.$repaygems.'`) Edelsteine':'').'`) erblickst, fühlst du dich gleich ein wenig besser.');

}
elseif ($_GET['op']=='futterpet')
{
	if (empty($_POST['days'])) {
		output('`0Das Futter kostet `^'.$playerpet['value1'].' Gold`0 und
		`%'.$playerpet['value2'].' Edelsteine`0 pro '.(getsetting('dayparts','1') > 1?'Tagesabschnitt':'Tag').'.`n
		<form action="stables.php?op=futterpet" method="post">
		Für wie viele '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage').' möchtest du Futter kaufen?
		<input type="text" name="days" value="0"> <input type="submit" value="Kaufen!">
		</form>');
		addnav('','stables.php?op=futterpet');
	}
	else {
		$days = (int)$_POST['days'];
		if ($Char->gold>=$playerpet['value1']*$days && $Char->gems>=$playerpet['value2']*$days) {
			$Char->gold -= $playerpet['value1']*$days;
			$Char->gems -= $playerpet['value2']*$days;
			if ($playerpet['value1']>0) {
				if ($playerpet['value2']>0) {
					$coststr = '`^'.($playerpet['value1']*$days).' Gold`0 und `%'.($playerpet['value2']*$days).' Edelsteine`0';
				}
				else $coststr = '`^'.($playerpet['value1']*$days).' Gold`0';
			}
			else {
				$coststr = '`%'.($playerpet['value2']*$days).' Edelsteine`0';
			}
			output('`)Merick nimmt die '.$coststr.' und gibt dir genug Futter, um dein(e/n) '.$playerpet['name'].' die nächsten '.$days.' Tage zu versorgen.`n');
			$oldtime = strtotime($Char->petfeed);
			if ($oldtime < time()) $oldtime = time();
			$newtime = $oldtime + $days * (3600*24 / getsetting("daysperday",4));
			$Char->petfeed = date('Y-m-d H:i:s',$newtime);
		}
		else {
			output('`)Du kannst das Futter nicht bezahlen. Merick weigert sich, dein Tier für dich durchzufüttern.');
		}
	}
}
elseif($_GET['op']=='futter')
{
	 if ($Char->gold>=$futtercost || ($Char->goldinbank + $Char->gold)>=$futtercost || $_GET['what']=='coupon')
	{
		getmount($Char->hashorse,true);

		$sql = 'SELECT mountextrarounds,hasxmount,xmountname FROM account_extra_info WHERE acctid='.$Char->acctid;
		$result = db_query($sql);
		$rowm = db_fetch_assoc($result);

		$buff = utf8_unserialize($playermount['mountbuff']);
		if($_GET['what']=='coupon') //Idee von plueschdrache.de
		{
			output('`7Dein '.$playermount['mountname'].'`7 macht sich gierig über den Gutschein her. So war das eigentlich nicht gedacht, aber wenns schmeckt, kann man nichts machen...`nDein '.$playermount['mountname'].'`7 ist vollständig regeneriert.');
			item_delete('tpl_id="feedcoupon" AND owner='.$Char->acctid,1);
		}
		else if ($session['bufflist']['mount']['rounds']-$rowm['mountextrarounds'] == $buff['rounds'])
		{
			output('`7Dein '.$playermount['mountname'].'`7 ist satt und rührt das vorgesetzte Futter nicht an. Darum gibt Merick dir dein Gold zurück.');
		}
		else if ($session['bufflist']['mount']['rounds']-$rowm['mountextrarounds'] > $buff['rounds']*0.5)
		{
			$futtercost=$futtercost/2;
			output('`7Dein '.$playermount['mountname'].'`7 nascht etwas von dem vorgesetzten Futter und lässt den Rest stehen. '.$playermount['mountname'].'`7 ist voll regeneriert.
			Da aber noch über die Hälfte des Futters übrig ist, gibt dir Merick 50% Preisnachlass.`nDu bezahlst nur '.$futtercost.' Gold.');
			$Char->reputation--;
			if ($Char->gold>=$futtercost)
			{
				$Char->gold-=$futtercost;
			}
			else
			{
				$Char->goldinbank -= ($futtercost - $Char->gold);
				$Char->gold = 0;
			}

		}
		else
		{
			if ($Char->gold>=$futtercost)
			{
				$Char->gold-=$futtercost;
			}
			else
			{
				$Char->goldinbank -= ($futtercost - $Char->gold);
				$Char->gold = 0;
			}

			output('`7Dein '.$playermount['mountname'].' macht sich gierig über das Futter her und frisst es bis auf den letzten Krümel.`n
			Dein '.$playermount['mountname'].' ist vollständig regeneriert und du gibst Merick die '.$futtercost.' Gold.');
			$Char->reputation--;
		}

		$session['bufflist']['mount']=$buff;
		$session['bufflist']['mount']['rounds']+=$rowm['mountextrarounds'];
		if ($rowm['hasxmount']==1)
		{
			$session['bufflist']['mount']['name']=$rowm['xmountname'].' `&('.$session['bufflist']['mount']['name'].'`&)';
		}
		$Char->fedmount=1;
	}
	else
	{
		output('`)Du hast nicht genug Gold dabei, um das Futter zu bezahlen. Merick weigert sich dein Tier für dich durchzufüttern und empfiehlt dir, im Wald nach einer grasbewachsenen Lichtung zu suchen.');
	}
}
elseif ($_GET['op']=='noname')
{
	output('`)Merick sieht dich zwar etwas zweifelnd an, erfüllt dir jedoch deinen Wunsch. Von nun an ist dein '.$playermount['mountname'].' `)wieder bekannt als... '.$playermount['mountname'].'.`n`n');
	$sql = 'UPDATE account_extra_info SET hasxmount=0,xmountname="" WHERE acctid='.$Char->acctid;
	db_query($sql);

	$arr_buff = $session['bufflist']['mount'];
	$mount_name = $playermount['mountname'];
	$mount_rounds = $arr_buff['rounds'];
	Cache::delete(Cache::CACHE_TYPE_SESSION, 'playermount');
	getmount($Char->hashorse,true);
	$session['bufflist']['mount']['name'] = $mount_name;
	$session['bufflist']['mount']['mountbuff']['rounds'] = $mount_rounds;

	addnav('Zu den Ställen','stables.php');
	page_footer();
}
elseif ($_GET['op']=='name')
{
	getmount($Char->hashorse,true);
	$n = $playermount['mountname'];
	$cost = $_GET['cost'];
	$msg = '';
	$pointsavailable=$Char->donation-$Char->donationspent;

	if ($pointsavailable < $cost) {
		output('Eine Taufe kostet '.$cost.' Punkte, aber du hast nur '.$pointsavailable.' Punkte.');
		addnav('Zu den Ställen','stables.php');
		page_footer();
	}

	if(isset($_POST['newname'])) {

		$newname = str_replace('`0','',stripslashes($_POST['newname']));

		// Alle anderen Tags als erlaubte Farbcodes rausschmeißen
		$newname = utf8_preg_replace('/[`][^'.regex_appoencode(1,false).']/','',$newname);

		if(mb_strlen($newname) == 0) {
			$msg.='Einfalls-Los, gefällig?`n';
		}

		if (mb_strlen($newname)>40) {
			$msg.='Der neuer Name ist zu lang, inklusive Farbcodes darf er nicht länger als 40 Zeichen sein.`n';
		}

		$colorcount = mb_substr_count($_POST['newname'],'`');
		if (getsetting('mount_maxcolors',10) != -1 && $colorcount>getsetting('mount_maxcolors',10))
		{
			$msg.='`0Du hast zu viele Farben im Namen benutzt. Du kannst maximal '.getsetting('mount_maxcolors',10).' Farbcodes benutzen.`n';
		}

		// Umbenennen!
		if (empty($msg)){
			$Char->donationspent+=$cost;
			$sql = 'UPDATE account_extra_info SET rename_mount=1,hasxmount=1,xmountname="'.db_real_escape_string($newname).'" WHERE acctid='.$Char->acctid;
			db_query($sql);
			output('`)Merick hebt zeremoniell seine Peitsche und verkündet:`n"`SUnd im Namen von Epona, Fury und Lassie taufe ich dich auf den Name '.$newname.'`S!`&"`n`n');

			$arr_buff = $session['bufflist']['mount'];
			$mount_name = $newname.' `&('.$playermount['mountname'].'`&)';
			$mount_rounds = $arr_buff['rounds'];
			Cache::delete(Cache::CACHE_TYPE_SESSION, 'playermount');
			getmount($Char->hashorse,true);
			$session['bufflist']['mount']['name'] = $mount_name;
			$session['bufflist']['mount']['mountbuff']['rounds'] = $mount_rounds;
			addnav('Zu den Ställen','stables.php');
			page_footer();
		}
		else{
			output('`b`$Falscher Name!`0`b`&`n'.$msg.'`n');
		}
	}

	$sql = 'SELECT mountextrarounds,hasxmount,xmountname FROM account_extra_info WHERE acctid='.$Char->acctid;
	$result = db_query($sql);
	$rowm = db_fetch_assoc($result);
	output('`bDein Tier (um)taufen`b`n`n
	`n`nDer Name deines treuen Freundes darf 40 Zeichen lang sein und Farbcodes enthalten.`n`n
	Dein Tier heißt bisher : `n
	'.($rowm['hasxmount']==1?$rowm['xmountname']:$n).'
	`n`n`0Wie soll dein Tier ab sofort heißen ?`n');
	rawoutput("<form action='stables.php?op=name&amp;cost=$cost' method='POST'>
				<input name='newname' id='newname' value=\"".
				(!empty($newname) ? $newname :
										($rowm['hasxmount']==1 ? $rowm['xmountname']:'')
									)."\" size=\"30\" maxlength=\"40\">");
	output('	`n`nVorschau: '.js_preview('newname').'
				`n`n<input type="submit" class="button" value="JA, Tier für '.$cost.' DP auf diesen Namen taufen!"></form>',true);
	addnav('','stables.php?op=name&cost='.$cost);
}

addnav('Spielen');
addnav('Hasenjagd','bunnyhunt.php');

if ($Char->hashorse>0 && $Char->fedmount==0)
{
	addnav('Begleiter-Futter');
	addnav('f?'.$playermount['mountname'].' füttern (`^'.$futtercost.'`0 Gold)','stables.php?op=futter');
	if(item_count('tpl_id="feedcoupon" AND owner='.$Char->acctid)>=1)
	{
		addnav('G?'.$playermount['mountname'].' mit Gutschein füttern','stables.php?op=futter&what=coupon');
	}
}
if ($Char->petid>0)
{
	addnav('Hauswächter-Futter');
	addnav('t?'.$playerpet['name'].' füttern','stables.php?op=futterpet');
}

$rowt = user_get_aei('hasxmount,rename_mount');
if ($rowt['hasxmount'] == 1 || $rowt['rename_mount'] == 1)
{
	$req=10;
}
else
{
	$req=100;
}
if (($pointsavailable>=$req) && ($Char->hashorse>0))
{
	addnav('Spezial');
	if ($rowt['hasxmount']==1)
	{
		addnav($playermount['mountname'].'`0 umtaufen (10 DP)','stables.php?op=name&cost=10');
    	addnav('Taufe aufheben','stables.php?op=noname',false,false,false,false,'Willst du wirklich den Namen deines Tieres aufgeben?');
	}
	elseif ($rowt['rename_mount']==1)
	{
		addnav($playermount['mountname'].'`0 taufen (10 DP)','stables.php?op=name&cost=10');
	}
	else
	{
		addnav($playermount['mountname'].'`0 taufen (100 DP)','stables.php?op=name&cost=100');
	}
}
if($Char->exchangequest==22)
{
	addnav('Nach einem Zahn fragen','exchangequest.php?op=stables');
}
$sql = 'SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1 AND creator=0 ORDER BY mountcategory,mountcostgems,mountcostgold';
$result = db_query($sql);
$category='';

$count = db_num_rows($result);

for ($i=0;$i<$count;$i++)
{
	$row = db_fetch_assoc($result);
	if ($category!=$row['mountcategory']){
		addnav($row['mountcategory']);
		$category = $row['mountcategory'];
	}
	$row['mountname'] = strip_appoencode($row['mountname'],3);
	addnav('Betrachte '.$row['mountname'].'`0','stables.php?op=examine&id='.$row['mountid'],false,false,false,false);
}
if ($Char->house>0) {

	$result = item_tpl_list_get(' stables_pet>0 ', ' ORDER BY tpl_gold ASC, tpl_gems ASC ','tpl_name,tpl_id');
	if (db_num_rows($result)>0)
	{
		addnav('Hauswächter');
		while ($row = db_fetch_assoc($result)) {
			addnav('Betrachte '.$row['tpl_name'].'`0','stables.php?op=examinepet&id='.$row['tpl_id'],false,false,false,false);
		}
	}
}
if ($Char->hashorse>0)
{
	getmount($Char->hashorse,true);
	if($playermount['creator'] != 0)
	{
		//Die Werte stammen aus der
		//cost_gold_basis und cost_gems_basis
		$repaygold = getsetting('stables_mount_editor_cost_gold_basis',5000);
		$repaygems = getsetting('stables_mount_editor_cost_gems_basis',20);
	}
	else
	{
		$repaygold = round($playermount['mountcostgold']*0.45,0);
		$repaygems = round($playermount['mountcostgems']*0.45,0);
	}
	output('`n`n`)Merick würde dir '.$playermount['mountname'].' für `^'.$repaygold.'`) Gold und `%'.$repaygems.'`) Edelsteine abkaufen.');
	addnav('Sonstiges');
	if($playermount['creator'] == $Char->acctid)
	{
		$arr_aei = user_get_aei('xmountname');
		addnav('Verkaufe '.$arr_aei['xmountname'],'stables.php?op=sellmount',false,false,false,false,'Möchtest Du deine eigene Zucht wirklich verkaufen? Sie ist dann für immer weg!');
	}
	else
	{
		addnav('Verkaufe '.$playermount['mountname'],'stables.php?op=sellmount',false,false,false,false,'Möchtest du dein Tier wirklich verkaufen?');
	}
}
if ($Char->petid>0)
{
	if ($Char->hashorse==0) addnav("Sonstiges");
	output('`n`n`)Merick würde dir '.$playerpet['name'].' für `%'.$petrepaygems.'`) Edelsteine abkaufen.');
	addnav('Verkaufe '.$playerpet['name'],'stables.php?op=sellpet',false,false,false,false);
}
page_footer();
?>