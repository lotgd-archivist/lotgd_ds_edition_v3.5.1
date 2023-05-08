<?php

/*
	Ein idyllisches, abgelegenes Tal, ursprünglich "nur" fürs RPG
	Alle festen Chatsections des Tals beginnen mit Nebel
	-> nebeltal, nebelwald, nebelfluss, nebelhaus, nebelberg, nebelhoehle
	Manche der Dinge sollte man vielleicht doch noch deaktivieren...
	Zu meiner Verteidigung: es ist sehr spät nachts. Es war immer spät nachts, ganz sicher!
	Autor: Laulajatar für atrahor.de
*/

define('RANDMUSH','3'); // Wahrscheinlichkeit für Pilznavi, 1:x, mind. 1
define('RANDGEM','200'); // Wahrscheinlichkeit für ES in der Höhle, 1:x, mind. 1
define('RANDBEAR','50'); // Wahrscheinlichkeit, Bär zu treffen, 1:x, mind. 1
define('MAXBOARD','1'); // Maximale "Schnitzereien" am Baum pro Spieler
define('BOARDCOST','5'); // WK Kosten für Schnitzerei am Baum
define('FLOWERAMT','23'); // Anzahl nötiger Blumen, um einen Blumenstrauß zu erhalten
define('FORESTCOLOR','`J'); // Farbe in der die zum Wald gehörigen Texte angezeigt werden
define('RIVERCOLOR','`F'); // Farbe in der die zum Fluss gehörigen Texte angezeigt werden

