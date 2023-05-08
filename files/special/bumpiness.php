<?php
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ( $session['user']['hashorse']>0 )
{
	if ( $session['bufflist']['mount']['rounds'] > 0 )
	{
		$keep = e_rand(10,33)/100;
        /** @noinspection PhpUndefinedVariableInspection */
        output('`QVerflucht!`0 Auf dem Streifzug durch den Wald ist dein '.$playermount['mountname'].' offenbar in ein `8Loch`0 getreten. Vermutlich war es der Eingang zu einem Hasenbau.
		`n`nDu hast Mitleid mit deinem humpelnden Tier, dass durch seine Verletzung `Qerheblich an Kraft verloren`0 hat.`0');
		//die sache mit dem buff
		$session['bufflist']['mount']['rounds'] = round($session['bufflist']['mount']['rounds']*$keep);
		if ( $session['bufflist']['mount']['rounds'] == 0 ) $session['bufflist']['mount']['rounds'] = 1;
	}
	else
	{
		output('Auf deinem Streifzug durch den Wald trittst du in ein `1Loch`0, das du übersehen hast. Vermutlich der Eingang zu einem Hasenbau.
		`n`8Du verstauchst dir den Fuß und solltest den Heiler aufsuchen.`0 Er wird deinen
		Gesundheitsverlust mit edlen Kräuterzubereitungen ausgleichen können.`0');
		$session['user']['hitpoints'] = round($session['user']['hitpoints']*0.95);
	}
}
else
{ // kein Pferd
	output('Auf deinem Streifzug durch den Wald trittst du in ein `1Loch`0, das du übersehen hast. Vermutlich der Eingang zu einem Hasenbau.
	`n`8Du verstauchst dir den Fuß und solltest dringend den Heiler aufsuchen.`0 Er wird deinen Gesundheitsverlust mit edlen Kräuterzubereitungen ausgleichen können.`0');
	$session['user']['hitpoints'] = round($session['user']['hitpoints']*0.85);
}
?>