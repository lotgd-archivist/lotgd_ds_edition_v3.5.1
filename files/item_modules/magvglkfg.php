<?php

function magvglkfg_hook_process ( $item_hook , &$item ) {

	global $Char,$session,$item_hook_info;

	$str_out = '';
	switch ( $item_hook ) {

		case 'furniture':
			{
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);

				include_once(LIB_PATH.'boss.lib.php');
				$str_out .= get_title('Der magische Vogelkäfig').'`tNeugierig betrachtest du den magischen Käfig und fragst dich, für was für Wesen man wohl einen solchen brauchen könnte, zumal ein normaler Vogel von dem rötlichen Schimmer der Gitterstäbe wohl eher verschreckt werden würde.';
				$bool_boss_loadable = boss_get_nav('fenris');
				if($bool_boss_loadable)
				{
					$str_out .= '`nWährend du den Käfig so anblickst, fällt dir auf, dass die beiden Rabenfedern geradezu vor Magie vibrieren und dir wird klar, dass sie irgendwie mit dem Käfig zusammenhängen müssen.`n
							Welche Mächte wirst du wohl anrufen, wenn du die Federn in den Käfig legst?';
				}
				else
				{
					$str_out .= '`nDu kannst jedoch auch bei näherer Untersuchung keinen Hinweis auf die zugehörigen Insassen finden.';
				}


			}
			output($str_out);
			break;
	}
}
?>
