<?php

//seeddealer: ein Specialevent für die Garten-Anbauten, es werden Rosen und alles aus der Kategorie Saatgut verkauft.
//Salator for DS-LoGD V3

page_header('Der fliegende Händler');
$str_backlink='forest.php';
$str_backtext='Zurück in den Wald';
$session['user']['specialinc'] = basename(__FILE__);

if ($_GET['sop']=='')
{
	output('`@Als du durch den Wald gehst spricht dich ein Mann in gebrochenem Deutsch an: 
	`c"`QWollekauferose?`@"`c
	`nIhn zu ignorieren scheint sinnlos, denn er verfolgt dich penetrant. Was wirst du tun?`n`n');
	addnav('Waren ansehen',$str_backlink.'?sop=browse');
	addnav('Ihn verprügeln',$str_backlink.'?sop=brawl');
	addnav('H?Frage nach Heiltrank',$str_backlink.'?sop=buy&id=Heiltrank'); //okay, doch einen Ausgang
}

else if ($_GET['sop']=='browse')
{
	$sql = 'SELECT id FROM items_classes WHERE 1 AND class_name="Saatgut"';
	$result = db_query($sql);
	$rowc = db_fetch_assoc($result);

  	$sql = 'SELECT tpl_id,tpl_name,tpl_description,tpl_gold,tpl_gems FROM items_tpl WHERE tpl_class='.$rowc['id'].' OR (tpl_name LIKE "%rose%" AND tpl_name NOT LIKE "%g%") ORDER BY tpl_class ASC, tpl_name ASC';
	$result = db_query($sql);
	$str_out='`@Der Händler preist seine Waren an: "`QSchaut was ich habe, alles ganz billige Preis!
	`nUnd heute isse Sonderangebot Mondblume. Wenn Du kaufen zwei, ich geben drei!`@"';

	$str_out.='`n`n<table border="0" cellpadding="0" width=95%>';
	$str_out.="<tr class='trhead'><th>Name</th><th>Beschreibung</th><th align='right'>Preis</th></tr>";
	$str_out.='<tr class="trlight">
	<td valign="top">'.create_lnk('Mondblume',$str_backlink.'?sop=buy&id=Mondblume').'</td>
	<td>Aus dem Orient kommt diese seltene Pflanze, welche nur bei Vollmond blüht.</td>
	<td align="right" valign="top">`^150&nbsp;Gold`0</td>
	</tr>';

	for ($i=0;$i<db_num_rows($result);$i++)
	{
	  	$row = db_fetch_assoc($result);
        /** @noinspection PhpUndefinedVariableInspection */
        $bgcolor=($bgcolor=='trdark'?'trlight':'trdark');
		$str_out.='<tr class="'.$bgcolor.'">
		<td valign="top">'.create_lnk($row['tpl_name'],$str_backlink.'?sop=buy&id='.$row['tpl_id']).'</td>
		<td>'.$row['tpl_description'].'</td>
		<td align="right" valign="top">`^'.$row['tpl_gold'].'&nbsp;Gold'.($row['tpl_gems']>0?'<br>`#'.$row['tpl_gems'].'&nbsp;Gemmen':'').'`0</td>
		</tr>';
	}
	$str_out.='</table>';
	
	output($str_out);
	$show_invent = true;
}

else if ($_GET['sop']=="buy")
{
  	$sql = 'SELECT * FROM items_tpl WHERE tpl_id="'.$_GET['id'].'"';
	$result = db_query($sql);
	if (db_num_rows($result)==0)
	{
	  	output('`@Als der Händler merkt, dass er die gewünschte Ware gar nicht hat, sagt er verlegen: 
		`c"`QOh, Vielmalsentschuldigung, ich garnicht habe '.$_GET['id'].'. Wollekauferose?`@"`c
		`nWegen dieser Peinlichkeit verliert der Händler einen Charmepunkt.');
		addnav('Nochmal suchen',$str_backlink.'?sop=browse');
		addnav($str_backtext,$str_backlink.'?sop=endspecial');
	}
	else
	{
	  	$row = db_fetch_assoc($result);
		if ($row['tpl_gold']>$session['user']['gold'] || $row['tpl_gems']>$session['user']['gems'])
		{
			$klau=e_rand(2,4);
			if ($session['user']['specialtyuses']['thievery']>=2)
			{
				$klau--;
			}
			switch($klau)
			{
				case 1: //Chance für Diebe
				{
					output('`@Du verwickelst den Händer in ein Gespräch, greifst dir heimlich '.$row['tpl_name'].'`@ und machst dich schließlich aus dem Staub. Natürlich ohne zu bezahlen, denn darin bist du geübt.');
					$session['user']['specialinc']='';
					addnav($str_backtext,$str_backlink);
					break;
				}
				case 2: //Ansehensverlust
				{
					output('`@Du verwickelst den Händer in ein Gespräch, greifst dir heimlich '.$row['tpl_name'].'`@ und willst dich aus dem Staub machen. Doch der Rosenverkäufer ist ja nicht dumm und bemerkt den Betrugsversuch.
					`nPeinlich berührt gibst du '.$row['tpl_name'].'`@ zurück und verschwindest so schnell du kannst.');
					$session['user']['reputation']-=5;
					$session['user']['specialinc']='';
					addnav('Zur Stadt','village.php');
					break;
				}
				default: //ab in den Kerker
				{
					output('`@Du greifst dir '.$row['tpl_name'].'`@ und willst dich aus dem Staub machen ohne zu bezahlen. 
					`nDer fliegende Händler schreit jedoch nach der Stadtwache und als diese herbeieilt erklärt er, dass du ihm sein wertvolles Saatgut stehlen wolltest.
					`n`$Tja, dumm gelaufen. Die Stadtwache glaubt dem Rosenverkäufer und steckt dich für 1 Tag in den Kerker.');
					$session['user']['imprisoned']+=1;
					$session['user']['specialinc']='';
					addnav('Na prima...','prison.php');
					addnews('`^'.$session['user']['name'].'`Q wurde des versuchten Samenraubes überführt und in den Kerker geworfen.');
					break;
				}
			}
		}
		else
		{
			output('`@Ein zufriedenes Lächeln zieht über das Gesicht des fliegenden Händlers, als du deinen Goldbeutel zückst und '.$row['tpl_name'].'`@ kaufst.
			`n`n"`QExzellente Kauf, '.($session['user']['sex']?'Senorina':'Senore').', '.$row['tpl_description'].'
			`nUnd heute isse Sonderangebot Mondblume. Wenn Du kaufen zwei, ich geben drei!`@"');
	 		$session['user']['gold']-=$row['tpl_gold'];
	 		$session['user']['gems']-=$row['tpl_gems'];

			$row['tpl_description'] = str_replace('{name}',$session['user']['name'],$row['tpl_description']);
			$row['tpl_gold'] = round($row['tpl_gold'] * 0.75);
			item_add($session['user']['acctid'],'',$row);

			addnav('Mehr kaufen',$str_backlink.'?sop=browse');
			addnav($str_backtext,$str_backlink.'?sop=endspecial');
		}
	}
}

elseif($_GET['sop']=='brawl')
{
	output('`@Du nimmst dem aufdringlichen Typen seine Rosen ab und haust sie ihm um die Ohren.
	`nDer fliegende Händler schreit jedoch nach der Stadtwache und als diese herbeieilt erklärt er, dass du ihm sein wertvolles Saatgut stehlen wolltest.
	`n`$Tja, dumm gelaufen. Die Stadtwache glaubt dem Rosenverkäufer und steckt dich für 1 Tag in den Kerker.');
	$session['user']['imprisoned']+=1;
	$session['user']['specialinc']='';
	addnav('Na prima...','prison.php');
	addnews('`^'.$session['user']['name'].'`Q wurde des versuchten Samenraubes überführt und in den Kerker geworfen.');
}

else
{
	$session['user']['specialinc']='';
	redirect($str_backlink);
}

//page_footer();
?>
