<?php
// "Parkhaus" für Tiere V1.5
// Beinhaltet Erträge, Schlachtung und XP-Bonus für den Beruf "Bauer"
// by Maris (Maraxxus@gmx.de)


function house_ext_stables ($str_case, $arr_ext, $arr_house) {

	global $session,$g_arr_house_extensions,$playermount;

	$str_base_file = 'house_extensions.php?_ext_id='.$arr_ext['id'];

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);

	switch($str_case)
	{
		// In den Ställen
		case 'in':

			$bool_access = true;
			if($arr_house['owner'] != $session['user']['acctid']
			&& is_array($arr_content['deniedstables'])
			&& $arr_content['deniedstables'][$session['user']['acctid']]>0)
			{
				$bool_access = false;
			}
			
			$gamedate=getsetting('gamedate','05-01-01').'-'.getsetting('actdaypart',1);
			
			switch ($_GET['act'])
			{
				case 'deposit':
				{
					// Mount laden
					getmount($session['user']['hashorse'],false);
					if (!$_GET['animal'])
					{
						output("`2Willst du dein Tier ".$playermount['mountname']." `2in die Ställe hineinsetzen?");
						addnav("Ja","$str_base_file&act=deposit&animal=1");
						addnav("Nein",$str_base_file);
					}
					else
					{
						$m=1;
						$used = 0;
						if (is_array($arr_content))
						{
							//Tiere zählen...
							$m = count($arr_content);
							if(isset($arr_content['deniedstables'])) $m--;

							//...und leere Position füllen
							$find=$m+1;
							$used=$m;
							for ($j=1; $j<=$m; $j++)
							{
								if (!is_array($arr_content[$j]))
								{
									$find=$j;
									$j=$m;
								}
							}
							$m=$find;
						}

						//Auf Überfüllung prüfen
						$total_space=$arr_ext['level']*2+2;
						if ($used>=$total_space)
						{
							output("`2Diese Ställe sind überfüllt, hier geht absolut nichts mehr rein!`nDamit du dein Tier hier unterbringen kannst muss entweder ein anderes raus oder der Stall vergrößert werden.`n`n");
						}
						else
						{
							$d_mount = $arr_content;
							$sql = 'SELECT * FROM mounts WHERE mountid='.$playermount['mountid'];
							$result = db_query($sql);
							$d_mount[$m] = db_fetch_assoc($result);

							$sql = 'SELECT hasxmount, mountextrarounds, xmountname, mountspecialdate, mount_sausage FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
							$result = db_query($sql);
							$extra = db_fetch_assoc($result);

							$d_mount[$m]['hasxmount']=$extra['hasxmount'];
							$d_mount[$m]['mount_sausage']=$extra['mount_sausage'];
							$d_mount[$m]['mountextrarounds']=$extra['mountextrarounds'];
							$d_mount[$m]['xmountname']=$extra['xmountname'];
							$d_mount[$m]['ownerid']=$session['user']['acctid'];
							$d_mount[$m]['ownername']=$session['user']['login'];
							if (is_array($session['bufflist']['mount']))
							{
								$d_mount[$m]['rounds']=$session['bufflist']['mount']['rounds'];
							}
							else
							{
								$d_mount[$m]['rounds']=0;
							}
							// Datumstempel
							$d_mount[$m]['deposit_date']=$gamedate;
							$d_mount[$m]['dung_date']=$gamedate;
							$d_mount[$m]['special_date']=$extra['mountspecialdate'];
							$s_mount=utf8_serialize($d_mount);

							$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_mount)."' WHERE id=".$arr_ext['id'];
							db_query($sql);
							output("`2Du setzt dein Tier ".$d_mount[$m]['mountname']."`2 in ein freies Eckchen des Stalls und versprichst, bald wieder zu kommen.`n");
							unset($session['bufflist']['mount']);
							$session['user']['hashorse']=0;
							Cache::delete(Cache::CACHE_TYPE_SESSION, 'playermount');
							$sql = "UPDATE account_extra_info SET hasxmount=0,mountextrarounds=0,xmountname=null,mountspecialdate='', mount_sausage=0 WHERE acctid=".$session['user']['acctid'];
							db_query($sql);

							debuglog('setzte sein Tier ('.$d_mount[$m]['mountname'].')in den Stall HausNr.'.$arr_house['houseid']);
						}
						addnav("Zurück",$str_base_file);
					}
					break;
				}

				case 'takeout':
				{
					$position=$_GET['position'];
					$action=$_GET['action'];

					$playerjob = user_get_aei('job');
					$job = $playerjob['job'];

					switch ($action)
					{

						case 'take':
						{
							if (is_array($arr_content[$position]))
							{
								$sql = 'SELECT * FROM mounts WHERE mountid="'.$arr_content[$position]['mountid'].'"';
								$result = db_query($sql);
								$playermount = db_fetch_assoc($result);
								$session['bufflist']['mount']=utf8_unserialize($playermount['mountbuff']);
								if ($arr_content[$position]['hasxmount']==1)
								{
									$mountname=$arr_content[$position]['mountname'];
									$session['bufflist']['mount']['name']=$arr_content[$position]['xmountname']." `&($mountname`&)";
								}
								$session['bufflist']['mount']['rounds']=$arr_content[$position]['rounds'];
								$session['user']['hashorse']=$arr_content[$position]['mountid'];
								Cache::delete(Cache::CACHE_TYPE_SESSION, 'playermount');
								getmount($session['user']['hashorse'],true);
								if (!$arr_content[$position]['mount_sausage'])
								{
								$arr_content[$position]['mount_sausage']=0;
								}
								$sql = "UPDATE account_extra_info SET hasxmount=".$arr_content[$position]['hasxmount'].",mountextrarounds=".$arr_content[$position]['mountextrarounds'].",xmountname='".addstripslashes($arr_content[$position]['xmountname'])."',mountspecialdate='".$arr_content[$position]['special_date']."', mount_sausage=".$arr_content[$position]['mount_sausage']." WHERE acctid=".$session['user']['acctid']."";
								db_query($sql);
								unset($arr_content[$position]);
								$d_mount=utf8_serialize($arr_content);
								$sql = "UPDATE house_extensions SET content='".db_real_escape_string($d_mount)."' WHERE id=".$arr_ext['id'];
								db_query($sql);
								output("`2Du nimmst dein Tier ".$playermount['mountname']." `2 wieder an dich!");
								if ($session['bufflist']['mount']['rounds']<=0)
								{
									output("`n`2Doch leider ist das arme Tier schon viel zu müde, um dir heute noch dienlich zu sein. Vielleicht solltest du bis morgen warten.`n");
									unset($session['bufflist']['mount']);
								}
								debuglog('nahm sein Tier ('.$playermount['mountname'].') aus dem Stall HausNr.'.$arr_house['houseid']);
							}
							else
							{
								output("`2Hoppla!`nGerade als du das Tier mitnehmen willst, stellst du fest, dass es gar nicht mehr da ist!`Scheinbar ist dir jemand zuvorgekommen.`n`n");
							}
							addnav("Zurück",$str_base_file);
							break;
						}

						case 'milk':
						{
							$position=$_GET['position'];
							output("`2Du rubbelst dir die Hände warm und näherst dich der Kuh.`n");
							if ($session['user']['turns']>0)
							{
								if ($arr_content[$position]['special_date']!=$gamedate)
								{
									$arr_content[$position]['special_date']=$gamedate;
									$s_arr_content=utf8_serialize($arr_content);
									$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_arr_content)."' WHERE id=".$arr_ext['id'];
									db_query($sql);
									$milk_gain=e_rand(5,15);
									output("`2Du melkst wie ein Weltmeister und bekommst `^$milk_gain Liter Milch`2!`nDu verlierst dabei einen Waldkampf.`n`n");
									$itemnew = item_get_tpl(' tpl_id="milchkn" ' );
									if ($itemnew)
									{
										$itemnew['tpl_description']=$itemnew['tpl_description']."`nInhalt: ".$milk_gain." Liter.";
										$itemnew['tpl_value1'] = $milk_gain;
										$itemnew['tpl_gold'] = $milk_gain*9;
										item_add($session['user']['acctid'],0,$itemnew);
									}
									$session['user']['turns']--;
									if ($job==$g_arr_house_extensions[$arr_ext['type']]['special_job'])
									{
										$xpgain=round($session['user']['experience']*0.01);
										if ($xpgain<75) $xpgain=75;
										output("`^Als Bauer bekommst du $xpgain Erfahrungspunkte!`n`n");
										$session['user']['experience']+=$xpgain;
									}
								}
								else
								{
									output("`2Doch leider wurde sie heute bereits gemolken!`nDu musst wohl bis morgen auf deine Milch warten.`n`n");
								}
							}
							else
							{
								output("`2Du bist bereits zu müde um noch eine Kuh zu melken!`n");
							}
							addnav('Tier ansehen',$str_base_file.'&act=takeout&position='.$position);
							addnav('Zum Stall',$str_base_file);
							break;
						}

						case 'eggs':
						{
							$position=$_GET['position'];
							output("`2Du näherst dich der Henne und schaust vorsichtig ins Nest.`n");
							if ($session['user']['turns']>0)
							{
								if ($arr_content[$position]['special_date']!=$gamedate)
								{
									$arr_content[$position]['special_date']=$gamedate;
									$s_arr_content=utf8_serialize($arr_content);
									$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_arr_content)."' WHERE id=".$arr_ext['id'];
									db_query($sql);
									$egg_gain=e_rand(2,5);
									output("`2Deine Henne hat dir ganze `^$egg_gain Eier gelegt`2!`nDu verlierst einen Waldkampf.`n`n");
									$itemnew = item_get_tpl(' tpl_id="eiersch" ' );
									if ($itemnew)
									{
										$itemnew['tpl_description']=$itemnew['tpl_description']."`nInhalt: ".$egg_gain." Stück.";
										$itemnew['tpl_value1'] = $egg_gain;
										$itemnew['tpl_gold'] = $egg_gain*5;
										item_add($session['user']['acctid'],0,$itemnew);
									}
									$session['user']['turns']--;
									if ($job==$g_arr_house_extensions[$arr_ext['type']]['special_job'])
									{
										$xpgain=round($session['user']['experience']*0.01);
										if ($xpgain<75) $xpgain=75;
										output("`^Als Bauer bekommst du $xpgain Erfahrungspunkte!`n`n");
										$session['user']['experience']+=$xpgain;
									}
								}
								else
								{
									output("`2Doch leider kannst du keine weiteren Eier dort entdecken.`nDu wirst bis morgen auf neue warten müssen.`n`n");
								}
							}
							else
							{
								output("`2Du bist leider schon zu müde um noch das Nest zu plündern.");
							}
							addnav('Tier ansehen',$str_base_file.'&act=takeout&position='.$position);
							addnav('Zum Stall',$str_base_file);
							break;
						}

						case 'breed':
						{
							$position=$_GET['position'];
							output('`2Du näherst dich und schaust ob dein '.$arr_content[$position]['mountname'].'`2 schon etwas mehr Speck angesetzt hat.`n');
							if ($session['user']['turns']>0)
							{
								if ($arr_content[$position]['special_date']!=$gamedate)
								{
									if ($arr_content[$position]['mountid']==71)
									{
										$maximum=5000;
										$cost=100;
										$ink=250;
									}
									else if ($arr_content[$position]['mountid']==72)
									{
										$maximum=10000;
										$cost=200;
										$ink=500;
									}
									else
									{
										$maximum=0;
										$cost=0;
										$ink=0;
									}

									if ($session['user']['gold']<$cost)
									{
										output("`2Das spezielle Mastfutter kostet `^$cost Goldmünzen`2, die du leider nicht hast.`n");
									}
									else
									{
									if ($arr_content[$position]['mount_sausage']>=$maximum)
									{
										output("`2Dein ".$arr_content[$position]['mountname']."`2 ist bereits gut gemästet und reif für die Schlachtung!`n");
									}
									else
									{
										$arr_content[$position]['mount_sausage']+=$ink;
										if ($arr_content[$position]['mount_sausage']>$maximum)
										{
										$arr_content[$position]['mount_sausage']=$maximum;
										}
										$arr_content[$position]['special_date']=$gamedate;
										$s_arr_content=utf8_serialize($arr_content);
										$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_arr_content)."' WHERE id=".$arr_ext['id'];
										db_query($sql);

										output("`2Du gibst deinem Tier ordentlich Kraftfutter und hoffst dass sich die Mast auch lohnt.`n`n");
										$session['user']['gold']-=$cost;
										$session['user']['turns']--;
									}
									}
								}
								else
								{
									output('`2Dein '.$arr_content[$position]['mountname'].'`2 scheint keinen Hunger zu haben.`nEs hat wohl heute schon gefressen. Warte bis morgen!`n`n');
								}
							}
							else
							{
								output("`2Du bist leider schon zu müde um noch Futtersäcke zu schleppen.");
							}
							addnav('Tier ansehen',$str_base_file.'&act=takeout&position='.$position);
							addnav('Zum Stall',$str_base_file);
							break;
						}

						case 'sheep':
						{
							$position=$_GET['position'];

							// Vereinfachte Berechnung der vergangenen Tage
									$date1=$arr_content[$position]['special_date'];
									$date2=$gamedate;
									$ye=mb_substr($date2,0,4)-mb_substr($date1,0,4);
									$mo=mb_substr($date2,5,2)-mb_substr($date1,5,2);
									$da=mb_substr($date2,8,2)-mb_substr($date1,8,2);
									$day_diff=intval($ye)*365+intval($mo)*30+intval($da);

							output("`2Du näherst dich dem Schaf mit verschlagenem Blick.`n");
							if ($session['user']['turns']>0)
							{
								//5 2-Stunden-Tage werden zu 2 6-Stunden-Tagen
								$earntime=ceil(5/(getsetting('dayparts',1)));
								if ($day_diff>=$earntime)
								{
									$arr_content[$position]['special_date']=$gamedate;
									$s_arr_content=utf8_serialize($arr_content);
									$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_arr_content)."' WHERE id=".$arr_ext['id'];
									db_query($sql);
									$sheep_gain=e_rand(3,7);
									output("`2Du schaffst es, dein Schaf um `^$sheep_gain Pfund Wolle`2 zu erleichtern!`2!`nDu verlierst einen Waldkampf.`n`n");
									$itemnew = item_get_tpl(' tpl_id="wolle" ' );
									if ($itemnew)
									{
										$itemnew['tpl_description']=$itemnew['tpl_description']."`nIn diesem Fall satte ".$sheep_gain." Pfund.";
										$itemnew['tpl_value1'] = $sheep_gain;
										$itemnew['tpl_gold'] = $sheep_gain*100;
										item_add($session['user']['acctid'],0,$itemnew);
									}
									$session['user']['turns']--;
									if ($job==$g_arr_house_extensions[$arr_ext['type']]['special_job'])
									{
										$xpgain=round($session['user']['experience']*0.0125);
										if ($xpgain<100) $xpgain=100;
										output("`^Als Bauer bekommst du $xpgain Erfahrungspunkte!`n`n");
										$session['user']['experience']+=$xpgain;
									}
								}
								else
								{
									output("`2Doch leider hat das arme Tier schon überall kahle Stellen.`nDu wirst wohl noch ".(($earntime-$day_diff==1) ? 'einen Tag' : ($earntime-$day_diff).' Tage')." warten müssen.`n`n");
								}
							}
							else
							{
								output("`2Du bist leider schon zu müde um noch ein Schaf zu scheren.");
							}
							addnav('Tier ansehen',$str_base_file.'&act=takeout&position='.$position);
							addnav('Zum Stall',$str_base_file);
							break;
						}

						case 'honey':
						{
							$position=$_GET['position'];
							output("`2Du näherst dich vorsichtig dem Bienenstock.`n");
							if ($session['user']['turns']>0)
							{
								if ($arr_content[$position]['special_date']!=$gamedate)
								{
									$arr_content[$position]['special_date']=$gamedate;
									$s_arr_content=utf8_serialize($arr_content);
									$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_arr_content)."' WHERE id=".$arr_ext['id'];
									db_query($sql);
									$honey_gain=e_rand(100,250);
									output("`2Deine Bienen waren fleissig. Du kannst dem Stock `^$honey_gain gramm Honig`2 entnehmen!`nDu verlierst einen Waldkampf.`n`n");
									$itemnew = item_get_tpl(' tpl_id="honig" ' );
									if ($itemnew)
									{
										$itemnew['tpl_description']=$itemnew['tpl_description']."`nDiese Portion ist ganze ".$honey_gain." gramm schwer.";
										$itemnew['tpl_value1'] = $honey_gain;
										$itemnew['tpl_gold'] = round($honey_gain*0.6);
										item_add($session['user']['acctid'],0,$itemnew);
									}
									$session['user']['turns']--;
									if ($job==$g_arr_house_extensions[$arr_ext['type']]['special_job'])
									{
										$xpgain=round($session['user']['experience']*0.01);
										if ($xpgain<100) $xpgain=100;
										output("`^Als Bauer bekommst du $xpgain Erfahrungspunkte!`n`n");
										$session['user']['experience']+=$xpgain;
									}
									if (e_rand(1,3)==3)
									{
										output("`4`n`nDie Bienen stechen dich!`nDu verlierst einige Lebenspunkte!`n");
										$lost=e_rand(10,$session['user']['hitpoints']*0.5);
										$session['user']['hitpoints']=max(1,$session['user']['hitpoints']-$lost);
									}
								}
								else
								{
									output("`2Doch leider sind die Bienen derart gereizt, dass du dich lieber erstmal nicht mehr in Gefahr bringen willst.`n`n");
								}
							}
							else
							{
								output("`2Doch leider findest du nicht mehr den Mut erneut hinein zu greifen.`nDu wirst bis morgen warten müssen.");
							}
							addnav('Tier ansehen',$str_base_file.'&act=takeout&position='.$position);
							addnav('Zum Stall',$str_base_file);
							break;
						}

						case 'wax':
						{
							$position=$_GET['position'];
							output("`2Du näherst dich vorsichtig dem Bienenstock.`n");
							if ($session['user']['turns']>0)
							{
								if ($arr_content[$position]['special_date']!=$gamedate)
								{
									$arr_content[$position]['special_date']=$gamedate;
									$s_arr_content=utf8_serialize($arr_content);
									$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_arr_content)."' WHERE id=".$arr_ext['id'];
									db_query($sql);
									$wax_gain=e_rand(50,100);
									output("`2Deine Bienen waren fleissig. Du kannst dem Stock `^$wax_gain gramm Wachs`2 entnehmen!`nDu verlierst einen Waldkampf.`n`n");
									$itemnew = item_get_tpl(' tpl_id="wachskl" ' );
									if ($itemnew)
									{
										$itemnew['tpl_description']=$itemnew['tpl_description']."`nDieser ist stolze ".$wax_gain." gramm schwer.";
										$itemnew['tpl_value1'] = $wax_gain;
										$itemnew['tpl_gold'] = round($wax_gain*0.9);
										item_add($session['user']['acctid'],0,$itemnew);
									}
									$session['user']['turns']--;
									if ($job==$g_arr_house_extensions[$arr_ext['type']]['special_job'])
									{
										$xpgain=round($session['user']['experience']*0.01);
										if ($xpgain<100) $xpgain=100;
										output("`^Als Bauer bekommst du $xpgain Erfahrungspunkte!`n`n");
										$session['user']['experience']+=$xpgain;
									}
									if (e_rand(1,3)==3)
									{
										output("`4`n`nDie Bienen stechen dich!`nDu verlierst einige Lebenspunkte!`n");
										$lost=e_rand(10,$session['user']['hitpoints']*0.5);
										$session['user']['hitpoints']=max(1,$session['user']['hitpoints']-$lost);
									}
								}
								else
								{
									output("`2Doch leider sind die Bienen derart gereizt, dass du dich lieber erstmal nicht mehr in Gefahr bringen willst.`n`n");
								}
							}
							else
							{
								output("`2Doch leider findest du nicht mehr den Mut erneut hinein zu greifen.`nDu wirst bis morgen warten müssen.");
							}
							addnav('Tier ansehen',$str_base_file.'&act=takeout&position='.$position);
							addnav('Zum Stall',$str_base_file);
							break;
						}

						case 'clean':
						{
							$position=$_GET['position'];
							output("`2Du näherst dich mit prüfendem Blick dem Tier.`n");
							if ($session['user']['turns']>0)
							{
								if ($arr_content[$position]['dung_date']!=$gamedate)
								{
									$arr_content[$position]['dung_date']=$gamedate;
									$s_arr_content=utf8_serialize($arr_content);
									$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_arr_content)."' WHERE id=".$arr_ext['id'];
									db_query($sql);
									output("`2Was für ein Gestank! Da war jemand fleissig und bei guter Verdauung. Es braucht eine Weile den ganzen Mist aufzusammeln.`nDu verlierst dabei einen Waldkampf.`n`n");
									$session['user']['turns']--;
                  
                  addnav('Den Mist aufsammeln',$str_base_file.'&act=takeout&action=shit&position='.$position);
                  
									if ($job==$g_arr_house_extensions[$arr_ext['type']]['special_job'])
									{
									$xpgain=round($session['user']['experience']*0.0075);
									if ($xpgain<75) $xpgain=75;
									output("`^Als Bauer bekommst du $xpgain Erfahrungspunkte!`n`n");
									$session['user']['experience']+=$xpgain;
									}
								}
								else
								{
									output("`2Doch irgendwie findest du nichts, was du ausmisten könntest.`nEntweder hat hier schon jemand sauber gemacht oder das Tier kam einfach noch nicht dazu Dreck zu machen. Hab einfach Geduld...`n`n");
								}
							}
							else
							{
								output("`2Du bist wirklich schon viel zu müde um heute noch in der Sch... äh im Schmutz zu wühlen!");
							}
              
							addnav('Tier ansehen',$str_base_file.'&act=takeout&position='.$position);
							addnav('Zum Stall',$str_base_file);
							break;
						}
            
            case 'shit':
            {
              output("`2Was auch immer du damit vorhast, du sammelst den ganzen Haufen Mist ein und nimmst ihn auch noch mit. Du Freak!"); 
              $position=$_GET['position'];
              $itemnew = item_get_tpl(' tpl_id="thedung" ' );
									if ($itemnew)
									{
										if ($arr_content[$position]['mountproduct'])
										{
										$itemnew['tpl_name']=$arr_content[$position]['mountproduct']."`0-".$itemnew['tpl_name'];
										}
										else
										{
										$itemnew['tpl_name']=$arr_content[$position]['mountname']."`0-".$itemnew['tpl_name'];
										}
										item_add($session['user']['acctid'],0,$itemnew);
									}
              addnav('Tier ansehen',$str_base_file.'&act=takeout&position='.$position);
							addnav('Zum Stall',$str_base_file);
							break;               
              
            }

						case 'remove':
						{
							$position=$_GET['position'];
							if ($_GET['confirm'])
							{
								if ($session['user']['turns']>0)
								{
									if (is_array($arr_content[$position]))
									{
										if($arr_content[$position]['dung_date']<>$gamedate)
										{ //Tier muss 1 Tag im Stall stehen ehe es geschlachtet werden darf. deposit_date ändert sich aber bei Betrachten
											$valuegold=$_GET['valuegold'];
											$itemnew = item_get_tpl(' tpl_id="wursthm" ' );
											if ($itemnew)
											{
												$itemnew['tpl_name'].=" Typ: ".$arr_content[$position]['mountname']."`0";
												if ($arr_content[$position]['mountproduct'])
												{
													$itemnew['tpl_description']=$itemnew['tpl_description']."`n`&Für die Herstellung wurde ausschliesslich ".$arr_content[$position]['mountproduct']."`0-Fleisch verwendet!";
												}
												else
												{
													$itemnew['tpl_description']=$itemnew['tpl_description']."`n`&Für die Herstellung wurde ausschliesslich ".$arr_content[$position]['mountname']."`0-Fleisch verwendet!";
												}
												if ($arr_content[$position]['hasxmount'])
												{
													$itemnew['tpl_description']=$itemnew['tpl_description']."`n`nDamit wird dir ".$arr_content[$position]['xmountname']."`& immer in schmackhafter Erinnerung bleiben.";
												}
												$itemnew['tpl_gold'] = $valuegold;
												if ($itemnew['tpl_gold']==0)
												{
													$itemnew['tpl_gold']=1;
												}
												item_add($session['user']['acctid'],0,$itemnew);
											}
											$session['user']['turns']--;
											if ($job==$g_arr_house_extensions[$arr_ext['type']]['special_job'])
											{
												$multip=round($valuegold/100000);
												if ($multip<0.01) $multip=0.01;
												$xpgain=round($session['user']['experience']*$multip);
												if ($xpgain<100) $xpgain=100;
												output("`^Als Bauer bekommst du $xpgain Erfahrungspunkte!`n`n");
												$session['user']['experience']+=$xpgain;
											}
											output('`2Mit breitem Grinsen machst du dich an dein blutiges Werk und kurze Zeit später ist '.$arr_content[$position]['mountname'].' `2nicht mehr.`nDu verlierst einen Waldkampf.`n`n');
											if ($session['user']['acctid']!=$arr_content[$position]['ownerid'])
											{
												output("`2Eigentlich war diese Aktion schon recht mies... Nur gut, dass das niemand mitbekommen hat...`n`n");
												insertcommentary($session['user']['acctid'],'/me `&wurde beobachtet wie '.($session['user']['sex'] ? 'sie ' : 'er ').$arr_content[$position]['mountname'].'`& von`^ '.$arr_content[$position]['ownername'].' `&zu Wurst verarbeitet hat!','stables-'.$arr_ext['houseid']);
											}
											unset($arr_content[$position]);
											$d_mount=utf8_serialize($arr_content);
											$sql = "UPDATE house_extensions SET content='".db_real_escape_string($d_mount)."' WHERE id=".$arr_ext['id'];
											db_query($sql);
											debuglog('schlachtet '.$arr_content[$position]['mountname'].' in Stall HausNr.'.$arr_house['houseid']);
										}
										else
										{
												output("`2Gerade als du dich in fieser Absicht dem Tier näherst kommen die Nachbarskinder und bewundern deine Neuanschaffung. Ja klar, sie finden das Tier ja ach so süüüß.`nVerdammt, so wird das heute nichts mit dem Schlachten...`n`n");
										}
									}
									else
									{
										output("`2Als du dich in fieser Absicht dem Tier näherst, stellst du fest, dass es gar nicht mehr da ist.`nWarscheinlich hat es den Braten gerochen und ist geflüchtet, oder aber es wurde rechtzeitig abgeholt.`n`n");
									}
								}
								else
								{
									output("`2Du bist leider schon zu müde um noch ein Tier zu schlachten!`n");
								}
							}
							else
							{
								if (is_array($arr_content[$position]))
								{
									$valuegold=(int)($arr_content[$position]['mount_sausage']);
									if (!$valuegold)
									{
										$sql = "SELECT * FROM mounts WHERE mountid=".(int)$arr_content[$position]['mountid'];
										$result = db_query($sql);
										$v_mount = db_fetch_assoc($result);
										$valuegold=$v_mount['mount_sausage'];
									}

									if ($session['user']['acctid']==$arr_content[$position]['ownerid'])
									{
										output('`2Willst du dein Tier '.$arr_content[$position]['mountname'].'`2 wirklich schlachten?`nSein Fleisch wäre wohl '.$valuegold.' Goldmünze'.($valuegold==1?'':'n').' wert!');
									}
									else
									{
										output($arr_content[$position]['mountname']."`2 von ".$arr_content[$position]['ownername']."`2 macht sich irgendwie ziemlich in deinen Ställen breit.`nUnd da du sicherlich keine Herberge für den Zoo anderer Leute bist, denkst du dir, dass es durchaus nicht unrecht wäre, wenn du dir in deinen eigenen Ställen Platz schaffst.`nImmerhin kümmert sich der Besitzer wohl nicht um sein Tier, also wird er es auch nicht vermissen.`n`nFür das Fleisch des Tieres würdest du gut und gerne $valuegold Goldmünze".($valuegold==1?'':'n')." bekommen!`n");
									}
									addnav("Aktionen");
									addnav("Verwursten",$str_base_file."&act=takeout&action=remove&position=$position&confirm=1&valuegold=$valuegold");
								}
								else
								{
									output("`2Als du dich dem vermeintlich dauergeparkten Tier näherst, stellst du fest, dass da gar nichts ist...`nSeltsam...`n`n");
								}
							}
							addnav("Zurück");
							addnav("Zum Stall",$str_base_file);
							break;
						}

						case 'exchange':
						{
							$position=$_GET['position'];
							if (is_array($arr_content[$position]))
							{
								//Tier aus dem arr_content laden und zwischenspeichern
								$sql = 'SELECT * FROM mounts WHERE mountid='.$arr_content[$position]['mountid'];
								$result = db_query($sql);
								$playermount = db_fetch_assoc($result);
								$newmount=utf8_unserialize($playermount['mountbuff']);
								if ($arr_content[$position]['hasxmount']==1)
								{
									$mountname=$arr_content[$position]['mountname'];
									$newmount['name']=$arr_content[$position]['xmountname']." `&($playermount[mountname]`&)";
								}
								else
								{
									$newmount['name']=$playermount['mountname'];
								}
								$newmount['rounds']=$arr_content[$position]['rounds'];
								$newmount['mountid']=$arr_content[$position]['mountid'];
								$newmount['special_date']=$arr_content[$position]['special_date'];
								$newmount['dung_date']=$arr_content[$position]['dung_date'];
								$new_hashorse=$arr_content[$position]['mountid'];
								$new_hasxmount=$arr_content[$position]['hasxmount'];
								$new_mount_sausage=$arr_content[$position]['mount_sausage'];
								$new_mountextrarounds=$arr_content[$position]['mountextrarounds'];
								$new_xmountname=$arr_content[$position]['xmountname'];

								//Tier aus dem arr_content entfernen
								unset($arr_content[$position]);
								$d_mount=utf8_serialize($arr_content);
								$sql = "UPDATE house_extensions SET content='".db_real_escape_string($d_mount)."' WHERE id=".$arr_ext['id'];
								db_query($sql);

								//Aktuelles Spielertier laden
								$sql = 'SELECT * FROM mounts WHERE mountid='.$session['user']['hashorse'];
								$result = db_query($sql);
								$arr_content[$position] = db_fetch_assoc($result);

								$sql = 'SELECT hasxmount, mountextrarounds, xmountname, mountspecialdate, mount_sausage FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
								$result = db_query($sql);
								$extra = db_fetch_assoc($result);

								//Spielertier in den arr_content
								$arr_content[$position]['hasxmount']=$extra['hasxmount'];
								$arr_content[$position]['mount_sausage']=$extra['mount_sausage'];
								$arr_content[$position]['special_date']=$extra['mountspecialdate'];
								$arr_content[$position]['mountextrarounds']=$extra['mountextrarounds'];
								$arr_content[$position]['xmountname']=$extra['xmountname'];
								$arr_content[$position]['ownerid']=$session['user']['acctid'];
								$arr_content[$position]['ownername']=$session['user']['login'];
								if (is_array($session['bufflist']['mount']))
								{
									$arr_content[$position]['rounds']=$session['bufflist']['mount']['rounds'];
								}
								else
								{
									$arr_content[$position]['rounds']=0;
								}
								$arr_content[$position]['deposit_date']=$gamedate;
								$arr_content[$position]['dung_date']=$gamedate;
								$s_arr_content=utf8_serialize($arr_content);

								$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_arr_content)."' WHERE id=".$arr_ext['id'];
								db_query($sql);

								//Neues Tier dem Spieler zuweisen
								unset($session['bufflist']['mount']);
								output('`2Du nimmst das Tier '.$newmount['name'].'`2 wieder an dich und gibst dafür das Tier '.$arr_content[$position]['mountname'].'`2 in die Ställe.');
								debuglog('tauschte Tiere in Stall '.$arr_house['houseid']);
								if (!$new_mount_sausage)
								{
									$new_mount_sausage=0;
								}
								$sql = "UPDATE account_extra_info SET hasxmount=".$new_hasxmount.",mountextrarounds=".$new_mountextrarounds.",xmountname='".addstripslashes($new_xmountname)."',mountspecialdate='".$newmount['special_date']."', mount_sausage=".$new_mount_sausage." WHERE acctid=".$session['user']['acctid'];
								db_query($sql);
								$session['bufflist']['mount']=$newmount;
								if ($session['bufflist']['mount']['rounds']<=0)
								{
									output('`n`2Doch leider ist das arme Tier '.$newmount['name'].'`2 schon viel zu müde, um dir heute noch dienlich zu sein. Vielleicht solltest du bis morgen warten.`n');
									unset($session['bufflist']['mount']);
								}
								$session['user']['hashorse']=$newmount['mountid'];
								Cache::delete(Cache::CACHE_TYPE_SESSION, 'playermount');
								getmount($session['user']['hashorse'],true);
							}
							else
							{
								output("`2Hmm... seltsam... du hättest schwören können, dass dieses Tier eben noch da war.`n`n");
							}
							addnav("Zurück",$str_base_file);
							break;
						}

						default:
						{
							if (is_array($arr_content[$position]))
							{
								if ($arr_content[$position]['deposit_date']!=$gamedate)
								{
									$sql = 'SELECT mountbuff FROM mounts WHERE mountid='.$arr_content[$position]['mountid'];
									$result = db_query($sql);
									$m_dummy = db_fetch_assoc($result);
									$m_dummy = utf8_unserialize($m_dummy['mountbuff']);
									$rounds = $m_dummy['rounds'];
									$arr_content[$position]['rounds']=$rounds+$arr_content[$position]['mountextrarounds'];
									$arr_content[$position]['deposit_date']=$gamedate;
									$s_arr_content=utf8_serialize($arr_content);
									$sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_arr_content)."' WHERE id=".$arr_ext['id'];
									db_query($sql);

								}
								output($arr_content[$position]['mountname'].'`2 hat heute noch '.$arr_content[$position]['rounds'].'`2 Runden übrig.`n`n');

								$c=0;
								if (is_array($arr_content))
								{
									foreach ($arr_content as $key => $val)
									{
										if($key!='deniedstables') $c++;
									}
								}
								$free_space=$arr_ext['level']*2+2-$c;

								if ($arr_content[$position]['ownerid']==$session['user']['acctid'])
								{
									output("`2Das ist dein Tier.`nWas soll damit geschehen?`n`n");
									addnav("Aktionen");
									if ($session['user']['hashorse']>0)
									{
										output('`4Mitnehmen kannst du es so nicht, da dir bereits ein anderes Vieh hinterherläuft.');
										if($bool_access==true)
										{
											output('`n`2Du könntest die beiden allerdings gegeneinander austauschen.`n');
											addnav("Austauschen",$str_base_file."&act=takeout&position=$position&action=exchange");
										}
									}
									else
									{
										addnav("Mitnehmen",$str_base_file."&act=takeout&position=$position&action=take");
									}
									if ($free_space>=0)
									{

										if ($arr_content[$position]['mountid']==67) //Milchkuh
										{
											addnav("Besondere Aktionen");
											addnav("Melken",$str_base_file."&act=takeout&position=$position&action=milk");
										}
										else if ($arr_content[$position]['mountid']==68) //Hühnchen
										{
											addnav("Besondere Aktionen");
											addnav("Eier suchen",$str_base_file."&act=takeout&position=$position&action=eggs");
										}
										else if ($arr_content[$position]['mountid']==69) //Honigbienen
										{
											addnav("Besondere Aktionen");
											addnav("Honig gewinnen",$str_base_file."&act=takeout&position=$position&action=honey");
											addnav("Wachs gewinnen",$str_base_file."&act=takeout&position=$position&action=wax");
										}
										else if ($arr_content[$position]['mountid']==70) //Schaf
										{
											addnav("Besondere Aktionen");
											addnav("Scheren",$str_base_file."&act=takeout&position=$position&action=sheep");
										}
										else if ($arr_content[$position]['mountid']==71) //Mastschwein
										{
											addnav("Besondere Aktionen");
											addnav("Mästen `^(100 Gold)`0",$str_base_file."&act=takeout&position=$position&action=breed");
										}
										else if ($arr_content[$position]['mountid']==72) //Zuchtbulle
										{
											addnav("Besondere Aktionen");
											addnav("Mästen `^(200 Gold)`0",$str_base_file."&act=takeout&position=$position&action=breed");
										}
										addnav("Ausmisten",$str_base_file."&act=takeout&position=$position&action=clean");
									}
									else
									{
									output("`2Der Stall ist dermaßen überfüllt, dass die Tiere darunter leiden!`nSchaffe Platz bevor du etwas mit ihnen anstellst!`n");
									}
								}
								else
								{
									output("`2Dies ist nicht dein Tier!`nDu kannst es nicht mitnehmen!");
									if ($session['user']['house']==$session['housekey'])
									{
										addnav("Besondere Aktionen");
									}
								}
								if ($session['user']['house']==$session['housekey'] || $arr_content[$position]['ownerid']==$session['user']['acctid'])
								{
									if ($session['user']['acctid']!=$arr_content[$position]['ownerid'])
									{
									addnav("Ausmisten",$str_base_file."&act=takeout&position=$position&action=clean");
									}
									addnav("`4Schlachten`0",$str_base_file."&act=takeout&position=$position&action=remove");
								}
								addnav("In Ruhe lassen");
								addnav("Zum Stall",$str_base_file);
							}
							else
							{
								output("`2Das Tier wurde bereits aus den Stall geholt, bevor du es dir näher ansehen konntest.");
								addnav("Zurück",$str_base_file);
							}
							break;
						}
					}
					break;
				}

				case 'manage': //Zugangssystem
				{
					$sql = 'SELECT a.acctid, a.name FROM keylist k LEFT JOIN accounts a ON a.acctid=k.owner WHERE k.value1='.$arr_ext['houseid'].' AND k.type='.HOUSES_KEY_DEFAULT.' AND owner > 0 AND owner != '.$arr_house['owner'].' ORDER BY k.id ASC';
					$result = db_query($sql);

					$str_content_md5 = md5(utf8_serialize($arr_content['deniedstables']));

					if($_POST['check'] == 'speichern')
					{
						unset($arr_content['deniedstables']);
						$arr_content['deniedstables'] = array();
					}

					if(is_array($_POST['id']))
					{
						foreach($_POST['id'] as $key => $value)
						{
							$arr_content['deniedstables'][$value] = 1;
						}
					}

					if($str_content_md5  != md5(utf8_serialize($arr_content['deniedstables'])))
					{
						$sql = "UPDATE house_extensions SET content='".db_real_escape_string(utf8_serialize($arr_content))."' WHERE id=".$arr_ext['id'];
						db_query($sql);

						$str_output .= '`@gespeichert`0`n`n';
					}

					$str_output .= get_title('`^Stallverwaltung');
					$str_output .= 'Hier kannst Du auswählen, welche der Wesen, die einen Schlüssel zu deinem Haus haben, <u>nichts</u> in deinen Stall stellen dürfen.`n`n';

					$str_output .= '<hr><br />
						<table align="center" cellpading="0" cellspacing="0">
							<form action="'.$str_base_file.'&act=manage" method="POST">';

					while($row=db_fetch_assoc($result))
					{
						if($arr_content['deniedstables'][$row['acctid']] > 0)
						{
							$bool_checked = true;
						}
						else
						{
							$bool_checked = false;
						}

						if($row['acctid'] != $session['user']['acctid'])
						{
							$str_output .= '
								<tr>
									<td><input type="checkbox" name="id[]" value="'.$row['acctid'].'" '.($bool_checked? 'checked':'').'> '.$row['name'].'</td>
								</tr>';
						}
					}

					$str_output .= '
						</table><br />
						<input type="hidden" name="check" value="speichern">';

					//START - alle Checkboxen markieren/abwählen
					$str_output.='<input type="button" value="Alle markieren" class="button" onClick="';
					$str_output.='for(i=0;i<document.getElementsByName(\'id[]\').length;i++) {document.getElementsByName(\'id[]\')[i].checked=true;}';
					$str_output.='">';

					$str_output.='<input type="button" value="Alle abwählen" class="button" onClick="';
					$str_output.='for(i=0;i<document.getElementsByName(\'id[]\').length;i++) {document.getElementsByName(\'id[]\')[i].checked=false;}';
					$str_output.='">';
					//END - alle Checkboxen markieren/abwählen

					$str_output .= '<br /><hr><br />
						<input type="submit" class="button" value="Speichern"></form>';

					addnav('',$str_base_file.'&act=manage');
					addnav('Zurück');
					addnav('Zum Stall',$str_base_file);
					output($str_output);
					break;
				}

				default:
				{
					$level=$arr_ext['level'];
					$total_space=$arr_ext['level']*2+2;
					output('`2Du befindest dich nun in den Ställen dieses Hauses.
					`nDieser Stall hat die Ausbaustufe '.$level.' und bietet Platz für insgesamt '.$total_space.' Tiere.`n`n');
					$c = count($arr_content);
					if (isset($arr_content['deniedstables'])) $c--;

					if ($c>0)
					{
						output('`2Als du dich umschaust entdeckst du folgende Tiere:`n(Klicke ein Tier an um es näher zu betrachten)`n`n');
						$i=1;

						foreach ($arr_content as $key => $val)
						{
							if($key=='deniedstables') //das würde einen leeren Tier-Eintrag erzeugen
							{
								continue;
							}
							$linktext=($val['hasxmount'] ? $val['xmountname'].' `2('.$val['mountname'].'`2)`0' : $val['mountname']);
							$navtext=$i.'. Box: '.strip_appoencode($val['mountname']);
							output('`0'.create_lnk($linktext,$str_base_file.'&act=takeout&position='.$key,true,true,'',false,$navtext,1).'`2 von `&'.$val['ownername'].'`2`n');
							$i++;
						}
					}
					else
					{
						output('`2Die einzigen Tiere in diesen Stallungen sind ein paar verschreckte Wühlmäuse und unzählige kleine Spinnen!`n`n');
					}
					$free_space=$total_space-$c;
					output('`n`2Freie Plätze in diesem Stall: '.$free_space.'`n');
					if ($session['user']['hashorse']>0 && $free_space > 0 && $bool_access==true)
					{
						addnav('Stallungen');
						addnav('Tier hineinsetzen',$str_base_file.'&act=deposit');
					}
					if($arr_house['owner'] == $session['user']['acctid'])
					{
						addnav('Verwaltung',$str_base_file.'&act=manage');
					}
					addnav('Zurück');
					addnav('Zum Haus','inside_houses.php');

					// Und chatten darf man auch noch
					output('`n`n');
					$comment_length=max($arr_ext['c_max_length'],getsetting('chat_post_len',600));
					viewcommentary('stables-'.$arr_ext['houseid'],'Zum Pferdeflüsterer werden:',30,'sagt',false,true,false,$comment_length,true);
					break;
				}
			}

		break;
		// END in den Ställen

		// Bau fertig
		case 'build_finished':

			// Wenn Bauernhof: Gleich auf Lvl 2 updaten
			if ($arr_ext['level'] == 0)
			{
				if($arr_house['status'] == 50 || $arr_house['status'] == 54 || $arr_house['status'] == 57) 
				{
						global $str_out;
						$str_out .= '`n`tDa du den Stall aufgrund deines Hausausbaus gleich viel besser nutzen kannst, startet dieser auf Stufe zwei!`0`n';
						// Level + 1 im weiteren Codeverlauf!
						db_query('UPDATE house_extensions SET level=1,content="'.utf8_serialize(array()).'" WHERE id='.$arr_ext['id']);
				}
			}
		break;

		// Abreißen
		case 'rip':

			global $str_out;

			$m = count($arr_content);
			if(isset($arr_content['deniedstables'])) $m--;
			if($m > 0) 
			{
				$str_out .= 'In deinem Stall befinden sich noch Tiere, die durch einen Abriss verlorengehen würden. Du solltest dich zunächst darum kümmern!';
				output($str_out);
				addnav('In die Ställe!',$str_base_file);
				page_footer();
			}

		break;

		// Job gekündigt
		case 'job_cancelled':
		
		break;

		// Job angenommen
		case 'job_obtained':
		
		break;

	}
	// END Main Switch

}
// END Main Function

?>
