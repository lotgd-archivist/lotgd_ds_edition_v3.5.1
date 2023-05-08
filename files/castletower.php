<?php
//ein Notausgang aus dem Schloss by Salator
function restore_buffs()
{
	global $session,$playermount;
	if (!is_array($session['bufflist']) || (count($session['bufflist']) <= 0) || (is_array($session['bufflist']['decbuff'])))
	{
		if (is_array($session['bufflist']['decbuff']))
		{
			$decbuff=$session['bufflist']['decbuff'];
		}
		$session['bufflist'] = utf8_unserialize($session['user']['buffbackup']);
		if (is_array($decbuff))
		{
			$session['bufflist']['decbuff']=$decbuff;
		}
		if (!is_array($session['bufflist']))
		{
			$session['bufflist'] = array();
		}
	}
	if ($session['user']['hashorse']>0)
	{
		output('`n`2Dein '.$playermount['mountname'].'`2 begrüsst Dich erfreut.`n');
	}
}

require_once 'common.php';
checkday();
page_header('Schlossturm');
if($_GET['op']=='')
{
	output('`2Plötzlich kommen dir Zweifel ob es wirklich die richtige Entscheidung war, ins verlassene Schloss zu gehen. Hilflos schaust du dich um und entdeckst eine metallbeschlagene Eichentür. Du drückst auf die Klinke, die Tür ist unverschlossen. Könnte das der Weg nach draußen sein?`n`nIm Dämmerlicht erblickst du eine Treppe nach oben. Tja, die wird wohl kaum ins Stadtzentrum führen und Schätze findest du hier sicher auch keine.');
	if($_GET['choose']==1)
	{
		addnav('Zurück ins Schloss','abandoncastle.php?loc=6');
	}
	else
	{
		addnav('Zurück in den Garten','gardenmaze.php?init=1');
	}
	addnav('Treppen hochsteigen','castletower.php?op=goon');
}
elseif($_GET['op']=='goon')
{
	output('`2Du steigst die vielen Stufen des alten Turmes hinauf.`n
	Plötzlich bleibst du ganz ruhig stehen. War da nicht ein Schlurfen hinter dir? Du lauschst, und tatsächlich, irgendetwas schlurft die Treppen hoch. Du glaubst ein Flüstern zu hören: "`^Töötet sie! Töötet sie!`2" Von Entsetzen gepackt rennst du weiter.`n
	Als die Treppe endet gelangst du zu einer Plattform mit 2 vollkommen gleich aussehenden schweren Holztüren. Du hast keine Zeit lange zu überlegen und rennst du durch die linke Tür in das dahinterliegende Turmzimmer. Laut dröhnend lässt du die Tür hinter dir ins Schloss fallen.`n`n
	Du siehst dich um. In diesem Raum steht nichts weiter als eine alte Truhe. Außerdem bemerkst du dass die Tür auf der Innenseite keine Klinke hat. Sofort wird dir deine Situation klar: `$Du bist eingeschlossen. Es gibt für dich aus dem etwa 30 Meter hoch gelegenen Turmzimmer keinen Ausweg mehr.`n`n
	`2Vor der Tür hörst du die schlurfenden Schritte immer näher kommen, jeden Augenblick könnte die Tür aufgehen und die schreckliche Gestalt eintreten.`nWas nun?');
	addnav('T?Die Truhe untersuchen','castletower.php?op=chest');
	addnav('F?`Blick aus dem Fenster','castletower.php?op=window');
	addnav('S?Erwarte dein Schicksal','castletower.php?op=doom');	
}
elseif($_GET['op']=='chest')
{
	output('`2In der Truhe liegen jahrhundertealte, von Motten angefressene Kleider. Jedoch kannst du nichts finden was dir in deiner Situation hilfreich sein könnte.');
	addnav('F?`Blick aus dem Fenster','castletower.php?op=window');
	addnav('S?Erwarte dein Schicksal','castletower.php?op=doom');
}
elseif($_GET['op']=='window')
{
	output('`2Das Fenster ist genau genommen nur eine kleine Dachluke, die zwar Licht hereinlässt, dir aber nur Sicht in den Himmel gewährt. Weit unter dir könnte der Eingang zum Schloss sein, doch so genau weißt du das nicht.`n`n
	Du könntest etwas von deinem persönlichen Besitz hinauswerfen und hoffen dass ein anderer Abenteurer auf dich aufmerksam wird.');
	addnav('Inventar durchsuchen','castletower.php?op=invent');
	addnav('T?Die Truhe untersuchen','castletower.php?op=chest');
	addnav('S?Erwarte dein Schicksal','castletower.php?op=doom');
}
elseif($_GET['op']=='invent')
{
	output('`n`2Du wühlst in deinen Taschen auf der Suche nach einem Gegenstand den du aus dem Fenster werfen kannst. Es sollte etwas sein was man in dieser Gegend normalerweise nicht findet -vielleicht ein Stück Schmuck- und Platz für eine kurze Mitteilung bieten, überlegst du dir.`n`n');
	$str_msg = '`nDoch leider hast du für diesen Zweck absolut nichts brauchbares dabei.';
	$arr_options = array('Verwenden!'=>'invent2');

	item_invent_set_env(ITEM_INVENT_HEAD_ORDER | ITEM_INVENT_HEAD_CATS | ITEM_INVENT_HEAD_SEARCH);

	item_invent_show_data(item_invent_head(' owner='.$session['user']['acctid'].' AND deposit1=0 AND deposit2=0 AND tpl_class=4',20), $str_msg, $arr_options);

	addnav('Zurück');
	addnav('Nichts werfen','castletower.php?op=goon');
}
elseif($_GET['op']=='invent2')
{
	$row=item_get('i.id='.intval($_GET['id']));
	if(!empty($row['description']) && mb_strpos($row['description'],'Schlossturm gefangen'))
	{
		output('`n`2Du entscheidest dich für '.$row['name'].'`2 und wirfst es aus dem Fenster. Das hat ja schonmal funktioniert.`n`nJetzt solltest du dich aber schnell verstecken...');
	}
	else
	{
		$row['description'].='`n`&Mit hastiger Schrift steht darauf geschrieben: `3"Hilfe! Ich bin im Schlossturm gefangen!"`0';
		item_set('id='.$row['id'],$row);
		$session['castletoweritem']=$row['name'];
		output('`n`2Du entscheidest dich für '.$row['name'].'`2, schreibst noch schnell `3"Hilfe! Ich bin im Schlossturm gefangen!"`2 drauf und wirfst '.$row['name'].'`2 aus dem Fenster.`n`nHoffentlich findet es jemand! Jetzt solltest du dich aber verstecken...');
	}
	addnav('T?Die Truhe untersuchen','castletower.php?op=chest2');
}
elseif($_GET['op']=='fight')
{
	$battle=true;
}
elseif($_GET['op']=='run')
{
	if($session['user']['hitpoints']>$session['user']['hitpoints']*0.1)
	{
		output('`2Irgendwie schaffst du es, aus dem Turmzimmer zu fliehen. Du stösst die Tür zu und rennst so schnell du kannst die Treppen hinunter. Panisch reisst du irgendeine Tür auf und findest dich auf dem Schlosshof wieder.`n`n`bPuh!`b');
		restore_buffs();
		addnav('Zurück in den Wald','forest.php');
	}
	else
	{
		output('`4Bei deinem Versuch zu Fliehen fällst du hin und stösst dir den Kopf. Das gibt dem Minotaurus Gelegenheit, dich erneut zu attackieren.`0');
		$battle=true;
	}
}
elseif($_GET['op']=='chest2')
{
	output('`2In der Truhe liegen jahrhundertealte, von Motten angefressene Kleider. Du wirfst einige davon heraus und steigst selbst in die Truhe. Mit einem Krachen fällt der Deckel über dir zu. Es ist stockfinster und gespenstisch still.');
	addnav('S?Erwarte dein Schicksal','castletower.php?op=doom&act=chest');
}
elseif($_GET['op']=='doom')
{
	if($_GET['act']=='chest')
	{
		output('`2Du bemerkst einen Hebel am Boden der Truhe. Wissend, dass dies dein einziger Ausweg ist, drückst du drauf und gelangst in einen Gang der mit Spinnen, Ratten und Schlangen gefüllt ist. Der Ekel packt dich, doch du hast keine Wahl. Also folgst du, dich vorwärts tastend, dem Gang und gelangst in eine Kammer, welche mit einer schweren Steinplatte abgedeckt ist. ');
		if(e_rand(1,3)==1)
		{
			output('`2Doch was du auch versuchst, du schaffst es nicht, die Platte auch nur einen Millimeter zu bewegen. Dir bleibt nichts weiter übrig als durch den Geheimgang zurück in den Turm zu gehen und auf Rettung zu warten.`nEs dauert einen ganzen Tag bis jemand die Botschaft auf deinem '.$session['castletoweritem'].'`2 findet und dich aus dem Turm befreit. Überglücklich fällst du deinem Retter um den Hals.');
			$session['user']['age']++;
			addnav('Zurück in den Wald','forest.php');
		}
		else
		{
			output('`2Mit letzter Kraft schiebst du die Steinplatte einen Spalt breit beiseite und zwängst dich nach oben.`nDu befindest dich in einer alten Familiengruft, völlig erschöpft, aber immerhin lebend.`n
			Unweit der Gruft findest du auch dein '.$session['castletoweritem'].'`2 wieder.');
			$session['user']['hitpoints']=max($session['user']['hitpoints']>>1,1);
			addnav('Weiter','friedhof.php');
		}
		unset($session['castletoweritem']);
		restore_buffs();
	}
	else
	{
		output('`2Das Schlurfen vor der Tür kommt immer näher, Angstschweiß steht dir auf der Stirn. Dann ist es soweit. Du hörst wie der Schlüssel im Schloss gedreht wird und...`n`n');
		if(e_rand(1,5)==1)
		{
			output('`2Nicht nur, dass die Tür keine Klinke hat, jetzt ist sie auch noch abgeschlossen. Verzweifelt schreist du um Hilfe. Doch kein Mensch sollte dich hören...`n`n`$Zwei Tage später stirbst du jämmerlich den Hungertod.');
			addnews($session['user']['name'].'`t ging in das verlassene Schloss und verschwand dort spurlos.');
			$session['user']['age']+=2;
			killplayer();
		}
		else
		{
			output('`2Die Zeit bis sich die Tür öffnet kommt dir vor wie Stunden. Dann schlurft ES herein. Mit Schrecken stellst du fest dass du es mit einem `$untoten Minotaurus`2 zu tun hast.');
			$badguy = array('creaturename'=>'`$untoter Minotaurus`0'
			,'creaturelevel'=>0
			,'creatureweapon'=>'Hörner'
			,'creatureattack'=>1
			,'creaturedefense'=>40
			,'creaturehealth'=>1000
			,'maze'=>1
			,'diddamage'=>0);

			$userattack=$session['user']['attack']+e_rand(1,3);
			$userhealth=round($session['user']['hitpoints']/2);
			$userdefense=$session['user']['defense']+e_rand(1,3);
			$badguy['creaturelevel']=$session['user']['level'];
			$badguy['creatureattack']+=($userattack-4);
			$badguy['creaturehealth']+=$userhealth;
			$badguy['creaturedefense']+=$userdefense;
			$session['user']['badguy']=createstring($badguy);

			addnav('Kämpfe','castletower.php?op=fight');
		}
	}
}
else
{
	output('Fehler');
	restore_buffs();
	addnav('Notausgang','forest.php');
}
if ($battle)
{
	include_once ('battle.php');
	if ($victory)
	{
		$gold=e_rand(100,500);
		$experience=$session['user']['level']*e_rand(37,80);
		$session['user']['gold']+=$gold;
		$session['user']['experience']+=$experience;

		output('`b`4Du hast `^'.$badguy['creaturename'].'`4 besiegt.`b`n
		`#Du erhältst `6'.$gold.' `#Gold!`n
		Du erhältst `6'.$experience.' `#Erfahrung!`n
		Der Weg zurück ist frei.`n');
		addnav('Weiter','forest.php');

		$session['user']['turns'] = max($session['user']['turns']-1,0);
		$badguy=array();
		$session['user']['badguy']='';
		$session['user']['specialinc']='';
		restore_buffs();
	}
	elseif ($defeat)
	{
		output('`&Als du auf dem Boden aufschlägst schlurft `^'.$badguy['creaturename'].'');

		/*$sql = 'SELECT name,state FROM disciples WHERE master='.$session['user']['acctid'];
		$result = db_query($sql);
		if (db_num_rows($result)>0){
		$rowk = db_fetch_assoc($result);}
		$kname=$rowk['name'];
		$kstate=$rowk['state'];

		if (($kstate>0) && ($kstate<20)) {
			output(' `&mit `^'.$kname.' `&');
			debuglog('Verlor einen Knappen bei einer Niederlage im Schloss/Irrgarten.');
		}*/
		output('`& weg.');

		addnews('`%'.$session['user']['name'].'`5 wurde von '.$badguy['creaturename'].' im Verlassenen Schloss erschlagen.');
		$badguy=array();
		killplayer(0,0);
		$session['user']['specialinc']='';
	}
	else
	{
		fightnav();
		if ($badguy['creaturehealth'] > 0){
			$hp=$badguy['creaturehealth'];
		}
	}
}
page_footer();
