<?php
/**
 * gametime.lib.php: Funktionen, die im weiteren Sinne mit Zeit und Datum zusammenhängen
 * @author LOGD-Core / Drachenserver-Team
 * @version DS-E V/2
 */

/**
 * Prüft auf neuen Tag ( is_new_day() ) und leitet gegebenenfalls Newday-Aktionen ein
 *
 */
function checkday() {
	global $session,$revertsession,$REQUEST_URI,$output;

	if ($session['user']['loggedin']){
		$output .= '<!--CheckNewDay()-->';
		
		// Talion: Wenn POST-Backup vorhanden: Zurückspielen 
		if(isset($session['post_backup'])) {
			
			$_POST = $session['post_backup'];
			unset($session['post_backup']);
			$output .= '<!--POST-Backup recovered-->';
	
		}
		
		if(is_new_day()){
			// Evtl. noch herumschwirrende Kommentare abfangen. by talion
			//addcommentary();

			// Ursprungszustand der Session bei Seitenstart wiederherstellen
			$session=$revertsession;
			
			// Gesamten POST-Bereich in Session zwischenlagern
			if(sizeof($_POST) > 0) {
				$session['post_backup'] = $_POST;
				$output .= '<!--POST-Backup done-->';
			}
			
			$session['user']['restorepage']=$REQUEST_URI;
			$session['allowednavs']=array();
			redirect('newday.php'.(mb_strpos($REQUEST_URI,'badnav.php') > -1 ? '?badnav=1':''));
		}
	}
}

/**
 * Berechnet, ob User einen neuen Tag erhält.
 *
 * @return bool true, wenn ja, sonst false
 */
