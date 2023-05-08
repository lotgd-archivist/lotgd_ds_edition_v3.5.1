<?php
/**
* Version:	0.6
* Date:		July 31, 2003
* Author:	John J. Collins
* Email:	collinsj@yahoo.com
*
* Purpose:	Provide a fun module to Legend of the Green Dragon
* Program Flow:	The player can choose to use the Private or Public Toilet. It costs Gold
* to use the Private Toilet. The Public Toilet is free. After using one of the toilet's,
* the players can wash their hands or return. If they choose to wash their hands, there is a
* chance that they can get their gold back. If they don't choose to wash their hands, there
* is a chance that they will lose some gold. If they loose gold there is an entry added
* to the daily news.
*/
require_once("common.php");

// How much does it cost to use the Private Toilet?
$cost = 5;
// How much gold must user have in hand before they can lose money
$goldinhand = 1;
// How much gold to give back if the player is rewarded for washing their hands
$giveback = 3;
// How much gold to take if the user is punished for not washing their hands
$takeback = 1;
// Minium random number for good habits
$goodminimum = 1;
// Maximum randdom number for good habits
$goodmaximum = 10;
// Odds of getting your money back
$goodmusthit = 6;
// Minimum random number for bad habits
$badminimum = 1;
// Maximum random number for bad habits
$badminimum = 4;
// Odds of losing money
$badmusthit = 2;
// Turn on to give the player a chance of finding a Gem if they visit the Private Toilet and Wash their hands.
// Turn on = 1
// Turn off = 0
$giveagem = 1;
// Give a gem if you visit the pay toilet and wash your hands. 1 in 4 chance of getting the gem.
// How often do you want to give out a Gem?
// Default is 1 out of 4 odds.
$givegempercent = 25;
$gemminimum = 1;
$gemmaximum = 100;
// Do you want to give the player a turn if they use the Pay Toilet and wash their hands.
// 1 give a turn
// 0 does not give an extra turn
$giveaturn = 0;
// Where do you want the player to go after leaving here?
// Usually this is the forest, you don't want no stinking toilet in the village do you, but can be anywhere.
$returnto = "forest.php";
// Does the player have enough gold to use the Private Toilet?


//You should really not have to edit anything below this line!
if ($session['user']['gold'] >= $cost)
{
	$canpay = true;
}

