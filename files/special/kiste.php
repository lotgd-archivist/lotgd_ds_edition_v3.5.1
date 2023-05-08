<?php
/**
 * Ein griff in eine Kiste
 * @author Harthas
 * @copyright Harthas for Atrahor
 */

if (!isset($session)){
	exit(); #Wir wollen ja nicht, dass jemand unerlaubt unser Special betritt.
}

//Die Specialinc-Varbiable setzen
$spec = 0;


page_header('Die Kiste');

switch ( $_GET['op'] ){

	case 'ichwillhierweg':
		output('`3Beinahe schon schreiend rennst du von jenem alten Mann davon. Für deinen Geschmack schien er etwas ZU verhutzelt, um wirklich noch leben zu können. Viel zu unheimlich. Keuchend bleibst du schliesslich irgendwo mittem im Wald wieder stehen...');
		break;

	case 'ichgreifinsloch':
		$loch   = $_GET['loch'];
		$rand_1 = mt_rand( 1 , 6 );
		$rand_2 = mt_rand( 1 , 2 );

		output('`3Neugierig wie du bist, streckst du deine Hand in das '.$loch.'. Loch. Kaum hast du deine Hand wieder zurück gezogen, zerrt der Alte die Kiste auch schon wieder direkt an sich und rennt kichernd in den Wald davon.`n');

		switch ( $rand_1 ){

			case 1:
				$gold = $session['user']['level'] * 5 * $loch;

				switch( $rand_2 ){

					case 1:
						output('`3Doch leider fühlst du auch nach einer längeren Zeit keine wirkliche Veränderung... 
						`nVermutlich hat der Alte dich reingelegt, musst du dir wohl oder übel eingestehen...
						`nDoch plötzlich fällt dir etwas auf. Dein Goldbeutel ist etwas schwerer geworden. Mit einigen prüfenden Blicken stellst du fest, dass du insgesamt `^'.$gold.'`3 mehr Gold in deinem Beutel hast. War das etwa der Alte?');

						$session['user']['gold'] += $gold;
						break;

					case 2:
						output('`3Doch leider fühlst du auch nach einer längeren Zeit keine wirkliche Veränderung... 
						`nVermutlich hat der Alte dich reingelegt, musst du dir wohl oder übel eingestehen... 
						`nDoch plötzlich fällt dir etwas auf... Dein Goldbeutel ist etwas leichter geworden... Mit einigen prüfenden Blicken stellst du fest, dass du insgesamt `^'.$gold.'`3 weniger Gold in deinem Beutel hast. War das etwa der Alte?');

						$session['user']['gold'] = max(0,$session['user']['gold']-$gold);
						break;
				}
				break;


			case 2:
				$gems = $loch;

				switch( $rand_2 ){

					case 1:
						output('`3Doch leider fühlst du auch nach einer längeren Zeit keine wirkliche Veränderung...
						`nVermutlich hat der Alte dich reingelegt, musst du dir wohl oder übel eingestehen...
						`nDoch plötzlich fällt dir etwas auf... Dein Edelsteinbeutel ist etwas leichter geworden... Mit einigen prüfenden Blicken stellst du fest, dass insgesamt `9'.$gems.'`3 Edelsteine in deinem Beutel fehlen. War das etwa der Alte?');

						$session['user']['gems'] = max(0,$session['user']['gems']-$gems);
						break;

					case 2:
						output('`3Doch leider fühlst du auch nach einer längeren Zeit keine wirkliche Veränderung...
						`nVermutlich hat der Alte dich reingelegt, musst du dir wohl oder übel eingestehen...
						`nDoch plötzlich fällt dir etwas auf... Dein Edelsteinbeutel ist etwas schwerer geworden... Mit einigen prüfenden Blicken stellst du fest, dass insgesamt `9'.$gems.'`3 Edelsteine in deinem Beutel mehr sind. War das etwa der Alte?');

						$session['user']['gems'] += $gems;
						break;
				}
				break;

			case 6:
				$punkte = $session['user']['level'] * $loch * 2;

				switch( $rand_2 ){

					case 1:
						output('`3Zuerst scheint für dich kein spürbarer Effekt vorhanden zu sein, und erst nach einigen Momenten fühlst du dich auf einmal unglaublich schwach...
						`nIrgendwie scheinst du etwas von deiner momentanen Lebenskraft verloren zu haben. Doch fühlst du gleichzeitig auch, dass du dich bald wieder erholt haben wirst.');

						//User stirbt hier nicht!
						$session['user']['hitpoints'] = max(1,$session['user']['hitpoints']-$punkte);
						break;

					case 2:
						output('`3Anfangs bemerkst du noch keinen grossen Unterschied, erst nach einigen Minuten scheint es dir, als ob du um einiges vitaler wärst. Du hast insgesamt '.$punkte.' Lebenspunkte hinzugewonnen.
						`nLeider ist es dir auch bewusst, dass du sie bald wieder verlieren wirst...');

						$session['user']['hitpoints'] += $punkte;
						break;
				}
				break;


			default:
				output('`3Doch auch nach einigen Stunden fühlst du noch stets absolut keine Veränderung. War wohl nur ein Trick, um ahnungslose Wandersleute auf die Schippe zu nehmen...
				`nKopfschüttelnd über das Verhalten des Alten verschwindest du schliesslich...');
				break;
		}

		break;

	default:
		output('`3Verträumt vor dich hin spazierend wanderst du durch den dunklen Wald...
		`nGerade als du dich für einige Minuten der Ruhe hinsetzen wolltest, hörst du von Links plötzlich ein undefinierbares Knacken. Erschrocken springst du einige Schritte zurück, da aus eben jener Ecke ein grinsender Mann mit unglaublich verhutzeltem Gesicht geschritten kommt.
		`nVerwundert wendest du den Blick auf die Kiste in seinen Händen... Insgesamt befinden sich drei Löcher in dieser.
		`nIrgendwie sehen diese Löcher auch noch recht interessant aus...
		`nWas möchtest du tun?');

		addnav('Hineingreifen');
		addnav('1.Öffnung','forest.php?op=ichgreifinsloch&loch=1');
		addnav('2.Öffnung','forest.php?op=ichgreifinsloch&loch=2');
		addnav('3.Öffnung','forest.php?op=ichgreifinsloch&loch=3');

		addnav('Fliehen');
		addnav('Davonrennen','forest.php?op=ichwillhierweg');

		$spec = 1;
		break;
}


if ($spec)
{
	$session['user']['specialinc'] = basename(__FILE__);
}
else
{
	$session['user']['specialinc'] = '';
}
?>
