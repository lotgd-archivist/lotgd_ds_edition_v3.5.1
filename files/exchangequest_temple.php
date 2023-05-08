<?php
require_once('common.php');
$str_filename = basename(__FILE__);

page_header('Der Tempel der Weisen');

switch($_GET['op'])
{
	case 'reactivate':
	{ //Boni reaktivieren
		if($session['user']['turns']>=5)
		{
			output('`^Wie damals, als du zum ersten Mal einen Fuß in diesen Tempel gesetzt hast, kniest du erneut vor dem Altar nieder und versinkst mit geschlossenen Augen in einem stillen Gebet. Als du die Augen wieder öffnest, stellst du erfreut fest, dass deine Brosche ihre Kräfte zurückerhalten hat. 
			`nZwar hättest du diese Zeit auch für ein paar Waldkämpfe nutzen können, jedoch denkst du, dass sich dieses Opfer gelohnt hat. ');
			$session['user']['exchangequest']=30;
			$session['user']['turns']-=5;
		}
		else
		{
			output('Du bist beim Meditieren eingeschlafen und wurdest erst wieder wach als der Baum, der draußen vor dem Eingang schwebte, mit einem lauten Krachen auf seinen Platz zurückfiel.
			`nDas Krachen war sicher in der ganzen Stadt zu hören, wie peinlich... Auf Seitenwegen schleichst du dich in die Stadt zurück, die Aktion kostet dich einen kompletten Tag.');
			$session['user']['age']++;
		}
	}
	break;

	case 'delete':
	{ //nochmal von vorn beginnen
		if(trim(mb_strtolower($_POST['ok']))=='ok')
		{
			output('`&Du hast dich entschlossen, den Kreis der Weisen zu verlassen.
			`n`hE`vi`fn l`&et`sztes Mal blickst du dich im Tempel um, als dir ein Exemplar des Buches `0Harry Potter und der offene Klodeckel`s auffällt. Du kannst nicht anders, als darin zu lesen. Wenig später merkst du, wie du verb`&lö`fde`vs`ht...');
			addhistory('`2Austritt aus dem `^Kreis der Weisen');
			$session['user']['exchangequest']=29;
			db_query('DELETE FROM history WHERE acctid=1300000 AND msg LIKE "%acctid='.$session['user']['acctid'].'" LIMIT 1');
			
		}
		else
		{
			output('`hA`vu`fst`&re`sten? Also bitte! Das ist hier ein Tempel und kein Pissoir!
			`n`nAchso, du willst den Kreis der Weisen verlassen? Nun gut, ich hoffe, du hast dir diesen Schritt reiflich überlegt. Wenn du jetzt \'OK\' sagst wird dein Name aus dem Buch der Weisen gestrichen. Du bist wieder ein einfacher Bürger und kannst die Vorteile des Tempels nicht mehr nutzen.
			`nDeine Brosche darfst du behalten, jedoch wird sie schon bald Glanz und Kraft verlieren.
			`nZwar soll dir der Zugang zum Tempel der Weisen nicht für immer verwehrt bleiben, der Weg zu einer erneuten Aufnahme wird jedoch nicht minder schwer wie beim ers`&te`fn M`va`hl.`0
			`n`n<form action="'.$str_filename.'?op=delete" method="post">
			Wirklich austreten?
			<input type="text" name="ok" size=3>
			<input type="submit" class="button" value="Bestätigen">
			</form>');
			addnav('',$str_filename.'?op=delete');
		}
	}
	break;

	case 'showlist':
	{ //Ruhmeshalle anzeigen
		$sql='SELECT msg FROM history WHERE acctid=1300000 ORDER BY id ASC';
		$result=db_query($sql);
		while($row=db_fetch_assoc($result))
		{
			$i++;
			$arr_readdata=explode(';',$row['msg']);
			$str_output.='<tr class="'.($i%2?'trdark':'trlight').'">
			<td>'.$arr_readdata[0].'</td>
			<td>'.$arr_readdata[1].'</td>
			<td align="right">'.$arr_readdata[2].'</td>
			</tr>';
		}

		output('`c`hD`va`fs B`&uc`sh der `iehrwürdigen Broschen`&tr`fäg`ve`hr`i`0
		`n`n<table border=0>
		<tr class="trhead">
		<th>Eingetragen am</th>
		<th>Name</th>
		<th>geschätzte Dauer</th>
		</tr>
		'.$str_output.'
		</table>`c');
		break;
	} //end Ruhmeshalle anzeigen
	
    case 'readbook':
	{//aus einem Buch lernen (original: Erkundung in der Expe)
		output('`c`b`hD`vi`fe S`&ch`sriftensa`&mm`flu`vn`hg`0`c`b
		`n`hA`vn `fde`&n W`sänden reihen sich Regale, welche mit unzähligen Büchern und handschriftlichen Notizen gefüllt sind. Was liegt also näher, als in diesen Schriften zu lesen?
		`nDir wird langsam klar, je länger du in diesen Schriften lesen wirst, umso mehr neue Erkenntnisse kannst du gewinnen, wie du sie in der Stadt niemals erlangen kö`&nn`fte`vs`ht.`n');
		if ($session['user']['turns'] < 1)
		{
			output("`n`n`0Du bist heute zu müde um noch irgendwas zu lernen!");
		}
		else
		{
			output("`IWievie Runden willst du mit den Büchern verbringen?
			`n`0<form action='".$str_filename."?op=readbook2' method='POST'>
			<input name='eround' id='eround'>
			<input type='submit' class='button' value='Bücher lesen'>
			</form>");
			JS::Focus("eround");
			addnav("",$str_filename."?op=readbook2");
		}
		break;
	}

	case 'readbook2':
	{ //Erfahrung steigern durch Erkundung
		$eround = abs((int)$_POST['eround']);
		if($eround<1)
		{
			output('Du fragst dich, was "Harry Potter und die Brosche der Weisen" für ein bescheuerter Buchtitel ist. Schnell legst du das Buch wieder weg, nicht dass du noch Erfahrung verlierst...');
		}
		else
		{
			if ($session['user']['turns'] <= $eround)
			{
				$eround = $session['user']['turns'];
			}
			$session['user']['turns']-=$eround;
			$exp = (($session['user']['level']*0.33)+2)*e_rand(8,18)+e_rand(5,10);
			$totalexp = (int)($exp*$eround);
			$session['user']['experience']+=$totalexp;
			output('`IDu legst das Buch weg und fühlst dich deutlich erfahrener!
			`nDu hast `y'.$totalexp.'`I Erfahrung bekommen!`n');
			debuglog('Hat den Tempel genutzt um Erfahrung zu sammeln');
		}
		break;
	}

	case 'alchemy_practice':
	{ //praktische Alchemie (original Gelände auskundschaften in der Expe)
		output('`c`b`hA`vn`fge`&wa`sndte Al`&ch`fem`vi`he`0`c`b
		`n`hW`vi`fe a`&ll`se anderen weißt auch du, dass in der Stadt Bedarf an neuen alchemistischen Produkten besteht. Umso mehr Belohnung hat das oberste Gremium der Weisen ausgesetzt, sollte ein Mitglied ein brauchbares Rezept finden. Du bist fest davon überzeugt, dass dir heute die große Erfindung gelingt und machst dich sofort an die Arbeit, um der Wissenschaft zu dienen und natürlich auch, um das Gold einstecken zu können. Doch du weißt auch, dass die Reinigung der Geräte so lange dauert, dass du heute sicher keinen Fuß mehr in das verlassene Schloss setzen kannst.`n`n
Du siehst einige alchemistische Gerätschaften. Mit Mörser und Rührstab kann fast jeder etwas herstellen. Dementsprechend gering wird hier deine Belohnung ausfallen. Dagegen ist die Druckkammer ein komplizierter Apparat, bei dem Fehlversuche an der Tagesordnung sind. Solltest du damit wirklich etwas herstellen, wird man dir sicher mehr Gold und Edelsteine überr`sei`fch`ve`hn.`n');
		if($session['user']['castleturns']>0 && $Char->getNewdayBit( UBIT_WISDOM_ALCHEMY )==0)
		{
			addnav('Was benutzen?');
			addnav('Mörser und Hölzer',$str_filename.'?op=alchemy_practice2&what=1');
			addnav('Distille',$str_filename.'?op=alchemy_practice2&what=2');
			addnav('Schmelzofen',$str_filename.'?op=alchemy_practice2&what=3');
			addnav('Druckkammer',$str_filename.'?op=alchemy_practice2&what=4');
		}
		else
		{
			output('`hAls du jedoch die verdreckten Gerätschaften siehst, verspürst du keine Lust mehr, diese heute nochmal zu reinigen.');
		}
		break;
	}

	case 'alchemy_practice2':
	{ //praktische Alchemie Ergebnisse
		if ($session['user']['castleturns']>0)
		{
			$what=$_GET['what'];
			switch ($what)
			{
			case '1':
				$limit=80;
				$gold=1000;
				$gems=0;
				$text="`hDu hast eine Substanz hergestellt, die an deinem Abstreichholz festtrocknet. Als du sie abkratzen willst entzündet sie sich!`0`n";
				break;
			case '2':
				$limit=60;
				$gold=1500;
				$gems=1;
				$text="`hDu hast eine nach Eichenholz schmeckende Flüssigkeit hergestellt, die dir die Sinne vernebelt!`0`n";
				break;
			case '3':
				$limit=40;
				$gold=4000;
				$gems=2;
				$text="`hDu hast eine Substanz mit undefinierbaren Eigenschaften hergestellt, die sich nicht bearbeiten lässt. Du nennst es Nihilit!`0`n";
				break;
			case '4':
				$limit=20;
				$gold=10000;
				$gems=8;
				$text="`hDu hast eine Substanz von nahezu diamantener Härte hergestellt!`0`n";
				break;
			}
			$chance=e_rand(0,100);
			if ($chance<=$limit)
			{
				output('`vGlückwunsch!`n'.$text);
				output('`vDas oberste Gremium der Weisen ist mit deiner Leistung derart zufrieden, dass man dir eine `*Belohnung von '.$gold.' Gold und '.$gems.' Edelsteinen `vüberreicht!`n`n');
				$session['user']['gold']+=$gold;
				$session['user']['gems']+=$gems;
			}
			else
			{
				output('`hNachdem deine Arbeiten beendet sind, musst du feststellen, dass diese Rezeptur vollkommen unbrauchbar ist.`n');
			}
			$Char->setNewdayBit(UBIT_WISDOM_ALCHEMY, 1 );
		}
		else
		{
			output('`hDu kannst heute keine Versuche mehr durchführen!`n');
		}
		$session['user']['castleturns']--;
		break;
	}

	case 'fluch_liste_auswahl':
	{ //Fluch aufheben: Liste der Verfluchten/Gesegneten
		$sql = 'SELECT a.name, a.acctid, a.login FROM items i
				INNER JOIN accounts a ON a.acctid = i.owner
				LEFT JOIN items_tpl it ON it.tpl_id=i.tpl_id
				WHERE it.curse>0
				AND i.owner<>'.$session['user']['acctid'].'
				GROUP BY i.owner ORDER BY a.login';

		$res = db_query($sql);

		output('`hDu schaust in die magische Kugel und erkennst eine lange Liste mit sämtlichen Helden, denen Flüche oder Krankheiten anhängen:`n`n');

		if(db_num_rows($res) == 0)
		{
			output('`iBei genauerer Betrachtung merkst du, dass es keinen Verfluchten oder Todkranken gibt, dem du helfen könntest.`i');
		}
		else
		{
			output('<table border="0"  cellpadding="3"><tr class="trhead"><th>Nr.</th><th>Name</th><th>Aktionen</th></tr>',true);

			for($i=1; $i<=db_num_rows($res); $i++)
			{
				$p = db_fetch_assoc($res);
				output('<tr class="'.($i%2?'trlight':'trdark').'"><td>'.$i.'</td><td>'.$p['name'].'</td><td><a href="'.$str_filename.'?op=fluch_liste&id='.$p['acctid'].'">Erscheinungen anzeigen</a></td>',true);
				output('</tr>',true);
				addnav('',$str_filename.'?op=fluch_liste&id='.$p['acctid']);
			}	// END for
			output('</table>',true);
		}	// END flüche vorhanden
		output('',true);
		break;
	}

	case 'fluch_liste':
	{ //Fluch aufheben: Liste der Flüche/Segen der Zielperson
		$sql = 'SELECT a.name, a.acctid, i.id, i.name AS fluchname, i.hvalue, i.gems FROM items i
				INNER JOIN accounts a ON i.owner = a.acctid
				LEFT JOIN items_tpl it ON it.tpl_id=i.tpl_id
				WHERE it.curse>0
				AND i.owner='.(int)$_GET['id'].' ORDER BY i.name';

		$res = db_query($sql);

		output('`hDu konzentrierst dich auf einen der Helden. Bald darauf erkennst du diese Flüche und Krankheiten:
		`n`n<table border="0" cellpadding="3">
		<tr class="trhead">
		<th>Nr.</th>
		<th>Name</th>
		<th>verbleibend</th>
		<th>Kosten</th>
		<th>Aktionen</th>
		</tr>');

		for($i=1; $i<=db_num_rows($res); $i++) {

			$p = db_fetch_assoc($res);
			output('<tr class="'.($i%2?'trlight':'trdark').'">
			<td>'.$i.'</td>
			<td>'.$p['fluchname'].'</td>
			<td align="center">'.(($p['hvalue'] == 0) ? 'unbegrenzt':$p['hvalue'].' Tage').'</td>
			<td>`3'.$p['gems'].' Edelsteine`0</td>
			<td><a href="'.$str_filename.'?op=fluch_del&id='.$p['id'].'">Aufheben</a></td>
			</tr>');
			addnav('',$str_filename.'?op=fluch_del&id='.$p['id']);
		}	// END for

		output('</table>',true);
		addnav('L?Zur Liste',$str_filename.'?op=fluch_liste_auswahl');
		break;
	}

	case 'fluch_del':
	{ //einen Fluch/Segen aufheben
		$i = item_get(' id='.(int)$_GET['id']);
		if($session['user']['gems']>=$i['gems'])
		{
			$session['user']['gems']-=$i['gems'];
			if(e_rand(1,4)==3 || ac_check($i['owner'])==true)
			{
				item_set('id='.(int)$_GET['id'],array('owner'=>$session['user']['acctid']));
				item_set_buffs(0,intval($i['buff1']).','.intval($i['buff2']));
				output('`hDu konzentrierst dich auf den Fluch. Doch irgendetwas stimmt hier nicht... Schließlich weißt du:`nDer Fluch ist auf `$DICH`0 übergegangen!');
				debuglog('Fehlschlag, übernimmt Fluch '.$i['name'].' von',$i['owner']);
			}
			else
			{
				item_delete(' id='.(int)$_GET['id']);
				output('`hDu konzentrierst dich auf den Fluch und spürst bereits nach kurzer Zeit, wie er schwächer und schwächer wird. Schließlich weißt du:`nEr ist Vergangenheit!');
				debuglog('nimmt Fluch '.$i['name'].' von',$i['owner']);
			}
			systemmail($i['owner'],'Fluch aufgehoben!','`@Ein Mitglied der Weisen, '.$session['user']['name'].'`@, hat dich von deinem schrecklichen Fluch "'.$i['name'].'" befreit.');
		}
		else
		{
			output('Du konzentrierst dich auf den Fluch. Es passiert überhaupt nichts. Scheinbar fehlt noch etwas für die Prozedur.');
		}
		addnav('L?Zur Liste',$str_filename.'?op=fluch_liste_auswahl');
		break;
	}

	case 'randomcomm':
	{ //Kommentare von einem zufälligen öffentlichen Platz anzeigen
		$str_sections = getsetting('rpdon_sections','village'); //wo es DP gibt muss ja öffentlich und demnach gefahrlos zum Spannen sein
		$str_sections=str_replace(',','","',$str_sections);
		$sql='SELECT section FROM commentary WHERE section IN("'.$str_sections.'") ORDER BY RAND() LIMIT 1';
		$result=db_query($sql);
		if(db_num_rows($result)>0 && $session['daily']['kristallkugel']<13)
		{
			$session['daily']['kristallkugel']++;
			$row=db_fetch_assoc($result);
			output(get_title('`*Di`fe K`&ri`fstal`&lk`fug`*el').'`*Gl`fei`&ch `fneben dem Schrein steht eine leicht grünlich schimmernde kristallene Kugel, in welcher in allen Farben Blitze zucken. Sogar eine Restenergie-Anzeige befindet sich daran: '.grafbar(13,13-$session['daily']['kristallkugel'],26,13).' 
			`nDu schaust in die Kristallkugel und erkennst, wie sich einige -dir mehr oder weniger bekannte- Wesen unter`&ha`flt`*en:`n');
			if($session['daily']['kristallkugel']>1)
			{
				addnav('Heilung eines Todkranken',$str_filename.'?op=fluch_liste_auswahl');
				output('`*Auch kannst du erkennen, dass einige verfluchte oder todkranke Wesen vor sich hinvegetieren. Vielleicht hast du die Macht, ihnen zu helfen?`n');
			}
			output('`n');
			viewcommentary($row['section'],'',0,'',false,false);
		}
		else
		{
			output('`*Die Kristallkugel ist gerade in der Reinigung.');
		}
		addnav('O?Anderer Ort',$str_filename.'?op=randomcomm');
		break;
	} //end randomcomm

	case 'alchemybook':
	{ //Liste aller Items die in einer Alchemie-Kombo vorkommen
		output('`hD`vu `fbe`&gi`sbst dich zu dem Regal wo die Enzyklopädie "Alchemie heute" steht. Leider findest du nur den relativ unwichtigen letzten Band mit dem Stichwortverzeichnis vor.
		`nZwar weißt du genau, wer die restlichen Bände hat, wagst es jedoch nicht, ihn darauf anzusp`&re`fch`ve`hn.`n');
		$sql='SELECT id1,id2,id3 FROM items_combos WHERE type=2';
		$result=db_query($sql);
		$str_items='"0"';
		while($row=db_fetch_assoc($result))
		{
			$str_items.=',"'.$row['id1'].'","'.$row['id2'].'","'.$row['id3'].'"';
		}
		$sql='SELECT tpl_name, tpl_description, find_forest, vendor, vendor_new, spellshop, giftshop, distributor FROM items_tpl WHERE tpl_id IN('.$str_items.') ORDER BY tpl_name';
		$result=db_query($sql);
		$str_out=get_title('`hS`vt`fic`&hw`sortverze`&ic`fhn`vi`hs').'<dl>';
		while($row=db_fetch_assoc($result))
		{
			$find=($row['find_forest']>0?' Wald,':'');
			$find.=($row['vendor']&1||$row['vendor_new']?' Wanderhändler,':'');
			$find.=($row['spellshop']&1?' Zauberladen,':'');
			$find.=($row['giftshop']>0?' Geschenkeladen,':'');
			$find.=($row['distributor']>0?' Mitbürger,':'');
			$find=($find==''?' Seltenheit':mb_substr($find,0,-1));
			$str_out.='<dt>`@'.$row['tpl_name'].'`0</dt>
			<dd>`&'.$row['tpl_description'].'
			`n`7Vorkommen:'.$find.
			'`0`n`n</dd>';
		}
		output($str_out.'</dl>');
		break;
	}

	default:
	{ //Eingangsseite
		output(get_title('`hD`ve`fr T`&em`spel der `&We`fis`ve`hn').'`hE`vi`fn s`&el`stsames Leuchten, hervorgerufen von 2 Ruhmkorffschen Apparaten, erhellt diesen unterirdischen Tempel. Dieser Raum ist nicht sehr groß und auch eher funktional als prunkvoll ausgestattet. An den Wänden reihen sich Regale, welche mit unzähligen Büchern und handschriftlichen Notizen gefüllt sind. Gegenüber vom Eingang befindet sich der Schrein der ewigen Weisheit mit der magischen Glaskugel. Du kannst die besondere Aura dieses Ortes spüren. 
		`nWenn du willst kannst du den anderen Weisen eine Schriftrolle hinter`&la`fss`ve`hn.`n`n');
		if($session['user']['exchangequest']>29 || ($access_control->su_check(access_control::SU_RIGHT_DEBUG) && $session['user']['exchangequest']!=29))
		{
			addnav('Buch der Weisen',$str_filename.'?op=showlist');
			addnav('Kristallkugel',$str_filename.'?op=randomcomm');
			if($session['daily']['kristallkugel']>1)
			{
				addnav('Heilung eines Todkranken',$str_filename.'?op=fluch_liste_auswahl');
			}
			addnav('l?Ein Buch lesen',$str_filename.'?op=readbook');
			addnav('x?Alchemistische Experimente',$str_filename.'?op=alchemy_practice');
			addnav('Alchemie heute',$str_filename.'?op=alchemybook');
		}
		if($session['user']['exchangequest']==29)
		{
			output('Deine Brosche hat erheblich an Energie verloren. Vielleicht ist Meditation der richtige Weg, um ihre Kräfte zu reaktivieren. Dies wird dich 5 Waldkämpfe kosten.`n`n');
			addnav('a?Kräfte aufladen',$str_filename.'?op=reactivate');
		}
		addnav('Austreten',$str_filename.'?op=delete',false,false,false,false);
		
		viewcommentary('tempel_der_weisen','Aufschreiben',25,'schrieb');
	}
}
addnav('Zurück');
if($_GET['op'])
{
	addnav('T?Zum Tempel',$str_filename);
}
addnav('W?Zur Wolkeninsel','wolkeninsel.php?op=insel');
addnav('d?Zum Stadtzentrum','village.php');

page_footer();
?>
