<?php
/**
* news.php:	Anzeige der aktuellen Spielernews
* @author LOGD-Core, modified by Drachenserver-Team
* @version DS-E V/2
*/

require_once('common.php');

function datum($timestamp) //Behelf, damit das wieder deutsch ausgegeben wird. Author: Taemor
{
	$tages = array("Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag");
	$tag = strftime("%w",$timestamp);

	$tag1 = strftime("%d",$timestamp);

	$monate = array("01"=>"Januar","02"=>"Februar","03"=>"März","04"=>"April","05"=>"Mai","06"=>"Juni","07"=>"Juli","08"=>"August","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Dezember");
	$monat = strftime("%m",$timestamp);

	$jahr = strftime("%Y",$timestamp);

	$datum = $tages[$tag].", ".$tag1.". ".$monate[$monat]." ".$jahr;
	return $datum;
}

if ($session['user']['imprisoned']>0) {
	redirect("prison.php");
}

if ($session['user']['loggedin']) {
	checkday();
}

$newsperpage=30;

page_header('Neuigkeiten aus '.getsetting('townname','Atrahor'));

if ($access_control->su_check(access_control::SU_RIGHT_NEWS))
{
	output('`0<form action="news.php" method="POST">
			[Admin] Meldung manuell eingeben?
			<input name="meldung" size="40">
			<input type="submit" class="button" value="Eintragen">
			</form>`n`n');
	addnav('','news.php');

	if (isset($_POST['meldung']) && !empty($_POST['meldung']))
	{
		$sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('".db_real_escape_string($_POST['meldung'])."',NOW(),0)";
		db_query($sql);
		$_POST['meldung']='';
	}
}

if ($access_control->su_check(access_control::SU_RIGHT_SEARCH_NEWS))
{
	$str_search=((isset($_POST['search']) && $_POST['search']>'')
		?stripslashes($_POST['search'])
		:((isset($_GET['search']) && $_GET['search']>'')
			?stripslashes($_GET['search'])
			:''));
	rawoutput('<form action="news.php?op=search&amp;page=1" method="POST">
	Nach etwas in den News suchen:
	<input name="search" size=50 value="'.$str_search.'">
	<input class="button" type="submit" value="Suchen">
	</form>');
	addnav('','news.php?op=search&page=1');
}

if (!$session['user']['loggedin'])
{
	addnav('Login', 'index.php');
}
elseif ($session['user']['alive'])
{
	addnav('Zurück');
	addnav('d?Zum Stadtzentrum','village.php');
	addnav('M?Zum Marktplatz','market.php');
}
else
{
	addnav(words_by_sex('Du bist tot [Jim|Jane]!'));
	addnav('S?Land der Schatten','shades.php');
}

//Neujahrs-Special
if ( ($session['user']['acctid'] && (time() - mktime(0,0,0,1,1,date('Y'))) <= (5 * 60) ) )
{
	if(isset($_REQUEST['new_years_special']))
	{
		$sql = "
			SELECT
				*
			FROM
				`user_online_newyear`
			WHERE
				`acctid`	= '" . $session['user']['acctid'] . "'
		";
		$res = db_query($sql);
		if (db_num_rows($res))
		{
			output("`@Du bist bereits im Neujahrsbonus eingetragen! Danke für's Spielen und gute Nacht!`n");
		}
		else
		{
			$sql = "
				INSERT INTO
					`user_online_newyear`
				SET
					`acctid` = '" . $session['user']['acctid'] . "'
			";
			db_query($sql);
			$subject = "Neujahrsgrüße";
			$body = '
				Liebe' . (!$session['sex']?'r':'') . ' ' . $session['user']['login'] . '`n
				wir bedanken uns für deine Treue zum Spiel und wünschen dir ein glückliches Jahr ' . date('Y') . "!`n
				Dein Drachenserver-Team`n
				PS: Du bist nun in die Liste für den Neujahrsbonus eingetragen.
			";
			systemmail($session['user']['acctid'],$subject,$body);
			addhistory('`^Hielt Atrahor auch zum Jahreswechsel ' . (date('Y')-1) . '/' . date('Y') . ' die Treue!');
			
            output('<hr>'.get_title('Das Neujahrsgeschenk').'`c`b`yWir danken Dir für Deine Treue und wünschen dir ein gesundes und erfolgreiches Jahr '.date('Y').'`b`c`n<hr>');
		}
	}
	else 
	{
		$str_out = '<hr>'.get_title('Das Neujahrsgeschenk').'`c`b`tWie jedes Jahr schickt es sich auch heute an eine Tradition aufleben zu lassen. Jeder Bewohner unserer Stadt, der zum Silvesterfeste und dem anstehenden Jahreswechsel bei uns weilt, soll für seine beispiellose Treue belohnt werden. Du bist ein solch treues Wesen. Unser Dank sei dir gewiss. `yNun klicke auf den folgenden Button, um Dich für Dein Geschenk zu registrieren. Es wird Dich in den nächsten Tagen erreichen!`n
		<input type="button" name="new_years_special" value="Das Neujahrgeschenk..." onClick="window.location.href=\'news.php?new_years_special=1\' ">`0`b`c`n<hr>';
		addnav('','news.php?new_years_special=1');
		output($str_out);
	}
}


//addnav('Information');
//addnav('Über das Spiel','about.php');

if ($_GET['op']=='')
{
	$offset = (int)$_GET['offset'];

	$timestamp = time() - 86400 * $offset;
	$date_from = date('Y-m-d',$timestamp);


	$sql = 'SELECT count(*) AS c FROM news WHERE newsdate="'.$date_from.'" AND accountid NOT IN ('.CIgnore::ignore_sql(CIgnore::IGNO_BIO).')';
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$totaltoday=$row['c'];

	$pageoffset = (int)$_GET['page'];

	if ($pageoffset>0)
	{
		$pageoffset--;
	}

	$pageoffset*=$newsperpage;

	$sql = 'SELECT * FROM news WHERE newsdate="'.$date_from.'"  AND accountid NOT IN ('.CIgnore::ignore_sql(CIgnore::IGNO_BIO).') ORDER BY newsid DESC LIMIT '.$pageoffset.','.$newsperpage;
	$result = db_query($sql);

	//$date = strftime('%A, %e. %B %Y',$timestamp);
	$date=datum($timestamp);

	output(get_title("`n`INeuigkeiten für $date".($totaltoday>$newsperpage?" (Meldungen ".($pageoffset+1)." - ".min($pageoffset+$newsperpage,$totaltoday)." von $totaltoday)":"")));

	$int_num_rows = db_num_rows($result);
	if ($int_num_rows == 0)
	{
		output('`c`K-=-`h=-=`K-=-`h=-=`K-=-`h=-=`K-=-`0`c');
		output(get_title('`y Es ist nichts Erwähnenswertes passiert. Alles in allem bisher ein langweiliger Tag.'));
	}
	else
	{
		$str_output = '`0';
		for ($i=0;$i<$int_num_rows;$i++)
		{
			$row = db_fetch_assoc($result);
			$str_output .= '`c`K-=-`h=-=`K-=-`h=-=`K-=-`h=-=`K-=-`0`c';
			if ($access_control->su_check(access_control::SU_RIGHT_NEWS))
			{
				$str_output .= '[ '.create_lnk('Del','superuser.php?op=newsdelete&newsid='.$row['newsid'].'&return='.URLEncode($_SERVER['REQUEST_URI'])).' ]&nbsp;';
			}
			$str_output .= $row['newstext'].'`n`0';
		}
		output($str_output, true);
	}

	output('`c`K-=-`h=-=`K-=-`h=-=`K-=-`h=-=`K-=-`0`c');

	addnav('Vergangene Tage');
	addnav('z?Tag zurück','news.php?offset='.($offset+1));
	if ($offset>0)
	{
		addnav('v?Tag vor','news.php?offset='.($offset-1));
	}
	page_nav('news.php?offset='.$offset,$totaltoday,$newsperpage);
}

else if ($_GET['op']=='search')
{
	addnav('`nNeueste Meldungen','news.php');
	if ((isset($_POST['search']) && mb_strlen($_POST['search'])>2) || (isset($_GET['search']) && mb_strlen($_GET['search'])>2))
	{
		$newsperpage=15;
		$_GET['search']=(isset($_POST['search'])?rawurlencode($_POST['search']):$_GET['search']);
		#$days=($_GET['days']);
		$lastweek=date("Y-m-d",time()-(7*24*3600));
		$page=max((int)$_GET['page'],1);
		$nrnews=db_fetch_assoc(db_query("SELECT count(*) as c
			FROM news
			WHERE newstext LIKE '%".db_real_escape_string(rawurldecode($_GET['search']))."%'
			 AND accountid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_BIO).")
			AND newsdate>'".$lastweek."'"));
		$newsresult=db_query("SELECT *
			FROM news
			WHERE newstext LIKE '%".db_real_escape_string(rawurldecode($_GET['search']))."%'
			AND newsdate>'".$lastweek."'
			 AND accountid NOT IN (".CIgnore::ignore_sql(CIgnore::IGNO_BIO).")
			ORDER BY newsdate DESC
			LIMIT ".(($page-1)*$newsperpage).",".$newsperpage);
		$newsfound="`n`c".$nrnews['c']." gefundene Nachrichten innerhalb der letzten sieben Tage :`c`n";
		if (db_num_rows($newsresult)==0)
		{
			$newsfound.="`n`n`c`iLeider keine entsprechenden Nachrichten gefunden`i`c`n`n";
		}
		else
		{
			while($newsrow=db_fetch_assoc($newsresult))
			{
				$newsfound.="`c`K-=-`h=-=`K-=-`h=-=`K-=-`h=-=`K-=-`0`c";
				if ($access_control->su_check(access_control::SU_RIGHT_NEWS))
				{
					$ndate=explode("-",$newsrow['newsdate']);
					$newsfound.=$ndate[2].".".$ndate[1].".".$ndate[0]."`n";
					$newsfound.='`0[ '.create_lnk('Del','superuser.php?op=newsdelete&newsid='.$newsrow['newsid'].'&return='.URLEncode($_SERVER['REQUEST_URI'])).' ]&nbsp;';
				}
				$newsfound.=$newsrow['newstext'].'`n`0';
			}
		}
		output($newsfound);
		page_nav('news.php?op=search&search='.rawurlencode($str_search),$nrnews['c'],$newsperpage);
	}
	else
	{
		output("`n`n`c`iBitte einen längeren Suchstring eingeben (min 3 Zeichen)`i`c");
	}
}

page_footer();
?>