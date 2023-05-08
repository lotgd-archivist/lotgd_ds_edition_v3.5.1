<?php
// Garten V1.0
// Ersetzt das Blumenbeet und erlaubt das säen, pflegen und ernten von verschiedenen Pflanzen.
// by Maris (Maraxxus@gmx.de)

function house_ext_garden($str_case, $arr_ext, $arr_house)
{

	global $session,$access_control;
    if(!isset($str_output))$str_output='';
	$str_base_file = 'house_extensions.php?_ext_id='.$arr_ext['id'];

	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);

	switch ($str_case)
	{

		// Im Garten
		case 'in':
		{
		{ //Hilfsklammer für Notepad
			$date2 = getsetting('gamedate','0005-01-01').'-'.getsetting('actdaypart',1);
			if (isset($arr_content['updated_day']))
			{
				$date1 = $arr_content['updated_day'];
			}
			else
			{
				$date1 = $date2;
			}
			if (!isset($arr_content['updated_by']))
			{
				$arr_content['updated_by'] = 0;
			}

			////////////
			// Konstanten
			////////////

			$level=$arr_ext['level'];
			$bed_h = 6;
			$bed_v = 4*$level;
			$total_space=$bed_v*$bed_h;

			$water_low_dmg = 30;
			$water_high_dmg = 115;
			$water_good_low = 95;
			$water_good_high = 105;

			//
			// Große Aktualisierungsschleife
			//
			if ($date1!=$date2)
			{
				$arr_content['updated_by']=$session['user']['acctid'];
				$arr_content['updated_day']=$date2;
				$sql = "UPDATE house_extensions SET content='".db_real_escape_string(utf8_serialize($arr_content))."' WHERE id=".$arr_ext['id'];
				db_query($sql);

				// Nur noch ein Update pro Tag

				if ($arr_content['updated_by'] == $session['user']['acctid']) //Was macht diese Abfrage? Die ist doch immer true wenn das 5 Zeilen höher so festgelegt wird
				{
					//Update-Vorgang
					$sql = 'SELECT c.position AS position,t.pest AS pest ,t.lifespan AS max_age, t.sensibility AS sensibility, t.sprout AS sprout, t.assert AS assert, t.size AS size, c.id AS id, c.age AS age, c.condition AS `condition`, t.path AS path, c.stage AS stage, t.stage AS stages, t.name AS name,c.owner_name AS owner_name, c.sort AS sort, c.occupies AS occupies FROM crops c
						LEFT JOIN crops_tpl t ON c.sort=t.id
						WHERE garden='.$arr_ext['id'];
					$result = db_query($sql);
					$amount_of_plants = db_num_rows($result);

/* der müll geht nicht, ich geb erstmal auf
Problemstellung: Das soll _nur_ ausgeführt werden wenn der Garten Unstimmigkeiten hat
Reparieren ansich funktioniert, wenn auch ziemlich umständlich
					//bei Bedarf Garten reparieren
					$arr_tmp=array_unique($arr_content['occupies']);
					if(count($arr_tmp)!=$amount_of_plants)
					{
						$arr_content=repair_garden($arr_ext['id']);
						$session['message']='`nEin Lindwurm hat diesen Garten durchquert.`n'.count($arr_tmp).' '.$amount_of_plants;
						//Seeehr dirty workaround, aber ich krieg es nicht anders hin die Änderung zu übernehmen (Salator)
						redirect('inside_houses.php');
					}
*/

					// Schädlinge
					for ($k=1; $k<=$total_space; $k++)
					{
						if ((!$arr_content[$k] || $arr_content[$k]==0) && (!$arr_content['occupied'][$k] || $arr_content['occupied'][$k]==0))
						{
							$free[]=$k;
						}
					}
					if (count($free)>0 && e_rand(1,10)==1)
					{
						$p_where = $free[e_rand(0,(count($free)-1))];
						$occupies[] = $p_where;

						$sql = 'SELECT id FROM crops_tpl WHERE pest=1 ORDER BY RAND()';
						$result_pest = db_query($sql);
						$new_pest = db_fetch_assoc($result_pest);
						$new_pest = $new_pest['id'];

						$sql = 'INSERT INTO crops SET `sort`='.$new_pest.', `garden`='.(int)$arr_ext['id'].', `sizeh`=1, `sizev`=1, `position`='.(int)$p_where.', `occupies`="'.db_real_escape_string(utf8_serialize($occupies)).'", `stage`=0, `condition`=100, `age`=0, `fruit`=0, `owner_name`="niemandem", `owner_id`=0';
						db_query($sql);

						$arr_content[$p_where]=db_insert_id();
						$arr_content['occupied'][$p_where]=db_insert_id();
					}
					// Schädlinge Ende

					////////////placeholder210////////////placeholder210////////////////
					// Schleife über die Anzahl der Pflanzen
					////////////placeholder210////////////placeholder210////////////////
					for ($i_plants=1; $i_plants<=$amount_of_plants; $i_plants++)
					{
						$crop_s = db_fetch_assoc($result);
						$crop_s['assert'] = utf8_unserialize($crop_s['assert']);
						$max_stage = count(utf8_unserialize($crop_s['stages']));
						$crop_id = $crop_s['id'];

						// Wasserstand
						if ($crop_s['condition']>0)
						{
							//Seerose braucht einen See
							if ($crop_s['name']=='Seerose')
							{
								if ($arr_content['water']>=$water_high_dmg)
								{
									$crop_s['condition']+=15;
								}
								else
								{
									$crop_s['condition']-=10;
								}
							}
							// 1. perfekt
							else if (($arr_content['water']>=$water_good_low) && ($arr_content['water']<=$water_good_high))
							{
								$crop_s['condition']+=15;
							}
							// 2. Geht so
							else if (($arr_content['water']>=$water_low_dmg) && ($arr_content['water']<=$water_high_dmg))
							{
								$crop_s['condition']+=5;
							}
							// 3. Ürgs
							else
							{
								$crop_s['condition']-=10;
							}

							$resistance = 100 - $crop_s['sensibility'];
							$damage = 0;
							if (is_array($arr_content['assert']))
							foreach($arr_content['assert'] as $key => $val)
							{
								if ($key!=$crop_s['sort'])
								{
									$damage+=$val;
								}
							}
							$res_damage = $damage-$resistance;
							if ($res_damage>=50)
							{
								$crop_s['condition']-=15;
							}
							else if ($res_damage>=30)
							{
								$crop_s['condition']-=10;
							}
							else if ($res_damage>=15)
							{
								$crop_s['condition']-=6;
							}
							else if ($res_damage>=5)
							{
								$crop_s['condition']-=3;
							}
						}
						// Wasserstand Ende

						// Altern (tot)
						$crop_s['age']++;
						if ($crop_s['max_age']>0 && $crop_s['age']>=$crop_s['max_age'])
						{
							$crop_s['condition']=0;
							$new_condition = $crop_s['condition'];
							$sql = "UPDATE crops SET `condition`=".$new_condition." WHERE id=".$crop_id;
							db_query($sql);
						}
						// Altern Ende

						// Zustand beschränken
						if ($crop_s['condition']<0)
						{
							$crop_s['condition']=0;
						}
						else if ($crop_s['condition']>100)
						{
							$crop_s['condition']=100;
						}
						// Zustand beschränken Ende

						// Unkraut nimmt keinen Schaden
						if ($crop_s['pest']==1)
						{
							$crop_s['condition']=100;
						}

						// Nächstes Wachstumsstadium
						$size = utf8_unserialize($crop_s['size']);
						$test_string = strtok($size[$crop_s['stage']+1],":");

						if (($crop_s['stage']<($max_stage-1)) && ($crop_s['age'] >= $test_string) && ($crop_s['condition']>0))
						{
							$crop_s['stage']++;
							$new_stage = $crop_s['stage'];
							$size = utf8_unserialize($crop_s['size']);
							strtok($size[$crop_s['stage']], ":");
							$size_x = strtok("x");
							$size_y = strtok("");
							strtok($size[$crop_s['stage']-1], ":");
							$size_x_old = strtok("x");
							$size_y_old = strtok("");
							if (($size_x*$size_y)>($size_x_old*$size_y_old))
							{
								$position = $crop_s['position'];

								for ($i=1; $i<=$size_y; $i++)
								{
									for ($j=1; $j<=$size_x; $j++)
									{
										$pos = $position - ((($i-1)*$bed_h)-1+$j);

										$nogo = 0;
										if ((ceil(($pos + ($size_x-1))/$bed_h) == ceil($pos/$bed_h)) && (($pos>0) && ($pos+($bed_h*($size_y-1)+($size_x-1)) <= ($bed_h*$bed_v)) ))
										{
											unset($check_fields);
											$up_left = $pos;
											for ($y=1; $y<=$size_y; $y++)
											{
												for ($x=1; $x<=$size_x; $x++)
												{
													$try = $up_left + ($y-1)*$bed_h + ($x-1);
													if ((($arr_content[$try]>0) &! ($arr_content[$try]==$crop_id)) || ($arr_content['occupied'][$try] && $arr_content['occupied'][$try]!=$crop_id))
													{
														$nogo = 1;
													}
													else
													{
														$check_fields[]=$try;
													}
												}
											}
											if ($nogo==0)
											{
												$found[]=$up_left;
												$occ[] = $check_fields;
											}
										}
									}
								}
							}
							else
							{
								$found[0] = $crop_s['position'];
								$occ[0] = utf8_unserialize($crop_s['occupies']);
							}
							if (is_array($found))
							{
								$choice = e_rand(0,(count($found)-1));
								$new_position = $found[$choice];
								if ($size_x*$size_y>1) unset($arr_content[$position]);

								for($i=1; $i<=($bed_h*$bed_v); $i++) //alte Position löschen
								{
									if($arr_content['occupied'][$i] == $crop_id)
									{
										$arr_content['occupied'][$i] = 0;
									}
								}

								foreach ($occ[$choice] as $val)
								{
									$arr_content['occupied'][$val]=$crop_id;
									if ($size_x*$size_y>1) $arr_content[$new_position]=$crop_id;
								}

								$arr_content['assert'][$crop_s['sort']]-=$crop_s['assert'][$crop_s['stage']-1];
								$arr_content['assert'][$crop_s['sort']]+=$crop_s['assert'][$crop_s['stage']];

								if ((($new_stage-1)=="0") && (e_rand(1,100)>$crop_s['sprout']))
								{
									$new_stage--;
									$crop_s['condition']=0;
								}

								$occ = utf8_serialize($occ[$choice]);
								$new_age = $crop_s['age'];
								$new_condition = $crop_s['condition'];
								$sql = "UPDATE crops SET position=".$new_position.", `condition`=".$new_condition.", occupies='".db_real_escape_string($occ)."', sizeh=".$size_x.", sizev=".$size_y.", stage=".$new_stage.", age=".$new_age." WHERE id=".$crop_id;
								db_query($sql);
								unset($occ);
								unset($found);
							}
						}
						else
						{
							$new_age = $crop_s['age'];
							$new_condition = $crop_s['condition'];
							$sql = "UPDATE crops SET `condition`=".$new_condition.", age=".$new_age." WHERE id=".$crop_id;
							db_query($sql);
						}
						// Nächstes Wachstumsstadium Ende
					}

					// Wasserstand anpassen
					$arr_content['water']-=e_rand(5,15);
					if ($arr_content['water']<0)
					{
						$arr_content['water']=0;
					}

				}

				$sql = "UPDATE house_extensions SET content='".db_real_escape_string(utf8_serialize($arr_content))."' WHERE id=".$arr_ext['id'];
				db_query($sql);
			}

			// Im Garten
			$bool_write_output = true;
			$int_care_price = 150;
			$int_water_price = 100;
		} //End Hilfsklammer für Notepad

			$act = $_GET['act'];
			switch ($act)
			{
				case 'look':
				{
					$str_title = get_title('`yBetrachte ein Stück des Beetes');
					$field = $_GET['field'];
					$str_output .= "`2Du kniest dich auf den Boden und betrachtest Feld $field.`n`c";
					if ($arr_content[$field]>0)
					{
						$sql = 'SELECT c.id AS id, t.pest AS pest, c.sizeh AS sizeh, c.sizev AS sizev, c.age AS age, c.condition AS `condition`, t.path AS path, c.stage AS stage, t.stage AS stages, t.name AS name,c.owner_id AS owner, c.owner_name AS owner_name FROM crops c LEFT JOIN crops_tpl t ON c.sort=t.id WHERE garden='.$arr_ext['id'].' AND position='.$field;
						$result = db_query($sql);
						$crop_s = db_fetch_assoc($result);
						$stages = utf8_unserialize($crop_s['stages']);

						if ($crop_s['owner_name']=="")
						{
							$crop_s['owner_name']="niemandem";
						}
						$str_output .= "<img style='background-image:url(./images/garden/soil.png);' src='./images/garden/".$crop_s['path']."/".$crop_s['stage'].".png' border='0'>`c`n`n";
						$str_output .= "`2Hier wächst `^".$crop_s['name']."`2 von `^".$crop_s['owner_name']."`2!`n`n";

						$str_output .= "Stadium: `^".$stages[$crop_s['stage']]."`2`n
								Alter: `^".$crop_s['age']." Tage`2`n
								Zustand: `^".$crop_s['condition']."%`2";
						addnav("Aktionen");
						if ($crop_s['condition']==0)
						{
							$str_output .= "`4 (tot)`n`2";
						}
						else
						{
							//Hausherr und Besitzer dürfen alles
							if ($session['user']['acctid']==$crop_s['owner'] || $session['user']['acctid']==$arr_house['owner'])
							{
								addnav("Abernten",$str_base_file."&act=harvest&field=".$field."&crop_id=".$crop_s['id']);
							}
							//Andere User dürfen auch ernten, das wird aber angezeigt
							//else
							//{
								//$str_confirm = "Das ist nicht Deine Pflanze. Möchtest du sie wirklich abernten?";
								//addnav("Abernten",$str_base_file."&act=harvest&field=".$field."&crop_id=".$crop_s['id'],false,false,false,true,$str_confirm);
							//}
							if ($crop_s['pest']!=1)
							{
								if(!$session['gardencaremsg'])
								{
									$str_confirm = "Das Aufpäppeln einer Pflanze kann helfen sie vor dem Tode zu bewahren, kostet dich allerdings auch ".$int_care_price." Goldmünzen!\\nBist du dir sicher?";
									$session['gardencaremsg']=1;
								}
								addnav("Aufpäppeln",$str_base_file."&act=care&field=".$field."&crop_id=".$crop_s['id'],false,false,false,true,$str_confirm);
							}
						}

						//Vernichten, Hauseigentümer darf alles zerstören, Unkrauthacken darf jeder
						if ($session['user']['acctid']==$crop_s['owner'] || $session['user']['acctid']==$arr_house['owner'] || $crop_s['pest']==1 || $crop_s['condition']==0)
						{
							$str_confirm = "Bist du sicher, dass du mit deiner Hacke alles vernichten möchtest,\\nwas sich auf diesem Stück Beet befindet?";
							addnav("Achtung!");

							addnav("Vernichten",$str_base_file."&act=kill&field=".$field."&crop_id=".$crop_s['id'],false,false,false,true,$str_confirm);
						}

						//Verschenken
						if ($session['user']['acctid']==$crop_s['owner'])
						{
							addnav("s?Verschenken",$str_base_file."&act=gift&crop_id=".$crop_s['id']);
						}
					}
					else
					{
						addnav("Aktionen");
						addnav("Etwas pflanzen",$str_base_file."&act=plant&field=".$field);
						$str_output .= "<img src='./images/garden/soil.png'>`c`n`n";
						$str_output .= "Hier wächst gerade nichts...`n`n";
					}

					addnav("Zurück");
					addnav("Zum Garten",$str_base_file);
					addnav("Zum Haus","inside_houses.php");
					break;
				}

				case 'care':
				{
					$sql = 'SELECT c.id AS id, c.harvest as harvest,c.care as care, c.sizeh AS sizeh, c.sizev AS sizev, c.age AS age, c.condition AS `condition`, t.path AS path, c.stage AS stage, t.stage AS stages, t.fruit AS fruit, t.name AS name,c.owner_name AS owner_name FROM crops c LEFT JOIN crops_tpl t ON c.sort=t.id WHERE garden='.$arr_ext['id'].' AND c.id='.(int)$_GET['crop_id'];
					$result = db_query($sql);
					$crop_s = db_fetch_assoc($result);

					if ($session['user']['turns']>0)
					{
						if ($session['user']['gold']>=$int_care_price)
						{
							if ($crop_s['condition']<50)
							{
								if ($crop_s['care']!=$date2)
								{
									$session['message'] = "`2Du nimmst dir Zeit und gönnst dem armen Pflänzchen eine Intensivpflege.`n";
									$new_condition = $crop_s['condition']+e_rand(15,35);
									$new_care = $date2;
									$session['user']['gold']-=$int_care_price;
									$session['user']['turns']--;
									$sql = "UPDATE crops SET `condition`=".$new_condition.", care = '".$new_care."' WHERE id=".(int)$_GET['crop_id'];
									db_query($sql);
								}
								else
								{
									$session['message'] = "`2Diese Pflanze wurde heute bereits umsorgt!<br>Warte bis morgen.";
								}
							}
							else
							{
								$session['message'] = "`2Hier ist ein Aufpäppeln noch nicht nötig!`n";
							}
						}
						else
						{
							$session['message'] = "`2Der gute Wille allein reicht leider nicht - dir fehlt das Gold!`n";
						}
					}
					else
					{
						$session['message'] = "`2So müde wie du bist kannst du dich nicht noch um diese Pflanze kümmern!`n";
					}
					redirect($str_base_file."&act=look&field=".(int)$_GET['field']);

					break;
				}

				case 'harvest':
				{
					$sql = 'SELECT c.id AS id, c.harvest as harvest, c.sizeh AS sizeh, c.sizev AS sizev, c.age AS age, c.condition AS `condition`, t.path AS path, c.stage AS stage, t.stage AS stages, t.fruit AS fruit, t.name AS name,c.owner_name AS owner_name , c.owner_id FROM crops c LEFT JOIN crops_tpl t ON c.sort=t.id WHERE garden='.$arr_ext['id'].' AND c.id='.(int)$_GET['crop_id'];
					$result = db_query($sql);
					$crop_s = db_fetch_assoc($result);
					$crop_s['fruit']=utf8_unserialize($crop_s['fruit']);
					$field = (int)$_GET['field'];

					if (!$_GET['val'])
					{
						if ($crop_s['fruit'][0]!="")
						{
							addnav("Abernten");
							$str_title = get_title($crop_s['name'].' abernten');
							$count_h= 0;
							//Wenn bereits einmal geerntet wurde
							if ($crop_s['harvest']>0 && count($crop_s['fruit'])>0)
							{
								/*
								$date1 = $crop_s['harvest'];
								//$date2 = getsetting('gamedate','0005-01-01');
								$ye=mb_substr($date2,0,4)-mb_substr($date1,0,4);
								$mo=mb_substr($date2,5,2)-mb_substr($date1,5,2);
								$da=mb_substr($date2,8,2)-$da2=mb_substr($date1,8,2);
								$day_diff=$ye*365+$mo*30+$da;*/
								$date1 = $crop_s['harvest'];
								$date2 = $crop_s['age'];

								$day_diff = $date2 - $date1;

								$str_output .= "`@Die letzte Ernte war vor ".$day_diff." Tagen.`n`n`2Was willst du ernten?`n";
							}
							//Wenn Es bei dieser Pflanze nichts zu holen gibt
							elseif (count($crop_s['fruit'])==0)
							{
								$session['message'] = "Bei dieser Pflanze gibt es (noch) nichts zu ernten.";
								redirect($str_base_file."&act=look&field=".$field);
							}
							//Es wurde noch nie geerntet, aber es gibt etwas zu holen
							else
							{
								$str_output .= "`@Du erntest diese Pflanze heute zum ersten Mal.`n`n`2Was willst du ernten?`n";
								$day_diff=999;
							}

							foreach ($crop_s['fruit'] as $val)
							{
								$val = utf8_preg_replace("/\r|\n/s", "", $val);
								$req_state = strtok($val, ":");
								$tpl_id =  strtok(":");
								$min_fruit = strtok(":");
								$max_fruit = strtok(":");
								$date_diff = strtok(":");
								$destroys = strtok(":");
								$itemnew = item_get_tpl(' tpl_id="'.$tpl_id.'"' );
								if ($itemnew)
								{
									if ($crop_s['stage']>=$req_state)
									{
										if($day_diff>=$date_diff)
										{
											$str_output .= create_lnk($itemnew['tpl_name'],$str_base_file."&act=harvest&field=".$field."&crop_id=".(int)$_GET['crop_id']."&val=".$val,true,true,'',false,false,CREATE_LINK_LEFT_NAV_HOTKEY);
										}
										else
										{
											$str_output.='`1'.$itemnew['tpl_name'].'`1 ist noch nicht reif';
										}
										$str_output .= '<br>';
										$count_h++;
									}
								}
								else
								{
									$str_output .= "`4Item-Fehler`0`n";
								}

							}
						}
						else
						{
							$session['message'] = "`2Diese Pflanze ist unverwertbar!`n";
							redirect($str_base_file."&act=look&field=".(int)$_GET['field']);
						}
					}
					else if ($session['user']['turns']>0)
					{
						$val=$_GET['val'];
						$req_state = strtok($val, ":");
						$tpl_id =  strtok(":");
						$min_fruit = strtok(":");
						$max_fruit = strtok(":");
						$date_diff = strtok(":");
						$destroys = strtok(":");
						$itemnew = item_get_tpl(' tpl_id="'.$tpl_id.'"' );
						
						// Variation in der Qualität (falls mögl. / nötig)
						if($itemnew['cooking'] && $itemnew['tpl_hvalue'])
						{
							include_once(ITEM_MOD_PATH.'kitchen.php');
							kitchen_set_qual($itemnew['tpl_hvalue'],$itemnew['tpl_description'],50);
						}
						// END Variation

						/*$date1 = $crop_s['harvest'];
						//$date2 = getsetting('gamedate','0005-01-01');
						$ye=mb_substr($date2,0,4)-mb_substr($date1,0,4);
						$mo=mb_substr($date2,5,2)-mb_substr($date1,5,2);
						$da=mb_substr($date2,8,2)-$da2=mb_substr($date1,8,2);
						$day_diff=$ye*365+$mo*30+$da;*/

						$date1 = $crop_s['harvest'];
						$date2 = $crop_s['age'];

						$day_diff = $date2 - $date1;

						if ($destroys==1 && !$_GET['confirm'])
						{
							$str_title .= get_title("Tot beim Erntevorgang?");
							$str_output .= "`^Diese Pflanze wird das Abernten nicht überleben!<br>Trotzdem weitermachen?";
							addnav("Ja, ".$tpl_name." ernten!",$str_base_file."&act=harvest&crop_id=".(int)$_GET['crop_id']."&val=".$val."&confirm=1&field=".(int)$_GET['field']);
						}
						else if ($day_diff<$date_diff)
						{
							$session['message'] = "Du kannst jetzt nichts von dieser Pflanze nehmen, warte noch ".($date_diff-$day_diff)." Tage, bis etwas nachgewachsen ist!";
							redirect($str_base_file."&act=look&field=".(int)$_GET['field']);
						}
						else
						{
							$gain = e_rand($min_fruit,$max_fruit);
							if (!$_GET['confirm'])
							{
								//$h_date=$date2);
								// Stattdessen aktuelles Alter der Pflanze
								$sql = "UPDATE crops SET harvest=age WHERE id=".(int)$_GET['crop_id'];
							}
							else
							{
							//und hier sollte die Pflanze gleich mit gelöscht werden und der Garten geupdated
								$sql = "UPDATE crops SET `condition`=0 WHERE id=".(int)$_GET['crop_id'];
							}
							db_query($sql);

							for ($it=1; $it<=$gain; $it++)
							{
								item_add($session['user']['acctid'],0,$itemnew);
							}
							if($session['user']['acctid']!=$crop_s['owner_id'])
							{
								insertcommentary($session['user']['acctid'],'/me erntete die Erträge einer Pflanze von '.$crop_s['owner_name'].'.','h_garden-'.$arr_ext['houseid']);
							}
							$session['message'] = "Du erntest ".$gain."x ".$itemnew['tpl_name']." und verlierst einen Waldkampf.";
							$session['user']['turns']--;
							redirect($str_base_file."&act=look&field=".(int)$_GET['field']);
						}
					}
					else
					{
						$session['message'] .= "Du bist schon zu müde, um noch etwas abzuernten!";
						redirect($str_base_file."&act=look&field=".(int)$_GET['field']);
					}

					addnav("Zurück");
					addnav("Zum Garten",$str_base_file);
					break;
				}

				case 'kill':
				{
					$field = (int)$_GET['field'];
					$crop_id = (int)$_GET['crop_id'];
					if ($session['user']['turns']>0)
					{
						$sql = 'SELECT occupies, sort, c.condition, c.stage AS stage, c.owner_name, c.owner_id, t.assert AS assert, t.pest FROM crops c LEFT JOIN crops_tpl t ON c.sort=t.id WHERE c.id='.$crop_id;
						$result = db_query($sql);
						$crop_s = db_fetch_assoc($result);
						//$occupies_s = utf8_unserialize($crop_s['occupies']);
						$crop_s['assert'] = utf8_unserialize($crop_s['assert']);
						//zur Vermeidung von Lochfraß content-Array nach Vorkommen der Pflanzen-ID prüfen statt alle $occupies_s löschen
						foreach ($arr_content['occupied'] as $key => $val)
						{
							if($arr_content['occupied'][$key]==$crop_id)
							{
								$arr_content['occupied'][$key] = 0;
							}
						}
						$session['message'] = "Du nimmst die Gartenhacke und machst kurzen Prozess.";
						$arr_content[$field]=0;
						$arr_content['assert'][$crop_s['sort']]-=$crop_s['assert'][$crop_s['stage']];
						$ser_content = utf8_serialize($arr_content);
						$sql = "UPDATE house_extensions SET content='".db_real_escape_string($ser_content)."' WHERE id=".$arr_ext['id'];
						db_query($sql);
						if ($crop_id)
						{
							db_query('DELETE FROM crops WHERE id='.$crop_id);
						}
						$session['user']['turns']--;

						//Message erstellen wenn user != owner
						if($crop_s['owner_id'] > 0 && $session['user']['acctid'] != $crop_s['owner_id'] && $crop_s['pest']==0)
						{
							insertcommentary($session['user']['acctid'],'/me wurde dabei beobachtet, wie '.($session['user']['sex'] ? 'sie' : 'er').' eine '.($crop_s['condition']?'':'tote ').'Pflanze von '.$crop_s['owner_name'].' kleingehäckselt hat.','h_garden-'.$arr_ext['houseid']);
						}
						//Feld zerstört, zurück zum Garten
						redirect($str_base_file);
					}
					else
					{
						$session['message'] = "`2Du bist schon zu müde für die Gartenarbeit.`n";
						redirect($str_base_file."&act=look&crop_id=".$crop_id);
					}

					break;
				}

				case 'gift':
				{
					if($_POST['acctid']==0)
					{
						$sql = 'SELECT a.acctid, a.login 
						FROM keylist k 
						LEFT JOIN accounts a ON a.acctid=k.owner 
						WHERE k.value1='.$arr_ext['houseid'].' 
						AND k.type='.HOUSES_KEY_DEFAULT.' 
						AND owner > 0 
						AND owner != '.$session['user']['acctid'].'
						OR a.house='.$arr_ext['houseid'].'
						GROUP BY a.acctid
						ORDER BY a.login ASC';
						$result = db_query($sql);
						if(db_num_rows($result)>0)
						{
							$str_output.='<form action="'.$str_base_file."&act=gift&crop_id=".(int)$_GET['crop_id'].'" method="post">
							Diese Pflanze verschenken an <select name=acctid>';
							addnav('',$str_base_file."&act=gift&crop_id=".(int)$_GET['crop_id']);
						
							while($row=db_fetch_assoc($result))
							{
								$str_output.='<option value="'.$row['acctid'].'">'.$row['login'].'</option>';
							}
							$str_output.='</select>
							`n<input type="submit" value="OK" class="button">
							</form>';
						}
					}
					else
					{
						$acctid=intval($_POST['acctid']);
						$sql='SELECT name,login,uniqueid FROM accounts WHERE acctid='.$acctid;
						$row=db_fetch_assoc(db_query($sql));
						if(ac_check($row))
						{
							$str_output.=get_title('`$Ach du Sch...ande!').'Gerade als du die Pflanze an '.$row['name'].' verschenken willst zieht sich der Himmel mit schwarzen Wolken zu, es blitzt und donnert und regnet wie aus Eimern.
							`nOb dieses Mistwetter vielleicht etwas damit zu tun hat, dass du gerade etwas Verbotenes tun wolltest?';
						}
						else
						{
							$sql='UPDATE crops 
							SET owner_name="'.$row['login'].'", owner_id='.$acctid.' WHERE id='.(int)$_GET['crop_id'];
							db_query($sql);
							$sql='SELECT t.name FROM crops c LEFT JOIN crops_tpl t ON c.sort=t.id WHERE c.id='.(int)$_GET['crop_id'];
							$rowc=db_fetch_assoc(db_query($sql));
							systemmail($acctid,'`@botanisches Geschenk`0',$session['user']['name'].'`2 war so freundlich und hat dir ein Exemplar vom Typ `^'.$rowc['name'].'`2 im Garten des Hauses `@'.$arr_ext['houseid'].'`2 geschenkt.');
							$str_output.=get_title('`@Aktion erfolgreich').'`2Alles klar, `^'.$rowc['name'].'`2 gehört jetzt '.$row['name'];
						}
					}
					addnav('Zum Garten',$str_base_file);
					break;
				}

				case 'plant':
				{
					$field = (int)$_GET['field'];
					if ($field<1)
					{ //Fehler! Ruf nen Progger! Schnell!!!
						$str_output .= '`2Beim Versuch, etwas auf das '.(int)$_GET['field'].'. Feld deines Gartens zu pflanzen, bist du auf einen Fuchsbau gestoßen.`nAls dich der Fuchs wütend anknurrt machst du vor Schreck einen Sprung über den Gartenzaun.`0`n';
						addnav("Zurück",$str_base_file);
					}
					else
					{
						if ($arr_content[$field]==0)
						{
							$str_title = get_title('`yEtwas einpflanzen');
							if (!$_GET['item'])
							{
								$str_output .= "`n<table border='0'>
								<tr>
								<td colspan=3>`2`bWähle das Saatgut, das du einpflanzen möchtest:`b`0</td>
								</tr>";
								$sql = 'SELECT i.id,i.name,i.value1,ct.path,ct.stage 
								FROM items i 
								LEFT JOIN items_tpl t USING(tpl_id) 
								LEFT JOIN items_classes c on t.tpl_class=c.id 
								LEFT JOIN crops_tpl ct on ct.id=i.value1 
								WHERE owner='.$session['user']['acctid'].' 
								AND c.class_name="Saatgut" 
								ORDER BY i.value1, i.id ASC';
								$result = db_query($sql);
								$amount=db_num_rows($result);
								if (!$amount)
								{
									$str_output .= "<tr><td colspan=3>`iBei dir herrscht gerade ziemlicher Samenmangel.`i</td></tr>";
								}
								for ($i=1; $i<=$amount; $i++)
								{
									$item_s = db_fetch_assoc($result);
									$maxstage=(count(utf8_unserialize($item_s['stage']))-1);
									$str_output .= '<tr>
									<td valign="top">'.create_lnk('`&-'.$item_s['name'].'`0',$str_base_file."&act=plant&field=".$field."&item=".$item_s['id']).'</td>
									<td align="right" valign="bottom">'.create_lnk('<img src="./images/garden/'.$item_s['path'].'/0.png" border=0 alt="'.$item_s['name'],$str_base_file."&act=plant&field=".$field."&item=".$item_s['id']).'"></td>
									<td><img src="./images/garden/'.$item_s['path'].'/'.$maxstage.'.png" alt="'.$item_s['name'].'"></td>
									</tr>';
								}
								$str_output .= '</table>';
							}
							else
							{
								if ($session['user']['turns']>0)
								{
									$item_id = (int)$_GET['item'];

									$item = item_get('id='.$item_id);

									$occupies[1] = $field;

									$sql = 'INSERT INTO crops SET `sort`='.(int)$item['value1'].', `garden`='.(int)$arr_ext['id'].', `sizeh`=1, `sizev`=1, `position`='.(int)$field.', `occupies`="'.db_real_escape_string(utf8_serialize($occupies)).'", `stage`=0, `condition`=50, `age`=0, `fruit`=0, `owner_name`="'.db_real_escape_string($session['user']['login']).'", `owner_id`='.(int)$session['user']['acctid'];
									db_query($sql);

									$session['message'] = "Du pflanzt ".$item['name']." auf Feld $field und verlierst einen Waldkampf.`n`n";
									$arr_content[$field]=db_insert_id();
									$arr_content['occupied'][$field]=db_insert_id();
									$ser_content = utf8_serialize($arr_content);
									$sql = "UPDATE house_extensions SET content='".db_real_escape_string($ser_content)."' WHERE id=".$arr_ext['id'];
									db_query($sql);
									$session['user']['turns']--;
									item_delete(' id='.$item_id);
								}
								else
								{
									$session['message'] = "`2Du bist schon zu müde, um noch etwas einzupflanzen!`n";
								}
								redirect($str_base_file);
							}
						}
						else
						{
							$session['message'] = "`2Auf diesem Feld wächst bereits etwas!`n";
							redirect($str_base_file.'&op=plant');
						}
					}
					addnav("Zum Garten",$str_base_file);
					break;
				}

				case 'water':
				{
					if ($arr_content['water']<150)
					{

						if ($session['user']['gold']<$int_water_price)
						{
							$session['message'] .= "Du hast doch gar nicht mehr soviel Gold.";
							redirect($str_base_file);
						}
					}
					else
					{
						$session['message'] .= "Willst du hier etwa einen See anlegen? Das Beet hat wirklich genug Wasser!";
						redirect($str_base_file);
					}

					if ($arr_content['waterdate']==$date2)
					{
						$session['message'] = "Es scheint, als wäre dir jemand zuvor gekommen.<br>Warte doch bis morgen.";
					}
					else
					{
						if ($session['user']['turns']<1)
						{
							$session['message'] = "So gern du auch möchtest, du fühlst dich heute schon zu müde für Gartenarbeit!";
						}
						else
						{
							$session['message'] = "`2Du nimmst dir etwas Zeit und gießt und düngst das Beet.";
							$arr_content['waterdate']=$date2;
							$arr_content['water']+=e_rand(15,30);
							if ($arr_content['water']>150)
							{
								$arr_content['water']=150;
							}
							$ser_content = utf8_serialize($arr_content);
							$sql = "UPDATE house_extensions SET content='".db_real_escape_string($ser_content)."' WHERE id=".$arr_ext['id'];
							db_query($sql);
							$session['user']['turns']--;
							$session['user']['gold']-=$int_water_price;
						}
					}
					redirect($str_base_file);
					break;
				}

				case 'manage': //Garten Zugangssystem
				{
					$sql = 'SELECT a.acctid, a.name FROM keylist k LEFT JOIN accounts a ON a.acctid=k.owner WHERE k.value1='.$arr_ext['houseid'].' AND k.type='.HOUSES_KEY_DEFAULT.' AND owner > 0 AND owner != '.$arr_house['owner'].' ORDER BY k.id ASC';
					$result = db_query($sql);

					$str_content_md5 = md5(utf8_serialize($arr_content['deniedgarden']));

					if($_POST['check'] == 'speichern')
					{
						unset($arr_content['deniedgarden']);
						$arr_content['deniedgarden'] = array();
					}

					if(is_array($_POST['id']))
					{
						foreach($_POST['id'] as $key => $value)
						{
							$arr_content['deniedgarden'][$value] = 1;
						}
					}

					if($str_content_md5  != md5(utf8_serialize($arr_content['deniedgarden'])))
					{
						$sql = "UPDATE house_extensions SET content='".db_real_escape_string(utf8_serialize($arr_content))."' WHERE id=".$arr_ext['id'];
						db_query($sql);

						$str_output .= '`@gespeichert`0`n`n';
					}

					$str_output .= get_title('`^Gartenverwaltung');
					$str_output .= 'Hier kannst Du auswählen, welche der Wesen, die einen Schlüssel zu deinem Haus haben, <u>nicht</u> in deinen Garten kommen sollen.`n`n';

					$str_output .= '<hr><br />
						<table align="center" cellpading="0" cellspacing="0">
							<form action="'.$str_base_file.'&act=manage" method="POST">';

					while($row=db_fetch_assoc($result))
					{
						if($arr_content['deniedgarden'][$row['acctid']] > 0)
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
					addnav('Zum Garten',$str_base_file);
					break;
				}

				default: //Beetansicht
				{
					//START Garten Zugangssystem
					$bool_access = true;
					if($arr_house['owner'] != $session['user']['acctid']
					&& is_array($arr_content['deniedgarden'])
					&& $arr_content['deniedgarden'][$session['user']['acctid']]>0)
					{
						$bool_access = false;
					}

					if($bool_access == false)
					{
						$str_title = get_title('`yDer Garten');
						$str_output .= 'Du hast leider keine Erlaubnis, diesen Garten zu betreten und stehst deshalb vor einem verschlossenen Gartentor.';

						addnav('Zurück');
						addnav('Zum Haus','inside_houses.php');
						break;
					}
					//END Garten Zugangssystem

                repair_garden($arr_ext['id'],false);

					if ($_GET['op']=='plant')
					{
						$str_title = get_title('`yEinem Pflänzchen Leben schenken.');
						$str_output .= "`2Wo möchtest du die Saat einpflanzen? Wähle das Feld mit der Maus aus und achte darauf, dass dein Pflänzchen auch später genug Platz haben wird.`n`n";
						$what = 'plant';
					}
					else
					{
						$what = 'look';
						$str_title = get_title('`yDer Garten');
						$str_output .= "`2Du bist im Garten, um dich vielleicht ein wenig mit deinen Pflanzen zu beschäftigen. Der trockene, erdige Geruch steigt dir in die Nase. Du betrachtest das große Beet und bildest dir deine Meinung darüber.`n`nDieser Garten hat die Ausbaustufe $level.`nDas Beet bietet insgeamt Platz für $total_space kleine Pflänzchen.`n`n`0";
					}

					// Alle Pflanzen laden
					$sql = 'SELECT t.path, t.assert, t.fruit, t.name AS cropname,
					sort, c.stage, t.stage AS stages, owner_name, age, harvest, c.condition 
					FROM crops c LEFT JOIN crops_tpl t ON c.sort=t.id 
					WHERE garden = '.$arr_ext['id'].' 
					ORDER BY position ASC';
					$result2 = db_query($sql);

					$str_output .= "<style type='text/css'>
							div.beet{
							width:  300px;
							height: ".(200*$level)."px;
							position:relative;
							top:0px;
							left:0px;
							}
							.plant{
							position: absolute;
							display: inline;
							}
							</style>
							<div class='beet'>";

					for ($i=1; $i<=$bed_v; $i++)
					{
						for ($j=1; $j<=$bed_h; $j++)
						{
							$field = (($i-1)*$bed_h)+$j ;

							if ($arr_content[$field]>0)
							{
								$this_crop = db_fetch_assoc($result2);
								$stages = utf8_unserialize($this_crop['stages']);
								$fruits = utf8_unserialize($this_crop['fruit']);
								$fruit0=explode(':',$fruits['0']);

								$titletag=$this_crop['cropname'].' von '.$this_crop['owner_name'];
								$titletag.=" (".trim($stages[$this_crop['stage']]);
								$titletag.=', '.$this_crop['age'].' Tage, ';
								$titletag.=($this_crop['condition']?$this_crop['condition'].'%':'tot');
								if($this_crop['condition']>0 && $fruit0[0]!='' && $this_crop['stage']>=$fruit0[0] && ($this_crop['age']-$this_crop['harvest'])>=$fruit0[4])
								{
									$titletag.=', reif';
								}
								$titletag.=')';
								$str_output .= '
								<a href="'.$str_base_file.'&act='.$what.'&field='.$field.'" class="plant" style="left:'.($j * 50).'px;top:'.(($i -1)*50).'px;">
								<img style="background-image:url(./images/garden/soil.png);" src="./images/garden/'.$this_crop['path'].'/'.$this_crop['stage'].'.png" border="0" alt="'.$this_crop['cropname'].'" title="'.$titletag.'"></a>';
								addnav('',$str_base_file.'&act='.$what.'&field='.$field);
							}
							else
							{
								if (!$arr_content['occupied'][$field])
								{
									$str_output .= '
									<a href="'.$str_base_file.'&act='.$what.'&field='.$field.'" class="plant" style="left:'.($j *50).'px;top:'.(($i -1)*50).'px;">
									<img src="./images/garden/soil.png" border="0" alt=""></a>';
									addnav('',$str_base_file.'&act='.$what.'&field='.$field);
								}
							}
						}
					}
					$str_output .= "</div>";
					if (!$_GET['op'])
					{
						$str_output .= "`n`2Zustand des Beetes:`n`n";

						$free = 0;
						for ($k=1; $k<=$total_space; $k++)
						{
							if ((!$arr_content[$k] || $arr_content[$k]==0) && (!$arr_content['occupied'][$k] || $arr_content['occupied'][$k]==0))
							{
								$free++;
							}
						}

						$str_output .= "Freie Felder: ".$free."`n";
						if (!isset($arr_content['water']) || !isset($arr_content['updated_day']))
						{
							if (!isset($arr_content['water']))
							{
								$arr_content['water']=75;
							}
							if (!isset($arr_content['updated_day']))
							{
								$arr_content['updated_day']=$date2;
							}
							$ser_content = utf8_serialize($arr_content);
							$sql = "UPDATE house_extensions SET content='".db_real_escape_string($ser_content)."' WHERE id=".$arr_ext['id'];
							db_query($sql);
						}



						$str_output .= "Bewässerung: ".$arr_content['water']."%`n";

						//modifizierte Grafbar
						$col='#00FF00';
						$col2='#000000';
						if ($arr_content['water']<=0)
						{ //alles zu spät
							$col='#000000';
						}
						else if ($arr_content['water']<10)
						{ //fast ausgetrocknet
							$col='#FF0000';
						}
						else if ($arr_content['water']<$water_low_dmg)
						{ //recht trocken
							$col='#FFFF00';
						}
						else if ($arr_content['water']<$water_good_low)
						{ //geht so
							$col='#00AA00';
						}
						else if ($arr_content['water']<=$water_good_high)
						{ //gut
							$col='#00FF00';
						}
						else if ($arr_content['water']<$water_high_dmg)
						{ //geht so
							$col='#00AA00';
						}
						else if ($arr_content['water']<150)
						{ //Sumpf
							$col='#000088';
						}
						else
						{ //dürfte nicht auftreten
							$col='#0000FF';
							$col2='#0000FF';
						}
						$str_output.= '`0<table cellspacing="0" style="border: solid 1px #000000; height: 5px;" width="150"><tr><td width="' . round($arr_content['water'] / 150 * 100) . '%" style="background-color:'.$col.'" height="3"></td><td height="3" width="'. round(100-($arr_content['water'] / 150 * 100)) .'%" style="background-color:'.$col2.'"></td></tr></table>';

						addnav("Garten");
						addnav("Etwas pflanzen",$str_base_file."&op=plant");

						//$str_confirm = "Das ganze Beet zu gießen und zu düngen würde dich ".$int_water_price." Goldmünzen kosten.\\nMöchtest du fortfahren?";
						addnav("Gießen&amp;Düngen (".$int_water_price." Gold)",$str_base_file."&act=water",false,false,false,true,'');
						if($arr_house['owner'] == $session['user']['acctid'])
						{
							addnav('Verwaltung',$str_base_file.'&act=manage');
						}
					}
					else
					{
						addnav("Zum Garten",$str_base_file);
					}
					addnav("Zurück");
					addnav("Zum Haus","inside_houses.php");



					// Der Chat
					$str_output .= "`n`n";
					output ($str_title.write_message().$str_output);
					$bool_write_output=false;
					$comment_length=max($arr_ext['c_max_length'],getsetting('chat_post_len',600));
					viewcommentary('h_garden-'.$arr_ext['houseid'],'Rufen:',30,'ruft',false,true,false,$comment_length,true);
					break;
				}
			}
			// END switch act

			if($bool_write_output == true)
			{
				output ($str_title.write_message().$str_output);
			}

			break;
		}

		// Abriss
		case 'rip':
		case 'rip_auto':
		{

			// Alle Pflanzen löschen
			db_query('DELETE FROM crops WHERE garden='.(int)$arr_ext['id']);

			break;
		}
	}
}

