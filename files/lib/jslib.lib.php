<?php
$BOOL_JSLIB_NEEDED = true;

define('HTTPREQ_FLAG_NEW_MAIL',			1);
define('HTTPREQ_FLAG_NEW_MOTD',			2);

define('DYN_DIV_EXISTS', 				1);						//div wird nicht angelegt, weil er schon existiert
define('DYN_DIRECT_OUTPUT', 			2);						//output() wird aufgerufen
define('DYN_SPLIT_RETURN', 				4);						//array als return mit js=> und div=>
define('DYN_CLEAR_JS', 					8 & DYN_SPLIT_RETURN);	//js wird ohne script-tags ausgegeben (implementiert automatisch DYN_SPLIT_RETURN)
define('DYN_CONNECT_TO_PLUMI', 			16);					//onPLUMI_[ID] wird gesetzt
define('DYN_NO_ADDNAV', 				32);					//addnav wird nicht aufgerufen
define('DYN_PLUMI_HIDE', 				64);					//PLUMI-DIV ist versteckt (zugeklappt)
define('DYN_LOAD_ONCE', 				128);					//Inhalt wird nur einmal geladen
define('DYN_NO_PROCESSVIEW', 			256);					//Lade... und Fehlgeschlagen... wird nicht angezeigt

define('JSLIB_HTTP_XML', 				1);
define('JSLIB_HTTP_TEXT', 				2);


require_once(LIB_PATH.'profession.lib.php');


function jslib_http_command( $txt, $http_mode=JSLIB_HTTP_XML ){
	jslib_http_text_output($txt, $http_mode, 'command');
}

/**
 * Setzt den HTTP-Header
 * @param int $http_mode ausgabemodus (siehe JSLIB_HTTP_XML, JSLIB_HTTP_TEXT)
 */
function jslib_setHTTPmode($http_mode=JSLIB_HTTP_XML){
	if($http_mode == JSLIB_HTTP_XML){
		header('Content-Type: text/xml; charset:utf-8');
	}
	else if($http_mode == JSLIB_HTTP_TEXT){
		header('Content-Type: text/plain; charset:utf-8');
	}
}

/**
 * gibt ein JSON-Objekt aus
 *
 * @param mixed $var Variable die umgesetzt werden soll
 * @param int $http_mode ausgabemodus (siehe JSLIB_HTTP_XML, JSLIB_HTTP_TEXT)
 * @return void
 */
function jslib_http_json( $var, $http_mode=JSLIB_HTTP_XML ){
	$str = json_encode($var);
	jslib_http_text_output($str, $http_mode, 'json');
}


/**
 * gibt Text für einen Request aus (=Response :>)
 * @param string $str_content Text der ausgegeben werden soll
 * @param int $http_mode ausgabemodus (siehe JSLIB_HTTP_XML, JSLIB_HTTP_TEXT)
 * @param string $root Wurzelname bei XML
 */
function jslib_http_text_output( $str_content, $http_mode=JSLIB_HTTP_XML, $root="root", $cdata=true ){
	jslib_setHTTPmode($http_mode);
	session_write_close();
	if($http_mode == JSLIB_HTTP_XML){
		die('<?xml version="1.0" encoding="UTF-8"?><'.$root.'>'.($cdata ? '<![CDATA[' : '').$str_content.($cdata ? ']]>' : '').'</'.$root.'>');
	}
	else if($http_mode == JSLIB_HTTP_TEXT){
		die($str_content);
	}
}

/**
 * erstellt div + loader für dynamischen inhalt
 * Die funktion heisst DYN_CONTENT_$str_htmlID(url) parameter url zum manipulieren der URL
 *
 * @param string $str_htmlID ID des div
 * @param string $str_url Welche url soll aufgerufen werden
 * @param int $int_options Bitweise-oder-verknüpfte FLAGS siehe DYN_...
 * @param string $str_loading Text, der im div eingeblendet wird, wenn geladen wird
 * @return mixed HTML + JS Quelltext oder Array
 */
