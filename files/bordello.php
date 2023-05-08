<?php
//+-------------------------+
//|    Kompletter Neubau    |
//|   mit ERLAUBNISS von    |
//|        Phudgee          |
//|      by Amerilion       |
//|    www.mekkelon.de.vu   |
//+-------------------------+
//Keinerlein Veränderung an der Einbauanleitung, der Code selbst ist aber komplett geändert bzw. neu strukturiert
//07/10/05

//-----------------OLD COPYRIGHT - RIGHTS STILL BY PHUDGEE, JUST RE-BUILDED ---------------------------
/* The House of Sin
** By Phudgee - phudgee@oldschoolpunk.com
** Written Aug. 10, 2004 in about 3 hours.
** Website: http://www.worldwidepunks.com
** Green Dragon Game: http://www.worldwidepunks.com/logd
** angepasst für www.silienta-logd.de und bugfixe durch Rikkarda@silienta-logd.de
** & Gargamel@silienta-logd.de
**  German Translation by Beleggrodion
**
** Installation:
** ALTER TABLE `accounts` ADD `bordello` TINYINT( 4 ) NOT null ;
**
** in newday.php:
**
**  find:  $session['user'][seenmaster]=0;
**
** after, add: $session['user'][bordello]=0;
**
** Modify the prices for the different services below, along with the name of the madam.
** Set your return village by setting the $returnvillage variable on line 29
**
** I pounded this together in about 3 hours. Let me know if there are any bugs!
**
*/
//-----------------------------------------------------------------------------------------------------


require_once 'common.php';
addcommentary();
checkday();
$madam = '`oOnèlla-Sophie`X';
page_header('Haus der Sinne');
output('`c`b`ADas Haus der Sinne`b`c`n');
$costone   =  100;
$costtwo   =  250;
$costthree =  500;
$costfour  =  750;
$costfive  = 1000;

