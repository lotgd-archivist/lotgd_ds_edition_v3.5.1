<?php
/* 
 * bushes
 * in the forest you have to be careful not to lose your goldbag
 *
 * region: forest
 *
 * v.1.0 040415(yymmdd)basis erstellt
 * v.1.1 040416(yymmdd)eingefaerbt und debuglog zugefuegt
 *
 * by bibir
 */
 
if (!isset($session)) exit();

output('`2Als du durch den Wald gehst, um nach Monstern Ausschau zu halten, kommst du zu einer Stelle, an der dir viele Büsche den Weg erschweren.`nDu bemerkst ein Ziehen und Zerren an deinem Goldbeutel.`n`n`n`0');
$gold_lost=getsetting('bushesgold','0');

switch(e_rand(1,3)){
	case 1: //goldbeutel ist weg
		output('`2Doch bevor du dich darum kümmern kannst, ihn festzuhalten, ist er dir auch schon abgerissen und hoffnungslos im Dickicht verloren.`n`n`6Du verlierst all dein Gold.`n`0');
		//donationpoints als entschaedigung
		if ($session['user']['gold'] > 1000) {
			$session['user']['donation']+= 3;
		}
		else if ($session['user']['gold'] > 100) {
			$session['user']['donation']+= 2;
		}
		else {
			$session['user']['donation']+= 1;
		}
		$gold_lost += $session['user']['gold'];
		$session['user']['gold']=0;
		if($session['user']['gems']>1500) {
			//Edelsteinverlust nur noch einmal pro Realwoche auftreten lassen
			$sql='SELECT * 
				FROM debuglog 
				WHERE actor='.$session['user']['acctid'].'
				AND message LIKE "verlor % Edelsteine im Gebüsch"
				AND DATEDIFF(NOW(),date) < 7';
			$result=db_query($sql);
			if(db_num_rows($result)==0)
			{
				//$gems_lost=floor($session['user']['gems']/200);
				$gems_lost=3;
				$session['user']['gems']-=$gems_lost;
				debuglog('verlor '.$gems_lost.' Edelsteine im Gebüsch');
				output('Zu dumm, dass in deinem Edelsteinbeutel kein Platz mehr war und du einige Steinchen im Goldbeutel hattest. `6Du verlierst daher auch noch '.$gems_lost.' Edelsteine.`0');
			}
		}
		break;
	case 2: //goldbeutel festgehalten
		output('`2Gerade rechtzeitig genug bemerkst du, wie sich dein Goldbeutel schon etwas gelöst hat.`nHastig machst du ihn wieder fest und bei deinem weiteren Weg hältst du ihn aufmerksam fest.`n`n`@Der wird dir so schnell nicht abhanden kommen.`0');
		break;
	case 3: //goldbeutel gefunden
		output('`2Gerade rechtzeitig genug bemerkst du, wie sich dein Goldbeutel schon etwas gelöst hat.`nSchnell machst du ihn wieder fest und kontrollierst, ob noch alles drin ist.`n`nAls du deinen Weg fortsetzen willst, entdeckst du einen Goldbeutel im Gebüsch hängen.Du mußt einiges an Kraft aufbringen, um den Beutel aus dem Gebüsch zu befreien.`n`n`@Da war wohl jemand nicht so vorsichtig, wie du selbst.`0`n`n');
		if($gold_lost == 0) {
			output('`6Leider ist der Goldbeutel leer.`n`2Da hat aber jemand mehr Glück gehabt, als Verstand.`0`n');
		}
		else {
			output('`6Als du den Goldbeutel aufmachst, zählst du `$'.$gold_lost.' `6Goldstücke, die nun dein sind.`n`n`0');
		}
		$session['user']['gold']+=$gold_lost;
		$gold_lost='0';	
		break;
		
	default:
		output('was war denn das?');
}

savesetting('bushesgold',$gold_lost);

?>