function jslib_dyn_content($str_htmlID, $str_url, $int_options=0, $str_loading='Lade...'){
	$str_script = '';
	if( !defined('JSLIB_DYN_CONTENT_CALLED') ){
		define('JSLIB_DYN_CONTENT_CALLED',23);
		$str_script .= 'LOTGD.loadLibrary("httprequest");
						var jslib_dyn_content_request_loaded = [];
						function jslib_dyn_content_request(id, url, loading){
							var obj = document.getElementById(id);
							if( loading != -1 ){
								obj.innerHTML = loading;
							}
							r = new LOTGD.HTTPRequest();
							r.send(	url,
									new Function("r", "var o = document.getElementById(\'"+id+"\');g_dyn_content_"+id+"_loaded = true;o.innerHTML = r.responseXML.getElementsByTagName(\'root\')[0].firstChild.nodeValue;"),
									new Function("r", "var o = document.getElementById(\'"+id+"\');"+(loading!=-1 ? "o.innerHTML = \'Fehlgeschlagen!\';" : "")),
									null);
						}';
	}

	$str_script .= 'var g_dyn_content_'.$str_htmlID.'_loaded = false;
					function DYN_CONTENT_'.$str_htmlID.'(url){
						'.($int_options & DYN_LOAD_ONCE ? 'if(g_dyn_content_'.$str_htmlID.'_loaded){return;}':'').'
						jslib_dyn_content_request("'.$str_htmlID.'", (isSet(url) ? url : "'.$str_url.'"), '.( $int_options & DYN_NO_PROCESSVIEW ? '-1' : '"'.$str_loading.'"').');
					}
				';

	if( $int_options ^ DYN_NO_ADDNAV ){
		addnav('',$str_url);
	}

	if( $int_options & DYN_CONNECT_TO_PLUMI ){
		$str_script .= 'function onPLUMI_'.$str_htmlID.'(show){
							if( show ){
								DYN_CONTENT_'.$str_htmlID.'();
							}
						}';
	}

	if( $int_options ^ DYN_CLEAR_JS ){
		$str_script = JS::encapsulate($str_script);
	}

	if( $int_options ^ DYN_DIV_EXISTS ){
		$str_div = '<div id="'.$str_htmlID.'" '.($int_options & DYN_PLUMI_HIDE ? 'style="display:none;"':'').'></div>';
	}

	if( $int_options & DYN_SPLIT_RETURN ){
		return array('js'=>$str_script, 'div'=>$str_div);
	}

	$str_ret = $str_script.$str_div;

	if( $int_options & DYN_DIRECT_OUTPUT ){
		output($str_ret);
	}

	return $str_ret;
}


