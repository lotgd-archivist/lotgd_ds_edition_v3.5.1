<?php
/**
 * @author Sa onserei und Eleya für atrahor.de
 * Gehobenes Viertel für alle Einrichtungen, die das gemeine Volk eher nicht aufsuchen würde/ wo sie wenig erwünscht sind, u.a. kulturelle Einrichtungen und Luxus
 */

require_once 'common.php';

$show_invent = true;
$filename=basename(__FILE__);
addcommentary();
checkday();
$bool_return_viewcommentary_output = true;

if ($Char->alive==0)
{
	redirect('shades.php');
}
if($Char->prangerdays>0)
{
	redirect("pranger.php");
}

$session['user']['specialinc']='';
$session['user']['specialmisc']='';

switch($_GET['op'])
{
	case 'showmanscorner':
		page_header('Der Backstagebereich');
		addnav('Zurück');
		addnav('T?Theater',$filename.'?op=theater');
		switch ($_GET['act'])
		{
			default:
			case '':
				{
					$str_output .= get_title('Der Backstagebereich').'`tEin geräumiger Bereich, in dem sich die Schauspieler vor dem Stück bereit machen können. Natürlich gibt es hier Kleiderständer mit viel Platz für alle nötigen Kostüme, Schränke, in denen weitere Utensilien verstaut werden können, sowie an den Wänden mehrere große Spiegel.
In einer der Ecknischen steht zudem ein Schreibtisch, groß genug, dass man darauf mehrere Blätter des Drehbuches ausbreiten und noch einmal durchgehen kann. Zudem bietet der Raum genug Platz, dass ein nervöser Schauspieler seinen Text vor sich hinmurmelnd hin- und herlaufen kann, ohne irgendwem im Weg zu sein.';

					addnav('Aktonen');
					if(getsetting('showman_theater_show_name','') != '')
					{

						addnav('Tickets vergeben',$filename.'?op=showmanscorner&act=tickets');
						addnav('Offiziell auf die Bühne gehen!',$filename.'?op=showmanscorner&act=theshowbegins&subact=introtext',false,false,false,true,'Möchtest du wirklich auf die Bühne gehen? Das ist offiziell, wenn du an dem Stück aktiv teilnimmst!');
						addnav('Nur so auf die Bühne gehen!',$filename.'?op=theshow&act=onstage');
					}
					$str_output .= viewcommentary('viertel_showmanscorner');

					addnav('Nächste Vorstellung festlegen',$filename.'?op=showmanscorner&act=modify');
					addnav('Vorstellung beenden',$filename.'?op=showmanscorner&act=delete',false,false,false,true,'Möchtest du die Vorstellung wirklich abbrechen? Alle bisherigen Kommentare werden gelöscht!, das Trinkgeld verteilt...kurzum, die Vorstellung wird beendet.');

					break;
				}
			case 'tickets':
				{
					$f_userdropdown = create_function('&$arr_receiver,&$arr_form','
						foreach($arr_receiver as $arr_user)
						{
						$str_select .= ",".$arr_user["acctid"].",".strip_appoencode($arr_user["name"]);
						}
						$arr_form["receiver_id"] = "Der Empfänger,select".$str_select;

						unset($arr_form["receiver"]);
						');
					$int_tickets_left = getsetting('showman_theater_show_free_tickets',30);
					$arr_form = array(
						'receiver_id'=> 'Die EmpfängerID,hidden',
						'receiver' => 'Der Empfänger,usersearch'
					);
					$arr_data = persistent_nav_vars(array_keys($arr_form));

					if ($_GET['subact'] == 'newsearch')
					{
						$arr_data = persistent_nav_vars(array_keys($arr_form),true);
					}
					elseif ($_GET['subact'] == 'sendtickets')
					{
						$int_receiver = (int)$arr_data['receiver_id'];
						$str_receiver = $arr_data['receiver'];

						if($int_receiver > 0)
        				{
        					$arr_receiver = CCharacter::getChars($int_receiver,'acctid,name',array('acctid' => array('type'=>CCharacter::SEARCH_EXACT) ),'','',1);
        				}
        				else
        				{
        					$arr_receiver = CCharacter::getChars($str_receiver,'acctid,name',array('name' => array('type'=>CCharacter::SEARCH_LIKE_EXT) ),'','',50);
        				}

        				if(count($arr_receiver) == 0)
        				{
        					setStatusMessage('Tut mir leid, so einen Bewohner haben wir in '.getsetting('townname','Atrahor').' nicht.');
        				}
        				elseif( (count($arr_receiver) > 1 && $int_receiver == 0) )
        				{
        					setStatusMessage('Es gibt mehrere Bewohner die einen ähnlichen Namen haben, bitte wähle den entsprechenden Nutzer aus der Liste aus, oder verfeinere die Suche.');
        					$f_userdropdown($arr_receiver,$arr_form);
        				}
        				elseif(count($arr_receiver) == 1)
        				{
							$arr_info = array(
								'tpl_name'	=> 'Ticket für die Vorstellung von: '.getsetting('showman_theater_show_name',''),
								'content'=>getsetting('showman_theater_show_id',''),
								'tpl_gold' => getsetting('showman_theater_show_fee',0)
							);
							item_add($arr_receiver[0]['acctid'],'theater_ticket',$arr_info);
							$int_tickets_left--;

							savesetting('showman_theater_show_free_tickets',$int_tickets_left);
							setStatusMessage('Du hast '.$arr_receiver[0]['name'].'`$ ein Ticket zukommen lassen');

							systemmail($arr_receiver[0]['acctid'],'Ein Theaterticket',$Char->name.'`t hat dir ein Theatherticket für die Vorstellung '.getsetting('showman_theater_show_name','').' zukommen lassen. Es ist für die gesamte Laufzeit des Schauspiels gültig. Du findest es in deinem Inventar.');

							persistent_nav_vars(array_keys($arr_form),true);

							redirect($filename.'?op=showmanscorner&act=tickets');
        				}
					}

					$str_output .= get_title('Die Tickets').'`tAls Schausteller ist es euch vergönnt einige Freitickets an Freunde und Verwandte zu verschenken. Füge dazu einfach den Namen deines Gastes dieser Liste hinzu und die Roadies machen schon den Rest. Sollten alle Freitickets allerdings bereits vergeben sein, so kannst du hier zumindest sehen, wer schon alles ein Ticket hat.`n';

					if($int_tickets_left > 0)
					{
						$str_output .= 'Es verbleiben noch `y`b'.$int_tickets_left.' Freitickets`b`t die von den Schaustellern vergeben werden können.';

						$str_output .= form_header($filename.'?op=showmanscorner&act=tickets&subact=sendtickets');
						$str_output .= generateform($arr_form,$arr_data);
						$str_output .= form_footer();
					}

					$arr_users = db_get_all('SELECT a.name FROM accounts a LEFT JOIN items i ON (a.acctid=i.owner) WHERE i.tpl_id="theater_ticket" AND i.content="'.getsetting('showman_theater_show_id','').'"');

					$str_output .= '`n<hr />`nEs haben bereits '.count($arr_users).' Bewohner ein Ticket für Eure Veranstaltung erworben oder erhalten:`n';

					foreach ($arr_users as $arr_user)
					{
						$str_output .= '`t'.$arr_user['name'].'`0`n';
					}

					addnav('Zurück hinter die Bühne',$filename.'?op=showmanscorner');
					addnav('Neue Suche',$filename.'?op=showmanscorner&act=tickets&subact=newsearch');

					break;
				}
			case 'modify':
				{
					$str_output .= get_title('Ein neues Stück veröffentlichen').'`tIn einem kleinen Häuschen am Eingangsbereich des Theaters sitzt ein nicht junger, aber auch nicht alt wirkender Mann, der dafür sorgt, dass neue Vorstellungen bekannt gegeben werden. Hier muss sich jeder melden, der ein Stück aufführen lassen möchte, um dafür zu sorgen, dass das Theater an fraglichen Terminen reserviert ist, und natürlich auch, dass die Leute von der Aufführung erfahren.`n Ein großer Stapel hochformatigen Papiers und eine edle Breitfeder ermöglichen es dir hier das Plakat für die nächste anstehende Vorstellung zu entwerfen. Du würdest den alten Aushang einfach überhängen. Sei dir aber bewusst, dass dann gnadenlos das alte Schauspiel abgebrochen wird!';
					$arr_form = array(
						'showman_theater_show_name' => 'Wie lautet der Name der nächsten Vorstellung?',
						'showman_theater_show_description' => 'Worum geht es in der nächsten Vorstellung?,textarea,60,5',
						'showman_theater_show_fee'		=> 'Wieviele Goldstücke soll Eintritt bezahlt werden müssen,int',
					);
					$arr_data = array(
						'showman_theater_show_name' => getsetting('showman_theater_show_name',''),
						'showman_theater_show_description' => getsetting('showman_theater_show_description',''),
						'showman_theater_show_fee'		=> getsetting('showman_theater_show_fee',''),
					);

					$str_output .= form_header($filename.'?op=showmanscorner&act=save');
					$str_output .= generateform($arr_form,$arr_data);
					$str_output .= form_footer();

					addnav('Zurück hinter die Bühne',$filename.'?op=showmanscorner');
					break;
				}
			case 'save':
				{
					savesetting('showman_theater_show_name',$_REQUEST['showman_theater_show_name']);
					savesetting('showman_theater_show_description',$_REQUEST['showman_theater_show_description']);
					savesetting('showman_theater_show_fee',abs((int)$_REQUEST['showman_theater_show_fee']));
					savesetting('showman_theater_show_id',md5($_REQUEST['showman_theater_show_name']));
					savesetting('showman_theater_show_free_tickets',30);
					savesetting('showman_theater_show_donation',0);

					setStatusMessage('Du hast die Angaben zur nächsten Veranstaltung überarbeitet');
					insertcommentary($Char->acctid,':: hängte einen neuen Aushang auf','viertel_showmanscorner');
					redirect($filename.'?op=showmanscorner');
					break;
				}
			case 'delete':
				{
					$int_donation = getsetting('showman_theater_show_donation',0);

					$arr_users = db_get_all('SELECT acctid,name FROM accounts LEFT JOIN account_extra_info USING (acctid) WHERE job='.JOB_SHOWMAN);
					$int_showmen = count($arr_users);

					if($int_showmen > 0)
					{
						foreach ($arr_users as $arr_user)
						{
							if($int_donation > 0)
							{
								$int_part_donation = min(1000,$int_donation/$int_showmen);
								user_update(array('goldinbank'=>array('sql'=>true,'value'=>'goldinbank+'.$int_part_donation)),$arr_user['acctid']);
								systemmail($arr_user['acctid'],'Lohn für eine Vorstellung','`tDu erhälst hiermit deinen Lohn für die Vorstellung '.getsetting('showman_theater_show_name').'`tDie Gäste bedanken sich bei den Schaustellern!');
							}
						}
					}

					savesetting('showman_theater_show_name','');
					savesetting('showman_theater_show_description','');
					savesetting('showman_theater_show_fee','');
					savesetting('showman_theater_show_id','');
					savesetting('showman_theater_show_free_tickets',0);
					savesetting('showman_theater_show_donation',0);

					setStatusMessage('Du hast die aktuelle Veranstaltung offiziell beendet');
					insertcommentary($Char->acctid,':: beendete die aktuelle Vorstellung.','viertel_showmanscorner');
					redirect($filename.'?op=showmanscorner');
					break;
				}
			case 'theshowbegins':
				{
					insertcommentary($Char->acctid,':: betritt durch den Vorhang die Bühne.','viertel_onstage');
					redirect($filename.'?op=theshow&act=onstage');
					break;
				}
		}
		break;
	case 'theshow':
		{
			page_header('Das Schauspiel');
			switch ($_GET['act'])
			{
				default:
				case '':
					{
						if(item_count('tpl_id="theater_ticket" AND content="'.getsetting('showman_theater_show_id','').'" AND owner='.$Char->acctid) == 0)
						{
							setStatusMessage('Die Platzanweiser geleiten dich mit freundlichen Worten wieder zurück in das Foyer, denn du hast keine gültiges Ticket. Ich frage mich nur wie du dann hier rein gekommen bist.');
							redirect($filename.'?op=theater');
						}
						$str_output .= get_title(getsetting('showman_theater_show_name','')).'`tSehr geehrte Damen, sehr geehrte Herren. Auf dieser Bühne präsentieren wir ihnen nun das Stück "'.getsetting('showman_theater_show_name','').'`t" Genießen sie einige schöne Momente durch unsere geachteten Schausteller. Vorhang auf für ein Stück welches sich wie folgt kurz zusammenfassen lässt`0: '.getsetting('showman_theater_show_description','').'`0';
						addnav('Das Schauspiel verlassen',$filename.'?op=theater');
						$str_output .= viewcommentary('viertel_onstage','',25,'sagt',false,false);
						addnav('Der Spendenhut');
						if($Char->gold >= 50) addnav('50 Goldstücke in den Spendenhut',$filename.'?op=theshow&act=spend&gold=50');
						if($Char->gold >= 100) addnav('100 Goldstücke in den Spendenhut',$filename.'?op=theshow&act=spend&gold=100');
						if($Char->gold >= 250) addnav('250 Goldstücke in den Spendenhut',$filename.'?op=theshow&act=spend&gold=250');
						if($Char->gold >= 500) addnav('500 Goldstücke in den Spendenhut',$filename.'?op=theshow&act=spend&gold=500');
						break;
					}
				case 'onstage':
					{
						$str_output .= get_title(getsetting('showman_theater_show_name','')).'`tDu stehst endlich auf der Bühne! Das nervöse Kribbeln in deinem Magen verwandelt sich mehr und mehr in das Hochgefühl, welches jeder Schauspieler empfindet, wenn er wieder vor seinem geliebten Publikum steht. Jetzt ist es deine Aufgabe, sie mit deinem Spiel zu verzaubern. Toi toi toi!';
						addnav('Zurück hinter die Bühne',$filename.'?op=showmanscorner');
						$str_output .= viewcommentary('viertel_onstage');
						break;
					}
				case 'ticket':
					{
						$arr_info = array(
							'tpl_name'	=> 'Ticket für die Vorstellung von: '.getsetting('showman_theater_show_name',''),
							'content'=>getsetting('showman_theater_show_id',''),
							'tpl_gold' => getsetting('showman_theater_show_fee',0)
						);
						item_add($Char->acctid,'theater_ticket',$arr_info);
						savesetting('showman_theater_show_donation',getsetting('showman_theater_show_donation',1000)+getsetting('showman_theater_show_fee',0));
						$Char->gold -= getsetting('showman_theater_show_fee',0);
						redirect($filename.'?op=theshow');
						break;
					}
				case 'spend':
					{
						savesetting('showman_theater_show_donation',getsetting('showman_theater_show_donation',1000)+(int)$_GET['gold']);
						setStatusMessage('Du wirfst '.(int)$_GET['gold'].' Goldstücke in den Spendenhut. Dieser bedankt sich artig...erm wie auch immer.');
						redirect($filename.'?op=theshow');
						break;
					}
			}
			break;
		}
	case 'theater':
		addnav('Zurück');
		addnav('Zurück',$filename);
		addnav('Aktionen');
		page_header('Das Amphitheater');
		$str_output .= get_title('`)D`7a`es `eA`sm`&phit`she`eat`7e`)r`0').'`)A`7n`eg`srenzend an den großen Platz befindet sich ein großes, beinahe kreisrundes Gebäude mit drei Etagen, die schon von Außen durch drei Schichten steinerner Säulenbögen zu erkennen sind: ein Amphitheater. Im Inneren des Theaters sieht man zunächst einmal eine große, runde Freifläche, auf der die Schauspieler auftreten können. Nur an einer Stelle ist der Kreis abgeflacht, denn dort befindet sich die Schauspielergarderobe, die gleichzeitig auch den Ein- und Ausgang in den Bühnenbereich bildet. Um diesen Bereich herum sind die Ränge angeordnet, die stufenförmig nach oben steigen, natürlich gibt es auch diverse Ehrenlogen für besonders bedeutende Zuschauer. `n
Das Theater ist mit raffinierten Techniken ausgestattet, so gibt es zum Beispiel ein Sonnensegel, welches über die Zuschauerränge ausgefahren und zumindest bedingt auch als Regenschutz genutzt werden kann, sowie einen kleinen Kellerraum mit einer Falltür als Verbindung zum Bühnenbereich und einem auf dem Flaschenzugprinzip basierenden Aufzug, mit dem man einen Akteur überraschend auf die Bühne bringen kann. Natürlich sorgt die Bauweise für eine brillante Akustik, auch ein Flüstern kann in den obersten Rängen noch verstanden wer`ed`7e`)n.`0`n';

		if(getsetting('showman_theater_show_name','') != '')
		{

			$str_output .= '`n<hr />`n`sAn einem Reissbrett erkennst du den Aushang für die nächste Vorstellung.`n`n
			`b'.getsetting('showman_theater_show_name','').'`b`n
			'.getsetting('showman_theater_show_description','').'`n`n
			Für den Eintritt erbitten die geneigten Schaustellersleut einen Obolus von '.getsetting('showman_theater_show_fee','').' Goldstücken.`0';

			if(item_count('tpl_id="theater_ticket" AND content="'.getsetting('showman_theater_show_id','').'" AND owner='.$Char->acctid) > 0)
			{
				$str_output .= '`n`sGlücklicherweise hälst du dein Ticket bereits in den Händen.`0';
				addnav('Das Schauspiel besuchen',$filename.'?op=theshow');
			}
			elseif($Char->gold > getsetting('showman_theater_show_fee',''))
			{
				addnav('Ein Ticket lösen',$filename.'?op=theshow&act=get_ticket');
			}
			else
			{
				$str_output .= '`nZu schade, dass du nicht genug Gold dabei hast, um daran Teil zu haben.';
			}
		}
		$str_output .= viewcommentary('viertel_theater','Hinzufügen',25);

		$arr_aei = user_get_aei('job');
		if($arr_aei['job'] == JOB_SHOWMAN || $Char->isSuperuser())
		{
			addnav('Zum Schaustellereck',$filename.'?op=showmanscorner');
		}

		break;

	case 'pavillon':
		page_header('Der Pavillon');
		$str_output .= get_title('`fD`&er `fP`&avillo`fn`0').'`fE`&in ganzes Stück von der Parkmitte entfernt befindet sich der Pavillon, ein kreisrundes, offenes Gebäude, das Dach wird nur von in regelmäßigen Abständen stehenden, filigranen weißen Säulen gestützt. An zwei gegenüberliegenden Seiten ist der Kreis abgeflacht, einmal nur leicht, um einen Eingangsbereich zu bilden, auf der anderen Seite ist er durch eine größere quadratische Fläche erweitert, so dass sich dort eine Nische befindet, die mit einer Theke ausgestattet wurde, an der Speisen und Getränke serviert werden können.`n
Der Boden ist mit hellem Parkett ausgekleidet, und könnte somit bei Bedarf als Tanzfläche dienen. Für gewöhnlich stehen über den Raum verteilt einige kleine Stehtische, die jedoch alle frei beweglich sind. In der warmen Jahreszeit bietet der Pavillon die ideale Umgebung für kleine oder auch größere Feiern der verschiedensten Anlässe.`n
Ein paar Schritte abseits befindet sich noch ein weiterer, ähnlich gearbeiteter Pavillon, dieser allerdings perfekt kreisrund und viel kleiner als sein Gegenstück, vermutlich gedacht für diejenigen, die – aus welchem Anlass auch immer – ein wenig Abstand von der Festgesellschaft suche`fn.`0`n';

		$str_output .= viewcommentary('viertel_pavillon','Hinzufügen',25);
		addnav('P?Zum Park',$filename.'?op=park');
		addnav('Zurück',$filename);
		break;

	case 'tanz':
		page_header('Der Ballsaal');
		$str_output .= get_title('`lD`Xe`Rr `rB`&all`rs`Ra`Xa`ll`0').'`lD`Xe`Rr `rB`&allsaal besteht aus einem einzigen großen, rechteckig angelegten Raum, dessen Eingang sich an einer der kurzen Seiten befindet, gegenüber der halbrunden Bühne, die für talentierte Musiker reserviert ist. Entlang der Wände stehen kleine Tische mit jeweils zwei Stühlen zum Ausruhen für die Tänzer bereit und Bedienstete sorgen für Erfrischungen. Der größte Teil des mit teurem Holz ausgelegten Saales ist jedoch frei gehalten um es möglichst vielen Personen gleichzeitig zu gestatten zu tanzen, ohne in der Bewegungsfreiheit eingeschränkt zu sein.`n
    Passend zum Anlass ist der Ballsaal festlich geschmückt, auf den Tischen stehen kunstvolle Kerzen, die Bühne ist mit farbigen Stoffen ausgelegt und von den Wänden hängen kunstvolle Dekorationen, die den großen Kristallkronleuchter erblassen la`rs`Rs`Xe`ln.`0`n';

		$str_output .= viewcommentary('viertel_tanz','Hinzufügen',25);
		addnav('Zurück',$filename);
		break;

	case 'park':
		page_header('Der Park');
		$str_output .= get_title('`2D`Ge`gr P`Ga`jr`Jk`0').'`JA`2u`Gf `gder Ostseite geht der große Brunnenplatz beinahe nahtlos in eine weitläufige Rasenfläche über. Das Gras ist akkurat kurz geschnitten, hier und dort wechseln sich quadratische, kreisrunde oder auch sternförmige Blumenbeete in den verschiedensten Farbtönen mit dem satten Grün des Rasens ab. Hell gepflasterte Wege, die jeweils von Bäumen oder Büschen gesäumt werden, führen aus allen Richtungen auf die Mitte des Parkes zu, die durch einen achteckigen, ebenfalls gepflasterten Platz gekennzeichnet ist. An jeder zweiten der acht Seiten steht eine breite, marmorne Bank, auf der man sich niederlassen kann, die übrigen Seiten schmückt jeweils eine kunstvolle Baumskulptur. In der Mitte dieses Platzes wiederum befindet sich ein kreisförmiges Blumenbeet in perfekt aufeinander abgestimmten Gelb- und Rottönen.`n
    Am anderen Ende des Parkes wird man noch eine kleine Überraschung finden: ein Labyrinth aus hochgewachsenen Hecken, in dem Abenteuerlustige versuchen können, den Weg zur Mitte und anschließend wieder nach draußen zu fin`Gd`2e`Jn.`0`n';

		$str_output .= viewcommentary('viertel_park','Hinzufügen',25);
		addnav('P?Zum Pavillon',$filename.'?op=pavillon');
		addnav('Z?Zurück',$filename);
		break;

  case 'palace':
	page_header('Das Schloss');
	$str_output .= get_title('`&D`yas Sch`/lo`ys`&s`0').'`&D`yi`/e Zinnen des Schlosses strecken sich weit dem Himmel entgegen und ein geschultes Auge wird sofort erkennen, das es sich um einen Prachtbau handelt, denn es sind kaum Wehranlagen vorhanden und nur vereinzelt patrouillieren hier Gardisten.`n
Weitläufige Gänge und Gemächer befinden sich hinter der Fassade aus Granit, welches im Sonnenlicht golden schimmert. Edle Hölzer und Stoffe wurden üppig bei der Inneneinrichtung verwendet, sodass sich die hohen Lords und Ladys, welche hier ein und ausgehen, wohl fühlen. Für deren Bedürfnisse ist zudem eine vermeintlich unsichtbare Schar von Bediensteten beschäftigt, die die Dienstboteneingänge oftmals eilenden Schrittes verlassen oder betre`/t`ye`&n.`0`n';

	$str_output .= viewcommentary('viertel_palace','Hinzufügen',25);
	addnav('Z?Zurück',$filename);
	break;

	default:
		page_header('Vergnügungsviertel');
		$str_output .= get_title('`zV`_er`egn`sügungsvi`eer`_te`zl`0').'`zI`_n `ee`siniger Entfernung vom belebten Stadtzentrum befindet sich ein weiterer, großer Platz, quadratisch angelegt und mit hellen Steinen gepflastert. Die Geräusche der Stadt sind nur noch sehr leise zu hören, hier geht es eher ruhig zu. In der Mitte des Platzes steht ein Brunnen, jedoch kein schlichter Trinkbrunnen, sondern ein regelrechtes Kunstwerk, in der Mitte der kreisrunden Wasserfläche befindet sich eine Marmorskulptur, die zwei im fröhlichen Spiel verschlungene Delfine darstellt. Kreisförmig um den Brunnen angeordnet sind einige Bänke, auf denen man sich niederlassen und das Plätschern des Wassers beobachten kann.`n`nZwei Seiten des Platzes werden von Gebäuden gesäumt, und natürlich gibt es auch hier Wege, die in verschiedene Richtungen führen. Zudem beginnt angrenzend ein Park, eine große Grünfläche mit in ordentlichen Reihen angelegten Bäumen und Büschen; der Natur hat man hier augenscheinlich nur wenig freien Lauf gelassen, hat mehr Wert auf Ordnung gel`ee`_g`zt.`0`n';

		$str_output .= viewcommentary('viertel_platz','Hinzufügen',25);

		addnav('o?Wohnviertel','houses.php');
		addnav('d?Stadtzentrum','village.php');
		addnav('M?Marktplatz','market.php');
		addnav('Vergnügungsviertel');
		addnav('C?Caesars Badehaus', 'badehaus.php');
		addnav('T?Theater',$filename.'?op=theater');
		addnav('B?Ballsaal',$filename.'?op=tanz');
		addnav('P?Pavillon',$filename.'?op=pavillon');
		addnav('Park',$filename.'?op=park');
		addnav('S?Das Schloss',$filename.'?op=palace');
		break;
}
output($str_output);
page_footer();

?>