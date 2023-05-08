<?php
/**
 * @desc Triff im Wald auf eine Sirene die dich beschenkt
 * Das Special wurde von Dragonslayer für Atrahor-Code überarbeitet
 * @filesource sirene.php
 * @author Taratan (Kargo)		
 */

if (! isset ( $session ))
{
	exit ();
}

$session ['user'] ['specialinc'] = basename ( __FILE__ );
$str_output = '`t';

switch ( $_GET ['op'])
{
	case '' :
		$str_output .= "Während du durch den Wald streifst, nimmst du plötzlich eine traumhafte Melodie war.
		`nDu horchst hin und stellst fest, dass der Ursprung der Melodie nicht weit von dir entfernt sein kann.
		`nWirst du ihr folgen?";
		
		addnav ( "Folge der Melodie", "forest.php?op=follow" );
		addnav ( "Zurück in den Wald", "forest.php?op=leave" );
	break;
	
	case "follow" :
		$str_output .= "Du entscheidest dich der Melodie zu folgen. Somit marschierst du mit geschlossenen Augen durch den Wald, bemüht die Melodie nicht zu verlieren ...";
		$rand = mt_rand ( 1, 10 );
		switch ( $rand)
		{
			case 1 :
			case 2 :
			case 3 :
			case 4 :
				$str_output .= "`n`n`2Zahlreiche Stunden läufst du durch den Wald, bis die Melodie urplötzlich verklingt. Als du dich in diesem Moment umschaust, stellst du fest, dass du dich komplett im Wald verlaufen hast.
				`nWährend du einen Weg zurück suchst, ärgerst du dich über dich selbst. In der verstrichenen Zeit hättest du problemlos Monster töten können.";
				$session ['user'] ['turns'] = max ( 0, $session ['user'] ['turns'] - 2 );
			break;
			case 5 :
			case 6 :
			case 7 :
				$str_output .= "`n`n`7Bis du plötzlich merkst, dass unter deinem rechten Fuß kein Boden mehr ist! Du stürzt in eine Grube und während du fällst wird dir bewusst, dass du diesen Fall nicht überleben wirst. Am Boden befinden sich zahlreiche Waffen anderer Opfer, die in diese Grube fielen und eben diese werden dich töten ...
				`nDu verlierst all dein Gold und 5% deiner Erfahrung!";
				
				killplayer();
				
				addnews ( $session ['user'] ['name'] . "`7 starb, als " . ($session ['user'] ['sex'] == 0 ? "er" : "sie") . " sich im Gesang der Sirene verlor!" );
				$session ['user'] ['specialinc'] = '';
			
			break;
			case 8 :
			case 9 :
			case 10 :
				$str_output .= "`n`n`7Bis die Melodie plötzlich klarer denn je ist. Langsam öffnest du deine Augen und erblickst einen kleinen See inmitten einer Lichtung. Darin ragt ein Fels hervor und eine bezaubernde Sirene sitzt auf ihm und singt ihr Lied.
				`nMit bedachtem Schritt näherst du dich ihr und als du den Rand des Sees erreicht hast, erblickst du im Wasser einen Lederbeutel.
				`nLeicht zögerlich schaust du zum Beutel und wieder hoch zur Sirene. Diese singt unaufhörlich ihr Lied weiter, ein ermutigendes Lied. Zur selben Zeit drängt dich eine Stimme in deinem Kopf dazu, dieses Geschenk endlich anzunehmen.";
				
				addnav ( "Nimm den Beutel", "forest.php?op=take" );
			break;
		}
	break;
	
	case "take" :
		$str_output .= "Vorsichtig greifst du ins Wasser und öffnest anschließend den Beutel ...";
		$rand_beutel = e_rand ( 1, 3 );
		switch ( $rand_beutel)
		{
			case 1 :
				$gold = e_rand ( $session ['user'] ['level'] * 35, $session ['user'] ['level'] * 85 );
				$str_output .= "`n`n`7Du magst deinen Augen kaum trauen. Darin befinden sich $gold Goldstücke! `n Erfreut über dieses Geschenk verneigst du dich und kehrst wieder zurück in den Wald, keinen Gedanken daran verschwendend, woher das Gold stammt. Als du dir den Beutel auf dem Rückweg nocheinmal genauer ansiehst, bemerkst du noch einen Zettel darin ...";
				$session ['user'] ['gold'] += $gold;
			break;
			case 2 :
				$gems = e_rand ( 1, 4 );
				$str_output .= "`n`n`7Du magst deinen Augen kaum trauen. Darin befinden sich $gems Edelsteine! `n Erfreut über dieses Geschenk verneigst du dich und kehrst wieder zurück. Keinen Gedanken daran verschwendend, woher diese Edelsteine stammen. Als du dir den Beutel auf dem Rückweg nocheinmal genauer ansiehst, bemerkst du noch einen Zettel darin ...";
				$session ['user'] ['gems'] += $gems;
			break;
			case 3 :
				$str_output .= "`n`n`7Du magst deinen Augen kaum trauen. Ein glitzerndes Pulver ist in die Höhe geschossen und lässt die Luft schimmern. Es ist ein wunderbarer Anblick! Als du anschließend in dein Spiegelbild im Wasser schaust, bemerkt du, dass auch du dich verschönert hast! Als du dir den Beutel auf dem Rückweg nocheinmal genauer betrachtest, findest du noch einen Zettel darin ...";
				$session ['user'] ['charm'] += 2;
			
			break;
		}
		$str_output .= "`n`n`yIch danke dir für deinen Besuch ... Lebe wohl!";
		$session ['user'] ['specialinc'] = '';
	break;
	case "leave" :
		$str_output .= "Du schaust noch einmal auf den Weg vor dir, schüttelst dann aber den Kopf.
		`n'`tSolche Dinge bringen in der Regel doch nur Pech`y', denkst du dir und gehst wieder deines Weges. Ab und zu erklingt das Lied noch in deinen Ohren, doch als du dich weit genug entfernt hast, ist auch diese Verschwunden ...";
		$session ['user'] ['specialinc'] = '';
	break;
}
output($str_output);
?>