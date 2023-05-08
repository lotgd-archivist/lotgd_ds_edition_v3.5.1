<?php
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

$jetzt = getgametime(true);
if ( $jetzt < 4 )
{
	output('Der Mond ist von Wolken verdeckt, kein Lichtstrahl erhellt den
	Wald. Und du selbst hast ja auch keine Lampe dabei, mitten in der Nacht.
	Leichtsinnig....`n`n
	Schon trittst du auf einen am Boden liegenden Ast, den du übersehen hast.
	Unglücklicherweise rutscht du auf dem moosbewachsenen Ast aus und fällst
	auf deinen Hosenboden.`n`n');
	$bis = ($session['user']['gold']>100 ? 3 : 2);
	$was = e_rand(1,$bis);
	switch ( $was ){
		case 1:
		output('Das musste nicht sein... Traurig klopfst du dir den Dreck von deiner
		Kleidung. Ist da nicht auch etwas eingerissen?`n
		`3Du verlierst einen Charmepunkt.');
		$session['user']['charm']--;
		break;
		case 2:
		output('Beim Aufstehen spürst du einen leichten Schmerz im Fuss. Wahrscheinlich
		hast du dir deinen Knöchel leicht verstaucht.`n
		`2Du verlierst ein paar Lebenspunkte.');
		$session['user']['hitpoints'] =round ($session['user']['hitpoints']*0.97);
		break;
		case 3:
		output('Ein wenig später bemerkst du, dass dir bei dem Sturz etwas Gold
		aus der Tasche gefallen sein muss.`n
		`$ In der Dunkelheit kannst du es nicht wiederfinden.');
		$session['user']['gold']= round ($session['user']['gold']*0.90);
		break;
	}
}

else if ( $jetzt < 8 )
{
	output('Auf deinem Weg durch den Wald störst du die Feen, die zu so früher
	Stunde den Tau auflesen.');
	$arr_spec=db_fetch_assoc(db_query('SELECT filename FROM specialty WHERE specid='.(int)$session['user']['specialty']));
	if($arr_spec['filename']>'')
	{
		require_once './module/specialty_modules/'.$arr_spec['filename'].'.php';
		$f2 = $arr_spec['filename'].'_info';
		$f2();
		if ( $session['user']['specialtyuses'][$info['fieldname'].'uses'] > 0 )
		{
			output('`nDie Feen rächen sich für die Störung und nehmen dir eine
			'.$info['color'].$info['specname'].'-Anwendung`0 für heute weg.');
			$session['user']['specialtyuses'][$info['fieldname'].'uses']--;
		}
		else
		{
			output('`nDu entschuldigst dich wortreich, die Feen verzeihen dir
			und du kannst weiterziehen.');
		}
	}
}

else if ( $jetzt < 15 )
{
	output('Du bemerkst, dass auch andere Bewohner den Tag im Wald verbringen.`n`n');
	$was = e_rand(1,3);
	switch ( $was ) {
		case 1:
		output('Du triffst einen Förster und fragst ihn, ob er auf seinem Weg einigen
		Gegnern für dich begegnet ist.`n
		Du hast Glück und der Förster weist Dir die Richtung. `^Dadurch gewinnst du
		einen Waldkampf!');
		$session['user']['turns']++;
		break;
		case 2:
		output('Du triffst auf eine Gruppe Schulkinder, die dich schnell umringen.
		Sie strahlen dich mit ihren grossen Augen an und dir wird warm ums Herz.`n
		Gerne kommst du ihrer Bitte nach, gemeinsam mit ihnen ein Lied zu singen.
		"Alle Vögel sind schon da...."`n`n
		`QDu vertrödelst einen Waldkampf, aber du hast Kinderherzen glücklich gemacht.');
		$session['user']['turns']--;
		break;
		case 3:
		output('Du triffst einen Heiler, der offensichtlich aus einer anderen Stadt
		stammt und hier einige seltene Pflanzen sucht.`n');
		if ( $session['user']['hitpoints'] < $session['user']['maxhitpoints'] ) {
			if ( $session['user']['gold'] > 10 ) {
				output('`^Für nur einen Zehnten deines Goldes heilt er dich.');
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
				$session['user']['gold']=round($session['user']['gold']*0.9);
			}
			else {
				output('`9Aber da du nur wenig Gold bei dir hast, kann er nichts für
				dich tun.');
			}
		}
		else {
			output('`9Aber da du gesund bist, kann er nichts für dich tun.');
		}
		break;
	}
}

else if ( $jetzt < 21 )
{
	output('Es ist schon Abend geworden und du rastest ein wenig, um dich auszuruhen
	und über den Tag nachzudenken.`n`n');
	$was = e_rand(1,3);
	switch ( $was ) {
		case 1:
		case 2:
		output('Die Pause hat dir gut getan, du erhältst einige Lebenspunkte.');
		if ( $session['user']['hitpoints'] < 20 ) {
			$session['user']['hitpoints']+= 5;
		}
		else {
			$session['user']['hitpoints']*=1.1;
		}
		break;
		case 3:
		output('Dabei versinkst du immer mehr ins Reich der Träume und verschläfst
		einen Waldkampf.');
		$session['user']['turns']--;
		break;
	}
}

else
{
	output('Ein Schwarm Glühwürmchen findet dich. In ihrem Licht kannst du auch
	zu später Stunde gut sehen.`n
	`^Du bekommst einen Waldkampf hinzu.');
	$session['user']['turns']++;
}
?>
