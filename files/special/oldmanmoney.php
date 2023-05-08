<?php
// found in www
// translation and mods by gargamel @ www.rabenthal.de

if (!isset($session))
{
	exit();
}

$oldm = round($session['user']['level'] * e_rand(5,50));
switch (e_rand(1,2))
{
case 1: // Gold weg
	output('Ein `^alter Mann`0 schlägt dich mit seinem Stock nieder und raubt dich
aus!');
	if ($oldm < $session['user']['gold'] )
	{
		output('`n`QEr erleichtert dich um `$'.$oldm.' Goldstücke.`0');
		$session['user']['gold']-=$oldm;
	}
	else
	{
		output('`n`QEr nimmt dir dein gesamtes Gold.`0');
		$session['user']['gold'] = 0;
	}
	output('`n`nDu könntest heulen! Die Rentner werden auch immer aggressiver!
Zügig setzt du deinen Weg fort, schließlich musst du ja wieder Gold verdienen...`0');
	break;
	
default: // neues Gold
	output('Ein `^alter Mann`0 humpelt auf dich zu und steckt dir `^'.$oldm.' Gold`0 zu!
	`n`nDu freust dich natürlich, fragst dich aber auch, ob du wirklich schon so heruntergekommen aussiehst...');
	$session['user']['gold']+=$oldm;
	break;
}
//abschluss
$session['user']['specialinc'] = '';

?>
