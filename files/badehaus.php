<?php
/**
 * @author Ysandre (email:ysandre[at]gmx.net) & Séverin (Großteil der Beschreibungstexte)
 * @copyright Ysandre for atrahor.de
 */

require_once 'common.php';
addcommentary();

if (!isset($Char))
{
	exit();
}//endif

page_header('Das Badehaus');

switch ($_GET['op'])
{
	case '':
	case 'entry':
		if (Atrahor::$Session['bclothes']=='off')
		{
			if(mt_rand(1,10) == 1)
			{
				insertcommentary($Char->acctid,': `4 hat versucht die Umkleide nackt zu verlassen.','badehaus');
			}
 			
 			Atrahor::$Session['bmessage'] = 'on';
			redirect('badehaus.php?op=locker');
		}//endif
		
		output(get_title('`sDie Eingangshalle').'
				`sVom Brunnenplatz aus kann man bereits ein hohes Gebäude erkennen, das über eine breite 
				Treppe aus hellem Stein zu erreichen ist. Das Dach liegt in großer Höhe und wird von Säulen 
				getragen, die so dick sind wie die Stämme alter Bäume. Wenn man die Stufen erst 
				hinaufgegangen ist, kann man schon die weite Eingangshalle sehen, von der aus verschiedene 
				Wege abzweigen, zu den Umkleidekabinen, rechts für die Männer, links für die Frauen so wie 
				gerade aus auf der linken Seite ein Bereich, welcher der Entspannung dienen soll und rechts 
				die Baderäume. Natürlich findet hier wieder eine Trennung statt. Sobald man die Eingangshalle 
				betritt zieht man schon den Blick eines beleibten, ältlichen Mannes auf sich, welcher in ein 
				weißes Wickelgewand gekleidet ist. Dieser wird jederzeit gerne Auskunft geben, wo sich 
				was befindet, wobei er den Kopf ein wenig schief legt, was immer mal wieder dazu führt, dass 
				ihm der Lorbeerkranz, den er auf dem schütteren, weißen Haar trägt, auf die Schulter fällt. 
				Die Ähnlichkeit mit einem hakennasigen Römer ist nicht zu leugnen.`n`0');
		viewcommentary('badehaus');
		
		addnav('Einrichtungen');
		addnav('U?Umkleide', 'badehaus.php?op=locker');	
		if (access_control::is_superuser())
		{
			addnav('m?Umkleide des anderen Geschlechts `)(SU)', 'badehaus.php?op=superlocker');
		}//endif
		addnav('K?Kurbereich', 'badehaus.php?op=wellness');
		addnav('Zurück');
		addnav('Z?Zurück zum Vergnügungsviertel', 'nobelviertel.php');
		break;
	
	case 'locker':
		b_rooms($Char->sex, 'locker');
		break;
	
	case 'superlocker':
		if (access_control::is_superuser())
		{
			b_rooms(!$Char->sex, 'locker');
		}
		else
		{
			redirect('badehaus.php?op=entry');
		}//endif
		break;
		
	case 'bath':
		b_rooms($Char->sex, 'bath');
		break;
		
	case 'superbath':
		if (access_control::is_superuser())
		{
			b_rooms(!$Char->sex, 'bath');
		}
		else
		{
			redirect('badehaus.php?op=entry');
		}//endif
		break;
		
	case 'private': 
		if($_GET['act'] == 'search' && mb_strlen($_POST['search']) > 0)
		{
			$search = str_create_search_string($_POST['search']);
			$sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$search.'" AND acctid !='.
					($Char->acctid).' ORDER BY login="'.db_real_escape_string($_POST['search']).'" DESC, login 
					ASC';
			$res = db_query($sql);
			$link = 'badehaus.php?op=private&act=id';
			output('<form action="'.$link.'" method="POST">');
			output(' <select name="ziel">');

			while ( $p = db_fetch_assoc($res) )
			{
				output('<option value="'.$p['acctid'].'">'.strip_appoencode($p['name'],3).'</option>');
			}//endwhile

			output('</select>`n`n');
			output('<input type="submit" class="button" value="Und hopp!"></form>');
			
			addnav('',$link);
			addnav('Doch nicht');
			addnav('Zurück','badehaus.php?op=locker');			
		}
		else if($_GET['act'] == 'id' && $_POST['ziel'])
		{
			$ziel = (int)$_POST['ziel'];
			$sql = 'SELECT name FROM accounts WHERE acctid='.$ziel;
			$res = db_query($sql);
			$name = db_fetch_assoc($res);

			Atrahor::$Session['bpartner']=$ziel;
			Atrahor::$Session['bname']=$name['name'];

			redirect('badehaus.php?op=privatebath');
		}
		else
		{
			output('`sAhja, du möchtest also ganz... ungestört sein? Mit wem willst du denn in den privaten 
					Badebereich verschwinden, hä?`n`n`0');
			$link = 'badehaus.php?op=private&act=search';
			output('
			<form action="'.$link.'" method="POST">
				Suche in allen Feldern: ' 
				.'<br />'
				. JS::Autocomplete('search', true, true)
			.'</form>
			');
			
			addnav('',$link);
			addnav('Doch nicht');
			addnav('Zurück','badehaus.php?op=locker');
			addnav('Reload','badehaus.php?op=private');
		} //endif
		break;
		
	case 'privatebath': 
		b_rooms(2, 'bath');
		break;
			
	case 'wellness':
		output(get_title('`sDer Kurbereich').'
				`sDurch einen hellen Vorhang hindurch gelangt man in einen länglichen Raum, in dessen Mitte 
				kleinere Bereiche durch Vorhänge abgeteilt sind. Es ist stets angenehm aber nicht auffallend 
				warm und leise Musik ist zu hören. Einige Männer und Frauen gehen hier zwar zielstrebig aber 
				doch gelassen umher, Masseure, die natürlich jederzeit ihre Dienste anbieten. Eine fällt hier 
				besonders auf, denn ihre strahlenden grünblauen Augen ähneln denen einer anderen Person: Kala. 
				Dies jedoch ist Nala, ihre kleine Schwester, die hier gerade ihre Ausbildung macht und bereits 
				eine wichtige Position unter den anderen Masseuren inne hat. `n`0');
		
		addnav('Angebote nutzen');
		addnav('Gurkenmaske `#(1 Edelstein)', 'badehaus.php?op=mask');
		addnav('Massage `^('.($Char->level*20).' Gold)', 'badehaus.php?op=massage');
		addnav('Zurück');
		addnav('Z?Zurück zum Eingang', 'badehaus.php?op=entry');
		break;
	
	case 'mask':
		$rand = e_rand(1,100);
		output(get_title('`sNalas Gurkenmaske?!'));
		
		if ($Char->gems > 0)
		{
			if ($rand <=10)
			{
				output('`sWunder, oh Wunder! Obwohl dir ein wenig bange war die Dienste von Nala zu nutzen, 
						musst du nun feststellen, dass die kleine Schwester von Kala wohl doch einiges 
						auf dem Kasten hat und fleißig gelernt hat. Vielleicht aber war es auch nur Glück, 
						wer weiß? `nDu gewinnst an Charme!`0');
				$Char->charm++;
			}
			else
			{
				output('`sNaja, was hattest du erwartet? Nala ist noch in Ausbildung, da wird nicht alles sofort 
						auf Anhieb klappen oder sogar wirken.`0');
			}
			$Char->gems--;
		}//endif gems
		else
		{
			output('`sMit nichts in der Tasche, darf man auch nicht erwarten, dass die Angestellten hier
					überhaupt einen Finger rühren. Vielleicht solltest du das nächste Mal etwas von deinem 
					Ersparten mitbringen, hm?`0');
		}//endif
		addnav('Kabine verlassen');
		addnav('Z?Zurück', 'badehaus.php?op=wellness');
		break;
		
	case 'massage':
		$cost = $Char->level*20;
		output(get_title('`sNalas Massage?!'));
		
		if ($Char->gold < $cost)
		{
			output('`sWie hast du dir das eigentlich gedacht? So ganz ohne Gold in deinen Taschen? Mit dem, 
					was du im Moment bei dir hast, wird dir niemand eine Massage verpassen.`0');
		}
		else
		{
			switch ($rand = e_rand(1,100))
			{
				case ($rand <= 5):
					output('`sEine halbe Stunde später weißt du, dass sich diese Investition gelohnt hat. Du 
							fühlst dich völlig entspannt und voller Tatendrang. Vielleicht solltest du heute 
							noch einmal deine Waffe '.$Char->weapon.' schwingen, hm?`0');
					$Char->turns++;
					break;
				
				case ($rand <= 10):
					output('`sDie Masseuse versteht ihr Handwerk und bearbeitet deinen müden Leib mit gekonnten 
							kräftigen Bewegungen. Du bist arg verspannt und so braucht es wohl eine Weile, bis 
							du eine Verbesserung spürst. Ah, das tut wirklich gut und nachdem die halbe Stunde 
							vorbei ist, fühlst du dich wirklich entspannt.`0');
					
					$muscles = array(
								"name" => "`2Gelöste Muskeln`0",
								"rounds" => 30,
				    			"startmsg"=>"`0Die gelockerten Muskeln lassen dich schneller reagieren.",
								"wearoff" => "`0Die Muskeln verhärten sich wieder.",
								"atkmod" => 1.07,
								"activate"=>"roundstart");
					buff_add($muscles);
					break;
					
				case ($rand <= 40):
					output('`sDie Masseuse versteht ihr Handwerk und bearbeitet deinen müden Leib mit gekonnten 
							kräftigen Bewegungen. Du bist arg verspannt und so braucht es wohl eine Weile, bis 
							du eine Verbesserung spürst. Trotz allem geht es dir danach nicht wirklich besser 
							und humpelnd entfernst du dich aus der Kabine, um nach irgendetwas Bequemen zum 
							Liegen zu suchen.`0');
					$Char->hitpoints = 1;
					break;
					
				default:
					output('`sEine halbe Stunde lang hast du dich durchkneten lassen, aber irgendwie hat sich 
							keine großartige Verbesserung eingestellt. Naja, aber immerhin fühlt sich dein 
							Nacken nicht mehr ganz so steif und ungelenk an.`0');
					break;
			} //endswitch $rand
			$Char->gold -= $cost;
		}//endif 
		
		addnav('Kabine verlassen');
		addnav('Z?Zurück', 'badehaus.php?op=wellness');
		break;

}//endswitch op
page_footer();

function b_rooms ($sex, $room)
{
	global $Char;
	if ($room == 'locker')
	{
		if (!isset(Atrahor::$Session['bclothes']))
		{
			Atrahor::$Session['bclothes'] = 'on';
		}//endif
			
		if ($_GET['bclothes'] == 'off')
		{
			Atrahor::$Session['bclothes'] = 'off';
		}
		elseif ($_GET['bclothes'] == 'on')
		{
			Atrahor::$Session['bclothes'] = 'on';
		}//endif
			
		if (Atrahor::$Session['bmessage']=='on')
		{
			JS::MessageBox("Du kannst doch nicht nackt die Umkleide verlassen!", "Oops!");
			Atrahor::$Session['bmessage']='off';
		}//endif message

		if ($sex == 0)
		{
			output(get_title('`sDie Männerumkleide').'
				`sDie erste Tür auf der rechten Seite führt in einen Raum, der von einer langen Bank in der
				Mitte geteilt wird. Auch an den Wänden sind Bänke und im Abstand von etwa einer Schulterbreite
				sind Haken in die Wand eingesetzt, an denen man Kleidung aufhängen kann. Dort, wo die weiße
				Wand frei ist, sind kleine Steinchen zu einem Muster zusammen gesetzt, was Wellen darstellt.
				An einigen Stellen, an denen allerdings ein dunkler Stein sitzen sollte, ist ein kleines Loch,
				groß genug, um von der anderen Seite hindurch zu spähen und den Raum recht gut einzusehen.`n`n
				Außerdem kannst du ein Schild erkennen, auf welchem du folgende Nachricht erkennen kannst:`n
				`4Das Tragen von anderen Kleidungsstücken außer einem Handtuch ist im Badebereich nicht
				gestattet! Gez. der Bademeister`n`n`0');
			b_special('lockermale');
			viewcommentary('lockermale');
		}//endif male

		else
		{
			output(get_title('`sDie Frauenumkleide').'
				`sDie erste Tür auf der linken Seite führt in einen Raum, der von einer langen Bank in der
				Mitte geteilt wird. Auch an den Wänden sind Bänke und im Abstand von etwa einer Schulterbreite 
				sind Haken in die Wand eingesetzt, an denen man Kleidung aufhängen kann. Dort, wo die weiße 
				Wand frei ist, sind kleine Steinchen zu einem Muster zusammen gesetzt, was Wellen darstellt. 
				An einigen Stellen, an denen allerdings ein dunkler Stein sitzen sollte, ist ein kleines Loch, 
				groß genug, um von der anderen Seite hindurch zu spähen und den Raum recht gut einzusehen.`n`n 
				Außerdem kannst du ein Schild erkennen, auf welchem du folgende Nachricht erkennen kannst:`n
				`4Das Tragen von anderen Kleidungsstücken außer einem Handtuch ist im Badebereich nicht 
				gestattet! Gez. der Bademeister`n`n`0');
			b_special('lockerfemale');
			viewcommentary('lockerfemale');
		}//endif female
		
		addnav('Kleidung');
		if (Atrahor::$Session['bclothes']=='on')
		{
			addnav('Ausziehen', 'badehaus.php?op=locker&bclothes=off');
		}//endif bathclothes on
		else
		{
			addnav('Anziehen', 'badehaus.php?op=locker&bclothes=on');
			addnav('Zu den Bädern');
			addnav('B?Badebereich', 'badehaus.php?op=bath');
			if (access_control::is_superuser())
			{
				addnav('a?Badebereich des anderen Geschlechts `)(SU)', 'badehaus.php?op=superbath');
			}
			addnav('P?Privater Baderaum', 'badehaus.php?op=private');
			if (isset(Atrahor::$Session['bpartner']))
			{
				addnav('n?Zu '.Atrahor::$Session['bname'].' ins Bad','badehaus.php?op=privatebath');
			}
		}//endif bathclothes off
		
		addnav('Sonstiges');
		addnav('U?Untersuche das Wandloch', 'badehaus.php?op=locker&var=hole');
		addnav('Zurück');
		addnav('Z?Zurück zum Eingang', 'badehaus.php?op=entry');
	}//endif locker
	else
	{	
		switch($_GET['bubble'])
		{
			case 'rose':
				$bcolor = 'R';
				break;
			
			case 'camomile':
				$bcolor = 'G';
				break;
			
			case 'lavender':
				$bcolor = 'V';
				break;
			
			default:
				$bcolor = 's';
				break;				
		}//endswitch bubble
		
		if ($sex == 0)
		{
			output(get_title('`'.$bcolor.'Das Männerbad').'
				`'.$bcolor.'Der Raum ist recht groß, doch kann man die tatsächlichen Ausmaße kaum erkennen, 
				weil zu  jeder Zeit Dampf herum wabert, der die bis auf halbe Höhe mit Holz verkleideten Wände 
				fast unsichtbar macht. Rundherum sind an den Wänden Holzbänke aus dunklem Holz, die stufenartig 
				drei Reihen in die Tiefe hinabgehen. Von dort aus tritt man auf einen recht schmalen Rand, 
				der die Bänke von einem tiefen Becken trennt, welches bis zum Rand mit stets warmem Wasser 
				angefüllt ist, das von unten her erwärmt wird.`n`0 ');
			b_special('menbath');
			viewcommentary('menbath');
		}//endif male
		
		else if ($sex == 1)
		{
			output(get_title('`'.$bcolor.'Das Frauenbad').'
				`'.$bcolor.'Das Bad ist ein recht großer Raum, deren Boden und ebenso ein Teil der Wände mit weißem 
				Stein ausgelegt ist. Die Kühle des Marmors wird jedoch von den zahlreichen, mit warmem 
				Wasser gefüllten Becken vertrieben, die zum Entspannen einladen, eines aufgrund seiner 
				Größe auch zum Schwimmen. Dazu finden sich zahlreiche Bänke aus Stein, wie auch Korbstühle, 
				in denen man ruhen und sich entspannen kann. Ein Teil des Raumes ist durch eine recht hohe 
				Wand aus Pflanzen abgetrennt, dahinter findet sich ein weiteres Becken und einige Liegen. 
				Außerdem wird niemand weit gehen müssen, um eine kleine Kommode vorzufinden, auf der 
				zahlreiche, weiße Handtücher liegen.`n`0');
			b_special('femalebath');
			viewcommentary('femalebath');
		}//endif female
		
		else
		{
			$chat = 'bath_'.min($Char->acctid, Atrahor::$Session['bpartner']).'_'.max($Char->acctid, 
					Atrahor::$Session['bpartner']);
	
			output(get_title('`'.$bcolor.'Der private Baderaum').'
					`'.$bcolor.'Hinter den großen Baderäumen liegt ein kleiner, abgelegener Raum, der nur auf 
					Wunsch geöffnet wird. Der Fußboden ist mit hellem Marmor ausgelegt, die Wände hingegen sind 
					mit dunklem Holz verkleidet. An einer Seite des Raumes steht eine Vielzahl Pflanzen, 
					darunter schließt das Becken ab, das den größten Teil des Raumes einnimmt. Hier kann man in 
					ungestörter Zweisamkeit ein gemeinsames Bad genießen oder auf zwei langen Bänken sitzen 
					oder liegen, ganz nach Belieben.`n`0');
			b_special($chat);
			viewcommentary($chat,'Kommentar hinzufügen',25,'sagt',false,true,false,0,false,true,2);
		}//endif private
		
		addnav('Badezusätze');
		addnav('keine Zusätze', 'badehaus.php?op='.($sex>1?'privatebath':'bath'));
		addnav('Rosenblütenblätter', 'badehaus.php?op='.($sex>1?'privatebath':'bath').'&bubble=rose');
		addnav('Kräutersud', 'badehaus.php?op='.($sex>1?'privatebath':'bath').'&bubble=camomile');
		addnav('Lavendelblüten', 'badehaus.php?op='.($sex>1?'privatebath':'bath').'&bubble=lavender');
		addnav('Sonstiges');
		addnav('Handtuchkampf', 'badehaus.php?op='.($sex>1?'privatebath':'bath').'&var=towel');
		addnav('Popobombe', 'badehaus.php?op='.($sex>1?'privatebath':'bath').'&var=bomb');
		if ($Char->drunkenness >= 33)
		{
			addnav('Hechtsprung', 'badehaus.php?op='.($sex>1?'privatebath':'bath').'&var=header');
		}//endif
		addnav('Zurück');
		addnav('Z?Zurück in die Umkleide', 'badehaus.php?op=locker');
	}//endif bath
}//endfunc b_rooms

function b_special ($chat)
{
	global $Char;
	$rand = e_rand(1,21);
		
		
	switch ($_GET['var'])
	{
		case 'towel':
			output('`n`sNur rumsitzen ist dir auf Dauer langweilig und jetzt schon nach Hause gehen ist keine 
				Option für dich, sodass du angestrengt überlegst, wie du deine Langeweile vertreiben kannst. ');
			switch ($rand)
			{
				case ($rand <= 7):
					output('`sSchließlich greifst du nach deinem Handtuch und fängst damit an wild um dich zu 
							schlagen und nach Opfern Ausschau zu halten. Das ist ein Spaß!`0`n`n');
					break;
					
				case ($rand <= 14):
					output('`sSchließlich greifst du nach deinem Handtuch und fängst damit an wild um dich zu 
							schlagen. Leider ruft man den Bademeister und jener hält dir eine kräftige 
							Standpauke.`0`n`n');
					$Char->reputation--;
					break;
					
				default:
					output('`sSchließlich greifst du nach deinem Handtuch und fängst damit an wild um dich zu 
							schlagen. Irgendwie aber verlierst du dein Gleichgewicht und die rutschigen Fließen 
							tun ihr übriges. Es zieht dir den Boden unter den Füßen weg und du knallst 
							schmerzhaft mit den Hinterkopf auf. `4Autsch!`0`n`n');
					$sql = 'INSERT INTO commentary (section,author,comment, postdate) values 
						("'.$chat.'","'.$Char->acctid.'","/me`0 ist beim Handtuchkampf ausgerutscht und liegt 
						nun bewusstlos am Boden.`0", NOW())';
 					db_query($sql);
					$Char->hitpoints = 1;
					break;
			}//endswitch $rand
			break;
		
		case 'bomb':
			output('`s`nNur rumsitzen ist dir auf Dauer langweilig und jetzt schon nach Hause gehen ist keine 
					Option für dich, sodass du angestrengt überlegst, wie du deine Langeweile vertreiben 
					kannst. Schließlich entscheidest du dich deinem inneren Kind nachzugeben, das schon die 
					ganze Zeit über nicht still sein will. Verstohlen blickst du dich um, ehe du mit einem 
					breiten Grinsen auf das Becken zurennst und mit angezogenen Beinen, Bobbes voraus ins Wasser 
					springst. Als du wieder auftauchst, musst du feststellen, dass ');
			
			switch ($rand)
			{
				case ($rand <= 17):
					output('`sder Bademeister deine Aktion ganz und gar nicht in Ordnung fand. Nur schnell weg, 
							sonst musst du dir seine Standpauke anhören!`0`n`n');
					break;
				default:
					output('`sdu beim Eintauchen wohl irgendwie dein Handtuch verloren hast. Peinlich, 
							peinlich!`0`n`n');
					$sql = 'INSERT INTO commentary (section,author,comment, postdate) values 
						("'.$chat.'","'.$Char->acctid.'","/me`0 hat '.($Char->sex?'ihr':'sein').' Handtuch bei 
						der Wasserbombe verloren.", NOW())';
					db_query($sql);
					break;
			}//endswitch bomb
			break;
			
		case 'header':
			output('`sWer hat eigentlich die dumme Regel erfunden nüchtern ins Wasser zu gehen? Du jedenfalls 
					nicht. Betrunken bist du durch den warmen, mit Dampfschwaden gefüllten Badebereich 
					getorkelt, bis dir die fixe Idee gekommen ist, einen Hechtsprung in das Becken zu machen. 
					Schwankend nimmst du Anlauf und ');
			switch ($rand)
			{
				case ($rand <= 3):
					output('`sschaffst es trotz deines katastrophalen Zustandes einen anständigen Köpfer 
							hinzulegen. Tief tauchst du in das Becken ein und entdeckst am Boden ein wenig 
							Gold! Ha, das hat sich ja mal gelohnt.`0`n`n');
					$Char->gold += 5;
					break;
				case ($rand <= 10):
					output('`sspringst frohen Mutes in das Nass. Irgendwie ist dir aber nicht ganz wohl und 
							alles dreht sich, sodass du nicht weißt, in welche Richtung du auftauchen musst. 
							Du ertrinkst beinahe und nur das Eingreifen des Bademeisters hat Schlimmeres 
							verhindert!`n`n');
					$Char->hitpoints = 1;
					$sql = 'INSERT INTO commentary (section,author,comment, postdate) values 
						("'.$chat.'","'.$Char->acctid.'","/me`0 ist beinahe ertrunken, weil '.($Char->sex?'sie':
						'er').' sternhagelvoll ins Wasser gesprungen ist.", NOW())';
					db_query($sql);
					break;
				default:
					output('`slandest mit dem Bauch auf der Wasseroberfläche. Naja, so ein Bauchplatscher hat 
							auch etwas Lustiges an sich!`n`n');
					break;
			}//endswitch bomb
			break;
			
		case 'hole':
			output('`sEinen Moment hast du noch gezögert dich dem Loch in der Wand zu nähern, aber dann ist 
					dir eingefallen, dass die andere Seite auch in eure Umkleide schauen kann. Warum solltest du 
					das dann nicht dürfen. Unerschrocken wie du nun einmal bist, näherst du dich also dem 
					Guckloch und riskierst einen Blick hindurch. Es braucht einen Augenblick, bis du ');
		
			switch ($rand = e_rand(1,100))
			{
				case ($rand <= 15):
					output('`serkennen musst, dass es nichts Besonderes zu sehen gibt. Die Umkleide auf der 
							anderen Seite ist leider leer!`0`n`n');
					break;
					
				case ($rand <= 30):
					output('`szu deinem Leidwesen mit ansehen musst, wie sich '.($Char->sex?'ein alter, 
							runzeliger Mann seiner ':'eine alte, runzelige Frau ihrer ').'Kleidung entledigt. 
							Das hättest du dir gerne ersparen wollen!`0`n`n');
					break;
					
				case ($rand <= 40):
					output('`sbemerkst, dass du gar nicht mehr so alleine bist und man dich dabei erwischt hat, 
							wie du deinen voyeuristischen Trieben nachgegangen bist. Peinlich, peinlich!`0`n`n');
					$Char->reputation *= 0.98;
					$sql = 'INSERT INTO commentary (section,author,comment, postdate) values 
						("'.$chat.'","'.$Char->acctid.'","/me`4 wurde beim Spannen beobachtet!`0", NOW())';
 					db_query($sql);
					break;
					
				case ($rand <= 45):
					output('`setwas erkennen kannst. Doch als sich das Bild endlich klärt und du scharf 
							sehen kannst, erblickst du '.($Char->sex?'deinen Liebsten ':'deine Liebste ').'beim 
							Umziehen. Ein breites Grinsen legt sich auf deine Lippen und gebannt verharrst du 
							eine Weile an dem Loch, bis dein Herz nicht mehr zu sehen ist. Hm, vielleicht 
							solltest du dich in das '.($Char->sex?'Männerband ':'Frauenbad ').'schleichen, um 
							deinem Schatz einen Besuch abzustatten?`0`n`n');
					$Char->charm++;
					break;
					
				case ($rand <= 65):
					output('`sfeststellen musst, dass du gerade '.($Char->sex?'Seth ':'Violet ').'beim Umkleiden 
							zuschaust. Länger als nötig verweilt dein Blick auf der allseits bekannten Person und
							erst nachdem es nichts mehr zu bestaunen gibt, was du nicht gesehen hast, wendest du 
							dich ab. Nicht schlecht...`0`n`n');
					break;
					
				case ($rand <= 85):
					output('`serkennen kannst, um wen es sich auf der andere Seite handelt. Ist das nicht ');
					$row = db_fetch_assoc(db_query('SELECT name FROM accounts WHERE locked=0 AND sex='.
							($Char->sex?'0':'1').' ORDER BY rand('.e_rand().') LIMIT 1'));
	                output($row['name'].'`s? Peinlich berührt wendest du den Blick ab und begibst dich wieder zu 
	                		deinem Bündel, um dich fertig für das Bad zu machen.`0`n`n');
					break;
					
				case ($rand <= 95):
					output('`serschrocken feststellen musst, dass dir von der Gegenseite ein fremdes Auge 
							entgegenblinzelt. Du weichst entsetzt einen Schritt zurück und überlegst dir, ob du
							dich hier wirklich umziehen sollst!`0`n`n');
					break;
					
				default:
					output('`senttäuscht feststellen musst, dass das Loch in der Wand wohl schon einmal entdeckt
							worden war und man es verhängt hat. Schade aber auch!`0`n`n');
					break;	
			} //endswitch $rand
		break;
	}//endswitch var
} //endfunc b_special
?>