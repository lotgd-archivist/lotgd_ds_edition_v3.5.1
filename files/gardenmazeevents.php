<?php
/*
@file gardenmazeevents.php
@desc Specials für gardenmaze.php (specialguest: jigsaw)
@author Sven-Michael "Alucard" Stübe
*/
require_once 'common.php';
page_header("Der Irrgarten des verlassenen Schlosses");
$pos = $_GET['pos'];
$specialid = $_GET['specialid'];
$out = "";
switch($specialid){

	case "jigsaw"://ja das muss sein :>
		switch($_GET['op']){
			case "help":
				if( $_GET['try'] == 1 )
				{
					$out = "Du schreist nocheinmal um Hilfe. Ob das was bringt?`n";
				}
				else{
					$out = "Du schreist eine Stunde um Hilfe.`n";
				}
				$res = db_query("SELECT acctid,name,weapon,sex,gold FROM accounts WHERE acctid<>".$session['user']['acctid']." AND loggedin<>0 AND maze_visited<>'' ORDER BY RAND() LIMIT 1");
				$gethelp = (db_num_rows($res) == 1)? (e_rand(1,5)==3 ? 1 : 0) : 0;
				if( $gethelp ){
					$hero = db_fetch_assoc($res);
					$out .= "Plötzlich merkst du, wie sich ".$hero['name']."`@ mit ".($hero['sex'] ? "ihrem" : "seinem")." ".$hero['weapon']."`@ zu dir hindurch kämpft und dich befreit.`n";
					if( $session['user']['gold'] > 0 ){
						$out .= "Als Dank für seine Tapferkeit gibst du ".$hero['name']."`@ dein ganzes Gold.";
						$out .= ($hero['sex'] ? "Sie" : "Er")." lächelt und macht sich davon.`n";
						$msg = "`&Du hast ".$session['user']['name']."`& im Labyrinth aus einer Falle gerettet. Du bekommst `^".$session['user']['gold']."`& Goldstücke als Dankeschön von ".($session['user']['sex'] ? "ihr." : "ihm.");
						systemmail($hero['acctid'],"Dankeschön",$msg);
						
						user_update(
							array
							(
								'gold'=>array('sql'=>true,'value'=>($hero['gold']+$session['user']['gold']))
							),
							$hero['acctid']
						);
						
						$session['user']['gold'] = 0;
					}
					else{
						$what = $hero['sex'] ? "einen wunderschönen Prinzen" : "eine wunderschöne Prinzessin";
						$out .= $hero['name']." `@hat gedacht, dass ".($hero['sex'] ? "sie" : "er")." ".$what." vorfinden würde. Stattdessen findet ".($hero['sex'] ? "sie" : "er")." nur dich vor, schaut enntäuscht und macht sich vom Acker.";
					}
					$out .= "Du freust dich über deine Rettung und findest deine Waffe und deine Rüstung wieder. Hoffentlich hat das bald ein Ende!";
					addnews($hero['name']."`& hat ".$session['user']['name']."`& aus einer höllischen Falle im Labyrinth gerettet");
					addnav("Was für ein Glück");
					addnav("Weiter", "gardenmaze.php?pos=".$pos);
				}
				elseif( $_GET['try'] == 1 ){
					$out .= "So sehr du auch schreien magst, keiner hört dich! Du siehst den einzigen Ausweg darin, dich in die Büsche zu stürzen. Jedoch bist du schon etwas geschwächt! Wie willst du es versuchen?";
					$session['user']['hitpoints'] *= 0.75;
					addnav("Wie?");
					addnav("Vorsichtig", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=try&strength=1&help=2");
					addnav("Energisch", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=try&strength=3&help=2");
				}
				else{
					$out .= "So laut du auch schreist; Es kommt niemand!`n";
					if( db_num_rows($res) < 1 ){ //Wenn keiner da ist, gibt es sogar nen Tip (nur wer aufmerksam ist weiss, was er zu tun hat) :D
						$out .= "Die mysteriöse Stimme spricht erneut zu dir: \"`i`&Es wird Dich keiner hören!`i`@\"`n";
					}
					$out .= "Was tust du nun?`n";
					$out .= "`4- Trotz Erschöpfung weiter um Hilfe rufen`n";
					$out .= "- versuchen, dich vorsichtig durch die Büsche zu schlängeln`n";
					$out .= "- energisch in die Büsche stürzen und sie wie wild auseinander reißen";
					addnav("Was nun?");
					addnav("Hilfe!!!! Hilfe!!!!", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=help&try=1");
					addnav("Vorsichtig", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=try&strength=1&help=1");
					addnav("Energisch", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=try&strength=3&help=1");
				}
			break;

			case "try":
				$free 	= 0;
				$chance = $_GET['try']+$_GET['strength'];
				$help   = $_GET['help'];
				$hplost = e_rand(10, $_GET['strength']*25)*0.01*$session['user']['maxhitpoints'];

				$out = "Du versuchst dich ".($_GET['strength']==1? "vorsichtig" : "energisch")." durch die Dornenbüsche zu kämpfen.`n";
				for($i=0; $i<$chance && !$free; $i++){
					$free = (e_rand(0,9+$help)== 2 ? 1 : 0);
				}

				if( $free ){
					$gems = e_rand(3,6);
					$gold = e_rand(3,8)*500;
					$out = "Als du dein Schicksal akzeptieren willst, bemerkst du dein ".$session['user']['weapon']."`@ und dein ".$session['user']['armor']."`@ vor deinen Füßen!`n";
					$out .= "Du ziehst deine Rüstung an und nimmst deine Waffe an dich, als du `^".$gold."`@ Goldsücke und `#".$gems."`@ Edelsteine vor dir erblickst! Du steckst deinen Lohn für dieses makabere Spielchen ein und gehst so schnell wie möglich weiter.";
					$session['user']['gems'] += $gems;
					$session['user']['gold'] += $gold;
					if( $hplost > $session['user']['hitpoints'] ){
						$session['user']['hitpoints'] = 1;
					}
					else{
						$session['user']['hitpoints'] -= $hplost;
					}
					addnav("Schnell weg!", "gardenmaze.php?pos=".$pos);
				}
				elseif( $hplost < $session['user']['hitpoints'] ){
					$out .= "Du kommst zwar ein Stückchen weiter, bist aber noch nicht draußen!";
					$session['user']['hitpoints'] -= $hplost;
					addnav("Was nun?");
					addnav("Vorsichtig", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=try&strength=1&help=".$help."&try=".($_GET['try']+1));
					addnav("Energisch", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=try&strength=3&help=".$help."&try=".($_GET['try']+1));
				}
				else{//sterben :>
					$out .= "Du hast soviel Blut verloren, dass dein Körper letztendlich leblos im Gestrüpp in sich zusammenfällt.`n";
					if(!$help){//wer nach hilfe geschrieen hat ist ein Feigling!
						$out .= "Jedoch wird dich Ramius für deinen Mut belohnen.";
						$session['user']['gravefights'] += 5;
					}
					killplayer(0,0,0,'shades.php','Mist!');
				}
			break;

			default:
				$out  = "Du läufst ahnungslos durch das Labyrinth, als du einen Stich in deinem Nacken merkst.`n";
				$out .= "Du fasst dir an den Nacken, spürst einen Dornen und ziehst ihn raus. Als du ihn betrachten willst, wird dir schwarz vor Augen und du kippst um.`n";
				$out .= "Nach einiger zeit wachst du an einem Ort auf, der von meterhohen Dornenbüschen umgeben ist. Du merkst, dass du dein ".$session['user']['weapon']."`@ und dein ".$session['user']['armor']."`@ nicht mehr bei dir hast.`n`n";
				$out .= "Plötzlich spricht eine Stimme zu dir:`n";
				$out .= "`&\"`iHallo ".$session['user']['name']."`&! Ich möchte ein Spiel spielen!`n Zeig: Wieviel Blut willst du vergießen um zu überleben?`i\"`n";
				$out .= "`@Die Stimme vehallt nach einem lauten Lachen. Nun bist du auf dich allein gestellt. Was wirst du tun?`n";
				$out .= "`4- Hilfe rufen`n";
				$out .= "- versuchen dich vorsichtig durch die Büsche zu schlängeln`n";
				$out .= "- energisch in die Büsche stürzen und sie wie wild auseinander reißen";
				addnav("Was nun?");
				addnav("Hilfe!!!! Hilfe!!!!", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=help");
				addnav("Vorsichtig", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=try&strength=1");
				addnav("Energisch", "gardenmazeevents.php?specialid=jigsaw&pos=".$pos."&op=try&strength=3");
			break;
		}

	break;

	case "blingbling":
		switch($_GET['op']){

			case "leave":
				$session['maze_output'] = "Du gehst einfach weiter.";
				redirect("gardenmaze.php?pos=".$pos);
			break;

			case "watch":
				$out .= "Du schaust etwas genauer hin und findest ";
				switch(e_rand(1,6)){
					case 1:
					case 2:
						$out .= "einen Edelstein und steckst ihn ein.";
						$session['user']['gems']++;
						addnav("Juchee!", "gardenmaze.php?pos=".$pos);
					break;
					case 3:
					case 4:
						$out .= "eine Heckenschere!`n";
						$out .= "Was willst du mit ihr anstellen?`n";
						$out .= "`4- testen, ob sie scharf ist`n";
						$out .= "- den Weg freischneiden.`n";
						$out .= "- sie wegwerfen und weiter gehen.";
						addnav("Testen", "gardenmazeevents.php?specialid=blingbling&pos=".$pos."&op=test");
						addnav("Freischneiden", "gardenmazeevents.php?specialid=blingbling&pos=".$pos."&op=cut&tested=0");
						addnav("Weg damit", "gardenmazeevents.php?specialid=blingbling&pos=".$pos."&op=trash");
					break;

					default:
						$out .= "ein billiges Stück Blech.`n";
						$out .= "Enttäuscht schmeißt du es wieder ins Gebüsch und gehst deines Weges.";
						addnav("Weiter", "gardenmaze.php?pos=".$pos);
					break;
				}
			break;

			case "test":
				$out = "Du fährst mit mit deinem Daumen quer über die Klinge und merkst, dass ";
				switch(e_rand(1,9)){
					case 1:
					case 4:
					case 7:
						$out .= "die Schere stumpf ist, lässt sie fallen und gehst weiter.";
						addnav("Weiter", "gardenmaze.php?pos=".$pos);
					break;

					case 2:
					case 5:
					case 8:
						$out .= "Du einen Finger weniger hast.`nEtwas Blut sprudelt aus dem Stumpf.`n";
						$out .= "`^Du verlierst einige Lebenspunkte!`n";
						$out .= "`@Aber nun weißt du, dass die Heckenschere scharf ist.`nWas machst du jetzt?`n";
						$out .= "`4- Ich hab mich genug verletzt. Ich lass es bleiben!`n";
						$out .= "- Ich will hier raus und werde mir den Weg freischneiden! Das sind doch nur Büsche!`n";
						$session['user']['hitpoints']-=20;
						if ($session['user']['hitpoints']<1){
							$session['user']['hitpoints']=1;
						}
						addnav("Weg damit", "gardenmazeevents.php?specialid=blingbling&pos=".$pos."&op=trash");
						addnav("Freischneiden", "gardenmazeevents.php?specialid=blingbling&pos=".$pos."&op=cut&tested=1");
					break;

					case 3:
					case 6:
					case 9:
						$out .= "dir die Hornhaut vom Daumen geschabt wird. Das Ding ist scharf!`nWas machst du jetzt?`n";
						$out .= "`4- Ich hab Angst mich zu verletzen. Ich lass es bleiben!`n";
						$out .= "- Das Ding schneidet meine Hornhaut, also werden die Büsche ein Klaxx.`n";
						addnav("Weg damit", "gardenmazeevents.php?specialid=blingbling&pos=".$pos."&op=trash");
						addnav("Freischneiden", "gardenmazeevents.php?specialid=blingbling&pos=".$pos."&op=cut&tested=1");
					break;
				}
			break;


			case "cut":
				$cancut = $_GET['tested'] == 1 ? 1 : e_rand(1,24)%2;
				$out .= "Du gehst wie wild auf die Sträucher los.`n";
				if($cancut){
					switch(e_rand(1,7)){
						case 1:
						case 2:
						case 3:
							$out .= "Du verfällst in einen wahren Schnibbelwahn!`n";
							$out .= "Als du dich umschaust bemerkst du, dass du am Ende dieses Labyrinthes bist.";
							$session['maze_output'] = $out;
							redirect("gardenmaze.php?pos=143");
						break;

						case 4:
							$out .= "Du verfällst einen wahren Schnibbelwahn und rutschst ab!`n";
							$out .= "Dein Kopf rollt einen Meter und dein restlicher Körper sackt in sich zusammen.`n";
							$out .= "`^Du bist tot!`n";
							killplayer(100, 0.5, 0, 'shades.php', 'Verdammt!');
						break;

						case 5:
						case 6:
						case 7:
							$gems = e_rand(1,3);
							$out .= "Du verfällst einen wahren Schnibbelwahn!`n";
							$out .= "Als du dich umschaust bemerkst du, dass du wieder am Anfang dieses Labyrinthes bist.`n";
							$out .= "Jedoch hast du auf deinem Weg durch die Büsche `#".$gems." `@Edelsteine gefunden.";
							$session['maze_output'] = $out;
							$session['user']['gems'] += $gems;
							redirect("gardenmaze.php?pos=0");
						break;
					}
				}
				else{
					$out .= "Nach einer Weile merkst du, dass du lediglich die Rinde verbeulst und du schmerzende Blasen an den Händen hast, weil die Heckenschere stumpf ist.`n";
					$out .= "`^Du verlierst ein paar Lebenspunkte!`n";
					$session['user']['hitpoints']-=20;
					if ($session['user']['hitpoints']<1){
						$session['user']['hitpoints']=1;
					}
					addnav("Weiter", "gardenmaze.php?pos=".$pos);
				}
			breaK;


			case "trash":
				$out .= "Da du kein Gärtner bist, schmeißt du die Heckenschere über die Büsche und hörst ein \"`i`&AUUUUUA! Du Ar...loch!`i\"`@ und gehst schnell weiter.`n";
				addnav("Weiter", "gardenmaze.php?pos=".$pos);
			breaK;

			default:
				$out  = "Als du zufällig in die Büsche schaust, siehst du etwas funkeln.`n";
				$out .= "Was wirst du tun?";
				addnav("Was nun?");
				addnav("Nachschauen", "gardenmazeevents.php?specialid=blingbling&pos=".$pos."&op=watch");
				addnav("Weitergehen", "gardenmazeevents.php?specialid=blingbling&pos=".$pos."&op=leave");


			break;
		}

	break;



	case "well":

		switch($_GET['op']){

			case "trink":
				$out  = "Du nimmst einen Schluck Wasser zu dir und ";
				switch(e_rand(1,10)){
					case 1:
					case 2:
					case 3:
					case 4:
						$out .= "fühlst dich gestärkt.";
						$session['user']['hitpoints'] = round(max($session['user']['maxhitpoints'],$session['user']['hitpoints'])*(1+$session['user']['level']*0.01),0);
						addnav("Weiter", "gardenmaze.php?pos=".$pos);
					break;
					case 5:
					case 6:
					case 7:
						$out .= "fühlst dich erfrischt.";
						$session['user']['hitpoints'] =max($session['user']['maxhitpoints'],$session['user']['hitpoints']);
						addnav("Weiter", "gardenmaze.php?pos=".$pos);
					break;
					case 8:
					case 9:
						$out .= "bekommst einen ungeheuren Powerschub!`n";
						$out .= "`^Deine max. Lebenspunkte erhöhen sich `bpermanent um 3`b!";
						$session['user']['hitpoints'] = round(max($session['user']['maxhitpoints'],$session['user']['hitpoints'])*(1+$session['user']['level']*0.02),0);
						$session['user']['maxhitpoints']+=3;
						addnav("Juhu", "gardenmaze.php?pos=".$pos);
					break;
					case 10:
						$out .= "fällst auf der Stelle tot um!`n";
						$out .= "`4Deine max. Lebenspunkte verringern sich `bpermanent um 1`b!`n";
						$out .= "`@Außerdem hast du das Gefühl, etwas vergessen zu haben.";
						$session['user']['maxhitpoints']--;
						killplayer(0,1,0,'shades.php','Mist!');
					break;
				}
			break;


			default:
				$out  = "Du hörst Wasser plätschern und entdeckst einen Brunnen, als zu dem Geräusch nachgehst.`n";
				$out .= "Was wirst du tun?";
				addnav("Was nun?");
				addnav("Erfrischen", "gardenmazeevents.php?specialid=well&pos=".$pos."&op=trink");
				addnav("Weitergehen", "gardenmaze.php?pos=".$pos);

			break;

		}

	break;



	case "appletree":

		switch($_GET['op']){

			case "apple":
				$out  = "Du nimmst einen Apfel vom Baum und beißt hinein.`n";
				switch(e_rand(1,12)){
					case 1:
					case 2:
					case 3:
					case 4:
						$out .= "Dieser Apfel schmeckt sauer.";
						addnav("Igitt!", "gardenmaze.php?pos=".$pos);
					break;
					case 5:
					case 6:
						$out .= "Dieser Apfel schmeckt köstlich!`n`^Du fühlst dich gestärkt.";
						$session['user']['hitpoints'] =max($session['user']['maxhitpoints'],$session['user']['hitpoints'])+50;
						addnav("Weiter", "gardenmaze.php?pos=".$pos);
					break;
					case 7:
					case 8:

						$out .= "Dieser Apfel birgt magische Kräfte!`n`^Du fühlst dich ";

						switch(e_rand(1,5)){
							case 1:
								$out .= "stärker!`n";
								$out .= "`^Dein Angriff steigt um 2 Punkte!";
								$session['user']['attack']+=2;
							break;

							case 2:
								$out .= "lebendiger!`n";
								$out .= "`^Deine max. Lebenspunkte erhöhen sich permanent um 1!";
								$session['user']['maxhitpoints']++;
							break;

							case 3:
								$out .= "geschützter!`n";
								$out .= "`^Deine Verteidigung steigt um 2 Punkte!";
								$session['user']['defence']++;
							break;

							case 4:
								$out .= "erfahrener!`n";
								$out .= "`^Deine Erfahrung steigt!";
								$session['user']['experience'] *= 1+$session['user']['level']*0.01;
							break;

							case 5:
								$out .= "reicher!`n";
								$out .= "`^Du bemerkst, dass du 500 Golstücke mehr mit dir herumschleppst!";
								$session['user']['gold'] += 500;
							break;
						}
						addnav("Juhu", "gardenmaze.php?pos=".$pos);
					break;
					case 9:
					case 10:
						$out .= "Dieser Apfel war vergiftet.`n";
						$out .= "Du fällst auf der Stelle tot um!`n";
						$out .= "`4Deine max. Lebenspunkte verringern sich `bpermanent um 1`b!`n";
						$out .= "`@Außerdem hast du das Gefühl etwas vergessen zu haben.";
						$session['user']['maxhitpoints']--;
						killplayer(0,1,0,'shades.php','Mist!');
					break;
					case 11:
					case 12:
						$out .= "Aua! Dieser Apfel birgt einen Edelstein in sich.`n";
						$out .= "Du verlierst ein paar Lebenspunkte, vergist den Schmerz aber wieder, als du den Edelstein in deine Tasche stopfst!`n";
						$session['user']['hitpoints']-=10;
						if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
						$session['user']['gems']++;
						addnav("Gut!", "gardenmaze.php?pos=".$pos);
					break;
				}
			break;

			case "slay":
				$out = "Du ziehst dein ".$session['user']['weapon']."`@ und schlägst der Schlange den Kopf ab.`n";
				switch(e_rand(1,4)){
					case 1:
					case 2:
					case 3:
						$out .= "Als du genauer hinschaust, bemerkst du, dass die Augen der Schlange Edelsteine sind. Schnell schneidest du Sie raus und steckst sie zu deinen anderen Gemmen.";
						$session['user']['gems']+=2;
						addnav("Jippie!", "gardenmaze.php?pos=".$pos);
					break;

					case 4:
						$out .= "Plötzlich wird es dunkel und Wadjet, die ägyptische Schlangengöttin, spricht zu dir:`n";
						$out .= "`&\"`iDu hast es gewagt, eine unschuldige Kreatur zu töten. Nun wirst Du meinen Zorn spüren!`i\"";
						$session['user']['gems']+=2;
						addnav("Kämpfe!", "mazemonster.php?op=snakegod");
					break;

				}

			break;


			default:
				$out  = "Du siehst einen Baum, der herrlich rot leuchtende Äpfel trägt.`n";
				$out .= "Als du an den Baum herantrittst siehst du eine Schlange. Diese spricht zu dir:`n";
				$out .= "`&\"`iHallo ".$session['user']['name']."`&! Siehst Du diese wundervollen Früchte? Nimm ruhig einen! Sie schmecken wirklich wunderbar.`i\"`n";
				$out .= "`@Was wirst du tun?";
				addnav("Was nun?");
				addnav("Apfel nehmen.", "gardenmazeevents.php?specialid=appletree&pos=".$pos."&op=apple");
				addnav("Schlange töten", "gardenmazeevents.php?specialid=appletree&pos=".$pos."&op=slay");
				addnav("Weitergehen", "gardenmaze.php?pos=".$pos);
			break;

		}

	break;
}

if( !empty($out) ){
	output("`n`@".$out."`0");
}
if($access_control->su_check(access_control::SU_RIGHT_CASTLECHOOSE)){
	addnav("Besonderes");
	addnav("Zum Garten", "gardenmaze.php?&pos=".$pos);
	addnav("Zur Stadt", "gardenmaze.php?superuser=leave");
}
page_footer();
?>