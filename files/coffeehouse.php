<?php
/***************************************************************
* Coffeehouse by Salator, inspired by Tweety's und Angel's Bäckerei
* EMail: salator@gmx.de
* lotgd-Version dragonslayer V2.5
*
* Background-music with Mozartwürfel:
* Author: Axel Berndt
* EMail: AxelBerndt@gmx.net
* Pianist: Prof. Niels Knolle
* originally at http://www.zib.de/vgp/unheard/materialien/externeApplets/mozart/mozartwuerfel.html
***************************************************************/

//Einstellungsteil
define('OPENTIME','00:00'); //Startzeit  des Bäckers
define('CLOSETIME','23:59'); //Schliesszeit des Bäckers
//Preise: Name, Preis, Beschreibung, beim Bäcker erhältlich?(1)
$products=array(
'milk' => array('Milch',50,'Es gibt nichts gesünderes als Milch.',1),
'coffee' => array('Kaffee',300,'Ein Aufguß aus türkischen Kaffeebohnen, welche vom Flammenstoß des grünen Drachen geröstet wurden.',0),
'longcoffee' => array('Verlängerter',300,'Dieser Kaffee wird mit der doppelten Menge Wasser zubereitet und ist nicht so stark.',0),
'milkcoffee' => array('Kaffee verkehrt',300,'Ein Pott Milch mit etwas Kaffee.',0),
'pretzel' => array('Brezel',100,'Eine Spezialität des Hauses, mit Käse überbacken.',1),
'buchtel' => array('Buchteln',100,'Eine böhmische Spezialität, Hefeteig gefüllt mit Pflaumenmus.',1),
'bap' => array('Semmel',150,'Ein kleines Weizengebäck, frisch und lecker.',1),
'bread' => array('Brot',600,'Ein ganzes Dreipfundbrot. Das ist nahrhaft!',1),
'candies' => array('Süßigkeiten',200,'Verlockende Mischung aus allem was dick macht.',1),
'cake' => array('Kuchen',1500,'Früchte zwischen Teig und Schlag, was kann es schöneres geben?',1),
'bigcake' => array('Riesentorte',4000,'Eine dreistöckige Torte mit einer kleinen Figur drauf. Ideal für Feierlichkeiten. Verschiedene Figuren für verschiedene Anlässe.',1),
'gemcake' => array('Gâteau de émeraude',20000,'Diesen Kuchen können sich wirklich nur edle Leute leisten! Mit erlesenen Zutaten aus dem Orient und verziert mit echten Smaragden.',0)
);
$xmas=array(
'gingerbread' => array('Lebkuchen',300,'Ein Lebkuchen mit gebrannten Mandeln drauf.',1),
'stollen' => array('Weihnachtsstollen',1200,'Ein Rosinenstollen mit Staubzucker bestreut.',1)
);
$lib_category=3; //Kategorie-Nummer der Datenbanktabelle library, aus welcher Bücher im Geschichtenteil des Dorfboten angezeigt werden sollen

//Code-Teil
require_once "common.php";
require_once(LIB_PATH.'board.lib.php');

function getcomment($acctid=1)
{
	$sql='SELECT postdate FROM commentary WHERE section=\'salon\' AND author='.$acctid.' ORDER BY commentid DESC LIMIT 1';
	$result = db_query($sql); 
	if(db_num_rows($result))
	{
		$row = db_fetch_assoc(db_query($sql));
		if (strtotime($row['postdate'])>=strtotime('-1 hours'))
		{
			return true;
		}
	}
	return false;
}

checkday();
page_header("Patisserie");
$indate = getsetting('gamedate','0005-01-01');
$date = explode('-',$indate);
$monat = $date[1];
$tag = $date[2];
if ($monat==12) 
{
	$products=array_merge($products,$xmas);
}
$time = date('H:i',convertgametime(strtotime(date('r'))));
$bakeroptions = utf8_unserialize($session['user']['specialmisc']);

