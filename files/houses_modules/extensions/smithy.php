<?php
// Schmiede V0.1
// by Maris (Maraxxus@gmx.de)

function house_ext_smithy ($str_case, $arr_ext, $arr_house) {
	
	global $session;
	
	$str_base_file = 'house_extensions.php?_ext_id='.$arr_ext['id'];
	
	// Inhaltsarray erstellen
	$arr_content = array();
	$arr_content = utf8_unserialize($arr_ext['content']);
		
	switch($str_case) {
		
		// In der Schmiede
		case 'in':

            // Definition: 3 Einheiten Stahl für eine neue Waffe
            $req_metal = 3;
            
            // Erfolgschance fürs Schmieden
            $chance_smith = 75;
            
            if (!$arr_content['metal']) $arr_content['metal']=0;
            if (!$arr_content['fine_m']) $arr_content['fine_m']=0;
            
			$act = $_GET['act'];
			
			switch ($act)
			{

            case 'create':
            if (!$_GET['confirm'])
            {
                output("`2Hier kannst du eine neue Waffe schmieden.`n");
                if ($arr_content['metal']<$req_metal)
                {
                    output("`n`4Du benötigst {$req_metal} Einheiten Stahl um eine neue Waffe zu schmieden, hast aber nur $arr_content[metal] Einheiten in deiner Schmiede!`0`n`n");
                }
                else
                {
                    output("`n`2Das Schmieden einer neuen Waffe benötigt {$req_metal} Einheiten Stahl.`nWillst du daraus einen Rohling formen?");
                    addnav("Ja!","$str_base_file&act=create&confirm=1");
                    addnav("Nein");
                }
            }
            else
            {
                $m=1;
                $used=0;
                $total_space=$arr_content['level']*2+2;
                if (is_array($arr_content))
                {
                    //Index ermitteln
                    $find=$total_space;
                    //Leere Stelle suchen
                    for ($j=1; $j<=$total_space; $j++)
                    {
                        if (!$arr_content[$j]['tpl_name'])
                        {
                            $find=$j;
                            $j=$total_space;
                        }
                    }
                    $m=$find;
                }
                //Auf Überfüllung prüfen
                for ($j=1; $j<=$total_space; $j++)
                {
                    if ($arr_content[$j]['tpl_name'])
                    {
                        $used++;
                    }
                }
                //output("used: ".$used."`n");
                if ($used>=$total_space)
                {
                    output("`2Und wo soll der Rohling hin?`nIst dir mal aufgefallen, dass auf der Werkbank kein Platz mehr ist?`n`n");
                }
                else
                {
                    if ($arr_content['metal']>=3)
                    {
                        $position=$m;
                        output("`2Alles klar!`nDu hast einen Rohling geformt!`n");
                        // Erstellung
                        $arr_content[$position]['tpl_id']='';
                        $arr_content[$position]['tpl_name']='Unfertiger Rohling';
                        $arr_content[$position]['tpl_description']='';
                        $arr_content[$position]['tpl_value1']=0;
                        $arr_content[$position]['tpl_value2']=0;
                        $arr_content[$position]['tpl_hvalue']=1;
                        $arr_content[$position]['tpl_hvalue2']=0;
                        $arr_content[$position]['tpl_gold']=1;
                        $arr_content[$position]['tpl_gems']=0;
                        $arr_content[$position]['tpl_weight']=0;
                        $arr_content[$position]['deposit1']=0;
                        $arr_content[$position]['deposit2']=0;
                        $arr_content[$position]['tpl_special_info']='';
                        // Besitzer
                        $arr_content[$position]['ownername']=$session['user']['login'];
                        $arr_content[$position]['ownerid']=$session['user']['acctid'];
                        // Material
                        $arr_content['metal']-=3;
                        $s_weapon=utf8_serialize($arr_content);
                        $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_weapon)."' WHERE id=".$arr_ext['id'];
                        db_query($sql);
                    }
                    else
                    {
                        output("`4Und woraus willst du den Rohling formen?`nIst dir mal aufgefallen, dass du gar nicht genug Rohstahl dafür hast?`0`n");
                    }
                }
            }
            addnav("Zurück","$str_base_file");
            break;

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
			                if ($key!="metal" && $key!="fine_m") $m++;
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
			        if ($used>=$total_space)
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
                        $d_weapon=$arr_content;
                        
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
			        
			        case 'forge':
                        $position=$_GET['position'];
                        if ($_GET['confirm'])
                        {
                            if ($arr_content[$position]['forge_date']==getsetting('gamedate','0005-01-01'))
                            {
                                output("`2An diesem Rohling wurde heute bereits gearbeitet!`nLass ihn erstmal abkühlen!");
                            }
                            else
                            {
                                if ($arr_content[$position]['tpl_hvalue2']>=20)
                                {
                                    output("`2Bei allem Talent - diesen Rohling kannst du nicht mehr weiter verarbeiten!`nDu musst ihn fertig stellen.`n");
                                }
                                else
                                {
                                    if ($session['user']['turns']>0)
                                    {
                                        $chance = e_rand(1,100);
                                        $playerjob = user_get_aei('job');
                                        if ($playerjob['job']==2)
                                        {
                                            $chance_smith+=10;
                                        }
                                        
                                        if ($chance<=$chance_smith)
                                        {
                                            output("`2Du schmiedest den Rohling weiter.`nSeine Stufe erhöht sich um 1.`n`n");
                                            $arr_content[$position]['tpl_hvalue2']++;
                                            $arr_content[$position]['forge_date']=getsetting('gamedate','0005-01-01');
                                            $session['user']['turns']--;
                                        }
                                        else
                                        {
                                            output("`4Mit deiner Grobschlächtigkeit hast du soeben den Rohling zerstört!`nDer ist höchstens noch als Elfenkunst verwertbar!`0");
                                            $arr_content[$position]['tpl_hvalue2']=0;
                                            $session['user']['turns']--;
                                            $arr_content[$position]['tpl_id']='elfknst';
                                            $arr_content[$position]['tpl_name']='Verdorbener Rohling';
                                            $arr_content[$position]['tpl_gold']=e_rand(1,600);
                                        }
                                        
                                        $s_content=utf8_serialize($arr_content);
                                        $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_content)."' WHERE id=".$arr_ext['id'];
                                        db_query($sql);
                                    }
                                    else
                                    {
                                        output("`2Du bist zu müde um heute noch dem Schmiedehandwerk nachzugehen.`n");
                                    }
                                }
                            }
                        }
                        else
                        {
                            if (is_array($arr_content[$position]))
                            {
                                output("`2Dein Rohling hat derzeit die Stufe {$arr_content[$position]['tpl_hvalue2']}.`nDu kannst weiter an deinem Rohling arbeiten, riskierst ab ihn dabei unbrauchbar zu machen!`n`n");
                                addnav("Weitermachen?");
                                addnav("Ja","$str_base_file&act=takeout&action=forge&position=$position&confirm=1");
                            }
                            else
                            {
                                output("`2Der Rohling, den du bearbeiten möchtest, ist irgendwie... verschwunden...");
                            }
                        }
                        addnav("Zurück");
                        addnav("Zur Schmiede","$str_base_file");
                    break;
			
                    case 'finish':
                        $position=$_GET['position'];
                        if ($_GET['confirm'])
                        {
                            if ($arr_content[$position]['forge_date']==getsetting('gamedate','0005-01-01'))
                            {
                                output("`2An diesem Rohling wurde heute bereits gearbeitet!`nLass ihn erstmal abkühlen!");
                            }
                            else
                            {
                                if ($arr_content[$position]['tpl_hvalue2']==0)
                                {
                                    output("`2Die Waffe, die du erstellen willst, würde 0 Schaden verursachen!`nWillst du deine Gegner damit zu Tode kitzeln ?`n");
                                }
                                else
                                {
                                    if ($session['user']['turns']>0)
                                    {

                                        if (!($_POST['name'] || $_GET['name']))
                                        {
                                            output("`&Benenne deine Waffe: ");
                                            output("<form action='$str_base_file&act=takeout&action=finish&position=".$position."&confirm=1' method='POST'><input name='name' size=\"30\" maxlength=\"50\"> <input type='submit' value='Vorschau'></form>");
                                            addnav("","$str_base_file&act=takeout&action=finish&position=".$position."&confirm=1");
                                        }
                                        else
                                        {
                                            if (!$_POST['name']) $_POST['name']=urldecode($_GET['name']);
                                            $_POST['name']=str_replace("`0","",$_POST['name']);
                                            $_POST['name'] = stripslashes(utf8_preg_replace('/[`][^'.regex_appoencode(1,false).']/','',$_POST['name']));
                                            if (!$_GET['doit'])
                                            {
                                                output("`&Deine Waffe heißt ".stripslashes($_POST['name'])."`&. Ist das in Ordnung?");
                                                addnav("Ja!","$str_base_file&act=takeout&action=finish&position=".$position."&confirm=1&name=".urlencode($_POST['name'])."&doit=1");
                                                addnav("Nein");
                                                addnav("Nochmal versuchen","$str_base_file&act=takeout&action=finish&position=".$position."&confirm=1");
                                            }
                                            else
                                            {
                                                output("`2Du schmiedest den Rohling fertig und hast nun eine selbstgemacht Waffe.`n");
                                                $session['user']['turns']--;
                                                $arr_content[$position]['tpl_id']='waffedummy';
                                                $arr_content[$position]['tpl_name']=urldecode($_GET['name']);
                                                $arr_content[$position]['tpl_description']='Eine Waffe geschmiedet von '.$session['user']['name'];
                                                $arr_content[$position]['tpl_value1']=$arr_content[$position]['tpl_hvalue2'];
                                                $arr_content[$position]['tpl_gold']=$arr_content[$position]['tpl_value2']*500;
                                                $arr_content[$position]['tpl_value2']=0;
                                                $arr_content[$position]['tpl_hvalue']=0;
                                                $arr_content[$position]['tpl_hvalue2']=0;
                                                $s_content=utf8_serialize($arr_content);
                                                $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_content)."' WHERE id=".$arr_ext['id'];
                                                db_query($sql);
                                            }
                                        }
                                    }
                                    else
                                    {
                                        output("`2Du bist zu müde um heute noch dem Schmiedehandwerk nachzugehen.`n");
                                    }
                                }
                            }
                        }
                        else
                        {
                            if (is_array($arr_content[$position]))
                            {
                                output("`2Dein Rohling hat derzeit die Stufe {$arr_content[$position]['tpl_hvalue2']}.`nWenn du ihn nun fertigstellst wird die Waffe, die du erhälst, {$arr_content[$position]['tpl_hvalue2']} Schaden verursachen. Bist du dir sicher?`n`n");
                                addnav("Weitermachen?");
                                addnav("Ja","$str_base_file&act=takeout&action=finish&position=$position&confirm=1");
                            }
                            else
                            {
                                output("`2Der Rohling, den du bearbeiten möchtest, ist irgendwie... verschwunden...");
                            }
                        }
                        addnav("Zurück");
                        addnav("Zur Schmiede","$str_base_file");
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
                            if ($arr_content[$position]['tpl_name']=='Unfertiger Rohling')
                            {
                                output("`^Das ist ein unfertiger Rohling.`nDu kannst ihn so noch nicht mitnehmen.`n`nStelle ihn entweder fertig oder verarbeite ihn weiter.`n`n");
                                addnav("Rohling");
                                addnav("Verarbeiten","$str_base_file&act=takeout&action=forge&position=$position");
                                addnav("Fertig stellen","$str_base_file&act=takeout&action=finish&position=$position");
                            }
                            else
                            {
                                output("`2Er gehört dir.`nWas soll damit geschehen?`n`n");
                                addnav("Aktionen");
                                addnav("Mitnehmen","$str_base_file&act=takeout&position=$position&action=take");
                            }
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
			    
			    case 'store':
                    $op = $_GET['op'];
                    if ($op=="deposit")
                    {
                        if (!$_GET['item'])
                        {
                            output("`n<table border='0'><tr><td>`2`bDu hast folgende Rohstoffe in deinem Inventar:`b</td></tr><tr><td valign='top'>");
                            $sql = 'SELECT i.id,i.name,i.value1 FROM items i LEFT JOIN items_tpl t USING(tpl_id) LEFT JOIN items_classes c on t.tpl_class=c.id WHERE owner='.$session['user']['acctid'].' AND c.class_name="Rohstoffe" ORDER BY i.value1, i.id ASC';
                            $result = db_query($sql);
                            $amount=db_num_rows($result);
                            if (!$amount) output("`iNaja, du hast nicht wirklich welche.`i");
                            for ($i=1;$i<=$amount;$i++){
                                $item_s = db_fetch_assoc($result);
                                output("<a href=$str_base_file&act=store&op=deposit&item=".$item_s['id'].">`&-".$item_s['name']."</a>`0`n");
				                addnav("","$str_base_file&act=store&op=deposit&item=".$item_s['id']);
				            }
                            output('</td></tr></table>');
                        }
                        else
                        {
                            $sql = 'SELECT * from items WHERE id='.$_GET['item'];
                            $result = db_query($sql);
                            $r_item = db_fetch_assoc($result);
                            if ($r_item['tpl_id']=="rohstahl")
                            {
                                $arr_content['metal']++;
                                $s_content=utf8_serialize($arr_content);
                                $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_content)."' WHERE id=".$arr_ext['id'];
                                db_query($sql);
                                item_delete(' id='.$r_item['id']);
                                output("`2Du deponierst den Rohstahl in der Schmiede.");
                            }
                            else if ($r_item['tpl_id']=="erz")
                            {
                                $arr_content['metal']+=0.5;
                                $s_content=utf8_serialize($arr_content);
                                $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_content)."' WHERE id=".$arr_ext['id'];
                                db_query($sql);
                                item_delete(' id='.$r_item['id']);
                                output("`2Du gewinnst aus dem Erzklumpen eine halbe Einheit Rohstahl.");
                            }
                            else if ($r_item['tpl_id']=="nuggetbig")
                            {
                                $arr_content['fine_m']++;
                                $s_content=utf8_serialize($arr_content);
                                $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_content)."' WHERE id=".$arr_ext['id'];
                                db_query($sql);
                                item_delete(' id='.$r_item['id']);
                                output("`2Du schmelzt das Gold ein und gewinnst eine Einheit Edelmetalle.");
                            }
                            else if ($r_item['tpl_id']=="nugget")
                            {
                                $arr_content['fine_m']+=0.5;
                                $s_content=utf8_serialize($arr_content);
                                $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_content)."' WHERE id=".$arr_ext['id'];
                                db_query($sql);
                                item_delete(' id='.$r_item['id']);
                                output("`2Du schmelzt das Gold ein und gewinnst eine halbe Einheit Edelmetalle.");
                            }
                            else
                            {
                                output("`2Das eignet sich nicht wirklich um eine Waffe damit zu schmieden.");
                            }
                        }
                    }
                    else if ($op=="take")
                    {
                        if (!$_GET['what'])
                        {
                            output("`2In der Schmiede befinden sich $arr_content[metal] Einheiten Rohstahl und $arr_content[fine_m] Einheiten Edelmetalle.`nWovon möchtest du eine Einheit mitnehmen?");
                            addnav("Mitnehmen");
                            if ($arr_content['metal']>0) addnav("Rohstahl","$str_base_file&act=store&op=take&what=steel");
                            if ($arr_content['fine_m']>0) addnav("Edelmetalle","$str_base_file&act=store&op=take&what=fine");
                            addnav("Nichts");
                        }
                        else
                        {
                            if ($_GET['what']=="steel")
                            {
                                if ($arr_content['metal']<1)
                                {
                                    $nogo=1;
                                }
                                else
                                {
                                    $obj="e Einheit Rohstahl";
                                    $tpl="rohstahl";
                                    $arr_content['metal']--;
                                }
                            }
                            else if ($_GET['what']=="fine")
                            {
                                if ($arr_content['fine_m']<1)
                                {
                                    $nogo=1;
                                }
                                else
                                {
                                    $obj=" großes Goldnugget";
                                    $tpl="nuggetbig";
                                    $arr_content['fine_m']--;
                                }
                            }
                            if (!$nogo==1)
                            {
                                output("`2Du packst ein$obj in dein Inventar.`n");
                                $s_content=utf8_serialize($arr_content);
                                $sql = "UPDATE house_extensions SET content='".db_real_escape_string($s_content)."' WHERE id=".$arr_ext['id'];
                                db_query($sql);
                                item_add($session['user']['acctid'],$tpl);
                            }
                            else
                            {
                                output("`2Davon ist so wenig da, dass es sich irgendwie nicht lohnt etwas mitzunehmen!`n");
                            }
                        }
                    }
                    addnav("Zurück","$str_base_file");
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
			            if ($key!="metal" && $key!="fine_m") $c++;
			        }
			    }
			
			    if ($c>0)
			    {
			        output("`2Als du dich umschaust entdeckst du folgendes auf der Werkbank:`n(Klicke einen Gegenstand an um ihn näher zu betrachten)`n`n");
			        $i=1;
			
			        foreach ($arr_content as $key => $val)
			        {
                            if ($key!="metal" && $key!="fine_m")
                            {
                                if ($val['tpl_name']=="Unfertiger Rohling")
                                {
                                    output("`0<a href='$str_base_file&act=takeout&position=$key'>$val[tpl_name]</a>`2 (Stufe {$val['tpl_hvalue2']}) von `&".$val['ownername']."`2`n",true);
                                }
                                else output("`0<a href='$str_base_file&act=takeout&position=$key'>$val[tpl_name]</a>`2 von `&".$val['ownername']."`2`n",true);
			                     addnav("","$str_base_file&act=takeout&position=$key");
                                $i++;
                            }
			        }
			    }
			    else
			    {
			        output("`2Auf der Werkbank ist nichts außer Staub! Hier sollte mal wieder geputzt werden!`n`n");
			    }
			    $free_space=$arr_ext['level']*2+2-$c;
                output("`n`2Freie Plätze auf der Werkbank: ".$free_space."`n");
			    output("Stahl: ".$arr_content['metal']." ".($arr_content['metal']==1?"Einheit":"Einheiten").".`nEdelmetalle: ".$arr_content['fine_m']." ".($arr_content['fine_m']==1?"Einheit":"Einheiten").".");
                addnav("Werkbank");
                addnav("Waffe ablegen","$str_base_file&act=deposit");
                addnav("Schmiedeofen");
                addnav("Waffe schmieden","$str_base_file&act=create");
                addnav("Lager");
                addnav("Material lagern","$str_base_file&act=store&op=deposit");
                addnav("Material mitnehmen","$str_base_file&act=store&op=take");
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
								
						
		break;
		
		// Abreißen
		case 'rip':
			
			global $str_out;
			
			if(sizeof($arr_content) > 0) {
				
				$str_out .= 'In deiner Schmiede befinden sich noch Ausrüstungsgegenstände, die durch einen Abriss verlorengehen würden. Du solltest dich zunächst darum kümmern!';
				output($str_out);
				addnav('Zur Schmiede!',$str_base_file);
				page_footer();
			}
									
		break;
			
	}	// END Main switch		
}	


?>
