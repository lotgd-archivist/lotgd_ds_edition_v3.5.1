<?php

// Romantisches Dinner bei Cedrik
// Benötigt u. modifiziert : newgiftshop.php
// Modifiziert ggf. bio.php (check auf Tageswechsel)
// Voreingestellt : 1 freies Essen und 5 freie Getränke
//
// Aufschlüsselung ['hvalue'] in ['item'] :
// 0 -> Default
// 1 -> Partner anwesend
// Getränke
// 2 -> Wasser
// 3 -> Wein
// 4 -> Ale
// 5 -> Prosecco
// 6 -> Schnaps
// 7 -> Rhizinusöl
// Speisen
//
// 100 -> Wildschweinbraten
// 110 -> Rehrücken
// 120 -> Hirschgulasch
// 130 -> Nudelpfanne
// 140 -> Drachensteak
// 150 -> Tintenfisch
//
//value1=PartnerID
//value2=eigene ID
// by Maris(Maraxxus@gmx.de)
// 26.2.07: Komplettüberarbeitung by Salator

require_once 'common.php';
page_header('Romantisches Dinner für Zwei');
addcommentary();

/*admin_output('Debug: `n
m1: '.$session['dinner']['m1'].'`n
essen: '.$session['dinner']['essen'].'`n
trinken: '.$session['dinner']['trinken'].'`n
`n',false);
*/

$chatarea = 'Dinner_'.min($session['dinner']['m1'],$session['user']['acctid']).'_'.max($session['dinner']['m1'],$session['user']['acctid']);


output('`c`b`ADas romantische Dinner`c`b`n');

