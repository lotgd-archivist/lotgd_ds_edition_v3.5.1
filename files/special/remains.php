<?php

// 11072004

/* Skeletal Remains v1.1 by Timothy Drescher (Voratus)
Current version can be found at Domarr's Keep (lgd.tod-online.com)

Version History
1.0 original version
1.1 bug fix (would not award gold nor gem(s) upon defeating the warrior)

- german translation by anpera
- some changes for my version - may not work with other versions!
- taken as base code to introduce curses based on my item system ;)
*/

if (!isset($session))
{
	exit();
}
$session['user']['specialinc']="remains.php";

if ($_GET['op']=='' || $_GET['op']=='search')
{
	$result = db_query('SELECT name,sex FROM accounts WHERE alive=0 AND hitpoints=0 AND dragonkills>0 ORDER BY rand('.e_rand().') LIMIT 1');
	$row2 = db_fetch_assoc($result);
	$tname = ($row2['name']?$row2['name']:'Krieger Voratus');
	output('`GDu stolperst über etwas. Es sind die skelettierten Überreste dessen, was einst ein Abenteuer wie du gewesen sein könnte. Tatsächlich deutet vieles darauf hin, dass dies die Überreste von `%'.$tname.'`G sind. 
	`nDie sterblichen Überreste scheinen unberührt und was immer '.($row2['sex']?'sie':'er').' für Schätze dabei gehabt haben mag, könnte immer noch dort sein.
	`n`nWas wirst du tun?`n`n');
	addnav('Nach Reichtümern suchen','forest.php?op=desecrate');
	addnav('Die Leiche in Ruhe lassen','forest.php?op=leave');
	if ($session['user']['turns'] > 2)
	{
		addnav('Begraben (3 Waldkämpfe)','forest.php?op=bury');
	}
	$session['user']['specialinc']='remains.php';
}
else if ($_GET['op']=='bury')
{
	$session['user']['turns']-=3;
	$session['user']['reputation']+=2;
	output('`GDu verbringst einen großen Teil des Tages damit, diesem gefallenen Abenteurer ein Grab zu schaufeln und ihm ein ordentliches Begräbnis zukommen zu lassen. Du verzichtest auf Reichtum, wenn du ihn stehlen müsstest - erst Recht von Toten.`n
	`n`nAußerdem willst du nicht die Rache der Toten hinter dir wissen, zusätzlich zu den Kreaturen des Waldes.`n`n
	Als du endlich mit dem Grab fertig bist, erscheint eine kleine Fee vor dir. "`#Das war sehr edel, was du getan hast. Ich werde dich dafür belohnen.`G"`n`n');
	$reward = e_rand(1,12);
	switch ($reward)
	{
	case 1:
	case 2:
	case 3:
		output('Die Fee gibt dir einen Edelstein!');
		$session['user']['gems']++;
		break;
	case 4:
	case 5:
	case 6:
		$cash = e_rand(($session['user']['level']*20),($session['user']['level']*40));
		output('Die Fee gibt dir '.$cash.' Gold!');
		$session['user']['gold']+=$cash;
		break;
	case 7:
	case 8:
	case 9:
		
		$fluch=item_get(' curse>0 AND owner='.$session['user']['acctid'], true);
		
		if ($fluch['id'] > 0)
		{
			output('Die Fee befreit dich von `$'.$fluch['name'].'`G.');
			if ($fluch['name']=='Der Ring')
			{
				$session['user']['maxhitpoints']+=$fluch['value1'];
			}
			
			item_delete(' id='.$fluch['id']);
		}
		else
		{
			output('Die Fee segnet dich.');
			$session['bufflist']['segen2'] = array('name'=>'`GSegen','rounds'=>8,'wearoff'=>'`GDer Segen wirkt nicht mehr.','dmgmod'=>1.1,'roundmsg'=>'`9Der Segen der Grabfee gibt dir Kraft.','activate'=>'offense');
		}
		break;
		default:
		output('Du fühlst dich gut bei dem, was du getan hast.`nDu bekommst mehr Charme!');
		$session['user']['charm']+=e_rand(1,2);
		break;
	}
	$session['user']['specialinc']='';
}
else if ($_GET['op']=='leave')
{
	output('`GIrgendetwas hat diese Person getötet und dieses Etwas könnte immer noch hier sein. Es ist sicherer, schnell zu verschwinden.`n`n ');
	$session['user']['specialinc']='';
}
else if ($_GET['op']=='desecrate')
{
	$session['user']['turns']--;
	output('`GDu durchwühlst die Sachen der Leiche nach etwas Brauchbarem.`n');
	$session['user']['reputation']-=2;
	switch (rand(1,9))
	{
	case 1:
	case 2:
		$gem_gain = e_rand(0,3);
		$gold_gain = e_rand($session['user']['level']*10,$session['user']['level']*20);
		$gemword = 'Edelsteine';
		if ($gem_gain == 1)
		{
			$gemword = 'Edelstein';
		}
		output("Die Leiche zu plündern hat sich bezahlt gemacht! Du hast $gem_gain $gemword und $gold_gain Goldstücke gefunden.`n`n");
		$session['user']['gems']+=$gem_gain;
		$session['user']['gold']+=$gold_gain;
		$session['user']['specialinc']='';
		break;
	case 3:
	case 4:
		output('Du durchsuchst die Leiche nach Reichtum, aber alles, was du finden kannst, sind vermoderte Lumpen und verrostete Waffen. Vielleicht hast du bei der nächsten Leiche mehr Glück.`n`n');
		$session['user']['specialinc']='';
		break;
		default:
		output('Als du die Leiche nach Reichtümern durchwühlst, lenkt eine plötzliche Bewegung deine Aufmerksamkeit auf sich. Du machst einen Satz zurück und siehst, wie sich dieses tote Skelett aufrappelt. Seine leeren Augenhöhlen staren dich mit einem roten Glühen an. Wenn es reden könnte, würde es dich sicher verfluchen, aber schon das kranke Schleifgeräusch von Knochen auf Knochen lässt es dir eiskalt den Rücken runterlaufen.`n`n');
		$badguy = array('creaturename'=>'`$Skelettkrieger`0',
		'creaturelevel'=>$session['user']['level']+1,
		'creatureweapon'=>'Verrostetes Schwert',
		'creatureattack'=>$session['user']['attack']+1,
		'creaturedefense'=>$session['user']['defence']+1,
		'creaturehealth'=>round($session['user']['maxhitpoints']*1.05,0),
		'diddamage'=>0);
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		break;
	}
}
if ($_GET['op']=='run')
{
	if (e_rand(1,5) == 1)
	{
		output('`c`b`&Deine Flucht vor der untoten Bedrohung war erfolgreich!`0`b`c`n');
		$session['user']['reputation']--;
		$session['user']['specialinc']='';
	}
	else
	{
		output('`c`b`$Es gelingt dir nicht, vor der untoten Bedrohung davon zu laufen!`0`b`c');
		$fluch = item_get_tpl(' tpl_id="fldblindh" ');
		$fluch['tpl_name']='`&Skelett`~schatten`0';
		item_add($session['user']['acctid'],0,$fluch);
		$session['user']['specialinc']='';
	}
}
if ($_GET['op']=='fight')
{
	$battle=true;
}
if ($battle)
{
	include('battle.php');
	if ($victory)
	{
		$badguy=array();
		$session['user']['badguy']='';
		output('`n`GNach einem heftigen Kampf hast du den Skelettkrieger besiegt. Du hoffst, dass er vielleicht jetzt endlich seine ewige Ruhe finden wird.`n`n
		Oder wenigstens, bis der nächste Abenteurer auf der Suche nach dem schnellen Geld über seine Leiche stolpert.`n`n');
		if (rand(1,2)==1)
		{
			$gem_gain = e_rand(0,3);
			$gold_gain = e_rand($session['user']['level']*10,$session['user']['level']*20);
			$gemword = 'Edelsteine';
			if ($gem_gain == 1)
			{
				$gemword='Edelstein';
			}
			output("Nach deinem Sieg nimmst du dir, was die deiner Meinung nach zusteht. Du findest $gem_gain $gemword und $gold_gain Goldstücke.`n`n");
			$session['user']['gems']+=$gem_gain;
			$session['user']['gold']+=$gold_gain;
		}
		else
		{
			output('Trotz allem findest du nichts, was die Mühe wert gewesen wäre.`n`n');
		}
		$exp_gain=($session['user']['level']+1)*20;
		output('Du bekommst '.$exp_gain.' Erfahrungspunkte.`n`n');
		$session['user']['experience']+=$exp_gain;
		$session['user']['specialinc']='';
	}
	else if ($defeat)
	{
		$badguy=array();
		$session['user']['badguy']='';
		output('`n`GDu wurdest vom Skelettkrieger besiegt! Doch anstatt im Totenreich aufzuwachen, bist du immer noch am Leben!
		`n`nDu verlierst 2% deiner Erfahrung'.($session['user']['gems']?' und vermisst plötzlich einen deiner Edelsteine':'').'.
		`nAußerdem scheint ein schwerer Fluch vom Skelettkrieger auf dich übergesprungen zu sein. Ob das seine Todesursache war? ');
		if ($session['user']['gems'])
		{
			$session['user']['gems']--;
		}
		$session['user']['hitpoints']=1;
		$session['user']['experience']=round($session['user']['experience']*0.98,0);
		$session['user']['specialinc']="";
		
		$fluch = item_get_tpl(' (curse>0) ');
		
		$buffs .= ($fluch['buff1'] > 0 ? ','.$fluch['buff1'] : '');
		$buffs .= ($fluch['buff2'] > 0 ? ','.$fluch['buff2'] : '');
		
		item_set_buffs('newday',$buffs);
		
		item_add($session['user']['acctid'],0,$fluch);
		
		addnews('`G'.$session['user']['name'].'`G kam dem Tod näher, als '.($session['user']['sex']?'ihr':'ihm').' lieb war.');
	}
	else
	{
		fightnav(true,true);
	}
}
?>
