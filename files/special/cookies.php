<?php
/*
* Kekse! - Das Keksskript
* written by Asuka and Zelda (THX GIRLS!)
* coded by Warchild ( warchild@gmx.org )
* 4/2004
* Version 0.9dt
* Letzte Änderungen: 
* 20.11. 2008 Salator: Chance für positive Ereignisse erhöht
* 
*/

if ($_GET['op']=='')
{
    output('`6Auf der Suche nach weiteren Gegnern steigt dir plötzlich ein süßlicher Geruch in die Nase. Irritiert biegst du die Zweige des nächsten Buschs auseinander und hast klaren Ausblick auf einen nahezu kreisrunden Platz, der mit `7schwarz-`&weißen `6Platten ausgelegt ist, auf denen dünnes `2Gras `6wuchert.
	`nIn der Mitte dieses Ortes steht ein quadratischer `&weißer `6Stein, von leichtem Dunst umgeben, auf dem ein Gebäckstück in Form eines `^`bKekses`b `6liegt!
	`nDer verlockende Duft lässt erahnen, dass er `ifrisch`i ist, was ja eigentlich gar nicht sein kann...`n');

	// Player is a reptile
    /** @noinspection PhpUndefinedVariableInspection */
    if ($session['user']['race'] == 'ecs')
	{
		output('Deine Echsensinne sträuben sich vor dem Geruch menschlichen Back-Wahns, doch noch kämpfst du mit dir.
		`n`7Wirst du den Keks nehmen und trotz des Ekels hinunterschlingen?
		`n`7Oder lässt du lieber deine schuppigen Finger davon?');
	}
	else
	{
		output('`bNun liegt es an dir:`b
		`n`n`7Nimmst du den Keks, da du dem Duft einfach nicht wiederstehen kannst?!
		`n`7Oder lässt du den Keks liegen wo er ist und läufst zurück in den Wald, da dir sofort klar ist:
		`n`^Kekse im Wald? Das ist nicht normal!');
	}
	$session['user']['specialinc']='cookies.php';
	addnav('e?K&#101;ks essen','forest.php?op=cookie',true);
	addnav('Den Ort verlassen','forest.php?op=nocookie');
}
else
{
	$session['user']['specialinc']='';
	if ($_GET['op']=='cookie')
	{
		if ($session['user']['race'] == 'ecs') $rand = e_rand(1,9); // Echsen kriegen eher schlechte Kekse
		else $rand = e_rand(1,10);
		output('`6Du schnappst dir gierig den Keks. Kauend bemerkst du...`n');
		switch ($rand)
			{
			case 1:
				$lifelost = e_rand(0,$session['user']['hitpoints']-5);
				if ($lifelost < 0) $lifelost = 0;
				$session['user']['hitpoints'] -= $lifelost;
				output('`^`bes ist ein Butterkeks`b!
				`n`6Zu spät bemerkst du jedoch die `4Dunkle Aura,`6 die den Keks umgibt. Du stellst mit Schrecken fest, dass dieser Keks entweder verflucht oder von einem `5Dämon `6besessen sein muss.
				`n`^Der Keks erwacht zum Leben `6und verbeißt sich in deine Hand. Schmerzerfüllt reißt du den Keks los und rennst blutend und panisch in den Wald zurück.
				`n`n`&Du verlierst `4'.$lifelost.' Lebenspunkte`&!');
				break;
			case 2:
			case 3:
				output('`^`bes ist ein Schokokeks`b!
				`n`6Sogleich beginnst du seltsamerweise in `^Erinnerungen an '.($session['user']['sex']?'deinen Märchenprinzen':'deine Märchenprinzessin').' `6 zu schwelgen. Als du bemerkst, dass du den Keks schon aufgegessen hast und immer noch verträumt lächelst, fühlst du dich viel wohler in deiner Haut. Du kehrst gut gelaunt in den Wald zurück.
				`n`n`&Du erhältst `2einen Charmepunkt`&!');
				$session['user']['charm']++;
				break;
			case 4:
				output('`^`bes ist ein schlichter Keks`b!
				`n`6Fröhlich schmatzend bemerkst du, dass dieser Keks eine leckere Karamell-Füllung enthält. ');
				$sql='SELECT specname,usename FROM specialty WHERE specid='.$session['user']['specialty'];
				$result=db_query($sql);
				if(db_num_rows($result)>0)
				{
					$row=db_fetch_assoc($result);
					$row['usename']=$row['usename'].'uses';
					$session['user']['specialtyuses'][$row['usename']] = 0;
					output('Jedoch kannst du dich darüber nicht all zu lange freuen, denn die `^Füllung des Kekses beginnt plötzlich steinhart zu werden!`6 Sie verklebt dir deinen Mund! Panisch versuchst du noch die Zähne auseinander zu bekommen, doch vorerst wird dir das wohl nicht gelingen. Wutentbrannt stürmst du zurück in den Wald!
					`n`n`&Du hast heute keine Möglichkeit mehr, deine `4'.$row['specname'].' `&einzusetzen!');
				}
				break;
			case 5:
				output('`^`beinen Keks mit Orangenfüllung`b!
				`n`6Der ekelige Geschmack ist durchdringend und du spuckst sofort alles aus. Die Füllung muss wohl schon schlecht gewesen sein. `^Du fühlst dich ziemlich schlecht `6und musst dich erst einmal ein wenig ausruhen, bevor du weiterziehen kannst.
				`n`n`&Du verlierst `4einen Waldkampf`&!');
				$session['user']['turns']=max(0,$session['user']['turns']-1);
				break;
			case 6:
			case 7:
				$goldamount = e_rand(10,$session['user']['level'] * 10 + 1);
				$session['user']['gold'] += $goldamount;
				output('`^`bes ist ein Goldkeks`b!
				`n`6Wie schön wäre es doch, wenn der Keks echtes Gold wäre! Plötzlich springt ein kleiner Kobold aus dem Gebüsch, klaut dir den angebissenen Keks aus der Hand und rennt mit meckerndem Lachen davon. Wütend willst du dem Dieb hinterher rennen, bemerkst jedoch ein `^Säckchen voller Gold`6 vor deinen Füßen liegen, welches der Kobold wohl verloren haben muss. Zufrieden nimmst du das Säckchen Gold als Entschädigung an dich und verlässt die Lichtung wieder in Richtung Wald.
				`n`n`&Du erhältst `^'.$goldamount.'`& Gold!');
				break;
			case 8:
				output('`^`bden Geschmack des Asuze Kekses`b!
				`n`6Du kaust laut schmatzend und versuchst zu schlucken, doch du bemerkst, wie immer mehr Krümel sich in deinem Hals ansammeln. Verzweifelt nach Luft schnappend und keuchend fällt dir der Rest des Kekses aus der Hand, während dir allmählich die Sinne schwinden.
				`n`n`&Du `$stirbst den Krümeltod!`& Du verlierst all dein Gold und 5% deiner Erfahrung!');
				killplayer();
				addnews('`&'.$session['user']['name'].'`0 starb den `^Krümeltod`0!');
				break;
			case 9:
			case 10:
				$fightamount = e_rand(1,3);
				$session['user']['turns'] += $fightamount;
				output('`^`bes ist ein Gute-Laune-Keks`b!
				`n`6Du stellst fest, dass dies der `^leckerste Keks `6 aller Zeiten ist. Dieser umwerfende Geschmack hebt mächtig deine Laune; du bist bereit, ein paar Monstern mehr den Garaus zu machen.
				`n`n`&Du erhältst `2'.($fightamount==1?'1 Waldkampf':$fightamount.' Waldkämpfe').'`& dazu!');
				break;
			}
	}
	else
	{
		output('`6Du lässt die Zweige wieder leise zurückfallen und schleichst von dannen.
		`nIst doch nur Kinderkram, oder? Jopp, definitiv!');
	}
}
?>
