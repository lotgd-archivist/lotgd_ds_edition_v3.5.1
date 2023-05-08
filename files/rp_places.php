<?php
require_once 'common.php';
if(!isset($session)){echo('$session nicht definiert in '.$filename.'');exit();}

$show_invent = true;

//kosten

$costs['first_place'] = 200;
$costs['place'] = 1000;
$costs['anbau'] = 200;
$costs['edit'] = 5;

$free_anbauten = 3;
$max_places = 30;
$max_anbau = 200;

$max_positions = 30;

//kosten ende
$world = intval($_GET['world']);
$id = intval($_GET['id']);
$extended = '';
$raw = '';
$name = 'Fehler';
$footer = '';
$description = 'Nix zu sehen weitergehen!';
$navs = array('Verschwinden!' => 'dorftor.php');
$private = false;
$semiprivate = false;
$rp = false;




$rights = array('edit' => 'Editieren', 'build' => 'Anbauen', 'keys' => 'Schlüssel vergeben', 'positions' => 'Mitglieder-Ränge' , 'ranks' => 'Mitglieder', 'cleanup' => 'Alle Beiträge löschen (private Orte nur).', 'allrooms' =>  'Alle private Unterorte ohne Schlüssel betreten' );

$user_rights = array();
if($world > 0 && $id > 0)
{
    $owner = db_get("SELECT acctid FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1");

    if($Char->acctid == $owner['acctid'])//besitzer
    {
        $user_rights = array('edit' => 1,'build' => 1,'keys' => 1,'positions' => 1,'ranks' => 1,'cleanup' => 1, 'allrooms' => 1);
    }
    else
    {
        $info = db_get("SELECT p.* FROM rp_worlds_members AS m
                        JOIN rp_worlds_positions AS p
                        ON p.id=m.position
                        WHERE m.rportid='".CRPPlaces::parent(intval($id))."'
                        AND m.acctid='".intval($Char->acctid)."' LIMIT 1");

        if(isset($info['id']))
        {
            $user_rights = $info;
        }
    }
}

if($world > 0)
{
    if($_GET['do']=='del')
    {
        $navs = array();
        $description = '';

        $navs['Zurück'] = '';
        $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
        $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo['id'];
        $name = ' Ort entfernen';

        if(isset($_GET['confirm']))
        {
            if(isset($_POST['pass']))
            {
                if(CCrypt::verify_password_hash($_POST['pass'],$Char->password))
                {
                    CRPPlaces::delete($world, $id);
                    if($worldinfo['parent'] == 0)
                    {
                        redirect('rp_places.php?world='.$world);
                    }
                    else
                    {
                        redirect('rp_places.php?world='.$world.'&id='.$worldinfo['parent']);
                    }
                }
                else
                {
                     $description = '`4Passwort war nicht korrekt!';
                }
            }
        }
        else
        {
            $form_layout = array('Gib dein Passwort ein um diesen Ort zu entfernen (Keine DP Entschädigung!),title',
                    'pass'=>'Passwort,password'
            );
            $str_link = 'rp_places.php?do=del&world='.$world.'&id='.$id.'&do=del&confirm=1';
            $raw .= form_header($str_link).generateform($form_layout,$pos).form_footer();
        }
    }
    else if($_GET['do']=='memberlist')
    {
        $navs = array();
        $description = '';

        $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
        $navs['Zurück'] = '';
        $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
        $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo['id'];
        $name = $worldinfo['name'].' Mitgliederliste';


        $subowner = db_get_all("SELECT * FROM rp_worlds_members WHERE rportid='".intval($id)."' ORDER BY position ASC, id ASC ");

        $owner_user = db_get("SELECT * FROM accounts WHERE acctid = '".$worldinfo['acctid']."'  LIMIT 1");

        $description .= '`n`n
            <table border="0" cellspacing="1" cellpadding="3" width="100%">
            <tr class="trhead">
            <td>Mitglied</td>
            <td>Position</td>
            </tr>
            <tr class="trdark">
            <td>'.(isset($owner_user['name']) ? $owner_user['name'] : '`i`&Niemand`0`i').'</td>
            <td>'.(($worldinfo['rang'] != '') ? $worldinfo['rang'] : color_from_name('Besitzer',$worldinfo['name'] )).'</td>
            </tr>
            ';
        $i=0;
        foreach($subowner as $sub)
        {
            $array_user = db_get("SELECT * FROM accounts WHERE acctid = '".$sub['acctid']."'  LIMIT 1");
            $array_pos = db_get("SELECT * FROM rp_worlds_positions WHERE id = '".$sub['position']."'  LIMIT 1");
            $description .= '<tr class="'.( ($i%2) ? 'trdark' : 'trlight' ).'" style="text-align:left;"><td>'.CRPChat::menulink($array_user).'</td><td>'.$array_pos['name'].'</td></tr>';
            $i++;
        }
        $description .= '</table>`n`n';

    }
    else if($_GET['do']=='worker')
    {
        $navs = array();
        $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
        $navs['Zurück'] = '';
        $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
        $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo['id'];
        $name = $worldinfo['name'].' Mitglieder';

        $description = '';

        if(isset($_GET['sdo']))
        {
            if($user_rights['positions'] || $user_rights['ranks'])$navs['Mitglieder'] =  'rp_places.php?do=worker&world='.$world.'&id='.$id;

            if($user_rights['positions'] && $_GET['sdo']=='pos')
            {
                $pos = db_get("SELECT * FROM rp_worlds_positions WHERE rportid='".intval($id)."' AND id='".intval($_GET['posid'])."' LIMIT 1 ");

                if(isset($_GET['del']))
                {
                    db_query("DELETE FROM rp_worlds_positions WHERE id='".intval($pos['id'])."' AND rportid='".$id."' LIMIT 1");

                    redirect('rp_places.php?do=worker&world='.$world.'&id='.$id);
                }
                else if(isset($_GET['save']))
                {
                    $name = db_real_escape_string(mb_substr(strip_appoencode(stripslashes($_POST['name']),2),0,255));
                    $update = '';
                    $newK = '';
                    $newV = '';

                    foreach($rights as $k => $v)
                    {
                        $update .= ",`".$k."`='".intval($_POST[$k])."'";
                        $newK .= ", `".$k."` ";
                        $newV .= ", '".intval($_POST[$k])."'";
                    }

                     if(isset($pos['id']))
                     {
                         db_query("UPDATE rp_worlds_positions SET name='".$name."' ".$update." WHERE id='".intval($pos['id'])."' AND rportid='".$id."' LIMIT 1");
                     }
                    else
                    {
                        db_query("INSERT INTO rp_worlds_positions (id ,rportid ,name ".$newK.") VALUES (null ,'".$id."' ,'".$name."'  ".$newV.")");
                    }
                    redirect('rp_places.php?do=worker&world='.$world.'&id='.$id);
                }
                else
                {
                    $rechte = array();

                    foreach($rights as $k => $v)
                    {
                        $rechte[$k] = $v.',bool';
                    }

                    $form_layout = array_merge(array('Position,title',
                        '1' => 'Vorschau,preview,name',
                        'name'=>'Rang-Name,text,255',
                        '2'=>'Rechte,divider'),
                        $rechte
                    );
                    $str_link = 'rp_places.php?do=worker&world='.$world.'&id='.$id.'&sdo=pos&posid='.intval($pos['id']).'&save=1';
                    $raw .= form_header($str_link).generateform($form_layout,$pos).form_footer();
                }
            }

            if($user_rights['ranks'] && $_GET['sdo']=='rank')
            {
                $sub = db_get("SELECT * FROM rp_worlds_members WHERE rportid='".intval($id)."' AND id='".intval($_GET['rankid'])."' LIMIT 1");

                if(isset($_GET['del']))
                {

                    $array_user = db_get("SELECT acctid FROM rp_worlds_members WHERE id='".intval($sub['id'])."' AND rportid='".$id."' LIMIT 1");

                    $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
                    systemmail(intval($array_user['acctid']),"`4Deine Mitgliedschaft wurde gekündigt!`0","`&{$session['user']['name']}
							`t hat deine Mitgliedschaft beim RP-Ort {$worldinfo['name']}`t gekündigt!");

                    db_query("DELETE FROM rp_worlds_members WHERE id='".intval($sub['id'])."' AND rportid='".$id."' LIMIT 1");

                    redirect('rp_places.php?do=worker&world='.$world.'&id='.$id);
                }
                else if(isset($_GET['save']))
                {
                    $login = db_real_escape_string(strip_appoencode(stripslashes($_POST['name']),3));

                    $array_user = db_get("SELECT acctid FROM accounts WHERE login LIKE  '".$login."'  LIMIT 1");

                    if(isset($sub['id']) && isset($array_user['acctid']))
                    {
                        if($Char->acctid != $array_user['acctid']){
                            db_query("UPDATE rp_worlds_members SET acctid='".intval($array_user['acctid'])."' ,position='".intval($_POST['position'])."'  WHERE id='".intval($sub['id'])."' AND rportid='".$id."' LIMIT 1");
                        }
                    }
                    else if (isset($array_user['acctid']))
                    {
                        if($Char->acctid != $array_user['acctid'])
                        {
                            db_query("INSERT INTO rp_worlds_members (id ,rportid ,acctid ,position) VALUES (null ,'".$id."' ,'".intval($array_user['acctid'])."' ,'".intval($_POST['position'])."')");

                            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
                            systemmail(intval($array_user['acctid']),"`@Du wurdest aufgenommen!`0","`&{$session['user']['name']}
							`t hat dich im RP-Ort {$worldinfo['name']}`t als Mitglied aufgenommen!");
                        }


                    }
                    redirect('rp_places.php?do=worker&world='.$world.'&id='.$id);
                }
                else
                {
                    $posselect = '';

                    $positionen = db_get_all("SELECT id,name FROM rp_worlds_positions WHERE rportid='".intval($id)."' ORDER BY id ASC ");
                    foreach($positionen as $pos)
                    {
                        $posselect .= ','.$pos['id'].','.strip_appoencode($pos['name'],3);
                    }

                    if(isset($sub['acctid']))
                    {
                        $array_user = db_get("SELECT login FROM accounts WHERE acctid='".intval($sub['acctid'])."'  LIMIT 1");
                        $sub['name'] = $array_user['login'];
                    }

                    $form_layout = array('Position,title',
                            'name'=>'Login des Spielers,usersearch,login',
                            '2'=>'Position,divider',
                            'position' => 'Position,select'.$posselect
                    );

                    $str_link = 'rp_places.php?do=worker&world='.$world.'&id='.$id.'&sdo=rank&rankid='.intval($sub['id']).'&save=1';
                    $raw .= form_header($str_link).generateform($form_layout,$sub).form_footer();
                }
            }

        }
        else
        {
            $positionen = db_get_all("SELECT * FROM rp_worlds_positions WHERE rportid='".intval($id)."' ORDER BY id ASC ");

            if($user_rights['positions'])
            {

                $description .= '`n`n`@INFO: JA ihr dürf Multis eintragen, wenn diese für euch, zB im Laden, arbeiten.
                                 `n`4Eure Multis dürfen sich aber nicht am Ausbau beteiligen (Multiregelung).`0`n`nMitglieder-Ränge: (Nur Ränge mit 0 Mitglieder können gelöscht werden)`n`n
                                    <table border="0" cellspacing="1" cellpadding="3" width="100%">
                                    <tr class="trhead">
                                    <td>Name</td>
                                    <td>Rechte</td>
                                    <td>Edit</td>
                                    </tr>
                                    ';
                $i=0;

                foreach($positionen as $pos)
                {
                    $cnt_member = db_get("SELECT COUNT(*) AS cnt FROM rp_worlds_members WHERE position='".intval($pos['id'])."'");
                     $cnt_member = intval($cnt_member['cnt']);

                    $description .= '<tr class="'.( ($i%2) ? 'trdark' : 'trlight' ).'" style="text-align:left;">';

                    $description .= '<td>'.$pos['name'].' ('.$cnt_member.' Mitglied/er)</td><td>';

                    foreach($rights as $k => $v)
                    {
                        if($pos[$k]==1)$description .= $v.'`n';
                    }
                    $description = trim($description ).'</td><td>'
                        .create_lnk('Edit','rp_places.php?do=worker&world='.$world.'&id='.$id.'&sdo=pos&posid='.$pos['id'])
                        .( ($cnt_member==0) ? ' | '.create_lnk('Del','rp_places.php?do=worker&world='.$world.'&id='.$id.'&sdo=pos&del=1&posid='.$pos['id']) : '' )
                        .'</td></tr>';
                    $i++;
                }

                $description .= '</table>`n'.count($positionen).' von ' . $max_positions.' Rängen vergeben.`n'.( (count($positionen) > $max_positions) ? 'Maximum erreicht' : create_lnk('Hinzufügen','rp_places.php?do=worker&world='.$world.'&id='.$id.'&sdo=pos'));
            }


            if($user_rights['ranks'])
            {


                $subowner = db_get_all("SELECT * FROM rp_worlds_members WHERE rportid='".intval($id)."' ORDER BY id ASC ");

                $description .= '`n`nMitglieder:`n`n
            <table border="0" cellspacing="1" cellpadding="3" width="100%">
            <tr class="trhead">
            <td>Mitglied</td>
            <td>Position</td>
            <td>Edit</td>
            </tr>
            ';
                $i=0;

                foreach($subowner as $sub)
                {
                    $array_user = db_get("SELECT * FROM accounts WHERE acctid = '".$sub['acctid']."'  LIMIT 1");
                    $array_pos = db_get("SELECT * FROM rp_worlds_positions WHERE id = '".$sub['position']."'  LIMIT 1");

                    $description .= '<tr class="'.( ($i%2) ? 'trdark' : 'trlight' ).'" style="text-align:left;">';

                    $description .= '<td>'.CRPChat::menulink($array_user).'</td><td>';

                    $description .= $array_pos['name'].'</td><td>'.create_lnk('Edit','rp_places.php?do=worker&world='.$world.'&id='.$id.'&sdo=rank&rankid='.$sub['id']).' | '.create_lnk('Del','rp_places.php?do=worker&world='.$world.'&id='.$id.'&sdo=rank&del=1&rankid='.$sub['id']).'</td></tr>';
                    $i++;
                }
                $description .= '</table>`n'.( (count($positionen) > 0) ? create_lnk('Hinzufügen','rp_places.php?do=worker&world='.$world.'&id='.$id.'&sdo=rank') : 'Erst eine Position erstellen!');
            }

        }

    }


    else if($_GET['do']=='quit_key')
    {
        $navs = array();
        $isanb = ($id>0);
        $navs['Zurück'] = '';
        if($isanb)
        {
            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
            $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo['id'];
        }
        else
        {
            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds WHERE id='".$world."' LIMIT 1"));
            $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world;
        }
        $name = 'Schlüssel zurückgeben?';
        $description = 'Bist du absolut sicher, dass du deinen Schlüssel zurückgeben willst? Dies lässt sich nicht mehr rückgängig machen!';
        $navs['Aktion'] = '';
        $navs['Ja!'] =  'rp_places.php?do=quit_key2&world='.$world.'&id='.$id;
    }
    else if($_GET['do']=='quit_mit')
    {
        $navs = array();
        $isanb = ($id>0);
        $navs['Zurück'] = '';
        if($isanb)
        {
            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
            $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo['id'];
        }
        else
        {
            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds WHERE id='".$world."' LIMIT 1"));
            $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world;
        }
        $name = 'Mitgliedschaft beenden?';
        $description = 'Bist du absolut sicher, dass du deine Mitgliedschaft beenden willst? Dies lässt sich nicht mehr rückgängig machen!';
        $navs['Aktion'] = '';
        $navs['Ja!'] =  'rp_places.php?do=quit_mit2&world='.$world.'&id='.$id;
    }

    else if($_GET['do']=='quit_key2')
    {
        $navs = array();
        $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
        $m_key = db_get("SELECT id FROM rp_worlds_places_keys WHERE placeid='".intval($id)."' AND acctid = '".intval($Char->acctid)."' LIMIT 1");
        db_query("DELETE FROM rp_worlds_places_keys WHERE id='".$m_key['id']."' LIMIT 1");
        redirect('rp_places.php?world='.$world);
    }
    else if($_GET['do']=='quit_mit2')
    {
        $navs = array();
        $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
        $m_mit = db_get("SELECT id FROM rp_worlds_members WHERE rportid='".intval($id)."' AND acctid='".intval($Char->acctid)."' LIMIT 1");
        db_query("DELETE FROM rp_worlds_members WHERE id='".$m_mit['id']."' LIMIT 1");
        redirect('rp_places.php?world='.$world);
    }

    else if($_GET['do']=='massmail')
    {
        $navs = array();
        $isanb = ($id>0);
        $navs['Zurück'] = '';
        if($isanb)
        {
            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
            $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo['id'];
        }
        else
        {
            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds WHERE id='".$world."' LIMIT 1"));
            $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world;
        }
        $name = 'Massenmail';
        $description = '';
        $sql="SELECT a.acctid, a.name, a.login
            FROM rp_worlds_members AS m
            LEFT JOIN accounts AS a
            ON a.acctid=m.acctid
            WHERE m.rportid='".intval($id)."'";
        $result=db_query($sql);
        $users=array();
        $keys=0;
        $residents .= '<input type="checkbox" id="selecctall"/> Alle auswählen<br>';
        while($row=db_fetch_assoc($result))
        {
            $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" id="inp347834"> '.$row['name'].'
			'.JS::event('#inp34783756','click','chk();').'
			<br>';
            $keys++;

            if ($_POST['title']!='' && $_POST['maintext']!='' && in_array($row['acctid'],$_POST['msg']))
            {
                $users[]=$row['acctid'];
            }
        }

        $mailsends=count($users);
        $gemcost=0;
        if ($mailsends<=5)
        {
            $gemcost=1;
        }
        elseif ($mailsends<=15)
        {
            $gemcost=2;
        }
        elseif ($mailsends<=25)
        {
            $gemcost=3;
        }
        elseif ($mailsends>25)
        {
            $gemcost=4;
        }

        if ($session['user']['gems']>=$gemcost AND $mailsends>0)
        {
            foreach($users as $id)
            {
                systemmail($id, $_POST['title'], $_POST['maintext'], $session['user']['acctid']);
            }

            $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler haben eine Taube erhalten und deine Kosten betragen '.$gemcost.' Edelsteine.<br><br>';
            $session['user']['gems']-=$gemcost;
        }
        elseif ($session['user']['gems']<$gemcost AND $mailsends>0)
        {
            $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler hätten eine Taube erhalten, wenn deine Kosten nicht '.$gemcost.' Edelsteine betragen würden. Leider kannst du dies nicht bezahlen.<br><br>';
        }

        if ($keys>0)
        {
            $str_out .= form_header('rp_places.php?do=massmail&world='.$world.'&id='.$id)
                .$sendresult.'
			<table border="0" cellpadding="0" cellspacing="10">
				<tr>
					<td><b>Betreff:</b></td>
					<td><input type="text" name="title" id="title" value="">
					 '.JS::event('#title','keydown','chk()').'
                                                '.JS::event('#title','focus','chk()').'
					</td>
				</tr>
				<tr>
					<td valign="top"><b>Nachricht:</b></td>
					<td><textarea name="maintext" id="maintext" rows="15" cols="50" class="input"></textarea>
					 '.JS::event('#maintext','keydown','chk()').'
                                                '.JS::event('#maintext','focus','chk()').'
					</td>
				</tr>
				<tr>
					<td valign="top"><b>Senden an:</b></td>
					<td>'.$residents.'
						`bKosten bis jetzt:`b <span id="cost">0</span> Edelstein(e)!
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<span id="but" style="visibility:hidden;"><input type="submit" value="Tauben auf die Reise schicken!" class="button"></span>
						<span id="msg">Bitte verfasse nun deine Botschaft und wähle die Empfänger!</span></td>
				</tr>
			</table>
			</form>
			'.JS::MassMail();
        }
        else
        {
            $str_out .= '`c`bDu arme Socke hast gar keine Mitglieder!.`b`c';
        }
        $description = $str_out;

    }

    else if($_GET['do']=='cleanup')
    {
        $navs = array();
        $isanb = ($id>0);
        $navs['Zurück'] = '';
        if($isanb)
        {
            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
            $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo['id'];
        }
        else
        {
            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds WHERE id='".$world."' LIMIT 1"));
            $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world;
        }
        $name = 'Alle Beiträge löschen?';
        $description = 'Bist du absolut sicher, dass du alle Beiträge an diesem Ort löschen willst? Dies lässt sich nicht mehr rückgängig machen!';
        $navs['Löschen'] = '';
        $navs['Ja alles löschen!'] =  'rp_places.php?do=cleanup2&world='.$world.'&id='.$id;
    }
    else if($_GET['do']=='cleanup2')
    {
        $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
        if($worldinfo['restricted']==1)
        {
            $private = true;
            $semiprivate = CRPPlaces::parent_restricted($id);
        }
        $sql = "UPDATE commentary SET deleted_by=16777215 WHERE section='rp_orte_".( $private||$semiprivate ? "p" : "o")."_".$world."_".$id."' AND deleted_by=0";
        db_query($sql);
        redirect('rp_places.php?world='.$world.'&id='.$id);
    }
    else if($_GET['do']=='add')
    {
		$navs = array();
		$isanb = ($id>0);
		$darf = false;
		
		$navs['Zurück'] = '';

        $private = false;

		if($isanb)
		{
			$worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
            if($worldinfo['restricted']==1)
            {
               // $private = true;
            }
			$navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo['id'];
			$name = $worldinfo['name'].' erweitern';
		}
		else
		{
			$worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds WHERE id='".$world."' LIMIT 1"));
			$navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world;
			$name = $worldinfo['name'].' um einen neuen Ort erweitern';
		}

		if($isanb)
		{
			$cnt = db_fetch_assoc(db_query("SELECT COUNT(*) AS c FROM rp_worlds_places WHERE world='".$world."' AND parent='".$id."' AND acctid='".$Char->acctid."'"));
			if($cnt['c']<$max_anbau)$darf = true;
			$name .= ' ('.($cnt['c']+1).' von '.$max_anbau.')';
		}
		else
		{
			$cnt = db_fetch_assoc(db_query("SELECT COUNT(*) AS c FROM rp_worlds_places WHERE world='".$world."' AND parent=0 AND acctid='".$Char->acctid."'"));
			if($cnt['c']<$max_places)$darf = true;
			$name .= ' ('.($cnt['c']+1).' von '.$max_places.')';
		}
		
		if($darf)
		{
			$pointsavailable=$Char->donation-$Char->donationspent;
			$willcost = 99999999;	
			$rabatt = false;		
			if($isanb)
			{
                $worldinfo = db_get("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1");
				$cnt = db_fetch_assoc(db_query("SELECT COUNT(*) AS c FROM rp_worlds_places WHERE parent<>0 AND acctid='".$worldinfo['acctid']."'"));
				if($cnt['c'] < $free_anbauten){
					$willcost = 0;
					$rabatt = true;
				}
				else $willcost = $costs['anbau'];
			}
			else
			{
				$cnt = db_fetch_assoc(db_query("SELECT COUNT(*) AS c FROM rp_worlds_places WHERE parent=0 AND acctid='".$Char->acctid."'"));
				if($cnt['c'] == 0){
					$willcost = $costs['first_place'];
					$rabatt = true;
				}
				else $willcost = $costs['place'];
			}
			
			if ($pointsavailable >= $willcost)
			{
				$form = true;
				$description = 'Informationen:`n
								`yJeder Charakter darf insgesamt '.$max_places.' Orte pro RP-Welt anlegen, die Kosten für einen Ort betragen einmalig `b'.$costs['place'].' DP`b, jeder Besitzer kann selbst entscheiden, ob dieser Ort öffentlich ist oder nur von eingeladenen Spielern betreten werden darf. Diese Einstellung, genau wie der Name und die Beschreibung, kann gegen `b'.$costs['edit'].'DP`b jederzeit geändert werden. An jedem eigenen Ort ist es zudem möglich bis zu `b'.$max_anbau.'`b weitere Ausbauten - bespielbare Unterorte - für jeweils `b'.$costs['anbau'].'DP`b anzulegen.`n`n
								Plays an öffentlichen Orten - welche dann für jeden Spieler nutzbar sind - werden ebenfalls mit DP belohnt. `n`n
								Angelegte Orte sollten zum Umfeld der RP-Welt passen und dürfen keine Serverregel verletzten.';
				if(isset($_POST['name']))
				{
					$form = false;
					
					$named = strip_appoencode(stripslashes(trim(strip_tags($_POST['name']))),2);
                    $rang = strip_appoencode(stripslashes(trim(strip_tags($_POST['rang']))),2);
                    $desc = closetags(stripslashes(trim(strip_tags($_POST['description']))),'`b`c`i');
                    $short = closetags(stripslashes(trim(strip_tags($_POST['short']))),'`b`c`i');

                    if($private)
                    {
                        $res = 1;
                    }
                    else
                    {

                        $res = intval($_POST['restricted']);
                    }

                    $priv_show_short = intval($_POST['priv_show_short']);

					if(mb_strlen(strip_appoencode($named,3)) > 0  &&  mb_strlen(strip_appoencode($desc,3)) > 0)
					{
                        $worldinfo = db_get("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1");

                        if(!isset($worldinfo['acctid']))$worldinfo['acctid']= $Char->acctid;

						if(db_query("INSERT INTO rp_worlds_places
						(id ,world ,acctid ,name, short, priv_show_short ,description ,restricted ,parent ,date,rang)
						VALUES (null , '".$world."', '".$worldinfo['acctid']."', '".db_real_escape_string($named)."', '".db_real_escape_string($short)."',  '".db_real_escape_string($priv_show_short)."', '".db_real_escape_string($desc)."', '".$res."', '".$id."', NOW( ), '".db_real_escape_string($rang)."')"))
						{
							$Char->donationspent+=$willcost;
							debuglog('Gab '.$willcost.'DP für '.($isanb ? 'einen Anbau' : 'einen RP-Ort').' aus'.( $rabatt ? ' mit Rabatt!' : ''));
							
							$description = '`n`@'.$named.' wurde angelegt.`0`n';
						}
						
					}
					else
					{
						$description .= '`n`n`b`$Bitte alle Felder sinnvoll ausfüllen.`0`b`n';
						$form = true;
					}
				}
				
				if($form)
				{
					$description .= '`n`n`fDieser '.($isanb ? 'Anbau' : 'Ort').' wird dich `b`$'.$willcost.'`b`f '.( $rabatt ? '(Rabatt) ' : '').'Donationpoints kosten!`n`n';

                    $rang = array();
                    $short = array();
                    $priv = array();

                    if(!$private)
                    {
                        $priv = array('restricted'=>'Privat,bool',);
                    }

                    if(!$isanb)
                    {
                        $rang = array('5' => 'Vorschau,preview,rang',
                            'rang' => '(Mitglieds-)Rang vom Inhaber (Std.: Besitzer),text,255',);

                        $short = array('2' => 'Vorschau,preview,short',
                            'short'=>'Kurz-Beschreibung,textarea,30,5,120',
                            'priv_show_short' => 'Kurz-Beschreibung auch bei privat anzeigen?,bool',);
                    }

                    $form_layout = array_merge(array('Dein neuer '.($isanb ? 'Anbau' : 'Ort').',title',
                            '1' => 'Vorschau,preview,name',
                            'name'=>'Name vom '.($isanb ? 'Anbau' : 'Ort').'',)
                        ,$priv
                        ,$rang
                        ,$short
                        ,array(
                            '3' => 'Vorschau,preview,description',
                            'description'=>'Beschreibung,textarea,30,5,4000')
                    );

					$str_link = 'rp_places.php?do=add&world='.$world.''.($isanb ? '&id='.$id : '');
					$raw .= form_header($str_link).generateform($form_layout,( isset($_POST['name']) ? $_POST : array() ),false,'Anlegen').form_footer();
					allownav($str_link);
				}
			}
			else
			{
				$description = '`n`$Du hast nicht genug Donationpoints!';
			}
		}
		else
		{
			$description = '`n`$Du hast bereits die maximale Anzahl an '.($isanb ? 'Anbauten' : 'Orte').' erreicht!';
		}
	}
	else if($_GET['do']=='edit')
	{
		if($id > 0)
		{
			$navs = array();
			$worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));

            $isanb =  ($worldinfo['parent']!=0);

            $private = false;

            if($worldinfo['parent']!=0)
            {
                $worldinfop = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$worldinfo['parent']."' LIMIT 1"));
                if($worldinfop['restricted']==1)
                {
                   // $private = true;
                }
            }

			$navs['Zurück'] = '';
			$navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo['id'];
			$name = $worldinfo['name'].' editieren';
	
			$pointsavailable=$Char->donation-$Char->donationspent;
			$willcost = $costs['edit'];
		
			if ($pointsavailable >= $willcost)
			{
				$form = true;
				$description = 'Informationen:`n
								`yJeder Charakter darf insgesamt '.$max_places.' Orte pro RP-Welt anlegen, die Kosten für einen Ort betragen einmalig `b'.$costs['place'].' DP`b, jeder Besitzer kann selbst entscheiden, ob dieser Ort öffentlich ist oder nur von eingeladenen Spielern betreten werden darf. Diese Einstellung, genau wie der Name und die Beschreibung, kann gegen `b'.$costs['edit'].'DP`b jederzeit geändert werden. An jedem eigenen Ort ist es zudem möglich bis zu `b'.$max_anbau.'`b weitere Ausbauten - bespielbare Unterorte - für jeweils `b'.$costs['anbau'].'DP`b anzulegen.`n`n
								Plays an öffentlichen Orten - welche dann für jeden Spieler nutzbar sind - werden ebenfalls mit DP belohnt. `n`n
								Angelegte Orte sollten zum Umfeld der RP-Welt passen und dürfen keine Serverregel verletzten.';
				if(isset($_POST['name']))
				{
					$form = false;

                    $named = strip_appoencode(stripslashes(trim(strip_tags($_POST['name']))),2);
                    $rang = strip_appoencode(stripslashes(trim(strip_tags($_POST['rang']))),2);
					$desc = closetags(stripslashes(trim(strip_tags($_POST['description']))),'`b`c`i');
                    $short = closetags(stripslashes(trim(strip_tags($_POST['short']))),'`b`c`i');
                    if($private)
                    {
                        $res = 1;
                    }
                    else
                    {

                        $res = intval($_POST['restricted']);
                    }
                    $priv_show_short = intval($_POST['priv_show_short']);

                    if($worldinfo['parent'] == 0 && $res == 1)
                    {
                        db_query("UPDATE rp_worlds_places SET restricted='1' WHERE parent='".$id."'");
                    }

					if(mb_strlen(strip_appoencode($named,3)) > 0  &&  mb_strlen(strip_appoencode($desc,3)) > 0)
					{
						if(db_query("UPDATE rp_worlds_places SET name='".db_real_escape_string($named)."',short='".db_real_escape_string($short)."',priv_show_short='".db_real_escape_string($priv_show_short)."',description='".db_real_escape_string($desc)."',restricted='".$res."',date = NOW(),rang='".db_real_escape_string($rang)."' WHERE id='".$id."' LIMIT 1"))
						{
							$Char->donationspent+=$willcost;
							debuglog('Gab '.$willcost.'DP für die Bearbeitung von '.$named.' (RP-Ort) aus');
							$description = '`n`@'.$named.' wurde bearbeitet.`0`n';
						}
						
					}
					else
					{
						$description .= '`n`n`b`$Bitte alle Felder sinnvoll ausfüllen.`0`b`n';
						$form = true;
					}
				}
				
				if($form)
				{
					$description .= '`n`n`fDiese Bearbeitung wird dich `b`$'.$willcost.'`b`f Donationpoints kosten!`n`n';

                    $rang = array();
                    $short = array();
                    $priv = array();

                    if(!$private)
                    {
                        $priv = array('restricted'=>'Privat,bool',);
                    }

                    if(!$isanb)
                    {
                        $rang = array('5' => 'Vorschau,preview,rang',
                            'rang' => '(Mitglieds-)Rang vom Inhaber (Std.: Besitzer),text,255',);

                        $short = array('2' => 'Vorschau,preview,short',
                            'short'=>'Kurz-Beschreibung,textarea,30,5,120',
                            'priv_show_short' => 'Kurz-Beschreibung auch bei privat anzeigen?,bool',);
                    }

                    $form_layout = array_merge(array('Bearbeiten von '.$worldinfo['name'].',title',
                            '1' => 'Vorschau,preview,name',
                            'name'=>'Name vom '.($isanb ? 'Anbau' : 'Ort').'',)
                        ,$priv
                        ,$rang
                        ,$short
                        ,array(
                            '3' => 'Vorschau,preview,description',
                            'description'=>'Beschreibung,textarea,30,5,4000')
                    );


					$str_link = 'rp_places.php?do=edit&world='.$world.'&id='.$id;
					$raw .= form_header($str_link).generateform($form_layout,( isset($_POST['name']) ? $_POST : $worldinfo ),false,'Speichern').form_footer();
					allownav($str_link);
				}
			}
			else
			{
				$description = '`n`$Du hast nicht genug Donationpoints!';
			}
		}
	}
	else
	{
		if($id > 0)
		{
			$navs = array();
			$row = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$id."' LIMIT 1"));
			$name = $row['name'];
			$description = $row['description'];
			CPicture::replace_pic_tags($description,$row['acctid']);
			$private = ($row['restricted']==1);
            $semiprivate = CRPPlaces::parent_restricted($row['id']);
			$navs['Zurück'] = '';
            $worldinfo = db_fetch_assoc(db_query("SELECT * FROM rp_worlds WHERE id='".$world."' LIMIT 1"));
			if($row['parent']!=0)
			{
				$worldinfo2 = db_fetch_assoc(db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND id='".$row['parent']."' LIMIT 1"));
				$navs['!?'.$worldinfo2['name']] = 'rp_places.php?world='.$world.'&id='.$worldinfo2['id'];
			}
			else
            {
                $navs['!?'.$worldinfo['name']] = 'rp_places.php?world='.$world;
			}

            $navs[$worldinfo['return_name']] = $worldinfo['return'];


            if($row['parent']==0)
            {
                $navs['Die Mitglieder'] = '';
                $navs['Liste'] = 'rp_places.php?world='.$world.'&id='.$id.'&do=memberlist';
            }

			$anb_res = db_query("SELECT * FROM rp_worlds_places WHERE world='".$world."' AND parent='".$id."'");
			if (db_num_rows($anb_res)>0)
			{
				$navs['-'] = '';
                $it = 0;
				while($anb_row = db_fetch_assoc($anb_res))
				{
					$allow = true;
					if($anb_row['restricted']==1 && $Char->acctid != $row['acctid'] && !$user_rights['allrooms'] && !$user_rights['keys'])
					{
                        $allow = CRPPlaces::has_key($anb_row['id'],$Char->acctid);
					}
					
					if($allow)
					{
						$navs[$anb_row['name'].'____|_____|____<___>__|__'.$anb_row['id']] = 'rp_places.php?world='.$world.'&id='.$anb_row['id'];
                        $it++;
					}
				}
                if($it == 0)unset($navs['-']);
			}

			if($user_rights['edit'] || $user_rights['build'] || $user_rights['positions'] || $user_rights['ranks'])
			{
				$navs['Verwaltung'] = '';
				if($user_rights['edit'])$navs['Editieren'] =  'rp_places.php?do=edit&world='.$world.'&id='.$id;
                if($user_rights['build'])$navs['Anbauen'] =  'rp_places.php?do=add&world='.$world.'&id='.$id;
			}
			if($row['parent']==0)
			{
                if($user_rights['positions'] || $user_rights['ranks'])$navs['Mitglieder'] =  'rp_places.php?do=worker&world='.$world.'&id='.$id;
			}
            if(($Char->acctid == $row['acctid']))
            {
                $navs['Taubenschlag!'] = '';
                $navs['Massenmail'] =  'rp_places.php?do=massmail&world='.$world.'&id='.$id;
                $navs['Zerstören!'] = '';
                $navs['Ort abreißen'] =  'rp_places.php?do=del&world='.$world.'&id='.$id;
            }
            if($private && $user_rights['cleanup']){
                $navs['Säubern'] = '';
                $navs['Beiträge löschen'] =  'rp_places.php?do=cleanup&world='.$world.'&id='.$id;
            }

            $m_key = db_get("SELECT placeid FROM rp_worlds_places_keys WHERE placeid='".$id."' AND acctid = '".intval($Char->acctid)."' LIMIT 1");
            $h_key = isset($m_key['placeid']);
            $m_mit = db_get("SELECT id FROM rp_worlds_members WHERE rportid='".intval($id)."' AND acctid='".intval($Char->acctid)."' LIMIT 1");
            $h_mit = isset($m_mit['id']);
            if(($row['parent']==0) && $h_mit){
                $navs['Mitgliedschaft'] = '';
                $navs['Mitgliedschaft beenden'] =  'rp_places.php?do=quit_mit&world='.$world.'&id='.$id;
            }
            if($h_key){
                $navs['Schlüssel'] = '';
                $navs['Schlüssel zurückgeben'] =  'rp_places.php?do=quit_key&world='.$world.'&id='.$id;
            }
			$rp = true;

            $footer .= '<div style="clear:both;"></div>';

			if($row['restricted']==1)
			{

                $footer .= '<div style="float:left; padding:25px;">';
				if($user_rights['keys'])
				{
					if($_GET['act']=='givekey')
					{
						$int_target = intval($_POST['acctid']);
						
						if($Char->acctid !=  $int_target)
						{
							db_query("INSERT INTO rp_worlds_places_keys (placeid,acctid) VALUES ('".$id."','".$int_target."') ON DUPLICATE KEY UPDATE acctid='".$int_target."'");
							
							systemmail($int_target,"`@Einladung erhalten!`0","`&{$session['user']['name']}
							`t hat dir eine Einladung für den privaten RP-Ort ({$worldinfo['name']}".( ($row['parent']!=0) ? ' -> '.$worldinfo2['name'] : '')." -> $name`t) geschickt!");
						}
					}
					else if($_GET['act']=='takekey')
					{
						$int_target = intval($_GET['acctid']);
						$int_key = intval($_GET['key']);
						db_query("DELETE FROM rp_worlds_places_keys WHERE id='".$int_key."' LIMIT 1");
						
						systemmail($int_target,"`4Einladung abgenommen!`0","`&{$session['user']['name']}
							`4 hat dir eine Einladung für den privaten RP-Ort ({$worldinfo['name']}".( ($row['parent']!=0) ? ' -> '.$worldinfo2['name'] : '')." -> $name`4) abgenommen!");
						
					}
					$str_givekey_lnk = 'rp_places.php?do=anbauten&world='.$world.'&id='.$id.'&act=givekey';
					$footer .= '<div id="search_div">
								Schlüssel vergeben an:`n`n`0
								'.form_header($str_givekey_lnk,'POST',true,'search_form','if(document.getElementById(\'search_sel\').selectedIndex > -1) {this.submit();} else {search();return false;}').'
									'.jslib_search('document.getElementById("search_form").submit();','Schlüssel vergeben!').'
								</form>
								</div>
								';
				}
				
				$keys = db_query("SELECT * FROM rp_worlds_places_keys WHERE placeid='".$row['id']."' ORDER BY id ASC");
				$i=1;
				$footer .= '`n`t`bDie Schlüssel:`b';
				while($key = db_fetch_assoc($keys))
				{
					$array_user = db_fetch_assoc(db_query("SELECT * FROM accounts WHERE acctid = '".$key['acctid']."'  LIMIT 1"));
					if(isset($array_user['name']))
					{
						$footer.='`n`t'.$i.': '.CRPChat::menulink($array_user).'`0 ';
						if($user_rights['keys'])$footer.=' `0[ '.create_lnk('X','rp_places.php?do=anbauten&world='.$world.'&id='.$id.'&act=takekey&acctid='.$key['acctid'].'&key='.$key['id'],true,false,'Bist Du sicher, dass du diesen Schlüssel wieder abnehmen möchtest?').' ] ';
						$i++;
					}
				}

                $footer .= '</div>';
			}

            $footer .= '<div style="float:right; padding:25px;width:40%;">'.house_show_furniture(23422342,$id,($Char->acctid == $row['acctid']),array(),false);
            $footer .= '</div>';
            $footer .= '<div style="clear:both;"></div>';


		}
		else
		{
			$row = db_fetch_assoc(db_query("SELECT * FROM rp_worlds WHERE id='".$world."'  LIMIT 1"));
			$name = $row['name'];
			$description = $row['description'];
			$navs = array(	'Zurück'=>'',
							'!?'.$row['return_name'] => $row['return'],
							'Verwaltung' => '',
							'Ort anlegen' => 'rp_places.php?do=add&world='.$world
						);
			$private = false;
              allownav('rp_places.php?world='.$world);
            $extended .= '`n`n<form action="rp_places.php?world='.$world.'" method="post">
                     Suchen: Besitzer <input name="searchb" type="text" size="15" maxlength="300" value=\''.utf8_htmlspecialchars((strip_appoencode(trim(stripslashes($_POST['searchb'])),3))).'\'>
                     Ortsname <input name="searcho" type="text" size="15" maxlength="300" value=\''.utf8_htmlspecialchars(strip_appoencode(trim(stripslashes($_POST['searcho'])),3)).'\'>
                     <input type="submit" value="Suchen">
                     </form>';

			$extended .= '`n`n<table width="100%" cellpadding="5" cellspacing="5"><tr class="trhead"><th>`&RP-Ort</th><th width="180px;">`&Besitzer</th><th>`&Beschreibung</th></tr>';

            if(isset($_POST['searchb']) || isset($_POST['searcho']))
            {
                $l = db_real_escape_string(strip_appoencode(trim(stripslashes($_POST['searchb'])),3));
                if($l=='')  $array_user_search = array();
                else $array_user_search = db_get_all("SELECT acctid FROM accounts WHERE login LIKE '%".$l."%' LIMIT 1");

                $ids = '0';

                foreach($array_user_search as $s)
                {
                    $ids .= ','.$s['acctid'];
                }

                $o = db_real_escape_string(strip_appoencode(trim(stripslashes($_POST['searcho'])),3));

                $res = db_query("SELECT * FROM rp_worlds_places WHERE parent=0
                AND world='".$world."'
                ".( ($ids != '0') ? " AND acctid IN (".$ids.") " : "" )."
                 ".( ($o != '') ? " AND name LIKE '%".$o."%' " : "" )."
                ORDER BY id ASC");
            }
            else
            {
                $res = db_query("SELECT * FROM rp_worlds_places WHERE parent=0 AND world='".$world."' ORDER BY id ASC");
            }

			$i=0;
			while($row = db_fetch_assoc($res))
			{
				$allow = true;
                $m_mit = db_get("SELECT id FROM rp_worlds_members WHERE rportid='".intval($row['id'])."' AND acctid='".intval($Char->acctid)."' LIMIT 1");
                $is_member = isset($m_mit['id']);
				if($row['restricted']==1 && $Char->acctid != $row['acctid'] && !$is_member)
				{
                    $allow = CRPPlaces::has_key($row['id'],$Char->acctid);
				}
				
				if(true)//$allow)
				{
					$array_user = db_fetch_assoc(db_query("SELECT * FROM accounts WHERE acctid = '".$row['acctid']."'  LIMIT 1"));
					if(isset($array_user['name']))
                    {
                        $link = 'rp_places.php?world='.$world.'&id='.$row['id'];
                        if($allow)allownav($link);

                        $desc = ($row['short'] != '') ? $row['short'] : str_replace('³','',$row['description']);

                        $extended .= '
						<tr class="'.($i%2?'trlight':'trdark').'">
						<td>'.( $allow ? '<a href="'.$link.'">'.$row['name'].'`0</a>' : $row['name'] ).'</td>
						<td>'.( CRPChat::menulink($array_user) ).'`0</td>
						<td>'.( $allow ? mb_substr(trim(strip_appoencode(utf8_preg_replace('/\[pic=(\w+)\]/i','',$desc),2)),0,120).'`0...'
                                :

                                ( ($row['priv_show_short'] && ($row['short'] != '')) ? mb_substr(trim(strip_appoencode(utf8_preg_replace('/\[pic=(\w+)\]/i','',$row['short']),2)),0,120).'`0...'.'`n' : '' ).'`YGeschlossene Gesellschaft - Für diesen Ort brauchst du eine Einladung von '.CRPChat::menulink($array_user).'.'

                            ).'</td>
						</tr>';
                        $i++;
                    }
                    else
                    {
                        //delete place
                        db_query("DELETE FROM rp_worlds_places WHERE id='".$row['id']."'");
                        //delete keys
                        db_query("DELETE FROM rp_worlds_places_keys WHERE placeid='".$row['id']."'");
                        //delete members
                        db_query("DELETE FROM rp_worlds_members WHERE rportid='".$row['id']."'");
                        db_query("DELETE FROM rp_worlds_positions WHERE rportid='".$row['id']."'");
                    }
                }
			}
			$extended .=  '</table>';

		}
	}
}

if($rp)addcommentary();
page_header(strip_appoencode($name,3));
output(get_title($name.( $private ? ' `&`i(privat)`0`i' : '')).$description.$extended);
rawoutput($raw);
if($rp)viewcommentary('rp_orte_'.( $private||$semiprivate ? 'p' : 'o').'_'.$world.'_'.$id,"Kommentar hinzufügen?",25,"sagt",false,true,false,0,true,true,($private ? 2 : 1));
output($footer);
foreach($navs as $k => $v)
{
	$k = explode('____|_____|____<___>__|__',$k);
	$k = $k[0];

	if($v!='')addnav($k,$v);
	else addnav($k);
}
page_footer();
?>