<?php

// 22072004

/*
- Beggar-Script by LionSource.com - ThunderEye
- made for LoGD 0.9.6 but should be work with newer versions
ALTER TABLE `accounts` ADD `gotfreegold` TINYINT(1) DEFAULT '0' NOT null ;
add "paidgold" in table "settings" and set "value" to 1
"gotfreegold"=>"Freigold genommen,bool",  - in user.php
"paidgold"=>"Gold das in Bettlergasse spendiert wurde (Wert-1),int", - in configuration.php

Änderungen by anpera:
- statt gotfreegold einzuführen, wird das von den empfangbaren Überweisungen abgezogen.
- Wert -1 entfernt
- Bild entfernt
- Für 0.9.7 ext (GER) angepasst
*/

// MOD by tcb, 17.5.05: angepasst an Ort: Tempel
// Mod by Maris, 07.06.06: Ruhmeshalleneintrag

require_once 'common.php';

page_header('Bettelstein');

if ($_GET['op']=='spenden')
{
	$maxgold = getsetting('beggarmax','25000') - getsetting('paidgold','0');
	$maxgold = min($maxgold,$session['user']['gold']);
	output('`0Von dem Elend am Bettelstein deprimiert, lässt du dich vor dem magischen Stein mit der blauen Aura nieder. Wild entschlossen, der Armut entgegen zu wirken, planst du Gold für die Bedürftigen zu spenden.
	`n`nJeder Verarmte kann dann von diesem Stein etwas Gold entnehmen.
	`n');
	output("<form action='beggar.php?op=spenden2' method='POST'>`)
	Du spendest <input name='goldspende' id='goldspende' size='5' value='".$maxgold."'> `^Goldstücke`) für die Bedürftigen.
	`n`n<input type='submit'value='Spendieren' id='goldspende'>
	</form>
	".focus_form_element('goldspende'));
	addnav('','beggar.php?op=spenden2');
	addnav('Zurück zum Marktplatz','market.php');
}

else if ($_GET['op']=='spenden2')
{
	$rowe = user_get_aei('beggar');
	
	$goldsumme = abs((int)$_POST['goldspende']);
	if ($session['user']['gold']<$goldsumme)
	{
		output('`)Du verfügst nicht über ausreichend Gold, um eine derartige Summe zu spenden.`nVersuche es erneut.');
		addnav('Zurück zum Stein','beggar.php');
	}
	else if ($goldsumme==0)
	{
		output('`)Du legst `^0 Goldstücke`) auf den Stein und bist verwundert, warum keiner reagiert. Hoppla, das war wohl nichts, versuche es erneut.');
		addnav('Zurück zum Stein','beggar.php');
	}
	else if (getsetting('paidgold','0')+$goldsumme>getsetting('beggarmax','25000'))
	{
		output('`)Du legst `^'.$goldsumme.' Goldstücke`) auf den Stein, aber nichts passiert. Scheinbar ist der Stein voll, wenn ein Stein überhaupt irgendwie voll sein kann. Enttäuscht nimmst du dein Gold wieder an dich.');
		addnav('Zurück zum Stein','beggar.php');
	}
	else if ($goldsumme<=10)
	{
		output('`)Du hast `^'.$goldsumme.' Gold`) gespendet. Wow, damit wirst du eine Menge Bettler glücklich machen...');
		if (e_rand(1,10)==2)
		{
			output('`n`n`4Du verlierst einen Charmepunkt!`0');
			$session['user']['charm']-=1;
		}
		addnav('Zurück zum Stein','beggar.php');
		savesetting('paidgold',getsetting('paidgold','0')+$goldsumme);
		$session['user']['gold']-=$goldsumme;
		user_set_aei(array('beggar'=>$rowe['beggar']-$goldsumme));
	}
	else if ($goldsumme<$session['user']['level']*2)
	{
		output('`)Eine Spende für die Armen sollte mindestens das Doppelte deines Levels (`^'.($session['user']['level']*2).' Goldstücke`)) betragen, sonst nimmt es niemand wahr.');
		addnav('Zurück zum Stein','beggar.php');
	}
	else
	{
		output('`0Eine Welle der Begeisterung schwappt durch die Bettlergasse. Du hast `^'.$goldsumme.' Goldstücke`0 gespendet und erntest von allen Betroffenen ein Lächeln!`n`)Nun können sich die Bedürftigen an dem Gold erfreuen.');
		addnav('Zurück zum Stein','beggar.php');
		if ($goldsumme>=$session['user']['level']*150 && e_rand(1,5)==2)
		{
			output('`n`n`rDu erhältst einen Charmepunkt! `0');
			$session['user']['charm']++;
		}
		savesetting('paidgold',getsetting('paidgold','0')+$goldsumme);
		$session['user']['gold']-=$goldsumme;
		user_set_aei(array('beggar'=>$rowe['beggar']-$goldsumme));
		$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'beggar',".$session['user']['acctid'].",\": hat `^$goldsumme Goldstücke`& auf dem Spenden-Stein hinterlegt!\")";
		db_query($sql);
	}
	
}

