<?php
/**
 * Waldspecial donation.php
 * Obskure Spendensammler treiben sich im Wald herum. Man kann etwas spenden und Segen bekommen, oder die Kreatur bekämpfen, oder gar nichts tun.
 * lauffähig getestet auf DS-Edition ab 3.42, mit geringen Änderungen sicher auch auf Standard-LotgD portierbar
 * Autor: Salator (salator@gmx.de) an einem öden Sonntagnachmittag im November des Jahres 2008
 */

if(!isset($session)) exit;

//Spendensammler
$collectors=array(
	1=> array('name'=>'Zeuge des Sofas','payfor'=>'den Aufbau einer wundervollen Welt, in der alle Menschen und Tiere in Frieden leben')
	,array('name'=>'Karl Ranseier','nameextra'=>', der wohl erfolgloseste Drachentöter aller Zeiten','payfor'=>'den Kauf einer neuen Drachentöter-Ausrüstung')
	,array('name'=>'Televoting-Agent','payfor'=>'eine Million Goldstücke für den Einmillionsten Spender')
	,array('name'=>'Arthur Dent','payfor'=>'die Planungsänderung einer Umgehungsstraße')
	,array('name'=>'Ritter der Kokosnuss','payfor'=>'neue Kokosnüsse')
	,array('name'=>'Bewohner von Gooncity','payfor'=>'die Vergrößerung von Gooncity')
	,array('name'=>'Gunther von Hagens','payfor'=>'eine Körperwelt')
	,array('name'=>'Dolly Buster','payfor'=>'eine Brustvergrößerung','sex'=>1)
	,array('name'=>'Edentulos','nameextra'=>', der zahnlose Vampir','payfor'=>'Zahnersatz')
	,array('name'=>'Psygnosis','nameextra'=>', König der Lemminge','payfor'=>'den Erwerb von Gefallen bei Ramius')
	,array('name'=>'Jürgen Schneider','payfor'=>'die Modernisierung des Wohnviertels')
	,array('name'=>'Pfadfinderin','payfor'=>'Backzutaten für Pfadfinderinnen-Kekse','sex'=>1)
	,array('name'=>'`$Ramius\' Mechaniker`0','payfor'=>'eine neue Höllenmaschine zum Seelen foltern')
);