require_once 'common.php';
require_once(LIB_PATH.'board.lib.php');
checkday();
addcommentary();
//page_header('Das Tal im Nebelgebirge');
switch($_GET['op'])
{

	case 'tal': //Das Tal an sich
	{
		page_header('Das Tal im Nebelgebirge');
		$indate = getsetting('gamedate','0005-01-01');
		$date = explode('-',$indate);
		$monat = $date[1];
		$tag = $date[2];
		switch($monat)
		{
			// Frühling
			case 3:
			case 4:
			case 5:
			{
				output('`c`b`JD`2a`js `@T`Gal im Nebelgeb`@i`jr`2g`Je`0`b`c
				`n`JD`2u `jü`@b`Gerquerst den Pass und als sich die Nebel, die dich umgeben, immer mehr lichten, kannst du ein wunderschönes Tal vor dir erblicken. So weit das Auge reicht erstrecken sich saftig grüne Wiesen, nur unterbrochen von den bunten Farbtupfern blühender Frühlingsblumen. In der Ferne erhebt sich ein düster aussehender, dichter Wald, aus dessen Unterholz ein kristallklarer Bach hervorsprudelt, der sich seinen Weg quer durch das Tal sucht, bis er irgendwo unterirdisch verschwindet. Von allen Seiten ist dieser Ort umgeben von Bergen, auf deren Gipfeln noch immer der Schnee des letzten Winters glitzert.`nWährend du langsam ins Tal hinabsteigst, bemerkst du, dass du nicht der erste bist, der es gefunden hat. Eine gemütlich aussehende Blockhütte steht im Schutze einiger riesiger Tannen und scheint nur auf einen müden Wanderer gewartet zu h`@a`jb`2e`Jn.`n`n`0');
				break;
			} // Ende Frühling

			// Sommer
			case 6:
			case 7:
			case 8:
			{
				output('`c`b`pD`ga`Gs `jT`2al im Nebelgeb`ji`Gr`gg`pe`0`b`c
				`n`pD`gu `Gü`jb`2erquerst den Pass und als sich die Nebel, die dich umgeben, immer mehr lichten, kannst du ein wunderschönes Tal vor dir erblicken. Hell strahlt die Sommersonne vom Himmel und zeigt die leuchtend grünen Wiesen in ihrer ganzen Pracht. In der Ferne erhebt sich ein düster aussehender, dichter Wald, aus dessen Unterholz ein kristallklarer Bach hervorsprudelt, der sich in der Sonne glitzernd seinen Weg quer durch das Tal sucht, bis er irgendwo unterirdisch verschwindet. Von allen Seiten ist dieser Ort umgeben von Bergen, die schon beginnen, ihre langen Schatten zu werfen.`nWährend du langsam ins Tal hinabsteigst, bemerkst du, dass du nicht der erste bist, der es gefunden hat. Eine gemütlich aussehende Blockhütte steht im Schutze einiger riesiger Tannen und scheint nur auf einen müden Wanderer gewartet zu `2h`ja`Gb`ge`pn.`n`n`0');
				break;
			} // Ende Sommer

			// Herbst
			case 9:
			case 10:
			case 11:
			{
				output('`c`b `UD`ua`Is `tT`yal im Nebelgeb`ti`Ir`ug`Ue`0`b`c
				`n`UD`uu `Iü`tb`yerquerst den Pass und als sich die Nebel, die dich umgeben, immer mehr lichten, kannst du ein wunderschönes Tal vor dir erblicken. Das Gras, das sich von deinen Füßen über das ganze Tal erstreckt ist schon ein wenig bräunlich und kündigt von nahen Winter. In der Ferne erhebt sich ein düster aussehender, dichter Wald, der nur von den goldgelben und rostroten Farbtupfern der wenigen Laubbäume unterbrochen wird. Aus dem Unterholz sprudelt ein kristallklarer Bach hervor, der sich seinen Weg quer durch das Tal sucht, bis er irgendwo unterirdisch verschwindet. Von allen Seiten ist dieser Ort umgeben von Bergen, die im Licht der letzten warmen Sonnenstrahlen des Jahres zu glühen scheinen.`nWährend du langsam ins Tal hinabsteigst, bemerkst du, dass du nicht der erste bist, der es gefunden hat. Eine gemütlich aussehende Blockhütte steht im Schutze einiger riesiger Tannen und scheint nur auf einen müden Wanderer gewartet zu h`ta`Ib`ue`Un.`n`n`0');
				if($session['user']['exchangequest']==15 && $monat==10 && $tag<15)
				{
					output('`%Am Wegesrand siehst du einen Wanderer sitzen. Er scheint zu erschöpft als dass er die Hütte aus eigener Kraft erreicht.`0`n`n');
					addnav('a?`%Wanderer ansprechen`0','exchangequest.php');
				}
				break;
			} // Ende Herbst

			// Winter
			case 12:
			case 1:
			case 2:
			{
				output('`c`b`#D`Fa`*s `fT`&al im Nebelgeb`fi`*r`Fg`#e`0`b`c
				`n`#D`Fu `*ü`fb`&erquerst den Pass und als sich die Nebel, die dich umgeben, immer mehr lichten, kannst du ein wunderschönes Tal vor dir erblicken. Der unberührte Schnee knirscht unter deinen Schritten, als du langsam ins Tal hinabsteigst, die winterliche Landschaft bewunderst. In der Ferne erhebt sich ein düster aussehender, dichter Wald, der ebenfalls vom kalten Weiß bedeckt ist. Der fast zugefrorene Bach sticht deutlich aus der Schneedecke hervor, Eiszapfen hängen von den Büschen an seinem Ufer und nur manchmal bricht sich ein Sonnestrahl in noch fließendem Wasser. Von allen Seiten ist dieser Ort umgeben von Bergen auf deren Gipfeln  es ebenso weiß glitzert, dort muss noch viel mehr Schnee liegen als hier.`nWährend du langsam ins Tal hinabsteigst, bemerkst du, dass du nicht der erste bist, der es gefunden hat. Eine gemütlich aussehende Blockhütte steht im Schutze einiger riesiger Tannen und scheint nur auf einen müden, verfrorenen Wanderer gewartet zu h`fa`*b`Fe`#n.`n`n`0');
				break;
			} // Ende Winter

			default:
			{
				output('`9Wie auch immer du das geschafft hast.. aber hier gibt es keinen Monat.');
			}

		} // Ende Monat
		if ($session['user']['drunkenness']>60) output('`tEine kleine Herde `rrosaner `&Elefanten `tstapft in der Ferne vorbei... Denkst du nicht auch, dass du etwas weniger trinken solltest?`n`n');
		viewcommentary('nebeltal','Durch das Tal streifen',30,'sagt',false,true,false,0,false,true,1);
		addnav('Wohin gehen?');
		addnav('W?Zum Wald','nebelgebirge.php?op=wald');
		addnav('F?Zum Fluss','nebelgebirge.php?op=river');
		addnav('H?Zur Hütte','nebelgebirge.php?op=hut');
		addnav('B?Bergsteigen','nebelgebirge.php?op=berg');

		addnav('Zurück');
		addnav('Z?Zurück nach '.getsetting('townname','Atrahor'),'pool.php');
		break;
	} // Ende Eingang Tal

	case 'wald':
	{
		page_header('Der große, finstre Wald');
		if (e_rand(1,RANDBEAR)==1)
		{
			redirect('nebelgebirge.php?op=bear');
		}
		else
		{
			output('`c`b'.FORESTCOLOR.'`JD`2e`jr `Gdunkle W`ja`2l`Jd`0`b`c
			`n'.FORESTCOLOR.'`JJ`2e `jn`Gäher du dem Wald kommst, desto höher scheinen die dunklen Tannen über dir aufzuragen. Schon ein paar Schritte, nachdem du den Waldrand hinter dir gelassen hast, fällt kaum noch ein Sonnenstrahl auf den mit trockenen Nadeln bedeckten Boden und deine gesamte Umgebung ist in ein leicht grünliches Dämmerlicht getaucht. Bei jedem Schritt kannst du es leise unter deinen Füßen rascheln hören und vielleicht ist es auch besser, leise zu sein, denn wer weiß was so fernab jeglicher Siedlung in den Tiefen des Waldes lebt. Hier und da kannst du vereinzelte Pilze im Schutz der Bäume wachsen sehen und irgendwo, in scheinbar weiter Ferne, kannst du den kleinen Fluss noch plätschern hören. Du weißt, dass du nur diesem Geräusch folgen musst, solltest du dich im dichten Gehölz verlaufen.`n
	  Tief im Wald kannst du eine kleine Lichtung finden, beschienen von dämmrigem, durch das Blätterdach sickerndem Licht. Ein großer Baum, der alle anderen des Waldes um ein gutes Stück überragt und etliche Jahre alte sein muss, steht in der Mitte der Licht`ju`2n`Jg.`n`n`0');
			viewcommentary('nebelwald','Mit gesenkter Stimme flüstern',30,'flüstert',false,true,false,0,false,true,1);
			addnav('Was tust du?');
			addnav('B?Den Baum betrachten','nebelgebirge.php?op=board');
			addnav('F?Zum Fluss gehen','nebelgebirge.php?op=river');
			if (e_rand(1,RANDMUSH)==1) addnav('P?Pilze sammeln','nebelgebirge.php?op=mush');
			addnav('T?Zurück ins Tal gehen','nebelgebirge.php?op=tal');
			// if (access_control::is_superuser()) addnav('Den Bär besuchen (SU)','nebelgebirge.php?op=bear');
		} // Ende else
		break;
	} // Ende Wald

	case 'bear':
	{
		page_header('Der große, finstre Wald');
		output('`c`b'.FORESTCOLOR.'Der dunkle Wald`0`b`c
		`n'.FORESTCOLOR.'Kaum hast du den Wald betreten fällt kaum noch ein Sonnenstrahl auf den mit trockenen Nadeln bedeckten Boden und deine gesamte Umgebung ist in ein leicht grünliches Dämmerlicht getaucht. Doch stattdessen hörst du ein leises Knacken und noch ehe du überlegen kannst, ob es nicht besser wäre, diesen Ort wieder zu verlassen, steht mit einem Male ein gigantischer Bär vor dir und funkelt dich bedrohlich an. Ein kurzer Blick auf seine Klauen und Zähne verrät dir, dass es vielleicht nicht ganz so schlau wäre, sich mit ihm anzulegen.`n`n`0');
		addnav('Was tust du?');
		addnav('A?Angreifen!','nebelgebirge.php?op=bearattack');
		addnav('W?Wegrennen!','nebelgebirge.php?op=bearrun');
		addnav('T?Tot stellen!','nebelgebirge.php?op=beardead');
		break;
	}

	case 'bearattack':
	{
		page_header('Der große, finstre Wald');
		output('`c`b'.FORESTCOLOR.'Der dunkle Wald`0`b`c
		`n'.FORESTCOLOR.'Irgendwann hast du einmal gehört, dass man diese Tiere leicht vertreiben könnte, also stürzt du dich mit lautem Kampfgeschrei auf den Bären vor dir.
		`nAls du wieder zu dir kommst, ist von dem Bär nicht mehr die kleinste Spur zu entdecken, doch dafür gibt es keine Stelle an deinem Körper, die dir nicht weh tut. Mühsam kämpfst du dich auf die Beine zurück.`n`n`4Du hast fast alle deine Lebenspunkte verloren!`n`n`0');
		$session['user']['hitpoints']=1;
		addnav('Autsch');
		addnav('Z?Zurück in den Wald','nebelgebirge.php?op=wald');
		break;
	} // Ende Angriff

	case 'bearrun';
	{
		page_header('Der große, finstre Wald');
		output('`c`b'.FORESTCOLOR.'Der dunkle Wald`0`b`c
		`n'.FORESTCOLOR.'Ohne einen Augenblick zu zögern drehst du dich um und rennst um dein Leben!
		`nIrgendwann bist du dir sicher, den Bären abgehängt zu haben und bleibst vollkommen außer Atem stehen. Scheinbar hast du es geschafft, keine Spur ist mehr von dem Tier zu entdecken. Du wartest, bis du dich wieder ein wenig erholt hast ehe du deinen Weg fortsetzt.`n`n`0');
		if($session['user']['turns']>0)
		{
			output('`4Durch deine Flucht hast du einen Waldkampf verloren.`n`n');
			$session['user']['turns']--;
		}
		addnav('Geschafft');
		addnav('Z?Zurück in den Wald','nebelgebirge.php?op=wald');
		break;
	} // Ende Wegrennen

	case 'beardead':
	{
		page_header('Der große, finstre Wald');
		output('`c`b'.FORESTCOLOR.'Der dunkle Wald`0`b`c
		`n'.FORESTCOLOR.'Theatralisch lässt du dich zu Boden sinken und bleibst reglos liegen, in der Hoffnung, dass der Bär sein Interesse an dir verliert.
		`nDu hörst leise Geräusche, wagst es jedoch nicht, die Augen wieder zu öffnen. Erst als es nach einer Weile noch immer still ist rührst du dich wieder und stellst fest: Du bist alleine. Da hast du ja noch einmal Glück gehabt. Du stehst auf und klopfst dir die Erde ab, ehe du deinen Weg fortsetzt.`n`n`0');
		addnav('Glück gehabt');
		addnav('Z?Zurück in den Wald','nebelgebirge.php?op=wald');
		break;
	} // Ende Tot stellen

	case 'board':
	{
		page_header('Der große Baum');
		output('`c`b'.FORESTCOLOR.'Der große Baum`0`b`c
		`n'.FORESTCOLOR.'Vor dir steht ein gewaltiger Baum, dessen Stamm sicherlich nicht einmal drei Menschen mit den Armen umfassen könnten. Seine Rinde muss steinhart sein, etwas dort hineinzuritzen dürfte eine ganze Weile dauern; `4'.BOARDCOST.' Runden'.FORESTCOLOR.' etwa..`n`n');
		board_view('tal',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,FORESTCOLOR.'Folgendes kannst du auf der Rinde des Baumes erkennen:',FORESTCOLOR.'Niemand scheint etwas in den Stamm des Baumes hineingeritzt zu haben.',true, true, false, true);
		output('`n`n');
		if ($session['user']['turns']>=BOARDCOST)
		{
		board_view_form("Einritzen",FORESTCOLOR.'Auch du kannst hier etwas in den Stamm ritzen.`n');
			if ($_GET['board_action'] == "add")
			{
				if (board_add('tal',14,MAXBOARD) == -1)
				{
					output(FORESTCOLOR.'`nDu hast doch schon etwas hineingeritzt, das sollte wirklich reichen.`n`n');
				}
				else
				{
					$session['user']['turns']-=BOARDCOST;
					redirect("nebelgebirge.php?op=board");
				}
			}
		}
		addnav('Z?Zurück in den Wald','nebelgebirge.php?op=wald');
		break;
	}   // Ende Board

	case 'mush':
		{
            page_header('Das Tal im Nebelgebirge');

			output('`c`b'.FORESTCOLOR.'Pilze sammeln`0`b`c
			`n'.FORESTCOLOR.'Du zückst dein Messer und suchst dir den schönsten Pilz aus, den du entdecken kannst. Voller Vorfreude beißt du hinein...`n');
			addnav('Zurück gehen');
			addnav('Z?Zurück in den Wald','nebelgebirge.php?op=wald');
			$pilz=(e_rand(1,100));
			// Ja, ich bin parteiisch :D Hobbits an die Macht
			// Keine giftigen Pilze für die kleinen Wesen
			// Die besten kommen ganz hinten
			if ($session['user']['race'] == 'hbl')
			{
				$pilz+=10;
			}

			//doofklicken darf keinen Bonus bringen
			$pilz-=(int)$session['daily']['pilz'];
			$session['daily']['pilz']++;
			$debuglog='pflückte Pilz Nr. '.$pilz.' ';

			$pilz=min($pilz,100);
			switch ($pilz)
			{
				case 1:
				{
					output(FORESTCOLOR.'Das war ein giftiger Pilz! Also wirklich, die roten mit den weißen Punkten isst man doch nicht! Haarscharf entrinnst du Ramius\' Fängen, doch wirst du heute nicht mehr kämpfen können...`n`4Du verlierst alle deine restlichen Waldkämpfe!`n`n');
					$session['user']['turns']=0;
					$debuglog.='(WK=0)';
					break;
				}

				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				{
					output(FORESTCOLOR.'Igitt! Das war ein scheußlicher Pilz. Eilig verschwindest du hinter dem nächsten Busch. Ein paar Minuten vergehen, bis du wieder hervorkommst, nicht mehr ganz so grün im Gesicht. Doch wird es wohl noch eine Weile dauern, bis die Übelkeit vollständig verflogen ist.`n`n');
					$session['bufflist']['mushbad'] = array('name'=>'Giftiger Pilz',
					'rounds'=>25,
					'wearoff'=>'Die Übelkeit lässt nach.',
					'defmod'=>0.95,
					'atkmod'=>0.95,
					'roundmsg'=>'Dir ist so schlecht, dass du gar nicht richtig kämpfen kannst.',
					'activate'=>'offense');
					$debuglog.='(Malus-Buff)';
					break;
				}

				case 20:
				case 21:
				case 22:
				case 23:
				case 24:
				{
					output(FORESTCOLOR.'Angewiedert spuckst du das Stück Pilz wieder aus. Das schmeckt ja grauenhaft. Mit der Suche nach etwas, um diesen scheußlichen Geschmack loszuwerden, vergeudest du `4einen Waldkampf'.FORESTCOLOR.'.`n`n');
					$session['user']['turns']=max(0,$session['user']['turns']-1);
					$debuglog.='(-1WK)';
					break;
				}

				case 40:
				case 41:
				case 42:
				case 43:
				case 44:
				case 45:
				case 46:
				case 47:
				{
					output(FORESTCOLOR.'Du beißt in den Pilz und isst ihn bis zum letzten Krümel auf. Nach ein paar Minuten merkst du jedoch, wie dir ein wenig übel wird. `4Du verlierst ein paar Lebenspunkte!`n`n');
					$session['user']['hitpoints']*=0.8;
					$debuglog.='(80% HP)';
					break;
				}

				case 50:
				case 51:
				case 52:
				case 53:
				case 54:
				case 55:
				case 56:
				case 57:
				case 58:
				case 59:
				case 60:
				case 61:
				{
					$hpbonus=ceil($session['user']['hitpoints']*0.1);
					output(FORESTCOLOR.'Du beißt in den Pilz und isst ihn bis zum letzten Krümel auf. Du fühlst dich irgendwie erfrischt, so gut war der. `2Du erhältst '.$hpbonus.' Lebenspunkte dazu!`n`n');
					$session['user']['hitpoints']+=$hpbonus;
					$debuglog.='(+10% HP)';
					if($session['user']['hitpoints']>$session['user']['maxhitpoints']*1.3)
					{
						output(FORESTCOLOR.'Doch lange hält deine Freude nicht an. Hast du schonmal was von "Bullemie" gehört? Genau so geht es dir nämlich jetzt.`n`n');
						$session['user']['hitpoints']*=0.5;
						$debuglog.=' >Maximum';
					}
					break;
				}

				case 80:
				{
					output(FORESTCOLOR.'Als du in den Pilz beißen willst, treffen deine Zähne auf etwas Hartes. Verwundert fischst du einen `#Edelstein'.FORESTCOLOR.' heraus und wirfst den Pilz fort.`n`n');
					$session['user']['gems']++;
					$debuglog.='(1ES)';
					break;
				}

				case 86:
				case 87:
				case 88:
				case 89:
				case 90:
				{
					output(FORESTCOLOR.'Das war vielleicht ein köstlicher Pilz. Du fühlst dich so gut, du könntest heute glatt noch ein Monster mehr erschlagen.`n`n');
					$session['user']['turns']++;
					$debuglog.='(+1WK)';
					break;
				}

				case 98:
				case 99:
				case 100:
				{
					output(FORESTCOLOR.'So einen leckeren Pilz hast du in deinem ganzen Leben noch nicht gegessen! Du fühlst dich einfach großartig und voller Energie.`n`n');
					$session['bufflist']['mushgood'] = array('name'=>'Leckerer Pilz',
					'rounds'=>25,
					'wearoff'=>'Deine Energie lässt langsam wieder nach.',
					'defmod'=>1,
					'atkmod'=>1.1,
					'roundmsg'=>'Du fühlst dich voller Energie.',
					'activate'=>'offense');
					$debuglog.='(Bonus-Buff)';
					break;
				}

				default:
				{
					output(FORESTCOLOR.'Ein wirklich guter Pilz. Erfreut gehst du weiter deines Weges.`n`n');
					$debuglog='';
					break;
				}
			} // Ende switch Pilz
			if($debuglog>'')
			{
				debuglog($debuglog);
			}
			break;
		}   // Ende der Pilze

	case 'river':
	{
		page_header('Der Gebirgsbach');
		output('`c`b'.RIVERCOLOR.'`FD`*e`fr `sGebirgsb`fa`*c`Fh`0`b`c
		`n'.RIVERCOLOR.'`FV`*o`fr `sdir fließt ein kleiner Gebirgsbach durch das friedliche Tal. Sein Wasser ist so klar, dass du ohne Probleme den Grund sehen kannst, die vielen, vom Wasser rundgeschliffenen Kiesel und ab und an einen winzigen Fisch, dem die eisigen Temperaturen nichts auszumachen scheinen. Du könnstest dich an seinem Ufer ins Gras setzen, die vielen bunten Blumen bewundern und den Tag genießen, für ein Bad wäre es wohl zu kalt... oder?
	`nDoch du erinnerst dich auch daran, vor langer Zeit einmal Geschichten gehört zu haben, dass man in solchen Gewässern eine winzige Chance hat, Gold zu finden.`n');
		if($session['user']['turns']>0)
		{
			output('`sDu könntest es einmal versuchen, doch es wird ganz sicher sehr lange dauern und ob du überhaupt etwas finden wirst, das wissen nur die Göt`ft`*e`Fr.`n`n`0');
		}
		else
		{
			output('`sLeider bist du heute schon viel zu müde um diesen Gerüchten auf den Grund zu ge`fh`*e`Fn.`n`n`0');
		}
		viewcommentary('nebelfluss','Etwas sagen',30,'sagt',false,true,false,0,false,true,1);
		addnav('Was willst du tun?');
		addnav('B?Blumen pflücken','nebelgebirge.php?op=flowers');
		if($session['user']['turns']>0)
		{
		addnav('G?Nach Gold suchen','nebelgebirge.php?op=gold');
		}
		addnav('T?Zurück ins Tal gehen','nebelgebirge.php?op=tal');
		break;
	} // Ende Fluss

	case 'flowers':
	{
        page_header('Das Tal im Nebelgebirge');

		if (!$session['flowers']) $session['flowers']=1;

		$flowername=e_rand(1,19);
		switch($flowername)
		{

				case 1:
				case 2:
					{
						$flowerlat='`&Convallaria majalis';
						$flowerger='das `&Maiglöckchen';
						break;
					}

				case 3:
				case 4:
					{
						$flowerlat='`^Caltha palustris';
						$flowerger='die `^Sumpfdotterblume';
						break;
					}

				case 5:
				case 6:
					{
						$flowerlat='`yBellis perennis';
						$flowerger='das `yGänseblümchen';
						break;
					}

				case 7:
				case 8:
					{
						$flowerlat='`9Centaurea cyanus';
						$flowerger='die `9Kornblume';
						break;
					}

				case 9:
				case 10:
					{
						$flowerlat='`qLilium lancifolium';
						$flowerger='die `qTigerlilie';
						break;
					}

				case 11:
				case 12:
					{
						$flowerlat='`fAnemone nemorosa';
						$flowerger='das `fBuschwindröschen';
						break;
					}

				case 13:
				case 14:
					{
						$flowerlat='`VViola tricolor';
						$flowerger='das `Vwilde Stiefmütterchen';
						break;
					}

				case 15:
				case 16:
					{
						$flowerlat='`/Narcissus pseudonarcissus';
						$flowerger='die `/Osterglocke';
						break;
					}

				case 17:
				case 18:
					{
						$flowerlat='`5Lamium maculatum';
						$flowerger='die `5gefleckte Taubnessel';
						break;
					}

				case 19:
					{
						$flowerlat='`2Urtica urens';
						$flowerger='eine `2kleine Brennnessel';
						break;
					}

				default:
					{
						output('Irgendwas stimmt hier nicht...');
						addnav('Zurück','nebelgebirge.php?op=river');
						break;
					}
		} // Ende switch name

		if ($flowername==19)
		{
			output('`c`b'.RIVERCOLOR.'`FB`*l`sumen pflück`*e`Fn`0`b`c
			`n'.RIVERCOLOR.'`FD`*u `sbückst dich nach einem besonders hübschen Exemplar der Gattung '.$flowerlat.RIVERCOLOR.' `sund brichst dieses sorgsam ab. Autsch! Das war '.$flowerger.RIVERCOLOR.'. Vor Schreck lässt du die Hälfte deiner gesammelten Blumen fallen und betrachtest deine Hand, die sich ein wenig rötlich fär`*b`Ft.`0`n`n');
			$session['flowers']=round($session['flowers']/2,0);
			if ($session['user']['hitpoints']>1) $session['user']['hitpoints']--;
			Addnav('Autsch!');
			addnav('B?Mehr Blumen pflücken','nebelgebirge.php?op=flowers');
			addnav('U?Zurück ans Ufer','nebelgebirge.php?op=river');
			addnav('T?Zurück ins Tal gehen','nebelgebirge.php?op=tal');
		}
		else
		{
			output('`c`b'.RIVERCOLOR.'Blumen pflücken`0`b`c
			`n'.RIVERCOLOR.'`FD`*u `sbückst dich nach einem besonders hübschen Exemplar der Gattung '.$flowerlat.RIVERCOLOR.' `sund brichst dieses sorgsam ab. Zufrieden steckst du '.$flowerger.RIVERCOLOR.' zu den anderen Blumen.`n');

			if ($session['flowers']<2)
			{
				output(RIVERCOLOR.'Du hast erst eine einzige Blume gepflüc`*k`Ft.`0`n`n');
			}
			else
			{
				output(RIVERCOLOR.'Du hast jetzt schon '.$session['flowers'].' Blumen gepflüc`*k`Ft.`0`n`n');
			}
			$session['flowers']++;


			if ($session['flowers']>FLOWERAMT)
			{
				output('`gDamit hast du genug Blumen für einen ganzen Blumenstrauß zusammen. Zufrieden erhebst du dich wieder und gehst zurück ans Ufer, während du an den bunten Blüten riechst.`n`n');
				item_add($session['user']['acctid'],'blmstrss');
				debuglog('pflückte einen Blumenstrauss');
				$session['flowers']=1;
				addnav('Schön...');
				addnav('U?Zurück ans Ufer','nebelgebirge.php?op=river');
				addnav('T?Zurück ins Tal gehen','nebelgebirge.php?op=tal');
			}
			else
			{
				addnav('Was tun?');
				addnav('B?Mehr Blumen pflücken','nebelgebirge.php?op=flowers');
				addnav('U?Zurück ans Ufer','nebelgebirge.php?op=river');
				addnav('T?Zurück ins Tal gehen','nebelgebirge.php?op=tal');
			}
		} // Ende Brennnessel-else
		break;
	} // Ende Blumen pflücken

	case 'gold':
	{
		page_header('Der Gebirgsbach');
		output('`c`b'.RIVERCOLOR.'`FG`*o`sldsuc`*h`Fe`0`b`c
		`n'.RIVERCOLOR.'`FD`*e`sine Neugier siegt und du steigst in das eisig kalte Wasser um nach dem kleinsten Anzeichen für Goldvorkommen zu suchen. Das dauert natürlich eine Weile. Du findest ');
		$session['user']['turns']-=1;
		switch (e_rand(1,40))
		{
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			{
				output('`^nicht mehr als ein bisschen Goldstaub'.RIVERCOLOR.'. `sDoch das könnte genausogut auch Einbildung gewesen se`*i`Fn...`n`n`0');
				$session['user']['gold']++;
				break;
			}
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 23:
			case 24:
			case 25:
			{
				output('`^einen kleinen Klumpen Gold'.RIVERCOLOR.'. `sDoch nach der ersten Freude stellst du fest, dass es sich lediglich um Katzengold handelt. Du steckst ihn trotzdem e`*i`Fn.`n`n`0');
				$item=item_get('tpl_id="katzengold" AND owner='.$session['user']['acctid']);
				if($item)
				{
					$item['value1']++;
					$item['name']=$item['value1'].' Katzengold';
					$item['gold']=$item['value1']*75;
					item_set('id='.$item['id'],$item);
				}
				else
				{
					item_add($session['user']['acctid'], 'katzengold', $item);
				}
				break;
			}
			case 26:
			case 27:
			{
				output('`^einen kleinen Klumpen Gold'.RIVERCOLOR.'. `sVoller Freude steckst du deinen Fund e`*i`Fn.`n`n`0');
				item_add($session['user']['acctid'],'goldklmp');
				break;
			}
			default:
			{
				output('`4nichts'.RIVERCOLOR.'. `sWie scha`*d`Fe...`n`n`0');
				break;
			}

		} // Ende Suche
		if($session['user']['turns']>0)
		{
			addnav('Weitersuchen');
			addnav('G?Nach Gold suchen','nebelgebirge.php?op=gold');
		}
		addnav('Ersteinmal ausruhen');
		addnav('U?Zurück ans Ufer','nebelgebirge.php?op=river');
		addnav('T?Zurück ins Tal gehen','nebelgebirge.php?op=tal');

		break;
	} // Ende Gold

	case 'hut':
	{
		page_header('Die Blockhütte');
		addnav('Was tun?');
		if ($session['campfire']=='on')
		{
			$hutcol='q';
		}
		else
		{
			$hutcol=')';
		}
		output('`c`b`'.$hutcol.'`tD`yi`Ye Blockhü`Yt`yt`te`0`b`c
		`n`'.$hutcol.'`tD`yu `Yöffnest die Tür zu der kleinen Hütte und blickst dich im Inneren um, augenscheinlich gibt es nur einen einzigen Raum im Erdgeschoss. Die Einrichtung besteht aus einem großen Sofa, einem Tisch mit mehreren Stühlen sowie einigen Schränken, in denen sich mit Sicherheit Decken und Geschirr finden lassen. ');
		if ($hutcol==')')
		{
			output('`YNeben dem Kamin wartet ein Stapel trockenes Holz nur darauf, dass du ein Feuer entzündest und es dir davor gemütlich machst, während draußen die Nacht hereinbricht.`0` ');
			addnav('Feuer entzünden','nebelgebirge.php?op=huton');
		}
	else
		{
			output('`YIm Kamin brennt ein fröhliches Feuer, dessen warmer Schein den ganzen Raum erhellt. Während draußen die Nacht hereinbricht, kannst du es dir hier gemütlich machen und dem leisen Knacken der Holzscheite und dem Knistern der Flammen lauschen.`0` ');
			addnav('Feuer löschen','nebelgebirge.php?op=hutoff');
		}
		output('`YEine kleine Treppe im hinteren Teil des Zimmers führt nach oben.`nDir ist klar, dass erwartet wird, dass du die Hütte in dem gleichen Zustand verlässt, wie du sie vorgefunden hast, damit sie auch dem nächsten Wanderer wieder Schutz bieten ka`yn`tn.`n`n`0');
		viewcommentary('nebelhaus','Leise sprechen',30,'sagt',false,true,false,0,false,true,1);
		addnav('Wohin gehen?');
		addnav('o?Nach oben','nebelgebirge.php?op=upstairs');
		if ($session['hutpartner']!='')
		{
			addnav('Zu '.$session['hutname'].' nach oben','nebelgebirge.php?op=stairpartner');
		}
		if (access_control::is_superuser()) addnav('Sessiondaten leeren (SU)','nebelgebirge.php?op=reset');
		addnav('W?Wieder nach draußen','nebelgebirge.php?op=tal');
		break;
	} // Ende Hütte

	case 'huton':
	{
        page_header('Das Tal im Nebelgebirge');
		$session['campfire']='on';
		redirect('nebelgebirge.php?op=hut');
		break;
	}

	case 'hutoff':
	{
        page_header('Das Tal im Nebelgebirge');
		$session['campfire']='off';
		redirect('nebelgebirge.php?op=hut');
		break;
	}

	case 'upstairs': // Privates Obergeschoss.. *vor Talion auf die Knie fall und anbet*
	{
        page_header('Das Tal im Nebelgebirge');
		if($_GET['act'] == 'search' && mb_strlen($_POST['search']) > 0)
		{
			$search = str_create_search_string($_POST['search']);
			$sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$search.'" AND acctid!='.$session['user']['acctid'].' ORDER BY login="'.db_real_escape_string($_POST['search']).'" DESC, login ASC';
			$res = db_query($sql);
			$link = 'nebelgebirge.php?op=upstairs&act=id';
			output('<form action="'.$link.'" method="POST">');
			output(' <select name="ziel">');

			while ( $p = db_fetch_assoc($res) )
			{
				output('<option value="'.$p['acctid'].'">'.strip_appoencode($p['name'],3).'</option>');
			}

			output('</select>`n`n');
			output('<input type="submit" class="button" value="Nach oben"></form>');
			addnav('',$link);
			addnav('Doch nicht');
			addnav('Zurück','nebelgebirge.php?op=hut');
		} // Ende if

		elseif($_GET['act'] == 'id' && $_POST['ziel'])
		{
			$ziel = (int)$_POST['ziel'];
			$sql = 'SELECT name FROM accounts WHERE acctid='.$ziel;
			$res = db_query($sql);
			$name = db_fetch_assoc($res);

			$session['hutpartner']=$ziel;
			$session['hutname']=$name['name'];

			redirect('nebelgebirge.php?op=stairpartner');

		} // Ende elseif

		else
		{

			output('`c`b`tD`yi`Ye Blockhü`Yt`yt`te`0`b`c
			`n`tS`yo`Yso, du willst also ganz... ungestört sein? Mit wem willst du ins Obergeschoss verschwind`Ye`yn`t?`0`n`n');
			$link = 'nebelgebirge.php?op=upstairs&act=search';
			output('<form action="'.$link.'" method="POST">');
			output('Name eingeben: <input type="input" name="search">');
			output('`n`n');
			output('<input type="submit" class="button" value="Suchen"></form>');
			addnav('',$link);
			addnav('Doch nicht');
			addnav('Zurück','nebelgebirge.php?op=hut');
		} // Ende else

		break;

	} // Ende Obergeschoss


	case 'stairpartner':
	{
		$chat = 'hut_'.min($session['user']['acctid'],$session['hutpartner']).'_'.max($session['user']['acctid'],$session['hutpartner']);

		page_header('Die Blockhütte');
		output('`c`b`tD`yi`Ye Blockhü`Yt`yt`te`0`b`c
		`n`tL`ya`Yngsam erklimmt ihr die wenigen Holzstufen, die unter eurem Gewicht leise knarren und gelangt durch die Luke im Boden in den ersten Stock. Dieses Zimmer ist bis auf ein einziges Bett in einer Ecke und eine Truhe daneben scheinbar vollkommen leer. Ein weicher Teppich bedeckt den Boden und dämpft eure Schritte, als '.$session['hutname'].' `tund du euch ein wenig umseht und schließlich dem Fenster nähert. Es zeigt nach Osten und muss am frühen Morgen einen wunderbaren Blick auf die gerade aufgehende Sonne bieten. Wenn es schon zu spät ist, als dass ihr den langen Rückweg nach Hause antreten wollt, könntet ihr hier die Nacht verbringen, doch werdet ihr wohl ein Stückchen zusammenrücken müssen.`nErst als du dich wieder umdrehst bemerkst du, dass in einer Ecke ein ganzer Haufen Stroh liegt, auf dem einer von euch wohl die Nacht verbringen könnte, wenn ihr euch noch nicht... gut genug kennt.`n`nDoch zumindest könnt ihr euch sicher sein, in der Abgeschiedenheit der Berge ganz ungestört zu sein, niemand wird euch hier oben überraschen könn`ye`tn.`n`n`0');
			viewcommentary($chat,'Leise unterhalten',30,'flüstert',false,true,false,true,false,true,2);
			addnav('Zur Treppe');
			addnav('Runtergehen','nebelgebirge.php?op=hut');
			break;
	} // Ende Partner

	case 'berg':
	{
		page_header('Der Berghang');
		output('`c`b`sD`ye`Yr Bergha`yn`sg`0`b`c
		`n`sV`yo`Yller Tatendrang machst du dich daran, den Berg zu erklimmen. Ist der Weg am Anfang noch einfach und leicht zu begehen, so wird er doch mit der Zeit immer steiler und unebener. Doch du weißt, dass der wunderbare Ausblick dich für deine Mühen entschädigen wird. Ganz außer Atem erreichst du einen kleinen Felsvorsprung und beschließt, eine Pause einzulegen. Hinter dir bemerkst du eine kleine, dunkle Höhlenöffnung, vor dir breitet sich das ganze Tal bis zur gegenüberliegenden Bergkette aus. Du lässt deinen Blick schweifen und siehst wieder den glitzernden Fluss, die Blockhütte, die von hier oben winzig aussieht und ganz am anderen Ende des Tals den Pass, durch den du gekommen bist. Ein Falke, der ruhig über dir am Himmel seine Kreise zieht, sieht von hier oben so nah aus, dass du fast das Gefühl hast, ebenfalls zu fliegen, während du ihn beobachtest. Doch so schön der Anblick auch ist, du solltest nicht die Zeit vergessen, denn im Dunkeln könnte der Abstieg leicht lebensgefährlich s`Ye`yi`sn.`n`n`n`0');
		viewcommentary('nebelberg','Rufen',30,'ruft',false,true,false,0,false,true,1);
		if($session['user']['exchangequest']==16)
		{
			addnav('`%Weiter nach oben`0','exchangequest.php');
		}
		else
		{
			addnav('Noch weiter nach oben','nebelgebirge.php?op=mountaintop');
		}
		if ($session['user']['dragonkills'] > 4)
		{
			addnav('Die Höhle erforschen');
			addnav('H?Hinein gehen','nebelgebirge.php?op=cave');
		}

		//Bossgegner Schwarzer Drache einfügen
		include_once(LIB_PATH.'boss.lib.php');
		boss_get_nav('black_dragon');
		boss_get_nav('grinch');

		addnav('Zurück ins Tal');
		addnav('s?Hinabsteigen','nebelgebirge.php?op=tal');
		break;
	} // Ende Bergsteigen

	case 'mountaintop':
	{
		page_header('Der Berghang');
		output(get_title('Die Bergspitze').'Der Weg war mehr als beschwerlich und dein Atem rasselt noch immer in Erinnerung an die letzten zermürbenden Meter auf deinem Weg zum Dach der Götter. Auf diesem kleinen Plateau, welches die ganze Welt zu überragen scheint, herrscht ein eisiger Wind. Schnee ist in jede Ritze der Felsbrocken eingedrungen und die feinen Kristalle prickeln schmerzhaft auf deiner Haut, die du mit allem zu schützen versuchst, was du an Kleidung trägst.');
		addnav('s?Hinabsteigen','nebelgebirge.php?op=tal');
		break;
	}
	case 'cave':
	{
		page_header('Die Echohöhle');
		output('`c`b`sD`yi`Ye `uEchohö`Yh`yl`se`0`b`c
		`n`sL`ye`Yi`ucht gebückt durchschreitest du den niedrigen Eingang der Höhle und blickst dich im dämmrigen Licht im Innern um. Auf den ersten Blick gibt es hier nicht viel zu sehen, rauhe, vom alljährlichen Tauwasser geformte Wände, die einen Gang bilden, der wohl noch ein ganzes Stück in den Berg hineinführt und immer enger wird. Leises Tröpfeln und Plätschern ist aus dem Berg zu hören, auch die Wände zu deinen Seiten sind ein wenig feucht und kalt. Doch als du langsam weiter in die Höhle vordringst, kannst du hören, wie jeder deiner Schritte von den Wänden wiederhallt und nur langsam im Berg verklingt. Es muss ein unglaubliches Echo geben, wenn du wirklich laut `ub`Yi`ys`st.`n`n`0');
		if (e_rand(1,RANDGEM)==1)
		{
			$session['user']['gems']++;
			debuglog('Fand einen Edelstein in der Echohöle');
			output('`^Du bemerkst aus den Augenwinkeln ein seltsames Glitzern in einer Ecke der Höhle. Als du näher herangehst stellst du fest, dass es sich um einen Edelstein handelt! Voller Freude steckst du ihn ein.`n`n`0');
		}
		viewcommentary('nebelhoehle','Rufen',30,'ruft',false,true,false,0,false,true,1);
		addnav('Was tun?');
		if($session['user']['turns']>0) addnav('Steinchen werfen','nebelgebirge.php?op=rock');
		addnav('Höhle verlassen','nebelgebirge.php?op=berg');
		// if (access_control::is_superuser()) addnav('Zum Gnom (SU)','nebelgebirge.php?op=gnomattack');
		break;
	} // Ende höhle

	case 'rock':
	{
        page_header('Das Tal im Nebelgebirge');

		if ($session['throws']=='') $session['throws']=1;
		$rocktime=e_rand(1,201);
		$rocktime*=0.1;
		$session['throws']++;

		if ($session['throws']>100) redirect('nebelgebirge.php?op=ooops');

		if ($rocktime>20)
		{
			output('`c`b`7Die Echohöhle`0`b`c
			`n`7Du hebst einen kleinen Stein auf, wirfst ihn tief in die Höhle und lauschst dem leisen Klappern und Klirren. Das immer lauter wird. Und näher kommt. Gerade als du dir überlegst, vielleicht einfach wieder ganz still und leise zu verschwinden, siehst du ein paar rot glühende Augen, die dich aus der Dunkelheit heraus anstarren und zu einem kleinen, runzligen, steingrauen Wesen gehören. `s"Duuuu haaaast miiiich aaaaufgeeeeeweeeeckt. Waaaaas wiiiiillst duuuu hiiiier?" `7So langsam das kleine Ding auch redet, sein Tonfall ist alles andere als freundlich und du willst lieber nicht herausfinden, ob es genauso langsam läuft wie es spricht...`n`n ');
			addnav('Hilfe!');
			addnav('G?Dem Gnom stellen','nebelgebirge.php?op=gnomattack');
			addnav('Wegrennen','nebelgebirge.php?op=tal');
			//if (access_control::is_superuser()) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
		}
		else
		{
			output('`c`b`7Die Echohöhle`0`b`c
			`n`7Du hebst einen kleinen Stein auf, wirfst ihn tief in die Höhle und lauschst dem leisen Klappern und Klirren. Das `sEcho `7kannst du '.$rocktime.' Sekunden danach noch hören... `n`n');
			addnav('Und jetzt?');
			addnav('Noch eins werfen','nebelgebirge.php?op=rock');
			addnav('Juhu...','nebelgebirge.php?op=cave');
		}
		break;
	}

	case 'gnomattack':
	{
        page_header('Das Tal im Nebelgebirge');

		$badguy = array(
		"creaturename"=>'`7Steingnom`0',
		"creaturelevel"=>$session['user']['level'],
		"creatureweapon"=>'`7Steinharte Fäuste`0',
		"creatureattack"=>$session['user']['attack']*1.3,
		"creaturedefense"=>$session['user']['defence']*1.2,
		"creaturehealth"=>$session['user']['maxhitpoints']*0.9,
		"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$session['user']['turns']--;
		debuglog('hat den Steingnom gereizt');
		redirect('nebelgebirge.php?op=fighting');
		break;
	} // Ende gnomattack

    /** @noinspection PhpMissingBreakStatementInspection */
    case 'run':
	{
        page_header('Das Tal im Nebelgebirge');
		output('`c`b`7Die Echohöhle`0`b`c
		`n`7Dieser Gnom scheint stärker zu sein, als er aussieht.. Du beschließt, dass es besser wäre, dich zurückzuziehen und drehst dich um, um wegzurennen.`n');
		if (e_rand(1,3)==1)
		{

			output('`7Du rennst aus der Höhle und noch ein ganzes Stück den Berg hinunter, ehe du völlig außer Atem stehen bleibst. Das ist geradenocheinmal gut gegangen...`n`n');
			addnav('Glück gehabt');
			addnav('Weitergehen','nebelgebirge.php?op=berg');
			break;
		}
		else
		{
			output('`7Doch der Gnom scheint wirklich sauer zu sein. Auf jeden Fall musst du sehr bald feststellen, dass er doch schneller rennt, als er spricht...`n`n<hr>`n');
		}
	// Und dieses break hier fehlt absichtlich, damit es mit dem Kampf weitergeht ;)
	}  // Ende run


	case 'fighting':
	case 'fight':
	{
        page_header('Das Tal im Nebelgebirge');
		include('battle.php');
		if ($victory)
		{
			$erfahrung=round($session['user']['experience']*0.02,0);
			$session['user']['experience']+=$erfahrung;
			headoutput('`7`c`bDie Echohöhle`b`c`nDu hast den kleinen Gnom besiegt! Für deinen Sieg erhälst du '.$erfahrung.' Erfahrungspunkte.`n`n<hr>`n');
			addnav('Juhu!');
			addnav('Höhle verlassen','nebelgebirge.php?op=berg');
			$badguy=array();
		}
		elseif ($defeat)
		{
			addnews($session['user']['name'].'`7 wurde von einem Steingnom besiegt.');
			$session['user']['gems']= max(0,$session['user']['gems']-2);
			$session['user']['hitpoints']=1;
			headoutput('`7`c`bDie Echohöhle`b`c`nDer Steingnom hat dich windelweich gepügelt. Als du vor ihm am Boden liegst, glaubst du schon, dass dein letztes Stündlein geschlagen hat, doch statt dir den Rest zu geben, begnügt das Wesen sich damit, `szwei Edelsteine `7aus deinem Beutel zu nehmen. Während der Gnom sich wieder in die Schatten der Höhle zurückzieht, rappelst du dich auf und schleppst dich mühsam nach draußen.`n`n<hr>`n');
			addnav('Oh nein!');
			addnav('Höhle verlassen','nebelgebirge.php?op=berg');
			$badguy=array();
		}
		else
		{
			fightnav(true,true);
		}
		break;
	} // Ende Kampf

	case 'ooops':
	{
		page_header('Einmal zu oft...');
		output('`c`b`)Das war ein Stein zu viel...`0`b`c
		`n`)Nachdem du den letzten Stein tief in die Höhle geworfen hast und das Echo verklungen ist, hörst du ein anderes, leises Geräusch. Ein Knacken und Bröckeln und als du den Blick hebst siehst du gerade noch, wie ein Stalagtit sich von der Höhlendecke löst und auf deinen Kopf zurast. Das war\'s dann wohl! Eine lange Dunkelheit umgibt dich.
		`n`4Das hat sehr sehr weh getan!
		`n`)Für deine Dummheit könnte Ramius dir alle Gefallen nehmen. Vielleicht hat er aber auch einen guten Tag und schmeißt dich nur aus seinem Reich...`n`n');
		$session['user']['hitpoints']=1;
		debuglog('warf einen Stein zu viel');
		addnav('Verdammt!');
		addnav('Weiter','wolkeninsel.php?op=insel');
		break;
	}

	case 'reset':
	{
        page_header('Das Tal im Nebelgebirge');
		$session['campfire']='';
		$session['flowers']='';
		$session['hutpartner']='';
		$session['hutname']='';
		$session['throws']='';
		redirect('nebelgebirge.php?op=hut');
		break;
	}

	default:
	{
		page_header('Der Nebelpfad');
		output('`c`b`eD`se`fr Pfad im Ne`fb`se`el`0`b`c
		`n`eD`su `ffolgst einem kleinen Pfad um den See und je weiter du kommst, desto dichter wird der Wald zu beiden Seiten. Der Boden scheint ein wenig anzusteigen und nach einer Weile bist du dir sicher, dass der Weg geradewegs zu den Bergen führt, die du in der Ferne erkennen kannst. Leichter Nebel liegt zwischen den Bäumen, nimmt dir die Sicht auf alles, was mehr als ein paar Meter entfernt ist, doch die Neugier treibt dich weiter. Schließlich führt der Pfad recht steil nach oben und endet an einem kleinen Pass, dessen Ende du durch den Nebel nicht erkennen kannst. Dort könnte dich alles erwarten... Willst du es wagen und weitergeh`se`en?`n`n');
		addnav('Weitergehen');
		addnav('P?Den Pass überqueren','nebelgebirge.php?op=tal');
		addnav('Zurück');
		addnav('S?Zum See','pool.php');
		addnav('W?Zum Wald','forest.php');
		break;
	} // Ende default

} // Ende von switch op

page_footer();
?>