function jslib_init(){
	global $BOOL_JSLIB_PLU_MI,$session, $quickkeys, $template;

	if(!isset($session['user']['prefs']['chef_kommt'])) $session['user']['prefs']['chef_kommt'] = false;
	if( $session['user']['prefs']['chef_kommt']==1 ){
		$str_chef = '<div id="LOTGD_CHEF" style="display:none; position: absolute; left: 0px; top: 0px; z-index: 800000"></div>'.JS::event('#LOTGD_CHEF','click','chef(27);').'';
		$str_js_chef = '
				var g_chef_old_title = "";
				ret = LOTGD.loadImages(["./images/chef.png"]);
				if( ret.length ){
					obj = $("LOTGD_CHEF");
					if( obj ){
						var w = (Browser.isIe ? document.documentElement.clientWidth || document.body.clientWidth : window.innerWidth);
						if( w>1258 ){
							ret[0].width = w;
						}
						obj.appendChild( ret[0] );
					}
				}
				function chef( kk ){
					if( kk == 27 ){
						obj = $("LOTGD_CHEF");
						if( obj ){
							with( obj.style ){
								if( display == "block" ){
									display = "none";
									document.title = g_chef_old_title;
								}
								else{
									window.scrollTo(0,0);
									display = "block";
									g_chef_old_title = document.title;
									document.title = "Tabelle 1";
								}
							}
							return true;
						}
					}
					return false;
				}

		';
	}
	else{
		$str_js_chef = 'function chef( kk ){return false;}';
	}
	if(!isset($str_chef))$str_chef='';
	if(!isset( $session['user']['superuser'] )) $session['user']['superuser'] = false;
	if(!isset($session['user']['acctid']  )) $session['user']['acctid'] = 0;
	if(!isset( $session['user']['login'] )) $session['user']['login'] ='';
	if(!isset( $str_output ))$str_output ='';
   // $str_output .= '<div class="js" style="width: 100%; text-align: center; background-color: #DEC55D; color: #000; font-size: 16px; position: fixed; top: 0; left: 0; padding: 8px; z-index: 999999;">Ohne Javascript kannst du dieses Browsergame leider nur sehr stark eingeschränkt nutzen!</div>';
    $str_output .= $str_chef.'
    <div class="nojs" style="display:none;">
			<div id="LOTGD_scroll_obj0" style="background: #000;position: fixed;display: none;border-width:1px;border-style:solid;border-color: #AA1111;z-index:3"><img src="./jslib/img/scroll0.gif" /></div>
			<div id="LOTGD_scroll_obj1" style="background: #000;position: fixed;display: none;border-width:1px;border-style:solid;border-color: #AA1111;z-index:3"><img src="./jslib/img/scroll1.gif" /></div>
			<div id="LOTGD_scroll_obj2" style="background: #000;position: fixed;display: none;border-width:1px;border-style:solid;border-color: #AA1111;z-index:3"><img src="./jslib/img/scroll2.gif" /></div>
			<div id="LOTGD_scroll_obj3" style="background: #000;position: fixed;display: none;border-width:1px;border-style:solid;border-color: #AA1111;z-index:3"><img src="./jslib/img/scroll3.gif" /></div>
			<div id="LOTGD_MESSAGEBOX_DIV" class="mb"><table cellspacing="0" cellpadding="0"><tbody><tr class="trhead"><td id="LOTGD_MESSAGEBOX_HEADER" colspan="4" class="head"/><td class="mb_button_l"><a id="LOTGD_MESSAGEBOX_CLOSE" href="javascript:void(0);"><img src="./jslib/img/x.gif" width="12" height="12"/></a></td></tr><tr class="trmiddle"><td id="LOTGD_MESSAGEBOX_MIDDLE" colspan="5" class="middle"/></tr><tr class="trhead"><td class="mb_button_r"><a id="LOTGD_MESSAGEBOX_FIRST" href="javascript:void(0);" title="Erste Nachricht">««</a></td><td class="mb_button_r"><a id="LOTGD_MESSAGEBOX_PREV" href="javascript:void(0);" title="Vorherige Nachricht">«</a></td><td id="LOTGD_MESSAGEBOX_NUMBER" class="mb_button_m"/><td class="mb_button_l"><a id="LOTGD_MESSAGEBOX_NEXT" href="javascript:void(0);" title="Nächste Nachricht">»</a></td><td class="mb_button_l"><a id="LOTGD_MESSAGEBOX_LAST" href="javascript:void(0);" title="Letzte Nachricht">»»</a></td></tr></tbody></table></div>
			<div id="LOTGD_HINT" class="lotgdHint" style="display: none;"></div>
    </div>
    <span id="LOTGD_JS_LIBS">'.JS::encapsulate('./jslib/lotgd.lib.js',true,true).'</span>';
    $js_out = 'LOTGD.m_colors = "'.regex_appoencode(1,false).'";
				LOTGD.m_su	   = '.(access_control::is_superuser()?'true':'false').';
				LOTGD.m_acctid = '.((int)$session['user']['acctid']).';
				LOTGD.m_login  = "'.addslashes($session['user']['login']).'";
				LOTGD.addEvent(document,"keydown",lotgd_keynav);
				LOTGD.addEvent(document,"keypress",lotgd_keynav);
				'.(!defined('JSLIB_NO_FOCUS_NEEDED')?'LOTGD.m_on_document_loaded.push( function(){self.focus();} );':'').'
				'.$str_js_chef.'
				function lotgd_keynav(e){
					var c,trg,altk,ctrlk,ev,cd, nn, link=false;
					if (window.event != null) {
						ev = window.event;
						trg = (ev.srcElement) ? ev.srcElement : ev.target;
						cd =  (ev.keyCode ? ev.keyCode : ev.which);
					}
					else {
						ev = e;
						trg = (ev.originalTarget) ? ev.originalTarget : ev.target;
						cd =  (ev.charCode ? ev.charCode : ev.which);
					}
					c=String.fromCharCode(cd).toUpperCase();
					altk=ev.altKey;
					ctrlk=ev.ctrlKey;
					nn=trg.nodeName.toUpperCase();
					if( ev.type=="keydown" ){
						if( chef(cd) ){
							return;
						}
					}
					if (nn=="INPUT" || nn=="TEXTAREA" || altk || ctrlk){}
					else if(ev.type=="keydown") {
					';
	// Wenn Pfeiltasten als Quickkeys genutzt werden sollen, by talion
	if(isset($quickkeys['arrowleft'])) {
		$val = $quickkeys['arrowleft'];
        $js_out .= '
							if (cd==37) { '.$val.';link=true; }';
		unset($quickkeys['arrowleft']);
	}
	if(isset($quickkeys['arrowup'])) {
		$val = $quickkeys['arrowup'];
        $js_out .= '
							if (cd==38) { '.$val.';link=true; }';
		unset($quickkeys['arrowup']);
	}
	if(isset($quickkeys['arrowright'])) {
		$val = $quickkeys['arrowright'];
        $js_out .= '
							if (cd==39) { '.$val.';link=true; }';
		unset($quickkeys['arrowright']);
	}
	if(isset($quickkeys['arrowdown'])) {
		$val = $quickkeys['arrowdown'];
        $js_out .= '
							if (cd==40) { '.$val.';link=true; }';
		unset($quickkeys['arrowdown']);
	}
	// END Pfeiltasten als Hotkeys
	// Wenn kein keydown
    $js_out.= '}
				else
				{';
	foreach($quickkeys as $key=>$val)
	{
        $js_out.='
							if (c == "'.mb_strtoupper($key).'") { '.$val.';link=true; }';
	}
    $js_out.='
		}
		if( link ){
			if(Browser.isIe){
				ev.cancelBubble= true;
				ev.returnValue = false;
			}
			else{
				ev.preventDefault();
				ev.stopPropagation();
			}
			return false;
		}
	}
			';
	$str_output .= JS::encapsulate($js_out,false,true);
    $str_output .= JS::encapsulate('./jslib/plumi.js',true);
	return $str_output;
}


$g_bool_req_exists = false;

/**
 * Erstellt JavaScript-Codeschnipsel für Initialisierung eines JSLIB-HTTPRequest-Objekts
 * JS-Objektname: g_req
 * Prüft dabei mittels globaler Var $g_bool_req_exists, ob auf aktueller Seite bereits vorhanden
 *
 * @return string Codeteil bzw. leer, falls bereits vorhanden
 */
function jslib_httpreq_init () {

	global $g_bool_req_exists;

	if($g_bool_req_exists) {
		return ('');
	}

	$g_bool_req_exists = true;

	$str_out = '
					var g_req = null;

					if(!isSet(LOTGD.HTTPRequest)) {
						LOTGD.loadLibrary("httprequest");
						new libLoadWaiter("httprequest", function i () {g_req = new LOTGD.HTTPRequest();}, true);
					}
					else {
						if(!isSet(g_req)) {
							g_req = new LOTGD.HTTPRequest();
						}
					}
				';

	return ($str_out);

}

/**
 * Gibt einen JS Quelltext+HTML Link zurück der bei einem Klick den User in den Stealth Mode und wieder zurück bringt
 *
 * @return string HTML + JS Quelltext
 */
function jslib_stealth_switch () {
	global $session;

	$stealth_link = 'httpreq_usermenu.php?op=stealth';
	addnav('',$stealth_link);
	$str_out = '<b><a href="javascript:void(0);" id="switch_stealth">Stealthmode <span id="stealthbut_state">'.($session['user']['activated'] == USER_ACTIVATED_STEALTH ? 'aus' : 'an').'</span>schalten</a> !</b>
	'.JS::event('#switch_stealth','click','switch_stealth()').'
	';

	$str_out .= JS::encapsulate('
		'.jslib_httpreq_init().'
		var g_stealth = '.(USER_ACTIVATED_STEALTH == $session['user']['activated'] ? 'true' : 'false').';

		function switch_stealth () {

			g_req.send( "'.$stealth_link.'",
											function (req) {LOTGD.parseCommand(LOTGD.getCommandFromRequest(req));},
											function () {alert("error bei stealth-switch");},
											null,
											null
									);

			if(!g_stealth) {
				document.getElementById("stealthbut_state").innerHTML = "aus";
				g_stealth = true;
			}
			else {
				document.getElementById("stealthbut_state").innerHTML = "an";
				g_stealth = false;
			}
		}');
	return ($str_out);
}

/**
 * Gibt einen JS Quelltext+HTML Link zurück der bei einem Klick den User in den Stealth Mode und wieder zurück bringt
 *
 * @return string HTML + JS Quelltext
 */
function jslib_quicknav () {
	global $session;

	$jslib_quicknav = 'httpreq.php?op=su_jump';
	addpregnav('/httpreq\.php\?op=su_jump.*/');
	$str_out = '<input id="jslib_quicknav_input" style="width:100%">';

	$str_out .= JS::encapsulate('
		'.jslib_httpreq_init().'
		LOTGD.addEvent(document.getElementById("jslib_quicknav_input"), "keydown", jslib_quicknav);

		function jslib_quicknav (e) {
			k=(e.keyCode?e.keyCode:e.which);
			if(k==13)
			{
				g_req.send( "'.$jslib_quicknav.'&su_jump_file="+document.getElementById("jslib_quicknav_input").value,
											function (req) {if(req.responseText == "error"){document.getElementById("jslib_quicknav_input").style["border"]="1px solid red";}else{LOTGD.parseCommand(LOTGD.getCommandFromRequest(req));}},
											function () {alert("error bei jslib_quicknav");},
											null,
											null
									);
			}
		}');
	return ($str_out);
}

function jslib_mb($content, $header=false, $ret=false){
	$str = JS::encapsulate('
	MessageBox.show("'.addslashes($content).'"'
	.($header!==false ? ',"'.addslashes($header).'"':'')
	.');
	');
	if( $ret ){
		return $str;
	}
	output( $str, true );
}

/**
 * Erstellt den nötigen HTML+JS Code um eine Ajax Suche auszuführen.
 * Das nötige Form element muss separat erstellt werden.
 *
 * @param string $str_search_end Javascriptcode der ausgeführt werden soll wenn auf den Absenden Button geklickt wird.
 * (NICHT der Suchen Knopf, der Absenden Knopf)
 * @param string $str_submit_but Beschriftung des "Absenden" Buttons
 * @param string $str_what Wonach soll gesucht werden definiert in httpreq.php case search
 * @param string $str_prefix Ein Prefix falls mehrere Suchelemente auf einer Seite auftreten müssen
 * @param bool 	 $bool_focus Soll das Suchfeld den Focus erlangen?
 * @return string Gibt den fertzigen HTML Quellcode zurück
 */
function jslib_search ($str_search_end,$str_submit_but='Übernehmen!',$str_what='account',$str_prefix='', $bool_focus = false) {

	$str_search_lnk = 'httpreq.php?op=search&what='.$str_what.'&prefix='.$str_prefix;
	addnav('',$str_search_lnk);

	$str_ret = '
				<span id="'.$str_prefix.'search_state"></span>
				<input type="text" id="'.$str_prefix.'search_in">
				<select id="'.$str_prefix.'search_sel" name="acctid" style="display:none;"></select><br>
				<input type="button" value="Suchen!" id="'.$str_prefix.'search_but" class="button">
				'.JS::event('#'.$str_prefix.'search_but','click',''.$str_prefix.'search();return false;').'
				<input type="button" value="'.$str_submit_but.'" id="'.$str_prefix.'submit_but" style="display:none;" class="button">
				'.JS::event('#'.$str_prefix.'submit_but','click',''.$str_prefix.'search_end();return false;').'
				'.JS::encapsulate('
					'.jslib_httpreq_init().'
					var g_'.$str_prefix.'sel = document.getElementById("'.$str_prefix.'search_sel");
					var g_'.$str_prefix.'in = document.getElementById("'.$str_prefix.'search_in");
					'.($bool_focus?'g_in.focus();':'').'

					function '.$str_prefix.'search () {
						if(!isSet(g_req)) {return;}

						if(g_'.$str_prefix.'in.value.length) {
							post = new LOTGD.HTTPPostVars();
							post.addVar("search",g_'.$str_prefix.'in.value);
							document.getElementById("'.$str_prefix.'search_state").innerHTML = \'<img src="\'+LOTGD.m_dir+\'img/wait.gif" alt="Lade...">Lade...\';
							g_req.send("'.$str_search_lnk.'",
										function (r) {
											LOTGD.parseCommand(LOTGD.getCommandFromRequest(r));
											document.getElementById("'.$str_prefix.'search_state").innerHTML = "";
										},
										function () {MessageBox.show("Es gibt gerade Probleme mit der Suche. Bitte schreibe eine Anfrage!");},
										post,
										null);
						}
						else {
							'.$str_prefix.'search_switch(true);
						}

					}
					function '.$str_prefix.'search_end () {
						if(g_'.$str_prefix.'sel.selectedIndex > -1) {
							'.$str_search_end.'
						}
					}
					function '.$str_prefix.'search_switch (input_on) {
						if(input_on) {
							g_'.$str_prefix.'in.style.display="inline";
							g_'.$str_prefix.'in.focus();
							document.getElementById("'.$str_prefix.'search_but").value="Suchen!";
							document.getElementById("'.$str_prefix.'submit_but").style.display="none";
							g_'.$str_prefix.'sel.style.display="none";
							for(i=g_'.$str_prefix.'sel.length-1;i>=0;i--){
								g_'.$str_prefix.'sel.options[i] = null;
							}
						}
						else {
							g_'.$str_prefix.'in.style.display="none";
							g_'.$str_prefix.'sel.style.display="inline";
							document.getElementById("'.$str_prefix.'search_but").value="Nochmal suchen!";
							document.getElementById("'.$str_prefix.'submit_but").style.display="inline";
							g_'.$str_prefix.'in.value = "";
						}
					}
				');

	return($str_ret);

}

/**
 * Gibt einen JS Quelltext+HTML Link zurück der bei einem Klick einen per Parameter übergebenen
 * Wert zwischen den Werten 1 und 0 switched
 *
 * @param string $str_switch_link Link zur Datei die den JS Command entgegen nimmt
 * @param int $int_state Status des aktuellen Objekts (1: An, 0: aus)
 * @param string $str_on Gibt den Text an der ausgegeben werden soll wenn das Element angeschaltet ist
 * @param string $str_off Gibt den Text an der ausgegeben werden soll wenn das Element ausgeschaltet ist
 * @param string $str_pre Gibt einen optionalen Prefix an, falls der Switch für verschiedene commands
 * auf einer Seite genutzt werden soll.
 * @return string HTML + JS Quelltext
 */
function jslib_int_switch ($str_switch_link, $int_state, $str_on = 'An', $str_off = 'Aus', $str_pre = '') {
	global $session;

	if(!isset($GLOBALS['int_int_switch_count']))
	{
		$GLOBALS['int_int_switch_count'] = 1;
	}
	else
	{
		$GLOBALS['int_int_switch_count']++;
	}
	$int_count = $GLOBALS['int_int_switch_count'];

	addnav('',$str_switch_link);
	$str_out = '<b><a href="javascript:void(0);" id="a_int_switch_state_'.$str_pre.'_'.$int_count.'">
  '.JS::event('#a_int_switch_state_'.$str_pre.'_'.$int_count.'','click','switch_int(\''.$str_switch_link.'\',\'int_switch_state_'.$str_pre.'_'.$int_count.'\', \''.$str_on.'\', \''.$str_off.'\')').'
	<span id="int_switch_state_'.$str_pre.'_'.$int_count.'">'.($int_state ? $str_on : $str_off).'</span></a></b>';
	if($int_count == 1)
	{
		$str_out .= JS::encapsulate('
			'.jslib_httpreq_init().'

			function switch_int (str_int_switch, int_switch_id, str_on, str_off) {
				var g_int_switch = document.getElementById(int_switch_id).innerHTML;

				g_req.send( str_int_switch,
												function (req) {LOTGD.parseCommand(LOTGD.getCommandFromRequest(req));},
												function () {alert("error bei int-switch");},
												null,
												null
										);

				if(g_int_switch == str_on) {
					document.getElementById(int_switch_id).innerHTML = str_off;
					g_stealth = str_off;
				}
				else {
					document.getElementById(int_switch_id).innerHTML = str_on;
					g_stealth = str_on;
				}
			}');
	}

	return ($str_out);
}

/**
 * Gibt den Code für einen Hint zurück
 *
 * @param string $str_text Beschriftung
 * @param string $str_hint_text Hint-Inhalt (auch HTML zugelassen)
 * @param string $str_style optional! oder 'logtdHint', 'logtdHintSweet', 'logtdHintSimple'
 * @return unknown
 */
function jslib_hint( $str_text, $str_hint_text, $str_style='lotgdHintSweet' ){
	$id = 'hint_'.md5(microtime());
	return '<div id='.$id.' style="display:inline;">'.$str_text.'</div>
	'.JS::encapsulate('
		LOTGD.Hint.add( $("'.$id.'"), \''.addslashes(str_replace(array("\n","\r"),'',appoencode($str_hint_text))).'\', false, false, false, "'.$str_style.'");
	');
}

function jslib_messagebox ($str_header, $str_text)
{
	return JS::encapsulate('MessageBox.show("'.addslashes($str_text).'", "'.addslashes($str_header).'");');
}
?>