<?php
/**
Kleines sinnloses Waldevent:
-Eine blinde Taube liefert eine Nachricht an irgendeinen zufälligen Spieler aus,
 der gerade eingeloggt ist
-Man bekommt auch noch ein paar Erfahrungspunkte
by Maris (Maraxxus@gmx.de)
Erweiterungen Taube killen, Rätselversand, lahme Taube by Salator
**/

$session['user']['specialinc']='randomyom.php';
if ($_GET['op']=='')
{
	output('`5Als du durch das Dickicht schleichst, entdeckst du plötzlich eine freie Fläche, auf der viele Körner verstreut sind.
	`nDir fallen auch einige `^Brieftauben`5 auf, die emsig damit beschäftigt sind, diese Körner aufzupicken.
	`nEbenso erblickst du eine scheinbar blinde Taube am Rand, die schon recht ausgemärgelt ist und immer wieder versucht eines der Körner zu erhaschen.
	`nDoch jedesmal kommt ihr eine andere Taube zuvor und schnappt ihr das Futter vor dem Schnabel weg.
	`n`nIrgendwie empfindest du Mitleid für das arme Tier.
	`nWas willst du tun?`n');

	addnav('Die blinde Taube füttern','forest.php?op=feed');
	addnav('Alle Tauben verjagen','forest.php?op=scare');
}
else
{
	if ($_GET['op']=='feed')
	{
		$link = 'forest.php?op=write';
		addnav('',$link);
		output('`5Du scheuchst ein paar der Tauben mit der Hand auf die Seite und sammelst einige Körner auf, um sie der blinden Taube zu geben.
		`nDankbar und ausgehungert stürzt sie sich auf das Futter.
		`nSie ist dir dafür so dankbar, dass sie eine Nachricht für dich übermitteln wird:`n`n');
		output("<form action='".$link."' method='POST'>
				Dein Brief: <input id='yominput' type='text' name='message' size='100' maxlength='500'>`n`n
				<input type='submit' class='button' value='Abschicken!'></form>",true);
		JS::Focus("yominput");
	}
	elseif ($_GET['op']=='write')
	{
		$message = $_POST['message'];
		if(mb_strpos(mb_strtolower($message),'kill')!==false)
		{
			output('`5Gerade als du der Taube deine Botschaft übergeben willst fällt mit lautem Krachen ein Baum um und trifft die Taube.
			`n`&Du solltest vorsichtiger sein mit dem was du schreibst!
			`n`5Naja, immerhin soll gebratene Taube gut schmecken, also packst du das Tier in deinen Beutel.');
			$item=array('tpl_name'=>'Blinde Taube','tpl_gold'=>10,'tpl_description'=>'Schreibe niemals: '.$message);
			item_add($session['user']['acctid'],'beutdummy',$item);
			$session['user']['specialinc']='';
		}
		elseif (mb_strlen($message) < 5)
		{
			$link = 'forest.php?op=write';
			addnav('',$link);
			output('`&Du kannst keine Nachricht mit weniger als 5 Zeichen verschicken!`n`n');
			output("<form action='".$link."' method='POST'>
					Dein Brief: <input type='text' name='message' size='100' maxlength='500'>`n`n
					<input type='submit' class='button' value='Abschicken!'></form>",true);
		}
		else
		{
			if (mb_strlen(trim($message)) < 5)
			{ //es gibt tatsächlich Leute die 5 Leerzeichen schreiben. Den Zahn ziehen wir jetzt *g*
				$sql='SELECT riddle,answer FROM riddles WHERE enabled=1 ORDER BY RAND() LIMIT 1';
				$result=db_query($sql);
				$row=db_fetch_assoc($result);
				$message=$row['riddle'].'`n`n'.$row['answer'];
			}
			$sql = 'SELECT acctid FROM accounts WHERE '.user_get_online().' ORDER BY RAND() LIMIT 1';
			$result = db_query($sql);
			$amount = db_num_rows($result);

			if ($amount>0)
			{
				$row=db_fetch_assoc($result);
				if($row['acctid']==$session['user']['acctid'])
				{
					redirect('forest.php?op=crash');
				}
				systemmail($row['acctid'],'`^Blinde Brieftaube!`0','`&Eine blinde Brieftaube krallt sich nach einer wackeligen Landung auf deine Schulter.
				`nSie hat folgende Nachricht bei sich, die '.$session['user']['name'].' `& geschrieben haben muss:
				`n`n`5'.$message);
			}
			$gain=round($session['user']['experience']*0.01);
			output('`5Als du die Nachricht geschrieben hast, erhebt sich die blinde Taube in die Lüfte und fliegt davon.
			`nDu fragst dich wer deinen Brief erhalten wird, und ob dieser Vogel es überhaupt fertig bringt irgendwem irgendetwas auszuliefern...
			'.($gain > 0 ? '`nDennoch hat dich diese Tat ein wenig klüger gemacht.
			`n`^Du erhältst '.$gain.' Punkte Erfahrung!`n' : '')
			);
			$session['user']['experience']+=$gain;
			$session['user']['specialinc']='';
		}
	}
	elseif ($_GET['op']=='scare')
	{
		output('`5So wie du es schon in deiner Kindheit geliebt hast, rennst du mit lautem Geschrei und wild rudernden Armen über den Platz.
		`nDie Brieftauben flattern aufgeschreckt hoch und fliegen in alle Richtungen weg.
		`nDu fühlst dich... irgendwie beobachtet...');
		addnews($session['user']['name'].'`# wurde dabei gesehen wie '.($session['user']['sex']?'sie':'er').' Brieftauben verjagt hat. Man munkelt nun, '.($session['user']['sex']?'sie':'er').' sei dafür verantwortlich, dass manche Nachrichten nicht ankommen!');
		$session['user']['specialinc']='';
	}
	elseif ($_GET['op']=='crash')
	{ //man würde die Taube selbst erhalten
		output('`5Als du die Nachricht geschrieben hast, erhebt sich die blinde Taube in die Lüfte und fliegt davon.
		`nEs dauert jedoch nicht lange, als sie mit einer anderen Taube zusammenstößt, die verträumt die Lichtung ansteuert.
		`nKurz darauf fallen dir 2 Tauben vor die Füße. Dem blinden Tier ist offenbar nicht mehr zu helfen, auch das andere Tier scheint angeschlagen...
		`nWas nun?');
		addnav('p?Die lahme Taube pflegen','forest.php?op=care');
		addnav('k?Die lahme Taube killen','forest.php?op=kill');
	}
	elseif ($_GET['op']=='care')
	{
		output('`5Du kannst das Leiden der Taube nicht mit ansehen und versuchst, dem Tier zu helfen. Schon bald geht es der Taube besser und sie fliegt weiter, ihrem Ziel entgegen.');
		if(getsetting('symp_active',0)==1)
		{
			$sql='SELECT acctid FROM accounts WHERE name LIKE"%fürst% von '.getsetting('townname','Atrahor').'%"';
			$result=db_query($sql);
			if(db_num_rows($result)==1)
			{
				$row=db_fetch_assoc($result);
				systemmail($row['acctid'],'Edelstein erhalten','`%Werter Fürst! Von allen Dingen,
				`ndie man erzählt, wenn Gläser klingen
				`nkann man Lobeshymnen singen.
				`n
				`nMan erzählte mir von Schlingen,
				`nin denen üble Gauner hingen,
				`ndie sich an meinem Gut vergingen.
				`n
				`nDrum lass\' ich ein paar Steinchen springen,
				`ndie Euch kleine Vöglein bringen.
				`n
				`nRitter Götz von Berlichingen
				`n`n`0Diesem Schreiben liegt ein Edelstein bei, den du sogleich in die Amtskasse tust.');
				savesetting('amtsgems',(getsetting('amtsgems',0)+1));
			}
		}
		addnews($session['user']['name'].'`# hat völlig uneigennützig eine Taube geheilt.');
		$session['user']['specialinc']='';
	}
	elseif ($_GET['op']=='kill')
	{
		output('`5Du kannst das Leiden der Taube nicht mit ansehen und machst kurzen Prozess.
		`nDann packt dich die Neugier, was denn die Taube für eine Botschaft dabei hatte.
		`nDu liest:
		`n`n`%Werter Fürst! Von allen Dingen,
		`ndie man erzählt, wenn Gläser klingen
		`nkann man Lobeshymnen singen.
		`n
		`nMan erzählte mir von Schlingen,
		`nin denen üble Gauner hingen,
		`ndie sich an meinem Gut vergingen.
		`n
		`nDrum lass\' ich ein paar Steinchen springen,
		`ndie Euch kleine Vöglein bringen.
		`n
		`nRitter Götz von Berlichingen
		`n`n`5In diesem Schreiben findest du einen Edelstein, den du natürlich einsteckst.');
		$session['user']['gems']++;
		addnews($session['user']['name'].'`# wurde dabei gesehen wie '.($session['user']['sex']?'sie':'er').' Brieftauben erwürgt hat. Man munkelt nun, '.($session['user']['sex']?'sie':'er').' sei dafür verantwortlich, dass manche Nachrichten nicht ankommen!');
		$session['user']['specialinc']='';
	}
}
?>
