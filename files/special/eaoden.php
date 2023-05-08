<?php
/*
Eaoden's Kettenhemde
Idee by Chaos
Skript by Vaan & Hecki
Erstmals erschienen auf http://www.cop-logd.de
14//12//2004
*/

require_once'common.php';
page_header('Eaoden\'s Kettenhemde');
if ($_GET['op']=='')
{
	$session['user']['specialinc']='eaoden.php';
	if ($session['user']['armor']=='`7Dünnes Kettenhemd`0')
	{
		output('`2Du triffst im Wald wieder den Krieger der dir dieses dumme Kettenhemd für unverschämte 10 Edelsteine gegeben hat!`n`n
		`2Verärgert über das lausige Geschäft, das du gemacht hast, würdest du ihm am liebsten das Kettenhemd an den Kopf werfen!`n
		`@"Eigentlich keine schlechte Idee"`2, denkst du dir!`n');
		addnav('Dem Krieger eine verpassen','forest.php?op=werfen');
		addnav('Nee lieber nicht','forest.php?op=gehe2');
	}
	else if ($session['user']['armor']=='`7Kettenhemd eines Kriegers`0')
	{
		output('`2Du triffst im Wald wieder den Krieger, der dir ein starkes Kettenhemd für nur 10 Edelsteine gegeben hat!`n`n
		`2Du bist immer noch absolut zufrieden mit deinem Kettenhemd und könntest ihm nochmal dafür danken, ein Edelstein würde schon ausreichen!`n`n');
		addnav('Gib dem Krieger einen Edelstein','forest.php?op=gem');
		addnav('Zurück in den Wald','forest.php?op=gehe2');
	}
	else
	{
		output('`2Als du so durch den Wald streifst kommst du an einer kleinen Hütte vor bei, vor der ein alter Krieger steht. Der Krieger spricht dich an:
		`2"`9Ahh, du musst `@'.$session['user']['name'].' `9sein!? Ich habe von dir gehört... aber ich vermute, dass dir dein/deine '.$session['user']['armor'].'`9 nicht genügend schützt, nicht wahr!?`2"`n`n
		`2Du musst feststellen, dass er Recht hat. Er redet weiter. "`9Ich habe hier 2 Kisten, in beiden Kisten ist ein Kettenhemd. Wenn du in die richtige Kiste greifst, bekommt du ein gutes Kettenhemd! Ist es aber die falsche Kiste... nun sagen wir so... bekommst du ein... schlechtes Kettenhemd! Der Spaß kostet 10 Edelsteine! Das ist heute ein Sonderangebot! Nur für dich alleine! Also? was ist?`2"`n`n
		`2Du überlegst "`@Hmmm... also 10 Edelsteine... das ist wahrhaftig nicht viel.`2"`n`n
		`2Was willst du machen?');
		if($session['user']['armordef']>0 || $session['user']['armor']=='Straßenkleidung') //User hat kein Luxusgewand an
		{
			addnav('Dein Glück versuchen','forest.php?op=vers');
		}
		addnav('Tss, ich gehe','forest.php?op=gehe');
	}
}

else if($_GET['op']=='werfen')
{
	output('`2Du schleuderst das Kettenhemd mit voller Wucht in Richtung des Kriegers, aber Eoaden fängt es mit einem gekonnten Rückwärtssalto ab und hält es nun in seinen Händen.`n`n
	`@"Netter Versuch, Kleiner, aber unterschätze niemals einen alten Krieger mit seinen Kettenhemden!"`n`n
	`2Durch diese dumme Tat hast du nun auch dein letztes Hemd verloren und stehst wieder mit Straßenkleidung da!');
	item_set_armor();
	$session['user']['specialinc']="";
}

else if($_GET['op']=='vers')
{
	if ($session['user']['gems']<10)
	{
		output('`2Du bemerkst, dass du nicht genügend Edelsteine hast und gehst zurück in den Wald.');
		//addnav("Zurück in den Wald","forest.php");
		$session['user']['specialinc']="";
	}
	else
	{
		output('`2Du gibst dem Krieger die Edelsteine. Er schiebt dir 2 Kisten hin. ');
		$session['user']['gems']-=10;
		if(e_rand(1,4)<3)
		{
			output('`2Nach kurzem Überlegen greifst du in die rechte Kiste und ziehst ein dünnes Kettenhemd hervor.
			`nNa toll, du hast das schlechte Kettenhemd gezogen! Beleidigt gehst du zurück in den Wald.');
			//$sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`7Dünnes Kettenhemd','Rüstung','".$session['user']['acctid']."','27','800','Rüstung mit 3 Verteidigungswert')";
			//db_query($sql);
			$arr_armor = array('tpl_name'=>'`7Dünnes Kettenhemd`0','tpl_gold'=>800,'tpl_value1'=>3,'tpl_description'=>'Rüstung mit 3 Verteidigungswert.');
			$int_id = item_add($session['user']['acctid'],'rstdummy',$arr_armor);
			item_set_armor($arr_armor['tpl_name'], $arr_armor['tpl_value1'], $arr_armor['tpl_gold'], $int_id, 0, 2);
			//addnav("W?Weiter","forest.php');
			$session['user']['specialinc']="";
		}
		else
		{
			output('`2Nach kurzem Überlegen greifst du in die linke Kiste und ziehst ein dickes, schweres Kettenhemd hervor.
			`nSuper, du hast das gute Kettenhemd gezogen! Freudig gehst du zurück in den Wald.');
			//$sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`7Kettenhemd eines Kriegers','Rüstung','".$session['user']['acctid']."','27','3800','Rüstung mit 30 Verteidigungswert')";
			//db_query($sql);
			$arr_armor = array('tpl_name'=>'`7Kettenhemd eines Kriegers`0','tpl_gold'=>3800,'tpl_value1'=>25,'tpl_description'=>'Rüstung mit 25 Verteidigungswert.');
			$int_id = item_add($session['user']['acctid'],'rstdummy',$arr_armor);
			item_set_armor($arr_armor['tpl_name'], $arr_armor['tpl_value1'], $arr_armor['tpl_gold'], $int_id, 0, 2);
			//addnav("W?Weiter","forest.php');
		}
	}
	$session['user']['specialinc']='';
}

else if($_GET['op']=='gem')
{
	if ($session['user']['gems']<1)
	{
		output('`2Du bemerkst, dass du nicht genügend Edelsteine dabei hast und gehst zurück in den Wald.');
		//addnav("Z?Zurück in den Wald","forest.php");
		$session['user']['specialinc']="";
	}
	else
	{
		output('`2Du gibst dem Krieger noch einen Edelstein weil du dein neues Kettenhemd so klasse findest!`n');
		if($session['user']['armordef']==25)
		{
			output('`2Daraufhin spricht der Krieger eine kleine Zauberformel und dein Kettenhemd bietet dir noch einen Verteidigungspunkt mehr Schutz an!');
			item_set_armor('', $session['user']['armordef']+1, -1, 0, 0, 1);
			$session['user']['gems']--;
		}
		$session['user']['specialinc']='';
	}
}

else if($_GET['op']=='gehe')
{
	output('`2Du findest den Preis zu hoch, wendest dich von `9Eaoden `2ab und gehst weiter.');
	//addnav("Weiter","forest.php');
}

else //if($_GET['op']=="gehe2")
{
	output('`2Du lässt den Krieger lieber in Ruhe und gehst weiter.');
}

?>