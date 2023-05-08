<?php
/*
* author: bibir (logd_bibir@email.de)
*      and Chaosmaker (webmaster@chaosonline.de)
*      for http://logd.chaosonline.de
*
* version: 1.2
*
*     a library with text from users to help other
*            a bit like faq
*
* details:
*  (15.11.04) start of idea
*  (15.01.05) project finished
*  (16.01.05) version 1.2: several minor bugfixes
*  (11.11.05) Talion: added feature for recommending books that are well done.
*  (10.07.06) Talion: newest contributions are shown in an extra section.
*  (30.11.06) Fossla (atrahor.de): books like the "Gesetzbuch" (book of laws) are shown without author
*/

require_once "common.php";

checkday();
addcommentary();
if(!isset($_GET['op'])) $_GET['op']="";

addnav('Bibliothek');

$sql = "SELECT count(*) AS anz FROM lib_books WHERE activated='1'";
$result = db_query($sql);
$books = db_fetch_assoc($result);
page_header("Drachen Bibliothek");
output("`c`b`IDrachen Bibliothek des gesammelten Wissens in ".($books['anz']==1?'einem Band':$books['anz'].' Bänden')."`0`b`c`n");

switch($_GET['op'])
{
	case "browse":
	{
		addnav("H?Zurück in die Halle","library.php");
		if($session['user']['alive'] && !$session['user']['imprisoned'])
		{
			addnav("Buch einreichen","library.php?op=offer");
		}
		output("`0Du ".(!$session['user']['alive'] ? 'schwebst' : 'gehst')." durch die Regalreihen, die fast die ganze Höhe des Gebäudes nutzen und siehst, dass alle Bücher ordentlich nach Themen einsortiert sind.`n
		Folgende Themen stehen derzeit zur Auswahl:`n`n");
		$sql = "SELECT t.*, COUNT(b.bookid) as anz FROM lib_themes t
		LEFT JOIN lib_books b ON b.themeid=t.themeid AND b.activated='1'
		GROUP BY themeid
		ORDER BY listorder ASC";
		$result = db_query($sql);
		output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'>
		<tr class='trhead'>
		<th>Thema</th>
		<th>Bücher</th>
		</tr>",true);
		$bgclass = '';
		addnav("Themen");
		while ($row = db_fetch_assoc($result))
		{
			$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
			if ($row['anz']>0)
			{
				output("<tr class='$bgclass'>
				<td>".create_lnk($row['theme'],'library.php?op=theme&id='.$row['themeid'],true,true,'',false,'',1)."</td>
				<td align='right'>".$row['anz']."</td>
				</tr>",true);
			}
			else
			{
				output("<tr class='$bgclass'>
				<td>".$row['theme']."`0</td>
				<td>kein Buch</td>
				</tr>",true);
			}
		}
		output("</table>",true);
		break;
	}

	case "theme":
	{
		addnav("H?Zurück in die Halle","library.php");
		//addnav("Andere Regale","library.php?op=browse");
		if($session['user']['alive']) {
			addnav("Buch einreichen","library.php?op=offer");
		}

		addnav("Themen");
		$sql = "SELECT themeid, theme, description FROM lib_themes ORDER BY listorder ASC";
		$result = db_query($sql);
		while ($row = db_fetch_assoc($result)) {
			if ($row['themeid']!=$_GET['id']) {
				addnav($row['theme'],"library.php?op=theme&id=".$row['themeid']);
			}
			else {
				addnav($row['theme'],'');
				$thistheme = $row['theme'];
				$thisdescription=$row['description'];
			}
		}

		output("`c`b".$thistheme."`0`b`c
		`n`c".$thisdescription."`0`c
		`n`0Zu diesem Thema stehen dir folgende Bücher zur Verfügung:`n`n");

		$sql = "SELECT title, bookid, acctid, author, recommended, show_author
		FROM lib_books
		WHERE themeid=".$_GET['id']."
		AND activated='1'
		ORDER BY listorder ASC";
		$result = db_query($sql);
		output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'>
		<tr class='trhead'>
		<th>Titel</th>
		<th>Autor</th>
		<th>Besonders Empfehlenswert?</th>
		</tr>",true);
		if (db_num_rows($result)==0)
		{
			output("<tr class='trdark'>
			<td colspan='3'>`0Es gibt leider bisher noch keine Bücher zu diesem Thema.</td>
			</tr>",true);
		}
		else
		{
			addnav('Bücher');
			$bgclass = '';
			while ($row = db_fetch_assoc($result))
			{
				$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
				output("<tr class='$bgclass'>
				<td>".create_lnk($row['title'].'`0','library.php?op=book&bookid='.$row['bookid'],true,true,'',false,strip_appoencode($row['title']))."</td>
				<td>".($row['show_author']!=0 ? $row['author'] : getsetting("lib_alternative_author","Allgemeine Ver&ouml;ffentlichung"))."`0</td>
				<td align='center'>".($row['recommended'] ? 'Ja' : ' - ')."`0</td></tr>",true);
			}
		}
		output("</table>",true);
		break;
	}

	case 'recommended':
	{
		addnav("H?Zurück in die Halle","library.php");

		output('`n`0Die folgenden Bücher werden von den Göttern als besonders empfehlenswert angesehen. Es lohnt sich also bestimmt, hier einen Blick hineinzuwerfen:`n`n');

		$sql = 'SELECT b.*,t.theme FROM lib_books b
		LEFT JOIN lib_themes t ON t.themeid=b.themeid
		WHERE recommended = 1 AND activated = "1"
		ORDER BY t.themeid ASC, t.listorder DESC, b.listorder DESC';

		$result = db_query($sql);
		output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'>
		<tr class='trhead'>
		<th>Titel</th>
		<th>Autor</th>
		</tr>",true);
		if (db_num_rows($result)==0) {
			output("<tr class='trdark'><td colspan='2'>`0Es gibt leider bisher noch keine empfohlenen Bücher.</td></tr>",true);
		}
		else
		{
			addnav('Bücher');
			$bgclass = '';
			$last_theme = 0;
			while ($row = db_fetch_assoc($result))
			{
				if($last_theme != $row['themeid'])
				{
					output('<tr class="trhead"><td colspan="2">`b'.$row['theme'].'`b</td></tr>',true);
					$last_theme = $row['themeid'];
				}

				$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
				output("<tr class='$bgclass'>
				<td>".create_lnk($row['title'].'`0','library.php?op=book&bookid='.$row['bookid'],true,true,'',false,strip_appoencode($row['title']))."</td>
				<td>".($row['show_author']!=0 ? $row['author'] : getsetting("lib_alternative_author","Allgemeine Ver&ouml;ffentlichung"))."`0</td>
				</tr>",true);
			}
		}
		output("</table>",true);

		break;
	}

	case 'new':
	{
		addnav("H?Zurück in die Halle","library.php");
		output('`n`0Diese Bücher wurden erst vor kurzem eingereicht, wie du an den fast neu wirkenden Einbänden erkennen kannst:`n`n');

		$sql = 'SELECT b.*,t.theme FROM lib_books b
		LEFT JOIN lib_themes t ON t.themeid=b.themeid
		WHERE activated = "1"
		ORDER BY b.bookid DESC, t.themeid ASC, t.listorder DESC, b.listorder DESC
		LIMIT 10';

		$result = db_query($sql);
		output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'>
		<tr class='trhead'>
		<th>Titel</th>
		<th>Autor</th>
		<th>Thema</th>
		</tr>",true);
		if (db_num_rows($result)==0)
		{
			output("<tr class='trdark'>
			<td colspan='3'>`0Es gibt leider bisher noch keine Bücher.</td>
			</tr>",true);
		}
		else
		{
			addnav('Bücher');
			$bgclass = '';
			while ($row = db_fetch_assoc($result))
			{
				$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
				output("<tr class='$bgclass'>
				<td>".create_lnk($row['title'].'`0','library.php?op=book&bookid='.$row['bookid'],true,true,'',false,strip_appoencode($row['title']))."</td>
				<td>".($row['show_author']!=0 ? $row['author'] : getsetting("lib_alternative_author","Allgemeine Ver&ouml;ffentlichung"))."`0</td>
				<td>".$row['theme']."`0</td></tr>",true);
			}
		}
		output("</table>",true);
		break;
	}

	case "book":
	{
		addnav("H?Zurück in die Halle","library.php");
		//addnav("Ein anderes Thema","library.php?op=browse");

		// Welche Ansicht zum Anzeigen des Textes?
		$bool_style = true;
		if(isset($_GET['style']))
		{
			$bool_style = (bool)$_GET['style'];
		}

		$sql = "SELECT t.theme, b.themeid, b.title, b.book, b.acctid, b.author, b.bookid, b.show_author FROM lib_books b
		LEFT JOIN lib_themes t USING(themeid)
		WHERE bookid=".(int)$_GET['bookid'];
		$result = db_query($sql);
		$row = db_fetch_assoc($result);

		// Views erhöhen
		if(!$session['bookview'.$row['bookid']])
		{
			$session['bookview'.$row['bookid']] = true;
			$sql = 'UPDATE lib_books SET views=views+1 WHERE bookid='.$row['bookid'];
			db_query($sql);
		}

		//addnav("R?Zurück ans Regal","library.php?op=theme&id=".$row['themeid']);
		addnav("Buch einreichen","library.php?op=offer");
		addnav("Empfehlenswerte Lektüre","library.php?op=recommended");
		addnav("Neueste Werke","library.php?op=new");

		addnav("Themen");
		$sql = "SELECT themeid, theme FROM lib_themes ORDER BY listorder ASC";
		$result = db_query($sql);
		while ($row2 = db_fetch_assoc($result))
		{
			addnav($row2['theme'],"library.php?op=theme&id=".$row2['themeid']);
		}

		addnav('Bücher');
		$sql = 'SELECT title, bookid FROM lib_books WHERE themeid='.$row['themeid'].' AND activated="1" ORDER BY listorder ASC';
		$result = db_query($sql);
		while ($row2 = db_fetch_assoc($result))
		{
			addnav(strip_appoencode($row2['title']),($row2['bookid']!=$_GET['bookid']?'library.php?op=book&bookid='.$row2['bookid']:''),false,false,false,false);
		}

		//nichts editierbar
		output("<div align='center'>
		<table cellpadding=2 cellspacing=1 bgcolor='#999999'>
		<tr class='trdark'>
		<td>Thema:</td>
		<td>".$row['theme']."`0</td>
		</tr>
		<tr class='trlight'>
		<td>Titel:</td>
		<td>".$row['title']."`0</td>
		</tr>
		<tr class='trdark'>
		<td>Autor:</td>
		<td>".($row['show_author']!=0 ? $row['author'] : getsetting("lib_alternative_author","Allgemeine Ver&ouml;ffentlichung"))."`0</td>
		</tr><tr class='trlight'>
		<td colspan='2'>
		<div align='center'>
		".($bool_style ? create_lnk('Textansicht','library.php?op=book&bookid='.$row['bookid'].'&style=0') : '`^Textansicht`0')
		.' | '
		.(!$bool_style ? create_lnk('Buchansicht','library.php?op=book&bookid='.$row['bookid'].'&style=1') : '`^Buchansicht`0').'</div>`n`n',true);
		if($bool_style == false)
		{
			output(str_replace("\n",'`n',$row['book']).'`0');
		}
		else
		{
			// Kleiner Test von Talion: Buch-Grafik als Hintergrund.
			//appoencode?? $row['book'] = appoencode(nl2br(strip_appoencode($row['book'],1)));
			$row['book'] = nl2br(strip_appoencode($row['book'],1));

			$author = ($row['show_author']!=0 ? 'Ein Buch von<br><br><b>'.strip_appoencode($row['author'],3).'</b><br>' : '');

			output('
			<div align="center">
			'.show_scroll('<div align="center" style="font-size:11pt;">
			<h1 style="font-size:14pt;">'.strip_appoencode($row['title'],3).'</h1>
			' . $author . '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><p align="center">Herausgegeben von der Hohen Bibliothek des gesammelten Wissens in '.getsetting('townname','').'</p><p>&nbsp;</p></div>
			<div align="left">
			'.$row['book']).'
			</div>
			</div>
			');
		}
		output('</td></tr></table></div>',true);
		break;
	}

	case "offer":
	{
		addnav("H?Zurück in die Halle","library.php");
		if ($_GET['subop']=="save" && !empty($_POST['title']) && !empty($_POST['book']))
		{
			addnav("Weiteres Buch schreiben","library.php?op=offer");
			output("`0Dein Buch wurde zum Druck eingereicht.`0");
			// maximale sortiernummer holen
			$sql = 'SELECT MAX(listorder) AS maxorder FROM lib_books';
			$result = db_query($sql);
			$row = db_fetch_assoc($result);

			db_insert('lib_books',
			array('themeid'=>$_POST['themeid'],'acctid'=>$session['user']['acctid'],'author'=>$session['user']['name'],'title'=>$_POST['title'],'book'=>$_POST['book'],'listorder'=>$row['maxorder']));

		}
		else
		{
			if ($_GET['subop']=='save')
			{
				output('`c`$Wie soll ein Buch gedruckt werden, wenn nicht Titel und Inhalt existieren?`0`c`n`n');
				$_POST['title'] = str_replace(array('`','³','²'),array('``','³³','²²'),$_POST['title']);
				$_POST['book'] = str_replace(array('`','³','²'),array('``','³³','²²'),$_POST['book']);
			}
			else
			{
				$_POST['title'] = $_POST['book'] = $_POST['themeid'] = '';
			}
			output("`0Hier hast du die Möglichkeit, eigenes Wissen niederzuschreiben und es anderen damit zur Verfügung zu stellen.`n`n
			Nun liegt es an dir, die Zeilen auf das Pergament zu bringen, die du dein Wissen nennst.`0
			<form action=\"library.php?op=offer&amp;subop=save\" method='POST'>
			<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trdark'><td>Thema:</td><td><select name='themeid'>",true);
			$sql2 = "SELECT * FROM lib_themes ORDER BY listorder ASC";
			$result2 = db_query($sql2);
			while ($row2 = db_fetch_assoc($result2))
			{
				output("<option value='".$row2['themeid']."' ".($row2['themeid']==$_POST['themeid']?" selected='selected'":"").">".utf8_preg_replace('/`./','',$row2['theme'])."</option>",true);
			}
			output("</select></td></tr>
			<tr class='trlight'><td>Titel:</td><td><input class='input' type='text' name='title' value='".$_POST['title']."' maxlength='50' size='50'></td></tr>
			<tr class='trdark'><td colspan='2'>Mein Wissen über dieses Thema:</td></tr>
			<tr class='trdark'><td colspan='2'><textarea name='book' class='input' cols='60' rows='10'>".$_POST['book']."</textarea></td></tr>
			<tr class='trlight'><td colspan='2'><input type='submit' class='button' value='Einreichen'></td></tr></table></form>",true);
			addnav("","library.php?op=offer&subop=save");
		}
		break;
	}

	case 'rules':
	{
		output('`0Am Schalter der Bibliothekare hängt ein Pergament. Bei näherer Betrachtung kannst du die (dir aufgrund der manchmal etwas seltsamen Sprache leicht unverständlichen) Regeln der Götter lesen, welche für das Einreichen neuer Bücher gelten:`n`n`n`n`q'.get_extended_text('lib_rules'));
		addnav('Zurück zur Halle','library.php');
		break;
	}

	case 'rp_train':
	{
		output('`0Am Rande des großen Lesesaals verbirgt eine schmale Tür dieses Gemach, welches dir nun offensteht.
		Es ähnelt im Aufbau der Halle, obgleich es natürlich wesentlich kleiner und schmäler ist. Ein großes, in Stein
		gemeißeltes Schild über der Eingangstür verkündet:`n
		`0`c`IRaum des Lernens.`0`c
		`n`0Hier kannst du nach Herzenslust das Sprechen und Auftreten in der Öffentlichkeit üben, Gelerntes anwenden und Tricks ausprobieren:`n`n');
		viewcommentary('rp_train','Etwas sagen:',30);
		addnav('Zurück zur Halle','library.php');

		if(!$session['user']['imprisoned'])
		{
			addnav('+?Zum OOC-Bereich','ooc_area.php');
		}
		break;
	}

	default:
	{
		output('`0Am Eingang zur Bibliothek hängt ein Plakat. Du liest:
		`n');
		output('`qDie Bibliothek ist ein Ort des Wissens.`n
		Dieses Wissen kann aber nur gehalten werden, wenn jemand es niedergeschrieben hat.`n
		Dazu steht in dieser Bibliothek die Möglichkeit bereit, Texte zu verfassen und diese einzureichen.`n
		Nachdem der geisterhafte Geoffrey, seit seinem Tod Bibliothekar Atrahors, und seine Tintengnome ihre Genehmigung erteilen, werden jene das Buch drucken und in die Regale der Bücherei stellen.`n
		Von nun an hat jeder die Möglichkeit, einen Blick in dieses Buch zu werfen und sowohl interessante als auch nützliche Informationen zu bekommen.`n
		Sollte das geschriebene Buch gedruckt werden, erhält der Autor ein Dankeschön in Form von `bbis zu '.getsetting("libdp","5").' Punkten`b in J.C. Petersens Jägerhütte, je nach Qualität.`0`n`n`n');

		addnav("Stöbern","library.php?op=browse");
		addnav("`^Empfohlene Lektüre`0","library.php?op=recommended");
		addnav("Neueste Werke","library.php?op=new");
		addnav('Raum des Lernens','library.php?op=rp_train');
		addnav('1?Buch der 1000 Namen','namegenerator.php',false,true);
	}


	if($session['user']['alive'])
	{

		if(!$session['user']['imprisoned'])
		{

			output("`0`(D`)ie `7B`ei`fb`0liothek erstreckt sich in einer großen, steinernen Halle, in der sich unzählige Regale aneinanderreihen. Fast greifbar sind in diesem Gebäude die Stille und das Wissen, welches hier zusammengetragen wird.
			Die Atmosphäre ist so eindringlich, dass man die Arbeit einiger tüchtiger Schreiberlinge, die ihre Erfahrungen und vieles mehr für die Nachwelt auf Pergament gebracht haben, einfach würdigen muss. Der Geruch von Papier und Tinte hängt in der Luft.
			Abseits in kleinen Nischen wurden mit Leder überzogene Sessel aufgestellt, damit man in aller Ruhe die Anstrengungen des Tages vergessen und in den Büchern stöbern kann. Ein jeder wird hier finden, wonach er sucht: Seien es Hilfestellungen oder spannende Geschichten, die von Kämpfen und großen Helden berichten; von einer holden Maid in Nöten, und dem gefährlichen Drachen der jene in einem Turm gefangen hält.
			So mag auch jedes Kinderherz hier gewiss höher schlagen, wenn man ihnen eine dieser Geschichten vorliest. Gleich am Eingang wurde für die Bibliothekare ein kleiner Schreibtisch aufgestellt, damit sie das Geschehen am besten überblicken `fk`eö`7n`)ne`(n.`n`n`(I`)m `7h`ei`fn`0tersten Winkel der Bibliothek, dort, wohin sich eigentlich niemals eine Menschenseele verirrt, befindet sich ein kleines Kämmerlein. An der Tür steht in Goldlettern 'Bibliothekar'.`n`n 
Der Bibliothekar der Drachenbibliothek heißt Geoffrey und ist tot. Er war zu seiner Zeit ein berühmter Gelehrter, der den Eierbecher erfand. Tragischerweise wurde er bei seinen Recherchen von einem Lexikon erschlagen, doch die gequälte Seele fand keine Ruhe und so verblieb sie in den Hallen der Bibliothek. Wenn man viel Glück hat, kann man den alten Mann mit Stock und Nickelbrille auf der Nase als durchscheinendes Wesen erkennen, wie es Bücher von einem Ort zum anderen bringt. Und kommt man doch einmal aus Zufall an jenem Kämmerlein vorbei, so kann man durch die milchigen Scheiben, die in die Tür eingelassen wurden, kleine Männlein erkennen, deren weiße Kittel von Tinte befleckt sind. Sie arbeiten an einem unendlich großen Berg an Büchern und ihre Federn ruhen nie. Das sind die Tintengnome, Verwandte der Silberputzgnome, die für nichts weiter als einen Keks und ein Bett für immer Bücher abschr`fe`ei`7b`)e`(n.
`0`n`n");
			if($session['user']['exchangequest']==2) //Tauschquest
			{
				$indate = getsetting('gamedate','0005-01-01');
				$date = explode('-',$indate);
				if($date[1]==2)
				{
					output('`& Vielleicht möchtest du dich ja auch an einen der Tische setzen und etwas schreiben?`0');
					addnav('T?`%An einen Tisch setzen','exchangequest.php');
				}
			} //end Tauschquest

			output("`n`n`0Ein paar Leute unterhalten sich leise:`n");

			viewcommentary("library","Leise flüstern:",25);
			addnav('Eigene Bücher');
			addnav("Buch einreichen","library.php?op=offer");
			addnav("Regeln","library.php?op=rules");
		}
		else
		{
			output("`0Auch den Gefangenen wird gestattet, sich in der steinernen Halle der Bibliothek am gesammelten Wissen zu bereichern und damit ihre Zeit im Kerker einigermaßen sinnvoll zu nutzen.
			Über verzweigte Gänge gelangt man in durch einen Hintereingang ins das Gebäude, doch Wachen begleiten die Kerkerinsassen auf Schritt und Tritt und begutachten sehr genau, welche Lektüre ausgewählt wird.
			Die ledernen Sessel allerdings dürfen nicht genutzt werden, da diese nur für rechtschaffene Bürger zur Verfügung gestellt werden.");
		}
	}
	else
	{
		output('`0Schemenhaft und ungreifbar schweben die Geister der Toten durch die unzähligen Regalreihen, die den meisten Platz in der weitläufigen, steinernen Halle der Bibliothek einnehmen.
		Dank Ramius Güte wird es den Geistern gestattet, hier ihre Zeit zu verbringen, die sie sonst in der Dunkelheit des Totenreiches absitzen müssten.
		Die Bibliothekare sind emsig beschäftigt, jedem einzelnen die für sie nur schemenreich erkennbaren Buchtitel vorzulesen und ihnen das gewünschte Buch auf einen der Tische zu legen, die zum Lesen bereitstehen.');
	}
}

addnav('Andere Orte');
addnav('Das Drachenmuseum','dragonmuseum.php');
if($session['user']['alive'])
{
	if($session['user']['imprisoned'])
	{
		addnav('Zum Kerker','prison.php');
	}
	else
	{
		addnav('d?Zum Stadtzentrum','village.php');
		addnav('M?Zum Marktplatz','market.php');
	}
}
else
{
	addnav('Zu den Schatten','shades.php');
}



page_footer();
?>