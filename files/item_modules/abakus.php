<?php

function abakus_hook_process ( $item_hook , &$item ) {

	global $session,$item_hook_info;

	$str_output = '';
	switch ( $item_hook ) {

		case 'furniture':

			if ($_GET['op']==1)
			{

				$times_used = getsetting('abakus_times_used',0);
				$times_used++;
				if (($times_used%10000)!=0)
				{
					$str_output .= '`7Du stellst dich vor das seltsame Gerät und beginnst ein wenig zu rechnen, in der Hoffnung möglichst genau den Wert `@5`7 zu erhalten.`n`n';
					$result = 0;
					for ($i=1; $i<=10000; $i++)
					{
						$result+=e_rand(0,100);
					}
					$result *= 0.00001;

					$str_output .= '`7Du erhältst das Ergebnis `@'.$result.'`7 !`n`n';

					if (($result>=4.999) && ($result<=5.001))
					{
						$str_output .= '`7Erfreut über ein so gutes Ergebnis erhältst du einen zusätzlichen Waldkampf!`n';
						$session['user']['turns']++;
					}
					savesetting('abakus_times_used',$times_used);
					addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
				}
				else
				{
					$str_output .= '`^Quäl den Abakus!`n`nJa, du hast es geschaft und bist in die tiefsten Geheimnisse der Mathematik eingetaucht.`n`nLeider weißt du damit nichts besseres anzufangen als dich für 15 weitere Runden zu motivieren...`n`n';
					addnews('`%'.$session['user']['name'].'`# hat sich heute in Höchstform gerechnet!');
					$session['user']['turns']+=15;
					savesetting('abakus_times_used',$times_used);
					addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
				}
			}
			else if ($_GET['op']==2)
			{
				$str_output .= '`@Fleissig, fleissig!`n`nDu schrubbst und putzt das Haus eine Runde lang auf Hochglanz.`n`n';
				$session['user']['turns']--;
				if ($session['user']['turns']<0)
				{
					$session['user']['turns']=0;
				}
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			}
			else if ($_GET['op']==3)
			{
				$str_output .= '`5Schlaf gut!`n`nSüße Träume!`n`n';
				addnav('Ins Land der Träume','inside_houses.php?act=logout');
			}
			else
			{

				$str_output .= '`7Lust auf ein paar Nümmerchen oder willst du lieber was anderes tun?`n`n';
				$str_one = create_lnk('<img src="./images/abakus.jpg" title="Abakus" border="0">',$item_hook_info['link'].'&op=1');
				$str_two = create_lnk('<img src="./images/abakus1.jpg" title="Hausarbeit" border="0">',$item_hook_info['link'].'&op=2');
				$str_three = create_lnk('<img src="./images/abakus0.jpg" title="Nickerchen" border="0">',$item_hook_info['link'].'&op=3');

				$order = e_rand(1,6);

				switch ($order)
				{
					case 1:
						$str_output .= $str_one.$str_two.$str_three.'`n`n';
						break;
					case 2:
						$str_output .= $str_one.$str_three.$str_two.'`n`n';
						break;
					case 3:
						$str_output .= $str_two.$str_one.$str_three.'`n`n';
						break;
					case 4:
						$str_output .= $str_two.$str_three.$str_one.'`n`n';
						break;
					case 5:
						$str_output .= $str_three.$str_one.$str_two.'`n`n';
						break;
					case 6:
						$str_output .= $str_three.$str_two.$str_one.'`n`n';
						break;
				}
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			}
			output($str_output,true);
			break;
	}
}
?>
