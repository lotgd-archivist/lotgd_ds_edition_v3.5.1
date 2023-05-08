<?php
/**
* comment2mail.php: Tool, um Chatkommentare an EMail zu versenden
* @author talion <t@ssilo.de>
* @version DS-E V/2.5
* letzte Änderung: 24.05.2010: ingame Datum zum Zeitpunkt des Abschickens hinzugefügt (Fossla)
*/

$str_filename = basename(__FILE__);
require_once('common.php');
require_once(LIB_PATH.'jslib.lib.php');

// Mindestzeitabstand zwischen zwei Sendungen in Sekunden
define('COMMENTMAIL_MIN_INTERVAL',300);

if(!$session['user']['loggedin']) {exit;}

popup_header('Rollenspiel an '.(!empty($session['user']['emailaddress']) ? $session['user']['emailaddress'] : 'EM@ail'),true);

// Wird in der viewcommentary gesetzt, um zu verhindern, dass sensible Sections angezeigt werden
if( empty($session['user']['chat_section']) ) {
	output('`n`b`$Kein Bereich ausgewählt, aus dem Kommentare versendet werden sollen!`b`0`n`n');
	popup_footer();
	exit;
}

if(!is_email($session['user']['emailaddress'])) {
	output('`$Du hast in deinem Profil keine gültige Emailadresse angegeben!');
	popup_footer();
	exit;
}

$str_section = $session['user']['chat_section'];
$int_comments_max = 200;
$str_out = '';
		
if(!isset($session['comment2mail'])) {
	$session['comment2mail'] = array();
	// Letzte Sendung
	$session['comment2mail']['lastsent'] = 0;
	// Section
	$session['comment2mail']['section'] = $str_section;
}
if(!isset($session['comment2mail']['from']) || $session['comment2mail']['section'] != $str_section) {
	// Um Irritationen zu vermeiden:
	if(sizeof($_GET)) {
		$_GET = array();
	}
	// KommentarID VON
	$session['comment2mail']['from'] = 0;
	// KommentarID BIS
	$session['comment2mail']['to'] = 0;	
	// Section
	$session['comment2mail']['section'] = $str_section;
}

$int_lasttime = $session['comsendmail']['lastsent'];

// Zeit warten
if((time() - $int_lasttime) < COMMENTMAIL_MIN_INTERVAL && !access_control::is_superuser()) {
	
	output('`n`$`bDu mußt noch eine Weile warten, ehe du erneut Kommentare versenden darfst!`b`0`n');
	popup_footer();
	exit;
	
}
				
// Befüllen mit Usereingaben
if(isset($_GET['from'])) {
	$session['comment2mail']['from'] = (int)$_GET['from'];
}
if(isset($_GET['to'])) {
	$session['comment2mail']['to'] = (int)$_GET['to'];
}

// to darf nicht vor from liegen
if($session['comment2mail']['to'] > 0 && $session['comment2mail']['to'] < $session['comment2mail']['from']) {
	$session['comment2mail']['to'] = 0;
}
						
$str_out .= '`&`n`n';

// Erster / letzter Kommentar. Wird in Kommentarschleife unten befüllt
$str_start_comment = '';
$str_end_comment = '';
$int_start_time = 0;
$int_end_time = 0;
		
// Query bauen
$str_sql = 'SELECT 	c.*,
					a.name,a.login,a.acctid,a.activated,a.loggedin,a.laston,a.location,a.superuser
			FROM commentary c
				INNER JOIN accounts a ON a.acctid = c.author';
$str_count_sql = '	SELECT 	COUNT(*) AS c
					FROM commentary c
						INNER JOIN accounts a ON a.acctid = c.author
				';

$str_where = ' WHERE 		section="'.db_real_escape_string($str_section).'" AND deleted_by = 0 AND self=1';

if($session['comment2mail']['from']) {
	$str_where .= ' AND commentid >= '.(int)$session['comment2mail']['from'].'';
}
if($session['comment2mail']['to']) {
	$str_where .= ' AND commentid <= '.(int)$session['comment2mail']['to'].'';
}
if(isset($_GET['send']))
{
	// Gewählte Kommentare ausschließen
	if(isset($_POST['exclude']) && $_POST['exclude'] != '0')
	{	
		//fix by bathi
		$str_where .= ' AND commentid NOT IN ('.db_intval_in_string($_POST['exclude']).') ';
	}
}
				
$str_count_sql .= $str_where;
$str_sql .= $str_where;

