<?php
/**
 * Hall of Fame
 * Auflistung verschiedener Top-Listen
 * 
 */
require_once "common.php";
require_once(LIB_PATH.'profession.lib.php');
page_header('Ruhmeshalle');
checkday();
$playersperpage = 50;
//$max_age = ' AND age <= '.getsetting('maxagepvp',50);

$max_age = ' AND 1=1  ';

$str_sql_no_su = '';

$str_su_groups = implode(',',$access_control->get_superuser_sugroups());
$str_sql_no_su = " AND (accounts.superuser NOT IN ($str_su_groups)) AND accounts.nohof = 0 ";

//Default Anzeige bei Seitenstart
$op = 'kills';
if (isset($_GET['op']))
{
	$op = $_GET['op'];
}

//Default Sortierung bei Seitenstart
$subop = 'most';
if ($_GET['subop'])
{
	$subop = $_GET['subop'];
}


if ($session['user']['alive'])
{
	addnav('d?Zurück zum Stadtzentrum','village.php');
	addnav('+?Zum OOC-Bereich','ooc_area.php');
}
else
{
	addnav('d?Zurück zu den Schatten','shades.php');
}

addnav('Bestenlisten');
addnav('Alter','hof.php?op=birth&subop='.$subop);
addnav('Arenakämpfer','hof.php?op=battlepoints&subop='.$subop);
addnav('Bettelstein','hof.php?op=beggar&subop='.$subop);
addnav('Bewaffnung','hof.php?op=weapon&subop='.$subop);
addnav('Bierkönige','hof.php?op=beer&subop='.$subop);
addnav('Donationspoints','hof.php?op=dps&subop='.$subop);
addnav('Edelsteine', 'hof.php?op=gems&subop='.$subop);
addnav('Edle Spender', 'hof.php?op=donation&subop='.$subop);
addnav('Feiglinge', 'hof.php?op=coward&subop='.$subop,false,false,false,false);
addnav('Geschwindigkeit', 'hof.php?op=days&subop='.$subop);
addnav('Goldener Joggingschuh','hof.php?op=runaway&subop='.$subop);
addnav('Goldverschwender','hof.php?op=wasterofgolds&subop='.$subop);
addnav('Häftlinge','hof.php?op=kerker&subop='.$subop);
addnav('j?Hasenjagd','hof.php?op=bunny&subop='.$subop);
addnav('Heizmeister','hof.php?op=spoil&subop='.$subop);
addnav('Heldentaten', 'hof.php?op=kills&subop='.$subop,false,false,false,false);
addnav('Knappen','hof.php?op=disciple&subop='.$subop);
addnav('Knochentürme','hof.php?op=bonestacks&subop='.$subop,false,false,false,false);
if ($access_control->su_lvl_check(1))
{
	addnav('Materielle Pfründe(SU)','hof.php?op=invent&subop='.$subop,false,false,false,false);
}
addnav('Puppenbesitzer','hof.php?op=doll&subop='.$subop);
if(CQuest::is_activ())addnav('Quest-Meister', 'hof.php?op=quests_solved&subop='.$subop);
if(CQuest::is_activ())addnav('Quest-Sternenjäger', 'hof.php?op=quests_sterne&subop='.$subop);
addnav('Quizkönige','hof.php?op=quiz&subop='.$subop);
if ($session['user']['alive']==0 || $access_control->su_lvl_check(1))
{
	addnav('Ramius\' Lieblinge','hof.php?op=grave&subop='.$subop);
}
addnav('Raufbolde', 'hof.php?op=raufen&subop='.$subop);
addnav('Reichtum', 'hof.php?op=money&subop='.$subop);
addnav('Rüstungen','hof.php?op=armor&subop='.$subop);
addnav('Schatzsucher','hof.php?op=treasure&subop='.$subop);
addnav('Schlagkraft','hof.php?op=punch&subop='.$subop);
addnav('Schönheit', 'hof.php?op=charm&subop='.$subop);
addnav('Stärke', 'hof.php?op=tough&subop='.$subop);
if ((bool)getsetting('symp_active','0') && $access_control->su_check(access_control::SU_RIGHT_VIEW_SYMPATHY_VOTES))
{
	addnav('Sympathie(SU)','hof.php?op=symp&subop='.$subop);
}
addnav('Tollpatsche', 'hof.php?op=resurrects&subop='.$subop);
addnav('Toreros', 'hof.php?op=toreros&subop='.$subop);
addnav('Uffs Maul!','hof.php?op=beatenup&subop='.$subop);
if ($access_control->su_lvl_check(1))
{
	addnav('Ungeliebte(SU)','hof.php?op=punchingball&subop='.$subop);
	addnav('Urlauber(SU)','hof.php?op=vacation&subop='.$subop);
}
addnav('Verschollene','hof.php?op=abwesend&subop='.$subop);
if ($access_control->su_lvl_check(1))
{
	addnav('Wanderer(SU)','hof.php?op=exchangequest&subop='.$subop);
}

if ($op!='profs' && $op!='job')
{
	addnav('Sortieren nach');
	addnav('Besten', 'hof.php?op='.$op.'&subop=most&page=1');
	addnav('Schlechtesten', 'hof.php?op='.$op.'&subop=least&page=1');
}

addnav('Sonstiges');

//Das fällt ja dann weg
//$str_hide_grotto_link = (!$arr_nav_vars['hide_superuser']?'hof.php?hide_superuser=1':'hof.php?show_superuser=1');
//if($access_control->su_lvl_check(1)) addnav('Grottenmitglieder '.($arr_nav_vars['hide_superuser']?'ein':'aus').'blenden',$str_hide_grotto_link);

addnav('Offizielle Ämter','hof.php?op=profs&subop='.$subop);
addnav('Bürgerwehr','hof.php?op=expe');
if($access_control->su_lvl_check(1))
{
	addnav('Berufe (SU)','hof.php?op=jobs&subop='.$subop);
}
addnav('Paare dieser Welt','hof.php?op=paare');
addnav('Turteltauben','hof.php?op=paare&type=999');
addnav('Rassenverteilung','hof.php?op=races');
if ((bool)getsetting('symp_active','0'))
{
	addnav('Vormonats-Sympathie','hof.php?op=symp_old');
}

