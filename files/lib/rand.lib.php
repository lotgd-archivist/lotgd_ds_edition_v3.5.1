<?php

function make_seed() 
{
	list($usec, $sec) = explode(' ', microtime());
	//return (float) $sec + ((float) $usec * 100000);
	return (float) $sec + ((float) $usec * rand(1,1000000));
}
mt_srand(make_seed());

// 
/**
 * Zufallsgenerator: Gibt Zufallswert zurück
 * modded by talion: checks for datatype and behaves as it should
 *
 * @param float $min Beginn
 * @param float $max Ende
 * @param bool $bool_bell_rand Soll bei der Wahrscheinlichkeitsverteilung 
 * eine Glockenkurve (true) oder Normalverteilung (false) genutzt werden
 * @return float Zufallszahl
 */
function e_rand($min=false,$max=false, $bool_bell_rand = true)
{
	if($bool_bell_rand === false)
	{
		mt_rand($min,$max);
	}
	
	if ($min===false) return mt_rand();
	if ($max===false) return mt_rand($min);
	
	$float = false;
	//if(is_float($min) || is_float($max)) {
	//dieser Vergleich ist scheinbar schneller als is_float und macht die float-Routine nur wenn wirklich float-Werte übergeben werden
	if($min>(int)$min || $max>(int)$max) {
		$min*=1000;
		$max*=1000;
		$float = true;
	}
	
	if ($min<$max)
	{
		$ret = mt_rand($min,$max);
	}
	else if($min>$max)
	{
		$ret = mt_rand($max,$min);
	}
	else
	{
		$ret = $min;
	}
	
	if($float)
	{
		$ret = round($ret/1000,3);
	}
	return $ret;
}

?>
