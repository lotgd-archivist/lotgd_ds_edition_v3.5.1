<?php
/**
* cedriks_delivery.php: Cedriks Bierlieferung
* - füllt Cedriks gelagerten Ale wieder auf, wenn diese zu knapp sind
* @author: Asgarath für atrahor.de
* @version: v1.0
* @date: Oct 2008
*
*
* ALTER TABLE `account_extra_info`
* ADD `gotalekegs` TINYINT(5) UNSIGNED NOT null DEFAULT '0'
*/

// Lade die Anzahl der vorhandenen Fässer
$totalkeg = getsetting('totalkeg',50);
$str_output ='';
// Wenn mehr als 5 Fässer vorhanden sind wird nicht aufgefüllt
if($totalkeg>5)
{
	$str_output = '`0Auf deinen Streifzügen durch die Wälder Atrahors stößt du plötzlich auf eine kleine Lichtung.';
	switch(e_rand(1,3))
	{
	case 1:
		$str_output.= ' Hier gibt es allerdings nichts besonderes außer ein paar Pilzen, von welchen du dir ein leckeres Essen kochst.';
        /** @noinspection PhpUndefinedVariableInspection */
        if($session['user']['hitpoints']<$session['user']['maxhitpoints'])
		{
			$str_output .= ' So wie es aussieht, war Medizin in den Pilzen.`n`n
						`^Deine Lebenspunkte sind wieder aufgefüllt!';
			$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
		}
		else
		{
			$str_output .= ' Es scheint, als wären die Pilze mit seltsamen Substanzen gefüllt, denn du fühlst dich unwiderstehlich.`n`n
						`%Du erhälst einen Charmepunkt!';
			$session['user']['charm'] += 1;
		}
		break;
	case 2:
		$gems = e_rand(3,6);
		$str_output .= ' Es scheint, als ob es hier nichts besonderes gäbe, doch dann entdeckst du einen kleinen Beutel mit `#'.$gems.'`0 Edelsteinen!';
        /** @noinspection PhpUndefinedVariableInspection */
        $session['user']['gems'] += $gems;
		break;
	case 3:
		$str_output .= ' Doch es scheint hier nichts besonderes zu geben, weshalb du dich wieder auf deinen Weg machst.';
		break;
	}
}
else
{
	$str_output .= 'Du bist gerade auf deinen täglichen Streifzügen durch die Wälder, als plötzlich eine Kutsche mit rasender Geschwindigkeit an dir vorbei rauscht. Du kannst gerade noch erkennen, dass sie einige Fässer aufgeladen haben. Es scheint so, als würde Cedrik endlich wieder eine frische Lieferung Ale erhalten.';
	savesetting('totalkeg',50);
	addnews('`^Cedrik hat soeben eine frische Lieferung Ale erhalten!');
	// Setze die Anzahl der gekauften Fässer jedes Users zuück
	$sql= 'UPDATE account_extra_info SET gotalekegs=0 WHERE gotalekegs>0';
	db_query($sql);
}
//addnav('Zurück in den Wald','forest.php');

output($str_output);
?>