function display_table($title, $sql, $none=false, $foot=false, $data_header=false, $tag=false)
{
	global $session, $countsql, $arr_pages, $playersperpage, $op, $subop,$max_age,$str_sql_no_su;

	if($countsql!==false)
	{
		if($countsql=='')
		{
			$countsql='SELECT count(*) AS c FROM accounts WHERE locked=0'.$max_age.$str_sql_no_su;
		}
		$arr_pages=page_nav('hof.php?op='.$op.'&subop='.$subop, $countsql, $playersperpage);
		$limit=(empty($_POST['search'])?$arr_pages['limit']:'0,'.$arr_pages['count']);
		$sql.=$limit;
	}

	output('`c`b`I`n'.$title.'`0`b `7(Seite '.$arr_pages['page'].': '.($arr_pages['from']+1).'-'.$arr_pages['to'].')`0`c`n');
	$str_output='<table cellspacing="1" cellpadding="2" align="center" bgcolor="#999999"><tr class="trhead">
	<th>Rang</th><th>Name</th>';
	if ($data_header !== false)
	{
		for ($i = 0; $i < count($data_header); $i++)
		{
			$str_output.='<th>'.$data_header[$i].'</th>';
		}
	}
	$str_output.='</tr>';
	if (!is_array($sql))
	{
		$result = db_query($sql);
	}
	$count = (is_array($sql) ? sizeof($sql) : db_num_rows($result));

	if ($count == 0)
	{
		$size = ($data_header === false) ? 2 : 2+count($data_header);
		if ($none === false)
		{
			$none = 'Keine Spieler gefunden';
		}
		$str_output.='<tr class="trlight"><td colspan="'. $size .'" align="center">`&' . $none .'`0</td></tr>';
	}
	else
	{
		if (empty($_POST['search']))
		{
			$suchname = '';
		}
		else
		{
			$suchname = mb_strtolower($_POST['search']);
		}
		for ($i=0; $i<$count; $i++)
		{
			if (!is_array($sql))
			{
				$row = db_fetch_assoc($result);
			}
			else
			{
				$row = $sql[$i];
			}
			if (!empty($suchname))
			{
				$name = strip_appoencode($row['name'],$int_mode=1,$bool_forbidden=true);
				$name = mb_strtolower($name);
				$pos = mb_strpos($name, $suchname);
				$same = false;
				if ($name == $suchname)
				{
					$same = true;
				}
			}
			if ($pos === false && !empty($suchname) && $same == false)
			{
				// nichts ausgeben
			}
			else
			{
				if (empty($_POST['search']) && $row['name']==$session['user']['name'])
				{
					//output("<tr class='hilight'>",true);
					$str_output.='<tr bgcolor="#005500">';
				}
				else
				{
					$str_output.='<tr class="' . ($i%2?'trlight':'trdark') . '">';
				}
				$str_output.='<td>'.($i+$arr_pages['from']+1).'.</td><td>`&'.CRPChat::menulink( $row).'`0</td>';
				if ($data_header !== false)
				{
					for ($j = 0; $j < count($data_header); $j++)
					{
						$id = 'data' . ($j+1);
						$val = $row[$id];
						if ($tag !== false)
						{
							$val = $val . " " . $tag[$j];
						}
						$str_output.="<td align='right'>".$val."`0</td>";
					}
				}
				$str_output.="</tr>";
			}
		}
	}
	output($str_output.'</table>', true);
	if ($foot != false)
	{
		output('`n`c'.$foot.'`c');
	}
} // end function display_table

output("<form action='hof.php?op=" . $_GET['op'] . "&amp;subop=" . $subop . "&amp;page=1' method='POST'>Helden suchen: ".JS::Autocomplete('search',true)."</form>");
addnav('','hof.php?op='.$_GET['op'].'&subop='.$subop.'&page=1');
$order = ($_GET['subop'] == 'least' ? 'ASC' : 'DESC');
$order_rev = ($_GET['subop'] == 'least' ? 'DESC' : 'ASC');
$sexsel = "IF(sex,'<img src=\"./images/female.gif\">&nbsp; &nbsp;','<img src=\"./images/male.gif\">&nbsp; &nbsp;')";
$loginsel = "IF(loggedin,'`@Online`0','`4Offline`0')";
$alivesel = "IF(alive,'`1Lebt`0','`4Tot`0')";


