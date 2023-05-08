<?php
	function webstuhl_hook_process($item_hook , &$item )
	{

		global $session,$item_hook_info;
        if(!isset($str_output))$str_output='';
		switch ($item_hook )
		{
			// Im Haus eingelagert
			case 'furniture':
			{

				switch($_GET['op'])
				{
					case 'weben': //Weben
					{
						// Hat der User Garn dabei?

						$sql = 'SELECT id,value1,gold FROM items WHERE owner='.$session['user']['acctid'].' AND deposit1="0" AND tpl_id="garn" ORDER BY RAND() LIMIT 1';
						$result = db_query($sql);
						$row = db_fetch_assoc($result);

						if(!empty($row['id']))
						{
							$itemnew['name'] = '`*Stoff';
							$itemnew['description'] = 'Ein Stück feiner Stoff, genau '.$row['value1'].' Ellen lang.';
							$itemnew['gems'] = 1;
							$itemnew['tpl_id'] = 'stoff';
							item_set('id='.$row['id'],$itemnew);

							$session['user']['turns'] -= 2 ;

							$str_output .= '`tDu setzt dich an den Webstuhl und beginnst zu weben. Wie von selbst huscht das Schiffchen durch das Fach, von einer Seite zur anderen und zurück, während du nach und nach dein Garn verarbeitest und eine Weile später hälst du schon ein kleines Stück Stoff in der Hand. Mit einem kurzen, prüfenden Blick stellst du fest, dass dieser Stoff dir bei einem Händler vermutlich `g'.$row['gold'].' Goldstücke und 1 Edelstein `teinbringen kann. Na das hat sich doch gelohnt!`n`n';
							if(item_count('owner='.$session['user']['acctid'].' AND tpl_id="garn" AND deposit1=0') && $session['user']['turns'] > 1)
							{
								$str_output .= 'Und du hast noch immer ein wenig Garn in deinem Beutel und genug Kraft, weiterzuweben, wenn du möchtest.`n`n';
								addnav('Weiterweben',$item_hook_info['link'].'&op=weben');
							}
						}
						else
						{
							$str_output .= 'Was ist denn hier passiert? Versuch es bitte nochmal.';
						}

						//addnav('Zurück',$item_hook_info['link']);
						break;
					}

					default:
					{
						$str_output.= '`tDu näherst dich dem großen Webstuhl, der in einer Ecke des Zimmers steht. Die Kettfäden sind gespannt und er scheint nur darauf zu warten, dass sich jemand daran setzt und zu weben beginnt. Wenn du Garn bei dir hast und hier ein wenig Zeit zubringst, könntest du sicher ein schönes Stück Stoff weben.`nDu siehst in deinem Beutel nach, ob du etwas Garn dabei hast.';

						// Garn dabei? Und nicht eingelagert, in irgendeinem Zimmer nützts ja nix.
						$int_garn = item_count('owner='.$session['user']['acctid'].' AND tpl_id="garn" AND deposit1=0');
						if($int_garn > 0)
						{
							$str_output .= 'Und tatsächlich: Du hast `g'.$int_garn.' Spule'.($int_garn > 0 ?'':'n').' `tGarn bei dir. ';
							// Man braucht mind. 2 Runden
							if($session['user']['turns'] > 1)
							{
								$str_output .= 'Willst du dich für `g2 Runden `tan den Webstuhl setzen setzen und ein wenig Stoff daraus weben?`n`n';
								addnav('Weben',$item_hook_info['link'].'&op=weben');
							}
							else
							{
								$str_output .= 'Doch leider bist du schon viel zu müde um nun noch zu weben.`n`n';
							}

						}
						else
						{
							$str_output .= '`n`nDoch leider findest du auch bei genauerer Suche kein Garn in deinem Beutel. Dann musst du wohl ein andermal wiederkommen. Oder du bleibst hier stehen und starrst den Webstuhl einfach so an...';
						}
						break;
					}
				}

				output(get_title('`IDer Webstuhl'));
				$str_output .= '`n`n'; // vielleicht find nur ich es hässlich, wenn der Text am Boden klebt xD
				output($str_output);
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);

				break;
			}
		}
	}
?>