<?php
/**
* inside_houses.php: Wohnviertel - Hausinnenraum
* @author anpera / überarbeitet von talion <t@ssilo.de>
* @version DS-E V/3
*/

$str_filename = basename(__FILE__);

require_once('common.php');
require_once(LIB_PATH.'house.lib.php');

// Zentraler Ausgabezwischenspeicher. Wird am Ende des Scripts an output gesendet
$str_out = '';

// Enthält Ausbau
$arr_current_build = array();

page_header('Im Inneren eines Hauses');

addcommentary();
checkday();

// Überprüfen, ob Haus gegeben
if (isset($_GET['id']))
{
	$session['housekey']=(int)$_GET['id'];
}
if (!$session['housekey'])
{
	redirect("houses.php");
}

// Haus abrufen
$sql = 'SELECT houses.*, 
a.superuser,
a.acctid,
a.name,
a.login,
a.expedition,
a.imprisoned,
a.lastip,
a.uniqueid
FROM houses LEFT JOIN accounts a ON (owner=a.acctid) WHERE houseid='.$session['housekey'];
$result = db_query($sql);
$row = db_fetch_assoc($result);
db_free_result($result);

// Bei bestimmten Baustatus: Haus betreten verboten!
if($row['build_state'] == HOUSES_BUILD_STATE_EMPTY || 
	$row['build_state'] == HOUSES_BUILD_STATE_INIT || 
	$row['build_state'] == HOUSES_BUILD_STATE_SELL)
{
	redirect('houses.php');
}

// Schatztruhen-Maximum festlegen
$int_goldmax = (int)getsetting('housetrsgoldmax',15000);
$int_gemmax = (int)getsetting('housetrsgemsmax',50);
// Kosten für Bau
$int_upgr_gold = 0;
$int_upgr_gems = 0;

// Aktueller Ausbau?
if($row['status']) {
	$arr_current_build = $g_arr_house_builds[$row['status']];
}


// Boni durch Ausbauten
if(isset($arr_current_build)) {

	if(isset($arr_current_build['goldmulti'])) {
		$int_goldmax = round($arr_current_build['goldmulti'] * $int_goldmax);
	}

	if(isset($arr_current_build['gemmulti'])) {
		$int_gemmax = round($arr_current_build['gemmulti'] * $int_gemmax);
	}
}

