<?php
// Schmiede V0.1
// by Maris (Maraxxus@gmx.de)


function house_extension_smithy ($str_case, $arr_ext, $arr_house) {
	
	global $session;
	
	$str_base_file = 'house_extensions.php?_ext_id='.$arr_ext['id'];
	
	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);
		
	switch($str_case) {
		
		// In der Schmiede
		case 'in':

			$act = $_GET['act'];
			switch ($act)
			{
			case 'deposit':
			    if (!$_GET['item'])
			    {
			        output("`n<table border='0'><tr><td>`2`bWähle eine Waffe, die du auf die Werkbank legen möchtest:`b</td></tr><tr><td valign='top'>");
							$sql = 'SELECT i.id,i.name,i.value1 FROM items i LEFT JOIN items_tpl t USING(tpl_id) LEFT JOIN items_classes c on t.tpl_class=c.id WHERE owner='.$session['user']['acctid'].' AND c.class_name="Waffen" ORDER BY i.value1, i.id ASC';
							$result = db_query($sql);
							$amount=db_num_rows($result);
							if (!$amount) output("`iVielleicht solltest du dir erstmal eine Waffe zulegen.`i");
							for ($i=1;$i<=$amount;$i++){
								$item_s = db_fetch_assoc($result);
								output("<a href=$str_base_file&act=deposit&item=".$item_s['id'].">`&-".$item_s['name']."</a>`0`n");
								addnav("","$str_base_file&act=deposit&item=".$item_s['id']);
							}
							output('</td></tr></table>');
							addnav('Zurück',$str_base_file);
			    }
			    else
			    {
			        $m=1;
			        if (is_array($arr_content))
			        {
			            //zählen...
			            foreach ($arr_content as $key => $val)
			            {
			                $m++;
			            }
			            //leere Position füllen
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
			        if ($used>$total_space)
			        {
			            output("`2Die Werkbank ist total überladen, da passt absolut nichts mehr drauf!`nDamit du deine Waffe hier bearbeiten kannst muss entweder eine andere fort oder die Schmiede muss vergrößert werden.`n`n");
			        }
			        else
			        {
			            // Waffe ablegen
			            $sql = 'SELECT * from items WHERE id='.$_GET['item'];
			            $result = db_query($sql);
			            $t_weapon = db_fetch_assoc($result);
			            
			            if ($t_weapon['deposit1']==9999999)
			            {
			              $w_old = item_set_weapon();
			            }
			            item_delete(' id='.$t_weapon['id']);
			
			            // Konvertierung
			            $d_weapon[$m]['tpl_id']=$t_weapon['tpl_id'];
			            $d_weapon[$m]['tpl_name']=db_real_escape_string(stripslashes($t_weapon['name']));
			            $d_weapon[$m]['tpl_description']=$t_weapon['description'];
			            $d_weapon[$m]['tpl_value1']=$t_weapon['value1'];
			            $d_weapon[$m]['tpl_value2']=$t_weapon['value2'];
			            $d_weapon[$m]['tpl_hvalue']=$t_weapon['hvalue'];
			            $d_weapon[$m]['tpl_hvalue2']=$t_weapon['hvalue2'];
			            $d_weapon[$m]['tpl_gold']=$t_weapon['gold'];
			            $d_weapon[$m]['tpl_gems']=$t_weapon['gems'];
			            $d_weapon[$m]['tpl_weight']=$t_weapon['weight'];
			            $d_weapon[$m]['deposit1']=0;
			            $d_weapon[$m]['deposit2']=0;
			            $d_weapon[$m]['tpl_special_info']=db_real_escape_string(stripslashes($t_weapon['special_info']));
			            
			            // Datumstempel
			            $d_weapon[$m]['deposit_date']=getsetting('gamedate','0005-01-01');
			            // Besitzer
			            $d_weapon[$m]['ownername']=$session['user']['login'];
			            $d_weapon[$m]['ownerid']=$session['user']['acctid'];
			            $s_weapon=utf8_serialize($d_weapon);
			
			            $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_weapon)."' WHERE id=".$arr_ext['id'];
			            db_query($sql);
			            output("`2Du legst {$d_weapon[$m]['weaponname']}`2 auf die Werkbank und kannst mit der Arbeit loslegen.`n");
			        }
			        addnav("Zurück",$str_base_file);
			    }
			    break;
			
			case 'takeout':
			    $position=$_GET['position'];
			    $action=$_GET['action'];
			
			    switch ($action)
			    {
			
			    case 'take':
			        if (is_array($arr_content[$position]))
			        {
			            unset($arr_content[$position]['deposit_date']);
			            unset($arr_content[$position]['ownername']);
			            unset($arr_content[$position]['ownerid']);
			            item_add($session['user']['acctid'],0,$arr_content[$position],false);
			            unset($arr_content[$position]);
			            $d_weapon=utf8_serialize($arr_content);
			            $sql = "UPDATE house_extensions SET content='".db_real_escape_string($d_weapon)."' WHERE id=".$arr_ext['id'];
			            db_query($sql);
			            output("`2Du nimmst deine Waffe wieder an dich!");
			        }
			        else
			        {
			            output("`2Hoppla!`nGerade als du das Ding mitnehmen willst, stellst du fest, dass es gar nicht mehr da ist!`Scheinbar ist dir jemand zuvorgekommen.`n`n");
			        }
			        addnav("Zurück",$str_base_file);
			        break;
			
			
			    case 'remove':
			        $position=$_GET['position'];
			        if ($_GET['confirm'])
			        {
			            if ($session['user']['turns']>0)
			            {
			                if (is_array($arr_content[$position]))
			                {
			                    $valuegold=$_GET['valuegold'];
			
			                    // Neues Rohmetall zum Lager
			                    
			                    $session['user']['turns']--;
			                    output("`2Du greifst dir {$arr_content[$position]['weaponname']}`2 und schmeisst das nutzlose Ding in die Esse.`nDu verlierst einen Waldkampf.`n`n");
			                    if ($session['user']['acctid']!=$arr_content[$position]['ownerid'])
			                    {
			                        output("`2Vielleicht hättest du den Besitzer erst fragen sollen?`n`n");
			                        insertcommentary($session['user']['acctid'],'/me `&wurde beobachtet wie '.($session['user']['sex'] ? 'sie ' : 'er ').$arr_content[$position]['weaponname'].'`& von`^ '.$arr_content[$position]['ownername'].' `&in die Esse geworfen hat!','smithy-'.$arr_ext['houseid']);
			                    }
			                    unset($arr_content[$position]);
			                    $d_weapon=utf8_serialize($arr_content);
			                    $sql = "UPDATE house_extensions SET content='".db_real_escape_string($d_weapon)."' WHERE id=".$arr_ext['id'];
			                    db_query($sql);
			                }
			                else
			                {
			                    output("`2Du willst gerade das Ding von der Werkbank nehmen, musst jedoch feststellen, dass es nicht mehr dort liegt - seltsam.`n`n");
			                }
			            }
			            else
			            {
			                output("`2Du bist leider schon zu müde um noch etwas einzuschmelzen!`n");
			            }
			        }
			        else
			        {
			            if (is_array($arr_content[$position]))
			            {
			                if ($session['user']['acctid']==$arr_content[$position]['ownerid'])
			                {
			                    output("`2Willst du dein {$arr_content[$position]['weaponname']}
			                    `2 wirklich einschmelzen?`nDu würdest eine Ladung Rohstahl zurück bekommen.");
			                }
			                else
			                {
			                    output($arr_content[$position]['weaponname']."`2 von ".$arr_content[$position]['ownername']."`2 liegt nun auch schon eine halbe Ewigkeit hier rum!`nOb die Arbeit daran jemals fertig wird?`nNaja, einschmelzen kannst du es sehr wohl, und aus dem Rohstahl könntest du dann selbst etwas ordentliches schmieden.`n");
			                }
			                addnav("Aktionen");
			                addnav("Einschmelzen","$str_base_file&act=takeout&action=remove&position=$position&confirm=1");
			            }
			            else
			            {
			                output("`2Als du dir dieses Ding näher ansehen willst musst du feststellen, dass es spurlos verschwunden ist`n`n");
			            }
			        }
			        addnav("Zurück");
			        addnav("Zur Schmiede",$str_base_file);
			        break;
			
			        default:
			        if (is_array($arr_content[$position]))
			        {
			            if ($arr_content[$position]['deposit_date']!=getsetting('gamedate','0005-01-01'))
			            {
			                $arr_content[$position]['deposit_date']=getsetting('gamedate','0005-01-01');
			                $s_content=utf8_serialize($arr_content);
			                $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_content)."' WHERE id=".$arr_ext['id'];
			                db_query($sql);
			
			            }
			            output("Du betrachtest den Gegenstand näher.`n`n");
			            if ($arr_content[$position]['ownerid']==$session['user']['acctid'])
			            {
			                output("`2Er gehört dir.`nWas soll damit geschehen?`n`n");
			                addnav("Aktionen");
			                addnav("Mitnehmen","$str_base_file&act=takeout&position=$position&action=take");
			            }
			            else
			            {
			                output("`2Das gehört nicht dir!`nDu kannst es nicht mitnehmen!");
			                if ($session['user']['house']==$session['housekey'])
			                {
			                    addnav("Besondere Aktionen");
			                }
			            }
			            if ($session['user']['house']==$session['housekey'] || $arr_content[$position]['ownerid']==$session['user']['acctid'])
			            {
			                addnav("`4Einschmelzen`0","$str_base_file&act=takeout&position=$position&action=remove");
			            }
			            addnav("Liegen lassen");
			            addnav("Zurück",$str_base_file);
			        }
			        else
			        {
			            output("`2Der Gegenstand wurde bereits von der Werkbank genommen, bevor du ihn dir näher ansehen konntest.");
			            addnav("Zurück",$str_base_file);
			        }
			        break;
			    }
			    break;
			
			    default:
			    $total_space=$arr_ext['level']*2+2;
			    $level=$arr_ext['level']-1;
			    output("`2Du befindest dich nun in der hauseigenen Waffenschmiede.`nDiese Schmiede hat die Ausbaustufe $level. Es können dort insgesamt $total_space Waffen zur gleichen Zeit bearbeitet oder angefertigt werden.`n`n");
			    $c=0;
			    if (is_array($arr_content))
			    {
			        foreach ($arr_content as $key => $val)
			        {
			            $c++;
			        }
			    }
			
			    if ($c>0)
			    {
			        output("`2Als du dich umschaust entdeckst du folgendes auf der Werkbank:`n(Klicke einen Gegenstand an um ihn näher zu betrachten)`n`n");
			        $i=1;
			
			        foreach ($arr_content as $key => $val)
			        {
			                output("`0<a href='$str_base_file&act=takeout&position=$key'>$val[tpl_name]</a>`2 von `&".$val['ownername']."`2`n",true);
			                addnav("","$str_base_file&act=takeout&position=$key");
			            $i++;
			        }
			    }
			    else
			    {
			        output("`2Auf der Werkbank ist nichts außer Staub! Hier sollte mal wieder geputzt werden!`n`n");
			    }
			    $free_space=$arr_ext['level']*2+2-$c;
			    output("`n`2Freie Plätze auf der Werkbank: ".$free_space."`n");
			    addnav("Werkbank");
			    addnav("Waffe ablegen","$str_base_file&act=deposit");
			    addnav("Zurück");
			    addnav("Zum Haus","inside_houses.php");
			
			    // Der Chat
			    output("`n`n");
			    $comment_length=max($arr_ext['c_max_length'],getsetting('chat_post_len',600));
			    viewcommentary('smithy-'.$arr_ext['houseid'],'Rufen:',30,'ruft',false,true,false,$comment_length,true);
			    break;
			}	// END switch act
			break;
			// END case in
		
		// Bau fertig
		case 'build_finished':
									
			// Wenn Schmied: Level 3
			if($arr_ext['level'] < 3) {
				$playerjob = user_get_aei('job');
				$job = $playerjob['job'];
				if(2 == $job) {
					db_query('UPDATE house_extensions SET level=3 WHERE id='.$arr_ext['id']);
					
					global $str_out;
					
					$str_out .= 'Durch deine Erfahrung als Schmied kannst du die Schmiede gleich viel effektiver nutzen - du startest auf Stufe 3!`n';
				}
			}
			
		break;
		
		// Abreißen
		case 'rip':
			
			global $str_out;
			
			if(sizeof($arr_content) > 0) {
				
				$str_out .= 'Du willst doch nicht dein tolles zeuch in der schmiede einfach so wechwerfen?! mach mal die glotzen uff!';
				output($str_out);
				addnav('Zur Schmiede!',$str_base_file);
				page_footer();
			}
									
		break;
			
	}	// END Main switch		
}	


?>
