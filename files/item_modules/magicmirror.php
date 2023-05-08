<?php
//Item-Modul Schneewittchen-Spiegel für Haus- und Itemsystem der DragonslayerEdition 3.x
//Zeigt den Charme relativ zum Schönsten der gleichen Rasse an
// by Salator 13.06.09

function img_string( $src, $alt='', $x=200, $y=200 ){

	if( $src != '' ){
		if( ( $src ) ){
			$ret = '<img src="'.$src.'" ';
			$ret .= 'alt="'.utf8_htmlspecialchars(strip_appoencode($alt,3)).'">';
		}
		else{
			$ret = '';
		}
	}
	else{
		$ret = '`n`n(kein Bild)&nbsp;&nbsp;&nbsp;';
	}
	return $ret;
}

function magicmirror_hook_process($item_hook , &$item )
{

	global $session,$item_hook_info;

	switch ($item_hook )
	{

		case 'furniture':

			$str_out='`c`b`#Der magische Spiegel`0`b`c`n';
			if($_GET['act']=='ask')
			{
				if($session['daily']['mirrorcleaned']==1 || item_delete('tpl_id="trinkwasser" AND owner='.$session['user']['acctid']))
				{
					$c=CRPChat::make_color($session['user']['prefs']['commenttalkcolor'],'3');
					if(!$session['daily']['mirrorcleaned'])
					{
						$str_out.='Was tut man nicht alles, um etwas über die eigene Schönheit zu erfahren? Also verwendest du eine Portion Wasser, um den Spiegel zu putzen. Nach einer Weile ist der Spiegel blitzblank.`n';
						$session['daily']['mirrorcleaned']=1;
					}
					
					//gleiche Rasse bzw Sonderfälle festlegen
					if($session['user']['title']=='`2Frosch`0')
					{
						$racename='Frosch';
						$str_where_race='AND title ="'.$session['user']['title'].'"';
						$picture='./images/kermit.jpg';
					}
					elseif($session['user']['title']=='`2Kröte`0')
					{
						$racename='Kröte';
						$str_where_race='AND title ="'.$session['user']['title'].'"';
						$picture='./images/toad.jpg';
						$racesex=1; //Grammatik: DIE Kröte
					}
					elseif($session['user']['title']=='Flauschihase')
					{
						$racename='Flauschihase';
						$str_where_race='AND title ="'.$session['user']['title'].'"';
						$picture='./images/fluffy.jpg';
					}
					else
					{
						$racename=db_result(db_query('SELECT name FROM races WHERE id="'.$session['user']['race'].'"'),0);
						$str_where_race='AND race="'.$session['user']['race'].'"';
						$picture=CPicture::get_image_path($session['user']['acctid'],'p',1);
						if($session['user']['race']=='ecs') $racesex=2; //Grammatik: DAS Echsenwesen
					}
					
					$str_out.='`c'.print_frame(img_string($picture,strip_appoencode($session['user']['name'],3),200, 200),'',0,true).'`c
					`n`n`7Du trittst vor den Spiegel und fragst ihn:
					`n'.$c.'Spieglein, Spieglein an der Wand! Wer ist [der|die] Schönste im ganzen Land?
					`n
					`n`7Der Spiegel beginnt in allen Farben zu leuchten, bevor er antwortet:
					`n`#';
					if($session['user']['charm']==0)
					{ //kein Charme
						$str_out.='Ey Du hässliche'.words_by_sex('[r||s]',$racesex).' '.$racename.'! Geh mal beiseite, ich seh´ nix!';
					}
					else
					{
						$str_out.='[Herr|Frau] '.$session['user']['login'].', Ihr seid '.words_by_sex('[der|die|das]',$racesex).' schönste '.$racename.' hier!`n';
						$sql='SELECT acctid,name,charm,alive
							FROM accounts
							WHERE charm>'.$session['user']['charm'].'
								'.$str_where_race.'
								AND sex='.$session['user']['sex'].'
							ORDER BY charm DESC
							LIMIT 1';
						$result=db_query($sql);
						if(db_num_rows($result))
						{
							$bestone=db_fetch_assoc($result);
							$charmmulti=round($bestone['charm']/$session['user']['charm']);
							if($charmmulti<2) $charmmulti='immernoch viel';
							else $charmmulti='noch '.$charmmulti.'mal';
							$str_out.='Aber '.$bestone['name'].'`#'.($bestone['alive']?'':', über den sieben Bergen, bei den sieben Zwergen,').' ist '.$charmmulti.' schöner als Ihr.';
						}
						else
						{
							$str_out.='[Kein Mann|Keine Frau] ist schöner als Ihr.';
						}

					} //END charm>0
				} //END spiegel geputzt
				else
				{
					$str_out.='Was tut man nicht alles, um etwas über die eigene Schönheit zu erfahren? 
					`nEtwas Wasser vom Dorfbrunnen wäre zum Spiegel putzen sehr hilfreich. Da du aber keins hast, befeuchtest du deinen Hemdsärmel mit Spucke und reibst auf dem Spiegel herum.
					`nNach einer Weile ist der Spiegel zwar ziemlich verschmiert, aber wenigstens nicht mehr staubig.
					`n
					`n`7Du trittst vor den Spiegel und fragst ihn:
					`n'.$c.'Spieglein, Spieglein an der Wand! Wer ist [der|die] Schönste im ganzen Land?
					`n
					`n`7Der Spiegel beginnt matt zu leuchten, bevor er antwortet:
					`n`#[Herr|Frau] '.$session['user']['login'].', Ihr seid der größte Schmutzfink hier! Jeder Oger ist schöner als Ihr.
					`n`n`7Vor Schreck über diese Antwort verlierst du all deine Charmepunkte, die sogleich durchs ganze Zimmer kullern.
					`nEs gelingt dir jedoch, '.$session['user']['charm'].' Punkte wieder einzusammeln.';
				}
			}
			else
			{
				$str_out.='`7Vor dir siehst du einen magischen Spiegel.
				`nDieser Spiegel kann dir eine ehrliche Antwort über deine Schönheit geben.';
				if(!$session['daily']['mirrorcleaned'])
				{
					$str_out.='`n`4Allerdings ist der Spiegel stark verschmutzt, du wirst eine Portion Wasser opfern müssen, um ihn zu putzen.';
				}
				$str_out.='`n`7
				`nWillst du den Spiegel befragen?';
				addnav('Ja, ich will das wissen',$item_hook_info['link'].'&act=ask');
			}
			addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			output(words_by_sex($str_out));

			break;
	}
}
?>
