<?php
/**
 * houses_httpreq.php: Zuständig für Alujax-Aktionen des Wohnviertels
 * @author talion
 * @version DS-E V/3
*/

$DONT_OVERWRITE_NAV 	= true;
$BOOL_JS_HTTP_REQUEST 	= true;
require_once('common.php');
require_once(LIB_PATH.'house.lib.php');

switch( $_GET['op'] ){

	// Möbelstück 'live' auslagern
	case 'furniture_out':

		$int_id = (int)$_GET['id'];

		if($int_id == 0) {
			exit;
		}

		$arr_item = item_get('id='.$int_id.' AND owner='.$session['user']['acctid'],true,'furniture_hook,furniture_private_hook,name');

		if(false === $arr_item) {
			exit;
		}

		item_set('id='.$int_id.' AND owner='.$session['user']['acctid'],array('deposit1'=>0,'deposit2'=>0),false,1);

		// Wenn Hook besteht (und damit Navi):
/*		if(!empty($arr_item['furniture_hook']) || !empty($arr_item['furniture_private_hook'])) {
			redirect('Location:inside_houses.php');
			exit;
		}*/

		// Erfolgsmeldung, Item ausblenden
		$str_back = '/mb Du packst '.$arr_item['name'].'`0 wieder zurück in dein Inventar.';

	break;


	case 'h_bio':

		// Hausid
		$int_id = (int)$_GET['id'];

		if($int_id == 0) {
			exit;
		}

		$str_output = '';
		$bool_save = false;

		$sql='SELECT houses.*,accounts.name AS besitzer FROM houses LEFT JOIN accounts ON accounts.acctid=houses.owner WHERE houseid='.$int_id;
		$result = db_query($sql);

		if(!db_num_rows($result)) {
			$str_output .= 'Dieses Haus existiert nicht!';
			echo($str_output);
			exit;
		}

		$row = db_fetch_assoc($result);

		$trick = utf8_unserialize($row['trick']);
		$dmg_info = utf8_unserialize($row['dmg_info']);

		$str_output .= '`0Du näherst dich Haus Nummer '.$row['houseid'].', um es aus der Nähe zu betrachten. ';
		if ($row['description'])
		{
			$str_output .= 'Du betrachtest '.$row['housename'].'`0 genauer:<br>`& '.appoencode(strip_tags($row['description'])).'`0<br><br>';
		}
		else
		{
			$str_output .= 'Das Haus trägt den Namen "`&'.$row['housename'].'`0".<br>';
		}

		$str_h_ava = CPicture::get_image_path($row['owner'],'h',1);
		$str_output .= '<br><div align="center" style="text-align:center;">';
	    if(($str_h_ava)) {
       		$str_output .= '<img src="'.$str_h_ava.'" alt="Haus # '.$row['houseid'].'" style="border:1px dotted #CCCCCC;">`n';
		}
		else {
			$str_output .= '<img src="./images/h_ava_default.jpg" alt="Haus # '.$row['houseid'].'" title="Haus # '.$row['houseid'].' - Kein Hausavatar vorhanden" width="300" height="192" style="border:1px dotted #CCCCCC;">`n';
		}
		$str_output .= '</div>';

		$properties = ' owner < 1234567 AND deposit>0 AND deposit_show=1 AND deposit1='.$row['houseid'].' AND deposit2=0 ';
		$extra = '  ORDER BY gems DESC, gold DESC, id ASC ';
		$result = item_list_get($properties , $extra );
		if ($row['besitzer']=='')
		{
			$row['besitzer']='niemandem';
		}
		$str_output .= '`0Das Haus gehört `^'.$row['besitzer'].'`0 und ist ';
		$str_output .= get_house_state($row['status'],$row['build_state'],true,false).'.<br>';

		// Gemächer
		$sql = 'SELECT loc FROM house_extensions WHERE loc IS NOT null AND level > 0 AND houseid='.$row['houseid'].' GROUP BY loc ORDER BY loc ASC';
		$res = db_query($sql);
		$int_floor_count = db_num_rows($res);
		if($int_floor_count <= 1) {
			$str_output .= 'Das Haus scheint sich nur ebenerdig zu erstrecken.';
		}
		else {
			$str_output .= 'Das Haus erstreckt sich über '.$int_floor_count.' Stockwerke:<br>';
			while($arr_r = db_fetch_assoc($res)) {
				// Zähler
				$int_floor_count--;
				$str_output .= house_get_floor($arr_r['loc'],true).'`0';
				if($int_floor_count) {
					$str_output .= ', ';
				}
			}
		}

		$str_output .= '`0<br><br>Du riskierst einen Blick durch eines der Fenster';

		// Ausbau verbietet näheren Anblick
		if (!$g_arr_house_builds[$row['status']]['invi']) //Hier gibt es nichts zu sehen...
		{
			$maxcount=db_num_rows($result);
			if ($maxcount>0)
			{
				$str_output .= '<br> und erkennst: <div style=" margin-top: 10px; width: 100%; min-height: 0; max-height: 100px; overflow: auto; background: #000; border: 1px solid #aa7800;">';//'<br>'.plu_mi('furn',0,false).' und erkennst: <div id="'.plu_mi_unique_id('furn').'" style="display:none;">';
				for ($i=0; $i<$maxcount; $i++)
				{
					$row2 = db_fetch_assoc($result);
					$str_output .= '`@'.$row2['name'];
					if ($i+1<$maxcount)
					{
						$str_output .= '`n';
					}
				}
				$str_output .= '</div>';
			}
			else
			{
				$str_output .= ' und siehst, dass das Haus sonst nichts weiter zu bieten hat.';
			}
		}
		else
		{
			$str_output .= ' und siehst, dass alle Fensteröffnungen mit dicken Brettern vernagelt wurden.';
		}
		$str_output .= '<br>';

		$pvptime_houses = getsetting('pvptimeout_houses',900);
		$pvptimeout_houses = date('Y-m-d H:i:s',strtotime(date('r').'-'.$pvptime_houses.' seconds'));
		if ($row['pvpflag_houses']>$pvptimeout_houses)
		{
			$str_output .= '`4Du erkennst Einbruchsspuren an diesem Haus. Vermutlich gibt es dort nicht mehr viel zu holen.`0<br>';
		}

		$dung_chance=e_rand(1,2);
		$str_output .= '<br><br>';
		if ($trick['eggs']>0)
		{
			$str_output .= '`^An der Frontseite siehst du die Spuren von '.$trick['eggs'].' Eiern, die jemand gegen das Haus geworfen haben muss.<br>';
		}
		if ($trick['dung'] && $dung_chance==1)
		{
			$str_output .= '`^Igitt! Jemand hat '.$trick['dung'].'`^ direkt vor der Haustür abgelegt!<br>';
		}

		if (($trick['eggs']>0 || ($trick['dung'] && $dung_chance==1)) && $session['user']['acctid']==$row['owner'])
		{
			$str_output .= '<br>`^Als Besitzer dieses Hauses könntest du die Verschmutzung jetzt entfernen.`0<br>';
			$str_output .= create_lnk('Schrubben','houses.php?op=scrub&id='.$row['houseid']).'<br>';
			$bool_save = true;
		}

		if($row['dmg'] >= 100) {
			$str_output .= '`$Das Haus sieht schon etwas heruntergekommen aus. Du erkennst: `&';
			foreach ($dmg_info as $k=>$d) {
				$str_output .= $g_arr_house_dmg_types[$k]['name'].', ';
			}
			// Komma wegmachen
			$str_output = mb_substr($str_output,mb_strlen($str_output)-2,2);
		}

		$str_output .= '<hr />';

		//Schabernack
		$item_s = item_get(' (tpl_id="thedung" OR tpl_id="eiersch") AND owner='.$session['user']['acctid'],false);
		if ($item_s)
		{
			$str_output .= '<div style="float:left;">'.create_lnk('<img src="./images/icons/herz.gif" alt="Schabernack" border="0"> `qSchabernack`0','houses.php?op=trick&id='.$row['houseid']).'</div>';
			$bool_save = true;
		}

		// Einbruch
		if($row['owner'] != $session['user']['acctid']) {
			if (getsetting('pvp',1)==1 && getsetting('demouser_acctid',0)!=$session['user']['acctid'])
			{
				if (($session['user']['profession']>0) && ($session['user']['profession']<3))
				{
					$str_output .= '<div style="float:right;">'.create_lnk('<img src="./images/icons/waffe.gif" alt="Razzia" border="0"> `$Razzia`0','houses_pvp.php?op=einbruch&id='.$row['houseid'],true,false,'Wirklich Razzia durchführen..?').'</div>';
					$bool_save = true;
				}
				else
				{
					if (( ($session['user']['profession'] != PROF_TEMPLE_SERVANT)  ) || ($access_control->su_check(access_control::SU_RIGHT_DEBUG)))  //&& ($session['user']['age'] <= getsetting('maxagepvp',50))
					{
						$str_output .= '<div style="float:right;">'.create_lnk('<img src="./images/icons/waffe.gif" alt="Einbruch" border="0"> `$Einbruch`0','houses_pvp.php?op=einbruch&id='.$row['houseid'],true,false,'Wirklich einbrechen..?'.($session['user']['pvpflag']==PVP_IMMU ? ' Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!' : '')).'</div>';
						$bool_save = true;
					}
				}
			}
		}

		// Einkaufen ; )
		if($session['user']['house'] == 0 && $row['build_state'] == HOUSES_BUILD_STATE_SELL) {
			if($row['owner'] == 0) {
				extract(house_get_price($row));
			}
			else {
				$gold = 0;
				$gems = 0;
			}

			$gold += $row['gold'];
			$gems += $row['gems'];

			$str_output .= '<div style="float:right;clear:both;width:100%;">';
			if($row['owner'] == $session['user']['acctid']) {
				$str_output .= '`^Dies ist dein Haus, das zum Verkauf steht.`0<br>
								'.create_lnk('<img src="./images/icons/gold.gif" alt="Verkauf abbrechen!" border="0"> `^Verkauf abbrechen!`0','houses.php?op=buy&id='.$row['houseid'],true,false,'Verkauf wirklich abbrechen..?');
			}
			else {
				$str_output .= '`^Dieses Haus steht zum Verkauf für '.$gold.' Gold und '.$gems.' Edelsteine!`0<br>'
								.create_lnk('<img src="./images/icons/gold.gif" alt="Kaufen!" border="0"> `^Kaufen!`0','houses.php?op=buy&id='.$row['houseid'],true,false,'Haus wirklich kaufen..?');
			}

			$str_output .= '</div>';
			$bool_save = true;
		}

		if($bool_save) {
			// Wegen allowednavs
			saveuser();
		}
		session_write_close();

		header('Content-Type: text/xml; charset:utf-8');
		echo('<?xml version="1.0" encoding="UTF-8"?>
				<root><![CDATA['.$str_output.']]></root>');
		exit;

	break;

	case 'h_page':

		$str_output = '';

		// Parameter zusammenstellen
		$int_p = (int)$_GET['p'];
		$int_p = max($int_p,1);
		$int_maxp = (int)$_GET['maxp'];

		include_once('houses_view.inc.php');

		header('Content-Type: text/xml; charset:utf-8');
		echo('<?xml version="1.0" encoding="UTF-8"?>
				<root><![CDATA['.houses_view_get_out($int_p,$int_maxp,houses_view_get_search()).']]></root>');

		session_write_close();


		// Wenn wir uns dem Timeout nähern: Mal updaten
		// laston_back wird in user_load gesetzt und enthält den Wert des letzten lastons
		if($session['lasthit'] - strtotime($session['laston_back']) > getsetting('LOGINTIMEOUT',900) * 0.5) {
			
			user_update(
				array
				(
					'laston'=>array('sql'=>true,'value'=>'NOW()'),
				),
				$session['user']['acctid']
			);
		}
		exit;

	break;

}

session_write_close();
jslib_http_command($str_back);

exit;

?>