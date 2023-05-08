<?php
// Addon : Die Auserwählten
// Ein Raum für Träger aller Male mit einigen Extras
// Benötigt : abandonedcastle.php, castleevents.php
// Modifiziert : newday.php, prison.php, inn.php, prefs.php, bio.php, special\vampire.php, graveyard.php
// by Maris (Maraxxus@gmx.de)

require_once 'common.php';
require_once(LIB_PATH.'house.lib.php');
checkday();

if ($session['user']['marks']>=CHOSEN_FULL || $access_control->su_check(access_control::SU_RIGHT_COMMENT))
{
	addcommentary();
}

if ($session['user']['marks']>=CHOSEN_FULL || $access_control->su_check(access_control::SU_RIGHT_DEBUG))
{
	if ($_GET['op']=='bg') 
	{ //Blutgott Start
		page_header('Der Opferaltar des Blutgottes');
		output ('`b`c`,D`Ae`4r Opferaltar des Blutgott`Ae`,s`0`c`b`n
		`,D`Au `4erkundest die sonderbare Feste ein wenig und gelangst in einen Bereich dessen Boden und Wände komplett mit schwarzem Marmor versehen sind. Im hinteren Bereich steht ein blutverkrusteter Altar, Gravuren in der Wand zeugen von grausigen Szenen.`n');
		if ($access_control->su_check(access_control::SU_RIGHT_DEBUG))
		{
			addnav('Blutchamp testen','bloodchamp.php?test=1');
		}
		if ($session['user']['marks'] & CHOSEN_BLOODGOD)
		{ //Pakt vorhanden
			$row_extra=user_get_aei('bloodchampdays');
			if ($row_extra['bloodchampdays']==0) 
			{ //kein Bloodchamp-Kampf
				output ('Zu dir spricht die dunkle Stimme: `," '.($session['user']['name']).'`,! Höre meine Stimme. Du, ' . ($session['user']['sex']?'die':'der') . ' Du auserwählt bist unter den Auserwählten, und ' . ($session['user']['sex']?'die':'der') . ' Du mächtig bist unter den Mächtigen und ' . ($session['user']['sex']?'die':'der') . ' Du in meiner Gunst stehst. Gehe hinaus in die Welt und künde von meiner Herrlichkeit! Sage Allen, dass in mir die Ewigkeit ruht! Schare sie um Dich, mein Kind, zu meinem Gefallen!"
				`n`n`n`&Du nimmst dir ein wenig Zeit und verharrst im stillen Gebet.`n');
				addnav('Was willst du tun?');
				addnav('Pakt brechen','thepath.php?op=bg3'); 
			}
			else
			{ //Bloodchamp-Kampf steht an
				output ('Zu dir spricht die dunkle Stimme: `," '.($session['user']['name']).'`,! So hast Du es nun gewagt der Herausforderung nachzukommen. Dies ist sehr löblich! Wisse, dass mein Champion auf Dich wartet, bereit mir zur Freude ein blutiges Schauspiel zu veranstalten. So gehe nun zu ihm und zeige mir, dass Du meiner Gunst würdig bist!"
				`n`n`n`&Es öffnet sich ein Durchgang in der Wand neben dem Altar.`n');
				addnav('Was willst du tun?');
				addnav('Pakt brechen','thepath.php?op=bg3');
				addnav('Zum Durchgang','bloodchamp.php'); 
			}
		}
		else
		{ //noch kein Pakt
			output ('Plötzlich spricht dich eine dunkle Stimme an: `," '.($session['user']['name']).'`,! Höre meine Stimme. Wisse, dass ich der bin, den Du den Blutgott nennst. Wisse auch, dass ich nur die Mächtigen und Würdigen um mich schare, und dass Du auserwählt bist in meinem Namen Schrecken zu verbreiten! Ich fordere ein Zehnt Deiner gesamten Lebenskraft und biete dir dafür die Gewissheit, nie wieder von einem meiner blutsaugenden Diener behelligt zu werden!"
			`n`n`n`&Willst du für ein Zehntel deiner Lebenspunkte einen Pakt mit dem Blutgott eingehen und dafür Immunität gegen Vampire erhalten?');
			addnav('Was nun?');
			addnav('Pakt eingehen','thepath.php?op=bg2');
			addnav('Lieber nicht','thepath.php');
		}
		addnav('Zurück zur Feste','thepath.php');
	}
	
	else if ($_GET['op']=='bg3') 
	{ //Pakt auflösen Sicherheitsabfrage
		page_header('Der Opferaltar des Blutgottes');
		output ('Willst du wirklich deinen Pakt mit dem Blutgott brechen?
		`n Deine Immunität gegen Vampirbisse wäre erloschen und deine geopferte Lebenskraft würdest du auch nicht zurück bekommen!');
		addnav('Sicher?');
		addnav('JA! Pakt brechen','thepath.php?op=bg4');
		addnav('NEIN! Verklickt...','thepath.php');
	}
	
	else if ($_GET['op']=='bg4')
	{ //Pakt auflösen
		page_header('Der Opferaltar des Blutgottes');
		output ('In der festen Überzeugung, dich nicht zum Handlanger irgendwelcher Götter zu machen zu lassen löst du den Pakt und der Blutgott wendet sich von dir ab. Du fühlst dich nun freier.');
		$Char->setBit(CHOSEN_BLOODGOD,'marks',0);
		debuglog('hat den Pakt mit dem Blutgott beendet');
		user_set_aei(array('bloodchampdays' =>0));
		addnav('Zurück zur Feste','thepath.php');
	}
	
	else if ($_GET['op']=='bg2') 
	{ //Pakt eingehen
		page_header('Der Opferaltar des Blutgottes');
		output ('`tDu nimmst den Opferdolch und gibst dem Blutgott ein Zehntel deiner Lebenskraft als Opfer dar. In Anerkennung dessen trifft dich ein roter Blitz und brennt winzig klein das Zeichen des Blutgottes in deinen Hals, auf dass jeder Vampir erkenne, dass du in der Gunst des Blutgottes stehst!`n');
		$Char->setBit(CHOSEN_BLOODGOD,'marks',1);
		user_set_aei(array('bloodchampdays' =>0));
		$losthp=round($session['user']['maxhitpoints']*0.1);
		debuglog('Opferte für einen Pakt mit dem Blutgott '.$losthp.' permantene LP.');
		$session['user']['maxhitpoints']-=$losthp;
		addnav('Zurück zur Feste','thepath.php');
	}
	
	else if ($_GET['op']=='charta') 
	{ //Charta der Auserwählten
		page_header('Die Charta der Auserwählten');
		output ('`b`c`uD`}i`Ie `tCharta der Auserwähl`It`}e`un`0`c`b`n
		`uD`}u `Is`tteigst eine schmale Treppe hinauf und begibst dich in eine fast quadratische Kammer. An der Stirnwand hängt ein riesiger handgefertigter Wandteppich. Es sieht so aus als sei er aus Goldfäden erstellt worden. Auf dem Wandteppich kannst du folgende Schrift le`Is`}e`un:`n
	`n`^Die Charta der Auserwählten:`n
	`AI`/    Das Geheimnis um die Auserwählten und ihre Macht ist zu hüten, auf das kein Gewöhnlicher davon erfahre!`n
	`AII`/   Die Auserwählten sind Begünstigte der Götter und haben den Gewöhnlichen in jeglicher Hinsicht Vorbild zu sein!`n
	`AIII`/  Die den Auserwählten verliehenen Kräfte sind von diesen weise und bedacht einzusetzen!`n
	`AIV`/   Kein Auserwählter treibe Schindluder mit der Gunst der Götter!`n
	`AV`/    Das Geheimnis um die Elementschreine und ihre Male hüte der Auserwählte wie sein eigenes Leben!`n
	`AVI`/   Der Auserwählte versinke weder in Selbstgefälligkeit, noch stelle er sich über die Gewöhnlichen!`n
	`AVII`/  Er lebe in Demut und ehre die Götter, die ihm ihre Gunst schenkten!`n
	`AVIII`/ So wie die Gunst der Götter vergänglich ist, so vergehen auch die Male der Elemente, sollte der Auserwählte freveln!`n
	`n`uD`}e`Ir `tuntere des Gobelins ist frei und kann um einige Punkte ergänzt wer`Id`}e`un.`n`n`n');
		addnav('Zurück zur Feste','thepath.php');
		viewcommentary('charta','Den Gobelin besudeln:',30,'schmierte');
	}
	
	else if ($_GET['op']=='key') 
	{ //Schlüsselmeister Start
		page_header('Der Schlüsselmeister');
		output ('`b`c`@D`je`2r `JSchlüsselmeis`2t`je`@r`0`c`b`n
		`@A`jl`2s `Jdu die Treppen in das Kellergewölbe der Feste hinabsteigst fällt dir ein kleiner verwinkelter Holztisch auf, hinter dem ein kauziger Gnom sitzt. Du weißt nicht wie lange er schon hier unten hockt, jedoch schaut er auf als er Gesellschaft wittert.
		`n `@"Willkommen beim Schlüsselmeister!"`J, sagt er mit krächzender Stimme, `@"Für nur `^500 Goldmünzen `@ kannst Du von mir erfahren, zu welchen Häusern ein Krieger Deiner Wahl Zugang hat!"`J 
		`nSein Angebot klingt verlockend, und du musst zugeben dass dich ein wenig die Neugier plagt, wer denn wo ein und aus g`2e`jh`@t.`n');
		addnav('500 Gold bezahlen','thepath.php?op=ke2');
		addnav('Zurück zur Feste','thepath.php');
	}
	
	else if ($_GET['op']=='ke2') 
	{ //Schlüsselmeister
		page_header('Der Schlüsselmeister');
		if (($session['user']['gold']<500) && ($_GET['who']=='')) 
		{
			output ('`JPeinlich berührt stellst du fest, dass du so viel Gold gar nicht bei dir hast. Also verlässt du schweigend den Keller.');
			addnav('Zurück zur Feste','thepath.php');
		} 
		else
		{
			if ($_GET['who']=='') 
			{
				output('`@"Nun, um wen geht es denn ?"');
				if ($_GET['subop']!='search')
				{
					output('<form action="thepath.php?op=ke2&subop=search" method="POST"><input name="name"><input type="submit" class="button" value="Suchen"></form>',true);
					addnav('','thepath.php?op=ke2&subop=search');
					addnav('Zurück','thepath.php');
				}
				else
				{
					addnav('Neue Suche','thepath.php?op=ke2');
					addnav('Zurück','thepath.php');
					$search = str_create_search_string($_POST['name']);
				$sql = 'SELECT acctid,name,level 
					FROM accounts 
					WHERE (locked=0 AND name LIKE "'.$search.'") 
					ORDER BY (login="'.db_real_escape_string($_POST['name']).'") DESC, level DESC';
					$result = db_query($sql);
					$max = db_num_rows($result);
					if ($max > 100) 
					{
						output('`n`n`@"Geht das vielleicht auch ein klein bisschen genauer? Damit könnte ja jeder gemeint sein!"`n');
						$max = 100;
					}
					output('<table border=0 cellpadding=0>
					<tr class="trhead">
					<th>Name</th>
					<th>Level</th>
					</tr>',true);
					for ($i=0;$i<$max;$i++)
					{
						$row = db_fetch_assoc($result);
						output('<tr class="'.($i%2?'trlight':'trdark').'">
						<td><a href="thepath.php?op=ke2&who='.($row['acctid']).'">'.$row['name'].'</a></td>
						<td align="center">'.$row['level'].'</td>
						</tr>',true);
						addnav('','thepath.php?op=ke2&who='.($row['acctid']));
					}
					output('</table>',true);
				}
			}
			else
			{
				require_once(LIB_PATH.'house.lib.php');
				$sql = 'SELECT acctid,name,house FROM accounts WHERE acctid="'.$_GET['who'].'"';
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				output('"`@Dann schauen wir mal wo sich '.($row['name']).'`@ so überall rumtreibt..."`n`n');
				$sql = 'SELECT * FROM keylist WHERE owner='.$row['acctid'].' AND type='.HOUSES_KEY_DEFAULT.' GROUP BY value1 ORDER BY value1='.$row['house'].' DESC, value1 ASC';
				$result = db_query($sql);
				output('<table cellpadding=2 align="center">
				<tr class="trhead">
				<th>HausNr.</th>
				<th>Name</th>
				<th>Haustyp</th>
				<th>Besitzer</th>
				</tr>',true);
				if ($row['house']>0)
				{
					$sql = 'SELECT houseid,housename,status,build_state FROM houses WHERE houseid='.$row['house'].' ORDER BY houseid DESC';
					$result2 = db_query($sql);
					$row2 = db_fetch_assoc($result2);
					if (!$_GET['limit']) 
					{
						output('<tr><td align="center">`3'.$row2['houseid'].'</td><td>'.$row2['housename'].' `&(Eigentümer)</td><td>'.get_house_state($row2['status'],$row2['build_state'],false).'</td></tr>',true); 
					}
				}
				if (db_num_rows($result)==0)
				{
					output('<tr><td colspan=4 align="center">`& '.($row['name']).' `i ist obdachlos!`i`0</td></tr>',true);
				}
				else
				{
					$rebuy=0;
					for ($i=0;$i<db_num_rows($result);$i++)
					{
						$item = db_fetch_assoc($result);
						$bgcolor=($i%2==1?'trlight':'trdark');
						$sql = 'SELECT houseid,housename,status,build_state,owner FROM houses WHERE houseid='.$item['value1'].' ORDER BY houseid DESC';
						$result2 = db_query($sql);
						$row2 = db_fetch_assoc($result2);
						$sql = 'SELECT name FROM accounts WHERE acctid = '.$row2['owner'].'';
						$result3 = db_query($sql);
						$row3 = db_fetch_assoc($result3);
						if ($amt!=$item['value1'] && $item['value1']!=$row['house'])
						{
							output('<tr class="'.$bgcolor.'"><td align="center">`3'.$row2['houseid'].'</td><td>'.$row2['housename'].'</td><td>'.get_house_state($row2['status'],$row2['build_state'],false).'</td><td>'.$row3['name'].'</td></tr>',true);
						}
						$amt=$item['value1'];
					}
				}
				if (!$_GET['limit']) 
				{
					$session['user']['gold']-=500;
				}
				addnav('Zurück zur Feste','thepath.php');
				output('</table>',true);
				output('</span>',true);
			}
		}
	}

	else 
	{ //Standardansicht für Auserwählte
		page_header('Die Feste der Auserwählten');
		output('`b`c`wD`Fi`*e `fFeste der Auserwähl`*t`Fe`wn`0`c`b`n
		`wD`Fu `*e`fntdeckst einen verschlungenen Pfad, der tief in den dunklen Wald führt. Je mehr Schritte du diesem Pfad folgst, umso mulmiger wird dir zumute. Doch plötzlich beginnen deine 5 Male wie wild zu jucken und du glaubst eine leise Stimme zu hören, die dich lockend noch tiefer in den Wald bittet.
		`nEntgegen allen Warnungen deines Verstandes folgst du der Stimme und gelangst nach einiger Zeit zu einem kubusförmigen, unscheinbaren Gebäude, das unter dem dichten Blätterdach des Waldes kaum sichtbar ist. Du betrittst die kleine Festung in freudiger Erwartung.
		`n Glückwunsch! Du hast die Feste der Auserwählten erreicht. Hier bist du unter Deinesgleichen und kannst von deinen ruhmreichen Taten erzäh`*l`Fe`wn.');
		addnav('G?Zum goldenen Gobelin','thepath.php?op=charta');
		addnav('B?Der Blutaltar','thepath.php?op=bg');
		addnav('H?Die Halle der Statuen','chosenfeats.php?op=list');
		addnav('S?Der Schlüsselmeister','thepath.php?op=key');
		addnav('Dodos Kammer','chosenfeats.php?op=dodo');
		addnav('K?Zum Koboldspitzel','chosenfeats.php?op=imp');
		addnav('W?Zur Wetterhexe','chosenfeats.php?op=hag');
		output('`n`n');
		viewcommentary('chosen','Verkünden:',30,'verkündet');
		addnav(':');
		addnav('Zurück zum Wald','forest.php');
	}
} //end Zutritt erlaubt
else
{ //Ansicht für Nichtauserwählte
	page_header('Der Wald');
	output ('`fDu entdeckst einen verschlungenen Pfad, der tief in den dunklen Wald führt. Je mehr Schritte du diesem Pfad folgst, umso mulmiger wird dir zumute. Irgendwann hältst du es nicht mehr aus und kehrst um. Du bist einfach noch nicht bereit, weißt aber, dass du es eines Tages sein wirst!');
	addnav('Zurück','forest.php');
}
page_footer();
?>
