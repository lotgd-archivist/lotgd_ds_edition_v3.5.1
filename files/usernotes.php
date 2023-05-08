<?php
/**
*
* @author Salator
* 
*/

require_once('common.php');
$str_filename=basename(__FILE__);

if(!isset($session))
{
	echo('$session nicht definiert in '.$str_filename.'');
	exit();
}

$townname=getsetting('townname','Atrahor');
popup_header(' Notizblock für '.$session['user']['login'].' in '.$townname);

$int_maxLen = getsetting('bioextranotesmaxlength',4096);

if(-1 == $int_maxLen) {
	output('`$Funktion deaktiviert!');
	popup_footer(false);
	exit;
}

$str_output .= JS::encapsulate('window.resizeTo(800,600);');
if(isset($_POST['bio_extra_notes']))
{

	$str_extranotes = closetags($_POST['bio_extra_notes'],'`i`b`c`H');

	//ungültige tags herausfiltern
	$str_extranotes = clean_html($str_extranotes);

	$max_l = getsetting('longbiomaxlength',4096);
	$str_extranotes = mb_substr($str_extranotes,0,$max_l);

	user_set_aei(array('bio_extra_notes' => $str_extranotes));
}


//------------------------------ Link-Bereich------------------------------//

$str_output.= '	<a href="prefs.php">`bProfil`b</a>
				<hr>';

//------------------------------ Notizen-Bereich------------------------------//


$arr_data=user_get_aei('bio_extra_notes');
$arr_form=array(
	'bio_extra_notes'=>',textarea,80,20,'.$int_maxLen.',true'
	);

$str_output.='<form action="'.$str_filename.'" method="POST">';
output($str_output);
showform($arr_form,$arr_data);
$str_output='</form>
<hr>
'.nl2br($arr_data['bio_extra_notes']).'
<br>';

output($str_output);
popup_footer(false);
?>
