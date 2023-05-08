<?php

/**
Trophäenjagd im Pvp
modifiziert : pvp.php
by Maris (Maraxxus@gmx.de)
**/

// modded by talion fürs neue itemsys
// Gammeltrophäen by Salator (Schalter: negatives $where)

require_once("common.php");
page_header();

//Überblick
if ($_GET['op']=="look"){
	$name=stripslashes(rawurldecode($_GET['who']));
	$who=rawurlencode($name);
	$dks=$_GET['dks'];
	$where=$_GET['where'];
	$id=$_GET['id'];

	output('`3Vor dir liegt `&'.$name.'`3 ausgestreckt auf dem Boden.`nDie Gelegenheit erscheint dir günstig, eine kleine Trophäe von deinem Opfer für deine Sammlung mitzunehmen.`nWas möchtest du dir mitnehmen ?`n');
	addnav('Trophäe');

	// 2 Ohren dieses Spieler bereits vorhanden ?
	if ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='1' AND hvalue='$id'") <=1)
	{
		addnav("Ein Ohr","trophy.php?op=take&who=$who&id=$id&where=$where&nmb=1&dks=$dks");
	}

	// 2 Augen dieses Spielers bereits vorhanden ?
	if ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='2' AND hvalue='$id'") <=1)
	{
		addnav("Ein Auge","trophy.php?op=take&who=$who&id=$id&where=$where&nmb=2&dks=$dks");
	}

	// 2 Hände dieses Spielers bereits vorhanden ?
	if ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='3' AND hvalue='$id'") <=1)
	{
		addnav("Eine Hand","trophy.php?op=take&who=$who&id=$id&where=$where&nmb=3&dks=$dks");
	}

	// 2 Füße dieses Spielers bereits vorhanden ?
	if ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='4' AND hvalue='$id'") <=1)
	{
		addnav("Ein Fuß","trophy.php?op=take&who=$who&id=$id&where=$where&nmb=4&dks=$dks");
	}

	// 2 Beine dieses Spielers bereits vorhanden ?
	if ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='5' AND hvalue='$id'") <=1)
	{
		addnav("Ein Bein","trophy.php?op=take&who=$who&id=$id&where=$where&nmb=5&dks=$dks");
	}

	// 2 Arme dieses Spielers bereits vorhanden ?
	if ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='6' AND hvalue='$id'") <=1)
	{
		addnav("Einen Arm","trophy.php?op=take&who=$who&id=$id&where=$where&nmb=6&dks=$dks");
	}

	// Kopf dieses Spielers bereits vorhanden ?
	if ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='7' AND hvalue='$id'") == 0)
	{
		addnav("Den Kopf","trophy.php?op=take&who=$who&id=$id&where=$where&nmb=7&dks=$dks");
	}

	// Rumpf dieses Spielers bereits vorhanden ?
	if ( item_count("owner=".$session['user']['acctid']." AND tpl_id='trph' AND value2='8' AND hvalue='$id'") == 0)
	{
		addnav("Den Rumpf","trophy.php?op=take&who=$who&id=$id&where=$where&nmb=8&dks=$dks");
	}

	addnav("Nichts");
}
else if ($_GET['op']=="take"){
	$name=stripslashes(rawurldecode($_GET['who']));
	$dks=$_GET['dks'];
	$where=$_GET['where'];
	// $what=$_GET[what];
	$nmb=$_GET['nmb'];
	$id=$_GET['id'];

	switch ($nmb) {
		case 1 :
			$what='Ein '.($where<0?'vergammeltes ':'').'Ohr';
			$crimes='ein '.($where<0?'vergammeltes ':'').'Ohr';
			$msgtext='Du bist heute ein Ausgekochtes Schlitzohr.';
			break;
		case 2 :
			$what='Ein '.($where<0?'vergammeltes ':'').'Auge';
			$crimes='ein '.($where<0?'vergammeltes ':'').'Auge';
			$msgtext='Heute bist du ein guter Kandidat zum Blindekuh spielen.';
			break;
		case 3 :
			$what='Eine '.($where<0?'vergammelte ':'').'Hand';
			$crimes='eine '.($where<0?'vergammelte ':'').'Hand';
			$msgtext='Du fühlst dich heute so Hand-lungsunfähig.';
			break;
		case 4 :
			$what='Ein '.($where<0?'vergammelter ':'').'Fuß';
			$crimes='einen '.($where<0?'vergammelten ':'').'Fuß';
			$msgtext='Gestern hattest du noch Hühneraugen an den Füßen, die sind jetzt weg.';
			break;
		case 5 :
			$what='Ein '.($where<0?'vergammeltes ':'').'Bein';
			$crimes='ein '.($where<0?'vergammeltes ':'').'Bein';
			$msgtext='Wie es scheint stehst du heute nicht mit beiden Beinen im Leben.';
			break;
		case 6 :
			$what='Ein '.($where<0?'vergammelter ':'').'Arm';
			$crimes='einen '.($where<0?'vergammelten ':'').'Arm';
			$msgtext='Du fühlst dich heute einfach Arm.';
			break;
		case 7 :
			$what='Der '.($where<0?'vergammelte ':'').'Kopf';
			$crimes='den '.($where<0?'vergammelten ':'').'Kopf';
			$msgtext='Verlier\' jetzt blos nicht den Kopf!';
			break;
		case 8 :
			$what='Der '.($where<0?'vergammelte ':'').'Rumpf';
			$crimes='den '.($where<0?'vergammelten ':'').'Rumpf';
			$msgtext='Deine Bauchschmerzen von gestern sind auf seltsame Weise verschwunden.';
			break;
	}

	$value=($dks+1)*25;
	output("`3Du machst dich an deine blutige Arbeit...`n$what `3von $name`3 verschwindet kurze Zeit später in deinem Rucksack und du machst dich schnell davon.`n`n`4Dein Ansehen leidet natürlich gewaltig und du fühlst dich nach solch einer Tat auch unattraktiver!`n`n");

	debuglog("verlor 25 Ansehen und 2 Charme wegen Leichenfledderei");
	$session['user']['reputation']-=25;
	$session['user']['charm']=max(0,$session['user']['charm']-2);

	$item['tpl_name'] = db_real_escape_string($what." von ".$name);
	$item['tpl_gold'] = $value;
	$item['tpl_value1'] = $dks;
	$item['tpl_value2'] = $nmb;
	$item['tpl_hvalue'] = $id;
	if($_GET['where']<0)
	{
		$item['tpl_description'] = db_real_escape_string($what." von ".$name."`0. Erworben in einer fiesen Grabschändung.");
	}
	else
	{
		$item['tpl_description'] = db_real_escape_string($what." von ".$name."`0. Erworben in einem fairen Kampf.");
		$sql='SELECT messageid,body FROM mail WHERE msgfrom=0 AND msgto='.$id.' AND subject LIKE "%umgebracht%" ORDER BY messageid DESC LIMIT 1';
		$row=db_fetch_assoc(db_query($sql));
		$newbody=$row['body'].'`n`n`4'.$msgtext;
		$sql='UPDATE mail SET body="'.$newbody.'" WHERE messageid='.$row['messageid'];
		db_query($sql);
	}

	item_add($session['user']['acctid'],'trph',$item);
	addcrimes("`4".$session['user']['name']."`3 schändete die Leiche von `4".$name." `3und nahm `4".$crimes." `3mit.");

}
// Zurück, wohin auch immer
if ($where==1){
	addnav('Zurück zur Kneipe','inn.php');
}
else if ($where==2){
	addnav('Zurück zum Wohnviertel','houses.php?op=einbruch');
}
else if ($where==-1){
	addnav('Zurück zum Friedhof','friedhof.php');
}
addnav('D?Zurück in die Stadt','village.php');

page_footer();
?>