if($_GET['op']=='donate') //etwas spenden
{
	$who=(int)$_GET['who'];
	$don=(int)$_GET['don']*100;
	output('Du findest, dass eine `7Spende für '.$collectors[$who]['payfor'].'`0 wirklich eine gute und unterstützenswerte Sache ist und gibst '.$don.' Goldstücke.
	`n`7'.$collectors[$who]['name'].'`0 ist sichtlich erfreut ob deiner Barmherzigkeit und dankt dir wortreich für deine Spende. Bevor sich eure Wege wieder trennen, spricht '.($collectors[$who]['sex']?'sie':'er').' noch einen Segen über dich.');
	$session['user']['gold']-=$don;
	if($don>=2000)
	{
		addnews('`5'.$session['user']['name'].'`2 wurde beobachtet, wie '.($session['user']['sex']?'sie':'er').' ein halbes Vermögen für "`7'.$collectors[$who]['payfor'].'`2" spendete.'); 
	}
	$buffrounds=$_GET['don']*4+15;
	if($session['bufflist']['donator']['rounds']>0)
	{
		$buffrounds+=$session['bufflist']['donator']['rounds'];
	}
	$buff = array('name'=>'`^Spenden-Segen','rounds'=>$buffrounds,'wearoff'=>'`!Der erkaufte Segen wirkt nicht mehr.`0','defmod'=>1.2,'roundmsg'=>'`7Der Segen von `f'.$collectors[$who]['name'].'`7 schützt dich.','activate'=>'roundstart');
	$session['bufflist']['donator']=$buff;
	$session['user']['specialinc']='';
}

elseif($_GET['op']=='fuckup') //nichts spenden
{
	$who=(int)$_GET['who'];
	$session['user']['specialinc']='';
	output('Du erklärst `7'.$collectors[$who]['name'].'`0, dass du kein Gold spenden kannst, weil ');
	switch(e_rand(1,10))
	{
	case 1:
		output('du gerade von einem alten Mann ausgeraubt wurdest.');
		break;
	case 2:
		output('du gerade von einer Diebesbande ausgeraubt wurdest.');
		break;
	case 3:
		output('du deinen Goldbeutel irgendwo im Gestrüpp verloren hast.');
		break;
	case 4:
		output('es dir deine Gesinnung verbietet, Geschäfte im Wald zu tätigen.');
		break;
	case 5:
		output('du schottischer Abstammung bist und schon in frühester Jugend zur Sparsamkeit erzogen wurdest.');
		break;
	case 6:
		output('zu Hause 7 hungrige Bambinis warten.');
		break;
	default:
		output('das Leben in '.getsetting('townname','Atrahor').' so teuer ist.');
		break;
	}
	if($session['user']['gold']<($session['user']['level']*20) && $session['user']['goldinbank']<getsetting('maxinbank','10000'))
	{
		output('`n'.$collectors[$who]['name'].'`0 ist von deiner Armut tief berührt und steckt dir ein paar Goldstücke zu.');
		$session['user']['gold']+=($session['user']['level']*20);
	}
	//den chronisch vollen Bettelstein etwas reduzieren
	$beggar=getsetting('paidgold','0');
	if($beggar>(getsetting('beggarmax','20000')/2))
	{
		savesetting('paidgold',($beggar-300));
	}
}

elseif($_GET['op']=='kill') //die Kreatur bekämpfen
{
	$who=(int)$_GET['who'];
	$targetlevel = $session['user']['level'];
	//alter uncached Query
	//$sql = 'SELECT * FROM creatures WHERE creaturelevel = '.$targetlevel.' ORDER BY rand('.e_rand().') LIMIT 1';
	//$result = db_query($sql);
	//$badguy = db_fetch_assoc($result);
	//cached Query
	
	$arr_creatures = Cache::get(Cache::CACHE_TYPE_MEMORY | Cache::CACHE_TYPE_HDD , 'forestcreatures'.$targetlevel);
			
	if($arr_creatures == false)
	{
		$arr_creatures= db_get_all('SELECT * FROM creatures WHERE creaturelevel = '.$targetlevel);
		Cache::set(Cache::CACHE_TYPE_MEMORY | Cache::CACHE_TYPE_HDD , 'forestcreatures'.$targetlevel, $arr_creatures);
	}
			
	$badguy=$arr_creatures[mt_rand(0,sizeof($arr_creatures)-1)];
	
	$badguy['creaturename']=$collectors[$who]['name'];
	$badguy['creatureweapon']='Spenden-Beutel';
	$badguy['creaturewin']='%w`5 hätte doch nur sagen brauchen, dass %o nichts spenden will...';
	$badguy['creaturelose']='Da könnte ja jeder kommen und betteln!';
	$session['user']['badguy']=createstring($badguy);
	$session['user']['turns']--;
	$session['user']['specialinc']='';
	redirect('forest.php?op=fight');
}

else //Startbild
{
	$who=e_rand(1,count($collectors));
	output('Auf deiner Suche nach Monstern begegnest du einem gar seltsam aussehenden Kauz. Bereit für den Kampf packst du dein '.$session['user']['weapon'].'`0 fester.
	`nDoch '.($collectors[$who]['sex']?'die':'der').' seltsame Fremde hebt '.($collectors[$who]['sex']?'ihre':'seine').' Hand zum Gruß und bedeutet dir auf diese Weise, dass '.($collectors[$who]['sex']?'sie':'er').' keine feindlichen Absichten hat. '.($collectors[$who]['sex']?'Sie':'Er').' stellt sich als `@'.$collectors[$who]['name'].$collectors[$who]['nameextra'].'`0 vor und erzählt dir, dass '.($collectors[$who]['sex']?'sie':'er').' `^Spenden für '.$collectors[$who]['payfor'].'`0 sammelt.
	`nUnd so fragt '.($collectors[$who]['sex']?'sie':'er').' auch dich, ob du ein paar Goldstücke für eine gute Sache übrig hast.
	`n`nWas wirst du tun?');
	$session['user']['specialinc']='donation.php';
	addnav('Armer Schlucker');
	addnav('Nichts spenden','forest.php?op=fuckup&who='.$who);
	addnav('Wohltäter');
	for($i=1;$i<10;$i++)
	{
		if(($i*100)>$session['user']['gold']) break;
		addnav($i.'?Spende '.$i.'00 Gold','forest.php?op=donate&who='.$who.'&don='.$i);
	}
	if($session['user']['gold']>1000)
	{
		addnav('Spende 1000 Gold','forest.php?op=donate&who='.$who.'&don=10');
		if($session['user']['gold']>2000)
		{
			addnav('Proll');
			addnav('Spende 2000 Gold','forest.php?op=donate&who='.$who.'&don=20');
			if($session['user']['gold']>5000)
			{
				addnav('Spende 5000 Gold','forest.php?op=donate&who='.$who.'&don=50');
			}
		}
	}
	addnav('Krieger');
	addnav('b?Die Kreatur bekämpfen','forest.php?op=kill&who='.$who);
}
?>
