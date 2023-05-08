<?php

/*********************************
*                                *
*  Der Pilzwald (pilzsuche.php)  *
*        Idee: Veskara           *
*    Programmierung: Linus       *
*   für alvion-logd.de/logd      *
*      im Dezember 2007          *
*                                *
**********************************/

/**
 * Komplett auf Atrahor umgeschrieben, nutzt jetzt Items
 */

/*
// Pilzsuche by Linus & Veskara
if(mt_rand(1,5)==2) savesetting('steintroll','0');
else savesetting('steintroll','1');
*/

require_once 'common.php';

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

$str_rounds = 'drei';
$int_rounds = 3;

page_header('Der Pilzwald');
$str_out = get_title('`ND`|e`.r `gPilzw`.a`|l`Nd').'`t';

$str_backlink = 'forest_rpg_places.php?op=deepforest';
$str_backtext = 'Zurück zur Lichtung';

$str_filename = basename(__FILE__);

switch($_GET['op']) 
{
	default:
	case '':
		addnav($str_backtext,$str_filename.'?op=end');
		$str_out .= '`ND`|i`.r `gist ein wenig unheimlich, wie du hier so durch den als Schattenwald bekannten Bereich des Waldes streifst. Andererseits gibt es nur hier die schmackhaften Schattenwaldpilze und auf genau die hast du es ja abgesehen, oder nicht? Direkt neben dir wächst zum Beispiel ein prächtiger Fliegenpilz aus dem Boden und erweckt in dir die Frage, ob du hier wohl heute noch andere und vor allem essbare Pilze finden könntest.`n
		Du bist dir dessen bewusst, dass es dich etwas Zeit kosten würde, aber eine schlechte Idee wäre es bestimmt nicht auf Pilzsuche zu gehen, nicht wahr?`n`n';
		if(isset(Atrahor::$Session['daily']['pilzsuche']))
		{
			$str_out .= '`gAllerdings hast du heute ja schon Pilze gesucht, und einschlägige Experten warnen vor dem Wurzelgnom-Syndrom, nach dem zu häufiges "im Waldboden wühlen" zu einer optischen Angleichung des Suchenden mit dem Suchobjekt führt. Und nee, ne Pilzknollennase steht dir bestimmt nicht `.g`|u`Nt.`n';
		}
		elseif($Char->turns >= 3)
		{
			addnav('Boden durchwühlen',$str_filename.'?op=suche');
			$str_out .= '`gAlso, wie lange möchtest du den Boden durchwühlen? Jeder Versuch kostet dich '.$str_rounds.' Runden. Du könntest folglich '.floor($Char->turns/$int_rounds).' Versuche star`.t`|e`Nn!`n';
		}
		else
		{
			$str_out .= '`gWenn da nicht die kleine Hürde wäre an der immer alles hängt, nämlich die liebe Zeit. Du hast heute schon so viel getan, dass du echt einfach nicht mehr die Zeit hättest jetzt auf allen Vieren durch den Wald zu juckeln. Naja, dann eben ein ander`.m`|a`Nl.`n';
		}

		break;
	case 'end':
		{
			Atrahor::$Session['daily']['pilzsuche'] = true;
			redirect($str_backlink);
		}
		break;
	case 'suche':
	
		if ($Char->turns <= 2) //Die Abfrage oben ist ungenau, vielleicht klappt es ja so besser^^
		{
		$str_out .= '`gDu bist schon viel zu müde, um nach Pilzen zu suchen. Morgen ist schließlich auch noch ein Tag...`n`n';
		addnav('Bah, keinen Dreck mehr!' , $str_filename);
		}
	
		else
		{		
			$str_out .= '`gDu sinkst theatralisch auf die Knie und gräbst deine Hände tief in den Mutterboden. Wenn jemand wichtiges deine bühnenreife Leistung gesehen hätte, wärst du sicherlich sofort engagiert worden.`n
			Vorsichtig arbeitest du dich Meter für Meter durch das Unterholz.`n`n';

			if(mt_rand(0,30) == 0)
			{
				$str_out.='`gDu grunzt so vor dich hin und findest auch nach kurzer Zeit zwischen deinen erdverschmierten Fingern einen wunderschönen Pilz. Du hast schon so einen Spruch auf den Lippen, der dich als Trüffelschwein outen soll, als vor dir aus dem Gestrüpp eine Schweinenase hervorschnuppert, erst den Pilz, dann Dich erfasst und mit gerafften Lefzen auf dich zustürmt. Na prima, an der Nase hängt der Rest des Schweins auch noch dran!`n`n';
			
				$badguy = array(
				'creaturename'=>'Mieses, fieses Trüffelschwein'
				,'creaturelevel'=>$Char->level+1
				,'creatureweapon'=>'Gar grässliche Keilerhauer!'
				,'creatureattack'=>$Char->attack
				,'creaturedefense'=>$Char->defence
				,'creaturehealth'=>$Char->hitpoints
				,'diddamage'=>0);

				$Char->badguy=utf8_serialize($badguy);

				$battle = true;
				$fight = true;
			
				output($str_out);
			}
			elseif (mt_rand(0,30) == 0)
			{
				addnav('Keine Lust mehr', $str_filename);
				if($Char->turns >= $int_rounds)
				{
					addnav('Ich such lieber feste Pilze',$str_filename.'?op=suche');
				}
			
				$str_out .= 'Da steht er mit einem Male vor dir. Der Inbegriff eines Pilzes. Starker Stengel, mächtige Krone. Du musst dir die Augen reiben, um es zu glauben. Dummerweise reibst du dir dabei etwas Erde ins Gesicht und blinzelst eine Weile vor dich hin. Mit verschwommenem Blick musst du miterleben, wie der Pilz sich mit einem Male verdutzt in deine Richtung dreht, ein lautes Quieken ausstösst, seinen Stengel mit zwei dünnen Ärmchen wie einen Rock rafft und auf Spindeldürren Beinchen von dannen tippelt. Also so geht das ja wohl nicht, wo kommen wir denn da hin wenn jeder dahergelaufene Pilz sich von dannen macht? `bHinterher!`b';
				addnav('Den kauf ich mir!!!',$str_filename.'?op=catch&runden='.$_REQUEST['runden']);
			}
			else
			{
				addnav('Bah, keinen Dreck mehr!' , $str_filename);
				$arr_mushroom = item_get('tpl_id="schattenpilz" AND i.owner='.$Char->acctid);
				if($arr_mushroom === false)
				{
					item_add($Char->acctid, 'schattenpilz');
					$arr_mushroom = item_get('tpl_id="schattenpilz" AND i.owner='.$Char->acctid);
					$arr_mushroom['content'] = array();
				}
				$arr_mushroom['content'] = adv_unserialize($arr_mushroom['content']);
			
				if($Char->turns >= $int_rounds)
				{
					addnav('Mehr Pilze suchen',$str_filename.'?op=suche');
				}
				$Char->turns -= $int_rounds;			
			
				$str_out .= '`gMit aufmerksamen Augen tastest du den Waldboden ab in der Hoffnung, dass sich dir ein Pilzhütchen zeigen würde.`n`n';

				switch(mt_rand(1,3)){
					case 1:
						{
							$str_out .= '`gWovon haben wir zu Beginn noch gesprochen? Von deinem sprichwörtlichen Pech? Tja leider bewirkt dein Pech, dass du nicht einmal den kleinsten Hügel gefunden hast unter dem sich hätte ein Pilz verstecken können.`n`n';
							break;
						}
					case 2:
					case 3:
						{
							$str_out .= '`gEs dauert nicht einmal lange bis du auf einmal, versteckt zwischen den herausragenden Wurzeln eines mächtigen Baumes, einen prächtig aussehenden Pilz entdeckst. Ohne zu zögern schneidest du ihn ab.`n`n';
						
							//Einen Pilz aussuchen
							$rand_pilz = array_rand($pilze);
						
							$arr_mushroom['content']['mushrooms'][ $rand_pilz ]++;
						
							item_set($arr_mushroom['id'],array('content'=>utf8_serialize($arr_mushroom['content'])),false,1);
						
							$str_out .= '`gDu kannst mit Freuden feststellen, dass sich nun ein '.$pilze[$rand_pilz][0].' in deinem Besitz befindet.';
							break;
						}
				}

				$str_out .= '`n<table align="center"><tr class="trhead"><th>Pilztyp</th><th>Anzahl</th></tr>';
				foreach($pilze as $arr_pilz)
				{
					$int_anzahl = ($arr_mushroom['content']['mushrooms'][ $arr_pilz[1] ] > 0) ? '<b>'.$arr_mushroom['content']['mushrooms'][ $arr_pilz[1] ].'</b>' :0;
					$str_out .= '<tr><td>'.$arr_pilz['0'].'</td><td align="center">'.$int_anzahl.'</td></tr>';
				}
				$str_out .= '</table>';
			}
		}

		break;
	case 'catch':
		{
			if($Char->turns >= $int_rounds)
			{
				addnav('Mehr Pilze suchen',$str_filename.'?op=suche');
			}
			addnav('Was für ein Erlebnis...', $str_filename);
			
			switch (mt_rand(1,3))
			{
				case 1:
					$str_out .= 'Du hechtest auf und sprintest dem kleinen Mistvieh, äh -Pilz hinterher. Ein ungleicher Kampf, schließlich hast du etwa 20x so lange Beine... einziger Vorteil des Pilzes, er kennt den Wald wie seine Sporentasche. Und du achtest gar nicht auf den riesigen Ast. "`yWelcher Ast?`t" - `bPTOOOIIIING`b';
					
					$Char->hitpoints = max(1,$Char->hitpoint - 5);
					break;
				case 2:
					$str_out .= 'Dieses kleine gewiefte Ding schlägt Haken wie ein Hase und das in einer Geschwindigkeit die man ihm nie zugetraut hätte. Es tippelt querfeldein und Kreuzfeld-Jakob, hüpft hier auf einen Stein, dort gegen einen Ast und weiter ins Gebüsch. Über soviel Engagement kannst du nur den Hut ziehen. Respekt! DEN holst du nicht mehr ein.';
					break;
				case 3:
					$str_out .= 'Im Tiefflug hechtest du dem kleinen Zappelding hinterher. Über Stock und über Steine springst du letztendlich mit einem gewaltigen Satz ab und gröhlst dabei deine Euphorie über deinen gelungenen Sprung heraus. Für zufällig vorbeilaufende Waldbewohner klingt das dann etwa so: "`yJooooooaaaaaaaaa`t" (Man stelle sich das bitte in Zeitlupe und Dopplereffekt  vor). Es mag vielleicht bescheuert klingen und ganz sicher bescheuert aussehen, aber der Erfolg rechtfertigt die Mittel. Du bekommst den Pilz an seinem Rockzipfel zu fassen und ziehst ihn zu dir heran. Doch so wehrlos wie er aussieht ist er nicht. Seine kleinen Beinchen sind unglaublich spitz und verdammt schnell. Hast du Tattoos? Ja dann kannst du es dir ungefähr vorstellen was er gerade mit deiner Nase macht. Glücklicherweise hast du etwas was er nicht hat: ROHE BRUTALE GEWALT. Ein handfester Klapps lässt das Erdreich erschüttern und du hast endlich den Pilz und deine Ruhe. Wurde ja auch Zeit.';
					
					
					//Einen Pilz aussuchen
					$rand_pilz = array_rand($pilze);
						
					$arr_mushroom['content']['mushrooms'][ $rand_pilz ]++;
						
					item_set($arr_mushroom['id'],array('content'=>utf8_serialize($arr_mushroom['content'])),false,1);
					
					break;
				case 4:
					$str_out .= 'Du rennst dem Ding hinterher, eigentlich der Auffassung, dass du es schnell einholen würdest. Auf diesen dünnen Beinchen kann es doch unmöglich flink vorankommen, oder? Doch du hast dich getäuscht, der Pilz wird immer schneller und schneller, der Abstand zwischen euch wird immer größer. Während du darauf achten musst, nicht über irgendwelche Pflanzen oder Wurzeln zu stolpern, scheint das Pilzwesen überhaupt keine Probleme mit dem unebenen Untergrund zu haben. Aber du kannst doch nicht zulassen, dass dieses Ding dir einfach entkommt? Du läufst schneller, holst deine letzten Kräfte aus dir heraus, doch auch damit schaffst du es nicht, den Abstand zu verringern.';
					break;
				case 5:
					$str_out .= 'Das Ding ist fast aus deinem Blickfeld verschwunden, als du plötzlich siehst, wie es stehen bleibt und dir zuwinkt. Natürlich kannst du es nicht genau wissen, aber es scheint dich verspotten zu wollen. Das ist ja wohl die Höhe! Wütend beschleunigst du noch einmal, doch das Pilzwesen fängt ebenfalls wieder an zu laufen. Das geht so lange, bis du erschöpft stehen bleiben musst, um wieder zu Atem zu kommen. Du nimmst ein paar tiefe Atemzüge, während du zusiehst, wie das Wesen immer kleiner wird. Tja, diesmal hast du dich wohl von einem Pilz abhängen lassen. Gut, dass das keiner mitbekommen hat...';
					break;					
			}
			break;
		}
	case 'fight':
	case 'run':
		{
			$battle=true;
			$fight=true;
			break;
		}
}


