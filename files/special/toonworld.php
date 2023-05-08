<?php

// Mach mal hat er gesagt
// [by Joshua Schmidtke]
//
// idea and coding by: Joshua Schmidtke [alias Mikay Kun]
//
// build: 2007-06-09

require_once('common.php');

page_header('Bunte Lichter');

//Sicher ist sicher
global $Char;

if ($_GET['op']=='nextstep')
{
	output('Ja, dein Mut ist wirklich etwas Besonderes, das muss man dir lassen. Schwupp - schon bist du im Portal. Diese wunderbaren, bunten Lichter sind wunderbar... und nach kurzer Zeit fällst du mit dem Gesicht auf etwas hartes. Du rappelst dich auf und siehst... Nichts. Schwarz.... bist du etwa erblindet? Plötzlich treten mehrere gestalten aus den Schatten. Sie tragen verschiedenen Dingen mit sich. Der eine hat Steinplatten unter dem Arm und zeigt direkt auf dich. Er legt sie auf den Boden und schon entsteht eine nette Welt. Du bist im Entwicklungszentrum der Entwickler angelangt.`n
	`n
	Zwar war es nicht das was du erwartet hast, aber naja, es sieht nett aus.');
	
	$Char->specialinc='';
}

elseif ($_GET['op']=='run')
{
	output('Du drehst dich auf der Stelle um und gehst zurück um den passenden Heiler für solche Wahnvorstellungen zu finden.');
	$Char->specialinc='';
	addnav('Ich bin Kassenpatient','village.php');
}

else
{
	output('Während du so durch die Gegend schlenderst, öffnet sich vor dir ein Portal. Es funkelt in vielen bunten Farben und wenn man in den Wirbel schaut dreht sich einem der Magen um. Aus dem Portal steigt eine Rauchwolke auf und eine unheimliche Figur gibt sich zu erkenen. Es scheint kein normales Wesen zu sein. Es wirkt mehr, als wäre es eine Zeichnung die ein verrückter Hexer zum Leben erweckt hat.`n
	`n
	Die Zeichnung schaut sich um, entdeckt dich und rennt schreiend zurück in das Portal.`n
	`n
	Du bemerkst, dass das Portal kleiner wird. Noch hast du die Chanche hinterher zu rennen. Willst du das wirklich riskieren?');
	
	addnav('Toon-Welt ich komme','village.php?op=nextstep');
	addnav('Schnell den nächsten Heiler aufsuchen','village.php?op=run');
	
	$Char->specialinc = 'toonworld.php';
}

?>