if ($_GET['op']=='kills') //Heldentaten
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,dragonkills AS data1,level AS data2,
	IF(dragonage,dragonage,"Unknown") AS data3, 
	IF(account_extra_info.bestdragonage,account_extra_info.bestdragonage,"Unknown") AS data4
	FROM accounts
	LEFT JOIN account_extra_info ON account_extra_info.acctid=accounts.acctid
	WHERE dragonkills>0 AND locked=0 '.$max_age.$str_sql_no_su.'
	ORDER BY dragonkills '.$order.',level '.$order.',experience '.$order.', accounts.acctid '.$order.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM accounts WHERE locked=0 AND dragonkills>0'.$max_age.$str_sql_no_su;

	$title = 'Helden mit den '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' Heldentaten:';
	$headers = array('Kills', 'Level', (getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage'), 'Bestzeit');
	$none = 'Es gibt noch keine Helden in diesem Land';
	display_table($title, $sql, $none, false, $headers, false);
}

else if ($_GET['op']=='money')
{
	//Böser Überlauf, wenn man goldinbank + gold rechnet, aber goldinbank < 0 ist... bitte nicht hauen *g*
	$sql = "
		SELECT
			`acctid`,
			`login`,
			`name`,
			IF(
				`goldinbank` + `gold` >= 0 AND `goldinbank` + `gold` < 999999999,
				ROUND((((RAND()*10)-5)/100 + 1) * (`goldinbank`+`gold`)),
				ROUND((((RAND()*10)-5)/100 + 1) * `goldinbank`)
			) AS 'data1'
		FROM
			`accounts`
		WHERE
			`locked`	= '0' 
			" . $max_age . $str_sql_no_su . "
		ORDER BY
			`data1`			" . $order . ",
			`level`			" . $order . ",
			`experience`	" . $order . ",
			`acctid`		" . $order . "
		LIMIT
			" . $limit;

	$title = 'Die '.($_GET['subop'] == 'least'?'ärmsten':'reichsten').' Krieger in diesem Land:';
	$foot = '(Vermögen +/- 5%)';
	$headers = array('Geschätztes Vermögen');
	$tags = array('Gold');
	display_table($title, $sql, false, $foot, $headers, $tags);
}

else if ($_GET['op'] == 'gems')
{
	$sql = 'SELECT acctid,login,name
	FROM accounts
	WHERE locked=0
	'.$max_age.$str_sql_no_su.'
	ORDER BY gems+gemsinbank '.$order.', level '.$order.', experience '.$order.', acctid '.$order.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM accounts WHERE locked=0 AND gems>0';

	$title = 'Die Krieger mit den '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' Edelsteinen:';
	display_table($title, $sql);
}

else if ($_GET['op'] == 'donation')
{
    $sql='SELECT accounts.acctid,accounts.name,aei.donations
	FROM account_extra_info aei
	LEFT JOIN accounts ON aei.acctid=accounts.acctid
	WHERE donations>0
	'.$str_sql_no_su.'
	ORDER BY donations '.$order.', accounts.acctid '.$order.'
	LIMIT '.$limit;
    $countsql='SELECT count(*) AS c FROM account_extra_info WHERE donations>0';

    $title = 'Die spendablen Spieler, '.($_GET['subop'] == 'least'?'aufsteigend':'absteigend').' sortiert:';
    $foot = 'Diesen Spielern gilt ein ganz besonderer Dank vom '.getsetting('teamname','Drachenserver-Team');
    display_table($title, $sql, false, $foot);
}

else if ($_GET['op'] == 'quests_solved')
{
    $sql='SELECT accounts.acctid,accounts.name,aei.quests_solved AS data1,aei.quests_time AS data2
	FROM account_extra_info aei
	LEFT JOIN accounts ON aei.acctid=accounts.acctid
	WHERE aei.quests_solved>0
    '.$str_sql_no_su.'
	ORDER BY quests_solved '.$order.',quests_time '.( ($order=='ASC') ? 'DESC' : 'ASC' ).', accounts.acctid '.$order.'
	LIMIT '.$limit;
    $countsql='SELECT count(*) AS c FROM account_extra_info WHERE quests_solved>0';

    $title = 'Die erfolgreichten Quest-Jäger, '.($_GET['subop'] == 'least'?'aufsteigend':'absteigend').' sortiert:';
    $headers = array('Quests', 'Zeit in '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage'));
    $none = 'Es gibt noch keine Quest-Jäger in diesem Land';
    display_table($title, $sql, $none, false, $headers, false);
}

else if ($_GET['op'] == 'quests_sterne')
{
    $sql='SELECT accounts.acctid,accounts.name,aei.quests_sterne AS data1 ,aei.quests_solved AS data2
	FROM account_extra_info aei
	LEFT JOIN accounts ON aei.acctid=accounts.acctid
	WHERE aei.quests_sterne>0
	'.$str_sql_no_su.'
	ORDER BY quests_sterne '.$order.',quests_time '.( ($order=='ASC') ? 'DESC' : 'ASC' ).', accounts.acctid '.$order.'
	LIMIT '.$limit;
    $countsql='SELECT count(*) AS c FROM account_extra_info WHERE quests_sterne>0';

    $title = 'Die mutigsten Quest- und Sterne-Jäger, '.($_GET['subop'] == 'least'?'aufsteigend':'absteigend').' sortiert:';
    $headers = array('Sterne', 'Quests');
    $none = 'Es gibt noch keine Quest-Jäger in diesem Land';
    display_table($title, $sql, $none, false, $headers, false);
}

else if ($_GET['op'] == 'birth')
{
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE birthday!=""';
	$arr_pages=page_nav('hof.php?op='.$op.'&subop='.$subop, $countsql, $playersperpage);
	$limit=(empty($_POST['search'])?$arr_pages['limit']:'0,'.$arr_pages['count']);
	$countsql=false;

	$sql = 'SELECT accounts.acctid,accounts.login,name,birthday AS data1,DATEDIFF(NOW(),laston) AS data2, dragonkills AS data3
	FROM accounts
	INNER JOIN account_extra_info USING(acctid)
	WHERE birthday!="" '.$str_sql_no_su.' ORDER BY data1 '.$order_rev.', data3 DESC LIMIT '.$limit;
	$res = db_query($sql);

	$arr = array();

	while ($p = db_fetch_assoc($res))
	{
		$p['data1'] = getgamedate($p['data1']);

		if ($p['data2'] == 0)
		{
			$p['data2'] = 'Heute';
		}
		else if ($p['data2'] == 1)
		{
			$p['data2'] = 'Gestern';
		}
		else
		{
			$p['data2'] .= ' Tage';
		}
		$arr[] = $p;
	}

	$title = 'Diese Krieger sind am '.($_GET['subop'] == 'least'?'kürzesten':'längsten').' in der Stadt:';
	$headers = array('Ankunft','Zuletzt gesehen','Heldentaten');
	$tags = array('','');
	display_table($title, $arr, false, '', $headers, $tags);
}

else if ($_GET['op'] == 'treasure')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,treasure_f AS data1
	FROM account_extra_info
	LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid
	WHERE treasure_f>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', accounts.dragonkills ASC
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE treasure_f>0';

	$title = 'Diese Krieger haben die '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' Schätze und Drachenreliquien gefunden:';
	$headers = array('Schätze');
	$tags = array('');
	display_table($title, $sql, false, '', $headers, $tags);
}

else if ($_GET['op'] == 'exchangequest')
{
	$sql = 'SELECT acctid,login,name,exchangequest AS data1
	FROM accounts
	WHERE exchangequest>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', acctid '.$order.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM accounts WHERE exchangequest>0';

	$title = 'Die '.($_GET['subop'] == 'least'?'neuesten':'fortgeschrittensten').' Sucher nach der Brosche:';
	$headers = array('Level');
	$tags = array('');
	display_table($title, $sql, false, '', $headers, $tags);
}

else if ($_GET['op'] == 'kerker')
{
	$sql = 'SELECT acctid,login,name,daysinjail AS data1
	FROM accounts
	WHERE daysinjail>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', dragonkills '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM accounts WHERE locked=0 AND daysinjail>0';

	$title = 'Diese Krieger haben die '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage').' im Kerker gesessen:';
	$foot = 'Es gelten nur die '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage').' die tatsächlich abgesessen wurden, nicht die Strafen';
	$headers = array('In Haft');
	$tags = array((getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage'));
	display_table($title, $sql, false, $foot, $headers, $tags);
}

else if ($_GET['op'] == 'raufen')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,tussle_rounds AS data1
	FROM account_extra_info
	LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid
	WHERE tussle_rounds>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', accounts.dragonkills '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE tussle_rounds>0';

	$title = 'Diese Raufbolde haben die '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' Kämpfer in Folge bezwungen:';
	$headers = array('Gegner');
	display_table($title, $sql, false, $foot, $headers, $tags);
}

else if ($_GET['op'] == 'beer')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,beerspent AS data1
	FROM account_extra_info
	LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid
	WHERE beerspent>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', accounts.dragonkills '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE beerspent>0';

	$title = 'Diese Krieger haben das '.($_GET['subop'] == 'least'?'wenigste':'meiste').' Freibier spendiert:';
	$foot = 'Auf ihr Wohl! Prost!';
	$headers = array('Freibier');
	$tags = array('Humpen');
	display_table($title, $sql, false, $foot, $headers, $tags);
}

else if ($_GET['op'] == 'dps')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,donation
	FROM account_extra_info
	LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid
	WHERE donation>0
	'.$str_sql_no_su.'
	ORDER BY donation '.$order.', accounts.dragonkills '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM accounts WHERE donation>0';

	$title = 'Diese Krieger haben die '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' Donationpoints gesammelt:';
	$headers = array('Donationpoints');
	display_table($title, $sql);
}



