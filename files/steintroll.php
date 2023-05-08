<?php

/**********************************
*                                 *
* Der Steintroll (steintroll.php) *
*          Idee: Veskara          *
*     Programmierung: Linus       *
*    für alvion-logd.de/logd      *
*           2007/2008             *
*                                 *
**********************************/

/**
 * Komplett auf Atrahor umgeschrieben, nutzt jetzt Items
 */

require_once('common.php');

if (getsetting('dailyspecial','Keines')=='Grasdrache')
{
	$steintroll=false;
	page_header('Der Grasdrache');
}
else
{
	$steintroll=true;
	page_header('Der Steintroll');
}

$str_backlink = 'rock.php';
$str_backtext = 'Zum magischen Felsen';

$str_filename = basename(__FILE__);

$arr_kristalle = item_get('`tpl_id` = "feenkristall" AND `owner`='.$Char->acctid);
$bool_save_kristall = false;

$str_out = get_title($steintroll?'Der Steintroll':'Der Grasdrache');

switch($_GET['op']) 
{
	default:
	case '':
		$str_out .= '`tDu trittst mit aller Wucht gegen den großen Stein. Das bereust du jedoch noch im selben Moment. Laut fluchend hüpfst du auf einem Bein herum und hältst den schmerzenden Fuß mit deinen Händen fest.';
		if($Char->level >= 10)
		{
			if($steintroll)
			{
			
				$str_out.= '`nPlötzlich beginnt der Stein sich zu bewegen und du stellst fest, dass hier im Stadtzentrum ein kleiner Troll sitzt, der dich jetzt mit böse funkelnden Augen anstarrt. "`yWas fällt dich ein?!?`t" schreit er dich an. "`yErst tust du mir treten und jetzt tust du machen grosses Geschrei!`t" Das Funkeln in den Augen des Steintrolls verwandelt sich jedoch rasch zu einem gierigen Glanz. "`yTust du Kristalle für mir haben? Gib mich Kristalle, mich hab ein paar schöne Dinge dafür.`t"';			
			}
			else
			{			
				$str_out .= '`nPlötzlich erscheint hinter dem Stein ein kleiner, seltsam aussehender Drache und schaut dich belustigt schmunzelnd an. 
				"`yWas tust du da?`t" will er von dir wissen. "`yWas soll der Krach?`t"`n
				Dann mustert der kleine borkige Drache aufmerksam den Beutel welcher an deiner Seite baumelt. "`yHast du etwa Kristalle für mich? Ich gebe dir auch ein paar schöne Dinge dafür.`t"';
			}
			if($arr_kristalle['item_count'] > 0) 
			{
				addnav('Kristalle eintauschen',$str_filename.'?op=tausch');
			}
			else
			{
				$str_out .= '`n`n'.$steintroll ? 
				'Enttäuscht wendet sich der Troll ab. "`yDu hast nichts für mir!`t"' : 
				'Enttäuscht schüttelt der Drache seinen Kopf "`yNein, du hast keine Kristalle die ich suche!`t" und verschwindet wieder hinter dem Felsen.';
			}
		}
		else 
		{
			$str_out .= '`n`bNichts passiert. Was hast du erwartet?';
		}
	break;

	case 'tausch':
		// Geschenke-Array in der Folge: name, class, gold, gems, description, hvalue, buff, Kristalle, Geschenktes Gold, Geschenkte gems, Geschenkte cp, Geschenkte Gefallen, nur Kämpfer
		
		if($steintroll)
		{
			$tausch = array(

				1=>array('Glücksmünze','Kleinod','500','0','Trage sie stets bei dir. Sie trägt ihren Namen sicher nicht zu unrecht.',0,0,'100',0,0,3,0,0,'glcksmnze'),
				2=>array('Mistelzweig für Verliebte','Geschenk','500','0','Ein Mistelzweig der die Liebe noch großer werden lässt.',0,0,'100',0,0,3,0,0,'mistelzwg'),
				3=>array('Herzkissen','Geschenk','500','0','Ein Kissen in Herzform.',0,0,'100',0,0,3,0,0,'herzkissen'),

				4=>array('Eulenstatue','Möbel','1000','0','Eine unheimliche Statue ...sie kann alles und jeden sehen.',0,0,'200',0,0,0,0,0,'eulenstatue'),
				5=>array('Thors-Hammer-Amulett','Kleinod','1000','0','Ein Amulett welches den Donner ruft wenn man an ihm reibt.',3,5,'200',0,0,0,0,1,'thorhammer'),
				6=>array('Silbernes Kreuz','Kleinod','1000','0','Ein Anhänger für eine Kette. Es schützt vor Vampiren.',0,0,'200',0,0,0,0,0,'silbkreuz'),
				7=>array('Kristallring','Geschenk','1000','0','Ein bezaubernder Ring für den oder die Liebste gut geeignet.',0,0,'200',0,0,5,0,0,'kristring'),
				8=>array('Runenstein','Kleinod','1000','0','Ein mystischer Stein auf dem Druiden geheimnisvolle Symbole verewigten.',0,0,'200',0,0,0,0,0,'runenstein'),

				9=>array('Marmorsplitter','Geschenk','1000','1','Ein kostbares Stück Marmor, glänzend und kostbar.',0,0,'500',0,0,0,0,0,'marmorsplit'),
				10=>array('Zepter des Ramius','Geschenk','1000','1','Ramius Zepter. Ob einem damit die Toten gehorchen?',0,0,'500',0,0,0,30,0,'ramiuszpt'),
				11=>array('Rubinring','Geschenk','2500','1','Ein Ring, welcher mit einem funkelndem roten Rubin geschmückt ist.',0,0,'500',0,0,0,0,0,'rubinring'),
				12=>array('Elfenbeinskulptur','Möbel','2500','1','Prächtige Skulptur, für den Kaminsims geeignet.',0,0,'500',0,0,0,0,0,'elfenbeinsklpt'),
				13=>array('Diamantverziertes Amulett','Geschenk','1000','1','Reich geschmücktes Amulett mit wertvollen Diamanten.',0,0,'500',0,0,0,0,0,'diaamulett')
			);
		}
		else
		{
			$tausch = array(
				1=>array('Drachenleder','Kleinod','500','0','Gut für schützende Kleidung geeignet.',1,4,'100',0,0,0,0,1,'drchleder'),
				2=>array('Regenbogenstaub','Geschenk','500','0','Er glitzert schön und ist fein anzusehen.',0,0,'100',0,0,3,0,0,'regenstb'),
				3=>array('Sternenstaub','Geschenk','500','0','Der Sternenstaub lässt Dich sehr hübsch aussehen.',0,0,'100',0,0,3,0,0,'sternstb'),
				4=>array('Drachenzahn','Kleinod','500','0','Ein echter Zahn eines Drachen, es heißt man hätte durch ihn magische Fähigkeiten.',1,3,'100',0,0,0,0,1,'drchzahn'),

				5=>array('Sternschnuppe','Geschenk','1000','0','Wünsch Dir was und es wird erfüllt werden.',0,0,'200',0,0,5,0,0,'sternschnuppe'),
				6=>array('Drachenanhänger','Kleinod','1000','0','Ein Anhänger der es in sich hat, doch was, ist seine eigene Magie?',0,0,'200',0,0,0,0,0,'drchanhaenger'),
				7=>array('Ende des Regenbogens','Geschenk','1000','0','Eine kleine Skulptur, die einen Teil des Regenbogens zeigt.',0,0,'200',1000,3,0,0,0,0,'regenende'),
				8=>array('Seelenstein','Kleinod','1000','0','Hunderte von Seelen wohnen in ihm, die dich nun beschützen.',3,2,'200',0,0,0,0,1,'seelenstein'),
				9=>array('Drachenstein','Möbel','1000','0','Ein Stein der Freude bringt...er wechselt regelmäßig seine Farbe.',0,0,'200',0,0,0,0,0,'drchstein'),
				10=>array('Drachenamulett','Kleinod','1000','0','Ein Schutzamulett, welches fast unzerstörbar ist.',5,1,'200',0,0,0,0,1,'drchamulett'),

				11=>array('Goldener Stern','Geschenk','1000','1','Ein Goldener Stern, der wunderbar sanftes Licht ausstrahlt.',0,0,'500',0,0,0,0,0,'gldnstern'),
				12=>array('Seelensplitter','Geschenk','1000','1','Ein Splitter der heller leuchtet wie die Sonne.',0,0,'500',0,0,0,0,0,'seelensplitter'),
				13=>array('Goldene Drachenstatue','Möbel','2500','1','Eine prachtvolle Statue.',0,0,'500',0,0,0,0,0,'gldndrache'),
				14=>array('Vergoldete Drachenschuppe','Möbel','2500','1','Ein seltener Fund, der dem Besitzer Glück bringen soll.',0,0,'500',0,0,0,0,0,'gldndrchschuppe'),
				15=>array('Atrahorium','Möbel','2500','1','Ein glasklarer Kristall, der laut einer Legende '.getsetting('townname','Atrahor').' in seinem Inneren abbildet.',0,0,'500',0,0,0,0,0,'atrahorium')
			);
		}
		$buffs = array
		(
			1=>array('name' => 'Drachenkraft', 'roundmsg' => 'Die Kraft des Drachen schützt dich.',
					'wearoff' => 'Der Drache legt sich schlafen.',
					'rounds' => 40, 'defmod' => '1.25', 'activate' => 'defense'),
			2=>array('name' => 'Seelenheil', 'roundmsg' => 'Hunderte von Seelen beschützen dich.',
					'wearoff' => 'Das Strahlen des Steins verblasst.', 'effectmsg' => 'Dein Gegner trifft nur mit halber Kraft.',
					'rounds' => 30, 'defmod' => '1.5', 'activate' => 'defense'),
			3=>array('name' => 'Urkraft des Drachen', 'roundmsg' => 'Die Kraft des Drachen stärkt dich.',
					'wearoff' => 'Der Drache legt sich schlafen.', 'effectmsg' => 'Die Urkraft verstärkt deine Schläge.',
					'rounds' => 20, 'atkmod' => '1.5', 'activate' => 'offense'),
			4=>array('name' => 'Schutz des Drachen', 'roundmsg' => 'Der Drache schützt dich.',
					'wearoff' => 'Der Drache legt sich schlafen.',
					'rounds' => 20, 'defmod' => '1.20', 'activate' => 'defense'),
			5=>array('name' => 'Thors Hammer', 'roundmsg' => 'Der Donner verstärkt deine Schläge.',
					'wearoff' => 'Der Donner verzieht sich.',
					'rounds' => 30, 'atkmod' => '1.50', 'activate' => 'offense')
		);

		switch($_GET['act'])
		{
			case '':
				$str_out .= '`nAlle `b'.$arr_kristalle['item_count'].' Kristalle`b, die du in deinem Beutel bei dir trägst, könntest du beim '.($steintroll?'Steintroll':'Grasdrachen').' eintauschen. Interessiert betrachtest du die Dinge, welche er gegen deine Kristalle eintauschen würde. Auch ein Tausch gegen Gold und Edelsteine wäre möglich.`n`n
				<table border="0" cellpadding="2" cellspacing="2"><tr class="trhead"><td>`bName`b</td><td>`bTyp`b</td><td>`bKristalle`b</td><td>`bBescheibung`b</td></tr>';
				$int_count = count($tausch);
				for($int_i = 1; $int_i <= $int_count; $int_i++)
				{
					if($tausch[$int_i][1]=='Möbel' && $Char->house == 0)
					{
						continue;
					}
					$str_bgcolor = ($str_bgcolor=='trlight'? 'trdark':'trlight');
						
					$str_out .= '<tr class="'.$str_bgcolor.'"><td>'.($arr_kristalle['item_count'] >= $tausch[$int_i][7]? create_lnk($tausch[$int_i][0],$str_filename.'?op=tausch&act=kauf&id='.$int_i) : $tausch[$int_i][0]). '</td><td>'.$tausch[$int_i][1].'</td><td>'.$tausch[$int_i][7].'</td><td>'.$tausch[$int_i][4].'</td></tr>';
				}
				$str_out .= '</table>';
				
				addnav('Gold','steintroll.php?op=gold');
				addnav('Edelsteine','steintroll.php?op=gems');
			break;

			case 'kauf':
				$id=(int)$_GET['id'];
				switch($tausch[$id][1])
				{
					case 'Möbel':
						$str_out .= '`nDu gibst dem '.($steintroll?'Steintroll':'Grasdrachen').' '.$tausch[$id][7].' Kristalle und bekommst dafür '.$tausch[$id][0].'.`n';
						
						$arr_item = array();
						item_add($Char->acctid,$tausch[$id][13],$arr_item);					
						
						$arr_kristalle['item_count'] -= $tausch[$_GET['id']][7];
						$bool_save_kristall = true;
					break;

					case 'Kleinod':
						$str_out .= '`nDu gibst dem '.($steintroll?'Steintroll':'Grasdrachen').' '.$tausch[$id][7].' Kristalle und bekommst dafür '.$tausch[$id][0].'.`n';
						if($tausch[$id][6] != 0)
						{
							$str_out .= "Sofort als du {$tausch[$id][0]} anlegst spürst du das irgend etwas mit dir passiert und du bekommst Lust auf einen Kampf!`n";
							$buffid=$tausch[$id][6];
							buff_add($buffs[$buffid]);							
						}
						$arr_item = array();
						item_add($Char->acctid,$tausch[$id][13],$arr_item);
						
						$arr_kristalle['item_count'] -= $tausch[$_GET['id']][7];
						$bool_save_kristall = true;

						//Gold zur Zeit nicht implementiert
						//if($tausch[$id][8]>0){
						//}

						//Gems zur Zeit nicht implementiert
						//if($tausch[$id][9]>0){
						//}

						//Charme
						if($tausch[$id][10] > 0)
						{
							$Char->charm+=$tausch[$id][10];
							$str_out.= '`nDu merkst sofort, als du das Kleinod anlegst, dass du dadurch schöner wirst. (Du erhältst '.$tausch[$id][10].' Charmepunkte.)';
						}

						//Gefallen zur Zeit nicht implementiert
						//if($tausch[$id][11]>0){
						//}
					break;
					case 'Geschenk':
						if ($_GET['subact']!='send')
						{
							allownav($str_filename.'?op=tausch&act=kauf&id='.$id);
							
							$str_out .= '
							<form action="'.$str_filename.'?op=tausch&act=kauf&id='.$id.'" method="POST">
								Suche in allen Feldern: ' 
								.'<br />'
								. JS::Autocomplete('q', true, true)
							.'</form>';
							
							if(isset($_POST['q']))
							{
								$arr_users = CCharacter::getChars($_POST['q'],"`a`.`acctid`,`login`,`name`",
									array(									
										'login' 	=> array('type'=>CCharacter::SEARCH_FUZZY, 'mode'=> null, 'open_bracket' => false, 'close_bracket' => false),
										'name' 		=> array('type'=>CCharacter::SEARCH_LIKE_EXT  , 'mode'=> 'OR', 'open_bracket' => false, 'close_bracket' => false)
									),' AND a.acctid != '.$Char->acctid
								);
							}
							else 
							{
								$arr_users = array();
							}
							
							if(count($arr_users) > 0)
							{
								$int_count = 1;
								$arr_ids = array();
								foreach ($arr_users as $arr_user)
								{
									if($int_count > 90)
									{
										$str_out .= '`n`n`$Es wurden noch mehr mögliche Empfänger gefunden, bitte schränke deine Suchbedingung weiter ein...';
										break;
									}
									$str_out .= create_lnk('`tAn '.$arr_user['name'].' `tverschicken.`n',$str_filename.'?op=tausch&act=kauf&subact=send&id='.$id.'&acctid='.$arr_user['acctid'],false);
									$arr_ids[] = $arr_user['acctid'];
									$int_count++;
								}
								addpregnav('/'.$str_filename.'\?op=tausch&act=kauf&subact=send&id='.$id.'&acctid=('.implode('|',$arr_ids).')/');
								unset($arr_ids);
							}
							else 
							{
								if(isset($_POST['q']))
								{
									$str_out .= '`n`n`$Leider gibt es mit diesen Kriterien niemanden an den man das Geschenk senden könnte. Auch an dich selbst kannst du das Geschenk nicht senden. Wäre ja auch unsinnig, oder?';	
								}							
							}						
						}
						else
						{
						
							$arr_item_tpl = item_get_tpl('tpl_id="'.$tausch[$id][13].'"');	
							$arr_item = array('tpl_description' => $arr_item_tpl['tpl_description'].' `tDies hat '.$Char->name.'`t dir unter unglaublichen Gefahren zukommen lassen.');
							item_add((int)$_GET['acctid'],$tausch[$id][13],$arr_item);
														
							$mailmessage = $Char->name.' hat dir ein Geschenk geschickt. Du öffnest es. Es ist ein/e '.$tausch[$id][0].'.`n';

							//Gold zur Zeit nicht implementiert
							//if($tausch[$id][8]>0){
							//}

							//Gems zur Zeit nicht implementiert
							//if($tausch[$id][9]>0){
							//}

							//Charme
							if($tausch[$id][10]>0)
							{
								user_update(array('charm'=>array('sql'=>true,'value'=>'charm+'.$tausch[$id][10])),(int)$_GET['acctid']);
								$mailmessage .= '`nDu spürst sofort, als du das Geschenk auspackst, dass es dich schöner macht. (Du erhältst '.$tausch[$id][10].' Charmepunkte.)';
							}

							//Gefallen
							if($tausch[$id][11]>0)
							{
								user_update(array('deathpower'=>array('sql'=>true,'value'=>'deathpower+'.$tausch[$id][11])),(int)$_GET['acctid']);
								$mailmessage.= '`nDu weißt, als du das Geschenk auspackst, dass es Ramius gefallen wird. (Du erhältst '.$tausch[$id][11].' Gefallen bei Ramius.)';
							}
							systemmail((int)$_GET['acctid'],'Geschenk erhalten!',$mailmessage);
							$arr_kristalle['item_count'] -= $tausch[$id][7];
							$bool_save_kristall = true;
							$str_out .= '`nDein/e '.$tausch[$id][0].'  wurde hübsch verpackt und verschickt!';
						}
					break;
				}
				addnav('Noch etwas eintauschen',$str_filename.'?op=tausch');
			break;
		}
	break;

	case 'gold':
		$gold = $arr_kristalle['item_count'] * 20;
		$str_out .= '"`yDu hast '.$arr_kristalle['item_count'].' Kristalle bei dir. Für einen Kristall bekommst du 20 Goldstücke. Ich gebe dir '.$gold.' Goldstücke für alle Kristalle die du bei dir trägst!`n`n';
		$str_out .= form_header($str_filename.'?op=givegold');
		$str_out .= 'Wie viele Kristalle gibst du mir? <input id="amount" name="amount" width="5" /><input type="submit" class="button" value="Geben">';
		$str_out .= form_footer();

		addnav('Alle Kristalle geben!',$str_filename.'?op=givegold&amount='.$arr_kristalle['item_count'],false,false,false,true,'Bist du ganz sicher dass du mir alle deine Kristalle eintauschen willst?');
		addnav('Etwas anderes machen',$str_filename.'?op=tausch');
	break;
	case 'givegold':
		$int_amount = min((int)$_REQUEST['amount'],$arr_kristalle['item_count']);

		$gold = $int_amount * 20;
		$str_out='Der '.($steintroll?'Steintroll':'Grasdrache').' gibt dir '.$gold.' Goldstücke für '.($int_amount>1? 'deine '.$int_amount.' Kristalle':'deinen Kristall').'.`n';
		$Char->gold+=$gold;
		$arr_kristalle['item_count'] -= $int_amount;
		$bool_save_kristall = true;
		addnav('Noch etwas eintauschen',$str_filename.'?op=tausch');
	break;

	case 'gems':
		$str_out .= '"`yDu hast '.$arr_kristalle['item_count'].' Kristalle bei dir. Für 100 Kristalle Gebe ich dir einen Edelstein. Ich könnte dir '.floor($arr_kristalle['item_count'] / 100).' Edelsteine für alle Kristalle, die du bei dir trägst, geben!`n`n';
		$str_out .= form_header($str_filename.'?op=givegems');
		$str_out .= 'Wie viele Kristalle gibst du mir? <input id="amount" name="amount" width="5" /><input type="submit" class="button" value="Geben">';
		$str_out .= form_footer();

		addnav('Alle Kristalle geben!',$str_filename.'?op=givegems&amount='.$arr_kristalle['item_count'],false,false,false,true,'Bist du ganz sicher dass du mir alle deine Kristalle eintauschen willst?');
		addnav('Etwas anderes machen',$str_filename.'?op=tausch');
	break;
		
	case 'givegems':
		$int_amount = min((int)$_REQUEST['amount'],$arr_kristalle['item_count']);

		$gems = floor($int_amount / 100);
		$str_out='Der '.($steintroll?'Steintroll':'Grasdrache').' gibt dir '.$gems.' Edelsteine für '.($int_amount>1? 'deine '.$int_amount.' Kristalle':'deinen Kristall').'.`n';
		$Char->gems+=$gems;
		$arr_kristalle['item_count'] -= $int_amount;
		$bool_save_kristall = true;
		addnav('Noch etwas eintauschen',$str_filename.'?op=tausch');
	break;
}

if($bool_save_kristall == true)
{
	if($arr_kristalle['item_count'] == 0)
	{
		item_delete($arr_kristalle['id'],1);
	}
	else 
	{
		item_set($arr_kristalle['id'],array('item_count' => $arr_kristalle['item_count']));
	}
}

addnav('Zurück');
addnav($str_backtext,$str_backlink);

output($str_out,true);
page_footer();

?>