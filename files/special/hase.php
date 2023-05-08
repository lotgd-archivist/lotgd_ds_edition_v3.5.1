<?php
/*
verletztes Häschen
by Vaan
18//12//2004
1.9.08 Erweiterung by Salator für DS-Edition: Man kann das Tier auch ins Inventar packen
	Charmepunkte gestaffelt
*/

if (!isset($session))
{
	exit();
}

if ($_GET['op']=='help')
{
	if($session['user']['charm']<55) $cp=5;
	elseif($session['user']['charm']<125) $cp=4;
	elseif($session['user']['charm']<250) $cp=3;
	elseif($session['user']['charm']<1550) $cp=2;
	else $cp=1;
	output('`2Du nimmst das Häschen und fängst an es zu verarzten. Als Du fertig bist hoppelt das Häschen vergnügt zurück in den Wald.
	`^Da du solange gebraucht hast, verlierst Du einen Waldkampf.
	`n`n`&Diese ehrenhafte Tat wird mit '.$cp.' Charmepunkt'.($cp>1?'en':'').' belohnt.');
	$session['user']['charm']+=$cp;
	$session['user']['turns']--;
	$session['user']['specialinc']='';
}

else if ($_GET['op']=='take')
{
	output('`2Du machst natürlich genau das, was alle Jäger mit einem Hasen machen: Du ziehst ihm das Fell über die Ohren.
	Dass Du diesen Hasen nicht selbst erlegt hast und er krank gewesen sein könnte stört dich nicht, du packst ihn einfach in deinen Beutel.');
	$item=array('tpl_gold' => e_rand(20,100),'tpl_hvalue' => e_rand(20,50));
	item_add($session['user']['acctid'],'toterhase',$item);
	$session['user']['specialinc']='';
}

else if ($_GET['op']=='gehe')
{
	output('`2Da Dich so ein kleiner Hase nicht interessiert, gehst du einfach weiter.
	`n`n`&Diese unehrenhafte Tat wird mit einem Abzug von 3 Charmepunkten bestraft.');
	$session['user']['charm']=max(0,$session['user']['charm']-3);
	$session['user']['specialinc']='';
}

else //if ($_GET['op']=='')
{
	$session['user']['specialinc']='hase.php';
	output('`2Als Du so über den Waldweg streifst, siehst Du ein verletztes Häschen auf dem Boden liegen.
	`nWas willst Du machen?');
	addnav('Das Häschen verarzten','forest.php?op=help');
	addnav('m?Das Häschen mitnehmen','forest.php?op=take');
	addnav('Einfach weiter gehen','forest.php?op=gehe');
}
?>
