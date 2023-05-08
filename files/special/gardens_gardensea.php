<?php

/********************************
*                               *
*  Der Gartensee                *
*  by Lestat @ www.logdwelt.de  *
*  Texte by Akasha              *
*  04.Februar 2006              *
*                               *
*  lestat@fahr-zur-hoelle.org   *
*                               *
*********************************


Ein kleiner See, der vom Garten aus erreicht werden kann, und an dem der Spieler spazieren gehen können.
Inspiriert von einigen anderen Skripten, aber komplett selbst geschrieben.

Wurde für Atrahor als Special im See eingefügt und tritt nur noch sehr selten auf. Somit kann ein Eintrag in der DB gespart werden.
Außerdem wurde auf Atrahor Methoden umgestellt, outputs entfernt,...!

*/

page_header('Der Gartensee');
$str_filename = basename($_SERVER['SCRIPT_FILENAME']);
$str_backlink = 'gardens.php';
$str_endspecial = $str_filename.'?op=endspecial';
$str_backtext = 'Zurück zum Garten';

$session['user']['specialinc'] = basename(__FILE__);

$str_output = '';

switch ($_GET['op'])
{

	case '':
		$str_output .= '`c`b`9Der Gartensee`c`b`n`n
		Du schlenderst den Fluss entlang, in den hinteren Bereich des Gartens, und kommst irgendwann an ein verziertes und von Kletterpflanzen überwuchertes Gartentor.
		Sobald du durch das Tor trittst, erstreckt sich vor dir eine wunderschöne, weite Landschaft mit einem riesigen See, in den der Fluss mündet.
		Um den vorderen Bereich des Sees verläuft ein schöner Weg, welcher mit Bänken gesäumt ist.
		Das Wasser glitzert herrlich und lädt zum Schwimmen ein.`n
		Doch weiter im Hintergrund gibt es auch unerforschte Gebiete. Eine kleine, überwucherte Insel mitten im See zieht deine Aufmerksamkeit auf sich und macht dich neugierg, was es wohl alles darauf zu entdecken gibt.
		Der Grossteil des Sees wird vom Wald umgrenzt. Sofort ermahnst du dich selber, dich nicht zu weit vom vorderen Bereich zu entfernen, wenn du nicht auf Gefahren stossen willst.`n`n`n';
		$str_output .= create_lnk('Ich trotze jeder Gefahr, auf gehts zum Erkunden!',$str_filename.'?op=event',true,true,'',false,'Spazieren gehen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n';
		$str_output .= create_lnk('Lieber zurück in den Garten!',$str_endspecial,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
		break;
	case 'endspecial':
		$session['user']['specialinc'] = '';
		redirect($str_backlink);
		break;
	case 'event':
		switch (e_rand(1,9))
		{
			case 1:
				$str_output .= '`c`b`9Bank`c`b`n`n
				`tDu kommst an eine der vielen Bänke, die um den See herum stehen. Möchtest Du Dich hinsetzen oder doch lieber weiter gehen?`n`n';

				$str_output .= create_lnk('Setz Dich hin',$str_filename.'?op=bank',true,true,'',false,'Hinsetzen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n';
				$str_output .= create_lnk('Geh weiter',$str_filename.'?op=weitergehen',true,true,'',false,'Weitergehen',CREATE_LINK_LEFT_NAV_HOTKEY);
				break;
			case 2:
				$str_output .= '`c`b`9Bucht`c`b`n`n
				`#Wie du so den Weg entlang läufst, kommst du an eine, zum schwimmen einladende, Bucht.
				Traust du dich ins Wasser, oder hast du zu viel Angst vor möglichen Gefahren?`n`n';

				$str_output .= create_lnk('Ach was, ab ins Wasser',$str_filename.'?op=schwimmen',true,true,'',false,'Schwimmen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n';
				$str_output .= create_lnk('Ne, da schrumpelt doch meine Haut, ich geh weiter',$str_filename.'?op=weitergehen',true,true,'',false,'Weitergehen',CREATE_LINK_LEFT_NAV_HOTKEY);
				break;
			case 3:
				$str_output .= '`c`b`9Boot`c`b`n`n
				`8Was ist denn das? Du entdeckst am Ufer, halb vom Schilf verdeckt, ein etwas mitgenommen aussehendes, kleines Ruderboot.`n`n
				`gOb das Ding noch seetüchtig genug ist, um mich sicher zur Insel zu bringen?`n`n';

				$str_output .= create_lnk('Ich bin leicht wie eine Feder',$str_filename.'?op=boot',true,true,'',false,'Ich versuchs!',CREATE_LINK_LEFT_NAV_HOTKEY).'`n';
				$str_output .= create_lnk('Hng, ich weiß nicht ob sich das mit meinem Gewicht vereinbaren lässt',$str_filename.'?op=weitergehen',true,true,'',false,'Weitergehen',CREATE_LINK_LEFT_NAV_HOTKEY);
				break;
			case 4:
				$str_output .= '`c`b`9Baum des Lebens`c`b`n`n
				`qWährend deines Spaziergangs entdeckst du einen prachtvollen Baum.
				Die Äste biegen sich unter der Last der großen herrlichen Früchte, die an ihm wachsen.`n`n';

				$str_output .= create_lnk('Sollte der nicht eigentlich woanders sein?',$str_filename.'?op=baum',true,true,'',false,'Frucht pflücken',CREATE_LINK_LEFT_NAV_HOTKEY).'`n';
				$str_output .= create_lnk('Das ist mir nicht geheuer...',$str_filename.'?op=weitergehen',true,true,'',false,'Weitergehen',CREATE_LINK_LEFT_NAV_HOTKEY);
				break;
			case 5:
/*				$str_output .= '`c`b`9Seeungeheuer`c`b`n`n
				`2Ist das nicht Nessie? Ist ja auch egal - jedenfalls greift dich ein schnaubendes Seeungeheuer an!`n`n';

				$str_output .= create_lnk('Komm nur her duuuuu',$str_filename.'?op=ungeheuer',true,true,'',false,'Attacke!',CREATE_LINK_LEFT_NAV_HOTKEY);
				break;
*/			case 6:
				$str_output .= '`c`b`9Fee`c`b`n`n
				`5Du begegnest einer Fee. Sie verlangt einen Edelstein von dir. Was machst du?`n`n';
				if ($session['user']['gems']==0)
				{
					$str_output .= '`%Du zeigst der Fee deine leeren Taschen, und sie lässt dich weiter ziehen.`n';
					$str_output .= create_lnk('Ansonsten gebe ich Feen ja IMMER was...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
					$session['user']['specialinc'] = '';
				}
				else
				{
					$str_output .= create_lnk('Na klar...kann die sowas überhaupt tragen?',$str_filename.'?op=feeja',true,true,'',false,'Gib ihr Einen',CREATE_LINK_LEFT_NAV_HOTKEY).'`n';
					$str_output .= create_lnk('Nix ist, MEINS!',$str_filename.'?op=feenein',true,true,'',false,'Gib ihr Keinen',CREATE_LINK_LEFT_NAV_HOTKEY);
				}
				break;
			case 7:
				$str_output .= '`c`b`9Alter Mann`c`b`n`n
				`6Dir kommt ein alter Mann entgegen. Er trägt auch noch einen Stock bei sich. `^Ist das nicht etwa der aus dem Wald?
				`6Langsam läufst du auf ihn zu. `^Doch! Das ist er. Aber welcher? Der mit dem schönen, oder der mit dem hässlichen Stock?
				`6Während du noch überlegst, hat dich der Alte schon erreicht und holt zum Schlag aus. Hin- und hergerissen, ob du deinen
				Kopf hinhalten, oder dich ducken sollst, zappelst du ziemlich dämlich aussehend vor dem Alten rum.`n`n
				Ein dumpfer Schmerz verrät dir aber, dass der Alte dir die Entscheidung schon abgenommen hat.`n';
				switch (e_rand(1,2))
				{
					case 1:
						$str_output .= '`6Du kannst es schon fühlen, dich hat der hässliche Stock erwischt.
						Grummelnd, den Kopf reibend und den Alten verfluchend gehst du davon.`n`n
						`^Du verlierst einen Charmepunkt!`n`n';
						$session['user']['charm']--;
						break;
					case 2:
						$str_output .= '`^Aua! `6Scheinbar muss, wer schön sein will, wirklich leiden.`n`n`^Du erhältst einen Charmepunkt!`n`n';
						$session['user']['charm']++;
						break;
				}
				$str_output .= create_lnk('Wird Zeit den mal wieder ordentlich zu verhauen...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 8:
				$str_output .= '`c`b`9Verlaufen`c`b`n`n
				`2Schon peinlich, aber wahr. Du hast es tatsächlich geschafft dich im Garten zu verlaufen!
				Dir ist das ganze so peinlich, dass du dir wünscht, du wärst tot. Schön dass gerade den Moment wohl irgendein Gott vorbeikommt und sich denkt`n`n
				`tHey hey hey, tun wir mal was gutes und erfüllen den Wunsch dieses kleinen Sterblichen`2`n`n
				`@Danke, das bist du dann also auch fast, als du endlich wieder den Weg zurück findest.`n`n';
				$session['user']['hitpoints']=1;
				$str_output .= create_lnk('Warum immer ich...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 9:
				$str_output .= '`c`b`9Götter`c`b`n`n
				`#Ein heller Lichtstrahl scheint auf dich nieder. Eine göttliche Stimme erklingt:
				`&Lange schon beobachte ich dich. Du schlenderst im Garten umher und willst ein Held sein?
				Geh lieber in den Wald und erschlage ein paar Monster!
				`n`n`^Du erhältst 2 Waldkämpfe!`n`n';
				$session['user']['turns']+=2;

				$str_output .= create_lnk('Klar, warum nicht...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
		}
		break;

	case 'weitergehen':
		$str_output .= '`c`b`9Spaziergang am See`c`b`n`n`6Na das war ja was...`n';
		$str_output .= create_lnk('Du setzt deinen Weg fort.',$str_filename.'?op=event',true,true,'',false,'Weitergehen',CREATE_LINK_LEFT_NAV_HOTKEY);
		break;

	case 'bank':
		$str_output .= '`c`b`9Bank`c`b`n`n';
		switch (e_rand(1,6))
		{
			case 1:
				$str_output .= '`tDu möchtest dich gerade setzen, da fällt dir ein Glitzern unter der Bank auf.`n`n
				Als du nachsiehst was da ist, findest du `^einen Edelstein!`n';
				$session['user']['gems']+=1;

				$str_output .= create_lnk('Ja wo kommst Du denn her? Ab in meinen Beutel!',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 2:
			case 3:
				$int_loose_hp = (round($session['user']['hitpoints']*0.1));
				$str_output .= '`tDu setzt dich auf die grobe Holzbank, und spürst einen stechenden Schmerz.`n`n
				Beim Setzen hast du dich an einen großen Holzsplitter verletzt, und verlierst '.$int_loose_hp.' Lebenspunkte.`n`n';
				$session['user']['hitpoints']-=$int_loose_hp;

				$str_output .= create_lnk('Aua, immer ich!',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 4:
			case 5:
				$goldfund=(e_rand(100,200));
				$str_output .= '`tDu setzt dich, als du plötzlich etwas hartes spürst. Scheinbar hat da jemand seinen Goldbeutel verloren.
				`^Du findest '.$goldfund.' Gold.`n';
				$session['user']['gold']+=$goldfund;
				$str_output .= create_lnk('Hehe, Glück gehabt!',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 6:
				$int_loose_exp = round($session['user']['experience']*0.03);
				$str_output .= '`tDu gehst auf die Bank zu, und rutscht plötzlich aus.
				Dein Kopf knallt gegen die Bank, aber ausser dass du dein Gehirn ordentlich durchschüttelst, verletzt du dich nicht.`n`n
				`^Du verlierst '.$int_loose_exp.' Erfahrungspunkte.`n`n
				`tDu denkst dir noch: `@"Hoffentlich hat das niemand gesehen."`t und gehst eilig davon.`n`n';
				$session['user']['experience']-=$int_loose_exp;

				$str_output .= create_lnk('Ich habe einen Brummschädel wie nach 10 Ale...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
		}
		break;

	case 'schwimmen':
		$str_output .= '`c`b`9Bucht`c`b`n`n';
		switch (e_rand(1,3))
		{
			case 1:
				$str_output .= '`#Wieso versuchst du es überhaupt? Etwa vergessen, dass du in deiner Rüstung `bgar nicht`b schwimmen kannst?`n`n
				`^Du bist ertrunken!`n`n';
				killplayer();

				$str_output .= create_lnk('Blubbblubb, hallo Ramius...','news.php',true,true,'',false,'Tägliche News',CREATE_LINK_LEFT_NAV_HOTKEY);
				addnews($session['user']['name'].' kann nicht mit '.($session['user']['sex']?'ihrer':'seiner').' Rüstung schwimmen...wie ärmlich!');
				$session['user']['specialinc'] = '';
				break;
			case 2:
				$str_output .= '`#Eine reizende Nixe segnet dich.`n`n
				`^Du hast für die nächsten 30 Runden einen stärkeren Angriff!`n';
				$session['bufflist']['Segen der Nixe'] = array('name'=>'`9Segen der Nixe','rounds'=>30,'wearoff'=>'Die Kraft der Nixe verlässt dich.`0','defmod'=>1,'atkmod'=>1.1,'minioncount'=>1,'mingoodguydamage'=>3,'maxgoodguydamage'=>$session['user']['level'],'roundmsg'=>'Du fühlst den Segen der Nixe und schlägst härter zu.`0','activate'=>'offense');
				$str_output .= create_lnk('Ich mag Nixen...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 3:
				$str_output .= '`#Du fühlst dich sauberer und gestärkt.`n`n
				`^Du bist attraktiver und erhältst 1 Charmepunkt!`n';
				$session['user']['charm']+=1;
				$str_output .= create_lnk('Und jetzt triefend wieder in den Garten zurück!',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
		}
		break;

	case 'boot':
		$str_output .= '`c`b`9Boot`c`b`n`n';
		switch (e_rand(1,7))
		{
			case 1:
				$str_output .= '`@"Vielleicht ist das Boot doch nicht ganz so dicht?!" `6denkst du dir, als dir das Wasser schon bis zu den Waden steht. Oh, du hast da ja auch zufällig eine besonders schwere Rüstung an...`n`n
				`^Du bist schneller ertrunken als du die Serverregeln lesen kannst`n`n';
				killplayer();

				$str_output .= create_lnk('Blubbblubb, hallo Ramius...','news.php',true,true,'',false,'Tägliche News',CREATE_LINK_LEFT_NAV_HOTKEY);
				addnews($session['user']['name'].' war nicht ganz dicht...also das Boot...also irgendwie.');
				$session['user']['specialinc'] = '';
				break;
			case 2:
			case 3:
				$str_output .= '`@"Vielleicht ist das Boot doch nicht ganz dicht?!" `6denkst du dir, als dir das Wasser schon bis zu den Waden steht. Oh, du hast da ja auch zufällig eine besonders leichte Rüstung an...`n`n
				Halb tot erreichst du das Ufer.`n`n
				`^Du hast die Hälfte deiner Lebenspunkte verloren!`n`n';
				$session['user']['hitpoints']*=0.5;
				$str_output .= create_lnk('Das war knapp.',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 4:
			case 5:
				$str_output .= '`6Ein heftiger Sturm zwingt dich ans Ufer zurück. Ein Sturm auf einem See? Hey, wenn Ungeheuer in Brunnen hausen, dann gibts auch Stürme in kleinen niedlichen Waldseen,
				die deine Frisur völlig zerzausen. `^Du verlierst 2 Charmepunkte!`n';
				$session['user']['charm']-=2;
				$str_output .= create_lnk('Immer ich...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 6:
				$gold=e_rand(500,1000);
				$str_output .= '`6Erschöpft aber glücklich erreichst du die Insel. Auf deiner kurzen Entdeckungstour findest du einen Schatz. Wenigstens hat sichs gelohnt.`n`n
				`^Du findest '.$gold.' Goldmünzen!`n`n';
				$session['user']['gold']+=$gold;
				$str_output .= create_lnk('Und jetzt wieder zurück? Och man...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 7:
				$steine=e_rand(1,2);
				$str_output .= '`6Erschöpft aber glücklich erreichst du die Insel. Auf deiner kurzen Entdeckungstour findest du einen Schatz. Wenigstens hat sichs gelohnt.`n`n
				`^Du findest '.$steine.' Edelsteine!`n`n';
				$session['user']['gems']+=$steine;
				$str_output .= create_lnk('Und jetzt wieder zurück? Och man...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
		}
		break;

	case 'baum':
		$str_output .= '`c`b`9Baum des Lebens`c`b`n`n `7(Was zum Geier macht der denn hier?)`n';
		switch (e_rand(1,9))
		{
			case 1:
				$str_output .= '`qAls du dich dem Baum näherst, stolperst du über eine Wurzel und schlägst mit dem Kopf auf einem spitzen Stein auf.`n`n
				`^Du überlebst nur knapp.`n`n';
				$session['user']['hitpoints']=1;

				$str_output .= create_lnk('F...Mist',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 2:
				$str_output .= '`qDu möchtest gerade eine Frucht pflücken, als dir ein Glitzern am Boden auffällt.`n
				`^Du findest einen Edelstein!`n`n
				`6Vor lauter Freude über deinen Fund vergisst du den Baum und gehst weiter.`n';
				$session['user']['gems']++;
				$str_output .= create_lnk('Ab in meinen Beutel, mein Kleiner',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 3:
				$str_output .= '`qDu pflückst eine Frucht und beisst herzhaft hinein.
				Leider schmeckt sie sehr bitter.`n`n
				`^Du fühlst dich schwächer. `qDie Frucht war wohl giftig.`n';
				$int_loose_hp = (round($session['user']['hitpoints']*0.9));
        $session['user']['hitpoints']-=$int_loose_hp;
				$str_output .= create_lnk('Das kann unmöglich der Baum des Lebens gewesen sein.',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 4:
				$str_output .= '`qDu pflückst eine Frucht und beisst herzhaft hinein.
				Leider schmeckt sie sehr bitter.`n`n
				`^Von diesem Ding wird dir sicher noch eine ganze Weile schlecht sein.`n';
				$session['bufflist']['Übelkeit'] = array('name'=>'`@Übelkeit','rounds'=>20,'wearoff'=>'Endlich gehts dir besser.`0','defmod'=>0.9,'atkmod'=>0.9,'minioncount'=>1,'mingoodguydamage'=>3,'maxgoodguydamage'=>$session['user']['level'],'roundmsg'=>'Die blöde Frucht fährt dir immer noch im Bauch herum.`0','activate'=>'offense');
				$str_output .= create_lnk('Ist mir übel...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 5:
				$str_output .= '`qDu pflückst eine Frucht und beisst herzhaft hinein.
				Sie schmeckt wunderbar süß und hat heilende Kräfte.`n`n';
				$session['user']['maxhitpoints']++;
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
				$str_output .= create_lnk('So lob ich mir das!',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 6:
				$str_output .= '`qDu pflückst eine Frucht und beisst herzhaft hinein.
				Sie schmeckt wunderbar süß und kräftigt Dich.`n
				Du fühlst dich erfrischt und erhältst 2 Waldkämpfe!`n`n';
				$session['user']['turns']+=2;
				$str_output .= create_lnk('Na dann, ab in den Wald',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 7:
				$str_output .= '`qDu pflückst eine Frucht und beisst herzhaft hinein.
				Sie schmeckt wunderbar süß.`n
				Als so-gut-wie Vegetarier bist du jetzt so richtig Sexy.`n
				`^Du erhältst einen Charmepunkt!`n`n';
				$session['user']['charm']++;
				$str_output .= create_lnk('Rrrrrr...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 8:
				if ($session['user']['gems']>0)
				{
					$str_output .= '`qDu streckst dich nach einer Frucht, da stürzt sich plötzlich das Äffchen aus dem Wald auf dich und klaut dir einen Edelstein.`n`n
					Der Ärger darüber verdirbt dir den Appetit.`n`n';
					$session['user']['gems']--;
				}
				else
				{
					$str_output .= '`qDu streckst dich nach einer Frucht, da stürzt sich plötzlich das Äffchen aus dem Wald auf dich.`n`n
					`^Hättest du nun Edelsteine bei dir, dann hätte es dir einen geklaut.`n
					`qDurch die Ablenkung vergisst du ganz, dass du eine Frucht wolltest.`n`n';
				}
				$str_output .= create_lnk('Dieses Mistding...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;
			case 9:
				$gold=e_rand(1000,2000);
				$str_output .= '`qDu pflückst eine Frucht und als du gerade hineinbeissen willst, bemerkst du, sie ist ein einzelner riesiger Diamant.
				Gerade als du darüber sinnierst wie unheimlich wertvoll dieser Diamant wohl ist kommt ein Händler vorbeigerauscht, entreisst den Diamant deinen Händen und drückt dir dafür '.$gold.' Gold hinein.
				Ungläubig schaust du ihm hinterher.`n`n';
				$session['user']['gold']+=$gold;
				$str_output .= create_lnk('Prompte Bedienung hier...',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
				$session['user']['specialinc'] = '';
				break;

		}
		break;

	case 'feeja':
		$str_output .= '`c`b`9Fee`c`b`n`n';
		$session['user']['gems']--;
		switch (e_rand(1,2))
		{
			case 1:
				$str_output .= '`%Die Arme hat in Wirtschaftslehre nicht sonderlich gut aufgepasst. Sie gibt dir zum Dank `^2 Edelsteine!`n`n';
				$session['user']['gems']+=2;
				break;
			case 2:
				$str_output .= '`5Dankeschön!`t ist ihre Antwort, und sie schwirrt davon.`n`n';
				break;
		}
		$str_output .= create_lnk('Bitte, gern geschehen.',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
		$session['user']['specialinc'] = '';
		break;

	case 'feenein':
		$str_output .= '`c`b`9Fee`c`b`n`n
		`5Na dann halt nicht! `tschmollt sie dich an und flattert wütend davon.`n`n';
		$str_output .= create_lnk('Pff, könnte ja jeder kommen.',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
		$session['user']['specialinc'] = '';
		break;

	case 'ungeheuer':
		$str_output .= '`c`b`9Seeungeheuer`c`b`n`n';
		$badguy = array('creaturename'=>'Seeungeheuer',
		'creaturelevel'=>$session['user']['level'],
		'creatureweapon'=>'Scharfe Zähne',
		'creatureattack'=>round($session['user']['attack']*0.75),
		'creaturedefense'=>round($session['user']['defence']*0.75),
		'creaturehealth'=>round($session['user']['maxhitpoints']*1.5),
		'diddamage'=>0);
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		break;

	case 'fight':
		$battle=true;
		break;
	default:
		//Im Fehlerfall gehts pronto wieder zurück
		$session['user']['specialinc'] = '';
		redirect ($str_backlink,'Fehler, keine gültige OP');
}

if ($battle)
{
	include('battle.php');
	if ($victory)
	{
		$wonexp=round($session['user']['experience']*0.07);
		if ($wonexp<100)
		{
			$wonexp=round(e_rand(100,120));
		}

		$str_output .= '`$`n`cDu hast gewonnen!`c`n
		`9Ein paar verschreckte Nixen tauchen auf, feiern dich und bedanken sich ganz herzlich bei dir.`n`n
		`^Du erhältst '.$wonexp.' Erfahrungspunkte!`n`n';
		$session['user']['experience']+=$wonexp;
		$badguy=array();
		$session['user']['badguy']='';
		$str_output .= create_lnk('Ich sagte ja ich mach das Vieh platt!',$str_backlink,true,true,'',false,$str_backtext,CREATE_LINK_LEFT_NAV_HOTKEY);
		$session['user']['specialinc'] = '';
	}
	else if ($defeat)
	{
		$str_output .= create_lnk('Upps, es war wohl doch etwas stärker als ich dachte.','news.php',true,true,'',false,'Tägliche News',CREATE_LINK_LEFT_NAV_HOTKEY);
		$str_output .= '`n`$`cDu hast verloren!`c`n';
		killplayer();
		$session['user']['specialinc'] = '';
		$badguy=array();
		$session['user']['badguy'] = createstring($badguy);
	}
	else
	{
		fightnav(true,false);
	}
}

output($str_output);
page_footer();
?>