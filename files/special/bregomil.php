<?php
// Bregomil
// By Maris (Maraxxus@gmx.de)

if (!isset($session)) exit();

$dart_cost=1; // Kosten für die Scheibe
$sack_cost=3; // Kosten für den Sandsack
$dummy_cost=5; // Kosten für die Puppe

if ($_GET['op']=='')
{
	$session['user']['specialinc']='bregomil.php';
	$hour=strftime('%H');
	output('Du gelangst an eine kleine Lichtung, die du irgendwo schon einmal gesehen hast. Doch diesmal erkennst du ein kleines Häuschen, nah am Waldrand. Die Hütte ist aus Stein und Holz gearbeitet und sieht sehr einladend aus. Die Tür steht weit offen und ohne zu überlegen gehst du näher und trittst ein.
	`n`5"Willkommen, Freund!"`0 ertönt es aus einer Ecke, in der ein kleiner, dünner Mann sitzt, der gerade an einem Stück Holz schnitzt.
	`n`5"Ich bin Bregomil Auerhahn, Künstler und Handwerker. Ich habe mich auf die Fertigung von Übungsgeräten spezialisiert. Seid nicht abgeschreckt von meinen Preisen, ich garantiere Euch höchste Qualität! Und dazu werde ich die Geräte mit dem Antlitz Eures schlimmsten Feindes versehen, damit das Training gleich doppelt so viel Spass macht! Na, was sagt Ihr?"`0
	`nAls Bregomil dein Zögern bemerkt unterbreitet er dir ein weiteres Angebot: "`5Wenn du lieber etwas zum Knuddeln haben willst, ich könnte dir auch '.($hour<6?'ein besonderes Püppchen':'einen Teddy').' mit dem Aussehen deines liebsten Freundes machen.`0"');
	addnav('Etwas kaufen?');
	addnav('Zielscheibe für '.$dart_cost.' Edelsteine','forest.php?op=weiter&was=scheibe');
	addnav('Sandsack für '.$sack_cost.' Edelsteine','forest.php?op=weiter&was=sack');
	addnav('p?Strohpuppe für '.$dummy_cost.' Edelsteine','forest.php?op=weiter&was=puppe');
	$hour=strftime('%H');
	addnav(($hour<6?'Kuschelpuppe':'Teddybär').' für '.$dummy_cost.' Edelsteine','forest.php?op=weiter&was=lovedoll');
	addnav('Danke, heute nicht!','forest.php?op=weg');
	$session['user']['specialinc'] = 'bregomil.php';
}

else if ($_GET['op']=="weiter")
{
	$session['user']['specialinc']="bregomil.php";
	$was=$_GET['was'];
	if ($was=="scheibe") { $cost=$dart_cost; }
	if ($was=="sack") { $cost=$sack_cost; }
	if ($was=="puppe" || $was=='lovedoll') { $cost=$dummy_cost; }

	if ( $session['user']['gems'] < $cost )
	{
		output("Du hast leider nicht genug Edelsteine, um dir das leisten zu können. Also lächelst du peinlich berührt und machst dich davon.`0");
	}
	else
	{
		if ($_GET['who']=='')
		{
			output('"`5Na, wem soll Euer neues Trainingsgerät denn ähnlich sehen ?`0"');
			if ($_GET['subop']!='search')
			{
				output("<form action='forest.php?op=weiter&was=$was&subop=search' method='POST'>
				<input name='name' id='name'>
				<input type='submit' class='button' value='Suchen'>
				</form>
				".focus_form_element('name'));
				addnav("","forest.php?op=weiter&was=$was&subop=search");
				addnav('Äh.. Doch nichts','forest.php?op=weg');
			}
			else
			{
				addnav('Neue Suche','forest.php?op=weiter&was='.$was);
				addnav('Kann ich das Andere nochmal sehen?','forest.php');
				if($_POST['name']=='mir' || $_POST['name']=='ich')
				{
					$_POST['name']=$session['user']['login'];
				}
				$search = str_create_search_string($_POST['name']);

				$sql = "SELECT acctid,name
					FROM accounts
					WHERE (locked=0 AND name LIKE '".$search."')
					ORDER BY login='".db_real_escape_string($_POST['name'])."' DESC, level DESC
					LIMIT 100";
				//output($sql);
				$result = db_query($sql);
				$max = db_num_rows($result);
				if ($max >= 100)
				{
					output('`n`n"`#Beschreibt bitte etwas genauer, ich kann so nicht arbeiten!`0"`n');
					$max = 100;
				}
				output("<table border=0 cellpadding=0>
				<tr class='trhead'>
				<th>Name</th>
				</tr>",true);
				if($max==0)
				{
					output("<tr>
					<td>".create_lnk($session['user']['name'],'forest.php?op=weiter&was='.$was.'&who='.$session['user']['acctid'])."</td>
					</tr>");
				}
				for ($i=0;$i<$max;$i++)
				{
					$row = db_fetch_assoc($result);
					output("<tr>
					<td>".create_lnk($row['name'],'forest.php?op=weiter&was='.$was.'&who='.$row['acctid'])."</td>
					</tr>");
				}
				output("</table>",true);
			}
		}
		else
		{
			$sql = 'SELECT name,acctid
				FROM accounts
				WHERE acctid='.(int)$_GET['who'];
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			output ('`5Soso... Euer Übungsgerät soll also so aussehen wie `%'.$row['name'].'`5?`0');
			addnav("Ja","forest.php?op=finish&was=$was&who=".$row['acctid']."");
			addnav("Nein","forest.php?op=weiter&was=$was");
			addnav('Ich will was ganz Anderes!','forest.php');
		}
	}
}

else if ($_GET['op']=="finish")
{
	$sql = 'SELECT name,acctid
		FROM accounts
		WHERE acctid='.(int)$_GET['who'];
	$result = db_query($sql);
	$row = db_fetch_assoc($result);

	if ($_GET['was']=='scheibe')
	{
		$was=' Zielscheibe';
		$tpl_id = 'zielsch';
		$item['tpl_gems']=$dart_cost;
		$item['tpl_name']='Zielscheibe';
		$item['tpl_description']='Zum Üben. Auf der Scheibe befindet sich ein Bild von '.($row['name']).'.';
	}
	elseif ($_GET['was']=='sack')
	{
		$was='n Sandsack';
		$tpl_id='sandsack';
		$item['tpl_gems']=$sack_cost;
		$item['tpl_name']='Sandsack';
		$item['tpl_description']='Zum Üben. Auf dem Sack wurde ein Bild von '.($row['name']).' aufgenäht.';
	}
	elseif ($_GET['was']=='puppe')
	{
		$was=' Strohpuppe';
		$tpl_id='strpuppe';
		$item['tpl_gems']=$dummy_cost;
		$item['tpl_name']='Strohpuppe';
		$item['tpl_description']='Zum Üben. Die Puppe hat täuschende Ähnlichkeit mit '.($row['name']).'.';
	}
	elseif ($_GET['was']=='lovedoll' && strftime('%H')<6)
	{
		$was=' Kuschelpuppe';
		$tpl_id='lovedoll';
		$item['tpl_gems']=$dummy_cost;
		$item['tpl_name']='Kuschelpuppe';
		$item['tpl_description']='Zum Liebhaben. Die Puppe hat täuschende Ähnlichkeit mit '.($row['name']).'.';
		$item['tpl_hvalue']=1;
	}
	else
	{
		$was='n Teddybär';
		$tpl_id='lovedoll';
		$item['tpl_gems']=$dummy_cost;
		$item['tpl_name']='Teddybär';
		$item['tpl_description']='Zum Liebhaben.  Dieser Teddy sieht fast genauso aus wie '.($row['name']).'.';
	}

	$session['user']['specialinc']='';

	//Achtung! value1 war früher die Hausnummer wo das eingelagert ist. Wenn dein Server nicht mit dem Dragonslayer-Itemsystem arbeitet muss die nächste Zeile weg!
	$item['tpl_value1'] = $row['acctid'];

	if(item_add($session['user']['acctid'], $tpl_id, $item))
	{
		$session['user']['gems']-=$item['tpl_gems'];
		
		output ('Der Mann streicht deine Edelsteine ein und macht sich an die Arbeit. Nach einiger Zeit kommt er wieder und gibt dir deine'.$was.'. Sieht tatsächlich so aus wie '.$row['name'].'!
		`n`nDu klemmst dir das Meisterwerk unter den Arm und gehst deines Weges.
		`nVergiss nicht, das gute Stück in deinem Haus einzulagern, damit es nicht verloren geht!');
	}
	else
	{
		output('Bregomil bemerkt auf einmal, dass ihm einige wichtige Dinge für eine'.$was.' fehlen und bittet vielmals um Entschuldigung. Er verspricht dir Super-Sonderkonditionen und frei-Haus-Lieferung, sobald er eine'.$was.' herstellen kann.
		`nNa wenn das mal keine leere Versprechung ist...');
		systemlog('Itemschablone '.$tpl_id.' fehlt! special/bregomil.php');
	}

}

else if ($_GET['op']=='weg')
{
	output('`QDu beschließt, so etwas nicht nötig zu haben und verlässt die Hütte.`0');
	$session['user']['specialinc']='';
}

else //Fehler
{
	output('`n`QDu begegnest Bregomil Auerhahn, dem Hersteller von Kampfübungsgeräten, welcher aber gerade keine Zeit hat. Also gehst du weiter.`0');
	$session['user']['specialinc']='';
}

?>
