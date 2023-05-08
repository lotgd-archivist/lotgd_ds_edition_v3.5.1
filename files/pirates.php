<?php
// @author Eleya für atrahor.de

require_once 'common.php';

$show_invent = true;
$filename=basename(__FILE__);
addcommentary();
checkday();

$bool_return_viewcommentary_output = true;

$row=user_get_aei('seenpirate');

if($Char->prangerdays>0){
	redirect("pranger.php");
} 

$Char->specialinc ='';
$Char->specialmisc ='';

//Parolen für den Piratenkönig
$arr_parolen = array(
	'Meerschweinchen haben keine Seemannsbeine!',
	'ARRRRR!',
	'Schweinskopfsülze!',
	'Flugtaubenverbot in Einbahnstraßen',
	'Mannpinsel Treibholz',
	'Holzbeine sind prima Knüppel',
	'Der Haken an der Hand spart den Zimmermann',
	'Nach zwei Buddeln Rum ist man nicht betrunken'
);

$arr_roots = array(
	'Vanille',
	'Möhre',
	'Pastinake',
	'Klettenwurzel',
	'Radieschen'
);

$arr_drinks = array(
	array('name'=>'Hausmeisterbrand','cost'=>5,'drunkenness'=>5),
	array('name'=>'Ale','cost'=>10,'drunkenness'=>10),
	array('name'=>'Scotch','cost'=>20,'drunkenness'=>10),
	array('name'=>'Singlemalt','cost'=>50,'drunkenness'=>10),
	array('name'=>'Grog','cost'=>50,'drunkenness'=>10),
	array('name'=>'Drachenschnapps','cost'=>50,'drunkenness'=>30),
	array('name'=>'Rum','cost'=>20,'drunkenness'=>20),	
	array('name'=>'Mäusemilch','cost'=>5,'drunkenness'=>-5),
	array('name'=>'Rattenmilch','cost'=>10,'drunkenness'=>-5),
	array('name'=>'Yakmilch','cost'=>15,'drunkenness'=>-10),
	array('name'=>'Ozelotmilch','cost'=>20,'drunkenness'=>-15)
);

//Setze die heutige Parole, falls sie noch nicht existiert
function get_parole()
{
	global $arr_parolen;
	if(!isset(Atrahor::$Session['daily']['pirates_spelunke_king_answer']))
	{
		Atrahor::$Session['daily']['pirates_spelunke_king_answer'] = mt_rand(0,count($arr_parolen)-1);        
	}
	return Atrahor::$Session['daily']['pirates_spelunke_king_answer'];
}

