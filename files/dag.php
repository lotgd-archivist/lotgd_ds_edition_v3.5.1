<?php

// 18062004

require_once 'common.php';
checkday();

page_header('Dag Durnicks Tisch');
output('`c`b`]Dag Durnicks Tisch`0`b`c`n');

if ($_GET['op']=='list')
{
	$sql = "SELECT name,alive,sex,level,laston,loggedin,bounty,location,activated
		FROM accounts
		WHERE bounty>0
		ORDER BY bounty DESC";
	$result = db_query($sql);
	output('`[Dag fischt ein kleines, ledergebundenes Buch unter seinem Mantel hervor, blättert es zu einer bestimmten Seite durch und hält es dir zum Lesen hin.
	`n`n`0`c`b`]Die Kopfgeldliste`0`b`c
	`n<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center">
	<tr class="trhead">
	<th>Kopfgeld</th>
	<th>Level</th>
	<th>Name</th>
	<th>Ort</th>
	<th><img src="./images/female.gif">/<img src="./images/male.gif"></th>
	<th>Zuletzt online</th>
	</tr>');
	while($row=db_fetch_assoc($result))
	{
		$i++;
		$str_out.='<tr class="'.($i%2?'trdark':'trlight').'">
		<td>`^'.$row['bounty'].'`0</td>
		<td>`^'.$row['level'].'`0</td>
		<td>`&'.$row['name'].'`0</td>
		<td>';
		$loggedin=user_get_online(0,$row);
		switch($row['location'])
		{
			case USER_LOC_FIELDS:
				$str_out.=$loggedin?'`#Online`0':'`3Die Felder`0';
				break;
			case USER_LOC_INN:
				$str_out.='`3Zimmer in Kneipe`0';
				break;
			case USER_LOC_HOUSE:
				$str_out.='`3Im Haus`0';
				break;
			case USER_LOC_PRISON:
				$str_out.='`3Im Kerker`0';
				break;
			case USER_LOC_VACATION:
				$str_out.='`3In Sibirien`0';
				break;
			default:
				$str_out.='`3Weiß der Geier...';
		}
		$str_out.='</td>
		<td align="center"><img src="./images/'.($row['sex']?'fe':'').'male.gif"></td>
		<td>';
		$laston=round((strtotime(date("r"))-strtotime($row['laston'])) / 86400,0).' Tage';
		if (mb_substr($laston,0,2)=="1 ") $laston='1 Tag';
		if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston='Heute';
		if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston='Gestern';
		if ($loggedin) $laston='Jetzt';
		$str_out.=$laston.'</td></tr>';
	}
	output($str_out.'</table>');
}

