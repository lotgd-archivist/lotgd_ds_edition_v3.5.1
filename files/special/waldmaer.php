<?php
/*
* Datei: waldmaer.php
* Waldereignis für LotGD 0.9.7 - www.atrahor.de
* Version: 1.3 ||letzte Änderung:  04.06.2006
*					16.8.06: Flucht-case eingebaut by talion.
* Autor: Fossla (fossla@atrahor.de || ICQ: 270-812-802)
*/

// Aufrufen der Seite ohne eingeloggt zu sein wird verhindert
if (!isset($session))
{
	exit();
}

$session['user']['specialinc'] = 'waldmaer.php';

switch($_GET['op'])
{
	case 'naehern':
		if(item_count('tpl_id="analloni_s" AND owner='.$session['user']['acctid'].' AND deposit1=0')>0)
		{
			$fall = e_rand(1,10);
		}
		else
		{
			$fall = e_rand(1,15);
		}
		switch ($fall)
		{
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				// Das Mädchen ist harmlos, für das Trösten gibt es Charmpunkte.
				output('`kDas Mädchen hat offensichtlich dein Mitleid geweckt und so trittst du näher an die Kleine heran. Du hockst dich freundlich vor sie und sprichst sie an.`n`n
				... Nachdem du dich ein wenig mit dem Mädchen unterhalten hast und nun weißt, dass sie sich verlaufen hat, lässt sie sich von dir den Weg zurück in die Stadt schildern und läuft fröhlich und dankbar von dannen.`n
				Du fühlst dich einfach super, weil du dem Mädchen helfen konntest.`n`nFür diese Tat bekommst du einen Charmepunkt.');
				$session['user']['charm']++;
				$session['user']['specialinc'] = '';
				break;
			case 7:
			case 8:
			case 9:
			case 10:
				// Das Mädchen entpuppt sich als Monster welches nur seine Gestallt verborgen hatte um unwissende heran zu locken. Jetzt ist dir klar warum hier kein Monster weit und breit war.
				output('`kDas Mädchen hat offensichtlich dein Mitleid geweckt und so trittst du näher an die Kleine heran.`n`n
				Sobald du nur noch wenige Meter von dem Kind entfernt bist, hebt sie plötzlich ihren Blick und sieht dich direkt an - mit roten Augen! Die bisher menschliche Gestallt erhebt sich und verändert sich auf schreckliche Weise in ein großes, haariges Ungetüm. Wie konntest du auf den Trick nur hereinfallen?`n`n');
				addnav('Auf in den Kampf!','forest.php?op=fighting&enemy=waldmaer');
				break;
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
				//Hier gibts ein Amulett
				output('`kDas Mädchen hat offensichtlich dein Mitleid geweckt und so trittst du näher an die Kleine heran. Du hockst dich freundlich vor sie und sprichst sie an.`n`n
				... Nachdem du dich ein wenig mit dem Mädchen unterhalten hast und nun weißt, dass sie sich verlaufen hatte, lässt sie sich von dir auf dem Weg zurück in die Stadt begleiten.`n
				Noch während ihr beide gemeinsam auf die Stadt zuhaltet, kommt mit einem Male die alte Kräuterfrau Caîna auf euch zu gerannt und umarmt das kleine Mädchen überschwänglich. Nach einigen Momenten der Wiedersehensfreude wendet sich Caîna dir zu und bedankt sich bei dir, dass du ihre Enkelin wohlbehalten zurück bringen konntest. Aus Dankbarkeit schenkt sie dir ein kleines Amulett, in dessen Mitte ein Anallôni-Harz-Stein eingelassen ist, in dem sich das Bild eines alten Klosters ausmachen lässt. Als du skeptisch eine Augenbraue hoch ziehst, beugt sie sich verschwörerisch zu dir und wispert dir zu, dass es sich um ein kraftvolles Amulett handle, welches auch den stärksten Geist zu bannen vermag.`n`n
				Nun, da sagst du natürlich nicht nein!');
				item_add($session['user']['acctid'],'analloni_s');
				$session['user']['specialinc'] = '';
				break;
				
		} // Ende der inneren Fallunterscheidung mit switch ob Mädchen harmlos oder nicht
		break;

	case 'gehen':
		output('`kDu machst dir keine weiteren Gedanken um das Mädchen und gehst einfach weiter in den Wald. Wenn du einfach alle Monster dort erledigst ist sie schließlich auch in Sicherheit! Ja, so ist es wohl das beste...`n`n');
		//addnav('Weiter...','forest.php');
		$session['user']['specialinc'] = '';
		break;

	case 'fighting':
		$badguy = array(
		"creaturename" => 'Waldmär',
		"creatureweapon" => 'grässliche Erscheinung',
		"creaturelevel" => $session['user']['level'],
		"creatureattack" => $session['user']['attack']+1,
		"creaturedefense" => $session['user']['defence'],
		"creaturehealth" => $session['user']['maxhitpoints']
		);
		$gegner['enemy_waldmaer'] = createstring($badguy);

		$session['user']['badguy'] = $gegner[ 'enemy_'.$_GET['enemy'] ];
		$_GET['op']="fight";
		$battle = true;
		break;
	case 'fight':
		$battle=true;
		break;
	case 'run':	// Fliehen
		if(e_rand(1,5) == 1) {
			output('`kEndlich schaffst du es, dich in die Büsche zu schlagen! Während du um dein Leben rennst, hörst du noch lange
			ein, zum Glück immer leiser werdendes, Rascheln hinter dir...`0');
			$session['user']['specialinc'] = '';
			addnav('Flucht in die Stadt!','village.php');
		}
		else {
			output('`kDeine Fluchtversuche bleiben ohne Erfolg. Das Monster schneidet dir regelmäßig den Weg ab!`n`n');	
			$battle=true;
		}				
		break;
	default:
		output('`kAuf deinem Weg durch den Wald hörst du plötzlich ein leises Schluchzen zu deiner linken Seite. Du wendest den Kopf und erblickst durch die Baumstämme hindurch ein kleines Wesen, welches zusammengekauert an einem Baumstamm lehnt.`n
		Neugierig wie du bist, trittst du näher heran und erkennst, dass es sich bei dem kleinen Wesen scheinbar um ein junges Mädchen handelt. Ihr Gesicht in den Händen verborgen, sitzt sie dort auf dem Waldboden und weint hörbar laut. Daher wunderst du dich, dass sie bisher noch keine Begegnung mit einem Monster gehabt zu haben scheint. Glück für sie!`n`n');
		addnav('Gehe zu dem Mädchen','forest.php?op=naehern');
		addnav('Zurück in den Wald','forest.php?op=gehen');

} // Ende schwitch mit get "op" - umfasst allen Inhalt der ausgegeben wird

if($battle == true)
{
	include_once('battle.php');
	if ($victory)
	{
		//addnav('Zurück in den Wald','forest.php');
		$session['user']['specialinc'] = '';
		$exp_plus = round($session['user']['experience'] * 0.01);
		$session['user']['experience'] += $exp_plus;
		output('`n`kDu bekommst ' . $exp_plus . ' Erfahrungspunkte dafür, dass du das Mädchen von seinem Fluch befreit hast!');
	}
	else if ($defeat)
	{
		addnews('' . $session['user']['name'] . '`k wurde zerfleischt im Wald gefunden.');
		killplayer(100,5,0,'news.php','Verdammt...');
		output('`n`kDie Bestie hat dich überlistet.`nDu verlierst 5% deiner Erfahrung und all dein Gold!');
		$session['user']['specialinc'] = '';
	}
	else
	{
		fightnav(true,true);
	}
}
