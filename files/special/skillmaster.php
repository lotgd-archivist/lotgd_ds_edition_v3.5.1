<?php
if (!isset($session))
{
	exit();
}

$session['user']['specialinc']='skillmaster.php';
$sql = 'SELECT * FROM specialty WHERE specid="'.$session["user"]["specialty"].'"';
$result = db_query($sql);

if (db_num_rows($result)==0)
{
	output('Du wanderst plan- und ziellos durchs Leben. Du solltest eine Rast machen und einige wichtige Entscheidungen für dein weiteres Leben treffen.');
	$session['user']['specialinc']='';
	// addnav('Zurück zum Wald', 'forest.php');
}
else
{
	$row = db_fetch_assoc($result);
	if (file_exists('./module/specialty_modules/'.$row['filename'].'.php'))
	{
		require_once('./module/specialty_modules/'.$row['filename'].'.php');
		$f1 = $row['filename'].'_info';
		$f1();
		$skills = array($row['specid']=>$row['specname']);
		$c = $info['color'];
	}
	if ($c=='')
	{
		$c='`7';
	}
	
	if ($_GET['op']=="give")
	{
		if ($session['user']['gems']>0)
		{
			output($c.'Du gibst `@Foil`&wench'.$c.' einen Edelstein und sie überreicht dir einen Zettel aus Pergament mit Anweisungen, wie du deine Fertigkeiten steigern kannst.
			`n`nDu studierst den Zettel, zerreisst ihn und futterst ihn auf, damit Ungläubige nicht an die Information gelangen können.
			`n`n`@Foil`&wench'.$c.' seufzt... "`&Du hättest ihn nicht zu essen brauchen... Naja, jetzt verschwinde von hier!'.$c.'"`#');
			increment_specialty();
			$session['user']['gems']--;
			//debuglog('gave 1 gem to Foilwench');
		}
		else
		{
			output($c.'Du überreichst deinen imaginären Edelstein. `@Foil`&wench'.$c.' starrt dich verdutzt an. "`&Komm wieder, wenn du einen `bechten`b Edelstein hast, du Dummkopf.'.$c.'"
			`n`n"`#Dummkopf?'.$c.'"
			`n`nDamit schmeisst `@Foil`&wench'.$c.' dich endgültig raus.');
		}
		$session['user']['specialinc']="";
		//addnav('Zurück zum Wald', 'forest.php');
	}
	elseif ($_GET['op']=='dont')
	{
		output($c.' Du informierst `@Foil`&wench'.$c.' darüber, dass sie sich ihren Reichtum selbst verdienen sollte. Dann stampfst du davon.');
		$session['user']['specialinc']="";
		//addnav('Return to the forest', 'forest.php');
	}
	else if ($session['user']['specialty']>0)
	{
		output(''.$c.' Auf deinen Streifzügen durch den Wald auf Jagd nach Beute stößt du auf eine kleine Hütte. Du gehst hinein und wirst vom grauen Gesicht einer kampferprobten alten Frau empfangen: 
		"`&Sei gegrüßt, '.$session['user']['name'].'`&, ich bin `@Foil`&wench'.$c.', Meister in allem.'.$c.'"
		`n`n"`#Meister in allem?'.$c.'" fragst du nach.
		`n`n"`&Ja, Meister in Allem. Es liegt in meiner Macht, alle Fähigkeiten zu kontrollieren und zu lehren.'.$c.'"
		`n`n"`#Und zu lehren?'.$c.'" fragst du sie.
		`n`nDie alte Frau seufzt: "`&Ja, und zu lehren.  Ich werde dir zeigen, wie du deine '.$skills[$session['user']['specialty']].' steigern kannst - unter zwei Bedingungen.'.$c.'"
		`n`n"`#Zwei Bedingungen?'.$c.'" wiederholst du fragend.
		`n`n"`&Ja. Zuerst musst du mir einen Edelstein geben und dann musst du aufhören, alles was ich sage als Frage zu wiederholen!'.$c.'"
		`n`n"`#Ein Edelstein!'.$c.'" sagst du bestimmt.
		`n`n"`&Nun ... ich glaube das war keine Frage. Und wie sieht es mit dem Edelstein aus?'.$c.'"');
		addnav('Gib ihr einen Edelstein','forest.php?op=give');
		addnav('Gib ihr keinen Edelstein','forest.php?op=dont');
	}
}
?>
