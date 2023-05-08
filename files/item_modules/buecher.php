<?php

function buecher_hook_process ( $item_hook , &$item ) {
	
	global $session,$item_hook_info;
	
	switch ( $item_hook ) {
		
		case 'furniture':
		
			output("`2Du greifst in das Bücherregal und nimmst wahllos eines der Bücher heraus.`n`n ");
			if ($session['user']['turns']<=0)
			{
				output("`2Es ist das 3000-Seitige Werk '`#Durch Askese den Zugang zur Transzendenz erlangen'`2.
				`nAuf sowas hast du heute nun wirklich keine Lust mehr! Du stellst das Buch zurück.`n`n ");
			} 
			else
			{
				switch(e_rand(1,11))
				{
					case 1:
						output("`2Du liest das Buch '`#Was Alkohol deinem Körper antut`2'.`n ");
						if ($session['user']['drunkenness']>0)
						{
							output("`2Geschockt durch die offene Direktheit dieses Buches wirst du schlagartig wieder `@nüchtern`2!`n ");
						}
						else
						{
							output("`2Du denkst : `@Wie gut, dass ich nicht trinke!`2
							`nIrgendwie könntest du dennoch jetzt ein gutes Bierchen vertragen - auf den Schrecken.`n`n ");
						}
						$session['user']['drunkenness']=0;
					break;
					
					case 2:
						output("`2Du liest das Buch '`#Das kleine Handbuch für die feine Gesellschaft`2'.
						`nDir eröffnen sich völlig neue Perspektiven im gesellschaftlichen Umgang.
						`nDu erhältst `@einen Charmepunkt`2!
						`nBeim Lesen des Büchleins verlierst du jedoch einen Waldkampf.");
						$session['user']['turns']-=1;
						$session['user']['charm']+=1;
					break;
					
					case 3:
						output("`2Du liest das Buch '`#Romeo und Julia`2'.
						`nVöllig ergriffen von der Tragik dieses Werkes wirst du den Rest des Tagen schniefend mit einem Taschentuch verbringen.
						`nDu verlierst alle deine verbleibenden Waldkämpfe!`n ");
						$session['user']['turns']=0;
						addnews("`@".$session['user']['name']."`@ wurde gesehen wie ".($session['user']['sex']?"sie":"er")." mit einem Taschentuch umherlief und heulend von Liebe und Leid erzählte.`n");
					break;
					
					case 4:
						output("`2Du liest das Buch '`#König Arthus`2'.
						`nVöllig mitgerissen von der Geschichte steigt deine Kampfeslust.
						`nDu erhältst `@3 Waldkämpfe`2!`n ");
						$session['user']['turns']+=3;
						addnews("`@".$session['user']['name']."`@ rannte mit einem lauten Kampfschrei von ".($session['user']['sex']?"ihrem":"seinem")." Haus direkt in den Wald.`n");
					break;
					
					case 5:
						output("`2Du liest das Buch '`#Harry Potter und der offene Klodeckel`2'.
						`nNachdem du angefangen hast zu lesen merkst du, wie du langsam `#verdummst`2, jedoch kannst du dich irgendwie nicht davon losreissen.
						`nDu verlierst `@2%`2 deiner Erfahrung und einen Waldkampf!`n ");
						$session['user']['turns']-=1;
						$session['user']['experience']=ceil($session['user']['experience']*0.98);
						addnews("`@".$session['user']['name']."`@ wurde beobachtet wie ".($session['user']['sex']?"sie":"er")." mit einem peinlichen Cape umherlief und laut `#'Bei Dumbledore!'`@ rief.`n");
						break;
					
					case 6:
						output("`2Du liest das Buch '`#Die Weisheiten des Konfusius`2'.
						`nDieses Buch ist wirklich sehr lehrreich und du gelangst zu einigen Erkenntnissen.
						`nDeine Erfahrung steigt um `@5%`2, jedoch verlierst du während des Lesens einen Waldkampf!`n ");
						$session['user']['turns']-=1;
						$session['user']['experience']=ceil($session['user']['experience']*1.05);
					break;
					
					case 7:
						output("`2Du liest das Buch '`#Die Räuber`2'.
						`nDie rauhe und rüde Art der wilden Räuber fasziniert dich und du beschließt, genauso rauh und rüde zu werden.
						`nDu verlierst `@einen Charmepunkt`2 und einen Waldkampf.`n ");
						$session['user']['turns']-=1;
						$session['user']['charm']=max(0,$session['user']['charm']-1);
						addnews("`@".$session['user']['name']."`@ wurde beobachtet wie ".($session['user']['sex']?"sie":"er")." laut rülpsend `#'Harr harr harr!'`@ rief.`n");
					break;
					
					case 8:
						output("`2Du liest das Buch '`#Lady Chatterley's Geliebter'`2'.
						`nZiemlich schnell stellst du es mit hochrotem Kopf zurück ins Regal.
						`nVielleicht solltest du doch etwas Anderes lesen.`n ");
					break;
					
					case 9:
						output("`2Du liest das Buch '`#Romeo und Julia`2'.
						`nVöllig ergriffen von der Tragik dieses Werkes wirst du den Rest des Tagen schniefend mit einem Taschentuch verbringen.
						`nDu verlierst alle deine verbleibenden Waldkämpfe!`n ");
						$session['user']['turns']=0;
						addnews("`@".$session['user']['name']."`@ wurde gesehen wie ".($session['user']['sex']?"sie":"er")." mit einem Taschentuch umherlief und heulend von Liebe und Leid erzählte.`n");
					break;
					
					case 10:
						output("`2Du liest das Buch '`#Der Gladiator'`2'.
						`nAngespornt durch die packende Geschichte erhältst du `@einen Spielerkampf`2 mehr.
						`nAuf in die Felder!.`n ");
						$session['user']['playerfights']++;
					break;
					
					case 11:
						output("`2Du liest das Buch '`#Angewandte Heilkunde'`2'.`n ");
						if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
						{
							output("`2Du befolgst die Anweisungen des Buches und verarztest deine Wunden.
							`n`@Dadurch heilst du komplett!`2`n ");
							$session['user']['hitpoints']=$session['user']['maxhitpoints'];
						}
						else
						{
							output("`2Doch da du nicht verwundet bist hilft dir das im Moment nicht weiter.`n ");
						}
						output("`2Du verlierst `@einen Waldkampf`2.`n ");
						$session['user']['turns']-=1;
					break;
				}
			}
			
			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			
			break;
			
	}
	
}

?>