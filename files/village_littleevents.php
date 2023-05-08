<?php
//werden in der village.php eingebunden, nicht wirklich "Specials"
require_once "common.php";

$rand = mt_rand(1,1500);
switch($rand)
{
	case ($rand == 100 || $rand == 101):
		if($Char->gems<500)
		{
			$str_output .= '`^Du findest einen Edelstein vor dir auf dem Boden, den du natürlich sofort einsteckst!`n`n`@';
			$Char->gems++;
		}
		else
		{
			$str_output .= '`$Dir fällt ein Edelstein aus der Tasche, was du jedoch erst später bemerkst. Den Edelstein zu suchen ist aussichtslos, den hat sicher schon jemand anderes gefunden.`n`n`@';
			$Char->gems--;
		}
		break;
	case ($rand == 150 || $rand == 151 || $rand == 152):
		if ($Char->gold>0)
		{
			$goldlost=ceil($Char->gold*0.15);
			$str_output .= '`4Jemand rempelt dich an und entfernt sich unter wortreicher Entschuldigung rasch. Dann stellst du fest, dass man dir '.$goldlost.' Gold gestohlen hat!`n`n`@';
			$Char->gold-=$goldlost;
			debuglog('wurde von Taschendieben um '.$goldlost.' Gold erleichtert');
		}
		break;
	case ($rand == 200 || $rand == 201 || $rand == 202):
		if ($Char->turns>0)
		{
			$str_output .= '`^Jemand kommt dir gut gelaunt entgegen gelaufen und reicht dir ein Ale. Deine Laune bessert sich dadurch und du hast heute eine Runde mehr!`n`n`@';
			$Char->turns++;
		}
		break;
	case ($rand == 250 || $rand == 251):
		$str_output .= '`4Jemand rennt eilig vor einer Stadtwache davon und stößt dich grob bei Seite, da du ihm im Weg stehst. Du stürzt und landest mit dem Gesicht in einem Kuhfladen. Leute drehen sich zu dir um und zeigen lachend auf dich. Du verlierst einen Charmepunkt!`@`n`n';
		$Char->charm=max(0,$Char->charm-1);
		break;
	case ($rand == 300):
		$str_output .= 'Aus irgendeiner Ecke scheint man dich zu beäugen. Jedenfalls hast du das ungute Gefühl dass dem so wäre. Als du dich jedoch umsiehst ist niemand bestimmtes auszumachen.`n`n';
		break;
	case ($rand == 301):
		$str_output .= 'Du lauschst kurz auf als jemand vermeintlich deinen Namen ruft. Aber das war wohl nur ein Trugschluss.`n`n';
		break;	
	case ($rand == 302):
		$arr_user = CCharacter::getChars($Char->login,'`name`',array('login' => array('type'=>CCharacter::SEARCH_SOUNDEX)),' AND `a`.`acctid` != '.$Char->acctid,'','1');
		$str_output .= 'Du lauschst kurz auf als jemand vermeintlich deinen Namen ruft. Aber du hast dich wohl verhört, es wurde '.$arr_user['name'].' gerufen. Klingt ja auch so ähnlich.`n`n';
		break;	
}

?>