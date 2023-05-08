<?php
// idea of gargamel @ www.rabenthal.de
//31.10.08 Salator: Code entschlackt, Halbling hinzu, Farbänderung
//02.11.08 Asgarath: "Stadtwache holen" für Stadtwachen entfernt

if (!isset($session))
{
	exit();
}

if ($_GET['op']=="")
{
	$raceid = e_rand(1,6);
	switch ($raceid)
	{
	case 1:
		$race = "`^einer Elfe`A";
		$str_raceid='elf';
		break;
	case 2:
		$race = "`#eines Zwerges`A";
		$str_raceid='zwg';
		break;
	case 3:
		$race = "`2eines Trolls`A";
		$str_raceid='trl';
		break;
	case 4:
		$race = "`yeines Menschen`A";
		$str_raceid='men';
		break;
	case 5:
		$race = "`teines Halblings`A";
		$str_raceid='hbl';
		break;
	default:
		$race = "`2eines Orks`A";
		$str_raceid='ork';
		break;
	}
	//passendes Mordopfer aus der DB suchen
	$sql='SELECT name
		FROM accounts
		WHERE alive =0
			AND hitpoints >0
			AND race ="'.$str_raceid.'"
		ORDER BY rand() 
		LIMIT 1';
	$result=db_query($sql);
	if(db_num_rows($result)==1)
	{
		$row=db_fetch_assoc($result);
		$nametext=' Außerdem erkennst du, dass die Leiche einmal `4'.$row['name'].'`A war.';
	}
	output('`AEigentlich dachtest du, dies wird ein schöner Tag.... Aber dann findest du auf deinem Streifzug durch den Wald die Überreste '.$race.'. Offensichtlich ein Opfer erbitterter Kämpfe im Wald.
	`nDu untersuchst die Fundstelle und kommst zu dem Schluss, dass vor dir noch niemand hier war.'.$nametext.'
	`n`n`9Was wirst du tun?');
	//abschluss intro
	addnav("Opfer begraben","forest.php?op=bur&race=$raceid");
	addnav("W?Nach Wertsachen suchen","forest.php?op=exam&race=$raceid");
	if(($session['user']['profession']!= PROF_GUARD) && ($session['user']['profession']!= PROF_GUARD_HEAD))
	{
		addnav("Stadtwache holen","forest.php?op=call&race=$raceid");
	}
	addnav("Zurück in den Wald","forest.php?op=back");
	$session['user']['specialinc'] = "corpse.php";
}

else if ($_GET['op']=="exam")
{
	// opfer durchsuchen
	$raceid = $_GET['race'];
	$spec = 0;
	switch ($raceid)
	{
	case 1:
		$race = "`^der Elfe`A";
		$text = "Was glaubst du, bei so einer kleinen und zerbrechlichen Elfe zu finden? Richtig, auch `Qnach einiger Suche: Nichts!`A";
		break;

	case 2:
		$race = "`#des Zwerges`A";
		$gold = e_rand(100,500);
		$gem = e_rand(0,2);
		$text = "Du kennst natürlich die Affinität von Zwergen zu Reichtümern und so erwartest du eigentlich, etwas zu finden. `^Und richtig, nach einiger Zeit bemerkst du $gold Goldstücke";
		if ($gem > 0 )
		{
			$text = $text." und $gem Edelsteine, die du dir gleich einsteckst.`A";
		}
		else
		{
			$text = $text.", Die du dir gleich einsteckst.`A";
		}
		$session['user']['gold']+= $gold;
		$session['user']['gems']+= $gem;
		break;

	case 3:
		$race = "`2des Trolls`A";
		$text = "Dir ist die Stärke der Trolle bekannt und du schaust besonders intensiv nach Dingen, die dir im Kampf helfen würden.";
		$spec+=1;
		break;

	case 4:
		$race = "`ydes Menschen`A";
		$hp = round($session['user']['experience'] * 0.025);
		$text = "Lange suchst du in den übrig gebliebenen Habseligkeiten, ohne etwas
brauchbares zu finden. Du schaust dich nochmal genau um. Hier liegen auch keine
Rüstung oder Waffen. Du kommst zu dem Schluß, dass es sich nicht um die Überreste
eines jungen Kriegers handelt, sondern eher um die eines alten Mannes.`nDu kannst
nicht erklären warum, aber ein wenig seiner Lebenserfahrung wird für dich zugänglich.
`^Du erhältst $hp Erfahrung.";
		$session['user']['experience']+= $hp;
		break;

	case 5:
		$race = "`tdes Halblings`A";
		$text = "Was glaubst du, bei so einem kleinen Halbling zu finden? Richtig, du findest ein Bündel Halblingskraut!`A";
		item_add($session['user']['acctid'],'hlblkraut');
		break;

	default:
		$race = "`2des Orks`A";
		$text = "Vorsichtig näherst du dich den Überresten der Kreatur. Es ekelt dich an,
aber tapfer durchsuchst du alles. `QLeider findest du nichts Nützliches.";
		break;
	}
	output("`AEifrig machst du dich daran, die Überreste $race zu durchsuchen.
	`n`n$text`n`n");
	if ($spec == 1 )
	{
		increment_specialty();
	}
	if ($raceid != 6 )
	{
		output("`A`n`nMit der Suche offenbarst du deine pietätlose Gier. `QDu verlierst deshalb 3 Charmepunkte.");
		$session['user']['charm']=max(0,$session['user']['charm']-3);
	}
	$session['user']['specialinc'] = "";
}

else if ($_GET['op']=="bur")
{
	// opfer begraben
	$raceid = $_GET['race'];
	switch ($raceid)
	{
	case 1:
		$turn = min(1,$session['user']['turns']);
		break;
	case 2:
	case 3:
		$turn = min(2,$session['user']['turns']);
		break;
	default:
		$turn = min(3,$session['user']['turns']);
		break;
	}
	$text=($turn==1?'Waldkampf':'Waldkämpfe');
	output("`ADu nimmst dir die Zeit, das Opfer würdevoll zu begraben. `QDabei verlierst du $turn $text.
	`^`n`nDie Götter sind sehr erfreut über dich, du bekommst $turn Charmepunkte. Außerdem schenken dir die Götter einen permanenten Lebenspunkt und du wirst komplett
geheilt!");
	$session['user']['turns']-= $turn;
	$session['user']['charm']+= $turn;
	$session['user']['maxhitpoints']+=1;
	if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
	{
		$session['user']['hitpoints']=$session['user']['maxhitpoints'];
	}
	$session['user']['specialinc'] = "";
}

else if ($_GET['op']=="back")
{
	// zurück in den Wald
	output("`2Dein Fund ist dir genauso unheimlich wie die Umgebung hier. Du siehst zu, dass du schnell weiter kommst.`A");
	$session['user']['specialinc'] = "";
}

else if ($_GET['op']=="call")
{
	// stadtwache holen
	$raceid = $_GET['race'];

	switch ($raceid)
	{
	default:
		output("`ADu läufst in die Stadt, sagst der Wache bescheid, führst sie zur Fundstelle und beantwortest geduldig all ihre Fragen.");
		$chance = e_rand(1,10);
		if ($chance >= 8 )
		{
			output("`n`nLeider kommt die Stadtwache zu dem Schluss, dass du selbst für den Tod des Opfers verantwortlich bist.
			Richtig nachweisen können sie es dir zwar nicht, aber sie geben dir sehr deutlich zu verstehen, dass du dich heute lieber nicht mehr im Wald blicken lassen solltest.
			`n`nDu hast heute keine Waldkämpfe mehr.");
			$session['user']['turns']=0;
		}
		else
		{
			$hp = round($session['user']['experience'] * 0.025);
			output("`n`nDie Stadtwache bedankt sich bei dir. Nur weil du sie herbeigeholt hast, kann dieser Fall nun von den Behörden verfolgt werden.
			`n`n`6Für dieses umsichtige Verhalten bekommst du $hp Erfahrungspunkte.");
			$session['user']['experience']+= $hp;
		}
		break;

	case 6:
		$gold = round($session['user']['gold'] * 0.40);
		output("Am Ende stellt die Stadtwache fest, dass es sich um ein Ork gehandelt haben muss. Plötzlich wird das Gespräch rauer. \"`QWas fällt Dir ein, uns für sowas zu rufen`A\" schimpfen sie mit dir, \"`Qdafür sind wir doch nun wirklich nicht zuständig!`A\" musst du dir anhören.`n`n");
		if ($gold > 10 )
		{
			output("`QFür den Fehlalarm der Stadtwache erhältst du eine Rechnung über
$gold Gold, die sofort bezahlt werden muss.`A");
			$session['user']['gold']-= $gold;
		}
		break;
	}
	$session['user']['specialinc'] = "";
}

else
{
	output('`2Du kommst an ein Gebiet, welches von der Stadtwache abgesperrt wird. Ein Gardist sagt zu dir in fast befehlerischem Ton: "Bitte geht weiter, es gibt hier nichts zu sehen!"
	`nDas tust du dann auch.');
}
?>