if ($_GET['op']=='')
{
	output('`[In deinem '.($session['user']['sex']?'schönsten Kleid ':'besten Anzug ').'schreitest du durch den Schankraum der Schenke und deutest dem Wirt Cedrik mit einem auffordernden Nicken an, dass du gern in die hinteren, kleinen Räume geführt werden möchtest. Als er stirnrunzelnd zu dir schaut, zieht du das Pergament mit der Reservierung hervor und lässt ihn einen kurzen Blick darauf werfen.
         Nach einem leichten Nicken sieht er dich an `s"Na, das ist ja mal ein Ding! '.($session['user']['sex']?'Die ':'Der ').'Kleine hat ne Verabredung. Ich hoffe doch, dass ihr beide von der Verabredung wisst? Denn wenn du jetzt da hinein gehst, werde ich den Gutschein zerreissen und für mich hattest du dein romantisches Essen; ob du es allein verbringst oder nicht, ist mir egal. Also... bist du dir ganz sicher?"');
	addnav('Ja, auf zum Dinner','dinner.php?op=drin');
	addnav('Öhm... ich warte lieber noch','inn.php');
}
else if ($_GET['op']=='drin')
{
	$row1 = item_get(' tpl_id="dineinl" AND owner='.$session['user']['acctid'],false);
	$row2 = item_get(' tpl_id="dineinl" AND owner='.$row1['value1'],false);
	if (is_array($row2))
	{
		if ($row1['hvalue']==0)
		{
			item_set('id='.$row1['id'],array('hvalue'=>1) );
		}
		output('<span style=color:#CE4040>Cedrik nickt und führt dich in einen kleinen Nebenraum. Hier stehen nur ein Tisch und zwei Stühle. Der Tisch ist schön hergerichtet, stilecht mit einer langen, roten Kerze, deren Licht den Raum dezent erhellt. Wunderschöne Tischdecken hängen bis fast zum Boden und edles Geschirr ist darauf plaziert. Im hinteren Teil des Raumes steht eine gemütliche Couch. Von irgendwoher dringt leise, romantische Musik an dein Ohr.</span>`n`n');
		if(!isset($session['dinner']['name']))
		{
			$sq3 = 'SELECT name FROM accounts WHERE acctid='.$row1['value1'];
			$result3=db_query($sq3);
			$row3 = db_fetch_assoc($result3);
		}
		if ($row2['hvalue']==0)
		{
			output('<span style=color:#CE4040>Frohen Mutes nimmst du auf der Couch Platz und wartest, denn `d'.$row3['name'].'<span style=color:#CE4040> ist noch nicht zu sehen... Du bekommst langsam Zweifel, ob du dich nicht in der Zeit geirrt hast. Du könntest ja noch etwas länger warten und ein Glas Wasser trinken. Wenn du jetzt jedoch gehst, ist dein Gutschein verfallen und dein Date endgültig geplatzt!</span></span>`n');
			addnav('Was nun?');
			addnav('Warten...','dinner.php?op=drin');
			addnav('Gehen','dinner.php?op=gehen');
		}
		else
		{
			if(!isset($session['dinner']))
			{
				if(mb_strpos($session['user']['pqtemp'],'Dinner'))
				{
					$temp=intval($session['user']['pqtemp']);
					$session['dinner']['m1']=$row1['value1'];
					$session['dinner']['name']=$row3['name'];
					$session['dinner']['essen']=floor($temp/10);
					$session['dinner']['trinken']=$temp%10;
				}
				else
				{
					$session['dinner']['m1']=$row1['value1'];
					$session['dinner']['name']=$row3['name'];
					$session['dinner']['essen']=1;
					$session['dinner']['trinken']=5;
					$session['user']['pqtemp']='15Dinner';
				}
			}
			output('<span style=color:#CE4040>Du freust dich riesig, als du `q'.$row3['name'].'<span style=color:#CE4040> erblickst. Ihr lächelt euch und an setzt euch an den Tisch. Ihr bekommt zur Begrüßung einen kleinen Aperitiv serviert. Ein hübsch gekleideter Knecht zündet für euch die Kerze an und nimmt eure Wünsche entgegen. Natürlich wird er auch auf ein Zeichen schnell verschwinden und euch beide allein lassen. Falls ihr noch etwas braucht oder mit dem Essen beginnen wollt, steht ein Glöckchen zum Läuten bereit.`nEr informiert dich, dass du noch `q'.$session['dinner']['essen'].'<span style=color:#CE4040> Essen und `q'.$session['dinner']['trinken'].'<span style=color:#CE4040> Getränke bestellen kannst.</span></span></span></span>`n');
			addnav('Was willst du tun?');
			addnav('Ernste Unterhaltung','dinner.php?op=unterhalten');
			addnav('Zum Tanz bitten','dinner.php?op=tanzen');
			addnav('Flirten','dinner.php?op=flirt');
			addnav('Plaudern','dinner.php?op=reden');

			addnav('Dinner beenden');
			addnav('Gehen','dinner.php?op=gehen');
		}
	}
	else
	{
           	output('<span style=color:#CE4040>Du sitzt allein da, weil dein Partner gegangen ist oder die Einladung vernichtet hat. Dir bleibt nichts anderes übrig als jetzt still und leise zu verschwinden.</span>');
		addnav('Gehen','dinner.php?op=gehen');
	}
}
else if ($_GET['op']=='reden')
{
	$row1 = item_get(' tpl_id="dineinl" AND owner='.$session['user']['acctid'],false);
	$drink=$row1['hvalue'];
	$food=$drink;
	$drink%=10;
	$food-=$drink;

	output('<span style=color:#CE4040>Ihr sitzt euch im Kerzenschein gegenüber und schaut euch in die Augen. In diesem Moment der Stille hört ihr auch die leise Musik recht gut. Der Aperitiv hat euch warm werden lassen und eure Wangen sind leicht gerötet.</span> `n`n');
	viewcommentary($chatarea,'Flüstern:',25,'flüstert',false,true,false,false,true,true,2);

	addnav('Bestellen');
	addnav('Getränk bestellen','dinner.php?op=trank');
	addnav('Essen bestellen','dinner.php?op=speis');
	if($food+$drink>1)
	{
		addnav('Essen & Trinken');
	}
	if ($drink>1)
	{
		switch ($drink)
		{
		case 2 :
			$drink2='Wasser';
			$nbr=1;
			break;
		case 3 :
			$drink2='Wein';
			$nbr=2;
			break;
		case 4 :
			$drink2='Ale';
			$nbr=3;
			break;
		case 5 :
			$drink2='Prosecco';
			$nbr=4;
			break;
		case 6 :
			$drink2='Schnaps';
			$nbr=5;
			break;
		case 7 :
			$drink2='Rhizinusoel';
			$nbr=6;
			break;
		}
		addnav('t?'.$drink2.' trinken','dinner.php?op=austrinken&what='.$drink2.'&nbr='.$nbr);
		addnav('w?'.$drink2.' wegschütten','dinner.php?op=austrinken&subop=weg&what='.$drink2.'&nbr='.$nbr);
	}
	if ($food>1)
	{
		switch ($food)
		{
		case 100 :
			$food2='Wildschweinbraten';
			$nbr=100;
			break;
		case 110 :
			$food2='Rehruecken';//geht nicht mit ü
			$nbr=110;
			break;
		case 120 :
			$food2='Hirschgulasch';
			$nbr=120;
			break;
		case 130 :
			$food2='Nudelpfanne';
			$nbr=130;
			break;
		case 140 :
			$food2='Drachensteak';
			$nbr=140;
			break;
		case 150 :
			$food2='Tintenfisch';
			$nbr=150;
			break;
		}
		addnav('s?'.$food2.' essen','dinner.php?op=aufessen&what='.$food2.'&nbr='.$nbr);
		addnav('w?'.$food2.' wegwerfen','dinner.php?op=aufessen&subop=weg&what='.$food2.'&nbr='.$nbr);
	}
	addnav('Sonstiges');
	addnav('A?Etwas Anderes tun','dinner.php?op=drin');
	addnav('');
	addnav('Nav aktualisieren','dinner.php?op=reden');
}
else if ($_GET['op']=='trank')
{
	output("<span style=color:#CE4040>Du läutest das Glöckchen und der Knecht eilt zu dir.</span>`n");
	if ($session['dinner']['trinken']>0)
	{
		$row = item_get(' tpl_id="dineinl" AND owner='.$session['dinner']['m1'],false);
		if ($row['hvalue']%10 <=1)
		{
			output('<span style=color:#CE4040>Er sagt dir, dass du noch `q'.$session['dinner']['trinken'].'<span style=color:#CE4040> Getränke an diesem Abend bestellen kannst und zeige dir die Karte.`nWas willst du '.$session['dinner']['name'].'<span style=color:#CE4040> denn Schönes gönnen?</span></span></span>`n');
			addnav('Getränkekarte');
			addnav('Wasser','dinner.php?op=trank2&what=1');
			addnav('Wein','dinner.php?op=trank2&what=2');
			addnav('Ale','dinner.php?op=trank2&what=3');
			addnav('Prosecco','dinner.php?op=trank2&what=4');
			addnav('Schnaps','dinner.php?op=trank2&what=5');
			addnav('Rhizinusoel','dinner.php?op=trank2&what=6');
			addnav('');
		}
		else
		{
			output('<span style=color:#CE4040>Er teilt dir mit, dass '.$session['dinner']['name'].'<span style=color:#CE4040> noch ein Getränk vor sich stehen hat und doch erst austrinken sollte, bevor du ein Neues bestellst.</span></span>');
		}
	}
	else
	{
		output('<span style=color:#CE4040>Er teilt dir Bedauern mit, dass du keine Getränke mehr an diesem Abend bestellen kannst.</span>`n');
	}
	addnav('Weiterplaudern','dinner.php?op=reden');
}
else if ($_GET['op']=='trank2')
{
	$what=$_GET['what'];
	$session['dinner']['trinken']--;
	$session['user']['pqtemp']=$session['dinner']['essen'].$session['dinner']['trinken'].'Dinner';

	switch ($what)
	{
	case 1 :
		$drink='ein Glas Wasser. Wie langweilig...';
		break;
	case 2 :
		$drink='eine kleine Karaffe Wein. Schön rot und kräftig!';
		break;
	case 3 :
		$drink='einen Humpen Ale. Na dann Prost!`0';
		break;
	case 4 :
		$drink='ein Gläschen Prosecco. Ein prickelndes alkoholisches Getränk aus fernen Landen.';
		break;
	case 5 :
		$drink='einen Fingerhut voll Schnaps. Allein der Geruch macht schon betrunken.';
		break;
	case 6 :
		$drink='ein Schälchen Rhizinusöl. Absolut widerlich, aber gut für den Magen.';
		break;
	}
	output('<span style=color:#CE4040>Der Knecht nickt und erfüllt dir deinen Wunsch.</span>`n');

	$row = item_get(' tpl_id="dineinl" AND owner='.$session['dinner']['m1'],false);
	if (is_array($row))
	{
		$hvalue=$row['hvalue']+$what;
		item_set('id='.$row['id'],array('hvalue'=>$hvalue) );
		$sql = 'INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"'.$chatarea.'",'.$session['user']['acctid'].',": `tflüstert dem Knecht etwas ins Ohr und dieser serviert '.db_real_escape_string($session['dinner']['name']).' `O'.$drink.' ")';
		db_query($sql);
		addnav('Weiterplaudern','dinner.php?op=reden');
	}
	else
	{
		output('<span style=color:#CE4040>Du sitzt allein da, weil dein Partner gegangen ist oder die Einladung vernichtet hat. Dir bleibt nichts anderes übrig als jetzt still und leise zu verschwinden.</span>');
		addnav('Gehen','dinner.php?op=gehen');
	}
}
else if ($_GET['op']=='speis')
{
	output('<span style=color:#CE4040>Du läutest das Glöckchen und der Knecht eilt zu dir.</span>`n');
	if ($session['dinner']['essen']>0)
	{

		$row = item_get(' tpl_id="dineinl" AND owner='.$session['dinner']['m1'],false);

		if ($row['hvalue']<=100)
		{
			output('<span style=color:#CE4040>Er sagt dir, dass du noch `q'.$session['dinner']['essen'].'<span style=color:#CE4040> Hauptgerichte an diesem Abend bestellen kannst und zeigt dir die Karte.`nWas willst du '.$session['dinner']['name'].'<span style=color:#CE4040> denn Leckeres bringen lassen?</span></span></span>`n');
			addnav('Speisekarte');
			addnav('Wildschweinbraten','dinner.php?op=speis2&what=100');
			addnav('Rehruecken','dinner.php?op=speis2&what=110');
			addnav('Hirschgulasch','dinner.php?op=speis2&what=120');
			addnav('Nudelpfanne','dinner.php?op=speis2&what=130');
			addnav('Drachensteak','dinner.php?op=speis2&what=140');
			addnav('Tintenfisch','dinner.php?op=speis2&what=150');
			addnav('');
		}
		else
		{
			output('<span style=color:#CE4040>Er teilt dir mit, dass '.$session['dinner']['name'].'<span style=color:#CE4040> bereits etwas zu Essen bekommen hat und der Teller noch auf dem Tisch steht.</span></span>');
		}
	}
	else
	{
		output('<span style=color:#CE4040>Er teilt dir Bedauern mit, dass du kein Essen mehr an diesem Abend bestellen kannst.</span>`n');
	}
	addnav('Weiterplaudern','dinner.php?op=reden');
}
else if ($_GET['op']=='speis2')
{
	$what=$_GET['what'];
	$session['dinner']['essen']--;
	$session['user']['pqtemp']=$session['dinner']['essen'].$session['dinner']['trinken'].'Dinner';

	switch ($what)
	{
	case 100 :
		$drink='knusprigen Wildschweinbraten. Mjamm!';
		break;
	case 110 :
		$drink='eine Portion zarten Rehrücken. Das arme Reh...';
		break;
	case 120 :
		$drink='ein deftiges Hirschgulach. Es riecht köstlich!';
		break;
	case 130 :
		$drink='eine leckere hausgemachte Nudelpfanne mit extra viel Käse.';
		break;
	case 140 :
		$drink='ein unglaublich zähes Drachensteak. Wohl nur für Trolle genießbar!';
		break;
	case 150 :
		$drink='einen widerlich glibberigen Tintenfisch mit extra langen Fangarmen.';
		break;
	}
	output('<span style=color:#CE4040>Der Knecht nickt und erfüllt dir deinen Wunsch.</span>`n');
	$row = item_get(' tpl_id="dineinl" AND owner='.$session['dinner']['m1'],false);

	$hvalue=$row['hvalue']+$what;
	if (is_array($row))
	{
		item_set('id='.$row['id'],array('hvalue'=>$hvalue) );
		$sql = 'INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"'.$chatarea.'",'.$session['user']['acctid'].',": `tflüstert dem Knecht etwas ins Ohr und dieser serviert '.db_real_escape_string($session['dinner']['name']).' `O'.$drink.' ")';
		db_query($sql);
		addnav('Weiterplaudern','dinner.php?op=reden');
	}
	else
	{
		output('<span style=color:#CE4040>Du sitzt allein da, weil dein Partner gegangen ist oder die Einladung vernichtet hat. Dir bleibt nichts anderes übrig als jetzt still und leise zu verschwinden.</span>');
		addnav('Gehen','dinner.php?op=gehen');
	}
}
else if ($_GET['op']=='austrinken')
{
	$drink=$_GET['nbr'];
	$drink2=$_GET['what'];

	$row = item_get(' tpl_id="dineinl" AND owner='.$session['user']['acctid'],false);
	$hvalue=$row['hvalue']-$drink;
	item_set('id='.$row['id'],array('hvalue'=>$hvalue) );

	if ($_GET['subop']=='weg')
	{
		output('<span style=color:#CE4040>Heimlich schüttest du '.$drink2.'<span style=color:#CE4040> in eine Topfpflanze in der Nähe.</span></span>');
	}
	else
	{
		output('<span style=color:#CE4040>Du nimmst '.$drink2.'<span style=color:#CE4040> und trinkst es in einem Zug aus.</span></span>');
	}
	$sql = 'INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"'.$chatarea.'",'.$session['user']['acctid'].',": `tist mit dem Getränk fertig.")';
	db_query($sql);
	addnav('Weiterplaudern','dinner.php?op=reden');
}
else if ($_GET['op']=='aufessen')
{
	$food=$_GET['nbr'];
	$food2=$_GET['what'];

	$row = item_get(' tpl_id="dineinl" AND owner='.$session['user']['acctid'],false);
	$hvalue=$row['hvalue']-$food;
	item_set('id='.$row['id'],array('hvalue'=>$hvalue) );

	if ($_GET['subop']=='weg')
	{
		output('<span style=color:#CE4040>Heimlich schaufelst du '.$food2.'<span style=color:#CE4040> unter den Tisch.</span></span>');
	}
	else
	{
		output('<span style=color:#CE4040>Du stürzt dich auf deine Portion '.$food2.'<span style=color:#CE4040> und isst gierig auf.</span></span>');
	}

	$sql = 'INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),"'.$chatarea.'",'.$session['user']['acctid'].',": `tist mit dem Essen fertig.")';
	db_query($sql);
	addnav('Weiterplaudern','dinner.php?op=reden');
}
else if ($_GET['op']=='flirt')
{
	$flirt_inc_style='dinner';
	$_GET['id']=$session['dinner']['m1'];
	if ($session['user']['seenlover']==1)
	{
		output('<span style=color:#CE4040>So gern du jetzt auch flirten möchtest... du hast heute schon und kannst dich irgendwie nicht so recht noch einmal dazu durchringen.</span>`n');
		addnav('Etwas Anderes tun','dinner.php?op=drin');
	}
	else
	{
		output('<span style=color:#CE4040>Du geniesst die Zeit mit '.$session['dinner']['name'].'<span style=color:#CE4040> wirklich und ganz allmählich beginnst du deutliche Zeichen zu senden. Sofort entsteht ein heftiger Flirt und ein angeregtes Gespräch. Ihr lacht und fühlt euch wirklich zueinander hingezogen!`n
		Ihr schaut euch an und begebt euch für eine Weile zusammen auf die Couch.</span></span>`n');

		$bool_flirtaffianced=true; //verlobt fremdflirten zwecks Auflösung
		$bool_noturnsallowed=true; //Flirt ohne WK erlaubt
		$bool_flirtcharmdiff=true; //Charmeunterschied nicht prüfen
		$flirtmail_subject='`%Flirt!`0';
		$flirtmail_body='`&'.$session['user']['name'].'`6 hat gerade damit begonnen dich heftig anzuflirten';
		$flirtlocation=' beim Dinner ';
		$str_output_noturns .= 'Du versuchst ohne Waldkämpfe zu flirten. Eigentlich sollte das hier erlaubt sein. Beschwer dich beim Programmierer.';

		include ('flirt.inc.php');
		output($str_output);

		addnav('Etwas Anderes tun','dinner.php?op=drin');
	}
}
else if ($_GET['op']=='tanzen')
{
	output('<span style=color:#CE4040>Du bittest dein Gegenüber zum Tanz und ihr beide begebt euch in die Mitte des kleinen Raumes, wo ihr eng umschlungen zu der leisen Musik zu tanzen beginnt.</span>`n');

	switch (e_rand(1,10))
	{
	case 2:
		output('<span style=color:#CE4040>Da dein Blick auf die Augen deines Partners gerichtet ist und du vor dich hin träumst, machst du einen Fehler und dein Partner tritt dir auf den Fuß! AUTSCH! Du verlierst ein paar Lebenspunkte!</span>`n');
		$session['user']['hitpoints']-=5;
		if ($session['user']['hitpoints']<1)
		{
			$session['user']['hitpoints']=1;
		}
		break;
	case 4:
		output('<span style=color:#CE4040>Du spürst dass die Hand deines Partners nicht da ist, wo sie eigentlich sein sollte!</span>`n');
		break;
	case 6:
		output('<span style=color:#CE4040>Du geniesst die Nähe und die Wärme deines Partners und bekommst Lust auf - mehr!</span>`n');
		break;
	case 8:
		output('<span style=color:#CE4040>Ungeschickt rutscht du aus und machst einen weiten Ausfallschritt um dein Gleichgewicht zu halten! Du hörst ein leises RATSCH... wie peinlich!`nDu verlierst einen Charmepunkt!</span>`n`n');
		$session['user']['charm']--;
		break;
	case 10:
		output('<span style=color:#CE4040>Du tanzt einfach göttlich und schaffst es deinem Partner wirklich zu imponieren!`nDu erhältst einen Charmepunkt`n`n</span>');
		$session['user']['charm']++;
		break;
		default:
		output('<span style=color:#CE4040>Ihr tanzt eine Weile recht eng und lasst euch schließlich erschöpft in die Couch fallen, um dort ein wenig auszuruhen, bevor ihr euch wieder an den Tisch setzt.</span>');
	}
	addnav('Etwas Anderes tun','dinner.php?op=drin');
}
else if ($_GET['op']=='unterhalten')
{
	output('<span style=color:#CE4040>Du überlegst kurz und leitest die Unterhaltung geschickt auf ein ernstes Thema um. Der Erfahrungsaustausch mit deinem Partner wird für euch beide von Vorteil sein.</span>`n`n');
	if ($session['user']['turns'] < 1)
	{
		output('`n<span style=color:#CE4040>Nur leider bist du so müde, dass du dich nicht auf ein ernstes Gespräch konzentrieren kannst!</span>');
	}
	else
	{
		output('<span style=color:#CE4040>Wieviele Runden willst du das Gespräch führen?</span>`n
		<form action="dinner.php?op=train2" method="POST"><input name="trai" id="trai"><input type="submit" class="button" value="Unterhalten"></form>');
        JS::Focus("trai");
		addnav('','dinner.php?op=train2');
	}
	addnav('Etwas Anderes tun','dinner.php?op=drin');
}
else if ($_GET['op']=='train2')
{
	$trai = abs((int)$_GET['trai'] + (int)$_POST['trai']);
	if ($session['user']['turns'] <= $trai)
	{
		$trai = $session['user']['turns'];
	}

	$session['user']['turns']-=$trai;
	$exp = $session['user']['level']*e_rand(7,15)+e_rand(0,9);
	$totalexp = $exp*$trai;
	$session['user']['experience']+=$totalexp;
	output('<span style=color:#CE4040>Ihr redet für '.$trai.' Runden und du bekommst '.$totalexp.' Erfahrungspunkte!</span>`n');
	addnav('Etwas Anderes tun','dinner.php?op=drin');
}
else if ($_GET['op']=='gehen')
{
	output('<span style=color:#CE4040>Cedrik zerreißt grinsend deinen Gutschein und wünscht dir noch einen schönen Tag.</span>');

	item_delete(' tpl_id="dineinl" AND owner='.$session['user']['acctid']);
	unset($session['dinner']);
	$session['user']['pqtemp']='';
	addnav('Zur Kneipe','inn.php');
}


page_footer();
?>