switch ($_GET['op'])
{
case '':
default;
	output('`XMadam '.$madam.' empfängt dich mit einer einladenden, überschwänglichen Geste. Doch auch in diesem Etablisment ist man nur an Gold interessiert und keinesfalls an irgendwelchen charakterlichen Qualitäten. Schon allein das Lächeln der Hausmutter verdeutlicht dies... Im Vorraum des Bordells sieht man einige Sitznischen und sonst nur viele Türen, die in die einzelen Gemächer, der "werten" Damen und Herren führen, die in diesem Hause angestellt sind. Entgegen den allgemeinen Gerüchten, ist der Raum sehr sauber und die Möbel wurden aus teurem Material hergestellt. Allerdings kann dies alles nicht über die Arbeit hinwegtäuschen, für die hier bezahlt wird.');
	if ($session['user']['seenlover'] == 1)
	{
		output('`n`XAls du dich nach einer netten "Unterhaltung" für den Abend erkundigst, bekommst du nur ein abweisendes `_"Tut mir leid, '.($session['user']['sex']?'meine Süße':'mein Süßer').', das Bordell ist für den Rest des Tages geschlossen! Komm doch morgen wieder." `XSie zwinkert dir noch einmal zu, nur um noch keinen kurzen, sehnsüchtigen Blick auf deinen Beutel zu werfen.`n');
	}
	else
	{
		if ($session['user']['gold'] < $costone)
		{
			output('`n`XUm die Nacht ( oder zumindest ein paar Stunden ) nicht allein verbringen zu müssen, fragst du Madam '.$madam.' nach ihrem Angebot an Personal. Mit einem gekonnten Blick schätzt sie das Gold ab, dass du in deinem Beutel bei dir trägst und schreit daraufhin empört. `_"Was denkst du!?!? Meine Jungs und Mädels sind nicht billig, vorallem nicht gratis! Verschwinde!!!"`n');
		}
		else
		{
			output('`n`XBereits kurz nachdem du das Bordell betreten hast, schätzt Madam '.$madam.' ab, ob du auch zahlungsfähig bist, ehe sie dir überhaupt erlaubt, sich etwas umzusehen. Während du den teuren Raum betrachtest, ruft sie ein paar ihrer Angestellten zusammen, sowohl Frauen als auch Männer. Anscheinend hat man in diesem Hause kein Problem mit gleichgeschlechtlichen Beziehungen. Aber noch viel besser: Du darfst dir nun jemanden ganz nach deinem Geschmack aussuchen.`n');
			addnav('Jungs','bordello.php?op=jung');
			addnav('Mädels','bordello.php?op=maedel');
		}
	}
	break;

case 'jung':
	output('`_"Soso, einen meiner Jungs magst du haben... '.($session['user']['sex']?'war ja eigentlich klar..."':'wie ungewöhnlich..."').'');
	if ($session['user']['gold'] >= $costone)
	{
		addnav('Tor','bordello.php?op=boyone');
	}
	if ($session['user']['gold'] >= $costtwo)
	{
		addnav('Sandar','bordello.php?op=boytwo');
	}
	if ($session['user']['gold'] >= $costthree)
	{
		addnav('Derris','bordello.php?op=boythree');
	}
	if ($session['user']['gold'] >= $costfour)
	{
		addnav('Kierst','bordello.php?op=boyfour');
	}
	if ($session['user']['gold'] >= $costfive)
	{
		addnav('Travys','bordello.php?op=boyfive');
	}
	break;
	
case 'maedel':
	output('`_"Soso, eines meiner Mädchen magst du haben... '.($session['user']['sex']?'wie ungewöhnlich..."':'war ja eigentlich klar..."').'');
	if ($session['user']['gold'] >= $costone)
	{
		addnav('Tiri','bordello.php?op=girlone');
	}
	if ($session['user']['gold'] >= $costtwo)
	{
		addnav('Sephya','bordello.php?op=girltwo');
	}
	if ($session['user']['gold'] >= $costthree)
	{
		addnav('Glynna','bordello.php?op=girlthree');
	}
	if ($session['user']['gold'] >= $costfour)
	{
		addnav('Kyale','bordello.php?op=girlfour');
	}
	if ($session['user']['gold'] >= $costfive)
	{
		addnav('Mora','bordello.php?op=girlfive');
	}
	break;
	
case 'boyone':
	$session['user']['gold']-=$costone;
	$session['user']['seenlover']=1;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costone.'`X Gold und steuerst die Treppe mit `ATor`X an.`n');
	if (e_rand(0,1)==0)
	{
		output('`n`XEr hat offensichtlich einen schlechten Tag und bringt dir kein großes Vergnügen.`n
		Er ist frustriert und sagt dir, dass du gehen sollst.`n
		Du sagst Madam '.$madam.', dass du dein Gold zurück verlangst, da die Leistung des Hauses nicht erbracht wurde!`n
		Sie sagt dir, dass es hinter dem Tisch ist... Als du dich auf den Weg machst, es zu holen, schlägt sie dich mit einem Rohr und einem gezielten Schlag auf den Kopf nieder.`n`n
		Du wachst später auf der Straße wieder auf und stellst fest, dass du eine große Beule an deinem Hinterkopf hast.`n`n');
		switch (e_rand(1,3))
		{
		case 1:
			if ($session['user']['charm']>=2)
			{
				output('`n`4Du bist die Witzfigur des Tages in der Stadt! Du verlierst 2 Charmepunkte.`n');
				$session['user']['charm']-=2;
			}
			else
			{
				output('`n`4Du bist die Witzfigur des Tages in der Stadt! Du verlierst deine letzten Charmepunkte.`n');
				$session['user']['charm']=0;
			}
			break;
		case 2:
			output('`n`4Du hast viele Lebenspunkte verloren.`n');
			$session['user']['hitpoints']-=floor($session['user']['hitpoints']*0.5);
			break;
		case 3:
			output('`n`4Du bemerkst, dass du eine Zeit lang auf der Straße gelegen hast. Du verlierst 2 Waldkämpfe.`n');
			addnews('`%'.$session['user']['name'].'`@ wurde  von `%Madam '.$madam.' `@bewusstlos geschlagen und auf die Straße geworfen.');
			$session['user']['turns']=max(0,$session['user']['turns']-2);
		}
	}
	else
	{
		output('`n`XDas war das Beste, wofür du dein Gold hättest ausgegeben können!`n
		`ATor`X weiß einfach total, was du magst.`n
		Er macht Dinge, die du dir nie hättest vorstellen können!`n
		Nach ein paar Momenten des höchsten Vergnügens verlässt du das Gebäude mit einem breiten Grinsen.`n
		Du hast genug für die nächsten paar Jahre!`n`n');
		switch (e_rand(1,3))
		{
		case 1:
			output('`n`RDu hast nun ein leichtes "Glühen"! Du bekommst 1 Charmepunkt.`n');
			$session['user']['charm']+=1;
			break;
		case 2:
			output('`n`RDu fühlst dich, als könntest du die gesamte Welt erobern. Du gewinnst 2 Waldkämpfe.`n');
			$session['user']['turns']+=2;
			break;
		case 3:
			output('`n`RDu fühlst dich total erfrischt. Du wurdest komplett geheilt!`n');
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
		}
	}
	break;
	
case 'boytwo':
	$session['user']['seenlover']=1;
	$session['user']['gold']-=$costtwo;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costtwo.'`X Gold und steuerst die Treppe mit `4Sandar`X an.`n`n');
	if (e_rand(0,1)==0)
	{
		output('`XEr weiß absolut, was sich '.($session['user']['sex']?'eine Frau':'ein Mann').' wünscht.`n
		Er macht für ein paar Minuten wundervolle Dinge mit dir.....`n
		Total befriedigt verlässt du das Bordell mit einem Lächeln!`n
		Drei Tage später lächelst du nicht mehr so....`n`n
		`4(Du hast dir eine Krankheit zugezogen)`n');
		$buff = array("name"=>"`4Krankheit`0","rounds"=>60,"wearoff"=>"`5`bDeine Krankheit lässt nach!`b`0","atkmod"=>0.95,"roundmsg"=>"Deine Krankheit behindert deine Kampffähigkeit!","activate"=>"offense");
		$session['bufflist']['bordello']=$buff;
	}
	else
	{
		output('`n`XDas war das Beste, wofür du dein Gold ausgegeben hast!`n
		`ASandar`X weiß absolut, was du magst und dir richtig gut tut.`n
		Er macht Dinge mit dir, die du dir nie hättest vorstellen können!`n
		Total befriedigt verlässt du das Bordell mit neuer Kraft!`n`n
		`RDu bekommst 10 zusätzliche Lebenspunkte!`n');
		$buff = array("name"=>"`!Befriedigung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger befriedigt!`b`0","atkmod"=>1.05,"roundmsg"=>"Deine kürzlich erhaltene Befriedigung erhöht deine Kampffähigkeiten!","activate"=>"offense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['hitpoints'] += 10;
	}
	break;
	
case 'boythree':
	$session['user']['seenlover']=1;
	$session['user']['gold']-=$costthree;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costthree.'`X Gold und steuerst die Treppe mit `ADerris`X an.`n`n');
	if (e_rand(0,1)==0)
	{
		output('`XEr weiß absolut, was sich '.($session['user']['sex']?'eine Frau':'ein Mann').' wünscht.`n
		Allerdings macht er plötzlich etwas, was du überhaupt nicht willst!`n
		Du willst die Treppe runter und dich bei der Madam beschweren!`n
		Doch `ADerris`X schlägt dich blitzschnell von hinten bewusstlos...`n`n
		Du wachst auf der Straße auf mit einem etwas leichteren Goldbeutel...`n
		`4Du wurdest ausgeraubt! Die Nachricht deiner Lage spricht sich schnell rum. Du bekommst den Kopf nicht frei und vernachlässigst deine Verteidigung!`n');
		$buff = array("name"=>"`4Demütigung`0","rounds"=>60,"wearoff"=>"`5`bDu bekommst deinen Stolz zurück!`b`0","defmod"=>0.9,"roundmsg"=>"Du bist zu beschämt um dich voll zu verteidigen!","activate"=>"defense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['gold'] = round($session['user']['gold']*0.5);
		
	}
	else
	{
		output('`n`XDas war das Beste, wofür du dein Gold ausgegeben hast!`n
		`ADerris`X weiß absolut, was du magst.`n
		Er macht Dinge mit dir, die du dir nie hättest vorstellen können!`n
		Nach einer Zeit der Befriedigung verlässt du das Gebäude mit einem noch nie dagewesenen Gefühl!`n`n
		`R(Diese Erfahrung hat dich total erneuert! Deine Sinne sind an der Grenze ihres Könnens. Deine Verteidigung steigt und du erhältst einen zusätzlichen Waldkampf!)`n');
		$buff = array("name"=>"`4Erneuert`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger erneuert!`b`0","defmod"=>1.1,"roundmsg"=>"Deine Sinne sind an der Spitze!","activate"=>"defense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['turns'] += 1;
	}
	break;
	
case 'boyfour':
	$session['user']['seenlover']=1;
	$session['user']['gold']-=$costfour;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costfour.'`X Gold und steuerst die Treppe mit `AKierst`X an.`n`n');
	if (e_rand(0,1)==0)
	{
		output("`XEr hat überhaupt kein Gefühl für sich selber.`n
		Er sitzt in der Ecke und lehnt es grundsätzlich ab, dich zu berühren!`n
		Er sagt, du siehst abscheulich aus...`n
		Verärgert trinkst du deinen Drink, nimmst deine Sachen und verlässt das Bordell....`n
		Eine Menschenansammlung steht vor der Tür und zeigt auf dich....`n
		Du bist dir nicht sicher, wieso sie lachen, aber Kierst führt die Menge an.`n");
		switch (e_rand(1,3))
		{
		case 1:
			output('`n`4Du wurdest im Bordell mit Drogen versorgt. Dein Angriff und deine Verteidigung sind ein wenig niedriger.`n');
			$buff = array("name"=>"`4Drogen`0","rounds"=>60,"wearoff"=>"`5`bDie Drogen sind weg!`b`0","defmod"=>.95,"atkmod"=>.95,"roundmsg"=>"Du hast Drogen genommen. Es ist schwer zu kämpfen!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			break;
		case 2:
			output('`n`4Du wurdest im Bordell mit Drogen versorgt. Dein Angriff und deine Verteidigung sind niedriger.`n');
			$buff = array("name"=>"`4Drogen`0","rounds"=>60,"wearoff"=>"`5`bDie Drogen sind weg!`b`0","defmod"=>.85,"atkmod"=>.85,"roundmsg"=>"Du hast Drogen genommen. Es ist schwer zu kämpfen!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			break;
		case 3:
			output('`n`4Du wurdest im Bordell mit Drogen versorgt. Dein Angriff und deine Verteidigung sind viel niedriger.`n');
			$buff = array("name"=>"`4Drogen`0","rounds"=>60,"wearoff"=>"`5`bDie Drogen sind weg!`b`0","defmod"=>.75,"atkmod"=>.75,"roundmsg"=>"Du hast Drogen genommen. Es ist schwer zu kämpfen!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
		}
	}
	else
	{
		output('`n`XDas war das Beste, wofür du dein Gold ausgegeben hast!`n
		`AKierst`X weiß absolut, was du magst.`n
		Er bearbeitet dich richtig!!!`n
		Nach ein paar Momenten der Befriedigung verlässt du das Bordell, glücklich grinsend!`n`n');
		switch (e_rand(1,3))
		{
		case 1:
			output('`n`RDu bist in Begeisterung. Dein Angriff und deine Verteidigung sind etwas höher.`n');
			$buff = array("name"=>"`4Begeisterung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger euphorisch!`b`0","defmod"=>1.05,"atkmod"=>1.05,"roundmsg"=>"Du bist im Freudentaumel!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			break;
		case 2:
			output('`n`RDu bist in Begeisterung. Dein Angriff und deine Verteidigung sind höher.`n');
			$buff = array("name"=>"`4Begeisterung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger euphorisch!`b`0","defmod"=>1.15,"atkmod"=>1.15,"roundmsg"=>"Du bist im Freudentaumel!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			break;
		case 3:
			output('`n`RDu bist in Begeisterung. Dein Angriff und deine Verteidigung sind höher und du erhältst einen zusätzlichen Waldkampf.`n');
			$buff = array("name"=>"`4Begeisterung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger euphorisch!`b`0","defmod"=>1.15,"atkmod"=>1.15,"roundmsg"=>"Du bist im Freudentaumel!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			$session['user']['turns'] += 1;
		}
	}
	break;
	
case 'boyfive':
	$session['user']['seenlover']=1;
	$session['user']['gold']-=$costfive;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costfive.'`X Gold und steuerst die Treppe mit `ATravys`X an.`n`n');
	if (e_rand(0,1)==0)
	{
		output('`XEr beginnt dich zu verwöhnen....`n
		Er lässt dich zurück mit geschlossenen Augen...`n
		Du hattest noch nie soviel Vergnügen in deinem Leben!!!`n
		Nun, du hattest noch nie soviel Vergnügen... zuviel, denn bevor es richtig zu Sache geht, bist du schon fertig!!!`n`n
		`4Es ist dir peinlich, in die Stadt zu gehen. Erst in der Abenddämmerung traust du dich. Du verlierst Waldkämpfe.`n');
		$buff = array("name"=>"`4Peinlichkeit`0","rounds"=>60,"wearoff"=>"`5`bDu hast deine Peinlichkeit überwunden!`b`0","defmod"=>.65,"atkmod"=>.65,"roundmsg"=>"Du bist zu verlegen um voll zu kämpfen!","activate"=>"offense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['turns'] -= round($session['user']['turns']*0.5);
		
	}
	else
	{
		output('`n`XDu bist '.($session['user']['sex']?'DIE FRAU...':'DER MANN...').'`n
		`ATravys`X hatte noch nie Erfahrungen mit jemandem wie dir in seinem Leben!!!`n
		Er erzählt in der ganzen Stadt wie gut du im Bett bist!!!`n
		Du verlässt das Bordell erhobenen Hauptes!!!`n`n
		`RDu bist die meistverehrte Person in der Stadt! Du erhältst ein paar Waldkämpfe.`n');
		$buff = array("name"=>"`4Großer Liebhaber`0","rounds"=>60,"wearoff"=>"`5`bDu fühlst dich nicht mehr länger hochmütig!`b`0","defmod"=>1.35,"atkmod"=>1.35,"roundmsg"=>"Der Stolz fließt durch deine Adern!","activate"=>"offense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['turns'] += 5;
	}
	break;
	

case 'girlone':
	$session['user']['gold']-=$costone;
	$session['user']['seenlover']=1;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costone.'`X Gold und steuerst die Treppe mit `ATiri`X an.`n`n');
	if (e_rand(0,1)==0)
	{
		output('`n`XSie hat offensichtlich einen schlechten Tag, und bringt dir kein großes Vergnügen`n
		Sie ist frustriert und sagt dir dass, du gehen sollst.`n
		Du sagst Madam '.$madam.', dass du dein Gold zurück möchtest!`n
		Sie sagt dir dass, es hinter dem Tisch ist... Als du dich auf den Weg machst es zu holen, schlägt sie dich mit einem Rohr und einem gezielten Schlag auf den Kopf nieder.`n`n
		Du wachst später auf der Straße wieder auf und stellst fest, dass du eine große Beule an deinem Hinterkopf hast.`n`n');
		switch (e_rand(1,3))
		{
		case 1:
			if ($session['user']['charm']>=2)
			{
				output('`n`4Du bist die Witzfigur des Tages in der Stadt! Du verlierst 2 Charmepunkte.`n');
				$session['user']['charm']-=2;
			}
			else
			{
				output('`n`4Du bist die Witzfigur des Tages in der Stadt! Du verlierst deine letzten Charmepunkte.`n');
				$session['user']['charm']=0;
			}
			break;
		case 2:
			output('`n`4Du hast viele Lebenspunkte verloren.`n');
			$session['user']['hitpoints']-=floor($session['user']['hitpoints']*0.5);
			break;
		case 3:
			output('`n`4Du bemerkst dass du eine Zeit lang auf der Straße gelegen hast. Du verlierst 2 Waldkämpfe.`n');
			addnews('`%'.$session['user']['name'].'`@ wurde von `%Madam '.$madam.'`@ bewusstlos geschlagen und auf die Straße geworfen');
			$session['user']['turns']=max(0,$session['user']['turns']-2);
		}
	}
	else
	{
		output('`n`XDas war das Beste, wofür du dein Gold ausgegeben hast!`n
		`ATiri`X weiß total, was du magst.`n
		Sie macht Dinge, die du dir nie hättest vorstellen können!`n
		Nach ein paar Momenten des höchsten Vergnügens verlässt du das Gebäude mit einem breiten Grinsen.`n
		Du hast genug für die nächsten paar Jahre!`n`n');
		switch (e_rand(1,3))
		{
		case 1:
			output('`n`RDu hast nun ein leichtes "Glühen"! Du bekommst 1 Charmepunkt.`n');
			$session['user']['charm']+=1;
			break;
		case 2:
			output('`n`RDu fühlst dich, als könntest du die gesamte Welt erobern. Du gewinnst 2 Waldkämpfe.`n');
			$session['user']['turns']+=2;
			break;
		case 3:
			output('`n`RDu fühlst dich total erfrischt. Du wurdest komplett geheilt!`n');
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
		}
	}
	break;
	
case 'girltwo':
	$session['user']['seenlover']=1;
	$session['user']['gold']-=$costtwo;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costtwo.'`X Gold und steuerst die Treppe mit `ASephya`X an.`n`n');
	if (e_rand(0,1)==0)
	{
		output('`ASephya`X weiß absolut, was sich '.($session['user']['sex']?'eine Frau':'ein Mann').' wünscht.`n
		Sie macht wundervolle Dinge mit dir für ein paar Minuten.....`n
		Total befriedigt verlässt du das Bordell mit einem Lächeln!`n
		Drei Tage später lächelst du nicht mehr so....`n`n
		`4(Du hast dir eine Krankheit zugezogen)`n');
		$buff = array("name"=>"`4Krankheit`0","rounds"=>60,"wearoff"=>"`5`bDeine Krankheit lässt nach!`b`0","atkmod"=>0.95,"roundmsg"=>"Deine Krankheit behindert deine Kampffähigkeit!","activate"=>"offense");
		$session['bufflist']['bordello']=$buff;
	}
	else
	{
		output('`n`XDas war das Beste, wofür du dein Gold ausgegeben hast!`n
		`ASephya`X weiß absolut, was du magst.`n
		Sie macht Dinge mit dir, die du dir nie hättest vorstellen können!`n
		Total befriedigt verlässt du das Bordell mit neuer Kraft!`n`n
		`n`RDu bekommst 10 zusätzliche Lebenspunkte!`n');
		$buff = array("name"=>"`!Befriedigung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger befriedigt!`b`0","atkmod"=>1.05,"roundmsg"=>"Deine kürzlich erhaltene Befriedigung erhöht deine Kampffähigkeiten!","activate"=>"offense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['hitpoints'] += 10;
	}
	break;
	
case 'girlthree':
	$session['user']['seenlover']=1;
	$session['user']['gold']-=$costthree;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costthree.'`X Gold und steuerst die Treppe mit `AGlynna`X an.`n`n');
	if (e_rand(0,1)==0)
	{
		output('`AGlynna`X weiß absolut, was sich '.($session['user']['sex']?'eine Frau':'ein Mann').' wünscht.`n
		Sie macht allerdings etwas, was du überhaupt nicht willst!`n
		Du willst die Treppe runter und dich bei der Madam beschweren!`n
		`AGlynna`X schlägt dir etwas über den Kopf...`n
		Du wachst auf der Straße auf mit einem etwas leichteren Goldbeutel...`n
		`n`4Du wurdest ausgeraubt! Die Nachricht deiner Lage spricht sich schnell herum. Du bekommst den Kopf nicht frei und vernachlässigst deine Verteidigung!`n');
		$buff = array("name"=>"`4Demütigung`0","rounds"=>60,"wearoff"=>"`5`bDu bekommst deinen Stolz zurück!`b`0","defmod"=>0.9,"roundmsg"=>"Du bist zu  beschämt um dich voll zu verteidigen!","activate"=>"defense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['gold'] = round($session['user']['gold']*0.5);
		
	}
	else
	{
		output('`n`XDas war das beste wofür du dein Gold ausgegeben hast!`n
		`AGlynna`X weiß absolut was du magst.`n
		Sie macht Dinge mit dir, die du dir nie hättest vorstellen können!`n
		Nach einer Zeit der Befriedigung verlässt du das Gebäude mit einem noch nie dagewesenen Gefühl!`n`n
		`R(Diese Erfahrung hat dich total erneuert! Deine Sinne sind an der Grenze ihres Könnens. Deine Verteidigung steigt, und du erhältst einen zusätzlichen Waldkampf!)`n');
		$buff = array("name"=>"`4Erneuert`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger erneuert!`b`0","defmod"=>1.1,"roundmsg"=>"Deine Sinne sind an der Spitze!","activate"=>"defense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['turns'] += 1;
	}
	break;
	
case 'girlfour':
	$session['user']['seenlover']=1;
	$session['user']['gold']-=$costfour;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costfour.'`X Gold und steuerst die Treppe mit `AKyale`X an.`n`n');
	if (e_rand(0,1)==0)
	{
		output('`AKyale`X hat überhaupt kein Gefühl für sich selber.`n
		Sie sitzt in der Ecke und lehnt es ab, dich zu berühren!`n
		Sie sagt, du siehst abscheulich aus...`n
		Verärgert trinkst du deinen Drink, nimmst deine Sachen und verlässt das Bordell....`n
		Eine Menschenansammlung steht vor der Tür und zeigt auf dich....`n
		Du bist dir nicht sicher, wieso sie lachen, aber Kyale führt die Menge an.`n`n');
		switch (e_rand(1,3))
		{
		case 1:
			output('`n`4Du wurdest im Bordell mit Drogen versorgt. Dein Angriff und deine Verteidigung sind ein wenig niedriger.`n');
			$buff = array("name"=>"`4Drogen`0","rounds"=>60,"wearoff"=>"`5`bDie Drogen sind weg!`b`0","defmod"=>.95,"atkmod"=>.95,"roundmsg"=>"Du hast Drogen genommen. Es ist schwer zu kämpfent!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			break;
		case 2:
			output('`n`4Du wurdest im Bordell mit Drogen versorgt. Dein Angriff und deine Verteidigung sind niedriger.`n');
			$buff = array("name"=>"`4Drogen`0","rounds"=>60,"wearoff"=>"`5`bDie Drogen sind weg!`b`0","defmod"=>.85,"atkmod"=>.85,"roundmsg"=>"Du hast Drogen genommen. Es ist schwer zu kämpfen!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			break;
		case 3:
			output('`n`4Du wurdest im Bordell mit Drogen versorgt. Dein Angriff und deine Verteidigung sind viel niedriger.`n');
			$buff = array("name"=>"`4Drogen`0","rounds"=>60,"wearoff"=>"`5`bDie Drogen sind weg!`b`0","defmod"=>.75,"atkmod"=>.75,"roundmsg"=>"Du hast Drogen genommen. Es ist schwer zu kämpfen!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
		}
	}
	else
	{
		output('`n`XDas war das Beste, wofür du dein Gold ausgegeben hast!`n
		`AKyale`X weiß absolut was du magst.`n
		Sie bearbeitet dich richtig!!!`n
		Nach ein paar Momenten der Befriedigung verlässt du das Bordell, glücklich grinsend.`n`n');
		switch (e_rand(1,3))
		{
		case 1:
			output('`n`RDu bist in Begeisterung. Dein Angriff und deine Verteidigung sind etwas höher.`n');
			$buff = array("name"=>"`4Begeisterung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger euphorisch!`b`0","defmod"=>1.05,"atkmod"=>1.05,"roundmsg"=>"Du bist im Freudentaumel!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			break;
		case 2:
			output('`n`RDu bist in Begeisterung. Dein Angriff und deine Verteidigung sind höher.`n');
			$buff = array("name"=>"`4Begeisterung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger euphorisch!`b`0","defmod"=>1.15,"atkmod"=>1.15,"roundmsg"=>"Du bist im Freudentaumel!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			break;
		case 3:
			output('`n`RDu bist in Begeisterung. Dein Angriff und deine Verteidigung sind höher und du erhältst einen zusätzlichen Waldkampf.`n');
			$buff = array("name"=>"`4Begeisterung`0","rounds"=>60,"wearoff"=>"`5`bDu bist nicht länger euphorisch!`b`0","defmod"=>1.15,"atkmod"=>1.15,"roundmsg"=>"Du bist im Freudentaumel!","activate"=>"offense");
			$session['bufflist']['bordello']=$buff;
			$session['user']['turns'] += 1;
		}
	}
	break;
	
case 'girlfive':
	$session['user']['seenlover']=1;
	$session['user']['gold']-=$costfive;
	output('`XDu gibst Madam '.$madam.' aus deinem Beutel `^'.$costfive.'`X Gold und steuerst die Treppe mit `AMora`X an.`n`n');
	if (e_rand(0,1)==0)
	{
		output('`AMora`X beginnt dich zu verwöhnen....`n
		Sie lässt dich zurück mit geschlossenen Augen...`n
		Du hattest noch nie soviel Vergnügen in deinem Leben!!!`n
		Nun, du hattest noch nie soviel Vergnügen... zuviel, denn bevor es richtig zu Sache geht, bist du schon fertig!!!
		`n`n`4Es ist dir peinlich in die Stadt zu gehen. Erst in der Abenddämmerung traust du dich. Du verlierst Waldkämpfe.`n');
		$buff = array("name"=>"`4Peinlichkeit`0","rounds"=>60,"wearoff"=>"`5`bDu hast deine Peinlichkeit überwunden!`b`0","defmod"=>.65,"atkmod"=>.65,"roundmsg"=>"Du bist zu verlegen um voll zu kämpfen!","activate"=>"offense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['turns'] -= round($session['user']['turns']*0.5);
		
	}
	else
	{
		output('`n`XDu bist '.($session['user']['sex']?'DIE FRAU...':'DER MANN...').' !`n
		`AMora`X hatte noch nie Erfahrungen mit jemandem wie dir in ihrem Leben!!!`n
		Sie erzählt in der ganzen Stadt, wie gut du im Bett bist!!!`n
		Du verlässt das Bordell erhobenen Hauptes!!!`n
		`n`RDu bist die meistverehrte Person in der Stadt! Du erhältst ein paar Waldkämpfe!`n');
		$buff = array("name"=>"`4Großer Liebhaber`0","rounds"=>60,"wearoff"=>"`5`bDu fühlst dich nicht mehr länger hochmütig!`b`0","defmod"=>1.35,"atkmod"=>1.35,"roundmsg"=>"Der Stolz fließt durch deine Adern!","activate"=>"offense");
		$session['bufflist']['bordello']=$buff;
		$session['user']['turns'] += 5;
	}
	break;
}
addnav('Zurück');
addnav('B?Zur Bar','tittytwister.php');
addnav('G?Zur dunklen Gasse','slums.php');
page_footer();
?>

