<?php

function feenstaub_hook_process ( $item_hook , &$item ) {

	global $session,$item_hook_info;

	switch ( $item_hook ) {

		case 'gift':	// Nach Versenden des Geschenks

		$what=e_rand(1,5);

		switch($what)
		{
			case 1:
				$item_hook_info['effect'] = 'Du öffnest die geheimnisvolle Dose vorsichtig und schaust hinein. Feiner Staub steigt aus ihr hinauf und nebelt dich völlig ein. Als sich der Staub verflüchtigt hat, stellst du fest, dass deine Haut zäher geworden ist.`nDeine Lebenspunkte erhöhen sich permanent um 2!';

				user_update(
				array
				(
				'maxhitpoints'=>array('sql'=>true,'value'=>'maxhitpoints+2')
				),
				$item_hook_info['acctid']
				);

				break;
			case 2:
				$item_hook_info['effect'] = 'Du öffnest die geheimnisvolle Dose vorsichtig und schaust hinein. Ein bunter Wirbel schillernder Farben strömt aus der Dose und hüllt deinen Körper ein. Mit dir geschieht eine Verwandlung.`nDu wirst um 5 Tage verjüngt!';

				$sql = "SELECT age FROM accounts WHERE acctid=".$item_hook_info['acctid'];
				$result = db_query($sql);
				$row = db_fetch_assoc($result);

				if ($row['age']>5)
				{
					user_update(
						array
						(
							'age'=>array('sql'=>true,'value'=>'age-5')
						),
						$item_hook_info['acctid']
					);
				}
				else
				{
					user_update(
						array
						(
							'age'=>1
						),
						$item_hook_info['acctid']
						);
				}

				break;
			case 3:
				$item_hook_info['effect'] = 'Du öffnest die geheimnisvolle Dose vorsichtig und schaust hinein. Ein Wölkchen feinen Staubes steigt auf dringt in deine Atemwege ein. In der Zeit, in der du bewusstlos bist, hast du eigenenartige Träume, die dir wie Visionen vorkommen.`nDu erhältst 300 Erfahrungspunkte!';
				user_update(
					array
					(
						'experience'=>array('sql'=>true,'value'=>'experience+300')
					),
					$item_hook_info['acctid']
				);
				break;
			case 4:
				$item_hook_info['effect'] = 'Du öffnest die geheimnisvolle Dose vorsichtig und schaust hinein. Sofort wirbelt das feine Pulver, das darin aufbewahrt wurde, auf und hüllt dich in schillerndem Glanz ein. Mit der Zeit verfliegt das Pulver, jedoch bleibt ein leichter schillernder Schein auf deiner Haut zurück.`nDu erhältst 3 Charmepunkte!';
				user_update(
					array
					(
						'charm'=>array('sql'=>true,'value'=>'charm+3')
					),
					$item_hook_info['acctid']
				);
				break;
			case 5:
				$item_hook_info['effect'] = 'Du öffnest die geheimnisvolle Dose vorsichtig und schaust hinein. Dabei musst du leider jedoch niesen, und das seltsam schillernde Pulver, das in der Dose aufbewahrt war, verstreut sich überall um dich herum ohne einen Effekt zu hinterlassen.';
				break;

		}

		$session['user']['gold'] -= $item['tpl_gold'];
		$session['user']['gems'] -= $item['tpl_gems'];

		$item_hook_info['hookstop'] = true;

		break;

	}


}

?>
