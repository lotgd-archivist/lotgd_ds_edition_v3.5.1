<?php
define('CHOSEN_EARTH',1);
define('CHOSEN_AIR',2);
define('CHOSEN_FIRE',4);
define('CHOSEN_WATER',8);
define('CHOSEN_SPIRIT',16);
define('CHOSEN_FULL',31);
define('CHOSEN_BLOODGOD',32);
//define('CHOSEN_BLOODCHAMP',33); //jetzt aei.bloodchampdays >0
//define('CHOSEN_BLOODCHAMP_END',35); //jetzt in aei.bloodchampdays >3

/**
 * Ermittelt bereits vorhandene Male aus einer marks-Var
 *
 * @param int Male-Status (i.d.R. session['user']['marks'])
 * @return array  	Gibt den aktuellen Male-Status in der Form 
 * 						array ( 'spirit'=>bool, 'fire'=>bool, 'water'=>bool, 'air'=>bool, 'earth'=>bool )
 * 					zurück
 */
function &get_marks_state ($marks) {
			
	$mark_array = array(
		'bloodgod'=>($marks & 32 ? true : false),
		'spirit'=>($marks & 16 ? true : false),
		'water'=>($marks & 8 ? true : false),
		'fire'=>($marks & 4 ? true : false),
		'air'=>($marks & 2 ? true : false),
		'earth'=>($marks & 1 ? true : false)
		);
	
	return($mark_array);
	
}

?>
