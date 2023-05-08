<?php
/*
Make by Deathbringer
// 29-09-2004 September
// E-Mail: shimmert@web.de
// Copyright 2004 by Deathbringer
//
// Last Fix on 29-09-2004 September
// Variable Version 0.5a

UPDATE by Piercy
// 12-08-2005
// E-Mail: piercy@lotgd-world.de
// Many fixed and something added

10.5.07: Komplettüberarbeitung und Anpassung für Atrahor by Salator (salator@gmx.de)

Installation:
    
füge folgende Tables hinzu:
ALTER TABLE accounts ADD prangerdays int(11) unsigned NOT null default '0';
ALTER TABLE accounts ADD prangermod int(4) unsigned NOT null default '0';		->DS-Edition: in Gruppenrichtlinien eingefügt
INSERT INTO settings VALUES ('prangerfrucht', '0');

öffne login.php
finde
@file(getsetting("logdnetserver","http://lotgd.net/")."logdnet.php?addy=".URLEncode(getsetting("server_address","http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])))."&desc=".URLEncode(getsetting("serverdesc","Another LoGD Server"))."&version=".URLEncode(GAME_VERSION)."");
füge danach ein:
if($session['user'][prangerdays]>0){
redirect("pranger.php");
    }

öffne user.php
finde
"superuser"=>"Superuser,enum,0,Standard Spieltage pro Kalendertag,1,Unbegrenzt Spieltage pro Kalendertag,2,Kreaturen und Spott administrieren,3,User administrieren",
füge danach ein
"prangermod"=>"Dorfältester,enum,0,Nein,1,Ja",
	DS-Edition: lib/security.lib.php

öffne village.php
finde
if ($session['user']['alive']){ }else{
        redirect("shades.php");
füge danach ein
if($session['user'][prangerdays]>0){
    redirect("pranger.php");
}

öffne newday.php
setze
if ($session['user'][prangerdays]>0){
     $session['user'][prangerdays]--;
}    

mache noch die navigation irgendwo hin

und es müßte klappen.

einiges noch vorweg Mods stellt man in der Admin grotte unter den user einstellungen ein.Die können dann die weiteren einstellungen vornehmen.
*/    
require_once "common.php";
checkday();
page_header("Der Pranger");
addcommentary();

