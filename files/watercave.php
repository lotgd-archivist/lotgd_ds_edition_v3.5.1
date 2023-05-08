<?php

/*
Unterwasserhöhle
© Fingolfin
29.04.2007

--SQL--
ALTER TABLE `account_extra_info` ADD `cave_xtal` TINYINT( 1 ) UNSIGNED NOT null DEFAULT '0',
ADD `cave_chest` TINYINT( 1 ) UNSIGNED NOT null DEFAULT '0',
ADD `cave_remind` TINYINT( 1 ) UNSIGNED NOT null DEFAULT '0';


//In die gewünschte Datei einfügen die mit der Unterwasserhöhle verbunden werden soll
if($session['bufflist']['`FLanger Atem'])
{
	addnav('Tauchen','watercave.php');   
}
else if(access_control::is_superuser())
{
	addnav('Tauchen(SU)','watercave.php?user=super');   
}

//in die fish.php:
function fishprank()
{
global $session;
global $minnows;
global $worms;
global $fishturn;
	
$str_output = '`0...und wartest geduldig darauf, dass ein Fisch an die Leine geht, doch nichts geschieht. Nach einer geraumen Zeit ziehst du enttäuscht deine Angel wieder ein, doch was ist das???`n`n
An deiner Angel hängt kein Köder mehr sondern ein kleiner Zettel auf dem folgendes steht:`n
`vHeute wars wohl nichts mit Angeln ;)`n
`0Verwundert entfernst du den Zettel und probierst es mit einem neuen Köder, doch erneut tut sich nichts - alle Fische scheinen verschwunden zu sein.`n`n
`tDu gibst auf und lässt es für heute sein.';
output($str_output);
	
savesetting('fishprank',0);
	
$Char->turns -= 1;
if($Char->turns<0)
{
	$Char->turns = 0;
}      
$fishturn = 0;
user_set_aei(array('fishturn'=>0,'minnows'=>$minnows,'worms'=>$worms),$Char->acctid);
}
*/


//-------------------------------------
$str_filename = basename(__FILE__);
$str_backtext = 'Zum Ufer';
$str_backlink = 'pool.php';
//-------------------------------------

require_once 'common.php';
checkday();
page_header('');

$str_output = '';