if (!is_array($bakeroptions)||$bakeroptions['bakerinit'] != 1)
{
	$bakeroptions = array();
	$bakeroptions['bakerinit'] = 1;
	$bakeroptions['mint'] = 0;
}

if (($time >= OPENTIME && $time <= CLOSETIME) || $_GET['op'])
{
	// geöffnet
	if ($_GET['op']=='') //eintreten
	{
		output('`c`b`lP`La`Xtisse`Xr`Li`le `l"`LZ`Xum `xsüßen `XE`Li`l"`0`b`c`n`lD`Lu `Xbetrittst die Patisserie und erkennst auf der Theke eine reiche Auswahl an Gaumenfreuden. Neben Brot, Semmeln und Brezeln kannst du hier auch süße Sachen wie Kuchen und Torten erwerben.`nIm angrenzenden Salon sitzen die Mitglieder der feinen Gesellschaft und lassen sich mit besonders erlesenen Köstlichkeiten verwöhnen.`n`n');
		if($session['user']['exchangequest']==14 && $monat==9 && $tag>24)
		{
			output('`%Hinten in der Backstube hörst du ein lautes Fluchen.`@`n`n');
			addnav('`%In die Backstube gehen`0','exchangequest.php');
		}
		board_view('inn" OR b.section="immo" OR b.section="sell',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,
		'Am schwarzen Brett neben der Tür hängen einige Nachrichten, die bei Cedrik in der Schenke aufgegeben wurd`Le`ln:',
		'Am schwarzen Brett neben der Tür ist nicht eine einzige Nachricht zu seh`Le`ln.',
		true,false,false,true,10);
		
		addnav('Backwaren','coffeehouse.php?op=baker');
		addnav('S?In den Salon','coffeehouse.php?op=salon');
	}

	elseif ($_GET['op']=='baker' || $_GET['op']=='waiter') //Backwaren kaufen
	{
		if ($session['user']['gold']<$products[$_GET['what']][1])
		{
			output('So gern du auch '.$products[$_GET['what']][0].' hättest, `4du hast nicht genug Gold!`n`n');
		}
		else
		{
			$bakeroptions[$_GET['what']]+=1;
			switch ($_GET['what'])
			{

				case 'pretzel':
				{
					if($_GET['op']=='waiter' || $bakeroptions['pretzel']<2)
					{
						output('`lSo eine Brezel gehört zum Kaffee einfach dazu. Und sie schmeckt wirklich vorzüglich. Zu gern würdest du gleich noch eine bestellen, aber du möchtest den anderen Anwesenden keinen Stoff für Klatsch bieten und reißt dich zusammen.');
					}
					else
					{
						output('`lDu kostest eine Brezel und bist begeistert so gut wie die war!`nDu stützt dich auf den ganzen Haufen und isst alle weg. Die Leute hinter dir fangen an zu murren, Worte wie "`6Vielfraß`l" sind zu hören. Da hast du dich wohl gerade unbeliebt gemacht.');
						$session['user']['charm']=max(0,$session['user']['charm']-1);
					}
					$session['user']['gold']-=$products[$_GET['what']][1];
					$bakeroptions['mint']++;
					break;
				}

				case 'buchtel':
				{
					if($_GET['op']=='waiter')
					{
						output('`lDu bestellst eine ganze Schüssel Buchteln, weißt du doch, wie gut die schmecken. Genüßlich isst du eine nach der anderen auf. Mmmh!');
					}
					else
					{
						output('`lDu kaufst eine Tüte Buchteln, kostest eine davon und bist begeistert wie gut die schmeckt!`nDu kannst nicht anders und isst sie auf dem Heimweg alle auf. Und was wirst du jetzt zu Hause erzählen?');
					}
					$session['user']['gold']-=$products[$_GET['what']][1];
					$bakeroptions['mint']++;
					break;
				}

				case 'bap':
				{
					if($bakeroptions['bap']>7)
					{
						output('`lDu bestellst deine '.$bakeroptions['bap'].'. Semmel. Das bemerkt auch der Bäcker in seiner Backstube und er freut sich dass es dir schmeckt.');
					}
					else
					{
						output('`lSo eine frische Semmel ist was feines! Du isst sie gleich an Ort und Stelle und fühlst dich etwas gestärkt.');
						$session['user']['hitpoints'] = min($session['user']['hitpoints']*1.02,$session['user']['maxhitpoints']*1.15);
					}
					$session['user']['gold']-=$products[$_GET['what']][1];
					$bakeroptions['mint']++;
					break;
				}

				case 'bread':
				{
					output('Dein Magen knurrt unüberhörbar, also bestellst du dir ein Brot. Als du es bis auf den letzten Krümel aufgegessen hast fühlst du dich gleich viel besser. So ein Brot ist wirklich nahrhaft...');
					if($bakeroptions['bread']>3)
					{
						output('`nEinige der Anwesenden beginnen Wetten abzuschließen ob du auch noch ein '.($bakeroptions['bread']+1).'. Brot in dich reinstopfen kannst.');
					}
					$healing=array(0,4000,1731,1092,865,745,670,617,577,546,521,500,482,467,455,443);
					$session['user']['hitpoints'] = min($session['user']['hitpoints']+$healing[$session['user']['level']],$session['user']['maxhitpoints']*1.05);
					$session['user']['gold']-=$products[$_GET['what']][1];
					$bakeroptions['mint']+=2;
					break;
				}

				case 'cake':
				{
					output('`lDu bestellst dir ein Stück Zwetschgenkuchen. Hmm, schmeckt der gut!`n');
					$session['user']['hitpoints'] = min($session['user']['hitpoints']*1.01,$session['user']['maxhitpoints']);
					$session['user']['gold']-=$products[$_GET['what']][1];
					$bakeroptions['mint']++;
					if(e_rand(1,20)==10)
					{
						output('Du beißt in den Kuchen und verspürst einen Schmerz weil du auf was hartes gebissen hast. Ein Smaragd?! Du lässt dir nichts anmerken und steckst den Edelstein ein.');
						$session['user']['gems']++;
					}
					break;
				}

				case 'bigcake':
				{
					output('`lDu bestellst dir eine Festtagstorte! Die anderen Anwesenden beginnen zu spekulieren was es bei dir zu feiern gibt. ');
					$session['user']['gold']-=$products[$_GET['what']][1];
					$bakeroptions['mint']+=3;
					if($_GET['op']=='waiter')
					{
						insertcommentary(1,'/msg Eine riesige Torte wird hereingefahren und an den Tisch von '.$session['user']['login'].' gebracht.','salon');
						output('Du bemerkst, daß du die Torte niemals alleine aufessen kannst und lädst alle ein mit dir zu feiern.`n');
						if($session['user']['reputation']<50)
						{
							$session['user']['reputation']++;
						}
						else
						{
							$session['user']['charm']++;
						}
					}
					else
					{
						output('Du gibst jedoch nicht viel auf das Geschwätz der Leute und nimmst die Torte mit nach hause.`n');
					}
					break;
				}

				case 'gemcake':
				{
					output('`lDer Kellner bringt dir eine prachtvolle Torte, welche mit Smaragden verziert ist.`nDu überlegst ob du es doch lassen solltest da reinzubeissen, jedoch kannst du deiner Schwäche nicht wiederstehen!`nDie Edelsteine steckst du natürlich ein.`n');
					$session['user']['gems']+=3;
					$session['user']['gold']-=$products[$_GET['what']][1];
					break;
				}

				case 'candies':
				{
					output('`lDu bestellst dir eine Schale mit Süßigkeiten.`n');
					if(e_rand(1,5)==3) 
					{
						output('Da bemerkst du ein verführerisches Lächeln '.($session['user']['sex']?'eines jungen Mannes':'einer jungen Frau').' und beschließt spontan, deine Leckereien zu teilen.');
						$session['user']['charm']++;
						$session['user']['turns']=max(0,$session['user']['turns']-1);
					}
					else
					{
						output('Eigentlich weißt du ja daß süße Sachen nicht gesund sind, gibst aber deiner Schwäche nach.');
						$session['user']['hitpoints']=max(1,$session['user']['hitpoints']-1);
					}
					$session['user']['gold']-=$products[$_GET['what']][1];
					break;
				}

				case 'milk':
				{
					output('`lDu trinkst einen Schluck `&Milch`l und fühlst dich gleich viel besser`n');
					$session['user']['drunkenness'] *=0.7;
					$session['user']['gold']-=$products[$_GET['what']][1];
					break;
				}

				case 'coffee':
				case 'longcoffee':
				{
					output('`lDu bestellst dir einen '.($_GET['what']=='longcoffee'?'"verlängerten"':'').' Kaffee. Wenig später bringt ihn der Kellner an deinen Tisch, `Tschwarz`l und heiß, dazu ein Kännchen Sahne, Zucker und das obligatorische Glas Wasser.`n');
					$session['user']['gold']-=$products[$_GET['what']][1];
					if(($bakeroptions['coffee']+$bakeroptions['longcoffee']+$bakeroptions['milkcoffee'])>2) user_set_aei(array('usedouthouse'=>0));
					break;
				}

				case 'milkcoffee':
				{
					output('`lWeil du Kaffee schlecht verträgst, aber dennoch nicht auf den Genuss verzichten möchtest, bestellst du dir einen Milchkaffee. Wenig später bringt ihn der Kellner an deinen Tisch, `8hellgelb`l und heiß, dazu Zucker und das obligatorische Glas Wasser.`n');
					$session['user']['gold']-=$products[$_GET['what']][1];
					if(($bakeroptions['coffee']+$bakeroptions['longcoffee']+$bakeroptions['milkcoffee'])>2) user_set_aei(array('usedouthouse'=>0));
					break;
				}

				case 'stollen':
					$bakeroptions['mint']++;
                    break;

				case 'gingerbread':
				{
					output('`lDu bestellst dir einen '.$products[$_GET['what']][0].'. Als du hineinbeißt du denkst du daran daß sich schonwieder ein Jahr dem Ende neigt. Wie schnell das doch ging...`n`n`c<img src="./images/candle.gif">`c');
					$session['user']['gold']-=$products[$_GET['what']][1];
					$bakeroptions['mint']++;
					break;
				}

				default:
				{
					$out.='`^Preisliste`0`n`n`c<table cellpadding="2" cellspacing="1" width="80%" bgcolor="#999999"><tr class="trhead"><th>Artikel</th><th width="60">Preis</th>';
					$count=1;
					while (list($key, $val) = each($products))
					if(($_GET['op']=='baker' && $val[3]==1) || ($_GET['op']=='waiter'))
					{
						$out.='<tr class="'.($count%2?"trlight":"trdark").'"><td>`9<a href="coffeehouse.php?op='.$_GET['op'].'&what='.$key.'">'.$val[0].'</a>`0</td><td align="right">`9'.$val[1].'`0</td></tr><tr class="'.($count%2?"trlight":"trdark").'"><td colspan="2">`7'.$val[2].'`0</td></tr>';
						$count++;
						addnav('','coffeehouse.php?op='.$_GET['op'].'&what='.$key);
						addnav($val[0],'coffeehouse.php?op='.$_GET['op'].'&what='.$key);
					}
					$out.='</table>';
					output($out,true);
				}
			} //end switch what

			if($_GET['op']=='waiter')
			{
				$session['user']['gold']-=$products[$_GET['what']][1]*0.1; //Trinkgeld
				if ($session['user']['gold']<0)
				{
					output('`nDu möchtest dem Kellner ein angemessenes Trinkgeld geben, stellst aber fest daß du nicht genug dabei hast. Wie peinlich...');
					$session['user']['gold']=0;
					$session['user']['charm']=max(0,$session['user']['charm']-1);
				}
			}
		}

		addnav(':');
		if($_GET['what']>'')
		{
			addnav('u?Weiter umsehen','coffeehouse.php?op='.$_GET['op']);
		}
		if($_GET['op']=='waiter')
		{
			addnav('S?Zum Salon','coffeehouse.php?op=salon');
		}
		else
		{
			addnav('B?Zur Backstube','coffeehouse.php');
		}
	}

	elseif ($_GET['op']=='mute') //Musik im Salon stummschalten
	{
		$bakeroptions['mute']=1;
		output('`lHeimlich schleichst du dich an den Pianist heran und `bhastdunichtgesehen`b sind seine Hände zusammengebunden. Endlich Ruhe!`nDu machst den Knoten nicht zu fest, aber solange du im Salon sitzt wird er schon halten...');
		addnav('S?Zurück zum Salon','coffeehouse.php?op=salon');
	}

	elseif ($_GET['op']=='salon') //in den Salon gehen
	{
		if($session['user']['gems']>5 || getcomment($session['user']['acctid'])==true)
		//du siehst wohlhabend aus und darfst rein
		{
			output('`lE`Li`Xn Lakai in hübsch bestickter Uniform öffnet dir mit einem ehrerbietigen Lächeln die Tür. Staunend betrittst du den prächtigen Salon. Hier wurde wirklich an keiner Ecke gespart, der Boden sowie die Tische sind aus edlem Marmor, die Stühle haben Polster aus edlem, purpurfarbenen Samt und von der Decke hängt ein prächtiger Kronleuchter mit glitzernden Kristallen. Eifrige Kellner erkundigen sich stets nach dem Wohlbefinden ihrer Gäste und scheinen ihnen ihre Wünsche beinahe von den Augen abzulesen. Das reichhaltige Sortiment an Köstlichkeiten macht es sehr schwer, eine Auswahl zu treffen, aber ganz gleich, was man hier bestellt, man wird nicht enttäuscht. An den Tischen liegt zudem die `iStadtpost`i zum Schmökern bere`Li`lt.');
			if(Weather::is_weather(Weather::WEATHER_HOT | Weather::WEATHER_MUGGINESS | Weather::WEATHER_WARM ))
			{
				$garden=true;
				output('`n`XBei solch schönem Wetter besteht für die Besucher die Möglichkeit, auf der Terrasse der Patisserie Platz zu nehmen.');
			}
			if($time>'17:00')
			{
				output('`n`XDer bekannte Pianist `3Niels Knolle`X sitzt an einem noblen Flügel der Marke `a`iA.Berndt`i`X und unterhält die Gäste mit beschwingten Klängen.');
			}
			output('`n`n');
			addcommentary();
			viewcommentary('salon','Erzählen',30,'erzählt',false,true,false,true);
			addnav('Bestellen','coffeehouse.php?op=waiter');
			addnav('Stadtpost lesen','coffeehouse.php?op=news');
		}
		else
		{
			output('`lDu begibtst dich in Richtung Salon. Doch bevor du eintreten kannst wirst du von einem Lakai aufgehalten. `^"Tut mir leid, '.($session['user']['sex']?'meine Dame':'mein Herr').', doch der Salon ist leider überfüllt. Ich muß Euch bitten, ein andermal wiederzukommen."`l`nVerdammt, hättest du mal auf dein Gefühl gehört und einen Tisch reserviert. Oder siehst du vielleicht nur zu ärmlich aus und wurdest deswegen abgewiesen?`n');
			addnav('B?zum Bäcker','coffeehouse.php?op=baker');
		}
	}

	elseif ($_GET['op']=='news') //Dorfzeitung lesen
	{
		switch($_GET['what'])
		{
			case 'dragon':
			{
				$out='`2`c`bD`ji`Ge `gDrachenp`gl`Ga`jg`2e`b`c`n`2A`ju`Gc`gh heute ist es wieder zahlreichen heldenhaften Einwohnern gelungen die Drachenplage einzudämmen. Wir ehren heute';
				$sql='SELECT newstext FROM news WHERE newstext LIKE "%ten erfolgreichen Kampf%"  AND accountid NOT IN ('.CIgnore::ignore_sql(CIgnore::IGNO_BIO).') order by newsid DESC LIMIT 15';
				$result=db_query($sql);
				for ($i=0;$i<db_num_rows($result);$i++){
					$row = db_fetch_assoc($result);
					$newstext=str_replace(' hat sich','|',$row['newstext']);
					$name=mb_substr($row['newstext'],0,mb_strpos($newstext,'|'));
					$out.=' '.$name.',';
				}
				output($out.'`g stellvertretend für viele weitere Hel`Gd`je`2n.');
				break;
			}

			case 'sport':
			{
				output('`I`c`bN`teu`yes vom Spo`tch`It`b`c`n`yDie ruhmreichen Arenakämpfer');
				$sql='SELECT newstext FROM news WHERE newstext LIKE "%Arena`6!"  AND accountid NOT IN ('.CIgnore::ignore_sql(CIgnore::IGNO_BIO).') order by newsid DESC LIMIT 10';
				$result=db_query($sql);
				if (db_num_rows($result)==0)
				{
					output('`n`7Die Arena ist zur Zeit wegen Bauarbeiten geschlossen.');
				}
				else
				{
					for ($i=0;$i<db_num_rows($result);$i++){
						$row = db_fetch_assoc($result);
						output('`n`c`^- ~ -`0`c'.$row['newstext']);
					}
				}
				output('`n`n`tDie Meisterkämpfe');
				$sql='SELECT newstext FROM news WHERE newstext LIKE "% Meister %"  AND accountid NOT IN ('.CIgnore::ignore_sql(CIgnore::IGNO_BIO).') order by newsid DESC LIMIT 10';
				$result=db_query($sql);
				for ($i=0;$i<db_num_rows($result);$i++){
					$row = db_fetch_assoc($result);
					output('`n`c`^- ~ -`0`c'.mb_substr($row['newstext'],0,mb_strpos($row['newstext'],'!')+1));
				}
				break;
			}

			case 'tales': //eine zufällige Geschichte aus der Bücherei
			{
				$sql='SELECT * FROM lib_books where themeid='.$lib_category.' AND activated="1" ORDER BY RAND() LIMIT 1';
				$result=(db_query($sql));
				if(db_num_rows($result)>0)
				{
					$row=db_fetch_assoc($result);
				}
				else
				{
					$row['title']='Ein Wintermärchen';
					$row['author']='Graf Heinrich';
					$row['book']='Drüben hinterm Dorfe steht ein Leiermann`nund mit starren Fingern dreht er, was er kann.`nBarfuss auf dem Eise wankt er hin und her`nund sein kleiner Teller bleibt ihm immer leer.`n`nKeiner will ihn hören, keiner sieht ihn an`nund die Hunde knurren um den alten Mann.`nUnd er lässt es gehen, alles wie es will,`ndreht, und seine Leier steht ihm nimmer still.`n`nWunderlicher Alter soll ich mit dir geh\'n?`nWillst zu meinen Liedern deine Leier dreh\'n?';
				}
					output('`c<table width=350 border=0><tr><td><center>`2<u>'.$row['title'].'</u></center><div align=right>von '.$row['author'].'</div>`n`0'.nl2br($row['book']).'</td></tr></table>`c');
				break;
			}

			case 'announces':
			{
				board_view('inn',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,'`2Sonstige Kleinanzeigen, welche auch in der Schenke aushängen`0','Heute keine Angebote',true,true,true);
				break;
			}

			case 'immo':
			{
				board_view('immo',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,'`2Schlüsseltausch, Umbauten, Häuser`0','Heute keine Angebote',true,true,true);
				break;
			}
			case 'sell':
			{
				board_view('sell',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,'`2Warenangebote und -gesuche, welche auch in der Schenke aushängen`0','Heute keine Angebote',true,true,true);
				break;
			}

			case 'boarddummy':
			{
				if($_GET['board_action'] == "add") {
					board_add('inn',1,1);
					redirect("coffeehouse.php?op=news&what=announces");
				}
				else {
					board_view_form('Hinzufügen','');
					board_view('inn',1,'Kleinanzeigen','',true,true,true);
				}
				break;
			}

		}
		addnav('Drachen','coffeehouse.php?op=news&what=dragon');
		addnav('Turniere','coffeehouse.php?op=news&what=sport');
		addnav('Kultur','coffeehouse.php?op=news&what=tales');
		addnav('Kleinanzeigen');
		addnav('Wohnen und Bauen','coffeehouse.php?op=news&what=immo');
		addnav('An- und Verkauf','coffeehouse.php?op=news&what=sell');
		addnav('Vermischtes','coffeehouse.php?op=news&what=announces');
		addnav(':');
		addnav('zum Salon','coffeehouse.php?op=salon');
	}

	elseif ($_GET['op']=='mint') //hommage an Monty Python
	{
		output('`lA`Ll`Xs du den Laden verlassen willst kommt der `&Bäckermeister`X auf dich zu.`n`&'.($session['user']['sex']?'Meine Dame':'Mein Herr').', Ihr habt so reichlich bei uns eingekauft. So nehmt dieses Mintplätzchen als Zeichen unserer Dankbarkeit!`n`2');
		if($time>'20:00')
		{
			output('`XErfreut greifst du zu und kostest das Mintplätzchen. Gerade als du den Bäcker ob der Vorzüglichkeit seiner Arbeit loben willst beginnt es in deinem Magen zu rumoren.`n`n`LWar es Gift? Nein, es war einfach nur `l`bzu viel`b. `lMit einem lauten Knall platzt du!');
			//Knappe muß aufwischen und verweigert seine Dienste
			$sql = "SELECT name,state FROM disciples WHERE state>0 AND master=".$session['user']['acctid']."";
			$result = db_query($sql);
			if (db_num_rows($result)>0)
			{
				$rowk = db_fetch_assoc($result);
				output('`X`n`nWer wird denn nun die Schweinerei wegmachen? Der Blick des Ladenbesitzers fällt auf deinen Knappen, und schon ist ein armes Opfer gefunden. Das gefällt `4'.$rowk['name'].'`X natürlich überhaupt nicht, er wird morgen nicht auf dich warten wenn du von Ramius zurückkehrst.');
			}
			killplayer(0,5,1,'shades.php','Mit Ramius weiteressen');
			addnews($session['user']['name'].'`t hat zuviel gegessen und ist geplatzt!');
		}
		else
		{
			output('Dankend lehnst du ab, es habe keinen Stil ein solches Plätzchen vor 8 zu essen.');
			addnav('M?Zurück zum Markt','market.php');
		}
	}

	else //Fehler
	{
		output('Verwundert stellst du fest dass es hier gar kein '.$_GET['op'].' gibt.');
	}
}

else
{
	output('`4Die Bäckerei hat um diese Zeit geschlossen.`n`&An der Tür sieht du ein Schild:`n`n`c`lGeöffnet täglich von '.OPENTIME.' bis '.CLOSETIME.' Uhr.`c`n');
}

addnav('Ausgang');
if($bakeroptions['mint']>15)
{
	addnav('M?Zurück zum Markt','coffeehouse.php?op=mint');
}
else
{
	addnav('M?Zurück zum Markt','market.php');
}
$session['user']['specialmisc'] = utf8_serialize($bakeroptions);

page_footer();
?>