else if ($_GET['op'] == 'beggar')
{
	$sql = 'SELECT accounts.acctid, accounts.login, accounts.name, IF( beggar >0, CONCAT( "`$", beggar * -1 ) , CONCAT( "`@", beggar * -1 ) ) AS data1
	FROM account_extra_info
	LEFT JOIN accounts ON accounts.acctid = account_extra_info.acctid
	WHERE beggar <>0
	'.$str_sql_no_su.'
	ORDER BY beggar '.$order.' LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE beggar<>0';

	$title = 'Die '.($_GET['subop'] == 'least'?'großzügigsten Spender':'gierigsten Bettler').' '.getsetting('townname','Atrahor').'s:';
	$foot = '(Hier erscheint was insgesamt vom Bettelstein genommen wurde.`n
	Rote Zahlen bedeuten, dass mehr entnommen als gespendet wurde.)';
	$headers = array('Stand');
	$tags = array('Gold`0');
	display_table($title, $sql, false, $foot, $headers, $tags);
}

else if ($_GET['op'] == 'symp')
{
	//dirty workaround wegen Sympathievergabe-Link: accounts.acctid nicht abrufen
	$sql = 'SELECT accounts.name,aei.sympathy AS data1
	FROM account_extra_info aei
	LEFT JOIN accounts ON accounts.acctid=aei.acctid
	WHERE sympathy>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', accounts.dragonkills '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE sympathy>0';

	$title = 'Das sind die Helden mit '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' Sympathiepunkten:';
	$headers = array('Sympathie');
	$tags = array('Punkte');
	display_table($title, $sql, false, false, $headers, $tags);
}

else if ($_GET['op'] == 'quiz')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,aei.quizpoints AS data1
	FROM account_extra_info aei
	LEFT JOIN accounts ON accounts.acctid=aei.acctid
	WHERE quizpoints>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', accounts.dragonkills '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE quizpoints>0';

	$title = 'Die Helden mit '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' Punkten im Quiz:';
	$headers = array('Quizpunkte');
	$tags = array('Punkte');
	display_table($title, $sql, false, false, $headers, $tags);
}

else if ($_GET['op'] == 'spoil')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,disciples_spoiled AS data1
	FROM account_extra_info aei
	LEFT JOIN accounts ON accounts.acctid=aei.acctid
	WHERE disciples_spoiled>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', accounts.dragonkills '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE disciples_spoiled>0';

	$title = 'Diese Krieger haben bislang die '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' Knappen verheizt:';
	$foot = 'Jünglinge, nehmt Euch in Acht!';
	$headers = array('Verloren');
	display_table($title, $sql, false, $foot, $headers, false);
}

else if ($op == 'bunny')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,bunnies AS data1,bunnyhunt AS data2
	FROM account_extra_info aei
	LEFT JOIN accounts  ON accounts.acctid=aei.acctid
	WHERE bunnies>0 
	'.$str_sql_no_su.'
	ORDER BY data2 '.$order.', data1 '.$order.', accounts.dragonkills '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE bunnies>0';

	$title = 'Diese Krieger haben die '.($subop == 'least'?'wenigsten':'meisten').' Häschen eingefangen:';
	$foot = 'Die Jagd geht weiter!';
	$headers = array('Gefangene Hasen','Komplett gelöst');
	$tags = array('','Spiele');
	display_table($title, $sql, false, $foot, $headers, $tags);
}


else if ($_GET['op'] == 'beatenup')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,timesbeaten AS data1
	FROM account_extra_info
	LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid
	WHERE timesbeaten>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE timesbeaten>0';

	$title = 'Diese Helden haben bislang die '.($_GET['subop'] == 'least'?'wenigste':'meiste').' Prügel kassiert:';
	$foot = '(Es werden nur erfolgreiche Prügelattacken gezählt, bei denen die Angreifer nicht vertrieben wurden.)';
	$headers = array('Prügel');
	$tags = array('x vermöbelt');
	display_table($title, $sql, false, $foot, $headers, $tags);
}

else if ($_GET['op'] == 'runaway')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,runaway AS data1
	FROM account_extra_info
	LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid
	WHERE runaway>0
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE runaway>0';

	$title = 'Diese Recken sind bislang am '.($_GET['subop'] == 'least'?'wenigsten':'häufigsten').' aus dem Kampf geflüchtet:';
	$foot = '(Es wird nur jeder erfolgreiche Fluchtversuch gewertet.)';
	$headers = array('davongelaufen');
	$tags = array('x geflüchtet');
	display_table($title, $sql, false, $foot, $headers, $tags);
}

else if ($_GET['op']=='weapon')
{
	$sql = 'SELECT acctid,login,name,weapon AS data1,weapondmg AS data2
	FROM accounts
	WHERE locked=0
	'.$str_sql_no_su.'
	ORDER BY weapondmg '.$order.', dragonkills '.$order.', attack '.$order.'
	LIMIT '.$limit;

	$title = 'Die '.($_GET['subop'] == 'least'?'schlichtesten':'mächtigsten').' Waffen in diesem Land:';
	$headers = array('Waffe','Waffenstärke');
	display_table($title, $sql, false, false, $headers, false);
}

else if ($_GET['op']=='armor')
{
	$sql = 'SELECT acctid,login,name,armor AS data1,armordef AS data2
	FROM accounts
	WHERE locked=0
	'.$str_sql_no_su.'
	ORDER BY armordef '.$order.', dragonkills '.$order.', defence '.$order.'
	LIMIT '.$limit;

	$title = 'Die '.($_GET['subop'] == 'least'?'schlichtesten':'stärksten').' Rüstungen in diesem Land:';
	$headers = array('Rüstung','Schutzklasse');
	display_table($title, $sql, false, false, $headers, false);
}

else if ($_GET['op']=='disciple')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,disciples.name AS data1,disciples.level AS data2
	FROM disciples
	LEFT JOIN accounts ON accounts.acctid=disciples.master
	WHERE state>0
	'.$str_sql_no_su.'
	ORDER BY disciples.level '.$order.', accounts.dragonkills '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM disciples WHERE state>0';

	$title = 'Diese Krieger haben die '.($_GET['subop'] == 'least'?'unerfahrensten':'besten').' Knappen:';
	$headers = array('Knappe','Level');
	display_table($title, $sql, false, false, $headers, false);
}

