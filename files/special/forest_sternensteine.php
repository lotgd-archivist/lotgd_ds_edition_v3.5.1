<?php
/**
 * Finde und sammle Sternensteine oder gib sie wieder zurück
 * Das komplette Special wurde von Dragonslayer auf den Kopf gestellt und für Atrahor angepasst. 
 * Alle Texte und Funktionen wurden ersetzt, nur die Idee des Specials stammt noch von Excalibur
 * und hiess irgendwann mal monpietre.php oder so
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor.de
 */

$str_filename = basename(__FILE__);

page_header('Die Quelle der Sternensteine');
output(get_title('`yQuelle der Sternensteine'));
$session['user']['specialinc']=$str_filename;

$arr_stones=array(
'ss_androme',
'ss_aquila',
'ss_atair',
'ss_aurigae',
'ss_aurora',
'ss_boreali',
'ss_centaur',
'ss_cetus',
'ss_draco',
'ss_furioni',
'ss_gemini',
'ss_gorgone',
'ss_hydra',
'ss_lynx',
'ss_monocer',
'ss_orion',
'ss_pisces',
'ss_serpens',
'ss_taurus',
'ss_vega');

$int_stones=count($arr_stones);

$str_output = '';

switch ($_GET['op'])
{
	case '':
		$str_output .= '`tAuf deinem Weg durch den Wald, auf der Suche nach Abenteuern, findest du eine klare Quelle, die einen übernatürlichen Schein ausstrahlt. Du bist durch Zufall über die `yQuelle der Sternensteine`t gestolpert, benannt nach einer längst vergessenen Sage.
		`n`nDragonslayer konnte jedoch einst ein paar ihrer Geheimnisse durch intensives Studium lüften und stellte dabei fest, dass einige der dort im Wasser befindlichen Steine besondere Kräfte frei werden lassen, wenn sie am Körper getragen werden.`n
		Er stellte außerdem fest, dass die Anzahl jener Steine begrenzt ist und eine Zugehörigkeit zu bestimmten Sternbildern aufweisen.
		`nMit etwas Glück kannst du einen dieser Steine besitzen, denn wer der Quelle ein Goldstück opfert, der erhält himmlischen Dank.`n`n';

		if($session['user']['gold']>0)
		{
			addnav('Gib ein Goldstück','forest.php?op=give');
		}
		else
		{
			$str_output .= 'Leider hast du kein Goldstück bei dir, sonst hättest du vielleicht einen der Steine dein Eigen nennen können.';
		}
		addnav('Verlasse die Quelle','forest.php?op=leave');

		//Steine können auch zurück gegeben werden
		$int_user_stones = item_count('owner='.$session['user']['acctid'].' AND tpl_class=28', true);
		if($int_user_stones > 2)
		{
			addnav('Steine zurückgeben');
			if($int_user_stones >= 3)
			{
				addnav('3 Steine zurückgeben','forest.php?op=return&amount=3');
			}
			if($int_user_stones >= 5)
			{
				addnav('5 Steine zurückgeben','forest.php?op=return&amount=5');
			}
			if($int_user_stones >= 10)
			{
				addnav('10 Steine zurückgeben','forest.php?op=return&amount=10');
			}
		}

		break;
	case 'return':
		$int_amount = (int)$_GET['amount'];
		$str_output .= '`tDu hast dich entschieden, einige der in deinem Besitz befindlichen Sternensteine zurückzulassen wo sie einst herkamen. Du stehst eine Weile am Rand der Quelle und blickst auf die '.$int_amount.' Steine in deiner Hand. Dann lässt du sie fallen und kannst schon nach wenigen Sekunden nicht mehr unterscheiden, ob du Kiesel oder Sternensteine unter der Wasseroberfläche glitzern siehst.';
		
		$arr_user_stones = item_list_get('owner='.$session['user']['acctid'].' AND tpl_class=28','ORDER BY RAND() LIMIT '.$int_amount,true,'id',true);
		
		foreach($arr_user_stones as $arr_stone)
		{
			$arr_stones_to_delete[] = $arr_stone['id']; 
		}
		$str_items_to_delete = join(',',$arr_stones_to_delete);
		item_delete('id IN ('.$str_items_to_delete.')',$int_amount);

		switch ($int_amount)
		{
			case 3:
			case 5:
				$str_output .= 'Die erwartete Belohnung lässt nicht lange auf sich warten und so siehst du bereits nach einigen Sekunden einen glitzenden Klumpen zu dir aufsteigen. Es handelt sich um '.$int_amount.' Edelsteine. Eine sehr willkommene Entschädigung.';
				$session['user']['gems']+=$int_amount;
				break;
			case 10:
				$str_output .= 'Die erwartete Belohnung lässt nicht lange auf sich warten und so siehst du bereits nach einigen Sekunden einen wabernden Klumpen zu dir aufsteigen. Es handelt sich um eine Rune. Eine sehr willkommene Entschädigung.';
				
				$a_runes_ident = runes_get_known();
				
				//Wenn die Rune bereits bekannt ist gib sie dem User
				if($a_runes_ident[12] == true)
				{
					item_add($session['user']['acctid'],'r_jera');
				}
				//ansonsten ein unbekanntes Exemplar
				else 
				{
					item_add($session['user']['acctid'],'r_dummy',array('value2'=>12));
				}
				break;

		}
		$str_output .= '`n`nFrohen Mutes gehst du in den Wald zurück';
		$session['user']['specialinc'] = '';
		addnav('Zurück in den Wald','forest.php');
		break;

	case 'give':
		$session['user']['gold'] = max(0,$session['user']['gold']-1);
		$str_output .= '`tDu nimmst ein Goldstück aus deinem Beutel und wirfst es in das klare Wasser hinein. Augenblicke später schon spürst du die magische und uralte Kraft, die von dieser Quelle ausgeht. Du schließt die Augen und greifst blind in den Quell hinein. Du fühlst viele kleinere Kiesel, die über die Jahre hinweg vom Wasser glatt gewaschen wurden. Sie sind kühl und teilweise glitschig. Mit einem Male jedoch spürst du einen Stein, der anders als die anderen zu sein scheint. Er fühlt sich warm an und ruft förmlich nach dir. Du umfasst ihn mit deiner Hand und hebst ihn aus dem Wasser heraus. Tatsächlich, es handelt sich um einen der Sternensteine!';
		$stone=e_rand(1,$int_stones);

		$str_stone_name = '';

		//Liste aller Sternensteine abrufen und nach Zufall ordnen
		$arr_user_stones = item_list_get('tpl_class=28','ORDER BY rand()',true,'tpl_id,owner,name,id',true);
		
		//Falls noch nicht alle Steine vergeben wurde werden sie hier verteilt
		if(count($arr_user_stones) == $int_stones)
		{
			foreach($arr_user_stones as $arr_stone)
			{
				//Nur Steine nehmen die der User nicht schon besitzt
				if ($arr_stone['owner'] == $session['user']['acctid'])
				{
					continue;
				}
				else
				{
					//Taube an den ehemaligen Besitzer
					systemmail($arr_stone['owner'],'`yDein Sternenstein hat einen neuen Besitzer','Dein Sternenstein "'.$arr_stone['name'].'" hat einen neuen Besitzer gefunden.');
					$str_stone_name = $arr_stone['name'];
					$arr_stone['owner'] = $session['user']['acctid'];
					item_set('id='.$arr_stone['id'],$arr_stone);
					break;
				}
			}
		}
		else
		{
			$arr_stones_copy = $arr_stones;
			//Alle existierenden usersteine durchgehen
			if(is_array($arr_user_stones))
			{
				foreach($arr_user_stones as $arr_stone)
				{
					//Alle existierenden Steine aus der Liste der möglichen Steine streichen
					if(in_array($arr_stone['tpl_id'],$arr_stones_copy))
					{
						$arr_stones_copy = array_remove_val($arr_stone['tpl_id'],$arr_stones_copy);
					}
				}
			}
			//Aus den verbleibenden steinen einen per Zufall auswählen und dem User geben
			$str_tpl = $arr_stones_copy[e_rand(0,count($arr_stones_copy)-1)];
			item_add($session['user']['acctid'],$str_tpl);
			$arr_stone = item_get('owner='.$session['user']['acctid'].' AND tpl_id = "'.$str_tpl.'"');
		}
		$str_output .= '`n`nBei genauerer Betrachtung handelt es sich um den `y'.$arr_stone['name'].'`t. Diesen wirst du fortan stets bei dir tragen und seine speziellen Kräfte an jedem neuen Tag auf dich wirken lassen. Dir ist jedoch auch bewusst, dass der Stein jederzeit seinen Besitzer wechseln kann.';
		$str_output .= '`n`nErfreut gehst du zurück in den Wald.';

		$session['user']['specialinc']='';
		//addnav('Zurück in den Wald','forest.php');

		break;
	case 'leave':
		$session['user']['specialinc']='';

		$str_output .= '`tDu wendest dich von der Quelle ab und läufst wieder zurück in den Wald. Ob das nun eine weise Entscheidung war wird nur die Zeit zeigen.';
		break;
}
output($str_output);
?>
