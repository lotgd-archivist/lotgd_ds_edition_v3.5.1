<?php

/**
 * @desc code, text and idea by Draza´ar
 * made for Vinestra
 * http://logd.legend-of-vinestra.de
 * drazaar@legend-of-vinestra.de
 * Feedback und Verbesserungsvorschläge sind erwünscht! ^^
 **/
$str_out ='';
switch($_GET['op']) {
	/**
	 * Special zu Beginn gleich verlassen
	 */
	case 'leave':
		$str_out = '`&Anderen Leuten helfen? Du bist hier ja nicht der güldene Samariter, außerdem hast du auch wirklich
				    Besseres zu tun!`n
				    Du ziehst dich in den Wald zurück.';

        /** @noinspection PhpUndefinedVariableInspection */
        $Char->specialinc = '';
		break;
	
	/**
	 * Der Person helfen
	 */
	case 'help':
		$str_out = '`&Edlen Gemüts stürmst du natürlich sofort los um der Hilfeschreienden 
				    heldenhaft beizustehen. `n`n
				    Du schlägst dich durch die Büsche und entdeckst schließlich wie drei Goblins eine wunderschöne, junge, anmutige Prinzessin  
				    mit ihren spitzen Messern bedrohen. Das reicht dir auch schon! Wild mit deiner Waffe rumfuchtelnd und einem Kampfschrei auf den Lippen brichst du durchs Unterholz. `n`n';
		
		$int_switch = mt_rand(0, 20);
		switch($int_switch) {
			/**
			 *	Kerker
			 */
			case 0: // mus aus irgendeinem Grund extra erwähnt werden  -Tyndal
			case ($int_switch <= 10):
				$str_out .= 'Und du musst feststellen, dass das Geschrei und das gekonnte Spiel mit deiner Waffe seine Wirkung nicht verfehlt. 
							 Die Goblins rennen allesamt querfeldein in verschiedene Richtungen, was dir nun auch gerade recht ist. `n`n
							 Gerade willst du dein Wort an die Prinzessin richten, als ziemlich viele Reiter 
							 um dich herum zum stehen kommen. `n
							 `$"Ahhhja, da haben wir Dich ja! Die Prinzessin entführen wollen, das haben wir ja gern!!"`&, ruft dir der Kommandant entgegen. `n
							 `9"Waren es vorher nicht mehrere und irgendwie kleinere?"`&, fragt einer der Soldaten etwas leiser gen Kommandant. `n
							 `$"Egal, hauptsache einen Entführer." `&ist die knappe Antwort, wobei dich einer der anderen Soldaten mit einem wuchtigen Schlag auf den Hinterkopf ins Reich der Träume schickt... `n
							 Die Prinzessin steht unterdessen untätig herum, anscheinend steht sie unter Schock...';

                /** @noinspection PhpUndefinedVariableInspection */
                $Char->specialinc = 'forest_kerker.php';
				
				addnav('Aktionen');
				addnav('Weiter', 'forest.php?op=injail');
				break;
			
			/**
			 *	Belohnung
			 */
			case ($int_switch < 20):
				$gems = mt_rand(2, 3);

                /** @noinspection PhpUndefinedVariableInspection */
                $extragold = mt_rand(1, ($Char->level*100));
				if($gems == 2)
				{
					$gold = $Char->level > 5 ? 5000 : ($Char->level * 1000);
				}
				elseif($gems == 3)
				{
					$gold = $Char->level > 5 ? 3750 : ($Char->level * 750);
				}
				
				$gold = $gold + $extragold;
					
				$str_out .= '`&Und du musst feststellen, dass das Geschrei und das gekonnte Spiel mit deiner Waffe seine Wirkung nicht verfehlt. 
							 Die Goblins rennen allesamt querfeldein in verschiedene Richtungen, was dir nun auch gerade recht ist. 
							 Die Prinzessin steht währenddessen zwar etwas geschafft, aber glücklich strahlend neben dir. `n
							 `g"Danke für Eure Hilfe! Ich dachte schon, ich wäre verloren..."`&, spricht sie. Plötzlich sind Pferdehufe 
							 auf dem Waldboden zu hören und ehe du dich versiehst, stehen bei euch auch schon einige in schwere Rüstungen gehüllte Reiter, samt einer Kutsche. `n
							 `$"Ahh, wie ich sehe, habt Ihr die Prinzessin bereits befreit. Hier, nehmt das als Lohn für Eure gute Tat!!!"`&, spricht der Kommandant 
							 und wirft dir ein Säckchen zu. `n
							 Jeder bedankt sich noch einmal bei dir und schließlich wird die Prinzessin in die Kutsche verfrachtet und zurück zu ihrem Schloss gebracht. `n`n`n
							 `^Du bekommst `%'.$gems.' `^Edelsteine und `%'.$gold.' `^Gold!';
				
				$Char->gold += $gold;
				$Char->gems += $gems;
				
				$Char->specialinc = '';
				break;
			
			/**
			 *	Absolut schlimmster Fall
			 */
			case 20:
				$str_out .= '`&Doch irgendwie musst du feststellen, dass sich so eine Gruppe Goblins nicht so leicht beeindrucken lässt. `n';

                /** @noinspection PhpUndefinedVariableInspection */
                if($Char->dragonkills < 20) {
					$str_out .= 'Schneller als du gucken kannst, hast du keine Waffe mehr in der Hand, dafür hat einer der Goblins nun zwei.
							     Dich auslachend macht der größte von ihnen eine fortscheuchende Bewegung mit seiner Hand. 
								 `2"'.($Char->sex?'Die is': 'Der is').' ja noch so klein, lohnt ja gar nich!"`&, höhnt er. `n`n
								 In Anbetracht dessen, dass das gerade ein ziemlicher Griff ins Plumpsklo war, befolgst du lieber den Rat des Goblins und ziehst dich zurück. `n`n';
					
					if($Char->level < 10) {
						$str_out .= 'Gerade als du drei Schritte gegangen bist, trifft dich etwas Hartes am Hinterkopf. 
								    `2"Vergiss nich dein Ding hier!" `&hörst du noch den Goblin nachrufen. Du gehst bewusstlos zu Boden, ausgeknocked von deiner eigenen Waffe!! `n`n`n
									`^Du verlierst fast alle Lebenspunkte.`n
									Du verlierst `%'.($Char->turns>=2?'2':$Char->turns).' `^Waldkämpfe!';
						
						$Char->hitpoints = 1;
						$Char->turns -= $Char->turns >= 2 ? 2 : $Char->turns;
						
						$Char->specialinc = '';
					}
					else {
						$str_out .= 'Zu blöd, dass der kleine Kerl noch deine Waffe hat. Jetzt stehst du wieder mit leeren Händen da und musst dir wohl eine neue kaufen! `n`n`n
									 `^Du verlierst deine Waffe!';
						debuglog('Verlor seine Waffe bei den Goblins');
						
						item_set_weapon('Fäuste',0,0,0,0,2);
						
						$Char->specialinc = '';
					}
					break;
				}
				elseif($Char->dragonkills < 75) {
					$str_out .= 'Und so kommt es zum Kampf. Du schlägst dich gut, doch gegen drei Gegner ist es schwer und Goblins sind nicht gerade dafür berühmt, faire Kämpfer zu sein. `n
								 Ein unbedachter Moment und du wirst hinterrücks niedergestochen und gehst tot zu Boden. Weil die Goblins aber Angst haben, dass du doch noch 
								 einmal aufstehen könntest, verschwinden sie samt Prinzessin. `n`n`n
								 `^Du bist TOT! `n
								 `^Du verlierst `%10% `^Erfahrung!';
					
					$Char->alive = false;
					$Char->hitpoints = 0;
					$Char->experience -= round($Char->experience * 0.1);
                    CQuest::died();
					addnav('Zu den Schatten', 'shades.php');
					
					$Char->specialinc = '';
				}
				else {
					$str_out .= 'Und so kommt es zum Kampf. Du schlägst dich gut, doch gegen drei Gegner ist es schwer und Goblins sind nicht gerade dafür berühmt, faire Kämpfer zu sein. `n
								Ein unbedachter Moment und du wirst hinterrücks niedergestochen und gehst tot zu Boden. Da die Goblins dich doch als recht gefährlich betrachtet haben, gehen sie 
								auf Nummer sicher, dass du auch wirklich tot bist. Dabei nehmen sie auch noch all dein Gold an sich! `n`n`n
								`^Du bist TOT! `n
								Du verlierst all dein  Gold! `n
								Du verlierst `%10% `^Erfahrung!';
					
					$Char->alive = false;
					$Char->hitpoints = 0;
					$Char->gold = 0;
					$Char->experience -= round($Char->experience * 0.1);
                    CQuest::died();
					addnav('Zu den Schatten', 'shades.php');
					
					$Char->specialinc = '';
				}
				break;
		}
		break;
	
	/**
	 * Im Kerker
	 */
	case 'injail':
		if(!isset($_GET['count']))
		{
			$count = 0;
		}
		else 
		{
			$count = $_GET['count'];
		}
		
		switch($count) {
			case 0:
				addnav('Aktionen');
				$str_out = '`&Du wachst im Kerker wieder auf. Die Luft ist feucht und riecht nach allem Möglichen, was du dir lieber gar nicht so
							genau vorstellen willst. In einer Ecke steht ein Krug Wasser und eine Schüssel mit irgendeinem Brei samt Löffel. `n
							Nicht sehr dankbar die Kerle, immerhin hast du ihre Prinzessin befreit... `n`n
							Was willst du nun tun? `n`n
							'.create_lnk('Den Löffel benutzen um einen Gang nach draußen zu graben.','forest.php?op=spoon&count='.$count,true,true,'',false,'Gang graben').'`n
							'.create_lnk('Nach Hilfe schreien und um Gnade winseln..','forest.php?op=cry&count='.$count,true,true,'',false,'Um Gnade winseln');				
				break;
			
			case 1:
				addnav('Aktionen');
				$str_out = '`&Du sitzt noch immer im Kerker, was Deine Laune nicht gerade verbessert. 
							Angestrengt überlegst Du Dir, was Du nun tun könntest um hier herauszukommen. `n`n
							'.create_lnk('Den Löffel benutzen um einen Gang nach draußen zu graben.','forest.php?op=spoon&count='.$count,true,true,'',false,'Gang graben').'`n
							'.create_lnk('Nach Hilfe schreien und um Gnade winseln..','forest.php?op=cry&count='.$count,true,true,'',false,'Um Gnade winseln');
					
				break;
				
			case 2:
				addnav('Aktionen');
				$str_out = '`&Langsam aber sicher fragst du dich, ob dieser Kerker Deine letzte Ruhestätte wird. 
							Doch von diesem Gedanken bist du nicht sonderlich begeistert, weshalb du dir überlegst, was du 
							nun probieren sollst. `n`n
							'.create_lnk('Den Löffel benutzen um einen Gang nach draußen zu graben.','forest.php?op=spoon&count='.$count,true,true,'',false,'Gang graben').'`n
							'.create_lnk('Nach Hilfe schreien und um Gnade winseln..','forest.php?op=cry&count='.$count,true,true,'',false,'Um Gnade winseln');
				
				break;
		}

        /** @noinspection PhpUndefinedVariableInspection */
        $Char->specialinc = 'forest_kerker.php';
		break;
		
	case 'spoon':
		$count = $_GET['count'];
		$count++;
		
		if($count == 3) {
            /** @noinspection PhpUndefinedVariableInspection */
            $str_out .= '`&Gerade willst du dich daran machen mit dem Löffel im Boden herumzubuddeln um "irgendwann"
						 einen Gang nach draußen gegraben zu haben, da wird auch schon deine Zellentüre aufgestoßen. 
						 Hastig wirfst du den Löffel weg und blickst den Kommandanten von vorher erstaunt an. `n
						 Dieser sagt nicht viel, sondern gibt seinen Männern ein Handzeichen, woraufhin diese dich 
						 wieder einmal unsanft packen und einige Minuten später unsanft vor dem Burgtor absetzen. Du willst 
						 schon fragen, was das Ganze zu bedeuten hat, doch da sind alle außer dir auch schon wieder verschwunden.
						 Ganz schön undankbar, das muss man doch sagen. Wenigstens hast du aus dieser Aktion etwas gelernt...`n`n`n
						 '.($Char->turns>0?'`^Du verlierst `%'.($Char->turns>=3?'3':$Char->turns).' `^Waldkämpfe! `n':'').'
						 `^Du bekommst `%5% `^Erfahrung!';
			
			$Char->experience += round($Char->experience * 0.05);
			$Char->turns = max(0,$Char->turns-3);
			
			$Char->specialinc = '';
		}
		else {
			switch(e_rand(1,6)) {
				case 1: case 2:
					/** @noinspection PhpUndefinedVariableInspection */
                    $str_out = '`&Du schnappst dir den kleinen Löffel von der Schale in der Ecke und fängst
								damit an wie wild im Boden herumzustochern. Nach ungefähr zwei Stunden hast du es geschafft 10 cm tief zu graben.
								Mit sehr unfreundlichen Gedanken gegenüber dem Kommandanten und seinen Männern rechnest du hoch,
								wie lange du wohl noch hier sitzen wirst um nach draußen zu gelangen, sollte es mit dieser
								Geschwindigkeit weiter gehen. Schließlich gibst du erschöpft auf.
								'.($Char->turns>0?'`nDas ganze hat dennoch ziemlich viel Zeit gekostet. `n`n`n
								`^Du verlierst einen Waldkampf!':'');
					
					$Char->turns--;
				
					$Char->specialinc = 'forest_kerker.php';
					addnav('Weiter', 'forest.php?op=injail&count='.$count);
					break;
					
				case 3: case 4: case 5:
					/** @noinspection PhpUndefinedVariableInspection */
                    $str_out = '`&Du schnappst dir den kleinen Löffel von der Schale in der Ecke und fängst
								damit an wie wild im Boden herumzustochern. Nach ungefähr zwei Stunden hast du es geschafft 10 cm tief zu graben.
								Mit sehr unfreundlichen Gedanken gegenüber dem Kommandanten und seinen Männern rechnest du hoch,
								wie lange du wohl noch hier sitzen wirst um nach draußen zu gelangen, sollte es mit dieser
								Geschwindigkeit weiter gehen. Als du nach einer weiteren Stunde schon Blasen an den Händen hast und sich immer
								noch nicht all zu viel an der Tiefe geändert hat, gibst du auf. `n
								Die Blasen an den Händen tun wirklich weh
								'.($Char->turns>0?' außerdem hat das Ganze ziemlich viel Zeit gekostet. `n`n`n
								`^Du verlierst einen Waldkampf!`n':'. `n`n`n').'
								`^Du verlierst `%10% `^deiner Lebenspunkte!';
								
					$Char->hitpoints -= round($Char->hitpoints * 0.1);			
					$Char->turns--;
						
					$Char->specialinc = 'forest_kerker.php';
					addnav('Weiter', 'forest.php?op=injail&count='.$count);
					break;
					
				case 6:
					$gems = mt_rand(2, 3);

                    /** @noinspection PhpUndefinedVariableInspection */
                    $str_out = '`&Du schnappst dir den kleinen Löffel von der Schale in der Ecke und fängst
								damit an wie wild im Boden herumzustochern. Nach ungefähr zwei Stunden hast du es geschafft 10 cm tief zu graben.
								Mit sehr unfreundlichen Gedanken gegenüber dem Kommandanten und seinen Männern rechnest du hoch,
								wie lange du wohl noch hier sitzen wirst um nach draußen zu gelangen, sollte es mit dieser
								Geschwindigkeit weiter gehen. Mit einem resignierenden Seufzer stößt du noch einmal mit dem Löffel
								auf den Erdboden. Ein lautes `iKRACKS`i ist zu hören und ein relativ großes Loch unter dem Boden wird frei. `n
								Du blinzelst ungläubig und springst dann hinein. Viel kannst du nicht erkennen, doch ganz am Ende des Ganges, 
								welcher sich dir nun zeigt, scheint ein Licht zu sein. Wie verrückt rennst du auf das Licht zu, doch bevor du es
								erreichst stolperst du und landest unsanft auf dem Boden. `n`n
								Glücklich bemerkst du, dass es wohl '.$gems.' Edelsteine waren, über die du gestolpert bist.`n
								Wie es aussieht hat schon einmal vor dir wer einen Gang gegraben und die Edelsteine hier verloren.
								Zufrieden steckst du sie ein und machst dich aus dem Staub, obwohl dir nach deinem Sturz echt alles weh tut!
								'.($Char->turns>0?'`nDas hat alles ziemlich viel Zeit gekostet!':'').' `n`n`n
								`^Du verlierst `%25% `^deiner Lebenspunkte! `n
								`^Du bekommst `%'.$gems.' `^Edelsteine!
								'.($Char->turns>0?'`n`^Du verlierst `%'.($Char->turns>=3?'3':$Char->turns).' `^Waldkämpfe!':'');
								
					$Char->gems += $gems;
					$Char->hitpoints -= round($Char->hitpoints * 0.25);
					$Char->turns = max(0,$Char->turns-3);
					
					$Char->specialinc = '';
					break;
			}
		}
		break;
		
	case 'cry':
		$count = $_GET['count'];
		$count++;
		
		if($count == 3) {
            /** @noinspection PhpUndefinedVariableInspection */
            $str_out .= '`&Du willst gerade mit schreien und jammern anfangen,
						 als deine Zellentüre auch schon weit aufgestoßen wurde. Vor dir steht der Kommandant, welcher dich vorher gefangen genommen 
						 hatte. Er verzieht kurz sein Gesicht, schaut auf dich herab und meint dann. `n
						 `$"Ok Bursche, die Prinzessin hat uns gesagt was passiert ist. Also raus hier." `n
						 `&Wieder wirst du unsanft gepackt und einige Augenblicke später stehst du vor der Burg, wo du anscheinend hinverfrachtet wurdest. `n
						 Dankbarkeit wird bei dir zwar anders definiert, aber du willst auch nicht wieder zurück in den Kerker, außerdem hast du aus der Sache wenigstens etwas gelernt. `n`n
						 '.($Char->turns>0?'Der Weg zum Wald ist allerdings weit und so wird es eine ganze Weile dauern, bis du unbeschadet zurück bist... `n`n`n
						 `^Du verlierst `%'.($Char->turns>=3?'3':$Char->turns).' `^Waldkämpfe!':'').'
						 `n`^Du bekommst `%5% `^Erfahrung!';
			
			$Char->experience += round($Char->experience * 0.05);
			$Char->turns = max(0,$Char->turns-3);
			
			$Char->specialinc = '';
		}
		else {
			switch(e_rand(1, 6)) {
				case 1: case 2:
					// LP-Verlust + WK-Verlust
					/** @noinspection PhpUndefinedVariableInspection */
                    $str_out = '`&Du rüttelst an den Stäben deiner Zellentüre und schreist aus vollem Hals, dass du hier raus willst,
								und sowieso zu unrecht eingesperrt wurdest. Eine ganze Weile rufst du schon und nichts tut sich, bis schließlich 
								ein lautes Stampfen aus einem Ende des Kerkerganges zu hören ist. Ein wenig später baut sich ein riesiger Troll vor 
								deiner Zellentüre auf. `n
								`2"Warum gefangene Menschlein immer so laaauuut??"`&, gröhlt er dir entgegen und verteilt dabei mächtig Spucke über dein
								Gesicht. `2"Grombargh will RUUHEE!!" `&Und damit du auch wirklich ruhig bist, klopft dir der Troll gleich einmal mit der flachen Seite 
								seiner Hellebarde kräftig auf die Finger, mit denen du noch immer das Gitter festhälst. `n
								So etwas tut weh'.($Char->turns>0?' und die Aktion hat Zeit gekostet. `n`n`n
								`^Du verlierst `%1 `^Waldkampf! `n':'. `n`n`n').'
								`^Du verlierst `%10% `^deiner Lebenspunkte! `n';
					
					$Char->hitpoints -= round($Char->hitpoints * 0.1);
					if($Char->turns > 0)
					{
						$Char->turns--;
					}
					break;
				
				case 3: case 4: case 5:
					// WK-Verlust
					/** @noinspection PhpUndefinedVariableInspection */
                    $str_out = '`&Du rüttelst an den Stäben deiner Zellentüre und schreist aus vollem Hals, dass du hier raus willst,
								und sowieso zu unrecht eingesperrt wurdest. Eine ganze Weile rufst du schon und nichts tut sich, bis schließlich 
								ein lautes Stampfen aus einem Ende des Kerkerganges zu hören ist. Ein wenig später baut sich ein riesiger Troll vor 
								deiner Zellentüre auf. `n
								`2"Warum gefangene Menschlein immer so laaauuut??"`&, gröhlt er dir entgegen und verteilt dabei mächtig Spucke über dein
								Gesicht. `2"Grombargh will RUUHEE!!" `&Du widersprichst dem Troll lieber nicht und ziehst dich erst einmal etwas ruhiger 
								ins Zelleninnere zurück. `n`n
								'.($Char->turns>0?'Die Aktion hat doch Zeit gekostet. `n`n`n
								`^Du verlierst einen Waldkampf':'');
					
					if($Char->turns > 0)
					{
						$Char->turns--;
					}
					break;
				
				case 6:
					// Edelstein-Gewinn + LP-Verlust + WK-Verlust
					/** @noinspection PhpUndefinedVariableInspection */
                    $str_out = '`&Du rüttelst an den Stäben deiner Zellentüre und schreist aus vollem Hals, dass du hier raus willst,
								und sowieso zu Unrecht eingesperrt wurdest. Eine ganze Weile rufst du schon und nichts tut sich, bis schließlich 
								ein lautes Stampfen aus einem Ende des Kerkerganges zu hören ist. Ein wenig später baut sich ein riesiger Troll vor 
								deiner Zellentüre auf. `n
								`2"Warum gefangene Menschlein immer so laaauuut??"`&, gröhlt er dir entgegen und verteilt dabei mächtig Spucke über dein
								Gesicht. `2"Grombargh will RUUHEE!!" `&Und damit du auch wirklich ruhig bist, wirft dir der Troll etwas verdammt Hartes an 
								den Kopf. Du gehst erst einmal K.O., doch als du wieder aufwachst bemerkst du, dass das Harte ein Edelstein war! Leider tut 
								so ein Edelstein auch ziemlich weh'.($Char->turns>0?' und die Aktion hat Zeit gekostet. `n`n`n
								`^Du verlierst `%1 `^Waldkampf! `n':'. `n`n`n').'
								`^Du verlierst `%25% `^deiner Lebenspunkte! `n
								`^Du bekommst `%1 `^Edelstein!';
							
					
					if($Char->turns > 0)
					{
						$Char->turns--;
					}
					$Char->gems++;
					$Char->hitpoints -= round($Char->hitpoints * 0.25);
 					
					break;
			}
			
			$Char->specialinc = 'forest_kerker.php';
			addnav('Weiter', 'forest.php?op=injail&count='.$count);
		}
		break;
						   
	/**
	 * Specialanfang
	 */
	default:
		/** @noinspection PhpUndefinedVariableInspection */
        $str_out = '`&Gemütlich schlenderst du durch den Wald, als plötzlich Hilfeschreie zu hören sind.
				   Als großartige'.($Char->sex?' Heldin':'r Held').' überlegst du dir natürlich, 
				   ob du der Sache nachgehst... `n`n
				   <a href="forest.php?op=help">Der Person natürlich helfen!</a> `n`n
				   <a href="forest.php?op=leave">Hilfeschreie sind dir definitiv zu heiß! Lieber gehen....</a> `n`n';
		addnav('', 'forest.php?op=help');
		addnav('', 'forest.php?op=leave');
		
		addnav('Aktionen');
		addnav('Helfen', 'forest.php?op=help');
		addnav('Zurückziehen', 'forest.php?op=leave');
		
		$Char->specialinc = 'forest_kerker.php';
}

output(words_by_sex($str_out));
?>