switch($_GET['op'])
{
	case 'king':
	{
		page_header('Der Piratenkönig'); // Hier kann man Gold loswerden, aber nur einmal pro DK^^

		switch ($_GET['act'])
		{
			default:
			{
				$str_output = get_title ('`,D`Ae`4r `:P`;i`Tr`Sate`Tn`;k`:ö`4n`Ai`,g`0');

				$str_output .= words_by_sex('`,F`Al`4a`:n`;k`Ti`Sert von zwei weiteren muskelbepackten, grimmig dreinblickenden Wächtern sitzt der Piratenkönig auf seinem Thron. Zu seinen Füßen liegt eine junge, knapp bekleidete Frau, wohl sein neustes Spielzeug.`n
				Der König selbst ist kein junger Mann mehr, sein Gesicht ist wettergegerbt und faltig, die langen, ehemals dunklen Haare sind schon stark ergraut. Dennoch ist sein Körper noch immer kräftig, und er wirkt noch immer eindrucksvoll. Bekleidet ist er mit dunklen Hosen, die von einem prachtvollen Gürtel gehalten werden, einem weißen Hemd sowie einem dunklen Umhang. Auf dem Kopf trägt er – natürlich – einen Hut, denn ohne einen Hut wäre er ja kein echter Pirat. Bewaffnet ist er nicht, jedenfalls kannst du keine Waffen entdecken, doch die beiden Leibwächter an seinen Seiten machen ohnehin jeden Versuch eines Angriffs zwecklos.`n
				Er grinst dich an, aus seinem Mund funkeln mehrere Goldzähne.`n
				'.words_by_sex('[`,"Was kann ich für dich tun, Landratte?"|`,"Was kann ich für Euch tun, Lady?"]').'`n
				`SDu weißt, dass dieser Mann dir praktisch jeden Gefallen tun kann – wenn er denn will, und wenn du ihn entsprechend dafür be`Tz`;a`:h`4l`As`,t.`0`n`n');

				if ($row['seenpirate'] == 1)
				{
					$str_output = ('`b`,Doch du weißt auch, dass du den König lieber nicht zu oft belästigen solltest. Bevor du dich nicht mit einer erneuten Heldentat brüsten kannst, solltest du es lieber nicht mehr versuchen!`b`0`n`n');
				}

				if ($row['seenpirate'] == 0)
				{
					addnav('Um einen Gefallen bitten',$filename.'?op=king&act=beg');
				}
				addnav('Verschwinde',$filename.'?op=spelunke');
			}
			break;

			case 'beg':
			{

				if($Char->goldinbank > 0)
				{
					$str_output .= words_by_sex('`n`SDer Piratenkönig blickt dich ernst an. `,"Das soll alles sein, was du mir gibst? Nein, nein, '.words_by_sex('[Söhnchen|Fräulein]').', so kommen wir hier nicht weiter. Ich weiß, dass du noch über weitaus größere Reichtümer verfügst. Bring sie mir!"`S`n`n');
					user_set_aei(array('seenpirate'=>1));
					addnav('Verschwinde',$filename.'?op=spelunke');
				}

				elseif ($Char->goldinbank == 0 && $Char->gold < 10000)
				{
					$str_output .= words_by_sex('`SDer Piratenkönig schüttelt lachend den Kopf. `,"Für das bisschen Gold erwartest du, dass ich etwas für dich tue? Nee, nee, '.words_by_sex('[min Jung|Lady]').', so geht das nicht! Komm wieder, wenn du mich anständig bezahlen kannst!"`n`n');
					user_set_aei(array('seenpirate'=>1));
					addnav('Verschwinde',$filename.'?op=spelunke');
				}
				else
				{
					$str_output .= '`SDer Piratenkönig nickt wohlwollend. `,"Aye, dafür lässt sich doch etwas machen. Gib mir dein Gold, und ich werde dir geben, was du willst." `SDu fragst sich verwundert, woher er denn weiß, was du willst, doch traust dich nicht, diese Frage zu stellen. Gibst du dem König dein Gold, oder lehnst du lieber ab und verschwindest?`n`n';
					addnav('Gib ihm das Gold',$filename.'?op=king&act=give');
					addnav('Verschwinde',$filename.'?op=spelunke');
				}
			}
			break;

			case 'give':
			{
				$str_output .= '`SDu übergibst dem Piratenkönig deinen Goldbeutel samt Inhalt, und beobachtest ein bisschen wehmütig, wie dieser ihn mit einem breiten Grinsen ein paar mal in den Händen wiegt, bevor er ihn dem einen seiner Leibwächter übergibt.`n`,"So, dann will ich doch einmal sehen, was ich für dich tun kann!"`S`n`n';

				if ($Char->gold >= 10000 && $Char->gold < 20000)
				{
					switch(e_rand(1,10))
					{
						case 1:
						case 2:
						case 3:	
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig einen Beutel mit `#2 `SEdelsteinen!';
							$Char->gems += 2;  
							break;	
						case 4:
						case 5:
						case 6:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig einen `?rosafarbenen `STrank. Du musterst ihn ein bisschen skeptisch, doch nachdem du ihn getrunken hast, fühlst du dich attraktiv. Du erhältst `?2 `SCharmepunkte!';
							$Char->charm += 2;
							break;
						case 7:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig `Ghellgrünen `STrank. Du musterst ihn ein bisschen skeptisch, doch nachdem du ihn getrunken hast, fühlst du dich stark. Du erhältst `G1 `Spermanenten Lebenspunkt!';
							$Char->maxhitpoints += 1;
							break;
						case 8:
						case 9:
						case 10:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig einen prall gefüllten Beutel. Fröhlich gehst du hinaus, doch als du in den Beutel guckst, musst du feststellen, dass er gefüllt ist mit... Steinen! Tja, da bist du wohl hereingefallen... Was hast du auch erwartet, bei einem Piraten?';
							break;
					}
				}

				elseif ($Char->gold >= 20000 && $Char->gold < 50000)
				{
					switch(e_rand(1,10))
					{
						case 1:
						case 2:
						case 3:
						case 4:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig einen Beutel mit `#4 `SEdelsteinen!';
							$Char->gems += 4;
							break;
						case 5:
						case 6:
						case 7:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig einen Trank. Du musterst ihn ein bisschen skeptisch, doch nachdem du ihn getrunken hast, fühlst du dich attraktiv. Du erhältst `?4 `SCharmepunkte!';
							$Char->charm += 4;
							break;
						case 8:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig `Ghellgrünen `STrank. Du musterst ihn ein bisschen skeptisch, doch nachdem du ihn getrunken hast, fühlst du dich stark. Du erhältst `G2 `Spermanente Lebenspunkte!';
							$Char->maxhitpoints += 2;
							break;
						case 9:
						case 10:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig einen prall gefüllten Beutel. Fröhlich gehst du hinaus, doch als du in den Beutel guckst, musst du feststellen, dass er gefüllt ist mit... Steinen! Tja, da bist du wohl hereingefallen... Was hast du auch erwartet, bei einem Piraten?';

							break;
					}
				}

				elseif ($Char->gold >= 50000)
				{
					switch(e_rand(1,10))
					{
						case 1:
						case 2:
						case 3:
						case 4:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig einen Beutel mit `#8 `SEdelsteinen!';
							$Char->gems += 8;
							break;
						case 5:
						case 6:
						case 7:
						case 8:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig einen Trank. Du musterst ihn ein bisschen skeptisch, doch nachdem du ihn getrunken hast, fühlst du dich attraktiv. Du erhältst `?8 `SCharmepunkte!';
							$Char->charm += 8;
							break;
						case 9:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig `Ghellgrünen `STrank. Du musterst ihn ein bisschen skeptisch, doch nachdem du ihn getrunken hast, fühlst du dich stark. Du erhältst `G3 `Spermanente Lebenspunkte!';
							$Char->maxhitpoints += 3;
							break;
						case 10:
							$str_output .= '`SAls Gegenleistung überlässt dir der Piratenkönig einen prall gefüllten Beutel. Fröhlich gehst du hinaus, doch als du in den Beutel guckst, musst du feststellen, dass er gefüllt ist mit... Steinen! Tja, da bist du wohl hereingefallen... Was hast du auch erwartet, bei einem Piraten?';
							break;
					}
				}

				else //Sollte eigentlich nicht passieren^^
				{
					$str_output .= 'Tut mir leid, ich kann dir nicht helfen!';
				}

				$Char->gold = 0;
				user_set_aei(array('seenpirate'=>1));
				debuglog('gab all sein Gold dem Piratenkönig');
				addnav('Vielen Dank!',$filename.'?op=spelunke');
			}
			break;
		}

		break;
	}
	case 'wache':
	{
		page_header('Bulls-Eye, der Leibwächter');

		switch ($_GET['act'])
		{
			default:
			{            
				$str_output .= get_title('`AB`:u`;l`Tls-Eye, der Leibwäch`;t`:e`Ar`0').'`AD`:e`;r `Tbullige Pirat wirft dir einen verächtlichen Blick zu, als du dich ihm näherst.`n
				`A"Wie lautet die Parole?"`T, fragt er in einem Tonfall, der vermutlich einen Preis für Unfreundlichkeit gewinnen könnte.`n
				Parole? Aber natürlich kennst du die Parole, sie lau`;t`:e`At…`0`n`n';
				addnav('');
				foreach($arr_parolen as $key => $str_parole)
				{
					addnav($str_parole,$filename.'?op=wache&act=answer&answer='.$key);    
				}            
			}
			break;


			case 'answer':
			{
				if($_GET['answer'] == get_parole())
				{
					$str_output .= get_title('`AB`:u`;l`Tls-Eye, der Leibwäch`;t`:e`Ar').'`AD`:e`;r `TWächter nickt und schenkt dir ein grimmiges Lächeln. Ein grimmiges Lächeln? Geht das überhaupt? Nun ja, du weißt nicht, wie du den Gesichtsausdruck des Mannes anders beschreiben sollst, also scheint es zu gehen. Ohne ein weiteres Wort tritt er dann zur Seite und lässt dich passie`;r`:e`An.`n`n';

					addnav('Zum Piratenkönig',$filename.'?op=king');
				}
				else
				{
					$str_output .= get_title('`AB`:u`;l`Tls-Eye, der Leibwäch`;t`:e`Ar').'`AD`:e`;r `TPirat lächelt dich an und… holt ohne Vorwarnung aus und verpasst dir eine solche Ohrfeige, dass du glaubst, dein Kopf wird dir vom Hals gerissen.`n
					`A"Versuch nicht noch einmal, mich hereinzulegen, sonst lass ich dich kielholen!"`T, bellt er. Du solltest zusehen, dass du verschwindest, und es entweder nicht mehr versuchst, oder schleunigst die Parole in Erfahrung bringst… und dich am besten ein wenig verkleidest, bevor du wieder hier auftauc`;h`:s`At.`n`n';        


					$Char->hitpoints -= e_rand(50,100);
					if ($Char->hitpoints<=0)
					{
						$Char->hitpoints=1;
					}
					addnav ('Abhauen!',$filename.'?op=spelunke');
				}
			}
			break;
		}

		break;
	}
	case 'upstairs':
	{
		page_header('Ins Obergeschoss');
		$str_output .= get_title('`:I`;n`Ys `}O`Ibergesc`}h`Yo`;s`:s`0').'`:E`;i`Yn`}e `Ischmale Wendeltreppe führt ins Obergeschoss der schiffsförmigen Spelunke, welches nur aus einem einzigen Raum besteht, an der Stelle, wo sich bei einem echten Schiff vielleicht die Kapitänskajüte befunden hätte. Vor der Treppe steht ein bulliger Pirat, der am Oberkörper nur mit einer Weste bekleidet ist, die sowohl seine Armmuskeln als auch seine Tätowierungen auf beiden Armen sehr gut erkennen lässt. Mit grimmigem Blick mustert er jeden, der sich der Treppe nähern möchte. Willst du wirklich versuchen, ob er dich vorbeil`}ä`Ys`;s`:t?`0`n`n';
		addnav('');
		addnav('Natürlich, kneifen ist nicht!',$filename.'?op=wache');
		addnav('Nein, lieber nicht...',$filename.'?op=spelunke');
		break;
	}   
	case 'barkeeper':
	{
		page_header('Barkeeper');
		
		//Hier überprüfen wir, ob sich einer totgesoffen hat
		$arr_drunkenness = $Char->handleDrunkenness();
		if($arr_drunkenness['died'] == true)
		{
			$_GET['act'] = 'diedfromdrunkenness';
		}
		
		switch ($_GET['act'])
		{
			case '':
			default:
			{                
				$str_output .= get_title('`:P`;r`}o`Is`tt!').'`:D`;u `}s`It`tehst vor dem schmierigen Wirt. Er ist fett, kahl, trägt einen Schnauzbart und eine dreckige Schürze, deren ursprüngliche Farbe du nur erahnen kannst. In der einen Hand hält er ein Glas, welches er geistesabwesend, aber mit Hingabe poliert. Wenn du genauer darüber nachdenkst scheint er immer nur das gleiche Glas zu polieren... Dem solltest du vielleicht irgendwann mal nachg`Ie`}h`:e`;n.`0';
				addnav('Zurück',$filename.'?op=spelunke');
				addnav('Der Barkeeper');
				addnav('Ins Glas spucken',$filename.'?op=barkeeper&act=spit');
				addnav('Ist das nicht die Scumm Bar?',$filename.'?op=barkeeper&act=about_scumm');
				addnav('Die Falltür hinter der Theke',$filename.'?op=barkeeper&act=cellar');
				if(item_count('i.tpl_id="common_root" AND i.owner='.$Char->acctid)>0) addnav('Nach Malzbier befragen',$filename.'?op=barkeeper&act=rootbeer');
				if($Char->gold >= 100) addnav('Wie lautet die Parole?(100G)',$filename.'?op=barkeeper&act=slogan');
				
        if($Char->spirits != RP_RESURRECTION)
        {
          addnav('Etwas bestellen');
				  foreach($arr_drinks as $key => $arr_drink)
				  {
					 addnav('('.$arr_drink['cost'].'G) '.$arr_drink['name'],$filename.'?op=barkeeper&act=order&drink='.$key);
				  }
        }               
				
				break;
			}
			case 'rootbeer':
			{
				$str_output .= get_title('`ND`Sa`Us `uMalzb`Ui`Se`Nr').'`NM`Si`Ut `ueiner Wurzel in der Hand gehst du zum Barkeeper und fragst ihn, ob er daraus etwas herstellen könne. Der Barkeper schaut kurz auf dich, dann auf die Wurzel und wieder zurück zu dir. Dann beugt er sich zu dir und sagt: "`tDu solltest jetzt besser ganz schnell was zu saufen bestellen. Denn sollte ich auf die Idee kommen du würdest mich NÜCHTERN mit solchem Quatsch behelligen, dann würde ich dich aus der Bar schmeissen lassen. Pffft...etwas brauen. Bin ich Cedrick oder was?`N"`0';
				addnav('Zurück',$filename.'?op=barkeeper');
				break;
			}
			case 'order':
			{
				$int_drink = (int)$_GET['drink'];
				$int_cost = $arr_drinks[$int_drink]['cost'];
				if($Char->gold >= $int_cost)
				{
					$str_output .= get_title('Der Barkeeper').'`IAhh! Prost sag ich da nur. Du hebst das wohlschmeckenden Gesöff an deine Lippen, lässt den Alkohol deine Kehle und Sinne beleben und deinen Magen erwärmen. Dann schmeisst du dem Wirt seine '.$int_cost.' Goldsücke auf die Theke und überlegst, ob du wohl noch etwas trinken solltest. Ein '.$arr_drinks[$int_drink]['name'].' allein bringt einen ja schließlich nicht um.`0 `n';
					
					$Char->drunkenness += $arr_drinks[$int_drink]['drunkenness'];
					
					if($Char->drunkenness == 0)
					{
						$str_output .= '`IDu fühlst dich stocknüchtern. Bäh, widerliches Gefühl.`0';
					}
					elseif(isBetween(1,$Char->drunkenness,20) )
					{
						$str_output .= '`IDu fühlst dich leicht angetüdelt`0';
					}
					elseif(isBetween(21,$Char->drunkenness,40) )
					{
						$str_output .= '`IDu fühlst dich beschwingt`0';
					}
					elseif(isBetween(41,$Char->drunkenness,60) )
					{
						$str_output .= '`IDu fühlst dich ordentlich angetrunken`0';
					}
					elseif(isBetween(61,$Char->drunkenness,80) )
					{
						$str_output .= '`IDu hast das Gefühl als ob die Welt sich um dich dreht.`0';
					}
					elseif(isBetween(81,$Char->drunkenness,90) )
					{
						$str_output .= '`IDu hältst dich selbst am Boden liegend noch fest und lallst keine vollständigen Worte mehr.`0';
					}
					elseif($Char->drunkenness > 90)
					{
						$str_output .= '`IDu hast praktisch kaum noch Blut im Alkoholsystem...`0';
					}
										
					switch (e_rand(1,8))
					{
						case 1:
							$str_output .= '`n`&Du fühlst dich krank!';
							$Char->hitpoints =1;
							break;
						case 2:
							$str_output .= '`n`&Du fühlst dich gesund!';
							$Char->hitpoints +=round ($Char->maxhitpoints*0.1);
							break;
						case 3:
							$str_output .= '`n`&Du fühlst dich lebhaft!';
							$Char->turns++;
							break;
						case 4:
							$str_output .= '`n`&Du fühlst dich müde!';
							$Char->turns--;
							break;
					}
					if ($Char->drunkenness > 33)
					{
						$Char->reputation--;
					}
										
					$Char->gold -= $int_cost;
					
					addnav('Prost',$filename.'?op=barkeeper');
				}
				else
				{
					$str_output .= get_title('`UD`ue`}r B`Iarke`}ep`ue`Ur').'`IDer Wirt beugt sich zu dir nach vorne und beduetet dir dich ebenfalls auf Flüsternähe zu dir zu beugen. "`t'.words_by_sex('[Süßerle|Schneckchen]').'`I", beginnt er Süßholz zu raspeln, "`tWenn du dir '.$int_cost.' Goldstücke nicht leisten kannst für mein erstklassiges Gesöff, dann solltest du besser ganz schnell die Fliege machen, denn Leute ohne Geld mag ich hier nicht!`I" Schade, dabei hätest du so gerne ein '.$arr_drinks[$int_drink]['name'].' gehabt...`0';
					addnav('Hmpf!',$filename.'?op=barkeeper');
				}
				break;
			}
			case 'diedfromdrunkenness':
			{
				$str_output .= get_title('`ND`(er `)l`ee`atzt`ae Sc`eh`)l`(uc`Nk').'`NO`(h `)o`eh `aoh, da hast du dich wohl maßlos übernommen. Manche Leute können zwar viel vertragen, aber niemand kann unbegrenzt tief ins Glas schauen, ohne die Konsequenzen tragen zu müssen. In deinem Fall bedeutet dies, dass du rücklings torkelnd irgendwo gegen stösst. Nun vielleicht war auch die eine oder andere vergiftete Klinge, eine verzauberte Ratte oder sonstiges Getier im Spiel. Wer weiß das schon so genau? Du jedenfalls bist tot und solltest Ramius mal langsam fragen, ob er dir zu ehren nicht einen Schrein eröffnen mö- Moment mal...das ist ja gar nicht Ra`em`)i`(u`Ns...`0';                
				clearnav();
				addnav('Zu den Schatten',$filename.'?op=deadpirate');
				break;
			}
			case 'cellar':
			{
				$str_output .= get_title('`SD`Te`(r `)Kel`(l`Te`Sr').'`SH`Ti`(n`)ter der Theke befindet sich eine kleine offene Luke, die hinab in den Bauch des Schiffes führt. Du vermutest, dass der Barkeeper dort seinen Rum hortet und deine angeborene Neugier würde dich zu gern dort hinein führen. Leider hat auch der Barkeeper deinen Blick bemerkt und schaut dich mit dem "Denk nicht mal dran Freundchen"- Blick an. Naja, was soll da unten auch zu sehen sein... ein Keller wie jeder andere...voller Rum...ach verda`(m`Tm`St!`0';
				addnav('Zurück',$filename.'?op=barkeeper');
				break;
			}
			case 'spit':
			{
				$str_output .= get_title('Ins Glas spucken').'`IDu winkst den Wirt zu dir heran und als sich dieser zu dir beugt ziehst du einmal kräftig die Nase hoch und rotzt ihm in sein frisch poliertes Glas. ';
				switch(mt_rand(1,3))
				{
					case 1:
					{
						$str_output .= 'Der Wirt ist fassunglos und mit einer Geschwindigkeit, die du so einem fetten Kerl nicht zugetraut hättest, hat er dich auch schon am Schlawittchen gepackt und brüllt dich an: "`tWas bildest du dir eigentlich ein '.words_by_sex('[Bürschchen|Kindchen]').'? Denkst du ich sei Cedrick und liesse sowas einfach mit mir machen?`I" Upps, das war anscheinend eine verdammt schlechte Idee, denn der folgende Schlag auf den Schädel setzt dich für einige Zeit außer Gefecht. Dumm nur, dass dies in einer Piratenspelunke passieren muss, denn als du aufwachst fehlt dir nicht nur beträchtlich viel Bargeld, sondern auch der Respekt der Piraten. "Scheiße" ist übrigens das Wort, nach dem du gerade suchst!`0';
						$Char->gold = 0;
						$Char->gems -= 5;
						$Char->charm -= 5;
						
						addnav('Sch...ade',$filename);
						break;
					}
					case 2:
					{
						$str_output .= 'Der Wirt ist fassunglos und mit einer Geschwindigkeit, die du so einem fetten Kerl nicht zugetraut hättest, hat er dich auch schon am Schlawittchen gepackt und raunt dir bösartig zu: "`tWas bildest du dir eigentlich ein '.words_by_sex('[Bürschchen|Kindchen]').'? Mit Cedrick kannst du solchen Scheiss vielleicht machen, aber hier nicht. Das gibt für dich einen Tag lang `bHausverbot`b!. Du willst gerade anfangen zu lachen, als dir die Betonung des Wortes auffällt. Vorsichtig blickst du dich um und spürst etwa 40 Augenpaare auf dich. Also mit sovielen Piraten kanst du es nie und nimmer aufnehmen und so lässt dich dich lieber lebendig hinausbegleiten, als tot abtransportieren. Tjaja, die Piratenlobby ist sehr stark.`0';
						$Char->charm -= 5;
						Atrahor::$Session['daily']['scumm_hausverbot'] = true;
						
						addnav('Tschüss denn',$filename);
						break;
					}
					case 3:
					{
						$str_output .= '`ILangsam und bedächtig legt der Wirt sein Glas aus der Hand und schaut zu dir auf. Als du gerade denkst er würde dich zerfleischen und den Geiern zum Frass vorwerfen wollen, beginnt er zu lächeln und sagt: "`t`IDa musst du mich wohl mit meinem jüngeren Bruder Cedrick verwechseln. Geh jetzt beser ganz schnell aus meinem Laden, ehe ich dir zeige wie man mit Bratpfannen Applaus klatscht!" Und äh, das tust du dann wohl auch besser...ich sag nur "Hausverbot"...`0';
						$Char->charm -= 5;
						Atrahor::$Session['daily']['scumm_hausverbot'] = true;
						
						addnav('Tschüss denn, ne?',$filename);
						
						break;
					}
				}
				break;
			}
			case 'slogan':
			{
				$str_output .= get_title('`tD`/i`ye Paro`/l`te').'`tD`/u `yschiebst dem Barkeeper 100 Goldstücke über den Tresen und nickst ihm verschwörerisch zu. Geschickt wie ein Taschenspieler beugt sich dieser nun auch nach vorne und raunt dir zu "`tDie Parole lautet heute `b '.$arr_parolen[get_parole()].' `b`y" Als er sich wieder aufrichtet ist dein Gold verschwunden und der  Barkeeper poliert sein Glas, als wenn nichts gewesen wä`/r`te.`0';
				$Char->gold -= 100;
				addnav('Zurück',$filename.'?op=barkeeper');
				break;
			}
			case 'return_scumm_incomplete':
			{
				$str_output .= get_title('`UD`ue`}r B`Iarke`}ep`ue`Ur').'`UD`uu `}h`Iolst ein Schild hervor, welches entfernt an das ehemalige Logo der Bar erinnern könnte und hälst es dem Wirt hin. Dieser ist leider not amused, wie die Queen sagen würde und blafft dich an "`tWillst du mich verarschen?!? Bring mir mein Schild, aber nicht so einen Schrott`I" Nun, völlig egal wer auch immer die Queen sein mag, diese Aufforderung hätte auch sie verstan`}d`ue`Un!`0';
				addnav('Zurück',$filename.'?op=barkeeper');
				break;
			}
			case 'return_scumm':
			{
				$arr_item = item_get('i.tpl_id="scumm_logo" AND i.owner='.$Char->acctid);
				$arr_item['content'] = utf8_unserialize($arr_item['content']);
				
				if($arr_item['content']['new'] == true)
				{
					$str_output .= get_title('`UD`ue`}r B`Iarke`}ep`ue`Ur').'`UD`uu `}h`Iolst das Schild aus deiner Tasche und hälst es dem Wirt hin. Dieser starrt dich an und lässt beinahe sein Glas fallen. Mit hochrotem Kopf brüllt er dich an: "`tDu hast mein Schild gestohlen und willst es mir jetzt selbst wiedergeben?!?`I" Erm ja, genau so hast du dir das eigentlich gedacht, aber die Rechnung dabei...nunja...ohne den Wirt gemacht. Denn dieser rennt kopflos auf dich zu und prügelt so lange mit Pfannen und Messern auf dich ein, bis auch du kopflos dastehst. Vor Ram`}i`uu`Us!`0';
					
					$Char->kill();
					item_delete('tpl_id="scumm_logo" AND owner='.$Char->acctid);
				}
				else
				{            
					$str_output .= get_title('`UD`ue`}r B`Iarke`}ep`ue`Ur').'`UD`uu `}h`Iolst das Schild aus deiner Tasche und hälst es dem Wirt hin. Dieser starrt dich an und lässt beinahe sein Glas fallen. Mit zitternder Unterlippe starrt er auf die goldenen Lettern. Er bedankt sich überschwenglich bei dir und rennt mit dem Schild sofort vor die Tür, um es dort direkt über dem jetzigen Schild anzunageln. Das etwa drei Meter lange und  ein Meter hohe Schild ziert diese Kneipe wirklich gut und... Moment mal... drei Meter lang und ein Meter... das hast du die ganze Zeit in deinem Beutel dabei gehabt? Ach, denk nicht länger drüber nach, sondern erfreu dich lieber daran, dass du jemanden glücklich gemacht hast, denn der Wirt ist glücklich und schmeisst eine Lokalrunde nach der andere, bis alle so richtig schön blau sind und sich noch am Boden liegend festhalten müssen! Du erhälst 2000 Erfahrungspunkte und einen Ka`}t`ue`Ur...`0';
					$Char->drunkenness = 80;
					$Char->experience += 2000;
					buff_add(array('name'=>'`#Kater','rounds'=>10,'wearoff'=>'Dein Rausch verschwindet.','atkmod'=>0.25,'roundmsg'=>'Du hast einen ordentlichen Kater und magst dich kaum bewegen.','activate'=>'offense')); 
					
					item_delete('tpl_id="scumm_logo" AND owner='.$Char->acctid);
					
					savesetting('scummbar_logo_stolen',0);
					
					addnav('Zurück',$filename.'?op=barkeeper');
				}
				break;
			}
			case 'about_scumm':
			{
				$str_output .= get_title('`UD`ue`}r B`Iarke`}ep`ue`Ur').'`UD`uu `}g`Iehst einige Schritte auf die Theke zu, als dich der Wirt auch sofort bemerkt und dir mit einem aufmunternden "`bHmmmpf`b" bedeutet deinen Wunsch loszuwerden. Du beugst dich zum Barkeeper und fragst ihn mit verschwörerischer Stimme, ob das hier nicht die Scumm Bar sei. Beinahe Augenblicklich hört der Wirt auf sein Glas zu polieren, stellt es ab und beugt sich seinerseits nach vorne. Der feuchte Schimmer in seinen Augen lässt dich vermuten, dass du wohl einen wunden Punkt getroffen hast. "`tJetzt hör mal genau zu '.words_by_sex('[Jungchen|Mädchen]').'! Die S.C.U.M.M. (!) Bar ist mein Ein und Alles. ';
				
				if(getsetting('scummbar_logo_stolen','0') == 0)
				{
					$str_output .= 'Komm ja nicht auf die Idee etwas Dummes anzustellen. Pfffft, zur sündigen Sirene...das haben sich die Investoren ausgedacht, als ich pleite war und das Geld brauchte. Dabei weiß jeder hier, dass dies die S.C.U.M.M. Bar ist und in meinem Herzen immer sein w`}i`ur`Ud!`I"`0';
				}
				else
				{
					$str_output .= 'Und wenn ich denjenigen erwische, der mir mein geliebtes Türschild geklaut hat, dem beiss ich beide Eier ab, schlag sie in die Pfanne, brate ein Omelette draus, garniere es und werfe es den Hunden zum Frass vor!`I" Okay, okay, so genau wolltest du das gar nicht wissen. "`tJetzt mal ernsthaft! Wenn du mir mein Schild wiederbringst, dann wäre ich dir äußerst dankbar!`I" ';
					$arr_item = item_get('i.tpl_id="scumm_logo" AND i.owner='.$Char->acctid);
					if($arr_item !== false)
					{
						$arr_item['content'] = utf8_unserialize($arr_item['content']);
						
						if(count($arr_item['content']) == 5 || $arr_item['content']['new'] == true)
						{
							$str_output .= 'Hmm, einen Augenblick! Du glaubst genau das Gesuchte bei dir zu ha`}b`ue`Un.';
							addnav('Hier ist dein Schild', $filename.'?op=barkeeper&act=return_scumm');
						}
						else
						{
							$str_output .= 'Hmm, einen Augenblick! Du glaubst genau das Gesuchte bei dir zu haben. Etwas unvollständig, aber immer`}h`ui`Un.';
							addnav('Hier ist dein Schild', $filename.'?op=barkeeper&act=return_scumm_incomplete');
						}
					}
				}        
								
				addnav('Aha!',$filename.'?op=barkeeper');
			}
		}
		break;
	}
	case 'whitedog':
	{
		page_header('Der kleine weiße Hund');
		$str_output .= get_title('`:D`;i`Ye `}S`Ipel`}u`Yn`;k`:e`0').'`IInmitten der Massen siehst du einen kleinen weißen Cockerspaniel sitzen, der gerade das tut worum ihn alle Männer beneiden, er leckt sich die...Hinterpfoten sauber. In einem plötzlichen Anfall von alltäglichem Irrsinn kniest du dich hin und lässt dich zu einem "Ja was bist du denn für ein Süßer"-Geseier hinreissen. Der Hund schaut kurz von seiner Arbeit auf. Und bellt `i"Wuff Wuff LeChuck Wuff Wuff hechel`I". Also DAS war jetzt mal eigenartig.`0';
		addnav('Zurück',$filename.'?op=spelunke');
		break;
	}
	case 'spelunke':
	{
		page_header('Die Spelunke');
		$str_output .= get_title('`:D`;i`Ye `}S`Ipel`}u`Yn`;k`:e`0').'`i`:"Z`;u`Yr `}s`Iündigen Sirene"`i `Inennt sie sich, wie die unordentliche Schrift auf dem Holzschild über der Tür verkündet. Schon von weitem hört man dröhnend lachende oder brüllende Männer, kichernde und kreischende Frauen, und manchmal auch das ein oder andere – sehr laut und sehr schief – gesungene Seemannslied. Der weitläufige Innenraum ist nur spärlich beleuchtet, der Boden übersäht mit Pfützen und einigen Scherben, es riecht nach Pfeifenqualm und Alkohol.`n
		An der Bar bedienen der Inhaber Captain John Blackbird, der trotz seines Holzbeines noch recht gut auf den Füßen ist, sowie drei stark geschminkte Frauen in Kleidern mit üppigen Dekolletees. Rum und Ale fließen in Massen, etwas anderes wird hier eigentlich gar nicht bestellt.`n
		In den Abendstunden ist die Spelunke meist gut gefüllt, die Piraten treffen sich, um sich ihre Abenteuer zu erzählen, sich gegenseitig beim Würfel- und Kartenspiel auszunehmen... oder um mit den Kellnerinnen anzubän`}d`Ye`;l`:n.`0`n';
		
		if (isset(Atrahor::$Session['daily']['scumm_hausverbot']))
		{
			$str_output .= '`n`IKaum hast DU jedoch die Spelunke betreten, kleben dir auch schon zwei breitschultrige Piraten am Ärmel und tragen dich wieder hinaus. Als du draussen unsanft im Dreck landest fällt es dir wieder ein, heute hast du ja Hausverbot...`0';
			$Char->charm -= 5;
			addnav('Zurück',$filename);
		}
		else
		{
			$str_output .= viewcommentary('pirates_spelunke','Hinzufügen',25);
			addnav('');
			addnav('Ins Obergeschoss',$filename.'?op=upstairs');
			addnav('Rede mit dem Barkeeper',$filename.'?op=barkeeper');
			addnav('Zum Anlegesteg',$filename.'?op=steg');
			if(mt_rand(1,10) == 1) 
			{
				addnav('Ein kleiner weißer Hund',$filename.'?op=whitedog');
			}
			if(getsetting('scummbar_logo_stolen',0) == 0 || item_count('i.tpl_id="scumm_logo" AND i.owner='.$Char->acctid) == 0)
			{
				addnav('Das Türschild klauen',$filename.'?op=steal_scumm');            
			}
			addnav('Zurück',$filename);
		}
		break;    
	}
	case 'steal_scumm':
	{
		page_header('Die Spelunke');
		$str_output .= get_title('`:S`;c`Yu`}mm `YB`;a`:r').'`:D`;u `Ys`}c`Ihaust dich vorsichtig um und wartest einen unbeobachteten Moment ab. Dann springst du auf eines der vielen umherstehenden Fässer und beginnst daran das Schild aus seiner Halterung abzulösen. Gerade bist du noch am überlegen, ob das Fass auf dem du stehst Deko ist oder tatsächlich einen Zweck hat, da hast du das Schild auch schon abgelöst und in deiner Tasche verschwinden lassen. Hehe`}h`Ye`;e`:e!`0`n';
		item_add($Char->acctid,'scumm_logo',array('content' => array('new'=>true)) );
		savesetting('scummbar_logo_stolen',1);        
		
		addnav('Zurück',$filename);
		break;   
	}
	case 'lager':
	{
		page_header('Der Lagerschuppen');
		$str_output .= get_title('`SD`Te`;r `:L`Yagerschu`:p`;p`Te`Sn`0').'`SE`Ti`;n`:e `Ylängliche, düstere Halle, in der einige Fässer und Kisten stehen, die sich jedoch größtenteils als leer herausstellen, wenn du sie näher betrachtest. Im vorderen Teil des Schuppens sind einige Hängematten befestigt, wohl für diejenigen, die einige Nächte lang nicht auf dem Schiff schlafen wollen, wenn auch dieser Schuppen ebenso wenig einladend wird und du dich fragst, ob dieses Angebot überhaupt von jemandem genutzt wird. Im hinteren Teil ist eine große Menge Stroh ausgelegt, welches schon leicht faulig riecht. Nur Eingeweihte wissen, dass irgendwo unter diesem Stroh eine Falltür verborgen ist, welche zu dem `iwirklichen`i, verborgenen Schmugglerlager führt, in dem Rumfässer, der größte Schatz der Piraten, sowie Gold und sonstige Reichtümer aufbewahrt we`:r`;d`Te`Sn.`0`n`n';
		$str_output .= viewcommentary('pirates_lager','Hinzufügen',25);
		addnav('P?Zum Piratennest',$filename);
		break;
	}
	case 'steg':
	{
		page_header('Der Anlegesteg');
		switch($_GET['act'])
		{
			default:
			case '':
			{
				$str_output .= get_title('`SD`Te`Yr `UA`unlege`Us`Yt`Te`Sg').'`SD`Tu `Ys`Ut`uehst auf dem Steg hinter der Bar. Das Gemurmel der Bar hast du mit der Tür hinter dir gelassen und vor dir ist nur der Duft des Meeres und ein uralter Steg, an dem seit Jahren kein Boot mehr festgemacht hat. Überall klebt alter Schlick und Muscheln haben sich an den Planken ein ruhiges Plätzchen gesucht. Einzig eine Möwe sitzt dort und pickt an einer roten Wurzel h`Ue`Yr`Tu`Sm.`0';
				addnav('Zurück',$filename.'?op=spelunke');
				addnav('Wurzel nehmen',$filename.'?op=steg&act=getroot');
				break;
			}
			case 'getroot':
			{
				$str_output .= get_title('Der Anlegesteg').'`uDu versuchst dir die Wurzel zu nehmen, doch hast die Rechnung ohne den Wirt gemacht. Die Möwe pickt dir in den Finger und du reibst dir die schmerzende Hand. Was für ein gemeines Vieh!`0';
				$Char->hitpoints = max(1,$Char->hitpoints-1);
				addnav('Zurück',$filename.'?op=spelunke');
				addnav('Möwe verjagen',$filename.'?op=steg&act=driveseagullaway');
				break;
			}
			case 'driveseagullaway':
			{
				$str_output .= get_title('Der Anlegesteg').'`uDu entschließt dich, die Möwe zu verjagen und machst dafür laute Geräusche. Zusätzlich winkst du mit den Armen und läufst auf das arme Tier zu. Mit mäßigem Erfolg. Zwar kannst du die Möwe kurz aufscheuchen, aber schon Sekunden später kommt sie wütend wieder zurückgeschossen, pickt dich mal hier und mal dahin und du musst mit tränenden Augen eingestehen, dass du die Möwe so wohl nicht von ihrer Beute weg bekommst.`0';
				$Char->hitpoints = max(1,$Char->hitpoints-5);
				addnav('Zurück',$filename.'?op=spelunke');
				addnav('Nochmal versuchen',$filename.'?op=steg&act=driveseagullaway');
				break;
			}
		}
		break;
	}
	case 'deadpirate':
	{
		page_header('Ein toter Pirat');
		$str_output .= get_title('`NE`(i`)n `)t`eo`ater P`)ir`(a`Nt');
		switch($_GET['act'])
		{
			case '':
			default:
			{
				$str_output .= '`NU`(m`) d`ei`sch herum ist alles grau. Die Formen wabern ein wenig, als wären selbst solide Dinge aus dichtem Rauch geformt. Du bist noch immer in der Piratenspelunke und kein Ramius hat dich abgeholt. Was ist denn nur hier los? Außerdem kannst du die Lebenden sehen! Als helle Schemen, die sich durch den Raum bewegen. Da! Dieser Halunke dort drüben macht sich gerade an einer am Boden liegenden Gestalt zu schaffen. Er stielt '.words_by_sex('[ihm|ihr]').' die Goldbörse. Dir dämmert es. Dort liegst du. Mausetot und irgendwie doch nicht. Was soll das alles? Du blickst dich um, ob du irgendetwas erkennen ka`en`)n`(s`Nt.`0';
				
				addnav('Zurück zum Strand',$filename.'?op=deadpirate&act=nowayout&subact=door');
				addnav('Die Treppe hinauf',$filename.'?op=deadpirate&act=nowayout&subact=upstairs');
				addnav('Aus dem Fenster steigen',$filename.'?op=deadpirate&act=nowayout&subact=');
				addnav('Falltür hinter der Theke',$filename.'?op=deadpirate&act=nowayout&subact=cellar');
				addnav('Zum Abort',$filename.'?op=deadpirate&act=nowayout&subact=toilet');
				addnav('Auf den Steg',$filename.'?op=deadpirate&act=nowayout&subact=steg');
				break;   
			}
			case 'nowayout':
			{
				switch($_GET['subact'])
				{
					default:
					case '':                    
					{
						$str_output .= '`eDu versuchst dir deinen Weg zu bahnen, wirst aber von unsichtbaren Wänden oder dicken Nebelschwaden aufgehalten. Was immer du auch versuchst, du landest wieder genauso in der Spelunke wie zuvor.`0';
						addnav('Zurück',$filename.'?op=deadpirate');
						
						break;
					}
					case 'cellar':
					{
						$str_output .= '`eLebendig wärst du sowieso niemals in die heiligen Hallen des Barkeepers gekommen, denn dort bewahrt er angeblich seinen Rum auf. Warum also nicht die Gunst der Stunde nutzen und mal einen Blick riskieren? Du umrundest die Theke und gehst an der leuchtenden Gestalt des Barkeepers vorbei bis du zu der kleinen Öffnung in den Planken gelangst, die wohl in das Unterdeck führt.`0';
						addnav('Hinabsteigen',$filename.'?op=deadpirate&act=nowayout&subact=hicks');
						addnav('Zurück',$filename.'?op=deadpirate');
						break;
					}
					case 'hicks':
					{
						$str_output .= '`eUnten angekommen schaust du dich bewundernd um. Zwar kannst du rein gar nichts erkennen und rätst eher, dass die großen rundlichen Nebelschleier Fässer darstellen und die kleinen Nebelsäulen Rumflaschen sind, doch die schiere Anzahl lässt dich anerkennend nicken. Viel mehr fasziniert dich aber ein ganz anderer Anblick. Denn in einer der Ecken sitzt eine große Gestalt, die dir nur allzu bekannt vorkommt, und nippt an etwas Nebel. Ramius, der Gott der Unterwelt, sitzt hier in der Bar und genehmigt sich ein Gläschen Rum, anstatt dich zu den Schatten zu geleiten. Etwas entrüstet gehst du auf ihn zu und räusperst dich. Also wenn sich ein Gott überhaupt erschrecken kann, dann ist Ramius dem eben sehr nahe gekommen. Mit seinem unglaublich kalten Blick sieht er dich an und seine Stimme klingt wie Nadeln, die über Metall raspeln, als er zu dir spricht. "`)Das vergessen wir beide lieber ganz schnell, meinst du nicht auch?`I" Mit einem Wisch seiner Hand geleitet er dich endlich zu den Schatten, aber du bist der festen Überzeugung, dass du jetzt einen Stein bei ihm im Brett hast.`n`bDu hast soeben 5 Gefallen bei Ramius erlangt.`b`0';
						$Char->deathpower += 5;
						addnav('Zu den Schatten','shades.php');
						break;
					}
					case 'door':
					{
						$str_output .= '`eDu gehst zur Eingangstür der Spelunke, um dich draußen etwas umzusehen. '.((getsetting('scummbar_logo_stolen',0) == 1)?'Du musst ein wenig schmunzeln, denn ein flüchtiger Blick offenbart dir, dass das Logo der Scumm Bar mal wieder verschwunden zu sein scheint. ':'').'Als du dich jedoch daran machst die Tür zu öffnen und hinaus zu gehen, trittst du wieder in die Spelunke ein, so als würde man dich nicht gehen lassen wollen.`0';
						addnav('Zurück',$filename.'?op=deadpirate');
						break;
					}
					case 'upstairs':
					{
						$str_output .= '`eDu steigst die Treppe empor. Doch anstatt des bulligen Türstehers empfängt dich eine wabernde Nebelwand. So sehr du dich auch hineindrückst und dagegen wirfst, wann immer du auf der anderen Seite wieder hervorzubrechen scheinst, stehst du wieder vor der Wand als wäre nichts geschehen. Entnervt gehst du die Treppe wieder hinunter.`0';
						addnav('Zurück',$filename.'?op=deadpirate');
						break;
					}
					case 'steg':
					{
						page_header('Der Anlegesteg');
						switch($_GET['subsubact'])
						{
							default:
							case '':
							{
								$bool_got_root = (Atrahor::$Session['daily']['deadpirate_got_root'] == true);
								$str_output .= '`eDu stehst auf dem Steg hinter der Bar, doch weit kannst du nicht sehen. Überall wabert der Nebel in dicken Vorhängen an dir vorbei. Nur eine kleine Möwe sitzt anscheinend dort'.($bool_got_root?'':' und pickt auf etwas herum').'.`0';
								
								addnav('Zurück',$filename.'?op=deadpirate');
								if($bool_got_root == false) addnav('Möwe verjagen',$filename.'?op=deadpirate&act=nowayout&subact=steg&subsubact=driveseagullaway');
								break;
							}
							case 'getroot':
							{
								$str_root = $arr_roots[mt_rand(0,count($arr_roots)-1)];
								$str_output .= '`eDu kniest dich hin und nimmst die Wurzel auf. Die sieht ganz schön ramponiert aus, aber du meinst zu erkennen, dass es sich um "'.$str_root.'" handeln könnte. Du steckst deinen Fund ein, man weiß nie wann man es gebrauchen könnte.`0';
								item_add($Char->acctid,'common_root',array('tpl_name'=>$str_root));
								Atrahor::$Session['daily']['deadpirate_got_root'] = true;
								addnav('Auf den Steg',$filename.'?op=deadpirate&act=nowayout&subact=steg');
								break;
							}
							case 'driveseagullaway':
							{
								$str_output .= '`eDu entschließt dich die Möwe zu verjagen. Das geht als Geist auch sehr einfach wie dir scheint. Zwar sieht dich die Möwe nicht, doch die Berührung durch einen Geist scheint ihr gar nicht zu gefallen. Wie von der Tarantel gestochen flattert die Möwe davon und hinterlässt nichts außer einem kleinen Angstklecks auf den Planken und die Wurzel an der sie herumpickte.`0';
								addnav('Zurück',$filename.'?op=deadpirate');
								addnav('Wurzel nehmen',$filename.'?op=deadpirate&act=nowayout&subact=steg&subsubact=getroot');
								break;
							}
						}
						break;
					}
				}
				break;
			}
		}
		break;
	}
	default: 
	{	
		page_header('Das Piratennest');
		$str_output .= get_title('`,D`:a`;s P`Yiraten`;ne`:s`,t`0').'`,I`:n `;ei`Yner versteckten Bucht lagern einige Schiffe, in mehr oder weniger gutem Zustand. Einige tragen schwarze Segel, einige die üblichen weißen, doch alle haben sie stolz die Flagge der Piraten gehisst. Hin und wieder sieht man zwielichtige Gestalten, größtenteils Männer, aber auch einige Frauen, eines der Schiffe betreten oder verlassen.`nIn einiger Entfernung vom Strand stehen ein paar aus Holz zusammengezimmerte Gebäude, Lagerschuppen für Schmugglergut, aber auch ein Haus, welches sich in seiner Bauart von den Schuppen unterscheidet, denn es ist geformt wie die hintere Hälfte eines Schiffes. Das Schild über der Tür und der Lärm, den man schon aus einiger Entfernung hört, lassen darauf schließen, dass es sich um eine Spelunke han`;de`:l`,t.`0`n`n';
		$str_output .= viewcommentary('pirates','Hinzufügen',25);
		addnav('');
		addnav('S?Die Spelunke',$filename.'?op=spelunke');
    addnav('L?Der Lagerschuppen',$filename.'?op=lager');	
		addnav('');
		addnav('Zurück zum Hafen','hafen.php');        
		break;
	}
}
output($str_output);
page_footer(); 

?>