<?php
/**
 * Das Dorffest von Dragonslayer für Atrahor.de
 * @author Dragonslayer
 * @copyright Dragonslayer for Atrahor
 * Einige Teile vom Halloweenmarkt von Siria/Casiphia übernommen
 */

require_once 'common.php';
$str_filename = basename(__FILE__);
if (!isset($session))
{
	exit();
}

page_header('Das Stadtfest');

// Für Feuerschrein brauchen wir Postlänge
if(isset($_POST['insertcommentary']['party_fireplace'])) {
	$int_fireshrine_post_len = mb_strlen(utf8_preg_replace('/\(.*\)/','',$_POST['insertcommentary']['party_fireplace']));
}

addcommentary();
checkday();

if ($_GET['op']!='profitgame')
{
	//$str_out .= get_title('`2&dArr; `$&dArr; `7&dArr; `$&dArr; `2&dArr; `JD`ja`Gs `gS`yt`/ad`yt`gf`Ge`js`Jt `2&dArr; `$&dArr; `7&dArr; `$&dArr; `2&dArr;');
}
//Each time we reload we get a bit less stuffed
if(Atrahor::$Session['bbq_hunger'] != '' && Atrahor::$Session['bbq_hunger'] > 1)
{
	Atrahor::$Session['bbq_hunger']--;
	if(Atrahor::$Session['bbq_hunger']<0)
	{
		Atrahor::$Session['bbq_hunger']=0;
	}
}
//Each time we reload we gain a bit of our condition back
if(Atrahor::$Session['dance_condition'] != '' && Atrahor::$Session['dance_condition'] > 1)
{
	Atrahor::$Session['dance_condition']--;
	if(Atrahor::$Session['dance_condition']<0)
	{
		Atrahor::$Session['dance_condition']=0;
	}
}

//Säufertod bearbeiten
if (true)//if (getsetting ('lastparty',0)>time() || getsetting('party_force_party',0)==true)
{
	$arr_result = $Char->handleDrunkenness();
	
	if(!is_null_or_empty($arr_result['output']))
	{
		$str_out .= $arr_result['output'];
	}
	
	if($arr_result['died'] == 1)
	{
		output($str_out);
		page_footer();
	}
}


