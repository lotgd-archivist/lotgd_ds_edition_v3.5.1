<?php

function schaedel_hook_process ( $item_hook , &$item ) {

	global $session,$item_hook_info;

	switch ( $item_hook ) {

		case 'gift':	// Nach Versenden des Geschenks

		$gefallen=e_rand(5,10);
		
		user_update(
			array
			(
				'deathpower'=>array('sql'=>true,'value'=>'deathpower+'.$gefallen)
			),
			$item_hook_info['acctid']
		);

		$item_hook_info['effect'] = 'Du untersuchst dieses merkwürdige Geschenk genauer. Dabei rutscht es dir aus der Hand und zerplatzt am Boden in 1000 Stücke. Doch eine seltsame Kraft wird frei, die dir '.$gefallen.' Gefallen bei Ramius bringt!';

		$session['user']['gold'] -= $item['tpl_gold'];
		$session['user']['gems'] -= $item['tpl_gems'];

		$item_hook_info['hookstop'] = true;

		break;
	}
}

?>