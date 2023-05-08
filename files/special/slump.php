<?php
/**
* slump.php: 
* you fall over your own feet or a branche
* your moneybag gets broken and all your money lays on the ground
* most of the gold you can collect but some pieces are grabed by the newest player and the last dragonfighter
* if they are set (like it is by anpera)
*
* region: Forest
* v.1.0 040422(yymmdd)basis erstellt
* @author bibir, Bugfixing by talion
* @version DS-E V/2
*/

checkday();

$newplayer=stripslashes(getsetting("newplayer",""));
$newdk=stripslashes(getsetting("newdragonkill",""));

$sql="SELECT acctid,name,goldinbank,sex FROM accounts WHERE name like '".db_real_escape_string($newplayer)."' LIMIT 1";
$result = db_query($sql);
$rownew = db_fetch_assoc($result);

$sql="SELECT acctid,name,goldinbank,sex FROM accounts WHERE name like '".db_real_escape_string($newdk)."' LIMIT 1";
$result = db_query($sql);
$rowdk = db_fetch_assoc($result);


output("`6Plötzlich stürzt du - waren es deine etwas ungeschickten Füße, oder doch ein Ast?
`nDas spielt jetzt auch keine Rolle mehr, denn nun liegst du am Boden und bemerkst, dass dein Goldbeutel zerrissen und sein ganzer Inhalt auf dem Boden verteilt ist.`n`n`0");

/** @noinspection PhpUndefinedVariableInspection */
if ($session['user']['gold']== 0)
{
	output("`^Zum Glück hast du kein Gold, welches dir verloren gehen könnte.`n`n`0");
}

// falls selbst juengster spieler oder drachenkämpfer
// oder kein juengster spieler und drachenkaempfer in den settings
else if ( ( empty($rownew['acctid']) && empty($rowdk['acctid']) ) || ($session['user']['acctid'] == $rownew['acctid']) || ($session['user']['acctid'] == $rowdk['acctid']) )
{
	output("`^Schnell sammelst du dein Gold wieder ein und gehst deinen Weg weiter.`n`n`0");
}

//kein juengster spieler - nur juengster drachenkaempfer
else if (empty($rownew['acctid']))
{
	//output("`n`^es gibt keinen juengsten spieler`0`n");
	output("`6Schnell willst du das Gold wieder einsammeln, doch bei ein paar Goldstücken ist jemand schneller.
	`n`n`3Du denkst: \"`#".($rowdk['sex'] ? 'Die' : 'Der')." hat doch gerade eine Heldentat vollbracht - wie war der Name noch gleich?
	`nAh,`0 ".$rowdk['name']."`#muss es gewesen sein.`3\"
	`n`n`6Jetzt ist es zu spät, so flink ".($rowdk['sex'] ? 'sie' : 'er')." beim Aufheben war, so flink ist ".($rowdk['sex'] ? 'sie' : 'er')." auch verschwunden.`n`n");
	//goldverteilung
	$save = round($session['user']['gold']*0.8,0);
	$lost = round($session['user']['gold']*0.2,0);
	$lost = min(2000,$lost);
	$mailmessage = "`^".$session['user']['name']."`2 stürzte im Wald über einen Ast und verlor dabei ".($session['user']['sex']?"ihr":"sein")." Gold.
	`n`nEin paar Goldstücke rollten dir dabei vor die Füße, die du aufgehoben und behalten hast. Die gefundenen `^ $lost `2Goldstücke hast du direkt zur Bank gebracht.`0";
	systemmail($rowdk['acctid'],"`2Du hast Gold im Wald gefunden",$mailmessage);
	$session['user']['gold']=$save;
	$dkgain = $rowdk['goldinbank']+ $lost;
	output("`^Du hast wenigstens noch ".$save."Goldstücke retten können.`0`n`n");
	
	user_update(
		array
		(
			'goldinbank'=>$dkgain
		),
		$rowdk['acctid']
	);
}

else if (empty($rowdk['acctid']))
{
	//kein juengster drachenkaempfer nur juengster spieler
	output("`6Schnell willst du das Gold wieder einsammeln, doch bei ein paar Goldstücken ist jemand schneller.
	`n`n`3Du denkst: \"`#".($rowdk['sex'] ? 'Die' : 'Der')." ist doch gerade neu in ".getsetting('townname','Atrahor')." - wie war der Name noch gleich?
	`n\"`#Ah,`0 ".$rownew['name']."`#muss es gewesen sein.`6\"`n`n");
	//goldverteilung
	$save = round($session['user']['gold']*0.8, 0);
	$lost = round($session['user']['gold']*0.2, 0);
	$lost = min(2000,$lost);
	$mailmessage = "`^".$session['user']['name']."`2 stürzte im Wald über einen Ast und verlor dabei ".($session['user']['sex']?"ihr":"sein")." Gold.
	`n`nEin paar Goldstücke rollten dir dabei vor die Füße, die du aufgehoben und behalten hast. Die gefundenen `^ $lost `2Goldstücke hast du direkt zur Bank gebracht.`0";
	systemmail($rownew['acctid'],"`2Du hast Gold im Wald gefunden",$mailmessage);
	$session['user']['gold']=$save;
	$newgain = $rownew['goldinbank']+ $lost;
	output("`^Du hast wenigstens noch $save Goldstücke retten können.`0`n`n");
	
	user_update(
		array
		(
			'goldinbank'=>$newgain
		),
		$rownew['acctid']
	);
	
}
else
{
	output("`6Schnell willst du das Gold wieder einsammeln, doch bei ein paar Goldstücken ist jemand schneller.
	`n`n
	Den einen Dieb hast du doch gerade erst neu hier in ".getsetting('townname','Atrahor')." gesehen und der andere hatte eben eine Heldentat vollbracht - wie waren ihre Namen noch gleich?
	`n`n`#\"`0".$rownew['name']."`# und `0".$rowdk['name']."`# müssen es gewesen sein.`3\"`6, denkst du.
	`n`nJetzt ist es zu spät, so flink sie beim Aufheben waren, so flink sind sie auch verschwunden.`n`n");
	// goldverteilung
	$newgain = round($session['user']['gold']*0.2);
	$dkgain = $newgain >>1; //Bitshift statt neue Berechnung mit 0.1
	$save = $session['user']['gold']-$newgain-$dkgain;
	
	$newgain = min(2000,$newgain);
	$dkgain = min(2000,$dkgain);
	
	$mailmessage1 = "`^".$session['user']['name']."`2 stürzte im Wald über einen Ast und verlor dabei ".($session['user']['sex']?"ihr":"sein")." Gold.
	`n`nEin paar Goldstücke rollten dir dabei vor die Füße, die du aufgehoben und behalten hast. Die gefundenen `^ $dkgain `2Goldstücke hast du direkt zur Bank gebracht.`0";
	systemmail($rowdk['acctid'],"`2Du hast Gold im Wald gefunden",$mailmessage1);
	
	$mailmessage2 = "`^".$session['user']['name']."`2 stürzte im Wald über einen Ast und verlor dabei ".($session['user']['sex']?"ihr":"sein")." Gold.
	`n`nEin paar Goldstücke rollten dir dabei vor die Füße, die du aufgehoben und behalten hast. Die gefundenen `^ $newgain `2Goldstücke hast du direkt zur Bank gebracht.`0";
	systemmail($rownew['acctid'],"`2Du hast Gold im Wald gefunden",$mailmessage2);
	
	$newgain += $rownew['goldinbank'];
	$dkgain += $rowdk['goldinbank'];
	$session['user']['gold']=$save;
	output("`^Du hast wenigstens noch $save Goldstücke retten können.`0`n`n");
	
	user_update(
		array
		(
			'goldinbank'=>$newgain
		),
		$rownew['acctid']
	);
	
	user_update(
		array
		(
			'goldinbank'=>$dkgain
		),
		$rowdk['acctid']
	);
}

$reward=round(e_rand($session['user']['experience']*0.05, $session['user']['experience']*0.1));
output("`@Du hast gelernt, dass man vorsichtig sein muss, wohin man seine Schritte setzt
`nund erhältst `& $reward `@Erfahrungspunkte.`0");
$session['user']['experience']+=$reward;
?>