else if ($_GET['op']=='addbounty')
{
	if ($session['user']['bounties'] >= getsetting('maxbounties',5))
	{
		output('`[Dag durchbohrt dich fast mit seinem Blick. `s"Hältst du mich für nen Meuchelmörder oder was? Du hast heut schon genuch Kopfgelder ausgesetzt. Jetz hau ab, bevor ich n Kopfgeld auf deinen Kopf aussetz, weil du mir auf die Nerven gehst."`n`n');
	}
	else
	{
		$fee = getsetting('bountyfee',10);
		if ($fee < 0 || $fee > 100)
		{
			$fee = 10;
			savesetting('bountyfee',$fee);
		}
		$min = getsetting('bountymin',50);
		$max = getsetting('bountymax',400);
		output('`[Dag Durnick blickt zu dir auf und rückt seine Pfeife mit den Zähnen zurecht.
		`n`s\'So, wen willst\'n tot sehen?
		Du sollst aber wissen, dass wir keine Kinder killn, deswegen muss dein Opfer mindestens Level ' . getsetting('bountylevel',3) . ' sein und der Preis darf nicht zu hoch sein.
		Außerdem dürfen die Opfer nicht zu oft getroffen werdn.
		Also wer in meinem Buch nicht gelistet is, kann nicht zum Abschuss freigegeben werdn!
		Wir betreiben hier kein Schlachthaus, sondern \'n ... Unternehmen.
		Ich verlang ' . getsetting('bountyfee',10) . '% Bearbeitungsgebühren für jeden Namen, den ich auf die Liste setzn soll.\'
		`n
		`n`0<form action="dag.php?op=finalize" method="POST">
		`[Zielperson: <input name="contractname">
		`n`[Betrag aussetzen:
		<input name="amount" id="amount" size="5">
		`n`n<input type="submit" class="button" value="Vertrag abschlie&szlig;en">
		`0</form>');
		addnav('','dag.php?op=finalize');
		if ($session["user"]["pvpflag"]==PVP_IMMU)
		{
			output('`[`n`nDag schaut dich fordernd an. `s"Petersen hat mir erzählt, dass er dich vor Killern schützt - jetzt willst du selber jemanden tot sehen? Du würdest seinen Schutz verlieren, wenn ich jemanden für dich auf die Liste hier setze, ist dir das klar?"`n`n');
		}
	}
}

elseif ($_GET['op']=='finalize')
{
	if ($_GET['subfinal']==1)
	{
		$sql = 'SELECT acctid,name,login,level,locked,age,dragonkills,pk,experience,bounty,pvpflag,lastip,emailaddress,reputation,uniqueid
			FROM accounts
			WHERE acctid="'.$_POST['contractname'].'"
			AND locked=0';
	}
	else
	{
		$contractname = rawurldecode($_POST['contractname']);
		$name = str_create_search_string($contractname);
		$sql = 'SELECT acctid,name,login,level,locked,age,dragonkills,pk,experience,bounty,pvpflag,lastip,emailaddress,reputation,uniqueid
			FROM accounts
			WHERE name LIKE "'.$name.'"
			AND locked=0
			ORDER BY login="'.db_real_escape_string($_POST['contractname']).'" DESC, level DESC
			LIMIT 100';
	}
	$result = db_query($sql);
	if (db_num_rows($result) == 0)
	{
		output('`[Dag Durnick sagt höhnisch lachend: `s"Es gibt nicht einen den ich mit so einem Namen kenne. Vielleicht kommst\' wieder, wenn du \'n echtes Opfer hast."');
	}
	elseif(db_num_rows($result) > 1)
	{
		if(db_num_rows($result) > 99)
		{
			output('`[Dag Durnick kratzt sich verwirrt am Kopf. `s"Du beschreibst hier fast die Hälfte der Stadt, du Narr. Warum gibst du mir jetzt nicht mal ne genauere Beschreibung?"`n');
		}
		output('`[Dag Durnick durchsucht seine Liste für einen Moment. `s"Da sind ein paar, die du meinen könntest. Wer genau soll\'s denn sein?"
		`n`0<form action="dag.php?op=finalize&amp;subfinal=1" method="POST">
		`[Zielperson: `0<select name="contractname">');
		while($row=db_fetch_assoc($result))
		{
			output('
			<option value="'.$row['acctid'].'">'.strip_appoencode($row['name']).'</option>');
		}
		output('</select>
		`n`n`[Betrag aussetzen: <input name="amount" id="amount" size="5" value="'.$_POST['amount'].'">
		`n`n<input type="submit" class="button" value="Vertrag abschlie&szlig;en">
		`0</form>');
		addnav('','dag.php?op=finalize&subfinal=1');
	}
	else // Now, we have just the one, so check it.
	{
		$row  = db_fetch_assoc($result);
		if ($row['locked'])
		{
			output('`[Dag Durnick sagt höhnisch lachend: `s"Es gibt nicht einen, den ich mit so einem Namen kenne. Vielleicht kommst\' wieder, wenn du \'n echtes Opfer hast."');
		}
		elseif ($row['login'] == $session['user']['login'])
		{
			output('`[Dag Durnick schlägt sich brüllend lachend auf die Schenkel: `s"Du willst n Kopfgeld auf dich selbst aussetzen? Ich helf doch keinem Selbstmörder!"');
		}
		elseif ($row['level'] < getsetting("bountylevel",3) ||
				($row['age'] < getsetting("pvpimmunity",5) &&
				$row['dragonkills'] == 0 && $row['pk'] == 0 &&
				$row['experience'] < getsetting("pvpminexp",1500)))
		{
			output('`[Dag Durnick starrt dich ärgerlich an: `s"Hab ich dir nicht gesagt, dass ich kein Meuchler bin? Das ist kein Opfer, das ein Kopfgeld wert wäre. Jetzt geh mir aus den Augen!"');
		}
		elseif ($row['pvpflag']==PVP_IMMU)
		{
			output('`s"Diese Person steht unter dem persönlichn Schutz von J. C. Petersen! Glaubst du echt, ich will\'s mir mit dem verscherzn? Hau bloß ab!"');
 		}
		elseif (ac_check($row))
		{
			output('`s`bKeine Chance!!`b Du darfst kein Kopfgeld auf deinen eigenen Charakter aussetzen!');
		}
		else // All good!
		{
			$amt = abs((int)$_POST['amount']);
			$min = getsetting('bountymin', 50) * $row['level'];
			$max = getsetting('bountymax', 400) * $row['level'];
			$fee = getsetting('bountyfee',10);
			if ($amt < $min)
			{
				output('`[Dag Durnick blickt finster: `s"Glaubst im Ernst, ich arbeite für so nen Hungerlohn? Denk ma drüber nach und komm wieder, wenn du bereit bist, hartes Bares zu bezahlen. Für dein Opfer brauchste mindestens ' . $min . ' Gold, damit\'s meine Zeit wert is."');
			}
			elseif ($session['user']['gold'] <round($amt*1.1,0))
			{
				output('`[Dag Durnick schaut dich finster an: `s"Du hast nicht genug Gold für diesen Vertrag. Wenn du meine Zeit so vergeudest, sollte ich stattdessen vielleicht n Kopfgeld auf DICH aussetzen!"');
			}
			elseif ($amt + $row['bounty'] > $max)
			{
				output('`[Dag schaut auf den Berg Münzen und lässt ihn unberührt liegen. `s"Ich werde diesen Vertrag ablehnen. Das is viel mehr, als '.$row['name'].' Wert is und das weißt du. Ich bin kein verdammter Auftragskiller. N Kopfgeld von '.$row['bounty'].' is schon auf diesen Kopf ausgesetzt. Ich wär bereit, es auf '.$max.' zu erhöhen, nach meinen '.$fee.'% Bearbeitungsgebühren natürlich."`n`n');
			}
			else
			{
				output('`[Du schiebst die Münzen zu Dag Durnick, der sie flink einstreicht. `s"Ich werd mir nur meine '.$fee.'% Gebühr einbehalten. Ich werd die Nachricht verbreiten, dass sich jemand um '.$row['name'].'`s kümmern soll. Hab Geduld und hab ein Ohr für die Nachrichten der Marktschreier.');
				$session['user']['bounties']++;
				$cost = round($amt*(1+($fee/100)),0);
				if ($row['reputation']>$session['user']['reputation'])
				{
					$session['user']['reputation']--;
					output(' `sDu sollst aber wissen, dass '.$row['name'].'`s mehr Ehre besitzt als du! '.$row['name'].'`s würde dich lieber selbst erwürgen, als feige ein Kopfgeld auszusetzen.');
				}
				else if ($row['reputation']<-25)
				{
					$session['user']['reputation']+=2;
					$cost=round($cost/2);
					output(' `sOder ... och weißt du was? Dieser schmierige Feigling '.$row['name'].'`s geht mir schon lang auf den Keks. Ich setz ihn für dich für den halben Preis auf die Liste!');
				}
				output('"`n`n');
				$session['user']['gold']-=$cost;
				if ($session['user']['pvpflag']==PVP_IMMU)
				{
					$session['user']['pvpflag']="1986-10-06 00:42:00";
					output('`n`4`bDeine Immunität ist hiermit verfallen!`b`n');
				}
				debuglog("setzte $amt Gold Kopfgeld aus auf: ", $row['acctid']);
				user_update(
					array
					(
						'bounty'=>array('sql'=>true,'value'=>"bounty+$amt")
					),
					$row['acctid']
				);
			}
		}
	}
}

else
{
	output('`(D`)u `7s`_c`[hlenderst rüber zu Dag Durnick, der es nichtmal für nötig hält, zu dir aufzuschauen. Er trägt einen langen, dunklen Mantel und ist damit beschäftigt, sein Buch zu studieren und die Auftrage säuberlich aufzuschreiben. Er nimmt einen langen Zug aus seiner Pf`_e`7i`)f`(e.
	`n`s"Du willst wohl wissn, ob n Preis auf deinen Kopf ausgesetzt is, richtig?"
	`n`n');
	if ($session['user']['bounty']>0)
	{
		output('`s"Nun, es sieht so aus als ob da `^'.$session['user']['bounty'].' Gold`s auf deinen Kopf ausgesetzt is. Du solltest gut auf dich aufpassen."');
	}
	else
	{
		output('`s"Da is kein Kopfgeld auf dich ausgesetzt. Ich schlag vor, du tust alles, damit das auch so bleibt."');
	}
	if ($session['user']['reputation']<-25)
	{
		output('`n`s"Und das meine ich ernst! Mach so weiter, und ich jag dich höchstpersönlich!"');
	}
	elseif ($session['user']['reputation']<-10)
	{
		output('`n`[Verächtlich schnaubend wendet er sich von dir ab.');
	}
	else
	{
		output('`n`s"Wäre echt \'ne Schande, wenn ich so ehrenwerte Leute auf meine Liste setzen müsste."');
	}
	addnav('Kopfgeldliste','dag.php?op=list');
	addnav('Kopfgeld aussetzen','dag.php?op=addbounty');
}

if ($_GET['op'] != '')
{
	addnav('Rede mit Dag Durnick', 'dag.php');
}
addnav('Zurück zur Kneipe','inn.php');

page_footer();
?>