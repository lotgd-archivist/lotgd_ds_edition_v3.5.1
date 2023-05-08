<?php
/******************************************************
* Autor: Salator (salator@gmx.de)
* inspired by G.Samsa (sorry, dein Script ist schon beim Lesen durchgefallen)
* lotgd-Version Dragonslayer V2.5
*
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
+Beschreibung:
+Ein Friedhof, auf dem man für seine Angehörigen und Freunde trauern kann,
+um ihnen das leben in der Unterwelt zu erleichtern
+oder raube das Grab aus...
+Enthält Ruhmeshalle gelöschter Charaktere
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

************
*Anleitung:*
************

SQL-Befehl in PHPmyAdmin ausführen:

CREATE TABLE `valhalla` (
`acctid` int(11) NOT null default '0',
`name` varchar(255) collate latin1_german1_ci NOT null default '',
`birth` date NOT null default '0000-00-00',
`death` date NOT null default '0000-00-00',
`dragonkills` tinyint(11) NOT null default '0',
`sex` tinyint(3) NOT null default '0',
`race` char(3) collate latin1_german1_ci NOT null default '',
`bio` text collate latin1_german1_ci NOT null,
`comments` text collate latin1_german1_ci NOT null
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Ruhmeshalle gelöschter Charaktere';
INSERT INTO `settings` ( `setting` , `value` ) VALUES ('famous_deleted_chars_min_DKs', '30');

Öffne lib/user.lib.php
´´´´´´´´´´´´´´´
suche:
function user_delete ($uid) {
Füge nach den Zeilen
	$acc = db_fetch_assoc(db_query($sql));
	$acc['tmpname'] = ($acc['cname'] ? $acc['cname'] : $acc['login']);
ein:
	//User in Valhalla speichern
	if($acc['dragonkills'] >= getsetting('famous_deleted_chars_min_DKs',30)) 
	{
		$sql='SELECT a.acctid,name,race,dragonkills,sex,birthday AS birth FROM accounts a LEFT JOIN account_extra_info aei USING(acctid) WHERE a.acctid='.$uid.' AND locked=0';
		$result=db_query($sql);
		if(db_num_rows($result))
		{
			$row=db_fetch_assoc($result);
			$row['death'] = getsetting('gamedate','0005-01-01');
			$row['bio']='';
			db_insert('valhalla',$row);
		}
	}
*/

require_once("common.php");
require_once(LIB_PATH.'board.lib.php');

