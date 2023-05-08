<?php
define('COMMENTARY_DISCIPLE', 1);
define('COMMENTARY_SPAM', 2);
define('SPAM_CLASSIFIER_NAME','comments');
$rcomment_sections = array('village'=>1,'marketplace'=>1,'garden'=>1,'expedition_wastes'=>1,'prison'=>1,'pool'=>1,'fishing'=>1,'witch'=>1, 'grassyfield'=>1);
$rcomment_sections_inside = array('prison'=>1);
$rcomment_sections_public = array('village'=>1,'marketplace'=>1,'garden'=>1,'expedition_wastes'=>1,'prison'=>1,'pool'=>1,'fishing'=>1, 'grassyfield'=>1);
// Wenn auf true, wird eine Kommentarsektion auf dieser Seite angezeigt
$BOOL_COMMENTAREA = false;
// Wenn auf true, wurde vom User ein Kommentar geschrieben
$bool_comment_written = false;

function commentaryline($cm,$linkbios=true,$cache=false){
    global $session;
    return $cm['cache'];
}

function processcomment($comm) {
	
	global $Char;
    $proc = CRPChat::process($Char->acctid, $comm);
    return $proc['comment'];
}

function addcommentary($alc=true)
{
	global $Char,$session,$allownonnav,$SCRIPT_NAME,$BOOL_JS_HTTP_REQUEST;

    $Char->chat_section = $session['chatconfig']['section'];
    CRPChat::$section = $session['chatconfig']['section'];
    CRPChat::savePost($_POST['chat_text']);

    //section des user zurücksetzen
    if( !$BOOL_JS_HTTP_REQUEST && $SCRIPT_NAME!='badnav.php' && !$allownonnav[$SCRIPT_NAME]){
        $Char->chat_section = '';
    }
}

function viewcommentary($section,
						$message="Kommentar hinzufügen?",
						$limit=25,
						$talkline="sagt",
						$showdate=false,
						$show_addform=true,
						$specialfuncs=false,
						$long_posting=0,
						$only_rpg=false,
						$linkbios=true,
						$su_min=1,
						$ooc_hint=false) {

	global $Char,$session,$REQUEST_URI,$doublepost,$BOOL_COMMENTAREA,$BOOL_POPUP, $access_control, $template, $bool_return_viewcommentary_output,$global_title;

    if($message != 'X' && $message != '0')
    {
        $BOOL_COMMENTAREA = true;
        $Char->chat_section = $section;
        CRPChat::$section = $section;
        $config['section'] = $section;

        CBookmarks::add_name($section,$global_title);
    }

    $str_output = '';
    $config['message'] = $message;
    $config['show_addform'] = $show_addform;
    $config['talkline'] = $talkline;
    $config['su_min'] = $su_min;


    if($long_posting === true)
    {
        $config['max'] = getsetting('chat_post_len_long',500);
    }
    elseif ($long_posting == 0)
    {
        $config['max'] = getsetting('chat_post_len',500);
    }
    else
    {
        $config['max'] = $long_posting;
    }

    $session['chatconfig'] = $config;

    $str_output .= CRPChat::getchat($config);

    if($bool_return_viewcommentary_output === true)
    {
        return $str_output;
    }
    else
    {
        output($str_output);
        return '';
    }

}

function insertcommentary ($author,$msg,$section,$su_min=1,$self=0,$flags=0) {
    $proc = CRPChat::process($author, $msg, true);
    if(is_array($proc)) CRPChat::insertcommentary($author, $proc['comment'], $proc['cache'], $section, $su_min, $self, $flags );
}