if($row['build_state'] == HOUSES_BUILD_STATE_IP) {

	// Kosten dieses Ausbaus ermitteln
	if(isset($g_arr_house_builds[$row['extension']])) {
		$int_upgr_gold = $g_arr_house_builds[$row['extension']]['goldcost'];
		$int_upgr_gems = $g_arr_house_builds[$row['extension']]['gemcost'];
	}
	else {

		$int_upgr_gold = 500000;
		$int_upgr_gems = 500;

		// Ausbau wählen (Temp)
		if($row['owner'] != $session['user']['acctid']) {
			output('`$Dieses Haus befindet sich im Ausbau. Dazu muss nach dem neuen System zuerst ein Ausbautyp bestimmt werden!
						Bevor der Hauseigentümer dies nicht getan hat, kann das Haus nicht genutzt werden.`n
						Sämtliche Werte im Hausschatz bleiben natürlich erhalten.`n`n
						(In dringenden Sonderfällen schreibe bitte eine Anfrage an die Administration!)');
			addnav('Zurück zum Wohnviertel','houses.php?op=enter');
			page_footer();
		}
		else {
			if($_GET['act'] != 'build_buildings') {
				output('`$Dieses Haus befindet sich im Ausbau. Dazu musst du nach dem neuen System zuerst einen Ausbautyp wählen! Bevor du dies nicht getan hast, kann das Haus nicht genutzt werden.`n
						Sämtliche Werte im Hausschatz bleiben natürlich erhalten.`n`n
						(In dringenden Sonderfällen schreibe bitte eine Anfrage an die Administration!)');
				addnav('Ausbau jetzt wählen!',$str_filename.'?act=build_buildings');
				addnav('Zurück zum Wohnviertel','houses.php?op=enter');
				page_footer();
			}
		}
	}

}
elseif($row['build_state'] == HOUSES_BUILD_STATE_EXT) {

	$arr_current_extension = db_fetch_assoc(db_query('SELECT level,type,loc,content,id FROM house_extensions WHERE id='.$row['extension']));
	$str_type = $arr_current_extension['type'];
	$arr_current_extension['content'] = utf8_unserialize($arr_current_extension['content']);

	// Kosten dieser Erweiterung ermitteln
	$int_upgr_gold = $g_arr_house_extensions[$str_type]['goldcost'];
	$int_upgr_gems = $g_arr_house_extensions[$str_type]['gemcost'];

	$arr_tmp = house_calc_ext_costs($int_upgr_gold,$int_upgr_gems,$arr_current_extension['level']);

	// Bei Gemächern: + Baukosten
	if($g_arr_house_extensions[$str_type]['room'] === true) {
		// Auch noch JS-Anzeige in case 'man_rooms' berücksichtigen!
		switch($arr_current_extension['loc']) {
			case HOUSES_ROOM_1ST: $arr_tmp['gems'] += 10; break;
			case HOUSES_ROOM_2ND: $arr_tmp['gems'] += 20; break;
			case HOUSES_ROOM_ROOF: $arr_tmp['gems'] += 40; break;
			case HOUSES_ROOM_TOWER: $arr_tmp['gems'] += 80; break;
		}

	}

	$int_upgr_gems = $arr_tmp['gems'];
	$int_upgr_gold = $arr_tmp['gold'];

	// Mit Gutschrift verrechnen, falls gegeben
	if(isset($arr_current_extension['content']['bonus'])) {
		$int_upgr_gold = max($int_upgr_gold - $arr_current_extension['content']['bonus']['gold'],0);
		$int_upgr_gems = max($int_upgr_gems - $arr_current_extension['content']['bonus']['gems'],0);
	}

	unset($arr_tmp);

}

// Evtl. Schatztruhen-Maximum anpassen!
$int_goldmax = max($int_goldmax,$int_upgr_gold);
$int_gemmax = max($int_gemmax,$int_upgr_gems);

// Wenn Überfluß: Minimieren
if($row['gold'] > $int_goldmax || $row['gems'] > $int_gemmax) {
	$str_log = '`^Code-Fehler(?) im Wohnviertel: 
				Hausschatz (Nr. '.$row['houseid'].', '.get_house_state($row['status'],$row['build_state'],false,false).') überfüllt:
				'.$row['gold'].'Gold, '.$row['gems'].'Gems.';
	$row['gold'] = min($row['gold'],$int_goldmax);
	$row['gems'] = min($row['gems'],$int_gemmax);
	$str_log .=  ' Wurde zurückgesetzt auf: '.$row['gold'].'Gold, '.$row['gems'].'Gems';
	// talion: Keine Ahnung, ob das noch benötigt wird; loggen wir einfach mal diese Fälle!
	db_query('UPDATE houses SET gold='.$row['gold'].',gems='.$row['gems'].' WHERE houseid='.$row['houseid']);
	systemlog($str_log);
}
// END Schatztruhen-Maximum


//Tretmine
if (isset($_GET['outside']))
{
	$trick=utf8_unserialize($row['trick']);
	if ($trick['dung'])
		{
		if (e_rand(1,10)==5)
		{
			if (e_rand(1,2)==2)
			{
				systemmail($session['user']['acctid'],"`)Tretmine!`0","`&Irgendein Schmutzfink hat einen ganzen Haufen $trick[dung]`& am Eingang zum Haus versteckt und ausgerechnet du bist voll reingetreten!`nWas für ein Mist...");
				systemmail($trick['dungid'],"`^Schabernack geglückt`0",$session['user']['name']."`& ist soeben aus vollem Schritt in den $trick[dung]`& getreten, den du am Eingang zu $row[housename]`& plaziert hast!");
				insertcommentary($session['user']['acctid'],': `)ist beim Betreten des Hauses aus vollem Schritt in eine Ladung '.$trick['dung'].'`) getreten, die jemand gemeinerweise am Eingang versteckt hatte.','house-'.$row['houseid']);
			}
			else
			{
				systemmail($session['user']['acctid'],"`)Glück gehabt!`0","`&Irgendein Schmutzfink hat einen ganzen Haufen $trick[dung]`& am Eingang zum Haus versteckt.`nAber clever und vorausschauend wie du bist hast du das natürlich sofort entdeckt und den Mist in der nächsten Hecke entsorgt.`nDich erwischt man eben nicht so leicht!");
				systemmail($trick['dungid'],"`4Schabernack gescheitert`0","`&Der $trick[dung]`&, den du am Eingang zu $row[housename]`& plaziert hast, wurde soeben entdeckt und entsorgt!`nSchade...");
			}
			unset($trick['dung']);
			unset($trick['dungid']);
			$trick_s=utf8_serialize($trick);
			$sql = "UPDATE houses SET trick='".db_real_escape_string($s_trick)."' WHERE houseid=".$_GET['id'];
			db_query($sql);
		}
	}
}

// Op-Determinante
$str_act = $_GET['act'];

// Main-Switch
switch($str_act) {


	case 'takegold': // Gold mitnehmen
	{
		output(house_get_title('Gold mitnehmen'));

		if ($row['build_state'] == HOUSES_BUILD_STATE_IP || $row['build_state'] == HOUSES_BUILD_STATE_EXT)
		{
			output('Hier wird gearbeitet! Du wirst dich doch wohl nicht an der Baukasse vergreifen...`n');
			addnav('Zurück zum Haus',$str_filename);
		}
		else
		{
			$rowe = user_get_aei('goldin');
			$maxtfer = $session['user']['level']*getsetting("transferperlevel",25)*4;
			$maxtfer = max($maxtfer-$rowe['goldin'],0);
			if ($row['owner'] != $session['user']['acctid']) {
				$sql = 'SELECT gold,chestlock FROM keylist WHERE value1='.$row['houseid'].' AND type='.HOUSES_KEY_DEFAULT.' AND owner='.$session['user']['acctid'];
				$res = db_query($sql);
				$row2 = db_fetch_assoc($res);
			}
			else {
				$row2['chestlock'] = 0;
			}
			if (($row2['chestlock']&2)==0)
			{
				if (!isset($_POST['gold']))
				{
					output('`tEs befindet sich `^'.$row['gold'].'`t Gold in der Schatztruhe des Hauses.
					`nDu darfst heute noch `^'.$maxtfer.'`t Gold mitnehmen.
					`n(Leerlassen, um Maximum zu entnehmen.)`0
					<form action="'.$str_filename.'?act=takegold" method="POST">
					`nWieviel Gold mitnehmen? <input type="text" id="gold" name="gold">`n`n
					<input type="submit" class="button" value="Mitnehmen">
					</form>
					'.focus_form_element('gold'));
					addnav('',$str_filename.'?act=takegold');
				}
				else
				{
					$amt=abs((int)$_POST['gold']);
					if($amt == 0) {	// Maximum bei leerem Feld
						$amt = min($maxtfer,$row['gold']);
					}
					if ($amt>$row['gold'])
					{
						output("`tSo viel Gold ist nicht mehr da.");
					}
					else if ($maxtfer<$amt)
					{
						output("`tDu darfst maximal `^$maxtfer`t Gold auf einmal nehmen.");
					}
					else if ($amt<0)
					{
						output("`tWenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.");
					}
					else if($amt > 0)
					{
						$row['gold']-=$amt;
						$session['user']['gold']+=$amt;
						user_set_aei(array('goldin'=>$rowe['goldin']+$amt));
						$sql = "UPDATE houses SET gold=$row[gold] WHERE houseid=".$row['houseid']."";
						db_query($sql);
						output('`tDu hast `^'.$amt.'`t Gold genommen. Insgesamt befindet sich jetzt noch `^'.$row['gold'].'`t Gold im Haus.');
						$goldspent=$row2['gold'];
						$goldspent-=$amt;
						if ($row['owner'] != $session['user']['acctid'])
						{
							$sql = "UPDATE keylist SET gold=$goldspent WHERE value1=".$row['houseid']." AND owner=".$session['user']['acctid']."";
							db_query($sql);
						}
						$sql = 'INSERT INTO commentary
										(postdate,section,author,comment)
									VALUES
										(now(),"house-'.$row['houseid'].'",'.$session['user']['acctid'].',": `\$nimmt `^'.$amt.'`\$ Gold.")';
						db_query($sql);
					}
					else {
						output('`tIrgendwie mag der Schatz nicht so ganz, wie du willst..');
					}
				}
			}
			else
			{
				output("`&Der Hausherr hat ein schweres, doppeltes Sicherheitsschloss an der Truhe angebracht, dass diese vor unerwünschtem Zugriff schützt.`nDa du keinen Schlüssel für dieses Schloss hast, sieht es wohl so aus als ob der Hausherr nicht will, dass du dich weiterhin an seinen Reichtümern vergreifst.`nDas tut mir aber leid...");
			}
			addnav("Zurück zum Haus","$str_filename");
		}
	break;
	}// END takegold

	case 'givegold': // Gold deponieren
	{
		output(house_get_title('Gold einlagern'));

		addnav("Zurück zum Haus",$str_filename);

		$rowe = user_get_aei('goldout');
		$maxout = $session['user']['level']*getsetting("maxtransferout",25);
		$transleft = max($maxout - $rowe['goldout'],0);
		if ($row['owner'] != $session['user']['acctid']) {
			$sql = 'SELECT gold,chestlock FROM keylist WHERE value1='.$row['houseid'].' AND type='.HOUSES_KEY_DEFAULT.' AND owner='.$session['user']['acctid'];
			$res = db_query($sql);
			$row2 = db_fetch_assoc($res);
		}
		else {
			$row2['chestlock'] = 0;
		}
		if (($row2['chestlock']&2)==0)
		{
			if (!isset($_POST['gold']))
			{
				output('`tEs befindet sich `^'.$row['gold'].'`t Gold in der Schatztruhe des Hauses.
				`nDu darfst heute noch `^'.$transleft.'`t Gold deponieren.
				`n(Feld leerlassen, um Maximum einzuzahlen)`0
				<form action="'.$str_filename.'?act=givegold" method="POST">
				`nWieviel Gold deponieren? <input type="text" id="gold" name="gold">
				`n`n<input type="submit" class="button" value="Deponieren">
				</form>
				'.focus_form_element('gold'));
				addnav('',$str_filename.'?act=givegold');
			}
			else
			{

				$amt=abs((int)$_POST['gold']);
				if($amt == 0) {	// Maximum
					$amt = min($transleft, round($int_goldmax)-$row['gold']);
					$amt = min($amt,$session['user']['gold']);
				}
				if ($amt>$session['user']['gold'])
				{
					output("`tSo viel Gold hast du nicht dabei.");
				}
				else if ($row['gold']>round($int_goldmax))
				{
					output("`tDer Schatz ist voll.");
				}
				else if ($amt>(round($int_goldmax)-$row['gold']))
				{
					output("`tDu gibst alles, aber du bekommst beim besten Willen nicht so viel in den Schatz.");
				}
				else if ($amt<0)
				{
					output("`tWenn du etwas aus dem Schatz nehmen willst, versuche nicht, etwas negatives hineinzutun.");
				}
				else if ($rowe['goldout']+$amt > $maxout)
				{
					output("`tDu darfst nicht mehr als `^$maxout`t Gold pro Tag deponieren.");
				}
				else if ($amt > 0)
				{
					$row['gold']+=$amt;
					$session['user']['gold']-=$amt;
					user_set_aei(array('goldout'=>$rowe['goldout']+$amt));
					output('`tDu hast `^'.$amt.'`t Gold deponiert. Insgesamt befinden sich jetzt `^'.$row['gold'].'`t Gold im Haus.');
					$goldspent=$row2['gold'];
					$goldspent+=$amt;
					$sql = 'UPDATE houses SET gold='.$row['gold'].' WHERE houseid='.$row['houseid'];
					db_query($sql);
					if ($row['owner'] != $session['user']['acctid'])
					{
						$sql = "UPDATE keylist SET gold=$goldspent WHERE value1=".$row['houseid']." AND owner=".$session['user']['acctid']."";
						db_query($sql);
					}
					$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row['houseid']."',".$session['user']['acctid'].",': `@deponiert `^$amt`@ Gold.')";
					db_query($sql);
				}
				else {
					output('`tIrgendwie mag der Schatz nicht so ganz, wie du willst..');
				}
			}
		}
		else
		{
			output("`&Der Hausherr hat ein schweres, doppeltes Sicherheitsschloss an der Truhe angebracht, dass diese vor unerwünschtem Zugriff schützt.`nDa du keinen Schlüssel für dieses Schloss hast, sieht es wohl so aus als ob der Hausherr nicht will, dass du dich weiterhin an seinen Reichtümern vergreifst.`nDas tut mir aber leid...");
		}

	break;
	}// END givegold

	case 'takegems': // Edelsteine mitnehmen
	{
		output(house_get_title('Edelsteine mitnehmen'));

		if ($row['build_state'] == HOUSES_BUILD_STATE_IP || $row['build_state'] == HOUSES_BUILD_STATE_EXT)
		{
			output('Hier wird gearbeitet! Du wirst dich doch wohl nicht an der Baukasse vergreifen...`n');
			addnav('Zurück zum Haus',$str_filename);
		}
		else
		{
			$rowe = user_get_aei('gemsin');
			$maxtfer = max(getsetting('housemaxgemsout',10) - $rowe['gemsin'],0);
			if($row['owner'] != $session['user']['acctid']) {
				$sql = 'SELECT gems,chestlock FROM keylist WHERE value1='.$row['houseid'].' AND type='.HOUSES_KEY_DEFAULT.' AND owner='.$session['user']['acctid'];
				$res = db_query($sql);
				$row2 = db_fetch_assoc($res);
			}
			else {
				$row2['chestlock'] = 0;
			}
			/*if($session['user']['dragonkills']<2)
			{
				$maxtfer=min($maxtfer,$row2['gems'] + $session['user']['dragonkills'] +1);
			}*/
			if (($row2['chestlock']&1)==0)
			{
				if (!$_POST['gems'])
				{
					output('`tEs befinden sich `#'.$row['gems'].'`t Edelsteine in der Schatztruhe des Hauses.
					`nDu darfst heute noch `^'.$maxtfer.'`t Edelsteine mitnehmen.
					`n`0<form action="'.$str_filename.'?act=takegems" method="POST">
					`nWieviele Edelsteine mitnehmen?
					<input type="text" id="gems" name="gems">
					`n`n<input type="submit" class="button" value="Mitnehmen">
					</form>
					'.focus_form_element('gems'));
					addnav('',$str_filename.'?act=takegems');
				}
				else
				{
					$amt=abs((int)$_POST['gems']);
					if ($amt>$row['gems'])
					{
						output('`tSo viele Edelsteine sind nicht mehr da.');
					}
					else if ($amt<0)
					{
						output('`tWenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.');
					}
					else if ($maxtfer<$amt)
					{
						output("`tDu darfst maximal `^$maxtfer`t Edelsteine pro Tag nehmen.");
					}
					else if($amt > 0)
					{
						$row['gems']-=$amt;
						$session['user']['gems']+=$amt;
						user_set_aei(array('gemsin'=>$rowe['gemsin']+$amt));
						$sql = 'UPDATE houses SET gems='.$row['gems'].' WHERE houseid='.$row['houseid'];
						db_query($sql);
						output('`tDu hast `#'.$amt.'`t Edelsteine genommen. Insgesamt befinden sich jetzt noch `#'.$row['gems'].'`t Edelsteine im Haus.');
						$gemsspent=$row2['gems'];
						$gemsspent-=$amt;
						// Nur aktualisieren, wenn nicht Hauseigentümer
						if ($row['owner'] != $session['user']['acctid'])
						{
							$sql = "UPDATE keylist SET gems=$gemsspent WHERE value1=".$row['houseid']." AND owner=".$session['user']['acctid']."";
							db_query($sql);
						}
						$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row['houseid']."',".$session['user']['acctid'].",': `\$nimmt `#$amt`\$ Edelsteine.')";
						db_query($sql);
					}
					else {
						output('`tIrgendwie mag der Schatz nicht so ganz, wie du willst..');
					}
				}
			}
			else
			{
				output("`&Der Hausherr hat ein schweres, doppeltes Sicherheitsschloss an der Truhe angebracht, dass diese vor unerwünschtem Zugriff schützt.`nDa du keinen Schlüssel für dieses Schloss hast, sieht es wohl so aus als ob der Hausherr nicht will, dass du dich weiterhin an seinen Reichtümern vergreifst.`nDas tut mir aber leid...");
			}
			addnav("Zurück zum Haus","$str_filename");
		}

	break;
	}// END takegems

	case 'givegems': // Edelsteine deponieren
	{
	output(house_get_title('Edelsteine einlagern'));

		if($row['owner'] != $session['user']['acctid']) {
			$sql = 'SELECT gems,chestlock FROM keylist WHERE value1='.$row['houseid'].' AND type='.HOUSES_KEY_DEFAULT.' AND owner='.$session['user']['acctid'];
			$res = db_query($sql);
			$row2 = db_fetch_assoc($res);
		}
		else {
			$row2['chestlock'] = 0;
		}
		if (($row2['chestlock']&1)==0)
		{
			if (!$_POST['gems'])
			{
				output('`0<form action="'.$str_filename.'?act=givegems" method="POST">
				`nWieviele Edelsteine deponieren?
				<input type="text" id="gems" name="gems">
				`n`n<input type="submit" class="button" value="Deponieren">
				</form>
				'.focus_form_element('gems'));
				addnav('',$str_filename.'?act=givegems');
			}
			else
			{
				$amt=abs((int)$_POST['gems']);

				if ($amt>$session['user']['gems'])
				{
					output("`tSo viele Edelsteine hast du nicht.");
				}
				else if ($row['gems']>=round($int_gemmax))
				{
					output("`tDer Schatz ist voll.");
				}
				else if ($amt>(round($int_gemmax)-$row['gems']))
				{
					output("`tDu gibst alles, aber du bekommst beim besten Willen nicht so viel in den Schatz.");
				}
				else if ($amt<0)
				{
					output("`tWenn du etwas aus dem Schatz nehmen willst, versuche nicht, etwas negatives hineinzutun.");
				}
				else if($amt > 0)
				{
					$row['gems']+=$amt;
					$session['user']['gems']-=$amt;
					$sql = 'UPDATE houses SET gems='.$row['gems'].' WHERE houseid='.$row['houseid'];
					db_query($sql);
					output('`tDu hast `#'.$amt.'`t Edelsteine deponiert. Insgesamt befinden sich jetzt `#'.$row['gems'].'`t Edelsteine im Haus.');

					// Nur aktualisieren, wenn nicht Hauseigentümer
					if ($row['owner'] != $session['user']['acctid'])
					{
						$sql = "UPDATE keylist SET gems=gems+$amt WHERE value1=".$row['houseid']." AND owner=".$session['user']['acctid']."";
						db_query($sql);
					}
					$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row['houseid']."',".$session['user']['acctid'].",': `@deponiert `#$amt`@ Edelsteine.')";
					db_query($sql);
					if ($amt>20)
					{
						debuglog("Deponiert $amt Edelsteine in einem Haus.");
					}
				}
				else {
					output('`tIrgendwie mag der Schatz nicht so ganz, wie du willst..');
				}
			}
		}
		else
		{
			output("`&Der Hausherr hat ein schweres, doppeltes Sicherheitsschloss an der Truhe angebracht, dass diese vor unerwünschtem Zugriff schützt.`nDa du keinen Schlüssel für dieses Schloss hast, sieht es wohl so aus als ob der Hausherr nicht will, dass du dich weiterhin an seinen Reichtümern vergreifst.`nDas tut mir aber leid...");
		}

		addnav("Zurück zum Haus","$str_filename");
	break;
	}// END givegems

	case 'build_menu': //Bau-Menü
	{
		output(house_get_title('Bauen an '.$row['housename'].'`0'));

		if ($session['user']['turns'] < 1)
		{
			output('`$Du bist zu erschöpft, um heute noch irgendetwas zu bauen. Warte bis morgen.`0');
			addnav('Zurück zum Haus',$str_filename);
			addnav('W?Zurück zum Wohnviertel','houses.php');
			page_footer();
		}

		// Upgrade-Kosten
		$upgold = $int_upgr_gold;
		$upgems = $int_upgr_gems;

		$goldtopay=max($upgold-$row['gold'],0);
		$gemstopay=max($upgems-$row['gems'],0);

		// Gebaut
		if(isset($_POST['gold']) || isset($_POST['gems'])) {
			$paidgold=(int)$_POST['gold'];
			$paidgems=(int)$_POST['gems'];

			if(isset($_POST['complete']) && $session['user']['gold'] >= $goldtopay && $session['user']['gems'] >= $gemstopay) {
				$paidgold = $goldtopay;
				$paidgems = $gemstopay;
			}

			if ($session['user']['gold']<$paidgold || $session['user']['gems']<$paidgems)
			{
				output('`tDu hast nicht genug dabei!');
				addnav('Zurück zum Haus',$str_filename);
				addnav('W?Zurück zum Wohnviertel','houses.php');
				page_footer();
			}
			else if ($paidgold<0 || $paidgems<0)
			{
				output('`tVersuch hier besser nicht zu beschummeln.');
				addnav('Zurück zum Haus',$str_filename);
				addnav('W?Zurück zum Wohnviertel','houses.php');
				page_footer();
			}
			else
			{
				output("`tDu baust für `^$paidgold`t Gold und `#$paidgems`t Edelsteine an deinem Haus...`n");

				$row['gold']+=$paidgold;
				$session['user']['gold']-=$paidgold;

				output('`nDu verlierst einen Waldkampf.');
				$session['user']['turns']--;

				if ($row['gold']>$upgold && $row['gold'] > $int_goldmax)
				{
					output('`nDu hast die kompletten Goldkosten bezahlt und bekommst das überschüssige Gold zurück.');
					$session['user']['gold']+=$row['gold']-$upgold;
					$row['gold']=$upgold;
				}

				//Edelsteine
				$row['gems']+=$paidgems;
				$session['user']['gems']-=$paidgems;
				if ($row['gems']>$upgems && $row['gems'] > $int_gemmax)
				{
					output('`nDu hast die kompletten Edelsteinkosten bezahlt und bekommst überschüssige Edelsteine zurück.');
					$session['user']['gems']+=$row['gems']-$upgems;
					$row['gems']=$upgems;
				}

				$goldtopay=max($upgold-$row['gold'],0);
				$gemstopay=max($upgems-$row['gems'],0);
				$done = floor(100-((100*$goldtopay/($upgold+1))+(100*$gemstopay/($upgems+0.01)))/2);
				output("`nDeine Hauserweiterung ist damit zu `$$done%`t fertig. Du musst noch `^$goldtopay`t Gold und `#$gemstopay `t Edelsteine bezahlen, bis du fertig bist.");
				$sql = 'UPDATE houses SET gems='.$row['gems'].',gold='.$row['gold'].' WHERE houseid='.$row['houseid'];
				db_query($sql);

				if ($row['gems']>=$upgems && $row['gold']>=$upgold)
				{
					// Kosten bezahlt ?
					redirect("$str_filename?act=build_finish");
				}

				addnav("Zurück zum Haus","$str_filename");
				addnav("W?Zurück zum Wohnviertel","houses.php");
				page_footer();

			}
		}

		// im Ausbau ?
		if ($row['gems']>=$upgems && $row['gold']>=$upgold)
		{
			// Kosten bezahlt ?
			redirect($str_filename.'?act=build_finish');
		}

		output('`tDu schaust, wie weit du mit deinem derzeitigen Ausbauvorhaben bereits bist.`n`n');

		$done=round(100-((100*$goldtopay/($upgold+1))+(100*$gemstopay/($upgems+0.01)))/2);
		output("`nEs ist zu `$$done%`t fertig. Du musst noch `^$goldtopay`t Gold und `#$gemstopay `t Edelsteine bezahlen.`nWillst du jetzt weiter bauen?`n`n");

		$str_lnk = $str_filename.'?act=build_menu';
		addnav('',$str_lnk);
		output("`0<form action=\"".$str_lnk."\" method='POST'>",true);
		if($goldtopay > 0) {
			output("`nWieviel Gold zahlen? <input type='text' name='gold'>`n",true);
		}
		if($gemstopay > 0) {
			output("`nWieviele Edelsteine? <input type='text' name='gems'>`n",true);
		}
		output("<input type='submit' class='button' value='Bauen!'>",true);
		if($session['user']['gold'] >= $goldtopay && $session['user']['gems'] >= $gemstopay) {
			output('`n`n`tDu verfügst eigentlich über die Mittel, diesen Ausbau gleich zu vollenden. Willst du dies nun tun?`0`n`n
				<input type="submit" value="Ja, fertigbauen!" name="complete" class="button">',true);
		}
		output('</form>');

		addnav('Zurück zum Haus',$str_filename);
		addnav('W?Zurück zum Wohnviertel','houses.php');
	break;
	}// END build_menu

	case 'build_buildings': // Ausbau
	{
		$str_out .= house_get_title('Ausbau in '.$row['housename'].'`0');
		if ($session['user']['dragonkills']<getsetting('houseextdks',10))
		{
			output('`tDu würdest ja dein Haus gerne weiter ausbauen, doch entspricht dein derzeitiger Rang wohl noch nicht den Anforderungen! Die Stadtverwaltung '.getsetting('townname','Atrahor').'s rät dir, erst noch einige Heldentaten zu vollbringen - so ungefähr '.(getsetting('houseextdks',10)-$session['user']['dragonkills']).' - und dann noch einmal um einen Ausbau zu ersuchen!');

			addnav('Zurück zum Haus',$str_filename);
			page_footer();
			exit;
		}

		// Auswahl getroffen?
		$int_choose=(int)$_REQUEST['buildid'];

		if($int_choose > 0)
		{

			// Überprüfen, ob Anbauten existieren, die in gewähltem Ausbau nicht existieren dürfen
			if(sizeof($g_arr_house_builds[$int_choose]['forbidden_ext'])) {
				$str_forbidden = implode(',',array_keys($g_arr_house_builds[$int_choose]['forbidden_ext']));
				$res = db_query(
						'	SELECT type FROM house_extensions
							WHERE houseid='.$row['houseid'].' AND loc IS null AND level > 0
									AND type IN('.$str_forbidden.')');
				if(db_num_rows($res)) {
					$str_out .= '`$In eine'.($g_arr_house_builds[$int_choose]['sex'] ? 'r' : 'm').' '.$g_arr_house_builds[$int_choose]['name'].' sind folgende Anbauten nicht möglich:`n`n';
					while($e = db_fetch_assoc($res)) {
						$str_out .= ' - '.$g_arr_house_extensions[$e['type']]['name'].'`n';
					}
					$str_out .= '`nWenn du dennoch dein Haus wie gewünscht ausbauen möchtest, musst du zunächst diese Anbauten abreißen!`0';

					addnav('Anbauten ansehen',$str_filename.'?act=build_extensions');
					addnav('Anderen Ausbau wählen',$str_filename.'?act=build_buildings');
					addnav('Zurück zum Haus',$str_filename);

					output($str_out);
					page_footer();
				}
				db_free_result($res);
			}

			if(!isset($_GET['ok'])) {
				$str_warning = house_get_rip_warning($row['houseid'],$int_choose);
				if(!empty($str_warning)) {
					$str_out .= $str_warning;
					$str_out .= '`n`n`&Bist du dir dennoch sicher, den Ausbau beginnen zu wollen?';
					addnav('JA, ich will!',$str_filename.'?act=build_buildings&buildid='.$int_choose.'&ok=1',false,false,false,false);
					addnav('Zurück');
					addnav('Anderen Ausbau wählen',$str_filename.'?act=build_buildings');
					addnav('Zurück zum Haus',$str_filename);

					output($str_out);
					page_footer();
				}
			}

			// Starten
			db_query('UPDATE houses SET extension="'.$int_choose.'", build_state='.HOUSES_BUILD_STATE_IP.' WHERE houseid='.$row['houseid']);
			redirect($str_filename);
		}

		$mainbuild = '<option value="">Bitte wählen:</option>';

		foreach ($g_arr_house_builds as $int_id => $arr_bld)
		{

			$str_add_info = '<br /><br />';
			if(sizeof($arr_bld['next'])) {
				$str_add_info .= '`&Weiter ausbaubar zu: ';
				foreach ($arr_bld['next'] as $int_tmp_bld) {
					$str_add_info .= $g_arr_house_builds[$int_tmp_bld]['name'].' ';
				}
				$str_add_info .= '<br />';
			}
			if(sizeof($arr_bld['forbidden_ext'])) {
				$str_add_info .= '`$Verbotene Anbauten: ';
				foreach ($arr_bld['forbidden_ext'] as $str_tmp_type) {
					$str_add_info .= $g_arr_house_extensions[$str_tmp_type]['name'].' ';
				}
			}

			// Wenn bereits Ausbau vorhanden: Liste erlaubter Ausbauten heranziehen
			if (!empty($arr_current_build))
			{
				if (!in_array($int_id,$arr_current_build['next'])) // Ist Ausbau erlaubt?
				{
					continue;
				} // sonst: überspringen

				$int_cost_gold=$arr_bld['goldcost'];
				$int_cost_gems=$arr_bld['gemcost'];

				$jsvars.='builds['.$int_id.']=new Array(\''.strip_appoencode($arr_bld['desc'],2).$str_add_info.'\','.$int_cost_gold.','.$int_cost_gems.');';

				$mainbuild.='<option value="'.$arr_bld['id'].'">'.$arr_bld['name'].'</option>';
			}

			else
			{
				if ($arr_bld['enable_zero'] && empty($arr_current_build))
					{ $mainbuild.='<option value="'.$arr_bld['id'].'">'.$arr_bld['name'].'</option>'; }

				$int_cost_gold=$arr_bld['goldcost'];
				$int_cost_gems=$arr_bld['gemcost'];

				$jsvars.='builds['.$int_id.']=new Array(\''.strip_appoencode($arr_bld['desc'],2).$str_add_info.'\','.$int_cost_gold.','.$int_cost_gems.');';
			}
		}

		if (isset($mainbuild))
		{
			$mainbuild='<select size="1" id="sel4657">'.$mainbuild.'</select>
			'.JS::event('#sel4657','change','load_build(this.value);').'
			';
		}
		else
		{
			$mainbuild='Weiteres Ausbauen nicht möglich!';
		}

		$str_out.=form_header($str_filename.'?act=build_buildings', 'POST', true, 'buildings');

		$str_out.='
		`c<table border="0" style="background-color:#000000;border-style:inset;border-width:1px;width:90%;">
			<tr class="trdark">
				<td colspan="2">
				'.$mainbuild.'
				</td>
			</tr>
			<tr class="trdark">
				<td id="manual" style="height:100px;" colspan="2">
				Die Handwerkerzunft '.getsetting('townname','Atrahor').'s steht zu deinen Diensten, um dein Domizil nach deinen Wünschen auszubauen. Aus der obigen Liste kannst du wählen.
				</td>
			</tr>
			<tr class="trdark">
				<td>
				<input type="submit" value="Bauen!" class="button"> <input type="text" name="buildid" style="display:none;">
				</td>
				<td align="right" id="priceinfo">
				<img src="./images/icons/gold.gif"> 0 <img src="./images/icons/gem.gif"> 0
				</td>
			</tr>
		</table>`c
		';

		$str_out.=JS::encapsulate('
		var builds=new Array();
		'.$jsvars.'

		function load_build(id)
			{
			document.buildings.buildid.value=id;
			load_manual(id);
			}

		function load_manual(id)
			{
			document.getElementById("manual").innerHTML=builds[id][0];
			document.getElementById("priceinfo").innerHTML=\' <img src="./images/icons/gold.gif"> \'+builds[id][1]+\' <img src="./images/icons/gem.gif"> \'+builds[id][2];
			}');

		addnav('Zurück',$str_filename);

	break;
	}// END build_buildings

	case 'build_extensions':// Anbauten
	{
		$str_out .= house_get_title('Anbauten in '.$row['housename'].'`0');

		// Auswahl getroffen?
		if (isset($_GET['choose']) || isset($_GET['ext_id']))
		{
			$str_choose=$_GET['choose'];
			$int_id=(int)$_GET['ext_id'];

			if ($int_id==0 && isset($g_arr_house_extensions[$str_choose])) // Wenn Neubau
			{
				$arr_tmp=db_fetch_assoc(db_query('SELECT COUNT(*) AS c FROM house_extensions WHERE houseid='.$row['houseid'].' AND type="'.$str_choose.'"'));

				if ($arr_tmp['c']>=$g_arr_house_extensions[$str_choose]['max_amount']) // Auf max. Anzahl prüfen
				{
					$str_out.='`$`bDas Gebäude hat momentan die höchste Stufe erreicht!`b`n`n';
				}

				else // Sonst: Einfügen
				{
					db_insert('house_extensions', array('type'=>$str_choose,'houseid'=>$row['houseid'],'level'=>0,'content'=>utf8_serialize(array())));
					Cache::delete(Cache::CACHE_TYPE_HDD,'houserooms'.$row['houseid']);
					$int_id=db_insert_id();
				}
			}

				// Wenn ID für Extension gegeben
			if ($int_id>0)
			{
				house_extension_run('build_start',$int_id,$row); // Modul runnen
				db_query('UPDATE houses SET build_state='.HOUSES_BUILD_STATE_EXT.',extension='.$int_id.' WHERE houseid='.$row['houseid']); // Starten

				redirect($str_filename);
			}
		}

		// Welche Erweiterungen existieren schon?
		$res = db_query('SELECT type,level,id FROM house_extensions WHERE houseid='.$row['houseid'].' AND loc IS null AND level > 0');
		$arr_ext_exist=db_create_list($res,'type');
		$int_ext_amount = db_num_rows($res);
		db_free_result($res);

		// max. Zahl an Anbauten
		if($row['status'] == 0) {
			$int_max_ext = getsetting('housemaxextensions',2);
		}
		else {
			$int_max_ext = getsetting('housemaxextensionsplus',2);
		}

		$arr_aei=user_get_aei('job'); // Beruf

		$str_out.='`c`&Maximal kannst du in diesem Haus '.$int_max_ext.' Anbauten errichten!`0`n<table border="0" cellspacing="2" cellpadding="5" style="border-style:inset;border-width:1px;width:90%;">';

		$int_counter = 0;

		// Liste durchlaufen
		foreach ($g_arr_house_extensions as $str_type => $arr_ext)
		{
			if ($arr_ext['room']) // Gemächer stehen unter gesondertem Punkt
			{
				continue;
			}

			if (isset($arr_ext['locked_right'])) // Debug: Wenn Anbau noch nicht freigeschaltet, auf benötigtes Recht prüfen
			{
				if (!$access_control->su_check($arr_ext['locked_right']))
				{
					continue;
				}
			}

			$int_lvl=0;
			$int_maxlvl=($arr_aei['job']==$arr_ext['special_job']?$arr_ext['maxlvl_job']:$arr_ext['maxlvl_else']);

			if (isset($arr_ext_exist[$str_type]))
			{
				$int_lvl=$arr_ext_exist[$str_type]['level'];
			}

			$build_cost=house_calc_ext_costs($arr_ext['goldcost'], $arr_ext['gemcost'], $int_lvl);

			$str_out.='
			<tr class="trdark">
				<td style="padding:5px;width:160px;height:80px;">
				<img src="./images/buildings/default.jpg" alt="Anbau">
				</td>
				<td style="padding:5px;" valign="top">
				<b>'.$arr_ext['colname'].'</b> (Stufe '.$int_lvl.')<br><br>
				`&'.$arr_ext['desc'].'<br><br>
				Benötigt: <img src="./images/icons/gold.gif"> '.$build_cost['gold'].' <img src="./images/icons/gem.gif"> '.$build_cost['gems'].'`0
				</td>
				<td style="padding:5px;text-align:center;">
				'.	($int_ext_amount < $int_max_ext || $int_lvl>0
						? (!isset($arr_current_build['forbidden_ext'][$str_type])
							? ($int_lvl<$int_maxlvl
								? create_lnk('<img src="./images/icons/bank.gif" alt="Aufwerten" style="float:left;border:none;"> `&Aufwerten zu<br>Stufe '.($int_lvl+1).'`0',
												$str_filename.'?act=build_extensions&'.($int_lvl == 0 ? 'choose='.$str_type : 'ext_id='.$arr_ext_exist[$str_type]['id']),true,false,'Sicher, dass du den Anbau starten möchtest?')
								:'Maximale Größe erreicht!'
								)
							: 'In eine'.($arr_current_build['sex']?'r':'m').' '.$arr_current_build['name'].' ist sowas nicht erlaubt!'
							)
						: 'In diesem Haus gibt es keinen Platz für weitere Anbauten!'
						)
					.($int_lvl>0?'<br><br>'.create_lnk('<img src="./images/icons/waffe.gif" alt="Abreißen" style="float:left;border:none;"> `$Abreißen!`0',$str_filename.'?act=rip_extensions&choose='.$arr_ext_exist[$str_type]['id'],true,false,'Sicher, dass du den Anbau abreißen möchtest?'):'').'
				</td>
			</tr>';
			$int_counter++;
		}

		if($int_counter == 0) {
			$str_out .= '<tr class="trdark"><td><i>Im Augenblick gibt es leider keine Anbauten für dich - schau später noch einmal vorbei!</i></td></tr>';
		}

		$str_out.='</table>`c';

		$str_lnk=$str_filename.'?act=build_extensions';
		addnav('',$str_lnk);

		addnav('Zurück',$str_filename);

	break;
	}// END build_extensions

	case 'build_finish': //Ausbau / Anbau fertig
	{
		$str_out .= house_get_title('Ausbau abgeschlossen!');

		// Ausbau
		if($row['build_state'] == HOUSES_BUILD_STATE_IP) {
			addnews("`t".$session['user']['name']."`3 hat die Arbeiten am Haus `t$row[housename]`3 fertiggestellt.");
			addhistory("`3Hat die Arbeiten am Haus `t$row[housename]`3 fertiggestellt.");

			$sql = 'UPDATE houses SET build_state=0,extension=0,status='.(int)$row['extension'].',gold = '.max($row['gold'] - $int_upgr_gold,0).',gems = '.max($row['gems'] - $int_upgr_gems,0).' WHERE houseid='.$row['houseid'];
			db_query($sql);

			$str_out .= $g_arr_house_builds[(int)$row['extension']]['finished_msg'].'`n';

			// Modul ausführen
			house_build_run('build_finished',(int)$row['extension'],$row);

			// Schlüssel usw.
			house_check($row,$row['extension']);

		}
		// Erweiterung
		elseif($row['build_state'] == HOUSES_BUILD_STATE_EXT) {
			$str_out .= '`b`@Herzlichen Glückwunsch!`0`b`n`n
						`tDu vollendest deinen Anbau und kannst ihn nun nutzen.`n';

			$sql = 'UPDATE houses SET build_state = 0,extension = 0,gold = '.max($row['gold'] - $int_upgr_gold,0).',gems = '.max($row['gems'] - $int_upgr_gems,0).' WHERE houseid='.$row['houseid'];
			db_query($sql);
			//addnews("`t".$session['user']['name']."`3 hat die Arbeiten am Haus `t$row[housename]`3 fertiggestellt.");

			// Modul ausführen
			house_extension_run('build_finished',(int)$row['extension'],$row);

			$sql_anbau_level = 'SELECT level FROM house_extensions WHERE id='.$row['extension'];
			$result_anbau_level = db_query($sql_anbau_level);
			$anbau_level = db_fetch_assoc($result_anbau_level);

			//$str_out .= '`$'.$anbau_level['level'];

			if ($anbau_level['level'] > 0)
			{
				db_query('UPDATE house_extensions SET level=level+1 WHERE id='.$row['extension']);
			}
			else
			{
				db_query('UPDATE house_extensions SET level=level+1,content="'.utf8_serialize(array()).'" WHERE id='.$row['extension']);
			}
		}

		addnav('Zum Haus',$str_filename);
		addnav('Gemächer',$str_filename.'?act=man_rooms');
	break;
	}// END build_finish

	case 'rip_extensions': // Anbauten abreißen
	{
		$str_out .= house_get_title('Anbau in '.$row['housename'].'`0 abreißen');

		$int_choose = (int)$_GET['choose'];

		// Modul ausführen
		house_extension_run('rip',$int_choose,$row);

		db_query('DELETE FROM house_extensions WHERE id='.$int_choose);
		// Cache zurücksetzen
		if(!Cache::delete(Cache::CACHE_TYPE_HDD,'houserooms'.$row['houseid']))
		{
			admin_output('Cachereset (houserooms'.$row['houseid'].') funzt nicht',true);
		}
		$str_out .= '`tBald schon rücken die Handwerker an, um diesen "Makel" zu beheben - bis auf jede Menge Dreck bleibt nach kurzer Zeit auch nicht davon übrig.`n
						Vielleicht findest du ja jemanden, der aufräumt..`n';

	debuglog('hat einen Anbau abgerissen.');
		addnav('Zum Haus',$str_filename);
	break;
	}// END rip_extensions

	case 'rip_builds': // Ausbau abreißen
	{
		$str_out .= house_get_title('Ausbau in '.$row['housename'].'`0 abreißen');

		if(isset($_GET['ok'])) {
			$sql = 'UPDATE houses SET build_state = 0,extension = "",status=0 WHERE houseid='.$row['houseid'];
			db_query($sql);

			// Schlüssel usw.
			house_check($row,0);

			$str_out .= '`tBald schon rücken die Handwerker an, um dein Haus wieder seines Ausbaus zu berauben - bis auf jede Menge Dreck bleibt nach kurzer Zeit auch nicht davon übrig.`n
							Vielleicht findest du ja jemanden, der aufräumt..`n';
			debuglog('hat den Ausbau seines Hauses abgerissen.');       
		}
		else {

			$str_out .= '`#Die Kosten, die durch das Entfernen des Ausbaus entstehen werden gerade durch den Wert der Baumaterialien gedeckt.`n
							`@Du wirst also NICHTS von deinem investierten Gold oder von den Edelsteinen zurückerhalten.`n
							Überzählige Schlüssel und Gemächer werden entfernt!`n`n
							`@Dein Haus wird wieder ein gewöhnliches Wohnhaus sein.`n';

			$str_out .= house_get_rip_warning($row['houseid'],0);

			addnav('JA, Ausbau entfernen!',$str_filename.'?act=rip_builds&ok=1',false,false,false,false);
		}

		// Modul ausführen
		house_build_run('rip',$row['status'],$row);

		addnav('Zurück',$str_filename);
	break;
	}// END rip_builds

	case 'rename': // Haus umbenennen
	{
		$str_out .= house_get_title('Haus umbenennen');

		if (!$_POST['housename'])
		{
			$str_out .= '`tEine Umbenennung des Hauses kostet dich `#1`t Edelstein.`0`n`n'.
						form_header($str_filename.'?act=rename');
			output($str_out);
			$str_out = '';
			$arr_form = array(	'h_prev'=>'Vorschau:,preview,housename',
								'housename'=>'Gib einen neuen Namen für dein Haus ein:,text,40');
			showform($arr_form,$row,false,'Haus umbenennen!');
			output('</form>');
		}
		else
		{
			if ($session['user']['gems']<1)
			{
				$str_out .= "`tDas kannst du nicht bezahlen.";
			}
			else
			{
				$fixed = strip_appoencode(stripslashes($_POST['housename']),2);
				$fixed = strip_tags($fixed);
				$str_out .= "`tDein Haus `0".$row['housename']."`t heißt jetzt `0".$fixed."`t.";
				$sql = "UPDATE houses SET housename='".db_real_escape_string($fixed)."`0' WHERE houseid=".$row['houseid'];
				db_query($sql);
				$session['user']['gems']-=1;
			}
		}
		addnav("Zurück zum Haus",$str_filename);
	break;
	}// END rename

	case 'c_le_change': // Kommentarlänge ändern
	{
		$str_out .= house_get_title('Kommentarlänge bestimmen');

		$max_total = getsetting('chat_post_len_max',8000);
		$min_total = getsetting('chat_post_len','0'); // Öffentlichen Plätze
		if (!isset($_POST['chars']))
		{
			$str_out .= '`tAls Hausherr(in) ist es dir hier möglich zu bestimmen, wieviel Platz (Zeichen) du dir und deinen Mitbewohnern für das Rollenspiel zugestehst.
			`nDer Wert, den du eingibst, darf den vorgegebenen Maximalwert (derzeit '.$max_total.') nicht überschreiten!
			`nEs sind nur positive, ganze Zahlen zulässig. Bei einer ungültigen Eingabe, oder wenn du 0 eingibst, wird stattdessen der Standartwert für öffentliche Räume (derzeit '.$min_total.') verwendet. Dieser Wert darf auch nicht unterschritten werden!
			`n`nDeine derzeitige Einstellung ist: '.$row['c_max_length'].'.
			`n`n`n`0
			<form action="'.$str_filename.'?act=c_le_change" method="POST">
			`nNeues Zeichenlimit für dieses Haus:
			<input type="text" id="chars" name="chars" value="'.$row['c_max_length'].'">
			`n`n<input type="submit" class="button" value="Setzen">
			</form>
			'.focus_form_element('chars');

			addnav("","$str_filename?act=c_le_change");
		}
		else
		{
			$amt=(int)$_POST['chars'];
			if ($amt>$max_total) $amt=$max_total;
			if ($amt<=0) $amt=$min_total;
			$amt=max($amt,$min_total);
			$str_out .= "`tAlles klar!`nDie maximale Zeichenlänge für dieses Haus wurde auf $amt geändert.`n";
			$sql = "UPDATE houses SET c_max_length=$amt WHERE houseid=".$row['houseid']."";
			db_query($sql);
		}
		addnav('Zurück zum Haus',$str_filename);
	break;
	}// END c_le_change

	case 'desc': // Beschreibung ändern
	{
		$str_out .= house_get_title('Hausbeschreibung ändern');

		$int_max_length = getsetting('housedesclen',500);
		if (!isset($_POST['description']))
		{
			$str_out .= '`tHier kannst du die Beschreibung für dein Haus ändern.`0`n`n`n';
			$str_out .= '<form action="'.$str_filename.'?act=desc" method="POST">';
			$arr_form = array(	
				'description'	=>	'Gib eine Beschreibung`n für dein Haus ein:`n,textarea,40,20,'.$int_max_length,
			);
			output($str_out);
			showform($arr_form,$row,false,'Übernehmen!');
			$str_out = '</form>';
			addnav('',$str_filename.'?act=desc');
		}
		else
		{
			$fixed = closetags(stripslashes($_POST['description']),'`c`i`b');
			$fixed = strip_tags($fixed);
			$fixed = mb_substr($fixed,0,$int_max_length);

			$str_out .= '`n`n`tDie Beschreibung wurde geändert:`n`n`0'.$fixed.'`t`n`n';

			$sql = "
				UPDATE
					`houses`
				SET
					`description`	= '" . db_real_escape_string($fixed) . "'
				WHERE
					`houseid`		= '" . $row['houseid']. "'
			";
			db_query($sql);
		}
		addnav('Zurück zum Haus',$str_filename);
	break;
	}// END desc

	case 'logout': //nur noch Notfall-Option
	{
		// Hier nur noch Fallback für die Codestellen, die diesen Link nutzen.
		// Sinnvoller: direkt auf die login.php verlinken, spart Serverpower
		redirect('login.php?op=logout&loc='.USER_LOC_HOUSE.'&restatloc='.$row['houseid']);

	break;
	}// END logout

	case 'keyback': // Schlüssel zurückgeben
	{
		// Gemach in diesem Haus?
		$arr_room = null;
		$res = db_query('SELECT id FROM house_extensions he WHERE he.owner='.$session['user']['acctid'].' AND houseid='.$row['houseid']);
		if(db_num_rows($res)) {
			$arr_room = db_fetch_assoc($res);
		}

		if($_GET['ok']) {
			$str_out .= house_get_title('Schlüssel zurückgegeben!');

			$sql = 'UPDATE keylist SET gold=0,gems=0,owner='.$row['owner'].',chestlock=0 WHERE owner='.$session['user']['acctid'].' AND value1='.$row['houseid'].' AND type='.HOUSES_KEY_DEFAULT;
			db_query($sql);

			if(!is_null($arr_room)) {
				house_take_room($arr_room,$row,false);
			}

			insertcommentary($session['user']['acctid'],': `^gibt '.($session['user']['sex'] ? 'ihren' : 'seinen').' Schlüssel '.(!is_null($arr_room) ? 'nebst Gemach' : '').' zurück.','house-'.$row['houseid']);

			$str_out .= '`tStill und leise legst du deinen Schlüssel auf den Türrahmen, ehe du nach einem letzten Blick zurück '.$row['housename'].'`t verlässt..';

			addnav('Zum Wohnviertel','houses.php');
		}
		else {

			$str_out .= house_get_title('Schlüssel zurückgeben');

			addnav('Nein, lieber nicht..',$str_filename);
			addnav('Ja, klar!',$str_filename.'?act=keyback&ok=1');

			$str_out .= '`tMöchtest du wirklich deinen Schlüssel zu diesem Haus wieder abgeben?`n
					Deine Einzahlungen in den Schatz wären verloren und du hättest keinen Zutritt mehr!`n`n';

			if(!is_null($arr_room)) {
				$str_out .= 'Ebenso würde dein Gemach in diesem Haus an den Hausherrn zurückgegeben!';
			}

		}
	break;
	}// END keyback

	case 'rest': // Rasten
	{
		$nd = 1;
		$fine=0;
		$int_rest_time = 10;

		$rownd = user_get_aei('hadnewday');

		$str_out = house_get_title('Rast in '.$row['housename'].'`0 ('.get_house_state($row['status'],0,false).')');

		$str_output = '';

		if($row['build_state'] == HOUSES_BUILD_STATE_ABANDONED) {
			$str_output = 'Dieses verlassene Haus ist leider schon so heruntergekommen, dass du sowieso kein Auge zugemacht hast. Du solltest dich besser nach einer anderen Schlafstelle umsehen.';
			$nd = 0;
		}

		if($session['user']['spirits'] == RP_RESURRECTION || $rownd['hadnewday'] == 2) {
			$str_output = 'Du fühlst dich nach traumatischen Erlebnissen am Vortag nicht in der Stimmung, dich einer Rast hinzugeben.';
			$nd = 0;
		}

		if($rownd['hadnewday'] == 1) {
			$str_output = 'Du hast heute wohl schon genug gerastet.';
			$nd = 0;
		}

		if($row['status'] == 0) {
			$nd = 0;
			$fine=1;
			$str_output = 'Du erwachst gut erholt im Haus und bist bereit für neue Abenteuer.';

		}

		// In Rast-Screen wird Timestamp initialisiert. Diese Initialisierung muss min. $int_rest_time Sekunden her sein!
		// Oder: GET['getnd'] gegeben
		if(	 isset($_GET['getnd']) ||
			(isset($session['getnd']) && time() >= $session['getnd']+$int_rest_time)
		) {

			unset($session['getnd']);
			if($nd) {
				user_set_aei(array('hadnewday'=>1));
				debuglog('Rastbonus erhalten');
				house_build_run('wakeup',$row['status'],$row);
				// das Haus mit üüübel Schaden belasten (10%-Chance, hier wird Haus unmittelbar genutzt)!
				house_add_dmg($row,1,10);
			}
			else {
				if ($fine==0) $str_output .= '`n`nDu erwachst im Haus und bist bereit für neue Abenteuer.';
			}

			$str_out .= $str_output;

			addnav('N?Tägliche News','news.php');
			addnav('d?Zurück zum Stadtzentrum','village.php');
			addnav('H?Zurück ins Haus',$str_filename);

		}
		else {
			addnav('Zurück',$str_filename);
			if($nd == 0)
			{
				$str_out .= $str_output;
			}
			else {
				$session['getnd'] = time();
				$str_lnk = $str_filename.'?act=rest';
				addnav('',$str_lnk);
				//Einen ausgeblendeten Link erstellen.
				$nav.='<span id="extralink" style="display: none;">';
				addnav('Aufstehen',$str_lnk);
				$nav.='</span>';
				$str_out .= 'Völlig erschöpft vom anstrengenden Tagwerk legst du dich in diesem Haus nieder und gibst dich süßem
							Schlummer hin.`n`n
							`c<input type="button" value="" onclick="window.location.href=\''.$str_lnk.'\';" id="rest_but">`c
							'.JS::encapsulate('
								var time = '.$int_rest_time.';
								counter();
								function counter () {
									var b = document.getElementById("rest_but");
									var nav = document.getElementById("extralink");
									if(time > 0) {
										b.disabled = true;
										b.value = "Chrrr...krrrrch ("+time+" Sekunden)";
										time--;
										window.setTimeout("counter();",1000);
										return;
									}
									b.disabled = false;
									b.value = "Aufstehen!";
									nav.style.visibility = "visible";
									nav.style.display = "inline";
									MessageBox.show("Du schlägst die Augen auf und streckst dich ausgiebig - es wird Zeit, dich wieder den wichtigen Dingen des Lebens zu widmen!","Erwacht!");
								}
							');
			}

		}

	break;
	}// END rest

	case 'giveroom': // Gemach vergeben
	{
		$str_out .= house_get_title('Gemach übergeben');

		addnav("Zur Gemachverwaltung",$str_filename.'?act=man_rooms');
		addnav("Zurück zum Haus",$str_filename);

		// GemachID
		$int_key = (int)$_REQUEST['key'];

		// Ziel-AcctID
		$int_target = (int)$_POST['acctid'];

		if(!empty($int_target)) {

			// Gemach noch nicht vergeben und für dieses Haus?
			$sql = 'SELECT he.id,he.owner,he.loc FROM house_extensions he
					WHERE id='.$int_key.' AND owner = 0 AND houseid='.$row['houseid'].' AND loc IS NOT null';
			$res = db_query($sql);

			if(!db_num_rows($res)) {
				redirect($str_filename.'?act=man_rooms');
			}

			// Schlüsselinfos
			$arr_key = db_fetch_assoc($res);

			// Wem wollen wir ein Gemach geben?
			$sql = 'SELECT name,acctid,level,dragonkills,sex FROM accounts WHERE acctid='.$int_target;
			$res = db_query($sql);

			if(!db_num_rows($res)) {
				$str_out .= 'Person existiert nicht bzw. ist invalide.`n`n';
				output($str_out);
				page_footer();
			}

			// Zielinfos abrufen
			$arr_whom = db_fetch_assoc($res);

			// Levelvorgaben erfüllt?
			if ($arr_whom['level'] < 5 && $arr_whom['dragonkills'] < 1)
			{
				$str_out .= '`t'.$arr_whom['name'].'`t ist noch nicht lange genug in der Stadt, als dass du '.($arr_whom?'ihr':'ihm').' vertrauen könntest. Also beschließt du, noch eine Weile zu beobachten.`n`n';
			}
			elseif (
				$arr_whom['acctid'] != $session['user']['acctid'] &&
				db_num_rows(db_query('SELECT id FROM house_extensions WHERE houseid='.$row['houseid'].' AND loc IS NOT null AND owner='.$arr_whom['acctid'])))
			{
				$str_out .= '`t'.$arr_whom['name'].'`t besitzt doch schon ein Gemach in deinem Haus.`n`n';
			}
			else {

				if($arr_whom['acctid'] != $session['user']['acctid']) {
					$str_out = '`tDu übergibst `&'.$arr_whom['name'].'`t ein eigenes Gemach für dein Haus. Du kannst das Gemach jederzeit wieder wegnehmen';

					if(getsetting('housetrsshare',1)) {
						$str_out .= ', aber '.$arr_whom['name'].'`t wird dann einen gerechten Anteil aus dem gemeinsamen Schatz des Hauses bekommen.`n';
					}
					else {
						$str_out .= '.';
					}

					systemmail($arr_whom['acctid'],"`@Gemach erhalten!`0","`&{$session['user']['name']}
							`t hat dir ein eigenes Gemach in Haus Nummer `b".$row['houseid']."`b ($row[housename], ".house_get_floor($arr_key['loc'])."`t) gegeben!");

					insertcommentary($session['user']['acctid'],': `^gibt '.$arr_whom['name'].'`^ ein Gemach.','house-'.$row['houseid']);
				}
				else {
					$str_out = '`tDu nimmst dieses Gemach selbst unter Beschlag und kannst es nun einrichten.';
					addnav('Zum Gemach!','house_extensions.php?_ext_id='.$arr_key['id']);
				}

				$sql = 'UPDATE house_extensions SET owner='.$arr_whom['acctid'].',name="",content="'.utf8_serialize(array()).'" WHERE id='.$int_key;
				db_query($sql);

				// Alte Einladungen löschen
				house_keys_del('type='.HOUSES_KEY_PRIVATE.' AND value2='.$int_key,0);
				
				//fixed by bathi $row['houseid']  NICHT  $arr_house['houseid'] also wirklich ;)
				Cache::delete(Cache::CACHE_TYPE_HDD, 'houserooms'.$row['houseid'] );

			}
		}
		// Gemach abnehmen
		else {
			redirect($str_filename.'?act=takeroom&key='.$int_key.'&ok=1');
		}

	break;
	}// END giveroom

	case 'givekey': // Schlüssel geben
	{
		$str_out .= house_get_title('Schlüssel übergeben');

		addnav("Zurück zum Haus",$str_filename);

		// SchlüsselID
		$int_key = (int)$_REQUEST['key'];

		// Ziel-AcctID
		$int_target = (int)$_POST['acctid'];

		if(!empty($int_target)) {

			// Schlüssel noch nicht vergeben und für dieses Haus?
			$sql = 'SELECT k.id,k.owner FROM keylist k
					WHERE value1='.$row['houseid'].' AND type='.HOUSES_KEY_DEFAULT.' AND owner='.$session['user']['acctid'].' ORDER BY id ASC LIMIT 1';
			$res = db_query($sql);

			if(!db_num_rows($res)) {
				redirect($str_filename.'');
			}

			// Schlüsselinfos
			$arr_key = db_fetch_assoc($res);

			// Wem wollen wir einen Schlüssel geben?
			$sql = 'SELECT name,acctid,level,uniqueid,dragonkills,sex FROM accounts WHERE acctid='.$int_target.' AND acctid!='.$session['user']['acctid'];
			$res = db_query($sql);

			if(!db_num_rows($res)) {
				$str_out .= 'Person existiert nicht bzw. ist invalide.`n`n';
				output($str_out);
				page_footer();
			}

			// Zielinfos abrufen
			$arr_whom = db_fetch_assoc($res);

			// Levelvorgaben erfüllt?
			if ($arr_whom['level'] < 5 && $arr_whom['dragonkills'] < 1)
			{
				$str_out .= '`t'.$arr_whom['name'].'`t ist noch nicht lange genug in der Stadt, als dass du '.($arr_whom['sex']?'ihr':'ihm').' vertrauen könntest. Also beschließt du, noch eine Weile zu beobachten.`n`n';
			}
			elseif(db_num_rows(db_query('SELECT id FROM keylist WHERE value1='.$row['houseid'].' AND owner='.$arr_whom['acctid'].' AND type='.HOUSES_KEY_DEFAULT))) {
				$str_out .= '`t'.$arr_whom['name'].'`t besitzt bereits einen Schlüssel!`n`n';
			}
			elseif(ac_check($arr_whom))
			{
				$str_out.='`tDu darfst '.$arr_whom['name'].'`t keinen Schlüssel geben!';
			}
			else {

				$str_out = '`tDu übergibst `&'.$arr_whom['name'].'`t einen Schlüssel für dein Haus. Du kannst den Schlüssel zum Haus jederzeit wieder wegnehmen';

				if(getsetting('housetrsshare',1)) {
					$str_out .= ', aber '.$arr_whom['name'].'`t wird dann einen gerechten Anteil aus dem gemeinsamen Schatz des Hauses bekommen.`n';
				}
				else {
					$str_out .= '.';
				}

				systemmail($arr_whom['acctid'],"`@Schlüssel erhalten!`0","`&{$session['user']['name']}
						`t hat dir einen Schlüssel zu Haus Nummer `b".$row['houseid']."`b ($row[housename]`t) gegeben!");

				house_keys_set(' id='.$arr_key['id'],array('owner'=>$arr_whom['acctid'],'value2'=>0,'gold'=>0,'gems'=>0,'chestlock'=>0,'hvalue'=>0));

				insertcommentary($session['user']['acctid'],': `^gibt '.$arr_whom['name'].'`^ einen Schlüssel.','house-'.$row['houseid']);

			}
		}

	break;
	}// END givekey

	case 'takeroom': // Gemach abnehmen
	{
		$str_out .= house_get_title('Gemach abnehmen');

		// GemachID
		$int_key = (int)$_GET['key'];

		// Gemach überhaupt vergeben und für dieses Haus?
		$sql = 'SELECT he.id,he.owner,a.name,a.restatlocation,a.location,a.acctid FROM house_extensions he
				LEFT JOIN accounts a ON a.acctid = he.owner
				WHERE houseid='.$row['houseid'].' AND id='.$int_key.' AND owner > 0 AND loc IS NOT null';
		$res = db_query($sql);
		if(db_num_rows($res) != 1) {
			redirect($str_filename.'?act=man_rooms');
			exit;
		}

		// Gemachinfos
		$arr_key = db_fetch_assoc($res);

		house_take_room($arr_key,$row,false);

		$str_out .= 'Du nimmst '.$arr_key['name'].'`0 die Schlüssel zum Gemach in deinem Haus wieder ab. Hoffentlich kommt '.$arr_key['name'].'`0 woanders unter..';

		addnav('Zurück',$str_filename.'?act=man_rooms');

	break;
	}// END takeroom

	case 'takekey': // Schlüssel abnehmen
	{
		$str_out .= house_get_title('Schlüssel abnehmen');

		addnav("Zurück zum Haus",$str_filename);

		// SchlüsselID
		$int_key = (int)$_GET['key'];

		// Schlüssel überhaupt vergeben und für dieses Haus?
		$sql = 'SELECT k.id,k.owner,a.name,a.restatlocation,a.location FROM keylist k
				LEFT JOIN accounts a ON a.acctid=k.owner
				WHERE id='.$int_key.' AND k.owner<>'.$session['user']['acctid'];
		$res = db_query($sql);
		if(db_num_rows($res) != 1) {
			page_footer();
			exit;
		}

		// Schlüsselinfos
		$arr_key = db_fetch_assoc($res);

		$str_out = '`tDu verlangst den Schlüssel von `&'.$arr_key['name'].'`t zurück.<br />';

		// Hat er Gemach?
		$int_goldgive = 0;
		$int_gemsgive = 0;
		$sql = 'SELECT id FROM house_extensions WHERE owner='.$arr_key['owner'].' AND houseid='.$row['houseid'].' AND loc IS NOT null';
		$res = db_query($sql);
		if(db_num_rows($res)) {
			$str_out .= 'Da `&'.$arr_key['name'].'`t in deinem Haus auch ein Gemach besitzt, muss er dieses ebenfalls räumen.<br />';

			house_take_room(db_fetch_assoc($res),$row,false);

			// Nur Kohle, wenn Gemach
			$ausbau = ($row['build_state'] ? true : false);

			// Anteil aus Schatz auszahlen?
			$str_mail_plus = 'Dein Gemach, das du in '.$row['housename'].'`t besessen hast, musst du leider auch räumen..`n';
			$str_comment_plus = '';
			if(getsetting('housetrsshare',1)) {
				if(!$ausbau) {
					$sql = "SELECT COUNT(*) AS c FROM keylist WHERE value1=".$row['houseid']." AND type=".HOUSES_KEY_DEFAULT." AND owner<>$arr_key[owner]";
					$count = db_fetch_assoc(db_query($sql));

					$int_goldgive=round($row['gold']/($count['c']+1));
					$int_gemsgive=round($row['gems']/($count['c']+1));
					$str_mail_plus .= "Du bekommst `^$int_goldgive`t Gold auf die Bank und `#$int_gemsgive`t Edelsteine aus dem gemeinsamen Schatz ausbezahlt!";
					$str_comment_plus = $arr_key['name'].'`^ bekommt einen Teil aus dem Schatz.';
					$str_out .= $arr_key['name'].'`t bekommt `^'.$int_goldgive.'`t Gold und `#'.$int_gemsgive.'`t Edelsteine aus dem gemeinsamen Schatz.';
				}
				else {
					$str_mail_plus .= "Weil sich das Haus im Ausbau befindet, bekommst du jedoch keinen Teil aus dem Schatz!";
				}
				// Hausschatz updaten
				if($int_goldgive > 0 || $int_gemsgive > 0) {
					$sql = "UPDATE houses SET gold=gold-$int_goldgive,gems=gems-$int_gemsgive WHERE houseid=".$row['houseid']."";
					db_query($sql);
				}
			}

		}


		// Account updaten
		$arr_user_update = array
		(
			'goldinbank'=>array('sql'=>true,'value'=>'goldinbank+'.$int_goldgive),
			'gems'=>array('sql'=>true,'value'=>'gems+'.$int_gemsgive)
		);
		if($arr_key['restatlocation'] == $row['houseid'])
		{
			$arr_user_update = array_merge($arr_user_update,array('location'=>0,'restatlocation'=>0));
		}
		user_update($arr_user_update,$arr_key['owner']);

		// falls der Urlaubsmodus per Usereditor gesetzt war, diesen wiederherstellen (umständlich, aber kommt ja nicht oft vor)
		if($arr_key['location']== USER_LOC_VACATION)
		{
			user_update(
				array
				(
					'location'=>USER_LOC_VACATION,
				),
				$arr_key['owner']
			);
		}

		// Systemmessage an Schlüsselbesitzer
		systemmail($arr_key['owner'],'`@Schlüssel zurückverlangt!`0','`&'.$session['user']['name'].'
		`t hat den Schlüssel zu Haus Nummer `b'.$row['houseid'].'`b ('.$row['housename'].'`t) zurückverlangt. '.
		$str_mail_plus);

		// Kommentar
		insertcommentary($session['user']['acctid'],': `^nimmt '.$arr_key['name'].'`^ einen Schlüssel ab. '.$str_comment_plus,'house-'.$row['houseid']);

		// Schlüssel zurückgeben
		house_keys_set(' id='.$arr_key['id'],array('owner'=>$row['owner'],'hvalue'=>0,'value2'=>0,'gold'=>0,'gems'=>0,'chestlock'=>0));

	break;
	}//END takekey

	case 'man_rooms': // Gemächer verwalten
	{
		$arr_floors = array();

		// Max. Anzahl gesamt
		$int_max_amount_total = house_get_max_rooms($row['status']);

		// Aktuelle Raumanzahl gesamt
		$arr_tmp = db_fetch_assoc(db_query('SELECT COUNT(*) AS c FROM house_extensions WHERE houseid='.$row['houseid'].' AND loc IS NOT null'));
		$int_room_count = $arr_tmp['c'];

		// "Freiräume"
		$int_free_rooms_left = house_get_max_rooms($row['status'],false) - $int_room_count;
		// Gutschrift
		$int_free_rooms_gold = getsetting('housefreeroomsgold',5000);
		$int_free_rooms_gems = getsetting('housefreeroomsgems',10);

		// max. mögl. Stockwerk
		$arr_max_floor = db_fetch_assoc(
								db_query('SELECT loc FROM house_extensions WHERE houseid='.$row['houseid'].' AND loc IS NOT null AND level > 0 ORDER BY loc DESC LIMIT 1')
								);

		if($arr_max_floor['loc'] >= 0) {
			$arr_floors = array(
			HOUSES_ROOM_BASEMENT=>'Keller',
			HOUSES_ROOM_GROUND=>'Erdgeschoß');
		}
		if($arr_max_floor['loc'] >= HOUSES_ROOM_GROUND) {
			$arr_floors[HOUSES_ROOM_1ST] = '1. Stock';
		}
		if($arr_max_floor['loc'] >= HOUSES_ROOM_1ST) {
			$arr_floors[HOUSES_ROOM_2ND] = '2. Stock';
		}
		if($arr_max_floor['loc'] >= HOUSES_ROOM_2ND) {
			$arr_floors[HOUSES_ROOM_ROOF] = 'Dachgeschoß';
			$arr_floors[HOUSES_ROOM_TOWER] = 'Turmgeschoß';
		}

		// Aktionen
		// Neubau
		if(isset($_POST['build'])) {

			$str_choose = $_POST['build'];

			//Wird hier oben bereits berechnet, damit man den Bau abhängig vom Stockwerk beenden kann
			$int_floor = (int)$_POST['floor'];
			$int_floor = max($int_floor,0);
			end($arr_floors);
			$int_floor = min($int_floor,key($arr_floors));

			// Valide?
			if($g_arr_house_extensions[$str_choose]['room'] === true) {

				// Auf max. Anzahl prüfen
				if(db_num_rows(db_query('SELECT id FROM house_extensions WHERE houseid='.$row['houseid'].' AND type="'.$str_choose.'"')) >= $g_arr_house_extensions[$str_choose]['max_amount']) {
					$str_out .= '`b`$So viele Gemächer dieser Art kannst du nicht bauen!`0`b`n`n';
				}
				elseif($int_room_count >= $int_max_amount_total) {
					$str_out .= '`b`$Leider bieten die beengten Verhältnisse in deinem Haus keinen Platz für weitere Gemächer!`0`b`n`n';
				}
				elseif($g_arr_house_extensions[$str_choose]['floor_level'] != 0 &&
				($g_arr_house_extensions[$str_choose]['floor_level'] & $int_floor) == 0)
				{
					$str_out .= '`b`$Dieses Gemach kann leider nicht in diesem Stockwerk gebaut werden. Bitte baue es ';
					if(($g_arr_house_extensions[$str_choose]['floor_level'] & HOUSES_ROOM_BASEMENT) >0)
					{
						$arr_where[] = 'im Keller';
					}
					if(($g_arr_house_extensions[$str_choose]['floor_level'] & HOUSES_ROOM_GROUND) >0)
					{
						$arr_where[] = 'im Erdgeschoss';
					}
					if(($g_arr_house_extensions[$str_choose]['floor_level'] & HOUSES_ROOM_1ST) >0)
					{
						$arr_where[] = 'im 1. Stock';
					}
					if(($g_arr_house_extensions[$str_choose]['floor_level'] & HOUSES_ROOM_2ND) >0)
					{
						$arr_where[] = 'im 2. Stock';
					}
					if(($g_arr_house_extensions[$str_choose]['floor_level'] & HOUSES_ROOM_ROOF) >0)
					{
						$arr_where[] = 'im Dachgeschoss';
					}
					if(($g_arr_house_extensions[$str_choose]['floor_level'] & HOUSES_ROOM_TOWER) >0)
					{
						$arr_where[] = 'im Turm';
					}

					if(!is_array($arr_where))
					{
						$str_out .= 'in einem anderen Geschoss.';
					}
					else
					{
						$str_out .= implode(' oder ',$arr_where);
					}
					$str_out .= '`0`b`n`n';
				}
				// Sonst: Einfügen
				else {
					$arr_content = array();
					// Wenn Freiräume: Gutschrift
					if($int_free_rooms_left > 0) {
						$int_free_rooms_gold = min($g_arr_house_extensions[$str_choose]['goldcost'],$int_free_rooms_gold);
						$int_free_rooms_gems = min($g_arr_house_extensions[$str_choose]['gemcost'],$int_free_rooms_gems);
						$arr_content = array('bonus'=>array('gold'=>$int_free_rooms_gold,'gems'=>$int_free_rooms_gems));
					}
					db_insert('house_extensions',
								array('type'=>$str_choose,'houseid'=>$row['houseid'],'level'=>0,'loc'=>$int_floor,'content'=>utf8_serialize($arr_content))
							);
					Cache::delete(Cache::CACHE_TYPE_HDD,'houserooms'.$row['houseid']);
					$int_id = db_insert_id();

					// Modul runnen
					house_extension_run('build_start',$int_id,$row);

					// Starten
					db_query('UPDATE houses SET build_state='.HOUSES_BUILD_STATE_EXT.',extension='.$int_id.' WHERE houseid='.$row['houseid']);

					redirect($str_filename);
				}

			}

		}

		// Mögliche Gemächer (für Build-Select)
		$arr_build_rooms = array();
		// Preise dafür, max. Anzahl (als JS)
		$str_build_rooms_js = '';

		foreach ($g_arr_house_extensions as $arr_he) {

			// Wenn Gemach
			if(isset($arr_he['room']) && true === $arr_he['room']) {

				//Wenn der Gemachtyp nicht freigegeben wurde: überspringen und nicht anzeigen!
				if(isset($arr_he['enabled']) && false === $arr_he['enabled'])
				{
					continue;
				}
				//Wenn der Gemachtyp nicht freigegeben wurde: überspringen und nicht anzeigen!
				if(isset($arr_he['locked_right']) && !$access_control->su_check($arr_he['locked_right']))
				{
					continue;
				}


				$arr_build_rooms[$arr_he['id']] = $arr_he['name'];

				$int_goldprice = $arr_he['goldcost'];
				$int_gemprice = $arr_he['gemcost'];

				$str_build_rooms_js .= 'case "'.$arr_he['id'].'": return("'.addslashes(strip_appoencode($arr_he['desc'],3)).'<br /><img src=\"./images/icons/gold.gif\" alt=\"Gold\"> '.$int_goldprice.' <img src=\"./images/icons/gem.gif\" alt=\"Edelsteine\"> '.$int_gemprice.(isset($arr_he['max_invi']) ? '<br><i>Raum für max. '.$arr_he['max_invi'].' Personen.<\/i>' : '').(isset($arr_he['max_furn']) ? '<br><i>Raum für max. '.$arr_he['max_furn'].' Möbelstücke.<\/i>' : '').'");';
			}

		}
		asort($arr_build_rooms);
		$arr_build_rooms = array_merge(array(''=>'Bitte wählen:'),$arr_build_rooms);

		// Schlüsselbesitzer-Select
		// Alle Schlüsselbesitzer abrufen
		$sql = 'SELECT a.name,a.acctid FROM keylist k
				LEFT JOIN accounts a ON a.acctid = k.owner
				WHERE k.value1='.$row['houseid'].' AND k.type='.HOUSES_KEY_DEFAULT.' AND (k.owner != '.$row['owner'].' AND k.owner > 0)';
		$res = db_query($sql);

		// Select erstellen
		$str_keyowners_select = 'Neuer Besitzer: <select name="acctid">
									<option value="'.$session['user']['acctid'].'">Du selbst</option>';

		while($arr_k = db_fetch_assoc($res)) {
			$str_keyowners_select .= '<option value="'.$arr_k['acctid'].'">'.$arr_k['name'].'</option>';
		}

		$str_keyowners_select = strip_appoencode($str_keyowners_select,3).'</select>';
		db_free_result($res);
		// END Schlüsselbesitzer-Select

		// Alle Gemächer in diesem Haus abrufen
		$sql = 'SELECT he.*,a.name AS oname,a.acctid FROM house_extensions he
				LEFT JOIN accounts a ON a.acctid = he.owner
				WHERE he.houseid='.$row['houseid'].' AND he.loc IS NOT null AND he.level > 0
				ORDER BY loc ASC, oname ASC';
		$res = db_query($sql);


		// Tablekopf + Script
		$str_out .= house_get_title('Gemächer in '.$row['housename'].'`0');
		$str_out .= '
					Hier, über deine komplizierten Konstruktionsskizzen und Baupläne gebeugt, verwaltest du die Gemächer in deinem Haus.
					Beachte bitte, dass du ein Gemach erst dann nutzen kannst, wenn du irgendjemandem (zum Beispiel dir selbst) die Schlüssel dazu
					überreicht hast. Zunächst wirst du nur in den unteren Stockwerken deines Hauses etwas bauen können - sobald sich im Erdgeschoss ein Gemach befindet,
					stehen dir auch die oberen Etagen zur Verfügung.`n`n
					'.JS::encapsulate('

						function get_room (id) {
							switch(id) {
								'.$str_build_rooms_js.'
								default: return("");
							}
						}
						function give_room (id) {
							var frm = document.getElementById("giverm_frm");
							var key = document.getElementById("key");
							if(frm.style.display != "none") {
								document.getElementById("but"+key.value).style.display = "block";
								key.value = 0;
								frm.style.display = "none";
								if(0 == id) {
									return;
								}
							}
							document.getElementById("but"+id).style.display = "none";
							if(!document.getElementById("r"+id).hasChildNodes()) {
								document.getElementById("r"+id).appendChild(frm);
							}
							key.value = id;
							frm.style.display = "block";
						}
						function constr_cost (f) {
							txt = "";
							switch(f) {
								case "'.HOUSES_ROOM_1ST.'": txt += "10"; break;
								case "'.HOUSES_ROOM_2ND.'": txt += "20"; break;
								case "'.HOUSES_ROOM_ROOF.'": txt += "40"; break;
								case "'.HOUSES_ROOM_TOWER.'": txt += "80"; break;
								default: txt += "0"; break;
							}
							document.getElementById("constr_cost_div").style.display = (txt == "0" ? "none" : "block");
							document.getElementById("constr_cost_span").innerHTML = txt;
						}
					');
		// Formular zum Vergeben des Gemach
		$str_out .= '<div id="giverm_frm" style="display:none;">'.form_header($str_filename.'?act=giveroom');
		$str_out .= $str_keyowners_select;
		$str_out .= ' 	<input type="hidden" name="key" id="key">
						<input type="button" value="Doch nicht" id="inp32456"> <input type="submit" value="Los &raquo;">
						'.JS::event('#inp32456','click','give_room(0);').'
					</form></div>';

		$str_out .= '`c<table cellpadding="4" style="background-color:#000000;border-style:inset;border-width:1px;width:90%;">';

		$str_trclass = 'trlight';

		// Datenschleife
		while($arr_r = db_fetch_assoc($res)) {

			$str_trclass = ($str_trclass == 'trlight' ? 'trdark' : 'trlight');

			// Name ermitteln
			$arr_r['name'] = (empty($arr_r['name']) ? $g_arr_house_extensions[$arr_r['type']]['name'] : $arr_r['name'].'`0 ('.$g_arr_house_extensions[$arr_r['type']]['name'].')');

			// Stockwerk ermitteln
			$arr_r['loc'] = house_get_floor($arr_r['loc'],true);

			// Eigentümer ermitteln
			$arr_r['oname'] = (empty($arr_r['oname']) ? '`@Frei!`0' : '`^Gehört '.($arr_r['owner'] == $session['user']['acctid'] ? 'Dir selbst' : $arr_r['oname']).'`^!`0');

			$str_out .= '<tr class="'.$str_trclass.'">'
							.'<td> '.$arr_r['name'].'</td>'
							.'<td width="25%" align="center">'.$arr_r['loc'].'</td>'
							.'<td width="25%" align="center">'.$arr_r['oname'].'</td>'
							.'<td>'.($arr_r['owner'] == $session['user']['acctid']
										? create_lnk('<img src="./images/icons/bank.gif" alt="Betreten" border="0"> `tGemach betreten &raquo;`0','house_extensions.php?_ext_id='.$arr_r['id'])
										: '').'</td>
						</tr>
						<tr class="'.$str_trclass.'">
							<td colspan="3">';

			if($arr_r['owner'] == 0) {
				// Button zur Formularanzeige
				$str_out .= '<input type="button" value="Gemach vergeben!" id="but'.$arr_r['id'].'" />
				'.JS::event('#but'.$arr_r['id'],'click','give_room('.$arr_r['id'].');').'
							<div id="r'.$arr_r['id'].'"></div>';
			}
			else {
				// Abnehmen-Button
				$str_out .= create_lnk('<img src="./images/icons/ruestung.gif" alt="Abnehmen" border="0"> `tAbnehmen!`0',$str_filename.'?act=takeroom&key='.$arr_r['id'],true,false,'Seid Ihr Euch sicher?');
			}

			$str_out .= '	</td>
							<td colspan="2">
								'.
			($arr_r['acctid'] 	? '<img src="./images/icons/waffe.gif" alt="Abreißen" border="0"> `iAbreißen!`i (Nur unbewohnte)'
								: create_lnk('<img src="./images/icons/waffe.gif" alt="Abreißen" border="0"> `$Abreißen!`0',$str_filename.'?act=rip_extensions&choose='.$arr_r['id'],true,false,'Möchtet Ihr dieses Gemach wirklich abreißen?')
						).'	</td>
						</tr>
						<tr><td colspan="4">&nbsp;</td></tr>';

		}

		// Neue Gemächer bauen

		$int_rooms_available = $int_max_amount_total-$int_room_count;
		$str_out .= '<tr>
						<td colspan="4" style="padding:10px;"><hr>`n
							'.($int_rooms_available>0?'`b`&Du kannst noch '.$int_rooms_available.' Gemächer in deinem Haus bauen`0`b`n`n':'`b`&Dein Haus ist voll ausgebaut, Du kannst momentan keine Gemächer mehr bauen`0`b`n`n').
	($int_free_rooms_left > 0 ? '`b`&Du hast noch '.$int_free_rooms_left.' verbilligte Räume zur Verfügung - für deren Bau erhältst du '.$int_free_rooms_gold.' Gold und '.$int_free_rooms_gems.' Edelsteine als Gutschrift auf die Baukosten!`0`b`n`n' : '').
	($row['extension'] > 0 	? '`b`&An diesem Haus wird bereits gebaut. Du musst dich noch etwas gedulden.`0`b'
							:
							form_header($str_filename.'?act=man_rooms').'
								<select name="build" id="sel112357">
									'.form_sel_options($arr_build_rooms).'
								</select>'.JS::event('#sel112357','change','document.getElementById(\'room_info\').innerHTML = get_room(this.value);').' im
								<select name="floor" id="sel432657">
									'.form_sel_options($arr_floors).'
								</select>
								'.JS::event('#sel432657','change','constr_cost(this.value);').'
								<input type="submit" value="Bauen!">
							</form>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="padding:10px;">
								<div id="room_info">
									`&Die Handwerkerzunft '.getsetting('townname','Atrahor').'s steht bereit, um deinen Wünschen nach mehr Gemächern Gestalt zu verleihen. Aus der obigen Liste kannst du wählen, was du bauen möchtest.`0
								</div>
								<div id="constr_cost_div" style="display:none;">
									`^<i>Wenn du über das Erdgeschoß hinaus bauen möchtest, fallen dafür gesonderte Baukosten an. Im Moment: <span id="constr_cost_span">0</span> Edelsteine</i>`0
								</div>').
						'</td>
					</tr>';

		// Table-Footer
		$str_out .= '</table>`c';

		addnav('Zurück',$str_filename);

	break;
	}// END man_rooms

	case 'repair': // Beschädigungen reparieren
	{
		$arr_dmg = utf8_unserialize($row['dmg_info']);

		if(isset($_GET['repair'])) {

			$str_what = $_GET['what'];
			$str_repair = $_GET['repair'];

			if($str_what == 'gems') {
				$int_cost = $g_arr_house_dmg_types[$str_repair]['cost'];
				if($session['user']['gems'] < $int_cost) {
					$str_out .= '`$Du würdest liebend gerne einige Handwerker mit der Behebung der Schäden beauftragen,
									doch leider verfügst du nicht über die dafür nötigen finanziellen Mittel.';
					output($str_out);
					addnav('Etwas anderes versuchen',$str_filename.'?act=repair');
					addnav('Zurück zum Haus',$str_filename);
					page_footer();
				}

				$str_out .= '`tFür '.$int_cost.'`t Edelsteine beauftragst du Handwerker, die die Schäden an '.$row['housename'].'`t beheben!';

				debuglog('repariert für '.$int_cost.' Gems Schäden an Haus Nr.'.$row['houseid']);

				$session['user']['gems'] -= $int_cost;

			}
			elseif($str_what == 'item') {
				$str_itemtpl = $g_arr_house_dmg_types[$str_repair]['repair_item'];
				if(false === ($arr_item = item_get('owner='.$session['user']['acctid'].' AND tpl_id="'.$str_itemtpl.'"',false))) {
					$str_out .= '`$Du würdest sicherlich in ausgezeichneter Weise diese Schäden selbst beheben können,
									doch leider fehlen dir die dazu benötigten Materialien.';
					output($str_out);
					addnav('Etwas anderes versuchen',$str_filename.'?act=repair');
					addnav('Zurück zum Haus',$str_filename);
					page_footer();
				}

				$str_out .= '`tMit '.$arr_item['name'].'`t reparierst du geschickt die Schäden an '.$row['housename'].'`t!';

				debuglog('repariert mit Item '.$arr_item['name'].' Schäden an Haus Nr.'.$row['houseid']);

				item_delete('id='.$arr_item['id'],1);

			}

			unset($arr_dmg[$str_repair]);

			$row['dmg'] = max($row['dmg'] - 100,0);

			$row['dmg_info'] = utf8_serialize($arr_dmg);

			db_query('UPDATE houses SET dmg='.$row['dmg'].',dmg_info="'.db_real_escape_string($row['dmg_info']).'" WHERE houseid='.$row['houseid']);

		}

		$str_out .= '`c`b`&Schäden an '.$row['housename'].':`b`c`n`n';

		if(sizeof($arr_dmg)) {
			$str_out .= '<table>';
			foreach ($arr_dmg as $k=>$d) {

				$str_out .= '<tr><td width="200">'.$g_arr_house_dmg_types[$k]['name'].'</td>';
				$str_out .= '<td>'.create_lnk('Für '.$d['cost'].' Edelsteine Handwerker beauftragen',$str_filename.'?act=repair&repair='.$k.'&what=gems').'</td>';
				$str_out .= '<td>';
				if(isset($d['repair_item'])) {
					$arr_item = item_get_tpl('tpl_id="'.$d['repair_item'],'tpl_name');
					if(false !== $arr_item) {
						$str_out .= create_lnk('Mit '.$arr_item['tpl_name'].' selbst reparieren!',$str_filename.'?act=repair&repair='.$k.'&what=item');
					}
				}
				$str_out .= '</td></tr>';

			}
			$str_out .= '</table>';
		}
		else {
			$str_out .= '`tKeine! Das Haus ist in einwandfreiem Zustand.';
		}

		addnav('Zurück zum Haus',$str_filename);

	break;
	}// END repair

	case 'itemsort': // eingelagerte Items sortieren
	{
		$str_out.=get_title('Möbelrücken').'Hier hast du die Möglichkeit, deine eingelagerten Möbel neu anzuordnen.`n';
		$str_out.=item_set_sort_order('deposit1='.(int)$session['housekey'].' AND deposit2=0 AND owner<1234567');
		addnav('Zurück zum Haus',$str_filename);
		break;
	} //END eingelagerte Items sortieren
	
	case 'set_house_inhabitant_order': //Sortiere Bewohner des Hauses
	{
		$str_out.=get_title('Schlüssel sortieren').'Hier hast du die Möglichkeit, deine Mitbewohner neu anzuordnen.`n';
		$str_out.=keylist_set_sort_order('k.id,a.name,k.sort_order', 'value1='.$row['houseid'].' AND type='.HOUSES_KEY_DEFAULT);
		addnav('Zurück zum Haus',$str_filename);
		break;
	}

	case 'massmail': // Massenmail (by mikay)
	{
		$str_out .= house_get_title('Taubenschlag unter dem Dach des Hauses '.$row['housename'].'`0');

		addnav('Abbrechen',$str_filename);
		addnav('','house_massmail.php?op=send');

		$sql='SELECT acc.acctid, acc.name, acc.login FROM keylist LEFT JOIN accounts acc ON acc.acctid=keylist.owner WHERE keylist.value1='.(int)$row['houseid'].' AND keylist.type='.HOUSES_KEY_DEFAULT.' AND acc.acctid!='.(int)$session['user']['acctid'];
		$result=db_query($sql);
		$users=array();
		$keys=0;

		$residents .= '<input type="checkbox" id="selecctall"/> Alle auswählen<br>';

		while($row=db_fetch_assoc($result))
		{
			$residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" id="inp347834"> '.$row['name'].'
			'.JS::event('#inp347834','click','chk();').'
			<br>';
			$keys++;

			if ($_POST['title']!='' && $_POST['maintext']!='' && in_array($row['acctid'],$_POST['msg']))
			{
				$users[]=$row['acctid'];
			}
		}

		$mailsends=count($users);

		if ($mailsends<=5)
		{
			$gemcost=1;
		}
		elseif ($mailsends<=15)
		{
			$gemcost=2;
		}
		elseif ($mailsends<=25)
		{
			$gemcost=3;
		}
		elseif ($mailsends>25)
		{
			$gemcost=4;
		}

		if ($session['user']['gems']>=$gemcost AND $mailsends>0)
		{
			foreach($users as $id)
			{
				systemmail($id, $_POST['title'], $_POST['maintext'], $session['user']['acctid']);
			}

			$sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler haben eine Taube erhalten und deine Kosten betragen '.$gemcost.' Edelsteine.<br><br>';
			$session['user']['gems']-=$gemcost;
		}
		elseif ($session['user']['gems']<$gemcost AND $mailsends>0)
		{
			$sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler hätten eine Taube erhalten, wenn deine Kosten nicht '.$gemcost.' Edelsteine betragen würden. Leider kannst du dies nicht bezahlen.<br><br>';
		}

		if ($keys>0)
		{
			$str_out .= form_header($str_filename.'?act=massmail')
			.$sendresult.'
			<table border="0" cellpadding="0" cellspacing="10">
				<tr>
					<td><b>Betreff:</b></td>
					<td><input type="text" name="title" id="title" value="">
					 '.JS::event('#title','keydown','chk()').'
                                                '.JS::event('#title','focus','chk()').'
					</td>
				</tr>
				<tr>
					<td valign="top"><b>Nachricht:</b></td>
					<td><textarea name="maintext" id="maintext" rows="15" cols="50" class="input"></textarea>
					 '.JS::event('#maintext','keydown','chk()').'
                                                '.JS::event('#maintext','focus','chk()').'
					</td>
				</tr>
				<tr>
					<td valign="top"><b>Senden an:</b></td>
					<td>'.$residents.'
						`bKosten bis jetzt:`b <span id="cost">0</span> Edelstein(e)!
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<span id="but" style="visibility:hidden;"><input type="submit" value="Tauben auf die Reise schicken!" class="button"></span>
						<span id="msg">Bitte verfasse nun deine Botschaft und wähle die Empfänger!</span></td>
				</tr>
			</table>
			</form>
			'.JS::MassMail();
		}
		else
		{
			$str_out .= '`c`bEs wurden noch keine Schlüssel verteilt - und ja, Bombentauben an missliebige Nachbarn sind gegen das Gesetz.`b`c';
		}

	break;
	}// END massmail

	case 'cancel_build': // Bau abbrechen
	{
		$str_out .= house_get_title('Ausbau abbrechen!');

		if(isset($_GET['ok'])) {

			db_query('UPDATE houses SET extension=0,build_state=0 WHERE houseid='.$row['houseid']);

			// Wenn Extension bereits in Datenbank und Level 0: Löschen
			if(isset($arr_current_extension)) {
				if($arr_current_extension['level'] == 0) {
					db_query('DELETE FROM house_extensions WHERE id='.$arr_current_extension['id']);
					// Cache zurücksetzen
					if(!Cache::delete(Cache::CACHE_TYPE_HDD,'houserooms'.$row['houseid']))
					{
						admin_output('Cachereset (houserooms'.$row['houseid'].') funzt nicht',true);
					}
				}
			}

			// Nur zur Sicherheit
			$row['extension'] = $row['build_state'] = 0;

			$str_out .= 'Du brichst den Ausbau in diesem Haus ab. Damit kannst du auch wieder einen anderen Aus- oder Anbau beginnen.`nAlles, was über die normale Schatzkammerkapazität hinaus an Gold oder Edelsteinen gelagert war, musste dem Abriss weichen.';

			addnav('Zurück ins Haus',$str_filename);
		}
		else {
			$str_out .= 'Willst du deinen gegenwärtigen Ausbau wirklich abbrechen? Alles, was du über die normale Kapazität hinaus in den Schatz einbezahlt hast, würde für den Abriss benötigt und damit verlorengehen!';

			addnav('Ja, Abbruch!',$str_filename.'?act=cancel_build&ok=1',false,false,false,false);
			addnav('Nein, zurück!',$str_filename);
		}


	break;
	}// END cancel_build

	default: // Eingangsscreen
	{
		// Folgende Navihotkeys garantieren
		$accesskeys['w']=1;$accesskeys['d']=1;$accesskeys['m']=1;$accesskeys['l']=1;$accesskeys['r']=1;

		$show_invent = true;
		$str_out = house_get_title($row['housename'].'`0 ('.get_house_state($row['status'],$row['build_state'],false).'`0)');

		// Ausbaustatus anzeigen
		switch($row['build_state']) {
			case HOUSES_BUILD_STATE_EXT:
				$str_what = $g_arr_house_extensions[$arr_current_extension['type']]['name'];
				$str_out .= '`c`i`&In diesem Haus wird gerade an "`^'.$str_what.'`&" gebaut!`n
							Kosten: '.$int_upgr_gems.' Edelstein(e), '.$int_upgr_gold.' Gold'.(isset($arr_current_extension['content']['bonus']) ? ' (mit Gutschrift)' : '').'`0`i`c`n';
			break;
			case HOUSES_BUILD_STATE_IP:
				$arr_what = $g_arr_house_builds[$row['extension']];
				$str_out .= '`c`i`&Dieses Haus wird gerade zu eine'.($arr_what['sex'] ? 'r' : 'm').' '.$arr_what['name'].' ausgebaut.`n
							Kosten: '.$int_upgr_gems.' Edelstein(e), '.$int_upgr_gold.' Gold`0`i`c`n';
			break;
			case HOUSES_BUILD_STATE_ABANDONED:
				$str_out .= '`c`$Dieses Haus ist verlassen und seine Möglichkeiten stark beschränkt - derzeit wird nach einem neuen Käufer dafür gesucht!`n';
				$int_left = (int)getsetting('houseabandonedmintime',864000) - (time() - strtotime($row['lastchange']));
				$int_daylen = (int)(86400 / (int)getsetting('daysperday',4));
				$str_out .= '`i(Noch '.max(ceil($int_left / $int_daylen),0).' '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage').', bis das Haus zum Verkauf gestellt wird!)`i`0`c`n';
			break;
			default:

			break;
		}

		$str_h_ava = CPicture::get_image_path($row['owner'],'h',1);

		if(($str_h_ava)) {
			//$str_out .= '<img src="'.$str_h_ava.'" alt="Hausavatar" style="float:right;margin:4px;">';
			$h_img = '[PIC=h]';
			CPicture::replace_pic_tags($h_img, $row['owner']);
			$h_img = str_replace('alt="Kein Bild gefunden!"', 'alt="Hausavatar" style="float:right;margin:4px;"', $h_img);
			$str_out .= $h_img;
		}

		// Beschädigungen
		if($row['dmg'] >= 100) {
			$str_out .= '`$Dieses Haus ist beschädigt! Möchtest du es nicht vielleicht reparieren? '.create_lnk('Ja, gerne!',$str_filename.'?act=repair').'`0`n`n';
		}

		if ($row['description'])
		{
			$row['description'] = strip_tags(closetags($row['description'],'`c`i`b'));
			CPicture::replace_pic_tags($row['description'],$row['owner']);
			$str_out.='`0`c'.$row['description'].'`0`c`n';
		}

		$str_out.='`0Du und deine Mitbewohner haben `^'.$row['gold'].'`0 Gold und `#'.$row['gems'].'`0 Edelsteine im Haus gelagert.`n';
		if (getsetting('activategamedate','0')==1)
		{
			$str_out.='Wir schreiben den `^'.getgamedate().'`0.`n';
		}
		$str_out.='Es ist jetzt `^'.getgametime(true).'`0 Uhr.`n`n<div style="clear:right;">&nbsp;</div>';
		output($str_out);

		addnav("Hausschatz");
		if($row['build_state'] != HOUSES_BUILD_STATE_ABANDONED) {
			addnav("G?Gold deponieren","$str_filename?act=givegold");
		}
		// Wenn Hausbesitzer
		//if($row['owner'] == $session['user']['acctid']) {
			addnav("n?Gold mitnehmen","$str_filename?act=takegold");
		//}
		addnav(':');
		if($row['build_state'] != HOUSES_BUILD_STATE_ABANDONED) {
			addnav("E?Edelsteine deponieren","$str_filename?act=givegems");
		}
		//if($row['owner'] == $session['user']['acctid']) {
			addnav("t?Edelsteine mitnehmen","$str_filename?act=takegems");
		//}


		if ($row['owner'] != $session['user']['acctid'])
		{
			addnav("Schlüssel");
			addnav('Zurückgeben',$str_filename.'?act=keyback',false,false,false,false);
		}

		// Ausbauten-Links
		if ($row['status'] && $row['build_state'] != HOUSES_BUILD_STATE_ABANDONED)
		{
			addnav('Besonderes');

			$accesskeys['r']=0;
			addnav('R?Rasten',$str_filename.'?act=rest');

			if(sizeof($arr_current_build['navs'])) {
				foreach ($arr_current_build['navs'] as $str_txt => $str_lnk) {
					// Wenn Code
					if($str_txt == 'code' && true === $str_lnk) {
						house_build_run('navi',$row['status'],$row);
					}
					else {
						addnav($str_txt,$str_lnk);
					}
				}
			}
		}
		// END Ausbauten-Links
				
		// Extensionarray zusammenstellen
		$arr_extensions = array();
		$sql = 'SELECT he.* FROM house_extensions he
				WHERE he.houseid='.$row['houseid'].' AND he.loc IS null AND he.level > 0';
		$res = db_query($sql);

		if(db_num_rows($res)) {

			while($e = db_fetch_assoc($res)) {
				
				$arr_type = $g_arr_house_extensions[$e['type']];
				$str_lnk = 'house_extensions.php?_ext_id='.$e['id'];

				if(!isset($arr_type['locked_right']) || $access_control->su_check($arr_type['locked_right'])) {
					$arr_extensions[$arr_type['name']] = $str_lnk;
				}

			}
		}

		db_free_result($res);
		// END Extensionsarray zusammenstellen
		
		// Raumnavis anzeigen
		house_set_room_navs($session['user']['acctid'],$row['houseid']);

		// Extensionsnnavis
		if(count($arr_extensions)) {
			addnav('Orte');
			foreach ($arr_extensions as $str_txt => $str_lnk) {
				addnav($str_txt,$str_lnk);
			}
		}

		//Specials laden
		spc_get_special('houses_inside',7);

		$comment_length=max($row['c_max_length'],getsetting('chat_post_len',600));
		viewcommentary('house-'.$row['houseid'],'Mit Mitbewohnern reden:',30,'sagt',false,true,false,$comment_length,true);

		$str_out='`n`n
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr class="frame_label">
										<td class="frame_label_l" width="46"/>
										<td class="frame_label" height="24">`tSonstiges`0</td>
										<td class="frame_label_r" width="46"/>
									</tr>
								</table>
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td class="frame_border_l" />
										<td class="frame_main" valign="top" style="text-align:left;">
					<table border="0" width="100%"><tr><td width="50%">`t`bDie Schlüssel von ('.CRPChat::menulink($row).'`t):`b `0</td><td>`t`bMobiliar:`b`0</td></tr><tr><td valign="top">';

		$sql = 'SELECT 	keylist.*,
						a.acctid AS aid, a.name AS besitzer, a.restatlocation, a.laston, a.login, a.location
				FROM keylist
				LEFT JOIN accounts a ON a.acctid=keylist.owner
				WHERE value1='.$row['houseid'].' AND keylist.type='.HOUSES_KEY_DEFAULT.'
				ORDER BY sort_order DESC, id ASC';
		$result = db_query($sql);
		$int_keycount = db_num_rows($result);

		// Nach wie vielen Sekunden werden Inaktive gelöscht?
		// Bei 90% der verstrichenen Zeit eine Warnung anzeigen
		$int_deluser = round((int)getsetting('expireoldacct',50) * 0.85 * 86400);
		$int_deluser_vacation = round((int)getsetting('expirevacationacct',180) * 0.95 * 86400);
		// Point of no return
		$int_warningtime = time() - $int_deluser;
		$int_warningtime_vacation = time() - $int_deluser_vacation;

		$str_key_out = '';
		$int_keys_avail = 0;	// freie Schlüssel

		for ($i=1; $i<=$int_keycount; $i++)
		{
			$item = db_fetch_assoc($result);
			if ($item['besitzer']=='')
			{
				$str_key_out.='`n`t'.$i.': `4`iVerloren`i`0';
			}
			else
			{
				$str_key_out.='`n';

				if ($item['aid']==$row['owner'])
				{
					$str_key_out .='`t'.$i.': `ifrei`i`0';
					$int_keys_avail++;
				}
				else {
					// Wenn Hauseigentümer, haben wir noch ein paar Rechte
					if($row['owner'] == $session['user']['acctid']) {
						$str_key_out .= '`0[ '.create_lnk('X',$str_filename.'?act=takekey&key='.$item['id'],true,false,'Bist Du sicher, dass du diesen Schlüssel wieder abnehmen möchtest?').' ] ';
					}

					$str_key_out .= '`t'.$i.': `0'.create_lnk('`&'.$item['besitzer'].'`0','bio.php?id='.$item['aid'],true,false,false,true).' ';

					if ($item['restatlocation'] == $row['houseid'] && $item['owner']>0)
					{
						$str_key_out.=' `ischläft hier`i';
					}

					// Warnung für Hausbesitzer, falls User vor Löschung steht
					if($row['owner'] == $session['user']['acctid'] || $Char->isSuperuser()) {
						$int_laston = strtotime($item['laston']);
						if(
							($int_laston <= $int_warningtime_vacation && $item['location'] == USER_LOC_VACATION) || 
							($int_laston <= $int_warningtime && $item['location'] != USER_LOC_VACATION)
						) 
						{
							$str_key_out.=' `$(Verschwindet bald)`0 ';
						}
					}
				}	// END nicht Eigentümer

			}	// END vorhanden

		}
		
		if($row['owner'] == $session['user']['acctid']) {
			$str_key_out .= '`n`0['.create_lnk('umsortieren',$str_filename.'?act=set_house_inhabitant_order',true,false).']';
		}

		// Wenn noch Schlüssel frei:
		if($session['user']['acctid'] == $row['owner']) {
			if($int_keys_avail) {
				// Schlüssel
				$str_givekey_lnk = $str_filename.'?act=givekey';
				$str_key_out = '
							<div id="search_div">
							`tDu hast noch `b'.$int_keys_avail.'`b Schlüssel frei.`n
							Schlüssel vergeben an:`n`n`0
							'.form_header($str_givekey_lnk,'POST',true,'search_form','if(document.getElementById(\'search_sel\').selectedIndex > -1) {this.submit();} else {search();return false;}').'
								'.jslib_search('document.getElementById("search_form").submit();','Schlüssel vergeben!').'
							</form>
							</div>
							'.$str_key_out;
			}
			else {
				$str_key_out = '`tDu hast leider keine Schlüssel mehr frei - aber vielleicht kannst du dir in der Jägerhütte einen nachmachen lassen.`n'.$str_key_out;
			}
		}

		$str_out .= $str_key_out;

		if ($row['owner'] != $session['user']['acctid'] && db_num_rows(db_query('SELECT acctid FROM accounts WHERE acctid='.$row['owner'].' AND restatlocation='.$row['houseid'].' AND location='.USER_LOC_HOUSE))>0) {
			$str_out.='`nDer Eigentümer schläft hier';
		}
		$str_out.='</td><td valign="top">';

		$str_out .= house_show_furniture($session['housekey'],0,($row['owner'] == $session['user']['acctid']),(HOUSES_BUILD_STATE_ABANDONED != $row['build_state'] ? array('furniture') : array()));

		$str_out.='</td></tr></table>
					</td>
										<td class="frame_border_r" />
									</tr>
								</table>
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr class="frame_label_b">
										<td class="frame_label_lb" width="46"/>
										<td class="frame_label" height="24"><img src="./images/frame/zier_b2.png"/></td>
										<td class="frame_label_rb" width="46"/>
									</tr>
								</table>
							</td>
						</tr>
						</table>
					';

		//Spezielle Links für Hauseigentümer
		if ($row['owner'] == $session['user']['acctid'])
		{
			addnav("Hausverwaltung");
			addnav('Gemächer',$str_filename.'?act=man_rooms');
			addnav("Haus umbenennen","$str_filename?act=rename",false,false,false,false);
			addnav("Beschreibung ändern","$str_filename?act=desc",false,false,false,false);
			addnav("Kommentarlänge ändern","$str_filename?act=c_le_change",false,false,false,false);
			addnav('Möbel rücken',$str_filename.'?act=itemsort',false,false,false,false);
			addnav('Taubenschlag',$str_filename.'?act=massmail',false,false,false,false);
			addnav('Bauen');
			// Wenn grade nichts im Bau:
			if($row['build_state'] == 0) {
				// Wenn noch kein Ausbau getätigt:
				if (empty($arr_current_build))
				{
					addnav("Haus ausbauen",$str_filename.'?act=build_buildings',false,false,false,false);
				}
				else {
					// Wenn weitere Ausbaustufen verfügbar:
					if(sizeof($arr_current_build['next']))
					{
						addnav("Haus weiter ausbauen","$str_filename?act=build_buildings",false,false,false,false);
					}
					addnav("Ausbau entfernen",$str_filename.'?act=rip_builds',false,false,false,false);
				}

				// Erweiterungen
				addnav('Anbauten',$str_filename.'?act=build_extensions',false,false,false,false);

			}
			elseif(
					$row['build_state'] == HOUSES_BUILD_STATE_INIT ||
					$row['build_state'] == HOUSES_BUILD_STATE_EXT ||
					$row['build_state'] == HOUSES_BUILD_STATE_IP) {

					addnav('`^Weiterbauen!`0',$str_filename.'?act=build_menu');
					addnav('`$Bau abbrechen!`0',$str_filename.'?act=cancel_build',false,false,false,false);
			}
			if($session['user']['exchangequest']==24) //Tauschquest
			{
				$indate = getsetting('gamedate','0005-01-01');
				$date = explode('-',$indate);
				$monat = $date[1];
				$tag = $date[2];
				if ($monat==12 && $tag<6)
				{
					addnav('`%Inventar aufräumen`0','exchangequest.php');
				}
			} //end Tauschquest
		}
		//END Spezielle Links für Hauseigentümer


		// Folgende Navihotkeys garantieren
		$accesskeys['w']=0;$accesskeys['d']=0;$accesskeys['m']=0;$accesskeys['l']=0;

		addnav('Ausgang');
		//by Salator: hadnewday nicht auf 1 setzen und anschließenden redirect sparen. addnav('L?Einschlafen (Log Out)','$str_filename?act=logout');
		addnav('L?Einschlafen (Log Out)','login.php?op=logout&loc='.USER_LOC_HOUSE.'&restatloc='.$row['houseid']);
		addnav('W?Zurück zum Wohnviertel','houses.php?op=enter');
		addnav('d?Zurück zum Stadtzentrum','village.php');
		addnav('M?Zurück zum Marktplatz','market.php');

		// DEBUG
		house_add_dmg($row,1,100);
	break;
	}// END default

}
// END switch (act)

// Ausgabe vornehmen und Seite schließen
output($str_out,true);
page_footer();
?>