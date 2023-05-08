<?php
//Ein Murmelspiel-Special von Salator

if(!isset($session)) exit;

page_header('Stadtzentrum');
$session['user']['specialinc']='village_marblegame.php';

switch($_GET['op']) {
	case 'play': {
		if(item_count('tpl_id="glasperle" AND owner='.$session['user']['acctid'])>0) {
			output('`2Du gesellst dich zu den Kindern und fragst ob du mitspielen darfst. Sie haben nichts dagegen. Also wirfst du eine Murmel.`n');
			switch(e_rand(0,$session['children'])) {
				case 0:
					output('Das war ein Meisterwurf! Deine Murmel landet dicht neben dem Loch. Es gelingt dir mit Leichtigkeit, sie in der 2. Runde zu versenken. Du gewinnst '.$session['children'].' Murmeln von den anderen Kindern.');
					for($i=0; $i<$session['children']; $i++) {
						item_add($session['user']['acctid'],'glasperle');
					}
					break;
				case 1:
				case 2:
					output('Gut geworfen! Deine Murmel landet einige Zentimeter neben dem Loch. Leider schaffst du es in der 2. Runde nicht, sie zu versenken. Deine Murmel ist verloren.');
					item_delete('tpl_id="glasperle" AND owner='.$session['user']['acctid'],1);
					break;
				default:
					output('Sie landet auch günstig. Doch eines der Kinder trifft exakt die selbe Stelle und schleudert deine Murmel weg. Somit brauchst du zu lange um deine Murmel zu versenken und verlierst deinen Einsatz.');
					item_delete('tpl_id="glasperle" AND owner='.$session['user']['acctid'],1);
					break;
			}
		addnav('Nochmal spielen','village.php?op=play');
		}
		else {
			output('`2Leider hast du keine Murmel, die du einsetzen könntest. So bleibt dir nichts weiter übrig als die Kinder alleine zu lassen.');
		}
		addnav('Zum Stadtzentrum','village.php?op=leave');
		break;
	} //end Murmelspiel

	case 'leave': {
		$session['user']['specialinc']='';
		unset ($session['children']);
		redirect('village.php');
		break;
	} //end leave

	default: {
		$session['children']=e_rand(2,4);
		output('`2Als du den Stadtplatz betrittst, erblickst du an einer Baumgruppe '.$session['children'].' Kinder, welche sich beim Murmelspiel vergnügen.`n
		Du beobachtest sie eine Weile. Die Regeln sind einfach: Der Reihe nach wirft jeder eine Murmel. Ab der zweiten Runde muss die Murmel durch Anschubsen bewegt werden. Wer seine Murmel zuerst im Loch versenkt, bekommt die Murmeln der Mitspieler.`n
		Sofern es dir dein Stolz nicht verbietet, könntest du ja eine Runde mitspielen.');
		if($session['user']['exchangequest']==1) {
			output('`n`n`%Ein Junge sitzt traurig etwas abseits. Er hat alle seine Murmeln verloren und kann also nicht mehr mitspielen.`n
			Da erinnerst du dich, dass sich in deinem Beutel die bunte Murmel befindet, welche dir die Fee geschenkt hat.`n
			Möchtest du deine Murmel verschenken und den Jungen wieder mitspielen lassen?');
			addnav('`%Verschenke deine Murmel','exchangequest.php');
		}
		addnav('Murmeln spielen','village.php?op=play');
		addnav('Zum Stadtzentrum','village.php?op=leave');
		break;
	} //end Start/Standardseite
} //end main switch
?>