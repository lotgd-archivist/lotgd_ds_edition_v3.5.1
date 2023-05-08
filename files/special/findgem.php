<?php

// idea with ape by manweru
// coding by anpera
// bugfix by talion

switch(e_rand(1,3))
{
	case 1:
	output('`^Das Glück lächelt dich an. Du findest einen Edelstein!`0');
    /** @noinspection PhpUndefinedVariableInspection */
    $session['user']['gems']++;
	break;
	case 2:
	output('`^Du hörst ein lautes Kreischen und spürst einen leichten Ruck in der Nähe deiner Edelsteinsammlung. ');
    /** @noinspection PhpUndefinedVariableInspection */
    if ($session['user']['gems']>0)
	{
		$session['user']['gems']--;
		output('Kurz darauf siehst du ein Äffchen mit einem deiner Edelsteine im Wald verschwinden.`0');
	}
	else
	{
		output('Glücklicherweise hast du keine Edelsteine dabei und machst dir darum auch keine Sorgen wegen des Äffchens, das scheinbar enttäuscht zurück in den Wald läuft.`0');
	}
	break;
	case 3:
	output('`^Ein kleines Äffchen wirft dir einen Edelstein an den Kopf und verschwindet im Wald. Du verlierst ein paar Lebenspunkte, aber der Edelstein lässt dich den Ärger darüber vergessen.`0');
    /** @noinspection PhpUndefinedVariableInspection */
    $session['user']['gems']++;
	$session['user']['hitpoints']*=0.9;
	break;
}
$session['user']['specialinc'] = '';

?>
