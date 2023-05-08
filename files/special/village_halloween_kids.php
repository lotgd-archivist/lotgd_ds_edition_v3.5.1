<?php
/**
 * Kleine Kinderschar auf dem Dorfplatz
 * idea of LonelyUnicorn
 * Umgeschrieben für Atrahor Code
 */

if (!isset($session))
{
	exit();
}

$str_out = 'Auf dem schaurig erhellten Stadtplatz läuft dir eine Meute seltsam anmutender Wesen entgegen.';

if(mt_rand(1,2) == 1)
{
	$rand = mt_rand(1,6);
	$str_out .= '`n`nEs ist eine Kinderschar in merkwürdigen Kostümen, die dir `^"Süßes oder saures" `0ins Ohr brüllen`n`n';
	switch ($rand){
		case 1:
			$str_out .= 'Du erschrickst dich dermaßen, dass du kopfüber in den Wald flüchtest. Du verläufst dich und brauchst einen Waldkampf, um zurückzufinden.';
            /** @noinspection PhpUndefinedVariableInspection */
            $Char->turns--;
			addnav('Huch, wo bin ich denn?','forest.php');
			break;
		case 2:
			$str_out .= 'Als sie merken, dass du keine Süßigkeiten bei dir hast, hauen sie dich mit ihren prall gefüllten Säcken.`n
	        `5Du verlierst einige Lebenspunkte.`0';
            /** @noinspection PhpUndefinedVariableInspection */
            $Char->hitpoints=round( $Char->hitpoints*0.82 );
			break;
		case 3:
			$str_out .= 'Sie schauen dich an, lachen und gehen einfach weiter.`0';
			break;
		case 4:
			$gold = e_rand (100,500);
			$str_out .= 'Böse funkelst du die Kinder an. Vor Schreck lassen sie ihre Säckchen fallen und flüchten. Zwischen vielen Süßigkeiten findest du etwas Gold.
	        Es sind insgesamt `^$gold Goldmünzen`0';
            /** @noinspection PhpUndefinedVariableInspection */
            $Char->gold+= $gold;
			break;
		case 5:
			/** @noinspection PhpUndefinedVariableInspection */
            $exp = round ( $Char->experience*0.05 );
			$str_out .= 'Die Kinder erzählen dir von unheimlichen Orten im Wald. Du weißt nun, wo du nicht hingehen darfst. `n
	        Durch dieses Wissen erhältst du `8$exp Erfahrungspunkte`0';
			$Char->experience+= $exp;
			break;
		case 6:
			$str_out .= 'Die Kinder zeigen dir, wo du viele, viele Süßigkeiten finden kannst. `n
	        Du siehst gleich, dass du dadurch auch Monster finden wirst. `n
	        `3Du bekommst zwei Kampfrunden extra.`0';
            /** @noinspection PhpUndefinedVariableInspection */
            $Char->turns+=2;
			break;
	}
}
else 
{
	$str_out .= 'Dummerweise sind es keine jugendlichen mit Schabernack im Sinn, sondern ziemlich übel riechende Gesellen mit weniger Lebensgeist als man im Allgemeinen hofft. Bah Zombies... Du machst einen weiten Bogen um die Meute.';
}
?> 