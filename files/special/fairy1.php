<?php
if (!isset($session))
{
	exit();
}


if ($_GET['op']=='give')
{
	if ($session['user']['gems']>0)
	{
		output('`%Du gibst der Fee einen deiner schwer verdienten Edelsteine.
		Sie schaut ihn an, quiekt vor Entzückung und verspricht dir als Gegenleistung ein Geschenk.
		Sie schwebt dir über den Kopf und streut goldenen Feenstaub auf dich herab, bevor sie davon huscht.
		Wenig später wird dir die Wirkung klar: `n`n`^');
		$session['user']['gems']--;
		switch (e_rand(1,10))
		{
		case 1:
			if($session['user']['dragonkills']>10 && $session['user']['exchangequest']==0 && e_rand(1,10)==5)
			{
				$session['user']['gems']++;
				$session['user']['specialinc']='';
				redirect('well_of_urd.php?op=start');
			}
			output('Du bekommst einen zusätzlichen Waldkampf!');
			$session['user']['turns']++;
			break;
		case 2:
		case 3:
			output('Du fühlst deine Sinne geschärft und bemerkst `%ZWEI`^ Edelsteine in der Nähe!');
			$session['user']['gems']+=2;
			break;
		case 4:
		case 5:
			output('Deine maximalen Lebenspunkte sind `bpermanent`b um 1 erhöht!');
			$session['user']['maxhitpoints']++;
			$session['user']['hitpoints']++;
			break;
		case 6:
		case 7:
			increment_specialty();
			break;
		case 8:
		case 9:
		case 10:
			output('Der Staub glitzert schön, mehr aber nicht. Und bevor du die Fee noch einmal fragen kannst ist sie schon längst verschwunden.');
			break;
		}
	}
	else
	{
		output("`%Du versprichst der Fee einen Edelstein, aber als du dein Goldsäckchen öffnest, entdeckst du, dass du gar keinen Edelstein hast.
		Die kleine Fee schwebt vor dir, die Arme in die Hüfte gestemmt und mit dem Fuß in der Luft klopfend, während du ihr zu erklären versuchst, warum du sie angelogen hast.
		`n`nAls sie genug von deinem Gestammel hat, streut sie ärgerlich etwas roten Feenstaub auf dich. 
		Du wirst ohnmächtig und als du wieder zu dir kommst, hast du keine Ahnung, wo du bist.
		Du brauchst so viel Zeit, um den Weg zurück in die Stadt zu finden, daß du einen ganzen Waldkampf verlierst.");
		$session['user']['turns']--;
	}
}

else if($_GET['op'] == 'dont')
{
	output('`%Du willst dich nicht von einem deiner kostbaren Edelsteine trennen und schmetterst das kleine Geschöpf im Vorbeigehen auf den Boden.`n');
	
	if (e_rand(1,25)==25)
	{
		output('`%Vollkommen erbost über diese respektlose Handlung ');
		
		$newtitle='Flauschihase';
		
		if ($session['user']['title']!=$newtitle)
		{
			$session['user']['title'] = $newtitle;
			$oldweaponname=$session['user']['weapon'];
			$oldarmorname=$session['user']['armor'];
			
			item_set_weapon('Samtpfötchen', 1, 1);
			item_set_armor('Kuscheliges Fell', 1, 1);
			
			user_set_name($session['user']['acctid']);
			
			addnews('`@'.$session['user']['name'].'`@ hat heute einen unfreiwilligen Imagewandel erfahren.');
			output('schimpft dir die kleine Fee hinterher und belegt dich mit einem sehr sonderbaren Fluch...
			`n`n`n`0Die Welt kommt dir plötzlich so groß vor. Auch dein '.$oldweaponname.'`0 und dein '.$oldarmorname.'`0 sind nicht mehr da wo sie eben noch waren. Wenigstens hast du deinen Inventarbeutel noch bei dir, in den du bald mal reingucken solltest.
			`n`%Verstört hoppelst du davon.');
			
			$session['user']['charm']++;
			
		}
		else
		{
			output('wirft sie dir eine Möhre an den Kopf. Autsch!');
			$session['user']['hitpoints']=max(1,$session['user']['hitpoints']-5);
			addnews('`@'.$session['user']['name'].'`# wurde von einer ärgerlichen Fee mit einer Möhre beworfen.');
		}
	}
}

else
{
	output('`%Du begegnest einer Fee. "`^Gib mir einen Edelstein!`%" verlangt sie. Was tust du?');
	addnav('Gib ihr einen Edelstein','forest.php?op=give');
	addnav('Gib ihr keinen Edelstein','forest.php?op=dont');
	$session['user']['specialinc']='fairy1.php';
}

output('`0`n');
?>
