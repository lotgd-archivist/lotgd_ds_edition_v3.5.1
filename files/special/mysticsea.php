<?php
/*
Geschrieben von Sir Arvex; © 2005
Nach einer Idee von Tatiascha
Bugs und Fehler an arvex@anagromataf.de
Erstmalig auf www.AnaGromAtaf.de erschienen
Version 1.05
Download auf http://files.arvex.de

Dezember 2007: Angepasst von Tyndal & Talion auf Dragonslayer-Edition
*/

if (!isset($session))
{
	exit();
}

$session['user']['specialinc'] = "mysticsea.php";
$str_output ='';
page_header("Der mystische See");

switch ($_GET['op'])
{
	case "":
		$str_output.=get_title('Der mystische See');
		$str_output.="`tWährend du so durch den Wald spazierst, lichten sich mit einem Male die Bäume.";
		$str_output.="Wie aus dem Nichts taucht vor dir ein kleiner mystischer See auf.`n";
		$str_output.="An seinem Ufer stehen riesige Trauerweiden, die silberne Oberfläche ist spiegelglatt und Nebelschwaden ziehen über das Wasser.";
		$str_output.="Du wirst von einer merkwürdigen Stimmung erfasst, wie magisch zieht dich dieser See an.`n`n";
		$str_output.="`yWas wirst du tun?";
		addnav("Der See");
		addnav("Schwimmen gehen", "forest.php?op=schwimm");
		addnav("Stein springen lassen", "forest.php?op=stein");
		addnav("Trauerweiden ansehen", "forest.php?op=trauer");
		addnav("Lieber nichts", "forest.php?op=nichts");
		break;
	
	case "nichts":
		$str_output="`n`tDu widerstehst der mystischen Anziehung des Sees lieber, irgendwie ist er dir unheimlich. Schnell machst du dich auf den Weg zurück in den Wald.";
		$session['user']['specialinc']="";
		break;

	case "schwimm":
		$str_output=get_title('`tDu bist Schwimmen gegangen');
		$str_output.="`tDu steigst aus deinen Kleidern, gehst vorsichtig in das kalte Wasser.";
		switch(e_rand(1,10))
		{ 
	        case 1:
			case 2: 
			case 3:
			case 4:
			case 5:
			case 6:
				$str_output.="Nachdem du ein paar Runden geschwommen bist, fühlst du dich erfrischt.`n`n";
				$str_output.="`yDu erhältst 2 Charmpunkte.";
				$session['user']['charm']+=2;
				$session['user']['specialinc']="";
				break;
			case 7:
				$str_output.="Nachdem du ein paar Züge geschwommen bist, tauchst du mutig auf den Grund des Sees.`n`n";
				$str_output.="`yDu findest einen Edelstein im Schlamm.";
				$session['user']['gems']++;
				$session['user']['specialinc']="";
				break;
			case 8:
			case 9: 
			case 10:
				$str_output.="Du schwimmst bis zur Mitte des Sees, als du einen Krampf bekommst. Schnell merkst du: das rettende Ufer wirst du nicht mehr erreichen!`n";
				$str_output.="`\tDu ertrinkst.`n`n";
				$str_output.="`yDu verlierst 5% deiner Erfahrung.";
				killplayer(0,5);
				addnews($session['user']['name']." `twurde tot am Ufer des verwunschenen Sees gefunden.");
				break;
		}
		
	break;

	case "stein":
		$str_output=get_title('`tDu läßt einen Stein springen');
		$str_output.="`yDu hebst einen der flachen Steine auf, die am Ufer verstreut liegen und versuchst ihn über das Wasser springen zu lassen.";
		switch(e_rand(1,10))
		{
			case 1:
			case 2: 
			case 3:
			case 4:
			case 5:
				$str_output.="Der Stein springt zweimal.`n`n";
				$str_output.="`tDu erhältst einen Waldkampf.";
				$session['user']['turns']++;
				$session['user']['specialinc']="";
				break;
			case 6:
				$str_output.="Der Stein springt fünfmal.`n`n";
				if ($session['user']['experience']<=100)
				{
					$str_output.="`tDu erhältst 45 Erfahrungspunkte.";
					$session['user']['experience']+=45;
					$session['user']['specialinc']="";
				}
				else
				{	
					$str_output.="`tDu erhältst `y".round($session['user']['experience']*0.05)." `tErfahrungspunkte.";
					$session['user']['experience']=round($session['user']['experience']*1.05);
					$session['user']['specialinc']="";
				}
				break;
			case 7:
			case 8:
			case 9: 
			case 10:
				$str_output.="Du scheiterst kläglich, der Stein versinkt mit einem lauten Platschen im Wasser.`n`n";
				if ($session['user']['turns']<=0)
				{
					$str_output.="`yDu fühlst dich echt deprimiert ... und lauschst noch stundenlang dem Hall des lauten Platschen";
					$session['user']['specialinc']="";
				}
				else
				{
					$str_output.="`yDu fühlst dich echt deprimiert ... und `yverlierst `yeinen Waldkampf.";
					$session['user']['turns']--;
					$session['user']['specialinc']="";
					break;
				}
		}
		
	break;

	case "trauer":
		$str_output=get_title('`tDu schaust dir die Trauerweiden an');
		switch(e_rand(1,10))
		{
			case 1:
			case 2: 
			case 3:
			case 4:
			case 5:
				$str_output.="Vorsichtig näherst du dich den Trauerweiden, doch wohl nicht vorsichtig genug.";
				$str_output.=" Einer der Äste holt plötzlich zu einem Schlag aus, und trifft dich mitten ins Gesicht.`n`n";
				if ($session['user']['hitpoints']<=10)
				{
					$str_output.="`yDer Schlag trifft dich mittem im Gesicht ... das macht dich nicht grade hübscher.";
					$session['user']['charm']-=2;
					$session['user']['specialinc']="";
				}
				else
				{
					$str_output.="`yDer Schlag trifft dich sehr sehr hart ins Gesicht ... dabei verlierst du fast alle Lebenspunkte.";
					$session['user']['hitpoints']=1;
					$session['user']['specialinc']="";
				}
				break;
			case 6:
			case 7:
			case 8:
			case 9:
				$str_output.="Du trittst durch die tiefhängenden Zweige der Weiden und untersuchst den Stamm genauer.`n`n";
				if ($session['user']['gold']<=75)
				{
					$str_output.="`y In einer kleinen Höhlung findest du `4".round($session['user']['level']*75)." `2Gold.";
					$session['user']['gold']+=round($session['user']['level']*75);
					$session['user']['specialinc']="";
				}
				elseif ($session['user']['gold']>75)
				{
					$str_output.="`yIn einer kleinen Höhlung findest du `4".round($session['user']['gold']*0.25)." `2Gold.";
					$session['user']['gold']+=round($session['user']['gold']*1.25);
					$session['user']['specialinc']="";
				}
				break;
			case 10:
				$str_output.="`yDu schiebst die tiefhängenden Äste der Weide zur Seite, als du auf dem Boden im Gras etwas funkeln siehst.`n`n";
				$str_output.="`2Du findest `4einen `2Edelstein.";
				$session['user']['gems']++;
				$session['user']['specialinc']="";
				break;
		}
	break;

}
output($str_output);

?>