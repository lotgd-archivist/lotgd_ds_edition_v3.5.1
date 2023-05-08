<?php
/*
* whitelilies - a forest-special where you can send flowers to a dead player
* the player will be resurrected without newday, but you may be dead
* Author: Salator (salator@gmx.de)
* Änderungen am Ahnenschrein in graveyard.php?op=shrine nötig
* inspired by the song "1000 weiße Lilien" from Welle:Erdball
*/

$session['user']['specialinc']="whitelilies.php";

switch ($_GET['op'])
{
	case 'search':
		if ($session['user']['gold'] < 1000)
		{
			output('`QLeider hast du nicht genügend Gold dabei - so wird das nichts mit der Beschwörung.`0');
			$session['user']['specialinc']='';
			break;
		}
		$sql='SELECT acctid,name,level,sex,turns FROM accounts WHERE alive=0 AND '.user_get_online().' ORDER BY turns DESC';
		$result=db_query($sql);
		if(db_num_rows($result)>0)
		{
            $i=0;
			$str_out='`kHonigmond blickt über ihr Blumenbeet und sagt dann zu dir: `t"Ja, es gibt Seelen bei denen eine Beschwörung Erfolg haben könnte. Wer soll denn erweckt werden?"`0`n<table cellpadding="3" cellspacing="1" border="0"><tr class="trhead"><th>&nbsp;</th><th>Name</th><th>Level</th><th>Geschlecht</th></tr>';
			while($row=db_fetch_assoc($result))
			{
				$str_out.='<tr class="'.($i%2?'trlight':'trdark').'">';
				$str_out.='<td>'.$row['turns'].'&nbsp;</td>';
				$str_out.='<td><a href="forest.php?op=resurrect&who='.$row['acctid'].'">';
				$str_out.=$row['name'];
				$str_out.='</a></td><td align="center">';
				$str_out.=$row['level'];
				$str_out.='</td><td align="center"><img src="./images/'.($row['sex']?'female':'male').'.gif"></td></tr>';
				addnav('','forest.php?op=resurrect&who='.$row['acctid']);
				$i++;
			}
			output($str_out.'</table>');
			addnav('n?Äh, niemand','forest.php?op=leave');
		}
		else
		{
			output('`kHonigmond blickt über ihr Blumenbeet und sagt dann zu dir: `t"Ich fürchte, alle Toten sind schon zu lange bei Ramius. Da kann ich nichts mehr tun."');
			addnav('Schade...','forest.php?op=leave');
		}
		break;
	case 'resurrect':
		$who=intval($_GET['who']);
		$sql='SELECT alive,loggedin,a.name,login,race,r.name AS racename FROM accounts a LEFT JOIN races r ON id=race WHERE acctid='.$who;
		$row=db_fetch_assoc(db_query($sql));
		if($row['alive']!=0 || $row['loggedin']==0)
		{
			output('Nun hast du so lange überlegt, '.$row['name'].'`0 wandelt nicht mehr durch Ramius\' Reich.`nDu gehst in den Wald zurück.');
		}
		else
		{
			user_update(
				array
				(
					'pqtemp'=>"1000 weiße Lilien"
				),
				$who
			);
			$mailmessage=$session['user']['name'].'`7 hat dich mit 1000 `&weißen`7 Lilien bedacht, du kannst deinen Tag jetzt unter den Lebenden beenden. Gehe zum Ahnenschrein und nutze diese Chance!';
			systemmail($who,'`21000 `&weiße`2 Lilien!`0',$mailmessage);

			$session['user']['turns']=0;
			$session['user']['gold']-=1000;
			output('`7Honigmond versetzt dich in Trance und deine Gedanken sind bei '.$row['login'].':`n`n`&Tausend weiße Lilien blühen, tausendmal denk ich an Dich`nTausend wundervolle Leben warten nun auf Dich.`nTausendmal für meine Liebe hol ich dich zurück.`n`n`7Als du wieder erwachst weißt du: `2Alles beginnt und endet zur richtigen Zeit am richtigen Ort.`n`n`tVor dir steigt eine Nebelwolke auf die sich immer weiter verdichtet und schließlich die Gestalt von '.$row['name'].'`t annimmt.');
			if(e_rand(1,3)==3 || $row['race']=='dmn')
			{
				output('`nDoch oje, was hast du getan? Das ist kein '.$row['racename'].', das ist nicht real!`n`n`4Die richtige Zeit und der richtige Ort wo es für `$DICH`4 endet ist `$hier und jetzt!');
				killplayer(0,0,0,'shades.php','Na prima...');
				addnews($session['user']['name'].'`4 misslang der Versuch, einen Toten zu erwecken`0');
			}
		}
		$session['user']['specialinc']='';
		break;
	case 'leave':
		$session['user']['specialinc']='';
		output('
			`kDu drehst dich einfach um und gehst.`n
			Schon bald erinnerst du dich an nichts mehr...
		');
		break;
	default:
		output('`kDu kommst auf eine Lichtung die voller `&weißer Lilien`k ist. Eine Gärtnerin ist damit beschäftigt, die Blümchen zu gießen. Als sie dich bemerkt kommt sie zu dir und bietet ihre Dienste an.`n`t"Hallo '.($session['user']['sex']?'gute Frau':'guter Mann').', ich bin Honigmond, die Lilienzüchterin. Was haltet Ihr davon, einem lieben Verstorbenen eine kleine Aufmerksamkeit zukommen zu lassen?"`n`kSie erzählt dir, dass 1000 ihrer Blumen die Macht haben, `$Ramius`k zu beschwören...`n`t"Doch es ist natürlich nicht kostenlos.`nJede Lilie kostet 1 Goldstück. Wenn dir also `^1000 Goldstücke `tnicht zu viel sind, werde ich die Beschwörung mit Dir zelebrieren."`n`n`@Diese Beschwörung wird dich den gesamten restlichen Tag kosten. Mindestens...`nWas machst du?`n`0');
		addnav('Tote erwecken','forest.php?op=search');
		addnav('Weitergehen','forest.php?op=leave');
}
?>