function write_message()
{
	return getStatusMessage();
}

//Löcher beseitigen, die durch fremdes Löschen von Pflanzen entstanden sind (by Fingolfin, als Funktion by Salator)
//$bed_h und $bed_v sollten am besten global sein
function repair_garden($ext_id,$verbose=false,$bed_h=6,$bed_v=4)
{
	$sql = 'SELECT id, content FROM house_extensions WHERE type="garden" AND id='.$ext_id;
	$result = db_query($sql);
	$int_gardens = db_num_rows($result);
	if($int_gardens!=1) return (false);

	$success = false;
	$str_output='';

	$arr_house_garden = db_fetch_assoc($result);
	$arr_garden_content = utf8_unserialize($arr_house_garden['content']);

	for($k=1; $k<=($bed_h*$bed_v); $k++)
	{
		$arr_garden_content['occupied'][$k] = 0;
		$arr_garden_content[$k] = 0;
	}

	$sql = 'SELECT c.id, c.position AS pos, c.stage AS stage, t.size AS size FROM crops c, crops_tpl t WHERE c.garden='.$arr_house_garden['id'].' AND c.sort=t.id ORDER BY c.position ASC';
	$result2 = db_query($sql);
	$int_crops = db_num_rows($result2);

	$str_output.='`0Extension: `^'.$arr_house_garden['id'].'`n';
	$str_output.='`0- Count Crops: `0'.$int_crops.'`n';

	for($crop=0; $crop<$int_crops; $crop++)
	{
		$arr_garden_crop = db_fetch_assoc($result2);
		$int_pos = $arr_garden_crop['pos'];
		$arr_garden_content[$int_pos] = $arr_garden_crop['id'];

		$str_output.='`0- Crop: `q'.$arr_garden_crop['id'].'`n';

		if($int_pos>0)
		{
			$size = utf8_unserialize($arr_garden_crop['size']);
			strtok($size[$arr_garden_crop['stage']], ':');
			$size_x = strtok('x');
			$size_y = strtok('');

			for ($y=1; $y<=$size_y; $y++)
			{
				for ($x=1; $x<=$size_x; $x++)
				{
					$occ = $int_pos + ($y-1)*$bed_h + ($x-1);
					$arr_garden_content['occupied'][$occ] = $arr_garden_crop['id'];
					$str_output.='`0--- Field occupied: `f'.$occ.'`n';
				}
			}
		}
		$str_output.='`0--- `@Refreshed`n';

		$success = true;
	}

	if($success == true)
	{
		$ser_content = utf8_serialize($arr_garden_content);
		$sql = 'UPDATE house_extensions SET content="'.db_real_escape_string($ser_content).'" WHERE id='.$arr_house_garden['id'];
		db_query($sql);

		$str_output.='`@Successful`nDB Update Successful`n`n';
	}
	else
	{
	$str_output.='`$Failed / No Crops`n`n';
	}

	if($success == true)
	{
		$str_output.='`n`n`b`@Successful`0';
	}

	if($verbose)
	{
		output($str_output);
	}
	return($arr_garden_content);
}

?>
