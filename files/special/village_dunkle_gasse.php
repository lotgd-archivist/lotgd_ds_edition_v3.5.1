<?php
require_once 'common.php';
checkday();
page_header('Stadtzentrum');
$session['user']['specialinc'] = basename(__FILE__);

$str_filename = 'village.php';
$str_backlink = 'village.php';

if ($_GET['sop']=='')
{
	output('`@Wie du so auf dem Stadtplatz stehst, fällt dir plötzlich eine Gestalt auf, die in gebückter Haltung rasch hinter einer Häuserecke in eine dunkle Gasse entschwindet.`nBei allem was dir heilig ist könntest du schwören, dass dieser Geselle etwas ausgefressen hat. Außer dir scheint niemand die Gestalt wahrgenommen zu haben. Ein bisschen mulmig ist dir schon zumute, immerhin könnte dich in dieser Gasse alles möglich erwarten, sogar der Tod! Doch andererseits könntest du durch die Ergreifung eines Schurken eine Heldentat begehen und so zu großem Ruhm und Ansehen gelangen.`nWie wirst du dich entscheiden?');
	addnav('In die Gasse folgen',$str_filename.'?sop=gasse2');
	addnav('Bleiben wo du bist',$str_filename.'?sop=go_back_to_village');
}
else if ($_GET['sop']=='gasse2')
{
	output('`tDu fasst dir ein Herz und huschst in die Gasse, der Gestalt hinterher. Es ist dunkel und du erkennst im ersten Moment rein gar nichts, orientierst dich nur an den leisen, schleichenden Geräuschen vor dir.`nDann auf einmal hörst du eine Tür zuschlagen und es wird still. Da sich deine Augen nun etwas besser an die Dunkelheit gewöhnt haben, siehst du links und rechts von dir jeweils eine Tür, beide scheinen nicht verschlossen zu sein.`nWas tust du?');
	addnav('Nimm die linke Tür',$str_filename.'?sop=house');
	addnav('Nimm die rechte Tür',$str_filename.'?sop=house');
	addnav('Zurück zum Stadtzentrum',$str_filename.'?sop=go_back_to_village');
}
else if ($_GET['sop']=='house')
{
	switch (e_rand(1,5))
	{
		case 1 :
		case 2 :
		case 3 :
			output('`tDu bist im Inneren eines Hauses, schwaches Licht fällt von irgendwoher in den Raum. Du erkennst einen Mantel, achtlos in eine Ecke geworfen, sowie eine Treppe, die ein Stockwerk höher führt. Auch kannst du eine Falltüre ausmachen, die in den Keller führt.`nWofür entscheidest du dich?');
			addnav('Nimm den Mantel',$str_filename.'?sop=coat');
			addnav('Treppe hoch',$str_filename.'?sop=upstairs');
			addnav('In den Keller',$str_filename.'?sop=down');
			addnav('Raus hier',$str_filename.'?sop=go_back_to_village');
			break;
		case 4 :
			output('`tDu schlüpfst durch die Türe und findest dich in einem kleinen Raum wieder. Der Raum ist schwach beleuchtet und ein Tisch steht in der Mitte. Verdächtig aussehende Gestalten richten sich plötzlich vom Tisch auf, als du herein kommst. Und bevor du dich versiehst saust ein Wurfdolch vorbei an deinem Gesicht in die Türe. Die Gauner sehen nicht so aus als ob sie Spass verstehen.`nWas tust du nun?');
			addnav('Kämpfe!',$str_filename.'?sop=fight');
			addnav('Rede dich raus',$str_filename.'?sop=argue');
			addnav('Flüchte',$str_filename.'?sop=go_back_to_village');
			break;
		case 5 :
			output('`tDu kommst in eine Waschküche und blickst in das völlig verdutzte Gesicht einer alten Frau, die gerade lange Unterhosen in einer kleinen hölzernen Wanne wäscht. Vollkommen fassungslos und auch ohne Worte starrt sie dich an. Anders ihre schwarze Katze, die dich anspringt und dir das Gesicht zerkratzt. Unter Schmerzensschreien schüttelst du das Tier ab und rennst raus. Das soll dir eine Lektion sein! Die Spuren dieses Abenteuers werden wohl ewig in deinem Gesicht zu erkennen sein!');
			addnews('`@'.$session['user']['name'].'`@ hat seltsame Kratzer im Gesicht, schweigt sich darüber jedoch aus!');
			$session['user']['charm']-=3;
			addnav('Zurück',$str_filename.'?sop=go_back_to_village');
			break;
	}
}
else if ($_GET['sop']=='coat')
{
	output('`tDu durchsuchst den Mantel, in der Hoffung etwas Wertvol...ähh Beweise zu finden.');
	switch (e_rand(1,5))
	{
		case 1 :
		case 2 :
			output('`t`nDu gehst dabei sehr gründlich vor, aber dennoch findest du nichts von Interesse. Langsam kommst du dir wirklich blöd vor, wie du in einem fremden Haus einen alten Mantel durchwühlst. Und bevor dich noch jemand erwischt ziehst du es vor schnell und unauffällig wieder zu verschwinden.');
			addnav('Zum Stadtzentrum',$str_filename.'?sop=go_back_to_village');
			break;
		case 3 :
		case 4 :
			$chance=e_rand(1,3);
			output('`t`nEin schlechtes Gewissen hast du schon, aber als du plötzlich etwas Hartes in einer Innentasche fühlst, ist das schnell vergessen. Im Mantel waren doch tatsächlich `^'.$chance.' Edelsteine`t, die du natürlich sicherstellst. Doch irgendwie... kommt dir das Ganze nun etwas seltsam vor, und bevor man dich wegen Einbruchs verhaftet ziehst du es vor mit deinem Fund zu verschwinden.');
			$session['user']['gems']+=$chance;
			addnav('Zurück',$str_filename.'?sop=go_back_to_village');
			break;
		case 5 :
			output('`t`nIn diesem Mantel ist nichts, so glaubst du... Doch als du das alte Ding schon zurück in die Ecke werfen willst bemerkst du ein Rascheln. Du öffnest eine Seitentasche und hälst einen `^Freibrief`t in deinen Händen. Hast du ein Glück! Du steckst das gute Stück ein und siehst zu dass du davon kommst.');

			// Freibrief
			item_add($session['user']['acctid'],'frbrf',array('tpl_description'=>'Ein Freibrief. Damit kannst du dich genau einmal aus dem Kerker holen.'));

			addnav('Zurück',$str_filename.'?sop=go_back_to_village');
			break;
	}
}
else if ($_GET['sop']=='upstairs')
{
	output('`tDu steigst die Stufen der Treppe hoch und findest dich in einer kleinen Kammer wieder. Ein Bett, eine Kommode und ein Tisch stehen darin. Ebenfalls kannst du eine große Truhe erkennen. Sie sieht allzu verlockend für dich aus. Wer weiß welche Reichtümer sie beinhaltet? Na was ist, willst du nicht mal nachsehen ?');
	addnav('Die Truhe öffnen',$str_filename.'?sop=chest');
	addnav('Zurück zur Gasse und in die andere Tür',$str_filename.'?sop=house');
	addnav('Zum Stadtzentrum',$str_filename.'?sop=go_back_to_village');
}
else if ($_GET['sop']=='chest')
{
	output('`t`nMit gierigem Blick in deinen Augen eilst du zur Truhe und öffnest sie ohne nachzudenken.');
	switch (e_rand(1,4))
	{
		case 1 :
		case 2 :
			$gold=e_rand(500,3000);
			$gems=e_rand(1,5);
			output("`t`nDu klappst den Deckel auf und dich lachen `^ $gold Gold und $gems Edelsteine`t an, die du hastig in deinen Taschen verschwinden lässt bevor du dich davon machst.");
			$session['user']['gold']+=$gold;
			$session['user']['gems']+=$gems;
			addnav('Schnell weg',$str_filename.'?sop=go_back_to_village');
			break;
		case 3 :
			output('`t`nDu schaust in das Innere der Truhe und lachst vor Freude, also du `^Tausende Goldmünzen und Dutzende Edelsteine`t erblickst. Du greifst mit beiden Händen hinein und spürst plötzlich eine Hand auf deiner Schulter. Als du dich umdreht schaust du in das grinsende Gesicht einer Stadtwache');
			if (($session['user']['profession']!=21) && ($session['user']['profession']!=22))
			{
				output(', die mit einem leisen `RKlick`t die Handschellen einrasten lässt. Zwar wehrst du dich und redest dich raus, aber letztendlich landest du doch im Kerker!');
				addnews('`@'.$session['user']['name'].'`@ wurde bei einem Einbruch gefasst und eingekerkert. Über der Zelle hängt ein Schild mit der Aufschrift `^Dümmster Einbrecher der Woche`@');
				$session['user']['imprisoned']=2;
				$session['user']['specialinc']='';
				addnav('In den Kerker','prison.php');
			}
			else
			{
				output(', die dich als Richter zwar nicht festnehmen kann, dich aber an der Hand nimmt und dich zurück zum Stadtzentrum begleitet.');
				addnews('`@Richter '.$session['user']['name'].'`@ wurde bei einem Einbruch gefasst und entging dank der Immunität dem Kerker.');
				addcrimes('`@Richter '.$session['user']['name'].'`@ wurde bei einem Einbruch gefasst und entging dank der Immunität dem Kerker.');
				addnav('Nun denn...',$str_filename.'?sop=go_back_to_village');
			}
			break;
		case 4 :
			output('`t`nDen Anblick von `^Gold und Edelsteinen`t trübt lediglich der süße Schmerz, den ein Dorn verursacht, der beim Öffnen der Truhe in deine Hand einschlug. Und während du die Reichtümer der Truhe in deine Taschen schaufelst, nimmt dir das Gift, das sich in deinem Körper ausbreitet, das Leben! Du bist tot und verlierst 5% deiner Erfahrung!');
			killplayer(0,5,0,'shades.php','Mit einem Lächeln sterben');
			addnews('`@'.$session['user']['name'].'`@ weiß nun, dass Truhen auch mit Fallen versehen sein können und wird im nächsten Leben sicher daran denken.');
			break;
	}
}
else if ($_GET['sop']=='down')
{
	output('`tDu klappst die Falltüre auf und kletterst eine schmale Leiter hinab. Schließlich bist du ganz unten angelangt. Schummeriges Licht erhellt den Keller ein wenig, du weißt nicht woher es kommt.');
	switch (e_rand(1,3))
	{
		case 1 :
			$gold=e_rand(1000,5000);
			$gems=e_rand(2,6);
			output("`nAls du dich etwas umblickst kannst du die Lichtquelle ausmachen : Eine fast abgebrannte Fackel an der Wand. Du nimmst die Fackel aus der Halterung und leuchtest die Ecken aus, denn von allein wird sie ja nicht hergekommen sein. Hinter einer Kiste hockt, unter einer Decke zusammengekauert, ein dürrer Mann. Seine Hände umklammern fest einen dicken Beutel, der mit Gold und Schmuck gefüllt sein muss. Er blickt resigniert zu dir auf und seufzt leise. Du packst den Strolch am Kragen und zerrst ihn nach oben. Als du mit ihm die Gasse wieder verlässt, begegnest du einer Stadtwache. Hoch erfreut über deinen Fang zahlt sie dir eine Belohnung von `^$gold Gold und $gems Edelsteinen`t aus. Außerdem stellt sie dir einen `^Freibrief`t aus!");
			$session['user']['gold']+=$gold;
			$session['user']['gems']+=$gems;

			// Freibrief
			item_add($session['user']['acctid'],'frbrf',array('tpl_description'=>'Ein Freibrief. Damit kannst du dich genau einmal aus dem Kerker holen.'));

			addnews("`@".$session['user']['name']."`@ hat einen Gauner geschnappt und wurde dafür reich belohnt.");
			addnav('Hurra',$str_filename.'?sop=go_back_to_village');
			break;
		case 2 :
			output('`t`nSchnell stellst du fest, dass das Licht von einer Türe kommt, die leicht angelehnt ist. Du näherst dich um zu lauschen, doch da fliegt die Türe schon auf, und eine Gruppe Halsabschneider starrt dich an, die Messer gezückt. Wie willst du dich nun da raus bringen ?');
			addnav('Kämpfe!',$str_filename.'?sop=fight');
			addnav('Rede dich raus',$str_filename.'?sop=argue');
			addnav('Flüchte',$str_filename.'?sop=go_back_to_village');
			break;
		case 3 :
			output('`t`nNeugierig schaust du dich nach der Lichtquelle um und als du dich ihr näherst spürst du nur noch einen Schlag auf deinen Hinterkopf und es wird dunkel. Du erwachst gefesselt und geknebelt, an den Füßen aufgehangen. Um dich herum haben sich ein paar Orks und Goblins versammelt, die sich nun an dir gütlich tun werden. Du verlierst 5% deiner Erfahrung und bist tot.');
			killplayer(0,5,0,'shades.php','Mahlzeit!');
			addnews('`@'.$session['user']['name'].'`@ wurde von Orks und Goblins gefrühstückt.');
			break;
	}
}
else if ($_GET['sop']=='fight')
{
	output("`tDu bist deutlich in der Unterzahl, nutzt jedoch deine Kampfkünste und deine Erfahrung. In angeberischen Posen zeigst du kleine Kunststücke mit deiner Waffe und näherst dich der Gaunergruppe.");
	switch (e_rand(1,4))
	{
		case 1 :
		case 2 :
			$gold=e_rand(3000,10000);
			$gems=e_rand(3,10);
			output("`t`nWie ein Meister der hohen Kampfkunst schlägst du einen nach dem anderen kampfunfähig. Deine Schläge sitzen perfekt und du überstehst den Kampf unverletzt als Sieger. Die lauten Kampfgeräusche und das Ächzen und Stöhnen deiner unterlegenen Gegner haben eine Stadtwache aufmerksam gemacht, die gerade in der Nähe auf Patrouille war. Sie teilt dir mit, dass du eine schon lange gesuchte Verbrecherbande gefasst hast und überreicht dir zum Dank `^$gold Gold und $gems Edelsteine`t, sowie einen `^Freibrief`t.");
			$session['user']['gold']+=$gold;
			$session['user']['gems']+=$gems;

			// Freibrief
			item_add($session['user']['acctid'],'frbrf',array('tpl_description'=>'Ein Freibrief. Damit kannst du dich genau einmal aus dem Kerker holen.'));

			addnews("`@".$session['user']['name']."`@ hat eine Diebesbande geschnappt und wurde zum Held des Tages erklärt.");
			addnav("Hurra",$str_filename."?sop=go_back_to_village");
			break;
		case 3 :
			output("`t`nBereits dein erster Gegner schlägt dir hart mit der Faust ins Gesicht. Damit hast du nicht gerechnet. Unter Schlägen und Tritten sackst du zusammen bis du ohnmächtig bist. Die Gauner machen sich einen Spass und versehen dich mit vielen kleinen Indizien und Beutestücken, bevor sie dich bei der Stadtwache abliefern");
			if (($session['user']['profession']!=21) && ($session['user']['profession']!=22))
			{
				output(" und für deine Ergreifung sogar noch eine Belohnung erhalten! Du kommst in den Kerker.");
				addnews("`@".$session['user']['name']."`@ wurde heute endlich nach wochenlangem Raubzug durch unsere schöne Stadt von ehrenhaften Bürgern gefasst!");
				$session['user']['imprisoned']=3;
				$session['user']['specialinc']='';
				addnav("In den Kerker","prison.php");
			}
			else
			{
				output(". Allerdings besitzt du Immunität, weswegen die Wache dich nicht einsperren kann.");
				addnews("`@Richter ".$session['user']['name']."`@ wurde nach wochenlangem Raubzug durch unsere schöne Stadt gefasst, entgeht dank der Immunität aber dem Kerker!");
				addcrimes("`@Richter ".$session['user']['name']."`@ wurde nach wochenlangem Raubzug durch unsere schöne Stadt gefasst, entgeht dank der Immunität aber dem Kerker!");
				addnav("Weiter",$str_filename."?sop=go_back_to_village");
			}
			break;
		case 4 :
			output("`t`nDu streckst deinen ersten Gegner eiskalt nieder, näherst dich dann dem Zweiten. Auch ihn schickst du schnell zu Ramius. Doch dann trifft dich eine Klinge genau zwischen die Schulterblätter. Zwar schaffst du es noch einen Dritten zu töten, doch hauchst du selbst nach mehreren weiteren Treffern auch dein Leben aus. Du bist tot und verlierst 5% deiner Erfahrung!");
			killplayer(0,5,0,'shades.php','Heldentod!');
			addnews("`@".$session['user']['name']."`@ wurde erstochen in einer Gasse gefunden.");
			break;
	}
}
else if ($_GET['sop']=="argue")
{
	output("`tDu versuchst durch geschickte Ausreden Herr der Lage zu werden und plapperst wie wild drauf los. Die Worte sprudeln aus deinem Mund wie Wasser aus einer Quelle. Die Halunken schauen dich fassungslos an.");
	switch (e_rand(1,4))
	{
		case 1 :
			output("`t`nDann erheben sich die Schurken, in fester Absicht dich endgültig zum Schweigen zu bringen. Dir wird wohl nichts anderes übrig bleiben als zu kämpfen. Also atmest du einmal tief ein und ziehst deine Waffe.");
			addnav("Kämpfen",$str_filename."?sop=fight");
			break;
		case 2 :
			output("`t`nDie Gauner stürmen auf dich zu, doch anstatt dich zu töten verpassen sie dir einen gewaltigen Tritt und werfen dich vor die Tür. Du hast wirklich Glück, dass sie dich nicht ernst genommen haben!");
			addnav("Zum Stadtzentrum",$str_filename."?sop=go_back_to_village");
			break;
		case 3 :
			output("`t`nDu redest dich um Kopf und Kragen, plapperst laut und unbeherrscht drauf los. Dann auf einmal spürst du einen Schlag in den Rücken und kippst nach vorn. Eine Gruppe von 5 Stadtwachen stürmt den kleinen Raum und nimmt alle fest, auch dich. Du hast sie geradewegs dorthin geführt, doch dass du nicht zu der Bande gehörst will dir auch so recht keiner glauben.");
			if (($session['user']['profession']!=21) && ($session['user']['profession']!=22))
			{
				output("Also verbringst du erstmal den Rest des Tages im Kerker.");
				addnews("`@".$session['user']['name']."`@ wurde als Bandenmitglied gefasst und in den Kerker geworfen!");
				$session['user']['imprisoned']=1;
				$session['user']['specialinc']='';
				addnav("In den Kerker","prison.php");
			}
			else
			{
				output("Da du Richter bist können sich dich aber auch nicht wegsperren. Glück gehabt!");
				addnews("`@Richter ".$session['user']['name']."`@ wurde als Bandenmitglied gefasst, entgeht dank der Immunität aber dem Kerker!");
				addcrimes("`@Richter ".$session['user']['name']."`@ wurde als Bandenmitglied gefasst, entgeht dank der Immunität aber dem Kerker!");
				addnav("Zurück",$str_filename."?sop=go_back_to_village");
			}
			break;
		case 4 :
			$gold=e_rand(1000,3000);
			$gems=e_rand(1,5);
			output("`t`nDie Gauner schauen sich gegenseitig an und springen plötzlich auf. Durch eine kleine Tür im hinteren Teil des Raumes gelangen sie in eine Gasse und rennen davon. Die haben doch tatsächlich geglaubt, du wärst nicht allein gekommen! Mit einem Schmunzeln machst du dich daran die Beute dieser Bande einzustecken. Insgesamt immerhin `^$gold Gold und $gems Edelsteine`t.");
			$session['user']['gold']+=$gold;
			$session['user']['gems']+=$gems;
			addnav("Hurra",$str_filename."?sop=go_back_to_village");
			break;
	}
}
else if($_GET['sop']=='go_back_to_village')
{
	output('Du gehst wieder zurück zum Stadtzentrum.');
	$session['user']['specialinc'] = '';
	addnav('Weiter',$str_backlink);
}
?>