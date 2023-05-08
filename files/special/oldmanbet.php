<?php

//13082004
//Originalautor unbekannt
//27.1.2008 Würfelraten by Salator (salator@gmx.de) nach "Nostradamus - Die letzte Prophezeiung"

if (!isset($session))
{
	exit();
}

define('JSLIB_NO_FOCUS_NEEDED',1); //wird nur für Atrahor benötigt

if ($_GET['op']=="") //Start
{
	output("`3Ein alter Mann hält dich im Wald an und fragt dich: \"`6Wie würde es Dir gefallen, ein kleines Ratespielchen mit mir zu spielen?`3\" Da du Leute wie ihn kennst, weißt du, dass er auf einen kleinen Wetteinsatz bestehen wird, wenn du dich darauf einlässt. 
	`n`nWillst du sein Spiel spielen?
	`n`n<a href='forest.php?op=yes'>Ja, Zahlenraten</a>
	`n<a href='forest.php?op=dice'>Ja, Würfelraten</a>
	`n<a href='forest.php?op=no'>Nein, ich gehe</a>");
	addnav("Ja, Zahlenraten","forest.php?op=yes");
	addnav("W?Ja, Würfelraten","forest.php?op=dice");
	addnav("Nein, weg hier","forest.php?op=no");
	addnav("","forest.php?op=yes");
	addnav("","forest.php?op=dice");
	addnav("","forest.php?op=no");
	$session['user']['specialinc']="oldmanbet.php";
}

else if ($_GET['op']=="yes") //Zahlenraten
{
	if ($session['user']['gold']>0)
	{
		$session['user']['specialinc']="oldmanbet.php";
		$bet = abs((int)$_GET['bet'] + (int)$_POST['bet']);
		if ($bet<=0)
		{
			output("`3\"`6Ich denke mir eine Zahl aus und Du hast 6 Versuche, diese Zahl zwischen 1 und 100 zu erraten. Ich werde Dir immer sagen, ob Dein Versuch zu hoch oder zu niedrig war.`3\"
			`n`n`3\"`6Wie hoch ist Dein Einsatz, ".($session['user']['sex']?"junge Dame":"junger Mann")."?`3\"`0
			<form action='forest.php?op=yes' method='POST'>
			<input name='bet' id='bet'>
			<input type='submit' class='button' value='Setze'>
			</form>");
            JS::Focus('bet');
			// Bravebrain
			addnav("","forest.php?op=yes");
			$session['user']['specialmisc']=e_rand(1,100);
		}
		else if ($bet>$session['user']['gold'])
		{
			output("`3Der alte Mann streckt seinen Stock aus und klopft damit deinen Golsbeutel ab. \"`6Ich glaub nicht, daß da `^$bet`6 Gold drin ist!`3\", erklärt er.
			`n`nVerzweifelt versuchst du ihm deinen guten Willen zu zeigen und kippst den Beutelinhalt vor ihm aus: `^".$session['user']['gold']."`3 Gold.
			`n`nVerlegen kehrst du in den Wald zurück.");
			$session['user']['specialinc']="";
			//addnav("Zurück in den Wald","forest.php");
		}
		else
		{
			if ($_POST['guess']!==null)
			{
				$try = (int)$_GET['try'];
				if ($_POST['guess']==$session['user']['specialmisc'])
				{
					if ($try == 1)
					{
						output("`3\"`6UNGLAUBLICH`3\", schreit der alte Mann, \"`6Du hast meine Zahl mit nur `^einem Versuch`6 erraten! Nun, ich gratuliere dir. Ich bin stark beeindruckt. Es ist gerade so, als ob Du meine Gedanken lesen könntest.`3\" Er schaut dich misstrauisch eine Weile an und überlegt, ob er sich mit deinem Gewinn einfach aus dem Staub machen soll, erinnert sich dann aber an deine scheinbaren geistigen Kräfte und händigt dir deine `^$bet`3 Gold aus.");
					}
					else
					{
						output("`3\"`6AAAH!!!!`3\", schreit der alte Mann, \"`6Du hast die Zahl mit nur $try Versuchen erraten!  Es war `^".$session['user']['specialmisc']."`6!!  Nun, ich gratuliere dir , und denke ich werde jetzt besser gehen...`3\"
						`nEr will ins Unterholz verschwinden, doch mit einem flinken Schlag mit ".$session['user']['weapon']."`3 schlägst du ihn KO. Du hilfst ihm dabei, dir die `^$bet`3 Goldmünzen zu geben, die er dir schuldet.");
					}
					$session['user']['gold']+=$bet;
					$session['user']['specialinc']="";
					$session['user']['specialmisc']="";
				}
				else
				{
					if ($_GET['try']>=6&&((int)$_POST['guess']>=0&&(int)$_POST['guess']<=100))
					{
						output("`3Der Mann gluckst vor Freude: \"`6Die Zahl war `^".$session['user']['specialmisc']."`6.`3\"
						`nAls der ehrenwerte Bürger, der du bist, gibst du dem Mann die `^$bet`3 Goldmünzen, die du ihm schuldest, bereit, von hier zu verschwinden.");
						$session['user']['specialinc']="";
						$session['user']['specialmisc']="";
						$session['user']['gold']-=$bet;
					}
					else
					{
						if ((int)$_POST['guess']>100||(int)$_POST['guess']<0||!(int)$_POST['guess'])
						{
							$try--;
							output("`3Der Alte lacht: \"`6Das ist wie einem Baby ein Schwert abzunehmen, wenn Du wirklich glaubst, $_POST[guess] ist zwischen 1 und 100!`3\"`n\"`6Du hast noch `^".(6-$try)."`6 Versuche übrig.`3\"`n");
						}
						else if ((int)$_POST['guess']>$session['user']['specialmisc'])
						{
							output("`3\"`6Nop, nicht `^".(int)$_POST['guess']."`6, meine Zahl ist kleiner als das!  Das war Versuch `^$try`6.`3\"`n`n");
						}
						else
						{
							output("`3\"`6Nop, nicht `^".(int)$_POST['guess']."`6, meine Zahl ist größer als das!  Das war Versuch `^$try`6.`3\"`n`n");
						}
						output("`3Du hast `^$bet`3 Gold gesetzt.  Was schätzt Du?`0
						<form action='forest.php?op=yes&bet=$bet&try=".(++$try)."' method='POST'>
						<input name='guess' id='guess'>
						<input type='submit' class='button' value='Rate'>
						</form>");
                        JS::Focus('guess');
						// Bravebrain
						addnav("","forest.php?op=yes&bet=$bet&try=$try");
					}
				}
			}
			else
			{
				output("`3Du hast `^$bet`3 Gold gesetzt.  Was schätzt du?`0
				<form action='forest.php?op=yes&bet=$bet&try=1' method='POST'>
				<input name='guess' id='guess'>
				<input type='submit' class='button' value='Rate'>
				</form>");
				addnav("","forest.php?op=yes&bet=$bet&try=1");
			}
		}
	}
	else
	{
		output("`3Der alte Mann streckt seinen Stock aus und klopft deinen Goldbeutel ab. \"`6Leer?!?! Wie kannst Du etwas setzen ohne Gold??`3\", brüllt er. Damit dreht er sich mit einem HARUMF um und verschwindet im Unterholz.");
		//addnav("Zurück in den Wald","forest.php");
		$session['user']['specialinc']="";
	}
}

elseif($_GET['op']=='dice') //Würfelraten
{
	$session['user']['specialinc']="oldmanbet.php";
	$bet=$session['user']['level']*100;
	$data=utf8_unserialize($session['user']['specialmisc']);
	if(!is_array($data['dice'])) //init
	{
		for($i=0;$i<5;$i++)
		{
			$rand=e_rand(1,6);
			$data['dice'][$i]=$rand;
			$data['guesses']=0;
			$data['sum']+=$rand;
		}
		output('"`6Ich würfle mit 5 Würfeln und gebe Dir den Gesamtwert. Du hast 5 Versuche, den Wert jedes Würfels zu erraten. Ich werde Dir sagen wieviele Würfel du richtig geraten hast.
		`nWenn Du gewinnst gebe ich Dir '.$bet.' Gold, wenn du verlierst bekomme ich diesen Betrag von Dir.`0"
		`nDer Alte schüttelt seinen Würfelbecher, schaut sich das Ergebnis an und sagt zu dir:
		`n`n"`6Die Würfel sind gefallen. Die Summe der Würfel ist `&'.$data['sum'].'`6 Nun liegt es bei Dir.`0"
		`nWas schätzt du?`n');
		$data['output']='`nDie Summe der fünf Würfel ist: `7'.$data['sum'].'`0
		`nWelchen Wert haben die einzelnen Würfel?`n`n';
		$arr_guess=array(1,1,1,1,1);
		addnav('Nein, weg hier','forest.php?op=no');
	}

	else
	{
		$arr_dices=$data['dice'];
		//$arr_guess=explode(',',$_POST['bet']);
		for($i=0;$i<5;$i++)
		{
			$arr_guess[$i]=intval($_POST['bet'.$i]);
			if($arr_guess[$i]<1) $arr_guess[$i]=1;
			if($arr_guess[$i]>6) $arr_guess[$i]=6;
		}
		if(array_sum($arr_guess)!=$data['sum']) //Summe eingegebene Augen ungleich Summe vorgegebene Augen
		{
			output('`$Na, Rechnen gehört ja nicht zu deinen Stärken.
			`nDein Tipp hat eine Summe von '.array_sum($arr_guess).'`0');
		}
		elseif(count($arr_guess)<5) //keine 5 Zahlen eingegeben
		{
			output('`$Na, bis 5 zählen kannst du offenbar nicht.`0');
		}
		else
		{
            $correct = 0;
            $numbers = '';
			$arr_tmp=$arr_guess;
			for($i=0;$i<5;$i++)
			{
				if($arr_guess[$i]<1 || $arr_guess[$i]>6)
				{
					$error='`$Du weißt aber, wie ein Würfel aussieht? Die Zahlen müssen im Bereich 1 - 6 liegen!`0';
				}
				$numbers.=$arr_guess[$i].' ';
				for($j=0;$j<5;$j++)
				{
					if($arr_dices[$i]==$arr_tmp[$j])
					{
						$arr_tmp[$j]=0;
						$correct++;
						break;
					}
				}
			}
			if($error) //zu doof richtige Zahlen einzugeben
			{
				output($error);
			}
			else
			{
				$data['guesses']++;
				$data['output'].='Dein '.$data['guesses'].'. Tipp: `2'.$numbers.'`0 &nbsp; &nbsp; &nbsp; &nbsp; `6Du hast `@'.($correct?$correct:'`4keinen einzigen').'`6 Würfel richtig geraten.`0`n';
			}
		}
	}

	if($correct==5) //Gewonnen
	{
		output($data['output'].'`n`@GEWONNEN!`0
		`n"`6Du hast alle Würfel richtig erraten. Hier ist dein Preis: `^'.$bet.' Gold.`0"');
		$session['user']['gold']+=$bet;
		$session['user']['specialinc']='';
		$session['user']['specialmisc']='';
	}
	elseif($data['guesses']>=5) //Verloren
	{
		$session['user']['specialinc']='';
		$session['user']['specialmisc']='';
		output($data['output'].'`n`$Verloren!
		`n`3"`6Du hast verloren. Nun musst du mir etwas geben.`3"`n');
		if($session['user']['gold']>=$bet)
		{
			$session['user']['gold']-=$bet;
			output('Als der ehrenwerte Bürger, der du bist, gibst du dem Mann die `^'.$bet.'`3 Goldmünzen, die du ihm schuldest, bereit, von hier zu verschwinden.');
		}
		elseif($session['user']['gems']>0)
		{
			$session['user']['gems']--;
			output('Zu spät stellst du fest, dass du nicht genügend Gold hast. So bietest du dem Mann stattdessen einen Edelstein an.');
		}
		else
		{
			$session['user']['hitpoints']=1;
			$session['user']['gold']=0;
			output('Zu spät stellst du fest, dass du nicht nur zu wenig Gold, sondern auch sonst nichts hast. Erbost über diese Frechheit schlägt dich der Mann nieder und nimmt dir dein ganzes Gold.');
			addnews('`4'.$session['user']['name'].'`3 konnte seine Wettschulden nicht bezahlen.');
		}
		output('`n`0Die Lösung war: ');
		for($i=0;$i<5;$i++)
		{
			output($data['dice'][$i].' ');
		}
	}
	else //Weiterraten
	{
		output($data['output'].'
		`n<form action="forest.php?op=dice" method="POST">
		<img src="./images/dice'.$arr_guess[0].'.gif" id="picbet0">
		<img src="./images/dice'.$arr_guess[1].'.gif" id="picbet1">
		<img src="./images/dice'.$arr_guess[2].'.gif" id="picbet2">
		<img src="./images/dice'.$arr_guess[3].'.gif" id="picbet3">
		<img src="./images/dice'.$arr_guess[4].'.gif" id="picbet4">
		<input type="hidden" name="bet0" id="bet0" value="'.$arr_guess[0].'">
		<input type="hidden" name="bet1" id="bet1" value="'.$arr_guess[1].'">
		<input type="hidden" name="bet2" id="bet2" value="'.$arr_guess[2].'">
		<input type="hidden" name="bet3" id="bet3" value="'.$arr_guess[3].'">
		<input type="hidden" name="bet4" id="bet4" value="'.$arr_guess[4].'">

		'.JS::event('#picbet0','click','increment(\'bet0\')').'
		'.JS::event('#picbet1','click','increment(\'bet1\')').'
		'.JS::event('#picbet2','click','increment(\'bet2\')').'
		'.JS::event('#picbet3','click','increment(\'bet3\')').'
		'.JS::event('#picbet4','click','increment(\'bet4\')').'

		'.JS::event('#bet0','click','increment(\'bet0\')').'
		'.JS::event('#bet1','click','increment(\'bet1\')').'
		'.JS::event('#bet2','click','increment(\'bet2\')').'
		'.JS::event('#bet3','click','increment(\'bet3\')').'
		'.JS::event('#bet4','click','increment(\'bet4\')').'


		<input type="submit" class="button" value="Tipp abgeben">
		`n&Sigma;: <input name="sum" id="sum" size="2" maxlength="2" value="'.array_sum($arr_guess).'" readonly>
		</form>');
		rawoutput('
		'.JS::encapsulate('
		Pic1 = new Image();
		Pic1.src = "./images/dice1.gif";
		Pic2 = new Image();
		Pic2.src = "./images/dice2.gif";
		Pic3 = new Image();
		Pic3.src = "./images/dice3.gif";
		Pic4 = new Image();
		Pic4.src = "./images/dice4.gif";
		Pic5 = new Image();
		Pic5.src = "./images/dice5.gif";
		Pic6 = new Image();
		Pic6.src = "./images/dice6.gif";

		function increment(field)
		{
			$fieldvalue=document.getElementById(field).value;
			$fieldvalue++;
			if($fieldvalue>6 || $fieldvalue<1) $fieldvalue=1;
			document.getElementById(field).value=$fieldvalue;
			document.getElementById("pic"+field).src = "./images/dice"+$fieldvalue+".gif";
			document.getElementById("sum").value=parseInt(document.getElementById("bet0").value) + parseInt(document.getElementById("bet1").value) + parseInt(document.getElementById("bet2").value) + parseInt(document.getElementById("bet3").value) + parseInt(document.getElementById("bet4").value);
		}
	    '));
		addnav('','forest.php?op=dice');
		addnav('Klicke auf die Würfel','');
		$session['user']['specialmisc']=utf8_serialize($data);
	}
}

else //if ($_GET['op']=="no")
{
	output("`3Aus Furcht, dich von deinem teuren teuren Gold trennen zu müssen, lehnst du das Spiel des Alten ab. Hätte ja eh nicht viel Sinn gehabt weil du so oder so gewonnen hättest. Jep, hätte definitiv keine Chance dieser alte Kerl, nop.");
	//addnav("Zurück zum Wald","forest.php");
	$session['user']['specialinc']="";
	$session['user']['specialmisc']="";
}
?>
