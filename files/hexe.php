<?php

// 01072004

/**
* Version:	0.4
* Author:	anpera
* Email:		logd@anpera.de
*
* Purpose:	Additional functions for hard working players
* Program Flow:	The witchhouse appears if a player has 1 or less forest fights left.
*		 In it he can buy additional forest fights or use his last forest fight to get a 'special event'.
*		He also can reset some variables to get more tries for example with flirting or finding the dragon...
*
* Nav added in function forest in common.php:
* if ($session['user'][turns]<=1 ) addnav("Hexenhaus","hexe.php");
*
* in newday.php find: $session['user']['seenlover'] = 0;
* after add: $session['user']['witch'] = 0;
*
* SQL: ALTER TABLE `accounts` ADD `witch` INT( 4 ) DEFAULT '0' NOT null ;
*/

require_once('common.php');
page_header('Hexenhaus');
$wkcost=$session['user']['level']*300;
$spcost=$session['user']['level']*100;
$rowe = user_get_aei('witch, usedouthouse, seenbard, lottery, treepick, gotfreeale');
if ($_GET['op'] == 'wkkauf')
{
	if ($session['user']['gold']<$wkcost)
	{
		output('`!`Z"Du hast gar nicht so viel Gold! `bRAUS HIER!`b"`. Mit diesen Worten trifft dich ein magischer Schlag mit voller Wucht und wirft dich aus der Hütte.`nDu hast ein paar Lebenspunkte verloren.`n`n');
		$session['user']['hitpoints']=round($session['user']['hitpoints']*0.9);
		user_set_aei(array('witch'=>$rowe['witch']+1));
	}
	else
	{
		user_set_aei(array('witch'=>$rowe['witch']+1));
    $session['user']['gold']-=$wkcost;
		$session['user']['turns']++;
		output('`.Du gibst der Hexe`^ '.$wkcost.' `.Gold. Blitzschnell greift sie mit der einen Hand die Kelle im Kessel und mit der anderen deinen Unterkiefer. Mit einem hohen Kichern flöst sie dir drei Portionen dieser braunen Brühe ein. Obwohl du gerade noch unfähig warst, dich aus dem Griff der Hexe zu befreien, fühlst du dich plötzlich wieder stark und bereit, einen weiteren Gegner im Wald zu bekämpfen.`n
		Du blickst dich noch einmal zu der immer noch kichernden Hexe um, aber die beugt sich schon wieder über ihren Punsch, ohne dir weitere Beachtung zu schenken. So gehst du zurück in den Wald.`n`n');
			}
	forest(true);
}
elseif ($_GET['op'] == 'fishturn')
{
	if ($session['user']['gold']<$wkcost)
	{
		output('`Z"Du hast gar nicht so viel Gold! `bRAUS HIER!`b"`. Mit diesen Worten trifft dich ein magischer Schlag mit voller Wucht und wirft dich aus der Hütte.`nDu hast ein paar Lebenspunkte verloren.`n`n');
		$session['user']['hitpoints']=round($session['user']['hitpoints']*0.9);
		user_set_aei(array('witch'=>$rowe['witch']+1));
	}
	else
	{
		$session['user']['gold']-=$wkcost;
		db_query('update account_extra_info set fishturn=fishturn+1 where acctid='.$session['user']['acctid']);
		output('`.Du gibst der Hexe`^ '.$wkcost.' `.Gold. Blitzschnell greift sie mit der einen Hand die Kelle im Kessel und mit der anderen deinen Unterkiefer. Mit einem hohen Kichern flöst sie dir drei Portionen dieser braunen Brühe ein. Obwohl du gerade noch unfähig warst, dich aus dem Griff der Hexe zu befreien, fühlst du dich plötzlich wieder stark und bereit, einen dicken Fisch an Land zu ziehen.`n
		Du blickst dich noch einmal zu der immer noch kichernden Hexe um, aber die beugt sich schon wieder über ihren Punsch, ohne dir weitere Beachtung zu schenken. So gehst du zurück in den Wald.`n`n');
		user_set_aei(array('witch'=>$rowe['witch']+1));
	}
	forest(true);
}
else if ($_GET['op'] == 'besonders')
{
	if ($session['user']['gold']<$spcost)
	{
		output('`Z"Du hast gar nicht so viel Gold! `bRAUS HIER!`b"`. Mit diesen Worten trifft dich ein magischer Schlag mit voller Wucht und wirft dich aus der Hütte.`nDu hast ein paar Lebenspunkte verloren.`n`n');
		$session['user']['hitpoints']=round($session['user']['hitpoints']*0.9);
		user_set_aei(array('witch'=>$rowe['witch']+1));
		forest(true);
	}
	else
	{
		$session['user']['gold']-=$spcost;
		output('`.Du bezahlst die Hexe und sie spricht einen Zauber auf dich, der zugegebenermaßen mehr wie ein Fluch klingt. Dann verlässt du das Hexenhaus gen Wald...`n`n');
		user_set_aei(array('witch'=>$rowe['witch']+1));
		addnav('Zum Wald','forest.php?forest_special=1&op=search');
	}
}
else if ($_GET['op'] == 'verwirren')
{
	output('`.Die Hexe nimmt deinen Edelstein und holt eine Puppe aus einer Truhe in der Ecke, die genauso aussieht wie dein Meister. Sie sticht der Puppe eine krumme, rostige Nadel in den Kopf und sagt: `Z"Gehe ruhig zu deinem Meister. Du hast heute eine zweite Chance, ihn zu schlagen. Es muß aber bald geschehen und bereite dich gut vor!"`.
	`nDu weißt nicht, ob jetzt tatsächlich der Meister oder du selbst verwirrt sein soll. Auf jeden Fall aber hast du wieder den Mut, deinen Meister heute doch noch einmal herauszufordern.`n`n');
	user_set_aei(array('witch'=>$rowe['witch']+1));
	$session['user']['gems']--;
	$session['user']['seenmaster']=0;
	forest(true);
}
else if ($_GET['op'] == 'drachen')
{
	output('`.Du nimmst 3 deiner schwer verdienten Edelsteine und streckst sie der Hexe auf der flachen Hand entgegen. Die Hexe nimmt deine Hand und drückt so fest zu, daß dir schwindelig wird.
	`Z"Hiermit erhältst du die Möglichkeit, erneut eine Heldentat zu vollbringen. Doch diesmal mache es richtig!"`. Sie läßt deine Hand los und die Edelsteine sind verschwunden.`n
	Du kannst deinen letzten Waldkampf jetzt einer Heldentat widmen...`n`n');
	user_set_aei(array('witch'=>$rowe['witch']+1));
	$session['user']['gems']-=3;
	$session['user']['seendragon']=0;
	forest(true);
}
else if ($_GET['op'] == 'flirt')
{
	$flirtcost=max(intval($_GET['cost']),1);
	if($session['user']['gems']>=$flirtcost)
	{
		output('`.Die Hexe nimmt deinen Edelstein und holt eine Puppe aus einer Truhe in der Ecke, die genauso aussieht wie '.($session['user']['sex']?'Seth':'Violet').'. Sie wirft die Puppe in ihren Kessel, rührt ein paar mal um und sagt: 
		`Z"Was erwartest du jetzt von mir? Geh einfach zu deinem Liebhaber und flirte. Du brauchst dazu keinen weiteren Rat einer alten Frau."`.`n`n');
		user_set_aei(array('witch'=>$rowe['witch']+1));
		$session['user']['gems']-=$flirtcost;
		$session['user']['seenlover']=0;
		debuglog($flirtcost.' Edels an die Hexe für nochmal flirten');
	}
	else
	{
		output('`Z"Du hast gar keine '.$gemscost.' Edelsteine! `bRAUS HIER!`b"`. Mit diesen Worten trifft dich ein magischer Schlag mit voller Wucht und wirft dich aus der Hütte.
		`nDu hast ein paar Lebenspunkte verloren.`n`n');
		$session['user']['hitpoints']=round($session['user']['hitpoints']*0.8);
		user_set_aei(array('witch'=>$rowe['witch']+1));
	}
	forest(true);
}
else if ($_GET['op'] == 'blase')
{
	output('`.Die Hexe nimmt deinen Edelstein und lädt dich auf ein Ale ein. Und noch eines. Und noch eines. Nach einer Weile spürst du Druck auf der Blase und denkst, obwohl du schon ziemlich angetrunken bist, daß dich die olle Hexe reingelegt hat und hier gar keine Magie am Werk war... *hic* ...`n`n');
	user_set_aei(array('witch'=>$rowe['witch']+1));
  $session['user']['drunkenness']+=30;
	$session['user']['gems']--;
	user_set_aei(array('usedouthouse'=>0));
	forest(true);
}
else if ($_GET['op'] == 'barde')
{
	output('`Z"Soso, der Barde will nicht mehr für dich singen. Hättest du ihm diesen Edelstein gegeben statt mir, hätte er sicher gesungen. Weißt du was? Ich werde ihm diesen Edelstein vor die Füße zaubern und ihn wissen lassen, daß er von dir ist. So wie ich ihn kenne, steckt er ihn sich in die löchrige Hosentasche und verliert ihn in der Kneipe wieder ... aber was solls."`. Damit legt die Hexe den Edelstein auf den Tisch und schüttet etwas von ihrem Punsch darüber. `Z"Schon gut, du kannst gehen."`. sagt sie noch zu dir und während du dich Richtung Wald umdrehst, siehst du den Edelstein verschwinden... `n`n');
	user_set_aei(array('witch'=>$rowe['witch']+1));
  $session['user']['gems']--;
	user_set_aei(array('seenbard'=>0));
	forest(true);
}
else if ($_GET['op'] == 'lotto')
{
	output('`Z"Nach schnellem Reichtum steht dir der Sinn? Weshalb verpulverst du dann deine Edelsteine auf diese Weise? Nunja, dein alter Lottoschein ist ungültig, du kannst dein Glück nochmal versuchen. Aber jammer mir nicht die Ohren voll, wenn es nicht klappt. Den Edelstein geb ich nicht wieder her!"`.
	Die Hexe wendet sich von dir ab, ohne ein weiteres Wort zu sagen. `n
	Als du dich in Richtung Wald umdrehst, siehst du den Edelstein verschwinden... `n`n');
	user_set_aei(array('witch'=>$rowe['witch']+1));
  $session['user']['gems']--;
	user_set_aei(array('lottery'=>0));
	forest(true);
}
else if ($_GET['op'] == 'freeale')
{
	output('`.Du erzählst der Hexe davon, dass Cedrik dir bei deiner Freiale-Politik einen Strich durch die Rechnung macht. Sie nimmt dir deine 350 Gold ab und sagt: `Z"Jaja, der olle Cedrik. Ich glaube, er hat beim Zwergenweitwurf gerade einen Zwerg an den Schädel bekommen und kann sich nicht mehr an dich erinnern."`. Dabei schnippt sie einen Kieselstein vom Tisch in Richtung einer Puppe, die dir merkwürdig vertraut vorkommt, und trifft sie am Kopf. `Z"So, und jetzt verschwinde, bevor ich mit dir auch sowas mach."`.`n`n');
	user_set_aei(array('witch'=>$rowe['witch']+1));
  $session['user']['gold']-=350;
	user_set_aei(array('gotfreeale'=>0));
	forest(true);
}
else if ($_GET['op'] == 'treeoflife')
{
	output('`Z"'.($session['user']['race']=='elf'?'Aha da haben wir ja einen Baumkuschler.':'Du siehst nicht gerade aus wie ein Baumkuschler.').'
	Aber solange ich meine Edelsteine bekomme solls mir egal sein."`.
	Die Hexe wendet sich von dir ab, buar rva jrvgrerf Jbeg mh fntra. `n
	Als du dich in Richtung Wald umdrehst, siehst du deine Edelsteine verschwinden... `n`n');
	user_set_aei(array('witch'=>$rowe['witch']+1));
  $session['user']['gems']-=3;
	user_set_aei(array('treepick'=>0));
	forest(true);
}
else if ($_GET['op'] == 'cursep')
{
	if ($_GET['id']!='' && $_GET['pid']!='')
	{
		$row = item_get_tpl( ' tpl_id="'.$_GET['id'].'"' );
		$goldcost=$row['tpl_gold']*$session['user']['level'];
		$klappt=e_rand(1,10);
		$count = item_count( ' tpl_id="'.$row['tpl_id'].'" AND owner='.$_GET['pid'] );
		if ( $count )
		{
			output('`.Die Hexe sucht aus einem Regal voller Puppen eine heraus, die wie dein Opfer aussieht. Sie stutzt kurz, dann dreht sie sich zu dir um: `Z"Jaja, es ist alles in Ordnung. Dein Opfer leidet bereits unter '.$row['tpl_name'].'`Z. Behalte dein Geld. Einen schönen Tag noch. Und jetzt ... lass mich alleine."`.');
		  user_set_aei(array('witch'=>$rowe['witch']+1));
        }
		else if ($session['user']['gold']<$goldcost || $session['user']['gems']<$row['tpl_gems'])
		{
			output('`.Als du deine Reichtümer vor der Hexe ausbreitest, musst du leider feststellen, dass du nicht genug hast, um die Hexe zu bezahlen. Du rechnest mit einem Donnerwetter aus Beschimpfungen, doch stattdessen geleitet dich die Hexe erstaunlich ruhig und freundlich zum Ausgang. Du bist verwirrt und lässt es geschehen.`nDoch schon bald sollst du herausfinden, wie die Hexe zu ihrem Geld kommen will: Sie hat den Fluch auf dich gesprochen!');
			user_set_aei(array('witch'=>$rowe['witch']+1));
      item_add( $session['user']['acctid'], 0, $row );
			$session['user']['reputation']--;
		}
		else if ($klappt>=9)
		{
			output('`.HOPPLA! Das ging gewaltig schief. Statt dein Opfer zu treffen, ist der Fluch auf dich gesprungen. Du weißt nicht, ob das Absicht der Hexe war, oder ein Versehen, aber sie verlangt ihren Lohn nicht, während sie dich scheinbar leicht verwirrt aus dem Haus schiebt.');
			user_set_aei(array('witch'=>$rowe['witch']+1));
      item_add( $session['user']['acctid'], 0, $row );
		}
		else
		{
			output('`.Die Hexe sucht aus einem Regal voller Puppen eine heraus, die wie dein Opfer aussieht. Sie legt die Puppe auf den Tisch zwischen euch. Mit einer Hand fährt sie kurz über die Puppe, während sie mit der anderen Hand deine '.$goldcost.' Gold und '.$row['tpl_gems'].' Edelsteine einstreicht. Dann nickt sie dir kurz zufrieden zu und weist dir den Weg zur Tür.`nDein Opfer wird an '.$row['tpl_name'].'`. eine Weile seine Freude haben.');
			user_set_aei(array('witch'=>$rowe['witch']+1));
      $session['user']['gold']-=$goldcost;
			$session['user']['gems']-=$row['tpl_gems'];
			$session['user']['reputation']-=3;
			$row['tpl_gems'] = round($row['tpl_gems']*0.5);
			$row['tpl_gold'] = round($row['tpl_gold']*0.5);
			item_add( $_GET['pid'], 0, $row );
			debuglog('Fluch '.$row['tpl_name'].' auf: ', $_GET['pid']);
			systemmail($_GET['pid'],'`\$Verflucht!`0','`9'.$session['user']['name'].'`9 hat dir den Fluch `T"'.$row['tpl_name'].'"`9 angehext!`n'.$row['tpl_description']);
		}
		$sql = 'UPDATE account_extra_info SET witch=witch+1 WHERE acctid='.$session['user']['acctid'];
		db_query($sql);
		forest(true);
	}
	else if ($_GET['id'] != '')
	{
		$id=$_GET['id'];
		if (isset($_POST['search']) || $_GET['search']>'')
		{
			if ($_GET['search']>'') $_POST['search']=$_GET['search'];
			$search = str_create_search_string($_POST['search']);
			$search="name LIKE '".$search."' AND ";
			if ($_POST['search']=="weiblich") $search="sex=1 AND ";
			if ($_POST['search']=="männlich") $search="sex=0 AND ";
		}
		else
		{
			$search='';
		}
		$ppp=25; // Player Per Page to display
		if (!$_GET['limit']){
			$page=0;
		}
		else
		{
			$page=(int)$_GET['limit'];
			addnav('Vorherige Seite','hexe.php?op=cursep&id='.$id.'&limit=".($page-1)."&search='.$_POST['search']);
		}
		$limit=''.($page*$ppp).','.($ppp+1);
		$sql = 'SELECT login,name,level,sex,acctid FROM accounts WHERE '.$search.' locked=0 AND alive=1 AND acctid<>'.$session['user']['acctid'].' AND lastip<>\''.$session['user']['lastip'].'\' AND dragonkills > 0 ORDER BY login,level LIMIT '.$limit;
		$result = db_query($sql);
		if (db_num_rows($result)>$ppp) addnav('Nächste Seite','hexe.php?op=cursep&id='.$id.'&limit='.($page+1).'&search='.$_POST['search']);
		output("`ZUnd wer darf das Opfer sein?`n`.
		<form action='hexe.php?op=cursep&id=$id' method='POST'>Nach Name suchen: <input name='search' value='$_POST[search]'><input type='submit' class='button' value='Suchen'></form>
		<table cellpadding='3' cellspacing='0' border='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Geschlecht</td></tr>");
		addnav('','hexe.php?op=cursep&id='.$id);
		for ($i=0;$i<db_num_rows($result);$i++)
		{
			$row = db_fetch_assoc($result);
			output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='hexe.php?op=cursep&id=".$id."&pid=".$row['acctid']."'>
			".$row['name']."
			</a></td><td>
			".$row['level']."
			</td><td align='center'><img src='./images/".($row['sex']?"female":"male").".gif'></td></tr>");
			addnav("","hexe.php?op=cursep&id=".$id."&pid=".$row['acctid']."");
		}
		output('</table>');
		addnav('Lieber nicht','hexe.php');
	}
	else
	{
		$result = item_tpl_list_get(' curse=1 OR curse=3 ', ' ORDER BY tpl_name,tpl_gems ASC ');
		output('`.Die alte Hexe reibt sich die knochigen Finger. `Z"Sehr schön, sehr schön! Womit kann ich deinen größten Feind quälen?"`. Sie erzählt dir, welche Flüche sie gerne mal an jemandem ausprobieren würde. 
		`nWähle ein Schicksal für dein Opfer:`n`n`4
		<ul>');
		for ($i=0;$i<db_num_rows($result);$i++)
		{
			$row = db_fetch_assoc($result);
			$goldcost=$row['tpl_gold']*$session['user']['level'];
			output("<li><a href='hexe.php?op=cursep&id=$row[tpl_id]'>$row[tpl_name]</a>`4: ".utf8_htmlentities($row['tpl_description'])."`4`nDauer: ".($row['tpl_hvalue']>0?"".$row['tpl_hvalue']."Tage":"unbegrenzt")."`nPreis: `^$goldcost`4 Gold, `#$row[tpl_gems]`4 Edelsteine.`n`n",true);
			addnav("","hexe.php?op=cursep&id=$row[tpl_id]");
		}
		output('</ul>');
		$sql = 'UPDATE account_extra_info SET witch=witch-1 WHERE acctid='.$session['user']['acctid'];
		db_query($sql);
		addnav('Lieber nicht','hexe.php');
	}
}
else if ($_GET['op'] == 'fluch1')
{
	$result = item_list_get( ' owner='.$session['user']['acctid'].' AND (curse = 2 OR curse = 3) ' );
	output('`.Die alte Hexe murmelt ein paar unverständliche Worte, bevor sie dir mit leicht arroganter Miene wie ein Arzt nach erfolgreicher Diagnose erzählt, was du wieder alles falsch gemacht hast.
	`Z"Also, dich von deinen Übeln zu befreien wird dich eine Kleinigkeit kosten. ');
	for ($i=0;$i<db_num_rows($result);$i++)
	{
		$row = db_fetch_assoc($result);
		output('`n'.$row['name'].' zu entfernen, kostet dich `^'.$row['gold'].' `ZGold und `#'.$row['gems'].'`Z Edelsteine. ');
		if ($row['hvalue']) output('Dieser Fluch hält noch '.$row['hvalue'].' Tage. ');
		addnav($row['name'].' entfernen','hexe.php?op=fluch2&id='.$row['id']);
	}
	output('Wovon soll ich dich befreien?`.\'`n');
	addnav('Vergiss es','forest.php');
}
else if ($_GET['op'] == "fluch2")
{
	$row = item_get( ' id='.$_GET['id'] , false );
	output('`.Die knochigen Finger der Hexe scheinen plötzlich überall an dir zu sein und du fühlst etwas Ekel bei dieser merkwürdigen Behandlung. Aber du hast keine Ahnung, wie man Flüche normalerweise behandelt und hältst deswegen die Klappe.');
	if ($session['user']['gold']<$row['gold'] || $session['user']['gems']<$row['gems'])
	{
		output('`Z"Aha! Dachte ichs mir doch. Ich soll dich von einem Fluch befreien und du willst nicht einmal dafür bezahlen? Scher dich hier raus, bevor ich dir noch einen schlimmeren Fluch dazu hexe!"`.
		`nOhne dich wehren zu können, schwebst du nach draußen und die Tür der Hexenhütte knallt hinter dir ins Schloss. Tja, du hättest vielleicht genug Kleingold mitnehmen sollen.`n`n');
		user_set_aei(array('witch'=>$rowe['witch']+1));
		forest();
	}
	else
	{
		output(' Schließlich scheint die Hexe gefunden zu haben, was sie offenbar gesucht hat, lässt '.$row['gold'].' Gold und '.$row['gems'].' Edelsteine von dir in ihrer Schatzkiste verschwinden und schenkt dir keine weitere Beachtung mehr.
		Gerade, als du den Mund zum Protestieren öffnen willst, fühlst du die Veränderung: `bDer Fluch wurde aufgehoben!`b. Glücklich verlässt du die Hütte der Hexe.');
		item_delete( ' id='.$_GET['id'] );
		user_set_aei(array('witch'=>$rowe['witch']+1));
		$session['user']['gold']-=$row['gold'];
		$session['user']['gems']-=$row['gems'];
		addnav('Zurück in den Wald','forest.php');
	}
}
else
{
	output('`ZD`zu `ob`ee`)t`.rittst das alte Hexenhaus im Wald. Über dem Kaminfeuer hängt ein großer Kessel, in dem eine seltsame braune Flüssigkeit vor sich hin blubbert. Eine typische Hexe, lang und dünn mit langer Hakennase und einem spitzen schwarzen Hut kommt dir grinsend entgegen. ');
	if ($rowe['witch']<getsetting('witchvisits',3))
	{
		output('`n`Z"Na, mein'.($session['user']['sex']?'e Kleine':' Kleiner').'? Hast du dich verlaufen? Oder kann ich sonst etwas für dich tun? Du siehst erschöpft aus! 
		Wenn du mir`^  '.$wkcost.' `Zvon deinem Gold gibst, lasse ich dich von meinem Aufputschpunsch kosten und du könntest noch ein paar Monster mehr erschlagen. ');
		addnav('Waldkampf kaufen','hexe.php?op=wkkauf');
		if ($session['user']['dragonkills']>1) addnav('Angelrunde kaufen','hexe.php?op=fishturn');
		if ($session['user']['turns']>0)
		{
			addnav('Besonderes Ereignis','hexe.php?op=besonders');
			output('Oder du gibst mir `^ '.$spcost.' `Z Gold und ich verspreche dir ein besonderes Ereignis im Wald, sobald du meine Hütte verlässt.');
		}
		addnav('Zurück in den Wald', 'forest.php');
		addnav('Sonstige Hexereien');
		if ($session['user']['seenmaster'] && $session['user']['gems']>0) addnav('Meister verwirren (1 Edelstein)','hexe.php?op=verwirren');
		if ($session['user']['seendragon'] && $session['user']['gems']>2 && $session['user']['turns']>0 && $session['user']['level']>=15) addnav('Erneute Heldentat (3 Edelsteine)','hexe.php?op=drachen');
		if ($session['user']['seenlover'] && $session['user']['gems']>0)
		{
			if($rowe['witch']==0)
			{
				addnav('Nochmal flirten (1 Edelstein)','hexe.php?op=flirt&cost=1');
			}
			else
			{
				addnav('Nochmal flirten (2 Edelsteine)','hexe.php?op=flirt&cost=2');
			}
			
		}
		if ($rowe['usedouthouse'] && $session['user']['gems']>0) addnav('Druck auf die Blase (1 Edelstein)','hexe.php?op=blase');
		if ($rowe['seenbard'] && $session['user']['gems']>0) addnav('Bardenhals befeuchten (1 Edelstein)','hexe.php?op=barde');
		if ($rowe['lottery'] && $session['user']['gems']>0) addnav('Nochmal Lotto (1 Edelstein)','hexe.php?op=lotto');
		if ($rowe['treepick'] && $session['user']['gems']>3) addnav('Baum besuchen (3 Edelsteine)','hexe.php?op=treeoflife');
		if ($rowe['gotfreeale'] && $session['user']['gold']>350) addnav('Cedrik verwirren (350 Gold)','hexe.php?op=freeale');
		output('"`.');
		if (item_count(' (curse=2 OR curse=3) AND owner='.$session['user']['acctid'] , true )>0)
		{
			output(' Sie macht eine kurze Pause, als ob sie etwas an deiner Erscheinung stören würde, und fährt dann fort: `Z"Ich spüre, es liegt ein `$Fluch`Z auf dir. Soll ich dir dabei behilflich sein, diesen Fluch los zu werden?"`.');
			addnav('Fluch beseitigen','hexe.php?op=fluch1');
		}
		if ($session['user']['playerfights']>0 && $session['user']['gems']>1)
		{
			addnav('Spieler verfluchen','hexe.php?op=cursep');
			output('`n`.Die Hexe bietet dir mehr nebenbei noch an, für Edelsteine jemanden für dich zu verfl`)u`ec`oh`ze`Zn.');
		}
	}
	else
	{
		output('`n`Z"Hey mein'.($session['user']['sex']?'e Kleine':' Kleiner').', du gehst mir auf die Nerven! Hast du mich heute nicht schon oft genug gestört? Mach dass du fort kommst und wage es nicht, heute nochmal zu kommen."`. 
		Das war deutlich genug für dich.');
		addnav('Zurück in den Wald', 'forest.php');
	}
}
page_footer();
?>
