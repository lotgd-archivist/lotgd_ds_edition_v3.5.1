<?php

function terror_hook_process ( $item_hook , &$item ) {

	global $session,$item_hook_info;

	switch ( $item_hook ) {

		case 'newday':

			if ($item['deposit1']==0 && $item['deposit2']!=0)
			{
				if ($item['value1']!=0)
				{
					$properties = ' owner < 1234567 AND (gold>0 OR gems>0) AND deposit2=0 AND deposit1='.$item['deposit2'];
					$extra = ' ORDER BY rand('.e_rand().') LIMIT 1';

					$res = item_list_get($properties , $extra , true , ' name,description,id,gold,gems' );
					if (db_num_rows($res)>0 && e_rand(1,2)==2)
					{
						$victim = db_fetch_assoc($res);

						$maxgolddamage=100*($item['hvalue']+1);
						$maxgemdamage=($item['hvalue']+1);
						$golddamage=round(e_rand($maxgolddamage*0.1,$maxgolddamage));
						$gemdamage=round(e_rand($maxgemdamage*0.5,$maxgemdamage));

						if ($victim['gold']>0)
						{
							$victim['gold']=max(0,$victim['gold']-$golddamage);
							$gemdamage=0;
						}
						else
						{
							$victim['gems']=max(0,$victim['gems']-=$gemdamage);
						}

						insertcommentary(1,'/msg '.$item['name'].' `4zerknabbert und beschädigt '.$victim['name'].'.','house-'.$item['deposit2']);

						item_set('id='.$victim['id'],$victim);

					}
					elseif (e_rand(1,3)==1)
					{
						$sql = 'SELECT a.login AS login FROM accounts a LEFT JOIN keylist k ON a.acctid=k.owner WHERE k.value1='.$item['deposit2'].' ORDER BY rand('.e_rand().') LIMIT 1';
						$result = db_query($sql);
						if (db_num_rows($result)>0)
						{
							$victim = db_fetch_assoc($result);
							$vname = $victim['login'];

							switch (e_rand(1,10))
							{
								case 1:
									$text="`4 knabbert Löcher in die Socken von `&".$vname;
									break;
								case 2:
									$text="`4 verschleppt die Schuhe von `&".$vname;
									break;
								case 3:
									$text="`4 pinkelt auf die Hosen von `&".$vname;
									break;
								case 4:
									$text="`4 zerlöchert die Kleidung von `&".$vname;
									break;
								case 5:
									$text="`4 vergeht sich am Frühstück von `&".$vname;
									break;
								case 6:
									$text="`4 beißt `&".$vname."`4 des nachts in die Füße";
									break;
								case 7:
									$text="`4 versteckt die Waffen von `&".$vname;
									break;
								case 8:
									$text="`4 zerbeisst das Kopfkissen von `&".$vname;
									break;
								case 9:
									$text="`4 verschandelt die frische Unterwäsche von `&".$vname;
									break;
								case 10:
									$text="`4 nagt an den Hausschuhen von `&".$vname;
									break;
							}
							$text=$item['name'].$text;

							insertcommentary(1,'/msg '.$text,'house-'.$item['deposit2']);

						}

					}
					else
					{
						$sql = 'SELECT gems FROM houses WHERE houseid='.$item['deposit2'];
						$result = db_query($sql);
						$victim = db_fetch_assoc($result);
						$gemtheft=e_rand(1,10)+$item['hvalue'];
						if ($victim['gems']>0 && $gemtheft>8)
						{
							$victim['gems']--;
							$sql = "UPDATE houses SET gems=".$victim['gems']." WHERE houseid=$item[deposit2]";
							db_query($sql);

							insertcommentary(1,'/msg '.$item['name'].'`4 klaut einen Edelstein aus der Schatztruhe.','house-'.$item['deposit2']);

							systemmail($item['owner'],'Edelstein erbeutet',"`@Dein ".$item['name']."`@ bringt dir einen Edelstein, den es aus dem Schatz von Haus ".$item['deposit2']." geklaut hat!");
							$session['user']['gems']++;
						}
						else
						{
							insertcommentary(1,'/msg '.$item['name'].'`4 nervt durch unentwegte Knabbergeräusche und lautes Fiepsen.','house-'.$item['deposit2']);

						}

					}
					$item['value1']--;
					item_set('id='.$item['id'],$item);

					if ($item['value1'] <= 0)
					{
						$itemnew = item_get_tpl( 'tpl_name="`&Frustriertes `&T`)e`&r`)r`&o`)r`thörnchen`0" ' );
						if( false !== $itemnew )
						{
							$itemnew['tpl_hvalue']=$item['hvalue'];
							$itemnew['tpl_hvalue2']=$item['hvalue2'];
							$itemnew['tpl_special_info']=$item['special_info'];
							$itemnew['deposit2']=0;
							if (mb_strpos($item['name'],"`&(`&T`)e`&r`)r`&o`)r"))
							{
								$oldname=mb_substr($item['name'],0,mb_strpos($item['name'],"`&(`&T`)e`&r`)r`&o`)r"));
								trim($oldname);
								$itemnew['tpl_name']=$oldname." `&(`&Frustriertes `&T`)e`&r`)r`&o`)r`thörnchen`0`&)";
							}
							item_add($session['user']['acctid'],0,$itemnew);
							item_delete( ' id='.$item['id']);
							systemmail($item['owner'],'Terror-Auftrag beendet',"`@Dein ".$item['name']."`@ kehrt nach Erfüllung seines Auftrags in Haus ".$item['deposit2']." zu dir zurück.`nNun sollte es für seine Arbeit entlohnt werden!");
						}
					}
				}
			}
			break;

		case 'use':
			if ($_GET['act']=="takeback")
			{
				$item_change['deposit2'] = 0;
				item_set('id='.$item['id'],$item_change);
				redirect($item_hook_info['ret']);
			}
			elseif ($_GET['act']=="lookup")
			{
				$search=$_POST['trai'];
				if ($search>0)
				{
					$sql = "SELECT h.houseid AS houseid,h.housename AS housename,a.name AS name FROM houses h LEFT JOIN accounts a ON h.owner=a.acctid WHERE status>0 AND owner>0 AND houseid=".$search;
					$result = db_query($sql);
					$counts=db_num_rows($result);
				}
				if ($counts>0)
				{
					$row = db_fetch_assoc($result);

					output("<a href=".$item_hook_info['link']."&op=use&act=proceed&number=".$row['houseid'].">Hausnummer:".$row['houseid']." ($row[housename])
					`nBesitzer: ".$row['name']." </a>`n`n",true);

					addnav("",$item_hook_info['link']."&op=use&act=proceed&number=".$row['houseid']);
				}
				else
				{
					output("`&Kein geeignetes Haus mit dieser Nummer gefunden!`n`n");
				}
				output("`0<form action='".$item_hook_info['link']."&op=use&act=lookup' method='POST'>
				<input name='trai' id='trai'>
				<input type='submit' class='button' value='Hausnummer suchen'>
				</form>
				".focus_form_element('trai'));
				addnav("",$item_hook_info['link']."&op=use&act=lookup");
				addnav("Zum Inventar",$item_hook_info['ret']);
			}
			elseif ($_GET['act']=="proceed")
			{
				$number=intval($_GET['number']);

				if (item_count('tpl_id="squirrg" AND deposit1=0 AND deposit2='.$number)==0)
				{
					output("Alles klar!`nDein Terrorhörnchen wird nun in Haus Nummer ".$number." Angst und Schrecken verbreiten!`n");
					$item['deposit2']=$number;
					item_set('id='.$item['id'],$item);
					addnav("Sehr gut!",$item_hook_info['ret']);
				}
				else
				{
					output("Das geht leider nicht!
					`nDieses Haus ist bereits von einem Terrorhörnchen infiltriert worden.");
					addnav("Auch gut",$item_hook_info['ret']);
				}
			}
			elseif ($item['deposit2']>0)
			{
				output($item['name']."`& befindet sich bereits im aktiven Einsatz in Haus Nummer ".$item['deposit2']."!
				`nWillst du dein Terrorhörnchen zurückbeordern?`n");
				addnav("Ja",$item_hook_info['link']."&op=use&act=takeback");
				addnav("Nein",$item_hook_info['ret']);
			}
			else
			{
				output($item['name']."`& ist bereit, um Angst und Schrecken zu verbreiten.
				`nAuf welches Haus willst du dein Terrorhörnchen loslassen?
				`n`n`0<form action='".$item_hook_info['link']."&op=use&act=lookup' method='POST'>
				<input name='trai' id='trai'>
				<input type='submit' class='button' value='Hausnummer suchen'>
				</form>
				".focus_form_element('trai'));
				addnav("",$item_hook_info['link']."&op=use&act=lookup");
				addnav("Zum Inventar",$item_hook_info['ret']);
			}

			break;
	}

}

?>
