<?php
/*
Item Waffe:
value1 = Anzahl der Anwendungen aktuell
value2 = Anzahl der Anwendungen neu (5)
hvalue = Treffsicherheit in % (40) wenn hvalue!=0 wird Item nicht gelöscht wenn value1 0 wird
hvalue2 = Waffenstärke (2)
content = serialisiertes Array Anzahl der Trophäen
hookstop setzen und item selbst aktualisieren

Item Trophäe:
value1 = 0 (original DK)
value2 = 9-10 (original Typ)
hvalue = 0 (original acctid)
hvalue2 = 0 (original Typ bei altem Mann)
*/
function huntweapon_hook_process ( $item_hook , &$item ) 
{
	global $session,$item_hook_info;
	
	switch ( $item_hook )
	{
		case 'newday':
		{ //nach dem DK Anwendungen auffüllen
			if($session['user']['age']==1)
			{
				$item['value1']=$item['value2'];
				item_set(' id='.$item['id'], $item);
			}
			break;
		}

		case 'battle':
		{
			global $badguy,$session,$zauber;
			$itemcontent=utf8_unserialize($item['content']);
			
			$dice=e_rand(0,100);
			if($dice<$item['hvalue']) //Gegner wurde getroffen
			{
				$damage=round(e_rand(1,$session['user']['attack']*0.5)+e_rand(1,$session['user']['level']*$item['hvalue2']));
				$badguy['creaturehealth']-=$damage;
				
				output('`tDu greifst zu '.$item['name'].'`t, zielst auf `&'.$badguy['creaturename'].' `t und schießt.
				`n'.$item['name'].'`t trifft '.$badguy['creaturename'].'`t mit `4'.$damage.'`t Schaden.');
				if($badguy['creaturehealth']<1)
				{ //Gegner wurde mit der Jagdwaffe getötet, es gibt eine Trophäe falls es der richtige war.
					output('`n`&Das war ein meisterhafter Blattschuss! '.$badguy['creaturename'].'`& ist tot! `tDu überlegst, was du dir als Trophäe mitnehmen könntest.`n');
					switch($badguy['creaturename'])
					{
						case 'Kaninchen': //Lvl1
						case 'Feldhase': //Lvl1
						case 'Kleiner Hase': //Lvl4
						{
							if($itemcontent['lasthit']['canin']!=$session['user']['dragonkills'])
							{
								output('Du entscheidest dich für die Pfote.');
								$trph=array('tpl_name'=>'Kaninchenpfote',
									'tpl_description'=>'Die Pfote eines Kaninchens. Du zeigst sie gerne herum wenn du von deinen jägerischen Talenten prahlst.',
									'tpl_gold'=>2500
									);
								$itemcontent['lasthit']['canin']=$session['user']['dragonkills'];
								$itemcontent['trphcount']['canin']++;
							}
							else
							{
								output('Zu schade, dass der Jäger-Kodex nur eine Kaninchentrophäe pro Drachenzyklus erlaubt. Also begnügst du dich mit dem Nutzmaterial des Tieres.');
							}
							break;
						}

						case 'Fuchs': //Lvl3
						{
							if($itemcontent['lasthit']['fox']!=$session['user']['dragonkills'])
							{
								output('Von einem Fuchs nimmt man natürlich den Schwanz als Trophäe.');
								$trph=array('tpl_name'=>'Fuchsschwanz',
									'tpl_description'=>'Ein wuscheliger Fuchsschwanz. Du zeigst ihn gerne herum wenn du von deinen jägerischen Talenten prahlst.',
									'tpl_gems'=>1
									);
								$itemcontent['lasthit']['fox']=$session['user']['dragonkills'];
								$itemcontent['trphcount']['fox']++;
							}
							else
							{
								output('Zu schade, dass der Jäger-Kodex nur eine Fuchstrophäe pro Drachenzyklus erlaubt. Also begnügst du dich mit dem Nutzmaterial des Tieres.');
							}
							break;
						}

						case 'Dachs': //Lvl5
						{
							if($itemcontent['lasthit']['badger']!=$session['user']['dragonkills'])
							{
								output('Der Pelz ist doch wunderbar für eine Mütze geeignet.');
								$trph=array('tpl_name'=>'Dachspelzmütze',
									'tpl_description'=>'Eine edle Mütze aus dem Pelz eines Dachses. Du zeigst sie gerne herum wenn du von deinen jägerischen Talenten prahlst.',
									'tpl_gems'=>2
									);
								$itemcontent['lasthit']['badger']=$session['user']['dragonkills'];
								$itemcontent['trphcount']['badger']++;
							}
							else
							{
								output('Zu schade, dass der Jäger-Kodex nur eine Dachstrophäe pro Drachenzyklus erlaubt. Also begnügst du dich mit dem Nutzmaterial des Tieres.');
							}
							break;
						}

						case 'Keiler': //Lvl7
						case 'Grunzendes Schwein': //Lvl1
						{
							if($itemcontent['lasthit']['pig']!=$session['user']['dragonkills'])
							{
								output('Der Kopf. Ja, der Kopf muss es sein!');
								$trph=array('tpl_name'=>'Keilerkopf',
									'tpl_description'=>'Ein gefährlich wirkender Wildschweinkopf. Der macht sich gut an der Wand deines Jagdzimmers wenn du von deinen jägerischen Talenten prahlst.',
									'tpl_gems'=>3
									);
								$itemcontent['lasthit']['pig']=$session['user']['dragonkills'];
								$itemcontent['trphcount']['pig']++;
							}
							else
							{
								output('Zu schade, dass der Jäger-Kodex nur eine Wildschweintrophäe pro Drachenzyklus erlaubt. Also begnügst du dich mit dem Nutzmaterial des Tieres.');
							}
							break;
						}

						case 'Reh': //Lvl9
						{
							if($itemcontent['lasthit']['deer']!=$session['user']['dragonkills'])
							{
								output('Du entscheidest dich für das Fell.');
								$trph=array('tpl_name'=>'Rehpelzumhang',
									'tpl_description'=>'Ein besonders schönes Stück Rehfell. Es hat die richtige Größe um es auf der Schulter zu tragen wenn du von deinen jägerischen Talenten prahlst.',
									'tpl_gems'=>4
									);
								$itemcontent['lasthit']['deer']=$session['user']['dragonkills'];
								$itemcontent['trphcount']['deer']++;
							}
							else
							{
								output('Zu schade, dass der Jäger-Kodex nur eine Rehtrophäe pro Drachenzyklus erlaubt. Also begnügst du dich mit dem Nutzmaterial des Tieres.');
							}
							break;
						}

						case 'Hirsch': //Lvl11
						{
							if($itemcontent['lasthit']['hart']!=$session['user']['dragonkills'])
							{
								output('Das Geweih sieht prächtig aus, das musst du haben.');
								$trph=array('tpl_name'=>'Hirschgeweih',
									'tpl_description'=>'Dieses Prachtexemplar von einem Zwölfender ist ein Muss für deine Ausstellung im Jägerzimmer.',
									'tpl_gems'=>5
									);
								$itemcontent['lasthit']['hart']=$session['user']['dragonkills'];
								$itemcontent['trphcount']['hart']++;
							}
							else
							{
								output('Zu schade, dass der Jäger-Kodex nur eine Hirschtrophäe pro Drachenzyklus erlaubt. Also begnügst du dich mit dem Nutzmaterial des Tieres.');
							}
							break;
						}

						case '`&Weißer Wolf`0':  //Lvl2
						case 'Streunender Wolf': //Lvl13
						{
							if($itemcontent['lasthit']['wulf']!=$session['user']['dragonkills'])
							{
								output('Du entscheidest dich für das Fell.');
								$trph=array('tpl_name'=>'Wolfsfell',
									'tpl_description'=>'Dieses herrlich weiße Wolfsfell ist wie geschaffen, um die Wand deines Jägerzimmers zu zieren.',
									'tpl_gems'=>6
									);
								$itemcontent['lasthit']['wulf']=$session['user']['dragonkills'];
								$itemcontent['trphcount']['wulf']++;
							}
							else
							{
								output('Zu schade, dass der Jäger-Kodex nur eine Wolfstrophäe pro Drachenzyklus erlaubt. Also begnügst du dich mit dem Nutzmaterial des Tieres.');
							}
							break;
						}

						case '`TBraunbär`0': //Lvl15
						case 'Grizzlybär':   //Lvl12
						case 'Kleiner Bär':  //Lvl2
						{
							if($itemcontent['lasthit']['bear']!=$session['user']['dragonkills'])
							{
								output('Du nimmst den Bären ganz mit und machst einen Teppich daraus.');
								$trph=array('tpl_name'=>'Bärenfellteppich',
									'tpl_description'=>'Dieses Bärenfell ist die Krönung der Ausstellung im Jägerzimmer. Vorsicht! Nicht über den Kopf stolpern!',
									'tpl_gems'=>7
									);
								$itemcontent['lasthit']['bear']=$session['user']['dragonkills'];
								$itemcontent['trphcount']['bear']++;
							}
							else
							{
								output('Zu schade, dass der Jäger-Kodex nur eine Bärentrophäe pro Drachenzyklus erlaubt. Also begnügst du dich mit dem Nutzmaterial des Tieres.');
							}
							break;
						}

						case '`QDer Große Hirsch`0':
						{
							if(!$itemcontent['lasthit']['god'])
							{
								output('Völlig perplex starrst du auf das, was du gerade erlegt hast.');
								$trph=array('tpl_name'=>'Mystisches Geweih',
									'tpl_description'=>'Diesem mystischen Geweih ist in deinem Jägerzimmer ein Schrein geweiht.',
									'tpl_gems'=>30,
									'tpl_value2'=>10
									);
								$itemcontent['lasthit']['god']=$session['user']['dragonkills'];
								$itemcontent['trphcount']['god']++;
							}
							else
							{
								output('Wie hast du das geschafft, hier zu landen? Das mystische Geweih darfst du dir nur ein einziges mal holen.');
							}
							break;
						}

						default:
						{
							output('Nach eingehender Betrachtung stellst du fest: Eine Trophäe von '.$badguy['creaturename'].'`t? Nichtmal ein Erztroll würde sowas haben wollen.');
							break;
						}
					}

					if($trph['tpl_name'])
					{
						$trph['tpl_value2']=$trph['tpl_value2']?$trph['tpl_value2']:9;
						item_add($session['user']['acctid'],'trph',$trph);
						$rowe=user_get_aei('hunterlevel');

						//Jägerlevel updaten
						if($rowe['hunterlevel']==0)
						{
							$changes['hunterlevel']=1;
							output('`nDu darfst dich fortan `b`&Jagdsprössling`t`b nennen.');
						}

						elseif($rowe['hunterlevel']==1 && array_sum($itemcontent['trphcount'])>=3)
						{
							$changes['hunterlevel']=2;
							output('`nDu hast 3 Trophäen gesammelt und darfst dich fortan `b`&Jungjäger`t`b nennen.');
						}

						elseif($rowe['hunterlevel']==2 && array_sum($itemcontent['trphcount'])>=5)
						{
							$changes['hunterlevel']=3;
							output('`nDu hast 5 Trophäen gesammelt und darfst dich fortan `b`&Jäger`t`b nennen.');
						}

						elseif($rowe['hunterlevel']==3 && array_sum($itemcontent['trphcount'])>=8)
						{
							$changes['hunterlevel']=4;
							output('`nDu hast 8 Trophäen gesammelt und darfst dich fortan `b`&Jägermeister`t`b nennen.');
						}

						elseif($rowe['hunterlevel']==4
						&& $itemcontent['trphcount']['canin']>0
						&& $itemcontent['trphcount']['fox']>0
						&& $itemcontent['trphcount']['badger']>0
						&& $itemcontent['trphcount']['pig']>0
						&& $itemcontent['trphcount']['deer']>0
						&& $itemcontent['trphcount']['hart']>0
						&& $itemcontent['trphcount']['wulf']>0
						&& $itemcontent['trphcount']['bear']>0)
						{
							$changes['hunterlevel']=5;
							output('`nDu hast 8 unterschiedliche Trophäen gesammelt und darfst dich fortan `b`&Oberförster`t`b nennen.');
						}

						elseif($rowe['hunterlevel']==5
						&& $itemcontent['trphcount']['canin']>2
						&& $itemcontent['trphcount']['fox']>2
						&& $itemcontent['trphcount']['badger']>2
						&& $itemcontent['trphcount']['pig']>2
						&& $itemcontent['trphcount']['deer']>2
						&& $itemcontent['trphcount']['hart']>2
						&& $itemcontent['trphcount']['wulf']>2
						&& $itemcontent['trphcount']['bear']>2)
						{
							$changes['hunterlevel']=6;
							output('`nDu hast mindestens 3 Exemplare von 8 unterschiedlichen Trophäen gesammelt und darfst dich fortan `b`&Hüter des Waldes`t`b nennen.');
						}

						elseif($rowe['hunterlevel']==6 && $trph['tpl_value2']==10)
						{
							$changes['hunterlevel']=7;
							output('`@Gratulation! `2Du hast es geschafft, den tödlichen Schlag mit deiner Jagdwaffe auszuführen. Somit gilt die Prüfung als bestanden.
							`0`nDu darfst dich fortan `b`&Beherrscher des Waldes`t`b nennen.');
						}

						if($changes)
						{
							user_set_aei($changes);
						}
					}
				}
			}
			else //daneben geschossen
			{
				output('`tDu greifst zu '.$item['name'].'`t, zielst und verfehlst `&'.$badguy['creaturename'].' `t'.($dice-$item['hvalue']>7?'meilenweit.':'um Haaresbreite.'));
			
			}
			$item_hook_info['hookstop']=true;
			$item['content']=utf8_serialize($itemcontent);
			$item['value1']--;
			item_set(' id='.$item['id'], $item);
			
			break;
		}
	}
}

?>