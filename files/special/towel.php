<?php
/*
* Waldereignis für LotGD - www.atrahor.de
* Hitchikers Guide to Atrahor: Wenn du in dieser Stadt überleben willst, musst du immer wissen wo dein Handtuch ist.
* Autor: Salator (salator@gmx.de)
*/

if (!isset($session))
{
	exit();
}

page_header('HGttG');

switch($_GET['op'])
{
	case 'answer':
		$session['user']['specialinc'] = '';
		$row=item_get('tpl_id="towel" AND owner='.$session['user']['acctid'].' AND deposit1'.($_GET['loc']==1?'=':'>').'0 AND deposit2'.($_GET['loc']==3?'>':'=').'0',false,'id');
		output('`7Du hast zwar überhaupt keine Ahnung was der Gnom mit dieser Frage bezwecken will, gibst ihm aber Auskunft, dass dein Handtuch ');
		if($_GET['loc']==1) output('hier in deinem Beutel ist.');
		elseif($_GET['loc']==2) output('bei dir zu hause liegt.');
		elseif($_GET['loc']==3) output('in deinem Zimmer liegt.');
		else output('vom Salator gefressen wurde. Seit wann fressen Salators Handtücher???');//Fehler
		output('`n`nDer Gnom nimmt ein Stück Klopapier aus seiner Box und betrachtet es eingehend, so als ob er darauf lesen könnte.`n
		Ja, er kann auf diesem Papier lesen. Er stellt fest, dass du ');
		if($row['id'])
		{
			output('die Wahrheit gesagt hast. Du bekommst von ihm ');
			switch (e_rand(1,10))
			{
				case 1:
				case 2:
				case 3:
				case 4:
					output('`^ein Goldstück`7! Wenn du es gut anlegst, wirst du dir von den Zinsen mal ein sündhaft teures Dinner leisten können.');
					$session['user']['gold']++;
					break;
				case 5:
				case 6:
					output('`^eine Handvoll gesalzene Erdnüsse`7. Es könnte einmal passieren dass jemand dein Haus abreißen will. Dann ist der richtige Zeitpunkt, die Nüsse zu essen und dazu ein gutes Ale zu trinken.');
					item_add($session['user']['acctid'],'erdnuss');
					break;
				case 7:
					output('2 `&weisse Mäuse`7. Pflege sie gut, doch denke immer daran: Nichts ist wie es scheint!');

					$item['tpl_special_info']='Männchen';
					item_add($session['user']['acctid'],'mice',$item);
					$item['tpl_special_info']='Weibchen';
					item_add($session['user']['acctid'],'mice',$item);
					break;
				case 8:
				case 9:
					output('`^einen Petunientopf`7. Vermeide es, ihn aus großer Höhe fallenzulassen.');
					item_add($session['user']['acctid'],'blmntpf');
					break;
				case 10:
					output('kräftig eins über die Rübe. ');
					if ($session['user']['specialty'])
					{
						output('`7Als du wieder zu dir kommst, fühlst du dich ein klein wenig geschickter.`n`#');
						increment_specialty();
					}
					else
					{
						output('`&Das war eine Lektion in Spaß.`n`n`^Du erhältst `b42`b Erfahrungspunkte!');
						$session['user']['experience'] += 42;
					}
					break;
			}
		}
		else
		{
			output('ihn angelogen hast. Er überwältigt und fesselt dich und bringt dich zum Plapperkäfer von '.getsetting('townname','Atrahor').'.`n
			`qDu hast gehört dass dieser Käfer gutes Essen mit Kriegern macht.`n
			Naja, das ist nicht ganz korrekt übersetzt, es müsste heißen er macht gutes Essen `$AUS`q Kriegern.`n`n
			Für dich bedeutet das: `$Du bist gleich tot!');
			killplayer(0,0,0,'shades.php','Na toll!');
			addnews('`5Gerüchte besagen, dass es gutes Essen mit '.$session['user']['name'].'`5 geben soll.');
		}
		$session['user']['specialinc'] = '';
		break;
    /** @noinspection PhpMissingBreakStatementInspection */
    case 'notowel':
		$session['user']['specialinc'] = '';
		if(item_count(' owner='.$session['user']['acctid'].' AND tpl_id="towel"')==0)
		{
			output('`7Du erklärst dem Gnom dass du kein Handtuch besitzt. Dieser zeigt sich betroffen und schenkt dir eins mit den Worten: "`2Wenn du in dieser Stadt überleben willst, musst du immer wissen wo dein Handtuch ist.`7"`n`n');
			$item['tpl_name']=($session['user']['sex']?'`r':'`w').'Handtuch`0';
			$item['tpl_description']='Ein Handtuch mit dem man sich abtrocknen kann. Das hat dir der Klopapier-Gnom geschenkt.';
			$item['tpl_gold']=1;
			$item['tpl_gems']=0;
			item_add($session['user']['acctid'],'towel',$item);
		}
		else
		{
			output('Naja, eigentlich hast du ja doch eins... ');
		}
		//kein break;
	case 'leave':
		output('Die Sache ist dir nicht geheuer und du verschwindest schnell.');
		//addnav('Weiter...','forest.php');
		$session['user']['specialinc'] = '';
		break;

	default:
		if($session['user']['dragonkills']>10)
		{
			$session['user']['specialinc'] = 'towel.php';
			output('`7Gerade als du in einiger Entfernung ein Monster zum Bekämpfen entdeckt hast bemerkst du wie dir eine kleine Gestalt hinterherrennt. Es ist der Klopapier-Gnom, stellst du erleichtert fest. Da von ihm keine Gefahr ausgeht lässt du ihn herankommen.`n
			Der Klopapier-Gnom hat eine gar seltsame Frage an dich, er will wissen wo dein Handtuch ist.`n
			`&Was wirst du ihm antworten?');
			addnav('Im Beutel','forest.php?op=answer&loc=1');
			addnav('H?Im Haus','forest.php?op=answer&loc=2');
			addnav('P?Im Privatgemach','forest.php?op=answer&loc=3');
			addnav('k?Ich habe keins','forest.php?op=notowel');
			addnav('Ignoriere es','forest.php?op=leave');
		}
		else
		{
			$session['user']['specialinc'] = '';
			redirect('forest.php?op=search');
		}

}