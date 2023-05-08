<?php
require_once "common.php";

// Eine weiße Mauer zum Beschmieren, hier können Sprüche und Ähnliches angebracht werden.
// Erfordert : Textabschnitt aus "market.php" (gekennzeichnet durch "Mauer")
//
// by Maris (Maraxxus@gmx.de)

$wallchangetime=getsetting("wallchangetime","300");
$time=getsetting("wall_chgtime","0");
$oldtime=(strtotime($time));
$acttime=(strtotime(date("Y-m-d H:i:s")));
$newtime=$acttime-$oldtime;

if ($newtime>$wallchangetime)
{
	if ($_GET['op']=='write')
	{
		savesetting("wall_author",($session['user']['login']));
		savesetting("wall_chgtime",date("Y-m-d H:i:s"));
		
		$number=e_rand(1,20);
		
		switch($number)
		{
			case 1 :
			$message=$session['user']['login']." ist ".($session['user']['sex']?"die":"der")." Größte!";
			break;
			case 2 :
			$message=$session['user']['login']." zeigts euch allen!";
			break;
			case 3 :
			$message=$session['user']['login']." herrscht hier!";
			break;
			case 4 :
			$message=$session['user']['login']." war hier!";
			break;
			case 5 :
			$message=$session['user']['login']." lässt ".($session['user']['sex']?"Männer":"Frauen")."herzen höher schlagen!";
			break;
			case 6 :
			$message=$session['user']['login']." hat hier das Sagen!";
			break;
			case 7 :
			$message=$session['user']['login']." tritt euch allen in den Hintern!";
			break;
			case 8 :
			$message=$session['user']['login']." - von Freunden begehrt, von Feinden gefürchtet!";
			break;
			case 9 :
			$message=$session['user']['login']." ist ".($session['user']['sex']?"die":"der")." Schönste in der ganzen Stadt!";
			break;
			case 10 :
			$message=$session['user']['login']." ist jedermans Liebling!";
			break;
			case 11 :
			$message=$session['user']['login']." ist gottgleich!";
			break;
			case 12 :
			$message=$session['user']['login']." lässt die Erde erbeben!";
			break;
			case 13 :
			$message=$session['user']['login']." lehrt euch das Fürchten!";
			break;
			case 14 :
			$message=$session['user']['login']." ist euch überlegen!";
			break;
			case 15 :
			$message=$session['user']['login']." tötet Drachen wie kein Anderer!";
			break;
			case 16 :
			$message=$session['user']['login']." ist der Inbegriff von Eleganz und Schönheit!";
			break;
			case 17 :
			$message=$session['user']['login']." lässt andere ".($session['user']['sex']?"Frauen":"Männer")." vor Neid erblassen!";
			break;
			case 18 :
			$message=$session['user']['login']." ist schön und klug!";
			break;
			case 19 :
			$message=$session['user']['login']." kennt keine Furcht!";
			break;
			case 20 :
			$message=$session['user']['login']." überzeugt durch Witz und Charme!";
			break;
		}
		savesetting("wall_msg",$message);
	}

	if ($_GET['op']=='change')
	{
		savesetting('wall_chgtime',date('Y-m-d H:i:s'));
		$author = stripslashes(getsetting('wall_author','0'));

		$number=e_rand(1,22);

		$sql = "SELECT sex FROM accounts WHERE login='".db_real_escape_string($author)."'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
	
		switch($number)
		{
			case 1 :
				$message=' ist '.($row['sex']?'die':'der').' Schwächste!';
				break;
			case 2 :
				$message=' kann nix!';
				break;
			case 3 :
				$message=' isst aus der Mülltonne!';
				break;
			case 4 :
				$message=' nervt gewaltig!';
				break;
			case 5 :
				$message=' bringt '.($row['sex']?'Männer':'Frauen').' zum flüchten!';
				break;
			case 6 :
				$message=' hat hier nichts zu sagen!';
				break;
			case 7 :
				$message=' leckt euch allen den Hintern!';
				break;
			case 8 :
				$message=' stinkt wie ein Iltis!';
				break;
			case 9 :
				$message=' ist '.($row['sex']?'die':'der').' Hässlichste in der ganzen Stadt!';
				break;
			case 10 :
				$message=' ist dumm wie ein Stück Brot!';
				break;
			case 11 :
				$message=' verträgt kein Ale!';
				break;
			case 12 :
				$message=' ist ein Bauerntrampel!';
				break;
			case 13 :
				$message=' ist der Witz der Stadt!';
				break;
			case 14 :
				$message=' kämpft wie ein '.($row['sex']?'Kind':'Mädchen').'!';
				break;
			case 15 :
				$message=' ist dick und faul!';
				break;
			case 16 :
				$message=' - am Liebsten sehen wir '.($row['sex']?'sie':'ihn').' von hinten!';
				break;
			case 17 :
				$message=' hat ja gar keine Ahnung!';
				break;
			case 18 :
				$message=' bedeutet: großer Kopf und wenig Verstand!';
				break;
			case 19 :
				$message=' - was will man bei solch einem Namen auch schon erwarten?';
				break;
			case 20 :
				$message=' liegt gerade besoffen hinter dieser Mauer!';
				break;
				
			case 21 :
				$message=' hats drauf - Zahnbelag, DAUMENDICK!';
				break;
			case 22 :
				$message=' prellt '.($row['sex']?'Freudenjungs':'Freudenmädchen').'!';
				break;
		}
		
		$message=$author.$message;
		savesetting('wall_msg',$message);
	}
	
}

redirect('market.php'.($newtime<60?'?op=toolate':''));

?>