else if ($_GET['op']=='goldnehmen')
{
	$rowe = user_get_aei('goldin, beggar');
	$goldsumme=getsetting("paidgold","0");
	$golduser=round(2*($session['user']['level']*getsetting("transferperlevel",25)));
	$transleft = getsetting("transferreceive",3) - $rowe['goldin'];
	if ($transleft<=0)
	{
		if($rowe['goldin']==1234567)
		{
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Doch nichts passiert. Der Stein scheint dich als Halbtoten überhaupt nicht zu bemerken.');
		}
		else
		{
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#'.$session['user']['name'].'`n`3Gold erhalten: `^'.$rowe['goldin'].'x`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.');
		}
		addnav('Zurück zum Marktplatz','market.php');
	}
	else
	{
		
		if (getsetting('paidgold','0')<1)
		{
			addnav('Zurück zum Stein','beggar.php');
			output('`n`n`)Du trittst an den Spenden-Stein und möchtest etwas Gold wegnehmen. Zu deiner Enttäuschung musst du jedoch feststellen, dass da kein Gold mehr ist, was du nehmen könntest. Das nächste Mal solltest du schneller sein.');
		}
		else if ($session['user']['gold']>=$session['user']['level']*1000)
		{
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#'.$session['user']['name'].'`n`3Gold: `^'.$session['user']['gold'].'`# in der Hand`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.');
			addnav('Zurück zum Marktplatz','market.php');
		}
		else if ($session['user']['goldinbank']>=$session['user']['level']*1000)
		{
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#'.$session['user']['name'].'`n`3Gold: `^'.$session['user']['goldinbank'].'`# auf der Bank`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.');
			addnav('Zurück zum Marktplatz','market.php');
		}
		else if (($session['user']['goldinbank']+$session['user']['gold'])>=$session['user']['level']*1000)
		{
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#'.$session['user']['name'].'`n`3Gold: `^'.$session['user']['gold'].'`# in der Hand und `^'.$session['user']['goldinbank'].'`# auf der Bank, das macht `^'.($session['user']['gold']+$session['user']['goldinbank']).'`# insgesamt`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.');
			addnav('Zurück zum Marktplatz','market.php');
		}
		else if (($session['user']['weapondmg']>=15) && ($session['user']['armordef']>=15))
		{ //note by Salator: Man kann die zwar ablegen, aber deswegen die Items-Tabelle durchsuchen halte ich für overhead. Codeteil steht unten auskommentiert
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#'.$session['user']['name'].'`n`3Ausrüstung: `#'.$session['user']['weapon'].'`# und '.$session['user']['armor'].'`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.');
			addnav('Zurück zum Marktplatz','market.php');
		}
		else if ($session['user']['gems']+$session['user']['gemsinbank']>=$session['user']['level'])
		{
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#'.$session['user']['name'].'`n`3Edelsteine: `^'.($session['user']['gems']+$session['user']['gemsinbank']).'`# in der Hand`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.');
			addnav('Zurück zum Marktplatz','market.php');
		}
		else if ($session['user']['house']>0)
		{
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#'.$session['user']['name'].'`n`3Besitzt Haus Nummer: `^'.$session['user']['house'].'`#`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.');
			addnav('Zurück zum Marktplatz','market.php');
		}
		/*
		else if (item_count('owner='.$session['user']['acctid'].' AND (tpl_id="waffedummy" OR tpl_id="rstdummy") AND value1>14') >1)
		{
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Doch statt Gold erscheint nur eine Meldung:`n`n`3Name: `#'.$session['user']['name'].'`n`3Ausrüstung: `#'.$session['user']['weapon'].'`# und '.$session['user']['armor'].'`# und ein dicker Beutel`n`3Status: `#keine Übereinstimmung mit einer verarmten Person`n`n`3Zugriff auf die Goldreserven verweigert.');
			addnav('Zurück zum Marktplatz','market.php');
		}
		*/
		else if ($goldsumme<$golduser)
		{
			$golduser=$goldsumme;
			output('`n`n`)Mit einem beherzten Griff schnappst du dir das Gold von dem Stein. Nichts zu knapp, denn es waren nur noch `^'.$goldsumme.' Goldstücke`) übrig.');
			addnav('Zurück zum Stein','beggar.php');
			$session['user']['gold']+=$golduser;
			if($session['user']['dragonkills']>9)
			{
				debuglog('('.$session['user']['dragonkills'].'DK) nahm '.$golduser.' Gold vom Bettelstein');
			}
			savesetting('paidgold',strval(getsetting('paidgold','0')-$golduser));
			user_set_aei(array('goldin'=>$rowe['goldin']+$golduser,'beggar'=>$rowe['beggar']+$golduser));
		}
		else
		{
			output('`n`n`)Du trittst an den Spenden-Stein und hältst die Hände auf. Der Stein beginnt zu glühen und du bemerkst, dass du gescannt wirst. Vor dir materialisiert sich ein Häufchen Gold. Voller Dankbarkeit an den Spender nimmst du die bereitgelegten `^'.$golduser.' Goldstücke`) weg und gehst deines Weges.');
			addnav('Zurück zum Stein','beggar.php');
			$session['user']['gold']+=$golduser;
			debuglog('('.$session['user']['dragonkills'].'DK) nahm '.$golduser.' Gold vom Bettelstein');
			savesetting('paidgold',strval(getsetting('paidgold','0')-$golduser));
			
			user_set_aei(array('goldin'=>$rowe['goldin']+$golduser,'beggar'=>$rowe['beggar']+$golduser));
		}
	}
}

