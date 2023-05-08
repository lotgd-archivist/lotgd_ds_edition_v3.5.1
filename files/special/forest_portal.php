<?php
/*
* ein seltsames Portal im Wald
* Idee: Raja, Programmierung: Salator
* verwendet Methoden der Dragonslayer-Edition 2.5
* benötigt das Special graeultat.php
*/

if (!isset($session)) exit();

$str_filename = basename(__FILE__);
$session['user']['specialinc']=$str_filename;

function clearing_nav()
{
	global $session;
	output('`n`n`0'.create_lnk('Auf der Lichtung bleiben und nichts tun.','forest.php?op=wait',true,true,'',false,'Nichts tun',1));
	if($session['user']['gravefights']>0)
	{
		output('`n'.create_lnk('Den Wald erkunden gehen','forest.php?op=explore&what=forest',true,true,'',false,'Wald erkunden',1).'
		`n'.create_lnk('Die Stadt erkunden.','forest.php?op=explore&what=village',true,true,'',false,'Stadt erkunden',1));
	}
	output('`n'.create_lnk('Eine Geistheilung versuchen.','forest.php?op=healing',true,true,'',false,'Geistheilung',1).'
	`n`n`&Du hast `^'.$session['user']['deathpower'].'`& Gefallen bei Ramius und noch `^'.$session['user']['gravefights'].'`& Grabkämpfe übrig.`n`n');
	///zum Testen
	admin_output('<form action="forest.php" method="post">
	op=<input type="text" name="op">
	<input type="submit" class="button" value="test">
	</form>',false);
	addnav('','forest.php');
	//*/
}

output('`7');
/** @noinspection PhpUndefinedVariableInspection */
if(isset($_POST['op']) && $access_control->su_check(access_control::SU_RIGHT_DEBUG))
{//zum Testen
	redirect('forest.php?op='.$_POST['op']);
}

switch($_GET['op'])
{
	case '':
	{ //a) Eingang
		output('Du schlenderst schon eine ganze Weile durch den Wald, ohne auf irgend einen interessanten Gegner gestoßen zu sein, als du eine kleine unscheinbare Lichtung entdeckst. Aus irgendeinem Grund weckt sie dein Interesse und du kannst nicht mehr anders, als sie zu erkunden. Trotz der magischen Anziehung, die scheinbar von dieser Lichtung ausgeht, kannst du nichts außergewöhnliches an ihr finden, sie ist wie jede andere Waldlichtung, die du in diesem Wald schon gefunden hast.
		`nWährend du wieder den Zugang der Lichtung suchst, durch welchen du gekommen bist, fällt dir ein kleines, rechteckiges Etwas auf, das in `#hellem blauen Licht`7 stahlt. Langsam gehst du darauf zu und während du dich näherst, scheint es zu sich zu vergrößern, bis es größer ist, als du selbst.
		Nachdem es die Größe erreicht hat, bei der du bequem durchgehen könntest hört es auf zu wachsen und du erkennst, dass es sich um eines der `#Portale`7 handelt, von denen schon viele Krieger gesprochen haben.
		Die Portale sollen vielen zu großem Ruhm und Reichtum verholfen haben, doch weißt du selbst nur zu gut, wie gerne bei diesen Erzählungen die schlechten Seiten übergangen werden und die Tatsache, dass man sein Leben oder seine Seele bei diesem Abenteuer verlieren kann, eine kleine Nebensächlichkeit ist, die einfach ausgelassen wird. Schließlich riskieren Helden immer ihr eigenes Leben.
		`n`nAlso was willst du tun?
		`n`n`0'.create_lnk('Zurück in den Wald und das ganze vergessen','forest.php?op=leave',true,true,'',false,'Zurück in den Wald',1).'
		`n'.create_lnk('Durchs Portal gehen','forest.php?op=enterportal',true,true,'',false,'Portal betreten',1));
		/*
		if($access_control->su_check(access_control::SU_RIGHT_DEBUG))
		{//zum Testen
			addnav('Admin-Test','forest.php?op=corpse_nosearch');
		}
		*/
		break;
	}
	
	case 'leave':
	{ //b) Zurück in den Wald
		output('Du beschließt, dass dir das ganze etwas zu gefährlich ist und du lieber wieder im Wald auf Monsterjagd gehen willst.');
		$session['user']['specialinc']='';
		if(e_rand(0,4)==0)
		{
			output('`nAllerdings brauchst du eine Weile, bis du wieder den Weg zurück in den dir bekannten Wald gefunden hast.
			`n`$Du verlierst eine Runde.');
			$session['user']['turns']--;
		}
		break;
	}
	
	case 'enterportal':
	{ //c) Durchs Portal gehen
		output('Du beschließt, es zu riskieren, egal wie gefährlich es ist. Du willst wissen, was sich auf der anderen Seite des `#Portals`7 befindet und gehst hindurch...
		`n`n`n`n');
		$backup=array(
			'gold'=>$session['user']['gold'],
			'gems'=>$session['user']['gems'],
			'hitpoints'=>$session['user']['hitpoints'],
			'gravefights'=>$session['user']['gravefights'],
			'deathpower'=>$session['user']['deathpower']);
		$session['user']['specialmisc']=utf8_serialize($backup);
		$dice=(isset($_GET['dice'])?$_GET['dice']:e_rand(1,3));
		switch($dice)
		{
			case 1:
			{ //d) Malus-Buff
				output('Als du wieder zu dir kommst, liegst du auf der Lichtung, die du gerade durch das Portal verlassen wolltest.
				`nVerwirrt setzt du dich auf, es hat sich aber nichts geändert, außer das es `4etwas später ist`0, was aber eher daran liegt dass du bewusstlos warst, und nichts mit dem Portal zu tun hat.
				`nLangsam stehst du auf, leicht taumelst du noch, aus irgendeinem Grund hast du höllische `4Kopfschmerzen`7. Vorsichtig fasst du dir an den Kopf und bemerkst eine riesige Platzwunde, als wärst du gegen eine Wand gelaufen. `2Erst jetzt fällt dir ein Baum auf der genau an der selben Stelle steht wo vor kurzem noch das Portal war, oder war das etwa nur Einbildung?
				`n`7Du hoffst, dass es niemand bemerkt hat und verschwindest wieder in den Wald.');
				$session['user']['specialinc']='';
				$session['bufflist']['bigheadache'] = array('name'=>'`4Schwere Kopfschmerzen',
				'rounds'=>20,
				'wearoff'=>'Die Kopfschmerzen haben sich verzogen.',
				'defmod'=>0.92,
				'atkmod'=>1,
				'roundmsg'=>'Du hast noch immer mit deinen Kopfschmerzen zu kämpfen.',
				'activate'=>'defense');
				break;
			}
			
			case 3:
			{ //e) WK-Verlust + Enter
				$turns_loss=e_rand(1,3);
				output('Du kannst es kaum erwarten zu sehen, was auf der anderen Seite ist. Als du aus dem `#Portal`7 trittst, schaust du dich sofort um, auf der Suche nach etwas Besonderem, einem Haufen voller Gold oder Edelsteinen, oder wenigstens einem legendären Gegner, den zu besiegen dir viel Ruhm und Ehre einbringt. Aber nichts von dem entspricht auch nur annähernd dem, was du hier sehen kannst.
				`nEnttäuscht sinkst du auf den Boden, nicht nur dass das Portal verschwunden ist, nein es hat sich auch nichts an der Lichtung geändert, die du gerade verlassen wolltest. Das einzige, was sich verändert hat, ist die Tatsache, dass es schon dunkel geworden ist und du viel Zeit verloren hast.
				`n`4Du verlierst '.$turns_loss.' Runden');
				$session['user']['turns']=max(0,$session['user']['turns']-=$turns_loss);
				addnav('Weiter','forest.php?op=clearing&text=2');
				break;
			}
			
			case 2:
			{ //f) Bonus-Buff "Vorahnung" + Enter
				$turns_plus=e_rand(1,5);
				output('Du kannst es kaum erwarten zu sehen, was auf der anderen Seite ist. Als du aus dem `#Portal`7 trittst, schaust du dich sofort um, auf der Suche nach etwas Besonderem, einem Haufen voller Gold oder Edelsteinen, oder wenigstens einem legendären Gegner, den zu besiegen dir viel Ruhm und Ehre einbringt. Aber nichts von dem entspricht auch nur annähernd dem, was du hier sehen kannst.
				`nVerwundert blickst du dich um. Es hat sich absolut nichts verändert, das einzige, was dir merkwürdig vorkommt, ist, dass die Sonne noch sehr tief steht, als wäre es früh am Morgen. Das Portal ist verschwunden.
				`n`n`^Du hast noch viel Zeit, bevor es Abend wird.
				`nDu bekommst '.$turns_plus.' Runden.
				`n`7Da dir alles merkwürdig bekannt vorkommt, hast du im Kampf einen Vorteil.');
				$session['user']['turns']+=$turns_plus;
				$session['bufflist']['vision'] = array('name'=>'`6Vorahnung',
				'rounds'=>20,
				'wearoff'=>'Deine Vorahnung wirkt nicht mehr.',
				'defmod'=>1,
				'atkmod'=>1.3,
				'roundmsg'=>'Du weißt, was gleich passieren wird.',
				'activate'=>'defense');
				addnav('Weiter','forest.php?op=clearing&text=3');
				break;
			}
			
			
			default:
			output('error in forest_portal: enterportal: dice illegal value: '.$dice);
			$session['user']['specialinc']='';
		} //end switch 1,3
		break;
	}
	
	case 'clearing':
	{ //h) ein Skelettaffe
		$exit=(isset($_GET['dice'])?$_GET['dice']:e_rand(0,4));
		if($exit<3)
		{
			output('Als du dich gerade wieder auf den Weg zurück in den Wald machen willst, fällt dir auf, wie still es ist. `bZu&nbsp;still`b für eine Waldlichtung, kein einziger Vogel zwitschert irgendwo, und auch sonst ist kein einziges Tier zu hören.
			`nLeicht verwirrt gehst du schließlich doch in den Wald, doch kurz darauf stolperst du wieder rückwärts auf die Lichtung zurück, deinen Blick starr auf etwas gerichtet, was wohl mal ein Affe war. Genauer gesagt ist er das jetzt auch noch, aber nur noch sein `&Skelett, DAS LEBT!`7
			`nAus irgendeinem Grund folgt er dir nicht auf die Lichtung und du bist froh, erstmal einen klaren Gedanken fassen zu können. Langsam begreifst du, warum es hier so ruhig ist: Du bist noch immer in den Wäldern '.getsetting('townname','Atrahor').'s, jedoch in der `sDimension des toten Fleisches`7.
			`n`nVollkommen außer dir setzt du dich erstmal auf den Boden, um wieder einen klaren Kopf zu bekommen und überlegst, was du tun könntest.
			`nDu könntest einfach auf der Lichtung zu bleiben und abwarten, ob sich irgendwas tut. Möglicherweise ist das alles nur Einbildung oder ein übler Fluch, der mit der Zeit seine Wirkung verliert. Aber was, wenn nicht? Sehr viel, was dir in irgendeiner Weise helfen könnte, kannst du auf der Lichtung nicht entdecken.
			`nDer Affe war sicher nicht das einzige lebende Skelett hier und ewig werden diese bestimmt nicht vor der Lichtung zurückschrecken. Vielleicht willst du dein Glück lieber im Wald oder in der Stadt versuchen? Immerhin hättest du dann eine Chance, irgendwas zu finden, was dich wieder aus dieser mießlichen Lage befreien kann.
			`n`nAlso?');
			clearing_nav();
		}
		elseif($_GET['text']==2)
		{
			output('Verärgert über die verschwendete Zeit machst du dich wieder auf in den Wald.');
			$session['user']['specialinc']='';
		}
		else
		{
			output(' Auch wenn es nicht das war, was du erwartet hattest, machst du dich doch zufrieden wieder auf den Weg zurück in den Wald.');
			$session['user']['specialinc']='';
		}
		break;
	}
	
	case 'wait':
	{ //j) auf der Lichtung warten
		output('Du legst dich auf die Lichtung und schließt die Augen, in der Hoffnung, das dies alles nur Einbildung war. ');
		$dice=(isset($_GET['dice'])?$_GET['dice']:($session['user']['gravefights']>0?e_rand(1,3):1));
		switch($dice)
		{
			case 1:
			{ // s) Ausgang
				output('Nachdem du einige Zeit geschlafen hast, wachst du wieder auf und alles scheint normal zu sein. Die Vögel zwitschern und auch die Tiere sind keine Skelette mehr.
				`nErleichtert gehst du zurück in den Wald.
				`n`4Da du aber so lange geschlafen hast verlierst du 1 Waldkampf.');
				$backup=utf8_unserialize($session['user']['specialmisc']);
				if ($backup['hitpoints']>0)
				{
					$session['user']['gold']=$backup['gold'];
					$session['user']['gems']=$backup['gems'];
					$session['user']['hitpoints']=$backup['hitpoints'];
					$session['user']['gravefights']=$backup['gravefights'];
					$session['user']['deathpower']=$backup['deathpower'];
				}
				$session['user']['turns']=max(0,$session['user']['turns']-1);
				$session['user']['specialinc']='';
				$session['user']['specialmisc']='';
				break;
			}
			
			case 2:
			{ // u) Kampf
				output('Dein Gefühl sagt dir, dass etwas nicht stimmt. Blitzschnell reißt du deine Augen auf und schaffst es gerade noch, der Waffe auszuweichen die der Affe in der Hand hält. 
				`n`4Moment! Das ist `$DEINE Waffe!');
				addnav('Kämpfen','forest.php?op=battle&opp=monkey');
				break;
			}
			
			case 3:
			{ // t) sterben
				output('Doch kurz darauf reißt du die Augen wegen einem schrecklichen Schmerz wieder auf und erkennst gerade noch, wie dir ein `&Skelettaffe`4 deine eigene Waffe in der Körper rammt`7. Dann wird auch schon alles schwarz vor deinen Augen.');
				addnews('`%'.$session['user']['name'].'`5 wurde von einem Skelettaffen mit der eigenen Waffe gemeuchelt.');
				killplayer(0,2);
				$session['user']['specialmisc']='';
				break;
			}
			
			default:
			{ //
				output('error in forest_portal: wait: dice illegal value: '.$dice);
				$session['user']['specialinc']='';
				break;
			}
			
		
		}
		break;
	}
	
	case 'explore':
	{ //k) l) Dorf/Wald erkunden
		$session['user']['gravefights']--;
		if($_GET['what']=='forest')
		{
			output('Du beschließt, dein Glück im `pWald`7 zu versuchen. Alles ist besser, als untätig hier herumzusitzen... ');
			$dice=e_rand(0,4);
		}
		else
		{
			output('Die `_Stadt`7 zu erkunden ist wohl die beste Möglichkeit, für das Ganze hier eine Lösung zu finden, also machst du dich auf den Weg. ');
			$dice=e_rand(0,10); //bei Änderung auf defaultfall achten, Menge sollte ca 1/3 sein
		}
		$dice=(isset($_GET['dice'])?$_GET['dice']:$dice);
		switch($dice)
		{
			case 0:
			case 10:
			{ //p) Leiche plündern
				output('Du irrst nun schon eine Weile durch '.($_GET['what']=='forest'?'den `pWald':'die `_Stadt').'`7, als du endlich etwas findest. Doch beruhigt es dich in keiner Weise, die `&Leiche`7 eines anderen Besuchers dieser Welt vor dir liegen zu sehen.
				`nHoffentlich passiert dir nicht das gleiche...
				`nAber auch wenn dir dieses Wesen leid tut, solltest du da nicht die Chance ergreifen und es auf etwas Wertvolles durchsuchen?');
				addnav('Durchsuchen','forest.php?op=corpse_search');
				addnav('In Ruhe lassen','forest.php?op=corpse_nosearch');
				break;
			}
			
			case 1:
			{ //m) leichter Kampf
				output('Es dauert nicht lange, da stürzt sich auch schon das nächste `&Skelett`7 auf dich, gekonnt blockst du seinen Angriff, doch bleibst du dann wie angewurzelt stehen...
				`nDas Monster, welches dir gegenübersteht ist nicht irgendeines, nein, es ist das `&Skelett des grünen Drachen`7. 
				`nSollst du wirklich einen Kampf mit ihm riskieren?');
				addnav('Kämpfen','forest.php?op=battle&opp=dragon1');
				//addnav('Wegrennen','forest.php?op=escape');
				addnav('Fressen lassen','forest.php?op=giveup');
				break;
			}
			
			case 2:
			{ //n) schwerer Kampf, bei Sieg Ausgang
				output('Du bist noch nicht weit gelaufen, da greift dich schon wieder das nächste `&Skelett`7 an, ohne große Schwierigkeiten blockst du seinen ersten Schlag und machst dich zum Angriff bereit. Doch stockst du dann, das Monster, das du gerade angreifen willst ist das `&Skelett des grünen Drachen`7.
				`nSollst du wirklich gegen es kämpfen?
				`n`nHalt, was ist das? Hinter dem Skelett kannst du das gleiche `#blaue Glänzen`7 sehen, was dich auf die mysteriöse Lichtung gelockt hat.
				`nAber ohne den Drachen zu besiegen wirst du wohl kaum zum Portal kommen.');
				addnav('Kämpfen','forest.php?op=battle&opp=dragon2');
				//addnav('Wegrennen','forest.php?op=escape');
				addnav('Fressen lassen','forest.php?op=giveup');
				break;
			}
			
			case 5:
			{ //q) Zigeunerzelt Gems plündern
				$backup=utf8_unserialize($session['user']['specialmisc']);
				if($backup['vessa']==1)
				{
					output('Auf deiner Suche nach etwas Nützlichem kommst du am `_Zigeunerzelt`7 vorbei, jedoch sieht es hier noch genauso aus wie vorhin.');
					$session['user']['gravefights']++;
				}
				else
				{
					$gems=e_rand(1,3);
					output('Auf deiner Suche nach etwas Nützlichem kommst du am `_Zigeunerzelt`7 vorbei, kurz riskierst du einen Blick, aber Vessa ist nicht da. Die ganze Einrichtung liegt verstreut herum, nur die Tarot-Karten liegen in Form eines Pentagrams. Inmitten von dem Chaos findest du `#'.$gems.'`7 Edelsteine, welche du natürlich sofort einsteckst.
					`nFieberhaft überlegst du, was du als nächstes tun könntest...');
					$session['user']['gems']+=$gems;
					$backup['vessa']=1;
					$session['user']['specialmisc']=utf8_serialize($backup);
				}
				clearing_nav();
				break;
			}
			
			case 6:
			{ //r) Bank Gold plündern
				$backup=utf8_unserialize($session['user']['specialmisc']);
				if($backup['bank']==1)
				{
					output('Auf deiner Suche nach etwas Nützlichem kommst du an der `_Bank`7 vorbei, noch immer liegt das Gold auf dem Boden. Schade, dass du nicht noch mehr tragen kannst...');
					$session['user']['gravefights']++;
				}
				else
				{
					$gold=e_rand(250,500)*$session['user']['level'];
					output('Auf deiner Suche nach etwas Nützlichem kommst du an der `_Bank`7 vorbei, auch hier ist niemand anzutreffen. Also gehst du hinein, blickst dich kurz um und siehst über den ganzen Boden Goldstücke verstreut. Die braucht hier bestimt keiner mehr, also nimmst du so viel Gold, wie du tragen kannst, mit.
					`nWieder draußen angekommen bist du um `^'.$gold.'`7 Gold reicher.
					`nFieberhaft überlegst du, was du als nächstes tun könntest...');
					$session['user']['gold']+=$gold;
					$backup['bank']=1;
					$session['user']['specialmisc']=utf8_serialize($backup);
				}
				clearing_nav();
				break;
			}
			
			case 7:
			{ // Kneipe, ein Ale trinken
				if($session['bufflist']['101']['rounds']>1)
				{
					output('Auf deiner Suche nach etwas Nützlichem kommst du an der `_Kneipe`7 vorbei, jedoch verspürst du keine Lust, schonwieder ein Ale zu trinken.');
				}
				else
				{
					output('Auf deiner Suche nach etwas Nützlichem kommst du an der `_Kneipe`7 vorbei, und wie auch schon im Wald war auch hier niemand anzutreffen. Also gehst du hinter den Tresen und zapfst dir erstmal ein Ale.
					`nDu fühlst dich lebhaft, aber merkwürdigerweise nicht betrunkener als vorher.
					`nFieberhaft überlegst du, was du als nächstes tun könntest...');
					$session['bufflist']['101'] = array('name'=>'`#Rausch','rounds'=>10,'wearoff'=>'Dein Rausch verschwindet.','atkmod'=>1.25,'roundmsg'=>'Du hast einen ordentlichen Rausch am laufen.','activate'=>'offense');
				}
				$session['user']['gravefights']++;
				clearing_nav();
				break;
			}
			
			case 9:
			{ // Akademie, ein Rezept teilweise lesen
				$arr_tmp = user_get_aei('combos');
				$arr_combo_ids = item_get_combolist(0,ITEM_COMBO_ALCHEMY);
				
				$sql='SELECT combo_id,combo_name,id1,id2,id3,result,type,chance 
				FROM items_combos 
				WHERE type=2 
				AND combo_id NOT IN (0'.db_real_escape_string(implode(',',array_keys($arr_combo_ids))).')
				AND no_entry = 0
				ORDER BY rand() 
				LIMIT 1';
				$result=db_query($sql);
				if(db_num_rows($result)==0)
				{ //schon alle verfügbaren Rezepte bekannt
					output('Du kommst an `_Warchilds Akademie`7 vorbei, findest aber nichts, was du nicht schon kennst.');
					$session['user']['gravefights']++;
					break;
				}
				$row=db_fetch_assoc($result);
				$maxrand = $row['id3']?3:2;
				$ingred_nr = e_rand(1,$maxrand);
				$ingred_id='id'.$ingred_nr;
				$ingred=$row['id'.$ingred_nr];
				if($row['result']>'')
				{
					$result=db_fetch_assoc(db_query('SELECT tpl_name FROM items_tpl WHERE tpl_id="'.$row['result'].'"'));
				}
				else
				{ //Sonderfall Suff, evtl noch weitere
                    $result = array();
					$result['tpl_name']=$row['combo_name'];
				}
				$ingredient=db_fetch_assoc(db_query('SELECT tpl_name FROM items_tpl WHERE tpl_id="'.$ingred.'"'));
				$quality=array('am besten frisch','kann auch getrocknet sein','nicht zu alt','vorher gut abwaschen','mit ein wenig Feenstaub bestreut','eine Woche in grünem Drachenschnaps eingelegt','zu Pulver zerstoßen');
				$q=e_rand(0,6);
					output('Auf deiner Suche nach etwas Nützlichem kommst du an `_Warchilds Akademie`7 vorbei, und wie auch schon im Wald war auch hier niemand anzutreffen. Also gehst hinein, auf der Suche nach Informationen.
				`n`(Weil es im Inneren stockfinster ist, entzündest du eine `tFackel. `YIn deren Schein entdeckst du wenig später ein Pergament auf dem Boden, welches du aufhebst.
				`n`n`tDu hältst deine Fackel dichter an das Pergament und erkennst eine blasse Schrift:
				`n`n`6`c<u>'.$result['tpl_name'].'</u>`c`6...als '.$ingred_nr.'. Zutat nehme man '.$ingredient['tpl_name'].'`6, '.$quality[intval($q)].'.`n
				`n`7Die Seite könnte eine Abschrift aus dem Buch `^"Alchemie heute"`7 sein, welches Petersen nie aus der Hand legt.
				`n`4Verdammt! Du hast deine Fackel `$zu`4 dicht an das Pergament gehalten und es verbrennt, bevor du es komplett lesen konntest.
				`n`7Jedoch hast du auf dem heißen Pergament für kurze Zeit eine Notiz mit unsichtbarer Tinte entdeckt: `T"255 Versuche, '.$row['chance'].' erfolgreich"`7.
				`nFieberhaft überlegst du, was du als nächstes tun könntest...');
				clearing_nav();
				break;
			}
			
			case 666:
			{ //cheat: 2 Grabrunden
				output('Auf deiner Suche nach etwas Nützlichem kommst du am `_Friedhof`7 vorbei, jemand hat in alle Grabsteine gemeißelt "Hier ruht niemand". Du erblickst den Geistschrein, meditierst an diesem und erhältst 2 Grabkämpfe.');
				$session['user']['gravefights']+=3;
				clearing_nav();
				break;
			}
			
			default: //case 3, 4, 8
			{ //o) Ausgang
				output('Du bist kaum 100 Meter gelaufen, als du das `#blaue Schimmern`7 wieder siehst. Du beginnst zu rennen und bleibst dann vor dem Portal stehen. Lange überlegst du nicht, sondern gehst einfach durch. Schlimmer als hier kann es auf der anderen Seite dieses Portals auch nicht sein.');
				addnav('Durch das Portal gehen','forest.php?op=portal');
				break;
			}
			
		}
		
		if($_GET['what']=='village')
		{
			$arr_apokalypse=array(
			'Und es kam Hagel und Feuer, mit Blut vermengt, und fiel auf die Erde; und der dritte Teil der Erde verbrannte, und der dritte Teil der Bäume verbrannte, und alles grüne Gras verbrannte.',
			'Und es stürzte etwas wie ein großer Berg mit Feuer brennend ins Meer, und der dritte Teil des Meeres wurde zu Blut, und der dritte Teil der lebendigen Geschöpfe im Meer starb, und der dritte Teil der Schiffe wurde vernichtet.',
			'Und es fiel ein großer Stern vom Himmel, der brannte wie eine Fackel und fiel auf den dritten Teil der Wasserströme und auf die Wasserquellen. Und der Name des Sterns heißt Wermut. Und der dritte Teil der Wasser wurde zu Wermut, und viele Menschen starben von den Wassern, weil sie bitter geworden waren.',
			'Und es wurde geschlagen der dritte Teil der Sonne und der dritte Teil des Mondes und der dritte Teil der Sterne, so daß ihr dritter Teil verfinstert wurde und den dritten Teil des Tages das Licht nicht schien, und in der Nacht desgleichen. ',
			'Und ich sah, und ich hörte, wie ein Adler mitten durch den Himmel flog und sagte mit großer Stimme: Weh, weh, weh denen, die auf Erden wohnen wegen der anderen Posaunen der drei Engel, die noch blasen sollen!',
			'Und es stieg auf ein Rauch aus dem Brunnen wie der Rauch eines großen Ofens, und es wurden verfinstert die Sonne und die Luft von dem Rauch des Brunnens. Und aus dem Rauch kamen Heuschrecken auf die Erde, und ihnen wurde Macht gegeben, wie die Skorpione auf Erden Macht haben.',
			'Und es wurde ihnen gesagt, sie sollten nicht Schaden tun dem Gras auf Erden noch allem Grünen noch irgendeinem Baum, sondern allein den Menschen, die nicht das Siegel Gottes haben an ihren Stirnen.',
			'Und in jenen Tagen werden die Menschen den Tod suchen und nicht finden, sie werden begehren zu sterben, und der Tod wird von ihnen fliehen.',
			'Und ich hörte eine Stimme aus den vier Ecken des goldenen Altars vor Gott; die sprach zu dem sechsten Engel, der die Posaune hatte: Lass los die vier Engel, die gebunden sind an dem großen Strom Euphrat. Und es wurden losgelassen die vier Engel, die bereit waren für die Stunde und den Tag und den Monat und das Jahr, zu töten den dritten Teil der Menschen.',
			'Und die übrigen Leute, die nicht getötet wurden von diesen Plagen, bekehrten sich doch nicht von den Werken ihrer Hände, dass sie nicht mehr anbeteten die bösen Geister und die goldenen, silbernen, ehernen, steinernen und hölzernen Götzen, die weder sehen noch hören noch gehen können, und sie bekehrten sich auch nicht von ihren Morden, ihrer Zauberei, ihrer Unzucht und ihrer Dieberei.',
			'Steh auf und miss den Tempel Gottes und den Altar und die dort anbeten. Aber den äußeren Vorhof des Tempels lass weg und miss ihn nicht, denn er ist den Heiden gegeben; und die heilige Stadt werden sie zertreten zweiundvierzig Monate lang.',
			'Und die Völker sind zornig geworden; und es ist gekommen dein Zorn und die Zeit, die Toten zu richten und den Lohn zu geben deinen Knechten, den Propheten und den Heiligen und denen, die deinen Namen fürchten, den Kleinen und den Großen, und zu vernichten, die die Erde vernichten.',
			'Und es entbrannte ein Kampf im Himmel: Michael und seine Engel kämpften gegen den Drachen. Und der Drache kämpfte und seine Engel, und sie siegten nicht, und ihre Stätte wurde nicht mehr gefunden im Himmel.'
			);
			$rand=e_rand(0,count($arr_apokalypse)-1);
			output('`n`n`7Jemand hat an eine Hauswand geschmiert: `4'.$arr_apokalypse[intval($rand)].'`0`n`n');
			//viewcommentary('village_ghosttown','Etwas dazuschmieren',25,'schrieb');
		}
		break;
	}
	
	case 'portal':
	{ //w) x) Portal zurück oder ins Totenreich
		if(e_rand(0,2)==0)
		{
			output('Als du wieder zu dir kommst liegst du auf dem Boden und siehst `&gefallene Krieger`7 an dir vorüberziehen. Langsam stehst du auf und blickst dich um.
			`n`$Schließlich kommst du zu dem Schluss, das du wohl im Totenreich gelandet bist.');
			killplayer(0,0);
			addnews('`%'.$session['user']['name'].' `$hat ein Portal ins Totenreich gefunden.');
			$backup=utf8_unserialize($session['user']['specialmisc']);
			$diff=(int)$backup['gravefights']-$session['user']['gravefights'];
			if($diff>0)
			{
				output('`n`7Beim Durchqueren des Portals hast du '.$diff.' Grabkämpfe bekommen.');
				$session['user']['gravefights']+=$diff;
			}
		}
		else
		{
			output('Als du auf der anderen Seite ankommst, schließt sich das `#Portal`7 auch gleich wieder hinter dir.
			`n`2Du erblickst einige Tiere und auch die Vögel sind wieder zu hören. Lange schon hast du dich nicht mehr so darüber gefreut. Erleichtert machst du dich wieder auf den Weg in den Wald.');
			$session['user']['specialinc']='';
		}
		$session['user']['specialmisc']='';
		break;
	}
	
	case 'corpse_search':
	{ //y) ab) Leiche durchsuchen
		if(e_rand(0,1)==0)
		{
			output('Du hattest Pech, die Leiche ist zwar eine Leiche, aber eine Lebende.');
			addnav('Kämpfen','forest.php?op=battle');
		}
		else
		{
			$gold=e_rand(10,1000);
			$gems=e_rand(0,3);
			output('Du beschließt, die Leiche zu durchsuchen. Der Tote braucht seine Reichtümer sicher nicht mehr, also warum solltest du sie nicht sinnvoll nutzen?
					`nNach langem Suchen findest du `#'.$gems.'`7 Edelsteine und `^'.$gold.'`7 Gold.
					`nFieberhaft überlegst du, was du als nächstes tun könntest.');
			$session['user']['gold']+=$gold;
			$session['user']['gems']+=$gems;
			clearing_nav();
		}
		break;
	}
	
	case 'corpse_nosearch':
	{ //z) Leiche in Ruhe lassen
		output('Du lässt die Leiche lieber in Ruhe, am Ende steht sie auch noch auf und will dir etwas tun.
		`nFieberhaft überlegst du, was du als nächstes tun könntest.');
		clearing_nav();
		break;
	}
	
	case 'escape':
	{ // vor dem Kampf wegrennen (wie bei der Hexe? - es IST die Hexe)
		$session['user']['specialinc']='graeultat.php';
		redirect('forest.php?op=escape');
		break;
	}
	
	case 'giveup':
	{ // Fressen lassen
		$favor=20-$session['user']['level'];
		output('Du kommst zu dem Schluss, dass der `@Grüne Drache`7 an sich schon schwer genug zu besiegen ist, und du deshalb wohl kaum eine Chance gegen sein `&Skelett`7 haben wirst. Enttäuscht über den Ausgang dieses Abenteuers lässt du dich auf die Knie sinken, deine Waffe vor dir auf dem Boden liegend, schließt du die Augen und wartest ab was passiert. Kurz spürst du einen stechenden Schmerz, doch dann ist alles weg.
		`n`$Ramius empfängt dich lachend im Totenreich und gewährt dir `^'.$favor.'`$ Gefallen dafür, das du dich so bereitwillig deinem Schicksal gestellt hast, obwohl es deinen Tod bedeutet hat.');
		killplayer(0,2);
		$session['user']['deathpower']+=$favor;
		$session['user']['gravefights']+=3;
		$session['user']['reputation']-=10;
		addnews('`%'.$session['user']['name'].'`4 wurde gefressen, als '.($session['user']['sex']?'sie':'er').' dem `7Skelett des Grünen Drachen`4 begegnete.');
		$session['user']['specialmisc']='';
		break;
	}
	
	case 'battle':
	{ //Kampf-Init
		switch($_GET['opp'])
		{
			case 'monkey':
			{
				$badguy = array('creaturename'=>'Untoter Affe','creaturelevel'=>$session['user']['level']-1,'creatureweapon'=>$session['user']['weapon'],'creatureattack'=>29,'creaturedefense'=>15,'creaturehealth'=>round($session['user']['hitpoints']*0.3),'creaturegold'=>$session['user']['level']*35,'diddamage'=>0);
                break;
			}
			case 'dragon1':
			case 'dragon2':
			{
				$badguy = array('creaturename'=>'Skelett des Grünen Drachen','creaturelevel'=>$session['user']['level'],'creatureweapon'=>'Gigantische Staubwolke','creatureattack'=>45,'creaturedefense'=>25,'creaturehealth'=>300,'creaturegold'=>667,'diddamage'=>0);

				if($_GET['opp']=='dragon2')
				{
					$badguy['exit']=1;
					// First, find out how each dragonpoint has been spent and count those
					// used on attack and defense.
					// Coded by JT, based on collaboration with MightyE
					$points = 0;
					if(is_array($session['user']['dragonpoints']))
					{
						foreach($session['user']['dragonpoints'] as $val)
						{
							if ($val=='at' || $val=='de')
							{
								$points++;
							}
						}
					}

					// Now, add points for hitpoint buffs that have been done by the dragon
					// or by potions!
					$points += (int)(($session['user']['maxhitpoints']-150)/5);

					// Okay.. *now* buff the dragon a bit.
					$points = round($points*0.85,0);

					$atkflux = e_rand(0, $points);
					$defflux = e_rand(0,$points-$atkflux);
					$hpflux = ($points - ($atkflux+$defflux)) * 5;
					$badguy['creatureattack']+=$atkflux;
					$badguy['creaturedefense']+=$defflux;
					$badguy['creaturehealth']+=$hpflux;
					$badguy['creaturehealth']*=1.65;

					$float_forest_bal = getsetting('forestbal',1.5);
					$badguy['creatureattack'] *= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
					$badguy['creaturedefense'] *= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
					$badguy['creaturehealth'] *= 1 + 0.01 * $float_forest_bal * $session['user']['balance_dragon'];
					$badguy['creaturehealth'] = round($badguy['creaturehealth']);
				}
				break;
			}

			default:
			{
				$badguy=db_fetch_assoc(db_query('SELECT * FROM creatures WHERE creaturelevel='.$session['user']['level'].' ORDER BY RAND() LIMIT 1'));
				$badguy['creaturename']='Untoter '.$badguy['creaturename'];
			}
		}
		          if(!isset($level))$level=$session['user']['level'];
		$badguy['creatureexp'] = e_rand(10 + round($level/3),20 + round($level/3));
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		break;
	}
	
	case 'healing':
	{ // Heilen
		$favortoheal = ceil(10 * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])/$session['user']['maxhitpoints']);
		if($_GET['amount']>0)
		{
			$int_amount = max($_GET['amount'],25) / 100;
			$favortoheal = ceil($favortoheal * $int_amount);
			if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
			{
				if ($session['user']['deathpower']>=$favortoheal)
				{
					output('Du richtest ein Gebet an `$Ramius`7 und bittest um Heilung. `$Ramius`7 nennt dich einen Schwächling, aber da du genug Gefallen bei ihm gut hast, gibt er deiner Bitte zum Preis von `4'.$favortoheal.'`) Gefallen nach.');
					$session['user']['deathpower']-=$favortoheal;
					$diff = round(($session['user']['maxhitpoints']-$session['user']['hitpoints'])*$int_amount, 0 );
					$session['user']['hitpoints'] += $diff;
				}
				else
				{
					output('Du richtest ein Gebet an `$Ramius`7 und bittest um Heilung. Doch `$Ramius`7 erhört dich nicht. `4Wenn du nicht genug Gefallen bei Ramius hast kann eine Geistheilung nicht funktionieren.');
				}
			}
			else
			{
				output('Vielleicht solltest du erstmal eine Geistheilung `inötig`i haben, bevor du das versuchst.');
			}
			output('`nFieberhaft überlegst du, was du als nächstes tun könntest.');
		}
		else
		{
			addnav('Geistheilung');
			addnav('Vollständig ('.$favortoheal.' Gefallen)','forest.php?op=healing&amount=100');
			addnav('7?Zu 75% ('.ceil($favortoheal*0.75).' Gefallen)','forest.php?op=healing&amount=75');
			addnav('5?Zu 50% ('.ceil($favortoheal*0.5).' Gefallen)','forest.php?op=healing&amount=50');
			addnav('2?Zu 25% ('.ceil($favortoheal*0.25).' Gefallen)','orest.php?op=healing&amount=25');
			addnav('Aktionen');
		}
		clearing_nav();
		break;
	}
	
	case 'fight':
	{ // Standard-Kampf-op
		$battle=true;
		break;
	}
	
	case 'run':
	{ // Standard-Fliehen-op
		output('Du machst es dir zu Nutze, dass dein Gegner nicht so schnell rennen kann und verschwindest, so schnell du kannst. Fieberhaft überlegst du, was du als nächstes tun könntest.');
		clearing_nav();
		break;
	}
	
	default: //Fehler
	output('error in forest_portal: op missed: '.$_GET['op']);
	$session['user']['specialinc']='';
}

if($battle)
{
	include('battle.php');
	if ($victory)
	{
		$gold=intval($badguy['creaturegold']);
		$exp=intval($badguy['creatureexp']);
		
		switch($badguy['creaturename'])
		{
			case 'Untoter Affe':
			{
				$str_output='Du hast es geschafft, du bist mit dem Leben davongekommen, aber noch immer in dieser verkehrten Welt. Wenigstens hast du deine Waffe wieder, nur was willst du jetzt machen?';
				clearing_nav();
            break;
			}
			
			case 'Skelett des Grünen Drachen':
			{
				if($badguy['exit']==1)
				{
					$str_output='Du hast es geschafft, der Weg zum `#Portal`7 ist frei, ohne noch einmal zurückzublicken machst du dich auf den Weg durch das Portal.
					`nAuf der anderen Seite angekommen verschwindet das `#Portal`7 auch gleich wieder hinter dir.
					`n`2Du erblickst einige Tiere und auch die Vögel sind wieder zu hören, lange schon hast du dich nicht mehr so darüber gefreut. Sofort machst du dich wieder auf den Weg in den Wald.';
					$session['user']['specialinc']='';
				}
				else
				{
					$str_output='`nFieberhaft überlegst du, was du als nächstes tun könntest.';
					clearing_nav();
				}
				break;
			}
			
			default:
			{
				$str_output='`nFieberhaft überlegst du, was du als nächstes tun könntest.';
				clearing_nav();
				break;
			}
		}
		headoutput('`c`b`@Sieg!`0`b`c
		`n`b`$Du hast `%'.$badguy['creaturename'].'`$ bezwungen!`0`b
		`n`7Du findest `^'.$gold.'`7 Gold.
		`nDu bekommst `^'.$exp.'`7 Gefallen bei Ramius.
		`n'.$str_output.'
		`n`n<hr>`n');
		$session['user']['gold']+=$gold;
		$session['user']['deathpower']+=$exp;
	}
	elseif($defeat)
	{
		$sql='SELECT name FROM disciples WHERE master='.$session['user']['acctid'];
		$result=db_query($sql);
		if(db_num_rows($result)==1)
		{
			$row=db_fetch_assoc($result);
		}
		headoutput('`c`b`$Niederlage!`0`b`c
		`n`&`bDu wurdest von `%'.$badguy['creaturename'].'`& besiegt!`b
		`n`$Du bist tot!
		`nDu verlierst 5% deiner Erfahrung.
		'.($row['name']?'`nDein Knappe '.$row['name'].'`$ irrt hilflos durch dieses trostlose Land.':'').'
		`n`n<hr>`n');
		killplayer(0);
		addnews('`%'.$session['user']['name'].'`4 wurde im Zombiewald von `5'.$badguy['creaturename'].'`4 niedergemetzelt.');
		$session['user']['specialmisc']='';
		$session['user']['gravefights']+=3;
	}
	else
	{
		fightnav(true,true);
	}
}
?>
