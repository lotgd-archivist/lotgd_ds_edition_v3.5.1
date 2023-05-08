<?php
// Richter-Addon : Ergänzung zu Dorfamt u. Stadtwache
// Benötigt : [profession] (shortint, unsigned) in [accounts]
//             Tabellen [crimes],[cases]

// by Maris (Maraxxus@gmx.de)

require_once "common.php";
require_once(LIB_PATH.'profession.lib.php');

page_header("Der Gerichtshof");

if (!isset($session))
{
	exit();
}

switch ($_GET['op'])
{
	case 'newsdelete': //Straftaten löschen
	{
		$sql = "DELETE FROM crimes WHERE newsid='$_GET[newsid]'";
		db_query($sql);
		$return = $_GET['return'];
		$return = utf8_preg_replace("'[?&]c=[[:digit:]-]*'","",$return);
		$return = mb_substr($return,mb_strrpos($return,"/")+1);
		redirect($return);
		break;
	}
	case 'bewerben':
	{
		output("`tDu holst tief Luft und öffnest langsam die schwere Eichentüre. Ein betagter Mann mit dichtem Backenbart sitzt hinter einem Tisch aus dunklem Holz und ist gerade in seine Arbeit vertieft. Als die Geräusche deiner Schritte auf dem Holzboden zu ihm dringen blickt er auf. \"`sWen haben wir denn hier?`t\" fragt er mit einem sadistischem Grinsen. Nachdem du dich vorgestellt und ihm dein Anliegen mitgeteilt hast kneift er die Augen zusammen.`n`n");
		$maxamount = getsetting("numberofjudges",10);
		$reqdk = getsetting("judgereq",50);

		$sql = "SELECT profession FROM accounts WHERE profession=".PROF_JUDGE_HEAD." OR profession=".PROF_JUDGE;
		$result = db_query($sql);
		if ((db_num_rows($result)) < $maxamount)
		{
			if (($session['user']['profession']==PROF_JUDGE_ENT) || ($session['user']['profession']==24))
			{
				output("\"`s ".($session['user']['name'])."! So sehr ich Euren Wunsch nachempfinden kann wieder richten zu dürfen muss ich Euch jedoch enttäuschen. Ihr hattet Eure Chance! Und nun verlasst mein Büro!`t\"");
			}
			else
			{
				output("\"`s ".($session['user']['name'])."!`s Ich hoffe Ihr wisst worauf Ihr Euch hier einlasst? Das Amt des Richters ist hart und entbehrungsreich. Und an Euch werden besondere Forderungen gestellt : Ihr müsst sowohl ruhmreich wie auch von höchstem Ansehen sein und in Eurem Verhalten ein Vorbild!`t\"`n`n");

				if (($session['user']['dragonkills']) >= $reqdk)
				{
					if ($session['user']['reputation']>=50)
					{
						output("\"`sIch sehe, ich sehe... Ihr seid sowohl ruhmreich, wie auch von allerhöchstem Ansehen! Das ist gut, sehr gut. Meinetwegen könnt Ihr sofort anfangen. Doch wisset, dass Ihr als Richter nicht nur Rechte, sondern auch Pflichten habt. Es ist Euch strengstens untersagt mit zwielichtigen Gesellen Kontakte zu knüpfen, auch nicht zur Täuschung! Jedes Eurer Urteile muss gerecht und nachvollziehbar sein! Geschenke anzunehmen ist Euch strengstens untersagt!`n Dem obersten Richter habt Ihr Folge zu leisten! Sollte man Euch bei irgendeinem Verstoß oder irgendeiner Unehrenhaftigkeit erwischen, seid Ihr für lange Zeit Richter gewesen! Sind wir uns da einige?`nAlso, wollt Ihr noch immer ?`t\"");
						addnav("Ja, Richter werden","court.php?op=bewerben_ok");
					}
					else
					{
						output("\"`sRuhmreich seid mehr als es von Nöten wäre, doch fürchte ich, dass Euch die Leute nicht trauen würden, wenn Ihr plötzlich in Richterrobe daher kämet. Tut mal etwas für Euer Ansehen und versucht es dann noch einmal!`t\"");
					}
				}
				else
				{
					output("\"`sIhr seid zwar ruhmreich, doch wie es mir scheint nicht ruhmreich genug. Ihr solltet noch mehr Ruhm durch Heldentaten erlangen und es dann noch einmal versuchen!`t\"");
				}
			}
			// Kein entlassener
		}
		// Noch nicht zu viele
		else
		{
			output("\"`sEs tut mir sehr leid, aber die Stadt hat zur Zeit genügend Richter. Versucht es doch später noch einmal!`t\"");
		}
		addnav("Zurück","dorfamt.php");
		break;
	}
	case 'bewerben_ok':
	{
		output("`tDu überreichst dem alten Mann dein Bewerbungsschreiben. Dieser verstaut es unter einem hohen Stapel Pergamenten und meint: \"Wir werden auf dich zurückkommen!\"");
		$session['user']['profession']=PROF_JUDGE_NEW;
		$sql = "SELECT acctid FROM accounts WHERE profession=".PROF_JUDGE_HEAD." ORDER BY loggedin DESC, RAND() LIMIT 1";
		$res = db_query($sql);
		if (db_num_rows($res))
		{
			$w = db_fetch_assoc($res);
			systemmail($w['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& hat sich als Richter beworben. Du solltest die Bewerbung überprüfen und eine Entscheidung treffen.");
		}
		addnav("Zurück","dorfamt.php");
		break;
	}
	case 'bewerben_abbr':
	{
		$session['user']['profession'] = 0;
		output("Du ziehst deine Bewerbung zurück.");
		addnav("Zurück","dorfamt.php");
		break;
	}
	case 'aufn':
	{
		$pid = (int)$_GET['id'];
		$sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_JUDGE_HEAD." OR profession=".PROF_JUDGE.")";
		$res = db_query($sql);
		$p = db_fetch_assoc($res);
		if ($p['anzahl'] >= getsetting("numberofjudges",10))
		{
			output("Es gibt bereits ".$p['anzahl']." Richter! Mehr sind zur Zeit nicht möglich.");
			addnav("Zurück","court.php?op=listj");
		}
		else
		{
			user_update(array('profession'=>PROF_JUDGE),$pid);

			db_query($sql);
			$sql = "SELECT name FROM accounts WHERE acctid=".$pid;
			$res = db_query($sql);
			$p = db_fetch_assoc($res);
			systemmail($pid,"Du wurdest aufgenommen!",$session['user']['name']."`& hat deine Bewerbung zum Richter angenommen. Damit bist du vom heutigen Tage an offiziell Hüter für Recht und Ordnung!");
			$sql = "INSERT INTO news SET newstext = '".db_real_escape_string($p['name'])." `&wurde heute offiziell das ehrenvolle Amt eines Richters zugewiesen!',newsdate=NOW(),accountid=".$pid;
			db_query($sql);
			addhistory('`2Aufnahme ins Richteramt',1,$pid);
			addnav("Willkommen!","court.php?op=listj");
			output("Der neue Richter ist jetzt aufgenommen!");
		}
	break;
	}
	case 'abl':
	{
		$pid = (int)$_GET['id'];
		
		if($_POST['message']!='')
		{
			user_update(array('profession'=>0),$pid);

			systemmail($pid,"Deine Bewerbung wurde abgelehnt!",$_POST['message']);
			output('Eine weitere Bewerbung findet ihren Platz in Ablage P.`n`n');
		}
		else
		{
			output('<form action="court.php?op=abl&id='.$pid.'" method="post">
			Dem Bewerber wird dieser Bescheid zugesandt:
			`n`n<textarea name="message" class="input" cols=70 rows=4>'.$profs[$session['user']['profession']][$session['user']['sex']].' '.$session['user']['login'].' hat deine Bewerbung als Richter abgelehnt.</textarea>
			`n<input type="submit" id="submit" class="button" value="Mitteilung senden">
			</form>`n');
			addnav('','court.php?op=abl&id='.$pid);
		}
		addnav('Zurück','court.php?op=listj');
		break;
	}
	case 'entlassen':
	{
		$pid = (int)$_GET['id'];
		user_update(array('profession'=>0),$pid);
		$sql = "SELECT name FROM accounts WHERE acctid=".$pid;
		$res = db_query($sql);
		$p = db_fetch_assoc($res);
		systemmail($pid,"Du wurdest entlassen!",$session['user']['name']."`& hat dich als Richter entlassen!");
		$sql = "INSERT INTO news SET newstext = '".db_real_escape_string($p['name'])." `&wurde heute vom Amt eines Richters enthoben!',newsdate=NOW(),accountid=".$pid;
		db_query($sql);
		addhistory('`$Entlassung aus dem Richteramt',1,$pid);
		addnav("Weiter","court.php?op=listj");
		output("Der Richter wurde entlassen!");
		break;
	}
	case 'leave': //Richteramt abgeben
	{
		output("`tMit schlotternden Knien betrittst du das Zimmer, in dem der ältere Herr mit dem Backenbart wie gewohnt hinter seinem Schreibtisch sitzt. Als du eintrittst und ihm die Hand reichst bittet er dich Platz zu nehmen und schau dich erwartungsvoll an.`nWillst du wirklich dein Richteramt aufgeben?");
		addnav("Ja, austreten!","court.php?op=leave_ok",false,false,false,false,'Wirklich das Amt des Richters aufgeben?');
		addnav("NEIN. Dabei bleiben","dorfamt.php");
		break;
	}
	case 'leave_ok': //Amtsaufgabe bestätigen
	{
		output("`tDu bittest um deine Entlassung und der ältere Herr erledigt sichtlich schweren Herzens alle Formalitäten \"`sWirklich schade, dass Ihr geht! Ich danke Euch vielmals für die treuen Dienste, die Ihr der Stadt geleistet habt und werde Euch nie vergessen! Für heute seid Ihr beurlaubt.`t\"");
		addnews("".$session['user']['name']."`@ hat das Richteramt niedergelegt. Die Gaunerwelt atmet auf.");
		$session['user']['profession'] = 0;
		addhistory('`2Aufgabe des Richteramts');
		addnav("Zurück ins Zivilleben","dorfamt.php");
		break;
	}
	case 'board': //schwarzes Brett
	{
		require_once(LIB_PATH.'board.lib.php');
		output("`tDu stellst dich vor das große Brett und schaust ob eine neue Mitteilung vorliegt.`n");
		//addcommentary();
		// if (($session['user']['profession']==2) || ($session['user']['superuser']>1))
		//{
		output("`tDu kannst eine Notiz hinterlassen oder entfernen.`n`n");
		if ($_GET['board_action'] == "add")
		{
			board_add('richter');
			redirect("court.php?op=board&ret=".$_GET['ret']."");
		}
		else
		{
			board_view_form('Hinzufügen','');
			board_view('richter',2,'','',true,true,true);
		}
		if ($_GET['ret']==1)
		{
			addnav("Zurück","court.php?op=judgesdisc");
		}
		else
		{
			addnav("Zurück","court.php");
		}
		break;
	}
	case 'listj': //Liste der Richter
	{
		$admin = ($session['user']['profession'] == PROF_JUDGE_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG)) ? true : false;
		$sql = "SELECT name,acctid,loggedin,dragonkills,login,level,profession,activated,laston
			FROM accounts
			WHERE profession=21
				OR profession=22
				OR profession=23
				OR profession=25
			ORDER BY profession DESC, dragonkills DESC, level DESC";
		$result = db_query($sql);
		output("`tFolgende Helden sind Richter:
		`n`n`0<table border='0' cellpadding='5' cellspacing='2' bgcolor='#999999'>
		<tr class='trhead'>
		<th>Name</th>
		<th>Level</th>
		<th>Funktion</th>
		<th>".($admin?'Aktionen':'')."</th>
		<th>Status</th>
		</tr>",true);
		$lst=0;
		$dks=0;
		for ($i=0; $i<db_num_rows($result); $i++)
		{
			$row = db_fetch_assoc($result);
			$lst+=1;
			$dks+=$row['dragonkills'];
			output('<tr class="'.($lst%2?'trlight':'trdark').'">
			<td><a href="mail.php?op=write&to='.$row['acctid'].'" target="_blank" onClick="'.popup('mail.php?op=write&to='.$row['acctid'].'').';return false;">
			<img src="./images/newscroll.GIF" width="16" height="16" alt="Mail schreiben" border="0"></a>
			<a href="javascript:void(0);" onClick="'.popup('bio.php?id='.$row['acctid']).';return false;">'.$row['name'].'</a></td>
			<td>'.$row['level'].'</td>
			<td>',true);
			//addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
			if ($row['profession']==PROF_JUDGE)
			{
				output("`#Richter`0</td><td>",true);
				if ($admin)
				{
					output('<a href="court.php?op=entlassen&id='.$row['acctid'].'">Entlassen</a>',true);
					addnav("","court.php?op=entlassen&id=".$row['acctid']);
				}
			}
			elseif ($row['profession']==PROF_JUDGE_HEAD)
			{
				output("`4Oberster Richter`0</td><td>",true);
			}
			elseif ($row['profession']==PROF_JUDGE_ENT)
			{
				output("`6Entlassung läuft`0</td><td>",true);
				if ($admin)
				{
					output('<a href="court.php?op=entlassen&id='.$row['acctid'].'">Entlassen</a>',true);
					addnav("","court.php?op=entlassen&id=".$row['acctid']);
				}
			}
			elseif ($row['profession']==PROF_JUDGE_NEW)
			{
				output("`@Bittet um Aufnahme`0</td><td>",true);
				if ($admin)
				{
					output('<a href="court.php?op=aufn&id='.$row['acctid'].'">Aufnehmen</a>`n',true);
					addnav("","court.php?op=aufn&id=".$row['acctid']);
					output('<a href="court.php?op=abl&id='.$row['acctid'].'">Ablehnen</a>',true);
					addnav("","court.php?op=abl&id=".$row['acctid']);
				}
			}
			output("</td><td>".(user_get_online(0,$row)?'`@online`0':'`4offline`0')."</td></tr>");
		}
		output("</table>
		`n`@Gemeinsame Heldentaten der Richter : `^$dks`n`n`0");
		addnav('Zurück','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		break;
	}
	case 'listh': //Kopfgeldliste
	{
		output("<span style='color: #9900FF'>",true);
		output("`&Die Kopfgeldliste:`n`n");
		$sql = "SELECT name,acctid,location,bounty,laston,alive,house,loggedin,login,level,activated,restatlocation FROM accounts WHERE bounty>0
ORDER BY bounty DESC";
		$result = db_query($sql);
		output("<table border='0' cellpadding='4' cellspacing='1' bgcolor='#999999'><tr class='trhead'><td>Kopfgeld</td><td>Level</td><td>Name</td><td>Ort</td><td>Lebt?</td></tr>",true);
		$lst=0;
		for ($i=0; $i<db_num_rows($result); $i++)
		{
			$row = db_fetch_assoc($result);
			$lst+=1;
			output("<tr class='".($lst%2?"trlight":"trdark")."'><td>".($row['bounty'])."</td><td>".($row['level'])."</td><td><a href='bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."'>".$row['name']."</a>",true);
			addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
			output("</td><td>",true);
			if ($row['location'] == USER_LOC_FIELDS)
			{
				output(user_get_online(0,$row)?"`@online":"`3Die Felder",true);
			}
			if ($row['location']==USER_LOC_INN)
			{
				output("`3Zimmer in Kneipe`0",true);
			}
			if ($row['location']==USER_LOC_PRISON)
			{
				output("`3Im Kerker`0",true);
			}
			if ($row['location']==USER_LOC_HOUSE)
			{
				$loc=$row['restatlocation'];
				output("Haus Nr. $loc",true);
			}
			output("</td><td>",true);
			if ($row['alive'])
			{
				output("`@lebt`&",true);
			}
			else
			{
				output("`4tot`&",true);
			}
			output("</td></tr>",true);
		}
		addnav('Zurück','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		output("</table>",true);
		output("</span>",true);
		break;
	}
	case 'news': //verdächtige Taten
	{
		$daydiff = ($_GET['daydiff']) ? $_GET['daydiff'] : 0;
		$min = $daydiff-1;
		if($daydiff>=4) {
			$daydiff=1000;
			$min=3;
		}
		$sql = "SELECT newstext,newsdate,newsid,accountid
			FROM crimes
			WHERE (DATEDIFF(NOW(),newsdate) <= ".$daydiff."
			AND DATEDIFF(NOW(),newsdate) > ".$min.")
			ORDER BY newsid ASC
			LIMIT 0,200";
		$res = db_query($sql);
		output("`&Die verdächtigen Taten von ".(($daydiff==0)?"heute":(($daydiff==1)?"gestern":"vor ".$daydiff." Tagen")).":`n`0");
		for ($i=0; $i<db_num_rows($res); $i++)
		{
			$row = db_fetch_assoc($res);
			output('`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c
			'.$row['newstext'].'
			`n`0[&nbsp;'.create_lnk('Ermitteln','court.php?op=inspect&accountid='.$row['accountid'].'&daydiff='.$daydiff).'&nbsp;]
			[&nbsp;'.create_lnk('Löschen','court.php?op=newsdelete&newsid='.$row['newsid'].'&return='.URLEncode($_SERVER['REQUEST_URI'])).'&nbsp;]');
		}
		if (db_num_rows($res)==0)
		{
			output("`n`c`b`1 Keine offenen Fälle an diesem Tag.`0`b`c");
		}
		//addnav("Aktualisieren","court.php?op=news");
		addnav("Heute","court.php?op=news");
		addnav("Gestern","court.php?op=news&daydiff=1");
		addnav("2?Vor 2 Tagen","court.php?op=news&daydiff=2");
		addnav("3?Vor 3 Tagen","court.php?op=news&daydiff=3");
		if($session['user']['profession']== PROF_JUDGE_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG))
		{
			addnav("Verjährtes","court.php?op=news&daydiff=4");
		}
		addnav('Zurück','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		break;
	}
	case 'inspect': //Ermitteln und zur Anklage bringen
	{
		$sql = "SELECT newstext,newsdate,newsid
			FROM crimes
			WHERE accountid=".(int)$_GET['accountid']."
			ORDER BY newsid ASC
			LIMIT 0,200";
		$res = db_query($sql);
		output("`&Eine genauere Betrachtung bringt folgendes Ergebnis :`n`0");
		for ($i=0; $i<db_num_rows($res); $i++)
		{
			$row = db_fetch_assoc($res);
			output('`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c
			'.$row['newstext'].'`n`0');
		}
		addnav('Anklage erheben','court.php?op=accuse&ret='.$_GET['ret'].'&suspect='.$_GET['accountid'].'&daydiff='.$_GET['daydiff']);
		addnav('Zurück','court.php?op=news&ret='.(int)$_GET['ret'].'&daydiff='.(int)$_GET['daydiff']);
		break;
	}
	case 'caseinfo': //Auflistung der Vergehen eines Chars und Prozesseröffnungs- oder Verurteilungsmöglichkeit
	{
		$sql = "SELECT * FROM cases WHERE accountid=".$_GET['who']."
		ORDER BY newsid DESC
		LIMIT 0,200";
		$res = db_query($sql);
		output("`&Folgende Tatbestände werden verhandelt :`n");
		for ($i=0; $i<db_num_rows($res); $i++)
		{
			$row = db_fetch_assoc($res);
			output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
			output("$row[newstext]`n");
		}
		if(!$row['judgeid'])
		{
			output('`2Der Delinquient wurde inzwischen verurteilt.');
		}
		elseif ($row['court']==0)
		{
			output("`n`nVerfahren wurde eröffnet von:`n");
			$sql2 = "SELECT name FROM accounts WHERE acctid=$row[judgeid]";
//kann fehlschlagen wenn Link veraltet und Täter bereits verurteilt
			$res2 = db_query($sql2);
			$row2 = db_fetch_assoc($res2);
			output($row2['name']);
			output("`n`nEin anderer Richter muss das Urteil verkünden.");
			if (($session['user']['acctid']!=$row['judgeid']) && ($session['user']['acctid']!=$row['accountid']))
			{
				addnav('Mit Prozess');
				addnav('Prozess planen','court.php?op=preprozess&ret='.$_GET['ret'].'&who='.$row['accountid']);
				addnav('Prozess führen','court.php?op=prozess&ret='.$_GET['ret'].'&who='.$row['accountid']);
				addnav('Prozess öffentlich führen','court.php?op=prozess&public=1&ret='.$_GET['ret'].'&who='.$row['accountid']);
				addnav('Aktenlage');
				addnav('Verurteilen','court.php?op=guilty&suspect='.$_GET['who'].'&ret='.$_GET['ret']);
				addnav('Freisprechen','court.php?op=notguilty&suspect='.$_GET['who'].'&ret='.$_GET['ret']);
				addnav("Sonstiges");
			}
		}
		elseif ($row['court']==1)
		{
			$persons=array('judge'=>$session['user']['login'], 'counsel'=>'niemand', 'attestor'=>'niemand', 'public'=>'nein');
			if($row['persons']) $persons=utf8_unserialize($row['persons']);
			$form = array('Prozessvorbereitung,title',
						'judge'=>'Richter:',
						'prosecutor'=>'Anklagevertreter:',
						'counsel'=>'Verteidigung:',
						'attestor'=>'Zeugen:',
						'public'=>'öffentlich?');
			output('<form action="court.php?op=preprozess&who='.$row['accountid'].'" method="POST">');
			addnav('','court.php?op=preprozess&who='.$row['accountid']);
			showform($form,$persons);
			output('</form><hr>');
			addcommentary(false);
			viewcommentary('preprozess'.$row['accountid'],"Planen:",30,"sagt");
			addnav("Prozess führen","court.php?op=prozess&ret=".$_GET['ret']."&who=".$row['accountid']."");
			addnav("Prozess öffentlich führen","court.php?op=prozess&public=1&ret=".$_GET['ret']."&who=".$row['accountid']);
		}
		else
		{
			$persons=array('judge'=>'undefiniert', 'prosecutor'=>'niemand', 'counsel'=>'niemand', 'attestor'=>'niemand');
			if($row['persons']) $persons=utf8_unserialize($row['persons']);
			output('`n`n`&Es läuft ein Prozess zu diesem Fall!`n
			`nVorsitz: '.$persons['judge'].'
			`nAnklagevertretung: '.$persons['prosecutor'].'
			`nVerteidigung: '.$persons['counsel'].'
			`nZeugen: '.$persons['attestor'].'
			`nöffentlich: '.$persons['public'].'`0');
		}
		if ($_GET['proc']==1)
		{
			addnav('Zurück','court.php?op=thecourt2&accountid='.$_GET['who']);
		}
		else
		{
			addnav("Zurück","court.php?op=cases&ret=".$_GET['ret']."");
		}
		break;
	}
	case 'preprozess': //setzt angegebenen Prozess in den Planungszustand
	{
		$persons=array('judge'=>$session['user']['login'], 'prosecutor'=>'niemand', 'counsel'=>'niemand', 'attestor'=>'niemand', 'public'=>'nein');
		if(isset($_POST['judge']))
		{
			$persons['judge']=$_POST['judge'];
			$persons['prosecutor']=$_POST['prosecutor'];
			$persons['counsel']=$_POST['counsel'];
			$persons['attestor']=$_POST['attestor'];
			$persons['public']=$_POST['public'];
		}
		db_query('update cases SET court=1, persons="'.db_real_escape_string(utf8_serialize($persons)).'" WHERE accountid='.$_GET['who']);
		redirect('court.php?op=caseinfo&ret='.$_GET['ret'].'&who='.$_GET['who']);
		addnav("Zurück","court.php");
		break;
	}
	case 'accuse': //anklagen
	{
		$sql = "SELECT newstext,newsdate,newsid
			FROM crimes
			WHERE accountid=".$_GET['suspect']."
			ORDER BY newsid ASC
			LIMIT 0,200";
		$res = db_query($sql);
		if(!db_num_rows($res)) {
			output("`&Alle Fälle wurden bereits abgearbeitet - scheint, als sei dir ein anderer Richter zuvorgekommen!");
		}
		else {
			output("`&Die Verbrechen wurde soeben zur Anklage gebracht.`n");
			for ($i=0; $i<db_num_rows($res); $i++)
			{
				$row = db_fetch_assoc($res);
				addtocases($row['newstext'],$_GET['suspect']);
				$sql = "DELETE FROM crimes WHERE newsid='$row[newsid]'";
				db_query($sql);
			}
			redirect('court.php?op=news&daydiff='.$_GET['daydiff']);
		}
		
		addnav("Zurück","court.php?op=news&daydiff=$_GET[daydiff]");
		break;
	}
	case 'cases': //aktuelle Fälle
	{
		$sql = "SELECT newsid,accountid,judgeid,court,name FROM cases
				LEFT JOIN accounts ON accountid = acctid
				GROUP BY accountid,judgeid
				ORDER BY court ASC, newsid ASC
				LIMIT 0,200";
		$res = db_query($sql);
		$int_count = db_num_rows($res);
		output('`&Derzeit wird '.$int_count.' Verbrechern der Prozess gemacht:`n`n`0');
		if ($int_count==0)
		{
			output("`n`1`b`c Zurzeit werden keine Fälle verhandelt. `c`b`0");
		}
		for ($i=0; $i<$int_count; $i++)
		{
			$row = db_fetch_assoc($res);
			output("<a href='court.php?op=caseinfo&ret=".$_GET['ret']."&who=$row[accountid]'>".($row['name']?$row['name']:$row['accountid'].'`4 (User gelöscht)`0')."</a>".($row['judgeid'] == $session['user']['acctid'] ? ' (Von dir angeklagt)':'').($row['court'] ? ' (Prozess '.($row['court']==1 ? 'in Planung':'läuft').')':'')."`n",true);
			addnav("","court.php?op=caseinfo&ret=".$_GET['ret']."&who=$row[accountid]");
		}
		addnav('Zurück','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		break;
	}
	case 'guilty': //Strafe festlegen
	{
		output('Wie lautet dein Strafmaß?
		`n<form method="POST" action="court.php?op=guilty2&ret='.$_GET['ret'].'&suspect='.$_GET['suspect'].'&proc='.$_GET['proc'].'">
		`n<input type="text" name="count" id="count"><input type="hidden" name="count2"> 
		<input type="submit" value="Tage Haft">
		</form>'.focus_form_element('count'));
		addnav('','court.php?op=guilty2&ret='.$_GET['ret'].'&suspect='.$_GET['suspect'].'&proc='.$_GET['proc'].'');
		if ($_GET['proc']!=1)
		{
			addnav("Zurück","court.php?op=caseinfo&ret=".$_GET['ret']."&who=$_GET[suspect]");
		}
		else
		{
			addnav("Zurück","court.php?op=thecourt2&ret=".$_GET['ret']."&accountid=$_GET[suspect]");
		}
		break;
	}
	case 'guilty2': //Strafe bestätigen
	{
		$count = $_POST['count'];
		$maxsentence=getsetting("maxsentence",5);
		if ($count>$maxsentence)
		{
			output("Na, wir wollen es mal nicht übertreiben. Findest du nicht, dass ".$maxsentence." Tage ausreichend wären ?");
		}
		else
		{
			$sql3 = 'SELECT newstext
				FROM cases
				WHERE accountid='.$_GET['suspect'].'
				ORDER BY newsid ASC
				LIMIT 0,200';
			$res3 = db_query($sql3);
			$int_casescount=db_num_rows($res3);
			if($int_casescount>0) //wenn das 0 ist war ein anderer Richter schneller
			{
				$sql2 = "SELECT name,a.acctid,sentence
					FROM accounts a
					LEFT JOIN account_extra_info aei ON a.acctid=aei.acctid
					WHERE a.acctid=".$_GET['suspect'];
				$res2 = db_query($sql2);
				$row2 = db_fetch_assoc($res2);
				$count2 = min($count+$row2['sentence'],$maxsentence);
				output("`&Alles klar! ".$count." Tage Haft. Die Stadtwachen wurden informiert. ".$row2['name']." `&soll nun für ".$count2." `&Tage hinter Gitter!");
				addnews("`#Richter" . ($session['user']['sex']?'in':'') . " ".$session['user']['name']."`& hat `@".$row2['name']."`& zu ".$count." `&Tagen Kerker verurteilt!");
				$mailtext="`@".$session['user']['name']."`& hat dich für deine Vergehen zu ".$count." Tagen Kerker verurteilt!
				`nDiese Strafe wird zu eventuell anderen Strafen hinzugerechnet, jedoch kann deine Haft dadurch nicht länger als ".$maxsentence." Tage werden.
				`nDeine Vergehen im Einzelnen:
				`n`n";
				for ($j=0; $j<$int_casescount; $j++)
				{
					$row3 = db_fetch_assoc($res3);
					$mailtext=$mailtext.$row3['newstext']."`n";
				}
				systemmail($row2['acctid'],'`$Du wurdest verurteilt!`0',$mailtext);
				$sql = "DELETE FROM cases WHERE accountid='$_GET[suspect]'";
				db_query($sql);
				$sql = "UPDATE account_extra_info SET sentence=$count2 WHERE acctid='$_GET[suspect]'";
				db_query($sql);
			}
			else
			{
				output('`nOops, da war ein Kollege schneller. Die Akte ist inzwischen geschlossen.`n');
			}
			if ($_GET['proc']==1)
			{
				insertcommentary(1,'/msg`^Das Hohe Gericht verurteilt '.$row2['name'].'`^ zu '.$count.' Tagen Kerker und beendet den Prozess.',"court".$_GET['suspect']);
			}
		}
		if ($_GET['proc']==1)
		{
			db_query('UPDATE items SET owner = "0" WHERE value1='.$_GET['suspect']);
		}
		addnav("Zurück","court.php?op=cases");
		break;
	}
	case 'notguilty': //freisprechen
	{
		output("Du entscheidest zugunsten des Angeklagten.");
		$sql2 = "SELECT name FROM accounts WHERE acctid=$_GET[suspect]";
		$res2 = db_query($sql2);
		$row2 = db_fetch_assoc($res2);
		addnews("`#Richter" . ($session['user']['sex']?'in':'') . " ".$session['user']['name']."`& hat `@".$row2['name']."`& freigesprochen!");
		$sql = "DELETE FROM cases WHERE accountid='$_GET[suspect]'";
		db_query($sql);
		if ($_GET['proc']==1)
		{
			$roomname="court".$_GET['suspect'];
			
			insertcommentary(1,'/msg`@Das Hohe Gericht spricht '.$row2['name'].'`@ in allen Anklagepunkten frei und beendet den Prozess.',$roomname);
		}
		if ($_GET['proc']==1)
		{
		//item_delete(' tpl_id="vorl" AND value1='.$_GET['suspect']);
			db_query('UPDATE items SET owner = "0" WHERE value1='.$_GET['suspect']);
		}
		addnav("Zurück","court.php?op=cases");
		break;
	}
	case 'archiv': //verkündete Urteile
	{
		$daydiff = ($_GET['daydiff']) ? $_GET['daydiff'] : 0;
		$min = $daydiff-1;

		$sql = "SELECT newstext,newsdate
			FROM news
			WHERE (newstext LIKE '%freigesprochen%'
				OR newstext LIKE '%verurteilt%')
			AND (DATEDIFF(NOW(),newsdate) <= ".$daydiff."
			AND DATEDIFF(NOW(),newsdate) > ".$min.")
			ORDER BY newsid DESC
			LIMIT 0,200";
		$res = db_query($sql);
		output("`&Urteile von ".(($daydiff==0)?"heute":(($daydiff==1)?"gestern":"vor ".$daydiff." Tagen")).":`n`0");
		while ($n = db_fetch_assoc($res))
		{
			output('`n`n'.$n['newstext']);
		}
		if (db_num_rows($res)==0)
		{
			output("`n`c`b`1 Keine Urteile an diesem Tag.`0`b`c");
		}
		//addnav("Aktualisieren","court.php?op=archiv");
		addnav("Heute","court.php?op=archiv");
		addnav("Gestern","court.php?op=archiv&daydiff=1");
		addnav("2?Vor 2 Tagen","court.php?ret=".$_GET['ret']."&op=archiv&daydiff=2");
		addnav("3?Vor 3 Tagen","court.php?ret=".$_GET['ret']."&op=archiv&daydiff=3");
		addnav("4?Vor 4 Tagen","court.php?ret=".$_GET['ret']."&op=archiv&daydiff=4");
		addnav("5?Vor 5 Tagen","court.php?ret=".$_GET['ret']."&op=archiv&daydiff=5");
		addnav('Zurück','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		break;
	}
	case 'faq': //Handbuch für Jungrichter
	{
		output(get_extended_text('judge_policy'));
		addnav('Zurück','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		break;
	}
	case 'schreiber': //Gerichtsschreiber
	{
		output("`eIn `sei`yne`tm viel zu kleinen Raum sitzt ein karges Männlein hinter einem kleinen Tisch, der meterhoch mit Unterlagen zugestellt ist. Irgendwo dazwischen steht eine kleine eiserne Kassette auf dem Tisch, die ein paar Goldmünzen enthält. Der Schreiber schaut dich an als du eint`yri`stt`est.'");
		addnav("Anzeige erstatten","court.php?op=anzeige&ret=".$_GET['ret']."");
		addnav('Zurück','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		break;
	}
	case 'anzeige': //Person suchen und Anzeige verfassen
	{
		output("`tDer Schreiberling schaut dich an. \"`sNa, wer hat Euch denn Schlimmes angetan?`t\" fragt er.`n`n");

		if ($_GET['who']=="")
		{
			addnav("Äh.. niemand!","court.php?op=schreiber&ret=".$_GET['ret']."");
			if ($_GET['subop']!="search")
			{
				output("<form action='court.php?op=anzeige&ret=".$_GET['ret']."&subop=search' method='POST'><input name='name'><input type='submit' class='button' value='Suchen'></form>",true);
				addnav("","court.php?op=anzeige&ret=".$_GET['ret']."&subop=search");
			}
			else
			{
				addnav("Neue Suche","court.php?op=anzeige&ret=".$_GET['ret']."");
				$search = str_create_search_string($_POST['name']);
				$sql = "SELECT acctid,name,level
					FROM accounts
					WHERE (locked=0 AND name LIKE '$search')
					ORDER BY login='".db_real_escape_string($_POST['name'])."' DESC, level DESC";
				$result = db_query($sql);
				$max = db_num_rows($result);
				if ($max > 50)
				{
					output("`n`n\"`sGeht es vielleicht ein bisschen genauer ?`t`n");
					$max = 50;
				}
				$str_output.="<table border=0 cellpadding=0>
				<tr class='trhead'>
				<th>Name</th>
				<th>Level</th>
				</tr>";
				for ($i=0; $i<$max; $i++)
				{
					$row = db_fetch_assoc($result);
					$str_output.="<tr class='trdark'>
					<td><a href='court.php?op=anzeige&ret=".$_GET['ret']."&who=".($row['acctid'])."'>".$row['name']."`0</a></td>
					<td>".$row['level']."</td>
					</tr>";
					addnav("","court.php?op=anzeige&ret=".$_GET['ret']."&who=".($row['acctid']));
				}
				output($str_output."</table>",true);
			}
		}
		else
		{
			$sql = "SELECT acctid,login,name
				FROM accounts
				WHERE acctid=\"$_GET[who]\"";
			$result = db_query($sql);
			if (db_num_rows($result)>0)
			{
				$row = db_fetch_assoc($result);
				if ($session['user']['profession']==21 || $session['user']['profession']==22)
				{
					$costs=0;
				}
				else
				{
					$costs=$session['user']['level']*100;
				}
				output("`tDer Schreiber nickt. \"`&Ja, der Name ".($row['name'])." `& ist mir ein Begriff... Die Gebühren für eine Anzeige liegen für Euch bei `^".$costs." Gold.`&\"`&`n`n");
				if ($costs>$session['user']['gold'])
				{
					output("`t`n`qDu schaust in deinen Beutel und stellst fest daß du nicht genug Gold dabei hast.`n`QUntertänigst entschuldigst du dich beim Gerichtsdiener und verlässt das Gebäude.`n`n");
					addnav("Tut mir leid!","village.php");
				}
				else
				{
					output("`n`&Wie lautet deine Anzeige? Bitte beschreibe den Tathergang ausführlich!");
					output("<form action='court.php?op=anzeige2&ret=".$_GET['ret']."&who=".($row['acctid'])."' method='POST'><textarea name='text' id='text' class='input' cols='50' rows='10'></textarea><br><input type='submit' class='button' value='diktieren'></form>",true);
					JS::Focus('text');
					addnav("","court.php?op=anzeige2&ret=".$_GET['ret']."&who=".($row['acctid'])."");
					addnav("Abbrechen","court.php?ret=".$_GET['ret']."&op=schreiber");
				}
			}
			else
			{
				output("\"`sIch kenne niemanden mit diesem Namen.`&\"");
			}
		}
		break;
	}
	case 'anzeige2': //Anzeige bestätigen
	{
		$text = stripslashes($_POST['text']);
		$sql = "SELECT acctid,login,name FROM accounts WHERE acctid=\"$_GET[who]\"";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		output("`&Die Anzeige lautet:`n`n");
		$pretext="`&Anzeige von ".$session['user']['name']." `&gegen ".$row['name']." `&: ";
		$text2=$pretext.$text;
		output($text2);
		$session['user']['pqtemp']=$text2;
		output("`n`n`&Zufrieden?");
		addnav("Sehr gut!","court.php?op=anzeige3&ret=".$_GET['ret']."&who=".$row['acctid']."");
		addnav("Nein, nochmal!","court.php?op=anzeige&ret=".$_GET['ret']."&who=".($row['acctid'])."");
		break;
	}
	case 'anzeige3': //Anzeige aufnehmen und Bezahlung
	{
		$sql = "SELECT acctid,login,name FROM accounts WHERE acctid=\"$_GET[who]\"";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$text=$session['user']['pqtemp'];
		output("`&Du hast zu Protokoll gegeben:`n");
		output($text);
		if ($session['user']['profession']==21 || $session['user']['profession']==22)
		{
		$buy=0;
		}
		else
		{
		$buy=$session['user']['level']*100;
		}
		if ($buy>$session['user']['gold'])
		{
			output("`&`n`nWas glaubst du wo du hier bist? Die Mühlen der Justiz mahlen sicherlich nicht umsonst. Also besorg dir ein wenig Kleingeld bevor du wiederkommst.`nDer Gerichtsdiener befördert dich mit einem Tritt nach draussen.");
			addnav("Autsch!","village.php");
		}
		else
		{
			output('`&`n`n');
			$text=str_replace("  "," ",$text);
			if ($session['user']['profession']==21 || $session['user']['profession']==22)
			{
				output('`tDer Schreiberling sieht dich an: `s"Ihr als Richter müsst natürlich keine Gebühr bezahlen."`t`n`n');
			}
			else
			{
				if(mb_strlen($text)>150)
				{
					output('`tDer Schreiberling sieht dich an: `s"Wenn das so ist brauchst Du nur die halbe Gebühr bezahlen."`t`n');
					$buy*=0.5;
				}
				output("Du bezahlst deine $buy Goldmünzen und sie versinken leise klirrend in der eisernen Kassette auf des Schreiberlings Tisch.`n`n");
				$session['user']['gold']-=$buy;
			}
			$sql = "INSERT INTO crimes(newstext,newsdate,accountid) VALUES ('".db_real_escape_string($text)."',NOW(),".$row['acctid'].")";
			db_query($sql);
			$session['user']['pqtemp']='';
			addnav('Hehe...','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		}
		break;
	}
	case 'thecourt': //Liste betretbarer RPG-Prozesse
	{
//Richter bekommen alle Prozesse
		if ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG) )
		{
			$res= item_list_get(' i.tpl_id="vorl" ',' GROUP BY value1 ORDER BY value1 DESC LIMIT 0,200 ');
		}
		else
		{
		if(item_get(' i.tpl_id="vorl" AND i.owner='.$session['user']['acctid'],false))
//auf eigene Vorladung prüfen
		{
			$res= item_list_get(' i.tpl_id="vorl" AND (i.owner="'.$session['user']['acctid'].'" OR value2="1") GROUP BY value1 ORDER BY value1 DESC LIMIT 0,200 ');
		}
		else //Abfrage ob public
		{
			$res= item_list_get(' i.tpl_id="vorl" AND value2="1" GROUP BY value1 ORDER BY value1 DESC LIMIT 0,200 ');
		}
		}
		if (db_num_rows($res))
		{
			output("`&Zu welchem Prozess möchtest du gehen ?`n`n");
			$int_count = db_num_rows($res);
			for ($i=0; $i<$int_count; $i++)
			{
				$row = db_fetch_assoc($res);
				$sql2 = "SELECT name FROM accounts WHERE acctid=$row[value1] ORDER BY name DESC";
				$res2 = db_query($sql2);
				$row2 = db_fetch_assoc($res2);
				output(create_lnk('&raquo; `&'.strip_appoencode($row2['name'],3),"court.php?op=thecourt2&ret=".$_GET['ret']."&accountid=$row[value1]").'`n',true);
			}
			addnav("Zurück","court.php");
		}
		else
		{
			if ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG) )
			{
				output("`&Derzeit werden hier keine Fälle verhandelt und du bist gewiss nicht gekommen um den Boden zu schrubben...`n`n");
				if ($_GET['ret']==1)
				{
					addnav("Zurück","court.php?op=judgesdisc");
				}
				else
				{
					addnav("Zurück","court.php");
				}
			}
			else
			{
				output("`&Du hast keine Vorladung und die Verhandlungen sind nicht öffentlich.`nWas willst du also hier ?`n`n");
				addnav("Zurück","court.php");
			}
		}
		break;
	}
	case 'thecourt2': //Verhandlungsraum
	{
		output('`eDu`s öf`yfn`test die schwere Eichentüre und betrittst den Gerichtssaal. Stühle und Bänke sind im hinteren Teil des großen Raumen ordentlich aufgestellt worden, eine Absperrung trennt diesen Teil von der Richterkanzel. Türen im hinteren Teil des Raumes führen zum Archiv und zum Besprechungsraum. Du stellst fest, dass dieser Raum sehr gepflegt und der Boden gut poli`yer`st i`est.`n`n');
		$roomname="court".$_GET['accountid'];
		addcommentary();
//Verhandlungsraum
		$bool_showform = ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || $session['user']['profession']==PROF_JUDGE_NEW || item_get(' i.tpl_id="vorl" AND i.owner='.$session['user']['acctid'].' AND value1='.$_GET['accountid'],false) || $session['user']['superuser']);
		viewcommentary($roomname,"Sagen:",30,"sagt",false,$bool_showform);
		//(wer || wer darf?) && Vorladung Besitzer= Angeklagter?
		if (($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG)) && item_get(' i.tpl_id="vorl" AND i.owner= '.$_GET['accountid'],false) )
		{
			addnav('Zeugen vorladen');
			addnav('Vorladen','court.php?op=witn&ret='.$_GET['ret'].'&accountid='.$_GET['accountid']);
			addnav('Anklageschrift');
			addnav('Lesen','court.php?op=caseinfo&ret='.$_GET['ret'].'&who='.$_GET['accountid'].'&proc=1');
			if ($session['user']['acctid']!=$_GET['accountid'])
			{
				addnav('Prozess beenden');
				addnav('Schuldig','court.php?op=guilty&ret='.$_GET['ret'].'&proc=1&suspect='.$_GET['accountid']);
				addnav('Nicht schuldig','court.php?op=notguilty&ret='.$_GET['ret'].'&proc=1&suspect='.$_GET['accountid']);
			}
			addnav('Prozesspause');
			addnav('Saal verlassen','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		}
		else
		{
			addnav('Raus hier!','court.php');
		}
		break;
	}
	case 'judgesdisc': //Diskussionsraum der Richter
	{
		output(get_title('`2Das Hinterzimmer')."
		`eHi`ser`y im`t kleinen Hinterzimmer des großes Verhandlungsraumes kannst du dich mit den anderen Richtern treffen. Ungestört von Plebs und Pöbel könnt ihr hier wichtige Fälle diskutieren oder einfach nur mal kurz ausspannen.`nEin großer runder Tisch in der Mitte des Raumes bietet allen Richtern Platz und sieht sehr gemütl`yic`sh a`eus.`n`n");
		if ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG) )
		{
			addcommentary();
		}
		viewcommentary("judges","Deine Meinung sagen:",30,"meint");
		addnav("Öffentliches");
		addnav("Verhandlungsraum","court.php?op=thecourt&ret=1");
		addnav("Liste der Richter","court.php?op=listj&ret=1");
		addnav("Gerichtsschreiber");
		addnav("G?Zum Gerichtsschreiber","court.php?op=schreiber&ret=1");
		addnav("Arbeit");
		addnav("T?Verdächtige Taten","court.php?op=news&ret=1");
		addnav("F?Aktuelle Fälle","court.php?op=cases&ret=1");
		//addnav("Kopfgeldliste","court.php?op=listh&ret=1");
		addnav("B?Schwarzes Brett","court.php?op=board&ret=1");
		addnav("OOC Diskussionsraum","court.php?op=judgesooc");
		addnav("Archiv");
		addnav("Urteile","court.php?op=archiv&ret=1");
		addnav("J?Handbuch für Jungrichter","court.php?op=faq&ret=1");
		addnav("Zurück");
		addnav("h?Gericht Eingangshalle","court.php");
		addnav("a?Zum Rathaus","dorfamt.php");
		addnav("d?Zum Stadtzentrum","village.php");
		break;
	}
	case 'judgesooc': //OOC-Raum der Richter
	{
		output(get_title('`2Der OOC-Raum')."
		`eHi`ser`y im`t noch kleineren Hinterzimmer kannst du dich zurückziehen wenn es draußen zu hektisch wird. An allen Wänden stehen hohe Regale mit alte`yn A`skt`een`n`n");
		if ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG) )
		
    require_once(LIB_PATH.'board.lib.php');
        output('`0`c');
        $int_pollrights = (($session['user']['ddl_rank'] == PROF_DDL_COLONEL) ? 2 : 1);
        if(poll_view('judges_ooc',$int_pollrights,$int_pollrights))
        {
            output('`n`^~~~~~~~~`0`n`n',true);
        }
        output('`c');
    
    {
			addcommentary();
		}
		viewcommentary("judges_ooc");
		addnav("Öffentliches");
		addnav("Verhandlungsraum","court.php?op=thecourt&ret=1");
		addnav("Liste der Richter","court.php?op=listj&ret=1");
		addnav("Gerichtsschreiber");
		addnav("G?Zum Gerichtsschreiber","court.php?op=schreiber&ret=1");
		addnav("Arbeit");
		addnav("T?Verdächtige Taten","court.php?op=news&ret=1");
		addnav("F?Aktuelle Fälle","court.php?op=cases&ret=1");
		//addnav("Kopfgeldliste","court.php?op=listh&ret=1");
		addnav("B?Schwarzes Brett","court.php?op=board&ret=1");
		addnav("H?Zum Hinterzimmer","court.php?op=judgesdisc");
		if ($session['user']['profession']==PROF_JUDGE_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG))
		  {
      addnav("Umfrage erstellen","court.php?op=poll");
      }
		addnav("Archiv");
		addnav("Urteile","court.php?op=archiv&ret=1");
		addnav("J?Handbuch für Jungrichter","court.php?op=faq&ret=1");
		addnav("Zurück");
		addnav("h?Gericht Eingangshalle","court.php");
		addnav("a?Rathaus","dorfamt.php");
		addnav("d?Stadtzentrum","village.php");
		break;
	}
	case 'prozess': //RPG-Prozess eröffnen
	{
		$sql = "SELECT name FROM accounts WHERE acctid=$_GET[who]";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$item['tpl_value1'] = $_GET['who'];
		$item['tpl_value2'] = $_GET['public'];
		$item['tpl_description'] = '`&Du wirst zum Gericht befohlen! Es betrifft das Verfahren gegen `4DICH!`& Solltest du dem nicht nachkommen, droht dir eine harte Strafe.';
		item_add($_GET['who'], 'vorl', $item );
		systemmail($_GET['who'],"`4Vorladung!`2",$item['tpl_description']);
		output($row['name']."`& hat eine Vorladung erhalten und wird sich (hoffentlich) bald im Gerichtssaal einfinden.`n");
		$sql = "UPDATE cases SET court=2 WHERE accountid=".$_GET['who'];
		db_query($sql);
		$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'court".$_GET['who']."',".$session['user']['acctid'].",'/msg ---Prozess eröffnet am ".getgamedate()." von Richter ".$session['user']['login']."---')";
		db_query($sql);
		addnav('Zurück','court.php'.($_GET['ret']==1?'?op=judgesdisc':''));
		break;
	}
	case 'witn': //Zeugen vorladen Namenssuche
	{
		output("`&Wen möchtest du zu diesem Prozess vorladen?`n`n");
		if ($_GET['who']=="")
		{
			addnav("Niemanden!","court.php?op=thecourt2&accountid=$_GET[accountid]");
			if ($_GET['subop']!="search")
			{
				output("<form action='court.php?op=witn&ret=".$_GET['ret']."&accountid=$_GET[accountid]&subop=search' method='POST'><input name='name'><input type='submit' class='button' value='Suchen'></form>",true);
				addnav("","court.php?op=witn&ret=".$_GET['ret']."&accountid=$_GET[accountid]&subop=search");
			}
			else
			{
				addnav("Neue Suche","court.php?op=witn&ret=".$_GET['ret']."&accountid=$_GET[accountid]");
				$search = str_create_search_string($_POST['name']);
				$sql = "SELECT name,alive,location,sex,level,reputation,laston,loggedin,login FROM accounts WHERE (locked=0 AND name LIKE '$search') ORDER BY IF(login='".db_real_escape_string(stripslashes($_POST['name']))."',1,0) DESC, level DESC";
				$result = db_query($sql);
				$max = db_num_rows($result);
				if ($max > 50)
				{
					output("`n`n`&Zu viele Suchergebnisse`&`n");
					$max = 50;
				}
				output("<table border=0 cellpadding=0><tr><td>Name</td><td>Level</td></tr>",true);
				for ($i=0; $i<$max; $i++)
				{
					$row = db_fetch_assoc($result);
					output("<tr><td><a href='court.php?op=witn&ret=".$_GET['ret']."&accountid=".$_GET['accountid']."&who=".rawurlencode($row['login'])."'>".$row['name']."</a></td><td>".$row['level']."</td></tr>",true);
					addnav("","court.php?op=witn&ret=".$_GET['ret']."&accountid=".$_GET['accountid']."&who=".rawurlencode($row['login']));
				}
				output("</table>",true);
			}
		}
		else
		{
			$sql = "SELECT acctid,login,name FROM accounts WHERE login=\"$_GET[who]\"";
			$result = db_query($sql);
			if (db_num_rows($result)>0)
			{
				$row = db_fetch_assoc($result);
				output($row['name']." `& als Zeugen vorladen ?`n`n");
				addnav("Ja","court.php?op=witn2&ret=".$_GET['ret']."&accountid=$_GET[accountid]&who=".$row['acctid']."");
				addnav("Nein","court.php?op=thecourt2&ret=".$_GET['ret']."&accountid=$_GET[accountid]");
			}
			else
			{
				output("\"`#Name wurde nicht gefunden.`&\"");
			}
		}
		break;
	}
	case 'witn2': //Vorladung abschicken
	{
		$sql = "SELECT name FROM accounts WHERE acctid=$_GET[accountid]";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$sql2 = "SELECT name FROM accounts WHERE acctid=$_GET[who]";
		$res2 = db_query($sql2);
		$row2 = db_fetch_assoc($res2);
		$item['tpl_value1'] = $_GET['accountid'];
		$item['tpl_description'] = '`&Du wirst zum Gericht befohlen! Es betrifft das Verfahren gegen '.$row['name'].'`&. Solltest du dem nicht nachkommen, droht dir eine harte Strafe.';
		item_add($_GET['who'], 'vorl', $item );
		systemmail($_GET['who'],"`4Vorladung!`2",$item['tpl_description']);
		output($row2['name']."`& hat eine Vorladung erhalten und wird sich (hoffentlich) bald im Gerichtssaal einfinden.`n");
		$roomname="court".$_GET['accountid'];
		
		insertcommentary(1,'/msg `&'.$row2['name'].'`& wird vom Hohen Gericht als Zeuge vorgeladen!',$roomname);
		addnav("Zurück","court.php?op=thecourt2&ret=".$_GET['ret']."&accountid=$_GET[accountid]");
		break;
	}
	case 'entrymsg': //Eintrittsmessage posten (gestrichen)
	{
		$roomname="court".$_GET['accountid'];
//        $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'$roomname',".$session['user']['acctid'].",'/me `&betritt den Gerichtssaal.`V')";
//        db_query($sql);
		redirect("court.php?op=thecourt2&ret=".$_GET['ret']."&accountid=$_GET[accountid]");
		break;
	}
	case 'leavemsg': //Verlassen-Message posten (gestrichen)
	{
		$roomname="court".$_GET['accountid'];
//        $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'$roomname',".$session['user']['acctid'].",'/me `&verlässt den Gerichtssaal.`V')";
//        db_query($sql);
		if ($_GET['ret']==1)
		{
			redirect("court.php?op=judgesdisc");
		}
		else
		{
			redirect("court.php");
		}
		break;
	}
	
	case 'massmail': // Massenmail (im wohnviertel by mikay)
    {
        $str_out .= get_title('Taubenschlag unter dem Dach des Gerichtes.`0');

        addnav('Zurück','court.php');

        $sql='SELECT acctid, name, login, profession
            FROM accounts
            WHERE profession='.PROF_JUDGE.'
            OR profession='.PROF_JUDGE_HEAD.'
            OR profession='.PROF_JUDGE_NEW.'
            AND acctid!='.(int)$session['user']['acctid'].'
            ORDER BY profession DESC';
        $result=db_query($sql);
        $users=array();
        $keys=0;

        while($row=db_fetch_assoc($result))
        {
            $profs[0][0]='Zivilist';
            if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';

            $residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" id="inp23421" '.($row['profession']!=PROF_JUDGE_NEW ? 'checked':'').'> '.$row['name'].'
            '.JS::event('#inp23421','click','chk();').'
            <br>';
            $keys++;
            $lastprofession=$row['profession'];

            if ($_POST['title']!='' && $_POST['maintext']!='' && in_array($row['acctid'],$_POST['msg']))
            {
                $users[]=$row['acctid'];
            }
        }

        $mailsends=count($users);

        if ($mailsends<=5)
        {
            $gemcost=1;
        }
        elseif ($mailsends<=15)
        {
            $gemcost=2;
        }
        elseif ($mailsends<=25)
        {
            $gemcost=3;
        }
        elseif ($mailsends>25)
        {
            $gemcost=4;
        }
        $gemcost=0;

        if ($session['user']['gems']>=$gemcost AND $mailsends>0)
        {
            foreach($users as $id)
            {
                systemmail($id, $_POST['title'], $_POST['maintext'], $session['user']['acctid']);
            }

            $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler haben eine Taube erhalten und deine Kosten betragen '.$gemcost.' Edelsteine.<br><br>';
            $session['user']['gems']-=$gemcost;
        }
        elseif ($session['user']['gems']<$gemcost AND $mailsends>0)
        {
            $sendresult='<b>Sendebericht:</b><br>'.count($users).' Spieler hätten eine Taube erhalten, wenn deine Kosten nicht '.$gemcost.' Edelsteine betragen würden. Leider kannst du dies nicht bezahlen.<br><br>';
        }

        if ($keys>0)
        {
            $str_out .= form_header('court.php?op=massmail')
            .$sendresult.'
            <table border="0" cellpadding="0" cellspacing="10">
                <tr>
                    <td><b>Betreff:</b></td>
                    <td><input type="text" name="title" id="title">
                    '.JS::event('#title','keydown','chk()').'
                    '.JS::event('#title','focus','chk()').'
                    </td>
                </tr>
                <tr>
                    <td valign="top"><b>Nachricht:</b></td>
                    <td><textarea name="maintext" id="maintext" rows="15" cols="50" class="input"></textarea>
                    '.JS::event('#maintext','keydown','chk()').'
                    '.JS::event('#maintext','focus','chk()').'
                    </td>
                </tr>
                <tr>
                    <td valign="top"><b>Senden an:</b></td>
                    <td>'.$residents.'
                        `bKosten bis jetzt:`b <span id="cost">0</span> Edelstein(e)!
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <span id="but" style="visibility:hidden;"><input type="submit" value="Tauben auf die Reise schicken!" class="button"><br></span>
                        <span id="msg">Bitte verfasse nun deine Botschaft und wähle die Empfänger!</span></td>
                </tr>
            </table>
            </form>
            '.JS::MassMail(true);
        }
        else
        {
            $str_out .= '`c`bEs wurden noch keine Schlüssel verteilt - und ja, Bombentauben an missliebige Nachbarn sind gegen das Gesetz.`b`c';
        }
        output($str_out);
        break;
    } // END massmail
    
    case 'poll': //Umfrage erstellen
    {
        require_once(LIB_PATH.'board.lib.php');
        output(get_title('Umfragen der Richter'));
        poll_add('judges_ooc'.$_GET['pollsection'],100,1);
        if(!empty($session['polladderror'])) {
            if($session['polladderror'] == 'maxpolls')
            {
                output('`$An dieser Stelle findet bereits eine Umfrage statt! Entferne bitte zunächst diese, ehe du eine neue eröffnest.`n`n');
            }
        }
        else
        {
            redirect('court.php?op=judgesooc');
        }

        if($_GET['pollsection'] == 'private')
        {
            output('`8Du möchtest also im Diskussionsraum eine Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und an einer prominenten Stelle aufgehängt zu werden:`n`n');

        }
        else
        {
            output('`8Du möchtest also eine öffentliche Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und für alle gut sichtbar platziert zu werden:`n`n');
        }
        addnav('Zurück zum Pausenraum','court.php?op=judgesooc');

        poll_show_addform();
        break;
    }
    
	default: //Gericht Hauptgebäude
	{
		addcommentary();
		output(get_title("`eDe`sr G`yer`tichtshof von A`ytr`sah`eor")."
		`eDi`ses`yer`t Teil des Gebäudes ist dem Gerichtswesen zugeteilt. Mehrere Türen sind links und rechts des breiten Ganges zu erkennen und auf großen Holztäfelchen steht geschrieben was sich dahinter verbirgt.`nManche Türen sind für dich verschlossen, andere zugä`yng`sli`ech.");
		addnav("Öffentliches");
		addnav("Verhandlungsraum","court.php?op=thecourt");
		addnav("Liste der Richter","court.php?op=listj");
		addnav("Gerichtsschreiber");
		addnav("G?Zum Gerichtsschreiber","court.php?op=schreiber");
		if ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD
		|| $access_control->su_check(access_control::SU_RIGHT_DEBUG) )
		{
			addnav("Arbeit");
			addnav("T?Verdächtige Taten","court.php?op=news");
			addnav("F?Aktuelle Fälle","court.php?op=cases");
			//addnav("Kopfgeldliste","court.php?op=listh");
			addnav("B?Schwarzes Brett","court.php?op=board");
			addnav("Hinterzimmer","court.php?op=judgesdisc");
			addnav("OOC Diskussionsraum","court.php?op=judgesooc");
			if ($session['user']['profession']==PROF_JUDGE_HEAD || $access_control->su_check(access_control::SU_RIGHT_DEBUG))
			 {
       addnav("Massenmail","court.php?op=massmail");
       }
			addnav("Archiv");
			addnav("Urteile","court.php?op=archiv");
			addnav("J?Handbuch für Jungrichter","court.php?op=faq");
		}
		addnav("Zurück");
		addnav("a?Zum Rathaus","dorfamt.php");
		addnav("d?Zum Stadtzentrum","village.php");
		output("`n`n");
		$bool_showform = ($session['user']['profession']==PROF_JUDGE || $session['user']['profession']==PROF_JUDGE_HEAD || $session['user']['profession']==PROF_JUDGE_NEW || access_control::is_superuser());
		viewcommentary("court","Sprechen:",30,"spricht",false,$bool_showform);
		break;
	}
}

page_footer();
?>