else if ($_GET['op']=='charm')
{

	if($_GET['type']=='dual' || !isset($_GET['type']))
	{
	output('<table><tr><td>');
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,r.colname AS data1
	FROM accounts
	LEFT JOIN races r ON r.id=race
	WHERE locked=0 AND sex=0
	'.$max_age.$str_sql_no_su.'
	ORDER BY charm '.$order.', level '.$order.', experience '.$order.', acctid '.$order.'
	LIMIT '.$limit;

	$title = 'Die '.($_GET['subop'] == 'least'?'hässlichsten':'schönsten').' Krieger in diesem Land:';
	$headers = array('Rasse');
	
	$op .= "&type=dual";
	$countsql = 'SELECT count(*) AS c FROM accounts WHERE locked=0 AND sex=0 '.$max_age.$str_sql_no_su;
	display_table($title, $sql, false, false, $headers, false);
	
	output('</td><td><div style="width:30px;"/></td><td>');
	
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name, r.colname AS data1
	FROM accounts
	LEFT JOIN races r ON r.id=race
	WHERE locked=0 AND sex=1
	'.$max_age.$str_sql_no_su.'
	ORDER BY charm '.$order.', level '.$order.', experience '.$order.', acctid '.$order.'
	LIMIT '.$limit;

	$title = 'Die '.($_GET['subop'] == 'least'?'hässlichsten':'schönsten').' Kriegerinnen in diesem Land:';
	$headers = array('Rasse');
	
	$countsql = 'SELECT count(*) AS c FROM accounts WHERE locked=0 AND sex=1 '.$max_age.$str_sql_no_su;
	display_table($title, $sql, false, false, $headers, false);
	
	output('</td></tr></table>');
	output(create_lnk('Nicht getrennt anzeigen','hof.php?op=charm&type=single'));
	}
	else if($_GET['type']=='single')
	{
	output('<table><tr><td>');
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,r.colname AS data1
	FROM accounts
	LEFT JOIN races r ON r.id=race
	WHERE locked=0
	'.$max_age.$str_sql_no_su.'
	ORDER BY charm '.$order.', level '.$order.', experience '.$order.', acctid '.$order.'
	LIMIT '.$limit;

	$title = 'Die '.($_GET['subop'] == 'least'?'hässlichsten':'schönsten').' Krieger und Kriegerinnen in diesem Land:';
	$headers = array('Rasse');
	
	$op .= "&type=single";
	display_table($title, $sql, false, false, $headers, false);
	
	output('</td></tr></table>');
	output(create_lnk('Getrennt anzeigen','hof.php?op=charm&type=dual'));
	}
	
}

else if ($_GET['op']=='tough')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,level AS data2 ,r.colname as data1
	FROM accounts
	LEFT JOIN races r ON r.id=race
	WHERE locked=0 
	'.$max_age.$str_sql_no_su.'
	ORDER BY ((maxhitpoints/30)+(attack*1.5)+(defence)) '.$order.', level '.$order.', experience '.$order.', acctid '.$order.'
	LIMIT '.$limit;

	$title = 'Die '.($_GET['subop'] == 'least'?'schwächsten':'stärksten').' Krieger in diesem Land:';
	$headers = array('Rasse', 'Level');
	display_table($title, $sql, false, false, $headers, false);
}

else if ($_GET['op']=='punch')
{
	// Godmode-Leute rausnehmen
	$str_sql_no_su_punch = ' AND !('.$access_control->su_check_other(access_control::SU_RIGHT_GODMODE).') ';

	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,punch AS data1,r.colname AS data2
	FROM accounts
	LEFT JOIN races r ON r.id=race
	WHERE locked=0
	'.$max_age.$str_sql_no_su_punch.'
	ORDER BY data1 '.$order.', level '.$order.', experience '.$order.', acctid '.$order.'
	LIMIT '.$limit;

	$title = 'Die '.($_GET['subop'] == 'least'?'armseligsten':'härtesten').' Schläge aller Zeiten:';
	$headers = array('Punkte','Rasse');
	display_table($title, $sql, false, false, $headers, false);
}

else if ($_GET['op']=='resurrects')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,level AS data1,resurrections AS data2
	FROM accounts
	WHERE locked=0 
	'.$max_age.$str_sql_no_su.'
	ORDER BY resurrections '.$order.', level '.$order_rev.', dragonkills '.$order.', acctid '.$order_rev.'
	LIMIT '.$limit;

	$title = 'Die '.($_GET['subop'] == 'least'?'geschicktesten':'tollpatschigsten').' Krieger in diesem Land:';
	$headers = array('Level','Tode seit DK');
	display_table($title, $sql, false, false, $headers, false);
}

else if ($_GET['op']=='grave')
{
	$countsql='SELECT count(*) AS c FROM accounts WHERE locked=0'.$max_age.$str_sql_no_su;
	$arr_pages=page_nav('hof.php?op='.$op.'&subop='.$subop, $countsql, $playersperpage);
	$limit=(empty($_POST['search'])?$arr_pages['limit']:'0,'.$arr_pages['count']);
	$countsql=false;

	$sql = 'SELECT acctid,name,loggedin,deathpower AS data1,location,'.$loginsel.' AS data2,laston,'.$alivesel.' AS data3,activated
	FROM accounts
	WHERE locked=0
	'.$max_age.$str_sql_no_su.'
	ORDER BY deathpower '.$order.', level '.$order.', experience '.$order.', acctid '.$order.'
	LIMIT '.$limit;
	$res = db_query($sql);

	$arr = array();
	while ($p = db_fetch_assoc($res))
	{
		if ($p['location']==USER_LOC_FIELDS)
		{
			$p['data2']=($p['loggedin']?'`#Online`0':'`3Die Felder`0');
		}
		elseif ($p['location']==USER_LOC_INN)
		{
			$p['data2']='`3Zimmer in Kneipe`0';
		}
		elseif ($p['location']==USER_LOC_HOUSE)
		{
			$p['data2']='`3Im Haus`0';
		}
		elseif ($p['location']==USER_LOC_PRISON)
		{
			$p['data2']='`3Im Kerker`0';
		}
		else
		{
			$p['data2']=('`3Weiß der Geier`0');
		}
		$arr[] = $p;
	}

	$title = 'Ramius\' '.($_GET['subop'] == 'least'?'faulste':'fleißigste').' Krieger:';
	$headers = array('Gefallen','Online','Status');
	display_table($title, $arr, false, false, $headers, false);
}

else if ($_GET['op']=='days')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,bestdragonage AS data1, accounts.dragonkills
	FROM account_extra_info
	LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid
	WHERE bestdragonage>0
		AND dragonkills>0
		'.$str_sql_no_su.'
	ORDER BY data1 '.$order_rev.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE bestdragonage>0';

	$title = 'Helden mit den '.($_GET['subop'] == 'least'?'langsamsten':'schnellsten').' Heldentaten:';
	$headers = array('Bestzeit '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage'));
	$none = 'Es gibt noch keine Helden in diesem Land';
	display_table($title, $sql, $none, false, $headers, false);
}

