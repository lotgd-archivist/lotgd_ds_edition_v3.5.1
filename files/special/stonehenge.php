<?php
function ascii_output()
{
output('`0');
rawoutput("<pre>
          .-----------.         .------------.
          :`.__________`        :`.____________`  .-----.._
          '.'_.------._.'       `.|__.-------..'  |`-..___.'|
           | ||__..| | |-._ __   | | |.... || |-._`--..__ |.'---.
       .::/ / |    | | |  ___  `-| | |  _  || |   | | | | | \    `.
  _.-.`  -`.|_|    `.|.' /`.  `. '.|_\     `|.'   | | | | || `.    `.
 '.   `.   `-   _     _  |  `.__`   _          _  `.|.' | ||`. \     \
 | `. __`.  _           _ `. |   |     `--   _     _    '.||  \ `.____.
 /`. |   |  .'`---...____   `'---' _       _          _    | | `.|_..-'
 | /`|__.' |`---...___ . |  `--      ______________________`.|_| |  ` |
 | | | '|  `---...____|.'    _     |`._-_______-______-_____`. | |    |
.| | |  | _ |  | | | | '| `-       '.|  ' ' '  '  '  '  ' ' ' || |    |
' `| |  |   /  | | |'|  |      _    |'.__.-------.__.-----.__.'| | .- |\
|  | |' |   |  | | | |  |   `--   _ | |..|    |  |''|  -| |::| `.|_..-' |
\   `|_.' _ |  | |_| '. | .`-._     | |  | -  |  |  | _ | |  | `--     .'
 '.         |  | | |  | | |`.  `. _ | |  |  _ |  |  |   | |  |   _    `.
   '-. _    '._|.' |  | | |  `.__`. | |  | __ |  |  |__ | |  |  `-- _  |
      `''''.    _  `-.|.' '.  |   |  `|__| ___ `.|__| _ `.|__|        /
       LGB :    _    _      `.|_.-'   `-             _          _   .'
            `-._______.::.__     `-     _      _       ___.:::.__.-'
                            `----.....__..------------`
</pre>");
}

if ($_GET['op']=='' || $_GET['op']=='search'){
	output('`#Du wanderst auf der Suche nach etwas zum Bekämpfen ziellos durch den Wald. Plötzlich stehst du mitten auf einem Feld.
	In der Mitte kannst du einen Steinkreis sehen. Du hast das legendäre Stonehenge gefunden!
	Du hast die Leute in der Stadt über diesen mystischen Ort reden hören, aber du hast eigentlich nie geglaubt, dass es wirklich existiert.
	Sie sagen, der Kreis hat große magische Kräfte und dass diese Kräfte unberechenbar sind. Was wirst du tun?');
	output("`n`n<a href='forest.php?op=stonehenge'>Betritt Stonehenge</a>`n<a href='forest.php?op=leavestonehenge'>Lasse es in Ruhe</a>",true);
	addnav("S?Betritt Stonehenge","forest.php?op=stonehenge");
	addnav("Lasse es in Ruhe","forest.php?op=leavestonehenge");
	addnav("","forest.php?op=stonehenge");
	addnav("","forest.php?op=leavestonehenge");
	$session['user']['specialinc']="stonehenge.php";
}

else{
	$session['user']['specialinc']="";
	if ($_GET['op']=="stonehenge"){
		$rand = e_rand(1,22);
		if($session['user']['exchangequest']==23) $rand=1;
		output("`#Obwohl du weißt, daß die Kräfte der Steine unvorhersagbar wirken, nimmst du diese Chance wahr.
		Du läufst in die Mitte der unzerstörbaren Steine und bist bereit, die fantastischen Kräfte von Stonehenge zu erfahren.
		Als du die Mitte erreichst, wird der Himmel zu einer schwarzen, sternenklaren Nacht.
		Du bemerkst, dass der Boden unter deinen Füssen in einem schwachen Licht lila zu glühen scheint, fast so, als ob sich der Boden selbst in Nebel verwandeln will.
		Du fühlst ein Kitzeln, das sich durch deinen gesamten Körper ausbreitet.
		Plötzlich umgibt ein helles, intensives Licht den Kreis und dich. Als das Licht verschwindet ");
		switch ($rand){
			case 1:
			case 2:
				output('bist du nicht mehr länger in Stonehenge.`n`n
				Überall um dich herum sind die Seelen derer, die in alten Schlachten und bei bedauerlichen Unfällen umgekommen sind.
				Jede trägt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben.
				Du bemerkst mit steigender Verzweiflung, daß der Steinkreis dich direkt ins Land der Toten transportiert hat!`n`n
				`^Du wurdest aufgrund deiner dümmlichen Entscheidung in die Unterwelt geschickt.`n
				Da du physisch dorthin transportiert worden bist, hast du noch dein ganzes Gold.`n
				Du verlierst aber 5% deiner Erfahrung.`n
				Du kannst morgen wieder spielen.');
				ascii_output();
				if($session['user']['exchangequest']==23)
				{
					killplayer(0,5,0,'exchangequest.php','Zu den Schatten');
				}
				else
				{
					killplayer(0,5,0,'news.php','Tägliche News');
				}
				addnews('`%'.$session['user']['name'].'`# ist für eine Weile verschwunden und jene, welche gesucht haben, kommen nicht zurück.');
				break;
			case 3:
				output('liegt dort nur noch der Körper eines Kriegers, der die Kräfte von Stonehenge herausgefordert hat.`n`n
				`^Dein Geist wurde aus deinem Körper gerissen!`n
				Da dein Körper in Stonehenge liegt, verlierst du all dein Gold.`n
				Du verlierst 10% deiner Erfahrung.`n
				Du kannst morgen wieder spielen.');
				ascii_output();
				killplayer(100,10,0,'news.php','Tägliche News');
				$session['user']['donation']+=1;
				addnews('`%'.$session['user']['name'].'s`# lebloser Körper wurde auf einer leeren Lichtung gefunden.');
				break;
			case 4:
			case 5:
			case 6:
				$reward = round($session['user']['experience'] * 0.1);
				output('fühlst du eine zerrende Energie durch deinen Körper zucken, als ob deine Muskeln verbrennen würden.
				Als der schreckliche Schmerz nachlässt, bemerkst du, dass deine Muskeln VIEL grösser geworden sind.`n`n
				`^Du bekommst `7'.$reward.'`^ Erfahrungspunkte!');
				ascii_output();
				$session['user']['experience'] += $reward;
				break;
			case 7:
			case 8:
			case 9:
			case 10:
				$reward = e_rand(1, 3); 		// original value: 1,4
				if ($reward == 4) $rewardn = 'VIER`^ Edelsteine';
				else if ($reward == 3) $rewardn = 'DREI`^ Edelsteine';
				else if ($reward == 2) $rewardn = 'ZWEI`^ Edelsteine';
				else if ($reward == 1) $rewardn = 'EINEN`^ Edelstein';
				output('...`n`n`^bemerkst du `%'.$rewardn.' vor deinen Füssen!`n`n');
				ascii_output();
				$session['user']['gems']+=$reward;
				//debuglog("found $reward gems from Stonehenge");  // said 4 gems ... can be less!!
				break;
			case 11:
			case 12:
			case 13:
				output('hast du viel mehr Vertrauen in deine eigenen Fähigkeiten.`n`n
				`^Dein Charme steigt!');
				ascii_output();
				$session['user']['charm'] += 2;
				break;
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
				output('fühlst du dich plötzlich extrem gesund.`n`n
				`^Deine Lebenspunkte wurden vollständig aufgefüllt.');
				ascii_output();
				$session['user']['hitpoints'] = max($session['user']['maxhitpoints'],$session['user']['hitpoints']);
				break;
			case 19:
			case 20:
				output("fühlst du deine Ausdauer in die Höhe schiessen!`n`n
				`^Deine Lebenspunkte wurden `bpermanent`b um `72 `^erhöht!");
				ascii_output();
				$session['user']['maxhitpoints'] += 2;
				$session['user']['hitpoints'] = max($session['user']['maxhitpoints'],$session['user']['hitpoints']);
				break;
			case 21:
			case 22:
				$prevTurns = $session['user']['turns'];
				if ($prevTurns >= 3) $session['user']['turns']-=3;
				else $session['user']['turns']=0;
				$currentTurns = $session['user']['turns'];
				$lostTurns = $prevTurns - $currentTurns;
				output('ist der Tag vergangen. Es scheint, als hätte Stonehenge dich in der Zeit eingefroren.`n
				Das Ergebnis ist, daß du '.$lostTurns.' Waldkämpfe verlierst!');
				ascii_output();
				break;
		}
	}
	else{
		output('`#Du fürchtest die unglaublichen Kräfte von Stonehenge und beschließt, die Steine lieber in Ruhe zu lassen. Du gehst zurück in den Wald.');
	}
}
?>