else
{
	addcommentary();
	output('`c`b`ZD`[e`_r `eB`)e`(ttel`)s`et`_e`[i`Zn`b`c`n`n `ZH`[i`_e`er `)l`(ungern verarmte Helden aller Ränge herum, die offenbar nicht wissen, dass man im Wald selber Gold verdienen kann, um sich der niveaulosesten aller Sachen herzugeben - betteln.
	`nIn einer Nische in dem magischen Felsen können Goldmünzen deponiert werden, die den armen Helden zugute kommen.');
	$goldsumme=getsetting('paidgold','0');
	if ($goldsumme>0)
	{
		$golduser=round(2*($session['user']['level']*getsetting("transferperlevel",25)));
		if ($goldsumme<$golduser)
		{
			$golduser=$goldsumme;
		}
		addnav($golduser.' Gold wegnehmen','beggar.php?op=goldnehmen');
		output('`n`n`eEs liegen noch `^'.$goldsumme.' Goldstücke`e auf dem Spenden-Stein bereit.');
	}
	output('`n`n`(Hier verliert kaum einer ein Wort, es wird nur gebettelt oder ge`)d`ea`_n`[k`Zt:`n');
	viewcommentary('beggar','Betteln',10,'bettelt',false,false,false,false,false,false);
	if($goldsumme<getsetting('beggarmax','25000'))
	{
		addnav('Gold spenden','beggar.php?op=spenden');
	}
	else
	{
		addnav('Gold spenden nicht möglich','');
	}
	addnav('Zurück zum Marktplatz','market.php');
}
page_footer();
?>
