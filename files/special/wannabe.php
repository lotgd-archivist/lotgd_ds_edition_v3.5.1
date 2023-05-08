<?php

// 22062004

/********************
Wannabe Knight #1
Wannabe Knight script with option to give chase.
Written by Robert for Maddnets LoGD
german by theKlaus
*********************/
if (!isset($session)) exit();
if ($_GET['op']=="")
{
//	output("<img src='./images/knight.gif' width='103' height='150' alt='Ritter' align='right'>",true);
	output('`^Du triffst einen `b`4Ritter Möchtegern,`b `^der dich mit seiner Kampfaxt angreift,`n
	`^aber du bist schneller! Deine Rüstung '.$session['user']['armor'].'`^ schützt dich und du wirst nicht verletzt!`n
	`^Du wehrst die Angriffe des `b`4Ritter Möchtegern`b `^ab, bis er sich umdreht und flüchet!`n
	`^Du hast dir etwas Erfahrung verdient!`n
	Du bemerkst, daß er verletzt ist und langsam rennt. Willst du ihn jagen und ganz fertig machen?`n
	Dies würde dich einen Waldkampf kosten, wenn du es tust.`n
	`&Was machst du?`n
	Jagst du ihn mit blutdurstigen Augen oder gehst du zurück in den Wald um andere Kreaturen zu bekämpfen?');
	$session['user']['experience']*=1.02;
	addnav('Jag den Feigling','forest.php?op=chase');
	addnav('Zurück in den Wald','forest.php?op=dont');
	$session['user']['specialinc']='wannabe.php';
}

else if ($_GET['op']=='chase')
{
	$session['user']['reputation']+=2;
	$session['user']['specialinc']='';
	output('`^Du beschließt, daß du den `4Ritter Möchtegern `^ein wenig jagst, `n
	`^bist dir aber nicht sicher, ob das das Richtige ist. Doch du willst Blutrache für seinen feigen Angriff auf dich.`n
	`^Du schnappst dir den `4Ritter Möchtegern`^, der sich schnell umdreht, seine Kampfaxt hebt und ');
	$session['user']['turns']--;
	switch(e_rand(1,5))
	{
		case 1:
			output('`^bevor du deine Waffe '.$session['user']['weapon'].'`^ heben kannst, wirst du schwer verletzt!`n
			Der `4Ritter Möchtegern `^schont dein Leben und geht stolzen Schrittes weiter seines Weges.`n
			`^Die Schwere deiner Wunden kostet dich 1 Waldkampf!');
			if ($session['user']['turns']>0) $session['user']['turns']--;
			break;
		case 2:
			output('`^du reagierst blitzartig mir deiner Waffe '.$session['user']['weapon'].'`^. Du wirst nicht verletzt!`n
			`^ Du kämpfst mit dem `4Ritter Möchtegern `^und wehrst jeden Angriff seiner Kampfaxt ab.`n
			Du schaffst es, ihn einige Male schwer zu treffen und ein weiteres Mal ergreift er die Flucht.`n
			Du bist jetzt zu erschöpft, um ihn nochmals zu jagen, hast aber einiges dabei gelernt!`n
			`^Du steigerst deine Erfahrung!`n');
			$session['user']['experience']*=1.02;
			break;
		case 3:
			output('`^und zielt auf deine Brust. Diesmal kann deine Rüstung '.$session['user']['armor'].' `^dich nicht komplett schützen.`n
			Ein mächtiger Schlag seiner Kampfaxt schickt dich auf den Boden. Er schont dein Leben und geht stolzen Schrittes weiter seines Weges.`n
			`^Du bist schwer verletzt und stellst fest, daß Rache keine gute Idee ist.`n
			`^Du verlierst 3% Erfahrung!`n');
			$session['user']['hitpoints']=2;
			$session['user']['experience']*=0.97;
			break;
		case 4:
			output('`^und zielt auf deine Brust. Du bist zu langsam und deine Rüstung '.$session['user']['armor'].'`^ versagt ihren Dienst.`n
			`5Du bist  tot! `n
			`^Du verlierst 5% deiner Erfahrung.`n
			Du kannst morgen wieder weiterspielen.');
			killplayer(100,5,0,'news.php','Tägliche News');
			addnews($session['user']['name'].'`0 wurde vom `4Ritter Möchtegern`0 niedergestreckt.');
			break;
		case 5:
			$gold = e_rand($session['user']['level']*15,$session['user']['level']*50);
			output('`^spricht: "`8 Ich habe Dich, '.$session['user']['name'].',`8 besiegt, aber Dir keinen Schaden zugefügt!`n
			Eigentlich hatte ich einen heimtückischen Troll gejagt und habe Dich mit ihm verwechselt.`n
			Als meine Sinne wieder klar waren, sah ich, daß Du garnicht der Troll bist. Ich schämte mich so sehr, daß ich mich umdrehte und davon lief. Aber das sollte ein Geheimnis zwischen uns bleiben. Ich gebe dir `5'.$gold.' Gold `8für dein Schweigen und wir sprechen nie wieder über diesen Tag.`^"');
			$session['user']['gold']+=$gold;
			//debuglog("got $gold gold from wannabe knight");
			break;
	}
}

else
{
	$session['user']['specialinc']='';
	output('`^Du verschwendest keine Zeit und gehst zurück in den Wald. ');
}


?>