switch($_GET['op'])
{

	case '':
	{ //Start
		page_header('Am Ufer');
		
		$str_output .= '`c`b`wD`Ba`Ws Uf`Be`wr`0`b`c`n
		`wAl`Bs d`Wu dich dem Waldsee näherst, musst du an den komischen Kauz denken, den du heute im Wald getroffen hast. Du betrachtest das blaue Leuchten in der Mitte des schwarzen Sees genauer. Wäre es nicht interessant, herrauszufinden was dort unten auf dem Seegrund sein könnte? Langsam watest du in das dunkle Wasser und nach kurzem kannst du deine Füße schon nicht mehr richtig erkennen. Erneut rufst du dir die Anweisungen ins Gedächtnis wie du deinen Atem am längsten anhälst. Während du dort stehst kommen dir auch Gedanken in den Kopf, was dir alles dort unten passieren kön`Bnt`we.`n`n
		`wWirst du versuchen hinabzutauchen? Es wird dich einige Zeit kosten und es gibt kein Zurück!';
		output($str_output);
		
		if($_GET['user'] == 'super')
		{
			addnav('Ab ins Wasser!',$str_filename.'?op=dive&user=super');   
		}
		else
		{
			addnav('Ab ins Wasser!',$str_filename.'?op=dive');   
		}
		addnav('Zurück');
		addnav($str_backtext,$str_backlink);
	}
	break;

	case 'dive':
	{ //Tauchen von/zur Höhle
		switch($_GET['dir'])
		{
			case '':
			{
				if($Char->turns>3)
				{
					page_header('Unterwasser');
					
					if ($Char->hashorse>0)
					{
						$str_output .= '`wE`Bi`Wn letztes mal schaust du zurück zu deinem/deiner '.$playermount['mountname'].'`W, der dir leider nicht folgen kann und hoffst ihn/sie so bald wie möglich wieder bei dir zu hab`Be`wn.`n`n';
						
						buff_remove('mount');
					}
					$str_output .= '`wD`Bu`W watest immer weiter in das dunkle Wasser, bis du irgendwann nicht mehr stehen kannst. Ein wenig aufgeregt schwimmst du weiter in den See, bis du über dem blauen Licht ankommst. Du holst so tief Luft wie du nur kannst und tauchst ab in das Wasser. Das Wasser wird deiner Erwartung entgegen wärmer, je näher du dem Licht kommst. Plötzlich bemerkst du einen Schatten, der zwischen dir und dem Licht vorbeihuscht. Hektisch versuchst du schneller voranzukommen, um das Licht zu erreich`Be`wn...`n`n
					`wDu verlierst einen Waldkampf.';
					output($str_output);
					
					$Char->turns --;
					
					if($_GET['user'] == 'super')
					{
						$atembuff = array('name'=>'`FLanger Atem','rounds'=>35,'wearoff'=>'`FDu bist aus der Puste!`0','atkmod'=>1.1,'roundmsg'=>'`FDu bekommst mehr Luft und schlägst härter zu!`0','activate'=>'offense');
						buff_add($atembuff);
					}
					
					addnav('Weiter',$str_filename.'?op=dive&dir=down');
				}
				else
				{
					page_header('Am Ufer');
					
					$str_output .= '`wD`Bu `Wgehst ein paar Schritte ins Wasser, doch nach einem Moment merkst du, dass du heute nicht mehr genug Kraft hast, dort hinabzutauchen und wieder hinaufzukomm`Be`wn.`n`n
					`wDu solltest wieder vorbeikommen, wenn du mehr Zeit hast.';
					output($str_output);
					
					addnav('Zurück');
					addnav($str_backtext,$str_backlink);   
				}
				break;
			}
			
			case 'down':
			{
				page_header('Unterwasser');
				
				switch(e_rand(1,7))
				{
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					{
						$str_output .= '`wD`Bu `wschwimmst auf das Licht zu und merkst, wie dir langsam die Luft ausgeht. Du hörst nichts, außer deine unregelmäßigen Bewegungen und ein leises Summen, was von dem Licht zu kommen scheint. Das Wasser ist inzwischen fast zu heiß um darin zu schwimmen, doch du kämpfst dich ununterbrochen voran.`n`n
						Als du schon fast die Hoffnung aufgegeben hast, etwas zu finden, entdeckst du eine Art Tunnel, aus dem das Licht zu kommen scheint. Ermutigt schwimmst du weiter in den Tunnel, der nach einer kurzen Strecke aufwärts geht. Das Licht ist jetzt nicht mehr so intensiv und plötzlich stößt du aus dem Wasser. Reflexartig holst du tief Luft. Vor dir eröffnet sich eine Höhle und du kletterst aus dem Wasser hera`Bu`ws.';
						output($str_output);
						
						user_set_aei(array('cave_xtal'=>0,'cave_chest'=>0,'cave_remind'=>0),$Char->acctid);
						
						$session['bufflist']['`FLanger Atem']['rounds'] -= 5;
						if($session['bufflist']['`FLanger Atem']['rounds']<1)
						{
							buff_remove('`FLanger Atem');
						}
						
						
						addnav('In die Höhle',$str_filename.'?op=cave');
						break;
					}
					
					case 6:
					case 7:
					{
						$str_output .= '`wD`Bu `Wschwimmst auf das Licht zu und erneut huscht ein Schatten vor dir vorbei. Deine Bewegungen werden immer hektischer und in deiner Panik verlierst du die Orientierung. Du merkst wie dir die Luft ausgeht, aber du kommst dem Licht einfach nicht näher. Inzwischen weißt du auch nicht mehr wo oben und unten ist und das warme Wasser verwirrt dich zusätzlich noch mehr.`n
						Du drehst dich gerade um dich selber als plötzlich aus dem Nichts ein schwarzer Schatten auf dich zugerast kommt. Das ist das letzte was du erleb`Bs`wt.`n`n
						`wDu bist tot und hast ein wenig Erfahrung und all dein Gold verloren.';
						output($str_output);
						
						$Char->kill(100,1);
						addnews($Char->name.' `(wurde im Waldsee von einem `$Ungeheuer `(gefressen.');   
						
						break;
					}
				}
				break;
			}
				
			case 'up':
			{	
				page_header('Unterwasser');
				
				$str_output .= '`wD`Bu `Wwillst nicht mehr länger hier unten verweilen und gehst zu dem Loch in der Höhle, das in den See führt. Du holst wieder so tief Luft wie du nur kannst, in der Hoffnung, dass du schneller an die Oberfläche kommst als du hier hinunter gebraucht hast.`n`n
				Auf dem Weg nach oben wirst du immer schneller und dein Herz beginnt zu rasen, als du glaubst, den Schatten erneut gesehen zu haben. Doch bevor du dich danach umschaun kannst brichst du schon aus der Wasseroberfläche.`n
				Schnell schwimmst du an Land und bist sehr erleichtert, dass du deinen kleinen Ausflug überlebt hast. Als du in den Himmel schaust erkennst du das einige Zeit vergangen sein muss seit du hinabgetaucht bi`Bs`wt.`n`n`w';
				if ($Char->hashorse>0)
				{
					$str_output .= '`wDein '.$playermount['mountname'].'`w erwartet dich schon freudig am Ufer und gemeinsam macht ihr euch auf den Weg.';   
				}
				else
				{
					$str_output .= '`wDu machst dich wieder auf den Weg in ein neues Abenteuer.';   
				}
				output($str_output);
				
				$Char->turns=max(0,$Char->turns-3);
				buff_remove('`FLanger Atem');
				
				addnav($str_backtext,$str_backlink);
				break;
			}
				
			case 'late':
			{
				switch(e_rand(1,6))
				{
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					{
						if($Char->race=='mwn')
						{
							$str_output .= '`wD`Bu `Wentschließt dich nun doch, aufzutauchen und blickst dich ein letztes Mal in der Höhle um, bevor du dich wieder ins Wasser begibst. Zügig und mit kräftigen Schlägen schwimmst du aus der Höhle und mitten in den See hinein. Du siehst bereits weit über dir die ein Schimmern, das wohl von der Oberfläche kommen muss, doch auf einmal schiebt sich ein riesiger, schwarzer Schatten zwischen dich und das helle Schimmern des Tageslichts, welches du nun nie mehr erreichen wirst. Das Seeungeheuer öffnet seinen gewaltigen Rachen, und das ist das letzte, was du sieh`Bs`wt...`n`n
							`wDu bist tot!.';
							output($str_output);

							$Char->kill(100,0);
							addnews($Char->name.' `(wurde vom Seeungeheuer gefressen.');
						}

						else
						{
							$str_output .= '`wD`Bu `Wentschließt dich nun doch, aufzutauchen und holst noch ein letztes Mal sehr tief Luft, bevor du in das Wasser springst. Zügig und mit kräftigen Schlägen schwimmst du aus der Höhle und mitten in den See hinein. Du siehst bereits weit über dir die ein Schimmern, das wohl von der Oberfläche kommen muss, doch deine Kraft lässt mit einem Mal nach und du würdest am liebsten Luft holen, doch noch bist du unter Wasser.`n`n
							Du bewegst dich immer hektischer und kommst fast nicht mehr voran. Langsam merkst du, wie dein Bewusstsein schwindet und sich deine Lungen mit Wasser füllen - und du stirb`Bs`wt.';
							output($str_output);

							$Char->kill(100,0);
							addnews($Char->name.' `(hat sich beim Tauchen überschätzt.');
							
						}
						break;
					}
					
					case 6:
					{
						$str_output .= '`wD`Bu `Wentschließt dich nun doch, aufzutauchen und holst noch ein letztes Mal sehr tief Luft, bevor du in das Wasser springst. Zügig und mit kräftigen Schlägen schwimmst du aus der Höhle und mitten in den See hinein.`n
						Langsam merkst du wie dir der Atem schwindet, doch du hängst zu sehr an deinem Leben um jetzt noch aufzugeben. Mit ein paar letzten verzweifelten Schwimmzügen schießt du nach oben und brichst durch das Wasser. Laut schnappst du nach Luft und deine Lungen füllen sich wied`Be`wr.`n`n';
						if($Char->hashorse>0)
						{
							$str_output .= '`wDu schwimmst zum Ufer wo dich dein '.$playermount['mountname'].'`w schon erwartet und gemeinsam macht ihr euch wieder auf den Weg.';    
						}
						else
						{
							$str_output .= '`wDu schwimmst zum Ufer und machst dich erleichtert über den guten Ausgang deines Abenteuers wieder auf den Weg.';
						}
						output($str_output);
						
						buff_remove('`FLanger Atem');
						
						addnav($str_backtext,$str_backlink);
						break;
					}
				}
				break;
			}

				default:
				{
					output('`wD`Bu`W tauchst in die vierte Dimension. Das geht doch gar nic`Bh`wt.');
					addnav('Notausgang','village.php');
				}
			}
		break;
	}

	case 'cave':
	{ //die Höhle
		$session['bufflist']['`FLanger Atem']['rounds'] -= 1;
		if($session['bufflist']['`FLanger Atem']['rounds']< 1)
		{
			buff_remove('`FLanger Atem');
		}
		$arr_remind = user_get_aei('cave_remind',$Char->acctid);
		
		if($session['bufflist']['`FLanger Atem']['rounds']< 11 && !$arr_remind['cave_remind'])
		{ //Atembuff wird wenig
			$str_output .= '`wD`Bu `Wwanderst immer noch durch die Höhle, als deine Gedanken in Richtung Oberfläche schweifen und dir wird plötzlich klar wird, dass du noch genug Luft brauchst um wieder bis nach oben Tauchen zu können.`n`n
			Wenn du länger hier bleibst wirst du vielleicht nicht mehr hinaufkomm`Be`wn.';
			output($str_output);
			
			addnav('Was tust du?');
			addnav('Hier unten bleiben',$str_filename.'?op=remind');
			addnav('Auftauchen',$str_filename.'?op=dive&dir=up');
			break;   
		}
		else if(empty($session['bufflist']['`FLanger Atem']))
		{ //Atembuff aufgebraucht
			$str_output .= '`wD`Bi`wr wird plötzlich ein wenig komisch und du setzt dich auf den Boden der Höhle. In der Hoffnung, dass es nur dein Kreislauf ist, bleibst du ruhig sitzen, doch es wird keineswegs besser - eher schlechter. Dein Blick verschwimmt und auf einmal kannst du nicht mehr richtig Luft holen. Langsam erstickst du in der Höhle.`n`n
			Du bist tot. Wärst du nur aufgetauc`Bh`wt...';
			output($str_output);
			
			$Char->kill(100,0);
			addnews($Char->name.' `(hat sich beim Tauchen überschätzt.');   
			
			break;
		}
		else if(e_rand(1,13)==7)
		{ //ein Monster kommt
			$str_output .= '`wD`Bu`W hörst plötzlich schnelle Schritte, die von irgendwoher in deine Richtung gerannt kommen. Hektisch ziehst du deine Waffe und ehe du dich richtig umgedreht hast siehst du dich einem Gegner gegenüber, der dich angreift. Bevor du dich ihm entgegen stürzt fragst du dich, wie er hier hinunter kommen konn`Bt`we...';
			output($str_output);
			
			addnav('Auf in den Kampf',$str_filename.'?op=fight');
			
			$sql = 'SELECT * FROM creatures WHERE creaturelevel = '.$Char->level.' ORDER BY rand('.e_rand().') LIMIT 1';
			$result = db_query($sql);
			$badguy = db_fetch_assoc($result);
			
			$userattack = $Char->attack+e_rand(1,3);
			$userhealth = round($Char->hitpoints);
			$userdefense = $Char->defense+e_rand(1,3);
			$badguy['creaturehealth'] = $Char->hitpoints * 0.8;
			$badguy['creatureattack'] = $Char->attack - 2;
			$badguy['creaturedefense'] = $Char->defense - 2;
			$Char->badguy=createstring($badguy);
			break;
		} 
		
		page_header('Die Höhle');
	
		switch($_GET['dir'])
		{
			case '':
			{ //Höhle main
				$str_output .= '`c`b`SD`Ni`Be `WHö`Bh`Nl`Se`b`c`n
				`SS`Nt`Ba`Wunend schaust du dich in der Höhle um. Von den steinigen Wänden kommt ein schwaches Glimmen, welches in dem Raum einige Tunnel hervorhebt. Du schaust dich noch kurz um und siehst hinter dir das Wasserloch, aus dem das blaue Licht schwach herausleuchtet, bevor du zu einer Wand gehst und sie dir genauer anschaust.`n`n
				Das Gestein ist mit winzig kleinen Kristallen überzogen, von denen das Leuchten zu kommen scheint. Du willst dich gerade abwenden, als dir auffällt, dass die kleinen Striche an der Wand gar nicht zu dem Gestein gehören, sondern Worte die irgendjemand dort hineingeritzt haben muss. Ein wenig enttäuscht, dass du nicht der erste bist, der diese Höhle entdeckt hat wendest du dich wieder dem Rau`Bm `Nz`Su.`n`n
				`SWas wirst du tun?`n`n';
				output($str_output);
				
				addcommentary();
				viewcommentary('underwatercave','Einritzen',25,'schrieb');
				
				addnav('Was tust du?');
				
				$arr_xtal = user_get_aei('cave_xtal',$Char->acctid);
				
				if($arr_xtal['cave_xtal']>0)
				{
					addnav('Kristalle abkratzen',$str_filename.'?op=cave&dir=xtal&act=try');
				}
				else
				{
					addnav('Kristalle abkratzen',$str_filename.'?op=cave&dir=xtal');
				}
				addnav('Nimm einen Tunnel',$str_filename.'?op=cave&dir=tunnel');
				addnav('Leuchten untersuchen',$str_filename.'?op=cave&dir=glow');
				addnav('Zurück');
				
				$arr_remind = user_get_aei('cave_remind',$Char->acctid);
				
				if($arr_remind['cave_remind']>0)
				{
					addnav('Auftauchen',$str_filename.'?op=dive&dir=late');   
				}
				else
				{
					addnav('Auftauchen',$str_filename.'?op=dive&dir=up');   
				}
				break;
			}
				
			case 'xtal':
			{ //Kristalle abkratzen
				switch($_GET['act'])
				{
					case '':
					{
						$str_output .= '`wD`Bu`W beginnst mit deiner/deinem '.$Char->weapon.'`W an der Wand zu kratzen, in der Hoffnung, genug von den leuchtenden Kristallen zu bekommen um sie verkaufen zu können. Nach einer Weile stehst du schwitzend da, aber du hast kaum ein paar Kristalle zusammenbekomm`Be`wn.`n`n';
					
						switch(e_rand(1,4))
						{
							case 1:
							{
								$str_output .= '`wD`Bu`W gräbst und schuftest dich fast kaputt, aber du bekommst einfach nicht genug Kristalle zusammen mit denen du etwas anfangen könntest. Der Stein ist einfach zu hart und widerspenst`Bi`wg.`n`n
								`wDu gibst auf und lässt es lieber sein.';
								break;
							}
							
							case 2:
							case 3:
							{
								$str_output .= '`wP`Bl`Wötzlich fällt dir in der Wand etwas golden glänzendes auf. Du gehst näher hin und siehst, dass es Gold i`Bs`wt.`n`n
								`wDu steckst den Klumpen schnell ein und lässt von der Wand ab.';
							
								$Char->gold += 200;
								break;
							}
							
							default:
							{
								$str_output .= '`wP`Bl`Wötzlich fällt dir in der Wand etwas hell glänzendes auf. Du gehst näher hin und siehst dass es ein Edelstein i`Bs`wt.`n`n
								`wDu steckst ihn schnell ein und lässt von der Wand ab.';
							
								$Char->gems += 1;
								break;
							}
						}
						user_set_aei(array('cave_xtal'=>1),$Char->acctid);
						
						output($str_output);
						
						addnav('Weiter',$str_filename.'?op=cave');
						break;
					}
					
					case 'try':
					{
						switch(e_rand(1,5))
						{
							case 1:
							case 2:
							case 3:
							{
								$str_output .= '`wA`Bl`Ws du erneut an den Wänden zu arbeiten beginnst hörst du ein Ächzen, dass in der ganzen Höhle erschallt. Sofort lässt du deine Finger von dem Stein und gehst ein paar Schritte zurück, doch das Ächzen wird sehr schnell zu einem lauten Drönen und von der Decke beginnt der Fels zu bröckeln.`n`n
								Verzweifelt rennst du in einen der Tunnel um in einen sicheren Teil der Höhle zu kommen, doch überall bricht der Fels zusammen. Du merkst gerade noch wie vor dir ein großer Felsbrocken herunterfällt und mitten auf dich dra`Bu`wf.
								`wDu hättest es wohl doch nicht probieren sollen ..';
								output($str_output);
								
								$Char->kill(100,0);
								addnews($Char->name.' `(wurde in einer Höhle unter Felsen begraben!');   
								break;
							}
							
							default:
							{
								$str_output .= '`wA`Bl`Ws du erneut an den Wänden zu arbeiten beginnst, hörst du ein Ächzen, das in der ganzen Höhle erschallt. Sofort lässt du deine Finger von dem Stein und gehst ein paar Schritte zurü`Bc`wk.`n`n
								`wDu solltest dein Leben nicht aufs Spiel setzen, indem du die Höhle einstürzen lässt.';
								output($str_output);
								
								addnav('Weiter',$str_filename.'?op=cave');
								break;
							}
						}
						break;
					}

					default:
					{
						output('`wD`Bu `Wschlägst einen riesigen Kristall ab. Leider nur in deiner Einbildung, denn das hier gibts gar nic`Bh`wt.');
						addnav('Notausgang','village.php');
					}
				}
				break;
			}
			
			case 'tunnel':
			{ //Tunnel zur Nebenhöhle, kann eine Truhe enthalten
				switch($_GET['act'])
				{
					case '':
					{
						$str_output .= '`wD`Bu `Wgehst durch einen der beiden Tunnel und gelangst in einen Raum, der auf den ersten Blick auch völlig gleich aussieht, aber etwas kleiner i`Bs`wt.`n`n';
						
						switch(e_rand(1,3))
						{
							case 1:
							{
								$str_output .= '`wA`Bl`Ws du jedoch genauer hinschaust entdeckst du in einer Ecke eine alte, aber sonst gut erhaltene Truhe. Interessiert wendest du dich ihr zu und untersuchst s`Bi`we.';
								
								addnav('Die Truhe öffnen',$str_filename.'?op=cave&dir=tunnel&act=open');
								break;
							}
							default:
							{
								$str_output .= '`wE`Bi`Wgentlich hattest du gehofft, hier etwas Interessantes zu finden, doch es gibt nichts, was du nicht schon zuvor gesehen hättest. Enttäuscht wendest du dich wieder `Ba`wb.';
								break;
							}
						}
						output($str_output);
						
						addnav('Zurück');
						addnav('In die Höhle',$str_filename.'?op=cave');
						break;
					}
					
					case 'open':
					{
						$arr_chest = user_get_aei('cave_chest',$Char->acctid);
						
						if($arr_chest['cave_chest']<1)
						{
							$str_output .= '`wD`Bu`W ziehst die Truhe ein wenig aus ihrer Ecke hinaus und begutachtest das Schloss, welches diese versperrt. Mit deiner/deinem '.$Char->weapon.'`0 versucht du, den Deckel von der Truhe zu hebeln, doch der bewegt sich keinen Millimeter. Nach weiteren gescheiterten Versuchen lässt du enttäuscht von der Truhe ab und willst gerade diese Höhle verlassen, als dir ein paar Handbreit neben der Truhe ein Schlüssel auffällt.`n`n
							Du nimmst den Schlüssel und steckst ihn in das Schloss - er passt und es öffnet sich einwandfrei - während du dich wegen deiner Blindheit ohrfeigen könntest. Vorsichtig öffnest du den Deck`Be`wl.`n`n';
							
							switch(e_rand(1,6))
							{
								case 1:
								case 2:
								case 3:
								{
									$truhengold = 0;
									$truhengold += (($Char->level * $Char->level * 10) / $Char->level) * $Char->level * 2;
									$truhengold += (1000 - e_rand(1,500)); 
									
									$str_output .= '`wD`Bu`W schaust in eine mit Goldstücken gefüllte Truhe. Du kannst dein Glück gar nicht fassen und schaufelst so viel wie du nur kannst in deinen Beutel, damit auch ja nichts zurückbleibt. Nebenbei zählst du was du bekommen hast.`n
									Es sind `^'.$truhengold.' Goldstücke `Wzusammengekommen und deine Laune bessert sich merkli`Bc`wh.';
									
									$Char->gold += $truhengold;
									
									addnav('Zurück');
									addnav('In die Höhle',$str_filename.'?op=cave');
									break;
								}
								
								case 4:
								{
									$str_output .= '`wB`Be`Wvor du irgendetwas unternehmen kannst, kommt dir aus der Truhe ein spinnenartiges Wesen entgegen gesprungen und beißt dich in die Hand, bevor es davon krabbelt. Dein Gehör setzt langsam aus und dir wird schwarz vor Augen. Du denkst noch, was für ein Narr du gewesen bist, hier hinunter zu komm`Be`wn...';
									
									$Char->kill(100,0);
									addnews($Char->name.' `(wurde im Waldsee von einem `$Ungeheuer `(gebissen.');   
											
									break;
								}
								
								default:
								{
									$str_output .= '`wD`Bu `Wblickst in eine völlig leere Truhe, in der nur ein wenig Staub liegt. Enttäuscht kickst du sie in die Ecke, in der sie zuvor gestanden hat, und verlässt den Raum wied`Be`wr...';
									
									addnav('Zurück');
									addnav('In die Höhle',$str_filename.'?op=cave');
									break;
								}
							}
						user_set_aei(array('cave_chest'=>1),$Char->acctid);
						}
						else
						{
							$str_output .= '`wD`Bi`wr fällt sofort auf, dass die Truhe kein Schloss hat und dir wird klar, dass es die Truhe ist, die du vorhin bereits geöffnet hast. Du lässt sie einfach stehen und wendest dich wieder zum Geh`Be`wn...';
							
							addnav('Zurück');
							addnav('In die Höhle',$str_filename.'?op=cave');
						}
						output($str_output);
						break;
					}

					default:
					{
						output('Du bist in einem endlosen kreisförmigen Tunnel gelandet.');
						addnav('Notausgang','village.php');
					}
				}
				break;
			}
				
			case 'glow':
			{ //das Leuchten untersuchen
				switch($_GET['act'])
				{
					case '':
					{	
						if(getsetting('fishprank','') == '')
						{
							$str_output .= '`wD`Bu`W bemerkst ein starkes Leuchten, das aus einem der Tunnel zu kommen scheint. Neugierig machst du dich auf den Weg, dem Licht folgend. Als du auf einen Raum am Ende des Tunnels stößt entdeckst du dort ein Wasserloch, genauso wie das durch welches du gekommen bist.`n`n
							Du schaust genauer hin und siehst eine kleine Nixe, die auf dem Rand sitzt und dich mit großen Augen anstarrt. Sie spricht dich direkt an: "`FDu und deine Freunde an der Oberfläche, ihr hängt doch immer eure Angeln in den See... Soll ich einen deiner Freunde ärgern und ihm einen Streich spielen?" `WEin leuchten huscht über ihre Augen und mit einem herausfordernden Grinsen meint sie: "`FNatürlich nur, wenn du mir einen glänzenden Edelstein da lässt!"`0`n`n
							`wWirst du die Nixe bestechen?`0`n`n';
							
							addnav('Bestechen',$str_filename.'?op=cave&dir=glow&act=fell');
							addnav('Lieber nicht..',$str_filename.'?op=cave');
						}
						else
						{
							$str_output .= '`wD`Bu`W bemerkst ein starkes Leuchten, dass aus einem der Tunnel zu kommen scheint. Neugierig machst du dich auf den Weg, dem Licht folgend. Es scheint eine Ewigkeit zu dauern bis du das Ende des Tunnels erreicht hast.`n
							Doch was ist das? - Du befindest dich in einem dunklen Raum in dem nichts zu leuchten schei`Bn`wt...`n`n
							`wEnttäuscht machst du dich wieder auf den Weg den Tunnel zurück.';
							
							addnav('Zurück',$str_filename.'?op=cave');   
						}
						output($str_output);
						break;
					}
						
					case 'fell':
					{
						if($Char->gems>0)
						{
							$str_output .= '`wD`Bu`W überlegst und musst an den Angler dort oben denken. Gehässig grinsend gibst du der Nixe einen Edelsteine, den sie freudig in die Hände nimmt und ohne ein weiteres Wort im Wasser verschwindet. Erst jetzt merkst du, dass du sehr erschöpft bist, aber du kannst dir nicht erklären wie`Bs`wo...`n`n
							`wDu machst dich wieder auf den Weg.';
							
							$Char->gems --;
							$Char->hitpoints *= 0.5;
							
							savesetting('fishprank',$Char->name);
						}
						else
						{
							$str_output .= '`wA`Bl`Ws du der Nixe sagst, dass du den Handel eingehst schaut sich die mit böse funkelnden Augen an und sagt: "`FDu hast keine Edelsteine! Du bist Böse!" `WMit einem Mal verschwindet sie und dir wird schummrig vor Augen, du musst dich hinsetzten und hast dich erst nach einiger Zeit wieder erho`Bl`wt.';
							
							$Char->hitpoints = 1;
							$session['bufflist']['`FLanger Atem']['rounds'] -= 5;
							if($session['bufflist']['`FLanger Atem']['rounds']< 1)
							{
								buff_remove('`FLanger Atem');
							}
						}
						output($str_output);
						
						addnav('Weiter',$str_filename.'?op=cave');
						break;
					}
					break;

					default:
					{
						output('`wV`Bo`Wr dir erscheint Neptun persönlich und schleudert dir seinen Vierzack entgegen. Gerade noch rechtzeitig fällt dir ein, dass Neptun ja einen Dreizack hat und dies hier ein Fehler sein mu`Bs`ws.');
						addnav('Notausgang','village.php');
					}
				}
				break;
			}

			default:
			{
				output('`wD`Bu`W beamst dich in eine Höhle ohne Ausgang. Weil das nicht geht musst du in die Stadt zurü`Bc`wk.');
				addnav('Notausgang','village.php');
			}
		}
		break;
	}

	case 'remind':
	{ //Erinnerung dass Atembuff knapp wird
		$str_output .= '`wN`Ba`Wch kurzem hin und her entscheidest du dich dafür, hier unten in der Höhle zu bleiben. Mit den Konsequenzen musst du selber leb`Be`wn...`n
		`wMit kleinen Gewissensbissen machst du dich wieder auf den Weg.';
		output($str_output);
		
		user_set_aei(array('cave_remind'=>1),$Char->acctid);
		
		addnav('Weiter',$str_filename.'?op=cave');
		break;       
	}

	case 'fight':
	{ //der Kampf ansich
		$battle=true;
		$fight=true;
		if ($battle == true)
		{
			include_once ('battle.php');

			if ($victory == true)
			{
				output('`b`4Du hast `^'.$badguy['creaturename'].'`4 besiegt.`b`n');
				$badguy = array();
				$Char->badguy='';
				$Char->specialinc='';
				$gold = e_rand(100,400);
				$experience = $Char->level * e_rand(37,88);
				output('`#Du findest `6'.$gold.' `#Gold!`n');
				$Char->gold+=$gold;
				output('`#Du erhältst `6'.$experience.' `#Erfahrung!`n');
				$Char->experience+=$experience;
				addnav('Weiter',$str_filename.'?op=cave');
			}
			else if ($defeat == true)
			{
				output('`4Als du auf dem Boden aufschlägst, läuft `^'.$badguy['creaturename'].'`4 mit einem hämischen Grinsen davon.');
				$badguy=array();
				$Char->badguy='';
				$Char->hitpoints=0;
				$Char->alive=0;
				$Char->specialinc='';
                CQuest::died();
				addnav('Weiter','shades.php');
				addnews('`^'.$Char->name.'`5 starb in der Höhle unter dem Waldsee!');
			}
			else
			{
				if ($fight)
				{
					fightnav(true,false);
					if ($badguy['creaturehealth'] > 0)
					{
						$hp=$badguy['creaturehealth'];
					}
				}
			}
		}
		else
		{
			redirect($str_filename.'?op=cave');
		}
		break;
	}

	default:
	{
		output('Wie auch immer du hier her gekommen bist, du bist hier falsch.');
		addnav('Notausgang','village.php');
	}
}

page_footer();
?>
