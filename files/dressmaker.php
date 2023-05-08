<?php
/************************************************
* Der Schneider
* Verkauf von exquisiten Gewändern rein zu Rollenspielzwecken,
* userdefinierte Gewänder analog zu Unikaten
* Autor: Salator (salator@gmx.de)
* für lotgd Dragonslayer Version 3.23
*************************************************
Deklaration:
Item value1 muss 0 sein!
Item hvalue: Unterkategorie:
0/1 für ihn/sie
98/99 Unikat für ihn/sie
Item hvalue2: Geschlecht des Artikels: 0=männlich, 1=weiblich, 2=sächlich
Item special_info: Name des Designers
*/

require_once('common.php');
require_once(LIB_PATH.'board.lib.php');
checkday();
page_header('Der Schneider');
$str_filename=basename(__FILE__);
define('DP_KOSTEN_SPECIAL_ITEM',100);

$str_out=get_title('`:D`ze`[r `rS`&chne`ri`[d`ze`:r');

if ($_GET['op']=='')
{
	$str_out.='`:D`zu `[b`re`&trittst  geradewegs die Schneiderstube. Ein kleines, unscheinbares Männchen sitzt an einem kleinen Tisch zwischen Unmengen von edlen Stoffen und näht. Überall stehen Kleiderständer, auf denen die edlen Stücke ausgestellt sind.
	`nAls Nadelflink dich bemerkt eilt er geschäftstüchtig auf dich zu um seine Waren anzupreisen. Hier hast du die Möglichkeit, dich einzukleiden. Egal ob Wahl zum Schützenkönig oder Ball am königlichen Hof, Nadelflink hat für jeden Anlass das passende Gewand.
	`n`nAn der Wand hängt direkt neben der Elle ein Gürtel mit der Aufschrift `z"Sieben auf einen Streich"`&. ';
	if ($session['user']['specialtyuses']['thievery'])
	{
		$str_out.='Du glaubst, dass damit sicher 7 Diebe gemeint sind, also denkst du erst gar nicht daran, hier etwas zu ste`rh`[l`ze`:n.';
	}
	elseif ($session['user']['specialtyuses']['wisdom'])
	{
		$str_out.='Du weißt natürlich, dass es sich dabei um 7 Fliegen handelt. Dich kann man nicht hereinl`re`[g`ze`:n.';
	}
	elseif ($session['user']['race']=='zwg')
	{
		$str_out.='Du hast schonmal etwas von den "sieben Zwergen" gehört, so dass dir dieser Spruch zusammen mit der Elle gehörigen Respekt einf`rl`[ö`zß`:t.';
	}
	else
	{
		$str_out.='Jedoch hat sich noch niemand getraut, Nadelflink nach der Bedeutung dieses Spruchs zu fr`ra`[g`ze`:n.';
	}
	$str_out.='`n`n';
	output($str_out);
	$str_out='';
	viewcommentary('schneider','Über Mode diskutieren');
	addnav('Waren ansehen');
	addnav('D?Für die Dame',$str_filename.'?op=browse&class=1');
	addnav('H?Für den Herrn',$str_filename.'?op=browse&class=0');
	addnav('Maßgeschneidertes',$str_filename.'?op=item');
}

else if ($_GET['op']=='browse')
{
	$rowc['id']=30; //db-Abfrage sparen
	/*
	$sql = 'SELECT id FROM items_classes WHERE class_name="Kleidung"';
	$result = db_query($sql);
	$rowc = db_fetch_assoc($result);
	*/

	$sql = 'SELECT tpl_id,tpl_name,tpl_description,tpl_gold,tpl_gems
		FROM items_tpl
		WHERE tpl_class='.$rowc['id'].'
		AND tpl_id!="kleiddummy"
		AND tpl_hvalue'.($_GET['class']>1?'='.$_GET['class']:'%2='.$_GET['class']).'
		ORDER BY tpl_id ASC';
	$result = db_query($sql);
	$str_out='Der Schneider kann dir diese Dinge verkaufen:`0';

	$str_out.='`n`n<table border="0" cellpadding="0" width=95%>';
	$str_out.='<tr class="trhead"><th>Name</th><th>Beschreibung</th><th align="right">Preis</th></tr>';

	for ($i=0;$i<db_num_rows($result);$i++)
	{
		$row = db_fetch_assoc($result);
		$bgcolor=($bgcolor=='trdark'?'trlight':'trdark');
		$str_out.='<tr class="'.$bgcolor.'">
		<td valign="top">'.create_lnk($row['tpl_name'],$str_filename."?op=buy&id=".$row['tpl_id']).'</td>
		<td>'.$row['tpl_description'].'</td>
		<td align="right" valign="top" style="white-space: nowrap;">'.($row['tpl_gold']>0?'<br>`^'.$row['tpl_gold'].'&nbsp;<img src="./images/icons/gold.gif" alt="Gold">':'').($row['tpl_gems']>0?'<br>`#'.$row['tpl_gems'].'&nbsp;<img src="./images/icons/gem.gif" alt="ES">':'').'`0</td>
		</tr>';
	}
	$str_out.='</table>';

	$show_invent = true;
}