if ($_GET['op'] == 'pay')
{
	page_header('Private Toilette');
	user_set_aei(array('usedouthouse' => 1));
	output('`SD`Tu `;bezahlst die '.$cost.' Gold an den Klo-Gnom für die Erlaubnis, das private Klo zu benutzen.`n
	Dies ist das sauberste Plumpsklo im ganzen Land!`n
	Der Klopapier-Gnom sagt dir noch, dass du einfach fragen sollst, wenn du noch etwas brauchst.`n
	'.($session['user']['sex']?'Sie':'Er').' dreht dir höflich '.($session['user']['sex']?'ihren':'seinen').' Rücken zu und schließt die Reinigung des Waschstandes `Ta`Sb.`n`n');
	$session['user']['gold'] -= $cost;
	addnav('Hände waschen', 'outhouse.php?op=washpay');
	addnav('Verlassen', 'outhouse.php?op=nowash');
}
else if ($_GET['op'] == 'free')
{
	page_header('Öffentliche Toilette!');
	user_set_aei(array('usedouthouse' => 1));

	spc_get_special('outhouse',70,'',array('op'));

	output('`SD`Te`;r furchtbare Gestank treibt dir Tränen in die Augen und deine Nasenhaare kräuseln sich!`n
	Nachdem er sich die Nase damit geputzt hat, überreicht dir der Klopapier-Gnom ein Blatt einlagiges Klopapier.`n
	Du entschliesst dich, dieses Teil lieber nicht zu benutzen, nachdem du seine Hände gesehen hast.`n`n
	Beinahe rutschst du in das große Loch in der Mitte des Raumes, während du '.($session['user']['sex']?'darüber in die Hocke gehst':'dich darüber stellst').'. Der Klopapier-Gnom beobachtet dich bei deinem Geschäft sehr genau.`n
	Du machst so schnell du kannst, denn so arg lange kannst du die Luft nicht anhalt`Te`Sn.`n');

	addnav('Hände waschen', 'outhouse.php?op=washfree');
	addnav('Verlassen', 'outhouse.php?op=nowash');
}
else if ($_GET['op'] == 'washpay'|| $_GET['op'] == 'washfree')
{
	page_header('Wasch-Stand');
	output('`;Hände waschen ist immer eine gute Sache. Du machst dich zurecht, bringst dein(e/n) '.$session['user']['armor'].'`; in Ordnung und betrachtest dein Spiegelbild im Wasser. Dann machst du dich wieder auf den Weg.`0`n');
	$goodhabits = e_rand($goodminimum, $goodmaximum);
	if ($goodhabits > $musthit && $_GET['op']=='washpay')
	{
		output('`^Die Waschraum-Fee segnet dich!`n');
		if (e_rand(1,2)==1)
		{
			output('`;Du bekommst `^'.$giveback.' `;Gold für Hygiene und Sauberkeit!`0`n');
			$session['user']['gold'] += $giveback;
		}
		else
		{
			// idea by metatron-sama, coded by anpera
			output('`7Deine Verteidigung steigt!`0`n');
			$session['bufflist']['segen'] = array('name'=>'`9Segen','rounds'=>8,'wearoff'=>'Der Segen der Waschraumfee wirkt nicht mehr.','defmod'=>1.1,'roundmsg'=>'`9Der Segen der Waschraumfee schützt dich.','activate'=>'offense');
		}
		if ($giveagem == 1)
		{
			$givegemtemp = e_rand($gemminimum, $gemmaximum);
			if ($givegemtemp <= $givegempercent)
			{
				$session['user']['gems']++;
				//debuglog("gained 1 gem in the outhouse");
				output('`&Bist du nicht ein Glückspilz? Du findest einen Edelstein beim Eingang!`0`n');
			}
			if ($giveaturn == 1)
			{
				$session['user']['turns']++;
				output('`&Du hast eine Extrarunde erhalten!`0`n');
			}
			if ($session['user']['drunkenness']>0)
			{
				$session['user']['drunkenness'] *=0.9;
				output('`&Du verlässt das Klohäuschen und fühlst dich etwas nüchterner!`n`0');
			}
		}
		if($session['user']['dragonkills']>9 && e_rand(1,100)<10 && file_exists('./special/towel.php')) //Gag-Special
		{
			$session['user']['specialinc']='towel.php';
		}
	}
	else if ($goodhabits > $musthit && $_GET['op'] == "washfree")
	{
		if (e_rand(1, 3)==1)
		{
			output('`7Du bemerkst einen kleinen Beutel mit `^'.$giveback.' `7Gold, den hier wohl jemand vergessen hat.`0');
			$session['user']['gold'] += $giveback;
		}
	}
	forest(true);
}
else if (($_GET['op'] == 'nowash'))
{
	page_header('Stinkende Hände');
	output('`;Deine Hände sind schmutzig und stinken!`n
	Hat dir deine Mutter denn gar nichts beigebracht?`n');
	$takeaway = e_rand($badminimum, $badmaximum);
	if ($takeaway >= $badmusthit)
	{
		if ($session['user']['gold'] >= $goldinhand)
		{
			$session['user']['gold'] -= $takeback;
			//debuglog("lost $takeback gold in the outhouse for not washing");
			output('`nDer Klopapier-Gnom hat dich auf den schleimigen, verdreckten Boden geschmissen und dir '.$takeback.' Goldstück'.($takeback > 1?'e':'').' für deine Schlampigkeit abgenommen!`n');
		}
		output('Bist du nicht auch froh, dass peinliche Momente wie dieser nicht in den News stehen?`n');
		// $session['user']['donation']+=1;
		addnews('`2Cool, '.($session['user']['name']).' `2lief mit einem langen Stück Klopapier an '.($session['user']['sex']?'ihrem':'seinem').' Fuß herum.');
	}
	forest(true);
}
else
{
	page_header('Die Klohäuschen');

	$rowe = user_get_aei('usedouthouse');

	if ($rowe['usedouthouse'] == 0)
	{
		output('`SD`Ti`;e Stadt verfügt über 2 Klohäuschen, die wegen der monsterabwehrenden Wirkung des Gestanks etwas ausserhalb im Wald stehen.`n`nIn typischer Klassenmanier gibt es ein bevorzugtes und ein heruntergekommenes Häuschen. Du hast die Wa`Th`Sl!`0`n`n');
		addnav('Toiletten');
		if ($canpay)
		{
			addnav('Private Toilette: ('.$cost.' Gold)', 'outhouse.php?op=pay');
			addnav('','outhouse.php?op=pay');
		}
		else
		{
			output('`;Die private Toilette kostet `^'.$cost.' `;Gold. Sieht so aus. als ob du es entweder aushalten, oder die öffentliche Toilette benutzen musst!');
		}
		addnav('Öffentliche Toilette (kostenlos)', 'outhouse.php?op=free');
		addnav('','outhouse.php?op=free');
		addnav('Aushalten', 'forest.php');
		if (getsetting('dailyspecial','Keines')=='Orkburg')
		{
			addnav('Zur Orkburg','paths.php?ziel=castle');
			output('`SNeben den Klohäuschen findest du, halb von Gras bewachsen, einen uralten, abgebrochenen Wegweiser am Boden. Er scheint aber noch immer in die Richtung zu zeigen, für die er einst gedacht war. Die Aufschrift lautet \'`tOrkburg`;\'. Du könntest diese Chance nutzen...');
		}
		if ($session['user']['prefs']['noimg']==0)
		{
			output('<table border="0" width="100%" cellpadding="3" cellspacing="8"><tr><td align="left">
			'.($canpay?'<a href="outhouse.php?op=pay"><img src="./images/outhouse_clean.jpg" title="Private Toilette" border="0"></a>':'').'
			</td><td align="right">
			<a href="outhouse.php?op=free">
			<img src="./images/outhouse_dirty.jpg" title="Öffentliche Toilette" border="0">
			</a></td></tr></table>');
		}
	}
	else
	{
		output('`SD`Ti`;e Stadt verfügt über 2 Klohäuschen, die wegen der monsterabwehrenden Wirkung des Gestanks etwas ausserhalb im Wald steh`Te`Sn.`n`n');
		switch (e_rand(1,4))
		{
		case 1:
			output('Die Klohäuschen sind wegen ... Reparaturarbeiten ... geschlossen.`nDu wirst es bis morgen aushalten müssen!');
			break;
		case 2:
			output('Als du dich den Plumpsklos näherst, erkennst du, dass du den Gestank heute nicht noch einmal aushalten kannst.');
			break;
		case 3:
			output('Du hast wirklich nichts mehr in dir, was du heute noch ablassen könntest!');
			break;
			default:
			output('Vor den Klohäuschen herrscht gerade sehr großer Andrang. Du beschließt, bis morgen zu warten.');
		}
		output('`n`n`;Du kehrst in den Wald zurück.`n`n`0');
		forest(true);
	}
}
page_footer();

?>
