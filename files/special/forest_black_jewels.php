<?php

/**
 * Special im Wald: Die Schwarzen Juwelen
 * @copyright Sith for Atrahor
 * @author Dragonslayer
 */

page_header('Die schwarzen Juwelen');

$session['user']['specialinc'] = basename(__FILE__);
$str_filename = basename($_SERVER['SCRIPT_FILENAME']);
$str_backlink = 'forest.php';

$str_output = '';

switch($_GET['op'])
{
	case 'leave':
		{
			$str_output .= '`(Du winkst und gehst weiter auf einem anderen Weg weiter durch den Wald. Hinter dir kannst du noch eine Weile lang Hexe hören, bis auch ihr Getrappel im Dickicht verklingt und du dich auf die vor dir liegenden Abenteuer konzentrieren kannst.';
			$session['user']['specialinc'] = '';
			break;
		}
	case '':
		{
			if(item_count('owner='.$session['user']['acctid'].' AND tpl_id="blackjewel"')>0)
			{
				$str_output .= '`(Du hörst ein dir gut bekanntes Hufgetrappel und neigst leicht den Kopf, als `iHexe`i deinen Weg kreuzt. Ihr tauscht einen kurzen, aber höflichen Gruß aus und sie erkundigt sich noch kurz nach deinem Juwel, bevor ihr auch schon wieder getrennte Wege geht.';
				$session['user']['specialinc'] = '';
			}
			else 
			{
				$str_output .= '
				`(Plötzlich wird es dunkel um dich herum und du hörst leise Hufe über den weichen Waldboden hinweg trappeln, die sich dir aus dem Nichts zu nähern scheinen. Als erstes siehst du nur einen Frauenkörper...unbekleidet. Doch noch verwunderter bist du, als du den Blick hinab schwenkst und statt Füßen zarte Hufe siehst, die über den weichen Boden wandern. Ein ungewöhnlich schönes Gesicht und saphirene Augen blicken dir kritisch entgegen. Du erschauderst unter dem Blick des mysteriösen Wesens, denn sie scheint tief in dein Innerstes zu blicken, als sie leise zu dir spricht:`n
				
				"`#Fremde'.($session['user']['sex']?'':'r').'. Tritt nur näher und sieh an, was `iHexe`i dir bieten kann.`("
	
				`( Aus dem Nichts erschien ein Tisch aus mitternachtsschwarzem Marmor, auf dem farblich angeordnet 13 Juwelen aneinander gereiht sind. `&Bergkristall`(, `^Citrin`(, `ITigerauge`(, `rRosenquarz`(,  `#Aquamarin`(, `%Karneol`(, `wOpal`(, `jSmaragd`(, `1Saphir`(, `$Rubin`(, `)Amethyst`(, `(Hämatit und `~Onyx`(.
	
				`n"`#Bringe der Dunkelheit dein Opfer dar und erhalte das Juwel deiner Macht. Doch überschätze deine Kräfte nicht, sonst wird alles vergebens sein.`("
				';
	
				if($session['user']['maxhitpoints']>($session['user']['level']*10))
				{
					addnav('3 permanente LP opfern',$str_filename.'?op=give&type=1');
				}
				else
				{
					addnav('zu wenig LP','');
				}
				if($session['user']['charm']>4)
				{
					addnav('5 Charmepunkte opfern',$str_filename.'?op=give&type=2');
				}
				else
				{
					addnav('zu wenig Charme','');
				}
				if($session['user']['gems']>9)
				{
					addnav('10 Edelsteine opfern',$str_filename.'?op=give&type=3');
				}
				else
				{
					addnav('zu wenig Edelsteine','');
				}
	
				addnav('Weggehen',$str_filename.'?op=leave');
			}
			break;
		}
	case 'give':
		{
			if($_GET['type'] == 1)
			{
				$str_chosen = '3 permanente LP';
			}
			elseif ($_GET['type'] == 2)
			{
				$str_chosen = '5 Charmepunkte';
			}
			elseif ($_GET['type'] == 3)
			{
				$str_chosen = '10 Edelsteine';
			}
			
			$c=CRPChat::make_color($Char->prefs['commenttalkcolor'],'3');
			$str_output .= '
			`(Du überlegst kurz und antwortest dann: "'.$c.'Ich gebe Dir '.$str_chosen.$c.'.`("
			`n"`#So, Du möchtest also '.$str_chosen.' für eines meiner Juwelen erübrigen. Welches Juwel soll es denn sein? Du hast die Wahl zwischen 
			`&Bergkristall`(, `^Citrin`(, `ITigerauge`(, `rRosenquarz`(,  `#Aquamarin`(, `%Karneol`(, `wOpal`(, `jSmaragd`(, `1Saphir`(, `$Rubin`(, `)Amethyst`(, `(Hämatit und `~Onyx`#.`("';
			$arr_order = array(1,2,3,4,5,6,7,8,9,10,11,12,13);
			shuffle($arr_order);

			foreach($arr_order as $int_i)
			{
				switch($int_i)
				{
					case 1:
						addnav('`&Bergkristall',$str_filename.'?op=choose_jewel&jewel=1&type='.$_GET['type']);
						break;
					case 2:
						addnav('`^Citrin',$str_filename.'?op=choose_jewel&jewel=2&type='.$_GET['type']);
						break;
					case 3:
						addnav('`ITigerauge',$str_filename.'?op=choose_jewel&jewel=3&type='.$_GET['type']);
						break;
					case 4:
						addnav('`rRosenquarz',$str_filename.'?op=choose_jewel&jewel=4&type='.$_GET['type']);
						break;
					case 5:
						addnav('`#Aquamarin',$str_filename.'?op=choose_jewel&jewel=5&type='.$_GET['type']);
						break;
					case 6:
						addnav('`%Karneol',$str_filename.'?op=choose_jewel&jewel=6&type='.$_GET['type']);
						break;
					case 7:
						addnav('`wOpal',$str_filename.'?op=choose_jewel&jewel=7&type='.$_GET['type']);
						break;
					case 8:
						addnav('`jSmaragd',$str_filename.'?op=choose_jewel&jewel=8&type='.$_GET['type']);
						break;
					case 9:
						addnav('`1Saphir',$str_filename.'?op=choose_jewel&jewel=9&type='.$_GET['type']);
						break;
					case 10:
						addnav('`$Rubin',$str_filename.'?op=choose_jewel&jewel=10&type='.$_GET['type']);
						break;
					case 11:
						addnav('`)Amethyst',$str_filename.'?op=choose_jewel&jewel=11&type='.$_GET['type']);
						break;
					case 12:
						addnav('`(Hämatit',$str_filename.'?op=choose_jewel&jewel=12&type='.$_GET['type']);
						break;
					case 13:
						addnav('`~Onyx',$str_filename.'?op=choose_jewel&jewel=13&type='.$_GET['type']);
						break;
				}
			}
			break;
		}
	case 'choose_jewel':
		{
			$_GET['jewel'] = (int)$_GET['jewel'];
			
			if($_GET['jewel'] == 1)
			{
				$str_jewel = '`&Bergkristall';	
			}
			elseif ($_GET['jewel'] == 2)
			{
				$str_jewel = '`^Citrin';
			}
			elseif ($_GET['jewel'] == 3)
			{
				$str_jewel = '`ITigerauge';
			}
			elseif ($_GET['jewel'] == 4)
			{
				$str_jewel = '`rRosenquarz';
			}
			elseif ($_GET['jewel'] == 5)
			{
				$str_jewel = '`#Aquamarin';
			}
			elseif ($_GET['jewel'] == 6)
			{
				$str_jewel = '`%Karneol';
			}
			elseif ($_GET['jewel'] == 7)
			{
				$str_jewel = '`wOpal';
			}
			elseif ($_GET['jewel'] == 8)
			{
				$str_jewel = '`jSmaragd';
			}
			elseif ($_GET['jewel'] == 9)
			{
				$str_jewel = '`1Saphir';
			}
			elseif ($_GET['jewel'] == 10)
			{
				$str_jewel = '`$Rubin';
			}
			elseif ($_GET['jewel'] == 11)
			{
				$str_jewel = '`)Amethyst';
			}
			elseif ($_GET['jewel'] == 12)
			{
				$str_jewel = '`(Hämatit';
			}
			elseif ($_GET['jewel'] == 13)
			{
				$str_jewel = '`~Onyx';
			}
			
			//User hat mit der Wahl genau getroffen
			if((($_GET['jewel']-1)*10<=$session['user']['dragonkills'] && $_GET['jewel']*10>=$session['user']['dragonkills'] && $_GET['jewel']<11)||
			($session['user']['dragonkills']>100 && $session['user']['dragonkills']<=149 && $_GET['jewel']==11) ||
			($session['user']['dragonkills']>=150 && $session['user']['dragonkills']<=199 && $_GET['jewel']==12) ||
			($session['user']['dragonkills']>200 && $_GET['jewel']==13))
			{
				$str_output .= '
				`( Du wählst '.$str_jewel.' `(aus und hältst es fest mit den Fingern umschlossen, als du das leise Lachen der Mitternachtsstimme vernimmst. Die Augen von Hexe blicken zärtlich zu dir hinunter, als sie ihre feinen, blassen Finger um die deinen legte und das Juwel in deiner Hand noch fester drückt. `n`n

				"`#Richtig. Trage '.$str_jewel.' `#mit Ehre.`(", sagt `iHexe`i und lässt die restlichen Steine wieder im Nichts verschwinden. "`#Möge die Nacht dich umarmen, '.($session['user']['sex']==0?'Bruder':'Schwester').'.`("

				`( Als nach einiger Zeit auch die leisen Hufe nicht mehr zu hören sind, siehst du, dass deine Opfergabe dir zum Segen geschenkt wurde.
				`n`n Du erhältst ';
				if($_GET['type'] == 1)
				{
					$str_chosen = '3 permanente LP';
					$session['user']['maxhitpoints']+=3;
				}
				elseif ($_GET['type'] == 2)
				{
					$str_chosen = '5 Charmepunkte';
					$session['user']['charm']+=5;
				}
				elseif ($_GET['type'] == 3)
				{
					$str_chosen = '10 Edelsteine';
					$session['user']['gems']+=10;
				}
				$str_output .= $str_chosen;
				$arr_content = array('level'=>$_GET['jewel']); //diesen wert nach value2 verschieben!
				item_add($session['user']['acctid'],'blackjewel',array('content'=>$arr_content,'tpl_value1'=>$_GET['jewel'],'tpl_value2'=>$_GET['jewel'],'tpl_gold'=>$_GET['jewel']*1000,'tpl_name'=>$str_jewel.'`0'));
			}
			//User hat mit der Wahl +/- 2 getroffen
			elseif((($_GET['jewel']-2)*10<=$session['user']['dragonkills'] && ($_GET['jewel']+2)*10>=$session['user']['dragonkills'] && $_GET['jewel']<11) ||
			($session['user']['dragonkills']>100 && $session['user']['dragonkills']<=149 && $_GET['jewel']==12 || $_GET['jewel']==10) ||
			($session['user']['dragonkills']>150 && $session['user']['dragonkills']<=199 && $_GET['jewel']==13 || $_GET['jewel']==11) ||
			($session['user']['dragonkills']>200 && $_GET['jewel']==12))
			{
				$str_output.= '
				`( Du wählst '.$str_jewel.' `(aus und hältst es fest mit den Fingern umschlossen, als du das leise Lachen der Mitternachtsstimme vernimmst.`n`n

				"`#Fast.`(", sagt `iHexe`i und schüttelte fast schon betrübt ihren schönen Kopf. "`#Doch leider nicht korrekt. Aber ich will gnädig sein und nehme dir nur die Hälfte deines Einsatzes, damit du es beim nächsten Mal besser machen kannst.`("`n`n
				Du verlierst ';
				if($_GET['type'] == 1)
				{
					$str_chosen = '2 permanente LP';
					$session['user']['maxhitpoints']-=2;
				}
				elseif ($_GET['type'] == 2)
				{
					$str_chosen = '3 Charmepunkte';
					$session['user']['charm']-=3;
				}
				elseif ($_GET['type'] == 3)
				{
					$str_chosen = '5 Edelsteine';
					$session['user']['gems']-=5;
				}
				$str_output .= $str_chosen;
			}
			//User lag völlig daneben
			else 
			{
				$str_output .= '`( Du wählst '.$str_jewel.' `(aus und hältst es fest in den Fingern umschlossen, als du das leise Lachen der Mitternachtsstimme vernimmst, fast schon höhnisch. ';
				
				if(($_GET['jewel']*10<$session['user']['dragonkills'] && $_GET['jewel']<11) ||
				($session['user']['dragonkills']<150 && $_GET['jewel'] < 12) ||
				($session['user']['dragonkills']<200 && $_GET['jewel'] < 13) ||
				($session['user']['dragonkills']>200 && $_GET['jewel'] !=13))
				{
					$str_output.= '"`#Du unterschätzt dich gewaltig. Denn '.$str_jewel.' `#würde dir gar nichts nützen.`("';
				}
				else 
				{
					$str_output.= '"`#Du überschätzt dich gewaltig. Denn '.$str_jewel.' `#würde deinen Leib zerfetzen.`("';
				}
				$str_output .= '
				`( Spöttisch schnalzt `iHexe`i mit der Zunge bevor sie sich amüsiert von dir abwendet. 
				Sie verschwindet wieder zwischen den Bäumen und nimmt deine Opfergabe mit sich. Du gehst vollkommen leer aus und verlierst ';
				if($_GET['type'] == 1)
				{
					$str_chosen = '3 permanente LP';
					$session['user']['maxhitpoints']-=3;
				}
				elseif ($_GET['type'] == 2)
				{
					$str_chosen = '5 Charmepunkte';
					$session['user']['charm']-=5;
				}
				elseif ($_GET['type'] == 3)
				{
					$str_chosen = '10 Edelsteine';
					$session['user']['gems']-=10;
				}
				$str_output .= $str_chosen;
			}
			$session['user']['specialinc'] = '';
			break;
		}
}
output($str_output);
?>