if ($battle == true)
{
	include_once ('battle.php');

	if ($victory == true)
	{
		$str_out .= '`b`4Du hast `^'.$badguy['creaturename'].'`4 besiegt.`b`n';
		$badguy=array();
		$Char->badguy='';
		$gold=e_rand(100,500);
		$experience=$Char->level*e_rand(37,99);
		$str_out .= '`#Du erhältst `6'.$gold.' `#Gold!`n';
		$Char->gold+=$gold;
		$str_out .= '`#Du erhältst `6'.$experience.' `#Erfahrung!`n';
		$Char->experience+=$experience;
		addnav('Weiter',$str_filename.'?op=suche');
		Atrahor::$Session['daily']['pilzsuche'] = true;
	}
	elseif ($defeat == true)
	{
		$str_out .= '`4Du wurdest von einem Schwein getötet...nein das klingt lächerlich...von einer Wildsau! ...auch nicht besser. Von einem `bfiesen, finsteren, gemeinen Todesgrunzer`b. Ja, DAS klingt gut...oh Hallo Ramius...mitkommen? Ich? Ok...';
		$badguy=array();
		killplayer(0,0);
		addnews($Char->name.'`5 wurde vom Todesgrunzer besiegt...');
	}
	else
	{
		if ($fight == true)
		{
			fightnav(true,false);
		}
	}
}
else 
{
	output($str_out);
}
page_footer();

?>