else if ($_GET['op']=='buy') //etwas kaufen
{
	$sql = 'SELECT * FROM items_tpl WHERE tpl_id="'.$_GET['id'].'"';
	$result = db_query($sql);
	if (db_num_rows($result)==0) //Fehler
	{
		$str_out.='`&Du denkst, dir etwas ganz Besonderes ausgesucht zu haben, doch der Schneider meint nur: "`zTut mir leid, aber mit '.$_GET['id'].' kann ich nicht dienen.`&"';
	}
	else //OK, tpl gefunden
	{
		$row = db_fetch_assoc($result);
		if ($row['tpl_gold']>$session['user']['gold'] || $row['tpl_gems']>$session['user']['gems'])
		{
			$str_out.='`$Das kannst du dir nicht leisten!`0';
		}
		else
		{
			$uncol_name=strip_appoencode($row['tpl_name']);
			$arr_colorcodes=array(
			'`A`4`$`4`A',
			'`D`d`q`d`D',
			'`^`/`y`/`^',
			'`J`2`j`2`J',
      '`G`g`8`g`G',
      '`1`!`9`!`1',
      '`w`F`f`F`w',
			'`x`R`r`R`x',
			'`S`T`Y`T`S',
			'`u`I`t`I`u',
			'`(`)`7`)`(',
      '`e`s`&`s`e'
			);

			if($_POST['colorcode']>'' || (isset($_POST['itemname']) && $_POST['itemname']!=$uncol_name)) //Farbe wurde bereits ausgesucht
			{
				if($_POST['colorcode']=='own' || $_POST['itemname']!=$uncol_name) //eigene Färbung
				{
					if(strip_appoencode($_POST['itemname'])==$uncol_name)
					{
						$row['tpl_name'] = $_POST['itemname'];
					}
					else //falscher Name
					{
						$session['message']='Der Name darf nicht geändert werden! Nur Farbcodes sind erlaubt.';
						redirect($str_filename.'?op=buy');
					}
				}
				elseif($_POST['colorcode']=='user') //Färbung wie Username
				{
					$row['tpl_name']=color_from_name(strip_appoencode($row['tpl_name']),$session['user']['name']);
				}
				elseif($_POST['colorcode']=='none') //Originalfarbe behalten
				{
					//nothing to do
				}
				else //Standardfarben
				{
					$row['tpl_name']=color_from_name(strip_appoencode($row['tpl_name']),$arr_colorcodes[$_POST['colorcode']]);
				}

				if(!mb_strpos($row['tpl_name'],'`0')) $row['tpl_name'].='`0'; //ggf Farbaufhebung anhängen
				$str_out.='`&Du übergibst dem Schneider den verlangten Preis und bekommst dafür '.($row['tpl_hvalue2']?($row['tpl_hvalue2']==1?'eine wunderschöne ':'ein exquisites ' ):'einen prächtigen ').$row['tpl_name'].'.
				`n`n"`zEine sehr gute Wahl, '.($session['user']['sex']?($session['user']['dragonkills']>10?'Madame':'junges Fräulein'):'mein Herr').', '.strip_appoencode($row['tpl_description']).'
				`nUnd wenn Ihr wieder etwas braucht, ich stehe stets zu Euren Diensten.`0"';
				$session['user']['gold']-=$row['tpl_gold'];
				$session['user']['gems']-=$row['tpl_gems'];

				item_add($session['user']['acctid'],'',$row);

				addnav('Mehr kaufen',$str_filename.'?op=browse&class='.$session['user']['sex']);
			}
			else //Formular Farbauswahl
			{
				addnav('',$str_filename.'?op=buy&id='.$_GET['id']);
				if(isset($session['message']))
				{
					$str_out.='`b`$'.$session['message'].'`0`b`n`n';
					unset($session['message']);
				}
				
				$str_out.='`&Nadelflink lässt dich wissen, dass du die Kleider aus farbigen Stoffen nach deinen Wünschen bekommen kannst.
				`nBitte wähle aus diesen Möglichkeiten:`n
				<form action="'.$str_filename.'?op=buy&id='.$_GET['id'].'" method="post">';
				foreach($arr_colorcodes as $key => $value)
				{
					$str_out.='
					<input type="radio" name="colorcode" value="'.$key.'"> '.color_from_name($uncol_name,$value).'`0`n';
				}
				$str_out.='
				Deine Farbe:`n
				<input type="radio" name="colorcode" value="user"> '.color_from_name($uncol_name,$session['user']['name']).'`0`n
				'.(mb_substr($row['tpl_name'],0,1)=='`'?'Originalfarbe':'ohne Farbe').':`n
				<input type="radio" name="colorcode" value="none"> '.$row['tpl_name'].'`0`n
				etwas ganz anderes:`n
				<input type="radio" name="colorcode" id="owncolor" value="own">
				'.js_preview('itemname').'`n
				<input type="text" name="itemname" id="itemname" value="'.$uncol_name.'">`n`n
				<input type="submit" class="button" value="Färbung übernehmen">
				</form>';
			}
		}
	}
}

else if ($_GET['op'] == 'item') //einzigartiges Kleidungsstück
{
	$str_out.='`&Hier hast du die Möglichkeit, dir für 20 Edelsteine und '.DP_KOSTEN_SPECIAL_ITEM.' Donationpoints ein einzigartiges, nach deinen Wünschen gestaltetes Kleidungsstück fertigen zu lassen.
	`nAußerdem bietet Nadelflink dir auch an, dieses Kleidungsstück an andere Einwohner '.getsetting('townname','Atrahor').'s zu versenden.
	`nJedem dieser Gewänder liegt ein Zertifikat bei, welches dich als Designer kennzeichnet.';
	if ($session['user']['gems']>=20 && $session['user']['donation']-$session['user']['donationspent'] >= DP_KOSTEN_SPECIAL_ITEM)
	{
		$str_out.='`n`nNadelflink benötigt nun die folgenden Informationen von dir:
		`n`n`0<form method="POST" action="'.$str_filename.'?op=item_confirm">
		<table border=0 width=100%>
		<tr>
		<td>Das Kleidungsstück ist für:</td>
		<td><input type="radio" name="hvalue" value="99" checked> eine Dame
		`n<input type="radio" name="hvalue" value="98"> einen Herrn</td>
		</tr><tr>
		<td>Vorschau:</td>
		<td>'.js_preview('name').'</td>
		</tr><tr>
		<td>Name des Kleidungsstücks:</td>
		<td><input type="text" name="name" id="name" size="40" maxlength="90" value="'.$name.'"></td>
		</tr><tr>
		<td>Vorschau:</td>
		<td>'.js_preview('desc').'</td>
		</tr><tr>
		<td>Beschreibung:</td>
		<td><textarea rows="4" cols="50" name="desc" id="desc" class="input" maxlength="600">'.$desc.'</textarea></td>
		</tr><tr>
		<td>&nbsp;</td>
		<td><input type="submit" name="ok" value="Kaufen"></td>
		</tr>
		</table>
		`n</form>';
		addnav('',$str_filename.'?op=item_confirm');
	}
	else
	{
		$str_out.='`n`n`4Leider kannst du dir diesen Luxus nicht leisten.';
	}
}

else if ($_GET['op'] == 'item_confirm') //einzigartiges Kleidungsstück selbst verwenden oder verschenken
{
	output($str_out);
	unset($str_out);
	addnav('Besonderes Kleidungsstück');
	$name = '`7 '.trim(stripslashes($_POST['name'])).'`0';
	$desc = trim(stripslashes($_POST['desc']));
	output('Wirklich `b'.DP_KOSTEN_SPECIAL_ITEM.'`b Punkte für dieses einzigartige Kleidungsstück ausgeben? Es wird ungefähr so aussehen:
	`n`n'.utf8_htmlspecialsimple($name).' `&('.utf8_htmlspecialsimple($desc).'`&)
	`nWillst du es selbst verwenden oder an jemanden verschenken?
	`n`n<form method="POST" action="'.$str_filename.'?op=item_ok">
	`n<input type="hidden" name="hvalue" value="'.(int)$_POST['hvalue'].'">
	<input type="hidden" name="name" value="');
	rawoutput(utf8_htmlentities($name));
	output('"><input type="hidden" name="desc" value="');
	rawoutput(utf8_htmlentities($desc).'`0');
	output('">
	<input type="submit" name="ok_selbst" value="Selbst verwenden!">
	<input type="submit" name="ok_geschenk" value="Verschenken">
	`n</form>');
	addnav('',$str_filename.'?op=item_ok');
}

else if ($_GET['op'] == 'item_ok') //einzigartiges Kleidungsstück kaufen/verschenken Abschluss
{
	output($str_out);
	unset($str_out);
	$name = trim(stripslashes($_POST['name']));
	$desc = trim(stripslashes(mb_substr($_POST['desc'],0,610)));

	if ($_GET['act'] == 'search' && mb_strlen($_POST['search']) > 2)
	{

		output($name.' `&('.$desc.'`&)`n`n');

		$search = str_create_search_string($_POST['search']);

		$sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$search.'" AND acctid!='.$session['user']['acctid'].' ORDER BY (login="'.db_real_escape_string($_POST['search']).'") DESC, login';
		$res = db_query($sql);

		$link = $str_filename.'?op=item_ok';

		output('<form action="'.$link.'" method="POST">
		<input type="hidden" name="hvalue" value="'.(int)$_POST['hvalue'].'">
		<input type="hidden" name="name" value="');
		rawoutput(utf8_htmlentities($name));
		output('"><input type="hidden" name="desc" value="');
		rawoutput(utf8_htmlentities($desc));
		output('">
		<select name="acctid">');

		while ($p = db_fetch_assoc($res) )
		{
			output('<option value="'.$p['acctid'].'">'.strip_appoencode($p['name'],3).'</option>');
		}

		output('</select>`n`n
		<input type="submit" class="button" value="Auswählen!"></form>');
		addnav('',$link);
	}
	else if ($_POST['ok_geschenk'])
	{
		$link = $str_filename.'?op=item_ok&act=search';

		output($name.' `&('.$desc.'`&)
		`n`nAn wen willst du das Gewand versenden?
		`n`n`0<form action="'.$link.'" method="POST">
		<input type="hidden" name="hvalue" value="'.(int)$_POST['hvalue'].'">
		<input type="hidden" name="name" value="');
		rawoutput(utf8_htmlentities($name));
		output('"><input type="hidden" name="desc" value="');
		rawoutput(utf8_htmlentities($desc));
		output('">
		Name: <input type="text" name="search">
		<input type="submit" class="button" value="Suchen!"></form>');
		addnav('',$link);

	}
	// END Geschenk
	else
	{
		$acctid = (int)$_POST['acctid'];

		$session['user']['donationspent'] += DP_KOSTEN_SPECIAL_ITEM;
		$session['user']['gems']-=20;

		$item['tpl_name'] = utf8_htmlspecialsimple(utf8_html_entity_decode($name));
		$item['tpl_description'] = utf8_htmlspecialsimple(utf8_html_entity_decode($desc));
		$item['tpl_gold'] = 0;
		$item['tpl_gems'] = 10;
		$item['tpl_hvalue'] = (int)$_POST['hvalue'];
		$item['tpl_special_info'] = $session['user']['name'];

		item_add(($acctid ? $acctid : $session['user']['acctid']) , 'kleiddummy' , $item );

		output('`&Nadelflink protokolliert gewissenhaft diesen Wunsch und meint dann:`n');
		if (!$acctid)
		{
			output('`:"Dein besonderes Kleidungsstück steht nun für dich bereit. Viel Spaß damit..."');
			debuglog('Gab '.DP_KOSTEN_SPECIAL_ITEM.' DP für Specialitem '.$name);
		}
		else
		{
			systemmail($acctid,'`2Ein Geschenk!',$session['user']['name'].'`2 hat dir ein einzigartiges Gewand namens '.$name.'`2 zum Geschenk gemacht. Du kannst es mit dir rumtragen, es anbeten oder einfach in ein Haus oder Privatgemach legen! Ist das nicht nett?`n(Kleiner Tipp: Du findest es in deinem Inventar.)');
			output('`:"Dein besonderes Kleidungsstück wurde an die gewünschte Person geliefert. Hoffentlich gefällt es..."');
			debuglog('Gab '.DP_KOSTEN_SPECIAL_ITEM.' DP für Specialitem '.$name.' für',$acctid);
		}
		output('`&, woraufhin er sich wieder seiner Arbeit zuwendet.');
	}
}

output($str_out);
addnav('Zurück');
addnav('Zur Übersicht',$str_filename);
addnav('M?Zum Markt','market.php');
page_footer();
?>