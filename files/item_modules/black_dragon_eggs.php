<?php

function black_dragon_eggs_hook_process($item_hook , &$item )
{

	global $session,$item_hook_info;
    if(!isset($str_output))$str_output='';
	switch ($item_hook )
	{

		case 'use':
			{
				switch($_GET['b_dg_act'])
				{
					case '':
						{
							$str_output .= get_title('Der beschworene Drache').'
			        		`2Du fummelst kurz an der Sphäre herum und wunderst dich noch, wie du diese zu benutzen hast,
			        		als diese auch schon blitzschnell in allen möglichen Farben zu leuchten beginnt.
			        		Das grelle Licht zwingt dich dazu die Augen zusammen zu kneifen. Was geschieht hier nur?
			        		Als du das Gefühl hast, dass sich das Licht zurückgezogen hat, öffnest du deine Lider
			        		langsam wieder und traust deinen Augen nicht. Vor dir steht in leibhaftiger Größe der schwarze Drache!
			        		`@"Sterblicher, da du mich beschworen hast gewähre ich dir einen Wunsch!"
			        		`2 Völlig überrumpelt, stotterst du einige wirre Worte vor dich hin,
			        		ehe du langsam wieder einen klaren Kopf bekommst.
			        		`G"W... Wunsch?"`2 fragst du zweifelnd, jedoch keine Antwort erhaltend. Du überlegst dir zittrig,
			        		was du dir wünschen könntest...`n
        		'
							.create_lnk('100 permanente Lebenspunkte',$item_hook_info['link'].'&op=use&b_dg_act=wish&b_dg_subact=LP',true,true,'',false,false,CREATE_LINK_LEFT_NAV_HOTKEY).'`n'
							.create_lnk('200 Edelsteine',$item_hook_info['link'].'&op=use&b_dg_act=wish&b_dg_subact=ES',true,true,'',false,false,CREATE_LINK_LEFT_NAV_HOTKEY).'`n'
							.create_lnk('150% mehr Erfahrung',$item_hook_info['link'].'&op=use&b_dg_act=wish&b_dg_subact=EXP',true,true,'',false,false,CREATE_LINK_LEFT_NAV_HOTKEY);


							break;
						}
					case 'wish':
						{
							if($_GET['b_dg_subact'] == 'LP')
							{
								$session['user']['maxhitpoints']+=100;
								debuglog('Benutzte schwarze Sphäre: +100HP');
							}
							elseif ($_GET['b_dg_subact'] == 'ES')
							{
								$session['user']['gems']+=200;
								debuglog('Benutzte schwarze Sphäre: +200ES');
							}
							elseif ($_GET['b_dg_subact'] == 'EXP')
							{
								$session['user']['experience']*=2.5;
								debuglog('Benutzte schwarze Sphäre: EXP*2.5');
							}
							$str_output .= get_title('Dein Wunsch sei erfüllt!').'
			            	`2Zweifelnd äußerst du deinen Wunsch, den Drachen noch immer ungläubig anstarrend. `@"So sei es!"`2 spricht er schließlich, ehe er in selbigem Licht wieder verschwindet, in welchem er kurz zuvor aufgetaucht war. Erleichtert atmest du aus...
			            	';
							item_delete(' id='.$item['id']);
							addnav('Zurück',$item_hook_info['ret']);
							break;
						}
				}
				output($str_output);
				break;
			}
	}
}
?>