else if ($_GET['op']=='coward') //höchstes Alter seit DK
{
	$sql = 'SELECT acctid,login,name,age AS data1,dragonkills
	FROM accounts
	WHERE dragonkills>0 AND locked=0 AND age>20
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM accounts WHERE locked=0 AND dragonkills>0 AND age>20';

	$title = 'Feiglinge, die sich '.($_GET['subop'] == 'least'?'weniger':'am längsten').' vor einer Heldentat drücken:';
	$headers = array('Dauer '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage'));
	$none = 'Es gibt noch keine Helden in diesem Land';
	display_table($title, $sql, $none, false, $headers, false);
}

else if ($_GET['op']=='doll')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,hvalue AS data1, items.name AS data2
	FROM items
	LEFT JOIN accounts ON accounts.acctid=items.owner
	WHERE items.tpl_id="kpuppe"
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', items.id '.$order.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM items WHERE tpl_id="kpuppe"';

	$title = 'Diese Sammler besitzen die '.($_GET['subop'] == 'least'?'wertlosesten':'wertvollsten').' Puppen:';
	$headers = array('Wert der Puppe','Puppenname');
	$none = 'Hier besitzt niemand eine Puppe.';
	$tags = array('DKs','');
	display_table($title, $sql, $none, false, $headers, $tags);
}

else if ($_GET['op']=='battlepoints')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,battlepoints AS data1,dragonkills AS data2
	FROM accounts
	WHERE locked=0 
	AND battlepoints>0'.$max_age.$str_sql_no_su.'
	ORDER BY battlepoints '.$order.', dragonkills '.$order_rev.', acctid '.$order.'
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM accounts WHERE battlepoints>0 '.$str_sql_no_su;

	$title = 'Die '.($_GET['subop'] == 'least'?'schlechtesten':'besten').' Arenakämpfer in diesem Land:';
	$headers = array('Punkte','Heldentaten');
	display_table($title, $sql, false, false, $headers, false);
}

else if ($_GET['op'] == 'toreros')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name,accounts.dragonkills,bullfightwins AS data1 
	FROM account_extra_info 
	LEFT JOIN accounts ON accounts.acctid=account_extra_info.acctid 
	WHERE bullfightwins>0 
	'.$str_sql_no_su.'
	ORDER BY data1 '.$order.', accounts.dragonkills '.$order_rev.', accounts.acctid '.$order.' 
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE bullfightwins>0';

	$title = 'Diese Helden haben bislang die '.($_GET['subop'] == 'least'?'wenigsten':'meisten').' Stiere besiegt:';
	$headers = array('Stiere');
	$tags = array('Siege');
	display_table($title, $sql, false, $foot, $headers, $tags);
}

else if ($_GET['op']=='abwesend')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name, DATEDIFF(NOW(),laston) AS data1,dragonkills AS data2 
	FROM accounts 
	WHERE locked=0 
		AND DATEDIFF(NOW(),laston) > 3 
		'.(!access_control::is_superuser() ? 'AND DATEDIFF(NOW(),laston) <= '.getsetting('expireoldacct',45) : '').'
		AND dragonkills>0 
		AND location !='.USER_LOC_VACATION.' 
	ORDER BY data1 '.$order.', dragonkills '.$order.', acctid '.$order.' 
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM accounts WHERE locked=0 AND dragonkills>0 AND DATEDIFF(NOW(),laston) > 3 AND location!='.USER_LOC_VACATION;

	$title = 'Die '.($_GET['subop'] == 'least'?'am kürzesten':'am längsten').' Verschollenen in diesem Land:';
	$headers = array('Tage','Heldentaten');
	display_table($title, $sql, false, false, $headers, false);
}

else if ($_GET['op']=='vacation')
{
	$sql = 'SELECT accounts.acctid,accounts.login,accounts.name, DATEDIFF(NOW(),laston) AS data1,dragonkills AS data2 
	FROM accounts 
	WHERE locked=0 
		AND DATEDIFF(NOW(),laston) > 3 
		AND dragonkills>0 AND location ='.USER_LOC_VACATION.' 
	ORDER BY data1 '.$order.', dragonkills '.$order.', acctid '.$order.' 
	LIMIT '.$limit;
	$countsql='SELECT count(*) AS c FROM accounts WHERE locked=0 AND DATEDIFF(NOW(),laston)>3 AND dragonkills>0 AND location='.USER_LOC_VACATION;

	$title = 'Die '.($_GET['subop'] == 'least'?'am kürzesten':'am längsten').' Verreisten in diesem Land:';
	$headers = array('Tage','Heldentaten');
	display_table($title, $sql, false, false, $headers, false);
}

else if ($_GET['op']=='invent')
{
	$sql='SELECT accounts.name, accounts.login, accounts.acctid, count( owner ) AS data1
	FROM items i
	LEFT JOIN accounts ON owner = acctid
	WHERE i.owner <1234567
		AND i.owner>0
		'.($_GET['subop']=='least'?'AND deposit1=0 AND deposit2=0 ':'').'
	GROUP BY owner
	ORDER BY data1 DESC, i.owner DESC
	LIMIT '.$limit;

	$adverb = ' und Lagerhallen';
	if ($_GET['subop'] == 'least')
	{
		$adverb = '';
	}
	$title = 'Die dicksten Beutel'.$adverb.':';
	$headers = array('Inventar');
	$foot = '`c`@Dirty trick:`0`nSortierung nach "`bBesten`b" zählt `balle Dinge`b,`nSortierung nach "`bSchlechtesten`b" zählt nur `bNichteintelagertes`b.';
	$tags = array('Dinge');
	display_table($title, $sql, false, $foot, $headers, $tags);
}

else if ($_GET['op']=='punchingball') //wer wurde wie oft als Übungsgerät oder Teddy gewählt?
{
	$sql='SELECT count( * ) AS data2,
		IF(accounts.acctid, accounts.name, v.name) AS name, accounts.login, accounts.acctid,
		IF(accounts.acctid, accounts.dragonkills, 999) AS dk,
		IF(accounts.acctid, "&nbsp;","&dagger;") AS data1
	FROM items i
	LEFT JOIN accounts ON accounts.acctid = i.value1
	LEFT JOIN valhalla v ON v.acctid = i.value1
	WHERE value1 >0
	'.$str_sql_no_su.'
	AND tpl_id '.($_GET['subop']=='least'?'="lovedoll"':'IN ( "zielsch", "sandsack", "strpuppe" )').'
	GROUP BY value1
	ORDER BY data2 DESC, dk ASC
	LIMIT '.$limit;
//	AND accounts.acctid>0

	$title = 'Diese Krieger zieren die meisten '.($_GET['subop'] == 'least'?'Teddys':'Übungsgeräte').':';
	$headers = array('','Anzahl');
	$tags = array('',($_GET['subop'] == 'least'?'Teddys':'Zielstrohsäcke'));
	display_table($title, $sql, false, $foot, $headers, $tags);
}

