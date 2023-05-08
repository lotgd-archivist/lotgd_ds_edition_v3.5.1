<?php
/*von Deriel und Callyshee für Atrahor.de */
require_once 'common.php';
$show_invent = true;
$filename=basename(__FILE__);

define('OPENTIME','20:00'); //Öffnungszeit Karneval
define('CLOSETIME','07:00'); //Schließungszeit Karneval
$cost = 200; //Preis der Wahrsagerin
$wks = 5; //benötigte (und abzuziehende) WKs fürs Spiegelkabinett

addcommentary();
$bool_return_viewcommentary_output = true; //?
checkday();

page_header('Das Zigeunerlager');
switch($_GET['op'])
{
	case 'zirkus':
		$str_output .= get_title('`jM`2i`Jt`Nt`Sn`;a`:cht Ka`;r`Sn`Ne`Jv`2a`jl`0').'`:E`;t`Swas abseits des eigentlichen Zigeunerlagers wurden bunt bemalte Wagen in kreisrunder Formation abgestellt, deren vergitterte Seitenflächen sie schnell als sehr geizig bemessene Käfige erkennbar machen.`n
		`c`("Avia Fortunas Mittnacht Karneval"`c
		`n
		`Swurde in unsorgfältiger Schrift angepinselt. Offensichtlich unterhält die alte Zigeneurin hier unter der Aufsicht ihres Urenkels `JM`No`Sh`;r`:o`S, einem Mann mittleren Alters dessen Körpergröße in keinem Verhältnis zu seiner großen Unfreundlichkeit steht, ein mehr oder weniger lukratives Schaustellergeschäft. In unregelmäßigen Abständen führt der griesgrämige Zigeuner sogar schaulustige, kleine Gruppen von Bürgern aus der Stadt vorbei an den Käfigen und erklärt deren Inhalt mit drohender, grollender Stimme und recht übertriebenen Gest`;e`:n.`n`n';
	  $time = getgametime(true);
	  if ($time > OPENTIME || $time < CLOSETIME)
	  {
	    //Mitternachtszirkus
	    $str_output .= '`NAuf dem Platz ist es dir möglich einen flüchtigen Blick in die Käfige zu werfen, doch du traust deinen Augen kaum, was du dort zu sehen vermagst! Ein `8M`aa`6n`Nti`(cor`N schleicht lauernd in seinem Käfig auf und ab. Das donnernde Grollen eines `$S`Aa`,t`my`Nrs lässt dich schaudern und einen Käfig weiter windet sich der dornenbesetze Körper eines echten `jDr`2ac`Jhl`Nin`(gs`N. Im hintersten Käfig steht stolz und prächtig ein `*Ei`fnh`&orn`N, doch meinst du vorerst genug der Zigeunermagie gesehen zu haben.`n`n';
	  }
	  else
	  {
	    $str_output .= '`(Auf dem Platz ist es dir möglich einen flüchtigen Blick in die Käfige zu werfen, doch die Aussichten sind mehr als enttäuschend. Ein zahnloser `UL`uö`tw`ye`(, ein alter `NAf`(f`)e`(, eine mickrige `8Sc`ah`6la`an`8ge`( und ein klappriger `)Es`eel`( fristen hier ihr klägliches Dasein und verschlafen deine Anwesenheit gänzlich. Schon deine bloße Neugierde scheint hier zuviel Beachtung gewesen zu sein...`n`n';
	  }
		$str_output .= viewcommentary('zirkus','Hinzufügen',25);
	  addnav('L?Zurück ins Lager',$filename);
	break;
	
	case 'wahrsagerin':
	  $str_output .= get_title('`ZD`]ie`: He`;llse`:h`]er`Zin`0').'`YW`Te`Sr die schmale Tür des hölzernen Wagens öffnet, wird begrüßt vom entsetzlich muffigen Gestank im Inneren des winzigen, dunklen Verschlags. `Z"Av`]i`:a`; Fort`:u`]n`Za"`S nennen sie die Zigeuner und respektieren die kleine, alte Frau voller Ehrfurcht als ihr spirituelles Oberhaupt. In der Tat besitzt ihre Erscheinung etwas rätselhaft Unmenschliches; Regungslos sitzt die Hellseherin auf ihrem hölzernen Schemel in der hintersten Ecke des Wagens vor einem niedrigen Tisch, die Hände auf einer großen, gläsernen Kugel gebettet. Wie die meisten Zigeuner fröhnt auch sie einer Vorliebe für schweren Goldschmuck und bunten Stoffen, macht aber keinesfalls einen nur ansatzweise so lebensfrohen Eindruck. Ihr faltiges, hässliches Gesicht und die grauen Haare, welche bis zum Boden reichen, lassen kaum zu ihr Alter zu schätzen, aber mit Sicherheit bist du nicht gekommen um das zu erfragen. `Z"...Sprich mein Kind, was kann ich für dich tun? Nein warte, du musst es nicht sagen. Ich weiß welchen Rat du brauchst..!" `Skrächzt die Alte mit angsteinflößender Stimme und hebt ihre klauenartigen Hände über die milchige Kristallkug`Te`Yl.`n`n';
	  addnav('W?Wahrsagen lassen (200 Gold)',$filename.'?op=wahrsagen');
	  addnav('L?Zurück ins Lager',$filename);
	break;
	
	case 'wahrsagen':
	  if ($Char->gold >= $cost || ($Char->goldinbank + $Char->gold) >= $cost)
	  {
	    if($Char->gold < $cost)
	    {
	      $Char->goldinbank -= ($cost - $Char->gold);
	      $Char->gold = 0;
	    }
	    else
	    {
	      $Char->gold-=$cost;
	    }
	    $str_output .= '`SDie Hellseherin hat einen weisen Rat für dich, den du beherzigen solltest: `Z';
	    switch (e_rand(1,15)){
	      case 1: //WK +
	        $str_output .= '"Du wirst das Abenteuer finden, oder das Abenteuer findet dich."`n`n
					`SPlötzlich fühlst du dich motiviert, das Abenteuer zu suchen und erhältst zwei zusätzliche Runden.';
	        $Char->turns += 2;
	      break;
	      case 2: // Gem -
	        $str_output .= '"Wer seine Nase hoch trägt, wird leicht daran herum geführt."`n`n
					`SUnd während du noch versuchst, dir aus diesen Worten einen Reim zu machen, wirst du von einem kleinen Jungen angerempelt. Erst als er schon weit fort ist, merkst du, dass dir ein Edelstein fehlt.';
	        $Char->gems --;
	      break;
	      case 3: //Heilung
	        $str_output .= '"Totgesagte leben länger."`n`n
					`SNoch während du versuchst, dir daraus einen Reim zu machen, erfüllt eine tiefe Ruhe dich. So gestärkt sind die Worte der Wahrsagerin schnell vergessen';
	        if($Char->hitpoints < $Char->maxpoints){
	          $Char->hitpoints = $Char->maxpoints;
	        }          
	      break;
				case 4: // Charm +
					$str_output .= '"Pech im Spiel, Glück in der Liebe."`n`n
					`SUnd während du noch versuchst, dir aus diesen Worten einen Reim zu machen, fällt dein Blick auf eine hübsche Zigeunerin, die dir schöne Augen macht. Sogleich fühlst du dich attraktiver.';
	        $Char->charm ++;
	      break;
	      case 5: //WK -
	        $str_output .= '"Wer immer im Gestern und Morgen lebt, verschläft das Heute."`n`n
					`SUnd tatächlich merkst du, dass du von diesem Tag nur wenig gehabt hast, aber bereit bist, dich zur Ruhe zu legen.';
	        $Char->turns = 0;
	      break;
	      case 6: //Gem +
	        $str_output .= '"Geld allein macht nicht glücklich."`n`n
					`SDoch während du noch versuchst, dir aus diesen Worten einen Reim zu machen, entdeckst du zu deinen Füßen einen funkelnden Edelstein und schon sind die Worte der Wahrsagerin vergessen.';
	        $Char->gems ++;
	      break;
	      case 7: //Charm -
	        $str_output .= '"Häßlichkeit entstellt immer, selbst das schönste Frauenzimmer."`n`n
					`SNoch während du noch versuchst, dir aus diesen Worten einen Reim zu machen, springen dich ein paar eingelegte Warzen aus einem offenen Glas an und saugen sich an dir fest.';
					$Char->charm --;
	      break;
	      case 8: //Gold +
	        $str_output .= '"Behalte deine Füße im Blick, denn das Gold liegt nicht zwischen den Sternen."`n`n
	        `SNoch während du noch versuchst, dir aus diesen Worten einen Reim zu machen, entdeckst du ein paar Goldstücke auf einem Baumstamm und schon sind die Worte der Wahrsagerin vergessen.';
	        $Char->gold += 100;
	      break;
	      case 9: //Exp +, Schaden
	        $str_output .= '"Gebrannte Kinder scheuen das Feuer."`n`n
					`SNoch während du noch versuchst, dir aus diesen Worten einen Reim zu machen, stößt du dir deinen Großen Zeh an einem Felsbrocken. Um ein paar Erfahrungspunkte reicher, sind die Worte der Wahrsagerin schnell vergessen.';
					$Char->hitpoints = $Char->hitpoints*0.98;
					$Char->experience = $Char->experience*1.03;
	      break;
	      case 10: //Exp -, Schaden
	        $str_output .= '"Nicht alles Gute kommt von oben."`n`n
					`SNoch während du versuchst, dir daraus einen Reim zu machen, fällt dir ein dicker Ast auf den Kopf und so sind die Worte der Wahrsagerin schnell vergessen.';
					$Char->hitpoints = $Char->hitpoints*0.98;
					$Char->experience = $Char->experience*0.98;
	      break;
	      case 11: //Gold -
	        $str_output .= '"Es ist nicht alles Gold, was glänzt."`n`n
					`SNoch während du versuchst, dir daraus einen Reim zu machen, reißt dir ein störrischer Ast ein Loch in deinen Geldbeutel und so sind die Worte der Wahrsagerin schnell vergessen.';
					$Char->gold -= 100;
	      break;
	      case 12: //Schaden
	        $str_output .= '"Keine Rose ist ohne Dornen."`n`n
					`SNoch während du versuchst, dir daraus einen Reim zu machen, siehst du wilde Rosen am Wegerand und stichst dich prompt an einem Dorn. Der Schmerz lässt dich die Worte der Wahrsagerin vergessen.';
					$Char->hitpoints = $Char->hitpoints*0.75;
	      break;
	      case 13: //Gem -
	        $str_output .= '"Das Glück ist eine leichte Dirne."`n`n
					`SNoch während du versuchst, dir daraus einen Reim zu machen, fällt ein Edelstein aus deiner Tasche. Da du jetzt mit fluchen beschäftigt bist, sind die Worte der Wahrsagerin schnell vergessen.';
					$Char->gems --;
	      break;
	      case 14: //WK -
	        $str_output .= '"Du siehst den Wald vor lauter Bäumen nicht."`n`n
					`SNoch während du versuchst, dir daraus einen Reim zu machen, verlierst du die Orientierung und vergisst die Worte der Wahrsagerin.';
					$Char->turns --;
	      break;
	      case 15: //Schaden
	        $str_output .= '"Was nicht tötet, härtet ab."`n`n
					`SNoch während du versuchst, dir daraus einen Reim zu machen, füllst du das Bedürfnis in dir wachsen, in den Wald zu gehen und so sind die Worte der Wahrsagerin vergessen.';
					$Char->turns ++;
	      break;
	    }
	  }else
	  {
	    $str_output .= '`YG`Tr`Sade als du dachtest, die Alte würde dir nun die Zukunft deuten, nimmt sie ihre sehnigen Finger wieder von der milchigen Kugel und krächzt dir ein vorwurfsvolles `Z"Avina Fortunas Dienste nimmt man nicht wie ein Geschenk entgegen! Eine kleine Aufwandsentschädigung wäre mehr als angebracht..."`S entgegen. Offensichtlich erwartet sie eine Investition von`^ '.$cost.' Goldstücken `Sfür ihre spirituellen Dienste, die du aber nicht besitz`Ts`Yt..';
	  }
	  addnav('L?Zurück ins Lager',$filename);
	break;
	//forest.php?specialinc=EREIGNIS
	
	
	case 'kabinett':
	  switch($_GET['act']){
	    case 'go':
	      if ($Char->turns < $wks){
	        $str_output .= '`TS`Schweren Herzens willst du ihm die Perle in die bereits ausgestreckte Hand legen, doch er zieht sie zurück und sagt: "Kommt wieder, wenn Ihr in einer besseren Verfassung seid."`n
	Seltsam, warum solltest du ausgeruhter sein, wenn du ein Spiegelkabinett besichtigst? Allerdings lässt er nicht mehr mit sich verhandeln und so gehst zu schließlich wieder deiner Weg`Te.';
	      }else{
	        $str_output .= '`TS`Schweren Herzens legst du ihm die Perle in die bereits ausgestreckte Hand und siehst zu, wie jene verschwindet. Zeit über deinen Verlust nachzusinnen hast du allerdings nicht, denn schon öffnet der Zigeuner die Tür zu seinem Wagen für dich, damit zu eintreten kannst.
	Spiegel erwarten dich dort und obwohl der Raum nur von wenigen Kerzen erhellt wird, kannst du gut genug sehen, um zu erkennen, das in jedem Spiegel, zwanzig an der Zahl, du selbst zu sehen bist.`n
	`)"Dies sind ganz besondere Spiegel, mit den alten Zaubern der Roma durchtränkt und von meiner Familie seid Jahrhunderten gehütet. Sie zeigen Euch nicht nur eine mögliche Zukunft, sondern erlauben es Euch diese zu betreten."`S`n
	Willst du einen der Spiegel näher betrachten oder diesem Hexenwerk so schnell wie möglich den Rücken zukehre`Tn?';
	        item_delete('tpl_id = "perl" AND owner='.$Char->acctid,1);
	        addnav('Spiegel betrachten',$filename.'?op=kabinett&act=in&spiegel=1');
	      }
	    break;
	    case 'in':
	      $str_output .= '`&Der Spiegel zeigt dir...`n`n';
	      $next = $_GET['spiegel'] + 1;
	      $last = $_GET['spiegel'] - 1;
	      if($_GET['spiegel']<20){
	        addnav('Nächstes Ereignis',$filename.'?op=kabinett&act=in&spiegel='.$next);
	      }
	      elseif($_GET['spiegel']==19){
	        addnav('Erstes Ereignis',$filename.'?op=kabinett&act=in&spiegel=1');
	      }
	      if($_GET['spiegel']>1){
	        addnav('Vorheriges Ereignis',$filename.'?op=kabinett&act=in&spiegel='.$last);
	      }
	        switch($_GET['spiegel']){
	          case 1: //erstes ereignis: Oger mit Schatzkiste
	            $str_output .= '`f...wie du dich einem Monster näherst, welches einen Schatz bewacht.`n`n';
	            $Char->turn -= $wks;
	            //addnav('Durch den Spiegel','forest.php?specialinc=ogre.php');
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='ogre.php';
	          break;
	          
	          case 2: //zweites ereignis: Geschenk verschicken
	            $str_output .= '`f...einen fahrenden Händler, welcher dir seine Waren zum Kauf anbietet.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='surprise.php';
	          break;
	          
	          case 3: //Wiedererweckung eines Toten durch weiße Lilien
	            $str_output .= '`f...dich selbst auf einer Lichtung voller weißer Lilien.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='whitelilies.php';
	          break;
	          
	          case 4: //Gebüschhändler
	            $str_output .= '`f...wie du dich mit einem Mann unterhältst, der einer der berühmten Gebüschhändler sein muss.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='forest_shrubbery.php';
	          break;
	          
	          case 5: //Spezialfähigkeiten-Altar
	            $str_output .= '`f...wie du vor einem Altar stehst, auf dem mehrere Gegenstände liegen.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='alter.php';
	          break;
	          
	          case 6: //Pirat beleidigen
	            $str_output .= '`f...einen Pirat, der dich zu einem Duell der Worte herausfordert.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='beleidgterpirat.php';
	          break;
	          
	          case 7: //Trainingsgeräte+Kuscheltiere
	            $str_output .= '`f...Bregomil,  den Künstler und Handwerker, welcher dir seine Dienste anbietet.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='bregomil.php';
	          break;
	          
	          case 8: //Idolsuche
	            $str_output .= '`f...wie du auf ein halb überwuchertes Grab stößt.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='cairn.php';
	          break;
	          
	          case 9: //Treffen mit Ramius
	            $str_output .= '`f...wie du einem unheimlichen Fremden im Wald begegnest.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='derfremde.php';
	          break;
	          
	          case 10: //Schwarze Juwelen - Begegnung mit Hexe
	            $str_output .= '`f...wie inmitten des Waldes auf die legendäre `iHexe`i triffst.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='forest_black_jewels.php';
	          break;
	          
	          case 11: //Affenbande im Wald
	            $str_output .= '`f...wie du von einer Affenbande umringt wirst.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='forest_monkey_island.php';
	          break;

	          case 12: //schwarze Feder
	            if($Char->marks<CHOSEN_FULL)
	              { //nicht Auserwählt
	                $str_output .= '`f...wie du einfach im Wald stehst.`n`n';
	              }else{
	                $str_output .= '`f...wie du in einer verlassenen Kirche ein Blumenbeet findest.`n`n';
	              }
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='forestchurch.php';
	          break;
	          
	          case 13: //Kneipe, anmachversuch
	            $str_output .= words_by_sex('`f...wie du im Eberkopf [einer der schönsten Frauen|einem der schönsten Männer] der Stadt begegnest.`n`n');
	            if ($Char->sex==1) {
	              addnav('Durch den Spiegel','forest.php');
								$Char->specialinc='inn_pickup_ladies.php'; 
	            }else{
	              addnav('Durch den Spiegel','forest.php');
	              $Char->specialinc='inn_pickup.php';
	            }
	          break;
	          
	          case 14: //Elfenkunst
	            $str_output .= '`f...wie du zu dem Baumhaus eines Elfen kletterst.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='jewelrymaker.php';
	          break;
	          
	          case 15: //Kadaverpuppe
	            $str_output .= '`f...wie du einer jungen Frau begegnest, die auf einer Bank sitzt.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='may.php';
	          break;
	          
	          case 16: //Runenmeister
	            $str_output .= '`f...wie du dem legendären Runenmeister begegnest.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='runemaster.php';
	          break;
	          
	          case 17: //Insigniensplitter
	            $str_output .= '`f...wie du einen der kostbaren Insigniensplitter findest.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='findregalia.php';
	          break;
	          
	          case 18: //Gruft mit Insignie
	            $str_output .= '`f...wie du vor einer Gruft stehst.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='gruft.php';
	          break;
	          
	          case 19: //Leiche begraben
	            $str_output .= '`f...zeigt dir wie du über eine Leiche im Wald stolperst.`n`n';
	            addnav('Durch den Spiegel','forest.php');
	            $Char->specialinc='corpse.php';
	          break;
	        
	        
	        }
	      $str_output .= '`TW`Sillst du durch diesen Spiegel gehen, dich einem anderen zuwenden oder lieber verschwinde`Tn?';
	    break;
	    
	    case 'no':
	      $srt_output .='`TD`Su weißt zwar nicht mehr genau, woher du die Perle hast, aber du wirst sie auf keinem Fall irgendeinem dahergelaufenem Kerl geben. So teuer möchtest du für deine Neugierde nun wirklich nicht bezahle`Tn. ';
	    break;
	    
	    default:
	      $str_output .= get_title('`fD`&a`es`) S`(p`Nie`Sg`Telk`Sa`Nb`(i`)n`ee`&t`ft').'`TE`Stwas abseits von den Übrigen steht ein Wagen, der mit bunten Symbolen verziert ist, welche ineinander zu verschwimmen, sollte jemand sie zu lange betrachten. Ein Mann sitzt davor und schaut auf, als du dich ihm näherst. Anders als `SV`]e`Zs`zs`Oa und Av`]i`:a`; Fort`:u`]n`Za`S scheint ihm nichts an der Aufmerksamkeit jener zu liegen, die diesen Ort besuchen um etwas Aufregendes zu erleben und gerade dies macht dich neugierig.`n';
	      if (item_count('tpl_id = "perl" AND owner='.$Char->acctid) == 0)
	      {
	        $str_output .= words_by_sex('`)"Ihr habt wohl von einem Wanderer von meinem Spiegelkabinett gehört und wollt dieses Wunder nun selbst am eigenen Leib erfahren, ist es nicht so, [Fremder|Fremde]?" `SEr mustert dich mit seinen Habichtaugen, denen weder die Art dich zu kleiden noch dein Verhalten entgeht. `)"Doch Ihr habt nichts, was ich begehren könnte. Kommt zurück, wenn ein wahrhaftes Kleinod Euer Eigen nennt."`S`n
	Seltsamer Bursche, der sich nicht mit Gold oder Edelsteinen zufrieden gibt, doch dich mit einer aus der Sippe der Zigeuner anzulegen ist nicht ratsam, also gehst du wortlos davo`Tn.');//leider keine perle
	      }else{
	        $str_output .= words_by_sex('`)"Ihr habt wohl von einem Wanderer von meinem Spiegelkabinett gehört und wollt dieses Wunder jetzt nun am eigenen Leib erfahren, ist es nicht so, [Fremder|Fremde]?" `SEr mustert dich mit seinen Habichtaugen, denen weder die Art dich zu kleiden noch dein Verhalten entgeht. `)"Ein Kleinod nennt Ihr Euer Eigen, Perlen sind für mich die schönsten Schmuckstücke und für eine solche würde ich Euch wahrlich einen Blick ins Spiegelkabinett gewähren. Was sagt Ihr dazu?"`S`n
	Du fragst dich, woher der Mann weiß, was du in deinen Taschen verbirgst, erinnerst dich aber, das solche Fähigkeiten vielen Zigeunern nachgesagt werden. Jetzt musst du dich entscheiden, gibst du ihm die Perle und trittst ein in das Spiegelkabinet`Tt?');//betreten? 
	        addnav('Die Perle abgeben',$filename.'?op=kabinett&act=go');
	        addnav('Die Perle behalten',$filename.'?op=kabinett&act=no');
	      }
	    break;
	  }
	  addnav('L?Zurück ins Lager',$filename);
	break;
	
	default:
	  $str_output .= get_title('`SDa`]s Z`:ig`Leune`:rl`]ag`Ser`0').'`SN`]icht weit von den Toren der Stadt entfernt, verborgen im dichten Unterholz, hat sich reisendes Volk angesiedelt und seine Wagen im schlammigen Boden abgestellt. Diese Menschen, gewandet in ungewöhnlich farbenreicher Kleidung und nicht minder auffällig dank ihrer sonnenverwöhnte Haut, sind den meisten Bürgern suspekt und genießen kein sonderlich hohes Ansehen innerhalb der Städte des Königreiches. Sie gelten vielerorts als verschlagen, listig oder sogar diebisch und ihnen werden Bündnisse mit den dunkelsten Göttern und Dämonen nachgesagt.`n
	Hier aber vermagst du nicht mehr zu erblicken als einige `SZi`;ge`:un`Zer`], die im Kreise ihrer Gemeinschaft am Lagerfeuer zusammensitzen, oder schöne, temperamentvolle `LFr`:a`]u`Sen`] die zu den Klängen fremdländischer Instrumente eigenwillige Tänze vollführen. Die zwanglose, aber gleichzeitig spannende Atmosphäre dieses Lagers läd leicht dazu ein, es zu erkunden und die vielen Sagen und Mythen des Wandervolkes zu ergründe`Sn.`n`n';
	  $str_output .= viewcommentary('zigeuner','Hinzufügen',25);
	  $Char->specialinc='';
	  addnav('d?Stadttor','dorftor.php');
	  addnav('V?Vessas Zelt','gypsy.php');
	  addnav('M?Mitternachtskarneval',$filename.'?op=zirkus');
	  addnav('W?Wahrsagerin',$filename.'?op=wahrsagerin');
		addnav('S?Spiegelkabinett',$filename.'?op=kabinett');

	break;

}
output($str_output);
page_footer();
?>
