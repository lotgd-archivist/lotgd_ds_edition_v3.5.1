<?php

// Der Tierstall aus dem Dragonslayer-Gildensystem als Specialevent.
// Diese Datei ist ohne das DS-Gildensystem nicht sinnvoll einsetzbar, da Änderungen am Wald und im Schloss nötig sind.
// Idee und Anpassung des Originalcodes by Salator

/** @noinspection PhpUndefinedVariableInspection */
$lvl=($session['user']['dragonkills']>10?3:2);
//dg_show_header('Der Tierstall ('.$dg_build_levels[$lvl].')');

/** @noinspection PhpUndefinedVariableInspection */
$arr_names = $guild['building_vars']['stall']['names'];
$arr_names=array();
$arr_animals = array(
	'goldschaf' => array('name'=>(!empty($arr_names['goldschaf']) ? $arr_names['goldschaf'] : '`^Goldschaf`0'),'minlvl'=>1,'desc'=>'Das `^Goldschaf `& sucht für dich im Wald nach zusätzlichen Goldvorkommen. "Mähhh"','goldprice'=>399,'gemprice'=>0,'effectmsg'=>'`^Dein Goldschaf scharrt mit seinen Hufen gut verborgene Münzen frei!`0','wearoff'=>'`^Dein Goldschaf trabt blökend davon.`0','rounds'=>30,'goldfind'=>1.7,'oname'=>'Schaf')
	,'beutegeier' => array('name'=>(!empty($arr_names['beutegeier']) ? $arr_names['beutegeier'] : '`6Beutegeier`0'),'minlvl'=>2,'desc'=>'Der `6Beutegeier`& rafft Beutestücke an sich. "Krrrrr"','goldprice'=>999,'gemprice'=>2,'effectmsg'=>'`6Dein Beutegeier pickt mit einem heiseren Krächzen auf deinem Gegner herum, um ihm ein Beutestück zu entlocken!`0','wearoff'=>'`6Dein Beutegeier schwingt sich schwerfällig in die Lüfte.`0','rounds'=>20,'failmsg'=>'`6Leider ist der Beutegeier erfolglos..`0','cname'=>$arr_names['beutegeier'],'oname'=>'Geier')
	,'gemelster' => array('name'=>(!empty($arr_names['gemelster']) ? $arr_names['gemelster'] : '`7Edelsteinelster`0'),'minlvl'=>3,'desc'=>'Die `7Edelsteinelster`& ist ein raffiniertes Biest, das im verlassenen Schloß noch den kleinsten Glitzer aufspürt.','goldprice'=>1199,'gemprice'=>2,'effectmsg'=>'`7Deine Edelsteinelster hüpft mit einem Glitzern in den Augen herum und hält Ausschau nach Gemmensteinen!`0','wearoff'=>'`7Deine Edelsteinelster flattert davon.`0','rounds'=>10,'failmsg'=>'`6Leider ist die Elster erfolglos..`0','cname'=>$arr_names['gemelster'],'oname'=>'Elster')
	);

if($_GET['act'] == 'get') // Tier mitnehmen
{
	$str_animal = $_GET['animal'];
	$ok = true;
	output('`&Neugierig zeigst du auf '.$arr_animals[$str_animal]['name'].'`&.');
	foreach($arr_animals as $animal => $a)
	{
		if($session['bufflist'][$animal])
		{
			output('`nDoch der Alte wehrt ab: `$'.$a['name'].'`$ und '.$arr_animals[$str_animal]['name'].'`$ gleichzeitig, das würde deine Fähigkeiten als Hirte übersteigen!');
			$ok = false;
		}
	}
	if($session['user']['gold'] < $arr_animals[$str_animal]['goldprice'] || $session['user']['gems'] < $arr_animals[$str_animal]['gemprice']) {
		output('`n`$Beschämt musst du feststellen, dass deine Besitztümer nicht ausreichen, um den Obulus für '.$arr_animals[$str_animal]['name'].'`$ bezahlen zu können!');
		$ok = false;
	}
	if($ok)
	{
		output(' Kurz darauf führst du deinen Begleiter an einer langen, reißsicheren Leine in den Wald.');
		$session['user']['gold'] -= $arr_animals[$str_animal]['goldprice'];
		$session['user']['gems'] -= $arr_animals[$str_animal]['gemprice'];
		$session['bufflist'][$str_animal] = $arr_animals[$str_animal];
	}
	$session['user']['specialinc']='';
}

else if($_GET['act']=='return') //special verlassen
{
	output('`5Du hast Besseres zu tun, als komische Tiere auszuführen. Also verabschiedest du dich von dem Hirten und gehst deines Weges.');
	$session['user']['specialinc']='';
}

else // Startbildschirm
{
	$session['user']['specialinc']='herdsman.php';
	output('`5Du gelangst zu einer Wiese, auf der ein alter Hirte seine Herde weiden lässt. Während du deinen Blick über die Herde schweifen lässt, bemerkst du, dass hier nicht nur gewöhnliche Schafe zu finden sind, sondern auch einige seltene Exemplare der Tierwelt.
	`nDer Hirte bietet dir an, für einen kleinen Obulus eines dieser ungewöhnlichen Tiere mitzunehmen.');
	foreach($arr_animals as $str_animal => $arr_info)
	{
		if($session['bufflist'][$str_animal])
		{
			//addnav($arr_info['name'].' zurückbringen!`0','forest.php?act=drop&animal='.$str_animal);
		}
		if($lvl >= $arr_info['minlvl'])
		{
			output('`n`n`&'.$arr_info['desc'].'`&');
			if($session['bufflist'][$str_animal])
			{
				output('`n`&Du selbst hast '.$arr_info['name'].'`& noch bei dir.');
			}
			else
			{
				$link = 'forest.php?act=get&animal='.$str_animal;
				addnav($arr_info['oname'].' mitnehmen',$link);
				addnav('',$link);
				output('`n'.$arr_info['name'].'`& <a href="'.$link.'">mitnehmen</a> ('.$arr_info['goldprice'].' Gold'.($arr_info['gemprice']>0 ? ' '.$arr_info['gemprice'].' Edelsteine' : '').') !`n',true);
			}
		}
	}	// END foreach
	addnav('Zurück in den Wald','forest.php?act=return');
}	// END wenn keine aktion

?>