elseif ($_GET['op']=='bonestacks')
{
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE maxbonestack>0';
	$sql = 'SELECT name, maxbonestack AS data1, a.acctid, a.login
	FROM account_extra_info aei
	LEFT JOIN accounts a ON a.acctid=aei.acctid
	WHERE maxbonestack>0
	ORDER BY maxbonestack '.$order.', a.dragonkills '.$order_rev.', aei.acctid '.$order.'
	LIMIT '.$limit;
	$adverb = ($_GET['subop'] == 'least'?'amateurhaftesten':'größten');
	$title = 'Die '.$adverb.' Knochentürme';
	$headers = array('Größter Turm');
	$tags = array('Knochen');
	$none = 'Es gibt noch keine "Hochstapler" in diesem Land';
	display_table($title, $sql, $none, false, $headers, $tags);
}

else if ($_GET['op']=='profs')
{
	$output=null;
	$arr_prof_list = array();

	$str_judges = '<tr class="trhead"><td>`bDie ehrenwerten Richter:`b</td></tr>';
	$str_priests = '<tr class="trhead"><td>`bDie würdigen Priester:`b</td></tr>';
	$str_guards = '<tr class="trhead"><td>`bDie tapferen Wachen:`b</td></tr>';
	$str_witches = '<tr class="trhead"><td>`bDie weisen Hexen und Hexer:`b</td></tr>';
	$str_txt = '';

	$sql = 'SELECT 	accounts.login,
					accounts.acctid,
					accounts.imprisoned,
					accounts.activated,
					accounts.expedition,
					accounts.sex,
					accounts.name,
					accounts.profession,
					aei.html_locked
					FROM accounts
					INNER JOIN account_extra_info aei ON accounts.acctid=aei.acctid
					WHERE profession > 0
					ORDER BY profession DESC, dragonkills DESC, acctid ASC';
	$res = db_query($sql);
	while($a = db_fetch_assoc($res)) {
		// Wenn Amt öffentlich angezeigt werden soll
		if($profs[$a['profession']][2]) {
			$str_txt = '<tr class="trlight"><td>'.$profs[$a['profession']][3].$profs[$a['profession']][$a['sex']].' `0'.CRPChat::menulink( $a ).'`0</td></tr>';

			switch($a['profession']) {

				case PROF_JUDGE:
				case PROF_JUDGE_HEAD:
					$str_judges .= $str_txt;
					break;

				case PROF_GUARD:
				case PROF_GUARD_HEAD:
					$str_guards .= $str_txt;
					break;

				case PROF_PRIEST:
				case PROF_PRIEST_HEAD:
					$str_priests .= $str_txt;
					break;

				case PROF_WITCH:
				case PROF_WITCH_HEAD:
					$str_witches .= $str_txt;
					break;
			}
		}
	}
	$out .= '`c`b`&Helden dieser Stadt, die ein offizielles Amt innehaben:`c`b`n';
	$out .= '`c<table cellspacing="2" cellpadding="2" align="center">';
	$out .= $str_judges.$str_priests.$str_witches.$str_guards;
	$out .= '</table>`c';
	output($out,true);
}

else if ($_GET['op']=='expe')
{
	
	$str_output .= '`c`b`&Die tapferen Mitglieder der Bürgerwehr:`c`b`n';
	$str_output .= '`c<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999">';
	$str_output .= '<tr class="trhead">
									<th><img src=\'./images/female.gif\'>/<img src=\'./images/male.gif\'></th>
									<th>Rang</th>
									<th>Name</th>
									</tr>';
	
	$sql = 'SELECT acctid,
								 name,
								 login,
								 sex,
								 ddl_rank
					FROM accounts
					WHERE expedition!=0
					ORDER BY ddl_rank DESC, 
									 dragonkills DESC, 
									 level DESC
					LIMIT 100';
					
	$res = db_query($sql);
	$max = db_num_rows($res);				
  for ($i=0; $i<$max; $i++)
  {
  	$row = db_fetch_assoc($res);
		$str_output .= '<tr class="'.($i%2?'trdark':'trlight').'">
								 <td align="center">'.($row['sex']?"<img src=\"./images/female.gif\">":"<img src=\"./images/male.gif\">").'</td>
								 <td align="center">'.get_ddl_rank($row['ddl_rank']).'`0</td>
								 <td>'.CRPChat::menulink( $row).'`0</td></tr>';
	}
	$str_output .= '</table>`c';
	
	output($str_output);
}

else if ($_GET['op']=='jobs')
{
	$output=null;
	$arr_prof_list = array();

	$str_farmer = '<tr class="trhead"><td>`bBauern:`b</td></tr>';
	$str_smith = '<tr class="trhead"><td>`bSchmiede:`b</td></tr>';
	$str_alchemist = '<tr class="trhead"><td>`bAlchemisten:`b</td></tr>';
	$str_miner = '<tr class="trhead"><td>`bMinenarbeiter:`b</td></tr>';
	$str_banker = '<tr class="trhead"><td>`bBänker:`b</td></tr>';
	$str_grocer = '<tr class="trhead"><td>`bKrämer:`b</td></tr>';
	$str_smelter = '<tr class="trhead"><td>`bSchmelzer:`b</td></tr>';
	$str_txt = '';

	$sql = 'SELECT 	accounts.login,
					accounts.acctid,
					accounts.imprisoned,
					accounts.activated,
					accounts.expedition,
					accounts.sex,
					accounts.name,
					aei.html_locked,
					aei.job AS job
					FROM accounts
					INNER JOIN account_extra_info aei ON accounts.acctid=aei.acctid
					WHERE job > 0 
					'.$str_sql_no_su.'
					ORDER BY job DESC, dragonkills DESC, acctid ASC';
	$res = db_query($sql);
	
	while($a = db_fetch_assoc($res)) {
		if($jobs[$a['job']][2]) {
			$str_txt = '<tr class="trlight"><td>'.$jobs[$a['job']][3].$jobs[$a['job']][$a['sex']].' `0'.CRPChat::menulink( $a).'`0</td></tr>';

			switch($a['job']) {

				case JOB_FARMER:
					$str_farmer .= $str_txt;
					break;

				case JOB_SMITH:
					$str_smith .= $str_txt;
					break;

				case JOB_ALCHEMIST:
					$str_alchemist .= $str_txt;
					break;

				case JOB_MINER:
					$str_miner .= $str_txt;
					break;

                case JOB_BANKER:
					$str_banker .= $str_txt;
					break;
					
	            case JOB_GROCER:
					$str_grocer .= $str_txt;
					break;
					
	            case JOB_SMELTER:
					$str_smelter .= $str_txt;
					break;
			}
		}
	}
	$out .= '`c`b`&Helden dieser Stadt, die einer ehrlichen Arbeit nachgehen:`c`b`n';
	$out .= '`c<table cellspacing="2" cellpadding="2" align="center">';
	$out .= $str_farmer.$str_smith.$str_alchemist.$str_miner.$str_banker.$str_grocer.$str_smelter;
	$out .= '</table>`c';
	output($out,true);
}

