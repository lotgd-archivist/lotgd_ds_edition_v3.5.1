<?php
/**
 * @author Valas
 * @copyright Valas for Atrahor.de
 * @desc Special 1 von 2 und eine kleine Homage an Monkey Island
 */

if (!isset($session)) exit();

$specialinc_file = 'forest_monkey_island.php';

//Der User hat noch kein Ohrenstäbchen
if(item_count('i.tpl_id ="qtip" AND i.owner = '.$session['user']['acctid']) == 0)
{
	$str_out = get_title('So ein Affentheater');
	switch ($_GET['op'])
	{
		case '':
			{
				$session['user']['specialinc'] = $specialinc_file;
				$str_out .= '`tBei deinem Weg durch den Wald, fällt dir plötzlich ein kleiner Affe auf, welcher scheinbar hungrig an einem... Bananenbaum?!? hinaufschaut, auf dem einige seiner Artgenossen genüsslich dinieren.`n
				Anscheinend wollen sie dem kleinen Racker nichts abgeben und stehst somit vor folgender Entscheidung:`n`n'.
				create_lnk('Bananen für den Affen besorgen','forest.php?op=get_banana',true,true,'',false,'Bananen besorgen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n'.
				create_lnk('Einfach weiter gehen','forest.php?op=leave',true,true,'',false,'Zurück in den Wald',CREATE_LINK_LEFT_NAV_HOTKEY);

				break;
			}
		case 'get_banana':
			{
				$session['user']['specialinc'] = '';
				switch (e_rand(1,2))
				{
					case 1:
						{
							$str_out .= '`tDu beschließt dem Tierchen zu helfen und beginnst vorsichtig auf den Baum zu klettern. Oben angekommen pflückst du rasch einen Bündel Bananen und begibst dich wieder herunter.
							Der Affe ist sichtlich erfreut darüber und drückt dir aus Dank ein... `bübergroßes Ohrstäbchen`b in die Hand, ehe er mit den Bananen verschwindet. Du fühlst dich einfach... wundervoll, ob diesem großzügigen Beweis seiner Dankbarkeit.';
							item_add($session['user']['acctid'],'qtip');
							break;
						}
					case 2:
						{
							$str_out .= '`tDu beschließt dem Tierchen zu helfen und beginnst vorsichtig auf den Baum zu klettern. Oben angekommen versuchst du rasch einen Bündel Bananen zu pflücken, doch die anderen Affen scheinen dein Vorhaben gar nicht gut zu finden. Sie fallen kurzerhand über dich her. Nicht lange dauert es, bis sie dich unsanft zurück auf den Boden der Tatsachen befördert haben.`n
							Du verlierst fast alle deine Lebenspunkte. Das hat man nun von seiner Hilfsbereitschaft.';
							$session['user']['hitpoints'] = 1;
							addnav('N?Tägliche News','news.php');
							addnav('Heiler aufsuchen','healer.php');
							addnav('Zurück in den Wald','forest.php');
							break;
						}
				}
				break;
			}
		case 'leave':
			{
				$session['user']['specialinc'] = '';
				$str_out .= 'Da du nicht derjenige bist, der Hunger hat, beschließt du das kleine Tier einfach zu ignorieren und gehst in den Wald zurück.';
				break;
			}
	}
}
else
{
	$str_out = get_title('Affige Erlebnisse');

	switch ($_GET['op'])
	{
		case '':
			{
				$session['user']['specialinc'] = $specialinc_file;

				$str_out .= '`tGelangweilt schlenderst du durch den Wald, in welchem du nun schon eine ganze Weile keine Monster mehr angetroffen hast, als dir plötzlich ein riesiger steinerner... Affenkopf?!? ins Auge fällt. Verdutzt schaust du das Ding an, als dir plötzlich ein Äffchen an den Rücken springt und dir dein `yOhrstäbchen`t klaut. Ehe du reagieren kannst, ist es auch schon zu dem riesigen Affenkopf gerannt und pult diesem im Ohr herum. Du staunst nicht schlecht, als der riesige Kopf seinen Mund zu öffnen scheint. Ob du dir das genauer anschauen solltest?`n`n'.

				create_lnk('Den Affenkopf betreten','forest.php?op=enter',true,true,'',false,'Kopf betreten',CREATE_LINK_LEFT_NAV_HOTKEY).'`n'.
				create_lnk('Einfach weiter gehen','forest.php?op=leave',true,true,'',false,'Zurück in den Wald',CREATE_LINK_LEFT_NAV_HOTKEY);

				break;
			}
		case 'enter':
			{
				$session['user']['specialinc'] = $specialinc_file;
				$str_out .= 'Du betrittst dieses seltsame Bildnis eines Affenkopfes. Sofort ruht dein geschulter Blick auf einer riesigen Schatztruhe. Freudig rennst du darauf zu, als sich dir plötzlich ein... Geisterpirat in den Weg stellt. Verdammt! Hättest du jetzt doch nur ein Malzbier dabei, oder wüsstest woher dir das so verdammt bekannt vorkommt...';

				$badguy = array(
				"creaturename"=>"`6Letschack, der untote Pirat`0"
				,"creaturelevel"=>($session['user']['level']+2)
				,"creatureweapon"=>"Untotes Gebein"
				,"creatureattack"=>($session['user']['attack']+e_rand(0,10))
				,"creaturedefense"=>($session['user']['defdense']+e_rand(0,10))
				,"creaturehealth"=>($session['user']['maxhitpoints'])
				,"diddamage"=>0);
				$session['user']['badguy']=utf8_serialize($badguy);

				addnav('Kämpfe','forest.php?op=fight');
				break;
			}
		case 'fight':
			{
				$session['user']['specialinc'] = $specialinc_file;
				$battle = true;
				break;
			}
		case 'leave':
			{
				$session['user']['specialinc'] = '';
				$str_out .= '`t"`ySachen gibts...`t" denkst du dir, ehe du deinen Weg fortsetzt..';
				$bool_delete_qtip = true;
				break;
			}
	}
}


//Kämpfööön
if ($battle)
{
	include ("battle.php");
	if ($victory)
	{
		$str_out = "`n`0Du hast `^".$badguy['creaturename']." geschlagen und das auch noch ganz ohne Malzbier!
		Schnell begibst du dich zu der Truhe und öffnest sie, wobei ein Schatz von 5.000 Gold und 5 Edelsteinen zum Vorschein kommt. Zufrieden sammelst du die Beute ein und kehrst in den Wald zurück.";
		$session['user']['gold'] += 5000;
		$session['user']['gems'] += 5;
		$badguy=array();
		$session['user']['badguy']="";
		$session['user']['specialinc']='';
		$bool_delete_qtip = true;
	}
	elseif($defeat)
	{
		$str_out = '`0Du wurdest von Letschack, dem untoten Piraten getötet...nichts ungewöhnliches, wenn man bedenkt, dass du kein Malzbier bei dir getragen hast.`n`n
		So lange, wie du nun schon in dieser Stadt lebst, kannst du dir denken was jetzt kommt, richtig? Richtig! Schöne Grüße an Ramius!';

		killplayer();
		$session['user']['specialinc']='';
		$session['user']['reputation']--;
		$bool_delete_qtip = true;
	}
	else
	{
		fightnav();
	}
}

if($bool_delete_qtip)
{
	item_delete('tpl_id ="qtip" AND owner = '.$session['user']['acctid'],1);
}

output($str_out);
?>