$str_sql .= ' ORDER BY postdate DESC,commentid DESC';
		
// Seitenansicht
$str_baselnk = $str_filename . '';

// Nur anzeigen, wenn noch keine Auswahl getroffen
if($session['comment2mail']['from'] == 0 && $session['comment2mail']['to'] == 0) {
	$arr_page_res = page_nav($str_baselnk,$str_count_sql,50,' | ','');
}
else {
	// Bestimmen, wie wir den Offset zu setzen haben
	if($session['comment2mail']['to'] == 0 && $session['comment2mail']['from'] > 0) {
		$arr_tmp = db_fetch_assoc(db_query($str_count_sql));
		$int_offset = max($arr_tmp['c']-$int_comments_max,0);
		$arr_page_res['limit'] = $int_offset.',200';
	}
	else {
		$arr_page_res['limit'] = '0,'.$int_comments_max;
	}			
	$arr_page_res['page_nav'] = '`iAuswahl`i';
}
	
// Query abschicken
$str_sql .= ' LIMIT '.$arr_page_res['limit'];
$res = db_query($str_sql);
		
// Ergebnisse ordnen
$arr_results = array();
while($c = db_fetch_assoc($res)) {
							
	$arr_results[$c['commentid']] = $c;
	
}

// Liste erstellen
if(sizeof($arr_results) == 0) {
	
	$str_out .= '`iKein Rollenspiel gefunden!`i';
	
}
else {
	
	$str_out .= '`^`bSeiten:`b `0'.$arr_page_res['page_nav'].'<hr />';
	
	$str_out .= '<div style="height:300px;overflow: auto;padding: 2px;">';
	$str_out .= JS::encapsulate(jslib_httpreq_init());
									
	// passend sortieren (nach Kommentarid aufsteigend)
	ksort($arr_results);
	
	$str_comments = '';
	
	foreach ($arr_results as $c) {
					
		$str_out .= '<div id="'.$c['commentid'].'">'
					.($session['comment2mail']['from'] == 0 ? '<a href="'.$str_filename.'?from='.$c['commentid'].'">&raquo Von hier</a> || ' : 
						($session['comment2mail']['to'] > 0 && ($session['comment2mail']['to'] != $c['commentid'] && $session['comment2mail']['from'] != $c['commentid']) ? '<input type="checkbox" name="ex[]" id="ex" value="'.$c['commentid'].'"> ' : ''));
		//$str_comments .= ''.date('d.m.y H:i',strtotime($c['postdate'])).': ';
														
		// Kommentar parsen
		$c['comment'] = commentaryline($c,false);
		
		$str_out .= $c['comment']
				.($session['comment2mail']['to'] == 0 ? ' || <a href="'.$str_filename.'?to='.$c['commentid'].'">&raquo Bis hier</a> ' : '')
					.'</div>';
		
		if($c['commentid'] == $session['comment2mail']['from']) {
			$str_start_comment = $c['comment'];
			$int_start_time = strtotime($c['postdate']);
		}
		if($c['commentid'] == $session['comment2mail']['to']) {
			$str_end_comment = $c['comment'];
			$int_end_time = strtotime($c['postdate']);
		}
		
		$str_comments .= '<div>'.$c['comment'].'</div>';
		
	}
									
	$str_out .= '<a name="unten"></a>';
	$str_out .= '</div>';				
				 
}

