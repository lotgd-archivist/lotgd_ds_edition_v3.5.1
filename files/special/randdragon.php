<?php

// 22062004

/* Random Green Dragon Encounter v1 by Timothy Drescher (Voratus)
Current version can be found at Domarr's Keep (lgd.tod-online.com)
This is a simple "forest special" which helps to keep the main idea in mind, by giving any player an
encounter with the Green Dragon, and the results could be deadly.
The following names/locations are server-specific and should be changed:
	Plains of Al'Khadar (and reference to "plains")
	Domarr's Keep (the main city)

Version History
1.0 original version

german translation by anpera
some changes for my game - may not work with other versions!
*/

if (!isset($session)) exit();
$session['user']['specialinc']='randdragon.php';
if ($_GET['count']==3) 
{
	output('Der `@Grüne Drache`0 hat genug von deinem Geschwafel. Er bläst dich mit einem Feuerstoß weg!
	`n`nDu fragst dich noch, was schlimmer ist, der Schmerz, oder der Gestank deines verbrennenden Fleisches. Aber das spielt keine Rolle. Das Reich der Schatten empfängt dich.
	`n`n`4Du wurdest vom `@Grünen Drachen`4 gegrillt!
	`nDu verlierst 5% deiner Erfahrung und alles Gold.');
	addnews('`%'.$session['user']['name'].'`t wurde bei einer zufälligen Begegnung im Wald vom `@Grünen Drachen`t getötet!');
	killplayer(100,5,0,'news.php','Tägliche News');
} 
else 
{
	switch($_GET['op'])
	{
		case '':
			output('Bei deinem Streifzug durch die Wälder hörst du plötzlich ein lautes Brüllen. Das Geräusch lässt das Blut in deinen Adern gefrieren.
			`nEin tiefes Stampfen ist hinter dir zu hören. Starr vor Schreck fühlst du einen Stoß heißen Atem in deinem Nacken. Langsam drehst du dich um - und siehst einen riesigen `@Grünen Drachen`0 vor dir stehen.
			`n`nDas könnte Ärger geben...');
			addnav('Angreifen!','forest.php?op=slay');
			addnav('Um Gnade winseln','forest.php?op=cower');
			addnav('Rede dich raus','forest.php?op=talk');
			addnav('Lauf weg!','forest.php?op=flee');
			break;	
		case 'slay':
			output('Du hältst deine Waffe fest im Griff und bereitest dich auf den Angriff auf diese gewaltige Kreatur vor.
			`n`nDu brüllst einen Kampfschrei und springst auf den Drachen zu!
			`nDoch bevor deine Waffe den Drachen berührt, schlägt er sie dir mit seinem Schwanz aus der Hand und spuckt dir seinen Feueratem entgegen. ');
			if ($session['user']['level'] < 15) 
			{
				output('`n`nDer Strahl wirft dich zu Boden. Du kannst fühlen, wie sich durch die große Hitze schwere Blasen auf deiner Haut bilden. 
				`nGeschwächst schaust du zum `@Grünen Drachen`0 auf, der auf dich zu stolziert. ');
				if (rand(1,4)==1) 
				{
					output('Er beugt sich gerade zu dir herunter, um dich zu verschlingen, als plötzlich ein Pfeil scheinbar aus dem Nichts im Kopf des Drachen einschlägt.
					`nMit einem entsetzlichen Brüllen fliegt der Drache davon.
					`nDu kannst gerade noch einen Elfen auf dich zurennen sehen, dann wird dir schwarz vor Augen.
					`n`nEinige Zeit später erwachst du auf einer Lichtung. Deine Wunden wurden geheilt, aber nichts kann die Verletzungen, die Drachenatem verursacht, wirklich vollständig beseitigen.
					`nDu verlierst zwei Charmepunkte durch die Verbrennungen!');
					addnews('`%'.$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit dem `@Grünen Drachen`t überlebt.');
					$session['user']['charm']-=2;
					$session['user']['turns']-=2;
					if ($session['user']['turns'] < 0) $session['user']['turns']=0;
					$session['user']['reputation']++;
					$session['user']['hitpoints']=$session['user']['maxhitpoints'];
					$session['user']['specialinc']='';
				} 
				else 
				{
					output('`nDas ist das Letzte, was du siehst, bevor du in die ewige Dunkelheit gleitest.
					`n`n`4Du wurdest vom `@Grünen Drachen`4 gefressen!
					`nDu verlierst 10% deiner Erfahrung und alles Gold.');
					addnews('`%'.$session['user']['name'].'`t wurde bei einer zufälligen Begegnung im Wald vom `@Grünen Drachen`t getötet!');
					killplayer(100,10,0,'news.php','Tägliche News');
				}
			} 
			else 
			{
				output('`nDu schaffst es im letzten Moment, dem Feuerstoß aus dem Weg zu stolpern, um dich kurz darauf Auge in Auge mit diesem gewaltigen Biest zu finden. Spöttisch sagt er zu dir: "`5Nicht hier. Nicht jetzt.`0"
				`nMit diesen Worten hebt der Drache ab und steigt in die Lüfte davon. Du bist wieder alleine mit deinen Gedanken.');
				$session['user']['specialinc']='';
			}
			break;
		case 'cower':
			output('Du kauerst dich vor dem `@Grünen Drachen`0 zusammen und flehst um dein Leben. Der Drache schnaubt dir erneut seinen heißen Atem entgegen. "`5An jemandem, der so erbärmlich jammert, würde ich mir sicher nur den Magen verderben.
			`n`5Hau schon ab.`0"
			`nDu beschließt, dass es das Beste ist, den Anweisungen der Kreatur zu folgen, und so hoppelst du verängstigt davon.');
			//addnews('`%'.$session['user']['name'].'`5 grovelled '.($session['user']['sex']?'her':'his').' way out of being dinner for the Green Dragon.');
			//$session['user']['charm']--;
			$session['user']['specialinc']='';
			$session['user']['reputation']-=2;
			$session['user']['specialinc']='';
			break;
		case 'talk':
			output('Du bist der Meinung, dass du diese Begegnung überleben könntest, wenn es dir gelingt, den `@Grünen Drachen`0 in ein Gespräch zu verwickeln. Jetzt brauchst du nur noch etwas, worüber ihr reden könntet.`n');
			addnav('Themen');
			addnav('W?Das Wetter','forest.php?op=weather&count=0');
			addnav('G?Der Grüne Drache','forest.php?op=dragon&count=0');
			addnav('Violet','forest.php?op=violet&count=0');
			addnav('Seth','forest.php?op=seth&count=0');
			addnav('Cedrik','forest.php?op=cedrik&count=0');
			addnav(getsetting('townname','Atrahor'),'forest.php?op=city&count=0');
			addnav('Stottere unkontrolliert','forest.php?op=stutter&count=0');
			break;
		case 'weather':
			$count=$_GET['count'];
			$count++;
			$w = Weather::get_weather();
			output('"`qAlso '.$w['name'].', was hältst du von diesem Wetter?`0"
			`nDer Drache legt den Kopf schief und schaut dich an. Ein kurzes Schnauben schlägt dir heiße, dampfende Luft entgegen.
			`n`nVielleicht interessiert den Drachen etwas anderes mehr?');
			addnav('Themen');
			addnav('G?Der Grüne Drache','forest.php?op=dragon&count='.$count);
			addnav('Violet','forest.php?op=violet&count='.$count);
			addnav('Seth','forest.php?op=seth&count='.$count);
			addnav('Cedrik','forest.php?op=cedrik&count='.$count);
			addnav(getsetting('townname','Atrahor'),'forest.php?op=city&count='.$count);
			addnav('Stottere unkontrolliert','forest.php?op=stutter&count='.$count);
			break;
		case 'dragon':
			$count=$_GET['count'];
			$count++;
			output('"`qDu bist also der Grüne Drache, hä?`0"
			`nDer Drache gibt ein ohrenbetäubendes Brüllen von sich und leckt sich dann die Lippen. Vielleicht wäre ein anderes Thema besser zur Unterhaltung geeignet.');
			addnav('Themen');
			addnav('W?Das Wetter','forest.php?op=weather&count='.$count);
			addnav('Violet','forest.php?op=violet&count='.$count);
			addnav('Seth','forest.php?op=seth&count='.$count);
			addnav('Cedrik','forest.php?op=cedrik&count='.$count);
			addnav(getsetting('townname','Atrahor'),'forest.php?op=city&count='.$count);
			addnav('Stottere unkontrolliert','forest.php?op=stutter&count='.$count);
			break;
		case 'violet':
			$count=$_GET['count'];
			$count++;
			output('"`qDiese Violet ist ganz schön süss, was?`0"
			`nDer Drache nickt. "`5Ein schmackhafter, süsser Happen wäre das Eine Schande, dass sie niemals die Schenke verlässt. Aber vielleicht wirst du meinen Hunger solange stillen?.`0"
			`nDu solltest dir etwas anderes ausdenken. Schnell!');
			addnav('Themen');
			addnav('W?Das Wetter','forest.php?op=weather&count='.$count);
			addnav('G?Der Grüne Drache','forest.php?op=dragon&count='.$count);
			addnav('Seth','forest.php?op=seth&count='.$count);
			addnav('Cedrik','forest.php?op=cedrik&count='.$count);
			addnav(getsetting('townname','Atrahor'),'forest.php?op=city&count='.$count);
			addnav('Stottere unkontrolliert','forest.php?op=stutter&count='.$count);
			break;
		case 'seth':
			$count=$_GET['count'];
			$count++;
			output('"`qSeth ist ein netter Kerl, stimmts?`0"
			`nDer Drache dreht den Kopf in Gedanken.
			`n"`5Ein bisschen schwer zu schlucken, würde ich wetten, aber er verlässt ja nie die Schenke. Du dagegen hast es getan.`0"
			`nDer Drache schaut dich hungrig an. Zeit für eien Themenwechsel!');
			addnav('Themen');
			addnav('W?Das Wetter','forest.php?op=weather&count='.$count);
			addnav('G?Der Grüne Drache','forest.php?op=dragon&count='.$count);
			addnav('Violet','forest.php?op=violet&count='.$count);
			addnav('Cedrik','forest.php?op=cedrik&count='.$count);
			addnav(getsetting('townname','Atrahor'),'forest.php?op=city&count='.$count);
			addnav('Stottere unkontrolliert','forest.php?op=stutter&count='.$count);
			break;
		case 'cedrik':
			$count=$_GET['count'];
			$count++;
			output('"`qCedrik ist ein mürrischer alter Kerl, meinst du nicht auch?`0"
			`nDer Drache blinzelt langsam. "`5Dieser Sterbliche interessiert mich nicht. Aber du bietest dich mir doch geradezu an.`0"
			`nMan braucht keinen Gedankenleser, um zu erfahren, was dieses Ding denkt. Und du solltest seine Gedanken schnell in eine andere Richtung lenken.');
			addnav('Themen');
			addnav('W?Das Wetter','forest.php?op=weather&count='.$count);
			addnav('G?Der Grüne Drache','forest.php?op=dragon&count='.$count);
			addnav('Violet','forest.php?op=violet&count='.$count);
			addnav('Seth','forest.php?op=seth&count='.$count);
			addnav(getsetting('townname','Atrahor'),'forest.php?op=city&count='.$count);
			addnav('Stottere unkontrolliert','forest.php?op=stutter&count='.$count);
			break;
		case 'city':
			$count=$_GET['count'];
			$count++;
			output('"`qWusstest du, dass die Stadt '.getsetting('townname','Atrahor').' heißt? Ist ein ziemlich beeindruckendes Örtchen!`0"
			`nDer Drache gröhlt laut.
			`n"`5Diese stinkende Stadt ist mir ein Dorn im Auge, weiter nichts! Ich sollte seine schwachen Mauern niederreißen und die Stadt niederbrennen! Alle sollten `bmeinen`b Namen kennen und mich fürchten!`0"
			`nNun, es scheint so, als ob du die Kreatur verärgert hast. Vielleicht hilft ein Themenwechsel.');
			addnav('Themen');
			addnav('W?Das Wetter','forest.php?op=weather&count='.$count);
			addnav('G?Der Grüne Drache','forest.php?op=dragon&count='.$count);
			addnav('Violet','forest.php?op=violet&count='.$count);
			addnav('Seth','forest.php?op=seth&count='.$count);
			addnav('Cedrik','forest.php?op=cedrik&count='.$count);
			addnav('Name?','forest.php?op=name&count='.$count);
			addnav('Stottere unkontrolliert','forest.php?op=stutter&count='.$count);
			break;
		case 'stutter':
			$count=$_GET['count'];
			$count++;
			output('Du versuchst, ein intelligentes Thema zu finden, aber stattdessen stotterst du nur unkontrolliert vor dich hin. Der Drache rollt dramatisch mit den Augen. Er schlägt dich mit seinem Schwanz auf den Hinterkopf und du wirst ohnmächtig.
			`n`nEinige Zeit später wachst du mit einer gewaltigen Beule am Kopf wieder auf.`n');
			if ($session['user']['hitpoints'] > $session['user']['maxhitpoints']*0.1) 
			{
				$session['user']['hitpoints']=round($session['user']['maxhitpoints']*0.1);
			} 
			else 
			{
				$session['user']['hitpoints']=1;
			}		
			$session['user']['turns']--;
			if ($session['user']['turns'] < 0) $session['user']['turns']=0;
			addnews('`%'.$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit dem `@Grünen Drachen`t überlebt.');
			$session['user']['reputation']++;
			$session['user']['specialinc']='';
			$session['user']['specialinc']='';
			break;
		case 'name':
			output('"`qWie ist dein Name, oh mächtiger Drache?`0"
			`nDer Drache betrachtet dich ernst. "`5Du wärst nicht in der Lage, ihn richtig auszusprechen. Es sind nur wenige in dieser Welt übrig, die das können, denn es verlangt die Sprachfertigkeit eines ausgewachsenen Drachen, von denen es nur noch wenige gibt. Unsere einst große und stolze Rasse wurde von den niederen Rassen zu Aasfressern reduziert, aus Angst, wir könnten sie alle vernichten.`0"
			`nDer Drache schaut einen Moment weg, dann wendet er sich dir erneut zu. "`5Drachen haben nur getötet, was wir als Nahrung brauchten. Jetzt töten wir, um zu überleben.`0"
			`n`n"`5Weiche von mir, bevor ich mich entschließe, dich ohne Grund zu töten.`0"
			`nDu hältst es für eine gute Idee, dich zu beeilen, bevor es sich der Drache anders überlegt und einen Snack aus dir macht.');
			addnews('`%'.$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit dem `@Grünen Drachen`t überlebt.');
			$session['user']['specialinc']='';
			break;
		case 'flee':
			$results=rand(1,4);
			if ($results==1) 
			{
				output('Du drehst dich um und flüchtest so schnell du kannst vor der Macht des Drachens. Du glaubst, du schaffst es, denn du hörst keinen Verfolger hinter dir.
				`nDu hältst an, um dich umzudrehen. Keine Spur vom Drachen!
				`nDu hast wirklich Glück gehabt.');
				addnews('`%'.$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit dem `@Grünen Drachen`t überlebt.');
				$session['user']['specialinc']='';
				$session['user']['reputation']--;
				$session['user']['specialinc']='';
			} 
			elseif ($results==4) 
			{
				output('Du drehst dich um und flüchtest so schnell du kannst vor der Macht des Drachens. Du glaubst, du schaffst es, denn du hörst keinen Verfolger hinter dir.
				`nDu hältst an, um dich umzudrehen. Keine Spur vom Drachen!
				`nDu glaubst schon, du bist dem Biest entkommen und drehst dich wieder um. Und da steht der Drache direkt vor dir und sein weit aufgerissenes Maul rast auf dich zu.
				`nBevor du Zeit hast, zu reagieren, hat dich der Drache verspeist.. 
				`n`n`4Du bist gestorben!
				`nDu verlierst 10% deiner Erfahrung und all dein Gold.');
				addnews('`%'.$session['user']['name'].'`t wurde bei einer zufälligen Begegnung im Wald vom `@Grünen Drachen`t getötet!');
				killplayer(100,10,0,'news.php','Tägliche News');
			} 
			else 
			{
				$damage=e_rand(round($session['user']['maxhitpoints']*0.5,0),$session['user']['maxhitpoints']);
				output('Du drehst dich um um vor der Macht des Drachen zu fliehen. Während du rennst, fühlst du plötzlich, wie du von einer Welle der Hitze umschlossen wirst. Der Drache hat dich mit einem Feuerstoß erwischt! Du verlierst '.$damage.' Lebenspunkte durch diesen Treffer!`n');
				$session['user']['hitpoints']-=$damage;
				if ($session['user']['hitpoints'] < 1) 
				{
					$session['user']['hitpoints']=0;
					output('`n`4Du bist gestorben!
					`nDu verlierst 10% deiner Erfahrungspunkte und alles Gold!');
					addnews('`%'.$session['user']['name'].'`t wurde bei einer zufälligen Begegnung im Wald vom `@Grünen Drachen`t getötet!');
					killplayer(100,10,0,'news.php','Tägliche News');
				} 
				else 
				{
					output('Du rollst dich auf dem Boden, um das Feuer zu löschen. Nachdem du festgestellt hast, dass du nicht tot bist, blickst du dich nach dem Drachen um. Doch der ist verschwunden. Hat er dich absichtlich nicht getötet? Oder war er bloß der Meinung, dass du jetzt verkocht bist?
					`nDie Antwort wirst du nie erfahren, so machst du dich wieder auf den Weg. Ein Besuch beim Heiler dürfte jetzt erstmal an der Reihe sein.');
					addnews('`%'.$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit dem `@Grünen Drachen`t überlebt.');
					$session['user']['specialinc']='';
				}
			}
			break;
	}
}
?>
