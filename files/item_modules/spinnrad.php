<?php

	function spinnrad_hook_process($item_hook , &$item )
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
					case 'spinnen': //Ein bisschen rumspinnen
					{
						// Ein Wollklumpen aus dem Beutel des Users ermitteln; Garn anlegen, doppelter Goldwert

						$sql = 'SELECT id,gold,value1 FROM items WHERE owner='.$session['user']['acctid'].' AND deposit1="0" AND tpl_id="wolle" ORDER BY RAND() LIMIT 1';
						$result = db_query($sql);
						$row = db_fetch_assoc($result);

						if ((db_num_rows($result)<=0) || ($row===false)) //Arznei gegen den mysteriösen "Ups, doch keine Wolle mehr da"-Fehler
						{
							$str_output .= '`tDu willst dich gerade ans Spinnrad setzen und zu spinnen beginnen, doch du findest du die Wolle, die du verarbeiten wolltest, gar nicht mehr. Irgendwo in den Tiefen deiner Taschen muss sie doch sein... `q(Dieser Fall sollte eigentlich nie auftreten. Sollte dies nicht das erste Mal sein und es ist dir überhaupt nicht mehr möglich, Wolle zu verarbeiten, so schreibe bitte eine Anfrage.)`0`n`n';
						}
						else
						{
							$itemnew['name'] = '`eGarn';
							$itemnew['description'] = 'Eine kleine Rolle feines Garn. Gewonnen aus '.$row['value1'].' Pfund Wolle.';
							$itemnew['gold'] = $row['gold']*2;
							$itemnew['tpl_id'] = 'garn';
							item_set('id='.$row['id'],$itemnew);

							$session['user']['turns'] -= 2 ;

							$str_output .= '`tDu setzt dich ans Spinnrad und beginnst zu spinnen. Wie von selbst dreht sich das Rad, während du nach und nach deine Wolle verarbeitest und eine Weile später hälst du schon eine kleine Spule Garn in der Hand. Mit einem kurzen, prüfenden Blick stellst du fest, dass dieses Garn dir bei einem Händler vermutlich `g'.$itemnew['gold'].' Goldstücke `teinbringen kann. Na das hat sich doch gelohnt!`n`n';
							if(item_count('owner='.$session['user']['acctid'].' AND tpl_id="wolle" AND deposit1=0') && $session['user']['turns'] > 1)
							{
								$str_output .= 'Und du hast noch immer ein wenig Wolle in deinem Beutel und genug Kraft, weiterzuspinnen, wenn du möchtest.`n`n';
								addnav('Weiterspinnen',$item_hook_info['link'].'&op=spinnen');
							}
						}
						break;
					}

					default:
					{
						$str_output.= '`tDu näherst dich dem großen, hölzernen Spinnrad, das in einer Ecke des Zimmers steht. Wenn du Wolle hast und hier ein wenig Zeit zubringst, könntest du sicher einiges an hübschem Garn spinnen.`nDu siehst in deinem Beutel nach, ob du etwas Wolle dabei hast.';

						// Wolle dabei? Und nicht eingelagert, in irgendeinem Zimmer nützts ja nix.
						$int_wolle = item_count('owner='.$session['user']['acctid'].' AND tpl_id="wolle" AND deposit1=0');
						if($int_wolle > 0)
						{
							$str_output .= 'Und tatsächlich: Du hast `g'.$int_wolle.' Portion'.($int_wolle > 0 ?'':'en').' Wolle `tbei dir. ';
							// Man braucht mind. 2 Runden
							if($session['user']['turns'] > 1)
							{
								$str_output .= 'Willst du dich für `g2 Runden `tan das Spinnrad setzen und ein wenig Garn daraus spinnen?`n`n';
								addnav('Losspinnen',$item_hook_info['link'].'&op=spinnen');
							}
							else
							{
								$str_output .= 'Doch leider bist du schon viel zu müde um nun noch zu spinnen.`n`n';
							}

						}
						else
						{
							$str_output .= '`n`nDoch leider findest du auch bei genauerer Suche keine Wolle in deinem Beutel. Dann musst du wohl ein andermal wiederkommen. Oder du bleibst hier stehen und starrst das Spinnrad einfach so an...';
						}
						break;
					}
				}


				output(get_title('`IDas Spinnrad'));
				$str_output .= '`n`n';
				output($str_output);
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);

				break;
			}
		}
	}

?>