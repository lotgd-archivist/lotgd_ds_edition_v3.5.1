<?php

function futtermat_hook_process ( $item_hook , &$item ) {

	global $session,$item_hook_info;

	switch ( $item_hook )
	{

		case 'furniture':

			if ($_GET['act'] == '')
			{
				output('`&Du stehst vor der gewaltigen Schrankwand, die eine Futtermaschine darstellen soll.`n`nDas Prinzip ist ganz einfach:`nDu legst einen Edelstein in die große Öffnung, drehst an den zahllosen kleinen und großen Rädchen und legst schließlich einen Hebel um, hoffend, das zu erhalten was du brauchst.`n`n
						Alternativ kannst du die Maschine auch mit einer Erdnuss füttern - und sie so dazu bringen, bestimmte Dinge mit höherer Wahrscheinlichkeit herauszugeben.');
				if ($item['value2']==0)
				{
					output('`4Die Futtermaschine ist defekt und bedarf dringend der Reparatur!');
					addnav('Reparieren (15000 Gold)',$item_hook_info['link'].'&act=repair');
					addnav('~~~');
				}
				else
				{
					output('`&Ein näherer Blick auf die Apparatur zeigt dir, ');
					if ($item['value1']>100)
					{
						output('`@dass die Futtermaschine in hervorragendem Zustand ist und keinerlei Grund zur Beanstandung bietet.`n');
					}
					elseif ($item['value1']>90)
					{
						output('`2dass die Maschine in einem tadellosen Zustand ist.');
					}
					elseif ($item['value1']>70)
					{
						output('`^dass sich das Gerät in einem allgemein guten Zustand befindet, aber Gebrauchsspuren aufweist.');
					}
					elseif ($item['value1']>40)
					{
						output('`Qdass die Maschine durch den ständigen Gebrauch schon deutlich abgenutzt erscheint.');
					}
					else
					{
						output('`4dass dieses Gerät kurz vor dem Zusammenbruch steht.');
					}
					addnav('Benutzen (1 Edelstein)',$item_hook_info['link'].'&act=use&use=gem');
					addnav('Benutzen (1 Erdnuss)',$item_hook_info['link'].'&act=use&use=nut');
					addnav('~~~');
					addnav('Warten (2500 Gold)',$item_hook_info['link'].'&act=maintain');
					addnav('~~~');
				}
			}
			elseif ($_GET['act'] == 'repair')
			{
				if ($session['user']['gold']<15000)
				{
					output('`4Die fehlt das nötige Gold für Werkzeuge und Ersatzteile!`&`n');
				}
				else
				{
					output('`&Du schraubst eine Weile an dem Gerät herum und tauschst einige der empfindlichen Bauteile aus.`nEs ist zwar nicht perfekt, aber es sollte fürs erste eine Weile halten.`n');
					$session['user']['gold']-=15000;
					$item['value2']=1;
					$item['value1']=75;
					item_set('id='.$item['id'],$item);
				}
				addnav('Zur Maschine',$item_hook_info['link']);
				addnav('~~~');
			}
			elseif ($_GET['act'] == 'maintain')
			{
				if ($session['user']['gold']<2500)
				{
					output('`4Du hast nicht genügend Gold um die benötigten Materialien für eine Wartung zu bezahlen!`&`n');
				}
				else
				{
					output('`&Du werkelst etwas an dem sensiblen Gerät herum und schaffst es dessen Gesamtzustand ein wenig zu verbessern.`n');
					$session['user']['gold']-=2500;
					$item['value1']+=10;
					if ($item['value1']>125)
					{
						$item['value1']=125;
					}
					item_set('id='.$item['id'],$item);
				}
				addnav('Zur Maschine',$item_hook_info['link']);
				addnav('~~~');
			}
			elseif ($_GET['act'] == 'use')
			{
				addnav('Zur Maschine',$item_hook_info['link']);
				addnav('~~~');
				
				if($_GET['use'] == 'gem')
				{
					if ($session['user']['gems']<1)
					{
						output('`&Der Edelstein ist für die Prozedur der Futterherstellung enorm wichtig.`nOhne ihn kannst du die Maschine nicht benutzen!`n');
						page_footer();
					}
					else
					{
						output('`&Du fütterst die Maschine mit dem kostbaren Edelstein und fragst dich während du die Schalter und Rädchen bedienst, ob dieses gierige Gerät wohl von Zwergen gebaut wurde.`n`nNachdem du den finalen Schalter betätigt hast ');
						$session['user']['gems']--;	
					}
				}
				else 
				{
					$arr_item = item_get('owner='.$session['user']['acctid'].' AND tpl_id="erdnuss"',false,'id');
					if (false === $arr_item)
					{
						output('`&Ohne Erdnuss kommst du so nicht weiter - aber vielleicht hast du dafür einen Edelstein?`n');
						page_footer();
					}
					else
					{
						output('`&Du fütterst die Maschine mit der Erdnuss und fragst dich, während du die Schalter und Rädchen bedienst, ob dieses gierige Gerät wohl von Terrorhörnchen gebaut wurde.`n`nNachdem du den finalen Schalter betätigt hast ');
						item_delete('id='.$arr_item['id'],1);
					}
				}
			
				$gain=e_rand(1,14);
				
				// Wenn Erdnuss eingesetzt wurde: Garantiert was anderes zurückgeben + höhere Wahrscheinlichkeit ; )
				if($_GET['use'] == 'nut')
				{
					$gain = max($gain,2);
					$gain = min($gain,12);
				}
				
				switch ($gain)
				{
					case 1:
						// Erdnüsse
						output('brummt die Futtermaschine in unregelmäßigen Abständen und lässt dann ein paar Erdnüsse in deine Hände fallen.');
						item_add($session['user']['acctid'],'erdnuss');
						break;
					case 2:
					case 3:
						// Macadamia
						output('röhrt das Gerät kurz und lässt dann eine kleine Portion Macadamia-Nüsse im Auswurffenster erscheinen.');
						item_add($session['user']['acctid'],'macanut');
						break;
					case 4:
					case 5:
						// Acolytenfutter
						output('rattert die Maschine eine Weile und spuckt dann eine Ladung Acolytenfutter aus.');
						item_add($session['user']['acctid'],'acofutter');
						break;
					// Nix
					default:
						output('musst du feststellen, dass sich gar nichts tut. Die verrückte Maschine muss wohl eine Fehlfunktion haben!`nDa hilft auch ein kräftiger Tritt nichts!');
						break;
				}
				$damage=e_rand(1,10);
				$ruin=e_rand(1,100);
				if ($ruin>$item['value1'])
				{
					if(e_rand(1,3)==1)
					{
						output('`n`n`4Zu allem Übel gibt die Maschine nach dieser Aktion den Geist auf!`nOh je, das wird teuer!');
						$item['value2']=0;
						$sql='INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),\''.$item_hook_info['section'].'\','.$session['user']['acctid'].',\': `4hat den Futtermittel-Automaten auf dem Gewissen!\')';
						db_query($sql);
					}
					else
					{
						$damage*=3;
					}
				}
				$item['value1']-=$damage;
				item_set('id='.$item['id'],$item);
		
			}
			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			break;
	}
}
?>