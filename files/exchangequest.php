<?php
/*Tausch-Quest, Idee von Valas, Programmierung von Salator
*Zentrale Code-Datei für den Quest, SOURCE SOLLTE NICHT ÖFFENTLICH SEIN
*Einbindung über bedingungsabhängige Links an diversen Orten, Einleitungstext sollte ebenfalls an den Orten sein.
*wurde getestet mit lotgd 0.9.7 Dragonslayer-Edition 3.23, Einbindug außerhalb: Viel Glück!
*benötigt ein Feld 'exchangequest' in der accounts-Tabelle und ein Item mit der Schablone 'exchngdmmy'
*benötigt für die Highscore eine mit Schreibrecht versehene Datei exchangequest_userlist.dat
*Schritt 7 benötigt ein Item exchngtrnk: unsichtbar, im Kampf nutzbar, tötet den Gegner sofort und setzt exchngdmmy auf Donneraxt
*/

require_once "common.php";
checkday();
page_header("Eine Begegnung");
$session['user']['specialinc']='';
output('`c`bEtwas Besonderes`b`c`n`%');
switch($session['user']['exchangequest']){
	case 0:
		{
			output('Wie auch immer du hier hingeraten bist, du bist hier falsch.');
			break;
		} //Quest starten ist im Waldspecial

	case 1:
		{ //trigger in special/village_marblegame.php, extratrigger in /special/oldmantown.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Vogelfeder';
			$item['description'] = 'Eine schlichte Vogelfeder, die dir der Junge auf dem Stadtplatz geschenkt hat. Angespitzt könnte sie als Schreibfeder dienen.';
			$item['gold'] = 20;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			output('Du gibst dem Kleinen deine bunte Murmel. Überglücklich hopst er dich an und sagt artig "`6Danke, '.($session['user']['sex']?'Tante':'Onkel').'!`%". Bevor er sich zu den anderen Kindern gesellt überreicht er dir eine Feder, die er sich ins Haar gesteckt hatte.`n
			Du wartest noch kurz und siehst, dass der Kleine mit deiner Murmel einen Glückstreffer hat. Zufrieden wendest du dich ab, während dir der Kleine nachwinkt.');
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			break;
		} //end Quest1

	case 2:
		{ //trigger in library.php
			if($_GET['op']=='ask')
			{
				$sql='SELECT name FROM items WHERE name LIKE "%feder%" AND owner='.$session['user']['acctid'];
				$result=db_query($sql);
				$count=db_num_rows($result);
				output('Du entscheidest dich, deinen Gegenüber anzusprechen. So erfährst du unter anderem dass du es mit dem nicht ganz unbekannten Dichter `QHeinrich Albert`% zu tun hast.`n
				Der Grund für sein nervöses Herumkramen ist, er findet seine Schreibfeder nicht und kann sein Gedicht nicht fertig schreiben.`n`n
				Du hast in deinem Beutel: ');
				for ($i=0; $i<$count; $i++)
				{
					$row=db_fetch_assoc($result);
					output(create_lnk($row['name'],'exchangequest.php?op=give',true,true,'',false,$row['name'].' geben').'`%, ');
				}
				output('`% welche du Heinrich Albert anbieten könntest. Was tust du?');
			}
			elseif($_GET['op']=='give')
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 Pergamentblatt';
				$item['description'] = 'Ein Liebesgedicht mit dem Titel "Du mein einzig", welches Heinrich Albert geschrieben und dir gewidmet hat.';
				$item['gold'] = 25;
				$item['gems'] = 0;
				item_set('id='.$item['id'],$item);
				output('Du gibst Heinrich Albert deine Feder. Ein Lächeln erhellt sein Gesicht. "`6Danke, '.$session['user']['login'].'! Nun kann ich mein Gedicht fertig schreiben. Ich werde es Euch widmen.`%"`n
				Sofort macht er sich an die Arbeit und schreibt folgende Zeilen:`n`n
				`&`cDu mein einzig Licht,`n
				die Lilg und Ros hat nicht,`n
				was an Farb und Schein`n
				dir möcht ähnlich sein,`n
				nur daß dein stolzer Mut`n
				der Schönheit unrecht tut.`n`n
				
				Alle Vöglein hier`n
				samt ihrer Melodie`n
				jubilierten nicht`n
				ohn\' der Liebe Pflicht`n
				und würden nicht erfreut`n
				durch diese Frühlingzeit.`n`n
	
				Darum Liebster laß`n
				uns beid ohn\' Unterlaß`n
				reden Tag und Nacht`n
				von der Liebe Macht.`n
				Das schafft dem Herzen Freud,`n
				vertreibt mit Lust die Zeit.`c`n
				`%Abschließend bläst er die Tinte trocken und überreicht dir das Pergament.');
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			}
			else
			{
				output('Du siehst dich nach einem freien Tisch um, leider erfolglos. Also beschließt du, einen der anderen Anwesenden zu fragen ob du dich zu ihm setzen darfst. Deine Wahl fällt auf einen nervös wirkenden Mensch.`n
			Er mustert dich kurz und deutet dir, Platz zu nehmen. Du setzt dich und packst deine Utensilien aus.`n
			Doch irgendwie bekommst du den Kopf nicht frei, der nervöse Mensch dir gegenüber nimmt dir völlig die Ruhe.`n
			Du könntest jetzt einfach gehen oder deinen Gegenüber ansprechen.');
				addnav('Ansprechen','exchangequest.php?op=ask');
			}
			addnav('Zur Bücherei','library.php');
			break;
		} //end Quest2

	case 3:
		{ //trigger in gardens.php
			if($_GET['op']=='give')
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 Rose';
				$item['description'] = 'Eine Rose, welche dich an die Begegnung mit Pia Lorenza im Garten erinnert.';
				$item['gold'] = 30;
				$item['gems'] = 0;
				item_set('id='.$item['id'],$item);
				output('Du gibst Pia Lorenza das Pergament mit dem Gedicht. Sie sagt "`6Das ist ja wunderbar! Davon fühle ich mich inspiriert, jetzt kann ich ganz bestimmt mein Gedicht zuende bringen. So nehmt diese Rose als Zeichen meines Dankes.`%"`n
				Du nimmst die Rose, ein wahrlich schönes Exemplar. Und wie sie duftet... Da wird dir schlagartig wieder klar warum du in den Garten gegangen bist. Höflich aber rasch verabschiedest du dich von Pia Lorenza und machst dich auf den Weg zu '.($session['user']['sex']?'deinem':'deiner').' Geliebten. ');
				if($session['user']['marriedto']!=0 && $session['user']['marriedto'] != 4294967295)
				{
					$sql='SELECT name FROM accounts WHERE acctid='.$session['user']['marriedto'];
					$row=db_fetch_assoc(db_query($sql));
					output('Doch, oh weh, '.$row['name'].'`% hat zu lange auf dich warten müssen und ist gegangen. Für heute wars das mit dem Flirten...');
					$session['user']['seenlover']=1;
				}
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			}
			else
			{
				output('Du näherst dich dem Mädchen und fragst ob du dich zu ihr setzen darfst. Doch sie winkt teilnahmslos ab und versucht weiter einen Reim auf `&Rosenblüte`% zu finden.`n
				"`QSchnappers Los war eine Niete`%" rutscht es dir heraus und auf einmal müsst ihr beide lachen. Die Hemmschwelle ist gebrochen und du unterhältst dich eine ganze Weile mit dem Mädchen, deren Name Pia Lorenza ist. Dabei wird es immer später, du vertrödelst einen Waldkampf.`n
				Pia Lorenza hat sich in einen Jungen verliebt und möchte ihn mit einem Gedicht erfreuen. Doch irgendwie kommt nichts brauchbares dabei heraus. "`6Was wohl Heinrich Albert an meiner Stelle schreiben würde?`%" sagt sie ein wenig wehmütig...`n
				`&Heinrich Albert!`% Das ist dein Stichwort. Du hast doch ein Liebesgedicht von ihm! Jetzt wäre eine gute Gelegenheit, es einzusetzen. Willst du Pia Lorenza das Gedicht geben?');
				addnav('`%Pergament geben`0','exchangequest.php?op=give');
			}
			addnav('In den Garten','gardens.php');
			break;
		} //end Quest3

	case 4:
		{ //trigger in special/calevents.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Räuchermischung';
			$item['description'] = 'Eine Räuchermischung wie sie von Hexen und Magiern gerne an Beltane verwendet wird.';
			$item['gold'] = 200;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			output('Du gibst der Hexe eine Rose. Im Gegenzug spricht sie einen Zauber über dich und gibt dir eine Räuchermischung.`n
			Vielleicht möchtest du dich ja auch an Ostara auf der Waldlichtung einfinden?');
			$session['bufflist']['witchspell'] = array('name'=>'`%Hexenzauber','rounds'=>20,'wearoff'=>'Der Zauber der Hexe wirkt nicht mehr.','defmod'=>1.1,'roundmsg'=>'`%Der Zauber der Hexe schützt dich.','activate'=>'offense');
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			addnav('Zurück in den Wald','forest.php');
			break;
		} //end Quest4

	case 5:
		{ //trigger in pool.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Rubin';
			$item['description'] = 'Ein Rubin von beachtlicher Größe. Den hast du in der Asche des Hexenfeuers gefunden. Sein Schliff und seine Herkunft lassen dich vermuten, dass es sich um einen magischen Gegenstand handelt.';
			$item['gold'] = 0;
			$item['gems'] = 1;
			item_set('id='.$item['id'],$item);
			output('Du entschließt dich, dem bevorstehenden Hexentanz beizuwohnen. Es dauert auch gar nicht lange bis sich einige Hexen und Magiere sowie weitere Gäste an diesem Ort versammeln.`n
			Als die Abenddämmerung beginnt tritt einer der Hexenmeister hervor und spricht einen Feuerzauber auf den Holzhaufen, welcher sofort lichterloh zu brennen beginnt. Die Zeremonie ist eröffnet.`n
			Die meisten der Anwesenden holen nun eine Räuchermischung aus ihrem Beutel und entzünden diese am geweihten Feuer. Also tust du es ihnen gleich.`n`n
			Bis spät in die Nacht tanzt ihr ausgelassen um das Feuer, bis sich auch der Letzte erschöpft ins Gras sinken lässt und das Feuer langsam niederbrennt.`n
			Als das Feuer erloschen ist bemerkst du in der Asche einen aufwändig geschliffenen Rubin, den du einsteckst.');
			$session['user']['turns']=0;
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			addnews('`^'.$session['user']['name'].' `&hat ein magisches Ritual vollführt.`0');
			addnav('S?Zurück zum See','pool.php');
			break;
		} //end Quest5

	case 6:
		{ //trigger in special/earthshrine.php
			$session['user']['specialinc']='';
			if($_GET['op']=='give')
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 Phiole mit Wasser';
				$item['description'] = 'Eine Phiole, gefüllt mit Wasser aus der Höhle des Erdschreins.';
				$item['gold'] = 10;
				$item['gems'] = 0;
				item_set('id='.$item['id'],$item);
				item_add($session['user']['acctid'],'exchngtrnk');
				output('Du greifst in deinen Beutel und holst den großen Rubin heraus. Ja, der sieht genau so aus wie die beiden anderen, die auf den Statuen liegen. Also legst du ihn der mittleren Statue in die Hand.
				`n`nDer Rubin passt perfekt! Du hast einen Mechanismus ausgelöst, welcher rumpelnd die Statuen beiseite schiebt. Eine Quelle kommt dahinter zum Vorschein.
				`nDu erinnerst dich an deinen Kampf gegen das Ungeheuer in dieser Höhle. Ob der Behemoth aus dieser Quelle seine Kraft schöpft? Einen Versuch wäre es ja wert, also füllst du eine Phiole mit dem Wasser.
				`n`nAls du dich von der Quelle entfernst schiebt der Mechanismus die drei Statuen wieder vor die Quelle. Der Rubin der mittleren Statue ist auf rätselhafte Weise verschwunden.');
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				addnews('`^'.$session['user']['name'].' `&hat ein magisches Ritual vollführt.');
				addnav('W?Zurück in den Wald','forest.php');
			}
			elseif($_GET['op']=='take')
			{
				output('Gierig greifst du nach einem der Rubine. Doch kaum hast du den Edelstein von der Statue abgehoben, beginnen die Höhlenwände zu zittern. Vor Schreck lässt du den Rubin fallen und rennst zum Ausgang.
				`nDu hast es fast geschafft, als ein dicker Felsen von der Decke herabstürzt. Nicht nur dass er dir den Ausgang verschließt, auch du wurdest hart getroffen. Mit gebrochenen Beinen und weiteren schweren Verletzungen liegst du da und wartest auf den Tod.');
				addnews($session['user']['name'].'`% wurde in einer Höhle begraben als '.($session['user']['sex']?'sie':'er').' zu gierig wurde.`0');
				killplayer(100,3,0,'shades.php','Hallo Ramius!');
				$village=false;
			}
			break;
		} //end Quest6

	case 7:
		/* Item-Code:
		global $badguy,$zauber,$session;

		output('`n`^Du trinkst das Wasser in dem Fläschchen mit einem Zug aus und fühlst dich so stark wie nie zuvor. `n`&`bDu holst zu einem <font size="+1">MEGA</font> Powerschlag aus und triffst '.$badguy['creaturename'].'`& mit einem vernichtenden Schlag!!!`b`n`QBeim Durchsuchen von '.$badguy['creaturename'].'`Q findest du eine `%Donneraxt`Q!`n`n');
		$badguy['creaturehealth'] = 0;
		$session['user']['exchangequest']++;

		$itemnew=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
		$itemnew['name'] = '1 `^Donneraxt`0';
		$itemnew['description'] = 'Eine echte zwergische Kampfaxt.';
		$itemnew['gold'] = 7654;
		$itemnew['gems'] = 0;
		item_set('id='.$itemnew['id'],$itemnew);

		item_delete(' id = '.$zauber['id']);
		*/
		break;
		//nothing to do here, end Quest 7

	case 8:
		{ //trigger in special/goldmine.php (Maris-version)
			$village=false;
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Brocken Mithril-Erz';
			$item['description'] = 'Mithril-Erz ist in '.getsetting('townname','Atrahor').' wertlos weil es niemand verarbeiten kann. Du hast aber gehört dass es in Frohnau nahezu unbezahlbar ist. Schade, dass du dort nie hinkommen wirst...';
			$item['gold'] = 50;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			$session['user']['specialinc']='goldmine.php';
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('Du entscheidest dich, den Zwerg anzusprechen. "`6Hallo Fremder! Vielleicht kann ich Euch helfen. Seht, diese Axt habe ich im Kampf erbeutet. Aber mir nützt sie nichts.`%" Der Zwerg, dessen Name Loki ist, ist sichtlich erfreut als er seine Donneraxt wieder in den Händen hält. "`^Ja das ist sie, meine Donneraxt! Ich werde gleich mal ausprobieren ob sie noch genau so gut funktioniert wie früher.`%" Und tatsächlich, mit dieser Axt gelingt es ihm problemlos, einen großen Brocken Mithril-Erz aus dem Gestein zu schlagen, welchen er dir als Zeichen seines Dankes überreicht.
			`n`nDer Zwerg verabschiedet sich von dir und sucht sich eine neue erfolgversprechende Stelle zum Graben. Du wendest dich wieder deinen eigenen Angelegenheiten zu. Du bist ja hier unten weil du einen der Kristalle mitnehmen wolltest...');
			addnav('Kristall nehmen','forest.php?op=enter&level=8&gallery=4&mount_enters='.$_GET['mount_enters'].'&pos=1');
			addnav('Zurück zum Aufzug','forest.php?op=enter&level=8&gallery=0&mount_enters='.$_GET['$mount_enters']);
			addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			break;
		} //end Quest 8

	case 9:
		{ //trigger in vendor.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 seltsame Flöte';
			$item['description'] = 'Eine Flöte, auf der man keinen sauberen Ton spielen kann. Aeki hat dir erzählt, dass man mit dieser Flöte riesige Bestien beschwören könne.';
			$item['gold'] = 500;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('Als du Aeki dein Mithril-Erz anbietest, bekommt er große Augen. "`q'.($session['user']['sex']?'Mädl':'Bursche').'`q, das ist ja etwas ganz Exquisites! Nie im Leben hätte ich gedacht, je einen solch großen Klumpen Mithril-Erz zu sehen! Ich gebe dir hundert... Nein, das ist nicht mit Gold oder Edelsteinen zu bezahlen... Ich gebe dir diese Flöte.`%"
			`nMit diesen Worten kramt Aeki eine schlicht aussehende hölzerne Flöte unter der Theke hervor, überreicht dir diese und schnappt sich das Erz, bevor du etwas sagen kannst.
			`nNatürlich hat er auch zu der Flöte eine Geschichte parat. Diese Flöte lag einst am Waldsee, inmitten von RIESIGEN Fußspuren, die jedoch nicht auf einen Kampf hinwiesen. Es heißt, man könne mit dieser Flöte die Kreaturen im Waldsee beschwören.
			`nDu hast diese Geschichte bereits von Old Drawl gehört, aber nie ernst genommen. Und auch jetzt hast du irgendwie das Gefühl, besch...ummelt worden zu sein. Aber wer weiß, vielleicht ist ja doch was dran...');
			addnav('Mehr verkaufen','vendor.php?op=sell');
			break;
		} //end Quest 9

	case 10:
		{ //trigger in fish.php
			$indate = getsetting('gamedate','0005-01-01');
			$date = explode('-',$indate);
			$tag = $date[2];
			$monat = $date[1];
			if($monat==6 && $tag>17 && $tag<21)
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 Handspiegel';
				$item['description'] = 'Ein Handspiegel, dessen goldener Rand reich verziert und mit Brillianten besetzt ist. Man könnte meinen, nichteinmal Elfen sind in der Lage etwas so Filigranes herzustellen.';
				$item['gold'] = 0;
				$item['gems'] = 15;
				item_set('id='.$item['id'],$item);
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				output('Du versuchst mal wieder, auf der Flöte ein Liedchen zu spielen, da passiert es: Das Wasser des Sees beginnt heftige Wellen zu schlagen, schließlich taucht eine riesige Bestie aus den Fluten auf.
				`nDu hast bereits von dem sagenhaften Ungeheuer "Nessie" gehört, was da vor dir steht muß genau dieses sein...
				`nDas Ungeheuer beginnt mit donnernder Stimme zu sprechen: "`tWer wagt es, meinen Schlaf zu stören!?`%" Du lässt vor Schreck die Flöte fallen, stellst dich stotternd vor und sagst, dass du diese Flöte von Aeki bekommen hast.
				`nNessie nickt und sagt: "`tIch bewache einen großen Schatz und lediglich jene, die diese Flöte spielen, sind berechtigt einen Teil davon an sich zu nehmen.`%"
				`nOb dieser Worte spuckt sie einen kleinen Handspiegel aus, welcher direkt vor deinen Füßen landet. Er schaut sehr wertvoll aus, Gold mit vielen Brillianten ziert seinen Rand. Ohne sich zu verabschieden taucht Nessie wieder ab. Da dir diese Kreatur mulmig erscheint und du ihr nicht nochmal begegnen willst, schnapst du dir den Spiegel und suchst das Weite, die Flöte vergisst du dabei am Ufer.');
				addnews('`^'.$session['user']['name'].'`& begegnete einer riesigen Kreatur.');
			}
			elseif($monat<6 || ($monat==6 && $tag<18))
			{
				output('Du versuchst, auf der Flöte ein Liedchen zu spielen. Es klingt scheußlich und du weißt, dass du erstmal alle Fische verscheucht hast. Aber sonst passiert nichts außergewöhnliches. Vielleicht ist es noch zu früh?');

			}
			else
			{
				output('Du versuchst, auf der Flöte ein Liedchen zu spielen. Es klingt scheußlich und du weißt, dass du erstmal alle Fische verscheucht hast. Aber sonst passiert nichts außergewöhnliches. Vielleicht ist es dieses Jahr schon zu spät?');

			}
			user_set_aei(array('fishturn'=>0));
			break;
		} //end Quest 10

	case 11:
		{ //trigger in special/kubus.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Kuss';
			$item['description'] = 'Ein Kuss hat keinerlei materiellen Wert. Und bevor du eine Anfrage schreibst, wie man einen Kuss in den Beutel packt, der Programmierer weiß es auch nicht. Betrachte dieses Item einfach als nicht vorhanden ;)';
			$item['gold'] = 0;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			$rowe=user_get_aei('csign,ctitle,cname,title_postorder,title_hide');
			if ($rowe['title_hide']) {
				$rowe['ctitle']='\'im Glück\'';
				$rowe['title_hide'] = false;
				$rowe['title_postorder'] = true;
			} else {
				$rowe['ctitle']=(!empty($rowe['ctitle'])?$rowe['ctitle']:$session['user']['title']).' \'im Glück\'';
			}
			user_set_name($session['user']['acctid'],true,$rowe);
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('Du zwinkerst '.($session['user']['sex']?'dem Fremden':'der Fremden').' zu und kurze Zeit später seid ihr beide auch schon im nächsten Gebüsch verschwunden...
			`n`nNach eurem rauschhaften Liebesspiel machst du dich wieder zurecht. Dabei benutzt du deinen reich verzierten Handspiegel. '.($session['user']['sex']?'Der':'Die').' mysteriöse Fremde erblickt den Spiegel von Nessie in deiner Hand. '.($session['user']['sex']?'Er':'Sie').' verspricht dir eine angemessene Belohnung, wenn du den Spiegel hergibst.
			`nDas Angebot klingt interessant und so willigst du ein. Wie verprochen bekommst du eine angemessene Belohnung... einen Kuss auf die Wange.
			`nBevor du reagieren kannst ist '.($session['user']['sex']?'der':'die').' Fremde auch schon verschwunden.
			`nDu fühlst dich einfach... toll... und bist von nun an bekannt als "`^'.$session['user']['name'].'`%".');
			$session['user']['turns']++;
			addnav('Ein Waldmonster fröhlich machen','forest.php');
			addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			break;
		} //end Quest 11

	case 12:
		{ //trigger in fireshrine.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Met-Karaffe';
			$item['description'] = 'Eine Karaffe gefüllt mit feinstem Met, wie er nur auf dem Stadtfest erhältlich ist.';
			$item['gold'] = 250;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('Wie du so am Lagerfeuer sitzt und dich unterhältst, fragt dich plötzlich einer der Zuhörenden, warum man dich `0'.$session['user']['name'].'`% nennt. Also erzählst du die Geschichte wie du einen wertvollen Spiegel gegen einen Kuss eingetauscht hast.
			`nDem Unbekannten gefällt die Geschichte so gut dass er dir einen Becher Met ausgibt.
			`nDu bist jedoch der Meinung, für heute schon genug getrunken zu haben, und füllst den Met heimlich in eine Karaffe, die du rein zufällig im Beutel hast.');
			user_set_name($session['user']['acctid']);
			addnav('Zurück zum Stadtfest','dorffest.php?op=fire&action=gossip');
			break;
		} //end Quest 12

	case 13:
		{ //trigger in special/inn_brawl.php, extra-trigger in inn.php?op=strolldown
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Glas Honig';
			$item['description'] = 'Ein Glas edler Honig aus fernen, nördlichen Landen.';
			$item['gold'] = 400;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('Du füllst dem Unhold sein Trinkhorn mit dem Met, den du vom Stadtfest aufgehoben hast. Die Lage ändert sich binnen Sekunden. Obwohl der Hühne sturzbetrunken ist erkennt er, dass es sich hier um einen ganz besonders edlen Met handelt. Ihr kommt ins Gespräch, naja, eigentlich verstehst du kein Wort von dem was der Hühne lallt. Aber was zählt ist, er gibt dir ein Glas Honig aus seiner Heimat.');
			addnav('Zurück zur Kneipe','inn.php');
			addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			break;
		} //end Quest 13

	case 14:
		{ //trigger in coffeehouse.php
			if($_GET['op']=='give')
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 Laib Brot';
				$item['description'] = 'Ein ganzes Brot. Sehr nahrhaft.';
				$item['gold'] = 600;
				$item['gems'] = 0;
				item_set('id='.$item['id'],$item);
				output('Du gibst dem Konditor den Honig. Er überreicht dir dafür ein frisches Brot.
				`nWie lecker das duftet! Und ein Brot kommt dir gerade recht, auf einer Bergwanderung wird man schnell hungrig.');
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			}
			else
			{
				output('Neugierig, herauszufinden wer denn hier so schimpft, gehst du in die Backstube. Schnell wird der Grund klar: Einer der Konditoren benötigt Honig, kann aber keinen finden. Drinnen im Salon sitzt die hochnäsige reiche Kundschaft, die sich nicht für die Probleme eines Konditors interessiert. Und auch die Weihnachtsbäckerei muss in Kürze angefangen werden.
				`nHonig... Wo bekommt man jetzt schnell Honig her? Aus deinem Beutel vielleicht?');
				addnav('`%Honig geben`0','exchangequest.php?op=give');
			}
			addnav('Zur Patisserie','coffeehouse.php');
			break;
		} //end Quest14

	case 15:
		{ //trigger in nebelgebirge.php
			if($_GET['op']=='give')
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 Wanderkarte';
				$item['description'] = 'Auf dieser alten Wanderkarte ist ein Weg eingezeichnet den es gar nicht gibt. Vielleicht gab es diesen Weg ja mal und er ist inzwischen zugewachsen.';
				$item['gold'] = 800;
				$item['gems'] = 0;
				item_set('id='.$item['id'],$item);
				output('Du teilst dein Brot mit dem Wanderer. Er dankt es dir, indem er dir eine Wanderkarte überreicht, welche zu einem wunderschönen Ort führen soll. Außerdem warnt er dich vor Bestien, welche sich dort aufhalten sollen und dass man nur sicher ist, wenn diese Biester schlafen.
				`nDie Pause hat dich vollständig geheilt, du verlierst aber einen Waldkampf.');
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
				$session['user']['turns']=max(0,$session['user']['turns']-1);
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			}
			else
			{
				output('Du entscheidest dich, den Wanderer anzusprechen. Dieser bittet dich um Hilfe weil er völlig entkräftet ist. Er hat sich wohl etwas überschätzt und der Weg in die Stadt ist noch weit.
				`nJetzt wäre eigentlich eine gute Gelegenheit für ein Picknick...');
				addnav('`%Picknicken`0','exchangequest.php?op=give');
			}
			addnav('Weiter ins Tal','nebelgebirge.php?op=tal');
			break;
		} //end Quest15

	case 16:
		{ //trigger in nebelgebirge.php
			if($_GET['op']=='')
			{
				//Next New Day in ... is by JT from logd.dragoncat.net
				$time = gametime();
				$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
				$secstotomorrow = $tomorrow-$time;
				$realsecstotomorrow = round($secstotomorrow / (int)getsetting('daysperday',4));
				if($realsecstotomorrow<=300) //5 Minuten vor Tageswechsel
				{
					output('Nach einem anstrengenden Aufstieg erreichst du ein Plateau. Im frischen Schnee entdeckst du Fußspuren. Die sind vermutlich vom Yeti, aber zum Glück ist er nirgends zu sehen.
					`nDu siehst dich um und stellst fest dass du an einem wundervollen Aussichtspunkt angekommen bist. Überall blühen silberne Blumen die du noch nie gesehen hast, zwischen welchen du dich auch gleich niederlässt und in den Vollmond blickst.');
					addnav('Rasten','exchangequest.php?op=sleep');
					$village=false;
				}
				else
				{ //nicht im Kampf zu besiegen
					$badguy = array(
					"creaturename"=>'`7Yeti`0',
					"creaturelevel"=>25,
					"creatureweapon"=>'`7Haarige Fäuste`0',
					"creatureattack"=>999,
					"creaturedefense"=>999,
					"creaturehealth"=>99999,
					"diddamage"=>0);
					$session['user']['badguy']=createstring($badguy);
					output('Nach einem anstrengenden Aufstieg erreichst du ein Plateau. Im frischen Schnee entdeckst du Fußspuren. Riesige Fußspuren! Doch du brauchst nicht lange zu überlegen woher die kommen, denn da steht er auch schon vor dir: Der Yeti! Wagst du es, zu kämpfen oder ziehst du dich lieber zurück?');
					addnav('Kämpfen!','exchangequest.php?op=fight');
					addnav('Zurück ins Tal','nebelgebirge.php?op=tal');
				}
			}
			elseif($_GET['op']=='sleep')
			{
				$time=getgametime(true);
				if($time>'20:00')
				{
					output('Der Abstieg ist um diese Zeit gefährlich, also ruhst du dich erstmal aus.');
					addnav('Weiter rasten','exchangequest.php?op=sleep');
					addnav('Zurück in die Stadt','village.php',false,false,false,true,'Zurückgehen ist nicht empfehlenswert. Willst du es dennoch tun?');
					$village=false;
				}
				else
				{
					output('Nach einer sehr entspannenden Nacht auf der Aussichtsstelle fühlst du dich einfach wunderbar.');
					addnav('Es ist ein neuer Tag','exchangequest.php?op=wakeup');
					$village=false;
				}
			}
			elseif($_GET['op']=='wakeup')
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 seltsamer Stein';
				$item['description'] = 'Ein Stein, welcher magisch bunt leuchtet. Du hast noch mehr davon in deinem Beutel.';
				$item['gold'] = 0;
				$item['gems'] = 1;
				item_set('id='.$item['id'],$item);
				output('Als du an diesem Morgen aufwachst sind die silbernen Blumen verschwunden. Stattdessen liegen überall seltsame Steine herum, welche in den verschiedensten Farben leuchten. Schnell sammelst du so viele wie du tragen kannst ein und verstaust sie in deinem Beutel, ehe du den Rückweg antrittst.
				`n`nAuf deinem Weg in die Stadt brichst du kraftlos zusammen. Du denkst noch, hoffentlich kommt ein Wanderer und hilft dir, so wie du es selbst vor einiger Zeit getan hast...');
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				addnav('Zurück ins Tal','nebelgebirge.php?op=tal');
				addnews('`^'.$session['user']['name'].'`& brach kraftlos am Nebelpfad zusammen und hofft auf Hilfe.`0');
			}
			elseif($_GET['op']=='fight')
			{
				include('battle.php');
				if ($victory)
				{ //wer das Ding besiegt darf auch weiter
					headoutput('`c`b`@Sieg!`0`b`c`nDu hast den Yeti besiegt!
					`nDu siehst dich um und stellst fest dass du an einem wundervollen Aussichtspunkt angekommen bist. Überall blühen silberne Blumen die du noch nie gesehen hast, zwischen welchen du dich auch gleich niederlässt und in den Vollmond blickst.`n`n<hr>`n'); 
					addnav('Erstmal ausruhen','exchangequest.php?op=sleep');
					addnews('`^'.$session['user']['name'].'`& hatte eine siegreiche Begegnung mit dem Yeti.`0');
					$badguy=array();
				}
				elseif ($defeat)
				{
					$session['user']['gems']--;
					$session['user']['hitpoints']=1;
					headoutput('`c`b`$Niederlage`0`b`c`n`%Der Yeti hat dich windelweich gepügelt. Als du vor ihm am Boden liegst, glaubst du schon, dass dein letztes Stündlein geschlagen hat, doch statt dir den Rest zu geben, begnügt das Wesen sich damit, `seinen Edelstein `%aus deinem Beutel zu nehmen. Während der Yeti sich wieder zurückzieht, rappelst du dich auf und schleppst dich mühsam zurück in die Stadt.
					`nWie sagte doch der Wanderer? Nur wenn sie schlafen ist man sicher...`n`n<hr>`n'); 
					addnav('Oh nein!');
					addnews('`^'.$session['user']['name'].'`& hatte eine unheimliche Begegnung mit dem Yeti.`0');
					$badguy=array();
				}
				else
				{
					fightnav(true,true);
					$village=false;
				}
			}
			elseif($_GET['op']=='run')
			{
				output('Der Kampf ist aussichtslos. So schnell du kannst verschwindest du von hier.');
				addnav('Zurück ins Tal','nebelgebirge.php?op=tal');
			}
			break;
		} //end Quest16

	case 17:
		{ //trigger in runemaster.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Zaubertafel';
			$item['description'] = 'Eine mit magischen Zeichen verzierte Steintafel. Man soll damit an bestimmten Tagen die Toten beschwören können.';
			$item['gold'] = 200;
			$item['gems'] = 2;
			item_set('id='.$item['id'],$item);
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('Du fragst den weisen Runenmeister, ob er dir vielleicht mehr über diese bunten Steine sagen kann. Du zeigst ihm deine Fundstücke von dem Berg-Plateau und ihm fallen sprichwörtlich die Augen aus dem Kopf. "`6So etwas habe ich noch nie gesehen! Ich muss diese Steine unbedingt studieren! Würdest du sie mir überlassen? Ich gebe dir hierfür auch ein Zeichen meiner Anerkennung!`%"
			`nDu willigst ein und er überreicht dir einen Beschwörungsstein, mit dem es möglich sein soll, an bestimmten Tagen, Geister zu beschwören.');
			addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			break;
		} //end Quest 17

	case 18:
		{ //trigger in friedhof.php
			$indate = getsetting('gamedate','0005-01-01');
			$date = explode('-',$indate);
			$monat = $date[1];
			$tag = $date[2];
			$time=getgametime(true);
			if($monat==10 && $tag==31 && $time>'18:00')
			{ //Samhain
				output('Samhain`0, die Nacht der Toten. Welcher Zeitpunkt wäre besser für eine Beschwörung geeignet als dieser?
				`nLeider bist du nicht der Einzige, der auf diese Idee gekommen ist. Zwar gelingt es dir, den einen oder anderen Geist herbeizuholen, jedoch hast du nicht deren ungeteilte Aufmerksamkeit.
				`nNaja, immerhin ist Samhain ein Fest und auf Festen wird getanzt. Das machst du dann auch mit den anderen Totenbeschwörern und den Geistern bis spät in die Nacht.');
			}
			elseif($time<'07:00' || $time>'18:00')
			{ //nachts
				if($settings['weather']==Weather::WEATHER_FOGGY && !isset($session['daily']['exchangequest']))
				{ //neblig
					$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
					$item['name'] = '1 Muschel';
					$item['description'] = 'Eine große Muschel. Wenn man sie ans Ohr hält hört man das Meer rauschen.';
					$item['gold'] = 0;
					$item['gems'] = 1;
					item_set('id='.$item['id'],$item);
					item_add($session['user']['acctid'],'zbrtafel');
					$session['user']['exchangequest']++;
					debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
					$row=db_fetch_assoc(db_query('SELECT name FROM valhalla ORDER BY rand() LIMIT 1'));
					output('In einer Nacht-und-Nebel-Aktion schleichst du dich auf den Friedhof, um die Toten zu beschwören. Da passiert es: Der Geist von '.$row['name'].'`% erscheint. Offenbar bist du kein geübter Totenbeschwörer, denn vor Schreck lässt du die Zaubertafel fallen, welche zu allem Übel auch noch zerbricht.
					`nDu hast gehört dass man die Geister zu verschiedensten Dingen befragen kann. Doch auch hier geschieht etwas Unerwartetes. Der Geist von '.$row['name'].'`% hat nämlich eine Bitte: Er möchte ein letzes mal sein früheres Haus sehen. Um vom Friedhof wegzukommen braucht er aber die Hilfe eines Lebenden.
					`n`nDa du ja in der Vergangenheit schon öfter deine Gutmütigkeit bewiesen hast kannst du auch dem Geist diesen Wunsch nicht abschlagen. Also führst du ihn ins Wohnviertel zu seinem früheren Haus. Zum Dank zeigt er dir ein geheimes Versteck. Du öffnest es und findest eine Muschel darin.
					`nDu willst gerade fragen was es mit dieser Muschel auf sich hat, doch der Geist ist schonwieder verschwunden.');
					addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
				}
				else
				{ //kein Nebel oder heute schon versucht
					output('In einer Nacht-und-Nebel-Aktion schleichst du dich auf den Friedhof, um die Toten zu beschwören. Doch so lange du es auch versuchst, es passiert überhaupt nichts.
					`nDu vertrödelst einen Waldkampf.');
					$session['daily']['exchangequest']=true;
					$session['user']['turns']--;
					if($session['user']['turns']<0)
					{
						output('`nAch du hast gar keinen Waldkampf mehr? Gut, dann vertrödelst du eben einfach nur Zeit...');
						$session['user']['turns']=0;
					}
				}
			}
			else
			{ //tagsüber
				output('Am hellichten Tag wird eine Beschwörung der Geister wenig Erfolg haben.');
			}
			addnav('F?Zurück zum Friedhof','friedhof.php');
			break;
		} //end Quest 18

	case 19:
		{ //trigger in fish.php (2x)
			if($_GET['op']=='give')
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 Perlenkette';
				$item['description'] = 'Ein wirklich exzellentes Stück.';
				$item['gold'] = 0;
				$item['gems'] = 3;
				item_set('id='.$item['id'],$item);
				output('Die Meerjungfrau nimmt die Muschel an sich. Dann bittet sie dich, kurz zu warten und taucht ab. Einige Momente später erscheint sie wieder an der Oberfläche und kann nun ohne Bedenken den gesamten Oberkörper aus dem Wasser ragen lassen, da dieser nun wieder ordnungsgemäß bedeckt ist. (Schade eigentlich...) Nachdem sie ihre neue Bekleidung betrachtet hat sagt sie: "`FZwar ist die Farbe nicht ganz die, die zur anderen Seite passende würde, aber vielleicht kann ich hiermit ja einen neuen Modetrend umsetzen. In jedem Fall hast du mich vor peinlichen Situationen gerettet, also nimm dies als ein Zeichen meines Dankes.`%" Sie nimmt ihre Perlenkette ab und drückt sie dir in die Hand. Ehe du dich bedanken kannst ist sie auch schon wieder in den Tiefen des Sees verschwunden.');
				$session['user']['charm']++;
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			}
			else
			{
				output('Du willst gerade deine Angel auswerfen, als eine Meerjungfrau den Kopf aus dem Wasser steckt. Sie sagt "`FIch bin an einem Felsen hängen gebieben, wobei mein linkes Austernkörbchen zu bruche gegangen ist. Ich bin vollkommen entblöst und möchte nicht, dass mich jemand so sieht. Kannst du mir bitte helfen?`%"
			`nDu kramst in deinem Beutel und findest eine Muschel, welche du ihr zeigst und fragst sie ob es hiermit in Ordnung zu bringen wäre. Fröhlich nickt sie mit dem Kopf.');
				addnav('`%Muschel geben`0','exchangequest.php?op=give');
			}
			addnav('Weiter angeln','fish.php');
			break;
		} //end Quest19

	case 20:
		{ //trigger in special/castle.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Flacon';
			$item['description'] = 'Eine edel aussehende Glasflasche mit stark riechendem Inhalt. Es scheint Parfum zu sein, aber kein gewöhnliches aus dem Geschenkeladen.';
			$item['gold'] = 1000;
			$item['gems'] = 4;
			item_set('id='.$item['id'],$item);
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('Du bietest der Dame deine Perlenkette an, welche sie sogleich umlegt. "`7Ja! Genau so etwas hat noch gefehlt um meine Schönheit perfekt zu machen!`%"
			`nAls Dank überreicht sie dir eine kleine Phiole, mit einem durftenden Gemisch.
			`n`nDir fällt auf dass die Dame wirklich große Ähnlichkeit mit der Nixe hat, mit der Perlenkette ist das eigentlich unübersehbar. Deshalb vermutest du, dass ihr Geschenk irgendeine Bedeutung im Wasser hat.');
			$session['user']['specialinc']='castle.php';
			$village=false;
			addnav('Zurück','forest.php?op=medicine');
			addnews('`^'.$session['user']['name'].' `&hat eine gute Tat vollbracht.`0');
			break;
		} //end Quest 20

	case 21:
		{ //trigger in well.php
			if($_GET['op']=='shades')
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 Steckbrief';
				$item['description'] = 'Schwarze Drachen sind gefährlicher und haben einen zehnmal selteneren Erscheinungszyklus als Grüne Drachen. Zu finden sind sie oftmals in großen Höhen, dort wo Himmel und Erde zusammenstoßen.';
				$item['gold'] = 10;
				$item['gems'] = 0;
				item_set('id='.$item['id'],$item);
				output('`$Als du im Totenreich ankommst empfängt dich ein Geist, der dir erzählt, dass ihn das selbe Schicksal ereilte wie dich und man diesem Brunnenmonster ein für alle Mal den Garaus machen sollte.
				`n`nEr verrät dir, um den Brunnenmonster-Vernichtungstrank zu brauen braucht man einen `&Zahn des `ySchwarzen Drachen`$. Dieser ist allerdings sehr schwer zu bekommen.
				`nEr gibt dir einen Zettel, auf dem der gesuchte Gegenstand beschrieben ist. Sein Vortrag endet mit den Worten: "`7Ich werde hier auf dich warten. Bringst du mir den `&Zahn des `ySchwarzen Drachen`7, so werde ich dich reich belohnen.`$"');
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				addnav('Halle verlassen','shades.php');
			}
			else
			{
				output('Hast du dich also tatsächlich dazu hinreißen lassen, in den Brunnen zu steigen...
				`nVerzweifelt suchst du nun in deinem Inventar irgendetwas, was dich aus dieser mieslichen Lage retten könnte. Doch du findest einfach nichts geeignetes.
				`n`nSchließlich fällt dir die Parfumflasche in die Hände. Sie wird dir mit Sicherheit nicht helfen, hier rauszukommen. Du willst sie gerade wieder wegpacken als sie dir aus den Händen gleitet und auf den Steinen zerschellt. Wenig später hörst du ein angewidertes Knurren aus den Tiefen des Brunnens. 
				`nDas Brunnenmonster scheint von diesem Geruch ganz und gar nicht angetan und zieht dich wütend in die Tiefe.
				`n`n`$Du bist tot.
				`n`n`%Sterbend schaust nach oben, das letzte was du siehst ist `&der Ring.`n`n');
				addnews($session['user']['name'].'`# wurde beim Nacktbaden im Dorfbrunnen von etwas erwischt!`0');
				killplayer(0,0,0,'exchangequest.php?op=shades','Na prima!');
			}
			$village=false;
			break;
		} //end Quest21

	case 22:
		{ //trigger in boss_modules/black_dragon.php, Irrweg in stables.php
			if($_GET['op']=='dragon')
			{
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				$item['name'] = '1 `&Zahn des `ySchwarzen Drachen`0';
				$item['description'] = 'Der Zahn des Schwarzen Drachen. Er hat eine beachtliche Größe.';
				$item['gold'] = 10000;
				$item['gems'] = 10;
				item_set('id='.$item['id'],$item);
				output('Du guckst in deinen Beutel und findest darin einen riesigen Zahn des Schwarzen Drachen. Dunkel kannst du dich daran erinnern, dass du diesen Zahn in die Unterwelt bringen sollst.');
				$session['user']['exchangequest']++;
				debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
				addnav('Weiter','village.php');
			}
			elseif($_GET['op']=='stables')
			{
				output('Du fragst Merik ob er dir eventuell einen Zahn von einem Schwarzen Drachen besorgen kann. Merik kramt eine Weile in allerlei Kisten und Schachteln herum, um dir schließlich einen `&Milchzahn von einem `ySchwarzen Drachen`% zu präsentieren. Er verlangt dafür `^5000 Gold`% und `#5 Edelsteine`%.');
				addnav('`%Kaufen`0','exchangequest.php?op=buy');
				addnav('Zurück','stables.php');
			}
			elseif($_GET['op']=='buy')
			{
				$goldcost*=$_GET['count'];
				$gemcost*=$_GET['count'];
				if($Char->gold >= 5000 && $Char->gems >= 5)
				{
					$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
					$item['name'] = '1 kleiner Drachenzahn';
					$item['description'] = 'Der Milchzahn eines jungen Schwarzen Drachen. Irgendwie sieht der knuffig aus. Schwarze Drachen sind gefährlicher und haben einen zehnmal selteneren Erscheinungszyklus als Grüne Drachen. Zu finden sind sie oftmals in großen Höhen, dort wo Himmel und Erde zusammenstoßen.';
					$item['gold'] = 5000;
					$item['gems'] = 5;
					item_set('id='.$item['id'],$item);
					output('Du zählst das Gold und die Edelsteine ab und Merik überreicht dir den Zahn. Aber Moment mal, hatte der Geist irgendwas von Milchzahn gesagt?! Naja immerhin hast du ihn jetzt.');
					$Char->gold -= 5000;
					$Char->gems -= 5;
				}
				else
				{
					output('Das kannst du dir nicht leisten!');
				}
				addnav('S?Zu den Ställen','stables.php');
				addnav('M?Zurück zum Marktplatz','market.php');
			}
			else
			{
				output('Du triffst dich mit dem zahnsuchenden Geist, musst jedoch feststellen dass du mit leeren Händen dastehst. Mag ja sein, dass dein lebloser Körper in der Oberwelt einen `&Zahn des`ySchwarzen Drachen`% hat, das nützt dir hier aber herzlich wenig.');
				addnav('Zurück','halle_der_geister.php');
			}
			$village=false;
			break;
		} //end Quest22

	case 23:
		{ //trigger in special/stonehenge.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Nebelflasche';
			$item['description'] = 'Im Inneren dieser Flasche zieht ein blauer Nebel. Man kann es stundenlang beobachten, aber sonst scheint es zu nichts nütze.';
			$item['gold'] = 1000;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			output('`$Du findest dich direkt vor den Füßen des zahnsuchenden Geistes wieder. Offenbar hast du einen Weg gefunden, den `&Zahn des `ySchwarzen Drachen`$ in die Unterwelt zu transportieren. Also überreichst du ihm diesen Zahn und erfüllst damit deinen Teil der Abmachung.
			`nAls Zeichen seines Dankes überreicht dir der Geist eine Flasche mit `#blauem Nebel`$, die auf den ersten Blick schön aussieht, aber mehr auch nicht. Ehe du jedoch nach dem Verwendungszweck fragen kannst, ist der Geist auch schon verschwunden.');
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			addnav('Weiter','shades.php');
			$village=false;
			break;
		} //end Quest23

	case 24:
		{ //trigger in inside_houses.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Lolli';
			$item['description'] = 'Kein normaldenkendes Wesen würde auf die Idee kommen, Honig an einem Stock kristallisieren zu lassen. Und trotzdem existiert der vor dir liegende Gegenstand... Gehörst du zu dem kleinen Kreis der Auserwählten, welche die Lösung kennen?';
			$item['gold'] = 20;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('Du sortierst dein Inventar aus, wobei dir auch die verstaubte blaue Flasche in die Hände fällt. Als du den Staub wegreibst beginnt der blaue Nebel plötzlich aus der Flasche aufzusteigen und ein riesiger Dschinn macht sich vor dir breit.
			`nMit offenem Mund und voller Nervosität schaust du ihn an und er sagt dir, dass er ein Orakel ist und dir genau eine Frage beantworten wird.
			`n`nDu erinnerst dich an die damaligen Worte der Fee "`^Der Weg zum Glück beginnt meist mit einer bedeutungslos erscheinenden Kleinigkeit. Ein Samenkorn kann im Laufe der Zeit ganze Wälder hervorbringen...`%" und beschließt zu fragen, was es damit auf sich hat. Der Geist sagt sagt darauf: "`#Die Antwort auf jene Frage ist nur für wenige Auserwählte bestimmt. Dies wird dir weiterhelfen.`%" Ob dieser Worte wirft er dir einen Lolli vor die Füße und verschwindet. Völlig verdutzt hebst du den Süßkram auf und setzt dich ersteinmal, um den Schreck zu verdauen.');
			addnav('H?Zurück ins Haus','inside_houses.php');
			addnews($session['user']['name'].'`F hatte eine seltsame Begegnung mit einem Dschinn.`0');
			break;
		} //end Quest 24

	case 25:
		{ //trigger in chosenfeats.php
			$dodo=$_GET['dodo'];
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$item['name'] = '1 Brosche';
			$item['description'] = 'Eine Brosche mit 1 großer und 7 kleinen Einkerbungen.';
			$item['gold'] = 1000;
			$item['gems'] = 0;
			item_set('id='.$item['id'],$item);
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('`tDu gibst Dodo den Lolli und er brummt vergnügt. "`5Ohh '.$session['user']['name'].'`5 ist ein toller Freund! Dodo will '.$session['user']['name'].'`5 etwas schenken. Dodo passt schon sehr lange Zeit auf etwas auf, aber Dodo hat keine Lust mehr, aufzupassen.`t"
			`n`nEr drückt dir eine Brosche in die Hand, auf welcher sich 7 kleine Einkerbungen um eine große befinden. ');
			$dodo+=30;
			addnav('Was nun?');
			addnav('Angreifen','chosenfeats.php?op=dodo&act=attack&dodo='.$dodo);
			addnav('Knuddeln','chosenfeats.php?op=dodo&act=knuddel&dodo='.$dodo);
			addnav('Tanzen','chosenfeats.php?op=dodo&act=dance&dodo='.$dodo);
			addnav('Eine Geschichte erzählen','chosenfeats.php?op=dodo&act=tale&dodo='.$dodo);
			addnav('Spielen','chosenfeats.php?op=dodo&act=play&dodo='.$dodo);
			addnav('Kitzeln','chosenfeats.php?op=dodo&act=tinkle&dodo='.$dodo);
			if ($dodo>=200)
			{
				addnav('Dodo mitnehmen','chosenfeats.php?op=dodo&act=takeout&dodo='.$dodo);
			}
			addnav('Dodo allein lassen','thepath.php');
			addnews($session['user']['name'].'`& wurde von `5Dodo, dem Kuscheldämon`& beinahe zerquetscht!`0');
			$village=false;
			break;
		} //end Quest 25

	case 26:
		{ //trigger in racesspecial.php
			$str_raceid = $_GET['race'];
			$sql='SELECT name,name_plur,raceroom,raceroom_nav FROM races WHERE id="'.$str_raceid.'"';
			$arr_race=db_fetch_assoc(db_query($sql));
			if(in_array($str_raceid, array('hbl','zwg','ork','trl','dkl','elf','gbl')))
			{ //alte Rassen nach Tolkien sind Hobbit, Zwerg, Ork, Troll, Dunkelelf, Elf, Goblin;  Mensch kommt im nächsten Schritt
				$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
				if(mb_strpos($item['description'],$arr_race['name_plur']))
				{ //bereits dagewesen
					output('Du bittest um eine Audienz beim Stammesführer der '.$arr_race['name_plur'].', was dir jedoch keine neuen Erkenntnisse bringt.');
				}
				else
				{
					output('Du bittest um eine Audienz beim Stammesführer der '.$arr_race['name_plur'].'. Als dieser deine Brosche erblickt sagt er: "`t'.($session['user']['sex']?'Verehrte Dame':'Werter Freund').', ich sehe dass Ihr die Brosche der Elemente tragt. Wisset, dass die Kraft der alten Völker dieses Artefakt stärken kann.`%"
					`n`nMit diesen Worten überreicht er dir ein Juwel, welches exakt in eine der Einkerbungen auf der Brosche passt.');
					if(!mb_strpos($item['description'],'Stein der'))
					{
						$item['description'] = 'Eine Brosche mit sieben kleinen Einkerbungen am Rand und einer großen in der Mitte, darin befinden sich: Der Stein der '.$arr_race['name_plur'];
						$item['gems']=1;
					}
					else
					{
						$item['description'] .= ', der Stein der '.$arr_race['name_plur'];
						$item['gems']++;
					}
					if($item['gems']==7)
					{ //alle 7 Völker besucht
						$session['user']['exchangequest']++;
						debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
					}
					item_set('id='.$item['id'],$item);
				}
			}
			elseif($str_raceid=='men')
			{ //Sonderfall Menschen
				output('Du bittest um eine Audienz beim Stammesführer der '.$arr_race['name_plur'].'. Als dieser deine Brosche erblickt sagt er: "`t'.($session['user']['sex']?'Verehrte Dame':'Werter Freund').', ich sehe dass Ihr die Brosche der Elemente tragt. Wisset, dass nur die Kraft der `balten Völker`b dieses Artefakt stärken kann. Den Stein der '.$arr_race['name_plur'].' wirst du jedoch an einer anderen Stelle bekommen.`%"
			`n`nOffenbar kann dir der Stammesführer nicht weiterhelfen, also verabschiedest du dich.');
			}
			else
			{ //keine alte Rasse
				output('Du bittest um eine Audienz beim Stammesführer der '.$arr_race['name_plur'].'. Als dieser deine Brosche erblickt sagt er: "`t'.($session['user']['sex']?'Verehrte Dame':'Werter Freund').', ich sehe dass Ihr die Brosche der Elemente tragt. Wisset, dass nur die Kraft der `balten Völker`b dieses Artefakt stärken kann. Leider zählen wir '.$arr_race['name_plur'].' nicht dazu.`%"
			`n`nOffenbar kann dir der Stammesführer nicht weiterhelfen, also verabschiedest du dich.');
			}
			addnav($arr_race['raceroom_nav'],'racesspecial.php?race='.$str_raceid);
			if($arr_race['raceroom'] == 1)
			{
				addnav('W?Zurück zum Wald','forest.php');
			}
			else
			{
				addnav('W?Zum Wohnviertel','houses.php');
			}
			break;
		} //end Quest 26

	case 27:
		{ //trigger in special/forestchurch.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$arr_srch=array('Stein der Elfen', 'Stein der Dunkelelfen', 'Stein der Halblinge', 'Stein der Zwerge', 'Stein der Orks', 'Stein der Trolle', 'Stein der Goblins');
			$arr_repl=array('`@Stein der Elfen`0', '`WStein der Dunkelelfen`0', '`tStein der Halblinge`0', '`TStein der Zwerge`0', '`mStein der Orks`0', '`JStein der Trolle`0', '`/Stein der Goblins`0');
			$item['description'] = str_replace($arr_srch, $arr_repl, $item['description']);
			$item['name'] = '`~`~`~'.color_from_name('Brosche der alten Völker');
			$item['description'] .= ' und der `sStein der Weisen`0.';
			$item['gems'] = 8;
			item_set('id='.$item['id'],$item);
			$session['user']['exchangequest']++;
			debuglog('erreichte Queststufe '.$session['user']['exchangequest']);
			output('`^`c`bSieg!`b`c`2Als du zum finalen Schlag ausholen willst springt der Fremde in die Luft, wo er von einem schwarzen Flügel eingehüllt wird. "`^Bemerkenswert... du bist tatsächlich '.($session['user']['sex']?'diejenige, für die':'derjenige, für den').' meine Macht bestimmt ist.`2" sagt er zu dir.
			`n`nVöllig perplex stehst du da, als der Fremde vor dir zu stehen kommt. Er setzt einen weiteren Stein in die Mitte deiner Brosche, deren Juwelen daraufhin in den verschiedensten Farben zu leuchten beginnen, bevor er genauso spektakulär verschwindet wie er gekommen ist.
			`n`nDu hast gerade den Stein der Weisen bekommen.');
			addnews('`^'.$session['user']['name'].' `&hat sich als würdig erwiesen, den Stein der Weisen zu tragen.`0');
			addnav('W?Zurück zum Wald','forest.php');
			break;
		} //end Quest 27

	case 28:
		{ //trigger in wolkeninsel.php
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$session['user']['acctid']);
			$startdate=$item['special_info'];
			$gamedate=getsetting('gamedate','0005-01-01');
			$arr_startdate = explode('-',$startdate);
			$arr_gamedate = explode('-',$gamedate);
			$duration=((int)$arr_gamedate[0]-(int)$arr_startdate[0])*12 + (int)$arr_gamedate[1]-(int)$arr_startdate[1] +1;
			$duration=round($duration/1.2)/10;
			$item['gold'] = 0;
			$item['gems'] = 10;
			$item['special_info'].=' bis '.$gamedate;
			$item['tpl_id'] = 'unikat';
			item_set('id='.$item['id'],$item);
			$session['user']['exchangequest']=30;
			addhistory('`2Aufnahme in den `^Kreis der Weisen');
			//Speichern in der history-Tabelle für user 1300000 (Eichhörnchen-Dummyuser)
			$str_savedata=getgamedate().';'.$session['user']['name'].';'.$duration.' Jahre;acctid='.$session['user']['acctid'];
			addhistory($str_savedata,1,1300000);
			//Speichern in Datei, dieser Teil wird vermutlich entfallen
			//	$str_savedata.="\n"; //letzter Teil muss in Anführungszeichen stehen sonst funktioniert der Zeilenumbruch nicht
			//	$file_highscore=fopen('exchangequest_userlist.dat','a+');
			//	flock($file_highscore,2);
			//	fputs($file_highscore,$str_savedata);
			//	flock($file_highscore,3);
			//	fclose($file_highscore);

			output('`@Die Fee sagt dir nun, du sollst die Brosche vor dem Baum niederlegen. Du tust wie geheißen und wie von Geisterhand erhebt sich der Baum mitsamt Wurzeln aus der Erde, um einige Meter über der Erde in der Luft zu schweben. Wo bis vor wenigen Augenblicken noch der Baum stand, ist nun der Eingang zu einem unterirdischen Tempel zu sehen. Die Fee fordert dich auf, in diesen Tempel zu gehen und zu meditieren. Also betrittst du den Tempel und nach einigen Schritten erreichst du einen Schrein.
			`n`nWährend du meditierst erstrahlt plötzlich der Raum in hellem Licht und eine Stimme ertönt: "`FWillkommen im Kreis der Weisen, '.$session['user']['login'].'!`@"
			`n`nDu hast die "`rBr`vos`Fch`*e `fder alt`*en `FVö`vlk`rer`@" geweiht!');
			addnews('`^'.$session['user']['name'].' `&hat nach einem langen, beschwerlichen Weg die "`FBrosche der alten Völker`&" geweiht.`0');

			break;
		} //end Finale

	default:
		{
			output('Huh? Was hast du denn angestellt um hier zu landen? Fehlercode '.$session['user']['exchangequest']);
			break;
		}
}
if($village!==false)
{
	addnav('d?Zurück zur Stadt','village.php');
}
page_footer();
?>