switch ($_GET['op'])
{
	default:
	case 'meadow':
		{
			$str_out = get_title('`JD`ji`Ge `gF`ye`/st`yw`gi`Ge`js`Je').'`/D`yi`ge `GW`ji`Jese, auf der sonst das lautstarke Stadtfest stattfindet, scheint kaum mehr die gleiche zu sein. Hier und dort finden sich allerdings noch Spuren der letzten Feiern; einige Lampions hängen noch in den Wipfeln der großen Bäume, die sich kreisförmig um die Fläche ringen und auch der ein oder andere Krug wurde schlicht am Baumstamm liegen gelassen. Das Gras an diesem Ort ist niedergetreten, weswegen man genau sieht, wo sich Personen bewegten und wo schwere Gegenstände Platz fanden. Allerdings ist es nun ruhig, so kann man jetzt die Vögel zwitschern hören und auch der Wind, der durch die Baumkronen streicht, durchbricht teilweise die S`jt`Gi`gl`yl`/e.`n`n';
			output($str_out);
			$str_out = '';
			viewcommentary('party_main','Über die Wiese schlendern',30,'sagt');
			addnav('Wohin');
			addnav('Schnappers Losstand','dorffest.php?op=profitgame');
			addnav('Z?Zurück zur Stadt','village.php');
			if($access_control->su_check(access_control::SU_RIGHT_DEBUG))
			{
				addnav('P?Das Stadtfest (für SU)','dorffest.php');
			}
			break;
		}
	case '':
		{
			
			$str_out .= Weather::get_weather_text('Dorffest');
			if($Char->marriedto=='4294967295')
			{
				$str_mate = ($Char->sex)?'Seth':'Violet';
				$str_out .= "Schade, dass $str_mate so viel zu tun hat, du hättest nichts gegen ein Tänzchen einzuwenden. Naja, vielleicht später.";
			}
			$str_out .= '`n`n';
			output($str_out);
			$str_out = '';
			viewcommentary('party_main','Auf der Wiese feiern',30,'erzählt');
			addnav('Stadtfest');
			addnav('T?Zum Tanze', 'dorffest.php?op=dance');
			addnav('L?Zum Lagerfeuer','dorffest.php?op=fire');
			addnav('G?Zum Grill','dorffest.php?op=grill');
			addnav('Stände');
			addnav('Schnappers Losstand','dorffest.php?op=profitgame');
			addnav('Feiertagsmützen','dorffest.php?op=caps');
			addnav('Schießbude','dorffest.php?op=orcfield');
			addnav('Horoskop-Automat','horoskop.php');
			addnav('Bretterbude',$str_filename.'?op=bretterbude');
			addnav('Wettzelt',$str_filename.'?op=wettzelt');
			#addnav('Besonderes');
			#addnav('Archivar Nograth','dorffest.php?op=story');

			if($Char->marriedto!=0 && $Char->marriedto != 4294967295)
			{
				addnav('Für Verliebte');
				addnav('Lauschiges Plätzchen suchen','dorffest.php?op=flirt');
			}

			//Special for juggleries
			if($Char->jugglery>0 || $Char->race=='slm')
			{
				addnav('Special');
				addnav('Gaukelei','dorffest.php?op=special&action=jugglery');
			}
			addnav('Zur Stadt');
			addnav('Z?Zurück zur Stadt','village.php');
			break;
		}
	
	case 'bretterbude':
		{
			switch($_GET['act'])
			{
				default:
				case '':
					{
						$str_out .= get_title('`ARoma´s Kussbude');
						$str_out .= '`rAn einer heruntergekommenen Bude bleibst du stehen und bist verwundert. Zwischen all den schönen Zelten und Ständen sah diese Bude eher wie ein Abfallhaufen aus. Du trittst näher heran, denn irgendwie zieht dich diese Bretterbude magisch an. Du umrundest den Verschlag einmal, kannst aber weder dahinter noch davor jemanden entdecken. Auch in der Bude schien niemand zu sein, doch ein leises Summen lässt dir keine Ruhe. Vorsichtig lehnst du dich über den etwas schmutzigen Tresen und zuckst erschrocken zurück. Da war doch jemand drin! `ARoma `rtaucht hinter dem Tresen auf, schlägt verzückt die Hände zusammen und blinzelt dir zu. `A"Ja wen haben wir denn da? Mei, fein, bist du hier um dir einen kostenlosen Kuss abzuholen, ' . $Char->name . '`A?" `rGanz verdattert starrst du ihn an und entdeckst erst jetzt ein kleines Holzschild auf dem mit krakeliger Handschrift geschrieben steht:`n`n`c`ARoma´s Kussbude`nKOSTENLOS!`nEinmaliges Erlebnis!`nFür Männlein oder Weiblein!`nTretet näher!!!`c`n
						 `rEs ist dir sichtlich unangenehm und du überlegst, was du nun tun sollst - Roma küssen oder lieber abhauen?';
						if(Atrahor::$Session['daily']['kuss'] == true)
						{
							$str_out .= '`rDa du nicht noch einmal in die Verlegenheit kommen willst gehst du lieber schnell zurück. Ein Kuss pro Tag ist mehr als genug!';
							addnav('Schnell zurück', $str_filename);
						}
						else
						{
							addnav('Roma küssen', $str_filename.'?op=bretterbude&act=kuss');
							addnav('Lieber zurück zum Markt', $str_filename.'?op=bretterbude&act=weg');
						}
					}
					break;
				case 'kuss':
					$str_out .= '`rDu entschließt dich Roma zu küssen. Immerhin ist der Kuss kostenlos und du hoffst bloß, dass dich niemand dabei sieht. Roma blickt dir verträumt in die Augen. Du lehnst dich über den Tresen und...';
					switch(e_rand(1, 7))
					{
						case 1:
							$str_out .= 'er gibt dir einen laut schmatzenden Kuss. Leider hattest du kein Glück, denn einige Kinder aus der Stadt haben euch beim küssen beobachtet. Kichernd verschwinden die Kinder und du bist dir sicher - Jeder Bürger der Stadt wird bald wissen das du hier warst.';
							addnews('`rEinige Kinder aus der Stadt erzählen, dass ' . $Char->name . ' `ARoma `rauf dem Stadtfest geküsst hat!');
							Atrahor::$Session['daily']['kuss'] = true;							
							$Char->charm -= 4;
							
							addnav('Zum Markt', $str_filename);
							break;
						case 2:
							$str_out .= 'er blickt dich etwas dümmlich an. `A"Mei, du hast aber spröde Lippen, ' . $Char->name . '`A. Na, dich küss ich nicht!"`r Erleichtert ziehst du von dannen, wunderst dich aber, warum dich alle Leute merkwürdig anstarren...';
							addnav('Zum Markt', $str_filename);
							Atrahor::$Session['daily']['kuss'] = true;
							break;
						case 3:
							$str_out .= words_by_sex('er gibt dir einen leidenschaftlichen Kuss. Als es endlich vorbei ist schaut er dich verliebt an und streckt den Arm nach dir aus. `A"Komm her, du [Süßer|Süße], du."`r murmelt er. Das war eindeutig zu viel des Guten und du nimmst die Beine in die Hand und läufst weg. `&"Alles, nur das nicht!"`r murmelst du noch.');
							Atrahor::$Session['daily']['kuss'] = true;
							addnav('Zum Markt rennen', $str_filename);
							break;
						case 4:
							$str_out .= 'er gibt dir einen leidenschaftlichen Kuss. Wider Erwarten genießt du den Kuss, doch `ARoma `rzieht sich von dir zurück. `A"Des langt jetzt aber, sonst verlange ich Reichtum, prunkhafte Gemächer und ein eigenes Königreich von dir..."`r Enttäuscht wendest du dich ab und denkst:`&"Und ich dachte, er fühlt genau wie ich. Schade..."';
							Atrahor::$Session['daily']['kuss'] = true;
							$Char->charm -= 4;						
							addnav('Betrübt zurück zum Markt gehen', $str_filename);
							break;
						case 5:
							$str_out .= 'er gibt dir einen kleinen Schmatzer auf die Wange. Dann dreht er sich zum nächsten Gast vor seiner Bretterbude um und gibt diesem einen ausgiebigen Kuss. `&"Komisch..."`rdenkst du, gehst aber weiter.';
							Atrahor::$Session['daily']['kuss'] = true;
							$Char->charm -= 2;							
							addnav('Zurück zum Markt', $str_filename);
							break;						
						case 6:
							$str_out .= 'er zieht dich über den Tresen, um dich zu vernaschen. Du bist wie betäubt und wehrst dich nicht. Als es endlich vorbei ist schiebt `ARoma `rdich zurück vor die Bude und legt ein süffisantes Lächeln auf und ruft: `A"Mei, das war ein feines Schäferstünchen, der nächste bitte..."`rDu klaubst deine Klamotten zusammen, aber deine Unterhose kannst du einfach nicht finden. `&"Dann muss es eben so gehen"`r murmelst du und verziehst dich.';
							Atrahor::$Session['daily']['kuss'] = true;
							$Char->charm += 2;
							addnav('Zum Markt', $str_filename);
							break;
						case 7:
							$str_out .= 'er gibt dir einen Kuss. Und dann strahlt er dich an, wird rot und flüstert: `A"Mei, du hast mir den heißesten Kuss meines Lebens verschafft! Dafür will ich dich reich belohnen!"`r Er steckt dir Gold und ein paar Edelsteine zu und du fühlst dich gleich sehr viel attraktiver und stolzierst zurück zum Markt.';
							Atrahor::$Session['daily']['kuss'] = true;
							$Char->charm += 5;
							$Char->gold += 250;
							$Char->gems += 2;
							addnav('Zum Markt stolzieren', $str_filename);
							break;
					}
					break;
				case 'weg':
					$str_out .= '`rDu nimmst lieber die Beine in die Hand und versuchst ihm zu entkommen...';
					switch(e_rand(1, 3))
					{
						case 1:
							$str_out .= '`raber er packt dich am Kragen und schleudert dich zu Boden. Ein kleiner Stein auf dem Boden wird dir zum Verhängnis, denn du knallst mit voller Wucht drauf.';
							$Char->kill(0,0);							
							addnews($Char->name . " `rlief vor `ARoma`r weg, stürzte und starb.");
							break;
						case 2:
							$str_out .= '`rAnscheinend bist du schneller als er und verlangsamst deinen Schritt als du wieder auf dem Markt bist.';
							addnav('Zum Markt', $str_filename);
							break;
						case 3:
							$str_out .= '`rAnscheinend bist du schneller als er und verlangsamst deinen Schritt. In dem Moment spürst du eine Hand auf deiner Schulter und resignierst. Du wirst `ARoma`r wohl oder übel doch küssen müssen.';
							addnav('Roma doch küssen', $str_filename.'?op=bretterbude&act=kuss');
							break;
					}
					break;				
			}			
			break;
		}
	
	case 'wettzelt':
		{
			switch($_GET['act'])
			{
				default:
				case '':
				{
					$str_out .= get_title('`COdlos Wettzelt');
					if(Atrahor::$Session['daily']['wetten'] > 3)
					{
						$str_out .= '`COdlo schüttelt betrübt den Kopf und sagt: `BLeider hast du heute schon genug Gold und Edelsteine eingesetzt. Leider kann ich von dir heute keine Wetten mehr annehmen. Komm doch morgen wieder.';
						addnav("Zurück", $str_filename);
					}
					else
					{
						$str_out .= '`CDu trittst auf Odlo´s Wettzelt zu und schaust dich gelegentlich verstohlen um. Zwar liebst du Glückspiele, doch ist es dir unangenehm dabei gesehen zu werden und so legst du einen Schritt zu. Vor einem provisorischem Tresen bleibst du stehen und nickst Odlo zu. Aufgeregt blickst du auf die Tafel, auf der die aktuellen Jackpots vermerkt sind:`n`n
						`c`4Goldjackpot - `b`Q '.getsetting('party_jackpotgold', 0).' `b`4Gold
						`4Edelsteinjackpot - `b`Q '.getsetting('party_jackpotgems', 0).' `b`4Edelsteine`c`n`n
						`CDu überlegst, ob du ein Spiel wagen solltest.';
						
						addnav('Goldspiel', $str_filename.'?op=wettzelt&act=gold');
						addnav('Edelsteinspiel', $str_filename.'?op=wettzelt&act=gems');
						addnav('Zurück zum Markt', $str_filename);
					}
					break;
				}				
				case 'gold':
				{
					if($Char->gold < 1)
					{
						$str_out .= '`CDu hast kein Gold bei dir und wirst von Odlo freundlich aber bestimmt fortgeschickt. `B"Wenn du das nächste Mal ohne Gold hier auftauchst, wirst du dein blaues Wunder erleben!"`C schreit er dir noch hinterher.';
						addnav("Wegrennen", $str_filename);
					}
					else
					{
						$str_out .= '`COdlo führt dich mit einem freundlichen Lächeln in das Zelt. Dort siehst du einen Tisch und einige Stühle. Du nimmst Platz und kurz darauf stellt Odlo dir ein erfrischendes Glas Wasser hin und fragt dich:`B"Nun, bist du bereit zu spielen und dein Glück zu versuchen '.$Char->name.'`B? Was möchtest du setzen?"';
						addnav('Einsatz wählen');
						addnav('500 Gold', $str_filename.'?op=wettzelt&act=give_gold&gold=500');
						addnav('1000 Gold', $str_filename.'?op=wettzelt&act=give_gold&gold=1000');
						addnav('Lieber verschwinden', $str_filename);
					}
					break;
				}
				case 'give_gold':
				{
					$int_gold = (int)$_GET['gold'];
					if($Char->gold < $int_gold)
					{
						$str_out .= '`COdlo blickt dich zornig an:`BDu wagst es dich ohne Gold ins Zelt zu wagen und dir ein Glas Wasser zu erschleichen? Scher dich raus!!!';
						addnav('Zurück', $str_filename);
					}
					else
					{
						$str_out .= '`CDu setzt '.$int_gold.' Gold und hoffst auf einen Erfolg. Durch deinen Einsatz ist der Jackpot erhöht worden und du drückst dir selbst die Daumen. Odlo reicht dir eine Schachtel aus der du nun ein Los ziehst. Mit zittrigen Fingern öffnest du es und es ist...';
						savesetting('party_jackpotgold', getsetting('party_jackpotgold', 0) + $int_gold);
						Atrahor::$Session['daily']['wetten'] += 1;
						switch(e_rand(1, 20))
						{
							case 1:
							case 2:
							case 3:
							case 4:
							case 5:
							case 6:
							case 12:
							case 13:
							case 15:
							case 17:
							case 19:
								$str_out .= 'eine Niete.`nTraurig hälst du Odlo das Los hin und erhebst dich um zu gehen.';
								$Char->gold -= $int_gold;
								break;
							case 7:
							case 8:
							case 9:
							case 10:
							case 11:
							case 14:
							case 16:
							case 18:
								$str_out .= 'ein Kleingewinn!`nOdlo reicht dir deinen Einsatz zurück!';
								break;
							case 20:
								$str_out .= '`4D`QE`qR `^JACK`qP`QO`4T`C!!!`nDu kannst dein Glück kaum fassen und umarmst Odlo glücklich. Dieser überreicht dir `^'.getsetting('party_jackpotgold', 0).'`C Goldstücke und zeigt dir mit einem wissenden Lächeln den Weg nach draußen.';
								$Char->gold += getsetting('party_jackpotgold', 0);
								savesetting('party_jackpotgold', 500);
								break;
						}
						addnav('Zurück', $str_filename);
					}
					break;
				}
				case 'gems':
				{
					$int_gems = (int)$_GET['gems'];
					if($Char->gems < 1)
					{
						$str_out .= '`CDu hast keine Edelsteine bei dir und wirst von Odlo freundlich aber bestimmt fortgeschickt. `B"Wenn du das nächste Mal ohne Edelsteine hier auftauchst wirst du dein blaues Wunder erleben!"`C schreit er dir noch hinterher.';
						addnav('Zurück', $str_filename);
					}
					else
					{
						$str_out .= '`COdlo führt dich mit einem freundlichen Lächeln in das Zelt. Dort siehst du einen Tisch und einige Stühle. Du nimmst Platz und kurz darauf stellt Odlo dir ein erfrischendes Glas Wasser hin und fragt dich:`B Nun, bist du bereit zu spielen und dein Glück zu versuchen '.$Char->name.'`B? Was möchtest du setzen?';
						addnav('Einsatz wählen');
						addnav('1 Edelstein', $str_filename.'?op=wettzelt&act=give_gems&gems=1');
						addnav('3 Edelsteine', $str_filename.'?op=wettzelt&act=give_gems&gems=3');
						addnav('Lieber verschwinden', $str_filename);
					}
					break;
				}
				case 'give_gems':
				{
					$int_gems = (int)$_GET['gems'];
					if($Char->gems < $int_gems)
					{
						$str_out .= '`COdlo blickt dich zornig an:`BDu wagst es dich ohne Edelsteine ins Zelt zu wagen und dir ein Glas Wasser zu erschleichen? Scher dich raus!!!';
						addnav('Zurück', $str_filename);
					}
					else
					{
						$str_out .= '`CDu setzt '.$int_gems.' Edelsteine und hoffst auf einen Erfolg. Durch deinen Einsatz ist der Jackpot erhöht worden und du drückst dir selbst die Daumen. Odlo reicht dir eine Schachtel aus der du nun ein Los ziehst. Mit zittrigen Fingern öffnest du es und es ist...';
						savesetting('party_jackpotgems', getsetting('party_jackpotgems', 0) + $int_gems);
						Atrahor::$Session['daily']['wetten'] += 1;
						switch(e_rand(1, 20))
						{
							case 1:
							case 2:
							case 3:
							case 4:
							case 5:
							case 6:
							case 7:
							case 9:
							case 11:
							case 12:
							case 13:
							case 15:
							case 17:
							case 19:
								$str_out .= 'eine Niete.`nTraurig hälst du Odlo das Los hin und erhebst dich um zu gehen.';
								$Char->gems -= $int_gems;
								break;
							case 8:
							case 10:
							case 14:
							case 16:
							case 18:
								$str_out .= 'ein Kleingewinn!`nOdlo reicht dir deinen Einsatz zurück!';
								break;
							case 20:
								$str_out .= '`4D`QE`qR `^JACK`qP`QO`4T`C!!!`nDu kannst dein Glück kaum fassen und umarmst Odlo glücklich. Dieser überreicht dir `^'.getsetting('party_jackpotgems', 0).' `CEdelsteine und zeigt dir mit einem wissenden Lächeln den Weg nach draußen.';
								$Char->gems += getsetting('party_jackpotgems', 0);
								debuglog('Gewann den Jackpot auf dem Stadtfest ('.getsetting('party_jackpotgems', 0).') Edelsteine.');
								savesetting('party_jackpotgems', 1);
								break;
						}
						addnav('Zurück', $str_filename);
					}
					break;
				}
			}
			
			break;
		}

	case 'caps':
		{
			function ostern($year) //von http://www.stadtaus.com/tutorials/ostern_berechnen.php
			{
				$J = date ("Y", mktime(0, 0, 0, 1, 1, $year));

				$a = $J % 19;
				$b = $J % 4;
				$c = $J % 7;
				$m = number_format (8 * number_format ($J / 100) + 13) / 25 - 2;
				$s = number_format ($J / 100 ) - number_format ($J / 400) - 2;
				$M = (15 + $s - $m) % 30;
				$N = (6 + $s) % 7;
				$d = ($M + 19 * $a) % 30;

				if ($d == 29)
				{
					$D = 28;
				}
				else if ($d == 28 and $a >= 11)
				{
					$D = 27;
				}
				else
				{
					$D = $d;
				}

				$e = (2 * $b + 4 * $c + 6 * $D + $N) % 7;

				$easter = mktime (0, 0, 0, 3, 21, $J) + (($D + $e + 1) * 86400);
				$easter -=172800; //-2 Tage = Karfreitag

				return $easter;
			}

			if (!isset(Atrahor::$Session['capgold']))
			{
				Atrahor::$Session['capgold']=rand(50,100);
			}

			$caps[1]=array(
			'date'=>'04-09',
			'time'=>86400,
			'name'=>'Jahrestagmütze',
			'info'=>'Die legendäre Atrahor-Mütze zum Jahrestag. Diese ist vom '.(date('Y')-2004).'. Jahrestag.'
			);

			$caps[2]=array(
			'date'=>'12-24',
			'time'=>(86400*2),
			'name'=>'Weihnnachtsmütze',
			'info'=>'Rot mit weißem Bommel. Eben Standard. So sieht man wie ein Weihnachtsmann aus und fühlt sich auch so.'
			);

			$caps[3]=array(
			'date'=>'12-24',
			'time'=>(86400*2),
			'name'=>'Rentiergeweih',
			'info'=>'Mit solchen Hörnern auf dem Kopf fühlt man sich wie ein großes Tier.'
			);
			//Ostern
			//output( date("Y-m-d", ostern(date("Y"))));
			$caps[4]=array(
			'date'=>date("m-d", ostern(date("Y"))),
			'time'=>(86400*4),
			'name'=>'Hasenohren',
			'info'=>'Wunderbare Osterohren, die weich und plüschig sind.'
			);
			//Standard
			$caps[5]=array(
			'date'=>date('m-d'),
			'time'=>86400,
			'name'=>'Stadtfestmütze',
			'info'=>'So muss das sein! Ein Zeichen der Zeit prägt diese Mütze: Ein Humpen Ale.'
			);

			if (!empty($_GET['cap']) && $Char->gold>=Atrahor::$Session['capgold'])
			{
				$str_out .= '`n`n`@Du lässt dir den Kopf vermessen und der Kürschner macht sich sofort ans Werk. Ehe du dich versiehst, hast du deine eigene Mütze.';

				$item['tpl_name']=$caps[$_GET['cap']]['name'];
				$item['tpl_description']=$caps[$_GET['cap']]['info'];
				$item['tpl_value1']=2;
				$item['tpl_gold']=0;
				$item['tpl_gems']=0;
				item_add($Char->acctid,'partycap',$item);

				$Char->gold-=Atrahor::$Session['capgold'];
				savesetting('dorffestmuetze',$Char->login);
			}
			elseif (!empty($_GET['cap']))
			{
				$str_out .= '`n`n`tDu möchtest schon eine Mütze bezahlen, aber da fällt dir ein, dass du zu wenig Gold dabei hast.
			Eine Mütze kostet heute `^'.Atrahor::$Session['capgold'].' Gold`t und der Kürschner lässt sich auf keine Preisverhandlung ein.';
			}
			else
			{
				$str_out .= '`tDu entdeckst einen netten Stand, recht groß und festlich, mit verschiedenen Mützen. Jede für sich ist ein Unikat in seiner Verarbeitung und wohl auf jeden abgestimmt. Gerade siehst du, wie sich '.getsetting('dorffestmuetze','Mikay Kun').' eine Mütze anfertigen lässt.`n
			`n
			Sofort möchtest du auch eine haben.`n
			`n
			Der Besitzer des Standes meint, dass er dir für nur `^'.Atrahor::$Session['capgold'].' Gold`t eine Mütze produzieren kann.';
			}

			addnav('Mützen');

			for ($i=1;$i<=count($caps);$i++)
			{
				$start=strtotime(date('Y').'-'.$caps[$i]['date']);
				$end=($start+$caps[$i]['time']);

				if (date('U')>=$start && date('U')<=$end)
				{
					addnav($caps[$i]['name'],'dorffest.php?op=caps&cap='.$i);
				}
				/*elseif ($Char->prefs['caps'][$i]==true)
				{ addnav($caps[$i]['name'],'dorffest.php?op=caps&cap='.$i); }*/
			}

			addnav('Zurück');
			addnav('Zum Stadtfest','dorffest.php');
			break;
		}

		// Losestand von Schnapper für Goldpresse
	case 'profitgame':
		{
			addnav('Losstand');

			if ($_GET['uac']=='buy') // Kaufen eines Loses
			{
				if ($Char->gold>=100)
				{
					Atrahor::$Session['daily']['schnapperlot']++;
					$rand=mt_rand(1,12);
					if(Atrahor::$Session['daily']['schnapperlot']>50)
					{
						$rand=mt_rand(1,Atrahor::$Session['daily']['schnapperlot']);
					}
					switch($rand)
					{
						case 1:
							$los='`wblaues';
							$losmsg='...es ist ein Gewinn! Hurra! Schnell zeigst du Schnapper das Los und er übergibt dir etwas. Es ist eine Mückenfalle.';

							$item=item_get('tpl_id="mueckfalle" AND owner='.$Char->acctid);
							if($item) //hochzählen damit die Datenbank nicht zugemüllt wird
							{
								$item['value1']++;
								$item['name']=$item['value1'].' Mückenfallen';
								$item['gold']=min($item['value1']*25,1000);
								item_set('id='.$item['id'],$item);
							}
							else
							{
								item_add($Char->acctid, 'mueckfalle');
							}
							break;

						case 2:
							$los='`@grünes';
							$losmsg='...es ist ein Gewinn! Hurra! Schnell zeigst du Schnapper das Los und er übergibt dir etwas. Es ist ein Stück Katzengold.';

							$item=item_get('tpl_id="katzengold" AND owner='.$Char->acctid);
							if($item)
							{
								$item['value1']++;
								$item['name']=$item['value1'].' Katzengold';
								$item['gold']=$item['value1']*75;
								item_set('id='.$item['id'],$item);
							}
							else
							{
								item_add($Char->acctid, 'katzengold', $item);
							}
							break;

						case 10:
						case 11:
						case 12:
							$los='`^goldenes';
							{

								if(e_rand(1,6) != 1) {
									$los='`$rotes';
									$losmsg='...es ist eine Niete! So ein Mist. Nunja, vielleicht wird es beim nächsten Mal besser.';
								}
								else {
									$losmsg="...es ist ein Gewinn! Hurra! Schnell zeigst du Schnapper das Los und er übergibt dir ein Säckchen Gold. Beim genauen Hinschauen bemerkst du, dass es gesunde Getreidetaler sind. Da du ein wenig hungrig bist, nimmst du dir gleich alle und verdrückst sie. Sie sind voller Energie und du fühlst dich bereit, dem Wald nochmal einen Besuch abzustatten, um etwas die Menge aufzumischen.`n`n`#Du erhältst 1 Waldkampf.";
									debuglog('Gewann WK bei Schnapper');
									$Char->turns++;
								}

							}
							break;

						default:
							$los='`$rotes';
							$losmsg='...es ist eine Niete! So ein Mist. Nunja, vielleicht wird es beim nächsten Mal besser.';
							break;
					}

					$str_out .= get_title('`tSchnappers Losstand').'`q"Danke für dein Gold und hier, nimm dir ein Los!"`t, sagt Schnapper geradezu überfreundlich. Aber ohne dies auch zu kommentieren, greifst du in den Eimer. Schnell ziehst du ein Los herraus.`n`nEs ist ein '.$los.'`t Los. Mal schauen, was es bringt. Langsam öffnest du es und...`n`n'.$losmsg;

					$Char->gold-=100;

					addnav('Nochmal ziehen','dorffest.php?op=profitgame&uac=buy',false,false,false,true,(Atrahor::$Session['daily']['schnapperlot']==100?'Willst du dich selbst in den Ruin treiben?':''));
					if(getsetting ('lastparty',0)>time())
					{
						addnav('Stand verlassen','dorffest.php');
					}
					else
					{
						addnav('Stand verlassen','dorffest.php?op=meadow');
					}
				}
				else
				{
					$str_out .= get_title('`tSchnappers Losstand').'`tDu möchtest dein Glück versuchen und hast bereits ein Los in der Hand. Doch Schnapper fragt erst, ob du auch Gold dabei hast, welches die Kosten deckt. Deinem Gesichtsausdruck zufolge, entreißt dir Schnapper das Los und packt es zurück. `q"Komm zurück, wenn du Gold hast!"`t. Mit diesen Worten verscheucht dich Schnapper.';
					if(getsetting ('lastparty',0)>time())
					{
						addnav('Stand verlassen','dorffest.php');
					}
					else
					{
						addnav('Stand verlassen','dorffest.php?op=meadow');
					}
				}
			}

			else // Standardtext
			{
				$str_out .= get_title('`tSchnappers Losstand').'Du entdeckst Schnapper etwas am Rande stehen. Laut schreiend versucht er seine Ware unter die Leute zu bringen. Doch diese Idee ist mal was anderes.`n`n`q"KAUFT LOSE! JEDES LOS IST EIN GEWINN!".`t`n`nDen Rest flüstert er nur leise, aber es heißt wohl: Zumindestens für mich. Damit ist es mal wieder klar. Der Satz "Kauft Lose! Jedes Los ein Gewinn, zumindestens für mich!" lässt dich vorsichtig sein, während du den Stand ansteuerst. Du denkst: Einen Versuch ist es Wert. Doch so ein Schlitzohr wie Schnapper ist alles zu zutrauen. Selbst die Preise sind ein Wunder für sich: `^100 Goldstücke `tdas Los. Eine nette Summe!`n`n`#Was möchtest du tun?';

				addnav('1 Los kaufen','dorffest.php?op=profitgame&uac=buy');
				if(getsetting ('lastparty',0)>time())
				{
					addnav('Stand verlassen','dorffest.php');
				}
				else
				{
					addnav('Stand verlassen','dorffest.php?op=meadow');
				}
			}
			break;
		}

	case 'flirt':
		{
			$query_result = db_query("SELECT name, sex FROM accounts WHERE acctid = ".$Char->marriedto);
			$arr_mate = db_fetch_array($query_result);
			$str_out .= "`2 Als du inmitten der tanzenden Menge ".$arr_mate[0]."`2 erspähst, macht dein Herz einen Sprung! Voller Freude
		lauft ihr euch entgegen und ergreift euch bei den Händen.`n
		Ein Blick genügt und ihr versteht euch. Bereits nach kurzer Zeit habt ihr die ausgelassene Menge hinter euch gelassen und befindet euch ein Stück tief im friedlichen Wald direkt am Stadtrand. Leicht könnt ihr noch den Klängen der Musik lauschen, doch euer Interesse gilt eigentlich etwas anderem.`n
		Als der Mond euch in weiche Schatten hüllt, bemerkt ihr, dass ihr völlig allein auf einer wunderschönen kleinen Lichtung steht - Wie herrlich!`n`n";

			$str_out .= "Dieser Platz ist NUR für euch beide, ihr seid hier völlig ungestört!`n`n";

			addnav("Wege");
			addnav("Z?Zurück","dorffest.php");

			//Little disturbance by another couple, but only little chance to take place
			switch(e_rand(0,300))
			{
				case 300:
					$arr_names = db_get('SELECT a1.name AS name1, a2.name AS name2 FROM accounts a1 LEFT JOIN accounts a2 ON (a1.marriedto = a2.acctid) WHERE a1.marriedto != 0 AND a1.marriedto != POW(2,32)-1 ORDER  BY RAND() LIMIT 1;');

					if($arr_names !== null)
					{
						$str_out .= "`$ Plötzlich raschelt etwas im Gebüsch! Ihr zuckt erschrocken zusammen und könnt erkennen, wie sich ".$arr_names['name1']." `$ und ".$arr_names['name2']." `$ gemeinsam durch das Gebüsch schleichen`n`n
					`4 Ihr grinst euch gegenseitig an... was die beiden wohl gesucht haben?";
					}
					break;
			}
			output($str_out);
			$str_out = '';

			//Generate a unique commentary ID which only those two can read
			$temp_array = array($Char->marriedto,$Char->acctid);
			sort($temp_array);

			$id_for_party_flirt = 'partyflit_'.implode('',$temp_array);
			// Private Kommentare
			viewcommentary($id_for_party_flirt,"Flüstern",30,"flüstert",false,true,false,false,false,true,2);
			break;
		}

	case 'dance':
		{
			$str_out .= '`2Du trittst auf die rappelvolle Tanzfläche und willst dem anderen Geschlecht mal so richtig zeigen, was Sache ist!`n
		Als die Musik aufspielt beginnst du, wie alle anderen auch, mit einem gewagten Tanz.';

			addnav('Tanzen');
			addnav('Imponieren','dorffest.php?op=dancefloor&action=posing');
			addnav('Ruhiger Tanz','dorffest.php?op=dancefloor&action=gossip');


			//Specials for special charakters
			if($Char->thievery>0 || $Char->race=='vmp' || $Char->race=='eng')
			{
				addnav('Special');
				if($Char->thievery>0)
				{
					addnav('Tänzer bestehlen','dorffest.php?op=special&action=steal');
				}

				if($Char->race=='vmp')
				{
					addnav('Opfer aussaugen','dorffest.php?op=special&action=suck');
				}

				if($Char->race=='eng')
				{
					addnav('Herumfliegen','dorffest.php?op=special&action=fly');
				}
			}

			addnav('Wege');
			addnav('Z?Zurück','dorffest.php');
			break;
		}

		//Add some special events for special charkters
	case 'special':
		{
			switch($_GET['action'])
			{
				case 'steal':
					if(Atrahor::$Session['specialtries']<10)
					{
						if($_GET['fightresult'] == 'victory')
						{
							
							$str_out .= '`b`4Du hast `^'.Atrahor::$Session['battlewrapper_badguy']['creaturename'].'`4 besiegt.`b`n';
							$gold=e_rand(100,500);
							$experience=$Char->level*e_rand(37,99);
							$str_out .= '`#Du erhältst `6'.$gold.' `#Gold!`n';
							$Char->gold+=$gold;
							$str_out .= '`#Du erhältst `6'.$experience.' `#Erfahrung!`n';
							$Char->experience+=$experience;
						}
						elseif($_GET['fightresult'] == 'defeat')
						{
							$str_out .= '`4Als du auf dem Boden aufschlägst, dreht sich  `^'.Atrahor::$Session['battlewrapper_badguy']['creaturename'].'`4 um und tanzt weiter.';
							$badguy=array();
							killplayer(0,0);
							addnews('`^'.$Char->name.'`5 hat auf dem Stadtfest einen Gegner unterschätzt!');
						}
						else
						{
							//Chance to steal and to fight somebody is rather high
							switch(e_rand(1,5))
							{
								case 1:
									$str_out .= 'Es schaut gerade niemand hin, wie zufällig rempelst du jemanden an und wie zufällig fallen dir einige Goldmünzen in die Hand';
									$Char->gold+=e_rand(50,200);
									addnav('Wege');
									addnav('Z?Zurück','dorffest.php?op=dance');
									break;
								case 5:
									$query_result = db_query('SELECT name,level,weapon,attack,defence,hitpoints from accounts order by rand() Limit 1');
									$arr_result_user = db_fetch_array($query_result);

									$badguy = array(
									'creaturename'=>$arr_result_user['name']
									,'creaturelevel'=>$arr_result_user['level']
									,'creatureweapon'=>$arr_result_user['weapon']
									,'creatureattack'=>$arr_result_user['attack']
									,'creaturedefense'=>$arr_result_user['defence']
									,'creaturehealth'=>$arr_result_user['hitpoints']
									,'diddamage'=>0
									,'linkwin'=>$str_filename.'?op=special&action=steal&fightresult=victory'
									,'linkdefeat'=>$str_filename.'?op=special&action=steal&fightresult=defeat');

									$userattack=$Char->attack+e_rand(1,3);
									$userhealth=round($Char->hitpoints);
									$userdefense=$Char->defense+e_rand(1,3);
									$badguy['creaturelevel']=$Char->level;
									$badguy['creatureattack']+=($userattack-4);
									$badguy['creaturehealth']+=$userhealth;
									$badguy['creaturedefense']+=$userdefense;
									$Char->badguy=utf8_serialize($badguy);
									$str_out .= '`2Verdammt, dein Opfer hat dich bemerkt!';

									addnav('Kämpfe!!!','battlewrapper.php?op=fight');
									output($str_out);
									page_footer();
									break;
								default:
									$str_out .= '`2 Hm, es schaut gerade zufällig jemand in deine Richtung, du lässt es wohl lieber bleiben.';
							}
						}
					}
					if($Char->alive == 1)
					{
						addnav('Wege');
						if(Atrahor::$Session['specialtries']<10)
						{
							Atrahor::$Session['specialtries']++;
							addnav('N?Nochmal versuchen','dorffest.php?op=special&action='.$_GET['action']);
						}
						else
						{
							$str_out .= '`n`2 Du denkst dir, dass es besser wäre, es erst mal bleiben zu lassen, sonst schöpft noch jemand Verdacht.';
						}
						addnav('Z?Zurück','dorffest.php?op=dance');
					}
					break;
				case 'suck':
					//chance to suck and fight somebody is rather high
					if(Atrahor::$Session['specialtries']<10)
					{
						if($_GET['fightresult'] == 'victory')
						{
							$str_out .= '`b`4Du hast `^'.Atrahor::$Session['battlewrapper_badguy']['creaturename'].'`4 besiegt.`b`n';
							$gold=e_rand(100,500);
							$experience=$Char->level*e_rand(37,99);
							$str_out .= '`#Du erhältst `6'.$gold.' `#Gold!`n';
							$Char->gold+=$gold;
							$str_out .= '`#Du erhältst `6'.$experience.' `#Erfahrung!`n';
							$Char->experience+=$experience;
						}
						elseif($_GET['fightresult'] == 'defeat')
						{
							$str_out .= '`4Als du auf dem Boden aufschlägst, dreht sich  `^'.Atrahor::$Session['battlewrapper_badguy']['creaturename'].'`4 um und tanzt weiter.';
							$badguy=array();
							killplayer(0,0);
							addnews('`^'.$Char->name.'`5 hat auf dem Stadtfest einen Gegner unterschätzt!');
						}
						else
						{
							//Chance to steal and to fight somebody is rather high
							switch(e_rand(1,5))
							{
								case 1:
									$str_out .= 'Es schaut gerade niemand hin, wie zufällig rempelst du jemanden an und wie zufällig beißt du deinem Opfer unauffällig in den Hals... Du hast halt Übung darin!`n...oder sie sind halt schon etwas angetrunken!';
									$Char->hitpoints+=e_rand(25,75);
									addnav('Wege');
									addnav('Z?Zurück','dorffest.php?op=dance');
									break;
								case 5:
									$query_result = db_query('SELECT name,level,weapon,attack,defence,hitpoints from accounts order by rand() Limit 1');
									$arr_result_user = db_fetch_array($query_result);

									$badguy = array(
									'creaturename'=>$arr_result_user['name']
									,'creaturelevel'=>$arr_result_user['level']
									,'creatureweapon'=>$arr_result_user['weapon']
									,'creatureattack'=>$arr_result_user['attack']
									,'creaturedefense'=>$arr_result_user['defence']
									,'creaturehealth'=>$arr_result_user['hitpoints']
									,'diddamage'=>0
									,'linkwin'=>$str_filename.'?op=special&action=suck&fightresult=victory'
									,'linkdefeat'=>$str_filename.'?op=special&action=suck&fightresult=defeat');

									$userattack=$Char->attack+e_rand(1,3);
									$userhealth=round($Char->hitpoints);
									$userdefense=$Char->defense+e_rand(1,3);
									$badguy['creaturelevel']=$Char->level;
									$badguy['creatureattack']+=($userattack-4);
									$badguy['creaturehealth']+=$userhealth;
									$badguy['creaturedefense']+=$userdefense;
									$Char->badguy=utf8_serialize($badguy);
									$str_out .= '`2Verdammt, dein Opfer hat dich bemerkt!';

									addnav('Kämpfe!!!','battlewrapper.php?op=fight');
									output($str_out);
									page_footer();
									break;
								default:
									$str_out .= '`2 Hm, es schaut gerade zufällig jemand in deine Richtung, du lässt es wohl lieber bleiben.';
							}
						}
					}
					if($Char->alive == 1)
					{
						addnav('Wege');
						if(Atrahor::$Session['specialtries']<10)
						{
							Atrahor::$Session['specialtries']++;
							addnav('N?Nochmal versuchen','dorffest.php?op=special&action='.$_GET['action']);
						}
						else
						{
							$str_out .= '`n`2 Du denkst dir, dass es besser wäre, es erst mal bleiben zu lassen, sonst schöpft noch jemand Verdacht.';
						}
						addnav('Z?Zurück','dorffest.php?op=dance');
					}
					break;
				case 'jugglery': //Gaukler-Special

					if(Atrahor::$Session['specialtries']<10)
					{
						if($_GET['fightresult'] == 'victory')
						{
							$str_out .= '`b`4Du hast `^'.Atrahor::$Session['battlewrapper_badguy']['creaturename'].'`4 besiegt.`b`n';
							$gold=e_rand(100,500);
							$experience=$Char->level*e_rand(37,99);
							$str_out .= '`#Du erhältst `6'.$gold.' `#Gold!`n';
							$Char->gold+=$gold;
							$str_out .= '`#Du erhältst `6'.$experience.' `#Erfahrung!`n';
							$Char->experience+=$experience;
						}
						elseif($_GET['fightresult'] == 'defeat')
						{
							$str_out .= '`4Als du auf dem Boden aufschlägst, dreht sich  `^'.Atrahor::$Session['battlewrapper_badguy']['creaturename'].'`4 um und tanzt weiter.';
							$badguy=array();
							killplayer(0,0);
							addnews('`^'.$Char->name.'`5 hat auf dem Stadtfest einen Gegner unterschätzt!');
						}
						else
						{
							switch(e_rand(1,6))
							{
								case 1:
								case 2:
									$action=e_rand(1,8);
									$place=e_rand(1,3);
									$arr_place=array('village','party_dancefloor','party_fireplace','party_main');
									switch($action){
										case 1:
											$str_out .= '`2Du erzählst die Geschichte `@Wie der König sein Pferd von hinten küsste`2 und erheiterst die Umstehenden. Einige Goldmünzen landen auf deinem Teller.';
											$msg='Ein Barde erzählt amüsant-pikante Geschichten über die Herrscher des Landes.';
											if($place==1) $place=e_rand(2,3);
											break;
										case 2:
											$str_out .= '`2Du nimmst deine Laute und spielst eine mitreißende Melodie. Die Anwesenden spenden dir reichlich Beifall und ein paar Goldmünzen.';
											if($place==1) $msg='Ein Lautenspieler stört die Musik von Seth und bringt einige Tänzer aus dem Takt.';
											break;
										case 3:
											$str_out .= '`2Du jonglierst eine Weile mit brennenden Fackeln und gibst deine Künste als Feuerspucker zum Besten. Die Umstehenden sind begeistert und werfen dir ein paar Goldmünzen zu.';
											$msg='Am Rande des Platzes gibt ein Feuerspucker seine Künste zum Besten.';
											break;
										case 4:
											$str_out .= '`2Bunt geschminkt als Clown ziehst du über den Festplatz. Die Kinder lachen über deine Späße und ein paar Goldmünzen fliegen dir zu.';
											$msg='Heiteres Lachen dringt von einer Gruppe Kinder, die einen Clown umringen, herüber.';
											break;
										case 5:
											$str_out .= '`2Du erzählst die Geschichte `@Wie du dich an deinen Haaren aus dem Sumpf gezogen hast`2. Zwar weißt du, dass es niemals so war, bekommst aber trotzdem ein paar Goldmünzen dafür.';
											$msg='Ein Barde erzählt unglaubliche Geschichten, die er in den Dunklen Landen erlebt hat.';
											break;
										case 6:
											$str_out .= '`2Du kannst eine hübsche Elfe überreden, dir als Partner beim Messerwerfen zu dienen. Die Umstehenden sind begeistert und werfen dir ein paar Goldmünzen zu.';
											$msg='Ein Messerwerfer demonstriert seine Zielgenauigkeit und lässt seine Messer dicht neben einer hübschen Elfe in eine Holzwand fliegen.';
											break;
										case 7:
											$str_out .= '`2Nach einiger Zeit des Wartens triffst du auf einige andere Gaukler, mit denen du einen aufwändigen Tanz geprobt hast. Ihr begebt euch auf die Bühne und führt den Tanz vor. Die Anwesenden applaudieren begeistert und werfen euch Goldmünzen zu.';
											$msg='Auf der Bühne beeindruckt eine Tänzergruppe mit einem aufwändig einstudierten Tanz.';
											break;
										default:
											$str_out .= 'Du reißt einige Possen und erhältst ein paar Goldmünzen dafür.';
									}
									if($msg!='')
									{
										insertcommentary(1,'/msg '.$msg,$arr_place[$place]);
									}
									$str_out .= '`n'.$action.' '.$place.' '.$arr_place[$place];
									output($str_out);
									$str_out = '';
									viewcommentary($arr_place[$place]);
									$Char->gold+=e_rand(50,200);
									break;
								case 5:
									$query_result = db_query('SELECT name,level,weapon,attack,defence,hitpoints from accounts order by rand() Limit 1');
									$arr_result_user = db_fetch_array($query_result);
	
									$badguy = array(
									'creaturename'=>$arr_result_user['name']
									,'creaturelevel'=>$arr_result_user['level']
									,'creatureweapon'=>$arr_result_user['weapon']
									,'creatureattack'=>$arr_result_user['attack']
									,'creaturedefense'=>$arr_result_user['defence']
									,'creaturehealth'=>$arr_result_user['hitpoints']
									,'diddamage'=>0
									,'linkwin'=>$str_filename.'?op=special&action=suck&fightresult=victory'
									,'linkdefeat'=>$str_filename.'?op=special&action=suck&fightresult=defeat');
	
									$userattack=$Char->attack+e_rand(1,3);
									$userhealth=round($Char->hitpoints);
									$userdefense=$Char->defense+e_rand(1,3);
									$badguy['creaturelevel']=$Char->level;
									$badguy['creatureattack']+=($userattack-4);
									$badguy['creaturehealth']+=$userhealth;
									$badguy['creaturedefense']+=$userdefense;
									$Char->badguy=utf8_serialize($badguy);
	
									$str_out .= '`2Du willst gerade anfangen zu musizieren, als du in die Klinge von einem Kulturbanausen blickst!';
									addnav('Kämpfe!!!','battlewrapper.php?op=fight');
									output($str_out);
									page_footer();
									break;
								default:
									$str_out .= '`2Du suchst einen Partner, mit dem du deine Künste als Messerwerfer vorführen kannst. Leider ist niemand bereit sich dafür zur Verfügung zu stellen.';
							}
						}
					}
	
					if($Char->alive == 1)
					{
						addnav('Wege');
						if(Atrahor::$Session['specialtries']<10)
						{
							Atrahor::$Session['specialtries']++;
							addnav('N?Nochmal versuchen','dorffest.php?op=special&action='.$_GET['action']);
						}
						else
						{
							$str_out .= '`2Du hast an diesem Abend schon dein Bestes gegeben, mit mäßigem Erfolg. Und bevor dich der verwöhnte Pöbel lyncht, hältst du es für besser, erst mal Pause zu machen.';				}
							addnav('Z?Zurück','dorffest.php?op=dance');
					}
				break;
				case 'fly': //especially for Luthein *g*
					$str_out .= '`2Du fliegst ein wenig in der Gegend herum, weil das Engel nun mal so machen.';
					addnav('Zurück','dorffest.php?op=dance');
					break;
				default:
					break;
			}
			break;
		}

	case 'dancefloor':
		{
			switch($_GET['action'])
			{
				case 'gossip':
					$str_out .= '`2 Du tanzt ruhig und gelassen mit einigen Bekannten und unterhältst dich nett.`n`n';
					output($str_out);
					$str_out = '';
					viewcommentary('party_dancefloor','Beim tanzen unterhalten',30,'sagt');
					break;
				default:
					if(Atrahor::$Session['dance_condition']>70)
					{
						$str_out .= '`2 Deine Füße tun weh - Du kannst bestimmt nicht so schnell wieder tanzen... Erst mal eine kleine Pause am Feuer? Aber auf jeden Fall was ruhiges! Mit der Zeit wirst du dich schon erholen.';
						break;
					}
					switch(e_rand(1,20))
					{
						case 1:
							$str_out .= '`2Bei einem gewagten Manöver verdrehst du dir das Knie und PLAUTZ liegst du auf der Nase... Naja, das können wir aber besser!
						`nZum Glück hat es niemand gesehen, so dass du ohne Peinlichkeiten aufstehen und weiter machen kannst.';
							Atrahor::$Session['dance_condition']+=20;
							break;
						case 5:
							$str_out .= '`2 Du drehst eine Pirouette - gekonnt, gekonnt.';
							Atrahor::$Session['dance_condition']+=5;
							break;
						case 6:
							$str_out .= '`2 Eine Soloeinlage wäre jetzt nicht schlecht, denkst du dir... Schade nur, dass es hier so eng ist.';
							Atrahor::$Session['dance_condition']+=5;
							break;
						case 15:
							$str_out .= 'Ungeschickt rutscht du aus und stolperst von der Tanzfläche. Dort fällt dir ein kleines Goldstück auf, das wohl jemand verloren hat. Dem gibst du wohl besser schnell ein neues Zuhause!';
							$Char->gold++;
							Atrahor::$Session['dance_condition']+=5;
							break;
						case 20:
							$str_out .= '`2 Du tanzt heute Abend einfach göttlich und viele Blicke fliegen dir zu! Du fühlst dich berauscht und bist bei einigen Beobachtern sicher in der Achtung gestiegen!
						`n`@Du erhältst einen Charmepunkt
						`n`nSchnell merkst du aber, dass so etwas doch arg auf die Kondition geht... Du bist völlig außer Puste';
							$Char->charm++;
							Atrahor::$Session['dance_condition']+=300;
							break;
						default:
							$str_out .= '`2Du tanzt eine Weile vor dich hin und fühlst dich dabei einfach großartig.';
							Atrahor::$Session['dance_condition']+=5;
					}
			}
			addnav('Tanzen');
			addnav('Imponieren','dorffest.php?op=dancefloor&action=posing');
			addnav('Ruhiger Tanz','dorffest.php?op=dancefloor&action=gossip');
			addnav('Wege');
			addnav('Z?Zurück','dorffest.php');
			break;
		}

		//The fireplace
	case 'fire':
		{
			switch($_GET['action'])
			{
				case 'gossip':
					//Im Wort Lagerfeuer bei ausreichender Betrunkenheit einen Link zum Feuerschrein verstecken
					$int_rand = mt_rand(0, mb_strlen("Lagerfeuer")-1);
					$bool_zufall = (rand(1,rand(10,15))==1);
					$str_feuer = '`@'.mb_substr("Lagerfeuer",0,$int_rand).((($Char->drunkenness>60) && ($bool_zufall))?'<a style="text-decoration:none; color:#FFFF00;" href="fireshrine.php">'.mb_substr("Lagerfeuer",$int_rand,1).'</a>`@':mb_substr("Lagerfeuer",$int_rand,1)).mb_substr("Lagerfeuer",$int_rand+1).'`2';
					if (($Char->drunkenness>60) && ($bool_zufall)) addnav("","fireshrine.php");
					$str_out .= '`2 Du setzt dich an das '.$str_feuer.' zu ein paar alten oder neuen Bekannten und beginnst eine angeregte Diskussion.`n`n';
					output($str_out,true);
					$str_out = '';
					viewcommentary('party_fireplace','Am Lagerfeuer erzählen',30,'sagt');
					if ($access_control->su_check(access_control::SU_RIGHT_DEBUG))
					{
						addnav('Feuerschrein','fireshrine.php');
					}
					addnav('Etwas zu trinken holen','dorffest.php?op=fire');

					break;
				default:
					$str_out .= '`2Hach ja, am Feuer kann man sich immer das eine oder andere erzählen und auch das eine oder andere trinken. Ja, besonders trinken... Denn getrunken wird hier reichlich, schließlich ruft gerade wieder einmal jemand `@FREIIIIBIIIIIER`2, als du ankommst.
				`n`@"Endlich mal eine vernünftige Verwendung für die Steuergelder!"`2 denkst du dir!
				`n`2 Schon kommt Cedrik mit einem riesigen Tablett auf dich zu und ehe du dich versiehst, hast du wieder etwas zu trinken in der Hand!';

					addnav('Lagerfeuer');
					addnav('Ans Lagerfeuer setzen','dorffest.php?op=fire&action=gossip');

					addnav('Getränke');
					addnav('Ale','dorffest.php?op=get_drink&action=ale');
					addnav('Met','dorffest.php?op=get_drink&action=met');
					addnav('Orkenwein','dorffest.php?op=get_drink&action=wine');
					addnav('Grüner Drachenschnaps','dorffest.php?op=get_drink&action=goodstuff');
					addnav('Met mit Zitrone', $str_filename . '?op=get_drink&action=mzitro');
					addnav('Wunderpunsch', $str_filename . '?op=get_drink&action=wunder');
					addnav('Weinbrand', $str_filename . '?op=get_drink&action=brand');
					addnav('Rotwein', $str_filename . '?op=get_drink&action=wein');
					addnav('Ohne Alkohol');
					addnav('Kaffee', $str_filename . '?op=get_drink&action=kaffee');
					addnav('Heiße Schokolade', $str_filename . '?op=get_drink&action=schoko');
					addnav('Wasser', $str_filename . '?op=get_drink&action=wasser');
					addnav('MILCH! (20 Gold)','dorffest.php?op=get_drink&action=milk');
			}

			addnav('Wege');
			addnav('Z?Zurück','dorffest.php');
			break;
		}
	case 'get_drink':
		{
			switch($_GET['action'])
			{
				case 'mzitro':
					{
						$str_out .= '`2Met mit Zitrone... da du diese Mischung noch nie probiert hast probierst du sie einfach. Kurz darauf erhältst du das Met, in dem eine ganze Zitrone schwimmt. Merkwürdig!';
						$Char->drunkenness += 12;
						break;
					}
				case 'wunder':
					{
						$str_out .= '`2Kurz darauf erhältst du ein Glas mit einer grünlichen Flüssigkeit, in der jede Menge Früchte und Gewürze rumschwimmen. Du wunderst dich darüber.';
						$Char->drunkenness += 9;
						break;
					}
				case 'brand':
					{
						$str_out .= '`2Ein rauchiger Weinbrand. Er brennt kaum, wärmt aber im Magen.';
						$Char->drunkenness += 25;
						break;
					}
				case 'wein':
					{
						$str_out .= '`2Du bekommst ein Glas mit einer tiefroten Flüssigkeit. Es ist der leckerste Wein, den du jemals getrunken hast.';
						$Char->drunkenness += 10;
						break;
					}
				case 'kaffee':
					{
						$str_out .= '`2Kaffee scholl ja gegen *hick* Trunkenheit häälfen, ne?';
						$Char->drunkenness -= 5;
						break;
					}
				case 'schoko':
					{
						$str_out .= '`2Die heisse Schokolade belebt deiner Sinne!';
						$Char->drunkenness -= 5;
						break;
					}
				case 'wasser':
					{
						$str_out .= '`2Ein Glas Wasser? Was bist du denn für ein Weichei?';
						$Char->drunkenness -= 10;
						$Char->charm--;
						break;
					}
				case 'ale':
					{
						$str_out .= '`2 Hmmm, köstlich!';
						$Char->drunkenness+=10;
						break;
					}
				case 'met':
					{
						$str_out .= '`2 Schön süß, genau wie du es magst';
						$Char->drunkenness+=10;
						break;
					}
				case 'wine':
					{
						$str_out .= '`2Hm, edler Wein, ganz ausgezeichneter Jahrgang und fantastisches Bouquet. Er schmeichelt deinem Gaumen!';
						$Char->drunkenness+=15;
						break;
					}
				case 'goodstuff':
					{
						$str_out .= '`2 HUIUIUIUIUI, halt dich lieber am Boden fest!!! Man, ist der scharf!`n
								Du fühlst dich, als ob du Feuer speien könntest... Naja, wenigstens weißt du jetzt, warum das geniale Gesöff
								`@Grüner Drachenschnaps`2 heißt. Man, geht der in den Kopf!';
						$Char->drunkenness+=30;
						Atrahor::$Session['drachenschnaps'] = true;
						break;
					}
				case 'milk':
					{
						if($Char->gold<20)
						{
							$str_out .= '`2 So sehr du jetzt auch vielleicht eine Milch brauchst, du kannst sie dir nicht leisten!';
						}
						else
						{
							$str_out .= '`2 So dumm es auch vielleicht aussieht, Milch zu trinken, durch die Milch fühlst du dich besser und etwas klarer!';
							$Char->gold -=20;
							$Char->drunkenness=max(0,$Char->drunkenness-10);
							if ($Char->hitpoints>$Char->maxhitpoints)
							{
								$Char->hitpoints=$Char->maxhitpoints;
							}
						}
						break;
					}
			}
			addnav('Wege');
			addnav('Z?Zurück','dorffest.php?op=fire');
			break;
		}
		//The grill
	case 'grill':
		{
			if(Atrahor::$Session['bbq_hunger']>50)
			{
				$str_out .= '`2Also wenn du jetzt noch etwas essen müsstest, dann wird dir sicher speiübel. Lassen wir das erst mal schön wieder sacken.';
			}
			else
			{
				$str_out .= '`2Hmmm, der Duft von gebratenem Fleisch und Knollengemüse liegt in der Luft und der warme flackernde Schein des offenen Feuers tut sein übriges - dir läuft das Wasser im Munde zusammen.
			`nDa die Schlange vor dir nicht allzu lang erscheint, stellst du dich an, zuversichtlich, dass du einige Leckereien bekommen wirst. Als du endlich an der Reihe bist, wirfst du einen Blick auf den Grill.
			`n`@"Tjo", `2meint der Grillmeister, `@"siehst ja, wie es hier zugeht, wie bei der Raubtierfütterung. Tut mir leid, wenn wir nicht immer alles da haben, ich muss erst frisch nachlegen, das dauert halt ne Weile!"
			`n`2Kein Problem, denkst du dir, nehm ich halt was gerade da ist.';

				addnav('Grillgut');
				switch (e_rand(1,5))
				{
					case 1:
						addnav('Grillwurst (5 Gold)','dorffest.php?op=buy_bbq&action=sausage');
						addnav('Nackensteak (15 Gold)','dorffest.php?op=buy_bbq&action=steak');
						addnav('Grillhaxe (50 Gold)','dorffest.php?op=buy_bbq&action=bigpork');
						break;
					case 2:
						addnav('Grillwurst (5 Gold)','dorffest.php?op=buy_bbq&action=sausage');
						addnav('Nackensteak (15 Gold)','dorffest.php?op=buy_bbq&action=steak');
						addnav('Maiskolben (10 Gold)','dorffest.php?op=buy_bbq&action=corncrob');
						break;
					case 3:
						addnav('Kartoffel (5 Gold)','dorffest.php?op=buy_bbq&action=potato');
						addnav('Maiskolben (10 Gold)','dorffest.php?op=buy_bbq&action=corncrob');
						addnav('T-Bone Steak (75 Gold)','dorffest.php?op=buy_bbq&action=tbone');
						break;
					case 4:
						addnav('Auberginen (5 Gold)','dorffest.php?op=buy_bbq&action=aubergine');
						addnav('Ratte (10 Gold)','dorffest.php?op=buy_bbq&action=rat');
						addnav('Lerchenlebern (50 Gold)','dorffest.php?op=buy_bbq&action=liver');
						break;
					case 5:
						addnav('Grillwurst (5 Gold)','dorffest.php?op=buy_bbq&action=sausage');
						addnav('Nackensteak (15 Gold)','dorffest.php?op=buy_bbq&action=steak');
						addnav('Grillhaxe (50 Gold)','dorffest.php?op=buy_bbq&action=bigpork');
						break;
				}
			}
			addnav('Wege');
			addnav('Z?Zurück','dorffest.php');
			break;
		}
	case 'buy_bbq':
		{
			//Let the user pay and decrease the hunger
			switch($_GET['action'])
			{
				case 'sausage':
					$Char->gold -= 5;
					Atrahor::$Session['bbq_hunger']+=5;
					break;
				case 'steak':
					$Char->gold -= 15;
					Atrahor::$Session['bbq_hunger']+=15;
					break;
				case 'bigpork':
					$Char->gold -= 50;
					Atrahor::$Session['bbq_hunger']+=50;
					break;
				case 'corncrob':
					$Char->gold -= 10;
					Atrahor::$Session['bbq_hunger']+=5;
					break;
				case 'potato':
					$Char->gold -= 5;
					Atrahor::$Session['bbq_hunger']+=5;
					break;
				case 'tbone':
					$Char->gold -= 75;
					Atrahor::$Session['bbq_hunger']+=20;
					break;
				case 'aubergine':
					$Char->gold -= 5;
					Atrahor::$Session['bbq_hunger']+=5;
					break;
				case 'rat':
					$Char->gold -= 10;
					Atrahor::$Session['bbq_hunger']+=10;
					if(mt_rand(0,10) == 0)
					{
						$buff = array('name'=>'`!Starker Würgereiz`0','rounds'=>20,'wearoff'=>'`!Deine magenkrämpfe lassen nach`0','defmod'=>0.7,'roundmsg'=>'Du musst würgen und vernachlässigst dabei deine deckung!','activate'=>'defense');
						buff_add($buff);
					}
					$Char->reputation--;
					break;
				case 'liver':
					$Char->gold -= 50;
					
					Atrahor::$Session['bbq_hunger']-=5;
					break;
			}

			//Not enough money available
			if($Char->gold<1)
			{
				$str_out .= '`@"Na ja, wollen wir mal nicht so sein, das bekommst du heute auch mal für etwas weniger Geld!"`n';
				$Char->gold=0;

			}
			$str_out .= '`2 Du bezahlst und beißt herzhaft hinein!';

			//Special for the food
			switch(e_rand(1,10))
			{
				case 1:
					$str_out .= '`nEs ist ein bisschen kalt, aber sonst sehr lecker.';
					break;
				case 2:
					$str_out .= '`nHm, sehr lecker, ein Labsal für deinen Magen.';
					$Char->hitpoints++;
					break;
				case 3:
					$str_out .= '`nVerdammt ist das heiß, du verbrennst dir ein wenig die Zunge! Aba schonscht scher lecka.';
					$Char->hitpoints--;
					break;
				case 4:
					$str_out .= '`n`$BUÄRKS!!!`2 Da muss wohl Schnapper eins von seinen Würsten untergeschummelt haben... Da vergeht einem ja alles!';
					$Char->hitpoints-=20;
					Atrahor::$Session['bbq_hunger']+=300;
					break;				
				case 10:
					$str_out .= '`n Du willst gerade in dein leckeres Essen hineinbeißen. Das Wasser läuft dir im Munde zusammen. Du schließt die Augen, öffnest den Mund und&nbsp;-
				`n`$WAS ZUM?!?
				`n`n`@Du wirst versehentlich angestoßen und das leckere, überaus saftige, Wasser im Mund zusammenlaufen lassende Stückchen Glück fällt dir aus der Hand und direkt in den Dreck, wo sich schon einige geifernde Hunde darüber hermachen.
				`nMist!';
					Atrahor::$Session['bbq_hunger']-=20;
					break;
				default:
					$str_out .= "`nDu lässt dir das Essen munden. Und dann auch noch so preiswert... So ein Stadtfest ist schon etwas Feines.";
			}

			//The user does not have to die here, if the hitpoints get below one, increase them
			if($Char->hitpoints<1)
			{
				$Char->hitpoints=1;
			}
			addnav('Wege');
			addnav('Z?Zurück','dorffest.php');
			break;
		}
	case 'orcfield':
		{
			$str_out .= '`2Du gehst durch die Tür in die `^"Schießbude"`2 und das Erste, was dir auffällt, ist eine endlose Kette von Holz-Minotauren, welche an der hinteren Wand quer durch den Raum gezogen werden. Hier kannst du auf Minotauren schießen, um zu Entspannen oder Dampf abzulassen.`nAuf einer Holztafel steht die Preisliste: `t10 Schuss 5 Gold.`n`n';
			$orkhits=e_rand(0,10);
			if($_GET['act']!='' && $Char->gold<5)
			{
				$str_out .= '`#Du willst auch eine Runde mitschießen, stellst aber fest, dass du nicht genug Gold hast. Frustriert wirfst du dein '.$Char->weapon.'`# nach vorn: Du triffst 1 Minotaurus und erntest komische Blicke von den Umstehenden.`n`n';
			}
			elseif($_GET['act']=='cotton')
			{
				$str_out .= '`#Du entscheidest dich für die Wattebällchen und stellst dein Können mit dieser Waffe unter Beweis: Du triffst '.$orkhits.' Minotauren, die jedoch keinen größeren Schaden nehmen.`n`n';
				$Char->gold-=5;
			}
			elseif($_GET['act']=='stone')
			{
				$str_out .= '`#Du entscheidest dich für die Steinschleuder und stellst dein Können mit dieser Waffe unter Beweis: Du triffst '.$orkhits.' Minotauren, wovon '.e_rand(0,$orkhits).' sogar umfallen.`n`n';
				$Char->gold-=5;
			}
			elseif($_GET['act']=='bow')
			{
				$str_out .= '`#Du entscheidest dich für Pfeil und Bogen und stellst dein Können mit dieser Waffe unter Beweis: '.$orkhits.' Minotauren fallen um.`n`n';
				$Char->gold-=5;
			}
			elseif($_GET['act']=='catapult')
			{
				$str_out .= '`#Du entscheidest dich für das Katapult und stellst dein Können mit dieser Waffe unter Beweis: Du triffst '.$orkhits.' Minotauren, die durch die Wucht des Aufpralls regelrecht zersplittern.`n`n';
				$Char->gold-=5;
			}
			elseif($_GET['act']=='machinegun')
			{
				if($orkhits==5)
				{
					$str_out .= '`#Du entscheidest dich für das RPG2000 und grübelst, wie man damit Minotauren trifft. Dann findest du den Abzug und schießt dir selbst ins Bein. `$AUTSCH!`# So ein gefährliches Ding! Da bist du froh, dass sowas erst in ein paar hundert Jahren für ein Computerspiel erfunden wird. Was auch immer ein Computer sein mag...`n`n';
					$Char->hitpoints*=0.6;
				}
				else
				{
					$str_out .= '`#Du entscheidest dich für das RPG2000 und grübelst, wie man damit Minotauren trifft. Vielleicht solltest du in ein paar hundert Jahren wiederkommen, wenn Computerspiele erfunden sind. Was auch immer Computerspiele sein mögen...`n`n';
				}
				$Char->gold-=5;
			}
			output($str_out);
			$str_out = '';
			viewcommentary('party_orcfield','Auch Minotauren killen',30,'ruft');
			addnav('Gepresste Wattebällchen','dorffest.php?op=orcfield&act=cotton');
			addnav('Steinschleuder','dorffest.php?op=orcfield&act=stone');
			addnav('Pfeil und Bogen','dorffest.php?op=orcfield&act=bow');
			addnav('Katapult','dorffest.php?op=orcfield&act=catapult');
			addnav('RPG2000','dorffest.php?op=orcfield&act=machinegun');
			addnav('Wege');
			addnav('Zurück', 'dorffest.php');
			break;
		}

}

output($str_out);
page_footer();
?>