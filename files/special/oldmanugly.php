<?php
if (!isset($session)) exit();
if ($session['user']['charm']>0)
{
	if(e_rand(1,3)==3)
	{
		output("Du steigst! Ohhhh! Direkt in ein riesiges Schlammloch! Du bist von oben bist unten mit Schlamm bedeckt!");
	}
	else
	{
		output("`^Ein alter Mann schlägt dich mit einem hässlichen Stock, kichert und rennt davon!`n`nDu `%verlierst einen`^ Charmepunkt!`0");
	}
	$session['user']['charm']--;
}
else
{
  output("`^Ein alter Mann trifft dich mit einem hässlichen Stock und schnappt nach Luft, als der Stock `%einen Charmepunkt verliert`^.  Du bist noch hässlicher als dieser hässliche Stock!`0");
}
?>
