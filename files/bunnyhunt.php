<?php
// Flauschihasenjagd
// by Maris (Maraxxus@gmx.de)
// 07.10.2008: Überarbeitung und Marker-Erweiterung by Salator

require_once 'common.php';

page_header('Flauschihasenjagd');
output('`c`b`&Flauschihasenjagd V0.8`0`b`c`n');

// Größe des Brettes (muss quadratisch sein)
$boardsize = 100;

// Anzahl der Hasen
$bunnies = 10;

// Anzahl der Netze
$nets = 22;

if ($_GET['op']=='new')
{
	if ($session['user']['turns']>0)
	{
		// Leeres Brett füllen
		for ($i=1; $i<=$boardsize; $i++)
		{
			$board['hidden'][$i]=-1;
		}
		for ($i=1; $i<=$bunnies; $i++)
		{
			$board['hidden'][$i]=100;
		}

		for ($i=1; $i<=200; $i++)
		{
			$pos1=e_rand(1,$boardsize);
			$pos2=e_rand(1,$boardsize);
			$copy = $board['hidden'][$pos1];
			$board['hidden'][$pos1]=$board['hidden'][$pos2];
			$board['hidden'][$pos2]=$copy;
		}

		// Brett aus Sicht des Spielers
		for ($i=1; $i<=$boardsize; $i++)
		{
			$board['user'][$i]=-1;
		}

		$board['caught'] = 0;
		$board['nets'] = $nets;
		$session['user']['pqtemp']=utf8_serialize($board);
		$_GET['op']='play';
	}
	else
	{
		output('`4Du bist schon zu erschöpft, um heute noch irgendwas zu jagen!
		`nVersuch es morgen nochmal.`n`0');
		addnav('Zurück','stables.php');
	}
}

if ($_GET['op']=='play')
{
	$board=utf8_unserialize($session['user']['pqtemp']);
	$pick=$_GET['pick'];
	$row = intval($_GET['row']);
	$col = intval($_GET['col']);
	$boardcols = sqrt($boardsize);
	
	if($row>0) //Zeile für Spieler markieren
	{
		$board['rows'][$row]=($board['rows'][$row]==1?0:1);
	}
	if($col>0) //Spalte für Spieler markieren
	{
		$board['cols'][$col]=($board['cols'][$col]==1?0:1);
	}
	if ($pick>0) //Wahl auswerten
	{
		$amount_of_bunnies=0;

		$x_v=floor(($pick-1)/$boardcols)+1;

		$y_v=$pick % $boardcols;
		$startx=(($x_v-1)*$boardcols)+1;

		if ($board['hidden'][$pick]==100)
		{
			$board['user'][$pick]=100;
			$board['caught']++;
		}
		else
		{
			// x-Richtung
			for ($i=$startx; $i<=$startx+$boardcols-1; $i++)
			{
				if ($board['hidden'][$i]==100)
				{
					$amount_of_bunnies++;
				}
			}

			// y-Richtung
			for ($i=$y_v; $i<=$boardsize; $i+=$boardcols)
			{
				if ($board['hidden'][$i]==100)
				{
					$amount_of_bunnies++;
				}
			}
			$board['user'][$pick]=$amount_of_bunnies;
			$board['nets']--;
		}
	}

	//Spielfeld zeichnen

	if ($board['nets']>0 && $board['caught']<$bunnies)
	{
		$str_output.='`&Wirf dein Netz!`0`n`n';
		addnav('Zurück zu den Ställen','bunnyhunt.php?op=leave');
	}
	else
	{
		$str_output.='`^Ende der Jagd!`0`n`n';
		if ($board['caught']<$bunnies)
		{
			addnav('Weiter','bunnyhunt.php?op=lose');
		}
		else //sollte eigentlich nicht auftreten
		{
			addnav('Weiter','bunnyhunt.php?op=win');
		}
	}
	$str_output.='<table border=0 align="center" bgcolor="#bb9955">
	<tr>';
	for ($i=1; $i<=$boardsize; $i++)
	{
		$col=$i%$boardcols;
		$row=ceil($i/$boardcols);
		if($col==0) $col=$boardcols;
		$tdclass=(($board['rows'][$row]==1 || $board['cols'][$col]==1)?'strike':'bunny');
		
		$str_output.='
		<td class="'.$tdclass.'">';
		if ($board['user'][$i]<0)
		{
			if($board['nets']>0)
			{
				$str_output.=create_lnk('<img src="./images/trans.gif" width=38 height=38 alt="" border="0">','bunnyhunt.php?op=play&pick='.$i);
			}
			else if($board['hidden'][$i]==100) //Auflösung bei Verloren
			{
				$str_output.='<img src="./images/bunny/bunnyleft.gif" width="40" height="40" border="0">';
			}
			else
			{
				$str_output.='&nbsp;';
			}
		}
		elseif($board['user'][$i]==100)
		{
			$str_output.='<img src="./images/bunny/bunny.gif" width="40" height="40" border="0">';
		}
		else
		{
			$str_output.=''.$board['user'][$i].'';
		}
		$str_output.='</td>';
		if ($i % $boardcols==0)
		{
			$str_output.='
			<td align="center" title="Zeile markieren">'.create_lnk('`$&radic;`0','bunnyhunt.php?op=play&row='.($i/($boardcols))).'</td>
			</tr>
			<tr>';
		}
	}
	for($i=1;$i<=$boardcols;$i++)
	{
		$str_output.='
		<td align="center" title="Spalte markieren">'.create_lnk('`$&radic;`0','bunnyhunt.php?op=play&col='.$i).'</td>';
	}
	$str_output.='<td>&nbsp;</td>
	</tr></table>
	`n`nVerbleibende Netze: '.$board['nets'].'
	`n`nGefangene Hasen:`n';
	for ($i=1; $i<=$board['caught']; $i++)
	{
		$str_output.='<img src="./images/bunny/bunny.gif"width="40" height="40" border="0">';
	}
	$str_output.='`n`n`&Übrig gebliebene Hasen: '.($bunnies-$board['caught']).'`0';
	output($str_output);
	
	$session['user']['pqtemp']=utf8_serialize($board);
}

else if ($_GET['op']=='win') //alle Hasen gefangen
{
	$caught=$_GET['caught'];
	output('<font size=+1>`^Du hast alle Hasen gefangen!
	`n`nMerick übergibt dir dankbar einen `@Futtersack`^ als Belohnung.
	`nDein Ergebnis wird zu deinen bisherigen Leistungen in der Ruhmeshalle addiert!
	`n`nDu verlierst einen Waldkampf.`0</font>');
	$session['user']['turns']--;
	$session['user']['pqtemp']='';
	$sql = 'UPDATE account_extra_info SET bunnyhunt=bunnyhunt+1, bunnies=bunnies+'.$bunnies.' WHERE acctid='.$session['user']['acctid'];
	db_query($sql);
	item_add($session['user']['acctid'],'fttrsack');
	addnav('Nochmal spielen','bunnyhunt.php?op=new');
	addnav('Rangliste','hof.php?op=bunny');
	addnav('Zurück zu den Ställen','stables.php');
}

else if ($_GET['op']=='lose') //keine Netze mehr und nicht alle Hasen gefangen
{
	$board=utf8_unserialize($session['user']['pqtemp']);
	$boardcols = sqrt($boardsize);
	
	output('<font size=+1>`4Du hast keine Netze mehr übrig und es nicht geschafft, alle Hasen zu erwischen!`0</font>`n',true);
	if ($board['caught']<5)
	{
		output('`4Wie jämmerlich, nichtmal 5 Hasen konntest du fangen! Damit verdienst du dir keinen Ruhm!
		`n`n`^Immerhin verlierst du für diese Vorstellung keinen Waldkampf.`n`n`0');
	}
	else
	{
		output('`@Aber immerhin hast du es geschafft '.$board['caught'].' Hasen zu fangen. Daher wird dein Ergebnis zu deinen bisherigen Leistungen in der Ruhmeshalle hinzugefügt!
		`n`n`4Du verlierst einen Waldkampf.`n`n`0');
		$sql = 'UPDATE account_extra_info SET bunnies=bunnies+'.$board['caught'].' WHERE acctid='.$session['user']['acctid'];
		db_query($sql);
		$session['user']['turns']--;
	}
	$session['user']['pqtemp']='';
	addnav('Nochmal spielen','bunnyhunt.php?op=new');
	addnav('Rangliste','hof.php?op=bunny');
	addnav('Zurück zu den Ställen','stables.php');
}

else if ($_GET['op']=='leave') //Spiel vorzeitig verlassen
{
	$session['user']['pqtemp']='';
	redirect('stables.php');
}

else if ($_GET['op']=='rules')
{
	addnav('Zurück','bunnyhunt.php');
	output('`c`b`&Anleitung für die Flauschihasenjagd `0`b`c
	`n`2Spielverlauf `Q
	`n
	`n`7Ziel des Spiels ist es, alle Hasen, die sich in den Feldern im hohen Gestrüpp <img src="./images/bunny/grass.jpg" width=25 height=25 alt=""> verstecken, durch das Werfen von Netzen einzufangen.
	`nDazu wählst du ein Feld deiner Wahl aus. Hast du einen Hasen erwischt wird dir auf diesem Feld das Bild eines Hasen <img src="./images/bunny/bunny.gif" width=25 height=25 alt=""> gezeigt, andernfalls entdeckst vielleicht Hasenspuren.
	`nDiese Spuren verraten dir, wieviele Hasen sich von dem Feld, das du gewählt hast, aus gesehen in allen Richtungen auf geradem Weg befinden. Die Anzahl der Hasen wird durch eine Zahl dargestellt.
	`nWenn sich z.B. in gerader Richtung vom gewählten Feld aus gesehen 2 Hasen links, einer oben, einer rechts und keiner unten befinden, so erscheint im gewählten Feld die Zahl 4.
	`n
	`nWenn du auf ein Häckchen am Rand des Feldes klickst, kannst du dir eine Spalte oder Zeile optisch markieren. Auf den Spielverlauf hat dies keinen Einfluss.
	`n
	`nWenn du einen Hasen erwischt hast, kannst du dein Netz weiter verwenden, andernfalls hat es sich hoffnungslos im Gestrüpp verfangen und ist verloren.
	`nDu hast nur `^'.$nets.' Netze`7 zur Verfügung, um alle `^'.$bunnies.' Hasen`7 zu fangen.
	Sollten dir die Netze ausgehen, so hast du verloren.
	Wenn du bei einer Niederlage weniger als 5 Hasen geschnappt hast, wird dein Ergebnis nicht für die Ruhmeshalle gewertet.
	In diesem Fall, oder wenn du aufgibst, verlierst du keinen Waldkampf.
	`n
	`nDas Spiel gewinnst du, wenn du alle Hasen gefangen hast.
	`n`n`0');
}

else //if ($_GET['op']=='')
{
	output('Du erkundigst dich bei Merick wie denn die Geschäfte so laufen.
	`n`nDer alte Zwerg beginnt nach ein paar Belanglosigkeiten frei von seinem Kummer zu berichten:
	"`6Aye, ich habe riesige Probleme mit Hasen!
	`nDie garstigen Viecher fressen mir das ganze Feld leer. Meine Tiere brauchen doch Futter!
	`nHilf mir die Hasen zu finden und einzufangen, dann will ich dich belohnen.`0"
	`n`n`^Um auf Hasenjagd zu gehen musst du mindestens einen Waldkampf übrig haben!`n`0');
	addnav('Flauschihasenjagd');
	addnav('H?Auf Hasenjagd gehen','bunnyhunt.php?op=new');
	addnav('Spielanleitung','bunnyhunt.php?op=rules');
	addnav('Rangliste','hof.php?op=bunny');
	addnav('Zurück');
	addnav('Zurück zu den Ställen','stables.php');
}
page_footer();
?>
