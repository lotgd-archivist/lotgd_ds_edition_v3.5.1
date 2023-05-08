<?php

require_once 'common.php';

if(!isset($session))
{
	echo('$session nicht definiert in '.$filename.'');
	exit();
}

$filename=basename(__FILE__);
page_header('Das Meer');

addcommentary();

$arr_mussle_colors = array(
	array('text' => '`&weiße', 'color' => '`&'),
	array('text' => '`Fbläuliche', 'color' => '`F'),
	array('text' => '`/gelbliche', 'color' => '`/'),
	array('text' => '`ggrünliche', 'color' => '`g'),
	array('text' => '`Rrosafarbene', 'color' => '`R'),
	array('text' => '`vfliederfarbene', 'color' => '`v'),
	array('text' => '`Iapricotfarbene', 'color' => '`I'),
	array('text' => '`Nschwarze', 'color' => '`N'),
	array('text' => '`olachsfarbene', 'color' => '`o'),
	array('text' => '`*türkisfarbene', 'color' => '`*')
);

$str_output = '';
switch ($_GET['op'])
{
	case 'meer':
		{
			$str_output .= Weather::get_weather_text('Meer');
			addnav('Z?Zurück',$filename.'?op=strand');
		}
		break;

	case 'strand':
		{
			//LeChuck holt sich seine Opfer
			if (getsetting('automaster',1))
			{
				$expreqd = get_exp_required($Char->level,$Char->dragonkills,true);
				if ($Char->experience>$expreqd && $Char->level>=15 && e_rand(1,3) == 3 )
				{
					redirect('boss.php?boss=lechuck&op=autochallenge');
				}
			}
			$str_output .= get_title('`I D`te`yr Stra`tn`Id').'`IE`ti`ynige Meter vom Hafen entfernt, nach einem kleinen Spaziergang durch die Dünen, gelangt man zu einem weitläufigen Sandstrand. Meist ist es ruhig hier; bei schönem Wetter kann man auch mal einer größeren Anzahl von Leuten begegnen, doch der Strand ist groß genug, dass jeder sein ruhiges Plätzchen finden kann. Der weiße, weiche Sand lädt dazu ein, das Schuhwerk auszuziehen und barfuß zu laufen, auch wenn es passieren kann, dass man mal auf einen spitzen Stein oder eine Muschel tritt, die das Meer angespült hat.`nIn einiger Entfernung beginnt der Strand, felsiger zu werden, anfangs sind es nur ein paar kleinere Felsen, die hier und da aus dem Sand gucken, doch  je weiter man geht , umso größer werden sie, ragen stellenweise weit ins Wasser herein und verführen sicherlich den ein oder anderen dazu, ein wenig herumzuklettern – manch einer ist dabei wohl auch schon ausgerutscht, man sollte also vorsichtig se`ti`In.`n`n';
			viewcommentary('meer_strand');
			addnav('s?Muscheln suchen',$filename.'?op=muscheln');
			addnav('M?Auf die Mole',$filename.'?op=meer');			
			addnav('H?Zum Hafen',$filename);
			addnav('n?Muschelkette nähen',$filename.'?op=nadelundfaden');
			
			//Bossgegner Skylla einfügen
			include_once(LIB_PATH.'boss.lib.php');
			boss_get_nav('skylla');
			//Geisterpirat LeChuck
			boss_get_nav('lechuck');
		}
		break;
	case 'muscheln':
		{
			$str_output .= '`ID`tu `ymachst dich auf die Suche nach schönen Fundstücken und schlenderst am Strand entlang, den Blick konzentriert gen Boden gerichtet.`n`n';
			if($Char->turns>0)
			{
				switch(e_rand(1,10))
				{
					case 1:
					case 2:
						$str_output .= 'Doch auch nachdem du den halben Strand abgesucht hast, findest du nichts, das sich lohnen würde mit nach Hause zu neh`ym`te`In.';
						break;
					case 3:
						if(item_count('i.owner = '.$Char->acctid .' AND i.tpl_id = "glasfigur"') == 0)
						{
							$str_output .= 'Zu deiner Überraschung findest du ein Stück glitzerndes Glas und stellst bei genauerer Betrachtung fest, dass es sich um eine schöne Glasfigur handelt, die du sogleich einsteckst.';
							item_add($Char->acctid,'glasfigur'); //Glasfigur
						}
						else 
						{
							$str_output .= 'Zu deiner Überraschung findest du ein Stück glitzerndes Glas und stellst bei genauerer Betrachtung fest, dass es sich um eine schöne Glasfigur handelt. Ein zweiter Blick in deinen Beutel offenbahrt dir jedoch, dass du solch eine Figur schon besitzt. Immer diese billige Dutzendw`ya`tr`Ie.';
						}
						break;
					case 4:
						$str_output .= 'Zu deiner Überraschung findest du ein Stück glitzerndes Glas, doch bei genauerer Betrachtung stellst du fest, dass es sich nur um eine billige Glasperle handelt. Dennoch steckst du dein Fundstück `ye`ti`In.';
						item_add($Char->acctid,'glasperle'); //Glasperle
						break;
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
						
						if (item_count('i.owner = '.$Char->acctid .' AND i.tpl_id = "muschel"') > mt_rand(100,150) )
						{
							$str_output .= 'Du rennst mit deinem Sack voll Muscheln wie ein Wilder über den Strand. Aber mal ehrlich. Hast du nach deiner groß angelegten Ernteaktion echt noch erwartet, viel zu finden? Eben. Leider ist alles abgegr`ya`ts`It.';
						}
						else 
						{
							$int_rand = e_rand(0, count($arr_mussle_colors)-1 );
							$str_color = $arr_mussle_colors[ $int_rand ]['text'];
							
							$str_output .= 'Du entdeckst im weichen Sand eine besonders schöne '.$str_color.'`y Muschel und beschließt, sie als Erinnerung an den Strandbesuch mit nach Hause zu neh`ym`te`In..';
																			
							
							$item['tpl_name']='Eine '.$str_color.' Muschel';
							$item['tpl_description']='Eine '.$str_color.' Muschel`0, welche du am Strand gefunden hast. Die Außenseite ist leicht gewellt, die Innenseite schimmert in wunderschönen Perlmuttfarben.';
							$item['content'] = $arr_mussle_colors[ $int_rand ]['color'];
							item_add($Char->acctid,'muschel',$item); //Muschel
						}
						break;
					case 10:
						$str_output .= 'Dir wird ein toter Fisch vor die Füße gespült, den du zuerst einfach ignorieren willst, dessen Form aber so merkwürdig ist, dass du ihn dir genauer ansiehst. Es scheint fast so, als würde sich etwas in dem Fisch befinden, weshalb du ihn mitnimmst, um ihn genauer zu untersuch`te`In.';
						item_add($Char->acctid,'fshkpf');
						break;
				}
				$Char->turns--;
			}
			else
			{
				$str_output .= '`tDoch du merkst schnell, wie deine Konzentration nachlässt und beschließt, die Suche für heute aufzugeben und wieder zu kommen, wenn du noch nicht so erschöpft bist.';
			}
			addnav('Z?Zurück',$filename.'?op=strand');
		}
		break;
	case 'nadelundfaden':
		{
			$str_output .= get_title('`tM`yu`&s`fchelkette n`fä`&h`ye`tn');			
			$int_count_mussels = item_count('i.owner = '.$Char->acctid .' AND i.tpl_id = "muschel"');
			
			//Nur wenn der user Nadel und Faden hat
			if(item_count('i.owner='.$Char->acctid.' AND tpl_id = "nadelundfaden"') == 0) 
			{
				$str_output .= '`tD`yu `&h`fockst dich an den Strand und breitest alle deine Muscheln vor dir aus. Nun, es mag vielleicht etwas kitschig klingen, aber eine schöne Muschelkette war jetzt das erste was dir in den Sinn kam, noch vor dem obligatorischen Sandburg bauen. Dummerweise hast du gar keine Utensilien bei dir, um die Kette zu bauen. Dazu bedarf es schon mindestens `bNadel und Fa`&d`ye`tn`b.';
			}
			else 
			{
				$str_md5_save = md5($_POST['mussel_name'].$_POST['mussels_to_delete'].$_POST['mussel_colored']);
				
				//Speichern
				if(isset($_POST['submit_changes']) && $str_md5_save == $_POST['checksum'])
				{
					$arr_changes['tpl_name'] = 'Eine Kette aus Muschelschalen: '.addstripslashes($_POST['mussel_colored'].'`0');
					$arr_changes['tpl_description'] = 'Die Kette ist aus einzelnen bunten Muschelschalen gefertigt. '.$Char->name.'`0 hat den Namen '.addstripslashes($_POST['mussel_colored'].'`0 darin eingraviert.');
					$arr_changes['tpl_gems'] = (int)count(explode(',',$_POST['mussels_to_delete']));
					
					item_add($Char->acctid,'muschelkette',$arr_changes);
					//fix by bathi
					item_delete('id IN('.db_intval_in_string($_POST['mussels_to_delete']).')');
					
					$Char->turns--;
	
					$str_output .= '`tD`ye`&i`fne Muschelkette mit dem Schriftzug '.$_POST['mussel_colored'].'`/ ist nach einiger Zeit fertig geworden. Du betrachtest sie eingehend und nickst zufrieden. Sie sieht kitschig schön aus und riecht ein klein wenig nach Meer und Fisch. Aber was hast du auch anderes erwar`&t`ye`tt?';
				}
				else 
				{
					$str_output .= '`tD`yu `&h`fockst dich auf den Strand und breitest alle deine Muscheln vor dir aus. Nun, es mag vielleicht etwas kitschig klingen, aber eine schöne Muschelkette war jetzt das erste was dir in den Sinn kam, noch vor dem obligatorischen Sandburg bauen. Mit einem Grinsen im Gesicht überlegst du, welchen Namen du in die Kette eingravieren könntest. Ein Buchstabe pro Muschel würde den meisten Sinn ergeben. Du nimmst Nadel und Faden aus deinem Beutel hervor und beginnst mit der Arb`&e`yi`tt.';
					
					$arr_form = array();
					$arr_data = array();
									
					$arr_form['mussel_name'] = 'Der Name der auf der Kette zu lesen sein soll,text,'.$int_count_mussels.'|?Natürlich dürfen nicht mehr Buchstaben verwendet werden als du Muscheln hast, in diesem Fall '.$int_count_mussels;
					
					$arr_form['submit_preview'] = 'Muscheln anordnen,submit_button,submit';
					$str_prev = '<hr /><br />';
					if($_GET['act'] == 'preview')
					{
						if(mb_strlen($_POST['mussel_name']) > $int_count_mussels || $int_count_mussels == 0)
						{
							$str_prev .= '`$Du hast doch gar nicht soviele Muscheln. Du solltest mehr Muscheln suchen.';
						}
						elseif(mb_strlen($_POST['mussel_name']) > 100 )
						{
							$str_prev .= '`$Ok, also jetzt haben wir es offiziell: Du bist ein Atrahor-Freak. Wer zum Geier kommt denn auf die Idee so viele Muscheln zu sammeln? Na gut, eine Kette mit 100 Muscheln erlaube ich dir, aber mehr nicht. Wer soll denn das tragen?!? Da kriegt man ja Genickstarre.';
						}
						else
						{
							$str_prev .= '`tD`yu `&t`füftelst eine Weile herum, legst dir die einzelnen Muscheln zurecht und hast schon bald eine erste Farbkombination gefunden auf die du den Namen kratzen könntest. Die Kette würde wie folgt aussehen, da bist du dir ganz si`fc`&h`ye`tr`n';						
							$arr_form['submit_changes'] = 'Kette erstellen!,submit_button,submit';
							
							$arr_form['mussels_to_delete'] = 'Die zu löschenden Muschelitems,hidden';
							
							$arr_data['mussel_name'] = isset($_POST['mussel_name']) ? trim(strip_appoencode($_POST['mussel_name'],3)) : '';
							
							$arr_mussels = item_list_get('i.owner = '.$Char->acctid .' AND i.tpl_id = "muschel"','',true,'*',true);
							shuffle($arr_mussels);
							
							$arr_mussels_to_delete = array();
							$str_mussel_name_colored = '';
							$str_prev .= '`b';
							for ($int_i = 0; $int_i < count($arr_mussels) && $int_i < mb_strlen($arr_data['mussel_name']); $int_i++)
							{
								$str_mussel_name_colored .= $arr_mussels[$int_i]['content'].$arr_data['mussel_name'][$int_i];
								$arr_mussels_to_delete[] = $arr_mussels[$int_i]['id'];
							}
							$str_prev .= $str_mussel_name_colored.'`0`b';
							$arr_data['mussels_to_delete'] = join(',',$arr_mussels_to_delete);
							$arr_form['mussel_colored'] = 'Farbtext,hidden';
							$arr_data['mussel_colored'] = str_replace(array('`','³','²'),array('``','³³','²²'),$str_mussel_name_colored);
							$arr_form['checksum'] = 'Prüfsumme,hidden';
							$arr_data['checksum'] = md5($arr_data['mussel_name'].$arr_data['mussels_to_delete'].$str_mussel_name_colored);
						}				
					}
					
					$str_output .= form_header($filename.'?op=nadelundfaden&act=preview');
					$str_output .= generateform($arr_form,$arr_data,true);
					$str_output .= form_footer();
					$str_output .= $str_prev;
				}
			}
			
			addnav('Z?Zurück',$filename.'?op=strand');
			break;
		}
	case 'boot':
		{
			$str_output .= get_title('`SD`Te`;r Anleg`Te`Sr').'`SV`Ti`;ele Schiffe unterschiedlicher Größe haben hier angelegt, von kleinen Ruderbooten über Fischkutter bis zu großen Segelbooten. In der Nähe unterhalten sich einige Seeleute und du gesellst dich zu ihnen, um zu erfragen, ob du auf einem Schiff mitfahren könntest. Zu deiner Enttäuschung erfährst du, das momentan keine Passagierschiffe auslau`;f`Te`Sn.';
			addnav('Z?Zurück',$filename);
		}
		break;
	case 'turm':
		{
			$str_output .= get_title('`$De`&r Le`$uch`&ttu`$rm').'`AB`4e`$i `&gutem Wetter nur ein normaler, rot-weiß gestreifter Turm, von dem man eine wundervolle Aussicht über das weite Meer hat. Bei Nacht oder schlechtem Wetter jedoch erfüllt er eine wichtige Aufgabe: Von der Spitze herab strahlt ein helles Licht, dass den Schiffen beim Navigieren helfen soll, damit zukünftig keine weiteren Schiffe auf die Klippen auflaufen würden. Blickt man an dem noch sichtlich neuen Turm vorbei in die Tiefe kann man auch erkennen, dass der Turm nicht vorsorglich gebaut worden ist, sondern ein Schiff dieses Schicksal erleiden mus`$s`4t`Ae.`n`n';
			addnav('W?Zum Wrack',$filename.'?op=wrack');
			addnav('Z?Zurück',$filename);
			viewcommentary('meer_turm');
		}
		break;
	case 'wrack':
		{
			$str_output .= get_title('`;D`Ta`Ss Wra`Tc`;k').'`;A`Tu`Sf den Felsen vor dem Leuchtturm liegt ein marodes, dreckiges Schiffswrack. Der Weg dorthin führt über glitschige Steine, die bei starkem Wellengang regelmäßig von Wellen überspült werden und dadurch zu einem Hindernis werden. Der ganze Rumpf ist von Muscheln und Algen bedeckt und durch ein klaffendes Loch in der Bordwand kann man in das düstere Innere hineinsehen und es gar betreten, sofern das Wasser, das sich dort gesammelt hat und in dem hin und wieder der glänzende Körper eines Fischens zu erkennen ist, einen nicht abschreckt.`nEine Treppe führt hinauf aufs Deck und Türen in mehrere Kabinen und Lagerräume, in denen sich teils kaputte Fässer und Kisten stapeln, doch das meiste, was es hier zu finden gibt ist verrottet und verschimme`Tl`;t.`n`n';
			addnav('Z?Zurück',$filename.'?op=turm');
			viewcommentary('meer_wrack');
		}
		break;

	default:
		{
			$str_output .= get_title('`tD`ye`8r Haf`ye`tn').'`tN`ya`8ch langer Reise erreicht man einen kleinen Hafen am Meer, der jedoch weniger geschäftig ist, als man erwarten würde. Kleine Lagerhallen säumen den Weg zum Pier, doch wird kaum Ware verladen und mit Ausnahme eines Handelsschiffes sind nur die Fischkutter in Benutzung. Dies jedoch ist nur zu leicht zu bemerken, der Fischgeruch zieht sich die ganze Straße hinauf und bis hin zu einem Fischmarkt am Rande dessen, was vermutlich das Wohngebiet der Fischer darstellt. Die kleinen, schmucklosen Häuser aus Holz und grob bearbeitetem Stein erwecken keinen vertrauenswürdigen Eindruck, sind sie doch windschief und Türen und Fenster durch die Seeluft angerostet. `nVom Pier aus hingegen kann man den hiesigen Leuchtturm erkennen und abseits vom Hafen, hinter Dünen verborgen, einen wundervollen Sandstra`yn`td.`n`n';
			viewcommentary('meer_hafen');
            CRPPlaces::addnav(3);
			addnav('');
			addnav('L?Zum Leuchtturm',$filename.'?op=turm');
			addnav('S?Zum Strand',$filename.'?op=strand');
			addnav('P?Das Piratennest','pirates.php');
			addnav('Zurück');
			addnav('K?Zur Kreuzung','forest_rpg_places.php');
			addnav('Z?Zurück nach '.getsetting('townname','Atrahor'),'village.php');
		}
}

headoutput($str_output);

page_footer();
?> 