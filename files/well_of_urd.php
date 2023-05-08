<?php
/*Der Brunnen der Nornen, Start und Hilfeseite für den Tausch-Quest
*Konzept by Valas
*Programmierung by Salator
*in sehr vielen Dateien sind Änderungen für die einzelnen Events nötig
*benötigt eine Item-Schablone "exchngdmmy" und evtl weitere Schablonen, welche nicht verlierbar sind
*/

require_once("common.php");

if (!isset($session))
{
	exit();
}
checkday();
page_header('Der Brunnen der Nornen');
output('`c`bEtwas Besonderes`b`c`n`%');

$events=array('Fee','Murmel','Feder','Gedicht','Rose','Räuchermischung','Rubin','Weihwasser','Donneraxt','Mithril-Erz','Flöte','Spiegel','Kuss','Met','Honig','Brot','Wanderkarte','Bunte Steine','Zaubertafel','Muschel','Perlenkette','Parfum','Steckbrief','Drachenzahn','Nebelflasche','Lolli','Brosche','Juwelen-Brosche','Leuchtende Brosche','Kraftlose Brosche','Geweihte Brosche');

switch($_GET['op'])
{
	case '':
	{
		if($Char->exchangequest>0)
		{
			output('Auf deinen Streifzügen kommst du an eine hohe Esche, die in Nebel gehüllt ist. Darunter befindet sich ein Brunnen, an dem 3 Mädchen sitzen und weben. Du bist dir sicher dass dies der legendäre Weltenbaum Yggdrasil und der Brunnen der Urd sein muß.`nDu könntest diese Chance nutzen und eine der Nornen befragen. Jedoch musst du bereit sein, dafür ein Opfer zu erbringen.`n`n
			`2Urd`0 weiß über die Vergangenheit bescheid.`n
			`2Verdanti`0 kennt die Gegenwart.`n
			`2Skuld`0 sieht das, was sein wird.
			`n`nIn der Nähe liegen einige Runensteine, an deren Lage du Botschaften erkennst:`n`n`0');
			viewcommentary('well_of_urd','Runen legen',25,'schrieb');
			addnav('U?Frage Urd (je 1 Donationpoint)','well_of_urd.php?op=urd');
			addnav('V?Frage Verdanti ('.($Char->level*30).' Gold)','well_of_urd.php?op=ver');
			addnav('S?Frage Skuld ('.$Char->exchangequest.' Edelsteine)','well_of_urd.php?op=skd');
			if($Char->exchangequest>28)
			{
				addnav('Orte');
				addnav('Geheimgang','exchangequest_temple.php');
			}
			//Boss einfügen
			include_once(LIB_PATH.'boss.lib.php');
			boss_get_nav('nidhoggr');
		}
		else
		{
			output('Du begegnest einer Fee. "`^Gib mir einen Edelstein!`%", verlangt sie. Was tust du?');
		    addnav('e?`%Gib ihr einen Edelstein','well_of_urd.php?op=start');
		    addnav('k?Gib ihr keinen Edelstein','forest.php');
		}
		break;
	} //end Startseite

	case 'start':
	{
		if($Char->gems>0)
		{
			item_delete('tpl_id="exchngdmmy" AND owner='.$Char->acctid);
			$item=array('tpl_name' => '1 bunte Glasperle'
				,'tpl_description' => 'Eine hübsche farbige Glasperle. Die Kinder in der Stadt spielen mit sowas gerne "Murmeln".'
				,'tpl_gold' => 10
				,'tpl_gems' => 0
				,'tpl_special_info' => getsetting('gamedate','0005-01-01') );
			item_add($Char->acctid,'exchngdmmy',$item);
			$Char->gems--;
			$Char->exchangequest=1;
			output('Du gibst der Fee einen deiner schwer verdienten Edelsteine. Sie schaut ihn an, quiekt vor Entzückung und verspricht dir als Gegenleistung ein Geschenk.`n
			Mit den Worten "`^Der Weg zum Glück beginnt meist mit einer bedeutungslos erscheinenden Kleinigkeit. Ein Samenkorn kann im Laufe der Zeit ganze Wälder hervorbringen...`%" gibt sie dir eine kleine Glasperle und verschwindet.
			`n`n`0Offenbar hast du einen verborgenen Weg gefunden. Du machst dir am Waldeingang einen nur für dich sichtbaren Hinweis. Vielleicht gibt es ja später hier noch mehr zu entdecken.');
		}
		else
		{
			output('Als du bemerkst, dass du nicht einen Edelstein hast, machst du dich schleunigst davon.');
		}
		break;
	} //end Quest starten
	
	case 'urd':
	{
		//kostet Donationpoints, gibt Hilfetext zu gelösten Schritten aus
		$out='Du entscheidest dich, Urd anzusprechen. ';
		if($_POST['number']) {
			$number=intval($_POST['number']);
			$out.='Sie sieht dich an und sagt: "`0Du willst also die Vision von dem '.$events[$number].'-Ereignis noch einmal hören? Also gut.`%"
			`nWenig später hat sie die passende Stelle im Netz des Schicksals gefunden:`0
			`n`n'.get_extended_text('quest_exchange');
			$Char->donationspent++;
		}
		$out.='`n`2Welches Ereignis möchtst du noch einmal ansehen? Jede Suche wird dich 1 Donationpoint kosten.
		`n<form action="well_of_urd.php?op=urd" method="post"><select name="number">';
		for($i=0; $i<$Char->exchangequest; $i++)
		{
			$out.='<option>'.$i.'. ('.$events[$i].')</option>';
		}
		$out.='</select> <input type="submit" value="OK"></form>';
		output($out);
		addnav('','well_of_urd.php?op=urd');
		addnav('B?Zum Brunnen','well_of_urd.php');
		break;
	} //end Urd
	
	case 'ver':
	{
		//kostet Gold, gibt aktuelles Item und eine von diversen Seltenheiten aus
		$goldcost=$Char->level*30;
		$message=' die verlangten ';
		if($Char->gold<$goldcost)
		{
			$goldcost=$Char->gold;
			$message=', weil du nicht mehr hast, ';
		}
		$row['iname']='riesigen Schätzen';
		$row['name']='im Moment niemand, außer vielleicht die Götter';
		$sql='SELECT i.name AS iname,a.name from items i LEFT JOIN accounts a ON owner=acctid 
		WHERE tpl_id in("hintdoc", "drstb", "idolrnds", "idolgnie", "idolfish", "idolkmpf", "idoldead", "goldenegg", "mapt", "ranfcrst", "gemmajor") 
		ORDER BY RAND() LIMIT 1';
		if($Char->exchangequest<29)
		{
			$item=item_get('tpl_id="exchngdmmy" AND owner='.$Char->acctid);
		}
		elseif($Char->exchangequest==29)
		{
			$item['name']='Brosche der alten Völker';
		}
		else
		{
			$item['name']='`rBr`vos`Fch`*e `fder alt`*en `FVö`vlk`rer`0';
		}
		$sql2='SELECT exchangequest FROM accounts where exchangequest>0 AND exchangequest>='.($Char->exchangequest-1).' AND exchangequest<='.($Char->exchangequest+1);
		$result=db_query($sql);
		if(db_num_rows($result)>0)
		{
			$row=db_fetch_assoc($result);
		}
		$result2=db_query($sql2);
		$q_lower = $q_equal = $q_higher = 0;
		while($row2=db_fetch_assoc($result2))
		{
			if($row2['exchangequest']==$Char->exchangequest-1) $q_lower++;
			elseif($row2['exchangequest']==$Char->exchangequest) $q_equal++;
			elseif($row2['exchangequest']==$Char->exchangequest+1) $q_higher++;
		}
		$q_equal--; //man selbst zählt ja auch
		if($Char->exchangequest==1)
		{
			$q_lower='viele, vor allem junge';
		}
		output('Du entscheidest dich, Verdanti anzusprechen und forderst feststehende Fakten... Sofort wird dir klar, dass das Unsinn ist. Also forderst du das totale Fehlen feststehender Fakten... Nein, das ist auch Unsinn. Also forderst du dass du '.$Char->name.'`% bist.
		`n`nVerdanti schaut dich verständnislos an und sagt
		`n"`0Wenn Du auf der Suche nach '.$row['iname'].'`0 bist, dann könntest Du '.$row['name'].'`0 fragen.
		`nDu selbst trägst '.$item['name'].'`0 bei Dir und bist somit auf Tausch-Level '.$Char->exchangequest.'.
		`n'.$q_lower.' Wesen suchen derzeit '.$item['name'].',`0
		`n'.$q_equal.' Wesen haben ebenfalls '.$item['name'].'`0
		'.($Char->exchangequest<29?'`nund '.$q_higher.' sind es vor Kurzem losgeworden':'').'.`%"
		`n`nDu gibst Verdanti'.$message.$goldcost.' Gold.');
		$Char->gold-=$goldcost;
		addnav('B?Zum Brunnen','well_of_urd.php');
		break;
	} //end Verdanti
	
	case 'skd':
	{
		//kostet Edelsteine, gibt Hilfetext für aktuellen Schritt aus
		if($_GET['act']=='ok')
		{
			output('Du entscheidest dich, Skuld anzusprechen. Sie sieht abwechselnd auf dich und auf ihre Stäbe. Dann sagt sie zu dir:
			`n`n"`0Sehen kann ich es, doch ändern kann ich es nicht.
			`n'.get_extended_text('quest_exchange').'`%"
			`n`nIn dem Glauben, irgendwann das eben gehörte einsetzen zu können, gibst du Skuld die vereinbarten Edelsteine.');
			$Char->gems-=$Char->exchangequest;
			debuglog('gab '.$Char->exchangequest.' Edelsteine für Tauschquest-Lösungshilfe');
		}
		else
		{
			if($Char->gems>=$Char->exchangequest && $Char->exchangequest != 30)
			{
				output('Ein Blick in die Zukunft kostet dich '.$Char->exchangequest.' Edelsteine. Willst du diese Menge ausgeben?');
				addnav('Ja, ich zahle','well_of_urd.php?op=skd&act=ok');
			}
			else
			{
				output('So gern du auch wissen möchtest was die Zukunft für dich bereit hält, Skuld kann dir nicht helfen.');
			}
		}
		addnav('B?Zum Brunnen','well_of_urd.php');
		break;
	} //end Skuld
	
	default:
	{
		output('An diesem Ort gibt es weiter nichts Interessantes für dich, also ziehst du weiter.');
		break;
	} //end ungültige op
} //end main switch

addnav('Zurück in den Wald','forest.php');

if($Char->exchangequest>0 && $Char->exchangequest<29 && item_count('tpl_id="exchngdmmy" AND owner='.$Char->acctid)==0) 
{ //verlorenes Tausch-Item ersetzen
	$item['tpl_name']='Ersatz-'.$events[$Char->exchangequest];
	$item['tpl_special_info']=getsetting('gamedate','0005-01-01').'-B';
	item_add($Char->acctid,'exchngdmmy',$item);
}

page_footer();
?>
