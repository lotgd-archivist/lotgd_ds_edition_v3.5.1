<?php

// Eichhörnchen-Farm
// 1. Vorabversion
// by Maris (Maraxxus@gmx.de)

/*definition der Bit-Werte von value1 der Zuchtfarm (Mitbewohner-Rechte)
dazutun		1
mitnehmen	2
füttern		4
pflegen		8
taufen		16
töten		32
beißen		64
*/

function squirrelfarm_hook_process($item_hook , &$item )
{

	global $session,$item_hook_info;

	switch ($item_hook )
	{

		//	Aktionen bei Nutzung
		case 'furniture':
			$bool_putin=($item['owner']==$session['user']['acctid'] || ($item['value1']&1)==0 ?true:false);
			$bool_take=($item['owner']==$session['user']['acctid'] || ($item['value1']&2)==0 ?true:false);
			$bool_feed=($item['owner']==$session['user']['acctid'] || ($item['value1']&4)==0 ?true:false);
			$bool_care=($item['owner']==$session['user']['acctid'] || ($item['value1']&8)==0 ?true:false);
			$bool_name=($item['owner']==$session['user']['acctid'] || ($item['value1']&16)==0 ?true:false);
			$bool_kill=($item['owner']==$session['user']['acctid'] || ($item['value1']&32)==0 ?true:false);
			$bool_bite=($item['owner']==$session['user']['acctid'] || ($item['value1']&64)==0 ?true:false);
			if ($item_hook_info['op'] == 'take')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
					output('`&Du greifst in das Gehege und ziehst '.$items['name'].'`& am Nackenfell heraus.`nDein fachmännischer Blick auf die Hörnchen-Unterseite verrät dir, dass es sich um ein `^'.$items['special_info'].'`& handelt.`nWie es aussieht geht es dem Tier ');
					if ($items['value1']==$items['value2'] || $items['value1']>4)
					{
						output('hervorragend');
					}
					else
					{
						$value1=$items['value1'];
						$text_feel=array('hundsmieserabel','sehr schlecht','schlecht','gut','sehr gut','prächtig','hervorragend');
						if ($items['tpl_id']=='squirrd')
						{
							$value1+=2;
						}
						output($text_feel[$value1]);
					}
					output('.`n`nWas hast du nun damit vor?');
					addnav('Aktionen');
					if($bool_take)
					{
						addnav("Mitnehmen",$item_hook_info['link'].'&op=take2&obj_id='.$items['id']);
					}
					if ($bool_feed && $items['value1']<$items['value2'])
					{
						addnav("~~~");
						addnav("Füttern",$item_hook_info['link'].'&op=feed&obj_id='.$items['id']);
					}
					if ($bool_care && $items['hvalue']<$items['hvalue2'])
					{
						addnav("~~~");
						addnav("Pflegen",$item_hook_info['link'].'&op=care&obj_id='.$items['id']);
					}
					if($bool_name)
					{
						addnav("~~~");
						addnav("Ins Taufbecken tunken",$item_hook_info['link'].'&op=name&obj_id='.$items['id']);
					}
					if($bool_kill)
					{
						addnav("~~~");
						addnav("Den Hals umdrehen",$item_hook_info['link'].'&op=kill&obj_id='.$items['id']);
					}
					if ($bool_bite && ($session['user']['race']=='vmp' || $session['user']['race']=='wwf') &! (mb_strpos($items['name'],"Vampir`thörnchen`0") || mb_strpos($items['name'],"Wer`thörnchen`0") || mb_strpos($items['tpl_id'],'fru')))
					{
						addnav("~~~");
						addnav("Beißen",$item_hook_info['link'].'&op=bite&obj_id='.$items['id']);
					}
					addnav("~~~");
					addnav("Zurück setzen",$item_hook_info['link']);
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
					addnav("Zurück",$item_hook_info['link']);
				}
			}
			else if ($item_hook_info['op'] == 'name')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
					$name = $items['name'];
					if (mb_substr($name,-1) == ")")
					{
						output('`4Dieser Nager hat schon einen Namen und kann nicht erneut benannt werden!`0');
						if($item['owner']==$session['user']['acctid'])
						{
							addnav('Name entfernen',$item_hook_info['link'].'&op=changename&obj_id='.$items['id']);
						}
					}
					else if ($items['tpl_id'] == 'squirrh')
					{
						output('Du sterblicher Wicht willst einen Engel taufen?`0');
						$item_change['value1']=$items['value1']+1;
						item_set('id='.$items['id'],$item_change);
					}
					else
					{
						output("`&Wie soll das Tierchen heissen?`n");
						output("<form action=".$item_hook_info['link']."&op=name2&obj_id=".$items['id']." method='POST'><input name='newname' value=\"\" size=\"60\" maxlength=\"60\"> <input type='submit' value='Mal probieren'></form>");
						addnav("",$item_hook_info['link'].'&op=name2&obj_id='.$items['id']);
					}
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
				}
				addnav("Zurück",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'name2')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
                    if(!isset($msg))$msg='';
					$_POST['newname']=str_replace("`0","",$_POST['newname']);
					$_POST['newname'] = utf8_preg_replace("/[`][c]/","",$_POST['newname']);
					if (mb_strlen($_POST['newname'])>60)
					{
						$msg.="Der Name ist zu lang, inklusive Farbcodes darf er nicht länger als 60 Zeichen sein.`n";
					}
					$colorcount = mb_substr_count($_POST['newname'],'`');
					//if (getsetting('squirrel_maxcolors',8) != -1 && $colorcount>getsetting('squirrel_maxcolors',10))
					//{
					//	$msg.='`0Du hast zu viele Farben im Namen benutzt. Du kannst maximal '.getsetting('squirrel_maxcolors',10).' Farbcodes benutzen.`n';
					//}
					if ($msg=="")
					{
						output("`&Dein ".$items['name']."`&  wird so heißen: ".$_POST['newname']."`n`n`0Ist es das was du willst?`n`n");
						rawoutput("<form action='".$item_hook_info['link']."&op=changename&obj_id=".$items['id']."' method='POST'><input type='hidden' name='newname' value=\"".utf8_htmlspecialchars($_POST['newname'])."\"><input type='submit' value='Ja' class='button'></form>");
						output("`n`n<a href=".$item_hook_info['link']."&op=name&obj_id=".$items['id'].">Nein, ich will nochmal!</a>",true);
						addnav("",$item_hook_info['link'].'&op=name&obj_id='.$items['id']);
						addnav("",$item_hook_info['link'].'&op=changename&obj_id='.$items['id']);
					}
					else
					{
						output("`bFalscher Name`b`n$msg");
						output("`n`n`&Wie soll dein ".$items['name']."`& heißen?`n");
						rawoutput("<form action=".$item_hook_info['link']."&op=name2&obj_id=".$items['id']." method='POST'><input name='newname' value=\"".utf8_htmlspecialchars($_POST['newname'])."\"size=\"60\" maxlength=\"60\"> <input type='submit' value='Vorschau'></form>");
						addnav("",$item_hook_info['link'].'&op=name2&obj_id='.$items['id']);
					}
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
				}
				addnav("Zurück",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'changename')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
					if($_POST['newname'])
					{
						$newname = ($_POST['newname']);
						output("`&Du tunkst dein ".$items['name']."`& beherzt ins Taufbecken und gibst ihm den schönen Namen ".$newname."`&.`n");

						insertcommentary($session['user']['acctid'],': `6tauft ein '.$items['name'].'`6 auf den wunderschönen Namen `^'.$newname.'`6.','sqf'.$item['id']);

						$item_change['name'] = $newname." `&(".$items['name']."`&)";
					}
					else
					{
						output("`&Du schlägst ein paar mal deinen Kopf gegen die Wand und schon hast du vergessen wie ".$items['name']."`& einmal hieß.
						`nDiese Aktion kostet dich 100 Erfahrungspunkte.`n");
						$item_change=item_get_tpl('tpl_id="'.$items['tpl_id'].'"','tpl_name');
						$item_change['name']=$item_change['tpl_name'];
						$session['user']['experience']=max(0,$session['user']['experience']-100);
					}
					item_set('id='.$items['id'],$item_change);
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
				}
				addnav("Zurück",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'take2')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if (!empty($items['id']))
				{
					output("`&Du packst ".$items['name']."`& leise pfeifend ein. Denn bei dir ists immer noch am schönsten!`n");
					$item_change['owner'] = $session['user']['acctid'];
					$item_change['deposit1'] = 0;
					item_set('id='.$items['id'],$item_change);
					insertcommentary($session['user']['acctid'],': `@entnimmt '.$items['name'].'`@ aus dem Käfig.','sqf'.$item['id']);
					addnav("Auf gehts!",$item_hook_info['link']);
				}
				else
				{
					output('`&Du nimmst das Tierchen aus dem Käfig, doch es kratzt und beißt so doll dass du es vor Schreck fallen lässt.');
					addnav('Zur Farm',$item_hook_info['link']);
				}
			}
			else if ($item_hook_info['op'] == 'putin')
			{
				output("`n<table border='0'><tr><td>`2`bFolgende Eichhörnchen nagen dir gerade Löcher in deine Taschen:`b</td></tr><tr><td valign='top'>");
				$sql = 'SELECT i.id,i.name,special_info FROM items i LEFT JOIN items_tpl it ON i.tpl_id=it.tpl_id LEFT JOIN items_classes ic ON it.tpl_class=ic.id WHERE owner='.$session['user']['acctid'].' AND ic.class_name="Kleintiere" ORDER BY i.id ASC';
				$result = db_query($sql);
				$amount=db_num_rows($result);
				if (!$amount)
				{
					output("`iKein einziges!");
				}
				for ($i=1; $i<=$amount; $i++)
				{
					$items = db_fetch_assoc($result);
					output("<a href=".$item_hook_info['link']."&op=putin2&obj_id=".$items['id'].">`&-".$items['name']."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(`i".$items['special_info']."`i)`0`n");
					addnav("",$item_hook_info['link'].'&op=putin2&obj_id='.$items['id']);
				}
				output('</td></tr></table>');
				addnav('Zur Farm',$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'putin2')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner='.$session['user']['acctid'],false);
				output("`&".$items['name']."`& wirklich in die Zuchtfarm setzen?");
				addnav("Jawohl",$item_hook_info['link'].'&op=putin3&obj_id='.$items['id']);
				addnav("Nein",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'putin3')
			{
				output("`&Du setzt den kleinen Nager in sein neues Heim, wo er alsbald im dichten Unterholz verschwindet und dich keines Blickes mehr würdigt.`n");
				$items = item_get(' id='.$_GET['obj_id'].' AND owner='.$session['user']['acctid'],false);
				$item_change['owner'] = 1300000;
				$item_change['deposit1'] = $item['id'];
				item_set('id='.$items['id'],$item_change);

				insertcommentary($session['user']['acctid'],': `@setzt '.$items['name'].'`@ in die Zuchtfarm.','sqf'.$item['id']);

				addnav("Machs gut!",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'kill')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
					output("`&Willst du wirklich das Leben von ".$items['name']."`& so brutal beenden?");
					addnav("Knack!",$item_hook_info['link'].'&op=kill2&obj_id='.$items['id']);
					addnav("Nicht doch...",$item_hook_info['link']);
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Offenbar hat das Tierchen den Braten gerochen...');
					addnav("Zurück",$item_hook_info['link']);
				}
			}
			else if ($item_hook_info['op'] == 'kill2')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if ($items['name']=='`&Baby`thörnchen`0' && e_rand(1,5)==3)
				{
					output('`&Das Babyhörnchen weilt nun nicht mehr unter uns.`nWeil es noch absolut unschuldig war ist es nun ein Engel.');
					$itemnew['owner']=1300000;
					$itemnew['deposit1']=$items['deposit1'];
					$itemnew['deposit2']=$items['deposit2'];
					item_overwrite('id='.$items['id'],'squirrh',$itemnew);
					insertcommentary(1,'/msg `4Ein unschuldiges `&Baby`thörnchen`4 ist gestorben, `&jedoch ist seine Anwesenheit noch immer zu spüren.','sqf'.$item['id']);
				}
				else if ($items['tpl_id'] == 'squirrh')
				{
					output('Du drehst dem Engelshörnchen den Hals um. Für eine Weile guckt es nun nach hinten, aber sonst passiert nichts.
					`nWenn du dieses Tier loswerden willst, nimm es in eine dunkle Seitengasse mit und lass es dort zurück.');
				}
				else if($items['id']>0)
				{
					output($items['name'].'`& weilt nun nicht mehr unter uns.`nDu stopfst die Überreste aus und nimmst sie mit.');
					item_overwrite('id='.$items['id'],'squirr');
					insertcommentary($session['user']['acctid'],': `4dreht '.$items['name'].'`4 in gemeiner Weise den Hals um!','sqf'.$item['id']);
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Offenbar hat das Tierchen den Braten gerochen...');
				}
				addnav("Zurück",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'care')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
					output("`&Durch eine aufmerksame und liebevolle Krallen- und Gebisspflege wird der kleine Nager künftig noch herzhafter zubeissen und sich noch stärker festkrallen können.`nAllerdings wird dich die diamantbeschichtete Feile `^1 Edelstein`& kosten.`nBist du sicher, dass du ".$items['name']."`& pflegen willst?");
				addnav("Klar doch!",$item_hook_info['link'].'&op=care2&obj_id='.$items['id']);
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
				}
				addnav("Dann nicht...",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'care2')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if ($items['hvalue']>$items['hvalue2'])
				{
					output($items['name']."`& ist bereits so gut wie es geht gepflegt.`nMehr ist hier nicht möglich!`n");
				}
				else if ($session['user']['gems']<1)
				{
					output("`&Neben etwas Verstand fehlt dir auch der benötigte `^Edelstein`& um ".$items['name']."`& zu pflegen!");
				}
				else if($items['id']>0)
				{
					$item_change['hvalue'] = $items['hvalue']+1;
					item_set('id='.$items['id'],$item_change);
					$session['user']['gems']--;
					output($items['name']."`& erträgt die Prozedur nur widerwillig, ist aber mit dem Resultat sichtlich zufrieden.`nZähne und Krallen sind nun viel schärfer!");
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
				}
				addnav("Zurück",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'bite')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
					output("`&Bist du sicher, dass du ".$items['name']."`& beißen willst?");
					addnav("Hunger!",$item_hook_info['link'].'&op=bite2&obj_id='.$items['id']);
					addnav("Nee",$item_hook_info['link']);
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
					addnav("Dann nicht...",$item_hook_info['link']);
				}
			}
			else if ($item_hook_info['op'] == 'bite2')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
					if(mb_strpos($items['name'],"Baby`thörnchen`0") || $items['tpl_id']=='squirrh') //Baby oder Engel
					{
						output("`&Das war zwar sehr lecker, doch leider war ".$items['name']." `&völlig ungeeignet für eine Verwandlung und ist gestorben.`nFür den kleinen Snack bekommst du ein paar Lebenspunkte.`n");
						$session['user']['hitpoints']=min($session['user']['hitpoints']*1.1,$session['user']['maxhitpoints']*1.1);
						insertcommentary($session['user']['acctid'],': `4vergreift sich an '.$items['name'].'`4 als kleinem Imbiss!','sqf'.$item['id']);
						item_delete(' id='.$items['id']);
					}
					else
					{
						output($items['name']."`& beginnt nach deinem Biss die unheilvolle Verwandlung.");
						insertcommentary($session['user']['acctid'],': `8beißt '.$items['name'].'`8!','sqf'.$item['id']);
						if ($session['user']['race']=='vmp')
						{
							$newid='squirrc';
						}
						else if ($session['user']['race']=='wwf')
						{
							$newid='squirre';
						}
						else
						{
							$newid=$items['tpl_id'];
						}
						//unmöglicher Fall
						$itemnew['deposit1']=$item['id'];
						$itemnew['hvalue']=$items['hvalue'];
						$itemnew['special_info']=$items['special_info'];
						$itemnew['owner']=1300000;
						if (mb_strpos($items['name'],"("))
						{
							$oldname=mb_substr($items['name'],0,mb_strrpos($items['name'],"("));
							trim($oldname);
							if ($session['user']['race']=='vmp')
							{
								$itemnew['name']=$oldname."(`8Vampir`thörnchen`0`&)";
							}
							else
							{
								$itemnew['name']=$oldname."(`TWer`thörnchen`0`&)";
							}
						}
						item_overwrite('id='.$items['id'],$newid,$itemnew);
					}
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
				}
				addnav("Zurück",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'feed')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
					output("`&Womit willst du den kleinen Nager füttern?`n`n");
					output("`n<table border='0'><tr><td>`&`bDu hast in deinen Taschen:`b</td></tr><tr><td valign='top'>",true);
					$result = item_list_get('owner='.$session['user']['acctid'].' AND i.tpl_id IN ("macanut", "acofutter", "erdnuss", "strgale") ' , ' ORDER BY tpl_id,id ASC ' );
					$amount=db_num_rows($result);
					if (!$amount)
					{
						output("`iLediglich ganz ungesunde Sachen...");
					}
					for ($i=1; $i<=$amount; $i++)
					{

						$items2 = db_fetch_assoc($result);

						output("<a href=".$item_hook_info['link']."&op=feed2&obj_id=".$items['id']."&obj2_id=".$items2['id'].">`&-".$items2['name']."</a>`0`n",true);
						addnav("",$item_hook_info['link'].'&op=feed2&obj_id='.$items['id'].'&obj2_id='.$items2['id']);
					}
					output("</td></tr></table>",true);
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
				}
				addnav("Zurück",$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'feed2')
			{
				$items = item_get(' id='.$_GET['obj_id'].' AND owner="1300000"',false);
				if(is_array($items))
				{
					$items2 = item_get(' id='.$_GET['obj2_id'],false);

					if ($items2['tpl_id']=="erdnuss")
					{
						output("`&Nachdem du weißt, dass diese Erdnüsse tote Eichhörnchen zum Leben erwecken willst du gar nicht herausfinden, was sie mit den Viechern anstellen, wenn sie noch leben!`n");
						addnav("Nochmal",$item_hook_info['link'].'&op=feed&obj_id='.$items['id']);
					}
					else if ($items2['tpl_id']=="macanut")
					{
						if($items['tpl_id']=='squirrd') //Babyhörnchen
						{
							output("`&Gierig schlingt der kleine Racker die leckren Macadamia-Nüsse in sich hinein.`n");
							$item_change['value1'] = $items['value1']+1;
							if ($item_change['value1']>=$items['value2'])
							{
								output("`&Der Kleine ist nun kräftig genug um es mit den Gefahren der Welt aufnehmen zu können!");
								$itemnew['deposit1']=$item['id'];
								$itemnew['special_info']=$items['special_info'];
								$itemnew['owner']=1300000;
								$itemnew['name']='`tKiller-Eichhörnchen`0`&';
								if (mb_strpos($items['name'],'('))
								{
									$oldname=mb_substr($items['name'],0,mb_strrpos($items['name'],'('));
									trim($oldname);
									$itemnew['name']=$oldname.'(`tKiller-Eichhörnchen`0`&)';
								}
								item_overwrite('id='.$items['id'],'squirra',$itemnew);
								insertcommentary(1,'/msg `@'.$items['name'].'`@ ist ausgewachsen zu '.$itemnew['name'].'`@!','sqf'.$item['id']);
							}
							else
							{
								output("`&Schon bald wird er groß und stark sein!`n");
								item_set('id='.$items['id'],$item_change);
							}
							item_delete(' id='.$items2['id']);
						}
						else
						{
							output("`&Dieses Eichhörnchen ist schon groß und braucht keine Nahrung dieser Art mehr!`n");
							addnav("Nochmal",$item_hook_info['link'].'&op=feed&obj_id='.$items['id']);
						}
					}
					else if ($items2['tpl_id']=="strgale")
					{
						if($items['tpl_id']=='squirrffru') //Frustriertes Partyhörnchen
						{
							output("`&Dieses Eichhörnchen ist ziemlich fertig mit der Welt und braucht bestenfalls eine Entziehungskur!`n");
							addnav("Nochmal",$item_hook_info['link'].'&op=feed&obj_id='.$items['id']);
						}
						else if($items['tpl_id']=='squirrf') //Partyhörnchen
						{
							$item_change['value1'] = $items['value1']+1;
							if ($item_change['value1']>$items['value2'])
							{
								output($items['name']."`& ist bereits bei bester Stimmung und braucht keinen Alkohol!`n");
							}
							else
							{
								output($items['name']."`& gießt sich einen hinter die Binde und ist bereit für die Party!`n");
								item_set('id='.$items['id'],$item_change);
								item_delete(' id='.$items2['id']);
							}
						}
						else
						{
							output("`&Wie bitte? Du solltest besser selbst die Finger von dem Zeug lassen!`n");
							addnav("Nochmal",$item_hook_info['link'].'&op=feed&obj_id='.$items['id']);
						}
					}
					else if ($items2['tpl_id']=="acofutter")
					{
						if($items['tpl_id']=='squirrd') //Babyhörnchen
						{
							output("`&Dein Schützling ist noch viel zu klein um diese Nahrung zu sich zu nehmen.`nVersuch es doch mal mit etwas Kräftigendem.");
							addnav("Nochmal",$item_hook_info['link'].'&op=feed&obj_id='.$items['id']);
						}
						else if($items['tpl_id']=='squirrf' || $items['tpl_id']=='squirrffru') //Partyhörnchen
						{
							output("`&Dein `%P`!a`@r`^t`4y`thörnchen`& verschmäht diese Art von Köstlichkeiten!`n");
							addnav("Nochmal",$item_hook_info['link'].'&op=feed&obj_id='.$items['id']);
						}
						else
						{
							if (mb_strpos($items['tpl_id'],'fru'))
							{
								output("`&Gierig schlingt der Nager das leckere Acolytenfutter in sich hinein.`n");
								$item_change['value1'] = $items['value1']+1;
								if ($item_change['value1']>=$items['value2'])
								{
									output("`&Das hat ihn soweit besänftigt, dass er nun nicht mehr frustriert ist!");
									if($items['tpl_id'] == 'micefru'){
										$tplname= 'mice';
										$itemnew['name']=str_replace('`&Frustrierte ','',$items['name']);
									}else{
										$tplname= mb_substr($items['tpl_id'],0,7);
										$itemnew['name']=str_replace('`&Frustriertes ','',$items['name']);
									}
									$itemnew['deposit1']=$item['id'];
									$itemnew['hvalue']=$items['hvalue'];
									$itemnew['hvalue2']=$items['hvalue2'];
									$itemnew['special_info']=$items['special_info'];
									$itemnew['owner']=1300000;
									item_overwrite('id='.$items['id'],$tplname,$itemnew);
									insertcommentary(1,'/msg `@'.$items['name'].'`@ ist nun nicht mehr frustriert!','sqf'.$item['id']);

								}
								else
								{
									output("`&Es sieht dich zwar etwas freundlicher an, aber sein Vertrauen hast du noch lange nicht zurück gewonnen!`n");
									item_set('id='.$items['id'],$item_change);
								}
								item_delete(' id='.$items2['id']);
							}
							else
							{
								$item_change['value1'] = $items['value1']+1;
								if ($item_change['value1']>$items['value2'])
								{
									output($items['name']."`& scheint es gerade sehr gut zu gehen.`nDas Futter bleibt unangetastet!`n");
								}
								else
								{
									output($items['name']."`& frisst das Futter schnell auf und schöpft neue Kraft.`n");
									item_set('id='.$items['id'],$item_change);
									item_delete(' id='.$items2['id']);
								}
							}
						}
					}
				}
				else
				{
					output('Du greifst in das Gehege, hast aber nichts in der Hand. Vielleicht solltest du schneller werden...');
				}
				addnav('Zurück',$item_hook_info['link']);
			}
			else if ($item_hook_info['op'] == 'setrights')
			{
				if(isset($_POST['value1']))
				{
					$item['value1']=intval($_POST['value1']);
					item_set('id='.$item['id'],array('value1'=>$item['value1']));
				}
				output('Deinen Mitbewohnern diese Aktionen verbieten:`n
				<form method="POST" action="'.$item_hook_info['link'].'&op=setrights'.'">');
				$arr_form=array('value1'=>'Bewohner dürfen nicht,bitflag,dazutun,mitnehmen,füttern,pflegen,taufen,töten,beißen');
				showform($arr_form,$item);
				output('</form>');
				addnav('',$item_hook_info['link'].'&op=setrights');
				addnav('Zurück',$item_hook_info['link']);
			}
			else
			{
				output("`&Du stapfst langsamen Schrittes die Treppenstufen zum Keller hinab und betrachtest die eigenartige Konstruktion.`nDie Eichhörnchenzuchtfarm, die du erblickst, bietet den kleinen, pelzigen Vierbeinern einen optimalen Ort um sich von den Strapazen des Alltags zu erholen.`n`n");
				if($item['value1']>0)
				{
					output('Du siehst einen Zettel, auf dem steht: 
					`c"`QBitte keine Tiere`n'
					.(($item['value1']&1)==0?'':'dazutun,`n')
					.(($item['value1']&2)==0?'':'mitnehmen,`n')
					.(($item['value1']&4)==0?'':'füttern,`n')
					.(($item['value1']&8)==0?'':'pflegen,`n')
					.(($item['value1']&16)==0?'':'taufen,`n')
					.(($item['value1']&32)==0?'':'töten,`n')
					.(($item['value1']&64)==0?'':'beißen,`n')
					.'`$sonst gibts Beule!
					`n`QDer Eigentümer.`&"`c`n');
				}
				output("`0`n<table border='0'><tr><td>`2`bDerzeit tummeln sich in der Farm:`b`0</td></tr><tr><td valign='top'>",true);
				$result = item_list_get('owner="1300000" AND i.deposit1='.$item['id'].' ' , ' ORDER BY hvalue,id ASC ' );
                if(!isset($amount))$amount=0;
				while($items = db_fetch_assoc($result))
				{
					output('-'.create_lnk($items['name'].'`0',$item_hook_info['link'].'&op=take&obj_id='.$items['id']).'`n');
					$amount++;
				}
				if (!$amount)
				{
					output("`iNur die Wollmäuse, und davon nicht zu wenige!`i");
				}
				output("</td></tr></table>",true);
				if ($bool_putin && $amount<9)
				{
					addnav('Eichhörnchen');
					addnav('Dazutun',$item_hook_info['link'].'&op=putin');
				}
				else
				{
					addnav("Überfüllt");
				}
				$roomname="sqf".$item['id'];
				output("`n`n`n");
				addcommentary();
				viewcommentary($roomname,"Sagen:",20,"sagt");
				addnav('Sonstiges');
				if ($item['owner']==$session['user']['acctid'])
				{
					addnav('Mitbewohner Rechte',$item_hook_info['link'].'&op=setrights');
				}
				addnav($item_hook_info['back_msg'],$item_hook_info['back_link']);
			}
			break;

			//	Aktionen bei Newday
		case 'newday':
			if ($_GET['resurrection'])
			{
				return;
			}
			//Rassen zählen
			$sql='SELECT COUNT(*) AS c,tpl_id FROM items WHERE owner=1300000 AND deposit1='.$item['id'].' GROUP BY tpl_id';
			$result=db_query($sql);
			$squirs=array();
			while ($row=db_fetch_assoc($result))
			{
				$squirs=array_merge($squirs,array($row['tpl_id'] => $row['c']));
			}
			//Gesamtanzahl Hörnchen
			$amount2=array_sum($squirs);
			//Babys und Engel abziehen
			$amount = $amount2 -$squirs['squirrd'] -$squirs['squirrdfru'] -$squirs['squirrh'];
			$females = item_count('owner="1300000" AND i.gold>100 AND i.special_info="Weibchen" AND i.deposit1='.$item['id'].' ');
			$males = $amount -$females;
			$party = $squirs['squirrf'];
			$engel = $squirs['squirrh'];
			if ($party>0)
			{
				$presult = item_list_get('owner="1300000" AND i.tpl_id="squirrf" AND i.deposit1='.$item['id'].' ' , ' ORDER BY hvalue,id ASC ' );
				$sql="INSERT INTO commentary   (postdate,section,author,comment) VALUES (now(),'sqf".$item['id']."',1,'/msg `^".$party." Partyhörnchen ".($party==1?'sorgt':'sorgen')." für ausgelassene Stimmung in der Zuchtfarm!')";
				db_query($sql);
				for ($i=1; $i<=$party; $i++)
				{
					$sqr = db_fetch_assoc($presult);
					$sqr['value1']--;
					item_set('id='.$sqr['id'],$sqr);
					if ($sqr['value1'] < 1)
					{
						$itemnew['hvalue']=$sqr['hvalue'];
						$itemnew['hvalue2']=$sqr['hvalue2'];
						$itemnew['deposit1']=$sqr['deposit1'];
						$itemnew['special_info']=$sqr['special_info'];
						$itemnew['owner']=1300000;
						if (mb_strpos($sqr['name'],"("))
						{
							$oldname=mb_substr($sqr['name'],0,mb_strrpos($sqr['name'],"("));
							trim($oldname);
							$itemnew['name']=$oldname." `&(`&Frustriertes `%P`!a`@r`^t`4y`thörnchen`0`&)";
						}
						item_overwrite('id = '.$sqr['id'],'squirrffru',$itemnew);
					}
				}
				$presult2 = item_list_get('owner="1300000" AND i.tpl_id<>"squirrf" AND i.tpl_id<>"squirrffru" AND i.tpl_id<>"squirrd" AND i.tpl_id<>"squirrdfru" AND i.tpl_id<>"squirrh" AND i.deposit1='.$item['id'].' ' , ' ORDER BY hvalue,id ASC ' );
				//Regenerierung, alle außer Party, Baby und Engel
				$party2=db_num_rows($presult2);
				for ($i=1; $i<=$party2; $i++)
				{
					$sqr2 = db_fetch_assoc($presult2);
					$sqr2['value1']++;
					if ($sqr2['value1']>$sqr2['value2'])
					{
						$sqr2['value1']=$sqr2['value2'];
					}
					item_set('id='.$sqr2['id'],$sqr2);
					if (mb_strpos($sqr2['name'],"Frustriertes"))
					{
						if ($sqr2['value1']==$sqr2['value2'])
						{
							$tplname= mb_substr($sqr2['tpl_id'],0,7);
							$itemnew['name']=str_replace('`&Frustriertes ','',$sqr2['name']);
							$itemnew['deposit1']=$item['id'];
							$itemnew['hvalue']=$sqr2['hvalue'];
							$itemnew['hvalue2']=$sqr2['hvalue2'];
							$itemnew['special_info']=$sqr2['special_info'];
							$itemnew['owner']=1300000;
							item_overwrite('id='.$sqr2['id'],$tplname,$itemnew);
							insertcommentary(1,"/msg `^".$sqr2['name']."`^ wird wieder etwas gelassener.",'sqf'.$item['id']);
						}
					}
				}
			}
			if($amount>=2 && $amount2<=8 && $males>=1 && $females>=1) //Nachwuchs
			{
				$chance=$amount+4;
				if ($party>0)
				{
					$chance*=2;
				}
				if ($engel>0)
				{
					$chance*=0.5;
				}
				$dice=e_rand(1,100);
				if ($dice<=$chance)
				{
					$itemnew = item_get_tpl(' tpl_name="`&Baby`thörnchen`0" ' );
					if (false !== $itemnew )
					{
						$itemnew['deposit1']=$item['id'];
						$bool_female = (bool)e_rand(0,1);
						$str_sex = ($bool_female ? 'Weibchen' : 'Männchen');
						$itemnew['tpl_special_info'] = $str_sex;
						item_add(1300000, 0, $itemnew);
					}
					systemmail($session['user']['acctid'],"`@Der Klapperstrauß war da!`0","`&Welch freudiges Ereignis:`nIn deiner Eichhörnchenzuchtfarm gab es letzte Nacht Nachwuchs!");
					$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'sqf".$item['id']."',1,'/msg`@Ein `8Baby`thörnchen`@ erblickt das Licht der Welt!')";
					db_query($sql);
				}
			}
//admin_output('`n`@Chance auf `&Babyhörnchen: '.(int)$chance.'%`0`n(total '.$amount.', m '.$males.', w '.$females.')`n',false);
			
			if($engel>0 && $squirs['squirrffru']>0) //Bekehren der Partyhörnchen
			{
				$presult2 = item_list_get('owner="1300000" AND i.tpl_id="squirrffru" AND i.deposit1='.$item['id'].' ' , ' ORDER BY hvalue,id ASC ' );
				$party2=db_num_rows($presult2);
				for ($i=1; $i<=$party2; $i++)
				{
					$sqr2 = db_fetch_assoc($presult2);
					$sqr2['value1']+=$engel;
					if ($sqr2['value1']>=$sqr2['value2'])
					{
						$itemnew['deposit1']=$item['id'];
						$itemnew['special_info']=$sqr2['special_info'];
						$itemnew['owner']=1300000;
						if (mb_strpos($sqr2['name'],"("))
						{
							$oldname=mb_substr($sqr2['name'],0,mb_strrpos($sqr2['name'],"("));
							trim($oldname);
							$itemnew['name']=$oldname."(`tKiller-Eichhörnchen`0`&)";
						}
						item_overwrite('id='.$sqr2['id'],'squirra',$itemnew);
						insertcommentary(1,"/msg `^".$sqr2['name']."`^ findet auf den Pfad der Tugend zurück.",'sqf'.$item['id']);
					}
					else
					{
						item_set('id='.$sqr2['id'],$sqr2);
					}
				}
				$eresult = item_list_get('owner="1300000" AND i.tpl_id="squirrh" AND i.deposit1='.$item['id'].' ' , ' ORDER BY hvalue,id ASC ' );
				$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'sqf".$item['id']."',1,'/msg `^".$engel." Engelshörnchen ".($engel==1?'predigt':'predigen')." für Abstinenz in der Zuchtfarm!')";
				db_query($sql);
                if(!isset($engeldown))$engeldown=0;
				for ($i=1; $i<=$engel; $i++)
				{
					$sqr = db_fetch_assoc($eresult);
					$sqr['value1']--;
					item_set('id='.$sqr['id'],$sqr);
					if ($sqr['value1'] < 1)
					{
						item_delete('id='.$sqr['id']);
						$engeldown++;
					}
				}
				if ($engeldown>0)
				{
					$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'sqf".$item['id']."',1,'/msg `^Nach Ausführung des Auftrags ".($engeldown==1?'verlässt ':'verlassen ').$engeldown." Engelshörnchen diese Welt.')";
					db_query($sql);
				}
			}
			break;
	}
}
?>