<?php
/* *******************
Altar of sacrifice
Written by TheDragonReborn
Based on Forest.php

Translation by Lendara Mondkind (Lisandra)
20.11.2008 Komplettüberarbeitung by Salator, Einwohner und Trophäen hinzu

******************* */

$session['user']['specialinc']='sacrificealtar.php';

if ($_GET['op']=='') //Startbild
{
	output('`@Als du durch den Wald wanderst, entdeckst du plötzlich einen Steinaltar.
	Er wurde aus Basaltstein unter einen riesigen Baum gebaut.
	Du gehst näher zu ihm hin und du siehst eingetrocknete Blutflecken von Jahrhunderten der Opferungen.
	Das ist eindeutig ein besonderer Ort und du kannst eine göttliche Präsenz spüren.
	`nDu solltest den Göttern vielleicht etwas opfern, um sie nicht zu beleidigen.
	`n`nWas wirst du tun?');
	addnav('Was opfern?');
	addnav('Monster');
	addnav('t?Ein starkes Monster','forest.php?op=sacrifice&type=creature&difficulty=strong');
	addnav('m?Ein mittleres Monster','forest.php?op=sacrifice&type=creature&difficulty=moderate');
	if ($session['user']['level'] > 1)
	{
		addnav('s?Ein schwaches Monster','forest.php?op=sacrifice&type=creature&difficulty=weak');
	}
	addnav('Stadtbewohner');
	addnav('Dich selbst','forest.php?op=sacrifice&type=yourself');
	if($session['user']['reputation']<35)
	{
		addnav('a?Einen alten Einwohner','forest.php?op=sacrifice&type=player&difficulty=strong');
		addnav('Irgendeinen Einwohner','forest.php?op=sacrifice&type=player&difficulty=moderate',true);
		addnav('H?Einen Hilflosen','forest.php?op=sacrifice&type=player&difficulty=weak');
	}
	addnav('F?Totes Fleisch','forest.php?op=sacrifice&type=trophy',true);
	addnav('Gegenstände');
	addnav('Waldblumen','forest.php?op=sacrifice&type=flowers');
	if ($session['user']['gems']>0)
	{
		addnav('Edelstein','forest.php?op=sacrifice&type=gem');
	}
	addnav('v?`nAltar verlassen','forest.php?op=leave');
}

else if ($_GET['op']=='sacrifice')
{
	if ($_GET['type']=='yourself') //selbst opfern
	{
		$session['user']['specialinc']='';
		output('`@Du entledigst dich deiner Sachen und legst dich auf den Altar.
		Als du deine '.$session['user']['weapon'].'`@ erhebst, denkst du an die Liebe. 
		Dann, ohne weitere Verzögerung, nimmst du dir das Leben.
		Als sich die Dunkelheit deiner bemächtigt, ');
		switch (e_rand(1,15))
		{
			case 1: //tot
			case 2:
			case 3:
				output('denkst du, dass du genug getan hast, um die Götter zu besänftigen, damit diese die Welt
				zu einem besseren Ort machen...
				`n`nLeider wirst DU nicht dabei sein, um es zu sehen.
				`n`n`^Du bist tot!`n
				Du verlierst all dein Gold!`n
				Du verlierst 5% deiner Erfahrung.`n
				Du kannst morgen wieder weiterspielen.');
				killplayer();
				if(mb_strtolower(mb_substr($session['user']['name'],-1))=='s')
				{
					addnews($session['user']['name'].'\' Körper wurde auf einem Altar in den Wäldern gefunden.');
				}
				else
				{
					addnews($session['user']['name'].'s Körper wurde auf einem Altar in den Wäldern gefunden.');
				}
				break;
			case 4: //tot
			case 5:
				output('siehst du wie der Himmel vom Zorn der Götter rot wird.
				Sie sind nicht so leichtgläubig wie du gedacht hast.
				Sie wissen warum du das getan hast.
				Niemand, der sich selbst respektiert, würde einer Selbstopferung zustimmen, wenn er nicht denken würde, dass er etwas dafür erhält.
				Ein gewaltiger Blitz kommt vom Himmel herab und trifft deinen toten Körper.
				Dabei nimmt der Blitz einige deiner Angriffs- und Verteidigungsfähigkeiten mit.
				Nun, das ist es, was du dafür erhältst, dass du die Götter betrügen wolltest.
				`n`n`^Du bist gestorben!`n
				Du verlierst all dein Gold!`n
				Du verlierst 5% deiner Erfahrung!`n
				Du verlierst 1 Punkt in Angriff und Verteidigung!`n
				Du kannst morgen wieder weiterspielen.');
				killplayer();
				if ($session['user']['attack'] >= 2)
				{
					$session['user']['attack']--;
				}
				if ($session['user']['defence'] >= 2)
				{
					$session['user']['defence']--;
				}
				if(mb_strtolower(mb_substr($session['user']['name'],-1))=='s')
				{
					addnews($session['user']['name'].'\' Überbleibsel wurden verkohlt auf einem Altar gefunden.');
				}
				else
				{
					addnews($session['user']['name'].'s Überbleibsel wurden verkohlt auf einem Altar gefunden.');
				}
				break;
			case 6: //charm ++
			case 7:
			case 8:
			case 9:
				output('siehst du ein strahlendes Leuchten.
				Es formt sich langsam zur Gestalt eines gutmütigen alten Mannes.
				`n`n"`#'.($session['user']['sex']?'Meine geliebte Tochter':'Mein geliebter Sohn').',"`@ sagt er, "`#Du hast mir die höchste Opferung erbracht und dafür werde ich Dich belohnen.`@"
				`n`nEr erhebt seine Hand und fährt sie an der gesamten Länge deines Körpers entlang.
				Er hält sie ganz knapp vor der Berührung mit dir.
				Du fühlst wie eine warme Energie durch dich wandert und alles fängt an klarer zu werden.
				Du stehst auf und erkennst, dass die Wunde, die du dir mit '.$session['user']['weapon'].'`@ zugefügt hast, komplett geheilt ist.
				Du schaust dich nach dem alten Mann um, doch er ist verschwunden.
				`n`nDu nimmst deine Sachen wieder auf und machst dich bereit weiterzugehen. 
				Als du an einer Wasserpfütze vorbei gehst, siehst du zufällig hinein und siehst dein Spiegelbild.
				Du siehst wesentlich '.($session['user']['sex']?'schöner':'angenehmer').' aus als je zuvor.
				Es muss ein Geschenk der Götter sein.
				`n`n`^Du erhältst 2 Charmepunkte!');
				$session['user']['charm']+=2;
				break;
			case 10: //maxhitpoints ++
			case 11:
			case 12:
			case 13:
				output('siehst du ein strahlendes Leuchten.
				Es formt sich langsam zur Gestalt eines gutmütigen alten Mannes.
				`n`n"`#'.($session['user']['sex']?'Meine geliebte Tochter':'Mein geliebter Sohn').',"`@ sagt er, "`#Du hast mir die höchste Opferung erbracht und dafür werde ich Dich belohnen.`@"
				`n`nEr erhebt seine Hand und fährt sie an der gesamten Länge deines Körpers entlang.
				Er hält sie ganz knapp vor der Berührung mit dir.
				Du fühlst wie eine warme Energie durch dich wandert und alles fängt an klarer zu werden.
				Du stehst auf und erkennst, dass die Wunde, die du dir mit '.$session['user']['weapon'].'`@ zugefügt hast, komplett geheilt ist.
				Du schaust dich nach dem alten Mann um, doch er ist verschwunden.
				`n`nAls du den Altar verlässt, fällt dir auf, dass du mehr Lebenspunkte als zuvor hast.
				`n`n`^Deine maximalen Lebenspunkte sind `bpermanent`b um 1 Punkt gestiegen!');
				$session['user']['maxhitpoints']++;
				break;
			case 14: //att&def ++
			case 15:
				output('siehst du ein strahlendes Leuchten.
				Es formt sich langsam zur Gestalt eines gutmütigen alten Mannes.
				`n`n"`#'.($session['user']['sex']?'Meine geliebte Tochter':'Mein geliebter Sohn').',"`@ sagt er, "`#Du hast mir die höchste Opferung erbracht und dafür werde ich Dich belohnen.`@"
				`n`nEr erhebt seine Hand und fährt sie an der gesamten Länge deines Körpers entlang.
				Er hält sie ganz knapp vor der Berührung mit dir.
				Du fühlst wie eine warme Energie durch dich wandert und alles fängt an klarer zu werden.
				Du stehst auf und erkennst, dass die Wunde, die du dir mit '.$session['user']['weapon'].'`@ zugefügt hast, komplett geheilt ist.
				Du schaust dich nach dem alten Mann um, doch er ist verschwunden.
				`n`nAls du den Altar verlässt, fällt dir auf, dass deine Muskeln größer geworden sind.
				`n`n`^Du erhältst einen Angriffs- und einen Verteidigungspunkt!');
				$session['user']['attack']++;
				$session['user']['defence']++;
				break;
		}
	}
	
	else if ($_GET['type']=='player') //Spieler opfern, Gegner erstellen
	{
		$difficulty=$_GET['difficulty'];
		output('`2Du entscheidest dich, einen armen Stadtbewohner an die Götter zu opfern. 
		Darum gehst du in die Stadt und suchst jemanden, den du zu einer Opferzeremonie "überreden" kannst.`n');
		$session['user']['turns']--;

		if ($difficulty=='weak')
		{
			$sql_where='dragonkills <= '.($session['user']['dragonkills']*1.1).'
			AND hitpoints <='.($session['user']['maxhitpoints']*1.1).'
			AND level ='.max($session['user']['level']-1,1);
			output('`$Du gehst in ein Gebiet der Stadt, von dem du weißt, dass sich dort eher hilflose Einwohner aufhalten.`0`n');
		}
		elseif ($difficulty=='strong')
		{
			$sql_where='dragonkills >= '.($session['user']['dragonkills']*0.8).'
			AND hitpoints >='.($session['user']['maxhitpoints']*0.7).'
			AND level ='.min($session['user']['level']+1,15);
			output('`$Du gehst in ein Gebiet der Stadt, in dem die fiesesten Schläger zu hause sind, in der Hoffnung, dort einen Verletzten zu finden.`0`n');
		}
		else
		{
			$sql_where='attack <='.($session['user']['attack']*1.1).'
			AND defence <='.($session['user']['defence']*1.1).'
			AND hitpoints >='.($session['user']['maxhitpoints']*0.7).'
			AND hitpoints <='.($session['user']['maxhitpoints']*1.2).'
			AND level IN('.($session['user']['level']-1).','.$session['user']['level'].','.($session['user']['level']+1).')';
		}
		
		$sql = 'SELECT acctid,
			name AS creaturename,
			level AS creaturelevel,
			attack AS creatureattack,
			defence AS creaturedefense,
			weapon AS creatureweapon,
			hitpoints AS creaturehealth,
			charm AS creaturegold,
			age AS creatureexp
		FROM accounts 
		WHERE '.$sql_where.'
			AND race != "'.$session['user']['race'].'"
			AND alive=1
			AND loggedin=0
		ORDER BY rand('.e_rand().')
		LIMIT 1';
		$result = db_query($sql);
		
		//output('`n'.$sql.'`n');
		
		if(db_num_rows($result)>0)
		{
			$badguy = db_fetch_assoc($result);
			$expflux = $badguy['creatureexp']+1;
			$expflux = e_rand(-$expflux,$expflux);
			$badguy['creatureexp']=($badguy['creatureexp']+1)*10;
			$badguy['creaturegold']=max($session['user']['level']*242,$badguy['creaturegold']);
			$badguy['diddamage']=0;
			$session['user']['badguy']=createstring($badguy);
			$battle=true;
		}
		else
		{
			output('`2Leider findest du kein geeignetes Opfer.
			`nDennoch hast du einen Waldkampf vertrödelt...');
			if($session['user']['turns']>0)
			{
				addnav('Zum Altar zurückkehren','forest.php?op=');
			}
		}
	}
	
	else if ($_GET['type']=='creature') //Monster opfern, Gegner erstellen
	{
		$difficulty=$_GET['difficulty'];
		output('`2Du entscheidest dich, eine unglückselige Kreatur an die Götter zu opfern. 
		Darum gehst du in den Wald und schaust dich nach einem passenden Geschenk um.`n');
		$session['user']['turns']--;
		$battle=true;
		if (e_rand(0,2)==1)
		{
			$plev = (e_rand(1,5)==1?1:0);
			$nlev = (e_rand(1,3)==1?1:0);
		}
		else
		{
			$plev=0;
			$nlev=0;
		}

		if ($difficulty=='weak')
		{
			$nlev++;
			output('`$Du gehst in ein Gebiet des Waldes, von dem du weißt, dass sich dort eher leichtere Gegner aufhalten.`0`n');
		}

		if ($difficulty=='strong')
		{
			$plev++;
			output('`$Du gehst in ein Gebiet des Waldes, welches Kreaturen aus deinen Alpträumen enthält, in der Hoffnung, dass du eine verletzte findest.`0`n');
		}
		$targetlevel = ($session['user']['level'] + $plev - $nlev );
		if ($targetlevel<1)
		{
			$targetlevel=1;
		}
		$sql = "SELECT * FROM creatures WHERE creaturelevel = $targetlevel ORDER BY rand(".e_rand().") LIMIT 1";
		$result = db_query($sql);
		$badguy = db_fetch_assoc($result);
		$expflux = round($badguy['creatureexp']/10,0);
		$expflux = e_rand(-$expflux,$expflux);
		$badguy['creatureexp']+=$expflux;
		$badguy['playerstarthp']=$session['user']['hitpoints'];
		$dk = 0;

		if (is_array($session['user']['dragonpoints']))
		{
			foreach ($session['user']['dragonpoints'] as $val)
			{
				if ($val=="at" || $val=="de")
				{
					$dk++;
				}
			}
		}

		$dk += (int)(($session['user']['maxhitpoints'] - ($session['user']['level']*10)) / 5);
		$dk = round($dk * 0.25, 0);

		$atkflux = e_rand(0, $dk);
		$defflux = e_rand(0, ($dk-$atkflux));
		$hpflux = ($dk - ($atkflux+$defflux)) * 5;
		$badguy['creatureattack']+=$atkflux;
		$badguy['creaturedefense']+=$defflux;
		$badguy['creaturehealth']+=$hpflux;
		if ($session['user']['race']=='vmp')
		{
			$badguy['creaturegold']*=1.2;
		}
		$badguy['creaturegold'] = round($badguy['creaturegold']);
		$badguy['diddamage']=0;
		$session['user']['badguy']=createstring($badguy);
	}
	
	else if ($_GET['type']=='trophy') //Trophäe opfern
	{
		output('`2Du schaust in deinen Beutel, ob du vielleicht etwas Blutverschmiertes findest, was du opfern kannst.`n');
		$sql='SELECT id, name, gold, gems, value1
			FROM items
			WHERE owner='.$session['user']['acctid'].'
				AND tpl_id="trph"
				AND deposit1=0
				AND deposit2=0
			ORDER BY value1 ASC, hvalue DESC
			LIMIT 100';
		$result=db_query($sql);
		if(db_num_rows($result)>0)
		{ //Liste ausgeben
			$minDK=round($session['user']['dragonkills']*0.5);
			$maxDK=round($session['user']['dragonkills']*1.2);
            /** @noinspection PhpUndefinedVariableInspection */
            $str_out.='`n`0<table border=0>
			<tr class="trhead">
			<th>Name</th>
			<th>DK</th>
			<th>Wert</th>
			</tr>';
            $trclass='trlight';
			while($row=db_fetch_assoc($result))
			{
				$trclass=($trclass=='trlight'?'trdark':'trlight');
				if($row['value1']<$minDK)
				{
					$dif='weak';
				}
				elseif($row['value1']>$maxDK)
				{
					$dif='strong';
				}
				else
				{
					$dif='moderate';
				}
				$str_out.='<tr class="'.$trclass.'">
				<td>'.create_lnk($row['name'].'`0','forest.php?op=won&difficulty='.$dif.'&badguyname='.urlencode($row['name']).'&itemid='.$row['id']).'</td>
				<td align="center">'.$row['value1'].'</td>
				<td align="right">`^'.$row['gold'].'`0/`#'.$row['gems'].'`0</td>
				</tr>';
			}
			$str_out.='</table>';
			output($str_out);
			addnav('Nichts davon opfern','forest.php?op=');
		}
		else
		{
			output('Leider findest du nichts dergleichen, außer einem `orosa`2 Schlüpfer. Ich will gar nicht wissen wie der -noch dazu in diesem Zustand- in deinen Beutel gekommen ist...');
			addnav('Zum Altar zurückkehren','forest.php?op=');
		}
	}
	
	else if ($_GET['type']=='gem') //Edelstein opfern
	{
		$session['user']['specialinc']='';
		switch (e_rand(1,2))
		{
			case 1:
				output('`#Du legst einen deiner hart verdienten Edelsteine auf den Altar und wartest ab was passiert.
				Aber es passiert nichts, gar nichts.
				Du bist natürlich schlau und versuchst ein paar Tricks wie `iim Busch verstecken, eine Art Regentanz, zu Edelstein und Altar sprechen, beten und Purzelbäume schlagen,`i aber trotz deiner Bemühungen... es passiert nichts.
				`nAlso beschließt du, den Edelstein wieder mitzunehmen und stattdessen ein paar Monster zu töten.
				`nDeine versuchten Tricks haben dich die Zeit für 1 Waldkampf gekostet.');
				$session['user']['turns']--;
				break;
			case 2:
				output('`#Du legst einen deiner hart verdienten Edelsteine auf den Altar und als du ihn kurz aus den Augen lässt, ist der Edelstein verschwunden!
				`nDu wartest, ob etwas passiert, aber es passiert nichts.
				Du wirst wütend wegen deiner Dummheit und erhältst einen Waldkampf!');
				$session['user']['turns']++;
				$session['user']['gems']--;
				$session['user']['donation']+=1;
				break;
		}
	}
	
	else if ($_GET['type']=='flowers') //Blumen opfern
	{
		if (!$_GET['flower'])
		{
			$session['user']['turns']--;
			output('`@Du suchst im Wald nach wilden Blumen, bis du auf eine Wiese mit verschiedenen Blumen gelangst.
			Dort sind`$ Rosen`@, `&Gänseblümchen`@, und `^Löwenzahn`@.
			`n Welche möchtest du opfern?');
			addnav('R?Opfere Rosen','forest.php?op=sacrifice&type=flowers&flower=roses');
			addnav('G?Opfere Gänseblümchen','forest.php?op=sacrifice&type=flowers&flower=daisies');
			addnav('L?Opfere Löwenzahn','forest.php?op=sacrifice&type=flowers&flower=dandelions');
			addnav('`nZum Altar zurückkehren','forest.php?op=');
		}
		else
		{
			$session['user']['specialinc']='';
			if ($_GET['flower']=='roses')
			{
				output('`@Du legst die Rosen als Opfergabe auf den Altar.
				Du senkst deinen Kopf zum Gebet an die Götter und bittest sie, die Opfergabe anzunehmen.
				Als du deinen Kopf wieder anhebst um auf den Altar zu schauen, ');
				switch (e_rand(1,7))
				{
					case 1:
						output('siehst du einen `^wütenden Hasen`@!
						Du dachtest doch nicht, dass Götter, die einen blutverschmierten Altar haben, wirklich eine Opfergabe bestehend aus Blumen akzeptieren würden?
						Wirklich, wer würde so etwas denken? 
						Jetzt wirst du deinen Tod finden, welcher dich mit großen und scharfen Zähnen erwartet!
						`n`n`^Du wurdest getötet von einem `$wütenden Hasen`^!`n
						Du verlierst all dein Gold!
						`nDu verlierst 10% deiner Erfahrung!
						`nDu kannst morgen wieder weiterspielen.');
						killplayer();
						$session['user']['donation']+=1;
						if(mb_strtolower(mb_substr($session['user']['name'],-1))=='s')
						{
							addnews($session['user']['name'].'\' Körper wurde gefunden... angeknabbert von Hasen!');
						}
						else
						{
							addnews($session['user']['name'].'s Körper wurde gefunden... angeknabbert von Hasen!');
						}
						break;
					case 2:
					case 3:
					case 4:
						output('siehst du eine wunderschöne Frau vor dir stehen.
						`n`n\'`#'.($session['user']['sex']?'Meine geliebte Tochter':'Mein geliebter Sohn').',`@\' sagt sie, \'`# ich danke Dir für das Geschenk der Rosen.
						Ich weiß, dass Du ein hartes Leben hinter Dir hast, also erhältst du ein Geschenk von mir.`@\'
						`n`nSie legt ihre Hand auf deinen Kopf und du fühlst ein warmes Gefühl durch deinen Körper gleiten.
						Als sie ihre Hand von deinem Kopf nimmt, sagt sie dir, dass du in die Wasserpfütze beim Altar schauen sollst.
						Du gehst zur Wasserpfütze und schaust hinein. Du bemerkst, dass du ein wenig '.($session['user']['sex']?'schöner':'angenehmer').' aussiehst als zuvor.
						Du gehst zum Altar zurück und bemerkst, dass die Göttin verschwunden ist.
						Wie war wohl ihr Name?
						`n`n`^Du erhältst 1 Charmepunkt!');
						$session['user']['charm']++;
						break;
					case 5:
					case 6:
					case 7:
						output('siehst du eine wunderschöne Frau vor dir stehen.
						`n`n\'`#'.($session['user']['sex']?'Meine geliebte Tochter':'Mein geliebter Sohn').',`@\' sagt sie, \'`# ich danke Dir für das Geschenk der Rosen.
						Ich weiß, dass Du ein hartes Leben hinter Dir hast, also erhältst du ein Geschenk von uns.`@\'
						`n`nSie sagt dir, dass du in die Wasserpfütze beim Altar schauen sollst.
						Du gehst zur Wasserpfütze und schaust hinein.
						Du siehst etwas funkelndes im Wasser! Du schaust zurück zum Altar und bemerkst, dass die Göttin verschwunden ist.
						Wie war wohl ihr Name?
						`n`n`^Du hast `%ZWEI`^ Edelsteine gefunden!');
						$session['user']['gems']+=2;
						break;
				}
			}
			
			else if ($_GET['flower']=='daisies')
			{
				output('`@Du legst die Gänseblümchen als Opfergabe auf den Altar.
				Du senkst deinen Kopf zum Gebet an die Götter und bittest sie, die Opfergabe anzunehmen.
				Als du deinen Kopf hebst und zum Altar schaust, ');
				switch (e_rand(1,12))
				{
					case 1:
						output('siehst du wie sich die Gänseblümchen in eine `^riesige Venus-Fliegenfalle`@, verwandeln, mit dem Unterschied, dass diese keine Fliegen fängt.
						Bevor du fliehen oder deine Waffe in die Hand nehmen kannst, hat dich die Pflanze bereits mit ihrem Maul verschlungen.
						Du bist nun dabei, in den nächsten 100 Jahren langsam verdaut zu werden.
						Denk über deine Fehler nach, genug Zeit dafür hast du nun...
						`n`n`^Du wurdest gefressen von einer `$Riesen Venus Fliegenfalle`^!
						`nDu verlierst all dein Gold!
						`nDu verlierst 10% deiner Erfahrung!
						`nDu kannst morgen wieder weiterspielen.');
						killplayer(100,10);
						$session['user']['donation']+=1;
						if(mb_strtolower(mb_substr($session['user']['name'],-1))=='s')
						{
							addnews($session['user']['name'].'\' Waffen wurden bei einer Riesenpflanze gefunden, aber mehr konnte nicht herausgefunden werden.');
						}
						else
						{
							addnews($session['user']['name'].'s Waffen wurden bei einer Riesenpflanze gefunden, aber mehr konnte nicht herausgefunden werden.');
						}
						break;
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
						output('siehst du ein junges Mädchen, welches auf dem Altar sitzt und die Gänseblümchen in der Händen hält.
						`n`n`#Er liebt mich, er liebt mich nicht. Er liebt mich, er liebt mich nicht,`@\' sagt sie während sie die Blumenblätter abrupft.
						Du starrst sie bewundernd an, bis sie das letzte Blumenblatt rupft.
						`n`n');
						if (e_rand(0,1)==0)
						{
							output('\'`#Er liebt mich nicht.
							Was?!`@\' schreit sie laut und fängt an zu weinen.
							Sie hüpft vom Altar und rennt dicht an dir vorbei in den Wald. 
							Du fühlst dich weniger charmant.
							`n`n`^Du verlierst 1 Charmepunkt!');
							$session['user']['charm']--;
						}
						else
						{
							output('\'`#Er liebt mich. Juchu!
							Er liebt mich, er liebt mich!`@\' sagt sie und hüpft auf und ab.
							Sie springt vom Altar und rennt dicht an dir vorbei in den Wald.
							Du fühlst dich nach der Freude des Mädchens charmanter.
							`n`n`^Du erhältst 1 Charmepunkt!');
							$session['user']['charm']++;
						}
						break;
					case 7:
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
						$reward=round(e_rand($session['user']['experience']*0.025+10, $session['user']['experience']*0.1+10));
						output('siehst du eine wunderschöne Frau in deiner Nähe.
						`n`n\'`#'.($session['user']['sex']?'Meine Tochter':'Mein Sohn').',`@\' sagt sie, \'`#Ich danke Dir für das Geschenk.
						Ich weiß, Du hattest ein hartes Leben bisher, darum erhältst Du ein Geschenk von uns.`@\'
						`n`nSie gibt dir etwas, das wie ein leckerer Brotlaib aussieht und motiviert dich, es zu essen.
						Da du nicht unhöflich sein willst, nimmst du das Brot in den Mund und isst es.
						Auf einmal fühlst du dich so, als ob sich mehr Wissen in deinem Gedächtnis breitgemacht hat.
						Du schließt kurz deine Augen und als du sie wieder öffnest, ist die Göttin verschwunden.
						Wie war wohl ihr Name?
						`n`n`^Du erhältst '.$reward.' Erfahrungspunkte!');
						$session['user']['experience']+=$reward;
						break;
				}
			}
			else if ($_GET['flower']=='dandelions')
			{
				output('`@Du legst den Löwenzahn auf den Opferaltar.
				Du senkst deinen Kopf zum Gebet an die Götter und bittest sie, die Opfergabe anzunehmen.
				Als du deinen Kopf wieder anhebst, schaust du auf den Altar und ');
				switch (e_rand(1,5))
				{
					case 1:
						output('siehst eine Göttin, die missbilligend auf dein Geschenk schaut.
						Plötzlich dreht sie sich zu dir und ihre Wut bricht aus.
						Sie geht voller Zorn auf dich zu!
						`n`n\'`#Ein `iUnkraut`i?!? Du schenkst den mächtigen Göttern `iUnkraut`i! Wurm! Du verdienst es nicht einmal zu leben!`@\' sagt sie und schleudert dann Feuerbälle auf dich.
						`n`nDer erste durchwandert dich einfach, verwandelt deinen Oberkörper in Asche und deine Arme, Beine und dein Kopf sterben langsam ab.
						Als dein Kopf auf den Boden fällt und rollt, tritt die Göttin diesen mit ihrem Fuß, nimmt ihn dann auf und schaut in deine Augen.
						`n`n\'`#Nun, '.$session['user']['name'].'`#, ich denke Du hast Deine Lektion gelernt. Störe die Götter nie wieder mit solchen Kleinigkeiten.`@\'
						`n`nAls dein Geist in die Schatten abtaucht, denkst du noch \'`&Sie irren sich, ich denke nicht, dass es der Gedanke ist der zählt...`@\'
						`n`n`^Du bist tot!
						`nDu verlierst all dein Gold!
						`nDiese Lektion hat dir mehr Erfahrung eingebracht, als du verlieren könntest.');
						killplayer(100,0);
						if(mb_strtolower(mb_substr($session['user']['name'],-1))=='s')
						{
							addnews($session['user']['name'].'\' Kopf wurde gefunden... auf einem Speer in der Nähe eines Altars für die Götter.');
						}
						else
						{
							addnews($session['user']['name'].'s Kopf wurde gefunden... auf einem Speer in der Nähe eines Altars für die Götter');
						}
						break;
					case 2:
					case 3:
					case 4:
					case 5:
						output('dein Geschenk geht in Flammen auf.
						Feuer umgibt den Löwenzahn.
						Als die Flammen alles in Asche verwandelt haben, gehst du zum Altar und entsorgst die Asche.`n');
						switch (e_rand(1,3))
						{
							case 1:
								output('`iDu findest dort nichts!`i
								Die Götter müssen dein Geschenk abgelehnt haben.
								Deine Hände sind ganz klebrig von dem ganzen Löwenzahn. 
								Naja, es war ja nur Unkraut...');
								break;
							case 2:
							case 3:
								output('`iDu findest einen Edelstein!!`i
								Die Götter müssen dein Geschenk angenommen haben.
								Deine Hände sind ganz klebrig von dem ganzen Löwenzahn, aber der Edelstein war es wert!
								`n`n`^ Du findest `%EINEN`^ Edelstein!');
								$session['user']['gems'] +=1;
								break;
						}
				}
			}
		}
	}
}

else if ($_GET['op']=='leave') //Altar verlassen
{
	output('`#Das ist ein heiliger Ort für Götter und Priester. Am besten machst du dich schnellstens wieder auf den Weg, bevor die Götter zornig werden, weil du an ihrem heiligen Altar verweilst.');
	$session['user']['specialinc']='';
}

else if ($_GET['op']=='won') //Ergebnisse nach erfolgreichem Kampf bzw Trophäenopfer
{
	$session['user']['specialinc']='';
	$badguyname=stripslashes(urldecode($_GET['badguyname']));
	if ($_GET['difficulty']=='strong')
	{
		$dif='strong';
	}
	elseif ($_GET['difficulty']=='weak')
	{
		$dif='weak';
	}
	else
	{
		$dif='moderate';
	}
	if(isset($_GET['itemid']) && $_GET['itemid']>0)
	{
		item_delete('id='.(int)$_GET['itemid']);
	}
	output('`@Du trägst dein Geschenk, `^'.$badguyname.'`@, zurück zum Altar.
	Du legst den toten Leichnam auf den Altar und führst das Blutritual durch.
	Als du dieses beendet hast, ');
	switch (e_rand(1,15))
	{
		case 1: //tot
			$row=db_fetch_assoc(db_query('SELECT name_plur FROM races WHERE id="'.$session['user']['race'].'"'));
			output('`ierwacht `^'.$badguyname.'`@ zu neuem Leben!`i
			Mit dem Unterschied das es nun Fangarme und Krallen besitzt und sehr hungrig aussieht.
			Dein Pech ist, du kannst nichts töten, was du schon getötet hast...
			Du hättest wissen müssen das die Götter solche Opfer nicht annehmen.
			Das war `i'.$row['name_plur'].'-Blut`i auf dem Altar.
			`n`nDie Götter wollen Blut und sie bekommen es nun von dir, ob dir das gefällt oder nicht.
			`n`n`^Du bist tot!
			`nDie Götter scheinen auch glänzendes gelbes Metall zu lieben, denn sie nehmen dir all dein Gold!
			`nDu verlierst 5% deiner Erfahrung.
			`nDu kannst morgen wieder weiterspielen.');
			killplayer();
			if(mb_strtolower(mb_substr($session['user']['name'],-1))=='s')
			{
				addnews($session['user']['name'].'\' Überreste waren nicht sehr schön, als sie gefunden wurden...');
			}
			else
			{
				addnews($session['user']['name'].'s Überreste waren nicht sehr schön, als sie gefunden wurden...');
			}
			break;
		
		case 2: //Gems++
		case 3:
		case 4:
			if ($dif=='weak')
			{
				$reward = 1;
				$rewardnum='EINEN`^ Edelstein';
			}
			elseif ($dif=='strong')
			{
				$reward = 3;
				$rewardnum='DREI`^ Edelsteine';
			}
			else
			{
				$reward = 2;
				$rewardnum='ZWEI`^ Edelsteine';
			}
			output('sprichst du ein Gebet für den Geist des toten `^'.$badguyname.'`@ aus.
			Du drehst dich um umd wäscht deine Hände in einer kleinen Pfütze beim Altar.
			Als du fertig bist, stehst du wieder auf und wendest dich dem Altar zu.
			`n`0`i`^'.$badguyname.'`@ ist verschwunden!`0`i
			`n`@An dessen Stelle ist nun ein Beutel.
			Du gehst hin und schaust in den Beutel hinein.
			Im Beutel findest du '.$reward.' Edelsteine!
			Die Götter haben dein Opfer wohl akzeptiert und dich für deine Mühen entlohnt.
			`n`n`^Du findest `%'.$rewardnum.'!`n');
			$session['user']['gems'] +=$reward;
			break;
		
		case 5: //Gold++
		case 6:
		case 7:
		case 8:
			if ($dif=='weak')
			{
				$reward = e_rand(10, 100);
				$bag='winziger';
			}
			else if ($dif=='strong')
			{
				$reward = e_rand(175, 300);
				$bag='etwas größerer';
			}
			else
			{
				$reward = e_rand(75, 200);
				$bag='kleiner';
			}
			output('sprichst du ein Gebet für den Geist des toten `^'.$badguyname.'`@ aus. 
			Du drehst dich um umd wäscht deine Hände in einer kleinen Pfütze beim Altar.
			Als du fertig bist, stehst du wieder auf und wendest dich dem Altar zu.
			`n`0`i`^'.$badguyname.'`@ ist verschwunden!`0`i
			`n`@An dessen Stelle ist nun ein '.$bag.' Beutel.
			Du gehst hin und schaust in den Beutel hinein.
			Im Beutel findest du '.$reward.' Gold!
			Die Götter haben dein Opfer wohl akzeptiert und dich für deine Mühen entlohnt.
			`n`n`^Du findest '.$reward.' Gold!`n');
			$session['user']['gold'] += $reward;
			break;
		
		case 9: //WK++
		case 10:
		case 11:
		case 12:
			if ($dif=='weak')
			{
				$reward = 2;
			}
			elseif ($dif=='strong')
			{
				$reward = 4;
			}
			else
			{
				$reward = 3;
			}
			output('legst du deine Hand auf den toten Körper um zu beten, aber als deine Hand das Fleisch des toten '.$badguyname.'`@ berührt, fühlst du dich von Energie durchflossen.
			Deine Schwäche wurde ausgesaugt und deine Müdigkeit besänftigt.
			Die Götter haben dir genug Stärke gegeben für weitere '.$reward.' Waldkämpfe!
			`n`n`^Du erhältst weitere '.$reward.' Waldkämpfe!!');
			$session['user']['turns']+=$reward;
			break;
		
		case 13: //charm--
		case 14:
			if ($dif=='weak')
			{
				$charmloss = 3;
			}
			elseif ($dif=='strong')
			{
				$charmloss = 1;
			}
			else
			{
				$charmloss = 2;
			}
			output('fängt der Leichnam an, größer zu werden, als ob er mit Luft gefüllt wird!
			Er wird immer noch größer. Du bist zu überrascht, um dich zu bewegen.
			Letztlich explodiert `^'.$badguyname.'`@ und beschmutzt dich mit Blut und Überresten.
			Das Opfer muss wohl nicht genug gewesen sein und du wurdest dafür bestraft.
			`n`n`^Du verlierst '.$charmloss.' Charmepunkte!');
			$session['user']['charm']-=$charmloss;
			$session['user']['donation']+=$charmloss;
			break;
		
		case 15: //tot
			output('`$färbt sich der Himmel rot.
			`@Du fürchtest dich davor, dass du die Götter verärgert hast und drehst dich um, um den Ort zu verlassen.
			Just in diesem Moment zuckt ein Blitz vom Himmel und trifft dich.
			Du wirst mit einer ungeheuren Wucht nach hinten geschleudert und als du den Boden aufschlägst, bist du bereits tot.
			Es ist nicht gut, den Göttern zu wenig Respekt zu zollen und du fandest das auf dem harten Weg heraus.
			`n`n`^Du bist tot!
			`nDu verlierst all dein Gold!
			`nDu verlierst 10% deiner Erfahrung!
			`nDu kannst morgen wieder weiterspielen.');
			$session['user']['donation']+=1;
			killplayer(100,10);
			addnews('Der verkohlte Körper von '.$session['user']['name'].' wurde irgendwo im Wald gefunden.');
			break;
	}
}

if ($_GET['op']=='run') //aus dem Kampf fliehen
{
	if (e_rand()%3 == 0)
	{
		$_GET['op']='';
		output('`c`b`&Du bist erfolgreich vor deinem Feind geflohen!`0`b`c
		`nDu fliehst feige vor deiner Beute und hast dabei vergessen, wo sich der Altar befindet.
		Du wirst möglicherweise nie mehr etwas opfern.
		Denk immer daran, es ist allein deine Schuld.');
		$session['user']['specialinc']='';
	}
	else
	{
		output('`c`b`$Du konntest nicht vor deinem Feind fliehen!`0`b`c');
	}
}

if ($_GET['op']=='fight' || $_GET['op']=='run')
{
	$battle=true;
}

if ($battle)
{
	include('battle.php');
	if ($victory)
	{
		if (getsetting('dropmingold',0))
		{
			$badguy['creaturegold']=e_rand($badguy['creaturegold']/4,3*$badguy['creaturegold']/4);
		}
		else
		{
			$badguy['creaturegold']=e_rand(0,$badguy['creaturegold']);
		}
		$expbonus = round(($badguy['creatureexp'] *
		(1 + 0.25 *
		($badguy['creaturelevel']-$session['user']['level'])
		)
		) - $badguy['creatureexp'],0
		);
		output('`0`b`&'.$badguy['creaturelose'].'
		`n`$Du hast '.$badguy['creaturename'].'`$ getötet!`0`b
		`n`#Du erhältst `^'.$badguy['creaturegold'].'`# Gold!`n');
		if (e_rand(1,25) == 1)
		{
			output('`&Du findest einen Edelstein!`n');
			$session['user']['gems']++;
		}
		if ($expbonus>0)
		{
			output('`#***Weil der Kampf schwieriger war, erhälst du zusätzliche `^'.$expbonus.'`# Erfahrungspunkte! `n('.$badguy['creatureexp'].' + '.abs($expbonus).' = '.($badguy['creatureexp']+$expbonus).') ');
			$dif='strong';
		}
		else if ($expbonus<0)
		{
			output('`#***Weil der Kampf so leicht war, werden dir `^'.abs($expbonus).'`# Erfahrungspunkte abgezogen! `n('.$badguy['creatureexp'].' - '.abs($expbonus).' = '.($badguy['creatureexp']+$expbonus).') ');
			$dif='weak';
		}
		output('Du erhältst insgesamt `^'.($badguy['creatureexp']+$expbonus).'`# Erfahrungspunkte!`n`0');
		$session['user']['gold']+=$badguy['creaturegold'];
		$session['user']['experience']+=($badguy['creatureexp']+$expbonus);
		$creaturelevel = $badguy['creaturelevel'];
		$_GET['op']="";
		if ($badguy['diddamage']!=1)
		{
			if ($session['user']['level']>=getsetting("lowslumlevel",4) || $session['user']['level']<=$creaturelevel)
			{
				output("`b`c`&~~ Perfekter Kampf! ~~`$`n`bDu erhältst einen Extra-Waldkampf!`c`0`n");
				$session['user']['turns']++;
			}
			else
			{
				output("`b`c`&~~ Unglaublicher Kampf! ~~`b`$`nEin schwierigerer Kampf hätte dir einen Extra-Waldkampf eingebracht.`c`n`0");
			}
		}
		$dontdisplayforestmessage=true;
		addnav('Zum Altar zurückkehren','forest.php?op=won&difficulty='.$dif.'&badguyname='.urlencode($badguy['creaturename']));
		$session['user']['specialinc']='sacrificealtar.php';
		$badguy=array();
	}
	else if ($defeat)
	{
		addnews('`%'.$session['user']['name'].'`5 wurde im Wald von '.$badguy['creaturename'].'5 getötet.`n'.get_taunt());
		killplayer();
		$session['user']['badguy']='';
		output('`0`b`&Du wurdest von `%'.$badguy['creaturename'].'`& getötet!!!
		`n`4Du hast all dein Gold verloren!
		`n10% deiner Erfahrung ging verloren!
		`nDu kannst morgen wieder weiterspielen.');
	}
	else
	{
		fightnav(true,true);
	}
}
output('`0');
?>