// SENDEN!
if(isset($_GET['send']) && !empty($str_comments)) {
			
	$str_townname = getsetting('townname','Atrahor');
	
	$str_subject_add = mb_substr(stripslashes($_POST['subject']),0,80);
	
	$str_css = '* {
					padding:0px;
					margin:0px;
				}
				body {
					background-color:#000000;
					padding:0px;
					margin:30px;
				}
				body {
					font-family: Verdana, Arial, Helvetica, sans-serif;
					font-size: 12px;
					color: #FFFFFF;
				}'
				.write_appoencode_css();
	
	$body = '<html>
		<head>
			<title>Rollenspiel aus '.$str_townname.'</title>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<style>
			<!--
				'.$str_css.'
			-->
			</style>
		</head>
		<body>
			<b>`c`^Rollenspiel-Archivierung für '.$session['user']['login'].' aus '.$str_townname.'`^, angefertigt am '.date('d.m.Y',time()).
				' (echtzeit) / '.getgamedate().' (ingame)'.
				(!empty($str_subject_add) ? '`n('.$str_subject_add.')' : '').'`c`0</b><br><br>
				`&Zeitraum (echtzeit): '.date('d.m.Y H:i',$int_start_time).' bis '.date('d.m.Y H:i',$int_end_time).'<br /><br />
				<hr />
			';

	$body .= $str_comments;
	
	$body = appoencode($body);
	
	$body .= '</body></html>';
	//Sollte jemand Empfangsprobleme mit 8bit-Kodierung haben muß das ganze noch durch convert.quoted-printable-encode (ab PHP 5.0) geschickt werden
	$session['comment2mail']['lastsent'] = time();
	unset($session['comment2mail']['from']);
	unset($session['comment2mail']['to']);
	unset($session['comment2mail']['section']);
				
	$mail_sender = getsetting('mail_sender_address','');
	
	$div = md5(time());
	$filename = 'rp_'.(!empty($str_subject_add) ? ''.$str_subject_add.'_' : '').''.date('dmyhis',time()).'.html';
	
	$headers='';

	$mailbody .= "Rollenspielarchivierung ".(!empty($str_subject_add) ? ' ('.$str_subject_add.')' : '')." vom ".date('d.m.Y',time())."/".getgamedate()."\n";
	

	$arr_file = array();
	$arr_file['content'] 	= $body;
	$arr_file['name'] 		= $filename;
	$arr_file['encoding'] 	= 'base64';
	$arr_file['type'] 		= 'Content-type: text/html; charset=UTF-8';
	
		
	send_mail($session['user']['emailaddress'],'Rollenspiel aus '.$str_townname.(!empty($str_subject_add) ? ' ('.$str_subject_add.')' : ''),$mailbody,$headers,null,$mail_sender,null,null,array($arr_file));
	
	debuglog('versandte Kommentare an EMail');
	
	output('`&Der gewünschte Ausschnitt wurde an `b'.$session['user']['emailaddress'].'`b gesendet! Du kannst dieses Fenster nun schließen.`n`n
			'.JS::CloseLink('Mach zu, das Ding').'`n`n');
	popup_footer(false);
	exit();
}
// END Senden
		
// Status
$str_selected = '`&Beginn: '.(!empty($str_start_comment) ? $str_start_comment.'`n[ <a href="'.$str_filename.'?from=0">Verwerfen</a> ]' : '`iNoch nicht gewählt`i').'`n
				`&Ende: '.(!empty($str_end_comment) ? $str_end_comment.'`n[ <a href="'.$str_filename.'?to=0">Verwerfen</a> ]' : '`iNoch nicht gewählt`i');	

if($session['comment2mail']['from'] == 0 || $session['comment2mail']['to'] == 0) {
	$str_out = '`c`b`^1. Schritt`b`c`n`&
					Wähle den `bBeginn`b und das `bEnde`b deiner Auswahl. Beachte hierbei bitte,
					dass du maximal '.$int_comments_max.' Kommentare auf einmal versenden kannst und mindestens '.(COMMENTMAIL_MIN_INTERVAL).' Sekunden zwischen zwei Sendungen liegen müssen!`n`n'
				.$str_selected							
				.$str_out;
		
}
// Formatierung wählen
else {
	
	$arr_form = array
					(
						'subject'=>'Zusätzlicher Betreff / Kurzbeschreibung,text,80'
					//	'dates'=>'Datum + Uhrzeit vor Posts anzeigen,checkbox,1'
					);
		
	$str_out = '`c`b`^2. Schritt`b`c`n`&
					Nun kannst du noch einige Einstellungen zum Aussehen der Mail vornehmen und Kommentare vom Versand ausschließen, indem du sie unten markierst - wenn dir nichts mehr einfällt:`n
					Den Senden-Button findest du unter deinem Mausfinger.`n`n
					`c<form method="POST" action="'.$str_filename.'?send=1" id="form6643">
					<input type="hidden" name="exclude" id="exclude" value="0">
					'.generateform($arr_form,array('dates'=>1),false,'&raquo; Senden! &laquo;').'
					</form>`c
					'.JS::event('#form6643','submit','var e = document.getElementsByName(\'ex[]\');for(i=0;i<e.length;i++){document.getElementById(\'exclude\').value+=(e[i].checked?\',\'+e[i].value:\'\');}').'
					`n`n'
				.$str_selected
				.'`n`n`i(Um Kommentare vom Versand auszuschließen: Setze ein Häkchen davor!)`i'
				.$str_out;
}
// END Status

// Fire!		
output($str_out,true);		

popup_footer(false);

?>
