<?php
/* coded by Ithil dae (alias Abraxas)
* Email: questbraxel@web.de
* April 2005
* www.zum-tanzenden-troll.de ; www.tanzender-troll.de
* v 0.02
* Wer einen Rhechtschraibfeler findet darf in behalten.
* kleinere Modifikationen by Salator für atrahor.de
*/

require_once("common.php");
addcommentary();
page_header('Horoskope');
output('`c`bDer Horoskop Automat`b`c`n`n');

// Verzichte hier absichtlich auf Zufallszahlen da der Spieler den Eindruck gewinnen soll,
// es handle sich wirklich um eine berechenbare Sache. In der Zeitung heißt es ja auch nicht,
// würfle eine 4 damit dir der große, dunkle Unbekannte begegnet.

$lvl=$session['user']['level']; //1-15
$drag=$session['user']['dragonkills']%10; //0-9
$age=$session['user']['age']%10; //0-9
	
if ($_GET['op']=='')
{
	addnav('Automat bedienen');
	if ($session['user']['gold']>=5)
	{
		addnav('Horoskop kaufen (`^5`0)','horoskop.php?op=horoskop');
	}
	if ($session['user']['gold']>=20)
	{
		addnav('Gutes Wort (`^20`0) ','horoskop.php?op=wort');
	}
	if ($session['user']['gold']<5)
	{
		addnav('Kostenloses Horoskop ziehen','horoskop.php?op=kostenlos');
	}
	
	addnav('Zurück');
	addnav('Zum Stadtfest','dorffest.php');
	
	output('`7 Alt, etwas zerdellt und dennoch irgendwie magisch...
	`nMit einem seltsamen Gefühl im Magen trittst du vor den seltsamen Horoskop-Automaten.
	`n`nEin bronzenes Schild besagt `2"Wer die Zukunft kennt, dem gehört die Gegenwart."
	`n`7 und ein anderes `2"Ein Zwerge u. Räder (c) Produkt. Defekte an Schmiede-Weg 19, Gargrim Flammensohn"`7...
	`n`n`n`^ 5 Münzen für den Blick in die Zukunft.
	`n20 Münzen für ein Gutes Wort bei den Geistern.');
	
	//admin_output('`n`n`0Admin:`$`nLiebe: '.($lvl+$drag).'`n`@Beruf: '.($age+$drag).'`n`#Weisheit: '.($age+$drag+$lvl-2).'`0',false);
}

else if ($_GET['op']=='horoskop')
{
	addnav('Zurück');
	addnav('Zum Stadtfest','dorffest.php');
	$session['user']['gold']-=5;
	output('`7 Etwas skeptisch wirfst du dein Gold in den schmalen Schlitz des Aparates.
	`nKurz darauf hörst du ein lautes Klacken, dann ein Rattern und Krachen. Rauch dringt aus dem Ausgabeschlitz...
	`nUnd Stille.
	`n Schon glaubst du dein Gold verloren, ja verschwendet und willst dich zum Gehen wenden, da ertönt ein leises Glöckchen...
	`n Und ein kleiner Zettel erscheint im Ausgabeschacht. Darauf ist zu lesen:
	`n`n`n');
	
	// Liebe
	output('`$Liebe:`n`n`$');
	switch($lvl+$drag)
	{
	case 1:
	case 2:
		output('Schlank und blond wird deine Liebe sein...`n`n');
		break;
	case 3:
	case 4:
		output('Du wirst herausfinden, dass Zwerge nicht nur Gold lieben... Dein Glück ist 1,20 m groß.`n`n');
		break;
	case 5:
	case 6:
		output('Ein großer Unbekannter wird in dein Leben treten... Er wird dir näher kommen... Wird dich berühren... Seine Lippen berühren dein Ohr und er sagt: "Gold oder Leben und wehe du schreist!"`n`n');
		break;
	case 7:
	case 8:
		output('Heute wirst du dein Glück finden!`n`n');
		break;
	case 9:
	case 10:
		output('Nutze den Tag, sei mutig und offen, dann wird sich deine Liebe offenbaren.`n`n');
		break;
	case 11:
	case 12:
		output('Sie achtet nicht auf Äußerlichkeiten, deine innere Größe ist für sie entscheidend. Na\'Kra, die Trollfrau, sehnt sich nach dir...`n`n');
		break;
	case 13:
	case 14:
		output('Tief-Purpurne Augen, dunkelblond und ein hübsches Gesicht... Halte Ausschau!`n`n');
		break;
	case 15:
	case 16:
		output('Heute steht es schlecht um deine Liebe... Vieleicht solltest du ein Bad nehmen...`n`n');
		break;
	case 17:
	case 18:
		output('Es gibt viele Wege in ein liebendes Herz. Deiner heißt Seife!`n`n');
		break;
	case 19:
	case 20:
		output('Geheimnissvoll und schön...`n`n');
		break;
	default:
		output('Du wirst deine wahre Liebe nicht heute finden.`n`n');
		break;
	}
	
	// Wetter
	$w = Weather::get_weather();
	output('`^Das Wetter:`n`n`^Das Wetter ist heute `^'.$w['name'].'`^.`n`n');
	
	// Beruf
	output('`@Beruf:`n`n');
	switch($age+$drag)
	{
	case 0:
	case 1:
		output('Ein Gnom wird mit einem äußerst dubiosen Geschäft an dich herantreten. Lehne ab!');
		break;
	case 2:
	case 3:
		output('Du wirst heute in den Feldern Erfolg haben!');
		break;
	case 4:
	case 5:
		output('Ein Ale würde deine Angriffskraft heben.');
		break;
	case 6:
	case 7:
		output('Der letzte Goblin, den du erschlagen hast, war ein naher Verwandter. Du solltest dich schämen!');
		break;
	case 8:
	case 9:
		output('Habe keine Bedenken, wenn du heute eine Rüstung stiehlst, wird man dich nicht erwischen.');
		break;
	case 10:
	case 11:
		output('Du solltest heute nicht mehr in den Wald gehen. Es könnte sein, dass man dich tötet!');
		break;
	case 12:
	case 13:
		output('Du solltest heute nicht in den Feldern übernachten...');
		break;
	case 14:
	case 15:
		output('Trage dein Gold nicht mehr mit dir herum, sonst wird es dir gestohlen!');
		break;
	case 16:
	case 17:
		output('Sei heute freigiebig mit deinem Gold, es wird sich später für dich lohnen!');
		break;
	case 18:
	case 19:
		output('Die Farbe ist verwischt...');
		break;
	default:
		output('Du solltest heute in ein neues Reittier investieren!');
		break;
	}
	
	// Weisheit
	output('`#`n`nHeutige Weisheit:`n`n');
	switch($age+$drag+$lvl-2)
	{
	case 1:
	case 2:
		output('Wenn es nachts im Bette kracht, der Bauer seine Erben macht.');
		break;
	case 3:
	case 4:
		output('Dreht der Hahn sich auf dem Grill, macht das Wetter was es will.');
		break;
	case 5:
	case 6:
		output('Das Ale im Glase ist besser als der Tropfen im Fass.');
		break;
	case 7:
	case 8:
		output('Es ist des Zwergen Eigenart, dass er die Frauen mag behaart.');
		break;
	case 9:
	case 10:
		output('Elfen haben doofe Ohren!');
		break;
	case 11:
	case 12:
		output('Weib ist jener, der sich badet aus freiem Willen.');
		break;
	case 13:
	case 14:
		output('Sieht die Magd den Bauern nackt, wird vom Brechreiz sie gepackt.');
		break;
	case 15:
	case 16:
		output('Ein Ale kaufen ist gut, ein Ale spendiert bekommen besser, es trinken das Beste!');
		break;
	case 17:
	case 18:
		output('Steht im Winter noch das Korn, ist es bestimmt vergessen wor\'n.');
		break;
	case 19:
	case 20:
		output('Wenn du aufwachst mit der Flasche im Arm und nichts an, dann war es ein gutes Besäufnis.');
		break;
	case 21:
	case 22:
		output('Strahlt der Mond ganz voll und hell, wächst dem Knecht ein Werwolffell!');
		break;
	case 23:
	case 24:
		output('Zu tief in die Jauche schaun macht den Bauern sportlich braun.');
		break;
	case 25:
	case 26:
		output('Wenn die Magd nach Knoblauch stinkt, der Knecht sie auch im Dunkeln find!');
		break;
	case 27:
	case 28:
		output('Kräht der Hahn auf dem Mist, ändert sich das Wetter, oder es bleibt wie\'s ist.');
		break;
	case 29:
	case 30:
		output('Fahr\'n dicke Bauern Ruderboot, fahr\'n sie alle Fische tot.');
		break;
	default:
		output('Wenn der Bauer zum Waldrand hetzt, war der Abort wohl besetzt.');
		break;
	}
}

else if ($_GET['op']=='wort')
{
	addnav('Zurück');
	addnav('Zum Stadtfest','dorffest.php');
	$session['user']['gold']-=20;
	output('`7 Etwas skeptisch wirfst du dein Gold in den schmalen Schlitz des Aparates.
	`nKurz darauf hörst du ein lautes Klacken, dann ein Rattern und Krachen. Rauch dringt aus dem Ausgabeschlitz...
	`nUnd Stille.
	`n Schon glaubst du dein Gold verloren, ja verschwendet und willst dich zum Gehen wenden, da ertönt ein leises Glöckchen...
	`nUnd ein kleiner Zettel erscheint im Ausgabeschacht. Darauf ist zu lesen:
	`n"Die Geister sind mit dir..."`n`n');
	
	switch(e_rand(1,30))
	{
		case 1:
			$session['user']['drunkenness'] *= .9;
			output('`2 Du fühlst dich jetzt nüchterner...');
			break;
			
		case 2:
			$session['user']['charm']++;
			output('`2 Du fühlst dich jetzt schöner...');
			break;
			
		case 3:
			output('`2Die Geister sind jetzt auf deiner Seite!`0`n');
			$session['bufflist']['segen'] = array('name'=>'`9Gute Geister','rounds'=>8,'wearoff'=>'Die Geister unterstützen dich nicht länger....','defmod'=>1.1,'roundmsg'=>'`9Die Geister sind auf deiner Seite!.','activate'=>'offense');
			break;
			
		default:
			output('`2 Du weisst zwar nicht warum, doch du fühlst dich jetzt besser...');
			break;
	}
}

else if ($_GET['op']=='kostenlos')
{
	addnav('Nein, lieber nicht','horoskop.php');
	addnav('Zurück');
	addnav('Zum Stadtfest','dorffest.php');
	output('`7 Etwas skeptisch drückst du den Knopf des Aparates.`n
	Kurz darauf hörst du ein lautes Klacken, dann ein Rattern und Krachen. Rauch dringt aus dem Ausgabeschlitz...
	`nUnd Stille.
	`n Schon glaubst du dein Gold verloren, ja verschwendet und willst dich zum Gehen wenden, da ertönt ein leises Glöckchen...
	`nUnd ein kleiner Zettel erscheint im Ausgabeschacht. Darauf ist zu lesen:
	`n`n"Nichts im Leben ist umsonst, nur der Tod und selbst der kostet das Leben... Möchtest du wirklich ein kostenloses Horoskop? Es wird dann auch mit Sicherheit in Erfüllung gehen...?"`n`n');
}
page_footer();
?>

