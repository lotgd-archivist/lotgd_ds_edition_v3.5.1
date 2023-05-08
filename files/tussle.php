<?php
// tussle v0.8
// Schlägerei nach dem Papier-Stein-Schere Prinzip
// by Maris (Maraxxus@gmx.de)
// für atrahor.de

require_once "common.php";
$str_filename = basename(__FILE__);
$aei_val = user_get_aei('tussle,tussle_rounds',$session['user']['acctid']);
if(mb_strlen($aei_val['tussle'])>11)$arr_tussle = utf8_unserialize($aei_val['tussle']);
$arr_stilnames=array('kräfteschonend (2. Revanche)','offensiv','defensiv','ausgeglichen','kräfteschonend');
switch ($_GET['op'])
{
	case 'init':
		page_header('Raufen - wähle deinen Stil!');
		user_set_aei(array('tussle'=>'a:0:{}'));
		output('`2Wähle deinen Kampfstil.');
		addnav('Kampfstil');
		addnav('Offensiv',$str_filename.'?op=init2&skill=1');
		addnav('Defensiv',$str_filename.'?op=init2&skill=2');
		addnav('Ausgeglichen',$str_filename.'?op=init2&skill=3');
		addnav('Kräfteschonend',$str_filename.'?op=init2&skill=4');
		addnav('Feige weglaufen',$str_filename);
	break;

	case 'init2':
		page_header("Raufen - Runde ".$arr_tussle['round']);
		if ($_GET['skill']) $arr_tussle['skill']=$_GET['skill'];
		if (is_array($arr_tussle['combo']))
		{
			$amount = (count($arr_tussle['combo'])+1);
		}
		else $amount = 1;
		if ($_GET['punch'])
		{
			$arr_tussle['combo'][$amount]=$_GET['punch'];
			$amount++;
		}
		// Schlagfolge festlegen
		if ($amount<=5)
		{
			output("`2Wähle deinen $amount. Schlag!`n`n");
			addnav("Schlag Nummer ".$amount);
			addnav("Hoher Schlag",$str_filename."?op=init2&punch=1");
			addnav("Brustschlag",$str_filename."?op=init2&punch=2");
			addnav("Tiefer Schlag",$str_filename."?op=init2&punch=3");
		}
		// Gegner generieren
		else
		{
			if (!$arr_tussle['round']) $arr_tussle['round']=1;
			for ($i=1; $i<=5; $i++)
			{
				$arr_tussle['opp_combo'][$i]=e_rand(1,3);
			}
			output("`2Dein Gegner für die ".$arr_tussle['round'].". Runde ist bereit für dich!`n`n");
			addnav("Weiter",$str_filename."?op=fight");
		}
		// "History"
		if ($amount>1)
		{
			output("`^Gewählte Schlagfolge:`n`0");
			$out_img='';
			foreach ($arr_tussle['combo'] as $key => $val)
			{
				switch ($val)
				{
					case 1:
						$desc="Hoher Schlag";
					break;
					case 2:
						$desc="Brustschlag";
					break;
					case 3:
						$desc="Tiefer Schlag";
					break;
				}
				output($key.". ".$desc."`n");
				$out_img.='<img src="./images/tussle/punch'.$val.'.jpg"><img src="./images/tussle/space.gif">';
			}
			output("`n".$out_img,true);
		}

	user_set_aei(array('tussle'=>db_real_escape_string(utf8_serialize($arr_tussle))),$session['user']['acctid']);
	break;

	case 'fight':
	page_header("Raufen - Runde ".$arr_tussle['round']);
	$balance=0;
	$my_hits=0;
	$opp_hits=0;
	$out_img='';
    if(empty($arr_tussle['combo']))$arr_tussle['combo'] = array();//todo hmmm?
	foreach ($arr_tussle['combo'] as $key => $val)
				{
					switch ($val)
					{
						case 1:
							$desc="Hohen Schlag";
						break;
						case 2:
							$desc="Brustschlag";
						break;
						case 3:
							$desc="Tiefen Schlag";
						break;
					}
					output("`^Angriff ".$key.":`2 Du holst zu einem ".$desc." aus");
					// Spieler schlägt Gegner
					if (($val==1 && $arr_tussle['opp_combo'][$key] == 3) || ($val==2 && $arr_tussle['opp_combo'][$key] == 1) || ($val==3 && $arr_tussle['opp_combo'][$key] == 2))
					{
						output(" und landest einen sauberen Treffer!`n`n");
						$balance++;
						$my_hits++;
					}
					// Gegner trifft Spieler
					elseif (($val==1 && $arr_tussle['opp_combo'][$key] == 2) || ($val==2 && $arr_tussle['opp_combo'][$key] == 3) || ($val==3 && $arr_tussle['opp_combo'][$key] == 1))
					{
						if ($arr_tussle['skill']==2 && (e_rand(1,20)>15))
						{
							output(" und kannst gerade so einem Hieb deines Gegners ausweichen.`n`n");
						}
						else
						{
							output(", wirst aber von deinem Gegner getroffen, noch bevor du zuschlagen kannst.`n`n");
							$balance--;
							$opp_hits++;
						}
					}
					// Gleichstand
					elseif ($val==$arr_tussle['opp_combo'][$key])
					{
						if ($arr_tussle['skill']==1 && (e_rand(1,20)>10))
						{
							output(" und schlägst durch den Block deines Gegners!`n`n");
							$balance++;
							$my_hits++;
						}
						else
						{
							output(", doch dein Gegner blockt ihn gekonnt.`n`n");
						}
					}
				output('<img src="./images/tussle/punch'.$val.'.jpg"><img src="./images/tussle/space.gif"><img src="./images/tussle/space.gif"><img src="./images/tussle/opunch'.$arr_tussle['opp_combo'][$key].'.jpg">`n`n');
				}
			output('`n`n`n`2Der Kampf ist vorbei!`nErgebnis: '.$my_hits.':'.$opp_hits.'`n`n');
			if ($balance>0 || ($balance==0 && $arr_tussle['skill']==3))
			{
				output('`2Du hast deinen Gegner niedergestreckt!');
				if ($arr_tussle['round']%5==3)
				{
					output('`^`nFür die nächste Zeche bekommst du einen Zuschuss von '.($arr_tussle['round']*50).' Goldmünzen!`0');
					$session['user']['gold']+=$arr_tussle['round']*50;
				}
				output('`2`n`nWeiter zur nächsten Runde.');
				$arr_tussle['round']++;
				addnav('Weiter');
				addnav('Nächste Runde',$str_filename.'?op=init2');
				addnav('Kneifen');
				addnav('Zum Trainingslager','train.php');
				unset($arr_tussle['combo']);
				user_set_aei(array('tussle' => db_real_escape_string(utf8_serialize($arr_tussle))));
				if ($aei_val['tussle_rounds']<($arr_tussle['round']-1)) user_set_aei(array('tussle_rounds'=>($arr_tussle['round']-1)));
			}
			else
			{
				if ($arr_tussle['skill']==4 && $arr_tussle['retry']!=1)
				{
					output('`4Zwar hast du den Kampf verloren, aber dein kräfteschonender Kampfstil erlaubt dir eine Revanche!`0');
					addnav('Weiter');
					addnav('Nochmal',$str_filename.'?op=init2');
					addnav('Kneifen');
					addnav('Zum Trainingslager','train.php');
					$arr_tussle['skill']=4;
					$arr_tussle['retry']=1;
					unset($arr_tussle['combo']);
					user_set_aei(array('tussle' => db_real_escape_string(utf8_serialize($arr_tussle))));
				}
				elseif ($arr_tussle['skill']==4 && $arr_tussle['retry']==1)
				{
					output('`4Du den Kampf verloren, aber dein kräfteschonender Kampfstil erlaubt dir eine weitere Revanche!`nDoch dies wird die letzte sein!`0');
					addnav('Weiter');
					addnav('Nochmal',$str_filename.'?op=init2');
					addnav('Kneifen');
					addnav('Zum Trainingslager','train.php');
					$arr_tussle['skill']=0;
					unset($arr_tussle['combo']);
					user_set_aei(array('tussle' => db_real_escape_string(utf8_serialize($arr_tussle))));
				}
				else
				{
					output('`4Du hast diesen Kampf verloren und liegst gekrümmt auf dem Boden.`nDa war wohl jemand stärker als erwartet!`0');
					addnav('Zurück');
					addnav('Zum Trainingslager','train.php');
					user_set_aei(array('tussle'=>getsetting('gamedate','0005-01-01')));
				}
			}
	break;

	case 'rules':
		page_header('Raufen - Die Regeln');
		output('`2`n`c`bRegeln fürs Raufen`b`c`n`nZu Beginn der Rauferei kannst du dir einen Kampfstil aussuchen. Es gibt davon insgesamt vier, jeder mit einem anderen Vorteil.`nVor Beginn jeder Runde suchst du dir deine Schlagfolge, bestehend aus fünf einzelnen Angriffen, aus.`n`nWenn es dir im Kampf gelingt öfter als dein Gegner zu treffen hast du gewonnen und kommst eine Runde weiter.`nUnterliegst du, so ist die Rauferei für dich beendet.`n`n`n<u>Kampfstil-Erklärung:</u>`n`n`bOffensiv`b: Deine Schläge durchdringen mit einer Warscheinlichkeit von 50% den Block des Gegners`n`n`bDefensiv`b: Du wehrst mit einer Warscheinlichkeit von 25% Treffer des Gegners ab`n`n`bAusgeglichen`b: Du gewinnst, auch wenn du am Ende des Kampfes gleichviele Treffer wie dein Gegner gelandet hast (kämpfen beide ausgeglichen entscheidet in diesem Fall der Zufall)`n`n`bKräfteschonend`b: Du darfst zwei Kämpfe verlieren ohne aufhören zu müssen`n`n');
		addnav('Zurück',$str_filename);
	break;

	default:
		page_header('Raufen');
		output('`2Es mag so manchen geben, der mit dem Schwert ein Meister ist, wieder ein anderer kämpft vorzüglich mit dem Speer oder mit der Axt.`nDoch in manchen Situation muss sich der wahre Krieger auf seine blossen Fäuste verlassen können.`nAuch wenn die Meister im bewaffneten Kampf bekannt sind und ihre Reihenfolge feststehen mag, so sieht diese ganz anders aus, schaut man sich eben jene Helden an, wenn sie einmal ohne ihre Lieblingswaffe auskommen müssen.`nDu hast hier die Möglichkeit deinen Platz in dieser Rangliste zu festigen oder auch nach oben zu korrigieren.`n');
		if ($aei_val['tussle']==getsetting('gamedate','0005-01-01'))
		{
			output('`n`n`$Du hast bereits ein dickes Auge und darfst heute nicht mehr raufen!`0');
		}
		else
		{
			addnav('Zum Kampf');
			addnav('Raufen',$str_filename.'?op=init');
			if(is_array($arr_tussle) && $arr_tussle['round']>0)
			{
				output('`nDu hast schon eine Fehde begonnen, diese kannst du jetzt fortsetzen.
				`nKampfstil: '.$arr_stilnames[$arr_tussle['skill']].'
				`nRunde: '.$arr_tussle['round']);
				addnav('F?Alte Fehde fortsetzen',$str_filename.'?op=init2');
			}
		}
		addnav('Information');
		addnav('Regeln',$str_filename.'?op=rules');
		addnav('Zurück');
		addnav('Abbruch','train.php');
	break;
}

page_footer();
?>