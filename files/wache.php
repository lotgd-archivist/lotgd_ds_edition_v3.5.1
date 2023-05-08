<?php
// Stadtwachen-Addon : Änderungen in houses.php, bio.php, pvp.php, inn.php, configuration.php, dorfamt.php
// Benötigt [profession] (shortint, unsigned) in ['user']
// by Maris (Maraxxus@gmx.de)
// 28.5.05: mod by tcb: Verlagert aus Dorfamt/Rathaus in eigene Datei, Bewerbungssystem, Schwarzes Brett verändert

$str_filename = basename(__FILE__);

require_once "common.php";
require_once(LIB_PATH.'profession.lib.php');

page_header("Die Stadtwache");

if (!isset($session)) exit();

switch($_GET['op']) {

	case 'bewerben':
	{
		output("`&Mit zittrigen Händen nimmst du die Klinke einer schweren Eichentür in die Hand und stößt sie auf. Ein alter Mann mit Backenbart sitzt hinter einem Schreibtisch und mustert dich eindringlich. \"`#Name! Rang!`&\" ruft er dir scharf entgegen. Nachdem du ihm gesagt hast was er wissen wollte kneift er die Augen zusammen.`n`n");
		$maxamount = getsetting("numberofguards",10);
		$reqdk = getsetting("guardreq",30);

		$sql = "SELECT profession FROM accounts WHERE profession=".PROF_GUARD_HEAD." OR profession=".PROF_GUARD;
		$result = db_query($sql);
		if ((db_num_rows($result)) < $maxamount) {

			if (($session['user']['profession']==PROF_GUARD_ENT) || ($session['user']['profession']==4)) {
				output("\"`# ".($session['user']['name'])."! So sehr ich Euren Wunsch nachempfinden kann, wieder dienen zu dürfen, muss ich Euch jedoch enttäuschen. Ihr hattet Eure Chance! Und nun verlasst mein Büro!`&\"");
			}
			else {
				output("\"`# ".($session['user']['name'])."!`# Ich hoffe, ihr wisst, worauf ihr euch hier einlasst? Der Dienst in der Stadtwache ist hart und entbehrungsreich. Und an euch werden besondere Forderungen gestellt: Ihr müsst sowohl ruhmreich als auch von höchstem Ansehen sein und in eurem Verhalten ein Vorbild!`&\"`n");

				if (($session['user']['dragonkills']) >= $reqdk) {
					if ($session['user']['reputation']>=50) {
						output ("\"`#Ich sehe, ich sehe... Ihr seid sowohl ruhmreich, wie auch von allerhöchstem Ansehen! Das ist gut, sehr gut. Meinetwegen könnt Ihr sofort anfangen. Doch wisset, dass Ihr als Stadtwache nicht nur Rechte, sondern auch Pflichten habt. Es ist Euch strengstens untersagt mit zwielichtigen Gesellen Kontakte zu knüpfen, auch nicht zur Täuschung! Ihr müsst Euch weiterhin mit Kopfgeldern zufrieden geben und dürft keine Beute an Euren Gegnern machen! Eurem Hauptmann habt Ihr Folge zu leisten! Sollte man Euch bei irgendeinem Verstoß oder irgendeiner Unehrenhaftigkeit erwischen, seid Ihr für lange Zeit Stadtwache gewesen! Sind wir uns da einig?`nAlso, wollt Ihr noch immer ?`&\"");
						addnav("Ja, Wache werden",$str_filename."?op=bewerben_ok");
					}
					else {
						output ("\"`#Ruhmreich seid ihr mehr, als es von Nöten wäre. Doch ich fürchte, dass euch die Leute nicht trauen würden, wenn ihr plötzlich in Uniform daher kämet. Arbeitet etwas an eurem Ansehen und versucht es dann noch einmal!`&\"");
					}
				}
				else {
					output ("\"`#Ihr seid zwar ruhmreich, doch wie es mir scheint nicht ruhmreich genug. Ihr solltet noch mehr Ruhm in Form einer Heldentat erlangen und es dann noch einmal versuchen!`&\"");
				}
			}	// Kein entlassener
		}	// Noch nicht zu viele
		else {
			output ("\"`#Es tut mir sehr leid, aber die Stadt hat zur Zeit genügend Stadtwachen. Versucht es doch später noch einmal!`&\"");
		}

		addnav("Zurück","dorfamt.php");
		break;
	}

	case 'bewerben_ok':
	{
		output("`&Du überreichst dem alten Mann dein Bewerbungsschreiben. Dieser verstaut es unter einem hohen Stapel Pergamenten und meint: \"Wir werden auf dich zurückkommen!\"");
		$session['user']['profession']=PROF_GUARD_NEW;
		$sql = "SELECT acctid FROM accounts WHERE profession=".PROF_GUARD_HEAD." ORDER BY loggedin DESC, RAND() LIMIT 1";
		$res = db_query($sql);
		if(db_num_rows($res)) {
			$w = db_fetch_assoc($res);
			systemmail($w['acctid'],"`&Neue Bewerbung!`0","`&".$session['user']['name']."`& hat sich für die Stadtwache beworben. Du solltest seine Bewerbung überprüfen und ihn gegegebenfalls einstellen.");
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

		$sql = "SELECT COUNT(*) AS anzahl FROM accounts WHERE (profession=".PROF_GUARD_HEAD." OR profession=".PROF_GUARD.")";
		$res = db_query($sql);
		$p = db_fetch_assoc($res);

		if($p['anzahl'] >= getsetting("numberofguards",10)) {
			output("Es gibt bereits ".$p['anzahl']." Wachen! Mehr sind zur Zeit nicht möglich.");
			addnav("Zurück",$str_filename."?op=listg");
		}
		else {

			user_update(
				array
				(
					'profession'=>PROF_GUARD
				),
				$pid
			);

			$sql = "SELECT name FROM accounts WHERE acctid=".$pid;
			$res = db_query($sql);
			$p = db_fetch_assoc($res);

			systemmail($pid,"Du wurdest aufgenommen!",$session['user']['name']."`& hat deine Bewerbung zur Aufnahme in die Stadtwache angenommen. Damit bist du vom heutigen Tage an offiziell Hüter für Recht und Ordnung!");

			$sql = "INSERT INTO news SET newstext = '".db_real_escape_string($p['name'])." `&wurde heute offiziell in die ehrenvolle Gemeinschaft der Stadtwachen aufgenommen!',newsdate=NOW(),accountid=".$pid;
			db_query($sql);

			addhistory('`2Aufnahme in die Stadtwache',1,$pid);

			addnav("Willkommen!",$str_filename."?op=listg");

			output("Die neue Stadtwache ist jetzt aufgenommen!");
		}
		break;
	}

	case 'abl':
	{
		$pid = (int)$_GET['id'];

		if($_POST['message']!='')
		{
			user_update(
				array
				(
					'profession'=>0
				),
				$pid
			);
			systemmail($pid,"Deine Bewerbung wurde abgelehnt!",$_POST['message']);
			output('Eine weitere Bewerbung findet ihren Platz in Ablage P.`n`n');
		}
		else
		{
			output('<form action="wache.php?op=abl&id='.$pid.'" method="post">
			Dem Bewerber wird dieser Bescheid zugesandt:
			`n`n<textarea name="message" class="input" cols=70 rows=4>'.$profs[$session['user']['profession']][$session['user']['sex']].' '.$session['user']['login'].' hat deine Bewerbung zur Aufnahme in die Stadtwache abgelehnt.</textarea>
			`n<input type="submit" id="submit" class="button" value="Mitteilung senden">
			</form>`n');
			addnav('',$str_filename.'?op=abl&id='.$pid);
		}
		addnav("Zurück",$str_filename."?op=listg");
		break;
	}

	case 'entlassen':
	{
		$pid = (int)$_GET['id'];

		user_update(
			array
			(
				'profession'=>0
			),
			$pid
		);

		$sql = "SELECT name FROM accounts WHERE acctid=".$pid;
		$res = db_query($sql);
		$p = db_fetch_assoc($res);

		systemmail($pid,"Du wurdest entlassen!",$session['user']['name']."`& hat dich aus der Stadtwache entlassen!");

		$sql = "INSERT INTO news SET newstext = '".db_real_escape_string($p['name'])." `&wurde heute aus der ehrenvollen Gemeinschaft der Stadtwachen entlassen!',newsdate=NOW(),accountid=".$pid;
		db_query($sql);

		addhistory('`$Entlassung aus der Stadtwache',1,$pid);

		addnav("Weiter",$str_filename."?op=listg");

		output("Die Wache wurde entlassen!");
		break;
	}

	case 'leave':
	{
		output ("`&Mit zitternden Knien betrittst du das Zimmer, in der der ältere Herr mit dem Backenbart wie gewohnt hinter seinem Schreibtisch sitzt. Als du eintrittst und ihm Meldung machst bittet er dich Platz zu nehmen und schau dich erwartungsvoll an.`nWillst du wirklich die Stadtwache verlassen?");
		addnav("Ja, austreten!",$str_filename."?op=leave_ok");
		addnav("NEIN. Dabei bleiben","dorfamt.php");
		break;
	}

	case 'leave_ok':
	{
		output ("`&Du bittest um deine Entlassung und der ältere Herr erledigt sichtlich schweren Herzens alle Formalitäten \"`#Wirklich schade, dass Ihr geht! Ich danke Euch vielmals für die treuen Dienste, die Ihr der Stadt geleistet habt und werde Euch nie vergessen! Beachtet, dass Eure Entlassung erst mit Beginn des morgigen Tages wirksam wird. Für heute seid Ihr jedoch beurlaubt.`&\"");
		addnews("".$session['user']['name']."`@ hat die Stadtwache verlassen. Die Gaunerwelt atmet auf.");

		addhistory('`2Austritt aus der Stadtwache');

		$session['user']['profession'] = 0;
		addnav("Zurück ins Zivilleben","dorfamt.php");
		break;
	}

	case 'ooc': //Pausenraum
	{
		if ($session['user']['profession']==PROF_GUARD || $session['user']['profession']==PROF_GUARD_HEAD || $access_control->su_check(access_control::SU_RIGHT_COMMENT))
		{
			addcommentary();
		}
		
		$sql = 'SELECT COUNT(`acctid`) AS Anzahl
						FROM `accounts` 
						WHERE `profession` = '.PROF_GUARD_HEAD.'';
		$res = db_query($sql);
		$p = db_fetch_assoc($res);
		
		// Auf Vorschlag von Severin einen neuen Text für den Pausenraum
		output(get_title('`AD`,e`Nr `(A`)u`7fenthaltsraum der Stadt`7w`)a`(c`Nh`,e`An').'
		`yEin wenig abseits vom Hauptraum des Quartiers, findet sich eine weitere Tür. Durch diese gelangt man in einen Raum, der vor allem durch zahlreiche Sitzgelegenheiten auffällt. Hier und da befindet sich noch ein kleiner Tisch, auf dem stets eine ganze Auswahl an Getränken steht, von Tee über einfaches Wasser bishin zu frischer Milch der hauseigenen `SM`mi`&l`mc`&h`mk`&u`Sh`y - alles, nur nichts Alkoholisches!`n`n');
		/*
		if($p['Anzahl']>1)
		{
			output(get_title('`2Der Aufenthaltsraum der Stadtwachen').'
			`yEin wenig abseits vom Hauptraum des Quartiers, findet sich eine weitere Tür. Durch diese gelangt man in einen Raum, der vor allem durch zahlreiche Sitzgelegenheiten auffällt. Hier und da befindet sich noch ein kleiner Tisch, auf dem stets eine ganze Auswahl an Getränken steht, von Tee über einfaches Wasser bishin zu frischer Milch der hauseigenen `SM`mi`&l`mc`&h`mk`&u`Sh`y - alles, nur nichts Alkoholisches!`n`n');
		}
		else
		{
			$sql = 'SELECT `name`
							FROM `accounts` 
							WHERE `profession` = '.PROF_GUARD_HEAD.'';
			$res = db_query($sql);
			$n = db_fetch_assoc($res);	
			output(get_title('`2Der Aufenthaltsraum der Stadtwachen').'
			`7Du ziehst dich zurück in das Hinterzimmer, wo du ungestört mit deinen Kollegen diskutieren kannst. Hier steht immer ein Kessel mit heißem Tee, den Hauptmann '.$n['name'].' `7für die erschöpften Recken bereitstellt. Mehrere Tische und Stühle laden zum Rasten ein.`n`n');
		}*/
		
		require_once(LIB_PATH.'board.lib.php');
		output('`0`c');
		$int_pollrights = (($session['user']['ddl_rank'] == PROF_DDL_COLONEL) ? 2 : 1);
		if(poll_view('guard_chief',$int_pollrights,$int_pollrights))
		{
			output('`n`^~~~~~~~~`0`n`n',true);
		}
		output('`c');
		
		addnav('Urteile',$str_filename.'?op=sentences');
		addnav('Schwarzes Brett',$str_filename.'?op=board');
		if($session['user']['profession'] == PROF_GUARD_HEAD
		|| $access_control->su_check(access_control::SU_RIGHT_DEV))
		{
			addnav ('f?Umfrage erstellen',$str_filename.'?op=poll&pollsection=chief');
		}
		//addnav("V?Letzte Vorfälle",$str_filename."?op=news");
		addnav('Handbuch für Wachen',$str_filename.'?op=faq');
		addnav('Zurück zum HQ',$str_filename.'?op=hq');
		viewcommentary('guardsooc','Sagen:',30,'sagt');
		break;
	}

	case 'board': //schwarzes Brett
	{
		require_once(LIB_PATH.'board.lib.php');
		output ("`&Du stellst dich vor das große Brett und schaust ob eine neue Mitteilung vorliegt.
		`n`tDu kannst eine Notiz hinterlassen oder entfernen.`n`n");
		if($_GET['board_action'] == "add") {
			board_add('wache');
			redirect($str_filename."?op=board");
		}
		else
		{
			board_view_form('Hinzufügen','');
			board_view('wache',2,'','',true,true,true);
		}

		addnav("Zurück",$str_filename."?op=hq");
		break;
	}

	case 'poll': //Umfrage erstellen
	{
		require_once(LIB_PATH.'board.lib.php');
		output(get_title('Umfragen der Stadtwache'));
		poll_add('guard_'.$_GET['pollsection'],100,1);
		if(!empty($session['polladderror'])) {
			if($session['polladderror'] == 'maxpolls')
			{
				output('`$An dieser Stelle findet bereits eine Umfrage statt! Entferne bitte zunächst diese, ehe du eine neue eröffnest.`n`n');
			}
		}
		else
		{
			redirect($str_filename.'?op=ooc');
		}

		if($_GET['pollsection'] == 'private')
		{
			output('`8Du möchtest also im Hinterzimmer des Hauptquartiers eine Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und an einer prominenten Stelle aufgehängt zu werden:`n`n');

		}
		else
		{
			output('`8Du möchtest also eine öffentliche Umfrage durchführen? So sei es denn, hier ist ein Pergament, das nur darauf wartet, von dir beschriftet und für alle gut sichtbar platziert zu werden:`n`n');
		}
		addnav('Zurück zum Pausenraum',$str_filename.'?op=ooc');

		poll_show_addform();
		break;
	}

	case 'massmail': // Massenmail (im wohnviertel by mikay)
	{
		$str_out .= get_title('Taubenschlag unter dem Dach des Hauptquartiers.`0');

		addnav('Abbrechen',$str_filename);

		$sql='SELECT acctid, name, login, profession
			FROM accounts
			WHERE profession='.PROF_GUARD.'
			OR profession='.PROF_GUARD_HEAD.'
			OR profession='.PROF_GUARD_NEW.'
			AND acctid!='.(int)$session['user']['acctid'].'
			ORDER BY profession DESC';
		$result=db_query($sql);
		$users=array();
		$keys=0;

		while($row=db_fetch_assoc($result))
		{
			$profs[0][0]='Zivilist';
			if($row['profession']!=$lastprofession) $residents.='`n`b'.$profs[$row['profession']][0].'`b`n';

			$residents.='<input type="checkbox" name="msg[]" value="'.$row['acctid'].'" id="inp5657" '.($row['profession']!=PROF_GUARD_NEW ? 'checked':'').'> '.$row['name'].'
			 '.JS::event('#inp5657','click','chk()').'
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
			$str_out .= form_header($str_filename.'?op=massmail')
			.$sendresult.'
			<table border="0" cellpadding="0" cellspacing="10">
				<tr>
					<td><b>Betreff:</b></td>
					<td><input type="text" name="title" id="title" value="">
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

	case 'listg': //Stadtwachen auflisten
	{
		$admin = ($session['user']['profession'] == 2 || $access_control->su_check(access_control::SU_RIGHT_DEBUG)) ? true : false;

		$sql = "SELECT name,acctid,dragonkills,login,level,profession,
			IF(".user_get_online().",'`@Online`0','`4Offline`0') AS loggedin
			FROM accounts
			WHERE profession=1 OR profession=2 OR profession=3 OR profession=5
			ORDER BY profession DESC, dragonkills DESC, level DESC";
		$result = db_query($sql);
		$str_out .= '`&Folgende Helden haben sich der Stadtwache angeschlossen:`n`n`0';
		$str_out .= '<table border="0" cellpadding="5" cellspacing="2" bgcolor="#999999">
		<tr class="trhead">
		<th>Name</th>
		<th>DK</th>
		<th>Funktion</th>
		<th>';
		if($admin)
		{
		$str_out .= 'Aktionen';
		}
		$str_out .= '</th>
		<th>Status</th>
		</tr>';
		$lst=0;
		$dks=0;
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$lst+=1;
			$dks+=$row['dragonkills'];
			$str_out .= '<tr class="'.($lst%2?'trlight':'trdark').'">
				<td>&nbsp;'.CRPChat::menulink($row).'`0&nbsp;</td>
				<td>&nbsp;'.$row['dragonkills'].'&nbsp;</td>
				<td>&nbsp;';
			if ($row['profession']==1) {
				$str_out .= '`#Stadtwache`0&nbsp;</td><td>';
				if($admin){
					$str_out .= '<a href="wache.php?op=entlassen&id='.$row['acctid'].'">Entlassen</a>';
					addnav('','wache.php?op=entlassen&id='.$row['acctid']);
				}
			}
			if ($row['profession']==PROF_GUARD_HEAD) {$str_out .= '`4Hauptmann`0&nbsp;</td><td>';}
			if ($row['profession']==PROF_GUARD_ENT) {$str_out .= '`6Entlassung läuft`0&nbsp;</td><td>';}
			if ($row['profession']==PROF_GUARD_NEW) {

				$str_out .= '`@Bittet um Aufnahme`0&nbsp;</td><td>';
				if($admin) {
					$str_out .= '<a href="wache.php?op=aufn&id='.$row['acctid'].'">Aufnehmen</a>`n';
					addnav("","wache.php?op=aufn&id=".$row['acctid']);
					$str_out .= '<a href="wache.php?op=abl&id='.$row['acctid'].'">Ablehnen</a>';
					addnav("","wache.php?op=abl&id=".$row['acctid']);
					}
				}
			$str_out .= '</td><td>&nbsp;'.$row['loggedin'].'&nbsp;</td></tr>';
		}
		db_free_result($result);
		$str_out .= '</table>';
		$str_out .= '<big>`n`@Gemeinsame Heldentaten der Stadtwache : `^'.$dks.'`n`n`0</big>';
		addnav('Zurück',$str_filename.'?op=hq');
		output($str_out);
		break;
	}

	case 'showg': //Stadtwachen auflisten für Normalsterbliche
	{
		$sql = "SELECT name,acctid,dragonkills,login,level,profession,
			IF(".user_get_online().",'`@Online`0','`4Offline`0') AS loggedin
			FROM accounts
			WHERE profession=1 OR profession=2
			ORDER BY profession DESC, dragonkills DESC, level DESC";
		$result = db_query($sql);
		$str_out.= '`&Folgende Helden haben sich der Stadtwache angeschlossen:`n`n`0
		<table border="0" cellpadding="5" cellspacing="2" bgcolor="#999999">
		<tr class="trhead">
		<th>Name</th>
		<th>DK</th>
		<th>Funktion</th>
		<th>Status</th>
		</tr>';
		$lst=0;
		$dks=0;
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$lst+=1;
			$dks+=$row['dragonkills'];
			$str_out.='<tr class="'.($lst%2?'trlight':'trdark').'">
			<td>&nbsp;'.CRPChat::menulink($row).'`0&nbsp;</td>
			<td>&nbsp;'.$row['dragonkills'].'&nbsp;</td>
			<td>&nbsp;'.($row['profession']==PROF_GUARD_HEAD?'`4Hauptmann':'`#Stadtwache').'`0&nbsp;</td>
			<td>&nbsp;'.$row['loggedin'].'&nbsp;</td>
			</tr>';
		}
		$str_out.="</table>";
		$str_out.="<big>`n`@Gemeinsame Heldentaten der Stadtwache : `^$dks`n`n`0</big>";
		output($str_out);
		addnav("Zurück","dorfamt.php");
		break;
	}

	case 'listh': //Kopfgeldliste entfällt
	{
		output("<span style='color: #9900FF'>",true);
		output ("`&Die Kopfgeldliste:`n`n");

		$sql = "SELECT name,acctid,location,bounty,laston,alive,house,loggedin,login,level,activated,restatlocation FROM accounts WHERE bounty>0
				ORDER BY bounty DESC";
		$result = db_query($sql);

		output("<table border='0' cellpadding='4' cellspacing='1' bgcolor='#999999'><tr class='trhead'><td>Kopfgeld</td><td>Level</td><td>Name</td><td>Ort</td><td>Lebt?</td></tr>",true);
		$lst=0;

		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$loggedin=user_get_online(0,$row);
			$lst+=1;
			output("<tr class='".($lst%2?"trlight":"trdark")."'><td>".($row['bounty'])."</td><td>".($row['level'])."</td><td><a href='bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."'>".$row['name']."</a>",true);
			addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
			output("</td><td>",true);
			
			switch($row['location'])
			{
				case USER_LOC_FIELDS:
					$loc .= $loggedin?'`#Online`0':'`3Die Felder`0';
					break;
				case USER_LOC_INN:
					$loc .= '`3Zimmer in Kneipe`0';
					break;
				case USER_LOC_HOUSE:
					$loc .= '`3Im Haus Nr '.$row['restatlocation'].'`0';
					break;
				default:
					$loc .= '`3'.get_location_name($row['location']).'`0';
			}
			output($loc);
			
			output("</td><td>",true);
			if ($row['alive']) { output("`@lebt`&",true);} else { output("`4tot`&",true);}
			output("</td></tr>",true);
		}
		addnav("Zurück",$str_filename."?op=hq");
		db_free_result($result);
		output("</table>",true);
		output("</span>",true);
		break;
	}

	case 'faq': //Handbuch für Jungrichter
	{
		output(get_extended_text('guard_policy'));
		addnav("Zurück",$str_filename."?op=hq");
		break;
	}

	case 'sentences': //Urteile
	{
		$str_out .= '`&Die Richter haben folgende Urteile verhängt:`n`n`0';

		$days = getsetting('pvpimmunity', 5);
		$exp = getsetting('pvpminexp', 1500);
		$sql = "SELECT a.acctid,a.bounty,sentence,restatlocation,location,loggedin,activated,laston,name,level,login,alive
				FROM account_extra_info aei
				LEFT JOIN accounts a ON a.acctid=aei.acctid
				WHERE sentence>0
				AND location<>".USER_LOC_PRISON."
				AND location<>".USER_LOC_VACATION."
				AND (a.age > ".$days." OR a.dragonkills > 0 OR a.pk > 0 OR a.experience > ".$exp.")
				ORDER BY sentence DESC,level DESC, alive DESC";
		$result = db_query($sql);

		$str_out .= '<table border="0" cellpadding="4" cellspacing="1" bgcolor="#999999">
		<tr class="trhead">
		<th>Strafe</th>
		<th>Level</th>
		<th>Name</th>
		<th>Ort</th>
		<th>Kopfgeld</th>
		</tr>';
		$lst=0;

		$count = db_num_rows($result);

		for ($i=0;$i<$count;$i++)
		{
			$row = db_fetch_assoc($result);

			$loggedin=user_get_online(0,$row);
			$lst+=1;
			$str_out .= '<tr class="'.($lst%2?'trlight':'trdark').'">
			<td>'.$row['sentence'].' Tage</td>
			<td align="center">'.$row['level'].'</td>
			<td>
			<a href="javascript:void(0);" onClick="'.popup('bio.php?id='.$row['acctid']).';return false;">'.$row['name'].'</a>';
			$str_out .= '</td>
			<td>';
			
			if($row['alive'] == 0) $str_out .= '`$TOT`0 und '.($loggedin? '`@online`0':'`3offline`0');
			else if ($row['location']==USER_LOC_FIELDS) $str_out .= ($loggedin? '`@online`0':'`3Die Felder`0');
			elseif ($row['location']==USER_LOC_INN) $str_out .= '`3Zimmer in Kneipe`0';
			elseif ($row['location']==USER_LOC_PRISON) $str_out .= '`TIm Kerker`0';
			elseif ($row['location']==USER_LOC_VACATION) $str_out .= '`&untergetaucht`0';
			elseif ($row['location']==USER_LOC_HOUSE){
				$loc = $row['restatlocation'];

				$sql="SELECT status FROM houses WHERE houseid=$loc ";
				$result3 = db_query($sql);
				$row3 = db_fetch_assoc($result3);
				$loc2= $row3['status'];
				if (($loc2<30) || ($loc2>39)){
					$str_out .= 'Haus Nr. '.$loc;
				}
				else{ // Versteck, Refugium etc..
					$str_out .= '`7untergetaucht';
				}
			}
			else 
			{
				$str_out .= '`3'.get_location_name($row['location']).'`0';
			}
			$str_out .= '`0</td>
			<td align="right">'.$row['bounty'].'</td>
			</tr>';
		}
		addnav('Zurück',$str_filename.'?op=hq');
		$str_out .= '</table>';
		if($count<=0)
		{
			$str_out .='<i>`0Ihr habt gute Arbeit geleistet, denn es gibt im Moment keine Verurteilten!</i>';
		}

		output($str_out);
		break;
	}

	case 'news': //gefilterte News, entfällt
	{
		$daydiff = ($_GET['daydiff']) ? $_GET['daydiff'] : 0;
		$min = $daydiff-1;

		$sql = "SELECT newstext,newsdate FROM news WHERE
					(newstext LIKE '%geflohen%' OR newstext LIKE '%einbruch%' OR newstext LIKE '%Zimmer in der Kneipe%' OR newstext LIKE '%in einem fairen Kampf in den Feldern%' OR newstext LIKE '%eine gerechte Strafe erhalten%')
					AND (DATEDIFF(NOW(),newsdate) <= ".$daydiff." AND DATEDIFF(NOW(),newsdate) > ".$min.")
					ORDER BY newsid DESC
					LIMIT 0,200";
		$res = db_query($sql);

		output("`&Die verdächtigen Taten von ".(($daydiff==0)?"heute":(($daydiff==1)?"gestern":"vor ".$daydiff." Tagen")).":`n");

		while($n = db_fetch_assoc($res)) {

			output('`n`n'.$n['newstext']);

		}

		addnav("Aktualisieren",$str_filename."?op=news");
		addnav("Heute",$str_filename."?op=news");
		addnav("Gestern",$str_filename."?op=news&daydiff=1");
		addnav("Vor 2 Tagen",$str_filename."?op=news&daydiff=2");
		addnav("Vor 3 Tagen",$str_filename."?op=news&daydiff=3");
		addnav("Zurück",$str_filename);
		break;
	}

	default: //Stadtwache Hauptquartier
	{
		if ($session['user']['profession']==PROF_GUARD || $session['user']['profession']==PROF_GUARD_HEAD || $access_control->su_check(access_control::SU_RIGHT_COMMENT))
		{
			addcommentary();
			output(get_title("`AD`,a`Ns `(H`)a`7u`eptquartier der Stadt`7w`)a`(c`Nh`,e`An").'
			<table border="0"><tr><td><img src="./images/stadtwachen_banner.png" width="120"></td><td>
			`AD`,u `Nb`(e`)t`7r`eittst vornehme Räumlichkeiten, die dir ein gewisses Gefühl von Ehrfurcht und auch Respekt vermitteln. An den Wänden hängen Schwerter und Trophäen. Ritterrüstungen säumen den holzvertäfelten Raum. Ein großer runder, edler Eichentisch steht genau in der Mitte des Hauptraumes. Umhänge und Rüstungsteile, achtlos über Stühle gehängt, kannst du aus den Augenwinkeln erkennen. Ein großer Kupferstich an der Stirnwand des Hauptraumes erinnert dich an deine Pflichten als Wächter dieser Stadt:
			`n`n`sEhre, Gerechtigkeit, Ritterlichkeit, Beständigkeit und Disziplin sollen den Wächter der Stadt '.getsetting('townname','Atrahor').' zu einem Symbol der Sicherheit für ihre Bürg`eer `7m`)a`(c`Nh`,e`An!`n`n`&</td></tr></table>`n');
			addnav("Rekrutierungsliste",$str_filename."?op=listg");
			//addnav("Kopfgeldliste",$str_filename."?op=listh");
			addnav("Urteile",$str_filename."?op=sentences");
			addnav("Schwarzes Brett",$str_filename."?op=board");
			if($session['user']['profession'] == PROF_GUARD_HEAD
			|| $access_control->su_check(access_control::SU_RIGHT_DEV))
			{
				addnav ('Massenmail',$str_filename.'?op=massmail');
			}
			addnav("Handbuch für Wachen",$str_filename."?op=faq");
			addnav("Pausenraum",$str_filename."?op=ooc");
			viewcommentary("guards","Melden:",30,"meldet");
		}
		else //Ansicht für Normalsterbliche, dürfte eigentlich nicht vorkommen
		{
			output('Als Zivilist fühlst du dich im Hauptquartier der Stadtwachen ein bisschen unwohl. Also siehst du zu dass du hier rauskommst.');
		}
		addnav("Zurück");
		addnav('a?zum Rathaus',"dorfamt.php");
		addnav('d?zum Stadtzentrum','village.php');
		break;
	}
}

page_footer();
?>