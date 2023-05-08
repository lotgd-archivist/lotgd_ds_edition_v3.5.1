<?php
/**
 * Start processing a special-file on any location in the script
 *
 * @param String 	$str_category 		The category in which the special file can be found
 * @param int 		$int_probability 	The probability by which a special will be called - Should be between 0 and 1000, so the value is in promille
 * @param String 	$str_explicit_file 	If given this will include the given file (if it can be found)
 * @param String 	$str_header 		Defines the page_header for the special
 * @param Bool		$bool_clear_navs	Defines whether existing navs shall be deleted before including the special
 * @param String	$str_entrypoint 	Defines the method entrypoint which will be called upon include of the file. If none is given
 * 										the file will only be included
 * @param Array 	$arr_codehook 		Contains codehooks in the form "name"=>"PHPCODE" which can be called wihtin the special
 * 										"initial_hook"/"finish_hook" are being called at the beginning/end of the file all other hooks
 * 										can be called manually by using spc_call_codehook
 * @uses spc_call_codehook
 * @author Dragonslayer for Atrahor.de
 */
function spc_get_special(
$str_category = 'forest',
$int_probability = 70,
$str_explicit_file = '',
$str_header = '`^`c`bEtwas Besonderes!`c`b`0',
$bool_clear_navs = true,
$str_entrypoint = '',
$arr_codehook = array()
)
{
	global $session,$nav,$output,$accesskeys;

	//Wurde bereits ein Special geladen? Dann wird das gleiche Special jetzt wieder geladen
	if (!empty($session['user']['specialinc']))
	{
		$str_explicit_file = $session['user']['specialinc'];
		$session['user']['specialinc'] = '';

		$arr_nav_backup = $session['user']['allowednavs'];
		$str_nav_backup = $nav;
		if($bool_clear_navs == true)
		{
			$str_nav_backup = $nav;
			clearoutput();
		}

		page_header(strip_appoencode($str_header));
		output($str_header);

		include('special/'.$str_explicit_file);

		//Damit der User nicht hängen bleibt:
		//Hat das Special navs geschrieben dann führ ein page_footer aus
		//Ansonsten schreibe das nav_backup_zurück
		if (is_array($session['allowednavs']) && count($session['allowednavs'])!=0)
		{
			page_footer();
		}
		else
		{
			$nav = $str_nav_backup;
			$session['user']['allowednavs'] = $arr_nav_backup;
			
			return;
		}
	}

	//Wurde eine spezielle Datei angegeben? Dann ist die wahrscheinlichkeit dass es auftritt = 100
	if($str_explicit_file != '')
	{
		$int_probability = 1000;
	}

	$int_random = e_rand(1,1000);

	/*Testing the random generator
	if($int_random<=$int_probability)
	$output .= '<font color="red">'.$int_random.'('.$int_probability.') - </font>';
	else
	$output .= $int_random.'('.$int_probability.') - ';
	//return;*/

	if ($int_random<=$int_probability)
	{
		if(!empty($str_explicit_file))
		{
			$str_sql = 'SELECT filename FROM special_events WHERE filename="'.$str_explicit_file.'" AND released=1 AND dk <='.$session['user']['dragonkills'];
		}
		else
		{
			$str_sql = 'SELECT filename FROM special_events LEFT JOIN special_category USING (category_id) WHERE category_name="'.$str_category.'" AND prio <= '.e_rand(0,3).' AND dk <='.$session['user']['dragonkills'].' AND released=1 ORDER BY RAND() LIMIT 1';
		}

		$db_result = db_query($str_sql);
		if(db_num_rows($db_result)==0)
		{
			return;
		}

		$str_special = db_result($db_result,0,'filename');
		unset($db_result);

		if ($str_special !== false)
		{
			$y = $HTTP_GET_VARS['op'];
			$HTTP_GET_VARS['op']='';
			$yy = $_GET['op'];
			$_GET['op']='';

			$output = '';
			page_header(strip_appoencode($str_header));
			output($str_header);

			$arr_nav_backup = $session['user']['allowednavs'];
			$str_nav_backup = $nav;
			
			if($bool_clear_navs == true)
			{				
				clearoutput();
			}

			spc_call_codehook($arr_codehook, 'initial_hook');

			include('special/'.$str_special);

			if($str_entrypoint != '' && function_exists($str_entrypoint))
			{
				$str_entrypoint();
			}
			elseif($str_entrypoint != '' && function_exists($str_entrypoint) === false)
			{
				admin_output('Die gewünschte Methode '.$str_entrypoint.' existiert nicht');
			}

			spc_call_codehook($arr_codehook, 'finish_hook');

			if($bool_clear_navs == false)
			{
				$session['user']['allowednavs'] = $arr_nav_backup;
				$nav = $str_nav_backup;
			}
			$session['specialinc_debug'] = $str_special;

			//db_query("UPDATE special SET anzahl=anzahl+1 WHERE filename='".$str_special."';");
			$HTTP_GET_VARS['op']=$y;
			$_GET['op']=$yy;

			page_footer();
		}
		else
		{
			admin_output('Das gesuchte Special '.$str_special.' existiert nicht');
		}
	}
}

/**
 * Calls a codehook from an array of codehooks
 *
 * @param Array 	$arr_codehook 		Contains codehooks in the form "name"=>"PHPCODE" which can be called wihtin the special
 * 										"initial_hook"/"finish_hook" are being called at the beginning/end of the file all other hooks
 * 										can be called manually by using spc_call_codehook
 * @param String $str_codehook_name		Tells which codehook to call
 * @author  Dragonslayer for Atrahor
 */
function spc_call_codehook(&$arr_codehook, $str_codehook_name)
{
	if(count($arr_codehook)!=0)
	{
		if(array_key_exists($str_codehook_name,$arr_codehook) == true)
		{
            eval(utf8_eval($arr_codehook[$str_codehook_name]));
		}
	}
}
?>