else if ($_GET['op']=='paare') //Paaresliste
{
	$output=null;
	$charisma=($_GET['type']=='999'?'999':'4294967295');
	if($charisma=='999')
	{
		$str_output.='Noch weiter abseits in einem Nebenraum der Ruhmeshalle findest du eine Liste mit Helden ganz anderer Art. Diese Turteltauben wollen einander heiraten. Irgendwann...
		`n`n`c`b`&Verlobte dieser Welt`b`c';
	}
	else
	{
		$str_output.='In einem Nebenraum der Ruhmeshalle findest du eine Liste mit Helden ganz anderer Art. Diese Helden meistern gemeinsam die Gefahren der Ehe!
		`n`n`c`b`&Heldenpaare dieser Welt`b`c';
	}
	$str_output.="`n<table cellspacing=0 cellpadding=2 align='center'><tr class='trhead'><th><img src=\"./images/female.gif\">`b Name`b</th><th></th><th><img src=\"./images/male.gif\">`b Name`b</th></tr>";
	$rank=1;

	$arr_married=db_get_all('SELECT acctid,name,login,sex,marriedto,charisma FROM accounts WHERE charisma='.$charisma.' '.$str_sql_no_su.' ORDER BY charisma DESC, sex ASC, acctid DESC','acctid');
	if(count($arr_married)==0)
	{
		$str_output.="<tr><td colspan=4 align='center'>`&`iIn diesem Land gibt es keine Paare.`i`0</td></tr>";
	}
	foreach ($arr_married as $key => $val)
	{
		if($val['sex']==0 && $val['acctid']==$arr_married[$arr_married[$key]['marriedto']]['marriedto'])
		{
			$trclass=($trclass=='trdark'?'trlight':'trdark');
			$str_output.='
			<tr class="'.$trclass.'"><td align="right">`&'.$arr_married[$arr_married[$key]['marriedto']]['name'].'`0</td><td>`)&nbsp;und&nbsp;`0 </td><td>`&'.$val['name'].'`0</td></tr>';
		}
	}
	output($str_output.'</table>');
}

else if ($_GET['op']=='races') //Rassenverteilung
{
	$output=null;
	$sql='SELECT colname,race,count(acctid) AS c FROM accounts LEFT JOIN races ON race = id GROUP BY race ORDER BY c DESC';
	$res = db_query($sql);
	$str_out.='`c`^Die Einwohner der Stadt, unterteilt nach Rassen`0`c`n`n<table cellpadding="2" cellspacing="1" bgcolor="#999999"><tr class="trhead"><th>Rang</th><th>Rasse</th><th width="60">Anz.</th>';
	$rank = 1;
	while($r = db_fetch_assoc($res)) {
		$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
		$str_out .= '<tr class="'.$bgclass.'"><td>'.$rank.'.</td><td>'.
		'`b'.($r['colname']?$r['colname']:'`&Unbekannt').'`b</td>
		<td align="center">'.$r['c'].'</td></tr>';
		$rank++;
	}
	$str_out .= '</table>`c';
	output($str_out,true);
}

else if ($_GET['op']=='symp_old') //Sympathieliste des Vormonats
{
	$output=null;
	$symp_list=utf8_unserialize(getsetting('old_symp_vote_list',''));
	if($symp_list=='')
	{
		output('Es gibt keine anzeigbaren Ergebnisse.');
	}
	else
	{
		$str_out.='`c`b`^Das waren im Vormonat die Helden mit meisten Sympathiepunkten: `0`b`c
		`n<table cellpadding="2" cellspacing="1" bgcolor="#999999">
		<tr class="trhead"><th>Rang</th><th>Name</th><th width="60">Sympathie</th>';
		$rank=0;
		foreach($symp_list as $symp)
		{
			$rank++;
			$str_out.='<tr class="'.($rank%2?'trdark':'trlight').'">
			<td>'.$rank.'</td>
			<td><img src="./images/'.($symp['sex']?'female.gif" alt="weiblich"':'male.gif" alt="männlich"').'> '.$symp['name'].'</td>
			<td align="right">'.$symp['sympathy'].' Punkte</td>
			</tr>';
		}
		$str_out .= '</table>`c`nGezählt werden nur die ersten 50 Plätze.';
		output($str_out,true);
	}
}

else if ($_GET["op"] == "wasterofgolds") //Goldverschwender im Goldschrein (Marktplatz)
{
	$countsql='SELECT count(*) AS c FROM account_extra_info WHERE wastedgold>0';
	$sql = 'SELECT name, wastedgold AS data1, a.acctid, a.login
	FROM account_extra_info aei
	LEFT JOIN accounts a ON a.acctid=aei.acctid
	WHERE wastedgold>0
	ORDER BY wastedgold '.$order.', a.dragonkills '.$order_rev.', aei.acctid '.$order.'
	LIMIT '.$limit;
	$adverb = ($_GET['subop'] == 'least'?'geizigsten':'größten');
	$title = 'Die '.$adverb.' Goldverschwender';
	$headers = array('Größte verschwen...geopferte Goldmenge');
	$tags = array('Gold');
	$none = 'Es gibt noch niemanden, der sein Gold lieber opfert als es zum Fenster hinauszuwerfen.';
	display_table($title, $sql, $none, false, $headers, $tags);
}

else
{
	$output=null;
	output(get_title('`IRuhmeshalle').'Durch eine nach innen aufschwingende Flügeltür gelangt man in eine große, runde Halle, die von einer wuchtigen Kuppel überspannt wird. Die verglasten Oberlichter lassen Sonne wie Mond herein scheinen, um das ornamentale Mosaik am Boden und die langen schmalen Tafeln, die in Goldrahmen eingefasst sind, an den Wänden zu beleuchten. Sollte das Licht einmal nicht ausreichen, so werden die Magier der Stadt beauftragt, die Aufschriften mit ausreichend Licht zu versorgen. In diesem domartig anmutenden Gebäude sind akribische Aufzeichnungen über die Ruhmes- und Schandtaten der Bürger zu finden, offenbar magisch in den Carrara-Marmor geschlagen, denn die Namen und Ziffern verändern sich mit dem Lauf der Zeit. Die Obrigkeit hat in dieser Halle nicht an Gold gespart und gibt für die Pflege dieser Hallen unzählige Steuergelder aus, um den Glanz zu erhalten. Einzelne, besonders herausragende Taten werden mit einer kleinen Statue geehrt, die zusammen einen eigenen Kreis um das Mosaik bilden.`n`n');
}
page_footer();
?>