function is_new_day(){
	global $session;
	$t1 = gametime();
	$t2 = convertgametime(strtotime($session['user']['lasthit']));
	$d1 = date('Y-m-d',$t1);
	$d2 = date('Y-m-d',$t2);
	if ($d1!=$d2)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * Ermittelt aktuelle Ingamezeit
 *
 * @return string Ingamezeit im Standardformat, das in den Settings angegeben ist.
 */
function getgametime($clock=false){
	$time = convertgametime(strtotime(date('r')),$clock);
	return date(getsetting('gametimeformat','g:i a'), $time );
}

/**
 * Kleine Helferfunktion gibt aktuell in den Settings gespeichertes Rohspieldatum zurück
 *
 * @return string Spieldatum im Format y-m-d
 */
function get_raw_gamedate ()
{
	return getsetting('gamedate','0005-01-01');
}

/**
 * Speichert Komponenten des Spieldatums in einem assoziat. Array:
 *  d => Tag
 *  m => Monat
 *  y => Jahr
 * Wenn invalides Datum übergeben wird, schreibt Funktion Meldung in das Syslog
 *
 * @param array &$arr_parts Referenz auf einen Array, der die Datumskomponenten aufnimmt
 * @param string $str_gamedate Spieldatum, das zerlegt werden soll (im Format y-m-d). Falls null (Standard), wird aktuelles Spieldatum verwendet.
 */
function get_gamedate_parts (&$arr_parts, $str_gamedate = null)
{
	
	$arr_tmp_parts = $arr_parts = array();
	
	if(is_null($str_gamedate))
	{
		$str_gamedate = get_raw_gamedate();
	}
	
	$arr_tmp_parts = explode('-',$str_gamedate);
	
	// Kein valides Datum
	if(count($arr_tmp_parts) != 3)
	{
		systemlog('`^Code-Fehler: Invalides Datum in get_gamedate_parts(), Param str_gamedate = '.$str_gamedate.'; Seite: '.$GLOBALS['SCRIPT_NAME']);
	}
	
	$arr_parts['y'] = (isset($arr_tmp_parts[0]) ? (int)$arr_tmp_parts[0] : 1);
	$arr_parts['m'] = (isset($arr_tmp_parts[1]) ? (int)$arr_tmp_parts[1] : 1);
	$arr_parts['d'] = (isset($arr_tmp_parts[2]) ? (int)$arr_tmp_parts[2] : 1);
}

/**
 * Gibt gewählte Komponente des Spieldatums zurück:
 *  d => Tag
 *  m => Monat
 *  y => Jahr
 * Wenn invalides Datum übergeben wird, schreibt Funktion Meldung in das Syslog
 *
 * @param string $str_part Gewünschte Datumskomponente (y | m | d)
 * @param string $str_gamedate Spieldatum, das zerlegt werden soll (im Format y-m-d). Falls null (Standard), wird aktuelles Spieldatum verwendet.
 */
function get_gamedate_part ($str_part,$str_gamedate = null)
{
	
	$arr_tmp = array();
	
	if(is_null($str_gamedate))
	{
		$str_gamedate = get_raw_gamedate();
	}
	
	$arr_tmp = explode('-',$str_gamedate);
	
	// Kein valides Datum
	if(count($arr_tmp) != 3)
	{
		systemlog('`^Code-Fehler: Invalides Datum in get_gamedate_part(), Param str_gamedate = '.$str_gamedate.'; Seite: '.$GLOBALS['SCRIPT_NAME']);
	}
	
	switch($str_part)
	{
		case 'y': return (isset ( $arr_tmp[0] ) ? (int)$arr_tmp[0] : 1);
		case 'm': return (isset ( $arr_tmp[1] ) ? (int)$arr_tmp[1] : 1);
		case 'd': return (isset ( $arr_tmp[2] ) ? (int)$arr_tmp[2] : 1);
		default : echo('Fehler: '.$str_part.' als Datumsteil nicht bekannt!'); return -1;
	}

}

/**
 * Gibt gewählte Komponente der Spielzeit zurück:
 *  h => Stunde
 *  m => Minute
 * @param string $str_part Gewünschte Zeitkomponente (h | m)
 * @param string $str_gametime Spielzeit diezerlegt werden soll (im Format hh:mm). Falls null (Standard), wird aktuelle Spielzeit verwendet.
 * @param bool $clock Sollen tagesabschnitte berücksichtigt werden
 * @return string
 */
function get_gametime_part($str_part, $str_gametime =  null, $clock = true)
{
	$arr_tmp = array();
	
	if(is_null($str_gametime))
	{
		$str_gametime = getgametime($clock);
	}
	
	$arr_tmp = explode(':',$str_gametime);
	
	switch($str_part)
	{
		case 'h': return (isset ( $arr_tmp[0] ) ? (int)$arr_tmp[0] : 1);
		case 'm': return (isset ( $arr_tmp[1] ) ? (int)$arr_tmp[1] : 1);
		default : echo('Fehler: '.$str_part.' als Zeitteil nicht bekannt!'); return -1;
	}
}

/**
 * Ermittelt aktuelles Spieldatum, formatiert dieses gemäß in den Settings angegebenem Format
 *
 * @param string Vorgegebenes (Spiel-)Datum im Format Y-m-d (optional, ansonsten wird aktuelles Spieldatum verwendet)
 * @return string Formatiertes Spieldatum
 * @author Chaosmaker; modded by talion: monate hinzugefügt, beliebiges Datum kann übergeben werden
 */
function getgamedate($indate='',$no_year=false) {
	$months = array(1=>'Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
	$indate = ($indate != '') ? $indate : get_raw_gamedate();
	
	// Jahr vor Zeitrechnung
	$year_extra = '';
	if(mb_substr($indate,0,1) == '-') {
		$year_extra = ' v.u.Z.';
		$indate = mb_substr($indate,1);
	}
	
	$date = explode('-',$indate);
    $find = array('%Y','%y','%m','%n','%d','%j','%F');

	$replace = array($date[0].$year_extra,sprintf('%02d',$date[0]%10000).$year_extra,sprintf('%02d',$date[1]),(int)$date[1],sprintf('%02d',$date[2]),(int)$date[2],$months[(int)$date[1]]);

    if($no_year)
    {
        return str_replace($find,$replace,'%d. %F');
    }

	return str_replace($find,$replace,getsetting('gamedateformat','%Y-%m-%d'));
}

/**
 * Gibt aktuelle Zeit in Spielzeit zurück
 * @param  $clock bool Sollen Tagesabschnitte berücksichtigt werden?
 * @return int Spielzeit in Sekunden
 */
function gametime($clock = false){
	$time = convertgametime(strtotime(date('r')), false);
	return $time;
}

/**
 * Wandelt Real-Zeit in Spielzeit um. 
 * Ausgehend von anpera-Version neugeschrieben (performanter, sinniger)
 *
 * @param int $intime Timestamp der umzuwandelnden Zeit
 * @return int Timestamp der umgewandelten Zeit
 * @author talion
 */
function convertgametime($intime,$clock=false){
	
	// Spieltage pro Tag
	$daysperday = getsetting('daysperday',4);
	$dayparts = getsetting("dayparts",1);
	// Es wird von 1 als Normalwert ausgegangen und alle anderen Fälle als Vielfache betrachtet
	$multi = getsetting('daysperday',4);
	if ($clock) $multi = round($multi/$dayparts);
	// Zeitverschiebung des Gametag-Beginns zur vollen Real-Stunde in Sekunden
	$offset = getsetting('gameoffsetseconds',0);
	// Offset an die längeren Ingame-Tage anpassen
	//(Offset von 10500 funktioniert nur bei 12 Ingame-Tagen pro Tag)
	if ($clock)
	{
		$offset += (3600/12) - (3600/$multi);
		//8 Stunden Abzug, damit der "neue Tag" ab 01:00 Uhr Nachts beginnt (aber nur zur Winterzeit).
		$offset -= 8*(3600/$multi) * (1-date("I"));
	}
	
	// Aktuelle Zeit
	$arr_time = getdate();

	// Vergleichszeit berechnen (Sekunden - Zeitverschiebung, Tag und Jahr = RL)
	$fixtime = gmmktime(0,0,0-$offset,$arr_time['mon'],$arr_time['mday'],$arr_time['year']);
	
	// Differenz zwischen gegebener (umzurechnender) Zeit und Vergleichszeit
	// Mit Spieltagen pro Tag multiplizieren
	// Umwandlung Datumsstring und zurück wegen Verschiebung durch Sommerzeit
	$time = $multi * (strtotime(gmdate('Y-m-d H:i:s',$intime)) - $fixtime);
	
	// Umgewandelten Timestamp zurück
	return intval($time);
}

/**
 * Funktion berechnet Abstand in Tagen zwischen zwei Spieldatumsangaben (Format: y-m-d)
 *
 * @param string $str_date1 Spieldatum 1 (y-m-d)
 * @param string $str_date2 Spieldatum 2 (y-m-d)
 * @return int Anzahl der (Spiel)Tage, die zwischen den beiden Daten liegen
 */
function gamedate_diff ($str_date1, $str_date2)
{
	
	$arr_date1 = array();
	$arr_date2 = array();
	
	get_gamedate_parts($arr_date1,$str_date1);
	get_gamedate_parts($arr_date2,$str_date2);	
	
	$int_days1 = gregoriantojd($arr_date1['m'],$arr_date1['d'],$arr_date1['y']);
	$int_days2 = gregoriantojd($arr_date2['m'],$arr_date2['d'],$arr_date2['y']);
	
	if($int_days2 > $int_days1)
	{
		return($int_days2 - $int_days1);
	}
	else 
	{
		return($int_days1 - $int_days2);
	}
	
}


function dhms($secs,$dec=false){
	if ($dec===false) $secs=round($secs,0);
	return (int)($secs/86400).'d'.(int)($secs/3600%24).'h'.(int)($secs/60%60).'m'.($secs%60).($dec?mb_substr($secs-(int)$secs,1):'').'s';
}

/**
 * Fügt Eintrag zu Aufzeichnungen hinzu
 *
 * @param string Nachricht, die hinzugefügt werden soll
 * @param int 0 = alle, 1 = User, 2 = Gilde
 * @param int ID, AcctID oder GuildID;
 * @param string Spieldatum im Format Y-m-d
 * @author talion
 */
function addhistory ($msg,$mode=1,$id=0,$str_gamedate='') {
	global $session;
	
	$id = (int)$id;
	
	$id = ($mode == 1 && $id == 0) ? $session['user']['acctid'] : $id;
	$id = ($mode == 2 && $id == 0 && $session['user']['guildid']) ? $session['user']['guildid'] : $id;

	$str_gamedate = (empty($str_gamedate) ? getsetting('gamedate','0000-00-00') : $str_gamedate);

	if($mode > 0 && $id == 0) {return;}

	db_insert('history',
				array(	'msg'=>$msg,
						'date'=>array('sql'=>true,'value'=>'NOW()'),
						'gamedate'=>$str_gamedate,
						'acctid'=>($mode==1 ? $id : 0),
						'guildid'=>($mode==2 ? $id : 0)
					)
				);
	
}

/**
 * Zeigt Aufzeichnungen.
 *
 * @param int 0 = alle, 1 = User, 2 = Gilde
 * @param int AcctID oder GildenID
 * @param bool Soll Kopf angezeigt werden?
 * @param bool Sollen versteckte Elemente angezeigt werden
 * @param bool Soll der Editiermodus angezeigt werden?
 * @author talion
 */
function show_history ($mode=0, $id=0, $header=false, $show_hidden = false, $edit = false, $return_html=false, $no_year = false) {
	
	global $session, $access_control;
	
	$id = (int)$id;
	if($mode > 0 && $id == 0)
	{
		return;
	}
	
	$str_output = '';
	
	if($header) {
		$header = 'Geschichte ';
		
		if($mode == 1) {
			$sql = 'SELECT name FROM accounts WHERE acctid='.$id;
			$res = db_query($sql);
			$player = db_fetch_assoc($res);
			$header .= 'von '.$player['name'];
		}
		elseif($mode == 2) {
			$guild = &dg_load_guild($id,array('name'));
			$header .= 'der Gilde '.$guild['name'];
		}
		else {
			$header .= 'der Stadt';
		}
		$str_output .= '`b'.$header.':`b`n`n';
	}
	$str_hidden_entries = '';
	if($show_hidden == false)
	{
		$str_hidden_entries = ' AND hidden = 0 ';
	}
	$sql = 'SELECT * FROM history WHERE '.($mode == 0 ? 'acctid=0 AND guildid=0' : ($mode == 1 ? 'acctid='.$id : 'guildid='.$id) ).' '.$str_hidden_entries.' ORDER BY gamedate DESC, id DESC';
	$res = db_query($sql);
	
	$str_output.='<table border="0" cellpadding="0" cellspacing="8" width="100%"> ';
	if(db_num_rows($res) == 0) {
		$str_output .= '<tr><td>`iNoch keine besonderen Ereignisse überliefert!`i</td></tr></table>';
		if($return_html == true)
		{
			return $str_output;
		}
		else
		{
			output($str_output);
			return;
		}
	}
	
	$lastyear = 0;
	$year = 0;

    $pos = array();
    $neg = array();
    $data = array();

    while($h = db_fetch_assoc($res))
    {
        $dt = explode('-',$h['gamedate']);
        if(mb_substr($h['gamedate'],0,1) == '-') $neg[intval($dt[1])][]=$h;
        else $pos[]=$h;

    }
    $neg2 = array();
    foreach($neg as $k => $v)
    {
        $neg2 = array_merge($v,$neg2);
    }

    //rsort($neg);
    $data = array_merge($pos,$neg2);

     foreach($data as $h)
     {
		$dt = explode('-',$h['gamedate']);
		$year = (empty($dt[0])?(intval($dt[1]).' v.u.Z'):intval($dt[0]));
		//$year = (int)date('y',strtotime($h['gamedate']));
		if($year !== $lastyear)
		{
			$str_output .= '<tr><td colspan="3">`b`&Ereignisse des Jahres '.$year.'`0`b`n`n</td></tr>';
			$lastyear = $year;
		}
		
		//@DS Der Inhalt wird rechts vom Datum gezeigt als wäre es eine Tabellenzelle
        $own = false;

		 $out = $h['msg'];
		 $out = clean_html($out,true,true,false,true,true,true);
		 if($mode == 1){
			 $out =  utf8_preg_replace_callback("/(\[.+\])/iUS",array('CSteckbrief','replace_names'),$out);
			 $out =  utf8_preg_replace_callback("/(\(.+\))/iUS",array('CSteckbrief','replace_cnames'),$out);
		 }
		 $out = str_ireplace(array('http','javascript','vbscript','expression','data:text','base64'),'',$out);
		 $out = closetags($out,'`b`c`i');
		 $h['msg'] =  str_replace("`^Besonderes Ereignis:`0","`y~`0",$out);


		 $out = $h['text'];
		 $out = clean_html($out,true,true,false,true,true,true);
		 if($mode == 1){
			 $out =  utf8_preg_replace_callback("/(\[.+\])/iUS",array('CSteckbrief','replace_names'),$out);
			 $out =  utf8_preg_replace_callback("/(\(.+\))/iUS",array('CSteckbrief','replace_cnames'),$out);
		 }
		 $out = str_ireplace(array('http','javascript','vbscript','expression','data:text','base64'),'',$out);
		 $out = closetags($out,'`b`c`i');
		 $h['text'] =  $out;

        if( '`y~`0' == mb_substr(trim($h['msg']),0,5))
        {
            $h['msg'] = mb_substr($h['msg'],5);
            $own = true;
        }

        $str_output .= '<tr><td style="padding-left:35px;" width="100px">`@'.getgamedate($h['gamedate'],$no_year).':</td>
        <td style="vertical-align: middle;">'.($own ? '`y~`0' : '').'</td><td style="vertical-align: middle;">';

         if($h['text']!= '')
         {
             $str_output .= '<div>'.($own ? '`0' : '`^').''.$h['msg'].'`0 '.plu_mi('history_'.$h['acctid'].'_'.$h['id'],0,false).''
                 . '<div id="'.plu_mi_unique_id('history_'.$h['acctid'].'_'.$h['id']).'" style="display:none;">
										<p>'.appoencode($h['text']).'</p>'.'</div></div>';
         }
         else
         {
             $str_output .= ($own ? '`0' : '`^').''.$h['msg'].'`0';
         }

		if(
			(
			//User darf sich selbst editieren
			($edit == true && $mode == 1 && $session['user']['acctid'] == $id) ||
			//User darf editieren wenn er in der Gilde ist und außerdem Gildenvorsteher (noch nicht getestet)
			($edit == true && $mode == 2 && $session['user']['guildid'] == $id && $session['user']['guildfunc'] == DG_FUNC_LEADER)
			)
		)
		{
			$str_output .= '`&['.jslib_int_switch('prefs.php?on_off_history=1&id='.$h['id'],$h['hidden'],'Versteckt','Sichtbar').']`0';
		}
		if($access_control->su_check(access_control::SU_RIGHT_NEWS)) {
			$link = 'bio.php?op=del_history&history_id='.$h['id'].'&id='.$id;
			$str_output .= ' `&[ <a href="'.utf8_htmlentities($link).'" onclick="return confirm(\'Wirklich löschen?\');">Del</a> ]`0';
		}
		
		$str_output .= '</td></tr>';
	}
	if($access_control->su_check(access_control::SU_RIGHT_NEWS))
	{
		addpregnav('/bio.php\?op=del_history&history_id=\d+&id='.$id.'/');
	}
	
	$str_output .= '</table>';
	if($return_html == true)
	{
		return appoencode($str_output);
	}
	else
	{
		output($str_output);
	}
}
?>