page_header('Der Friedhof');
addcommentary();
music_set('friedhof'); //Mendelssohn-Trauermarsch, Chopin-MarcheFunebre
$ppp=30;
if (!$_GET['limit'])
{
	$page=0;
}
else
{
	$page=(int)$_GET['limit'];
}
$limit=''.($page*$ppp).','.($ppp);
switch ($_GET['op'])
{
//-----------Listen von Toten
case 'dead': //derzeit tote
{
	$sql = 'SELECT acctid,name,level,sex,deathpower,
		IF('.user_get_online().',"`@Online`0","`4Offline`0") AS loggedin
		FROM accounts
		WHERE alive = 0
			AND hitpoints=0
			AND dragonkills '.($_GET['type']?'>':'=').' 0
			AND restatlocation = 0
		ORDER BY laston DESC, dragonkills DESC
		LIMIT '.$limit;
	if($_GET['type']==1)
	{
		$str_out='`ND`(u g`)ehst einen der Seitenwege entlang, in den Bereich des Friedhofs wo sich die neuesten Gräber befinden. Hier gibt es außer frischen Gräbern nicht viel zu sehen. Zwar entdeckst du auf einigen Gräbern wertvolle Grabbeigaben, du weißt jedoch dass die Götter jene strafen, die es wagen, Dinge von den Gräbern zu entwen`(de`Nn.';
	}
	else
	{
		$str_out='`ND`(u b`)egibst dich in den Bereich des Friedhofs in dem die jüngsten und unerfahrensten Einwohner '.getsetting('townname','Atrahor').'s begraben sind. Noch so jung, und hatten doch keine Chance im rauhen All`(ta`Ng...';
	}
	$str_out.='`n`n`eAuf schlichten Holztafeln stehen die Namen der Verstorbenen:
	`n`n`0`c<table cellpadding=2 cellspacing=1 bgcolor=\'#999999\' align=\'center\'>
	<tr class=\'trhead\'>
	<th>&nbsp;#&nbsp;</th>
	<th>Name des Toten</th>
	<th>m/w</th>
	<th>online</th>
	</tr>';

	$result = db_query($sql);
	if ($page>0) addnav('Vorherige Seite','friedhof.php?op=dead&type='.$_GET['type'].'&limit='.($page-1));
	if (db_num_rows($result)>($ppp-1)) addnav('Nächste Seite','friedhof.php?op=dead&type='.$_GET['type'].'&limit='.($page+1));
	if (!db_num_rows($result))
	{
		$str_out.='<tr class=\'trdark\'><td colspan=4 align=\'center\'>Hier ruht niemand.</td></tr>';
	}
	else
	{
		while ($row = db_fetch_assoc($result)) 
		{
			$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
			$i++;
			$str_out.='<tr class="'.$bgclass.'"><td align=center>';
			$str_out.=$page*$ppp+$i;
			$str_out.='</td><td>';
			$str_out.='<a href="friedhof.php?op=viewgrave&amp;acctid='.$row['acctid'].'&amp;type='.$_GET['type'].'&amp;limit='.$page.'">'.$row['name'].'</a>';
			$str_out.='</td><td align=center>';
			$str_out.=($row['sex']?'<img src="./images/female.gif">':'<img src=./images/male.gif>');
			$str_out.='</td><td>';
			$str_out.=$row['loggedin'];
			$str_out.='</td></tr>';
			addnav('','friedhof.php?op=viewgrave&acctid='.$row['acctid'].'&type='.$_GET['type'].'&limit='.$page);
		}
	}
	output($str_out.'</table>`c');
	break;
}
case 'valhalla': //gelöschte Chars
{
//music_set('valhalla'); //Hekate-Der Nibelunge Nôt

	$str_out='`n`n`c<form action="friedhof.php?op=valhalla&type='.$_GET['type'].'&do=search" method="POST">
                Nach Name suchen: <input name="search" value="'.stripslashes($_POST['search']).'">
                <input type="submit" class="button" value="Suchen">
                </form>`c`n';
	addnav('','friedhof.php?op=valhalla&type='.$_GET['type'].'&do=search');
	
	if($_GET['do'] == 'search'){
		$sql = "SELECT * FROM valhalla WHERE name_clean LIKE '%".db_real_escape_string($_POST['search'])."%' ORDER BY dragonkills DESC LIMIT 20";
	}
	else{
			$sql = 'SELECT * FROM valhalla ';
			//$sql.='WHERE dragonkills'.($_GET['type']?'>=':'<').'100 '; //Vorbereitung für die Unterscheidung zwischen Valhalla und Folkvang
			$sql.='ORDER BY dragonkills DESC LIMIT '.$limit;
	}
	
	$result = db_query($sql);
	if ($page>0) 
	{
		addnav('Vorherige Seite','friedhof.php?op=valhalla&type='.$_GET['type'].'&limit='.($page-1));
	}
	if (db_num_rows($result)>($ppp-1)) 
	{
		addnav('Nächste Seite','friedhof.php?op=valhalla&type='.$_GET['type'].'&limit='.($page+1));
	}
	if($_GET['type']==1) 
	{
		$str_out.='`c`b`)W`ea`fl`yha`fl`el`)a`0`b`c
		`n`yE`fh`er`)fürchtig betrittst du die große, reich verzierte Halle. Hier wurde den berühmtesten Kriegern vergangener Zeiten ein Denkmal gese`et`fz`yt.';
	}
	else  
	{
		$str_out.='`c`b`)F`eo`sl`ykv`sa`en`)g`0`b`c
		`n`yE`sh`er`)fürchtig betrittst du die große Halle. Hier wurde den großen Kriegern vergangener Zeiten ein Denkmal gese`et`sz`yt.';
	}
	$str_out.='`n`n`0`c<table cellpadding=2 cellspacing=1 bgcolor="#999999" align="center"><tr class="trhead"><td>Grabstein</td><td>Name des Toten</td>';
	if($access_control->su_check(access_control::SU_RIGHT_EDITORUSER)) 
	{
		$str_out.='<td>Op</td></tr>';
	}
	if (!db_num_rows($result))
	{
		$str_out.='<tr class="trdark"><td colspan="3" align="center">Es gibt noch keine ruhmreichen Krieger.</td></tr>';
	}
	else
	{
		while ($row = db_fetch_assoc($result)) 
		{
			$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
			$i++;
			$str_out.='<tr class='.$bgclass.'><td>';
			$str_out.=$page*$ppp+$i;
			$str_out.='</td><td>';
			$str_out.='<a href="friedhof.php?op=view&amp;type='.$_GET['type'].'&amp;acctid='.$row['acctid'].'&amp;limit='.$page.'">'.$row['name'].'</a>';
			$str_out.='</td>';
			addnav('','friedhof.php?op=view&type='.$_GET['type'].'&acctid='.$row['acctid'].'&limit='.$page);
			if($access_control->su_check(access_control::SU_RIGHT_EDITORUSER))
			{
				$str_out.='<td><a href="friedhof.php?op=del_user&amp;type='.$_GET['type'].'&amp;acctid='.$row['acctid'].'">del</a></td>';
				addnav('','friedhof.php?op=del_user&type='.$_GET['type'].'&acctid='.$row['acctid']);
			}
			$str_out.='</tr>';
		}		
	}
	if($session['user']['exchangequest']==18)
	{
		addnav('`%Tote beschwören`0','exchangequest.php');
	}
	output($str_out.'</table>`c');
	break;
}
//--------------- der Tempel
case 'temple': //Zeremonientempel
{
	output('`c`b`(D`)a`es `sM`fa`&us`fo`sl`ee`)u`(m`0`b`c
	`n`(E`)h`er`sf`fu`&rchtsvoll betrittst du das Mausoleum. Es ist im gotischen Spitzbogen-Stil gebaut. Durch die schmalen, bunt verglasten Fenster an den Seitenwänden fällt etwas Tageslicht in den Raum. Geradewegs führt ein Gang an Sitzbänken vorbei direkt zum Altar.
	`nAuf der linken Seite entdeckst du einen kleinen Stand, an dem Blumen verkauft w`fe`sr`ed`)e`(n.`n`n');
	viewcommentary("friedhof_temple","Leise sprechen",25,"spricht leise");
	if($session['user']['profession']==PROF_PRIEST || 
	$session['user']['profession']==PROF_PRIEST_HEAD || 
	$session['user']['profession']==PROF_WITCH || 
	$session['user']['profession']==PROF_WITCH_HEAD ||
	$access_control->su_check(access_control::SU_RIGHT_DEBUG))
	{
		addnav('Priester-Raum','friedhof.php?op=priestroom');
	}
	addnav ('A?Zum Altar','friedhof.php?op=alter');
	
	if($Char->alive)
	{
		addnav ('Blumen kaufen (100 Gold)','friedhof.php?op=buyflowers');
	}
	break;
}
case 'priestroom': //Priesterraum
{
	output('`kVor dem Tod sind alle gleich. Also kommen auch hier Priester, Hexen und Schamanen aller Gesinnungen zusammen um Angehörigen ihrer Glaubensrichtung zur letzten Ruhestätte zu begleiten.
	`nHier in diesem kleinen Hinterzimmer können in Ruhe Vorbereitungen für die Zeremonie getroffen werden.`n');
	viewcommentary('graveyard_priestroom');
	addnav('Begräbnis-Ankündigung','friedhof.php?op=board1');
	addnav('M?Zum Mausoleum','friedhof.php?op=temple');
	break;
}
case 'board1': //Ankündigung der nächsten Begräbnisse
{
	output('`kSchwarzes Brett für die nächste Begräbniszeremonie, der neueste Text wird am Altar angezeigt.
	`n`n`&Format: Name, (Zeilenumbruch), weitere Infos wie Datum der Zeremonie.`n');
	board_view_form('Eintragen','`&Deine Planung (bitte nur eine):');
	if($_GET['board_action'] == "add") {
		board_add('friedhof');
		redirect('friedhof.php?op=board1');
	}
	output('`n`n');
	board_view('friedhof',2,
		'`^An der Tafel hängen diese Ankündigungen:',
		'`^Kein Begräbnis geplant.');
	addnav('Priester-Raum','friedhof.php?op=priestroom');
	addnav('M?Zum Mausoleum','friedhof.php?op=temple');
	break;
}
case 'alter': //demnächst zu begrabende
{
    $sql = 'SELECT accounts.name, DATEDIFF(NOW(),laston) AS data1 FROM accounts WHERE locked=0 AND DATEDIFF(NOW(),laston) <= '.getsetting('expireoldacct',50).' ORDER BY data1 DESC, dragonkills DESC LIMIT 1';
	$result=db_query($sql);
	$row=db_fetch_assoc($result);
	$daysleft=getsetting('expireoldacct',50)-$row['data1'];
	$nomsg='`k'.$row['name'].'`k. Die Trauerfeier findet '.($daysleft>0?'in '.$daysleft.' Tagen':'heute').' im engsten Familienkreis statt.';
	output('`kAuf dem Altar steht ein geöffneter Sarg. Darin befindet sich die sterbliche Hülle von ');
	board_view('friedhof',($access_control->su_check(access_control::SU_RIGHT_COMMENT))?2:1,'',$nomsg,false,false,false,false,1);
	addnav('M?Zum Mausoleum','friedhof.php?op=temple');
	break;
}
case 'buyflowers': //1 Gefallen kaufen
{
	output('`)G`el`se`pi`gch links neben dem Eingang befindet sich ein Stand mit Schnittblumen. Damit verdient sich der Totengräber ein kleines Zubrot.`n Diese Sträuße kannst du auf den Gräbern deiner liebsten Verblichenen verteilen und ihr Grab verschö`pn`se`er`)n.`n');
	if($session['user']['gold']>=100)
	{
		$session['user']['gold']-=100;
		$session['graveflowers']++;
		output('Also suchst du dir einen schönen Strauß aus und legst 100 Goldstücke in die Kasse.');
	}
	else output('Leider hast du keine 100 Goldstücke dabei.');
	addnav('M?Zum Mausoleum','friedhof.php?op=temple');
	break;
}
//--------- Grab-Aktionen
case 'viewgrave': //Grab näher betrachten
{
	$sql='SELECT name,deathpower,loggedin,specialmisc,newstext,pvpflag FROM news LEFT JOIN accounts ON accountid=acctid WHERE acctid='.intval($_GET['acctid']).' AND newstext NOT LIKE "%erniedrigt%" ORDER BY newsid DESC LIMIT 1';
	$row=db_fetch_assoc(db_query($sql));
	output('`)Du betrachtest das Grab von '.$row['name'].'`). Hier kannst du um einen lieben Mitbürger trauern. Blumen und Kränze kannst du im Tempel erwerben.`nOder willst du lieber das Grab plündern?`n`nDie Vögel zwitschern dir zu, warum '.$row['name'].'`) gestorben ist:`n`^'.$row['newstext'].'`n`)Natürlich können sich auch Vögel mal irren...');
//	admin_output('`n`WSU: Gefallen: '.$row['deathpower'],false);
	if($session['graveflowers']>0) 
	{
		addnav('Blumen ablegen','friedhof.php?op=flowers&type='.$_GET['type'].'&limit='.$page.'&acctid='.$_GET['acctid']);
	}
	addnav('Totenwache','friedhof.php?op=wake&type='.$_GET['type'].'&acctid='.$_GET['acctid']);
	addnav(':');
	if($row['loggedin']==0 && $session['user']['playerfights']>0  && $session['user']['turns']>0 && $session['user']['gravefights']>=3 && $_GET['type']==1)
	{
		$atk=$row['deathpower'];
		if($row['specialmisc']=='tombraid.php' || $row['pvpflag']==PVP_IMMU) 
		{
			$atk=1;
		}
		addnav('p?Grab plündern','friedhof.php?op=tombraid&atk='.$atk.'&acctid='.$_GET['acctid']);
		addnav(':');
	}
	addnav('G?Weitere Gräber','friedhof.php?op=dead&type='.$_GET['type'].'&limit='.$page);
	$session['graveid']=$_GET['acctid'];
	break;
}
case 'flowers': //Blumen ablegen
{
	$session['daily']['graveflowers']++;
	$session['graveflowers']--;
	$sql='SELECT acctid,name,sex,deathpower,marks FROM accounts WHERE acctid='.intval($_GET['acctid']);
	$row=db_fetch_assoc(db_query($sql));
	output('`gDu legst eine Blume auf das Grab und trauerst eine Weile um '.$row['name'].'`g...`n');
	if(($row['deathpower']<80 || ($row['deathpower']<100 && $row['marks']<CHOSEN_FULL))
		&& ac_check($row) == false 
		&& e_rand(1,$session['daily']['graveflowers'])<5)
	{
		$row['deathpower']++;
		
		user_update(
			array
			(
				'deathpower'=>$row['deathpower'],
			),
			$_GET['acctid']
		);
		
		debuglog('legte eine Blume auf das Grab von',$row['acctid']);
		if ($row['deathpower']==100 || ($row['deathpower']==80 && $row['marks']>=CHOSEN_FULL))
		{
			output('`ARamius`) ist gerührt von deiner Liebe zu '.$row['name'].'`) und beschließt, '.($row['sex']?'ihr':'ihm').' noch eine Chance zu geben.`n');
		}
	}
	addnav('Zum Grab','friedhof.php?op=viewgrave&type='.$_GET['type'].'&limit='.$_GET['limit'].'&acctid='.$_GET['acctid']);
	break;
}
case 'wake': //Totenwache
{
	if ($session['graveid'])
	{
		output('`)Du überlegst dir, ob du nicht das Grab vor gemeinen Grabräubern schützen möchtest.`nDazu müsstest du dein Nachtlager hier aufschlagen und bist für jeden angreifbar.`nWillst du das?');
		addnav('Logout','login.php?op=logout');
		addnav('Zum Grab','friedhof.php?op=viewgrave&type='.$_GET['type'].'&limit='.$_GET['limit'].'&acctid='.$_GET['acctid']);
	}
	else //eigentlich unnötig, Login ist auf der News-Seite
	{
		output('`)Du beendest die Totenwache.');
	}
	break;
}
case 'tombraid': //Grab plündern - 1. Kampf, 2. Totenwächter?, 3. was klauen
{
	$step=$_GET['bg'];
	if($step==2) //prüfen ob einer Totenwache hält
	{
		if ($session['user']['pvpflag']==PVP_IMMU)
		{
			$session['user']['pvpflag']="1986-10-06 00:42:00";
			output("`n`4`bDeine Immunität ist hiermit verfallen!`b`0`n");
		}
		pvpwarning(true);
		$sql='SELECT name,sex,maxhitpoints,weapon,attack,defence FROM accounts WHERE loggedin=0 AND alive=1 AND restorepage like"%?op=wake&acctid='.$session['graveid'].'%" AND pvpflag !="5013-10-06 00:42:00" ORDER BY maxhitpoints DESC LIMIT 1';
		$result=db_query($sql);
		if(db_num_rows($result)>0)
		{
			$row=db_fetch_assoc($result);
			output('`QDu beginnst gerade, die Schaufel in die lockere Graberde zu rammen, als du von '.$row['name'].'`Q aufgehalten wirst.');
			$hps=e_rand($row['maxhitpoints']*0.9,$row['maxhitpoints']*1.1);
			$badguy = array('creaturename'=>$row['name'],'creaturelevel'=>$row['level'],'creatureweapon'=>$row['weapon'],'creatureattack'=>$row['attack'],'creaturedefense'=>$row['defence'],'creaturehealth'=>$hps, 'diddamage'=>0, 'pvp'=>1);
			$session['user']['badguy']=createstring($badguy);
			addnav('Kämpfe!','friedhof.php?op=fight');
		}
		else
		{
			$step=3;
		}
	}
	if($step==3) //alle Gegner besiegt
	{
		//Statistik hochzählen
		user_set_stats(array('tombraids'=>'tombraids+1'));
		
		$sql='SELECT alive,loggedin,name,pvpflag,dragonkills FROM accounts WHERE acctid='.$session['graveid'];
		$row=db_fetch_assoc(db_query($sql));
		if($row['alive']==0 && $row['loggedin']==0)
		{
			output('`)Du schaust dich noch einmal um. Nein, weit und breit ist niemand zu sehen der dir bei deinem Vorhaben gefährlich werden könnte. ');
			if($session['user']['gravefights']>=10)
			{
				addnav('E?Gold und Edelsteine suchen','friedhof.php?op=gold');
			}
			if($session['user']['gravefights']>=3)
			{
				addnav('Krimskram suchen','friedhof.php?op=items');
				if($row['pvpflag']!=PVP_IMMU && $session['user']['dragonkills']>75)
				{
					$row_extra = user_get_aei('trophyhunter');
					if($row_extra['trophyhunter']==0) //ja die 0 ist beabsichtigt
					{
						$who=rawurlencode($row['name'].'s Leichnam');
						$dks=min(ceil($row['dragonkills']/5),25);
						addnav('Gammelfleisch abschneiden','trophy.php?op=look&who='.$who.'&dks='.$dks.'&where=-1&id='.$session['graveid']);
					}
				}
			}
			if($session['user']['gravefights']<3)
			{
				output('Doch irgendwie fühlst du dich total schlapp. Schade, so wird das nichts mit der Plünderei.');
				unset($session['graveid']);
			}
			else
			{
				output('Na dann mal los.');
			}
		}
		else
		{
			output('`kNachdem du eine halbe Ewigkeit in dem Grab gewühlt hast musst du feststellen: `$Es ist vollkommen leer...');
			unset($session['graveid']);
		}
	}
	if(!$step)
	{
		if (ac_check($_GET['acctid']))
		{
			$str_output .= '`$`bDas geht doch nicht!!`b Du kannst doch nicht deine eigenen Charaktere oder deine eigene Familie ausrauben!';
			addnav('Zurück zum Friedhof','friedhof.php');
		}
		else
		{
			output('`4Die Stadtwache wird auf dich aufmerksam als du so über den Friedhof schleichst.');
			if ($session['user']['pvpflag']==PVP_IMMU)
			{
				output("`n`&Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!`0`n`n");
			}
			pvpwarning();
			$session['graveid']=$_GET['acctid'];
			$hps=abs(max($session['user']['maxhitpoints']*1.1,$session['user']['hitpoints']));
			$atk = $session['user']['attack']*1.2;
			$def = $session['user']['defence']*1.2;
			if($session['bufflist']['mount']['rounds']>5)
			{
				$atk*=1.2;
				$def*=1.2;
			}
			if($_GET['atk']<100)
			{
				$atk*=1.4;
				$def*=1.3;
			}
			$badguy = array('creaturename'=>'Stadtwache','creaturelevel'=>$session['user']['level'],'creatureweapon'=>'starker Holzknüppel','creatureattack'=>$atk,'creaturedefense'=>$def,'creaturehealth'=>$hps, 'diddamage'=>0);
			$session['user']['badguy']=createstring($badguy);
			addnav('Kämpfe!','friedhof.php?op=fight');
		}
	}
	break;
}
case 'gold': //Gold/ES klauen
{
	if($session['graveid'])
	{
		$sql='SELECT name,gold,gems FROM accounts WHERE acctid='.$session['graveid'];
		$row=db_fetch_assoc(db_query($sql));
		$gold=min($row['gold'],666);
		$goldnew=$row['gold']-$gold;
		$gems=min($row['gems'],1);
		$gemsnew=$row['gems']-$gems;
		output('`kBeim Durchsuchen der Leiche von '.$row['name'].'`k findest du `^'.$gold.' Gold`k und `#'.$gems.' Edelsteine`k, du verlierst 10 Grabkämpfe.`n');
		$session['user']['gold']+=$gold;
		$session['user']['gems']+=$gems;
		$session['user']['gravefights']-=10;
		
		user_update(
			array
			(
				'gold'=>$goldnew,
				'gems'=>$gemsnew,
				'specialmisc'=>'tombraid.php'
			),
			$session['graveid']
		);
		
		systemmail($session['graveid'],'`$Grabraub!`0','`$'.$session['user']['name'].'`$ hat dein Grab geplündert und hat dir `^'.$gold.'`$ Gold und `#'.$gems.'`$ Edelsteine geklaut!');
		addcrimes($session['user']['name'].'`$ plündert `^'.$gold.'`$ Gold und `#'.$gems.'`$ Edelsteine von einem Grab.');
		debuglog('Grabraub: erbeutete '.$gold.' Gold und '.$gems.' Edelsteine',$session['graveid']);
		if($gems>0 && $session['user']['maxhitpoints']>$session['user']['level']*10 && e_rand(1,2)==2)
		{
			output('`4Die Götter strafen dich und nehmen dir einen permanenten Lebenspunkt!`n');
			$session['user']['maxhitpoints']--;
		}
		unset ($session['graveid']);
	}
	else output('`)In dem ganzen Durcheinander hast du leider vergessen welches Grab du plündern wolltest.');
	break;
}
case 'items': //Items klauen
{
	if($session['graveid'])
	{
		$sql='SELECT name FROM accounts WHERE acctid='.$session['graveid'];
		$row=db_fetch_assoc(db_query($sql));
		$rowi=item_get('owner='.$session['graveid'].' AND deposit1=0 AND it.tpl_class IN (3,9,17,21,24,25,26) ORDER BY rand()',true,'i.id,i.name');
		//tpl_class: 3-Beute, 9-Fluch, 16-Trophäe, 17-Tränke, 19-Rune, 21-Kleintiere, 24-Zutaten, 25-Nahrungsmittel, 26-Rohstoffe
		if(is_array($rowi))
		{
			$item_new=array('owner' => $session['user']['acctid'], 'deposit1'=>0, 'deposit2'=>0);
			output('`kBeim Durchsuchen der Leiche von '.$row['name'].'`k findest du `^'.$rowi['name'].'`k. Du verlierst 3 Grabkämpfe.`n');
			$session['user']['gravefights']-=3;
			if(item_set('id='.$rowi['id'],$item_new))
			{
				systemmail($session['graveid'],'`$Grabraub!`0','`$'.$session['user']['name'].'`$ hat dein Grab geplündert und dir `^'.$rowi['name'].'`$ geklaut!');
				addcrimes($session['user']['name'].'`$ plündert `^'.$rowi['name'].'`$ von einem Grab.');
				debuglog('Grabraub: erbeutete '.$rowi['name'].'`0',$session['graveid']);
				
				user_update(
					array
					(
						'specialmisc'=>'tombraid.php'
					),
					$session['graveid']
				);
				
				if($session['user']['maxhitpoints']>$session['user']['level']*10 && e_rand(1,2)==2)
				{
					output('`4Die Götter strafen dich und nehmen dir einen permanenten Lebenspunkt!`n');
					$session['user']['maxhitpoints']--;
				}
			}
			else 
			{
				output('`4Aber für '.$rowi['name'].'`4 ist einfach kein Platz mehr in deinem Beutel. Schaaade...');
			}
		}
		else 
		{
			output('`4Du findest nix!');
		}
		unset ($session['graveid']);
	}
	else 
	{
		output('`)In dem ganzen Durcheinander hast du leider vergessen welches Grab du plündern wolltest.');
	}
	break;
}
// --------------Ruhmeshalle-Aktionen
case 'view': //Statue/Grabstein ansehen
{
	$sql = 'SELECT v.*,r.colname FROM valhalla v LEFT JOIN races r ON (id=race) WHERE acctid='.$_GET['acctid'];
	$row = db_fetch_assoc(db_query($sql));

	output('`tDu betrachtest '.($_GET['type']?'eine Statue, die einen '.$row['colname'].'`t darstellt,':'einen Grabstein').' näher. In goldenen Lettern steht eingraviert um wen es sich handelt:`0
	`n`c`^'.$row['name'].'`n`^* '.getgamedate($row['birth']).'`n`^&dagger; '.getgamedate($row['death']).'`n`n'.($row['sex']?'Sie':'Er').' war ein ruhmreicher '.$row['colname'].'`n`^ mit '.$row['dragonkills'].' Heldentat' . ($row['dragonkills']>1?'en':'') . '
	`0`c'.($row['bio']?'`n`n`tDu erinnerst dich, was die Leute erzählen, wie '.$row['name'].'`t war:
	`n`n`7'.$row['bio']:'').'
	`n`n`t Auf einer Extratafel ist Platz für ein paar Worte von den Hinterbliebenen.
	`n`n`7');
	if($row['comments']) 
	{
		$arr_comments=adv_unserialize($row['comments']);
		foreach($arr_comments as $key=>$val){
			$str_out.=$val;
			if($access_control->su_check(access_control::SU_RIGHT_COMMENT)) {
				$str_out.=' '.create_lnk('[del]','friedhof.php?op=del_memory&type='.$_GET['type'].'&acctid='.$_GET['acctid'].'&entry='.$key,true,false,'Diesen Eintrag wirklich löschen?').'`n';
			}
			$str_out.='`n<hr>';
		}
		output($str_out);
	}
	else 
	{
		output('Es hat noch niemand einen Nachruf geschrieben, oder er wurde noch nicht in Stein gemeißelt.');
	}
	addnav('Weitere Statuen','friedhof.php?op=valhalla&type='.$_GET['type'].'&limit='.$page);
	addnav('Nachruf schreiben','friedhof.php?op=memory&type='.$_GET['type'].'&acctid='.$row['acctid']);
	break;
}
case 'del_user': //User endgültig löschen
{
	if($_GET['ack']=='yes')
	{
		$sql = 'DELETE FROM valhalla WHERE acctid='.$_GET['acctid'];
		db_query($sql);
		redirect('friedhof.php?op=valhalla&type='.$_GET['type']);
	}
	else
	{
		$sql = 'SELECT * FROM valhalla WHERE acctid='.$_GET['acctid'];
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		output('`)Willst du das Grab von '.$row['name'].'`$ wirklich zerstören?`n`n');
		addnav('Grab zerstören?');
		output('<a href="friedhof.php?op=del_user&acctid='.$_GET['acctid'].'&ack=yes">Ja</a>`n',true);
		addnav('Ja','friedhof.php?op=del_user&acctid='.$_GET['acctid'].'&ack=yes');
		addnav('','friedhof.php?op=del_user&acctid='.$_GET['acctid'].'&ack=yes');
		output('<a href="friedhof.php?op=valhalla&type='.$_GET['type'].'">Nein</a>',true);
		addnav('','friedhof.php?op=valhalla&type='.$_GET['type']);
		addnav('Nein','friedhof.php?op=valhalla&type='.$_GET['type']);
	}
	break;
}
case 'memory': //Nachruf schreiben
{
	$sql = 'SELECT comments FROM valhalla WHERE acctid='.$_GET['acctid'];
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$arr_comments=array();
	if($row['comments']!='') 
	{
		$arr_comments=adv_unserialize($row['comments']);
	}
	if($_POST['text']) 
	{
		$text=closetags($_POST['text'],'`c`i`b');
		if(!mb_strpos($text,'©')) {
			$text.="`n`n\n`7© ".$session['user']['name'];
		}
		
		$arr_comments[$session['user']['acctid']]=$text;
		
		$sql='UPDATE valhalla SET comments=\''.db_real_escape_string(utf8_serialize($arr_comments)).'\' WHERE acctid='.(int)$_GET['acctid'];
		
		db_query($sql);
	}
	foreach($arr_comments as $val)
	{
		$str_out.=$val.'`n<hr>';
	}
		output($str_out.'`n`(Hier kannst du einen Nachruf verfassen. Bitte zolle dem Verblichenen Respekt, die Friedhofsverwaltung wird Tafeln mit sinnlosen Schmierereien kostenpflichtig entfernen.`0');
		rawoutput("<form action='friedhof.php?op=memory&amp;type=".$_GET['type']."&amp;acctid=".$_GET['acctid']."' method='POST'><textarea name='text' id='text' class='input' cols='50' rows='10'>".$arr_comments[$session['user']['acctid']]."</textarea><br><input type='submit' class='button' value='In Stein meißeln'></form>");
		output(focus_form_element('text'));
		addnav('','friedhof.php?op=memory&type='.$_GET['type'].'&acctid='.$_GET['acctid']);
		addnav('Zurück zur Statue','friedhof.php?op=view&type='.$_GET['type'].'&acctid='.$_GET['acctid']);
	break;
}
case 'del_memory': //Nachruf löschen
{
	$sql = 'SELECT comments FROM valhalla WHERE acctid='.$_GET['acctid'];
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$arr_comments=adv_unserialize($row['comments']);
	unset ($arr_comments[intval($_GET['entry'])]);
	$sql='UPDATE valhalla SET comments=\''.db_real_escape_string(utf8_serialize($arr_comments)).'\' WHERE acctid='.$_GET['acctid'];
	db_query($sql);
	redirect('friedhof.php?op=view&type='.$_GET['type'].'&acctid='.$_GET['acctid']);
	break;
}
case 'fight':
{
	$fight=true;
	include 'battle.php';
	$battle=true;
	if ($victory)
	{
		if ($badguy['creaturename']=='Stadtwache')
		{
			output('`n`#Du hast die Stadtwache besiegt und kannst dich weiter dem Grab widmen!`nDu bekommst ein paar Erfahrungspunkte.');
			$session['user']['experience'] += $session['user']['level']*10;
			$session['user']['turns']--;
			$session['user']['playerfights']--;
			addnav('Weiter','friedhof.php?op=tombraid&bg=2');
		}
		else
		{
			output('`n`#'.$badguy['creaturename'].'`# kann das Grab nicht länger schützen!');
			addnav('Weiter','friedhof.php?op=tombraid&bg=3');
		}
		$badguy=array();
	}
	else if ($defeat)
	{
		unset ($session['graveid']);
		if ($badguy['creaturename']=='Stadtwache')
		{
			$chance=e_rand(1,2);
			//Bonus für Diebe
			if (($session['user']['specialty']==3) && ($chance==1))
			{
				output('`n`$Die Stadtwache hat dich besiegt. Doch als Dieb weist du dich zu erretten und stellst dich tot. Als die Wache geschockt einen Moment unaufmerksam wird rennst du schnell weg.');
				$session['user']['hitpoints']=1;
				addnav('Weiter','village.php');
				addnews('`%'.$session['user']['name'].'`3 wurde von der Stadtwache bei einer Grabschändung gestellt, konnte aber entkommen.');
			}
			else
			{
				if (($session['user']['profession']!=21) && ($session['user']['profession']!=22))
				{
					output('`n`$Die Stadtwache hat dich besiegt und nimmt dich fest. Du wirst wegen versuchter Grabschändung für 2 Tage in den Kerker geworfen!');
					$session['user']['hitpoints']=$session['user']['maxhitpoints'];
					$session['user']['imprisoned']=2;
					$session['user']['badguy']="";
					addnav('Weiter','prison.php');
					addnews('`%'.$session['user']['name'].'`3 wurde von der Stadtwache bei einer Grabschändung festgenommen und in den Kerker geworfen.');
				}
				else
				{
					output('`n`$Die Stadtwache hat dich besiegt. Durch deine richterliche Immunität bleibt dir der Kerker erspart.');
					addnews('`%Richter '.$session['user']['name'].'`3 wurde von der Stadtwache bei einer Grabschändung gefasst und entging dank der Immunität dem Kerker.');
					addcrimes('`%Richter '.$session['user']['name'].'`3 wurde von der Stadtwache bei einer Grabschändung gefasst und entging dank der Immunität dem Kerker.');
					$session['user']['hitpoints']=1;
					addnav('Weiter','village.php');
				}
			}
		}
		else
		{
			output('`n`$'.$badguy['creaturename'].'`$ hat dich besiegt. Du liegst schwer verletzt am Boden!`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte.');
			$session['user']['hitpoints'] = 1;
			$session['user']['charm'] -= 3;
			addnews('`%'.$session['user']['name'].'`3 stieß bei einer Grabschändung auf unerwartete Gegenwehr und verletzte sich schwer.');
			addnav('Davonkriechen','village.php');
		}
	}
	else
	{
		fightnav(false,true);
	}
	break;
}
//---------------Eingang
default:
	{
		output('`c`b`ND`(er `)Fried`(ho`Nf`0`b`c
		`n`ND`(u g`)eh`est durch ein großes schmiedeeisernes Tor und betrittst den Platz der ewigen Ruhe. Am Ende des Hauptweges, welcher auf beiden Seiten von Gräberreihen flankiert wird, erblickst du einen prunkvollen Säulentempel mit dem Schriftzug
		`0`c`yDen ruhmreichen Helden '.getsetting('townname','Atrahor').'s`0`c
		`e`nMitten auf dem Friedhof steht ein altertümliches Mausoleum, welches vor nicht allzu langer Zeit aufwändig restauriert wurde. Eine geflügelte Figur, deren Augen dir zu folgen scheinen, ziert die Dachspitze. Auf der Gedenktafel über der Tür ist zu `)le`(se`Nn:`0
		`c`ARamius, Herr über den Tod`0`c`n`n');
		
		if ($Char->alive==0)
			{
			output('`4`bAchtung: Der Leveltod deines Charakters gehört `inicht`i zum Rollenspiel!`b`0');
			}
			
		viewcommentary("friedhof_main","Hinzufügen",25,"spricht leise");
		addnav('Mausoleum betreten','friedhof.php?op=temple');
		
		if($Char->alive)
		{
			addnav('Ramius\' Gesellschaft');
			addnav('T?Zu den Toten','friedhof.php?op=dead&type=1');
			addnav('Friedhof der Fremden','friedhof.php?op=dead&type=0');
			addnav('G?Die Gruft','lowercity.php?op=gruft');
		}
		addnav('Ruhmeshalle');
		addnav('Walhalla betreten','friedhof.php?op=valhalla&type=1');
//		addnav('Folkvang betrachten','friedhof.php?op=valhalla&type=0'); //Vorbereitung für die Unterscheidung zwischen Walhalla und Folkvang
	
		if($Char->alive)
		{
		//Bossgegner Hel einfügen
        include_once(LIB_PATH.'boss.lib.php');
				boss_get_nav('hel');
    }
    
	}
}
if(!$fight)
{
	addnav('Zurück');
	if($_GET['op'])	
	{
		addnav('H?Zum Hauptweg','friedhof.php');
	}
	
  if($Char->alive)
  {
  addnav('d?Zur Stadt','village.php');
  }
  else
  {
  addnav('Z?Zurück zu den Schatten','shades.php');
  }
}
page_footer();
?>