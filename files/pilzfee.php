<?php

/*********************************
*                                *
*    Die Pilzfee (pilzfee.php)   *
*        Idee: Veskara           *
*    Programmierung: Linus       *
*   für alvion-logd.de/logd      *
*      im Dezember 2007          *
*                                *
**********************************/

/**
 * Komplett auf Atrahor umgeschrieben, nutzt jetzt Items
 */

require_once './common.php';

$pilze = array
(
	'hasel'		=>array('Haselröhrling','hasel',1),
	'gift' 		=>array('Giftmorchel','gift',2),
	'feigen'	=>array('Feigenfiesling','feigen',3),
	'hasen'		=>array('Hasenschwämmchen','hasen',4),
	'baum'		=>array('Baumfungi','baum',5),
	'insekt'	=>array('Insektentäubling','insekt',6),
	'leucht'	=>array('Leuchtender Nachtpilz','leucht',7),
	'alvion'	=>array('Alvionsteinpilz','alvion',8),
	'goetter'	=>array('Götterwulstling','goetter',9),
	'gold'		=>array('Goldener Pilz','gold',10),
	'atrahor'	=>array('Atrahürnling','atrahor',10),
);

$arr_mushroom = item_get('tpl_id="schattenpilz" AND i.owner='.$Char->acctid);
$arr_mushroom['content'] = adv_unserialize($arr_mushroom['content']);
if(!isset($arr_mushroom['content']['mushrooms']))
{
	$arr_mushroom['content']['mushrooms'] = array();
}

page_header("Die Pilzfee");

$str_backlink = 'gardens.php';
$str_backtext = 'Zurück zum Garten';

$str_filename = basename(__FILE__);

$str_out = get_title('`RD`ri`oe `tPilz`of`re`Re');
$str_out .= '`t';

switch($_GET['op'])
{
	default:
	case '':
		{
			addnav($str_backtext,$str_backlink);
			$str_out .= '`RD`ru `op`tflückst dir eine der lecker aussehenden Früchte von einem der vielen Obstbäume. Doch kaum willst du einen Bissen probieren, zerplatzt die Frucht auf einmal und zerfällt in glitzernden Staub. Nachdem du den ersten Schrecken überwunden hast, öffnest du deine Augen und siehst eine kleine Fee aufgeregt umherschwirren. "`rHast du Pilze? Hast du Pilze für mich?`t" fragt sie mit ihrer glockenhellen Sti`om`rm`Re. `n`n';
	
			$int_count = 0;
			foreach ($arr_mushroom['content']['mushrooms'] as $mushroom)
			{
				$int_count += $mushroom;
			}
	
			if($int_count >0)
			{
				$str_out .= '`RS`ri`oe `tflattert um dich herum herum. "`rJaaa, du hast Pilze für mich`t", ruft sie aufgeregt. "`yGib mir deine Pilze und ich werde dich belohnen!`t"';
				addnav('Pilze geben',$str_filename.'?op=pilze');
			}
			else
			{
				$str_out .= '`RS`ri`oe `tflattert um dich herum und ihr Gesicht überzieht sich mit einem traurigen Schleier. "`rDu hast keine Pilze für mich. Ich kann nichts für dich tun.`t"`n
				Ihre kleinen Flügelchen tragen sie hinauf zur Krone des Obstbaumes und sie verschwindet aus deinen Au`og`re`Rn.';
			}
			break;
		}
	case 'pilze':
		{
			addnav($str_backtext,$str_backlink);

			$str_out .= '`RD`ru `oz`teigst der Fee deine gesammelte Beute und kannst gar nicht so schnell schauen, wie die Fee schon um die Pilze herum schwirrt. "`tGibst du sie mir? Gibst du sie mir?`t" bittet sie überschwinglich und schaut dich dabei mit großen Augen...also äh, eher kleinen Auge`on `ra`Rn.`n';
	
			$arr_form = array();
			$arr_data = array();
			
			$str_out .= form_header($str_filename.'?op=sell');
			
			foreach($pilze as $key => $arr_pilz)
			{
				if($arr_mushroom['content']['mushrooms'][$key] == 0)
				{
					continue;
				}
				else 
				{
					$arr_form['mushrooms['.$key.']'] = $arr_pilz[0].': Davon besitzt du '.$arr_mushroom['content']['mushrooms'][$key];
					$arr_data[$key] = $arr_mushroom['content']['mushrooms'][$key];					
				}
			}
			$arr_data = generate_form_data($arr_data,'mushrooms');
			
			$str_out .= generateform($arr_form,$arr_data,false,'Geben');
			$str_out .= form_footer();
			break;
		}
	case 'sell':
		{
			addnav($str_backtext,$str_backlink);
			
			$str_out .= 'Du wühlst kurz in deinem Pilzarsenal und holst die gewünschten Pilze und ein paar schmutzige Fingernägel hervor. IGITT...achso, sind ja deine eigenen...`n`n';
			$int_cristalls = 0;
			foreach($_POST['mushrooms'] as $key => $val)
			{
				$val = abs(intval($val));
				$int_count = min($val, $arr_mushroom['content']['mushrooms'][$key]);
				$int_cristall =  $int_count * $pilze[$key][2];
				$int_cristalls += $int_cristall;
				$arr_mushroom['content']['mushrooms'][$key] -= $int_count;
				
				$str_out .= 'Du gibst der Fee '.$int_count .' Exemplar(e) der Sorte '.$pilze[$key][0].' und bekommst dafür '.$int_cristall.' Kristalle.`n';
			}
			
			$str_out .= '`n`nInsgesamt sind dies `b'.$int_cristalls.'`b Kristalle für diesen Tausch.';
			
			//Soo, jetzt zählen wir nochmal
			$int_count = 0;
			foreach ($arr_mushroom['content']['mushrooms'] as $mushroom)
			{
				$int_count += $mushroom;
			}
			if($int_count > 0)
			{
				item_set($arr_mushroom['id'],array('content'=>utf8_serialize($arr_mushroom['content'])),false,1);
				addnav('Mehr Pilze geben',$str_filename.'?op=pilze');
			}
			else 
			{
				item_delete($arr_mushroom['id'],1);
			}
			
			$arr_item = item_get('`tpl_id` = "feenkristall" AND `owner`='.$Char->acctid);
			if($arr_item === false)
			{
				item_add($Char->acctid,'feenkristall',array('item_count' => $int_cristalls));				
			}
			else 
			{
				item_set($arr_item['id'],array('item_count' => $arr_item['item_count']+$int_cristalls));
			}			
		}
}

output($str_out,true);
page_footer();
?>