if ($_GET['op']=='') //changed by Piercy START
{                
	
 // if ($session['user']['prangerdays']==0) { $session['user']['location']=0; }
  if ($session['user']['prangerdays']>0) 
	{
		$session['user']['location'] = USER_LOC_PRISON;
		output('`c`b`SD`Te`(r `)Pran`(g`Te`Sr`b`c`n`n
		`)Dir werden folgende Verbrechen zur Last gelegt:`n`n
		`^'.stripslashes($session['user']['pqtemp']).'`n`n
		`)Du befindest dich für `$'.$session['user']['prangerdays'].'`) Tage am Pranger, hier gibts nichts zu tun.`n`n
		Es gibt keine Chance zu fliehen. Du kannst hier nur schön brav deine Strafe absitzen.');    
		addnav('Bleib weiter da hängen','login.php?op=logout&loc='.USER_LOC_PRISON,true);
	}       
//anzeige wer dort ist
	else
	{        
		$sql = "SELECT count(*) AS c FROM accounts WHERE prangerdays>0";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$jailedplayers = $row['c'];
		if($access_control->su_check(access_control::SU_RIGHT_PRANGERMOD) || $access_control->su_check(access_control::SU_RIGHT_DEBUG)) 
		{
			$bool_prangermod=true;
		}
		//story
		output('`c`b`SD`Te`(r `)Pran`(g`Te`Sr`b`c
		`SA`Tu`(f `)dem matschigen Richtplatz steht ein massiver Eichenstamm von etwa zwei Meter neunzig Größe. Aus seinem Holz ragen schwere Eisenketten, an deren Enden dicke Ringe befestigt sind.`n
		Die Ringe sind mit Schlössern versehen, um böse Kriminelle und fiese Schufte an den schweren Eichenstamm zu ketten.`n
		Im Mittelteil des Stammes befindet sich eine weitere, wesentlich breitere Kette, die zum Fixieren eines Körpers dient und von einer Seite quer über den Stamm zur zweiten Seite verläuft und ebenfalls mit einem Schloss versehen ist. Am Boden halten erneut  Ketten mit Eisenringen die Beine des Gefangenen still.`n
		Der ganze Pranger ist übersäht mit den faulenden Überresten von Eiern und Tomaten, welche von vielerlei Getier angefressen wurden. Rund um den Pranger liegen auch noch einige Steine. Eine von vielen Arten mit denen das Volk dem Sünder seine Meinung mitteilt und seiner Wut Luft macht.`n`n
		Wer immer hier hängen mag hat sich schwerster Verbrechen schuldig gemacht; durch eine auffällige Aufschrift auf der Kleidung oder auch nur mit einem kleinen Holzschild werden sie dem Volk mitgeteilt...`n`n
		Du siehst, dass '.$jailedplayers.' Halunken an dem Pranger angekettet s`(i`Tn`Sd.`n`n');
		$sql = "SELECT acctid,name,level,prangerdays,pqtemp FROM accounts WHERE prangerdays>0";
		$result = db_query($sql);
		if (db_num_rows($result)>0)
		{
			output('Spieler am Pranger:`n');
			output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
			output("<tr class='trhead'><td><b>Gefangener</b></td><td><b>Level</b></td><td><b>Tage am Pranger</b></td>");
			if($bool_prangermod) output('<td>Aktionen</td>');
			output("</tr>",true);
			for ($i=0;$i<db_num_rows($result);$i++)
			{
				$row = db_fetch_assoc($result);
				output("<tr class='".($i%2?"trdark":"trlight")."'>
				<td>`&".$row['name']."</td>
				<td>`&".$row['level']."</td>
				<td>`&".$row['prangerdays']."</td>",true);
				if($bool_prangermod)
				{
					output("<td><a href=pranger.php?op=rausholen&player=".$row['acctid'].">Aufheben</a></td>");
          addnav("","pranger.php?op=rausholen&player=".$row['acctid']);
        }  
					output("</tr><tr class='".($i%2?"trdark":"trlight")."'><td colspan=4>".stripslashes($row['pqtemp'])."</td>");
									
				output('</tr>');
			}
			output('</table>',true);
			$frucht = getsetting ("prangerfrucht",0);
			if($frucht>0)
			{
				output('Es liegen noch `s'.$frucht.' `)Gegenstände auf dem Platz herum die du werfen kannst.`n`n');
				addnav('Der Pranger');
				addnav('Angeprangte bewerfen','pranger.php?op=wurflist');
			}
		}
		else //Wenn keiner dranhängt eine fiese Falle *g*
		{
			addnav('Pranger untersuchen','pranger.php?op=examine1');
		}
		output('Die Leute stehen auf dem Platz und schreien:`n');
		addnav('Zurück zum Richtplatz','dorftor.php?op=richtplatz');
		if($bool_prangermod)
		{
			addnav('Mod-Aktionen');
			addnav('P?Leute an den Pranger binden','pranger.php?op=bind');
			addnav('Früchteverwaltung','pranger.php?op=fruechte');
		}        
	}
	if($session['user']['prangerdays']>0)
	{
		viewcommentary('pranger','Niemand wird dich hören.',20,'verzweifelt');
		addnav('Aktualisieren','pranger.php'); //Zuhören funktioniert nicht mit ausgeblendeter Eingabezeile
	}
	else
	{
		viewcommentary('pranger','Schrei\' dir deinen Frust raus',20,'schreit');
	}
}//changed by Piercy END

elseif ($_GET['op']=='bind') //changed by Piercy START Leute an den Pranger bringen Eingabeformular
{
	output("`c`bWelcher Schurke muss büßen?`b
	<form action='pranger.php?op=search' method='POST'>Suche Leute die angebunden werden sollen:`n <input name='q' id='q'>`n<input type='submit' class='button'></form>",true);
	JS::Focus('q');
	addnav('','pranger.php?op=search');
	addnav('P?Zurück zum Pranger','pranger.php');
}//changed by Piercy END

elseif ($_GET['op']=='search') //Liste passender user
{
	$search=str_create_search_string($_POST['q']);
	$sql = "SELECT acctid,login,name,level,prangerdays FROM accounts WHERE name LIKE '$search' ORDER BY login DESC LIMIT 100";
	$result = db_query($sql);
	if (db_num_rows($result)>=100)
	{
		output('`$Es wurden über 100 Spieler gefunden! Bitte formuliere deine Anfrage genauer.`0`n');
	}
	if (db_num_rows($result)<=0)
	{
		output('`$Keine Ergebnisse gefunden`0');
	}
	else
	{
		output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
		output("<tr class='trhead'><td><b>Level</b></td><td><b>Name</b></td><td><b>Aktion</b></td></tr>",true);
		$rn=0;
		for ($i=0;$i<db_num_rows($result);$i++)
		{
			$row=db_fetch_assoc($result);
			output("<tr class='".($rn%2?"trlight":"trdark")."'>
			<td>".$row['level']."</td>
			<td>".$row['name']."</td>
			<td>",true);
			if($row['prangerdays']>0)
			{
				output("<a href=pranger.php?op=rausholen&player=".$row['acctid'].">`2Aufheben`0</a>");
				addnav('','pranger.php?op=rausholen&player='.$row['acctid']);
			}
			else
			{
				output("<a href='pranger.php?op=einweisen&player=".$row['acctid']."'>Anbinden</a>",true);        
				addnav('','pranger.php?op=einweisen&player='.$row['acctid']);
			}
			output("</td></tr>",true);
		}
		output("</table>",true);
	}
	addnav('P?Zurück zum Pranger','pranger.php');
	addnav('Zurück zum Stadtzentrum','village.php');
}

elseif($_GET['op']=='einweisen') //changed by Piercy START abfrage zum anprangern
{
	$player=(int)$_GET['player'];
	$prangerdays=$_POST['prangerdays'];
	if($player != '')
	{
		if($_POST['ok']=='1')
		{
			$wort= ($prangerdays==1?'Tag':'Tage');
			$sql='SELECT acctid,sex,name,prangerdays,login,level FROM accounts WHERE acctid ='.$player;
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			systemmail($player,'`^Angeprangert! Von`0',$session['user']['name'].' hat dich an den Pranger gekettet!`nDu bist nun für '.$prangerdays.' '.$wort.' angekettet.`n`nFolgende Verbrechen werden dir zur Last gelegt:`n`6'.$_POST['pranger_reason'],$session['user']['acctid']);
			addnav('Zurück zum Stadtzentrum','village.php');
			addnews("`^".$row['name']."`% wurde an einem dicken Eichenstamm für $prangerdays $wort angekettet.");
			user_update(
				array
				(
					'prangerdays'=>$prangerdays,
					'pqtemp'=>db_real_escape_string($_POST['pranger_reason']),
				),
				$row['acctid']
			);
		}
		else
		{
			output("Gib an, wieviel Spieltage derjenige büßen muss:");
			output("<form action='pranger.php?op=einweisen&player=$player' method='POST'>");
			output("<SELECT name='prangerdays'>");
			for($i=1;$i<11;$i++)
			{
				rawoutput("<OPTION value='$i'>$i</OPTION>"); //keine weitere bedeutung.. nur schreibfaul ;)
			}
			output("</SELECT> Tage");
			output('`n`nGib eine Begründung ein:`n<textarea class=\'input\' cols=\'50\' rows=\'10\' name=\'pranger_reason\'></textarea>`n`n');
			output("<input type='hidden' name='player' value='$player'>");
			output("<input type='hidden' name='ok' value='1'>");
			output("<input type='submit'class='button' value='Anketten'></form>");
			addnav("","pranger.php?op=einweisen&player=$player");
			addnav('Doch nicht','pranger.php');
		}
	}
}//changed by Piercy END

elseif($_GET['op']=='examine1')
{
    output('`c`b`SD`Te`(r `)Pran`(g`Te`Sr`b`c
		`)Du siehst, dass im Augenblick niemand hier angebunden ist. Das wäre die perfekte Gelegenheit, dir den Pranger einmal genauer anzusehen. Schon willst du dich ihm nähern, als du den Kerkermeister bemerkst, der mit einem nicht gerade vertrauenerweckenden Grinsen genau auf dich zukommt. Vielleicht ist es doch keine so gute Idee, wer weiß, ob er nicht etwas dagegen hat. Unschlüssig bleibst du einen Moment stehen, noch kannst du einfach so tun, als wäre nichts gewesen und den Ort wieder verlassen.`n`nWillst du `iwirklich`i näher herangehen?`n`n');
    addnav('Aber sicher!','pranger.php?op=examine');
    addnav('Lieber doch nicht...','pranger.php');
}

elseif($_GET['op']=='examine') //sich selbst anketten lassen
{
	output('`c`b`SD`Te`(r `)Pran`(g`Te`Sr`b`c
		`)Du gehst zu dem leeren Pranger und schaust ihn dir genau an. Just in diesem Moment kommt der Kerkermeister bei dir an, welcher dir bereitwillig erklärt wie man einen Sünder an den Pfahl kettet.`n
`i`s"Also zuerst legt man die oberen zwei Eisenschellen um die Arme, während der Delinquient von zwei starken Stadtwachen festgehalten wird, etwa so:"`)`i`nDer Kerkermeister demonstriert es dir und legt deine Arme in die Schellen.`n
`s`i"Dann legt man zwei Schellen um die Beine und die besonders stabile Kette um den Bauch. Seht Ihr, das ist ganz einfach."`i`)`nEhe du dich versiehst bist du gefangen.`n`i`s"Nun noch festziehen und die dicken Schlösser dran, fertig."`i`)`n
	Du spürst am eigenen Leib dass man sich hier wirklich nicht rühren kann. Nun würdest du gerne wieder losgebunden werden, doch der Kerkermeister geht fies grinsend seiner Wege.`n`n
	`&Immerhin ist das eine Erfahrung die man nicht alle Tage macht. Du bekommst 300 Erfahrungspunkte.`n`n');
	viewcommentary('pranger','Schrei\' um Hilfe',20,'heult');
	$session['user']['prangerdays']=1;
	$session['user']['pqtemp']='`#'.$session['user']['name'].'`# war zu neugierig.';
	$session['user']['experience']+=300;
	addnews("`^".$session['user']['name']."`% wurde an einem dicken Eichenstamm für 1 Tag angekettet.");
	addnav('Na toll...','pranger.php');
}

elseif($_GET['op']=='rausholen') //abfrage zum lösen vom Pranger
{
	$player=(int)$_GET['player'];
	if($player != "")
	{
		$sql="SELECT acctid, name,prangerdays,login, level FROM accounts WHERE acctid =".$player;
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		
		user_update(
			array
			(
				'prangerdays'=>0,
				'location'=>0
			),
			$row['acctid']
		);
		
		systemmail($player,"`^Losgebunden! Von`0",$session['user']['name']." hat dich von den Ketten befreit!`nDu bist endlich wieder frei!",$session['user']['acctid']);
		redirect('pranger.php');
	}     
}

elseif($_GET['op']=='fruechte') //changed by Piercy START
{
	$fruit = getsetting('prangerfrucht',0);
	output('`b`cGegenstände hinzufügen`b');
	rawoutput("<form action='pranger.php?op=gegenstaende' method='POST'>");
	output('Es sind noch '.$fruit.' Gegenstände vorhanden.`nWieviele Gegenstände möchtest Du hinzufügen?`n');
	rawoutput("<input name='fruit' value='0' size='4'>");
	rawoutput("<input type='submit'class='button' value='Hinzufügen'></form>");
	addnav('','pranger.php?op=gegenstaende');
	addnav('Keine Änderung','pranger.php');
}//changed by Piercy END
        
elseif($_GET['op']=='gegenstaende') //changed by Piercy START
{
	$fruit = abs((int)$_POST['fruit']);
	savesetting('prangerfrucht',getsetting('prangerfrucht',0)+$fruit);
	redirect('pranger.php');
}//changed by Piercy END

elseif($_GET['op']=='wurflist') //added by Piercy START
{
	$sql='SELECT acctid, name, prangerdays FROM accounts WHERE prangerdays>0';
	$result=db_query($sql);
	output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
	output("<tr class='trhead'><td><b>Name</b></td><td><b>Werfen</b></td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++)
	{
		$row = db_fetch_assoc($result);
		output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);        
		output("`&".$row['name']."`n");
		output("</td><td>",true);
		output("<a href='pranger.php?op=steine&player=".$row['acctid']."'>Steine</a> | ");
		output("<a href='pranger.php?op=eier&player=".$row['acctid']."'>Eier</a> | ");
		output("<a href='pranger.php?op=tomate&player=".$row['acctid']."'>Tomaten</a>");
		output("</td></tr>",true);
		addnav('','pranger.php?op=steine&player='.$row['acctid']);
		addnav('','pranger.php?op=eier&player='.$row['acctid']);
		addnav('','pranger.php?op=tomate&player='.$row['acctid']);
	}
	output("</table>",true);
	output("`n`n");
	output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>
	<tr class='trhead'><td><b>Gegenstand</b></td><td><b>Kosten</b></td></tr>
	<tr class='trlight'><td>Steine</td><td>100 Gold</td></tr>
	<tr class='trdark'><td>Eier</td><td>50 Gold</td></tr>
	<tr class='trlight'><td>Tomaten</td><td>20 Gold</td></tr>
	</table>",true);
	addnav('Nichts werfen','pranger.php');
}// END Wurfliste

elseif ($_GET['op']=="steine") //changed by Piercy START
{
	$player=intval($_GET['player']);
	$sql="SELECT name,sex,maxhitpoints FROM accounts WHERE acctid=$player";
	$result=db_query($sql);
	$row=db_fetch_assoc($result);
	$name=$row['name'];
	if ($session['user']['gold']< 100)
	{
		output("`0Du kannst es dir nicht leisten, $name`0 zu bewerfen. Komm wieder wenn du genug Gold hast!");
		addnav('Zurück','pranger.php');
	}
	else if (getsetting('prangerfrucht',0)<1)
	{
		output('Es liegen keine Gegenstände mehr auf dem Platz.');
		addnav('Zurück','pranger.php');
	}
	else
	{    
		$session['user']['gold']-=100;
		savesetting ('prangerfrucht' ,getsetting ('prangerfrucht',0)- 1);
		switch (e_rand(1,4)) 
		{
			case 1:
        		insertcommentary($session['user']['acctid'],': `&wirft einen Stein gegen `0'.$name.'`&s Kopf und trifft schmerzhaft.','pranger');
			break;
			case 2:
        		insertcommentary($session['user']['acctid'],': `&wirft einige Steine daneben, trifft aber dann `0'.$name.'`& umso härter.','pranger');
			break;       
			case 3:
        		insertcommentary($session['user']['acctid'],': `&ist zu dumm um `0'.$name.'`& zu treffen.','pranger');
			break;
			default:
        		insertcommentary($session['user']['acctid'],': `&wird selbst von einem dicken Stein getroffen und blutet.','pranger');
			break;
		}
		redirect('pranger.php');
	}     
}// END Steine

elseif ($_GET['op']=="eier") //changed by Piercy START
{
	$player=intval($_GET['player']);
	$sql="SELECT name,sex FROM accounts WHERE acctid=$player";
	$result=db_query($sql);
	$row=db_fetch_assoc($result);
	$name=$row['name'];
	if ($session['user']['gold']< 50)
	{
		output("`0Du kannst es dir nicht leisten, $name`0 zu bewerfen. Komm wieder wenn du genug Gold hast!");
		addnav('Zurück','pranger.php');
	}
	else if (getsetting('prangerfrucht',0)<1)
	{
		output('Es liegen keine Gegenstände mehr auf dem Platz.');
		addnav('Zurück','pranger.php');
	}
	else
	{
		$session['user']['gold']-=50;
		savesetting ('prangerfrucht' ,getsetting ('prangerfrucht',0)- 1);
		switch (e_rand(1,4))
		{
			case 1:
        		insertcommentary($session['user']['acctid'],': `&klatscht `0'.$name.'`& ein stinkendes Ei an den Kopf.','pranger');
			break;
			case 2:
        		insertcommentary($session['user']['acctid'],': `&wirft `0'.$name.'`& ein faules Ei zu und lässt '.($row['sex']?'sie':'ihn').' so richtig stinken.','pranger');
			break;
			case 3: 
		        insertcommentary($session['user']['acctid'],': `&ist einfach zu blöd `0'.$name.'`& zu treffen.','pranger');
			break;
			default:
        		insertcommentary($session['user']['acctid'],': `&steht im Weg und wird selbst von mehreren Eiern getroffen.','pranger');
				break;
		}
		redirect('pranger.php');
	}  
}// END Eier
  
elseif ($_GET['op']=="tomate") //changed by Piercy START
{
	$player=intval($_GET['player']);
	$sql="SELECT name,sex FROM accounts WHERE acctid=$player";
	$result=db_query($sql);
	$row=db_fetch_assoc($result);
	$name=$row['name'];
	if ($session['user']['gold']< 20)
	{
		output("`0Du kannst es dir nicht leisten, $name`0 zu bewerfen. Komm wieder wenn du genug Gold hast!");
		addnav('Zurück','pranger.php');
	}
	else if (getsetting('prangerfrucht',0)<1)
	{
		output('Es liegen keine Gegenstände mehr auf dem Platz.');
		addnav('Zurück','pranger.php');
	}
	else
	{    
		$session['user']['gold']-=20;
		savesetting ('prangerfrucht' ,getsetting ('prangerfrucht',0)- 1);                             
		switch (e_rand(1,4)) 
		{
			case 1:
        		insertcommentary($session['user']['acctid'],': `&haut `0'.$name.'`& mit einer noch rohen Tomate die Birne ein.','pranger');
			break;
			case 2:
        		insertcommentary($session['user']['acctid'],': `&lässt eine gammelige Tomate zielsicher in `0'.$name.'`&s Gesicht gleiten','pranger');
			break;
			case 3:
        		insertcommentary($session['user']['acctid'],': `&wirft `0'.$name.'`&  mit stinkenden, angefaulten Tomaten schön farbig.','pranger');
			break;
			default:
        		insertcommentary($session['user']['acctid'],': `&bekommt selbst eine vergammelte Tomate an den Kopf, als '.($session['user']['sex']?'sie':'er').' eine aufheben will.','pranger');
			break;
		}    
		redirect('pranger.php');        
	} 
}// END Tomate

page_footer();    
?>
